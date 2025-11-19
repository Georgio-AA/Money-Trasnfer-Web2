<?php echo $__env->make('includes.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<section class="page-header">
    <h1>Verify Bank Account</h1>
    <p>Email verification for <?php echo e($bankAccount->bank_name); ?></p>
</section>

<section class="verification">
    <div class="container">
        <div class="account-info">
            <h3>Account Details</h3>
            <div class="account-card">
                <p><strong>Bank:</strong> <?php echo e($bankAccount->bank_name); ?></p>
                <p><strong>Account:</strong> ****<?php echo e(substr($bankAccount->account_number, -4)); ?></p>
                <p><strong>Currency:</strong> <?php echo e($bankAccount->currency); ?></p>
                <p><strong>Type:</strong> <?php echo e(ucfirst($bankAccount->account_type) ?? 'Not specified'); ?></p>
            </div>
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

        <div class="verification-methods">
            <h3>Email Verification</h3>

            <div class="method-card">
                <div class="method-header">
                    <h4>📧 Verify via Email Link</h4>
                    <p>We’ll send a secure verification link to your email. Click the link to verify this bank account.</p>
                </div>

                <div class="status-info" style="background:#f0f9ff;border:1px solid #bae6fd;border-radius:8px;padding:1rem;margin-bottom:1rem;">
                    <?php if($bankAccount->verification_sent_at): ?>
                        <p><strong>Last sent:</strong> <?php echo e($bankAccount->verification_sent_at->format('M d, Y \a\t g:i A')); ?></p>
                    <?php else: ?>
                        <p>No verification email sent yet.</p>
                    <?php endif; ?>
                    <?php if($bankAccount->verification_expires_at): ?>
                        <p><strong>Link expires:</strong> <?php echo e($bankAccount->verification_expires_at->format('M d, Y \a\t g:i A')); ?></p>
                    <?php endif; ?>
                </div>

                <form action="<?php echo e(route('bank-accounts.send-verification-email', $bankAccount)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-primary btn-full">
                        <?php echo e($bankAccount->verification_sent_at ? 'Resend Verification Email' : 'Send Verification Email'); ?>

                    </button>
                </form>
            </div>
        </div>

        <div class="back-action">
            <a href="<?php echo e(route('bank-accounts.index')); ?>" class="btn btn-secondary">Back to Bank Accounts</a>
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

.verification {
    padding: 2rem;
    background-color: #f9fafb;
    min-height: 70vh;
}

.container {
    max-width: 800px;
    margin: 0 auto;
}

.account-info {
    margin-bottom: 2rem;
}

.account-card {
    background: white;
    padding: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.account-card p {
    margin: 0.5rem 0;
    color: #374151;
}

.verification-methods {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.verification-methods h3 {
    padding: 1.5rem 1.5rem 0;
    margin: 0 0 1rem 0;
    color: #1f2937;
}

.method-tabs {
    display: flex;
    border-bottom: 1px solid #e5e7eb;
}

.tab-btn {
    flex: 1;
    padding: 1rem;
    background: none;
    border: none;
    cursor: pointer;
    font-weight: 500;
    color: #6b7280;
    transition: all 0.2s;
}

.tab-btn.active {
    background-color: #3b82f6;
    color: white;
}

.tab-btn:hover:not(.active) {
    background-color: #f3f4f6;
}

.tab-content {
    display: none;
    padding: 1.5rem;
}

.tab-content.active {
    display: block;
}

.method-card {
    max-width: 100%;
}

.method-header h4 {
    color: #1f2937;
    margin: 0 0 0.5rem 0;
}

.method-header p {
    color: #6b7280;
    margin: 0 0 1.5rem 0;
}

.file-upload-area {
    border: 2px dashed #d1d5db;
    border-radius: 8px;
    padding: 2rem;
    text-align: center;
    cursor: pointer;
    transition: border-color 0.2s, background-color 0.2s;
}

.file-upload-area:hover {
    border-color: #3b82f6;
    background-color: #f8fafc;
}

.upload-icon {
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

.file-info {
    font-size: 0.875rem;
    color: #6b7280;
}

.file-name {
    margin-top: 0.5rem;
    padding: 0.5rem;
    background-color: #f3f4f6;
    border-radius: 4px;
    font-weight: 500;
    display: none;
}

.requirements {
    background-color: #f0f9ff;
    border: 1px solid #bae6fd;
    border-radius: 8px;
    padding: 1rem;
    margin: 1.5rem 0;
}

.requirements h5 {
    margin: 0 0 0.75rem 0;
    color: #0369a1;
}

.requirements ul {
    margin: 0;
    padding-left: 1rem;
}

.requirements li {
    color: #075985;
    margin-bottom: 0.25rem;
}

.micro-steps {
    margin: 1.5rem 0;
}

.step {
    display: flex;
    align-items: flex-start;
    margin-bottom: 1.5rem;
}

.step-number {
    background-color: #3b82f6;
    color: white;
    width: 2rem;
    height: 2rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin-right: 1rem;
    flex-shrink: 0;
}

.step-content h5 {
    margin: 0 0 0.25rem 0;
    color: #1f2937;
}

.step-content p {
    margin: 0;
    color: #6b7280;
}

.amount-inputs {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1rem;
}

.micro-status {
    background-color: #f0f9ff;
    border: 1px solid #bae6fd;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1.5rem;
}

.status-info h5 {
    margin: 0 0 0.75rem 0;
    color: #0369a1;
}

.status-info p {
    margin: 0.25rem 0;
    color: #075985;
    font-size: 0.875rem;
}

.demo-bank-statement {
    background-color: #f8fafc;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    padding: 1.5rem;
    margin: 1.5rem 0;
}

.demo-bank-statement h5 {
    margin: 0 0 0.75rem 0;
    color: #1f2937;
}

.demo-bank-statement em {
    color: #6b7280;
    font-size: 0.875rem;
}

.statement-entries {
    background-color: white;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    margin: 1rem 0;
    overflow: hidden;
}

.statement-entry {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 1rem;
    border-bottom: 1px solid #f3f4f6;
    font-family: 'Courier New', monospace;
}

.statement-entry:last-child {
    border-bottom: none;
}

.entry-description {
    color: #374151;
    font-weight: 500;
}

.entry-amount {
    color: #059669;
    font-weight: bold;
}

.demo-note {
    margin: 0.75rem 0 0 0;
    color: #6b7280;
    font-size: 0.875rem;
    font-style: italic;
}

.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #374151;
}

.form-group input {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 1rem;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.form-group input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
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

.btn-full {
    width: 100%;
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

.back-action {
    margin-top: 1.5rem;
    text-align: center;
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

.error-text {
    color: #ef4444;
    font-size: 0.875rem;
    margin-top: 0.25rem;
    display: block;
}

@media (max-width: 768px) {
    .method-tabs {
        flex-direction: column;
    }
    
    .amount-inputs {
        grid-template-columns: 1fr;
    }
}
</style>

<script></script>

<?php echo $__env->make('includes.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\money-transfer\resources\views/bank-accounts/verify.blade.php ENDPATH**/ ?>