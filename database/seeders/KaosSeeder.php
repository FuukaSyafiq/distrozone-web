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
                "description" => "Kaos hitam polos lengan pendek berbahan Cotton Combed berkualitas tinggi yang lembut, adem, dan nyaman dipakai seharian. Dengan potongan simple dan warna hitam yang elegan, kaos ini cocok untuk berbagai aktivitas, mulai dari harian, nongkrong, hingga dipadukan dengan outfit kasual maupun semi-formal. Jahitan rapi dan bahan tidak mudah melar menjadikannya pilihan tepat untuk kamu yang mengutamakan kenyamanan dan tampilan timeless.",
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
                "description" => "Tampil lebih berani dengan kaos merah polos berbahan Cotton Combed premium yang halus dan menyerap keringat. Desain lengan pendek dengan warna merah yang solid membuat kaos ini cocok untuk gaya kasual maupun streetwear. Nyaman dipakai dalam waktu lama, tidak panas, dan tetap terlihat stylish untuk aktivitas sehari-hari.",
                "harga_jual" => 50000,
                "harga_pokok" => 40000,
                "stok_kaos" => 100
            ],
            [
                "merek_kaos" => "Cotton Combed",
                "nama_kaos" => "Kaos abu abu polos lengan pendek Cotton",
                "description" => "Kaos abu-abu polos ini dibuat dari Cotton Combed pilihan yang ringan dan breathable, memberikan rasa nyaman maksimal saat digunakan. Warna abu-abu yang netral mudah dipadukan dengan berbagai outfit, cocok untuk gaya santai maupun kegiatan luar ruangan. Dengan potongan modern dan bahan awet, kaos ini menjadi item wajib di lemari pakaian kamu.",
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
                "description" => "Kaos putih polos berbahan Cotton Combed berkualitas yang lembut, tidak kaku, dan nyaman di kulit. Warna putih yang bersih memberikan kesan fresh dan minimalis, cocok digunakan sebagai outfit utama atau inner. Ideal untuk pemakaian harian, kaos ini hadir dengan jahitan kuat dan bahan yang tidak mudah rusak meski sering dicuci.",
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
                "description" => "Kaos merah gelap polos dengan bahan Cotton Combed premium ini menawarkan kombinasi kenyamanan dan kesan elegan. Warna merah gelap memberikan tampilan lebih dewasa dan eksklusif, cocok untuk kamu yang ingin tampil beda namun tetap simpel. Nyaman dipakai seharian, adem, dan pas untuk berbagai aktivitas kasual.",
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
                "description" => "Kaos polo biru lengan pendek ini dibuat dari bahan nyaman dan berkualitas, memberikan tampilan kasual yang tetap rapi dan stylish. Desain polo dengan kerah klasik cocok digunakan untuk aktivitas santai maupun semi-formal. Warna biru yang elegan serta bahan yang adem menjadikan kaos ini pilihan ideal untuk tampil percaya diri di berbagai kesempatan.",
                "warna_kaos" => "biru",
                "ukuran" => "XL",
                "harga_jual" => 70000,
                "harga_pokok" => 55000,
                "stok_kaos" => 100
            ],
        ];
        foreach ($kaoss as $kaos) {
            $kaos = Kaos::create($kaos);
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
            $namaKaos = pathinfo(basename($filePath), PATHINFO_FILENAME);
            $kaos = Kaos::getKaosByName($namaKaos);

            // contoh simpan ke DB
            Image::create([
                'path' => $path,                       // path S3
                'file_name' => basename($filePath),
                'mime_type' => mime_content_type($absolutePath),
                'size' => filesize($absolutePath),
                'id_kaos' => $kaos->id_kaos
            ]);
        }
    }
    public static function down(): void
    {
        Kaos::query()->delete();
        Storage::disk('s3')->deleteDirectory('kaos');
    }
}
