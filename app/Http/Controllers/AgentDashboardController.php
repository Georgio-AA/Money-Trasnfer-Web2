<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agent;
use App\Models\Transfer;
use App\Models\ExchangeRate;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\AgentNotification;

class AgentDashboardController extends Controller
{
    public function index()
    {
        $user = session('user'); // logged-in agent
        $agent = Agent::where('user_id', $user['id'])->first();

        if (!$agent) {
            abort(403, 'You are not an agent.');
        }



        // Transfers where this agent is assigned
        $transfers = Transfer::with(['sender', 'beneficiary'])
            ->where('agent_id', $user['id']) // use users.id, not agents.id
            ->orderBy('created_at', 'desc')
            ->get();

        $notifications = AgentNotification::where('agent_id', $user['id'])
    ->orderBy('created_at', 'desc')
    ->get();

        return view('agent.dashboard', compact('agent', 'transfers', 'notifications'));
    }

    public function processTransfer($id)
    {
        $user = session('user');
        $agent = Agent::where('user_id', $user['id'])->first();

        if (!$agent) {
            abort(403, 'You are not an agent.');
        }

        // Only allow processing transfers assigned to this agent
        $transfer = Transfer::where('agent_id', $user['id'])->findOrFail($id);

        $transfer->update(['status' => 'processing']);

        return back()->with('success', 'Transfer is now processing.');
    }
    
    public function completePayout(Request $request, $id)
{
    $user = session('user');
    $agent = Agent::where('user_id', $user['id'])->firstOrFail();

    // Only transfers assigned to this agent and in processing
    $transfer = Transfer::where('agent_id',$user['id'])
        ->where('status', 'processing')
        ->findOrFail($id);

    // Commission calculation
    $commissionRate = $agent->commission_rate ?? 0;
    $commissionAmount = $transfer->payout_amount * ($commissionRate / 100);
    \Log::info("Incrementing balance by amount1: " . $transfer->payout_amount);
    
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

                // Update transfer
                $transfer->status = 'completed';
                $transfer->agent_commission = $commissionAmount;
                $transfer->completed_at = now();
                $transfer->save();

                DB::commit();

                return redirect()->route('agent.dashboard')
                    ->with('success', 'Payout completed and beneficiary balance updated.');
            } catch (\Throwable $e) {
                DB::rollBack();
                return back()->with('error', 'Failed to complete transfer: ' . $e->getMessage());
            }
}


    // NEW METHOD: To track completed transfers and "Commissions"
    public function commissionHistory()
    {
        $user = session('user');
        $agent = Agent::where('user_id', $user['id'])->firstOrFail();
        
        $completedTransfers = Transfer::where('agent_id', $user['id'])
            ->where('status', 'completed')
            ->orderBy('completed_at', 'desc')
            ->get();
            
        // NOTE: If 'agent_commission' column is missing, this sum will fail. 
        // We will assume you will adjust this to display total transfers if commission tracking is impossible.
        // $totalCommission = $completedTransfers->sum('agent_commission'); 

        $totalCommission = $completedTransfers->sum(function ($transfer) {
        return $transfer->agent_commission ?? 0;
    });

        return view('agent.commissions', compact('completedTransfers', 'totalCommission'));
    }
}
