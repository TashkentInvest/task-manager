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
                        {{-- @dd($item->category) --}}
                        <h5 class="card-title text-secondary">Краткое название: <span class="text-bold"
                                style="font-weight: bold">{{ $item->short_title }}</span></h5> <br><br>
                        <p class="card-text"><strong>Поручитель:</strong> {{ $item->user->name }}</p>
                        <p class="card-text"><strong>Категория:</strong> {{ $item->category->name ?? 'Не указана' }}</p>
                        <p class="card-text"><strong>Дата выдачи:</strong> {{ $item->issue_date ?? '' }}</p>
                        <p class="card-text"><strong>Срок выполнения:</strong> {{ $item->planned_completion_date ?? '' }}
                        </p>
                        <p class="card-text"><strong>Примечание:</strong> {{ $item->note }}</p>
                        <p class="card-text"><strong>Закрепленный файл:</strong>
                            @if ($item->attached_file)
                                <a href="{{ Storage::url($item->attached_file) }}" target="_blank"
                                    class="text-decoration-none">Скачать</a>
                            @else
                                Нет
                            @endif
                        </p>

                        {{-- admin reject start --}}

                        @if ($item->order->checked_status == 2)
                            <div class="mt-4 border p-3 rounded bg-light">
                                <h5 class="text-danger">Отказ по поручению</h5>

                                <p class="card-text"><strong>Комментарий об отказе:</strong></p>
                                <blockquote class="blockquote">
                                    <p class="mb-0">{{ $item->order->checked_comment }}</p>
                                </blockquote>


                                <p class="card-text mt-3"><strong>Дата отказа:</strong> <span
                                        class="text-muted">{{ $item->reject_time }}</span></p>
                            </div>
                        @elseif($item->order->checked_status == 1)
                            <blockquote class="blockquote text-success">
                                <p class="mb-0">Вазифа тасдиқланди</p>
                            </blockquote>
                        @endif

                        {{-- admin reject end --}}

                        {{-- @dump($order) --}}
                        {{-- Reject Comments Section --}}
                        @if ($item->reject_comment != null)
                            <div class="mt-4 border p-3 rounded bg-light">
                                <h5 class="text-danger">Отказ по поручению</h5>
                                @if (isset($item->order))
                                    <p class="card-text"><strong>Кто отклонил:</strong> <span
                                            class="text-warning">{{ $item->order->user->name }}</span></p>
                                @endif
                                <p class="card-text"><strong>Комментарий об отказе:</strong></p>
                                <blockquote class="blockquote">
                                    <p class="mb-0">{{ $item->reject_comment }}</p>
                                </blockquote>

                                @if ($item->files && count($item->files) > 0)
                                    <h5>Загруженные файлы:</h5>
                                    <ul class="list-group">
                                        @foreach ($item->files as $file)
                                            <li class="list-group-item">
                                                <strong>{{ $file->name }}</strong>
                                                <a href="{{ Storage::url($file->file_name) }}" target="_blank"
                                                    class="btn btn-link">Скачать</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p>Нет загруженных файлов.</p>
                                @endif

                                <p class="card-text mt-3"><strong>Дата отказа:</strong> <span
                                        class="text-muted">{{ $item->reject_time }}</span></p>
                            </div>
                        @endif
                        {{-- End Reject Comments Section --}}

                        <div class="d-flex justify-content-end mt-4">
                            @if (auth()->user()->roles[0]->name != 'Super Admin' && $item->status->name == 'Accepted')
                                <form action="{{ route('orders.complete') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="task_id" value="{{ $item->id }}">
                                    <button type="submit" class="btn btn-success">Закончить</button>
                                </form>
                            @endif
                            @if (auth()->user()->roles[0]->name == 'Super Admin')
                                <a href="{{ route('taskEdit', $item->id) }}" class="btn btn-info mx-2">Редактировать</a>


                                <form action="{{ route('orders.admin_confirm') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="task_id" value="{{ $item->id }}">
                                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                                    <button type="submit" class="btn btn-success">
                                        Принят
                                    </button>
                                </form>


                                <button class="btn btn-danger mx-2" data-bs-toggle="modal"
                                    data-bs-target="#rejectModal">Отказ</button>
                            @else
                                @if ($item->status->name == 'Active')
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
                            @if (auth()->user()->roles[0]->name != 'Super Admin' &&
                                    !isset($item->reject_comment) &&
                                    $item->status->name == 'Accepted')
                                <button class="btn btn-danger mx-2" data-bs-toggle="modal"
                                    data-bs-target="#rejectModal">Отказ</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rejection Modal -->
        <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="rejectModalLabel">Отказ по поручению ID: {{ $item->id }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('orders.admin_reject') }}" method="POST">
                            @csrf
                            <input type="hidden" name="task_id" value="{{ $item->id }}">
                            <input type="hidden" name="user_id" value="{{ auth()->id() }}">

                            <div class="mb-3">
                                <label for="checked_comment" class="form-label">Комментарий об отказе</label>
                                <textarea class="form-control" id="checked_comment" name="checked_comment" rows="3" required></textarea>
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
                                <input type="file" class="form-control" id="files" name="files[]" multiple
                                    required>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Отменить</button>
                                <button type="submit" class="btn btn-success">Сахранить</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
