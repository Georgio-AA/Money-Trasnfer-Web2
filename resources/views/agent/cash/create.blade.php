<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Cash Transaction</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Record New Transaction</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('agent.cash.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="type" class="form-label">Transaction Type</label>
            <select name="type" id="type" class="form-select" required>
                <option value="">Select Type</option>
                <option value="cash-in">Cash In</option>
                <option value="cash-out">Cash Out</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="amount" class="form-label">Amount</label>
            <input type="number" name="amount" id="amount" class="form-control" step="0.01" min="0.01" required>
        </div>

        <div class="mb-3">
            <label for="currency" class="form-label">Currency</label>
            <input type="text" name="currency" id="currency" class="form-control" maxlength="10" required>
        </div>

        <div class="mb-3">
            <label for="transfer_id" class="form-label">Transfer ID (optional)</label>
            <input type="number" name="transfer_id" id="transfer_id" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">Record Transaction</button>
        <a href="{{ route('agent.cash.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
</body>
</html>
