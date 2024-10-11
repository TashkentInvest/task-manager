<?php

namespace App\Http\Controllers\Blade;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Tasks;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $order = Order::create([
            'task_id' => $request->task_id,
            'user_id' => $request->user_id,
        ]);

        $task = Tasks::find($request->task_id);
        $status = \App\Models\TaskStatus::where('name', 'Accepted')->first(); 

        if ($status) {
            $task->status_id = $status->id; 
            $task->save(); 
        }

        return redirect()->back()->with('success', 'Order created and task status updated to Accepted!');
    }
}
