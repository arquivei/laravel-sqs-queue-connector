<?php

namespace SqsQueueConnector\Tests\Queue\Jobs;

use Aws\Sqs\SqsClient;
use PHPUnit\Framework\TestCase;
use Illuminate\Container\Container;
use SqsQueueConnector\Queue\Jobs\SqsJob;
use SqsQueueConnector\Tests\Jobs\ConsumerJob;

class SqsJobTest extends TestCase
{
    public function testInstanceOf()
    {
        $sqsJob = $this->createMock(SqsJob::class);
        $this->assertInstanceOf(SqsJob::class, $sqsJob);
    }

    public function testMethods()
    {
        $sqsJob = $this->createMock(SqsJob::class);
        $this->assertTrue(method_exists($sqsJob, 'getRawBody'));
        $this->assertTrue(method_exists($sqsJob, 'getValueValidation'));
    }

    public function testReturnBody()
    {
        $containerMock = $this->createMock(Container::class);
        $sqsClientMock = $this->createMock(SqsClient::class);


        $sqsJob = new SqsJob(
            $containerMock,
            $sqsClientMock,
            ['Body' => json_encode($this->bodyMock())],
            'sqs-custom',
            'queue',
            $this->consumersMock()
        );

        $this->assertEquals($this->returnMock(), $sqsJob->getRawBody());
    }

    private function bodyMock(): \stdClass
    {
        $obj = new \stdClass();
        $obj->Data = true;

        return $obj;
    }

    private function consumersMock(): array
    {
        return [
            [
                'validation' => [
                    'key' => ['Data'],
                    'value' => true,
                ],
                'job' => ConsumerJob::class,
            ]
        ];
    }

    private function returnMock(): string
    {
        return json_encode([
            'displayName' => ConsumerJob::class,
            'job' => 'Illuminate\Queue\CallQueuedHandler@call',
            'maxTries' => null,
            'timeout' => null,
            'timeoutAt' => null,
            'data' => [
                'commandName' => ConsumerJob::class,
                'command' => serialize(new ConsumerJob($this->bodyMock())),
            ],
        ]);
    }
}