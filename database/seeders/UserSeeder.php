<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Admin;
use App\Models\UserAddress;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
                'phone' => '0812' . rand(10000000, 99999999), // Phone unik
                'role' => $admin['role'],
            ]);

            Admin::create([
                'user_id' => $user->id,
                'role' => $admin['admin_role'],
            ]);

            $this->generateAddress($user);
        }

        // Buat 2 Customer
        $customers = [
            ['name' => 'Siti Nurhaliza', 'email' => 'siti@wavemoon.com'],
            ['name' => 'Andi Saputra', 'email' => 'andi@wavemoon.com'],
        ];

        foreach ($customers as $customer) {
            $user = User::create([
                'name' => $customer['name'],
                'email' => $customer['email'],
                'password' => Hash::make('password'),
                'phone' => '0813' . rand(10000000, 99999999), // Phone unik
                'role' => 'customer',
            ]);

            $this->generateAddress($user);
        }
    }

    /**
     * Generate user address dengan hubungan Province -> Regency -> District -> Village
     */
    private function generateAddress(User $user)
    {
        // Daftar nama bunga
        $flowers = ['Mawar', 'Melati', 'Anggrek', 'Tulip', 'Teratai', 'Kenanga', 'Sakura', 'Lavender', 'Edelweiss', 'Dahlia'];

        // Ambil data secara random dari IndoRegion
        $province = Province::inRandomOrder()->first();
        $regency = Regency::where('province_id', $province->id)->inRandomOrder()->first();
        $district = District::where('regency_id', $regency->id)->inRandomOrder()->first();
        $village = Village::where('district_id', $district->id)->inRandomOrder()->first();

        // Pilih nama bunga random
        $flowerName = $flowers[array_rand($flowers)];

        // Generate postal code hanya satu kali
        $postalCode = rand(10000, 99999);

        // Generate alamat dengan format yang diinginkan
        $fullAddress = sprintf(
            "Jl %s, No %d, %s, %s, %s, %s, %d",
            $flowerName,
            rand(1, 999),
            ucwords(strtolower($village->name)),
            ucwords(strtolower($district->name)),
            ucwords(strtolower($regency->name)),
            ucwords(strtolower($province->name)),
            $postalCode
        );

        UserAddress::create([
            'user_id' => $user->id,
            'id_province' => $province->id,
            'id_regency' => $regency->id,
            'id_district' => $district->id,
            'id_village' => $village->id,
            'postal_code' => $postalCode,
            'full_address' => $fullAddress,
        ]);
    }
}
