<?php

use GraphQL\Error\FormattedError;

defined('BASEPATH') or exit('No direct script access allowed');





class employers extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/company_model');
        $this->load->model('hr_documents_management_model');
        $this->load->library("pagination");
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    public function index($keyword = null, $status = 'all', $company = null, $contact_name = null, $page_number = 1)
    {
        $redirect_url = 'manage_admin';
        $function_name = 'list_employers';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->data['page_title'] = 'Manage Employers';
        $records_per_page = 100;
        $my_offset = 0;

        if ($page_number > 1) {
            $my_offset = ($page_number - 1) * $records_per_page;
        }

        $keyword = $keyword == null ? 'all' : trim(urldecode($keyword));
        $company = $company == null ? 'all' : trim(urldecode($company));
        $contact_name = $contact_name == null ? 'all' : trim(urldecode($contact_name));
        $status = $status == null ? 'all' : $status;
        $employers_count = $this->company_model->get_all_employers_new($records_per_page, $my_offset, $keyword, $status, true, $company, $contact_name);
        $employers = $this->company_model->get_all_employers_new($records_per_page, $my_offset, $keyword, $status, false, $company, $contact_name);
        // echo "<pre>"; print_r($employers); die();
        $config = array();
        $config['base_url'] = base_url('manage_admin/employers/' . rawurlencode($keyword) . '/' . $status . '/' . rawurlencode($company) . '/' . rawurlencode($contact_name) . '/');
        $config['per_page'] = $records_per_page;
        $config['uri_segment'] = 7;
        $config['total_rows'] = $employers_count;
        $config['num_links'] = 5;
        $config['use_page_numbers'] = true;
        //pagination style
        $config['full_tag_open'] = '<nav class="hr-pagination"><ul>';
        $config['full_tag_close'] = '</ul></nav><!--pagination-->';
        $config['first_link'] = '&laquo;';
        $config['first_tag_open'] = '<li class="prev page">';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = '&raquo;';
        $config['last_tag_open'] = '<li class="next page">';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = '<i class="fa fa-angle-right"></i>';
        $config['next_tag_open'] = '<li class="next page">';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = '<i class="fa fa-angle-left"></i>';
        $config['prev_tag_open'] = '<li class="prev page">';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="javascript:;">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page">';
        $config['num_tag_close'] = '</li>';
        $this->pagination->initialize($config);
        $this->data['links'] = $this->pagination->create_links();
        //        $total_employers = $this->company_model->count_all_employers();

        // print_r($employers);
        // die();
        $this->data['employers'] = $employers;
        $this->data['total_employers'] = $employers_count;
        $this->data['total_rows'] = $employers_count;
        $this->data['offset'] = $my_offset == 0 ? 1 : $my_offset;
        $this->data['flag'] = true;
        $this->data['end_offset'] = $my_offset == 0 ? $records_per_page > $employers_count ? $employers_count : $records_per_page : $my_offset + $records_per_page;
        $this->form_validation->set_rules('action', 'action', 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->render('manage_admin/company/listing_view_employers', 'admin_master');
        } else {
            if (isset($_POST['execute']) && $_POST['execute'] == 'multiple_action') {
                $form_type = $_POST['type'];
                $form_action = $_POST['action'];
                $form_rows = $_POST['checkit'];
                $this->company_model->perform_multiple_action($form_type, $form_action, $form_rows);
            }

            redirect('manage_admin/employers/' . $keyword . '/' . $status . '/' . $company . '/' . $contact_name . '/', $page_number, 'refresh');
        }
    }

    private function index_old()
    {
        $redirect_url = 'manage_admin';
        $function_name = 'list_employers';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name

        if (isset($_POST['execute']) && $_POST['execute'] == 'multiple_action') {
            $form_type = $_POST['type'];
            $form_action = $_POST['action'];
            $form_rows = $_POST['checkit'];
            $this->company_model->perform_multiple_action($form_type, $form_action, $form_rows);
        }
        //--------------------Search section Start---------------//
        // get start and end from get
        $search = array();
        $search_data = $this->input->get(NULL, True);
        $this->data['search'] = $search_data;
        $this->data['flag'] = false;

        foreach ($search_data as $key => $value) {
            if ($key != 'start' && $key != 'end') {
                if ($value != '') { // exclude these values from array
                    $search[$key] = $value;
                    $this->data["flag"] = true;
                }
            }
        }

        // calculate between from the start and get
        //        if (isset($search_data['start']) && $search_data['start'] != "" && isset($search_data['end']) && $search_data['end'] != "") {
        //            $start_date = explode('-', $search_data['start']);
        //            $start_date = $start_date[2] . '-' . $start_date[0] . '-' . $start_date[1] . ' 00:00:00';
        //            $end_date = explode('-', $search_data['end']);
        //            $end_date = $end_date[2] . '-' . $end_date[0] . '-' . $end_date[1] . ' 00:00:00';

        if (isset($search_data['start']) || isset($search_data['end'])) {
            if (isset($search_data['start']) && $search_data['start'] != "") {
                $start_date = explode('-', $search_data['start']);
                $start_date = $start_date[2] . '-' . $start_date[0] . '-' . $start_date[1] . ' 00:00:00';
            } else {
                $start_date = '01-01-1970 00:00:00';
            }

            if (isset($search_data['end']) && $search_data['end'] != "") {
                $end_date = explode('-', $search_data['end']);
                $end_date = $end_date[2] . '-' . $end_date[0] . '-' . $end_date[1] . ' 23:59:59';
            } else {
                $end_date = date('Y-m-d H:i:s');
            }

            $between = "registration_date between '" . $start_date . "' and '" . $end_date . "'";
        }
        //--------------------Search section End---------------//
        //Pagination
        $config = array();
        $config['base_url'] = base_url() . "manage_admin/employers/";

        // gets the number of records in both search and main
        //if (isset($search_data['start']) && $search_data['start'] != "" && isset($search_data['end']) && $search_data['end'] != "") {
        if (isset($search_data['start']) || isset($search_data['end'])) {
            $config['total_rows'] = $this->company_model->total_employers_date($search, $between);
        } else {
            $config['total_rows'] = $this->company_model->total_employers($search);
        }

        // set some config properties and initilize pagination
        $config['per_page'] = 100;

        if (isset($post['per_page']) && !empty($post['per_page'])) {
            $config['per_page'] = $post['per_page'];
        }

        $config['uri_segment'] = 3;
        $choice = $config["total_rows"] / $config['per_page'];
        $config['num_links'] = ceil($choice); //5;
        $config['use_page_numbers'] = true;

        //pagination style
        $config['full_tag_open'] = '<nav class="hr-pagination"><ul>';
        $config['full_tag_close'] = '</ul></nav><!--pagination-->';
        $config['first_link'] = '&laquo;';
        $config['first_tag_open'] = '<li class="prev page">';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = '&raquo;';
        $config['last_tag_open'] = '<li class="next page">';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = '<i class="fa fa-angle-right"></i>';
        $config['next_tag_open'] = '<li class="next page">';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = '<i class="fa fa-angle-left"></i>';
        $config['prev_tag_open'] = '<li class="prev page">';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="javascript:;">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page">';
        $config['num_tag_close'] = '</li>';

        $search_sid = '';
        $search_username = '';
        $search_email = '';
        $search_CompanyName = '';
        $search_start = '';
        $search_end = '';

        if (isset($_GET['sid'])) {
            $search_sid = '?sid=' . $_GET['sid'];
            $search_username = '&username=' . $_GET['username'];
            $search_email = '&email=' . $_GET['email'];
            $search_CompanyName = '&CompanyName=' . $_GET['CompanyName'];
            $search_start = '&start=' . $_GET['start'];
            $search_end = '&end=' . $_GET['end'];
        }

        $config['suffix'] = $search_sid . $search_username . $search_email . $search_CompanyName . $search_start . $search_end;
        $config['first_url'] = base_url('manage_admin/employers/1') . $search_sid . $search_username . $search_email . $search_CompanyName . $search_start . $search_end;
        $config['last_url'] = base_url('manage_admin/employers/1') . $search_sid . $search_username . $search_email . $search_CompanyName . $search_start . $search_end;
        $config['prev_url'] = base_url('manage_admin/employers/1') . $search_sid . $search_username . $search_email . $search_CompanyName . $search_start . $search_end;
        $config['next_url'] = base_url('manage_admin/employers/1') . $search_sid . $search_username . $search_email . $search_CompanyName . $search_start . $search_end;

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;
        $my_offset = 0;

        if ($page > 1) {
            $my_offset = ($page - 1) * $config["per_page"];
        }

        $this->data['links'] = $this->pagination->create_links();
        $this->data['page_title'] = 'Manage Employers';
        $this->data['per_page'] = $config["per_page"];
        $this->data['total_rows'] = $config["total_rows"];
        $this->data['offset'] = $my_offset;

        // gets all records displayed, both for search and main
        $employersArray = array();
        //if (isset($search_data['start']) && $search_data['start'] != "" && isset($search_data['end']) && $search_data['end'] != "") {
        if (isset($search_data['start']) || isset($search_data['end'])) {
            $employerData = $this->company_model->get_all_employers_date($config["per_page"], $my_offset, $search, $between);
            $i = 0;

            foreach ($employerData as $employer) {
                $companyData = $this->company_model->get_details($employer['parent_sid'], 'company');

                if (isset($companyData[0]['CompanyName'])) {
                    $employer['CompanyName'] = $companyData[0]['CompanyName'];
                } else {
                    $employer['CompanyName'] = '';
                }

                $employersArray[$i] = $employer;
                $i++;
            }

            $this->data['employers'] = $employersArray;
            $this->data['total'] = $this->company_model->total_employers_date($search, $between);
        } else {
            $employerData = $this->data['employers'] = $this->company_model->get_all_employers($config["per_page"], $my_offset, $search);
            $i = 0;

            foreach ($employerData as $employer) {
                $companyData = $this->company_model->get_details($employer['parent_sid'], 'company');
                $employer['CompanyName'] = 'Not Found!';

                if (!empty($companyData)) {
                    $employer['CompanyName'] = $companyData[0]['CompanyName'];
                }

                $employersArray[$i] = $employer;
                $i++;
            }

            $this->data['employers'] = $employersArray;
            $this->data['total'] = $this->company_model->total_employers($search);
        }

        // gets company data from users, nothing picked
        //if (isset($search_data['start']) && $search_data['start'] != "" && isset($search_data['end']) && $search_data['end'] != "") {
        if (isset($search_data['start']) || isset($search_data['end'])) {
            $this->data['companies'] = $this->company_model->get_all_companies_date($config["per_page"], $my_offset, $search, $between);
        } else {
            $this->data['companies'] = $this->company_model->get_all_companies($config["per_page"], $my_offset, $search);
        }

        $this->render('manage_admin/company/listing_view_employers', 'admin_master');
    }

    public function edit_employer($sid = NULL)
    {
        $redirect_url = 'manage_admin';
        $function_name = 'edit_employers';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // set employment date
        $this->load->model('employee_model');

        $employer_detail = $this->company_model->get_details($sid, 'employer');
        $company_detail = $this->company_model->get_details($employer_detail[0]['parent_sid'], 'company');
        $this->data['company_detail'] = $company_detail;
        $this->data['creator'] = $employer_detail[0]['created_by'] == null ? [] : $this->company_model->getEmployeeCreator($employer_detail[0]['created_by']);
        $this->data['show_timezone'] = isset($company_detail[0], $company_detail[0]['timezone']) ? $company_detail[0]['timezone'] : '';
        $this->data['page_title'] = 'Edit Employer';
        $security_access_levels = $this->company_model->get_security_access_levels();
        $this->data['security_access_levels'] = $security_access_levels;
        $this->load->library('form_validation');

        if ($employer_detail[0]['username'] != $this->input->post('username')) {
            $this->form_validation->set_rules('username', 'User Name', 'required|min_length[5]|trim|xss_clean|is_unique[users.username]');
        } else {
            $this->form_validation->set_rules('username', 'User Name', 'required|min_length[5]|trim|xss_clean');
        }

        if ($employer_detail[0]['email'] != $this->input->post('email')) {
            $this->form_validation->set_rules('email', 'E-Mail Address', 'trim|xss_clean|valid_email|callback_email_check');
        } else {
            $this->form_validation->set_rules('email', 'E-Mail Address', 'trim|xss_clean|valid_email');
        }

        $this->form_validation->set_rules('alternative_email', 'Alternative Email Address', 'trim|xss_clean|valid_email');
        $this->form_validation->set_rules('direct_business_number', 'Direct Business Number', 'trim|xss_clean');
        $this->form_validation->set_rules('cell_number', 'Cell Number', 'trim|xss_clean');
        $this->form_validation->set_rules('job_title', 'Job Title', 'trim|xss_clean|alpha_numeric_spaces');

        if ($this->form_validation->run() === FALSE) {
            if ($employer_detail) {
                $this->data['data'] = $employer_detail[0];
                $this->data['data']['last_status_text'] = $this->company_model->GetCurrentEmployeeStatus($employer_detail[0]["sid"]);
            } else {
                $this->session->set_flashdata('message', 'Employer does not exists!');
                redirect('manage_admin/employers', 'refresh');
            }

            $this->load->helper('form');
            $this->render('manage_admin/company/edit_employer');
        } else {
            //
            $sid = $this->input->post('sid');
            $action = $this->input->post('submit');
            //
            $data = array(
                'username' => $this->input->post('username'),
                'email' => $this->input->post('email')
            );
            //
            $employeeData = $this->company_model->GetEmployeeById($sid, 'extra_info');
            //
            $extraInfo = unserialize($employeeData['extra_info']);
            $extraInfo['secondary_email'] = $this->input->post('alternative_email', true);

            $data['first_name'] = $this->input->post('first_name');
            $data['last_name'] = $this->input->post('last_name');
            $data['job_title'] = $this->input->post('job_title');
            $data['direct_business_number'] = $this->input->post('direct_business_number');
            $data['cell_number'] = $this->input->post('txt_phonenumber') ? $this->input->post('txt_phonenumber') : $this->input->post('cell_number');
            $data['PhoneNumber'] = $data['cell_number'];
            $data['alternative_email'] = $this->input->post('alternative_email');
            $data['extra_info'] = serialize($extraInfo);
            $registration_date = $this->input->post('registration_date');
            $data['access_level'] = $this->input->post('security_access_level');
            $data['access_level_plus'] = $this->input->post('access_level_plus');
            $data['complynet_status'] = $this->input->post('complynet_status');
            $data['employee_type'] = $this->input->post('employee_type');
            $data['gender'] = $this->input->post('gender');
            $data['marital_status'] = $this->input->post('marital_status');

            $data['workers_compensation_code'] = $this->input->post('workers_compensation_code');
            $data['eeoc_code'] = $this->input->post('eeoc_code');
            $data['salary_benefits'] = $this->input->post('salary_benefits');
            $data['payment_method'] = $this->input->post('payment_method');
            //
            $data['languages_speak'] = null;
            //
            $languages_speak = $this->input->post('secondaryLanguages');
            //
            $data['union_name'] = $this->input->post('union_name');
            $data['union_member'] = $this->input->post('union_member');

            //
            $data['uniform_top_size'] = $this->input->post('uniform_top_size');
            $data['uniform_bottom_size'] = $this->input->post('uniform_bottom_size');

            if ($data['union_member'] == 0) {
                $data['union_name'] = '';
            }

            if ($languages_speak) {
                $data['languages_speak'] = implode(',', $languages_speak);
            }


            if ($this->input->post('temppate_job_title') && $this->input->post('temppate_job_title') != '0') {
                $templetJobTitleData = $this->input->post('temppate_job_title');
                $templetJobTitleDataArray = explode('#', $templetJobTitleData);
                $data['job_title'] = $templetJobTitleDataArray[1];
                $data['job_title_type'] = $templetJobTitleDataArray[0];
            } else {
                $data['job_title_type'] = 0;
            }

           // _e($data,true,true);


            //
            if ($this->input->post('complynet_job_title') != 'null' && $this->input->post('complynet_job_title', true)) {
                $data['complynet_job_title'] = $this->input->post('complynet_job_title');
            }

            //
            if ($data['gender'] != "other") {
                $updateGender = array();
                $updateGender['gender'] = ucfirst($data['gender']);
                $this->company_model->update_gender_in_eeoc_form($sid, 'employee', $updateGender);
            }
            //
            $data['nick_name'] = $this->input->post('nick_name', true)
                ? $this->input->post('nick_name', true)
                : null;
            //
            $data['middle_name'] = $this->input->post('middle_name', true)
                ? $this->input->post('middle_name', true)
                : null;

            //
            if (!empty($this->input->post('hourly_rate', true))) {
                $data['hourly_rate'] = $this->input->post('hourly_rate', true);
            }
            //
            if (!empty($this->input->post('hourly_technician', true))) {
                $data['hourly_technician'] = $this->input->post('hourly_technician', true);
            }
            //
            if (!empty($this->input->post('flat_rate_technician', true))) {
                $data['flat_rate_technician'] = $this->input->post('flat_rate_technician', true);
            }
            //
            if (!empty($this->input->post('semi_monthly_salary', true))) {
                $data['semi_monthly_salary'] = $this->input->post('semi_monthly_salary', true);
            }
            //
            if (!empty($this->input->post('semi_monthly_draw', true))) {
                $data['semi_monthly_draw'] = $this->input->post('semi_monthly_draw', true);
            }

            if ($this->input->post("employment_date", true)) {
                $data["employment_date"] = formatDateToDB(
                    $this->input->post("employment_date", true),
                    "m-d-Y",
                    DB_DATE
                );
            } else {
                $data["employment_date"] = null;
            }

            if (IS_PTO_ENABLED == 1) {
                $data['user_shift_hours'] = $this->input->post('shift_hours', true);
                $data['user_shift_minutes'] = $this->input->post('shift_mins', true);
            }

            if (IS_NOTIFICATION_ENABLED == 1) {
                if (!sizeof($this->input->post('notified_by', true))) $data['notified_by'] = 'email';
                else $data['notified_by'] = implode(',', $this->input->post('notified_by', true));
            }

            if ($registration_date != NULL) {
                $data['registration_date'] = DateTime::createFromFormat('m-d-Y', $registration_date)->format('Y-m-d H:i:s');
                $data['joined_at'] = DateTime::createFromFormat('m-d-Y', $registration_date)->format('Y-m-d');
            } else {
                $data['joined_at'] = NULL;
                $data['registration_date'] = NULL;
            }
            //
            // Added on: 21-12-2021
            if (!empty($this->input->post('rehire_date'))) {

                $rehireDate = DateTime::createFromFormat('m-d-Y', $this->input->post('rehire_date', true))->format('Y-m-d');
                //
                $this->company_model->updateEmployeeRehireDate(
                    $rehireDate,
                    $sid,
                    0
                );
                //
                $data['rehire_date'] = $rehireDate;
                $data['general_status'] = 'rehired';
                $data['active'] = 1;
            }
            //
            $profile_picture = $this->upload_file_to_aws('profile_picture', $sid, 'profile_picture'); // Picture Upload and Update

            if ($profile_picture != 'error') {
                $data['profile_picture'] = $profile_picture;
            } else {
                $pictures = NULL;
            }

            if (IS_TIMEZONE_ACTIVE) {
                //Added on: 25-06-2019
                $timezone = $this->input->post('timezone', true);
                if ($timezone != '') $data['timezone'] = $timezone;
            }

            // set LMS job title
            $data["lms_job_title"] = $this->input->post(
                "lms_job_title",
                true
            );
            //
            $this->company_model->update_user($sid, $data, 'Employer');
            //
            // Check and Update employee basic profile info
            $this->checkAndUpdateProfileInfo(
                $sid,
                $employer_detail[0],
                $data
            );

            //
            $oldData = $this->db
                ->select('first_name, last_name, email, PhoneNumber, parent_sid')
                ->where('sid', $sid)->get('users')->row_array();

            $this->company_model->update_user($sid, $data, 'Employer');

            //
            $teamId = $this->input->post('teamId');
            handleEmployeeDepartmentAndTeam($sid, $teamId);
            //
            $this->load->model('2022/complynet_model', 'complynet_model');
            // check if employee is ready for transfer
            $this
                ->complynet_model
                ->checkAndStartTransferEmployeeProcess(
                    $sid,
                    $oldData["parent_sid"]
                );
            // ComplyNet interjection
            if (isCompanyOnComplyNet($oldData['parent_sid'])) {
                //
                $this->complynet_model->updateEmployeeOnComplyNet($oldData['parent_sid'], $sid, [
                    'first_name' => $oldData['first_name'],
                    'last_name' => $oldData['last_name'],
                    'email' => $oldData['email'],
                    'PhoneNumber' => $oldData['PhoneNumber']
                ]);

                // update employee complynet job title on complynet
                // if ($employer_detail[0]['complynet_job_title'] != $data['complynet_job_title']) {
                updateEmployeeJobRoleToComplyNet($sid, $oldData['parent_sid']);
                // }

                // update employee department on complynet
                //
                $departmentId = $teamId != 0 ? getDepartmentColumnByTeamId($teamId, 'department_sid') : 0;
                //
                // if ($employer_detail[0]['department_sid'] != $departmentId) {
                updateEmployeeDepartmentToComplyNet($sid, $oldData['parent_sid']);
                // }
            }

            if ($action == 'Save') {
                redirect('manage_admin/employers/', 'refresh');
            } else {
                redirect('manage_admin/employers/edit_employer/' . $sid, 'refresh');
            }
        }
    }

    private function checkAndUpdateProfileInfo(
        $employeeId,
        $employeeDetail,
        $dataToInsert
    ) {
        // New employee profile data
        $newProfileData = [];
        $newProfileData['first_name'] = $dataToInsert['first_name'];
        $newProfileData['last_name'] = $dataToInsert['last_name'];
        $newProfileData['dob'] = $dataToInsert['dob'];
        $newProfileData['email'] = $dataToInsert['email'];
        $newProfileData['ssn'] = $dataToInsert['ssn'];
        //
        // Old employee profile data
        $oldProfileData = [];
        $oldProfileData['first_name'] = $employeeDetail['first_name'];
        $oldProfileData['last_name'] = $employeeDetail['last_name'];
        $oldProfileData['email'] = $employeeDetail['email'];
        $oldProfileData['ssn'] = $employeeDetail['ssn'];
        $oldProfileData['dob'] = $employeeDetail['dob'];
        //
        $profileDifference = $this->findDifference($oldProfileData, $newProfileData);
        //
        if ($profileDifference['profile_changed'] == 1) {
            $this->load->model("gusto/Gusto_payroll_model", "gusto_payroll_model");
            $this->gusto_payroll_model->updateGustoEmployeInfo($employeeId, 'profile');
        }
        //
        // New employee address data
        $newAddressData = [];
        $newAddressData['Location_Address'] = $dataToInsert['Location_Address'];
        $newAddressData['Location_City'] = $dataToInsert['Location_City'];
        $newAddressData['Location_ZipCode'] = $dataToInsert['Location_ZipCode'];
        $newAddressData['Location_State'] = $dataToInsert['Location_State'];
        //
        // Old employee address data
        $oldAddressData = [];
        $oldAddressData['Location_Address'] = $employeeDetail['Location_Address'];
        $oldAddressData['Location_City'] = $employeeDetail['Location_City'];
        $oldAddressData['Location_State'] = $employeeDetail['Location_State'];
        $oldAddressData['Location_ZipCode'] = $employeeDetail['Location_ZipCode'];
        //
        $addressDifference = $this->findDifference($oldAddressData, $newAddressData);
        //
        if ($addressDifference['profile_changed'] == 1) {
            $this->load->model("gusto/Gusto_payroll_model", "gusto_payroll_model");
            $this->gusto_payroll_model->updateGustoEmployeInfo($employeeId, 'address');
        }
        //
        // New employee payment method
        $newPaymentData = [];
        $newPaymentData['payment_method'] = $dataToInsert['payment_method'];
        //
        // Old employee payment method
        $oldPaymentData = [];
        $oldPaymentData['payment_method'] = $employeeDetail['payment_method'];
        //
        $paymentMethodDifference = $this->findDifference($oldPaymentData, $newPaymentData);
        //
        if ($paymentMethodDifference['profile_changed'] == 1) {
            $this->load->model("gusto/Gusto_payroll_model", "gusto_payroll_model");
            $this->gusto_payroll_model->updateGustoEmployeInfo($employeeId, 'payment_method');
        }
    }

    /**
     * 
     */
    function findDifference($previous_data, $form_data)
    {
        // 
        $profile_changed = 0;
        //
        $dt = [];
        //
        if (!empty($previous_data)) {
            foreach ($previous_data as $key => $data) {
                //
                if (!isset($form_data[$key])) {
                    continue;
                }
                //   
                if ((isset($form_data[$key])) && strip_tags($data) != strip_tags($form_data[$key])) {
                    //
                    $dt[$key] = [
                        'old' => $data,
                        'new' => $form_data[$key]
                    ];
                    //
                    $profile_changed = 1;
                }
            }
        }
        //

        return ['profile_changed' => $profile_changed, 'data' => $dt];
    }

    public function add_employer($company_sid = null)
    {
        $redirect_url = 'manage_admin';
        $function_name = 'edit_employers';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name

        if ($company_sid != null) {
            $this->data['company_sid'] = $company_sid;
            $this->data['page_title'] = 'Create Employer';
            $company_name = $this->company_model->get_company_name($company_sid);
            $this->data['company_name'] = $company_name;
            $security_access_levels = $this->company_model->get_security_access_levels();
            $this->data['security_access_levels'] = $security_access_levels;
            $this->form_validation->set_rules('first_name', 'First Name', 'required|trim');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required|trim');
            $this->form_validation->set_rules('username', 'Username', 'required|trim|min_length[5]|is_unique[users.username]');
            $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
            $this->form_validation->set_rules('alternative_email', 'Alternative Email', 'trim|valid_email');
            $this->form_validation->set_rules('job_title', 'Job Title', 'trim');
            $this->form_validation->set_rules('direct_business_number', 'Direct Business Number', 'trim');

            if (get_company_module_status($company_sid, 'primary_number_required') == 1) {
                $this->form_validation->set_rules('cell_number', 'Cell Number', 'trim');
            }

            $this->form_validation->set_rules('security_access_level', 'Security Access Level', 'required|trim');

            if ($this->form_validation->run() == false) {
                $this->render('manage_admin/company/add_employer');
            } else {

                $company_sid = $this->input->post('company_sid');
                $ip_address = getUserIP() . ' - ' . $_SERVER['HTTP_USER_AGENT'];
                $username = $this->input->post('username');

                $email = $this->input->post('email');
                $first_name = $this->input->post('first_name');
                $last_name = $this->input->post('last_name');
                $job_title = $this->input->post('job_title');
                $cell_number = $this->input->post('cell_number');
                $direct_business_number = $this->input->post('direct_business_number');
                $alternative_email = $this->input->post('alternative_email');
                $access_level = $this->input->post('security_access_level');
                $registration_date = $this->input->post('registration_date');
                $action = $this->input->post('action');
                $gender = $this->input->post('gender');
                $timezone = $this->input->post('timezone');
                $payment_method = $this->input->post('payment_method');
                $employee_type = $this->input->post('employee_type');
                $salt = generateRandomString(48);


                if ($registration_date != NULL) {
                    $joined_at = DateTime::createFromFormat('m-d-Y', $registration_date)->format('Y-m-d');
                    $registration_date = DateTime::createFromFormat('m-d-Y', $registration_date)->format('Y-m-d H:i:s');
                } else {
                    $joined_at = NULL;
                    $registration_date = NULL;
                }

                $insert_data = array();

                $insert_data['job_title'] = $job_title;
                //
                if ($this->input->post('temppate_job_title') && $this->input->post('temppate_job_title') != '0') {
                    $templetJobTitleData = $this->input->post('temppate_job_title');
                    $templetJobTitleDataArray = explode('#', $templetJobTitleData);
                    $insert_data['job_title'] = $templetJobTitleDataArray[1];
                    $insert_data['job_title_type'] = $templetJobTitleDataArray[0];
                    $insert_data['lms_job_title'] = $templetJobTitleDataArray[0];
                } else {
                    $insert_data['job_title_type'] = 0;
                    $insert_data['lms_job_title'] = null;
                }


                $insert_data['ip_address'] = $ip_address;
                $insert_data['username'] = $username;
                $insert_data['email'] = $email;
                $insert_data['first_name'] = $first_name;
                $insert_data['last_name'] = $last_name;
                $insert_data['employee_type'] = $employee_type;
                $insert_data['cell_number'] = $cell_number;
                $insert_data['registration_date'] = $registration_date;
                $insert_data['joined_at'] = $joined_at;
                $insert_data['CompanyName'] = $company_name;
                $insert_data['direct_business_number'] = $direct_business_number;
                $insert_data['alternative_email'] = $alternative_email;
                $insert_data['access_level'] = $access_level;
                $insert_data['salt'] = $salt;
                $insert_data['gender'] = $gender;
                $insert_data['timezone'] = $timezone;
                $insert_data['payment_method'] = $payment_method;
                $insert_data['extra_info'] = serialize(['secondary_email' => $this->input->post('alternative_email', true)]);
                $insert_data['access_level_plus'] = $this->input->post('access_level_plus');

                $insert_data['workers_compensation_code'] = $this->input->post('workers_compensation_code');
                $insert_data['eeoc_code'] = $this->input->post('eeoc_code');
                $insert_data['salary_benefits'] = $this->input->post('salary_benefits');

                //
                if ($this->input->post('complynet_job_title') != 'null' && $this->input->post('complynet_job_title', true)) {
                    $insert_data['complynet_job_title'] = $this->input->post('complynet_job_title');
                }

                $sid = $this->company_model->add_new_employer($company_sid, $insert_data);
                $profile_picture = $this->upload_file_to_aws('profile_picture', $sid, 'profile_picture');
                //
                //
                $teamId = $this->input->post('teamId');
                handleEmployeeDepartmentAndTeam($sid, $teamId);

                if ($profile_picture != 'error') {
                    $pictures = array('profile_picture' => $profile_picture);
                    $this->company_model->update_user($sid, $pictures);
                } else {
                    $pictures = NULL;
                }

                if ($action == 'sendemail') {
                    $replacement_array = array();
                    $replacement_array['employer_name'] = ucwords($first_name . ' ' . $last_name);
                    $replacement_array['access_level'] = ucwords($access_level);
                    $replacement_array['company_name'] = $company_name;
                    $replacement_array['username'] = $username;
                    $replacement_array['login_page'] = '<a style="background-color: #d62828; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" href="https://www.automotohr.com/login" target="_blank">Login page</a>';
                    $replacement_array['firstname'] = $first_name;
                    $replacement_array['lastname'] = $last_name;
                    $replacement_array['first_name'] = $first_name;
                    $replacement_array['last_name'] = $last_name;
                    $replacement_array['email'] = $email;
                    $replacement_array['create_password_link']  = '<a style="background-color: #d62828; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" target="_blank" href="' . base_url() . "employee_management/generate_password/" . $salt . '">Create Your Password</a>';
                    log_and_send_templated_email(NEW_EMPLOYEE_TEAM_MEMBER_NOTIFICATION, $email, $replacement_array);
                }

                $this->session->set_flashdata('message', '<strong>Success: </strong> Employer Account Created');
                redirect('manage_admin/employers', 'refresh');
            }
        } else {
            redirect('manage_admin/companies', 'refresh');
        }
    }

    function employer_task()
    {
        $redirect_url = 'manage_admin';
        $function_name = 'show_employer_multiple_actions';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $action = $this->input->post('action');
        $employer_id = $this->input->post('sid');

        if ($action == 'delete') {
            $this->company_model->delete_employer($employer_id);
        }
    }

    function employer_login()
    {

        $redirect_url = 'manage_admin';
        $function_name = 'employerlogin';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->load->model('dashboard_model');
        $action = $this->input->post('action');
        $employer_id = $this->input->post('sid');


        if ($action == 'login') {

            $result = $this->dashboard_model->update_session_details(0, $employer_id);
            $empData = $result['employer'];
            $system_check = NULL;

            if (isset($_POST['system'])) {
                $system_check = $_POST['system'];
            }

            $task_check = NULL;

            if (isset($_POST['task'])) {
                $task_check = $_POST['task'];
            }

            if ($system_check != null) {
                $dataToUpdate = array('verification_key' => NULL); //removing verification key from user document
                $this->company_model->updateUserDocument($employer_id, $dataToUpdate); //activate user
                $updatedData = array('active' => 1);
                $this->dashboard_model->update_user($employer_id, $updatedData);
            } else if ($task_check != null) {
                $updatedData = array('activation_key' => NULL); //Removing user activation_key
                $this->dashboard_model->update_user($empData["parent_sid"], $updatedData);
            }

            $companyData = $result['company'];


            if ($empData) {
                $portal_detail = $result['portal'];
                $sess_array = array();
                $data['cart'] = db_get_cart_content($empData['parent_sid']);
                $sess_array = array(
                    'company_detail' => $companyData,
                    'employer_detail' => $empData,
                    'portal_detail' => $portal_detail,
                    'cart' => $data['cart'],
                    'is_super' => 1,
                    'clocked_status' => $result['clocked_status']
                );

                $this->db->where('company_id', $sess_array['company_detail']['sid']);
                $config = $this->db->count_all_results('incident_type_configuration');
                $sess_array['incident_config'] = $config;
                $sess_array['resource_center'] = $sess_array['company_detail']['enable_resource_center'];

                $this->session->set_userdata('logged_in', $sess_array);
                $activity_data = array();
                $activity_data['company_sid'] = $companyData['sid'];
                $activity_data['employer_sid'] = $empData['sid'];
                $activity_data['company_name'] = $companyData['CompanyName'];
                $activity_data['employer_name'] = $empData['first_name'] . ' ' . $empData['last_name'];
                $activity_data['employer_access_level'] = $empData['access_level'];
                $activity_data['module'] = 'Super Admin';
                $activity_data['action_performed'] = 'Employer Login';
                $activity_data['action_year'] = date('Y');
                $activity_data['action_week'] = date('W');
                $activity_data['action_timestamp'] = date('Y-m-d H:i:s');
                $activity_data['action_status'] = '';
                $activity_data['action_url'] = current_url();
                $activity_data['employer_ip'] = getUserIP();
                $activity_data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
                $this->db->insert('logged_in_activitiy_tracker_super', $activity_data);
                return "true";
            } else
                return "false";
        }
    }

    function upload_file_to_aws($file_input_id, $company_sid, $document_name, $suffix = '', $bucket_name = AWS_S3_BUCKET_NAME)
    {
        require_once(APPPATH . 'libraries/aws/aws.php');

        if (isset($_FILES[$file_input_id]) && $_FILES[$file_input_id]['name'] != '') {
            $last_index_of_dot = strrpos($_FILES[$file_input_id]["name"], '.') + 1;
            $file_ext = substr($_FILES[$file_input_id]["name"], $last_index_of_dot, strlen($_FILES[$file_input_id]["name"]) - $last_index_of_dot);
            $file_name = trim($document_name . '-' . $suffix);
            $file_name = str_replace(" ", "_", $file_name);
            $file_name = strtolower($file_name);
            $prefix = str_pad($company_sid, 4, '0', STR_PAD_LEFT);
            $new_file_name = $prefix . '-' . $file_name . '-' . generateRandomString(3) . '.' . $file_ext;

            if ($_FILES[$file_input_id]['size'] == 0) {
                $this->session->set_flashdata('message', '<b>Warning:</b> File is empty! Please try again.');
                return 'error';
            }

            $aws = new AwsSdk();
            $aws->putToBucket($new_file_name, $_FILES[$file_input_id]['tmp_name'], $bucket_name);
            return $new_file_name;
        } else {
            return 'error';
        }
    }

    public function change_status()
    {

        $action = $this->input->post('action');
        $employer_id = $this->input->post('sid');

        $data_to_insert = array();
        $data_to_insert['status_change_date'] = date('Y-m-d');
        $data_to_insert['employee_sid'] =  $employer_id;
        $data_to_insert['changed_by'] = 0;
        $data_to_insert['ip_address'] = getUserIP();
        $data_to_insert['user_agent'] = $_SERVER['HTTP_USER_AGENT'];

        if ($action == 'deactive') {
            $data_to_insert['employee_status'] = 6; // inactive
            $this->company_model->terminate_user($employer_id, $data_to_insert);
            $data = array('active' => 0, 'general_status' => 'inactive');
            $this->company_model->update_user_status($employer_id, $data);
        } elseif ($action == 'active') {
            $data_to_insert['employee_status'] = 5; // active
            $this->company_model->terminate_user($employer_id, $data_to_insert);
            $data = array('active' => 1, 'terminated_status' => 0, 'general_status' => 'active');
            $this->company_model->update_user_status($employer_id, $data);
        }
        //
        changeComplynetEmployeeStatus($employer_id, $action);
    }

    function send_login_credentials()
    {
        $action = $this->input->post('action');
        $sid = $this->input->post('sid');
        $company_name = $this->input->post('name');
        $employee_details = $this->company_model->get_employee_details($sid);

        if (!empty($employee_details)) {
            $first_name = $employee_details[0]['first_name'];
            $last_name = $employee_details[0]['last_name'];
            $username = $employee_details[0]['username'];
            $access_level = $employee_details[0]['access_level'];
            $email = $employee_details[0]['email'];
            $salt = $employee_details[0]['salt'];

            if ($salt == NULL || $salt == '') {
                $salt = generateRandomString(48);
                $data = array('salt' => $salt);
                $this->company_model->update_user($sid, $data);
            }

            if ($action == 'sendemail') {
                $replacement_array = array();
                $replacement_array['employer_name'] = ucwords($first_name . ' ' . $last_name);
                $replacement_array['access_level'] = ucwords($access_level);
                $replacement_array['company_name'] = $company_name;
                $replacement_array['username'] = $username;
                $replacement_array['login_page'] = '<a style="background-color: #d62828; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" href="https://www.automotohr.com/login" target="_blank">Login page</a>';
                $replacement_array['firstname'] = $first_name;
                $replacement_array['lastname'] = $last_name;
                $replacement_array['first_name'] = $first_name;
                $replacement_array['last_name'] = $last_name;
                $replacement_array['email'] = $email;
                $replacement_array['create_password_link']  = '<a style="background-color: #d62828; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" target="_blank" href="' . base_url() . "employee_management/generate_password/" . $salt . '">Create Your Password</a>';
                log_and_send_templated_email(NEW_EMPLOYEE_TEAM_MEMBER_NOTIFICATION, $email, $replacement_array);
            }

            echo 'success';
        } else {
            echo 'error';
        }
    }

    //
    function email_check($email)
    {
        //
        $post = $this->input->post(NULL, TRUE);
        //
        $this->form_validation->set_message('email_check', 'The %s already exists.');
        //
        if (!isset($email)) {
            return false;
        }
        //
        if (isset($post['sid'])) {
            $parent_sid = $this->db
                ->select('parent_sid')
                ->where('sid', $post['sid'])
                ->get('users')
                ->row_array()['parent_sid'];
        }
        //
        $this->db
            ->where('LOWER(email)', strtolower($email));
        //
        if (isset($post['sid'])) {
            $this->db->where('sid <> ', $post['sid']);
            $this->db->where('is_executive_admin', 0);
            $this->db->where('parent_sid', $parent_sid);
        }
        //
        if ($this->db->count_all_results('users')) {
            return false;
        }
        //
        return true;
    }

    public function AssignBulkDocuments($sid = NULL)
    {
        $redirect_url = 'manage_admin';
        $function_name = 'AssignBulkDocuments';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $employee_detail = $this->company_model->get_details($sid, 'employer');
        $company_detail = $this->company_model->get_details($employee_detail[0]['parent_sid'], 'company');
        // 
        $this->data['creator'] = $employee_detail[0]['created_by'] == null ? [] : $this->company_model->getEmployeeCreator($employee_detail[0]['created_by']);
        $this->data['active_categories'] = $this->company_model->get_all_documents_category($company_detail[0]['sid']);
        $this->data['page_title'] = 'Assign Bulk Document';
        $this->data['companySid'] = $company_detail[0]['sid'];
        $this->data['companyName'] = $company_detail[0]['CompanyName'];
        $this->data['employeeName'] = getUserNameBySID($sid);
        $security_access_levels = $this->company_model->get_security_access_levels();
        $this->data['security_access_levels'] = $security_access_levels;
        $this->load->library('form_validation');



        if ($this->form_validation->run() === FALSE) {
            if ($employee_detail) {
                $this->data['employee_detail'] = $employee_detail[0];
            } else {
                $this->session->set_flashdata('message', 'Employer does not exists!');
                redirect('manage_admin/employers', 'refresh');
            }

            $this->load->helper('form');
            $this->render('manage_admin/company/assign_bulk_document');
        }
    }

    /**
     * Assign document to employee
     *
     * accepts POST
     *
     * @return JSON
     *
     */
    function upload_assign_document()
    {
        // check if ajax request is not set
        if (!$this->input->is_ajax_request()) redirect('assign_bulk_documents', 'referesh');
        // set return array
        $return_array = array('Status' => FALSE, 'Response' => 'Invalid request', 'Redirect' => TRUE);
        // check if request method is not GET
        // user is not signed in
        if ($this->input->server('REQUEST_METHOD') != 'POST' || !$this->session->userdata('logged_in')) $this->response($return_array);
        //

        //
        $file = $_FILES['file'];
        $formpost = $this->input->post(NULL, TRUE);
        if (!sizeof($file)) $this->response($return_array);
        if ($file['error'] != 0) $this->response($return_array);
        //
        $userId                 = $formpost['employeeId'];
        $userType               = $formpost['type'];
        $document_title         = $file['name'];
        $companyId              = $formpost['companyId'];;
        $employerId             = 0;
        $document_description   = '';

        $gen_document_title = substr($document_title, 0, strrpos($document_title, '.'));
        $gen_document_title = ucwords((preg_replace('/[^A-Za-z0-9\-]/', ' ', $gen_document_title)));

        //
        if ($_SERVER['HTTP_HOST'] == 'localhost') $uploaded_document_s3_name = '0057-test_latest_uploaded_document-58-Yo2.pdf';
        else $uploaded_document_s3_name = upload_file_to_aws('file', $companyId, str_replace(' ', '_', $document_title), $employerId, AWS_S3_BUCKET_NAME);
        // $uploaded_document_s3_name = upload_file_to_aws('file', $companyId, str_replace(' ', '_', $document_title), $employerId, AWS_S3_BUCKET_NAME);
        //
        $uploaded_document_original_name = $document_title;
        //
        $file_info = pathinfo($uploaded_document_original_name);
        //
        $data_to_insert = array();
        $data_to_insert['status'] = 1;
        $data_to_insert['user_sid'] = $userId;
        $data_to_insert['user_type'] = $userType;
        $data_to_insert['company_sid'] = $companyId;
        $data_to_insert['assigned_by'] = $employerId;
        $data_to_insert['document_sid'] = 0;
        $data_to_insert['user_consent'] = 1;

        if (isset($_POST['signed_date']) && $_POST['signed_date'] != '') {
            $data_to_insert['signature_timestamp'] = DateTime::createFromFormat('m/d/Y', $_POST['signed_date'])->format('Y-m-d') . ' 00:00:00';
        }

        $data_to_insert['document_type'] = 'uploaded';
        $data_to_insert['assigned_date'] = date('Y-m-d H:i:s');
        $data_to_insert['document_title'] = $gen_document_title;
        $data_to_insert['document_description'] = $document_description;
        //
        if (isset($file_info['extension'])) {
            $data_to_insert['document_extension'] = $file_info['extension'];
        }
        //
        if ($uploaded_document_s3_name != 'error') {
            $data_to_insert['uploaded'] = 1;
            $data_to_insert['uploaded_file'] = $uploaded_document_s3_name;
            $data_to_insert['uploaded_date'] = date('Y-m-d H:i:s');
            $data_to_insert['document_s3_name'] = $uploaded_document_s3_name;
            $data_to_insert['document_original_name'] = $uploaded_document_original_name;
        } else {
            $return_array['Response'] = 'Error';
            $this->response($return_array);
        }

        if (isset($_POST['is_offer_letter'])) {
            $user_info = '';

            $user_info = $this->company_model->get_employee_information($companyId, $userId);

            $offer_letter_name = $gen_document_title;

            $data_to_insert['document_title']       = $offer_letter_name;
            $data_to_insert['document_type']        = 'offer_letter';
            $data_to_insert['offer_letter_type']    = 'uploaded';

            $already_assigned = $this->company_model->check_employee_offer_letter_exist($companyId, $userType, $userId, 'offer_letter');

            if (!empty($already_assigned)) {
                foreach ($already_assigned as $key => $previous_offer_letter) {
                    $previous_assigned_sid = $previous_offer_letter['sid'];
                    $already_moved = $this->company_model->check_offer_letter_moved($previous_assigned_sid, 'offer_letter');

                    if ($already_moved == 'no') {
                        $previous_offer_letter['doc_sid'] = $previous_assigned_sid;
                        unset($previous_offer_letter['sid']);
                        $this->company_model->insert_documents_assignment_record_history($previous_offer_letter);
                    }
                }
            }

            $this->company_model->disable_all_previous_letter($companyId, $userType, $userId, 'offer_letter');
        } else {

            if (!isset($_POST['categories'])) {
                if (isset($_POST['visible_to_payroll'])) {
                    $data_to_insert['visible_to_payroll'] = 1;
                } else {
                    $data_to_insert['visible_to_payroll'] = 0;
                }
            } else if (!in_array(27, $_POST['categories'])) {
                if (isset($_POST['visible_to_payroll'])) {
                    $data_to_insert['visible_to_payroll'] = 1;
                } else {
                    $data_to_insert['visible_to_payroll'] = 0;
                }
            }
        }

        //
        $insert_id = $this->company_model->insertDocumentsAssignmentRecord($data_to_insert);
        $this->company_model->add_update_categories_2_documents($insert_id, $this->input->post('categories'), "documents_assigned");

        $return_array['Status'] = true;
        $return_array['Response'] = 'Proceed';
        $this->response($return_array);
    }

    public function employee_status_detail($sid = NULL)
    {
        $redirect_url = 'manage_admin';
        $function_name = 'employee_status';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        //
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $employee_detail = $this->company_model->get_details($sid, 'employer');
        $company_detail = $this->company_model->get_details($employee_detail[0]['parent_sid'], 'company');
        //
        $this->load->library('form_validation');
        //
        $employee_status_records = $this->company_model->get_employee_status_detail($sid);
        //
        foreach ($employee_status_records as $key => $record) {
            $record_sid = $record['sid'];
            $attach_documents = $this->company_model->get_terminated_employees_documents($sid, $record_sid);
            $employee_status_records[$key]['attach_documents'] = $attach_documents;
        }
        //
        $this->data['employer_sid'] = 0;
        $this->data['employeeSid'] = $sid;
        $this->data['security_details'] = $security_details;
        $this->data['page_title'] = 'Employee Status';
        $this->data['companySid'] = $company_detail[0]['sid'];
        $this->data['employeeName'] = getUserNameBySID($sid);
        $this->data['companyName'] = $company_detail[0]['CompanyName'];
        $this->data['employee_status_records'] = $employee_status_records;
        //
        if ($this->form_validation->run() === FALSE) {
            if ($employee_detail) {
                $this->data['employee_detail'] = $employee_detail[0];
            } else {
                $this->session->set_flashdata('message', 'Employer does not exists!');
                redirect('manage_admin/employers', 'refresh');
            }

            $this->load->helper('form');
            $this->render('manage_admin/company/employee_status_detail');
        }
    }

    public function change_employee_status($sid = NULL)
    {
        $redirect_url = 'manage_admin';
        $function_name = 'employee_status';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        //
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $employee_detail = $this->company_model->get_details($sid, 'employer');
        $company_detail = $this->company_model->get_details($employee_detail[0]['parent_sid'], 'company');
        //
        $this->data['page_title'] = 'Change Employee Status';
        $this->data['companySid'] = $company_detail[0]['sid'];
        $this->data['companyName'] = $company_detail[0]['CompanyName'];
        $this->data['employeeName'] = getUserNameBySID($sid);
        $this->data['employeeSid'] = $sid;
        $this->data['security_details'] = $security_details;
        //
        $this->load->library('form_validation');
        $this->form_validation->set_rules('status', 'Status', 'trim|xss_clean|required');
        $this->form_validation->set_rules('termination_details', 'Termination Details', 'trim|xss_clean|required');
        //
        if ($this->form_validation->run() === FALSE) {
            if ($employee_detail) {
                $this->data['employee_detail'] = $employee_detail[0];
            } else {
                $this->session->set_flashdata('message', 'Employer does not exists!');
                redirect('manage_admin/employers', 'refresh');
            }

            $this->load->helper('form');
            $this->render('manage_admin/company/change_employee_status');
        } else {
            $status = $this->input->post('status');
            $termination_reason = $this->input->post('terminated_reason');
            $termination_date = $this->input->post('termination_date');
            $status_change_date = $this->input->post('status_change_date');
            $termination_details = $this->input->post('termination_details');
            $involuntary = isset($_POST['involuntary']) ? $_POST['involuntary'] : 0;
            $rehire = isset($_POST['rehire']) ? $_POST['rehire'] : 0;
            $system_access = isset($_POST['system_access']) ? $_POST['system_access'] : 0;

            $data_to_insert = array();
            $data_to_insert['employee_status'] = $status;
            $data_to_insert['termination_reason'] = empty($termination_reason) ? 0 : $termination_reason;
            if ($status == 1) {
                $data_to_insert['termination_date'] = formatDateToDB($termination_date, 'm-d-Y'); //date('Y-m-d', strtotime($termination_date));
            }

            $data_to_insert['involuntary_termination'] = $involuntary;
            $data_to_insert['do_not_hire'] = $rehire;
            $data_to_insert['status_change_date'] = formatDateToDB($status_change_date, 'm-d-Y'); // date('Y-m-d', strtotime($status_change_date));
            $data_to_insert['details'] = htmlentities($termination_details);
            $data_to_insert['employee_sid'] = $sid;
            $data_to_insert['changed_by'] = 0;
            $data_to_insert['ip_address'] = getUserIP();
            $data_to_insert['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
            $data_to_update = array();

            if ($status == 1) {
                if ($system_access == 1) {
                    $data_to_update['active'] = 0;
                } elseif (date('m-d-Y') >= $termination_date) {
                    $data_to_update['active'] = 0;
                }
                $data_to_update['terminated_status'] = 1;
                $data_to_update['general_status'] = 'terminated';
            } else {
                if ($status == 5) {
                    $data_to_update['active'] = 1;
                    $data_to_update['general_status'] = 'active';
                } else if ($status == 6) {
                    $data_to_update['active'] = 0;
                    $data_to_update['general_status'] = 'inactive';
                } else if ($status == 7) {
                    $data_to_update['general_status'] = 'leave';
                } else if ($status == 4) {
                    $data_to_update['general_status'] = 'suspended';
                    $data_to_update['active'] = 0;
                } else if ($status == 3) {
                    $data_to_update['general_status'] = 'deceased';
                    $data_to_update['active'] = 0;
                } else if ($status == 2) {
                    $data_to_update['general_status'] = 'retired';
                    $data_to_update['active'] = 0;
                } else if ($status == 8) {
                    $data_to_update['active'] = 1;
                    $data_to_update['general_status'] = 'rehired';
                    $data_to_update['rehire_date'] = $data_to_insert['status_change_date'];
                }
                $data_to_update['terminated_status'] = 0;
            }
            //
            $rowId = $this->company_model->terminate_user($sid, $data_to_insert);
            //
            //
            $this->load->model("v1/payroll_model", "payroll_model");
            //
            $companyPayrollStatus = $this->payroll_model->GetCompanyPayrollStatus($company_detail[0]['sid']);
            $employeePayrollStatus = $this->payroll_model->checkEmployeePayrollStatus($sid, $company_detail[0]['sid']);
            //
            if ($companyPayrollStatus && $employeePayrollStatus) {
                if ($status == 1 || $status == 8) {
                    //
                    $effective_date = $data_to_insert['status_change_date'];
                    //
                    if ($status == 1) {
                        $effective_date = $data_to_insert['termination_date'];
                    }
                    //
                    $employeeData[] = [
                        'sid' => $rowId,
                        'effective_date' => $effective_date,
                        'employee_status' => $status
                    ];
                    //
                    $response = $this->payroll_model->syncEmployeeStatus(
                        $sid,
                        $employeeData
                    );
                    //
                    if (isset($response['errors'])) {
                        //
                        // Delete inserted record because gusto error
                        $this->company_model->deleteEmployeeStatus($rowId);
                        //
                        $this->session->set_flashdata('message', '<b>Error:</b> ' . $response['errors'][0]['message'] . '!');
                        redirect(base_url('manage_admin/employers/EmployeeStatusDetail/' . $sid), 'refresh');
                    }
                }
            }

            if ($status == 9) {
                $data_transfer_log_update['to_company_sid'] = $company_detail[0]['sid'];
                $data_transfer_log_update['employee_copy_date'] = formatDateToDB($status_change_date, 'm-d-Y');
                $this->company_model->employees_transfer_log_update($sid, $data_transfer_log_update);
                //
                // ToDo if transfer then complynet status update pending
            }
            if ($status != 9) {
                $this->company_model->change_terminate_user_status($sid, $data_to_update);
            }
            //
            $employeeStatus = $data_to_update['active'] == 1 ? "active" : "deactive";
            changeComplynetEmployeeStatus($sid, $employeeStatus);
            //
            $this->session->set_flashdata('message', '<b>Success:</b> Status Updated Successfully!');
            redirect(base_url('manage_admin/employers/EmployeeStatusDetail/' . $sid), 'refresh');
        }
    }

    public function edit_employee_status($sid, $status_id)
    {
        $redirect_url = 'manage_admin';
        $function_name = 'employee_status';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        //
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $employee_detail = $this->company_model->get_details($sid, 'employer');
        $company_detail = $this->company_model->get_details($employee_detail[0]['parent_sid'], 'company');
        //
        $status_data = $this->company_model->get_status_by_id($status_id);
        $status_documents = $this->company_model->get_status_documents($status_id);
        //
        $this->data['security_details'] = $security_details;
        $this->data['page_title'] = 'Edit Employee Status';
        $this->data['companySid'] = $company_detail[0]['sid'];
        $this->data['companyName'] = $company_detail[0]['CompanyName'];
        $this->data['employeeName'] = getUserNameBySID($sid);
        $this->data['employeeSid'] = $sid;
        $this->data['status_id'] = $status_id;
        $this->data['status_data'] = $status_data;
        $this->data['employee_detail'] = $employee_detail[0];
        $this->data['status_documents'] = $status_documents;
        //
        $this->load->library('form_validation');
        $this->form_validation->set_rules('status', 'Status', 'trim|xss_clean|required');
        $this->form_validation->set_rules('termination_details', 'Termination Details', 'trim|xss_clean|required');
        //
        if ($this->form_validation->run() === FALSE) {
            if ($employee_detail) {
                $this->data['employee_detail'] = $employee_detail[0];
            } else {
                $this->session->set_flashdata('message', 'Employer does not exists!');
                redirect('manage_admin/employers', 'refresh');
            }

            $this->load->helper('form');
            $this->render('manage_admin/company/edit_employee_status');
        } else {
            $status = $this->input->post('status');
            $termination_reason = $this->input->post('terminated_reason');
            $termination_date = $this->input->post('termination_date');
            $status_change_date = $this->input->post('status_change_date');
            $termination_details = $this->input->post('termination_details');
            $involuntary = isset($_POST['involuntary']) ? $_POST['involuntary'] : 0;
            $rehire = isset($_POST['rehire']) ? $_POST['rehire'] : 0;
            $system_access = isset($_POST['system_access']) ? $_POST['system_access'] : 0;

            $data_to_insert = array();
            $data_to_insert['employee_status'] = $status;
            $data_to_insert['termination_reason'] = empty($termination_reason) ? 0 : $termination_reason;

            if ($status == 1) {
                $data_to_insert['termination_date'] = formatDateToDB($termination_date, 'm-d-Y'); // date('Y-m-d', strtotime($termination_date));
            } else {
                $data_to_insert['termination_date'] = NULL;
            }

            $data_to_insert['involuntary_termination'] = $involuntary;
            $data_to_insert['do_not_hire'] = $rehire;
            $data_to_insert['status_change_date'] = formatDateToDB($status_change_date, 'm-d-Y'); // date('Y-m-d', strtotime($status_change_date));
            $data_to_insert['details'] = htmlentities($termination_details);
            $data_to_insert['employee_sid'] = $sid;
            $data_to_insert['changed_by'] = 0;
            $data_to_insert['ip_address'] = getUserIP();
            $data_to_insert['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
            $data_to_update = array();

            if ($status == 1) {
                if ($system_access == 1) {
                    $data_to_update['active'] = 0;
                } elseif (date('m-d-Y') >= $termination_date) {
                    $data_to_update['active'] = 0;
                } else {
                    $data_to_update['active'] = 1;
                }
                $data_to_update['terminated_status'] = 1;
                $data_to_update['general_status'] = 'terminated';
            } else {
                if ($status == 5) {
                    $data_to_update['active'] = 1;
                    $data_to_update['general_status'] = 'active';
                } else if ($status == 6) {
                    $data_to_update['active'] = 0;
                    $data_to_update['general_status'] = 'inactive';
                } else if ($status == 7) {
                    $data_to_update['general_status'] = 'leave';
                } else if ($status == 4) {
                    $data_to_update['active'] = 0;
                    $data_to_update['general_status'] = 'suspended';
                } else if ($status == 3) {
                    $data_to_update['active'] = 0;
                    $data_to_update['general_status'] = 'deceased';
                } else if ($status == 2) {
                    $data_to_update['active'] = 0;
                    $data_to_update['general_status'] = 'retired';
                } else if ($status == 8) {
                    $data_to_update['active'] = 1;
                    $data_to_update['general_status'] = 'rehired';
                    $data_to_update['rehire_date'] = $data_to_insert['status_change_date'];
                }
                $data_to_update['terminated_status'] = 0;
            }
            //
            //
            $this->load->model("v1/payroll_model", "payroll_model");
            //
            $companyPayrollStatus = $this->payroll_model->GetCompanyPayrollStatus($company_detail[0]['sid']);
            $employeePayrollStatus = $this->payroll_model->checkEmployeePayrollStatus($sid, $company_detail[0]['sid']);
            //
            //
            if ($companyPayrollStatus && $employeePayrollStatus) {
                //
                if ($status == 1 || $status == 8) {
                    $old_effective_date = @unserialize($status_data['payroll_object'])['effective_date'];
                    $oldDate = strtotime($old_effective_date);
                    $newDate = strtotime($data_to_insert['status_change_date']);
                    $effectiveDate = $data_to_insert['status_change_date'];
                    //
                    if ($status == 1) {
                        $newDate = strtotime($data_to_insert['termination_date']);
                        $effectiveDate = $data_to_insert['termination_date'];
                    }
                    //
                    if ($oldDate != $newDate) {
                        $employeeData = [];
                        $employeeData['sid'] = $status_id;
                        $employeeData['effective_date'] = $effectiveDate;
                        $employeeData['version'] = $status_data['payroll_version'];
                        $employeeData['employee_status'] = $status;
                        //
                        $response = $this->payroll_model->updateEmployeeStatusOnGusto(
                            $sid,
                            $company_detail[0]['sid'],
                            $employeeData
                        );
                        //
                        if (isset($response['errors'])) {
                            $this->session->set_flashdata('message', '<b>Error:</b> ' . $response['errors'][0]['message'] . '!');
                            redirect(base_url('manage_admin/employers/EmployeeStatusDetail/' . $sid), 'refresh');
                        }
                    }
                }
            }
            //
            $this->company_model->update_terminate_user($status_id, $data_to_insert);
            //
            if ($status == 9) {

                $data_transfer_log_update['to_company_sid'] = $company_detail[0]['sid'];;
                $data_transfer_log_update['employee_copy_date'] = formatDateToDB($status_change_date, 'm-d-Y');

                $this->company_model->employees_transfer_log_update($sid, $data_transfer_log_update);

                //
                $this->db->where('sid', $sid)->update('users', ['transfer_date' => $data_transfer_log_update['employee_copy_date']]);
                //
                // ToDo if transfer then complynet status update pending
            }


            // Check its current status then update in user primary data
            if ($this->company_model->check_for_main_status_update($sid, $status_id)) {
                //
                if ($status != 9) {
                    $this->company_model->change_terminate_user_status($sid, $data_to_update);
                    //
                    $employeeStatus = $data_to_update['active'] == 1 ? "active" : "deactive";
                    changeComplynetEmployeeStatus($sid, $employeeStatus);
                }
            }
            //
            $this->session->set_flashdata('message', '<b>Success:</b> Status Updated Successfully!');
            redirect(base_url('manage_admin/employers/EmployeeStatusDetail/' . $sid), 'refresh');
        }
    }

    public function employee_documents($sid)
    {
        $redirect_url = 'manage_admin';
        $function_name = 'AssignBulkDocuments';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        // Param2: Redirect URL, Param3: Function Name
        check_access_permissions($security_details, $redirect_url, $function_name);
        //
        $employee_detail = $this->company_model->get_details($sid, 'employer');
        $company_detail = $this->company_model->get_details($employee_detail[0]['parent_sid'], 'company');
        $company_sid = $company_detail[0]['sid'];
        $this->data['company_sid'] = $company_sid;
        $pp_flag = 1;
        $user_sid = $sid;
        $user_type = "employee";
        //
        // $active_groups = array();
        $in_active_groups = array();
        $group_ids = array();
        $group_docs = array();
        $document_ids = array();
        // 
        $documents = $this->company_model->get_all_assign_documents($company_sid, $sid);
        //
        $this->data['page_title'] = 'Assigned Documents';
        $this->data['companySid'] = $company_sid;
        $this->data['companyName'] = $company_detail[0]['CompanyName'];
        $this->data['employeeName'] = getUserNameBySID($sid);
        $this->data['documents'] = $documents;
        //
        $categories = $this->hr_documents_management_model->get_all_documents_category($company_sid);
        //
        $active_categories = [];
        //
        if (!empty($categories)) {
            foreach ($categories as $key => $category) {
                $document_status = $this->hr_documents_management_model->is_document_assign_2_category($category['sid']);
                $categories[$key]['document_status'] = $document_status;
                $category_status = $category['status'];
                $category_sid = $category['sid'];
                $category_ids[] = $category_sid;
                $category_documents = $this->hr_documents_management_model->get_all_documents_in_category($category_sid, 0);

                if ($category_status) {
                    $active_categories[] = array(
                        'sid' => $category_sid,
                        'name' => $category['name'],
                        'sort_order' => $category['sort_order'],
                        'description' => $category['description'],
                        'created_date' => $category['created_date'],
                        'documents_count' => count($category_documents),
                        'documents' => $category_documents
                    );
                } else {
                    $in_active_categories[] = array(
                        'sid' => $category_sid,
                        'name' => $category['name'],
                        'sort_order' => $category['sort_order'],
                        'description' => $category['description'],
                        'created_date' => $category['created_date'],
                        'documents_count' => count($category_documents),
                        'documents' => $category_documents
                    );
                }
            }
        }

        // 
        $this->data['active_categories'] = $active_categories;
        //
        $i9_form = $this->hr_documents_management_model->fetch_form('i9', $user_type, $user_sid);
        $w9_form = $this->hr_documents_management_model->fetch_form('w9', $user_type, $user_sid);
        $w4_form = $this->hr_documents_management_model->fetch_form('w4', $user_type, $user_sid);

        $this->data['i9_form'] = $i9_form;
        $this->data['w9_form'] = $w9_form;
        $this->data['w4_form'] = $w4_form;

        $EEVDocument = array();
        // _e($w4_form, true, true);
        if (!empty($w4_form) && $w4_form['user_consent'] == 1) {
            $EEVDocument['w4']['title'] = "W4 Fillable";
            $EEVDocument['w4']['assign_date'] = $w4_form['sent_date'];
            $EEVDocument['w4']['sign_date'] = $w4_form['signature_timestamp'];
            $EEVDocument['w4']['url'] = base_url("form_w4/download_w4_form_2020/employee") . "/" . $user_sid;
        }
        //
        $this->data['EEVDocument'] = $EEVDocument;

        $assigned_sids                          = array();
        $no_action_required_sids                = array();
        $completed_sids                         = array();
        $completed_documents                    = array();
        $signed_documents                       = array();
        $signed_document_sids                   = array();
        $completed_document_sids                = array();
        $no_action_required_documents           = array();
        $no_action_required_payroll_documents   = array();
        $payroll_documents_sids                 = array();
        $completed_offer_letter                 = array();
        $completed_payroll_documents            = array();
        $user_completed_payroll_documents       = array();



        $assigned_documents = $documents;
        // _e($assigned_documents, true, true);
        $assigned_offer_letters = $this->hr_documents_management_model->get_assigned_offers($company_sid, $user_type, $user_sid);

        // echo "<pre>";
        // print_r($active_documents);
        // die();

        foreach ($assigned_documents as $key => $assigned_document) {
            $is_magic_tag_exist = 0;
            $is_document_completed = 0;
            $is_document_authorized = 0;
            $authorized_sign_status = 0;

            if (!empty($assigned_document['document_description']) && ($assigned_document['document_type'] == 'generated' || $assigned_document['document_type'] == 'hybrid_document')) {
                $document_body = $assigned_document['document_description'];
                // $magic_codes = array('{{signature}}', '{{signature_print_name}}', '{{inital}}', '{{sign_date}}', '{{short_text}}', '{{text}}', '{{text_area}}', '{{checkbox}}', 'select');
                $magic_codes = array('{{signature}}', '{{inital}}', '{{short_text_required}}', '{{text_required}}', '{{text_area_required}}', '{{checkbox_required}}');

                if (str_replace($magic_codes, '', $document_body) != $document_body) {
                    $is_magic_tag_exist = 1;
                }

                if (str_replace('{{authorized_signature}}', '', $document_body) != $document_body) {

                    $assign_on = date("Y-m-d", strtotime($assigned_document['assigned_date']));
                    $compare_date = date("Y-m-d", strtotime('2020-03-04'));

                    // if (!empty($assigned_document['form_input_data'] || $assign_on >= $compare_date )) {
                    if ($assign_on >= $compare_date || !empty($assigned_document['form_input_data'])) {
                        $is_document_authorized = 1;
                    }

                    // if ($assigned_document['user_consent'] == 1 && !empty($assigned_document['authorized_signature'])) {
                    if (!empty($assigned_document['authorized_signature'])) {
                        $authorized_sign_status = 1;
                    } else {
                        $authorized_sign_status = 0;
                    }
                }
            }

            $assigned_documents[$key]['is_document_authorized'] = $assigned_document['is_document_authorized'] = $is_document_authorized;
            $assigned_documents[$key]['authorized_sign_status'] = $assigned_document['authorized_sign_status'] = $authorized_sign_status;

            if ($assigned_document['document_sid'] == 0) {
                $doc_visible_check = $this->hr_documents_management_model->get_manual_doc_visible_payroll_check($assigned_document['sid']);
                $assigned_document['visible_to_payroll'] = $doc_visible_check;
            }

            $payroll_sids = $this->hr_documents_management_model->get_payroll_documents_sids();
            $documents_management_sids = $payroll_sids['documents_management_sids'];
            $documents_assigned_sids = $payroll_sids['documents_assigned_sids'];

            if (in_array($assigned_document['document_sid'], $documents_management_sids)) {
                $assigned_document['pay_roll_catgory'] = 1;
            } else if (in_array($assigned_document['sid'], $documents_assigned_sids)) {
                $assigned_document['pay_roll_catgory'] = 1;
            } else {
                $assigned_document['pay_roll_catgory'] = 0;
            }

            if ($assigned_document['document_type'] != 'offer_letter') {
                if ($assigned_document['status'] == 1) {
                    if ($assigned_document['acknowledgment_required'] || $assigned_document['download_required'] || $assigned_document['signature_required'] || $is_magic_tag_exist) {

                        if ($assigned_document['acknowledgment_required'] == 1 && $assigned_document['download_required'] == 1 && $assigned_document['signature_required'] == 1) {
                            if ($assigned_document['uploaded'] == 1) {
                                $is_document_completed = 1;
                            } else {
                                $is_document_completed = 0;
                            }
                        } else if ($assigned_document['acknowledgment_required'] == 1 && $assigned_document['download_required'] == 1) {
                            if ($is_magic_tag_exist == 1) {
                                if ($assigned_document['uploaded'] == 1) {
                                    $is_document_completed = 1;
                                } else {
                                    $is_document_completed = 0;
                                }
                            } else if ($assigned_document['uploaded'] == 1) {
                                $is_document_completed = 1;
                            } else if ($assigned_document['acknowledged'] == 1 && $assigned_document['downloaded'] == 1) {
                                $is_document_completed = 1;
                            } else {
                                $is_document_completed = 0;
                            }
                        } else if ($assigned_document['acknowledgment_required'] == 1 && $assigned_document['signature_required'] == 1) {
                            if ($assigned_document['uploaded'] == 1) {
                                $is_document_completed = 1;
                            } else {
                                $is_document_completed = 0;
                            }
                        } else if ($assigned_document['download_required'] == 1 && $assigned_document['signature_required'] == 1) {
                            if ($assigned_document['uploaded'] == 1) {
                                $is_document_completed = 1;
                            } else {
                                $is_document_completed = 0;
                            }
                        } else if ($assigned_document['acknowledgment_required'] == 1) {
                            if ($assigned_document['acknowledged'] == 1) {
                                $is_document_completed = 1;
                            } else if ($assigned_document['uploaded'] == 1) {
                                $is_document_completed = 1;
                            } else {
                                $is_document_completed = 0;
                            }
                        } else if ($assigned_document['download_required'] == 1) {
                            if ($assigned_document['downloaded'] == 1) {
                                $is_document_completed = 1;
                            } else if ($assigned_document['uploaded'] == 1) {
                                $is_document_completed = 1;
                            } else {
                                $is_document_completed = 0;
                            }
                        } else if ($assigned_document['signature_required'] == 1) {
                            if ($assigned_document['uploaded'] == 1) {
                                $is_document_completed = 1;
                            } else {
                                $is_document_completed = 0;
                            }
                        } else if ($is_magic_tag_exist == 1) {
                            if ($assigned_document['user_consent'] == 1) {
                                $is_document_completed = 1;
                            } else {
                                $is_document_completed = 0;
                            }
                        }

                        if ($is_document_completed > 0) {
                            if ($assigned_document['pay_roll_catgory'] == 0) {

                                $signed_document_sids[] = $assigned_document['document_sid'];
                                $signed_documents[] = $assigned_document;
                                unset($assigned_documents[$key]);
                            } else if ($assigned_document['pay_roll_catgory'] == 1) {
                                $signed_document_sids[] = $assigned_document['document_sid'];
                                $completed_payroll_documents[] = $assigned_document;
                                unset($assigned_documents[$key]);
                            }
                        } else {
                            if ($assigned_document['pay_roll_catgory'] == 1) {
                                $uncompleted_payroll_documents[] = $assigned_document;
                                unset($assigned_documents[$key]);
                            }

                            $assigned_sids[] = $assigned_document['document_sid'];
                        }
                    } else {
                        if ($is_document_authorized == 1) {
                            //
                            if ($authorized_sign_status == 1) {
                                if ($assigned_document['pay_roll_catgory'] == 0) {
                                    $signed_document_sids[] = $assigned_document['document_sid'];
                                    $signed_documents[] = $assigned_document;
                                    unset($assigned_documents[$key]);
                                } else if ($assigned_document['pay_roll_catgory'] == 1) {
                                    $signed_document_sids[] = $assigned_document['document_sid'];
                                    $completed_payroll_documents[] = $assigned_document;
                                    unset($assigned_documents[$key]);
                                }
                            } else {
                                if ($assigned_document['pay_roll_catgory'] == 1) {
                                    $uncompleted_payroll_documents[] = $assigned_document;
                                    unset($assigned_documents[$key]);
                                }
                            }
                            //
                            $assigned_sids[] = $assigned_document['document_sid'];
                            //
                        } else if ($assigned_document['pay_roll_catgory'] == 0) {
                            $assigned_sids[] = $assigned_document['document_sid'];
                            $no_action_required_sids[] = $assigned_document['document_sid'];
                            $no_action_required_documents[] = $assigned_document;
                            unset($assigned_documents[$key]);
                        } else if ($assigned_document['pay_roll_catgory'] == 1) {
                            if ($assigned_document['user_consent'] == 1 && $assigned_document['document_sid'] == 0) {
                                $no_action_required_payroll_documents[] = $assigned_document;
                                unset($assigned_documents[$key]);
                            }
                        }
                    }
                } else {
                    $revoked_sids[] = $assigned_document['document_sid'];
                }
            }
        }

        $current_assigned_offer_letter = $this->hr_documents_management_model->get_current_assigned_offer_letter($company_sid, $user_type, $user_sid);

        if (!empty($current_assigned_offer_letter)) {
            if ($current_assigned_offer_letter[0]['user_consent'] == 1) {
                $completed_offer_letter = $current_assigned_offer_letter;
            }
        }

        // Check for authorize tag
        if (sizeof($completed_offer_letter)) {
            //
            $completed_offer_letter[0]['is_document_authorized'] = 0;
            $completed_offer_letter[0]['authorized_sign_status'] = 0;
            //
            if (str_replace('{{authorized_signature}}', '', $completed_offer_letter[0]['document_description']) != $completed_offer_letter[0]['document_description']) {
                $assign_on = date("Y-m-d", strtotime($completed_offer_letter[0]['assigned_date']));
                $compare_date = date("Y-m-d", strtotime('2020-03-04'));

                if ($assign_on >= $compare_date || !empty($completed_offer_letter[0]['form_input_data'])) {
                    $completed_offer_letter[0]['is_document_authorized'] = 1;
                }

                if (!empty($completed_offer_letter[0]['authorized_signature'])) {
                    $completed_offer_letter[0]['authorized_sign_status'] = 1;
                }
            }
        }

        $this->data['CompletedGeneralDocuments'] = $this->hr_documents_management_model->getGeneralDocuments(
            $user_sid,
            'employee',
            $company_sid,
            'completed'
        );

        $this->data['w4_form_uploaded'] = $this->hr_documents_management_model->get_form_uploaded($user_sid, 'w4');
        $this->data['w9_form_uploaded'] = $this->hr_documents_management_model->get_form_uploaded($user_sid, 'w9');
        $this->data['i9_form_uploaded'] = $this->hr_documents_management_model->get_form_uploaded($user_sid, 'i9');


        $categorized_docs = $this->hr_documents_management_model->categrize_documents($company_sid, $signed_documents, $no_action_required_documents, 1);

        $this->data['categories_no_action_documents'] = $categorized_docs['categories_no_action_documents'];
        $this->data['categories_documents_completed'] =  $categorized_docs['categories_documents_completed'];
        $this->data['no_action_document_categories'] =  $categorized_docs['no_action_document_categories'];

        // 

        $eeo_form_info = $this->hr_documents_management_model->get_eeo_form_info($user_sid, $user_type);
        $this->data['eeo_form_info'] = $eeo_form_info;


        $this->data['pp_flag']                                = 1;
        $this->data['completed_sids']                         = $completed_sids; // completed Documemts Ids
        $this->data['signed_document_sids']                   = $signed_document_sids; // signed Documemts Ids
        $this->data['signed_documents']                       = $signed_documents; // signed Documemts
        $this->data['completed_document_sids']                = $completed_document_sids; // completed Documemts Ids
        $this->data['completed_documents']                    = $completed_documents; // completed Documemts
        $this->data['no_action_required_documents']           = $no_action_required_documents; // no action required documents
        $this->data['no_action_required_payroll_documents']   = $no_action_required_payroll_documents;
        $this->data['assigned_sids']                          = $assigned_sids;
        $this->data['user_type']                              = $user_type;
        $this->data['user_sid']                               = $user_sid;

        // $this->data['assigned_documents']             = $assigned_documents; // not completed Documemts
        $this->data['completed_offer_letter']         = $completed_offer_letter;
        $this->data['completed_payroll_documents']    = $completed_payroll_documents;
        $this->data['payroll_documents_sids']         = $payroll_documents_sids;

        $this->data['downloadDocumentData'] = $this->hr_documents_management_model->get_last_download_document_name($company_sid, $user_sid, $user_type, 'single_download');


        // _e($this->data, true, true);

        if ($employee_detail) {
            $this->data['employee_detail'] = $employee_detail[0];
        } else {
            $this->session->set_flashdata('message', 'Employer does not exists!');
            redirect('manage_admin/employers', 'refresh');
        }

        $this->render('manage_admin/company/employee_documents');
    }

    /**
     * Send back json
     *
     * @param $array Array
     */
    private function response($array)
    {
        header('Content-Type: application/json');
        echo json_encode($array);
        exit(0);
    }



    public function employerTransferLog($employee_sid = null)
    {
        $redirect_url = 'manage_admin';
        $function_name = 'edit_employers';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name

        if ($employee_sid != null) {
            $this->data['page_title'] = 'Employee Transfer History';
            $security_access_levels = $this->company_model->get_security_access_levels();
            $this->data['security_access_levels'] = $security_access_levels;

            $copyTransferEmployee = $this->company_model->checkIsEmployeeTransferred($employee_sid);

            $record["copyTransferEmployee"] = $copyTransferEmployee;
            //
            $resp['Status'] = true;
            $resp['Msg'] = 'Proceed.';
            $resp['Data'] = $this->load->view('manage_admin/company/employers_transfer_log', $record, true);

            res($resp);
        }
    }
}
