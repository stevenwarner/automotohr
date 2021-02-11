<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('invoice_model');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    public function index($company_name = 'all', $payment_type = 'all', $start_date = 'all', $end_date = 'all') {
        if ($this->session->userdata('affiliate_loggedin')) {
            $data = array();
            $affiliate_data = $this->session->userdata('affiliate_loggedin');
            $data['session'] = $affiliate_data;
            $affiliate_detail                                                    = $data['session']['affiliate_users'];
            $security_sid                                                       = $affiliate_detail['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;
            $company_name = urldecode($company_name);
            $payment_type = urldecode($payment_type);
            $start_date = urldecode($start_date);
            $end_date = urldecode($end_date);
            if (!empty($start_date) && $start_date != 'all') {
                $start_date_applied = empty($start_date) ? null : DateTime::createFromFormat('m-d-Y', $start_date)->format('Y-m-d 00:00:00');
            } else {
                $start_date_applied = NULL;
            }

            if (!empty($end_date) && $end_date != 'all') {
                $end_date_applied = empty($end_date) ? null : DateTime::createFromFormat('m-d-Y', $end_date)->format('Y-m-d 23:59:59');
            } else {
                $end_date_applied = NULL;
            }
            $data['title'] = 'Payment Vouchers';
            $company_name_array = array();
            if ($company_name != null && $company_name != 'all') {
                $company_name_array = explode(',', $company_name);
            }
            $data['company_name_array'] = $company_name_array;
            $session = $this->session->userdata('affiliate_loggedin');
            $data['name'] = $session['affiliate_users']['full_name'];
            $market_sid = $session['affiliate_users']['sid'];
            $all_companies = $this->invoice_model->get_all_company_names($market_sid);
            $all_vouchers = $this->invoice_model->get_all_invoices($market_sid,$company_name_array,$payment_type,$start_date_applied,$end_date_applied);
            $data['all_vouchers'] = $all_vouchers;
            $data['all_companies'] = $all_companies;
            $this->load->view('main/header', $data);
            $this->load->view('invoice/index');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function view_voucher($sid) {
        if ($this->session->userdata('affiliate_loggedin')) {
            $data = array();
            $data['title'] = 'Payment Vouchers';
            $affiliate_data = $this->session->userdata('affiliate_loggedin');
            $data['name'] = $affiliate_data['affiliate_users']['full_name'];
            $data['session'] = $affiliate_data;
            $affiliate_detail                                                    = $data['session']['affiliate_users'];
            $security_sid                                                       = $affiliate_detail['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;
            $voucher = $this->invoice_model->get_voucher_detail($sid);
            $data['voucher'] = $voucher;
            $this->load->view('main/header', $data);
            $this->load->view('invoice/voucher_detail');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function get_pdf(){
        $html = $this->input->raw_input_stream;
        $this->pdfgenerator->generate($html,'pdf');
    }
}
