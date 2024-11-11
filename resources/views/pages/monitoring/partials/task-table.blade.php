<!-- resources/views/partials/task-table.blade.php -->
<div class="table-responsive">
    <table class="table table-nowrap align-middle table-borderless">
        <thead class="table-light">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Поручитель</th>
                <th scope="col">Департамент / Исполнитель</th>
                <th scope="col">Краткое название</th>
                <th scope="col">Дата задачи</th>
                <th scope="col">Дата окончания</th>
                <th scope="col">Оставшиеся дни</th>
                <th scope="col">Исполнитель</th>
                <th scope="col">Председатель правления</th>
                <th scope="col">Действия</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($tasks as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->user->name }}</td>
                    <td>
                        @if ($item->assign_type == 'role')
                            @if ($roleNamesByTask[$item->id] ?? false)
                                @php
                                    $roles = $roleNamesByTask[$item->id];
                                @endphp

                                <div class="d-flex align-items-center flex-wrap" style="max-width: 250px;">
                                    @if ($roles->count() > 1)
                                        <span class="badge bg-primary text-light p-2 m-1">
                                            <i class="fas fa-user-tag"></i> {{ $roles->first() }} и
                                        </span>
                                        <span class="badge bg-secondary text-light p-2 m-1">
                                            <i class="fas fa-users"></i> {{ $roles->count() }} роли
                                            назначены
                                        </span>
                                    @else
                                        <span class="badge bg-primary text-light p-2 m-1">
                                            <i class="fas fa-user-tag"></i> {{ $roles->first() }}
                                        </span>
                                    @endif
                                </div>
                            @else
                                <span class="badge bg-secondary text-light p-2 m-1">
                                    <i class="fas fa-times-circle"></i> Роли не назначены
                                </span>
                            @endif
                        @elseif($item->assign_type == 'custom')
                            @php
                                $users = $item->task_users;
                            @endphp
                            @if ($users->isNotEmpty())
                                <div class="d-flex align-items-center flex-wrap" style="max-width: 250px;">
                                    @if ($users->count() > 1)
                                        <span class="badge bg-primary text-light p-2 m-1">
                                            <i class="fas fa-user"></i> {{ $users->first()->name }} и
                                        </span>
                                        <span class="badge bg-secondary text-light p-2 m-1">
                                            <i class="fas fa-users"></i> {{ $users->count() }} пользователей
                                            назначено
                                        </span>
                                    @else
                                        <span class="badge bg-primary text-light p-2 m-1">
                                            <i class="fas fa-user"></i> {{ $users->first()->name }}
                                        </span>
                                    @endif
                                </div>
                            @else
                                <span class="badge bg-secondary text-light p-2 m-1">
                                    <i class="fas fa-times-circle"></i> Нет назначенных пользователей
                                </span>
                            @endif
                        @else
                            <span class="badge bg-warning text-dark p-2 m-1">Не найдено</span>
                        @endif
                    </td>

                    <td>{{ $item->short_title }}</td>
                    <td>{{ $item->issue_date ?? 'Не указана' }}</td>
                    <td>{{ $item->planned_completion_date ?? 'Не указана' }}
                    </td>
                    <td>
                        @php
                            $remainingDays = $item->planned_completion_date
                                ? now()->diffInDays($item->planned_completion_date, false)
                                : 'N/A';

                            // Check if reject_time exists
                            $isRejected = !is_null($item->reject_time);
                        @endphp

                        @if (is_int($remainingDays))
                            @if ($isRejected)
                                @if ($remainingDays >= 0 && $item->order->$order->checked_status == 1)
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

                    </td>
                    {{-- <td>
                        <div class="d-flex align-items-center">
                            <span class="badge badge-soft-{{ $item->status->getColor() }} font-size-16 m-1">
                                {{ $item->status->name }}
                            </span>
                    
                        </div>
                    </td> --}}
                    <td>
                        <div class="d-flex align-items-center">
                            <span class="badge badge-soft-{{ $item->status->getColor() }} font-size-16 m-1">
                                @if ($item->status->name == 'Active')
                                    Активный
                                @elseif($item->status->name == 'Canceled')
                                    Отмененный
                                @elseif($item->status->name == 'Accepted')
                                    Принятый
                                @elseif($item->status->name == 'Completed')
                                    Завершенный
                                @elseif($item->status->name == 'Deleted')
                                    Удаленный
                                @elseif($item->status->name == 'ORDER_ACTIVE')
                                    Заказ активен
                                @elseif($item->status->name == 'TIME_IS_OVER')
                                    Время истекло
                                @elseif($item->status->name == 'ADMIN_REJECT')
                                    Отклонен администратором
                                @elseif($item->status->name == 'XODIM_REJECT')
                                    Отклонен сотрудником
                                @else
                                    {{ $item->status->name }} <!-- Default to original name if not matched -->
                                @endif
                            </span>
                        </div>
                    </td>

                    <td>
                        @if (isset($item->order))
                            @if ($item->order->checked_status == 1)
                                <div class="d-flex align-items-center">
                                    <span class="badge badge-soft-success font-size-16 m-1">
                                        Вазифа тасдиқланди
                                    </span>
                                </div>
                            @elseif($item->order->checked_status == 2)
                                <div class="d-flex align-items-center">
                                    <span class="badge badge-soft-danger font-size-16 m-1">
                                        Вазифа рад этилди
                                    </span>
                                </div>
                            @else
                                <p></p>
                            @endif
                        @endif
                    </td>

                    <td class="text-center">
                        <ul class="list-unstyled d-flex gap-2 mb-0 justify-content-center">
                            @if (auth()->user()->roles[0]->name != 'Super Admin')
                                @if ($item->status->name == 'Active')
                                    <li data-bs-toggle="tooltip" data-bs-placement="top" title="Принять">
                                        <form action="{{ route('orders.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="task_id" value="{{ $item->id }}">
                                            <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                                            <button type="submit" class="btn btn-success">
                                                <i class="bx bxs-badge-check"></i>
                                            </button>
                                        </form>
                                    </li>
                                @endif
                            @else
                                <li data-bs-toggle="tooltip" data-bs-placement="top" title="Редактировать">
                                    <a href="{{ route('taskEdit', $item->id) }}" class="btn btn-info">
                                        <i class="bx bxs-edit"></i>
                                    </a>
                                </li>
                                <li data-bs-toggle="tooltip" data-bs-placement="top" title="Удалить">
                                    <form action="{{ route('taskDestroy', $item->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <i class="bx bxs-trash"></i>
                                        </button>
                                    </form>
                                </li>
                            @endif
                            <li data-bs-toggle="tooltip" data-bs-placement="top" title="Подробности">
                                <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal_{{ $item->id }}">
                                    <i class="bx bxs-show"></i>
                                </button>
                            </li>

                            <li data-bs-toggle="tooltip" data-bs-placement="top" title="Посмотреть">
                                <a href="{{ route('taskShow', $item->id) }}" class="btn btn-primary">
                                    <i class="bx bxs-link"></i> Посмотреть
                                </a>
                            </li>

                        </ul>
                        @include('pages.monitoring.partials.task-modal', [
                            'item' => $item,
                            'roleNamesByTask' => $roleNamesByTask,
                        ])
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
