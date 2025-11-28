<?php echo $__env->make('includes.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<style>
body { background-color: #f3f4f6; }
.admin-container { max-width: 1400px; margin: 40px auto; padding: 0 20px; }
.admin-page-header { margin-bottom: 40px; display: flex; justify-content: space-between; align-items: center; }
.admin-page-header h1 { font-size: 32px; color: #1a202c; margin: 0; }
.admin-page-header p { color: #718096; font-size: 16px; margin: 5px 0 0 0; }
.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
.stat-card { background: white; border-radius: 8px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); position: relative; }
.stat-card h3 { font-size: 14px; color: #718096; margin: 0 0 8px 0; text-transform: uppercase; font-weight: 600; }
.stat-card .value { font-size: 36px; font-weight: bold; color: #2d3748; margin: 0; }
.stat-card .subtext { font-size: 13px; color: #718096; margin-top: 8px; }
.stat-card.clickable { cursor: pointer; transition: transform 0.2s, box-shadow 0.2s; }
.stat-card.clickable:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
.stat-card a { text-decoration: none; color: inherit; display: block; }
.revenue-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 30px; }
.revenue-card { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 8px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
.revenue-card.green { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
.revenue-card.blue { background: linear-gradient(135deg, #2193b0 0%, #6dd5ed 100%); }
.revenue-card.orange { background: linear-gradient(135deg, #ee0979 0%, #ff6a00 100%); }
.revenue-card h3 { font-size: 14px; margin: 0 0 12px 0; opacity: 0.9; text-transform: uppercase; font-weight: 600; }
.revenue-card .value { font-size: 32px; font-weight: bold; margin: 0; }
.charts-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px; }
.chart-card { background: white; border-radius: 8px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
.chart-card h2 { font-size: 18px; color: #1a202c; margin: 0 0 20px 0; font-weight: 600; }
.chart-container { position: relative; height: 300px; }
.status-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 15px; margin-bottom: 30px; }
.status-stat { background: white; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); text-align: center; }
.status-stat .count { font-size: 28px; font-weight: bold; margin: 0 0 5px 0; }
.status-stat .label { font-size: 12px; color: #718096; text-transform: uppercase; font-weight: 600; }
.status-stat.pending .count { color: #f59e0b; }
.status-stat.processing .count { color: #3b82f6; }
.status-stat.completed .count { color: #10b981; }
.status-stat.failed .count { color: #ef4444; }
.status-stat.cancelled .count { color: #6b7280; }
.activity-section { background: white; border-radius: 8px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
.activity-section h2 { font-size: 20px; color: #1a202c; margin: 0 0 20px 0; }
.activity-table { width: 100%; border-collapse: collapse; }
.activity-table th { text-align: left; padding: 12px; background: #f7fafc; color: #4a5568; font-size: 14px; font-weight: 600; border-bottom: 2px solid #e2e8f0; }
.activity-table td { padding: 12px; border-bottom: 1px solid #e2e8f0; color: #2d3748; font-size: 14px; }
.status-badge { display: inline-block; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; }
.status-pending { background: #fef3c7; color: #92400e; }
.status-processing { background: #dbeafe; color: #1e40af; }
.status-completed { background: #d1fae5; color: #065f46; }
.status-failed { background: #fee2e2; color: #991b1b; }
.status-cancelled { background: #e5e7eb; color: #374151; }
@media(max-width: 1024px) {
.charts-grid { grid-template-columns: 1fr; }
.status-grid { grid-template-columns: repeat(3, 1fr); }
}
@media(max-width: 768px) {
.status-grid { grid-template-columns: repeat(2, 1fr); }
}
</style>

<div class="admin-container">
<div class="admin-page-header">
<div>
<h1>Admin Dashboard</h1>
<p>Manage overall platform operations</p>
</div>
</div>

<!-- Main Stats -->
<div class="stats-grid">
<div class="stat-card clickable">
<a href="<?php echo e(route('admin.users.index')); ?>">
<h3>Total Users</h3>
<div class="value"><?php echo e($stats['total_users']); ?></div>
</a>
</div>
<div class="stat-card clickable">
<a href="<?php echo e(route('admin.transfers.index')); ?>?status=pending,processing">
<h3>Active Transfers</h3>
<div class="value"><?php echo e($stats['active_transfers']); ?></div>
</a>
</div>
<div class="stat-card">
<h3>Pending Verifications</h3>
<div class="value"><?php echo e($stats['pending_verifications']); ?></div>
</div>
<div class="stat-card clickable">
<a href="<?php echo e(route('admin.agents.index')); ?>">
<h3>Pending Agents</h3>
<div class="value"><?php echo e($stats['pending_agents']); ?></div>
</a>
</div>
<div class="stat-card clickable">
<a href="<?php echo e(route('admin.settings')); ?>">
<h3>⚙️ Settings</h3>
<div class="value" style="font-size: 18px; color: #3b82f6;">Configure</div>
</a>
</div>
<div class="stat-card clickable">
<a href="<?php echo e(route('admin.compliance')); ?>">
<h3>🛡️ Compliance</h3>
<div class="value" style="font-size: 18px; color: #10b981;">Monitor</div>
</a>
</div>
<div class="stat-card clickable">
<a href="<?php echo e(route('admin.exchange-rates')); ?>">
<h3>💱 Exchange Rates</h3>
<div class="value" style="font-size: 18px; color: #8b5cf6;">Manage</div>
</a>
</div>
<div class="stat-card clickable">
<a href="<?php echo e(route('admin.fraud.index')); ?>">
<h3>🚨 Fraud Detection</h3>
<div class="value" style="font-size: 18px; color: #dc2626;">Protect</div>
</a>
</div>
<div class="stat-card clickable">
<a href="<?php echo e(route('admin.reports.index')); ?>">
<h3>📊 Reports</h3>
<div class="value" style="font-size: 18px; color: #6366f1;">Analytics</div>
</a>
</div>
</div>

<!-- Revenue Stats -->
<div class="revenue-grid">
<div class="revenue-card">
<h3>Total Revenue</h3>
<div class="value">$<?php echo e(number_format($revenueStats['total_revenue'], 2)); ?></div>
<div class="subtext" style="margin-top: 8px; opacity: 0.9;">From <?php echo e($revenueStats['completed_count']); ?> completed transfers</div>
</div>
<div class="revenue-card green">
<h3>Transfer Volume</h3>
<div class="value">$<?php echo e(number_format($revenueStats['total_volume'], 2)); ?></div>
<div class="subtext" style="margin-top: 8px; opacity: 0.9;">Total amount transferred</div>
</div>
<div class="revenue-card blue">
<h3>Completed Transfers</h3>
<div class="value"><?php echo e($revenueStats['completed_count']); ?></div>
<div class="subtext" style="margin-top: 8px; opacity: 0.9;">Successfully processed</div>
</div>
<div class="revenue-card orange">
<h3>Average Fee</h3>
<div class="value">$<?php echo e(number_format($revenueStats['avg_fee'], 2)); ?></div>
<div class="subtext" style="margin-top: 8px; opacity: 0.9;">Per transfer</div>
</div>
</div>

<!-- Transfer Status Breakdown -->
<div class="status-grid">
<div class="status-stat pending">
<div class="count"><?php echo e($transferStats['pending']); ?></div>
<div class="label">Pending</div>
</div>
<div class="status-stat processing">
<div class="count"><?php echo e($transferStats['processing']); ?></div>
<div class="label">Processing</div>
</div>
<div class="status-stat completed">
<div class="count"><?php echo e($transferStats['completed']); ?></div>
<div class="label">Completed</div>
</div>
<div class="status-stat failed">
<div class="count"><?php echo e($transferStats['failed']); ?></div>
<div class="label">Failed</div>
</div>
<div class="status-stat cancelled">
<div class="count"><?php echo e($transferStats['cancelled']); ?></div>
<div class="label">Cancelled</div>
</div>
</div>

<!-- Charts -->
<div class="charts-grid">
<div class="chart-card">
<h2>User Growth (Last 12 Months)</h2>
<div class="chart-container">
<canvas id="userGrowthChart"></canvas>
</div>
</div>
<div class="chart-card">
<h2>Transfer Volume (Last 6 Months)</h2>
<div class="chart-container">
<canvas id="transferVolumeChart"></canvas>
</div>
</div>
</div>

<!-- Recent Activity -->
<div class="activity-section">
<h2>Recent Transfers</h2>
<?php if($recentActivity->count() > 0): ?>
<table class="activity-table">
<thead>
<tr>
<th>Transfer ID</th>
<th>Sender</th>
<th>Receiver</th>
<th>Amount</th>
<th>Status</th>
<th>Date</th>
</tr>
</thead>
<tbody>
<?php $__currentLoopData = $recentActivity; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transfer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<tr>
<td>#<?php echo e($transfer->id); ?></td>
<td><?php echo e($transfer->sender->name ?? 'N/A'); ?></td>
<td><?php echo e($transfer->beneficiary->full_name ?? 'N/A'); ?></td>
<td>$<?php echo e(number_format($transfer->amount, 2)); ?></td>
<td>
<span class="status-badge status-<?php echo e($transfer->status); ?>">
<?php echo e(ucfirst($transfer->status)); ?>

</span>
</td>
<td><?php echo e($transfer->created_at->format('M d, Y H:i')); ?></td>
</tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</tbody>
</table>
<?php else: ?>
<p style="color: #718096; text-align: center; padding: 40px 0;">No recent activity</p>
<?php endif; ?>
</div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// User Growth Chart
const userGrowthCtx = document.getElementById('userGrowthChart').getContext('2d');
new Chart(userGrowthCtx, {
type: 'line',
data: {
labels: <?php echo json_encode(array_column($userGrowth, 'month'), 512) ?>,
datasets: [{
label: 'New Users',
data: <?php echo json_encode(array_column($userGrowth, 'count'), 512) ?>,
borderColor: '#3b82f6',
backgroundColor: 'rgba(59, 130, 246, 0.1)',
tension: 0.4,
fill: true
}]
},
options: {
responsive: true,
maintainAspectRatio: false,
plugins: {
legend: { display: false }
},
scales: {
y: { beginAtZero: true, ticks: { precision: 0 } }
}
}
});

// Transfer Volume Chart
const transferVolumeCtx = document.getElementById('transferVolumeChart').getContext('2d');
new Chart(transferVolumeCtx, {
type: 'bar',
data: {
labels: <?php echo json_encode(array_column($transferVolume, 'month'), 512) ?>,
datasets: [{
label: 'Transfer Volume ($)',
data: <?php echo json_encode(array_column($transferVolume, 'volume'), 512) ?>,
backgroundColor: '#10b981',
borderRadius: 6
}]
},
options: {
responsive: true,
maintainAspectRatio: false,
plugins: {
legend: { display: false }
},
scales: {
y: { beginAtZero: true }
}
}
});
</script>

</main></body></html>
<?php /**PATH C:\xampp\htdocs\money-transfer\WebProject\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>