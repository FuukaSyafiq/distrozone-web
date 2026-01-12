<?php

namespace Database\Seeders;

use App\Models\Kaos;
use App\Models\Image;
use App\Models\KaosVariant;
use App\Models\MerekKaos;
use App\Models\TypeKaos;
use App\Models\UkuranKaos;
use App\Models\Warna;
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
                "id_warna_kaos" => Warna::getWarna('black')->id,
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
                "id_warna_kaos" => Warna::getWarna('red-bright')->id,
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
                "id_warna_kaos" => Warna::getWarna('gray')->id,
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
                "id_warna_kaos" => Warna::getWarna('white')->id,
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
                "id_warna_kaos" => Warna::getWarna('red-dark')->id,
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
                "id_warna_kaos" => Warna::getWarna('skyblue')->id,
                "ukuran" => "XL",
                "harga_jual" => 70000,
                "harga_pokok" => 55000,
                "stok_kaos" => 100
            ],
        ];
        foreach ($kaoss as $item) {

            // 1️⃣ MEREK
            $merek = MerekKaos::firstOrCreate([
                'merek' => $item['merek_kaos']
            ]);

            // 2️⃣ TYPE KAOS
            $type = TypeKaos::firstOrCreate([
                'type' => $item['type_kaos']
            ]);

            // 3️⃣ CREATE KAOS (TANPA warna & ukuran)
            $kaos = Kaos::create([
                'nama_kaos'    => $item['nama_kaos'],
                'merek_id'     => $merek->id,
                'type_id'      => $type->id,
                'description'  => $item['description'],
                'harga_jual'   => $item['harga_jual'],
                'harga_pokok'  => $item['harga_pokok'],
            ]);

            // 4️⃣ UKURAN
            $ukuran = UkuranKaos::firstOrCreate([
                'ukuran' => $item['ukuran']
            ]);

            // 5️⃣ VARIANT
            KaosVariant::create([
                'kaos_id'   => $kaos->id_kaos,   // ✅ BENAR
                'warna_id'  => $item['id_warna_kaos'],
                'ukuran_id' => $ukuran->id,
                'stok_kaos' => $item['stok_kaos'],
                'image_path' => null              // ✅ image diisi belakangan
            ]);
        }

        $files = Storage::disk('public')->files('kaos');

        foreach ($files as $filePath) {

            $absolutePath = Storage::disk('public')->path($filePath);

            $path = Storage::disk('s3')->put(
                'kaos',
                new File($absolutePath)
            );

            
            $filename = pathinfo(basename($filePath), PATHINFO_FILENAME);

            $parts = explode('-', $filename);
            if (count($parts) < 2) continue;

            $warnaSlug = array_pop($parts);
            $namaKaos  = implode('-', $parts);

            $kaos  = Kaos::where('nama_kaos', $namaKaos)->first();
            $warna = Warna::getWarna($warnaSlug);

            if (!$kaos || !$warna) continue;

            $variant = KaosVariant::where('kaos_id', $kaos->id_kaos)
                ->where('warna_id', $warna->id)
                ->first();

            if (!$variant) continue;

            $variant->update([
                'image_path' => $path
            ]);
        }
    }
    public static function down(): void
    {
        Kaos::query()->delete();
        Storage::disk('s3')->deleteDirectory('kaos');
    }
}
