<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transfer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class FraudDetectionController extends Controller
{
    private $fraudAlertsFile;
    private $fraudRulesFile;
    private $blockedEntitiesFile;

    public function __construct()
    {
        $storagePath = storage_path('app/private');
        if (!File::exists($storagePath)) {
            File::makeDirectory($storagePath, 0755, true);
        }
        
        $this->fraudAlertsFile = $storagePath . '/fraud_alerts.json';
        $this->fraudRulesFile = $storagePath . '/fraud_rules.json';
        $this->blockedEntitiesFile = $storagePath . '/blocked_entities.json';

        // Initialize files if they don't exist
        if (!File::exists($this->fraudAlertsFile)) {
            File::put($this->fraudAlertsFile, json_encode([], JSON_PRETTY_PRINT));
        }
        if (!File::exists($this->fraudRulesFile)) {
            $this->initializeFraudRules();
        }
        if (!File::exists($this->blockedEntitiesFile)) {
            File::put($this->blockedEntitiesFile, json_encode([
                'users' => [],
                'ips' => [],
                'devices' => [],
                'emails' => [],
                'phones' => []
            ], JSON_PRETTY_PRINT));
        }
    }

    public function index()
    {
        // Get fraud statistics
        $stats = $this->getFraudStats();

        // Get recent fraud alerts
        $alerts = $this->getRecentAlerts(20);

        // Get active fraud rules
        $rules = $this->getActiveRules();

        // Get blocked entities
        $blocked = $this->getBlockedEntities();
        
        // Enrich blocked users with user details
        foreach ($blocked['users'] as &$userBlock) {
            $user = User::find($userBlock['value']);
            if ($user) {
                $userBlock['name'] = $user->name;
                $userBlock['email'] = $user->email;
            }
        }

        // High-risk transfers (fraud score > 70)
        $highRiskTransfers = Transfer::with('user')
            ->where('fraud_score', '>', 70)
            ->whereIn('status', ['pending', 'processing'])
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.fraud-detection', compact('stats', 'alerts', 'rules', 'blocked', 'highRiskTransfers'));
    }

    private function getFraudStats()
    {
        $today = now()->startOfDay();
        $weekAgo = now()->subDays(7);

        return [
            'total_alerts' => count($this->getAllAlerts()),
            'alerts_today' => count(array_filter($this->getAllAlerts(), function($alert) use ($today) {
                return strtotime($alert['created_at']) >= $today->timestamp;
            })),
            'high_risk_transfers' => Transfer::where('fraud_score', '>', 70)
                ->whereIn('status', ['pending', 'processing'])
                ->count(),
            'blocked_users' => count($this->getBlockedEntities()['users']),
            'blocked_ips' => count($this->getBlockedEntities()['ips']),
            'fraudulent_amount' => Transfer::where('status', 'fraud_blocked')
                ->where('created_at', '>=', $weekAgo)
                ->sum('amount'),
            'prevented_transactions' => Transfer::where('status', 'fraud_blocked')
                ->where('created_at', '>=', $weekAgo)
                ->count(),
        ];
    }

    public function calculateFraudScore($transferId)
    {
        $transfer = Transfer::with('user')->findOrFail($transferId);
        $score = 0;
        $reasons = [];

        // Rule 1: Velocity Check - Multiple transfers in short time
        $recentTransfers = Transfer::where('user_id', $transfer->user_id)
            ->where('created_at', '>=', now()->subHours(24))
            ->count();
        
        if ($recentTransfers > 5) {
            $score += 30;
            $reasons[] = "High velocity: {$recentTransfers} transfers in 24 hours";
        } elseif ($recentTransfers > 3) {
            $score += 15;
            $reasons[] = "Medium velocity: {$recentTransfers} transfers in 24 hours";
        }

        // Rule 2: Large amount for new account
        $accountAge = now()->diffInDays($transfer->user->created_at);
        if ($accountAge < 7 && $transfer->amount > 1000) {
            $score += 40;
            $reasons[] = "New account ({$accountAge} days old) with large transfer (${$transfer->amount})";
        } elseif ($accountAge < 30 && $transfer->amount > 5000) {
            $score += 30;
            $reasons[] = "Young account with very large transfer";
        }

        // Rule 3: Unusual amount pattern
        $avgAmount = Transfer::where('user_id', $transfer->user_id)
            ->where('status', 'completed')
            ->avg('amount');
        
        if ($avgAmount > 0 && $transfer->amount > ($avgAmount * 5)) {
            $score += 25;
            $reasons[] = "Amount 5x higher than user's average (${$avgAmount})";
        }

        // Rule 4: Multiple failed attempts
        $failedAttempts = Transfer::where('user_id', $transfer->user_id)
            ->where('status', 'failed')
            ->where('created_at', '>=', now()->subDays(7))
            ->count();
        
        if ($failedAttempts >= 3) {
            $score += 20;
            $reasons[] = "{$failedAttempts} failed transfer attempts in last 7 days";
        }

        // Rule 5: Unusual time (late night transactions)
        $hour = (int)$transfer->created_at->format('H');
        if ($hour >= 0 && $hour <= 4) {
            $score += 10;
            $reasons[] = "Transfer initiated during unusual hours (late night)";
        }

        // Rule 6: Round numbers (common in fraud)
        if ($transfer->amount == round($transfer->amount, -2) && $transfer->amount >= 1000) {
            $score += 5;
            $reasons[] = "Suspiciously round amount: ${$transfer->amount}";
        }

        // Rule 7: Rapid beneficiary addition
        if ($transfer->beneficiary_id) {
            $beneficiary = \App\Models\Beneficiary::find($transfer->beneficiary_id);
            if ($beneficiary) {
                $beneficiaryAge = now()->diffInMinutes($beneficiary->created_at);
                if ($beneficiaryAge < 30) {
                    $score += 20;
                    $reasons[] = "Beneficiary added {$beneficiaryAge} minutes ago";
                }
            }
        }

        // Rule 8: Check if user is in watch list
        $blocked = $this->getBlockedEntities();
        if (in_array($transfer->user_id, $blocked['users'])) {
            $score += 100;
            $reasons[] = "User is on blocked list";
        }

        // Cap score at 100
        $score = min($score, 100);

        // Update transfer with fraud score
        $transfer->fraud_score = $score;
        $transfer->fraud_reasons = json_encode($reasons);
        $transfer->save();

        // Create alert if high risk
        if ($score >= 70) {
            $this->createFraudAlert($transfer, $score, $reasons, 'high');
        } elseif ($score >= 50) {
            $this->createFraudAlert($transfer, $score, $reasons, 'medium');
        }

        return [
            'score' => $score,
            'risk_level' => $this->getRiskLevel($score),
            'reasons' => $reasons
        ];
    }

    private function getRiskLevel($score)
    {
        if ($score >= 80) return 'critical';
        if ($score >= 60) return 'high';
        if ($score >= 40) return 'medium';
        return 'low';
    }

    private function createFraudAlert($transfer, $score, $reasons, $severity)
    {
        $alerts = $this->getAllAlerts();

        $alert = [
            'id' => uniqid('alert_'),
            'transfer_id' => $transfer->id,
            'user_id' => $transfer->user_id,
            'user_name' => $transfer->user->name ?? 'Unknown',
            'user_email' => $transfer->user->email ?? 'Unknown',
            'amount' => $transfer->amount,
            'fraud_score' => $score,
            'severity' => $severity,
            'reasons' => $reasons,
            'status' => 'pending', // pending, reviewed, false_positive, confirmed_fraud
            'created_at' => now()->toDateTimeString(),
            'reviewed_at' => null,
            'reviewed_by' => null,
        ];

        $alerts[] = $alert;

        // Keep only last 1000 alerts
        if (count($alerts) > 1000) {
            $alerts = array_slice($alerts, -1000);
        }

        File::put($this->fraudAlertsFile, json_encode($alerts, JSON_PRETTY_PRINT));

        return $alert;
    }

    public function reviewAlert(Request $request, $alertId)
    {
        $validated = $request->validate([
            'action' => 'required|in:approve,block,false_positive',
            'notes' => 'nullable|string|max:500'
        ]);

        $alerts = $this->getAllAlerts();
        $alertIndex = array_search($alertId, array_column($alerts, 'id'));

        if ($alertIndex === false) {
            return back()->with('error', 'Alert not found');
        }

        $alert = &$alerts[$alertIndex];
        $transfer = Transfer::find($alert['transfer_id']);
        $user = session('user');

        if ($validated['action'] === 'approve') {
            $alert['status'] = 'false_positive';
            // Allow transfer to proceed
            if ($transfer && in_array($transfer->status, ['pending', 'processing'])) {
                $transfer->fraud_score = 0;
                $transfer->save();
            }
            $message = 'Alert marked as false positive. Transfer approved.';
        } elseif ($validated['action'] === 'block') {
            $alert['status'] = 'confirmed_fraud';
            // Block transfer and user
            if ($transfer) {
                $transfer->status = 'fraud_blocked';
                $transfer->save();
            }
            $this->blockEntity('user', $alert['user_id'], $validated['notes'] ?? 'Confirmed fraudulent activity');
            $message = 'Alert confirmed. User blocked and transfer cancelled.';
        } else {
            $alert['status'] = 'false_positive';
            $message = 'Alert marked as false positive.';
        }

        $alert['reviewed_at'] = now()->toDateTimeString();
        $alert['reviewed_by'] = $user['name'] ?? 'Admin';
        $alert['notes'] = $validated['notes'] ?? null;

        File::put($this->fraudAlertsFile, json_encode($alerts, JSON_PRETTY_PRINT));

        return back()->with('success', $message);
    }

    public function blockEntity(string $type, $value, string $reason)
    {
        $blocked = $this->getBlockedEntities();

        $entry = [
            'value' => $value,
            'reason' => $reason,
            'blocked_at' => now()->toDateTimeString(),
            'blocked_by' => session('user.name') ?? 'System'
        ];

        if (!in_array($value, array_column($blocked[$type . 's'] ?? [], 'value'))) {
            $blocked[$type . 's'][] = $entry;
            File::put($this->blockedEntitiesFile, json_encode($blocked, JSON_PRETTY_PRINT));
        }

        return true;
    }

    public function blockUser(Request $request, $userId)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        $user = User::find($userId);
        
        if (!$user) {
            return back()->with('error', 'User not found');
        }

        // Block user in database
        $user->status = 'blocked';
        $user->save();

        // Add to blocked entities list
        $this->blockEntity('user', $userId, $validated['reason']);

        // Cancel all pending/processing transfers for this user (sender_id column)
        Transfer::where('sender_id', $userId)
            ->whereIn('status', ['pending', 'processing'])
            ->update(['status' => 'fraud_blocked']);

        return back()->with('success', "User {$user->name} has been blocked successfully.");
    }

    public function unblockEntity(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:user,ip,device,email,phone',
            'value' => 'required'
        ]);

        $blocked = $this->getBlockedEntities();
        $type = $validated['type'] . 's';

        $blocked[$type] = array_filter($blocked[$type], function($item) use ($validated) {
            return $item['value'] != $validated['value'];
        });

        // Re-index array
        $blocked[$type] = array_values($blocked[$type]);

        File::put($this->blockedEntitiesFile, json_encode($blocked, JSON_PRETTY_PRINT));

        // If unblocking a user, update their status in database
        if ($validated['type'] === 'user') {
            $user = User::find($validated['value']);
            if ($user) {
                $user->status = 'active';
                $user->save();
            }
        }

        return back()->with('success', ucfirst($validated['type']) . ' unblocked successfully');
    }

    public function addRule(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'required|string|max:255',
            'condition' => 'required|string',
            'score_points' => 'required|integer|min:1|max:100',
            'enabled' => 'boolean'
        ]);

        $rules = $this->getAllRules();

        $rule = [
            'id' => uniqid('rule_'),
            'name' => $validated['name'],
            'description' => $validated['description'],
            'condition' => $validated['condition'],
            'score_points' => $validated['score_points'],
            'enabled' => $validated['enabled'] ?? true,
            'created_at' => now()->toDateTimeString(),
            'triggers' => 0
        ];

        $rules[] = $rule;
        File::put($this->fraudRulesFile, json_encode($rules, JSON_PRETTY_PRINT));

        return back()->with('success', 'Fraud rule added successfully');
    }

    public function toggleRule($ruleId)
    {
        $rules = $this->getAllRules();
        $ruleIndex = array_search($ruleId, array_column($rules, 'id'));

        if ($ruleIndex !== false) {
            $rules[$ruleIndex]['enabled'] = !$rules[$ruleIndex]['enabled'];
            File::put($this->fraudRulesFile, json_encode($rules, JSON_PRETTY_PRINT));
            return back()->with('success', 'Rule updated successfully');
        }

        return back()->with('error', 'Rule not found');
    }

    public function deleteRule($ruleId)
    {
        $rules = $this->getAllRules();
        $rules = array_filter($rules, function($rule) use ($ruleId) {
            return $rule['id'] !== $ruleId;
        });

        File::put($this->fraudRulesFile, json_encode(array_values($rules), JSON_PRETTY_PRINT));

        return back()->with('success', 'Rule deleted successfully');
    }

    // Helper methods
    private function getAllAlerts()
    {
        if (File::exists($this->fraudAlertsFile)) {
            return json_decode(File::get($this->fraudAlertsFile), true) ?: [];
        }
        return [];
    }

    private function getRecentAlerts($limit = 20)
    {
        $alerts = $this->getAllAlerts();
        usort($alerts, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });
        return array_slice($alerts, 0, $limit);
    }

    private function getAllRules()
    {
        if (File::exists($this->fraudRulesFile)) {
            return json_decode(File::get($this->fraudRulesFile), true) ?: [];
        }
        return [];
    }

    private function getActiveRules()
    {
        return array_filter($this->getAllRules(), function($rule) {
            return $rule['enabled'] ?? false;
        });
    }

    private function getBlockedEntities()
    {
        if (File::exists($this->blockedEntitiesFile)) {
            return json_decode(File::get($this->blockedEntitiesFile), true) ?: [
                'users' => [],
                'ips' => [],
                'devices' => [],
                'emails' => [],
                'phones' => []
            ];
        }
        return [
            'users' => [],
            'ips' => [],
            'devices' => [],
            'emails' => [],
            'phones' => []
        ];
    }

    private function initializeFraudRules()
    {
        $defaultRules = [
            [
                'id' => 'rule_velocity_high',
                'name' => 'High Velocity Transactions',
                'description' => 'More than 5 transactions in 24 hours',
                'condition' => 'transfers_24h > 5',
                'score_points' => 30,
                'enabled' => true,
                'created_at' => now()->toDateTimeString(),
                'triggers' => 0
            ],
            [
                'id' => 'rule_new_account_large',
                'name' => 'New Account Large Transfer',
                'description' => 'Account less than 7 days old with transfer > $1000',
                'condition' => 'account_age < 7 AND amount > 1000',
                'score_points' => 40,
                'enabled' => true,
                'created_at' => now()->toDateTimeString(),
                'triggers' => 0
            ],
            [
                'id' => 'rule_unusual_amount',
                'name' => 'Unusual Amount Pattern',
                'description' => 'Transfer amount 5x higher than user average',
                'condition' => 'amount > avg_amount * 5',
                'score_points' => 25,
                'enabled' => true,
                'created_at' => now()->toDateTimeString(),
                'triggers' => 0
            ],
            [
                'id' => 'rule_failed_attempts',
                'name' => 'Multiple Failed Attempts',
                'description' => '3 or more failed transfers in 7 days',
                'condition' => 'failed_transfers_7d >= 3',
                'score_points' => 20,
                'enabled' => true,
                'created_at' => now()->toDateTimeString(),
                'triggers' => 0
            ],
            [
                'id' => 'rule_late_night',
                'name' => 'Late Night Activity',
                'description' => 'Transactions between midnight and 4 AM',
                'condition' => 'hour >= 0 AND hour <= 4',
                'score_points' => 10,
                'enabled' => true,
                'created_at' => now()->toDateTimeString(),
                'triggers' => 0
            ]
        ];

        File::put($this->fraudRulesFile, json_encode($defaultRules, JSON_PRETTY_PRINT));
    }
}
