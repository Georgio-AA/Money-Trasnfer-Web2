<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\BankAccount;
use App\Models\Transfer;
use App\Models\User;
use App\Models\CardRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_users' => User::count(),
            'active_transfers' => Transfer::whereIn('status', ['pending', 'processing'])->count(),
            'pending_verifications' => BankAccount::where('is_verified', false)->count(),
            'pending_agents' => Agent::where('approved', false)->count(),
        ];

        // Revenue and fee analytics
        $revenueStats = $this->getRevenueStats();
        
        // Transfer statistics by status
        $transferStats = $this->getTransferStats();
        
        // User growth data (last 12 months)
        $userGrowth = $this->getUserGrowth();
        
        // Transfer volume by month (last 6 months)
        $transferVolume = $this->getTransferVolume();

        $recentActivity = Transfer::with(['sender', 'beneficiary'])
            ->latest()
            ->take(10)
            ->get();

        // Pending card requests
        $pendingCardRequests = CardRequest::with('user')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats', 
            'recentActivity', 
            'revenueStats', 
            'transferStats', 
            'userGrowth', 
            'transferVolume',
            'pendingCardRequests'
        ));
    }

    protected function getRevenueStats()
    {
        $completedTransfers = Transfer::where('status', 'completed');
        
        return [
            'total_revenue' => $completedTransfers->sum('transfer_fee'),
            'total_volume' => $completedTransfers->sum('amount'),
            'completed_count' => $completedTransfers->count(),
            'avg_fee' => $completedTransfers->avg('transfer_fee') ?? 0,
        ];
    }

    protected function getTransferStats()
    {
        return [
            'pending' => Transfer::where('status', 'pending')->count(),
            'processing' => Transfer::where('status', 'processing')->count(),
            'completed' => Transfer::where('status', 'completed')->count(),
            'failed' => Transfer::where('status', 'failed')->count(),
            'cancelled' => Transfer::where('status', 'cancelled')->count(),
        ];
    }

    protected function getUserGrowth()
    {
        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = User::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            
            $months[] = [
                'month' => $date->format('M Y'),
                'count' => $count,
            ];
        }
        
        return $months;
    }

    protected function getTransferVolume()
    {
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $volume = Transfer::where('status', 'completed')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('amount');
            
            $months[] = [
                'month' => $date->format('M Y'),
                'volume' => $volume,
            ];
        }
        
        return $months;
    }
}
