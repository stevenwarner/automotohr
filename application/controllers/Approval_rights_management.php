<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Approval_rights_management extends Public_Controller {
    public function __construct() {
        parent::__construct();
        //$this->load->model('dashboard_model');
        $this->load->model('job_approval_rights_model');
    }

    public function index(){
        if ($this->session->userdata('logged_in')) {
            $data['session']                                                    = $this->session->userdata('logged_in');
            $security_sid                                                       = $data['session']['employer_detail']['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;
            check_access_permissions($security_details, 'my_settings', 'approval_rights_management');
            $company_sid                                                        = $data['session']['company_detail']['sid'];
            $employer_sid                                                       = $data['session']['company_detail']['sid'];
            $current_employees                                                  = $this->job_approval_rights_model->GetAllUsers($company_sid);
            $data['current_employees']                                          = $current_employees;

            /*
            if($_POST){
                $perform_action = $this->input->post('perform_action');

                if($perform_action == 'save_job_approving_employees'){
                    $this->form_validation->set_rules('job_approval_employees[]', 'Employees', 'required|trim');
                }elseif($perform_action == 'save_applicant_approving_employees'){
                    $this->form_validation->set_rules('applicant_approval_employees[]', 'Employees', 'required|trim');
                }
            }
            */

            $this->form_validation->set_rules('perform_action', 'perform action', 'required|trim');

            if($this->form_validation->run() == false){
                $jobs_approval_module_status                                    = $this->job_approval_rights_model->GetModuleStatus($company_sid, 'jobs');
                $data['jobs_approval_module_status']                            = $jobs_approval_module_status;
                $applicants_approval_module_status                              = $this->job_approval_rights_model->GetModuleStatus($company_sid, 'applicants');
                $data['applicants_approval_module_status']                      = $applicants_approval_module_status;
                $task_management_module_status                                  = $this->job_approval_rights_model->GetModuleStatus($company_sid, 'tasks_management');
                $data['task_management_module_status']                          = $task_management_module_status;
                $jobs_approving_employees                                       = $this->job_approval_rights_model->GetUsersWithApprovalRights($company_sid, 'jobs');

                $data['jobs_approving_employees']                               = $jobs_approving_employees;
                $jobs_approving_employee_ids                                    = array();

                foreach($jobs_approving_employees as $employee){
                    $jobs_approving_employee_ids[]                              = $employee['sid'];
                }

                $data['jobs_approving_employee_ids']                            = $jobs_approving_employee_ids;
                $applicants_approving_employees                                 = $this->job_approval_rights_model->GetUsersWithApprovalRights($company_sid, 'applicants');
                $data['applicants_approving_employees']                         = $applicants_approving_employees;
                $applicants_approving_employee_ids                              = array();

                foreach($applicants_approving_employees as $employee){
                    $applicants_approving_employee_ids[]                        = $employee['sid'];
                }

                $data['applicants_approving_employee_ids']                      = $applicants_approving_employee_ids;
                $data['title']                                                  = 'Approval Rights Management';
                $this->load->view('main/header', $data);
                $this->load->view('approval_rights_management/index');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');

                switch($perform_action){
                    case 'save_job_approving_employees':
                        $this->job_approval_rights_model->ResetRights($company_sid, 'jobs');
                        $module_status                                          = $this->input->post('company_job_approval_status');
                        $this->job_approval_rights_model->UpdateModuleStatus($company_sid, $module_status, 'jobs');

                        if(isset($_POST['job_approval_employees'])) {
                            $employees                                          = $this->input->post('job_approval_employees');
                            
                            foreach ($employees as $employee) {
                                $this->job_approval_rights_model->UpdateRights($company_sid, $employee, 1, 'jobs');
                            }
                        }

                        $this->session->set_flashdata('message', 'Job Listing approval module status successfully updated.');
                        redirect('approval_rights_management', 'refresh');
                        break;
                    case 'save_applicant_approving_employees':
                        $this->job_approval_rights_model->ResetRights($company_sid, 'applicants');
                        $module_status                                          = $this->input->post('company_applicant_approval_status');
                        $this->job_approval_rights_model->UpdateModuleStatus($company_sid, $module_status, 'applicants');

                        if(isset($_POST['applicant_approval_employees'])) {
                            $employees                                          = $this->input->post('applicant_approval_employees');

                            foreach ($employees as $employee) {
                                $this->job_approval_rights_model->UpdateRights($company_sid, $employee, 1, 'applicants');
                            }
                        }

                        $this->session->set_flashdata('message', 'Applicant Hiring approval module status successfully updated.');
                        redirect('approval_rights_management', 'refresh');
                        break;
                    case 'save_task_management':
                        $module_status                                          = $this->input->post('task_management_module_status');
                        $this->job_approval_rights_model->UpdateModuleStatus($company_sid, $module_status, 'tasks_management');
                        $this->session->set_flashdata('message', 'Task management module status successfully updated.');
                        redirect('approval_rights_management', 'refresh');
                        break;
                }
            }           
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