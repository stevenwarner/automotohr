<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Job_approval_rights extends Public_Controller {
    public function __construct() {
        parent::__construct();

        $this->load->model('dashboard_model');
        $this->load->model('job_approval_rights_model');

    }

    public function index(){
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');

            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            //check_access_permissions($security_details, 'dashboard', 'application_tracking'); // First Param: security array, 2nd param: redirect url, 3rd param: function name

            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['company_detail']['sid'];

            $current_employees = $this->dashboard_model->GetAllUsers($company_sid);
            $data['current_employees'] = $current_employees;


            if($_POST){
                if(isset($_POST['employees'])){
                    $employees = $_POST['employees'];
                    if(!empty($employees)){
                        $this->job_approval_rights_model->ResetRights($company_sid);
                        foreach ($employees as $employee) {
                            $this->job_approval_rights_model->UpdateRights($company_sid, $employee, 1);
                        }
                    }
                }

                if(isset($_POST['company_job_approval_status'])){
                    $moduleStatus = $_POST['company_job_approval_status'];
                    if($moduleStatus == 1){
                        $this->job_approval_rights_model->UpdateModuleStatus($company_sid, $moduleStatus);
                    }
                }else{
                    $moduleStatus = 0;
                    $this->job_approval_rights_model->UpdateModuleStatus($company_sid, $moduleStatus);
                }

                if(isset($_POST['company_applicant_approval_status'])){
                    $moduleStatus = $_POST['company_applicant_approval_status'];
                    if($moduleStatus == 1){
                        $this->job_approval_rights_model->UpdateApplicantApprovalModuleStatus($company_sid, $moduleStatus);
                    }
                }else{
                    $moduleStatus = 0;
                    $this->job_approval_rights_model->UpdateApplicantApprovalModuleStatus($company_sid, $moduleStatus);
                }
            }

            $employeesWithRights = $this->job_approval_rights_model->GetUsersWithApprovalRights($company_sid);

//            echo '<pre>';
//            print_r($employeesWithRights);
//            echo '<pre>';


            $employeesArray = array();
            foreach($employeesWithRights as $employeeWithReights){
                $employeesArray[] = $employeeWithReights['sid'];
            }

            $data['employeesArray'] = $employeesArray;

            $jobApprovalModuleStatus = $this->job_approval_rights_model->GetModuleStatus($company_sid);
            $applicantApprovalModuleStatus = $this->job_approval_rights_model->GetApplicantApprovalModuleStatus($company_sid);

            if($jobApprovalModuleStatus == 1){
                $data['jobApprovalModuleStatus'] = 'checked="checked"';
            }else{
                $data['jobApprovalModuleStatus'] = '';
            }


            if($applicantApprovalModuleStatus == 1){
                $data['applicantApprovalModuleStatus'] = 'checked="checked"';
            }else{
                $data['applicantApprovalModuleStatus'] = '';
            }

            $data['title'] = 'Approval Rights Management';
            $this->load->view('main/header', $data);
            $this->load->view('job_approval_rights/index');
            $this->load->view('main/footer');
        } else {
            redirect('login', "refresh");
        }

    }

    public function ajax_responder() {

        if (array_key_exists('perform_action', $_POST)) {
            $perform_action = strtoupper($_POST['perform_action']);
            switch ($perform_action) {
                case '':
                    break;
                default:
                    break;
            }
        }

    }


}
