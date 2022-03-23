<?php

declare(strict_types = 1);

namespace Uc\KafkaProducer\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Uc\KafkaProducer\Events\ProduceMessageEvent;
use Uc\KafkaProducer\Listeners\MessageProducerListener;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ProduceMessageEvent::class => [
            MessageProducerListener::class,
        ],
    ];
}
