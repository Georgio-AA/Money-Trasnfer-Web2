@include('includes.header')

<style>
body { background-color: #f3f4f6; }
.admin-container { max-width: 1400px; margin: 40px auto; padding: 0 20px; }
.admin-page-header { margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; }
.admin-page-header h1 { font-size: 32px; color: #1a202c; margin: 0; }
.search-bar { display: flex; gap: 10px; margin-bottom: 20px; }
.search-bar input, .search-bar select { padding: 10px; border: 1px solid #e2e8f0; border-radius: 6px; }
.search-bar input { flex: 1; max-width: 400px; }
.search-bar button { padding: 10px 20px; background: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer; }
.users-table-container { background: white; border-radius: 8px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
.users-table { width: 100%; border-collapse: collapse; }
.users-table th { text-align: left; padding: 12px; background: #f7fafc; color: #4a5568; font-size: 14px; font-weight: 600; border-bottom: 2px solid #e2e8f0; }
.users-table td { padding: 12px; border-bottom: 1px solid #e2e8f0; color: #2d3748; font-size: 14px; }
.badge { display: inline-block; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; }
.badge-admin { background: #fef3c7; color: #92400e; }
.badge-user { background: #e0e7ff; color: #3730a3; }
.badge-verified { background: #d1fae5; color: #065f46; }
.badge-unverified { background: #fee2e2; color: #991b1b; }
.action-links { display: flex; gap: 10px; }
.action-links a { color: #3b82f6; text-decoration: none; font-size: 13px; }
.action-links a:hover { text-decoration: underline; }
.pagination { display: flex; justify-content: center; gap: 5px; margin-top: 20px; }
.pagination a, .pagination span { padding: 8px 12px; border: 1px solid #e2e8f0; border-radius: 4px; color: #4a5568; text-decoration: none; }
.pagination .active { background: #3b82f6; color: white; border-color: #3b82f6; }
</style>

<div class="admin-container">
<div class="admin-page-header">
<h1>User Management</h1>
</div>

<form class="search-bar" method="GET" action="{{ route('admin.users.index') }}">
<input type="text" name="search" placeholder="Search by name, email, or phone..." value="{{ request('search') }}">
<select name="role">
<option value="">All Roles</option>
<option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
<option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
</select>
<button type="submit">Search</button>
</form>

<div class="users-table-container">
<table class="users-table">
<thead>
<tr>
<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Phone</th>
<th>Role</th>
<th>Status</th>
<th>Registered</th>
<th>Actions</th>
</tr>
</thead>
<tbody>
@forelse($users as $user)
<tr style="{{ ($user->status ?? 'active') === 'blocked' ? 'background-color: #fee2e2;' : '' }}">
<td>#{{ $user->id }}</td>
<td>
{{ $user->name }}
@if(($user->status ?? 'active') === 'blocked')
<span style="color: #dc2626; font-size: 12px; font-weight: 600;">🚫 BLOCKED</span>
@endif
</td>
<td>{{ $user->email }}</td>
<td>{{ $user->phone ?? 'N/A' }}</td>
<td><span class="badge badge-{{ $user->role }}">{{ ucfirst($user->role) }}</span></td>
<td><span class="badge badge-{{ $user->is_verified ? 'verified' : 'unverified' }}">{{ $user->is_verified ? 'Verified' : 'Unverified' }}</span></td>
<td>{{ $user->created_at->format('M d, Y') }}</td>
<td>
<div class="action-links">
<a href="{{ route('admin.users.show', $user->id) }}">View</a>
<a href="{{ route('admin.users.edit', $user->id) }}">Edit</a>
</div>
</td>
</tr>
@empty
<tr><td colspan="8" style="text-align: center; padding: 40px; color: #718096;">No users found</td></tr>
@endforelse
</tbody>
</table>

<div class="pagination">
{{ $users->links() }}
</div>
</div>
</div>

</main></body></html>
