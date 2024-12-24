@extends('layouts.admin')

@section('content')
    <style>
        /*=========================
          GLOBAL STYLES
        =========================*/
        body {
            background: #f0f2f5;
            /* Light gray background for contrast */
        }

        .container-fluid {
            margin-top: 20px;
        }

        .creative-gradient {
            background: linear-gradient(145deg, #ececec, #ffffff);
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
            padding: 25px 30px;
        }

        .rounded-custom {
            border-radius: 1rem !important;
        }

        .shadow-custom {
            box-shadow: 0 5px 12px rgba(0, 0, 0, 0.1);
        }

        /*=========================
          HEADINGS & TEXT
        =========================*/
        .title-highlight {
            background: linear-gradient(120deg, #3498db, #224abe);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 700;
        }

        .badge-status {
            padding: 0.4rem 0.6rem;
            border-radius: 0.75rem;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .badge-pending {
            background-color: #ffc107;
            color: #000;
        }

        .badge-success {
            background-color: #28a745 !important;
        }

        .badge-danger {
            background-color: #dc3545 !important;
        }

        .badge-warning {
            background-color: #ffc107 !important;
        }

        /*=========================
          LIST GROUP
        =========================*/
        .list-group-item {
            border: none;
            background-color: #fff;
            margin-bottom: 10px;
            padding: 15px 20px;
            transition: background-color 0.15s ease-in-out;
        }

        .list-group-item:hover {
            background-color: #f0f2f5;
        }

        /*=========================
          CUSTOM CARDS
        =========================*/
        .task-details {
            background-color: #ffffff;
            border-radius: 1rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
            padding: 20px;
            margin-bottom: 20px;
        }

        .task-details h4,
        .task-details h3 {
            margin-top: 20px;
            font-weight: 600;
        }

        .blockquote {
            margin: 1rem 0;
            padding: 0.75rem 1rem;
            background-color: #f9fbfc;
            border-left: 4px solid #224abe;
            border-radius: 0.5rem;
        }

        .blockquote p {
            margin-bottom: 0;
            font-style: italic;
            color: #666;
        }

        /*=========================
          BUTTONS
        =========================*/
        .btn-soft-danger {
            background-color: #fff3f5;
            color: #c82333;
            border: 1px solid #c82333;
        }

        .btn-soft-danger:hover {
            background-color: #c82333;
            color: #fff;
        }

        .btn-soft-success {
            background-color: #e8f5e9;
            color: #28a745;
            border: 1px solid #28a745;
        }

        .btn-soft-success:hover {
            background-color: #28a745;
            color: #fff;
        }

        .btn-soft-primary {
            background-color: #eef2fd;
            color: #224abe;
            border: 1px solid #224abe;
        }

        .btn-soft-primary:hover {
            background-color: #224abe;
            color: #fff;
        }

        /*=========================
          OTHER SMALL DETAILS
        =========================*/
        .ms-icon {
            margin-right: 6px;
        }
    </style>

    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-lg-12">
                <div class="card shadow-lg border-0 rounded">
                    <div class="card-body">
                        <br><br>
                        <div class="row">
                            <!-- LEFT COLUMN -->
                            <div class="col-7 mb-4">
                                <!-- Task Details Card -->
                                <div class="task-details p-4">
                                    <!-- Title / Creator -->
                                    <p class="h5 mb-4">
                                        <i class="bi bi-person-check text-primary ms-icon"></i>
                                        <strong class="text-primary">Буюрувчи:</strong>
                                        <span class="text-dark">{{ $item->user->name }}</span>
                                    </p>

                                    <!-- Assigned Employees -->
                                    <h4 class="text-success mb-3">
                                        <i class="bi bi-people-fill ms-icon"></i> Буюруқни бажарувчи
                                    </h4>
                                    @if ($item->task_users->isNotEmpty())
                                        <ul class="list-unstyled ms-4">
                                            @foreach ($item->task_users as $i)
                                                <li class="mb-3">
                                                    <span class="badge text-muted px-3 py-2">{{ $i->name }}</span><br>
                                                    <small class="text-muted">{{ $i->about }}</small>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-muted ms-4">Ижрочилар мавжуд эмас.</p>
                                    @endif

                                    <!-- Task Info -->
                                    <h4 class="text-primary mt-5">
                                        <i class="bi bi-info-circle-fill ms-icon"></i>
                                        Буюруқ ҳақида маълумот
                                    </h4>
                                    @php
                                        $remainingDays = $item->planned_completion_date
                                            ? now()->diffInDays($item->planned_completion_date, false)
                                            : 'N/A';
                                        $isRejected = !is_null($item->reject_time);
                                    @endphp

                                    <p class="mt-3">
                                        <strong class="text-primary">Берилган сана:</strong>
                                        <span class="text-dark">{{ $item->issue_date ?? 'Кўрсатилмаган' }}</span>
                                    </p>

                                    {{-- <p>
                                        <strong class="text-primary">Бажариш муддати:</strong>
                                        <span class="text-dark">
                                            {{ $item->planned_completion_date ?? 'Кўрсатилмаган' }}
                                        </span>
                                        @if (is_int($remainingDays))
                                            @if ($isRejected)
                                                <span class="badge bg-danger ms-3">
                                                    Муддат {{ abs($remainingDays) }} кун олдин тугади
                                                </span>
                                            @elseif ($remainingDays > 0)
                                                <span class="badge bg-success ms-3">
                                                    {{ $remainingDays }} кун қолди
                                                </span>
                                            @elseif ($remainingDays < 0)
                                                <span class="badge bg-danger ms-3">
                                                    {{ abs($remainingDays) }} кун кечиктирилди
                                                </span>
                                            @else
                                                <span class="badge bg-warning ms-3">
                                                    Муддат бугун
                                                </span>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary ms-3">Кўрсатилмаган</span>
                                        @endif
                                    </p> --}}
                                </div>

                                {{-- Admin Status Section --}}
                                @if ($item->order)
                                    @if ($item->order->checked_status == 2)
                                        <div class="mt-4 border p-3 rounded bg-white shadow-sm">
                                            <h3 class="text-danger mb-3">
                                                <i class="bi bi-x-circle-fill ms-icon"></i>
                                                Председатель правления статус: Восстановить по поручению
                                            </h3>
                                            <p class="card-text">
                                                <strong>Комментарий об восстановление:</strong>
                                            </p>
                                            <blockquote class="blockquote">
                                                <p class="mb-0">{{ $item->order->checked_comment }}</p>
                                            </blockquote>
                                            <p class="card-text mt-3">
                                                <strong>Дата восстановление:</strong>
                                                <span class="text-muted">
                                                    {{ $item->order->checked_time ?? '' }}
                                                </span>
                                            </p>
                                        </div>
                                    @elseif($item->order->checked_status == 1)
                                        <div class="mt-4 border p-3 rounded bg-white shadow-sm">
                                            <h3 class="text-success mb-3">
                                                <i class="bi bi-check-circle-fill ms-icon"></i>
                                                Председатель правления статус: Вазифа тасдиқланди
                                            </h3>
                                            <p class="card-text mt-3">
                                                <strong>Дата одобрения:</strong>
                                                <span class="text-muted">{{ $item->order->checked_time ?? '' }}</span>
                                            </p>
                                        </div>
                                    @endif

                                    {{-- Employee Rejection Comments --}}
                                    @if ($item->status->id != 4 && $item->status->name == 'XODIM_REJECT')
                                        <h3 class="mt-5">
                                            <i class="bi bi-person-fill-slash ms-icon"></i>
                                            Ходим статус
                                        </h3>
                                        <div class="mt-3 border p-3 rounded bg-white shadow-sm">
                                            <h5 class="text-danger mb-3">
                                                <i class="bi bi-arrow-counterclockwise ms-icon"></i>
                                                Восстановить по поручению
                                            </h5>
                                            <p class="card-text">
                                                <strong>Кто отклонил:</strong>
                                                <span class="text-warning">
                                                    {{ $item->order->user->name ?? 'Не указано' }}
                                                </span>
                                            </p>
                                            <p class="card-text">
                                                <strong>Комментарий об восстановление:</strong>
                                            </p>
                                            <blockquote class="blockquote">
                                                <p class="mb-0">{{ $item->reject_comment }}</p>
                                            </blockquote>
                                            @php
                                                $rejectFiles = $item->files->filter(function ($file) {
                                                    return file_exists(
                                                        public_path('porucheniya/reject/' . $file->file_name),
                                                    );
                                                });
                                            @endphp
                                            @if ($rejectFiles->count() > 0)
                                                <h5>Загруженные файлы:</h5>
                                                <ul class="list-group">
                                                    @foreach ($rejectFiles as $file)
                                                        <li
                                                            class="list-group-item d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <a href="{{ asset('porucheniya/reject/' . $file->file_name) }}"
                                                                    class="btn btn-soft-primary btn-sm" target="_blank">
                                                                    {{ $file->name }} — Посмотреть
                                                                </a>
                                                            </div>
                                                            <form action="{{ route('file.delete', $file->id) }}"
                                                                method="POST" style="display:inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-soft-danger btn-sm">
                                                                    Ўчириш
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <p>Нет загруженных файлов.</p>
                                            @endif
                                            <p class="card-text mt-3">
                                                <strong>Дата восстановление:</strong>
                                                <span class="text-muted">{{ $item->reject_time }}</span>
                                            </p>
                                        </div>
                                    @elseif($item->status->name == 'Completed')
                                        <div class="mt-4 border p-3 rounded bg-white shadow-sm">
                                            <h5 class="text-success mb-3">
                                                <i class="bi bi-check-circle-fill ms-icon"></i>
                                                Завершено
                                            </h5>
                                            <p class="card-text">
                                                <strong>Кто закончил:</strong>
                                                <span class="text-warning">
                                                    {{ $item->order->user->name ?? 'Не указано' }}
                                                </span>
                                            </p>
                                            <blockquote class="blockquote text-success">
                                                <p class="mb-0">Вазифа якунланди</p>
                                                <p class="mb-0">{{ $item->reject_comment }}</p>
                                            </blockquote>
                                            @php
                                                $completeFiles = $item->files->filter(function ($file) {
                                                    return file_exists(
                                                        public_path('porucheniya/complete/' . $file->file_name),
                                                    );
                                                });
                                            @endphp
                                            @if ($completeFiles->count() > 0)
                                                <h5>Загруженные файлы:</h5>
                                                <ul class="list-group">
                                                    @foreach ($completeFiles as $file)
                                                        <li
                                                            class="list-group-item d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <a href="{{ asset('porucheniya/complete/' . $file->file_name) }}"
                                                                    class="btn btn-soft-primary btn-sm" target="_blank">
                                                                    {{ $file->name }} — Посмотреть
                                                                </a>
                                                            </div>
                                                            <form action="{{ route('file.delete', $file->id) }}"
                                                                method="POST" style="display:inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-soft-danger btn-sm">
                                                                    Ўчириш
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <p>Нет загруженных файлов.</p>
                                            @endif
                                            <p class="card-text mt-3">
                                                <strong>Дата окончания:</strong>
                                                <span class="text-muted">{{ $item->reject_time }}</span>
                                            </p>
                                        </div>
                                    @endif
                                @endif

                                <!-- Document Info, if any -->
                                @if (isset($item->document))
                                    <div class="task-details border rounded shadow p-4 bg-white mt-4">
                                        <h4 class="text-primary mb-3">
                                            <i class="bi bi-file-earmark-text-fill ms-icon"></i>
                                            Ҳужжат ҳақида маълумот
                                        </h4>
                                        <p>
                                            <strong>Сарлавҳа:</strong>
                                            <span class="text-dark">{{ $item->document->title }}</span>
                                        </p>
                                        <p>
                                            <strong>Категория:</strong>
                                            <span class="text-dark">
                                                {{ $item->document->category->name ?? 'Категория кўрсатилмаган' }}
                                            </span>
                                        </p>
                                        <p>
                                            <strong>Ҳат рақами:</strong>
                                            <span class="text-dark">{{ $item->document->letter_number }}</span>
                                        </p>
                                        <p>
                                            <strong>Қабул қилинган сана:</strong>
                                            <span class="text-dark">{{ $item->document->received_date }}</span>
                                        </p>

                                        <h4 class="text-primary mt-4 mb-2">
                                            <i class="bi bi-paperclip ms-icon"></i>
                                            Қўшимча файллар
                                        </h4>
                                        @if ($item->document->files->count())
                                            <ul class="list-group mt-2">
                                                @foreach ($item->document->files as $file)
                                                    <li class="list-group-item">
                                                        <a href="{{ Storage::url($file->file_path) }}" target="_blank"
                                                            class="text-decoration-none">
                                                            {{ basename($file->file_path) }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p class="text-muted mt-2">Қўшимча файллар мавжуд эмас.</p>
                                        @endif
                                    </div>
                                @endif

                             

                                <!-- Order Action History -->
                                <h4 class="text-primary mt-5">
                                    <i class="bi bi-clock-history ms-icon"></i>
                                    Буйруқ ҳаракатлари тарихи
                                </h4>
                                @php
                                    $order = \App\Models\Order::where('task_id', $item->id)->first();
                                @endphp

                                @if ($order && $order->actions->count() > 0)
                                    <ul class="list-group list-group-flush mt-3">
                                        @foreach ($order->actions as $action)
                                            <li class="list-group-item">
                                                <strong>{{ $action->user->name }}</strong><br>
                                                <span class="text-muted">
                                                    {{ $action->created_at->format('d.m.Y H:i') }}
                                                </span><br>
                                                <em>{{ $action->action }}</em>
                                                @if ($action->comment)
                                                    — <small>{{ $action->comment }}</small>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted mt-3">Ҳеч қандай ҳаракат қайд этилмаган.</p>
                                @endif

                                <!-- Order Additional Info -->
                             
                            </div><!-- End col-7 -->

                            <!-- RIGHT COLUMN - 'Фишка' -->

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
                                <a href="{{ route('taskEdit', $item->id) }}" class="btn btn-warning mx-2">Ўзгартириш</a>
                                <form action="{{ route('orders.admin_confirm') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="task_id" value="{{ $item->id }}">
                                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                                    <button type="submit" class="btn btn-success">Тасдиқлаш</button>
                                </form>
                                <button class="btn btn-primary mx-2" data-bs-toggle="modal"
                                    data-bs-target="#rejectModal">Қайта тиклаш</button>
                            @else
                                @if ($item->status->name == 'Active' && auth()->user()->roles[0]->name != 'Super Admin')
                                    <form action="{{ route('orders.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="task_id" value="{{ $item->id }}">
                                        <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                                        <button type="submit" class="btn btn-success">
                                            Қабул қилиш <i class="bx bxs-badge-check"></i>
                                        </button>
                                    </form>
                                @endif
                            @endif

                            @if (auth()->user()->roles[0]->name != 'Super Admin' && $item->status->name != 'Active')
                                <button class="btn btn-success mx-2" data-bs-toggle="modal"
                                    data-bs-target="#finishModalEmp">
                                    Тамомлаш
                                </button>

                                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModalEmp">
                                    Рад этиш
                                </button>
                            @endif
                        </div>
                    </div>

                    @if (auth()->user()->roles[0]->name == 'Super Admin')
                        <div class="card-body">
                            <form action="{{ route('taskDestroy', $item->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="bx bxs-trash"></i> Ўчириш
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Admin Reject Modal (Возстановить) -->
        <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="rejectModalLabel">Қайта тиклаш (ID: {{ $item->id }})</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('orders.admin_reject') }}" method="POST">
                            @csrf
                            <input type="hidden" name="task_id" value="{{ $item->id }}">
                            <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                            <div class="mb-3">
                                <label for="checked_comment" class="form-label">Қайта тиклаш изоҳи</label>
                                <textarea class="form-control" id="checked_comment" name="checked_comment" rows="3" required></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Бекор
                                    қилиш</button>
                                <button type="submit" class="btn btn-primary">Қайта тиклаш</button>
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
                        <h5 class="modal-title" id="rejectModalEmpLabel">Рад этиш (ID: {{ $item->id }})</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('orders.reject') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="task_id" value="{{ $item->id }}">
                            <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                            <div class="mb-3">
                                <label for="reject_comment" class="form-label">Рад этиш изоҳи</label>
                                <textarea class="form-control" id="reject_comment" name="reject_comment" rows="3" required>{{ old('reject_comment', $item->reject_comment) }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="attached_file" class="form-label">Файл юклаш</label>
                                <input type="file" class="form-control" id="attached_file" name="attached_file[]"
                                    multiple>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Бекор
                                    қилиш</button>
                                <button type="submit" class="btn btn-danger">Рад этиш</button>
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
                        <h5 class="modal-title" id="finishModalEmpLabel">Тамомлаш (ID: {{ $item->id }})</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('orders.complete') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="task_id" value="{{ $item->id }}">
                            <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                            <div class="mb-3">
                                <label for="reject_comment" class="form-label">Тамомлаш изоҳи</label>
                                <textarea class="form-control" id="reject_comment" name="reject_comment" rows="3" required>{{ old('reject_comment', $item->reject_comment) }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="attached_file" class="form-label">Файл юклаш</label>
                                <input type="file" class="form-control" id="attached_file" name="attached_file[]"
                                    multiple>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Бекор
                                    қилиш</button>
                                <button type="submit" class="btn btn-success">Тамомлаш</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
