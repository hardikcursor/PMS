<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FaremetrixController;
use App\Http\Controllers\Api\HeaderFooterController;
use App\Http\Controllers\Api\VehicleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;





Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/login', [AuthController::class, 'login']);
Route::get('/vehicles', [VehicleController::class, 'show']);
Route::post('/fares', [FaremetrixController::class, 'getFareMatrix']);

Route::get('/headersfooter', [HeaderFooterController::class, 'getHeaderFooter']);
