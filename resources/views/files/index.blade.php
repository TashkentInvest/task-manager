@extends('layouts.admin')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Файлы</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}" class="text-primary">@lang('global.home')</a>
                        </li>
                        <li class="breadcrumb-item active">Файлы</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Загруженные файлы Heading & Upload Button -->
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h1 class="display-6 text-primary">Загруженные файлы</h1>
        </div>
        <div class="col-md-6 text-end">
            <a class="btn btn-lg btn-outline-primary" href="{{ route('files.create') }}">
                <i class="fas fa-upload"></i> Загрузить файлы
            </a>
        </div>
    </div>

    <!-- File Table -->
    <div class="table-responsive">
        <table class="table table-hover align-middle table-bordered">
            <thead class="table-light">
                <tr>
                
                        <th>Имя</th>
                        <th>Отдел</th>
                        <th>Файл</th>
                        <th>Создано</th>
                        <th>Действия</th>
                    
                    
                    <th>Сайт (Trello)</th>

                </tr>
            </thead>
            <tbody>
                @foreach ($files as $file)
                    <tr>
                        <td>{{ $file->name }}</td>
                        <td>{{ $file->department }}</td>
                        <td>
                            <a href="{{ route('files.show', $file->slug) }}" class="text-primary">
                                <i class="fas fa-file"></i> Open file
                            </a>
                        </td>
                        <td>{{ $file->created_at->format('d-m-Y H:i') }}</td>
                        <td>
                            <button class="btn btn-sm btn-secondary"
                                onclick="copyToClipboard('{{ route('files.show', $file->slug) }}')">
                                <i class="fas fa-copy"></i> Copy Link
                            </button>
                        </td>

                        <td>
                            <a target="_blank" class="btn btn-sm btn-light" href="https://trello.com/b/r5YPolha/">Trello
                                <img width="30" src="https://cdn.icon-icons.com/icons2/3053/PNG/512/trello_macos_bigsur_icon_189616.png" alt="">
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- JavaScript for Copy to Clipboard -->
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
            console.log('Link copied to clipboard!');
        }
    </script>
@endsection
