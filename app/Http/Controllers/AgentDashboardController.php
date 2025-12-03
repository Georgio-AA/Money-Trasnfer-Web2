<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agent;
use App\Models\Transfer;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\User;

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

        return view('agent.dashboard', compact('agent', 'transfers'));
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
/*
    public function completePayout(Request $request, $id)
    {
        $transfer = Transfer::findOrFail($id);
        $user = session('user');
        $agent = Agent::where('user_id', $user['id'])->firstOrFail();

        // 1. Authorization and Status Check (CRITICAL)
        //if ($transfer->agent_id != $agent->id || $transfer->status !== 'processing') {
          //  return redirect()->route('agent.dashboard')->with('error', 'Unauthorized or transfer cannot be completed.');
        //}

        // 2. Simple Commission Calculation (Assuming 0.5% of the Payout Amount)
        // NOTE: You must ensure 'agent_commission' column is added if you want to track it.
        // Since it's NOT in your provided list, we'll use a placeholder variable and proceed.
        // If commission is required, you MUST add this column.
        
        // --- If commission tracking is absolutely required, you must add the column ---
        // Let's assume you will create a migration to add `agent_commission` later.
        $commissionRate = $agent->commission_rate ; // 0.5% example
        $commissionAmount = $transfer->payout_amount * $commissionRate; // Based on amount received by beneficiary
        
        // 3. Update Transfer
        $transfer->status = 'completed'; 
        $transfer->agent_commission = $commissionAmount; // <-- Requires migration
        $transfer->completed_at = now(); // Use existing 'completed_at' column
        $transfer->save();

        return redirect()->route('agent.dashboard')->with('success', 'Payout completed successfully.');
    }
*/public function completePayout(Request $request, $id)
{
    $user = session('user');
    $agent = Agent::where('user_id', $user['id'])->firstOrFail();

    // Only transfers assigned to this agent and in processing
    $transfer = Transfer::where('agent_id',$user['id'])
        ->where('status', 'processing')
        ->findOrFail($id);

    // Commission calculation
    $commissionRate = $agent->commission_rate ?? 0; // fallback if null
    $commissionAmount = $transfer->payout_amount * $commissionRate;
\Log::info("Incrementing balance by amount1: " . $transfer->payout_amount);
    // --- Begin DB transaction for safety ---
    
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
                $transfer->save(); // <--- THIS IS MISSING
                
                DB::commit();
                
                return back()->with('success', 'Transfer completed successfully! Recipient has been credited.');
                
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->with('error', 'Failed to complete transfer: ' . $e->getMessage());
            }
    return redirect()->route('agent.dashboard')
        ->with('success', 'Payout completed and beneficiary balance updated.');
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

