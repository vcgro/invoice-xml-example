<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Dto\InvoiceCreateDto;
use App\Http\Resources\InvoiceCollectionResource;
use App\Http\Resources\InvoiceCreateResource;
use App\Repositories\Contracts\InvoiceRepositoryContract;
use App\Services\Invoice\InvoiceStorage;
use App\Services\Invoice\InvoiceValidator;
use App\Services\Invoice\InvoiceXmlParser;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

final readonly class InvoiceController
{
    /**
     * Route: GET /api/invoices/get
     * List Invoices Endpoint.
     *
     * Depending on the business requirements, it may be necessary to add pagination to prevent routing issues in
     * the future.
     */
    public function index(
        InvoiceRepositoryContract $invoiceRepository,
    ): InvoiceCollectionResource {
        $invoices = $invoiceRepository->getAll(withMetaData: true);

        return new InvoiceCollectionResource($invoices);
    }

    /**
     * Route: POST /api/invoices/create
     * Create Invoice Endpoint.
     *
     * To maintain consistency between the database and the filesystem, we first (over)write the file and only then
     * update the database.
     * A possible optimization is to move the logic of saving the file and subsequently updating the database
     * to asynchronous processing via a queue.
     *
     * @throws Exception
     */
    public function store(
        Request $request,
        InvoiceStorage $invoiceStorage,
        InvoiceXmlParser $invoiceXmlParser,
        InvoiceValidator $invoiceValidator,
        InvoiceRepositoryContract $invoiceRepository,
    ): InvoiceCreateResource {
        $parser = $invoiceXmlParser->loadData(
            $request->getContent()
        );

        $validated = $invoiceValidator->validate(
            $parser->toArray()
        );

        $dto = new InvoiceCreateDto(
            invoiceNumber: $validated['invoice_number'],
            supplierId: $validated['supplier_id'],
            customerId: $validated['customer_id'],
            payableAmount: $validated['payable_amount'],
            issueDate: $validated['issue_date'],
        );
        $invoiceId = $invoiceRepository->createWithMetadata($dto);

        // This can be placed in a queue:
        $invoiceStorage->forcePutOrFail(
            $invoiceId,
            $parser->toXml()
        );
        $invoiceRepository->updateFilepath(
            $invoiceId,
            $invoiceStorage->getInvoiceFilepath($invoiceId)
        );

        return new InvoiceCreateResource($invoiceId);
    }

    /**
     * Route: GET /api/invoices/{invoice_id}/xml
     * Get Invoice XML Endpoint.
     *
     * Depending on business requirements, it may be necessary to check if the invoice exists in the database
     * before allowing the file to be downloaded.
     *
     * @throws FileNotFoundException
     */
    public function show(
        InvoiceStorage $invoiceStorage,
        int $invoiceId
    ): StreamedResponse {
        if ($invoiceStorage->fileExists($invoiceId)) {
            return $invoiceStorage->download($invoiceId);
        }

        throw new FileNotFoundException(
            __(
                'general.exception.storage.file_not_found',
                ['path' => $invoiceStorage->getInvoiceFilepath($invoiceId)],
            )
        );
    }
}
