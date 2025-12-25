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
            ["nama" => "Mbak lua", "username" => "lua_kasir", "password" => $password, "role_id" => Role::getIdByRole("KASIR"), "alamat" => "Mantingan, Ngawi, Jawa timur", "no_telepon" => "0832782743272334", "email" => "lua@lua.my.id", "nik" => "352352353455433", "foto_id" => Image::getImageByFilename("mbak-cantik.jpeg")->id, "verified" => true, "email_verified_at" => now()],

            //admin 
            ["nama" => "Bos Fajrul", "username" => "admin", "password" => $password, "role_id" => Role::getIdByRole("ADMIN"), "alamat" => "Geneng, Ngawi, Jawa timur", "no_telepon" => "0833473263274632", "email" => "fajrul@fajrul.com", "nik" => "34352352352354", "foto_id" => null,  "verified" => true, "email_verified_at" => now()],

            // kasir 2
            ["nama" => "Mas robert", "username" => "robert_kasir", "password" => $password, "role_id" => Role::getIdByRole("KASIR"), "alamat" => "Kertonegoro, Ngawi, Jawa timur", "no_telepon" => "0832263274632", "email" => "robert@robert.dev", "nik" => "34352454352352354", "foto_id" => Image::getImageByFilename("china1.jpeg")->id, "verified" => true],

            ["nama" => "Mas aril", "username" => "aril", "password" => $password, "role_id" => Role::getIdByRole("CUSTOMER"), "alamat" => "Kertonegoro, Ngawi, Jawa timur", "no_telepon" => "0832263274632", "email" => "aril@yahoo.com", "nik" => "3434351952352354", "foto_id" => Image::getImageByFilename("bos.jpeg")->id]
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
