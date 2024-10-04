@extends('layouts.admin')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Create New File</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}" class="text-primary">@lang('global.home')</a>
                        </li>
                        <li class="breadcrumb-item active">Create New File</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Form -->
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-body">
                    <h1 class="mb-4 text-primary">Upload Files</h1>

                    <form action="{{ route('files.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="user_id" id="" value="{{auth()->user()->id ?? null}}">

                        <!-- Name Input -->
                        <div class="mb-3">
                            <label for="name" class="form-label">File Name</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{auth()->user()->name}}" placeholder="Enter file name" required>
                        </div>

                        <!-- Department Input -->
                        <div class="mb-3">
                            <label for="department" class="form-label">Department</label>
                            <input type="text" name="department" id="department" class="form-control" value="{{auth()->user()->about ?? null}}" placeholder="Enter department" required>
                        </div>

                        <!-- File Input -->
                        <div class="mb-3">
                            <label for="files" class="form-label">Choose Files</label>
                            <input type="file" name="files[]" id="files" class="form-control" multiple required> <!-- Allow multiple files -->
                            <small class="form-text text-muted">You can upload multiple files.</small>
                        </div>

                        <!-- Submit Button -->
                        <div class="text-center">
                            <button type="submit" class="btn btn-lg btn-primary">
                                <i class="fas fa-upload"></i> Upload Files
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
