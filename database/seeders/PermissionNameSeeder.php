<?php

namespace Database\Seeders;

use App\Models\PermissionName;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionNameSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{

		$datas = [
			["name" => "Transaction", "action" => "CREATE"],
			["name" => "Transaction", "action" => "READ"],
			["name" => "Transaction", "action" => "DELETE"],
			["name" => "Transaction", "action" => "UPDATE"],
			["name" => "Transaction", "action" => "VIEWPAGE"],
			["name" => "User", "action" => "CREATE"],
			["name" => "User", "action" => "READ"],
			["name" => "User", "action" => "DELETE"],
			["name" => "User", "action" => "UPDATE"],
			["name" => "User", "action" => "VIEWPAGE"],
			["name" => "User", "action" => "ACCESS"],
			["name" => "Kaos", "action" => "UPDATE"],
			["name" => "Kaos", "action" => "READ"],
			["name" => "Kaos", "action" => "CREATE"],
			["name" => "Kaos", "action" => "ACCESS"],
			["name" => "Karyawan", "action" => "READ"],
			["name" => "Karyawan", "action" => "CREATE"],
			["name" => "Karyawan", "action" => "DELETE"],
			["name" => "Karyawan", "action" => "ACCESS"],
		];


		foreach ($datas as $value) {
			PermissionName::create($value);
		}
	}
	public static function down()
	{
		PermissionName::query()->delete();
	}
}
