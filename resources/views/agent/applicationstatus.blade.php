<!DOCTYPE html>
<html>
<head>
    <title>Agent Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">

    <h2 class="mb-4">Agent Dashboard</h2>

    {{-- Success message --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- If user has not applied yet --}}
    @if(!isset($agent) || !$agent)
        <div class="card p-4">
            <h4>You are not an agent yet</h4>
            <p>You can apply to become a partner agent.</p>
            <a href="{{ route('agent.applytobeagent') }}" class="btn btn-primary">Apply Now</a>
        </div>
    @else
        <div class="card p-4 mb-4">
            <h4>Agent Application Status</h4>

            @if($agent)
            @if($agent->approved)
                <p>Status: <span class="badge bg-success">Approved</span></p>
                <p>You now have full access to the Agent Panel.</p>
                <a href="{{ route('agent.dashboard') }}" class="btn btn-success">
                    Enter Agent Panel
                </a>
            @else
                <p>Status: <span class="badge bg-warning text-dark">Pending Approval</span></p>
                <p>Your application is currently under review. You will be notified once approved.</p>
            @endif
            @else
                <p>You have not applied to be an agent yet.</p>
            @endif

        </div>

        <div class="card p-4">
            <h4>Your Application Details</h4>
            <ul>
                <li><strong>Store Name:</strong> {{ $agent->store_name }}</li>
                <li><strong>Address:</strong> {{ $agent->address }}</li>
                <li><strong>Country:</strong> {{ $agent->country }}</li>
                <li><strong>City:</strong> {{ $agent->city }}</li>
                <li><strong>Working Hours:</strong> {{ $agent->working_hours }}</li>
                <li><strong>Commission Rate:</strong> {{ $agent->commission_rate }}%</li>
            </ul>
        </div>
    @endif

</div>
</body>
</html>
