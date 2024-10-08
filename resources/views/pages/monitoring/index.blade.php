@extends('layouts.admin')

@section('styles')
    <style>
        .project-list-table .monitoring-table tr {
            position: relative !important;
            height: 80px;
        }

        .project-list-table tr td .progress-request {
            position: absolute;
            width: 100%;
            left: 0;
            right: 0;
            bottom: 0px;
        }

        .project-list-table tr td .progress-request .progress {
            background: #808080ab;
        }

        .project-list-table tr td .progress-request .progress.progress-xl {
            height: 20px;
            font-size: 16px;
        }
    </style>
@endsection

@section('content')
    <div class="row mb-4">
        <div class="col-lg-6 col-12">
            <div class="">
                <h3 class="text-black text-5">Мониторинг</h3>
                <div class="table-responsive">
                    <table class="table project-list-table table-nowrap align-middle table-borderless">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Сотрудник</th>
                                <th scope="col">Компания</th>
                                <th scope="col">Водитель</th>
                                <th scope="col">Время</th>
                                <th scope="col">Действие</th>
                            </tr>
                        </thead>
                        <tbody class="monitoring-table">
                          {{-- Table content can be dynamically populated here --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-12">
            <div class="">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="text-black text-5">Оставшиеся поручения</h3>
                    @can('left-request.add')
                        <a href="{{ route('taskAdd') }}" class="btn btn-sm btn-success waves-effect waves-light float-right">
                            <span class="fas fa-plus-circle"></span>
                            Добавить
                        </a>
                    @endcan
                </div>
                <div class="table-responsive">
                    <table class="table project-list-table table-nowrap align-middle table-borderless">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Категория</th>
                                <th scope="col">Поручение</th>
                                <th scope="col">Исполнитель</th>
                                <th scope="col">Действие</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tasks as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->category->name }}</td>
                                    <td>{{ $item->poruchenie }}</td>
                                    <td>{{ $item->executor }}</td>
                                    
                                    <td class="text-center">
                                        <form :action="getTaskDestroyRoute(item.id)" method="post">
                                            @csrf
                                            <ul class="list-unstyled hstack gap-1 mb-0 justify-content-lg-center justify-content-md-center justify-content-start">
                                                <li data-bs-toggle="tooltip" data-bs-placement="top" title="Принять">
                                                    <button @click="onSubmit(item.id, {{ auth()->user()->id }})" type="button" class="btn btn-sm btn-success">
                                                        <i class="bx bxs-badge-check" style="font-size:16px;"></i>
                                                    </button>
                                                </li>

                                                <li data-bs-toggle="tooltip" data-bs-placement="top" title="Редактировать">
                                                    <a href="{{ route('taskEdit', $item->id) }}" class="btn btn-sm btn-info">
                                                        <i class="bx bxs-edit" style="font-size:16px;"></i>
                                                    </a>
                                                </li>
                                                <li data-bs-toggle="tooltip" data-bs-placement="top" title="Удалить">
                                                    <form action="{{ route('taskDestroy', $item->id) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">
                                                            <i class="bx bxs-trash" style="font-size:16px;"></i>
                                                        </button>
                                                    </form>
                                                </li>
                                                
                                                
                                              
                                                

                                                <li data-bs-toggle="tooltip" data-bs-placement="top" title="Подробности">
                                                    <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#exampleModal_{{$item->id}}">
                                                        <i class="bx bxs-show" style="font-size: 16px;"></i>
                                                    </button>
                                                </li>
                                            </ul>
                                        </form>

                                        <div class="modal fade" id="exampleModal_{{$item->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Оставшееся поручение ID: {{ $item->id }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table class="table table-striped">
                                                            <tbody>
                                                                <tr class="text-center">
                                                                    <td colspan="2"><b>Информация о поручении:</b></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><b>Категория:</b></td>
                                                                    <td>{{ $item->category->name }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><b>Поручение:</b></td>
                                                                    <td>{{ $item->poruchenie }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><b>Исполнитель:</b></td>
                                                                    <td>{{ $item->executor }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><b>Со исполнитель:</b></td>
                                                                    <td>{{ $item->co_executor }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><b>Дата выдачи:</b></td>
                                                                    <td>{{ $item->issue_date }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><b>Срок выполнения (план):</b></td>
                                                                    <td>{{ $item->planned_completion_date }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><b>Статус выполнения (факт):</b></td>
                                                                    <td>{{ $item->actual_status }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><b>Состояние исполнения:</b></td>
                                                                    <td>{{ $item->execution_state }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><b>Примечание:</b></td>
                                                                    <td>{{ $item->note }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><b>Оповещение:</b></td>
                                                                    <td>{{ $item->notification }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><b>Приоритет:</b></td>
                                                                    <td>{{ $item->priority }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><b>Тип документа:</b></td>
                                                                    <td>{{ $item->document_type }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><b>Тип задачи:</b></td>
                                                                    <td>{{ $item->type_request == '2' ? 'Дополнительное поручение' : ($item->type_request == '1' ? 'Позднее поручение' : 'Нет') }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><b>Закрепленный файл:</b></td>
                                                                    <td>
                                                                        @if($item->attached_file)
                                                                            <a href="{{ Storage::url($item->attached_file) }}" target="_blank">Скачать</a>
                                                                        @else
                                                                            Нет
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">
                                        <img src="{{ asset('assets/images/empty.png') }}" alt="" width="100%">
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
