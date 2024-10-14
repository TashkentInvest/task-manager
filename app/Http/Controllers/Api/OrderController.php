<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tasks;
use App\Models\TaskStatus;
use App\Models\Order;
use App\Models\User;
use App\Models\Rating;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use PDO;

class OrderController extends Controller
{
    public function getOrders(Request $request)
    {
        $user = User::where('id', $request->get('user_id'))->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // $userRole = $user->getRoleNames()->implode(', ');
        // $userRole === 'Super Admin' || $userRole === 'Manager'
        
        $sql = "SELECT
                order.id AS order_id,
                order_user.id AS order_user_id,
                order_user.name AS accepted_user,
                order.status AS order_status,
                order.deadline AS order_deadline,
                comp.name AS company_name,
                comp.owner_name AS company_owner_name,
                comp.phone AS company_phone,
                task_user.name AS task_user_name,
                task.description AS task_description,
                categ.name AS category_name,
                categ.deadline AS category_deadline,
                categ.score AS category_score,
                driver.full_name AS driver_name,
                driver.track_num AS driver_track_num,
                driver.eastern_time AS driver_eastern_time,
                driver.phone AS driver_phone,
                driver.comment AS driver_comment,
            FROM
                orders AS `order`
            LEFT JOIN tasks AS task ON task.id = `order`.task_id
            LEFT JOIN users AS task_user ON task_user.id = task.user_id
            LEFT JOIN users AS order_user ON order_user.id = `order`.user_id
            LEFT JOIN category AS categ ON categ.id = task.category_id

            WHERE (`order`.task_id = task.id) AND (`order`.status = 0 OR `order`.status = 77)
            ORDER BY `order`.id DESC";

        $conn = DB::connection()->getPdo();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $response = [
            "status" => 0,
            "data" => $result,
        ];
        return $response;
    }

    public function deleteOrder($id)
    {
        $order = Order::find($id);
        if($order){
            $order->status = TaskStatus::DELETED;
            $order->save();

            $order2 = Order::where('id', $order->id)->first();
            $taskId = $order2->task_id;
            $task = Tasks::where('id', $taskId)->first();
            $task->status_id = TaskStatus::ACTIVE;
            $task->save();

            $response = [
                "status" => 0,
                "message" => "Order canceled !"
            ];
        }else{
            $response = [
                "status" => 3,
                "message" => "Order not found !"
            ];
        }
        return $response;
    }
    public function completeOrder(Request $request, $id)
    {
        // dd($request);
        $order = Order::find($id);
        if($order){
            if($order->status == TaskStatus::TIME_IS_YET_OVER){
                $order->status = TaskStatus::TIME_IS_OVER;
                $order->save();
        
                $order2 = Order::where('id', $order->id)->first();
                $taskId = $order2->task_id;
                $task = Tasks::where('id', $taskId)->first();
                $task->status_id = TaskStatus::TIME_IS_OVER;
                $task->save();
        
                $category = Category::where('id', $task->category_id)->first();
                // $user = User::where('id', $request->get('user_id'))->first(); // Fetch user
    
                $user_id = $order->user->id;
    
                $user = User::where('id', $user_id)
                    ->with(['orders' => function($query) use ($user_id) {
                        $query->where('user_id', $user_id);
                    }])
                    ->first();
                
                $user->last_finishedtask = now();
                $user->save();
    
                $rating = new Rating;
                $rating->order_id = $order2->id;
                $rating->user_id = $user->id;
                $rating->score = $category->score;
                $rating->description = '';
                $rating->save();
        
            
                // dd($user);
        
                $response = [
                    "status" => 0,
                    "message" => "Order completed!"
                ];
            }else{

                
                $order->status = TaskStatus::Completed;
                $order->save();
        
                $order2 = Order::where('id', $order->id)->first();
                $taskId = $order2->task_id;
                $task = Tasks::where('id', $taskId)->first();
                $task->status_id = TaskStatus::Completed;
                $task->save();
        
                // $category = Category::where('id', $task->category_id)->first();

            
                // $user = User::where('id', $request->get('user_id'))->first(); // Fetch user

                $user_id = $order->user->id;

                $user = User::where('id', $user_id)
                    ->with(['orders' => function($query) use ($user_id) {
                        $query->where('user_id', $user_id);
                    }])
                    ->first();
                
            


                $user->last_finishedtask = now();
                $user->save();
            
                
        
                $response = [
                    "status" => 0,
                    "message" => "Order completed!"
                ];
            }

        }else{
            $response = [
                "status" => 3,
                "message" => "Order not found!"
            ];
        }
        return $response;
    }

    // user->last_finishedtask update now qlsh kere
    public function updateOrderStatus(Request $request, $id)
    {
        // var_dump($request);  
        $order = Order::find($id);
        if($order){
            $order->status = TaskStatus::TIME_IS_YET_OVER;
            $order->save();
    
            $order2 = Order::where('id', $order->id)->first();
            $taskId = $order2->task_id;
            $task = Tasks::where('id', $taskId)->first();
            $task->status_id = TaskStatus::TIME_IS_YET_OVER;
            $task->save();
    
            $category = Category::where('id', $task->category_id)->first();
            // $user = User::where('id', $request->get('user_id'))->first(); // Fetch user

            $user_id = $order->user->id;

            $user = User::where('id', $user_id)
                ->with(['orders' => function($query) use ($user_id) {
                    $query->where('user_id', $user_id);
                }])
                ->first();
            
            $user->last_finishedtask = now();
            $user->save();

            $rating = new Rating;
            $rating->order_id = $order2->id;
            $rating->user_id = $user->id;
            $rating->score = $category->score;
            $rating->description = '';
            $rating->save();
    
        
            // dd($user);
    
            $response = [
                "status" => 0,
                "message" => "Order completed!"
            ];
        }else{
            $response = [
                "status" => 3,
                "message" => "Order not found!"
            ];
        }
        return $response;
    }
}    
