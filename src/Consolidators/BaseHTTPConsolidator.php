<?php

namespace NotificationChannels\Webhook\Consolidators;

use NotificationChannels\Webhook\Clients\BaseHttpClient;
use NotificationChannels\Webhook\Concerns\ConsolidatesWebhookClients;

class BaseHttpConsolidator implements ConsolidatesWebhookClients
{
    /**
     * @param array $clients
     * @return array 
     */
    public function consolidateClients(array $clients) : array
    {
        if (empty($clients)) {
            $clients[] = BaseHttpClient::class;
        }

        return $clients;
    }
}
