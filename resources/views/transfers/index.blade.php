@include('includes.header')

<section class="page-header">
    <h1>My Transfers</h1>
    <p>View and manage all your money transfers</p>
</section>

<section class="transfers-list-section">
    <div class="container">
        <div class="page-actions">
            <a href="{{ route('transfers.create') }}" class="btn btn-primary">
                <span>+</span> New Transfer
            </a>
        </div>
        
        @if($transfers->isEmpty())
            <div class="empty-state">
                <div class="empty-icon">📤</div>
                <h3>No transfers yet</h3>
                <p>Start sending money to your beneficiaries</p>
                <a href="{{ route('transfers.create') }}" class="btn btn-primary">Initiate Transfer</a>
            </div>
        @else
            <div class="transfers-table">
                <table>
                    <thead>
                        <tr>
                            <th>Transfer ID</th>
                            <th>Beneficiary</th>
                            <th>Amount</th>
                            <th>Recipient Gets</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transfers as $transfer)
                            <tr>
                                <td>#{{ $transfer->id }}</td>
                                <td>
                                    <div class="beneficiary-info">
                                        <strong>{{ $transfer->beneficiary->full_name }}</strong>
                                        <small>{{ $transfer->beneficiary->country }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="amount-info">
                                        <strong>{{ $transfer->source_currency }} {{ number_format($transfer->amount, 2) }}</strong>
                                        <small>Fee: {{ number_format($transfer->transfer_fee, 2) }}</small>
                                    </div>
                                </td>
                                <td class="payout-amount">
                                    {{ $transfer->target_currency }} {{ number_format($transfer->payout_amount, 2) }}
                                </td>
                                <td>
                                    <span class="status-badge status-{{ $transfer->status }}">
                                        {{ ucfirst($transfer->status) }}
                                    </span>
                                </td>
                                <td>{{ $transfer->created_at->format('M d, Y') }}</td>
                                <td>
                                    <a href="{{ route('transfers.show', $transfer->id) }}" class="btn-link">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="pagination-wrapper">
                {{ $transfers->links() }}
            </div>
        @endif
    </div>
</section>

<style>
.page-header{background:linear-gradient(135deg,#22c55e,#06b6d4);color:#fff;padding:2rem;text-align:center}
.transfers-list-section{padding:2rem 0;min-height:60vh}
.container{max-width:1200px;margin:0 auto;padding:0 1rem}
.page-actions{display:flex;justify-content:flex-end;margin-bottom:1.5rem}
.btn{padding:0.75rem 1.5rem;border:none;border-radius:8px;cursor:pointer;font-size:1rem;font-weight:500;text-decoration:none;display:inline-flex;align-items:center;gap:0.5rem}
.btn-primary{background:#2563eb;color:#fff}
.btn-primary:hover{background:#1d4ed8}
.empty-state{text-align:center;padding:4rem 2rem;background:#fff;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.1)}
.empty-icon{font-size:4rem;margin-bottom:1rem}
.empty-state h3{margin:0 0 0.5rem 0;color:#374151;font-size:1.5rem}
.empty-state p{color:#6b7280;margin-bottom:2rem}
.transfers-table{background:#fff;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.1);overflow:hidden}
table{width:100%;border-collapse:collapse}
thead{background:#f9fafb;border-bottom:2px solid #e5e7eb}
thead th{padding:1rem;text-align:left;font-weight:600;color:#374151;font-size:0.875rem;text-transform:uppercase}
tbody tr{border-bottom:1px solid #f3f4f6;transition:background 0.2s}
tbody tr:hover{background:#f9fafb}
tbody td{padding:1rem;color:#111827}
.beneficiary-info{display:flex;flex-direction:column;gap:0.25rem}
.beneficiary-info strong{color:#111827}
.beneficiary-info small{color:#6b7280;font-size:0.875rem}
.amount-info{display:flex;flex-direction:column;gap:0.25rem}
.amount-info strong{color:#111827}
.amount-info small{color:#6b7280;font-size:0.875rem}
.payout-amount{color:#059669;font-weight:600}
.status-badge{display:inline-block;padding:0.25rem 0.75rem;border-radius:999px;font-size:0.875rem;font-weight:600}
.status-pending{background:#fef3c7;color:#92400e}
.status-processing{background:#dbeafe;color:#1e40af}
.status-completed{background:#d1fae5;color:#065f46}
.status-failed{background:#fee2e2;color:#991b1b}
.status-refunded{background:#e0e7ff;color:#3730a3}
.btn-link{color:#2563eb;text-decoration:none;font-weight:600;font-size:0.875rem}
.btn-link:hover{text-decoration:underline}
.pagination-wrapper{margin-top:2rem;display:flex;justify-content:center}
@media(max-width:768px){
.transfers-table{overflow-x:auto}
table{min-width:800px}
}
</style>

@include('includes.footer')
