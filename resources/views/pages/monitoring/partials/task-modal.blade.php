<div class="modal fade" id="exampleModal_{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Оставшееся поручение ID: {{ $item->id }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <div class="modal-body">
                <table class="table table-striped">
                    <tbody>
                        <tr class="text-center">
                            <td colspan="2" class="h5"><strong>Информация о поручении</strong></td>
                        </tr>

                        @if ($item->assign_type == 'role')
                            <tr>
                                <td><strong>Роли:</strong></td>
                                <td>
                                    <div class="d-flex flex-wrap" style="max-width: 300px;">
                                        @if (!empty($roleNamesByTask[$item->id]))
                                            @foreach ($roleNamesByTask[$item->id] as $role)
                                                <span class="badge bg-primary text-light p-2 m-1" style="white-space: nowrap;">{{ $role }}</span>
                                            @endforeach
                                            <span class="badge bg-secondary text-light p-2 m-1">{{ count($roleNamesByTask[$item->id]) }} ролей назначено</span>
                                        @else
                                            <span class="badge bg-secondary text-light p-2 m-1">Нет назначенных ролей</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @elseif($item->assign_type == 'custom')
                            <tr>
                                <td><strong>Пользователи:</strong></td>
                                <td>
                                    <div class="d-flex flex-wrap" style="max-width: 300px;">
                                        @if ($item->task_users->isNotEmpty())
                                            @foreach ($item->task_users as $user)
                                                <span class="badge bg-primary text-light p-2 m-1" style="white-space: nowrap;">{{ $user->name }}</span>
                                            @endforeach
                                            <span class="badge bg-secondary text-light p-2 m-1">{{ $item->task_users->count() }} пользователей назначено</span>
                                        @else
                                            <span class="badge bg-secondary text-light p-2 m-1">Нет назначенных пользователей</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endif

                        @foreach ([
                            'Категория' => $item->category->name ?? 'Нет',
                            'Автор' => $item->user->name,
                            'Дата выдачи' => $item->issue_date ?? '',
                            'Срок выполнения (план)' => $item->planned_completion_date ?? '',
                            'Краткое название' => $item->short_title,
                            'Состояние исполнения' => $item->status->name ?? '',
                            'Примечание' => $item->note ?? 'Нет',
                            'Закрепленный файл' => $item->attached_file ? '<a href="' . Storage::url($item->attached_file) . '" target="_blank" class="text-decoration-none">Скачать</a>' : 'Нет',
                        ] as $label => $value)
                            <tr>
                                <td><strong>{{ $label }}:</strong></td>
                                <td>{!! $value !!}</td>
                            </tr>
                        @endforeach

                        @if ($item->reject_comment)
                            <div class="mt-4 border p-3 rounded bg-light">
                                <h5 class="text-danger"><i class="bi bi-exclamation-circle"></i> Отказ по поручению</h5>
                                @if (isset($item->order))
                                    <p class="card-text"><strong>Кто отклонил:</strong> <span class="text-warning">{{ $item->order->user->name }}</span></p>
                                @endif
                                <p class="card-text"><strong>Комментарий об отказе:</strong></p>
                                <blockquote class="blockquote">
                                    <p class="mb-0">{{ $item->reject_comment }}</p>
                                </blockquote>


                                <p class="card-text mt-3"><strong>Дата отказа:</strong> <span class="text-muted">{{ $item->reject_time }}</span></p>
                            </div>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>
