<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tasks;
use App\Models\TaskStatus;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Barryvdh\DomPDF\Facade\Pdf;
use PDO;

class TaskController extends Controller
{
    
    public function getTasks()
    {
        $tasks = Tasks::where('status_id', TaskStatus::ACTIVE)
        ->whereHas('driver')->whereHas('company')->whereHas('category')->whereHas('level')
        ->with('category', 'company', 'driver', 'level')
        ->orderByRaw("FIELD(type_request, 2, 1, 0)")
        ->get()->all();
        // dd($tasks);  
        if($tasks){
            $response = [
                "status" => 0,
                "data"=> $tasks
            ];
        }else{
            $response = [
                "status" => 3,
                "message" => "Not active tasks",
                "data"=> $tasks
            ];
        }
        return $response;
    }
        public function acceptTask(Request $request, $id)
    {
        $taskId = $id;
        $user = User::where('id', $request->get('user_id'))->get()->first();
        $userRole = $user->getRoleNames()->implode(', ');
        if($userRole === 'Employee' && $user->is_online === 0){
            $response = [
                "status" => 3,
                "message" => "You are not online !"
            ];
        }else{
            $order = Order::where('user_id', $request->get('user_id'))->where('status', 0)->get()->all();
            if(count($order) === 2){
                $response = [
                    "status" => 3,
                    "message" => "You don't get more 2 tasks !"
                ];
            }else{
                $task = Tasks::where('id', $taskId)->first();
                if($task->status_id === TaskStatus::ACCEPTED){
                    $response = [
                        "status" => 3,
                        "message" => "Task already accepted !"
                    ];
                }else{
                    $task->status_id = TaskStatus::ACCEPTED;
                    $order = new Order();
                    $order->user_id = $user->id;
                    $order->task_id = $task->id;
                    $order->status = TaskStatus::ORDER_ACTIVE;
                
                    $currentTime = now();
                    $deadline = $currentTime->addMinutes($task->category->deadline);
                    $order->deadline = $deadline;
                
                    $order->save();
                    $task->save();
                    
                    $response = [
                        "status" => 0,
                        "order_id"=> $order->id,
                        "message" => "Task accepted !"
                    ];
                }
            }
        }
        return $response;
    }

    // public function submitOrder($id)
    // {
    //     $task = Tasks::find($id);
    
    //     if (!$id) {
    //         return redirect()->route('monitoringIndex')->with('error', 'Product not found.');
    //     }
    
    //     $existingOrder = Order::where('task_id', $id)->first();
    
    //     if ($existingOrder) {
    //         return redirect()->route('monitoringIndex')->with('error', 'This product has already been ordered by another user.');
    //     }
    
    //     $user = auth()->user();
    //     $order = new Order();
    //     $order->user_id = $user->id;
    //     $order->task_id = $task->id;
    //     $order->status = 1;
    
    //     $currentTime = now();
    //     $deadline = $currentTime->addMinutes($task->category->deadline);
    //     $order->deadline = $deadline;
    
    //     $order->save();
    
    
    //     return redirect()->route('orderIndex')->with('success', 'Order submitted successfully.');
    // }

    public function deleteTask(Request $request, $id)
    {
        $task = Tasks::find($id);
        $user = User::where('id', $request->get('user_id'))->get()->first();
        if($task){
            $task->user_id = $user->id;
            $task->status_id = TaskStatus::DELETED;
            $task->save();
            $response = [
                "status" => 0,
                "message" => "Task deleted !"
            ];
        }else{
            $response = [
                "status" => 3,
                "message" => "Task not found !"
            ];
        }
        return $response;
    }



    public function restore($id)
    {
        Tasks::withTrashed()->findOrFail($id)->restore();
        return response()->json(['message' => 'Task restored successfully']);
    }

}
