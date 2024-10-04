<!-- Left sidebar -->
<div class="vertical-menu">
    <div data-simplebar class="h-100">
        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">MENU</li>

                <!-- Dashboard -->
                @can('dashboard.show')
                <li class="{{ Request::is('dashboard*') ? 'mm-active':'' }}">
                    <a href="{{ route('dashboardIndex') }}" class=" waves-effect {{ Request::is('dashboard*') ? 'mm-active':'' }}">
                        <i class="bx bxs-pie-chart-alt-2"></i>
                        <!-- <sub><i class="fas fa-child"></i></sub> -->
                        <span>Dashboard</span>
                    </a>
                </li>
                @endcan
                <!-- Monitoring -->
                @can('monitoring.show')
                <li class="{{ Request::is('monitoring*') ?  'mm-active':'' }}">
                    <a href="{{ route('monitoringIndex') }}" class=" waves-effect {{ Request::is('monitoring*') ? 'mm-active':'' }}">
                        <i class="bx bxs-bar-chart-alt-2"></i>
                        <!-- <sub><i class="fas fa-child"></i></sub> -->
                        <span>Monitoring</span>
                    </a>
                </li>
                @endcan

                {{-- files start --}}
                <li class="{{ Request::is('files*') ? 'mm-active':'' }}">
                    <a href="{{ route('files.index') }}" class=" waves-effect {{ Request::is('files*') ? 'mm-active':'' }}">
                        <i class="bx bxs-file"></i>
                        <!-- <sub><i class="fas fa-child"></i></sub> -->
                        <span>Files</span>
                    </a>
                </li>

                {{-- files end --}}
                <!-- Manage -->
                @canany([
                    'category.show',
                    
                    'long-text.show',
                    'employee.show',
                    'cheque.show'
                ])
                <li class="{{ (Request::is('company*') || Request::is('category*') || Request::is('driver*') || Request::is('cheque*') || Request::is('long*') || Request::is('employee*') ) ? 'mm-active':''}}">
                    <a href="javascript: void(0);" class="has-arrow waves-effect {{ (Request::is('company*') || Request::is('category*') || Request::is('driver*') || Request::is('cheque*') || Request::is('long*') || Request::is('employee*') ) ? 'mm-active':''}}">
                        <i class="bx bxs-magic-wand"></i>
                        <span>Manage</span>
                    </a>
                    <ul class="sub-menu {{ (Request::is('company*') || Request::is('category*') || Request::is('driver*') || Request::is('cheque*') || Request::is('long*') || Request::is('employee*') ) ? ' ':'d-none'}}" aria-expanded="false">
                        @can('category.show')
                        <li>
                            <a href="{{ route('categoryIndex') }}" class="{{ Request::is('category*') ? 'mm-active':'' }}">
                                <i class="bx bx-border-all" style="min-width: auto;"></i>
                                Category
                            </a>
                        </li>
                        @endcan

                        
                        
                        {{-- @can('company.show')
                        <li>
                            <a href="{{ route('companyIndex') }}" class="{{ Request::is('company*') ? 'mm-active':'' }}">
                                <i class="bx bxs-building" style="min-width: auto;"></i>
                                Company
                            </a>
                        </li>
                        @endcan

                        @can('driver.show')
                        <li>
                            <a href="{{ route('driverIndex') }}" class="{{ Request::is('driver*') ? 'mm-active':'' }}">
                                <i class="bx bxs-truck" style="min-width: auto;"></i>
                                Driver
                            </a>
                        </li>
                        @endcan --}}

                        @can('long-text.show')
                        <li>
                            <a href="{{ route('longTextIndex') }}" class="{{ Request::is('long*') ? 'mm-active':'' }}">
                                <i class="bx bx-text" style="min-width: auto;"></i>
                                Long Texts
                            </a>
                        </li>
                        @endcan

                        @can('employee.show')
                        <li>
                            <a href="{{ route('employeeIndex') }}" class="{{ Request::is('employee*') ? 'mm-active':'' }}">
                                <i class="bx bxs-user-plus" style="min-width: auto;"></i>
                                Employee
                            </a>
                        </li>
                        @endcan

                        @can('cheque.show')
                        <li>
                            <a href="{{ route('chequeIndex') }}" class="{{ Request::is('cheque*') ? 'mm-active':'' }}">
                                <i class="bx bxs-folder-open" style="min-width: auto;"></i>
                                Cheque
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
                <li class="{{ (Request::is('report*')  ) ? 'mm-active':''}}">
                    <a href="javascript: void(0);" class="has-arrow waves-effect {{ (Request::is('report*')  ) ? 'mm-active':''}}">
                        <i class="bx bx-file"></i>
                        <span>Report</span>
                    </a>
                    <ul class="sub-menu {{ (Request::is('report*') ) ? ' ':'d-none'}}" aria-expanded="false">
                        @if(auth()->user()->roles[0]->name != 'Employee')
                        <li>
                            <a href="{{ route('dailyReportIndex') }}" class="{{ Request::is('report-daily*') ? 'mm-active':'' }}">
                                <i class="bx bxs-calendar-check" style="min-width: auto;"></i>
                                Daily
                            </a>
                        </li>
                        @endif
                        
                        @can('control-report.show')
						@if(auth()->user()->roles[0]->name != "Employee")			

                        <li>
                            <a href="{{ route('reportUserIndex') }}" class="{{ Request::is('report-control*') ? 'mm-active':'' }}">
                                <i class="bx bx-border-all" style="min-width: auto;"></i>
                                Control-Report
                            </a>
                        </li>
                        @else
                            @php
                             $user = auth()->user();
                            @endphp
                        <li>
                            <a href="{{ route('controlReportIndex', ['id' => auth()->user()->id, 'name' => auth()->user()->name]) }}" class="{{ Request::is('report-control*') ? 'mm-active':'' }}">
                                <i class="bx bx-border-all" style="min-width: auto;"></i>
                                Control-Report
                            </a>
                        </li>

                        @endif
                        @endcan

                        @can('shift.show')
                        <li>
                            <a href="{{ route('shiftIndex') }}" class="{{ Request::is('report-shift*') ? 'mm-active':'' }}">
                                <i class="bx bxs-building" style="min-width: auto;"></i>
                                Shift
                            </a>
                        </li>
                        @endcan

                        @can('fines.show')
                        <li>
                            <a href="{{ route('finesIndex') }}" class="{{ Request::is('report-fines*') ? 'mm-active':'' }}">
                                <i class="bx bx-error" style="min-width: auto;"></i>
                                Fines
                            </a>
                        </li>
                        @endcan

                        @can('bonuses.show')
                        <li>
                            <a href="{{ route('bonusesIndex') }}" class="{{ Request::is('report-bonuses*') ? 'mm-active':'' }}">
                                <i class="bx bx-diamond" style="min-width: auto;"></i>
                                Bonuses
                            </a>
                        </li>
                        @endcan

                        @can('request-history.show')
                        <li>
                            <a href="{{ route('historyIndex') }}" class="{{ Request::is('report-history*') ? 'mm-active':'' }}">
                                <i class="bx bx-history" style="min-width: auto;"></i>
                                Request History
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
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
                                Default {{ auth()->user()->theme == 'default' ? '✅':'' }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('userSetTheme',[auth()->id(),'theme' => 'light']) }}">
                                <!-- <i class="fas fa-key"></i> -->
                                <i class="nav-icon fas fa-circle text-white"></i>
                                Light {{ auth()->user()->theme == 'light' ? '✅':'' }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('userSetTheme',[auth()->id(),'theme' => 'dark']) }}">
                                <!-- <i class="fas fa-key"></i> -->
                                <i class="nav-icon fas fa-circle text-gray"></i>
                                Dark {{ auth()->user()->theme == 'dark' ? '✅':'' }}
                            </a>
                        </li>
                    </ul>
                </li>

                @if (isset($user->roles[0]) && $user->roles[0]->name == "Super Admin")
                    
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