@extends('layouts.admin')

@section('content')
{{-- @dd($qarorlar) --}}
    <h1 class="text-center">Қарор Маълумотлари</h1>
    <p><strong>Уникал Код:</strong> {{ $qarorlar->unique_code }}</p>
    <p><strong>Қисқача Ном:</strong> {{ $qarorlar->short_name }}</p>
    <p><strong>Сана:</strong> {{ $qarorlar->sana }}</p>
    <p><strong>Изоҳ:</strong> {{ $qarorlar->comment }}</p>

    <h3>Файллар:</h3>
    <ul>
        @foreach($qarorlar->files as $file)
            <li>    
                <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="btn btn-link">
                    {{ basename($file->file_path) }} - Файлни Кўриш
                </a>
                
                <!-- Display image if the file is an image -->
                @if(in_array(pathinfo($file->file_path, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                    <br>
                    <img src="{{ asset('storage/' . $file->file_path) }}" alt="Image" width="200">
                @endif
            </li>
        @endforeach
    </ul>
@endsection
