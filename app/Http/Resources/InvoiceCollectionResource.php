<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

final class InvoiceCollectionResource extends ResourceCollection
{
    public static $wrap = 'invoices';

    public $collects = InvoiceResource::class;
}
