<?php

use FontLib\Table\Type\head;

defined('BASEPATH') OR exit('No direct script access allowed');

class Merge_employees extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/merge_employees_model');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    public function index () {
        $this->data['security_details'] = $security_details = db_get_admin_access_level_details($this->ion_auth->user()->row()->id);
        check_access_permissions($security_details, 'manage_admin', 'merge_employees');

        $page_title = 'Copy Employees To Another Company';
        $companies = $this->merge_employees_model->get_all_companies();
        $this->data['page_title'] = $page_title;
        $this->data['companies'] = $companies;

        $this->render('manage_admin/company/merge_employees', 'admin_master');
    }

    function employees($companyId){
        //
        $employees = $this->merge_employees_model->GetCompanyEmployees($companyId);
        //
        if(!empty($employees)){
            $t =[];
            foreach($employees as $emp){
                $t[] = [
                    'id' => $emp['userId'],
                    'name' => remakeEmployeeName($emp),
                    'email' => $emp['email']
                ];
            }
            //
            $employees = $t;
        }
        header("Content-type: application/json");
        echo json_encode($employees);
        exit;
    }

    public function merge () {
        //
        $company_sid        = $this->input->post('company_sid');
        $secondary_employee_sid = $this->input->post('secondary_employee_sid');
        $primary_employee_sid   = $this->input->post('primary_employee_sid');
        $secondary_employee_email    = $this->input->post('secondary_employee_email');
        //
        //Update Primary Employee Profile
        $this->merge_employees_model->update_company_employee($primary_employee_sid, $secondary_employee_sid);
        
        // now move all other information
        
        // 1) Employee emergency contacts
        $this->merge_employees_model->update_employee_emergency_contacts($primary_employee_sid, $secondary_employee_sid);
        
        // 2) Employee equipment information
        $this->merge_employees_model->update_employee_equipment_information($primary_employee_sid, $secondary_employee_sid);
        
        // 3) Employee dependant information
        $this->merge_employees_model->update_employee_dependant_information($primary_employee_sid, $secondary_employee_sid);

        // 4) Employee license information
        $this->merge_employees_model->update_employee_license_information($primary_employee_sid, $secondary_employee_sid);

        // 5) Employee background check
        $this->merge_employees_model->update_employee_background_check($primary_employee_sid, $secondary_employee_sid);

        // 6) Employee portal misc notes
        $this->merge_employees_model->update_employee_misc_notes($primary_employee_sid, $secondary_employee_sid);

        // 7) Employee private_message
        $this->merge_employees_model->update_employee_private_message($primary_employee_sid, $secondary_employee_email);

        // 8) Employee portal rating
        $this->merge_employees_model->update_employee_rating($primary_employee_sid, $secondary_employee_sid);

        // 9) Employee calendar events
        $this->merge_employees_model->update_employee_schedule_event($primary_employee_sid, $secondary_employee_sid);

        // 10) Employee portal attachments
        $this->merge_employees_model->update_employee_attachments($primary_employee_sid, $secondary_employee_sid);

        // 11) Employee reference checks
        $this->merge_employees_model->update_employee_reference_checks($primary_employee_sid, $secondary_employee_sid);

        // 12) Employee Onboarding Configuration
        $this->merge_employees_model->update_onboarding_configuration($primary_employee_sid, $secondary_employee_sid);

        // 13) Employee Documents
        $this->merge_employees_model->update_documents($primary_employee_sid, $secondary_employee_sid);

        // 14) Employee Direct Deposit Information
        $this->merge_employees_model->update_employee_direct_deposit_information($primary_employee_sid, $secondary_employee_sid);

        // 15) Employee E-Signature Data
        $this->merge_employees_model->update_employee_e_signature($primary_employee_sid, $secondary_employee_sid);

        // 16) Employee EEOC Form
        $this->merge_employees_model->update_employee_eeoc_form($primary_employee_sid, $secondary_employee_sid);
        //
        $array['status'] = "success";
        $array['message'] = "Success! Employee is successfully merged!";
        $this->session->set_flashdata('message', '<b>Success:</b> Employee Merged Successfully!');
        //
        return print_r(json_encode($array));
    }
}


