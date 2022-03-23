<?php

declare(strict_types = 1);

namespace Uc\KafkaProducer\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Junges\Kafka\Message\Message;

/**
 * Fire this event whenever we want to produce a new message into Kafka.
 */
class ProduceMessageEvent
{
    use Dispatchable;

    /**
     * @param \Junges\Kafka\Message\Message $message Reference to the intsance of the message object.
     */
    public function __construct(
        protected Message $message
    )
    {
    }

    /**
     * Return the message instance.
     *
     * @return \Junges\Kafka\Message\Message
     */
    public function getMessage() : Message
    {
        return $this->message;
    }
}
