@include('includes.header')

<div class="container-fluid mt-5 mb-5">
    <!-- Page Header -->
    <div class="row mb-5">
        <div class="col-md-6">
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <i class="fas fa-boxes" style="font-size: 2.5rem; color: #007bff;"></i>
                </div>
                <div>
                    <h1 class="mb-2" style="font-size: 2.5rem; font-weight: 700; color: #212529;">
                        Store Products
                    </h1>
                    <p class="text-muted" style="font-size: 1rem; margin-bottom: 0;">
                        Manage digital services inventory
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-6 text-end d-flex align-items-center justify-content-end">
            <a href="{{ route('admin.store.products.create') }}" class="btn btn-primary" style="padding: 0.6rem 1.5rem; font-weight: 600; border-radius: 6px; box-shadow: 0 2px 4px rgba(0, 123, 255, 0.25);">
                <i class="fas fa-plus-circle me-2"></i> Add Product
            </a>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 mb-4" role="alert" style="background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%); border-left: 4px solid #28a745;">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 mb-4" role="alert" style="background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%); border-left: 4px solid #dc3545;">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="row mb-4 g-3">
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 h-100" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-left: 4px solid #007bff;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-1 fw-semibold" style="font-size: 0.85rem;">Total Products</p>
                            <h3 class="text-primary mb-0" style="font-size: 2.25rem; font-weight: 700;">
                                {{ $products->total() }}
                            </h3>
                        </div>
                        <div style="font-size: 2.5rem; color: #007bff; opacity: 0.2;">
                            <i class="fas fa-boxes"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="card border-0 h-100" style="background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%); border-left: 4px solid #28a745;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-1 fw-semibold" style="font-size: 0.85rem;">Active</p>
                            <h3 class="mb-0" style="font-size: 2.25rem; font-weight: 700; color: #28a745;">
                                {{ $products->where('is_active', true)->count() }}
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
            <div class="card border-0 h-100" style="background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%); border-left: 4px solid #ffc107;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-1 fw-semibold" style="font-size: 0.85rem;">Inactive</p>
                            <h3 class="mb-0" style="font-size: 2.25rem; font-weight: 700; color: #ff9800;">
                                {{ $products->where('is_active', false)->count() }}
                            </h3>
                        </div>
                        <div style="font-size: 2.5rem; color: #ffc107; opacity: 0.2;">
                            <i class="fas fa-pause-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="card border-0 h-100" style="background: linear-gradient(135deg, #cfe2ff 0%, #b6d4fe 100%); border-left: 4px solid #17a2b8;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-1 fw-semibold" style="font-size: 0.85rem;">Categories</p>
                            <h3 class="mb-0" style="font-size: 2.25rem; font-weight: 700; color: #17a2b8;">
                                {{ $products->pluck('category')->unique()->count() }}
                            </h3>
                        </div>
                        <div style="font-size: 2.5rem; color: #17a2b8; opacity: 0.2;">
                            <i class="fas fa-list"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="card border-0 shadow-sm" style="border-radius: 8px; overflow: hidden;">
        <div class="card-header bg-primary text-white py-3">
            <h6 class="mb-0 d-flex align-items-center">
                <i class="fas fa-table me-2"></i> All Products
            </h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light" style="background-color: #f8f9fa;">
                    <tr>
                        <th style="border: none; padding: 1.25rem 1rem; font-weight: 600; color: #212529; font-size: 0.9rem;">Product Name</th>
                        <th style="border: none; padding: 1.25rem 1rem; font-weight: 600; color: #212529; font-size: 0.9rem;">Provider</th>
                        <th style="border: none; padding: 1.25rem 1rem; font-weight: 600; color: #212529; font-size: 0.9rem;">Category</th>
                        <th style="border: none; padding: 1.25rem 1rem; font-weight: 600; color: #212529; font-size: 0.9rem;">Price</th>
                        <th style="border: none; padding: 1.25rem 1rem; font-weight: 600; color: #212529; font-size: 0.9rem;">Status</th>
                        <th style="border: none; padding: 1.25rem 1rem; font-weight: 600; color: #212529; font-size: 0.9rem;">Orders</th>
                        <th style="border: none; padding: 1.25rem 1rem; font-weight: 600; color: #212529; font-size: 0.9rem;">Created</th>
                        <th style="border: none; padding: 1.25rem 1rem; font-weight: 600; color: #212529; font-size: 0.9rem;">Actions</th>
                    </tr>
                </thead>
                <tbody style="border-top: 2px solid #dee2e6;">
                    @forelse($products as $product)
                        <tr style="border-bottom: 1px solid #dee2e6;">
                            <td style="padding: 1.25rem 1rem; vertical-align: middle;">
                                <strong style="color: #212529; font-weight: 600;">{{ $product->name }}</strong>
                            </td>
                            <td style="padding: 1.25rem 1rem; vertical-align: middle;">
                                <i class="fas fa-building me-1" style="color: #6c757d;"></i> {{ $product->provider }}
                            </td>
                            <td style="padding: 1.25rem 1rem; vertical-align: middle;">
                                <span class="badge" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); padding: 0.4rem 0.7rem; font-size: 0.85rem;">
                                    {{ ucfirst(str_replace('_', ' ', $product->category)) }}
                                </span>
                            </td>
                            <td style="padding: 1.25rem 1rem; vertical-align: middle;">
                                <span class="text-primary fw-bold" style="font-size: 1.1rem;">${{ number_format($product->price, 2) }}</span>
                            </td>
                            <td style="padding: 1.25rem 1rem; vertical-align: middle;">
                                @if($product->is_active)
                                    <span class="badge" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); padding: 0.5rem 0.8rem; font-size: 0.85rem;">
                                        <i class="fas fa-check-circle me-1"></i> Active
                                    </span>
                                @else
                                    <span class="badge bg-secondary" style="padding: 0.5rem 0.8rem; font-size: 0.85rem;">
                                        <i class="fas fa-lock me-1"></i> Inactive
                                    </span>
                                @endif
                            </td>
                            <td style="padding: 1.25rem 1rem; vertical-align: middle;">
                                <span class="badge bg-info" style="padding: 0.5rem 0.8rem; font-size: 0.85rem;">{{ $product->orders_count ?? 0 }}</span>
                            </td>
                            <td style="padding: 1.25rem 1rem; vertical-align: middle;">
                                <small class="text-muted">{{ $product->created_at->format('M d, Y') }}</small>
                            </td>
                            <td style="padding: 1.25rem 1rem; vertical-align: middle;">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.store.products.edit', $product->id) }}" class="btn btn-outline-primary" title="Edit" style="padding: 0.35rem 0.6rem; border-radius: 4px;">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.store.products.toggle', $product->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-outline-warning" title="{{ $product->is_active ? 'Deactivate' : 'Activate' }}" style="padding: 0.35rem 0.6rem; border-radius: 4px;">
                                            <i class="fas {{ $product->is_active ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                        </button>
                                    </form>
                                    @if(($product->orders_count ?? 0) === 0)
                                        <form action="{{ route('admin.store.products.destroy', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this product?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Delete" style="padding: 0.35rem 0.6rem; border-radius: 4px;">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <button type="button" class="btn btn-outline-danger" disabled title="Cannot delete - has orders" style="padding: 0.35rem 0.6rem; border-radius: 4px;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="fas fa-inbox" style="font-size: 3rem; color: #dee2e6; margin-bottom: 1rem;"></i>
                                <p class="mt-3 text-muted" style="font-size: 1.1rem;">No products found</p>
                                <a href="{{ route('admin.store.products.create') }}" class="btn btn-primary btn-sm" style="padding: 0.5rem 1rem;">
                                    <i class="fas fa-plus-circle me-1"></i> Create First Product
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $products->links() }}
        </div>
    @endif

    <!-- Quick Links -->
    <div class="row mt-4">
        <div class="col-md-12">
            <a href="{{ route('admin.store.orders.index') }}" class="btn btn-outline-secondary" style="padding: 0.6rem 1.2rem; border-radius: 6px;">
                <i class="fas fa-list me-2"></i> View All Orders
            </a>
        </div>
    </div>
</div>

<style>
    .table tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }

    .btn-group-sm .btn {
        padding: 0.375rem 0.5rem;
        font-size: 0.875rem;
    }
</style>

@include('includes.footer')
