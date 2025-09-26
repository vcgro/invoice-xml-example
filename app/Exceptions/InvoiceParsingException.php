<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

final class InvoiceParsingException extends Exception
{
    public function __construct()
    {
        parent::__construct(
            __('general.exception.parser.invalid_xml')
        );
    }
}
