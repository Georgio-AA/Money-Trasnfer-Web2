@include('includes.header')

<style>
    body { background-color: #f3f4f6; }
    .admin-container { max-width: 1400px; margin: 40px auto; padding: 0 20px; }
    .page-header { margin-bottom: 2rem; }
    .page-header h1 { font-size: 2rem; font-weight: bold; color: #333; margin-bottom: 0.5rem; }
    .page-header p { color: #6c757d; }
    
    /* Stat Cards */
    .stat-cards { margin-bottom: 2rem; }
    .stat-card { 
        padding: 1.5rem; 
        border-radius: 0.35rem; 
        background: white; 
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15); 
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .stat-card:hover { transform: translateY(-2px); box-shadow: 0 0.25rem 2rem 0 rgba(58, 59, 69, 0.2); }
    .stat-card .stat-value { 
        font-size: 1.75rem; 
        font-weight: bold; 
        color: #007bff; 
        margin: 0.5rem 0;
    }
    .stat-card .stat-label { 
        font-size: 0.85rem; 
        color: #6c757d; 
        text-transform: uppercase; 
        letter-spacing: 0.5px; 
        margin-bottom: 0.5rem;
    }
    
    /* Table */
    .card { border: none; box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15); margin-bottom: 2rem; }
    .card-header { background-color: #007bff; color: white; font-weight: bold; border-radius: 0.35rem 0.35rem 0 0; }
    .table-responsive { border-radius: 0 0 0.35rem 0.35rem; }
    .table { margin-bottom: 0; }
    .table thead th { 
        background-color: #f8f9fa; 
        border-top: none;
        font-weight: 600;
        color: #333;
    }
    .table tbody tr:hover { background-color: #f8f9fa; }
    .table tbody td { vertical-align: middle; }
    
    /* Status & Badges */
    .badge { padding: 0.4rem 0.6rem; font-size: 0.75rem; }
    .status-pending { background-color: #ffc107; color: #000; }
    .status-approved { background-color: #17a2b8; color: white; }
    .status-paid { background-color: #28a745; color: white; }
    
    /* Buttons */
    .btn-sm { padding: 0.25rem 0.5rem; font-size: 0.75rem; }
</style>

<div class="admin-container">
    <!-- Page Header -->
    <div class="page-header d-flex justify-content-between align-items-start">
        <div>
            <h1>Commission Management</h1>
            <p class="text-muted">Track and manage agent earnings and commissions</p>
        </div>
        <a href="{{ route('admin.commissions.report') }}" class="btn btn-primary">
            <i class="fas fa-chart-bar"></i> Detailed Report
        </a>
    </div>

    @if($agents->isEmpty())
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="fas fa-info-circle"></i> <strong>No Data:</strong> No agents found in the system.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @else
        <!-- Summary Statistics -->
        <div class="row stat-cards">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-label">Total Agents</div>
                    <div class="stat-value">{{ count($agents) }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-label">Total Commission (Month)</div>
                    <div class="stat-value">${{ number_format($agents->sum('total_commission'), 2) }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-label">Total Transfers (Month)</div>
                    <div class="stat-value">{{ $agents->sum('total_transfers') }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-label">Average Commission</div>
                    <div class="stat-value">${{ number_format($agents->count() > 0 ? $agents->sum('total_commission') / $agents->count() : 0, 2) }}</div>
                </div>
            </div>
        </div>

        <!-- Agents Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Agent Commission Summary</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Agent Name</th>
                            <th>Store Name</th>
                            <th>Commission Rate</th>
                            <th>Type</th>
                            <th class="text-end">Transfers</th>
                            <th class="text-end">Transfer Amount</th>
                            <th class="text-end">Total Commission</th>
                            <th class="text-end">Avg/Transfer</th>
                            <th class="text-center">Commissions</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($agents as $agent)
                            <tr>
                                <td>
                                    <strong>{{ $agent['user']['name'] ?? 'N/A' }}</strong>
                                    @if(isset($agent['user']['email']))
                                        <br><small class="text-muted">{{ $agent['user']['email'] }}</small>
                                    @endif
                                </td>
                                <td>{{ $agent['store_name'] ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-primary">{{ $agent['commission_rate'] ?? 0 }}%</span>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ ucfirst($agent['commission_type'] ?? 'percentage') }}</span>
                                </td>
                                <td class="text-end">{{ $agent['total_transfers'] ?? 0 }}</td>
                                <td class="text-end">${{ number_format($agent['total_transfer_amount'] ?? 0, 2) }}</td>
                                <td class="text-end">
                                    <strong class="text-success">${{ number_format($agent['total_commission'] ?? 0, 2) }}</strong>
                                </td>
                                <td class="text-end">${{ number_format($agent['average_commission_per_transfer'] ?? 0, 2) }}</td>
                                <td class="text-center">
                                    <span class="badge bg-success">{{ $agent['commission_count'] ?? 0 }}</span>
                                </td>
                                <td class="text-center">
                                    @if($agent['approved'])
                                        <span class="badge bg-success">Approved</span>
                                    @else
                                        <span class="badge bg-warning">Pending</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.commissions.detail', $agent['id']) }}" class="btn btn-sm btn-primary" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

@include('includes.footer')
