<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ongkir;

class OngkirSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ongkirs = [
            [
                'wilayah' => "Jakarta",
                'tarif_per_kg' => 24000
            ],
            [
                'wilayah' => "Depok",
                'tarif_per_kg' => 24000
            ],
            [
                'wilayah' => "Bekasi",
                'tarif_per_kg' => 25000
            ],
            [
                'wilayah' => "Tangerang",
                'tarif_per_kg' => 25000
            ],
            [
                'wilayah' => "Bogor",
                'tarif_per_kg' => 27000
            ],
            [
                'wilayah' => "Jawa barat",
                'tarif_per_kg' => 31000
            ],
            [
                'wilayah' => "Jawa tengah",
                'tarif_per_kg' => 39000
            ],
            [
                'wilayah' => "Jawa timur",
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
