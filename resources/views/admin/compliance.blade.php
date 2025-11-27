@include('includes.header')

<style>
body { background-color: #f3f4f6; }
.admin-container { max-width: 1400px; margin: 40px auto; padding: 0 20px; }
.page-header { margin-bottom: 40px; }
.page-header h1 { font-size: 32px; color: #1a202c; margin: 0 0 10px 0; }
.page-header p { color: #718096; font-size: 16px; margin: 0; }
.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
.stat-card { background: white; border-radius: 8px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
.stat-card h3 { font-size: 14px; color: #718096; margin: 0 0 8px 0; text-transform: uppercase; font-weight: 600; }
.stat-card .value { font-size: 36px; font-weight: bold; margin: 0; }
.stat-card.alert { border-left: 4px solid #ef4444; }
.stat-card.alert .value { color: #ef4444; }
.stat-card.warning { border-left: 4px solid #f59e0b; }
.stat-card.warning .value { color: #f59e0b; }
.stat-card.info { border-left: 4px solid #3b82f6; }
.stat-card.info .value { color: #3b82f6; }
.section { background: white; border-radius: 8px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 30px; }
.section h2 { font-size: 20px; color: #1a202c; margin: 0 0 20px 0; font-weight: 600; }
.data-table { width: 100%; border-collapse: collapse; }
.data-table th { text-align: left; padding: 12px; background: #f7fafc; color: #4a5568; font-size: 14px; font-weight: 600; border-bottom: 2px solid #e2e8f0; }
.data-table td { padding: 12px; border-bottom: 1px solid #e2e8f0; color: #2d3748; font-size: 14px; }
.data-table tr:hover { background: #f7fafc; }
.badge { display: inline-block; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; }
.badge-danger { background: #fee2e2; color: #991b1b; }
.badge-warning { background: #fef3c7; color: #92400e; }
.badge-success { background: #d1fae5; color: #065f46; }
.badge-info { background: #dbeafe; color: #1e40af; }
.badge-secondary { background: #e5e7eb; color: #374151; }
.btn { padding: 8px 16px; border: none; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-block; }
.btn-sm { padding: 6px 12px; font-size: 12px; }
.btn-primary { background: #3b82f6; color: white; }
.btn-primary:hover { background: #2563eb; }
.btn-danger { background: #ef4444; color: white; }
.btn-danger:hover { background: #dc2626; }
.btn-secondary { background: #6b7280; color: white; }
.btn-secondary:hover { background: #4b5563; }
.alert-box { padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; font-size: 14px; }
.alert-success { background: #d1fae5; color: #065f46; border-left: 4px solid #10b981; }
.alert-info { background: #dbeafe; color: #1e40af; border-left: 4px solid #3b82f6; }
.empty-state { text-align: center; padding: 60px 20px; color: #718096; }
.empty-state svg { width: 64px; height: 64px; margin-bottom: 16px; opacity: 0.5; }
.tabs { display: flex; gap: 8px; margin-bottom: 20px; border-bottom: 2px solid #e2e8f0; }
.tab { padding: 12px 24px; border: none; background: none; cursor: pointer; font-size: 14px; font-weight: 600; color: #718096; border-bottom: 2px solid transparent; margin-bottom: -2px; }
.tab.active { color: #3b82f6; border-bottom-color: #3b82f6; }
.tab:hover { color: #2d3748; }
.tab-content { display: none; }
.tab-content.active { display: block; }
.modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); }
.modal-content { background: white; margin: 10% auto; padding: 32px; border-radius: 8px; max-width: 500px; }
.modal-header { margin-bottom: 20px; }
.modal-header h3 { margin: 0; font-size: 20px; color: #1a202c; }
.modal-footer { margin-top: 24px; display: flex; gap: 12px; justify-content: flex-end; }
.form-group { margin-bottom: 16px; }
.form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568; font-size: 14px; }
.form-group textarea { width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 14px; font-family: inherit; resize: vertical; }
</style>

<div class="admin-container">
<div class="page-header">
<h1>Compliance & Monitoring</h1>
<p>Monitor transactions and ensure regulatory compliance</p>
</div>

@if(session('success'))
<div class="alert-box alert-success">{{ session('success') }}</div>
@endif

<!-- Statistics -->
<div class="stats-grid">
<div class="stat-card warning">
<h3>High-Value Today</h3>
<div class="value">{{ $stats['high_value_count'] }}</div>
</div>
<div class="stat-card alert">
<h3>Flagged Users</h3>
<div class="value">{{ $stats['flagged_users'] }}</div>
</div>
<div class="stat-card info">
<h3>Pending Review</h3>
<div class="value">{{ $stats['pending_review'] }}</div>
</div>
<div class="stat-card alert">
<h3>Limit Violations</h3>
<div class="value">{{ $stats['limit_violations'] }}</div>
</div>
</div>

<!-- Tabs -->
<div class="section">
<div class="tabs">
<button class="tab active" onclick="switchTab('high-value')">High-Value Transactions</button>
<button class="tab" onclick="switchTab('suspicious')">Suspicious Activity</button>
<button class="tab" onclick="switchTab('limits')">Limit Violations</button>
<button class="tab" onclick="switchTab('alerts')">Recent Alerts</button>
<button class="tab" onclick="switchTab('audit')">Audit Log</button>
</div>

<!-- High-Value Transactions Tab -->
<div id="high-value" class="tab-content active">
<h2>High-Value Transactions (>,000)</h2>
@if($highValueTransfers->count() > 0)
<table class="data-table">
<thead>
<tr>
<th>ID</th>
<th>Sender</th>
<th>Receiver</th>
<th>Amount</th>
<th>Status</th>
<th>Date</th>
<th>Actions</th>
</tr>
</thead>
<tbody>
@foreach($highValueTransfers as $transfer)
<tr>
<td>#{{ $transfer->id }}</td>
<td>{{ $transfer->sender->name ?? 'N/A' }}</td>
<td>{{ $transfer->beneficiary->full_name ?? 'N/A' }}</td>
<td><strong>${{ number_format($transfer->amount, 2) }}</strong></td>
<td><span class="badge badge-{{ $transfer->status === 'completed' ? 'success' : ($transfer->status === 'pending' ? 'warning' : 'info') }}">{{ ucfirst($transfer->status) }}</span></td>
<td>{{ $transfer->created_at->format('M d, Y H:i') }}</td>
<td>
<a href="{{ route('admin.transfers.show', $transfer->id) }}" class="btn btn-sm btn-primary">View</a>
<button onclick="openFlagModal({{ $transfer->id }})" class="btn btn-sm btn-danger">Flag</button>
</td>
</tr>
@endforeach
</tbody>
</table>
<div style="margin-top: 20px;">{{ $highValueTransfers->links() }}</div>
@else
<div class="empty-state"><p>No high-value transactions found</p></div>
@endif
</div>

<!-- Suspicious Activity Tab -->
<div id="suspicious" class="tab-content">
<h2>Suspicious Activity Patterns</h2>
<div class="alert-box alert-info" style="margin-bottom: 20px;">
Users with more than 5 transfers in the last 24 hours are flagged for review.
</div>
@if(count($suspiciousUsers) > 0)
<table class="data-table">
<thead>
<tr>
<th>User ID</th>
<th>Name</th>
<th>Email</th>
<th>Transfers (24h)</th>
<th>Actions</th>
</tr>
</thead>
<tbody>
@foreach($suspiciousUsers as $user)
<tr>
<td>#{{ $user->id }}</td>
<td>{{ $user->name }}</td>
<td>{{ $user->email }}</td>
<td><span class="badge badge-danger">{{ $user->transfer_count }} transfers</span></td>
<td>
<a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-primary">View Profile</a>
<a href="{{ route('admin.transfers.index') }}?user_id={{ $user->id }}" class="btn btn-sm btn-secondary">View Transfers</a>
</td>
</tr>
@endforeach
</tbody>
</table>
@else
<div class="empty-state"><p>No suspicious activity detected</p></div>
@endif
</div>

<!-- Limit Violations Tab -->
<div id="limits" class="tab-content">
<h2>Daily Limit Violations</h2>
<div class="alert-box alert-info" style="margin-bottom: 20px;">
Users who exceeded their daily transfer limit (configured in settings).
</div>
@if(count($limitExceeded) > 0)
<table class="data-table">
<thead>
<tr>
<th>User ID</th>
<th>Name</th>
<th>Email</th>
<th>Today's Total</th>
<th>Actions</th>
</tr>
</thead>
<tbody>
@foreach($limitExceeded as $user)
<tr>
<td>#{{ $user->id }}</td>
<td>{{ $user->name }}</td>
<td>{{ $user->email }}</td>
<td><span class="badge badge-danger">${{ number_format($user->daily_total, 2) }}</span></td>
<td>
<a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-primary">View Profile</a>
<a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-secondary">Edit</a>
</td>
</tr>
@endforeach
</tbody>
</table>
@else
<div class="empty-state"><p>No limit violations today</p></div>
@endif
</div>

<!-- Recent Alerts Tab -->
<div id="alerts" class="tab-content">
<h2>Recent Compliance Alerts</h2>
@if(count($alerts) > 0)
<table class="data-table">
<thead>
<tr>
<th>Type</th>
<th>Transfer ID</th>
<th>Amount</th>
<th>Reason</th>
<th>Status</th>
<th>Flagged At</th>
</tr>
</thead>
<tbody>
@foreach($alerts as $alert)
<tr>
<td><span class="badge badge-warning">{{ ucfirst(str_replace('_', ' ', $alert['type'])) }}</span></td>
<td>#{{ $alert['transfer_id'] }}</td>
<td>${{ number_format($alert['amount'], 2) }}</td>
<td>{{ $alert['reason'] }}</td>
<td><span class="badge badge-{{ $alert['status'] === 'resolved' ? 'success' : 'warning' }}">{{ ucfirst(str_replace('_', ' ', $alert['status'])) }}</span></td>
<td>{{ \Carbon\Carbon::parse($alert['flagged_at'])->format('M d, Y H:i') }}</td>
</tr>
@endforeach
</tbody>
</table>
@else
<div class="empty-state"><p>No recent alerts</p></div>
@endif
</div>

<!-- Audit Log Tab -->
<div id="audit" class="tab-content">
<h2>Transaction Audit Log</h2>
<div class="alert-box alert-info" style="margin-bottom: 20px;">
<a href="{{ route('admin.audit-log') }}" class="btn btn-primary">Open Full Audit Log with Filters</a>
</div>
<p style="color: #718096;">Access the comprehensive audit log to review all completed, failed, and cancelled transactions with advanced filtering options.</p>
</div>
</div>
</div>

<!-- Flag Transaction Modal -->
<div id="flagModal" class="modal">
<div class="modal-content">
<div class="modal-header">
<h3>Flag Transaction for Review</h3>
</div>
<form id="flagForm" method="POST">
@csrf
<div class="form-group">
<label for="reason">Reason for Flagging *</label>
<textarea id="reason" name="reason" rows="4" placeholder="Describe why this transaction is suspicious..." required></textarea>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" onclick="closeFlagModal()">Cancel</button>
<button type="submit" class="btn btn-danger">Flag Transaction</button>
</div>
</form>
</div>
</div>

<script>
function switchTab(tabName) {
document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
event.target.classList.add('active');
document.getElementById(tabName).classList.add('active');
}

function openFlagModal(transferId) {
const modal = document.getElementById('flagModal');
const form = document.getElementById('flagForm');
form.action = '/admin/compliance/flag/' + transferId;
modal.style.display = 'block';
}

function closeFlagModal() {
document.getElementById('flagModal').style.display = 'none';
document.getElementById('reason').value = '';
}

window.onclick = function(event) {
const modal = document.getElementById('flagModal');
if (event.target === modal) {
closeFlagModal();
}
}
</script>

</main></body></html>
