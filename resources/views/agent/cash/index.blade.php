<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Agent Cash Operations</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Cash Operations - {{ $agent->store_name }}</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('agent.cash.create') }}" class="btn btn-primary mb-3">New Transaction</a>

    @if($transactions->isEmpty())
        <p>No transactions recorded yet.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Currency</th>
                    <th>Transfer ID</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $t)
                    <tr>
                        <td>{{ $t->id }}</td>
                        <td>{{ ucfirst($t->type) }}</td>
                        <td>{{ number_format($t->amount, 2) }}</td>
                        <td>{{ $t->currency }}</td>
                        <td>{{ $t->transfer_id ?? '-' }}</td>
                        <td>{{ ucfirst($t->status) }}</td>
                        <td>{{ $t->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
</body>
</html>
