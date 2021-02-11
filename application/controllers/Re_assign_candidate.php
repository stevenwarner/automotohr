<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Re_assign_candidate extends Public_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->helper('path');
        //$this->load->model('dashboard_model');
        $this->load->model('assign_candidate_other_job_model');
    }

    public function index($own_job='',$company_to='all',$new_job='all') {
        if ($this->session->userdata('logged_in')) {

            $data['session']                                                    = $this->session->userdata('logged_in');
            $security_sid                                                       = $data['session']['employer_detail']['sid'];

            if (!$this->session->userdata('security_details_'.$security_sid)) {
                $security_details                                               = db_get_access_level_details($security_sid);
                $this->session->set_userdata('security_details_'.$security_sid, $security_details);
            }

            $security_details                                                   = $this->session->userdata('security_details_'.$security_sid);
            $data['security_details']                                           = $security_details;
            check_access_permissions($security_details, 'my_settings', 're_assign_applicant');
            $company_sid                                                        = $data['session']['company_detail']['sid'];
            $company_name                                                        = $data['session']['company_detail']['CompanyName'];
            $data['company_sid']                                                = $company_sid;

            $data['title']                                                      = "Assign candidate to other jobs";

            $data['companies'] = $this->assign_candidate_other_job_model->get_all_companies($company_sid);
            $data['applicants'] = $this->assign_candidate_other_job_model->get_applicants($company_sid);
            $data['jobs'] = $this->assign_candidate_other_job_model->get_company_jobs($company_sid);
            if($own_job!=''){
                $data['applied_applicants'] = $this->assign_candidate_other_job_model->get_applied_applicant($company_sid,$own_job);
                $data['applicants_count'] = sizeof($data['applied_applicants']);
            }

            if(isset($_POST['action']) && $_POST['action']=='re_assign'){
                $candidates = $_POST['ej_active'];
                $statuses = array();
                $statuses['success'] = 0;
                $statuses['exist'] = 0;
                $statuses['applied'] = 0;
                foreach($candidates as $candidate){
                    $applicant_job = explode('-',$candidate);
                    $applicant_id = $applicant_job[0];
                    $old_job = $applicant_job[1];
                    $status = $this->assign_candidate_other_job_model->re_assign_candidate($company_to,$company_sid,$old_job,$new_job,$applicant_id,$company_name,$security_sid);
                    if($status == 1){
                        $statuses['success']++;
                    } else if($status == 2){
                        $statuses['exist']++;
                    } else if($status == 3){
                        $statuses['applied']++;
                    }
                }
                if(sizeof($candidates)==($statuses['success']+$statuses['exist'])){
                    $this->session->set_flashdata('message', '<b>Success:</b> '.sizeof($candidates).' Re-assigned.');
                } else if($statuses['applied']>0 && ($statuses['success']+$statuses['exist'])>0){
                    $this->session->set_flashdata('message', '<b>Success:</b> '.($statuses['success']+$statuses['exist']).' Re-assigned.' . $statuses['applied'] . ' Could not Re-assigned.');
                } else if(sizeof($candidates)==$statuses['applied']){
                    $this->session->set_flashdata('message', '<b>Success:</b> '.sizeof($candidates)." Couldn't be re-assigned.");
                }
                redirect(current_url(), 'refresh');
            }
            $this->load->view('main/header', $data);
            $this->load->view('assign_candidate_other_job/index');
            $this->load->view('main/footer');

        } else {
            redirect('login', 'refresh');
        }
    }

    public function ajax_responder() {
        if (array_key_exists('perform_action', $_POST)) {
            $perform_action = $_POST['perform_action'];
            switch ($perform_action) {
                case 'load_jobs':
                    $company_sid = $this->input->post('company_sid');
                    $company_jobs = $this->assign_candidate_other_job_model->get_company_jobs($company_sid);
                    echo json_encode($company_jobs);
                    break;
                default:
                    break;
            }
        }
    }
}