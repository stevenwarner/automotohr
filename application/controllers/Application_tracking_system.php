<?php defined('BASEPATH') or exit('No direct script access allowed');
ini_set("memory_limit", "1024M");


class Application_tracking_system extends Public_Controller
{
    public function __construct()
    {
        parent::__construct();
        require_once(APPPATH . 'libraries/aws/aws.php');
        $this->load->library('pagination');
        $this->load->model('application_tracking_system_model');
        $this->load->model('job_approval_rights_model');
        $this->load->model('manage_admin/remarket_model');
        // $this->load->model('manage_admin/interview_questionnaires_model');
        $this->load->model('portal_email_templates_model');
        // check and add status
        $this->load->model('Application_status_model', 'application_status_model');
        $this->application_status_model
            ->replaceStatusCheck(
                $this->session->userdata('logged_in')['company_detail']['sid'] ?? 0
            );
    }

    public function index($archive = 'active', $searchKeyword = NULL, $job_sid = NULL, $status = NULL, $job_fit_category_sid = 0, $app_type = 'all', $fair_type = 'all', $ques_status = 'all', $emp_app_status = 'all')
    {
        // _e($this->db->get('portal_company_sms_module')->result_array(), true, true);
        if ($this->session->userdata('logged_in')) {
            $job_sid_urldecode                                                  = urldecode($job_sid);
            $app_type                                                           = urldecode($app_type);
            $fair_type                                                          = urldecode($fair_type);
            $ques_status                                                        = urldecode($ques_status);
            $emp_app_status                                                     = urldecode($emp_app_status);
            $search_activated                                                   = 0;
            $all_manual_applicants                                              = 0;
            $all_talent_applicants                                              = 0;
            $all_job_fair_applicants                                            = 0;
            $applicant_total                                                    = 0;
            $applicant_total_pagination                                         = 0;

            if (($searchKeyword != NULL && $searchKeyword != 'all') || ($job_sid != NULL && $job_sid != 'all' && $job_sid != 'null') || ($status != NULL && $status != 'all') || $job_fit_category_sid > 0) {
                $search_activated                                           = 1;
            }

            $data['session']                                                    = $this->session->userdata('logged_in');
            $company_sid                                                        = $data['session']['company_detail']['sid'];
            // Check and set the company sms module
            // phone number
            company_sms_phonenumber(
                $data['session']['company_detail']['sms_module_status'],
                $company_sid,
                $data,
                $this
            );
            $company_email                                                      = $data['session']['company_detail']['email'];
            $company_name                                                       = $data['session']['company_detail']['CompanyName'];
            $employers_details                                                  = $data['session']['employer_detail'];
            $employer_sid                                                       = $employers_details['sid'];
            $access_level                                                       = $employers_details['access_level'];
            $ats_active_job_flag                                                = null; // get both active and inactive jobs

            if (isset($data['session']['portal_detail']['ats_active_job_flag'])) {
                $ats_active_job_flag                                            = $data['session']['portal_detail']['ats_active_job_flag'];
            }

            $is_admin                                                           = false;

            if ($access_level == 'Admin') {
                $is_admin                                                       = true;
            }

            if ($ats_active_job_flag == 0) {
                $ats_active_job_flag                                            = null; // get both active and inactive jobs
            }

            $security_sid                                                       = $employer_sid;

            //            if (!$this->session->userdata('security_details_' . $security_sid)) {
            //                $security_details                                               = db_get_access_level_details($security_sid);
            //                $this->session->set_userdata('security_details_' . $security_sid, $security_details);
            //            }

            //            $security_details                                                   = $this->session->userdata('security_details_' . $security_sid);
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;
            check_access_permissions($security_details, 'dashboard', 'application_tracking');
            $data['remarket_company_settings'] = $this->remarket_model->get_remarket_company_settings($company_sid);
            $data['title']                                                      = 'Application Tracking System';

            if (!$this->session->userdata('job_fit_categories_' . $security_sid)) {
                $job_fit_categories                                             = db_get_job_category($company_sid);
                $this->session->set_userdata('job_fit_categories_' . $security_sid, $job_fit_categories);
            }

            $data['job_fit_categories']                                         = $this->session->userdata('job_fit_categories_' . $security_sid);
            $data['search_activated']                                           = $search_activated;
            $data['app_type']                                                   = $app_type;
            $data['fair_type']                                                  = $fair_type;
            $data['ques_status']                                                = $ques_status;
            $data['emp_app_status']                                             = $emp_app_status;

            if (!$this->session->userdata('has_access_to_profile' . $security_sid)) {
                $has_access_to_profile = check_access_permissions_for_view($security_details, 'applicant_profile');
                $this->session->set_userdata('has_access_to_profile_' . $security_sid, $has_access_to_profile);
            }

            $data['has_access_to_profile']                                      = $this->session->userdata('has_access_to_profile_' . $security_sid);

            if (isset($_POST['delete_contacts']) && $_POST['delete_contacts'] == 'true') {
                $delete_fields = $_POST['ej_check'];

                foreach ($delete_fields as $key => $value) {
                    $this->application_tracking_system_model->delete_applicant($value);
                }

                $success_message = 'Application(s) deleted successfully';
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            }

            if (isset($_POST['mc_hire_contacts']) && $_POST['mc_hire_contacts'] == 'true') {
                $hire_fields = $_POST['mc_check'];

                foreach ($hire_fields as $key => $value) {
                    $this->application_tracking_system_model->mc_hire_applicant($value, 'portal_manual_candidates');
                }

                $success_message = 'Applicant(s) hired successfully';
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            }

            if (isset($_POST['mc_decline_contacts']) && $_POST['mc_decline_contacts'] == 'true') {
                $decline_fields = $_POST['mc_check'];

                foreach ($decline_fields as $key => $value) {
                    $this->application_tracking_system_model->mc_decline_applicant($value);
                }

                $success_message = 'Applicant(s) declined successfully';
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            }

            if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'ajax_update_status') {
                $sid                                                            = $_REQUEST['id'];
                $status                                                         = $_REQUEST['status'];
                $status_sid                                                     = $_REQUEST['status_sid'];

                //
                $oldStatus = getApplicantOnboardingPreviousStatus($sid);
                $this->application_tracking_system_model->change_current_status($sid, $status, $company_sid, 'portal_applicant_jobs_list');

                // Log 
                $data['session'] = $this->session->userdata('logged_in');
                $employers_details  = $data['session']['employer_detail'];
                $employer_sid       = $employers_details['sid'];
                saveApplicantOnboardingStatusLog($sid, $employer_sid, $status, $oldStatus);
                // load indeed library
                $this->load->model("indeed_model");
                $this->indeed_model->pushTheApplicantStatus(
                    $status,
                    $sid,
                    $company_sid
                );
                echo 'Done';
                exit;
            }

            if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'ajax_update_status_candidate') {
                $sid                                                            = $_REQUEST['id'];
                $status                                                         = $_REQUEST['status'];
                $this->application_tracking_system_model->change_current_status($sid, $status, $company_sid, 'portal_manual_candidates'); // function name changed with new pararmeter!
                // load indeed library
                $this->load->model("indeed_model");
                $this->indeed_model->pushTheApplicantStatus(
                    $status,
                    $sid,
                    $company_sid
                );
                echo 'Done';
                exit;
            }

            if ($archive == 'active' || $archive == 'assigned_to' || $archive == 'assigned_by' || $archive == 'onboarding') {
                $archived = 0;
            } else if ($archive == 'archive') {
                $archived = 1;
            }

            $assigned_applicants_sids                                           = array();

            if ($archive == 'assigned_to') {
                $assigned_applicants_sids                                       = $this->application_tracking_system_model->get_all_applicants_assigned_to_me($company_sid, $employer_sid);
            } else if ($archive == 'assigned_by') {
                $assigned_applicants_sids                                       = $this->application_tracking_system_model->get_all_applicants_assigned_by_me($company_sid, $employer_sid);
            }

            $records_per_page                                                   = 30;
            $baseUrl                                                            = base_url('application_tracking_system') . '/' . $archive . '/' . urlencode($searchKeyword) . '/' . $job_sid . '/' . $status . '/' . $job_fit_category_sid . '/' . $app_type . '/' . $fair_type . '/' . $ques_status . '/' . $emp_app_status;
            $uri_segment                                                        = 11;
            $keywords                                                           = '';
            $my_offset                                                          = 0;
            $page                                                               = ($this->uri->segment(11)) ? $this->uri->segment(11) : 0;

            if ($page > 1) {
                $my_offset                                                      = ($page - 1) * $records_per_page;
            }
            //**** search and filtration code ****//
            if ($searchKeyword != null) {                                       // sets the keywords for search and $data
                $data['search']                                                 = 'true';

                if ($searchKeyword != 'all') {
                    $data['searchValue']                                        = urldecode($searchKeyword);
                    $keywords                                                   = urldecode($searchKeyword);
                    $data['keyword']                                            = $keywords;
                } else {
                    $data['searchValue']                                        = '';
                    $keywords                                                   = '';
                    $data['keyword']                                            = '';
                }
            } else {
                $data['keyword']                                                = '';
            }

            $data['status']                                                     = $status; // sets the status for search and $data
            $data['job_fit_category_sid']                                       = $job_fit_category_sid;
            $status                                                             = urldecode($status);
            $status                                                             = str_replace("_", " ", $status);
            $status                                                             = ucwords($status);
            $applicant_filters                                                  = array(); // create the filter array
            $have_status                                                        = $this->application_tracking_system_model->have_status_records($company_sid);
            $data['have_status']                                                = $have_status;

            if ($data['have_status'] == true) {
                $company_statuses                                               = $this->application_tracking_system_model->get_company_statuses($company_sid);
                $data['company_statuses']                                       = $company_statuses;
            }

            if ($status != '' && $status != NULL && $status != 'all' && $status != 'All') {
                if ($data['have_status'] == true) {
                    $status_sid                                                 = $this->application_tracking_system_model->get_status_sid($company_sid, $status);
                    $applicant_filters['status_sid']                            = $status_sid;
                } else {
                    $applicant_filters['status']                                = $status;
                }
            }

            //$applicants_not_hired_sids = $this->application_tracking_system_model->get_not_hired_applicants($company_sid);
            if ($keywords != '' && $keywords != NULL && $keywords != 'all' && $keywords != '0') {
                $applicants = $this->application_tracking_system_model->get_applicants_by_search($company_sid, $employer_sid, $archived, $keywords, $records_per_page, $my_offset, $assigned_applicants_sids, $archive, $app_type, $is_admin, $fair_type, $ques_status, $emp_app_status);
                $applicant_total_array = $this->application_tracking_system_model->get_applicants_by_search_count($company_sid, $employer_sid, $archived, $keywords, $assigned_applicants_sids, $archive, $app_type, $is_admin, $fair_type, $ques_status, $emp_app_status);

                if (!empty($applicant_total_array)) {
                    $applicant_total = $applicant_total_array['all_job_applicants'];
                    $all_manual_applicants = $applicant_total_array['all_manual_applicants'];
                    $all_talent_applicants = $applicant_total_array['all_talent_applicants'];
                    $all_job_fair_applicants = $applicant_total_array['all_job_fair_applicants'];

                    if ($app_type == 'all' || $app_type == null) {
                        $applicant_total_pagination = $applicant_total + $all_manual_applicants + $all_talent_applicants + $all_job_fair_applicants;
                    } else {
                        if (strtolower($app_type) == 'applicant') {
                            $applicant_total_pagination = $applicant_total;
                        } elseif (strtolower($app_type) == 'manual candidate') {
                            $applicant_total_pagination = $all_manual_applicants;
                        } elseif (strtolower($app_type) == 'talent network') {
                            $applicant_total_pagination = $all_talent_applicants;
                        } elseif (strtolower($app_type) == 'job fair') {
                            $applicant_total_pagination = $all_job_fair_applicants;
                        }
                    }
                }
            } else if ($job_sid_urldecode != null && preg_replace('/[a-z]/', '', $job_sid_urldecode) > 0 && $job_sid_urldecode != 'all') {
                $applicants = $this->application_tracking_system_model->get_job_specific_applicants($company_sid, $employer_sid, $job_sid_urldecode, $archived, $records_per_page, $my_offset, $applicant_filters, $job_fit_category_sid, $assigned_applicants_sids, $archive, $app_type, $is_admin, $fair_type, $ques_status, $emp_app_status);
                $applicant_total_array = $this->application_tracking_system_model->get_job_specific_applicants_count($company_sid, $employer_sid, $job_sid_urldecode, $archived, $applicant_filters, $job_fit_category_sid, $assigned_applicants_sids, $archive, $app_type, $is_admin, $fair_type, $ques_status, $emp_app_status);

                if (!empty($applicant_total_array)) {
                    $applicant_total = $applicant_total_array['all_job_applicants'];
                    $all_manual_applicants = $applicant_total_array['all_manual_applicants'];
                    $all_talent_applicants = $applicant_total_array['all_talent_applicants'];
                    $all_job_fair_applicants = $applicant_total_array['all_job_fair_applicants'];

                    if ($app_type == 'all' || $app_type == null) {
                        $applicant_total_pagination = $applicant_total + $all_manual_applicants + $all_talent_applicants + $all_job_fair_applicants;
                    } else {
                        if (strtolower($app_type) == 'applicant') {
                            $applicant_total_pagination = $applicant_total;
                        } elseif (strtolower($app_type) == 'manual candidate') {
                            $applicant_total_pagination = $all_manual_applicants;
                        } elseif (strtolower($app_type) == 'talent network') {
                            $applicant_total_pagination = $all_talent_applicants;
                        } elseif (strtolower($app_type) == 'job fair') {
                            $applicant_total_pagination = $all_job_fair_applicants;
                        }
                    }
                }
            } else {
                if (is_admin($employer_sid) || $archive == 'assigned_to' || $archive == 'assigned_by') { // logged in user is an admin
                    $applicants = $this->application_tracking_system_model->get_admin_jobs_and_applicants($company_sid, $archived, $records_per_page, $my_offset, $applicant_filters, $job_fit_category_sid, $assigned_applicants_sids, $archive, $app_type, $is_admin, $fair_type, $ques_status, $emp_app_status);
                    $applicant_total_array = $this->application_tracking_system_model->get_admin_jobs_and_applicants_count($company_sid, $archived, $applicant_filters, $job_fit_category_sid, $assigned_applicants_sids, $archive, $app_type, $is_admin, $fair_type, $ques_status, $emp_app_status);

                    //print_r($applicant_total_array);
                    if (!empty($applicant_total_array)) {
                        $applicant_total = $applicant_total_array['all_job_applicants'];
                        $all_manual_applicants = $applicant_total_array['all_manual_applicants'];
                        $all_talent_applicants = $applicant_total_array['all_talent_applicants'];
                        $all_job_fair_applicants = $applicant_total_array['all_job_fair_applicants'];

                        if ($app_type == 'all' || $app_type == null) {
                            $applicant_total_pagination = $applicant_total + $all_manual_applicants + $all_talent_applicants + $all_job_fair_applicants;
                        } else {
                            if (strtolower($app_type) == 'applicant') {
                                $applicant_total_pagination = $applicant_total;
                            } elseif (strtolower($app_type) == 'manual candidate') {
                                $applicant_total_pagination = $all_manual_applicants;
                            } elseif (strtolower($app_type) == 'talent network') {
                                $applicant_total_pagination = $all_talent_applicants;
                            } elseif (strtolower($app_type) == 'job fair') {
                                $applicant_total_pagination = $all_job_fair_applicants;
                            }
                        }
                    }
                } else { // logged in user is an employee


                    $applicants = $this->application_tracking_system_model->get_employee_jobs_and_applicants($company_sid, $employer_sid, $archived, $records_per_page, $my_offset, $applicant_filters, $job_fit_category_sid, $assigned_applicants_sids, $archive, $app_type, $is_admin, $fair_type, $ques_status, $emp_app_status);
                    $applicant_total_array = $this->application_tracking_system_model->get_employee_jobs_and_applicants_count($company_sid, $employer_sid, $archived, $applicant_filters, $job_fit_category_sid, $assigned_applicants_sids, $archive, $app_type, $is_admin, $fair_type, $ques_status, $emp_app_status);
                    if (!empty($applicant_total_array)) {
                        $applicant_total = $applicant_total_array['all_job_applicants'];
                        $all_manual_applicants = $applicant_total_array['all_manual_applicants'];
                        $all_talent_applicants = $applicant_total_array['all_talent_applicants'];
                        $all_job_fair_applicants = $applicant_total_array['all_job_fair_applicants'];

                        if ($app_type == 'all' || $app_type == null) {
                            $applicant_total_pagination = $applicant_total + $all_manual_applicants + $all_talent_applicants + $all_job_fair_applicants;
                        } else {
                            if (strtolower($app_type) == 'applicant') {
                                $applicant_total_pagination                     = $applicant_total;
                            } elseif (strtolower($app_type) == 'manual candidate') {
                                $applicant_total_pagination                     = $all_manual_applicants;
                            } elseif (strtolower($app_type) == 'talent network') {
                                $applicant_total_pagination                     = $all_talent_applicants;
                            } elseif (strtolower($app_type) == 'job fair') {
                                $applicant_total_pagination                     = $all_job_fair_applicants;
                            }
                        }
                    }
                }
            }

