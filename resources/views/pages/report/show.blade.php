@extends('layouts.admin')

@section('content')
<!-- Content Header (Page header) -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Control report</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: #007bff;">@lang('global.home')</a></li>
                    <li class="breadcrumb-item active">Control report</li>
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
                <h3 class="card-title">Report</h3>
                <div class="btn-group float-right" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-sm btn-success waves-effect waves-light"
                    data-bs-toggle="modal" data-bs-target="#exampleModal_filter">
                        <i class="fas fa-filter"></i> @lang('global.filter')
                    </button>
                    <form action="" method="get">
                        <div class="modal fade" id="exampleModal_filter" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">@lang('global.filter')</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
    
    
                                        {{-- Task status --}}
                                        <div class="form-group row align-items-center my-2">
                                            <div class="col-3">
                                                <h6>User Name</h6>
                                            </div>
                                            <div class="col-2">
                                                <select class="form-control form-control-sm" name="status_operator">
                                                    <option value="like" {{ request()->status_operator == 'like' ? 'selected' : '' }}>Like</option>
                                                    <!-- Add other comparison operators if needed -->
                                                </select>
                                            </div>
                                            <div class="col-3">
                                                <input type="hidden" name="user_name_operator" value="like">
                                                <input class="form-control form-control-sm" type="text" name="user_name" value="{{ old('user_name', request()->user_name ?? '') }}">
                                            </div>
                                        </div>
                                        {{-- Task status end --}}
                                        <div class="form-group row align-items-center">
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
                                                    <option value="like" {{ request()->created_at_operator == '=' ? 'selected':'' }}> = </option>
                                                    <option value=">" {{ request()->created_at_operator == '>' ? 'selected':'' }}> > </option>
                                                    <option value="<" {{ request()->created_at_operator == '<' ? 'selected':'' }}> < </option>
                                                    <option value="between" {{ request()->created_at_operator == 'between' ? 'selected':'' }}> От .. до .. </option>
                                                </select>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-4">
                                                <input class="form-control form-control-sm" type="date" name="created_at" 
                                                value="{{ old('created_at',request()->created_at??'') }}">
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-4" id="created_at_pair" style="display: {{ request()->created_at_operator == 'between' ? 'block':'none'}}">
                                                <input class="form-control form-control-sm" type="date" name="created_at_pair" value="{{ old('created_at_pair',request()->created_at_pair??'') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" name="filter" class="btn btn-primary">@lang('global.filtering')</button>
                                        <button type="button" class="btn btn-outline-warning float-left pull-left" id="reset_form">@lang('global.clear')</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('global.closed')</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    {{-- <button type="button" class="btn btn-sm btn-secondary waves-effect waves-light">Middle</button> --}}
                    {{-- <a href="{{ route('controlReportIndex', ['id' => $id, 'name' => $user->name]) }}" class="btn btn-secondary waves-effect waves-light btn-sm"><i class="bx bx-revision"></i> @lang('global.clear')</a> --}}
                    <a href="{{ route('controlReportIndex', ['id' => $id, 'name' => $user->name]) }}" class="btn btn-secondary waves-effect waves-light btn-sm">
                        <i class="bx bx-revision"></i> @lang('global.clear')
                    </a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <!-- Data table -->
                <table id="datatable-buttons" class="table table-bordered dt-responsive w-100">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Employee</th>
                            <th>Company</th>
                            <th>Category</th>
                            <th>Driver</th>
                            <th>Timer</th>
                          
                            <th class="w-25">@lang('global.actions')</th>

 
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reports as $order)
                        

                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$order->user->name}}</td>
                            <td>{{$order->task->company->name}}</td>
                            <td>{{$order->task->category->name}}</td>
                            <td>{{$order->task->driver->full_name}}</td>
                            <td>{{$order->task->category->deadline}} min</td>
                       
                        
                          
                            <td class="text-center">
                                @can('roles.delete')
                                <form action="#!" method="post">
                                    @csrf
                                    <ul class="list-unstyled hstack gap-1 mb-0 justify-content-lg-center justify-content-md-center justify-content-start">
                                        
                                     
                                        
                                        {{-- <li data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('global.delete')">
                                            <input name="_method" type="hidden" value="DELETE">
                                            <button type="button" class="btn btn-danger" onclick="if (confirm('Вы уверены?')) {this.form.submit()}">
                                                <i class="bx bxs-x-circle" style="font-size: 16px;"></i>
                                            </button>
                                        </li> --}}

                                        <li data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('global.detail')">
                                            <button type="button" class="btn btn-success" data-bs-toggle="modal" 
                                            data-bs-target="#exampleModaldetail_{{ $order->id }}">
                                                <i class="bx bxs-show" style="font-size: 16px;"></i>
                                            </button>
                                        </li>

                                        {{-- @if($order->report_status == 0)
                                        
                                        <li data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('global.detail')">
                                            <button type="button" class="btn btn-warning" data-bs-toggle="modal" 
                                            data-bs-target="#exampleModal_{{ $order->id }}">
                                                <i class="bx bxs-show" style="font-size: 16px;"></i>
                                            </button>
                                        </li>

                                        @else

                                        <li data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('global.detail')">
                                            <button type="button" class="btn btn-warning disabled" data-bs-toggle="modal" 
                                            data-bs-target="#exampleModal_{{ $order->id }}">
                                                <i class="bx bxs-show" style="font-size: 16px;"></i>
                                            </button>
                                        </li>

                                        @endif --}}

                                        
                                        @if(auth()->user()->roles[0]->name != 'Employee')
                                        <li data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('global.detail')">
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" 
                                            data-bs-target="#exampleModalmodalizm_{{ $order->id }}">
                                                <i class="bx bxs-show" style="font-size: 16px;"></i>
                                            </button>
                                        </li>
                                        @endif

                                   

                                

                                        </li>
                                        
                                    </ul>
                                </form>

                                <div class="modal fade" id="exampleModaldetail_{{ $order->id }}" tabindex="-1" aria-labelledby="exampleModalLabeldetail" aria-hidden="true">
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
                                                            <td colspan="2"><b>Report Info:</b></td>
                                                        </tr>
                                                    
                                        
                                                        <tr>
                                                            <td><b>Category</b></td>
                                                            <td>{{ $order->task->category->name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Company</b></td>
                                                            <td>{{ $order->task->company->name }}</td>

                                                        </tr>
                                                        <tr>
                                                            <td><b>Driver</b></td>
                                                            <td>{{ $order->task->driver->full_name }}</td>
                                                        </tr>
                                                        
                                                        <tr>
                                                            <td><b>Score</b></td>
                                                            {{-- @dump($order->rating->score ) --}}
                                                            <td>{{ $order->rating->score ?? '' }}</td>
                                                        </tr>

                                                        
                                                        <tr>
                                                            <td><b>Fines Description</b></td>
                                                            <td>{{ $order->rating->description ?? '' }}</td>
                                                        </tr>
                                                     
                                                     
                                                        <tr>
                                                            <td><b>Task Level</b></td>

                                                            <td>{{ $order->task->level->name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Description</b></td>
                                                            <td>{{$order->task->description ? $order->task->description : 'Empty' }}</td>
                                                        </tr>
                                                        
                                                        
                                                      

                                                        <tr>
                                                            <td><b>Started At</b></td>

                                                            <td>{{ $order->created_at }} | {{ $order->created_at->format('l') }}
                                                        </td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Finished At</b></td>
                                                            <td>{{ $order->updated_at }} | {{ $order->updated_at->format('l') }}
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

                                {{-- edit modal start --}}

                                <div class="modal fade" id="exampleModalmodalizm_{{ $order->id }}" tabindex="-1" aria-labelledby="exampleModalLabelmodalizm" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title"> Fines Edit </h5>
                                            </div>
                                            <div class="modal-body">
                                                <table class="table table-striped text-center">
                                                    <tbody>
                                                        <form id="orderForm" action="{{ route('controlReportStore', ['order_id' => $order->id]) }}" method="post">
                                                            @csrf
                                                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                                                            <input type="hidden" name="user_id" value="{{ $order->user->id }}">
                                                            
                                                            <div class="row">
                                                                <div class="col-12 col-lg-12 mb-2">
                                                                    <label class="col-form-label">Action</label>
                                                                    {{-- @dump($rating->action) --}}
                                                                    {{-- <select class="form-select" name="action">
                                                                        <option value="1" {{ $rating->action == 1 ? 'selected' : '' }}>Fines</option>
                                                                        <option value="0" {{ $rating->action == 0 ? 'selected' : '' }}>Bonuses</option>

                                                                        
                                                                    </select>
                                                                     --}}

                                                                     
                                                                @if(isset($rating) && $order->rating)
                                                                     <select class="form-select" name="action">
                                                                         <option value="1" {{ $order->rating->action == 1 ? 'selected' : '' }}>Fines</option>
                                                                         <option value="0" {{ $order->rating->action == 0 ? 'selected' : '' }}>Bonuses</option>
                                                                     </select>
                                                                 @else
                                                                     <select class="form-select" name="action">
                                                                         <option value="1">Fines</option>
                                                                         <option value="0">Bonuses</option>
                                                                     </select>
                                                                 @endif
                                                                 
                                                                    
                                                                </div>
                                                                <div class="col-12 col-lg-6 mb-2">
                                                                    <div class="form-group">
                                                                        <label for="score">Score</label>
                                                                        <input type="text" class="form-control" id="score" name="score" value="{{ $order->rating->score ?? old('score') }}">

                                                                    </div>
                                                                </div>
                                                                <div class="col-12 col-lg-6 mb-2">
                                                                    <div class="form-group">
                                                                        <label for="description">Description</label>
                                                                        <input type="text" class="form-control" id="description" name="description" value="{{ $order->rating->description ?? old('description') }}">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                         
                                                            <button type="submit" class="btn btn-primary">Submit</button>
                                                        </form>
                                                      
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                {{-- edit modal end --}}

                                <div class="modal fade" id="exampleModal_{{ $order->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title"> Fines add </h5>
                                            </div>
                                            <div class="modal-body">
                                                <table class="table table-striped text-center">
                                                    <tbody>
                                                        <form id="orderForm" action="{{ route('controlReportCreate') }}" method="post">
                                                            @csrf
                                                            <input type="hidden" name="order_id" value="{{ $order->id }}">

                                                            <input type="hidden" name="user_id" value="{{ $order->user->id }}">
                                                            {{-- @dump($order->user->id ) --}}
                                                            
                                                            <div class="row">
                                                                <div class="col-12 col-lg-6 mb-2">
                                                                    <label class="col-form-label">Action</label>
                                                                    <select class="form-select" name="action">
                                                                        <option value="1">Fines</option>
                                                                        <option value="0">Bonuses</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-12 col-lg-6 mb-2">
                                                                    <label class="col-form-label">Username</label>
                                                                    <input type="text" class="form-control" value="{{ $order->user->name }}" readonly>
                                                                </div>
                                                                <div class="col-12 col-lg-6 mb-2">
                                                                    <label class="col-form-label">Score</label>
                                                                    <input type="text" class="form-control" name="score" id="score" placeholder="Score" >
                                                                </div>
                                                                <div class="col-12 col-lg-6 mb-2">
                                                                    <label class="col-form-label">Description</label>
                                                                    <input type="text" class="form-control" name="description" id="description" placeholder="Description" >
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <button id="saveButton" type="submit" class="btn btn-success waves-effect waves-light float-right">Save</button>
                                                            </div>
                                                        

                                                       
                                                        </form>
                                                        
                                                        <script>
                                                            $(document).ready(function () {
                                                                // Fetch rating via AJAX when the page loads
                                                                $.ajax({
                                                                    type: 'GET',
                                                                    url: '{{ route("fetchRating", $order->id) }}',
                                                                    success: function (response) {
                                                                        $('#score').val(response.score);
                                                                        $('#description').val(response.description);
                                                                    },
                                                                    error: function (xhr, status, error) {
                                                                        console.error(xhr.responseText);
                                                                    }
                                                                });
                                                        
                                                                // Handle form submission via AJAX
                                                                $('#orderForm').submit(function (e) {
                                                                    e.preventDefault(); // Prevent the default form submission
                                                                    var formData = $(this).serialize(); // Serialize form data
                                                                    $.ajax({
                                                                        type: 'POST',
                                                                        url: $(this).attr('action'),
                                                                        data: formData,
                                                                        success: function (response) {
                                                                            // Handle success response if needed
                                                                            console.log(response);
                                                                        },
                                                                        error: function (xhr, status, error) {
                                                                            // Handle error response if needed
                                                                            console.error(xhr.responseText);
                                                                        }
                                                                    });
                                                                });
                                                            });
                                                        </script>
                                                        
                                                        
                                                        
                                                        
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @endcan

                                
                                {{-- edit btn  --}}

                     

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