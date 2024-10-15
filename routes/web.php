<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Blade\UserController;
use App\Http\Controllers\Blade\RoleController;
use App\Http\Controllers\Blade\PermissionController;
use App\Http\Controllers\Blade\HomeController;
use App\Http\Controllers\Blade\ApiUserController;
use App\Http\Controllers\Blade\CategoryController;
use App\Http\Controllers\Blade\DashboardController;
use App\Http\Controllers\Blade\MonitoringController;
use App\Http\Controllers\Blade\EmployeeController;
use App\Http\Controllers\Blade\LongTextController;
use App\Http\Controllers\Blade\TaskController;
use App\Http\Controllers\Blade\OrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DemoController;
use App\Http\Controllers\FileDownloadController;
use App\Http\Controllers\FinesController;
use App\Http\Controllers\FileController;

/*
|--------------------------------------------------------------------------
| Blade (front-end) Routes
|--------------------------------------------------------------------------
|
| Here is we write all routes which are related to web pages
| like UserManagement interfaces, Diagrams and others
|
*/

Route::middleware(['auth'])->group(function () {
    // Route::resource('files', FileController::class); // Exclude show from resource
    Route::get('/files', [FileController::class, 'index'])->name('files.index');
    Route::get('/files/create',[FileController::class,'create'])->name('files.create');
    Route::post('/files',[FileController::class,'store'])->name('files.store');

    Route::get('/files/{slug}', [FileController::class, 'show'])->name('files.show');
});

// Default laravel auth routes
Auth::routes(['register' => false]);

// file start

// file end

// Welcome page
Route::get('/', function () {
    return view('welcome');
});


