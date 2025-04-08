<?php defined('BASEPATH') or exit('No direct script access allowed');

class Form_i9 extends Public_Controller
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
            $filler_sid = $data['session']['employer_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            //
            $uInfo = [];

            $data['title'] = 'Form I-9';
            $data['employee'] = $data['session']['employer_detail'];

            $company_sid = $data['session']['company_detail']['sid'];

            $employer_access_level = $data['session']['employer_detail']['access_level'];
            $employer_details = $data['session']['employer_detail'];

            $redirect_url = base_url('form_i9');
            if ($sid == NULL && $type == NULL) {
                $employer_sid = $employer_details['sid'];
                $parent_sid = $company_sid;

                if ($company_sid != $parent_sid) {
                    $this->session->set_flashdata('message', '<b>Error:</b> Employee Not Found!'); // Employee Exists In Db But Not In Same Company
                    $url = base_url('employee_management');
                    redirect($url, 'refresh');
                }

                $left_navigation = 'manage_employer/employee_management/profile_right_menu_personal';
                $data['title'] = 'My I9 Form';

                $reload_location = 'form_i9';
                $type = 'employee';

                $data['employer'] = $employer_details;

                $data['return_title_heading'] = 'My Profile';

                $back_url = base_url('my_profile');
                $form_back_url = base_url('hr_documents_management/my_documents');
                $data['return_title_heading_link'] = $back_url;

                $data['applicant_average_rating'] = $this->form_wi9_model->getApplicantAverageRating($employer_sid, 'employee'); // getting applicant ratings - getting average rating of applicant

                $load_view = check_blue_panel_status(false, 'self');
            } else if ($type == 'employee') {
                check_access_permissions($security_details, 'employee_management', 'employee_form_i9'); // Param2: Redirect URL, Param3: Function Name
                $data = employee_right_nav($sid);
                $employer_sid = $sid;
                $parent_sid = $company_sid;
                $data['employee'] = $data['session']['employer_detail'];
                $data['employee']['company_corp_name'] = getCompanyCorporateName($data['employee']['parent_sid']);

                if ($company_sid != $parent_sid) {
                    $this->session->set_flashdata('message', '<b>Error:</b> Employee Not Found!'); // Employee Exists In Db But Not In Same Company
                    $url = base_url('employee_management');
                    redirect($url, 'refresh');
                }

                $left_navigation = 'manage_employer/employee_management/profile_right_menu_employee_new';
                $data['title'] = 'Employee / I9 Form';

                $reload_location = 'form_i9/employee/' . $sid;

                $data['employer'] = $this->form_wi9_model->get_employee_information($company_sid, $employer_sid);

                $data['return_title_heading'] = 'Employee Profile';

                $back_url = base_url() . 'employee_profile/' . $sid;
                $form_back_url = base_url() . 'employee_profile/' . $sid;
                $data['return_title_heading_link'] = $back_url;

                $data['applicant_average_rating'] = $this->form_wi9_model->getApplicantAverageRating($sid, 'employee'); // getting applicant ratings - getting average rating of applicant

                $load_view = check_blue_panel_status(false, $type);
                $redirect_url = base_url('hr_documents_management/documents_assignment/employee/' . $sid);
            } else if ($type == 'applicant') {
                check_access_permissions($security_details, 'application_tracking', 'applicant_form_i9'); // Param2: Redirect URL, Param3: Function Name
                $employer_sid = $sid;
                $data = applicant_right_nav($sid);
                $data['employee'] = $data['session']['employer_detail'];
                $left_navigation = 'manage_employer/application_tracking_system/profile_right_menu_applicant';
                $data['title'] = 'Applicant / I9 Form';

                $reload_location = 'form_i9/applicant/' . $sid . '/' . $jobs_listing;

                $data['return_title_heading'] = 'Applicant Profile';

                $back_url = base_url() . 'applicant_profile/' . $sid . '/' . $jobs_listing;
                $form_back_url = base_url() . 'applicant_profile/' . $sid . '/' . $jobs_listing;
                $data['return_title_heading_link'] = $back_url;

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

                $data['applicant_notes'] = $this->form_wi9_model->getApplicantNotes($employer_sid); //Getting Notes
                $data['applicant_average_rating'] = $this->form_wi9_model->getApplicantAverageRating($employer_sid, 'applicant'); //getting average rating of applicant
                $data['employer'] = $data_employer;
                $data['employee']['company_corp_name']=getCompanyCorporateName($parent_sid);

                

                $load_view = check_blue_panel_status(false, $type);
                $redirect_url = base_url('hr_documents_management/applicant/' . $sid . '/' . $jobs_listing);
            }

            $data['states'] = db_get_active_states(227);
            $data['employer_sid'] = $employer_sid;

            $field = array(
                'field' => 'section1_last_name',
                'label' => 'Last Name',
                'rules' => 'xss_clean|trim|required'
            );
            $order_field = array(
                'field' => 'section1_first_name',
                'label' => 'First Name',
                'rules' => 'xss_clean|trim|required'
            );

            $config[] = $field;
            $config[] = $order_field;

            $this->form_validation->set_error_delimiters('<label class="error">', '</label>');
            $this->form_validation->set_rules($config);

            $data['section2_flag'] = false;

            $previous_form = $this->form_wi9_model->fetch_form('i9', $type, $employer_sid);

            if (empty($previous_form)) {
                $this->session->set_flashdata('message', '<strong>Error: </strong> Form information not found!');
                redirect($form_back_url, 'refresh');
            }

            if (!empty($previous_form["section1_preparer_or_translator"]) && empty($previous_form["section1_preparer_json"])) {
                $previous_form["section1_preparer_json"] = copyPrepareI9Json($previous_form);
            }

            if (!empty($previous_form["section3_emp_sign"]) && empty($previous_form["section3_authorized_json"])) {
                $previous_form["section3_authorized_json"] = copyAuthorizedI9Json($previous_form);
            }

            $data['pre_form'] = $previous_form;
            $data['form'] = $previous_form;

            if ($employer_access_level == 'Admin' && sizeof($previous_form) > 0 && $previous_form['user_sid'] != $security_sid) {
                $data['section2_flag'] = true;
            }
            if (sizeof($previous_form) > 0) {
                $files = $this->form_wi9_model->form_docs($previous_form['sid']);
                $data['files'] = $files;
            }

            $e_signature_data = $this->e_signature_model->get_signature_record('employee', $employer_sid);
            $data['e_signature_data'] = $e_signature_data;

            if ($sid != NULL) {
                $filler_e_signature_data = $this->e_signature_model->get_signature_record('employee', $filler_sid);
                $data['filler_e_signature_data'] = $filler_e_signature_data;
            }

            $data['load_view'] = $load_view;
            $data['left_navigation'] = $left_navigation;

            $data['signed_flag'] = isset($previous_form['user_consent']) && $previous_form['user_consent'] == 1 ? true : false;

            $data['company_sid'] = $company_sid;
            $data['users_type'] = $type;
            $data['users_sid'] = $employer_sid;

            $data['first_name'] =  $data['employee']['first_name'];
            $data['last_name'] = $data['employee']['last_name'];
            $data['email'] = $data['employee']['email'];
            $data['documents_assignment_sid'] = null;
            $data['prepare_signature'] = 'get_prepare_signature';

            $data['company_corp_name'] =$data['employee']['company_corp_name'];

            if ($this->form_validation->run() == FALSE) {
                if (empty($previous_form['user_consent'])) {
                    if ($type == 'employee') {
                        $this->session->set_flashdata('message', '<strong>Info: </strong> Employee consent is required!');
                    } else {
                        $this->session->set_flashdata('message', '<strong>Info: </strong> Applicant consent is required!');
                    }
                }
                $this->load->view('main/header', $data);
                //
                if (!empty($previous_form['s3_filename']) && $previous_form['s3_filename'] != NULL) {
                    $this->load->view('form_i9/index_uploaded');
                } else {
                    if (!empty($data['form']["version"]) && $data['form']["version"] == "2025") {
                        $this->load->view('v1/forms/i9/2025/index');
                    } else {
                        $this->load->view('form_i9/index_new');
                    }
                }
                //
                $this->load->view('main/footer');
            } else {
                //
                $formpost = $this->input->post(NULL, TRUE);
                //
                if ($type != 'applicant' && $formpost['user_consent'] == 1) {
                    // Send document completion alert
                    broadcastAlert(
                        DOCUMENT_NOTIFICATION_ACTION_TEMPLATE,
                        'documents_status',
                        'i9_completed',
                        $company_sid,
                        $data['session']['company_detail']['CompanyName'],
                        $data['first_name'],
                        $data['last_name'],
                        $data['users_sid']
                    );
                }

                $insert_data = array();

                if (sizeof($previous_form) == 0 || !$previous_form['applicant_flag'] || $security_sid == $previous_form['emp_app_sid']) {
                    // Section 1 Data Array Starts
                    $insert_data['section1_last_name'] = $formpost['section1_last_name'];
                    $insert_data['section1_first_name'] = $formpost['section1_first_name'];
                    $insert_data['section1_middle_initial'] = $formpost['section1_middle_initial'];
                    $insert_data['section1_other_last_names'] = $formpost['section1_other_last_names'];
                    $insert_data['section1_address'] = $formpost['section1_address'];
                    $insert_data['section1_apt_number'] = $formpost['section1_apt_number'];
                    $insert_data['section1_city_town'] = $formpost['section1_city_town'];
                    $insert_data['section1_state'] = $formpost['section1_state'];
                    $insert_data['section1_zip_code'] = $formpost['section1_zip_code'];
                    $insert_data['section1_date_of_birth'] = empty($formpost['section1_date_of_birth']) || $formpost['section1_date_of_birth'] == 'N/A' ? null : DateTime::createFromFormat('m-d-Y', $formpost['section1_date_of_birth'])->format('Y-m-d H:i:s');
                    $insert_data['section1_social_security_number'] = $formpost['section1_social_security_number'];
                    $insert_data['section1_emp_email_address'] = $formpost['section1_emp_email_address'];
                    $insert_data['section1_emp_telephone_number'] = $formpost['section1_emp_telephone_number'];
                    $insert_data['section1_penalty_of_perjury'] = $formpost['section1_penalty_of_perjury'];
                    $options = array();
                    if ($formpost['section1_penalty_of_perjury'] == 'permanent-resident') {
                        $options['section1_alien_registration_number_one'] = $formpost['section1_alien_registration_number_one'];
                        $options['section1_alien_registration_number_two'] = $formpost['section1_alien_registration_number_two'];
                    } elseif ($formpost['section1_penalty_of_perjury'] == 'alien-work') {
                        $options['section1_alien_registration_number_one'] = $formpost['section1_alien_registration_number_one'];
                        $options['section1_alien_registration_number_two'] = $formpost['section1_alien_registration_number_two'];
                        $options['alien_authorized_expiration_date'] = empty($formpost['alien_authorized_expiration_date']) || $formpost['alien_authorized_expiration_date'] == 'N/A' ? null : DateTime::createFromFormat('m-d-Y', $formpost['alien_authorized_expiration_date'])->format('Y-m-d H:i:s');
                        $options['form_admission_number'] = $formpost['form_admission_number'];
                        $options['foreign_passport_number'] = $formpost['foreign_passport_number'];
                        $options['country_of_issuance'] = $formpost['country_of_issuance'];
                    }

                    $insert_data['section1_alien_registration_number'] = serialize($options);
                    //$insert_data['section1_emp_signature'] = $formpost['section1_emp_signature'];
                    $insert_data['section1_today_date'] = empty($formpost['section1_today_date']) || $formpost['section1_today_date'] == 'N/A' ? null : DateTime::createFromFormat('m-d-Y', $formpost['section1_today_date'])->format('Y-m-d H:i:s');
                    $options = array();

                    $options['section1_preparer_or_translator'] = $formpost['section1_preparer_or_translator'];
                    $options['number-of-preparer'] = $formpost['number-of-preparer'];
                    $insert_data['section1_preparer_or_translator'] = serialize($options);

                    $insert_data['section1_preparer_today_date'] = empty($formpost['section1_preparer_today_date']) || $formpost['section1_preparer_today_date'] == 'N/A' ? null : DateTime::createFromFormat('m-d-Y', $formpost['section1_preparer_today_date'])->format('Y-m-d H:i:s');

                    if ($sid != NULL) {
                        $insert_data['section1_preparer_signature'] = $filler_e_signature_data['signature_bas64_image'];
                    }
                    $insert_data['section1_preparer_last_name'] = $formpost['section1_preparer_last_name'];
                    $insert_data['section1_preparer_first_name'] = $formpost['section1_preparer_first_name'];
                    $insert_data['section1_preparer_city_town'] = $formpost['section1_preparer_city_town'];
                    $insert_data['section1_preparer_address'] = $formpost['section1_preparer_address'];
                    $insert_data['section1_preparer_state'] = $formpost['section1_preparer_state'];
                    $insert_data['section1_preparer_zip_code'] = $formpost['section1_preparer_zip_code'];


                    $insert_data['company_sid'] = $company_sid;
                    $insert_data['emp_app_sid'] = $employer_sid;
                    $insert_data['applicant_flag'] = 1;
                    $insert_data['applicant_filled_date'] = date('Y-m-d H:i:s');


                    // User Consent And Signature Task

                    $signature_bas64_image = $this->input->post('signature_bas64_image');
                    $initial_signature_base64_image = $this->input->post('init_signature_bas64_image');

                    $insert_data['section1_emp_signature'] = $signature_bas64_image;
                    $insert_data['section1_emp_signature_init'] = $initial_signature_base64_image;;
                    $insert_data['section1_emp_signature_ip_address'] = getUserIP();
                    $insert_data['section1_emp_signature_user_agent'] = $_SERVER['HTTP_USER_AGENT'];

                    if ($sid == NULL) {
                        $insert_data['user_consent'] = 1;
                    }

                    // Section 1 Ends

                } else if ($security_sid != $previous_form['emp_app_sid']) {

                    // Portal Form I9 Tracker
                    $mailbody = [];
                    $mailbody['usersid'] = $employer_sid;
                    $mailbody['companysid'] = $data['session']['company_detail']['sid'];
                    $mailbody['previous_form_sid'] = $previous_form['emp_app_sid'];
                    $mailbody['reviewer_signature_base64'] = $reviewer_signature_base64;
                    $mailbody['get_signature_query'] = $this->db->last_query();
                    //
                    if (empty($formpost['section2_firstday_of_emp_date']) || $formpost['section2_firstday_of_emp_date'] == 'N/A') {
                        $reviewer_signature_base64 = '';
                    }
                    //
                    $insert_data['section2_last_name'] = $formpost['section2_last_name'];
                    $insert_data['section2_first_name'] = $formpost['section2_first_name'];
                    $insert_data['section2_middle_initial'] = $formpost['section2_middle_initial'];
                    $insert_data['section2_citizenship'] = $formpost['section2_citizenship'];

                    $insert_data['section2_lista_part1_document_title'] = $formpost['lista_part1_doc_select_input'] != 'input' ? $formpost['section2_lista_part1_document_title'] : $formpost['section2_lista_part1_document_title_text_val'];
                    $insert_data['section2_lista_part1_issuing_authority'] = isset($formpost['section2_lista_part1_issuing_authority']) && $formpost['lista_part1_issuing_select_input'] != 'input' ? $formpost['section2_lista_part1_issuing_authority'] : $formpost['section2_lista_part1_issuing_authority_text_val'];
                    $insert_data['section2_lista_part1_document_number'] = $formpost['section2_lista_part1_document_number'];
                    $insert_data['section2_lista_part1_expiration_date'] = empty($formpost['section2_lista_part1_expiration_date']) || $formpost['section2_lista_part1_expiration_date'] == 'N/A' ? null : DateTime::createFromFormat('m-d-Y', $formpost['section2_lista_part1_expiration_date'])->format('Y-m-d H:i:s');
                    $insert_data['section2_lista_part2_document_title'] = $formpost['lista_part2_doc_select_input'] != 'input' ? $formpost['section2_lista_part2_document_title'] : $formpost['section2_lista_part2_document_title_text_val'];
                    $insert_data['section2_lista_part2_issuing_authority'] = isset($formpost['section2_lista_part2_issuing_authority']) && $formpost['lista_part2_issuing_select_input'] != 'input' ? $formpost['section2_lista_part2_issuing_authority'] : $formpost['section2_lista_part2_issuing_authority_text_val'];
                    $insert_data['section2_lista_part2_document_number'] = $formpost['section2_lista_part2_document_number'];
                    $insert_data['section2_lista_part2_expiration_date'] = empty($formpost['section2_lista_part2_expiration_date']) || $formpost['section2_lista_part2_expiration_date'] == 'N/A' ? null : DateTime::createFromFormat('m-d-Y', $formpost['section2_lista_part2_expiration_date'])->format('Y-m-d H:i:s');
                    $insert_data['section2_lista_part3_document_title'] = $formpost['lista_part3_doc_select_input'] != 'input' ? $formpost['section2_lista_part3_document_title'] : $formpost['section2_lista_part3_document_title_text_val'];
                    $insert_data['section2_lista_part3_issuing_authority'] = isset($formpost['section2_lista_part3_issuing_authority']) && $formpost['lista_part3_doc_select_input'] != 'input' ? $formpost['section2_lista_part3_issuing_authority'] : $formpost['section2_lista_part3_issuing_authority_text_val'];
                    $insert_data['section2_lista_part3_document_number'] = $formpost['section2_lista_part3_document_number'];
                    $insert_data['section2_lista_part3_expiration_date'] = empty($formpost['section2_lista_part3_expiration_date']) || $formpost['section2_lista_part3_expiration_date'] == 'N/A' ? null : DateTime::createFromFormat('m-d-Y', $formpost['section2_lista_part3_expiration_date'])->format('Y-m-d H:i:s');
                    $insert_data['section2_additional_information'] = $formpost['section2_additional_information'];

                    $insert_data['listb_auth_select_input'] = isset($formpost['listb-auth-select-input']) ? $formpost['listb-auth-select-input'] : '';
                    $insert_data['lista_part1_doc_select_input'] = isset($formpost['lista_part1_doc_select_input']) ? $formpost['lista_part1_doc_select_input'] : '';
                    $insert_data['lista_part1_issuing_select_input'] = isset($formpost['lista_part1_issuing_select_input']) ? $formpost['lista_part1_issuing_select_input'] : '';
                    $insert_data['lista_part2_doc_select_input'] = isset($formpost['lista_part2_doc_select_input']) ? $formpost['lista_part2_doc_select_input'] : '';
                    $insert_data['lista_part2_issuing_select_input'] = isset($formpost['lista_part2_issuing_select_input']) ? $formpost['lista_part2_issuing_select_input'] : '';
                    $insert_data['lista_part3_doc_select_input'] = isset($formpost['lista_part3_doc_select_input']) ? $formpost['lista_part3_doc_select_input'] : '';
                    $insert_data['lista_part3_issuing_select_input'] = isset($formpost['lista_part3_issuing_select_input']) ? $formpost['lista_part3_issuing_select_input'] : '';

                    $insert_data['section2_listb_document_title'] = $formpost['section2_listb_document_title'];
                    $insert_data['section2_listb_issuing_authority'] = isset($formpost['section2_listb_issuing_authority']) && $formpost['listb-auth-select-input'] != 'input' ? $formpost['section2_listb_issuing_authority'] : $formpost['section2_listb_issuing_authority_text_val'];
                    $insert_data['section2_listb_document_number'] = $formpost['section2_listb_document_number'];
                    $insert_data['section2_listb_expiration_date'] = empty($formpost['section2_listb_expiration_date']) || $formpost['section2_listb_expiration_date'] == 'N/A' ? null : DateTime::createFromFormat('m-d-Y', $formpost['section2_listb_expiration_date'])->format('Y-m-d H:i:s');

                    $insert_data['section2_listc_document_title'] = $formpost['section2_listc_document_title'];
                    $insert_data['section2_listc_dhs_extra_field'] = $formpost['section2_listc_dhs_extra_field'];
                    $insert_data['listc_auth_select_input'] = isset($formpost['listc-auth-select-input']) ? $formpost['listc-auth-select-input'] : '';
                    $insert_data['section2_listc_issuing_authority'] = isset($formpost['section2_listc_issuing_authority']) && $formpost['listc-auth-select-input'] != 'input' ? $formpost['section2_listc_issuing_authority'] : $formpost['section2_listc_issuing_authority_text_val'];
                    $insert_data['section2_listc_document_number'] = $formpost['section2_listc_document_number'];
                    $insert_data['section2_listc_expiration_date'] = empty($formpost['section2_listc_expiration_date']) || $formpost['section2_listc_expiration_date'] == 'N/A' ? null : DateTime::createFromFormat('m-d-Y', $formpost['section2_listc_expiration_date'])->format('Y-m-d H:i:s');

                    $insert_data['section2_firstday_of_emp_date'] = empty($formpost['section2_firstday_of_emp_date']) || $formpost['section2_firstday_of_emp_date'] == 'N/A' ? null : DateTime::createFromFormat('m-d-Y', $formpost['section2_firstday_of_emp_date'])->format('Y-m-d H:i:s');
                    $insert_data['section2_sig_emp_auth_rep'] = $reviewer_signature_base64;

                    $insert_data['section2_today_date'] = empty($formpost['section2_today_date']) || $formpost['section2_today_date'] == 'N/A' ? null : DateTime::createFromFormat('m-d-Y', $formpost['section2_today_date'])->format('Y-m-d H:i:s');
                    $insert_data['section2_title_of_emp'] = $formpost['section2_title_of_emp'];
                    $insert_data['section2_last_name_of_emp'] = $formpost['section2_last_name_of_emp'];
                    $insert_data['section2_first_name_of_emp'] = $formpost['section2_first_name_of_emp'];
                    $insert_data['section2_emp_business_name'] = $formpost['section2_emp_business_name'];
                    $insert_data['section2_emp_business_address'] = $formpost['section2_emp_business_address'];
                    $insert_data['section2_city_town'] = $formpost['section2_city_town'];
                    $insert_data['section2_state'] = $formpost['section2_state'];
                    $insert_data['section2_zip_code'] = $formpost['section2_zip_code'];
                    //

                    //
                    $details = [];
                    // 
                    for ($i = 1; $i <= 3; $i++) {
                        $details[$i] = [
                            'section3_rehire_date' => $formpost['section3_authorized_rehire_date_' . $i],
                            'section3_last_name' => $formpost['section3_authorized_last_name_' . $i],
                            'section3_first_name' => $formpost['section3_authorized_first_name_' . $i],
                            'section3_middle_initial' => $formpost['section3_authorized_middle_initial_' . $i],
                            'section3_document_title' => $formpost['section3_authorized_document_title_' . $i],
                            'section3_document_number' => $formpost['section3_authorized_document_number_' . $i],
                            'section3_expiration_date' => $formpost['section3_authorized_expiration_date_' . $i],
                            'section3_name_of_emp' => $formpost['section3_authorized_name_of_emp_' . $i],
                            'signature' => $formpost['section3_authorized_signature_' . $i],
                            'section3_signature_date' => $formpost['section3_authorized_today_date_' . $i],
                            'section3_additional_information' => $formpost['section3_authorized_additional_information_' . $i],
                            'section3_alternative_procedure' => isset($formpost['section3_authorized_alternative_procedure_' . $i]) ? 1 : 0,
                        ];
                        //
                    }

                    $insert_data['section1_authorized_json'] = json_encode($details);
                    $insert_data['emp_app_sid'] = $employer_sid;
                    $insert_data['employer_flag'] = 1;
                    $insert_data['employer_filled_date'] = date('Y-m-d H:i:s');

                    // Section 2,3 Ends
                }

                // TO be checked and removed
                if (isset($formpost['section1_last_name'])) {
                    $insert_data['section1_last_name'] = $formpost['section1_last_name'];
                    $insert_data['section1_first_name'] = $formpost['section1_first_name'];
                    $insert_data['section1_middle_initial'] = $formpost['section1_middle_initial'];
                    $insert_data['section1_other_last_names'] = $formpost['section1_other_last_names'];
                    $insert_data['section1_address'] = $formpost['section1_address'];
                    $insert_data['section1_apt_number'] = $formpost['section1_apt_number'];
                    $insert_data['section1_city_town'] = $formpost['section1_city_town'];
                    $insert_data['section1_state'] = $formpost['section1_state'];
                    $insert_data['section1_zip_code'] = $formpost['section1_zip_code'];
                    $insert_data['section1_date_of_birth'] = empty($formpost['section1_date_of_birth']) || $formpost['section1_date_of_birth'] == 'N/A' ? null : DateTime::createFromFormat('m-d-Y', $formpost['section1_date_of_birth'])->format('Y-m-d H:i:s');
                    $insert_data['section1_social_security_number'] = $formpost['section1_social_security_number'];
                    $insert_data['section1_emp_email_address'] = $formpost['section1_emp_email_address'];
                    $insert_data['section1_emp_telephone_number'] = $formpost['section1_emp_telephone_number'];
                }

                if ($previous_form['user_consent'] == 0) {
                    $insert_data['employer_flag'] = 0;
                }

                // Log i9 form
                $i9TrackerData = [];
                $i9TrackerData['data'] = $insert_data;
                $i9TrackerData['loggedIn_person_id'] = $security_sid;
                $i9TrackerData['previous_form_sid'] = $previous_form['emp_app_sid'];
                $i9TrackerData['session_id'] = session_id();
                $i9TrackerData['session_employer_id'] = $data['session']['employer_detail']['sid'];
                $i9TrackerData['session_company_id'] = $data['session']['company_detail']['sid'];
                $i9TrackerData['reviewer_signature_base64'] = $reviewer_signature_base64;
                $i9TrackerData['module'] = $sid ? 'fi9/gp' : 'fi9/bp';
                //
                portalFormI9Tracker($employer_sid, $type, $i9TrackerData);

                //$this->form_wi9_model->insert_form_data('i9', $insert_data, $employer_sid);
                $this->form_wi9_model->update_form('i9', $type, $employer_sid, $insert_data);
                //
                $i9_sid = getVerificationDocumentSid($employer_sid, $type, 'i9');
                keepTrackVerificationDocument($employer_sid, $type, 'completed', $i9_sid, 'i9', 'Blue Panel');
                //
                $this->session->set_flashdata('message', '<strong>Success: </strong> I-9 Submitted Successfully!');
                redirect($redirect_url, 'refresh');
            }
        } else {
            redirect('login', "refresh");
        }
    }

    public function index_old($type = null, $sid = null, $jobs_listing = null)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $filler_sid = $data['session']['employer_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            //
            $uInfo = [];

            $data['title'] = 'Form I-9';
            $data['employee'] = $data['session']['employer_detail'];

            $company_sid = $data['session']['company_detail']['sid'];

            $employer_access_level = $data['session']['employer_detail']['access_level'];
            $employer_details = $data['session']['employer_detail'];

            $redirect_url = base_url('form_i9');
            if ($sid == NULL && $type == NULL) {
                $employer_sid = $employer_details['sid'];
                $parent_sid = $company_sid;

                if ($company_sid != $parent_sid) {
                    $this->session->set_flashdata('message', '<b>Error:</b> Employee Not Found!'); // Employee Exists In Db But Not In Same Company
                    $url = base_url('employee_management');
                    redirect($url, 'refresh');
                }

                $left_navigation = 'manage_employer/employee_management/profile_right_menu_personal';
                $data['title'] = 'My I9 Form';

                $reload_location = 'form_i9';
                $type = 'employee';

                $data['employer'] = $employer_details;

                $data['return_title_heading'] = 'My Profile';

                $back_url = base_url('my_profile');
                $form_back_url = base_url('hr_documents_management/my_documents');
                $data['return_title_heading_link'] = $back_url;

                $data['applicant_average_rating'] = $this->form_wi9_model->getApplicantAverageRating($employer_sid, 'employee'); // getting applicant ratings - getting average rating of applicant

                $load_view = check_blue_panel_status(false, 'self');
            } else if ($type == 'employee') {
                check_access_permissions($security_details, 'employee_management', 'employee_form_i9'); // Param2: Redirect URL, Param3: Function Name
                $data = employee_right_nav($sid);
                $employer_sid = $sid;
                $parent_sid = $company_sid;
                $data['employee'] = $data['session']['employer_detail'];

                if ($company_sid != $parent_sid) {
                    $this->session->set_flashdata('message', '<b>Error:</b> Employee Not Found!'); // Employee Exists In Db But Not In Same Company
                    $url = base_url('employee_management');
                    redirect($url, 'refresh');
                }

                $left_navigation = 'manage_employer/employee_management/profile_right_menu_employee_new';
                $data['title'] = 'Employee / I9 Form';

                $reload_location = 'form_i9/employee/' . $sid;

                $data['employer'] = $this->form_wi9_model->get_employee_information($company_sid, $employer_sid);

                $data['return_title_heading'] = 'Employee Profile';

                $back_url = base_url() . 'employee_profile/' . $sid;
                $form_back_url = base_url() . 'employee_profile/' . $sid;
                $data['return_title_heading_link'] = $back_url;

                $data['applicant_average_rating'] = $this->form_wi9_model->getApplicantAverageRating($sid, 'employee'); // getting applicant ratings - getting average rating of applicant

                $load_view = check_blue_panel_status(false, $type);
                $redirect_url = base_url('hr_documents_management/documents_assignment/employee/' . $sid);
            } else if ($type == 'applicant') {
                check_access_permissions($security_details, 'application_tracking', 'applicant_form_i9'); // Param2: Redirect URL, Param3: Function Name
                $employer_sid = $sid;
                $data = applicant_right_nav($sid);
                $data['employee'] = $data['session']['employer_detail'];
                $left_navigation = 'manage_employer/application_tracking_system/profile_right_menu_applicant';
                $data['title'] = 'Applicant / I9 Form';

                $reload_location = 'form_i9/applicant/' . $sid . '/' . $jobs_listing;

                $data['return_title_heading'] = 'Applicant Profile';

                $back_url = base_url() . 'applicant_profile/' . $sid . '/' . $jobs_listing;
                $form_back_url = base_url() . 'applicant_profile/' . $sid . '/' . $jobs_listing;
                $data['return_title_heading_link'] = $back_url;

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

                $data['applicant_notes'] = $this->form_wi9_model->getApplicantNotes($employer_sid); //Getting Notes
                $data['applicant_average_rating'] = $this->form_wi9_model->getApplicantAverageRating($employer_sid, 'applicant'); //getting average rating of applicant
                $data['employer'] = $data_employer;

                $load_view = check_blue_panel_status(false, $type);
                $redirect_url = base_url('hr_documents_management/applicant/' . $sid . '/' . $jobs_listing);
            }

            $data['states'] = db_get_active_states(227);
            $data['employer_sid'] = $employer_sid;

            $field = array(
                'field' => 'section1_last_name',
                'label' => 'Last Name',
                'rules' => 'xss_clean|trim|required'
            );
            $order_field = array(
                'field' => 'section1_first_name',
                'label' => 'First Name',
                'rules' => 'xss_clean|trim|required'
            );

            $config[] = $field;
            $config[] = $order_field;

            $this->form_validation->set_error_delimiters('<label class="error">', '</label>');
            $this->form_validation->set_rules($config);

            $data['section2_flag'] = false;

            $previous_form = $this->form_wi9_model->fetch_form('i9', $type, $employer_sid);

            if (empty($previous_form)) {
                $this->session->set_flashdata('message', '<strong>Error: </strong> Form information not found!');
                redirect($form_back_url, 'refresh');
            }

            $data['pre_form'] = $previous_form;

            if ($employer_access_level == 'Admin' && sizeof($previous_form) > 0 && $previous_form['user_sid'] != $security_sid) {
                $data['section2_flag'] = true;
            }
            if (sizeof($previous_form) > 0) {
                $files = $this->form_wi9_model->form_docs($previous_form['sid']);
                $data['files'] = $files;
            }

            $e_signature_data = $this->e_signature_model->get_signature_record('employee', $employer_sid);
            $data['e_signature_data'] = $e_signature_data;

            if ($sid != NULL) {
                $filler_e_signature_data = $this->e_signature_model->get_signature_record('employee', $filler_sid);
                $data['filler_e_signature_data'] = $filler_e_signature_data;
            }

            $data['load_view'] = $load_view;
            $data['left_navigation'] = $left_navigation;

            $data['signed_flag'] = isset($previous_form['user_consent']) && $previous_form['user_consent'] == 1 ? true : false;

            $data['company_sid'] = $company_sid;
            $data['users_type'] = $type;
            $data['users_sid'] = $employer_sid;

            $data['first_name'] = $data['employee']['first_name'];
            $data['last_name'] = $data['employee']['last_name'];
            $data['email'] = $data['employee']['email'];
            $data['documents_assignment_sid'] = null;
            $data['prepare_signature'] = 'get_prepare_signature';

            if ($this->form_validation->run() == FALSE) {
                if (empty($previous_form['user_consent'])) {
                    if ($type == 'employee') {
                        $this->session->set_flashdata('message', '<strong>Info: </strong> Employee consent is required!');
                    } else {
                        $this->session->set_flashdata('message', '<strong>Info: </strong> Applicant consent is required!');
                    }
                }
                $this->load->view('main/header', $data);
                if (!empty($previous_form['s3_filename']) && $previous_form['s3_filename'] != NULL) $this->load->view('form_i9/index_uploaded');
                else $this->load->view('form_i9/index');
                $this->load->view('main/footer');
            } else {
                //
                $formpost = $this->input->post(NULL, TRUE);
                //
                if ($type != 'applicant' && $formpost['user_consent'] == 1) {
                    // Send document completion alert
                    broadcastAlert(
                        DOCUMENT_NOTIFICATION_ACTION_TEMPLATE,
                        'documents_status',
                        'i9_completed',
                        $company_sid,
                        $data['session']['company_detail']['CompanyName'],
                        $data['first_name'],
                        $data['last_name'],
                        $data['users_sid']
                    );
                }

                $insert_data = array();

                if (sizeof($previous_form) == 0 || !$previous_form['applicant_flag'] || $security_sid == $previous_form['emp_app_sid']) {
                    // Section 1 Data Array Starts
                    $insert_data['section1_last_name'] = $formpost['section1_last_name'];
                    $insert_data['section1_first_name'] = $formpost['section1_first_name'];
                    $insert_data['section1_middle_initial'] = $formpost['section1_middle_initial'];
                    $insert_data['section1_other_last_names'] = $formpost['section1_other_last_names'];
                    $insert_data['section1_address'] = $formpost['section1_address'];
                    $insert_data['section1_apt_number'] = $formpost['section1_apt_number'];
                    $insert_data['section1_city_town'] = $formpost['section1_city_town'];
                    $insert_data['section1_state'] = $formpost['section1_state'];
                    $insert_data['section1_zip_code'] = $formpost['section1_zip_code'];
                    $insert_data['section1_date_of_birth'] = empty($formpost['section1_date_of_birth']) || $formpost['section1_date_of_birth'] == 'N/A' ? null : DateTime::createFromFormat('m-d-Y', $formpost['section1_date_of_birth'])->format('Y-m-d H:i:s');
                    $insert_data['section1_social_security_number'] = $formpost['section1_social_security_number'];
                    $insert_data['section1_emp_email_address'] = $formpost['section1_emp_email_address'];
                    $insert_data['section1_emp_telephone_number'] = $formpost['section1_emp_telephone_number'];
                    $insert_data['section1_penalty_of_perjury'] = $formpost['section1_penalty_of_perjury'];
                    $options = array();
                    if ($formpost['section1_penalty_of_perjury'] == 'permanent-resident') {
                        $options['section1_alien_registration_number_one'] = $formpost['section1_alien_registration_number_one'];
                        $options['section1_alien_registration_number_two'] = $formpost['section1_alien_registration_number_two'];
                    } elseif ($formpost['section1_penalty_of_perjury'] == 'alien-work') {
                        $options['section1_alien_registration_number_one'] = $formpost['section1_alien_registration_number_one'];
                        $options['section1_alien_registration_number_two'] = $formpost['section1_alien_registration_number_two'];
                        $options['alien_authorized_expiration_date'] = empty($formpost['alien_authorized_expiration_date']) || $formpost['alien_authorized_expiration_date'] == 'N/A' ? null : DateTime::createFromFormat('m-d-Y', $formpost['alien_authorized_expiration_date'])->format('Y-m-d H:i:s');
                        $options['form_admission_number'] = $formpost['form_admission_number'];
                        $options['foreign_passport_number'] = $formpost['foreign_passport_number'];
                        $options['country_of_issuance'] = $formpost['country_of_issuance'];
                    }

                    $insert_data['section1_alien_registration_number'] = serialize($options);
                    //$insert_data['section1_emp_signature'] = $formpost['section1_emp_signature'];
                    $insert_data['section1_today_date'] = empty($formpost['section1_today_date']) || $formpost['section1_today_date'] == 'N/A' ? null : DateTime::createFromFormat('m-d-Y', $formpost['section1_today_date'])->format('Y-m-d H:i:s');
                    $options = array();

                    $options['section1_preparer_or_translator'] = $formpost['section1_preparer_or_translator'];
                    $options['number-of-preparer'] = $formpost['number-of-preparer'];
                    $insert_data['section1_preparer_or_translator'] = serialize($options);

                    $insert_data['section1_preparer_today_date'] = empty($formpost['section1_preparer_today_date']) || $formpost['section1_preparer_today_date'] == 'N/A' ? null : DateTime::createFromFormat('m-d-Y', $formpost['section1_preparer_today_date'])->format('Y-m-d H:i:s');

                    if ($sid != NULL) {
                        $insert_data['section1_preparer_signature'] = $filler_e_signature_data['signature_bas64_image'];
                    }
                    $insert_data['section1_preparer_last_name'] = $formpost['section1_preparer_last_name'];
                    $insert_data['section1_preparer_first_name'] = $formpost['section1_preparer_first_name'];
                    $insert_data['section1_preparer_city_town'] = $formpost['section1_preparer_city_town'];
                    $insert_data['section1_preparer_address'] = $formpost['section1_preparer_address'];
                    $insert_data['section1_preparer_state'] = $formpost['section1_preparer_state'];
                    $insert_data['section1_preparer_zip_code'] = $formpost['section1_preparer_zip_code'];


                    $insert_data['company_sid'] = $company_sid;
                    $insert_data['emp_app_sid'] = $employer_sid;
                    $insert_data['applicant_flag'] = 1;
                    $insert_data['applicant_filled_date'] = date('Y-m-d H:i:s');


                    // User Consent And Signature Task

                    $signature_bas64_image = $this->input->post('signature_bas64_image');
                    $initial_signature_base64_image = $this->input->post('init_signature_bas64_image');

                    $insert_data['section1_emp_signature'] = $signature_bas64_image;
                    $insert_data['section1_emp_signature_init'] = $initial_signature_base64_image;;
                    $insert_data['section1_emp_signature_ip_address'] = getUserIP();
                    $insert_data['section1_emp_signature_user_agent'] = $_SERVER['HTTP_USER_AGENT'];

                    if ($sid == NULL) {
                        $insert_data['user_consent'] = 1;
                    }

                    // Section 1 Ends

                } else if ($security_sid != $previous_form['emp_app_sid']) {

                    // Section 2,3 Data Array Starts
                    $user_sid = $employer_details['sid'];
                    $user_type = 'employee';

                    $signature = get_e_signature($company_sid, $user_sid, $user_type);

                    if (!empty($signature)) {
                        $reviewer_signature_base64 = $signature['signature_bas64_image'];
                    }


                    // Portal Form I9 Tracker
                    $mailbody = [];
                    $mailbody['usersid'] = $employer_sid;
                    $mailbody['companysid'] = $data['session']['company_detail']['sid'];
                    $mailbody['previous_form_sid'] = $previous_form['emp_app_sid'];
                    $mailbody['reviewer_signature_base64'] = $reviewer_signature_base64;
                    $mailbody['get_signature_query'] = $this->db->last_query();

                    if (empty($formpost['section2_firstday_of_emp_date']) || $formpost['section2_firstday_of_emp_date'] == 'N/A') {
                        $reviewer_signature_base64 = '';
                    }

                    $insert_data['section2_last_name'] = $formpost['section2_last_name'];
                    $insert_data['section2_first_name'] = $formpost['section2_first_name'];
                    $insert_data['section2_middle_initial'] = $formpost['section2_middle_initial'];
                    $insert_data['section2_citizenship'] = $formpost['section2_citizenship'];

                    $insert_data['section2_lista_part1_document_title'] = $formpost['lista_part1_doc_select_input'] != 'input' ? $formpost['section2_lista_part1_document_title'] : $formpost['section2_lista_part1_document_title_text_val'];
                    $insert_data['section2_lista_part1_issuing_authority'] = isset($formpost['section2_lista_part1_issuing_authority']) && $formpost['lista_part1_issuing_select_input'] != 'input' ? $formpost['section2_lista_part1_issuing_authority'] : $formpost['section2_lista_part1_issuing_authority_text_val'];
                    $insert_data['section2_lista_part1_document_number'] = $formpost['section2_lista_part1_document_number'];
                    $insert_data['section2_lista_part1_expiration_date'] = empty($formpost['section2_lista_part1_expiration_date']) || $formpost['section2_lista_part1_expiration_date'] == 'N/A' ? null : DateTime::createFromFormat('m-d-Y', $formpost['section2_lista_part1_expiration_date'])->format('Y-m-d H:i:s');
                    $insert_data['section2_lista_part2_document_title'] = $formpost['lista_part2_doc_select_input'] != 'input' ? $formpost['section2_lista_part2_document_title'] : $formpost['section2_lista_part2_document_title_text_val'];
                    $insert_data['section2_lista_part2_issuing_authority'] = isset($formpost['section2_lista_part2_issuing_authority']) && $formpost['lista_part2_issuing_select_input'] != 'input' ? $formpost['section2_lista_part2_issuing_authority'] : $formpost['section2_lista_part2_issuing_authority_text_val'];
                    $insert_data['section2_lista_part2_document_number'] = $formpost['section2_lista_part2_document_number'];
                    $insert_data['section2_lista_part2_expiration_date'] = empty($formpost['section2_lista_part2_expiration_date']) || $formpost['section2_lista_part2_expiration_date'] == 'N/A' ? null : DateTime::createFromFormat('m-d-Y', $formpost['section2_lista_part2_expiration_date'])->format('Y-m-d H:i:s');
                    $insert_data['section2_lista_part3_document_title'] = $formpost['lista_part3_doc_select_input'] != 'input' ? $formpost['section2_lista_part3_document_title'] : $formpost['section2_lista_part3_document_title_text_val'];
                    $insert_data['section2_lista_part3_issuing_authority'] = isset($formpost['section2_lista_part3_issuing_authority']) && $formpost['lista_part3_doc_select_input'] != 'input' ? $formpost['section2_lista_part3_issuing_authority'] : $formpost['section2_lista_part3_issuing_authority_text_val'];
                    $insert_data['section2_lista_part3_document_number'] = $formpost['section2_lista_part3_document_number'];
                    $insert_data['section2_lista_part3_expiration_date'] = empty($formpost['section2_lista_part3_expiration_date']) || $formpost['section2_lista_part3_expiration_date'] == 'N/A' ? null : DateTime::createFromFormat('m-d-Y', $formpost['section2_lista_part3_expiration_date'])->format('Y-m-d H:i:s');
                    $insert_data['section2_additional_information'] = $formpost['section2_additional_information'];

                    $insert_data['listb_auth_select_input'] = isset($formpost['listb-auth-select-input']) ? $formpost['listb-auth-select-input'] : '';
                    $insert_data['lista_part1_doc_select_input'] = isset($formpost['lista_part1_doc_select_input']) ? $formpost['lista_part1_doc_select_input'] : '';
                    $insert_data['lista_part1_issuing_select_input'] = isset($formpost['lista_part1_issuing_select_input']) ? $formpost['lista_part1_issuing_select_input'] : '';
                    $insert_data['lista_part2_doc_select_input'] = isset($formpost['lista_part2_doc_select_input']) ? $formpost['lista_part2_doc_select_input'] : '';
                    $insert_data['lista_part2_issuing_select_input'] = isset($formpost['lista_part2_issuing_select_input']) ? $formpost['lista_part2_issuing_select_input'] : '';
                    $insert_data['lista_part3_doc_select_input'] = isset($formpost['lista_part3_doc_select_input']) ? $formpost['lista_part3_doc_select_input'] : '';
                    $insert_data['lista_part3_issuing_select_input'] = isset($formpost['lista_part3_issuing_select_input']) ? $formpost['lista_part3_issuing_select_input'] : '';

                    $insert_data['section2_listb_document_title'] = $formpost['section2_listb_document_title'];
                    $insert_data['section2_listb_issuing_authority'] = isset($formpost['section2_listb_issuing_authority']) && $formpost['listb-auth-select-input'] != 'input' ? $formpost['section2_listb_issuing_authority'] : $formpost['section2_listb_issuing_authority_text_val'];
                    $insert_data['section2_listb_document_number'] = $formpost['section2_listb_document_number'];
                    $insert_data['section2_listb_expiration_date'] = empty($formpost['section2_listb_expiration_date']) || $formpost['section2_listb_expiration_date'] == 'N/A' ? null : DateTime::createFromFormat('m-d-Y', $formpost['section2_listb_expiration_date'])->format('Y-m-d H:i:s');

                    $insert_data['section2_listc_document_title'] = $formpost['section2_listc_document_title'];
                    $insert_data['section2_listc_dhs_extra_field'] = $formpost['section2_listc_dhs_extra_field'];
                    $insert_data['listc_auth_select_input'] = isset($formpost['listc-auth-select-input']) ? $formpost['listc-auth-select-input'] : '';
                    $insert_data['section2_listc_issuing_authority'] = isset($formpost['section2_listc_issuing_authority']) && $formpost['listc-auth-select-input'] != 'input' ? $formpost['section2_listc_issuing_authority'] : $formpost['section2_listc_issuing_authority_text_val'];
                    $insert_data['section2_listc_document_number'] = $formpost['section2_listc_document_number'];
                    $insert_data['section2_listc_expiration_date'] = empty($formpost['section2_listc_expiration_date']) || $formpost['section2_listc_expiration_date'] == 'N/A' ? null : DateTime::createFromFormat('m-d-Y', $formpost['section2_listc_expiration_date'])->format('Y-m-d H:i:s');

                    $insert_data['section2_firstday_of_emp_date'] = empty($formpost['section2_firstday_of_emp_date']) || $formpost['section2_firstday_of_emp_date'] == 'N/A' ? null : DateTime::createFromFormat('m-d-Y', $formpost['section2_firstday_of_emp_date'])->format('Y-m-d H:i:s');
                    $insert_data['section2_sig_emp_auth_rep'] = $reviewer_signature_base64;

                    $insert_data['section2_today_date'] = empty($formpost['section2_today_date']) || $formpost['section2_today_date'] == 'N/A' ? null : DateTime::createFromFormat('m-d-Y', $formpost['section2_today_date'])->format('Y-m-d H:i:s');
                    $insert_data['section2_title_of_emp'] = $formpost['section2_title_of_emp'];
                    $insert_data['section2_last_name_of_emp'] = $formpost['section2_last_name_of_emp'];
                    $insert_data['section2_first_name_of_emp'] = $formpost['section2_first_name_of_emp'];
                    $insert_data['section2_emp_business_name'] = $formpost['section2_emp_business_name'];
                    $insert_data['section2_emp_business_address'] = $formpost['section2_emp_business_address'];
                    $insert_data['section2_city_town'] = $formpost['section2_city_town'];
                    $insert_data['section2_state'] = $formpost['section2_state'];
                    $insert_data['section2_zip_code'] = $formpost['section2_zip_code'];

                    $insert_data['section3_pre_last_name'] = $formpost['section3_pre_last_name'];
                    $insert_data['section3_pre_first_name'] = $formpost['section3_pre_first_name'];
                    $insert_data['section3_pre_middle_initial'] = $formpost['section3_pre_middle_initial'];
                    $insert_data['section3_last_name'] = $formpost['section3_last_name'];
                    $insert_data['section3_first_name'] = $formpost['section3_first_name'];
                    $insert_data['section3_middle_initial'] = $formpost['section3_middle_initial'];
                    $insert_data['section3_rehire_date'] = empty($formpost['section3_rehire_date']) || $formpost['section3_rehire_date'] == 'N/A' ? null : DateTime::createFromFormat('m-d-Y', $formpost['section3_rehire_date'])->format('Y-m-d H:i:s');
                    $insert_data['section3_document_title'] = $formpost['section3_document_title'];
                    $insert_data['section3_document_number'] = $formpost['section3_document_number'];
                    $insert_data['section3_expiration_date'] = empty($formpost['section3_expiration_date']) || $formpost['section3_expiration_date'] == 'N/A' ? null : DateTime::createFromFormat('m-d-Y', $formpost['section3_expiration_date'])->format('Y-m-d H:i:s');
                    $insert_data['section3_emp_sign'] = $reviewer_signature_base64;
                    $insert_data['section3_today_date'] = empty($formpost['section3_today_date']) || $formpost['section3_today_date'] == 'N/A' ? null : DateTime::createFromFormat('m-d-Y', $formpost['section3_today_date'])->format('Y-m-d H:i:s');
                    $insert_data['section3_name_of_emp'] = $formpost['section3_name_of_emp'];

                    $insert_data['emp_app_sid'] = $employer_sid;
                    $insert_data['employer_flag'] = 1;
                    $insert_data['employer_filled_date'] = date('Y-m-d H:i:s');

                    // Section 2,3 Ends
                }

                // TO be checked and removed
                if (isset($formpost['section1_last_name'])) {
                    $insert_data['section1_last_name'] = $formpost['section1_last_name'];
                    $insert_data['section1_first_name'] = $formpost['section1_first_name'];
                    $insert_data['section1_middle_initial'] = $formpost['section1_middle_initial'];
                    $insert_data['section1_other_last_names'] = $formpost['section1_other_last_names'];
                    $insert_data['section1_address'] = $formpost['section1_address'];
                    $insert_data['section1_apt_number'] = $formpost['section1_apt_number'];
                    $insert_data['section1_city_town'] = $formpost['section1_city_town'];
                    $insert_data['section1_state'] = $formpost['section1_state'];
                    $insert_data['section1_zip_code'] = $formpost['section1_zip_code'];
                    $insert_data['section1_date_of_birth'] = empty($formpost['section1_date_of_birth']) || $formpost['section1_date_of_birth'] == 'N/A' ? null : DateTime::createFromFormat('m-d-Y', $formpost['section1_date_of_birth'])->format('Y-m-d H:i:s');
                    $insert_data['section1_social_security_number'] = $formpost['section1_social_security_number'];
                    $insert_data['section1_emp_email_address'] = $formpost['section1_emp_email_address'];
                    $insert_data['section1_emp_telephone_number'] = $formpost['section1_emp_telephone_number'];
                }



                // Log i9 form
                $i9TrackerData = [];
                $i9TrackerData['data'] = $insert_data;
                $i9TrackerData['loggedIn_person_id'] = $security_sid;
                $i9TrackerData['previous_form_sid'] = $previous_form['emp_app_sid'];
                $i9TrackerData['session_id'] = session_id();
                $i9TrackerData['session_employer_id'] = $data['session']['employer_detail']['sid'];
                $i9TrackerData['session_company_id'] = $data['session']['company_detail']['sid'];
                $i9TrackerData['reviewer_signature_base64'] = $reviewer_signature_base64;
                $i9TrackerData['module'] = $sid ? 'fi9/gp' : 'fi9/bp';
                //
                portalFormI9Tracker($employer_sid, $type, $i9TrackerData);

                //$this->form_wi9_model->insert_form_data('i9', $insert_data, $employer_sid);
                $this->form_wi9_model->update_form('i9', $type, $employer_sid, $insert_data);
                //
                $i9_sid = getVerificationDocumentSid($employer_sid, $type, 'i9');
                keepTrackVerificationDocument($employer_sid, $type, 'completed', $i9_sid, 'i9', 'Blue Panel');
                //
                $this->session->set_flashdata('message', '<strong>Success: </strong> I-9 Submitted Successfully!');
                redirect($redirect_url, 'refresh');
            }
        } else {
            redirect('login', "refresh");
        }
    }

    public function ajax_responder()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $company_sid = $data["session"]["company_detail"]["sid"];
            $employer_sid = $data["session"]["employer_detail"]["sid"];
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim');
            if ($this->form_validation->run() == false) {
                //Handle Get
            } else {
                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'upload_i9_form':
                        $document_sid = $this->input->post('document_sid');
                        $user_type = $this->input->post('user_type');
                        $user_sid = $this->input->post('user_sid');
                        $user_type = empty($user_type) ? null : $user_type;
                        $user_sid = empty($user_sid) ? null : $user_sid;

                        $uploaded_document_original_name = $_FILES['document']['name'];
                        $document_name = 'eev-i9_form-document';

                        $uploaded_document_s3_name = upload_file_to_aws('document', $company_sid, str_replace(' ', '_', $document_name), $employer_sid, AWS_S3_BUCKET_NAME);

                        $data_to_insert = array();
                        $response = array();

                        if ($uploaded_document_s3_name != 'error') {
                            //
                            $already_assigned_i9 = $this->form_wi9_model->check_i9_exist('employee', $user_sid); //Here type will always be employee

                            if (empty($already_assigned_i9)) {
                                $i9_data_to_insert = array();
                                $i9_data_to_insert['user_sid'] = $user_sid;
                                $i9_data_to_insert['user_type'] = $user_type;
                                $i9_data_to_insert['company_sid'] = $company_sid;
                                $i9_data_to_insert['sent_status'] = 1;
                                $i9_data_to_insert['sent_date'] = date('Y-m-d H:i:s');
                                $i9_data_to_insert['status'] = 1;
                                $i9_data_to_insert['s3_filename'] = $uploaded_document_s3_name;
                                $i9_data_to_insert['emp_app_sid'] = $employer_sid;
                                $i9_data_to_insert['employer_flag'] = 1;
                                $i9_data_to_insert['applicant_flag'] = 1;
                                $i9_data_to_insert['applicant_filled_date'] = date('Y-m-d H:i:s');
                                $i9_data_to_insert['employer_filled_date'] = date('Y-m-d H:i:s');
                                $i9_data_to_insert['user_consent'] = 1;
                                $this->form_wi9_model->insert_i9_form_record($i9_data_to_insert);
                            } else {
                                $already_assigned_i9['i9form_ref_sid'] = $already_assigned_i9['sid'];
                                unset($already_assigned_i9['sid']);
                                $this->form_wi9_model->i9_forms_history($already_assigned_i9);
                                $this->form_wi9_model->delete_i9_form($already_assigned_i9['i9form_ref_sid']);
                                $i9_data_to_insert = array();
                                $i9_data_to_insert['s3_filename'] = $uploaded_document_s3_name;
                                $i9_data_to_insert['sid'] = $already_assigned_i9['i9form_ref_sid'];
                                $i9_data_to_insert['user_sid'] = $user_sid;
                                $i9_data_to_insert['user_type'] = $user_type;
                                $i9_data_to_insert['company_sid'] = $company_sid;
                                $i9_data_to_insert['sent_status'] = 1;
                                $i9_data_to_insert['sent_date'] = date('Y-m-d H:i:s');
                                $i9_data_to_insert['status'] = 1;
                                $i9_data_to_insert['emp_app_sid'] = $employer_sid;
                                $i9_data_to_insert['employer_flag'] = 1;
                                $i9_data_to_insert['applicant_flag'] = 1;
                                $i9_data_to_insert['applicant_filled_date'] = date('Y-m-d H:i:s');
                                $i9_data_to_insert['employer_filled_date'] = date('Y-m-d H:i:s');
                                $i9_data_to_insert['user_consent'] = 1;
                                $this->form_wi9_model->insert_i9_form_record($i9_data_to_insert);
                            }


                            $response['Status'] = TRUE;
                            $response['Response'] = 'i9 form upload successfully';
                        } else {
                            $response['Status'] = FALSE;
                            $response['Response'] = 'Something went wrong!';
                        }

                        header('Content-Type: application/json');
                        echo json_encode($response);
                        exit(0);

                        break;
                }
            }
        }
    }

    public function ajax_handler()
    {

        $data['session'] = $this->session->userdata('logged_in');
        $company_sid = $data["session"]["company_detail"]["sid"];
        $employer_sid = $data['session']['employer_detail']['sid'];
        $uploader = $_POST['uploader'];
        $pictures = upload_file_to_aws('docs', $company_sid, 'docs', '', AWS_S3_BUCKET_NAME);
        if (!empty($pictures) && $pictures != 'error') {
            if (isset($_POST['form_id']) && $_POST['form_id'] != 0) {
                $form = $_POST['form_id'];
            } else {
                $insert['company_sid'] = $company_sid;
                $insert['emp_app_sid'] = $_POST['app_id'];
                $form = $this->form_wi9_model->insert_form_data('i9', $insert, $_POST['app_id']);
            }

            $docs = array();
            $last_index_of_dot = strrpos($_FILES["docs"]["name"], '.') + 1;

            $file_ext = substr($_FILES["docs"]["name"], $last_index_of_dot, strlen($_FILES["docs"]["name"]) - $last_index_of_dot);
            if ($uploader == 'applicant') {
                $docs['employer_sid'] = NULL;
                $docs['applicant_sid'] = $_POST['app_id'];
            } else {
                $docs['employer_sid'] = $employer_sid;
                $docs['applicant_sid'] = NULL;
                $docs['verified_date'] = date('Y-m-d H:i:s');
                $docs['verified'] = 1;
            }
            $docs['company_sid'] = $company_sid;
            $docs['type'] = $file_ext;
            $docs['file_code'] = $pictures;
            $docs['form_id'] = $form;
            $docs['upload_date'] = date('Y-m-d H:i:s');
            $docs['file_name'] = $_FILES["docs"]["name"];
            $insert_id = $this->form_wi9_model->insert_form_docs($docs);

            echo $form;
        } else {
            echo 'error';
        }
    }

    public function verify_docs()
    {
        $id = $this->input->post('id');
        $data = array();
        $data['verified'] = 1;
        $data['verified_date'] = date('Y-m-d H:i:s');
        $this->form_wi9_model->mark_as_verified($id, $data);
        echo 'Verified';
    }

    //For employee to preview its own I9 PDF
    public function preview_i9form($user_type, $employee_sid)
    {
        if ($this->session->userdata('logged_in')) {
            //
            $previous_form = $this->form_wi9_model->fetch_form('i9', $user_type, $employee_sid);
            //
            $data['title'] = 'Form i-9';
            $data['pre_form'] = $previous_form;
            $data['section_access'] = "employee_section";
            //
            if (!empty($data["pre_form"]["version"]) && $data["pre_form"]["version"] == "2023") {
                $this->load->view('2022/federal_fillable/form_i9_preview_new', $data);
            } else if (!empty($data["pre_form"]["version"]) && $data["pre_form"]["version"] == "2025") {
                $data['section_access'] = "complete_pdf";
                $this->load->view('v1/forms/i9/2025/form_i9_preview', $data);
            } else {
                $this->load->view('2022/federal_fillable/form_i9_preview', $data);
            }
            //
        } else {
            redirect('login', "refresh");
        }
    }

    //For employee to download its own I9 PDF
    public function download_i9form($user_type, $employee_sid)
    {
        if ($this->session->userdata('logged_in')) {
            //
            $previous_form = $this->form_wi9_model->fetch_form('i9', $user_type, $employee_sid);
            //
            $data['title'] = 'Form i-9';
            $data['pre_form'] = $previous_form;
            $data['section_access'] = "employee_section";
            //
            if (!empty($data["pre_form"]["version"]) && $data["pre_form"]["version"] == "2023") {
                $this->load->view('2022/federal_fillable/form_i9_download_new', $data);
            } else if (!empty($data["pre_form"]["version"]) && $data["pre_form"]["version"] == "2025") {
                $data['section_access'] = "complete_pdf";
                $this->load->view('v1/forms/i9/2025/form_i9_download', $data);
            } else {
                $this->load->view('2022/federal_fillable/form_i9_download', $data);
            }
            //
        } else {
            redirect('login', "refresh");
        }
    }

    //For employer to print an employees I9 PDF
    public function print_i9_form($type, $sid)
    {
        if ($this->session->userdata('logged_in')) {
            //
            $previous_form = $this->form_wi9_model->fetch_form('i9', $type, $sid);
            //
            $data['title'] = 'Form i-9';
            $data['pre_form'] = $previous_form;
            $data['section_access'] = "complete_pdf";
            //
            if (!empty($data["pre_form"]["version"]) && $data["pre_form"]["version"] == "2023") {
                $this->load->view('2022/federal_fillable/form_i9_print_new', $data);
            } else if (!empty($data["pre_form"]["version"]) && $data["pre_form"]["version"] == "2025") {
                $data['section_access'] = "complete_pdf";
                $this->load->view('v1/forms/i9/2025/form_i9_print', $data);
            } else {
                $this->load->view('2022/federal_fillable/form_i9_print', $data);
            }
            //
        } else {
            redirect('login', "refresh");
        }
    }

    //For employer to download an employees I9 PDF
    public function download_i9_form($type, $sid)
    {
        if ($this->session->userdata('logged_in')) {
            //
            $previous_form = $this->form_wi9_model->fetch_form('i9', $type, $sid);
            //
            $data['title'] = 'Form i-9';
            $data['pre_form'] = $previous_form;
            $data['section_access'] = "complete_pdf";
            //
            if (!empty($data["pre_form"]["version"]) && $data["pre_form"]["version"] == "2023") {
                $this->load->view('2022/federal_fillable/form_i9_download_new', $data);
            } else if (!empty($data["pre_form"]["version"]) && $data["pre_form"]["version"] == "2025") {
                $data['section_access'] = "complete_pdf";
                $this->load->view('v1/forms/i9/2025/form_i9_download', $data);
            } else {
                $this->load->view('2022/federal_fillable/form_i9_download', $data);
            }
            //
        } else {
            redirect('login', "refresh");
        }
    }

    public function print_i9_img($document_file)
    {
        if ($this->session->userdata('logged_in')) {
            $session = $this->session->userdata('logged_in');
            $data['session'] = $session;
            $security_sid = $data['session']['employer_detail']['sid'];
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $load_view = check_blue_panel_status(false, 'self');

            $company_name = $session['company_detail']['CompanyName'];
            $employee_name = $session['employer_detail']['first_name'] . ' ' . $session['employer_detail']['last_name'];

            $data['company_name']   = $company_name;
            $data['employee_name']  = $employee_name;
            $data['action_date']    = 'Print Date';
            $data['action_by']      = 'Print By';
            $data['title']          = 'Document Center';


            if ($this->form_validation->run() == false) {
                $document_file = AWS_S3_BUCKET_URL . $document_file;
                $data['original_document_description'] = '<img src="' . $document_file . '" style="width:100%; height:500px;" />';

                $data['download']       = NULL;
                $data['file_name']      = NULL;
                $data['print']          = 'original';
                $data['document_title'] = $document_file;

                $this->load->view('form_i9/print_i9_img', $data);
            }
        } else {
            redirect('login', 'refresh');
        }
    }
}
