<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Position::create(['name' => 'HR Manager', 'department_id' => 1]);
        Position::create(['name' => 'Sales Executive', 'department_id' => 2]);
        Position::create(['name' => 'Software Engineer', 'department_id' => 3]);
        Position::create(['name' => 'Marketing Specialist', 'department_id' => 4]);
    }
}
