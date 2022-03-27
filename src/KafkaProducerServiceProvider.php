<?php

declare(strict_types = 1);

namespace Uc\KafkaProducer;

use Illuminate\Support\ServiceProvider;
use Uc\KafkaProducer\Providers\EventServiceProvider;

/**
 * Service provider of the package.
 */
class KafkaProducerServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() : void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'kafka-producer');
        $this->app->register(EventServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() : void
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
