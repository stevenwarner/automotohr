<?php defined('BASEPATH') or exit('No direct script access allowed');

class Onboarding_block extends Public_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Onboarding_block_model');
    }
    public function index()
    {
        if ($this->session->userdata('logged_in')) {

            if (!checkIfAppIsEnabled('onboardinghelpbox')) {
                $this->session->set_flashdata('message', '<b>Error:</b> Access denied');
                redirect(base_url('dashboard'), "refresh");
            }
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'appearance', 'document_management_portal'); // no need to check in this Module as Dashboard will be available to all

            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $companyBlockData = $this->Onboarding_block_model->check_companySid($company_sid);

            // $departments = $this->department_management_model->get_all_departments($company_sid);
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');

            $data['title'] = 'Onboarding Block';
            // $data['company_sid'] = $company_sid;
            // $data['employer_sid'] = $employer_sid;
            $data['companyBlockData'] = $companyBlockData;
            // $data['departments'] = $departments;

            if ($this->form_validation->run() == false) {

                $this->load->view('main/header', $data);
                $this->load->view('onboarding_block/index');
                $this->load->view('main/footer');
            }
        } else {
            redirect('login', 'refresh');
        }
    }
    public function insert_data_into_db()
    {
        $data['session'] = $this->session->userdata('logged_in');
        $company_sid = $data['session']['company_detail']['sid'];
        $employer_sid = $data['session']['employer_detail']['sid'];
        $title = $this->input->post('title');
        $phone_number = $this->input->post('phone_number');
        $email_address = $this->input->post('email_address');
        $description = $this->input->post('description');
        $sid = $this->input->post('sid');

        //die(print_r($email_address));

        $data_to_insert = array();

        $data_to_insert['company_sid'] = $company_sid;
        $data_to_insert['employer_sid'] = $employer_sid;
        $data_to_insert['block_title'] = trim($title);
        $data_to_insert['phone_number'] = trim($phone_number);
        $data_to_insert['email_address'] = trim(($email_address));
        $data_to_insert['description'] = trim($description);
        $data_to_insert['is_active'] = $this->input->post('status', true);

        // header('content-type: application/json');

        // echo json_encode(array('msg' => 'Success', 'sid' => 666));
        // exit;

        if ($sid == 0) {
            $companyBlockData = $this->Onboarding_block_model->check_companySid($data_to_insert['company_sid']);
            if (!sizeof($companyBlockData)) {
                $is_insert = $this->Onboarding_block_model->insert_data_into_DB($data_to_insert);
                if (!empty($is_insert)) {
                    echo json_encode(array('msg' => 'Success', 'success' => 'Success: Your details have been saved.', 'sid' => $is_insert));
                } else {
                    echo json_encode(array('msg' => 'not successful', 'error' => 'Error: Something went wrong while saving the details. Please, try again in a few seconds.'));
                }
            } else {
                echo json_encode(array('msg' => 'not successful', 'error' => 'Error: Details already exists for this company.'));
            }
        } else {
            // Update

            $is_insert = $this->Onboarding_block_model->update_data_into_DB($data_to_insert, $company_sid);
            echo json_encode(array('msg' => 'Success', 'success' => 'Success: Your details have been updated.'));
        }
    }



    //
    function manage_company_help_box()
    {

        if ($this->session->userdata('logged_in')) {
            if (!checkIfAppIsEnabled('companyhelpbox')) {
                $this->session->set_flashdata('message', '<b>Error:</b> Access denied');
                redirect(base_url('dashboard'), "refresh");
            }

            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'dashboard', 'my_settings');
            $employer_id = $data["session"]["employer_detail"]["sid"];
            $data['title'] = "Company Help Box";
            $company_sid = $data['session']['company_detail']['sid'];

            if ($company_sid != null) {

                $data['page_title'] = 'Manage Help Box Info';
                $data['company_sid'] = $company_sid;
                $contact_info = $this->Onboarding_block_model->get_helpbox_info($company_sid);

                if (sizeof($contact_info) == 0) {
                    $contact_info[0]['box_title'] = '';
                    $contact_info[0]['box_support_email'] = '';
                    $contact_info[0]['box_support_phone_number'] = '';
                    $contact_info[0]['box_status'] = '0';
                    $contact_info[0]['buton_text'] = 'Contact Support';
                }

                $data['contact_info'] = $contact_info;
                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/manage_company_help_box');
                $this->load->view('main/footer');
            } else {
                redirect('my_settings', 'refresh');
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    //
    public function manage_company_help_box_update()
    {
        $data['session'] = $this->session->userdata('logged_in');
        $company_sid = $data['session']['company_detail']['sid'];
        $employer_sid = $data['session']['employer_detail']['sid'];
        $helpboxTitle = $this->input->post('helpboxtitle');
        $helpboxEmail = $this->input->post('helpboxemail');
        $helpboxPhoneNumber = $this->input->post('helpboxphonenumber');
        $helpboxStatus = $this->input->post('helpboxstatus');
        $helpButtonText = $this->input->post('helpButtonText');
        $this->Onboarding_block_model->add_update_helpbox_info($company_sid, $helpboxTitle, $helpboxEmail, $helpboxPhoneNumber, $helpboxStatus, $helpButtonText);
        // Update
        echo json_encode(array('msg' => 'Success', 'success' => 'Success: Help box details have been updated.'));
    }
}
