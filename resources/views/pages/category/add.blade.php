@extends('layouts.admin')

@section('content')

<!-- Content Header (Page header) -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Add Control Category</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: #007bff;">@lang('global.home')</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('api-userIndex') }}" style="color: #007bff;">Control Category</a></li>
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

                <form action="{{ route('categoryCreate') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-lg-6 mb-2">
                            <label for="name" class="col-form-label">Name</label>
                            <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" placeholder="Name" value="{{ old('name') }}" required>
                            @if($errors->has('name'))
                                <span class="error invalid-feedback">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                        <div class="col-12 col-lg-6 mb-2">
                            <label for="count" class="col-form-label">Score</label>
                            <input class="form-control" type="number" 
                            name="score" id="count" 
                            placeholder="Enter score" value="{{ old('score') }}" required>
                        </div>
                        <div class="col-12 col-lg-6 mb-2">
                            <label for="deadline" class="col-form-label">Deadline</label>
                            <input class="form-control" type="number" name="deadline" id="deadline" placeholder="Type between 1 and 60" max="60" value="{{ old('deadline') }}" required>
                        </div>
                        <div class="col-12 col-lg-6 mb-2">
                            <label for="additional_time" class="col-form-label">Additional time</label>
                            <input class="form-control" 
                            type="additional_time" name="additional_time" 
                            id="additional_time" placeholder="Type between 1 and 60" max="60" 
                            value="{{ old('additional_time') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-success waves-effect waves-light float-right">@lang('global.save')</button>
                        <a href="{{ route('categoryIndex') }}" class="btn btn-light waves-effect float-left">@lang('global.cancel')</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection