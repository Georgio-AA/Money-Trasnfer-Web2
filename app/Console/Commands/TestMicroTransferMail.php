<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BankAccount;
use App\Models\User;
use App\Mail\MicroTransferVerificationMail;
use Illuminate\Support\Facades\Mail;

class TestMicroTransferMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:micro-mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test micro-transfer verification email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            // Try to get the first bank account from the database
            $bankAccount = BankAccount::with('user')->first();
            
            if (!$bankAccount) {
                $this->error('No bank accounts found in database. Please create one first.');
                return 1;
            }

            Mail::to($bankAccount->user->email)->send(new MicroTransferVerificationMail($bankAccount, [
                'amount1' => 0.23,
                'amount2' => 0.47
            ]));

            $this->info('Micro-transfer verification email sent successfully!');
            return 0;
        } catch (\Exception $e) {
            $this->error('Failed to send email: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
            return 1;
        }
    }
}