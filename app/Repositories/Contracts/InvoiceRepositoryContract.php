<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Dto\InvoiceCreateDto;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Collection;

interface InvoiceRepositoryContract
{
    /**
     * @return int Inserted ID
     */
    public function createWithMetadata(InvoiceCreateDto $dto): int;

    /**
     * @return int Number of updated rows
     */
    public function updateFilepath(int $invoiceId, string $filepath): int;

    /**
     * @return Collection<int, Invoice>
     */
    public function getAll(bool $withMetaData = false): Collection;

    public function findOrFail(int $invoiceId): Invoice;
}
