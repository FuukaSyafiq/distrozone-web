<?php

namespace Database\Seeders;

use App\Models\MerekKaos;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MerekSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $merek = [
            "ADIDAS",
            "POLO"
        ];

        foreach($merek as $m)  {
        MerekKaos::create([
            'merek' => $m
        ]);
    }
    }
}
