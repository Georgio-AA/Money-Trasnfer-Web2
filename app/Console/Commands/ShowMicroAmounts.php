<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BankAccount;

class ShowMicroAmounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'show:micro-amounts {bankAccountId?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show micro-transfer amounts for testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $bankAccountId = $this->argument('bankAccountId');
        
        if ($bankAccountId) {
            $bankAccount = BankAccount::find($bankAccountId);
            if (!$bankAccount) {
                $this->error("Bank account with ID {$bankAccountId} not found.");
                return 1;
            }
            $bankAccounts = collect([$bankAccount]);
        } else {
            $bankAccounts = BankAccount::whereNotNull('micro_amount_1')->get();
        }

        if ($bankAccounts->isEmpty()) {
            $this->info('No bank accounts with micro-transfer amounts found.');
            return 0;
        }

        $this->info('Bank Accounts with Micro-Transfer Amounts:');
        $this->info('========================================');

        foreach ($bankAccounts as $account) {
            $this->info("Account ID: {$account->id}");
            $this->info("Bank: {$account->bank_name}");
            $this->info("Account: ****" . substr($account->account_number, -4));
            $this->info("Amount 1: $" . number_format($account->micro_amount_1, 2));
            $this->info("Amount 2: $" . number_format($account->micro_amount_2, 2));
            $this->info("Sent At: " . ($account->micro_transfer_sent_at ? $account->micro_transfer_sent_at->format('Y-m-d H:i:s') : 'Not set'));
            $this->info("Attempts: {$account->verification_attempts}/3");
            $this->info("Verified: " . ($account->is_verified ? 'Yes' : 'No'));
            $this->info('----------------------------------------');
        }

        return 0;
    }
}