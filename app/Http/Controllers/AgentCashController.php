namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AgentTransaction;
use App\Models\Agent;

class AgentCashController extends Controller
{
    public function index()
    {
        $user = session('user');
        $agent = Agent::where('user_id', $user['id'])->first();

        $transactions = AgentTransaction::where('agent_id', $agent->id)->latest()->get();

        return view('agent.cash.index', compact('agent', 'transactions'));
    }

    public function create()
    {
        return view('agent.cash.create');
    }

    public function store(Request $request)
    {
        $user = session('user');
        $agent = Agent::where('user_id', $user['id'])->first();

        $validated = $request->validate([
            'type' => 'required|in:cash-in,cash-out',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|max:10',
            'transfer_id' => 'nullable|exists:transfers,id'
        ]);

        AgentTransaction::create([
            'agent_id' => $agent->id,
            'type' => $validated['type'],
            'amount' => $validated['amount'],
            'currency' => $validated['currency'],
            'transfer_id' => $validated['transfer_id'] ?? null,
            'status' => 'completed', // can be pending if needed
        ]);

        return redirect()->route('agent.cash.index')->with('success', 'Transaction recorded successfully.');
    }
}
