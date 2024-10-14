@extends('layouts.admin')

@section('styles')
<style>
@media (max-width: 575px){
    .card-body .card-description{
        margin-top: 15px;
        text-align: center;
    }

    .card-body .card-description p{
        margin-bottom: 5px;
    }

    .card-body .avatar-sm {
        height: 4rem;
        width: 4rem;
    }
}

@media (max-width: 414px){
    .card-body {
        padding: 15px;
    }

    .card-body .avatar-sm {
        height: 3rem;
        width: 3rem;
    }
}
</style>
@endsection

@section('content')
<div class="row mb-3 align-items-center">
    <div class="col-6">
        <h1>Dashboard</h1>
    </div>
    <div class="col-6">
        {{-- <div class="btn-group float-right">
            <button name="filter" type="button" value="1" class="btn btn-primary waves-effect waves-light btn-sm" data-bs-toggle="modal" data-bs-target="#filterModal"><i class="bx bxs-filter-alt"></i> @lang('global.filter')</button>
            <form action="" method="get">
                <div id="filterModal" class="modal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">@lang('global.filter')</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group row align-items-center">
                                    <div class="col-lg-3 col-md-4 col-sm-3 col-12">
                                        <h6>Дата создания</h6>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-3 col-4">
                                        <select class="form-control form-control-sm" name="created_at_operator" onchange="
                                                                if(this.value == 'between'){
                                                                document.getElementById('created_at_pair').style.display = 'block';
                                                                } else {
                                                                document.getElementById('created_at_pair').style.display = 'none';
                                                                }
                                                                ">
                                            <option value="like" {{ request()->created_at_operator == '=' ? 'selected':'' }}> = </option>
                                            <option value=">" {{ request()->created_at_operator == '>' ? 'selected':'' }}> > </option>
                                            <option value="<" {{ request()->created_at_operator == '<' ? 'selected':'' }}>
                                                < </option>
                                            <option value="between" {{ request()->created_at_operator == 'between' ? 'selected':'' }}> От .. до .. </option>
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-4">
                                        <input class="form-control form-control-sm" type="date" name="created_at" value="{{ old('created_at',request()->created_at??'') }}">
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-4" id="created_at_pair" style="display: {{ request()->created_at_operator == 'between' ? 'block':'none'}}">
                                        <input class="form-control form-control-sm" type="date" name="created_at_pair" value="{{ old('created_at_pair',request()->created_at_pair??'') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" name="filter" class="btn btn-sm btn-primary">@lang('global.filtering')</button>
                                <button type="button" class="btn btn-sm btn-outline-warning float-left pull-left" id="reset_form">@lang('global.clear')</button>
                                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">@lang('global.closed')</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <a href="{{ route('dashboardIndex') }}" class="btn btn-secondary waves-effect waves-light btn-sm"><i class="bx bx-revision"></i> @lang('global.clear')</a>
        </div> --}}
    </div>
