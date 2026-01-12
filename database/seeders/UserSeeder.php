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

            // ===== KASIR =====
            [
                'nama' => 'Mbak Lua',
                'password' => $password,
                'role_id' => Role::getIdByRole('KASIR'),
                'alamat_lengkap' => 'Kelapa Gading, Jakarta Utara, DKI Jakarta',
                'no_telepon' => '08327827334',
                'email' => 'lua@lua.my.id',
                'nik' => '352352353455433',
                'foto_user' => Image::getImageByFilename('mbak-cantik.jpeg')->path,
                'kota_id' => Kota::getKota('JAKARTA')->id,
                'nik_verified' => NikVerified::APPROVED,
                'email_verified_at' => now(),
            ],

            // ===== ADMIN =====
            [
                'nama' => 'Bos Fajrul',
                'password' => $password,
                'role_id' => Role::getIdByRole('ADMIN'),
                'alamat_lengkap' => 'Bogor Tengah, Kota Bogor, Jawa Barat',
                'no_telepon' => '0833479824632',
                'email' => 'fajrul@fajrul.com',
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
                'email' => 'robert@robert.dev',
                'nik' => '34352454352352354',
                'foto_user' => Image::getImageByFilename('china1.jpeg')->path,
                'kota_id' => Kota::getKota('DEPOK')->id,
                'nik_verified' => NikVerified::APPROVED,
                'email_verified_at' => now(),
            ],

            // ===== CUSTOMER =====
            [
                'nama' => 'Mas Aril',
                'password' => $password,
                'role_id' => Role::getIdByRole('CUSTOMER'),
                'alamat_lengkap' => 'Kertonegoro, Kabupaten Ngawi, Jawa Timur',
                'no_telepon' => '088234974632',
                'email' => 'aril@yahoo.com',
                'nik' => '3434351952352354',
                'foto_user' => Image::getImageByFilename('bos.jpeg')->path,
                'kota_id' => Kota::getKota('NGAWI')->id,
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
        User::query()->delete();
    }
}
