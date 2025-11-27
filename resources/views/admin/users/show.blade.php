@include('includes.header')

<style>
body { background-color: #f3f4f6; }
.admin-container { max-width: 1200px; margin: 40px auto; padding: 0 20px; }
.back-link { color: #3b82f6; text-decoration: none; margin-bottom: 20px; display: inline-block; }
.user-header { background: white; border-radius: 8px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px; }
.user-header h1 { margin: 0 0 10px 0; font-size: 28px; color: #1a202c; }
.user-meta { display: flex; gap: 15px; flex-wrap: wrap; }
.badge { display: inline-block; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; }
.badge-admin { background: #fef3c7; color: #92400e; }
.badge-user { background: #e0e7ff; color: #3730a3; }
.badge-verified { background: #d1fae5; color: #065f46; }
.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 20px; }
.stat-card { background: white; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
.stat-card h3 { margin: 0 0 8px 0; font-size: 14px; color: #718096; text-transform: uppercase; }
.stat-card .value { font-size: 28px; font-weight: bold; color: #2d3748; }
.info-section { background: white; border-radius: 8px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
.info-section h2 { margin: 0 0 20px 0; font-size: 20px; color: #1a202c; }
.info-row { display: grid; grid-template-columns: 200px 1fr; padding: 12px 0; border-bottom: 1px solid #e2e8f0; }
.info-row:last-child { border-bottom: none; }
.info-label { font-weight: 600; color: #4a5568; }
.info-value { color: #2d3748; }
.actions { display: flex; gap: 10px; margin-top: 20px; }
.btn { padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 600; text-decoration: none; display: inline-block; }
.btn-primary { background: #3b82f6; color: white; }
.btn-danger { background: #ef4444; color: white; }
</style>

<div class="admin-container">
<a href="{{ route('admin.users.index') }}" class="back-link"> Back to Users</a>

<div class="user-header">
<h1>{{ $user->name }}</h1>
<div class="user-meta">
<span class="badge badge-{{ $user->role }}">{{ ucfirst($user->role) }}</span>
<span class="badge badge-{{ $user->is_verified ? 'verified' : 'unverified' }}">{{ $user->is_verified ? 'Verified' : 'Unverified' }}</span>
</div>
</div>

<div class="stats-grid">
<div class="stat-card"><h3>Total Transfers</h3><div class="value">{{ $stats['total_transfers'] }}</div></div>
<div class="stat-card"><h3>Total Sent</h3><div class="value">${{ number_format($stats['total_sent'], 2) }}</div></div>
<div class="stat-card"><h3>Bank Accounts</h3><div class="value">{{ $stats['bank_accounts'] }}</div></div>
<div class="stat-card"><h3>Verified Accounts</h3><div class="value">{{ $stats['verified_accounts'] }}</div></div>
</div>

<div class="info-section">
<h2>User Information</h2>
<div class="info-row"><div class="info-label">User ID</div><div class="info-value">#{{ $user->id }}</div></div>
<div class="info-row"><div class="info-label">Email</div><div class="info-value">{{ $user->email }}</div></div>
<div class="info-row"><div class="info-label">Phone</div><div class="info-value">{{ $user->phone ?? 'Not provided' }}</div></div>
<div class="info-row"><div class="info-label">Age</div><div class="info-value">{{ $user->age ?? 'Not provided' }}</div></div>
<div class="info-row"><div class="info-label">Registered</div><div class="info-value">{{ $user->created_at->format('F d, Y h:i A') }}</div></div>
<div class="info-row"><div class="info-label">Last Updated</div><div class="info-value">{{ $user->updated_at->format('F d, Y h:i A') }}</div></div>

<div class="actions">
<a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary">Edit User</a>
@if($user->id !== session('user.id'))
<form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" onsubmit="return confirm('Are you sure you want to delete this user?');">
@csrf
@method('DELETE')
<button type="submit" class="btn btn-danger">Delete User</button>
</form>
@endif
</div>
</div>
</div>

</main></body></html>
