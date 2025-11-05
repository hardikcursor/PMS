<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FaremetrixController;
use App\Http\Controllers\Api\HeaderFooterController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\VehicleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;








Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/login', [AuthController::class, 'login']);
Route::post('/masterlogin', [AuthController::class, 'masterlogin']);
Route::get('/vehicles', [VehicleController::class, 'show']);
Route::post('/fares', [FaremetrixController::class, 'getFareMatrix']);

Route::post('/headersfooter', [HeaderFooterController::class, 'getHeaderFooter']);
Route::post('/get-address', [HeaderFooterController::class, 'getAddress']);
Route::post('/report',[ReportController::class, 'store']);
Route::get('/subscriptions',[SubscriptionController::class, 'subscriptions']);