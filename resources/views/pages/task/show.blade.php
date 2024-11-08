@extends('layouts.admin')

@section('content')
    <style>
        .card {
            border-radius: 10px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            border-radius: 10px 10px 0 0;
            background: linear-gradient(45deg, #007bff, #0056b3);
            color: white;
        }

        .card-body {
            padding: 20px;
        }

        .card-title {
            font-size: 1.25rem;
            color: #343a40;
        }

        .card-text {
            font-size: 1.1rem;
        }

        .btn {
            border-radius: 5px;
            padding: 10px 20px;
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-info {
            background-color: #17a2b8;
            border-color: #17a2b8;
        }

        .btn-link {
            color: #007bff;
            text-decoration: none;
        }

        .btn-link:hover {
            color: #0056b3;
            text-decoration: underline;
        }

        .modal-header {
            border-bottom: 1px solid #dee2e6;
        }

        .modal-footer {
            border-top: 1px solid #dee2e6;
        }

        .blockquote {
            border-left: 5px solid #dc3545;
            padding-left: 15px;
            margin: 0;
            font-size: 1.2rem;
            color: #495057;
        }

        .list-group-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 15px;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .form-control {
            border-radius: 5px;
        }

        .text-bold {
            font-weight: bold;
        }

        .text-muted {
            color: #6c757d !important;
        }
    </style>

    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-lg-12">
                <div class="card shadow-lg border-0 rounded">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">Детали поручения ID: {{ $item->id }}</h3>
                    </div>
                    <div class="card-body">
                        {{-- Task Details --}}
                        <h5 class="card-title text-secondary">Краткое название: <span class="text-bold"
                                style="font-weight: bold">{{ $item->short_title }}</span></h5> <br><br>
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <p class="card-text"><strong>Поручитель:</strong> <span
                                            class="text-muted">{{ $item->user->name }}</span></p>
                                    {{-- <p class="card-text"><strong>Категория:</strong> <span
                                            class="text-muted">{{ $item->category->name ?? 'Не указана' }}</span></p> --}}

                                    <p class="card-text"><strong>Закрепленный файл:</strong>
                                        @if ($item->files && $item->files->count() > 0)
                                            <ul class="list-group">
                                                @foreach ($item->files as $file)
                                                    @php
                                                        // Build the file path
                                                        $filePath = public_path('porucheniya/' . $file->file_name);
                                                    @endphp

                                                    @if (file_exists($filePath))
                                                        <!-- Check if the file exists in the specified directory -->
                                                        <li>
                                                            <span class="badge badge-soft-primary font-size-16 m-1">
                                                                {{ $file->name }}
                                                            </span>
                                                            <a href="{{ asset('porucheniya/' . $file->file_name) }}"
                                                                target="_blank"> Скачать
                                                            </a>
                                                            @if (auth()->user()->roles[0]->name == 'Super Admin')
                                                                <form action="{{ route('file.delete', $file->id) }}"
                                                                    method="POST" style="display:inline;">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="btn btn-link text-danger">Удалить</button>
                                                                </form>
                                                            @endif
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        @else
                                            <p>Нет загруженных файлов.</p>
                                        @endif

                                    </p>
                                </div>

                            </div>
                            <div class="col-6">
                                <div class="mb-3">

                                    @php
                                        $remainingDays = $item->planned_completion_date
                                            ? now()->diffInDays($item->planned_completion_date, false)
                                            : 'N/A';

                                        // Check if reject_time exists
                                        $isRejected = !is_null($item->reject_time);
                                    @endphp

                                    <p class="card-text"><strong>Дата выдачи:</strong> <span
                                            class="text-muted">{{ $item->issue_date ?? 'Не указана' }}</span></p>
                                    <p class="card-text"><strong>Срок выполнения:</strong> <span
                                            class="text-muted">{{ $item->planned_completion_date ?? 'Не указана' }}

                                            @if (is_int($remainingDays))
                                                @if ($isRejected)
                                                    @if ($remainingDays >= 0)
                                                        <span class="badge badge-soft-success font-size-16 m-1">
                                                            Срок выполнения еще не истек: {{ $remainingDays }} дней осталось
                                                        </span>
                                                    @else
                                                        <span class="badge badge-soft-danger font-size-16 m-1">
                                                            Срок завершения был {{ abs($remainingDays) }} дней назад
                                                        </span>
                                                    @endif
                                                @else
                                                    @if ($remainingDays > 0)
                                                        <span class="badge badge-soft-warning font-size-16 m-1">
                                                            {{ $remainingDays }} дней осталось
                                                        </span>
                                                    @elseif ($remainingDays < 0)
                                                        <span class="badge badge-soft-danger font-size-16 m-1">
                                                            {{ abs($remainingDays) }} дней просрочено
                                                        </span>
                                                    @else
                                                        <span class="badge badge-soft-warning font-size-16 m-1">
                                                            Срок сегодня
                                                        </span>
                                                    @endif
                                                @endif
                                            @else
                                                N/A
                                            @endif

                                        </span>
                                    </p>



                                </div>

                            </div>


                        </div>

                        <div class="mt-4 border p-3 rounded bg-light">
                            <p class="card-text"><strong>Примечание:</strong></p>
                            <blockquote class="blockquote">
                                <p class="mb-0">{{ $item->note }}</p>
                            </blockquote>

                        </div>

                        {{-- Admin Status Section --}}
                        @if ($item->order)
                            @if ($item->order->checked_status == 2)
                                <div class="mt-4 border p-3 rounded bg-light">
                                    <h5 class="text-danger">
                                        <h3>Председатель правления статус</h3> Восстановить по поручению
                                    </h5>
                                    <p class="card-text"><strong>Комментарий об восстановление:</strong></p>
                                    <blockquote class="blockquote">
                                        <p class="mb-0">{{ $item->order->checked_comment }}</p>
                                    </blockquote>
                                    <p class="card-text mt-3"><strong>Дата восстановление:</strong> <span
                                            class="text-muted">{{ $item->order->checked_time ?? '' }}</span></p>

                                </div>
                            @elseif($item->order->checked_status == 1)
                                <div class="mt-4 border p-3 rounded bg-light">
                                    <p class="card-text"><strong>Председатель правления статус:</strong></p>
                                    <blockquote class="blockquote text-success">
                                        <p class="mb-0">
                                        <h3>Председатель правления статус</h3> Вазифа тасдиқланди</p>
                                    </blockquote>

                                    <p class="card-text mt-3"><strong>Дата одобрения:</strong> <span
                                            class="text-muted">{{ $item->order->checked_time ?? '' }}</span></p>
                                </div>
                            @endif

                            {{-- Employee Rejection Comments --}}
                            @if ($item->status->id == 4)
                                <h3>Ходим статус</h3>
                                <div class="mt-4 border p-3 rounded bg-light">
                                    <h5 class="text-danger">Восстановить по поручению</h5>
                                    <p class="card-text"><strong>Кто отклонил:</strong> <span
                                            class="text-warning">{{ $item->order->user->name ?? 'Не указано' }}</span></p>
                                    <p class="card-text"><strong>Комментарий об восстановление:</strong></p>
                                    <blockquote class="blockquote">
                                        <p class="mb-0">{{ $item->reject_comment }}</p>
                                    </blockquote>
                                    @if ($item->files && count($item->files) > 0)
                                        <h5>Загруженные файлы:</h5>
                                        <ul class="list-group">
                                            @foreach ($item->files as $file)
                                                @php
                                                    // Build the file path
                                                    $filePath = public_path('porucheniya/reject/' . $file->file_name);
                                                @endphp

                                                @if (file_exists($filePath))
                                                    <!-- Check if the file exists in the specified directory -->
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        <span>
                                                            <a href="{{ asset('porucheniya/reject/' . $file->file_name) }}"
                                                                class="btn btn-primary" target="_blank">{{ $file->name }}
                                                                Посмотреть</a>
                                                            <form action="{{ route('file.delete', $file->id) }}"
                                                                method="POST" style="display:inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn btn-danger">Удалить</button>
                                                            </form>
                                                        </span>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    @else
                                        <p>Нет загруженных файлов.</p>
                                    @endif

                                    <p class="card-text mt-3"><strong>Дата восстановление:</strong> <span
                                            class="text-muted">{{ $item->reject_time }}</span></p>
                                </div>
                            @else
                                <div class="mt-4 border p-3 rounded bg-light">
                                    <h5 class="text-success">Завершено</h5>
                                    <p class="card-text"><strong>Кто закончил:</strong> <span
                                            class="text-warning">{{ $item->order->user->name ?? 'Не указано' }}</span>
                                    </p>
                                    <blockquote class="blockquote text-success">
                                        <p class="mb-0">Вазифа якунланди</p>
                                    </blockquote>

                                    @if ($item->files && count($item->files) > 0)
                                        <h5>Загруженные файлы:</h5>
                                        <ul class="list-group">
                                            @foreach ($item->files as $file)
                                                {{-- @dd($file) --}}
                                                @php
                                                    // Build the file path
                                                    $filePath = public_path('porucheniya/complete/' . $file->file_name);
                                                    // dd($filePath)
                                                @endphp

                                                @if (file_exists($filePath))
                                                    <!-- Check if the file exists in the specified directory -->
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        <span>
                                                            <a href="{{ asset('porucheniya/complete/' . $file->file_name) }}"
                                                                class="btn btn-primary" target="_blank">{{ $file->name }}
                                                                Посмотреть</a>
                                                            <form action="{{ route('file.delete', $file->id) }}"
                                                                method="POST" style="display:inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn btn-danger">Удалить</button>
                                                            </form>
                                                        </span>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    @else
                                        <p>Нет загруженных файлов.</p>
                                    @endif

                                    <p class="card-text mt-3"><strong>Дата окончания:</strong> <span
                                            class="text-muted">{{ $item->reject_time }}</span></p>
                                </div>
                            @endif
                        @endif


                        {{-- Action Buttons --}}
                        <div class="d-flex justify-content-end mt-4">
                            {{-- @if (auth()->user()->roles[0]->name != 'Super Admin' && $item->status->name == 'Accepted')
                                <form action="{{ route('orders.complete') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="task_id" value="{{ $item->id }}">
                                    <button type="submit" class="btn btn-success">Закончить</button>
                                </form>
                            @endif --}}
                            @if (auth()->user()->roles[0]->name == 'Super Admin' && $item->status->name != 'Active')
                                <a href="{{ route('taskEdit', $item->id) }}" class="btn btn-warning mx-2">Редактировать</a>
                                <form action="{{ route('orders.admin_confirm') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="task_id" value="{{ $item->id }}">
                                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                                    <button type="submit" class="btn btn-success">Принят</button>
                                </form>
                                <button class="btn btn-primary mx-2" data-bs-toggle="modal"
                                    data-bs-target="#rejectModal">Восстановить</button>
                            @else
                                @if ($item->status->name == 'Active' && auth()->user()->roles[0]->name != 'Super Admin')
                                    <form action="{{ route('orders.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="task_id" value="{{ $item->id }}">
                                        <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                                        <button type="submit" class="btn btn-success">
                                            Принят <i class="bx bxs-badge-check"></i>
                                        </button>
                                    </form>
                                @endif
                            @endif
                            @if (auth()->user()->roles[0]->name != 'Super Admin' && $item->status->name != 'Active')
                                {{-- <form action="{{ route('orders.complete') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="task_id" value="{{ $item->id }}">
                                    <button type="submit" class="btn btn-success">Закончить</button>
                                </form> --}}

                                <button class="btn btn-success mx-2" data-bs-toggle="modal"
                                    data-bs-target="#finishModalEmp">Завершить</button>

                                {{-- finishModalEmp --}}
                                <button class="btn btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#rejectModalEmp">Отказ</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Admin Reject Modal -->
        <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="rejectModalLabel">Восстановить по поручению ID: {{ $item->id }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('orders.admin_reject') }}" method="POST">
                            @csrf
                            <input type="hidden" name="task_id" value="{{ $item->id }}">
                            <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                            <div class="mb-3">
                                <label for="checked_comment" class="form-label">Комментарий об восстановление</label>
                                <textarea class="form-control" id="checked_comment" name="checked_comment" rows="3" required></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Отменить</button>
                                <button type="submit" class="btn btn-primary">Восстановить</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Employee Reject Modal -->
        <div class="modal fade" id="rejectModalEmp" tabindex="-1" aria-labelledby="rejectModalEmpLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="rejectModalEmpLabel">Отказ по поручению ID: {{ $item->id }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('orders.reject') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="task_id" value="{{ $item->id }}">
                            <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                            <div class="mb-3">
                                <label for="reject_comment" class="form-label">Комментарий об Отказе</label>
                                <textarea class="form-control" id="reject_comment" name="reject_comment" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="attached_file" class="form-label">Загрузить файл</label>
                                <input type="file" class="form-control" id="attached_file" name="attached_file[]"
                                    multiple>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Отменить</button>
                                <button type="submit" class="btn btn-danger">Отказ</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="finishModalEmp" tabindex="-1" aria-labelledby="finishModalEmpLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="finishModalEmpLabel">Восстановить по поручению ID:
                            {{ $item->id }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('orders.complete') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="task_id" value="{{ $item->id }}">
                            <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                            <div class="mb-3">
                                <label for="reject_comment" class="form-label">Комментарий об восстановление</label>
                                <textarea class="form-control" id="reject_comment" name="reject_comment" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="attached_file" class="form-label">Загрузить файл</label>
                                <input type="file" class="form-control" id="attached_file" name="attached_file[]"
                                    multiple>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Отменить</button>
                                <button type="submit" class="btn btn-success">Завершить</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
