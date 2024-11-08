<?php

namespace App\Http\Controllers\Blade;

use App\Http\Controllers\Controller;
use App\Models\File;
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

    public function reject(Request $request)
    {
        // Validate the request
        $request->validate([
            'reject_comment' => 'required|string|max:255',
            'attached_file.*' => 'nullable', // Adjust file types and size as needed
        ]);

        // Find the order and update its status
        $order = Tasks::findOrFail($request->task_id);

        // Set the status to rejected
        $status = \App\Models\TaskStatus::where('name', 'XODIM_REJECT')->first();
        if ($status) {
            $order->status_id = $status->id;
        }

        // Save the rejection comment and time
        $order->reject_comment = $request->reject_comment;
        $order->reject_time = now();
        $order->save();

        // Handle file uploads
        if ($request->hasFile('attached_file')) {
            foreach ($request->file('attached_file') as $file) {
                // Check if the file is valid
                if ($file->isValid()) {
                    $fileName = time() . '_' . '_' . $file->getClientOriginalName(); // Create a unique name
                    $file->move(public_path('porucheniya/reject'), $fileName); // Move file to the directory

                    // Save file information to the database
                    File::create([
                        'user_id' => auth()->user()->id,
                        'task_id' => $order->id,
                        'name' => $file->getClientOriginalName(), // Store the original name
                        'file_name' => $fileName, // Store the unique name
                        'department' => null, // Set this as needed
                        'slug' => null, // Generate a slug if necessary
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Order rejected and files uploaded successfully!');
    }


    public function complete(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'reject_comment' => 'required|string|max:255',
            'attached_file.*' => 'nullable', // Adjust file types and size as needed
        ]);

     
        // Find the task first
        $task = Tasks::findOrFail($request->task_id);
        $task->reject_comment = $request->reject_comment;

        $task->reject_time = now(); // Update the task status


        // Find the order associated with this task
        $order = Order::where('task_id', $task->id)->first();
        if (!$order) {
            return redirect()->back()->with('error', 'Order not found!');
        }

        // Get the 'Completed' status
        $status = \App\Models\TaskStatus::where('name', 'Completed')->first();
        if ($status) {
            $task->status_id = $status->id; // Update the task status
            $task->reject_comment = $request->reject_comment; // Update the task status
            $task->reject_time = now(); // Update the task status

            $order->finished_user_id = auth()->id(); // Set the finished user ID
            $task->save(); // Save the task
            $order->save(); // Save the order
        }

        if ($request->hasFile('attached_file')) {
            foreach ($request->file('attached_file') as $file) {
                // Check if the file is valid
                if ($file->isValid()) {
                    $fileName = time() . '_' . '_' . $file->getClientOriginalName(); // Create a unique name
                    $file->move(public_path('porucheniya/complete'), $fileName); // Move file to the directory

                    // Save file information to the database
                    File::create([
                        'user_id' => auth()->user()->id,
                        'task_id' => $order->id,
                        'name' => $file->getClientOriginalName(), // Store the original name
                        'file_name' => $fileName, // Store the unique name
                        'department' => null, // Set this as needed
                        'slug' => null, // Generate a slug if necessary
                    ]);
                }
            }
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

        $order->checked_status = 1; // Confirmed
        $order->checked_comment = null; 
        $order->checked_time = now(); 

        $order->save();

        return redirect()->back()->with('success', 'Order confirmed!');
    }

    public function adminReject(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'checked_comment' => 'required|string|max:255',
        ]);

        $order = Order::where('task_id', $request->task_id)->first();
        if (!$order) {
            return redirect()->back()->with('error', 'Order not found!');
        }

        $order->checked_status = 2; // Rejected
        $order->checked_comment = $request->checked_comment; 
        $order->checked_time = now(); 

        $order->save();

        return redirect()->back()->with('success', 'Order rejected with comment!');
    }
}
