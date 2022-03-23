<?php

declare(strict_types = 1);

namespace Uc\KafkaProducer\Tests\Feature;

use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Message\Message;
use Uc\KafkaProducer\Events\ProduceMessageEvent;
use Uc\KafkaProducer\Tests\TestCase;

class MessageProducerListenerTest extends TestCase
{
    public function testEventListener_WhenEventOccurs_ProducesMessage() : void
    {
        config(['kafka.brokers' => env('KAFKA_BROKERS')]);
        Kafka::fake();

        $message = Message::create('dummy-kafka-topic');
        $message
            ->withKey('dummy-key')
            ->withBody([
                'foo' => 'bar',
            ]);

        ProduceMessageEvent::dispatch($message);

        Kafka::assertPublishedOn('dummy-kafka-topic', $message, function (Message $message) {
            return $message->getBody()['foo'] === 'bar';
        });
    }
}
