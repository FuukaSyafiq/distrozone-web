<?php

namespace Database\Seeders;

use App\Models\Warna;
use Illuminate\Database\Seeder;

class WarnaSeeder extends Seeder
{
    public function run(): void
    {
        $warnas = [
            // --- BASIC & MONOCHROME ---
            ['key' => 'white', 'label' => 'White', 'hex' => '#ffffff'],
            ['key' => 'gray-light', 'label' => 'Light Gray', 'hex' => '#f3f4f6'],
            ['key' => 'gray-medium', 'label' => 'Slate', 'hex' => '#64748b'],
            ['key' => 'black', 'label' => 'Black', 'hex' => '#111827'],

            // --- EARTH TONES (Warna Bumi) ---
            ['key' => 'oat', 'label' => 'Oat', 'hex' => '#fef3c7'],
            ['key' => 'taupe', 'label' => 'Taupe', 'hex' => '#a8a29e'],
            ['key' => 'terracotta', 'label' => 'Terracotta', 'hex' => '#c2410c'],
            ['key' => 'mahogany', 'label' => 'Mahogany', 'hex' => '#78350f'],
            ['key' => 'olive', 'label' => 'Olive Drab', 'hex' => '#3f6212'],
            ['key' => 'khaki', 'label' => 'Khaki', 'hex' => '#f0e68c'],

            // --- REDS & PINKS ---
            ['key' => 'red-bright', 'label' => 'Light Red', 'hex' => '#ef4444'],
            ['key' => 'red-dark', 'label' => 'Dark Red', 'hex' => '#7f1d1d'],
            ['key' => 'soft-pink', 'label' => 'Soft Pink', 'hex' => '#fbcfe8'],
            ['key' => 'magenta', 'label' => 'Magenta', 'hex' => '#d946ef'],
            ['key' => 'maroon', 'label' => 'Maroon', 'hex' => '#800000'],

            // --- BLUES & TEALS ---
            ['key' => 'skyblue', 'label' => 'Sky Blue', 'hex' => '#7dd3fc'],
            ['key' => 'ocean-blue', 'label' => 'Ocean Blue', 'hex' => '#3b82f6'],
            ['key' => 'navy', 'label' => 'Navy Blue', 'hex' => '#1e3a8a'],
            ['key' => 'ice-mint', 'label' => 'Ice Mint', 'hex' => '#ccfbf1'],
            ['key' => 'teal', 'label' => 'Teal', 'hex' => '#0d9488'],

            // --- GREENS & YELLOWS ---
            ['key' => 'emerald', 'label' => 'Emerald Green', 'hex' => '#10b981'],
            ['key' => 'forest-green', 'label' => 'Forest Green', 'hex' => '#166534'],
            ['key' => 'yellow-goose', 'label' => 'Yellow Goose', 'hex' => '#fef08a'],
            ['key' => 'mustard', 'label' => 'Mustard Gold', 'hex' => '#eab308'],
            ['key' => 'orange-sun', 'label' => 'Sunset Orange', 'hex' => '#f97316'],

            // --- PURPLES & OTHERS ---
            ['key' => 'lavender', 'label' => 'Lavender', 'hex' => '#e9d5ff'],
            ['key' => 'royal-purple', 'label' => 'Royal Purple', 'hex' => '#7e22ce'],
            ['key' => 'indigo', 'label' => 'Indigo', 'hex' => '#4338ca'],
        ];

        foreach ($warnas as $warna) {
            Warna::updateOrCreate(
                ['key' => $warna['key']],
                $warna
            );
        }
    }

    public static function down()
    {
        Warna::query()->delete();
    }
}
