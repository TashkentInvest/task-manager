<?php

namespace App\Http\Controllers\Blade;

use App\Http\Controllers\Controller;
use App\Models\TaskStatus;
use App\Models\TaskLevel;
use App\Models\TasksHistory;
use App\Models\Tasks;

class MonitoringController extends Controller
{
    public function index()
    {
        abort_if_forbidden('monitoring.show');

        $tasks = Tasks::with(['roles', 'category', 'user'])
            ->where('status_id', TaskStatus::ACTIVE)
            ->get();

        $roleNamesByTask = $tasks->mapWithKeys(function ($task) {
            return [$task->id => $task->roles->pluck('name')];
        });

        return view('pages.monitoring.index', [
            'taskStatuses' => TaskStatus::all(),
            'taskLevels' => TaskLevel::all(),
            'tasksHistories' => TasksHistory::all(),
            'tasks' => $tasks,
            'trashedTasks' => Tasks::onlyTrashed()->get(),
            'allTasks' => Tasks::withTrashed()->get(),
            'roleNamesByTask' => $roleNamesByTask,
        ]);
    }
}
