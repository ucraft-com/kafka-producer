<?php

declare(strict_types = 1);

namespace Uc\KafkaProducer\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Uc\KafkaProducer\Message;

/**
 * Fire this event whenever we want to produce a new message into Kafka.
 *
 * @author Tigran Mesropyan <tiko@ucraft.com>
 */
class ProduceMessageEvent
{
    use Dispatchable;

    /**
     * Initialize properties.
     *
     * @param \Uc\KafkaProducer\Message $message Reference to the instance of the message object.
     */
    public function __construct(
        protected Message $message
    )
    {
    }

    /**
     * Return the message instance.
     *
     * @return \Uc\KafkaProducer\Message
     */
    public function getMessage() : Message
    {
        return $this->message;
    }
}
