<?php

namespace App\Http\Controllers\Blade;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Driver\ApplicationStoreRequest;
use App\Models\Company;
use App\Models\Driver;

class DriverController extends Controller
{
    public function index()
    {
        abort_if_forbidden('driver.show');
        $drivers = Driver::with('company')
        ->with(['company' => function($query) {
            $query->withTrashed();
        }])
        ->get()->all();
        return view('pages.driver.index', compact('drivers'));
    }

    public function add()
    {
        abort_if_forbidden('driver.add');
        $companies = Company::all();
        return view('pages.driver.add', compact('companies'));
    }

    public function create(ApplicationStoreRequest $request)
    {
        $validated = $request->validated();
        $driver = new Driver;
        $driver->full_name = $validated['full_name'];
        $driver->track_num = $validated['track_num'];
        $driver->company_id = $validated['company_id'];
        $driver->eastern_time = $validated['eastern_time'];
        $driver->phone = $validated['phone'];
        $driver->comment = $validated['comment'];
        $driver->save();

        return redirect()->route('driverIndex');
    }

    public function edit($id)
    {
        abort_if_forbidden('driver.edit');
        $driver = Driver::find($id);
        $companies = Company::all();
        return view('pages.driver.edit', compact('driver', 'companies'));
    }

    public function update(ApplicationStoreRequest $request, $id)
    {
        $driver = Driver::find($id);
        $validated = $request->validated();
        
        $driver->full_name = $validated['full_name'];
        $driver->track_num = $validated['track_num'];
        $driver->company_id = $validated['company_id'];
        $driver->eastern_time = $validated['eastern_time'];
        $driver->phone = $validated['phone'];
        $driver->comment = $validated['comment'];
        $driver->save();
        return redirect()->route('driverIndex');
    }

    public function destroy($id)
    {
        abort_if_forbidden('driver.delete');
        $driver = Driver::find($id);
        $driver->delete();
        message_set("Driver deleted",'success',1);
        return redirect()->back();
    }
}
