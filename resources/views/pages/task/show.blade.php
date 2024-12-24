@extends('layouts.admin')

@section('content')
    <style>
        /* General Styles */
        .card {
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transition: transform 0.2s ease-in-out;
        }

        .card:hover {
            transform: scale(1.02);
        }

        .card-header {
            background: linear-gradient(120deg, #4e73df, #224abe);
            color: #fff;
            font-weight: bold;
            font-size: 1.5rem;
            padding: 20px;
            border-radius: 12px 12px 0 0;
        }

        .card-body {
            padding: 25px;
            background-color: #f8f9fc;
        }

        .card-title {
            font-size: 1.3rem;
            color: #4e73df;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .btn {
            border-radius: 6px;
            font-size: 1rem;
            padding: 10px 18px;
            transition: background-color 0.3s;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .badge {
            font-size: 0.95rem;
            padding: 0.5rem 0.7rem;
            border-radius: 10px;
        }

        .modal-header {
            background: linear-gradient(120deg, #4e73df, #224abe);
            color: white;
        }

        .modal-footer {
            background-color: #f8f9fc;
        }

        .list-group-item {
            border: none;
            background-color: #f8f9fc;
            margin-bottom: 10px;
            padding: 15px 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .list-group-item:hover {
            background-color: #e2e6ea;
        }

        .blockquote {
            font-style: italic;
            color: #6c757d;
            border-left: 4px solid #4e73df;
            padding-left: 15px;
            margin: 10px 0;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 8px;
            font-size: 0.9rem;
            color: white;
            font-weight: bold;
        }

        .status-active {
            background-color: #17a2b8;
        }

        .status-completed {
            background-color: #28a745;
        }

        .status-rejected {
            background-color: #dc3545;
        }

        .status-pending {
            background-color: #ffc107;
        }

        .task-details {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .task-details h5 {
            margin-bottom: 15px;
            font-weight: bold;
            color: #4e73df;
        }
    </style>

    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-lg-12">
                <div class="card shadow-lg border-0 rounded">


                    <div class="card-body">
                        {{-- Task Details --}}

                        <br><br>
                        <div class="row">
                            <div class="col-7">
                                <div class="task-details border rounded shadow p-4 bg-light">
                                    <p class="h5">
                                        <strong class="text-primary">Поручитель:</strong>
                                        <span class="text-dark">{{ $item->user->name }}</span>
                                    </p>
                            
                                    <h3 class="text-success my-4">Исполнитель поручения</h3>
                                    @if ($item->task_users->isNotEmpty())
                                        <ul class="list-unstyled">
                                            @foreach ($item->task_users as $i)
                                                <li class="mb-3">
                                                    <span class="badge text-muted px-3 py-2">{{ $i->name }}</span><br>
                                                    <p class="text-muted mt-1 mb-0">{{ $i->about }}</p>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-muted">Нет исполнителей.</p>
                                    @endif
                            
                                    <h4 class="text-primary mt-5">Закрепленный файл</h4>
                                    @php
                                        $initialFiles = $item->files->filter(function ($file) {
                                            return file_exists(public_path('porucheniya/' . $file->file_name));
                                        });
                                    @endphp
                            
                                    @if ($initialFiles->count() > 0)
                                        <ul class="list-group list-group-flush mt-3">
                                            @foreach ($initialFiles as $file)
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span>
                                                        <a href="{{ asset('porucheniya/' . $file->file_name) }}" target="_blank" class="text-decoration-none">
                                                            <span class="badge bg-primary text-white">{{ $file->name }}</span>
                                                        </a>
                                                    </span>
                                                    @if (auth()->user()->roles[0]->name == 'Super Admin')
                                                        <form action="{{ route('file.delete', $file->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger">Удалить</button>
                                                        </form>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-muted mt-3">Нет загруженных файлов.</p>
                                    @endif
                            
                                    <h4 class="text-primary mt-5">Информация о поручении</h4>
                                    @php
                                        $remainingDays = $item->planned_completion_date ? now()->diffInDays($item->planned_completion_date, false) : 'N/A';
                                        $isRejected = !is_null($item->reject_time);
                                    @endphp
                            
                                    <p><strong class="text-primary">Дата выдачи:</strong> <span class="text-dark">{{ $item->issue_date ?? 'Не указана' }}</span></p>
                                    <p>
                                        <strong class="text-primary">Срок выполнения:</strong> <span class="text-dark">{{ $item->planned_completion_date ?? 'Не указана' }}</span>
                                        @if (is_int($remainingDays))
                                            @if ($isRejected)
                                                <span class="badge bg-danger ms-3">Срок завершения был {{ abs($remainingDays) }} дней назад</span>
                                            @elseif ($remainingDays > 0)
                                                <span class="badge bg-success ms-3">{{ $remainingDays }} дней осталось</span>
                                            @elseif ($remainingDays < 0)
                                                <span class="badge bg-danger ms-3">Просрочено на {{ abs($remainingDays) }} дней</span>
                                            @else
                                                <span class="badge bg-warning ms-3">Срок сегодня</span>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary ms-3">N/A</span>
                                        @endif
                                    </p>
                                </div>
                            
                                @if (isset($item->document))
                                    <div class="task-details border rounded shadow p-4 bg-white mt-4">
                                        <h4 class="text-primary">Информация о документе</h4>
                                        <p><strong>Сарлавха:</strong> <span class="text-dark">{{ $item->document->title }}</span></p>
                                        <p><strong>Категория:</strong> <span class="text-dark">{{ $item->document->category->name ?? 'Категория танланмаган' }}</span></p>
                                        <p><strong>Хат Рақами:</strong> <span class="text-dark">{{ $item->document->letter_number }}</span></p>
                                        <p><strong>Қабул Қилинган Санаси:</strong> <span class="text-dark">{{ $item->document->received_date }}</span></p>
                            
                                        <h4 class="text-primary mt-4">Қўшимча Файллар</h4>
                                        @if ($item->document->files->count())
                                            <ul class="list-group mt-2">
                                                @foreach ($item->document->files as $file)
                                                    <li class="list-group-item">
                                                        <a href="{{ Storage::url($file->file_path) }}" target="_blank" class="text-decoration-none">
                                                            {{ basename($file->file_path) }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p class="text-muted mt-2">Файллар қўшилмаган.</p>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            

                            <div class="col-5 ">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>фишка</h5>
                                    </div>
                                    <div class="card-body pc-component">
                                        <html>

                                        <head>
                                            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
                                            <meta name="Generator" content="Microsoft Word 15 (filtered)">

                                            <style>
                                                /* Font Definitions */
                                                @font-face {
                                                    font-family: "Cambria Math";
                                                    panose-1: 2 4 5 3 5 4 6 3 2 4;
                                                }

                                                @font-face {
                                                    font-family: Calibri;
                                                    panose-1: 2 15 5 2 2 2 4 3 2 4;
                                                }

                                                /* Style Definitions */
                                                p.MsoNormal,
                                                li.MsoNormal,
                                                div.MsoNormal {
                                                    margin-top: 0cm;
                                                    margin-right: 0cm;
                                                    margin-bottom: 10.0pt;
                                                    margin-left: 0cm;
                                                    line-height: 115%;
                                                    font-size: 11.0pt;
                                                    font-family: "Calibri", sans-serif;
                                                }

                                                .MsoChpDefault {
                                                    font-family: "Calibri", sans-serif;
                                                }

                                                .MsoPapDefault {
                                                    margin-bottom: 8.0pt;
                                                    line-height: 107%;
                                                }

                                                @page WordSection1 {
                                                    size: 595.3pt 841.9pt;
                                                    margin: 2.0cm 42.5pt 2.0cm 3.0cm;
                                                }

                                                div.WordSection1 {
                                                    page: WordSection1;
                                                }
                                            </style>

                                        </head>

                                        <body lang="RU">

                                            <div class="WordSection1">

                                                <table class="MsoTableGrid" border="1" cellspacing="0"
                                                    cellpadding="0" width="100%" style="width:100%; none">
                                                    <tr style="height:482.1pt">
                                                        <td width="737" valign="top"
                                                            style="width:100%; padding:20px">
                                                            <div align="center">
                                                                <table class="MsoNormalTable" border="0"
                                                                    cellspacing="0" cellpadding="0" align="center"
                                                                    style="border-collapse:collapse">
                                                                    <tr style="height:12.9pt">
                                                                        <td
                                                                            style="width: 100%; text-align: center; vertical-align: middle;">
                                                                            <p class="MsoNormal" align="center">
                                                                                <img style="width: 200px"
                                                                                    src="https://toshkentinvest.uz/assets/frontend/tild6238-3031-4265-a564-343037346231/tic_logo_blue.png"
                                                                                    alt=""> <br><br>
                                                                                <b><span lang="EN-US"
                                                                                        style="line-height:115%; font-family: 'Times New Roman',serif;">“TOSHKENT
                                                                                        INVEST KOMPANIYASI”</span></b>
                                                                            </p>
                                                                            <p class="MsoNormal" align="center"
                                                                                style="margin-top:6.0pt; margin-right:0cm; margin-bottom:0cm; margin-left:0cm; text-align:center;">
                                                                                <b><span lang="EN-US"
                                                                                        style="line-height:115%; font-family: 'Times New Roman',serif;">AKSIYADORLIK
                                                                                        JAMIYATI</span></b>
                                                                            </p>
                                                                        </td>
                                                                    </tr>
                                                                    {{-- <tr style="height:12.9pt">
                                                                        <td width="348"
                                                                            style="width:261.25pt; border:none; border-bottom:double windowtext 6.0pt; padding:0cm 0cm 0cm 1.5pt; height:12.9pt; text-align:center; vertical-align:middle;">
                                                                            <p class="MsoNormal" align="center"
                                                                                style="margin-top:6.0pt; margin-right:0cm; margin-bottom:0cm; margin-left:0cm; text-align:center;">
                                                                                <b><span lang="EN-US"
                                                                                        style="line-height:115%; font-family: 'Times New Roman',serif;">BOSHQARUV
                                                                                        RAISI VAZIFASINI BAJARUVCHI</span></b>
                                                                            </p>
                                                                        </td>
                                                                    </tr> --}}
                                                                </table>
                                                            </div>
                                                            <p class="MsoNormal" align="center"
                                                                style="text-align:center">
                                                                <b><span lang="EN-US"
                                                                        style="font-size:12.0pt;line-height:115%;font-family: 'DejaVu Sans', sans-serif;">&nbsp;</span></b>
                                                            </p>
                                                            <p class="MsoNormal">
                                                                <b>Ma’sullar:</b>
                                                                @if ($item->task_users->isNotEmpty())
                                                                    <ul>
                                                                        @foreach ($item->task_users as $i)
                                                                            <li><b>{{ $i->name ?? '' }}</b> —
                                                                                {{ $i->about ?? '' }}</li>
                                                                        @endforeach
                                                                    </ul>
                                                                @endif
                                                            </p>
                                                            <p class="MsoNormal" align="center"
                                                                style="text-align:center">
                                                                <span lang="EN-US"
                                                                    style="font-size:10.0pt;line-height:115%;font-family: 'DejaVu Sans', sans-serif; color:black">&nbsp;</span>
                                                            </p>



                                                            <p class="MsoNormal"
                                                                style="text-align:justify;text-indent:15.65pt">
                                                                <b>
                                                                    <span lang="EN-US"
                                                                        style="font-size:10.0pt;line-height:115%;font-family: 'DejaVu Sans', sans-serif; color:black;">
                                                                        Muddati: {{ $item->planned_completion_date->day }}
                                                                        {{ $monthNames[$item->planned_completion_date->month - 1] }}
                                                                        {{ $item->planned_completion_date->year }}

                                                                        {{-- {{$item->planned_completion_date->format('d.m.Y') ?? ''}} |  --}}
                                                                    </span>
                                                                </b>
                                                            </p>


                                                            </p>
                                                            <p class="MsoNormal"
                                                                style="text-align:justify;text-indent:15.65pt"><span
                                                                    lang="EN-US"
                                                                    style="font-size:10.0pt;line-height:115%;font-family: 'DejaVu Sans', sans-serif; color:black">&nbsp;</span>
                                                            </p>
                                                            <p class="MsoNormal"
                                                                style="text-align:justify;text-indent:15.65pt;line-height:150%">
                                                                <span lang="EN-US"
                                                                    style="font-size:12.0pt;line-height:150%;font-family: 'DejaVu Sans', sans-serif;color:black">&nbsp;</span>
                                                            </p>
                                                            <p class="MsoNormal"
                                                                style="text-align:justify;text-indent:15.65pt;line-height:150%">
                                                                <span lang="EN-US"
                                                                    style="font-size:12.0pt;line-height:150%;font-family: 'DejaVu Sans', sans-serif;color:black;">{{ $item->note }}
                                                                </span><span lang="UZ-CYR"
                                                                    style="font-size:12.0pt; line-height:150%; font-family: 'DejaVu Sans', sans-serif;color:black;">
                                                            </p>
                                                            <p class="MsoNormal"
                                                                style="text-align:justify;text-indent:15.65pt"><span
                                                                    lang="EN-US"
                                                                    style="font-size:12.0pt;line-height:115%;font-family: 'DejaVu Sans', sans-serif;">&nbsp;</span>
                                                            </p>
                                                            <p class="MsoNormal"
                                                                style="text-align:justify;text-indent:15.65pt"><span
                                                                    lang="UZ-CYR"
                                                                    style="font-size:12.0pt;line-height:115%;font-family: 'DejaVu Sans', sans-serif;">&nbsp;</span>
                                                            </p>
                                                            <p class="MsoNormal"
                                                                style="font-size:12.0pt; line-height:115%; font-family:'Times New Roman',serif; margin: 0;">
                                                                <span style="float: left;"><b>Boshqaruv raisi
                                                                        v.b.</b></span>
                                                                <span style="float: right;"><b>B.&nbsp;Shakirov</b></span>
                                                            </p>



                                                            <p class="MsoNormal"
                                                                style="margin-right:15.75pt;text-indent:15.65pt"><b><span
                                                                        lang="EN-US"
                                                                        style="font-size:12.0pt;line-height:115%;font-family: 'DejaVu Sans', sans-serif;">&nbsp;</span></b>
                                                            </p>
                                                            <p class="MsoNormal"
                                                                style="margin-right:15.75pt;text-indent:15.65pt"><b><span
                                                                        lang="EN-US"
                                                                        style="font-size:12.0pt;line-height:115%;font-family: 'DejaVu Sans', sans-serif;">&nbsp;</span></b>
                                                            </p>
                                                            <p class="MsoNormal" style="margin-right:15.75pt"><i><span
                                                                        lang="EN-US"
                                                                        style="font-size:12.0pt;line-height:115%;font-family: 'DejaVu Sans', sans-serif;">{{ $item->created_at->day }}
                                                                        {{ $monthNames[$item->created_at->month - 1] }}
                                                                        {{ $item->created_at->year }}</span></i><i>
                                                                    <br>
                                                                    <span lang="EN-US"
                                                                        style="font-size:12.0pt;line-height:115%;font-family:'Times New Roman',serif"><span
                                                                            style="">{{ $item->id + 26 }}-son</span></span></i>
                                                            </p>
                                                        </td>
                                                    </tr>
                                                </table>

                                                <p class="MsoNormal"><span lang="EN-US">&nbsp;</span></p>

                                            </div>

                                        </body>

                                        </html>

                                    </div>
                                </div>
                            </div>


                        </div>





                        {{-- Action Buttons --}}
                        <div class="d-flex justify-content-end mt-4">
                            @if (auth()->user()->roles[0]->name == 'Super Admin' && $item->status->name != 'Active')
                                <a href="{{ route('taskEdit', $item->id) }}"
                                    class="btn btn-warning mx-2">Редактировать</a>
                                <form action="{{ route('orders.admin_confirm') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="task_id" value="{{ $item->id }}">
                                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                                    <button type="submit" class="btn btn-success">Принят</button>
                                </form>
                                <button class="btn btn-primary mx-2" data-bs-toggle="modal"
                                    data-bs-target="#rejectModal">Восстановить</button>
                            @else
                                @if ($item->status->name == 'Active' && auth()->user()->roles[0]->name != 'Super Admin')
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
                            @if (auth()->user()->roles[0]->name != 'Super Admin' && $item->status->name != 'Active')
                                <button class="btn btn-success mx-2" data-bs-toggle="modal"
                                    data-bs-target="#finishModalEmp">Завершить</button>

                                <button class="btn btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#rejectModalEmp">Отказ</button>
                            @endif
                        </div>
                    </div>

                    @if (auth()->user()->roles[0]->name == 'Super Admin')
                        <div class="card-body">
                            <form action="{{ route('taskDestroy', $item->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="bx bxs-trash"></i> Удалить
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Admin Reject Modal -->
        <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="rejectModalLabel">Восстановить по поручению ID: {{ $item->id }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('orders.admin_reject') }}" method="POST">
                            @csrf
                            <input type="hidden" name="task_id" value="{{ $item->id }}">
                            <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                            <div class="mb-3">
                                <label for="checked_comment" class="form-label">Комментарий об восстановление</label>
                                <textarea class="form-control" id="checked_comment" name="checked_comment" rows="3" required></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Отменить</button>
                                <button type="submit" class="btn btn-primary">Восстановить</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Employee Reject Modal -->
        <div class="modal fade" id="rejectModalEmp" tabindex="-1" aria-labelledby="rejectModalEmpLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="rejectModalEmpLabel">Отказ по поручению ID: {{ $item->id }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('orders.reject') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="task_id" value="{{ $item->id }}">
                            <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                            <div class="mb-3">
                                <label for="reject_comment" class="form-label">Комментарий об Отказе</label>
                                <textarea class="form-control" id="reject_comment" name="reject_comment" rows="3" required>{{ old('reject_comment', $item->reject_comment) }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="attached_file" class="form-label">Загрузить файл</label>
                                <input type="file" class="form-control" id="attached_file" name="attached_file[]"
                                    multiple>
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

        <!-- Employee Finish Modal -->
        <div class="modal fade" id="finishModalEmp" tabindex="-1" aria-labelledby="finishModalEmpLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="finishModalEmpLabel">Завершить поручение ID: {{ $item->id }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('orders.complete') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="task_id" value="{{ $item->id }}">
                            <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                            <div class="mb-3">
                                <label for="reject_comment" class="form-label">Комментарий об завершении</label>
                                <textarea class="form-control" id="reject_comment" name="reject_comment" rows="3" required> {{ old('reject_comment', $item->reject_comment) }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="attached_file" class="form-label">Загрузить файл</label>
                                <input type="file" class="form-control" id="attached_file" name="attached_file[]"
                                    multiple>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Отменить</button>
                                <button type="submit" class="btn btn-success">Завершить</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
