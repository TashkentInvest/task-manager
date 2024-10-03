<?php

namespace App\Http\Controllers\Blade;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Models\TasksHistory;
use App\Models\Tasks;

class RequestHistoryController extends Controller
{
    //
    public function index()
    {
        // $tasks = Tasks::withTrashed()->with('category', 'company', 'driver', 'level', 'user', 'status')->orderBy('id', 'DESC')->get();
        if(auth()->user()->roles[0]->name != 'Employee'){

        $tasks = Tasks::deepFilters()->withTrashed()
        ->with(['category', 'company', 'driver', 'level', 'user', 'status'])
        ->with(['company' => function($query) {
            $query->withTrashed();
        }])
        ->with(['driver' => function($query) {
            $query->withTrashed();
        }])
        ->orderBy('id', 'DESC')
        ->get();

        
        $deleted_tasks = Company::withTrashed()->get();

        }else{
            $tasks = Tasks::withTrashed()
            ->where('user_id', auth()->user()->id)
            ->with(['category', 'company', 'driver', 'level', 'user', 'status'])
            ->with(['company' => function($query) {
                $query->withTrashed();
            }])
            ->with(['driver' => function($query) {
                $query->withTrashed();
            }])
            ->orderBy('id', 'DESC')
            ->get();
    
            $deleted_tasks = Company::withTrashed()->get();
        }
        // dump($deleted_tasks);
        return view('pages.history.index', compact('tasks','deleted_tasks'));
    }

    public function historyDetail($id)
    {
        $currentTask = Tasks::deepFilters()->where('id', $id)
        ->with('category', 'company')
        ->with(['company' => function($query) {
            $query->withTrashed();
        }])
        ->with(['driver' => function($query) {
            $query->withTrashed();
        }])
        ->get()->first();


        $tasks = TasksHistory::deepFilters()->where('task_id', $id)
        ->with('category', 'company', 'driver', 'level', 'user', 'status')
        ->with(['company' => function($query) {
            $query->withTrashed();
        }])
        ->with(['driver' => function($query) {
            $query->withTrashed();
        }])
        ->get()->all();
        return view('pages.history.detail', compact('tasks', 'currentTask'),['id' => $id]);
    }
}
