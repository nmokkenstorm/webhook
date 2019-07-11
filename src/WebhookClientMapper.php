<?php

namespace NotificationChannels\Webhook;

use Illuminate\Contracts\Container;

use NotificationChannels\Webhook\Concerns\MapsWebhookClients;
use NotificationChannels\Webhook\Concerns\ResolvesWebhookClients;
use NotificationChannels\Webhook\Concerns\SendsWebhookNotifications;
use NotificationChannels\Webhook\Concerns\ConsolidatesWebhookClients;

class WebhookClientMapper implements MapsWebhookClients
{
    /**
     * @var \NotificationChannels\Webhook\Concerns\SendsWebhookNotifications[]
     */
    private $resolvers = [];

    /**
     * @var\NotificationChannels\Webhook\Concerns\ConsolidatesWebhookClients
     */
    private $consolidator;

    /**
     * @var lluminate\Contracts\Container
     */
    private $container;

    /**
     * @param NotificationChannels\Webhook\Concerns\ConsolidatesWebhookClients  $consolidator
     * @param Illuminate\Contracts\Container $container
     */
    public function __construct(ConsolidatesWebhookClients $consolidator, Container $container)
    {
        $this->consolidator = $consolidator
    }

    /**
     * Resolve a client to send notifications with
     *
     * @param string $url
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @return \NotificationChannels\Webhook\Concerns\SendsWebhookNotifications
     */
    public function getClient(string $url, $notifiable, Notification $notification) : SendsWebhookNotifications
    {
        return $this->container->make($this->consolidator->consolidate($this->resolveClients()));
    }

    /**
     * @param string $url
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @return array
     */
    private function resolveClients(string $url, $notifiable, Notification $notification) : array
    {
        $mapper = function (ResolvesWebhookClient $resolver) use ($url, $notifiable, $notification) : ? string {
            return $resolver->getClassName($url, $notificatiable, $notification);
        }

        return array_filter(array_map($mapper, $this->resolvers));
    }
    
    /**
     * Add a resolver to resolve clients with
     *
     * @return void
     */
    public function registerResolver(ResolvesWebhookClients $resolver) : void
    {
        $this->resolvers[] = $resolver;
    }
}
