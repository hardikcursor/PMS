<?php

use App\Http\Controllers\Admin\AddDeviceController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\farematrixController;
use App\Http\Controllers\Admin\LincenseController;
use App\Http\Controllers\Admin\PosUserController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;





// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'dologin'])->name('dologin');




Route::prefix('superadmin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('superadmin.dashboard');
    Route::get('/logout', [DashboardController::class, 'logout'])->name('superadmin.logout');
    Route::prefix('company')->group(function () {
        Route::get('/create', [CompanyController::class, 'create'])->name('superadmin.company.create');
        Route::post('/store', [CompanyController::class, 'store'])->name('superadmin.company.store');
        Route::get('/manage', [CompanyController::class, 'managecompany'])->name('superadmin.company.manage');
        Route::get('/activate', [CompanyController::class, 'activate'])->name('superadmin.company.activate');
        Route::get('/inactive', [CompanyController::class, 'inactive'])->name('superadmin.company.inactive');
        Route::post('/changestatus', [CompanyController::class, 'changestatus'])->name('superadmin.company.changeStatus');
    });

    Route::prefix('subscription')->group(function () {
        Route::get('/manage', [LincenseController::class, 'index'])->name('superadmin.subscription.manage');
        Route::get('/create', [LincenseController::class, 'create'])->name('superadmin.subscription.create');
        Route::post('/store', [LincenseController::class, 'store'])->name('superadmin.subscription.store');

    });

    Route::prefix('posdevices')->group(function () {
        Route::get('/manage', [AddDeviceController::class, 'index'])->name('superadmin.adddevices.index');
        Route::get('/create', [AddDeviceController::class, 'create'])->name('superadmin.adddevices.create');
        Route::post('/store', [AddDeviceController::class, 'store'])->name('superadmin.adddevices.store');
        
    });
    Route::prefix('faremetrix')->group(function () {
        Route::get('/', [farematrixController::class, 'index'])->name('superadmin.faremetrix.index');
        // Route::get('/create', [farematrixController::class, 'create'])->name('superadmin.faremetrix.create');
        Route::get('/add-vehicle', [farematrixController::class, 'vehicleadd'])->name('superadmin.faremetrix.vehicleadd');
        Route::post('/add-vehicle', [farematrixController::class, 'addvehicle'])->name('superadmin.faremetrix.addvehicle');
        Route::post('/store-rate', [farematrixController::class, 'ratecreate'])->name('superadmin.faremetrix.ratecreate');
        Route::get('/add-slot', [farematrixController::class, 'addslot'])->name('superadmin.faremetrix.addslot');
        Route::get('/edit/{id}',[farematrixController::class, 'edit'])->name('superadmin.faremetrix.edit');
        Route::post('/store-slot', [farematrixController::class, 'storeslot'])->name('superadmin.faremetrix.storeslot');
    });

    Route::prefix('posuser')->group(function () {
        Route::get('/add-new', [PosUserController::class, 'index'])->name('superadmin.posuser.manageposuser');
        Route::get('/create', [PosUserController::class, 'create'])->name('superadmin.posuser.addnewposuser');
        Route::post('/store', [PosUserController::class, 'store'])->name('superadmin.posuser.store');
    });

});