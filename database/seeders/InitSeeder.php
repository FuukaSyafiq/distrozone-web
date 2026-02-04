<?php

namespace Database\Seeders;

use App\Models\UkuranKaos;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class InitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $this->call(ProvinsiSeeder::class);
         $this->call(KotaSeeder::class);
         $this->call(WarnaSeeder::class);
         $this->call(UkuranSeeder::class);
         $this->call(OngkirSeeder::class);
         $this->call(JamOperasionalSeeder::class);
         $this->call(RoleSeeder::class);
         $this->call(UserSeeder::class);
    }


    public static function down()
    {
         ProvinsiSeeder::down();
         KotaSeeder::down();
         UserSeeder::down();
         JamOperasionalSeeder::down();
         OngkirSeeder::down();
         WarnaSeeder::down();
         RoleSeeder::down();
         UkuranKaos::delete();
    }
}
