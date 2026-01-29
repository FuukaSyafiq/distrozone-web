<?php

namespace Database\Seeders;

use App\Models\UkuranKaos;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UkuranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ukuran = [
            "4XS",
            "3XS",
            "XXS",
            "XS",
            "S",
            "M",
            "L",
            "XL",
            "XXL",
            "3XL",
            "4XL",
            "5XL",
            "6XL",
        ];

        foreach ($ukuran as $u) {
            UkuranKaos::create([
                'ukuran' => $u
            ]);
        }
    }

    public function down() {
        UkuranKaos::query()->delete();
    }
}
