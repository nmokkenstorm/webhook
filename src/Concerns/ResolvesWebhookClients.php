<?php

namespace NotificationChannels\Webhook\Concerns;

use Callable;
use Illuminate\Support\Arr;
use Illuminate\Notifications\Notification;

interface ResolvesWebhookClients
{
    /**
     * Map a client to send notifications with
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     * @param string $url
     */
    public function getClassName(string $url, $notifiable = NULL, ? Notification $notification = NULL) : ? string;
}
