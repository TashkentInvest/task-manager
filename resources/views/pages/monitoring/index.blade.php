@extends('layouts.admin')

@section('content')
    <div class="row mb-4">
        <div class="col-lg-12 col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="text-black text-5">Оставшиеся поручения</h3>
                    @can('left-request.add')
                        <a href="{{ route('taskAdd') }}" class="btn btn-success btn-sm">
                            <span class="fas fa-plus-circle"></span>
                            Добавить
                        </a>
                    @endcan
                </div>
                <div class="table-responsive">
                    <table class="table table-nowrap align-middle table-borderless">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Поручение</th>
                                <th scope="col">Исполнитель</th>
                                <th scope="col">Дата задачи</th>
                                <th scope="col">Дата окончания</th>
                                <th scope="col">Оставшиеся дни до окончания</th>
                                <th scope="col">Статус</th>
                                <th scope="col">Действие</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tasks as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>
                                        @if (isset($roleNamesByTask[$item->id]) && !empty($roleNamesByTask[$item->id]))
                                            @foreach ($roleNamesByTask[$item->id] as $role)
                                                <span class="badge bg-primary text-light p-1 m-1">{{ $role }}</span>
                                            @endforeach
                                        @else
                                            <span class="badge bg-secondary text-light p-1 m-1">No Roles Assigned</span>
                                        @endif
                                    </td>


                                    <td>{{ $item->executor }}</td>
                                    <td>
                                        @if ($item->issue_date instanceof \Carbon\Carbon)
                                            {{ $item->issue_date->format('d.m.Y') }}
                                        @else
                                            {{ $item->issue_date }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->planned_completion_date instanceof \Carbon\Carbon)
                                            {{ $item->planned_completion_date->format('d.m.Y') }}
                                        @else
                                            {{ $item->planned_completion_date }}
                                        @endif
                                    </td>

                                    <td>
                                        @if ($item->planned_completion_date instanceof \Carbon\Carbon)
                                            @php
                                                // Calculate remaining days
                                                $remainingDays = now()->diffInDays($item->planned_completion_date, false); // false gives a negative value if overdue
                                            @endphp
                    
                                            @if ($remainingDays > 0)
                                                {{ $remainingDays }} days remaining
                                            @elseif ($remainingDays < 0)
                                                {{ abs($remainingDays) }} days overdue
                                            @else
                                                Due today
                                            @endif
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->status)
                                            @if ($item->status->id == 1)
                                                <span class="badge bg-primary">Active</span>
                                            @elseif($item->status->id == 2)
                                                <span class="badge bg-warning text-dark">Process</span>
                                            @elseif($item->status->id == 3)
                                                <span class="badge bg-success">Completed</span>
                                            @else
                                                <span class="badge bg-danger">Rejected</span>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary">No Status</span>
                                            <!-- Customize this message -->
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        <ul class="list-unstyled d-flex gap-2 mb-0 justify-content-center">
                                            <li data-bs-toggle="tooltip" data-bs-placement="top" title="Принять">
                                                <button @click="onSubmit(item.id, {{ auth()->user()->id }})" type="button"
                                                    class="btn btn-success btn-sm">
                                                    <i class="bx bxs-badge-check"></i>
                                                </button>
                                            </li>
                                            <li data-bs-toggle="tooltip" data-bs-placement="top" title="Редактировать">
                                                <a href="{{ route('taskEdit', $item->id) }}" class="btn btn-info btn-sm">
                                                    <i class="bx bxs-edit"></i>
                                                </a>
                                            </li>
                                            <li data-bs-toggle="tooltip" data-bs-placement="top" title="Удалить">
                                                <form action="{{ route('taskDestroy', $item->id) }}" method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="bx bxs-trash"></i>
                                                    </button>
                                                </form>
                                            </li>
                                            <li data-bs-toggle="tooltip" data-bs-placement="top" title="Подробности">
                                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#exampleModal_{{ $item->id }}">
                                                    <i class="bx bxs-show"></i>
                                                </button>
                                            </li>
                                        </ul>

                                        <!-- Modal -->
                                        <div class="modal fade" id="exampleModal_{{ $item->id }}" tabindex="-1"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Оставшееся поручение
                                                            ID: {{ $item->id }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table class="table table-striped">
                                                            <tbody>
                                                                <tr class="text-center">
                                                                    <td colspan="2"><strong>Информация о
                                                                            поручении:</strong></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Категория:</strong></td>
                                                                    <td>{{ $item->category->name }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Автор:</strong></td>
                                                                    <td>{{ $item->user->name }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Поручение:</strong></td>
                                                                    <td>
                                                                        @if (isset($roleNamesByTask[$item->id]) && !empty($roleNamesByTask[$item->id]))
                                                                            @foreach ($roleNamesByTask[$item->id] as $role)
                                                                                <span
                                                                                    class="badge bg-primary text-light p-1 m-1">{{ $role }}</span>
                                                                            @endforeach
                                                                        @else
                                                                            <span
                                                                                class="badge bg-secondary text-light p-1 m-1">No
                                                                                Roles Assigned</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Исполнитель:</strong></td>
                                                                    <td>{{ $item->executor }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Со исполнителем:</strong></td>
                                                                    <td>{{ $item->co_executor }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Дата выдачи:</strong></td>
                                                                    <td>
                                                                        @if ($item->issue_date instanceof \Carbon\Carbon)
                                                                            {{ $item->issue_date->format('d.m.Y') }}
                                                                        @else
                                                                            {{ $item->issue_date }}
                                                                        @endif
                                                                    </td>


                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Срок выполнения (план):</strong></td>
                                                                    <td>
                                                                        @if ($item->planned_completion_date instanceof \Carbon\Carbon)
                                                                            {{ $item->planned_completion_date->format('d.m.Y') }}
                                                                        @else
                                                                            {{ $item->planned_completion_date }}
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Статус выполнения (факт):</strong></td>
                                                                    <td>{{ $item->actual_status }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Состояние исполнения:</strong></td>
                                                                    <td>{{ $item->execution_state }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Примечание:</strong></td>
                                                                    <td>{{ $item->note }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Оповещение:</strong></td>
                                                                    <td>{{ $item->notification }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Приоритет:</strong></td>
                                                                    <td>{{ $item->priority }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Тип документа:</strong></td>
                                                                    <td>{{ $item->document_type }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Тип задачи:</strong></td>
                                                                    <td>{{ $item->type_request == '2' ? 'Дополнительное поручение' : ($item->type_request == '1' ? 'Позднее поручение' : 'Нет') }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Закрепленный файл:</strong></td>
                                                                    <td>
                                                                        @if ($item->attached_file)
                                                                            <a href="{{ Storage::url($item->attached_file) }}"
                                                                                target="_blank">Скачать</a>
                                                                        @else
                                                                            Нет
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Закрыть</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">
                                        <img src="{{ asset('assets/images/empty.png') }}" alt="No Data" width="50%">
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
