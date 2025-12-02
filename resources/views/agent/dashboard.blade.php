<h2>Welcome, {{ $agent->store_name }}</h2>

@if(session('success'))
    <p style="color:green;">{{ session('success') }}</p>
@endif
@if(session('error'))
    <p style="color:red;">{{ session('error') }}</p>
@endif

<table>
    <thead>
        <tr>
            <th>Sender</th>
            <th>Beneficiary</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($transfers as $transfer)
        <tr>
            <td>{{ $transfer->sender->name }}</td>
            <td>{{ $transfer->beneficiary->full_name }}</td>
            <td>{{ number_format($transfer->amount, 2) }} {{ $transfer->source_currency }}</td>
            <td>{{ ucfirst($transfer->status) }}</td>
            <td>
                @if($transfer->status === 'pending')
                    <form action="{{ route('agent.transfer.process', $transfer->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit">Process</button>
                    </form>
                @elseif($transfer->status === 'processing')
                    <form action="{{ route('agent.transfer.complete', $transfer->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit">Complete Payout</button>
                    </form>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>











