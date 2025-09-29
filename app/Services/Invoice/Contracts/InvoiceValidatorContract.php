<?php

declare(strict_types=1);

namespace App\Services\Invoice\Contracts;

use App\Exceptions\CustomValidationException;

interface InvoiceValidatorContract
{
    /**
     * @param array<string, string|null> $data
     *
     * @return array<string, string>
     *
     * @throws CustomValidationException
     */
    public function validate(array $data): array;
}
