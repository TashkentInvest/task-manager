<!-- Left sidebar -->
<div class="vertical-menu">
    <div data-simplebar class="h-100">
        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">MENU</li>

                <!-- Dashboard -->
                {{-- @can('dashboard.show')
                <li class="{{ Request::is('dashboard*') ? 'mm-active':'' }}">
                    <a href="{{ route('dashboardIndex') }}" class=" waves-effect {{ Request::is('dashboard*') ? 'mm-active':'' }}">
                        <i class="bx bxs-pie-chart-alt-2"></i>
                        <!-- <sub><i class="fas fa-child"></i></sub> -->
                        <span>Dashboard</span>
                    </a>
                </li>
                @endcan --}}
                <!-- Monitoring -->
                @can('qarorlar.show')
                <li class="{{ Request::is('qarorlar*') ?  'mm-active':'' }}">
                    <a href="{{ route('qarorlarIndex') }}" class=" waves-effect {{ Request::is('qarorlar*') ? 'mm-active':'' }}">
                        <i class="bx bxs-file"></i>
                        <!-- <sub><i class="fas fa-child"></i></sub> -->
                        <span>Қарорлар</span>
                    </a>
                </li>
                @endcan
                @can('monitoring.show')
                <li class="{{ Request::is('monitoring*') ?  'mm-active':'' }}">
                    <a href="{{ route('monitoringIndex') }}" class=" waves-effect {{ Request::is('monitoring*') ? 'mm-active':'' }}">
                        <i class="bx bxs-bar-chart-alt-2"></i>
                        <!-- <sub><i class="fas fa-child"></i></sub> -->
                        <span>Мониторинг</span>
                    </a>
                </li>
                @endcan

                {{-- files start --}}
                {{-- <li class="{{ Request::is('files*') ? 'mm-active':'' }}">
                    <a href="{{ route('files.index') }}" class=" waves-effect {{ Request::is('files*') ? 'mm-active':'' }}">
                        <i class="bx bxs-file"></i>
                        <!-- <sub><i class="fas fa-child"></i></sub> -->
                        <span>Файлы</span>
                    </a>
                </li> --}}

                {{-- files end --}}
                <!-- Manage -->
                @canany([
                    'category.show',
                    
                    'long-text.show',
                    'employee.show',
                ])
                <li class="{{ (Request::is('company*') || Request::is('category*') || Request::is('driver*') || Request::is('cheque*') || Request::is('long*') || Request::is('employee*') ) ? 'mm-active':''}}">
                    <a href="javascript: void(0);" class="has-arrow waves-effect {{ (Request::is('company*') || Request::is('category*') || Request::is('driver*') || Request::is('cheque*') || Request::is('long*') || Request::is('employee*') ) ? 'mm-active':''}}">
                        <i class="bx bxs-magic-wand"></i>
                        <span>Управлять</span>
                    </a>
                    <ul class="sub-menu {{ (Request::is('company*') || Request::is('category*') || Request::is('driver*') || Request::is('cheque*') || Request::is('long*') || Request::is('employee*') ) ? ' ':'d-none'}}" aria-expanded="false">
                        @can('category.show')
                        <li>
                            <a href="{{ route('categoryIndex') }}" class="{{ Request::is('category*') ? 'mm-active':'' }}">
                                <i class="bx bx-border-all" style="min-width: auto;"></i>
                                Категория
                            </a>
                        </li>
                        @endcan

                        
                  
                        {{-- @can('long-text.show')
                        <li>
                            <a href="{{ route('longTextIndex') }}" class="{{ Request::is('long*') ? 'mm-active':'' }}">
                                <i class="bx bx-text" style="min-width: auto;"></i>
                                Long Texts
                            </a>
                        </li>
                        @endcan --}}

                        @can('employee.show')
                        <li>
                            <a href="{{ route('employeeIndex') }}" class="{{ Request::is('employee*') ? 'mm-active':'' }}">
                                <i class="bx bxs-user-plus" style="min-width: auto;"></i>
                                Сотрудники
                            </a>
                        </li>
                        @endcan

                   
                    </ul>
                </li>
                @endcanany

                <!-- Report -->
                @canany([
                    'control-report.show',
                    'shift.show',
                    'fines.show',
                    'bonuses.show',
                    'request-history.show'
                ])
 
                @endcanany
                
                <!-- Theme -->
                <li class="menu-title">@lang('global.theme')</li>

                <li class="">
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="fas fa-palette"></i>
                        <span>@lang('global.theme')</span>
                    </a>
                    <ul class="sub-menu d-none" aria-expanded="false">
                        <li>
                            <a href="{{ route('userSetTheme',[auth()->id(),'theme' => 'default']) }}">
                                <!-- <i class="fas fa-key"></i> -->
                                <i class="nav-icon fas fa-circle text-info"></i>
                                По умолчанию
 {{ auth()->user()->theme == 'default' ? '✅':'' }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('userSetTheme',[auth()->id(),'theme' => 'light']) }}">
                                <!-- <i class="fas fa-key"></i> -->
                                <i class="nav-icon fas fa-circle text-white"></i>
                                Светлый {{ auth()->user()->theme == 'light' ? '✅':'' }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('userSetTheme',[auth()->id(),'theme' => 'dark']) }}">
                                <!-- <i class="fas fa-key"></i> -->
                                <i class="nav-icon fas fa-circle text-gray"></i>
                                Темный
 {{ auth()->user()->theme == 'dark' ? '✅':'' }}
                            </a>
                        </li>
                    </ul>
                </li>

                @if (auth()->user()->roles[0]->name == "Super Admin")
                    
                @canany([
                    'permission.show',
                    'roles.show',
                    'user.show'
                ])
                <li class="menu-title">API Users</li>
                <li class="{{ (Request::is('permission*') || Request::is('role*') || Request::is('user*')) ? 'mm-active':''}}">
                    <a href="javascript: void(0);" class="has-arrow waves-effect {{ (Request::is('permission*') || Request::is('role*') || Request::is('user*')) ? 'mm-active':''}}">
                        <i class="fas fa-users-cog"></i>
                        <span>@lang('cruds.userManagement.title')</span>
                    </a>
                    <ul class="sub-menu {{ (Request::is('permission*') || Request::is('role*') || Request::is('user*')) ? ' ':'d-none'}}" aria-expanded="false">
                        @can('permission.show')
                            <li>
                                <a href="{{ route('permissionIndex') }}" class="{{ Request::is('permission*') ? 'mm-active':'' }}">
                                    <i class="bx bxs-key" style="font-size: 14px; min-width: auto;"></i>
                                    @lang('cruds.permission.title_singular')
                                </a>
                            </li>
                        @endcan
                        @can('roles.show')
                            <li>
                                <a href="{{ route('roleIndex') }}" class="{{ Request::is('role*') ? 'mm-active':'' }}">
                                    
                                    <i class="bx bxs-lock-alt" style="font-size: 14px; min-width: auto;"></i>
                                    @lang('cruds.role.fields.roles')
                                </a>
                            </li>
                        @endcan

                        @can('user.show')
                            <li>
                                <a href="{{ route('userIndex') }}" class="{{ Request::is('user*') ? 'mm-active':'' }}">
                                    <!-- <i class="fas fa-user-friends"></i> -->
                                    <i class="bx bxs-user-plus" style="font-size: 14px; min-width: auto;"></i>
                                    @lang('cruds.user.title')
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
                @endcanany

                @can('api-user.view')
                <li class="{{ Request::is('api-user*') ? 'mm-active':'' }}">
                    <a href="{{ route('api-userIndex') }}" class=" waves-effect {{ Request::is('api-users*') ? 'mm-active':'' }}">
                        <i class="fas fa-cog"></i>
                        <!-- <sub><i class="fas fa-child"></i></sub> -->
                        <span>API Users</span>
                    </a>
                </li>
                @endcan

                @endif

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>