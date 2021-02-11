<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Form_affiliate_end_user_license_agreement extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('manage_admin/documents_model');
    }

    public function index($verification_key = null, $pre_fill_flag = null ){
        $data = array();
        if($verification_key != null){
            $document_record = $this->documents_model->get_document_record('form_affiliate_end_user_license_agreement', $verification_key);
            
            $agent_id = $document_record['marketing_agency_sid'];
            $agent_record = $this->documents_model->get_agent_record($agent_id, 'Form_affiliate_end_user_license_agreement');

            if(!empty($document_record)){
                $status =  $document_record['status'];
                $marketing_agencies_sid = $document_record['marketing_agencies_sid'];
                $marketing_agency_name = $document_record['full_name'];
                $marketing_agency_email = $document_record['email'];

                $ip_track = $this->documents_model->get_document_ip_tracking_record($marketing_agencies_sid, 'form_affiliate_end_user_license_agreement');
                $data['ip_track'] = $ip_track;
                $data['pre_fill_flag'] = $pre_fill_flag;

                $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
                $this->form_validation->set_message('required', '%s Required');

                if($pre_fill_flag != null && $pre_fill_flag == 'pre_fill') {
                    $this->form_validation->set_rules('marketing_agencies_sid', 'marketing_agencies_sid', 'xss_clean|trim');
                    $this->form_validation->set_rules('the_entity', 'Entity', 'xss_clean|trim');
                    $this->form_validation->set_rules('the_client', 'Client', 'xss_clean|trim');

                    $this->form_validation->set_rules('company_by', 'By', 'xss_clean|trim');
                    $this->form_validation->set_rules('company_name', 'Name', 'xss_clean|trim');
                    $this->form_validation->set_rules('company_title', 'Title', 'xss_clean|trim');
                    $this->form_validation->set_rules('company_date', 'Date', 'xss_clean|trim');
                    $this->form_validation->set_rules('client_by', 'By', 'xss_clean|trim');
                    $this->form_validation->set_rules('client_name', 'Name', 'xss_clean|trim');
                    $this->form_validation->set_rules('client_title', 'Title', 'xss_clean|trim');
                    $this->form_validation->set_rules('client_date', 'Date', 'xss_clean|trim');
                    $this->form_validation->set_rules('acknowledgement', 'Acknowledgement', 'xss_clean|trim');
                } else {
                    $this->form_validation->set_rules('marketing_agencies_sid', 'marketing_agencies_sid', 'xss_clean|trim');
                    $this->form_validation->set_rules('the_entity', 'Entity', 'xss_clean|trim');
                    $this->form_validation->set_rules('the_client', 'Client', 'xss_clean|trim');

                    $this->form_validation->set_rules('company_by', 'By', 'xss_clean|trim');
                    $this->form_validation->set_rules('company_name', 'Name', 'xss_clean|trim');
                    $this->form_validation->set_rules('company_title', 'Title', 'xss_clean|trim');
                    $this->form_validation->set_rules('company_date', 'Date', 'xss_clean|trim');
                    $this->form_validation->set_rules('client_by', 'By', 'xss_clean|trim');
                    $this->form_validation->set_rules('client_name', 'Name', 'required|xss_clean|trim');
                    $this->form_validation->set_rules('client_title', 'Title', 'xss_clean|trim');
                    $this->form_validation->set_rules('client_date', 'Date', 'xss_clean|trim');
                    // $this->form_validation->set_rules('client_signature', 'E-Signature', 'required|xss_clean|trim');
                    $this->form_validation->set_rules('acknowledgement', 'Acknowledgement', 'required|xss_clean|trim');
                }

                if($this->form_validation->run() == false){
                    $document_record['payment_method'] = $this->input->post('payment_method');
                } else {
                    $dataToSave = array();
                    $dataToSave['marketing_agency_sid'] = $this->input->post('company_sid');
                    $dataToSave['the_entity'] = $this->input->post('the_entity');
                    $dataToSave['the_client'] = $this->input->post('the_client');

                    $dataToSave['company_by'] = $this->input->post('company_by');
                    $dataToSave['company_name'] = $this->input->post('company_name');
                    $dataToSave['company_title'] = $this->input->post('company_title');
                    $dataToSave['company_date'] =  date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $this->input->post('company_date')))) ;
                    $dataToSave['company_signature'] = $this->input->post('company_signature');
                    $dataToSave['client_by'] = $this->input->post('client_by');
                    $dataToSave['client_name'] = $this->input->post('client_name');
                    $dataToSave['client_title'] = $this->input->post('client_title');
                    $dataToSave['client_date'] = date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $this->input->post('client_date')))) ;
                    $dataToSave['client_signature'] = $this->input->post('client_signature');
                    $dataToSave['acknowledgement'] = $this->input->post('acknowledgement');
                    $is_pre_fill = $this->input->post('is_pre_fill');
                    $status = '';

                    if($is_pre_fill == 1){
                        $status = 'pre-filled';
                    } else {
                        $status = 'signed';
                        $dataToSave['client_ip'] = getUserIP();
                        $dataToSave['client_signature_timestamp'] = date('Y-m-d H:i:s');
                    }
                    $this->documents_model->update_document_record('form_affiliate_end_user_license_agreement', $verification_key, $dataToSave, $status);

                    if($pre_fill_flag != null && $pre_fill_flag == 'pre_fill'){
                        $this->session->set_flashdata('message', 'Form Successfully Pre-Filled.');

                        $this->documents_model->insert_document_ip_tracking_record($marketing_agencies_sid, 0, getUserIP(), 'form_affiliate_end_user_license_agreement', 'pre_filled', $_SERVER['HTTP_USER_AGENT']);

                        if(isset($_POST['save']) && $_POST['save'] == 'Save And Send'){

                            $link_eula = '<a style="' . DEF_EMAIL_BTN_STYLE_DANGER . '" target="_blank" href="' . base_url('form_affiliate_end_user_license_agreement/' . $verification_key) . '">Affiliate End User License Agreement</a>';

                            $links = '<br />';
                            $links .= $link_eula;
                            $links .= '<br />';
                            $replacement_array = array();
                            $replacement_array['affiliate_name'] = $marketing_agency_name;
                            $replacement_array['links'] = $links;


//                            echo '<pre>';
//                            print_r($replacement_array);
//                            die();
                            log_and_send_templated_email(AFFILIATE_END_USER_LICENSE_NOTIFICATION, $marketing_agency_email, $replacement_array);

//                            $this->documents_model->insert_document_email_history_record($company_sid, $eula_sid, 'Manual Email', $marketing_agency_email);
                        }

                        redirect('manage_admin/marketing_agencies/', 'refresh');

                    } else {
                        $this->session->set_flashdata('message', '"We Appreciate Your Business"');

                        if ($this->session->userdata('logged_in')) {
                            $data['session'] = $this->session->userdata('logged_in');
                            $employer_sid = $data["session"]["employer_detail"]["sid"];
                        } else {
                            $employer_sid = -1;
                        }

                        $this->documents_model->insert_document_ip_tracking_record($marketing_agencies_sid, $employer_sid, getUserIP(), 'form_affiliate_end_user_license_agreement', 'signed', $_SERVER['HTTP_USER_AGENT']);

                        redirect('thank_you', 'refresh');
                    }

                }

                //Check if is prefill by admin
                if($pre_fill_flag != null && $pre_fill_flag == 'pre_fill'){
                    $data['is_pre_fill'] = 1;
                } else {
                    $data['is_pre_fill'] = 0;
                }

                if($status == 'signed'){
                    $data['readonly'] = 1;
                } else {
                    $data['readonly'] = 0;
                }
                
                $data['page_title'] = 'Affiliate End User License Agreement';
                $data['company_document'] = $document_record;
                $data['verification_key'] = $verification_key;
                $data['agent_record'] = $agent_record;
                $data['destination'] = 'affiliate_end_user';
                $data['user_type'] = 'marketing_agencies';
// print_r($data); die();
                $this->load->view('form_affiliate_end_user_license_agreement/index', $data);
            } else {
                redirect('login', 'refresh');
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    function ajax_commission_detail () {
        $form_post = $this->input->post();
        $record_sid = $form_post['record_sid'];
        $marketing_agency_sid = $form_post['record_ma_sid'];
        $type = $form_post['submit_commission_type'];
        $value = '';

        if ($type == 'closed_qualified_customers') {

            $value = $form_post['full_commission_details'];
        
        } else if ($type == 'commission_schedule_percentage') {
           
            $value = $form_post['full_commission_percentage']; 

        } else if ($type == 'commission_schedule_flat') {
            
            $value = $form_post['full_commission_rate']; 

        }

        $this->documents_model->update_commission_info($record_sid, $marketing_agency_sid, $value, $type);
        echo $value;
        
    }
}