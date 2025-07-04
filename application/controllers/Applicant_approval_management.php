<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Applicant_approval_management extends Public_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('dashboard_model');
        $this->load->model('job_approval_rights_model');
        $this->load->model('application_tracking_system_model');
    }

    public function index(){
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'approval_rights_management'); // First Param: security array, 2nd param: redirect url, 3rd param: function name
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $employer_name = $data['session']['employer_detail']['first_name'] . ' ' . $data['session']['employer_detail']['last_name'];
//            $company_has_job_approval_rights = $this->job_approval_rights_model->GetModuleStatus($company_sid);
            $users_with_applicant_approval_rights = $this->job_approval_rights_model->GetUsersWithApprovalRights($company_sid, 'applicants');
            $company_has_applicant_approval_rights = $this->job_approval_rights_model->GetModuleStatus($company_sid, 'applicants');
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|xss_clean|trim');

            if($this->form_validation->run() == false){
                $user_ids = array();
                $pending_applicants = array();
                $approved_applicants = array();
                $rejected_applicants = array();

                foreach($users_with_applicant_approval_rights as $user){
                    $user_ids[] = $user['sid'];
                }

                if ($company_has_applicant_approval_rights == 1) {
                    if (in_array($employer_sid, $user_ids)) {
                        $pending_applicants = $this->application_tracking_system_model->get_all_applicants_by_approval_status($company_sid, 'pending');
                        $approved_applicants = $this->application_tracking_system_model->get_all_applicants_by_approval_status($company_sid, 'approved');
                        $rejected_applicants = $this->application_tracking_system_model->get_all_applicants_by_approval_status($company_sid, 'rejected');
                    }
                }

                $data['company_has_applicant_approval_rights'] = $company_has_applicant_approval_rights;
                $data['pending_applicants'] = $pending_applicants;
                $data['approved_applicants'] = $approved_applicants;
                $data['rejected_applicants'] = $rejected_applicants;
                $data['company_sid'] = $company_sid;
                $data['employer_sid'] = $employer_sid;
                $data['title'] = 'Applicants Hiring Approvals Management';
                $this->load->view('main/header', $data);
                $this->load->view('applicant_approval_management/index');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');
                switch(strtolower($perform_action)){
                    case 'set_status_rejected':
                        $company_sid = $this->input->post('company_sid');
                        $employer_sid = $this->input->post('employer_sid');
                        $applicant_sid = $this->input->post('applicant_sid');
                        $job_sid = $this->input->post('job_sid');
                        $approval_status_type = $this->input->post('approval_status_type');
                        $status = 'rejected';
                        $reason = '';
                        
                        if(isset($_POST['reason'])) {
                            $reason = $this->input->post('reason');
                        }

                        $applicant_detail = $this->application_tracking_system_model->get_single_applicant($applicant_sid, $job_sid);

                        if(!empty($applicant_detail)) {
                            $applicant_name = ucwords($applicant_detail['first_name'] . ' ' . $applicant_detail['last_name']);
                            $requested_by = $applicant_detail['approval_by'];
                            $requesting_employer_detail = $this->application_tracking_system_model->get_employer_details($requested_by);
                            //$requesting_employer_name = ucwords($requesting_employer_detail['first_name'] . ' ' . $requesting_employer_detail['last_name']);
                            $job_title = $applicant_detail['job_title'];
                            $replacement_array = array();
                            $replacement_array['applicant_name'] = $applicant_name;
                            $replacement_array['job_title'] = $job_title;
                            $replacement_array['approval_status'] = $status;
                            $replacement_array['conditions'] = $reason;

                            if($approval_status_type == 'rejected_conditionally'){
                                foreach($users_with_applicant_approval_rights as $user){
                                    $replacement_array['employer_name'] = ucwords($user['first_name'] . ' ' . $user['last_name']);
                                    $user_email = $user['email'];
                                    $replacement_array['approving_authority'] = $employer_name;
                                    log_and_send_templated_email(APPLICANT_HIRING_CONDITIONAL_REJECTION_NOTIFICATION, $user_email, $replacement_array);
                                }
                            } else {
                                foreach($users_with_applicant_approval_rights as $user){
                                    $replacement_array['employer_name'] = ucwords($user['first_name'] . ' ' . $user['last_name']);
                                    $user_email = $user['email'];
                                    $replacement_array['approving_authority'] = $employer_name;
                                    log_and_send_templated_email(APPLICANT_HIRING_APPROVAL_STATUS_CHANGE, $user_email, $replacement_array);
                                }
                            }
                        }

                        $this->application_tracking_system_model->set_applicant_approval_status($company_sid, $applicant_sid, $status, $employer_sid, $reason, $approval_status_type, '', $job_sid);
                        //echo $this->db->last_query();
                        $this->application_tracking_system_model->insert_applicant_approval_history_record($company_sid, $employer_sid, $applicant_sid, $status, $approval_status_type, date('Y-m-d h:i:s'), $reason);
                        break;
                }
            }
        } else {
            redirect('login', "refresh");
        }
    }

    public function ajax_responder() {
        $data['session'] = $this->session->userdata('logged_in');
        $company_sid = $data['session']['company_detail']['sid'];
        $employer_sid = $data['session']['employer_detail']['sid'];
        $employer_name = $data['session']['employer_detail']['first_name'] . ' ' . $data['session']['employer_detail']['last_name'];
        $users_with_applicant_approval_rights = $this->job_approval_rights_model->GetUsersWithApprovalRights($company_sid, 'applicants');

        if (array_key_exists('perform_action', $_POST)) {
            $perform_action = strtoupper($_POST['perform_action']);
            switch ($perform_action) {
                case 'UPDATE_APPLICANT_APPROVAL_STATUS':
                    if($_POST){
                        $applicant_sid = $_POST['applicant_id'];
                        $status = $_POST['status'];
                        $reason = $_POST['reason'];
                        $job_sid = $_POST['job_sid'];
                        $applicant_detail = $this->application_tracking_system_model->get_single_applicant($applicant_sid, $job_sid);
                        
                        if(!empty($applicant_detail)) {
                            $applicant_name = ucwords($applicant_detail['first_name'] . ' ' . $applicant_detail['last_name']);
                            $requested_by = $applicant_detail['approval_by'];
                            $this->application_tracking_system_model->set_applicant_approval_status($company_sid, $applicant_sid, $status, $employer_sid, $reason, 'approved', '', $job_sid);
                            $this->application_tracking_system_model->insert_applicant_approval_history_record($company_sid, $employer_sid, $applicant_sid, $status, '', date('Y-m-d h:i:s'), $reason);
                            //Send Emails to Employers - Start
                            $requesting_employer_detail = $this->application_tracking_system_model->get_employer_details($requested_by);
                            //$requesting_employer_name = ucwords($requesting_employer_detail['first_name'] . ' ' . $requesting_employer_detail['last_name']);
                            $job_title = $applicant_detail['job_title'];
                            $replacement_array = array();
                            $replacement_array['applicant_name'] = $applicant_name;
                            $replacement_array['job_title'] = $job_title;
                            $replacement_array['approval_status'] = $status;

                            if(trim($reason) == ''){
                                foreach($users_with_applicant_approval_rights as $user){
                                    $replacement_array['employer_name'] = ucwords($user['first_name'] . ' ' . $user['last_name']);
                                    $user_email = $user['email'];
                                    $replacement_array['approving_authority'] = $employer_name;
                                    log_and_send_templated_email(APPLICANT_HIRING_APPROVAL_STATUS_CHANGE, $user_email, $replacement_array);
                                }
                            } else {
                                $replacement_array['conditions'] = $reason;
                                foreach($users_with_applicant_approval_rights as $user){
                                    $replacement_array['employer_name'] = ucwords($user['first_name'] . ' ' . $user['last_name']);
                                    $user_email = $user['email'];
                                    $replacement_array['approving_authority'] = $employer_name;
                                    log_and_send_templated_email(APPLICANT_HIRING_CONDITIONAL_REJECTION_NOTIFICATION, $user_email, $replacement_array);
                                }
                            }
                            //Send Emails to Employers - End
                        }
                        echo 'success';
                    }

                    break;
                case 'RESET_APPLICANT_FOR_APPROVAL':
                    $applicant_sid = $this->input->post('applicant_sid');
                    $company_sid = $this->input->post('company_sid');
                    $job_sid = $this->input->post('job_sid');
                    $employer_response = $this->input->post('employer_response');
                    $this->application_tracking_system_model->reset_applicant_for_approval($company_sid, $employer_sid, $applicant_sid, $employer_response, $job_sid);
                    $this->application_tracking_system_model->insert_applicant_approval_history_record($company_sid, $employer_sid, $applicant_sid, 'pending', 're_request', date('Y-m-d h:i:s'), $employer_response);
                    $this->session->set_flashdata('message', 'Applicant approval request successfully sent');
                    redirect('applicant_profile/' . $applicant_sid);
                    break;
                case 'REJECT_APPLICANT':
                    $applicant_sid = $this->input->post('applicant_id');
                    $company_sid = $this->input->post('company_sid');
                    $job_sid = $this->input->post('job_sid');
                    $status_reason = $this->input->post('approval_status_reason');
                    $status_type = $this->input->post('approval_status_type');
                    $status = $this->input->post('approval_status');
                    $this->application_tracking_system_model->set_applicant_approval_status($company_sid, $applicant_sid, $status, $employer_sid, $status_reason, $status_type, '', $job_sid);
                    $this->application_tracking_system_model->insert_applicant_approval_history_record($company_sid, $employer_sid, $applicant_sid, $status, $status_type, date('Y-m-d h:i:s'), $status_reason);
                    echo 'success';
                    break;
                case 'EMPLOYER_RESPONSE_ON_CANDIDATE_REJECTION':
                    $applicant_sid = $this->input->post('applicant_id');
                    $company_sid = $this->input->post('company_sid');
                    $job_sid = $this->input->post('job_sid');
                    $status_reason_response = $this->input->post('approval_status_reason_response');
                    $status_type = $this->input->post('approval_status_type');
                    $status = $this->input->post('approval_status');
                    $this->application_tracking_system_model->set_applicant_approval_status($company_sid, $applicant_sid, $status, $employer_sid, null, $status_type, $status_reason_response, $job_sid);
                    $this->application_tracking_system_model->insert_applicant_approval_history_record($company_sid, $employer_sid, $applicant_sid, $status, $status_type, date('Y-m-d h:i:s'), $status_reason_response);
                    echo 'success';
                    break;
                default:
                    break;
            }
        }
    }
}