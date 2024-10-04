@extends('layouts.admin')

@section('styles')
    <style>
        .project-list-table .monitoring-table tr {
            position: relative !important;
            height: 80px;
        }

        .project-list-table tr td .progress-request {
            position: absolute;
            width: 100%;
            left: 0;
            right: 0;
            bottom: 0px;
        }

        .project-list-table tr td .progress-request .progress {
            background: #808080ab;
        }

        .project-list-table tr td .progress-request .progress.progress-xl {
            height: 20px;
            font-size: 16px;
        }
    </style>
@endsection

@section('content')
    <div class="row mb-4">
        <div class="col-lg-6 col-12">
            <div class="">
                <h3 class="text-black text-5">Monitoring</h3>
                <div class="table-responsive">
                    <table class="table project-list-table table-nowrap align-middle table-borderless">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Employee</th>
                                <th scope="col">Company</th>
                                <th scope="col">Driver</th>
                                <th scope="col">Time</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody class="monitoring-table">
                            {{-- <tr v-if="orders.length > 0" v-for="item in orders" :key="item.id">
                                <td>{{ $item->order_id }}</td>
                                <td>{{ $item->accepted_user }}</td>
                                <td>{{ $item->company_name }}</td>
                                <td>{{ $item->driver_name }}</td>
                                <td>


                                    <div class="progress-request">
                                        <div class="progress progress-xl">
                                            <vue-countdown :time="item.timeout * 1000"
                                                style="
                                                background: transparent;
                                                color: #fff;
                                                margin:0 auto;
                                                display: flex;
                                                justify-content: center;
                                                align-items: center;
                                                text-align: center;
                                                position: absolute;
                                                left: 0;
                                                right: 0;"
                                                v-slot="{ minutes, seconds }">
                                                {{ minutes }} minutes, {{ seconds }} seconds.
                                            </vue-countdown>
                                            <div class="progress-bar" role="progressbar"
                                                :style="{
                                                    width: (item.timeout / (item.category_deadline * 60)) * 100 + '%'
                                                    // backgroundColor: (item.timeout / (item.category_deadline * 60)) * 100 <= 30  ? 'red' : 'primary' 
                                                }"
                                                :aria-valuemin="0" :aria-valuemax="100"
                                                :aria-valuenow="progressPercentage">
                                            </div>


                                        </div>
                                    </div>
                                </td>

                                <td class="text-center">
                                    <form action="#!" method="post">
                                        @csrf
                                        <ul
                                            class="list-unstyled hstack gap-1 mb-0 justify-content-lg-center justify-content-md-center justify-content-start">
                                            <template
                                                v-if="(item.order_user_id === {{ auth()->user()->id }}) || ([1, 2, 3].includes({{ auth()->user()->id }}))">
                                                <li data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="@lang('global.edit')">
                                                    <button type="button" class="btn btn-sm btn-success"
                                                        @click="completeOrder(item.order_id)">
                                                        <i class="bx bxs-badge-check" style="font-size:16px;"></i>
                                                    </button>
                                                </li>

                                                <li data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="@lang('global.delete')">
                                                    <input name="_method" type="hidden" value="DELETE">
                                                    <button type="button" class="btn btn-sm btn-danger"
                                                        @click="confirmOrderDelete(item.order_id)">
                                                        <i class="bx bx-x-circle" style="font-size: 16px;"></i>
                                                    </button>
                                                </li>
                                            </template>
                                            <template v-else>
                                                <li data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="@lang('global.edit')">
                                                    <button type="button" class="btn btn-sm btn-success"
                                                        @click="completeOrder(item.order_id)" disabled>
                                                        <i class="bx bxs-badge-check" style="font-size:16px;"></i>
                                                    </button>
                                                </li>

                                                <li data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="@lang('global.delete')">
                                                    <input name="_method" type="hidden" value="DELETE">
                                                    <button type="button" class="btn btn-sm btn-danger"
                                                        @click="confirmOrderDelete(item.order_id)" disabled>
                                                        <i class="bx bx-x-circle" style="font-size: 16px;"></i>
                                                    </button>
                                                </li>
                                            </template>

                                            <li data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('global.detail')">
                                                <button type="button" class="btn btn-sm btn-warning"
                                                    @click="openModalOrder(item.order_id)">
                                                    <i class="bx bxs-show" style="font-size: 16px;"></i>
                                                </button>
                                            </li>
                                        </ul>
                                    </form>
                                </td>
                            </tr>
                            <tr v-else>
                                <td colspan="6" class="text-center">
                                    <img src="{{ asset('assets/images/empty.png') }}" alt="" width="100%">
                                </td>
                            </tr> --}}
                        </tbody>
                    </table>
                    {{-- <div v-for="item in orders" :key="item.order_id" class="modal fade"
                        :id="'exampleModalOrder_' + item.order_id" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Order ID:{{ $item->order_id }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <table class="table table-striped">
                                        <tbody>
                                            <!-- Left Info -->
                                            <tr class="text-center">
                                                <td colspan="2"><b>Left request Info:</b></td>
                                            </tr>
                                            <tr>
                                                <td><b>Task user:</b></td>
                                                <td>
                                                    {{ $item->task_user_name }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Task level:</b></td>
                                                <td>
                                                    {{ $item->task_level_name }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Description:</b></td>
                                                <td>
                                                    <b>{{ $item->task_description ? item.task_description : 'Empty' }}</b>
                                                </td>
                                            </tr>
                                            <!-- Category Info -->
                                            <tr class="text-center">
                                                <td colspan="2"><b>Category Info:</b></td>
                                            </tr>
                                            <tr>
                                                <td><b>Name:</b></td>
                                                <td>
                                                    {{ $item->category_name }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Score:</b></td>
                                                <td>
                                                    {{ $item->category_score }}
                                                </td>
                                            </tr>
                                            <!-- Company Info -->
                                            <tr class="text-center">
                                                <td colspan="2"><b>Company Info:</b></td>
                                            </tr>
                                            <tr>
                                                <td><b>Name:</b></td>
                                                <td>
                                                    {{ $item->company_name }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Owner name:</b></td>
                                                <td>
                                                    {{ $item->company_owner_name }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Owner phone:</b></td>
                                                <td>
                                                    +{{ $item->company_phone }}
                                                </td>
                                            </tr>
                                            <!-- Driver Info -->
                                            <tr class="text-center">
                                                <td colspan="2"><b>Driver Info:</b></td>
                                            </tr>
                                            <tr>
                                                <td><b>Full name:</b></td>
                                                <td>
                                                    {{ $item->driver_name }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Phone:</b></td>
                                                <td>
                                                    +{{ $item->driver_phone }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Timezone:</b></td>
                                                <td>
                                                    {{ $item->driver_eastern_time }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Truck number:</b></td>
                                                <td>
                                                    {{ $item->driver_track_num }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Comment:</b></td>
                                                <td>
                                                    <b style="color: red;">{{ $item->driver_comment ? item.driver_comment : 'Empty' }}</b>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-12">
            <div class="">

                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="text-black text-5">Left Request</h3>
                    @can('left-request.add')
                        <a href="{{ route('taskAdd') }}" class="btn btn-sm btn-success waves-effect waves-light float-right">
                            <span class="fas fa-plus-circle"></span>
                            @lang('global.add')
                        </a>
                    @endcan
                </div>
                <div class="table-responsive">

                    <table class="table project-list-table table-nowrap align-middle table-borderless">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Category</th>
                                <th scope="col">Level</th>
                                <th scope="col">Description</th>
                                
                                {{-- <th scope="col">Time</th> --}}
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- {{ console.log(tasks[1]['driver']) }} --}}
                            @forelse ($tasks as $item)
                            {{-- @dd($item) --}}
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->category->name }}</td>
                                    <td>{{ $item->level->name }}</td>
                                    <td>{{ $item->description }}</td>
                                    
                                    <td class="text-center">
                                        <form :action="getTaskDestroyRoute(item.id)" method="post">
                                            @csrf
                                            <ul
                                                class="list-unstyled hstack gap-1 mb-0 justify-content-lg-center justify-content-md-center justify-content-start">
                                                <li data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Qabul qilish">
                                                    <button @click="onSubmit(item.id, {{ auth()->user()->id }})"
                                                        type="button" class="btn btn-sm btn-success">
                                                        <i class="bx bxs-badge-check" style="font-size:16px;"></i>
                                                    </button>
                                                </li>

                                                <li data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="@lang('global.edit')">
                                                    <a :href="getTaskEditRoute(item.id)" class="btn btn-sm btn-info">
                                                        <i class="bx bxs-edit" style="font-size:16px;"></i>
                                                    </a>
                                                </li>

                                                <li data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="@lang('global.delete')">
                                                    <input name="_method" type="hidden" value="DELETE">
                                                    <button type="button" class="btn btn-sm btn-danger"
                                                        @click="confirmDelete(item.id)">
                                                        <i class="bx bxs-trash" style="font-size: 16px;"></i>
                                                    </button>
                                                </li>

                                                <li data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="@lang('global.detail')">
                                                    <button type="button" class="btn btn-sm btn-warning"
                                                    data-bs-toggle="modal" data-bs-target="#exampleModal_{{$item->id}}">
                                                        <i class="bx bxs-show" style="font-size: 16px;"></i>
                                                    </button>

                                                    {{-- <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#exampleModal_{{$item->id}}"> --}}

                                                </li>
                                            </ul>
                                        </form>

                                        <div class="modal fade" id="exampleModal_{{$item->id}}" tabindex="-1" aria-labelledby="exampleModalLabel"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-scrollable modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Lert request ID:{{ $item->id }}
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <table class="table table-striped">
                                                        <tbody>
                                                            <!-- Left Info -->
                                                            <tr class="text-center">
                                                                <td colspan="2"><b>Left request Info:</b></td>
                                                            </tr>
                                                            <tr>
                                                                {{-- @dd($item) --}}
                                                                <td><b>Task level:</b></td>
                                                                <td>
                                                                    {{ $item->level->name }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Description:</b></td>
                                                                <td>
                                                                    <b>{{ $item->description ? $item->description : 'Empty' }}</b>
                                                                </td>
                                                            </tr>
                                                            <!-- Category Info -->
                                                            <tr class="text-center">
                                                                <td colspan="2"><b>Category Info:</b></td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Name:</b></td>
                                                                <td>
                                                                    {{ $item->category->name }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Score:</b></td>
                                                                <td>
                                                                    {{ $item->category->score }}
                                                                </td>
                                                            </tr>
                                                            <!-- Company Info -->
                                                         
                                                            <!-- Driver Info -->
                                                            <tr class="text-center">
                                                                <td colspan="2"><b>Driver Info:</b></td>
                                                            </tr>
                                                         
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </td>
                                </tr>
                             
                            @empty

                            <tr>
                                <td colspan="5" class="text-center">
                                    <img src="{{ asset('assets/images/empty.png') }}" alt="" width="100%">
                                </td>
                            </tr>
                            @endforelse

                        </tbody>
                    </table>

                  
                </div>
            </div>
        </div>
    </div>
@endsection
