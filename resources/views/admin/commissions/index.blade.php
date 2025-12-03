@include('includes.header')

<style>
    body { background-color: #eef1f5; }
    .admin-container { max-width: 1500px;
         margin: 40px auto; 
         padding: 0 20px;
         
         }

    /* Header */
    .page-header h1 { font-size: 1.8rem; font-weight: 700; color: #1e2a3a; }
    .page-header p { font-size: 0.95rem; color: #6c757d; }
    .btn-icon { display: flex; align-items: center; gap: 6px; }

    /* Stat Cards */
    .stat-cards { display: flex; gap: 25px; margin-bottom: 35px; }
    .stat-card {
        background: #fff;
        border-radius: 12px;
        padding: 24px;
        border: 1px solid #e1e5eb;
        transition: 0.2s ease;
        flex: 1;
        margin-top: 20px;
    }
    .stat-value { font-size: 1.8rem; font-weight: 700; color: #1f49d8; }
    .stat-label { font-size: 0.85rem; text-transform: uppercase; color: #889099; margin-bottom: 6px; }

    /* Table Card */
    .card { border-radius: 12px; overflow: hidden; border: 1px solid #e3e7ee; }
    .card-header {
        background: #111827;
        color: white;
        padding: 15px 20px;
        font-weight: 600;
        font-size: 1.05rem;
    }

    /* Table */
    .table-striped tbody tr:nth-child(odd) {
        background-color: #f5f7fa;
    }
    .table th { text-transform: uppercase; font-size: 0.75rem; color: #6c757d; border-top: none; }
    .table td {
        vertical-align: middle;
        padding-top: 18px !important;
        padding-bottom: 18px !important;
        font-size: 0.9rem;
    }

    /* Neutral badges */
    .badge {
        background: none !important;
        border: 1px solid #cbd5e1 !important;
        color: #374151 !important;
        padding: 6px 10px;
        border-radius: 6px;
        font-size: 0.7rem;
        font-weight: 600;
    }

    /* Neutral View Button */
    .btn-dark {
        background: none !important;
        border: none !important;
        color: #2563eb !important;
        font-weight: 600;
    }
    .btn-dark:hover {
        text-decoration: underline;
        background: none !important;
    }

.full-report-btn {
    background: #2563eb;
    color: white !important;
    padding: 10px 18px;
    font-weight: 600;
    border-radius: 8px;
    border: none;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    transition: 0.2s ease;

}

.full-report-btn:hover {
    background: #1b4fc1;
    transform: translateY(-2px);
    color: white !important;
}
</style>

<div class="admin-container">

    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1>Commission Management</h1>
            <p>Monitor agent activity, transfers and commission earnings</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.commissions.report') }}" class="full-report-btn">
    <i class="fas fa-chart-line"></i> Full Report
</a>

        </div>
    </div>

    @if(!$agents->isEmpty())
    <div class="stat-cards">
        <div class="stat-card">
            <div class="stat-label">Agents</div>
            <div class="stat-value">{{ count($agents) }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Commission (Month)</div>
            <div class="stat-value">${{ number_format($agents->sum('total_commission'), 2) }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Transfers</div>
            <div class="stat-value">{{ $agents->sum('total_transfers') }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Avg Commission</div>
            <div class="stat-value">
                ${{ number_format($agents->count() ? $agents->sum('total_commission')/$agents->count() : 0,2) }}
            </div>
        </div>
    </div>
    @endif

    <div class="card">
        <div class="card-header">Agents & Commission Overview</div>
        <div class="table-responsive p-2">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>Agent</th>
                        <th>Store</th>
                        <th>Rate</th>
                        <th>Type</th>
                        <th class="text-end">Transfers</th>
                        <th class="text-end">Transfer Amount</th>
                        <th class="text-end">Commission</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($agents as $agent)
                <tr>
                    <td>
                        <strong>{{ $agent['user']['name'] }}</strong>
                        <div class="text-muted" style="font-size: 0.75rem;">{{ $agent['user']['email'] }}</div>
                    </td>
                    <td>{{ $agent['store_name'] ?? '—' }}</td>
                    <td>{{ $agent['commission_rate'] }}%</td>
                    <td>{{ ucfirst($agent['commission_type']) }}</td>

                    <td class="text-end">{{ $agent['total_transfers'] }}</td>
                    <td class="text-end">${{ number_format($agent['total_transfer_amount'],2) }}</td>
                    <td class="text-end">
                        <strong>${{ number_format($agent['total_commission'],2) }}</strong>
                    </td>

                    <td class="text-center">{{ $agent['approved'] ? 'Approved' : 'Pending' }}</td>

                    <td class="text-center">
                        <a href="{{ route('admin.commissions.detail', $agent['id']) }}" class="btn btn-sm btn-dark">
                            View
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-3 text-muted">No agents have commission data yet</td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('includes.footer')
