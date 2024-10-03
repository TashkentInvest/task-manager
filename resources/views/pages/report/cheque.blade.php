@extends('layouts.admin')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">CHEQUE</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: #007bff;">@lang('global.home')</a>
                        </li>
                        <li class="breadcrumb-item active">CHEQUE</li>
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
                    <h3 class="card-title">Cheuue Period</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">

                    <table id="datatable" class="table table-bordered dt-responsive w-100">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Employee</th>
                                <th>Total Shift</th>
                                <th>Total Score</th>
                                <th>Fines</th>
                                <th>Bonuses</th>
                            </tr>
                        </thead>
                        <tbody>
                    
                            @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                         <td>{{ $user->name }}</td>

                                        <td>
                                            @foreach ($user->userOfDays as $item)
                                                month: {{ $item->month }} | weeks: {{ $item->week_days }} | count of days:
                                                {{ $item->count_work_days }}
                                            @endforeach
                                        </td>
                                        <td>{{ $userScores[$user->id]['totalScore'] }}</td>
                                        <td>{{ $userScores[$user->id]['finesScore'] }}</td>
                                        <td>{{ $userScores[$user->id]['bonusScore'] }}</td>
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
