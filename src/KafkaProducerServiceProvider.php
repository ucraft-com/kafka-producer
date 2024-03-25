<?php

declare(strict_types=1);

namespace Uc\KafkaProducer;

use Illuminate\Support\ServiceProvider;
use Uc\KafkaProducer\Providers\EventServiceProvider;

use function config;

/**
 * Service provider of the package.
 *
 * @author Tigran Mesropyan <tiko@ucraft.com>
 */
class KafkaProducerServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'kafka-producer');
        $this->app->register(EventServiceProvider::class);

        $this->app->singleton(Producer::class, function () {
            $builder = new ProducerBuilder();

            return $builder
                ->setConfig([
                    'log_level'                             => config('kafka-producer.log_level'),
                    'debug'                                 => config('kafka-producer.debug'),
                    'bootstrap.servers'                     => config('kafka-producer.bootstrap_servers'),
                    'client.id'                             => config('kafka-producer.client_id'),
                    'enable.idempotence'                    => config('kafka-producer.idempotence'),
                    'compression.codec'                     => config('kafka-producer.compression_codec'),
                    'ssl.endpoint.identification.algorithm' => config(
                        'kafka-producer.ssl_endpoint_identification_algorithm'
                    ),
                    'security.protocol'                     => config('kafka-producer.security_protocol'),
                    'sasl.mechanisms'                       => config('kafka-producer.sasl_mechanisms'),
                    'sasl.username'                         => config('kafka-producer.sasl_username'),
                    'sasl.password'                         => config('kafka-producer.sasl_password'),
                    'socket.timeout.ms'                     => config('kafka-producer.socket_timeout_ms'),
                    'partitioner'                           => config('kafka-producer.partitioner'),
                    'message.max.bytes'                     => config('kafka-producer.message_max_bytes'),
                ])
                ->getProducer();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes(
                [
                    __DIR__.'/../config/config.php' => config_path('kafka-producer.php'),
                ],
                'kafka-producer-config'
            );
        }
    }
}
