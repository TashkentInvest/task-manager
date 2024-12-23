@extends('layouts.admin')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Редактировать поручение</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}" style="color: #007bff;">@lang('global.home')</a>
                        </li>
                        <li class="breadcrumb-item active">Редактировать</li>
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

                <div class="card-body">
                    <form action="{{ route('taskUpdate', $task->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('POST') <!-- Use PUT for update -->

                        <input type="hidden" name="user_id" value="{{ old('user_id', $task->user_id) }}">

                        <div class="row">

                            <div class="mb-3">
                                <label for="document_id" class="form-label">Ҳужжат:</label>
                                <select name="document_id" id="document_id" class="form-select">
                                    <option value="">-- Ҳужжат Йўқ --</option>
                                    @foreach ($documents as $doc)
                                        <option value="{{ $doc->id }}"
                                            @if (old('document_id', $task->document_id) == $doc->id) selected @endif>
                                            {{ $doc->title }}
                                            ({{ $doc->category ? $doc->category->name : 'Категория Йўқ' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            
                            <div class="col-md-6 mb-3">
                                <label>Категория</label>
                                <select class="form-control select2" name="category_id" required>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category_id', $task->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label>Назначить</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="assign_type" id="assign_role"
                                        value="role"
                                        {{ old('assign_type', $task->assign_type) == 'role' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="assign_role">Назначить по ролям</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="assign_type"
                                        id="assign_custom_users" value="custom"
                                        {{ old('assign_type', $task->assign_type) == 'custom' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="assign_custom_users">Назначить конкретным
                                        пользователям</label>
                                </div>
                            </div>
                        </div>

                        <!-- Roles select field -->
                        <div class="row" id="rolesSection"
                            style="{{ old('assign_type', $task->assign_type) == 'role' ? 'display:block;' : 'display:none;' }}">
                            <div class="col-md-12 mb-3">
                                <label>Роли</label>
                                <select name="roles[]" class="select2 form-control select2-multiple" multiple="multiple"
                                    style="width: 100%;">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->name }}"
                                            {{ in_array($role->name, old('roles', $task->roles->pluck('name')->toArray())) ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('roles')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Custom users select field -->
                        <div class="row" id="customUsersSection"
                            style="{{ old('assign_type', $task->assign_type) == 'custom' ? 'display:block;' : 'display:none;' }}">
                            <div class="col-md-12 mb-3">
                                <label>Пользователи</label>
                                <select name="users[]" class="form-control select2" multiple="multiple"
                                    style="width: 100%;">
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ in_array($user->id, old('users', $task->task_users->pluck('id')->toArray())) ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('users')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Дата выдачи</label>
                                <input type="datetime-local" name="issue_date" class="form-control"
                                    value="{{ old('issue_date', $task->issue_date) }}" required>
                                @error('issue_date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Срок выполнения (план)</label>
                                <input type="datetime-local" name="planned_completion_date" class="form-control"
                                    value="{{ old('planned_completion_date', $task->planned_completion_date) }}" required>
                                @error('planned_completion_date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                           
                            <div class="col-md-6 mb-3">
                                <label>Закрепленный файл (опционально)</label>
                                <input type="file" name="attached_file[]" class="form-control" multiple>
                                @error('attached_file.*')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            @if ($task->files && $task->files->count() > 0)
                                <div class="col-md-12">
                                    <h5>Закрепленные файлы</h5>


                                    {{-- @if (auth()->user()->roles[0]->name == 'Super Admin')
                                                    <form action="{{ route('file.delete', $file->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-link text-danger">Delete</button>
                                                    </form>
                                                @endif --}}

                                    @if ($task->files && $task->files->count() > 0)
                                        <ul class="list-group">
                                            @foreach ($task->files as $file)
                                                @php
                                                    // Build the file path
                                                    $filePath = public_path('porucheniya/' . $file->file_name);
                                                @endphp

                                                @if (file_exists($filePath))
                                                    <!-- Check if the file exists in the specified directory -->
                                                    <li>
                                                        <span class="badge badge-soft-primary font-size-16 m-1">
                                                            {{ $file->name }}
                                                        </span>
                                                        <a href="{{ asset('porucheniya/' . $file->file_name) }}"
                                                            target="_blank">View</a>
                                                        {{-- @if (auth()->user()->roles[0]->name == 'Super Admin')
                                                            <form action="{{ route('file.delete', $file->id) }}"
                                                                method="POST" style="display:inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn btn-link text-danger">Delete</button>
                                                            </form>
                                                        @endif --}}
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    @else
                                        <p>Нет загруженных файлов.</p>
                                    @endif


                                </div>
                            @endif
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Примечание (опционально)</label>
                                <textarea rows="3" name="note" class="form-control">{{ old('note', $task->note) }}</textarea>
                                @error('note')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group mt-2">
                            <button type="submit" id="submitBtn"
                                class="btn btn-success float-right">@lang('global.update')</button>
                            <a href="{{ route('monitoringIndex') }}"
                                class="btn btn-light waves-effect float-left">@lang('global.cancel')</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('input[name="assign_type"]').change(function() {
                if ($(this).val() === 'role') {
                    $('#rolesSection').show();
                    $('#customUsersSection').hide();
                } else {
                    $('#rolesSection').hide();
                    $('#customUsersSection').show();
                }
            });
        });
    </script>
@endsection
