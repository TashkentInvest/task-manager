<?php

namespace App\Http\Controllers\Blade;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LongText\ApplicationStoreRequest;
use App\Models\LongText;

class LongTextController extends Controller
{
    public function index()
    {
        $longTexts = LongText::all();
        return view('pages.longTexts.index', compact('longTexts'));
    }

    public function add()
    {
        return view('pages.longTexts.add');
    }

    public function create(ApplicationStoreRequest $request)
    {
        $validated = $request->validated();   
        $existingCategory = LongText::where('category', $validated['category'])->first();
        if ($existingCategory) {
            return redirect()->back()->withInput()->withErrors(['category' => 'Category already exists']);
        }
        $longText = new LongText;
        $longText->category = $validated['category'];
        $longText->description = $validated['description'];
        $longText->save();

        return redirect()->route('longTextIndex');
    }

    public function edit($id)
    {
        $longText = LongText::find($id);
        return view('pages.longTexts.edit', compact('longText'));
    }

    public function update(ApplicationStoreRequest $request, $id)
    {
        $longText = LongText::find($id);
        $validated = $request->validated();
        
        $longText->category = $validated['category'];
        $longText->description = $validated['description'];
        $longText->save();
        return redirect()->route('longTextIndex');
    }

    public function destroy($id)
    {
        $longText = LongText::find($id);
        $longText->delete();
        message_set("Long text deleted",'success',1);
        return redirect()->back();
    }
}
