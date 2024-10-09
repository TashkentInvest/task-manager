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
                                <th scope="col">Исполнитель</th>
                                <th scope="col">Поручение</th>
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
                                        @if ($roleNamesByTask[$item->id] ?? false)
                                            @foreach ($roleNamesByTask[$item->id] as $role)
                                                <span class="badge bg-primary text-light p-1 m-1">{{ $role }}</span>
                                            @endforeach
                                        @else
                                            <span class="badge bg-secondary text-light p-1 m-1">No Roles Assigned</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->executor }}</td>
                                    <td>{{ optional($item->issue_date)->format('d.m.Y') ?? $item->issue_date }}</td>
                                    <td>{{ optional($item->planned_completion_date)->format('d.m.Y') ?? $item->planned_completion_date }}</td>
                                    <td>
                                        @php
                                            $remainingDays = $item->planned_completion_date ? now()->diffInDays($item->planned_completion_date, false) : 'N/A';
                                        @endphp
                                        @if (is_int($remainingDays))
                                            {{ $remainingDays > 0 ? "{$remainingDays} days remaining" : ($remainingDays < 0 ? abs($remainingDays) . " days overdue" : "Due today") }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $item->status->color ?? 'secondary' }}">{{ $item->status->name ?? 'No Status' }}</span>
                                    </td>
                                    <td class="text-center">
                                        <ul class="list-unstyled d-flex gap-2 mb-0 justify-content-center">
                                            <li data-bs-toggle="tooltip" data-bs-placement="top" title="Принять">
                                                <button @click="onSubmit(item.id, {{ auth()->user()->id }})" type="button" class="btn btn-success btn-sm">
                                                    <i class="bx bxs-badge-check"></i>
                                                </button>
                                            </li>
                                            <li data-bs-toggle="tooltip" data-bs-placement="top" title="Редактировать">
                                                <a href="{{ route('taskEdit', $item->id) }}" class="btn btn-info btn-sm">
                                                    <i class="bx bxs-edit"></i>
                                                </a>
                                            </li>
                                            <li data-bs-toggle="tooltip" data-bs-placement="top" title="Удалить">
                                                <form action="{{ route('taskDestroy', $item->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="bx bxs-trash"></i>
                                                    </button>
                                                </form>
                                            </li>
                                            <li data-bs-toggle="tooltip" data-bs-placement="top" title="Подробности">
                                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal_{{ $item->id }}">
                                                    <i class="bx bxs-show"></i>
                                                </button>
                                            </li>
                                        </ul>
                                        @include('pages.monitoring.partials.task-modal', ['item' => $item, 'roleNamesByTask' => $roleNamesByTask])
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
