<?php

declare(strict_types = 1);

namespace Uc\KafkaProducer\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Uc\KafkaProducer\Events\ProduceMessageEvent;
use Uc\KafkaProducer\Listeners\MessageProducerListener;

/**
 * @author Tigran Mesropyan <tiko@ucraft.com>
 */
class EventServiceProvider extends ServiceProvider
{
    /**
     * @var array The event handler mappings for the application.
     */
    protected $listen = [
        ProduceMessageEvent::class => [
            MessageProducerListener::class,
        ],
    ];
}
