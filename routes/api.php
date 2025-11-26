<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:api');

Route::prefix('v1')
    ->name('api.v1.')
    ->group(function () {
        Route::name('example.')
            ->prefix('example')
            ->middleware('guest')
            ->controller(App\Http\Controllers\Api\v1\ExampleController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::get('/show/{idOrSlug}', 'show')->name('show');
                Route::put('/update/{idOrSlug}', 'update')->name('update');
                Route::delete('/delete/{idOrSlug}', 'destroy')->name('delete');
                Route::post('/bulk_action/update', 'bulkUpdate')->name('bulk_update');
                Route::match(['delete', 'post'], '/bulk_action/destroy', 'bulkDestroy')->name('bulk_destroy');
            });
        Route::name('auth.')
            ->prefix('auth')
            ->controller(App\Http\Controllers\Api\v1\AuthController::class)
            ->group(function () {
                Route::post('/login', 'login')->name('login')->middleware('guest');
                Route::post('/logout', 'logout')->name('logout')->middleware('auth:api');
                Route::get('/me', 'me')->name('me')->middleware('auth:api');
            });
    });
