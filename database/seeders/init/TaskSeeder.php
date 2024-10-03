<?php

namespace Database\Seeders\init;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tasks;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tasks = [
            [
                'category_id' => 1,

                'level_id' => 1,
                'description' => 'Task 1 description',
                'type_request' => 2,
                'user_id' => 1,
                'status_id' => 1,
            ],
            [
                'category_id' => 2,

                'level_id' => 2,
                'description' => 'Task 2 description',
                'type_request' => 0,
                'user_id' => 2,
                'status_id' => 1,
            ],

        ];

        foreach ($tasks as $taskData) {
            Tasks::create($taskData);
        }
    }
}
