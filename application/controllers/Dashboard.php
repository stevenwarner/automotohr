<?php defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends Public_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('dashboard_model');
        $this->load->model('timeoff_model');
        $this->load->model('job_approval_rights_model');
        $this->load->model('tickets_model');
        $this->load->model('onboarding_model');
        $this->load->model('hr_documents_management_model');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        require_once(APPPATH . 'libraries/aws/aws.php');
        $this->load->library('pagination');
    }

    public function welcome()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $employer_id = $data["session"]["employer_detail"]["sid"];
            $data['title'] = "Welcome to " . STORE_NAME;
            $company_id = $data["session"]["company_detail"]["sid"];
            $data['domain_name'] = $this->dashboard_model->domain_name_by_company_id($company_id);
            $data['employee'] = $data['session']['employer_detail'];
            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/welcome');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function index()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session']                                                    = $this->session->userdata('logged_in');
            $employer_detail                                                    = $data['session']['employer_detail'];
            $company_detail                                                     = $data['session']['company_detail'];
            $data['access_level_plus']                                          = $data["session"]["employer_detail"]["access_level_plus"];
            $data['pay_plan_flag']                                              = $data["session"]["employer_detail"]["pay_plan_flag"];
            $security_sid                                                       = $employer_detail['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;
            //check_access_permissions($security_details, 'appearance', 'customize_appearance'); // no need to check in this Module as Dashboard will be available to all
            $employer_id                                                        = $employer_detail['sid'];
            $company_id                                                         = $company_detail['sid'];
            $data['employerData']                                               = $employer_detail;
            $loggedin_access_level                                              = $employer_detail['access_level'];
            $data['companyData']                                                = $company_detail;
            $data['companyData']['locationDetail']                              = db_get_state_name($data['companyData']['Location_State']);
            $jobs_approval_module_status                                        = $company_detail['has_job_approval_rights']; //get_job_approval_module_status($company_id);
            $applicant_approval_module_status                                   = $company_detail['has_applicant_approval_rights']; //get_applicant_approval_module_status($company_id);

            $data['EmsStatus'] = getCompanyEmsStatusBySid($company_id, false);
            if (check_blue_panel_status() && strtolower($loggedin_access_level) == 'employee') { //New Panel configuration

                // $configuration  = $this->onboarding_model->get_onboarding_configuration('employee', $employer_id);
                // $locations_data = $this->get_single_record_from_array($configuration, 'section', 'locations');
                // $timings_data   = $this->get_single_record_from_array($configuration, 'section', 'timings');
                // $people_data    = $this->get_single_record_from_array($configuration, 'section', 'people');
                // $items_data     = $this->get_single_record_from_array($configuration, 'section', 'items');
                // $links_data    = $this->get_single_record_from_array($configuration, 'section', 'links');
                // $assign_links  = $this->onboarding_model->onboarding_assign_useful_links($employer_id, $company_id, 'employee');

                // $locations = empty($locations_data) ? array() : $locations_data['items_details'];
                // $timings   = empty($timings_data) ? array() : $timings_data['items_details'];
                // $people    = empty($people_data) ? array() : $people_data['items_details'];
                // $items     = empty($items_data) ? array() : $items_data['items_details'];
                // $links  = empty($links_data) ? array() : $links_data['items_details'];
                // $data['locations']        = $locations;
                // $data['timings']          = $timings;
                // $data['people']           = $people;
                // $data['items']            = $items;
                // $data['links']            = $links;
                // $data['links']            = $assign_links;
                // $data['complete_steps']   = $this->onboarding_model->check_updated_sections($company_id, 'employee', $employer_id);
                // echo '<pre>'; print_r($data['complete_steps']); exit;






                ////////-----------------------------------------------------------------------------------------\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\


                $configuration                                                  = $this->onboarding_model->get_onboarding_configuration('employee', $employer_id);
                $sections_data                                                  = $this->get_single_record_from_array($configuration, 'section', 'sections');
                $locations_data                                                 = $this->get_single_record_from_array($configuration, 'section', 'locations');
                // Custom Location
                $custom_office_locations                                        = $this->onboarding_model->get_custom_office_records($company_id, $employer_id, 'employee', 'location', 1);
                $data['custom_office_locations']                                = $custom_office_locations;
                $timings_data                                                   = $this->get_single_record_from_array($configuration, 'section', 'timings');
                // Custom Timing
                $custom_office_timings                                          = $this->onboarding_model->get_custom_office_records($company_id, $employer_id, 'employee', 'timing');
                $data['custom_office_timings']                                  = $custom_office_timings;
                // Custom Useful Links
                $custom_useful_link                                             = $this->onboarding_model->get_custom_office_records($company_id, $employer_id, 'employee', 'useful_link');
                $data['custom_useful_link']                                     = $custom_useful_link;
                //
                $people_data                                                    = $this->get_single_record_from_array($configuration, 'section', 'people');
                $items_data                                                     = $this->onboarding_model->get_assigned_custom_office_record_sids($company_id, $employer_id, 'employee', 'item', 2); // fetch items from new table
                $ems_notification                                               = $this->onboarding_model->get_ems_notifications($company_id, $employer_id);
                $sections                                                       = empty($sections_data) ? array() : $sections_data['items_details'];
                $locations                                                      = empty($locations_data) ? array() : $locations_data['items_details'];
                $timings                                                        = empty($timings_data) ? array() : $timings_data['items_details'];
                $people                                                         = empty($people_data) ? array() : $people_data['items_details'];
                $assign_links                                                   = $this->onboarding_model->onboarding_assign_useful_links($employer_id, $company_id, 'employee');
                $welcome_video                                                      = $this->onboarding_model->get_onboarding_setup_welcome_video($company_id, $employer_id, 'employee'); // Welcome Videos
                $data['welcome_video']                                              = $welcome_video;
                $data['sections']                                               = $sections;
                $data['ems_notification']                                       = $ems_notification;
                $data['locations']                                              = $locations;
                $data['timings']                                                = $timings;
                $data['people']                                                 = $people;
                $data['items']                                                  = $items_data;
                $data['links']                                                  = $assign_links;
                $data['complete_steps']                                         = $this->onboarding_model->check_updated_sections($company_id, 'employee', $employer_id);
                $data['safety_sheet_flag']                                      = $this->dashboard_model->check_safety_data($company_id);
                $data['session']['safety_sheet_flag']                           = $data['safety_sheet_flag'];

                $data['holidayDates'] = $this->timeoff_model->getDistinctHolidayDates(array('companySid' => $company_id));

                $data['has_approval_access'] = false;

                // Check if the employee has
                // access for the Job Approval Management
                // if not then redirect to dashboard
                // only for access level employee
                // Check for employee Job Approval Management
                if (
                    $jobs_approval_module_status == 1 &&
                    $this->dashboard_model->check_employee_has_approval_rights($company_id, $employer_id) == 1
                ) { // Jobs Count By Approval Status
                    $data['all_unapproved_jobs_count'] = $this->dashboard_model->getJobsForEmployee($company_id, $employer_id, 'pending');
                    $data['all_approved_jobs_count']   = $this->dashboard_model->getJobsForEmployee($company_id, $employer_id, 'approved');
                    $data['all_rejected_jobs_count']   = $this->dashboard_model->getJobsForEmployee($company_id, $employer_id, 'rejected');
                    $data['has_approval_access'] = true;
                }




                ///////////-----------------------------------------------------------------------------------------\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

            } else { /* It is for Main Dashboard --- Start ---*/
                if (!isset($company_detail['has_task_management_rights'])) {
                    $task_management_module_status                              = $this->job_approval_rights_model->GetModuleStatus($company_id, 'tasks_management');
                } else {
                    $task_management_module_status                              = $company_detail['has_task_management_rights'];
                }

                $data['job_approval_module_status']                             = $jobs_approval_module_status;
                $data['applicant_approval_module_status']                       = $applicant_approval_module_status;

                if ($task_management_module_status == 1 && ($loggedin_access_level == 'Admin' || $loggedin_access_level = 'Hiring Manager')) {
                    $data['task_management_module_status']                      = 1;
                } else {
                    $data['task_management_module_status']                      = 0;
                }



                $by_date_today                                                  = true;
                $today_start                                                    = date('Y-m-d 00:00:00');
                // $today_start                                                    = get_current_datetime(array( 'to_format' => 'Y-m-d', 'extra' => ' 00:00:00', '_this' => $this));
                $today_end                                                      = date('Y-m-d 23:59:59');
                // $today_end                                                      = get_current_datetime(array( 'to_format' => 'Y-m-d', 'extra' => ' 23:59:59', '_this' => $this));
                $this_month_start                                               = date('Y-m-01 00:00:00');
                // $this_month_start                                               = get_current_datetime(array( 'to_format' => 'Y-m-01', 'extra' => ' 00:00:00', '_this' => $this));
                $this_month_end                                                 = date('Y-m-t 23:59:59');
                // $this_month_end                                                 = get_current_datetime(array( 'to_format' => 'Y-m-t', 'extra' => ' 23:59:59', '_this' => $this));
                //
                // _e($today_start, true);
                // _e($today_end, true);
                // _e($this_month_start, true);
                // _e($this_month_end, true, true);

                $eventCount                                                     = $this->dashboard_model->company_employee_events_count($company_id, $employer_id); //Events
                $eventCountToday                                                = $this->dashboard_model->company_employee_events_count($company_id, $employer_id, $today_start, $today_end);
                $data['eventCount']                                             = $eventCount;
                $data['eventCountToday']                                        = $eventCountToday; //count($all_company_events_today);
                $unreadMessageCount                                             = $this->dashboard_model->get_all_unread_messages_count($employer_id); //Messages
                $data['unreadMessageCount']                                     = $unreadMessageCount;
                $data['questionnairCount']                                      = $this->dashboard_model->companyQuestionnairCount($company_id); //getting total questionnaires
                $all_company_background_checks_count                            = $this->dashboard_model->get_all_company_background_checks_count($company_id); //Background Checks
                $all_company_background_checks_this_month_count                 = $this->dashboard_model->get_all_company_background_checks_count($company_id, $this_month_start, $this_month_end); //Background Checks
                $data['checks_total_count']                                     = $all_company_background_checks_count;
                $data['checks_monthly_count']                                   = $all_company_background_checks_this_month_count;
                $mydata                                                         = $this->dashboard_model->GetAllJobsCompanySpecificCount($company_id, '', 'rejected');

                if ($loggedin_access_level != 'Admin' && $jobs_approval_module_status == 1) {
                    $all_company_jobs                                           = $this->dashboard_model->get_all_company_jobs_count($company_id, $employer_id); //Jobs
                    $all_company_jobs_active                                    = $this->dashboard_model->get_all_company_jobs_count($company_id, $employer_id, 1);
                    $all_company_applications                                   = $this->dashboard_model->get_all_company_applicants_count($company_id, $employer_id); //Applications
                    $all_act_inact_applications                                 = $this->dashboard_model->get_all_active_inactive_applicants_count($company_id, $employer_id); //Applications
                    $all_company_applications_today                             = $this->dashboard_model->get_all_company_applicants_count($company_id, $employer_id, $today_start, $today_end);
                    // $this->filter_out_array_based_on_date($all_company_applications, 'date_applied', $today_start, $today_end);
                } else {
                    if ($loggedin_access_level != 'Admin') {
                        $all_company_jobs                                           = $this->dashboard_model->get_all_company_jobs_count($company_id, $employer_id); //Jobs
                        $all_company_jobs_active                                    = $this->dashboard_model->get_all_company_jobs_count($company_id, $employer_id, 1);
                        $all_company_applications                                   = $this->dashboard_model->get_all_company_applicants_count($company_id, $employer_id); //Applications
                        $all_act_inact_applications                                 = $this->dashboard_model->get_all_active_inactive_applicants_count($company_id, $employer_id); //Applications
                        $all_company_applications_today                             = $this->dashboard_model->get_all_company_applicants_count($company_id, $employer_id, $today_start, $today_end);
                    } else {
                        $all_company_jobs                                           = $this->dashboard_model->get_all_company_jobs_count($company_id); //Jobs
                        $all_company_jobs_active                                    = $this->dashboard_model->get_all_company_jobs_count($company_id, 0, 1);
                        $all_company_applications                                   = $this->dashboard_model->get_all_company_applicants_count($company_id); //Applications
                        $all_act_inact_applications                                 = $this->dashboard_model->get_all_active_inactive_applicants_count($company_id); //Applications
                        $all_company_applications_today                             = $this->dashboard_model->get_all_company_applicants_count($company_id, 0, $today_start, $today_end);
                    }
                }

                $data['jobCount']                                               = $all_company_jobs;
                $data['jobCountActive']                                         = $all_company_jobs_active;
                $data['applicants']                                             = $all_company_applications;
                $data['all_active_inactive_applicants']                         = $all_act_inact_applications;
                $data['applicants_today']                                       = $all_company_applications_today;

                $data['all_unapproved_jobs_count']                          = 0;
                $data['all_approved_jobs_count']                            = 0;
                $data['all_rejected_jobs_count']                            = 0;

                if ($jobs_approval_module_status == 1) { // Jobs Count By Approval Status
                    if (!in_array(
                        strtolower($data['session']['employer_detail']['access_level']),
                        array('employee', 'hiring manager', 'manager')
                    )) {
                        $data['all_unapproved_jobs_count'] = $this->dashboard_model->GetAllJobsCompanySpecificCount($company_id, '', 'pending');
                        $data['all_approved_jobs_count']   = $this->dashboard_model->GetAllJobsCompanySpecificCount($company_id, '', 'approved');
                        $data['all_rejected_jobs_count']   = $this->dashboard_model->GetAllJobsCompanySpecificCount($company_id, '', 'rejected');
                    } else {
                        $data['all_unapproved_jobs_count'] = $this->dashboard_model->getJobsForEmployee($company_id, $employer_id, 'pending');
                        $data['all_approved_jobs_count']   = $this->dashboard_model->getJobsForEmployee($company_id, $employer_id, 'approved');
                        $data['all_rejected_jobs_count']   = $this->dashboard_model->getJobsForEmployee($company_id, $employer_id, 'rejected');
                    }
                }

                if ($applicant_approval_module_status == 1) {
                    if ($loggedin_access_level == 'Admin') {
                        $employer_visibility_check                              = 0;
                    } else {
                        $employer_visibility_check                              = $employer_id;
                    }

                    $pending_applicants_count                                   = $this->dashboard_model->get_all_company_applicants_count($company_id, $employer_visibility_check, NULL, NULL, 'pending'); //Applications
                    $approved_applicants_count                                  = $this->dashboard_model->get_all_company_applicants_count($company_id, $employer_visibility_check, NULL, NULL, 'approved');
                    $rejected_applicants_count                                  = $this->dashboard_model->get_all_company_applicants_count($company_id, $employer_visibility_check, NULL, NULL, 'rejected');
                    $data['pending_applicants_count']                           = $pending_applicants_count;
                    $data['approved_applicants_count']                          = $approved_applicants_count;
                    $data['rejected_applicants_count']                          = $rejected_applicants_count;
                } else {
                    $data['pending_applicants_count']                           = 0;
                    $data['approved_applicants_count']                          = 0;
                    $data['rejected_applicants_count']                          = 0;
                }

                $users_with_applicants_approval_rights                          = $this->dashboard_model->GetUsersWithApprovalRights($company_id, 'applicants');
                $applicants_approving_user_ids                                  = array_column($users_with_applicants_approval_rights, 'sid');
                $users_with_jobs_approval_rights                                = $this->job_approval_rights_model->GetUsersWithApprovalRights($company_id, 'jobs');
                $jobs_approving_user_ids                                        = array_column($users_with_jobs_approval_rights, 'sid');
                $data['employer_sid']                                           = $employer_id;
                $data['users_with_job_approval_rights']                         = $jobs_approving_user_ids;
                $data['users_with_applicant_approval_rights']                   = $applicants_approving_user_ids;
                $data['visitors']                                               = $this->dashboard_model->get_visitors($company_id); //getting total visitors
                $data['unread_tickets_count']                                   = $this->tickets_model->get_unread_tickets_count($company_id);
                /* It is for Main Dashboard --- END ---*/
            }

            $data['title']                                                      = 'Dashboard';
            $data['employee']                                                   = $data['session']['employer_detail'];
            $load_view                                                          = check_blue_panel_status(true, 'self');
            $data['load_view']                                                  = $load_view;
            $data['access_level']                                               = $loggedin_access_level;
            $data['company_sid'] = $company_id;
            $data['employer_sid'] = $data['employee']['sid'];
            $data['employee_sid'] = $data['employee']['sid'];
            $data['employee_name'] = $data['employee']['first_name'] . ' ' . $data['employee']['last_name'];
            $data['level'] = $this->timeoff_model->getEmployerApprovalStatus($data['employer_sid']);

            // state forms from group
            $this->hr_documents_management_model
                ->assignGroupDocumentsToUser(
                    $employer_id,
                    "employee",
                    0,
                    true,
                    $company_id,
                    0
                );

            $announcements = $this->dashboard_model->get_all_events_count($company_id, $employer_id);
            $messages = $this->dashboard_model->get_all_messages_count($employer_id);
            $incidents = $this->dashboard_model->get_all_incidents_count($company_id, $employer_id);
            $incidents_new = $this->dashboard_model->assigned_incidents_new_flow_count($employer_id, $company_id);
            $training_session_count = $this->dashboard_model->get_training_session_count('employee', $employer_id, $company_id);
            $online_video_count = $this->dashboard_model->get_my_all_online_videos_count('employee', $employer_id, $company_id);
            // $documents_count = $this->dashboard_model->get_documents_count('employee', $employer_id);
            $assigned_documents = $this->dashboard_model->get_assigned_documents($company_id, 'employee', $employer_id, 0);
            $assigned_offer_letter = $this->dashboard_model->get_assigned_offer_letter($company_id, 'employee', $employer_id);
            $is_w4_assign = $this->dashboard_model->check_w4_form_exist('employee', $employer_id);
            $is_w9_assign = $this->dashboard_model->check_w9_form_exist('employee', $employer_id);
            $is_i9_assign = $this->dashboard_model->check_i9_exist('employee', $employer_id);

            //
            $documents_count = 0;

            if (!empty($is_w4_assign)) {
                $documents_count++;
            }

            if (!empty($is_w9_assign)) {
                $documents_count++;
            }

            if (!empty($is_i9_assign)) {
                $documents_count++;
            }

            if (!empty($assigned_offer_letter)) {
                $documents_count++;
            }

            //
            $this->load->model('hr_documents_management_model');
            if ($this->hr_documents_management_model->hasEEOCPermission($company_id, 'eeo_on_employee_document_center')) {
                $eeoc_form = $this->hr_documents_management_model->get_eeo_form_info($employer_id, 'employee');
                if (!empty($eeoc_form) && $eeoc_form['status'] == 1 && $eeoc_form['is_expired'] == 0) {
                    $documents_count++;
                }
            }

            foreach ($assigned_documents as $key => $assigned_document) {
                //
                $assigned_document['archive'] = $assigned_document['archive'] == 1 || $assigned_document['company_archive'] == 1 ? 1 : 0;
                //
                if ($assigned_document['archive'] == 1) {
                    unset($assigned_documents[$key]);
                }
                $is_magic_tag_exist = 0;
                $is_document_completed = 0;

                if (!empty($assigned_document['document_description']) && ($assigned_document['document_type'] == 'generated' || $assigned_document['document_type'] == 'hybrid_document')) {
                    $document_body = $assigned_document['document_description'];
                    // $magic_codes = array('{{signature}}', '{{signature_print_name}}', '{{inital}}', '{{sign_date}}', '{{short_text}}', '{{text}}', '{{text_area}}', '{{checkbox}}', 'select');
                    $magic_codes = array('{{signature}}', '{{inital}}', '{{short_text_required}}', '{{text_required}}', '{{text_area_required}}', '{{checkbox_required}}');

                    if (str_replace($magic_codes, '', $document_body) != $document_body) {
                        $is_magic_tag_exist = 1;
                    }
                }

                if ($assigned_document['approval_process'] == 0) {
                    if ($assigned_document['document_type'] != 'offer_letter') {
                        if ($assigned_document['status'] == 1) {
                            if (($assigned_document['acknowledgment_required'] || $assigned_document['download_required'] || $assigned_document['signature_required'] || $is_magic_tag_exist) && $assigned_document['archive'] == 0) {

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
                                    if (!empty($assigned_document['uploaded_file']) || !empty($assigned_document['submitted_description'])) {
                                        $signed_document_sids[] = $assigned_document['document_sid'];
                                        // $signed_documents[] = $assigned_document;
                                        unset($assigned_documents[$key]);
                                    } else {
                                        $completed_document_sids[] = $assigned_document['document_sid'];
                                        // $completed_documents[] = $assigned_document;
                                        unset($assigned_documents[$key]);
                                    }
                                    $completed_sids[] = $assigned_document['document_sid'];
                                    $signed_documents[] = $assigned_document;
                                } else {
                                    $assigned_sids[] = $assigned_document['document_sid'];
                                }
                            } else { // nothing is required so it is "No Action Required Document"
                                if (str_replace('{{authorized_signature}}', '', $document_body) != $document_body) {
                                    //
                                    if (!empty($assigned_document['authorized_signature'])) {
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
                                } else {
                                    $assigned_sids[] = $assigned_document['document_sid'];
                                    $no_action_required_sids[] = $assigned_document['document_sid'];
                                    $no_action_required_documents[] = $assigned_document;
                                    unset($assigned_documents[$key]);
                                }
                            }
                        } else {
                            $revoked_sids[] = $assigned_document['document_sid'];
                        }
                    }
                } else {
                    unset($assigned_documents[$key]);
                }
            }

            $documents_count = $documents_count + sizeof($assigned_documents) + $this->hr_documents_management_model->getGeneralDocumentCount(
                $data['session']['employer_detail']['sid'],
                'employee',
                $data['session']['company_detail']['sid']
            );

            // For Time off
            $pto_user_access = get_pto_user_access($employer_detail['parent_sid'], $employer_detail['sid']);
            if (checkIfAppIsEnabled('timeoff') && $pto_user_access['dashboard'] == 1) {
                // Get time off counts
                $requests = $this->timeoff_model->getTimeOffRequests(
                    $data['session']['company_detail']['sid'],
                    $data['session']['employer_detail']['sid'],
                    $data['session']['employer_detail']
                );
                $data['TodaysRequests'] = $requests['TodaysCount'];
                $data['TotalRequests'] = $requests['TotalCount'];
                $data['TotalEmployeeOffToday'] = $this->timeoff_model->getTodayOffEmployees([
                    'employerId' => $data['session']['employer_detail']['sid'],
                    'companyId' => $data['session']['company_detail']['sid']
                ], true);
            }

            //
            $this->load->model('varification_document_model');
            // For verification documents
            $companyEmployeesForVerification = $this->varification_document_model->getAllCompanyInactiveEmployee($data['session']['company_detail']['sid']);
            $companyApplicantsForVerification = $this->varification_document_model->getAllCompanyInactiveApplicant($data['session']['company_detail']['sid']);

            $today_start                = date('Y-m-d 00:00:00');
            $today_end                  = date('Y-m-d 23:59:59');
            $eventCount                 = $this->dashboard_model->company_employee_events_count($company_id, $employer_id); //Events
            $eventCountToday            = $this->dashboard_model->company_employee_events_count($company_id, $employer_id, $today_start, $today_end);
            $incident_count             = $this->dashboard_model->assigned_incidents_count($employer_id, $company_id);
            $unreadMessageCount         = $this->dashboard_model->get_all_unread_messages_count($employer_id); //Messages
            //           
            $total_assigned_today_doc   = $this->dashboard_model->get_all_auth_documents_assigned_today_count($company_id, $employer_id, $companyEmployeesForVerification, $companyApplicantsForVerification);
            $total_pending_auth_doc     = $this->dashboard_model->get_all_pending_auth_documents_count($company_id, $employer_id, $companyEmployeesForVerification, $companyApplicantsForVerification);
            $total_assigned_auth_doc    = $this->dashboard_model->get_all_auth_documents_assigned_count($company_id, $employer_id, $companyEmployeesForVerification, $companyApplicantsForVerification);
            //
            // Authorized Check
            $data['AuthorizedDocuments'] = [];
            $data['AuthorizedDocuments']['Today'] = $total_assigned_today_doc;
            $data['AuthorizedDocuments']['Pending'] = $total_pending_auth_doc;
            $data['AuthorizedDocuments']['Total'] = $total_assigned_auth_doc;
            //    
            $data['total_assigned_today_doc']   = $total_assigned_today_doc;
            $data['total_pending_auth_doc']     = $total_pending_auth_doc;
            $data['total_assigned_auth_doc']    = $total_assigned_auth_doc;
            //
            // For verification documents
            //
            if ($employer_detail['access_level_plus'] || $employer_detail['pay_plan_flag']) {
                // Pending Employer Sections
                $data['PendingEmployerSection'] = [];
                $data['PendingEmployerSection']['Employee'] = $this->varification_document_model->get_all_users_pending_w4($data['session']['company_detail']['sid'], 'employee', TRUE, $companyEmployeesForVerification);
                $data['PendingEmployerSection']['Employee'] += $this->varification_document_model->get_all_users_pending_i9($data['session']['company_detail']['sid'], 'employee', TRUE, $companyEmployeesForVerification);
                // $data['PendingEmployerSection']['Employee'] += $this->varification_document_model->getPendingAuthDocs($data['session']['company_detail']['sid'], 'employee', TRUE, $data['session']['employer_detail'], $companyEmployeesForVerification);
                //
                $data['PendingEmployerSection']['Applicant'] = $this->varification_document_model->get_all_users_pending_w4($data['session']['company_detail']['sid'], 'applicant', TRUE, $companyApplicantsForVerification);
                $data['PendingEmployerSection']['Applicant'] += $this->varification_document_model->get_all_users_pending_i9($data['session']['company_detail']['sid'], 'applicant', TRUE, $companyApplicantsForVerification);
                // $data['PendingEmployerSection']['Applicant'] += $this->varification_document_model->getPendingAuthDocs($data['session']['company_detail']['sid'], 'applicant', TRUE, $data['session']['employer_detail'], $companyApplicantsForVerification);
                //
                $data['PendingEmployerSection']['Total'] = $data['PendingEmployerSection']['Employee'] + $data['PendingEmployerSection']['Applicant'];
            } else {
                $data['PendingEmployerSection']['Total'] = 0;
                $data['PendingEmployerSection']['Employee'] = 0;
                $data['PendingEmployerSection']['Applicant'] = 0;
            }
            // 
            $total_assigned_today_doc   = $this->dashboard_model->get_all_auth_documents_assigned_today_count($company_id, $employer_id, $companyEmployeesForVerification, $companyApplicantsForVerification);
            $total_pending_auth_doc     = $this->dashboard_model->get_all_pending_auth_documents_count($company_id, $employer_id, $companyEmployeesForVerification, $companyApplicantsForVerification);
            $total_assigned_auth_doc    = $this->dashboard_model->get_all_auth_documents_assigned_count($company_id, $employer_id, $companyEmployeesForVerification, $companyApplicantsForVerification);

            $data['messages']                   = $messages;
            $data['eventCount']                 = $eventCount;
            $data['has_announcements']          = $announcements;
            $data['incidents_count']            = $incident_count;
            $data['documents_count']            = $documents_count;
            $data['eventCountToday']            = $eventCountToday; //count($all_company_events_today);
            $data['unreadMessageCount']         = $unreadMessageCount;
            $data['training_session_count']     = $training_session_count + $online_video_count;
            //
            $this->load->model('timeoff_model');
            //
            $data['timeOffDays'] = $this->timeoff_model->getTimeOffDays($data['session']['company_detail']['sid']);
            //
            $data['theme'] = 2;
            //
            $this->load->model('performance_management_model', 'pmm');
            $data['review'] = $this->pmm->getMyReviewCounts($data['session']['company_detail']['sid'], $employer_id);
            $data['total_goals'] = count($this->pmm->getMyGoals($data['employee']['sid']));
            //
            $data['employee_handbook_enable'] = $this->dashboard_model->get_employee_handbook_status($company_id);
            //
            if ($data['employee_handbook_enable']) {
                //
                $data['handbook_documents'] = $this->dashboard_model->get_employee_handbook_documents_new($company_id, $employer_id);
            }
            //
            $total_document_approval = count($this->varification_document_model->getMyApprovalDocuments($data['session']['employer_detail']['sid']));
            $data["all_documents_approval"] = $total_document_approval;
            //
            $data['PendingEmployerSection']['Total'] = $data['PendingEmployerSection']['Employee'] + $data['PendingEmployerSection']['Applicant'];

            $data['total_library_doc'] = count($this->hr_documents_management_model->getVerificationDocumentsForLibrary(
                $company_id,
                $employer_id,
                'employee'
            )) + $this->dashboard_model->get_all_library_doc_count($company_id);
            // Get the employee information change report
            // Loads up the model
            $this->load->model('2022/User_model', 'em');
            // Set the data array
            $data['employeeInformationChange'] = [
                'daily' => $this->em->getEmployeeInformationChange($company_id, 'daily'),
                'week' => $this->em->getEmployeeInformationChange($company_id, 'week'),
                'month' => $this->em->getEmployeeInformationChange($company_id, 'month')
            ];
            // set default js
            $data['PageScripts'] = [];
            $data['incident_count'] = $this->dashboard_model->assigned_incidents_count($employer_id, $company_id);

            // LMS - Trainings
            // if ($isLMSModuleEnabled = checkIfAppIsEnabled(MODULE_LMS)) {
            //     // load model
            //     $this->load->model('v1/course_model');
            //     // get pending course count
            //     $data['pendingTrainings'] =
            //         $this->course_model->getEmployeePendingCourseCount(
            //             $data['session']['company_detail']['sid'],
            //             $data['session']['employer_detail']['sid']
            //         );
            //     //
            //     $data['coursesInfo'] =
            //     $this->course_model->getCompanyCoursesInfo(
            //         $data['session']['company_detail']['sid']
            //     );
            //     //
            //     $subordinateInfo = getMyDepartmentAndTeams($data['session']['employer_detail']['sid']);
            //     //
            //     $data['haveSubordinate'] = 'no';
            //     //
            //     if (!empty($subordinateInfo['employees'])) {
            //         $data['haveSubordinate'] = 'yes';
            //     }

            // }
            if ($isLMSModuleEnabled = checkIfAppIsEnabled(MODULE_LMS)) {
                // load model
                $this->load->model('v1/course_model');
                //
                $data['coursesInfo'] =
                    $this->course_model->getCompanyCoursesInfo(
                        $data['session']['company_detail']['sid']
                    );
                // get pending course count
                $data['pendingTrainings'] =
                    $this->course_model->getEmployeePendingCourseCount(
                        $data['session']['company_detail']['sid'],
                        $data['session']['employer_detail']['sid']
                    );

                $data["lmsCompanyStats"] =  $this->course_model->getCompanyStats(
                    $data['session']['company_detail']['sid']
                );
            }
            //
            $data['isLMSModuleEnabled'] = $isLMSModuleEnabled;

            $bundleCSS = bundleCSS(['v1/plugins/ms_modal/main'], 'public/v1/css/', 'dashboard', true);
            $bundleJS = bundleJs([
                'v1/plugins/ms_modal/main',
                'js/app_helper',
            ], 'public/v1/js/', 'dashboard', true);

            // check and add payroll scripts
            if (checkIfAppIsEnabled(PAYROLL)) {
                if (!hasAcceptedPayrollTerms($data['session']['company_detail']['sid'])) {
                    $bundleJS .= "\n" . bundleJs(['v1/payroll/js/agreement'], 'public/v1/js/payroll/', 'company-agreement', true);
                }
                if (!isCompanyOnBoard($data['session']['company_detail']['sid'])) {
                    $bundleJS .= "\n" . bundleJs(['v1/payroll/js/company_onboard'], 'public/v1/js/payroll/', 'setup-company', true);
                }

                // for payroll
                if (isCompanyOnBoard($company_id) && isEmployeeOnPayroll($employer_id)) {
                    // load up the model
                    $this->load->model('v1/Pay_stubs_model', 'pay_stubs_model');
                    //
                    $data['employeePayStubsCount'] = $this->pay_stubs_model
                        ->getMyPayStubsCount($employer_id);
                }
            }
            //
            $data['appJs'] = $bundleJS;
            $data['appCSS'] = $bundleCSS;

            $companyStateForms = $this->hr_documents_management_model
                ->getCompanyStateForms(
                    $company_id,
                    $employer_id,
                    "employee"
                );


            $data['documents_count'] += count($companyStateForms["not_completed"]);

            // load shifts model
            $this->load->model("v1/Shift_model", "shift_model");
            $data['myAssignedShifts'] = $this->shift_model
                ->getShiftsCountByEmployeeId(
                    $employer_id,
                    getSystemDate("Y-m-01"),
                    getSystemDate("Y-m-t")
                );

            $data["mySubordinatesCount"] = $this->shift_model
                ->getMySubordinates(
                    $employer_id,
                    true
                );
            //
            if (checkIfAppIsEnabled('performanceevaluation')) {
                $this
                    ->load
                    ->model(
                        "v1/Employee_performance_evaluation_model",
                        "employee_performance_evaluation_model"
                    );
                //
                $pendingVerificationPerformanceSectionOne =
                    $this->employee_performance_evaluation_model->checkPerformanceVerificationDocumentSection(
                        $employer_id,
                        1
                    );
                //
                $data["pendingVerificationPerformanceDocument"] = $pendingVerificationPerformanceSectionOne;
            }

            if (checkIfAppIsEnabled(SCHEDULE_MODULE)) {
                //
                $data["awaitingShiftRequests"] = $this->shift_model->getAwaitingSwapShiftsByUserId($employer_id);
                //
                if (isPayrollOrPlus()) {
                    $data["awaitingShiftsApprovals"] = $this->shift_model->getAwaitingSwapShiftsApprovals();
                }
            }


            //   
            $subdata = $this->subordinatesReport();
            //
            $data['subordinateInfo'] = $subdata['subordinateInfo'];

            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/dashboard_new');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function employee_management_system()
    {

        if ($this->session->userdata('logged_in')) {
            $data['session']                                                    = $this->session->userdata('logged_in');
            $employer_detail                                                    = $data['session']['employer_detail'];
            $company_detail                                                     = $data['session']['company_detail'];
            $security_sid                                                       = $employer_detail['sid'];
            $ems_status                                                         = $company_detail['ems_status'];

            if (!$ems_status) {
                $this->session->set_flashdata('message', '<strong>Warning</strong> Not Allowed!');
                redirect('dashboard', 'refresh');
            }

            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;
            $employer_id                                                        = $employer_detail['sid'];
            $company_id                                                         = $company_detail['sid'];
            $data['employerData']                                               = $employer_detail;
            $loggedin_access_level                                              = $employer_detail['access_level'];
            $data['companyData']                                                = $company_detail;
            $data['company_info']                                               = $company_detail;
            $data['companyData']['locationDetail']                              = db_get_state_name($data['companyData']['Location_State']);
            $welcome_video                                                      = $this->onboarding_model->get_onboarding_setup_welcome_video($company_detail['sid'], $employer_detail['sid'], 'employee'); // Welcome Videos
            $data['welcome_video']                                              = $welcome_video;

            $configuration                                                  = $this->onboarding_model->get_onboarding_configuration('employee', $employer_id);
            $sections_data                                                  = $this->get_single_record_from_array($configuration, 'section', 'sections');
            $locations_data                                                 = $this->get_single_record_from_array($configuration, 'section', 'locations');
            // Custom Location
            $custom_office_locations                                        = $this->onboarding_model->get_custom_office_records($company_id, $employer_id, 'employee', 'location', 1);
            $data['custom_office_locations']                                = $custom_office_locations;
            $timings_data                                                   = $this->get_single_record_from_array($configuration, 'section', 'timings');
            // Custom Timing
            $custom_office_timings                                          = $this->onboarding_model->get_custom_office_records($company_id, $employer_id, 'employee', 'timing');
            $data['custom_office_timings']                                  = $custom_office_timings;
            // Custom Useful Links
            $custom_useful_link                                             = $this->onboarding_model->get_custom_office_records($company_id, $employer_id, 'employee', 'useful_link');
            $data['custom_useful_link']                                     = $custom_useful_link;
            //
            $people_data                                                    = $this->get_single_record_from_array($configuration, 'section', 'people');
            //  $items_data                                                     = $this->get_single_record_from_array($configuration, 'section', 'items');
            $items_data                                                     = $this->onboarding_model->get_assigned_custom_office_record_sids($company_id, $employer_id, 'employee', 'item', 2); // fetch items from new table
            // $links_data                                                     = $this->get_single_record_from_array($configuration, 'section', 'links');
            $ems_notification                                               = $this->onboarding_model->get_ems_notifications($company_id, $employer_id);
            $sections                                                       = empty($sections_data) ? array() : $sections_data['items_details'];
            $locations                                                      = empty($locations_data) ? array() : $locations_data['items_details'];
            $timings                                                        = empty($timings_data) ? array() : $timings_data['items_details'];
            $people                                                         = empty($people_data) ? array() : $people_data['items_details'];
            $assign_links                                                   = $this->onboarding_model->onboarding_assign_useful_links($employer_id, $company_id, 'employee');
            $data['sections']                                               = $sections;
            $data['ems_notification']                                       = $ems_notification;
            $data['locations']                                              = $locations;
            $data['timings']                                                = $timings;
            $data['people']                                                 = $people;
            $data['items']                                                  = $items_data;
            $data['links']                                                  = $assign_links;
            $data['complete_steps']                                         = $this->onboarding_model->check_updated_sections($company_id, 'employee', $employer_id);
            $data['safety_sheet_flag']                                      = $this->dashboard_model->check_safety_data($company_id);
            $data['session']['safety_sheet_flag']                           = $data['safety_sheet_flag'];
            // echo '<pre>'; print_r($data['session']); exit;

            $sess_array                                                     = array();
            $sess_array['company_detail']                                   = $data['session']['company_detail'];
            $sess_array['employer_detail']                                  = $data['session']['employer_detail'];
            $sess_array['cart']                                             = $data['session']['cart'];
            $sess_array['portal_detail']                                    = $data['session']['portal_detail'];

            if (isset($data['session']['clocked_status'])) {
                $sess_array['clocked_status']                               = $data['session']['clocked_status'];
            }

            if (isset($data['session']['incident_config'])) {
                $sess_array['incident_config']                              = $data['session']['incident_config'];
            }

            if (isset($data['session']['resource_center'])) {
                $sess_array['resource_center']                              = $data['session']['resource_center'];
            }

            if (isset($data['session']['is_super'])) {
                $sess_array['is_super']                                     = $data['session']['is_super'];
            }

            $sess_array['safety_sheet_flag']                                = $data['safety_sheet_flag'];
            $this->session->set_userdata('logged_in', $sess_array);

            $this->load->model('timeoff_model');
            $data['holidayDates'] = $this->timeoff_model->getDistinctHolidayDates(array('companySid' => $company_id));


            // Check if the employee has
            // access for the Job Approval Management
            // if not then redirect to dashboard
            // only for access level employee
            // Check for employee Job Approval Management
            $company_detail = $data['session']['company_detail'];
            $jobs_approval_module_status = $company_detail['has_job_approval_rights']; //get_job_approval_module_status($company_id);

            // state forms from group
            $this->hr_documents_management_model
                ->assignGroupDocumentsToUser(
                    $employer_id,
                    "employee",
                    0,
                    true,
                    $company_id,
                    0
                );

            $data['has_approval_access'] = false;
            if (
                $jobs_approval_module_status == 1 &&
                $this->dashboard_model->check_employee_has_approval_rights($company_id, $employer_id) == 1
            ) { // Jobs Count By Approval Status
                $data['all_unapproved_jobs_count'] = $this->dashboard_model->getJobsForEmployee($company_id, $employer_id, 'pending');
                $data['all_approved_jobs_count']   = $this->dashboard_model->getJobsForEmployee($company_id, $employer_id, 'approved');
                $data['all_rejected_jobs_count']   = $this->dashboard_model->getJobsForEmployee($company_id, $employer_id, 'rejected');
                $data['has_approval_access'] = true;
            }
            $announcements = $this->dashboard_model->get_all_events_count($company_id, $employer_id);
            $messages = $this->dashboard_model->get_all_messages_count($employer_id);
            $incidents = $this->dashboard_model->get_all_incidents_count($company_id, $employer_id);
            $incidents_new = $this->dashboard_model->assigned_incidents_new_flow_count($employer_id, $company_id);
            $incident_count = $this->dashboard_model->assigned_incidents_count($employer_id, $company_id);
            $training_session_count = $this->dashboard_model->get_training_session_count('employee', $employer_id, $company_id);
            $online_video_count = $this->dashboard_model->get_my_all_online_videos_count('employee', $employer_id, $company_id);
            // $documents_count = $this->dashboard_model->get_documents_count('employee', $employer_id);
            $assigned_documents = $this->dashboard_model->get_assigned_documents($company_id, 'employee', $employer_id, 0);
            $assigned_offer_letter = $this->dashboard_model->get_assigned_offer_letter($company_id, 'employee', $employer_id);
            $is_w4_assign = $this->dashboard_model->check_w4_form_exist('employee', $employer_id);
            $is_w9_assign = $this->dashboard_model->check_w9_form_exist('employee', $employer_id);
            $is_i9_assign = $this->dashboard_model->check_i9_exist('employee', $employer_id);

            $documents_count = 0;


            if (!empty($is_w4_assign)) {
                $documents_count++;
            }

            if (!empty($is_w9_assign)) {
                $documents_count++;
            }

            if (!empty($is_i9_assign)) {
                $documents_count++;
            }

            if (!empty($assigned_offer_letter)) {
                $documents_count++;
            }

            $this->load->model('hr_documents_management_model');
            //
            if ($this->hr_documents_management_model->hasEEOCPermission($company_id, 'eeo_on_employee_document_center')) {
                $eeoc_form = $this->hr_documents_management_model->get_eeo_form_info($employer_id, 'employee');

                if (!empty($eeoc_form) && $eeoc_form['status'] == 1 && $eeoc_form['is_expired'] == 0) {
                    $documents_count++;
                }
            }

            foreach ($assigned_documents as $key => $assigned_document) {
                //
                $assigned_document['archive'] = $assigned_document['archive'] == 1 || $assigned_document['company_archive'] == 1 ? 1 : 0;
                //
                $is_magic_tag_exist = 0;
                $is_document_completed = 0;
                //
                if (!empty($assigned_document['document_description']) && $assigned_document['document_type'] == 'generated') {
                    $document_body = $assigned_document['document_description'];
                    $magic_codes = array('{{signature}}', '{{inital}}', '{{short_text_required}}', '{{text_required}}', '{{text_area_required}}', '{{checkbox_required}}');

                    if (str_replace($magic_codes, '', $document_body) != $document_body) {
                        $is_magic_tag_exist = 1;
                    }
                }
                //

                if ($assigned_document['approval_process'] == 0) {

                    if ($assigned_document['document_type'] != 'offer_letter') {
                        if ($assigned_document['status'] == 1) {
                            if (($assigned_document['acknowledgment_required'] || $assigned_document['download_required'] || $assigned_document['signature_required'] || $is_magic_tag_exist) && $assigned_document['archive'] == 0) {

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
                                    if (!empty($assigned_document['uploaded_file']) || !empty($assigned_document['submitted_description'])) {
                                        $signed_document_sids[] = $assigned_document['document_sid'];
                                        // $signed_documents[] = $assigned_document;
                                        unset($assigned_documents[$key]);
                                    } else {
                                        $completed_document_sids[] = $assigned_document['document_sid'];
                                        // $completed_documents[] = $assigned_document;
                                        unset($assigned_documents[$key]);
                                    }
                                    $completed_sids[] = $assigned_document['document_sid'];
                                    $signed_documents[] = $assigned_document;
                                } else {
                                    $assigned_sids[] = $assigned_document['document_sid'];
                                }
                            } else { // nothing is required so it is "No Action Required Document"
                                unset($assigned_documents[$key]);
                            }
                        } else {
                            $revoked_sids[] = $assigned_document['document_sid'];
                        }
                    }
                } else {
                    unset($assigned_documents[$key]);
                }
            }

            // $this->load->model('hr_documents_management_model');
            // $assigned_offer_letters = $this->hr_documents_management_model->get_assigned_offers($company_id, 'employee', $employer_id, 0);

            // $nc = 0;
            // if(sizeof($assigned_offer_letters)){
            //     foreach ($assigned_offer_letters as $k => $v) {
            //         if($v['user_consent'] != 1) $nc++;
            //     }
            // }

            // $documents_count = $documents_count + sizeof($assigned_documents) + $nc;
            $this->load->model('hr_documents_management_model');
            $documents_count = $documents_count + sizeof($assigned_documents) + $this->hr_documents_management_model->getGeneralDocumentCount(
                $data['session']['employer_detail']['sid'],
                'employee',
                $data['session']['company_detail']['sid']
            );

            $today_start                                                    = date('Y-m-d 00:00:00');
            $today_end                                                      = date('Y-m-d 23:59:59');
            $eventCount                                                     = $this->dashboard_model->company_employee_events_count($company_id, $employer_id); //Events
            $eventCountToday                                                = $this->dashboard_model->company_employee_events_count($company_id, $employer_id, $today_start, $today_end);
            $data['eventCount']                                             = $eventCount;
            $data['eventCountToday']                                        = $eventCountToday; //count($all_company_events_today);
            $unreadMessageCount                                             = $this->dashboard_model->get_all_unread_messages_count($employer_id); //Messages
            $data['unreadMessageCount']                                     = $unreadMessageCount;
            $data['has_announcements'] = $announcements;
            // $data['incidents_count'] = $incidents_new;
            $data['incidents_count'] = $incident_count;
            $data['messages'] = $messages;
            $data['training_session_count'] = $training_session_count + $online_video_count;
            // $data['online_video_count'] = $online_video_count;
            $data['documents_count'] = $documents_count;

            $data['title']                                                      = 'Dashboard';
            $data['employee']                                                   = $data['session']['employer_detail'];
            $data['load_view']                                                  = 'old';
            //
            $data['company_sid'] = $company_id;
            $data['employer_sid'] = $data['employee']['sid'];
            $data['employee_sid'] = $data['employee']['sid'];
            $data['employee_name'] = $data['employee']['first_name'] . ' ' . $data['employee']['last_name'];
            $data['level'] = $this->timeoff_model->getEmployerApprovalStatus($data['employer_sid']);

            $data['timeOffDays'] = $this->timeoff_model->getTimeOffDays($data['session']['company_detail']['sid']);
            $data['performanceReviewPending'] = 0;

            $data['theme'] = 2;

            $this->session->set_userdata('time_off_theme', $data['theme']);
            //
            $this->load->model('performance_management_model', 'pmm');
            $data['review'] = $this->pmm->getMyReviewCounts($data['session']['company_detail']['sid'], $employer_id);
            $data['total_goals'] = count($this->pmm->getMyGoals($data['employee']['sid']));
            //
            //
            $this->load->model('varification_document_model');
            //
            $companyEmployeesForVerification = $this->varification_document_model->getAllCompanyInactiveEmployee($company_id);
            $companyApplicantsForVerification = $this->varification_document_model->getAllCompanyInactiveApplicant($company_id);
            //

            // Authorized Check
            $data['AuthorizedDocuments'] = [];
            $data['AuthorizedDocuments']['Today'] = $this->dashboard_model->get_all_auth_documents_assigned_today_count($company_id, $employer_id, $companyEmployeesForVerification, $companyApplicantsForVerification);
            $data['AuthorizedDocuments']['Pending'] = $this->dashboard_model->get_all_pending_auth_documents_count($company_id, $employer_id, $companyEmployeesForVerification, $companyApplicantsForVerification);
            $data['AuthorizedDocuments']['Total'] = $this->dashboard_model->get_all_auth_documents_assigned_count($company_id, $employer_id, $companyEmployeesForVerification, $companyApplicantsForVerification);

            // Pending Employer Sections
            $data['PendingEmployerSection'] = [];
            // For verification documents
            //
            $data['PendingEmployerSection']['Employee'] = 0;
            if ($data['session']['employer_detail']['access_level_plus']) {
                $data['PendingEmployerSection']['Employee'] = $this->varification_document_model->get_all_users_pending_w4($company_id, 'employee', TRUE, $companyEmployeesForVerification);
                $data['PendingEmployerSection']['Employee'] += $this->varification_document_model->get_all_users_pending_i9($company_id, 'employee', TRUE, $companyEmployeesForVerification);
            }
            $data['PendingEmployerSection']['Employee'] += $this->varification_document_model->getPendingAuthDocs($company_id, 'employee', TRUE, $data['session']['employer_detail'], $companyEmployeesForVerification);
            //
            $data['PendingEmployerSection']['Applicant'] = 0;
            if ($data['session']['employer_detail']['access_level_plus']) {
                $data['PendingEmployerSection']['Applicant'] = $this->varification_document_model->get_all_users_pending_w4($company_id, 'applicant', TRUE, $companyApplicantsForVerification);
                $data['PendingEmployerSection']['Applicant'] += $this->varification_document_model->get_all_users_pending_i9($company_id, 'applicant', TRUE, $companyApplicantsForVerification);
            }
            $data['PendingEmployerSection']['Applicant'] += $this->varification_document_model->getPendingAuthDocs($company_id, 'applicant', TRUE, $data['session']['employer_detail'], $companyApplicantsForVerification);
            //
            $data['PendingEmployerSection']['Total'] = $data['PendingEmployerSection']['Employee'] + $data['PendingEmployerSection']['Applicant'];

            //
            $this->load->model('payroll_model', 'pm');
            //
            $data['TotalPayStubs'] = count($this->pm->GetPayrollColumns(
                'payroll_employees_pay_stubs',
                [
                    'employee_sid' => $data['session']['employer_detail']['sid']
                ],
                'sid'
            ));
            //
            $data['employee_handbook_enable'] = $this->dashboard_model->get_employee_handbook_status($company_id);
            //
            if ($data['employee_handbook_enable']) {
                //
                $data['handbook_documents'] = $this->dashboard_model->get_employee_handbook_documents_new($company_id, $employer_id);
            }
            //

            $data['is_handbook_category_exist'] = $this->dashboard_model->check_company_employee_handbook_category($company_id);
            //
            $total_document_approval = count($this->varification_document_model->getMyApprovalDocuments($data['session']['employer_detail']['sid']));
            $data["all_documents_approval"] = $total_document_approval;
            //

            $data['total_library_doc'] = count($this->hr_documents_management_model->getVerificationDocumentsForLibrary(
                $company_id,
                $employer_id,
                'employee'
            )) + $this->dashboard_model->get_all_library_doc_count($company_id);

            //
            $data['LMSStatus'] = $this->dashboard_model->getLMSStatus($company_id);

            // LMS - Trainings
            if ($isLMSModuleEnabled = checkIfAppIsEnabled(MODULE_LMS)) {
                // load model
                $this->load->model('v1/course_model');
                // get pending course count
                $data['pendingTrainings'] =
                    $this->course_model->getEmployeePendingCourseCount(
                        $data['session']['company_detail']['sid'],
                        $data['session']['employer_detail']['sid']
                    );
            }
            //
            $data['isLMSModuleEnabled'] = $isLMSModuleEnabled;

            // for payroll
            if (checkIfAppIsEnabled(PAYROLL) && isCompanyOnBoard($company_id) && isEmployeeOnPayroll($employer_id)) {
                // load up the model
                $this->load->model('v1/Pay_stubs_model', 'pay_stubs_model');
                //
                $data['employeePayStubsCount'] = $this->pay_stubs_model
                    ->getMyPayStubsCount($employer_id);
            }

            $companyStateForms = $this->hr_documents_management_model
                ->getCompanyStateForms(
                    $company_id,
                    $employer_id,
                    "employee"
                );

            $data['documents_count'] += count($companyStateForms["not_completed"]);

            // load shifts model
            $this->load->model("v1/Shift_model", "shift_model");
            $data['myAssignedShifts'] = $this->shift_model
                ->getShiftsCountByEmployeeId(
                    $employer_id,
                    getSystemDate("Y-m-01"),
                    getSystemDate("Y-m-t")
                );
            $data["mySubordinatesCount"] = $this->shift_model
                ->getMySubordinates(
                    $employer_id,
                    true
                );
            //
            if (checkIfAppIsEnabled('performanceevaluation')) {
                $this
                    ->load
                    ->model(
                        "v1/Employee_performance_evaluation_model",
                        "employee_performance_evaluation_model"
                    );
                //
                $pendingVerificationPerformanceSectionOne =
                    $this->employee_performance_evaluation_model->checkPerformanceVerificationDocumentSection(
                        $employer_id,
                        1
                    );
                //
                $data["pendingVerificationPerformanceDocument"] = $pendingVerificationPerformanceSectionOne;
                //
                $pendingPerformanceDocument =
                    $this->employee_performance_evaluation_model->checkEmployeeUncompletedDocument(
                        $employer_id
                    );
                //
                $data['documents_count'] = $data['documents_count'] + ($pendingPerformanceDocument ? 1 : 0);
            }

            if (checkIfAppIsEnabled(SCHEDULE_MODULE)) {
                //
                $data["awaitingShiftRequests"] = $this->shift_model->getAwaitingSwapShiftsByUserId($employer_id);
                //
                if (isPayrollOrPlus()) {
                    $data["awaitingShiftsApprovals"] = $this->shift_model->getAwaitingSwapShiftsApprovals();
                }
            }

            $this->load->view('main/header', $data);
            $this->load->view('onboarding/getting_started');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), 'refresh');
        }
    }

    private function get_single_record_from_array($records, $key, $value)
    {
        if (is_array($records)) {
            foreach ($records as $record) {
                foreach ($record as $k => $v) {
                    if ($k == $key && $v == $value) {
                        return $record;
                    }
                }
            }

            return array();
        } else {
            return array();
        }
    }

    public function colleague_profile($employee_sid)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $company_sid = $data["session"]["company_detail"]["sid"];
            $employer_sid = $data["session"]["employer_detail"]["sid"];
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            //check_access_permissions($security_details, 'dashboard', 'colleague_profile'); // Param2: Redirect URL, Param3: Function Name
            $full_access = false;

            if ($data['session']['employer_detail']['access_level'] == 'Admin') {
                $full_access = true;
            }

            $data['full_access'] = $full_access;
            $data['employee'] = $data['session']['employer_detail'];
            $employer = $this->dashboard_model->get_employee_info($company_sid, $employee_sid);
            $data['employer'] = $employer;
            $data['title'] = 'Colleague Profile - ' . ucwords($employer['first_name'] . ' ' . $employer['last_name']);
            $load_view = check_blue_panel_status(false, 'self');
            $data['load_view'] = $load_view;

            $this->load->view('main/header', $data);
            $this->load->view('dashboard/colleague_profile');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    private function filter_out_array_based_on_value($data_array, $field_name, $value)
    {
        $return_array = array();

        if (!empty($data_array)) {
            foreach ($data_array as $key => $row) {
                if (isset($row[$field_name])) {
                    $row_val = $row[$field_name];
                    if ($row_val == $value) {
                        $return_array[] = $row;
                    }
                }
            }
        }

        return $return_array;
    }

    private function filter_out_array_based_on_date($data_array, $date_field_name, $date_start, $date_end)
    {
        $return_array = array();

        if (!empty($data_array)) {
            foreach ($data_array as $key => $row) {
                if (isset($row[$date_field_name])) {
                    $my_date = $row[$date_field_name];
                    $my_date_unix = strtotime($my_date);
                    $date_start_unix = strtotime($date_start);
                    $date_end_unix = strtotime($date_end);

                    if ($my_date_unix >= $date_start_unix && $my_date_unix <= $date_end_unix) {
                        $return_array[] = $row;
                    }
                }
            }
        }

        return $return_array;
    }

    private function log_and_send_email_with_attachment($from, $to, $subject, $body, $senderName, $file_path)
    {
        $CI = &get_instance();

        if (base_url() == STAGING_SERVER_URL) {
            $emailData = array(
                'date' => date('Y-m-d H:i:s'),
                'subject' => $subject,
                'email' => $to,
                'message' => $body,
                'username' => $senderName,
            );

            save_email_log_common($emailData);
        } else {
            $emailData = array(
                'date' => date('Y-m-d H:i:s'),
                'subject' => $subject,
                'email' => $to,
                'message' => $body,
                'username' => $senderName,
            );

            save_email_log_common($emailData);
            sendMailWithAttachmentRealPath($from, $to, $subject, $body, $senderName, $file_path);
        }
    }

    private function send_email_notification($event_sid, $is_update = false)
    {
        $session = $this->session->userdata('logged_in');
        $company_sid = $session["company_detail"]["sid"];
        $company_name = $session["company_detail"]["CompanyName"];
        $event = $this->dashboard_model->get_event_details($event_sid);
        $participants = $this->dashboard_model->get_event_participants($event_sid);
        $message_hf = message_header_footer($company_sid, $company_name);
        $destination = APPPATH . '../assets/ics_files/';
        $filePath = generate_ics_file_for_event($destination, $event_sid, true);

        foreach ($participants as $participant) {
            $to_name = ucwords($participant['first_name'] . ' ' . $participant['last_name']);
            $to_email = $participant['email'];
            $from_name = STORE_NAME . ' - Events';
            $from_email = FROM_EMAIL_EVENTS;

            if (!empty($event)) {
                $event_status = $event['event_status'];
                if ($event_status == 'cancelled') {
                    //Cancelled Event
                    //This is Already Handled in Event Cancellation Code @ line 1393
                } else {
                    if ($is_update == false) { //Create Event
                        $subject = ucfirst($event['category']) . " schedule notification";
                    } else { //Update Event
                        $subject = ucfirst($event['category']) . " schedule update notification";
                    }
                }
            }
        }
    }

    public function setting_task()
    {
        $action = $this->input->post('action');

        if ($action == 'remove_logo') {
            $company_id = $this->input->post('sid');
            $this->dashboard_model->delete_logo($company_id);
        }
    }

    public function validate_youtube($str)
    {
        if ($this->session->userdata('logged_in')) {
            if ($str != "") {
                preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $str, $matches);
                if (!isset($matches[0])) { //if validation not passed
                    $this->form_validation->set_message('validate_youtube', 'Invalid youtube video url.');
                    $this->session->set_flashdata('message', '<b>Error:</b> In-valid Youtube video URL');
                    return FALSE;
                } else { //if validation passed
                    return TRUE;
                }
            } else {
                return true;
            }
        }
    }

    public function validate_vimeo($str)
    {
        if ($str != "") {
            if ($_SERVER['HTTP_HOST'] == 'localhost') {
                $api_url = 'https://vimeo.com/api/oembed.json?url=' . urlencode($str);
                $response = @file_get_contents($api_url);

                if (!empty($response)) {
                    $response = json_decode($response, true);

                    if (isset($response['video_id'])) {
                        return TRUE;
                    } else {
                        $this->form_validation->set_message('validate_vimeo', 'In-valid Vimeo video URL');
                        $this->session->set_flashdata('message', '<b>Error:</b> In-valid Vimeo video URL');
                        return FALSE;
                    }
                } else {
                    $this->form_validation->set_message('validate_vimeo', 'In-valid Vimeo video URL');
                    $this->session->set_flashdata('message', '<b>Error:</b> In-valid Vimeo video URL');
                    return FALSE;
                }
            } else {
                $api_url = 'https://vimeo.com/api/oembed.json?url=' . urlencode($str);
                $cSession = curl_init();
                curl_setopt($cSession, CURLOPT_URL, $api_url);
                curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($cSession, CURLOPT_HEADER, false);
                $response = curl_exec($cSession);
                curl_close($cSession);
                $response = json_decode($response, true); //$response = @file_get_contents($api_url);

                if (isset($response['video_id'])) {
                    return TRUE;
                } else {
                    $this->form_validation->set_message('validate_vimeo', 'In-valid Vimeo video URL');
                    $this->session->set_flashdata('message', '<b>Error:</b> In-valid Vimeo video URL');
                    return FALSE;
                }
            }
        } else {
            return true;
        }
    }

    public function vimeo_get_id($str)
    {
        if ($str != "") {
            if ($_SERVER['HTTP_HOST'] == 'localhost') {
                $api_url = 'https://vimeo.com/api/oembed.json?url=' . urlencode($str);
                $response = @file_get_contents($api_url);

                if (!empty($response)) {
                    $response = json_decode($response, true);

                    if (isset($response['video_id'])) {
                        return $response['video_id'];
                    } else {
                        return 0;
                    }
                } else {
                    return 0;
                }
            } else {
                $api_url = 'https://vimeo.com/api/oembed.json?url=' . urlencode($str);
                $cSession = curl_init();
                curl_setopt($cSession, CURLOPT_URL, $api_url);
                curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($cSession, CURLOPT_HEADER, false);
                $response = curl_exec($cSession);
                curl_close($cSession);
                $response = json_decode($response, true); //$response = @file_get_contents($api_url);

                if (isset($response['video_id'])) {
                    return $response['video_id'];
                } else {
                    return 0;
                }
            }
        } else {
            return 0;
        }
    }

    public function validate_vimeo_url($str)
    {
        if ($str != "") {
            $api_url = 'https://vimeo.com/api/oembed.json?url=' . urlencode($str);
            $response = @file_get_contents($api_url);

            if (!empty($response)) {
                $response = json_decode($response, true);

                if (isset($response['video_id'])) {
                    return TRUE;
                } else {
                    $this->form_validation->set_message('validate_vimeo', 'In-valid Vimeo video URL'); //hererere
                    return FALSE;
                }
            } else {
                $this->form_validation->set_message('validate_vimeo', 'In-valid Vimeo video URL');
                return FALSE;
            }
        } else {
            return TRUE;
        }
    }

    public function validate_vimeo_curl($str)
    {
        if ($str != "") {
            $api_url = 'https://vimeo.com/api/oembed.json?url=' . urlencode($str);
            $cSession = curl_init();
            curl_setopt($cSession, CURLOPT_URL, $api_url);
            curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($cSession, CURLOPT_HEADER, false);
            $response = curl_exec($cSession);
            curl_close($cSession);
            $response = json_decode($response, true); //$response = @file_get_contents($api_url);

            if (isset($response['video_id'])) {
                return TRUE;
            } else {
                $this->form_validation->set_message('validate_vimeo', 'In-valid Vimeo video URL'); //hererere
                return FALSE;
            }
        } else {
            return TRUE;
        }
    }

    function alpha_dash_space($str)
    {
        if ($str != "") {
            if (!preg_match("/^([-0-9])+$/i", $str)) {
                $this->form_validation->set_message('alpha_dash_space', 'The %s field may only contain numeric characters and dashes.');
                return FALSE;
            } else {
                return TRUE;
            }
        } else
            return TRUE;
    }

    function save_jobs_to_feed()
    {
        if ($this->input->post()) {
            $data['session'] = $this->session->userdata('logged_in');
            $company_id = $data["session"]["company_detail"]["sid"];
            $employer_id = $data["session"]["employer_detail"]["sid"];
            $formpost = $this->input->post(NULL, TRUE);

            foreach ($formpost['product_sid'] as $key => $productIdWithDay) {
                $explodedArray = explode(",", $productIdWithDay);
                $formpost['product_sid'][$key] = $explodedArray[0];
                $formpost['no_of_days'][$key] = $explodedArray[1];
            }

            $product_type = 'job-board';

            if (!empty($formpost['product_sid'])) {
                $productCounter = $this->dashboard_model->checkPurchasedProductQty($formpost['product_sid'], $company_id, $product_type);
                //Start=>> checking that the applied products still have counter greater than 1?
                foreach ($productCounter as $product) {
                    if ($product['remaining_qty'] <= 0) {
                        echo "error";
                        exit;
                    }
                }
                //End=>> checking that the applied products still have counter greater than 1?
                $jobData['job_sid'] = $formpost['job_sid'];
                $jobData['employer_sid'] = $formpost['employer_sid'];
                $jobData['purchased_date'] = date('Y-m-d H:i:s');

                foreach ($formpost['product_sid'] as $key => $productId) {
                    $no_of_days = $formpost['no_of_days'][$key];
                    $jobData['product_sid'] = $productId;
                    $invoice_price = $this->dashboard_model->get_product_budget($productId, $company_id, $no_of_days);
                    $jobData['budget'] = $invoice_price;
                    $result = $this->dashboard_model->productDetail($productId);
                    $jobData['company_sid'] = $company_id;
                    //New Scenario to Set the Expiry Date from Super Admin Upon Activation instead store Number of Days
                    //$jobData['expiry_date'] = date('Y-m-d H:i:s', strtotime("+" . $no_of_days . " days"));
                    $jobData['no_of_days'] = $no_of_days;
                    $this->dashboard_model->insertJobFeed($jobData);
                    //deduct purchased product from order table
                    $this->dashboard_model->deduct_product_qty($productId, $company_id, $no_of_days);
                    //New Job Products Tracking
                    $this->dashboard_model->mark_product_as_used($productId, $company_id, $employer_id, $jobData['job_sid']);
                }
                echo "success";
            } else {
                echo "error";
            }
        }
    }

    function import_google_fonts()
    {
        echo "Import them";
        $jSonData = file_get_contents('https://www.googleapis.com/webfonts/v1/webfonts?key=AIzaSyDe1NtsblZE0ibkbt9k7JJlPkLvuclq0r4&sort=popularity');
        $data = json_decode($jSonData, true);
        $data = $data['items'];
        $insert_data = array();

        foreach ($data as $key => $value) {
            if (isset($value['files']['regular'])) {
                $insert_data[] = array(
                    'font_family' => $value['family'],
                    'font_url' => $value['files']['regular']
                );
            }
        }

        echo "<pre>";
        print_r($insert_data);
        //$this->db->insert_batch('google_fonts', $insert_data);
    }


    //
    public function subordinatesReport($departments = "all", $teams = "all", $employees = "all", $courses = "all")
    {
        //
        $this->load->model('v1/course_model');
        $data = [];
        //
        $session = $this->session->userdata('logged_in');
        //
        $companyId = $session['company_detail']['sid'];
        $employeeId = $session['employer_detail']['sid'];
        //
        $data['security_details'] = db_get_access_level_details($employeeId);
        //
        $subordinateInfo = getMyDepartmentAndTeams($employeeId, "courses");
        $subordinateInfo['courses'] = $this->course_model->getActiveCompanyCourses($companyId);
        //
        $haveSubordinate = 'no';
        //
        if (!empty($subordinateInfo['employees'])) {
            //
            $subordinateInfo['total_course'] = 0;
            $subordinateInfo['expire_soon'] = 0;
            $subordinateInfo['expired'] = 0;
            $subordinateInfo['started'] = 0;
            $subordinateInfo['completed'] = 0;
            $subordinateInfo['ready_to_start'] = 0;
            //
            unset($subordinateInfo['employees'][$employeeId]);
            //
            foreach ($subordinateInfo['employees'] as $key => $subordinateEmployee) {
                //
                $teamId = $subordinateEmployee['team_sid'];
                $subordinateInfo['employees'][$key]['department_name'] =  isset($subordinateInfo['teams'][$teamId]) ? $subordinateInfo['teams'][$teamId]["department_name"] : "N/A";
                $subordinateInfo['employees'][$key]['team_name'] =  isset($subordinateInfo['teams'][$teamId]) ? $subordinateInfo['teams'][$teamId]["name"] : "N/A";
                //
                if (isset($subordinateEmployee['coursesInfo'])) {
                    //
                    if (isset($_GET['courses']) && $_GET['courses'][0] != "all") {
                        $filterCourses = getCoursesInfo(implode(',', $_GET['courses']), $key);
                        //
                        $subordinateInfo['employees'][$key]['coursesInfo']['total_course'] = $filterCourses['total_course'];
                        $subordinateInfo['employees'][$key]['coursesInfo']['expire_soon'] = $filterCourses['expire_soon'];
                        $subordinateInfo['employees'][$key]['coursesInfo']['expired'] = $filterCourses['expired'];
                        $subordinateInfo['employees'][$key]['coursesInfo']['started'] = $filterCourses['started'];
                        $subordinateInfo['employees'][$key]['coursesInfo']['completed'] = $filterCourses['completed'];
                        $subordinateInfo['employees'][$key]['coursesInfo']['ready_to_start'] = $filterCourses['ready_to_start'];
                    }
                    //
                    $subordinateInfo['total_course'] = $subordinateInfo['total_course'] + $subordinateEmployee['coursesInfo']['total_course'];
                    $subordinateInfo['expire_soon'] = $subordinateInfo['expire_soon'] + $subordinateEmployee['coursesInfo']['expire_soon'];
                    $subordinateInfo['expired'] = $subordinateInfo['expired'] + $subordinateEmployee['coursesInfo']['expired'];
                    $subordinateInfo['started'] = $subordinateInfo['started'] + $subordinateEmployee['coursesInfo']['started'];
                    $subordinateInfo['completed'] = $subordinateInfo['completed'] + $subordinateEmployee['coursesInfo']['completed'];
                    $subordinateInfo['ready_to_start'] = $subordinateInfo['ready_to_start'] + $subordinateEmployee['coursesInfo']['ready_to_start'];
                }
            }
            //
            // Enter subordinate json into DB
            $haveSubordinate = 'yes';
        }

        $data['haveSubordinate'] = $haveSubordinate;
        $data['subordinateInfo'] = $subordinateInfo;
        return $data;
    }
}
