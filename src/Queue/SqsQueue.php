<?php

namespace SqsQueueConnector\Queue;

use Aws\Sqs\SqsClient;
use SqsQueueConnector\Queue\Jobs\SqsJob;

/**
 * Class SqsQueue
 * @package SqsQueueConnector\Queue
 */
class SqsQueue extends \Illuminate\Queue\SqsQueue
{
    /**
     * @var array
     */
    private $consumers;

    /**
     * SqsQueue constructor.
     * @param SqsClient $sqs
     * @param string $default
     * @param array $consumers
     * @param string $prefix
     */
    public function __construct(
        SqsClient $sqs,
        string $default,
        array $consumers,
        string $prefix = ''
    ) {
        parent::__construct($sqs, $default, $prefix);
        $this->consumers = $consumers;
    }

    /**
     * @param null $queue
     * @return \Illuminate\Contracts\Queue\Job|null|SqsJob
     */
    public function pop($queue = null)
    {
        $response = $this->sqs->receiveMessage([
            'QueueUrl' => $queue = $this->getQueue($queue),
            'AttributeNames' => ['ApproximateReceiveCount'],
        ]);

        if (!is_null($response['Messages']) && count($response['Messages']) > 0) {
            return new SqsJob(
                $this->container,
                $this->sqs,
                $response['Messages'][0],
                $this->connectionName,
                $queue,
                $this->consumers
            );
        }
    }
}