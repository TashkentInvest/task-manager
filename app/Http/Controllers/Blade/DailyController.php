<?php

namespace App\Http\Controllers\Blade;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Rating;
use App\Models\Tasks;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Http\Request;

class DailyController extends Controller
{
    public function index()
    {
        $ratings = Rating::orderBy('updated_at', 'desc')->get();
        // $orders = Order::get()->all();

        if(auth()->user()->roles[0]->name != 'Employee'){
            // $orders = Order::deepFilters()->where('status', '!=', 0)->get()->all();
            $orders = Order::deepFilters()
            ->where('status', '!=', 0)
            ->with(['task.company' => function($query) {
                $query->withTrashed();
            }])
            ->with(['task.driver' => function($query) {
                $query->withTrashed();
            }])
            ->with(['task.category' => function($query) {
                $query->withTrashed();
            }])
            ->orderBy('updated_at', 'desc')
            ->get();


        }else{
            $orders = Order::deepFilters()->where('status', '!=', 0)
            ->where('user_id', auth()->user()->id)
            ->with(['task.company' => function($query) {
                $query->withTrashed();
            }])
            ->with(['task.driver' => function($query) {
                $query->withTrashed();
            }])
            ->with(['task.category' => function($query) {
                $query->withTrashed();
            }])
            ->orderBy('updated_at', 'desc')
            ->get();            
        }


        $users = User::whereHas('ratings')->get()->all();
        foreach ($ratings as $report) {
            $rating = Rating::where('order_id', $report->id)->first();

            $report->rating = $rating;
        }
        
        // Pass the user, their reports, and the rating to the view
        return view('pages.daily.index', compact( 'ratings', 'users', 'orders'));
    }

    public function toggleUserActivation(Request $request, $id)
    {
        // dd($request);
        $order = Order::findOrFail($id);
        $user_id = $request->input('user_id');
        $status = $request->input('status');
    
        $order2 = Order::where('id', $order->id)->first();
        
        $taskId = $order2->task_id;
        $task = Tasks::where('id', $taskId)->first();
        $task->save();

        $category = Category::withTrashed()->where('id', $task->category_id)
        ->first();

        if ($status == 2) {
            // dd($status);
            $rating = Rating::findOrNew($id);
            $rating->order_id = $order->id;
            $rating->user_id = $user_id;
            $rating->score = $category->score;
            $rating->description = '';
            $rating->action = 0;
            // dd($rating);
            $rating->save();

            $order2 = Order::where('id', $order->id)->first();
            $taskId = $order2->task_id;
            $task = Tasks::where('id', $taskId)->first();
            $task->status_id = TaskStatus::Completed;
            $task->save();
            // $task = Tasks::findOrFail($id);

            $order->task_id = $task->id;
            $order->status = TaskStatus::Completed;
            $order->save();

            // if($task->status_id === TaskStatus::ACCEPTED){

        }else{
            $rating = Rating::findOrNew($id);
            $rating->order_id = $order->id;
            $rating->user_id = $user_id;
            $rating->score = $category->score;
            $rating->action = 1;
            $rating->save();

            $order2 = Order::where('id', $order->id)->first();
            $taskId = $order2->task_id;
            $task = Tasks::where('id', $taskId)->first();
            $task->status_id = TaskStatus::ADMIN_REJECT;
            $task->save();

            $order->task_id = $task->id;
            $order->status = TaskStatus::ADMIN_REJECT;
            $order->save();
        }
    
        $order->checked_status = $status;
        $order->save();
    
        return redirect()->back()->with('status', 'Status toggled successfully.');
    }
    
}
