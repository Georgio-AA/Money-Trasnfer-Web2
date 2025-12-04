@include('includes.header')

<div class="container-fluid mt-5 mb-5">
    <!-- Page Header -->
    <div class="row mb-5">
        <div class="col-md-12">
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <i class="fas fa-receipt" style="font-size: 2.5rem; color: #007bff;"></i>
                </div>
                <div>
                    <h1 class="mb-2" style="font-size: 2.5rem; font-weight: 700; color: #212529;">
                        Store Orders
                    </h1>
                    <p class="text-muted" style="font-size: 1rem; margin-bottom: 0;">
                        Monitor all customer purchases and digital service deliveries
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="row mb-4 g-3">
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 h-100" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-left: 4px solid #007bff;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-1 fw-semibold" style="font-size: 0.85rem;">Total Orders</p>
                            <h3 class="text-primary mb-0" style="font-size: 2.25rem; font-weight: 700;">
                                {{ $orders->total() }}
                            </h3>
                        </div>
                        <div style="font-size: 2.5rem; color: #007bff; opacity: 0.2;">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="card border-0 h-100" style="background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border-left: 4px solid #28a745;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-1 fw-semibold" style="font-size: 0.85rem;">Completed</p>
                            <h3 class="mb-0" style="font-size: 2.25rem; font-weight: 700; color: #28a745;">
                                {{ $orders->where('status', 'completed')->count() }}
                            </h3>
                        </div>
                        <div style="font-size: 2.5rem; color: #28a745; opacity: 0.2;">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="card border-0 h-100" style="background: linear-gradient(135deg, #fffaeb 0%, #fef3c7 100%); border-left: 4px solid #ffc107;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-1 fw-semibold" style="font-size: 0.85rem;">Pending</p>
                            <h3 class="mb-0" style="font-size: 2.25rem; font-weight: 700; color: #ff9800;">
                                {{ $orders->where('status', 'pending')->count() }}
                            </h3>
                        </div>
                        <div style="font-size: 2.5rem; color: #ffc107; opacity: 0.2;">
                            <i class="fas fa-hourglass"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="card border-0 h-100" style="background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%); border-left: 4px solid #dc3545;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-1 fw-semibold" style="font-size: 0.85rem;">Failed</p>
                            <h3 class="mb-0" style="font-size: 2.25rem; font-weight: 700; color: #dc3545;">
                                {{ $orders->where('status', 'failed')->count() }}
                            </h3>
                        </div>
                        <div style="font-size: 2.5rem; color: #dc3545; opacity: 0.2;">
                            <i class="fas fa-times-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 mb-4" role="alert" style="background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%); border-left: 4px solid #28a745;">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Orders Table -->
    <div class="card border-0 shadow-sm" style="border-radius: 8px; overflow: hidden;">
        <div class="card-header bg-primary text-white py-3">
            <h6 class="mb-0 d-flex align-items-center">
                <i class="fas fa-table me-2"></i> All Orders
            </h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light" style="background-color: #f8f9fa;">
                    <tr>
                        <th style="border: none; padding: 1.25rem 1rem; font-weight: 600; color: #212529; font-size: 0.9rem;">Order ID</th>
                        <th style="border: none; padding: 1.25rem 1rem; font-weight: 600; color: #212529; font-size: 0.9rem;">Customer</th>
                        <th style="border: none; padding: 1.25rem 1rem; font-weight: 600; color: #212529; font-size: 0.9rem;">Product</th>
                        <th style="border: none; padding: 1.25rem 1rem; font-weight: 600; color: #212529; font-size: 0.9rem;">Provider</th>
                        <th style="border: none; padding: 1.25rem 1rem; font-weight: 600; color: #212529; font-size: 0.9rem;">Amount</th>
                        <th style="border: none; padding: 1.25rem 1rem; font-weight: 600; color: #212529; font-size: 0.9rem;">Code</th>
                        <th style="border: none; padding: 1.25rem 1rem; font-weight: 600; color: #212529; font-size: 0.9rem;">Status</th>
                        <th style="border: none; padding: 1.25rem 1rem; font-weight: 600; color: #212529; font-size: 0.9rem;">Date</th>
                        <th style="border: none; padding: 1.25rem 1rem; font-weight: 600; color: #212529; font-size: 0.9rem;">Actions</th>
                    </tr>
                </thead>
                <tbody style="border-top: 2px solid #dee2e6;">
                    @forelse($orders as $order)
                        <tr style="border-bottom: 1px solid #dee2e6;">
                            <td style="padding: 1.25rem 1rem; vertical-align: middle;">
                                <code style="background: #f8f9fa; padding: 0.35rem 0.6rem; border-radius: 4px; font-size: 0.85rem; color: #6c757d;">#{{ $order->id }}</code>
                            </td>
                            <td style="padding: 1.25rem 1rem; vertical-align: middle;">
                                <strong style="color: #212529; font-weight: 600;">{{ $order->user->name ?? 'Unknown' }}</strong>
                                <br>
                                <small class="text-muted">{{ $order->user->email ?? 'N/A' }}</small>
                            </td>
                            <td style="padding: 1.25rem 1rem; vertical-align: middle;">
                                <strong style="color: #212529; font-weight: 600;">{{ $order->product->name }}</strong>
                                <br>
                                <small class="text-muted">
                                    {{ ucfirst(str_replace('_', ' ', $order->product->category)) }}
                                </small>
                            </td>
                            <td style="padding: 1.25rem 1rem; vertical-align: middle;">
                                <i class="fas fa-building me-1" style="color: #6c757d;"></i> {{ $order->product->provider }}
                            </td>
                            <td style="padding: 1.25rem 1rem; vertical-align: middle;">
                                <span class="text-primary fw-bold" style="font-size: 1.1rem;">${{ number_format($order->price_at_purchase, 2) }}</span>
                            </td>
                            <td style="padding: 1.25rem 1rem; vertical-align: middle;">
                                <code style="background: #f8f9fa; padding: 0.35rem 0.6rem; border-radius: 4px; font-size: 0.8rem; display: block; max-width: 120px; overflow: hidden; text-overflow: ellipsis;" title="{{ $order->generated_code }}">
                                    {{ $order->generated_code }}
                                </code>
                                <button 
                                    class="btn btn-link btn-sm copy-code-btn p-0 mt-1" 
                                    data-code="{{ $order->generated_code }}"
                                    title="Copy code"
                                    style="color: #007bff; text-decoration: none; font-size: 0.85rem;"
                                >
                                    <i class="fas fa-copy"></i> Copy
                                </button>
                            </td>
                            <td style="padding: 1.25rem 1rem; vertical-align: middle;">
                                @if($order->status === 'completed')
                                    <span class="badge" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); padding: 0.5rem 0.8rem; font-size: 0.85rem;">
                                        <i class="fas fa-check-circle me-1"></i> Completed
                                    </span>
                                @elseif($order->status === 'pending')
                                    <span class="badge bg-warning text-dark" style="padding: 0.5rem 0.8rem; font-size: 0.85rem;">
                                        <i class="fas fa-hourglass me-1"></i> Pending
                                    </span>
                                @else
                                    <span class="badge bg-danger" style="padding: 0.5rem 0.8rem; font-size: 0.85rem;">
                                        <i class="fas fa-times-circle me-1"></i> Failed
                                    </span>
                                @endif
                            </td>
                            <td style="padding: 1.25rem 1rem; vertical-align: middle;">
                                <small class="text-muted">
                                    {{ $order->created_at->format('M d, Y') }}
                                    <br>
                                    {{ $order->created_at->format('h:i A') }}
                                </small>
                            </td>
                            <td style="padding: 1.25rem 1rem; vertical-align: middle;">
                                <a href="{{ route('admin.store.orders.index') }}" class="btn btn-sm btn-outline-primary" title="View Details" style="padding: 0.35rem 0.7rem; border-radius: 4px;">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <i class="fas fa-inbox" style="font-size: 3rem; color: #dee2e6; margin-bottom: 1rem;"></i>
                                <p class="mt-3 text-muted" style="font-size: 1.1rem;">No orders yet</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($orders->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $orders->links() }}
        </div>
    @endif

    <!-- Back Button -->
    <div class="row mt-4">
        <div class="col-md-12">
            <a href="{{ route('admin.store.products.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Back to Products
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const copyBtns = document.querySelectorAll('.copy-code-btn');
    
    copyBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const code = this.dataset.code;
            
            // Copy to clipboard
            navigator.clipboard.writeText(code).then(() => {
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-check"></i> Copied!';
                this.classList.add('text-success');
                
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.classList.remove('text-success');
                }, 2000);
            });
        });
    });
});
</script>

<style>
    .table tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }

    code {
        background-color: #f5f5f5;
        padding: 0.2rem 0.4rem;
        border-radius: 3px;
        font-size: 0.85em;
    }
</style>

@include('includes.footer')
