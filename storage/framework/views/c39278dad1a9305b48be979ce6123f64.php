<?php echo $__env->make('includes.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<section class="page-header">
    <h1>Edit Beneficiary</h1>
    <p>Update beneficiary information</p>
</section>

<section class="form-section">
    <div class="container">
        <?php if(session('error')): ?>
            <div class="alert alert-error"><?php echo e(session('error')); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="<?php echo e(route('beneficiaries.update', $beneficiary->id)); ?>" class="beneficiary-form">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            
            <div class="form-grid">
                <div class="form-group full-width">
                    <h3 class="section-title">Personal Information</h3>
                </div>
                
                <div class="form-group">
                    <label for="full_name">Full Name *</label>
                    <input type="text" name="full_name" id="full_name" value="<?php echo e(old('full_name', $beneficiary->full_name)); ?>" required>
                    <?php $__errorArgs = ['full_name'];
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
                    <label for="phone_number">Phone Number *</label>
                    <input type="tel" name="phone_number" id="phone_number" value="<?php echo e(old('phone_number', $beneficiary->phone_number)); ?>" required placeholder="+1234567890">
                    <small class="hint">Include country code (e.g., +1, +63, +234)</small>
                    <?php $__errorArgs = ['phone_number'];
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
                    <label for="email">Email Address</label>
                    <input type="email" name="email" id="email" value="<?php echo e(old('email', $beneficiary->email)); ?>" placeholder="email@example.com">
                    <?php $__errorArgs = ['email'];
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
                    <label for="relationship">Relationship</label>
                    <select name="relationship" id="relationship">
                        <option value="">-- Select relationship --</option>
                        <option value="family" <?php echo e(old('relationship', $beneficiary->relationship) == 'family' ? 'selected' : ''); ?>>Family</option>
                        <option value="friend" <?php echo e(old('relationship', $beneficiary->relationship) == 'friend' ? 'selected' : ''); ?>>Friend</option>
                        <option value="business" <?php echo e(old('relationship', $beneficiary->relationship) == 'business' ? 'selected' : ''); ?>>Business Partner</option>
                        <option value="employee" <?php echo e(old('relationship', $beneficiary->relationship) == 'employee' ? 'selected' : ''); ?>>Employee</option>
                        <option value="other" <?php echo e(old('relationship', $beneficiary->relationship) == 'other' ? 'selected' : ''); ?>>Other</option>
                    </select>
                    <?php $__errorArgs = ['relationship'];
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
                
                <div class="form-group full-width">
                    <h3 class="section-title">Location</h3>
                </div>
                
                <div class="form-group">
                    <label for="country">Country *</label>
                    <select name="country" id="country" required>
                        <option value="">-- Select country --</option>
                        <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($code); ?>" <?php echo e(old('country', $beneficiary->country) == $code ? 'selected' : ''); ?>>
                                <?php echo e($name); ?> (<?php echo e($code); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['country'];
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
                    <label for="city">City</label>
                    <input type="text" name="city" id="city" value="<?php echo e(old('city', $beneficiary->city)); ?>" placeholder="Enter city">
                    <?php $__errorArgs = ['city'];
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
                
                <div class="form-group full-width">
                    <label for="address">Address</label>
                    <input type="text" name="address" id="address" value="<?php echo e(old('address', $beneficiary->address)); ?>" placeholder="Street address">
                    <?php $__errorArgs = ['address'];
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
                    <label for="postal_code">Postal/ZIP Code</label>
                    <input type="text" name="postal_code" id="postal_code" value="<?php echo e(old('postal_code', $beneficiary->postal_code)); ?>" placeholder="Postal code">
                    <?php $__errorArgs = ['postal_code'];
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
                
                <div class="form-group full-width">
                    <h3 class="section-title">Payout Information</h3>
                </div>
                
                <div class="form-group">
                    <label for="preferred_payout_method">Preferred Payout Method *</label>
                    <select name="preferred_payout_method" id="preferred_payout_method" required>
                        <option value="">-- Select method --</option>
                        <?php $__currentLoopData = $payoutMethods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>" <?php echo e(old('preferred_payout_method', $beneficiary->preferred_payout_method) == $key ? 'selected' : ''); ?>>
                                <?php echo e($label); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['preferred_payout_method'];
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
                
                <div class="form-group" id="wallet-fields" style="display:none;">
                    <label for="mobile_wallet_number">Mobile Wallet Number</label>
                    <input type="text" name="mobile_wallet_number" id="mobile_wallet_number" value="<?php echo e(old('mobile_wallet_number', $beneficiary->mobile_wallet_number)); ?>" placeholder="Enter wallet number">
                    <?php $__errorArgs = ['mobile_wallet_number'];
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
                
                <div class="form-group" id="wallet-provider-field" style="display:none;">
                    <label for="mobile_wallet_provider">Wallet Provider</label>
                    <select name="mobile_wallet_provider" id="mobile_wallet_provider">
                        <option value="">-- Select provider --</option>
                        <option value="gcash" <?php echo e(old('mobile_wallet_provider', $beneficiary->mobile_wallet_provider) == 'gcash' ? 'selected' : ''); ?>>GCash</option>
                        <option value="paymaya" <?php echo e(old('mobile_wallet_provider', $beneficiary->mobile_wallet_provider) == 'paymaya' ? 'selected' : ''); ?>>PayMaya</option>
                        <option value="mpesa" <?php echo e(old('mobile_wallet_provider', $beneficiary->mobile_wallet_provider) == 'mpesa' ? 'selected' : ''); ?>>M-Pesa</option>
                        <option value="bkash" <?php echo e(old('mobile_wallet_provider', $beneficiary->mobile_wallet_provider) == 'bkash' ? 'selected' : ''); ?>>bKash</option>
                        <option value="easypaisa" <?php echo e(old('mobile_wallet_provider', $beneficiary->mobile_wallet_provider) == 'easypaisa' ? 'selected' : ''); ?>>Easypaisa</option>
                        <option value="jazzcash" <?php echo e(old('mobile_wallet_provider', $beneficiary->mobile_wallet_provider) == 'jazzcash' ? 'selected' : ''); ?>>JazzCash</option>
                        <option value="other" <?php echo e(old('mobile_wallet_provider', $beneficiary->mobile_wallet_provider) == 'other' ? 'selected' : ''); ?>>Other</option>
                    </select>
                    <?php $__errorArgs = ['mobile_wallet_provider'];
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
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update Beneficiary</button>
                <a href="<?php echo e(route('beneficiaries.index')); ?>" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</section>

<style>
.page-header{background:linear-gradient(135deg,#22c55e,#06b6d4);color:#fff;padding:2rem;text-align:center}
.form-section{padding:2rem 0;min-height:60vh}
.container{max-width:900px;margin:0 auto;padding:0 1rem}
.alert{padding:1rem;border-radius:8px;margin-bottom:1.5rem}
.alert-error{background:#fef2f2;border:1px solid #fecaca;color:#991b1b}
.beneficiary-form{background:#fff;padding:2rem;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.1)}
.form-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:1.5rem;margin-bottom:2rem}
.form-group.full-width{grid-column:1/-1}
.section-title{margin:0;padding-bottom:0.5rem;border-bottom:2px solid #e5e7eb;color:#374151;font-size:1rem;font-weight:600;text-transform:uppercase;letter-spacing:0.5px}
.form-group label{display:block;font-weight:600;margin-bottom:0.5rem;color:#374151}
.form-group input,.form-group select{width:100%;padding:0.75rem;border:1px solid #d1d5db;border-radius:8px;font-size:1rem}
.form-group input:focus,.form-group select:focus{outline:none;border-color:#2563eb;box-shadow:0 0 0 3px rgba(37,99,235,0.1)}
.hint{display:block;margin-top:0.25rem;font-size:0.75rem;color:#6b7280}
.error{display:block;margin-top:0.25rem;font-size:0.875rem;color:#dc2626}
.form-actions{display:flex;gap:1rem;justify-content:flex-end}
.btn{padding:0.75rem 1.5rem;border:none;border-radius:8px;cursor:pointer;font-size:1rem;font-weight:500;text-decoration:none;display:inline-block}
.btn-primary{background:#2563eb;color:#fff}
.btn-primary:hover{background:#1d4ed8}
.btn-outline{background:#fff;color:#374151;border:1px solid #d1d5db}
.btn-outline:hover{background:#f9fafb}
@media(max-width:768px){
.form-grid{grid-template-columns:1fr}
.form-actions{flex-direction:column}
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const payoutMethod = document.getElementById('preferred_payout_method');
    const walletFields = document.getElementById('wallet-fields');
    const walletProviderField = document.getElementById('wallet-provider-field');
    
    function toggleWalletFields() {
        if (payoutMethod.value === 'mobile_wallet') {
            walletFields.style.display = 'block';
            walletProviderField.style.display = 'block';
        } else {
            walletFields.style.display = 'none';
            walletProviderField.style.display = 'none';
        }
    }
    
    payoutMethod.addEventListener('change', toggleWalletFields);
    toggleWalletFields(); // Check on page load
});
</script>

<?php echo $__env->make('includes.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php /**PATH C:\xampp\htdocs\money-transfer\WebProject\resources\views/beneficiaries/edit.blade.php ENDPATH**/ ?>