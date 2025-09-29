<?php

declare(strict_types=1);

use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

Route::prefix('/invoices')
    ->as('invoices.')
    ->group(function (): void {
        Route::get('/', [InvoiceController::class, 'index'])
            ->name('index');
        Route::post('/', [InvoiceController::class, 'store'])
            ->name('store');
        Route::get('/{invoice_id}', [InvoiceController::class, 'show'])
            ->name('show')
            ->whereNumber('invoice_id');
        Route::get('/{invoice_id}/xml', [InvoiceController::class, 'download'])
            ->name('download')
            ->whereNumber('invoice_id');
    });
