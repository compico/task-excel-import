<?php

declare(strict_types=1);

use App\Http\Controllers\Api\ProductsImportController;
use Illuminate\Support\Facades\Route;

Route::get(
    '/product/import/check_hash', [ProductsImportController::class, 'check_hash']
);

Route::post(
    '/product/import', [ProductsImportController::class, 'import']
);
