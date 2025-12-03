<?php echo $__env->make('includes.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<section class="page-header">
    <h1>My Reviews</h1>
    <p>View and manage your service reviews</p>
</section>

<section class="reviews-section">
    <div class="container">
        <div class="reviews-header">
            <h2>Your Reviews</h2>
            <a href="<?php echo e(route('reviews.create')); ?>" class="btn btn-primary">Write a Review</a>
        </div>

        <?php if(session('success')): ?>
            <div class="alert alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="alert alert-error"><?php echo e(session('error')); ?></div>
        <?php endif; ?>

        <?php if(session('info')): ?>
            <div class="alert alert-info"><?php echo e(session('info')); ?></div>
        <?php endif; ?>

        <?php if($reviews->count() > 0): ?>
            <div class="reviews-grid">
                <?php $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="review-card">
                        <div class="review-header">
                            <div class="rating">
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <span class="star <?php echo e($i <= $review->rating ? 'filled' : ''); ?>">★</span>
                                <?php endfor; ?>
                            </div>
                            <span class="review-date"><?php echo e($review->created_at->format('M d, Y')); ?></span>
                        </div>

                        <?php if($review->transfer): ?>
                            <div class="review-subject">
                                <strong>Transfer Review</strong>
                                <p class="review-meta">Transfer #<?php echo e($review->transfer->id); ?> - <?php echo e($review->transfer->source_currency); ?> <?php echo e(number_format($review->transfer->amount, 2)); ?></p>
                            </div>
                        <?php elseif($review->agent): ?>
                            <div class="review-subject">
                                <strong>Agent Review</strong>
                                <p class="review-meta"><?php echo e($review->agent->business_name); ?></p>
                            </div>
                        <?php endif; ?>

                        <p class="review-comment"><?php echo e($review->comment); ?></p>

                        <div class="review-actions">
                            <a href="<?php echo e(route('reviews.show', $review->id)); ?>" class="btn btn-secondary btn-sm">View</a>
                            <a href="<?php echo e(route('reviews.edit', $review->id)); ?>" class="btn btn-secondary btn-sm">Edit</a>
                            <form action="<?php echo e(route('reviews.destroy', $review->id)); ?>" method="POST" style="display:inline;" 
                                  onsubmit="return confirm('Are you sure you want to delete this review?');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div class="pagination-wrapper">
                <?php echo e($reviews->links()); ?>

            </div>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon">⭐</div>
                <h3>No Reviews Yet</h3>
                <p>You haven't written any reviews. Share your experience with our services!</p>
                <a href="<?php echo e(route('reviews.create')); ?>" class="btn btn-primary">Write Your First Review</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
.page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 3rem 2rem;
    text-align: center;
}

.reviews-section {
    padding: 2rem;
    background: #f9fafb;
    min-height: 70vh;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
}

.reviews-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.reviews-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.review-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s;
}

.review-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.rating {
    display: flex;
    gap: 0.25rem;
}

.star {
    font-size: 1.5rem;
    color: #d1d5db;
}

.star.filled {
    color: #fbbf24;
}

.review-date {
    color: #6b7280;
    font-size: 0.875rem;
}

.review-subject {
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e5e7eb;
}

.review-subject strong {
    color: #1f2937;
    font-size: 1.1rem;
}

.review-meta {
    margin: 0.25rem 0 0 0;
    color: #6b7280;
    font-size: 0.875rem;
}

.review-comment {
    color: #374151;
    line-height: 1.6;
    margin-bottom: 1rem;
}

.review-actions {
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

.btn-danger {
    background-color: #ef4444;
    color: white;
}

.btn-danger:hover {
    background-color: #dc2626;
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

.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 2rem;
}

@media (max-width: 768px) {
    .reviews-header {
        flex-direction: column;
        gap: 1rem;
    }

    .reviews-grid {
        grid-template-columns: 1fr;
    }

    .review-actions {
        justify-content: space-between;
    }
}
</style>

<?php echo $__env->make('includes.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php /**PATH C:\XAMPP\htdocs\money-transfer2\WebProject\resources\views/reviews/index.blade.php ENDPATH**/ ?>