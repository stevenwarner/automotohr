<?php
require_once FCPATH . '/application/libraries/aws-v3/aws-autoloader.php';

use Aws\S3\S3Client;

/**
 * Class Aws_lib
 */
class Aws_lib
{
    /**
     * S3 Singletone
     * @var S3Client
     */
    private $s3;

    //

    /**
     * Aws_lib constructor.
     */
    public function __construct()
    {
        //
        $awsCreds = getCreds('AHR')->AWS;
        //
        $this->s3 = S3Client::factory(array(
            'region' => $awsCreds->REGION,
            'version' => $awsCreds->VERSION,
            'credentials' => array(
                'key' => $awsCreds->CREDENTIALS->KEY,
                'secret' => $awsCreds->CREDENTIALS->SECRET
            )
        ));
    }


    /**
     * List All Buckets
     * @return array
     */
    public function list_buckets()
    {
        return $this->s3->listBuckets()->toArray();
    }

    /**
     * Get Single Object from Bucket and save as well
     * @param $bucket
     * @param $key
     * @param string $save_path
     * @return \Aws\Result
     */
    public function get_object($bucket, $key, $save_path = '')
    {
        $config = array();
        $config['Bucket'] = $bucket;
        $config['Key'] = $key;

        $is_return = false;

        if (!empty($save_path)) {
            $config['SaveAs'] = $save_path;
        } else {
            $is_return = true;
        }


        $result = $this->s3->getObject($config);

        if ($is_return == true) {
            return $result;
        }
    }

    /**
     * Uploads object(file or folder) to s3
     * @param $options
     * @return \Aws\Result|\Guzzle\Service\Resource\Model
     */
    public function put_object($options)
    {
        $result = $this->s3->putObject($options);
        return $result;
    }

    /**
     * Copy the object in S3
     * @param array $options
     * @return \Aws\Result|\Guzzle\Service\Resource\Model
     */
    public function copyObject($options)
    {
        return $this->s3->copyObject($options);
    }

    public function delete_object($bucket, $keyname, $objectType)
    {
        if ($objectType == "file") {
            $result = $this->s3->deleteObject(array(
                'Bucket' => $bucket,
                'Key' => $keyname
            ));
            return $result;
        } else if ($objectType == "folder") {
            $objects = [];
            $filesInside = $this->list_objects(S3_BUCKET_PORTAL, '', $keyname);
            foreach ($filesInside as $singleFile) {
                $objects[] = ['Key' => $singleFile['Key']];
            }

            $result = $this->s3->deleteObjects([
                'Bucket' => $bucket,
                'Delete' => [
                    'Objects' => $objects,
                    'Quiet' => false,
                ]
            ]);
            return $result;
        }

        return json_encode(['error' => 'invalid request']);
    }

    /**
     * Get Objects from a Bucket
     * @param $bucket
     * @return array
     */
    public function list_objects($bucket, $delimiter = '', $prefix = '')
    {

        $paginator = $this->s3->getPaginator(
            'ListObjects',
            ['Bucket' => $bucket, 'Delimiter' => $delimiter, 'Prefix' => $prefix]
        );

        $data = array();

        foreach ($paginator->search('Contents[*]') as $key => $obj) {
            $data[$key] = $obj;
        }

        return $data;
    }

    public function copy_object($sourceBucket, $sourceKeyname, $targetBucket, $targetKeyname, $acl_permission_string = 'public-read')
    {

        $file_exists = $this->s3->doesObjectExist($sourceBucket, $sourceKeyname);

        if ($file_exists) {
            $this->s3->copyObject(array(
                'Bucket' => $targetBucket,
                'Key' => $targetKeyname,
                'CopySource' => "{$sourceBucket}/{$sourceKeyname}",
                'ACL' => $acl_permission_string
            ));
        }
    }

    public function check_if_file_exists($bucket_name, $key_name)
    {
        return $this->s3->doesObjectExist($bucket_name, $key_name);
    }

    public function copy_files_buckets_to_server($bucket, $key, $path, $file_name)
    {
        $result = $this->s3->getObject(array(
            'Bucket' => $bucket,
            'Key'    => $key,
            'SaveAs' => $path . $file_name
        ));
        return $result;
    }

    /**
     * Get secure Single Object from Bucket and save as well
     * @param array $config
     * @return \Aws\Result
     */
    public function get_secure_object(array $config = [])
    {

        return $this->s3->getObject($config);
    }
}
