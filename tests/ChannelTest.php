<?php

namespace NotificationChannels\Webhook\Test;

use Mockery;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Notifications\Notification;
use NotificationChannels\Webhook\WebhookChannel;
use NotificationChannels\Webhook\WebhookMessage;
use NotificationChannels\Webhook\Clients\BaseHttpClient;
use NotificationChannels\Webhook\Concerns\MapsWebhookClients;
use NotificationChannels\Webhook\Exceptions\CouldNotSendNotification;

class ChannelTest extends TestCase
{
    /** @test */
    public function it_can_send_a_notification()
    {
        $notifiable = new TestNotifiable();
        $notification = new TestNotification();

        $this->createTestChannel(200, $notifiable, $notification)
             ->send($notifiable, $notification);
    }

    /** @test */
    public function it_can_send_a_notification_with_2xx_status()
    {
        $notifiable = new TestNotifiable();
        $notification = new TestNotification();

        $this->createTestChannel(201, $notifiable, $notification)
             ->send($notifiable, $notification);
    }

    /**
     * @test
     */
    public function it_throws_an_exception_when_it_could_not_send_the_notification()
    {
        $this->expectException(CouldNotSendNotification::class);

        $mock = new MockHandler([
                new RequestException('Server Error',
                new Request('POST', 'test'),
                new Response(500, [], 'Server Error')
            ),
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $notifiable = new TestNotifiable();
        $notification = new TestNotification();

        $this->createTestChannel(200, $notifiable, $notification, $client)
             ->send($notifiable, $notification);
    }

    /**
     * @param int
     */
    private function createTestChannel(int $statusCode, $notifiable, $notification, $guzzle = NULL)
    {
        $response = new Response($statusCode);

        $testPayload = [
            'body' => '{"payload":{"webhook":"data"}}',
            'headers' => [
                'User-Agent' => 'WebhookAgent',
                'X-Custom' => 'CustomHeader',
            ],
        ];

        if (!$guzzle) {
            $guzzle = Mockery::mock(ClientInterface::class);
            $guzzle->shouldReceive('post')
                ->once()
                ->with('https://notifiable-webhook-url.com', $testPayload)
                ->andReturn($response);
        }
        
        $mapper = Mockery::mock(MapsWebhookClients::class);
        $mapper->shouldReceive('getClient')
               ->once()
               ->with($notifiable, $notification, 'https://notifiable-webhook-url.com')
               ->andReturn(new BaseHttpClient($guzzle));

        return new WebhookChannel($mapper);
    }
}

class TestNotifiable
{
    use \Illuminate\Notifications\Notifiable;

    /**
     * @return string
     */
    public function routeNotificationForWebhook()
    {
        return [ 'https://notifiable-webhook-url.com' ];
    }
}

class TestNotification extends Notification
{
    public function toWebhook($notifiable)
    {
        return
            (new WebhookMessage(
                [
                    'payload' => [
                        'webhook' => 'data',
                    ],
                ]
            ))->userAgent('WebhookAgent')
            ->header('X-Custom', 'CustomHeader');
    }
}
