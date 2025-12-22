<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use App\Models\Image;

class DeleteImages
{
	public static function DeleteImages($fileName)
	{
		if (Storage::disk("public")->exists($fileName)) {
			Storage::disk("public")->delete($fileName);
		}

		Image::where('file_name', $fileName)->delete();
	}

	public static function DeleteFromStorage($fileName)
	{
		if (Storage::disk("public")->exists($fileName)) {
			Storage::disk("public")->delete($fileName);
		}
	}
}
