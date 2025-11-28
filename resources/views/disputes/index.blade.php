@include('includes.header')

<section class="page-header">
    <h1>My Disputes</h1>
    <p>Track and manage your transaction disputes</p>
</section>

<section class="disputes-section">
    <div class="container">
        <div class="disputes-header">
            <h2>Your Disputes</h2>
            <a href="{{ route('disputes.create') }}" class="btn btn-primary">File a Dispute</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @if(session('info'))
            <div class="alert alert-info">{{ session('info') }}</div>
        @endif

        @if($disputes->count() > 0)
            <div class="disputes-list">
                @foreach($disputes as $dispute)
                    <div class="dispute-card status-{{ $dispute->status }}">
                        <div class="dispute-header">
                            <div class="dispute-info">
                                <h3>Dispute #{{ $dispute->id }}</h3>
                                <span class="dispute-status status-badge-{{ $dispute->status }}">
                                    {{ ucfirst(str_replace('_', ' ', $dispute->status)) }}
                                </span>
                            </div>
                            <span class="dispute-date">Filed: {{ $dispute->created_at->format('M d, Y') }}</span>
                        </div>

                        <div class="dispute-body">
                            @if($dispute->transfer)
                                <div class="transfer-details">
                                    <p><strong>Transfer ID:</strong> #{{ $dispute->transfer->id }}</p>
                                    <p><strong>Amount:</strong> {{ $dispute->transfer->source_currency }} {{ number_format($dispute->transfer->amount, 2) }}</p>
                                    <p><strong>Date:</strong> {{ $dispute->transfer->created_at->format('M d, Y') }}</p>
                                </div>
                            @endif

                            <div class="reason-section">
                                <p><strong>Reason:</strong> {{ ucfirst(str_replace('_', ' ', $dispute->reason)) }}</p>
                            </div>

                            @if($dispute->resolved_at)
                                <p class="resolved-date"><strong>Resolved:</strong> {{ $dispute->resolved_at->format('M d, Y H:i') }}</p>
                            @endif
                        </div>

                        <div class="dispute-actions">
                            <a href="{{ route('disputes.show', $dispute->id) }}" class="btn btn-secondary btn-sm">View Details</a>
                            
                            @if($dispute->status === 'open')
                                <form action="{{ route('disputes.cancel', $dispute->id) }}" method="POST" style="display:inline;" 
                                      onsubmit="return confirm('Are you sure you want to cancel this dispute?');">
                                    @csrf
                                    @method('POST')
                                    <button type="submit" class="btn btn-secondary btn-sm">Cancel</button>
                                </form>
                            @endif

                            @if(in_array($dispute->status, ['open', 'investigating']) && !in_array($dispute->status, ['refund_requested', 'resolved']))
                                <form action="{{ route('disputes.request-refund', $dispute->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm">Request Refund</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="pagination-wrapper">
                {{ $disputes->links() }}
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">📋</div>
                <h3>No Disputes Found</h3>
                <p>You haven't filed any disputes. If you have issues with a transfer, you can file a dispute here.</p>
                <a href="{{ route('disputes.create') }}" class="btn btn-primary">File Your First Dispute</a>
            </div>
        @endif
    </div>
</section>

<style>
.page-header {
    background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
    color: white;
    padding: 3rem 2rem;
    text-align: center;
}

.disputes-section {
    padding: 2rem;
    background: #f9fafb;
    min-height: 70vh;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
}

.disputes-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.disputes-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.dispute-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    border-left: 4px solid #6b7280;
}

.dispute-card.status-open {
    border-left-color: #3b82f6;
}

.dispute-card.status-investigating {
    border-left-color: #f59e0b;
}

.dispute-card.status-resolved {
    border-left-color: #10b981;
}

.dispute-card.status-refund_requested {
    border-left-color: #8b5cf6;
}

.dispute-card.status-cancelled {
    border-left-color: #6b7280;
}

.dispute-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e5e7eb;
}

.dispute-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.dispute-info h3 {
    margin: 0;
    color: #1f2937;
}

.dispute-status {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 600;
}

.status-badge-open {
    background-color: #dbeafe;
    color: #1e40af;
}

.status-badge-investigating {
    background-color: #fef3c7;
    color: #92400e;
}

.status-badge-resolved {
    background-color: #d1fae5;
    color: #065f46;
}

.status-badge-refund_requested {
    background-color: #ede9fe;
    color: #5b21b6;
}

.status-badge-cancelled {
    background-color: #f3f4f6;
    color: #374151;
}

.dispute-date {
    color: #6b7280;
    font-size: 0.875rem;
}

.dispute-body {
    margin-bottom: 1rem;
}

.transfer-details {
    background: #f9fafb;
    padding: 1rem;
    border-radius: 6px;
    margin-bottom: 1rem;
}

.transfer-details p {
    margin: 0.25rem 0;
    color: #374151;
}

.reason-section {
    margin: 1rem 0;
}

.resolved-date {
    color: #059669;
    font-weight: 600;
    margin-top: 1rem;
}

.dispute-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.btn {
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 500;
    transition: all 0.2s;
    display: inline-block;
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

.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.8125rem;
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
}

.empty-state p {
    color: #6b7280;
    margin-bottom: 1.5rem;
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

.alert-error {
    background-color: #fee2e2;
    color: #991b1b;
    border: 1px solid #fca5a5;
}

.alert-info {
    background-color: #dbeafe;
    color: #1e40af;
    border: 1px solid #93c5fd;
}

@media (max-width: 768px) {
    .disputes-header {
        flex-direction: column;
        gap: 1rem;
    }

    .dispute-header {
        flex-direction: column;
        gap: 0.5rem;
    }

    .dispute-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
}
</style>

@include('includes.footer')
