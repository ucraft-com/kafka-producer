<?php

declare(strict_types = 1);

namespace Uc\KafkaProducer;

/**
 * Wrapper class of Kafka message.
 */
final class Message
{
    /**
     * @param string $topicName Name of the Kafka topic where the message should be published.
     * @param mixed  $body      Content of the message. The content can be in an arbitrary type.
     *                          During publication, it should be serialized to the string.
     * @param mixed  $key       Key of the message. The key can be in an arbitrary type.
     *                          During publication, it should be serialized to the string.
     * @param array  $headers   List of the headers.
     * @param int    $partition The Kafka topic partition number where the message should be produced.
     */
    public function __construct(
        protected string $topicName,
        protected mixed  $body,
        protected mixed  $key,
        protected array  $headers,
        protected int    $partition,
    )
    {

    }

    /**
     * Get the name of the Kafka topic where the message should be published.
     *
     * @return string
     */
    public function getTopicName() : string
    {
        return $this->topicName;
    }

    /**
     * Set the name of the Kafka topic where the message should be produced.
     *
     * @param string $topicName
     *
     * @return $this
     */
    public function setTopicName(string $topicName) : Message
    {
        $this->topicName = $topicName;

        return $this;
    }

    /**
     * Get the content of the message.
     * The content can be in an arbitrary type. During publication, it should be serialized to the string.
     *
     * @return mixed
     */
    public function getBody() : mixed
    {
        return $this->body;
    }

    /**
     * Set the content of the message.
     * The content can be in an arbitrary type. During publication, it should be serialized to the string.
     *
     * @param mixed $body
     *
     * @return $this
     */
    public function setBody(mixed $body) : Message
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get the key of the message.
     * The key can be in an arbitrary type. During publication, it should be serialized to the string.
     *
     * @return mixed
     */
    public function getKey() : mixed
    {
        return $this->key;
    }

    /**
     * Set the key of the message.
     * The key can be in an arbitrary type. During publication, it should be serialized to the string.
     *
     * @return mixed
     */
    public function setKey(mixed $key) : Message
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Get the list of the headers.
     *
     * @return array<string>
     */
    public function getHeaders() : array
    {
        return $this->headers;
    }

    /**
     * Set the list of the headers.
     *
     * @param array<string> $headers
     *
     * @return $this
     */
    public function setHeaders(array $headers) : Message
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * Get the Kafka topic partition number where the message should be produced.
     *
     * @return int
     */
    public function getPartition() : int
    {
        return $this->partition;
    }

    /**
     * Set the Kafka topic partition number where the message should be produced.
     *
     * @param int $partition
     *
     * @return $this
     */
    public function setPartition(int $partition) : Message
    {
        $this->partition = $partition;

        return $this;
    }
}
