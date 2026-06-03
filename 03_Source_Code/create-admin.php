<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = \App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@jatibahagya.com',
    'password' => bcrypt('password123'),
    'email_verified_at' => now(),
]);

\App\Models\Admin::create([
    'user_id' => $user->id,
    'role' => 'super_admin',
]);

echo "Admin user created successfully!\n";
echo "Email: admin@jatibahagya.com\n";
echo "Password: password123\n";