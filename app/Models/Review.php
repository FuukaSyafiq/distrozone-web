<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Review extends Model
{
    use HasFactory;

    protected $table = "reviews";
    protected $primaryKey = 'id_review';
    public $incrementing = true;

    protected $keyType = 'int';
    protected $fillable = ['review', 'star', 'id_customer', 'id_kaos'];

    public function user()
    {
        return $this->belongsTo(User::class,'id_customer');
    }

    public function kaos()
    {
        return $this->belongsTo(Kaos::class, 'id_kaos');
    }
   
    public static function getReviewsByKaosId($kaosId)
    {
        return DB::table('reviews as rvw')
            ->select('rvw.review', 'rvw.star', 'u.name', 'rvw.created_at')
            ->distinct()
            ->join('kaos as ko', 'rvw.id_kaos', '=', 'ko.id_kaos')
            ->join('users as u', 'rvw.id_user', '=', 'u.id_user')
            ->where('ko.id_kaos', $kaosId)
            ->get();
    }
}
