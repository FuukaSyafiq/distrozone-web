<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JamOperasional extends Model
{
    use HasFactory;

    protected $table = "jam_operasional";
    public $timestamps = false;

    protected $fillable = [
        'hari',
        'jam_buka',
        'jam_tutup',
        'status',
        'jenis'
    ];

    /**
     * Cek apakah toko sedang buka untuk jenis operasional tertentu
     */
    public static function isBuka(string $jenis = 'OFFLINE'): bool
    {
        try {
            $now = Carbon::now();
            $currentTime = $now->format('H:i:s');
            $today = strtoupper($now->isoFormat('dddd')); // e.g., MONDAY, TUESDAY

            // Konversi hari Inggris ke Indonesia jika perlu
            $hariIndonesia = self::convertDayToIndo($today);

            $query = self::where('hari', $hariIndonesia)
                ->where('jenis', $jenis)
                ->where('status', 'BUKA')
                ->where(function ($query) use ($currentTime) {
                    // Kondisi untuk jam normal (jam_buka <= jam_tutup)
                    $query->where(function ($q) use ($currentTime) {
                        $q->whereRaw('jam_buka <= jam_tutup')
                            ->whereRaw('? BETWEEN jam_buka AND jam_tutup', [$currentTime]);
                    })
                        // Kondisi untuk jam yang melewati tengah malam (jam_buka > jam_tutup)
                        ->orWhere(function ($q) use ($currentTime) {
                            $q->whereRaw('jam_buka > jam_tutup')
                                ->where(function ($q2) use ($currentTime) {
                                    $q2->whereRaw('? >= jam_buka', [$currentTime])
                                        ->orWhereRaw('? <= jam_tutup', [$currentTime]);
                                });
                        });
                });
            return $query->exists();
        } catch (\Exception $e) {
            dd('Error checking jam operasional: ' . $e->getMessage());
        }
    }


    /**
     * Konversi hari Inggris ke Indonesia
     */
    private static function convertDayToIndo(string $dayEnglish): string
    {
        $days = [
            'SUNDAY'    => 'MINGGU',
            'MONDAY'    => 'SENIN',
            'TUESDAY'   => 'SELASA',
            'WEDNESDAY' => 'RABU',
            'THURSDAY'  => 'KAMIS',
            'FRIDAY'    => 'JUMAT',
            'SATURDAY'  => 'SABTU',
        ];

        return $days[$dayEnglish] ?? $dayEnglish;
    }

    /**
     * Dapatkan jam operasional hari ini
     */
    public static function getJamHariIni(string $jenis = 'OFFLINE'): ?self
    {
        try {
            $today = Carbon::now();
            $hariIndonesia = self::convertDayToIndo(strtoupper($today->isoFormat('dddd')));

            return self::where('hari', $hariIndonesia)
                ->where('jenis', $jenis)
                ->first();
        } catch (\Exception $e) {
            dd('Error getting jam operasional hari ini: ' . $e->getMessage());
        }
    }

    /**
     * Cek apakah toko sedang tutup
     */
    public static function isTutup(string $jenis = 'OFFLINE'): bool
    {
        return !self::isBuka($jenis);
    }

    /**
     * Scope untuk query jam buka
     */
    public function scopeSedangBuka($query, string $jenis = 'OFFLINE')
    {
        $now = Carbon::now();
        $currentTime = $now->format('H:i:s');
        $hariIndonesia = self::convertDayToIndo(strtoupper($now->isoFormat('dddd')));

        return $query->where('hari', $hariIndonesia)
            ->where('jenis', $jenis)
            ->where('status', 'BUKA')
            ->where(function ($query) use ($currentTime) {
                $query->where(function ($q) use ($currentTime) {
                    $q->whereRaw('jam_buka <= jam_tutup')
                        ->whereRaw('? BETWEEN jam_buka AND jam_tutup', [$currentTime]);
                })
                    ->orWhere(function ($q) use ($currentTime) {
                        $q->whereRaw('jam_buka > jam_tutup')
                            ->where(function ($q2) use ($currentTime) {
                                $q2->whereRaw('? >= jam_buka', [$currentTime])
                                    ->orWhereRaw('? <= jam_tutup', [$currentTime]);
                            });
                    });
            });
    }

    /**
     * Scope untuk jam operasional hari tertentu
     */
    public function scopeHariIni($query)
    {
        $today = Carbon::now();
        $hariIndonesia = self::convertDayToIndo(strtoupper($today->isoFormat('dddd')));

        return $query->where('hari', $hariIndonesia);
    }
}
