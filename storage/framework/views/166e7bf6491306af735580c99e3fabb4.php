<?php echo $__env->make('includes.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<section class="page-header">
    <h1>Review Details</h1>
    <p>View your review</p>
</section>

<section class="review-details-section">
    <div class="container">
        <div class="review-card">
            <div class="review-header">
                <div class="rating">
                    <?php for($i = 1; $i <= 5; $i++): ?>
                        <span class="star <?php echo e($i <= $review->rating ? 'filled' : ''); ?>">★</span>
                    <?php endfor; ?>
                    <span class="rating-text"><?php echo e($review->rating); ?> out of 5</span>
                </div>
                <span class="review-date"><?php echo e($review->created_at->format('M d, Y - h:i A')); ?></span>
            </div>

            <?php if($review->transfer): ?>
                <div class="transfer-info">
                    <h3>Transfer Information</h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="label">Transfer ID:</span>
                            <span class="value">#<?php echo e($review->transfer->id); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="label">Amount:</span>
                            <span class="value"><?php echo e($review->transfer->source_currency); ?> <?php echo e(number_format($review->transfer->amount, 2)); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="label">Recipient:</span>
                            <span class="value"><?php echo e($review->transfer->beneficiary->full_name ?? 'N/A'); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="label">Date:</span>
                            <span class="value"><?php echo e($review->transfer->created_at->format('M d, Y')); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="label">Status:</span>
                            <span class="value"><span class="status-badge"><?php echo e(ucfirst($review->transfer->status)); ?></span></span>
                        </div>
                    </div>
                </div>
            <?php elseif($review->agent): ?>
                <div class="agent-info">
                    <h3>Agent Information</h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="label">Business Name:</span>
                            <span class="value"><?php echo e($review->agent->business_name); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="label">Location:</span>
                            <span class="value"><?php echo e($review->agent->address ?? 'N/A'); ?></span>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="review-content">
                <h3>Your Review</h3>
                <p class="review-comment"><?php echo e($review->comment); ?></p>
            </div>

            <?php if($review->updated_at != $review->created_at): ?>
                <div class="review-meta">
                    <small>Last updated: <?php echo e($review->updated_at->format('M d, Y - h:i A')); ?></small>
                </div>
            <?php endif; ?>

            <div class="review-actions">
                <a href="<?php echo e(route('reviews.index')); ?>" class="btn btn-secondary">Back to Reviews</a>
                <a href="<?php echo e(route('reviews.edit', $review->id)); ?>" class="btn btn-primary">Edit Review</a>
                <form action="<?php echo e(route('reviews.destroy', $review->id)); ?>" method="POST" style="display:inline;" 
                      onsubmit="return confirm('Are you sure you want to delete this review?');">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger">Delete Review</button>
                </form>
            </div>
        </div>
    </div>
</section>

<style>
.page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 3rem 2rem;
    text-align: center;
}

.review-details-section {
    padding: 2rem;
    background: #f9fafb;
    min-height: 70vh;
}

.container {
    max-width: 800px;
    margin: 0 auto;
}

.review-card {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 1.5rem;
    margin-bottom: 1.5rem;
    border-bottom: 2px solid #e5e7eb;
}

.rating {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.star {
    font-size: 2rem;
    color: #d1d5db;
}

.star.filled {
    color: #fbbf24;
}

.rating-text {
    margin-left: 0.5rem;
    color: #6b7280;
    font-weight: 600;
    font-size: 1.1rem;
}

.review-date {
    color: #6b7280;
    font-size: 0.875rem;
}

.transfer-info,
.agent-info {
    background: #f9fafb;
    padding: 1.5rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
}

.transfer-info h3,
.agent-info h3,
.review-content h3 {
    margin: 0 0 1rem 0;
    color: #1f2937;
    font-size: 1.125rem;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.info-item .label {
    color: #6b7280;
    font-size: 0.875rem;
    font-weight: 500;
}

.info-item .value {
    color: #1f2937;
    font-weight: 600;
}

.status-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 999px;
    font-size: 0.875rem;
    font-weight: 600;
    background: #d1fae5;
    color: #065f46;
}

.review-content {
    margin-bottom: 1.5rem;
    padding: 1.5rem;
    background: #f9fafb;
    border-radius: 8px;
}

.review-comment {
    color: #374151;
    line-height: 1.8;
    font-size: 1.05rem;
    margin: 0;
}

.review-meta {
    text-align: right;
    margin-bottom: 1.5rem;
    padding-top: 1rem;
    border-top: 1px solid #e5e7eb;
}

.review-meta small {
    color: #9ca3af;
    font-size: 0.875rem;
}

.review-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    padding-top: 1.5rem;
    border-top: 2px solid #e5e7eb;
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

.btn-danger {
    background-color: #ef4444;
    color: white;
}

.btn-danger:hover {
    background-color: #dc2626;
}

@media (max-width: 768px) {
    .review-header {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }

    .review-actions {
        flex-direction: column;
    }

    .btn {
        width: 100%;
        text-align: center;
    }
}
</style>

<?php echo $__env->make('includes.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php /**PATH C:\xampp\htdocs\money-transfer\WebProject\resources\views/reviews/show.blade.php ENDPATH**/ ?>