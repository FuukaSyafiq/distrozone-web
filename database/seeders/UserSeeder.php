<?php

namespace Database\Seeders;

use App\Models\Image;
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
        $datas = [
            // kasir 1
            ["nama" => "Mbak lua",  "password" => $password, "role_id" => Role::getIdByRole("KASIR"), "alamat" => "Mantingan, Ngawi, Jawa timur", "no_telepon" => "08327827334", "email" => "lua@lua.my.id", "nik" => "352352353455433", "foto_id" => Image::getImageByFilename("mbak-cantik.jpeg")->id, "verified" => true, "email_verified_at" => now()],

            //admin 
            ["nama" => "Bos Fajrul",  "password" => $password, "role_id" => Role::getIdByRole("ADMIN"), "alamat" => "Geneng, Ngawi, Jawa timur", "no_telepon" => "0833479824632", "email" => "fajrul@fajrul.com", "nik" => "34352352352354", "foto_id" => null,  "verified" => true, "email_verified_at" => now()],

            // kasir 2
            ["nama" => "Mas robert",  "password" => $password, "role_id" => Role::getIdByRole("KASIR"), "alamat" => "Kertonegoro, Ngawi, Jawa timur", "no_telepon" => "083226872332", "email" => "robert@robert.dev", "nik" => "34352454352352354", "foto_id" => Image::getImageByFilename("china1.jpeg")->id, "verified" => true, "email_verified_at" => now()],

            ["nama" => "Mas aril", "password" => $password, "role_id" => Role::getIdByRole("CUSTOMER"), "alamat" => "Kertonegoro, Ngawi, Jawa timur", "no_telepon" => "088234974632", "email" => "aril@yahoo.com", "nik" => "3434351952352354", "foto_id" => Image::getImageByFilename("bos.jpeg")->id, "email_verified_at" => now()]
        ];

        foreach ($datas as $value) {
            User::create($value);
        }
    }

    public static function down()
    {
        User::query()->delete();
    }
}
