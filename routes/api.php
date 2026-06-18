<?php

use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\ExampleController;
use App\Http\Controllers\Api\v1\PermissionController;
use App\Http\Controllers\Api\v1\RoleController;
use App\Http\Controllers\Api\v1\SettingController;
use App\Http\Controllers\Api\v1\UserController;
use App\Http\Middleware\CheckApiToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Middleware\RoleMiddleware;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:api');

Route::prefix('v1')
    ->name('api.v1.')
    ->middleware(CheckApiToken::class)
    ->group(function () {
        Route::name('setting.')
            ->prefix('setting')
            ->middleware('auth:api')
            ->controller(SettingController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::put('/bulk_action/update', 'bulkUpdate')->name('bulk_action.update');
            });

        Route::name('user.')
            ->prefix('user')
            ->middleware('auth:api')
            ->controller(UserController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::get('/show/{idOrSlug}', 'show')->name('show');
                Route::put('/update/{idOrSlug}', 'update')->name('update');
                Route::delete('/delete/{idOrSlug}', 'destroy')->name('delete');
                Route::put('/bulk_action/update', 'bulkUpdate')->name('bulk_action.update');
                Route::match(['delete', 'post'], '/bulk_action/destroy', 'bulkDestroy')->name('bulk_action.destroy');
            });

        Route::name('example.')
            ->prefix('example')
            ->middleware('auth:api')
            ->controller(ExampleController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::get('/show/{idOrSlug}', 'show')->name('show');
                Route::put('/update/{idOrSlug}', 'update')->name('update');
                Route::delete('/delete/{idOrSlug}', 'destroy')->name('delete');
                Route::put('/bulk_action/update', 'bulkUpdate')->name('bulk_action.update');
                Route::match(['delete', 'post'], '/bulk_action/destroy', 'bulkDestroy')->name('bulk_action.destroy');
            });

        Route::name('role.')
            ->prefix('role')
            ->middleware(['auth:api', RoleMiddleware::class.':admin'])
            ->controller(RoleController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::get('/show/{idOrSlug}', 'show')->name('show');
                Route::put('/update/{idOrSlug}', 'update')->name('update');
                Route::delete('/delete/{idOrSlug}', 'destroy')->name('delete');
                Route::put('/bulk_action/update', 'bulkUpdate')->name('bulk_action.update');
                Route::match(['delete', 'post'], '/bulk_action/destroy', 'bulkDestroy')->name('bulk_action.destroy');
            });

        Route::name('permission.')
            ->prefix('permission')
            ->middleware(['auth:api', RoleMiddleware::class.':admin'])
            ->controller(PermissionController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::get('/show/{idOrSlug}', 'show')->name('show');
                Route::put('/update/{idOrSlug}', 'update')->name('update');
                Route::delete('/delete/{idOrSlug}', 'destroy')->name('delete');
                Route::put('/bulk_action/update', 'bulkUpdate')->name('bulk_action.update');
                Route::match(['delete', 'post'], '/bulk_action/destroy', 'bulkDestroy')->name('bulk_action.destroy');
            });
        Route::name('auth.')
            ->prefix('auth')
            ->controller(AuthController::class)
            ->group(function () {
                Route::post('/login', 'login')->name('login')->middleware('guest')->withoutMiddleware(CheckApiToken::class);
                Route::post('/logout', 'logout')->name('logout')->middleware('auth:api');
                Route::post('/register', 'register')->name('register')->middleware('guest');
                Route::get('/me', 'me')->name('me')->middleware('auth:api');
                Route::get('/api-token', 'generateApiToken')->name('api-token')->middleware('auth:api')->withoutMiddleware(CheckApiToken::class);
            });
    });
