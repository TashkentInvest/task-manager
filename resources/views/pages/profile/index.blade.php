@extends('layouts.admin')

@section('content')
<!-- Content Header (Page header) -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Profile</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: #007bff;">@lang('global.home')</a></li>
                    <li class="breadcrumb-item active">Profile</li>
                </ol>
            </div>

        </div>
    </div>
</div>

<!-- Main content -->
<div class="row">
    <div class="col-xl-6">
        <div class="card overflow-hidden">
            <div class="bg-primary bg-soft">
                <div class="row">
                    <div class="col-7">
                        <div class="text-primary p-3">
                            <h5 class="text-primary">Welcome Back !</h5>
                            <p>It will seem like simplified</p>
                        </div>
                    </div>
                    <div class="col-5 align-self-end">
                        <img src="assets/images/profile-img.png" alt="" class="img-fluid">
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="avatar-md profile-user-wid mb-4">
                            <!-- Check if user has an avatar -->
                                @if($user->avatar)
                                <!-- Use asset() helper to generate the correct URL for the avatar -->
                                <img src="{{ asset('storage/' . $user->avatar) }}" class="img-thumbnail rounded-circle" style="width: 70px !important; height: 70px !important;" alt="Avatar">
                                @else
                                <!-- Display a default avatar if user doesn't have one -->
                                <img src="{{ asset('assets/images/users/avatar-1.jpg') }}" class="img-thumbnail rounded-circle" alt="Default Avatar">
                                @endif

                            {{-- <img src="assets/images/users/avatar-1.jpg" alt="" class="img-thumbnail rounded-circle"> --}}
                        </div>
                        <h5 class="font-size-15 text-truncate">{{$user->name}}</h5>
                        <p class="text-muted mb-0 text-truncate">{{$user->roles[0]->name}}</p>
                    </div>

                    <div class="col-sm-8">
                        <div class="pt-4">
                            
                            {{-- <div class="row">
                                <div class="col-6">
                                    <h5 class="font-size-15">{{$Completed_own_orders}}</h5>
                                    <p class="text-muted mb-0">Completed Projects</p>
                                </div>
                                <div class="col-6">
                                    <h5 class="font-size-15">{{$Completed_own_orders}}</h5>
                                    <p class="text-muted mb-0">UnCompleted Projects</p>
                                </div>
                            </div> --}}
                            <div class="mt-4">
                                <a href="{{route('userEdit', ['id' => $user->id])}}" class="btn btn-primary waves-effect waves-light btn-sm">Edit Profile <i class="mdi mdi-arrow-right ms-1"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end card -->

        <div class="card">
            <div class="card-body d-flex flex-column">
                <h4 class="card-title mb-4">Personal Information</h4>

                <p class="text-muted mb-4"> {{$user->about}}.</p>
                <div class="table-responsive">
                    <table class="table table-nowrap mb-0">
                        <tbody>
                            <tr>
                                <th scope="row">Full Name :</th>
                                <td>{{$user->name}}</td>
                            </tr>
                            <tr>
                                <th scope="row">Mobile :</th>
                                <td>{{$user->phone}}</td>
                            </tr>
                            <tr>
                                <th scope="row">E-mail :</th>
                                <td>{{$user->email}}</td>
                            </tr>
                            <tr>
                                <th scope="row">Location :</th>
                                <td>{{$user->location}}</td>
                            </tr>
                            <tr>
                                <th scope="row">Birth Date :</th>
                                <td>{{$user->birth_date}}</td>
                            </tr>

                            <tr>
                                <th scope="row">Hire Date :</th>
                                <td>{{$user->hire_date}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- end card -->
    </div>         
    
 
</div>
@endsection