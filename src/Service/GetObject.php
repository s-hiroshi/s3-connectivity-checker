<?php


namespace HSawai\S3ConnectivityChecker\Service;


use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Exception;

class GetObject
{
    private S3Client $client;

    public function __construct(S3Client $client)
    {
        $this->client = $client;
    }

    /**
     * @throws \Exception
     */
    public function getObject(string $bucket, string $filename)
    {
        try {
            $result = $this->client->getObject([
                'Bucket' => $bucket,
                'Key' => $filename,
                'SaveAs' => sprintf('%s.%s',$filename, strtotime('now')),
            ]);
        } catch (AwsException $e) {
            throw new Exception($e->getMessage());
        }
    }
}