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

        // Determine assign_type from the request
        $assignType = request()->input('assign_type');

        // Check if the user is a Super Admin
        if ($isSuperAdmin) {
            // Fetch all active tasks if the user is a Super Admin
            $tasks = Tasks::with(['roles', 'category', 'user'])
                ->where('status_id', TaskStatus::ACTIVE)
                ->get();
        } elseif ($assignType === 'role') {
            // Fetch tasks that are active and have roles associated with the authenticated user
            $roleIds = $user->roles()->pluck('id')->toArray();
            $tasks = Tasks::with(['roles', 'category', 'user'])
                ->where('status_id', TaskStatus::ACTIVE)
                ->whereHas('roles', function ($query) use ($roleIds) {
                    $query->whereIn('role_id', $roleIds);
                })
                ->get();
        } elseif ($assignType === 'custom') {
            // Fetch tasks that are associated with users assigned to them
            $userId = $user->id; // Assuming you want tasks for the authenticated user
            $tasks = Tasks::with(['roles', 'category', 'user'])
                ->where('status_id', TaskStatus::ACTIVE)
                ->whereHas('users', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->get();
        } else {
            // Handle cases where the assign_type is not recognized, if needed
            $tasks = collect(); // Empty collection or handle as needed
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
