@extends('layouts.admin')

@section('content')

<!-- Content Header (Page header) -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Add Driver</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: #007bff;">@lang('global.home')</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('driverIndex') }}" style="color: #007bff;">Control Driver</a></li>
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
                <h3 class="card-title">@lang('global.add')</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <form action="{{ route('driverCreate') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-12 mb-2">
                            <label for="full_name" class="col-form-label">Full Name</label>
                            <input class="form-control {{ $errors->has('full_name') ? 'is-invalid' : '' }}" type="text" 
                            name="full_name" id="full_name" placeholder="Full Name" value="{{ old('full_name') }}" required>
                            @if($errors->has('full_name'))
                                <span class="error invalid-feedback">{{ $errors->first('full_name') }}</span>
                            @endif
                        </div>

                        <div class="col-12 col-lg-6 mb-2">
                            <label for="track_num" class="col-form-label">Vehicle</label>
                            <input class="form-control" type="text" name="track_num" 
                            id="track_num" placeholder="AA 077 F" value="{{ old('track_num') }}" required>
                        </div>

                        <div class="col-12 col-lg-6 mb-2">
                            <label class="col-form-label">Company</label>
                            <select class="form-control select2" style="width: 100%;" name="company_id"
                                value="{{ old('company_id') }}" required>
                                <option></option>
                                @foreach($companies as $company)
                                    <option value="{{$company->id}}">{{$company->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-lg-6 mb-2">
                            <label for="eastern_time" class="col-form-label">Time Zone</label>
                            <input class="form-control" type="text" name="eastern_time" 
                            id="eastern_time" placeholder="Time Zone" value="{{ old('eastern_time') }}" required>
                        </div>

                        <div class="col-12 col-lg-6 mb-2">
                            <label for="phone" class="col-form-label">Phone number</label>
                            <input class="form-control" type="number" 
                            name="phone" id="phone" placeholder="Phone number" value="{{ old('phone') }}">
                        </div>

                        <div class="col-12 mb-2">
                            <label for="comment" class="col-form-label">Comment</label>
                            <textarea class="form-control" name="comment" cols="10"
                            placeholder="About">{{ old('comment') }}</textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-success waves-effect waves-light float-right">@lang('global.save')</button>
                        <a href="{{ route('driverIndex') }}" class="btn btn-light waves-effect float-left">@lang('global.cancel')</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection