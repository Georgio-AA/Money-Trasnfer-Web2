@include('includes.header')

<style>
body { background-color: #f3f4f6; }
.admin-container { max-width: 1000px; margin: 40px auto; padding: 0 20px; }
.back-link { color: #3b82f6; text-decoration: none; margin-bottom: 20px; display: inline-block; }
.transfer-header { background: white; border-radius: 8px; padding: 24px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
.transfer-header h1 { margin: 0 0 10px 0; font-size: 24px; color: #1a202c; }
.badge { display: inline-block; padding: 6px 16px; border-radius: 12px; font-size: 14px; font-weight: 600; }
.badge-pending { background: #fef3c7; color: #92400e; }
.badge-processing { background: #dbeafe; color: #1e40af; }
.badge-completed { background: #d1fae5; color: #065f46; }
.badge-failed { background: #fee2e2; color: #991b1b; }
.badge-cancelled { background: #f3f4f6; color: #4a5568; }
.info-section { background: white; border-radius: 8px; padding: 24px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
.info-section h2 { margin: 0 0 20px 0; font-size: 18px; color: #1a202c; }
.info-row { display: grid; grid-template-columns: 200px 1fr; padding: 12px 0; border-bottom: 1px solid #e2e8f0; }
.info-row:last-child { border-bottom: none; }
.info-label { font-weight: 600; color: #4a5568; }
.info-value { color: #2d3748; }
.actions { display: flex; gap: 10px; }
.btn { padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 600; }
.btn-danger { background: #ef4444; color: white; }
.btn-primary { background: #3b82f6; color: white; }
.status-form { display: inline-flex; gap: 10px; align-items: center; }
.status-form select { padding: 8px; border: 1px solid #e2e8f0; border-radius: 6px; }
</style>

<div class="admin-container">
<a href="{{ route('admin.transfers.index') }}" class="back-link"> Back to Transfers</a>

<div class="transfer-header">
<h1>Transfer #{{ $transfer->id }}</h1>
<span class="badge badge-{{ $transfer->status }}">{{ ucfirst($transfer->status) }}</span>
</div>

<div class="info-section">
<h2>Transfer Details</h2>
<div class="info-row"><div class="info-label">Transfer ID</div><div class="info-value">#{{ $transfer->id }}</div></div>
<div class="info-row"><div class="info-label">Amount</div><div class="info-value">${{ number_format($transfer->amount, 2) }} {{ $transfer->currency ?? 'USD' }}</div></div>
<div class="info-row"><div class="info-label">Sender</div><div class="info-value">{{ $transfer->sender->name ?? 'N/A' }} ({{ $transfer->sender->email ?? 'N/A' }})</div></div>
<div class="info-row"><div class="info-label">Receiver</div><div class="info-value">{{ $transfer->receiver->name ?? $transfer->receiver_name ?? 'N/A' }}</div></div>
<div class="info-row"><div class="info-label">Status</div><div class="info-value"><span class="badge badge-{{ $transfer->status }}">{{ ucfirst($transfer->status) }}</span></div></div>
<div class="info-row"><div class="info-label">Created</div><div class="info-value">{{ $transfer->created_at->format('F d, Y h:i A') }}</div></div>
<div class="info-row"><div class="info-label">Last Updated</div><div class="info-value">{{ $transfer->updated_at->format('F d, Y h:i A') }}</div></div>
</div>

<div class="info-section">
<h2>Admin Actions</h2>
<div class="actions">
@if(in_array($transfer->status, ['pending', 'processing']))
<form method="POST" action="{{ route('admin.transfers.cancel', $transfer->id) }}" onsubmit="return confirm('Are you sure you want to cancel this transfer?');">
@csrf
<button type="submit" class="btn btn-danger">Cancel Transfer</button>
</form>
@endif

<form method="POST" action="{{ route('admin.transfers.updateStatus', $transfer->id) }}" class="status-form">
@csrf
<select name="status" required>
<option value="">Change Status</option>
<option value="pending">Pending</option>
<option value="processing">Processing</option>
<option value="completed">Completed</option>
<option value="failed">Failed</option>
<option value="cancelled">Cancelled</option>
</select>
<button type="submit" class="btn btn-primary">Update Status</button>
</form>
</div>
</div>
</div>

</main></body></html>
