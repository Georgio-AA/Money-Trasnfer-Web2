@include('includes.header')

<section class="page-header">
    <h1>Dispute Details</h1>
    <p>View your dispute information</p>
</section>

<section class="dispute-detail-section">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('info'))
            <div class="alert alert-info">{{ session('info') }}</div>
        @endif

        <div class="dispute-card">
            <div class="dispute-header">
                <div>
                    <h2>Dispute #{{ $dispute->id }}</h2>
                    <span class="status-badge status-{{ $dispute->status }}">
                        {{ ucfirst(str_replace('_', ' ', $dispute->status)) }}
                    </span>
                </div>
                <div class="dispute-dates">
                    <p><strong>Filed:</strong> {{ $dispute->created_at->format('M d, Y H:i') }}</p>
                    @if($dispute->resolved_at)
                        <p><strong>Resolved:</strong> {{ $dispute->resolved_at->format('M d, Y H:i') }}</p>
                    @endif
                </div>
            </div>

            @if($dispute->transfer)
                <div class="section">
                    <h3>Transfer Information</h3>
                    <div class="transfer-info">
                        <div class="info-row">
                            <span class="label">Transfer ID:</span>
                            <span class="value">#{{ $dispute->transfer->id }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Amount:</span>
                            <span class="value">{{ $dispute->transfer->source_currency }} {{ number_format($dispute->transfer->amount, 2) }}</span>
                        </div>
                        @if($dispute->transfer->beneficiary)
                            <div class="info-row">
                                <span class="label">Recipient:</span>
                                <span class="value">{{ $dispute->transfer->beneficiary->full_name }}</span>
                            </div>
                        @endif
                        <div class="info-row">
                            <span class="label">Transfer Date:</span>
                            <span class="value">{{ $dispute->transfer->created_at->format('M d, Y H:i') }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Transfer Status:</span>
                            <span class="value status-{{ $dispute->transfer->status }}">{{ ucfirst($dispute->transfer->status) }}</span>
                        </div>
                        <div class="info-row">
                            <a href="{{ route('transfers.show', $dispute->transfer->id) }}" class="btn btn-secondary btn-sm">View Transfer Details</a>
                        </div>
                    </div>
                </div>
            @endif

            <div class="section">
                <h3>Dispute Details</h3>
                <div class="dispute-info">
                    <div class="info-row">
                        <span class="label">Reason:</span>
                        <span class="value reason">{{ ucfirst(str_replace('_', ' ', $dispute->reason)) }}</span>
                    </div>
                    <div class="info-row full-width">
                        <span class="label">Description:</span>
                        <div class="description-box">{{ $dispute->description }}</div>
                    </div>
                </div>
            </div>

            <div class="section">
                <h3>Resolution Status</h3>
                <div class="status-timeline">
                    <div class="timeline-item {{ $dispute->status == 'open' ? 'active' : 'completed' }}">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h4>Dispute Filed</h4>
                            <p>{{ $dispute->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                    <div class="timeline-item {{ in_array($dispute->status, ['investigating', 'refund_requested', 'resolved']) ? 'active' : '' }} {{ in_array($dispute->status, ['refund_requested', 'resolved']) ? 'completed' : '' }}">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h4>Under Investigation</h4>
                            @if(in_array($dispute->status, ['investigating', 'refund_requested', 'resolved']))
                                <p>In progress</p>
                            @else
                                <p>Pending</p>
                            @endif
                        </div>
                    </div>
                    <div class="timeline-item {{ $dispute->status == 'resolved' ? 'completed' : '' }}">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h4>Resolved</h4>
                            @if($dispute->resolved_at)
                                <p>{{ $dispute->resolved_at->format('M d, Y H:i') }}</p>
                            @else
                                <p>Pending</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="action-section">
                @if($dispute->status === 'open')
                    <form action="{{ route('disputes.cancel', $dispute->id) }}" method="POST" style="display:inline;" 
                          onsubmit="return confirm('Are you sure you want to cancel this dispute? This action cannot be undone.');">
                        @csrf
                        @method('POST')
                        <button type="submit" class="btn btn-secondary">Cancel Dispute</button>
                    </form>
                @endif

                @if(in_array($dispute->status, ['open', 'investigating']) && !in_array($dispute->status, ['refund_requested', 'resolved']))
                    <form action="{{ route('disputes.request-refund', $dispute->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-primary">Request Refund</button>
                    </form>
                @endif

                <a href="{{ route('disputes.index') }}" class="btn btn-secondary">Back to Disputes</a>
            </div>
        </div>
    </div>
</section>

<style>
.page-header {
    background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
    color: white;
    padding: 3rem 2rem;
    text-align: center;
}

.dispute-detail-section {
    padding: 2rem;
    background: #f9fafb;
    min-height: 70vh;
}

.container {
    max-width: 900px;
    margin: 0 auto;
}

.dispute-card {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.dispute-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding-bottom: 1.5rem;
    border-bottom: 2px solid #e5e7eb;
    margin-bottom: 2rem;
}

.dispute-header h2 {
    margin: 0 0 0.5rem 0;
    color: #1f2937;
}

.status-badge {
    display: inline-block;
    padding: 0.375rem 0.875rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 600;
}

.status-open { background: #dbeafe; color: #1e40af; }
.status-investigating { background: #fef3c7; color: #92400e; }
.status-resolved { background: #d1fae5; color: #065f46; }
.status-refund_requested { background: #ede9fe; color: #5b21b6; }
.status-cancelled { background: #f3f4f6; color: #374151; }

.dispute-dates {
    text-align: right;
}

.dispute-dates p {
    margin: 0.25rem 0;
    color: #6b7280;
    font-size: 0.875rem;
}

.section {
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid #e5e7eb;
}

.section:last-of-type {
    border-bottom: none;
}

.section h3 {
    margin: 0 0 1rem 0;
    color: #374151;
    font-size: 1.25rem;
}

.transfer-info,
.dispute-info {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.info-row.full-width {
    flex-direction: column;
    align-items: flex-start;
    gap: 0.5rem;
}

.label {
    font-weight: 600;
    color: #6b7280;
}

.value {
    color: #1f2937;
    font-weight: 500;
}

.value.reason {
    color: #dc2626;
}

.description-box {
    background: #f9fafb;
    padding: 1rem;
    border-radius: 6px;
    border-left: 3px solid #3b82f6;
    line-height: 1.6;
    color: #374151;
    width: 100%;
}

.status-timeline {
    display: flex;
    justify-content: space-between;
    position: relative;
    padding: 2rem 0;
}

.timeline-item {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
}

.timeline-marker {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #e5e7eb;
    border: 4px solid #f3f4f6;
    position: relative;
    z-index: 2;
}

.timeline-item.active .timeline-marker {
    background: #3b82f6;
    border-color: #dbeafe;
}

.timeline-item.completed .timeline-marker {
    background: #10b981;
    border-color: #d1fae5;
}

.timeline-item::before {
    content: '';
    position: absolute;
    top: 20px;
    left: 50%;
    right: -50%;
    height: 4px;
    background: #e5e7eb;
    z-index: 1;
}

.timeline-item:last-child::before {
    display: none;
}

.timeline-item.completed::before {
    background: #10b981;
}

.timeline-content {
    margin-top: 1rem;
    text-align: center;
}

.timeline-content h4 {
    margin: 0 0 0.25rem 0;
    color: #1f2937;
    font-size: 0.9375rem;
}

.timeline-content p {
    margin: 0;
    color: #6b7280;
    font-size: 0.875rem;
}

.action-section {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    flex-wrap: wrap;
    padding-top: 1.5rem;
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

.btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
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

.alert-info {
    background-color: #dbeafe;
    color: #1e40af;
    border: 1px solid #93c5fd;
}

@media (max-width: 768px) {
    .dispute-header {
        flex-direction: column;
        gap: 1rem;
    }

    .dispute-dates {
        text-align: left;
    }

    .info-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
    }

    .status-timeline {
        flex-direction: column;
        gap: 2rem;
    }

    .timeline-item::before {
        top: 40px;
        bottom: -2rem;
        left: 20px;
        right: auto;
        width: 4px;
        height: auto;
    }

    .timeline-content {
        text-align: left;
        margin-left: 1rem;
    }

    .action-section {
        flex-direction: column;
    }

    .btn {
        width: 100%;
    }
}
</style>

@include('includes.footer')
