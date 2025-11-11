<?php
// scripts/send_test_mail.php
// Bootstraps the Laravel app and sends a verification mail to the specified address.

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Bootstrap the application
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Mail\VerificationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

$recipient = 'ghady.abobaraka12017@gmail.com';

$user = User::latest()->first();
if (! $user) {
    echo "No users found in DB. Create a user first.\n";
    exit(1);
}

// Populate verification token and expiry if missing
if (! $user->verification_token || ! $user->verification_due) {
    $token = Str::random(64);
    $due = Carbon::now()->addDays(3);
    $user->update([
        'verification_token' => $token,
        'verification_due' => $due,
    ]);
    // refresh the model
    $user->refresh();
    echo "Populated verification_token and verification_due for user ID {$user->id}\n";
}

try {
    Mail::to($recipient)->send(new VerificationMail($user));
    echo "Mail sent (or attempted) to: {$recipient}\n";
} catch (Throwable $e) {
    echo "Exception while sending mail: " . $e->getMessage() . "\n";
    // Also log to laravel log so the app's logging path shows details
    \Illuminate\Support\Facades\Log::error('Test mail failed: ' . $e->getMessage());
    exit(2);
}

return 0;
