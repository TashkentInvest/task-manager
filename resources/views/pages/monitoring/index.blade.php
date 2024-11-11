@extends('layouts.admin')

@section('content')
    <div class="row mb-4">
        <div class="col-lg-12 col-12">
            <div class="card shadow-sm border-0 rounded">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="text-black text-5">Оставшиеся поручения</h3>
                    <div>
                        <!-- Filter Button -->
                        <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#filterModal">
                            <i class="fas fa-filter"></i> Фильтры
                        </button>
                        @can('left-request.add')
                            <a href="{{ route('taskAdd') }}" class="btn btn-success">
                                <span class="fas fa-plus-circle"></span> Добавить
                            </a>
                        @endcan
                    </div>
                </div>

                <!-- Filter Modal Start -->
                <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <form method="GET" action="{{ route('monitoringIndex') }}">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="filterModalLabel">Фильтры</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <!-- Role Filter -->
                                        <div class="col-md-6">
                                            <label for="role" class="form-label">Роль</label>
                                            <select name="role" id="role" class="form-select">
                                                <option value="">Все</option>
                                                @foreach($roles as $role)
                                                    <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>
                                                        {{ $role->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <!-- User Filter -->
                                        <div class="col-md-6">
                                            <label for="user" class="form-label">Пользователь</label>
                                            <select name="user" id="user" class="form-select">
                                                <option value="">Все</option>
                                                @foreach($users as $filterUser)
                                                    <option value="{{ $filterUser->id }}" {{ request('user') == $filterUser->id ? 'selected' : '' }}>
                                                        {{ $filterUser->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <!-- Status Filter -->
                                        <div class="col-md-6">
                                            <label for="status" class="form-label">Статус</label>
                                            <select name="status" id="status" class="form-select">
                                                <option value="">Все</option>
                                                @foreach($taskStatuses as $status)
                                                    <option value="{{ $status->id }}" {{ request('status') == $status->id ? 'selected' : '' }}>
                                                        {{-- {{ $status->getColor() }} --}}
                                                        {{ $status->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <!-- Date From Filter -->
                                        <div class="col-md-3">
                                            <label for="date_from" class="form-label">Дата от</label>
                                            <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
                                        </div>
                                        <!-- Date To Filter -->
                                        <div class="col-md-3">
                                            <label for="date_to" class="form-label">Дата до</label>
                                            <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <!-- Search Filter -->
                                        <div class="col-md-12">
                                            <label for="search" class="form-label">Поиск</label>
                                            <input type="text" name="search" id="search" class="form-control" value="{{ request('search') }}" placeholder="Поиск...">
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Применить фильтры</button>
                                    <a href="{{ route('monitoringIndex') }}" class="btn btn-secondary">Сбросить</a>
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Закрыть</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Filter Modal End -->

                <div class="common-space project-tabs">
                    <ul class="nav nav-tabs border-tab" id="top-tab" role="tablist">
                        <!-- All Tasks Tab -->
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="top-home-tab" data-bs-toggle="tab" href="#top-home"
                                role="tab" aria-controls="top-home" aria-selected="true">
                                <i class="fa-solid fa-bullseye"></i> Все
                                <span class="badge bg-primary text-white ms-2">{{ $allTasks->count() ?? '' }}</span>
                            </a>
                        </li>
                        <!-- New Tasks Tab -->
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="progress-top-tab" data-bs-toggle="tab" href="#top-progress"
                                role="tab" aria-controls="top-progress" aria-selected="false">
                                <i class="fa-solid fa-bars-progress"></i> Новые
                                <span class="badge bg-secondary text-white ms-2">{{ $inProgressTasks->count() ?? '' }}</span>
                            </a>
                        </li>
                        <!-- Pending Tasks Tab -->
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="pending-top-tab" data-bs-toggle="tab" href="#top-pending" role="tab"
                                aria-controls="top-pending" aria-selected="false">
                                <i class="fa-regular fa-hourglass-half"></i> Процессные
                                <span class="badge bg-warning text-dark ms-2">{{ $pendingTasks->count() ?? '' }}</span>
                            </a>
                        </li>
                        <!-- Completed Tasks Tab -->
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="completed-top-tab" data-bs-toggle="tab" href="#top-completed"
                                role="tab" aria-controls="top-completed" aria-selected="false">
                                <i class="fa-solid fa-circle-check"></i> Выполненные
                                <span class="badge bg-success text-white ms-2">{{ $completedTasks->count() ?? '' }}</span>
                            </a>
                        </li>
                        <!-- Rejected Tasks Tab -->
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="employeeRejectedTasks-top-tab" data-bs-toggle="tab"
                                href="#top-employeeRejectedTasks" role="tab" aria-controls="top-employeeRejectedTasks"
                                aria-selected="false">
                                <i class="fa-solid fa-circle-xmark"></i> Незавершенные
                                <span class="badge bg-danger text-white ms-2">{{ $employeeRejectedTasks->count() ?? '' }}</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="tab-content" id="top-tabContent">
                    <!-- All Tasks Content -->
                    <div class="tab-pane fade show active" id="top-home" role="tabpanel" aria-labelledby="top-home-tab">
                        @include('pages.monitoring.partials.task-table', ['tasks' => $allTasks])
                    </div>
                    <!-- New Tasks Content -->
                    <div class="tab-pane fade" id="top-progress" role="tabpanel" aria-labelledby="progress-top-tab">
                        @include('pages.monitoring.partials.task-table', ['tasks' => $inProgressTasks])
                    </div>
                    <!-- Pending Tasks Content -->
                    <div class="tab-pane fade" id="top-pending" role="tabpanel" aria-labelledby="pending-top-tab">
                        @include('pages.monitoring.partials.task-table', ['tasks' => $pendingTasks])
                    </div>
                    <!-- Completed Tasks Content -->
                    <div class="tab-pane fade" id="top-completed" role="tabpanel" aria-labelledby="completed-top-tab">
                        @include('pages.monitoring.partials.task-table', ['tasks' => $completedTasks])
                    </div>
                    <!-- Rejected Tasks Content -->
                    <div class="tab-pane fade" id="top-employeeRejectedTasks" role="tabpanel"
                        aria-labelledby="employeeRejectedTasks-top-tab">
                        @include('pages.monitoring.partials.task-table', [
                            'tasks' => $employeeRejectedTasks,
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
