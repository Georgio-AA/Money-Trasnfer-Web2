@include('includes.header')

<section class="page-header">
    <h1>File a Dispute</h1>
    <p>Report an issue with your transfer</p>
</section>

<section class="form-section">
    <div class="container">
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <form action="{{ route('disputes.store') }}" method="POST" class="dispute-form">
            @csrf

            @if($transfer)
                <input type="hidden" name="transfer_id" value="{{ $transfer->id }}">
                
                <div class="transfer-info-card">
                    <h3>Transfer Details</h3>
                    <div class="transfer-details">
                        <p><strong>Transfer ID:</strong> #{{ $transfer->id }}</p>
                        <p><strong>Amount:</strong> {{ $transfer->source_currency }} {{ number_format($transfer->amount, 2) }}</p>
                        <p><strong>Recipient:</strong> {{ $transfer->beneficiary->full_name ?? 'N/A' }}</p>
                        <p><strong>Date:</strong> {{ $transfer->created_at->format('M d, Y H:i') }}</p>
                        <p><strong>Status:</strong> <span class="status-{{ $transfer->status }}">{{ ucfirst($transfer->status) }}</span></p>
                    </div>
                </div>
            @else
                <div class="form-group">
                    <label for="transfer_id">Select Transfer to Dispute *</label>
                    <select name="transfer_id" id="transfer_id" required>
                        <option value="">-- Choose a transfer --</option>
                        @foreach($eligibleTransfers as $t)
                            <option value="{{ $t->id }}" {{ old('transfer_id') == $t->id ? 'selected' : '' }}>
                                #{{ $t->id }} - {{ $t->source_currency }} {{ number_format($t->amount, 2) }} 
                                ({{ $t->created_at->format('M d, Y') }}) - {{ ucfirst($t->status) }}
                            </option>
                        @endforeach
                    </select>
                    @if($eligibleTransfers->isEmpty())
                        <small class="hint" style="color: #dc2626;">You have no eligible transfers to dispute.</small>
                    @endif
                    @error('transfer_id')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            @endif

            <div class="form-group">
                <label for="reason">Dispute Reason *</label>
                <select name="reason" id="reason" required>
                    <option value="">-- Select a reason --</option>
                    <option value="wrong_recipient" {{ old('reason') == 'wrong_recipient' ? 'selected' : '' }}>Wrong Recipient</option>
                    <option value="wrong_amount" {{ old('reason') == 'wrong_amount' ? 'selected' : '' }}>Wrong Amount</option>
                    <option value="not_received" {{ old('reason') == 'not_received' ? 'selected' : '' }}>Payment Not Received by Recipient</option>
                    <option value="unauthorized" {{ old('reason') == 'unauthorized' ? 'selected' : '' }}>Unauthorized Transaction</option>
                    <option value="fraudulent" {{ old('reason') == 'fraudulent' ? 'selected' : '' }}>Fraudulent Activity</option>
                    <option value="technical_issue" {{ old('reason') == 'technical_issue' ? 'selected' : '' }}>Technical Issue</option>
                    <option value="other" {{ old('reason') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('reason')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">Detailed Description *</label>
                <textarea name="description" id="description" rows="8" required placeholder="Please provide a detailed explanation of the issue... (minimum 20 characters)">{{ old('description') }}</textarea>
                <small class="hint">Minimum 20 characters, maximum 2000 characters. Include all relevant details to help us investigate.</small>
                @error('description')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="info-box">
                <strong>⚠️ Important Information:</strong>
                <ul>
                    <li>Our team will investigate your dispute within 2-5 business days</li>
                    <li>You will be contacted via email with updates</li>
                    <li>Provide as much detail as possible to expedite the resolution</li>
                    <li>You can request a refund after filing if eligible</li>
                    <li>False or fraudulent disputes may result in account suspension</li>
                </ul>
            </div>

            <div class="form-actions">
                <a href="{{ route('disputes.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Submit Dispute</button>
            </div>
        </form>
    </div>
</section>

<style>
.page-header {
    background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
    color: white;
    padding: 3rem 2rem;
    text-align: center;
}

.form-section {
    padding: 2rem;
    background: #f9fafb;
    min-height: 70vh;
}

.container {
    max-width: 800px;
    margin: 0 auto;
}

.dispute-form {
    background: white;
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.transfer-info-card {
    background: linear-gradient(135deg, #fef3c7 0%, #fde047 100%);
    padding: 1.5rem;
    border-radius: 8px;
    margin-bottom: 2rem;
}

.transfer-info-card h3 {
    margin: 0 0 1rem 0;
    color: #78350f;
}

.transfer-details p {
    margin: 0.5rem 0;
    color: #92400e;
}

.status-pending { color: #f59e0b; font-weight: 600; }
.status-completed { color: #059669; font-weight: 600; }
.status-failed { color: #dc2626; font-weight: 600; }
.status-cancelled { color: #6b7280; font-weight: 600; }

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #374151;
}

.form-group select,
.form-group textarea {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 1rem;
    font-family: inherit;
}

.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.hint {
    display: block;
    margin-top: 0.25rem;
    font-size: 0.875rem;
    color: #6b7280;
}

.error {
    display: block;
    margin-top: 0.25rem;
    font-size: 0.875rem;
    color: #dc2626;
}

.info-box {
    background: #dbeafe;
    border: 1px solid #60a5fa;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1.5rem;
}

.info-box strong {
    display: block;
    margin-bottom: 0.5rem;
    color: #1e40af;
}

.info-box ul {
    margin: 0.5rem 0 0 1.5rem;
    padding: 0;
    color: #1e40af;
}

.info-box li {
    margin-bottom: 0.25rem;
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
}

.btn-primary {
    background-color: #dc2626;
    color: white;
}

.btn-primary:hover {
    background-color: #b91c1c;
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

.alert-error {
    background-color: #fee2e2;
    color: #991b1b;
    border: 1px solid #fca5a5;
}

@media (max-width: 768px) {
    .form-actions {
        flex-direction: column;
    }

    .btn {
        width: 100%;
        text-align: center;
    }
}
</style>

@include('includes.footer')
