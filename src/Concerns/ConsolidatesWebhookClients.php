<?php

namespace NotificationChannels\Webhook\Concerns;

interface ConsolidatesWebhookClients
{
    /**
     * @param array $clients
     * @return \NotificationChannels\Webhook\Concerns\SendsWebhookNotifications
     * @return array 
     */
    public function consolidateClients(array $clients) : array;
}
