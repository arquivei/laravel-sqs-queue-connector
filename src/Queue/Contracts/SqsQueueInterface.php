<?php

namespace SqsQueueConnector\Queue\Contracts;

/**
 * Interface SqsQueueInterface
 * @package SqsQueueConnector\Queue\Jobs
 */
interface SqsQueueInterface
{
    public function __construct(\stdClass $data);
}