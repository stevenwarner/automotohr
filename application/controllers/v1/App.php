<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 *
 */
class App extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        //
    }

    /**
    * scorm parse
    */
    public function parseScorm($courseId)
    {
        $filePath = $this->input->post('scorm_file');
        //
        $uploadPath = copyAWSFile($filePath);
        //
        // $zip = new ZipArchive;
        // $res = $zip->open($uploadPath);
        // $zip->close();
        // //
        // if ($res !== TRUE) {
        //     return SendResponse(404, ['status' => false, 'errors' => ['Unable to unzip file.']]);
        // }
        //
        $newFolder = ROOTPATH.'uploads/'.str_replace(".zip", "", $filePath);
        //
        $file = $newFolder . '/imsmanifest.xml';
        echo $file;
        //
        if (!file_exists($file)) {
            return SendResponse(404, ['status' => false, 'errors' => ['"imsmanifest.xml" file is missing.']]);
        }
        //
        $handler = fopen($file, 'r');
        $fileContents = fread($handler, filesize($file));
        fclose($handler);
        //
        $this->load->library('scorm/parser', [], 'scorm_parser');
        //
        $scormInfo = $this
            ->scorm_parser
            ->setContent($fileContents)
            ->parse();
        //
        if (!$scormInfo) {
            return SendResponse(404, ['status' => false, 'errors' => ['Unable to read XML file.']]);
        }   
        //
        if (isset($scormInfo['errors'])) {
            // Todo delete AWS file and also local one if version not matched
            return SendResponse(404, ['status' => false, 'errors' => $scormInfo['errors']]);
        }
        //
        $this->db->where("sid", $courseId);
        $this->db->update("lms_default_courses", ["Imsmanifist_json" => $scormInfo]);
        //
        return SendResponse(200, ['status' => true, 'success' => ['Scorm file read successfully.']]);
   }
}