<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\QarorFile;
use App\Models\Qarorlar;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class QarorlarController extends Controller
{
    public function index()
    {
        $qarorlar = Qarorlar::with(['user', 'files'])->get(); // Load user and file relationships
        return view('pages.qarorlar.index', compact('qarorlar'));
    }

    public function add()
    {
        $users = User::all();
        return view('pages.qarorlar.add', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'category_id' => 'required',
            'unique_code' => 'required|unique:qarorlars',
            'sana' => 'required|date',
            'short_name' => 'required|string|max:255',
            'comment' => 'nullable|string',
            'files.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        ]);

        $qaror = Qarorlar::create($request->except('files'));

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $filePath = $file->store('qarorlar', 'public');

                QarorFile::create([
                    'qaror_id' => $qaror->id,
                    'file_path' => $filePath,
                ]);
            }
        }

        return redirect()->route('qarorlar.index')->with('success', 'Qaror created successfully with files!');
    }

    public function show(Qarorlar $qarorlar)
    {
        $qarorlar->load('files'); // Load files for the qaror
        return view('pages.qarorlar.show', compact('qarorlar'));
    }

    public function edit(Qarorlar $qarorlar)
    {
        $users = User::all();
        $qarorlar->load('files'); // Load files for the qaror
        return view('pages.qarorlar.edit', compact('qarorlar', 'users'));
    }

    public function update(Request $request, Qarorlar $qarorlar)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'category_id' => 'required',
            'unique_code' => 'required|unique:qarorlars,unique_code,' . $qarorlar->id,
            'sana' => 'required|date',
            'short_name' => 'required|string|max:255',
            'comment' => 'nullable|string',
            'files.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        ]);

        $qarorlar->update($request->except('files'));

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $filePath = $file->store('qarorlar', 'public');

                QarorFile::create([
                    'qaror_id' => $qarorlar->id,
                    'file_path' => $filePath,
                ]);
            }
        }

        return redirect()->route('qarorlar.index')->with('success', 'Qaror updated successfully!');
    }

    public function destroy(Qarorlar $qarorlar)
    {
        // Delete related files from storage and database
        foreach ($qarorlar->files as $file) {
            Storage::disk('public')->delete($file->file_path);
            $file->delete();
        }

        $qarorlar->delete();

        return redirect()->route('qarorlar.index')->with('success', 'Qaror and associated files deleted successfully!');
    }

    public function deleteFile($fileId)
    {
        $file = QarorFile::findOrFail($fileId);

        // Delete the file from storage
        Storage::disk('public')->delete($file->file_path);

        $file->delete();

        return redirect()->back()->with('success', 'File deleted successfully!');
    }
}
