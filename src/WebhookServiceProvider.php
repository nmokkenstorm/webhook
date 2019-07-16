<?php

namespace NotificationChannels\Webhook;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

use Illuminate\Support\ServiceProvider as BaseProvider;

use NotificationChannels\Webhook\BaseHTTPClient;
use NotificationChannels\Webhook\Concerns\BaseHttpConsolidator;

class ServiceProvider extends Baseprovider
{
    /**
     * All of the container bindings that should be registered.
     *
     * @var array
     */
    public $bindings = [
        Concerns\ConsolidatesWebhookClients::class  => BaseHttpConsolidator::class,
    ];

    /**
     * All of the container singletons that should be registered.
     *
     * @var array
     */
    public $singletons = [
        Concerns\MapsWebhookClients::class          => WebhookClientMapper::class,
    ];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/config.php' => config_path('webhook-notifications.php'),
        ]);

        $this->mergeConfigFrom(
            __DIR__.'/../config/config.php', 'webhook-notifications'
        );
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app
            ->when(BaseHTTPClient::class)
            ->needs(ClientInterface)
            ->give(function ($app) {
                return new Client($app['config.webhook-notifications']['default-config']);
            });
    }
}
