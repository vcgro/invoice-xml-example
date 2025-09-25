<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exceptions\CustomValidationException;
use App\Exceptions\StorageSaveException;
use App\Exceptions\XmlParsingException;
use App\Http\Resources\InvoiceCollectionResource;
use App\Http\Resources\InvoiceCreateResource;
use App\UseCases\Commands\DownloadInvoiceQuery;
use App\UseCases\Commands\GetAllInvoiceQuery;
use App\UseCases\Queries\StoreInvoiceCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\StreamedResponse;

final readonly class InvoiceController
{
    /**
     * Route: GET /api/invoices/get
     * List Invoices Endpoint.
     */
    public function index(
        GetAllInvoiceQuery $getAllInvoiceQuery,
    ): InvoiceCollectionResource {
        $invoices = $getAllInvoiceQuery->execute();

        return new InvoiceCollectionResource($invoices);
    }

    /**
     * Route: POST /api/invoices/create
     * Create Invoice Endpoint.
     *
     * @throws CustomValidationException
     * @throws StorageSaveException
     * @throws XmlParsingException
     * @throws ValidationException
     */
    public function store(
        Request $request,
        StoreInvoiceCommand $createInvoiceCommand,
    ): InvoiceCreateResource {
        $invoiceId = $createInvoiceCommand->execute($request->getContent());

        return new InvoiceCreateResource($invoiceId);
    }

    /**
     * Route: GET /api/invoices/{invoice_id}/xml
     * Get Invoice XML Endpoint.
     *
     * @throws FileNotFoundException
     */
    public function show(
        int $invoiceId,
        DownloadInvoiceQuery $downloadInvoiceQuery,
    ): StreamedResponse {
        return $downloadInvoiceQuery->execute($invoiceId);
    }
}
