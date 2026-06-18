<?php

use Dedoc\Scramble\Scramble;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Scramble::registerUiRoute(path: 'docs/api/v1', api: 'api/v1');
Scramble::registerJsonSpecificationRoute(path: 'docs/api/v1.json', api: 'api/v1');
