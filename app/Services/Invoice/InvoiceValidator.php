<?php

declare(strict_types=1);

namespace App\Services\Invoice;

use App\Exceptions\CustomValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

final readonly class InvoiceValidator
{
    /**
     * @param array<string, string|null> $data
     *
     * @return array<string, string>
     *
     * @throws CustomValidationException
     * @throws ValidationException
     */
    public function validate(array $data): array
    {
        $rules = [
            'invoice_number' => 'required|string|max:100',
            'supplier_id' => 'required|string|max:100',
            'customer_id' => 'required|string|max:100',
            'payable_amount' => 'required|numeric|decimal:2',
            'issue_date' => 'required|date_format:Y-m-d',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new CustomValidationException();
        }

        return $validator->validated();
    }
}
