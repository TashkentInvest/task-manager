@extends('layouts.admin')

@section('content')

<!-- Content Header (Page header) -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">@lang('cruds.user.title')</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: #007bff;">@lang('global.home')</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('userIndex') }}" style="color: #007bff;">@lang('cruds.user.title')</a></li>
                    <li class="breadcrumb-item active">@lang('global.edit')</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- Main content -->


<div class="row">
    <div class="col-lg-8 offset-lg-2 col-sm-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">@lang('global.edit')</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('userUpdate', $user->id) }}" method="post" enctype="multipart/form-data">

                    @csrf

                    <div class="mb-3">
                        <label for="name" class="col-md-2 col-form-label">@lang('cruds.user.fields.name')</label>
                        <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" placeholder="@lang('cruds.user.fields.name')" value="{{ old('name',$user->name) }}" required>
                        @if($errors->has('name'))
                            <span class="error invalid-feedback">{{ $errors->first('name') }}</span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="email" class="col-md-2 col-form-label">@lang('cruds.user.fields.email')</label>
                        <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email" id="email" placeholder="@lang('cruds.user.fields.email')" value="{{ old('email',$user->email) }}" required>
                        @if($errors->has('email'))
                            <span class="error invalid-feedback">{{ $errors->first('name') }}</span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="col-md-2 col-form-label">Phone</label>
                        <input class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}" type="text" name="phone" id="phone" placeholder="phone" value="{{ old('phone',$user->phone) }}" required>
                        @if($errors->has('phone'))
                            <span class="error invalid-feedback">{{ $errors->first('name') }}</span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="birth_date" class="col-md-2 col-form-label">Birth date</label>
                        <input class="form-control {{ $errors->has('birth_date') ? 'is-invalid' : '' }}" type="date" name="birth_date" id="birth_date" placeholder="birth_date" value="{{ old('birth_date',$user->birth_date) }}" >
                        @if($errors->has('birth_date'))
                            <span class="error invalid-feedback">{{ $errors->first('name') }}</span>
                        @endif
                    </div>

                    @if(isset($user->roles[0]) && auth()->user()->roles[0]->name != 'Employee')
                    <div class="mb-3">
                        <label for="hire_date" class="col-md-2 col-form-label">Hire date</label>
                        <input class="form-control {{ $errors->has('hire_date') ? 'is-invalid' : '' }}" type="date" name="hire_date" id="hire_date" placeholder="hire_date" value="{{ old('hire_date',$user->hire_date) }}" >
                        @if($errors->has('hire_date'))
                            <span class="error invalid-feedback">{{ $errors->first('name') }}</span>
                        @endif
                    </div>
                    @endif

                    <div class="mb-3">
                        <label for="location" class="col-md-2 col-form-label">Location</label>
                        <input class="form-control {{ $errors->has('location') ? 'is-invalid' : '' }}" type="text" name="location" id="location" placeholder="location" value="{{ old('location',$user->location) }}" required>
                        @if($errors->has('location'))
                            <span class="error invalid-feedback">{{ $errors->first('name') }}</span>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label for="avatar" class="col-md-2 col-form-label">Avatar</label>
                        <input type="file" class="form-control" name="avatar" id="avatar">
                        @if($errors->has('avatar'))
                            <span class="error invalid-feedback">{{ $errors->first('avatar') }}</span>
                        @endif
                    </div>
                    


                    @canany(['roles.edit','user.edit'])
                    @if(isset($user->roles[0]) && auth()->user()->roles[0]->name != 'Employee')
                    <div class="mb-3">
                        <label>@lang('cruds.role.fields.roles')</label>
                        <select name="roles[]" 
                        class="select2 form-control select2-multiple" 
                        multiple="multiple" 
                        data-placeholder="@lang('pleaseSelect')" 
                        style="width: 100%;">
                            @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ ($user->hasRole($role->name) ? "selected":'') }}>{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
             
                    @endif
                    @endcan

                    <div class="mb-3">
                        <label for="password" class="form-label">@lang('cruds.user.fields.password')</label>
                        <div class="input-group auth-pass-inputgroup">
                            <input id="password" type="password" class="form-control {{ $errors->has('password') ? 'is-invalid': '' }}" name="password"  placeholder="@lang('cruds.user.fields.password')" aria-label="Password" aria-describedby="password-addon">
                            <button class="btn btn-light " type="button" onclick="togglePassword('password', 'toggle-icon-1')">
                                <i id="toggle-icon-1" class="mdi mdi-eye-outline"></i>
                            </button>
                        </div>
                        @if($errors->has('password'))
                            <span class="error invalid-feedback">{{ $errors->first('password') }}</span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">@lang('global.login_password_confirmation')</label>
                        <div class="input-group auth-pass-inputgroup">
                            <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" autocomplete="new-password" placeholder="@lang('global.login_password_confirmation')" aria-label="Password" aria-describedby="password-addon">
                            <button class="btn btn-light " type="button" onclick="togglePassword('password_confirmation', 'toggle-icon-2')">
                                <i id="toggle-icon-2" class="mdi mdi-eye-outline"></i>
                            </button>
                        </div>
                        @if($errors->has('password_confirmation'))
                            <span class="error invalid-feedback">{{ $errors->first('password_confirmation') }}</span>
                        @endif
                    </div>


                    <div class="form-group">
                        <button type="submit" class="btn btn-success waves-effect waves-light float-right">@lang('global.save')</button>
                        <a href="{{ route('userIndex') }}" class="btn btn-light waves-effect float-left">@lang('global.cancel')</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

@endsection