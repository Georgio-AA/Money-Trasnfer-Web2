@include('includes.header')

<style>
    .page-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 3rem 2rem;
        border-radius: 1rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
    }

    .page-hero h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .page-hero p {
        font-size: 1.05rem;
        opacity: 0.9;
    }

    .stat-card {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--gradient-start), var(--gradient-end));
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    }

    .stat-card.total {
        --gradient-start: #667eea;
        --gradient-end: #764ba2;
    }

    .stat-card.spent {
        --gradient-start: #11998e;
        --gradient-end: #38ef7d;
    }

    .stat-card.recent {
        --gradient-start: #f093fb;
        --gradient-end: #f5576c;
    }

    .stat-label {
        color: #6c757d;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
        margin-bottom: 0.75rem;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .purchases-table-wrapper {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        border: 1px solid #e9ecef;
    }

    .table {
        margin-bottom: 0;
    }

    .table thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .table thead th {
        padding: 1.25rem;
        font-weight: 600;
        border: none;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }

    .table tbody td {
        padding: 1.25rem;
        border-top: 1px solid #e9ecef;
        vertical-align: middle;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .table tbody tr:first-child td {
        border-top: none;
    }

    .order-id {
        font-weight: 600;
        color: #007bff;
        font-family: 'Courier New', monospace;
    }

    .product-name {
        font-weight: 600;
        color: #212529;
        margin-bottom: 0.25rem;
    }

    .product-category {
        font-size: 0.85rem;
        color: #6c757d;
    }

    .amount-price {
        font-weight: 700;
        font-size: 1.1rem;
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .status-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .status-badge.completed {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white;
    }

    .status-badge.pending {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }

    .status-badge.failed {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        color: white;
    }

    .code-cell {
        font-family: 'Courier New', monospace;
        font-size: 0.9rem;
        font-weight: 600;
        color: #495057;
    }

    .action-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.9rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        color: white;
    }

    .copy-code-btn {
        background: white;
        color: #667eea;
        border: 1px solid #667eea;
        padding: 0.35rem 0.75rem;
        font-size: 0.8rem;
    }

    .copy-code-btn:hover {
        background: #667eea;
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-state-icon {
        font-size: 4rem;
        color: #dee2e6;
        margin-bottom: 1rem;
    }

    .empty-state h5 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #212529;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: #6c757d;
        margin-bottom: 1.5rem;
    }

    .browse-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 0.5rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .browse-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        color: white;
    }

    .back-link {
        color: #667eea;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .back-link:hover {
        color: #764ba2;
        transform: translateX(-4px);
    }

    .pagination {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 2rem;
    }

    .pagination .page-link {
        color: #667eea;
        border: 1px solid #dee2e6;
        border-radius: 0.5rem;
        padding: 0.5rem 0.75rem;
    }

    .pagination .page-link:hover {
        background: #667eea;
        color: white;
        border-color: #667eea;
    }

    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: #667eea;
    }

    @media (max-width: 768px) {
        .page-hero h1 {
            font-size: 1.8rem;
        }

        .table {
            font-size: 0.9rem;
        }

        .table thead th,
        .table tbody td {
            padding: 0.75rem;
        }

        .action-btn {
            padding: 0.4rem 0.8rem;
            font-size: 0.8rem;
        }
    }
</style>

<div class="container mt-5">
    <!-- Page Header -->
    <div class="page-hero">
        <h1>
            <i class="fas fa-history me-3"></i> My Purchases
        </h1>
        <p>View your complete digital service purchase history and redemption codes</p>
    </div>

    @if($purchases->count() > 0)
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="stat-card total">
                    <p class="stat-label">
                        <i class="fas fa-shopping-bag me-2"></i> Total Purchases
                    </p>
                    <div class="stat-value">{{ $purchases->total() }}</div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stat-card spent">
                    <p class="stat-label">
                        <i class="fas fa-dollar-sign me-2"></i> Total Spent
                    </p>
                    <div class="stat-value">${{ number_format($purchases->sum('price_at_purchase'), 2) }}</div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stat-card recent">
                    <p class="stat-label">
                        <i class="fas fa-calendar me-2"></i> Recent 30 Days
                    </p>
                    <div class="stat-value">{{ $purchases->whereBetween('created_at', [now()->subDays(30), now()])->count() }}</div>
                </div>
            </div>
        </div>

        <!-- Purchases Table -->
        <div class="purchases-table-wrapper mb-4">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Product</th>
                            <th>Provider</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Redemption Code</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchases as $order)
                            <tr>
                                <td class="order-id">#{{ $order->id }}</td>
                                <td>
                                    <div class="product-name">{{ $order->product->name }}</div>
                                    <div class="product-category">{{ ucfirst(str_replace('_', ' ', $order->product->category)) }}</div>
                                </td>
                                <td>
                                    <i class="fas fa-building me-1" style="color: #667eea;"></i> {{ $order->product->provider }}
                                </td>
                                <td class="amount-price">${{ number_format($order->price_at_purchase, 2) }}</td>
                                <td>
                                    <div style="font-size: 0.9rem; font-weight: 600;">{{ $order->created_at->format('M d, Y') }}</div>
                                    <div style="font-size: 0.85rem; color: #6c757d;">{{ $order->created_at->format('h:i A') }}</div>
                                </td>
                                <td>
                                    @if($order->status === 'completed')
                                        <span class="status-badge completed">
                                            <i class="fas fa-check-circle me-1"></i> Completed
                                        </span>
                                    @elseif($order->status === 'pending')
                                        <span class="status-badge pending">
                                            <i class="fas fa-hourglass me-1"></i> Pending
                                        </span>
                                    @else
                                        <span class="status-badge failed">
                                            <i class="fas fa-times-circle me-1"></i> Failed
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="code-cell" title="{{ $order->generated_code }}" style="max-width: 120px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        {{ $order->generated_code }}
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('store.confirmation', $order->id) }}" class="action-btn">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($purchases->hasPages())
            <div class="d-flex justify-content-center">
                {{ $purchases->links() }}
            </div>
        @endif

        <!-- Back Button -->
        <div class="mt-4 mb-4">
            <a href="{{ route('store.index') }}" class="back-link">
                <i class="fas fa-arrow-left"></i> Back to Store
            </a>
        </div>
    @else
        <!-- Empty State -->
        <div class="purchases-table-wrapper">
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-inbox"></i>
                </div>
                <h5>No Purchases Yet</h5>
                <p>You haven't purchased any digital services yet. Start browsing our collection today!</p>
                <a href="{{ route('store.index') }}" class="browse-btn">
                    <i class="fas fa-shopping-bag"></i> Browse Store
                </a>
            </div>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add any additional JavaScript interactions here
});
</script>

@include('includes.footer')
