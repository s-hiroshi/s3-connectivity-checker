<?php

namespace HSawai\S3ConnectivityChecker\Tests\Service;

use Aws\Exception\AwsException;
use Aws\Result;
use Aws\S3\S3Client;
use Exception;
use HSawai\S3ConnectivityChecker\Service\PutObject;
use Aws\MockHandler;
use Aws\CommandInterface;
use Psr\Http\Message\RequestInterface;

use PHPUnit\Framework\TestCase;

class PutObjectTest extends TestCase
{
    private $client;
    private $mock;

    protected function setUp(): void
    {
        $this->mock = new MockHandler();


        $this->client = new S3Client([
            'region' => 'ap-northeast-1',
            'version' => 'latest',
            'handler' => $this->mock,
            'credentials' => false,
        ]);
    }

    public function testPutObjectSuccess(): void
    {
        $this->mock->append(new Result(['foo' => 'bar']));
        $putObject = new PutObject($this->client);

        $this->assertNull($putObject->putObject('s3-connectivity-checker', 'tmp.txt'));
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testPutObjectException(): void
    {
        $this->expectException(Exception::class);
        $this->mock->append(function (CommandInterface $cmd, RequestInterface $req) {
            return new AwsException('Mock exception', $cmd);
        });

        $putObject = new PutObject($this->client);
        $putObject->putObject('s3-connectivity-checker', 'tmp.txt');
        $putObject->putObject('s3-connectivity-checker', 'tmp.txt');
    }
}
