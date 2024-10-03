<?php

namespace App\Http\Controllers\Blade;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TaskStatus;
use App\Models\TaskLevel;
use App\Models\TasksHistory;
use App\Models\Tasks;

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
        $tasks = Tasks::where('status_id', TaskStatus::ACTIVE)->get()->all();
        // dd($tasks);

        return view('pages.monitoring.index',compact('taskStatuses', 'taskLevels', 'tasksHistories', 'tasks','trashedTasks','allTasks'));
    }
}
