<?php

namespace NotificationChannels\Webhook\Concerns;

use Illuminate\Notifications\Notification;

interface SendsWebhookNotifications
{
    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     * @param string $url
     *
     * @return void
     */
    public function send($notifiable, Notification $notification, string $url);
}
