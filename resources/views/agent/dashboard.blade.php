<link rel="stylesheet" href="{{ asset('css/agent.css') }}">

<h2>Welcome, {{ $agent->store_name }}</h2>

@if(session('success'))
    <p style="color:green;font-weight: 600;background:#f0f9ff;padding:10px;border-radius:8px;">{{ session('success') }}</p>
@endif
@if(session('error'))
    <p style="color:red;font-weight: 600;background:#fee2e2;padding:10px;border-radius:8px;">{{ session('error') }}</p>
@endif
<!-- Notifications Toggle Button -->
<div id="notification-panel" class="notification-panel">
    <div class="panel-header">
        <span>Notifications</span>
        <button id="close-panel">&times;</button>
    </div>
    <ul>
        @if($notifications->isEmpty())
            <li>No new notifications</li>
        @else
            @foreach($notifications as $note)
                <li style="{{ $note->read ? 'opacity:0.6;' : 'font-weight:bold;' }}">
                    {{ $note->message }}
                    <span style="font-size:12px;">({{ $note->created_at->diffForHumans() }})</span>
                </li>
            @endforeach
        @endif
    </ul>
</div>

<button id="toggle-panel" class="toggle-btn">🔔 Notifications</button>



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
                        <option value="completed" {{ $transfer->status === 'completed' ? 'selected' : '' }}>Completed (Credits Recipient)</option>
                    </form>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<button onclick="window.location='{{ route('agent.commissions') }}'">View Your Commissions</button>
<button onclick="window.location='{{ route('home') }}'">Back to Home</button>







<script>
const panel = document.getElementById('notification-panel');
const toggleBtn = document.getElementById('toggle-panel');
const closeBtn = document.getElementById('close-panel');

toggleBtn.addEventListener('click', () => {
    panel.style.right = '0';
});

closeBtn.addEventListener('click', () => {
    panel.style.right = '-300px';
});
</script>



