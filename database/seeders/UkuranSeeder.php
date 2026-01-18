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
            "M",
            "S",
            "L",
            "XL",
            "XS",
            "XXL",
            "3XL",
            "4XL"
        ];

        foreach ($ukuran as $u) {
            UkuranKaos::create([
                'ukuran' => $u
            ]);
        }
    }
}
