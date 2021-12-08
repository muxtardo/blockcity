<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('item_statuses')->insert([
            #1
            [
                'name' => 'Good',
                'color' => 'green',
                'loss' => 0,
                'fix_price' => 0,
            ],
            #2
            [
                'name' => 'Weathered',
                'color' => '#896420',
                'loss' => 20,
                'fix_price' => 10,
            ],
            #3
            [
                'name' => 'Broken',
                'color' => 'red',
                'loss' => 30,
                'fix_price' => 20,
            ],
        ]);
    }
}
