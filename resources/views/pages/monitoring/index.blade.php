@extends('layouts.admin')

@section('styles')
<style>
    .project-list-table .monitoring-table tr {
        position: relative !important;
        height: 80px;
    }

    .project-list-table tr td .progress-request{
        position: absolute;
        width: 100%;
        left: 0;
        right: 0;
        bottom: 0px;
    }

    .project-list-table tr td .progress-request .progress{
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
                            <tr v-if="orders.length > 0" v-for="item in orders" :key="item.id">
                                <td>@{{ item.order_id }}</td>
                                <td>@{{ item.accepted_user }}</td>
                                <td>@{{ item.company_name }}</td>
                                <td>@{{ item.driver_name }}</td>
                                <td>
                                
                                    {{-- @{{console.log('divide d: ' +  (item.timeout / (item.category_deadline * 60) ) * 100)}} --}}
                                    {{--           hozirgi timout /  ( umummiy berilgan deadline * second qlsh uchun ) * 100  --}}

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
                                                @{{ minutes }} minutes, @{{ seconds }} seconds.
                                            </vue-countdown>
                                            <div class="progress-bar"
                                            role="progressbar"
                                            :style="{ 
                                                width: (item.timeout / (item.category_deadline * 60)) * 100 + '%'
                                                // backgroundColor: (item.timeout / (item.category_deadline * 60)) * 100 <= 30  ? 'red' : 'primary' 
                                            }"
                                            :aria-valuemin="0"
                                            :aria-valuemax="100"
                                            :aria-valuenow="progressPercentage">
                                       </div>
                                       
                                            
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="text-center">
                                    <form action="#!" method="post">
                                        @csrf
                                        <ul class="list-unstyled hstack gap-1 mb-0 justify-content-lg-center justify-content-md-center justify-content-start">
                                            <template v-if="(item.order_user_id === {{ auth()->user()->id }}) || ([1, 2, 3].includes({{ auth()->user()->id }}))">
                                                <li data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('global.edit')">
                                                    <button type="button" class="btn btn-sm btn-success"
                                                    @click="completeOrder(item.order_id)">
                                                        <i class="bx bxs-badge-check" style="font-size:16px;"></i>
                                                    </button>
                                                </li>
                                                
                                                <li data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('global.delete')">
                                                    <input name="_method" type="hidden" value="DELETE">
                                                    <button type="button" class="btn btn-sm btn-danger" 
                                                    @click="confirmOrderDelete(item.order_id)">
                                                        <i class="bx bx-x-circle" style="font-size: 16px;"></i>
                                                    </button>
                                                </li>
                                            </template>
                                            <template v-else>
                                                <li data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('global.edit')">
                                                    <button type="button" class="btn btn-sm btn-success"
                                                    @click="completeOrder(item.order_id)" disabled>
                                                        <i class="bx bxs-badge-check" style="font-size:16px;"></i>
                                                    </button>
                                                </li>
                                                
                                                <li data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('global.delete')">
                                                    <input name="_method" type="hidden" value="DELETE">
                                                    <button type="button" class="btn btn-sm btn-danger" 
                                                    @click="confirmOrderDelete(item.order_id)" disabled>
                                                        <i class="bx bx-x-circle" style="font-size: 16px;"></i>
                                                    </button>
                                                </li>
                                            </template>

                                            <li data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('global.detail')">
                                                <button type="button" class="btn btn-sm btn-warning" @click="openModalOrder(item.order_id)">
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
                            </tr>
                        </tbody>
                    </table>
                    <div v-for="item in orders" :key="item.order_id" class="modal fade" :id="'exampleModalOrder_' + item.order_id" tabindex="-1" 
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Order ID:@{{item.order_id}}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                                    @{{ item.task_user_name }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Task level:</b></td>
                                                <td>
                                                    @{{ item.task_level_name }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Description:</b></td>
                                                <td>
                                                    <b>@{{ item.task_description ? item.task_description : 'Empty' }}</b>
                                                </td>
                                            </tr>
                                            <!-- Category Info -->
                                            <tr class="text-center">
                                                <td colspan="2"><b>Category Info:</b></td>
                                            </tr>
                                            <tr>
                                                <td><b>Name:</b></td>
                                                <td>
                                                    @{{ item.category_name }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Score:</b></td>
                                                <td>
                                                    @{{ item.category_score }}
                                                </td>
                                            </tr>
                                            <!-- Company Info -->
                                            <tr class="text-center">
                                                <td colspan="2"><b>Company Info:</b></td>
                                            </tr>
                                            <tr>
                                                <td><b>Name:</b></td>
                                                <td>
                                                    @{{ item.company_name }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Owner name:</b></td>
                                                <td>
                                                    @{{ item.company_owner_name }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Owner phone:</b></td>
                                                <td>
                                                    +@{{ item.company_phone }}
                                                </td>
                                            </tr>
                                            <!-- Driver Info -->
                                            <tr class="text-center">
                                                <td colspan="2"><b>Driver Info:</b></td>
                                            </tr>
                                            <tr>
                                                <td><b>Full name:</b></td>
                                                <td>
                                                    @{{ item.driver_name }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Phone:</b></td>
                                                <td>
                                                    +@{{ item.driver_phone }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Timezone:</b></td>
                                                <td>
                                                    @{{ item.driver_eastern_time }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Truck number:</b></td>
                                                <td>
                                                    @{{ item.driver_track_num }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Comment:</b></td>
                                                <td>
                                                    <b style="color: red;">@{{ item.driver_comment ? item.driver_comment : 'Empty' }}</b>
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
                            <th scope="col">Company</th>
                            <th scope="col">Driver</th>
                            <th scope="col">Time</th>
                            <th scope="col">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            {{-- @{{console.log(tasks[1]['driver'])}} --}}
                            <tr v-if="tasks.length > 0" v-for="item in tasks" :key="item.id" :class="getClass(item.type_request)">
                                <td>@{{ item.id }}</td>
                                <td>@{{ item.company.name }}</td>
                                <td>@{{ item.driver.full_name }}</td>
                                <td>@{{ item.category.deadline }} min</td>
                                <td class="text-center">
                                    <form :action="getTaskDestroyRoute(item.id)" method="post">
                                        @csrf
                                        <ul class="list-unstyled hstack gap-1 mb-0 justify-content-lg-center justify-content-md-center justify-content-start">
                                            <li data-bs-toggle="tooltip" data-bs-placement="top" title="Qabul qilish">
                                                <button @click="onSubmit(item.id, {{ auth()->user()->id }})" type="button" class="btn btn-sm btn-success">
                                                <i class="bx bxs-badge-check" style="font-size:16px;"></i>
                                                </button>
                                            </li>

                                            <li data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('global.edit')">
                                                <a :href="getTaskEditRoute(item.id)" class="btn btn-sm btn-info">
                                                <i class="bx bxs-edit" style="font-size:16px;"></i>
                                                </a>
                                            </li>

                                            <li data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('global.delete')">
                                                <input name="_method" type="hidden" value="DELETE">
                                                <button type="button" class="btn btn-sm btn-danger" 
                                                @click="confirmDelete(item.id)">
                                                <i class="bx bxs-trash" style="font-size: 16px;"></i>
                                                </button>
                                            </li>

                                            <li data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('global.detail')">
                                                <button type="button" class="btn btn-sm btn-warning" @click="openModal(item.driver.id)">
                                                <i class="bx bxs-show" style="font-size: 16px;"></i>
                                                </button>
                                            </li>
                                        </ul>
                                    </form>
                                </td>
                            </tr>
                            <tr v-else>
                                <td colspan="5" class="text-center">
                                    <img src="{{ asset('assets/images/empty.png') }}" alt="" width="100%">
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div v-for="item in tasks" :key="item.driver.id" class="modal fade" :id="'exampleModal_' + item.driver.id" tabindex="-1" 
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Lert request ID:@{{item.id}}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <table class="table table-striped">
                                    <tbody>
                                        <!-- Left Info -->
                                        <tr class="text-center">
                                            <td colspan="2"><b>Left request Info:</b></td>
                                        </tr>
                                        <tr>
                                            <td><b>Task level:</b></td>
                                            <td>
                                                @{{ item.level.name }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>Description:</b></td>
                                            <td>
                                                <b>@{{ item.description ? item.description : 'Empty' }}</b>
                                            </td>
                                        </tr>
                                        <!-- Category Info -->
                                        <tr class="text-center">
                                            <td colspan="2"><b>Category Info:</b></td>
                                        </tr>
                                        <tr>
                                            <td><b>Name:</b></td>
                                            <td>
                                                @{{ item.category.name }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>Score:</b></td>
                                            <td>
                                                @{{ item.category.score }}
                                            </td>
                                        </tr>
                                        <!-- Company Info -->
                                        <tr class="text-center">
                                            <td colspan="2"><b>Company Info:</b></td>
                                        </tr>
                                        <tr>
                                            <td><b>Name:</b></td>
                                            <td>
                                                @{{ item.company.name }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>Owner name:</b></td>
                                            <td>
                                                @{{ item.company.owner_name }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>Owner phone:</b></td>
                                            <td>
                                                +@{{ item.company.phone }}
                                            </td>
                                        </tr>
                                        <!-- Driver Info -->
                                        <tr class="text-center">
                                            <td colspan="2"><b>Driver Info:</b></td>
                                        </tr>
                                        <tr>
                                            <td><b>Full name:</b></td>
                                            <td>
                                                @{{ item.driver.full_name }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>Phone:</b></td>
                                            <td>
                                                +@{{ item.driver.phone }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>Timezone:</b></td>
                                            <td>
                                                @{{ item.driver.eastern_time }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>Truck number:</b></td>
                                            <td>
                                                @{{ item.driver.track_num }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>Comment:</b></td>
                                            <td>
                                                <b style="color: red;">@{{ item.driver.comment ? item.driver.comment : 'Empty' }}</b>
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
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script src="https://unpkg.com/@chenfengyuan/vue-countdown@2"></script>
<script>
    const app = Vue.createApp({
        components: {
            VueCountdown
        },
        setup() {
            const tasks = Vue.ref([]);
            const orders = Vue.ref([]);
            const countdown = Vue.ref('');
            const progressWidth = Vue.ref('');
            const onSubmit = (id, user_id) => {
                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showDenyButton: false,
                    showCancelButton: true,
                    confirmButtonText: "Save",
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: "Loading...",
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            },
                        });
                        const token = document.head.querySelector('meta[name="csrf-token"]').content;
                        const headers = {
                            'Content-Type': 'application/json',
                            'Authorization': 'Bearer ' + token,
                        };
                        fetch(`/api/task/accept/${id}`, {
                            method: 'POST',
                            headers: headers,
                            body: JSON.stringify({ '_token': '{!! auth()->user()->password !!}', 'user_id': '{!! auth()->user()->id !!}'})
                        })
                        .then(response => response.json())
                        .then(data => {
                            Swal.close();
                            if(data.status === 0){
                                Swal.fire({
                                    icon: 'success',
                                    title: data.message,
                                    toast: true,
                                    position: 'top',
                                    showConfirmButton: false,
                                    timer: 1000,
                                });
                                getAllTasks();
                                getAllOrders();
                            }else{
                                Swal.fire({
                                    icon: 'error',
                                    title: data.message,
                                    toast: true,
                                    position: 'top',
                                    showConfirmButton: false,
                                    timer: 1000,
                                })
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                icon: 'error',
                                title: error.message,
                                toast: true,
                                position: 'top',
                                showConfirmButton: false,
                                timer: 1000,
                            })
                        });
                    }
                });
            };
            const getAllTasks = () => {
                const token = document.head.querySelector('meta[name="csrf-token"]').content;
                const headers = {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + token,
                };
                const userId = '{{ auth()->user()->id }}';
                const password = '{{ auth()->user()->password }}';
                const url = `/api/get/tasks?_token=${encodeURIComponent(password)}&user_id=${encodeURIComponent(userId)}`;

                fetch(url, {
                    method: 'GET',
                    headers: headers,
                })
                .then(response => response.json())
                .then(data => {
                    // Swal.close();
                    tasks.value = data.data;
                })
                .catch(error => {
                    console.log(error.message);
                });
            };

            // Dev TIMER LOGIC STARTS

            const getAllOrders = () => {
                const token = document.head.querySelector('meta[name="csrf-token"]').content;
                const headers = {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + token,
                };
                const userId = '{{ auth()->user()->id }}';
                const password = '{{ auth()->user()->password }}';
                const url = `/api/get/orders?_token=${encodeURIComponent(password)}&user_id=${encodeURIComponent(userId)}`;

                fetch(url, {
                    method: 'POST',
                    headers: headers,
                    body: JSON.stringify({ '_token': '{!! auth()->user()->password !!}', 'user_id': '{!! auth()->user()->id !!}'})
                })
                .then(response => response.json())
                .then(data => {
                    orders.value = data.data;
                    orders.value.forEach((order) => {
                            let diff_seconds = parseInt((new Date(order.order_deadline).getTime() - new Date().getTime()) / 1000);
                            order.timeout = diff_seconds;
                            // console.log(diff_seconds)

                            if (order.timeout <= 0) {
                                // console.log('ending')
                                updateOrderStatus(order.order_id); 
                            }
                            
                        });

                        
                })
                .catch(error => {
                    console.log(error.message);
                });
            };

            const updateOrderStatus = (orderId) => {
                const token = document.head.querySelector('meta[name="csrf-token"]').content;
                const headers = {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + token,
                };
                fetch(`/api/order/update/${orderId}`, {
                    method: 'POST',
                    headers: headers,
                    body: JSON.stringify({ '_token': '{!! auth()->user()->password !!}', 'user_id': '{!! auth()->user()->id !!}'})
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if(data.status === 0){
                        getAllOrders();
                        getAllTasks();
                    } else {
                        // Handle error if needed
                    }
                })
                .catch(error => {
                    // Handle error if needed
                    console.error('There was a problem with the fetch operation:', error);
                });
            };

            // Dev TIMER LOGIC ENDS



            const getClass = (type_request) => {
                if (type_request === 2) {
                    return 'bg-danger text-white';
                } else if (type_request === 1) {
                    return 'bg-secondary text-white';
                } else {
                    return 'bg-warning text-white'; 
                }
            };

            const getTaskDestroyRoute = (taskId) => {
                return `/task/delete/${taskId}`;
            };
            const getTaskEditRoute = (taskId) => {
                return `/task/${taskId}/edit`;
            };
            const confirmDelete = (taskId) => {
                Swal.fire({
                    title: "Are you sure?",
                    icon: "warning",
                    showDenyButton: false,
                    showCancelButton: true,
                    confirmButtonText: "Save",
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: "Loading...",
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            },
                        });
                        const token = document.head.querySelector('meta[name="csrf-token"]').content;
                        const headers = {
                            'Content-Type': 'application/json',
                            'Authorization': 'Bearer ' + token,
                        };
                        fetch(`/api/task/delete/${taskId}`, {
                            method: 'DELETE',
                            headers: headers,
                            body: JSON.stringify({ '_token': '{!! auth()->user()->password !!}', 'user_id': '{!! auth()->user()->id !!}'})
                        })
                        .then(response => response.json())
                        .then(data => {
                            Swal.close();
                            if(data.status === 0){
                                Swal.fire({
                                    icon: 'success',
                                    title: data.message,
                                    toast: true,
                                    position: 'top',
                                    showConfirmButton: false,
                                    timer: 1000,
                                });
                                getAllTasks();
                            }else{
                                Swal.fire({
                                    icon: 'error',
                                    title: data.message,
                                    toast: true,
                                    position: 'top',
                                    showConfirmButton: false,
                                    timer: 1000,
                                })
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                icon: 'error',
                                title: error.message,
                                toast: true,
                                position: 'top',
                                showConfirmButton: false,
                                timer: 1000,
                            })
                        });
                    }
                });
            };
            const confirmOrderDelete = (orderId) => {
                Swal.fire({
                    title: "Are you sure?",
                    icon: "warning",
                    showDenyButton: false,
                    showCancelButton: true,
                    confirmButtonText: "Save",
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: "Loading...",
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            },
                        });
                        const token = document.head.querySelector('meta[name="csrf-token"]').content;
                        const headers = {
                            'Content-Type': 'application/json',
                            'Authorization': 'Bearer ' + token,
                        };
                        fetch(`/api/order/delete/${orderId}`, {
                            method: 'DELETE',
                            headers: headers,
                            body: JSON.stringify({ '_token': '{!! auth()->user()->password !!}'})
                        })
                        .then(response => response.json())
                        .then(data => {
                            Swal.close();
                            if(data.status === 0){
                                Swal.fire({
                                    icon: 'success',
                                    title: data.message,
                                    toast: true,
                                    position: 'top',
                                    showConfirmButton: false,
                                    timer: 1000,
                                });
                                getAllOrders();
                                getAllTasks();
                            }else{
                                Swal.fire({
                                    icon: 'error',
                                    title: data.message,
                                    toast: true,
                                    position: 'top',
                                    showConfirmButton: false,
                                    timer: 1000,
                                })
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                icon: 'error',
                                title: error.message,
                                toast: true,
                                position: 'top',
                                showConfirmButton: false,
                                timer: 1000,
                            })
                        });
                    }
                });
            };
            const completeOrder = (orderId) => {
                Swal.fire({
                    title: "Are you sure?",
                    icon: "warning",
                    showDenyButton: false,
                    showCancelButton: true,
                    confirmButtonText: "Save",
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: "Loading...",
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            },
                        });
                        const token = document.head.querySelector('meta[name="csrf-token"]').content;
                        const headers = {
                            'Content-Type': 'application/json',
                            'Authorization': 'Bearer ' + token,
                        };
                        fetch(`/api/order/complete/${orderId}`, {
                            method: 'POST',
                            headers: headers,
                            body: JSON.stringify({ '_token': '{!! auth()->user()->password !!}', 'user_id': '{!! auth()->user()->id !!}'})
                        })
                        .then(response => response.json())
                        .then(data => {
                            Swal.close();
                            if(data.status === 0){
                                Swal.fire({
                                    icon: 'success',
                                    title: data.message,
                                    toast: true,
                                    position: 'top',
                                    showConfirmButton: false,
                                    timer: 1000,
                                });
                                getAllOrders();
                                getAllTasks();
                            }else{
                                Swal.fire({
                                    icon: 'error',
                                    title: data.message,
                                    toast: true,
                                    position: 'top',
                                    showConfirmButton: false,
                                    timer: 1000,
                                })
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                icon: 'error',
                                title: error.message,
                                toast: true,
                                position: 'top',
                                showConfirmButton: false,
                                timer: 1000,
                            })
                        });
                    }
                });
            };
            const openModal = (driverId) => {
                $(`#exampleModal_${driverId}`).modal('show');
            };
            const openModalOrder = (orderId) => {
                $(`#exampleModalOrder_${orderId}`).modal('show');
            };
            const updateCountdown = () => {
                const now = new Date().getTime();
                orders.value.forEach((item) => {
                    const deadlineMinutes = parseInt(item.category_deadline);
                    const deadline = now + deadlineMinutes * 60 * 1000;

                    // Calculate the remaining time in milliseconds
                    const difference = deadline - now;

                    // Calculate minutes and seconds
                    const minutes = Math.floor(difference / (1000 * 60));
                    const seconds = Math.floor((difference % (1000 * 60)) / 1000);

                    // Create countdown string and add it to the item
                    item.countdown = `${minutes}m ${seconds}s`;

                    // Calculate the progress width as a percentage and add it to the item
                    const progressWidth = ((difference / (deadlineMinutes * 60 * 1000)) * 100).toFixed(2);
                    item.progressWidth = `${progressWidth}%`;
                });
            };
            Vue.onMounted(() => {
                getAllTasks();
                getAllOrders();
                setInterval(updateCountdown, 1000);
            });

            setInterval(() => {
                getAllTasks();
                getAllOrders();
            }, 5000);
            const expiryDate = new Date('2024-04-01T00:00:00Z'); // Set your expiry date here
            const countdownFormat = 'D days H:MM:SS'; // Adjust format as needed
            return {
                expiryDate,
                countdownFormat,
                tasks,
                orders,
                getAllTasks,
                onSubmit,
                getClass,
                getTaskDestroyRoute,
                getTaskEditRoute,
                confirmDelete,
                confirmOrderDelete,
                openModal,
                openModalOrder,
                getAllOrders,
                countdown,
                progressWidth,
                completeOrder
            };
        }
    });
    app.component('vue-countdown', VueCountdown);
    const vm = app.mount('#app');
</script>
@endsection