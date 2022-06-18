<?php

namespace Uc\KafkaProducer\Tests\Unit;

use RuntimeException;
use Uc\KafkaProducer\Message;
use Uc\KafkaProducer\MessageBuilder;
use Uc\KafkaProducer\Tests\TestCase;

/**
 * @author Tigran Mesropyan <tiko@ucraft.com>
 */
class MessageBuilderTest extends TestCase
{
    public function testGetMessage_WithGivenProperties_ReturnsCorrectInstance() : void
    {
        $builder = $this->createBuilder();

        $message = $builder
            ->setTopicName('logs')
            ->setBody('dummy content.')
            ->setKey('first-log')
            ->setHeaders(['foo', 'bar'])
            ->getMessage();

        $this->assertInstanceOf(Message::class, $message);
        $this->assertEquals('logs', $message->getTopicName());
        $this->assertEquals('dummy content.', $message->getBody());
        $this->assertEquals('first-log', $message->getKey());
        $this->assertEquals(['foo', 'bar'], $message->getHeaders());
    }

    public function testGetMessage_WithoutTopicName_ThrowsException() : void
    {
        $builder = $this->createBuilder();

        $this->expectException(RuntimeException::class);

        $builder
            ->setBody('dummy content.')
            ->setKey('first-log')
            ->setHeaders(['foo', 'bar'])
            ->getMessage();
    }

    protected function createBuilder() : MessageBuilder
    {
        return new MessageBuilder();
    }
}
