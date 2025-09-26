<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Invoice;
use App\Repositories\Contracts\InvoiceRepositoryContract;
use Illuminate\Database\Eloquent\Collection;

final readonly class GetAllInvoiceAction
{
    public function __construct(
        private InvoiceRepositoryContract $invoiceRepository
    ) {
    }

    /**
     * Depending on the business requirements, it may be necessary to add pagination to prevent routing issues in
     * the future.
     *
     * @return Collection<int, Invoice>
     */
    public function execute(): Collection
    {
        return $this->invoiceRepository->getAll(withMetaData: true);
    }
}
