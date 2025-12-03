<?php

namespace App\Http\Controllers;

use App\Models\CardRequest;
use App\Models\User;
use App\Mail\CardRequestSubmittedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class CardRequestController extends Controller
{
    /**
     * Show the form for creating a new card request
     */
    public function create()
    {
        $user = session('user');
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in first');
        }

        // Get fresh user data from database to get latest balance
        $freshUser = User::find($user['id']);
        if ($freshUser) {
            $userBalance = $freshUser->balance;
        } else {
            $userBalance = $user['balance'] ?? 0;
        }

        return view('card.request', compact('userBalance'));
    }

    /**
     * Store a newly created card request
     */
    public function store(Request $request)
    {
        $user = session('user');
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in first');
        }

        // Validate the request
        $validated = $request->validate([
            'id_image' => 'required|image|mimes:jpeg,png,jpg|max:5120', // 5MB max
        ], [
            'id_image.required' => 'Please upload a clear photo/scan of your government-issued ID',
            'id_image.image' => 'The file must be a valid image',
            'id_image.mimes' => 'The image must be a JPG or PNG file',
            'id_image.max' => 'The image must not exceed 5MB',
        ]);

        try {
            // Get fresh user data from database
            $freshUser = User::findOrFail($user['id']);
            $cardAmount = $freshUser->balance;

            // Store the ID image in storage
            $imagePath = $request->file('id_image')->store('card-requests/id-images', 'public');

            // Create the card request
            $cardRequest = CardRequest::create([
                'user_id' => $freshUser->id,
                'amount' => $cardAmount,
                'id_image' => $imagePath,
                'status' => 'pending',
            ]);

            // Get all admin users
            $admins = User::where('role', 'admin')->get();

            // Send email to all admins
            if ($admins->isNotEmpty()) {
                // Build review link (you may need to adjust this based on your admin route)
                $reviewLink = url('/admin/card-requests/' . $cardRequest->id);

                foreach ($admins as $admin) {
                    Mail::to($admin->email)->send(new CardRequestSubmittedMail(
                        $freshUser->name,
                        $freshUser->email,
                        $freshUser->phone ?? null,
                        $cardAmount,
                        $reviewLink
                    ));
                }
            }

            Log::info('Card request submitted', [
                'card_request_id' => $cardRequest->id,
                'user_id' => $freshUser->id,
                'amount' => $cardAmount,
                'id_image' => $imagePath,
            ]);

            return redirect()->route('card.request.create')
                ->with('success', 'Card request submitted successfully! Our admin team will review your ID and respond within 24-48 hours.');

        } catch (\Exception $e) {
            Log::error('Failed to create card request', [
                'user_id' => $user['id'],
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to submit card request: ' . $e->getMessage());
        }
    }

    /**
     * Show all pending card requests (Admin only)
     */
    public function index()
    {
        // Check if user is admin
        $user = session('user');
        if (!$user || $user['role'] !== 'admin') {
            abort(403, 'Unauthorized access');
        }

        $cardRequests = CardRequest::with('user')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.card_requests.index', compact('cardRequests'));
    }

    /**
     * Show a specific card request for admin review
     */
    public function show($id)
    {
        // Check if user is admin
        $user = session('user');
        if (!$user || $user['role'] !== 'admin') {
            abort(403, 'Unauthorized access');
        }

        $cardRequest = CardRequest::with('user')->findOrFail($id);

        return view('admin.card_requests.show', compact('cardRequest'));
    }

    /**
     * Approve a card request
     */
    public function approve($id)
    {
        // Check if user is admin
        $user = session('user');
        if (!$user || $user['role'] !== 'admin') {
            abort(403, 'Unauthorized access');
        }

        $cardRequest = CardRequest::findOrFail($id);

        if ($cardRequest->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Only pending requests can be approved');
        }

        try {
            $cardRequest->update(['status' => 'approved']);

            Log::info('Card request approved', [
                'card_request_id' => $cardRequest->id,
                'user_id' => $cardRequest->user_id,
                'amount' => $cardRequest->amount,
            ]);

            // TODO: Implement actual card generation here when ready

            return redirect()->route('admin.card-requests.index')
                ->with('success', 'Card request approved successfully!');

        } catch (\Exception $e) {
            Log::error('Failed to approve card request', [
                'card_request_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Failed to approve card request: ' . $e->getMessage());
        }
    }

    /**
     * Reject a card request
     */
    public function reject($id)
    {
        // Check if user is admin
        $user = session('user');
        if (!$user || $user['role'] !== 'admin') {
            abort(403, 'Unauthorized access');
        }

        $cardRequest = CardRequest::findOrFail($id);

        if ($cardRequest->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Only pending requests can be rejected');
        }

        try {
            $cardRequest->update(['status' => 'rejected']);

            Log::info('Card request rejected', [
                'card_request_id' => $cardRequest->id,
                'user_id' => $cardRequest->user_id,
                'amount' => $cardRequest->amount,
            ]);

            return redirect()->route('admin.card-requests.index')
                ->with('success', 'Card request rejected successfully!');

        } catch (\Exception $e) {
            Log::error('Failed to reject card request', [
                'card_request_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Failed to reject card request: ' . $e->getMessage());
        }
    }
}