            if ($is_admin) {
                $data['all_jobs']                                               = $this->application_tracking_system_model->get_all_jobs_company_specific($company_sid, $ats_active_job_flag);
            } else {
                $data['all_jobs']                                               = $this->application_tracking_system_model->get_all_jobs_company_and_employer_specific($company_sid, $employer_sid, $ats_active_job_flag);
            }

            $config                                                             = array();
            $config['base_url']                                                 = $baseUrl;
            $config['total_rows']                                               = $applicant_total_pagination;
            $config['per_page']                                                 = $records_per_page;
            $config['uri_segment']                                              = $uri_segment;
            $choice                                                             = $config['total_rows'] / $config['per_page'];
            $config['num_links']                                                = 4; //ceil($choice);
            $config['use_page_numbers']                                         = true;
            $config['full_tag_open']                                            = '<nav class="hr-pagination"><ul>';
            $config['full_tag_close']                                           = '</ul></nav><!--pagination-->';
            $config['first_link']                                               = '&laquo; First';
            $config['first_tag_open']                                           = '<li class="prev page">';
            $config['first_tag_close']                                          = '</li>';
            $config['last_link']                                                = 'Last &raquo;';
            $config['last_tag_open']                                            = '<li class="next page">';
            $config['last_tag_close']                                           = '</li>';
            $config['next_link']                                                = '<i class="fa fa-angle-right"></i>';
            $config['next_tag_open']                                            = '<li class="next page">';
            $config['next_tag_close']                                           = '</li>';
            $config['prev_link']                                                = '<i class="fa fa-angle-left"></i>';
            $config['prev_tag_open']                                            = '<li class="prev page">';
            $config['prev_tag_close']                                           = '</li>';
            $config['cur_tag_open']                                             = '<li class="active"><a href="">';
            $config['cur_tag_close']                                            = '</a></li>';
            $config['num_tag_open']                                             = '<li class="page">';
            $config['num_tag_close']                                            = '</li>';
            $this->pagination->initialize($config);
            $data['links']                                                      = $this->pagination->create_links();

            /*foreach ($applicants as $key => $applicant) {
                $average_interview_score = $this->interview_questionnaires_model->get_questionnaire_average_score($company_sid, $applicant['applicant_sid'], 'applicant', 0);
                $applicants[$key]['interview_score'] = $average_interview_score;
                $video_interview_scores = $this->application_tracking_system_model->get_applicant_video_interview_rating($company_sid, $applicant['applicant_sid']);
                $average_rating = 0;
                $total_rating = 0;

                if (!empty($video_interview_scores)) {
                    foreach ($video_interview_scores as $score) {
                        $total_rating = $total_rating + $score['rating'];
                    }
                }

                if (count($video_interview_scores) > 0) {
                    $average_rating = $total_rating / count($video_interview_scores);
                }

                $applicants[$key]['video_interview_score'] = round($average_rating, 1);
            } */

            $employer_id                                                        = $employer_sid;
            $job_fair_configuration                                             = $this->application_tracking_system_model->job_fair_configuration($company_sid);
            $data['job_fair_configuration']                                     = $job_fair_configuration;
            $job_fair_forms                                                     = array();

            if ($job_fair_configuration != 0) { // get all job fairs and their keys
                $job_fair_forms = $this->application_tracking_system_model->job_fair_forms($company_sid);
            }

            $data['job_fair_forms']                                             = $job_fair_forms;
            //**** code for graph ****//
            if ($archived == 0) {
                $ApplciantPerMonth = $this->application_tracking_system_model->getApplicantCountByMonth('Applicant', $company_sid);
                $ManualPerMonth = $this->application_tracking_system_model->getApplicantCountByMonth('Manual Candidate', $company_sid);
                $TalentPerMonth = $this->application_tracking_system_model->getApplicantCountByMonth('Talent Network', $company_sid);
                $JobFairPerMonth = $this->application_tracking_system_model->getApplicantCountByMonth('Job Fair', $company_sid);

                for ($i = 1; $i <= 12; $i++) {
                    $countApp = 0;

                    foreach ($ApplciantPerMonth as $app) {
                        if ($app['month'] == $i) {
                            $countApp = $app['count'];
                        }
                    }

                    $countManual = 0;

                    foreach ($ManualPerMonth as $manual) {
                        if ($manual['month'] == $i) {
                            $countManual = $manual['count'];
                        }
                    }

                    $countTalent = 0;

                    foreach ($TalentPerMonth as $talent) {
                        if ($talent['month'] == $i) {
                            $countTalent = $talent['count'];
                        }
                    }

                    $countJobFair = 0;

                    foreach ($JobFairPerMonth as $jobfair) {
                        if ($jobfair['month'] == $i) {
                            $countJobFair = $jobfair['count'];
                        }
                    }

                    if ($job_fair_configuration == 0) {
                        $newNewArray[$i] = array(
                            "countApp" => $countApp,
                            "countManual" => $countManual,
                            "countTalent" => $countTalent
                        );
                    } else {
                        $newNewArray[$i] = array(
                            "countApp" => $countApp,
                            "countManual" => $countManual,
                            "countTalent" => $countTalent,
                            "countJobFair" => $countJobFair
                        );
                    }
                }

                if ($job_fair_configuration == 1) {
                    $newGraph[0] = array('Month', 'Job Applicants', 'Manual Candidates', 'Talent Network', 'Job Fair');
                } else {
                    $newGraph[0] = array('Month', 'Job Applicants', 'Manual Candidates', 'Talent Network');
                }

                $i = 1;

                foreach ($newNewArray as $key => $month) {
                    if ($job_fair_configuration == 1) {
                        $newGraph[$i] = array(
                            substr(date("F", mktime(0, 0, 0, $key, 10)), 0, 3),
                            intval($month['countApp']),
                            intval($month['countManual']),
                            intval($month['countTalent']),
                            intval($month['countJobFair'])
                        );
                    } else {
                        $newGraph[$i] = array(
                            substr(date("F", mktime(0, 0, 0, $key, 10)), 0, 3),
                            intval($month['countApp']),
                            intval($month['countManual']),
                            intval($month['countTalent'])
                        );
                    }

                    $i++;
                }

                $data['graph'] = json_encode($newGraph);
                //getting data for PieChart start
                $newChart[0] = array("Applicant Type", "Count");
                $newChart[1] = array("Job Applicants", intval($applicant_total));
                $newChart[2] = array("Talent Network", intval($all_talent_applicants));
                $newChart[3] = array("Manual Candidates", intval($all_manual_applicants));

                if ($job_fair_configuration == 1) {
                    $newChart[4] = array("Job Fair", intval($all_job_fair_applicants));
                }

                $data['chart'] = json_encode($newChart);
                //getting data for PieChart ends
            }
            //**** code for graph ****//

            if ($job_sid_urldecode != null || $job_sid_urldecode != 'all') {
                $data['job_sid']                                                = $job_sid_urldecode;
                $data['job_sid_array']                                          = explode(',', $job_sid_urldecode);
            }

            $data['applicant_total']                                            = $applicant_total;
            $data['all_manual_applicants']                                      = $all_manual_applicants;
            $data['all_talent_applicants']                                      = $all_talent_applicants;
            $data['all_job_fair_applicants']                                    = $all_job_fair_applicants;
            $data['all_job_applicants']                                         = $applicant_total;
            $data['archive']                                                    = $archive;
            $data['archived']                                                   = $archived;
            $data['employer_jobs']                                              = $applicants;
            $data['employer_sid']                                               = $employer_sid;
            $data['jobs_approval_module_status']                                = $this->job_approval_rights_model->GetModuleStatus($company_sid, 'jobs');
            $questionnaires                                                     = $this->application_tracking_system_model->get_all_questionnaires_by_employer($company_sid, 'job'); //Getting questionnaires of company
            $data['questionnaires']                                             = $questionnaires;
            $portal_email_templates                                             = $this->application_tracking_system_model->get_portal_email_templates($company_sid);

            foreach ($portal_email_templates as $key => $template) {
                $portal_email_templates[$key]['attachments']                    = $this->portal_email_templates_model->get_all_email_template_attachments($template['sid']);
            }

            $data['portal_email_templates'] = $portal_email_templates;
            $emailTemplateData                                                  = get_email_template(SEND_CANDIDATES_INFO);
            $data['candidate_notification_template']                            = $emailTemplateData;
            $data['employees']                                                  = get_users_list($company_sid, 'employee', 'all');
            // echo "<pre>"; _e($data,true,true);
            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/application_tracking_system/index');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    } // end of index

