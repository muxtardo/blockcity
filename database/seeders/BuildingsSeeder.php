<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BuildingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('buildings')->insert([
            #1
            [
                'name' => 'Common House',
                'rarity' => 1,
                'upgrade_cost' => 30,
                'drop_chance' => 50,
                'incomes' => 1.44,
                'images' => 1,
            ],
            #2
            [
                'name' => 'Epic House',
                'rarity' => 2,
                'upgrade_cost' => 70,
                'drop_chance' => 30,
                'incomes' => 3.16,
                'images' => 1,
            ],
            #3
            [
                'name' => 'Legendary House',
                'rarity' => 3,
                'upgrade_cost' => 180,
                'drop_chance' => 20,
                'incomes' => 7.77,
                'images' => 1,
            ],            
        ]);        
    }
}
