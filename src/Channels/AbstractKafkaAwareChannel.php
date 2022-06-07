<?php

declare(strict_types = 1);

namespace Uc\KafkaProducer\Channels;

use Illuminate\Events\Dispatcher;
use Psr\Log\LoggerInterface;

abstract class AbstractKafkaAwareChannel
{
    /**
     * Reference on event dispatcher instance.
     *
     * @var \Illuminate\Events\Dispatcher
     */
    protected Dispatcher $dispatcher;

    /**
     * Reference on logger interface implementation instance.
     *
     * @var \Psr\Log\LoggerInterface
     */
    protected LoggerInterface $logger;

    /**
     * @param \Illuminate\Events\Dispatcher $dispatcher
     * @param \Psr\Log\LoggerInterface      $logger
     */
    public function __construct(Dispatcher $dispatcher, LoggerInterface $logger)
    {
        $this->dispatcher = $dispatcher;
        $this->logger = $logger;
    }
}
