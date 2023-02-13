<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Employee Survey Module
 *
 * PHP version = 7.4.25
 *
 * @category   Module
 * @package    LMS Courses
 * @author     AutomotoHR <www.automotohr.com>
 * @author     Mubashir Ahmed
 * @version    1.0
 * @link       https://www.automotohr.com
 */

class Courses extends Public_Controller
{
    // Set page path
    private $pp;
    // Set mobile path
    private $mp;
    /**
     * Holds the pages
     */
    private $pages;
    //
    private $res = array();
    //
    private $scorm_versions;

    /**
     * 
     */
    public function __construct()
    {
        // Inherit parent properties and methods
        parent::__construct();
        // Load user agent
        $this->load->library('user_agent');
        //
        $this->pages = [
            'header' => 'main/header_2022',
            'footer' => 'main/footer_2022',
        ];
        //
        $this->mp = $this->agent->is_mobile() ? 'mobile/' : '';
        //
        $this->res['Status'] = FALSE;
        $this->res['Redirect'] = TRUE;
        $this->res['Response'] = 'Invalid request';
        $this->res['Code'] = 'INVALIDREQUEST';
        //
        $this->scorm_versions = [
            "20043rd",
            "20044th",
            "12"
        ];
        //    
        $this->load->model("2022/Course_model", "cm");
    }


    /**
     *
     */
    public function overview()
    {
        //
        if ($this->session->userdata('logged_in')) {
            $data = [];
            $data['session'] = $this->session->userdata('logged_in');
            $employee_detail = $data['session']['employer_detail'];
            $company_detail  = $data['session']['company_detail'];
            $employee_sid  = $employee_detail['sid'];
            $ems_status = $company_detail['ems_status'];

            if (!$ems_status) {
                $this->session->set_flashdata('message', '<strong>Warning</strong> Not Allowed!');
                redirect('dashboard', 'refresh');
            }


            $data['load_view'] = 1;
            $data['session'] = $this->session->userdata('logged_in');
            $data['security_details'] = db_get_access_level_details($employee_sid);
            $data['employee'] = $employee_detail;
            //
            $this->load
                ->view($this->pages['header'], $data)
                ->view("{$this->mp}courses/overview")
                ->view($this->pages['footer']);
        } else {
            redirect(base_url('login'), 'refresh');
        }
    }

    /**
     *
     */
    public function surveys()
    {
        //
        $data = [];
        $data['load_view'] = 1;
        $data['session'] = $this->session->userdata('logged_in');
        //
        $this->load
            ->view($this->pages['header'], $data)
            ->view("{$this->mp}es/surveys")
            ->view($this->pages['footer']);
    }

    /**
     *
     */
    public function create()
    {
        //
        if ($this->session->userdata('logged_in')) {
            $data = [];
            $data['session'] = $this->session->userdata('logged_in');
            $employee_detail = $data['session']['employer_detail'];
            $company_detail  = $data['session']['company_detail'];
            $employee_sid  = $employee_detail['sid'];
            $ems_status = $company_detail['ems_status'];
            //
            if (!$ems_status) {
                $this->session->set_flashdata('message', '<strong>Warning</strong> Not Allowed!');
                redirect('dashboard', 'refresh');
            }
            //
            $data['load_view'] = 1;
            $data['session'] = $this->session->userdata('logged_in');
            $data['security_details'] = db_get_access_level_details($employee_sid);
            $data['employee'] = $employee_detail;
            //
            $data['PageCSS'] = [
                'mFileUploader/index'
            ];
            //
            $data['PageScripts'] = [
                '2022/js/courses/create',
                'mFileUploader/index'
            ];
            //
            $this->load
                ->view($this->pages['header'], $data)
                ->view("{$this->mp}courses/create")
                ->view($this->pages['footer']);
        } else {
            redirect(base_url('login'), 'refresh');
        }
    }

