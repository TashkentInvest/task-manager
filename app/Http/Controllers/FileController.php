<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class FileController extends Controller
{
    // Fetch and display all files
    public function index()
    {
        // If the user is a Super Admin, show all files
        if (Auth::user()->roles[0]->name === 'Super Admin') {
            $files = File::orderByDesc('created_at')->get();
        } else {
            // For regular users, only show their files
            $files = File::where('user_id', Auth::id())->orderByDesc('created_at')->get();
        }

        return view('files.index', compact('files'));
    }

    // Render the file upload form
    public function create()
    {
        return view('files.create');
    }

    // Store uploaded files
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required',
    //         'department' => 'required',
    //         'files.*' => 'required', // Validate each file
    //     ]);

    //     foreach ($request->file('files') as $file) {
    //         $fileModel = new File();
    //         $fileModel->name = $request->name;
    //         $fileModel->department = $request->department;
    //         $fileModel->user_id = Auth::id();   

    //         if ($file) {
    //             $filePath = $file->store('uploads', 'local');
    //             $fileModel->file_name = $filePath;
    //             $fileModel->slug = Str::random(16);
    //         }

    //         $fileModel->save();
    //     }

    //     return redirect()->route('files.index');
    // }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'department' => 'required',
            'files.*' => 'required', // Ensure each file is valid
        ]);

        foreach ($request->file('files') as $file) {
            $fileModel = new File();
            $fileModel->name = $request->name;
            $fileModel->department = $request->department;
            $fileModel->user_id = Auth::id();

            if ($file) {
                // Create a unique filename using a random slug and the file's original extension
                $extension = $file->getClientOriginalExtension();
                $fileName = Str::random(16) . '.' . $extension; // Change file name

                $filePath = $file->storeAs('uploads', $fileName, 'local'); // Store with new name
                $fileModel->file_name = $filePath;
                $fileModel->slug = Str::random(16);
            }

            $fileModel->save();
        }

        return redirect()->route('files.index');
    }


    // Show and download a file by its slug
    public function show($slug)
    {
        $file = File::where('slug', $slug)->firstOrFail();

        // Authorize the action using the policy
        if (!Auth::user()->can('view', $file)) {
            abort(403, 'Unauthorized access');
        }

        $filePath = storage_path("app/{$file->file_name}");

        if (!file_exists($filePath)) {
            return abort(404, 'File not found');
        }

        return response()->download($filePath);
    }
}
