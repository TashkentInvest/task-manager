@extends('layouts.admin')

@section('content')
    <h1 class="text-center">Қарорлар Рўйхати</h1>
    <a href="{{ route('qarorlarAdd') }}" class="btn btn-success mb-3">Янги Қарор Қўшиш</a>
    <table class="table table-striped table-bordered">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Уникал Код</th>
                <th>Қисқача Ном</th>
                <th>Ҳаракатлар</th>
            </tr>
        </thead>
        <tbody>
            @foreach($qarorlar as $qaror)
                <tr>
                    <td>{{ $qaror->id }}</td>
                    <td>{{ $qaror->unique_code }}</td>
                    <td>{{ $qaror->short_name }}</td>
                    <td>
                        <a href="{{ route('qarorlarShow', $qaror->id) }}" class="btn btn-info btn-sm">Кўриш</a>
                        <a href="{{ route('qarorlarEdit', $qaror->id) }}" class="btn btn-warning btn-sm">Таҳрирлаш</a>
                        <form action="{{ route('qarorlarDestroy', $qaror->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Қарорни ўчиришга ишончингиз комилми?')">Ўчириш</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
