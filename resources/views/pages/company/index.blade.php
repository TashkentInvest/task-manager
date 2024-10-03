@extends('layouts.admin')

@section('content')
<!-- Content Header (Page header) -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Control Company</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: #007bff;">@lang('global.home')</a></li>
                    <li class="breadcrumb-item active">Control Company</li>
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
                <h3 class="card-title">Company</h3>
                @can('company.add')
                <a href="{{ route('companyAdd') }}" class="btn btn-sm btn-success waves-effect waves-light float-right">
                    <span class="fas fa-plus-circle"></span>
                    @lang('global.add')
                </a>
                @endcan
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <!-- Data table -->
                <table id="datatable" class="table table-bordered dt-responsive w-100">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Owner name</th>
                            <th>Phone</th>
                            <th>Created at</th>
                            <th class="w-25">@lang('global.actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($companies as $company)
                        <tr>
                            <td>{{ $company->id }}</td>
                            <td>{{ $company->name }}</td>
                            <td>{{ $company->owner_name }}</td>
                            <td>+{{ $company->phone }}</td>
                            <td>{{ $company->created_at }}</td>
                            <td class="text-center">
                                @can('company.delete')
                                <form action="{{ route('companyDestroy', $company->id) }}" method="post">
                                    @csrf
                                    <div class="btn-group">
                                        @can('company.edit')
                                        <a href="{{ route('companyEdit',$company->id) }}" type="button" class="btn btn-info btn-sm waves-effect waves-light"> @lang('global.edit')</a>
                                        @endcan
                                        @can('company.delete ')
                                        <input name="_method" type="hidden" value="DELETE">
                                        <button type="button" class="btn btn-danger waves-effect btn-sm waves-light" onclick="if (confirm('Вы уверены?')) { this.form.submit() } "> @lang('global.delete')</button>
                                        @endcan
                                    </div>
                                </form>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->

            <!-- /.modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">New company</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form>
                                <div class="mb-3">
                                    <label for="recipient-name" class="col-form-label">Name:</label>
                                    <input type="text" class="form-control" id="recipient-name">
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Send message</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
</div>
@endsection