<?php

namespace Database\Seeders;

use App\Models\AnggotaKeluarga;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class InitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call(ImageSeeder::class);
        $this->call(KaosSeeder::class);
        $this->call(OngkirSeeder::class);
        $this->call(JamOperasionalSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(PermissionNameSeeder::class);
        $this->call(PermissionAdminSeeder::class);
        $this->call(PermissionKasirSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(ReviewSeeder::class);
        $this->call(TransaksiSeeder::class);
    }


    public static function down()
    {
        TransaksiSeeder::down();
        ReviewSeeder::down();
        PermissionAdminSeeder::down();
        PermissionKasirSeeder::down();
        UserSeeder::down();
        PermissionNameSeeder::down();
        JamOperasionalSeeder::down();
        ImageSeeder::down();
        OngkirSeeder::down();
        KaosSeeder::down();
        RoleSeeder::down();
    }
}
