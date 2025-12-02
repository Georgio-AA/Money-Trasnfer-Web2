<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Agent;    
use App\Models\Transfer;

class AgentDashboardController extends Controller
{
        public function index()
    {
        $user = session('user');
        $agent = Agent::where('user_id', $user['id'])->first();

        $incomingTransfers = Transfer::where('agent_id', $agent->id)
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('agent.dashboard', compact('agent', 'incomingTransfers'));
    }

    public function process($id)
    {
        $transfer = Transfer::findOrFail($id);
        return view('agent.process', compact('transfer'));
    }

}
