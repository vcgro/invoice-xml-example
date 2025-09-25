<?php

declare(strict_types=1);

use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

Route::prefix('/invoices')->group(function (): void {
    Route::get('/get', [InvoiceController::class, 'index'])
        ->name('invoices.index');
    Route::post('/create', [InvoiceController::class, 'store'])
        ->name('invoices.store');
    Route::get('/{invoice_id}/xml', [InvoiceController::class, 'show'])
        ->name('invoices.show')
        ->whereNumber('invoice_id');
});
