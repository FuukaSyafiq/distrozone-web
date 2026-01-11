<?php

namespace Database\Seeders;

use App\Models\Provinsi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProvinsiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provinsi = [
            'ACEH',
            'SUMATERA UTARA',
            'SUMATERA BARAT',
            'RIAU',
            'KEPULAUAN RIAU',
            'JAMBI',
            'SUMATERA SELATAN',
            'BENGKULU',
            'LAMPUNG',
            'KEPULAUAN BANGKA BELITUNG',

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
            'PAPUA SELATAN',
            'PAPUA TENGAH',
            'PAPUA PEGUNUNGAN',
            'PAPUA BARAT DAYA',
        ];

        foreach ($provinsi as $nama) {
            Provinsi::create([
                'provinsi' => $nama
            ]);
        }
    }
    public static function down(): void
    {
        Provinsi::query()->delete();
    }
}
