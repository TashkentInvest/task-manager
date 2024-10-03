@extends('layouts.admin')

@section('content')

<!-- Content Header (Page header) -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Edit Employee</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: #007bff;">@lang('global.home')</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('employeeIndex') }}" style="color: #007bff;">Edit Employee</a></li>
                    <li class="breadcrumb-item active">@lang('global.add')</li>
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

                <form action="{{ route('employeeUpdate', $user->id) }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-lg-6 mb-2">
                            <label for="name" class="col-form-label">Name</label>
                            <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" 
                            name="name" id="name" placeholder="Name" value="{{ old('name', $user->name) }}" required>
                            @if($errors->has('name'))
                                <span class="error invalid-feedback">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                        <div class="col-12 col-lg-6 mb-2">
                            <label for="phone" class="col-form-label">Phone number</label>
                            <input class="form-control input-mask {{ $errors->has('phone') ? 'is-invalid' : '' }}"  
                            name="phone" id="phone" placeholder="998-90-123-45-67" value="{{ old('phone', $user->phone) }}" 
                            data-inputmask="'mask': '999-99-999-99-99'" required>
                            @if($errors->has('phone'))
                                <span class="error invalid-feedback">{{ $errors->first('phone') }}</span>
                            @endif
                        </div>
                        <div class="col-12 col-lg-6 mb-2">
                            <label for="birth_date" class="col-form-label">Date of birth</label>
                            <input id="birth_date" class="form-control input-mask" 
                            name="birth_date" value="{{ old('birth_date', $user->birth_date) }}"
                            data-inputmask="'alias': 'datetime'" data-inputmask-inputformat="dd-mm-yyyy">
                        </div>
                        <div class="col-12 col-lg-6 mb-2">
                            <label for="hire_date" class="col-form-label">Hire date</label>
                            <input id="hire_date" class="form-control input-mask" 
                            name="hire_date" value="{{ old('hire_date', $user->hire_date) }}"
                            data-inputmask="'alias': 'datetime'" data-inputmask-inputformat="dd-mm-yyyy">
                        </div>

                        @if(auth()->check() && auth()->user()->roles->isNotEmpty() && auth()->user()->roles[0]->name != "Employee")
                        <div class="col-12 mb-4">
                            <label for="hire_date" class="col-form-label">Roles</label>
                            <div class="d-flex align-items-center flex-wrap">
                                @foreach($roles as $index => $role)
                                    <div class="form-check {{ $index > 0 ? 'ms-2' : '' }}">
                                        <input class="form-check-input" type="radio" name="role_id"
                                            id="role_{{ $role->id }}" value="{{ $role->id }}" {{ in_array($role->id, $user->roles->pluck('id')->toArray()) ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="role_{{ $role->id }}">
                                            {{ $role->name }}
                                        </label>
                                    </div>
                                @endforeach
                                </div>
                        </div>
                                @endif
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-success waves-effect waves-light float-right">@lang('global.save')</button>
                        <a href="{{ route('employeeIndex') }}" class="btn btn-light waves-effect float-left">@lang('global.cancel')</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/libs/inputmask/min/jquery.inputmask.bundle.min.js') }}"></script>

<!-- form mask init -->
<script src="{{ asset('assets/js/pages/form-mask.init.js') }}"></script>
@endsection