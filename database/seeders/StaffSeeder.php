<?php

namespace Database\Seeders;

use App\Models\StaffProvince;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provinces = [
            'ACEH',
            'SUMATERA UTARA',
            'SUMATERA BARAT',
            'RIAU',
            'JAMBI',
            'SUMATERA SELATAN',
            'BENGKULU',
            'LAMPUNG',
            'KEPULAUAN BANGKA BELITUNG',
            'KEPULAUAN RIAU',
            'DKI JAKARTA',
            'JAWA BARAT',
            'JAWA TENGAH',
            'DI YOGYAKARTA',
            'JAWA TIMUR',
            'BANTEN',
            'BALI',
            'NUSA TENGGARA BARAT',
            'NUSA TENGGARA TIMUR',
            'KALIMANTAN BARAT',
            'KALIMANTAN TENGAH',
            'KALIMANTAN SELATAN',
            'KALIMANTAN TIMUR',
            'KALIMANTAN UTARA',
            'SULAWESI UTARA',
            'SULAWESI TENGAH',
            'SULAWESI SELATAN',
            'SULAWESI TENGGARA',
            'GORONTALO',
            'SULAWESI BARAT',
            'MALUKU',
            'MALUKU UTARA',
            'PAPUA',
            'PAPUA BARAT',
            'PAPUA TENGAH',
            'PAPUA PEGUNUNGAN',
            'PAPUA SELATAN',
        ];

        foreach ($provinces as $province) {
            // Format password menjadi nama_prov123
            $password = strtolower(str_replace(' ', '', $province)) . '123';

            // Format email untuk 2 kata, ganti spasi dengan underscore
            $email = 'staff_' . strtolower(str_replace( ' ', '_', $province)) . '@gmail.com';



            // Buat user untuk staff
            $user = User::create([
                'email' => $email,
                'password' => Hash::make($password),
                'role' => 'STAFF',
            ]);

            // Buat entri di tabel staff_provinces
            StaffProvince::create([
                'user_id' => $user->id,
                'province' => strtoupper($province),
            ]);
        }
    }
    }
