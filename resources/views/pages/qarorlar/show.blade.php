@extends('layouts.admin')

@section('content')
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white text-center">
                <h3>Қарор Маълумотлари</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Уникал Код:</strong> {{ $qarorlar->unique_code }}</p>
                        <p><strong>Қисқача Ном:</strong> {{ $qarorlar->short_name }}</p>
                        <p><strong>Сана:</strong> {{ \Carbon\Carbon::parse($qarorlar->sana)->format('d-m-Y') }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Изоҳ:</strong> {{ $qarorlar->comment ?? 'Йўқ' }}</p>
                    </div>
                </div>

                <h4 class="mt-4">Файллар:</h4>
                <div class="list-group">
                    @foreach($qarorlar->files as $file)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="btn btn-link">
                                {{ basename($file->file_path) }} - <strong>Файлни Кўриш</strong>
                            </a>

                            <!-- Display image if the file is an image -->
                            @if(in_array(pathinfo($file->file_path, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                                <img src="{{ asset('storage/' . $file->file_path) }}" alt="Image" width="50" class="ml-2">
                            @else
                                <span class="badge badge-info">{{ pathinfo($file->file_path, PATHINFO_EXTENSION) }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
