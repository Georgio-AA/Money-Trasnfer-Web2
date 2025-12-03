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

<style>
:root{
  --primary:#2563eb;
  --accent:#06b6d4;
  --muted:#6b7280;
  --card-bg:#ffffff;
  --page-bg: linear-gradient(180deg,#0e5474 0%, #8db8cbff 100%);
  --radius:12px;
}

body {
  font-family: Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
  margin:0;
  padding:20px;
  background: var(--page-bg);
  color:#0f172a;
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
h2 {
  text-align:center;
  font-size:22px;
  font-weight:700;
  margin-bottom:20px;
  color:#fff;
  text-shadow: 0 1px 3px rgba(0,0,0,0.3);
}

/* Card container */
.table-card {
  background: var(--card-bg);
  padding:20px;
  border-radius: var(--radius);
  box-shadow: 0 6px 20px rgba(2,6,23,0.35);
  max-width:900px;
  margin:0 auto;
  overflow-x:auto;
}

/* Table style */
table {
  width:100%;
  border-collapse: separate;
  border-spacing:0;
  font-size:14px;
}

thead {
  background: linear-gradient(90deg, #7b9adcff, #1d4ed8);
  color:#fff;
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
  background: #f0f9ff;
}

tbody td {
  padding:8px 12px;
   font-weight: 600;
}

tfoot td {
  padding:10px 12px;
  font-weight:700;
  background: #f3f4f6;
  border-top:2px solid #2563eb;
}

tfoot tr strong {
  color: #2563eb;
}

/* Responsive */
@media(max-width:768px){
  table, thead, tbody, th, td, tfoot, tr {
    display:block;
  }
  thead tr {
    display:none;
  }
  tbody tr {
    margin-bottom:12px;
    border-bottom:2px solid #e5e7eb;
    padding:8px;
  }
  tbody td {
    padding:4px 0;
    display:flex;
    justify-content:space-between;
    position: relative;
  }
  tbody td::before {
    content: attr(data-label);
    font-weight:600;
    color: var(--muted);
  }
  tfoot td {
    display:block;
    text-align:right;
  }
}

p {
  text-align:center;
  font-size:14px;
  color:#fff;
  margin-top:20px;
}
</style>
