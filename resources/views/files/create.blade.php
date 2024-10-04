<!-- resources/views/files/create.blade.php -->

@extends('layouts.app')

@section('content')
    <h1>Upload Files</h1>
    <form action="{{ route('files.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="text" name="name" placeholder="Name" required>
        <input type="text" name="department" placeholder="Department" required>
        <input type="file" name="files[]" multiple required> <!-- Allow multiple files -->
        <button type="submit">Upload</button>
    </form>
@endsection
