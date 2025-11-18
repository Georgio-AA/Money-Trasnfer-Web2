@include('includes.header')

<section class="page-header">
    <h1>Initiate Money Transfer</h1>
    <p>Send money to your beneficiaries quickly and securely</p>
</section>

<section class="transfer-form-section">
    <div class="container">
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif
        
        <!-- Balance Display -->
        <div class="balance-card">
            <div class="balance-icon">💰</div>
            <div class="balance-info">
                <span class="balance-label">Your Available Balance</span>
                <span class="balance-amount">{{ session('user')['currency'] ?? 'USD' }} {{ number_format(session('user')['balance'] ?? 0, 2) }}</span>
            </div>
        </div>
        
        <form method="POST" action="{{ route('transfers.store') }}" class="transfer-form" id="transferForm">
            @csrf
            
            <div class="form-grid">
                <!-- Beneficiary Selection -->
                <div class="form-group full-width">
                    <label for="beneficiary_id">Select Beneficiary *</label>
                    <select name="beneficiary_id" id="beneficiary_id" required>
                        <option value="">-- Choose a beneficiary --</option>
                        @foreach($beneficiaries as $beneficiary)
                            <option value="{{ $beneficiary->id }}" data-country="{{ $beneficiary->country }}">
                                {{ $beneficiary->full_name }} ({{ $beneficiary->country }})
                            </option>
                        @endforeach
                    </select>
                    @if($beneficiaries->isEmpty())
                        <small class="hint">No beneficiaries found. <a href="{{ route('beneficiaries.create') }}" class="link">Add a beneficiary first</a></small>
                    @endif
                    @error('beneficiary_id')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                
                <!-- Amount & Currency -->
                <div class="form-group">
                    <label for="amount">Amount to Send *</label>
                    <input type="number" name="amount" id="amount" step="0.01" min="1" value="{{ old('amount') }}" required>
                    @error('amount')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="source_currency">Source Currency *</label>
                    <select name="source_currency" id="source_currency" required>
                        <option value="">-- Select currency --</option>
                        @foreach($currencies as $code => $name)
                            <option value="{{ $code }}" {{ old('source_currency') == $code ? 'selected' : '' }}>
                                {{ $code }} - {{ $name }}
                            </option>
                        @endforeach
                    </select>
                    @error('source_currency')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="target_currency">Target Currency *</label>
                    <select name="target_currency" id="target_currency" required>
                        <option value="">-- Select currency --</option>
                        @foreach($currencies as $code => $name)
                            <option value="{{ $code }}" {{ old('target_currency') == $code ? 'selected' : '' }}>
                                {{ $code }} - {{ $name }}
                            </option>
                        @endforeach
                    </select>
                    @error('target_currency')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                
                <!-- Transfer Options -->
                <div class="form-group">
                    <label for="transfer_speed">Transfer Speed *</label>
                    <select name="transfer_speed" id="transfer_speed" required>
                        <option value="">-- Select speed --</option>
                        @foreach($speeds as $key => $label)
                            <option value="{{ $key }}" {{ old('transfer_speed') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('transfer_speed')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="payout_method">Payout Method *</label>
                    <select name="payout_method" id="payout_method" required>
                        <option value="">-- Select method --</option>
                        <option value="bank_deposit" {{ old('payout_method') == 'bank_deposit' ? 'selected' : '' }}>Bank Deposit</option>
                        <option value="cash_pickup" {{ old('payout_method') == 'cash_pickup' ? 'selected' : '' }}>Cash Pickup</option>
                        <option value="mobile_wallet" {{ old('payout_method') == 'mobile_wallet' ? 'selected' : '' }}>Mobile Wallet</option>
                    </select>
                    @error('payout_method')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                
                <!-- Promotion Code -->
                <div class="form-group full-width">
                    <label for="promotion_id">Promotion Code (Optional)</label>
                    <select name="promotion_id" id="promotion_id">
                        <option value="">-- No promotion --</option>
                        @foreach($promotions as $promo)
                            <option value="{{ $promo->id }}" {{ old('promotion_id') == $promo->id ? 'selected' : '' }}>
                                {{ $promo->title }} - {{ $promo->discount_percent }}% off
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <!-- Quote Display -->
            <div class="quote-box" id="quoteBox" style="display:none;">
                <h3>Transfer Quote</h3>
                <div class="quote-details">
                    <div class="quote-row">
                        <span>Amount to send:</span>
                        <span id="quoteAmount">-</span>
                    </div>
                    <div class="quote-row">
                        <span>Exchange rate:</span>
                        <span id="quoteRate">-</span>
                    </div>
                    <div class="quote-row">
                        <span>Transfer fee:</span>
                        <span id="quoteFee">-</span>
                    </div>
                    <div class="quote-row total">
                        <span><strong>Total to pay:</strong></span>
                        <span id="quoteTotal"><strong>-</strong></span>
                    </div>
                    <div class="quote-row highlight">
                        <span><strong>Recipient gets:</strong></span>
                        <span id="quotePayout"><strong>-</strong></span>
                    </div>
                </div>
            </div>
            
            <!-- Submit -->
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" id="calculateBtn">Calculate Quote</button>
                <button type="submit" class="btn btn-primary">Initiate Transfer</button>
                <a href="{{ route('transfers.index') }}" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</section>

<style>
.page-header{background:linear-gradient(135deg,#22c55e,#06b6d4);color:#fff;padding:2rem;text-align:center}
.transfer-form-section{padding:2rem 0}
.container{max-width:900px;margin:0 auto;padding:0 1rem}
.alert{padding:1rem;border-radius:8px;margin-bottom:1.5rem}
.alert-error{background:#fef2f2;border:1px solid #fecaca;color:#991b1b}
.balance-card{background:linear-gradient(135deg,#4f46e5,#7c3aed);color:#fff;padding:1.5rem;border-radius:12px;display:flex;align-items:center;gap:1rem;margin-bottom:1.5rem;box-shadow:0 4px 12px rgba(79,70,229,0.3)}
.balance-icon{font-size:2.5rem}
.balance-info{display:flex;flex-direction:column}
.balance-label{font-size:0.875rem;opacity:0.9}
.balance-amount{font-size:1.75rem;font-weight:700;margin-top:0.25rem}
.transfer-form{background:#fff;padding:2rem;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.1)}
.form-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:1.5rem;margin-bottom:2rem}
.form-group.full-width{grid-column:1/-1}
.form-group label{display:block;font-weight:600;margin-bottom:0.5rem;color:#374151}
.form-group input,.form-group select{width:100%;padding:0.75rem;border:1px solid #d1d5db;border-radius:8px;font-size:1rem}
.form-group input:focus,.form-group select:focus{outline:none;border-color:#2563eb;box-shadow:0 0 0 3px rgba(37,99,235,0.1)}
.hint{display:block;margin-top:0.25rem;font-size:0.875rem;color:#6b7280}
.link{color:#2563eb;text-decoration:underline}
.error{display:block;margin-top:0.25rem;font-size:0.875rem;color:#dc2626}
.quote-box{background:#f0fdf4;border:2px solid #86efac;border-radius:12px;padding:1.5rem;margin-bottom:2rem}
.quote-box h3{margin:0 0 1rem 0;color:#166534}
.quote-details{display:flex;flex-direction:column;gap:0.75rem}
.quote-row{display:flex;justify-content:space-between;padding:0.5rem 0}
.quote-row.total{border-top:2px solid #86efac;margin-top:0.5rem;padding-top:1rem}
.quote-row.highlight{background:#dcfce7;padding:0.75rem;border-radius:6px;margin-top:0.5rem}
.form-actions{display:flex;gap:1rem;justify-content:flex-end}
.btn{padding:0.75rem 1.5rem;border:none;border-radius:8px;cursor:pointer;font-size:1rem;font-weight:500;text-decoration:none;display:inline-block}
.btn-primary{background:#2563eb;color:#fff}
.btn-primary:hover{background:#1d4ed8}
.btn-secondary{background:#6b7280;color:#fff}
.btn-secondary:hover{background:#4b5563}
.btn-outline{background:#fff;color:#374151;border:1px solid #d1d5db}
.btn-outline:hover{background:#f9fafb}
@media(max-width:768px){
.form-grid{grid-template-columns:1fr}
.form-actions{flex-direction:column}
}
</style>

<script>
document.getElementById('calculateBtn').addEventListener('click', function() {
    const amount = document.getElementById('amount').value;
    const sourceCurrency = document.getElementById('source_currency').value;
    const targetCurrency = document.getElementById('target_currency').value;
    const transferSpeed = document.getElementById('transfer_speed').value;
    
    if (!amount || !sourceCurrency || !targetCurrency || !transferSpeed) {
        alert('Please fill in all required fields to calculate quote');
        return;
    }
    
    fetch('{{ route("transfers.calculate-quote") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            amount: amount,
            source_currency: sourceCurrency,
            target_currency: targetCurrency,
            transfer_speed: transferSpeed
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            alert(data.error);
            return;
        }
        
        document.getElementById('quoteAmount').textContent = sourceCurrency + ' ' + data.amount;
        document.getElementById('quoteRate').textContent = '1 ' + sourceCurrency + ' = ' + data.exchange_rate + ' ' + targetCurrency;
        document.getElementById('quoteFee').textContent = sourceCurrency + ' ' + data.transfer_fee;
        document.getElementById('quoteTotal').textContent = sourceCurrency + ' ' + data.total_paid;
        document.getElementById('quotePayout').textContent = targetCurrency + ' ' + data.payout_amount;
        document.getElementById('quoteBox').style.display = 'block';
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to calculate quote');
    });
});
</script>

@include('includes.footer')
