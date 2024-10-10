<div class="modal fade" id="exampleModal_{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
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
                            <td colspan="2"><strong>Информация о поручении:</strong></td>
                        </tr>

                        @if ($item->assign_type == 'role')
                            @if ($roleNamesByTask[$item->id] ?? false)
                                @php
                                    $roles = $roleNamesByTask[$item->id];
                                @endphp
                                <tr>
                                    <td><strong>Роли:</strong></td>
                                    <td>
                                        <div class="d-flex flex-wrap" style="max-width: 300px;">
                                            <!-- Set a max-width as needed -->
                                            @foreach ($roles as $role)
                                                <span class="badge bg-primary text-light p-2 m-1"
                                                    style="white-space: nowrap;">{{ $role }}</span>
                                            @endforeach
                                        </div>
                                        <span class="badge bg-secondary text-light p-2 m-1">{{ $roles->count() }} ролей
                                            назначено</span>
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td><strong>Роли:</strong></td>
                                    <td><span class="badge bg-secondary text-light p-2 m-1">Нет назначенных ролей</span>
                                    </td>
                                </tr>
                            @endif
                        @elseif($item->assign_type == 'custom')
                            @php
                                $users = $item->task_users; // Assuming 'task_users' is the relationship method
                            @endphp
                            <tr>
                                <td><strong>Пользователи:</strong></td>
                                <td>
                                    <div class="d-flex flex-wrap" style="max-width: 300px;">
                                        <!-- Set a max-width as needed -->
                                        @if ($users->isNotEmpty())
                                            @foreach ($users as $user)
                                                <span class="badge bg-primary text-light p-2 m-1"
                                                    style="white-space: nowrap;">{{ $user->name }}</span>
                                            @endforeach
                                    </div>
                                    <span class="badge bg-secondary text-light p-2 m-1">{{ $users->count() }}
                                        пользователей назначено</span>
                                @else
                                    <span class="badge bg-secondary text-light p-2 m-1">Нет назначенных
                                        пользователей</span>
                        @endif
                        </td>
                        </tr>
                        @endif

                        @foreach ([
        'Категория' => $item->category->name ?? '',
        'Автор' => $item->user->name,
        'Дата выдачи' => optional($item->issue_date)->format('d.m.Y'),
        'Срок выполнения (план)' => optional($item->planned_completion_date)->format('d.m.Y'),
        'Краткое название' => $item->short_title,
        'Состояние исполнения' => $item->execution_state,
        'Примечание' => $item->note,
        'Оповещение' => $item->notification,
        'Приоритет' => $item->priority,
        'Закрепленный файл' => $item->attached_file ? '<a href="' . Storage::url($item->attached_file) . '" target="_blank" class="text-decoration-none">Скачать</a>' : 'Нет',
    ] as $label => $value)
                            <tr>
                                <td><strong>{{ $label }}:</strong></td>
                                <td>{!! $value !!}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>
