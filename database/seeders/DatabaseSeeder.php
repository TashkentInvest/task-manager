<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            SystemInitSeeder::class,
            DocumentCategorySeeder::class,
            MinistriesSeeder::class,
            
        ]);
    }
}
