<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PermissionName;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            [
                "role_id" => Role::getIdByRole("ADMIN"),
                "permission_name_id" => PermissionName::getIdByPermissionNameAndAction("Transaction", "CREATE")
            ],
            [
                "role_id" => Role::getIdByRole("ADMIN"),
                "permission_name_id" => PermissionName::getIdByPermissionNameAndAction("Transaction", "UPDATE")
            ],
            [
                "role_id" => Role::getIdByRole("ADMIN"),
                "permission_name_id" => PermissionName::getIdByPermissionNameAndAction("Transaction", "READ")
            ],
            [
                "role_id" => Role::getIdByRole("ADMIN"),
                "permission_name_id" => PermissionName::getIdByPermissionNameAndAction("Transaction", "DELETE")
            ],
            [
                "role_id" => Role::getIdByRole("ADMIN"),
                "permission_name_id" => PermissionName::getIdByPermissionNameAndAction("User", "CREATE")
            ],
            [
                "role_id" => Role::getIdByRole("ADMIN"),
                "permission_name_id" => PermissionName::getIdByPermissionNameAndAction("User", "VIEWPAGE")
            ],
            [
                "role_id" => Role::getIdByRole("ADMIN"),
                "permission_name_id" => PermissionName::getIdByPermissionNameAndAction("User", "UPDATE")
            ],
            [
                "role_id" => Role::getIdByRole("ADMIN"),
                "permission_name_id" => PermissionName::getIdByPermissionNameAndAction("User", "READ")
            ],
            [
                "role_id" => Role::getIdByRole("ADMIN"),
                "permission_name_id" => PermissionName::getIdByPermissionNameAndAction("User", "DELETE")
            ],

        ];

        foreach ($datas as $data) {
            Permission::create($data);
        }
    }

    public static function down()
    {
        Permission::where('role_id', Role::getIdByRole("ADMIN"))->delete();
    }
}
