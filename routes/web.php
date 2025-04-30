<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\authentications\RegisterBasic;
use App\Http\Controllers\authentications\ForgotPasswordBasic;
use App\Http\Controllers\dashboard\Analytics;
use App\Http\Controllers\import_and_export\ImportController;
use App\Http\Controllers\import_and_export\ExportController;
use App\Http\Controllers\users\UserController;
use App\Http\Controllers\table1\Table1Controller;
use App\Http\Controllers\weather\WeatherController;
use App\Http\Controllers\RoleController;


Route::middleware(['auth'])->group(function () {

// PERMISSION MIDDLEWARE
// Route::middleware(['role'])->group(function () {

    // PAGES
    Route::get('/', [Analytics::class, 'index'])->name('dashboard-analytics');
    // Route::get('/user-management', [Analytics::class, 'userManagement'])->name('user-management');
    Route::middleware('permission:View Users')->get('/user-management', [Analytics::class, 'userManagement'])->name('user-management');

    // TABLE 1
    // Route::resource('table1', Table1Controller::class);
    Route::middleware('permission:View Table 1')->get('table1', [Table1Controller::class, 'index'])->name('table1.index');

    // Route::middleware('permission:Create Histories')->group(function () {
    //     Route::get('table1/create', [Table1Controller::class, 'create'])->name('table1.create');
    //     Route::post('table1', [Table1Controller::class, 'store'])->name('table1.store');
    // });
    Route::middleware('permission:Create Histories')->get('table1/create', [Table1Controller::class, 'create'])->name('table1.create');
    Route::middleware('permission:Create Histories')->post('table1', [Table1Controller::class, 'store'])->name('table1.store');
    Route::middleware('permission:Update Histories')->get('table1/{table1}/edit', [Table1Controller::class, 'edit'])->name('table1.edit');
    Route::middleware('permission:Update Histories')->put('table1/{table1}', [Table1Controller::class, 'update'])->name('table1.update');
    Route::middleware('permission:Delete Histories')->delete('table1/{table1}', [Table1Controller::class, 'destroy'])->name('table1.destroy');
    Route::get('/search', [Table1Controller::class, 'search'])->name('data.search');
    
    // USERS
    Route::middleware('permission: View Users')->get('user', [UserController::class, 'index'])->name('user.index');
    Route::middleware('permission:Create Users')->get('user/create', [UserController::class, 'create'])->name('user.create');
    Route::middleware('permission:View Users')->get('user/{user}', [UserController::class, 'show'])->name('user.show');
    Route::middleware('permission:Create Users')->post('user', [UserController::class, 'store'])->name('user.store');
    Route::middleware('permission:Update Users')->get('user/{user}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::middleware('permission:Update Users')->put('user/{user}', [UserController::class, 'update'])->name('user.update');
    Route::middleware('permission:Delete Users')->delete('user/{user}', [UserController::class, 'destroy'])->name('user.destroy');
    Route::get('/user-search', [UserController::class, 'userSearch'])->name('user.search');
    //User Account 
    Route::middleware('permission:Create Users')->get('/user-register', [RegisterBasic::class, 'userRegisterForm'])->name('user-register');
    Route::middleware('permission:Create Users')->post('/user-register', [RegisterBasic::class, 'userRegister'])->name('user-register');

    // Export#x1
    Route::get('/export-users', [ExportController::class, 'exportUsers'])->name('export.users1');  
    Route::get('/export-table1', [ExportController::class, 'exportTable1'])->name('export.table2'); 
    // Import#x1
    // Route::post('import/process', [ImportController::class, 'process'])->middleware('role')->name('import.process');
    // Route::get('/progress', [ImportController::class, 'getProgress'])->middleware('role')->name('progress');

    // LARAVEL EXCEL
    // Import#x2
    // Route::middleware('permission:Import User')->get('/import-users', function () {return view('import');});
    Route::post('/import-users', [ImportController::class, 'importUser'])->name('import.users');
    // Route::post('/import-table1', [ImportController::class, 'importTable1'])->middleware('role')->name('import.table1');
    // Export#x2
    // Route::get('/export-users', [ExportController::class, 'export'])->middleware('role')->name('export.users');
    // Export#x2
    Route::get('/export-csv', [ExportController::class, 'exportCsv'])->name('export.csv');
    Route::get('/export-weather', [ExportController::class, 'exportWeather'])->name('export.weather');   

    // Import
    Route::post('import/process', [ImportController::class, 'process'])->name('import.process');
    // Route::get('/import/progress', [ImportController::class, 'getImportProgress'])->name('import.progress');
    Route::get('/progress', [ImportController::class, 'getProgress'])->name('progress');

    // Weather
    Route::middleware('permission:View Weather')->get('weather', [WeatherController::class, 'index'])->name('weather.index');
    Route::middleware('permission:Create Weather')->get('weather/create', [WeatherController::class, 'create'])->name('weather.create');
    Route::middleware('permission:View Weather')->get('weather/{weather}', [WeatherController::class, 'show'])->name('weather.show');
    Route::middleware('permission:Create Weather')->post('weather', [WeatherController::class, 'store'])->name('weather.store');
    Route::middleware('permission:Update Weather')->get('weather/{weather}/edit', [WeatherController::class, 'edit'])->name('weather.edit');
    Route::middleware('permission:Update Weather')->put('weather/{weather}', [WeatherController::class, 'update'])->name('weather.update');
    Route::middleware('permission:Delete Weather')->delete('weather/{weather}', [WeatherController::class, 'destroy'])->name('weather.destroy');
    Route::post('import-weather/process', [ImportController::class, 'weatherProcess'])->name('import-weather.process');
    Route::get('/weather-search', [WeatherController::class, 'search'])->name('weather.search');

    // Roles and Permission
    Route::middleware('permission:View Roles')->get('roles', [RoleController::class, 'index'])->name('roles.index');
    // Route::middleware('permission:view_role')->get('roles/{role}', [RoleController::class, 'show'])->name('roles.show');
    Route::middleware('permission:Create Roles')->get('roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::middleware('permission:Create Roles')->get('roles/getTable', [RoleController::class, 'getTable'])->name('roles.getTable');
    Route::middleware('permission:Create Roles')->post('roles', [RoleController::class, 'store'])->name('roles.store');
    Route::middleware('permission:Update Roles')->get('roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::middleware('permission:Update Roles')->put('roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::middleware('permission:Delete Roles')->delete('roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
    Route::middleware('permission:Update Roles')->get('/role-layout', [RoleController::class, 'edit'])->name('role.edit');

    Route::middleware('permission:Edit Roles')->put('/permissions/update', [RoleController::class, 'updatePermission'])->name('permissions.update');
// });

    // PROFILE
    Route::get('/user-profile', [UserController::class, 'userProfile'])->name('user-profile');
    Route::put('/user-profile', [UserController::class, 'updateProfile'])->name('profile-update');
    // CHANGE PASSWORD - New Users
    Route::get('/password/change', [LoginBasic::class, 'showChangeForm'])->name('password.change');
    Route::post('/password/change', [LoginBasic::class, 'update'])->name('password.update');


});

    // AUTHENTICATION
    Route::get('/auth/login-basic', [LoginBasic::class, 'index'])->name('auth-login-basic');
    Route::post('/auth/login-basic', [LoginBasic::class, 'login'])->name('auth-login-basic2');
    Route::get('/logout', [LoginBasic::class, 'logout'])->name('logout');

    // FORGOT PASSWORD
    Route::get('/auth/forgot-password-basic', [ForgotPasswordBasic::class, 'index'])->name('auth-reset-password-basic');
    Route::post('/auth/forgot-password-basic', [ForgotPasswordBasic::class, 'handleResetRequest'])->name('password.handle_request');
    Route::get('password/reset/{token}', [ForgotPasswordBasic::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset/update', [ForgotPasswordBasic::class, 'resetPassword'])->name('password.update1');

   

    Route::get('/default-page', function () {
        return view('content.default-page');
    })->name('default-page');