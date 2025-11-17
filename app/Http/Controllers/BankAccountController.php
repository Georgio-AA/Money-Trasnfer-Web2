<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Mail\BankAccountVerificationMail;

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

        // Generate verification token and send verification email
        $token = Str::random(64);
        $bankAccount->update([
            'verification_token' => $token,
            'verification_expires_at' => now()->addDays(7),
            'verification_sent_at' => now(),
        ]);

        try {
            $bankAccount = $bankAccount->fresh(['user']);
            Mail::to($user['email'])->send(new BankAccountVerificationMail($bankAccount));
            $info = 'Verification email sent. Please check your inbox to verify this bank account.';
        } catch (\Exception $e) {
            \Log::error('Failed to send bank account verification email: ' . $e->getMessage());
            $info = 'Bank account created. We could not send the verification email (' . $e->getMessage() . '). You can resend it from the verification page.';
        }

        return redirect()->route('bank-accounts.index')
            ->with('success', 'Bank account added successfully!')
            ->with('info', $info);
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
     * Show verification info (email-only flow) and allow resending the email.
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
     * Resend verification email for a bank account.
     */
    public function sendVerificationEmail(BankAccount $bankAccount)
    {
        $user = Session::get('user');
        if (!$user || $bankAccount->user_id !== $user['id']) {
            abort(403, 'Unauthorized action.');
        }

        if ($bankAccount->is_verified) {
            return redirect()->route('bank-accounts.index')
                ->with('info', 'This bank account is already verified.');
        }

        $token = Str::random(64);
        $bankAccount->update([
            'verification_token' => $token,
            'verification_expires_at' => now()->addDays(7),
            'verification_sent_at' => now(),
        ]);

        try {
            $bankAccount = $bankAccount->fresh(['user']);
            Mail::to($user['email'])->send(new BankAccountVerificationMail($bankAccount));
            return back()->with('success', 'Verification email has been sent. Please check your inbox.');
        } catch (\Exception $e) {
            \Log::error('Failed to send bank account verification email: ' . $e->getMessage());
            return back()->with('error', 'Could not send the verification email: ' . $e->getMessage());
        }
    }

    /**
     * Verify by email link: mark account verified if token matches and not expired.
     */
    public function verifyByEmail(BankAccount $bankAccount, string $token)
    {
        if ($bankAccount->is_verified) {
            return redirect()->route('bank-accounts.index')
                ->with('info', 'This bank account is already verified.');
        }

        if (!$bankAccount->verification_token || !hash_equals($bankAccount->verification_token, $token)) {
            return redirect()->route('bank-accounts.index')->with('error', 'Invalid verification link.');
        }

        if ($bankAccount->verification_expires_at && $bankAccount->verification_expires_at->isPast()) {
            return redirect()->route('bank-accounts.verify-form', $bankAccount)
                ->with('error', 'Verification link has expired. Please resend the verification email.');
        }

        $bankAccount->update([
            'is_verified' => true,
            'verification_token' => null,
        ]);

        return redirect()->route('bank-accounts.index')->with('success', 'Bank account verified successfully!');
    }
}