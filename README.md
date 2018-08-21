# Laravel SQS Queue Connector
Consumer queues with different event structures Illuminate pattern

## Install

    composer require arquivei/laravel-sqs-queue-connector
    
Add queue config in section `connections` in your `config/queue.php`
```
    'sqs-custom' => [
        'driver' => 'sqs-custom',
        'key' => env('AWS_KEY', 'your-public-key'),
        'secret' => env('AWS_SECRET', 'your-secret-key'),
        'prefix' => env('AWS_SQS_PREFIX', 'https://sqs.us-east-1.amazonaws.com/your-account-id'),
        'queue' => env('AWS_EVENTS_QUEUE', 'your-queue-name'),
        'region' => env('AWS_REGION', 'us-east-1'),
        'consumers' => [
            [
                'validation' => [
                    'key' => [],
                    'value' => '',
                ],
                'job' => YourConsumerJob::class,
            ],
            [
                'validation' => [
                    'key' => [],
                    'value' => '',
                ],
                'job' => YourConsumerJob::class,
            ],
        ],
    ],
```

##### Important

- Your consumers should implement contract interface: `SqsQueueConnector\Queue\Contracts\SqsQueueInterface`

## Usage

```php
<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use SqsQueueConnector\Queue\Contracts\SqsQueueInterface;

class ConsumerJob implements ShouldQueue, SqsQueueInterface
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $data;

    public function __construct(\stdClass $data)
    {
        $this->data = $data;
    }

    public function handle()
    {
        print_r([
            'data' => $this->data,
        ]);
    }
}
```

## Run Tests

``$ vendor/phpunit/phpunit/phpunit tests``

## TODO
- Add validation for default consumer
- Add any validation option for one consumer