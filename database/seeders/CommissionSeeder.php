<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\Commission;
use App\Models\Transfer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

/**
 * CommissionSeeder
 * 
 * Generates test commission data for agents based on existing transfers.
 * This seeder creates realistic commission records for testing and demonstration.
 */
class CommissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all agents
        $agents = Agent::all();
        
        if ($agents->isEmpty()) {
            $this->command->info('No agents found. Please run AgentSeeder first.');
            return;
        }

        $this->command->info('Creating commission records for agents...');

        foreach ($agents as $agent) {
            // Get all transfers for this agent
            $transfers = Transfer::where('agent_id', $agent->user_id)->get();

            foreach ($transfers as $transfer) {
                // Skip if commission already exists
                $existingCommission = Commission::where('transfer_id', $transfer->id)->first();
                if ($existingCommission) {
                    continue;
                }

                // Calculate commission based on agent settings
                $commissionData = $this->calculateCommission($agent, $transfer);

                // Create commission record with varying status
                Commission::create([
                    'agent_id' => $agent->id,
                    'transfer_id' => $transfer->id,
                    'commission_amount' => $commissionData['amount'],
                    'commission_rate' => $commissionData['rate'],
                    'calculation_method' => $commissionData['method'],
                    'transfer_amount' => $transfer->amount,
                    'status' => $this->randomStatus(),
                    'paid_at' => $this->randomPaidDate(),
                    'created_at' => $transfer->created_at ?? now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('Commission seeding completed successfully!');
        $this->displaySummary();
    }

    /**
     * Calculate commission for a transfer based on agent settings.
     *
     * @param Agent $agent
     * @param Transfer $transfer
     * @return array
     */
    private function calculateCommission(Agent $agent, Transfer $transfer): array
    {
        $commissionType = $agent->commission_type ?? 'percentage';
        $commissionRate = $agent->commission_rate ?? 2.5;

        if ($commissionType === 'percentage') {
            // For percentage: calculate as percentage of transfer amount
            $amount = ($transfer->amount * $commissionRate) / 100;
        } else {
            // For fixed: use flat rate
            $amount = $commissionRate;
        }

        return [
            'amount' => round($amount, 2),
            'rate' => $commissionRate,
            'method' => $commissionType,
        ];
    }

    /**
     * Get a random commission status.
     * Weighted distribution: 40% pending, 35% approved, 25% paid
     *
     * @return string
     */
    private function randomStatus(): string
    {
        $random = rand(1, 100);
        
        if ($random <= 40) {
            return 'pending';
        } elseif ($random <= 75) {
            return 'approved';
        } else {
            return 'paid';
        }
    }

    /**
     * Get a random paid date or null.
     * 25% chance to have paid_at date (if status is paid)
     *
     * @return Carbon|null
     */
    private function randomPaidDate(): ?Carbon
    {
        $random = rand(1, 100);
        
        if ($random <= 25) {
            // Return a date within the past 30 days
            $days = rand(1, 30);
            return Carbon::now()->subDays($days);
        }
        
        return null;
    }

    /**
     * Display summary of created commissions.
     *
     * @return void
     */
    private function displaySummary(): void
    {
        $totalCommissions = Commission::count();
        $totalEarnings = Commission::sum('commission_amount');
        $pendingEarnings = Commission::where('status', 'pending')->sum('commission_amount');
        $approvedEarnings = Commission::where('status', 'approved')->sum('commission_amount');
        $paidEarnings = Commission::where('status', 'paid')->sum('commission_amount');

        $this->command->info('');
        $this->command->info('Commission Summary:');
        $this->command->info('  Total Commissions: ' . $totalCommissions);
        $this->command->info('  Total Earnings: $' . number_format($totalEarnings, 2));
        $this->command->info('  Pending: $' . number_format($pendingEarnings, 2));
        $this->command->info('  Approved: $' . number_format($approvedEarnings, 2));
        $this->command->info('  Paid: $' . number_format($paidEarnings, 2));
        $this->command->info('');
    }
}
