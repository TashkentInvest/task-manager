<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Department::create(['name' => 'HR', 'user_id' => 1]);
        Department::create(['name' => 'APZ', 'user_id' => 2]);
        Department::create(['name' => 'YURIST', 'user_id' => 3]);
        Department::create(['name' => 'FINANSIST', 'user_id' => 4]);
    }
}
