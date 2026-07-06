<?php

namespace Modules\Web\Providers;

use Nwidart\Modules\Support\ModuleServiceProvider;

class WebServiceProvider extends ModuleServiceProvider
{
    protected string $name = 'Web';
    protected string $nameLower = 'web';

    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
    ];
}
