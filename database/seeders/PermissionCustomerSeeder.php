<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PermissionName;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionCustomerSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		$datas = [
			[
				"role_id" => Role::getIdByRole("PENYEWA"),
				"permission_name_id" => PermissionName::getIdByPermissionNameAndAction("Room", "CREATE")
			],
			[
				"role_id" => Role::getIdByRole("PENYEWA"),
				"permission_name_id" => PermissionName::getIdByPermissionNameAndAction("Room", "UPDATE")
			],
			[
				"role_id" => Role::getIdByRole("PENYEWA"),
				"permission_name_id" => PermissionName::getIdByPermissionNameAndAction("Room", "READ")
			],
			[
				"role_id" => Role::getIdByRole("PENYEWA"),
				"permission_name_id" => PermissionName::getIdByPermissionNameAndAction("Room", "DELETE")
			],
			[
				"role_id" => Role::getIdByRole("PENYEWA"),
				"permission_name_id" => PermissionName::getIdByPermissionNameAndAction("Rented Room", "READ")
			],
			[
				"role_id" => Role::getIdByRole("PENYEWA"),
				"permission_name_id" => PermissionName::getIdByPermissionNameAndAction("Room", "VIEWPAGE")
			],
			[
				"role_id" => Role::getIdByRole("PENYEWA"),
				"permission_name_id" => PermissionName::getIdByPermissionNameAndAction("User", "ACCESS")
			],
			[
				"role_id" => Role::getIdByRole("PENYEWA"),
				"permission_name_id" => PermissionName::getIdByPermissionNameAndAction("User", "VIEWPAGE")
			],
		];

		foreach ($datas as $data) {
			Permission::create($data);
		}
	}

	public static function down()
	{
		Permission::where('role_id', Role::getIdByRole("PENYEWA"))->delete();
	}
}
