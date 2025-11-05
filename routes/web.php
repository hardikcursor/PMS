<?php

use App\Http\Controllers\Admin\AddDeviceController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\farematrixController;
use App\Http\Controllers\Admin\LincenseController;
use App\Http\Controllers\Admin\LocationController;
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
        Route::get('/edit/{id}', [CompanyController::class, 'edit'])->name('superadmin.company.edit');
        Route::put('/update/{id}', [CompanyController::class, 'update'])->name('superadmin.company.update');
        Route::delete('/delete/{id}', [CompanyController::class, 'destroy'])->name('superadmin.company.delete');
        Route::get('/manage', [CompanyController::class, 'managecompany'])->name('superadmin.company.manage');
        Route::get('/activate', [CompanyController::class, 'activate'])->name('superadmin.company.activate');
        Route::get('/inactive', [CompanyController::class, 'inactive'])->name('superadmin.company.inactive');
        Route::post('/changestatus', [CompanyController::class, 'changestatus'])->name('superadmin.company.changeStatus');
    });

    Route::prefix('subscription')->group(function () {
        Route::get('/manage', [LincenseController::class, 'index'])->name('superadmin.subscription.manage');
        Route::get('/create', [LincenseController::class, 'create'])->name('superadmin.subscription.create');
        Route::post('/store', [LincenseController::class, 'store'])->name('superadmin.subscription.store');
        Route::get('/edit/{id}', [LincenseController::class, 'edit'])->name('superadmin.subscription.edit');
        Route::put('/update/{id}', [LincenseController::class, 'update'])->name('superadmin.subscription.update');
        Route::delete('/delete/{id}', [LincenseController::class, 'destroy'])->name('superadmin.subscription.destroy');
    });

    Route::prefix('posdevices')->group(function () {
        Route::get('/manage', [AddDeviceController::class, 'index'])->name('superadmin.adddevices.index');
        Route::get('/create', [AddDeviceController::class, 'create'])->name('superadmin.adddevices.create');
        Route::post('/store', [AddDeviceController::class, 'store'])->name('superadmin.adddevices.store');
        Route::get('/edit/{id}', [AddDeviceController::class, 'edit'])->name('superadmin.adddevices.edit');
        Route::put('/update/{id}', [AddDeviceController::class, 'update'])->name('superadmin.adddevices.update');
        Route::delete('/delete/{id}', [AddDeviceController::class, 'destroy'])->name('superadmin.adddevices.destroy');
        Route::post('/changestatus', [AddDeviceController::class, 'changestatus'])->name('superadmin.adddevices.changeStatus');

    });
    Route::prefix('faremetrix')->group(function () {
        Route::get('/', [farematrixController::class, 'index'])->name('superadmin.faremetrix.index');
        // Route::get('/create', [farematrixController::class, 'create'])->name('superadmin.faremetrix.create');
        Route::get('/add-vehicle', [farematrixController::class, 'vehicleadd'])->name('superadmin.faremetrix.vehicleadd');
        Route::post('/add-vehicle', [farematrixController::class, 'addvehicle'])->name('superadmin.faremetrix.addvehicle');
        Route::get('/edit-vehicle/{id}', [farematrixController::class, 'editvehicle'])->name('superadmin.faremetrix.editvehicle');
        Route::put('/update-vehicle/{id}', [farematrixController::class, 'updatevehicle'])->name('superadmin.faremetrix.updatevehicle');
        Route::delete('/delete-vehicle/{id}', [farematrixController::class, 'deletevehicle'])->name('superadmin.faremetrix.deletevehicle');
        Route::post('/store-rate', [farematrixController::class, 'ratecreate'])->name('superadmin.faremetrix.ratecreate');
        Route::get('/add-slot', [farematrixController::class, 'addslot'])->name('superadmin.faremetrix.addslot');
        Route::get('get-company-vehicles/{companyId}', [farematrixController::class, 'getVehicles'])
            ->name('superadmin.getCompanyVehicles');
        Route::get('/edit/{id}', [farematrixController::class, 'edit'])->name('superadmin.faremetrix.edit');
        Route::post('/store-slot', [farematrixController::class, 'storeslot'])->name('superadmin.faremetrix.storeslot');
        Route::post('/vehicle/rate/update', [farematrixController::class, 'updateRate'])->name('update.vehicle.rate');
        Route::post('/vehicle/rate/delete', [farematrixController::class, 'deleteRate'])->name('delete.vehicle.rate');
    });

    Route::prefix('posuser')->group(function () {
        Route::get('/add-new', [PosUserController::class, 'index'])->name('superadmin.posuser.manageposuser');
        Route::get('/create', [PosUserController::class, 'create'])->name('superadmin.posuser.addnewposuser');
        Route::post('/store', [PosUserController::class, 'store'])->name('superadmin.posuser.store');
        Route::get('/edit/{id}', [PosUserController::class, 'edit'])->name('superadmin.posuser.editposuser');
        Route::put('/update/{id}', [PosUserController::class, 'update'])->name('superadmin.posuser.updateposuser');
        Route::delete('/delete/{id}', [PosUserController::class, 'destroy'])->name('superadmin.posuser.deleteposuser');
    });

    Route::prefix('location')->group(function () {
        // Show locations table
        Route::get('/locations', [LocationController::class, 'index'])->name('locations.index');

    });

});

