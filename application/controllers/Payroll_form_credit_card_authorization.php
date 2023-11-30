<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Payroll_form_credit_card_authorization extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/documents_model');
    }

    public function index($verification_key = null, $pre_fill_flag = null) {
        $data = array();
        if ($verification_key != null) {
            $document_record = $this->documents_model->get_document_record('payroll_credit_card_authorization_form', $verification_key);
                //echo '<pre>'; print_r($document_record); exit;
            if (!empty($document_record) && ($document_record['status'] != 'signed') || $pre_fill_flag == 'view') {
                $status = $document_record['status'];
                $data['dont_show_it'] = $pre_fill_flag;
                $company_sid = $document_record['company_sid'];
                $ip_track = $this->documents_model->get_document_ip_tracking_record($company_sid, 'payroll_credit_card_authorization_form');
                $data['ip_track'] = $ip_track;
                $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
                $this->form_validation->set_message('required', '%s Required');

                if ($pre_fill_flag != null && $pre_fill_flag == 'pre_fill') {
                    $this->form_validation->set_rules('authorized_person_full_name', 'Name', 'xss_clean|trim');
                    $this->form_validation->set_rules('recurring_amount', 'Recurring Amount', 'xss_clean|trim|numeric');
                    $this->form_validation->set_rules('day_of_payment', 'Payment Day', 'xss_clean|trim');
                    $this->form_validation->set_rules('authorization_on_behalf_of', 'Individual or Company', 'xss_clean|trim');
                    $this->form_validation->set_rules('billing_address', 'Billing Address', 'xss_clean|trim');
                    $this->form_validation->set_rules('billing_phone_number', 'Phone Number', 'xss_clean|trim');
                    $this->form_validation->set_rules('billing_city', 'City', 'xss_clean|trim');
                    $this->form_validation->set_rules('billing_state', 'State', 'xss_clean|trim');
                    $this->form_validation->set_rules('billing_zip_code', 'Zip Code', 'xss_clean|trim');
                    $this->form_validation->set_rules('billing_email_address', 'Email', 'xss_clean|trim|valid_email');
                    $this->form_validation->set_rules('cc_type', '', 'xss_clean|trim');
                    $this->form_validation->set_rules('cc_holder_name', 'Name', 'xss_clean|trim');
                    $this->form_validation->set_rules('cc_number', 'Number', 'xss_clean|trim');
                    $this->form_validation->set_rules('cc_expiration_month', 'Expiration Month', 'xss_clean|trim');
                    $this->form_validation->set_rules('cc_expiration_year', 'Expiration Year', 'xss_clean|trim');
                    $this->form_validation->set_rules('cc_front_image', 'Front Image', 'xss_clean|trim');
                    $this->form_validation->set_rules('cc_back_image', 'Back Image', 'xss_clean|trim');
                    $this->form_validation->set_rules('driving_license_front_image', 'Front Image', 'xss_clean|trim');
                    $this->form_validation->set_rules('authorization_date', 'Date', 'xss_clean|trim');
                    $this->form_validation->set_rules('additional_fee', 'Additional Fee', 'xss_clean|trim|numeric');
                    $this->form_validation->set_rules('acknowledgement', 'Acknowledgement', 'xss_clean|trim');
                    $this->form_validation->set_rules('contract_type', 'Contract Type', 'xss_clean|trim');
                    $this->form_validation->set_rules('contract_length', 'Contract Length', 'xss_clean|trim');
                } else {
                    $this->form_validation->set_rules('authorized_person_full_name', 'Your Name', 'xss_clean|trim|required');
                    $this->form_validation->set_rules('recurring_amount', 'Recurring Amount', 'xss_clean|trim|numeric');
                    $this->form_validation->set_rules('day_of_payment', 'Payment Day', 'xss_clean|trim');
                    $this->form_validation->set_rules('authorization_on_behalf_of', 'Individual Cardholder or Company Representative Authorization', 'xss_clean|trim|required');
                    $this->form_validation->set_rules('billing_address', 'Billing Address', 'xss_clean|trim');
                    $this->form_validation->set_rules('billing_phone_number', 'Phone Number', 'xss_clean|trim');
                    $this->form_validation->set_rules('billing_city', 'City', 'xss_clean|trim');
                    $this->form_validation->set_rules('billing_state', 'State', 'xss_clean|trim');
                    $this->form_validation->set_rules('billing_zip_code', 'Zip Code', 'xss_clean|trim');
                    $this->form_validation->set_rules('billing_email_address', 'Email', 'xss_clean|trim');
                    $this->form_validation->set_rules('cc_type', 'Credit Card Type', 'xss_clean|trim|required');
                    $this->form_validation->set_rules('cc_holder_name', 'Name', 'xss_clean|trim|required');
                    $this->form_validation->set_rules('cc_number', 'Number', 'xss_clean|trim|required');
                    $this->form_validation->set_rules('cc_expiration_month', 'Expiration Month', 'xss_clean|trim|required');
                    $this->form_validation->set_rules('cc_expiration_year', 'Expiration Year', 'xss_clean|trim|required');
                    $this->form_validation->set_rules('cc_front_image', 'Front Image', 'xss_clean|trim');
                    $this->form_validation->set_rules('cc_back_image', 'Back Image', 'xss_clean|trim');
                    $this->form_validation->set_rules('driving_license_front_image', 'Front Image', 'xss_clean|trim');
                    $this->form_validation->set_rules('authorization_date', 'Date', 'xss_clean|trim');
                    $this->form_validation->set_rules('additional_fee', 'Additional Fee', 'xss_clean|trim|numeric');
                    $this->form_validation->set_rules('acknowledgement', 'Acknowledgement', 'required|xss_clean|trim');
                    $this->form_validation->set_rules('signature', 'Authorized Signature', 'required|xss_clean|trim');
                    $this->form_validation->set_rules('contract_type', 'Contract Type', 'xss_clean|trim');
                    $this->form_validation->set_rules('contract_length', 'Contract Length', 'xss_clean|trim');
                }

                if ($this->form_validation->run() == false) {                                    
                    if ($pre_fill_flag != null && $pre_fill_flag == 'pre_fill') { // Check if is prefill by admin
                        $data['is_pre_fill'] = 1;
                    } else {
                        $data['is_pre_fill'] = 0;
                    }

                    if ($status == 'signed') { // Check if is signed by client
                        $data['readonly'] = 1;
                    } else {
                        $data['readonly'] = 0;
                    }

                    $data['page_title'] = 'Credit Card Authorization';
                    $data['company_document'] = $document_record;
                    $data['verified'] = $document_record['processed'];
                    $data['verification_key'] = $verification_key;
                    $this->load->view('payroll_cc_auth/index', $data);
                } else {
                    $dataToSave = array();
                    $dataToSave['authorized_person_full_name'] = $this->input->post('authorized_person_full_name');
                    $dataToSave['recurring_amount'] = $this->input->post('recurring_amount');
                    $dataToSave['day_of_payment'] = $this->input->post('day_of_payment');
                    $dataToSave['authorization_on_behalf_of'] = $this->input->post('authorization_on_behalf_of');
                    $dataToSave['billing_address'] = $this->input->post('billing_address');
                    $dataToSave['billing_phone_number'] = $this->input->post('billing_phone_number');
                    $dataToSave['billing_city'] = $this->input->post('billing_city');
                    $dataToSave['billing_state'] = $this->input->post('billing_state');
                    $dataToSave['billing_zip_code'] = $this->input->post('billing_zip_code');
                    $dataToSave['billing_email_address'] = $this->input->post('billing_email_address');
                    $dataToSave['cc_type'] = $this->input->post('cc_type');
                    $dataToSave['cc_holder_name'] = $this->input->post('cc_holder_name');
                    $dataToSave['cc_number'] = $this->input->post('cc_number');
                    $dataToSave['cc_expiration_month'] = $this->input->post('cc_expiration_month');
                    $dataToSave['cc_expiration_year'] = $this->input->post('cc_expiration_year');
                    $dataToSave['authorized_signature'] = $this->input->post('signature');
                    $dataToSave['acknowledgement'] = $this->input->post('acknowledgement');
                    $dataToSave['contract_type'] = $this->input->post('contract_type');
                    $dataToSave['contract_length'] = $this->input->post('contract_length');
                    $uploadPath = realpath(APPPATH . '../assets/images/companies/');

                    if (isset($_FILES['cc_front_image']) && $_FILES['cc_front_image']['name'] != '') {
                        $cc_front_image = uploadFile($document_record['company_sid'], $uploadPath, 'cc_front_image', 5242880, array('jpg', 'jpeg', 'gif', 'png'), 'cc-front-img-' . $document_record['company_sid']);

                        if (base_url() == 'http://localhost/automotoCI/') {
                            $cc_front_image = substr($cc_front_image, strrpos($cc_front_image, '\\') + 1, strlen($cc_front_image));
                        } else {
                            $cc_front_image = substr($cc_front_image, strrpos($cc_front_image, '/') + 1, strlen($cc_front_image));
                        }

                        $dataToSave['cc_front_image'] = $cc_front_image;
                    }

                    if (isset($_FILES['cc_back_image']) && $_FILES['cc_back_image']['name'] != '') {
                        $cc_back_image = uploadFile($document_record['company_sid'], $uploadPath, 'cc_back_image', 5242880, array('jpg', 'jpeg', 'gif', 'png'), 'cc-back-img-' . $document_record['company_sid']);

                        if (base_url() == 'http://localhost/automotoCI/') {
                            $cc_back_image = substr($cc_back_image, strrpos($cc_back_image, '\\') + 1, strlen($cc_back_image));
                        } else {
                            $cc_back_image = substr($cc_back_image, strrpos($cc_back_image, '/') + 1, strlen($cc_back_image));
                        }

                        $dataToSave['cc_back_image'] = $cc_back_image;
                    }

                    if (isset($_FILES['driving_license_front_image']) && $_FILES['driving_license_front_image']['name'] != '') {
                        $driving_license_front_image = uploadFile($document_record['company_sid'], $uploadPath, 'driving_license_front_image', 5242880, array('jpg', 'jpeg', 'gif', 'png'), 'driving-license-front-img-' . $document_record['company_sid']);

                        if (base_url() == 'http://localhost/automotoCI/') {
                            $driving_license_front_image = substr($driving_license_front_image, strrpos($driving_license_front_image, '\\') + 1, strlen($driving_license_front_image));
                        } else {
                            $driving_license_front_image = substr($driving_license_front_image, strrpos($driving_license_front_image, '/') + 1, strlen($driving_license_front_image));
                        }

                        $dataToSave['driving_license_front_image'] = $driving_license_front_image;
                    }

                    $dataToSave['authorization_date'] = date('Y-m-d H:i:s', strtotime($this->input->post('authorization_date')));
                    $dataToSave['additional_fee'] = $this->input->post('additional_fee');
                    $is_pre_fill = $this->input->post('is_pre_fill');
                    $status = '';

                    if ($is_pre_fill == 1) {
                        $status = 'pre-filled';
                    } else {
                        $status = 'signed';
                        $processed = $document_record['processed'];
                        
                        //if ($processed==0) {
                            $cc_type = $dataToSave['cc_type'];
                            $cc_holder_name = $dataToSave['cc_holder_name'];
                            $cc_number = $dataToSave['cc_number'];
                            $cc_expiration_month = $dataToSave['cc_expiration_month'];
                            $cc_expiration_year = $dataToSave['cc_expiration_year'];
                            
                            $dataToSave['cc_type'] = encode_string($cc_type);
                            $dataToSave['cc_holder_name'] = encode_string($cc_holder_name);
                            $dataToSave['cc_number'] = encode_string($cc_number);
                            $dataToSave['cc_expiration_month'] = encode_string($cc_expiration_month);
                            $dataToSave['cc_expiration_year'] = encode_string($cc_expiration_year);
                            $dataToSave['processed'] = 1;
                            $dataToSave['client_ip'] = $_SERVER['SERVER_ADDR'];
                            $dataToSave['client_signature_timestamp'] = date('Y-m-d H:i:s');
                        //}
                    }
                    
                    $this->documents_model->update_document_record('payroll_credit_card_authorization_form', $verification_key, $dataToSave, $status);

                    if ($pre_fill_flag != null && $pre_fill_flag == 'pre_fill') {
                        $this->session->set_flashdata('message', 'Form Successfully Pre-Filled.');
                        $this->documents_model->insert_document_ip_tracking_record($company_sid, 0, getUserIP(), 'payroll_credit_card_authorization_form', 'pre_filled', $_SERVER['HTTP_USER_AGENT']);
                        redirect('manage_admin/documents/' . $company_sid, 'refresh');
                    } else {
                        $this->session->set_flashdata('message', '"We Appreciate Your Business"');

                        if ($this->session->userdata('logged_in')) {
                            $data['session'] = $this->session->userdata('logged_in');
                            $employer_sid = $data["session"]["employer_detail"]["sid"];
                        } else {
                            $employer_sid = -1;
                        }

                        $this->documents_model->insert_document_ip_tracking_record($company_sid, $employer_sid, getUserIP(), 'payroll_credit_card_authorization_form', 'signed', $_SERVER['HTTP_USER_AGENT']);
                        redirect('thank_you', 'refresh');
                    }
                }
            } else {
                redirect('thank_you', 'refresh');
            }
        } else {
            redirect('login', 'refresh');
        }
    }
}