<?php

declare(strict_types = 1);

namespace Uc\KafkaProducer;

class MessageBuilder
{
    protected string $topicName;

    protected mixed $body = null;

    protected mixed $key = null;

    protected array $headers = [];

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

    public function getMessage() : Message
    {
        return new Message(
            $this->topicName,
            $this->body,
            $this->key,
            $this->headers,
            $this->partition,
        );
    }
}
