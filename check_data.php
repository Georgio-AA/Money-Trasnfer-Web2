<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Database Check ===\n\n";

$agents = \App\Models\Agent::with('user')->get();
echo "Total Agents: " . $agents->count() . "\n";

foreach ($agents as $agent) {
    echo "\nAgent: " . $agent->user->name . " (ID: " . $agent->id . ", User ID: " . $agent->user_id . ")\n";
    
    $transfers = \App\Models\Transfer::where('agent_id', $agent->user_id)->get();
    echo "  Transfers for this agent: " . $transfers->count() . "\n";
    
    $commissions = \App\Models\Commission::where('agent_id', $agent->id)->get();
    echo "  Commissions: " . $commissions->count() . "\n";
    if ($commissions->count() > 0) {
        echo "  Total Commission Amount: $" . number_format($commissions->sum('commission_amount'), 2) . "\n";
    }
}

echo "\n=== Transfer Data ===\n";
$totalTransfers = \App\Models\Transfer::count();
echo "Total Transfers in DB: " . $totalTransfers . "\n";
if ($totalTransfers > 0) {
    $transfer = \App\Models\Transfer::first();
    echo "Sample Transfer:\n";
    echo "  ID: " . $transfer->id . "\n";
    echo "  Agent ID: " . $transfer->agent_id . "\n";
    echo "  Amount: " . $transfer->amount . "\n";
}

echo "\n=== Commission Data ===\n";
$totalCommissions = \App\Models\Commission::count();
echo "Total Commissions in DB: " . $totalCommissions . "\n";
?>
