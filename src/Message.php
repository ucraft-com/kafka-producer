<?php

declare(strict_types = 1);

namespace Uc\KafkaProducer;

final class Message
{
    public function __construct(
        protected string $topicName,
        protected mixed  $body,
        protected mixed  $key,
        protected array  $headers,
        protected int    $partition,
    )
    {

    }

    public function getTopicName() : string
    {
        return $this->topicName;
    }

    public function setTopicName(string $topicName) : Message
    {
        $this->topicName = $topicName;

        return $this;
    }

    public function getBody() : mixed
    {
        return $this->body;
    }

    public function setBody(mixed $body) : Message
    {
        $this->body = $body;

        return $this;
    }

    public function getKey() : mixed
    {
        return $this->key;
    }

    public function setKey(mixed $key) : Message
    {
        $this->key = $key;

        return $this;
    }

    public function getHeaders() : array
    {
        return $this->headers;
    }

    public function setHeaders(array $headers) : Message
    {
        $this->headers = $headers;

        return $this;
    }

    public function getPartition() : int
    {
        return $this->partition;
    }

    public function setPartition(int $partition) : Message
    {
        $this->partition = $partition;

        return $this;
    }
}
