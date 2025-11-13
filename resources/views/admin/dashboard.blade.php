<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        body { background-color: #f3f4f6; font-family: Arial, sans-serif; margin: 0; color: #1f2937; }
        .admin-header { background: #111827; color: #f9fafb; padding: 20px 40px; display: flex; justify-content: space-between; align-items: center; }
        .admin-header h1 { margin: 0; font-size: 24px; }
        .admin-header nav a { color: #f9fafb; margin-left: 20px; text-decoration: none; font-weight: 500; }
        .admin-container { padding: 40px; max-width: 1100px; margin: 0 auto; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 24px; }
        .card { background: #ffffff; border-radius: 8px; padding: 24px; box-shadow: 0 10px 25px rgba(15, 23, 42, 0.08); }
        .card h2 { margin: 0 0 12px 0; font-size: 18px; color: #111827; }
        .card p { margin: 0; font-size: 32px; font-weight: bold; color: #2563eb; }
        .activity-list { list-style: none; padding: 0; margin: 0; }
        .activity-list li { padding: 12px 0; border-bottom: 1px solid #e5e7eb; font-size: 14px; color: #4b5563; }
        .activity-list li:last-child { border-bottom: none; }
    </style>
</head>
<body>
    <header class="admin-header">
        <h1>SwiftPay Admin</h1>
        <nav>
            <a href="{{ route('admin.agents.index') }}">Agent Approvals</a>
            <a href="{{ url('/') }}">View Site</a>
            <a href="#">Settings</a>
        </nav>
    </header>

    <main class="admin-container">
        <section class="card" style="margin-bottom: 24px;">
            <h2>Platform Overview</h2>
            <p>Welcome back! Use the cards below to monitor key activity across SwiftPay.</p>
        </section>

        <section class="grid">
            <div class="card">
                <h2>Total Users</h2>
                <p>--</p>
            </div>
            <div class="card">
                <h2>Active Transfers</h2>
                <p>--</p>
            </div>
            <div class="card">
                <h2>Pending Verifications</h2>
                <p>--</p>
            </div>
            <div class="card">
                <h2>Support Tickets</h2>
                <p>--</p>
            </div>
        </section>

        <section class="card" style="margin-top: 24px;">
            <h2>Recent Platform Activity</h2>
            <ul class="activity-list">
                <li>Metrics will appear here once tracking is implemented.</li>
                <li>Hook this section up to your transfers, users, or audit tables.</li>
                <li>Consider surfacing alerts for compliance and operations teams.</li>
            </ul>
        </section>
    </main>
</body>
</html>
