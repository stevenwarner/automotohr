<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * 
 * @author  AutomotoHR
 * @link    www.automotohr.com
 * @author  Nisar Ahmed 
 * @version 1.0
 */
class Aws_pdf extends Public_Controller
{
    /**
     * main entry point
     * inherit all properties of parent
     */
    public function __construct()
    {
        // inherit
        parent::__construct();
        // load model      
    }


    //
    public function getFileBase64()
    {
        $this->copyObject($this->input->post("fileName"));
        echo 'success';
    }


    /**
     * 
     */
    public function copyObject($fileName)
    {
        // load the AWS library
        $this->load->library(
            "Aws_lib",
            '',
            "aws_lib"
        );

        $meta = [
            "ContentType" => "application/pdf",
            "ContentDisposition" => "inline",
            "logicByM" => "1"
        ];

        $options = [
            'Bucket'     => AWS_S3_BUCKET_NAME,
            'CopySource' => urlencode(AWS_S3_BUCKET_NAME . '/' . $fileName), // Source object
            'Key'        => $fileName, // Destination object
            'Metadata' => $meta,
            "MetadataDirective" => "REPLACE",
            "ContentType" => "application/pdf",
            'ACL'        => 'public-read', // Optional: specify the ACL (access control list)
        ];
        //
        $this->aws_lib->copyObject($options);

        return $fileName;
    }
    
}
