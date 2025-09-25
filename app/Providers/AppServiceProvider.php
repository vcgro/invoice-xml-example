<?php

declare(strict_types=1);

namespace App\Providers;

use App\Repositories\Contracts\InvoiceRepositoryContract;
use App\Repositories\EloquentInvoiceRepository;
use App\Services\Invoice\InvoiceStorage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->singleton(function (): InvoiceStorage {
            $disk = (string) config('invoices.storage_disk');

            return new InvoiceStorage(
                Storage::disk($disk)
            );
        });

        $this->app->bind(InvoiceRepositoryContract::class, EloquentInvoiceRepository::class);
    }
}
