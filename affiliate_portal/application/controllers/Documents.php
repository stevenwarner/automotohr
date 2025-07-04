<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Documents extends CI_Controller {
     public function __construct() {
        parent::__construct();
        $this->load->model('documents_model');
    }

	public function index() {
        if ($this->session->userdata('affiliate_loggedin')) {
            $data = array();
            $data['title'] = 'Documents';
            $affiliate_data = $this->session->userdata('affiliate_loggedin');
            $data['name'] = $affiliate_data['affiliate_users']['full_name'];
            $user_id = $affiliate_data['affiliate_users']['sid'];
            $data['session'] = $affiliate_data;
            $affiliate_detail                                                    = $data['session']['affiliate_users'];
            $security_sid                                                       = $affiliate_detail['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;

            $w9_form = $this->documents_model->get_w9_document($user_id);
            
            if (!empty($w9_form)) {
                $w9_form['document_name'] = 'W9 Fillable';
                $data['count'] = 1;
            } else {
                $data['count'] = 0;
            }
            
            $data['w9_form'] = $w9_form;
            $data['allDocuments'] = $this->documents_model->getAllDocuments($user_id);
            $data['count'] += count($data['allDocuments']);

            $this->load->view('main/header', $data);
            $this->load->view('documents/index');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function view_w9 () {
        if ($this->session->userdata('affiliate_loggedin')) {
            $data = array();
            $data['title'] = 'Referred Client Management';
            $affiliate_data = $this->session->userdata('affiliate_loggedin');
            $data['name'] = $affiliate_data['affiliate_users']['full_name'];
            $user_id = $affiliate_data['affiliate_users']['sid'];
            $data['session'] = $affiliate_data;
            $affiliate_detail                                                    = $data['session']['affiliate_users'];
            $security_sid                                                       = $affiliate_detail['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;

            $data['title'] = 'Form W-9';

            $field = array(
                'field' => 'w9_name',
                'label' => 'Name',
                'rules' => 'xss_clean|trim|required'
            );
            $order_field = array(
                'field' => 'w9_business_name',
                'label' => 'Business Name',
                'rules' => 'xss_clean|trim|required'
            );

            $config[] = $field;
            $config[] = $order_field;

            $this->form_validation->set_error_delimiters('<label class="error">', '</label>');
            $this->form_validation->set_rules($config);

            $w9_form = $this->documents_model->get_w9_document($affiliate_detail['sid']);
            
            if ($w9_form['user_consent'] != 1) {
                $w9_form['w9_name'] = $affiliate_detail['full_name'];
                $w9_form['w9_address'] = $affiliate_detail['address'];
            }
            
            $data['pre_form'] = $w9_form;
            $data['signed_flag'] = false;
            $data['affiliate_detail'] = $affiliate_detail;
            
            if ($this->form_validation->run() == FALSE) {
                $this->load->view('main/header', $data);
                $this->load->view('documents/form_w9');
                $this->load->view('main/footer');
            } else { 

                $w9_name = $this->input->post('w9_name');
                $w9_business_name = $this->input->post('w9_business_name');
                $w9_federaltax_classification = $this->input->post('w9_federaltax_classification');
                $w9_llc_federaltax_description = $this->input->post('w9_llc_federaltax_description');
                $w9_other_federaltax_description = $this->input->post('w9_other_federaltax_description');
                $w9_exemption_payee_code = $this->input->post('w9_exemption_payee_code');
                $w9_exemption_reporting_code = $this->input->post('w9_exemption_reporting_code');
                $w9_address = $this->input->post('w9_address');
                $w9_city_state_zip = $this->input->post('w9_city_state_zip');
                $w9_requester_name_address = $this->input->post('w9_requester_name_address');
                $w9_account_no = $this->input->post('w9_account_no');
                $w9_social_security_number = $this->input->post('w9_social_security_number');
                $w9_employer_identification_number = $this->input->post('w9_employer_identification_number');
                $ip_address = $this->input->post('ip_address');
                $user_agent = $this->input->post('user_agent');
                $signature_bas64_image = $this->input->post('signature_bas64_image');
                $initial_base64 = $this->input->post('init_signature_bas64_image');
                $signature_ip_address = $this->input->post('signature_ip_address');
                $signature_user_agent = $this->input->post('signature_user_agent');
                $user_consent = $this->input->post('user_consent');
                $affiliate_sid = $this->input->post('affiliate_sid');
                $user_consent = $this->input->post('w9_user_consent');



                $data_to_update = array();
                $data_to_update['w9_name'] = $w9_name;
                $data_to_update['w9_business_name'] = $w9_business_name;
                $data_to_update['w9_federaltax_classification'] = $w9_federaltax_classification;

                if ($w9_federaltax_classification == 'llc') {
                    $data_to_update['w9_federaltax_description'] = $w9_llc_federaltax_description;
                } elseif ($w9_federaltax_classification == 'other') {
                    $data_to_update['w9_federaltax_description'] = $w9_other_federaltax_description;
                }

                $data_to_update['w9_exemption_payee_code'] = $w9_exemption_payee_code;
                $data_to_update['w9_exemption_reporting_code'] = $w9_exemption_reporting_code;
                $data_to_update['w9_address'] = $w9_address;
                $data_to_update['w9_city_state_zip'] = $w9_city_state_zip;
                $data_to_update['w9_requester_name_address'] = $w9_requester_name_address;
                $data_to_update['w9_account_no'] = $w9_account_no;
                $data_to_update['w9_social_security_number'] = $w9_social_security_number;
                $data_to_update['w9_employer_identification_number'] = $w9_employer_identification_number;
                $data_to_update['ip_address'] = $ip_address;
                $data_to_update['user_agent'] = $user_agent;
                $data_to_update['user_consent'] = $user_consent;
                $data_to_update['signature_timestamp'] = date('Y-m-d H:i:s');
                $data_to_update['signature_email_address'] = $affiliate_detail['email'];
                $data_to_update['signature_bas64_image'] = $signature_bas64_image;
                $data_to_update['init_signature_bas64_image'] = $initial_base64;
                $data_to_update['signature_ip_address'] = $signature_ip_address;
                $data_to_update['signature_user_agent'] = $signature_user_agent;
                $data_to_update['affiliate_sid'] = $affiliate_sid;
                $data_to_update['parent_sid'] = $affiliate_detail['parent_sid'];
                $data_to_update['full_name'] = $affiliate_detail['full_name'];
                $data_to_update['email_address'] = $affiliate_detail['email'];
                $data_to_update['w9_form_status'] = 'filled';

                $this->documents_model->update_w9_form($affiliate_sid, $data_to_update);

                $this->session->set_flashdata('message', '<strong>Success: </strong> Request Submitted Successfully!');
                redirect(base_url('documents/view_w9/'), 'refresh');
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function form_w9 ($verification_key) {

        $affiliate_detail = $this->documents_model->get_affiliate_detail($verification_key);

        if (!empty($affiliate_detail)) {
            $data = array();
            $data['title'] = 'Referred Client Management';
            $data['name'] = $affiliate_detail['full_name'];

            $data['title'] = 'Form W-9';

            $field = array(
                'field' => 'w9_name',
                'label' => 'Name',
                'rules' => 'xss_clean|trim|required'
            );
            $order_field = array(
                'field' => 'w9_business_name',
                'label' => 'Business Name',
                'rules' => 'xss_clean|trim|required'
            );

            $config[] = $field;
            $config[] = $order_field;

            $this->form_validation->set_error_delimiters('<label class="error">', '</label>');
            $this->form_validation->set_rules($config);

            $w9_form = $this->documents_model->get_w9_document($affiliate_detail['sid']);

            if ($w9_form['user_consent'] != 1) {
                $w9_form['w9_name'] = $affiliate_detail['full_name'];
                $w9_form['w9_address'] = $affiliate_detail['address'];
            }

            $data['pre_form'] = $w9_form;
            $data['signed_flag'] = false;
            $data['affiliate_detail'] = $affiliate_detail;
            
            if ($this->form_validation->run() == FALSE) {
                $this->load->view('main/header', $data);
                $this->load->view('documents/form_w9');
                $this->load->view('main/footer');
            } else { 

                $w9_name = $this->input->post('w9_name');
                $w9_business_name = $this->input->post('w9_business_name');
                $w9_federaltax_classification = $this->input->post('w9_federaltax_classification');
                $w9_llc_federaltax_description = $this->input->post('w9_llc_federaltax_description');
                $w9_other_federaltax_description = $this->input->post('w9_other_federaltax_description');
                $w9_exemption_payee_code = $this->input->post('w9_exemption_payee_code');
                $w9_exemption_reporting_code = $this->input->post('w9_exemption_reporting_code');
                $w9_address = $this->input->post('w9_address');
                $w9_city_state_zip = $this->input->post('w9_city_state_zip');
                $w9_requester_name_address = $this->input->post('w9_requester_name_address');
                $w9_account_no = $this->input->post('w9_account_no');
                $w9_social_security_number = $this->input->post('w9_social_security_number');
                $w9_employer_identification_number = $this->input->post('w9_employer_identification_number');
                $ip_address = $this->input->post('ip_address');
                $user_agent = $this->input->post('user_agent');
                $signature_bas64_image = $this->input->post('signature_bas64_image');
                $initial_base64 = $this->input->post('init_signature_bas64_image');
                $signature_ip_address = $this->input->post('signature_ip_address');
                $signature_user_agent = $this->input->post('signature_user_agent');
                $user_consent = $this->input->post('user_consent');
                $affiliate_sid = $this->input->post('affiliate_sid');
                $user_consent = $this->input->post('w9_user_consent');



                $data_to_update = array();
                $data_to_update['w9_name'] = $w9_name;
                $data_to_update['w9_business_name'] = $w9_business_name;
                $data_to_update['w9_federaltax_classification'] = $w9_federaltax_classification;

                if ($w9_federaltax_classification == 'llc') {
                    $data_to_update['w9_federaltax_description'] = $w9_llc_federaltax_description;
                } elseif ($w9_federaltax_classification == 'other') {
                    $data_to_update['w9_federaltax_description'] = $w9_other_federaltax_description;
                }

                $data_to_update['w9_exemption_payee_code'] = $w9_exemption_payee_code;
                $data_to_update['w9_exemption_reporting_code'] = $w9_exemption_reporting_code;
                $data_to_update['w9_address'] = $w9_address;
                $data_to_update['w9_city_state_zip'] = $w9_city_state_zip;
                $data_to_update['w9_requester_name_address'] = $w9_requester_name_address;
                $data_to_update['w9_account_no'] = $w9_account_no;
                $data_to_update['w9_social_security_number'] = $w9_social_security_number;
                $data_to_update['w9_employer_identification_number'] = $w9_employer_identification_number;
                $data_to_update['ip_address'] = $ip_address;
                $data_to_update['user_agent'] = $user_agent;
                $data_to_update['user_consent'] = $user_consent;
                $data_to_update['signature_timestamp'] = date('Y-m-d H:i:s');
                $data_to_update['signature_email_address'] = $affiliate_detail['email'];
                $data_to_update['signature_bas64_image'] = $signature_bas64_image;
                $data_to_update['init_signature_bas64_image'] = $initial_base64;
                $data_to_update['signature_ip_address'] = $signature_ip_address;
                $data_to_update['signature_user_agent'] = $signature_user_agent;
                $data_to_update['affiliate_sid'] = $affiliate_sid;
                $data_to_update['parent_sid'] = $affiliate_detail['parent_sid'];
                $data_to_update['full_name'] = $affiliate_detail['full_name'];
                $data_to_update['email_address'] = $affiliate_detail['email'];
                $data_to_update['w9_form_status'] = 'filled';


                $this->documents_model->update_w9_form($affiliate_sid, $data_to_update);
                $this->documents_model->unset_verification_key($affiliate_sid);

                $this->session->set_flashdata('message', '<strong>Success: </strong> Request Submitted Successfully!');
                redirect(base_url('documents/thankyou'), 'refresh');
            }
        } else {
            redirect(base_url('documents/linked_expired'), 'refresh');   
        }
    }

    public function get_signature ($user_sid, $company_sid, $user_type) {
        $signature = get_e_signature($company_sid, $user_sid, $user_type); 

        $return_data = array();
        if (!empty($signature) ) {
            $return_data['signature_sid'] = $signature['sid'];
            $return_data['company_sid'] = $signature['company_sid'];
            $return_data['signature'] = $signature['signature'];
            $return_data['signature_timestamp'] = date('m-d-Y', strtotime(str_replace('-', '/', $signature['signature_timestamp'])));
            $return_data['signature_bas64_image'] = $signature['signature_bas64_image'];
            $return_data['init_signature_bas64_image'] = $signature['init_signature_bas64_image'];
            $return_data['active_signature'] = $signature['active_signature'];
            $return_data['ip_address'] = $signature['ip_address'];
            $return_data['user_agent'] = $signature['user_agent'];
            $return_data['user_name'] = $signature['first_name'].' '.$signature['last_name'];

            echo json_encode($return_data);
        } else {
            echo false;
        }
    }

    public function save_e_signature () {
        $form_post = $this->input->post();
        $insert_signature = set_e_signature($form_post);
    }

    public function thankyou () {
        $data = array();
        $data['page_title'] = "Thank You";
        $data['message'] = 'Your W9 Form has been Submitted Successfully!';
        $this->load->view('main/header', $data);
        $this->load->view('documents/thankyou');
        $this->load->view('main/footer');    
    }

    public function linked_expired () {
        $data = array();
        $data['page_title'] = "Link Expired!";
        $data['message'] = 'Sorry this link has expired. Please contact AutomotoHR or your HR Manager for Assistance.';
        $this->load->view('main/header', $data);
        $this->load->view('errors/html/form_w9_error');
        $this->load->view('main/footer');     
    }

    public function print_download_w9_form ($document_sid = null, $action) {
        if ($this->session->userdata('affiliate_loggedin')) {
            if ($document_sid != null) {
                $affiliate_data = $this->session->userdata('affiliate_loggedin');
                $data['name'] = $affiliate_data['affiliate_users']['full_name'];
                $affiliate_detail = $affiliate_data['affiliate_users'];

                $form_w9 = $this->documents_model->get_w9_form_data($document_sid);
                if ($form_w9['user_consent'] != 1) {
                    $form_w9['w9_name'] = $affiliate_detail['full_name'];
                    $form_w9['w9_address'] = $affiliate_detail['address'];
                }
                $data['pre_form'] = $form_w9;
                $data['action'] = $action;
                $this->load->view('documents/w9_pdf_view',$data);
            } else {
                redirect('manage_admin/marketing_agencies');
            }
        } else {
            redirect(base_url('login'), "refresh");
        }    
    }


    // 
    function upload_and_save_document(){
        
        $sid = $this->session->userdata('affiliate_loggedin')['affiliate_users']['sid'];
        $filename = upload_file_to_aws('file', $sid, $this->input->post('document_name'));
        //
        $ins = [];
        $ins['marketing_agency_sid'] = $sid;
        $ins['document_name'] = $this->input->post('document_name');
        $ins['aws_document_name'] = $filename;
        $ins['client_upload_date'] = date('Y-m-d H:i:s', strtotime('now'));
        $ins['client_aws_filename'] = $filename;
        $ins['insert_date'] = date('Y-m-d H:i:s', strtotime('now'));
        $ins['status'] = 'signed';
        $ins['active_status'] = 1;
        //
        $this->documents_model->insertDocument($ins);

        echo 'success';
    }
   
    // 
    function upload_and_save_sign_document(){
        //
        $sid = $this->session->userdata('affiliate_loggedin')['affiliate_users']['sid'];
        $filename = upload_file_to_aws('file', $sid, $this->input->post('document_name'));
        //
        $upd = [];
        $upd['client_upload_date'] = date('Y-m-d H:i:s', strtotime('now'));
        $upd['client_aws_filename'] = $filename;
        $upd['status'] = 'signed';
        //
        $this->documents_model->updateDocument($upd,$this->input->post('id'));

        echo 'success';
    }

    function download($filename){
        downloadAWSFileToBrowser($filename);
    }
}
