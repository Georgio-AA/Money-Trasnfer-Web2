<?php
// scripts/confirm_verification_test.php
// Bootstraps Laravel and calls AuthController::verifyEmail for the latest user with a verification_token.

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Http\Controllers\AuthController;

$user = User::whereNotNull('verification_token')->latest()->first();
if (! $user) {
    echo "No user with a verification token found.\n";
    exit(1);
}

$token = $user->verification_token;
echo "Found user ID {$user->id} with token: {$token}\n";

$controller = new AuthController();
// Call verification method
$response = $controller->verifyEmail($token);

// Refresh user model and show is_verified value
$user->refresh();
$is = $user->is_verified ? '1' : '0';
echo "After running verifyEmail, is_verified = {$is}\n";

// Also dump a simple check of verification_token and due
$vt = $user->verification_token === null ? 'null' : $user->verification_token;
$vd = $user->verification_due ? $user->verification_due->toDateTimeString() : 'null';
echo "verification_token: {$vt}\n";
echo "verification_due: {$vd}\n";

return 0;
