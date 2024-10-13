@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card shadow-lg border-0 rounded">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Детали поручения ID: {{ $item->id }}</h3>
                </div>
                <div class="card-body">
                    <h5 class="card-title text-secondary">Краткое название: <span class="text-bold" style="font-weight: bold">{{ $item->short_title }}</span></h5>
                    <p class="card-text"><strong>Поручитель:</strong> {{ $item->user->name }}</p>
                    <p class="card-text"><strong>Категория:</strong> {{ $item->category->name ?? 'Не указана' }}</p>
                    <p class="card-text"><strong>Дата выдачи:</strong> {{ optional($item->issue_date)->format('d.m.Y') }}</p>
                    <p class="card-text"><strong>Срок выполнения:</strong> {{ optional($item->planned_completion_date)->format('d.m.Y') }}</p>
                    <p class="card-text"><strong>Примечание:</strong> {{ $item->note }}</p>
                    <p class="card-text"><strong>Закрепленный файл:</strong>
                        @if ($item->attached_file)
                            <a href="{{ Storage::url($item->attached_file) }}" target="_blank" class="text-decoration-none">Скачать</a>
                        @else
                            Нет
                        @endif
                    </p>

                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('taskEdit', $item->id) }}" class="btn btn-success">Закончить</a>
                        <a href="{{ route('taskEdit', $item->id) }}" class="btn btn-info mx-2">Редактировать</a>
                        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">Отказ</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="rejectModalLabel">Отказ по поручению ID: {{ $item->id }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('orders.reject') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="task_id" value="{{ $item->id }}">
                        <input type="hidden" name="user_id" value="{{ auth()->id() }}">

                        <div class="mb-3">
                            <label for="reject_comment" class="form-label">Комментарий</label>
                            <textarea class="form-control" id="reject_comment" name="reject_comment" rows="3" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="files" class="form-label">Файлы (можно выбрать несколько)</label>
                            <input type="file" class="form-control" id="files" name="files[]" multiple required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отменить</button>
                            <button type="submit" class="btn btn-success">Сахранить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
