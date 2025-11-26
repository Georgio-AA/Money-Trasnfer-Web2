@include('includes.header')

<section class="page-header">
    <h1>Select a Transfer to Review</h1>
    <p>Choose which completed transfer you'd like to review</p>
</section>

<section class="transfers-section">
    <div class="container">
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @if($eligibleTransfers->count() > 0)
            <div class="transfers-grid">
                @foreach($eligibleTransfers as $transfer)
                    <div class="transfer-card">
                        <div class="transfer-header">
                            <h3>Transfer #{{ $transfer->id }}</h3>
                            <span class="status-badge">{{ ucfirst($transfer->status) }}</span>
                        </div>

                        <div class="transfer-details">
                            <div class="detail-row">
                                <span class="label">Recipient:</span>
                                <span class="value">{{ $transfer->beneficiary->full_name }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Amount:</span>
                                <span class="value">{{ $transfer->source_currency }} {{ number_format($transfer->amount, 2) }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Recipient Gets:</span>
                                <span class="value">{{ $transfer->target_currency }} {{ number_format($transfer->payout_amount, 2) }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Date:</span>
                                <span class="value">{{ $transfer->created_at->format('M d, Y - h:i A') }}</span>
                            </div>
                            @if($transfer->completed_at)
                                <div class="detail-row">
                                    <span class="label">Completed:</span>
                                    <span class="value">{{ $transfer->completed_at->format('M d, Y - h:i A') }}</span>
                                </div>
                            @endif
                        </div>

                        <div class="transfer-actions">
                            <a href="{{ route('reviews.create', ['transfer_id' => $transfer->id]) }}" class="btn btn-primary">
                                ⭐ Review This Transfer
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">📦</div>
                <h3>No Transfers Available for Review</h3>
                <p>You don't have any completed transfers that haven't been reviewed yet.</p>
                <div class="empty-actions">
                    <a href="{{ route('transfers.index') }}" class="btn btn-primary">View My Transfers</a>
                    <a href="{{ route('reviews.index') }}" class="btn btn-secondary">Back to Reviews</a>
                </div>
            </div>
        @endif
    </div>
</section>

<style>
.page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 3rem 2rem;
    text-align: center;
}

.transfers-section {
    padding: 2rem;
    background: #f9fafb;
    min-height: 70vh;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
}

.transfers-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
    gap: 1.5rem;
}

.transfer-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s;
}

.transfer-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.transfer-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #e5e7eb;
}

.transfer-header h3 {
    margin: 0;
    color: #1f2937;
    font-size: 1.25rem;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 999px;
    font-size: 0.875rem;
    font-weight: 600;
    background: #d1fae5;
    color: #065f46;
}

.transfer-details {
    margin-bottom: 1.5rem;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f3f4f6;
}

.detail-row:last-child {
    border-bottom: none;
}

.detail-row .label {
    color: #6b7280;
    font-weight: 500;
}

.detail-row .value {
    color: #1f2937;
    font-weight: 600;
    text-align: right;
}

.transfer-actions {
    display: flex;
    justify-content: center;
}

.btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 8px;
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

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 12px;
}

.empty-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
}

.empty-state h3 {
    color: #1f2937;
    margin-bottom: 0.5rem;
    font-size: 1.5rem;
}

.empty-state p {
    color: #6b7280;
    margin-bottom: 2rem;
    font-size: 1.1rem;
}

.empty-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.alert {
    padding: 1rem;
    border-radius: 6px;
    margin-bottom: 1.5rem;
}

.alert-error {
    background-color: #fee2e2;
    color: #991b1b;
    border: 1px solid #fca5a5;
}

@media (max-width: 768px) {
    .transfers-grid {
        grid-template-columns: 1fr;
    }

    .detail-row {
        flex-direction: column;
        gap: 0.25rem;
    }

    .detail-row .value {
        text-align: left;
    }

    .empty-actions {
        flex-direction: column;
    }

    .btn {
        width: 100%;
    }
}
</style>

@include('includes.footer')
