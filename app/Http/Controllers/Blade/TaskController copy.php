<?php

namespace App\Http\Controllers\Blade;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\ApplicationStoreRequest;
use App\Http\Requests\Task\ApplicationUpdateRequest;
use Illuminate\Http\Request;
use App\Models\TaskStatus;
use App\Models\TaskLevel;
use App\Models\Tasks;
use App\Models\TasksHistory;

class TaskController extends Controller
{
    public function index()
    {
        $tasksHistories = TasksHistory::all();
        return view('tasks.index', compact('tasksHistories'));
    }

    public function add()
    {
        abort_if_forbidden('task.add');
        return view('pages.task.add');
    }
    public function create(ApplicationStoreRequest $request)
    {
        dd($request);

        // $validated = $request->validated();
    
        // $task = new Tasks;
        
        // dd($task);
    
        // $task->description = $validated['description'];
        // $task->type_request = $validated['type_request'];
        // $task->save();
    
        // return redirect()->route('taskIndex');
    }
    


    public function show($id)
    {
        $taskHistory = TasksHistory::findOrFail($id);
        return view('tasks.show', compact('taskHistory'));
    }

    public function edit($id)
    {
        $taskHistory = TasksHistory::findOrFail($id);
        $taskStatuses = TaskStatus::all();
        $taskLevels = TaskLevel::all();

        return view('tasks.edit', compact('taskHistory', 'taskStatuses', 'taskLevels'));
    }

    public function update(ApplicationUpdateRequest $request, $id)
    {
        TasksHistory::findOrFail($id)->update($request->validated());

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully!');
    }

    public function destroy($id)
    {
        TasksHistory::findOrFail($id)->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully!');
    }
}
