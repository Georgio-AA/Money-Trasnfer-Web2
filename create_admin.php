<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$response = $kernel->handle(
    $request = \Illuminate\Http\Request::capture()
);

// Now we can use the database
use App\Models\User;
use Illuminate\Support\Facades\Hash;

$email = 'ghady.abobaraka12017@gmail.com';

// Check if user exists
$user = User::where('email', $email)->first();

if ($user) {
    // Update user to admin
    $user->update(['role' => 'admin']);
    echo "✓ User updated to admin successfully!\n";
    echo "  Email: {$user->email}\n";
    echo "  Name: {$user->name}\n";
    echo "  Role: {$user->role}\n";
} else {
    echo "✗ User not found with email: $email\n";
}