/*
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agent;
use App\Models\Transfer;
use Illuminate\Support\Facades\Session;
class AgentDashboardController extends Controller
{
    public function index()
    {
        $user = session('user'); // your manual session
$agent = Agent::where('user_id', $user['id'])->first();

if (!$agent) {
    abort(403, 'You are not an agent.');
}


        // Transfers where this agent is assigned
        $transfers = Transfer::with(['sender', 'beneficiary'])
            ->where('agent_id', $agent->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('agent.dashboard', compact('agent', 'transfers'));
    }
    public function processTransfer($id)
{
    $agent = auth()->user()->agentProfile;

    $transfer = Transfer::where('agent_id', $agent->id)->findOrFail($id);

    $transfer->update(['status' => 'processing']);

    return back()->with('success', 'Transfer marked as processing.');
}

}








class AgentDashboardController extends Controller
{
    private function authorizeAgent()
    {
        $user = Session::get('user');

        // 1. Check if user is logged in and has the 'agent' role
        if (!$user || ($user['role'] ?? null) !== 'agent') {
            return null;
        }

        // 2. Retrieve the Agent model instance (which confirms approval)
        $agent = Agent::where('user_id', $user['id'])->first();

        // 3. Check if the Agent record exists and is approved (assuming approval is inherent if the Agent record exists)
        if (!$agent) {
             // If the user has the 'agent' role but no agent profile, maybe they are pending review.
             // For now, we deny access to the dashboard until the Agent model exists.
             return null;
        }

        return $agent;
    }

    public function index()
    {
        $agent = $this->authorizeAgent();

        if (!$agent) {
            // Redirect unauthorized users to login or a generic error page
            return redirect()->route('login')->with('error', 'Unauthorized access to the Agent Dashboard.');
        }

        $incomingTransfers = Transfer::where('agent_id', $agent->id)
            // Status for transfers pending agent action
            ->where('status', 'pending') 
            ->with(['sender', 'beneficiary'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('agent.dashboard', compact('agent', 'incomingTransfers'));
    }

    public function process($id)
    {
        $agent = $this->authorizeAgent();

        if (!$agent) {
            return redirect()->route('login')->with('error', 'Unauthorized access.');
        }

        $transfer = Transfer::findOrFail($id);
        
        // Ensure the transfer belongs to this agent and is in the correct status
        if ($transfer->agent_id != $agent->id || $transfer->status !== 'pending') {
             return redirect()->route('agent.dashboard')->with('error', 'Transfer is not available for processing.');
        }
        
        return view('agent.transfer_process', compact('transfer', 'agent'));
    }


    public function completePayout(Request $request, $id)
    {
        $agent = $this->authorizeAgent();

        if (!$agent) {
            return redirect()->route('login')->with('error', 'Unauthorized access.');
        }

        $transfer = Transfer::findOrFail($id);

        // 1. Authorization and Status Check (CRITICAL)
        if ($transfer->agent_id != $agent->id || $transfer->status !== 'pending') {
            return redirect()->route('agent.dashboard')->with('error', 'Unauthorized or transfer cannot be completed.');
        }

        // 2. Update Transfer status
        $transfer->status = 'completed'; 
        // Assuming you need commission logic here later. Placeholder for now:
        // $commissionRate = 0.005; // 0.5% example
        // $transfer->agent_commission = $transfer->payout_amount * $commissionRate;
        
        $transfer->completed_at = now(); // Use existing 'completed_at' column
        $transfer->save();

        return redirect()->route('agent.dashboard')->with('success', "Payout for transfer #$id completed successfully.");
    }
    
    
    public function commissionHistory()
    {
        $agent = $this->authorizeAgent();

        if (!$agent) {
            return redirect()->route('login')->with('error', 'Unauthorized access.');
        }
        
        $completedTransfers = Transfer::where('agent_id', $agent->id)
            ->where('status', 'completed')
            ->orderBy('completed_at', 'desc')
            ->get();

        return view('agent.commissions', compact('completedTransfers'));
    }
}*/
/*
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Agent;    
use App\Models\Transfer;

class AgentDashboardController extends Controller
{
     
public function index()
    {
        $user = session('user');
        $agent = Agent::where('user_id', $user['id'])->firstOrFail();

        $incomingTransfers = Transfer::where('agent_id', $agent->id)
            // Filter by the specific status set during creation
            ->where('status', 'agent_pickup_pending') 
            ->with(['sender', 'beneficiary'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('agent.dashboard', compact('agent', 'incomingTransfers'));
    }

    public function process($id)
    {
        $transfer = Transfer::findOrFail($id);
        return view('agent.process', compact('transfer'));
    }
// NEW METHOD: Handles the completion of the payout
    public function completePayout(Request $request, $id)
    {
        $transfer = Transfer::findOrFail($id);
        $user = session('user');
        $agent = Agent::where('user_id', $user['id'])->firstOrFail();

        // 1. Authorization and Status Check (CRITICAL)
        if ($transfer->agent_id != $agent->id || $transfer->status !== 'agent_pickup_pending') {
            return redirect()->route('agent.dashboard')->with('error', 'Unauthorized or transfer cannot be completed.');
        }

        // 2. Simple Commission Calculation (Assuming 0.5% of the Payout Amount)
        // NOTE: You must ensure 'agent_commission' column is added if you want to track it.
        // Since it's NOT in your provided list, we'll use a placeholder variable and proceed.
        // If commission is required, you MUST add this column.
        
        // --- If commission tracking is absolutely required, you must add the column ---
        // Let's assume you will create a migration to add `agent_commission` later.
        $commissionRate = 0.005; // 0.5% example
        $commissionAmount = $transfer->payout_amount * $commissionRate; // Based on amount received by beneficiary
        
        // 3. Update Transfer
        $transfer->status = 'completed'; 
        // $transfer->agent_commission = $commissionAmount; // <-- Requires migration
        $transfer->completed_at = now(); // Use existing 'completed_at' column
        $transfer->save();

        return redirect()->route('agent.dashboard')->with('success', 'Payout completed successfully.');
    }

    // NEW METHOD: To track completed transfers and "Commissions"
    public function commissionHistory()
    {
        $user = session('user');
        $agent = Agent::where('user_id', $user['id'])->firstOrFail();
        
        $completedTransfers = Transfer::where('agent_id', $agent->id)
            ->where('status', 'completed')
            ->orderBy('completed_at', 'desc')
            ->get();
            
        // NOTE: If 'agent_commission' column is missing, this sum will fail. 
        // We will assume you will adjust this to display total transfers if commission tracking is impossible.
        // $totalCommission = $completedTransfers->sum('agent_commission'); 

        return view('agent.commissions', compact('completedTransfers'));
    }
}
*/