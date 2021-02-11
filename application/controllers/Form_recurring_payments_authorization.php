<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Form_recurring_payments_authorization extends CI_Controller {
    public function __construct() {
        parent::__construct();

        $this->load->model('manage_admin/documents_model');

    }

    public function index($verification_key = null){
//        if($verification_key != null){
//
//            $document_record = $this->documents_model->get_document_record('recurring_payment_authorization', $verification_key);
//
//            if(!empty($document_record)){
//                $data = array();
//                $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
//                $this->form_validation->set_message('required', '%s Required');
//
//
//                $this->form_validation->set_rules('authorized_person_full_name', 'Your Name', 'required|xss_clean|trim');
//                $this->form_validation->set_rules('payment_day', 'payment_day', 'required|xss_clean|trim');
//                $this->form_validation->set_rules('billing_address', 'Billing Address', 'required|xss_clean|trim');
//                $this->form_validation->set_rules('phone_number', 'Phone Number', 'required|xss_clean|trim');
//                $this->form_validation->set_rules('billing_city', 'City', 'required|xss_clean|trim');
//                $this->form_validation->set_rules('billing_state', 'State', 'required|xss_clean|trim');
//                $this->form_validation->set_rules('billing_zip_code', 'Zip Code', 'required|xss_clean|trim');
//                $this->form_validation->set_rules('email', 'Email', 'required|xss_clean|trim|valid_email');
//
//                $this->form_validation->set_rules('bank_account_type', 'Bank Account Type', 'required|xss_clean|trim');
//                $this->form_validation->set_rules('bank_name', 'Bank Name', 'required|xss_clean|trim');
//                $this->form_validation->set_rules('bank_account_title', 'Bank Account Title', 'required|xss_clean|trim');
//                $this->form_validation->set_rules('bank_account_number', 'Bank Account Number', 'required|xss_clean|trim');
//                $this->form_validation->set_rules('bank_routing_number', 'Bank Routing Number', 'required|xss_clean|trim');
//                $this->form_validation->set_rules('bank_state', 'Bank State', 'required|xss_clean|trim');
//                $this->form_validation->set_rules('bank_city', 'Bank City', 'required|xss_clean|trim');
//
//                $this->form_validation->set_rules('cc_type', 'Credit Card Type', 'required|xss_clean|trim');
//                $this->form_validation->set_rules('cc_holder_name', 'Credit Card Holder Name', 'required|xss_clean|trim');
//                $this->form_validation->set_rules('cc_number', 'Credit Card Number', 'required|xss_clean|trim');
//                $this->form_validation->set_rules('cc_expiration_month', 'Credit Card Expiration Month', 'required|xss_clean|trim|numeric');
//                $this->form_validation->set_rules('cc_expiration_year', 'Credit Card Expiration Year', 'required|xss_clean|trim|numeric');
//                $this->form_validation->set_rules('signature', 'Signature', 'required|xss_clean|trim');
//                $this->form_validation->set_rules('date_of_authorization', 'Date of Authorization', 'required|xss_clean|trim');
//
//
//                if($this->form_validation->run() == false){
//
//
//                }else{
//                    $authorized_person_full_name = $this->input->post('authorized_person_full_name');
//                    $payment_day = $this->input->post('payment_day');
//                    $billing_address = $this->input->post('billing_address');
//                    $phone_number = $this->input->post('phone_number');
//                    $billing_city = $this->input->post('billing_city');
//                    $billing_state = $this->input->post('billing_state');
//                    $billing_zip_code = $this->input->post('billing_zip_code');
//                    $email = $this->input->post('email');
//
//                    $bank_account_type = $this->input->post('bank_account_type');
//                    $bank_name = $this->input->post('bank_name');
//                    $bank_account_title = $this->input->post('bank_account_title');
//                    $bank_account_number = $this->input->post('bank_account_number');
//                    $bank_routing_number = $this->input->post('bank_routing_number');
//                    $bank_state = $this->input->post('bank_state');
//                    $bank_city = $this->input->post('bank_city');
//
//                    $cc_type = $this->input->post('cc_type');
//                    $cc_holder_name = $this->input->post('cc_holder_name');
//                    $cc_number = $this->input->post('cc_number');
//                    $cc_expiration_month = $this->input->post('cc_expiration_month');
//                    $cc_expiration_year = $this->input->post('cc_expiration_year');
//                    $signature = $this->input->post('signature');
//                    $date_of_authorization = $this->input->post('date_of_authorization');
//
//                }
//
//                $data['verification_key'] = $verification_key;
//                }else{
//                //redirect('login', 'refresh');
//            }
//        } else{
//            //redirect('login', 'refresh');
//        }

        $this->load->view('form_recurring_payments_authorization/index');

    }




}
