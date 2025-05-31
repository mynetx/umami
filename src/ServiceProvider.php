<?php

namespace Mynetx\Umami;

use Mynetx\Umami\Tags\Umami;
use Mynetx\Umami\Widgets\UmamiStatsWidget;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $widgets = [
        UmamiStatsWidget::class,
    ];

    protected $tags = [
        Umami::class,
    ];

    protected $viewNamespace = 'mynetx-umami';

    protected $config = [
        'umami',
    ];

    public function bootAddon(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', $this->viewNamespace);

        // Publish the config file
        $this->publishes([
            __DIR__.'/../config/umami.php' => config_path('umami.php'),
        ], 'umami-config');
    }
}
