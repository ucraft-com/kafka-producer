<?php

declare(strict_types = 1);

namespace Uc\KafkaProducer\Listeners;

use RdKafka\Conf;
use RdKafka\Producer;
use RuntimeException;
use Uc\KafkaProducer\Events\ProduceMessageEvent;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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
    protected Serializer $serializer;

    public function __construct()
    {
        $this->serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
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
        $producer = $this->createProducer();
        $message = $event->getMessage();

        $topic = $producer->newTopic($message->getTopicName());

        $topic->producev(
            $message->getPartition(),
            RD_KAFKA_MSG_F_BLOCK,
            $this->serializer->serialize($message->getBody(), 'json'),
            $this->serializer->serialize($message->getKey(), 'json'),
            $message->getHeaders(),
        );

        $producer->poll(0);

        for ($flushRetries = 0; $flushRetries < 10; $flushRetries++) {
            $result = $producer->flush(10000);
            if (RD_KAFKA_RESP_ERR_NO_ERROR === $result) {
                break;
            }
        }

        if (RD_KAFKA_RESP_ERR_NO_ERROR !== $result) {
            throw new RuntimeException('Was unable to flush, messages might be lost!');
        }
    }

    protected function createProducer() : Producer
    {
        $config = $this->createProducerConfig();

        return new Producer($config);
    }

    protected function createProducerConfig() : Conf
    {
        $conf = new Conf();

        $conf->set('log_level', config('kafka-producer.log_level'));
        if (config('kafka-producer.debug')) {
            $conf->set('debug', config('kafka-producer.debug'));
        }

        if (extension_loaded('pcntl')) {
            pcntl_sigprocmask(SIG_BLOCK, [SIGIO]);
            $conf->set('internal.termination.signal', (string) SIGIO);
        } else {
            $conf->set('queue.buffering.max.ms', 1);
        }

        $conf->set('bootstrap.servers', config('kafka-producer.bootstrap_servers'));
        $conf->set('client.id', config('kafka-producer.client_id'));
        $conf->set('enable.idempotence', config('kafka-producer.idempotence'));
        $conf->set('compression.codec', config('kafka-producer.compression_codec'));
        $conf->set('ssl.endpoint.identification.algorithm', config('kafka-producer.ssl_endpoint_identification_algorithm'));
        $conf->set('security.protocol', config('kafka-producer.security_protocol'));
        $conf->set('sasl.mechanisms', config('kafka-producer.sasl_mechanisms'));
        $conf->set('sasl.username', config('kafka-producer.sasl_username'));
        $conf->set('sasl.password', config('kafka-producer.sasl_password'));
        $conf->set('socket.timeout.ms', config('kafka-producer.socket_timeout_ms'));
        $conf->set('partitioner', config('kafka-producer.partitioner'));

        return $conf;
    }
}
