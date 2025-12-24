<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PermissionName;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionKasirSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		$datas = [
			[
				"role_id" => Role::getIdByRole("KASIR"),
				"permission_name_id" => PermissionName::getIdByPermissionNameAndAction("Transaction", "CREATE")
			],
			[
				"role_id" => Role::getIdByRole("KASIR"),
				"permission_name_id" => PermissionName::getIdByPermissionNameAndAction("Transaction", "UPDATE")
			],
			[
				"role_id" => Role::getIdByRole("KASIR"),
				"permission_name_id" => PermissionName::getIdByPermissionNameAndAction("Transaction", "READ")
			],
			[
				"role_id" => Role::getIdByRole("KASIR"),
				"permission_name_id" => PermissionName::getIdByPermissionNameAndAction("Transaction", "DELETE")
			],
			[
				"role_id" => Role::getIdByRole("KASIR"),
				"permission_name_id" => PermissionName::getIdByPermissionNameAndAction("Transaction", "READ")
			],
			[
				"role_id" => Role::getIdByRole("KASIR"),
				"permission_name_id" => PermissionName::getIdByPermissionNameAndAction("Transaction", "VIEWPAGE")
			],
			[
				"role_id" => Role::getIdByRole("KASIR"),
				"permission_name_id" => PermissionName::getIdByPermissionNameAndAction("User", "ACCESS")
			],
			[
				"role_id" => Role::getIdByRole("KASIR"),
				"permission_name_id" => PermissionName::getIdByPermissionNameAndAction("User", "VIEWPAGE")
			],
		];

		foreach ($datas as $data) {
			Permission::create($data);
		}
	}

	public static function down()
	{
		Permission::where('role_id', Role::getIdByRole("KASIR"))->delete();
	}
}
