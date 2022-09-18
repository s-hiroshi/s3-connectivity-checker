<?php

namespace HSawai\S3ConnectivityChecker\Service;


use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Exception;

class PutObject
{
    private S3Client $client;

    public function __construct(S3Client $client)
    {
        $this->client = $client;
    }
    
    private function createLocalFile($filename) {
        file_put_contents($filename, date("Y-m-d H:i:s"));
    }

    /**
     * @throws \Exception
     */
    public function putObject(string $bucket, $filename)
    {
        try {
            $this->createLocalFile($filename);
            $result = $this->client->putObject([
                'Bucket' => $bucket,
                'Key' => $filename,
                'SourceFile' => $filename,
            ]);
        } catch (AwsException $e) {
            throw new Exception($e->getMessage());
        }
    }
}