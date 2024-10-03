<?php

namespace App\Http\Controllers\Blade;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Category\ApplicationStoreRequest;
use App\Models\Category;


class CategoryController extends Controller
{
    public function index()
    {
        abort_if_forbidden('category.show');
        $categories = Category::get()->all();
        return view('pages.category.index', compact('categories'));
    }

    // add category page
    public function add()
    {
        abort_if_forbidden('category.add');
        return view('pages.category.add');
    }

    public function create(ApplicationStoreRequest $request)
    {
        $validated = $request->validated();   
        $existingCategory = Category::where('name', $validated['name'])->first();
        if ($existingCategory) {
            return redirect()->back()->withInput()->withErrors(['name' => 'Name already exists']);
        }
        $category = new Category;
        $category->name = $validated['name'];
        $category->deadline = $validated['deadline'];
        $category->score = $validated['score'];
        $category->additional_time = $validated['additional_time'];
        $category->save();

        return redirect()->route('categoryIndex');
    }

    public function edit($id)
    {
        abort_if_forbidden('category.edit');
        $category = Category::find($id);
        return view('pages.category.edit', compact('category'));
    }

    public function update(ApplicationStoreRequest $request, $id)
    {
        $category = Category::find($id);
        $validated = $request->validated();
        
        $category->name = $validated['name'];
        $category->score = $validated['score'];
        $category->deadline = $validated['deadline'];
        $category->additional_time = $validated['additional_time'];
        $category->save();
        return redirect()->route('categoryIndex');
    }

    public function destroy($id)
    {
        abort_if_forbidden('category.delete');
        $category = Category::find($id);
        $category->delete();
        return redirect()->back();
    }
}
