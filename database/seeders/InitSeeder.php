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
        $this->call(RoleSeeder::class);
        $this->call(PermissionNameSeeder::class);
        // $this->call(PermissionOwnerSeeder::class);
        // $this->call(PermissionPenyewaSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(ReviewSeeder::class);
    }


    public static function down()
    {
        Storage::disk('s3')->deleteDirectory('foto_karyawan');
        ReviewSeeder::down();
        // PermissionOwnerSeeder::down();
        // PermissionPenyewaSeeder::down();
        UserSeeder::down();
        PermissionNameSeeder::down();
        ImageSeeder::down();
        KaosSeeder::down();
        RoleSeeder::down();
      
    }
}
