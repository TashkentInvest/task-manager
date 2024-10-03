<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Blade\ApiUserController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\OrderController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/


# Api Clients
Route::post('/login',[ApiAuthController::class,'login']);

Route::group(['middleware' => 'api-auth'],function (){
    Route::post('/me',[ApiAuthController::class,'me']);
    Route::post('/tokens',[ApiAuthController::class,'getAllTokens']);
    Route::post('/logout',[ApiAuthController::class,'logout']);
    // 
});

Route::group(['middleware' => 'ajax.check'],function (){
    Route::post('/api-user/toggle-status/{user_id}',[ApiUserController::class,'toggleUserActivation']);
    Route::post('/api-token/toggle-status/{token_id}',[ApiUserController::class,'toggleTokenActivation']);
    Route::get('/get/tasks',[TaskController::class, 'getTasks'])->name('taskAll');
    Route::post('/task/accept/{id}',[TaskController::class, 'acceptTask'])->name('taskAccept');
    Route::delete('/task/delete/{id}',[TaskController::class, 'deleteTask'])->name('taskDelete');

    // orders
    Route::post('/get/orders',[OrderController::class, 'getOrders'])->name('orderAll');
    Route::delete('/order/delete/{id}',[OrderController::class, 'deleteOrder'])->name('orderDelete');
    Route::post('/order/complete/{id}',[OrderController::class, 'completeOrder'])->name('orderComplate');
    Route::post('/order/update/{id}',[OrderController::class, 'updateOrderStatus'])->name('orderUpdate');
});


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
