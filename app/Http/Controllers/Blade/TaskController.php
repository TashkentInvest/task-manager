<?php

namespace App\Http\Controllers\Blade;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\RoleTask;
use Illuminate\Http\Request;
use App\Models\TaskStatus;
use App\Models\Tasks;
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
            'actual_status' => 'nullable|string|max:255',
            'execution_state' => 'nullable|string|max:255',
            'attached_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
            'note' => 'nullable|string',
            'notification' => 'nullable|string',
            'priority' => 'nullable|string|in:Высокий,Средний,Низкий',
            'document_type' => 'nullable|string|max:255',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,name',
        ]);

        // Create a new task
        $task = new Tasks();
        $task->category_id = $validatedData['category_id'] ?? 1;
        $task->status_id = $validatedData['status_id'] ?? 1;
        $task->poruchenie = $validatedData['poruchenie'] ?? null;
        $task->description = $validatedData['description'] ?? null;
        $task->issue_date = $validatedData['issue_date'] ?? null;
        $task->executor = $validatedData['executor'] ?? null;
        $task->co_executor = $validatedData['co_executor'] ?? null;
        $task->planned_completion_date = $validatedData['planned_completion_date'] ?? null;
        $task->actual_status = $validatedData['actual_status'] ?? null;
        $task->execution_state = $validatedData['execution_state'] ?? null;
        $task->note = $validatedData['note'] ?? null;
        $task->notification = $validatedData['notification'] ?? null;
        $task->priority = $validatedData['priority'] ?? null;
        $task->document_type = $validatedData['document_type'] ?? null;
        $task->user_id = auth()->user()->id;

        // Handle file upload
        if ($request->hasFile('attached_file')) {
            $filePath = $request->file('attached_file')->store('attachments', 'public');
            $task->attached_file = $filePath;
        }

        // Save the task
        $task->save();

        // Manually insert into the `role_task` pivot table
        if (!empty($validatedData['roles'])) {
            foreach ($validatedData['roles'] as $roleName) {
                // Fetch the role by name and get its ID
                $role = Role::where('name', $roleName)->first();

                if ($role) {
                    // Insert into the pivot table
                    RoleTask::create([
                        'task_id' => $task->id,
                        'role_id' => $role->id
                    ]);
                }
            }
        }

        // Redirect with success message
        return redirect()->route('monitoringIndex')->with('success', 'Task created successfully!');
    }



    public function edit($id)
    {
        abort_if_forbidden('left-request.edit');
        $task = Tasks::find($id);
        $taskStatuses = TaskStatus::all();
        $categories = Category::all();


        if (auth()->user()->hasRole('Super Admin'))
            $roles = Role::all();
        else
            $roles = Role::where('name', '!=', 'Super Admin')->get();


        return view('pages.task.edit', compact('taskStatuses', 'task', 'categories', 'roles'));
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
            'actual_status' => 'nullable|string|max:255',
            'execution_state' => 'nullable|string|max:255',
            'attached_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
            'note' => 'nullable|string',
            'notification' => 'nullable|string',
            'priority' => 'nullable|string|in:Высокий,Средний,Низкий',
            'document_type' => 'nullable|string|max:255',
            'type_request' => 'nullable|integer|in:0,1,2',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,name', // Validate role names exist
        ]);

        // Find the task by ID
        $task = Tasks::findOrFail($id);

        // Update the task with validated data
        $task->category_id = $validatedData['category_id'] ?? $task->category_id;
        $task->status_id = $validatedData['status_id'] ?? $task->status_id;
        $task->poruchenie = $validatedData['poruchenie'] ?? $task->poruchenie;
        $task->description = $validatedData['description'] ?? $task->description;
        $task->issue_date = $validatedData['issue_date'] ?? $task->issue_date;
        $task->executor = $validatedData['executor'] ?? $task->executor;
        $task->co_executor = $validatedData['co_executor'] ?? $task->co_executor;
        $task->planned_completion_date = $validatedData['planned_completion_date'] ?? $task->planned_completion_date;
        $task->actual_status = $validatedData['actual_status'] ?? $task->actual_status;
        $task->execution_state = $validatedData['execution_state'] ?? $task->execution_state;
        $task->note = $validatedData['note'] ?? $task->note;
        $task->notification = $validatedData['notification'] ?? $task->notification;
        $task->priority = $validatedData['priority'] ?? $task->priority;
        $task->document_type = $validatedData['document_type'] ?? $task->document_type;
        $task->type_request = $validatedData['type_request'] ?? $task->type_request;

        // Handle file upload
        if ($request->hasFile('attached_file')) {
            $filePath = $request->file('attached_file')->store('attachments', 'public');
            $task->attached_file = $filePath;
        }

        // Save the updated task
        $task->save();

        // Remove old role-task associations
        $task->roles()->detach();

        // Insert updated roles into the pivot table
        if (!empty($validatedData['roles'])) {
            foreach ($validatedData['roles'] as $roleName) {
                // Fetch the role by name and get its ID
                $role = Role::where('name', $roleName)->first();

                if ($role) {
                    // Insert into the pivot table
                    RoleTask::create([
                        'task_id' => $task->id,
                        'role_id' => $role->id
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
