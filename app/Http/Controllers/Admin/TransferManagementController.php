<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transfer;
use Illuminate\Http\Request;

class TransferManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Transfer::with(['sender', 'receiver']);

        // Search by transfer ID or amount
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('amount', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transfers = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.transfers.index', compact('transfers'));
    }

    public function show(Transfer $transfer)
    {
        $transfer->load(['sender', 'receiver', 'beneficiary']);
        
        return view('admin.transfers.show', compact('transfer'));
    }

    public function cancel(Request $request, Transfer $transfer)
    {
        // Only allow canceling pending or processing transfers
        if (!in_array($transfer->status, ['pending', 'processing'])) {
            return back()->with('error', 'Only pending or processing transfers can be canceled');
        }

        $transfer->update([
            'status' => 'cancelled',
        ]);

        return back()->with('success', 'Transfer canceled successfully');
    }

    public function updateStatus(Request $request, Transfer $transfer)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,failed,cancelled',
        ]);

        $transfer->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Transfer status updated successfully');
    }
}
