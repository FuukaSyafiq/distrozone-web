<?php

namespace App\Helpers;

use App\Models\Image;

/**
 * Store image information in the database.
 *
 * @param string $filename The name of the file to be stored.
 * @param int|null $roomId The ID of the room associated with the image (nullable).
 * @param int|null $tipeRoomId The ID of the room type associated with the image (nullable).
 * @param bool $isVr Whether the image is a VR image (default: false).
 * @param string|null $mimeType The MIME type of the file (nullable).
 * @param int|null $size The size of the file in bytes (nullable).
 * @return \App\Models\Image The created image record.
 */

class StoreImages
{
	public static function StoreImages($filename, $roomId = null, $tipeRoomId = null, $isVr = false, $mimeType = null, $size = null)
	{
		$fileDB = Image::create([
			'file_name' => $filename,
			'mime_type' => $mimeType,
			'path' => '/storage' . '/' . $filename,
			'size' => $size,
			"room_id" => $roomId,
			"tipe_room_id" => $tipeRoomId,
			"is_vr" => $isVr
		]);
		return $fileDB;
	}
}
