<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 *
 */
class Courses extends Public_Controller
{
    public function __construct()
    {
        parent::__construct();
        //
        // load the library
        $this->load->library('Api_auth');
        //
        $this->load->model('v1/course_model');
        // call the company event
        $this->api_auth->checkAndLogin(
            $this->session->userdata('logged_in')['company_detail']['sid'],
            $this->session->userdata('logged_in')['employer_detail']['sid']
        );
        //
    }

    /**
     *
     */
    public function myCourses()
    {
        //
        $data = [];
        //
        $session = $this->session->userdata('logged_in');
        //
        $companyId = $session['company_detail']['sid'];
        $employeeId = $session['employer_detail']['sid'];
        //
        $data['security_details'] = db_get_access_level_details($employeeId);
        //
        $data['title'] = "My Trainings | " . STORE_NAME;
        $data['employer_sid'] = $employeeId;
        $data['employee'] = $session['employer_detail'];
        $data['load_view'] = 1;
        // load CSS
        $data['PageCSS'] = [
            '2022/css/main'
        ];
        // load JS
        $data['PageScripts'] = [
            'js/app_helper',
            'v1/common',
            'v1/lms/assign_employee_courses',
        ];
        //
        // get access token
        $data['apiAccessToken'] = getApiAccessToken(
            $companyId,
            $employeeId
        );
        //
        $data['apiURL'] = getCreds('AHR')->API_BROWSER_URL;
        //
        $this->load
            ->view('main/header_2022', $data)
            ->view('courses/my_dashboard')
            ->view('main/footer');
    }

    /**
     *
     */
    public function getCourse(int $sid)
    {
        //
        $data = [];
        //
        $session = $this->session->userdata('logged_in');
        //
        $companyId = $session['company_detail']['sid'];
        $employeeId = $session['employer_detail']['sid'];
        //
        $data['security_details'] = db_get_access_level_details($employeeId);
        //
        // Check if course is already taken then move it to history if needed
        // $this->course_model->moveCourseHistory($sid, $employeeId, $companyId);
        //
        $lessonStatus = '';
        //
        if (!$this->course_model->checkEmployeeCourse($companyId, $employeeId, $sid)) {
            _e("record not found",true);
            $lessonStatus = 'not_started';
        } else {
            if ($this->course_model->checkEmployeeCourseCompleted($companyId, $employeeId, $sid)) {
                $lessonStatus = 'not_started';
            } else {
                $lessonStatus = 'started';
            }
        }
        //
        $courseInfo = $this->course_model->getCourseInfo($sid);
        //
        $questions = $courseInfo['course_questions'];
        //
        $data['title'] = "My Courses :: " . STORE_NAME;
        $data['session'] = $session;
        $data['employer_sid'] = $employeeId;
        $data['employee'] = $session['employer_detail'];
        $data['load_view'] = 1;
        $data['course_sid'] = $sid;
        $data['courseInfo'] = $courseInfo;
        $data['lessonStatus'] = $lessonStatus;
        //
        // load CSS
        $data['PageCSS'] = [
            '2022/css/main'
        ];
        // load JS
        $data['PageScripts'] = [
            'js/app_helper',
            'v1/common',
            'v1/lms/preview_assign',
        ];
        //
        if ($courseInfo['course_type'] == "scorm") {
            $viewName = "scorm_course";
            $scormInfo = json_decode($courseInfo['Imsmanifist_json'], true);
            $data['PageScripts'][] = 'v1/plugins/ms_scorm/main';
            //
            if ($scormInfo["version"] == '1.2') {
                $data['PageScripts'][] = 'v1/plugins/ms_scorm/adapter_12';
            } elseif ($scormInfo["version"] == '2004_3') {
                $data['PageScripts'][] = ['v1/plugins/ms_scorm/adapter_2004_3'];
            } elseif ($scormInfo["version"] == '2004_4') {
                $data['PageScripts'][] = ['v1/plugins/ms_scorm/adapter_2004_4'];
            }
            //
            $data['version'] = $scormInfo["version"];
            //
            $data['CMIObject'] = $this->course_model->getCMIObject($sid, $employeeId, $companyId);
        } elseif ($courseInfo['course_type'] == "manual") {
            $viewName = "manual_course";
            //
            $data['questions'] = $questions;
            $data['viewMode'] = "attempt";
        }
        //
        // get access token
        $data['apiAccessToken'] = getApiAccessToken(
            $companyId,
            $employeeId
        );
        //
        $data['apiURL'] = getCreds('AHR')->API_BROWSER_URL;
        //
        $this->load
            ->view('main/header_2022', $data)
            ->view('courses/' . $viewName)
            ->view('main/footer');
    }

    /**
     *
     */
    public function viewCertificate(int $sid)
    {
        //
        $data = [];
        //
        $session = $this->session->userdata('logged_in');
        //
        $companyId = $session['company_detail']['sid'];
        $employeeId = $session['employer_detail']['sid'];
        //
        $data['security_details'] = db_get_access_level_details($employeeId);
        //
        $data['title'] = "Certificate :: " . STORE_NAME;
        $data['session'] = $session;
        $data['employer_sid'] = $employeeId;
        $data['employee'] = $session['employer_detail'];
        $data['load_view'] = 1;
        // load CSS
        $data['PageCSS'] = [
            '2022/css/main'
        ];
        // load JS
        $data['PageScripts'] = [
            'js/app_helper',
            'v1/common',
        ];
        // get access token
        $data['apiAccessToken'] = getApiAccessToken(
            $companyId,
            $employeeId
        );
        //
        $data['apiURL'] = getCreds('AHR')->API_BROWSER_URL;
        //
        $this->load
            ->view('courses/certificate', $data);
    }

    /**
     *
     */
    public function previewResult($sid)
    {
        //
        $data = [];
        //
        $session = $this->session->userdata('logged_in');
        //
        $companyId = $session['company_detail']['sid'];
        $employeeId = $session['employer_detail']['sid'];
        //
        $data['security_details'] = db_get_access_level_details($employeeId);
        //
        $courseInfo = $this->course_model->getCourseInfo($sid);
        //
        $data['title'] = "My Courses :: " . STORE_NAME;
        $data['employer_sid'] = $employeeId;
        $data['employee'] = $session['employer_detail'];
        $data['viewMode'] = "preview";
        $data['load_view'] = 1;
        $data['course_sid'] = $sid;
        $data['courseInfo'] = $courseInfo;
        // load CSS
        $data['PageCSS'] = [
            '2022/css/main'
        ];
        // load JS
        $data['PageScripts'] = [
            'js/app_helper',
            'v1/common',
            'v1/lms/preview_assign'
        ];
        //
        // get access token
        $data['apiAccessToken'] = getApiAccessToken(
            $companyId,
            $employeeId
        );
        //
        $data['apiURL'] = getCreds('AHR')->API_BROWSER_URL;
        //
        $this->load
            ->view('main/header_2022', $data)
            ->view('courses/manual_course_result_preview')
            ->view('main/footer');
    }
}
