@extends('layouts.admin')

@section('content')

<!-- Content Header (Page header) -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Edit Shift</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: #007bff;">@lang('global.home')</a></li>
                    <li class="breadcrumb-item"><a href="" style="color: #007bff;">Edit Shift</a></li>
                    <li class="breadcrumb-item active">@lang('global.edit')</li>
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
                <h3 class="card-title">@lang('global.edit')</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <form action="#!" method="post" id="myForm">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="removeDisabled"
                                name="edit_weekdays" checked>
                                <label class="form-check-label" for="removeDisabled">
                                    <b>Edit weekdays</b>
                                </label>
                            </div>
                        </div>
                        <input type="hidden" name="edit_week_days" value="{{ $userDayInfo->id }}">
                        <input type="hidden" name="user_id" value="{{ $userDayInfo->user_id }}">
                        <div class="col-12 col-lg-6 mb-2">
                            <label>Select weekdays</label>
                            <select class="select2 form-control select2-multiple"
                                    multiple="multiple" style="width: 100%;" name="off_days" data-placeholder="Choose ..." required>
                                <option value="Sunday" {{ strpos($userDayInfo->week_days, 'Sunday') !== false ? 'selected' : '' }}>Sunday</option>
                                <option value="Monday" {{ strpos($userDayInfo->week_days, 'Monday') !== false ? 'selected' : '' }}>Monday</option>
                                <option value="Tuesday" {{ strpos($userDayInfo->week_days, 'Tuesday') !== false ? 'selected' : '' }}>Tuesday</option>
                                <option value="Wednesday" {{ strpos($userDayInfo->week_days, 'Wednesday') !== false ? 'selected' : '' }}>Wednesday</option>
                                <option value="Thursday" {{ strpos($userDayInfo->week_days, 'Thursday') !== false ? 'selected' : '' }}>Thursday</option>
                                <option value="Friday" {{ strpos($userDayInfo->week_days, 'Friday') !== false ? 'selected' : '' }}>Friday</option>
                                <option value="Saturday" {{ strpos($userDayInfo->week_days, 'Saturday') !== false ? 'selected' : '' }}>Saturday</option>
                            </select>
                        </div>
                        <div class="col-12 col-lg-6 mb-2">
                            <label>Additional off days</label>
                            <select class="select2 form-control select2-multiple"
                                multiple="multiple" style="width: 100%;" name="additional_off_day" data-placeholder="Choose ..." required>
                                <option></option>
                                @foreach($other_days as $day)
                                    <option value="{{$day}}">{{$day}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success waves-effect waves-light float-right">@lang('global.save')</button>
                        <a href="" class="btn btn-light waves-effect float-left">@lang('global.cancel')</a>
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
        toggleSelects();
        var shiftId = $('[name="edit_week_days"]').val();
        // console.log(shiftId);
        // Add change event handler
        $('#removeDisabled').change(function(){
            toggleSelects();
        });
        function toggleSelects() {
            if ($('#removeDisabled').is(':checked')) {
                $('select[name="off_days"]').prop('disabled', false);
                $('select[name="additional_off_day"]').prop('disabled', true);
            } else {
                $('select[name="off_days"]').prop('disabled', true);
                $('select[name="additional_off_day"]').prop('disabled', false);
            }
        };
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
                edit_week_days: $('#removeDisabled').prop('checked'),
                user_id: $('[name="user_id"]').val(),
                'off_days[]': selectedDays,
                additional_off_day: additionalOffDay
            };
            console.log(formData);
            // Send the form data via AJAX
            $.ajax({
                url: '/report-shift/update/' + shiftId,
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
                        location.reload(true);
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