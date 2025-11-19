<?php echo $__env->make('includes.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<section class="page-header">
    <h1>Add New Bank Account</h1>
    <p>Link a new bank account to your SwiftPay account</p>
</section>

<section class="add-account">
    <div class="container">
        <div class="form-container">
            <form action="<?php echo e(route('bank-accounts.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                
                <div class="form-group">
                    <label for="bank_name">Bank Name *</label>
                    <input type="text" id="bank_name" name="bank_name" value="<?php echo e(old('bank_name')); ?>" required>
                    <?php $__errorArgs = ['bank_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="error-text"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label for="account_number">Account Number *</label>
                    <input type="text" id="account_number" name="account_number" value="<?php echo e(old('account_number')); ?>" required>
                    <?php $__errorArgs = ['account_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="error-text"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label for="account_type">Account Type</label>
                    <select id="account_type" name="account_type">
                        <option value="">Select account type</option>
                        <option value="checking" <?php echo e(old('account_type') == 'checking' ? 'selected' : ''); ?>>Checking</option>
                        <option value="savings" <?php echo e(old('account_type') == 'savings' ? 'selected' : ''); ?>>Savings</option>
                        <option value="business" <?php echo e(old('account_type') == 'business' ? 'selected' : ''); ?>>Business</option>
                        <option value="current" <?php echo e(old('account_type') == 'current' ? 'selected' : ''); ?>>Current</option>
                    </select>
                    <?php $__errorArgs = ['account_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="error-text"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label for="currency">Currency *</label>
                    <select id="currency" name="currency" required>
                        <option value="">Select currency</option>
                        <option value="USD" <?php echo e(old('currency') == 'USD' ? 'selected' : ''); ?>>USD - US Dollar</option>
                        <option value="EUR" <?php echo e(old('currency') == 'EUR' ? 'selected' : ''); ?>>EUR - Euro</option>
                        <option value="GBP" <?php echo e(old('currency') == 'GBP' ? 'selected' : ''); ?>>GBP - British Pound</option>
                        <option value="JPY" <?php echo e(old('currency') == 'JPY' ? 'selected' : ''); ?>>JPY - Japanese Yen</option>
                        <option value="CAD" <?php echo e(old('currency') == 'CAD' ? 'selected' : ''); ?>>CAD - Canadian Dollar</option>
                        <option value="AUD" <?php echo e(old('currency') == 'AUD' ? 'selected' : ''); ?>>AUD - Australian Dollar</option>
                        <option value="CHF" <?php echo e(old('currency') == 'CHF' ? 'selected' : ''); ?>>CHF - Swiss Franc</option>
                        <option value="CNY" <?php echo e(old('currency') == 'CNY' ? 'selected' : ''); ?>>CNY - Chinese Yuan</option>
                        <option value="SEK" <?php echo e(old('currency') == 'SEK' ? 'selected' : ''); ?>>SEK - Swedish Krona</option>
                        <option value="NZD" <?php echo e(old('currency') == 'NZD' ? 'selected' : ''); ?>>NZD - New Zealand Dollar</option>
                        <option value="MXN" <?php echo e(old('currency') == 'MXN' ? 'selected' : ''); ?>>MXN - Mexican Peso</option>
                        <option value="SGD" <?php echo e(old('currency') == 'SGD' ? 'selected' : ''); ?>>SGD - Singapore Dollar</option>
                        <option value="HKD" <?php echo e(old('currency') == 'HKD' ? 'selected' : ''); ?>>HKD - Hong Kong Dollar</option>
                        <option value="NOK" <?php echo e(old('currency') == 'NOK' ? 'selected' : ''); ?>>NOK - Norwegian Krone</option>
                        <option value="KRW" <?php echo e(old('currency') == 'KRW' ? 'selected' : ''); ?>>KRW - South Korean Won</option>
                        <option value="TRY" <?php echo e(old('currency') == 'TRY' ? 'selected' : ''); ?>>TRY - Turkish Lira</option>
                        <option value="RUB" <?php echo e(old('currency') == 'RUB' ? 'selected' : ''); ?>>RUB - Russian Ruble</option>
                        <option value="INR" <?php echo e(old('currency') == 'INR' ? 'selected' : ''); ?>>INR - Indian Rupee</option>
                        <option value="BRL" <?php echo e(old('currency') == 'BRL' ? 'selected' : ''); ?>>BRL - Brazilian Real</option>
                        <option value="ZAR" <?php echo e(old('currency') == 'ZAR' ? 'selected' : ''); ?>>ZAR - South African Rand</option>
                        <option value="LBP" <?php echo e(old('currency') == 'LBP' ? 'selected' : ''); ?>>LBP - Lebanese Pound</option>
                    </select>
                    <?php $__errorArgs = ['currency'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="error-text"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="security-notice">
                    <div class="notice-icon">🔒</div>
                    <div class="notice-content">
                        <h4>Security Notice</h4>
                        <p>Your bank account information is encrypted and stored securely. We never store your online banking credentials.</p>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="<?php echo e(route('bank-accounts.index')); ?>" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Add Bank Account</button>
                </div>
            </form>
        </div>

        <div class="info-panel">
            <h3>Why verify your bank account?</h3>
            <ul>
                <li>✓ Faster money transfers</li>
                <li>✓ Higher transfer limits</li>
                <li>✓ Lower fees for verified accounts</li>
                <li>✓ Enhanced security for your transfers</li>
            </ul>

            <div class="verification-methods">
                <h4>Verification Methods Available:</h4>
                <div class="method">
                    <strong>📄 Document Upload</strong>
                    <p>Upload a bank statement or void check</p>
                </div>
                <div class="method">
                    <strong>💰 Micro-transfers</strong>
                    <p>We'll send small amounts to verify ownership</p>
                </div>
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

.add-account {
    padding: 2rem;
    background-color: #f9fafb;
    min-height: 70vh;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: 2rem;
}

.form-container {
    background: white;
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #374151;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 1rem;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.error-text {
    color: #ef4444;
    font-size: 0.875rem;
    margin-top: 0.25rem;
    display: block;
}

.security-notice {
    background-color: #f0f9ff;
    border: 1px solid #bae6fd;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
}

.notice-icon {
    font-size: 1.5rem;
}

.notice-content h4 {
    margin: 0 0 0.25rem 0;
    color: #0369a1;
}

.notice-content p {
    margin: 0;
    color: #075985;
    font-size: 0.875rem;
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

.info-panel {
    background: white;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    height: fit-content;
}

.info-panel h3 {
    color: #1f2937;
    margin-bottom: 1rem;
}

.info-panel ul {
    list-style: none;
    padding: 0;
    margin-bottom: 1.5rem;
}

.info-panel li {
    padding: 0.5rem 0;
    color: #059669;
}

.verification-methods h4 {
    color: #374151;
    margin-bottom: 1rem;
}

.method {
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e5e7eb;
}

.method:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.method strong {
    color: #1f2937;
    display: block;
    margin-bottom: 0.25rem;
}

.method p {
    color: #6b7280;
    font-size: 0.875rem;
    margin: 0;
}

@media (max-width: 768px) {
    .container {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .btn {
        width: 100%;
    }
}
</style>

<?php echo $__env->make('includes.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\money-transfer\resources\views/bank-accounts/create.blade.php ENDPATH**/ ?>