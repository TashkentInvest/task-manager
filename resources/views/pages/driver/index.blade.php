@extends('layouts.admin')

@section('content')
<!-- Content Header (Page header) -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Control Driver</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: #007bff;">@lang('global.home')</a></li>
                    <li class="breadcrumb-item active">Control Driver</li>
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
                <h3 class="card-title">Driver</h3>
                @can('driver.add')
                <a href="{{ route('driverAdd') }}" class="btn btn-sm btn-success waves-effect waves-light float-right">
                    <span class="fas fa-plus-circle"></span>
                    @lang('global.add')
                </a>
                @endcan
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <!-- Data table -->
                <table id="datatable-buttons" class="table table-bordered dt-responsive w-100">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Vehicle</th>
                            <th>Time Zone</th>
                            <th>Company</th>
                            <th id="1234" class="w-25">@lang('global.actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($drivers as $driver)
                        <tr>
                            <td>{{ $driver->id }}</td>
                            <td>{{ $driver->full_name }}</td>
                            <td>{{ $driver->track_num }}</td>
                            <td>{{ $driver->eastern_time }}</td>
                            <td class="{{ $driver->company->trashed() ? 'bg-danger' : '' }}">{{ $driver->company->name ?? 'Deleted' }}</td>
                            
                            {{-- <td>{{ $driver->company->name ?? '---' }}</td> --}}
                            <td class="text-center">
                                <form action="{{ route('driverDestroy', $driver->id) }}" method="post">
                                    @csrf
                                    <ul class="list-unstyled hstack gap-1 mb-0 justify-content-lg-center justify-content-md-center justify-content-start">
                                        
                                        @can('driver.edit')
                                        <li data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('global.edit')">
                                            <a href="{{ route('driverEdit', $driver->id) }}" class="btn btn-info">
                                                <i class="bx bxs-edit" style="font-size:16px;"></i>
                                            </a>
                                        </li>
                                        @endcan

                                        @if(auth()->user()->roles[0]->name != 'Employee')

                                        <li data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('global.delete')">
                                            <input name="_method" type="hidden" value="DELETE">
                                            <button type="button" class="btn btn-danger" onclick="if (confirm('Вы уверены?')) {this.form.submit()}">
                                                <i class="bx bxs-trash" style="font-size: 16px;"></i>
                                            </button>
                                        </li>
                                        @endcan

                                        <li data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('global.detail')">
                                            <button type="button" class="btn btn-warning" data-bs-toggle="modal" 
                                            data-bs-target="#exampleModal_{{ $driver->id }}">
                                                <i class="bx bxs-show" style="font-size: 16px;"></i>
                                            </button>
                                        </li>
                                        
                                    </ul>
                                </form>
                                
                                <div class="modal fade" id="exampleModal_{{ $driver->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">{{  $driver->full_name  }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <table class="table table-striped">
                                                    <tbody>
                                                        <tr>
                                                            <td>Phone number:</td>
                                                            <td>
                                                                <b>{{$driver->phone ?? 'Empty'}}</b>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2">Comment:</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2">
                                                                <b>{{$driver->comment ?? 'Empty'}}</b>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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