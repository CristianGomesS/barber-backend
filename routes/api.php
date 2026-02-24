<?php

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

    Route::post('/forceResetPassword', [ForgotPasswordController::class, 'forceResetPassword']);
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('api.users.index');
        Route::get('/{id}', [UserController::class, 'show'])->name('api.users.show');
        Route::post('/', [UserController::class, 'store'])->name('api.users.store');
        Route::put('/{id}', [UserController::class, 'update'])->name('api.users.update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('api.users.destroy');
        Route::put('restore/{id}', [UserController::class, 'restore'])->name('api.users.restore');

    });

    Route::prefix('service')->group(function () {
        Route::get('/', [ServiceController::class, 'index'])->name('api.service.index');
        Route::get('/{id}', [ServiceController::class, 'show'])->name('api.service.show');
        Route::post('/', [ServiceController::class, 'store'])->name('api.service.store');
        Route::put('/{id}', [ServiceController::class, 'update'])->name('api.service.update');
        Route::delete('/{id}', [ServiceController::class, 'destroy'])->name('api.service.destroy');
        Route::put('restore/{id}', [ServiceController::class, 'restore'])->name('api.service.restore');

    });

    Route::prefix('roles')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('api.role.index');
        Route::get('/{id}', [RoleController::class, 'show'])->name('api.role.show');
        Route::post('/', [RoleController::class, 'store'])->name('api.role.store');
        Route::put('/{id}', [RoleController::class, 'update'])->name('api.role.update');
        Route::delete('/{id}', [RoleController::class, 'destroy'])->name('api.role.destroy');
        Route::put('restore/{id}', [RoleController::class, 'restore'])->name('api.role.restore');
    });

});

// Route::fallback(function () {
//     return response()->json([
//         'message' => 'Rota não encontrada. Verifique a URL ou o método (GET/POST).'
//     ], 404);
// });