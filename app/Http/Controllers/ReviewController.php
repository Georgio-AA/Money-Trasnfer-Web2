<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Transfer;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ReviewController extends Controller
{
    /**
     * Display all reviews by the authenticated user
     */
    public function index()
    {
        $user = Session::get('user');
        if (!$user) {
            return redirect()->route('login');
        }

        $reviews = Review::where('user_id', $user['id'])
            ->with(['agent'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('reviews.index', compact('reviews'));
    }

    /**
     * Show form to create a review for a completed transfer
     */
    public function create(Request $request)
    {
        $user = Session::get('user');
        if (!$user) {
            return redirect()->route('login');
        }

        $transferId = $request->query('transfer_id');
        
        if ($transferId) {
            // Specific transfer selected
            $transfer = Transfer::where('id', $transferId)
                ->where('sender_id', $user['id'])
                ->where('status', 'completed')
                ->first();
            
            if (!$transfer) {
                return redirect()->route('reviews.create')
                    ->with('error', 'Transfer not found or not eligible for review.');
            }
            
            // Check if already reviewed
            $existingReview = Review::where('user_id', $user['id'])
                ->where('transfer_id', $transferId)
                ->first();
            
            if ($existingReview) {
                return redirect()->route('reviews.index')
                    ->with('info', 'You have already reviewed this transfer.');
            }

            return view('reviews.create', compact('transfer'));
        }

        // No transfer selected - show list of eligible transfers
        $eligibleTransfers = Transfer::where('sender_id', $user['id'])
            ->where('status', 'completed')
            ->whereNotIn('id', function($query) use ($user) {
                $query->select('transfer_id')
                    ->from('reviews')
                    ->where('user_id', $user['id'])
                    ->whereNotNull('transfer_id');
            })
            ->with('beneficiary')
            ->orderBy('completed_at', 'desc')
            ->get();

        return view('reviews.select-transfer', compact('eligibleTransfers'));
    }

    /**
     * Store a new review
     */
    public function store(Request $request)
    {
        $user = Session::get('user');
        if (!$user) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'transfer_id' => 'required|exists:transfers,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ]);

        // Verify transfer ownership and completion
        $transfer = Transfer::where('id', $validated['transfer_id'])
            ->where('sender_id', $user['id'])
            ->where('status', 'completed')
            ->first();
        
        if (!$transfer) {
            return back()->with('error', 'Transfer not found or not eligible for review.');
        }

        // Check for duplicate review
        $existingReview = Review::where('user_id', $user['id'])
            ->where('transfer_id', $validated['transfer_id'])
            ->first();
        
        if ($existingReview) {
            return back()->with('error', 'You have already reviewed this transfer.');
        }

        $validated['user_id'] = $user['id'];

        Review::create($validated);

        return redirect()->route('reviews.index')
            ->with('success', 'Thank you for your review! Your feedback helps us improve our service.');
    }

    /**
     * Show a specific review
     */
    public function show($id)
    {
        $user = Session::get('user');
        if (!$user) {
            return redirect()->route('login');
        }

        $review = Review::where('id', $id)
            ->where('user_id', $user['id'])
            ->with(['agent', 'transfer'])
            ->firstOrFail();

        return view('reviews.show', compact('review'));
    }

    /**
     * Show edit form for a review
     */
    public function edit($id)
    {
        $user = Session::get('user');
        if (!$user) {
            return redirect()->route('login');
        }

        $review = Review::where('id', $id)
            ->where('user_id', $user['id'])
            ->with(['transfer'])
            ->firstOrFail();

        return view('reviews.edit', compact('review'));
    }

    /**
     * Update an existing review
     */
    public function update(Request $request, $id)
    {
        $user = Session::get('user');
        if (!$user) {
            return redirect()->route('login');
        }

        $review = Review::where('id', $id)
            ->where('user_id', $user['id'])
            ->firstOrFail();

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ]);

        $review->update($validated);

        return redirect()->route('reviews.index')
            ->with('success', 'Review updated successfully!');
    }

    /**
     * Delete a review
     */
    public function destroy($id)
    {
        $user = Session::get('user');
        if (!$user) {
            return redirect()->route('login');
        }

        $review = Review::where('id', $id)
            ->where('user_id', $user['id'])
            ->firstOrFail();

        $review->delete();

        return redirect()->route('reviews.index')
            ->with('success', 'Review deleted successfully.');
    }
}
