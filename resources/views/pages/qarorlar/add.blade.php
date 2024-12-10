@extends('layouts.admin')

@section('content')
    <h1 class="text-center mb-4">Янги Қарор Қўшиш</h1>
    <form action="{{ route('qarorlarStore') }}" method="POST" enctype="multipart/form-data" class="mt-4">
        @csrf
        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">

        <div class="row mb-4">
            <div class="col-md-6">
                <label for="unique_code" class="form-label">Уникал Код</label>
                <input type="text" name="unique_code" id="unique_code" class="form-control @error('unique_code') is-invalid @enderror" required>
                @error('unique_code')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">Уникал код билан янгиликларни аниқ белгилашга ёрдам беради.</div>
            </div>


            <div class="col-md-6">
                <label for="sana" class="form-label">Сана</label>
                <input type="date" name="sana" id="sana" class="form-control @error('sana') is-invalid @enderror" required>
                @error('sana')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="amount" class="form-label">Нархи</label>
                <input type="number" name="amount" id="amount" 
                       class="form-control @error('amount') is-invalid @enderror" 
                       value="{{ old('amount') }}" 
                        min="0" max="9999999999999999999999999999.99" required>
                @error('amount')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
        </div>

        <div class="row mb-4">
           
            <div class="col-md-6">
                <label for="short_name" class="form-label">Қисқача Ном</label>
                <input type="text" name="short_name" id="short_name" class="form-control @error('short_name') is-invalid @enderror" required>
                @error('short_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">Қисқача ва тушунарли ном киритинг.</div>
            </div>
            <div class="col-md-6">
                <label for="comment" class="form-label">Изоҳ</label>
                <textarea name="comment" id="comment" class="form-control @error('comment') is-invalid @enderror" rows="4" placeholder="Қарорга изоҳ киритинг..."></textarea>
                @error('comment')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-4">
            <label for="files" class="form-label">Файлларни Юклаш</label>
            <input type="file" name="files[]" id="files" class="form-control @error('files') is-invalid @enderror" multiple>
            @error('files')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="form-text">PDF, DOC, JPG, PNG форматларини қўшишингиз мумкин. Бир неча файлни ҳам юклаш мумкин.</div>
        </div>

        <div class="mb-4">
            <label for="kuzatuv_files" class="form-label">Кузатув кенгашининг қарори</label>
            <input type="file" name="kuzatuv_files[]" id="kuzatuv_files" class="form-control @error('kuzatuv_files') is-invalid @enderror" multiple>
            @error('kuzatuv_files')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="form-text">PDF, DOC, JPG, PNG форматларини қўшишингиз мумкин. Бир неча файлни ҳам юклаш мумкин.</div>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-success btn-lg">Сақлаш</button>
        </div>
    </form>
@endsection
