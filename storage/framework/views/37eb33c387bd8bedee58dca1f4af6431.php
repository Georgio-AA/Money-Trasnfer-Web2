<?php echo $__env->make('includes.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<section class="page-header">
    <h1>Edit Review</h1>
    <p>Update your review</p>
</section>

<section class="form-section">
    <div class="container">
        <?php if(session('error')): ?>
            <div class="alert alert-error"><?php echo e(session('error')); ?></div>
        <?php endif; ?>

        <form action="<?php echo e(route('reviews.update', $review->id)); ?>" method="POST" class="review-form">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <?php if($review->transfer): ?>
                <div class="transfer-info-card">
                    <h3>Transfer Information</h3>
                    <div class="transfer-details">
                        <p><strong>Transfer ID:</strong> #<?php echo e($review->transfer->id); ?></p>
                        <p><strong>Amount:</strong> <?php echo e($review->transfer->source_currency); ?> <?php echo e(number_format($review->transfer->amount, 2)); ?></p>
                        <p><strong>Date:</strong> <?php echo e($review->transfer->created_at->format('M d, Y')); ?></p>
                        <p><strong>Status:</strong> <span class="status-completed"><?php echo e(ucfirst($review->transfer->status)); ?></span></p>
                    </div>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <label for="rating">Rating *</label>
                <div class="star-rating">
                    <?php for($i = 5; $i >= 1; $i--): ?>
                        <input type="radio" name="rating" id="star<?php echo e($i); ?>" value="<?php echo e($i); ?>" <?php echo e(old('rating', $review->rating) == $i ? 'checked' : ''); ?> required>
                        <label for="star<?php echo e($i); ?>" class="star-label" title="<?php echo e($i); ?> stars">★</label>
                    <?php endfor; ?>
                </div>
                <?php $__errorArgs = ['rating'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="error"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label for="comment">Your Review *</label>
                <textarea name="comment" id="comment" rows="6" required placeholder="Tell us about your experience... (minimum 10 characters)"><?php echo e(old('comment', $review->comment)); ?></textarea>
                <small class="hint">Minimum 10 characters, maximum 1000 characters</small>
                <?php $__errorArgs = ['comment'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="error"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-actions">
                <a href="<?php echo e(route('reviews.show', $review->id)); ?>" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Review</button>
            </div>
        </form>
    </div>
</section>

<style>
.page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 3rem 2rem;
    text-align: center;
}

.form-section {
    padding: 2rem;
    background: #f9fafb;
    min-height: 70vh;
}

.container {
    max-width: 700px;
    margin: 0 auto;
}

.review-form {
    background: white;
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.transfer-info-card {
    background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
    padding: 1.5rem;
    border-radius: 8px;
    margin-bottom: 2rem;
}

.transfer-info-card h3 {
    margin: 0 0 1rem 0;
    color: #1f2937;
}

.transfer-details p {
    margin: 0.5rem 0;
    color: #374151;
}

.status-completed {
    color: #059669;
    font-weight: 600;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #374151;
}

.form-group textarea {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 1rem;
    font-family: inherit;
}

.form-group textarea:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.star-rating {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
    gap: 0.5rem;
}

.star-rating input[type="radio"] {
    display: none;
}

.star-label {
    font-size: 2.5rem;
    color: #d1d5db;
    cursor: pointer;
    transition: color 0.2s;
}

.star-rating input[type="radio"]:checked ~ .star-label,
.star-rating input[type="radio"]:checked + .star-label {
    color: #fbbf24;
}

.star-rating .star-label:hover,
.star-rating .star-label:hover ~ .star-label {
    color: #fbbf24;
}

.hint {
    display: block;
    margin-top: 0.25rem;
    font-size: 0.875rem;
    color: #6b7280;
}

.error {
    display: block;
    margin-top: 0.25rem;
    font-size: 0.875rem;
    color: #dc2626;
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
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

.alert {
    padding: 1rem;
    border-radius: 6px;
    margin-bottom: 1rem;
}

.alert-error {
    background-color: #fee2e2;
    color: #991b1b;
    border: 1px solid #fca5a5;
}

@media (max-width: 768px) {
    .form-actions {
        flex-direction: column;
    }

    .btn {
        width: 100%;
        text-align: center;
    }
}
</style>

<?php echo $__env->make('includes.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php /**PATH C:\xampp\htdocs\money-transfer\WebProject\resources\views/reviews/edit.blade.php ENDPATH**/ ?>