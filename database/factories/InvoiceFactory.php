<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Invoice>
 */
final class InvoiceFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'filepath' => null,
        ];
    }

    public function withFilepath(): self
    {
        return $this->afterCreating(function (Invoice $invoice): void {
            $template = config('invoices.storage_path') . '/invoice_%s.xml';

            $invoice->filepath = sprintf($template, $invoice->id);
            $invoice->save();
        });
    }
}
