<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Dto\InvoiceCreateDto;
use App\Models\Invoice;
use App\Models\InvoiceMetadata;
use App\Repositories\Contracts\InvoiceRepositoryContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

final readonly class EloquentInvoiceRepository implements InvoiceRepositoryContract
{
    public function createWithMetadata(InvoiceCreateDto $dto): int
    {
        return DB::transaction(function () use ($dto) {
            $invoice = Invoice::query()
                ->create();

            InvoiceMetadata::query()->create([
                'invoice_id' => $invoice->id,
                'invoice_number' => $dto->invoiceNumber,
                'issue_date' => $dto->issueDate,
                'supplier_id' => $dto->supplierId,
                'customer_id' => $dto->customerId,
                'payable_amount' => $dto->payableAmount,
            ]);

            return $invoice->id;
        }, 3);
    }

    public function updateFilepath(int $invoiceId, ?string $filepath = null): int
    {
        return Invoice::query()
            ->where('id', $invoiceId)
            ->update([
                'filepath' => $filepath,
            ]);
    }

    public function getAll(bool $withMetaData = false): Collection
    {
        return Invoice::query()
            ->when($withMetaData, function (Builder $builder): void {
                $builder->with(['invoiceMetadata']);
            })->get();
    }
}
