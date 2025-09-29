<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\DownloadInvoiceAction;
use App\Actions\GetAllInvoiceAction;
use App\Actions\GetInvoiceAction;
use App\Actions\StoreInvoiceAction;
use App\Exceptions\CustomValidationException;
use App\Exceptions\StorageSaveException;
use App\Exceptions\InvoiceParsingException;
use App\Http\Requests\InvoiceShowRequest;
use App\Http\Resources\InvoiceCollectionResource;
use App\Http\Resources\InvoiceCreateResource;
use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\StreamedResponse;

final readonly class InvoiceController
{
    public function index(
        GetAllInvoiceAction $getAllInvoiceAction,
    ): InvoiceCollectionResource {
        $invoices = $getAllInvoiceAction->execute();

        return new InvoiceCollectionResource($invoices);
    }

    /**
     * @throws CustomValidationException
     * @throws StorageSaveException
     * @throws InvoiceParsingException
     * @throws ValidationException
     */
    public function store(
        Request $request,
        StoreInvoiceAction $createInvoiceAction,
    ): InvoiceCreateResource {
        $invoiceId = $createInvoiceAction->execute($request->getContent());

        return new InvoiceCreateResource($invoiceId);
    }

    public function show(
        GetInvoiceAction $getInvoiceAction,
        int $invoiceId
    ): InvoiceResource {
        $invoice = $getInvoiceAction->execute($invoiceId);

        return new InvoiceResource($invoice);
    }

    /**
     * @throws FileNotFoundException
     */
    public function download(
        DownloadInvoiceAction $downloadInvoiceAction,
        int $invoiceId
    ): StreamedResponse {
        return $downloadInvoiceAction->execute($invoiceId);
    }
}
