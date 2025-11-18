@include('includes.header')

<style>
body { background-color: #f3f4f6; }
.admin-container { max-width: 900px; margin: 40px auto; padding: 0 20px; }
.admin-page-header { margin-bottom: 30px; }
.admin-page-header h1 { font-size: 32px; color: #1a202c; margin: 0 0 10px 0; }
.admin-page-header p { color: #718096; font-size: 16px; margin: 0; }
.settings-section { background: white; border-radius: 8px; padding: 32px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
.settings-section h2 { margin: 0 0 24px 0; font-size: 20px; color: #1a202c; padding-bottom: 12px; border-bottom: 2px solid #e2e8f0; }
.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.form-group { margin-bottom: 20px; }
.form-group.full-width { grid-column: 1 / -1; }
.form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568; font-size: 14px; }
.form-group input, .form-group select { width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 14px; }
.form-group input:focus, .form-group select:focus { outline: none; border-color: #3b82f6; }
.form-group small { display: block; margin-top: 5px; color: #718096; font-size: 13px; }
.error { color: #ef4444; font-size: 13px; margin-top: 5px; }
.success-message { background: #d1fae5; color: #065f46; padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; font-size: 14px; }
.form-actions { display: flex; gap: 10px; margin-top: 24px; padding-top: 24px; border-top: 1px solid #e2e8f0; }
.btn { padding: 12px 32px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 600; }
.btn-primary { background: #3b82f6; color: white; }
.btn-primary:hover { background: #2563eb; }
.toggle-switch { position: relative; display: inline-block; width: 50px; height: 24px; }
.toggle-switch input { opacity: 0; width: 0; height: 0; }
.toggle-slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #cbd5e0; border-radius: 24px; transition: .4s; }
.toggle-slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; border-radius: 50%; transition: .4s; }
input:checked + .toggle-slider { background-color: #3b82f6; }
input:checked + .toggle-slider:before { transform: translateX(26px); }
.info-box { background: #e0f2fe; border-left: 4px solid #3b82f6; padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; }
.info-box p { margin: 0; color: #1e40af; font-size: 14px; }
</style>

<div class="admin-container">
<div class="admin-page-header">
<h1>System Configuration</h1>
<p>Manage platform settings and configuration</p>
</div>

@if(session('success'))
<div class="success-message">{{ session('success') }}</div>
@endif

<form method="POST" action="{{ route('admin.settings.update') }}">
@csrf

<div class="settings-section">
<h2>Platform Information</h2>
<div class="form-grid">
<div class="form-group">
<label for="platform_name">Platform Name *</label>
<input type="text" id="platform_name" name="platform_name" value="{{ old('platform_name', $settings['platform_name']) }}" required>
@error('platform_name')<div class="error">{{ $message }}</div>@enderror
</div>
<div class="form-group">
<label for="platform_email">Platform Email *</label>
<input type="email" id="platform_email" name="platform_email" value="{{ old('platform_email', $settings['platform_email']) }}" required>
@error('platform_email')<div class="error">{{ $message }}</div>@enderror
</div>
</div>
</div>

<div class="settings-section">
<h2>Transfer Fees</h2>
<div class="info-box">
<p>Configure transfer fees. Total fee = (Amount  Percentage) + Fixed Fee</p>
</div>
<div class="form-grid">
<div class="form-group">
<label for="transfer_fee_percentage">Fee Percentage (%) *</label>
<input type="number" step="0.01" id="transfer_fee_percentage" name="transfer_fee_percentage" value="{{ old('transfer_fee_percentage', $settings['transfer_fee_percentage']) }}" required>
<small>Example: 2.5 means 2.5% of transfer amount</small>
@error('transfer_fee_percentage')<div class="error">{{ $message }}</div>@enderror
</div>
<div class="form-group">
<label for="transfer_fee_fixed">Fixed Fee ($) *</label>
<input type="number" step="0.01" id="transfer_fee_fixed" name="transfer_fee_fixed" value="{{ old('transfer_fee_fixed', $settings['transfer_fee_fixed']) }}" required>
<small>Example: 1.00 means $1.00 per transfer</small>
@error('transfer_fee_fixed')<div class="error">{{ $message }}</div>@enderror
</div>
</div>
</div>

<div class="settings-section">
<h2>Transfer Limits</h2>
<div class="form-grid">
<div class="form-group">
<label for="min_transfer_amount">Minimum Transfer ($) *</label>
<input type="number" step="0.01" id="min_transfer_amount" name="min_transfer_amount" value="{{ old('min_transfer_amount', $settings['min_transfer_amount']) }}" required>
@error('min_transfer_amount')<div class="error">{{ $message }}</div>@enderror
</div>
<div class="form-group">
<label for="max_transfer_amount">Maximum Transfer ($) *</label>
<input type="number" step="0.01" id="max_transfer_amount" name="max_transfer_amount" value="{{ old('max_transfer_amount', $settings['max_transfer_amount']) }}" required>
@error('max_transfer_amount')<div class="error">{{ $message }}</div>@enderror
</div>
<div class="form-group full-width">
<label for="daily_transfer_limit">Daily Transfer Limit ($) *</label>
<input type="number" step="0.01" id="daily_transfer_limit" name="daily_transfer_limit" value="{{ old('daily_transfer_limit', $settings['daily_transfer_limit']) }}" required>
<small>Maximum amount a user can transfer per day</small>
@error('daily_transfer_limit')<div class="error">{{ $message }}</div>@enderror
</div>
</div>
</div>

<div class="settings-section">
<h2>System Status</h2>
<div class="form-group">
<label for="maintenance_mode">Maintenance Mode</label>
<label class="toggle-switch">
<input type="checkbox" id="maintenance_mode" name="maintenance_mode" value="1" {{ old('maintenance_mode', $settings['maintenance_mode']) ? 'checked' : '' }}>
<span class="toggle-slider"></span>
</label>
<small style="margin-left: 60px;">Enable to prevent users from making transfers (admins can still access)</small>
</div>
</div>

<div class="settings-section">
<div class="form-actions">
<button type="submit" class="btn btn-primary">Save Settings</button>
</div>
@if(isset($settings['updated_at']))
<p style="color: #718096; font-size: 13px; margin-top: 12px;">Last updated: {{ $settings['updated_at'] }}</p>
@endif
</div>

</form>
</div>

</main></body></html>
