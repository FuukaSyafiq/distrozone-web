<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = "images";

    

    protected $fillable = [
        'file_name',
        'mime_type',
        'path',
        'size',
    ];

    public static function getFileNameById($id)
    {
        return self::where("id", $id)->first();
    }

    public static function getImageByFilename($filename)
    {
        return self::where("file_name", $filename)->first();
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
