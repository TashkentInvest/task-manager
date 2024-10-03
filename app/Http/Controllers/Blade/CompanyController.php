<?php

namespace App\Http\Controllers\Blade;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Company\ApplicationStoreRequest;
use App\Models\Company;

class CompanyController extends Controller
{
    public function index()
    {
        abort_if_forbidden('company.show');
        $companies = Company::get()->all();
        return view('pages.company.index', compact('companies'));
    }

    public function add()
    {
        abort_if_forbidden('company.add');
        return view('pages.company.add');
    }

    public function create(ApplicationStoreRequest $request)
    {
        $validated = $request->validated();   
        $existingCategory = Company::where('name', $validated['name'])->first();
        if ($existingCategory) {
            return redirect()->back()->withInput()->withErrors(['name' => 'Name already exists']);
        }
        $company = new Company;
        $company->name = $validated['name'];
        $company->owner_name = $validated['owner_name'];
        $company->phone = $validated['phone'];
        $company->save();

        return redirect()->route('companyIndex');
    }

    public function edit($id)
    {
        abort_if_forbidden('company.edit');
        $company = Company::find($id);
        return view('pages.company.edit', compact('company'));
    }

    public function update(ApplicationStoreRequest $request, $id)
    {
        $category = Company::find($id);
        $validated = $request->validated();
        
        $category->name = $validated['name'];
        $category->owner_name = $validated['owner_name'];
        $category->phone = $validated['phone'];
        $category->save();
        return redirect()->route('companyIndex');
    }

    public function destroy($id)
    {
        abort_if_forbidden('company.delete');
        $category = Company::find($id);
        $category->delete();
        return redirect()->back();
    }
}
