@extends('layouts.admin')

@section('content')
<!-- Content Header (Page header) -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Control Long Texts</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: #007bff;">@lang('global.home')</a></li>
                    <li class="breadcrumb-item active">Control Long Texts</li>
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
                <h3 class="card-title">Long Text</h3>
                @can('driver.add')
                <a href="{{ route('longTextAdd') }}" class="btn btn-sm btn-success waves-effect waves-light float-right">
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
                            <th>Category</th>
                            <th>Description</th>
                            <th class="w-25">@lang('global.actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($longTexts as $longText)
                        <tr>
                            <td>{{  $longText->id }}</td>
                            <td>{{  $longText->category }}</td>
                            <td style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis; max-width: 200px;">{{  $longText->description }}</td>
                            <td class="text-center">
                                @can('driver.delete')
                                <form action="{{ route('longTextDestroy', $longText->id) }}" method="post">
                                    @csrf
                                    <ul class="list-unstyled hstack gap-1 mb-0 justify-content-lg-center justify-content-md-center justify-content-start">
                                        
                                        <li data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('global.edit')">
                                            <a href="{{ route('longTextEdit', $longText->id) }}" class="btn btn-info">
                                                <i class="bx bxs-edit" style="font-size:16px;"></i>
                                            </a>
                                        </li>
                                        
                                        
                                        <li data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('global.delete')">
                                            <input name="_method" type="hidden" value="DELETE">
                                            <button type="button" class="btn btn-danger" onclick="if (confirm('Вы уверены?')) {this.form.submit()}">
                                                <i class="bx bxs-trash" style="font-size: 16px;"></i>
                                            </button>
                                        </li>

                                        <li data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('global.detail')">
                                            <button type="button" class="btn btn-warning" data-bs-toggle="modal" 
                                            data-bs-target="#exampleModal_{{ $longText->id }}">
                                                <i class="bx bxs-show" style="font-size: 16px;"></i>
                                            </button>
                                        </li>
                                        
                                    </ul>
                                </form>
                                @endcan
                                <div class="modal fade" id="exampleModal_{{ $longText->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">{{  $longText->category  }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <table class="table table-striped">
                                                    <tbody>
                                                        <tr>
                                                            <td>Category:</td>
                                                            <td>
                                                                <b>{{$longText->category}}</b>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2">
                                                                <div class="d-flex justify-content-between align-items-center">
                                                                    Comment:
                                                                    <ul class="list-unstyled hstack gap-1 mb-0 justify-content-lg-center justify-content-md-center justify-content-start">
                                                                        <li data-bs-toggle="tooltip" data-bs-placement="top" title="Click to copy">
                                                                            <button type="button" data-clipboard-target="#foo_{{$longText->id}}" class="btn btn-primary btn-copy btn-sm">
                                                                                <i class="bx bxs-copy" style="font-size: 16px;"></i>
                                                                            </button>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr style="text-align: left;">
                                                            <td colspan="2">
                                                                <b id="foo_{{ $longText->id }}">{{$longText->description}}</b>
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

@section('scripts')
<script src="{{ asset('assets/libs/clipboard/dist/clipboard.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var clipboard = new ClipboardJS('.btn-copy');

        clipboard.on('success', function (e) {
            e.clearSelection();
            Swal.fire({
                toast: true,
                position: 'top-right',
                icon: 'success',
                title: 'Copied!',
                showConfirmButton: false,
                timer: 1500
            });
        });

        clipboard.on('error', function (e) {
            Swal.fire({
                toast: true,
                position: 'top-right',
                icon: 'error',
                title: 'Failed to copy the text.',
                showConfirmButton: false,
                timer: 1500
            });
        });
    });
</script>
@endsection