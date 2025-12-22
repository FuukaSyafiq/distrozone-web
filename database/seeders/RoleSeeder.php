<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = [
            'ADMIN',
            'KASIR',
            'CUSTOMER'
        ];

        foreach ($role as $name) {
            Role::create(['role' => $name]);
        }
    }

    public static function down()
    {
        Role::query()->delete();
    }
}
