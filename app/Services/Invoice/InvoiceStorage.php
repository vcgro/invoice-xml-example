<?php

declare(strict_types=1);

namespace App\Services\Invoice;

use App\Exceptions\StorageSaveException;
use Illuminate\Contracts\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\StreamedResponse;

final readonly class InvoiceStorage
{
    private string $storage_path;

    public function __construct(
        private Filesystem $disk,
    ) {
        $this->storage_path = (string) config('invoices.storage_path');
    }

    /**
     * @throws StorageSaveException
     */
    public function forcePutOrFail(int $invoiceId, string $xml): void
    {
        $filepath = $this->getInvoiceFilepath($invoiceId);

        // To maintain consistency between the database and the filesystem, we safeguard against the error where a file
        // exists and differs from the database record but cannot be overwritten:
        if ($this->fileExists($invoiceId)) {
            $this->disk->delete($filepath);
        }

        $this->disk->put(
            $this->getInvoiceFilepath($invoiceId),
            $xml
        );

        if (! $this->fileExists($invoiceId)) {
            throw new StorageSaveException($filepath);
        }
    }

    public function getInvoiceFilepath(int $filename): string
    {
        return $this->storage_path . '/' . $filename . '.xml';
    }

    public function fileExists(int $invoiceId): bool
    {
        return $this->disk->fileExists(
            $this->getInvoiceFilepath($invoiceId)
        );
    }

    public function get(int $invoiceId): ?string
    {
        return $this->disk->get(
            $this->getInvoiceFilepath($invoiceId)
        );
    }

    public function download(int $invoiceId): StreamedResponse
    {
        return $this->disk->download(
            $this->getInvoiceFilepath($invoiceId),
            sprintf('invoice_%d.xml', $invoiceId),
            [
                'Content-Type' => 'application/xml',
            ]
        );
    }
}
