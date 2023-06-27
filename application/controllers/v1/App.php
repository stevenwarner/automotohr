<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Holds all public functions
 *
 * @package AutomotoHR
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 */
class App extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * SCORM parse
     */
    public function parseScorm($courseId)
    {
        // SCORM file name
        $filePath = $this->input->post('scorm_file');
        // get the file to local
        $uploadPath = copyAWSFile($filePath);
        // extract the file
        $zip = new ZipArchive;
        $res = $zip->open($uploadPath);
        $zip->close();
        // unable to extract the file
        if ($res !== true) {
            return SendResponse(404, ['status' => false, 'errors' => ['Unable to unzip file.']]);
        }
        // set the "IMSmanifest" file
        $file = $uploadPath . '/imsmanifest.xml';
        // check if the file exists
        if (!file_exists($file)) {
            return SendResponse(404, ['status' => false, 'errors' => ['"IMSmanifest.xml" file is missing.']]);
        }
        // read the file
        $handler = fopen($file, 'r');
        $fileContents = fread($handler, filesize($file));
        fclose($handler);
        // load library
        $this->load->library('scorm/parser', [], 'scorm_parser');
        // content to JSON
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
        return SendResponse(200, ['status' => true, 'success' => ['SCORM file read successfully.']]);
    }
}
