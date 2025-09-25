<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

final class StorageSaveException extends Exception
{
    public function __construct(
        string $fileName
    ) {
        parent::__construct(
            __('general.exception.storage.file_can_not_save', ['path' => $fileName])
        );
    }
}
