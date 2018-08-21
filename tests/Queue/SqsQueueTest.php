<?php

namespace SqsQueueConnector\Tests\Queue;

use PHPUnit\Framework\TestCase;
use SqsQueueConnector\Queue\SqsQueue;

class SqsQueueTest extends TestCase
{
    public function testInstanceOf()
    {
        $sqsQueue = $this->createMock(SqsQueue::class);
        $this->assertInstanceOf(SqsQueue::class, $sqsQueue);
    }

    public function testMethods()
    {
        $sqsQueue = $this->createMock(SqsQueue::class);
        $this->assertTrue(method_exists($sqsQueue, 'pop'));
    }
}