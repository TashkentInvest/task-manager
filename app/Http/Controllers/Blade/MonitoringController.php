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

        // Get the roles for the authenticated user
        $user = Auth::user();
        $isSuperAdmin = $user->roles()->where('name', 'Super Admin')->exists();

        // Check if the user is a Super Admin
        if ($isSuperAdmin) {
            // Fetch all active tasks if the user is a Super Admin
            $tasks = Tasks::with(['roles', 'category', 'user'])
                ->where('status_id', TaskStatus::ACTIVE)
                ->get();
        } else {
            // Fetch tasks that are active and have roles associated with the authenticated user
            $roleIds = $user->roles()->pluck('id')->toArray();
            $tasks = Tasks::with(['roles', 'category', 'user'])
                ->where('status_id', TaskStatus::ACTIVE)
                ->whereHas('roles', function ($query) use ($roleIds) {
                    $query->whereIn('role_id', $roleIds);
                })
                ->get();
        }

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
