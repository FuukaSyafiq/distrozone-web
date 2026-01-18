<?php

namespace Database\Seeders;

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
        $this->call(ProvinsiSeeder::class);
        $this->call(KotaSeeder::class);
        $this->call(WarnaSeeder::class);
        // $this->call(KaosSeeder::class);
        $this->call(OngkirSeeder::class);
        $this->call(JamOperasionalSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        // $this->call(TransaksiSeeder::class);
    }


    public static function down()
    {
        ProvinsiSeeder::down();
        KotaSeeder::down();
        // TransaksiSeeder::down();
        UserSeeder::down();
        JamOperasionalSeeder::down();
        OngkirSeeder::down();
        // KaosSeeder::down();
        WarnaSeeder::down();
        RoleSeeder::down();
    }
}
