@extends('layouts.admin')

@section('content')
<!-- Content Header (Page header) -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Control File Create</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: #007bff;">@lang('global.home')</a></li>
                    <li class="breadcrumb-item active">Control File Create</li>
                </ol>
            </div>

        </div>
    </div>
</div>
    <h1>Upload Files</h1>
    <form action="{{ route('files.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="text" name="name" placeholder="Name" required>
        <input type="text" name="department" placeholder="Department" required>
        <input type="file" name="files[]" multiple required> <!-- Allow multiple files -->
        <button type="submit">Upload</button>
    </form>
@endsection
