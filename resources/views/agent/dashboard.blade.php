<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent Dashboard - SwiftPay</title>
    <link rel="stylesheet" href="{{ asset('css/agent.css') }}">
</head>
<body>

<div class="dashboard-container">
    <!-- Header Section -->
    <div class="dashboard-header">
        <div>
            <h1>Agent Dashboard</h1>
            <p class="welcome-text">Welcome back, <strong>{{ $agent->store_name }}</strong></p>
        </div>
        <div class="header-actions">
            <button id="toggle-panel" class="notification-btn">
                <span class="notification-icon">🔔</span>
                @if($notifications->where('read', false)->count() > 0)
                    <span class="notification-badge">{{ $notifications->where('read', false)->count() }}</span>
                @endif
            </button>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success">
            <span class="alert-icon">✓</span>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">
            <span class="alert-icon">⚠</span>
            {{ session('error') }}
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card stat-pending">
            <div class="stat-icon">⏳</div>
            <div class="stat-content">
                <div class="stat-label">Pending</div>
                <div class="stat-value">{{ $stats['pending'] ?? 0 }}</div>
            </div>
        </div>
        <div class="stat-card stat-processing">
            <div class="stat-icon">⚡</div>
            <div class="stat-content">
                <div class="stat-label">Processing</div>
                <div class="stat-value">{{ $stats['processing'] ?? 0 }}</div>
            </div>
        </div>
        <div class="stat-card stat-completed">
            <div class="stat-icon">✓</div>
            <div class="stat-content">
                <div class="stat-label">Completed</div>
                <div class="stat-value">{{ $stats['completed'] ?? 0 }}</div>
            </div>
        </div>
        <div class="stat-card stat-commissions">
            <div class="stat-icon">💰</div>
            <div class="stat-content">
                <div class="stat-label">Total Commissions</div>
                <div class="stat-value">${{ number_format($stats['commissions'] ?? 0, 2) }}</div>
            </div>
        </div>
    </div>

    <!-- Transfers Table -->
    <div class="table-container">
        <div class="table-header">
            <h2>Assigned Transfers</h2>
            <div class="table-actions">
                <button onclick="location.reload()" class="btn-refresh">
                    <span>🔄</span> Refresh
                </button>
            </div>
        </div>

        @if($transfers->isEmpty())
            <div class="empty-state">
                <div class="empty-icon">📦</div>
                <h3>No Transfers Yet</h3>
                <p>You don't have any assigned transfers at the moment.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="transfers-table">
                    <thead>
                        <tr>
                            <th>Transfer ID</th>
                            <th>Sender</th>
                            <th>Beneficiary</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($transfers as $transfer)
                        <tr>
                            <td data-label="ID">#{{ $transfer->id }}</td>
                            <td data-label="Sender">{{ $transfer->sender->name }}</td>
                            <td data-label="Beneficiary">{{ $transfer->beneficiary->full_name }}</td>
                            <td data-label="Amount">
                                <strong>{{ number_format($transfer->amount, 2) }} {{ $transfer->source_currency }}</strong>
                            </td>
                            <td data-label="Status">
                                <span class="status-badge status-{{ $transfer->status }}">
                                    {{ ucfirst($transfer->status) }}
                                </span>
                            </td>
                            <td data-label="Actions" class="action-cell">
                                @if($transfer->status === 'pending')
                                    <form action="{{ route('agent.transfer.process', $transfer->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn-process">Process</button>
                                    </form>
                                @elseif($transfer->status === 'processing')
                                    <form action="{{ route('agent.transfer.complete', $transfer->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn-complete">Complete Payout</button>
                                    </form>
                                @else
                                    <span class="text-muted">No action needed</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Action Buttons -->
    <div class="bottom-actions">
        <button onclick="window.location='{{ route('agent.commissions') }}'" class="btn-secondary">
            💼 View Commissions
        </button>
        <button onclick="window.location='{{ route('home') }}'" class="btn-outline">
            🏠 Back to Home
        </button>
    </div>
