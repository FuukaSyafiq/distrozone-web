<?php

namespace App\Http\Controllers;

use App\Helpers\StoreImages;
use App\Models\Image;
use App\Traits\Upload; //import the trait
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Str;

class ImageController extends Controller
{
    use Upload; //add this trait

    public function store(Request $request, string $html_key)
    {
        try {
            // Cek apakah file ada di request
            if ($request->hasFile($html_key)) {
                $file = $request->file($html_key);

                // Membuat nama file dengan UUID dan ekstensi file asli
                $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '-' . Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();

                // Upload file menggunakan trait
                $path = $this->UploadFile($file, "KTP", $fileName); // Menggunakan method dari trait Upload

                // Simpan informasi file ke database
                // $fileDB = StoreImages::StoreImages($fileName, null, null, false, $file->getClientMimeType(), $file->getSize());
                $fileDB = Image::create([
                    'file_name' => $fileName,
                    'mime_type' => $file->getClientMimeType(),
                    'path' => '/storage/' . $path,  // Menghapus double slash
                    'size' => $file->getSize(),
                    "id_kaos" => null,
                ]);

                return $fileDB;  // Mengembalikan object Image yang baru disimpan
            }

            throw new Exception("File tidak ditemukan di request.");
        } catch (Exception $e) {
            // Log error untuk debugging
            Log::error('Error while uploading files: ' . $e->getMessage());
            throw new Exception("Error while uploading files: " . $e->getMessage(), 1);
        }
    }
}
