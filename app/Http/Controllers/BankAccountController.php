<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use App\Mail\MicroTransferVerificationMail;

class BankAccountController extends Controller
{
    /**
     * Display a listing of the user's bank accounts.
     */
    public function index()
    {
        $user = Session::get('user');
        if (!$user) {
            return redirect()->route('login');
        }

        $bankAccounts = BankAccount::where('user_id', $user['id'])->get();
        return view('bank-accounts.index', compact('bankAccounts'));
    }

    /**
     * Show the form for creating a new bank account.
     */
    public function create()
    {
        return view('bank-accounts.create');
    }

    /**
     * Store a newly created bank account in storage.
     */
    public function store(Request $request)
    {
        $user = Session::get('user');
        if (!$user) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('bank_accounts')->where(function ($query) use ($user) {
                    return $query->where('user_id', $user['id']);
                })
            ],
            'account_type' => 'nullable|string|in:checking,savings,business,current',
            'currency' => 'required|string|size:3|in:USD,EUR,GBP,JPY,CAD,AUD,CHF,CNY,SEK,NZD,MXN,SGD,HKD,NOK,KRW,TRY,RUB,INR,BRL,ZAR'
        ]);

        $validated['user_id'] = $user['id'];
        $validated['is_verified'] = false; // New accounts start as unverified

        $bankAccount = BankAccount::create($validated);

        return redirect()->route('bank-accounts.index')
            ->with('success', 'Bank account added successfully! Please verify it to enable transfers.');
    }

    /**
     * Display the specified bank account.
     */
    public function show(BankAccount $bankAccount)
    {
        $user = Session::get('user');
        if (!$user || $bankAccount->user_id !== $user['id']) {
            abort(403, 'Unauthorized action.');
        }

        return view('bank-accounts.show', compact('bankAccount'));
    }

    /**
     * Show the form for editing the specified bank account.
     */
    public function edit(BankAccount $bankAccount)
    {
        $user = Session::get('user');
        if (!$user || $bankAccount->user_id !== $user['id']) {
            abort(403, 'Unauthorized action.');
        }

        return view('bank-accounts.edit', compact('bankAccount'));
    }

    /**
     * Update the specified bank account in storage.
     */
    public function update(Request $request, BankAccount $bankAccount)
    {
        $user = Session::get('user');
        if (!$user || $bankAccount->user_id !== $user['id']) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('bank_accounts')->where(function ($query) use ($user) {
                    return $query->where('user_id', $user['id']);
                })->ignore($bankAccount->id)
            ],
            'account_type' => 'nullable|string|in:checking,savings,business,current',
            'currency' => 'required|string|size:3|in:USD,EUR,GBP,JPY,CAD,AUD,CHF,CNY,SEK,NZD,MXN,SGD,HKD,NOK,KRW,TRY,RUB,INR,BRL,ZAR'
        ]);

        // If account details are changed, mark as unverified
        if ($bankAccount->account_number !== $validated['account_number'] || 
            $bankAccount->bank_name !== $validated['bank_name']) {
            $validated['is_verified'] = false;
        }

        $bankAccount->update($validated);

        $message = $bankAccount->wasChanged('is_verified') ? 
            'Bank account updated successfully! Please verify it again due to changes.' :
            'Bank account updated successfully!';

        return redirect()->route('bank-accounts.index')->with('success', $message);
    }

    /**
     * Remove the specified bank account from storage.
     */
    public function destroy(BankAccount $bankAccount)
    {
        $user = Session::get('user');
        if (!$user || $bankAccount->user_id !== $user['id']) {
            abort(403, 'Unauthorized action.');
        }

        $bankAccount->delete();

        return redirect()->route('bank-accounts.index')
            ->with('success', 'Bank account deleted successfully!');
    }

    /**
     * Show the verification form for a bank account.
     */
    public function showVerificationForm(BankAccount $bankAccount)
    {
        $user = Session::get('user');
        if (!$user || $bankAccount->user_id !== $user['id']) {
            abort(403, 'Unauthorized action.');
        }

        if ($bankAccount->is_verified) {
            return redirect()->route('bank-accounts.index')
                ->with('info', 'This bank account is already verified.');
        }

        return view('bank-accounts.verify', compact('bankAccount'));
    }

    /**
     * Process verification for a bank account.
     */
    public function verify(Request $request, BankAccount $bankAccount)
    {
        $user = Session::get('user');
        if (!$user || $bankAccount->user_id !== $user['id']) {
            abort(403, 'Unauthorized action.');
        }

        if ($bankAccount->is_verified) {
            return redirect()->route('bank-accounts.index')
                ->with('info', 'This bank account is already verified.');
        }

        $validated = $request->validate([
            'verification_method' => 'required|string|in:document,micro_transfer',
            'document' => 'required_if:verification_method,document|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'micro_amount_1' => 'required_if:verification_method,micro_transfer|numeric|between:0.01,0.99',
            'micro_amount_2' => 'required_if:verification_method,micro_transfer|numeric|between:0.01,0.99'
        ]);

        if ($validated['verification_method'] === 'document') {
            // Store the uploaded document
            $documentPath = $request->file('document')->store('verification_documents', 'public');
            
            // In a real application, you would send this for manual review
            // For demo purposes, we'll auto-approve
            $bankAccount->update([
                'is_verified' => true,
                'verification_document' => $documentPath
            ]);

            $message = 'Bank account verified successfully via document upload!';
        
        } elseif ($validated['verification_method'] === 'micro_transfer') {
            // Check if verification has expired
            if ($bankAccount->verification_expires_at && 
                $bankAccount->verification_expires_at->isPast()) {
                return back()->withErrors([
                    'micro_amounts' => 'Verification has expired. Please start the process again.'
                ]);
            }

            // Check if too many attempts
            if ($bankAccount->verification_attempts >= 3) {
                return back()->withErrors([
                    'micro_amounts' => 'Too many verification attempts. Please contact support.'
                ]);
            }

            // Increment verification attempts
            $bankAccount->increment('verification_attempts');

            // Verify the micro amounts against what was sent
            if (abs($validated['micro_amount_1'] - $bankAccount->micro_amount_1) < 0.01 && 
                abs($validated['micro_amount_2'] - $bankAccount->micro_amount_2) < 0.01) {
                
                $bankAccount->update(['is_verified' => true]);
                $message = 'Bank account verified successfully via micro-transfer!';
            } else {
                return back()->withErrors([
                    'micro_amounts' => 'The micro-transfer amounts do not match. Please try again. Attempts remaining: ' . (3 - $bankAccount->verification_attempts)
                ]);
            }
        }

        return redirect()->route('bank-accounts.index')->with('success', $message);
    }

    /**
     * Start the micro-transfer verification process.
     */
    public function startMicroTransferVerification(BankAccount $bankAccount)
    {
        $user = Session::get('user');
        if (!$user || $bankAccount->user_id !== $user['id']) {
            abort(403, 'Unauthorized action.');
        }

        // Generate two random micro amounts
        $microAmount1 = round(mt_rand(1, 99) / 100, 2);
        $microAmount2 = round(mt_rand(1, 99) / 100, 2);
        
        // Make sure they're different
        while ($microAmount2 === $microAmount1) {
            $microAmount2 = round(mt_rand(1, 99) / 100, 2);
        }

        // Update bank account with micro transfer details
        $bankAccount->update([
            'micro_amount_1' => $microAmount1,
            'micro_amount_2' => $microAmount2,
            'micro_transfer_sent_at' => now(),
            'verification_expires_at' => now()->addDays(7),
            'verification_attempts' => 0
        ]);

        try {
            // Reload the bank account to ensure we have the fresh instance with all relationships
            $bankAccount = $bankAccount->fresh(['user']);
            
            // Send email notification
            Mail::to($user['email'])->send(new MicroTransferVerificationMail($bankAccount, [
                'amount1' => $microAmount1,
                'amount2' => $microAmount2
            ]));

            $message = 'Micro-transfers have been initiated! Check your email for detailed instructions. The amounts will appear in your account within 1-2 business days.';
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Failed to send micro-transfer verification email: ' . $e->getMessage());
            
            $message = 'Micro-transfers have been initiated! The amounts will appear in your account within 1-2 business days. (Note: Email notification could not be sent - ' . $e->getMessage() . ')';
        }

        return redirect()->route('bank-accounts.verify-form', $bankAccount)
            ->with('info', $message)
            ->with('micro_started', true);
    }
}