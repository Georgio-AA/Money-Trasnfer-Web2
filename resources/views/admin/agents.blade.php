@include('includes.header')

<style>
body { background-color: #f3f4f6; }
.admin-container { max-width: 1200px; margin: 40px auto; padding: 0 20px; }
.admin-page-header { margin-bottom: 40px; }
.admin-page-header h1 { font-size: 32px; color: #1a202c; margin-bottom: 10px; }
.admin-page-header p { color: #718096; font-size: 16px; }
.agents-section { background: white; border-radius: 8px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 30px; }
.agents-section h2 { font-size: 20px; color: #1a202c; margin-bottom: 20px; }
.agents-table { width: 100%; border-collapse: collapse; }
.agents-table th { text-align: left; padding: 12px; background: #f7fafc; color: #4a5568; font-size: 14px; font-weight: 600; border-bottom: 2px solid #e2e8f0; }
.agents-table td { padding: 12px; border-bottom: 1px solid #e2e8f0; color: #2d3748; }
.action-btn { padding: 8px 16px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 600; transition: all 0.2s; }
.approve-btn { background: #10b981; color: white; }
.approve-btn:hover { background: #059669; }
.revoke-btn { background: #ef4444; color: white; }
.revoke-btn:hover { background: #dc2626; }
.empty-state { color: #718096; text-align: center; padding: 40px; }
.badge { display: inline-block; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; }
.badge-approved { background: #d1fae5; color: #065f46; }
</style>

<div class="admin-container">
<div class="admin-page-header">
<h1>Manage Agents</h1>
<p>Approve new agents and partner stores</p>
</div>
<div class="agents-section">
<h2>Pending Approval ({{ $pendingAgents->count() }})</h2>
@if($pendingAgents->count() > 0)
<table class="agents-table">
<thead><tr><th>Agent ID</th><th>Name</th><th>Email</th><th>Business Name</th><th>Phone</th><th>Registered</th><th>Action</th></tr></thead>
<tbody>
@foreach($pendingAgents as $agent)
<tr>
<td>#{{ $agent->id }}</td>
<td>{{ $agent->user->name ?? 'N/A' }}</td>
<td>{{ $agent->user->email ?? 'N/A' }}</td>
<td>{{ $agent->business_name ?? 'N/A' }}</td>
<td>{{ $agent->phone_number ?? 'N/A' }}</td>
<td>{{ $agent->created_at->format('M d, Y') }}</td>
<td><form method="POST" action="{{ route('admin.agents.approve', $agent->id) }}" style="display: inline;">@csrf<button type="submit" class="action-btn approve-btn">Approve</button></form></td>
</tr>
@endforeach
</tbody>
</table>
@else
<p class="empty-state">No pending agents to approve</p>
@endif
</div>
<div class="agents-section">
<h2>Approved Agents ({{ $approvedAgents->count() }})</h2>
@if($approvedAgents->count() > 0)
<table class="agents-table">
<thead><tr><th>Agent ID</th><th>Name</th><th>Email</th><th>Business Name</th><th>Phone</th><th>Status</th><th>Action</th></tr></thead>
<tbody>
@foreach($approvedAgents as $agent)
<tr>
<td>#{{ $agent->id }}</td>
<td>{{ $agent->user->name ?? 'N/A' }}</td>
<td>{{ $agent->user->email ?? 'N/A' }}</td>
<td>{{ $agent->business_name ?? 'N/A' }}</td>
<td>{{ $agent->phone_number ?? 'N/A' }}</td>
<td><span class="badge badge-approved">Approved</span></td>
<td><form method="POST" action="{{ route('admin.agents.revoke', $agent->id) }}" style="display: inline;">@csrf<button type="submit" class="action-btn revoke-btn">Revoke</button></form></td>
</tr>
@endforeach
</tbody>
</table>
@else
<p class="empty-state">No approved agents yet</p>
@endif
</div>
</div>

</main></body></html>
