@extends('layouts.admin')

@section('content')
<!-- Content Header (Page header) -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Control Fines</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: #007bff;">@lang('global.home')</a></li>
                    <li class="breadcrumb-item active">Control Fines</li>
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
                <h3 class="card-title">Fines</h3>
            
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <!-- Data table -->
                <table id="datatable" class="table table-bordered dt-responsive w-100">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Employee</th>
                            <th>Minus</th>
                            <th>Birth date</th>
                            <th>Action</th>
                          
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr>
                            <td>{{$loop->iteration}} </td>
                            <td>{{$user->name}}</td>
                            <td>{{$totalScore}}</td>
                            <td>{{$user->birth_date ? $user->birth_date : 'Not Set'}}</td>
                            <td>    
                                <button type="button" class="btn btn-warning" data-bs-toggle="modal" 
                                    data-bs-target="#exampleModal_{{ $user->id }}">
                                    <i class="bx bxs-show" style="font-size: 16px;"></i>
                                </button>
                                <div class="modal fade" id="exampleModal_{{ $user->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                 
                                    <div class="modal-dialog modal-dialog-scrollable modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title"> Fines show </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <table class="table table-striped">
                                                    <tbody>
                                                        <!-- Left Info -->
                                                        <tr class="text-center">
                                                            <td colspan="2"><b>Left request Info:</b></td>
                                                        </tr>
                                                    
                                                        @foreach ($fines as $fine)
                                                        <tr class="bg-warning text-white">
                                                            <td><b>Next Fines ID</b></td>
                                                            <td>{{$loop->iteration}}</td>
                                                            
                                                        </tr>

                                                        <tr>
                                                            <td><b>Category</b></td>
                                                            <td>{{ $fine->order->task->category->name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Company</b></td>
                                                            <td>{{ $fine->order->task->company->name }}</td>

                                                        </tr>
                                                        <tr>
                                                            <td><b>Driver</b></td>
                                                            <td>{{ $fine->order->task->driver->full_name }}</td>
                                                        </tr>
                                                     
                                                        <tr>
                                                            <td><b>Score</b></td>
                                                            <td>{{ $fine->score }}</td>
                                                        </tr>

                                                        
                                                        <tr>
                                                            <td><b>Fines Description</b></td>
                                                            <td>{{ $fine->description }}</td>
                                                        </tr>
                                                     
                                                     
                                                        <tr>
                                                            <td><b>Task Level</b></td>

                                                            <td>{{ $fine->order->task->level->name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Description</b></td>
                                                            <td>{{$fine->order->task->description ? $fine->order->task->description : 'Empty' }}</td>
                                                        </tr>
                                                        
                                                        
                                                      

                                                        <tr>
                                                            <td><b>Started At</b></td>

                                                            <td>{{ $fine->created_at }} | {{ $fine->created_at->format('l') }}
                                                        </td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Finished At</b></td>
                                                            <td>{{ $fine->updated_at }} | {{ $fine->updated_at->format('l') }}
                                                        </td>

                                                        </tr>
                                                   
                                                        @endforeach
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