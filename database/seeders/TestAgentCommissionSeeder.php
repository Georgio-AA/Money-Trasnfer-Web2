<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Agent;
use App\Models\Commission;
use App\Models\Transfer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

/**
 * TestAgentCommissionSeeder
 * 
 * Creates test agents with transfers and commissions for demonstration.
 */
class TestAgentCommissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating test agents with commissions...');

        // Create test agents if they don't exist
        $testAgents = [
            [
                'name' => 'John',
                'surname' => 'Smith',
                'email' => 'john.agent@example.com',
                'store_name' => 'City Money Transfer',
                'commission_rate' => 2.5,
                'commission_type' => 'percentage',
            ],
            [
                'name' => 'Sarah',
                'surname' => 'Johnson',
                'email' => 'sarah.agent@example.com',
                'store_name' => 'Express Transfers',
                'commission_rate' => 3.0,
                'commission_type' => 'percentage',
            ],
            [
                'name' => 'Mike',
                'surname' => 'Brown',
                'email' => 'mike.agent@example.com',
                'store_name' => 'Global Money Services',
                'commission_rate' => 2.0,
                'commission_type' => 'percentage',
            ],
        ];

        foreach ($testAgents as $agentData) {
            // Create user if not exists
            $user = User::where('email', $agentData['email'])->first();
            
            if (!$user) {
                $user = User::create([
                    'name' => $agentData['name'],
                    'surname' => $agentData['surname'],
                    'email' => $agentData['email'],
                    'password' => bcrypt('password'),
                    'role' => 'agent',
                    'status' => 'active',
                    'is_verified' => true,
                    'balance' => 5000,
                    'currency' => 'USD',
                ]);
            }

            // Create agent if not exists
            $agent = Agent::where('user_id', $user->id)->first();
            if (!$agent) {
                $agent = Agent::create([
                    'user_id' => $user->id,
                    'store_name' => $agentData['store_name'],
                    'address' => '123 Main Street',
                    'country' => 'United States',
                    'latitude' => 40.7128,
                    'longitude' => -74.0060,
                    'working_hours' => '9 AM - 5 PM',
                    'commission_rate' => $agentData['commission_rate'],
                    'commission_type' => $agentData['commission_type'],
                    'approved' => true,
                ]);

                $this->command->info("Created agent: {$agentData['name']} {$agentData['surname']}");

                // Create sample commissions for this agent
                $this->createSampleCommissions($agent);
            }
        }

        $this->displaySummary();
    }

    /**
     * Create sample commission records for an agent.
     *
     * @param Agent $agent
     * @return void
     */
    private function createSampleCommissions(Agent $agent): void
    {
        // Create 10 sample commissions for this agent (without transfer records)
        for ($i = 0; $i < 10; $i++) {
            $transferAmount = rand(100, 5000);
            $commissionRate = $agent->commission_rate;
            $commissionAmount = ($transferAmount * $commissionRate) / 100;

            // Determine status
            $random = rand(1, 100);
            if ($random <= 40) {
                $status = 'pending';
                $paidAt = null;
            } elseif ($random <= 75) {
                $status = 'approved';
                $paidAt = null;
            } else {
                $status = 'paid';
                $paidAt = Carbon::now()->subDays(rand(1, 30));
            }

            Commission::create([
                'agent_id' => $agent->id,
                'transfer_id' => null, // Allow null for demo/testing
                'commission_amount' => round($commissionAmount, 2),
                'commission_rate' => $commissionRate,
                'calculation_method' => $agent->commission_type ?? 'percentage',
                'transfer_amount' => $transferAmount,
                'status' => $status,
                'paid_at' => $paidAt,
                'created_at' => Carbon::now()->subDays(rand(1, 60)),
                'updated_at' => now(),
            ]);
        }
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
        $agents = Agent::count();
        $pendingEarnings = Commission::where('status', 'pending')->sum('commission_amount');
        $approvedEarnings = Commission::where('status', 'approved')->sum('commission_amount');
        $paidEarnings = Commission::where('status', 'paid')->sum('commission_amount');

        $this->command->info('');
        $this->command->info('✓ Commission Test Data Summary:');
        $this->command->info('  Total Agents: ' . $agents);
        $this->command->info('  Total Commissions: ' . $totalCommissions);
        $this->command->info('  Total Earnings: $' . number_format($totalEarnings, 2));
        $this->command->info('  Status Breakdown:');
        $this->command->info('    - Pending: $' . number_format($pendingEarnings, 2));
        $this->command->info('    - Approved: $' . number_format($approvedEarnings, 2));
        $this->command->info('    - Paid: $' . number_format($paidEarnings, 2));
        $this->command->info('');
    }
}
