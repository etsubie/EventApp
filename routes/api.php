<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EventController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
;

Route::group(['middleware' => ['auth:sanctum', 'permission:create events']], function () {
    Route::post('/events', [EventController::class, 'store']);

});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::group(['middleware' => ['permission:view events']], function () {
        Route::group(['middleware' => ['role:admin|host|attendee']], function () {
            Route::get('/events', [EventController::class, 'index']);
        });
    });
});
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::group(['middleware' => ['permission:view users']], function () {
        Route::get('/users', [UserController::class, 'index']);
    });
});
Route::get('/events/{event}', [EventController::class, 'show']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::group(['middleware' => ['permission:manage events']], function () {
        Route::delete('/events/{event}', [EventController::class, 'destroy']);
        Route::patch('/events/{event}', [EventController::class, 'update']);
    });
});

Route::post('/categories', [CategoryController::class, 'store']);

