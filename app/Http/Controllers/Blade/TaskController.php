<?php

namespace App\Http\Controllers\Blade;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\RoleTask;
use Illuminate\Http\Request;
use App\Models\TaskStatus;
use App\Models\Tasks;
use App\Models\TaskUser;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class TaskController extends Controller
{
    public function index()
    {
        $tasksHistories = Tasks::where('status_id', TaskStatus::ACTIVE)->get()->all();
        return view('tasks.index', compact('tasksHistories'));
    }

    public function add()
    {
        abort_if_forbidden('left-request.add');
        $categories = Category::all();
        $taskStatuses = TaskStatus::all();
        $count = 1;
        $users = User::get()->all();

        if (auth()->user()->hasRole('Super Admin'))
            $roles = Role::all();
        else
            $roles = Role::where('name', '!=', 'Super Admin')->get();

        return view('pages.task.add', compact('categories', 'count', 'users', 'roles'));
    }

    public function create(Request $request)
    {
        try {
            // Validate the incoming request
            $validatedData = $request->validate([
                // 'category_id' => 'required|exists:categories,id', // Ensure category_id is validated
                'issue_date' => 'nullable|date',
                'poruchenie' => 'nullable|string',
                'executor' => 'nullable|string|max:255',
                'co_executor' => 'nullable|string|max:255',
                'planned_completion_date' => [
                    'nullable',
                    'date',
                    function ($attribute, $value, $fail) use ($request) {
                        if ($request->filled('issue_date') && $value && strtotime($value) < strtotime($request->input('issue_date'))) {
                            $fail('The planned completion date cannot be earlier than the issue date.');
                        }
                    },
                ],
                'short_title' => 'nullable|string|max:255',
                'attached_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
                'note' => 'nullable|string',
               
                'roles' => 'nullable|array',
                'roles.*' => 'exists:roles,name',
                'users' => 'nullable|array',
                'users.*' => 'exists:users,id',
            ]);

            // Create a new task
            $task = new Tasks();
            $task->category_id = $validatedData['category_id'] ?? null;
            $task->user_id = auth()->user()->id;
            $task->poruchenie = $validatedData['poruchenie'] ?? null;
            $task->issue_date = $validatedData['issue_date'] ?? null;
            $task->planned_completion_date = $validatedData['planned_completion_date'] ?? null;
            $task->short_title = $validatedData['short_title'] ?? null;
          

            // Assign the assign_type based on the input
            $assignType = $request->input('assign_type');
            if ($assignType === 'role' || $assignType === 'custom') {
                $task->assign_type = $assignType;
            } else {
                $task->assign_type = null; // or a default value
            }

            $task->save();


            // Assign roles or users based on selection
            if ($request->input('assign_type') == 'role') {
                foreach ($validatedData['roles'] as $roleName) {
                    $role = Role::where('name', $roleName)->first();
                    if ($role) {
                        RoleTask::create([
                            'task_id' => $task->id,
                            'role_id' => $role->id
                        ]);
                    }
                }
            } elseif ($request->input('assign_type') == 'custom') {
                foreach ($validatedData['users'] as $userId) {
                    $user = User::find($userId); // Use find instead of where for user ID
                    if ($user) {
                        TaskUser::create([
                            'task_id' => $task->id,
                            'user_id' => $user->id
                        ]);
                    }
                }
            }

            return redirect()->route('monitoringIndex')->with('success', 'Task created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Log the validation errors
            \Log::error('Validation errors: ', $e->errors());

            return redirect()->back()->withErrors($e->validator)->withInput();
        }
    }






    public function edit($id)
    {
        // Fetch the task and related data
        $task = Tasks::with(['roles', 'user', 'task_users'])->findOrFail($id);
        $categories = Category::all(); // Adjust as necessary
        $roles = Role::all();
        $users = User::all();

        return view('pages.task.edit', compact('task', 'categories', 'roles', 'users'));
    }

    public function update(Request $request, $id)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'issue_date' => 'nullable|date',
            'poruchenie' => 'nullable|string',
            'executor' => 'nullable|string|max:255',
            'co_executor' => 'nullable|string|max:255',
            'planned_completion_date' => [
                'nullable',
                'date',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->filled('issue_date') && $value && strtotime($value) < strtotime($request->input('issue_date'))) {
                        $fail('The planned completion date cannot be earlier than the issue date.');
                    }
                },
            ],
            'short_title' => 'nullable|string|max:255',
            'attached_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
            'note' => 'nullable|string',
         
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,name', // Validate role names exist
            'users' => 'nullable|array', // Add validation for users
            'users.*' => 'exists:users,id', // Validate user IDs exist
        ]);

        // Find the task by ID
        $task = Tasks::findOrFail($id);

        // Update the task with validated data
        // ... (existing code) ...

        // Handle the roles and users assignment
        $assignType = $request->input('assign_type');
        if ($assignType === 'role' || $assignType === 'custom') {
            $task->assign_type = $assignType;
        } else {
            $task->assign_type = null; // or a default value
        }

        // Save the updated task
        $task->save();

        // Remove old role-task associations
        $task->roles()->detach();
        $task->task_users()->detach(); // Also detach existing user assignments

        // Insert updated roles into the pivot table
        if ($assignType === 'role') {
            foreach ($validatedData['roles'] as $roleName) {
                $role = Role::where('name', $roleName)->first();
                if ($role) {
                    RoleTask::create([
                        'task_id' => $task->id,
                        'role_id' => $role->id,
                    ]);
                }
            }
        } elseif ($assignType === 'custom') {
            foreach ($validatedData['users'] as $userId) {
                $user = User::find($userId); // Use find instead of where for user ID
                if ($user) {
                    TaskUser::create([
                        'task_id' => $task->id,
                        'user_id' => $user->id,
                    ]);
                }
            }
        }

        // Redirect with success message
        return redirect()->route('monitoringIndex')->with('success', 'Task updated successfully!');
    }



    public function destroy($id)
    {
        $task = Tasks::find($id);

        if (!$task) {
            return redirect()->back()->with('error', 'Task not found.');
        }

        // Optional: Update the status instead of deleting
        $task->status_id = TaskStatus::DELETED;
        $task->user_id = auth()->user()->id;
        $task->save();

        return redirect()->back()->with('success', 'Task deleted successfully.');
    }
}
