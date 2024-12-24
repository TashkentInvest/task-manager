@extends('layouts.admin')

@section('content')
    <h1 class="mb-4 text-center text-primary">Ҳужжатлар Рўйхати</h1>

    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('documents.create') }}" class="btn btn-success">
            <i class="fas fa-plus-circle"></i> Янги Ҳужжат Яратиш
        </a>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
    </div>

    <!-- Filter Section -->
    <form method="GET" action="{{ route('documents.index') }}" class="mb-3">
        <div class="input-group">
            <select name="status_type" class="form-select">
                <option value="">Ҳужжат Турини Танланг</option>
                <option value="kiruvchi" {{ request('status_type') == 'kiruvchi' ? 'selected' : '' }}>Кирувчи</option>
                <option value="chiquvchi" {{ request('status_type') == 'chiquvchi' ? 'selected' : '' }}>Чиқувчи</option>
            </select>
            <button type="submit" class="btn btn-primary">Филтрлаш</button>
        </div>
    </form>

    <div class="card">
        <div class="card-body">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr class="table-info">
                        <th>ID</th>
                        <th>Ҳужжат Тури</th>
                        <th>Асосий Категория</th>
                        <th>Қўшимча Категория</th>
                        <th>Вазирлик</th>
                        <th>Хат №</th>
                        <th>Қабул Қилинган Санаси</th>
                        <th>Сарлавха</th>
                        <th>Амалиётлар</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documents as $document)
                        <tr>
                            <td>{{ $document->id }}</td>
                            <td>{{ $document->status_type }}</td>
                            <td>
                                {{ $document->category ? $document->category->parent->name ?? $document->category->name : 'Йўқ' }}
                            </td>
                            <td>
                                {{ $document->category && $document->category->parent ? $document->category->name : 'Йўқ' }}
                            </td>
                            <td>
                                {{ $document->ministry ? $document->ministry->name : 'Йўқ' }}
                            </td>
                            <td>{{ $document->letter_number }}</td>
                            <td>{{ $document->received_date }}</td>
                            <td>{{ $document->title }}</td>
                            <td class="d-flex">
                                <a href="{{ route('documents.show', $document->id) }}" class="btn btn-info btn-sm mr-2">
                                    <i class="fas fa-eye"></i> Кўрсатиш
                                </a>
                                <a href="{{ route('documents.edit', $document->id) }}" class="btn btn-warning btn-sm mr-2">
                                    <i class="fas fa-edit"></i> Таҳрир Қилиш
                                </a>
                                <form action="{{ route('documents.destroy', $document->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Ҳужжатни ўчиришга ишонч ҳосил қилдингизми?')">
                                        <i class="fas fa-trash-alt"></i> Ўчириш
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted">Ҳужжатлар топилмади.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            {{-- <div class="d-flex justify-content-center mt-4">
                {{ $documents->links('vendor.pagination.bootstrap-4') }}
            </div> --}}
        </div>
    </div>
@endsection
