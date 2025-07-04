<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Recurring_payments extends Admin_Controller {
    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->library('form_validation');
        $this->load->model('manage_admin/recurring_payments_model');
    }

    public function index($company_name = null) {
        $redirect_url = 'manage_admin';
        $function_name = 'recurring_payments';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $company_name = $company_name == null ? 'all' : urldecode($company_name);
        $this->form_validation->set_rules('perform_action', 'Perform Action', 'required|xss_clean');
        $this->form_validation->set_rules('recurring_payment_sid', 'Recurring Payment Sid', 'required|xss_clean');
        $this->data['flag'] = true;
        $this->data['page_title'] = 'Recurring Payments';
        
        if ($this->form_validation->run() == false) {
            $active_recurring_payments = $this->recurring_payments_model->get_all_recurring_payment_records('active', $company_name);
            $inactive_recurring_payments = $this->recurring_payments_model->get_all_recurring_payment_records('in-active', $company_name);
            $this->data['active_recurring_payments'] = $active_recurring_payments;
            $this->data['inactive_recurring_payments'] = $inactive_recurring_payments;
            $this->render('manage_admin/recurring_payments/list_recurring_payments', 'admin_master');
        } else {
            $perform_action = $this->input->post('perform_action');

            switch ($perform_action) {
                case 'delete_recurring_payment':
                    $recurring_payment_sid = $this->input->post('recurring_payment_sid');
                    $this->recurring_payments_model->delete_recurring_payment_record($recurring_payment_sid);
                    $this->session->set_flashdata('message', '<strong>Success:</strong> Recurring Payment Successfully Deleted.');
                    redirect('manage_admin/recurring_payments', 'refresh');
                    break;
            }
        }
    }

    public function add_edit_recurring_payment($rec_payment_sid = null) {
        $redirect_url = 'manage_admin';
        $function_name = 'add_edit_delete_recurring_payments';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $rec_payment = array();
        
        if ($rec_payment_sid != null) {
            $rec_payment = $this->recurring_payments_model->get_recurring_payment_record($rec_payment_sid);
        }

        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        $this->form_validation->set_rules('company_sid', 'Company', 'required');
        $this->form_validation->set_rules('items[]', 'Items', 'required');
        $this->form_validation->set_rules('discount_amount', 'Discount Amount', 'required');
        $this->form_validation->set_rules('number_of_rooftops', 'Discount Amount', 'required|numeric');
        $this->form_validation->set_rules('payment_day', 'Discount Amount', 'required|numeric');

        if ($this->form_validation->run() == false) {
            
        } else {
            $rec_sid = $this->input->post('rec_sid');
            $company_sid = $this->input->post('company_sid');
            $items = $this->input->post('items[]');
            $discount_amount = $this->input->post('discount_amount');
            $number_of_rooftops = $this->input->post('number_of_rooftops');
            $payment_day = $this->input->post('payment_day');
            $total_after_discount = $this->input->post('total_after_discount');
            $status = $this->input->post('status');
            $admin_sid = $this->ion_auth->user()->row()->id;
            $ip_address = getUserIP();

            if ($rec_sid == '') {
                $rec_sid = null;
            }

            $this->recurring_payments_model->save_recurring_payment_record($rec_sid, $company_sid, $number_of_rooftops, $payment_day, $discount_amount, $admin_sid, $ip_address, $total_after_discount, $status, $items);
            redirect('manage_admin/recurring_payments', 'refresh');
        }

        $companies = $this->recurring_payments_model->get_all_companies_excluding_companies_in_rp();
        $company_detail = array();
        
        if (!empty($rec_payment)) {
            $company_sid = $rec_payment[0]['company_sid'];
            $company_detail = $this->recurring_payments_model->get_company_detail($company_sid);
            //echo $this->db->last_query();
        }

        $packages = $this->recurring_payments_model->get_all_products('account-package');
        $facebook_api = $this->recurring_payments_model->get_all_products('facebook-api');
        $development_fee = $this->recurring_payments_model->get_all_products('development-fee');
        $addons = array_merge($facebook_api, $development_fee);

        foreach ($packages as $key => $package) {
            if ($package['name'] == 'Deluxe Theme') {
                unset($packages[$key]);
            }
        }

        $packages = array_values($packages);
        $this->data['companies'] = $companies;
        $this->data['company_detail'] = $company_detail;
        $this->data['packages'] = $packages;
        $this->data['addons'] = $addons;

        if (!empty($rec_payment)) {
            $rec_payment = $rec_payment[0];
            $items = $rec_payment['cs_item_ids'];
            $items = explode(',', $items);
            $rec_payment['items'] = $items;
            $this->data['rec_payment'] = $rec_payment;
        } else {
            $this->data['rec_payment'] = array();
        }

        $this->render('manage_admin/recurring_payments/add_edit_recurring_payment', 'admin_master');
    }

}