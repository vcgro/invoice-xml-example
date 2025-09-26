<?php

declare(strict_types=1);

namespace App\Actions;

use App\Dto\InvoiceCreateDto;
use App\Exceptions\CustomValidationException;
use App\Exceptions\StorageSaveException;
use App\Exceptions\XmlParsingException;
use App\Repositories\Contracts\InvoiceRepositoryContract;
use App\Services\Invoice\InvoiceStorage;
use App\Services\Invoice\InvoiceValidator;
use App\Services\Invoice\InvoiceXmlParser;
use Illuminate\Validation\ValidationException;

final readonly class StoreInvoiceAction
{
    public function __construct(
        private InvoiceStorage $invoiceStorage,
        private InvoiceXmlParser $invoiceXmlParser,
        private InvoiceValidator $invoiceValidator,
        private InvoiceRepositoryContract $invoiceRepository,
    ) {
    }

    /**
     * To maintain consistency between the database and the filesystem, we first (over)write the file and only then
     * update the database.
     * A possible optimization is to move the logic of saving the file and subsequently updating the database
     * to asynchronous processing via a queue.
     *
     * @throws CustomValidationException
     * @throws XmlParsingException
     * @throws ValidationException
     * @throws StorageSaveException
     */
    public function execute(string $data): int
    {
        $parser = $this->invoiceXmlParser
            ->loadData($data);

        $validated = $this->invoiceValidator
            ->validate(
                $parser->toArray()
            );

        $dto = new InvoiceCreateDto(
            invoiceNumber: $validated['invoice_number'],
            supplierId: $validated['supplier_id'],
            customerId: $validated['customer_id'],
            payableAmount: $validated['payable_amount'],
            issueDate: $validated['issue_date'],
        );

        $invoiceId = $this->invoiceRepository
            ->createWithMetadata($dto);

        // This can be placed in a queue:
        $this->invoiceStorage
            ->forcePutOrFail(
                $invoiceId,
                $parser->toXml()
            );

        $this->invoiceRepository
            ->updateFilepath(
                $invoiceId,
                $this->invoiceStorage->getInvoiceFilepath($invoiceId)
            );

        return $invoiceId;
    }
}
