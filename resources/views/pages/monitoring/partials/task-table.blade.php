<div class="table-responsive">
    <style>
        /* Compact table styling */
        th,
        td {
            font-size: 12px;
            padding: 8px !important;
            white-space: nowrap;
            text-overflow: ellipsis;
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th {
            background-color: #f8f9fa;
        }

        .badge {
            font-size: 10px;
            padding: 5px 8px;
        }

        .btn {
            font-size: 12px;
            padding: 4px 6px;
        }

        .btn-primary,
        .btn-info,
        .btn-success,
        .btn-warning {
            font-size: 10px;
        }

        /* Tooltip styling */
        [data-bs-toggle="tooltip"] {
            cursor: pointer;
        }

        td.note-column {
            max-width: 200px; /* Set a maximum width for the column */
            word-break: break-word;
            white-space: normal;
            line-height: 1.6; /* Add line-height for better spacing */
        }
        /* Responsive scrolling */
        @media screen and (max-width: 768px) {
            th,
            td {
                font-size: 10px;
            }

            .btn {
                padding: 2px 4px;
            }

            .badge {
                font-size: 8px;
            }

            .table-responsive {
                overflow-x: auto;
            }
        }
    </style>

    <table class="table table-nowrap align-middle table-borderless">
        <thead class="table-light">
            <tr>
                <th>Поручитель</th>
                <th>Департамент / Исполнитель</th>
                <th>Дата задачи / Дата окончания</th>
                <th>Оставшиеся дни</th>
                <th>Исполнитель</th>
                <th>Председатель</th>
                <th style="width: 300px !important">Note</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($tasks as $item)
                <tr>
                    <td title="{{ $item->user->name }}">{{ $item->user->name }}</td>
                    <td>
                        @if ($item->assign_type == 'role')
                            @if ($roleNamesByTask[$item->id] ?? false)
                                @php
                                    $roles = $roleNamesByTask[$item->id];
                                @endphp

                                <div class="d-flex align-items-center flex-wrap">
                                    <span class="badge bg-primary text-light" title="{{ $roles->first() }}">
                                        <i class="fas fa-user-tag"></i> {{ $roles->first() }}
                                    </span>
                                    @if ($roles->count() > 1)
                                        <span class="badge bg-secondary text-light" title="Назначены роли">
                                            <i class="fas fa-users"></i> +{{ $roles->count() - 1 }}
                                        </span>
                                    @endif
                                </div>
                            @else
                                <span class="badge bg-secondary text-light">
                                    <i class="fas fa-times-circle"></i> Роли не назначены
                                </span>
                            @endif
                        @elseif($item->assign_type == 'custom')
                            @php
                                $users = $item->task_users;
                            @endphp
                            @if ($users->isNotEmpty())
                                <div class="d-flex align-items-center flex-wrap">
                                    <span class="badge bg-primary text-light" title="{{ $users->first()->name }}">
                                        <i class="fas fa-user"></i> {{ $users->first()->name }}
                                    </span>
                                    @if ($users->count() > 1)
                                        <span class="badge bg-secondary text-light" title="Назначены пользователи">
                                            <i class="fas fa-users"></i> +{{ $users->count() - 1 }}
                                        </span>
                                    @endif
                                </div>
                            @else
                                <span class="badge bg-secondary text-light">
                                    <i class="fas fa-times-circle"></i> Нет назначенных пользователей
                                </span>
                            @endif
                        @else
                            <span class="badge bg-warning text-dark">Не найдено</span>
                        @endif
                    </td>
                    <td>{{ $item->issue_date ?? 'Не указана' }} / {{ $item->planned_completion_date ?? 'Не указана' }}</td>
                    <td>
                        @php
                            $remainingDays = $item->planned_completion_date
                                ? now()->diffInDays($item->planned_completion_date, false)
                                : 'N/A';
                        @endphp
                        @if (is_int($remainingDays))
                            @if ($remainingDays > 0)
                                <span class="badge bg-warning">Осталось: {{ $remainingDays }} дней</span>
                            @elseif ($remainingDays < 0)
                                <span class="badge bg-danger">Просрочено на {{ abs($remainingDays) }} дней</span>
                            @else
                                <span class="badge bg-warning">Срок сегодня</span>
                            @endif
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-{{ $item->status->getColor() }}">
                            {{ $item->status->name }}
                        </span>
                    </td>
                    <td>
                        @if (isset($item->order) && $item->order->checked_status == 1)
                            <span class="badge bg-success">Тасдиқланди</span>
                        @elseif(isset($item->order) && $item->order->checked_status == 2)
                            <span class="badge bg-danger">Рад этилди</span>
                        @endif
                    </td>
                    <td class="note-column">
                        {{ $item->note }}
                    </td>
                    <td class="text-center">
                        <div class="d-flex flex-wrap gap-1 justify-content-center">
                            <a href="{{ route('taskShow', $item->id) }}" class="btn btn-primary" title="Посмотреть">
                                Посмотреть
                            </a>
                            <a href="{{ route('monitoringFishka', $item->id) }}" class="btn btn-primary" title="PDF">
                                PDF
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">
                        <img src="{{ asset('assets/images/empty.png') }}" alt="No Data" style="width: 50%;">
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
