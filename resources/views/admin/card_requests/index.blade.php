@include('includes.header')

<section class="admin-page-header">
    <h1>Card Requests</h1>
    <p>Review and manage pending card requests</p>
</section>

<section class="admin-content">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        <div class="requests-header">
            <h2>Pending Requests</h2>
            <div class="filter-info">
                Total: <strong>{{ $cardRequests->total() }}</strong> request(s)
            </div>
        </div>

        @if($cardRequests->isEmpty())
            <div class="empty-state">
                <div class="empty-icon">📋</div>
                <h3>No Pending Requests</h3>
                <p>There are currently no card requests awaiting review.</p>
            </div>
        @else
            <div class="requests-grid">
                @foreach($cardRequests as $request)
                    <div class="request-card">
                        <div class="card-header">
                            <div class="user-info">
                                <h3 class="user-name">{{ $request->user->name }}</h3>
                                <p class="user-email">{{ $request->user->email }}</p>
                            </div>
                            <div class="status-badge pending">
                                Pending
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="info-row">
                                <span class="label">User ID:</span>
                                <span class="value">#{{ $request->user->id }}</span>
                            </div>

                            @if($request->user->phone)
                                <div class="info-row">
                                    <span class="label">Phone:</span>
                                    <span class="value">{{ $request->user->phone }}</span>
                                </div>
                            @endif

                            <div class="info-row">
                                <span class="label">Card Amount:</span>
                                <span class="value amount">${{ number_format($request->amount, 2) }}</span>
                            </div>

                            <div class="info-row">
                                <span class="label">Requested:</span>
                                <span class="value">{{ $request->created_at->format('F d, Y \a\t g:i A') }}</span>
                            </div>

                            <div class="id-image-section">
                                <label class="id-label">Government-Issued ID:</label>
                                <div class="id-image-preview">
                                    @if($request->id_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($request->id_image))
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($request->id_image) }}" 
                                             alt="User ID" 
                                             class="id-image-thumbnail"
                                             onclick="openImageModal(this.src)">
                                        <small class="preview-hint">Click to view full image</small>
                                    @else
                                        <div class="image-not-found">
                                            <p>Image not found or deleted</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="card-actions">
                            <a href="{{ route('admin.card-requests.show', $request->id) }}" class="btn btn-info">
                                View Details
                            </a>
                            <form method="POST" action="{{ route('admin.card-requests.approve', $request->id) }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to approve this request?')">
                                    ✓ Approve
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.card-requests.reject', $request->id) }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to reject this request?')">
                                    ✕ Reject
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($cardRequests->hasPages())
                <div class="pagination-wrapper">
                    {{ $cardRequests->links() }}
                </div>
            @endif
        @endif
    </div>
</section>

<!-- Modal for viewing full image -->
<div id="imageModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="modal-close" onclick="closeImageModal()">&times;</span>
        <img id="modalImage" src="" alt="Full ID Image" class="modal-image">
    </div>
</div>

<style>
    .admin-page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 40px 20px;
        text-align: center;
    }

    .admin-page-header h1 {
        font-size: 32px;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .admin-page-header p {
        font-size: 16px;
        opacity: 0.9;
    }

    .admin-content {
        padding: 30px 0;
    }

    .requests-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #f0f0f0;
    }

    .requests-header h2 {
        font-size: 24px;
        font-weight: 600;
        margin: 0;
        color: #333;
    }

    .filter-info {
        font-size: 14px;
        color: #666;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .empty-icon {
        font-size: 48px;
        margin-bottom: 15px;
    }

    .empty-state h3 {
        font-size: 20px;
        font-weight: 600;
        color: #333;
        margin-bottom: 10px;
    }

    .empty-state p {
        color: #666;
        margin: 0;
    }

    .requests-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 25px;
        margin-bottom: 40px;
    }

    .request-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: box-shadow 0.3s ease;
    }

    .request-card:hover {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
    }

    .card-header {
        padding: 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .user-info {
        flex: 1;
    }

    .user-name {
        font-size: 18px;
        font-weight: 600;
        margin: 0 0 5px 0;
    }

    .user-email {
        font-size: 13px;
        opacity: 0.9;
        margin: 0;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .status-badge.pending {
        background-color: rgba(255, 255, 255, 0.3);
        color: white;
    }

    .card-body {
        padding: 20px;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #f0f0f0;
        font-size: 14px;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-row .label {
        font-weight: 600;
        color: #666;
    }

    .info-row .value {
        color: #333;
    }

    .info-row .amount {
        color: #667eea;
        font-weight: 600;
        font-size: 16px;
    }

    .id-image-section {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 2px solid #f0f0f0;
    }

    .id-label {
        display: block;
        font-weight: 600;
        color: #333;
        font-size: 14px;
        margin-bottom: 10px;
    }

    .id-image-preview {
        text-align: center;
    }

    .id-image-thumbnail {
        max-width: 100%;
        max-height: 200px;
        border-radius: 5px;
        border: 1px solid #ddd;
        cursor: pointer;
        transition: transform 0.3s ease;
    }

    .id-image-thumbnail:hover {
        transform: scale(1.02);
    }

    .preview-hint {
        display: block;
        color: #667eea;
        font-size: 12px;
        margin-top: 8px;
    }

    .image-not-found {
        background-color: #f0f0f0;
        padding: 30px;
        border-radius: 5px;
        color: #666;
    }

    .card-actions {
        padding: 20px;
        background-color: #f8f9fa;
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn {
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        text-align: center;
        transition: all 0.3s ease;
        flex: 1;
        min-width: 100px;
    }

    .btn-info {
        background-color: #3498db;
        color: white;
    }

    .btn-info:hover {
        background-color: #2980b9;
    }

    .btn-success {
        background-color: #27ae60;
        color: white;
    }

    .btn-success:hover {
        background-color: #229954;
    }

    .btn-danger {
        background-color: #e74c3c;
        color: white;
    }

    .btn-danger:hover {
        background-color: #c0392b;
    }

    .modal {
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        max-width: 90%;
        max-height: 90vh;
        position: relative;
        overflow: auto;
    }

    .modal-close {
        position: absolute;
        right: 20px;
        top: 20px;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
        color: #666;
    }

    .modal-close:hover {
        color: #000;
    }

    .modal-image {
        max-width: 100%;
        max-height: 80vh;
        border-radius: 5px;
    }

    .pagination-wrapper {
        display: flex;
        justify-content: center;
        margin-top: 40px;
    }

    .alert {
        padding: 15px 20px;
        border-radius: 5px;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .alert-success {
        background-color: #d4edda;
        border: 1px solid #c3e6cb;
        color: #155724;
    }

    .alert-error {
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
    }

    @media (max-width: 768px) {
        .requests-grid {
            grid-template-columns: 1fr;
        }

        .requests-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .card-actions {
            flex-direction: column;
        }

        .btn {
            min-width: auto;
        }
    }
</style>

<script>
    function openImageModal(src) {
        document.getElementById('imageModal').style.display = 'flex';
        document.getElementById('modalImage').src = src;
    }

    function closeImageModal() {
        document.getElementById('imageModal').style.display = 'none';
    }

    // Close modal when clicking outside of it
    window.onclick = function(event) {
        const modal = document.getElementById('imageModal');
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
</script>

@include('includes.footer')
