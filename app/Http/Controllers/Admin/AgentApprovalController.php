<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class AgentApprovalController extends Controller
{
    public function index(): View
    {
        $pendingAgents = Agent::with('user')
            ->where('approved', false)
            ->latest()
            ->get();

        $approvedAgents = Agent::with('user')
            ->where('approved', true)
            ->latest('updated_at')
            ->get();

        return view('admin.agents', compact('pendingAgents', 'approvedAgents'));
    }

    public function approve(Agent $agent): RedirectResponse
    {
        if ($agent->approved) {
            return redirect()
                ->route('admin.agents.index')
                ->with('info', 'This agent is already approved.');
        }

        $agent->forceFill(['approved' => true])->save();

        // Update user's role to 'agent'
        $user = $agent->user;
        $user->role = 'agent';
        $user->save();

        return redirect()
            ->route('admin.agents.index')
            ->with('success', 'Agent has been approved successfully.');
    }

    public function revoke(Agent $agent): RedirectResponse
    {
        if (! $agent->approved) {
            return redirect()
                ->route('admin.agents.index')
                ->with('info', 'This agent is already pending approval.');
        }

        $agent->forceFill(['approved' => false])->save();

        // Revert user's role
        $user = $agent->user;
        $user->role = 'user'; 
        $user->save();

        return redirect()
            ->route('admin.agents.index')
            ->with('success', 'Agent approval has been revoked.');
    }
}
