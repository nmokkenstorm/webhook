<?php

namespace NotificationChannels\Webhook\Clients;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;

use Illuminate\Support\Arr;
use Illuminate\Notifications\Notification;

use NotificationChannels\Webhook\Concerns\SendsWebhookNotifications;
use NotificationChannels\Webhook\Exceptions\CouldNotSendNotification;

class BaseHttpClient implements SendsWebhookNotifications
{
    /**
     * @var GuzzleHttp\ClientInterface
     */
    protected $client;

    /**
     * @param GuzzleHttp\ClientInterface
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Send the notification to a single webhook.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     * @param string
     * @return void
     *
     * @throws \NotificationChannels\Webhook\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification, string $url)
    {
        $webhookData = $notification->toWebhook($notifiable)->toArray();

        try {
            $response = $this->client->post($url, [
                'body'      => json_encode(Arr::get($webhookData, 'data', [])),
                'headers'   => Arr::get($webhookData, 'headers', []),
            ]);
        } catch (RequestException $exception) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($exception->getResponse());
        }
    }
}
