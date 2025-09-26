<?php

declare(strict_types=1);

namespace App\Services\Invoice\Contracts;

use App\Exceptions\CustomValidationException;

interface InvoiceValidatorContract
{
    /**
     * @throws CustomValidationException
     */
    public function validate(array $data): array;
}