// Web pages
Route::group(['middleware' => 'auth'],function (){

    // there should be graphics, diagrams about total conditions
    Route::get('/home', [HomeController::class,'index'])->name('home');

    // Users
    Route::get('/users',[UserController::class,'index'])->name('userIndex');
    Route::get('/user/add',[UserController::class,'add'])->name('userAdd');
    Route::post('/user/create',[UserController::class,'create'])->name('userCreate');
    Route::get('/user/{id}/edit',[UserController::class,'edit'])->name('userEdit');
    Route::post('/user/update/{id}',[UserController::class,'update'])->name('userUpdate');
    Route::delete('/user/delete/{id}',[UserController::class,'destroy'])->name('userDestroy');
    Route::get('/user/theme-set/{id}',[UserController::class,'setTheme'])->name('userSetTheme');
    Route::post('/user/toggle-status/{id}', [UserController::class, 'toggleUserActivation'])->name('userActivation');
    Route::get('/profile',[UserController::class,'userProfile'])->name('userProfile');

    // Permissions
    Route::get('/permissions',[PermissionController::class,'index'])->name('permissionIndex');
    Route::get('/permission/add',[PermissionController::class,'add'])->name('permissionAdd');
    Route::post('/permission/create',[PermissionController::class,'create'])->name('permissionCreate');
    Route::get('/permission/{id}/edit',[PermissionController::class,'edit'])->name('permissionEdit');
    Route::post('/permission/update/{id}',[PermissionController::class,'update'])->name('permissionUpdate');
    Route::delete('/permission/delete/{id}',[PermissionController::class,'destroy'])->name('permissionDestroy');

    // Roles
    Route::get('/roles',[RoleController::class,'index'])->name('roleIndex');
    Route::get('/role/add',[RoleController::class,'add'])->name('roleAdd');
    Route::post('/role/create',[RoleController::class,'create'])->name('roleCreate');
    Route::get('/role/{role_id}/edit',[RoleController::class,'edit'])->name('roleEdit');
    Route::post('/role/update/{role_id}',[RoleController::class,'update'])->name('roleUpdate');
    Route::delete('/role/delete/{id}',[RoleController::class,'destroy'])->name('roleDestroy');

    // ApiUsers
    Route::get('/api-users',[ApiUserController::class,'index'])->name('api-userIndex');
    Route::get('/api-user/add',[ApiUserController::class,'add'])->name('api-userAdd');
    Route::post('/api-user/create',[ApiUserController::class,'create'])->name('api-userCreate');
    Route::get('/api-user/show/{id}',[ApiUserController::class,'show'])->name('api-userShow');
    Route::get('/api-user/{id}/edit',[ApiUserController::class,'edit'])->name('api-userEdit');
    Route::post('/api-user/update/{id}',[ApiUserController::class,'update'])->name('api-userUpdate');
    Route::delete('/api-user/delete/{id}',[ApiUserController::class,'destroy'])->name('api-userDestroy');
    Route::delete('/api-user-token/delete/{id}',[ApiUserController::class,'destroyToken'])->name('api-tokenDestroy');

    // Category
    Route::get('/category',[CategoryController::class,'index'])->name('categoryIndex');
    Route::get('/category/add',[CategoryController::class,'add'])->name('categoryAdd');
    Route::post('/category/create',[CategoryController::class,'create'])->name('categoryCreate');
    Route::get('/category/{id}/edit',[CategoryController::class,'edit'])->name('categoryEdit');
    Route::post('/category/update/{category_id}',[CategoryController::class,'update'])->name('categoryUpdate');
    Route::delete('/category/delete/{id}',[CategoryController::class,'destroy'])->name('categoryDestroy');
    
    // Task
    Route::get('/monitoring',[ProductController::class,'index'])->name('monitoringIndex');
    // Route::resource('tasks', TaskController::class);
    // Route::get('/tasks',[TaskController::class,'index'])->name('monitoringIndex');
    Route::get('/task/add',[TaskController::class,'add'])->name('taskAdd');
    Route::post('/task/create',[TaskController::class,'create'])->name('taskCreate');
    Route::get('/task/{id}',[TaskController::class,'show'])->name('taskShow');
    Route::get('/task/{id}/edit',[TaskController::class,'edit'])->name('taskEdit');
    Route::post('/task/update/{task_id}',[TaskController::class,'update'])->name('taskUpdate');
    Route::delete('/task/delete/{id}', [TaskController::class, 'destroy'])->name('taskDestroy');

    // Order controller
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::post('/orders/reject', [OrderController::class, 'reject'])->name('orders.reject');
    Route::post('/orders/complete', [OrderController::class, 'complete'])->name('orders.complete');
    Route::post('/orders/admin_confirm', [OrderController::class, 'adminConfirm'])->name('orders.admin_confirm');
    Route::post('/orders/admin_reject', [OrderController::class, 'adminReject'])->name('orders.admin_reject');

 
    Route::get('/dashboard',[DashboardController::class,'index'])->name('dashboardIndex');

    // Monitoring
    Route::get('/monitoring',[MonitoringController::class,'index'])->name('monitoringIndex');


    // Employee
    Route::get('/employees',[EmployeeController::class,'index'])->name('employeeIndex');
    Route::get('/employee/add',[EmployeeController::class,'add'])->name('employeeAdd');
    Route::post('/employee/create',[EmployeeController::class,'create'])->name('employeeCreate');
    Route::get('/employee/{id}/edit',[EmployeeController::class,'edit'])->name('employeeEdit');
    Route::post('/employee/update/{user_id}',[EmployeeController::class,'update'])->name('employeeUpdate');
    Route::delete('/employee/delete/{id}',[EmployeeController::class,'destroy'])->name('employeeDestroy');
    Route::post('/employee/toggle-status/{id}',[EmployeeController::class,'toggleProductActivation'])->name('productActivation');



    // Long text
    Route::get('/long-texts',[LongTextController::class,'index'])->name('longTextIndex');
    Route::get('/long-text/add',[LongTextController::class,'add'])->name('longTextAdd');
    Route::post('/long-text/create',[LongTextController::class,'create'])->name('longTextCreate');
    Route::get('/long-text/{id}/edit',[LongTextController::class,'edit'])->name('longTextEdit');
    Route::post('/long-text/update/{longText_id}',[LongTextController::class,'update'])->name('longTextUpdate');
    Route::delete('/long-text/delete/{id}',[LongTextController::class,'destroy'])->name('longTextDestroy');

    // Document

    Route::get('/cheque',[ReportController::class,'cheque'])->name('chequeIndex');

    // COMMAND CONTROLLER
    Route::any('/toggle-command', [DemoController::class, 'toggleCommand'])->name('toggleCommand');
    Route::get('/get-status', [DemoController::class,'getStatus'])->name('getStatus');


    // filedonloader

    Route::delete('/files/{id}', [TaskController::class, 'deleteFile'])->name('file.delete');

    
    Route::get('download-pdf', [FileDownloadController::class, 'downloadPdf']);
    Route::get('download-excel', [FileDownloadController::class, 'downloadExcel']);
    Route::get('download-csv', [FileDownloadController::class, 'downloadCsv']);
});

// Change language session condition
Route::get('/language/{lang}',function ($lang){
    $lang = strtolower($lang);
    if ($lang == 'ru' || $lang == 'uz')
    {
        session([
            'locale' => $lang
        ]);
    }
    return redirect()->back();
})->name('changelang');

/*
|--------------------------------------------------------------------------
| This is the end of Blade (front-end) Routes
|-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\
*/
