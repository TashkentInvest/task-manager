@extends('layouts.admin')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Employees</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: #007bff;">@lang('global.home')</a>
                        </li>
                        <li class="breadcrumb-item active">Employees</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">EMPLOYEES</h3>
                    @can('employee.add')
                        @if (auth()->check() && auth()->user()->roles->isNotEmpty() && auth()->user()->roles[0]->name != 'Employee')
                            <a href="{{ route('employeeAdd') }}"
                                class="btn btn-sm btn-success waves-effect waves-light float-right">
                                <span class="fas fa-plus-circle"></span>
                                @lang('global.add')
                            </a>
                        @endif
                    @endcan
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle table-nowrap table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" style="width: 70px;">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Date of birth</th>
                                    <th scope="col">Hire date</th>
                                    <th scope="col">Phone number</th>
                                    <th scope="col">Active</th>
                                    {{-- <th scope="col">Projects</th> --}}
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    @if (!$user->hasRole('Super Admin'))
                                        <tr>
                                            <td>
                                                @if (is_null($user->avatar))
                                                    <div class="avatar-xs">
                                                        <span class="avatar-title rounded-circle">
                                                            {{ substr($user->name, 0, 1) }}
                                                        </span>
                                                    </div>
                                                @else
                                                    <h1>ok</h1>
                                                @endif
                                            </td>
                                            <td>
                                                <h5 class="font-size-14 mb-1"><a href="javascript: void(0);"
                                                        class="text-dark">{{ $user->name }}</a></h5>
                                                <p class="text-muted mb-0">{{ $user->getRoleNames()->implode(', ') }}</p>
                                            </td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->birth_date ?? 'Empty' }}</td>
                                            <td>{{ $user->hire_date ?? 'Empty' }}</td>
                                            <td>{{ $user->phone ?? 'Empty' }}</td>
                                            @if (auth()->user()->roles[0]->name != 'Employee')
                                                <td>
                                                    <div>
                                                        <span id="program1_{{ $user->id }}"
                                                            class="badge badge-soft-{{ $user->is_online ? 'success' : 'danger' }} font-size-16 m-1">{{ $user->is_online ? 'Online' : 'Offline' }}</span>
                                                    </div>
                                                </td>

                                            @else
                                                <td>
                                                    <div>
                                                        <span id="program1_{{ $user->id }}"
                                                            class="badge badge-soft-{{ $user->is_online ? 'success' : 'danger' }} font-size-16 m-1">{{ $user->is_online ? 'Online' : 'Offline' }}</span>
                                                    </div>
                                                </td>

                                             
                                            @endif

                                            {{-- <td>
                                                125
                                            </td> --}}
                                            <td>
                                                @can('employee.delete')
                                                    @if (auth()->check() && auth()->user()->roles->isNotEmpty() && auth()->user()->roles[0]->name != 'Employee')
                                                        <form action="{{ route('employeeDestroy', $user->id) }}"
                                                            method="post">
                                                            @csrf
                                                            <ul class="list-unstyled hstack gap-1 mb-0 justify-content-start">
                                                                @can('employee.edit')
                                                                    <li data-bs-toggle="tooltip" data-bs-placement="top"
                                                                        title="@lang('global.edit')">
                                                                        <a href="{{ route('employeeEdit', $user->id) }}"
                                                                            class="btn btn-info">
                                                                            <i class="bx bxs-edit" style="font-size:16px;"></i>
                                                                        </a>
                                                                    </li>
                                                                @endcan
                                                                <li data-bs-toggle="tooltip" data-bs-placement="top"
                                                                    title="@lang('global.delete')">
                                                                    <input name="_method" type="hidden" value="DELETE">
                                                                    <button type="button" class="btn btn-danger"
                                                                        onclick="if (confirm('Вы уверены?')) { this.form.submit() } ">
                                                                        <i class="bx bxs-trash" style="font-size: 16px;"></i>
                                                                    </button>
                                                                </li>
                                                            </ul>
                                                        </form>
                                                    @else
                                                        <form action="#!">
                                                            @csrf
                                                            <ul class="list-unstyled hstack gap-1 mb-0 justify-content-start">
                                                                <li data-bs-toggle="tooltip" data-bs-placement="top"
                                                                    title="@lang('global.edit')">
                                                                    <a href="{{ route('employeeEdit', $user->id) }}"
                                                                        class="btn btn-info disabled">
                                                                        <i class="bx bxs-edit" style="font-size:16px;"></i>
                                                                    </a>
                                                                </li>

                                                                <li data-bs-toggle="tooltip" data-bs-placement="top"
                                                                    title="@lang('global.delete')">
                                                                    <button type="button" class="btn btn-danger disabled">
                                                                        <i class="bx bxs-trash" style="font-size: 16px;"></i>
                                                                    </button>
                                                                </li>
                                                            </ul>
                                                        </form>
                                                    @endif
                                                @endcan
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <ul class="pagination pagination-rounded justify-content-center mt-4">

                                {{-- Previous Page Link --}}
                                <li class="page-item {{ $users->onFirstPage() ? 'disabled' : '' }}">
                                    <a href="{{ $users->previousPageUrl() }}" class="page-link">
                                        <i class="mdi mdi-chevron-left"></i>
                                    </a>
                                </li>

                                {{-- Pagination Elements --}}
                                @for ($i = 1; $i <= $users->lastPage(); $i++)
                                    <li class="page-item {{ $users->currentPage() == $i ? 'active' : '' }}">
                                        <a href="{{ $users->url($i) }}" class="page-link">{{ $i }}</a>
                                    </li>
                                @endfor

                                {{-- Next Page Link --}}
                                <li class="page-item {{ $users->hasMorePages() ? '' : 'disabled' }}">
                                    <a href="{{ $users->nextPageUrl() }}" class="page-link">
                                        <i class="mdi mdi-chevron-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/libs/tinymce/tinymce.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            initTinyMCE("elm1_1")

            function initTinyMCE(selector) {
                tinymce.init({
                    selector: "#" + selector,
                    height: 200,
                    menubar: false,
                    plugins: [
                        "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                        "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                        "save table contextmenu directionality emoticons template paste textcolor",
                    ],
                    toolbar: "bold italic | alignleft aligncenter alignright alignjustify | emoticons",
                    style_formats: [
                        // Add your style formats here...
                    ],
                });
            }
        });
    </script>

@section('scripts')
    <script>
        function toggle_instock(id) {
            $.ajax({
                url: "/employee/toggle-status/" + id,
                type: "POST",
                data: {
                    _token: "{!! @csrf_token() !!}"
                },
                success: function(result) {
                    if (result.is_active == 1) {
                        $("#program_" + id).attr('class', "fas fa-check-circle text-success");
                        $("#program1_" + id).attr('class',
                            "badge badge-soft-success text-success font-size-16 m-1");
                    } else {
                        $("#program_" + id).attr('class', "fas fa-times-circle text-danger");
                        $("#program1_" + id).attr('class',
                            "badge badge-soft-danger text-danger font-size-16 m-1");
                    }
                },
                error: function(errorMessage) {
                    console.log(errorMessage)
                }
            });
        }
    </script>
@endsection
