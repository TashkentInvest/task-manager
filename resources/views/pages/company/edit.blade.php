@extends('layouts.admin')

@section('content')

<!-- Content Header (Page header) -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Edit Company</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: #007bff;">@lang('global.home')</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('companyIndex') }}" style="color: #007bff;">Control Company</a></li>
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

                <form action="{{ route('companyUpdate', $company->id) }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-lg-6 mb-2">
                            <label for="name" class="col-form-label">Name</label>
                            <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" placeholder="Name" value="{{ old('name', $company->name) }}" required>
                            @if($errors->has('name'))
                                <span class="error invalid-feedback">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                        <div class="col-12 col-lg-6 mb-2">
                            <label for="owner_name" class="col-form-label">Owner name</label>
                            <input class="form-control" type="text" 
                            name="owner_name" id="owner_name" 
                            placeholder="Owner name" value="{{ old('owner_name', $company->owner_name) }}" required>
                        </div>
                        <div class="col-12 col-lg-6 mb-2">
                            <label for="phone" class="col-form-label">Phone number</label>
                            <input class="form-control" type="number" 
                            name="phone" id="phone" placeholder="Phone number" value="{{ old('phone', $company->phone) }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-success waves-effect waves-light float-right">@lang('global.save')</button>
                        <a href="{{ route('companyIndex') }}" class="btn btn-light waves-effect float-left">@lang('global.cancel')</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection