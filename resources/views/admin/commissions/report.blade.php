@include('includes.header')

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
            <h1 class="h3 mb-0">Commission Report</h1>
            <small class="text-muted">Filter and analyze commission data</small>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.commissions.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <a href="{{ route('admin.commissions.export.pdf', request()->query()) }}" class="btn btn-danger" target="_blank">
                <i class="fas fa-file-pdf"></i> PDF
            </a>
            <a href="{{ route('admin.commissions.export.excel', request()->query()) }}" class="btn btn-success">
                <i class="fas fa-file-excel"></i> Excel
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-light py-3">
                    <h5 class="m-0 font-weight-bold">Filters</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.commissions.report') }}" class="needs-validation">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="period" class="form-label">Period:</label>
                                <select name="period" id="period" class="form-control" onchange="updateDateFields()">
                                    <option value="daily" {{ request('period') === 'daily' ? 'selected' : '' }}>Daily</option>
                                    <option value="weekly" {{ request('period') === 'weekly' ? 'selected' : '' }}>Weekly</option>
                                    <option value="monthly" {{ request('period') === 'monthly' ? 'selected' : '' }}>Monthly</option>
                                    <option value="yearly" {{ request('period') === 'yearly' ? 'selected' : '' }}>Yearly</option>
                                    <option value="custom" {{ request('period') === 'custom' ? 'selected' : '' }}>Custom Range</option>
                                </select>
                            </div>

                            <div class="col-md-3" id="start_date_container" style="display: {{ request('period') === 'custom' ? 'block' : 'none' }};">
                                <label for="start_date" class="form-label">From Date:</label>
                                <input type="date" name="start_date" id="start_date" class="form-control" 
                                       value="{{ request('start_date') }}">
                            </div>

                            <div class="col-md-3" id="end_date_container" style="display: {{ request('period') === 'custom' ? 'block' : 'none' }};">
                                <label for="end_date" class="form-label">To Date:</label>
                                <input type="date" name="end_date" id="end_date" class="form-control" 
                                       value="{{ request('end_date') }}">
                            </div>

                            <div class="col-md-3">
                                <label for="agent_id" class="form-label">Agent:</label>
                                <select name="agent_id" id="agent_id" class="form-control">
                                    <option value="">-- All Agents --</option>
                                    @foreach($agents as $agent)
                                        <option value="{{ $agent->id }}" 
                                                {{ request('agent_id') == $agent->id ? 'selected' : '' }}>
                                            {{ $agent->user->name ?? 'Unknown' }} ({{ $agent->store_name ?? 'No Store' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label for="status" class="form-label">Status:</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="">-- All Status --</option>
                                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label for="calculation_method" class="form-label">Calculation Method:</label>
                                <select name="calculation_method" id="calculation_method" class="form-control">
                                    <option value="">-- All Methods --</option>
                                    <option value="percentage" {{ request('calculation_method') === 'percentage' ? 'selected' : '' }}>Percentage</option>
                                    <option value="fixed" {{ request('calculation_method') === 'fixed' ? 'selected' : '' }}>Fixed Fee</option>
                                </select>
                            </div>

                            <div class="col-md-6 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter"></i> Apply Filters
                                </button>
                                <a href="{{ route('admin.commissions.report') }}" class="btn btn-secondary">
                                    <i class="fas fa-redo"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-primary text-uppercase text-xs font-weight-bold mb-1">
                        Total Commission
                    </div>
                    <div class="h3 mb-0 font-weight-bold text-gray-800">
                        ${{ number_format($totals['total_commission'] ?? 0, 2) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-success text-uppercase text-xs font-weight-bold mb-1">
                        Total Transfers
                    </div>
                    <div class="h3 mb-0 font-weight-bold text-gray-800">
                        {{ $totals['total_transfers'] ?? 0 }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-info text-uppercase text-xs font-weight-bold mb-1">
                        Average Commission
                    </div>
                    <div class="h3 mb-0 font-weight-bold text-gray-800">
                        ${{ number_format($totals['average_commission'] ?? 0, 2) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-warning text-uppercase text-xs font-weight-bold mb-1">
                        Commission by Status
                    </div>
                    <div class="small">
                        <span class="text-warning">P: ${{ number_format($totals['pending'] ?? 0, 2) }}</span> | 
                        <span class="text-info">A: ${{ number_format($totals['approved'] ?? 0, 2) }}</span> | 
                        <span class="text-success">Pd: ${{ number_format($totals['paid'] ?? 0, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Commissions Table -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="m-0 font-weight-bold">Commission Details</h5>
                </div>
                @if($commissions->isEmpty())
                    <div class="card-body">
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle"></i> No commissions found matching your filters.
                        </div>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 small">
                            <thead class="table-light">
                                <tr>
                                    <th>Agent</th>
                                    <th>Transfer ID</th>
                                    <th>Transfer Amount</th>
                                    <th>Commission Amount</th>
                                    <th>Commission Rate</th>
                                    <th>Method</th>
                                    <th>Status</th>
                                    <th>Created Date</th>
                                    <th>Paid Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($commissions as $commission)
                                    <tr>
                                        <td>
                                            <strong>{{ $commission->agent->user->name ?? 'N/A' }}</strong>
                                            <br>
                                            <small class="text-muted">
                                                {{ $commission->agent->store_name ?? 'No Store' }}
                                            </small>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.transfers.show', $commission->transfer_id) }}" class="text-primary">
                                                #{{ $commission->transfer_id }}
                                            </a>
                                        </td>
                                        <td>${{ number_format($commission->transfer_amount, 2) }}</td>
                                        <td>
                                            <strong class="text-success">
                                                ${{ number_format($commission->commission_amount, 2) }}
                                            </strong>
                                        </td>
                                        <td>{{ $commission->commission_rate }}%</td>
                                        <td>
                                            <span class="badge badge-secondary">
                                                {{ ucfirst($commission->calculation_method) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($commission->status === 'paid')
                                                <span class="badge bg-success text-white">Paid</span>
                                            @elseif($commission->status === 'approved')
                                                <span class="badge bg-info text-white">Approved</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @endif
                                        </td>
                                        <td>{{ $commission->created_at->format('M d, Y') }}</td>
                                        <td>
                                            @if($commission->paid_at)
                                                {{ $commission->paid_at->format('M d, Y') }}
                                            @else
                                                <span class="text-muted">--</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        {{ $commissions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .border-left-primary {
        border-left: 0.25rem solid #007bff !important;
    }
    .border-left-success {
        border-left: 0.25rem solid #28a745 !important;
    }
    .border-left-info {
        border-left: 0.25rem solid #17a2b8 !important;
    }
    </div>
</div>

<script>
    function updateDateFields() {
        const period = document.getElementById('period').value;
        const startContainer = document.getElementById('start_date_container');
        const endContainer = document.getElementById('end_date_container');
        
        if (period === 'custom') {
            startContainer.style.display = 'block';
            endContainer.style.display = 'block';
        } else {
            startContainer.style.display = 'none';
            endContainer.style.display = 'none';
        }
    }
</script>

@include('includes.footer')
