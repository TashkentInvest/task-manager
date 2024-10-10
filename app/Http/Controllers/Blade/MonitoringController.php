<?php

namespace App\Http\Controllers\Blade;

use App\Http\Controllers\Controller;
use App\Models\TaskStatus;
use App\Models\Tasks;
use Illuminate\Support\Facades\Auth;

class MonitoringController extends Controller
{
    public function index()
    {
        abort_if_forbidden('monitoring.show');

        $user = Auth::user();
        $isSuperAdmin = $user->roles()->where('name', 'Super Admin')->exists();

        // Initialize the query to fetch tasks
        $query = Tasks::with(['roles', 'category', 'task_users'])
            ->where('status_id', TaskStatus::ACTIVE);

        // If not super admin, apply additional filters
        if (!$isSuperAdmin) {
            $roleIds = $user->roles()->pluck('id')->toArray();

            // Filter tasks by roles and assigned users
            $query->whereHas('roles', function ($q) use ($roleIds) {
                $q->whereIn('role_id', $roleIds);
            })
                ->orWhereHas('task_users', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
        }

        // Execute the query and get the tasks
        $tasks = $query->get();

        $roleNamesByTask = $tasks->mapWithKeys(function ($task) {
            return [$task->id => $task->roles->pluck('name')];
        });

        return view('pages.monitoring.index', [
            'taskStatuses' => TaskStatus::all(),
            'tasks' => $tasks,
            'trashedTasks' => Tasks::onlyTrashed()->get(),
            'allTasks' => Tasks::withTrashed()->get(),
            'roleNamesByTask' => $roleNamesByTask,
        ]);
    }
}
