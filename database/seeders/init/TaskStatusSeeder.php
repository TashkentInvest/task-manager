<?php

namespace Database\Seeders\init;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TaskStatus;

class TaskStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statusJson = json_decode( file_get_contents(__DIR__."/../references/task_status.json") );
        echo "TASK STATUS::inserting".PHP_EOL;
		foreach($statusJson as $item)
		{
			$task_status = new TaskStatus();
            $task_status->name = $item->name;
			$task_status->save();
		}
    }
}
