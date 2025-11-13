@include('includes.header')

<section class="page-header">
    <h1>Edit Bank Account</h1>
    <p>Update your bank account information</p>
</section>

<section class="edit-account">
    <div class="container">
        <div class="form-container">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('bank-accounts.update', $bankAccount) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="bank_name">Bank Name *</label>
                    <input type="text" id="bank_name" name="bank_name" value="{{ old('bank_name', $bankAccount->bank_name) }}" required>
                    @error('bank_name')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="account_number">Account Number *</label>
                    <input type="text" id="account_number" name="account_number" value="{{ old('account_number', $bankAccount->account_number) }}" required>
                    @error('account_number')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="account_type">Account Type</label>
                    <select id="account_type" name="account_type">
                        <option value="">Select account type</option>
                        <option value="checking" {{ old('account_type', $bankAccount->account_type) == 'checking' ? 'selected' : '' }}>Checking</option>
                        <option value="savings" {{ old('account_type', $bankAccount->account_type) == 'savings' ? 'selected' : '' }}>Savings</option>
                        <option value="business" {{ old('account_type', $bankAccount->account_type) == 'business' ? 'selected' : '' }}>Business</option>
                        <option value="current" {{ old('account_type', $bankAccount->account_type) == 'current' ? 'selected' : '' }}>Current</option>
                    </select>
                    @error('account_type')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="currency">Currency *</label>
                    <select id="currency" name="currency" required>
                        <option value="">Select currency</option>
                        <option value="USD" {{ old('currency', $bankAccount->currency) == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                        <option value="EUR" {{ old('currency', $bankAccount->currency) == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                        <option value="GBP" {{ old('currency', $bankAccount->currency) == 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                        <option value="JPY" {{ old('currency', $bankAccount->currency) == 'JPY' ? 'selected' : '' }}>JPY - Japanese Yen</option>
                        <option value="CAD" {{ old('currency', $bankAccount->currency) == 'CAD' ? 'selected' : '' }}>CAD - Canadian Dollar</option>
                        <option value="AUD" {{ old('currency', $bankAccount->currency) == 'AUD' ? 'selected' : '' }}>AUD - Australian Dollar</option>
                        <option value="CHF" {{ old('currency', $bankAccount->currency) == 'CHF' ? 'selected' : '' }}>CHF - Swiss Franc</option>
                        <option value="CNY" {{ old('currency', $bankAccount->currency) == 'CNY' ? 'selected' : '' }}>CNY - Chinese Yuan</option>
                        <option value="SEK" {{ old('currency', $bankAccount->currency) == 'SEK' ? 'selected' : '' }}>SEK - Swedish Krona</option>
                        <option value="NZD" {{ old('currency', $bankAccount->currency) == 'NZD' ? 'selected' : '' }}>NZD - New Zealand Dollar</option>
                        <option value="MXN" {{ old('currency', $bankAccount->currency) == 'MXN' ? 'selected' : '' }}>MXN - Mexican Peso</option>
                        <option value="SGD" {{ old('currency', $bankAccount->currency) == 'SGD' ? 'selected' : '' }}>SGD - Singapore Dollar</option>
                        <option value="HKD" {{ old('currency', $bankAccount->currency) == 'HKD' ? 'selected' : '' }}>HKD - Hong Kong Dollar</option>
                        <option value="NOK" {{ old('currency', $bankAccount->currency) == 'NOK' ? 'selected' : '' }}>NOK - Norwegian Krone</option>
                        <option value="KRW" {{ old('currency', $bankAccount->currency) == 'KRW' ? 'selected' : '' }}>KRW - South Korean Won</option>
                        <option value="TRY" {{ old('currency', $bankAccount->currency) == 'TRY' ? 'selected' : '' }}>TRY - Turkish Lira</option>
                        <option value="RUB" {{ old('currency', $bankAccount->currency) == 'RUB' ? 'selected' : '' }}>RUB - Russian Ruble</option>
                        <option value="INR" {{ old('currency', $bankAccount->currency) == 'INR' ? 'selected' : '' }}>INR - Indian Rupee</option>
                        <option value="BRL" {{ old('currency', $bankAccount->currency) == 'BRL' ? 'selected' : '' }}>BRL - Brazilian Real</option>
                        <option value="ZAR" {{ old('currency', $bankAccount->currency) == 'ZAR' ? 'selected' : '' }}>ZAR - South African Rand</option>
                    </select>
                    @error('currency')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                @if($bankAccount->is_verified)
                    <div class="warning-notice">
                        <div class="notice-icon">⚠️</div>
                        <div class="notice-content">
                            <h4>Important Notice</h4>
                            <p>Changing the bank name or account number will require re-verification of this account.</p>
                        </div>
                    </div>
                @endif

                <div class="form-actions">
                    <a href="{{ route('bank-accounts.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Bank Account</button>
                </div>
            </form>
        </div>
    </div>
</section>

<style>
.page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 3rem 2rem;
    text-align: center;
}

.edit-account {
    padding: 2rem;
    background-color: #f9fafb;
    min-height: 70vh;
}

.container {
    max-width: 600px;
    margin: 0 auto;
}

.form-container {
    background: white;
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #374151;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 1rem;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.error-text {
    color: #ef4444;
    font-size: 0.875rem;
    margin-top: 0.25rem;
    display: block;
}

.warning-notice {
    background-color: #fef3c7;
    border: 1px solid #f59e0b;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
}

.notice-icon {
    font-size: 1.5rem;
}

.notice-content h4 {
    margin: 0 0 0.25rem 0;
    color: #92400e;
}

.notice-content p {
    margin: 0;
    color: #a16207;
    font-size: 0.875rem;
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
}

.btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    text-decoration: none;
    font-size: 1rem;
    font-weight: 500;
    transition: all 0.2s;
    display: inline-block;
    text-align: center;
}

.btn-primary {
    background-color: #3b82f6;
    color: white;
}

.btn-primary:hover {
    background-color: #2563eb;
}

.btn-secondary {
    background-color: #6b7280;
    color: white;
}

.btn-secondary:hover {
    background-color: #4b5563;
}

.alert {
    padding: 1rem;
    border-radius: 6px;
    margin-bottom: 1rem;
}

.alert-success {
    background-color: #d1fae5;
    color: #065f46;
    border: 1px solid #a7f3d0;
}

@media (max-width: 768px) {
    .form-actions {
        flex-direction: column;
    }
    
    .btn {
        width: 100%;
    }
}
</style>

@include('includes.footer')