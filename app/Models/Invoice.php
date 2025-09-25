<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property ?int $filepath
 * @property Carbon $created_at
 * @property ?InvoiceMetadata $metadata
 */
final class Invoice extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'filepath',
    ];

    protected $hidden = [
        'filepath',
    ];

    /** @phpstan-ignore-next-line */
    public function metadata(): HasOne
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
