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
        $files = File::orderByDesc('created_at')->get();
        return view('files.index', compact('files'));
    }

    // Render the file upload form
    public function create()
    {
        return view('files.create');
    }

    // Store uploaded files
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'department' => 'required',
            'files.*' => 'required|file', // Validate each file
        ]);

        // Loop through each uploaded file
        foreach ($request->file('files') as $file) {
            $fileModel = new File();
            $fileModel->name = $request->name;
            $fileModel->department = $request->department;

            // Store the file and generate a slug
            if ($file) {
                $filePath = $file->store('uploads', 'local'); // File will be stored in storage/app/uploads
                $fileModel->file_name = $filePath; // Save the path
                $fileModel->slug = Str::random(16); // Generate a random slug for the file
            }

            $fileModel->save(); // Save the file record in the database
        }

        return redirect()->route('files.index'); // Redirect to files index page after upload
    }

    // Show and download a file by its slug
    public function show($slug)
    {
        // Ensure the user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login'); // Redirect to login if not authenticated
        }

        // Fetch the file record by its slug
        $file = File::where('slug', $slug)->firstOrFail();

        // Construct the full file path for downloading
        $filePath = storage_path("app/{$file->file_name}");

        // Check if the file exists
        if (!file_exists($filePath)) {
            return abort(404, 'File not found'); // Show 404 error if file is missing
        }

        // Serve the file for download
        return response()->download($filePath);
    }
}
