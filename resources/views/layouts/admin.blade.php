<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@lang('panel.site_title')</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ asset('assets/images/teamdevs.png') }}">
    <!-- DataTables -->
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
    <!-- Responsive datatable examples -->
    <link href="{{ asset('assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />
    <!-- select2 -->
    <link href="{{ asset('assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Bootstrap Css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet"
        type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/libs/@chenfengyuan/datepicker/datepicker.min.css') }}">
    <link href="{{ asset('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/myStyle.css') }}" rel="stylesheet" type="text/css" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}">
    @yield('styles')
</head>

<style>
    body {
        min-height: 100vh !important;
    }
</style>

<body data-sidebar="{{ auth()->user()->theme()['sidebar'] ?? '' }}"
    data-layout-mode="{{ auth()->user()->theme()['body'] ?? '' }}">
    {{-- class="sidebar-enable vertical-collpsed" --}}
    <!-- Begin page -->
    <!-- Loader -->
    <div id="preloader">
        <div id="status">
            <div class="spinner-chase">
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
            </div>
        </div>
    </div>
    <div id="layout-wrapper">
        <header id="page-topbar">
            <div class="navbar-header">
                <div class="d-flex">
                    <!-- LOGO -->
                    <div class="navbar-brand-box">
                        <a href="{{ route('home') }}" class="logo logo-dark">
                            <span class="logo-sm">
                                <img src="{{ asset('assets/images/logo.svg') }}" alt="" height="22">
                            </span>
                            <span class="logo-lg">
                                <img src="{{ asset('assets/images/logo.svg') }}" alt="" height="50">
                            </span>
                        </a>

                        <a href="{{ route('home') }}" class="logo logo-light">
                            <span class="logo-sm">
                                <img src="{{ asset('assets/images/logo.svg') }}" alt="" height="22">
                            </span>
                            <span class="logo-lg">
                                {{-- <img src="{{ asset('assets/images/logo_red.png') }}" alt="" height="50"> --}}
                                {{-- <h3 class="text-center text-light my-2">Teamdevs</h3> --}}
                                <img src="{{ asset('assets/images/logo.svg') }}" alt="" height="50">

                            </span>
                        </a>
                    </div>

                    <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect"
                        id="vertical-menu-btn">
                        <i class="fa fa-fw fa-bars"></i>
                    </button>
                    <div class="d-flex align-items-center">
                        <div class="cup-main">
                            <i class="bx bxs-trophy"></i>
                        </div>
                        <p class="cup-main-text">

                            {{-- $order->checked_status --}}
                            @php

                                use App\Models\Rating;
                                $rating = Rating::where('user_id', auth()->user()->id)->first();
                                $totalScore =
                                    Rating::where('user_id', auth()->user()->id)
                                        ->where('action', 0)
                                        ->sum('score') -
                                    Rating::where('user_id', auth()->user()->id)
                                        ->where('action', 1)
                                        ->sum('score');

                                $score = $totalScore;
                                // dump($totalScore)
                                // $score = $totalScore ? $rating->sum('score') : 0;
                                // dump($rating)
                            @endphp
                            {{ $score }} ball
                        </p>
                    </div>

                </div>



                <div class="d-flex">


                    {{-- command btn start --}}
                    <div class="d-flex align-items-center">
                        @php
                            $statusFilePath = public_path('status.txt');

                            $statusContent = file_get_contents($statusFilePath);
                            // var_dump($statusContent);
                            $statusData = json_decode($statusContent, true);
                            $my_demo_status = $statusData['stop_demo_cron'];

                        @endphp
                        {{-- @dump(auth()->user()->roles[0]->name) --}}
                        {{-- @if (auth()->user()->roles[0]->name != 'Employee')			
							@if ($my_demo_status)
							<form action="{{ route('toggleCommand') }}" method="POST">
								@csrf
								<input type="hidden" name="action" value="start">
								<button type="submit" class="text-button btn btn-success text-white border shadow">Start Command</button>
							</form>
							@else
							<form action="{{ route('toggleCommand') }}" method="POST">
								@csrf
								<input type="hidden" name="action" value="stop">
								<button type="submit" class="text-button btn btn-dark text-white">Stop Command</button>
							</form>
							@endif
						@endif --}}
                    </div>

                    {{-- command btn end --}}


                    {{-- <?php var_dump(auth()->user()->roles->contains('id', 4)); ?> --}}
                    @if (auth()->check() && auth()->user()->roles->contains('id', 4))
                        <div class="d-flex align-items-center">
                            <div class="square-switch d-flex">
                                <input type="checkbox" id="user_{{ auth()->user()->id }}" switch="bool"
                                    onclick="toggle_instock({{ auth()->user()->id }})"
                                    {{ auth()->user()->is_online ? 'checked' : '' }} />
                                <label for="user_{{ auth()->user()->id }}" class="mb-0 employee-switch-status"
                                    data-on-label="Online" data-off-label="Offline"></label>
                            </div>
                        </div>
                    @endif

                    <div class="dropdown d-inline-block">
                        <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img class="rounded-circle header-profile-user"
                                src="{{ asset('assets/images/avatar-dafault.png') }}" alt="Header Avatar">
                            <span class="d-none d-xl-inline-block ms-1" key="t-henry">
                                @if (auth()->user())
                                    {{ auth()->user()->name }}
                                @endif
                            </span>
                            <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <!-- item-->
                            <!-- <a class="dropdown-item" href="#"><i class="bx bx-user font-size-16 align-middle me-1"></i> <span key="t-profile">Profile</span></a>
       <a class="dropdown-item" href="#"><i class="bx bx-wallet font-size-16 align-middle me-1"></i> <span key="t-my-wallet">My Wallet</span></a> -->
                            @if (auth()->user())
                                <a class="dropdown-item d-block"
                                    href="{{ route('userProfile', auth()->user()->id) }}">
                                    <!-- <span class="badge bg-success float-end">11</span> -->
                                    <i class="bx bx-user font-size-16 align-middle me-1"></i>
                                    <span key="t-settings">Profile</span>
                                </a>
                            @endif
                            @if (auth()->user())
                                <a class="dropdown-item d-block" href="{{ route('userEdit', auth()->user()->id) }}">
                                    <!-- <span class="badge bg-success float-end">11</span> -->
                                    <i class="bx bx-wrench font-size-16 align-middle me-1"></i>
                                    <span key="t-settings">@lang('global.settings')</span>
                                </a>
                            @endif
                            <div class="dropdown-divider"></div>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                style="display: none;">
                                @csrf
                            </form>
                            <a class="dropdown-item text-danger" href="#" role="button"
                                onclick="
								event.preventDefault();
								document.getElementById('logout-form').submit();">
                                <i class="bx bx-power-off font-size-16 align-middle me-1 text-danger"></i>
                                <span key="t-logout">@lang('global.logout')</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- ========== Left Sidebar Start ========== -->
        @include('layouts.sidebar')
        <!-- Left Sidebar End -->



        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content" id="app">
            <div class="page-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
            <!-- End Page-content -->

            <footer class="footer">
                <div class="container-fluid">
                    <div class="row d-flex align-items-center">
                        <div class="col-sm-4">
                            <strong>Copyright &copy; {{ date('Y') }} <a href="#!">TeamDevs-Group
                                </a>.</strong>
                            All rights reserved.
                        </div>
                        <div class="col-sm-4">
                            <div class="row p-0 m-0">

                                <div class="col-4">
                                    <div class="mt-4">
                                        <p class="mb-2 text-truncate"><i class="mdi mdi-circle text-warning me-1"></i>
                                            First task</p>
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="mt-4">
                                        <p class="mb-2 text-truncate"><i
                                                class="mdi mdi-circle text-secondary me-1"></i> Later Task</p>
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="mt-4">
                                        <p class="mb-2 text-truncate"><i class="mdi mdi-circle text-danger me-1"></i>
                                            Extra task</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="text-sm-end d-none d-sm-block">
                                <b>Version</b> 1.0
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
        <!-- end main content-->

    </div>

    <!-- JAVASCRIPT -->
    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>
    <!-- Required datatable js -->
    <script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Buttons examples -->
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/libs/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/libs/pdfmake/build/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>

    <!-- Responsive examples -->
    <script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

    <!-- Datatable init js -->
    <script src="{{ asset('assets/js/pages/datatables.init.js') }}"></script>
    <!-- form advanced init -->
    <script src="{{ asset('assets/js/pages/form-advanced.init.js') }}"></script>
    <script src="{{ asset('assets/js/pages/job-list.init.js') }}"></script>
    <!-- App js -->
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
        $("#reset_form").on('click', function() {
            $('form :input').val('');
            $("form :input[class*='like-operator']").val('like');
            $("div[id*='_pair']").hide();
        });

        function togglePassword(inputId, toggleIconId) {
            var passwordInput = document.getElementById(inputId);
            var toggleIcon = document.getElementById(toggleIconId);

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                toggleIcon.classList.remove("mdi-eye-outline");
                toggleIcon.classList.add("mdi-eye-off-outline");
            } else {
                passwordInput.type = "password";
                toggleIcon.classList.remove("mdi-eye-off-outline");
                toggleIcon.classList.add("mdi-eye-outline");
            }
        }

        function toggle_instock(id) {
            $.ajax({
                url: "/user/toggle-status/" + id,
                type: "POST",
                data: {
                    _token: "{!! @csrf_token() !!}"
                },
                success: function(result) {
                    let checkbox = $("#user_" + id);
                    if (result.is_active == 1) {
                        checkbox.prop("checked", true);
                        Swal.fire({
                            icon: 'success',
                            title: 'You are online.',
                            toast: true,
                            position: 'top',
                            showConfirmButton: false,
                            timer: 1000,
                        })
                    } else {
                        checkbox.prop("checked", false);
                        Swal.fire({
                            icon: 'error',
                            title: 'You are offline.',
                            toast: true,
                            position: 'top',
                            showConfirmButton: false,
                            timer: 1000,
                        })
                    }
                },
                error: function(errorMessage) {
                    console.log(errorMessage)
                }
            });
        }
    </script>
    {{-- <script>
		setInterval(function() {
	  var progressBar = document.querySelector('.progress-bar');
	  var width = (item.timeout / (item.category_deadline * 60)) * 100 + '%';
	  progressBar.style.width = width;
	  
	}, 1000); 
	</script> --}}
    @if (session('_message'))
        <script>
            Swal.fire({
                position: 'top-end',
                icon: "{{ session('_type') }}",
                title: "{{ session('_message') }}",
                showConfirmButton: false,
                timer: {{ session('_timer') ?? 5000 }}
            });
        </script>
        @php(message_clear())
    @endif
    @yield('scripts')
</body>
