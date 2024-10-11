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

        // Fetch tasks
        $allTasks = $query->get();

        // Separate tasks by status
        $inProgressTasks = $allTasks->where('status_id', TaskStatus::ACTIVE);
        $pendingTasks = $allTasks->where('status_id', TaskStatus::ACCEPTED);
        $completedTasks = $allTasks->where('status_id', TaskStatus::COMPLATED);

        // Prepare role names by task
        $roleNamesByTask = $allTasks->mapWithKeys(function ($task) {
            return [$task->id => $task->roles->pluck('name')];
        });

        return view('pages.monitoring.index', [
            'taskStatuses' => TaskStatus::all(),
            'allTasks' => $allTasks,
            'inProgressTasks' => $inProgressTasks,
            'pendingTasks' => $pendingTasks,
            'completedTasks' => $completedTasks,
            'trashedTasks' => Tasks::onlyTrashed()->get(),
            'roleNamesByTask' => $roleNamesByTask,
        ]);
    }
}
