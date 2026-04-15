<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\NetworkController;
use App\Http\Controllers\UserController;

// Маршруты авторизации
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Всё, что ниже — только для авторизованных
Route::middleware(['auth'])->group(function () {
    // Главная страница — список абонентов
    Route::get('/', [SubscriberController::class, 'main'])->name('main');

    // CRUD абонентов
    Route::resource('subscribers', SubscriberController::class)->except(['show']);
    Route::post('/subscribers/bulk-delete', [SubscriberController::class, 'bulkDelete'])
        ->name('subscribers.bulkDelete');
    Route::post('/subscribers/ajax', [SubscriberController::class, 'store'])
        ->name('subscribers.ajax');

    // CRUD оборудования (equipment) — использует DeviceController
    Route::resource('equipment', DeviceController::class)->except(['show'])->names('equipment');
    
    // CRUD устройств (devices) — для обратной совместимости, использует тот же контроллер
    Route::resource('devices', DeviceController::class)->except(['show']);
    Route::post('/devices/bulk-delete', [DeviceController::class, 'bulkDelete'])
        ->name('devices.bulkDelete');

    // Экспорт
    Route::get('/subscribers/export', [SubscriberController::class, 'export'])->name('subscribers.export');
    Route::get('/equipment/export', [DeviceController::class, 'export'])->name('equipment.export');

    // Network (IP-адреса)
    Route::resource('network', NetworkController::class)->except(['show', 'create', 'edit', 'index']);
    Route::get('/network/data', [NetworkController::class, 'data'])->name('network.data');
    Route::get('/subscribers/data', [SubscriberController::class, 'data'])->name('subscribers.data');
    Route::get('/equipment/data', [DeviceController::class, 'data'])->name('equipment.data');
    Route::get('/devices/data', [DeviceController::class, 'data'])->name('devices.data');

    // CRUD Пользователей
    Route::middleware(['auth','role:admin'])->group(function(){
        Route::get('/users', [UserController::class,'index'])->name('users.index'); // список пользователей
        Route::post('/users', [UserController::class,'store'])->name('users.store'); // сохранение
        Route::put('/users/{user}', [UserController::class,'update'])->name('users.update'); // обновление
        Route::delete('/users/{user}', [UserController::class,'destroy'])->name('users.destroy'); // удаление
    });

    Route::get('/table', [App\Http\Controllers\TableColumnController::class, 'index'])->name('table.columns');
    Route::post('/table/save', [App\Http\Controllers\TableColumnController::class, 'save'])->name('table.columns.save');
    Route::get('/table/data', [App\Http\Controllers\TableColumnController::class, 'data'])->name('table.columns.data');
});

