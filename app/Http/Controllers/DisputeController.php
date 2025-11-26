<?php

namespace App\Http\Controllers;

use App\Models\Dispute;
use App\Models\Transfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class DisputeController extends Controller
{
    /**
     * Display all disputes by the authenticated user
     */
    public function index()
    {
        $user = Session::get('user');
        if (!$user) {
            return redirect()->route('login');
        }

        $disputes = Dispute::where('user_id', $user['id'])
            ->with(['transfer'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('disputes.index', compact('disputes'));
    }

    /**
     * Show form to create a dispute for a transfer
     */
    public function create(Request $request)
    {
        $user = Session::get('user');
        if (!$user) {
            return redirect()->route('login');
        }

        $transferId = $request->query('transfer_id');
        $transfer = null;
        
        if ($transferId) {
            $transfer = Transfer::where('id', $transferId)
                ->where('sender_id', $user['id'])
                ->first();
            
            if (!$transfer) {
                return redirect()->route('disputes.index')
                    ->with('error', 'Transfer not found or you do not have permission to dispute it.');
            }
            
            // Check if already disputed
            $existingDispute = Dispute::where('transfer_id', $transferId)
                ->whereIn('status', ['open', 'investigating'])
                ->first();
            
            if ($existingDispute) {
                return redirect()->route('disputes.show', $existingDispute->id)
                    ->with('info', 'This transfer already has an open dispute.');
            }
        }

        // Get user's transfers that can be disputed
        $eligibleTransfers = Transfer::where('sender_id', $user['id'])
            ->whereIn('status', ['completed', 'failed', 'cancelled'])
            ->whereNotIn('id', function($query) {
                $query->select('transfer_id')
                    ->from('disputes')
                    ->whereIn('status', ['open', 'investigating']);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('disputes.create', compact('transfer', 'eligibleTransfers'));
    }

    /**
     * Store a new dispute
     */
    public function store(Request $request)
    {
        $user = Session::get('user');
        if (!$user) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'transfer_id' => 'required|exists:transfers,id',
            'reason' => 'required|string|in:wrong_recipient,wrong_amount,not_received,unauthorized,fraudulent,technical_issue,other',
            'description' => 'required|string|min:20|max:2000',
        ]);

        // Verify transfer ownership
        $transfer = Transfer::where('id', $validated['transfer_id'])
            ->where('sender_id', $user['id'])
            ->first();
        
        if (!$transfer) {
            return back()->with('error', 'Transfer not found or you do not have permission to dispute it.');
        }

        // Check for existing open dispute
        $existingDispute = Dispute::where('transfer_id', $validated['transfer_id'])
            ->whereIn('status', ['open', 'investigating'])
            ->first();
        
        if ($existingDispute) {
            return redirect()->route('disputes.show', $existingDispute->id)
                ->with('info', 'This transfer already has an open dispute.');
        }

        $validated['user_id'] = $user['id'];
        $validated['status'] = 'open';

        $dispute = Dispute::create($validated);

        return redirect()->route('disputes.show', $dispute->id)
            ->with('success', 'Dispute filed successfully. Our team will investigate and contact you soon.');
    }

    /**
     * Show a specific dispute
     */
    public function show($id)
    {
        $user = Session::get('user');
        if (!$user) {
            return redirect()->route('login');
        }

        $dispute = Dispute::where('id', $id)
            ->where('user_id', $user['id'])
            ->with(['transfer', 'transfer.beneficiary'])
            ->firstOrFail();

        return view('disputes.show', compact('dispute'));
    }

    /**
     * Cancel a dispute (only if status is 'open')
     */
    public function cancel($id)
    {
        $user = Session::get('user');
        if (!$user) {
            return redirect()->route('login');
        }

        $dispute = Dispute::where('id', $id)
            ->where('user_id', $user['id'])
            ->where('status', 'open')
            ->firstOrFail();

        $dispute->update([
            'status' => 'cancelled',
            'resolved_at' => now(),
        ]);

        return redirect()->route('disputes.index')
            ->with('success', 'Dispute cancelled successfully.');
    }

    /**
     * Request refund for a dispute (changes status to refund_requested)
     */
    public function requestRefund($id)
    {
        $user = Session::get('user');
        if (!$user) {
            return redirect()->route('login');
        }

        $dispute = Dispute::where('id', $id)
            ->where('user_id', $user['id'])
            ->whereIn('status', ['open', 'investigating'])
            ->with('transfer')
            ->firstOrFail();

        // Update dispute status
        $dispute->update([
            'status' => 'refund_requested',
        ]);

        return redirect()->route('disputes.show', $dispute->id)
            ->with('success', 'Refund request submitted. Our team will process your request and update you shortly.');
    }
}
