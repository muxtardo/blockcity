<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'rarity',
        'price',
        'upgrade_cost',
        'drop_chance',
        'incomes',
    ];

    public static function getRandomHouse() {
        /*
            Query to get a random house: 
                SELECT `*` 
                FROM (SELECT `name`, SUM(drop_chance) OVER (ORDER BY id) AS prob FROM items CROSS JOIN (SELECT SUM(drop_chance) total FROM items WHERE type = 'house') as c) as b 
                WHERE prob >= RAND() * total 
                ORDER BY prob 
                LIMIT 1;
        */
        // soma todos os drop_chance de todos os items do tipo house
        $rawTotal = DB::raw('(SELECT SUM(drop_chance) total FROM items WHERE `type` = "house") as c');
        // faz uma subquery contendo a soma consecutiva de drop_chance de todos os items do tipo house
        $subquerySql = Item::select('*')
            ->selectRaw('SUM(drop_chance) OVER (ORDER BY id) AS prob')
            ->crossJoin($rawTotal)
            ->whereRaw('`type` = "house"')
            ->toSql();
        // faz uma query que retorna um item aleatorio do tipo house
        $item = DB::table(DB::raw("({$subquerySql}) as b"))
            ->whereRaw('prob >= RAND() * total')
            ->orderBy('prob')
            ->first();
        return $item;
    } 
}
