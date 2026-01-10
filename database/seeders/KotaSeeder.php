<?php

namespace Database\Seeders;

use App\Models\Kota;
use App\Models\Provinsi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kotas = [
            // ===== JAWA =====
            [
                'kota' => 'TANGERANG',
                'provinsi_id' => Provinsi::getProvinsi('DKI JAKARTA')->id
            ],
            [
                'kota' => 'BOGOR',
                'provinsi_id' => Provinsi::getProvinsi('DKI JAKARTA')->id
            ],
            [
                'kota' => 'JAKARTA',
                'provinsi_id' => Provinsi::getProvinsi('DKI JAKARTA')->id
            ],
            [
                'kota' => 'BEKASI',
                'provinsi_id' => Provinsi::getProvinsi('DKI JAKARTA')->id
            ],
            [
                'kota' => 'DEPOK',
                'provinsi_id' => Provinsi::getProvinsi('DKI JAKARTA')->id
            ],
            [
                'kota' => 'BANDUNG',
                'provinsi_id' => Provinsi::getProvinsi('JAWA BARAT')->id
            ],
            [
                'kota' => 'BEKASI',
                'provinsi_id' => Provinsi::getProvinsi('JAWA BARAT')->id
            ],
            [
                'kota' => 'DEPOK',
                'provinsi_id' => Provinsi::getProvinsi('JAWA BARAT')->id
            ],
            [
                'kota' => 'SEMARANG',
                'provinsi_id' => Provinsi::getProvinsi('JAWA TENGAH')->id
            ],
            [
                'kota' => 'SURAKARTA',
                'provinsi_id' => Provinsi::getProvinsi('JAWA TENGAH')->id
            ],
            [
                'kota' => 'YOGYAKARTA',
                'provinsi_id' => Provinsi::getProvinsi('DI YOGYAKARTA')->id
            ],
            [
                'kota' => 'SURABAYA',
                'provinsi_id' => Provinsi::getProvinsi('JAWA TIMUR')->id
            ],
            [
                'kota' => 'SIDOARJO',
                'provinsi_id' => Provinsi::getProvinsi('JAWA TIMUR')->id
            ],
            [
                'kota' => 'NGAWI',
                'provinsi_id' => Provinsi::getProvinsi('JAWA TIMUR')->id
            ],
            [
                'kota' => 'MALANG',
                'provinsi_id' => Provinsi::getProvinsi('JAWA TIMUR')->id
            ],

            // ===== SUMATERA =====
            [
                'kota' => 'MEDAN',
                'provinsi_id' => Provinsi::getProvinsi('SUMATERA UTARA')->id
            ],
            [
                'kota' => 'BINJAI',
                'provinsi_id' => Provinsi::getProvinsi('SUMATERA UTARA')->id
            ],
            [
                'kota' => 'PADANG',
                'provinsi_id' => Provinsi::getProvinsi('SUMATERA BARAT')->id
            ],
            [
                'kota' => 'PEKANBARU',
                'provinsi_id' => Provinsi::getProvinsi('RIAU')->id
            ],
            [
                'kota' => 'BATAM',
                'provinsi_id' => Provinsi::getProvinsi('KEPULAUAN RIAU')->id
            ],
            [
                'kota' => 'PALEMBANG',
                'provinsi_id' => Provinsi::getProvinsi('SUMATERA SELATAN')->id
            ],
            [
                'kota' => 'BANDAR LAMPUNG',
                'provinsi_id' => Provinsi::getProvinsi('LAMPUNG')->id
            ],
            [
                'kota' => 'BANDA ACEH',
                'provinsi_id' => Provinsi::getProvinsi('ACEH')->id
            ],
        ];


        foreach ($kotas as $kota) {
            kota::create([
                'kota' => $kota['kota'],
                'provinsi_id' => $kota['provinsi_id']
            ]);
        }
    }

    public static function down(): void
    {
        Kota::query()->delete();
    }
}
