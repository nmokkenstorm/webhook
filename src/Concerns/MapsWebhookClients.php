<?php

namespace NotificationChannels\Webhook\Concerns;

use Illuminate\Notifications\Notification;

interface MapsWebhookClients
{
    /**
     * Resolve a client to send notifications with
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     * @param string $url
     */
    public function getClient($notifiable, Notification $notification, string $url) : SendsWebhookNotifications;
}
