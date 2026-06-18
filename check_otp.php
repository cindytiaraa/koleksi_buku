<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

$user = \App\Models\User::first();
echo "ID: " . $user->id . "\n";
echo "Email: " . $user->email . "\n";
echo "OTP: " . ($user->otp ?? 'NULL') . "\n";
echo "Role: " . ($user->role ?? 'NULL') . "\n";
echo "Is Approved: " . ($user->is_approved ?? 'NULL') . "\n";

// Show all columns
echo "\n=== All columns ===\n";
foreach ($user->getAttributes() as $key => $val) {
    echo "$key: $val\n";
}
