@extends('layouts.admin')

@section('content')

<!-- Content Header (Page header) -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Edit Long text</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: #007bff;">@lang('global.home')</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('longTextIndex') }}" style="color: #007bff;">Edit Long Text</a></li>
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

                <form action="{{ route('longTextUpdate', $longText->id) }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-12 mb-2">
                            <label for="category" class="col-form-label">Category</label>
                            <input class="form-control {{ $errors->has('category') ? 'is-invalid' : '' }}" type="text" 
                            name="category" id="category" placeholder="Category" value="{{ old('category', $longText->category) }}" required>
                            @if($errors->has('category'))
                                <span class="error invalid-feedback">{{ $errors->first('category') }}</span>
                            @endif
                        </div>

                        <div class="col-12 mb-2">
                            <label for="description" class="col-form-label">Description</label>
                            <textarea class="form-control" name="description" cols="10" rows="10"
                            placeholder="About" required>{{ $longText->description }}</textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-success waves-effect waves-light float-right">@lang('global.save')</button>
                        <a href="{{ route('longTextIndex') }}" class="btn btn-light waves-effect float-left">@lang('global.cancel')</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection