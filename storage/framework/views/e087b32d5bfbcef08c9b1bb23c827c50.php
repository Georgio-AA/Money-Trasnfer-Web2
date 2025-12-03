<?php echo $__env->make('includes.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<style>
body { background-color: #f3f4f6; }
.admin-container { max-width: 1400px; margin: 40px auto; padding: 0 20px; }

/* HEADER */
.report-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
.report-header-title { font-size: 28px; font-weight: 700; color: #1a202c; }
.report-header-subtitle { font-size: 15px; color: #718096; }

/* FILTER inputs modern styling */
.form-select, .form-control {
    background: #ffffff;
    border: 1px solid #cbd5e0;
    border-radius: 8px;
    padding: 10px 12px;
    font-size: 14px;
    color: #2d3748;
    box-shadow: 0 1px 2px rgba(0,0,0,0.04);
    transition: border 0.2s, box-shadow 0.2s;
}

.form-select:focus, .form-control:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 2px rgba(59,130,246,0.25);
}

/* FILTER labels */

.filter-row {
    display: flex;
    align-items: flex-end;
    gap: 16px;
    flex-wrap: nowrap;
    padding: 5px;
}

.filter-row > div {
    display: flex;
    flex-direction: column;
}

.form-label {
    font-weight: 600;
    color: #4a5568;
    font-size: 14px;
}

/* BUTTONS */
.btn-primary {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    border: none;
    border-radius: 6px;
    padding: 10px 16px;
    font-size: 14px;
    font-weight: 600;
    color: white;
    transition: opacity .2s, transform .2s;
}

.btn-primary:hover {
    opacity: .90;
    transform: translateY(-1px);
}

.btn-outline-secondary {
    border: 1px solid #cbd5e0;
    border-radius: 6px;
    padding: 10px 16px;
    font-size: 14px;
    color: #4a5568;
    font-weight: 600;
    background: white;
}

.btn-outline-secondary:hover {
    background: #edf2f7;
}

/* PDF + Excel buttons */
.btn-outline-danger {
    border: 1px solid #f87171;
    color: #b91c1c;
    border-radius: 6px;
    padding: 10px 16px;
    font-size: 14px;
    background: white;
}
.btn-outline-danger:hover {
    background: #fee2e2;
}

.btn-outline-success {
    border: 1px solid #34d399;
    color: #047857;
    border-radius: 6px;
    padding: 10px 16px;
    font-size: 14px;
    background: white;
}
.btn-outline-success:hover {
    background: #d1fae5;
}


/* GRID STAT CARDS */
.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 30px; }
.stat-card { background: white; border-radius: 8px; padding: 22px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
.stat-card h3 { font-size: 13px; color: #718096; text-transform: uppercase; margin: 0 0 8px 0; }
.stat-card .value { font-size: 30px; font-weight: 700; color: #2d3748; }

/* TABLE */
.table-card { background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
.table-card-header { padding: 20px; font-size: 18px; font-weight: 600; border-bottom: 1px solid #e2e8f0; }
.table { width: 100%; border-collapse: collapse; }
.table thead th { font-size: 12px; text-transform: uppercase; color: #718096; border-bottom: 2px solid #e2e8f0; padding: 12px; }
.table tbody td { padding: 14px; border-bottom: 1px solid #edf2f7; font-size: 14px; color: #2d3748; }

.status-badge { display: inline-block; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; }
.status-pending { background: #fef3c7; color: #92400e; }
.status-approved { background: #dbeafe; color: #1e40af; }
.status-paid { background: #d1fae5; color: #065f46; }
</style>

<div class="admin-container">

    <!-- HEADER -->
    <div class="report-header">
        <div>
            <div class="report-header-title">Commission Report</div>
            <div class="report-header-subtitle">Commission analytics & financial breakdown</div>
        </div>

        <div style="display: flex; gap: 8px;">
            <div class="d-flex gap-2">
    <a href="<?php echo e(route('admin.commissions.index')); ?>" class="btn btn-outline-secondary">
        Back
    </a>
    <a href="<?php echo e(route('admin.commissions.export.pdf', request()->query())); ?>" 
       class="btn btn-outline-danger" target="_blank">
        PDF
    </a>
    <a href="<?php echo e(route('admin.commissions.export.excel', request()->query())); ?>" 
       class="btn btn-outline-success">
        Excel
    </a>
</div>

        </div>
    </div>

    <form method="GET" action="<?php echo e(route('admin.commissions.report')); ?>">
    <div class="filter-row">

        <div>
            <label class="form-label">Period</label>
            <select name="period" class="form-select" onchange="updateDateFields()">
                <option value="daily" <?php echo e(request('period')==='daily'?'selected':''); ?>>Daily</option>
                <option value="weekly" <?php echo e(request('period')==='weekly'?'selected':''); ?>>Weekly</option>
                <option value="monthly" <?php echo e(request('period')==='monthly'?'selected':''); ?>>Monthly</option>
                <option value="yearly" <?php echo e(request('period')==='yearly'?'selected':''); ?>>Yearly</option>
                <option value="custom" <?php echo e(request('period')==='custom'?'selected':''); ?>>Custom</option>
            </select>
        </div>

        <div>
            <label class="form-label">Agent</label>
            <select name="agent_id" class="form-select">
                <option value="">All Agents</option>
                <?php $__currentLoopData = $agents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($agent->id); ?>" <?php echo e(request('agent_id')==$agent->id?'selected':''); ?>>
                    <?php echo e($agent->user->name); ?>

                </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div>
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="">All</option>
                <option value="pending" <?php echo e(request('status')==='pending'?'selected':''); ?>>Pending</option>
                <option value="approved" <?php echo e(request('status')==='approved'?'selected':''); ?>>Approved</option>
                <option value="paid" <?php echo e(request('status')==='paid'?'selected':''); ?>>Paid</option>
            </select>
        </div>

        <div>
            <label class="form-label">Method</label>
            <select name="calculation_method" class="form-select">
                <option value="">All</option>
                <option value="percentage" <?php echo e(request('calculation_method')==='percentage'?'selected':''); ?>>Percentage</option>
                <option value="fixed" <?php echo e(request('calculation_method')==='fixed'?'selected':''); ?>>Fixed</option>
            </select>
        </div>

        <div class="buttons">
            <button type="submit" class="btn btn-primary">Apply</button>
        </div>
        <div class="buttons">
            <a href="<?php echo e(route('admin.commissions.report')); ?>" class="btn btn-outline-secondary">Reset</a>
        </div>

    </div>
</form>

    <!-- STATS -->
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total Commission</h3>
            <div class="value">$<?php echo e(number_format($totals['total_commission'],2)); ?></div>
        </div>
        <div class="stat-card">
            <h3>Total Transfers</h3>
            <div class="value"><?php echo e($totals['total_transfers']); ?></div>
        </div>
        <div class="stat-card">
            <h3>Average Commission</h3>
            <div class="value">$<?php echo e(number_format($totals['average_commission'],2)); ?></div>
        </div>
        <div class="stat-card">
            <h3>Paid vs Pending</h3>
            <div class="value">
                <span style="color:#10b981;">$<?php echo e(number_format($totals['paid'],2)); ?></span>
                <span style="color:#f59e0b; font-size:20px;"> | </span>
                <span style="color:#92400e;">$<?php echo e(number_format($totals['pending'],2)); ?></span>
            </div>
        </div>
    </div>

    <!-- TABLE -->
    <div class="table-card">
        <div class="table-card-header">Commission Records</div>

        <?php if($commissions->isEmpty()): ?>
            <div style="padding:24px; text-align:center; color:#718096;">No commissions found</div>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Agent</th>
                        <th>Amount</th>
                        <th>Commission</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                <?php $__currentLoopData = $commissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $commission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($commission->agent->user->name); ?></td>
                        <td>$<?php echo e(number_format($commission->transfer_amount,2)); ?></td>
                        <td>$<?php echo e(number_format($commission->commission_amount,2)); ?></td>
                        <td><?php echo e($commission->calculation_method); ?></td>
                        <td>
                            <span class="status-badge status-<?php echo e($commission->status); ?>">
                                <?php echo e(ucfirst($commission->status)); ?>

                            </span>
                        </td>
                        <td><?php echo e($commission->created_at->format('M d, Y')); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>

            <div style="padding: 15px;">
                <?php echo e($commissions->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>

<?php echo $__env->make('includes.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php /**PATH C:\XAMPP\htdocs\money-transfer2\WebProject\resources\views/admin/commissions/report.blade.php ENDPATH**/ ?>