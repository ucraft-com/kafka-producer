<?php

declare(strict_types = 1);

namespace Uc\KafkaProducer\Listeners;

use Uc\KafkaProducer\Events\ProduceMessageEvent;
use Junges\Kafka\Facades\Kafka;

use function config;
use function extension_loaded;
use function pcntl_sigprocmask;

/**
 * Listen for ProduceMessageEvent and produce data into Kafka.
 *
 * @see \Uc\KafkaProducer\Events\ProduceMessageEvent
 */
class MessageProducerListener
{
    /**
     * Produce message instance into Kafka topic.
     *
     * @param \Uc\KafkaProducer\Events\ProduceMessageEvent $event
     *
     * @return void
     */
    public function handle(ProduceMessageEvent $event) : void
    {
        $message = $event->getMessage();
        $producer = Kafka::publishOn($message->getTopicName())
            ->withConfigOptions([
                'client.id'                             => config('kafka-producer.client_id'),
                'enable.idempotence'                    => config('kafka-producer.idempotence'),
                'compression.codec'                     => 'snappy',
                'ssl.endpoint.identification.algorithm' => config('kafka-producer.ssl_endpoint_identification_algorithm'),
                'security.protocol'                     => config('kafka-producer.security_protocol'),
                'sasl.mechanisms'                       => config('kafka-producer.sasl_mechanisms'),
                'sasl.username'                         => config('kafka-producer.sasl_username'),
                'sasl.password'                         => config('kafka-producer.sasl_password'),
                'socket.timeout.ms'                     => config('kafka-producer.socket_timeout_ms'),
            ]);

        if (extension_loaded('pcntl')) {
            pcntl_sigprocmask(SIG_BLOCK, [SIGIO]);
            $producer->withConfigOption('internal.termination.signal', (string) SIGIO);
        }

        $producer->withMessage($message);
        $producer->send();
    }
}
