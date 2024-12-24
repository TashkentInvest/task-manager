<div class="kanban-board">
    <style>
        /* General Board Styling */
        .kanban-board {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            padding: 15px;
            background-color: #f4f5f7;
            border-radius: 8px;
        }

        /* Task Card Styling */
        .kanban-card {
            width: 100%;
            max-width: 300px;
            background: #fff;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border-left: 5px solid #007bff;
            display: flex;
            flex-direction: column;
        }

        .kanban-card-header {
            padding: 12px;
            background-color: #f8f9fa;
            font-weight: bold;
            font-size: 14px;
            color: #333;
            border-bottom: 1px solid #ddd;
        }

        .kanban-card-body {
            padding: 12px;
            flex: 1;
        }

        .kanban-card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 12px;
            background-color: #f4f5f7;
            border-top: 1px solid #ddd;
        }

        .badge {
            display: inline-block;
            font-size: 10px;
            padding: 3px 6px;
            border-radius: 5px;
            color: #fff;
        }

        .badge-primary {
            background-color: #007bff;
        }

        .badge-success {
            background-color: #28a745;
        }

        .badge-warning {
            background-color: #ffc107;
            color: #000;
        }

        .badge-danger {
            background-color: #dc3545;
        }

        .kanban-card-footer .btn {
            font-size: 10px;
            padding: 4px 8px;
            color: #fff;
            background-color: #007bff;
            text-decoration: none;
            border-radius: 4px;
            transition: 0.3s;
        }

        .kanban-card-footer .btn:hover {
            background-color: #0056b3;
        }

        /* Tooltip on Hover */
        [data-bs-toggle="tooltip"] {
            cursor: pointer;
        }

        /* Responsive Adjustments */
        @media screen and (max-width: 768px) {
            .kanban-card {
                max-width: 100%;
            }
        }
    </style>

    <!-- Task Cards -->
    @foreach ($tasks as $item)
        <div class="kanban-card">
            <!-- Card Header -->
            <div class="kanban-card-header">
                {{ $item->user->name }}
            </div>

            <!-- Card Body -->
            <div class="kanban-card-body">
                <p><strong>Департамент / Исполнитель:</strong>
                    @if ($item->assign_type == 'role')
                        @php
                            $roles = $roleNamesByTask[$item->id] ?? [];
                        @endphp
                        @if ($roles)
                            {{ $roles->first() }} @if ($roles->count() > 1)
                                <span class="badge badge-secondary">+{{ $roles->count() - 1 }} роли</span>
                            @endif
                        @else
                            <span class="badge badge-warning">Роли не назначены</span>
                        @endif
                    @else
                        Назначенный пользователь
                    @endif
                </p>

                <p><strong>Дата задачи:</strong> {{ $item->issue_date ?? 'Не указана' }}</p>
                <p><strong>Дата окончания:</strong> {{ $item->planned_completion_date ?? 'Не указана' }}</p>

                <p><strong>Оставшиеся дни:</strong>
                    @php
                        $remainingDays = $item->planned_completion_date
                            ? now()->diffInDays($item->planned_completion_date, false)
                            : 'N/A';
                    @endphp
                    @if (is_int($remainingDays))
                        @if ($remainingDays > 0)
                            <span class="badge badge-warning">Осталось: {{ $remainingDays }} дней</span>
                        @elseif ($remainingDays < 0)
                            <span class="badge badge-danger">Просрочено на {{ abs($remainingDays) }} дней</span>
                        @else
                            <span class="badge badge-warning">Срок сегодня</span>
                        @endif
                    @else
                        N/A
                    @endif
                </p>

                <p><strong>Note:</strong> {{ $item->note }}</p>
            </div>

            <!-- Card Footer -->
            <div class="kanban-card-footer">
                <a href="{{ route('taskShow', $item->id) }}" class="btn btn-primary">Посмотреть</a>
                <a href="{{ route('monitoringFishka', $item->id) }}" class="btn btn-warning">PDF</a>
            </div>
        </div>
    @endforeach
</div>
