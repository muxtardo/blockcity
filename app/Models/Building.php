<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Building extends Model
{
	use HasFactory, SoftDeletes;

	protected $fillable = [
		'name',
		'rarity',
		'drop_chance',
		'incomes',
		'upgrade_cost',
		'images',
	];

	public static function mint()
	{
		/*
			Query to get a random house:
				SELECT `*`
				FROM (SELECT `name`, SUM(drop_chance) OVER (ORDER BY id) AS prob FROM buildings CROSS JOIN (SELECT SUM(drop_chance) total FROM buildings) as c) as b
				WHERE prob >= RAND() * total
				ORDER BY prob
				LIMIT 1;
		*/

		// Soma todos os drop_chance de todos os buildings
		$rawTotal = DB::raw('(SELECT SUM(drop_chance) total FROM buildings) AS c');

		// Faz uma subquery contendo a soma consecutiva de drop_chance de todos os buildings
		$subquerySql = Building::select('*')->selectRaw('(SELECT SUM(drop_chance) FROM buildings sb WHERE sb.id <= `buildings`.id) AS prob')
			->crossJoin($rawTotal)->toSql();

		// Faz uma query que retorna um building aleatorio
		$building = DB::table(DB::raw("({$subquerySql}) as b"))
			->whereRaw('prob >= RAND() * total')
			->orderBy('prob')->first();

		return $building;
	}

	// Retornar todas as casas relacionadas
	public function built()
	{
		return $this->hasMany(UserBuilding::class, 'building_id', 'id');
	}

}
