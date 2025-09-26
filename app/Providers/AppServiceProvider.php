<?php

declare(strict_types=1);

namespace App\Providers;

use App\Repositories\Contracts\InvoiceRepositoryContract;
use App\Repositories\EloquentInvoiceRepository;
use App\Services\Invoice\Contracts\InvoiceParserContract;
use App\Services\Invoice\Contracts\InvoiceStorageContract;
use App\Services\Invoice\Contracts\InvoiceValidatorContract;
use App\Services\Invoice\InvoiceStorage;
use App\Services\Invoice\InvoiceValidator;
use App\Services\Invoice\InvoiceXmlParser;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->bind(InvoiceStorageContract::class, InvoiceStorage::class);
        $this->app->bind(InvoiceValidatorContract::class, InvoiceValidator::class);
        $this->app->bind(InvoiceRepositoryContract::class, EloquentInvoiceRepository::class);
        $this->app->bind(InvoiceParserContract::class, InvoiceXmlParser::class);
    }
}
