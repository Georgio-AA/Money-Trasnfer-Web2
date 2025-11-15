<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\BankAccount;
use App\Models\Transfer;
use App\Models\User;
use Illuminate\Contracts\View\View;

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

        $recentActivity = Transfer::with(['sender', 'receiver'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentActivity'));
    }
}
