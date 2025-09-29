<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Invoice;
use App\Repositories\Contracts\InvoiceRepositoryContract;

final readonly class GetInvoiceAction
{
    public function __construct(
        private InvoiceRepositoryContract $repository,
    ) {
    }

    public function execute(int $invoiceId): Invoice
    {
        return $this->repository->findOrFail($invoiceId);
    }
}