     /**
     * AJAX request handler
     *
     * @accepts POST
     * 'action'
     *
     * @return JSON
     */
    function handler()
    {
        // Check for ajax request
        if (!$this->input->is_ajax_request()) $this->resp();
        ///
        $post = $this->input->post(NULL, TRUE);
        // Check post size and action
        if (!sizeof($post) || !isset($post['action'])) $this->resp();
        if (!isset($post['companyId']) || $post['companyId'] == '') $this->resp();
        if (!isset($post['employeeId']) || $post['employeeId'] == '') $this->resp();
        $post['public'] = 0;
        // For expired session
        if ($post['public'] == 0 && empty($this->session->userdata('logged_in'))) {
            $this->res['Redirect'] = true;
            $this->res['Response'] = 'Your login session has expired.';
            $this->res['Code'] = 'SESSIONEXPIRED';
            $this->resp();
        }
        //
        $this->res['Redirect'] = FALSE;
        //
        switch (strtolower($post['action'])) {
            // Fetch company
            case 'add_course':
                //
                $data_to_insert = array();
                $data_to_insert['company_sid'] = $post['companyId'];
                $data_to_insert['creator_sid'] = $post['employeeId'];
                $data_to_insert['title'] = $post['title'];
                $data_to_insert['type'] = $post['course_type'];
                $data_to_insert['description'] = $post['description'];
                $data_to_insert['start_date'] = $post['start_date'];
                $data_to_insert['end_date'] = $post['end_date'];
                //
                $insert_id = $this->cm->addData('lms_courses', $data_to_insert);
                //
                $this->res['Id'] = $insert_id;
                $this->res['Type'] = $post['course_type'];
                $this->res['Response'] = 'You have successfully added a course with the title <b>"' . (stripcslashes($post['title'])) . '"</b>.';
                $this->res['Code'] = "SUCCESS";
                $this->res['Status'] = true;
                $this->resp();
                break;

            case 'upload_zip':
                //
                $random = generateRandomString(5);
                $company_id = $post['companyId'];
                $companyName = getCompanyNameBySid($company_id);
                $target_file_name = basename($_FILES["upload_zip"]["name"]);
                $file_name = strtolower($random . '_' . $target_file_name);
                //
                $target_dir = 'assets/temp_files/scorm/' . strtolower(preg_replace('/\s+/', '_', $companyName)) . '/' . $_POST['courseId'] .'/';
                $target_file = $target_dir . $file_name;
                $file_info = pathinfo($_FILES["upload_zip"]["name"]);
                // 
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                //
                if (move_uploaded_file($_FILES["upload_zip"]["tmp_name"], $target_file)) {
                    $zip = new ZipArchive;
                    //
                    $x = $zip->open($target_file);
                    //
                    if ($x === true) {
                        $newName = $random . '_' .$file_info['filename'];
                        $zip->renameName($_FILES["upload_zip"]["name"],$newName);
                        $zip->extractTo($target_dir);
                        $zip->close();
                    }
                    //
                    $unzipFile = $target_dir .$file_info['filename'];
                    $this->load->library('Scorm/Scorm_lib', '', 'slib');
                    //
                    $courseContent = $this->slib->LoadFile($unzipFile."/imsmanifest.xml")->GetIndex();
                    if (
                        !empty($courseContent) && 
                        isset($courseContent["items"]) &&
                        !empty($courseContent["items"])
                    ) {
                        if (
                            in_array($courseContent['version'], $this->scorm_versions)
                        ) {
                            $data_to_insert = array();
                            $data_to_insert['company_sid'] = $post['companyId'];
                            $data_to_insert['creator_sid'] = $post['employeeId'];
                            $data_to_insert['upload_scorm_file'] = $unzipFile;
                            $data_to_insert['version'] = $courseContent['version'];
                            //
                            $insert_id = $this->cm->addData('lms_scorm_courses', $data_to_insert);
                            //
                            $this->res['Response'] = '<strong>The file ' . basename($_FILES["upload_zip"]["name"]) . ' has been uploaded.';
                            $this->res['Code'] = "SUCCESS";
                            $this->res['Status'] = true;
                            $this->resp();
                        } else {
                            $this->res['Response'] = '<strong>Sorry</strong>, system not support this '. $courseContent['version'] .' version.';
                            $this->resp();
                        }
                    } else {
                        $this->res['Response'] = '<strong>Sorry</strong>, scorm file is invalide';
                        $this->resp();
                    }
                    
                } else {
                    $this->res['Response'] = '<strong>Sorry</strong>, there was an error uploading your file.';
                    $this->resp();
                }
                //
                
                break;  

            case "get_employees_list":
                $this->res['employees']   = $this->cm->getAllActiveEmployees($company_detail['sid']);
                $this->res['departments'] = $this->cm->getAllDepartments($company_detail['sid']);
                $this->res['jobTitles']   = $this->cm->getAllJobTitles($company_detail['sid']);
                $this->res['Status'] = true;
                $this->res['Response'] = 'Proceed.';
                $this->resp();
                break;    
        } 
        //
    }           


