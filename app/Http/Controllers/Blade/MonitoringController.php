<?php

namespace App\Http\Controllers\Blade;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TaskStatus;
use App\Models\TaskLevel;
use App\Models\TasksHistory;
use App\Models\Tasks;
use App\Models\User;

class MonitoringController extends Controller
{
    public function index()
    {
        abort_if_forbidden('monitoring.show');
    
        $trashedTasks = Tasks::onlyTrashed()->get();
        $allTasks = Tasks::withTrashed()->get();
    
        $taskStatuses = TaskStatus::all();
        $taskLevels = TaskLevel::all();
        $tasksHistories = TasksHistory::all();
        $tasks = Tasks::with('roles')->where('status_id', TaskStatus::ACTIVE)->get();
        
        // Collecting roles for each task
        $roleNamesByTask = [];
        foreach ($tasks as $task) {
            $roleNamesByTask[$task->id] = $task->roles->pluck('name')->toArray(); // Collecting role names into an array
        }
    
        return view('pages.monitoring.index', compact('taskStatuses', 'taskLevels', 'tasksHistories', 'tasks', 'trashedTasks', 'allTasks', 'roleNamesByTask'));
    }
    
}
