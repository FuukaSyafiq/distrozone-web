<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ambil semua file di folder
        $files = $files = Storage::disk('public')->files('foto_karyawan');
        foreach ($files as $filePath) {

            // skip kalau bukan file

            $absolutePath = Storage::disk('public')->path($filePath);

            // upload ke S3
            $path = Storage::disk('s3')->put(
                'foto_karyawan',
                new File($absolutePath)
            );
            // contoh simpan ke DB
            Image::create([
                'path' => $path,                       // path S3
                'file_name' => basename($filePath),
                'mime_type' => mime_content_type($absolutePath),
                'size' => filesize($absolutePath)
            ]);
        }

    }

    public static function down()
    {
        Image::query()->delete();
        Storage::disk('s3')->deleteDirectory('foto_karyawan');
    }
}
