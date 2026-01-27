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

        if ($image->path && Storage::disk('s3')->exists($image->path)) {
            Storage::disk('s3')->delete($image->path);
        }

        // Hapus record DB
        $image->delete();

        return back()->with('success', 'Gambar berhasil dihapus');
    }
}
