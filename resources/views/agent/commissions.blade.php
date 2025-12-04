<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commission History - SwiftPay</title>
    <link rel="stylesheet" href="{{ asset('css/agent.css') }}">
</head>
<body>

<div class="commissions-container">
    <!-- Header Section -->
    <div class="page-header">
        <div class="header-content">
            <button onclick="window.location='{{ route('agent.dashboard') }}'" class="back-button">
                ← Back
            </button>
            <div>
                <h1>Commission History</h1>
                <p class="subtitle">Track your earnings from completed transfers</p>
            </div>
        </div>
    </div>

    <!-- Summary Card -->
    <div class="summary-card">
        <div class="summary-icon">💰</div>
        <div class="summary-content">
            <div class="summary-label">Total Commissions Earned</div>
            <div class="summary-value">${{ number_format($totalCommission, 2) }}</div>
            <div class="summary-info">From {{ $completedTransfers->count() }} completed transfer(s)</div>
        </div>
    </div>

    <!-- Commissions Table -->
    <div class="table-container">
        <div class="table-header">
            <h2>Completed Transfers</h2>
            <button onclick="location.reload()" class="btn-refresh">
                <span>🔄</span> Refresh
            </button>
        </div>

        @if($completedTransfers->isEmpty())
            <div class="empty-state">
                <div class="empty-icon">📊</div>
                <h3>No Commissions Yet</h3>
                <p>You haven't completed any transfers yet. Start processing transfers to earn commissions.</p>
                <button onclick="window.location='{{ route('agent.dashboard') }}'" class="btn-primary">
                    Go to Dashboard
                </button>
            </div>
        @else
            <div class="table-responsive">
                <table class="commissions-table">
                    <thead>
                        <tr>
                            <th>Transfer ID</th>
                            <th>Recipient</th>
                            <th>Payout Amount</th>
                            <th>Commission Rate</th>
                            <th>Commission Earned</th>
                            <th>Completed At</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($completedTransfers as $t)
                        <tr>
                            <td data-label="Transfer ID">
                                <span class="transfer-id">#{{ $t->id }}</span>
                            </td>
                            <td data-label="Recipient">
                                <strong>{{ $t->beneficiary->full_name ?? 'N/A' }}</strong>
                            </td>
                            <td data-label="Payout Amount">
                                <span class="amount">{{ number_format($t->payout_amount, 2) }} {{ $t->target_currency }}</span>
                            </td>
                            <td data-label="Commission Rate">
                                <span class="rate-badge">{{ $t->agent ? number_format($t->agent->commission_rate ?? 0, 2) : '0.00' }}%</span>
                            </td>
                            <td data-label="Commission Earned">
                                <span class="commission-amount">
                                    {{ number_format($t->agent_commission ?? 0, 2) }} {{ $t->target_currency }}
                                </span>
                            </td>
                            <td data-label="Completed At">
                                <span class="date-time">{{ $t->completed_at ? $t->completed_at->format('M d, Y') : 'N/A' }}</span>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Total Summary -->
            <div class="table-footer">
                <div class="footer-content">
                    <span class="footer-label">Total Commission Earned:</span>
                    <span class="footer-value">${{ number_format($totalCommission, 2) }}</span>
                </div>
            </div>
        @endif
    </div>

    <!-- Action Button -->
    <div class="bottom-action">
        <button onclick="window.location='{{ route('agent.dashboard') }}'" class="btn-back">
            Back to Dashboard
        </button>
    </div>
</div>

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

.commissions-container {
    max-width: 1400px;
    margin: 0 auto;
}

/* Header */
.page-header {
    background: white;
    padding: 24px 32px;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    margin-bottom: 24px;
}

.header-content {
    display: flex;
    align-items: center;
    gap: 20px;
}

.back-button {
    background: #f3f4f6;
    border: none;
    padding: 10px 16px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    color: #374151;
}

