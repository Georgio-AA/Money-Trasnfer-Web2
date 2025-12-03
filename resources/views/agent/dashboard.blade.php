<style>
:root{
  --primary:#2563eb;
  --accent:#06b6d4;
  --muted:#6b7280;
  --card-bg:#ffffff;
  --page-bg: linear-gradient(180deg,#0e5474 0%, #95c2d5ff 100%);
  --radius:12px;
}

body {
  font-family: Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
  margin:0;
  padding:20px;
  background: var(--page-bg);
  color:#0f172a;
}

h2 {
  text-align:center;
  font-size:24px;
  font-weight:700;
  color:#fff;
  margin-bottom:20px;
  text-shadow: 0 1px 3px rgba(0,0,0,0.3);
}

.agent-container {
  max-width: 1000px;
  margin: 0 auto;
  padding: 0 20px;
}

/* Flash messages */
.agent-container p {
  text-align:center;
  font-weight:600;
  margin:10px 0;
}
.agent-container p.success { color:#10b981; }
.agent-container p.error { color:#ef4444; }

/* Table card */
.table-card {
  background: var(--card-bg);
  border-radius: var(--radius);
  padding:20px;
  box-shadow: 0 6px 20px rgba(2,6,23,0.35);
  overflow-x:auto;
  margin-bottom:20px;
}

/* Table styling */
table {
  width:100%;
  border-collapse: collapse;
  font-size:14px;
}

thead {
  background: linear-gradient(90deg, #2563eb, #6d86c9ff);
  color:white;
}

thead th {
  padding:10px 12px;
  text-align:left;
  font-weight:600;
}

tbody tr {
  border-bottom:1px solid #e5e7eb;
  transition: background 0.2s;
}

tbody tr:hover {
  background:#f0f9ff;
}

tbody td {
  padding:8px 12px;
   font-weight: 600;
}

button {
  background: linear-gradient(90deg, #2563eb, #1d4ed8);
  color:white;
  border:none;
  border-radius:8px;
  padding:6px 12px;
  font-weight:600;
  cursor:pointer;
  transition: transform 0.1s, box-shadow 0.1s;
}
button:hover {
  transform: translateY(-1px);
  box-shadow: 0 6px 18px rgba(37,99,235,0.2);
}

/* Commission button */
.agent-container > button {
  display:block;
  margin:20px auto 0 auto;
  max-width:220px;
}

/* Status badges */
.status-badge {
  display:inline-block;
  padding:4px 10px;
  border-radius:12px;
  font-size:12px;
  font-weight:600;
  text-transform:capitalize;
}
.status-pending { background:#fef3c7; color:#92400e; }
.status-processing { background:#dbeafe; color:#1e40af; }
.status-completed { background:#d1fae5; color:#065f46; }

/* Responsive */
@media(max-width:768px){
  table, thead, tbody, th, td, tr {
    display:block;
  }
  thead tr { display:none; }
  tbody tr {
    margin-bottom:12px;
    border-bottom:2px solid #e5e7eb;
    padding:8px;
  }
  tbody td {
    padding:4px 0;
    display:flex;
    justify-content:space-between;
    position:relative;
  }
  tbody td::before {
    content: attr(data-label);
    font-weight:600;
    color: var(--muted);
  }
}
</style>


<h2>Welcome, {{ $agent->store_name }}</h2>

@if(session('success'))
    <p style="color:green;font-weight: 600;background:#f0f9ff;padding:10px;border-radius:8px;">{{ session('success') }}</p>
@endif
@if(session('error'))
    <p style="color:red;font-weight: 600;background:#fee2e2;padding:10px;border-radius:8px;">{{ session('error') }}</p>
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
<button onclick="window.location='{{ route('agent.commissions') }}'">View Your Commissions</button>











