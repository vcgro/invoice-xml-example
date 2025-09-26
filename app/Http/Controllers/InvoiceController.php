<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\DownloadInvoiceAction;
use App\Actions\GetAllInvoiceAction;
use App\Actions\StoreInvoiceAction;
use App\Exceptions\CustomValidationException;
use App\Exceptions\StorageSaveException;
use App\Exceptions\XmlParsingException;
use App\Http\Resources\InvoiceCollectionResource;
use App\Http\Resources\InvoiceCreateResource;
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
        GetAllInvoiceAction $getAllInvoiceAction,
    ): InvoiceCollectionResource {
        $invoices = $getAllInvoiceAction->execute();

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
        StoreInvoiceAction $createInvoiceAction,
    ): InvoiceCreateResource {
        $invoiceId = $createInvoiceAction->execute($request->getContent());

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
        DownloadInvoiceAction $downloadInvoiceAction,
    ): StreamedResponse {
        return $downloadInvoiceAction->execute($invoiceId);
    }
}
