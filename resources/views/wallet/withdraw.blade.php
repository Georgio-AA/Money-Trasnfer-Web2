@include('includes.header')

<section class="page-header">
    <h1>Withdraw Money</h1>
    <p>Transfer funds from your wallet to your bank account</p>
</section>

<section class="form-section">
    <div class="container">
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif
        
        <div class="balance-info-card">
            <span class="label">Available Balance:</span>
            <span class="amount">{{ session('user')['currency'] ?? 'USD' }} {{ number_format(session('user')['balance'] ?? 0, 2) }}</span>
        </div>
        
        @if($bankAccounts->isEmpty())
            <div class="empty-state-card">
                <div class="empty-icon">🏦</div>
                <h3>No Bank Accounts Found</h3>
                <p>You need to add and verify a bank account before withdrawing money.</p>
                <a href="{{ route('bank-accounts.create') }}" class="btn btn-primary">Add Bank Account</a>
            </div>
        @else
            <form method="POST" action="{{ route('wallet.withdraw') }}" class="transaction-form">
                @csrf
                
                <div class="form-group">
                    <label for="bank_account_id">Withdraw to Bank Account *</label>
                    <select name="bank_account_id" id="bank_account_id" required>
                        <option value="">-- Choose bank account --</option>
                        @foreach($bankAccounts as $account)
                            <option value="{{ $account->id }}">
                                {{ $account->bank_name }} - {{ $account->masked_account_number }} ({{ $account->currency_display }})
                            </option>
                        @endforeach
                    </select>
                    @error('bank_account_id')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="amount">Amount to Withdraw *</label>
                    <input type="number" name="amount" id="amount" step="0.01" min="1" value="{{ old('amount') }}" required>
                    <small class="hint">Maximum: {{ session('user')['currency'] ?? 'USD' }} {{ number_format(session('user')['balance'] ?? 0, 2) }}</small>
                    @error('amount')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="info-box warning">
                    <strong>⚠️ Important Information:</strong>
                    <ul>
                        <li>Funds will be transferred from your wallet to your bank account</li>
                        <li>Processing time: 1-3 business days</li>
                        <li>No fees for withdrawals</li>
                        <li>Your wallet balance will be deducted immediately</li>
                        <li>Make sure your bank account details are correct</li>
                    </ul>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-warning">💸 Withdraw Money</button>
                    <a href="{{ route('wallet.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        @endif
    </div>
</section>

<style>
.page-header{background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;padding:2rem;text-align:center}
.form-section{padding:2rem 0;min-height:60vh;background:#f9fafb}
.container{max-width:600px;margin:0 auto;padding:0 1rem}
.alert{padding:1rem;border-radius:8px;margin-bottom:1.5rem}
.alert-error{background:#fef2f2;border:1px solid #fecaca;color:#991b1b}
.balance-info-card{background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;padding:1rem 1.5rem;border-radius:12px;margin-bottom:1.5rem;display:flex;justify-content:space-between;align-items:center;box-shadow:0 4px 12px rgba(245,158,11,0.3)}
.balance-info-card .label{font-size:0.875rem;opacity:0.9}
.balance-info-card .amount{font-size:1.5rem;font-weight:700}
.empty-state-card{background:#fff;border-radius:16px;padding:3rem 2rem;text-align:center;box-shadow:0 2px 12px rgba(0,0,0,0.08)}
.empty-icon{font-size:4rem;margin-bottom:1rem}
.empty-state-card h3{margin:0 0 0.5rem 0;color:#111827}
.empty-state-card p{margin:0 0 1.5rem 0;color:#6b7280}
.transaction-form{background:#fff;border-radius:16px;padding:2rem;box-shadow:0 2px 12px rgba(0,0,0,0.08)}
.form-group{margin-bottom:1.5rem}
.form-group label{display:block;font-weight:600;margin-bottom:0.5rem;color:#374151}
.form-group input,.form-group select{width:100%;padding:0.75rem;border:1px solid #d1d5db;border-radius:8px;font-size:1rem}
.form-group input:focus,.form-group select:focus{outline:none;border-color:#f59e0b;box-shadow:0 0 0 3px rgba(245,158,11,0.1)}
.hint{display:block;margin-top:0.25rem;font-size:0.875rem;color:#6b7280}
.error{display:block;margin-top:0.25rem;font-size:0.875rem;color:#dc2626}
.info-box{background:#fef3c7;border:1px solid #fde047;border-radius:8px;padding:1rem;margin-bottom:1.5rem}
.info-box strong{display:block;margin-bottom:0.5rem;color:#92400e}
.info-box ul{margin:0.5rem 0 0 1.5rem;padding:0;color:#78350f}
.info-box li{margin-bottom:0.25rem}
.form-actions{display:flex;gap:1rem;justify-content:center}
.btn{padding:0.75rem 1.5rem;border:none;border-radius:8px;cursor:pointer;font-size:1rem;font-weight:600;text-decoration:none;display:inline-block}
.btn-primary{background:#3b82f6;color:#fff}
.btn-primary:hover{background:#2563eb}
.btn-warning{background:#f59e0b;color:#fff}
.btn-warning:hover{background:#d97706}
.btn-secondary{background:#6b7280;color:#fff}
.btn-secondary:hover{background:#4b5563}
@media(max-width:768px){
.form-actions{flex-direction:column}
.balance-info-card{flex-direction:column;gap:0.5rem;text-align:center}
}
</style>

@include('includes.footer')
