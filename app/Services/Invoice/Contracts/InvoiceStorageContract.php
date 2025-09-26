<?php

declare(strict_types=1);

namespace App\Services\Invoice\Contracts;

use App\Exceptions\StorageSaveException;
use Symfony\Component\HttpFoundation\StreamedResponse;

interface InvoiceStorageContract
{
    /**
     * @throws StorageSaveException
     */
    public function forcePutOrFail(int $invoiceId, string $xml): void;

    public function getInvoiceFilepath(int $filename): string;

    public function fileExists(int $invoiceId): bool;

    public function get(int $invoiceId): ?string;

    public function download(int $invoiceId): StreamedResponse;
}
