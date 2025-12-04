<?php
require_once __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$user = User::where('email', 'ghady.abobaraka12017@gmail.com')->first();

if ($user) {
    $user->balance = 100000;
    $user->save();
    echo "✅ Successfully updated balance for " . $user->email . " to $" . number_format($user->balance, 2) . "\n";
} else {
    echo "❌ User with email ghady.abobaraka12017@gmail.com not found.\n";
}
?>
