<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('items')->insert([
            #1
            [
                'name' => 'Common House',
                'type' => 'house',
                'rarity' => 1,
                'price' => 0,
                'upgrade_cost' => 30,
                'drop_chance' => 50,
                'incomes' => 1.44,
            ],
            #2
            [
                'name' => 'Epic House',
                'type' => 'house',
                'rarity' => 2,
                'price' => 0,
                'upgrade_cost' => 70,
                'drop_chance' => 30,
                'incomes' => 3.16,
            ],
            #3
            [
                'name' => 'Legendary House',
                'type' => 'house',
                'rarity' => 3,
                'price' => 0,
                'upgrade_cost' => 180,
                'drop_chance' => 20,
                'incomes' => 7.77,
            ],            
        ]);
    }
}
