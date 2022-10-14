<?php

namespace HSawai\S3ConnectivityChecker\Tests\Command;

use Aws\Exception\AwsException;
use Aws\Result;
use Aws\S3\S3Client;
use Aws\MockHandler;
use Aws\CommandInterface;
use Exception;
use HSawai\S3ConnectivityChecker\Command\PutCommand;
use HSawai\S3ConnectivityChecker\Service\PutObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Symfony\Component\Console\Tester\CommandTester;

class putObjectCommandTest extends TestCase
{
    private $client;
    private $mockHandler;

    protected function setUp(): void
    {
        $this->mockHandler = new MockHandler();
        $this->mockHandler->append(new Result(['foo' => 'bar'])); // Call S3Client::putObject
        // Call S3Client::receiveMessage with AwsException
        $this->mockHandler->append(function (CommandInterface $cmd, RequestInterface $req) {
            return new AwsException('Mock exception', $cmd);
        });
        $this->client = new S3Client([
            'region' => 'ap-northeast-1',
            'version' => 'latest',
            'handler' => $this->mockHandler,
            'credentials' => false,
        ]);
    }

    public function testPutCommandSuccess(): void
    {
        $putObject = new PutObject($this->client);

        $command = new PutCommand($putObject);
        $commandTester = new CommandTester($command);
        $actual = $commandTester->execute([]);

        $this->assertEquals(0, $actual);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testPutCommandFailed(): void
    {
        $putObject = new PutObject($this->client);
        $putObject->putObject('sample-bucket', 'sample');

        $command = new PutCommand($putObject);
        $commandTester = new CommandTester($command);
        $actual = $commandTester->execute([]);

        $this->assertEquals(255, $actual);

    }
}