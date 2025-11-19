<?php echo $__env->make('includes.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<style>
body { background-color: #f3f4f6; }
.admin-container { max-width: 1400px; margin: 40px auto; padding: 0 20px; }
.page-header { margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; }
.page-header h1 { font-size: 32px; color: #1a202c; margin: 0; }
.sync-btn { padding: 10px 20px; background: #10b981; color: white; border: none; border-radius: 6px; cursor: pointer; }
.tabs { display: flex; gap: 10px; margin-bottom: 20px; border-bottom: 2px solid #e2e8f0; }
.tab { padding: 12px 24px; background: none; border: none; color: #64748b; font-size: 16px; cursor: pointer; border-bottom: 3px solid transparent; transition: all 0.2s; }
.tab.active { color: #3b82f6; border-bottom-color: #3b82f6; font-weight: 600; }
.tab-content { display: none; }
.tab-content.active { display: block; }
.card { background: white; border-radius: 8px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px; }
.card h2 { font-size: 20px; color: #1a202c; margin: 0 0 20px 0; }
.form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px; margin-bottom: 20px; }
.form-group { display: flex; flex-direction: column; }
.form-group label { font-size: 14px; color: #4a5568; margin-bottom: 5px; font-weight: 600; }
.form-group input, .form-group select { padding: 10px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 14px; }
.form-actions { display: flex; gap: 10px; justify-content: flex-end; }
.btn { padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 600; }
.btn-primary { background: #3b82f6; color: white; }
.btn-danger { background: #ef4444; color: white; }
.rates-table, .fees-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
.rates-table th, .fees-table th { text-align: left; padding: 12px; background: #f7fafc; color: #4a5568; font-size: 14px; font-weight: 600; border-bottom: 2px solid #e2e8f0; }
.rates-table td, .fees-table td { padding: 12px; border-bottom: 1px solid #e2e8f0; color: #2d3748; font-size: 14px; }
.badge { display: inline-block; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; }
.badge-percentage { background: #dbeafe; color: #1e40af; }
.badge-fixed { background: #fef3c7; color: #92400e; }
.badge-tiered { background: #e0e7ff; color: #3730a3; }
.action-btn { padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; font-size: 12px; margin-right: 5px; }
.action-btn.edit { background: #3b82f6; color: white; }
.action-btn.delete { background: #ef4444; color: white; }
.alert { padding: 12px 20px; border-radius: 6px; margin-bottom: 20px; }
.alert-success { background: #d1fae5; color: #065f46; border-left: 4px solid #10b981; }
.alert-error { background: #fee2e2; color: #991b1b; border-left: 4px solid #ef4444; }
.alert-info { background: #dbeafe; color: #1e40af; border-left: 4px solid #3b82f6; }
.currency-pair { display: flex; align-items: center; gap: 8px; }
.currency-pair .arrow { color: #9ca3af; }
.rate-value { font-weight: 600; color: #10b981; }
.updated-time { font-size: 12px; color: #9ca3af; }
</style>

<div class="admin-container">
    <div class="page-header">
        <h1>Exchange Rates & Fee Management</h1>
        <form action="<?php echo e(route('admin.exchange-rates.sync')); ?>" method="POST" style="display: inline;">
            <?php echo csrf_field(); ?>
            <button type="submit" class="sync-btn">🔄 Sync Rates</button>
        </form>
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

    <div class="tabs">
        <button class="tab active" onclick="switchTab('rates')">Exchange Rates</button>
        <button class="tab" onclick="switchTab('fees')">Fee Structures</button>
    </div>

    <!-- Exchange Rates Tab -->
    <div id="rates-tab" class="tab-content active">
        <div class="card">
            <h2>Add/Update Exchange Rate</h2>
            <form action="<?php echo e(route('admin.exchange-rates.update-rate')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="form-grid">
                    <div class="form-group">
                        <label>From Currency</label>
                        <select name="from_currency" required>
                            <option value="">Select Currency</option>
                            <?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($code); ?>"><?php echo e($code); ?> - <?php echo e($name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>To Currency</label>
                        <select name="to_currency" required>
                            <option value="">Select Currency</option>
                            <?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($code); ?>"><?php echo e($code); ?> - <?php echo e($name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Exchange Rate</label>
                        <input type="number" name="rate" step="0.000001" placeholder="e.g., 1.234567" required>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save Exchange Rate</button>
                </div>
            </form>
        </div>

        <div class="card">
            <h2>Current Exchange Rates</h2>
            <table class="rates-table">
                <thead>
                    <tr>
                        <th>Currency Pair</th>
                        <th>Exchange Rate</th>
                        <th>Last Updated</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $rates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $rate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <div class="currency-pair">
                                    <strong><?php echo e($rate['from']); ?></strong>
                                    <span class="arrow">→</span>
                                    <strong><?php echo e($rate['to']); ?></strong>
                                </div>
                            </td>
                            <td><span class="rate-value"><?php echo e(number_format($rate['rate'], 6)); ?></span></td>
                            <td><span class="updated-time"><?php echo e(\Carbon\Carbon::parse($rate['updated_at'])->diffForHumans()); ?></span></td>
                            <td>
                                <form action="<?php echo e(route('admin.exchange-rates.delete-rate', $key)); ?>" method="POST" style="display: inline;" onsubmit="return confirm('Delete this exchange rate?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="action-btn delete">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="4" style="text-align: center; padding: 40px; color: #718096;">No exchange rates configured</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Fee Structures Tab -->
    <div id="fees-tab" class="tab-content">
        <div class="card">
            <h2>Add/Update Fee Structure</h2>
            <form action="<?php echo e(route('admin.exchange-rates.update-fee')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Currency</label>
                        <select name="currency" required>
                            <option value="">Select Currency</option>
                            <?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($code); ?>"><?php echo e($code); ?> - <?php echo e($name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Fee Type</label>
                        <select name="fee_type" id="fee_type" required onchange="toggleFeeFields()">
                            <option value="percentage">Percentage</option>
                            <option value="fixed">Fixed Amount</option>
                            <option value="tiered">Tiered (Advanced)</option>
                        </select>
                    </div>
                    <div class="form-group" id="percentage_field">
                        <label>Fee Percentage (%)</label>
                        <input type="number" name="fee_percentage" step="0.01" placeholder="e.g., 2.5">
                    </div>
                    <div class="form-group" id="fixed_field">
                        <label>Fixed Fee Amount</label>
                        <input type="number" name="fee_fixed" step="0.01" placeholder="e.g., 1.00">
                    </div>
                    <div class="form-group">
                        <label>Minimum Fee</label>
                        <input type="number" name="min_fee" step="0.01" placeholder="e.g., 1.00">
                    </div>
                    <div class="form-group">
                        <label>Maximum Fee (Optional)</label>
                        <input type="number" name="max_fee" step="0.01" placeholder="e.g., 50.00">
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save Fee Structure</button>
                </div>
            </form>
        </div>

        <div class="card">
            <h2>Current Fee Structures</h2>
            <table class="fees-table">
                <thead>
                    <tr>
                        <th>Currency</th>
                        <th>Fee Type</th>
                        <th>Fee Details</th>
                        <th>Min Fee</th>
                        <th>Max Fee</th>
                        <th>Last Updated</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $fees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency => $fee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><strong><?php echo e($fee['currency']); ?></strong></td>
                            <td><span class="badge badge-<?php echo e($fee['fee_type']); ?>"><?php echo e(ucfirst($fee['fee_type'])); ?></span></td>
                            <td>
                                <?php if($fee['fee_type'] === 'percentage'): ?>
                                    <?php echo e($fee['fee_percentage']); ?>%
                                <?php elseif($fee['fee_type'] === 'fixed'): ?>
                                    <?php echo e(number_format($fee['fee_fixed'], 2)); ?> <?php echo e($fee['currency']); ?>

                                <?php else: ?>
                                    Tiered Structure
                                <?php endif; ?>
                            </td>
                            <td><?php echo e(number_format($fee['min_fee'], 2)); ?> <?php echo e($fee['currency']); ?></td>
                            <td><?php echo e($fee['max_fee'] ? number_format($fee['max_fee'], 2) . ' ' . $fee['currency'] : 'No limit'); ?></td>
                            <td><span class="updated-time"><?php echo e(\Carbon\Carbon::parse($fee['updated_at'])->diffForHumans()); ?></span></td>
                            <td>
                                <form action="<?php echo e(route('admin.exchange-rates.delete-fee', $currency)); ?>" method="POST" style="display: inline;" onsubmit="return confirm('Delete this fee structure?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="action-btn delete">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="7" style="text-align: center; padding: 40px; color: #718096;">No fee structures configured</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function switchTab(tab) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.remove('active');
    });
    
    // Remove active class from all tab buttons
    document.querySelectorAll('.tab').forEach(button => {
        button.classList.remove('active');
    });
    
    // Show selected tab
    document.getElementById(tab + '-tab').classList.add('active');
    
    // Add active class to clicked button
    event.target.classList.add('active');
}

function toggleFeeFields() {
    const feeType = document.getElementById('fee_type').value;
    const percentageField = document.getElementById('percentage_field');
    const fixedField = document.getElementById('fixed_field');
    
    if (feeType === 'percentage' || feeType === 'tiered') {
        percentageField.style.display = 'flex';
        fixedField.style.display = 'none';
    } else if (feeType === 'fixed') {
        percentageField.style.display = 'none';
        fixedField.style.display = 'flex';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', toggleFeeFields);
</script>

</main></body></html>
<?php /**PATH C:\xampp\htdocs\money-transfer\resources\views/admin/exchange-rates.blade.php ENDPATH**/ ?>