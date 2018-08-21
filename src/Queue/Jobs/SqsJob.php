<?php

namespace SqsQueueConnector\Queue\Jobs;

use Aws\Sqs\SqsClient;
use Illuminate\Container\Container;
use SqsQueueConnector\Queue\Contracts\SqsQueueInterface;

/**
 * Class SqsJob
 * @package SqsQueueConnector\Queue\Jobs
 */
class SqsJob extends \Illuminate\Queue\Jobs\SqsJob
{
    /**
     * @var array
     */
    private $consumers;

    /**
     * SqsJob constructor.
     * @param Container $container
     * @param SqsClient $sqs
     * @param array $job
     * @param string $connectionName
     * @param string $queue
     * @param array $consumers
     */
    public function __construct(
        Container $container,
        SqsClient $sqs,
        array $job,
        string $connectionName,
        string $queue,
        array $consumers
    ) {
        parent::__construct($container, $sqs, $job, $connectionName, $queue);
        $this->consumers = $consumers;
    }

    /**
     * This function check if yours validations exists in message body
     * If it exists will return a Illuminate pattern job with the job passed in validation
     *
     * @return string
     */
    public function getRawBody()
    {
        $job = $this->job['Body'];

        $classConsumer = null;
        foreach ($this->consumers as $consumer) {
            $validation = $this->getValueValidation($consumer);

            if ($validation === $consumer['validation']['value'] &&
                in_array(SqsQueueInterface::class, class_implements($consumer['job']))
            ) {
                $classConsumer = $consumer['job'];
                break;
            }
        }

        if (!is_null($classConsumer)) {
            $job = json_encode([
                'displayName' => $classConsumer,
                'job' => 'Illuminate\Queue\CallQueuedHandler@call',
                'maxTries' => null,
                'timeout' => null,
                'timeoutAt' => null,
                'data' => [
                    'commandName' => $classConsumer,
                    'command' => serialize(new $classConsumer(json_decode($job))),
                ],
            ]);
        }

        return $job;
    }

    /**
     * @param array $consumer
     * @return mixed
     */
    private function getValueValidation(array $consumer)
    {
        $validation = json_decode($this->job['Body']);
        foreach ($consumer['validation']['key'] as $key) {
            $validation = $validation->$key ?? $validation;
        }

        return $validation;
    }
}