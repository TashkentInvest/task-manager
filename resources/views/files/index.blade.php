<!-- resources/views/files/index.blade.php -->

@extends('layouts.app')

@section('content')
    <h1>Uploaded Files</h1>
    <a href="{{ route('files.create') }}">Upload Files</a>
    <table class="table">
        <tr>
            <th>Name</th>
            <th>Department</th>
            <th>File</th>
            <th>Created at</th>
            <th>Actions</th>
        </tr>
        @foreach($files as $file)
        <tr>
            <td>{{ $file->name }}</td>
            <td>{{ $file->department }}</td>
            <td>
                <a href="{{ route('files.show', $file->slug) }}">Open file</a>
                {{-- <a href="{{ route('files.show', $file->slug) }}">{{ basename($file->file_name) }}</a> --}}
            </td>
            <td>{{ $file->created_at }}</td>
            <td>
                <button class="btn btn-secondary" onclick="copyToClipboard('{{ route('files.show', $file->slug) }}')">Copy Link</button>
            </td>
        </tr>
        @endforeach
    </table>

    <script>
        function copyToClipboard(text) {
            const input = document.createElement('input');
            input.style.position = 'fixed';
            input.style.opacity = '0';
            document.body.appendChild(input);
            input.value = text;
            input.select();
            document.execCommand('copy');
            document.body.removeChild(input);
            alert('Link copied to clipboard!');
        }
    </script>
@endsection
