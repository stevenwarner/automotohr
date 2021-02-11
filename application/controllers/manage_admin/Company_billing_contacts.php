<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Company_billing_contacts extends Admin_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('manage_admin/company_billing_contacts_model');

        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');


    }

    public function index($company_sid = null) {
        if($company_sid != null) {
            $admin_id = $this->session->userdata('user_id');
            $security_details = db_get_admin_access_level_details($admin_id);
            $this->data['security_details'] = $security_details;
            //check_access_permissions($security_details, 'manage_admin', 'invoices_panel'); // Param2: Redirect URL, Param3: Function Name
            $this->data['groups'] = $this->ion_auth->groups()->result();


            $company_billing_contacts = array();

            if ($company_sid != null && $company_sid != 0) {
                $this->data['company_sid'] = $company_sid;
                $company_name = $this->company_billing_contacts_model->get_company_name($company_sid);
                $this->data['company_name'] = $company_name;
                $company_billing_contacts = $this->company_billing_contacts_model->get_all_billing_contacts($company_sid);
            }


            $this->form_validation->set_rules('company_sid', 'Company Sid', 'required|trim');
            $this->form_validation->set_rules('company_billing_contact_sid', 'Company Billing Contact Sid', 'required|trim');


            if ($this->form_validation->run() == false) {

            } else {
                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'delete_company_billing_contact':
                        $company_sid = $this->input->post('company_sid');
                        $company_billing_contact_sid = $this->input->post('company_billing_contact_sid');

                        $this->company_billing_contacts_model->set_billing_contact_record_status($company_sid, $company_billing_contact_sid, 'deleted');

                        $this->session->set_flashdata('message', '<strong>Success: </strong> Company Contact Deleted.');

                        redirect('manage_admin/company_billing_contacts/' . $company_sid);
                        break;
                }
            }


            $this->data['company_billing_contacts'] = $company_billing_contacts;
            $this->data['page_title'] = 'Company Billing Contacts';
            $this->render('manage_admin/company_billing_contacts/index', 'admin_master');
        } else {
            $this->session->set_flashdata('message', '<strong>Error: </strong>Company Not Found!');
            redirect('manage_admin/companies');
        }
    }

    public function add_edit($company_billing_contact_sid = null, $company_sid = null) {
        $admin_id = $this->session->userdata('user_id');
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        //check_access_permissions($security_details, 'manage_admin', 'invoices_panel'); // Param2: Redirect URL, Param3: Function Name
        $this->data['groups'] = $this->ion_auth->groups()->result();


        $this->form_validation->set_rules('company_billing_contact_sid', 'Company Billing Contact Sid', 'required|trim');
        $this->form_validation->set_rules('title', 'Title', 'required|trim');
        $this->form_validation->set_rules('contact_name', 'Contact Name', 'required|trim');
        //$this->form_validation->set_rules('billing_address', 'Billing Address', 'required|trim');
        $this->form_validation->set_rules('email_address', 'Email Address', 'required|trim|valid_email');
        $this->form_validation->set_rules('phone_number', 'Phone Number', 'required|trim');
        $this->form_validation->set_rules('cell_number', 'Cell Number', 'trim');
        $this->form_validation->set_rules('company_name', 'Company Name', 'trim');


        if($this->form_validation->run() == false){

        }else{
            $field_data =  array();

            $company_billing_contact_sid = $this->input->post('company_billing_contact_sid');

            $field_data['title'] = $this->input->post('title');
            $field_data['contact_name'] = $this->input->post('contact_name');
            $field_data['billing_address'] = $this->input->post('billing_address');
            $field_data['email_address'] = $this->input->post('email_address');
            $field_data['phone_number'] = $this->input->post('phone_number');
            $field_data['cell_number'] = $this->input->post('cell_number');
            $field_data['company_name'] = $this->input->post('company_name');

            $this->company_billing_contacts_model->save_billing_contact_record($company_billing_contact_sid, $company_sid, $admin_id, $field_data);


            if($company_billing_contact_sid > 0) {
                $this->session->set_flashdata('message', '<strong>Success:</strong> Billing Contact Updated.');
            } else {
                $this->session->set_flashdata('message', '<strong>Success:</strong> New Billing Contact Added.');
            }

            redirect('manage_admin/company_billing_contacts/' . $company_sid);
        }


        if($company_sid != null && $company_sid != 0){
            $company_name = $this->company_billing_contacts_model->get_company_name($company_sid);
            $this->data['company_name'] = $company_name;
            $this->data['company_sid'] = $company_sid;




            if($company_billing_contact_sid != null && $company_billing_contact_sid != 0){
                $this->data['company_billing_contact_sid'] = $company_billing_contact_sid;
                $this->data['company_billing_contact_record'] = $this->company_billing_contacts_model->get_single_billing_contact_record($company_sid, $company_billing_contact_sid);
                $this->data['page_title'] = 'Edit Contact';
            }else{
                $this->data['company_billing_contact_sid'] = 0;
                $this->data['page_title'] = 'Add Contact: "' . $company_name . '"';
            }
        }else{
            $this->data['company_sid'] = 0;
        }

        $this->render('manage_admin/company_billing_contacts/add_edit', 'admin_master');
    }


}
