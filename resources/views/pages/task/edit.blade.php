@extends('layouts.admin')

@section('content')

<!-- Content Header (Page header) -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Редактировать поручение</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}" style="color: #007bff;">@lang('global.home')</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('monitoringIndex') }}" style="color: #007bff;">Список задач</a>
                    </li>
                    <li class="breadcrumb-item active">@lang('global.edit')</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<div class="row">
    <div class="col-lg-10 offset-lg-1 col-sm-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">@lang('global.edit')</h3>
            </div>

            <div class="card-body">
                <form action="{{ route('taskUpdate', $task->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Категория</label>
                            <select class="form-control select2" name="category_id" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $category->id == old('category_id', $task->category_id) ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                       
                    </div>

                    <div class="row mb-3">
                       

                    <div class="form-group">
                        <label>Поручение</label>
                        <input type="text" name="poruchenie" class="form-control" value="{{ old('poruchenie', $task->poruchenie) }}" required>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Дата выдачи</label>
                            <input type="date" name="issue_date" class="form-control" value="{{ old('issue_date', $task->issue_date) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label>Автор</label>
                            <input type="text" name="author" class="form-control" value="{{ old('author', $task->author) }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Исполнитель</label>
                            <input type="text" name="executor" class="form-control" value="{{ old('executor', $task->executor) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label>Со исполнитель</label>
                            <input type="text" name="co_executor" class="form-control" value="{{ old('co_executor', $task->co_executor) }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Срок выполнения (план)</label>
                            <input type="date" name="planned_completion_date" class="form-control" value="{{ old('planned_completion_date', $task->planned_completion_date) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label>Статус выполнения (факт)</label>
                            <input type="text" name="actual_status" class="form-control" value="{{ old('actual_status', $task->actual_status) }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Состояние исполнения</label>
                            <input type="text" name="execution_state" class="form-control" value="{{ old('execution_state', $task->execution_state) }}">
                        </div>

                        <div class="col-md-6">
                            <label>Закрепленный файл</label>
                            @if($task->attached_file)
                                <p>Текущий файл: <a href="{{ Storage::url($task->attached_file) }}" target="_blank">Скачать</a></p>
                            @endif
                            <input type="file" name="attached_file" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Примечание</label>
                        <textarea name="note" class="form-control">{{ old('note', $task->note) }}</textarea>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Оповещение</label>
                            <input type="text" name="notification" class="form-control" value="{{ old('notification', $task->notification) }}">
                        </div>

                        <div class="col-md-6">
                            <label>Приоритет</label>
                            <select class="form-control" name="priority">
                                <option value="Высокий" {{ old('priority', $task->priority) == 'Высокий' ? 'selected' : '' }}>Высокий</option>
                                <option value="Средний" {{ old('priority', $task->priority) == 'Средний' ? 'selected' : '' }}>Средний</option>
                                <option value="Низкий" {{ old('priority', $task->priority) == 'Низкий' ? 'selected' : '' }}>Низкий</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Тип документа</label>
                        <input type="text" name="document_type" class="form-control" value="{{ old('document_type', $task->document_type) }}">
                    </div>

                    <div class="form-group">
                        <label>Тип задачи</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="type_request" id="is_extra_yes" value="2" {{ old('type_request', $task->type_request) == '2' ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_extra_yes">Дополнительное поручение</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="type_request" id="is_extra_no" value="1" {{ old('type_request', $task->type_request) == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_extra_no">Позднее поручение</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="type_request" id="is_none" value="0" {{ old('type_request', $task->type_request) == '0' ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_none">Нет</label>
                        </div>
                    </div>

                    <div class="form-group mt-2">
                        <button type="submit" class="btn btn-success float-right">@lang('global.save')</button>
                        <a href="{{ route('monitoringIndex') }}" class="btn btn-light float-left">@lang('global.cancel')</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

@endsection
