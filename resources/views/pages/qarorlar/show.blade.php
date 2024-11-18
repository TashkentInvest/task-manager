@extends('layouts.admin')

@section('content')
    <h1 class="text-center">Қарор Маълумотлари</h1>
    <p><strong>Уникал Код:</strong> {{ $qarorlar->unique_code }}</p>
    <p><strong>Қисқача Ном:</strong> {{ $qarorlar->short_name }}</p>
    <p><strong>Сана:</strong> {{ $qarorlar->sana }}</p>
    <p><strong>Изоҳ:</strong> {{ $qarorlar->comment }}</p>

    <h3>Файллар:</h3>
    <ul>
        @foreach($qarorlar->files as $file)
            <li>
                <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="btn btn-link">Файлни Кўриш</a>
            </li>
        @endforeach
    </ul>
@endsection
