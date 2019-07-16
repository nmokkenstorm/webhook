<?php

namespace NotificationChannels\Webhook\Concerns;

use Illuminate\Notifications\Notification;

interface MapsWebhookClients
{
    /**
     * Resolve a client to send notifications with
     *
     * @param string $url
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     * @return \NotificationChannels\Webhook\Concerns\SendsWebhookNotifications
     */
    public function getClient(string $url, $notifiable, Notification $notification) : SendsWebhookNotifications;
}
