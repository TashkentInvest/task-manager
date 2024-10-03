@extends('layouts.admin')

@section('content')
<!-- Content Header (Page header) -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Control Category</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: #007bff;">@lang('global.home')</a></li>
                    <li class="breadcrumb-item active">Control Category</li>
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
                <h3 class="card-title">Category</h3>
                @can('category.add')
                <a href="{{ route('categoryAdd') }}" class="btn btn-sm btn-success waves-effect waves-light float-right">
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
                            <th>Ball</th>
                            <th>Deadline</th>
                            <th>Addtional Time</th>
                            <th>Created at</th>
                            <th class="w-25">@lang('global.actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <td>{{ $category->id }}</td>
                                <td>{{ $category->name }}</td>
                                <td>
                                    {{ $category->score }}
                                </td>
                                <td>{{ $category->deadline }} minutes</td>
                                <td>
                                    @if($category->additional_time)
                                    {{ $category->additional_time }} minutes
                                    @else
                                    ---
                                    @endif
                                </td>
                                <td>{{ $category->created_at }}</td>
                                <td class="text-center">
                                    @can('category.delete')
                                    <form action="{{ route('categoryDestroy', $category->id) }}" method="post">
                                        @csrf
                                        <div class="btn-group">
                                            @can('category.edit')
                                            <a href="{{ route('categoryEdit',$category->id) }}" type="button" class="btn btn-info btn-sm waves-effect waves-light"> @lang('global.edit')</a>
                                            @endcan
                                            @can('category.de;ete')

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
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
</div>
@endsection