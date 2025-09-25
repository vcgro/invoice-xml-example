<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

final class CustomValidationException extends Exception
{
    public function __construct()
    {
        parent::__construct(
            __('general.exception.validation.invoice_validation_fail')
        );
    }
}
