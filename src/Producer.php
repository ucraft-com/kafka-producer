<?php

declare(strict_types = 1);

namespace Uc\KafkaProducer;

use RdKafka\Producer as KafkaProducer;
use RuntimeException;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Wrapper class over RdKafka\Producer.
 *
 * @author Tigran Mesropyan <tiko@ucraft.com>
 */
class Producer
{
    /**
     * @var \RdKafka\Producer Reference on the RdKafka\Producer instance.
     */
    protected KafkaProducer $producer;

    /**
     * @var \Symfony\Component\Serializer\SerializerInterface Reference on the instance of Symfony serializer.
     */
    protected SerializerInterface $serializer;

    public function __construct(KafkaProducer $producer, SerializerInterface $serializer)
    {
        $this->producer = $producer;
        $this->serializer = $serializer;
    }

    /**
     * Produce and send a single message to broker.
     *
     * @param \Uc\KafkaProducer\Message $message
     *
     * @return void
     */
    public function produce(Message $message) : void
    {
        $topic = $this->producer->newTopic($message->getTopicName());

        $topic->producev(
            $message->getPartition(),
            RD_KAFKA_MSG_F_BLOCK,
            $this->serializer->serialize($message->getBody(), 'json'),
            $this->serializer->serialize($message->getKey(), 'json'),
            $message->getHeaders(),
        );

        $this->producer->poll(0);

        for ($flushRetries = 0; $flushRetries < 10; $flushRetries++) {
            $result = $this->producer->flush(10000);
            if (RD_KAFKA_RESP_ERR_NO_ERROR === $result) {
                break;
            }
        }

        if (RD_KAFKA_RESP_ERR_NO_ERROR !== $result) {
            throw new RuntimeException('Was unable to flush, messages might be lost!');
        }
    }
}
