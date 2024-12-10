@extends('layouts.admin')

@section('content')
    <h1 class="text-center">Қарорлар Рўйхати</h1>
    <a href="{{ route('qarorlarAdd') }}" class="btn btn-success mb-3">Янги Қарор Қўшиш</a>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Файл</th>
                    <th>Кузатув кенгашининг қарори</th>
                    <th>Уникал Код</th>
                    <th>Қарор сана</th>
                    <th>Нархи</th>
                    <th class="styled_styles">Қисқача Ном</th>
                    <th>Яратилган сана</th>
                    <th>Ҳаракатлар</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($qarorlar as $qaror)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <a target="_blank" class="btn btn-primary btn-sm"
                                href="storage/{{ $qaror->files->first()->file_path ?? '' }}">Кориш</a>
                        </td>
                        <td>
                            @if ($qaror->files->isNotEmpty())
                                <ul>
                                    @foreach ($qaror->files->whereIn('file_path', 'like', '%Кузатув кенгашининг қарори%') as $file)
                                        <li>
                                            <a target="_blank" class="btn btn-primary btn-sm"
                                                href="{{ asset('storage/' . $file->file_path) }}">
                                                Кориш
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <span>Файл мавжуд эмас</span>
                            @endif
                        </td>
                        <td>{{ $qaror->unique_code }}</td>
                        <td>{{ $qaror->sana }}</td>
                        <td>{{ number_format($qaror->amount ?? 0, 2, '.', ' ') }}</td>
                        <td class="styled_styles">{{ $qaror->short_name }}</td>
                        <td>{{ $qaror->created_at }}</td>
                        <td>
                            <a href="{{ route('qarorlarShow', $qaror->id) }}" class="btn btn-info btn-sm">Кўриш</a>
                            <a href="{{ route('qarorlarEdit', $qaror->id) }}" class="btn btn-warning btn-sm">Таҳрирлаш</a>
                            <form action="{{ route('qarorlarDestroy', $qaror->id) }}" method="POST"
                                style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Қарорни ўчиришга ишончингиз комилми?')">Ўчириш</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <style>
        /* Custom style for the 'Қисқача Ном' column */
        .styled_styles {
            width: 300px;
            max-width: 300px;
            word-wrap: break-word;
            word-break: break-word;
            white-space: normal;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        @media (max-width: 768px) {
            .styled_styles {
                width: 100% !important;
                max-width: 100% !important;
                word-break: normal !important;

            }
        }
    </style>
@endsection
