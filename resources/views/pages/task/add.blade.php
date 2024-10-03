@extends('layouts.admin')

@section('content')

<!-- Content Header (Page header) -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Add Task</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: #007bff;">@lang('global.home')</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('api-userIndex') }}" style="color: #007bff;">Control Category</a></li>
                    <li class="breadcrumb-item active">@lang('global.add')</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- Main content -->

<div class="row">
    <div class="col-lg-10 offset-lg-1 col-sm-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">@lang('global.add')</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <form id="taskForm" action="{{ route('taskCreate') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <div class="row">

                            <div class="col">
                                <label>Level</label>
                                <select class="form-control select2" style="width: 100%;" name="level_id" required>
                                    @foreach($taskLevels as $level)
                                    <option value="{{$level->id}}">{{$level->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col">
                                <label>Category</label>
                                <select class="form-control select2" style="width: 100%;" name="category_id" required>
                                    @foreach($categories as $category)
                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                    </div>

                    {{-- company and drivers --}}
                    <div class="form-group">
                        <div class="row">

                            {{-- <div class="col">
                                <label>Company</label>
                                <select class="form-control select2" style="width: 100%;" name="company_id" required>
                                    @foreach($companies as $company)
                                    <option value="{{$company->id}}">{{$company->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                             --}}
                           
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-12">
                                <label>Name</label>
                                <textarea rows="3" name="description" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="box d-flex">
                        <div class="form-group">
                            <label>Is this an Extra task?</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type_request" id="is_extra_yes" value="2">
                                <label class="form-check-label" for="is_extra_yes">
                                    Extra Task 
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type_request" id="is_extra_no" value="1">
                                <label class="form-check-label" for="is_extra_no">
                                    Later Task 
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type_request" id="is_extra_no" value="0">
                                <label class="form-check-label" for="is_extra_no">
                                    None
                                </label>
                            </div>
                        </div>
                    </div>
                    

                    <div class="form-group mt-2">
                        <input type="button" id="submitButton"  class="btn btn-success float-right" value="@lang('global.save')">
                        <a href="{{ route('monitoringIndex') }}" class="btn btn-light waves-effect float-left">@lang('global.cancel')</a>
                    </div>


                    {{-- <button type="submit">send</button> --}}
                </form>

            </div>
        </div>
    </div>
</div>
@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function () {
        $("#submitButton").click(function () {
            $(this).prop("disabled", true);

            var formData = $("#taskForm").serialize();

            formData += '&_token={{ csrf_token() }}';

            $.ajax({
                url: $("#taskForm").attr("action"),
                type: 'POST',
                data: formData,
                success: function (response) {
                    console.log("Request successful");
                    window.location.href = "{{ route('monitoringIndex') }}";

                },
                error: function (xhr, status, error) {
                    $("#submitButton").prop("disabled", false);
                    console.error("Request failed with status:", xhr.status);
                }
            });
        });
    });
</script>
