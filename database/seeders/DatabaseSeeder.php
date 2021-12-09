<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\BuildingsSeeder;
use Database\Seeders\BuildingStatusesSeeder;
use Database\Seeders\ItemsSeeder;
use Database\Seeders\ItemStatusesSeeder;
use Database\Seeders\PacksSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        (new BuildingsSeeder)->run();
        (new BuildingStatusesSeeder)->run();
        (new ItemsSeeder())->run();
        (new ItemStatusesSeeder())->run();
        (new PacksSeeder())->run();
    }
}
