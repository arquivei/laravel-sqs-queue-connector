<?php

namespace SqsQueueConnector\Tests\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use SqsQueueConnector\Queue\Contracts\SqsQueueInterface;

/**
 * Class ConsumerJob
 * @package SqsQueueConnector\Tests\Jobs
 */
class ConsumerJob implements ShouldQueue, SqsQueueInterface
{
    /*
     * Implements traits queue
     */

    /**
     * @var \stdClass
     */
    private $data;

    /**
     * ConsumerJob constructor.
     * @param \stdClass $data
     */
    public function __construct(\stdClass $data)
    {
        $this->data = $data;
    }
}
