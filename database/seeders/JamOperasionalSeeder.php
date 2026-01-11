<?php

namespace Database\Seeders;

use App\Models\JamOperasional;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JamOperasionalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $hari = [
            'SENIN',
            'SELASA',
            'RABU',
            'KAMIS',
            'JUMAT',
            'SABTU',
            'MINGGU',
        ];

        $data = [];

        foreach ($hari as $h) {

            // =====================
            // OFFLINE
            // =====================
            if ($h === 'SENIN') {
                // Senin LIBUR
                $data[] = [
                    'jenis' => 'offline',
                    'hari' => $h,
                    'jam_buka' => null,
                    'jam_tutup' => null,
                    'status' => 'TUTUP',
                ];
            } else if ($h === 'MINGGU') {
                $data[] = [
                    'jenis' => 'offline',
                    'hari' => $h,
                    'jam_buka' => null,
                    'jam_tutup' => null,
                    'status' => 'TUTUP',
                ];
            } else  {
                // Selasa - Sabtu
                $data[] = [
                    'jenis' => 'offline',
                    'hari' => $h,
                    'jam_buka' => '10:00',
                    'jam_tutup' => '20:00',
                    'status' => 'BUKA',
                ];

                
            } 

            // =====================
            // ONLINE (SETIAP HARI)
            // =====================
            $data[] = [
                'jenis' => 'online',
                'hari' => $h,
                'jam_buka' => '10:00',
                'jam_tutup' => '17:00',
                'status' => 'BUKA',
            ];
        }

        JamOperasional::insert($data);
    }

    public static function down(): void
    {
        JamOperasional::query()->delete();
    }
}
