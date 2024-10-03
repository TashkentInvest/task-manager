@extends('layouts.admin')

@section('content')

<!-- Content Header (Page header) -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Edit Category</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: #007bff;">@lang('global.home')</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('categoryIndex') }}" style="color: #007bff;">Control Category</a></li>
                    <li class="breadcrumb-item active">@lang('global.edit')</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- Main content -->

<div class="row">
    <div class="col-lg-10 offset-lg-1 col-sm-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">@lang('global.edit')</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <form action="{{ route('taskUpdate', $task->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                
                    <div class="form-group">
                        <div class="row">
                            <div class="col">
                                <label>Level</label>
                                <select class="form-control select2" style="width: 100%;" name="level_id" required>
                                    @foreach($taskLevels as $level)
                                        <option value="{{ $level->id }}" {{ $level->id == old('level_id', $task->level_id) ? 'selected' : '' }}>{{ $level->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col">
                                <label>Category</label>
                                <select class="form-control select2" style="width: 100%;" name="category_id" required>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $category->id == old('category_id', $task->category_id) ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                
                    {{-- Company and drivers --}}
                    <div class="form-group">
                        <div class="row">
                            <div class="col">
                                <label>Company</label>
                                <select class="form-control select2" style="width: 100%;" name="company_id" required>
                                    <option value="" disabled selected>Choose a company</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}" {{ $company->id == old('company_id', $task->company_id) ? 'selected' : '' }}>{{ $company->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- <div class="col">
                                <label>Task Status</label>
                                <select class="form-control select2" style="width: 100%;" name="status_id" required>
                                    <option value="" disabled selected>Choose a task status</option>
                                    @foreach($taskStatuses as $taskStatus)
                                        <option value="{{ $taskStatus->id }}" {{ $taskStatus->id == old('status_id', $task->status_id) ? 'selected' : '' }}>{{ $taskStatus->name }}</option>
                                    @endforeach
                                </select>
                            </div> --}}
                            <div class="col">
                                <label>Driver</label>
                                <select class="form-control select2" style="width: 100%;" name="driver_id" required>
                                    <option value="" disabled selected>Choose a driver</option>
                                    @foreach($drivers as $driver)
                                        <option value="{{ $driver->id }}" {{ $driver->id == old('driver_id', $task->driver_id) ? 'selected' : '' }}>{{ $driver->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                
                    <div class="form-group">
                        <div class="row">
                            <div class="col-12">
                                <label>Name</label>
                                <textarea rows="3" name="description" class="form-control">{{ old('description', $task->description) }}</textarea>
                            </div>
                        </div>
                    </div>
                
                    <div class="box d-flex">
                        <div class="form-group">
                            <label>Is this an Extra task?</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type_request" id="is_extra_yes" value="2" {{ old('type_request') == '2' ? 'checked' : ($task->type_request == 2 ? 'checked' : '') }}>
                                <label class="form-check-label" for="is_extra_yes">
                                    Extra Task 
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type_request" id="is_extra_no" value="1" {{ old('type_request') == '1' ? 'checked' : ($task->type_request == 1 ? 'checked' : '') }}>
                                <label class="form-check-label" for="is_extra_no">
                                    Later Task 
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type_request" id="is_none" value="0" {{ old('type_request') == '0' ? 'checked' : ($task->type_request == 0 ? 'checked' : '') }}>
                                <label class="form-check-label" for="is_none">
                                    None
                                </label>
                            </div>
                        </div>
                        
                    </div>
                        
                    
                 
                    <div class="form-group mt-2">
                        <button type="submit" class="btn btn-success float-right">@lang('global.save')</button>
                        <a href="{{ route('monitoringIndex') }}" class="btn btn-light waves-effect float-left">@lang('global.cancel')</a>
                    </div>
                </form>
                


            </div>
        </div>
    </div>
</div>
@endsection