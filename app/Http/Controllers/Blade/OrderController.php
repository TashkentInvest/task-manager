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
        
        $request->validate([
            'reject_comment' => 'required|string|max:255',
            'files.*' => 'nullable', // Adjust file types and size as needed
        ]);
        // dd('das');

        // Find the order and update it
        $order = Tasks::findOrFail($request->task_id);

        $status = \App\Models\TaskStatus::where('name', 'XODIM_REJECT')->first();

        if ($status) {
            $order->status_id = $status->id;
            $order->save();
        }
        $order->reject_comment = $request->reject_comment;
        $order->save();

        // Handle file uploads
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('uploads'); // Change this path as necessary

                // Create a new file record
                File::create([
                    'user_id' => auth()->id(),
                    'name' => $file->getClientOriginalName(),
                    'department' => '', // Set as necessary
                    'file_name' => $path,
                    'slug' => uniqid() . '-' . time(), // Generate unique slug
                ]);
            }
        }

        return redirect()->back()->with('success', 'Order rejected and files uploaded successfully!');
    }
}
