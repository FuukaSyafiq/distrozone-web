<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionName extends Model
{
	use HasFactory;

	// Nonaktifkan timestampt
	public $timestamps = false;
	protected $table = 'permission_name';

	protected $fillable = [
		'name',
		'action',
	];

	public static function getIdByPermissionNameAndAction($module, $action)
	{

		$result = self::where('name', $module)->where('action', $action)->first('id');

		return isset($result) ? $result->id : null;
	}

	public static function getAllModules()
	{
		return self::select(['name', 'description'])->distinct()->orderBy('name')->get();
	}

	public static function deleteNameAndAction($name) {
		return self::where('name', $name)->delete();
	}

}