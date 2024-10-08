@extends('layouts.admin')

@section('content')

<!-- Content Header (Page header) -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Добавить поручение</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}" style="color: #007bff;">@lang('global.home')</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('api-userIndex') }}" style="color: #007bff;">Контроль категории</a>
                    </li>
                    <li class="breadcrumb-item active">@lang('global.add')</li>
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
                <h3 class="card-title">@lang('global.add')</h3>
            </div>

            <div class="card-body">
                <form id="taskForm" action="{{ route('taskCreate') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Категория</label>
                            <select class="form-control select2" style="width: 100%;" name="category_id" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Дата выдачи</label>
                            <input type="date" name="issue_date" class="form-control" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Автор поручения</label>
                            <input type="text" name="author" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Исполнитель поручения</label>
                            <input type="text" name="executor" class="form-control" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Со исполнитель (опционально)</label>
                            <input type="text" name="co_executor" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Срок выполнения (план)</label>
                            <input type="date" name="planned_completion_date" class="form-control" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Статус выполнения (факт) (опционально)</label>
                            <input type="text" name="actual_status" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Состояние исполнения (опционально)</label>
                            <input type="text" name="execution_state" class="form-control">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Закрепленный файл (опционально)</label>
                            <input type="file" name="attached_file" class="form-control" accept=".pdf, .jpg, .jpeg, .png">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Примечание (опционально)</label>
                            <textarea rows="3" name="note" class="form-control"></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Оповещение (опционально)</label>
                            <input type="text" name="notification" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Приоритет (опционально)</label>
                            <select class="form-control" name="priority">
                                <option value="">Выберите приоритет</option>
                                <option value="Высокий">Высокий</option>
                                <option value="Средний">Средний</option>
                                <option value="Низкий">Низкий</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Вид документа (опционально)</label>
                            <input type="text" name="document_type" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Это дополнительное поручение?</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type_request" id="is_extra_yes" value="2">
                                <label class="form-check-label" for="is_extra_yes">Дополнительное поручение</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type_request" id="is_extra_no" value="1">
                                <label class="form-check-label" for="is_extra_no">Позднее поручение</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type_request" id="is_extra_none" value="0">
                                <label class="form-check-label" for="is_extra_none">Нет</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-2">
                        <button type="submit" id="submitButton" class="btn btn-success float-right">@lang('global.save')</button>
                        <a href="{{ route('monitoringIndex') }}" class="btn btn-light waves-effect float-left">@lang('global.cancel')</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
