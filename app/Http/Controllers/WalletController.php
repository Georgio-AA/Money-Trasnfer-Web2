<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\BankAccount;
use App\Models\PaymentTransaction;
use App\Models\ExchangeRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    public function index()
    {
        $user = session('user');
        
        // Get fresh user data with latest balance
        $freshUser = User::findOrFail($user['id']);
        session(['user' => $freshUser->toArray()]);
        
        // Get user's bank accounts
        $bankAccounts = BankAccount::where('user_id', $user['id'])->get();
        
        // Get recent transactions
        $transactions = PaymentTransaction::where('user_id', $user['id'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('wallet.index', compact('freshUser', 'transactions', 'bankAccounts'));
    }
    
    public function showDepositForm()
    {
        $user = session('user');
        $bankAccounts = BankAccount::where('user_id', $user['id'])
            ->where('is_verified', true)
            ->get();
        
        return view('wallet.deposit', compact('bankAccounts'));
    }
    
    public function deposit(Request $request)
    {
        $user = session('user');
        
        $validated = $request->validate([
            'bank_account_id' => 'required|exists:bank_accounts,id',
            'amount' => 'required|numeric|min:1|max:100000',
        ]);
        
        $bankAccount = BankAccount::findOrFail($validated['bank_account_id']);
        
        // Verify ownership
        if ($bankAccount->user_id != $user['id']) {
            return back()->with('error', 'Unauthorized access to bank account.');
        }
        
        if (!$bankAccount->is_verified) {
            return back()->with('error', 'Bank account must be verified before depositing.');
        }
        
        $sender = User::findOrFail($user['id']);
        
        // Check currency conversion if needed
        $amountToDeduct = $validated['amount'];
        $amountToCredit = $validated['amount'];
        
        if ($sender->currency !== $bankAccount->currency) {
            // Need to convert wallet currency to bank account currency
            $exchangeRate = ExchangeRate::where('base_currency', $sender->currency)
                ->where('target_currency', $bankAccount->currency)
                ->first();
            
            if (!$exchangeRate) {
                return back()->with('error', 
                    'Cannot deposit: No exchange rate found from ' . $sender->currency . 
                    ' to ' . $bankAccount->currency . '. Please contact support.');
            }
            
            $amountToCredit = $validated['amount'] * $exchangeRate->rate;
        }
        
        // Check if bank account has sufficient balance
        if ($bankAccount->balance < $amountToCredit) {
            return back()->with('error', 
                'Insufficient balance in bank account. Available: ' . $bankAccount->currency . ' ' . 
                number_format($bankAccount->balance, 2) . 
                ' (Need: ' . $bankAccount->currency . ' ' . number_format($amountToCredit, 2) . ')');
        }
        
        DB::beginTransaction();
        
        try {
            // Add money to wallet (in wallet's currency)
            $sender->balance += $amountToDeduct;
            $sender->save();
            
            // Deduct from bank account (in bank's currency)
            $bankAccount->balance -= $amountToCredit;
            $bankAccount->save();
            
            // Create transaction record
            PaymentTransaction::create([
                'transfer_id' => null,
                'user_id' => $sender->id,
                'transaction_type' => 'credit',
                'amount' => $amountToDeduct,
                'currency' => $sender->currency,
                'status' => 'completed',
                'payment_method' => 'bank_account',
                'description' => 'Deposit from ' . $bankAccount->bank_name . ' (' . $bankAccount->masked_account_number . ')' .
                    ($sender->currency !== $bankAccount->currency ? 
                        ' - Converted from ' . $bankAccount->currency . ' ' . number_format($amountToCredit, 2) : ''),
            ]);
            
            session(['user' => $sender->toArray()]);
            
            DB::commit();
            
            return redirect()->route('wallet.index')
                ->with('success', 'Money deposited successfully! Your wallet balance has been updated.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Deposit failed: ' . $e->getMessage());
        }
    }
    
    public function showWithdrawForm()
    {
        $user = session('user');
        $bankAccounts = BankAccount::where('user_id', $user['id'])
            ->where('is_verified', true)
            ->get();
        
        return view('wallet.withdraw', compact('bankAccounts'));
    }
    
    public function withdraw(Request $request)
    {
        $user = session('user');
        
        $validated = $request->validate([
            'bank_account_id' => 'required|exists:bank_accounts,id',
            'amount' => 'required|numeric|min:1',
        ]);
        
        $bankAccount = BankAccount::findOrFail($validated['bank_account_id']);
        
        // Verify ownership
        if ($bankAccount->user_id != $user['id']) {
            return back()->with('error', 'Unauthorized access to bank account.');
        }
        
        if (!$bankAccount->is_verified) {
            return back()->with('error', 'Bank account must be verified before withdrawing.');
        }
        
        $sender = User::findOrFail($user['id']);
        
        // Check currency conversion if needed
        $amountToDeduct = $validated['amount'];
        $amountToCredit = $validated['amount'];
        
        if ($sender->currency !== $bankAccount->currency) {
            // Need to convert from wallet currency to bank account currency
            $exchangeRate = ExchangeRate::where('base_currency', $sender->currency)
                ->where('target_currency', $bankAccount->currency)
                ->first();
            
            if (!$exchangeRate) {
                return back()->with('error', 
                    'Cannot withdraw: No exchange rate found from ' . $sender->currency . 
                    ' to ' . $bankAccount->currency . '. Please contact support.');
            }
            
            $amountToCredit = $validated['amount'] * $exchangeRate->rate;
        }
        
        // Check sufficient balance
        if ($sender->balance < $amountToDeduct) {
            return back()->with('error', 
                'Insufficient balance. You have ' . $sender->currency . ' ' . 
                number_format($sender->balance, 2) . ' available.');
        }
        
        DB::beginTransaction();
        
        try {
            // Deduct money from wallet (in wallet's currency)
            $sender->balance -= $amountToDeduct;
            $sender->save();
            
            // Add to bank account (in bank's currency)
            $bankAccount->balance += $amountToCredit;
            $bankAccount->save();
            
            // Create transaction record
            PaymentTransaction::create([
                'transfer_id' => null,
                'user_id' => $sender->id,
                'transaction_type' => 'debit',
                'amount' => $amountToDeduct,
                'currency' => $sender->currency,
                'status' => 'completed',
                'payment_method' => 'bank_account',
                'description' => 'Withdrawal to ' . $bankAccount->bank_name . ' (' . $bankAccount->masked_account_number . ')' .
                    ($sender->currency !== $bankAccount->currency ? 
                        ' - Converted to ' . $bankAccount->currency . ' ' . number_format($amountToCredit, 2) : ''),
            ]);
            
            session(['user' => $sender->toArray()]);
            
            DB::commit();
            
            return redirect()->route('wallet.index')
                ->with('success', 'Money withdrawn successfully! Check your bank account in 1-3 business days.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Withdrawal failed: ' . $e->getMessage());
        }
    }
}
