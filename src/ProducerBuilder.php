<?php

declare(strict_types = 1);

namespace Uc\KafkaProducer;

use RdKafka\Conf;
use RdKafka\Producer as KafkaProducer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\JsonSerializableNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\UidNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

use function extension_loaded;
use function pcntl_sigprocmask;
use function array_filter;

/**
 * Builder utility to create a Producer instances.
 *
 * @author Tigran Mesropyan <tiko@ucraft.com>
 */
class ProducerBuilder
{
    /**
     * @var array<string, string> List of key-value pairs of Kafka producer configuration.
     */
    protected array $config = [];

    /**
     * @var \Symfony\Component\Serializer\SerializerInterface Reference on the instance of Symfony serializer.
     */
    protected SerializerInterface $serializer;

    public function __construct()
    {
        $this->serializer = new Serializer(
            [new JsonSerializableNormalizer(), new UidNormalizer(), new DateTimeNormalizer(), new ObjectNormalizer()],
            [new JsonEncoder()]
        );
    }

    /**
     * Set configuration.
     *
     * @param array<string, string> $config
     *
     * @return $this
     */
    public function setConfig(array $config) : ProducerBuilder
    {
        $this->config = $config;

        return $this;
    }

    /**
     * Set specific configuration property.
     *
     * @param string $property
     * @param string $value
     *
     * @return $this
     */
    public function setConfigProperty(string $property, string $value) : ProducerBuilder
    {
        $this->config[$property] = $value;

        return $this;
    }

    /**
     * Remove specific configuration property.
     *
     * @param string $property
     *
     * @return $this
     */
    public function removeConfigProperty(string $property) : ProducerBuilder
    {
        unset($this->config[$property]);

        return $this;
    }

    /**
     * Get configuration.
     * Filter null values.
     *
     * @return array<string, string>
     */
    public function getConfig() : array
    {
        return array_filter($this->config, fn($value, $key) => null !== $value, ARRAY_FILTER_USE_BOTH);
    }

    /**
     * Set the Serializer instance.
     *
     * @return \Symfony\Component\Serializer\SerializerInterface
     */
    public function getSerializer() : SerializerInterface
    {
        return $this->serializer;
    }

    /**
     * Get the Serializer instance.
     *
     * @param \Symfony\Component\Serializer\SerializerInterface $serializer
     *
     * @return ProducerBuilder
     */
    public function setSerializer(SerializerInterface $serializer) : ProducerBuilder
    {
        $this->serializer = $serializer;

        return $this;
    }

    /**
     * Get Producer instance.
     *
     * @return \Uc\KafkaProducer\Producer
     */
    public function getProducer() : Producer
    {
        $producer = new KafkaProducer($this->configureProducer());

        return new Producer($producer, $this->serializer);
    }

    /**
     * Create configuration object for the producer.
     *
     * @return \RdKafka\Conf
     */
    protected function configureProducer() : Conf
    {
        $conf = new Conf();

        foreach ($this->getConfig() as $name => $value) {
            $conf->set($name, $value);
        }

        if (extension_loaded('pcntl')) {
            pcntl_sigprocmask(SIG_BLOCK, [SIGIO]);
            $conf->set('internal.termination.signal', (string) SIGIO);
        } else {
            $conf->set('queue.buffering.max.ms', 1);
        }

        return $conf;
    }
}
