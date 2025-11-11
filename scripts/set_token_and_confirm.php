<?php
// scripts/set_token_and_confirm.php
// Bootstraps Laravel, sets a verification token & due on the latest user, then calls AuthController::verifyEmail

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Str;
use Carbon\Carbon;

$user = User::latest()->first();
if (! $user) {
    echo "No users found in DB.\n";
    exit(1);
}

$token = Str::random(64);
$due = Carbon::now()->addDays(3);

$user->update([
    'verification_token' => $token,
    'verification_due' => $due,
    'is_verified' => false,
]);

$user->refresh();
echo "Set token for user ID {$user->id}: {$user->verification_token}\n";

$controller = new AuthController();
$response = $controller->verifyEmail($token);

$user->refresh();
$is = $user->is_verified ? '1' : '0';
echo "After verifyEmail, is_verified = {$is}\n";

echo "verification_token: ";
var_export($user->verification_token);
echo "\nverification_due: ";
var_export($user->verification_due);
echo "\n";

return 0;
