<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Resend_screening_questionnaire extends Public_Controller {
    public function __construct() {
        parent::__construct();
        if($this->session->userdata('logged_in')) {
            require_once(APPPATH . 'libraries/aws/aws.php');
            $this->load->library("pagination");
            $this->load->model('resend_screening_questionnaires_model');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function index($job_applicant, $jobs_listing, $job_id, $redirect_flag = 1) {
        if ($this->session->userdata('logged_in')) {
            $data['session']                                                    = $this->session->userdata('logged_in');
            $ats_params = $this->session->userdata('ats_params');
            $data = applicant_right_nav($job_applicant, $job_id, $ats_params);
            $security_sid                                                       = $data['session']['employer_detail']['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;
            $company_sid                                                        = $data["session"]["company_detail"]["sid"];
            $employer_sid                                                       = $data["session"]["employer_detail"]["sid"];
            $data['title']                                                      = "Screening Questionnaires";
            $data['job_list_sid']                                               = $jobs_listing;

            $applicant_data = $this->resend_screening_questionnaires_model->get_applicant_data($job_applicant);
            $company_name = $data["session"]["company_detail"]["CompanyName"];
            if($job_id){
                $job_ques_id = $this->resend_screening_questionnaires_model->previous_questionnaire_id($job_id);
                $data['job_title'] = $this->resend_screening_questionnaires_model->job_title($job_id);
                $data['pre_que_id'] =  $job_ques_id['questionnaire_sid'];
            }
            elseif($job_id == 0 && $applicant_data['desired_job_title'] == NULL){
                $data['job_title'] = 'You have job opportunity at '. ucwords($company_name);
                $data['pre_que_id'] = 0;
            }
            else{
                $data['job_title'] = ucwords($applicant_data['desired_job_title']);
                $data['pre_que_id'] = 0;
            }

            $questionnaires = $this->resend_screening_questionnaires_model->get_all_questionnaires_by_employer($company_sid);
            $data['subtitle'] = "Resend Questionnaire";
            $data['questionnaires'] = $questionnaires;
            $data['current_url'] = base_url('applicant_profile/'.$job_applicant . '/' . $jobs_listing);
            if($this->input->post()){
                $new_que_id = $this->input->post('questionnaire');

                $questionnaire_key = $this->resend_screening_questionnaires_model->copy_applicant_job_list($jobs_listing,$company_sid,$employer_sid);
                $new_ques_key = $this->resend_screening_questionnaires_model->update_applicant_job_list($jobs_listing,$new_que_id,$questionnaire_key);



                $replacement_array = array();
                $replacement_array['company_name'] = ucwords($company_name);
                $replacement_array['company-name'] = ucwords($company_name);
                $replacement_array['applicant_name'] = $applicant_data['first_name'] . ' ' . $applicant_data['last_name'];
                $replacement_array['applicant-name'] = $applicant_data['first_name'] . ' ' . $applicant_data['last_name'];
                $replacement_array['job_title'] = ucwords($data['job_title']);
                $replacement_array['job-title'] = ucwords($data['job_title']);

                $replacement_array['url'] = '<a href="' . base_url() . 'Job_screening_questionnaire/'  . $new_ques_key . '" style="'.DEF_EMAIL_BTN_STYLE_PRIMARY.'" target="_blank">Screening Questionnaires</a>';

                $message_hf = message_header_footer_domain($applicant_data['employer_sid'], $company_name);

                $message_hf['footer'] = $message_hf['footer'] . FROM_INFO_EMAIL_DISCLAIMER_MSG;
                $message_hf['header'] = FROM_INFO_EMAIL_DISCLAIMER_MSG . $message_hf['header'] ;

                log_and_send_templated_email(RESEND_SCREENING_QUESTIONNAIRE, $applicant_data['email'], $replacement_array, $message_hf);

                $this->session->set_flashdata('message', '<strong>Success: </strong> Screening Questionnaire Sent Successfully!');
                if($redirect_flag){
                    redirect('applicant_profile/'.$job_applicant . '/' . $jobs_listing, 'refresh');
                } else{
                    $redirect_url = $this->input->post('current-url');
                    redirect($redirect_url, 'refresh');
                }
            }

            $data['employee'] = $data["session"]["employer_detail"];
            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/resend_screening_questionnaire');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    } // end of index
}