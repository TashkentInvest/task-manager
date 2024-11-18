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
    public function show(Qarorlar $qarorlar)
    {
        $qarorlar->load('files'); // Load files for the Qaror
        return view('pages.qarorlar.show', compact('qarorlar'));
    }

    // Show form to edit an existing Qaror
    public function edit(Qarorlar $qarorlar)
    {
        $users = User::all();
        $qarorlar->load('files'); // Load files for the Qaror
        return view('pages.qarorlar.edit', compact('qarorlar', 'users'));
    }

    // Update an existing Qaror in the database
    public function update(Request $request, Qarorlar $qarorlar)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'category_id' => 'nullable|exists:categories,id',
            'unique_code' => 'required|unique:qarorlars,unique_code,' . $qarorlar->id,
            'sana' => 'required|date',
            'short_name' => 'required|string|max:255',
            'comment' => 'nullable|string',
            'files.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        ]);

        $qarorlar->update($request->except('files'));

        // Save new files if uploaded
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $filePath = $file->store('qarorlar', 'public');

                QarorFile::create([
                    'qaror_id' => $qarorlar->id,
                    'file_path' => $filePath,
                ]);
            }
        }

        return redirect()->route('qarorlarIndex')->with('success', 'Қарор муваффақиятли янгиланди!');
    }

    // Delete a Qaror and its associated files
    public function destroy(Qarorlar $qarorlar)
    {
        // Delete related files from storage and database
        foreach ($qarorlar->files as $file) {
            Storage::disk('public')->delete($file->file_path);
            $file->delete();
        }

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
