<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('import');
});

Route::get(
    '/product/list/{page?}',
    [ProductController::class, 'list']
)->name('product.list')->whereNumber('page');

Route::get(
    '/product/{id}',
    [ProductController::class, 'index']
)->whereNumber('id');
