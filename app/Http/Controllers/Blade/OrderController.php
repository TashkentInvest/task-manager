<?php

namespace App\Http\Controllers\Blade;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Order;
use App\Models\Tasks;
use Illuminate\Http\Request;
use App\Models\OrderAction; 
use Illuminate\Support\Facades\Storage;

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

        $task = Tasks::findOrFail($request->task_id);
        $status = \App\Models\TaskStatus::where('name', 'Accepted')->first();

        if ($status) {
            $task->status_id = $status->id;
            $task->save();
        }

        // Log action
        OrderAction::create([
            'order_id' => $order->id,
            'user_id'  => auth()->id(),
            'action'   => 'Created',
            'comment'  => 'Order created and task status updated to Accepted!',
        ]);

        return redirect()->back()->with('success', 'Order created and task status updated to Accepted!');
    }

    public function reject(Request $request)
    {
        $request->validate([
            'task_id'         => 'required|exists:tasks,id',
            'reject_comment'  => 'required|string|max:255',
            'attached_file.*' => 'nullable|file',
        ]);

        $task = Tasks::findOrFail($request->task_id);
        $status = \App\Models\TaskStatus::where('name', 'XODIM_REJECT')->first();
        if ($status) {
            $task->status_id = $status->id;
        }

        $task->reject_comment = $request->reject_comment;
        $task->reject_time    = now();
        $task->save();

        $order = Order::where('task_id', $task->id)->first();

        // File upload
        if ($request->hasFile('attached_file')) {
            foreach ($request->file('attached_file') as $file) {
                if ($file->isValid()) {
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $file->move(public_path('porucheniya/reject'), $fileName);

                    File::create([
                        'user_id'   => auth()->id(),
                        'task_id'   => $task->id,
                        'name'      => $file->getClientOriginalName(),
                        'file_name' => $fileName,
                    ]);
                }
            }
        }

        // Log action
        if ($order) {
            OrderAction::create([
                'order_id' => $order->id,
                'user_id'  => auth()->id(),
                'action'   => 'Rejected',
                'comment'  => $request->reject_comment,
            ]);
        }

        return redirect()->back()->with('success', 'Order rejected and files uploaded successfully!');
    }

    public function complete(Request $request)
    {
        $request->validate([
            'task_id'        => 'required|exists:tasks,id',
            'reject_comment' => 'required|string|max:255',
            'attached_file.*'=> 'nullable|file',
        ]);

        $task = Tasks::findOrFail($request->task_id);
        $task->reject_comment = $request->reject_comment;
        $task->reject_time    = now();

        $status = \App\Models\TaskStatus::where('name', 'Completed')->first();
        if ($status) {
            $task->status_id = $status->id;
            $task->save();
        }

        $order = Order::where('task_id', $task->id)->first();
        if ($order) {
            $order->finished_user_id = auth()->id();
            $order->save();
        }

        // File upload
        if ($request->hasFile('attached_file')) {
            foreach ($request->file('attached_file') as $file) {
                if ($file->isValid()) {
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $file->move(public_path('porucheniya/complete'), $fileName);

                    File::create([
                        'user_id'   => auth()->id(),
                        'task_id'   => $task->id,
                        'name'      => $file->getClientOriginalName(),
                        'file_name' => $fileName,
                    ]);
                }
            }
        }

        // Log action
        if ($order) {
            OrderAction::create([
                'order_id' => $order->id,
                'user_id'  => auth()->id(),
                'action'   => 'Completed',
                'comment'  => $request->reject_comment,
            ]);
        }

        return redirect()->back()->with('success', 'Task status updated to Completed!');
    }

    public function adminConfirm(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $order = Order::where('task_id', $request->task_id)->first();
        if (!$order) {
            return redirect()->back()->with('error', 'Order not found!');
        }

        $order->checked_status  = 1; // Confirmed
        $order->checked_comment = null;
        $order->checked_time    = now();
        $order->save();

        // Log action
        OrderAction::create([
            'order_id' => $order->id,
            'user_id'  => auth()->id(),
            'action'   => 'Admin Confirm',
            'comment'  => 'Order confirmed by admin',
        ]);

        return redirect()->back()->with('success', 'Order confirmed!');
    }

    public function adminReject(Request $request)
    {
        $request->validate([
            'task_id'         => 'required|exists:tasks,id',
            'checked_comment' => 'required|string|max:255',
        ]);

        $task = Tasks::findOrFail($request->task_id);
        $task->reject_comment = $request->checked_comment;
        $task->reject_time    = now();

        // Return the task status to 'Active'
        $status = \App\Models\TaskStatus::where('name', 'Active')->first();
        if ($status) {
            $task->status_id = $status->id;
            $task->save();
        }

        $order = Order::where('task_id', $request->task_id)->first();
        if (!$order) {
            return redirect()->back()->with('error', 'Order not found!');
        }

        $order->checked_status  = 2; // Rejected
        $order->checked_comment = $request->checked_comment;
        $order->checked_time    = now();
        $order->save();

        // Log action
        OrderAction::create([
            'order_id' => $order->id,
            'user_id'  => auth()->id(),
            'action'   => 'Admin Reject',
            'comment'  => $request->checked_comment,
        ]);

        return redirect()->back()->with('success', 'Order rejected with comment!');
    }
}
