<?php

namespace Database\Seeders;

use App\Models\Kota;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ongkir;
use App\Models\Provinsi;

class OngkirSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ongkirs = [
            [
                'wilayah' => Provinsi::getProvinsi('DKI JAKARTA')->provinsi,
                'tarif_per_kg' => 24000
            ],
            [
                'wilayah' => Kota::getKota("DEPOK")->kota,
                'tarif_per_kg' => 24000
            ],
            [
                'wilayah' => Kota::getKota("BEKASI")->kota,
                'tarif_per_kg' => 25000
            ],
            [
                'wilayah' => Kota::getKota("TANGERANG")->kota,
                'tarif_per_kg' => 25000
            ],
            [
                'wilayah' => Kota::getKota("BOGOR")->kota,
                'tarif_per_kg' => 27000
            ],
            [
                'wilayah' => Provinsi::getProvinsi('JAWA BARAT')->provinsi,
                'tarif_per_kg' => 31000
            ],
            [
                'wilayah' => Provinsi::getProvinsi('JAWA TENGAH')->provinsi,
                'tarif_per_kg' => 39000
            ],
            [
                'wilayah' => Provinsi::getProvinsi('JAWA TIMUR')->provinsi,
                'tarif_per_kg' => 47000
            ]
        ];

        foreach($ongkirs as $ongkir) {
            Ongkir::create($ongkir);
        }
    }

    public static function down(): void
    {
        Ongkir::query()->delete();
    }
}
