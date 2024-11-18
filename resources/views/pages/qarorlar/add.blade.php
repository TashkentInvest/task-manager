@extends('layouts.admin')

@section('content')
    <h1 class="text-center">Янги Қарор Қўшиш</h1>
    <form action="{{ route('qarorlarStore') }}" method="POST" enctype="multipart/form-data" class="mt-4">
        @csrf
       <input type="hidden" name="user_id" value="{{auth()->user()->id}}">

        <div class="mb-3">
            <label for="unique_code" class="form-label">Уникал Код</label>
            <input type="text" name="unique_code" id="unique_code" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="short_name" class="form-label">Қисқача Ном</label>
            <input type="text" name="short_name" id="short_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="sana" class="form-label">Сана</label>
            <input type="date" name="sana" id="sana" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="comment" class="form-label">Изоҳ</label>
            <textarea name="comment" id="comment" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label for="files" class="form-label">Файлларни Юклаш</label>
            <input type="file" name="files[]" id="files" class="form-control" multiple>
        </div>

        <button type="submit" class="btn btn-primary">Сақлаш</button>
    </form>
@endsection
