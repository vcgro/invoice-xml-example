<?php

declare(strict_types=1);

return [
    'exception' => [
        'storage' => [
            'file_can_not_save' => 'The file cannot be written to :path due to a technical issue.',
            'file_not_found' => 'The file at path :path does not exist.'
        ],
        'parser' => [
            'invalid_xml' => 'XML parsing error. Probably invalid XML.',
        ],
        'validation' => [
            'invoice_validation_fail' => 'The invoice data is not valid.',
        ]
    ],
];
