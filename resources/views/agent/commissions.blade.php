<h2>Completed Agent Payouts</h2>

@if($completedTransfers->isEmpty())
    <p>No transfers completed yet.</p>
@else
    <table>
        <thead>
            <tr>
                <th>Transfer ID</th>
                <th>Amount Paid Out</th>
                <th>Recipient Name</th>
                <th>Completion Date</th>
            </tr>
        </thead>
        <tbody>
        @foreach($completedTransfers as $t)
            <tr>
                <td>#{{ $t->id }}</td>
                <td>{{ number_format($t->payout_amount, 2) }} {{ $t->target_currency }}</td>
                <td>{{ $t->beneficiary->full_name ?? 'N/A' }}</td>
                <td>{{ $t->completed_at ? $t->completed_at->format('M d, Y H:i') : 'N/A' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif
