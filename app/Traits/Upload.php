<?php

namespace App\Traits;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

trait Upload
{
	public function UploadFile(UploadedFile $file, $folder, $filename = null)
	{
		// Jika $filename tidak diberikan, buat nama acak berdasarkan UUID dan ekstensi file asli
		$filename = $filename ?? Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();

		// Simpan file dengan nama yang sudah diberikan
		try {
			return $file->storeAs(
				$folder,       // Folder tujuan
				$filename,     // Nama file
				"public"       // Disk yang digunakan (public)
			);
		} catch (Exception $e) {
			// Tangani kesalahan jika ada masalah selama penyimpanan
			Log::error("Error while uploading file: " . $e->getMessage());
			throw new Exception("Gagal mengupload file: " . $e->getMessage());
		}
	}

	public function deleteFile($path, $disk = 'local')
	{
		Storage::disk($disk)->delete($path);
	}
}
