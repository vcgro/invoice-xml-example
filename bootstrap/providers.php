<?php

declare(strict_types=1);

$telescopeProviders = [];
if (
    app()->environment('local')
    && class_exists(Laravel\Telescope\TelescopeServiceProvider::class)
    && class_exists(App\Providers\TelescopeServiceProvider::class)
) {
    $telescopeProviders[] = Laravel\Telescope\TelescopeServiceProvider::class;
    $telescopeProviders[] = App\Providers\TelescopeServiceProvider::class;
}

return [
    App\Providers\AppServiceProvider::class,
    ...$telescopeProviders
];
