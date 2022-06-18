<?php

declare(strict_types = 1);

namespace Uc\KafkaProducer\Tests\Feature;

use RdKafka\Topic;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Uc\KafkaProducer\Events\ProduceMessageEvent;
use Uc\KafkaProducer\MessageBuilder;
use Uc\KafkaProducer\Producer;
use Uc\KafkaProducer\Tests\TestCase;
use Mockery;

/**
 * @author Tigran Mesropyan <tiko@ucraft.com>
 */
class MessageProducerListenerTest extends TestCase
{
    public function testEventListener_WhenEventOccurs_ProducesMessage() : void
    {
        $producer = $this->createProducerMock();

        // Replace the original producer with mocked.
        $this->app->singleton(Producer::class, fn() => $producer);

        $builder = new MessageBuilder();
        $message = $builder
            ->setTopicName('topic_name')
            ->setKey('key')
            ->setBody(['hello' => 'world'])
            ->setHeaders(['foo', 'bar'])
            ->getMessage();

        ProduceMessageEvent::dispatch($message);
    }

    protected function createProducerMock() : Producer
    {
        $topic = Mockery::mock(Topic::class);
        $topic->shouldReceive('producev')
            ->withArgs([
                RD_KAFKA_PARTITION_UA,
                RD_KAFKA_MSG_F_BLOCK,
                '{"hello":"world"}',
                '"key"',
                ['foo', 'bar'],
            ])
            ->once();

        $producer = Mockery::mock(\RdKafka\Producer::class);
        $producer->shouldReceive('newTopic')
            ->with('topic_name')
            ->once()
            ->andReturn($topic);

        $producer->shouldReceive('poll');
        $producer->shouldReceive('flush');

        $serializer = new Serializer(
            [new ObjectNormalizer()],
            [new JsonEncoder()]
        );

        return new Producer($producer, $serializer);
    }
}
