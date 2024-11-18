@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1>Qarorlar</h1>
        <a href="{{ route('qarorlar.create') }}" class="btn btn-primary mb-3">Create New Qaror</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Unique Code</th>
                    <th>Short Name</th>
                    <th>Files</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($qarorlar as $qaror)
                    <tr>
                        <td>{{ $qaror->id }}</td>
                        <td>{{ $qaror->unique_code }}</td>
                        <td>{{ $qaror->short_name }}</td>
                        <td>
                            @foreach ($qaror->files as $file)
                                <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank">View File</a><br>
                            @endforeach
                        </td>
                        <td>
                            <a href="{{ route('qarorlar.edit', $qaror->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('qarorlar.destroy', $qaror->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
