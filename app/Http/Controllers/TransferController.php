<?php

namespace App\Http\Controllers;

use App\Models\Transfer;
use App\Models\Beneficiary;
use App\Models\ExchangeRate;
use App\Models\TransferService;
use App\Models\Promotion;
use App\Models\User;
use App\Models\PaymentTransaction;
use App\Http\Controllers\Admin\ExchangeRateController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransferController extends Controller
{
    public function create()
    {
        $user = session('user');
        
        // Refresh user data from database to get latest balance
        $freshUser = User::find($user['id']);
        if ($freshUser) {
            session(['user' => $freshUser->toArray()]);
            $user = $freshUser->toArray();
        }
        
        // Get user's beneficiaries
        $beneficiaries = Beneficiary::where('user_id', $user['id'])->get();
        
        // Get available transfer services
        $transferServices = TransferService::where('is_active', true)->get();
        
        // Get available promotions
        $promotions = Promotion::where('active', true)
            ->where('valid_from', '<=', now())
            ->where('valid_to', '>=', now())
            ->get();
        
        // Get transfer speeds from config
        $speeds = config('transfer.speeds');
        
        // Get countries from config
        $countries = config('transfer.countries');
        
        // Common currencies
        $currencies = [
            'USD' => 'US Dollar',
            'EUR' => 'Euro',
            'GBP' => 'British Pound',
            'CAD' => 'Canadian Dollar',
            'AUD' => 'Australian Dollar',
            'INR' => 'Indian Rupee',
            'PKR' => 'Pakistani Rupee',
            'BDT' => 'Bangladeshi Taka',
            'PHP' => 'Philippine Peso',
            'NGN' => 'Nigerian Naira',
            'KES' => 'Kenyan Shilling',
            'GHS' => 'Ghanaian Cedi',
            'EGP' => 'Egyptian Pound',
            'MXN' => 'Mexican Peso',
            'JPY' => 'Japanese Yen',
            'CNY' => 'Chinese Yuan',
            'BRL' => 'Brazilian Real',
            'ZAR' => 'South African Rand',
            'AED' => 'UAE Dirham',
            'SAR' => 'Saudi Riyal',
            'LBP' => 'Lebanese Pound',
        ];
        
        return view('transfers.create', compact(
            'beneficiaries',
            'transferServices',
            'promotions',
            'speeds',
            'countries',
            'currencies'
        ));
    }
    
    public function store(Request $request)
    {
        $user = session('user');
        
        $validated = $request->validate([
            'beneficiary_id' => 'required|exists:beneficiaries,id',
            'source_currency' => 'required|string|max:10',
            'target_currency' => 'required|string|max:10',
            'amount' => 'required|numeric|min:1',
            'transfer_speed' => 'required|in:instant,same_day,next_day,standard',
            'payout_method' => 'required|string',
            'promotion_id' => 'nullable|exists:promotions,id',
        ]);
        
        // Get fresh user data from database
        $sender = User::findOrFail($user['id']);
        
        // Get the beneficiary to verify ownership
        $beneficiary = Beneficiary::findOrFail($validated['beneficiary_id']);
        
        if ($beneficiary->user_id != $user['id']) {
            return back()->with('error', 'Unauthorized access to beneficiary.');
        }
        
        // Get exchange rate
        if ($validated['source_currency'] === $validated['target_currency']) {
            // Same currency, no conversion needed
            $rate = 1.0;
        } else {
            $exchangeRate = ExchangeRate::where('base_currency', $validated['source_currency'])
                ->where('target_currency', $validated['target_currency'])
                ->first();
            
            if (!$exchangeRate) {
                return back()->with('error', 'Exchange rate not available for this currency pair.');
            }
            
            $rate = $exchangeRate->rate;
        }
        
        // Calculate amounts
        $amount = $validated['amount'];
        
        // Calculate base fee (simplified - you can make this more sophisticated)
        $transferFee = $this->calculateFee($amount, $validated['transfer_speed']);
        
        // Apply promotion discount if applicable
        $discount = 0;
        if ($validated['promotion_id']) {
            $promotion = Promotion::find($validated['promotion_id']);
            if ($promotion && $promotion->active) {
                $discount = ($transferFee * $promotion->discount_percent) / 100;
            }
        }
        
        $finalFee = $transferFee - $discount;
        $totalPaid = $amount + $finalFee;
        $payoutAmount = $amount * $rate;
        
        // Calculate amount to deduct from wallet (convert if needed)
        $amountToDeduct = $totalPaid;
        if ($sender->currency !== $validated['source_currency']) {
            // Need to convert from wallet currency to source currency to check if user has enough
            $conversionRate = ExchangeRate::where('base_currency', $validated['source_currency'])
                ->where('target_currency', $sender->currency)
                ->first();
            
            if (!$conversionRate) {
                return back()->with('error', 
                    'Cannot process transfer: No exchange rate found from ' . $validated['source_currency'] . 
                    ' to ' . $sender->currency . '. Please contact support.');
            }
            
            // Convert source currency amount to wallet currency
            $amountToDeduct = $totalPaid * $conversionRate->rate;
        }
        
        // Check if sender has sufficient balance in wallet
        if ($sender->balance < $amountToDeduct) {
            return back()->with('error', 
                'Insufficient balance. You need ' . $sender->currency . ' ' . 
                number_format($amountToDeduct, 2) . ' in your wallet but you only have ' . 
                $sender->currency . ' ' . number_format($sender->balance, 2) . '.');
        }
        
        // Use database transaction to ensure data consistency
        DB::beginTransaction();
        
        try {
            // Deduct balance from sender's wallet
            $sender->balance -= $amountToDeduct;
            $sender->save();
            
            // Create the transfer
            $transfer = Transfer::create([
                'sender_id' => $user['id'],
                'beneficiary_id' => $validated['beneficiary_id'],
                'source_currency' => $validated['source_currency'],
                'target_currency' => $validated['target_currency'],
                'amount' => $amount,
                'exchange_rate' => $rate,
                'transfer_fee' => $finalFee,
                'total_paid' => $totalPaid,
                'payout_amount' => $payoutAmount,
                'transfer_speed' => $validated['transfer_speed'],
                'status' => 'pending',
                'promotion_id' => $validated['promotion_id'],
            ]);
            
            // Create payment transaction record
            PaymentTransaction::create([
                'transfer_id' => $transfer->id,
                'user_id' => $sender->id,
                'transaction_type' => 'debit',
                'amount' => $totalPaid,
                'currency' => $validated['source_currency'],
                'status' => 'completed',
                'payment_method' => 'balance',
                'description' => 'Money transfer to ' . $beneficiary->full_name,
            ]);
            
            // Update session with new balance
            session(['user' => $sender->toArray()]);
            
            DB::commit();
            
            return redirect()->route('transfers.show', $transfer->id)
                ->with('success', 'Transfer initiated successfully! Your balance has been deducted.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Transfer failed: ' . $e->getMessage());
        }
    }
    
    public function show($id)
    {
        $user = session('user');
        $transfer = Transfer::with(['beneficiary', 'promotion'])->findOrFail($id);
        
        // Verify the transfer belongs to the user
        if ($transfer->sender_id != $user['id']) {
            abort(403, 'Unauthorized access.');
        }
        
        return view('transfers.show', compact('transfer'));
    }
    
    public function index()
    {
        $user = session('user');
        
        $transfers = Transfer::with(['beneficiary'])
            ->where('sender_id', $user['id'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('transfers.index', compact('transfers'));
    }
    
    private function calculateFee($amount, $speed, $currency = 'USD')
    {
        // Use the ExchangeRateController to calculate base fee
        $baseFee = ExchangeRateController::calculateFee($amount, $currency);
        
        // Apply speed multiplier
        switch ($speed) {
            case 'instant':
                $speedMultiplier = 2.0;
                break;
            case 'same_day':
                $speedMultiplier = 1.5;
                break;
            case 'next_day':
                $speedMultiplier = 1.2;
                break;
            default: // standard
                $speedMultiplier = 1.0;
        }
        
        return $baseFee * $speedMultiplier;
    }
    
    public function calculateQuote(Request $request)
    {
        $validated = $request->validate([
            'source_currency' => 'required|string',
            'target_currency' => 'required|string',
            'amount' => 'required|numeric|min:1',
            'transfer_speed' => 'required|string',
        ]);
        
        $amount = $validated['amount'];
        $sourceCurrency = $validated['source_currency'];
        $targetCurrency = $validated['target_currency'];
        
        // Get exchange rate from ExchangeRateController
        $rate = ExchangeRateController::getRate($sourceCurrency, $targetCurrency);
        
        // Calculate fee using ExchangeRateController
        $fee = $this->calculateFee($amount, $validated['transfer_speed'], $sourceCurrency);
        
        $totalPaid = $amount + $fee;
        $payoutAmount = $amount * $rate;
        
        return response()->json([
            'amount' => number_format($amount, 2),
            'exchange_rate' => number_format($rate, 6),
            'transfer_fee' => number_format($fee, 2),
            'total_paid' => number_format($totalPaid, 2),
            'payout_amount' => number_format($payoutAmount, 2),
            'source_currency' => $sourceCurrency,
            'target_currency' => $targetCurrency,
        ]);
    }
    
    /**
     * Update transfer status and handle completion logic
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,sent,completed,failed',
        ]);
        
        $transfer = Transfer::with('beneficiary')->findOrFail($id);
        $oldStatus = $transfer->status;
        $newStatus = $validated['status'];
        
        // If status is changing to completed, credit the recipient
        if ($oldStatus !== 'completed' && $newStatus === 'completed') {
            DB::beginTransaction();
            
            try {
                // Find recipient by phone number
                $recipient = User::where('phone', $transfer->beneficiary->phone_number)->first();
                
                if (!$recipient) {
                    return back()->with('error', 'Recipient user not found.');
                }
                
                // Convert payout amount to recipient's wallet currency if different
                $amountToCredit = $transfer->payout_amount;
                $creditCurrency = $transfer->target_currency;
                
                if ($transfer->target_currency !== $recipient->currency) {
                    // Need to convert target_currency to recipient's currency
                    if ($transfer->target_currency === $recipient->currency) {
                        // Same currency, no conversion
                        $amountToCredit = $transfer->payout_amount;
                    } else {
                        // Get exchange rate from target_currency to recipient's currency
                        $conversionRate = ExchangeRate::where('base_currency', $transfer->target_currency)
                            ->where('target_currency', $recipient->currency)
                            ->first();
                        
                        if ($conversionRate) {
                            $amountToCredit = $transfer->payout_amount * $conversionRate->rate;
                            $creditCurrency = $recipient->currency;
                        } else {
                            // If no exchange rate found, credit in original currency and warn
                            $amountToCredit = $transfer->payout_amount;
                            $creditCurrency = $transfer->target_currency;
                        }
                    }
                }
                
                // Credit the recipient's balance
                $recipient->balance += $amountToCredit;
                $recipient->save();
                
                // Create payment transaction for recipient
                PaymentTransaction::create([
                    'transfer_id' => $transfer->id,
                    'user_id' => $recipient->id,
                    'transaction_type' => 'credit',
                    'amount' => $amountToCredit,
                    'currency' => $creditCurrency,
                    'status' => 'completed',
                    'payment_method' => 'balance',
                    'description' => 'Money received from transfer' . 
                        ($creditCurrency !== $transfer->target_currency ? 
                            ' (converted from ' . $transfer->target_currency . ')' : ''),
                ]);
                
                // Update transfer status
                $transfer->status = $newStatus;
                $transfer->save();
                
                DB::commit();
                
                return back()->with('success', 'Transfer completed successfully! Recipient has been credited.');
                
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->with('error', 'Failed to complete transfer: ' . $e->getMessage());
            }
        }
        
        // For other status changes, just update the status
        $transfer->status = $newStatus;
        $transfer->save();
        
        return back()->with('success', 'Transfer status updated to ' . $newStatus . '.');
    }
}
