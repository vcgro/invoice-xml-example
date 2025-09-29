<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\InvoiceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property ?string $filepath
 * @property Carbon $created_at
 * @property ?InvoiceMetadata $invoiceMetadata
 */
final class Invoice extends Model
{
    /** @use HasFactory<InvoiceFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'filepath',
    ];

    protected $hidden = [
        'filepath',
    ];

    /** @phpstan-ignore-next-line */
    public function invoiceMetadata(): HasOne
    {
        return $this->hasOne(InvoiceMetadata::class);
    }

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }
}
