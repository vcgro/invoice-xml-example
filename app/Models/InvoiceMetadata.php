<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\InvoiceMetadataFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $invoice_id
 * @property string $invoice_number
 * @property string $supplier_id
 * @property string $customer_id
 * @property string $payable_amount Decimal
 * @property Carbon $issue_date
 */
final class InvoiceMetadata extends Model
{
    /** @use HasFactory<InvoiceMetadataFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'invoice_id',
        'invoice_number',
        'issue_date',
        'supplier_id',
        'customer_id',
        'payable_amount',
    ];
}
