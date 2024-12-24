@extends('layouts.admin')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Добавить поручение</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}" style="color: #007bff;">@lang('global.home')</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('api-userIndex') }}" style="color: #007bff;">Контроль категории</a>
                        </li>
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

                <div class="card-body">
                    <form id="taskForm" action="{{ route('taskCreate') }}" method="post" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="user_id" class="form-control" value="{{ old('user_id') }}">


                        <div class="row">


                            <div class="mb-3">
                                <label for="document_id" class="form-label">Ҳужжат (Кирувчи):</label>
                                <select name="document_id" id="document_id" class="form-control select2" required>
                                    <option value="">-- Танланг --</option>
                                    @foreach ($documents as $doc)
                                        <option value="{{ $doc->id }}" @selected(old('document_id') == $doc->id)>
                                            {{ $doc->title }}
                                            ({{ $doc->category ? $doc->category->name : 'Категория йўқ' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Категория</label>
                                <select class="form-control select2" name="category_id" required>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                                        value="role" checked>
                                    <label class="form-check-label" for="assign_role">Назначить по ролям</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="assign_type"
                                        id="assign_custom_users" value="custom">
                                    <label class="form-check-label" for="assign_custom_users">Назначить конкретным
                                        пользователям</label>
                                </div>
                            </div>
                        </div>

                        <!-- Roles select field -->
                        <div class="row" id="rolesSection">
                            <div class="col-md-12 mb-3">
                                <label>@lang('cruds.role.fields.roles')</label>
                                <select name="roles[]" class="select2 form-control select2-multiple" multiple="multiple"
                                    style="width: 100%;">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->name }}"
                                            {{ in_array($role->name, old('roles', [])) ? 'selected' : '' }}>
                                            {{ $role->name }}
                                            ({{ $role->title ?? '' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('roles')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Custom users select field, hidden by default -->
                        <div class="row" id="customUsersSection" style="display:none;">
                            <div class="col-md-12 mb-3">
                                <label>Custom Users</label>
                                <select name="users[]" class="form-control select2" multiple="multiple"
                                    style="width: 100%;">
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ in_array($user->id, old('users', [])) ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('users')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <script>
                            // Toggle between roles and custom users
                            document.getElementById('assign_role').addEventListener('change', function() {
                                document.getElementById('rolesSection').style.display = 'block';
                                document.getElementById('customUsersSection').style.display = 'none';
                            });

                            document.getElementById('assign_custom_users').addEventListener('change', function() {
                                document.getElementById('rolesSection').style.display = 'none';
                                document.getElementById('customUsersSection').style.display = 'block';
                            });
                        </script>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Дата выдачи</label>
                                <input type="date" name="issue_date" class="form-control"
                                    value="{{ old('issue_date', now()->format('Y-m-d')) }}" required>
                                @error('issue_date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="col-md-6 mb-3">
                                <label>Срок выполнения (план)</label>
                                <input type="date" name="planned_completion_date" class="form-control"
                                    value="{{ old('planned_completion_date') }}" required>
                                @error('planned_completion_date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                         
                            <div class="col-md-6 mb-3">
                                <label>Закрепленный файл (опционально)</label>
                                <input type="file" name="attached_file[]" class="form-control" multiple>

                                @error('attached_file')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Примечание (опционально)</label>
                                <textarea rows="3" name="note" class="form-control">{{ old('note') }}</textarea>
                                @error('note')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>


                        </div>



                        <div class="form-group mt-2">
                            <button type="submit" id="submitBtn"
                                class="btn btn-success float-right">@lang('global.save')</button>
                            <a href="{{ route('monitoringIndex') }}"
                                class="btn btn-light waves-effect float-left">@lang('global.cancel')</a>
                        </div>
                    </form>
                    <script>
                        document.getElementById('taskForm').addEventListener('submit', function() {
                            document.getElementById('submitBtn').disabled = true; // Disable the button
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
@endsection