    /**
     * AJAX Responder
     */
    private function resp()
    {
        header('Content-type: application/json');
        echo json_encode($this->res);
        exit(0);
    }


    /**
     *
     */
    public function companysurveys($id)
    {
        //
        $data = [];
        $data['load_view'] = 1;
        $data['session'] = $this->session->userdata('logged_in');
        $data['employee'] = $data['session']['employer_detail'];
        //
        $this->load
            ->view($this->pages['header'], $data)
            ->view("{$this->mp}es/companysurvey")
            ->view($this->pages['footer']);
    }

    /**
     *
     */
    public function surveyfeedback($id, $id2, $id3)
    {
        //
        $data = [];
        $data['load_view'] = 1;
        $data['session'] = $this->session->userdata('logged_in');
        $data['employee'] = $data['session']['employer_detail'];
        //
        $this->load
            ->view($this->pages['header'], $data)
            ->view("{$this->mp}es/surveyfeedback")
            ->view($this->pages['footer']);
    }


    /**
     *
     */
    public function settings()
    {
        //
        $data = [];
        $data['load_view'] = 1;
        $data['session'] = $this->session->userdata('logged_in');
        $data['employee'] = $data['session']['employer_detail'];
        //
        $this->load
            ->view($this->pages['header'], $data)
            ->view("{$this->mp}es/settings")
            ->view($this->pages['footer']);
    }




    /**
     *
     */
    public function reports()
    {
        //
        $data = [];
        $data['load_view'] = 1;
        $data['session'] = $this->session->userdata('logged_in');
        $data['employee'] = $data['session']['employer_detail'];
        //
        $this->load
            ->view($this->pages['header'], $data)
            ->view("{$this->mp}es/reports")
            ->view($this->pages['footer']);
    }


    /**
     *
     */
    public function faqs()
    {
        //
        $data = [];
        $data['load_view'] = 1;
        $data['session'] = $this->session->userdata('logged_in');
        $data['employee'] = $data['session']['employer_detail'];
        //
        $this->load
            ->view($this->pages['header'], $data)
            ->view("{$this->mp}es/faqs")
            ->view($this->pages['footer']);
    }


    /**
     *
     */
    public function surveyTemplateDetail($id)
    {
        //
        $data = [];
        $data['load_view'] = 1;
        $data['session'] = $this->session->userdata('logged_in');
        $data['employee'] = $data['session']['employer_detail'];
        $data['templateId'] = $id;
        //
        $this->load
            ->view($this->pages['header'], $data)
            ->view("{$this->mp}es/surveytemplatedetail")
            ->view($this->pages['footer']);
    }


    /**
     *
     */
    public function surveyTemplateSelect($id)
    {
        //
        $data = [];
        $data['load_view'] = 1;
        $data['session'] = $this->session->userdata('logged_in');
        $data['employee'] = $data['session']['employer_detail'];
        $data['templateId'] = $id;
        $data['company_id'] = $data["session"]["company_detail"]["sid"];
        $data['employer_id'] = $data["session"]["employer_detail"]["sid"];


        //
        $this->load
            ->view($this->pages['header'], $data)
            ->view("{$this->mp}es/surveytemplateselect")
            ->view($this->pages['footer']);
    }



}
