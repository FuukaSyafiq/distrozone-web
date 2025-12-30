<?php

namespace App\Http\Controllers;

use App\Helpers\StoreImages;
use App\Models\Image;
use App\Traits\Upload; //import the trait
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Str;

class ImageController extends Controller
{

    public function destroy(Image $image)
    {
        // hapus file dari S3
        Storage::disk('s3')->delete($image->path);

        // hapus dari database
        $image->delete();

        return back()->with('success', 'Foto berhasil dihapus');
    }
}
