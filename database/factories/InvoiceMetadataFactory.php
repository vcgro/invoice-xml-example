<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\InvoiceMetadata;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InvoiceMetadata>
 */
final class InvoiceMetadataFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'invoice_id' => Invoice::factory(),
            'invoice_number' => fake()->unique()->numerify('INV#####'),
            'issue_date' => fake()->date(),
            'supplier_id' => fake()->regexify('[0-9]{20}'),
            'customer_id' => fake()->bothify('FR#####'),
            'payable_amount' => fake()->randomFloat(2, 100, 10000),
        ];
    }
}
