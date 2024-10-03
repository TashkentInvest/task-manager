<?php

namespace Database\Seeders\init;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TaskLevel;

class TaskLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $levelsJson = json_decode( file_get_contents(__DIR__."/../references/task-level.json") );
        echo "TASK LEVEL::inserting".PHP_EOL;
		foreach($levelsJson as $item)
		{
			$taskL = new TaskLevel();
            $taskL->name = $item->name;
			$taskL->save();
		}
    }
}
