<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent Approvals</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        body { background-color: #f3f4f6; font-family: Arial, sans-serif; margin: 0; color: #1f2937; }
        .admin-header { background: #111827; color: #f9fafb; padding: 20px 40px; display: flex; justify-content: space-between; align-items: center; }
        .admin-header h1 { margin: 0; font-size: 24px; }
        .admin-header nav a { color: #f9fafb; margin-left: 20px; text-decoration: none; font-weight: 500; }
        .admin-container { padding: 40px; max-width: 1200px; margin: 0 auto; }
        h2 { margin-top: 0; }
        table { width: 100%; border-collapse: collapse; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 10px 25px rgba(15, 23, 42, 0.08); }
        th, td { padding: 14px 16px; text-align: left; border-bottom: 1px solid #e5e7eb; font-size: 14px; }
        th { background: #f9fafb; text-transform: uppercase; letter-spacing: 0.05em; font-size: 12px; color: #374151; }
        tr:last-child td { border-bottom: none; }
        .empty { padding: 24px; text-align: center; color: #6b7280; font-style: italic; }
        .badge { display: inline-block; padding: 4px 8px; border-radius: 9999px; font-size: 12px; font-weight: 600; }
        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-approved { background: #dcfce7; color: #166534; }
        .actions { display: flex; gap: 8px; }
        .btn { padding: 8px 12px; border: none; border-radius: 6px; font-size: 13px; cursor: pointer; }
        .btn-approve { background: #22c55e; color: #ffffff; }
        .btn-revoke { background: #ef4444; color: #ffffff; }
        .alert { padding: 16px 20px; border-radius: 8px; margin-bottom: 24px; font-size: 14px; }
        .alert-success { background: #dcfce7; color: #166534; }
        .alert-info { background: #dbeafe; color: #1e3a8a; }
    </style>
</head>
<body>
<header class="admin-header">
    <h1>SwiftPay Admin</h1>
    <nav>
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <a href="{{ url('/') }}">View Site</a>
    </nav>
</header>

<main class="admin-container">
    <h2>Agent &amp; Partner Store Approvals</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('info'))
        <div class="alert alert-info">{{ session('info') }}</div>
    @endif

    <section style="margin-bottom: 32px;">
        <h3>Pending Requests</h3>
        @if ($pendingAgents->isEmpty())
            <div class="empty">No pending agent applications right now.</div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Agent</th>
                        <th>Store</th>
                        <th>Country</th>
                        <th>Contact</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($pendingAgents as $agent)
                    <tr>
                        <td>{{ optional($agent->user)->name ?? 'N/A' }}</td>
                        <td>{{ $agent->store_name ?? '—' }}</td>
                        <td>{{ $agent->country ?? '—' }}</td>
                        <td>{{ optional($agent->user)->email ?? '—' }}</td>
                        <td><span class="badge badge-pending">Pending</span></td>
                        <td>
                            <form action="{{ route('admin.agents.approve', $agent) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-approve">Approve</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </section>

    <section>
        <h3>Approved Agents</h3>
        @if ($approvedAgents->isEmpty())
            <div class="empty">No approved agents yet.</div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Agent</th>
                        <th>Store</th>
                        <th>Country</th>
                        <th>Contact</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($approvedAgents as $agent)
                    <tr>
                        <td>{{ optional($agent->user)->name ?? 'N/A' }}</td>
                        <td>{{ $agent->store_name ?? '—' }}</td>
                        <td>{{ $agent->country ?? '—' }}</td>
                        <td>{{ optional($agent->user)->email ?? '—' }}</td>
                        <td><span class="badge badge-approved">Approved</span></td>
                        <td>
                            <form action="{{ route('admin.agents.revoke', $agent) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-revoke">Revoke</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </section>
</main>
</body>
</html>
