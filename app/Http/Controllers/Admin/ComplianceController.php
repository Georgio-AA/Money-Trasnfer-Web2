<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transfer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ComplianceController extends Controller
{
    protected $alertsFile;
    
    public function __construct()
    {
        $this->alertsFile = storage_path('app/compliance_alerts.json');
        
        if (!File::exists($this->alertsFile)) {
            File::put($this->alertsFile, json_encode([], JSON_PRETTY_PRINT));
        }
    }

    public function index()
    {
        // High-value transactions (>$5000)
        $highValueTransfers = Transfer::with(['sender', 'beneficiary'])
            ->where('amount', '>', 5000)
            ->whereIn('status', ['pending', 'processing', 'completed'])
            ->latest()
            ->paginate(20);

        // Suspicious patterns - multiple transfers in short time
        $suspiciousUsers = $this->getSuspiciousUsers();

        // Users exceeding daily limits
        $limitExceeded = $this->getUsersExceedingLimits();

        // Compliance statistics
        $stats = [
            'high_value_count' => Transfer::where('amount', '>', 5000)
                ->whereDate('created_at', today())
                ->count(),
            'flagged_users' => count($suspiciousUsers),
            'pending_review' => Transfer::where('amount', '>', 10000)
                ->where('status', 'pending')
                ->count(),
            'limit_violations' => count($limitExceeded),
        ];

        // Recent alerts
        $alerts = $this->getRecentAlerts();

        return view('admin.compliance', compact(
            'highValueTransfers',
            'suspiciousUsers',
            'limitExceeded',
            'stats',
            'alerts'
        ));
    }

    public function flagTransaction(Request $request, Transfer $transfer)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $alert = [
            'id' => uniqid(),
            'type' => 'transaction_flagged',
            'transfer_id' => $transfer->id,
            'sender_id' => $transfer->sender_id,
            'beneficiary_id' => $transfer->beneficiary_id,
            'amount' => $transfer->amount,
            'reason' => $request->reason,
            'flagged_by' => session('user.id'),
            'flagged_at' => now()->toDateTimeString(),
            'status' => 'pending_review',
        ];

        $this->addAlert($alert);

        return back()->with('success', 'Transaction has been flagged for review.');
    }

    public function resolveAlert(Request $request, $alertId)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'notes' => 'nullable|string|max:500',
        ]);

        $alerts = $this->getAllAlerts();
        
        foreach ($alerts as &$alert) {
            if ($alert['id'] === $alertId) {
                $alert['status'] = 'resolved';
                $alert['action'] = $request->action;
                $alert['resolution_notes'] = $request->notes;
                $alert['resolved_by'] = session('user.id');
                $alert['resolved_at'] = now()->toDateTimeString();
                break;
            }
        }

        File::put($this->alertsFile, json_encode($alerts, JSON_PRETTY_PRINT));

        return back()->with('success', 'Alert has been resolved.');
    }

    public function auditLog(Request $request)
    {
        $query = Transfer::with(['sender', 'beneficiary'])
            ->whereIn('status', ['completed', 'failed', 'cancelled']);

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where(function($q) use ($request) {
                $q->where('sender_id', $request->user_id)
                  ->orWhere('beneficiary_id', $request->user_id);
            });
        }

        // Filter by amount range
        if ($request->filled('min_amount')) {
            $query->where('amount', '>=', $request->min_amount);
        }
        if ($request->filled('max_amount')) {
            $query->where('amount', '<=', $request->max_amount);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transfers = $query->latest()->paginate(50);
        
        $users = User::select('id', 'name', 'email')->orderBy('name')->get();

        return view('admin.audit-log', compact('transfers', 'users'));
    }

    protected function getSuspiciousUsers()
    {
        // Users with more than 5 transfers in last 24 hours
        $suspiciousUserIds = Transfer::where('created_at', '>=', now()->subDay())
            ->groupBy('sender_id')
            ->havingRaw('COUNT(*) > 5')
            ->pluck('sender_id');

        return User::whereIn('id', $suspiciousUserIds)
            ->get()
            ->map(function($user) {
                $user->transfer_count = Transfer::where('sender_id', $user->id)
                    ->where('created_at', '>=', now()->subDay())
                    ->count();
                return $user;
            });
    }

    protected function getUsersExceedingLimits()
    {
        $settings = $this->getSettings();
        $dailyLimit = $settings['daily_transfer_limit'] ?? 50000;

        $exceedingUserIds = Transfer::whereDate('created_at', today())
            ->where('status', 'completed')
            ->groupBy('sender_id')
            ->havingRaw('SUM(amount) > ?', [$dailyLimit])
            ->pluck('sender_id');

        return User::whereIn('id', $exceedingUserIds)
            ->get()
            ->map(function($user) {
                $user->daily_total = Transfer::where('sender_id', $user->id)
                    ->whereDate('created_at', today())
                    ->where('status', 'completed')
                    ->sum('amount');
                return $user;
            });
    }

    protected function getSettings()
    {
        $settingsFile = storage_path('app/admin_settings.json');
        
        if (File::exists($settingsFile)) {
            return json_decode(File::get($settingsFile), true);
        }

        return [
            'daily_transfer_limit' => 50000,
        ];
    }

    protected function addAlert($alert)
    {
        $alerts = $this->getAllAlerts();
        array_unshift($alerts, $alert);
        
        // Keep only last 100 alerts
        $alerts = array_slice($alerts, 0, 100);
        
        File::put($this->alertsFile, json_encode($alerts, JSON_PRETTY_PRINT));
    }

    protected function getAllAlerts()
    {
        if (File::exists($this->alertsFile)) {
            return json_decode(File::get($this->alertsFile), true) ?? [];
        }
        return [];
    }

    protected function getRecentAlerts()
    {
        $alerts = $this->getAllAlerts();
        return array_slice($alerts, 0, 10);
    }
}
