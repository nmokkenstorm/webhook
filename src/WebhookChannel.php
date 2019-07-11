<?php

namespace NotificationChannels\Webhook;

use Illuminate\Support\Arr;
use Illuminate\Notifications\Notification;

use NotificationChannels\Webhook\Concerns\MapsWebhookClients;

class WebhookChannel
{
    /**
     * @var \NotificationChannels\Webhook\MapsWebhookClients
     */
    private $clientMapper;

    /**
     * @param \NotificationChannels\Webhook\MapsWebhookClients $clientMapper
     */
    public function __construct(MapsWebhookClients $clientMapper)
    {
        $this->clientMapper = $clientMapper;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     * @return void
     *
     * @throws \NotificationChannels\Webhook\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        foreach ($this->getUrlsForNotifiable($notifiable) as $url) {

            $this->clientMapper
                 ->getClient($notifiable, $notification, $url)
                 ->send($notifiable, $notification, $url);
        
        }
    } 

    /**
     * @param mixed $notifiable
     */
    protected function getUrlsForNotifiable($notifiable)
    {
        return Arr::wrap($notifiable->routeNotificationFor('Webhook'));
    }
}
