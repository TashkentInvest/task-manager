<?php

namespace App\Observers;

use App\Models\Tasks;
use App\Models\TasksHistory;
use Illuminate\Support\Facades\Log;


class TasksHistoryObserver
{
    public function created(Tasks $task)
    {
        //
        TasksHistory::create([
            'task_id' => $task->id,
            'category_id' => $task->category_id,
        
            'level_id' => $task->level_id,
            'description' => $task->description,
            'type_request' => $task->type_request ?? 0,
            'user_id' => $task->user_id,
            'old_status_id' => -1,
            'new_status_id' => $task->status_id ?? 1,
        ]);
    }

    public function updated(Tasks $task)
    {
        if ($task->isDirty('status_id')) {
            $taskHistory = TasksHistory::where('task_id', $task->id)->latest()->first();
            TasksHistory::create([
                'task_id' => $task->id,
                'category_id' => $task->category_id,
       
                'level_id' => $task->level_id,
                'description' => $task->description,
                'type_request' => $task->type_request ?? 0,
                'user_id' => $task->user_id,
                'old_status_id' => $task->getOriginal('status_id'),
                'new_status_id' => $task->status_id,
            ]);
        }
    }
}
