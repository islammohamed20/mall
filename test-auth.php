<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::first();
if (!$user) {
    echo "No user found\n";
    exit;
}
$token = $user->createToken('test_token')->plainTextToken;
echo "TOKEN: " . $token . "\n";
