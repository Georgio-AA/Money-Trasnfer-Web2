@include('includes.header')

<section class="page-header">
    <h1>Transfer Details</h1>
    <p>View your money transfer information</p>
</section>

<section class="transfer-details-section">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        <div class="transfer-card">
            <div class="transfer-header">
                <div>
                    <h2>Transfer #{{ $transfer->id }}</h2>
                    <span class="status-badge status-{{ $transfer->status }}">{{ ucfirst($transfer->status) }}</span>
                </div>
                <div class="transfer-date">
                    {{ $transfer->created_at->format('M d, Y - h:i A') }}
                </div>
            </div>
            
            <div class="transfer-body">
                <div class="detail-section">
                    <h3>Beneficiary Information</h3>
                    <div class="detail-row">
                        <span class="label">Name:</span>
                        <span class="value">{{ $transfer->beneficiary->full_name }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Country:</span>
                        <span class="value">{{ $transfer->beneficiary->country }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Payout Method:</span>
                        <span class="value">{{ ucwords(str_replace('_', ' ', $transfer->beneficiary->preferred_payout_method ?? 'Bank Deposit')) }}</span>
                    </div>
                </div>
                
                <div class="detail-section">
                    <h3>Transfer Details</h3>
                    <div class="detail-row">
                        <span class="label">Amount Sent:</span>
                        <span class="value">{{ $transfer->source_currency }} {{ number_format($transfer->amount, 2) }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Exchange Rate:</span>
                        <span class="value">1 {{ $transfer->source_currency }} = {{ number_format($transfer->exchange_rate, 4) }} {{ $transfer->target_currency }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Transfer Fee:</span>
                        <span class="value">{{ $transfer->source_currency }} {{ number_format($transfer->transfer_fee, 2) }}</span>
                    </div>
                    @if($transfer->promotion)
                        <div class="detail-row promotion">
                            <span class="label">Promotion Applied:</span>
                            <span class="value">{{ $transfer->promotion->title }} (-{{ $transfer->promotion->discount_percent }}%)</span>
                        </div>
                    @endif
                    <div class="detail-row highlight">
                        <span class="label"><strong>Total Paid:</strong></span>
                        <span class="value"><strong>{{ $transfer->source_currency }} {{ number_format($transfer->total_paid, 2) }}</strong></span>
                    </div>
                    <div class="detail-row payout">
                        <span class="label"><strong>Recipient Receives:</strong></span>
                        <span class="value"><strong>{{ $transfer->target_currency }} {{ number_format($transfer->payout_amount, 2) }}</strong></span>
                    </div>
                </div>
                
                <div class="detail-section">
                    <h3>Service Information</h3>
                    <div class="detail-row">
                        <span class="label">Transfer Speed:</span>
                        <span class="value">{{ ucwords(str_replace('_', ' ', $transfer->transfer_speed)) }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Status:</span>
                        <span class="value">{{ ucfirst($transfer->status) }}</span>
                    </div>
                    @if($transfer->completed_at)
                        <div class="detail-row">
                            <span class="label">Completed At:</span>
                            <span class="value">{{ $transfer->completed_at->format('M d, Y - h:i A') }}</span>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="transfer-actions">
                <a href="{{ route('transfers.index') }}" class="btn btn-primary">View All Transfers</a>
                <a href="{{ route('transfers.create') }}" class="btn btn-secondary">New Transfer</a>
            </div>
        </div>
    </div>
</section>

<style>
.page-header{background:linear-gradient(135deg,#22c55e,#06b6d4);color:#fff;padding:2rem;text-align:center}
.transfer-details-section{padding:2rem 0;min-height:60vh}
.container{max-width:900px;margin:0 auto;padding:0 1rem}
.alert{padding:1rem;border-radius:8px;margin-bottom:1.5rem}
.alert-success{background:#f0fdf4;border:1px solid #86efac;color:#166534}
.transfer-card{background:#fff;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.1);overflow:hidden}
.transfer-header{background:#f9fafb;padding:1.5rem;border-bottom:1px solid #e5e7eb;display:flex;justify-content:space-between;align-items:center}
.transfer-header h2{margin:0 0 0.5rem 0;color:#111827}
.transfer-date{color:#6b7280;font-size:0.875rem}
.status-badge{display:inline-block;padding:0.25rem 0.75rem;border-radius:999px;font-size:0.875rem;font-weight:600}
.status-pending{background:#fef3c7;color:#92400e}
.status-processing{background:#dbeafe;color:#1e40af}
.status-completed{background:#d1fae5;color:#065f46}
.status-failed{background:#fee2e2;color:#991b1b}
.status-refunded{background:#e0e7ff;color:#3730a3}
.transfer-body{padding:2rem}
.detail-section{margin-bottom:2rem}
.detail-section:last-child{margin-bottom:0}
.detail-section h3{margin:0 0 1rem 0;color:#374151;font-size:1.125rem;border-bottom:2px solid #e5e7eb;padding-bottom:0.5rem}
.detail-row{display:flex;justify-content:space-between;padding:0.75rem 0;border-bottom:1px solid #f3f4f6}
.detail-row:last-child{border-bottom:none}
.detail-row .label{color:#6b7280;font-weight:500}
.detail-row .value{color:#111827;font-weight:600;text-align:right}
.detail-row.highlight{background:#f0fdf4;padding:1rem;border-radius:8px;margin-top:0.5rem}
.detail-row.payout{background:#dcfce7;padding:1rem;border-radius:8px;margin-top:0.5rem}
.detail-row.promotion{background:#fef3c7;padding:0.5rem;border-radius:6px}
.transfer-actions{padding:1.5rem;background:#f9fafb;border-top:1px solid #e5e7eb;display:flex;gap:1rem;justify-content:center}
.btn{padding:0.75rem 1.5rem;border:none;border-radius:8px;cursor:pointer;font-size:1rem;font-weight:500;text-decoration:none;display:inline-block}
.btn-primary{background:#2563eb;color:#fff}
.btn-primary:hover{background:#1d4ed8}
.btn-secondary{background:#6b7280;color:#fff}
.btn-secondary:hover{background:#4b5563}
@media(max-width:768px){
.transfer-header{flex-direction:column;align-items:flex-start;gap:1rem}
.detail-row{flex-direction:column;gap:0.25rem}
.detail-row .value{text-align:left}
.transfer-actions{flex-direction:column}
}
</style>

@include('includes.footer')
