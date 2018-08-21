<?php

namespace SqsQueueConnector\Tests\Queue\Connectors;

use PHPUnit\Framework\TestCase;
use SqsQueueConnector\Queue\SqsQueue;
use SqsQueueConnector\Queue\Connectors\SqsConnector;

class SqsJobTest extends TestCase
{
    public function testInstanceOf()
    {
        $sqsConnector = $this->createMock(SqsConnector::class);
        $this->assertInstanceOf(SqsConnector::class, $sqsConnector);
    }

    public function testMethods()
    {
        $sqsConnector = $this->createMock(SqsConnector::class);
        $this->assertTrue(method_exists($sqsConnector, 'connect'));
        $this->assertTrue(method_exists($sqsConnector, 'getDefaultConfiguration'));
    }

    public function testReturnSqsQueue()
    {
        $config = [
            'key' => 'key',
            'secret' => 'key',
            'token' => 'token',
            'queue' => 'queue',
            'consumers' => [],
            'region' => 'us-west-2',
            'prefix' => 'prefix',
        ];

        $sqsConnector = new SqsConnector();

        $sqsQueue = $sqsConnector->connect($config);

        $this->assertInstanceOf(SqsQueue::class, $sqsQueue);
    }
}