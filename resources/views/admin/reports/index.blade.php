@include('includes.header')

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
                Showing data from {{ $startDate->format('M d, Y') }} to {{ $endDate->format('M d, Y') }}
            </p>
        </div>
        <div class="period-selector">
            <a href="{{ route('admin.reports.index', ['period' => 'day']) }}" class="period-btn {{ $period === 'day' ? 'active' : '' }}">Today</a>
            <a href="{{ route('admin.reports.index', ['period' => 'week']) }}" class="period-btn {{ $period === 'week' ? 'active' : '' }}">Week</a>
            <a href="{{ route('admin.reports.index', ['period' => 'month']) }}" class="period-btn {{ $period === 'month' ? 'active' : '' }}">Month</a>
            <a href="{{ route('admin.reports.index', ['period' => 'year']) }}" class="period-btn {{ $period === 'year' ? 'active' : '' }}">Year</a>
            <a href="{{ route('admin.reports.index', ['period' => 'all']) }}" class="period-btn {{ $period === 'all' ? 'active' : '' }}">All Time</a>
        </div>
    </div>

    <!-- Transaction Statistics -->
    <div class="stats-grid">
        <div class="stat-card highlight">
            <h3>Total Transactions</h3>
            <div class="value">{{ number_format($transactionStats['total_transactions']) }}</div>
            <div class="subtext">{{ $transactionStats['success_rate'] }}% success rate</div>
        </div>
        <div class="stat-card success">
            <h3>Completed</h3>
            <div class="value">{{ number_format($transactionStats['completed']) }}</div>
            <div class="subtext">Avg: {{ $transactionStats['avg_processing_time'] }} min</div>
        </div>
        <div class="stat-card warning">
            <h3>Pending / Processing</h3>
            <div class="value">{{ number_format($transactionStats['pending'] + $transactionStats['processing']) }}</div>
            <div class="subtext">{{ $transactionStats['pending'] }} pending, {{ $transactionStats['processing'] }} processing</div>
        </div>
        <div class="stat-card">
            <h3 style="color: #dc2626;">Failed / Cancelled</h3>
            <div class="value" style="color: #dc2626;">{{ number_format($transactionStats['failed'] + $transactionStats['cancelled']) }}</div>
            <div class="subtext" style="color: #6b7280;">{{ $transactionStats['failed'] }} failed, {{ $transactionStats['cancelled'] }} cancelled</div>
        </div>
    </div>

    <!-- Revenue Statistics -->
    <div class="section-card">
        <h2>
            💰 Revenue Overview
            <a href="{{ route('admin.reports.export', ['type' => 'revenue', 'period' => $period]) }}" class="export-btn">📥 Export CSV</a>
        </h2>
        <div class="stats-grid">
            <div>
                <h4 style="margin: 0 0 8px 0; color: #6b7280; font-size: 14px;">TOTAL REVENUE (FEES)</h4>
                <div style="font-size: 28px; font-weight: bold; color: #10b981;">${{ number_format($revenueStats['total_revenue'], 2) }}</div>
            </div>
            <div>
                <h4 style="margin: 0 0 8px 0; color: #6b7280; font-size: 14px;">TOTAL VOLUME PROCESSED</h4>
                <div style="font-size: 28px; font-weight: bold; color: #3b82f6;">${{ number_format($revenueStats['total_volume'], 2) }}</div>
            </div>
            <div>
                <h4 style="margin: 0 0 8px 0; color: #6b7280; font-size: 14px;">AVG TRANSACTION VALUE</h4>
                <div style="font-size: 28px; font-weight: bold; color: #8b5cf6;">${{ number_format($revenueStats['avg_transaction_value'], 2) }}</div>
            </div>
            <div>
                <h4 style="margin: 0 0 8px 0; color: #6b7280; font-size: 14px;">COMPLETED TRANSACTIONS</h4>
                <div style="font-size: 28px; font-weight: bold; color: #f59e0b;">{{ number_format($revenueStats['transaction_count']) }}</div>
            </div>
        </div>
    </div>

    <!-- User Statistics -->
    <div class="section-card">
        <h2>
            👥 User Activity
            <a href="{{ route('admin.reports.export', ['type' => 'users', 'period' => $period]) }}" class="export-btn">📥 Export CSV</a>
        </h2>
        <div class="stats-grid">
            <div>
                <h4 style="margin: 0 0 8px 0; color: #6b7280; font-size: 14px;">NEW USERS</h4>
                <div style="font-size: 28px; font-weight: bold; color: #3b82f6;">{{ number_format($userStats['new_users']) }}</div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: {{ min(($userStats['new_users'] / max($userStats['total_users'], 1)) * 100, 100) }}%;"></div>
                </div>
            </div>
            <div>
                <h4 style="margin: 0 0 8px 0; color: #6b7280; font-size: 14px;">ACTIVE USERS</h4>
                <div style="font-size: 28px; font-weight: bold; color: #10b981;">{{ number_format($userStats['active_users']) }}</div>
                <div style="font-size: 14px; color: #6b7280; margin-top: 4px;">Made at least 1 transfer</div>
            </div>
            <div>
                <h4 style="margin: 0 0 8px 0; color: #6b7280; font-size: 14px;">TOTAL USERS</h4>
                <div style="font-size: 28px; font-weight: bold; color: #8b5cf6;">{{ number_format($userStats['total_users']) }}</div>
                <div style="font-size: 14px; color: #6b7280; margin-top: 4px;">{{ $userStats['verified_users'] }} verified</div>
            </div>
            <div>
                <h4 style="margin: 0 0 8px 0; color: #6b7280; font-size: 14px;">BLOCKED USERS</h4>
                <div style="font-size: 28px; font-weight: bold; color: #dc2626;">{{ number_format($userStats['blocked_users']) }}</div>
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
                    @php $total = array_sum($speedDistribution); @endphp
                    @forelse($speedDistribution as $speed => $count)
                        <tr>
                            <td><strong>{{ ucfirst($speed) }}</strong></td>
                            <td>{{ number_format($count) }}</td>
                            <td>
                                <span class="badge badge-info">{{ $total > 0 ? round(($count / $total) * 100, 1) : 0 }}%</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" style="text-align: center; padding: 40px; color: #9ca3af;">No data available</td></tr>
                    @endforelse
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
                    @forelse($topRoutes as $route)
                        <tr>
                            <td><strong>{{ $route->route }}</strong></td>
                            <td>{{ number_format($route->count) }}</td>
                            <td>${{ number_format($route->total_amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" style="text-align: center; padding: 40px; color: #9ca3af;">No data available</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Daily Trends Chart -->
    <div class="section-card">
        <h2>
            📈 Daily Transaction Trends
            <a href="{{ route('admin.reports.export', ['type' => 'transactions', 'period' => $period]) }}" class="export-btn">📥 Export CSV</a>
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
                <div style="font-size: 28px; font-weight: bold; color: #3b82f6;">{{ number_format($supportStats['total_tickets']) }}</div>
            </div>
            <div>
                <h4 style="margin: 0 0 8px 0; color: #6b7280; font-size: 14px;">OPEN TICKETS</h4>
                <div style="font-size: 28px; font-weight: bold; color: #f59e0b;">{{ number_format($supportStats['open_tickets']) }}</div>
            </div>
            <div>
                <h4 style="margin: 0 0 8px 0; color: #6b7280; font-size: 14px;">CLOSED TICKETS</h4>
                <div style="font-size: 28px; font-weight: bold; color: #10b981;">{{ number_format($supportStats['closed_tickets']) }}</div>
            </div>
            <div>
                <h4 style="margin: 0 0 8px 0; color: #6b7280; font-size: 14px;">HIGH PRIORITY</h4>
                <div style="font-size: 28px; font-weight: bold; color: #dc2626;">{{ number_format($supportStats['high_priority']) }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const ctx = document.getElementById('trendsChart');

const dates = {!! json_encode($dailyTrends->pluck('date')) !!};
const counts = {!! json_encode($dailyTrends->pluck('count')) !!};
const volumes = {!! json_encode($dailyTrends->pluck('volume')) !!};
const fees = {!! json_encode($dailyTrends->pluck('fees')) !!};

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
