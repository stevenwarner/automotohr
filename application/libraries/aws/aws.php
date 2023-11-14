<?php
require 'aws-autoloader.php';

use Aws\S3\S3Client;
use Aws\Common\Exception\MultipartUploadException;
use Aws\S3\Model\MultipartUpload\UploadBuilder;

class AwsSdk
{
    private $client;

    function __construct()
    {
        //
        $awsCredentials = getCreds('AHR')->AWS->CREDENTIALS;
        //
        $this->client = S3Client::factory(array(
            'key' => $awsCredentials->KEY,
            'secret' => $awsCredentials->SECRET
        ));
    }

    public function getFromBucket($key, $bucket)
    {
        $result = $this->client->getObject(array(
            'Bucket' => $bucket,
            'Key'    => $key
        ));
        return $result;
    }

    public function putToBucket($key, $filePath, $bucket)
    {

        ini_set('max_execution_time', 0);
        $uploader = UploadBuilder::newInstance()
            ->setClient($this->client)
            ->setSource($filePath)
            ->setBucket($bucket)
            ->setKey($key)
            ->setOption('Metadata', array('Foo' => 'Bar'))
            ->setOption('CacheControl', 'max-age=3600')
            ->setOption('ACL', 'public-read')
            ->build();

        // Perform the upload. Abort the upload if something goes wrong
        try {
            $uploader->upload();
            return 1;
        } catch (MultipartUploadException $e) {
            $uploader->abort();
            return 0;
        }
    }

    public function uploadBase64File(
        string $key,
        string $base64,
        string $contentType,
        string $bucket
    ) {
        ini_set('max_execution_time', 0);
        try {
            $result = $this->client
                ->putObject([
                    'ACL' => 'public-read',
                    'Body' => $base64,
                    'Bucket' => $bucket,
                    'Key' => $key,
                    'ContentType' => $contentType
                ]);
            return $result;
        } catch (MultipartUploadException $e) {
            return 0;
        }
    }

    public function deleteObj($key, $bucket)
    {
        $result = $this->client->deleteObject(array(
            'Bucket' => $bucket,
            'Key'    => $key
        ));
        return $result;
    }

    public function createBucket($bucket)
    {

        $result = $this->client->createBucket(array(
            'Bucket' => $bucket
        ));
        // Wait until the bucket is created
        $this->client->waitUntilBucketExists(array('Bucket' => $bucket));
    }

    public function deleteBucket($bucket)
    {
        $result = $this->client->deleteBucket(array(
            'Bucket' => $bucket
        ));
    }

    public function getUrl($key, $bucket)
    {
        $plainUrl = $this->client->getObjectUrl($bucket, $key);
        return $plainUrl;
    }
}

//main
/*
$aws = new AwsSdk();

$res = $aws->getFromBucket('test.txt' , 'automototest');

echo "Get result: ".$res['Body'];

//$aws->putToBucket('new.jpg', 'test.jpg', 'automototest');

//$aws->deleteObj('new.jpg', 'automototest');

$aws->deleteBucket('wallyBucket');

*/
