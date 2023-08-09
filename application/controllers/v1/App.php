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

    public function index()
    {
        ob_start(“ob_gzhandler”);
        $data = [];
        $data['meta'] = [];
        $data['meta']['title'] = 'Homepage | AutomotoHR.com';
        $data['meta']['description'] = 'AutomotoHR Helps you differentiate your business and Brand from everyone else, with our People Operations platform Everything is in one place on one system Hire to Retire. So HOW DOES YOUR COMPANY STAND OUT? ';
        $data['meta']['keywords'] = 'AutomotoHR,People Operations platform,Business Differentiation,Brand Identity,One System Solution,Hire to Retire,Company Distinctiveness,HR Innovation,Unified HR Management,Branding Strategy,Employee Lifecycle,Streamlined Operations,Personnel Management,HR Efficiency,Competitive Advantage,Employee Experience,Seamless Integration,Organizational Uniqueness,HR Transformation,Comprehensive HR Solution';
        // stylesheets
        $data['pageCSS'] = [
            'v1/app/css/bootstrap',
            'v1/app/css/app'
        ];
        //
        $data['slider'] = [
            [
                'title'=> 'Effortlessly Manage HR, Benefits & Payroll!',
                'sub_title'=> 'Say goodbye to administrative hassles by embracing a simplified solution that serves all your HR needs –',
                'link'=> 'product-1',
                'link_text'=> 'product-1',
                'image' => 'assets/v1/app/images/banner_1.png'
            ],
            [
                'title' => 'Smart Onboarding with AutomotoHR!',
                'sub_title' => 'Leave behind inefficient onboarding methods and embrace <span class="anchar_tag">AutomotoHR</span> to optimize data management, expedite paperwork, & elevate orientation.',
                'link' => 'product-1',
                'link_text' => 'product-1',
                'image' => 'assets/v1/app/images/banner_2.png'
            ],
            [
                'title' => 'One-Stop Shop for HR & Hiring!',
                'sub_title' => 'Efficiently handle job postings, targeted advertising, candidate management, and assessment checks in one place.',
                'link' => 'product-1',
                'link_text' => 'product-1',
                'image' => 'assets/v1/app/images/banner_3.png'
            ]
        ];
        //
        $this->load
            ->view('v1/app/header', $data)
            ->view('v1/app/homepage')
            ->view('v1/app/footer');
    }

    /**
     * SCORM parse
     */
    public function parseScorm($courseId)
    {
        // SCORM file name
        $filePath = $this->input->post('scorm_file');
        // get the file to local
        $zipFilePath = copyAWSFile($filePath);
        $uploadPath = str_replace('.zip', '', $zipFilePath);
        // extract the file
        $zip = new ZipArchive;
        $res = $zip->open($zipFilePath);
        //
        if ($res !== true) {
            // unable to extract the file
            return SendResponse(404, ['status' => false, 'errors' => ['Unable to unzip file.']]);
        }
        // extract the file
        $zip->extractTo($uploadPath);
        $zip->close();
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

    /**
     * preview s3 document
     */
    public function previewDocument(): array
    {
        // get post
        $post = $this->input->post(
            null,
            true
        );
        // set error array
        $errorArray = [];
        //
        if (!$post['key']) {
            $errorArray[] = '"File name" is missing.';
        }
        //
        if (!$post['ext']) {
            $errorArray[] = '"File extension" is missing.';
        }
        //
        if ($errorArray) {
            return SendResponse(
                404,
                [
                    'errors' => $errorArray
                ]
            );
        }
        //
        return SendResponse(
            200,
            [
                'view' => $this->load->view('v1/file/preview', $post, true)
            ]
        );
    }

    /**
     * download dile from AWS
     *
     * @param string $key
     * @return void
     */
    public function downloadFileFromAWSAndStream(string $key): void
    {
        downloadAWSFileToBrowser($key);
    }
}
