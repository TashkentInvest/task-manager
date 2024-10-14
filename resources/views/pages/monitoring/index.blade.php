@extends('layouts.admin')

@section('content')
    <div class="row mb-4">
        <div class="col-lg-12 col-12">
            <div class="card shadow-sm border-0 rounded">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="text-black text-5">Оставшиеся поручения</h3>
                    @can('left-request.add')
                        <a href="{{ route('taskAdd') }}" class="btn btn-success">
                            <span class="fas fa-plus-circle"></span>
                            Добавить
                        </a>
                    @endcan
                </div>


                <div class="common-space project-tabs">
                    <ul class="nav nav-tabs border-tab" id="top-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="top-home-tab" data-bs-toggle="tab" href="#top-home"
                                role="tab" aria-controls="top-home" aria-selected="true">
                                <i class="fa-solid fa-bullseye"></i> Все
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="progress-top-tab" data-bs-toggle="tab" href="#top-progress"
                                role="tab" aria-controls="top-progress" aria-selected="false">
                                <i class="fa-solid fa-bars-progress"></i> Новые
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="pending-top-tab" data-bs-toggle="tab" href="#top-pending" role="tab"
                                aria-controls="top-pending" aria-selected="false">
                                <i class="fa-regular fa-hourglass-half"></i> Процессные
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="completed-top-tab" data-bs-toggle="tab" href="#top-completed"
                                role="tab" aria-controls="top-completed" aria-selected="false">
                                <i class="fa-solid fa-circle-check"></i> Выполненные
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="employeeRejectedTasks-top-tab" data-bs-toggle="tab"
                                href="#top-employeeRejectedTasks" role="tab" aria-controls="top-employeeRejectedTasks"
                                aria-selected="false">
                                <i class="fa-solid fa-circle-check"></i> Незавершенные
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="tab-content" id="top-tabContent">
                    <div class="tab-pane fade show active" id="top-home" role="tabpanel" aria-labelledby="top-home-tab">
                        @include('pages.monitoring.partials.task-table', ['tasks' => $allTasks])
                    </div>
                    <div class="tab-pane fade" id="top-progress" role="tabpanel" aria-labelledby="progress-top-tab">
                        @include('pages.monitoring.partials.task-table', ['tasks' => $inProgressTasks])
                    </div>
                    <div class="tab-pane fade" id="top-pending" role="tabpanel" aria-labelledby="pending-top-tab">
                        @include('pages.monitoring.partials.task-table', ['tasks' => $pendingTasks])
                    </div>
                    <div class="tab-pane fade" id="top-completed" role="tabpanel" aria-labelledby="completed-top-tab">
                        @include('pages.monitoring.partials.task-table', ['tasks' => $completedTasks])
                    </div>
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
