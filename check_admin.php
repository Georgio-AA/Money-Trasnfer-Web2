<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$admin = \App\Models\User::where('email', 'admin@example.com')->first();
if ($admin) {
    echo "Admin User Found:\n";
    echo "Name: " . $admin->name . "\n";
    echo "Email: " . $admin->email . "\n";
    echo "Role: " . ($admin->role ?? 'null') . "\n";
    echo "Is Admin: " . ($admin->is_admin ? 'true' : 'false') . "\n";
} else {
    echo "Admin user not found\n";
}
?>
