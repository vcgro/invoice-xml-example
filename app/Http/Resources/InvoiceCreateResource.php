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
final class InvoiceCreateResource extends JsonResource
{
    public static $wrap = '';

    /**
     * @return array<string, int|string>
     */
    #[Override]
    public function toArray(Request $request): array
    {
        return [
            'id' => (int) $this->resource,
            'message' => 'Invoice saved successfully',
        ];
    }
}
