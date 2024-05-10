<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Form_w9 extends Public_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('form_wi9_model');
        $this->load->model('e_signature_model');
        $this->load->library('pdfgenerator');
    }

    public function index($type = null, $sid = null, $jobs_listing = null)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $employer_sid = $data['session']['employer_detail']['sid'];

            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;

            $data['title'] = 'Form W9';
            $data['employee'] = $data['session']['employer_detail'];

            $company_sid = $data['session']['company_detail']['sid'];

            $employer_access_level = $data['session']['employer_detail']['access_level'];
            $employer_details = $data['session']['employer_detail'];

            if ($sid == NULL && $type == NULL) {
                $employer_sid = $employer_details['sid'];
                $parent_sid = $company_sid;

                if ($company_sid != $parent_sid) {
                    $this->session->set_flashdata('message', '<b>Error:</b> Employee Not Found!'); // Employee Exists In Db But Not In Same Company
                    $url = base_url('employee_management');
                    redirect($url, 'refresh');
                }

                $left_navigation = 'manage_employer/employee_management/profile_right_menu_personal';
                $data['title'] = 'My W9 Form';

                $reload_location = 'form_w9';
                $type = 'employee';

                $data['employer'] = $employer_details;

                $data['return_title_heading'] = 'My Profile';
                $back_url = base_url('my_profile');
                $form_back_url = base_url('hr_documents_management/my_documents');
                $data['return_title_heading_link'] = $back_url;

                $data['applicant_average_rating'] = $this->form_wi9_model->getApplicantAverageRating($employer_sid, 'employee'); // getting applicant ratings - getting average rating of applicant

                $e_signature_data = $this->e_signature_model->get_signature_record('employee', $employer_sid);
                $data['e_signature_data'] = $e_signature_data;

                $load_view = check_blue_panel_status(false, 'self');
            } else if ($type == 'employee') {
                check_access_permissions($security_details, 'employee_management', 'employee_form_w4'); // Param2: Redirect URL, Param3: Function Name
                $data = employee_right_nav($sid);
                $employer_sid = $sid;
                $parent_sid = $company_sid;

                if ($company_sid != $parent_sid) {
                    $this->session->set_flashdata('message', '<b>Error:</b> Employee Not Found!'); // Employee Exists In Db But Not In Same Company
                    $url = base_url('employee_management');
                    redirect($url, 'refresh');
                }

                $left_navigation = 'manage_employer/employee_management/profile_right_menu_employee_new';
                $data['title'] = 'Employee / W9 Form';

                $reload_location = 'form_w9/employee/' . $sid;

                $data['employer'] = $this->form_wi9_model->get_employee_information($company_sid, $employer_sid);

                $data['return_title_heading'] = 'Employee Profile';
                $back_url = base_url() . 'employee_profile/' . $sid;
                $form_back_url = base_url() . 'employee_profile/' . $sid;

                $data['return_title_heading_link'] = $back_url;

                $data['applicant_average_rating'] = $this->form_wi9_model->getApplicantAverageRating($sid, 'employee'); // getting applicant ratings - getting average rating of applicant

                $e_signature_data = $this->e_signature_model->get_signature_record('employee', $employer_sid);
                $data['e_signature_data'] = $e_signature_data;

                $load_view = check_blue_panel_status(false, $type);
            } else if ($type == 'applicant') {
                check_access_permissions($security_details, 'application_tracking', 'applicant_form_w4'); // Param2: Redirect URL, Param3: Function Name

                $data = applicant_right_nav($sid);
                $employer_sid = $sid;
                $left_navigation = 'manage_employer/application_tracking_system/profile_right_menu_applicant';
                $data['title'] = 'Applicant / W9 Form';

                $reload_location = 'form_w9/applicant/' . $sid . '/' . $jobs_listing;

                $data['return_title_heading'] = 'Applicant Profile';
                $data['return_title_heading_link'] = base_url() . 'applicant_profile/' . $sid . '/' . $jobs_listing;

                $data['company_background_check'] = checkCompanyAccurateCheck($company_sid);
                $data['kpa_onboarding_check'] = checkCompanyKpaOnboardingCheck($company_sid); //Outsourced HR Compliance and Onboarding check

                $applicant_info = $this->form_wi9_model->get_applicants_details($sid);
                $parent_sid = $applicant_info['company_sid'];

                if ($parent_sid > 0) {
                    if ($company_sid != $parent_sid) {
                        $this->session->set_flashdata('message', '<b>Error:</b> Applicant Not Found!'); // Applicant Exist In Db But Not in Same Company.
                        $url = base_url('application_tracking_system/active/all/all/all/all');
                        redirect($url, 'refresh');
                    }
                } else {
                    $this->session->set_flashdata('message', '<b>Error:</b> Applicant Not Found!'); // Applicant Does Not Exist In Db.
                    $url = base_url('application_tracking_system/active/all/all/all/all');
                    redirect($url, 'refresh');
                }

                $back_url = base_url('application_tracking_system/active/all/all/all/all');
                $form_back_url = base_url('application_tracking_system/active/all/all/all/all');

                $data_employer = array(
                    'sid' => $applicant_info['sid'],
                    'first_name' => $applicant_info['first_name'],
                    'last_name' => $applicant_info['last_name'],
                    'email' => $applicant_info['email'],
                    'Location_Address' => $applicant_info['address'],
                    'Location_City' => $applicant_info['city'],
                    'Location_Country' => $applicant_info['country'],
                    'Location_State' => $applicant_info['state'],
                    'Location_ZipCode' => $applicant_info['zipcode'],
                    'PhoneNumber' => $applicant_info['phone_number'],
                    'profile_picture' => $applicant_info['pictures'],
                    'user_type' => ucwords($type)
                );

                $data['applicant_notes'] = $this->form_wi9_model->getApplicantNotes($sid); //Getting Notes
                $data['applicant_average_rating'] = $this->form_wi9_model->getApplicantAverageRating($sid, 'applicant'); //getting average rating of applicant
                $data['employer'] = $data_employer;

                $e_signature_data = $this->e_signature_model->get_signature_record('applicant', $employer_sid);
                $data['e_signature_data'] = $e_signature_data;

                $load_view = check_blue_panel_status(false, $type);
            }


            $data['title'] = 'Form W-9';
            $data['employee'] = $data['session']['employer_detail'];

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

            $previous_form = $this->form_wi9_model->fetch_form('w9', $type, $employer_sid);
            if (empty($previous_form)) {
                $this->session->set_flashdata('message', '<strong>Error: </strong> W9 Form information not found!');
                redirect($form_back_url, 'refresh');
            }
            $data['pre_form'] = $previous_form;
            if (isset($_GET['submit']) && $_GET['submit'] == 'Download PDF') {
                $view = $this->load->view('form_w9/index-pdf', $data, TRUE);
                $this->pdfgenerator->generate($view, 'Form W9', true, 'A4', 'portrait');
            }

            $data['load_view'] = $load_view;
            $data['left_navigation'] = $left_navigation;

            $data['save_post_url'] = current_url();
            $data['company_sid'] = $company_sid;
            $data['users_type'] = $type;
            $data['users_sid'] = $employer_sid;
            $data['first_name'] = $data['employer']['first_name'];
            $data['last_name'] = $data['employer']['last_name'];
            $data['email'] = $data['employer']['email'];
            $data['documents_assignment_sid'] = null;

            $data['signed_flag'] = isset($previous_form['user_consent']) && $previous_form['user_consent'] == 1 ? true : false;

            if ($this->form_validation->run() == FALSE) {
                $this->load->view('main/header', $data);
                if(isset($previous_form['manual'])) $this->load->view('form_w9/index_upload');
                else $this->load->view('form_w9/index');
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
                $company_sid = $this->input->post('company_sid');
                $ip_address = $this->input->post('ip_address');
                $user_agent = $this->input->post('user_agent');
                $first_name = $this->input->post('first_name');
                $last_name = $this->input->post('last_name');
                $email_address = $this->input->post('email_address');
                $active_signature = $this->input->post('active_signature');
                $signature = $this->input->post('signature');
                $signature_email_address = $this->input->post('email_address');
                $signature_bas64_image = $this->input->post('signature_bas64_image');
                $initial_base64 = $this->input->post('init_signature_bas64_image');
                $signature_ip_address = $this->input->post('signature_ip_address');
                $signature_user_agent = $this->input->post('signature_user_agent');
                $user_consent = $this->input->post('user_consent');



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
                $data_to_update['company_sid'] = $company_sid;
                $data_to_update['ip_address'] = $ip_address;
                $data_to_update['user_agent'] = $user_agent;
                $data_to_update['first_name'] = $first_name;
                $data_to_update['last_name'] = $last_name;
                $data_to_update['email_address'] = $email_address;
                $data_to_update['active_signature'] = $active_signature;
                $data_to_update['signature'] = $signature;
                $data_to_update['user_consent'] = $user_consent;
                $data_to_update['signature_timestamp'] = getSystemDate();
                $data_to_update['signature_email_address'] = $signature_email_address;
                $data_to_update['signature_bas64_image'] = $signature_bas64_image;
                $data_to_update['init_signature_bas64_image'] = $initial_base64;
                $data_to_update['signature_ip_address'] = $signature_ip_address;
                $data_to_update['signature_user_agent'] = $signature_user_agent;
                $data_to_update['company_sid'] = $company_sid;
                $data_to_update['employee_sid'] = $employer_sid;
                //

                $this->load->model('2022/User_model', 'em');

                $this->em->handleGeneralDocumentChange(
                    'w9',
                    $this->input->post(null, true),
                    '',
                    $this->input->post('user_sid'),
                    $this->session->userdata('logged_in')['employer_detail']['sid']
                );

                $this->form_wi9_model->update_form('w9', $type, $employer_sid, $data_to_update);
                //
                $w9_sid = getVerificationDocumentSid ($employer_sid, $type, 'w9');
                keepTrackVerificationDocument($employer_sid, $type, 'completed', $w9_sid, 'w9', 'Blue Panel');
                //
                if($type != 'applicant' && $this->input->post('user_consent') == 1){
                    // Send document completion alert
                    broadcastAlert(
                        DOCUMENT_NOTIFICATION_ACTION_TEMPLATE,
                        'documents_status',
                        'w9_completed',
                        $company_sid,
                        $data['session']['company_detail']['CompanyName'],
                        $data['first_name'],
                        $data['last_name'],
                        $data['users_sid'],
                        [],
                        $type
                    );
                }

                $this->session->set_flashdata('message', '<strong>Success: </strong> Request Submitted Successfully!');
                redirect('form_w9', 'refresh');
            }

        } else {
            redirect('login', "refresh");
        }
    }

    public function upload_signed_form(){
        $user_sid = $this->input->post('user_sid');
        $current_url = $this->input->post('current_url');
        $sid = $this->input->post('form_sid');

        $data['session'] = $this->session->userdata('logged_in');
        $employer_sid = $data['session']['employer_detail']['sid'];
        $company_sid = $data['session']['company_detail']['sid'];

        if($_SERVER['HTTP_HOST']=='localhost'){
//            $aws_file_name = '0003-d_6-1542874444-39O.jpg';
//            $aws_file_name = '0057-testing_uploaded_doc-58-AAH.docx';
            $aws_file_name = '0057-test_latest_uploaded_document-58-Yo2.pdf';
        } else {
            $aws_file_name = upload_file_to_aws('upload_file', $company_sid, 'W9form_'.$user_sid , time());
        }

        $uploaded_file = '';

        if ($aws_file_name != 'error') {
            $uploaded_file = $aws_file_name;
        } else{
            $this->session->set_flashdata('message', '<b>Error:</b> There is some problem in uploading form.');
            redirect($current_url,'refresh');
        }

        $upload_form_array = array();
        $upload_form_array['user_consent'] = 1;
        $upload_form_array['uploaded_by_sid'] = $employer_sid;
        $upload_form_array['uploaded_file'] = $uploaded_file;
        $upload_form_array['signature_timestamp'] = date('Y-m-d H:i:s');

        $this->form_wi9_model->do_manual_upload_form($sid,$upload_form_array,'w9');
        $this->session->set_flashdata('message', '<b>Success:</b> W9 Form Uploaded Successfully.');
        redirect($current_url,'refresh');

    }

    public function print_w9_form ($type, $sid) {

        if ($this->session->userdata('logged_in')) {

            $previous_form = $this->form_wi9_model->fetch_form('w9', $type, $sid);
            $data['pre_form'] = $previous_form;

            $this->load->view('form_w9/print_w9_pdf', $data);

        } else {
            redirect('login', "refresh");
        }
    }

    public function preview_w9form($type, $sid)
    {
        if ($this->session->userdata('logged_in')) {
            $data['title'] = 'Form W-9';

            $previous_form = $this->form_wi9_model->fetch_form('w9', $type, $sid);
            $data['pre_form'] = $previous_form;

            if ($this->form_validation->run() == FALSE) {
                $this->load->view('form_w9/index-pdf', $data);
            }
        } else {
            redirect('login', "refresh");
        }
    }

    public function download_w9form ($type, $sid) {
        if ($this->session->userdata('logged_in')) {
            $data['title'] = 'Form W-9';

            $previous_form = $this->form_wi9_model->fetch_form('w9', $type, $sid);
            $data['pre_form'] = $previous_form;

            $this->load->view('form_w9/form_w9_pdf', $data);
        } else {
            redirect('login', "refresh");
        }
    }
}