</div>
<div class="row mb-4">

      <!-- ./col -->
      <div class="col-lg-3 col-md-3 col-sm-6 col-6">
        <!-- small box -->
        <a href="#!" class="text-black card_box">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex flex-lg-row flex-md-row flex-sm-row flex-column-reverse justify-content-center">


                        <div class="flex-grow-1 card-description">
                            <p class="text-muted fw-medium">Всего сотрудников</p>
                            <h4 class="mb-0">{{ $users }}</h4>
                        </div>
                      
                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                                <span class="avatar-title">
                                    <i class="bx bxs-user-plus font-size-24"></i>
                                </span>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-6">
        <!-- small box -->
        <a href="#!" class="text-black card_box">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex flex-lg-row flex-md-row flex-sm-row flex-column-reverse justify-content-center">
                        <div class="flex-grow-1 card-description">
                            <p class="text-muted fw-medium">Всего заказов</p>
                            @if (auth()->user()->roles[0]->name != "Employee")
                            <h4 class="mb-0">{{$all_orders}}</h4>

                            @else

                            <h4 class="mb-0">{{$own_orders}}</h4>
                            @endif
                        </div>

                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                                <span class="avatar-title">
                                    <i class="bx bxs-shopping-bag font-size-24"></i>
                                </span>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
  
    <!-- ./col -->
    <div class="col-lg-3 col-md-3 col-sm-6 col-6">
        <!-- small box -->
        <a href="#!" class="text-black card_box">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex flex-lg-row flex-md-row flex-sm-row flex-column-reverse justify-content-center">
                     
                        @if (auth()->user()->roles[0]->name != "Employee")

                        <div class="flex-grow-1 card-description">
                            <p class="text-muted fw-medium">Невыполненные задачи</p>
                            <h4 class="mb-0">{{ $unclomlated_all_orders  }}</h4>
                        </div>
                        @else
                        <div class="flex-grow-1 card-description">
                            <p class="text-muted fw-medium">Невыполненные задачи</p>
                            <h4 class="mb-0">{{ $unCompleted_own_orders  }}</h4>
                        </div>



                        @endif
    
                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                                <span class="avatar-title">
                                    <i class="bx bxs-pie-chart font-size-24"></i>
                                </span>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-md-3 col-sm-6 col-6">
        <!-- small box -->
        <a href="#!" class="text-black card_box">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex flex-lg-row flex-md-row flex-sm-row flex-column-reverse justify-content-center">
                        <div class="flex-grow-1 card-description">
                            
                            <p class="text-muted fw-medium">Средний заказ</p>
                            <h4 class="mb-0">
                        @if (isset($user->roles[0]) && auth()->user()->roles[0]->name != "Employee")
                                
                                {{$monthlyAverageAllUsers}}
                                @else
                                {{ $monthlyAverage  }}
                                @endif
                                <sup>месяц</sup></h4>
                        </div>
    
                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                                <span class="avatar-title">
                                    <i class="bx bxs-pie-chart font-size-24"></i>
                                </span>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Main content -->
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="clearfix">
                    <div class="float-end">
                        <div class="input-group input-group-sm">
                            @if(auth()->user()->hasRole('Super Admin'))
                            <select class="form-select form-select-sm" id="chart-type-select">
                                <option value="line" selected>Line</option>
                                <option value="bar">Bar</option>
                                <option value="pie">Pie</option>
                            </select>
                            @else

                            <select class="form-select form-select-sm disabled" disabled id="chart-type-select">
                                <option value="line" selected>Line</option>
                                <option value="bar">Bar</option>
                                <option value="pie">Pie</option>
                            </select>
                            @endif
                        </div>
                    </div>
                    <h4 class="card-title mb-4">Top 5 Users</h4>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div id="chart-container" class="apex-charts" dir="ltr"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.content -->
@endsection

@section('scripts')
<script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
<script>
    // Assuming $topUsers is properly passed from the controller
    var topUsers = @json($topUsers);

    // Function to render the chart based on the selected type
    function renderChart(chartType) {
        var options = {
            chart: {
                height: 350,
                type: chartType,
                toolbar: {
                    show: true,
                    download: true,
                }
            },
            colors: ['#727cf5'],
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            markers: {
                size: 5,
                colors: '#727cf5',
                strokeColors: '#fff',
                strokeWidth: 2
            },
            series: [{
                name: 'Orders Count',
                data: topUsers.map(user => user.orders_count)
            }],
            xaxis: {
                categories: topUsers.map(user => user.name),
                labels: {
                    style: {
                        fontSize: '13px'
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        fontSize: '13px'
                    }
                }
            },
            grid: {
                borderColor: '#f1f3fa'
            }
        };

        // Initialize the chart
        var chart = new ApexCharts(document.querySelector("#chart-container"), options);
        chart.render();
    }

    // Initial render of chart
    var initialChartType = document.getElementById('chart-type-select').value;
    renderChart(initialChartType);

    // Add event listener to select input
    document.getElementById('chart-type-select').addEventListener('change', function() {
        // Get selected chart type
        var selectedChartType = this.value;

        // Render the chart based on the selected type
        renderChart(selectedChartType);
    });
</script>
@endsection
