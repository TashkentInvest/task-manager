@extends('layouts.admin')

@section('content')
    <div class="container mt-5">
        <h1 class="mb-4 text-center text-primary">Ҳужжат №{{ $document->id }}</h1>

        <!-- Document Details Section -->
        <div class="mb-4">
            <p><strong>Сарлавха:</strong> {{ $document->title }}</p>
            <p><strong>Категория:</strong> 
                {{ $document->category ? $document->category->name : 'Категория танланмаган' }}
            </p>
            <p><strong>Хат Рақами:</strong> {{ $document->letter_number }}</p>
            <p><strong>Қабул Қилинган Санаси:</strong> {{ \Carbon\Carbon::parse($document->received_date)->format('d-m-Y H:i') }}</p>
        </div>

        <hr>

        <!-- Files Section -->
        <div class="mb-4">
            <h4>Қўшимча Файллар:</h4>
            @if($document->files->count())
                <ul class="list-group">
                    @foreach($document->files as $file)
                        <li class="list-group-item">
                            <a href="{{ Storage::url($file->file_path) }}" target="_blank">
                                {{ basename($file->file_path) }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @else
                <p>Файллар қўшилмаган.</p>
            @endif
        </div>

        <hr>

        <!-- Action Links -->
        <div class="d-flex justify-content-between">
            <a href="{{ route('documents.edit', $document->id) }}" class="btn btn-warning">Ҳужжатни Таҳрирлаш</a>
            <a href="{{ route('documents.index') }}" class="btn btn-secondary">Ҳужжатлар Рўйхатга Қайтиш</a>
        </div>
    </div>
@endsection
