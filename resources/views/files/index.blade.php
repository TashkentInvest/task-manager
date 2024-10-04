@extends('layouts.admin')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Control Files</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: #007bff;">@lang('global.home')</a>
                        </li>
                        <li class="breadcrumb-item active">Control Files</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>

    <div class="row align-items-center mb-4">
        <div class="col-6">
            <h1 class="display-4 text-primary">Uploaded Files</h1>
        </div>
        <div class="col-6 text-end">
            <a class="btn btn-lg btn-outline-primary" href="{{ route('files.create') }}">
                <i class="fas fa-upload"></i> Upload Files
            </a>
        </div>
    </div>

    <table class="table">
        <tr>
            <th>Name</th>
            <th>Department</th>
            <th>File</th>
            <th>Created at</th>
            <th>Actions</th>
        </tr>
        @foreach ($files as $file)
            <tr>
                <td>{{ $file->name }}</td>
                <td>{{ $file->department }}</td>
                <td>
                    <a href="{{ route('files.show', $file->slug) }}">Open file</a>
                    {{-- <a href="{{ route('files.show', $file->slug) }}">{{ basename($file->file_name) }}</a> --}}
                </td>
                <td>{{ $file->created_at }}</td>
                <td>
                    <button class="btn btn-secondary"
                        onclick="copyToClipboard('{{ route('files.show', $file->slug) }}')">Copy Link</button>
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
