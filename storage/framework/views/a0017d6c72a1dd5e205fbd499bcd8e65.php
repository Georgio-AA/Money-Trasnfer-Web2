<?php echo $__env->make('includes.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<style>
body { background-color: #f3f4f6; }
.admin-container { max-width: 1400px; margin: 40px auto; padding: 0 20px; }
.card { border: none; box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15); }
.card-header { background-color: #007bff; color: white; font-weight: bold; }
.border-left-primary { border-left: 0.25rem solid #007bff !important; }
.border-left-success { border-left: 0.25rem solid #28a745 !important; }
.border-left-warning { border-left: 0.25rem solid #ffc107 !important; }
.status-badge { padding: 0.35rem 0.65rem; border-radius: 0.25rem; font-size: 0.875rem; }
.status-pending { background-color: #ffc107; color: #000; }
.status-approved { background-color: #17a2b8; color: white; }
.status-paid { background-color: #28a745; color: white; }
</style>

<div class="admin-container">
    <div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">Commission Details</h1>
            <small class="text-muted"><?php echo e($agent->user->name ?? 'Agent'); ?> <?php echo e($agent->user->surname ?? ''); ?></small>
        </div>
        <div class="col-md-4 text-end">
            <a href="<?php echo e(route('admin.commissions.index')); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <a href="<?php echo e(route('admin.commissions.report', ['agent_id' => $agent->id])); ?>" class="btn btn-primary">
                <i class="fas fa-download"></i> Export Report
            </a>
        </div>
    </div>

    <!-- Agent Information Card -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="card-title">Agent Information</h5>
                            <p class="mb-2">
                                <strong>Name:</strong> <?php echo e($agent->user->name ?? 'N/A'); ?> <?php echo e($agent->user->surname ?? ''); ?>

                            </p>
                            <p class="mb-2">
                                <strong>Email:</strong> <?php echo e($agent->user->email ?? 'N/A'); ?>

                            </p>
                            <p class="mb-2">
                                <strong>Store:</strong> <?php echo e($agent->store_name ?? 'N/A'); ?>

                            </p>
                            <p class="mb-0">
                                <strong>Address:</strong> <?php echo e($agent->address ?? 'N/A'); ?>, <?php echo e($agent->country ?? 'N/A'); ?>

                            </p>
                        </div>
                        <div class="col-md-6">
                            <h5 class="card-title">Commission Settings</h5>
                            <p class="mb-2">
                                <strong>Commission Rate:</strong> 
                                <span class="badge badge-primary"><?php echo e($agent->commission_rate ?? 0); ?>%</span>
                            </p>
                            <p class="mb-2">
                                <strong>Commission Type:</strong> 
                                <span class="badge badge-info"><?php echo e(ucfirst($agent->commission_type ?? 'percentage')); ?></span>
                            </p>
                            <p class="mb-2">
                                <strong>Status:</strong> 
                                <?php if($agent->approved): ?>
                                    <span class="badge badge-success">Approved</span>
                                <?php else: ?>
                                    <span class="badge badge-warning">Pending Approval</span>
                                <?php endif; ?>
                            </p>
                            <p class="mb-0">
                                <strong>Joined:</strong> <?php echo e($agent->created_at->format('M d, Y')); ?>

                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-primary text-uppercase text-xs font-weight-bold mb-1">
                        Total Transfers
                    </div>
                    <div class="h3 mb-0 font-weight-bold text-gray-800">
                        <?php echo e($stats['total_transfers'] ?? 0); ?>

                    </div>
                    <small class="text-muted">
                        Period: <?php echo e($dateRange[0]->format('M d')); ?> - <?php echo e($dateRange[1]->format('M d, Y')); ?>

                    </small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-success text-uppercase text-xs font-weight-bold mb-1">
                        Total Transfer Amount
                    </div>
                    <div class="h3 mb-0 font-weight-bold text-gray-800">
                        $<?php echo e(number_format($stats['total_transfer_amount'] ?? 0, 2)); ?>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-info text-uppercase text-xs font-weight-bold mb-1">
                        Total Commission Earned
                    </div>
                    <div class="h3 mb-0 font-weight-bold text-gray-800">
                        $<?php echo e(number_format($stats['total_commission'] ?? 0, 2)); ?>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-warning text-uppercase text-xs font-weight-bold mb-1">
                        Average Commission
                    </div>
                    <div class="h3 mb-0 font-weight-bold text-gray-800">
                        $<?php echo e(number_format($stats['average_commission_per_transfer'] ?? 0, 2)); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Commission Breakdown -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark py-3">
                    <h6 class="m-0 font-weight-bold">Pending Commission</h6>
                </div>
                <div class="card-body">
                    <h3 class="h5 text-warning">$<?php echo e(number_format($stats['pending_commission'] ?? 0, 2)); ?></h3>
                    <small class="text-muted">Awaiting approval</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-info text-white py-3">
                    <h6 class="m-0 font-weight-bold">Approved Commission</h6>
                </div>
                <div class="card-body">
                    <h3 class="h5 text-info">$<?php echo e(number_format($stats['approved_commission'] ?? 0, 2)); ?></h3>
                    <small class="text-muted">Ready to pay out</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-success text-white py-3">
                    <h6 class="m-0 font-weight-bold">Paid Commission</h6>
                </div>
                <div class="card-body">
                    <h3 class="h5 text-success">$<?php echo e(number_format($stats['paid_commission'] ?? 0, 2)); ?></h3>
                    <small class="text-muted">Already paid out</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Date Range Filter -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-body">
                    <form method="GET" action="<?php echo e(route('admin.commissions.detail', $agent->id)); ?>" class="form-inline">
                        <div class="form-group me-3">
                            <label for="period" class="me-2">Period:</label>
                            <select name="period" id="period" class="form-control" onchange="this.form.submit()">
                                <option value="daily" <?php echo e(request('period') === 'daily' ? 'selected' : ''); ?>>Daily</option>
                                <option value="weekly" <?php echo e(request('period') === 'weekly' ? 'selected' : ''); ?>>Weekly</option>
                                <option value="monthly" <?php echo e(request('period') === 'monthly' ? 'selected' : ''); ?>>Monthly</option>
                                <option value="yearly" <?php echo e(request('period') === 'yearly' ? 'selected' : ''); ?>>Yearly</option>
                                <option value="custom" <?php echo e(request('period') === 'custom' ? 'selected' : ''); ?>>Custom</option>
                            </select>
                        </div>
                        <?php if(request('period') === 'custom'): ?>
                            <div class="form-group me-3">
                                <label for="start_date" class="me-2">From:</label>
                                <input type="date" name="start_date" id="start_date" class="form-control" 
                                       value="<?php echo e(request('start_date')); ?>" onchange="this.form.submit()">
                            </div>
                            <div class="form-group">
                                <label for="end_date" class="me-2">To:</label>
                                <input type="date" name="end_date" id="end_date" class="form-control" 
                                       value="<?php echo e(request('end_date')); ?>" onchange="this.form.submit()">
                            </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Commissions Table -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="m-0 font-weight-bold">Commission Transactions</h5>
                </div>
                <?php if($commissions->isEmpty()): ?>
                    <div class="card-body">
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle"></i> No commissions found for this period.
                        </div>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Transfer ID</th>
                                    <th>Transfer Amount</th>
                                    <th>Commission Amount</th>
                                    <th>Rate</th>
                                    <th>Method</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $commissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $commission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <?php if($commission->transfer): ?>
                                                <a href="<?php echo e(route('admin.transfers.show', $commission->transfer)); ?>" class="text-primary">
                                                    #<?php echo e($commission->transfer_id); ?>

                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted">#<?php echo e($commission->transfer_id); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>$<?php echo e(number_format($commission->transfer_amount, 2)); ?></td>
                                        <td>
                                            <strong class="text-success">
                                                $<?php echo e(number_format($commission->commission_amount, 2)); ?>

                                            </strong>
                                        </td>
                                        <td><?php echo e($commission->commission_rate); ?>%</td>
                                        <td>
                                            <span class="badge badge-secondary">
                                                <?php echo e(ucfirst($commission->calculation_method)); ?>

                                            </span>
                                        </td>
                                        <td>
                                            <?php if($commission->status === 'paid'): ?>
                                                <span class="badge badge-success">Paid</span>
                                            <?php elseif($commission->status === 'approved'): ?>
                                                <span class="badge badge-info">Approved</span>
                                            <?php else: ?>
                                                <span class="badge badge-warning">Pending</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($commission->created_at->format('M d, Y H:i')); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <?php echo e($commissions->links()); ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

    </div>
</div>

<?php echo $__env->make('includes.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php /**PATH C:\XAMPP\htdocs\money-transfer2\WebProject\resources\views/admin/commissions/detail.blade.php ENDPATH**/ ?>