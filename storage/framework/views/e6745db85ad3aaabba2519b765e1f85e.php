<?php echo $__env->make('includes.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<section class="page-header">
    <h1>Add Money to Wallet</h1>
    <p>Deposit funds from your bank account</p>
</section>

<section class="form-section">
    <div class="container">
        <?php if(session('error')): ?>
            <div class="alert alert-error"><?php echo e(session('error')); ?></div>
        <?php endif; ?>
        
        <?php if($bankAccounts->isEmpty()): ?>
            <div class="empty-state-card">
                <div class="empty-icon">🏦</div>
                <h3>No Bank Accounts Found</h3>
                <p>You need to add and verify a bank account before depositing money.</p>
                <a href="<?php echo e(route('bank-accounts.create')); ?>" class="btn btn-primary">Add Bank Account</a>
            </div>
        <?php else: ?>
            <form method="POST" action="<?php echo e(route('wallet.deposit')); ?>" class="transaction-form">
                <?php echo csrf_field(); ?>
                
                <div class="form-group">
                    <label for="bank_account_id">Select Bank Account *</label>
                    <select name="bank_account_id" id="bank_account_id" required>
                        <option value="">-- Choose bank account --</option>
                        <?php $__currentLoopData = $bankAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($account->id); ?>">
                                <?php echo e($account->bank_name); ?> - <?php echo e($account->masked_account_number); ?> (<?php echo e($account->currency_display); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['bank_account_id'];
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
                    <label for="amount">Amount to Deposit *</label>
                    <input type="number" name="amount" id="amount" step="0.01" min="1" max="100000" value="<?php echo e(old('amount')); ?>" required>
                    <small class="hint">Maximum: 100,000 per transaction</small>
                    <?php $__errorArgs = ['amount'];
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
                
                <div class="info-box">
                    <strong>ℹ️ How it works:</strong>
                    <ul>
                        <li>Funds are transferred from your bank account to your wallet</li>
                        <li>Processing time: Instant (simulated)</li>
                        <li>No fees for deposits</li>
                        <li>Your wallet balance will be updated immediately</li>
                    </ul>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">💵 Add Money</button>
                    <a href="<?php echo e(route('wallet.index')); ?>" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        <?php endif; ?>
    </div>
</section>

<style>
.page-header{background:linear-gradient(135deg,#3b82f6,#2563eb);color:#fff;padding:2rem;text-align:center}
.form-section{padding:2rem 0;min-height:60vh;background:#f9fafb}
.container{max-width:600px;margin:0 auto;padding:0 1rem}
.alert{padding:1rem;border-radius:8px;margin-bottom:1.5rem}
.alert-error{background:#fef2f2;border:1px solid #fecaca;color:#991b1b}
.empty-state-card{background:#fff;border-radius:16px;padding:3rem 2rem;text-align:center;box-shadow:0 2px 12px rgba(0,0,0,0.08)}
.empty-icon{font-size:4rem;margin-bottom:1rem}
.empty-state-card h3{margin:0 0 0.5rem 0;color:#111827}
.empty-state-card p{margin:0 0 1.5rem 0;color:#6b7280}
.transaction-form{background:#fff;border-radius:16px;padding:2rem;box-shadow:0 2px 12px rgba(0,0,0,0.08)}
.form-group{margin-bottom:1.5rem}
.form-group label{display:block;font-weight:600;margin-bottom:0.5rem;color:#374151}
.form-group input,.form-group select{width:100%;padding:0.75rem;border:1px solid #d1d5db;border-radius:8px;font-size:1rem}
.form-group input:focus,.form-group select:focus{outline:none;border-color:#3b82f6;box-shadow:0 0 0 3px rgba(59,130,246,0.1)}
.hint{display:block;margin-top:0.25rem;font-size:0.875rem;color:#6b7280}
.error{display:block;margin-top:0.25rem;font-size:0.875rem;color:#dc2626}
.info-box{background:#eff6ff;border:1px solid#bfdbfe;border-radius:8px;padding:1rem;margin-bottom:1.5rem}
.info-box strong{display:block;margin-bottom:0.5rem;color:#1e40af}
.info-box ul{margin:0.5rem 0 0 1.5rem;padding:0;color:#1e3a8a}
.info-box li{margin-bottom:0.25rem}
.form-actions{display:flex;gap:1rem;justify-content:center}
.btn{padding:0.75rem 1.5rem;border:none;border-radius:8px;cursor:pointer;font-size:1rem;font-weight:600;text-decoration:none;display:inline-block}
.btn-primary{background:#3b82f6;color:#fff}
.btn-primary:hover{background:#2563eb}
.btn-secondary{background:#6b7280;color:#fff}
.btn-secondary:hover{background:#4b5563}
@media(max-width:768px){
.form-actions{flex-direction:column}
}
</style>

<?php echo $__env->make('includes.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php /**PATH C:\xampp\htdocs\money-transfer\WebProject\resources\views/wallet/deposit.blade.php ENDPATH**/ ?>