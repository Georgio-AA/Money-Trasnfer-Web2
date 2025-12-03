<?php echo $__env->make('includes.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<section class="admin-page-header">
    <h1>Card Request Details</h1>
    <p>Review request from <?php echo e($cardRequest->user->name); ?></p>
</section>

<section class="admin-content">
    <div class="container">
        <div class="detail-wrapper">
            <!-- User Information -->
            <div class="detail-card">
                <h2 class="card-title">User Information</h2>
                
                <div class="detail-section">
                    <div class="detail-row">
                        <span class="label">Full Name:</span>
                        <span class="value"><?php echo e($cardRequest->user->name); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Email:</span>
                        <span class="value email"><?php echo e($cardRequest->user->email); ?></span>
                    </div>
                    <?php if($cardRequest->user->phone): ?>
                        <div class="detail-row">
                            <span class="label">Phone:</span>
                            <span class="value"><?php echo e($cardRequest->user->phone); ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="detail-row">
                        <span class="label">User ID:</span>
                        <span class="value">#<?php echo e($cardRequest->user->id); ?></span>
                    </div>
                </div>
            </div>

            <!-- Card Request Details -->
            <div class="detail-card">
                <h2 class="card-title">Card Request Details</h2>
                
                <div class="detail-section">
                    <div class="detail-row">
                        <span class="label">Request ID:</span>
                        <span class="value">#<?php echo e($cardRequest->id); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Requested Card Amount:</span>
                        <span class="value amount">$<?php echo e(number_format($cardRequest->amount, 2)); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Status:</span>
                        <span class="value">
                            <span class="status-badge pending"><?php echo e(ucfirst($cardRequest->status)); ?></span>
                        </span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Submitted:</span>
                        <span class="value"><?php echo e($cardRequest->created_at->format('F d, Y \a\t g:i A')); ?></span>
                    </div>
                </div>
            </div>

            <!-- ID Image -->
            <div class="detail-card">
                <h2 class="card-title">Government-Issued ID</h2>
                
                <div class="detail-section">
                    <?php if($cardRequest->id_image): ?>
                        <?php if(\Illuminate\Support\Facades\Storage::disk('public')->exists($cardRequest->id_image)): ?>
                            <div class="id-image-container">
                                <img src="<?php echo e(\Illuminate\Support\Facades\Storage::url($cardRequest->id_image)); ?>" 
                                     alt="User ID" 
                                     class="id-image-full"
                                     onclick="openImageModal(this.src)">
                                <p class="image-hint">Click image to view at full size</p>
                            </div>
                        <?php else: ?>
                            <div class="image-not-found">
                                <p>⚠️ Image file not found in storage</p>
                                <p style="font-size: 12px; margin-top: 10px; color: #999;">
                                    Path: <?php echo e($cardRequest->id_image); ?>

                                </p>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="image-not-found">
                            <p>No ID image uploaded</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-card">
                <h2 class="card-title">Review Decision</h2>
                
                <div class="action-buttons">
                    <form method="POST" action="<?php echo e(route('admin.card-requests.approve', $cardRequest->id)); ?>" style="display: inline;">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-approve" onclick="return confirm('Are you sure you want to approve this card request?')">
                            ✓ Approve Card Request
                        </button>
                    </form>
                    <form method="POST" action="<?php echo e(route('admin.card-requests.reject', $cardRequest->id)); ?>" style="display: inline;">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-reject" onclick="return confirm('Are you sure you want to reject this card request?')">
                            ✕ Reject Card Request
                        </button>
                    </form>
                </div>

                <a href="<?php echo e(route('admin.card-requests.index')); ?>" class="btn btn-back">
                    ← Back to Requests
                </a>
            </div>
        </div>
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

    .detail-wrapper {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 25px;
        margin-bottom: 30px;
    }

    .detail-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        padding: 25px;
    }

    .action-card {
        grid-column: 1 / -1;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        padding: 25px;
    }

    .card-title {
        font-size: 20px;
        font-weight: 600;
        color: #333;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }

    .detail-section {
        display: flex;
        flex-direction: column;
        gap: 0;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 15px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .detail-row:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .detail-row .label {
        font-weight: 600;
        color: #666;
        font-size: 14px;
    }

    .detail-row .value {
        color: #333;
        font-size: 14px;
    }

    .detail-row .amount {
        color: #667eea;
        font-weight: 600;
        font-size: 18px;
    }

    .detail-row .email {
        word-break: break-all;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-badge.pending {
        background-color: #fff3cd;
        color: #856404;
    }

    .id-image-container {
        text-align: center;
    }

    .id-image-full {
        max-width: 100%;
        max-height: 500px;
        border-radius: 5px;
        border: 1px solid #ddd;
        cursor: pointer;
        transition: transform 0.3s ease;
    }

    .id-image-full:hover {
        transform: scale(1.02);
    }

    .image-hint {
        color: #667eea;
        font-size: 12px;
        margin-top: 10px;
    }

    .image-not-found {
        background-color: #f0f0f0;
        padding: 60px 30px;
        border-radius: 5px;
        text-align: center;
        color: #666;
    }

    .action-buttons {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
    }

    .btn {
        padding: 15px 30px;
        border: none;
        border-radius: 5px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        text-align: center;
        transition: all 0.3s ease;
        flex: 1;
    }

    .btn-approve {
        background-color: #27ae60;
        color: white;
    }

    .btn-approve:hover {
        background-color: #229954;
    }

    .btn-reject {
        background-color: #e74c3c;
        color: white;
    }

    .btn-reject:hover {
        background-color: #c0392b;
    }

    .btn-back {
        background-color: #95a5a6;
        color: white;
        display: inline-block;
        width: auto;
        margin-top: 15px;
    }

    .btn-back:hover {
        background-color: #7f8c8d;
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

    @media (max-width: 768px) {
        .detail-wrapper {
            grid-template-columns: 1fr;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn {
            width: 100%;
        }

        .detail-row {
            flex-direction: column;
            gap: 5px;
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

<?php echo $__env->make('includes.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php /**PATH C:\XAMPP\htdocs\money-transfer2\WebProject\resources\views/admin/card_requests/show.blade.php ENDPATH**/ ?>