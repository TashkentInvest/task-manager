<?php

namespace App\Http\Controllers\Blade;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Document;
use App\Models\File;
use App\Models\Order;
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
        $tasksHistories = Tasks::with('document')->where('status_id', TaskStatus::ACTIVE)->get()->all();
        return view('tasks.index', compact('tasksHistories'));
    }

    public function add()
    {
        abort_if_forbidden('left-request.add');
        $categories = Category::all();
        $taskStatuses = TaskStatus::all();
        $count = 1;
        $users = User::get()->all();
        $documents = Document::with('category')->get();

        if (auth()->user()->hasRole('Super Admin'))
            $roles = Role::all();
        else
            $roles = Role::where('name', '!=', 'Super Admin')->get();

        return view('pages.task.add', compact('categories', 'count', 'users', 'roles','documents'));
    }

    public function create(Request $request)
    {
        // dd($request);
        try {
            // Validate the incoming request
            $validatedData = $request->validate([
                // 'category_id' => 'required|exists:categories,id', // Ensure category_id is validated
                'issue_date' => 'nullable|date',
                'poruchenie' => 'nullable|string',
                'planned_completion_date' => [
                    'nullable',
                    'date',
                    function ($attribute, $value, $fail) use ($request) {
                        if ($request->filled('issue_date') && $value && strtotime($value) < strtotime($request->input('issue_date'))) {
                            $fail('The planned completion date cannot be earlier than the issue date.');
                        }
                    },
                ],
                'attached_file' => 'nullable',
                'note' => 'nullable|string',

                'category_id' => 'nullable',
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
            $task->note = $validatedData['note'] ?? null;


            // Assign the assign_type based on the input
            $assignType = $request->input('assign_type');
            if ($assignType === 'role' || $assignType === 'custom') {
                $task->assign_type = $assignType;
            } else {
                $task->assign_type = null; // or a default value
            }



            $task->save();

            if ($request->hasFile('attached_file')) {
                foreach ($request->file('attached_file') as $file) {
                    $fileName = time() . '_' . $file->getClientOriginalName(); // Create a unique name
                    $file->move(public_path('porucheniya'), $fileName); // Move file to the directory

                    // Save file information to the database
                    File::create([
                        'user_id' => auth()->user()->id,
                        'task_id' => $task->id,
                        'name' => $file->getClientOriginalName(), // Store the original name
                        'file_name' => $fileName, // Store the unique name
                        'department' => null, // Set this as needed
                        'slug' => null, // Generate a slug if necessary
                    ]);
                }
            }


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
    public function show($id)
    {
        // Fetch the task and related data
        $item = Tasks::where('id', $id)
            ->with(['roles', 'user', 'task_users', 'category', 'order', 'files']) // Load files relationship
            ->findOrFail($id);

        $categories = Category::all(); // Adjust as necessary
        $roles = Role::all();
        $users = User::all();
        $order = Order::withTrashed()->where('task_id', $id)->first();

        return view('pages.task.show', compact('item', 'categories', 'roles', 'users', 'order'));
    }


    public function update(Request $request, $id)
    {
        // dd('');
        // Validate the incoming request
        $validatedData = $request->validate([
            'issue_date' => 'nullable|date',
            'poruchenie' => 'nullable|string',
            'planned_completion_date' => [
                'nullable',
                'date',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->filled('issue_date') && $value && strtotime($value) < strtotime($request->input('issue_date'))) {
                        $fail('The planned completion date cannot be earlier than the issue date.');
                    }
                },
            ],
            'attached_file.*' => 'nullable', // Allow multiple files
            'note' => 'nullable|string',
            'category_id' => 'nullable',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,name',
            'users' => 'nullable|array',
            'users.*' => 'exists:users,id',
        ]);

        // Find the task by ID
        $task = Tasks::findOrFail($id);

        // Update task properties
        $task->category_id = $validatedData['category_id'] ?? null;
        $task->user_id = auth()->user()->id;
        $task->poruchenie = $validatedData['poruchenie'] ?? null;
        $task->issue_date = $validatedData['issue_date'] ?? null;
        $task->planned_completion_date = $validatedData['planned_completion_date'] ?? null;
        $task->note = $validatedData['note'] ?? null;

        // Handle the roles and users assignment
        $assignType = $request->input('assign_type');
        $task->assign_type = in_array($assignType, ['role', 'custom']) ? $assignType : null;

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
                    RoleTask::create(['task_id' => $task->id, 'role_id' => $role->id]);
                }
            }
        } elseif ($assignType === 'custom') {
            foreach ($validatedData['users'] as $userId) {
                $user = User::find($userId);
                if ($user) {
                    TaskUser::create(['task_id' => $task->id, 'user_id' => $user->id]);
                }
            }
        }

        // Handle file uploads
        if ($request->hasFile('attached_file')) {
            foreach ($request->file('attached_file') as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName(); // Generate a unique name
                $file->move(public_path('porucheniya'), $fileName); // Move file to the directory

                // Save file information to the database
                File::create([
                    'user_id' => auth()->user()->id,
                    'task_id' => $task->id,
                    'name' => $file->getClientOriginalName(),
                    'file_name' => $fileName,
                    'department' => null, // Set this as needed
                    'slug' => null, // Generate a slug if necessary
                ]);
            }
        }

        // Redirect with success message
        return redirect()->route('monitoringIndex')->with('success', 'Task updated successfully!');
    }



    public function deleteFile($fileId)
    {
        $file = File::findOrFail($fileId);
    
        // Attempt to build the file paths
        $filePath1 = public_path('porucheniya/' . $file->file_name);
        $filePath2 = public_path('porucheniya/reject/' . $file->file_name);
    
        // Determine which path to use
        $filePath = file_exists($filePath1) ? $filePath1 : $filePath2;
    
        // Check if the file exists and is a file, then delete it
        if (file_exists($filePath) && is_file($filePath)) {
            unlink($filePath); // Delete the file from storage
        }
    
        // Delete the file record from the database
        $file->delete();
    
        return redirect()->back()->with('success', 'File deleted successfully!');
    }
    


    public function destroy($id)
    {
        // dd('ads');
        $task = Tasks::find($id);

        if (!$task) {
            return redirect()->back()->with('error', 'Task not found.');
        }
        dd($task);

        // Optional: Update the status instead of deleting
        $task->status_id = TaskStatus::DELETED;
        $task->user_id = auth()->user()->id;
        $task->save();

        return redirect()->back()->with('success', 'Task deleted successfully.');
    }
}
