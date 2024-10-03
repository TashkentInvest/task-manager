@extends('layouts.admin')

@section('content')

<!-- Content Header (Page header) -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Add Shift</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: #007bff;">@lang('global.home')</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('shiftIndex') }}" style="color: #007bff;">Control Shift</a></li>
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

                <form action="#!" method="post" id="myForm">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-lg-6 mb-2">
                            <label class="col-form-label">User</label>
                            <select class="form-control select2" style="width: 100%;" 
                            data-placeholder="Choose ..."
                            name="user_id"
                                value="{{ old('user_id') }}" required>
                                <option></option>
                                @foreach($users as $user)
                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-lg-6 mb-2">
                            <label class="col-form-label">Select weekdays</label>
                            <select class="select2 form-control select2-multiple"
                                multiple="multiple" style="width: 100%;" name="off_days" data-placeholder="Choose ..." required>
                                <option value="0">Sunday</option>
                                <option value="0">Monday</option>
                                <option value="0">Tuesday</option>
                                <option value="0">Wednesday</option>
                                <option value="0">Thursday</option>
                                <option value="0">Friday</option>
                                <option value="0">Saturday</option>
                            </select>
                        </div>
                        <!-- <div class="col-12 col-lg-6 mb-2">
                            <label>Auto Close</label>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="yyyy-mm-dd" data-date-format="yyyy-mm-dd" 
                                data-provide="datepicker" data-date-autoclose="true" name="additional_off_day">
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                            </div>
                        </div> -->
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success waves-effect waves-light float-right">@lang('global.save')</button>
                        <a href="{{ route('shiftIndex') }}" class="btn btn-light waves-effect float-left">@lang('global.cancel')</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('assets/libs/@chenfengyuan/datepicker/datepicker.min.js') }}"></script>
<script>
    $(document).ready(function() {
        var currentDate = new Date();
        $('[data-provide="datepicker"]').datepicker({
            format: 'yyyy-mm-dd',
            startDate: currentDate,
            autoclose: true
        });
        $('#myForm').submit(function(event) {
            event.preventDefault(); // Prevent the form from submitting normally
            var selectedDays = [0, 0, 0, 0, 0, 0, 0]; 
            
            var selectedOptions = $('[name="off_days"]').select2('data');
            var dayIndices = {
                "Sunday": 0,
                "Monday": 1,
                "Tuesday": 2,
                "Wednesday": 3,
                "Thursday": 4,
                "Friday": 5,
                "Saturday": 6
            };
            selectedOptions.forEach(function(option) {
                var dayIndex = dayIndices[option.text]; // Get the index of the selected day
                selectedDays[dayIndex] = 1; // Set selected day to 1
            });
            // Additional off day
            var additionalOffDay = $('[name="additional_off_day"]').val();
            // Prepare data to be sent via AJAX
            var formData = {
                user_id: $('[name="user_id"]').val(),
                'off_days[]': selectedDays,
                // additional_off_day: additionalOffDay
            };
            console.log(formData);
            // Send the form data via AJAX
            $.ajax({
                url: "{{ route('shiftCreate') }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: formData,
                success: function(response) {
                    if(response.status === 0){
                        Swal.fire({
                            icon: 'success',
                            title: response.message,
                            toast: true,
                            position: 'top',
                            showConfirmButton: false,
                            timer: 1000,
                        });
                        // location.reload(true);
                        window.location.href = response.redirect_url;

                    }
                },
                error: function(xhr, status, error) {
                    // console.log(xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: xhr.responseText,
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 1000,
                    });
                }
            });
        });
    });
</script>
@endsection