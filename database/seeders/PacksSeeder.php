<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PacksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('packs')->insert([
            #1
            [
                'name' => 'Little House Pack',
                'image' => 'casas/build6.png',
                'price' => 36,
                'price_bnb' => 0.0613,
                'sell_limit' => 500,
                'rewards' => '{"7": 6, "8": 6, "9": 6}',
            ],
            #2
            [
                'name' => 'Medium House Pack',
                'image' => 'casas/build8.png',
                'price' => 76,
                'price_bnb' => 0.1294,
                'sell_limit' => 400,
                'rewards' => '{"10": 4, "11": 4, "12": 4}',
            ],
            #3
            [
                'name' => 'Big House Pack',
                'image' => 'casas/build10.png',
                'price' => 162,
                'price_bnb' => 0.2758,
                'sell_limit' => 500,
                'rewards' => '{"13": 3, "14": 3, "15": 3}',
            ],
            #4
            [
                'name' => 'Small Neighborhood Pack',
                'image' => 'casas/build12.png',
                'price' => 180,
                'price_bnb' => 0.3064,
                'sell_limit' => 400,
                'rewards' => '{"7": 18, "8": 18, "9": 18, "10": 4, "11": 4, "12": 4}',
            ],
            #5
            [
                'name' => 'Medium Neighborhood Pack',
                'image' => 'casas/build34.png',
                'price' => 490,
                'price_bnb' => 0.8342,
                'sell_limit' => 300,
                'rewards' => '{"7": 18, "8": 18, "9": 18, "10": 12, "11": 12, "12": 12, "13": 3, "14": 3, "15": 3}',
            ],
            #6
            [
                'name' => 'Big Neighborhood Pack',
                'image' => 'casas/build33.png',
                'price' => 1350,
                'price_bnb' => 2.2983,
                'sell_limit' => 200,
                'rewards' => '{"7": 30, "8": 30, "9": 30, "10": 20, "11": 20, "12": 20, "13": 15, "14": 15, "15": 15}',
            ],
        ]);

    }
}
