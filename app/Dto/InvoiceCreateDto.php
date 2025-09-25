<?php

declare(strict_types=1);

namespace App\Dto;

final readonly class InvoiceCreateDto
{
    public function __construct(
        public string $invoiceNumber,
        public string $supplierId,
        public string $customerId,
        public string $payableAmount,
        public string $issueDate,
    ) {
    }
}
