<?php echo $__env->make('includes.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<section class="page-header">
    <h1>My Bank Accounts</h1>
    <p>Manage your bank accounts and payment methods</p>
</section>

<section class="bank-accounts">
    <div class="container">
        <div class="account-header">
            <h2>Your Bank Accounts</h2>
            <a href="<?php echo e(route('bank-accounts.create')); ?>" class="btn btn-primary">Add New Account</a>
        </div>

        <?php if(session('success')): ?>
            <div class="alert alert-success">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="alert alert-error">
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        <?php if(session('info')): ?>
            <div class="alert alert-info">
                <?php echo e(session('info')); ?>

            </div>
        <?php endif; ?>

        <?php if($bankAccounts->count() > 0): ?>
            <div class="accounts-grid">
                <?php $__currentLoopData = $bankAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="account-card">
                        <div class="account-header-card">
                            <h3><?php echo e($account->bank_name); ?></h3>
                            <div class="account-status">
                                <?php if($account->is_verified): ?>
                                    <span class="status verified">✓ Verified</span>
                                <?php else: ?>
                                    <span class="status unverified">⚠ Unverified</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="account-details">
                            <p><strong>Account Number:</strong> ****<?php echo e(substr($account->account_number, -4)); ?></p>
                            <p><strong>Account Type:</strong> <?php echo e(ucfirst($account->account_type) ?? 'Not specified'); ?></p>
                            <p><strong>Currency:</strong> <?php echo e($account->currency); ?></p>
                            <p><strong>Balance:</strong> <span class="account-balance"><?php echo e($account->currency); ?> <?php echo e(number_format($account->balance, 2)); ?></span></p>
                            <p><strong>Added:</strong> <?php echo e($account->created_at->format('M d, Y')); ?></p>
                        </div>

                        <div class="account-actions">
                            <a href="<?php echo e(route('bank-accounts.show', $account)); ?>" class="btn btn-secondary">View</a>
                            <a href="<?php echo e(route('bank-accounts.edit', $account)); ?>" class="btn btn-secondary">Edit</a>
                            
                            <?php if(!$account->is_verified): ?>
                                <a href="<?php echo e(route('bank-accounts.verify-form', $account)); ?>" class="btn btn-primary">Verify</a>
                                <form action="<?php echo e(route('bank-accounts.send-verification-email', $account)); ?>" method="POST" style="display:inline;">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-secondary">Resend Email</button>
                                </form>
                            <?php endif; ?>
                            
                            <form action="<?php echo e(route('bank-accounts.destroy', $account)); ?>" method="POST" style="display:inline;" 
                                  onsubmit="return confirm('Are you sure you want to delete this bank account?');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon">🏦</div>
                <h3>No Bank Accounts Found</h3>
                <p>You haven't added any bank accounts yet. Add your first bank account to start sending money.</p>
                <a href="<?php echo e(route('bank-accounts.create')); ?>" class="btn btn-primary">Add Your First Account</a>
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

.bank-accounts {
    padding: 2rem;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
}

.account-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.accounts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 1.5rem;
}

.account-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 1.5rem;
    border: 1px solid #e5e7eb;
    transition: transform 0.2s, box-shadow 0.2s;
}

.account-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.account-header-card {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.account-header-card h3 {
    margin: 0;
    color: #1f2937;
}

.status {
    padding: 0.25rem 0.75rem;
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

.status.primary {
    background-color: #fef3c7;
    color: #92400e;
}

.account-details {
    margin-bottom: 1.5rem;
}

.account-details p {
    margin: 0.5rem 0;
    color: #6b7280;
}

.account-balance {
    color: #059669;
    font-weight: 700;
    font-size: 1.1rem;
}

.account-actions {
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

.btn-primary-action {
    background-color: #f59e0b;
    color: white;
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.875rem;
    font-weight: 500;
    text-decoration: none;
    display: inline-block;
}

.btn-primary-action:hover {
    background-color: #d97706;
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

.empty-state {
    text-align: center;
    padding: 3rem;
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

@media (max-width: 768px) {
    .account-header {
        flex-direction: column;
        align-items: stretch;
        gap: 1rem;
    }
    
    .accounts-grid {
        grid-template-columns: 1fr;
    }
    
    .account-actions {
        justify-content: space-between;
    }
    
    .btn {
        flex: 1;
        text-align: center;
    }
}
</style>

<?php echo $__env->make('includes.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\money-transfer\WebProject\resources\views/bank-accounts/index.blade.php ENDPATH**/ ?>