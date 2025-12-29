<?php

namespace Database\Seeders;

use App\Models\Kaos;
use App\Models\Image;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class KaosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kaoss = [
            [
                "merek_kaos" => "Cotton Combed",
                "nama_kaos" => "Kaos hitam polos lengan pendek Cotton",
                "type_kaos" => "lengan pendek",
                "warna_kaos" => "hitam",
                "ukuran" => "L",
                "harga_jual" => 50000,
                "harga_pokok" => 40000,
                "stok_kaos" => 100
            ],
            [
                "merek_kaos" => "Cotton Combed",
                "nama_kaos" => "Kaos merah polos lengan pendek Cotton",

                "type_kaos" => "lengan pendek",
                "warna_kaos" => "merah",
                "ukuran" => "L",
                "harga_jual" => 50000,
                "harga_pokok" => 40000,
                "stok_kaos" => 100
            ],
            [
                "merek_kaos" => "Cotton Combed",
                "nama_kaos" => "Kaos abu abu polos lengan pendek Cotton",

                "type_kaos" => "lengan pendek",
                "warna_kaos" => "abu",
                "ukuran" => "XL",
                "harga_jual" => 70000,
                "harga_pokok" => 55000,
                "stok_kaos" => 100
            ],
            [
                "merek_kaos" => "Cotton Combed",
                "nama_kaos" => "Kaos putih polos lengan pendek Cotton",

                "type_kaos" => "lengan pendek",
                "warna_kaos" => "putih",
                "ukuran" => "L",
                "harga_jual" => 55000,
                "harga_pokok" => 40000,
                "stok_kaos" => 100
            ],
            [
                "merek_kaos" => "Cotton Combed",
                "nama_kaos" => "Kaos merah gelap polos lengan pendek Cotton",

                "type_kaos" => "lengan pendek",
                "warna_kaos" => "merah-gelap",
                "ukuran" => "L",
                "harga_jual" => 55000,
                "harga_pokok" => 45000,
                "stok_kaos" => 100
            ],
            [
                "merek_kaos" => "polo",
                "nama_kaos" => "Kaos biru polo lengan pendek",
                "type_kaos" => "lengan pendek",
                "warna_kaos" => "biru",
                "ukuran" => "XL",
                "harga_jual" => 70000,
                "harga_pokok" => 55000,
                "stok_kaos" => 100
            ],
        ];
        foreach ($kaoss as $kaos) {
            $kaos = Kaos::create(
                [
                    "merek_kaos" => $kaos['merek_kaos'],
                    "nama_kaos" => $kaos['nama_kaos'],
                    "type_kaos" => $kaos['type_kaos'],
                    "warna_kaos" => $kaos['warna_kaos'],
                    "ukuran" => $kaos['ukuran'],
                    "harga_jual" => $kaos['harga_jual'],
                    "harga_pokok" => $kaos['harga_pokok'],
                    "stok_kaos" => $kaos['stok_kaos']
                ]
            );
        }

        $files = $files = Storage::disk('public')->files('kaos');
        foreach ($files as $filePath) {

            // skip kalau bukan file

            $absolutePath = Storage::disk('public')->path($filePath);
            // upload ke S3
            $path = Storage::disk('s3')->put(
                'kaos',
                new File($absolutePath)
            );

            // contoh simpan ke DB
            Image::create([
                'path' => $path,                       // path S3
                'file_name' => basename($filePath),
                'mime_type' => mime_content_type($absolutePath),
                'size' => filesize($absolutePath),
            ]);
        }
    }
    public static function down(): void
    {
        Kaos::query()->delete();
        Storage::disk('s3')->deleteDirectory('kaos');
    }
}
