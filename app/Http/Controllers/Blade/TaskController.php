<?php

namespace App\Http\Controllers\Blade;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Driver;
use Illuminate\Http\Request;
use App\Models\TaskStatus;
use App\Models\TaskLevel;
use App\Models\Tasks;
use App\Models\TasksHistory;
use Illuminate\Support\Facades\Storage;

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
        $taskHistory = TasksHistory::all();
        $taskStatuses = TaskStatus::all();
        $taskLevels = TaskLevel::all();
        $count = 1;
        return view('pages.task.add', compact('categories', 'count', 'taskLevels'));
    }

    public function create(Request $request)
    {
        // dd($request);
        // Validate the incoming request
        $validatedData = $request->validate([
            'issue_date' => 'nullable|date', // Дата выдачи
            'author' => 'nullable|string|max:255', // Автор поручения
            'executor' => 'nullable|string|max:255', // Исполнитель поручения
            'co_executor' => 'nullable|string|max:255', // Со исполнитель поручения
            'planned_completion_date' => 'nullable|date', // Срок выполнения (план)
            'actual_status' => 'nullable|string|max:255', // Статус выполнения (факт)
            'execution_state' => 'nullable|string|max:255', // Состояние исполнения
            'attached_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png', // Закрепленный файл
            'note' => 'nullable|string', // Примичание
            'notification' => 'nullable|string', // Оповещение
            'priority' => 'nullable|string|in:Высокий,Средний,Низкий', // Приоритет
            'document_type' => 'nullable|string|max:255', // Вид документа
            'type_request' => 'nullable|integer|in:0,1,2', // Ensure type_request is valid
        ]);
        
        // Create a new task
        $task = new Tasks();
        $task->category_id = $validatedData['category_id'] ?? 1;
        $task->status_id = $validatedData['status_id'] ?? 1;
        $task->description = $validatedData['description'] ?? null;
        $task->issue_date = $validatedData['issue_date'] ?? null;
        $task->author = $validatedData['author'] ?? null;
        $task->executor = $validatedData['executor'] ?? null;
        $task->co_executor = $validatedData['co_executor'] ?? null; // Optional field
        $task->planned_completion_date = $validatedData['planned_completion_date'];
        $task->actual_status = $validatedData['actual_status'] ?? null; // Optional field
        $task->execution_state = $validatedData['execution_state'] ?? null; // Optional field
        $task->note = $validatedData['note'] ?? null; // Optional field
        $task->notification = $validatedData['notification'] ?? null; // Optional field
        $task->priority = $validatedData['priority'] ?? null; // Optional field
        $task->document_type = $validatedData['document_type'] ?? null; // Optional field
        $task->type_request = $validatedData['type_request'];
        $task->user_id = auth()->user()->id; // Set the user ID from the authenticated user
        
        // Handle file upload
        if ($request->hasFile('attached_file')) {
            $filePath = $request->file('attached_file')->store('attachments', 'public');
            $task->attached_file = $filePath; // Save the file path in the task
        }
        
        // Save the task
        $task->save();

        // Redirect with success message
        return redirect()->route('monitoringIndex')->with('success', 'Task created successfully!');
    }




    public function edit($id)
    {
        abort_if_forbidden('left-request.edit');
        $task = Tasks::find($id);
        $taskHistory = TasksHistory::all();
        $taskStatuses = TaskStatus::all();
        $taskLevels = TaskLevel::all();
        $categories = Category::all();

        return view('pages.task.edit', compact('taskHistory', 'taskStatuses', 'taskLevels', 'task', 'categories'));
    }

    public function update(Request $request, $id)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            // 'category_id' => 'nullable|exists:categories,id', // Ensure category exists
            'poruchenie' => 'nullable|string|max:255', // Поручение
            'issue_date' => 'nullable|date', // Дата выдачи
            'author' => 'nullable|string|max:255', // Автор поручения
            'executor' => 'nullable|string|max:255', // Исполнитель поручения
            'co_executor' => 'nullable|string|max:255', // Со исполнитель поручения
            'planned_completion_date' => 'nullable|date', // Срок выполнения (план)
            'actual_status' => 'nullable|string|max:255', // Статус выполнения (факт)
            'execution_state' => 'nullable|string|max:255', // Состояние исполнения
            'attached_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048', // Закрепленный файл
            'note' => 'nullable|string|max:255', // Примечание
            'notification' => 'nullable|string|max:255', // Оповещение
            'priority' => 'nullable|string|in:Высокий,Средний,Низкий', // Приоритет
            'document_type' => 'nullable|string|max:255', // Вид документа
            'driver_id' => 'nullable|exists:drivers,id', // Driver existence check
            'company_id' => 'nullable|exists:companies,id', // Company existence check
        ]);

        // dd('da');
        // Find the task by ID
        $task = Tasks::findOrFail($id);

        // Handle file upload if present
        if ($request->hasFile('attached_file')) {
            // Delete the previous file if exists
            if ($task->attached_file && Storage::exists($task->attached_file)) {
                Storage::delete($task->attached_file);
            }

            // Store the new file
            $filePath = $request->file('attached_file')->store('attachments', 'public');
            $validatedData['attached_file'] = $filePath; // Update the path in the validated data
        }

        // Update the task with validated data
        $task->update(array_merge($validatedData, [
            'user_id' => auth()->user()->id, // Optionally update user_id if necessary
            'type_request' => $request->input('type_request', 0), // Default to 0 if not present
        ]));

        return redirect()->route('monitoringIndex')->with('success', 'Task updated successfully');
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
