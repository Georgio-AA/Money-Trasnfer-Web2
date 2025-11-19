<?php echo $__env->make('includes.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<section class="page-header">
    <h1>Bank Account Details</h1>
    <p>View your bank account information</p>
</section>

<section class="account-details">
    <div class="container">
        <div class="details-container">
            <div class="account-card">
                <div class="card-header">
                    <h2><?php echo e($bankAccount->bank_name); ?></h2>
                    <div class="verification-status">
                        <?php if($bankAccount->is_verified): ?>
                            <span class="status verified">✓ Verified</span>
                        <?php else: ?>
                            <span class="status unverified">⚠ Unverified</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="account-info">
                    <div class="info-row">
                        <label>Bank Name:</label>
                        <span><?php echo e($bankAccount->bank_name); ?></span>
                    </div>
                    
                    <div class="info-row">
                        <label>Account Number:</label>
                        <span>****<?php echo e(substr($bankAccount->account_number, -4)); ?></span>
                    </div>
                    
                    <div class="info-row">
                        <label>Account Type:</label>
                        <span><?php echo e(ucfirst($bankAccount->account_type) ?? 'Not specified'); ?></span>
                    </div>
                    
                    <div class="info-row">
                        <label>Currency:</label>
                        <span><?php echo e($bankAccount->currency); ?></span>
                    </div>
                    
                    <div class="info-row">
                        <label>Date Added:</label>
                        <span><?php echo e($bankAccount->created_at->format('F j, Y \a\t g:i A')); ?></span>
                    </div>
                    
                    <?php if($bankAccount->updated_at != $bankAccount->created_at): ?>
                        <div class="info-row">
                            <label>Last Updated:</label>
                            <span><?php echo e($bankAccount->updated_at->format('F j, Y \a\t g:i A')); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <div class="info-row">
                        <label>Status:</label>
                        <span>
                            <?php if($bankAccount->is_verified): ?>
                                <span class="text-success">✓ Verified and ready for transfers</span>
                            <?php else: ?>
                                <span class="text-warning">⚠ Verification required for transfers</span>
                            <?php endif; ?>
                        </span>
                    </div>
                </div>

                <?php if(!$bankAccount->is_verified): ?>
                    <div class="verification-prompt">
                        <div class="prompt-content">
                            <h4>Verify this account to unlock:</h4>
                            <ul>
                                <li>✓ Faster money transfers</li>
                                <li>✓ Higher transfer limits</li>
                                <li>✓ Lower fees</li>
                                <li>✓ Enhanced security</li>
                            </ul>
                            <a href="<?php echo e(route('bank-accounts.verify-form', $bankAccount)); ?>" class="btn btn-primary">Verify Now</a>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="actions">
                    <a href="<?php echo e(route('bank-accounts.edit', $bankAccount)); ?>" class="btn btn-secondary">Edit Account</a>
                    <?php if(!$bankAccount->is_verified): ?>
                        <a href="<?php echo e(route('bank-accounts.verify-form', $bankAccount)); ?>" class="btn btn-primary">Verify Account</a>
                    <?php endif; ?>
                    <form action="<?php echo e(route('bank-accounts.destroy', $bankAccount)); ?>" method="POST" style="display:inline;" 
                          onsubmit="return confirm('Are you sure you want to delete this bank account? This action cannot be undone.');">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-danger">Delete Account</button>
                    </form>
                </div>
            </div>

            <?php if($bankAccount->is_verified): ?>
                <div class="security-info">
                    <h3>🔒 Security Information</h3>
                    <div class="security-details">
                        <div class="security-item">
                            <strong>Encryption:</strong> Your account information is encrypted using AES-256 encryption.
                        </div>
                        <div class="security-item">
                            <strong>Access Control:</strong> Only you can view and modify this account information.
                        </div>
                        <div class="security-item">
                            <strong>Monitoring:</strong> All account activities are monitored for suspicious behavior.
                        </div>
                        <div class="security-item">
                            <strong>Compliance:</strong> We comply with PCI DSS and banking security standards.
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="back-action">
            <a href="<?php echo e(route('bank-accounts.index')); ?>" class="btn btn-outline">← Back to All Accounts</a>
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

.account-details {
    padding: 2rem;
    background-color: #f9fafb;
    min-height: 70vh;
}

.container {
    max-width: 800px;
    margin: 0 auto;
}

.details-container {
    display: grid;
    gap: 1.5rem;
}

.account-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.card-header {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    padding: 1.5rem;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-header h2 {
    margin: 0;
    color: #1f2937;
}

.status {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: bold;
}

.status.verified {
    background-color: #d1fae5;
    color: #065f46;
}

.status.unverified {
    background-color: #fef3c7;
    color: #92400e;
}

.account-info {
    padding: 1.5rem;
}

.info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f3f4f6;
}

.info-row:last-child {
    border-bottom: none;
}

.info-row label {
    font-weight: 600;
    color: #374151;
    flex: 1;
}

.info-row span {
    color: #6b7280;
    flex: 2;
    text-align: right;
}

.text-success {
    color: #059669 !important;
}

.text-warning {
    color: #d97706 !important;
}

.verification-prompt {
    background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
    border-top: 1px solid #e5e7eb;
    padding: 1.5rem;
}

.prompt-content h4 {
    margin: 0 0 1rem 0;
    color: #0369a1;
}

.prompt-content ul {
    margin: 0 0 1.5rem 0;
    padding-left: 1.5rem;
    color: #075985;
}

.prompt-content li {
    margin-bottom: 0.5rem;
}

.actions {
    padding: 1.5rem;
    border-top: 1px solid #e5e7eb;
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.security-info {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 1.5rem;
}

.security-info h3 {
    margin: 0 0 1rem 0;
    color: #1f2937;
}

.security-details {
    display: grid;
    gap: 1rem;
}

.security-item {
    padding: 1rem;
    background-color: #f8fafc;
    border-radius: 8px;
    border-left: 4px solid #3b82f6;
}

.security-item strong {
    color: #1f2937;
    display: block;
    margin-bottom: 0.25rem;
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
    text-align: center;
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

.btn-outline {
    background-color: transparent;
    color: #3b82f6;
    border: 2px solid #3b82f6;
}

.btn-outline:hover {
    background-color: #3b82f6;
    color: white;
}

.back-action {
    margin-top: 1.5rem;
    text-align: center;
}

@media (max-width: 768px) {
    .card-header {
        flex-direction: column;
        align-items: stretch;
        gap: 1rem;
    }
    
    .info-row {
        flex-direction: column;
        align-items: stretch;
        gap: 0.25rem;
    }
    
    .info-row span {
        text-align: left;
    }
    
    .actions {
        flex-direction: column;
    }
    
    .btn {
        width: 100%;
    }
}
</style>

<?php echo $__env->make('includes.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\money-transfer\resources\views/bank-accounts/show.blade.php ENDPATH**/ ?>