Route::prefix('company-admin')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\CompanyAdmin\DashboardController::class, 'index'])->name('admin.dashboard');

    Route::prefix('posdevices')->group(function () {
        Route::get('/manage', [\App\Http\Controllers\CompanyAdmin\AddDeviceController::class, 'index'])->name('admin.adddevices.index');
        Route::get('/create', [\App\Http\Controllers\CompanyAdmin\AddDeviceController::class, 'create'])->name('admin.adddevices.create');
        Route::post('/store', [\App\Http\Controllers\CompanyAdmin\AddDeviceController::class, 'store'])->name('admin.adddevices.store');
        Route::get('/edit/{id}', [\App\Http\Controllers\CompanyAdmin\AddDeviceController::class, 'edit'])->name('admin.adddevices.edit');
        Route::put('/update/{id}', [\App\Http\Controllers\CompanyAdmin\AddDeviceController::class, 'update'])->name('admin.adddevices.update');
        Route::delete('/delete/{id}', [\App\Http\Controllers\CompanyAdmin\AddDeviceController::class, 'destroy'])->name('admin.adddevices.destroy');
        Route::post('/changestatus', [\App\Http\Controllers\CompanyAdmin\AddDeviceController::class, 'changestatus'])->name('admin.adddevices.changeStatus');

    });

    Route::prefix('faremetrix')->group(function () {
        Route::get('/', [\App\Http\Controllers\CompanyAdmin\farematrixController::class, 'index'])->name('admin.faremetrix.index');
        // Route::get('/create', [farematrixController::class, 'create'])->name('superadmin.faremetrix.create');
        Route::get('/add-vehicle', [\App\Http\Controllers\CompanyAdmin\farematrixController::class, 'vehicleadd'])->name('admin.faremetrix.vehicleadd');
        Route::post('/add-vehicle', [\App\Http\Controllers\CompanyAdmin\farematrixController::class, 'addvehicle'])->name('admin.faremetrix.addvehicle');
        Route::post('/store-rate', [\App\Http\Controllers\CompanyAdmin\farematrixController::class, 'ratecreate'])->name('admin.faremetrix.ratecreate');
        Route::get('/add-slot', [\App\Http\Controllers\CompanyAdmin\farematrixController::class, 'addslot'])->name('admin.faremetrix.addslot');
        Route::get('/edit/{id}', [\App\Http\Controllers\CompanyAdmin\farematrixController::class, 'edit'])->name('admin.faremetrix.edit');
        Route::post('/store-slot', [\App\Http\Controllers\CompanyAdmin\farematrixController::class, 'storeslot'])->name('admin.faremetrix.storeslot');
        Route::post('/vehicle/rate/update', [\App\Http\Controllers\CompanyAdmin\farematrixController::class, 'updateRate'])->name('admin.update.vehicle.rate');
        Route::post('/vehicle/rate/delete', [\App\Http\Controllers\CompanyAdmin\farematrixController::class, 'deleteRate'])->name('admin.delete.vehicle.rate');
        // Update Vehicle (for modal)
        Route::put('/superadmin/faremetrix/{id}', [\App\Http\Controllers\CompanyAdmin\farematrixController::class, 'update'])->name('superadmin.faremetrix.update');

        // Delete Vehicle
        Route::delete('/superadmin/faremetrix/{id}', [\App\Http\Controllers\CompanyAdmin\farematrixController::class, 'delete'])->name('superadmin.faremetrix.delete');

    });

    Route::prefix('posuser')->group(function () {
        Route::get('/add-new', [\App\Http\Controllers\CompanyAdmin\PosUserController::class, 'index'])->name('admin.posuser.manageposuser');
        Route::get('/create', [\App\Http\Controllers\CompanyAdmin\PosUserController::class, 'create'])->name('admin.posuser.addnewposuser');
        Route::post('/store', [\App\Http\Controllers\CompanyAdmin\PosUserController::class, 'store'])->name('admin.posuser.store');
        Route::get('/edit/{id}', [\App\Http\Controllers\CompanyAdmin\PosUserController::class, 'edit'])->name('admin.posuser.editposuser');
        Route::put('/update/{id}', [\App\Http\Controllers\CompanyAdmin\PosUserController::class, 'update'])->name('admin.posuser.updateposuser');
        Route::delete('/delete/{id}', [\App\Http\Controllers\CompanyAdmin\PosUserController::class, 'destroy'])->name('admin.posuser.deleteposuser');
    });

    Route::prefix('reports')->group(function () {
        Route::get('/daily', [\App\Http\Controllers\CompanyAdmin\ReportsController::class, 'dailyreport'])->name('admin.reports.daily');
        Route::get('/vehicle', [\App\Http\Controllers\CompanyAdmin\ReportsController::class, 'vehicleReport'])->name('admin.reports.vehicle');
    });
});

Route::prefix('user')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\User\DashboardController::class, 'dashboard'])->name('user.dashboard');
    Route::get('/total-revenue', [\App\Http\Controllers\User\DashboardController::class, 'getTotalRevenue'])->name('total.revenue');
    Route::get('/today-collection', [\App\Http\Controllers\User\DashboardController::class, 'todayUserCollections'])->name('reports.today.collection');
    Route::get('/logout', [\App\Http\Controllers\User\DashboardController::class, 'logout'])->name('user.logout');

    Route::get('/reports', [\App\Http\Controllers\User\ReportController::class, 'index'])->name('user.reports');
    Route::get('/vehicle-report', [\App\Http\Controllers\User\VehicleReportController::class, 'vehicleReport'])->name('user.vehicle.report');
    Route::get('/user-report', [\App\Http\Controllers\User\UserReportController::class, 'userReport'])->name('user.user.report');
    Route::get('/pass-report', [\App\Http\Controllers\User\PassReportController::class, 'passReport'])->name('user.pass.report');
});
