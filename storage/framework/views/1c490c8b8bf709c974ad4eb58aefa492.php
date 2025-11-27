<?php echo $__env->make('includes.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<style>
body { background-color: #f3f4f6; }
.admin-container { max-width: 1400px; margin: 40px auto; padding: 0 20px; }
.admin-page-header { margin-bottom: 30px; }
.admin-page-header h1 { font-size: 32px; color: #1a202c; margin: 0; }
.filter-bar { background: white; border-radius: 8px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
.filter-row { display: flex; gap: 10px; flex-wrap: wrap; align-items: end; }
.filter-group { flex: 1; min-width: 150px; }
.filter-group label { display: block; margin-bottom: 5px; font-weight: 600; color: #4a5568; font-size: 13px; }
.filter-group input, .filter-group select { width: 100%; padding: 8px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 14px; }
.filter-btn { padding: 8px 20px; background: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; }
.transfers-table-container { background: white; border-radius: 8px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
.transfers-table { width: 100%; border-collapse: collapse; font-size: 14px; }
.transfers-table th { text-align: left; padding: 12px; background: #f7fafc; color: #4a5568; font-weight: 600; border-bottom: 2px solid #e2e8f0; }
.transfers-table td { padding: 12px; border-bottom: 1px solid #e2e8f0; color: #2d3748; }
.badge { display: inline-block; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; }
.badge-pending { background: #fef3c7; color: #92400e; }
.badge-processing { background: #dbeafe; color: #1e40af; }
.badge-completed { background: #d1fae5; color: #065f46; }
.badge-failed { background: #fee2e2; color: #991b1b; }
.badge-cancelled { background: #f3f4f6; color: #4a5568; }
.action-link { color: #3b82f6; text-decoration: none; font-size: 13px; }
.action-link:hover { text-decoration: underline; }
</style>

<div class="admin-container">
<div class="admin-page-header">
<h1>Transfer Management</h1>
</div>

<div class="filter-bar">
<form method="GET" action="<?php echo e(route('admin.transfers.index')); ?>">
<div class="filter-row">
<div class="filter-group">
<label>Search</label>
<input type="text" name="search" placeholder="ID or Amount" value="<?php echo e(request('search')); ?>">
</div>
<div class="filter-group">
<label>Status</label>
<select name="status">
<option value="">All Status</option>
<option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>Pending</option>
<option value="processing" <?php echo e(request('status') == 'processing' ? 'selected' : ''); ?>>Processing</option>
<option value="completed" <?php echo e(request('status') == 'completed' ? 'selected' : ''); ?>>Completed</option>
<option value="failed" <?php echo e(request('status') == 'failed' ? 'selected' : ''); ?>>Failed</option>
<option value="cancelled" <?php echo e(request('status') == 'cancelled' ? 'selected' : ''); ?>>Cancelled</option>
</select>
</div>
<div class="filter-group">
<label>Date From</label>
<input type="date" name="date_from" value="<?php echo e(request('date_from')); ?>">
</div>
<div class="filter-group">
<label>Date To</label>
<input type="date" name="date_to" value="<?php echo e(request('date_to')); ?>">
</div>
<button type="submit" class="filter-btn">Filter</button>
</div>
</form>
</div>

<div class="transfers-table-container">
<table class="transfers-table">
<thead>
<tr>
<th>ID</th>
<th>Sender</th>
<th>Receiver</th>
<th>Amount</th>
<th>Status</th>
<th>Date</th>
<th>Actions</th>
</tr>
</thead>
<tbody>
<?php $__empty_1 = true; $__currentLoopData = $transfers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transfer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<tr>
<td>#<?php echo e($transfer->id); ?></td>
<td><?php echo e($transfer->sender->name ?? 'N/A'); ?></td>
<td><?php echo e($transfer->receiver->name ?? $transfer->receiver_name ?? 'N/A'); ?></td>
<td>$<?php echo e(number_format($transfer->amount, 2)); ?> <?php echo e($transfer->currency ?? 'USD'); ?></td>
<td><span class="badge badge-<?php echo e($transfer->status); ?>"><?php echo e(ucfirst($transfer->status)); ?></span></td>
<td><?php echo e($transfer->created_at->format('M d, Y H:i')); ?></td>
<td>
<a href="<?php echo e(route('admin.transfers.show', $transfer->id)); ?>" class="action-link">View Details</a>
</td>
</tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
<tr><td colspan="7" style="text-align: center; padding: 40px; color: #718096;">No transfers found</td></tr>
<?php endif; ?>
</tbody>
</table>

<div style="margin-top: 20px;">
<?php echo e($transfers->links()); ?>

</div>
</div>
</div>

</main></body></html>
<?php /**PATH C:\xampp\htdocs\money-transfer\resources\views/admin/transfers/index.blade.php ENDPATH**/ ?>