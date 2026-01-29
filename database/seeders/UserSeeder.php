<?php

namespace Database\Seeders;

use App\Helpers\NikVerified;
use App\Helpers\Status;
use App\Models\Image;
use App\Models\Kota;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Hash;
use App\Models\User;
use Illuminate\Support\Facades\Hash as FacadesHash;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\File;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $password = FacadesHash::make('password');
        // dd(Image::getImageByFilename("mbak-cantik.jpeg")->id);
        $users = [
            // ===== ADMIN =====
            [
                'nama' => 'Bos Distrozone',
                'password' => $password,
                'role_id' => Role::getIdByRole('ADMIN'),
                'alamat_lengkap' => 'Bogor Tengah, Kota Bogor, Jawa Barat',
                'no_telepon' => '0833479824632',
                'email' => 'lettucelaugh@autistiche.org',
                'nik' => '34352352352354',
                'foto_user' => null,
                'kota_id' => Kota::getKota('BOGOR')->id,
                'nik_verified' => NikVerified::APPROVED,
                'email_verified_at' => now(),
            ],

            // ===== KASIR 2 =====
            [
                'nama' => 'Mas Robert',
                'password' => $password,
                'role_id' => Role::getIdByRole('KASIR'),
                'alamat_lengkap' => 'Beji, Kota Depok, Jawa Barat',
                'no_telepon' => '083226872332',
                'email' => 'aduh@cumallover.me',
                'nik' => '34352454352352354',
                'foto_user' => null,
                'kota_id' => Kota::getKota('DEPOK')->id,
                'nik_verified' => NikVerified::APPROVED,
                'email_verified_at' => now(),
            ],

            // ===== CUSTOMER =====
            [
                'nama' => 'Mas Syafiq',
                'password' => $password,
                'role_id' => Role::getIdByRole('CUSTOMER'),
                'alamat_lengkap' => 'Kertonegoro, Kabupaten Ngawi, Jawa Timur',
                'no_telepon' => '088234974632',
                'email' => 'syafiqparadisam@gmail.com',
                'nik' => '3434351952352354',
                'foto_user' => null,
                'kota_id' => Kota::getKota('BANDUNG')->id,
                'nik_verified' => NikVerified::PENDING,
                'email_verified_at' => now(),
            ],

        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }

    public static function down()
    {
        Image::query()->delete();
        Storage::disk('s3')->deleteDirectory('foto_karyawan');
        User::query()->delete();
    }
}
