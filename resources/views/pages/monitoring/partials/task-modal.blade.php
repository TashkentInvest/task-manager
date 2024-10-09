<div class="modal fade" id="exampleModal_{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                        @foreach ([
                            'Категория' => $item->category->name,
                            'Автор' => $item->user->name,
                            'Исполнитель' => $item->executor,
                            'Со исполнителем' => $item->co_executor,
                            'Дата выдачи' => optional($item->issue_date)->format('d.m.Y'),
                            'Срок выполнения (план)' => optional($item->planned_completion_date)->format('d.m.Y'),
                            'Статус выполнения (факт)' => $item->actual_status,
                            'Состояние исполнения' => $item->execution_state,
                            'Примечание' => $item->note,
                            'Оповещение' => $item->notification,
                            'Приоритет' => $item->priority,
                            'Тип документа' => $item->document_type,
                            'Тип задачи' => $item->type_request == '2' ? 'Дополнительное поручение' : ($item->type_request == '1' ? 'Позднее поручение' : 'Нет'),
                            'Закрепленный файл' => $item->attached_file ? '<a href="'.Storage::url($item->attached_file).'" target="_blank">Скачать</a>' : 'Нет',
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