</div>

<!-- Notifications Panel -->
<div id="notification-panel" class="notification-panel">
    <div class="panel-header">
        <h3>Notifications</h3>
        <button id="close-panel" class="close-btn">&times;</button>
    </div>
    <div class="panel-body">
        @if($notifications->isEmpty())
            <div class="empty-notifications">
                <div class="empty-icon">🔔</div>
                <p>No new notifications</p>
            </div>
        @else
            <ul class="notification-list">
                @foreach($notifications as $note)
                    <li class="notification-item {{ $note->read ? 'read' : 'unread' }}">
                        <div class="notification-content">
                            <p>{{ $note->message }}</p>
                            <span class="notification-time">{{ $note->created_at->diffForHumans() }}</span>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>

<div id="overlay" class="overlay"></div>







<script>
const panel = document.getElementById('notification-panel');
const toggleBtn = document.getElementById('toggle-panel');
const closeBtn = document.getElementById('close-panel');
const overlay = document.getElementById('overlay');

toggleBtn.addEventListener('click', () => {
    panel.classList.add('active');
    overlay.classList.add('active');
});

closeBtn.addEventListener('click', () => {
    panel.classList.remove('active');
    overlay.classList.remove('active');
});

overlay.addEventListener('click', () => {
    panel.classList.remove('active');
    overlay.classList.remove('active');
});
</script>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 20px;
}

.dashboard-container {
    max-width: 1400px;
    margin: 0 auto;
}

