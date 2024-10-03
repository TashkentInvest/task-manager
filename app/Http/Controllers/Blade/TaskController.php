<?php

namespace App\Http\Controllers\Blade;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\ApplicationStoreRequest;
use App\Http\Requests\Task\ApplicationUpdateRequest;
use App\Models\Category;
use App\Models\Company;
use App\Models\Driver;
use Illuminate\Http\Request;
use App\Models\TaskStatus;
use App\Models\TaskLevel;
use App\Models\Tasks;
use App\Models\TasksHistory;

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
        $companies = Company::all();
        $drivers = Driver::all();
        $taskHistory = TasksHistory::all();
        $taskStatuses = TaskStatus::all();
        $taskLevels = TaskLevel::all();
        $count = 1;
        return view('pages.task.add', compact('categories', 'companies', 'drivers', 'count','taskLevels'));
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'category_id' => 'required',
            'driver_id' => 'required',
            'company_id' => 'required', 
            'level_id' => 'required',
        ]);
        
        $task = new Tasks;
    
        $task->category_id = $request->input('category_id');
        $task->company_id = $request->input('company_id');
        $task->driver_id = $request->input('driver_id');
        $task->level_id = $request->input('level_id');

        $task->type_request = $request->has('type_request') ? $request->input('type_request') : 0;
        
        $task->description = $request->input('description') ?? '';
        
        $task->user_id = auth()->user()->id;
    
        $task->save();


        // $taskLevel = TaskLevel::where($task->id);

        // if($request->get('type_request') == 0){
        //     $taskLevel;
        // }

        // $taskLevel->save();

        
        return redirect()->route('monitoringIndex')->with('success', 'Task created successfully');
    }
    
    
    
    public function edit($id)
    {
        abort_if_forbidden('left-request.edit');
        $task = Tasks::find($id);
        $taskHistory = TasksHistory::all();
        $taskStatuses = TaskStatus::all();
        $taskLevels = TaskLevel::all();
        $categories = Category::all();
        $companies = Company::all();
        $drivers = Driver::all();
        return view('pages.task.edit',compact('taskHistory','taskStatuses','taskLevels','task','categories', 'companies', 'drivers'));
    }

    public function update(Request $request,$id)
    {
        $task = Tasks::find($id);
        $task->category_id = $request->get('category_id');
        $task->company_id = $request->get('company_id');
        $task->driver_id = $request->get('driver_id');
        $task->level_id = $request->get('level_id');
        // $task->status_id = $request->get('status_id');
        $task->type_request = $request->get('type_request');
        $task->description = $request->get('description') ?? '';
        $task->user_id = auth()->user()->id;
        $task->save();
        return redirect()->route('monitoringIndex');
    }

    public function destroy($id)
    {
        $task = Tasks::find($id);
        $task->status_id = TaskStatus::DELETED;
        $task->user_id = auth()->user()->id;
        $task->save();
        return redirect()->back();
    }
}
