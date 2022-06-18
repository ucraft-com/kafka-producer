<?php

declare(strict_types = 1);

namespace Uc\KafkaProducer\Listeners;

use Uc\KafkaProducer\Producer;
use Uc\KafkaProducer\Events\ProduceMessageEvent;

/**
 * Listen for ProduceMessageEvent and produce data into Kafka.
 *
 * @see    \Uc\KafkaProducer\Events\ProduceMessageEvent
 *
 * @author Tigran Mesropyan <tiko@ucraft.com>
 */
class MessageProducerListener
{
    protected Producer $producer;

    public function __construct(Producer $producer)
    {
        $this->producer = $producer;
    }

    /**
     * Produce message instance into Kafka topic.
     *
     * @param \Uc\KafkaProducer\Events\ProduceMessageEvent $event
     *
     * @return void
     */
    public function handle(ProduceMessageEvent $event) : void
    {
        $this->producer->produce($event->getMessage());
    }
}
