<?php

namespace App\Http\Controllers;

use App\Models\QarorFile;
use App\Models\Qarorlar;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class QarorlarController extends Controller
{
    // Display a list of Qarorlar
    public function index()
    {
        $qarorlar = Qarorlar::with(['user', 'files'])->get(); // Load user and file relationships
        return view('pages.qarorlar.index', compact('qarorlar'));
    }

    // Show form to add a new Qaror
    public function add()
    {
        // dd('dw');
        $users = User::all();
        return view('pages.qarorlar.add', compact('users'));
    }

    // Store a new Qaror in the database
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'category_id' => 'nullable|exists:categories,id',
            'unique_code' => 'required|unique:qarorlars',
            'sana' => 'required|date',
            'short_name' => 'required|string|max:255',
            'comment' => 'nullable|string',
            'files.*' => 'nullable',
        ]);

        $qaror = Qarorlar::create($request->except('files'));

        // Save associated files
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $filePath = $file->store('qarorlar', 'public');

                QarorFile::create([
                    'qaror_id' => $qaror->id,
                    'file_path' => $filePath,
                ]);
            }
        }

        return redirect()->route('qarorlarIndex')->with('success', 'Қарор ва файллар муваффақиятли сақланди!');
    }

    // Show details of a specific Qaror
    public function show($id)
    {
        $qarorlar = Qarorlar::where('id', $id)->with('files')->first(); // Use 'first' to return a single model instance
        return view('pages.qarorlar.show', compact('qarorlar'));
    }
    
    // Show form to edit an existing Qaror
    public function edit($id)
    {
        $users = User::all();
        $qarorlar = Qarorlar::where('id', $id)->with('files')->first(); // Use first() to get the actual model instance
        return view('pages.qarorlar.edit', compact('qarorlar', 'users'));
    }

    // Update an existing Qaror in the database
    public function update(Request $request, $id)
    {
        // Validate the incoming request
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'category_id' => 'nullable|exists:categories,id',
            'unique_code' => 'required|unique:qarorlars,unique_code,' . $id,
            'sana' => 'required|date',
            'short_name' => 'required|string|max:255',
            'comment' => 'nullable|string',
            'files.*' => 'nullable',
        ]);
    
        // Find the specific Qaror by its ID
        $qarorlar = Qarorlar::findOrFail($id);
    
        // Update the Qarorlar record with the validated request data, excluding files
        $qarorlar->update($request->except('files'));
    
        // Save new files if uploaded
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                // Store the file in the 'qarorlar' directory under the public disk
                $filePath = $file->store('qarorlar', 'public');
    
                // Associate the file with the Qarorlar model
                QarorFile::create([
                    'qaror_id' => $qarorlar->id, // Associate with the updated Qaror
                    'file_path' => $filePath,    // Store the file path
                ]);
            }
        }
    
        // Redirect back to the index with a success message
        return redirect()->route('qarorlarIndex')->with('success', 'Қарор муваффақиятли янгиланди!');
    }
    

    // Delete a Qaror and its associated files
    public function destroy($id)
    {
        $qarorlar = Qarorlar::findOrFail($id);
        $qarorlar->delete();

        return redirect()->route('qarorlarIndex')->with('success', 'Қарор ва тегишли файллар муваффақиятли ўчирилди!');
    }

    // Delete a specific file from a Qaror
    public function deleteFile($fileId)
    {
        $file = QarorFile::findOrFail($fileId);

        // Delete the file from storage
        Storage::disk('public')->delete($file->file_path);

        $file->delete();

        return redirect()->back()->with('success', 'Файл муваффақиятли ўчирилди!');
    }
}
