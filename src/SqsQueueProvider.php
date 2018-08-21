<?php

namespace SqsQueueConnector;

use Illuminate\Support\ServiceProvider;
use SqsQueueConnector\Queue\Connectors\SqsConnector;

/**
 * Class SqsQueueProvider
 * @package SqsQueueConnector
 */
class SqsQueueProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->booted(function () {
            $manager = $this->app['queue'];
            $manager->addConnector('sqs-custom', function () {
                return new SqsConnector;
            });
        });
    }
}