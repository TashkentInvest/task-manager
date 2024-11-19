@extends('layouts.admin')

@section('content')
    <h1 class="text-center">Қарорни Таҳрирлаш</h1>
    <form action="{{ route('qarorlarUpdate', $qarorlar->id) }}" method="POST" enctype="multipart/form-data" class="mt-4">
        @csrf
        {{-- <div class="mb-3">
            <label for="user_id" class="form-label">Фойдаланувчи</label>
            <select name="user_id" id="user_id" class="form-select" required>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ $qarorlar->user_id == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div> --}}

        <input type="hidden" name="user_id" value="{{auth()->user()->id}}"> 

        <div class="mb-3">
            <label for="unique_code" class="form-label">Уникал Код</label>
            <input type="text" name="unique_code" id="unique_code" value="{{ $qarorlar->unique_code }}" class="form-control" required>
        </div>
        
        <div class="mb-3">
            <label for="sana" class="form-label">Сана</label>
            <input type="date" name="sana" id="sana" value="{{ $qarorlar->sana }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="short_name" class="form-label">Қисқача Ном</label>
            <input type="text" name="short_name" id="short_name" value="{{ $qarorlar->short_name }}" class="form-control" required>
        </div>

       

        <div class="mb-3">
            <label for="comment" class="form-label">Изоҳ</label>
            <textarea name="comment" id="comment" class="form-control">{{ $qarorlar->comment }}</textarea>
        </div>

        <div class="mb-3">
            <label for="files" class="form-label">Файлларни Янгилаш</label>
            <input type="file" name="files[]" id="files" class="form-control" multiple>
        </div>

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

        <button type="submit" class="btn btn-primary">Сақлаш</button>
    </form>
@endsection
