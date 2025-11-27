@include('includes.header')

<style>
body { background-color: #f3f4f6; }
.admin-container { max-width: 1400px; margin: 40px auto; padding: 0 20px; }
.page-header { margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; }
.page-header h1 { font-size: 32px; color: #1a202c; margin: 0; }
.page-header a { text-decoration: none; }
.filter-section { background: white; border-radius: 8px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px; }
.filter-section h3 { font-size: 16px; color: #1a202c; margin: 0 0 16px 0; font-weight: 600; }
.filter-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; }
.form-group { margin-bottom: 0; }
.form-group label { display: block; margin-bottom: 6px; font-weight: 600; color: #4a5568; font-size: 13px; }
.form-group input, .form-group select { width: 100%; padding: 8px 12px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 14px; }
.form-group input:focus, .form-group select:focus { outline: none; border-color: #3b82f6; }
.filter-actions { display: flex; gap: 12px; margin-top: 16px; }
.btn { padding: 10px 24px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 600; text-decoration: none; display: inline-block; }
.btn-primary { background: #3b82f6; color: white; }
.btn-primary:hover { background: #2563eb; }
.btn-secondary { background: #e5e7eb; color: #374151; }
.btn-secondary:hover { background: #d1d5db; }
.btn-sm { padding: 6px 12px; font-size: 12px; }
.data-section { background: white; border-radius: 8px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
.data-table { width: 100%; border-collapse: collapse; }
.data-table th { text-align: left; padding: 12px; background: #f7fafc; color: #4a5568; font-size: 13px; font-weight: 600; border-bottom: 2px solid #e2e8f0; }
.data-table td { padding: 12px; border-bottom: 1px solid #e2e8f0; color: #2d3748; font-size: 14px; }
.data-table tr:hover { background: #f7fafc; }
.badge { display: inline-block; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; }
.badge-success { background: #d1fae5; color: #065f46; }
.badge-danger { background: #fee2e2; color: #991b1b; }
.badge-warning { background: #fef3c7; color: #92400e; }
.badge-info { background: #dbeafe; color: #1e40af; }
.badge-secondary { background: #e5e7eb; color: #374151; }
.empty-state { text-align: center; padding: 60px 20px; color: #718096; }
.export-btn { background: #10b981; color: white; }
.export-btn:hover { background: #059669; }
</style>

<div class="admin-container">
<div class="page-header">
<h1>Transaction Audit Log</h1>
<a href="{{ route('admin.compliance') }}" class="btn btn-secondary"> Back to Compliance</a>
</div>

<!-- Filters -->
<div class="filter-section">
<h3>Filter Transactions</h3>
<form method="GET" action="{{ route('admin.audit-log') }}">
<div class="filter-grid">
<div class="form-group">
<label for="user_id">User</label>
<select id="user_id" name="user_id">
<option value="">All Users</option>
@foreach($users as $user)
<option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
{{ $user->name }} ({{ $user->email }})
</option>
@endforeach
</select>
</div>
<div class="form-group">
<label for="min_amount">Min Amount ($)</label>
<input type="number" id="min_amount" name="min_amount" step="0.01" value="{{ request('min_amount') }}" placeholder="0.00">
</div>
<div class="form-group">
<label for="max_amount">Max Amount ($)</label>
<input type="number" id="max_amount" name="max_amount" step="0.01" value="{{ request('max_amount') }}" placeholder="10000.00">
</div>
<div class="form-group">
<label for="date_from">Date From</label>
<input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}">
</div>
<div class="form-group">
<label for="date_to">Date To</label>
<input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}">
</div>
</div>
<div class="filter-actions">
<button type="submit" class="btn btn-primary">Apply Filters</button>
<a href="{{ route('admin.audit-log') }}" class="btn btn-secondary">Clear Filters</a>
<button type="button" class="btn export-btn" onclick="exportToCSV()">Export to CSV</button>
</div>
</form>
</div>

<!-- Results -->
<div class="data-section">
<p style="color: #718096; margin-bottom: 16px;">Showing {{ $transfers->total() }} transaction(s)</p>
@if($transfers->count() > 0)
<table class="data-table" id="auditTable">
<thead>
<tr>
<th>ID</th>
<th>Date</th>
<th>Sender</th>
<th>Receiver</th>
<th>Amount</th>
<th>Fee</th>
<th>Status</th>
<th>Actions</th>
</tr>
</thead>
<tbody>
@foreach($transfers as $transfer)
<tr>
<td>#{{ $transfer->id }}</td>
<td>{{ $transfer->created_at->format('M d, Y H:i') }}</td>
<td>{{ $transfer->sender->name ?? 'N/A' }}<br><small style="color: #718096;">{{ $transfer->sender->email ?? '' }}</small></td>
<td>{{ $transfer->beneficiary->full_name ?? 'N/A' }}<br><small style="color: #718096;">{{ $transfer->beneficiary->email ?? '' }}</small></td>
<td><strong>${{ number_format($transfer->amount, 2) }}</strong></td>
<td>${{ number_format($transfer->transfer_fee ?? 0, 2) }}</td>
<td>
<span class="badge badge-{{ 
$transfer->status === 'completed' ? 'success' : 
($transfer->status === 'failed' ? 'danger' : 
($transfer->status === 'cancelled' ? 'secondary' : 'info')) 
}}">
{{ ucfirst($transfer->status) }}
</span>
</td>
<td>
<a href="{{ route('admin.transfers.show', $transfer->id) }}" class="btn btn-sm btn-primary">View</a>
</td>
</tr>
@endforeach
</tbody>
</table>
<div style="margin-top: 20px;">{{ $transfers->appends(request()->query())->links() }}</div>
@else
<div class="empty-state">
<p>No transactions found matching your criteria</p>
</div>
@endif
</div>
</div>

<script>
function exportToCSV() {
const table = document.getElementById('auditTable');
let csv = [];
const rows = table.querySelectorAll('tr');
    
for (let i = 0; i < rows.length; i++) {
const row = [], cols = rows[i].querySelectorAll('td, th');
for (let j = 0; j < cols.length - 1; j++) { // Skip last column (Actions)
let text = cols[j].innerText.replace(/\n/g, ' ').replace(/"/g, '""');
row.push('"' + text + '"');
}
csv.push(row.join(','));
}
    
const csvFile = new Blob([csv.join('\n')], { type: 'text/csv' });
const downloadLink = document.createElement('a');
downloadLink.download = 'audit_log_' + new Date().toISOString().split('T')[0] + '.csv';
downloadLink.href = window.URL.createObjectURL(csvFile);
downloadLink.style.display = 'none';
document.body.appendChild(downloadLink);
downloadLink.click();
document.body.removeChild(downloadLink);
}
</script>

</main></body></html>
