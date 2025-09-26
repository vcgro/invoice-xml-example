<?php

declare(strict_types=1);

namespace App\Actions;

use App\Services\Invoice\Contracts\InvoiceStorageContract;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Symfony\Component\HttpFoundation\StreamedResponse;

final readonly class DownloadInvoiceAction
{
    public function __construct(
        private InvoiceStorageContract $invoiceStorage,
    ) {
    }

    /**
     * Depending on business requirements, it may be necessary to check if the invoice exists in the database
     * before allowing the file to be downloaded.
     *
     * @throws FileNotFoundException
     */
    public function execute(int $invoiceId): StreamedResponse
    {
        if ($this->invoiceStorage->fileExists($invoiceId)) {
            return $this->invoiceStorage->download($invoiceId);
        }

        throw new FileNotFoundException(
            __(
                'general.exception.storage.file_not_found',
                ['path' => $this->invoiceStorage->getInvoiceFilepath($invoiceId)],
            )
        );
    }
}
