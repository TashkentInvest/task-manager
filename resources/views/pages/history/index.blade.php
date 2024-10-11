@extends('layouts.admin')

@section('content')
<!-- Content Header (Page header) -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Request History</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: #007bff;">@lang('global.home')</a></li>
                    <li class="breadcrumb-item active">Request History</li>
                </ol>
            </div>

        </div>
    </div>
</div>

<!-- Main content -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">History</h3>
                <div class="btn-group float-right" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-sm btn-success waves-effect waves-light"
                        data-bs-toggle="modal" data-bs-target="#exampleModal_filter">
                        <i class="fas fa-filter"></i> @lang('global.filter')
                    </button>
                    <form action="" method="get">
                        <div class="modal fade" id="exampleModal_filter" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">@lang('global.filter')</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">

                                        <!-- Company Search -->
                                            <div class="form-group row align-items-center my-2">
                                                <div class="col-3">
                                                    <h6>Company Name</h6>
                                                </div>
                                                <div class="col-2">
                                                    <select class="form-control form-control-sm" name="company_operator">
                                                        <option value="like" {{ request()->company_operator == 'like' ? 'selected' : '' }}>Like</option>
                                                        <!-- Add other comparison operators if needed -->
                                                    </select>
                                                </div>
                                                <div class="col-3">
                                                    <input type="hidden" name="company_name_operator" value="like">
                                                    <input class="form-control form-control-sm" type="text" name="company_name" value="{{ old('company_name', request()->company_name ?? '') }}">
                                                </div>
                                            </div>

                                            <!-- Company Search End-->

                                            
                                        <!-- Category Search -->
                                        <div class="form-group row align-items-center my-2">
                                            <div class="col-3">
                                                <h6>Category Name</h6>
                                            </div>
                                            <div class="col-2">
                                                <select class="form-control form-control-sm" name="category_operator">
                                                    <option value="like" {{ request()->category_operator == 'like' ? 'selected' : '' }}>Like</option>
                                                    <!-- Add other comparison operators if needed -->
                                                </select>
                                            </div>
                                            <div class="col-3">
                                                <input type="hidden" name="category_name_operator" value="like">
                                                <input class="form-control form-control-sm" type="text" name="category_name" value="{{ old('category_name', request()->category_name ?? '') }}">
                                            </div>
                                        </div>

                                        <!-- Category Search End-->

                                          <!-- driver Search -->
                                          <div class="form-group row align-items-center my-2">
                                            <div class="col-3">
                                                <h6>Driver Name</h6>
                                            </div>
                                            <div class="col-2">
                                                <select class="form-control form-control-sm" name="driver_operator">
                                                    <option value="like" {{ request()->driver_operator == 'like' ? 'selected' : '' }}>Like</option>
                                                    <!-- Add other comparison operators if needed -->
                                                </select>
                                            </div>
                                            <div class="col-3">
                                                <input type="hidden" name="driver_full_name_operator" value="like">
                                                <input class="form-control form-control-sm" type="text" name="driver_full_name" value="{{ old('driver_full_name', request()->driver_full_name ?? '') }}">
                                            </div>
                                        </div>

                                        <!-- driver Search End-->


                                           <!-- user Search -->
                                           <div class="form-group row align-items-center my-2">
                                            <div class="col-3">
                                                <h6>User Name</h6>
                                            </div>
                                            <div class="col-2">
                                                <select class="form-control form-control-sm" name="user_operator">
                                                    <option value="like" {{ request()->user_operator == 'like' ? 'selected' : '' }}>Like</option>
                                                    <!-- Add other comparison operators if needed -->
                                                </select>
                                            </div>
                                            <div class="col-3">
                                                <input type="hidden" name="user_name_operator" value="like">
                                                <input class="form-control form-control-sm" type="text" name="user_name" value="{{ old('user_name', request()->user_name ?? '') }}">
                                            </div>
                                        </div>

                                        <!-- user Search End-->


                                        {{-- Task status --}}
                                        <div class="form-group row align-items-center my-2">
                                            <div class="col-3">
                                                <h6>Status Name</h6>
                                            </div>
                                            <div class="col-2">
                                                <select class="form-control form-control-sm" name="status_operator">
                                                    <option value="like" {{ request()->status_operator == 'like' ? 'selected' : '' }}>Like</option>
                                                    <!-- Add other comparison operators if needed -->
                                                </select>
                                            </div>
                                            <div class="col-3">
                                                <input type="hidden" name="status_name_operator" value="like">
                                                <input class="form-control form-control-sm" type="text" name="status_name" value="{{ old('status_name', request()->status_name ?? '') }}">
                                            </div>
                                        </div>
                                        {{-- Task status end --}}


        

                                        <div class="form-group row align-items-center my-2">
                                            <div class="col-lg-3 col-md-4 col-sm-3 col-12">
                                                <h6>Дата создания</h6>
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-sm-3 col-4">
                                                <select class="form-control form-control-sm" name="created_at_operator"
                                                    onchange="
                                                        if(this.value == 'between'){
                                                        document.getElementById('created_at_pair').style.display = 'block';
                                                        } else {
                                                        document.getElementById('created_at_pair').style.display = 'none';
                                                        }
                                                        ">
                                                    <option value="like"
                                                        {{ request()->created_at_operator == '=' ? 'selected' : '' }}> =
                                                    </option>
                                                    <option value=">"
                                                        {{ request()->created_at_operator == '>' ? 'selected' : '' }}> >
                                                    </option>
                                                    <option value="<"
                                                        {{ request()->created_at_operator == '<' ? 'selected' : '' }}>
                                                        < </option>
                                                    <option value="between"
                                                        {{ request()->created_at_operator == 'between' ? 'selected' : '' }}>
                                                        От .. до .. </option>
                                                </select>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-4">
                                                <input class="form-control form-control-sm" type="date"
                                                    name="created_at"
                                                    value="{{ old('created_at', request()->created_at ?? '') }}">
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-4" id="created_at_pair"
                                                style="display: {{ request()->created_at_operator == 'between' ? 'block' : 'none' }}">
                                                <input class="form-control form-control-sm" type="date"
                                                    name="created_at_pair"
                                                    value="{{ old('created_at_pair', request()->created_at_pair ?? '') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" name="filter"
                                            class="btn btn-primary">@lang('global.filtering')</button>
                                        <button type="button" class="btn btn-outline-warning float-left pull-left"
                                            id="reset_form">@lang('global.clear')</button>
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">@lang('global.closed')</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    {{-- <button type="button" class="btn btn-sm btn-secondary waves-effect waves-light">Middle</button> --}}
                    <a href="" class="btn btn-secondary waves-effect waves-light btn-sm">
                        <i class="bx bx-revision"></i> @lang('global.clear')
                    </a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <!-- Data table -->
                <table id="datatable" class="table table-bordered dt-responsive w-100">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Company</th>
                            <th>Company</th>
                            <th>Driver</th>
                            <th>User name</th>
                            <th>Status</th>
                            <th>Created at</th>
                            <th class="w-20">@lang('global.actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tasks as $task)
                            <tr>
                                <td>{{ $task->id }}</td>
                                <td class="{{ $task->company->trashed() ? 'bg-danger' : '' }}">{{ $task->company->name ?? 'Deleted' }}</td>
                                <td class="{{ $task->category->trashed() ? 'bg-danger' : '' }}">{{ $task->category->name ?? 'Deleted' }}</td>
                                <td class="{{ $task->driver->trashed() ? 'bg-danger' : '' }}">{{ $task->driver->full_name ?? 'Deleted' }}</td>
                                {{-- <td>{{ $task->driver->full_name ?? 'Deleted' }}</td> --}}
                                <td>{{ $task->user->name }}</td>
                                <td>
                                    @if($task->status_id == 2 || $task->status_id == 5)
                                        <span class="badge rounded-pill bg-danger" style="font-size: 14px;">{{ $task->status->name }}</span> 
                                    @elseif($task->status_id == 1 || $task->status_id == 3 || $task->status_id == 4)
                                        <span class="badge rounded-pill bg-success" style="font-size: 14px;">{{ $task->status->name }}</span> 
                                    @else
                                        <span class="badge rounded-pill bg-warning" style="font-size: 14px;">{{ $task->status->name }}</span> 
                                    @endif
                                </td>
                                <td>{{ $task->created_at }}</td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ route('historyDetailIndex', $task->id) }}" type="button" class="btn btn-info btn-sm waves-effect waves-light"> @lang('global.details')</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
</div>
@endsection