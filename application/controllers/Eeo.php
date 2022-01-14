<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Eeo extends Public_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('eeo_model');
        $this->load->model('dashboard_model');
        $this->load->model('application_tracking_system_model');
        $this->load->model('hr_documents_management_model');
        $this->load->library('pagination');
    }

    public function index($keyword = 'all', $opt_type = 'no', $start_date = null, $end_date = null)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'eeo'); // Param2: Redirect URL, Param3: Function Name
            $company_id = $data['session']['company_detail']['sid'];
            $keyword = urldecode($keyword);

            $display_start_day = '';
            $display_end_day = '';

            if ($start_date != null && $start_date != 'all') {
                $display_start_day = $start_date;
                $start_date = DateTime::createFromFormat('m-d-Y', $start_date)->format('Y-m-d 00:00:00');
            } else {
                $start_date = new DateTime();
                $display_start_day = $start_date->format('m-01-Y');
                $start_date = $start_date->format('Y-m-1 00:00:00');
            }

            if ($end_date != null && $end_date != 'all') {
                $display_end_day = $end_date;
                $end_date = DateTime::createFromFormat('m-d-Y', $end_date)->format('Y-m-d 23:59:59');
            } else {
                $end_date = new DateTime();
                $display_end_day = $end_date->format('m-t-Y');
                $end_date = $end_date->format('Y-m-t 23:59:59');
            }

            $records_per_page = PAGINATION_RECORDS_PER_PAGE;
            $page = ($this->uri->segment(6)) ? $this->uri->segment(6) : 0;
            $my_offset = 0;

            if ($page > 1) {
                $my_offset = ($page - 1) * $records_per_page;
            }


            $total_records = $this->eeo_model->get_all_eeo_applicants($keyword, $opt_type, $start_date, $end_date, $company_id, $records_per_page, $my_offset, true);
            $eeo_candidates = $this->eeo_model->get_all_eeo_applicants($keyword, $opt_type, $start_date, $end_date, $company_id, $records_per_page, $my_offset);
            $data['eeo_candidates'] = $eeo_candidates;

            $start_date = DateTime::createFromFormat('Y-m-d H:i:s', $start_date)->format('m-d-Y');
            $end_date = DateTime::createFromFormat('Y-m-d H:i:s', $end_date)->format('m-d-Y');

            $baseUrl = base_url('eeo') . '/' . urlencode($keyword) . '/' . $opt_type . '/' . urlencode($start_date) . '/' . urlencode($end_date);

            $uri_segment = 6;

            $config = array();
            $config['base_url'] = $baseUrl;
            $config['total_rows'] = $total_records;
            $config['per_page'] = $records_per_page;
            $config['uri_segment'] = $uri_segment;
            $config['num_links'] = 4;
            $config['use_page_numbers'] = true;
            $config['full_tag_open'] = '<nav class="hr-pagination"><ul>';
            $config['full_tag_close'] = '</ul></nav><!--pagination-->';
            $config['first_link'] = '&laquo; First';
            $config['first_tag_open'] = '<li class="prev page">';
            $config['first_tag_close'] = '</li>';
            $config['last_link'] = 'Last &raquo;';
            $config['last_tag_open'] = '<li class="next page">';
            $config['last_tag_close'] = '</li>';
            $config['next_link'] = '<i class="fa fa-angle-right"></i>';
            $config['next_tag_open'] = '<li class="next page">';
            $config['next_tag_close'] = '</li>';
            $config['prev_link'] = '<i class="fa fa-angle-left"></i>';
            $config['prev_tag_open'] = '<li class="prev page">';
            $config['prev_tag_close'] = '</li>';
            $config['cur_tag_open'] = '<li class="active"><a href="">';
            $config['cur_tag_close'] = '</a></li>';
            $config['num_tag_open'] = '<li class="page">';
            $config['num_tag_close'] = '</li>';
            $this->pagination->initialize($config);

            $data['links'] = $this->pagination->create_links();

            $data['current_page'] = $page;
            $data['from_records'] = $my_offset == 0 ? 1 : $my_offset;
            $data['to_records'] = $total_records < $records_per_page ? $total_records : $my_offset + $records_per_page;
            $data['total_records'] = $total_records;

            $data['title'] = 'EEO form Applicants';

            $data['keyword'] = $keyword;
            $data['startdate'] = $display_start_day;
            $data['enddate'] = $display_end_day;
            $data['opt_type'] = $opt_type;

            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/eeo_applicants_new');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function export_excel()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'eeo'); // Param2: Redirect URL, Param3: Function Name
            $company_id = $data['session']['company_detail']['sid'];

            $keyword = $_POST['keyword'];
            $start_date = $_POST['startdate'];
            $end_date = $_POST['enddate'];
            $opt_type = $_POST['opt_type'];

            $keyword = empty($keyword) ? 'all' : $keyword;
            $start_date = empty($start_date) ? 'all' : $start_date;
            $end_date = empty($end_date) ? 'all' : $end_date;
            $opt_type = empty($opt_type) ? 'all' : $opt_type;

            $display_start_day = 'all';
            $display_end_day = 'all';

            if ($start_date != null && $start_date != 'all') {
                $display_start_day = $start_date;
                $start_date = DateTime::createFromFormat('m-d-Y', $start_date)->format('Y-m-d 00:00:00');
            } else {
                $start_date = new DateTime();
                $display_start_day = $start_date->format('m-01-Y');
                $start_date = $start_date->format('Y-m-1 00:00:00');
            }

            if ($end_date != null && $end_date != 'all') {
                $display_end_day = $end_date;
                $end_date = DateTime::createFromFormat('m-d-Y', $end_date)->format('Y-m-d 23:59:59');
            } else {
                $end_date = new DateTime();
                $display_end_day = $end_date->format('m-t-Y');
                $end_date = $end_date->format('Y-m-t 23:59:59');
            }

            $eeo_candidates = $this->eeo_model->get_all_eeo_applicants($keyword, $opt_type, $start_date, $end_date, $company_id, null, 0, false);

            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=eeoreport_' . $opt_type . '-' . date('Y-m-d-H-i-s') . '.csv');

            $output = fopen('php://output', 'w');

            fputcsv($output, array('Name', 'Opt Out', 'Date', 'IP Address', 'US Citizen', 'Visa Status', 'Group Status', 'Veteran', 'Disability', 'Gender', 'Applicant Type', 'Applicant Source'));

            if (sizeof($eeo_candidates) > 0) {
                foreach ($eeo_candidates as $candidate) {
                    $input = array();
                    $input['name'] = ucwords($candidate['first_name']) . ' ' . ucwords($candidate['last_name']);
                    $input['opt_out'] = ucwords($opt_type);
                    // $input['date_applied'] = date_with_time($candidate['date_applied']);
                    $input['date_applied'] = reset_datetime(array('datetime' => $candidate['date_applied'], '_this' => $this, 'from_format' => 'Y-m-d H:i:s'));
                    $input['ip_address'] = $candidate['ip_address'];
                    $input['us_citizen'] = $candidate['us_citizen'];
                    $input['visa_status'] = $candidate['visa_status'];
                    $input['group_status'] = $candidate['group_status'];
                    $input['veteran'] = $candidate['veteran'];
                    $input['disability'] = $candidate['disability'];
                    $input['gender'] = $candidate['gender'];
                    $input['applicant_type'] = $candidate['applicant_type'];
                    $input['applicant_source'] = $candidate['applicant_source'];
                    fputcsv($output, $input);
                }
                // insert into the csv export file
            }


            if ($keyword != null) {
                $keyword = urlencode($keyword);
            } else {
                $keyword = 'all';
            }

            redirect(base_url('eeo') . '/' . $keyword . '/' . $opt_type . '/' . $display_start_day . '/' . $display_end_day, "refresh");
        } else {
            redirect(base_url('login'), 'refresh');
        }
    }

    public function get_all_candidates($company_id, $records_per_page = null, $my_offset = null)
    {
        $manual_candidates_query = $this->eeo_model->get_eeo_candidates($company_id, $records_per_page, $my_offset);
        $eeo_candidates = array();

        if (sizeof($manual_candidates_query) > 0) {
            foreach ($manual_candidates_query as $manual_row) {
                $manual_job_title = $this->eeo_model->get_job_title_by_type($manual_row['job_sid'], $manual_row['applicant_type'], $manual_row['desired_job_title']);

                if (isset($manual_row['eeo_form']) && $manual_row['eeo_form'] == 'Yes') {
                    $eeo_candidates[] = array(
                        "sid" => $manual_row['applicant_sid'],
                        "job_sid" => $manual_row['job_sid'],
                        "Title" => $manual_job_title,
                        "first_name" => $manual_row['first_name'],
                        "last_name" => $manual_row['last_name'],
                        "eeo_form" => $manual_row['eeo_form'],
                        "application_sid" => $manual_row['application_sid'],
                        "us_citizen" => $manual_row['us_citizen'],
                        "visa_status" => $manual_row['visa_status'],
                        "group_status" => $manual_row['group_status'],
                        "veteran" => $manual_row['veteran'],
                        "disability" => $manual_row['disability'],
                        "gender" => $manual_row['gender'],
                        "date_applied" => $manual_row['date_applied']);
                } else {
                    $eeo_candidates[] = array(
                        "sid" => $manual_row['applicant_sid'],
                        "job_sid" => $manual_row['job_sid'],
                        "Title" => $manual_job_title,
                        "first_name" => $manual_row['first_name'],
                        "last_name" => $manual_row['last_name'],
                        "eeo_form" => $manual_row['eeo_form'],
                        "date_applied" => $manual_row['date_applied']);
                }
            }
        }
        return $eeo_candidates;
    }

    public function form($type = null, $sid = null, $job_list_sid = null)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            //check_access_permissions($security_details, 'appearance', 'customize_appearance'); // no need to check in this Module as Dashboard will be available to all
            //getting userdata from DB

            $employer_sid = $data["session"]["employer_detail"]["sid"];
            $company_sid = $data["session"]["company_detail"]["sid"];

            if ($sid == null && $type == null) {
                $sid = $employer_sid;
                $left_navigation = 'manage_employer/employee_management/profile_right_menu_personal';
                $data['title'] = 'E.E.O.C. Form';
                $reload_location = 'eeo/form';
                $type = 'employee';
                $data["return_title_heading"] = "My Profile";
                $data["return_title_heading_link"] = base_url('my_profile');
                $cancel_url = 'my_profile/';
                $data['cancel_url'] = $cancel_url;
                // getting applicant ratings - getting average rating of applicant
                $data['applicant_average_rating'] = $this->application_tracking_system_model->getApplicantAverageRating($employer_sid, 'employee');

                $data["employer"] = $data['session']['employer_detail'];
                $load_view = check_blue_panel_status(false, 'self');

            } elseif ($type == 'employee') {
                check_access_permissions($security_details, 'employee_management', 'employee_eeoc_form');  // Param2: Redirect URL, Param3: Function Name
                $data = employee_right_nav($sid);
                $data['security_details'] = $security_details;
                $left_navigation = 'manage_employer/employee_management/profile_right_menu_employee_new';
                $data['title'] = 'E.E.O.C. Form';
                $reload_location = 'eeo/employee/' . $sid;
                $data["return_title_heading"] = "Employee Profile";
                $data["return_title_heading_link"] = base_url() . 'employee_profile/' . $sid;
                $cancel_url = 'employee_profile/' . $sid;
                // getting applicant ratings - getting average rating of applicant
                $data['applicant_average_rating'] = $this->application_tracking_system_model->getApplicantAverageRating($sid, 'employee');
                $data['cancel_url'] = $cancel_url;

                $employerDetails = $this->dashboard_model->getEmployerDetail($sid);
                $data["employer"] = $employerDetails;
                $load_view = check_blue_panel_status(false, $type);
            } elseif ($type == 'applicant') {
                check_access_permissions($security_details, 'application_tracking', 'applicant_eeoc_form');  // Param2: Redirect URL, Param3: Function Name
                $data = applicant_right_nav($sid);
                $data['security_details'] = $security_details;
                $left_navigation = 'manage_employer/application_tracking_system/profile_right_menu_applicant';
                $data['title'] = 'Applicant E.E.O.C. Form';
                $reload_location = 'direct_deposit/applicant/' . $sid . '/' . $job_list_sid;
                $cancel_url = 'applicant_profile/' . $sid . '/' . $job_list_sid;
                $data["return_title_heading"] = "Applicant Profile";
                $data["return_title_heading_link"] = base_url() . 'applicant_profile/' . $sid . '/' . $job_list_sid;
                $data['applicant_average_rating'] = $this->application_tracking_system_model->getApplicantAverageRating($sid, 'applicant'); //getting average rating of applicant
                $data['cancel_url'] = $cancel_url;

                $applicant_info = $this->dashboard_model->get_applicants_details($sid);
                $data['company_background_check'] = checkCompanyAccurateCheck($company_sid);

                $data['kpa_onboarding_check'] = checkCompanyKpaOnboardingCheck($company_sid);

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
                    'user_type' => 'Applicant'
                );

                $data['applicant_average_rating'] = $this->application_tracking_system_model->getApplicantAverageRating($applicant_info['sid'], 'applicant'); //getting average rating of applicant
                $data['employer'] = $data_employer;
                $load_view = check_blue_panel_status(false, $type);
            }

            $data['employee'] = $data['session']['employer_detail'];

            $data['left_navigation'] = $left_navigation;

            $data['users_type'] = $type;
            $data['users_sid'] = $sid;
            $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;
            $data['job_list_sid'] = $job_list_sid;

            $eeoc = $this->eeo_model->get_latest_eeo_record($type, $sid);
            $data['eeoc'] = $eeoc;

            $eeoc_status = $this->eeo_model->get_eeo_form_status($type, $sid);
            $data['eeoc_status'] = $eeoc_status;

            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim');
            $data['load_view'] = $load_view;
            if ($this->form_validation->run() == false) {
                //load views

                // if($sid == $employer_sid) {
                //   $this->load->view('onboarding/on_boarding_header', $data);
                //   $this->load->view('onboarding/eeoc_form');
                //   $this->load->view('onboarding/on_boarding_footer');
                // } else {
                    $this->load->view('main/header', $data);
                    $this->load->view('eeo/form');
                    $this->load->view('main/footer');
                // }
            } else {
                $perform_action = $this->input->post('perform_action');
                switch ($perform_action) {
                    case 'update_eeo_data':
                        $eeoc_form_status = $this->input->post('eeoc_form_status');

                        $users_type = $this->input->post('users_type');
                        $users_sid = $this->input->post('users_sid');
                        $us_citizen = $this->input->post('us_citizen');
                        $visa_status = $this->input->post('visa_status');
                        $group_status = $this->input->post('group_status');
                        $veteran = $this->input->post('veteran');
                        $disability = $this->input->post('disability');
                        $gender = $this->input->post('gender');

                        $data_to_update = array();
                        $data_to_update['eeo_form'] = $eeoc_form_status;

                        $this->eeo_model->update_eeo_form_status($users_type, $users_sid, $eeoc_form_status);

                        if ($eeoc_form_status == 'Yes') {

                            $data_to_insert = array();
                            $data_to_insert['users_type'] = $users_type;
                            $data_to_insert['application_sid'] = $users_sid;
                            $data_to_insert['us_citizen'] = $us_citizen;
                            $data_to_insert['visa_status'] = $visa_status;
                            $data_to_insert['group_status'] = $group_status;
                            $data_to_insert['veteran'] = $veteran;
                            $data_to_insert['disability'] = $disability;
                            $data_to_insert['gender'] = $gender;
                            $data_to_insert['is_latest'] = 1;

                            $this->eeo_model->insert_eeo_record($users_type, $users_sid, $data_to_insert);

                            $this->session->set_flashdata('message', '<strong>Success</strong> E.E.O.C. Form Updated!');

                        }

                        if ($sid == $employer_sid) {
                            redirect('eeo/form', 'refresh');
                        } else if ($users_type == 'employee') {
                            redirect('eeo/form/employee/' . $sid, 'refresh');
                        } else if ($users_type == 'application') {
                            redirect('eeo/form/applicant/' . $sid, 'refresh');
                        }

                        break;
                }
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function EEOC_form($user_type, $user_sid, $jobs_listing = null)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'eeo'); // Param2: Redirect URL, Param3: Function Name
            $company_sid = $data['session']['company_detail']['sid'];
            //
            if ($data['session']['portal_detail']['eeo_form_status'] != 1) {
                $this->session->set_flashdata('message', '<strong>Error:</strong> E.E.O.C Form Disable!');
                //
                if ($user_type == 'applicant') {
                    redirect('applicant_profile/' . $user_sid . '/' . $job_list_sid, 'refresh');
                } else {
                    redirect('employee_profile/'. $user_sid, 'refresh');
                }
            }
            //
            switch ($user_type) {
                case 'employee':
                    $user_info = $this->hr_documents_management_model->get_employee_information($company_sid, $user_sid);
                    
                    if (empty($user_info)) {
                        $this->session->set_flashdata('message', '<strong>Error:</strong> Employee Not Found!');
                        redirect('employee_management', 'refresh');
                    }

                    $data["user_name"] = getUserNameBySID($user_sid);

                    $data = employee_right_nav($user_sid, $data);
                    $left_navigation = 'manage_employer/employee_management/profile_right_menu_employee_new';
                    $data['applicant_average_rating'] = $this->hr_documents_management_model->getApplicantAverageRating($user_sid, 'employee'); // getting applicant ratings - getting average rating of applicant
                    $data['employer'] = $this->hr_documents_management_model->get_company_detail($user_sid);
                    $eeo_form_info = $this->hr_documents_management_model->get_user_eeo_form_info($user_sid);
                    $data['eeo_form_info'] = $eeo_form_info;
                    $data['left_navigation'] = $left_navigation;
                    break;
                case 'applicant':
                    $user_info = $this->hr_documents_management_model->get_applicant_information($company_sid, $user_sid);

                    if (empty($user_info)) {
                        $this->session->set_flashdata('message', '<strong>Error:</strong> Applicant Not Found!');
                        redirect('application_tracking_system/active/all/all/all/all', 'refresh');
                    }

                    $data = applicant_right_nav($user_sid, $jobs_listing);
                    $left_navigation = 'manage_employer/application_tracking_system/profile_right_menu_applicant';
                    $applicant_info = $this->hr_documents_management_model->get_applicants_details($user_sid);
                    $eeo_form_status = $this->hr_documents_management_model->get_eeo_form_status($user_sid);
                    $eeo_form_info = $this->hr_documents_management_model->get_user_eeo_form_info($user_sid);
                    $data['eeo_form_status'] = $eeo_form_status;
                    $data['eeo_form_info'] = $eeo_form_info;

                    $data_employer = array('sid' => $applicant_info['sid'],
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
                        'user_type' => ucwords($user_type));

                    $data["user_name"] = $applicant_info['first_name']." ".$applicant_info['last_name'];

                    $data['applicant_average_rating'] = $this->hr_documents_management_model->getApplicantAverageRating($user_sid, 'applicant'); //getting average rating of applicant
                    $data['employer'] = $data_employer;
                    $data['company_sid'] = $company_sid;
                    $data['employer_sid'] = $applicant_info['sid'];
                    $data['left_navigation'] = $left_navigation;
                    break;
            }
            //
            $data['title'] = 'EEOC Form';
            $data['user_type'] = $user_type;
            $data['user_sid'] = $user_sid;
            $data['job_list_sid'] = $jobs_listing;
            //
            $this->load->view('main/header', $data);
            $this->load->view('eeo/user_eeoc');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }
}