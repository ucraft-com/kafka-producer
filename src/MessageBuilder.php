<?php

declare(strict_types = 1);

namespace Uc\KafkaProducer;

use RuntimeException;

/**
 * Builder utility to create a message instances.
 *
 * @see \Uc\KafkaProducer\Message
 */
class MessageBuilder
{
    /**
     * @var string|null Name of the Kafka topic where the message should be published.
     */
    protected string|null $topicName = null;

    /**
     * @var mixed|null Content of the message. The content can be in an arbitrary type.
     *                 During publication, it should be serialized to the string.
     */
    protected mixed $body = null;

    /**
     * @var mixed|null Key of the message. The key can be in an arbitrary type.
     *                 During publication, it should be serialized to the string.
     */
    protected mixed $key = null;

    /**
     * @var array<string> List of the headers.
     */
    protected array $headers = [];

    /**
     * @var int The Kafka topic partition number where the message should be produced.
     */
    protected int $partition = RD_KAFKA_PARTITION_UA;

    public function setTopicName(string $topicName) : MessageBuilder
    {
        $this->topicName = $topicName;

        return $this;
    }

    public function setBody(mixed $body) : MessageBuilder
    {
        $this->body = $body;

        return $this;
    }

    public function setKey(mixed $key) : MessageBuilder
    {
        $this->key = $key;

        return $this;
    }

    public function setHeaders(array $headers) : MessageBuilder
    {
        $this->headers = $headers;

        return $this;
    }

    public function setPartition(int $partition) : MessageBuilder
    {
        $this->partition = $partition;

        return $this;
    }

    /**
     * Create an instance of the message based on the configured properties.
     *
     * @return \Uc\KafkaProducer\Message
     */
    public function getMessage() : Message
    {
        if (null === $this->topicName) {
            throw new RuntimeException('Messages can not be created without topic name. Please provide name of the topic.');
        }

        return new Message(
            $this->topicName,
            $this->body,
            $this->key,
            $this->headers,
            $this->partition,
        );
    }
}
