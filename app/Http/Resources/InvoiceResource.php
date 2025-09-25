<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Override;

/**
 * @mixin Invoice
 */
final class InvoiceResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    #[Override]
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at->toIso8601ZuluString(),
            'metadata' => $this->whenLoaded('metadata', fn (): array => [
                'invoice_number' => $this->metadata?->invoice_number,
                'issue_date' => $this->metadata?->issue_date,
                'supplier_id' => $this->metadata?->supplier_id,
                'customer_id' => $this->metadata?->customer_id,
                // Do not cast decimals to float to avoid precision errors:
                'payable_amount' => $this->metadata?->payable_amount,
            ]),
        ];
    }
}
