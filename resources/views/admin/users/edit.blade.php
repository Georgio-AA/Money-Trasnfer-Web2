@include('includes.header')

<style>
body { background-color: #f3f4f6; }
.admin-container { max-width: 800px; margin: 40px auto; padding: 0 20px; }
.back-link { color: #3b82f6; text-decoration: none; margin-bottom: 20px; display: inline-block; }
.form-container { background: white; border-radius: 8px; padding: 32px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
.form-container h1 { margin: 0 0 24px 0; font-size: 24px; color: #1a202c; }
.form-group { margin-bottom: 20px; }
.form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568; font-size: 14px; }
.form-group input, .form-group select { width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 14px; }
.form-group input:focus, .form-group select:focus { outline: none; border-color: #3b82f6; }
.error { color: #ef4444; font-size: 13px; margin-top: 5px; }
.form-actions { display: flex; gap: 10px; margin-top: 24px; }
.btn { padding: 12px 24px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 600; text-decoration: none; }
.btn-primary { background: #3b82f6; color: white; }
.btn-secondary { background: #e2e8f0; color: #4a5568; }
</style>

<div class="admin-container">
<a href="{{ route('admin.users.show', $user->id) }}" class="back-link"> Back to User</a>

<div class="form-container">
<h1>Edit User</h1>

<form method="POST" action="{{ route('admin.users.update', $user->id) }}">
@csrf
@method('PUT')

<div class="form-group">
<label for="name">Full Name *</label>
<input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
@error('name')<div class="error">{{ $message }}</div>@enderror
</div>

<div class="form-group">
<label for="email">Email Address *</label>
<input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
@error('email')<div class="error">{{ $message }}</div>@enderror
</div>

<div class="form-group">
<label for="phone">Phone Number</label>
<input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
@error('phone')<div class="error">{{ $message }}</div>@enderror
</div>

<div class="form-group">
<label for="role">User Role *</label>
<select id="role" name="role" required>
<option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
<option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
</select>
@error('role')<div class="error">{{ $message }}</div>@enderror
</div>

<div class="form-actions">
<button type="submit" class="btn btn-primary">Save Changes</button>
<a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-secondary">Cancel</a>
</div>
</form>
</div>
</div>

</main></body></html>
