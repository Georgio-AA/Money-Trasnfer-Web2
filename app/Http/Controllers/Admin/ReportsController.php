<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transfer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'month'); // day, week, month, year
        $startDate = $this->getStartDate($period);
        $endDate = now();

        // Transaction Reports
        $transactionStats = $this->getTransactionStats($startDate, $endDate);
        
        // Revenue Reports
        $revenueStats = $this->getRevenueStats($startDate, $endDate);
        
        // User Activity Reports
        $userStats = $this->getUserStats($startDate, $endDate);
        
        // Transfer Speed Distribution
        $speedDistribution = $this->getSpeedDistribution($startDate, $endDate);
        
        // Top Routes (Currency Pairs)
        $topRoutes = $this->getTopRoutes($startDate, $endDate);
        
        // Daily Transaction Trends (for chart)
        $dailyTrends = $this->getDailyTrends($startDate, $endDate);
        
        // Support Ticket Stats
        $supportStats = $this->getSupportStats($startDate, $endDate);
        
        // User Feedback Stats
        $feedbackStats = $this->getFeedbackStats($startDate, $endDate);

        return view('admin.reports.index', compact(
            'period',
            'transactionStats',
            'revenueStats',
            'userStats',
            'speedDistribution',
            'topRoutes',
            'dailyTrends',
            'supportStats',
            'feedbackStats',
            'startDate',
            'endDate'
        ));
    }

    private function getStartDate($period)
    {
        return match($period) {
            'day' => now()->startOfDay(),
            'week' => now()->startOfWeek(),
            'month' => now()->startOfMonth(),
            'year' => now()->startOfYear(),
            'all' => Carbon::parse('2020-01-01'),
            default => now()->startOfMonth(),
        };
    }

    private function getTransactionStats($startDate, $endDate)
    {
        return [
            'total_transactions' => Transfer::whereBetween('created_at', [$startDate, $endDate])->count(),
            'completed' => Transfer::whereBetween('created_at', [$startDate, $endDate])->where('status', 'completed')->count(),
            'pending' => Transfer::whereBetween('created_at', [$startDate, $endDate])->where('status', 'pending')->count(),
            'processing' => Transfer::whereBetween('created_at', [$startDate, $endDate])->where('status', 'processing')->count(),
            'failed' => Transfer::whereBetween('created_at', [$startDate, $endDate])->where('status', 'failed')->count(),
            'cancelled' => Transfer::whereBetween('created_at', [$startDate, $endDate])->where('status', 'cancelled')->count(),
            'success_rate' => $this->calculateSuccessRate($startDate, $endDate),
            'avg_processing_time' => $this->getAvgProcessingTime($startDate, $endDate),
        ];
    }

    private function calculateSuccessRate($startDate, $endDate)
    {
        $total = Transfer::whereBetween('created_at', [$startDate, $endDate])->count();
        if ($total == 0) return 0;
        
        $completed = Transfer::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->count();
        
        return round(($completed / $total) * 100, 2);
    }

    private function getAvgProcessingTime($startDate, $endDate)
    {
        $avg = Transfer::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->whereNotNull('completed_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, created_at, completed_at)) as avg_time')
            ->first();
        
        return $avg->avg_time ? round($avg->avg_time, 2) : 0;
    }

    private function getRevenueStats($startDate, $endDate)
    {
        $completed = Transfer::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed');

        return [
            'total_revenue' => $completed->sum('transfer_fee'),
            'total_volume' => $completed->sum('amount'),
            'avg_transaction_value' => $completed->avg('amount'),
            'total_fees_collected' => $completed->sum('transfer_fee'),
            'transaction_count' => $completed->count(),
        ];
    }

    private function getUserStats($startDate, $endDate)
    {
        return [
            'new_users' => User::whereBetween('created_at', [$startDate, $endDate])->count(),
            'active_users' => Transfer::whereBetween('created_at', [$startDate, $endDate])
                ->distinct('sender_id')
                ->count('sender_id'),
            'total_users' => User::count(),
            'verified_users' => User::where('is_verified', true)->count(),
            'blocked_users' => User::where('status', 'blocked')->count(),
        ];
    }

    private function getSpeedDistribution($startDate, $endDate)
    {
        return Transfer::whereBetween('created_at', [$startDate, $endDate])
            ->select('transfer_speed', DB::raw('count(*) as count'))
            ->groupBy('transfer_speed')
            ->get()
            ->pluck('count', 'transfer_speed')
            ->toArray();
    }

    private function getTopRoutes($startDate, $endDate)
    {
        return Transfer::whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('CONCAT(source_currency, " → ", target_currency) as route'),
                DB::raw('count(*) as count'),
                DB::raw('sum(amount) as total_amount')
            )
            ->groupBy('source_currency', 'target_currency')
            ->orderByDesc('count')
            ->limit(10)
            ->get();
    }

    private function getDailyTrends($startDate, $endDate)
    {
        return Transfer::whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as count'),
                DB::raw('sum(amount) as volume'),
                DB::raw('sum(transfer_fee) as fees')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    private function getSupportStats($startDate, $endDate)
    {
        $ticketsFile = storage_path('app/private/support_tickets.json');
        
        if (!File::exists($ticketsFile)) {
            return [
                'total_tickets' => 0,
                'open_tickets' => 0,
                'closed_tickets' => 0,
                'high_priority' => 0,
            ];
        }

        $tickets = json_decode(File::get($ticketsFile), true);
        
        // Filter tickets by date range
        $filteredTickets = array_filter($tickets, function($ticket) use ($startDate, $endDate) {
            $createdAt = Carbon::parse($ticket['created_at']);
            return $createdAt->between($startDate, $endDate);
        });

        $openTickets = array_filter($filteredTickets, fn($t) => $t['status'] === 'open');
        $closedTickets = array_filter($filteredTickets, fn($t) => $t['status'] === 'closed');
        $highPriorityTickets = array_filter($filteredTickets, fn($t) => isset($t['priority']) && $t['priority'] === 'high');

        return [
            'total_tickets' => count($filteredTickets),
            'open_tickets' => count($openTickets),
            'closed_tickets' => count($closedTickets),
            'high_priority' => count($highPriorityTickets),
        ];
    }

    private function getFeedbackStats($startDate, $endDate)
    {
        $feedbackFile = storage_path('app/private/user_feedback.json');
        
        if (!File::exists($feedbackFile)) {
            return [
                'total_feedback' => 0,
                'avg_rating' => 0,
                'five_star' => 0,
                'four_star' => 0,
                'three_star' => 0,
                'two_star' => 0,
                'one_star' => 0,
                'positive_sentiment' => 0,
                'negative_sentiment' => 0,
                'recent_feedback' => [],
            ];
        }

        $allFeedback = json_decode(File::get($feedbackFile), true);
        
        // Filter feedback by date range
        $filteredFeedback = array_filter($allFeedback, function($feedback) use ($startDate, $endDate) {
            $createdAt = Carbon::parse($feedback['created_at']);
            return $createdAt->between($startDate, $endDate);
        });

        // Calculate rating distribution
        $ratings = array_column($filteredFeedback, 'rating');
        $fiveStar = count(array_filter($ratings, fn($r) => $r == 5));
        $fourStar = count(array_filter($ratings, fn($r) => $r == 4));
        $threeStar = count(array_filter($ratings, fn($r) => $r == 3));
        $twoStar = count(array_filter($ratings, fn($r) => $r == 2));
        $oneStar = count(array_filter($ratings, fn($r) => $r == 1));
        
        // Calculate average rating
        $avgRating = count($ratings) > 0 ? array_sum($ratings) / count($ratings) : 0;
        
        // Calculate sentiment (4-5 stars = positive, 1-2 stars = negative)
        $positiveSentiment = $fiveStar + $fourStar;
        $negativeSentiment = $oneStar + $twoStar;
        
        // Get recent feedback (last 5)
        $recentFeedback = array_slice(array_reverse($filteredFeedback), 0, 5);

        return [
            'total_feedback' => count($filteredFeedback),
            'avg_rating' => round($avgRating, 2),
            'five_star' => $fiveStar,
            'four_star' => $fourStar,
            'three_star' => $threeStar,
            'two_star' => $twoStar,
            'one_star' => $oneStar,
            'positive_sentiment' => $positiveSentiment,
            'negative_sentiment' => $negativeSentiment,
            'recent_feedback' => $recentFeedback,
        ];
    }

    public function export(Request $request)
    {
        $type = $request->get('type', 'transactions');
        $period = $request->get('period', 'month');
        $startDate = $this->getStartDate($period);
        $endDate = now();

        switch($type) {
            case 'transactions':
                return $this->exportTransactions($startDate, $endDate);
            case 'revenue':
                return $this->exportRevenue($startDate, $endDate);
            case 'users':
                return $this->exportUsers($startDate, $endDate);
            case 'feedback':
                return $this->exportFeedback($startDate, $endDate);
            default:
                return back()->with('error', 'Invalid export type');
        }
    }

    private function exportTransactions($startDate, $endDate)
    {
        $transfers = Transfer::with(['sender', 'beneficiary'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $csv = "ID,Date,Sender,Beneficiary,Amount,Currency From,Currency To,Fee,Status,Speed,Completed At\n";
        
        foreach($transfers as $transfer) {
            $csv .= implode(',', [
                $transfer->id,
                $transfer->created_at->format('Y-m-d H:i:s'),
                '"' . ($transfer->sender->name ?? 'N/A') . '"',
                '"' . ($transfer->beneficiary->name ?? 'N/A') . '"',
                $transfer->amount,
                $transfer->source_currency,
                $transfer->target_currency,
                $transfer->transfer_fee,
                $transfer->status,
                $transfer->transfer_speed,
                $transfer->completed_at ? $transfer->completed_at->format('Y-m-d H:i:s') : 'N/A',
            ]) . "\n";
        }

        $filename = 'transactions_report_' . now()->format('Y-m-d') . '.csv';
        
        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    private function exportRevenue($startDate, $endDate)
    {
        $transfers = Transfer::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->get();

        $csv = "Date,Transaction ID,Amount,Fee,Total Revenue,Currency\n";
        
        foreach($transfers as $transfer) {
            $csv .= implode(',', [
                $transfer->created_at->format('Y-m-d'),
                $transfer->id,
                $transfer->amount,
                $transfer->transfer_fee,
                $transfer->total_paid,
                $transfer->source_currency,
            ]) . "\n";
        }

        $filename = 'revenue_report_' . now()->format('Y-m-d') . '.csv';
        
        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    private function exportUsers($startDate, $endDate)
    {
        $users = User::whereBetween('created_at', [$startDate, $endDate])->get();

        $csv = "ID,Name,Email,Phone,Registered Date,Verified,Role,Status\n";
        
        foreach($users as $user) {
            $csv .= implode(',', [
                $user->id,
                '"' . $user->name . '"',
                $user->email,
                $user->phone ?? 'N/A',
                $user->created_at->format('Y-m-d'),
                $user->is_verified ? 'Yes' : 'No',
                $user->role,
                $user->status ?? 'active',
            ]) . "\n";
        }

        $filename = 'users_report_' . now()->format('Y-m-d') . '.csv';
        
        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    private function exportFeedback($startDate, $endDate)
    {
        $feedbackFile = storage_path('app/private/user_feedback.json');
        
        if (!File::exists($feedbackFile)) {
            return back()->with('error', 'No feedback data available');
        }

        $allFeedback = json_decode(File::get($feedbackFile), true);
        
        // Filter feedback by date range
        $filteredFeedback = array_filter($allFeedback, function($feedback) use ($startDate, $endDate) {
            $createdAt = Carbon::parse($feedback['created_at']);
            return $createdAt->between($startDate, $endDate);
        });

        $csv = "User Name,Email,Rating,Comment,Category,Date\n";
        
        foreach($filteredFeedback as $feedback) {
            $csv .= implode(',', [
                '"' . ($feedback['user_name'] ?? 'Anonymous') . '"',
                '"' . ($feedback['user_email'] ?? 'N/A') . '"',
                $feedback['rating'] ?? 'N/A',
                '"' . str_replace('"', '""', $feedback['comment'] ?? 'No comment') . '"',
                '"' . ($feedback['category'] ?? 'General') . '"',
                Carbon::parse($feedback['created_at'])->format('Y-m-d H:i:s'),
            ]) . "\n";
        }

        $filename = 'feedback_report_' . now()->format('Y-m-d') . '.csv';
        
        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