    public function downloadFile($fileName)
    {
        $path = AWS_S3_BUCKET_URL . $fileName;
        header('Content-Description: File Transfer');
        header("Content-Type: application/octet-stream");
        header('Content-Disposition: attachment; filename=' . $fileName);
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($path));
        $file_content = file_get_contents($path);
        echo $file_content;
    }

    public function update_status()
    {
        $data['session'] = $this->session->userdata('logged_in');
        $company_sid = $data["session"]["company_detail"]["sid"];

        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'ajax_update_status') {
            $sid = $_REQUEST['id'];
            $status = $_REQUEST['status'];
            $status_sid = $_REQUEST['status_sid'];
            $this->application_tracking_system_model->update_applicant_status($company_sid, $sid, $status_sid, $status);
            echo 'success';
            exit;
        } elseif (isset($_REQUEST['action']) && $_REQUEST['action'] == 'ajax_update_status_candidate') {
            $sid = $_REQUEST['id'];
            $status = $_REQUEST['status'];
            $this->application_tracking_system_model->change_current_status($sid, $status, $company_sid, 'portal_manual_candidates');
            exit;
        }
    }

    function active_single_applicant()
    {
        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'active_single_applicant') {
            $sid = $_REQUEST['active_id'];
            $this->application_tracking_system_model->active_single_applicant($sid);
            echo "Done";
            exit;
        }
    }

    public function applicant_profile($app_id = NULL, $job_list_sid = NULL, $tab_type = false)
    {
        if ($app_id == NULL) {
            redirect('application_tracking_system/active/all/all/all/all/all/all');
        } else {
            $ats_full_url                                                       = $this->session->userdata('ats_full_url');
            $ats_params                                                         = $this->session->userdata('ats_params');
            $data                                                               = applicant_right_nav($app_id, $job_list_sid, $ats_params);
            $company_sid                                                        = $data['session']['company_detail']['sid'];
            $employer_sid                                                       = $data['session']['employer_detail']['sid'];
            $employer_details                                                   = $data['session']['employer_detail'];
            $access_level                                                       = $employer_details['access_level'];
            $security_sid                                                       = $employer_sid;
            $employer_id                                                        = $data['applicant_info']['employer_sid'];
            $data['main_employer_id'] = $security_sid;
            $data['tab_type'] = $tab_type;
            if ($company_sid != $employer_id) {
                $this->session->set_flashdata('message', '<b>Error:</b> Applicant not found!');
                redirect('application_tracking_system/active/all/all/all/all');
            }
            $data['company_timezone'] = !empty($data['session']['company_detail']['timezone']) ? $data['session']['company_detail']['timezone'] : STORE_DEFAULT_TIMEZONE_ABBR;

            if (!empty($data['session']['employer_detail']['timezone']))
                $data['employer_timezone'] =   $data['session']['employer_detail']['timezone'];
            else
                $data['employer_timezone'] = !empty($data['session']['company_detail']['timezone']) ? $data['session']['company_detail']['timezone'] : STORE_DEFAULT_TIMEZONE_ABBR;

            // Check and set the company sms module
            // phone number
            company_sms_phonenumber(
                $data['session']['company_detail']['sms_module_status'],
                $company_sid,
                $data,
                $this
            );

            $security_details                                                   = $data['security_details'];
            check_access_permissions($security_details, 'application_tracking_system/active/all/all/all/all', 'applicant_profile');

            $config = array(
                array(
                    'field' => 'first_name',
                    'label' => 'First Name',
                    'rules' => 'trim|required|xss_clean'
                ),
                array(
                    'field' => 'last_name',
                    'label' => 'Last Name',
                    'rules' => 'trim|xss_clean'
                ),
                array(
                    'field' => 'email',
                    'label' => 'Email',
                    'rules' => 'valid_email|required'
                )
            );

            $this->form_validation->set_message('required', 'Please provide your %s.');
            $this->form_validation->set_error_delimiters('<label class="error">', '</label>');
            $this->form_validation->set_rules($config);
            $hired_status                                                       = $data['applicant_info']['hired_status'];

            if ($hired_status == 1) {
                $this->session->set_flashdata('message', '<b>Error:</b> No Applicant Found!');
                redirect('application_tracking_system/active/all/all/all/all');
            }

            $interview_questionnaires                                           = $this->application_tracking_system_model->get_interview_questionnaires($company_sid);
            $data['interview_questionnaires']                                   = $interview_questionnaires;
            $data['applicant_sid']                                              = $app_id;
            $data['employer_sid']                                               = $employer_sid;
            $data['main_employer_id']                                               = $employer_sid;
            $data['employer_id']                                                = $employer_sid;
            $interview_questionnaire_scores                                     = $this->application_tracking_system_model->get_interview_questionnaires_scores($app_id);
            $data['interview_questionnaire_scores']                             = $interview_questionnaire_scores;
            $data['applicant_jobs']                                             = $this->application_tracking_system_model->get_single_applicant_all_jobs($app_id, $company_sid);

            $data['have_status']                                                = $this->application_tracking_system_model->have_status_records($company_sid);

            if ($data['have_status'] == true) {
                $data['company_statuses']                                       = $this->application_tracking_system_model->get_company_statuses($company_sid);
            }

            $data['ats_full_url']                                               = $ats_full_url;

            $data['l_employment'] = 0;
            $data['ssn_required'] = 0;
            $data['dob_required'] = 0;
            // $data['ssn_required'] = $data['session']['portal_detail']['ssn_required'];
            // $data['dob_required'] = $data['session']['portal_detail']['dob_required'];
            //
            if ($data['ssn_required'] == 1) {
                //
                $this->form_validation->set_rules('SSN', 'SSN', 'required|trim|xss_clean');
            }
            //
            if ($data['dob_required'] == 1) {
                //
                $this->form_validation->set_rules('DOB', 'DOB', 'required|trim|xss_clean');
            }

            //
            if (get_company_module_status($data['session']['company_detail']['sid'], 'primary_number_required') == 1) {
                $this->form_validation->set_rules('phone_number', 'phone_number', 'required|trim|xss_clean');
            }
            $portalData = getPortalData(
                $this->session->userdata("logged_in")["company_detail"]["sid"],
                ["uniform_sizes"]
            );
            if ($portalData["uniform_sizes"]) {
                $this->form_validation->set_rules('uniform_top_size', 'Uniform top size', 'required|trim|xss_clean');
                $this->form_validation->set_rules('uniform_bottom_size', 'Uniform bottom size', 'required|trim|xss_clean');
            }
            $data['portalData'] = $portalData;


            if ($this->form_validation->run() == FALSE) { //checking if the form is submitted so i can open the form screen again
                $data['edit_form']                                              = false;

                if ($this->input->post()) {
                    $data['edit_form']                                          = true;
                }

                $data['notes_view']                                             = false;

                if (isset($_SESSION['show_notes']) && $_SESSION['show_notes'] == 'true') {
                    $data['notes_view']                                         = true;
                    $_SESSION['show_notes']                                     = 'false';
                }

                $data['show_event']                                             = false;

                if (isset($_SESSION['show_event']) && $_SESSION['show_event'] == 'true') {
                    $data['show_event']                                         = true;
                    $_SESSION['show_event']                                     = 'false';
                }

                $data['show_message']                                           = false; //checking if the form is submitted so i can open the Messages form screen again

                if (isset($_SESSION['show_message']) && $_SESSION['show_message'] == 'true') {
                    $data['show_message']                                       = true;
                    $_SESSION['show_message']                                   = 'false';
                }

                $data['error_message']                                          = '';
                $data['invalid_email']                                          = '';
                $data['applicant_notes']                                        = $this->application_tracking_system_model->getApplicantNotes($app_id); //Getting Notes
                $data['applicant_average_rating']                               = $this->application_tracking_system_model->getApplicantAverageRating($app_id, 'applicant'); //getting average rating of applicant
                $rating_result                                                  = $this->application_tracking_system_model->getApplicantAllRating($app_id, 'applicant'); //getting all rating of applicant

                if ($rating_result != NULL) {
                    $data['applicant_ratings_count']                            = $rating_result->num_rows();
                    $data['applicant_all_ratings']                              = $rating_result->result_array();
                } else {
                    $data['applicant_all_ratings']                              = NULL;
                    $data['applicant_ratings_count']                            = NULL;
                }
                //getting private messages of the user
                $rawMessages                                                    = $this->application_tracking_system_model->get_sent_messages($data['applicant_info']['email'], $app_id);

                if (!empty($rawMessages)) {
                    //
                    $i                                                          = 0;
                    //
                    foreach ($rawMessages as $message) {
                        if ($message['outbox'] == 1) {
                            $employerData                                       = $employer_details;
                            $message['profile_picture']                         = $employerData['profile_picture'];
                            $message['first_name']                              = $employerData['first_name'];
                            $message['last_name']                               = $employerData['last_name'];
                            $message['username']                                = $employerData['username'];

                            if ($message['from_id'] == "notifications@automotohr.com") {
                                $message['sender_name'] = "AutoMoto HR";
                                $message['sender_logo'] = base_url("assets/manage_admin/images/new_logo.JPG");
                            } else {
                                $message['sender_name'] = getUserNameBySID($message['from_id']);
                                $message['sender_profile_picture'] = get_employee_profile_info($message['from_id'])['profile_picture'];
                            }
                        } else {
                            $message['profile_picture']                         = $data['applicant_info']['pictures'];
                            $message['first_name']                              = $data['applicant_info']['first_name'];
                            $message['last_name']                               = $data['applicant_info']['last_name'];
                            $message['username']                                = "";
                            $message['sender_name']                             = $data['applicant_info']['first_name'] . " " . $data['applicant_info']['last_name'];
                            $message['sender_profile_picture']                  = $data['applicant_info']['pictures'];
                        }
                        //
                        $allMessages[$i]                                        = $message;
                        $i++;
                    }
                    //
                    $data['applicant_message']                                  = $allMessages;
                } else {
                    $data['applicant_message']                                  = array();
                }

                $data['company_accounts']                                       = $this->application_tracking_system_model->getCompanyAccounts($company_sid); //fetching list of all sub-accounts
                $data['upcoming_events']                                        = $this->application_tracking_system_model->get_applicant_events($app_id, 'upcoming');

                if (empty($data['applicant_info']['country'])) {
                    $data['applicant_info']['country_name']                     = "";
                } else {
                    $country_id = $data['applicant_info']['country'];
                    $data['applicant_info']['country_name']                     = $this->application_tracking_system_model->getCountryName($country_id);
                }

                if (empty($data['applicant_info']['state'])) { // get state name
                    $data['applicant_info']['state_name']                       = '';
                } else {
                    $state_id                                                   = $data['applicant_info']['state'];
                    $data['applicant_info']['state_name']                       = $this->application_tracking_system_model->getStateName($state_id);
                }

                $data['applicant_info']['test']                                 = false;
                $data['title']                                                  = 'Applicant Detail'; //header data
                $data_countries                                                 = db_get_active_countries();

                foreach ($data_countries as $value) {
                    $data_states[$value['sid']] = db_get_active_states($value['sid']);
                }

                $data['active_countries']                                       = $data_countries;
                $data['active_states']                                          = $data_states;
                $data_states_encode                                             = htmlentities(json_encode($data_states));
                $data['states']                                                 = $data_states_encode;
                $is_onboarding_configured                                       = $this->application_tracking_system_model->is_onboarding_configured($company_sid);
                $data['is_onboarding_configured']                               = $is_onboarding_configured;
                $onboarding_status                                              = $this->application_tracking_system_model->get_onboarding_status($company_sid, $app_id);
                $data['onboarding_status']                                      = $onboarding_status;
                $data['company_sid']                                            = $company_sid;
                $data['extra_info']                                             = unserialize($data['applicant_info']['extra_info']);
                $applicants_approval_module_status                              = $this->job_approval_rights_model->GetModuleStatus($company_sid, 'applicants');
                $data['applicants_approval_module_status']                      = $applicants_approval_module_status;
                $portal_email_templates                                         = $this->application_tracking_system_model->get_portal_email_templates($company_sid);

                foreach ($portal_email_templates as $key => $template) {
                    $portal_email_templates[$key]['attachments']                = $this->portal_email_templates_model->get_all_email_template_attachments($template['sid']);
                }

                $this->load->model('resend_screening_questionnaires_model');
                $questionnaires                                                 = $this->resend_screening_questionnaires_model->get_all_questionnaires_by_employer($company_sid); //Getting questionnaires of company
                $data['questionnaires']                                         = $questionnaires;
                $data['portal_email_templates']                                 = $portal_email_templates;
                $addresses                                                      = $this->application_tracking_system_model->get_company_addresses($company_sid);
                $data['addresses']                                              = $addresses;
                $unique_sid                                                     = 0;
                $onboarding_url                                                 = 'javascript:;';

                if ($data['applicant_info']['is_onboarding'] == 1) { //get unique ID
                    $unique_sid                                                 = $this->application_tracking_system_model->get_applicant_unique_sid($company_sid, $app_id);
                    $onboarding_url                                             = base_url('onboarding/getting_started/' . $unique_sid . '?employer=' . $data['session']['employer_detail']['sid']);
                }

                $data['unique_sid']                                             = $unique_sid;
                $data['onboarding_url']                                         = $onboarding_url;
                $data['is_new_calendar']                                        = (int)$this->call_old_event();
                //
                $data['_ssv'] = getSSV($data['session']['employer_detail']);
                //
                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/application_tracking_system/applicant_profile');
                $this->load->view('main/footer');
            } else {
                $formpost = $this->input->post(NULL, TRUE);

                if (isset($formpost['email'])) {
                    $email_exist = $this->application_tracking_system_model->check_applicant_email_exist($app_id, $company_sid, $formpost['email']);

                    if ($email_exist == 'record_found') {
                        $this->session->set_flashdata('message', '<b>Error:</b> This email address already exists against another applicant. Please, use a different email address.');
                        redirect("applicant_profile/" . $app_id . '/' . $job_list_sid, "location");
                    }
                }


                if ($data['applicant_info']['email'] != $formpost['email']) {
                    $dataToUpdate = array(
                        'to_id' => $formpost['email']
                    ); //if email is changed then chnage email in Private message Table too.

                    $this->application_tracking_system_model->update_private_message_to_id($app_id, $data['applicant_info']['email'], $dataToUpdate);
                    $dataToUpdate = array(
                        'from_id' => $formpost['email']
                    );

                    $this->application_tracking_system_model->update_private_message_from_id($app_id, $data['applicant_info']['email'], $dataToUpdate);
                }

                $user_data = array();
                $user_data['job_fit_category_sid'] = 0;

                foreach ($formpost as $key => $value) { //Arranging company detial
                    if ($key != 'video_source' && $key != 'yt_vm_video_url' && $key != 'pre_upload_video_url' && $key != 'secondary_email' && $key != 'secondary_PhoneNumber' && $key != 'other_email' && $key != 'other_PhoneNumber' && $key != 'txt_phonenumber' && $key != 'txt_secondary_phonenumber' && $key != 'txt_other_phonenumber' && $key != 'title_option' && $key != 'template_job_title') { // exclude these values from array
                        if (is_array($value)) {
                            $value = implode(',', $value);
                        }
                        $user_data[$key] = $value;
                    }
                }


                // Added on: 03-05-2019
                unset($user_data['DOB']);


                // Reset phone number
                $user_data['phone_number'] = isset($formpost['txt_phonenumber']) ? $formpost['txt_phonenumber'] : $formpost['phone_number'];
                $secondary_phonenumber    = isset($formpost['txt_secondary_phonenumber']) ? $formpost['txt_secondary_phonenumber'] : $this->input->post('secondary_PhoneNumber', true);
                $other_phonenumber        = isset($formpost['txt_other_phonenumber']) ? $formpost['txt_other_phonenumber'] : $this->input->post('other_PhoneNumber', true);

                $date_of_birth = $this->input->post('DOB');

                if (!empty($date_of_birth) && !preg_match(XSYM_PREG, $date_of_birth)) {
                    $DOB = date('Y-m-d', strtotime(str_replace('-', '/', $date_of_birth)));
                    $user_data['dob'] = $DOB;
                }
                //
                $notified_by = $this->input->post('notified_by');
                //
                if (!empty($notified_by)) {
                    $user_data['notified_by'] = $notified_by;
                } else {
                    $user_data['notified_by'] = 'email';
                }
                //
                if ($this->input->post('SSN') && !preg_match(XSYM_PREG, $this->input->post('SSN')))
                    $user_data['ssn'] = $this->input->post('SSN');
                //
                if (preg_match(XSYM_PREG, $this->input->post('SSN'))) unset($user_data['SSN']);
                //
                $user_data['employee_number'] = $this->input->post('employee_number');
                $user_data['employer_sid'] = $employer_id;
                $user_data['extra_info'] = serialize(array('secondary_email' => $this->input->post('secondary_email'), 'secondary_PhoneNumber' => $secondary_phonenumber, 'other_email' => $this->input->post('other_email'), 'other_PhoneNumber' => $other_phonenumber));
                $video_source = $this->input->post('video_source');
                $video_id = '';

                if ($video_source != 'no_video') {
                    if (isset($_FILES['upload_video']) && !empty($_FILES['upload_video']['name'])) {
                        $random = generateRandomString(5);
                        $target_file_name = basename($_FILES["upload_video"]["name"]);
                        $upload_video_file_name = strtolower($company_sid . '/' . $random . '_' . $target_file_name);
                        $target_dir = "assets/uploaded_videos/";
                        $target_file = $target_dir . $upload_video_file_name;
                        $filename = $target_dir . $company_sid;

                        if (!file_exists($filename)) {
                            mkdir($filename);
                        }

                        if (move_uploaded_file($_FILES["upload_video"]["tmp_name"], $target_file)) {

                            $this->session->set_flashdata('message', '<strong>The file ' . basename($_FILES["upload_video"]["name"]) . ' has been uploaded.');
                        } else {

                            $this->session->set_flashdata('message', '<strong>Sorry, there was an error uploading your file.');
                            redirect('application_tracking_system/applicant_profile', 'refresh');
                        }

                        $video_id = $upload_video_file_name;
                    } else {
                        $video_id = $this->input->post('yt_vm_video_url');

                        if ($video_source == 'youtube') {
                            $url_prams = array();
                            parse_str(parse_url($video_id, PHP_URL_QUERY), $url_prams);

                            if (isset($url_prams['v'])) {
                                $video_id = $url_prams['v'];
                            } else {
                                $video_id = '';
                            }
                        } else if ($video_source == 'vimeo') {
                            $video_id = $this->vimeo_get_id($video_id);
                        } else if ($video_source == 'uploaded' && $this->input->post('pre_upload_video_url') != '') {
                            $video_id = $this->input->post('pre_upload_video_url');
                        }
                    }
                }

                $user_data['YouTube_Video'] = $video_id;
                $user_data['video_type'] = $video_source;

                if (isset($_FILES['pictures']) && $_FILES['pictures']['name'] != '' && $_FILES['pictures']['size'] > 0) {
                    $file = explode(".", $_FILES['pictures']['name']);
                    $file_name = str_replace(" ", "-", $file[0]);
                    $profilePicture = $file_name . '-' . generateRandomString(5) . '.' . $file[1];
                    $aws = new AwsSdk();
                    $aws->putToBucket($profilePicture, $_FILES['pictures']['tmp_name'], AWS_S3_BUCKET_NAME);

                    if (!empty($data['applicant_info']['pictures'])) {
                        $aws->deleteObj($data['applicant_info']['pictures'], AWS_S3_BUCKET_NAME);
                    }

                    $user_data['pictures'] = $profilePicture;
                }

                //
                if (IS_NOTIFICATION_ENABLED == 1 && $data['phone_sid'] != '') {
                    if (!sizeof($this->input->post('notified_by', true))) $user_data['notified_by'] = 'email';
                    else $user_data['notified_by'] = implode(',', $this->input->post('notified_by', true));
                }
                //
                $full_emp_app = isset($data['applicant_info']['full_employment_application']) && !empty($data['applicant_info']['full_employment_application']) ? unserialize($data['applicant_info']['full_employment_application']) : array();
                $full_emp_app['PhoneNumber'] = $this->input->post('PhoneNumber');
                $full_emp_app['TextBoxTelephoneOther'] = $this->input->post('other_PhoneNumber');
                $full_emp_app['TextBoxAddressStreetFormer3'] = $this->input->post('other_email');
                $user_data['full_employment_application'] = serialize($full_emp_app);

                //
                if ($this->input->post('temppate_job_title') && $formpost['template_job_title'] != '0') {
                    $templetJobTitleData = $formpost['template_job_title'];
                    $templetJobTitleDataArray = explode('#', $templetJobTitleData);
                    $user_data['desired_job_title'] = $templetJobTitleDataArray[1];
                    $user_data['job_title_type'] = $templetJobTitleDataArray[0];
                } else {
                    $user_data['job_title_type'] = 0;
                }


                if (!empty($user_data['desired_job_title'])) {
                    $this->application_tracking_system_model->update_applicant_job_title($job_list_sid, $user_data['desired_job_title']);
                }
                //
                unset($user_data['workers_compensation_code']);
                unset($user_data['eeoc_code']);
                unset($user_data['salary_benefits']);
                //
                if (isPayrollOrPlus(true)) {
                    $user_data['workers_compensation_code'] = $formpost['workers_compensation_code'];
                    $user_data['eeoc_code'] = $formpost['eeoc_code'];
                    $user_data['salary_benefits'] = $formpost['salary_benefits'];
                }

                //
                $user_data['uniform_top_size'] = $formpost['uniform_top_size'];
                $user_data['uniform_bottom_size'] = $formpost['uniform_bottom_size'];


                //
                $user_data['languages_speak'] = null;
                //
                $languages_speak = $formpost['secondaryLanguages'];
                unset($user_data['secondaryLanguages']);
                unset($user_data['secondaryOption']);
                //
                if ($languages_speak) {
                    $user_data['languages_speak'] = implode(',', $languages_speak);
                }
                //

                $result = $this->application_tracking_system_model->update_applicant($app_id, $user_data);

                $this->session->set_flashdata('message', '<b>Success:</b> Applicant updated successfully');

                if ($job_list_sid != NULL) {
                    redirect("applicant_profile/" . $app_id . '/' . $job_list_sid, "location");
                } else {
                    redirect("applicant_profile/" . $app_id, "location");
                }
            }
        }
    }

    public function applicant_submitted_resume_result($app_id = NULL, $applicant_id = NULL) {
        // if ($app_id == NULL || $applicant_id == NULL) {
        //     redirect('application_tracking_system/active/all/all/all/all/all/all');
        //     return;
        // }

        $ats_params = $this->session->userdata('ats_params');
        $data = applicant_right_nav($app_id, $applicant_id, $ats_params);
        $data['submitted_resume_data'] = $this->application_tracking_system_model->get_submitted_resume_data($applicant_id);


        $this->load->view('main/header', $data);
        $this->load->view('manage_employer/application_tracking_system/submitted_resume');
        $this->load->view('main/footer');
    }

    public function upload_extra_attachment()
    {
        if (isset($_FILES['newlife']) && $_FILES['newlife']['name'] != '') { //uploading Files to AWS if any
            $data['session'] = $this->session->userdata('logged_in');
            $employer_id = $data['session']['employer_detail']['sid'];
            $file = explode(".", $_FILES['newlife']['name']);
            $file_name = str_replace(" ", "-", $file[0]);
            $fileName = $file_name . '-' . generateRandomString(5) . '.' . $file[1];

            if ($_FILES['newlife']['size'] == 0) {
                $this->session->set_flashdata('message', '<b>Warning:</b> File is empty! Please try again.');

                if ($this->input->post('users_type') == 'employee') {
                    redirect('employee_profile/' . $this->input->post('applicant_job_sid'));
                } else {
                    redirect('applicant_profile/' . $this->input->post('applicant_job_sid'));
                }
            }

            $aws = new AwsSdk();
            $aws->putToBucket($fileName, $_FILES['newlife']['tmp_name'], AWS_S3_BUCKET_NAME);
            $user_data['original_name'] = $_FILES['newlife']['name'];
            $user_data['uploaded_name'] = $fileName;
            $user_data['employer_sid'] = $employer_id;
            $user_data['applicant_job_sid'] = $this->input->post('applicant_job_sid');
            $user_data['users_type'] = $this->input->post('users_type');
            $user_data['date_uploaded'] = date('Y-m-d');
        }

        $result = $this->application_tracking_system_model->upload_extra_attachments($user_data);
        $this->session->set_flashdata('message', '<b>Success:</b> File uploaded successfully');

        if ($user_data['users_type'] == 'employee') {
            redirect('employee_profile/' . $user_data['applicant_job_sid']);
        } else {
            redirect('applicant_profile/' . $user_data['applicant_job_sid']);
        }
    }

    public function upload_attachment($app_id)
    {
        if ($app_id == NULL) {
            redirect('application_tracking_system/active/all/all/all/all/all/all');
        } else {
            $session                = $this->session->userdata('logged_in');
            $company_sid            = $session["company_detail"]["sid"];
            $employer_sid           = $session['employer_detail']['sid'];

            $job_sid                = $this->input->post('job_sid', true);
            $job_type               = $this->input->post('job_type', true);

            $resume_extension = '';
            $resume_original_name = '';
            $resume_s3_name = '';

            // if (in_array($company_sid, array("7", "51"))) {
            if (!in_array($company_sid, array("0"))) {
                $resume_log_data        = array();

                if (isset($_FILES['resume']) && $_FILES['resume']['name'] != '') {

                    if ($_FILES['resume']['size'] == 0) {
                        $this->session->set_flashdata('message', '<b>Warning:</b> File is empty! Please try again.');
                        redirect("applicant_profile/" . $app_id, "location");
                    }

                    $resume_upload_file     = $_FILES['resume']['name'];
                    $upload_file            = explode(".", $resume_upload_file);
                    $resume_original_name   = $upload_file[0];
                    $resume_extension       = $upload_file[1];
                    $resume_name            = 'applicant-resume';

                    if ($_SERVER['HTTP_HOST'] == 'localhost') {
                        // $resume_s3_name = '0057-test_latest_uploaded_document-58-Yo2.pdf';
                        // $resume_s3_name = '0057-applicant-resume-68062-dzd.docx';
                        $resume_s3_name = '0057-applicant-resume-68074-IXR.pdf';
                    } else {
                        $resume_s3_name = upload_file_to_aws('resume', $company_sid, str_replace(' ', '_', $resume_name), $app_id, AWS_S3_BUCKET_NAME);
                    }

                    $old_s3_resume = $this->application_tracking_system_model->get_single_job_detail($app_id, $company_sid, $job_sid, $job_type);


                    if (!empty($old_s3_resume)) {

                        $applicant_info         = $this->application_tracking_system_model->getApplicantData($app_id);
                        $applicant_email        = $applicant_info['email'];
                        $user_type              = $applicant_info['applicant_type'];
                        $user_sid               = $app_id;

                        $resume_log_data['company_sid']             = $company_sid;
                        $resume_log_data['user_type']               = $user_type;
                        $resume_log_data['user_sid']                = $user_sid;
                        $resume_log_data['user_email']              = $applicant_email;
                        $resume_log_data['requested_by']            = $employer_sid;
                        $resume_log_data['requested_subject']       = '';
                        $resume_log_data['requested_message']       = '';
                        $resume_log_data['requested_ip_address']    =  getUserIP();
                        $resume_log_data['requested_user_agent']    = $_SERVER['HTTP_USER_AGENT'];
                        $resume_log_data['request_status']          = 3;
                        $resume_log_data['is_respond']              = 1;
                        $resume_log_data['resume_original_name']    = $resume_original_name;
                        $resume_log_data['resume_s3_name']          = $resume_s3_name;
                        $resume_log_data['resume_extension']        = $resume_extension;
                        $resume_log_data['old_resume_s3_name']      = $old_s3_resume;
                        $resume_log_data['response_date']           = date('Y-m-d H:i:s');
                        $resume_log_data['requested_date']          = date('Y-m-d H:i:s');
                        $resume_log_data['job_sid']                 = $job_sid;
                        $resume_log_data['job_type']                = $job_type;

                        $this->application_tracking_system_model->insert_resume_log($resume_log_data);
                    }

                    $this->application_tracking_system_model->update_resume_applicant_job_list($app_id, $company_sid, $job_sid, $resume_s3_name, $job_type);
                } else {
                    $user_data['resume'] = $this->input->post("old_resume");
                }

                if (isset($_FILES['cover_letter']) && $_FILES['cover_letter']['name'] != '') { //uploading cover letter to AWS
                    $file = explode(".", $_FILES['cover_letter']['name']);
                    $resume_extension = $file[1];
                    $file_name = str_replace(" ", "-", $file[0]);
                    $letter = $file_name . '-' . generateRandomString(5) . '.' . $file[1];

                    if ($_FILES['cover_letter']['size'] == 0) {
                        $this->session->set_flashdata('message', '<b>Warning:</b> File is empty! Please try again.');
                        redirect("applicant_profile/" . $app_id, "location");
                    }

                    $aws = new AwsSdk();
                    $aws->putToBucket($letter, $_FILES["cover_letter"]["tmp_name"], AWS_S3_BUCKET_NAME);
                    $user_data['cover_letter'] = $letter;
                } else {
                    $user_data['cover_letter'] = $this->input->post("old_letter");
                }

                $result = $this->application_tracking_system_model->update_applicant($app_id, $user_data);
                $this->session->set_flashdata('message', '<b>Success:</b> Attachment(s) uploaded successfully');
                redirect("applicant_profile/" . $app_id, "location");
            } else {

                if (isset($_FILES['resume']) && $_FILES['resume']['name'] != '') { //uploading Resume to AWS if any
                    $file = explode(".", $_FILES['resume']['name']);
                    $resume_extension = $file[1];
                    $resume_original_name = $file[0];
                    $file_name = str_replace(" ", "-", $file[0]);
                    $resume = $file_name . '-' . generateRandomString(5) . '.' . $file[1];
                    $resume_s3_name = $resume;
                    if ($_FILES['resume']['size'] == 0) {
                        $this->session->set_flashdata('message', '<b>Warning:</b> File is empty! Please try again.');
                        redirect("applicant_profile/" . $app_id, "location");
                    }
                    // $whitelist = array(
                    //     '127.0.0.1',
                    //     '::1'
                    // );

                    // if(!in_array(getUserIP(), $whitelist)){
                    $aws = new AwsSdk();
                    $aws->putToBucket($resume, $_FILES["resume"]["tmp_name"], AWS_S3_BUCKET_NAME);
                    // }else{
                    //     $resume = "0057-resume_hassan_213_bokhary-71860-6pW.docx";
                    // }
                    // echo $resume; exit;
                    $user_data['resume'] = $resume;

                    $data['session']        = $this->session->userdata('logged_in');
                    $company_sid            = $data["session"]["company_detail"]["sid"];

                    $jobdetails             = $this->application_tracking_system_model->get_single_job_detail_old($app_id, $company_sid, $job_sid, $job_type);
                    $resume_log_data                            = array();

                    if (isset($jobdetails['sid'])) {
                        $job_sid = $jobdetails['sid'];
                        $resume_log_data['job_type'] = "portal_applicant_jobs_list_sid";
                    } else {
                        $resume_log_data['job_type'] = 'job';
                    }

                    $employer_sid           = $data['session']['employer_detail']['sid'];
                    $applicant_info         = $this->application_tracking_system_model->getApplicantData($app_id);
                    $old_resume_s3_name     = $jobdetails['resume'] ? $jobdetails['resume'] : $applicant_info['resume'];
                    $applicant_email        = $applicant_info['email'];
                    $user_type              = $applicant_info['applicant_type'];
                    $user_sid               = $app_id;

                    $resume_log_data['company_sid']             = $company_sid;
                    $resume_log_data['user_type']               = $user_type;
                    $resume_log_data['user_sid']                = $user_sid;
                    $resume_log_data['user_email']              = $applicant_email;
                    $resume_log_data['requested_by']            = $employer_sid;
                    $resume_log_data['requested_subject']       = '';
                    $resume_log_data['requested_message']       = '';
                    $resume_log_data['requested_ip_address']    =  getUserIP();
                    $resume_log_data['requested_user_agent']    = $_SERVER['HTTP_USER_AGENT'];
                    $resume_log_data['request_status']          = 3;
                    $resume_log_data['is_respond']              = 1;
                    $resume_log_data['resume_original_name']    = $resume_original_name;
                    $resume_log_data['resume_s3_name']          = $resume_s3_name;
                    $resume_log_data['resume_extension']        = $resume_extension;
                    $resume_log_data['old_resume_s3_name']      = $old_resume_s3_name;
                    $resume_log_data['response_date']           = date('Y-m-d H:i:s');
                    $resume_log_data['requested_date']           = date('Y-m-d H:i:s');
                    $resume_log_data['job_sid']                 = $job_sid;
                    if (!empty($jobdetails['resume'])) {
                        $this->application_tracking_system_model->insert_resume_log($resume_log_data);
                    }
                } else {
                    $user_data['resume'] = $this->input->post("old_resume");
                }

                if (isset($_FILES['cover_letter']) && $_FILES['cover_letter']['name'] != '') { //uploading cover letter to AWS
                    $file = explode(".", $_FILES['cover_letter']['name']);
                    $resume_extension = $file[1];
                    $file_name = str_replace(" ", "-", $file[0]);
                    $letter = $file_name . '-' . generateRandomString(5) . '.' . $file[1];

                    if ($_FILES['cover_letter']['size'] == 0) {
                        $this->session->set_flashdata('message', '<b>Warning:</b> File is empty! Please try again.');
                        redirect("applicant_profile/" . $app_id, "location");
                    }

                    $aws = new AwsSdk();
                    $aws->putToBucket($letter, $_FILES["cover_letter"]["tmp_name"], AWS_S3_BUCKET_NAME);
                    $user_data['cover_letter'] = $letter;
                } else {
                    $user_data['cover_letter'] = $this->input->post("old_letter");
                }

                $this->application_tracking_system_model->update_resume_applicant_job_list_old($app_id, $company_sid, $job_sid, $user_data['resume'], $job_type);
                $result = $this->application_tracking_system_model->update_applicant($app_id, $user_data);
                $this->session->set_flashdata('message', '<b>Success:</b> Attachment(s) uploaded successfully');
                redirect("applicant_profile/" . $app_id, "location");
            }
        }
    }

    public function insert_notes()
    { //check if insert notes
        if ($this->input->post()) {
            $formpost = $this->input->post(NULL, TRUE);
            $_SESSION['show_notes'] = 'true';
            $applicant_job_sid = $formpost['applicant_job_sid'];
            $job_list_sid = $formpost['job_list_sid'];
            $applicant_email = $formpost['applicant_email'];
            $action = $formpost['action'];
            $data['session'] = $this->session->userdata('logged_in');
            $company_sid = $data['session']['company_detail']['sid'];
            $employee_sid = $data['session']['employer_detail']['sid'];

            if ($action == 'add_note') {
                $notes = $formpost['notes'];
                $attachment = upload_file_to_aws('notes_attachment', $company_sid, 'notes_attachment', $applicant_job_sid);
                //                $attachment = '0003-notes_attachment-213-1x8.docx';
                $attachment_extension = NULL;

                if ($attachment != 'error') {
                    $extension = pathinfo($attachment, PATHINFO_EXTENSION);
                    $attachment_extension = $extension;
                } else {
                    $attachment = NULL;
                }

                $this->application_tracking_system_model->insertNote($company_sid, $applicant_job_sid, $applicant_email, $notes, $attachment, $attachment_extension, $employee_sid);
                $this->session->set_flashdata('message', '<b>Success:</b> Note added successfully');
                redirect('applicant_profile/' . $applicant_job_sid . '/' . $job_list_sid);
            } else {
                $note_sid = $formpost['sid'];
                $attachment = upload_file_to_aws('notes_attachment', $company_sid, 'notes_attachment', $applicant_job_sid);
                $update_array = array();
                $update_array['notes'] = $formpost['my_edit_notes'];
                $update_array['modified_date'] = date('Y-m-d H:i:s');
                $update_array['modified_sid'] = $employee_sid;

                if ($attachment != 'error') {
                    $extension = pathinfo($attachment, PATHINFO_EXTENSION);
                    $attachment_extension = $extension;
                    $update_array['attachment'] = $attachment;
                    $update_array['attachment_extension'] = $attachment_extension;
                }

                $this->application_tracking_system_model->updateRightNotes($note_sid, $update_array);
                $this->session->set_flashdata('message', '<b>Success:</b> Note updated successfully');
                redirect('applicant_profile/' . $applicant_job_sid . '/' . $job_list_sid);
            }
        } else {
            redirect('application_tracking_system/active/all/all/all/all', 'refresh');
        }
    }

    public function update_notes_from_popup()
    {
        if ($this->input->post()) {
            $formpost = $this->input->post(NULL, TRUE);
            $redirect_url = $formpost['redirect_url'];
            $perform_action = $formpost['perform_action'];
            $applicant_sid = $formpost['employers_sid'];
            $data['session'] = $this->session->userdata('logged_in');
            $employee_sid = $data['session']['employer_detail']['sid'];
            $company_sid = $data['session']['company_detail']['sid'];
            $attachment = upload_file_to_aws('notes_attachment', $company_sid, 'notes_attachment', $applicant_sid);
            //            $attachment = '0003-notes_attachment-213-1x8.docx';
            $attachment_extension = '';
            $now = date('Y-m-d H:i:s');
            $update_array = array();

            if ($attachment != 'error') {
                $extension = pathinfo($attachment, PATHINFO_EXTENSION);
                $attachment_extension = $extension;
                $update_array['attachment'] = $attachment;
                $update_array['attachment_extension'] = $attachment_extension;
            } else {
                $attachment = '';
            }

            if ($perform_action == 'update') {
                $note_sid = $formpost['sid'];
                $notes = $formpost['my_edit_notes'];
                $update_array['notes'] = $notes;
                $update_array['modified_date'] = $now;
                $update_array['modified_sid'] = $employee_sid;
                $this->application_tracking_system_model->updateRightNotes($note_sid, $update_array);
                $this->session->set_flashdata('message', '<b>Success:</b> Note updated successfully');
            } else {
                $notes = $formpost['add_notes'];
                $applicant_email = $formpost['applicant_email'];
                $this->application_tracking_system_model->insertNote($company_sid, $applicant_sid, $applicant_email, $notes, $attachment, $attachment_extension, $employee_sid);
                $this->session->set_flashdata('message', '<b>Success:</b> Note added successfully');
            }
            redirect($redirect_url, 'refresh');
        } else {
            redirect('application_tracking_system/active/all/all/all/all', 'refresh');
        }
    }

    public function insert_review_from_popup()
    {
        if ($this->input->post()) {
            $data['session'] = $this->session->userdata('logged_in');

            $company_sid = $data["session"]["company_detail"]["sid"];
            $employer_sid = $data["session"]["employer_detail"]["sid"];
            $applicant_job_sid = $this->input->post('applicant_job_sid');
            $applicant_email = $this->input->post('applicant_email');
            $redirect_url = $this->input->post('redirect_url');
            $rating = $this->input->post('rating');
            if ($this->input->post('perform_action') == 'add') {
                $comment = $this->input->post('comment');
            } else {
                $comment = $this->input->post('edit_comment');
            }
            $users_type = $this->input->post('users_type');


            $data_to_save = array();
            $data_to_save['company_sid'] = $company_sid;
            $data_to_save['employer_sid'] = $employer_sid;
            $data_to_save['applicant_job_sid'] = $applicant_job_sid;
            $data_to_save['applicant_email'] = $applicant_email;
            $data_to_save['rating'] = $rating;
            $data_to_save['comment'] = $comment;
            $data_to_save['users_type'] = $users_type;
            $attachment = upload_file_to_aws('review_attachment', $company_sid, 'review_attachment', $employer_sid);

            $data_to_save['source_type'] = $this->input->post('video_source', true);
            $data_to_save['source_value'] = $this->input->post('yt_vm_video_url');

            if ($attachment != 'error') {
                $extension = pathinfo($attachment, PATHINFO_EXTENSION);

                $data_to_save['attachment'] = $attachment;
                $data_to_save['attachment_extension'] = $extension;
            }

            $data_to_save['source_value'] = str_replace('watch?v=', 'embed/', $data_to_save['source_value']);
            $data_to_save['source_value'] = str_replace('/vimeo.com', '/player.vimeo.com/video', $data_to_save['source_value']);


            if ($data_to_save['source_type'] == 'uploaded') {
                //
                $source_value = upload_file_to_aws('upload_video', $company_sid, 'upload_video', $employer_sid);
                if ($source_value != 'error') {
                    $data_to_save['source_value'] = $source_value;
                } else unset($data_to_save['source_value']);
                //
                $source_value = upload_file_to_aws('add_upload_video', $company_sid, 'add_upload_video', $employer_sid);
                if ($source_value != 'error') {
                    $data_to_save['source_value'] = $source_value;
                } else unset($data_to_save['source_value']);
            }

            $this->application_tracking_system_model->save_rating($data_to_save);

            $this->session->set_flashdata('message', '<b>Success:</b> Rating saved successfully');
            redirect($redirect_url, 'refresh');
        } else {
            redirect('application_tracking_system/active/all/all/all/all', 'refresh');
        }
    }

    function applicant_message()
    {
        if ($this->input->post()) {
            $data['session'] = $this->session->userdata('logged_in');
            $company_name = $data['session']['company_detail']['CompanyName'];
            $formpost = $this->input->post(NULL, TRUE);

            $formpost['date'] = date('Y-m-d H:i:s');
            $formpost['from_id'] = $data['session']['employer_detail']['sid'];
            $temp_id = $formpost['template'];
            unset($formpost['template']);

            foreach ($formpost as $key => $value) {
                if ($key != 'applicant_name' && $key != 'employee_id') { // exclude these values from array
                    $message_data[$key] = $value;
                }
            }

            $attach_body = '';

            if ($temp_id != '') {
                $attachments = $this->portal_email_templates_model->get_all_email_template_attachments($temp_id);

                if (sizeof($attachments) > 0) {
                    $attach_body .= '<br> Please Review The Following Attachments: <br>';

                    foreach ($attachments as $attachment) {
                        $attach_body .= '<a href="' . AWS_S3_BUCKET_URL . $attachment['attachment_aws_file'] . '">' . $attachment['original_file_name'] . '</a><br>';
                    }
                }
            }

            $message_data['contact_name'] = $formpost['applicant_name'];
            $message_hf = (message_header_footer($data['session']['company_detail']['sid'], $company_name));
            $employerData = $this->application_tracking_system_model->user_data_by_id($data['session']['employer_detail']['sid']); //getting employer data

            if ($employerData['first_name'] != NULL || $employerData['first_name'] != '' || $employerData['last_name'] != NULL || $employerData['last_name'] != '') {
                $employer_name = $employerData['first_name'] . ' ' . $employerData['last_name'];
            } else {
                $employer_name = $employerData['username'];
            }
            //$from = $employerData['email'];
            $from = REPLY_TO;
            $message_data['identity_key'] = generateRandomString(48);
            $secret_key = $message_data['identity_key'] . "__";
            $app_emp_data = array();

            if ($formpost['users_type'] == 'applicant') {
                $applicantData = $this->application_tracking_system_model->getApplicantData($formpost['job_id']);
                $to = $applicantData['email'];
                $app_emp_data = $applicantData;
            } else if ($formpost['users_type'] == 'employee') {
                $employerDetail = $this->application_tracking_system_model->getEmployerDetail($formpost['employee_id']);
                $to = $employerDetail['email'];
                $message_data['job_id'] = "";
                $app_emp_data = $employerDetail;
            }

            $job_title = $app_emp_data['job_title'];
            $today = new DateTime();
            // Set server date/time for db
            $message_data['date'] = $today->format('Y-m-d H:i:s');
            $today = reset_datetime(array('datetime' => $today->format('Y-m-d'), '_this' => $this));
            $applicant_fname = $app_emp_data['first_name'];
            $applicant_lname = $app_emp_data['last_name'];

            $message_data['subject'] = str_replace('{{company_name}}', $company_name, $message_data['subject']);
            $message_data['subject'] = str_replace('{{date}}', $today, $message_data['subject']);
            $message_data['subject'] = str_replace('{{first_name}}', $applicant_fname, $message_data['subject']);
            $message_data['subject'] = str_replace('{{last_name}}', $applicant_lname, $message_data['subject']);
            $message_data['subject'] = str_replace('{{job_title}}', $job_title, $message_data['subject']);
            $message_data['message'] = str_replace('{{company_name}}', $company_name, $message_data['message']);
            $message_data['message'] = str_replace('{{date}}', $today, $message_data['message']);
            $message_data['message'] = str_replace('{{first_name}}', $applicant_fname, $message_data['message']);
            $message_data['message'] = str_replace('{{last_name}}', $applicant_lname, $message_data['message']);
            $message_data['message'] = str_replace('{{job_title}}', $job_title, $message_data['message']);
            $message_data['message'] .= $attach_body;
            $formpost['message'] = $message_data["message"];
            $formpost['subject'] = $message_data["subject"];

            if (isset($_FILES['message_attachment']) && $_FILES['message_attachment']['name'] != '') { //uploading profile picture
                $file = explode(".", $_FILES['message_attachment']['name']);
                $file_name = str_replace(" ", "-", $file[0]);
                $messageFile = $file_name . '-' . generateRandomString(5) . '.' . $file[1];

                if ($_FILES['message_attachment']['size'] == 0) {
                    $this->session->set_flashdata('message', '<b>Warning:</b> File is empty! Please try again.');
                    redirect($_SERVER['HTTP_REFERER']);
                }

                $aws = new AwsSdk();
                $aws->putToBucket($messageFile, $_FILES['message_attachment']['tmp_name'], AWS_S3_BUCKET_NAME);
                $message_data['attachment'] = $messageFile;
                $subject = $formpost['subject']; //'Private Message Notification';
                $body = $message_hf['header']
                    . '<h2 style="width:100%; margin:0 0 20px 0;">Dear ' . $formpost['applicant_name'] . ',</h2>'
                    . '<br>'
                    . ucfirst($employer_name) . '</b> has sent you a private message.'
                    . '<br><br><b>'
                    . 'Date:</b> '
                    . date('m-d-Y H:i:s')
                    . '<br><br><b>'
                    . 'Subject:</b> '
                    . $formpost["subject"]
                    . '<br><hr>'
                    . $formpost["message"] . '<br><br>'
                    . $message_hf['footer']
                    . '<div style="width:100%; float:left; background-color:#000; color:#000; box-sizing:border-box;">message_id:'
                    . $secret_key . '</div>';

                sendMailWithAttachment($from, $to, $subject, $body, $company_name, $_FILES['message_attachment'], REPLY_TO);
            } else {
                $subject = $formpost['subject']; //'Private Message Notification';
                $body = $message_hf['header']
                    . '<h2 style="width:100%; margin:0 0 20px 0;">Dear ' . $formpost['applicant_name'] . ',</h2>'
                    . '<br>'
                    . ucfirst($employer_name) . '</b> has sent you a private message.'
                    . '<br><br><b>'
                    . 'Date:</b> '
                    . reset_datetime(array('datetime' => date('Y-m-d H:i:s', strtotime('now')), '_this' => $this))
                    . '<br><br><b>'
                    . 'Subject:</b> '
                    . $formpost["subject"]
                    . '<br><hr>'
                    . $formpost["message"] . '<br><br>'
                    . $message_hf['footer']
                    . '<div style="width:100%; float:left; background-color:#000; color:#000; box-sizing:border-box;">message_id:'
                    . $secret_key . '</div>';

                sendMail($from, $to, $subject, $body, $company_name, REPLY_TO);
            }
            $_SESSION['show_message'] = 'true';
            $this->application_tracking_system_model->save_message($message_data);
            $this->session->set_flashdata('message', 'Success! Message sent successfully!');

            //            if ($formpost['users_type'] == 'employee') {
            //                redirect('employee_profile/' . $formpost['employee_id']);
            //            } else {
            //                redirect('applicant_profile/' . $formpost['job_id']);
            //            }
            //          header('Location: ' . $_SERVER['HTTP_REFERER']);
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            if ($formpost['users_type'] == 'employee') {
                redirect('employee_management', 'refresh');
            } else {
                redirect('application_tracking_system/active/all/all/all/all', 'refresh');
            }
        }
    }

    public function event_schedule()
    {
        if ($this->input->post()) {
            $data['session'] = $this->session->userdata('logged_in');
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $employer_email = $data['session']['employer_detail']['email'];
            $formpost = $this->input->post(NULL, TRUE);
            $redirect_to = $this->input->post('redirect_to');
            $filePath = '';

            foreach ($formpost as $key => $value) {
                if (
                    $key != 'action' &&
                    $key != 'sid' &&
                    $key != 'redirect_to'
                ) { // exclude these values from array
                    if (is_array($value)) {
                        $value = implode(',', $value);
                    }
                    $listing_data[$key] = $value;
                }
            }

            $timestamp = explode('-', $formpost['date']);
            $month = $timestamp[0];
            $day = $timestamp[1];
            $year = $timestamp[2];
            $listing_data['date'] = $year . '-' . $month . '-' . $day;
            $listing_data['companys_sid'] = $company_sid;
            $listing_data['employers_sid'] = $employer_sid;

            if (isset($_FILES['messageFile']) && $_FILES['messageFile']['name'] != '') {
                $file = explode(".", $_FILES["messageFile"]["name"]);
                $file_name = str_replace(" ", "-", $file[0]);
                $attachment = $file_name . '-' . generateRandomString(5) . '.' . $file[1];
                if ($_FILES['messageFile']['size'] == 0) {
                    $this->session->set_flashdata('message', '<b>Warning:</b> File is empty! Please try again.');
                } else {
                    $aws = new AwsSdk();
                    $aws->putToBucket($attachment, $_FILES["messageFile"]["tmp_name"], AWS_S3_BUCKET_NAME);
                    $listing_data['messageFile'] = $attachment;
                }
            }

            $_SESSION['show_event'] = 'true';

            if ($_POST['action'] == 'edit_event') {
                $this->application_tracking_system_model->update_Event($formpost['sid'], $listing_data);
                //Generate .ics file
                $destination = APPPATH . '../assets/ics_files/';
                $filePath = generate_ics_file_for_event($destination, $formpost['sid'], true);
                $this->session->set_flashdata('message', '<b>Success:</b> Event updated successfully');
            } else {
                $this->application_tracking_system_model->saveEvent($listing_data);
                $event_sid = $this->db->insert_id();
                //Generate .ics file
                $destination = APPPATH . '../assets/ics_files/';
                $filePath = generate_ics_file_for_event($destination, $event_sid);
                $this->session->set_flashdata('message', '<b>Success:</b> Event scheduled successfully');
            }

            $i = 0;
            $interviewersList = '';
            $whoisattending = '';

            if (isset($formpost['interviewer'])) {
                if ($listing_data['users_type'] == 'employee') {
                    $messageFrom = '<br><br><b>Message from the Employer:</b>';
                    $interviewersList .= '<br><b>Your Meeting is scheduled with:<br></b> ';
                } else {
                    $messageFrom = '<br><br><b>Message from the Interviewer:</b>';
                    $interviewersList .= '<br><b>Your Interview is scheduled with:<br></b> ';
                }

                foreach ($formpost['interviewer'] as $user_sid) {
                    $userData[$i] = $this->application_tracking_system_model->user_data_by_id($user_sid);
                    $interviewersList .= $userData[$i]['first_name'] . ' ' . $userData[$i]['last_name'] . '<br>';
                    $whoisattending .= $userData[$i]['first_name'] . ' ' . $userData[$i]['last_name'] . '<br>';
                    $i++;
                }
            }
            //sending email to applicant about event - hassan
            //$from = $employer_email;
            $from = FROM_EMAIL_EVENTS;
            $from_name = $data["session"]["company_detail"]["CompanyName"];
            $to = $formpost['applicant_email'];
            $subject = ucfirst($formpost['category']) . " schedule notification";
            $message_hf = (message_header_footer($data["session"]["company_detail"]["sid"], $data["session"]["company_detail"]["CompanyName"]));

            if ($formpost['users_type'] == 'applicant') {
                $applicantData = $this->application_tracking_system_model->getApplicantData($formpost['applicant_job_sid']);
            } else if ($formpost['users_type'] == 'employee') {
                $applicantData = $this->application_tracking_system_model->getEmployerDetail($formpost['applicant_job_sid']);
            }

            $portalData = $this->application_tracking_system_model->get_portal_detail($data["session"]["company_detail"]["sid"]);

            if ($formpost['address'] != "") {
                $address = "https://maps.googleapis.com/maps/api/staticmap?center=" . urlencode($formpost['address']) . "&zoom=13&size=400x400&key=" . GOOGLE_MAP_API_KEY . "&markers=color:blue|label:|" . urlencode($formpost['address']);
                $address = '<br><br>'
                    . '<b>Meeting Location:<b><br> ' . $formpost['address'] . '<br> <a href = "https://maps.google.com/maps?z=12&t=m&q=' . urlencode($formpost['address']) . '"> <img src = "' . $address . '" alt = "No Map Found!" > </a>';
            } else {
                $address = "";
            }
            //check if goToMeeting is Checked Start
            if (isset($formpost['goToMeetingCheck']) && $formpost['goToMeetingCheck'] == 1) {
                $goToMeeting = '<br><br><b>Meeting Call in Details</b>'
                    . '<br><b>Meeting Id Number:</b> '
                    . $formpost['meetingId']
                    . '<br><b>Meeting Call In Number:</b> '
                    . $formpost['meetingCallNumber']
                    . '<br><br>'
                    . $formpost['meetingURL']
                    . '<br>';
            } else {
                $goToMeeting = "<br>";
            }
            //check if goToMeeting is Checked Ends

            if ($_POST['action'] == 'edit_event') {
                $edit_message = '<h3>Your event details have changed. Please view the changes below and update this event on your calendar</h3>';
            } else {
                $edit_message = '';
            }

            if (isset($formpost['messageCheck']) && $formpost['messageCheck'] == 1 && $_FILES['messageFile']['name'] == '') {
                $body = $message_hf['header']
                    . '<h2 style="width:100%; margin:0 0 20px 0;">Dear ' . $applicantData['first_name'] . ' ' . $applicantData['last_name'] . ',</h2>'
                    . $edit_message
                    . '<br>A ' . ucfirst($formpost['category']) . ' has been scheduled for you with: ' . $data["session"]["company_detail"]["CompanyName"]
                    . '<br><br><b>Following are the Event Details:</b>'
                    . '<br>'
                    . ucfirst($formpost['category'])
                    . ' with '
                    . $data["session"]["company_detail"]["CompanyName"]
                    . '<br><b>Date:</b> '
                    . $formpost['date']
                    . '<br><b>Time:</b> From '
                    . $formpost['eventstarttime']
                    . ' To '
                    . $formpost['eventendtime']
                    . $goToMeeting
                    . $interviewersList
                    . $messageFrom
                    . '<br><b>Subject:</b> '
                    . $formpost['subject']
                    . '<br><b>Message:</b> '
                    . $formpost['message']
                    . $address
                    . '<br><br>'
                    . $message_hf['footer'];
                //sendMail($from, $to, $subject, $body, $portalData['sub_domain']);
                sendMailWithAttachmentRealPath($from, $to, $subject, $body, $from_name, $filePath);
                //sendMailWithAttachmentRealPath($from, $to, $subject, '&nbsp;', $portalData['sub_domain'], $filePath);
            } elseif (isset($formpost['messageCheck']) && $formpost['messageCheck'] == 1 && isset($_FILES['messageFile']) && $_FILES['messageFile']['name'] != '') {
                $body = $message_hf['header'];
                $body .= '<h2 style="width:100%; margin:0 0 20px 0;">Dear ' . $applicantData['first_name'] . ' ' . $applicantData['last_name'] . ',</h2>';
                $body .= $edit_message;
                $body .= '<br>A ' . ucfirst($formpost['category']) . ' has been scheduled for you with: ' . $data["session"]["company_detail"]["CompanyName"];
                $body .= '<br><br><b>Following are the Event Details:</b>';
                $body .= '<br>';
                $body .= ucfirst($formpost['category']);
                $body .= ' with ';
                $body .= $data["session"]["company_detail"]["CompanyName"];
                $body .= '<br><b>Date:</b> ';
                $body .= $formpost['date'];
                $body .= '<br><b>Time:</b> From ' . $formpost['eventstarttime'] . ' To ' . $formpost['eventendtime'];
                $body .= $goToMeeting;
                $body .= $interviewersList;
                $body .= $messageFrom;
                $body .= '<br><b>Subject:</b>';
                $body .= $formpost['subject'];
                $body .= '<br><b>Message:</b> ';
                $body .= $formpost['message'];
                $body .= '<br><b>Attachment:</b>' . '<a style="background-color: #00a700; font-size:14px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:25px; padding: 0 15px; margin-lefy:10px; color: #fff; border-radius: 5px; text-align: center; display:inline-block;" href="' . AWS_S3_BUCKET_URL . $attachment . '" target="_blank">Download</a>';
                $body .= $address;
                $body .= '<br><br>';
                $body .= $message_hf['footer'];

                //sending mail with Attachment
                //sendMailWithAttachment($from, $to, $subject, $body, $portalData['sub_domain'], $_FILES['messageFile']);
                sendMailWithAttachmentRealPath($from, $to, $subject, $body, $from_name, $filePath);
                //sendMailWithAttachmentRealPath($from, $to, $subject, '&nbsp;', $portalData['sub_domain'], $filePath);
            } else {
                $body = $message_hf['header']
                    . '<h2 style = "width:100%; margin:0 0 20px 0;">Dear ' . $applicantData['first_name'] . ' ' . $applicantData['last_name'] . ', </h2>'
                    . $edit_message
                    . '<br>A ' . ucfirst($formpost['category']) . ' has been scheduled for you with: ' . $data["session"]["company_detail"]["CompanyName"]
                    . '<br><br><b>Following are the Event Details:</b>'
                    . '<br>'
                    . ucfirst($formpost['category'])
                    . ' with '
                    . $data["session"]["company_detail"]["CompanyName"]
                    . '<br><b>Date:</b> '
                    . $formpost['date']
                    . '<br><b>Time:</b> From '
                    . $formpost['eventstarttime']
                    . ' To '
                    . $formpost['eventendtime']
                    . $goToMeeting
                    . $interviewersList
                    . $address
                    . '<br><br>'
                    . $message_hf['footer'];

                //sendMail($from, $to, $subject, $body, $portalData['sub_domain']);
                sendMailWithAttachmentRealPath($from, $to, $subject, $body, $from_name, $filePath);
                //sendMailWithAttachmentRealPath($from, $to, $subject, '&nbsp;', $portalData['sub_domain'], $filePath);
            }
            //saving emial to email log
            $emailData = array(
                'date' => date('Y-m-d H:i:s'),
                'subject' => $subject,
                'email' => $to,
                'message' => $body,
                'username' => $data['session']['company_detail']['CompanyName']
            );

            $this->application_tracking_system_model->save_email_logs($emailData);

            foreach ($userData as $user) { //sending comment email and notification email to interviewer
                //$from = $employer_email;
                $from = FROM_EMAIL_EVENTS;
                $from_name = $data['session']['company_detail']['CompanyName'];
                $to = $user['email'];
                $subject = ucfirst($formpost['category']) . " notification";
                $username = $user['first_name'];

                if ($user['first_name'] == NULL) {
                    $username = $user['username'];
                }

                if (isset($formpost['messageCheck']) && $formpost['commentCheck'] == 1) {
                    $body = $message_hf['header']
                        . '<h2 style = "width:100%; margin:0 0 20px 0;">Dear ' . $username . ', </h2>'
                        . $edit_message
                        . '<br>A ' . ucfirst($formpost['category']) . ' has been scheduled for you with: '
                        . ucfirst($applicantData['first_name']) . ' ' . ucfirst($applicantData['last_name'])
                        . '<br><br><b>Following are the Event Details:</b>'
                        . '<br>'
                        . ucfirst($formpost['category'])
                        . ' with '
                        . ucfirst($applicantData['first_name']) . ' ' . ucfirst($applicantData['last_name'])
                        . '<br><b>Date:</b> '
                        . $formpost['date']
                        . '<br><b>Time:</b> From '
                        . $formpost['eventstarttime']
                        . ' To '
                        . $formpost['eventendtime']
                        . $goToMeeting
                        . "<br><b>Who's Attending:</b><br>"
                        . $whoisattending
                        . '<br><br><b>Comment:</b> '
                        . $formpost['comment']
                        . $address
                        . '<br><br>'
                        . $message_hf['footer'];
                } else {
                    $body = $message_hf['header']
                        . '<h2 style="width:100%; margin:0 0 20px 0;">Dear ' . $username . ',</h2>'
                        . $edit_message
                        . '<br>A ' . ucfirst($formpost['category']) . ' has been scheduled for you with: '
                        . ucfirst($applicantData['first_name']) . ' ' . ucfirst($applicantData['last_name'])
                        . '<br><br><b>Following are the Event Details:</b>'
                        . '<br>'
                        . ucfirst($formpost['category'])
                        . ' with '
                        . ucfirst($applicantData['first_name']) . ' ' . ucfirst($applicantData['last_name'])
                        . '<br><b>Date:</b> '
                        . $formpost['date']
                        . '<br><b>Time:</b> From '
                        . $formpost['eventstarttime']
                        . ' To '
                        . $formpost['eventendtime']
                        . $goToMeeting
                        . "<br><b>Who's Attending:</b><br>"
                        . $whoisattending
                        . $address
                        . '<br><br>'
                        . $message_hf['footer'];
                }
                //sendMail($from, $to, $subject, $body, $portalData['sub_domain']);
                sendMailWithAttachmentRealPath($from, $to, $subject, $body, $from_name, $filePath);
                //sendMailWithAttachmentRealPath($from, $to, $subject, '&nbsp;', $portalData['sub_domain'], $filePath);
                //saving emial to email log
                $emailData = array(
                    'date' => date('Y-m-d H:i:s'),
                    'subject' => $subject,
                    'email' => $to,
                    'message' => $body,
                    'username' => $data["session"]["company_detail"]['CompanyName']
                );

                $this->application_tracking_system_model->save_email_logs($emailData);
            }

            switch ($redirect_to) {
                case 'applicant_profile':
                    redirect('applicant_profile/' . $formpost['applicant_job_sid'], 'refresh');
                    break;
                case 'employee_profile':
                    redirect('employee_profile/' . $formpost['applicant_job_sid'], 'refresh');
                    break;
                case 'my_profile':
                default:
                    redirect('my_profile', 'refresh');
                    break;
            }
        } else {
            redirect('dashboard', 'refresh');
        }
    }

    public function deleteEvent()
    {
        if (isset($_GET['action']) && $_GET['action'] == 'remove_event') {
            $sid = $_GET['sid'];
            $this->application_tracking_system_model->deleteEvent($sid);
            echo 'Done';
            exit;
        }
    }

    public function send_reference_request_email()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'application_tracking_system/active/all/all/all/all', 'send_reference_request_email');
            $userName = $data['session']['employer_detail']['username'];

            if (isset($_POST['perform_action'])) { //Send Add References Request Email
                if ($_POST['perform_action'] == 'send_add_reference_request_email') {
                    $applicant_info = $this->application_tracking_system_model->getApplicantData($_POST['applicant_sid']);

                    if (!empty($applicant_info)) {
                        $VerificationKey = '';

                        if ($applicant_info['verification_key'] == null) {
                            $VerificationKey = 'app' . generateRandomString(24);
                            $this->application_tracking_system_model->updateVerificationKey($applicant_info['sid'], $VerificationKey);
                        } else {
                            $VerificationKey = $applicant_info['verification_key'];
                        }

                        $url = base_url('reference_checks_public') . '/' . $VerificationKey;
                        $data2 = $this->session->userdata('logged_in');
                        $company_name = $data2['company_detail']['CompanyName'];
                        $company_sid = $data2['company_detail']['sid'];
                        $message_hf = message_header_footer($company_sid, $company_name);
                        $reference_request_letter_check = $this->application_tracking_system_model->check_whether_table_exists('reference_request_letter', $company_sid);

                        if (empty($reference_request_letter_check)) {
                            $subject = 'Please Provide References';
                            $body = '';
                            $body .= $message_hf['header'];
                            $body .= '<p>Dear ' . ucwords($applicant_info['first_name'] . ' ' . $applicant_info['last_name']) . ',</p>';
                            $body .= '<p>Your Job Application at "' . ucwords($company_name) . '" has been well received.</p>';
                            $body .= '<p>Kindly click on the following link and provide "References" and their contact details for confirmation:</p>';
                            $body .= '<p></p>';
                            $body .= '<p><a target="_blank" href="' . $url . '">Applicant References</a></p>';
                            $body .= '<p></p>';
                            $body .= '<p>Please contact the references you are using and let them know that they will be receiving an email reference questionnaire and possibly a call from one of our company managers. Inform your reference of the urgency of completing the questionnaire.</p>';
                            $body .= '<p>Thank You!</p>';
                            $body .= '<p>---------------------------------------------------------</p>';
                            $body .= '<p>Automated Email; Please Do Not reply!</p>';
                            $body .= '<p>---------------------------------------------------------</p>';
                            $body .= $message_hf['footer'];
                        } else {
                            $email_template = $reference_request_letter_check[0];
                            $subject = $email_template['subject'];
                            $body = $message_hf['header'];
                            $body .= $email_template['message_body'];
                            $body .= $message_hf['footer'];
                            $body = str_replace('{{applicant_name}}', $applicant_info['first_name'] . ' ' . $applicant_info['last_name'], $body);
                            $body = str_replace('{{job_title}}', ucwords($company_name), $body);
                            $body = str_replace('{{reference_link}}', '<a target="_blank" href="' . $url . '">Applicant References</a>', $body);
                        }

                        $reload_location = base_url('applicant_profile') . '/' . $applicant_info['sid'];
                        $applicantEmail = $applicant_info['email'];

                        if (base_url() == STAGING_SERVER_URL) {
                            $emailData = array(
                                'date' => date('Y-m-d H:i:s'),
                                'subject' => $subject,
                                'email' => $applicantEmail,
                                'message' => $body,
                                'username' => $userName
                            );
                            $this->application_tracking_system_model->save_email_logs($emailData);
                            $this->session->set_flashdata('message', '<b>Notification: </b>Email has been successfully Sent!');
                            redirect($reload_location, 'refresh');
                        } else {
                            $emailData = array(
                                'date' => date('Y-m-d H:i:s'),
                                'subject' => $subject,
                                'email' => $applicantEmail,
                                'message' => $body,
                                'username' => $userName
                            );
                            $this->application_tracking_system_model->save_email_logs($emailData);
                            sendMail(FROM_EMAIL_NOTIFICATIONS, $applicantEmail, $subject, $body, $company_name, NULL);
                            $this->session->set_flashdata('message', '<b>Notification: </b>Email has been successfully Sent!');
                        }
                        redirect($reload_location, 'refresh');
                    }
                }
            }
        } else {
            redirect('login', "refresh");
        }
    }

    public function deleteMessage()
    {
        if (isset($_GET['action']) && $_GET['action'] == 'delete_message') {
            $sid = $_GET['sid'];
            $this->application_tracking_system_model->deleteMessage($sid);
            exit;
        } else {
            redirect('application_tracking_system/active/all/all/all/all', 'refresh');
        }
    }

    public function resend_message()
    {
        if ($this->input->post()) {
            $data['session'] = $this->session->userdata('logged_in');
            $id = $this->input->post('id');
            $messageData = $this->application_tracking_system_model->getMessageDetail($id);
            $to = $messageData['to_id'];
            $subject = $messageData['subject'];
            $body = $messageData['message'];
            $employerData = $this->application_tracking_system_model->user_data_by_id($data["session"]["employer_detail"]["sid"]); //getting employer data
            $from = $employerData['email'];

            if (isset($messageData['attachment']) && $messageData['attachment'] != NULL) {
                $aws = new AwsSdk();
                $file = $aws->getFromBucket($messageData['attachment'], AWS_S3_BUCKET_NAME);
                $result = sendMailWithAttachment($from, $to, $subject, $body, $data["session"]["company_detail"]["CompanyName"], $file, REPLY_TO);
            } else {
                $result = sendMail($from, $to, $subject, $body, $data["session"]["company_detail"]["CompanyName"], REPLY_TO);
            }
        } else {
            redirect('application_tracking_system/active/all/all/all/all', 'refresh');
        }
    }

    function delete_note()
    {
        $id = $this->input->post('sid');
        $this->application_tracking_system_model->delete_note($id);
        $_SESSION['show_notes'] = 'true';
        $this->session->set_flashdata('message', '<b>Success:</b> Note deleted successfully');
    }

    public function ajax_responder()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];

            if ($_POST) {
                $perform_action = $_POST['perform_action'];

                switch ($perform_action) {
                    case 'set_applicant_for_approval':
                        $applicant_sid = $_POST['applicant_id'];
                        $job_sid = $_POST['job_sid'];
                        $this->application_tracking_system_model->set_applicant_approval_status($company_sid, $applicant_sid, 'pending', $employer_sid, '', 'first_request', '', $job_sid);
                        $this->application_tracking_system_model->insert_applicant_approval_history_record($company_sid, $employer_sid, $applicant_sid, 'pending', 'first_request', date('Y-m-d h:i:s'), '');
                        $emailTemplateData = get_email_template(NEW_APPLICANT_HIRING_APPROVAL_REQUEST);
                        $employer_name = $data["session"]["employer_detail"]["first_name"] . ' ' . $data["session"]["employer_detail"]["last_name"];
                        //Send Emails to Users With Approval Rights
                        $users_with_approval_rights = $this->application_tracking_system_model->get_all_users_with_approval_rights($company_sid);
                        $applicant_info = $this->application_tracking_system_model->get_single_applicant($applicant_sid, $job_sid);

                        if (!empty($applicant_info)) {
                            if (!empty($users_with_approval_rights)) {
                                foreach ($users_with_approval_rights as $user) {
                                    $user_email_address = $user['email'];
                                    $replacement_array = array();
                                    $replacement_array['job_title'] = ucwords($applicant_info['job_title']);
                                    $replacement_array['applicant_name'] = ucwords($applicant_info['first_name'] . ' ' . $applicant_info['last_name']);
                                    $replacement_array['employer_name'] = ucwords($employer_name);
                                    $replacement_array['approving_authority'] = ucwords($user['first_name'] . ' ' . $user['last_name']);
                                    log_and_send_templated_email(NEW_APPLICANT_HIRING_APPROVAL_REQUEST, $user_email_address, $replacement_array);
                                }
                            }
                            echo 'success';
                        } else {
                            echo 'error';
                        }
                        break;
                    case 'get_applicant_rejection_form':
                        $applicant_sid = $this->input->post('applicant_sid');
                        $company_sid = $this->input->post('company_sid');
                        $job_sid = $this->input->post('job_sid');
                        $applicant_job = $this->application_tracking_system_model->get_portal_applicant_jobs_list_record($applicant_sid, $company_sid, $job_sid);
                        $view_data = array();
                        $view_data['applicant_job'] = $applicant_job;
                        $view_html = $this->load->view('applicant_approval_management/applicant_rejection_form_partial', $view_data, true);
                        $return_data = array();
                        $return_data['view'] = $view_html;
                        $return_data['status'] = 'success';
                        $this->output->set_content_type('application/json');
                        echo json_encode($return_data);
                        break;
                    case 'get_applicant_approval_response_form':
                        $applicant_sid = $this->input->post('applicant_sid');
                        $company_sid = $this->input->post('company_sid');
                        $job_sid = $this->input->post('job_sid');
                        $applicant_job = $this->application_tracking_system_model->get_portal_applicant_jobs_list_record($applicant_sid, $company_sid, $job_sid);
                        $view_data = array();
                        $view_data['applicant_job'] = $applicant_job;
                        $view_html = $this->load->view('applicant_approval_management/applicant_rejection_form_partial', $view_data, true);
                        $return_data = array();
                        $return_data['view'] = $view_html;
                        $return_data['status'] = 'success';
                        $this->output->set_content_type('application/json');
                        echo json_encode($return_data);
                        break;
                    case 'get_hire_applicant_form':
                        $applicant_sid = $this->input->post('applicant_sid');
                        $company_sid = $this->input->post('company_sid');
                        $job_sid = $this->input->post('job_sid');
                        $email = $this->input->post('email');
                        $view_data = array();
                        $view_data['company_sid'] = $company_sid;
                        $view_data['applicant_sid'] = $applicant_sid;
                        $view_data['job_sid'] = $job_sid;
                        $view_data['email'] = $email;
                        $view_html = $this->load->view('manage_employer/application_tracking_system/hire_applicant_form_partial', $view_data, true);
                        $return_data = array();
                        $return_data['view'] = $view_html;
                        $return_data['status'] = 'success';
                        //print_r($return_data);
                        $this->output->set_content_type('application/json');
                        echo json_encode($return_data);
                        break;
                    case 'fetch_applicant_notes':
                        $applicant_sid = $this->input->post('applicant_sid');
                        $job_sid = $this->input->post('job_sid');
                        $applicant_notes = $this->application_tracking_system_model->getApplicantNotes($applicant_sid);
                        // Added on: 26-06-2019
                        if (sizeof($applicant_notes)) foreach ($applicant_notes as $k0 => $v0) $applicant_notes[$k0]['insert_date'] = reset_datetime(array('datetime' => $v0['insert_date'], '_this' => $this, 'from_format' => 'b d Y H:i a', 'format' => 'default'));
                        print_r(json_encode($applicant_notes));
                        break;
                    case 'fetch_applicant_reviews':
                        $applicant_sid = $this->input->post('applicant_sid');
                        $applicant_reviews = $this->application_tracking_system_model->getApplicantAllRating($applicant_sid, 'applicant');

                        if (!empty($applicant_reviews)) {
                            $applicant_reviews = $applicant_reviews->result_array();
                        } else {
                            $applicant_reviews = array();
                        }

                        print_r(json_encode($applicant_reviews));
                        break;
                    case 'fetch_applicant_questionnaire':
                        $applicant_sid = $this->input->post('applicant_sid');
                        $applicant_jobs = $this->application_tracking_system_model->get_single_applicant_all_jobs($applicant_sid, $company_sid);

                        $questionnaire_final = '';
                        $que_head = '<div id="screening_questionnaire_tabpage" class="tab-pane fade in active hr-innerpadding"><div class="row"><div class="col-xs-12">';
                        $questionnaire_final = $questionnaire_final . $que_head;
                        if (sizeof($applicant_jobs) > 0) {
                            $item = 0;
                            $counter = 0;
                            $ques_body = '<div class="tab-header-sec"><h2 class="tab-title">Screening Questionnaire</h2></div>';
                            $questionnaire_final = $questionnaire_final . $ques_body;
                            $question_data = '';

                            foreach ($applicant_jobs as $job) {
                                if ($job['job_title'] != NULL && $job['job_title'] != '') {
                                    $ques_title = '<p class="questionnaire-heading margin-top">Job: ' . $job['job_title'] . '</p>';
                                    $question_data = $question_data . $ques_title;
                                }
                                if ($job['questionnaire'] != NULL && $job['questionnaire'] != '') {
                                    $my_questionnaire = unserialize($job['questionnaire']);

                                    if (isset($my_questionnaire['applicant_sid'])) {
                                        $questionnaire_type = 'new';
                                        $questionnaire_name = $my_questionnaire['questionnaire_name'];
                                        $questionnaire = $my_questionnaire['questionnaire'];
                                        $q_b = '';
                                        foreach ($questionnaire as $key => $questionnaire_answers) {
                                            $answer = $questionnaire_answers['answer'];
                                            $passing_score = $questionnaire_answers['passing_score'];
                                            $score = $questionnaire_answers['score'];
                                            $status = $questionnaire_answers['status'];
                                            $item++;

                                            $attendDate = formatDateToDB($my_questionnaire['attend_timestamp'], DB_DATE_WITH_TIME, DATE_WITH_TIME);

                                            $ques_start = '<div class="panel panel-default"><div class="panel-heading"><h4 class="panel-title"><span style="float: right; text-align: right; font-size: 12px; margin-top: -5px">Completed At<br/> ' . $attendDate . '</span><a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_' . $item . '"><span class="glyphicon glyphicon-minus"></span>  ' . $key . '</a></h4></div><div id="collapse_' . $item . '" class="panel-collapse collapse in">';
                                            if (is_array($answer)) {
                                                foreach ($answer as $multiple_answer) {
                                                    $ques_ans = '<div class="panel-body">' . $multiple_answer . '</div>';
                                                }
                                            } else {
                                                $ques_ans = '<div class="panel-body">' . $answer . '</div>';
                                            }
                                            $ques_score = '<div class="panel-body"><b>Score: ' . $score . ' points out of possible ' . $passing_score . '</b><span class="' . strtolower($status) . ' pull-right" style="font-size: 22px;">(' . $status . ')</span> </div> </div> </div>';
                                            $q_b = $q_b . $ques_start . $ques_ans . $ques_score;
                                        }
                                        $anchor = $job['questionnaire_result'] == 'Fail' ? '<a style="background-color:#FF0000;" href="javascript:;">' : '<a href="javascript:;">';

                                        $ques_name = '<p class="questionnaire-heading margin-top" style="background-color: #466b1d;">' . $questionnaire_name . '</p> <div class="tab-btn-panel"><span>Score: ' . $job['score'] . '</span> ' . $anchor . $job['questionnaire_result'] . '</a></div><div class="questionnaire-body">' . $q_b . '</div>';
                                        $question_data = $question_data . $ques_name;
                                    } else {
                                        $questionnaire_type = 'old';
                                        $btn_panel = '<div class="tab-btn-panel"><span>Score :' . $job['score'] . '</span>' . $job['passing_score'] <= $job['score'] ? '<a href="javascript:;">Pass</a>' : '<a href="javascript:;">Fail</a>' . '</div>';

                                        $questionnaire = unserialize($job['questionnaire']);
                                        $items = '';
                                        foreach ($questionnaire as $key => $value) {
                                            $item++;
                                            if (is_array($value)) {
                                                foreach ($value as $answer) {
                                                    $ans = '<div class="panel-body">' . $answer . '</div>';
                                                }
                                            } else {
                                                $ans = '<div class="panel-body">' . $value . '</div>';
                                            }
                                            $items = $items . '<div class="panel panel-default"><div class="panel-heading"><h4 class="panel-title"><a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_' . $item . '"><span class="glyphicon glyphicon-minus"></span>  ' . $key . '</a></h4></div><div id="collapse_' . $item . '" class="panel-collapse collapse in">' . $ans . '</div></div>';
                                            $counter++;
                                        }
                                        $ques_ans = '<p class="questionnaire-heading margin-top" style="background-color: #466b1d;">Questions / Answers</p><div class="panel-group-wrp"><div class="panel-group" id="accordion">' . $items . '</div></div>';
                                        $final_else = $btn_panel . $ques_ans;
                                        $question_data = $question_data . $final_else;
                                    }
                                } else {
                                    $no_quest = '<div class="applicant-notes-empty"><div class="notes-not-found">No questionnaire found!</div></div>';
                                    $question_data = $question_data . $no_quest;
                                }
                                $job_manual_questionnaire_history = $job['manual_questionnaire_history'];

                                if (!empty($job_manual_questionnaire_history)) {
                                    $job_manual_questionnaire_history_count = count($job_manual_questionnaire_history);
                                    $manual_question_data = '';
                                    foreach ($job_manual_questionnaire_history as $job_man_key => $job_man_value) {
                                        $job_manual_questionnaire       = $job_man_value['questionnaire'];
                                        $job_questionnaire_sent_date    = $job_man_value['questionnaire_sent_date'];
                                        $job_man_questionnaire_result   = $job_man_value['questionnaire_result'];
                                        $job_man_score                  = $job_man_value['score'];
                                        $job_man_passing_score          = $job_man_value['passing_score'];

                                        $resent = '<br>Resent on: ' . date_with_time($job_questionnaire_sent_date) . '<hr style="margin-top: 5px; margin-bottom: 5px;">';
                                        $manual_question_data = $manual_question_data . $resent;
                                        if ($job_manual_questionnaire != '' || $job_manual_questionnaire != NULL) {
                                            $job_manual_questionnaire_array = unserialize($job_manual_questionnaire);

                                            if (empty($job_manual_questionnaire_array)) {
                                                $no_ques = '<div class="applicant-notes-empty"><div class="notes-not-found">No questionnaire found!</div></div>';
                                                $manual_question_data = $manual_question_data . $no_ques;
                                            } else {
                                                /************************************************************/
                                                $man_ques = '';
                                                if (isset($job_manual_questionnaire_array['applicant_sid'])) {
                                                    $questionnaire_name = $job_manual_questionnaire_array['questionnaire_name'];

                                                    $questionnaire = $job_manual_questionnaire_array['questionnaire'];
                                                    foreach ($questionnaire as $key => $questionnaire_answers) {
                                                        $answer = $questionnaire_answers['answer'];
                                                        $passing_score = $questionnaire_answers['passing_score'];
                                                        $score = $questionnaire_answers['score'];
                                                        $status = $questionnaire_answers['status'];
                                                        $item++;

                                                        if (is_array($answer)) {
                                                            foreach ($answer as $multiple_answer) {
                                                                $man_ans = '<div class="panel-body">' . $multiple_answer . '</div>';
                                                            }
                                                        } else {
                                                            $man_ans = '<div class="panel-body">' . $answer . '</div>';
                                                        }
                                                        $attendDate = formatDateToDB($job_manual_questionnaire_array['attend_timestamp'], DB_DATE_WITH_TIME, DATE_WITH_TIME);
                                                        $man_ques = $man_ques . '<div class="panel panel-default">
                                                                            <div class="panel-heading">
                                                                                <h4 class="panel-title">
                                                                                <span style="float: right; text-align: right; font-size: 12px; margin-top: -5px">Completed At<br/> ' . $attendDate . '</span>
                                                                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_' . $item . '"><span class="glyphicon glyphicon-plus"></span>  ' . $key . '</a>
                                                                                </h4>
                                                                            </div>
                                                                            <div id="collapse_' . $item . '" class="panel-collapse collapse">' . $man_ans . '<div class="panel-body">
                                                                                    <b>Score: ' . $score . ' points out of possible' . $passing_score . '</b>
                                                                                    <span class="' . strtolower($status) . ' pull-right" style="font-size: 22px;">(' . $status . ')</span>
                                                                                </div>
                                                                            </div>
                                                                        </div>';
                                                    }
                                                    $anchor = $job_man_questionnaire_result == 'Fail' ? '<a style="background-color:#FF0000;" href="javascript:;">' : '<a href="javascript:;">';
                                                    $manual_ques = '<p class="questionnaire-heading margin-top" style="background-color: #466b1d;">' . $questionnaire_name . '</p><div class="tab-btn-panel"><span>Score: ' . $job_man_score . '</span>' . $anchor . $job_man_questionnaire_result . '</a></div><div class="questionnaire-body"> ' . $man_ques . ' </div>';
                                                    $manual_question_data = $manual_question_data . $manual_ques;
                                                } else {
                                                    $questionnaire = $job_manual_questionnaire_array;
                                                    $man_panel = '';
                                                    foreach ($questionnaire as $key => $value) {
                                                        $item++;
                                                        if (is_array($value)) {
                                                            foreach ($value as $answer) {
                                                                $ans = '<div class="panel-body">' . $answer . '</div>';
                                                            }
                                                        } else {
                                                            $ans = '<div class="panel-body">' . $value . '</div>';
                                                        }
                                                        $man_panel = $man_panel . '<div class="panel panel-default">
                                                                        <div class="panel-heading">
                                                                            <h4 class="panel-title">
                                                                                <a class="accordion-toggle" data-toggle="collapse"
                                                                                   data-parent="#accordion"
                                                                                   href="#collapse_' . $item . '">
                                                                                    <span class="glyphicon glyphicon-plus"></span>  ' . $key . '
                                                                                </a>
                                                                            </h4>
                                                                        </div>
                                                                        <div id="collapse_ $item"
                                                                             class="panel-collapse collapse">' . $ans . '
                                                                        </div>
                                                                    </div>';

                                                        $counter++;
                                                    }
                                                    $man_score = '<div class="tab-btn-panel">
                                                                <span>Score : ' . $job_man_score . '</span>' . $job_man_passing_score <= $job_man_score ? '<a href="javascript:;">Pass</a>' : '<a href="javascript:;">Fail</a>' . '</div>
                                                        <p class="questionnaire-heading margin-top" style="background-color: #466b1d;">Questions / Answers</p>
                                                        <div class="panel-group-wrp">
                                                            <div class="panel-group" id="accordion">' . $man_panel . '</div></div>';

                                                    $manual_question_data = $manual_question_data . $man_score;
                                                }
                                            }
                                        } else {
                                            $no_ques_found = '<div class="applicant-notes-empty">
                                                                                        <div class="notes-not-found">No questionnaire found!</div>
                                                                                    </div>';
                                            $manual_question_data = $manual_question_data . $no_ques_found;
                                        }
                                    }
                                    $question_data = $question_data . $manual_question_data;
                                }
                            }
                            $questionnaire_final = $questionnaire_final . $question_data;
                        } else {
                            $no_ques_found = '<div class="tab-header-sec">
                                    <h2 class="tab-title">Screening Questionnaire</h2>
                                    <div class="applicant-notes-empty">
                                        <div class="notes-not-found">No questionnaire found!</div>
                                    </div>
                               </div>';
                            $questionnaire_final = $questionnaire_final . $no_ques_found;
                        }
                        $end_div = '</div></div></div>';
                        $questionnaire_final = $questionnaire_final . $end_div;

                        echo $questionnaire_final;

                        //                        print_r(json_encode($applicant_job));
                        break;
                    case 'arch_bulk_applicants':
                        if ($this->input->is_ajax_request()) {
                            $applicant_sids = $this->input->post('arch_id');
                            foreach ($applicant_sids as $app_sid) {
                                $this->application_tracking_system_model->arch_single_applicant($app_sid);
                            }
                            $this->session->set_flashdata('message', '<b>Success:</b> Candidate(s) archived successfully');
                            echo 'success';
                        } else {
                            echo 'error';
                        }
                        break;
                    case 'active_bulk_applicants':
                        if ($this->input->is_ajax_request()) {
                            $applicant_sids = $this->input->post('active_id');
                            foreach ($applicant_sids as $app_sid) {
                                $this->application_tracking_system_model->active_single_applicant($app_sid);
                            }
                            $this->session->set_flashdata('message', '<b>Success:</b> Candidate(s) activated successfully');
                            echo 'success';
                        } else {
                            echo 'error';
                        }
                        break;
                }
            }
        }
    }

    public function send_kpa_onboarding()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'application_tracking_system/active/all/all/all/all', 'send_kpa_onboarding');
            $company_id = $data["session"]["company_detail"]["sid"];
            $company_name = $data["session"]["company_detail"]["CompanyName"];
            $employer_sid = $data['session']['employer_detail']['sid'];

            if (isset($_POST['kpa_action'])) { //Send KPA onboarding Request Email
                if ($_POST['kpa_action'] == 'send_kpa_onboarding_email') {
                    $applicant_info = $this->application_tracking_system_model->getApplicantData($_POST['applicant_sid']);

                    if (!empty($applicant_info)) {
                        $kpaDetials = $this->application_tracking_system_model->get_kpa_onboarding($company_id);
                        $message_hf = (message_header_footer($company_id, $company_name));
                        $replacement_array = array();
                        $replacement_array['applicant_name'] = ucwords($applicant_info['first_name'] . ' ' . $applicant_info['last_name']);
                        $on_boarding_link = '<a target="_blank" style="' . VIDEO_INTERVIEW_EMAIL_BTN_STYLE . '" href="' . $kpaDetials['kpa_url'] . ' ">HR Compliance And Onboarding</a>';
                        $replacement_array['on_boarding_link'] = $on_boarding_link;
                        $replacement_array['company_name'] = ucwords($company_name);
                        //log_and_send_templated_email(ON_BOARDING_REQUEST, $applicant_info['email'], $replacement_array, $message_hf);
                        log_and_send_templated_portal_email('on_boarding_request', $company_id, $applicant_info['email'], $replacement_array, $message_hf);
                        $this->session->set_flashdata('message', '<b>Notification: </b>Email has been successfully Sent!');
                        $reload_location = base_url('applicant_profile') . '/' . $applicant_info['sid'];
                        //** save record in outsource_onboarding_emails table ***//
                        $insert_array = array();
                        $insert_array['sent_date'] = date('Y-m-d H:i:s');
                        $insert_array['company_sid'] = $company_id;
                        $insert_array['employer_sid'] = $employer_sid;
                        $insert_array['applicant_sid'] = $applicant_info['sid'];
                        $insert_array['email_log_sid'] = NULL;
                        $insert_array['message_body'] = NULL;
                        $insert_array['applicant_email'] = $applicant_info['email'];
                        $this->application_tracking_system_model->save_onboarding_email_record($insert_array);
                        //** save record in outsource_onboarding_emails table ***//
                        redirect($reload_location, 'refresh');
                    }
                }
            }
        } else {
            redirect('login', "refresh");
        }
    }

    function delete_file()
    {
        $id = $this->input->post('id');
        $type = $this->input->post('type');

        if ($type == "Resume") {
            $this->application_tracking_system_model->delete_file($id, "resume");
        } elseif ($type == "Cover Letter") {
            $this->application_tracking_system_model->delete_file($id, "cover_letter");
        } elseif ($type == "file") {
            $this->application_tracking_system_model->delete_file($id, "file");
        }

        $this->session->set_flashdata('message', '<b>Success:</b> ' . $type . ' removed successfully');
    }

    public function updateEmployerStatus()
    {
        $data['session'] = $this->session->userdata('logged_in');
        $company_sid = $data["session"]["company_detail"]["sid"];
        $user_id = $this->input->post('sid');
        $status = $this->input->post('status');
        $status_sid = $this->input->post('status_sid');
        //$this->application_tracking_system_model->change_current_status($user_id, $status, $company_sid, 'portal_applicant_jobs_list');
        $oldStatus = getApplicantOnboardingPreviousStatus($user_id);

        $this->application_tracking_system_model->update_applicant_status($company_sid, $user_id, $status_sid, $status);

        // Log 
        $data['session'] = $this->session->userdata('logged_in');
        $employers_details  = $data['session']['employer_detail'];
        $employer_sid       = $employers_details['sid'];
        saveApplicantOnboardingStatusLog($user_id, $employer_sid, $status, $oldStatus);

        echo 'success';
    }

    public function save_rating()
    {
        if ($this->input->post()) {
            $data['session'] = $this->session->userdata('logged_in');
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $applicant_job_sid = $this->input->post('applicant_job_sid');
            $applicant_email = $this->input->post('applicant_email');
            $rating = $this->input->post('rating');
            $comment = $this->input->post('comment');
            $users_type = $this->input->post('users_type');

            $data_to_save = array();
            $data_to_save['company_sid'] = $company_sid;
            $data_to_save['employer_sid'] = $employer_sid;
            $data_to_save['applicant_job_sid'] = $applicant_job_sid;
            $data_to_save['applicant_email'] = $applicant_email;
            $data_to_save['rating'] = $rating;
            $data_to_save['comment'] = $comment;
            $data_to_save['users_type'] = $users_type;

            if ($users_type != 'employee') {
                $data_to_save['source_type'] = $this->input->post('video_source', true);
                $data_to_save['source_value'] = $this->input->post('yt_vm_video_url');
                $data_to_save['source_value'] = str_replace('watch?v=', 'embed/', $data_to_save['source_value']);
                $data_to_save['source_value'] = str_replace('/vimeo.com', '/player.vimeo.com/video', $data_to_save['source_value']);
                if ($data_to_save['source_type'] == 'uploaded') {
                    $source_value = upload_file_to_aws('upload_video', $company_sid, 'upload_video', $employer_sid);
                    if ($source_value != 'error') {
                        $data_to_save['source_value'] = $source_value;
                    } else unset($data_to_save['source_value']);
                }
            }



            $attachment = upload_file_to_aws('review_attachment', $company_sid, 'review_attachment', $employer_sid);

            if ($attachment != 'error') {
                $extension = pathinfo($attachment, PATHINFO_EXTENSION);

                $data_to_save['attachment'] = $attachment;
                $data_to_save['attachment_extension'] = $extension;
            }


            $this->application_tracking_system_model->save_rating($data_to_save);

            $this->session->set_flashdata('message', '<b>Success:</b> Rating saved successfully');


            header('Location: ' . $_SERVER['HTTP_REFERER']);
        } else {
            redirect('application_tracking_system/active/all/all/all/all', 'refresh');
        }
    }

    function archive_single_applicant()
    {
        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'arch_single_applicant') {
            $sid = $_REQUEST['arch_id'];
            $this->application_tracking_system_model->arch_single_applicant($sid);
            echo 'Done';
            exit;
        }
    }

    function delete_single_applicant()
    {
        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'del_single_applicant') {
            $sid = $_REQUEST['del_id'];
            $this->application_tracking_system_model->delete_single_applicant($sid);
            echo 'Done';
            exit;
        }
    }

    function deleteTalentUser($id)
    {
        $this->application_tracking_system_model->deleteTalentUser($id);
        echo 'Success';
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


    /**
     * Check for old event check
     *
     * @return Bool
     */
    private function call_old_event()
    {
        $this->load->config('calendar_config');
        $calendar_opt = $this->config->item('calendar_opt');
        if ($calendar_opt['show_new_calendar_to_all'])
            return true;
        if (
            ($calendar_opt['old_event_check'] && !$calendar_opt['ids_check'] && in_array($this->input->ip_address(), $calendar_opt['remote_ips'])) ||
            ($calendar_opt['old_event_check'] && $calendar_opt['ids_check'] && in_array($this->session->userdata('logged_in')['company_detail']['sid'], $calendar_opt['allowed_ids']))
        ) {
            return true;
        }

        return false;
    }


    /**
     * Handles AJAX requests
     * Created on: 18-07-2019
     *
     * @accepts POST
     *
     * @return JSON
     */
    function handler()
    {
        //
        $data['session'] = $this->session->userdata('logged_in');
        $company_name    = $data['session']['company_detail']['CompanyName'];
        $company_id      = $data['session']['company_detail']['sid'];
        // Set default aray
        $resp['Status'] = false;
        $resp['Response'] = 'Invalid request.';
        //
        if (!sizeof($this->input->post()) || $this->input->method(TRUE) != 'POST') $this->resp($resp);
        //
        $form_data = $this->input->post(NULL, TRUE);
        // Load the twilio library
        $this->load->library('twilio/Twilioapp', null, 'twilio');
        //
        // _e($form_data, true);
        // _e(IS_SANDBOX === 1, true);
        //
        $module = 'ats';
        $session = $this->session->userdata('logged_in');
        $company_sid = $session['company_detail']['sid'];
        $employee_sid = $session['employer_detail']['sid'];


        switch ($form_data['action']) {
            case 'send_sms':
                // Double check - If SMS module is not active
                // then through an error
                if ($session['company_detail']['sms_module_status'] == 0) {
                    $resp['Response'] = 'SMS module is not active for this company.';
                    $this->resp($resp);
                }
                // Message send
                // Create message service and add phone number
                $message_body = $form_data['message'];
                // Set & Send Request
                $this
                    ->twilio
                    ->setMode('production')
                    ->setMessage($message_body);
                $companyDetails = get_company_sms_phonenumber($company_sid, $this);
                $this->twilio->setReceiverPhone($form_data['phone_e16']);
                $this->twilio->setSenderPhone($companyDetails['phone_number']);
                $this->twilio->setMessageServiceSID($companyDetails['message_service_sid']);
                $resp2 = $this->twilio->sendMessage();
                // Check & Handling Errors
                if (!is_array($resp2)) {
                    $resp['Response'] = 'Failed to send SMS.';
                    $this->resp($resp);
                }
                if (isset($resp2['Error'])) {
                    $resp['Response'] = 'Failed to send SMS.';
                    $this->resp($resp);
                }
                // Set Insert Array
                $insert_array = $resp2['DataArray'];
                $insert_array['module_slug'] = $module;
                $insert_array['company_id']  = $company_sid;
                $insert_array['sender_user_id'] = $employee_sid;
                $insert_array['sender_user_type'] = 'employee';
                $insert_array['message_service_sid'] = $companyDetails['message_service_sid'];
                $insert_array['receiver_user_id'] = isset($form_data['applicant_id']) ? $form_data['applicant_id'] : $form_data['id'];
                $insert_array['receiver_user_type'] = $form_data['type'];
                //
                $insert_array['receiver_phone_number'] = $form_data['phone_e16'];
                // Add data in database
                $insert_id = $this
                    ->application_tracking_system_model
                    ->save_sent_message($insert_array);

                $resp['Status'] = TRUE;
                $resp['Response'] = 'SMS sent.';

                $this->resp($resp);
                break;

            case 'fetch_sms_ats':
                $records = $this
                    ->application_tracking_system_model
                    ->fetch_sms(
                        $form_data['type'],
                        isset($form_data['applicant_id']) ? $form_data['applicant_id'] : $form_data['id'],
                        $company_sid,
                        $form_data['last_fetched_id'],
                        isset($form_data['module']) ? $form_data['module'] : ''
                    );

                if (!$records) {
                    $resp['Response'] = 'No record found.';
                    $this->resp($resp);
                }
                //
                $resp['Status'] = TRUE;
                $resp['Response'] = 'Proceed';
                $resp['Data'] = $records['Records'];
                $resp['LastId'] = $records['LastId'];
                $this->resp($resp);
                break;

            case 'update_phone_number':
                // Update applicant phonenumber
                $this
                    ->application_tracking_system_model
                    ->applicant_phone_number(
                        $form_data['phone_e16'],
                        $form_data['applicant_id']
                    );
                //
                $resp['Status'] = TRUE;
                $resp['Response'] = 'Phone number updated.';
                $resp['Phone'] = phonenumber_format($form_data['phone_e16']);
                break;

            case 'send_email_to_update_number':
                //
                $dataArray = array();
                $dataArray['type'] = $form_data['type'];
                $dataArray['name'] = $form_data['name'];
                $dataArray['sid']  = $form_data['sid'];
                $dataArray['companyId'] = $company_id;
                $dataArray['companyName'] = $company_name;
                $dataArray['emailAddress'] = $form_data['email_address'];
                //
                $result = sendEmailToUpdatePhoneNumber($dataArray, $this);
                if (!$result) $this->resp($resp);
                //
                $resp['Status'] = true;
                $resp['Response'] = 'Email is sent to the applicant.';
                $this->resp($resp);
                break;
        }

        $this->resp($resp);
    }

    /**
     * Sends JSON
     * Created on: 18-07-2019
     *
     * @param $resp Array
     *
     * @return JSON
     */
    private function resp($resp)
    {
        header('Content-Type: application/json');
        echo @json_encode($resp);
        exit(0);
    }



    public function getApplicantStatusHistory($sId)
    {
        return SendResponse(
            200,
            [
                'view' => $this->load->view('manage_employer/application_tracking_system/status_log', [
                    'data' => $this->application_tracking_system_model->get_applicant_obboarding_status_log($sId)
                ], true)
            ]
        );
    }
}
