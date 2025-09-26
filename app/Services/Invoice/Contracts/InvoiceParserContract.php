<?php

declare(strict_types=1);

namespace App\Services\Invoice\Contracts;

use App\Exceptions\InvoiceParsingException;

interface InvoiceParserContract
{
    /**
     * @throws InvoiceParsingException
     */
    public function loadData(string $data): self;

    /**
     * @throws InvoiceParsingException
     */
    public function toXml(): string;

    /**
     * @throws InvoiceParsingException
     */
    public function toArray(): array;
}
