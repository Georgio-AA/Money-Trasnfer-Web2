@include('includes.header')

<style>
body { background-color: #f3f4f6; }
.admin-container { max-width: 1200px; margin: 40px auto; padding: 0 20px; }
.admin-page-header { margin-bottom: 40px; }
.admin-page-header h1 { font-size: 32px; color: #1a202c; margin-bottom: 10px; }
.admin-page-header p { color: #718096; font-size: 16px; }
.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 40px; }
.stat-card { background: white; border-radius: 8px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
.stat-card h3 { font-size: 14px; color: #718096; margin-bottom: 8px; text-transform: uppercase; }
.stat-card .value { font-size: 36px; font-weight: bold; color: #2d3748; }
.activity-section { background: white; border-radius: 8px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
.activity-section h2 { font-size: 20px; color: #1a202c; margin-bottom: 20px; }
.activity-table { width: 100%; border-collapse: collapse; }
.activity-table th { text-align: left; padding: 12px; background: #f7fafc; color: #4a5568; font-size: 14px; font-weight: 600; border-bottom: 2px solid #e2e8f0; }
.activity-table td { padding: 12px; border-bottom: 1px solid #e2e8f0; color: #2d3748; }
.status-badge { display: inline-block; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; }
.status-pending { background: #fef3c7; color: #92400e; }
.status-processing { background: #dbeafe; color: #1e40af; }
.status-completed { background: #d1fae5; color: #065f46; }
.status-failed { background: #fee2e2; color: #991b1b; }
.stat-card.clickable { cursor: pointer; transition: transform 0.2s, box-shadow 0.2s; }
.stat-card.clickable:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
.stat-card a { text-decoration: none; color: inherit; display: block; }
</style>

<div class="admin-container">
<div class="admin-page-header">
<h1>Admin Dashboard</h1>
<p>Manage overall platform operations</p>
</div>
<div class="stats-grid">
<div class="stat-card"><h3>Total Users</h3><div class="value">{{ $stats['total_users'] }}</div></div>
<div class="stat-card"><h3>Active Transfers</h3><div class="value">{{ $stats['active_transfers'] }}</div></div>
<div class="stat-card"><h3>Pending Verifications</h3><div class="value">{{ $stats['pending_verifications'] }}</div></div>
<div class="stat-card clickable">
<a href="{{ route('admin.agents.index') }}">
<h3>Pending Agents</h3>
<div class="value">{{ $stats['pending_agents'] }}</div>
</a>
</div>
</div>
<div class="activity-section">
<h2>Recent Transfers</h2>
@if($recentActivity->count() > 0)
<table class="activity-table">
<thead><tr><th>Transfer ID</th><th>Sender</th><th>Receiver</th><th>Amount</th><th>Status</th><th>Date</th></tr></thead>
<tbody>
@foreach($recentActivity as $transfer)
<tr>
<td>#{{ $transfer->id }}</td>
<td>{{ $transfer->sender->name ?? 'N/A' }}</td>
<td>{{ $transfer->receiver->name ?? $transfer->receiver_name ?? 'N/A' }}</td>
<td>${{ number_format($transfer->amount, 2) }}</td>
<td><span class="status-badge status-{{ strtolower($transfer->status) }}">{{ ucfirst($transfer->status) }}</span></td>
<td>{{ $transfer->created_at->format('M d, Y H:i') }}</td>
</tr>
@endforeach
</tbody>
</table>
@else
<p style="color: #718096; text-align: center; padding: 40px;">No recent transfers</p>
@endif
</div>
</div>

</main></body></html>
