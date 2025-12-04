<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

echo "\n========== ADMIN USERS VERIFICATION ==========\n\n";

$admins = User::where('is_admin', 1)->orWhere('role', 'admin')->get();

echo "Total Admin Users: " . $admins->count() . "\n\n";

if ($admins->count() > 0) {
    echo "Admin Accounts Created:\n";
    foreach ($admins as $admin) {
        echo "  - Name: " . $admin->name . "\n";
        echo "    Email: " . $admin->email . "\n";
        echo "    Role: " . ($admin->role ?? 'Not set') . "\n";
        echo "    Is Admin: " . ($admin->is_admin ? 'Yes' : 'No') . "\n\n";
    }
} else {
    echo "⚠️  No admin users found!\n";
}

echo "========== END ==========\n\n";
?>
