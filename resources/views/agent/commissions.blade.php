<h2>Completed Agent Payouts</h2>

@if($completedTransfers->isEmpty())
    <p>No transfers completed yet.</p>
@else
    <table>
        <thead>
            <tr>
                <th>Transfer ID</th>
                <th>Amount Paid Out</th>
                <th>Commission Earned</th>
                <th>Recipient Name</th>
            </tr>
        </thead>
        <tbody>
        @foreach($completedTransfers as $t)
            <tr>
                <td>#{{ $t->id }}</td>
                <td>{{ number_format($t->payout_amount, 2) }} {{ $t->target_currency }}</td>
                <td>{{ number_format($t->agent_commission ?? 0, 2) }} {{ $t->target_currency }}</td>
                <td>{{ $t->beneficiary->full_name ?? 'N/A' }}</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2"><strong>Total Commission:</strong></td>
                <td colspan="3"><strong>{{ number_format($totalCommission, 2) }}</strong> {{ $t->target_currency ?? '' }}</td>
            </tr>
        </tfoot>
    </table>
@endif
<button onclick="window.location='{{ route('agent.dashboard') }}'">Back to Dashboard</button>

<link rel="stylesheet" href="{{ asset('css/agent.css') }}">
