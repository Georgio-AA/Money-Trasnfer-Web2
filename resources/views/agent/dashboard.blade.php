<h2>Incoming Transfers</h2>

@if($incomingTransfers->isEmpty())
    <p>No pending transfers.</p>
@else
    <table>
        <thead>
            <tr>
                <th>Sender</th>
                <th>Amount</th>
                <th>Beneficiary Name</th>
                <th>Beneficiary Phone Number</th>
                <th>Payout Method</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($incomingTransfers as $t)
            <tr>
                <td>{{ $t->sender->name }}</td>
                <td>{{ $t->amount }} {{ $t->currency }}</td>
                <td>{{ $t->beneficiary->full_name }}</td>
                <td>{{ $t->beneficiary->phone_number }}</td>
                <td>{{ $t->payout_method }}</td>
                <td><a href="{{ route('agent.transfer.process', $t->id) }}">Process</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif
