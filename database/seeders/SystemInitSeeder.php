<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\Init\RoleSeeder;
use Database\Seeders\Init\TaskLevelSeeder;
use Database\Seeders\Init\TaskStatusSeeder;
use Database\Seeders\Init\UserSeeder;
use Database\Seeders\Init\CategorySeeder;
use Database\Seeders\Init\TaskSeeder;

class SystemInitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(
            [
                RoleSeeder::class,
                UserSeeder::class,
                CategorySeeder::class, // boshliq (xusanakan , baxtyoraka)
                TaskLevelSeeder::class,
                TaskStatusSeeder::class,
                // TaskSeeder::class,
            ]
        );
    }
}
