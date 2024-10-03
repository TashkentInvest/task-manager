@extends('layouts.admin')

@section('content')
<!-- Content Header (Page header) -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Control Report</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: #007bff;">@lang('global.home')</a></li>
                    <li class="breadcrumb-item active">Control Report</li>
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
                <h3 class="card-title">Control Report</h3>
                {{-- <a href="#!" class="btn btn-sm btn-success waves-effect waves-light float-right">
                    <span class="fas fa-plus-circle"></span>
                    @lang('global.add')
                </a> --}}
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <!-- Data table -->
                <table id="datatable" class="table table-bordered dt-responsive w-100">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Role</th>
                            <th>Phone</th>
                          
                         
                            <th class="w-25">@lang('global.actions')</th>

 
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        @if(isset($user->roles[0]) && $user->roles[0]->name != "Super Admin")			
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->roles[0]->name }}</td>
                                <td>{{ $user->phone }}</td>
                                <td><a href="{{ route('controlReportIndex', ['id' => $user->id, 'name' => $user->name]) }}" class="btn btn-primary">View User</a></td>
                            </tr>
                        @endif
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