/* Header */
.dashboard-header {
    background: white;
    padding: 24px 32px;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    margin-bottom: 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.dashboard-header h1 {
    font-size: 28px;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 4px;
}

.welcome-text {
    font-size: 14px;
    color: #6b7280;
}

.header-actions {
    display: flex;
    gap: 12px;
}

.notification-btn {
    position: relative;
    background: #f3f4f6;
    border: none;
    border-radius: 12px;
    padding: 12px 16px;
    cursor: pointer;
    transition: all 0.3s;
    font-size: 20px;
}

.notification-btn:hover {
    background: #e5e7eb;
    transform: translateY(-2px);
}

.notification-badge {
    position: absolute;
    top: 6px;
    right: 6px;
    background: #ef4444;
    color: white;
    border-radius: 10px;
    padding: 2px 6px;
    font-size: 10px;
    font-weight: 600;
}

/* Alerts */
.alert {
    padding: 16px 20px;
    border-radius: 12px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 12px;
    font-weight: 500;
}

.alert-success {
    background: #d1fae5;
    color: #065f46;
    border: 1px solid #6ee7b7;
}

.alert-error {
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #fca5a5;
}

.alert-icon {
    font-size: 20px;
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 24px;
}

.stat-card {
    background: white;
    padding: 24px;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    display: flex;
    align-items: center;
    gap: 16px;
    transition: transform 0.3s, box-shadow 0.3s;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
}

.stat-icon {
    font-size: 36px;
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
}

.stat-pending .stat-icon { background: #fef3c7; }
.stat-processing .stat-icon { background: #dbeafe; }
.stat-completed .stat-icon { background: #d1fae5; }
.stat-commissions .stat-icon { background: #ede9fe; }

.stat-label {
    font-size: 13px;
    color: #6b7280;
    font-weight: 500;
    margin-bottom: 4px;
}

.stat-value {
    font-size: 28px;
    font-weight: 700;
    color: #1a1a1a;
}

/* Table Container */
.table-container {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    margin-bottom: 24px;
}

.table-header {
    padding: 24px 32px;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.table-header h2 {
    font-size: 20px;
    font-weight: 600;
    color: #1a1a1a;
}

.btn-refresh {
    background: #f3f4f6;
    border: none;
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-refresh:hover {
    background: #e5e7eb;
}

.table-responsive {
    overflow-x: auto;
}

.transfers-table {
    width: 100%;
    border-collapse: collapse;
}

.transfers-table thead {
    background: #f9fafb;
}

.transfers-table th {
    padding: 16px 24px;
    text-align: left;
    font-weight: 600;
    font-size: 13px;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.transfers-table tbody tr {
    border-bottom: 1px solid #f3f4f6;
    transition: background 0.2s;
}

.transfers-table tbody tr:hover {
    background: #f9fafb;
}

.transfers-table td {
    padding: 20px 24px;
    font-size: 14px;
    color: #374151;
}

.status-badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.status-pending {
    background: #fef3c7;
    color: #92400e;
}

.status-processing {
    background: #dbeafe;
    color: #1e40af;
}

.status-completed {
    background: #d1fae5;
    color: #065f46;
}

.action-cell form {
    display: inline;
}

.btn-process, .btn-complete {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-process:hover, .btn-complete:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.text-muted {
    color: #9ca3af;
    font-size: 13px;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
}

.empty-icon {
    font-size: 64px;
    margin-bottom: 16px;
}

.empty-state h3 {
    font-size: 20px;
    color: #374151;
    margin-bottom: 8px;
}

.empty-state p {
    color: #6b7280;
    font-size: 14px;
}

/* Bottom Actions */
.bottom-actions {
    display: flex;
    gap: 16px;
    justify-content: center;
}

.btn-secondary, .btn-outline {
    padding: 14px 28px;
    border-radius: 12px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-secondary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
}

.btn-secondary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
}

.btn-outline {
    background: white;
    color: #667eea;
    border: 2px solid #667eea;
}

.btn-outline:hover {
    background: #667eea;
    color: white;
}

/* Notification Panel */
.notification-panel {
    position: fixed;
    top: 0;
    right: -400px;
    width: 400px;
    height: 100vh;
    background: white;
    box-shadow: -4px 0 20px rgba(0, 0, 0, 0.15);
    transition: right 0.3s ease;
    z-index: 1001;
    display: flex;
    flex-direction: column;
}

.notification-panel.active {
    right: 0;
}

.panel-header {
    padding: 24px;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.panel-header h3 {
    font-size: 18px;
    font-weight: 600;
    color: #1a1a1a;
}

.close-btn {
    background: none;
    border: none;
    font-size: 28px;
    color: #9ca3af;
    cursor: pointer;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    transition: all 0.2s;
}

.close-btn:hover {
    background: #f3f4f6;
    color: #374151;
}

.panel-body {
    flex: 1;
    overflow-y: auto;
}

.notification-list {
    list-style: none;
}

.notification-item {
    padding: 16px 24px;
    border-bottom: 1px solid #f3f4f6;
}

.notification-item.unread {
    background: #f0f9ff;
}

.notification-content p {
    font-size: 14px;
    color: #374151;
    margin-bottom: 8px;
}

.notification-time {
    font-size: 12px;
    color: #9ca3af;
}

.empty-notifications {
    text-align: center;
    padding: 60px 20px;
}

.empty-notifications .empty-icon {
    font-size: 48px;
    margin-bottom: 12px;
}

.empty-notifications p {
    color: #9ca3af;
    font-size: 14px;
}

.overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s;
    z-index: 1000;
}

.overlay.active {
    opacity: 1;
    visibility: visible;
}

/* Responsive */
@media (max-width: 768px) {
    .dashboard-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .bottom-actions {
        flex-direction: column;
    }
    
    .notification-panel {
        width: 100%;
        right: -100%;
    }
    
    .transfers-table thead {
        display: none;
    }
    
    .transfers-table tbody tr {
        display: block;
        margin-bottom: 16px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
    }
    
    .transfers-table td {
        display: flex;
        justify-content: space-between;
        padding: 12px 16px;
        border-bottom: 1px solid #f3f4f6;
    }
    
    .transfers-table td:last-child {
        border-bottom: none;
    }
    
    .transfers-table td::before {
        content: attr(data-label);
        font-weight: 600;
        color: #6b7280;
    }
}
</style>

</body>
</html>


