<?php

namespace Database\Seeders;

use App\Models\TypeKaos;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeKaosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $type = [
            "LENGAN PENDEK",
            "LENGAN PANJANG"
        ];

        foreach ($type as $t) {
            TypeKaos::create([
                'type' => $t
            ]);
        }
    }
}
