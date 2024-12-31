<?php defined('BASEPATH') or exit('No direct script access allowed');

class Form_w4 extends Public_Controller
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

            $data['title'] = 'Form W4';
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
                $data['title'] = 'My W4 Form';

                $reload_location = 'form_w4';
                $type = 'employee';

                $data['employer'] = $employer_details;

                $data['return_title_heading'] = 'My Profile';
                $back_url = base_url('my_profile');
                $form_back_url = base_url('hr_documents_management/my_documents');
                $data['return_title_heading_link'] = $back_url;

                $data['applicant_average_rating'] = $this->form_wi9_model->getApplicantAverageRating($employer_sid, 'employee'); // getting applicant ratings - getting average rating of applicant

                // $e_signature_data = $this->e_signature_model->get_signature_record('employee', $employer_sid);
                $e_signature_data = get_e_signature($company_sid, $employer_sid, 'employee');
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
                $data['title'] = 'Employee / W4 Form';

                $reload_location = 'form_w4/employee/' . $sid;

                $data['employer'] = $this->form_wi9_model->get_employee_information($company_sid, $employer_sid);

                $data['return_title_heading'] = 'Employee Profile';
                $back_url = base_url() . 'employee_profile/' . $sid;
                $form_back_url = base_url() . 'employee_profile/' . $sid;

                $data['return_title_heading_link'] = $back_url;

                $data['applicant_average_rating'] = $this->form_wi9_model->getApplicantAverageRating($sid, 'employee'); // getting applicant ratings - getting average rating of applicant


                $e_signature_data = get_e_signature($company_sid, $employer_sid, 'employee');
                $data['e_signature_data'] = $e_signature_data;

                $load_view = check_blue_panel_status(false, $type);
            } else if ($type == 'applicant') {
                check_access_permissions($security_details, 'application_tracking', 'applicant_form_w4'); // Param2: Redirect URL, Param3: Function Name

                $data = applicant_right_nav($sid);
                $employer_sid = $sid;
                $left_navigation = 'manage_employer/application_tracking_system/profile_right_menu_applicant';
                $data['title'] = 'Applicant / W4 Form';

                $reload_location = 'form_w4/applicant/' . $sid . '/' . $jobs_listing;

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


                $e_signature_data = get_e_signature($company_sid, $employer_sid, 'applicant');
                $data['e_signature_data'] = $e_signature_data;

                $load_view = check_blue_panel_status(false, $type);
            }

            $data['title'] = 'Form W-4';
            $data['employee'] = $data['session']['employer_detail'];

            $field = array(
                'field' => 'w4_first_name',
                'label' => 'First Name',
                'rules' => 'xss_clean|trim|required'
            );
            //
            // $order_field = array(
            //    'field' => 'w4_middle_name',
            //    'label' => 'Middle Name',
            //    'rules' => 'xss_clean|trim|required'
            // );

            $config[] = $field;
            // $config[] = $order_field;

            $this->form_validation->set_error_delimiters('<label class="error">', '</label>');
            $this->form_validation->set_rules($config);

            $previous_form = $this->form_wi9_model->fetch_form('w4', $type, $employer_sid);
            if (empty($previous_form)) {
                $this->session->set_flashdata('message', '<strong>Error: </strong> W4 Form information not found!');
                redirect($form_back_url, 'refresh');
            }

            // prefill form data
            $previous_form = prefillFormData(
                $employer_sid,
                $type,
                'w4',
                $previous_form
            );

            // $e_signature_data = $this->e_signature_model->get_signature_record('employee', $employer_sid);
            // $data['e_signature_data'] = $e_signature_data;

            $data['pre_form'] = $previous_form;
            if (isset($_GET['submit']) && $_GET['submit'] == 'Download PDF') {
                $view = $this->load->view('form_w4/form_w4', $data, TRUE);
                $this->pdfgenerator->generate($view, 'W4 Form', true, 'A4');
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

            // $data['signed_flag'] = false;
            $data['signed_flag'] = isset($previous_form['user_consent']) && $previous_form['user_consent'] == 1 ? true : false;
            //
            $assign_on = date("Y-m-d", strtotime($previous_form['sent_date']));
            $compare_date = date("Y-m-d", strtotime('2020-01-06'));

            $compare_date_2024 = date("Y-m-d", strtotime('2024-01-01'));

            $compare_date_2025 = date("Y-m-d", strtotime('2025-01-01'));

            //
            if ($this->form_validation->run() == FALSE) {
                $this->load->view('main/header', $data);
                if (isset($previous_form['manual'])) $this->load->view('form_w4/index_upload');
                else {

                    if ($assign_on >= $compare_date_2025) {
                        $this->load->view('form_w4/index_ems_2025');
                    } elseif ($assign_on >= $compare_date_2024) {
                        $this->load->view('form_w4/index_ems_2024');
                    } elseif ($assign_on >= $compare_date) {
                        // $this->load->view('form_w4/index_ems_2020');
                        $this->load->view('form_w4/index_ems_2023');
                    } else {
                        $this->load->view('form_w4/index');
                    }
                }

                $this->load->view('main/footer');
            } else {

                $first_name = $this->input->post('w4_first_name');
                $middle_name = $this->input->post('w4_middle_name');
                $last_name = $this->input->post('w4_last_name');
                $ss_number = $this->input->post('ss_number');
                $home_address = $this->input->post('home_address');
                $city = $this->input->post('city');
                $state = $this->input->post('state');
                $zip = $this->input->post('zip');
                $marriage_status = $this->input->post('marriage_status');
                $signature_timestamp = getSystemDate();
                $signature_email_address = $this->input->post('email_address');
                $signature_base64 = $this->input->post('signature_bas64_image');
                $initial_base64 = $this->input->post('init_signature_bas64_image');
                $signature_ip_address = $this->input->post('signature_ip_address');
                $signature_user_agent = $this->input->post('signature_user_agent');
                $emp_name = $this->input->post('emp_name');
                $emp_address = $this->input->post('emp_address');
                $first_date_of_employment = $this->input->post('first_date_of_employment');
                $emp_identification_number = $this->input->post('emp_identification_number');
                $user_consent = $this->input->post('user_consent');

                if (!empty($first_date_of_employment)) {
                    $first_date_of_employment = DateTime::createFromFormat('m-d-Y', $first_date_of_employment)->format('Y-m-d');
                }

                if ($assign_on >= $compare_date) {
                    $mjsw_status            = $this->input->post('mjsw_status');
                    $dependents_children    = $this->input->post('dependents_children');
                    $other_dependents       = $this->input->post('other_dependents');
                    $claim_total_amount     = $this->input->post('claim_total_amount');
                    $other_income           = $this->input->post('other_income');
                    $other_deductions       = $this->input->post('other_deductions');
                    $additional_tax         = $this->input->post('additional_tax');
                    $signature_date         = $this->input->post('signature_date');
                    $mjw_two_jobs           = $this->input->post('mjw_two_jobs');
                    $mjw_three_jobs_a       = $this->input->post('mjw_three_jobs_a');
                    $mjw_three_jobs_b       = $this->input->post('mjw_three_jobs_b');
                    $mjw_three_jobs_c       = $this->input->post('mjw_three_jobs_c');
                    $mjw_pp_py              = $this->input->post('mjw_pp_py');
                    $mjw_divide             = $this->input->post('mjw_divide');
                    $dw_input_1             = $this->input->post('dw_input_1');
                    $dw_input_2             = $this->input->post('dw_input_2');
                    $dw_input_3             = $this->input->post('dw_input_3');
                    $dw_input_4             = $this->input->post('dw_input_4');
                    $dw_input_5             = $this->input->post('dw_input_5');

                    if (!empty($signature_date)) {
                        $signature_timestamp = DateTime::createFromFormat('m-d-Y', $signature_date)->format('Y-m-d') . " " . getSystemDate("H:i:s");
                    }
                } else {
                    $different_last_name = $this->input->post('different_last_name');
                    $number_of_allowance = $this->input->post('number_of_allowance');
                    $additional_amount = $this->input->post('additional_amount');
                    $claim_exempt = $this->input->post('claim_exempt');
                    $paw_yourself = $this->input->post('paw_yourself');
                    $paw_married = $this->input->post('paw_married');
                    $paw_head = $this->input->post('paw_head');
                    $paw_single_wages = $this->input->post('paw_single_wages');
                    $paw_child_tax = $this->input->post('paw_child_tax');
                    $paw_dependents = $this->input->post('paw_dependents');
                    $paw_other_credit = $this->input->post('paw_other_credit');
                    $paw_accuracy = $this->input->post('paw_accuracy');
                    $daaiw_estimate = $this->input->post('daaiw_estimate');
                    $daaiw_enter_status = $this->input->post('daaiw_enter_status');
                    $daaiw_subtract_line_2 = $this->input->post('daaiw_subtract_line_2');
                    $daaiw_estimate_of_adjustment = $this->input->post('daaiw_estimate_of_adjustment');
                    $daaiw_add_line_3_4 = $this->input->post('daaiw_add_line_3_4');
                    $daaiw_estimate__of_nonwage = $this->input->post('daaiw_estimate__of_nonwage');
                    $daaiw_subtract_line_6 = $this->input->post('daaiw_subtract_line_6');
                    $daaiw_divide_line_7 = $this->input->post('daaiw_divide_line_7');
                    $daaiw_enter_number_personal_allowance = $this->input->post('daaiw_enter_number_personal_allowance');
                    $daaiw_add_line_8_9 = $this->input->post('daaiw_add_line_8_9');
                    $temjw_personal_allowance = $this->input->post('temjw_personal_allowance');
                    $temjw_num_in_table_1 = $this->input->post('temjw_num_in_table_1');
                    $temjw_more_line2 = $this->input->post('temjw_more_line2');
                    $temjw_num_from_line2 = $this->input->post('temjw_num_from_line2');
                    $temjw_num_from_line1 = $this->input->post('temjw_num_from_line1');
                    $temjw_subtract_5_from_4 = $this->input->post('temjw_subtract_5_from_4');
                    $temjw_amount_in_table_2 = $this->input->post('temjw_amount_in_table_2');
                    $temjw_multiply_7_by_6 = $this->input->post('temjw_multiply_7_by_6');
                    $temjw_divide_8_by_period = $this->input->post('temjw_divide_8_by_period');
                }

                $data_to_update = array();
                $data_to_update['first_name'] = $first_name;
                $data_to_update['middle_name'] = $middle_name;
                $data_to_update['last_name'] = $last_name;
                $data_to_update['ss_number'] = $ss_number;
                $data_to_update['home_address'] = $home_address;
                $data_to_update['city'] = $city;
                $data_to_update['state'] = $state;
                $data_to_update['zip'] = $zip;
                $data_to_update['marriage_status'] = $marriage_status;
                $data_to_update['signature_timestamp'] = $signature_timestamp;
                $data_to_update['signature_email_address'] = $signature_email_address;
                $data_to_update['signature_bas64_image'] = $signature_base64;
                $data_to_update['init_signature_bas64_image'] = $initial_base64;
                $data_to_update['ip_address'] = $signature_ip_address;
                $data_to_update['user_agent'] = $signature_user_agent;
                $data_to_update['emp_name'] = $emp_name;
                $data_to_update['emp_address'] = $emp_address;
                $data_to_update['first_date_of_employment'] = $first_date_of_employment;
                $data_to_update['emp_identification_number'] = $emp_identification_number;
                $data_to_update['user_consent'] = $user_consent;
                $data_to_update['company_sid'] = $company_sid;
                $data_to_update['employer_sid'] = $employer_sid;

                if ($assign_on >= $compare_date) {
                    $data_to_update['mjsw_status'] = $mjsw_status;
                    $data_to_update['dependents_children'] = $dependents_children;
                    $data_to_update['other_dependents'] = $other_dependents;
                    $data_to_update['claim_total_amount'] = $claim_total_amount;
                    $data_to_update['other_income'] = $other_income;
                    $data_to_update['other_deductions'] = $other_deductions;
                    $data_to_update['additional_tax'] = $additional_tax;
                    $data_to_update['mjw_two_jobs'] = $mjw_two_jobs;
                    $data_to_update['mjw_three_jobs_a'] = $mjw_three_jobs_a;
                    $data_to_update['mjw_three_jobs_b'] = $mjw_three_jobs_b;
                    $data_to_update['mjw_three_jobs_c'] = $mjw_three_jobs_c;
                    $data_to_update['mjw_pp_py'] = $mjw_pp_py;
                    $data_to_update['mjw_divide'] = $mjw_divide;
                    $data_to_update['dw_input_1'] = $dw_input_1;
                    $data_to_update['dw_input_2'] = $dw_input_2;
                    $data_to_update['dw_input_3'] = $dw_input_3;
                    $data_to_update['dw_input_4'] = $dw_input_4;
                    $data_to_update['dw_input_5'] = $dw_input_5;
                } else {
                    $data_to_update['different_last_name'] = $different_last_name;
                    $data_to_update['number_of_allowance'] = $number_of_allowance;
                    $data_to_update['additional_amount'] = $additional_amount;
                    $data_to_update['claim_exempt'] = $claim_exempt;
                    $data_to_update['paw_yourself'] = $paw_yourself;
                    $data_to_update['paw_married'] = $paw_married;
                    $data_to_update['paw_head'] = $paw_head;
                    $data_to_update['paw_single_wages'] = $paw_single_wages;
                    $data_to_update['paw_child_tax'] = $paw_child_tax;
                    $data_to_update['paw_dependents'] = $paw_dependents;
                    $data_to_update['paw_other_credit'] = $paw_other_credit;
                    $data_to_update['paw_accuracy'] = $paw_accuracy;
                    $data_to_update['daaiw_estimate'] = $daaiw_estimate;
                    $data_to_update['daaiw_enter_status'] = $daaiw_enter_status;
                    $data_to_update['daaiw_subtract_line_2'] = $daaiw_subtract_line_2;
                    $data_to_update['daaiw_estimate_of_adjustment'] = $daaiw_estimate_of_adjustment;
                    $data_to_update['daaiw_add_line_3_4'] = $daaiw_add_line_3_4;
                    $data_to_update['daaiw_estimate__of_nonwage'] = $daaiw_estimate__of_nonwage;
                    $data_to_update['daaiw_subtract_line_6'] = $daaiw_subtract_line_6;
                    $data_to_update['daaiw_divide_line_7'] = $daaiw_divide_line_7;
                    $data_to_update['daaiw_enter_number_personal_allowance'] = $daaiw_enter_number_personal_allowance;
                    $data_to_update['daaiw_add_line_8_9'] = $daaiw_add_line_8_9;
                    $data_to_update['temjw_personal_allowance'] = $temjw_personal_allowance;
                    $data_to_update['temjw_num_in_table_1'] = $temjw_num_in_table_1;
                    $data_to_update['temjw_more_line2'] = $temjw_more_line2;
                    $data_to_update['temjw_num_from_line2'] = $temjw_num_from_line2;
                    $data_to_update['temjw_num_from_line1'] = $temjw_num_from_line1;
                    $data_to_update['temjw_subtract_5_from_4'] = $temjw_subtract_5_from_4;
                    $data_to_update['temjw_amount_in_table_2'] = $temjw_amount_in_table_2;
                    $data_to_update['temjw_multiply_7_by_6'] = $temjw_multiply_7_by_6;
                    $data_to_update['temjw_divide_8_by_period'] = $temjw_divide_8_by_period;
                }
                //
                $this->load->model('2022/User_model', 'em');

                $this->em->handleGeneralDocumentChange(
                    'w4',
                    $this->input->post(null, true),
                    '',
                    $this->input->post('user_sid'),
                    $this->session->userdata('logged_in')['employer_detail']['sid']
                );
                //
                $this->form_wi9_model->update_form('w4', $type, $employer_sid, $data_to_update);
                //
                $w4_sid = getVerificationDocumentSid($employer_sid, $type, 'w4');
                keepTrackVerificationDocument($employer_sid, $type, 'completed', $w4_sid, 'w4', 'Blue Panel');
                //
                if ($type != 'applicant' && $this->input->post('user_consent') == 1) {
                    // Send document completion alert
                    broadcastAlert(
                        DOCUMENT_NOTIFICATION_ACTION_TEMPLATE,
                        'documents_status',
                        'w4_completed',
                        $company_sid,
                        $data['session']['company_detail']['CompanyName'],
                        $data['first_name'],
                        $data['last_name'],
                        $data['users_sid'],
                        [],
                        $type
                    );
                }

                // check for payroll
                if ($type === 'employee' && checkIfAppIsEnabled(PAYROLL) && isEmployeeOnPayroll($employer_sid)) {
                    // load payroll model
                    $this->load->model('v1/Documents_management_model', 'documents_management_model');
                    //
                    $this->documents_management_model->checkAndSignDocument('w4', $employer_sid);
                    // load payroll model
                    $this->load->model('v1/Payroll/W4_payroll_model', 'W4_payroll_model');
                    //
                    $this->W4_payroll_model->syncW4ForEmployee($employer_sid);
                }

                $this->session->set_flashdata('message', '<strong>Success: </strong> W4 Form Submitted Successfully!');
                redirect('form_w4', 'refresh');
            }
        } else {
            redirect('login', "refresh");
        }
    }

    public function admin_reply($type = null, $sid = null, $jobs_listing = null)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $employer_sid = $data['session']['employer_detail']['sid'];

            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;

            $data['title'] = 'Form W4';
            $data['employee'] = $data['session']['employer_detail'];

            $company_sid = $data['session']['company_detail']['sid'];

            $employer_access_level = $data['session']['employer_detail']['access_level'];
            $employer_details = $data['session']['employer_detail'];

            if ($type == 'employee') {
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
                $data['title'] = 'Employee / W4 Form';

                $reload_location = 'form_w4/employee/' . $sid;

                $data['employer'] = $this->form_wi9_model->get_employee_information($company_sid, $employer_sid);

                $data['return_title_heading'] = 'Employee Profile';
                $back_url = base_url() . 'employee_profile/' . $sid;
                $form_back_url = base_url() . 'employee_profile/' . $sid;

                $data['return_title_heading_link'] = $back_url;

                $data['applicant_average_rating'] = $this->form_wi9_model->getApplicantAverageRating($sid, 'employee'); // getting applicant ratings - getting average rating of applicant


                $e_signature_data = get_e_signature($company_sid, $employer_sid, 'employee');
                $data['e_signature_data'] = $e_signature_data;

                $load_view = check_blue_panel_status(false, $type);
            } else if ($type == 'applicant') {
                check_access_permissions($security_details, 'application_tracking', 'applicant_form_w4'); // Param2: Redirect URL, Param3: Function Name

                $data = applicant_right_nav($sid);
                $employer_sid = $sid;
                $left_navigation = 'manage_employer/application_tracking_system/profile_right_menu_applicant';
                $data['title'] = 'Applicant / W4 Form';

                $reload_location = 'form_w4/applicant/' . $sid . '/' . $jobs_listing;

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


                $e_signature_data = get_e_signature($company_sid, $employer_sid, 'applicant');
                $data['e_signature_data'] = $e_signature_data;

                $load_view = check_blue_panel_status(false, $type);
            }

            $data['title'] = 'Form W-4';
            $data['employee'] = $data['session']['employer_detail'];

            $field = array(
                'field' => 'w4_first_name',
                'label' => 'First Name',
                'rules' => 'xss_clean|trim|required'
            );
            $order_field = array(
                'field' => 'w4_middle_name',
                'label' => 'Middle Name',
                'rules' => 'xss_clean|trim|required'
            );

            $config[] = $field;
            $config[] = $order_field;

            $this->form_validation->set_error_delimiters('<label class="error">', '</label>');
            $this->form_validation->set_rules($config);

            $previous_form = $this->form_wi9_model->fetch_form('w4', $type, $employer_sid);
            if (empty($previous_form)) {
                $this->session->set_flashdata('message', '<strong>Error: </strong> W4 Form information not found!');
                redirect($form_back_url, 'refresh');
            }

            //            $e_signature_data = $this->e_signature_model->get_signature_record('employee', $employer_sid);
            //            $data['e_signature_data'] = $e_signature_data;

            $data['pre_form'] = $previous_form;
            $view = $this->load->view('form_w4/test_form_w4', $data, TRUE);
            $data['view'] = $view;
            if (isset($_GET['submit']) && $_GET['submit'] == 'Download PDF') {
                //                print_r($this->load->view('form_w4/form_w4', $data, TRUE));
                //                die();
                $this->pdfgenerator->generate($view, 'W4 Form', true, 'A4');
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

            //            $data['signed_flag'] = false;
            $data['signed_flag'] = isset($previous_form['user_consent']) && $previous_form['user_consent'] == 1 ? true : false;

            if ($this->form_validation->run() == FALSE) {
                $this->load->view('main/header', $data);
                $this->load->view('form_w4/admin_reply_view');
                $this->load->view('main/footer');
            } else {

                $first_name = $this->input->post('w4_first_name');
                $middle_name = $this->input->post('w4_middle_name');
                $last_name = $this->input->post('w4_last_name');
                $ss_number = $this->input->post('ss_number');
                $home_address = $this->input->post('home_address');
                $city = $this->input->post('city');
                $state = $this->input->post('state');
                $zip = $this->input->post('zip');
                $marriage_status = $this->input->post('marriage_status');
                $different_last_name = $this->input->post('different_last_name');
                $number_of_allowance = $this->input->post('number_of_allowance');
                $additional_amount = $this->input->post('additional_amount');
                $claim_exempt = $this->input->post('claim_exempt');
                $signature_timestamp = date('Y-m-d H:i:s');
                $signature_email_address = $this->input->post('email_address');
                $signature_base64 = $this->input->post('signature_bas64_image');
                $initial_base64 = $this->input->post('init_signature_bas64_image');
                $signature_ip_address = $this->input->post('signature_ip_address');
                $signature_user_agent = $this->input->post('signature_user_agent');
                $emp_name = $this->input->post('emp_name');
                $emp_address = $this->input->post('emp_address');
                $first_date_of_employment = $this->input->post('first_date_of_employment');
                $emp_identification_number = $this->input->post('emp_identification_number');
                $paw_yourself = $this->input->post('paw_yourself');
                $paw_married = $this->input->post('paw_married');
                $paw_head = $this->input->post('paw_head');
                $paw_single_wages = $this->input->post('paw_single_wages');
                $paw_child_tax = $this->input->post('paw_child_tax');
                $paw_dependents = $this->input->post('paw_dependents');
                $paw_other_credit = $this->input->post('paw_other_credit');
                $paw_accuracy = $this->input->post('paw_accuracy');
                $daaiw_estimate = $this->input->post('daaiw_estimate');
                $daaiw_enter_status = $this->input->post('daaiw_enter_status');
                $daaiw_subtract_line_2 = $this->input->post('daaiw_subtract_line_2');
                $daaiw_estimate_of_adjustment = $this->input->post('daaiw_estimate_of_adjustment');
                $daaiw_add_line_3_4 = $this->input->post('daaiw_add_line_3_4');
                $daaiw_estimate__of_nonwage = $this->input->post('daaiw_estimate__of_nonwage');
                $daaiw_subtract_line_6 = $this->input->post('daaiw_subtract_line_6');
                $daaiw_divide_line_7 = $this->input->post('daaiw_divide_line_7');
                $daaiw_enter_number_personal_allowance = $this->input->post('daaiw_enter_number_personal_allowance');
                $daaiw_add_line_8_9 = $this->input->post('daaiw_add_line_8_9');
                $temjw_personal_allowance = $this->input->post('temjw_personal_allowance');
                $temjw_num_in_table_1 = $this->input->post('temjw_num_in_table_1');
                $temjw_more_line2 = $this->input->post('temjw_more_line2');
                $temjw_num_from_line2 = $this->input->post('temjw_num_from_line2');
                $temjw_num_from_line1 = $this->input->post('temjw_num_from_line1');
                $temjw_subtract_5_from_4 = $this->input->post('temjw_subtract_5_from_4');
                $temjw_amount_in_table_2 = $this->input->post('temjw_amount_in_table_2');
                $temjw_multiply_7_by_6 = $this->input->post('temjw_multiply_7_by_6');
                $temjw_divide_8_by_period = $this->input->post('temjw_divide_8_by_period');
                $user_consent = $this->input->post('user_consent');
                $first_date_of_employment = date("Y-m-d", strtotime($first_date_of_employment));


                $data_to_update = array();
                $data_to_update['first_name'] = $first_name;
                $data_to_update['middle_name'] = $middle_name;
                $data_to_update['last_name'] = $last_name;
                $data_to_update['ss_number'] = $ss_number;
                $data_to_update['home_address'] = $home_address;
                $data_to_update['city'] = $city;
                $data_to_update['state'] = $state;
                $data_to_update['zip'] = $zip;
                $data_to_update['marriage_status'] = $marriage_status;
                $data_to_update['different_last_name'] = $different_last_name;
                $data_to_update['number_of_allowance'] = $number_of_allowance;
                $data_to_update['additional_amount'] = empty($additional_amount) ? 0 : $additional_amount;
                $data_to_update['claim_exempt'] = $claim_exempt;
                $data_to_update['signature_timestamp'] = $signature_timestamp;
                $data_to_update['signature_email_address'] = $signature_email_address;
                $data_to_update['signature_bas64_image'] = $signature_base64;
                $data_to_update['init_signature_bas64_image'] = $initial_base64;
                $data_to_update['ip_address'] = $signature_ip_address;
                $data_to_update['user_agent'] = $signature_user_agent;
                $data_to_update['emp_name'] = $emp_name;
                $data_to_update['emp_address'] = $emp_address;
                $data_to_update['first_date_of_employment'] = $first_date_of_employment;
                $data_to_update['emp_identification_number'] = $emp_identification_number;
                $data_to_update['paw_yourself'] = $paw_yourself;
                $data_to_update['paw_married'] = $paw_married;
                $data_to_update['paw_head'] = $paw_head;
                $data_to_update['paw_single_wages'] = $paw_single_wages;
                $data_to_update['paw_child_tax'] = $paw_child_tax;
                $data_to_update['paw_dependents'] = $paw_dependents;
                $data_to_update['paw_other_credit'] = $paw_other_credit;
                $data_to_update['paw_accuracy'] = $paw_accuracy;
                $data_to_update['daaiw_estimate'] = $daaiw_estimate;
                $data_to_update['daaiw_enter_status'] = $daaiw_enter_status;
                $data_to_update['daaiw_subtract_line_2'] = $daaiw_subtract_line_2;
                $data_to_update['daaiw_estimate_of_adjustment'] = $daaiw_estimate_of_adjustment;
                $data_to_update['daaiw_add_line_3_4'] = $daaiw_add_line_3_4;
                $data_to_update['daaiw_estimate__of_nonwage'] = $daaiw_estimate__of_nonwage;
                $data_to_update['daaiw_subtract_line_6'] = $daaiw_subtract_line_6;
                $data_to_update['daaiw_divide_line_7'] = $daaiw_divide_line_7;
                $data_to_update['daaiw_enter_number_personal_allowance'] = $daaiw_enter_number_personal_allowance;
                $data_to_update['daaiw_add_line_8_9'] = $daaiw_add_line_8_9;
                $data_to_update['temjw_personal_allowance'] = $temjw_personal_allowance;
                $data_to_update['temjw_num_in_table_1'] = $temjw_num_in_table_1;
                $data_to_update['temjw_more_line2'] = $temjw_more_line2;
                $data_to_update['temjw_num_from_line2'] = $temjw_num_from_line2;
                $data_to_update['temjw_num_from_line1'] = $temjw_num_from_line1;
                $data_to_update['temjw_subtract_5_from_4'] = $temjw_subtract_5_from_4;
                $data_to_update['temjw_amount_in_table_2'] = $temjw_amount_in_table_2;
                $data_to_update['temjw_multiply_7_by_6'] = $temjw_multiply_7_by_6;
                $data_to_update['temjw_divide_8_by_period'] = $temjw_divide_8_by_period;
                $data_to_update['company_sid'] = $company_sid;
                $data_to_update['employer_sid'] = $employer_sid;
                $data_to_update['user_consent'] = $user_consent;

                $this->form_wi9_model->update_form('w4', $type, $employer_sid, $data_to_update);

                $this->session->set_flashdata('message', '<strong>Success: </strong> W4 Form Submitted Successfully!');
                redirect('form_w4', 'refresh');
            }
        } else {
            redirect('login', "refresh");
        }
    }

    public function upload_signed_form()
    {
        $user_sid = $this->input->post('user_sid');
        $current_url = $this->input->post('current_url');
        $sid = $this->input->post('form_sid');

        $data['session'] = $this->session->userdata('logged_in');
        $employer_sid = $data['session']['employer_detail']['sid'];
        $company_sid = $data['session']['company_detail']['sid'];

        if ($_SERVER['HTTP_HOST'] == 'localhost') {
            // $aws_file_name = '0003-d_6-1542874444-39O.jpg';
            // $aws_file_name = '0057-testing_uploaded_doc-58-AAH.docx';
            $aws_file_name = '0057-test_latest_uploaded_document-58-Yo2.pdf';
        } else {
            $aws_file_name = upload_file_to_aws('upload_file', $company_sid, 'W4form_' . $user_sid, time());
        }

        $uploaded_file = '';

        if ($aws_file_name != 'error') {
            $uploaded_file = $aws_file_name;
        } else {
            $this->session->set_flashdata('message', '<b>Error:</b> There is some problem in uploading form.');
            redirect($current_url, 'refresh');
        }

        $upload_form_array = array();
        $upload_form_array['user_consent'] = 1;
        $upload_form_array['uploaded_by_sid'] = $employer_sid;
        $upload_form_array['uploaded_file'] = $uploaded_file;
        $upload_form_array['signature_timestamp'] = date('Y-m-d H:i:s');

        $this->form_wi9_model->do_manual_upload_form($sid, $upload_form_array);
        $this->session->set_flashdata('message', '<b>Success:</b> W4 Form Uploaded Successfully.');
        redirect($current_url, 'refresh');
    }

    public function preview_w4form($type, $sid)
    {
        if ($this->session->userdata('logged_in')) {
            $data['title'] = 'Form W-4';

            $previous_form = $this->form_wi9_model->fetch_form('w4', $type, $sid);
            $data['pre_form'] = $previous_form;

            if ($this->form_validation->run() == FALSE) {
                $this->load->view('form_w4/index-pdf', $data);
            }
        } else {
            redirect('login', "refresh");
        }
    }

    public function download_w4_form($type, $sid)
    {
        if ($this->session->userdata('logged_in')) {
            $data['title'] = 'Form W-4';

            $previous_form = $this->form_wi9_model->fetch_form('w4', $type, $sid);
            $data['pre_form'] = $previous_form;

            $this->load->view('form_w4/download_w4_form', $data);
        } else {
            redirect('login', "refresh");
        }
    }

    public function print_w4_form($type, $sid)
    {
        if ($this->session->userdata('logged_in')) {
            $data['title'] = 'Form W-4';

            $previous_form = $this->form_wi9_model->fetch_form('w4', $type, $sid);
            $data['pre_form'] = $previous_form;

            $this->load->view('form_w4/print_w4_form', $data);
        } else {
            redirect('login', "refresh");
        }
    }

    public function print_w4_form_2020($type, $sid)
    {

        if ($this->session->userdata('logged_in')) {
            $data['title'] = 'Form W-4';

            $previous_form = $this->form_wi9_model->fetch_form('w4', $type, $sid);

            $assign_on = date("Y-m-d", strtotime($previous_form['sent_date']));
            $compare_date_2024 = date("Y-m-d", strtotime('2024-01-01'));
            $data['pre_form'] = $previous_form;

            $compare_date_2025 = date("Y-m-d", strtotime('2025-01-01'));


            if ($assign_on >= $compare_date_2025) {
                $this->load->view('form_w4/print_w4_2025', $data);
            } elseif ($assign_on >= $compare_date_2024) {
                $this->load->view('form_w4/print_w4_2024', $data);
            } else {
                $this->load->view('form_w4/print_w4_2023', $data);
            }
        } else {
            redirect('login', "refresh");
        }
    }

    public function download_w4_form_2020($type, $sid)
    {
        if ($this->session->userdata('logged_in')) {
            $data['title'] = 'Form W-4';

            $previous_form = $this->form_wi9_model->fetch_form('w4', $type, $sid);
            $data['pre_form'] = $previous_form;


            $previous_form = $this->form_wi9_model->fetch_form('w4', $type, $sid);

            $assign_on = date("Y-m-d", strtotime($previous_form['sent_date']));
            $compare_date_2024 = date("Y-m-d", strtotime('2024-01-01'));
            $data['pre_form'] = $previous_form;

            $compare_date_2025 = date("Y-m-d", strtotime('2025-01-01'));

            if ($assign_on >= $compare_date_2025) {
                $this->load->view('form_w4/download_w4_2025', $data);
            }elseif ($assign_on >= $compare_date_2024) {
                $this->load->view('form_w4/download_w4_2024', $data);
            } else {

                $this->load->view('form_w4/download_w4_2023', $data);
            }
        } else {
            redirect('login', "refresh");
        }
    }
}
