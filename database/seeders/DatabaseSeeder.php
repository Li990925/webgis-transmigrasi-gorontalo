<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Super Admin Provinsi',
                'email' => 'admin@gorontalo.go.id',
                'password' => Hash::make('password123'),
                'role' => 'super_admin',
                'kabupaten' => null,
                'jabatan' => 'Kepala Dinas Tenaga Kerja, ESDM & Transmigrasi Prov. Gorontalo',
            ],
            [
                'name' => 'Kabupaten Boalemo',
                'email' => 'operator.boalemo@gorontalo.go.id',
                'password' => Hash::make('password123'),
                'role' => 'operator_kabupaten',
                'kabupaten' => 'Kabupaten Boalemo',
                'jabatan' => 'Pranata SIG Bidang Transmigrasi Disnakertrans Boalemo',
            ],
            [
                'name' => 'Kabupaten Pohuwato',
                'email' => 'operator.pohuwato@gorontalo.go.id',
                'password' => Hash::make('password123'),
                'role' => 'operator_kabupaten',
                'kabupaten' => 'Kabupaten Pohuwato',
                'jabatan' => 'Staf Tata Ruang & Pertanahan Kawasan Transmigrasi Pohuwato',
            ],
            [
                'name' => 'Kabupaten Gorontalo',
                'email' => 'operator.gorontalo@gorontalo.go.id',
                'password' => Hash::make('password123'),
                'role' => 'operator_kabupaten',
                'kabupaten' => 'Kabupaten Gorontalo',
                'jabatan' => 'Analis Data Transmigrasi Disnakertrans Kab. Gorontalo',
            ],
            [
                'name' => 'Kabupaten Gorontalo Utara',
                'email' => 'operator.gorut@gorontalo.go.id',
                'password' => Hash::make('password123'),
                'role' => 'operator_kabupaten',
                'kabupaten' => 'Kabupaten Gorontalo Utara',
                'jabatan' => 'Operator GIS & Wilayah Transmigrasi Disnakertrans Gorut',
            ],
            [
                'name' => 'Eksekutif Pemprov Gorontalo',
                'email' => 'eksekutif@gorontalo.go.id',
                'password' => Hash::make('password123'),
                'role' => 'eksekutif',
                'kabupaten' => null,
                'jabatan' => 'Bappeda Litbang & Tim Koordinasi Penataan Ruang Daerah Prov. Gorontalo',
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }

        $this->call([
            KawasanTransmigrasiSeeder::class,
        ]);
    }
}
