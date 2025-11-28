<?php echo $__env->make('includes.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<style>
body { background-color: #f3f4f6; }
.admin-container { max-width: 1400px; margin: 40px auto; padding: 0 20px; }
.page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
.page-header h1 { margin: 0; font-size: 32px; color: #1a202c; }
.period-selector { display: flex; gap: 10px; }
.period-btn { padding: 8px 16px; border: 1px solid #d1d5db; background: white; border-radius: 6px; cursor: pointer; font-weight: 500; text-decoration: none; color: #374151; }
.period-btn.active { background: #3b82f6; color: white; border-color: #3b82f6; }
.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
.stat-card { background: white; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
.stat-card.highlight { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
.stat-card.success { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; }
.stat-card.warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; }
.stat-card h3 { margin: 0 0 8px 0; font-size: 14px; opacity: 0.9; text-transform: uppercase; }
.stat-card .value { font-size: 32px; font-weight: bold; margin-bottom: 8px; }
.stat-card .subtext { font-size: 14px; opacity: 0.8; }
.section-card { background: white; border-radius: 8px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px; }
.section-card h2 { margin: 0 0 20px 0; font-size: 20px; color: #1a202c; display: flex; justify-content: space-between; align-items: center; }
.export-btn { padding: 8px 16px; background: #10b981; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 600; text-decoration: none; display: inline-block; }
.export-btn:hover { background: #059669; }
.chart-container { width: 100%; height: 300px; margin-top: 20px; }
.table { width: 100%; border-collapse: collapse; }
.table th { background: #f3f4f6; padding: 12px; text-align: left; font-weight: 600; color: #374151; border-bottom: 2px solid #e5e7eb; }
.table td { padding: 12px; border-bottom: 1px solid #e5e7eb; }
.table tr:hover { background: #f9fafb; }
.badge { display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; }
.badge-success { background: #d1fae5; color: #065f46; }
.badge-warning { background: #fef3c7; color: #92400e; }
.badge-danger { background: #fee2e2; color: #991b1b; }
.badge-info { background: #dbeafe; color: #1e40af; }
.grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
.progress-bar { width: 100%; height: 8px; background: #e5e7eb; border-radius: 4px; overflow: hidden; margin-top: 8px; }
.progress-fill { height: 100%; background: #3b82f6; transition: width 0.3s; }
@media(max-width: 768px) { .stats-grid { grid-template-columns: 1fr; } .grid-2 { grid-template-columns: 1fr; } }
</style>

<div class="admin-container">
    <div class="page-header">
        <div>
            <h1>📊 Reports & Analytics</h1>
            <p style="color: #6b7280; margin: 8px 0 0 0;">
                Showing data from <?php echo e($startDate->format('M d, Y')); ?> to <?php echo e($endDate->format('M d, Y')); ?>

            </p>
        </div>
        <div class="period-selector">
            <a href="<?php echo e(route('admin.reports.index', ['period' => 'day'])); ?>" class="period-btn <?php echo e($period === 'day' ? 'active' : ''); ?>">Today</a>
            <a href="<?php echo e(route('admin.reports.index', ['period' => 'week'])); ?>" class="period-btn <?php echo e($period === 'week' ? 'active' : ''); ?>">Week</a>
            <a href="<?php echo e(route('admin.reports.index', ['period' => 'month'])); ?>" class="period-btn <?php echo e($period === 'month' ? 'active' : ''); ?>">Month</a>
            <a href="<?php echo e(route('admin.reports.index', ['period' => 'year'])); ?>" class="period-btn <?php echo e($period === 'year' ? 'active' : ''); ?>">Year</a>
            <a href="<?php echo e(route('admin.reports.index', ['period' => 'all'])); ?>" class="period-btn <?php echo e($period === 'all' ? 'active' : ''); ?>">All Time</a>
        </div>
    </div>

    <!-- Transaction Statistics -->
    <div class="stats-grid">
        <div class="stat-card highlight">
            <h3>Total Transactions</h3>
            <div class="value"><?php echo e(number_format($transactionStats['total_transactions'])); ?></div>
            <div class="subtext"><?php echo e($transactionStats['success_rate']); ?>% success rate</div>
        </div>
        <div class="stat-card success">
            <h3>Completed</h3>
            <div class="value"><?php echo e(number_format($transactionStats['completed'])); ?></div>
            <div class="subtext">Avg: <?php echo e($transactionStats['avg_processing_time']); ?> min</div>
        </div>
        <div class="stat-card warning">
            <h3>Pending / Processing</h3>
            <div class="value"><?php echo e(number_format($transactionStats['pending'] + $transactionStats['processing'])); ?></div>
            <div class="subtext"><?php echo e($transactionStats['pending']); ?> pending, <?php echo e($transactionStats['processing']); ?> processing</div>
        </div>
        <div class="stat-card">
            <h3 style="color: #dc2626;">Failed / Cancelled</h3>
            <div class="value" style="color: #dc2626;"><?php echo e(number_format($transactionStats['failed'] + $transactionStats['cancelled'])); ?></div>
            <div class="subtext" style="color: #6b7280;"><?php echo e($transactionStats['failed']); ?> failed, <?php echo e($transactionStats['cancelled']); ?> cancelled</div>
        </div>
    </div>

    <!-- Revenue Statistics -->
    <div class="section-card">
        <h2>
            💰 Revenue Overview
            <a href="<?php echo e(route('admin.reports.export', ['type' => 'revenue', 'period' => $period])); ?>" class="export-btn">📥 Export CSV</a>
        </h2>
        <div class="stats-grid">
            <div>
                <h4 style="margin: 0 0 8px 0; color: #6b7280; font-size: 14px;">TOTAL REVENUE (FEES)</h4>
                <div style="font-size: 28px; font-weight: bold; color: #10b981;">$<?php echo e(number_format($revenueStats['total_revenue'], 2)); ?></div>
            </div>
            <div>
                <h4 style="margin: 0 0 8px 0; color: #6b7280; font-size: 14px;">TOTAL VOLUME PROCESSED</h4>
                <div style="font-size: 28px; font-weight: bold; color: #3b82f6;">$<?php echo e(number_format($revenueStats['total_volume'], 2)); ?></div>
            </div>
            <div>
                <h4 style="margin: 0 0 8px 0; color: #6b7280; font-size: 14px;">AVG TRANSACTION VALUE</h4>
                <div style="font-size: 28px; font-weight: bold; color: #8b5cf6;">$<?php echo e(number_format($revenueStats['avg_transaction_value'], 2)); ?></div>
            </div>
            <div>
                <h4 style="margin: 0 0 8px 0; color: #6b7280; font-size: 14px;">COMPLETED TRANSACTIONS</h4>
                <div style="font-size: 28px; font-weight: bold; color: #f59e0b;"><?php echo e(number_format($revenueStats['transaction_count'])); ?></div>
            </div>
        </div>
    </div>

    <!-- User Statistics -->
    <div class="section-card">
        <h2>
            👥 User Activity
            <a href="<?php echo e(route('admin.reports.export', ['type' => 'users', 'period' => $period])); ?>" class="export-btn">📥 Export CSV</a>
        </h2>
        <div class="stats-grid">
            <div>
                <h4 style="margin: 0 0 8px 0; color: #6b7280; font-size: 14px;">NEW USERS</h4>
                <div style="font-size: 28px; font-weight: bold; color: #3b82f6;"><?php echo e(number_format($userStats['new_users'])); ?></div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: <?php echo e(min(($userStats['new_users'] / max($userStats['total_users'], 1)) * 100, 100)); ?>%;"></div>
                </div>
            </div>
            <div>
                <h4 style="margin: 0 0 8px 0; color: #6b7280; font-size: 14px;">ACTIVE USERS</h4>
                <div style="font-size: 28px; font-weight: bold; color: #10b981;"><?php echo e(number_format($userStats['active_users'])); ?></div>
                <div style="font-size: 14px; color: #6b7280; margin-top: 4px;">Made at least 1 transfer</div>
            </div>
            <div>
                <h4 style="margin: 0 0 8px 0; color: #6b7280; font-size: 14px;">TOTAL USERS</h4>
                <div style="font-size: 28px; font-weight: bold; color: #8b5cf6;"><?php echo e(number_format($userStats['total_users'])); ?></div>
                <div style="font-size: 14px; color: #6b7280; margin-top: 4px;"><?php echo e($userStats['verified_users']); ?> verified</div>
            </div>
            <div>
                <h4 style="margin: 0 0 8px 0; color: #6b7280; font-size: 14px;">BLOCKED USERS</h4>
                <div style="font-size: 28px; font-weight: bold; color: #dc2626;"><?php echo e(number_format($userStats['blocked_users'])); ?></div>
                <div style="font-size: 14px; color: #6b7280; margin-top: 4px;">Security measures</div>
            </div>
        </div>
    </div>

    <div class="grid-2">
        <!-- Transfer Speed Distribution -->
        <div class="section-card">
            <h2>⚡ Transfer Speed Distribution</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Speed Type</th>
                        <th>Count</th>
                        <th>Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $total = array_sum($speedDistribution); ?>
                    <?php $__empty_1 = true; $__currentLoopData = $speedDistribution; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $speed => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><strong><?php echo e(ucfirst($speed)); ?></strong></td>
                            <td><?php echo e(number_format($count)); ?></td>
                            <td>
                                <span class="badge badge-info"><?php echo e($total > 0 ? round(($count / $total) * 100, 1) : 0); ?>%</span>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="3" style="text-align: center; padding: 40px; color: #9ca3af;">No data available</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Top Routes -->
        <div class="section-card">
            <h2>🌍 Top Transfer Routes</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Route</th>
                        <th>Transfers</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $topRoutes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $route): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><strong><?php echo e($route->route); ?></strong></td>
                            <td><?php echo e(number_format($route->count)); ?></td>
                            <td>$<?php echo e(number_format($route->total_amount, 2)); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="3" style="text-align: center; padding: 40px; color: #9ca3af;">No data available</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Daily Trends Chart -->
    <div class="section-card">
        <h2>
            📈 Daily Transaction Trends
            <a href="<?php echo e(route('admin.reports.export', ['type' => 'transactions', 'period' => $period])); ?>" class="export-btn">📥 Export CSV</a>
        </h2>
        <div class="chart-container">
            <canvas id="trendsChart"></canvas>
        </div>
    </div>

    <!-- Support Ticket Statistics -->
    <div class="section-card">
        <h2>🎫 Support Ticket Statistics</h2>
        <div class="stats-grid">
            <div>
                <h4 style="margin: 0 0 8px 0; color: #6b7280; font-size: 14px;">TOTAL TICKETS</h4>
                <div style="font-size: 28px; font-weight: bold; color: #3b82f6;"><?php echo e(number_format($supportStats['total_tickets'])); ?></div>
            </div>
            <div>
                <h4 style="margin: 0 0 8px 0; color: #6b7280; font-size: 14px;">OPEN TICKETS</h4>
                <div style="font-size: 28px; font-weight: bold; color: #f59e0b;"><?php echo e(number_format($supportStats['open_tickets'])); ?></div>
            </div>
            <div>
                <h4 style="margin: 0 0 8px 0; color: #6b7280; font-size: 14px;">CLOSED TICKETS</h4>
                <div style="font-size: 28px; font-weight: bold; color: #10b981;"><?php echo e(number_format($supportStats['closed_tickets'])); ?></div>
            </div>
            <div>
                <h4 style="margin: 0 0 8px 0; color: #6b7280; font-size: 14px;">HIGH PRIORITY</h4>
                <div style="font-size: 28px; font-weight: bold; color: #dc2626;"><?php echo e(number_format($supportStats['high_priority'])); ?></div>
            </div>
        </div>
    </div>

    <!-- User Feedback & Ratings -->
    <div class="section-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 style="margin: 0;">⭐ User Feedback & Ratings</h2>
            <a href="<?php echo e(route('admin.reports.export', ['type' => 'feedback', 'period' => $period])); ?>" class="export-btn">
                Export Feedback CSV
            </a>
        </div>
        
        <div class="stats-grid" style="margin-bottom: 30px;">
            <div>
                <h4 style="margin: 0 0 8px 0; color: #6b7280; font-size: 14px;">TOTAL FEEDBACK</h4>
                <div style="font-size: 28px; font-weight: bold; color: #3b82f6;"><?php echo e(number_format($feedbackStats['total_feedback'])); ?></div>
            </div>
            <div>
                <h4 style="margin: 0 0 8px 0; color: #6b7280; font-size: 14px;">AVERAGE RATING</h4>
                <div style="font-size: 28px; font-weight: bold; color: #f59e0b;">
                    <?php echo e($feedbackStats['avg_rating']); ?> <span style="font-size: 18px;">/ 5.0</span>
                </div>
            </div>
            <div>
                <h4 style="margin: 0 0 8px 0; color: #6b7280; font-size: 14px;">POSITIVE FEEDBACK</h4>
                <div style="font-size: 28px; font-weight: bold; color: #10b981;"><?php echo e(number_format($feedbackStats['positive_sentiment'])); ?></div>
                <div style="font-size: 12px; color: #6b7280; margin-top: 4px;">4-5 stars</div>
            </div>
            <div>
                <h4 style="margin: 0 0 8px 0; color: #6b7280; font-size: 14px;">NEGATIVE FEEDBACK</h4>
                <div style="font-size: 28px; font-weight: bold; color: #dc2626;"><?php echo e(number_format($feedbackStats['negative_sentiment'])); ?></div>
                <div style="font-size: 12px; color: #6b7280; margin-top: 4px;">1-2 stars</div>
            </div>
        </div>

        <!-- Rating Distribution -->
        <div style="margin-bottom: 30px;">
            <h3 style="margin: 0 0 15px 0; font-size: 16px; color: #374151;">Rating Distribution</h3>
            <div style="display: grid; gap: 12px;">
                <?php $__currentLoopData = [5 => 'five_star', 4 => 'four_star', 3 => 'three_star', 2 => 'two_star', 1 => 'one_star']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stars => $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 80px; font-weight: 500; color: #374151;"><?php echo e($stars); ?> ⭐</div>
                    <div style="flex: 1; background: #f3f4f6; height: 24px; border-radius: 6px; overflow: hidden;">
                        <?php
                            $percentage = $feedbackStats['total_feedback'] > 0 
                                ? ($feedbackStats[$key] / $feedbackStats['total_feedback']) * 100 
                                : 0;
                            $color = $stars >= 4 ? '#10b981' : ($stars == 3 ? '#f59e0b' : '#dc2626');
                        ?>
                        <div style="width: <?php echo e($percentage); ?>%; height: 100%; background: <?php echo e($color); ?>; transition: width 0.3s;"></div>
                    </div>
                    <div style="width: 60px; text-align: right; font-weight: 500; color: #374151;">
                        <?php echo e($feedbackStats[$key]); ?>

                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <!-- Recent Feedback -->
        <?php if(count($feedbackStats['recent_feedback']) > 0): ?>
        <div>
            <h3 style="margin: 0 0 15px 0; font-size: 16px; color: #374151;">Recent Feedback</h3>
            <div style="display: grid; gap: 12px;">
                <?php $__currentLoopData = $feedbackStats['recent_feedback']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feedback): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div style="padding: 15px; background: #f9fafb; border-radius: 8px; border-left: 4px solid #3b82f6;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <div style="font-weight: 600; color: #374151;">
                            <?php echo e($feedback['user_name'] ?? 'Anonymous'); ?>

                        </div>
                        <div style="color: #f59e0b; font-weight: 500;">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <?php if($i <= $feedback['rating']): ?>
                                    ⭐
                                <?php else: ?>
                                    ☆
                                <?php endif; ?>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <div style="color: #6b7280; font-size: 14px; line-height: 1.5;">
                        <?php echo e($feedback['comment'] ?? 'No comment provided'); ?>

                    </div>
                    <div style="margin-top: 8px; font-size: 12px; color: #9ca3af;">
                        <?php echo e(\Carbon\Carbon::parse($feedback['created_at'])->format('M d, Y h:i A')); ?>

                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const ctx = document.getElementById('trendsChart');

const dates = <?php echo json_encode($dailyTrends->pluck('date')); ?>;
const counts = <?php echo json_encode($dailyTrends->pluck('count')); ?>;
const volumes = <?php echo json_encode($dailyTrends->pluck('volume')); ?>;
const fees = <?php echo json_encode($dailyTrends->pluck('fees')); ?>;

new Chart(ctx, {
    type: 'line',
    data: {
        labels: dates,
        datasets: [
            {
                label: 'Transaction Count',
                data: counts,
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                yAxisID: 'y'
            },
            {
                label: 'Volume ($)',
                data: volumes,
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.4,
                yAxisID: 'y1'
            },
            {
                label: 'Fees Collected ($)',
                data: fees,
                borderColor: '#f59e0b',
                backgroundColor: 'rgba(245, 158, 11, 0.1)',
                tension: 0.4,
                yAxisID: 'y1'
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
            mode: 'index',
            intersect: false,
        },
        plugins: {
            legend: {
                position: 'top',
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        let label = context.dataset.label || '';
                        if (label) {
                            label += ': ';
                        }
                        if (context.datasetIndex === 0) {
                            label += context.parsed.y + ' transactions';
                        } else {
                            label += '$' + context.parsed.y.toFixed(2);
                        }
                        return label;
                    }
                }
            }
        },
        scales: {
            y: {
                type: 'linear',
                display: true,
                position: 'left',
                title: {
                    display: true,
                    text: 'Transaction Count'
                }
            },
            y1: {
                type: 'linear',
                display: true,
                position: 'right',
                title: {
                    display: true,
                    text: 'Amount ($)'
                },
                grid: {
                    drawOnChartArea: false,
                }
            }
        }
    }
});
</script>

</main></body></html>
<?php /**PATH C:\xampp\htdocs\money-transfer\resources\views/admin/reports/index.blade.php ENDPATH**/ ?>