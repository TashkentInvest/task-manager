@extends('layouts.admin')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Shift</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: #007bff;">@lang('global.home')</a>
                        </li>
                        <li class="breadcrumb-item active">Shift</li>
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
                    <h3 class="card-title">Shifts</h3>
                    @if (auth()->user()->roles[0]->name != 'Employee')
                        <a href="{{ route('shiftAdd') }}"
                            class="btn btn-sm btn-success waves-effect waves-light float-right">
                            <span class="fas fa-plus-circle"></span>
                            @lang('global.add')
                        </a>
                    @endif
                    <!-- <div class="boc d-flex mx-2 align-center">
                        <input class="mx-1" type="text" placeholder="from">
                        <input class="mx-1" type="text" placeholder="to">
                        <input class="bg-success text-white" style="border: none;outline:none" type="submit" value="submit">
                    </div> -->
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <!-- Data table -->
                    <table id="datatable" class="table table-bordered dt-responsive w-100">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Month</th>
                                <th>Off days</th>
                                <th>Count work days</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->user->name }}</td>
                                    <td>{{ $item->month }}</td>
                                    <td>
                                        @php
                                            $daysArray = explode(', ', $item->week_days);
                                        @endphp
                                        @foreach ($daysArray as $day)
                                            <span class="badge badge-soft-primary">{{ $day }}</span>
                                        @endforeach
                                    </td>
                                    <td>{{ $item->count_work_days }} days</td>
                                    <td>
                                        <form action="{{ route('shiftDestroy', $item->id) }}" method="post">
                                            @csrf
                                            <ul
                                                class="list-unstyled hstack gap-1 mb-0 justify-content-lg-center justify-content-md-center justify-content-start">
                                                @if (auth()->user()->roles[0]->name != 'Employee')
                                                    @can('driver.edit')
                                                        <li data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="@lang('global.edit')">
                                                            <a href="{{ route('shiftEdit', $item->id) }}" class="btn btn-info">
                                                                <i class="bx bxs-edit" style="font-size:16px;"></i>
                                                            </a>
                                                        </li>
                                                    @endcan

                                                    <li data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="@lang('global.delete')">
                                                        <input name="_method" type="hidden" value="DELETE">
                                                        <button type="button" class="btn btn-danger"
                                                            onclick="if (confirm('Вы уверены?')) {this.form.submit()}">
                                                            <i class="bx bxs-trash" style="font-size: 16px;"></i>
                                                        </button>
                                                    </li>


                                                    <li data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="@lang('global.detail')">
                                                        <button type="button" class="btn btn-warning"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#exampleModal_{{ $item->id }}">
                                                            <i class="bx bxs-show" style="font-size: 16px;"></i>
                                                        </button>
                                                    </li>
                                                @else
                                                    <p>You don't have permission</p>
                                                @endif
                                            </ul>
                                        </form>
                                        <div class="modal fade" id="exampleModal_{{ $item->id }}" tabindex="-1"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-scrollable">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">
                                                            {{ $item->user->name }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form id="orderForm"
                                                            action="{{ route('shiftExrtaDays', $item->id) }}"
                                                            method="post">
                                                            @csrf
                                                            <table class="table table-striped text-center">
                                                                <tbody>
                                                                    <tr>
                                                                        <td colspan="2">Off days in {{ $item->month }}:
                                                                        </td>
                                                                    </tr>
                                                                    @for ($i = 0; $i < count($item->days); $i += 2)
                                                                        <tr>
                                                                            <td colspan="1">
                                                                                <div class="form-check mb-3">
                                                                                    <input class="form-check-input"
                                                                                        type="checkbox"
                                                                                        id="formCheck_{{ $item->days[$i] }}"
                                                                                        name="extra_work_days[]"
                                                                                        value="{{ $item->days[$i]->date }}">
                                                                                    <label class="form-check-label"
                                                                                        for="formCheck_{{ $item->days[$i] }}">
                                                                                        <b>{{ $item->days[$i]->date }}</b>
                                                                                    </label>
                                                                                </div>
                                                                            </td>
                                                                            @if (isset($item->days[$i + 1]))
                                                                                <td colspan="1">
                                                                                    <div class="form-check mb-3">
                                                                                        <input class="form-check-input"
                                                                                            type="checkbox"
                                                                                            id="formCheck_{{ $item->days[$i + 1] }}"
                                                                                            name="extra_work_days[]"
                                                                                            value="{{ $item->days[$i + 1]->date }}">
                                                                                        <label class="form-check-label"
                                                                                            for="formCheck_{{ $item->days[$i + 1] }}">
                                                                                            <b>{{ $item->days[$i + 1]->date }}</b>
                                                                                        </label>
                                                                                    </div>
                                                                                </td>
                                                                            @endif
                                                                        </tr>
                                                                    @endfor
                                                                </tbody>
                                                            </table>
                                                            <button type="submit"
                                                                class="btn btn-primary float-right">Submit</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
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
