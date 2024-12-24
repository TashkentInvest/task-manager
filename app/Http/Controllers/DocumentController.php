<?php

// app/Http/Controllers/DocumentController.php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentFile;
use App\Models\DocumentCategory;
use App\Models\Ministry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
   public function index(Request $request)
    {
        $statusType = $request->input('status_type');

        $documents = Document::when($statusType, function ($query, $statusType) {
            return $query->where('status_type', $statusType);
        })->with(['category.parent', 'ministry.parent'])->paginate(10);

        return view('pages.document.index', compact('documents'));
    }


    public function create()
    {
        $categories = DocumentCategory::with('children')->whereNull('parent_id')->get();
        $ministries = Ministry::with('children')->whereNull('parent_id')->get();

        return view('pages.document.add', compact('categories', 'ministries'));
    }

    public function store(Request $request)
    {
        // dd('das');
        $request->validate([
            'title'                => 'nullable',
            'letter_number'        => 'nullable',
            'received_date'        => 'nullable',
            'document_category_id' => 'nullable',
            'ministry_id' => 'nullable',

            'files.*'              => 'nullable',
            'status_type*'              => 'nullable',
        ]);

        // dd('daw');

        // Create the Document
        $document = Document::create([
            'title'                => $request->title,
            'letter_number'        => $request->letter_number,
            'received_date'        => $request->received_date,
            'user_id'              => Auth::id(),
            'document_category_id' => $request->document_category_id,
            'ministry_id' => $request->ministry_id,
            'status_type' => $request->status_type,

        ]);

        // Handle any uploaded files
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('public/documents');
                DocumentFile::create([
                    'document_id' => $document->id,
                    'file_path'   => $path
                ]);
            }
        }

        return redirect()->route('documents.index')->with('success', 'Document created');
    }

    public function show(Document $document)
    {
        $document->load('category', 'files');
        return view('pages.document.show', compact('document'));
    }

    public function edit(Document $document)
    {
        // Load top-level categories with children
        $categories = DocumentCategory::with('children')->whereNull('parent_id')->get();
    
        // Load top-level ministries with children
        $ministries = Ministry::with('children')->whereNull('parent_id')->get();
    
        // Identify the selected ministry and sub-ministry
        $selectedMinistry = $document->ministry ? $document->ministry->parent : null;
        $selectedSubMinistry = $document->ministry;
    
        return view('pages.document.edit', compact('document', 'categories', 'ministries', 'selectedMinistry', 'selectedSubMinistry'));
    }
    


    public function update(Request $request, Document $document)
    {
        $request->validate([
            'title'                => 'required',
            'letter_number'        => 'required',
            'received_date'        => 'required|date',
            'document_category_id' => 'nullable',
            'ministry_id' => 'nullable',
            'status_type*'              => 'nullable',

        ]);

        $document->update($request->only(
            'title',
            'letter_number',
            'received_date',
            'document_category_id',
            'ministry_id',
            'status_type',
        ));

        // If new files are uploaded
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('public/documents');
                DocumentFile::create([
                    'document_id' => $document->id,
                    'file_path'   => $path
                ]);
            }
        }

        return redirect()->route('documents.index')->with('success', 'Document updated');
    }

    public function destroy(Document $document)
    {
        // Optionally delete files from storage
        foreach ($document->files as $f) {
            Storage::delete($f->file_path);
        }
        $document->delete();

        return redirect()->route('documents.index')->with('success', 'Document deleted');
    }
}
