<?php

namespace NotificationChannels\Webhook\Test;

use Illuminate\Support\Arr;
use NotificationChannels\Webhook\WebhookMessage;

class MessageTest extends TestCase
{
    /** @test */
    public function it_accepts_data_when_constructing_a_message()
    {
        $message = new WebhookMessage(['foo' => 'bar']);

        $this->assertEquals(['foo' => 'bar'], Arr::get($message->toArray(), 'data'));
    }

    /** @test */
    public function it_provides_a_create_method()
    {
        $message = WebhookMessage::create(['foo' => 'bar']);

        $this->assertEquals(['foo' => 'bar'], Arr::get($message->toArray(), 'data'));
    }

    /** @test */
    public function it_can_set_the_webhook_data()
    {
        $message = new WebhookMessage();

        $message->data(['foo' => 'bar']);
        
        $this->assertEquals(['foo' => 'bar'], Arr::get($message->toArray(), 'data'));
    }

    /** @test */
    public function it_can_set_the_user_agent()
    {
        $message = new WebhookMessage();
        
        $message->userAgent('MyUserAgent');
        
        $this->assertEquals('MyUserAgent', Arr::get($message->toArray(), 'headers.User-Agent'));
    }

    /** @test */
    public function it_can_set_a_custom_header()
    {
        $message = new WebhookMessage();
        
        $message->header('X-Custom', 'Value');
        
        $this->assertEquals(['X-Custom' => 'Value'], Arr::get($message->toArray(), 'headers'));
    }
}
