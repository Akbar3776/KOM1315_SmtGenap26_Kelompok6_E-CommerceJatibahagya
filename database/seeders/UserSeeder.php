<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat 3 Admin dengan role yang berbeda
        $admins = [
            ['name' => 'Rizky Pratama', 'email' => 'rizky@wavemoon.com', 'role' => 'admin', 'admin_role' => 'super_admin'],
            ['name' => 'Dewi Anjani', 'email' => 'dewi@wavemoon.com', 'role' => 'admin', 'admin_role' => 'staff'],
            ['name' => 'Bayu Setiawan', 'email' => 'bayu@wavemoon.com', 'role' => 'admin', 'admin_role' => 'customer_support'],
        ];

        foreach ($admins as $admin) {
            $user = User::create([
                'name' => $admin['name'],
                'email' => $admin['email'],
                'password' => Hash::make('password'),
                'phone' => '081234567890',
                'address' => 'Admin Office',
                'role' => $admin['role'],
            ]);

            Admin::create([
                'user_id' => $user->id,
                'role' => $admin['admin_role'],
            ]);
        }

        // Buat 2 Customer
        $customers = [
            ['name' => 'Siti Nurhaliza', 'email' => 'siti@wavemoon.com'],
            ['name' => 'Andi Saputra', 'email' => 'andi@wavemoon.com'],
        ];

        foreach ($customers as $customer) {
            User::create([
                'name' => $customer['name'],
                'email' => $customer['email'],
                'password' => Hash::make('password'),
                'phone' => '081234567891',
                'address' => 'Customer Address',
                'role' => 'customer',
            ]);
        }
    }
}