.back-button:hover {
    background: #e5e7eb;
    transform: translateX(-4px);
}

.page-header h1 {
    font-size: 28px;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 4px;
}

.subtitle {
    font-size: 14px;
    color: #6b7280;
}

/* Summary Card */
.summary-card {
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    padding: 32px;
    border-radius: 16px;
    box-shadow: 0 8px 30px rgba(251, 191, 36, 0.3);
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 24px;
    color: white;
}

.summary-icon {
    font-size: 56px;
    width: 80px;
    height: 80px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(10px);
}

.summary-label {
    font-size: 14px;
    opacity: 0.9;
    margin-bottom: 8px;
    font-weight: 500;
}

.summary-value {
    font-size: 48px;
    font-weight: 700;
    margin-bottom: 4px;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.summary-info {
    font-size: 13px;
    opacity: 0.85;
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
    color: #374151;
}

.btn-refresh:hover {
    background: #e5e7eb;
}

.table-responsive {
    overflow-x: auto;
}

.commissions-table {
    width: 100%;
    border-collapse: collapse;
}

.commissions-table thead {
    background: #f9fafb;
}

.commissions-table th {
    padding: 16px 24px;
    text-align: left;
    font-weight: 600;
    font-size: 13px;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.commissions-table tbody tr {
    border-bottom: 1px solid #f3f4f6;
    transition: background 0.2s;
}

.commissions-table tbody tr:hover {
    background: #f9fafb;
}

.commissions-table td {
    padding: 20px 24px;
    font-size: 14px;
    color: #374151;
}

.transfer-id {
    font-weight: 600;
    color: #667eea;
}

.amount {
    font-weight: 600;
    color: #1a1a1a;
}

.rate-badge {
    display: inline-block;
    background: #dbeafe;
    color: #1e40af;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 13px;
    font-weight: 600;
}

.commission-amount {
    font-weight: 700;
    color: #059669;
    font-size: 15px;
}

.date-time {
    color: #6b7280;
    font-size: 13px;
}

/* Table Footer */
.table-footer {
    background: #f9fafb;
    padding: 20px 32px;
    border-top: 2px solid #e5e7eb;
}

.footer-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.footer-label {
    font-size: 16px;
    font-weight: 600;
    color: #374151;
}

.footer-value {
    font-size: 28px;
    font-weight: 700;
    color: #059669;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 80px 20px;
}

.empty-icon {
    font-size: 72px;
    margin-bottom: 20px;
}

.empty-state h3 {
    font-size: 22px;
    color: #374151;
    margin-bottom: 12px;
    font-weight: 600;
}

.empty-state p {
    color: #6b7280;
    font-size: 15px;
    margin-bottom: 24px;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 12px 28px;
    border-radius: 10px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
}

/* Bottom Action */
.bottom-action {
    text-align: center;
}

.btn-back {
    background: white;
    color: #667eea;
    border: 2px solid #667eea;
    padding: 14px 32px;
    border-radius: 12px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-back:hover {
    background: #667eea;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
}

/* Responsive */
@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }
    
    .summary-card {
        flex-direction: column;
        text-align: center;
    }
    
    .summary-value {
        font-size: 36px;
    }
    
    .table-header {
        flex-direction: column;
        gap: 16px;
        align-items: flex-start;
    }
    
    .commissions-table thead {
        display: none;
    }
    
    .commissions-table tbody tr {
        display: block;
        margin-bottom: 16px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
    }
    
    .commissions-table td {
        display: flex;
        justify-content: space-between;
        padding: 12px 16px;
        border-bottom: 1px solid #f3f4f6;
    }
    
    .commissions-table td:last-child {
        border-bottom: none;
    }
    
    .commissions-table td::before {
        content: attr(data-label);
        font-weight: 600;
        color: #6b7280;
        font-size: 13px;
    }
    
    .footer-content {
        flex-direction: column;
        gap: 12px;
    }
}
</style>

</body>
</html>
