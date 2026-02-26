<?php

use App\Http\Controllers\Api\AbilityController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ForgotPasswordController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('forget-password', [ForgotPasswordController::class, 'forgotPassword']);
Route::post('valid-token', [ForgotPasswordController::class, 'validToken']);
Route::post('reset-password', [ForgotPasswordController::class, 'resetPassword']);


Route::group(['middleware' => ['auth:sanctum']], function () {
    //   Route::get('/auth/me', [AuthController::class, 'me']);

    Route::post('/forceResetPassword', [ForgotPasswordController::class, 'forceResetPassword'])->middleware(['abilities:force_resete_password']);
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('api.users.index')->middleware(['abilities:list_users']);
        Route::get('/{id}', [UserController::class, 'show'])->name('api.users.show')->middleware(['abilities:list_users']);
        Route::post('/', [UserController::class, 'store'])->name('api.users.store')->middleware(['abilities:create_user']);
        Route::put('/{id}', [UserController::class, 'update'])->name('api.users.update')->middleware(['abilities:update_user']);
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('api.users.destroy')->middleware(['abilities:delete_user']);
        Route::put('restore/{id}', [UserController::class, 'restore'])->name('api.users.restore')->middleware(['abilities:delete_user']);
    });

    Route::prefix('service')->group(function () {
        Route::get('/', [ServiceController::class, 'index'])->name('api.service.index')->middleware(['abilities:list_services']);
        Route::get('/{id}', [ServiceController::class, 'show'])->name('api.service.show')->middleware(['abilities:list_services']);
        Route::post('/', [ServiceController::class, 'store'])->name('api.service.store')->middleware(['abilities:create_services']);
        Route::put('/{id}', [ServiceController::class, 'update'])->name('api.service.update')->middleware(['abilities:update_services']);
        Route::delete('/{id}', [ServiceController::class, 'destroy'])->name('api.service.destroy')->middleware(['abilities:delete_services']);
        Route::put('restore/{id}', [ServiceController::class, 'restore'])->name('api.service.restore')->middleware(['abilities:delete_services']);
        Route::post('/link-barber', [ServiceController::class, 'linkBarber'])->middleware(['abilities:update_services']);
    });

    Route::prefix('roles')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('api.role.index')->middleware(['abilities:list_roles']);
        Route::get('/{id}', [RoleController::class, 'show'])->name('api.role.show')->middleware(['abilities:list_roles']);
        Route::post('/', [RoleController::class, 'store'])->name('api.role.store')->middleware(['abilities:create_roles']);
        Route::put('/{id}', [RoleController::class, 'update'])->name('api.role.update')->middleware(['abilities:update_roles']);
        Route::delete('/{id}', [RoleController::class, 'destroy'])->name('api.role.destroy')->middleware(['abilities:delete_roles']);
        Route::put('restore/{id}', [RoleController::class, 'restore'])->name('api.role.restore')->middleware(['abilities:delete_roles']);
    });
    Route::prefix('abilities')->group(function () {
        Route::get('/', [AbilityController::class, 'index'])->name('api.ability.index')->middleware(['abilities:list_ability']);
        Route::get('/{id}', [AbilityController::class, 'show'])->name('api.ability.show')->middleware(['abilities:list_ability']);
        Route::post('/', [AbilityController::class, 'store'])->name('api.ability.store')->middleware(['abilities:create_ability']);
        Route::put('/{id}', [AbilityController::class, 'update'])->name('api.ability.update')->middleware(['abilities:update_ability']);
        Route::delete('/{id}', [AbilityController::class, 'destroy'])->name('api.ability.destroy')->middleware(['abilities:delete_ability']);
        Route::put('restore/{id}', [AbilityController::class, 'restore'])->name('api.ability.restore')->middleware(['abilities:delete_ability']);
    });
    Route::prefix('appointments')->group(function () {
        Route::get('/', [AppointmentController::class, 'myAppointments'])->name('api.appointments.index')->middleware(['abilities:list_appointments']);
        Route::post('/', [AppointmentController::class, 'beforeStore'])->name('api.appointments.store')->middleware(['abilities:create_appointments']);
    });

});

// Route::fallback(function () {
//     return response()->json([
//         'message' => 'Rota não encontrada. Verifique a URL ou o método (GET/POST).'
//     ], 404);
// });