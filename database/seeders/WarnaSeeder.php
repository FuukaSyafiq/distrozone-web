<?php

namespace Database\Seeders;

use App\Models\Warna;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WarnaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $warnas = [
            [
                'key' => 'yellow-goose',
                'label' => 'Yellow Goose',
                'tw_class' => 'bg-yellow-200',
            ],
            [
                'key' => 'black',
                'label' => 'Black',
                'tw_class' => 'bg-gray-900',
            ],
            [
                'key' => 'gray',
                'label' => 'Gray',
                'tw_class' => 'bg-gray-100',
            ],
            [
                'key' => 'ocean-blue',
                'label' => 'Ocean Blue',
                'tw_class' => 'bg-blue-500',
            ],
            [
                'key' => 'skyblue',
                'label' => 'Skyblue',
                'tw_class' => 'bg-sky-300',
            ],
            [
                'key' => 'white',
                'label' => 'White',
                'tw_class' => 'bg-white',
            ],
            [
                'key' => 'red-dark',
                'label' => 'Dark red',
                'tw_class' => 'bg-red-900',
            ],
            [
                'key' => 'red-bright',
                'label' => 'Light red',
                'tw_class' => 'bg-red-500',
            ],
            [
                'key' => 'taupe',
                'label' => 'Taupe',
                'tw_class' => 'bg-stone-400',
            ],
            [
                'key' => 'mahogany',
                'label' => 'Mahogany',
                'tw_class' => 'bg-amber-900',
            ],
            [
                'key' => 'oat',
                'label' => 'Oat',
                'tw_class' => 'bg-amber-100',
            ],
            [
                'key' => 'soft-pink',
                'label' => 'Soft Pink',
                'tw_class' => 'bg-pink-200',
            ],
            [
                'key' => 'ice-mint',
                'label' => 'Ice Mint',
                'tw_class' => 'bg-teal-100',
            ],
        ];

        foreach ($warnas as $warna) {
            Warna::updateOrCreate(
                ['key' => $warna['key']],
                $warna
            );
        }
    }

    public static function down() {
        Warna::query()->delete();
    }
}
