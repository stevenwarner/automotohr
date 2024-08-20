<?php defined('BASEPATH') or exit('No direct script access allowed');

class Reports extends Public_Controller
{
    private $res;
    private $limit = 50;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('reports_model');
        $this->load->model('attendance_model');

        //
        $this->res['Status'] = false;
        $this->res['Redirect'] = true;
        $this->res['Response'] = 'Session has expired. Please login again';
    }

    public function index()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'reports');
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $this->load->model('settings_model');
            $job_fair_configuration = $this->settings_model->job_fair_configuration($company_sid);
            $data['job_fair_configuration'] = $job_fair_configuration;
            $data['title'] = 'Advanced Hr Reports';

            $data['companyDetailsForSMS'] = get_company_sms_phonenumber($company_sid, $this);

            $this->load->view('main/header', $data);
            $this->load->view('reports/index');
            $this->load->view('main/footer');
        } else {
            redirect('login', "refresh");
        }
    }

    public function generate($type = null, $keyword = 'all', $job_sid = 'all', $applicant_type = 'all', $applicant_status = 'all', $start_date = 'all', $end_date = 'all', $source = 'all', $manager = 'all',  $page_number = 1)
    {

        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'reports');
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $data['title'] = 'Advanced Hr Reports - Generate ' . ucwords($type) . ' Reports';
            $keyword = urldecode($keyword);
            $job_sid = urldecode($job_sid);
            $applicant_type = urldecode($applicant_type);
            $applicant_status = urldecode($applicant_status);
            $start_date = urldecode($start_date);
            $end_date = urldecode($end_date);
            $source = urldecode($source);

            if ($job_sid != null || $job_sid != 'all') {
                $data['job_sid_array']                                          = explode(',', $job_sid);
            }
            if (!empty($start_date) && $start_date != 'all') {
                $start_date_applied = empty($start_date) ? null : DateTime::createFromFormat('m-d-Y', $start_date)->format('Y-m-d 00:00:00');
            } else {
                $start_date_applied = date('Y-m-d 00:00:00');
            }

            if (!empty($end_date) && $end_date != 'all') {
                $end_date_applied = empty($end_date) ? null : DateTime::createFromFormat('m-d-Y', $end_date)->format('Y-m-d 23:59:59');
            } else {
                $end_date_applied = date('Y-m-d 23:59:59');
            }

            $data['filter_state'] = true;

            if ($type != null) {
                if ($type == 'applicants') {
                    $myColumns = array(
                        'sid',
                        'Title',
                        'first_name',
                        'last_name',
                        'email',
                        'phone_number',
                        'date_applied',
                        'applicant_type',
                        'questionnaire',
                        'score',
                        'passing_score',
                        'status'
                    );
                    $column_info = $this->reports_model->GetColumnsInformation('portal_job_applications');
                }
            }

            $date_applied_column_info = array(
                'name' => 'date_applied',
                'type' => 'datetime',
                'primary_key' => 0
            );

            $column_info['date_applied'] = $date_applied_column_info;
            $data['flag'] = false;
            $search_string = '';
            $search_string2 = '';
            $date_search = 0;
            $this->form_validation->set_data($this->input->get(NULL, true));

            if ($type != null) { //Handle Different Report Types
                if ($type == 'applicants') {
                    if (is_admin($employer_sid)) { //echo 'admin';
                        $allJobs = $this->reports_model->GetAllJobsCompanySpecific($company_sid);
                        //                        $applicants = $this->reports_model->GetAllApplicantsCompanySpecific($company_sid, $myColumns, $search_string, $search_string2);
                    } else {
                        $allJobs = $this->reports_model->GetAllJobsCompanyAndEmployerSpecific($company_sid, $employer_sid);
                        //                        $applicants = $this->reports_model->GetAllApplicants($company_sid, $employer_sid, $myColumns, $search_string, $search_string2);
                    }

                    $lastQuery = $this->db->last_query();
                    $this->session->set_userdata('last_query', $lastQuery);
                    $this->session->set_userdata('last_query_columns', $myColumns);
                    $jobOptions = array();

                    foreach ($allJobs as $job) {
                        $state = $city = '';
                        if (isset($job['Location_City']) && $job['Location_City'] != null && $job['Location_City'] != '') $city = ' - ' . ucfirst($job['Location_City']);
                        if (isset($job['Location_State']) && $job['Location_State'] != null && $job['Location_State'] != '') $state = ', ' . db_get_state_name($job['Location_State'])['state_name'];
                        $active = ' (In Active) ';

                        if ($job['active']) {
                            $active = ' (Active) ';
                        }

                        $jobOptions[$job['sid']] = $job['Title'] . $city . $state . $active;
                    }

                    $data['company_sid'] = $company_sid;
                    $data['jobOptions'] = $jobOptions;
                    $applicant_types = explode(',', APPLICANT_TYPE_ATS);
                    $data['applicant_types'] = $applicant_types;
                }
            } else {
                redirect('reports');
            }

            $applicant_statuses = $this->reports_model->get_company_statuses($company_sid);
            $data['applicant_statuses'] = $applicant_statuses;
            $per_page = PAGINATION_RECORDS_PER_PAGE;
            //$per_page = 2;
            //$page_number = $this->input->get('page_number');
            $offset = 0;

            if ($page_number > 1) {
                $offset = ($page_number - 1) * $per_page;
            }

            $total_records = $this->reports_model->get_applicants($company_sid, $keyword, $job_sid, $applicant_type, $applicant_status, $start_date_applied, $end_date_applied, true, null, null, $source, $manager);
            $this->load->library('pagination');
            $pagination_base = base_url('reports/generate/applicants') . '/' . urlencode($keyword) . '/' . $job_sid . '/' . urlencode($applicant_type) . '/' . urlencode($applicant_status) . '/' . urlencode($start_date) . '/' . urlencode($end_date) . '/' . urlencode($source) . '/' . urlencode($manager);
            //echo $pagination_base;
            $config = array();
            $config["base_url"] = $pagination_base;
            $config["total_rows"] = $total_records;
            $config["per_page"] = $per_page;
            $config["uri_segment"] = 11;
            $config["num_links"] = 8;
            $config["use_page_numbers"] = true;
            $config['full_tag_open'] = '<nav class="hr-pagination"><ul>';
            $config['full_tag_close'] = '</ul></nav><!--pagination-->';
            $config['first_link'] = '<i class="fa fa-angle-double-left"></i>';
            $config['first_tag_open'] = '<li class="prev page">';
            $config['first_tag_close'] = '</li>';
            $config['last_link'] = '<i class="fa fa-angle-double-right"></i>';
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
            $data['page_links'] = $this->pagination->create_links();
            $data['current_page'] = $page_number;
            $data['from_records'] = $offset == 0 ? 1 : $offset;
            $data['to_records'] = $total_records < $per_page ? $total_records : $offset + $per_page;

            //-----------------------------------Pagination Ends-----------------------------//
            //$applicants = $this->Reports_model->get_applicants($company_sid, $search_string, $search_string2, $start_date_applied, $end_date_applied, false, $per_page, $offset);
            $applicants = $this->reports_model->get_applicants($company_sid, $keyword, $job_sid, $applicant_type, $applicant_status, $start_date_applied, $end_date_applied, false, $per_page, $offset, $source, $manager);


            $data['applicants'] = $applicants;
            $data['applicants_count'] = $total_records;

            $companyinfo = getCompanyInfo($company_sid);
            $data['companyName'] = $companyinfo['company_name'];


            // ** export file sheet ** //
            if (isset($_POST['submit']) && $_POST['submit'] == 'Export') {

                $myRecords = $this->reports_model->get_applicants($company_sid, $keyword, $job_sid, $applicant_type, $applicant_status, $start_date_applied, $end_date_applied, false, true, null, null, $source, $manager);
                if (isset($myRecords) && sizeof($myRecords) > 0) {
                    header('Content-Type: text/csv; charset=utf-8');
                    header('Content-Disposition: attachment; filename=data.csv');
                    $output = fopen('php://output', 'w');

                    if (isset($_POST['embed-source']) && $_POST['embed-source'] == 1) {
                        $myColumns = array(
                            'sid',
                            'Title',
                            'first_name',
                            'last_name',
                            'email',
                            'phone_number',
                            'date_applied',
                            'applicant_type',
                            'applicant_source',
                            'questionnaire',
                            'score',
                            'passing_score',
                            'status',
                            'status_change_date',
                            'status_changed_by',
                            'reviews_info'

                        );
                    } else {
                        $myColumns = array(
                            'sid',
                            'Title',
                            'first_name',
                            'last_name',
                            'email',
                            'phone_number',
                            'date_applied',
                            'applicant_type',
                            'questionnaire',
                            'score',
                            'passing_score',
                            'status',
                            'status_change_date',
                            'status_changed_by',
                            'reviews_info'
                        );
                    }
                    $cols = array();

                    foreach ($myColumns as $col) {
                        if ($col != 'questionnaire' && $col != 'score' && $col != 'passing_score' && $col != 'sid') {
                            if ($col == 'Title') {
                                $cols[] = 'Job Title';
                            } else {
                                $cols[] = ucwords(str_replace('_', ' ', $col));
                            }
                        }
                    }

                    $cols[] = 'Questionnaire Score';
                    $cols[] = 'Reviews Score';
                    $cols[] = 'Interview Scores';

                    fputcsv($output, array($companyinfo['company_name']));

                    fputcsv($output, $cols);

                    foreach ($myRecords as $applicant) {
                        $input = array();
                        foreach ($myColumns as $myColumn) {
                            if ($myColumn != 'questionnaire' && $myColumn != 'score' && $myColumn != 'passing_score' && $myColumn != 'sid') {
                                if ($myColumn != 'Title' && $myColumn != 'applicant_type') {
                                    $columnDetail = $column_info[$myColumn];
                                    $columnType = $columnDetail['type'];
                                    if ($columnType == 'datetime') {
                                        $input[$myColumn] = reset_datetime(array('datetime' => $applicant[$myColumn], '_this' => $this));
                                    } else if ($myColumn == 'status_change_date') {

                                        $input[$myColumn] = $applicant['status_change_date'] != null ? reset_datetime(array('datetime' => $applicant['status_change_date'], '_this' => $this)) : 'N/A';
                                    } else if ($myColumn == 'status_changed_by') {

                                        $input[$myColumn] = $applicant['status_change_by'] != null ? getUserNameBySID($applicant['status_change_by']) : 'N/A';
                                    } else if ($myColumn == 'reviews_info') {

                                        $reviewNote = '';
                                        if (!empty($applicant['review_comment'])) {
                                            foreach ($applicant['review_comment'] as $commentRow) {
                                                $reviewNote .=  "\n Employer: " . getUserNameBySID($commentRow['employer_sid']) . "\n\n" . " Rating: " . $commentRow['rating'] . "\n\n Note: " . strip_tags($commentRow['comment']) . "\n\n Date: " . date_with_time($commentRow['date_added']) . "\n\n";
                                            }
                                            $input[$myColumn] = $reviewNote;
                                        } else {
                                            $input[$myColumn] = "N/A";
                                        }
                                    } else {
                                        $input[$myColumn] = $applicant[$myColumn];
                                    }
                                } else {
                                    $city = '';
                                    $state = '';
                                    if (isset($applicant['Location_City']) && $applicant['Location_City'] != NULL) {
                                        $city = ' - ' . ucfirst($applicant['Location_City']);
                                    }
                                    if (isset($applicant['Location_State']) && $applicant['Location_State'] != NULL) {
                                        $state = ', ' . db_get_state_name($applicant['Location_State'])['state_name'];
                                    }
                                    $input[$myColumn] = ($applicant[$myColumn] == '' ? 'Job Removed From System' : $applicant[$myColumn] . $city . $state);
                                }
                            }
                        }

                        if ($applicant['questionnaire'] == '' || $applicant['questionnaire'] == NULL) {
                            $input['questionnaire_score'] = 'N/A';
                        } else {
                            $result = $applicant['score'];
                            if ($applicant['score'] >= $applicant['passing_score']) {
                                $result .= ' (Pass)';
                            } else {
                                $result .= ' (Fail)';
                            }
                            $input['questionnaire_score'] = $result;
                        }

                        $input['reviews_score'] = $applicant['review_score'] . ' with ' . $applicant['review_count'] . ' Review(s)';

                        if (sizeof($applicant['scores']) > 0) {
                            $score_text = '';
                            foreach ($applicant['scores'] as $score) {
                                $score_text .= 'Employer : ' . ucwords($score['first_name'] . ' ' . $score['last_name']) . ' ';
                                $score_text .= 'Candidate Score : ' . $score['candidate_score'] . ' out of 100 ';
                                $score_text .= 'Job Relevancy Score : ' . $score['job_relevancy_score'] . ' out of 100; ';
                            }
                        } else {
                            $score_text = 'No interview scores';
                        }

                        $input['scores'] = $score_text;
                        fputcsv($output, $input);
                    }
                    fclose($output);
                    exit;
                } else {
                    $this->session->set_flashdata('message', 'No data found.');
                }
            }

            //
            $managers = $this->reports_model->get_managers($company_sid);

            $data['managers'] = $managers;

            //  _e($data['managers'],true,true);
            $this->load->view('main/header', $data);
            $this->load->view('reports/generate');
            $this->load->view('main/footer');
        } else {
            redirect('login', "refresh");
        }
    }

    public function generate_monthly_filled_jobs_report()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'reports');
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['company_detail']['sid'];
            $status = 0;
            $year = date("Y");
            $data['filter_state'] = false;
            $data['search'] = false;
            $data['status'] = false;

            if (isset($_GET['submit']) && $_GET['submit'] == 'Apply Filters') {
                $search_data = $this->input->get(NULL, true);
                $status = $this->input->get('status');
                $year = $search_data['year'];
                $data['filter_state'] = true;
                $data['search'] = $search_data;
                $data['status'] = $status;
            }

            $jan_jobs = $this->reports_model->GetAllHiredJobs($company_sid, '01-01-' . $year, '31-01-' . $year, $status);

            if (is_leap_year($year)) {
                $feb_jobs = $this->reports_model->GetAllHiredJobs($company_sid, '01-02-' . $year, '29-02-' . $year, $status);
            } else {
                $feb_jobs = $this->reports_model->GetAllHiredJobs($company_sid, '01-02-' . $year, '28-02-' . $year, $status);
            }

            $mar_jobs = $this->reports_model->GetAllHiredJobs($company_sid, '01-03-' . $year, '31-03-' . $year, $status);
            $apr_jobs = $this->reports_model->GetAllHiredJobs($company_sid, '01-04-' . $year, '30-04-' . $year, $status);
            $may_jobs = $this->reports_model->GetAllHiredJobs($company_sid, '01-05-' . $year, '31-05-' . $year, $status);
            $jun_jobs = $this->reports_model->GetAllHiredJobs($company_sid, '01-06-' . $year, '30-06-' . $year, $status);
            $jul_jobs = $this->reports_model->GetAllHiredJobs($company_sid, '01-07-' . $year, '31-07-' . $year, $status);
            $aug_jobs = $this->reports_model->GetAllHiredJobs($company_sid, '01-08-' . $year, '31-08-' . $year, $status);
            $sep_jobs = $this->reports_model->GetAllHiredJobs($company_sid, '01-09-' . $year, '30-09-' . $year, $status);
            $oct_jobs = $this->reports_model->GetAllHiredJobs($company_sid, '01-10-' . $year, '31-10-' . $year, $status);
            $nov_jobs = $this->reports_model->GetAllHiredJobs($company_sid, '01-11-' . $year, '30-11-' . $year, $status);
            $dec_jobs = $this->reports_model->GetAllHiredJobs($company_sid, '01-12-' . $year, '31-12-' . $year, $status);

            $chart_data = array(
                array('January', count($jan_jobs)),
                array('February', count($feb_jobs)),
                array('March', count($mar_jobs)),
                array('April', count($apr_jobs)),
                array('May', count($may_jobs)),
                array('June', count($jun_jobs)),
                array('July', count($jul_jobs)),
                array('August', count($aug_jobs)),
                array('September', count($sep_jobs)),
                array('October', count($oct_jobs)),
                array('November', count($nov_jobs)),
                array('December', count($dec_jobs))
            );

            $jobs = array(
                'january' => $jan_jobs,
                'february' => $feb_jobs,
                'march' => $mar_jobs,
                'april' => $apr_jobs,
                'may' => $may_jobs,
                'june' => $jun_jobs,
                'july' => $jul_jobs,
                'august' => $aug_jobs,
                'september' => $sep_jobs,
                'october' => $oct_jobs,
                'november' => $nov_jobs,
                'december' => $dec_jobs
            );

            $data['title'] = 'Advanced Hr Reports - Closed / Filled Jobs Per Month';
            $data['jobs'] = $jobs;
            $data['chart_data'] = json_encode($chart_data);


            //
            $companyinfo = getCompanyInfo($company_sid);
            $data['companyName'] = $companyinfo['company_name'];

            if (isset($_POST['submit']) && $_POST['submit'] == 'Export') {
                header('Content-Type: text/csv; charset=utf-8');
                header('Content-Disposition: attachment; filename=data.csv');
                $output = fopen('php://output', 'w');

                //
                fputcsv($output, array($companyinfo['company_name']), '', '', '');

                fputcsv($output, array('Job Title', 'Filled Date'));

                foreach ($jobs as $month => $jobList) {
                    fputcsv($output, array($month));

                    if (sizeof($jobList) > 0) {
                        foreach ($jobList as $job) {
                            $input = array();
                            $state = $city = '';
                            if (isset($job['Location_City']) && $job['Location_City'] != null && $job['Location_City'] != '') $city = ' - ' . ucfirst($job['Location_City']);
                            if (isset($job['Location_State']) && $job['Location_State'] != null && $job['Location_State'] != '') $state = ', ' . db_get_state_name($job['Location_State'])['state_name'];
                            $input['Title'] = ($job['Title'] != '' ? $job['Title'] . $city . $state : 'Job Removed From System');
                            $input['hired_date'] = reset_datetime(array('datetime' => $job['hired_date'], '_this' => $this));
                            fputcsv($output, $input);
                        }
                    } else {
                        fputcsv($output, array('No jobs found'));
                    }
                }
                fclose($output);
                exit;
            }

            $this->load->view('main/header', $data);
            $this->load->view('reports/generate_monthly_filled_jobs_report');
            $this->load->view('main/footer');
        } else {
            redirect('login', "refresh");
        }
    }

    public function generate_candidates_between_certain_period($start_date = null, $end_date = null, $keyword = 'all', $job_sid = 'all', $applicant_type = 'all', $applicant_status = 'all', $page_number = 1)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'reports');
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];

            if ($start_date == null || $start_date == 'beginning-of-time') {
                $start_date = date('Y-m-d 00:00:00');
            } else {
                $start_date = date('Y-m-d', strtotime(urldecode(str_replace('-', '/', $start_date))));
            }

            if ($end_date == null || $end_date == 'end-of-days') {
                $end_date = date('Y-m-d 23:59:59');
            } else {
                $end_date = date('Y-m-d', strtotime(urldecode(str_replace('-', '/', $end_date))));
            }

            $allJobs = $this->reports_model->GetAllJobsCompanySpecific($company_sid);
            $jobOptions = array();

            foreach ($allJobs as $job) {
                $state = $city = '';
                if (isset($job['Location_City']) && $job['Location_City'] != null && $job['Location_City'] != '') $city = ' - ' . ucfirst($job['Location_City']);
                if (isset($job['Location_State']) && $job['Location_State'] != null && $job['Location_State'] != '') $state = ', ' . db_get_state_name($job['Location_State'])['state_name'];


                $active = ' (In Active) ';

                if ($job['active']) {
                    $active = ' (Active) ';
                }

                $jobOptions[$job['sid']] = $job['Title'] . $city . $state . $active;
            }
            $data['jobOptions'] = $jobOptions;
            $applicant_types = explode(',', APPLICANT_TYPE_ATS);
            $data['applicant_types'] = $applicant_types;

            $keyword = urldecode($keyword);
            $job_sid = urldecode($job_sid);
            $applicant_type = urldecode($applicant_type);
            $applicant_status = urldecode($applicant_status);
            $data['title'] = 'Advanced Hr Reports - Applicants Between ( ' . date('m-d-Y', strtotime($start_date)) . ' - ' . date('m-d-Y', strtotime($end_date)) . ' )';
            $data['startdate'] = date('m-d-Y', strtotime($start_date));
            $data['enddate'] = date('m-d-Y', strtotime($end_date));
            $data['keyword'] = $keyword;
            $data['job_sid'] = $job_sid;
            $data['applicant_type'] = $applicant_type;
            $data['applicant_status'] = $applicant_status;

            if ($job_sid != null || $job_sid != 'all') {
                $data['job_sid_array']                                          = explode(',', $job_sid);
            }

            $per_page = PAGINATION_RECORDS_PER_PAGE;
            //$per_page = 2;
            //$page_number = $this->input->get('page_number');
            $offset = 0;

            if ($page_number > 1) {
                $offset = ($page_number - 1) * $per_page;
            }

            $total_records = $this->reports_model->GetAllApplicantsBetweenPeriod($company_sid, $start_date, $end_date, $keyword, 0, $job_sid, $applicant_type, $applicant_status, true);
            $this->load->library('pagination');
            $pagination_base = base_url('reports/candidates_between_period') . '/' . urlencode($start_date) . '/' . urlencode($end_date) . '/' . urlencode($keyword) . '/' . urlencode($job_sid) . '/' . urlencode($applicant_type) . '/' . urlencode($applicant_status);
            //echo $pagination_base;
            $config = array();
            $config["base_url"] = $pagination_base;
            $config["total_rows"] = $total_records;
            $config["per_page"] = $per_page;
            $config["uri_segment"] = 9;
            $config["num_links"] = 8;
            $config["use_page_numbers"] = true;
            $config['full_tag_open'] = '<nav class="hr-pagination"><ul>';
            $config['full_tag_close'] = '</ul></nav><!--pagination-->';
            $config['first_link'] = '<i class="fa fa-angle-double-left"></i>';
            $config['first_tag_open'] = '<li class="prev page">';
            $config['first_tag_close'] = '</li>';
            $config['last_link'] = '<i class="fa fa-angle-double-right"></i>';
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

            //$config['page_query_string'] = true;
            //$config['reuse_query_string'] = true;
            //$config['query_string_segment'] = 'page_number';

            $this->pagination->initialize($config);
            $data['page_links'] = $this->pagination->create_links();
            $data['current_page'] = $page_number;
            $data['from_records'] = $offset == 0 ? 1 : $offset;
            $data['to_records'] = $total_records < $per_page ? $total_records : $offset + $per_page;
            $data['applicants_count'] = $total_records;

            $applicant_statuses = $this->reports_model->get_company_statuses($company_sid);
            $data['applicant_statuses'] = $applicant_statuses;

            $applicants = $this->reports_model->GetAllApplicantsBetweenPeriod($company_sid, $start_date, $end_date, $keyword, 0, $job_sid, $applicant_type, $applicant_status, false, $per_page, $offset);
            $data['applicants'] = $applicants;
            $data['is_hired_report'] = false;

            //
            $companyinfo = getCompanyInfo($company_sid);
            $data['companyName'] = $companyinfo['company_name'];


            //** excel sheet export code **//
            if (isset($_POST['submit']) && $_POST['submit'] == 'Export') {
                $applicants = $this->reports_model->GetAllApplicantsBetweenPeriod($company_sid, $start_date, $end_date, $keyword, 0, $job_sid, $applicant_type, $applicant_status, false);
                if (isset($applicants) && sizeof($applicants) > 0) {
                    header('Content-Type: text/csv; charset=utf-8');
                    header('Content-Disposition: attachment; filename=data.csv');
                    $output = fopen('php://output', 'w');


                    fputcsv($output, array($companyinfo['company_name'], '', ''));

                    if (isset($is_hired_report) && $is_hired_report == true) {
                        fputcsv($output, array('Job Title', 'Applicant Name', 'Hired On'));
                    } else {
                        fputcsv($output, array('Job Title', 'Applicant Name', 'Application Date'));
                    }

                    foreach ($applicants as $applicant) {
                        $state = $city = '';
                        if (isset($applicant['Location_City']) && $applicant['Location_City'] != null && $applicant['Location_City'] != '') $city = ' - ' . ucfirst($applicant['Location_City']);
                        if (isset($applicant['Location_State']) && $applicant['Location_State'] != null && $applicant['Location_State'] != '') $state = ', ' . db_get_state_name($applicant['Location_State'])['state_name'];
                        $input = array();
                        $input['Title'] = ($applicant['Title'] != '' ? $applicant['Title'] . $city . $state : 'Job Removed From System');
                        $input['name'] = ucwords($applicant['first_name'] . ' ' . $applicant['last_name']);

                        if (isset($is_hired_report) && $is_hired_report == true) {
                            $input['date'] = date('m-d-Y', strtotime(str_replace('-', '/', $applicant['hired_date'])));
                        } else {
                            $input['date'] = date('m-d-Y', strtotime(str_replace('-', '/', $applicant['date_applied'])));
                        }
                        // Added on: 27-06-2019
                        $input['date'] = reset_datetime(array(
                            'datetime' => $applicant[isset($is_hired_report) && $is_hired_report == true ? 'hired_date' : 'date_applied'],
                            '_this' => $this
                        ));

                        fputcsv($output, $input);
                    }
                    fclose($output);
                    exit;
                } else {
                    $this->session->set_flashdata('message', 'No data found.');
                }
            }

            $this->load->view('main/header', $data);
            $this->load->view('reports/generate_applicants_between_period_report');
            $this->load->view('main/footer');
        } else {
            redirect('login', "refresh");
        }
    }

    public function generate_time_to_fill($keyword = 'all')
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'reports');
            $keyword = urldecode($keyword);
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $data['title'] = 'Advanced Hr Reports - Time To Fill';
            $jobs = array();

            if (is_admin($employer_sid)) {
                $jobs = $this->reports_model->GetAllJobsCompanySpecific($company_sid, $keyword);
            } else {
                $jobs = $this->reports_model->GetAllJobsCompanyAndEmployerSpecific($company_sid, $employer_sid, $keyword);
            }

            $average_difference = 0;

            foreach ($jobs as $jobKey => $job) {
                $applicants = $this->reports_model->GetAllApplicantsCompanyEmployerAndJobSpecific($company_sid, $employer_sid, $job['sid']);

                foreach ($applicants as $appkey => $applicant) {
                    $jobActivationDate = strtotime(str_replace('-', '/', $job['activation_date']));
                    $dateHired = strtotime(str_replace('-', '/', $applicant['hired_date']));
                    $differenceUnix = $dateHired - $jobActivationDate;

                    if ($differenceUnix < 0) {
                        $differenceUnix = 0;
                    }

                    $applicants[$appkey]['unix_diff'] = $differenceUnix;
                    $difference = ceil(intval($differenceUnix) / (60 * 60 * 24));
                    $applicants[$appkey]['days_to_fill'] = $difference;
                    $average_difference += intval($difference);
                }

                if (count($applicants) > 0) {
                    $average_difference = ceil($average_difference / count($applicants));
                } else {
                    $average_difference = 0;
                }

                $jobs[$jobKey]['average_days_to_fill'] = $average_difference;
                $jobs[$jobKey]['applicant_count'] = count($applicants);
                $jobs[$jobKey]['applicants'] = $applicants;
            }

            $data['jobs'] = $jobs;

            //
            $companyinfo = getCompanyInfo($company_sid);
            $data['companyName'] = $companyinfo['company_name'];


            if (isset($_POST['submit']) && $_POST['submit'] == 'Export') {
                if (isset($jobs) && sizeof($jobs) > 0) {
                    header('Content-Type: text/csv; charset=utf-8');
                    header('Content-Disposition: attachment; filename=data.csv');
                    $output = fopen('php://output', 'w');

                    fputcsv($output, array($companyinfo['company_name'], '', '', ''));


                    fputcsv($output, array('Job Title', 'Job Date', 'Applicants', 'Average Days To Fill'));

                    foreach ($jobs as $job) {
                        $input = array();
                        if (isset($job['Location_City']) && $job['Location_City'] != NULL) {
                            $city = ' - ' . ucfirst($job['Location_City']);
                        }
                        if (isset($job['Location_State']) && $job['Location_State'] != NULL) {
                            $state = ', ' . db_get_state_name($job['Location_State'])['state_name'];
                        }
                        $input['Title'] = $job['Title'] . $city . $state;
                        // $input['date'] = date('m-d-Y', strtotime(str_replace('-', '/', $job['activation_date'])));
                        $input['date'] = reset_datetime(array('datetime' => $job['activation_date'], '_this' => $this));
                        $input['applicant_count'] = $job['applicant_count'];
                        $input['average_days_to_fill'] = $job['average_days_to_fill'];
                        fputcsv($output, $input);
                    }
                    fclose($output);
                    exit;
                } else {
                    $this->session->set_flashdata('message', 'No data found.');
                }
            }

            $this->load->view('main/header', $data);
            $this->load->view('reports/generate_time_to_fill_report');
            $this->load->view('main/footer');
        } else {
            redirect('login', "refresh");
        }
    }

    public function generate_time_to_hire($keyword = 'all')
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'reports');
            $keyword = urldecode($keyword);
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $data['title'] = 'Advanced Hr Reports - Time To Hire';
            $jobs = array();

            if (is_admin($employer_sid)) {
                $jobs = $this->reports_model->GetAllJobsCompanySpecific($company_sid, $keyword);
            } else {
                $jobs = $this->reports_model->GetAllJobsCompanyAndEmployerSpecific($company_sid, $employer_sid, $keyword);
            }

            $average_difference = 0;

            foreach ($jobs as $jobKey => $job) {
                $applicants = $this->reports_model->GetAllApplicantsCompanyEmployerAndJobSpecific($company_sid, $employer_sid, $job['sid']);
                foreach ($applicants as $appkey => $applicant) {
                    $applicationDate = strtotime(str_replace('-', '/', $applicant['date_applied']));
                    $hiredDate = strtotime(str_replace('-', '/', $applicant['hired_date']));
                    $differenceUnix = $hiredDate - $applicationDate;

                    if ($differenceUnix < 0) {
                        $differenceUnix = 0;
                    }

                    $applicants[$appkey]['unix_diff'] = $differenceUnix;
                    $difference = ceil(intval($differenceUnix) / (60 * 60 * 24));
                    $applicants[$appkey]['days_to_hire'] = $difference;
                    $average_difference += intval($difference);
                }

                if (count($applicants) > 0) {
                    $average_difference = ceil($average_difference / count($applicants));
                } else {
                    $average_difference = 0;
                }

                $jobs[$jobKey]['average_days_to_hire'] = $average_difference;
                $jobs[$jobKey]['applicant_count'] = count($applicants);
                $jobs[$jobKey]['applicants'] = $applicants;
            }

            $data['jobs'] = $jobs;

            //
            $companyinfo = getCompanyInfo($company_sid);
            $data['companyName'] = $companyinfo['company_name'];


            //** excel sheet file **//
            if (isset($_POST['submit']) && $_POST['submit'] == 'Export') {
                if (isset($jobs) && sizeof($jobs) > 0) {
                    header('Content-Type: text/csv; charset=utf-8');
                    header('Content-Disposition: attachment; filename=data.csv');
                    $output = fopen('php://output', 'w');

                    fputcsv($output, array($companyinfo['company_name'], '', '', ''));


                    fputcsv($output, array('Job Title', 'Job Date', 'Applicants', 'Average Days To Hire'));

                    foreach ($jobs as $job) {
                        $state = $city = '';
                        if (isset($job['Location_City']) && $job['Location_City'] != null && $job['Location_City'] != '') $city = ' - ' . ucfirst($job['Location_City']);
                        if (isset($job['Location_State']) && $job['Location_State'] != null && $job['Location_State'] != '') $state = ', ' . db_get_state_name($job['Location_State'])['state_name'];
                        $input = array();
                        $input['Title'] = $job['Title'] . $city . $state;
                        // $input['date'] = date('m-d-Y', strtotime(str_replace('-', '/', $job['activation_date'])));
                        $input['date'] = reset_datetime(array('datetime' => $job['activation_date'], '_this' => $this));
                        $input['applicant_count'] = $job['applicant_count'];
                        $input['average_days_to_hire'] = $job['average_days_to_hire'];
                        fputcsv($output, $input);
                    }
                    fclose($output);
                    exit;
                } else {
                    $this->session->set_flashdata('message', 'No data found.');
                }
            }
            //** excel sheet file **//
            $this->load->view('main/header', $data);
            $this->load->view('reports/generate_time_to_hire_report');
            $this->load->view('main/footer');
        } else {
            redirect('login', "refresh");
        }
    }

    public function generate_active_new_hire_categories()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');

            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'reports');

            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $data['title'] = 'Advanced Hr Reports - Active New Hire Categories';
            $categories = $this->reports_model->GetAllJobCategoriesWhereApplicantsAreHired($company_sid);
            $data['categories'] = $categories;
            //
            $companyinfo = getCompanyInfo($company_sid);
            $data['companyName'] = $companyinfo['company_name'];

            //** excel sheet file **//
            if (isset($_POST['submit']) && $_POST['submit'] == 'Export') {
                if (isset($categories) && sizeof($categories) > 0) {

                    header('Content-Type: text/csv; charset=utf-8');
                    header('Content-Disposition: attachment; filename=data.csv');
                    $output = fopen('php://output', 'w');

                    fputcsv($output, array($companyinfo['company_name'], ''));


                    fputcsv($output, array('Category', 'Hire Count'));

                    foreach ($categories as $key => $category) {
                        $input = array();
                        $input['category'] = $category['category'];
                        $input['count'] = $category['count'];
                        fputcsv($output, $input);
                    }
                    fclose($output);
                    exit;
                } else {
                    $this->session->set_flashdata('message', 'No data found.');
                }
            }
            //** excel sheet file **//

            $this->load->view('main/header', $data);
            $this->load->view('reports/generate_active_new_hire_categories_report');
            $this->load->view('main/footer');
        } else {
            redirect('login', "refresh");
        }
    }

    public function generate_new_hires_report($start_date = null, $end_date = null, $keyword = 'all', $job_sid = 'all', $applicant_type = 'all', $applicant_status = 'all', $page_number = 1)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');

            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'reports');

            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $data['title'] = 'Advanced Hr Reports - New Hires';

            if ($start_date == null || $start_date == 'beginning-of-time') {
                $start_date = date('Y-m-d 00:00:00');
            } else {
                $start_date = date('Y-m-d', strtotime(urldecode(str_replace('-', '/', $start_date))));
            }

            if ($end_date == null || $end_date == 'end-of-days') {
                $end_date = date('Y-m-d 23:59:59');
            } else {
                $end_date = date('Y-m-d', strtotime(urldecode(str_replace('-', '/', $end_date))));
            }

            $allJobs = $this->reports_model->GetAllJobsCompanySpecific($company_sid);
            $jobOptions = array();

            foreach ($allJobs as $job) {
                $state = $city = '';
                if (isset($job['Location_City']) && $job['Location_City'] != null && $job['Location_City'] != '') $city = ' - ' . ucfirst($job['Location_City']);
                if (isset($job['Location_State']) && $job['Location_State'] != null && $job['Location_State'] != '') $state = ', ' . db_get_state_name($job['Location_State'])['state_name'];
                $active = ' (In Active) ';

                if ($job['active']) {
                    $active = ' (Active) ';
                }

                $jobOptions[$job['sid']] = $job['Title'] . $city . $state . $active;
            }

            $data['jobOptions'] = $jobOptions;
            $applicant_types = explode(',', APPLICANT_TYPE_ATS);
            $data['applicant_types'] = $applicant_types;

            $keyword = urldecode($keyword);
            $job_sid = urldecode($job_sid);

            $applicant_type = urldecode($applicant_type);
            $applicant_status = urldecode($applicant_status);
            $data['startdate'] = date('m-d-Y', strtotime($start_date));
            $data['enddate'] = date('m-d-Y', strtotime($end_date));
            $data['keyword'] = $keyword;
            $data['job_sid'] = $job_sid;
            $data['applicant_type'] = $applicant_type;
            $data['applicant_status'] = $applicant_status;

            if ($job_sid != null || $job_sid != 'all') {
                $data['job_sid_array']                                          = explode(',', $job_sid);
            }

            $per_page = PAGINATION_RECORDS_PER_PAGE;
            $offset = 0;

            if ($page_number > 1) {
                $offset = ($page_number - 1) * $per_page;
            }

            $total_records = $this->reports_model->GetAllApplicantsBetweenNew($company_sid, $start_date, $end_date, $keyword, 1, $job_sid, $applicant_type, $applicant_status, true);
            $this->load->library('pagination');
            $pagination_base = base_url('reports/generate_new_hires_report') . '/' . urlencode($start_date) . '/' . urlencode($end_date) . '/' . urlencode($keyword) . '/' . urlencode($job_sid) . '/' . urlencode($applicant_type) . '/' . urlencode($applicant_status);
            $config = array();
            $config["base_url"] = $pagination_base;
            $config["total_rows"] = $total_records;
            $config["per_page"] = $per_page;
            $config["uri_segment"] = 9;
            $config["num_links"] = 8;
            $config["use_page_numbers"] = true;
            $config['full_tag_open'] = '<nav class="hr-pagination"><ul>';
            $config['full_tag_close'] = '</ul></nav><!--pagination-->';
            $config['first_link'] = '<i class="fa fa-angle-double-left"></i>';
            $config['first_tag_open'] = '<li class="prev page">';
            $config['first_tag_close'] = '</li>';
            $config['last_link'] = '<i class="fa fa-angle-double-right"></i>';
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

            //$config['page_query_string'] = true;
            //$config['reuse_query_string'] = true;
            //$config['query_string_segment'] = 'page_number';

            $this->pagination->initialize($config);
            $data['page_links'] = $this->pagination->create_links();
            $data['current_page'] = $page_number;
            $data['from_records'] = $offset == 0 ? 1 : $offset;
            $data['to_records'] = $total_records < $per_page ? $total_records : $offset + $per_page;
            $data['applicants_count'] = $total_records;

            $applicant_statuses = $this->reports_model->get_company_statuses($company_sid);
            $data['applicant_statuses'] = $applicant_statuses;


            $applicants = $this->reports_model->GetAllApplicantsBetweenNew($company_sid, $start_date, $end_date, $keyword, 1, $job_sid, $applicant_type, $applicant_status, false, $per_page, $offset);
            $data['title'] = 'Advanced Hr Reports - Applicants Hired Between ( ' . date('m-d-Y', strtotime($start_date)) . ' - ' . date('m-d-Y', strtotime($end_date)) . ' )';
            $data['applicants'] = $applicants;
            $data['is_hired_report'] = true;

            //
            $companyinfo = getCompanyInfo($company_sid);
            $data['companyName'] = $companyinfo['company_name'];


            //** excel sheet file **//
            if (isset($_POST['submit']) && $_POST['submit'] == 'Export') {
                $applicants = $this->reports_model->GetAllApplicantsBetweenNew($company_sid, $start_date, $end_date, $keyword, 1, $job_sid, $applicant_type, $applicant_status, false);

                if (isset($applicants) && sizeof($applicants) > 0) {

                    header('Content-Type: text/csv; charset=utf-8');
                    header('Content-Disposition: attachment; filename=New Hires data.csv');
                    $output = fopen('php://output', 'w');

                    fputcsv($output, array($companyinfo['company_name'], '', ''));

                    if (isset($data['is_hired_report']) && $data['is_hired_report'] == true) {
                        fputcsv($output, array('Job Title', 'Applicant Name', 'Hired On'));
                    } else {
                        fputcsv($output, array('Job Title', 'Applicant Name', 'Application Date'));
                    }

                    foreach ($applicants as $applicant) {
                        $state = $city = '';
                        if (isset($job['Location_City']) && $job['Location_City'] != null && $job['Location_City'] != '') $city = ' - ' . ucfirst($job['Location_City']);
                        if (isset($job['Location_State']) && $job['Location_State'] != null && $job['Location_State'] != '') $state = ', ' . db_get_state_name($job['Location_State'])['state_name'];
                        $input = array();
                        $input['Title'] = ($applicant['Title'] != '' ? $applicant['Title'] . $city . $state : 'Job Removed From System');
                        $input['name'] = ucwords($applicant['first_name'] . ' ' . $applicant['last_name']);
                        if (isset($data['is_hired_report']) && $data['is_hired_report'] == true) {
                            $input['date'] = date('m-d-Y', strtotime(str_replace('-', '/', $applicant['hired_date'])));
                        } else {
                            $input['date'] = date('m-d-Y', strtotime(str_replace('-', '/', $applicant['date_applied'])));
                        }
                        // Added on: 27-06-2019
                        $input['date'] = reset_datetime(array('datetime' => $applicant[isset($data['is_hired_report']) && $data['is_hired_report'] == true ? 'hired_date' : 'date_applied'], '_this' => $this));
                        fputcsv($output, $input);
                    }

                    fclose($output);
                    exit;
                } else {
                    $this->session->set_flashdata('message', 'No data found.');
                }
            }
            //** excel sheet file **//

            $this->load->view('main/header', $data);
            $this->load->view('reports/generate_applicants_between_period_report');
            $this->load->view('main/footer');
        } else {
            redirect('login', "refresh");
        }
    }

    public function generate_new_hires_onboarding_report($start_date = null, $end_date = null, $keyword = 'all')
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');

            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'reports');

            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];

            $keyword = urldecode($keyword);
            if ($start_date == null || $start_date == 'beginning-of-time') {
                $start_date = date('Y-m-d 00:00:00');
            } else {
                $start_date = date('Y-m-d', strtotime(urldecode(str_replace('-', '/', $start_date))));
            }

            if ($end_date == null || $end_date == 'end-of-days') {
                $end_date = date('Y-m-d 23:59:59');
            } else {
                $end_date = date('Y-m-d', strtotime(urldecode(str_replace('-', '/', $end_date))));
            }
            $data['title'] = 'Advanced Hr Reports - New Hires On-Boarding Between ( ' . date('m-d-Y', strtotime($start_date)) . ' - ' . date('m-d-Y', strtotime($end_date)) . ' )';
            $applicants = $this->reports_model->GetAllApplicantsOnboarding($company_sid, $start_date, $end_date, $keyword, true);
            //echo $this->db->last_query();
            $data['applicants'] = $applicants;
            $data['startdate'] = date('m-d-Y', strtotime($start_date));
            $data['enddate'] = date('m-d-Y', strtotime($end_date));
            $data['is_hired_report'] = true;
            $data['keyword'] = $keyword;

            //
            $companyinfo = getCompanyInfo($company_sid);
            $data['companyName'] = $companyinfo['company_name'];


            //** excel sheet file **//
            if (isset($_POST['submit']) && $_POST['submit'] == 'Export') {
                if (isset($applicants) && sizeof($applicants) > 0) {

                    header('Content-Type: text/csv; charset=utf-8');
                    header('Content-Disposition: attachment; filename=data.csv');
                    $output = fopen('php://output', 'w');

                    fputcsv($output, array($companyinfo['company_name'], '', ''));


                    if (isset($data['is_hired_report']) && $data['is_hired_report'] == true) {
                        fputcsv($output, array('Job Title', 'Applicant Name', 'Hired On'));
                    } else {
                        fputcsv($output, array('Job Title', 'Applicant Name'));
                    }

                    foreach ($applicants as $applicant) {
                        $input = array();
                        $city = '';
                        $state = '';
                        if (isset($applicant['Location_City']) && $applicant['Location_City'] != NULL) {
                            $city = ' - ' . ucfirst($applicant['Location_City']);
                        }
                        if (isset($applicant['Location_State']) && $applicant['Location_State'] != NULL) {
                            $state = ', ' . db_get_state_name($applicant['Location_State'])['state_name'];
                        }
                        $input['Title'] = ($applicant['Title'] != '' ? $applicant['Title'] . $city . $state : 'Job Removed From System');
                        $input['name'] = ucwords($applicant['first_name'] . ' ' . $applicant['last_name']);
                        if (isset($data['is_hired_report']) && $data['is_hired_report'] == true) {
                            // Added on: 27-06-2019
                            $input['date'] = reset_datetime(array('datetime' => $applicant['hired_date'], '_this' => $this));
                            // $input['date'] = date('m-d-Y', strtotime(str_replace('-', '/', $applicant['hired_date'])));
                        }
                        fputcsv($output, $input);
                    }
                    fclose($output);
                    exit;
                } else {
                    $this->session->set_flashdata('message', 'No data found.');
                }
            }
            //** excel sheet file **//

            $this->load->view('main/header', $data);
            $this->load->view('reports/generate_new_hires_onboarding_report');
            $this->load->view('main/footer');
        } else {
            redirect('login', "refresh");
        }
    }

    public function generate_job_views_applicants_report()
    {

        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');

            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'reports');

            if (isset($_POST['job_status'])) {
                $jobStatus = $_POST['job_status'];
            } else {
                $jobStatus = 'all';
            }


            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $data['title'] = 'Job Views and Hires';
            $all_jobs = $this->reports_model->get_all_jobs_views_applicants_count_filter($company_sid, $jobStatus);
            $data['all_jobs'] = $all_jobs;
            $total_views = 0;
            $total_applicants = 0;

            $data['jobstatus'] = $jobStatus;

            foreach ($all_jobs as $job) {
                $total_views += intval($job['views']);
                $total_applicants += intval($job['applicant_count']);
            }

            $data['total_views'] = $total_views;
            $data['total_applicants'] = $total_applicants;

            $companyinfo = getCompanyInfo($company_sid);
            $data['companyName'] = $companyinfo['company_name'];



            //** excel sheet file **//
            if (isset($_POST['submit']) && $_POST['submit'] == 'Export') {
                if (isset($all_jobs) && sizeof($all_jobs) > 0) {

                    header('Content-Type: text/csv; charset=utf-8');
                    header('Content-Disposition: attachment; filename=data.csv');
                    $output = fopen('php://output', 'w');


                    fputcsv($output, array($companyinfo['company_name'], '', '', '', '', ''));

                    fputcsv($output, array('Job Title', 'Created Date', 'Deactivation Date', 'Status', 'Views', 'Applicants'));

                    foreach ($all_jobs as $job) {
                        $state = $city = '';
                        if (isset($job['Location_City']) && $job['Location_City'] != null && $job['Location_City'] != '') $city = ' - ' . ucfirst($job['Location_City']);
                        if (isset($job['Location_State']) && $job['Location_State'] != null && $job['Location_State'] != '') $state = ', ' . db_get_state_name($job['Location_State'])['state_name'];
                        $title = ($job['Title'] != '' ? $job['Title'] . $city . $state : 'Job Removed From System');
                        // $created_date = date('F j, Y, g:i a', strtotime($job['activation_date']));
                        $created_date = reset_datetime(array('datetime' => $job['activation_date'], '_this' => $this));
                        $deactivation_date = ($job['deactivation_date'] != null ? date('F j, Y, g:i a', strtotime($job['activation_date'])) : 'N.A.');
                        $status = $job['active'];
                        $views = $job['views'];
                        $applicant_count = $job['applicant_count'];

                        if ($status == 0) {
                            $status = 'Inactive';
                        } else if ($status == 1) {
                            $status = 'Active';
                        } else if ($status == 2) {
                            $status = 'Archived';
                        }

                        fputcsv($output, array($title, $created_date, $deactivation_date, $status, $views, $applicant_count));
                    }
                    fclose($output);
                    exit;
                } else {
                    $this->session->set_flashdata('message', 'No data found.');
                }
            }
            //** excel sheet file **//

            $this->load->view('main/header', $data);
            $this->load->view('reports/generate_job_views_applicants_report');
            $this->load->view('main/footer');
        } else {
            redirect('login', "refresh");
        }
    }

    public function print_report()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');

            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'reports');

            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];

            $lastQuery = $this->session->userdata('last_query');
            $data = $this->db->query($lastQuery)->result_array();
            $data['myRecords'] = $data;
            $data['title'] = 'Advanced Hr Reports - Print Report';
            $this->load->view('reports/print_report', $data);
        } else {
            redirect('login', "refresh");
        }
    }

    public function ajax_responder()
    {
        if (array_key_exists('perform_action', $_POST)) {
            $perform_action = strtoupper($_POST['perform_action']);
            switch ($perform_action) {
                case 'GETFILECONTENT':
                    break;
                default:
                    break;
            }
        }
    }

    //** applicant referrals **//
    public function generate_applicant_referrals_report()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');

            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'reports');

            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];

            $data['title'] = 'Reference Report';

            //** **//
            $references = $this->reports_model->get_references($company_sid);
            $users = array();

            foreach ($references as $reference) {
                $ref = array();
                $user_sid = $reference['user_sid'];
                foreach ($references as $reference2) {
                    if ($reference2['user_sid'] == $user_sid) {
                        $ref[] = $reference2;
                    }
                }
                $users[$reference['user_name']] = $ref;
            }

            $data['users'] = $users;

            //
            $companyinfo = getCompanyInfo($company_sid);
            $data['companyName'] = $companyinfo['company_name'];

            //** **//

            /** export excel sheet * */
            if (isset($_POST['submit']) && $_POST['submit'] == 'Export') {
                if (isset($users) && sizeof($users) > 0) {

                    header('Content-Type: text/csv; charset=utf-8');
                    header('Content-Disposition: attachment; filename=data.csv');
                    $output = fopen('php://output', 'w');

                    fputcsv($output, array($companyinfo['company_name'], ''));


                    foreach ($users as $user => $references) {
                        fputcsv($output, array($user, ucwords($references[0]['users_type'])));
                        fputcsv($output, array('Reference Title', 'Name', 'Type', 'Relation', 'Email', 'Department', 'Branch', 'Reference Organization'));

                        foreach ($references as $reference) {
                            $input = array();

                            $input['reference_title'] = $reference['reference_title'];
                            $input['reference_name'] = $reference['reference_name'];
                            $input['reference_type'] = $reference['reference_type'];
                            $input['reference_relation'] = $reference['reference_relation'];
                            $input['reference_email'] = $reference['reference_email'];
                            $input['department_name'] = $reference['department_name'];
                            $input['branch_name'] = $reference['branch_name'];
                            $input['organization_name'] = $reference['organization_name'];

                            fputcsv($output, $input);
                        }

                        fputcsv($output, array());
                        fputcsv($output, array());
                    }
                    fclose($output);
                    exit;
                } else {
                    $this->session->set_flashdata('message', 'No data found.');
                }
            }
            /** export excel sheet * */
            $this->load->view('main/header', $data);
            $this->load->view('reports/generate_applicant_referrals_report');
            $this->load->view('main/footer');
        } else {
            redirect('login', "refresh");
        }
    }

    // ** applicant statuses ** //
    public function generate_applicant_status_report($keyword = 'all', $applicant_status = 'all', $start_date = 'all', $end_date = 'all', $job_sid = 'all', $applicant_type = 'all', $page_number = 1)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');

            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'reports');

            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $data['title'] = 'Applicant Status';

            $keyword = urldecode($keyword);
            $applicant_status = urldecode($applicant_status);
            $start_date = urldecode($start_date);
            $end_date = urldecode($end_date);
            $job_sid = urldecode($job_sid);
            $applicant_type = urldecode($applicant_type);

            if ($job_sid != null || $job_sid != 'all') {
                $data['job_sid_array']                                          = explode(',', $job_sid);
            }

            if (!empty($start_date) && $start_date != 'all') {
                $start_date_applied = empty($start_date) ? null : DateTime::createFromFormat('m-d-Y', $start_date)->format('Y-m-d 00:00:00');
            } else {
                $start_date_applied = date('Y-m-d 00:00:00');
            }

            if (!empty($end_date) && $end_date != 'all') {
                $end_date_applied = empty($end_date) ? null : DateTime::createFromFormat('m-d-Y', $end_date)->format('Y-m-d 23:59:59');
            } else {
                $end_date_applied = date('Y-m-d 23:59:59');
            }
            $allJobs = $this->reports_model->GetAllJobsCompanySpecific($company_sid);

            $jobOptions = array();
            foreach ($allJobs as $job) {
                $state = $city = '';
                if (isset($job['Location_City']) && $job['Location_City'] != null && $job['Location_City'] != '') $city = ' - ' . ucfirst($job['Location_City']);
                if (isset($job['Location_State']) && $job['Location_State'] != null && $job['Location_State'] != '') $state = ', ' . db_get_state_name($job['Location_State'])['state_name'];
                $active = ' (In Active) ';

                if ($job['active']) {
                    $active = ' (Active) ';
                }

                $jobOptions[$job['sid']] = $job['Title'] . $city . $state . $active;
            }
            $data['jobOptions'] = $jobOptions;
            //            $applicant_types = array();
            //            $applicant_types[] = 'Applicant';
            //            $applicant_types[] = 'Talent Network';
            //            $applicant_types[] = 'Manual Candidate';
            //            $applicant_types[] = 'Job Fair';
            //            $applicant_types[] = 'Re-Assigned Candidates';
            $applicant_types = explode(',', APPLICANT_TYPE_ATS);
            $data['applicant_types'] = $applicant_types;

            //-----------------------------------Pagination Starts----------------------------//
            $per_page = PAGINATION_RECORDS_PER_PAGE;
            $offset = 0;
            if ($page_number > 1) {
                $offset = ($page_number - 1) * $per_page;
            }

            $total_records = $this->reports_model->get_applicants($company_sid, $keyword, $job_sid, $applicant_type, $applicant_status, $start_date_applied, $end_date_applied, true);

            $this->load->library('pagination');

            $pagination_base = base_url('reports/generate_applicant_status_report') . '/' . urlencode($keyword) . '/' . urlencode($applicant_status) . '/' . urlencode($start_date) . '/' . urlencode($end_date) . '/' . urlencode($job_sid) . '/' . urlencode($applicant_type);

            $config = array();
            $config["base_url"] = $pagination_base;
            $config["total_rows"] = $total_records;
            $config["per_page"] = $per_page;
            $config["uri_segment"] = 9;
            $config["num_links"] = 8;
            $config["use_page_numbers"] = true;
            $config['full_tag_open'] = '<nav class="hr-pagination"><ul>';
            $config['full_tag_close'] = '</ul></nav><!--pagination-->';
            $config['first_link'] = '<i class="fa fa-angle-double-left"></i>';
            $config['first_tag_open'] = '<li class="prev page">';
            $config['first_tag_close'] = '</li>';
            $config['last_link'] = '<i class="fa fa-angle-double-right"></i>';
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
            $data["page_links"] = $this->pagination->create_links();

            $data['current_page'] = $page_number;
            $data['from_records'] = $offset == 0 ? 1 : $offset;
            $data['to_records'] = $total_records < $per_page ? $total_records : $offset + $per_page;

            $data['have_status'] = $this->reports_model->check_company_status($company_sid);

            $applicants = $this->reports_model->get_applicants($company_sid, $keyword, $job_sid, $applicant_type, $applicant_status, $start_date_applied, $end_date_applied, false, $per_page, $offset);



            //echo $this->db->last_query();
            $data['applicants'] = $applicants;
            $data['applicants_count'] = $total_records;

            $company_statuses = $this->reports_model->get_company_statuses($company_sid);
            $data['applicant_statuses'] = $company_statuses;

            //
            $companyinfo = getCompanyInfo($company_sid);
            $data['companyName'] = $companyinfo['company_name'];


            // **** //
            //** excel sheet file **//
            if (isset($_POST['submit']) && $_POST['submit'] == 'Export') {
                $applicants = $this->reports_model->get_applicants($company_sid, $keyword, $job_sid, $applicant_type, $applicant_status, $start_date_applied, $end_date_applied, false);

                if (isset($applicants) && sizeof($applicants) > 0) {

                    header('Content-Type: text/csv; charset=utf-8');
                    header('Content-Disposition: attachment; filename=data.csv');
                    $output = fopen('php://output', 'w');

                    fputcsv($output, array($companyinfo['company_name'], '', '', '', ''));


                    fputcsv($output, array('Application Date', 'Applicant Name', 'Job Title', 'Email', 'Status'));

                    foreach ($applicants as $applicant) {
                        $state = $city = '';
                        if (isset($applicant['Location_City']) && $applicant['Location_City'] != null && $applicant['Location_City'] != '') $city = ' - ' . ucfirst($applicant['Location_City']);
                        if (isset($applicant['Location_State']) && $applicant['Location_State'] != null && $applicant['Location_State'] != '') $state = ', ' . db_get_state_name($applicant['Location_State'])['state_name'];
                        $input = array();

                        // $input['date_applied'] = date('m-d-Y', strtotime(str_replace('-', '/', $applicant['date_applied'])));
                        $input['date_applied'] = reset_datetime(array('datetime' => $applicant['date_applied'], '_this' => $this));
                        $input['name'] = ucwords($applicant['first_name'] . ' ' . $applicant['last_name']);
                        $input['Title'] = $applicant['Title'] . $city . $state;
                        $input['email'] = $applicant['email'];
                        $input['status'] = ucwords($applicant['status']);

                        fputcsv($output, $input);
                    }
                    fclose($output);
                    exit;
                } else {
                    $this->session->set_flashdata('message', 'No data found.');
                }
            }
            //** excel sheet file **//

            $this->load->view('main/header', $data);
            $this->load->view('reports/generate_applicant_status_report');
            $this->load->view('main/footer');
        } else {
            redirect('login', "refresh");
        }
    }

    public function generate_candidate_offers_report($start_date = null, $end_date = null, $keyword = 'all')
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');

            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'reports');

            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $data['title'] = 'Applicant Offers';

            $keyword = urldecode($keyword);
            // **** //
            if ($start_date == null || $start_date == 'beginning-of-time') {
                $start_date = date('Y-m-d 00:00:00');
            } else {
                $start_date = date('Y-m-d 00:00:00', strtotime(urldecode(str_replace('-', '/', $start_date))));
            }

            if ($end_date == null || $end_date == 'end-of-days') {
                $end_date = date('Y-m-d 23:59:59');
            } else {
                $end_date = date('Y-m-d 23:59:59', strtotime(urldecode(str_replace('-', '/', $end_date))));
            }

            $data['candidates'] = $this->reports_model->get_candidate_offers($company_sid, $start_date, $end_date, $keyword);

            $data['startdate'] = date('m-d-Y', strtotime($start_date));
            $data['enddate'] = date('m-d-Y', strtotime($end_date));
            $data['keyword'] = $keyword;

            //
            $companyinfo = getCompanyInfo($company_sid);
            $data['companyName'] = $companyinfo['company_name'];


            // **** //
            //** excel sheet file **//
            if (isset($_POST['submit']) && $_POST['submit'] == 'Export') {
                if (isset($data['candidates']) && sizeof($data['candidates']) > 0) {

                    header('Content-Type: text/csv; charset=utf-8');
                    header('Content-Disposition: attachment; filename=data.csv');
                    $output = fopen('php://output', 'w');

                    fputcsv($output, array($companyinfo['company_name'], '', '', ''));


                    fputcsv($output, array('Offer Date', 'Job Title', 'Applicant Name', 'Email', 'Employee Type'));

                    foreach ($data['candidates'] as $candidate) {
                        $state = $city = '';
                        if (isset($candidate['Location_City']) && $candidate['Location_City'] != null && $candidate['Location_City'] != '') $city = ' - ' . ucfirst($candidate['Location_City']);
                        if (isset($candidate['Location_State']) && $candidate['Location_State'] != null && $candidate['Location_State'] != '') $state = ', ' . db_get_state_name($candidate['Location_State'])['state_name'];
                        $input = array();

                        // $input['offer_date'] = date('m-d-Y', strtotime(str_replace('-', '/', $candidate['registration_date'])));
                        $input['offer_date'] = reset_datetime(array('datetime' => $candidate['registration_date'], '_this' => $this));
                        $input['job_title'] = $candidate['job_title'] . $city . $state;
                        $input['applicant_name'] = ucwords($candidate['first_name'] . ' ' . $candidate['last_name']);
                        $input['email'] = $candidate['email'];
                        $input['employee_type'] = ucwords($candidate['access_level']);

                        fputcsv($output, $input);
                    }
                    fclose($output);
                    exit;
                } else {
                    $this->session->set_flashdata('message', 'No data found.');
                }
            }
            //** excel sheet file **//

            $this->load->view('main/header', $data);
            $this->load->view('reports/generate_candidate_offers_report');
            $this->load->view('main/footer');
        } else {
            redirect('login', "refresh");
        }
    }

    public function applicant_origination_tracker_report($start_date = null, $end_date = null)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');

            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'reports');

            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $data['title'] = 'Applicant Origination Tracker Report';

            // **** //
            if ($start_date == null || $start_date == 'beginning-of-time') {
                $start_date = date('Y-m-d 00:00:00');
            } else {
                $start_date = date('Y-m-d 00:00:00', strtotime(urldecode(str_replace('-', '/', $start_date))));
            }

            if ($end_date == null || $end_date == 'end-of-days') {
                $end_date = date('Y-m-d 23:59:59');
            } else {
                $end_date = date('Y-m-d 23:59:59', strtotime(urldecode(str_replace('-', '/', $end_date))));
            }

            //** code here **//
            $applicant_sources = array();
            $applicant_sources[] = 'automoto_social';
            $applicant_sources[] = 'career_website';
            $applicant_sources[] = 'glassdoor';
            $applicant_sources[] = 'indeed';
            $applicant_sources[] = 'juju';

            $data['companies_applicants_by_source'] = $this->get_company_applicants_by_source($company_sid, $applicant_sources, $start_date, $end_date);
            //** code here **//
            $data['startdate'] = date('m-d-Y', strtotime($start_date));
            $data['enddate'] = date('m-d-Y', strtotime($end_date));

            //******* data exports ********//
            if (isset($_POST['submit']) && $_POST['submit'] == 'Export') {
                if (isset($data['companies_applicants_by_source']) && sizeof($data['companies_applicants_by_source']) > 0) {

                    header('Content-Type: text/csv; charset=utf-8');
                    header('Content-Disposition: attachment; filename=data.csv');
                    $output = fopen('php://output', 'w');

                    $company_applicants_by_source = $data['companies_applicants_by_source'];
                    $company_info = $company_applicants_by_source['company_info'];
                    $applicants_by_source = $company_applicants_by_source['applicants_by_source'];
                    fputcsv($output, array('Company Name : ' . ucwords($company_info['CompanyName'])));
                    fputcsv($output, array());
                    if (!empty($applicants_by_source)) {
                        foreach ($applicants_by_source as $key => $source_applicants) {
                            fputcsv($output, array(ucwords(str_replace('_', ' ', $key))));
                            fputcsv($output, array('Total : ' . count($source_applicants) . ' Applicant(s)'));
                            fputcsv($output, array('Application Date', 'Applicant Name', 'Job Title', 'Email'));
                            if (!empty($source_applicants)) {
                                foreach ($source_applicants as $applicant) {
                                    $input = array();
                                    $input['date_applied'] = convert_date_to_frontend_format($applicant['date_applied']);
                                    $input['name'] = ucwords($applicant['first_name'] . ' ' . $applicant['last_name']);
                                    $input['job_title'] = ucwords($applicant['job_title']);
                                    $input['email'] = ucwords($applicant['email']);
                                    fputcsv($output, $input);
                                }
                            } else {
                                fputcsv($output, array('No Applicants'));
                            }
                            fputcsv($output, array());
                        }
                    } else {
                        fputcsv($output, array('No Applicants'));
                    }
                    fputcsv($output, array());
                    fclose($output);
                    exit;
                } else {
                    $this->session->set_flashdata('message', 'No data found.');
                }
            }
            //******* data exports ********//

            $this->load->view('main/header', $data);
            $this->load->view('reports/generate_applicant_origination_tracker_report');
            $this->load->view('main/footer');
        } else {
            redirect('login', "refresh");
        }
    }

    private function get_company_applicants_by_source($company_sid, $applicant_sources = array(), $start_date, $end_date)
    {

        $applicants_by_source = array();

        foreach ($applicant_sources as $applicant_source) {
            $applicants = $this->reports_model->get_applicants_by_source($company_sid, $applicant_source, $start_date, $end_date);
            $applicants_by_source[$applicant_source] = $applicants;
        }

        $company_info = get_company_details($company_sid);
        //$applicants = $this->reports_model->get_applicants_by_source($company_sid, null, $start_date, $end_date);
        //$applicants_by_source['career_website'] = array_merge($applicants, $applicants_by_source['career_website']);

        $company_data = array();
        $company_data['company_info'] = $company_info;
        $company_data['applicants_by_source'] = $applicants_by_source;

        return $company_data;
    }

    public function applicant_interview_scores_report($keyword = 'all', $start_date = 'all', $end_date = 'all', $job_sid = 'all', $applicant_status = 'all', $applicant_type = 'all', $page_number = 1)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');

            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'reports');

            $company_sid = $data['session']['company_detail']['sid'];
            $data['title'] = 'Advanced Hr Reports - Applicant Interview Scores';

            $allJobs = $this->reports_model->GetAllJobsCompanySpecific($company_sid);
            $jobOptions = array();

            foreach ($allJobs as $job) {
                $state = $city = '';
                if (isset($job['Location_City']) && $job['Location_City'] != null && $job['Location_City'] != '') $city = ' - ' . ucfirst($job['Location_City']);
                if (isset($job['Location_State']) && $job['Location_State'] != null && $job['Location_State'] != '') $state = ', ' . db_get_state_name($job['Location_State'])['state_name'];
                $active = ' (In Active) ';

                if ($job['active']) {
                    $active = ' (Active) ';
                }

                $jobOptions[$job['sid']] = $job['Title'] . $city . $state . $active;
            }
            $data['jobOptions'] = $jobOptions;
            //            $applicant_types = array();
            //            $applicant_types[] = 'Applicant';
            //            $applicant_types[] = 'Talent Network';
            //            $applicant_types[] = 'Manual Candidate';
            //            $applicant_types[] = 'Job Fair';
            //            $applicant_types[] = 'Re-Assigned Candidates';
            $applicant_types = explode(',', APPLICANT_TYPE_ATS);
            $data['applicant_types'] = $applicant_types;

            $keyword = urldecode($keyword);
            $job_sid = urldecode($job_sid);
            $applicant_type = urldecode($applicant_type);
            $applicant_status = urldecode($applicant_status);
            $start_date = urldecode($start_date);
            $end_date = urldecode($end_date);
            $data['title'] = 'Advanced Hr Reports - Applicants Between ( ' . date('m-d-Y', strtotime($start_date)) . ' - ' . date('m-d-Y', strtotime($end_date)) . ' )';
            $data['startdate'] = date('m-d-Y', strtotime($start_date));
            $data['enddate'] = date('m-d-Y', strtotime($end_date));
            $data['keyword'] = $keyword;
            $data['job_sid'] = $job_sid;
            $data['applicant_type'] = $applicant_type;
            $data['applicant_status'] = $applicant_status;

            if ($job_sid != null || $job_sid != 'all') {
                $data['job_sid_array']                                          = explode(',', $job_sid);
            }

            if (!empty($start_date) && $start_date != 'all') {
                $start_date_applied = empty($start_date) ? null : DateTime::createFromFormat('m-d-Y', $start_date)->format('Y-m-d 00:00:00');
            } else {
                $start_date_applied = date('Y-m-d 00:00:00');
            }

            if (!empty($end_date) && $end_date != 'all') {
                $end_date_applied = empty($end_date) ? null : DateTime::createFromFormat('m-d-Y', $end_date)->format('Y-m-d 23:59:59');
            } else {
                $end_date_applied = date('Y-m-d 23:59:59');
            }

            $per_page = PAGINATION_RECORDS_PER_PAGE;
            //$per_page = 2;
            //$page_number = $this->input->get('page_number');
            $offset = 0;

            if ($page_number > 1) {
                $offset = ($page_number - 1) * $per_page;
            }

            $total_records = $this->reports_model->get_applicant_interview_scores($company_sid, $keyword, $start_date_applied, $end_date_applied, true, $job_sid, $applicant_type, $applicant_status);
            $this->load->library('pagination');
            $pagination_base = base_url('reports/applicant_interview_scores_report') . '/' . urlencode($keyword) . '/' . urlencode($start_date) . '/' . urlencode($end_date) . '/' . urlencode($job_sid) . '/' . urlencode($applicant_status) . '/' . urlencode($applicant_type);
            //echo $pagination_base;
            $config = array();
            $config["base_url"] = $pagination_base;
            $config["total_rows"] = $total_records;
            $config["per_page"] = $per_page;
            $config["uri_segment"] = 9;
            $config["num_links"] = 8;
            $config["use_page_numbers"] = true;
            $config['full_tag_open'] = '<nav class="hr-pagination"><ul>';
            $config['full_tag_close'] = '</ul></nav><!--pagination-->';
            $config['first_link'] = '<i class="fa fa-angle-double-left"></i>';
            $config['first_tag_open'] = '<li class="prev page">';
            $config['first_tag_close'] = '</li>';
            $config['last_link'] = '<i class="fa fa-angle-double-right"></i>';
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

            //$config['page_query_string'] = true;
            //$config['reuse_query_string'] = true;
            //$config['query_string_segment'] = 'page_number';

            $this->pagination->initialize($config);
            $data['page_links'] = $this->pagination->create_links();
            $data['current_page'] = $page_number;
            $data['from_records'] = $offset == 0 ? 1 : $offset;
            $data['to_records'] = $total_records < $per_page ? $total_records : $offset + $per_page;
            $data['applicants_count'] = $total_records;

            $applicant_statuses = $this->reports_model->get_company_statuses($company_sid);
            $data['applicant_statuses'] = $applicant_statuses;

            $data['companies_applicant_scores'] = $this->reports_model->get_applicant_interview_scores($company_sid, $keyword, $start_date_applied, $end_date_applied, false, $job_sid, $applicant_type, $applicant_status, $per_page, $offset);

            //
            $companyinfo = getCompanyInfo($company_sid);
            $data['companyName'] = $companyinfo['company_name'];

            if (isset($_POST['submit']) && $_POST['submit'] == 'Export') {
                if (isset($data['companies_applicant_scores']) && sizeof($data['companies_applicant_scores']) > 0) {

                    header('Content-Type: text/csv; charset=utf-8');
                    header('Content-Disposition: attachment; filename=data.csv');
                    $output = fopen('php://output', 'w');

                    fputcsv($output, array($companyinfo['company_name'], '', '', ''));

                    fputcsv($output, array('Total : ' . count($data['companies_applicant_scores']) . ' Applicant Interview(s)'));
                    fputcsv($output, array('Interview Date', 'Applicant', 'Conducted By', 'For Position', 'Applicant Evaluation Score', 'Job Relevancy Evaluation Score', 'Applicant Overall Score', 'Job Relevancy Overall Score', 'Star Rating'));

                    if (!empty($data['companies_applicant_scores'])) {
                        foreach ($data['companies_applicant_scores'] as $applicant_score) {
                            $state = $city = '';
                            if (isset($applicant_score['Location_City']) && $applicant_score['Location_City'] != null && $applicant_score['Location_City'] != '') $city = ' - ' . ucfirst($applicant_score['Location_City']);
                            if (isset($applicant_score['Location_State']) && $applicant_score['Location_State'] != null && $applicant_score['Location_State'] != '') $state = ', ' . db_get_state_name($applicant_score['Location_State'])['state_name'];
                            $input = array();
                            // $input['created_date'] = convert_date_to_frontend_format($applicant_score['created_date']);
                            $input['created_date'] = reset_datetime(array('datetime' => $applicant_score['created_date'], '_this' => $this));
                            $input['applicant'] = ucwords($applicant_score['first_name'] . ' ' . $applicant_score['last_name']);
                            $input['conducted_by'] = ucwords($applicant_score['conducted_by_first_name'] . ' ' . $applicant_score['conducted_by_last_name']) . ' ( ' . ucwords($applicant_score['conducted_by_job_title']) . ' ) ';
                            $input['for_position'] = ucwords($applicant_score['job_title'] . $city . $state);
                            $input['applicant_evaluation_score'] = $applicant_score['candidate_score'] . ' Point(s) ( Out of 100 )';
                            $input['job_relevancy_evaluation_score'] = $applicant_score['job_relevancy_score'] . ' Point(s) ( Out of 100 )';
                            $input['applicant_overall_score'] = ($applicant_score['candidate_overall_score'] * 10) . ' Point(s) ( Out of 100 )';
                            $input['job_relevancy_overall_score'] = ($applicant_score['job_relevancy_overall_score'] * 10) . ' Point(s) ( Out of 100 )';
                            $input['star_rating'] = $applicant_score['star_rating'] . '/5';
                            fputcsv($output, $input);
                        }
                    } else {
                        fputcsv($output, array('No Applicants Found'));
                    }

                    fclose($output);
                    exit;
                } else {
                    $this->session->set_flashdata('message', 'No data found.');
                }
            }

            $this->load->view('main/header', $data);
            $this->load->view('reports/generate_applicant_interview_scores_report');
            $this->load->view('main/footer');
        } else {
            redirect('login', "refresh");
        }
    }

    public function applicant_source_report($keyword = 'all', $job_sid = 'all', $applicant_type = 'all', $start_date = 'all', $end_date = 'all', $source = 'all', $applicant_status = 'all', $page_number = 1)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'reports');

            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];

            $data['title'] = 'Advanced Hr Reports - Applicant Source Report';
            $data['company_sid'] = $company_sid;

            //            $applicant_types = array();
            //            $applicant_types[] = 'Applicant';
            //            $applicant_types[] = 'Talent Network';
            //            $applicant_types[] = 'Manual Candidate';
            //            $applicant_types[] = 'Job Fair';
            //            $applicant_types[] = 'Re-Assigned Candidates';
            $applicant_types = explode(',', APPLICANT_TYPE_ATS);
            $data['applicant_types'] = $applicant_types;

            $keyword = trim(urldecode($keyword));
            $job_sid = trim(urldecode($job_sid));
            $applicant_type = trim(urldecode($applicant_type));
            $start_date = trim(urldecode($start_date));
            $end_date = trim(urldecode($end_date));
            $applicant_status = trim(urldecode($applicant_status));

            if ($job_sid != null || $job_sid != 'all') {
                $data['job_sid_array']                                          = explode(',', $job_sid);
            }

            if (!empty($start_date) && $start_date != 'all') {
                $start_date_applied = empty($start_date) ? null : DateTime::createFromFormat('m-d-Y', $start_date)->format('Y-m-d 00:00:00');
            } else {
                $start_date_applied = date('Y-m-d 00:00:00');
            }

            if (!empty($end_date) && $end_date != 'all') {
                $end_date_applied = empty($end_date) ? null : DateTime::createFromFormat('m-d-Y', $end_date)->format('Y-m-d 23:59:59');
            } else {
                $end_date_applied = date('Y-m-d 23:59:59');
            }

            $data['flag'] = true;

            $per_page = PAGINATION_RECORDS_PER_PAGE;
            $offset = 0;
            if ($page_number > 1) {
                $offset = ($page_number - 1) * $per_page;
            }

            /* if (is_admin($employer_sid)) { */

            $allJobs = $this->reports_model->GetAllJobsCompanySpecific($company_sid);
            $applicants = $this->reports_model->GetSourceReportAllApplicantsCompanySpecific($company_sid, $keyword, $job_sid, $applicant_type, $start_date_applied, $end_date_applied, $per_page, $offset, true, false, $source, $applicant_status);
            $applicants_count = $this->reports_model->GetSourceReportAllApplicantsCompanySpecific($company_sid, $keyword, $job_sid, $applicant_type, $start_date_applied, $end_date_applied, null, null, false, true, $source, $applicant_status);

            /* } else {
              $allJobs = $this->reports_model->GetAllJobsCompanyAndEmployerSpecific($company_sid, $employer_sid);
              $applicants = $this->reports_model->GetSourceReportAllApplicants($company_sid, $employer_sid, $keyword, $job_sid, $applicant_type, $start_date_applied, $end_date_applied);
              } */
            foreach ($allJobs as $job) {
                $active = ' (In Active) ';

                if ($job['active']) {
                    $active = ' (Active) ';
                }

                $jobOptions[$job['sid']] = $job['Title'] . $active;
            }

            $data['jobOptions'] = $jobOptions;
            $data['applicants'] = $applicants;
            $data['flag'] = true;

            $company_statuses = $this->reports_model->get_company_statuses($company_sid);
            $data['applicant_statuses'] = $company_statuses;

            $this->load->library('pagination');
            $pagination_base = base_url('reports/applicant_source_report') . '/' . urlencode($keyword) . '/' . urlencode($job_sid) . '/' . urlencode($applicant_type) . '/' . urlencode($start_date) . '/' . urlencode($end_date) . '/' . urlencode($source) . '/' . urlencode($applicant_status);

            $config = array();
            $config["base_url"] = $pagination_base;
            $config["total_rows"] = $applicants_count;
            $config["per_page"] = $per_page;
            $config["uri_segment"] = 10;
            $config["num_links"] = 8;
            $config["use_page_numbers"] = true;
            $config['full_tag_open'] = '<nav class="hr-pagination"><ul>';
            $config['full_tag_close'] = '</ul></nav><!--pagination-->';
            $config['first_link'] = '<i class="fa fa-angle-double-left"></i>';
            $config['first_tag_open'] = '<li class="prev page">';
            $config['first_tag_close'] = '</li>';
            $config['last_link'] = '<i class="fa fa-angle-double-right"></i>';
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
            $data["page_links"] = $this->pagination->create_links();

            $data['current_page'] = $page_number;
            $data['from_records'] = $offset == 0 ? 1 : $offset;
            $data['to_records'] = $applicants_count < $per_page ? $applicants_count : $offset + $per_page;
            $data['applicants_count'] = $applicants_count;

            //
            $companyinfo = getCompanyInfo($company_sid);
            $data['companyName'] = $companyinfo['company_name'];

            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim');
            if ($this->form_validation->run() == false) {
                $this->load->view('main/header', $data);
                $this->load->view('reports/generate_applicant_source_report');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');
                switch ($perform_action) {
                    case 'export_csv':
                        $applicants = $this->reports_model->GetSourceReportAllApplicantsCompanySpecific($company_sid, $keyword, $job_sid, $applicant_type, $start_date_applied, $end_date_applied, $applicants_count, 0, true, false);

                        if (!empty($applicants)) {

                            header('Content-Type: text/csv; charset=utf-8');
                            header('Content-Disposition: attachment; filename=data.csv');
                            $output = fopen('php://output', 'w');

                            fputcsv($output, array($companyinfo['company_name'], '', '', '', '', '', ''));

                            fputcsv($output, array('Date Applied', 'Applicant Type', 'First Name', 'Last Name', 'Job Title', 'IP Address', 'Applicant Source'));

                            foreach ($applicants as $applicant) {
                                $input = array();

                                $input[] = reset_datetime(array('datetime' => $applicant['date_applied'], '_this' => $this));
                                // $input[] = $applicant['date_applied'];
                                $input[] = $applicant['applicant_type'];
                                $input[] = $applicant['first_name'];
                                $input[] = $applicant['last_name'];
                                $input[] = $applicant['Title'];
                                $input[] = $applicant['ip_address'];
                                $input[] = $applicant['applicant_source'];

                                fputcsv($output, $input);
                            }
                            fclose($output);
                            exit;
                            //                            redirect(current_url(), 'refresh');
                        }
                        break;
                }
            }
        } else {
            redirect('login', "refresh");
        }
    }

    public function employee_monthly_attendance_report($employer_sid = null, $year = null, $month = null)
    {
        if ($this->session->userdata('logged_in')) {
            if ($this->form_validation->run() == false) {
                $data['session'] = $this->session->userdata('logged_in');

                $security_sid = $data['session']['employer_detail']['sid'];
                $security_details = db_get_access_level_details($security_sid);
                $data['security_details'] = $security_details;
                //check_access_permissions($security_details, 'dashboard', 'application_tracking');

                $company_sid = $data["session"]["company_detail"]["sid"];

                if ($employer_sid == null || $employer_sid == 0) {
                    $employer_sid = $data["session"]["employer_detail"]["sid"];
                }


                $data['title'] = "Employee Monthly Attendance Report";



                $year = ($year == null ? date('Y') : $year);
                $month = ($month == null ? date('m') : $month);


                $start_unix_date = mktime(0, 0, 0, $month, 1, $year);
                $end_unix_date = mktime(23, 59, 59, $month, date('t', $start_unix_date), $year);

                $start_date = date('Y-m-d H:i:s', $start_unix_date);
                $end_date = date('Y-m-d H:i:s', $end_unix_date);



                $attendances = $this->attendance_model->get_attendance_register($company_sid, $employer_sid, $start_date, $end_date, 'clock_out', true);


                $grand_total_minutes = 0;
                $grand_total_hours = 0;
                if (!empty($attendances)) {
                    foreach ($attendances as $key => $attendance) {
                        $total_hours = $attendance['hours_after_breaks'];
                        $total_minutes = $attendance['minutes_after_breaks'];

                        $hours_to_minutes = $total_hours * 60;

                        $total_minutes = $total_minutes + $hours_to_minutes;

                        $grand_total_minutes = $grand_total_minutes + $total_minutes;
                    }
                }

                $grand_total_hours = floor($grand_total_minutes / 60);

                $remainder = $grand_total_minutes % 60;

                $data['grand_total_hours'] = $grand_total_hours;
                $data['grand_total_minutes'] = $remainder;


                $data['attendances'] = $attendances;



                $employees = $this->attendance_model->get_employees($company_sid);
                $data['employees'] = $employees;

                $employee_detail = $this->attendance_model->get_employee_information($company_sid, $employer_sid);
                $data['employee_details'] = $employee_detail;

                $months = array('', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
                $data['months'] = $months;
                $data['subtitle'] = "Employee Attendance For the Month of " . $months[intval($month)] . ", " . $year;

                //load views
                $this->load->view('main/header', $data);
                $this->load->view('reports/employee_monthly_attendance_report');
                $this->load->view('main/footer');
            } else {
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function employee_weekly_attendance_report($employer_sid = null, $year = null, $week = null)
    {
        if ($this->session->userdata('logged_in')) {
            if ($this->form_validation->run() == false) {
                $data['session'] = $this->session->userdata('logged_in');

                $security_sid = $data['session']['employer_detail']['sid'];
                $security_details = db_get_access_level_details($security_sid);
                $data['security_details'] = $security_details;
                //check_access_permissions($security_details, 'dashboard', 'application_tracking');

                $company_sid = $data["session"]["company_detail"]["sid"];

                if ($employer_sid == null || $employer_sid == 0) {
                    $employer_sid = $data["session"]["employer_detail"]["sid"];
                }


                $data['title'] = "Employee Weekly Attendance Report";


                $company_timezone = $data['session']['portal_detail']['company_timezone'];
                date_default_timezone_set($company_timezone);

                $year = ($year == null ? date('Y') : $year);
                $week = ($week == null ? date('W') : $week);


                $week_start_date = (new DateTime())->setISODate($year, $week);
                $week_end_date = (new DateTime())->setISODate($year, $week, 7);


                $month = $week_start_date->format('m');


                $start_unix_date = mktime(0, 0, 0, $week_start_date->format('m'), $week_start_date->format('d'), $week_start_date->format('Y'));
                $end_unix_date = mktime(23, 59, 59, $week_end_date->format('m'), $week_end_date->format('d'), $week_end_date->format('Y'));

                $start_date = date('Y-m-d H:i:s', $start_unix_date);
                $end_date = date('Y-m-d H:i:s', $end_unix_date);



                $attendances = $this->attendance_model->get_attendance_register($company_sid, $employer_sid, $start_date, $end_date, 'clock_out', true);


                $grand_total_minutes = 0;
                $grand_total_hours = 0;
                if (!empty($attendances)) {
                    foreach ($attendances as $key => $attendance) {
                        $total_hours = $attendance['hours_after_breaks'];
                        $total_minutes = $attendance['minutes_after_breaks'];

                        $hours_to_minutes = $total_hours * 60;

                        $total_minutes = $total_minutes + $hours_to_minutes;

                        $grand_total_minutes = $grand_total_minutes + $total_minutes;
                    }
                }

                $grand_total_hours = floor($grand_total_minutes / 60);

                $remainder = $grand_total_minutes % 60;

                $data['grand_total_hours'] = $grand_total_hours;
                $data['grand_total_minutes'] = $remainder;


                $data['attendances'] = $attendances;



                $employees = $this->attendance_model->get_employees($company_sid);
                $data['employees'] = $employees;

                $employee_detail = $this->attendance_model->get_employee_information($company_sid, $employer_sid);
                $data['employee_details'] = $employee_detail;

                $months = array('', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
                $data['months'] = $months;
                $data['subtitle'] = "Employee Attendance For the Month of " . $months[intval($month)] . ", " . $year;

                $data['week'] = $week;
                $data['week_start'] = $week_start_date->format('m/d/Y');
                $data['week_end'] = $week_end_date->format('m/d/Y');

                $data['subtitle'] = 'Attendance Register for Week ' . $week . ' of Year ' . $year . ' ( ' . $data['week_start'] . ' - ' . $data['week_end'] . ' ) ';

                //load views
                $this->load->view('main/header', $data);
                $this->load->view('reports/employee_weekly_attendance_report');
                $this->load->view('main/footer');
            } else {
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function generate_job_fair_report($keyword = 'all', $start_date = 'all', $end_date = 'all', $page_number = 1)
    {
        if ($this->session->userdata('logged_in')) {
            if ($this->form_validation->run() == false) {
                $data['session'] = $this->session->userdata('logged_in');

                $security_sid = $data['session']['employer_detail']['sid'];
                $security_details = db_get_access_level_details($security_sid);
                $data['security_details'] = $security_details;
                $company_sid = $data["session"]["company_detail"]["sid"];
                $keyword = urldecode($keyword);
                $start_date = urldecode($start_date);
                $end_date = urldecode($end_date);

                $data['title'] = "Employee Weekly Attendance Report";

                if (!empty($start_date) && $start_date != 'all') {
                    $start_date_applied = empty($start_date) ? null : DateTime::createFromFormat('m-d-Y', $start_date)->format('Y-m-d 00:00:00');
                } else {
                    $start_date_applied = date('Y-m-01 00:00:00');
                }

                if (!empty($end_date) && $end_date != 'all') {
                    $end_date_applied = empty($end_date) ? null : DateTime::createFromFormat('m-d-Y', $end_date)->format('Y-m-d 23:59:59');
                } else {
                    $end_date_applied = date('Y-m-d 23:59:59');
                }

                $per_page = PAGINATION_RECORDS_PER_PAGE;
                $offset = 0;

                if ($page_number > 1) {
                    $offset = ($page_number - 1) * $per_page;
                }

                $total_records = $this->reports_model->get_job_fairs($company_sid, $keyword, $start_date_applied, $end_date_applied, true);
                $this->load->library('pagination');
                $pagination_base = base_url('reports/generate_job_fair_report') . '/' . urlencode($keyword) . '/' . urlencode($start_date) . '/' . urlencode($end_date);
                //echo $pagination_base;
                $config = array();
                $config["base_url"] = $pagination_base;
                $config["total_rows"] = $total_records;
                $config["per_page"] = $per_page;
                $config["uri_segment"] = 6;
                $config["num_links"] = 8;
                $config["use_page_numbers"] = true;
                $config['full_tag_open'] = '<nav class="hr-pagination"><ul>';
                $config['full_tag_close'] = '</ul></nav><!--pagination-->';
                $config['first_link'] = '<i class="fa fa-angle-double-left"></i>';
                $config['first_tag_open'] = '<li class="prev page">';
                $config['first_tag_close'] = '</li>';
                $config['last_link'] = '<i class="fa fa-angle-double-right"></i>';
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
                $data['page_links'] = $this->pagination->create_links();
                $data['current_page'] = $page_number;
                $data['from_records'] = $offset == 0 ? 1 : $offset;
                $data['to_records'] = $total_records < $per_page ? $total_records : $offset + $per_page;

                //-----------------------------------Pagination Ends-----------------------------//
                $applicants = $this->reports_model->get_job_fairs($company_sid, $keyword, $start_date_applied, $end_date_applied, false, $per_page, $offset);
                $data['applicants'] = $applicants;
                $data['applicants_count'] = $total_records;

                //                echo '<pre>';
                //                print_r(unserialize($applicants[0]['talent_and_fair_data']));
                // ** export file sheet ** //
                if (isset($_POST['submit']) && $_POST['submit'] == 'Export') {
                    $myRecords = $this->reports_model->get_job_fairs($company_sid, $keyword, $start_date_applied, $end_date_applied, false);
                    if (isset($myRecords) && sizeof($myRecords) > 0) {
                        header('Content-Type: text/csv; charset=utf-8');
                        header('Content-Disposition: attachment; filename=Job_Fair.csv');
                        $output = fopen('php://output', 'w');
                        $myColumns = array(
                            'Title',
                            'first_name',
                            'last_name',
                            'email',
                            'phone_number',
                            'date_applied',
                            'questionnaire'
                        );
                        $column_info = $this->reports_model->GetColumnsInformation('portal_job_applications');
                        $cols = array();

                        foreach ($myColumns as $col) {
                            if ($col == 'Title') {
                                $cols[] = 'Job Title';
                            } else {
                                $cols[] = ucwords(str_replace('_', ' ', $col));
                            }
                        }
                        fputcsv($output, $cols);

                        foreach ($myRecords as $applicant) {
                            $input = array();

                            foreach ($myColumns as $myColumn) {
                                if ($myColumn != 'questionnaire') {
                                    if ($myColumn != 'Title') {
                                        $columnDetail = $column_info[$myColumn];
                                        $columnType = $columnDetail['type'];
                                        if ($columnType == 'datetime') {
                                            $input[$myColumn] = reset_datetime(array('datetime' => $applicant[$myColumn], '_this' => $this));
                                        } else {
                                            $input[$myColumn] = $applicant[$myColumn];
                                        }
                                    } else {
                                        $input[$myColumn] = ($applicant['desired_job_title'] == '' ? 'Job Not Provided' : $applicant['desired_job_title']);
                                    }
                                }
                                if ($myColumn == 'questionnaire') {

                                    $questions = unserialize($applicant['talent_and_fair_data']);
                                    $job_fair_data = '';

                                    // Updated on: 27-06-2019
                                    // Added questionaire exists check
                                    if (sizeof($questions) && isset($questions['questions']) && sizeof($questions['questions'])) {
                                        foreach ($questions['questions'] as $question => $answer) {
                                            $job_fair_data = 'Que: ' . $question;
                                            $input[] = $job_fair_data;
                                            if (is_array($answer)) {
                                                $multi_ans = '';
                                                foreach ($answer as $que => $ans) {
                                                    $multi_ans .= $ans . ',';
                                                }
                                                $multi_ans = rtrim($multi_ans, ',');
                                                $job_fair_data = 'Ans: ' . $multi_ans;
                                                $input[] = $job_fair_data;
                                            } else {
                                                $job_fair_data = 'Ans: ' . $answer;
                                                $input[] = $job_fair_data;
                                            }
                                        }
                                    }
                                    //                                    $input[] = $job_fair_data;
                                }
                            }

                            fputcsv($output, $input);
                        }
                        fclose($output);
                        exit;
                    } else {
                        $this->session->set_flashdata('message', 'No data found.');
                    }
                }

                //load views
                $this->load->view('main/header', $data);
                $this->load->view('reports/generate_job_fair_report');
                $this->load->view('main/footer');
            } else {
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function generate_applicant_origination_statistics_report($employer_sid = null, $year = null, $week = null)
    {
        if ($this->session->userdata('logged_in')) {
            if ($this->form_validation->run() == false) {
                $data['session'] = $this->session->userdata('logged_in');

                $security_sid = $data['session']['employer_detail']['sid'];
                $security_details = db_get_access_level_details($security_sid);
                $data['security_details'] = $security_details;
                //check_access_permissions($security_details, 'dashboard', 'application_tracking');

                $company_sid = $data["session"]["company_detail"]["sid"];

                if ($employer_sid == null || $employer_sid == 0) {
                    $employer_sid = $data["session"]["employer_detail"]["sid"];
                }


                $data['title'] = "Generate Applicant Origination Statistics";
                $data['flag'] = true;
                $search = $this->input->get(NULL, TRUE);
                if (!isset($search['date_option'])) {
                    $search['date_option'] = 'daily';
                }
                $data['search'] = $search;

                // grouping data
                $indeed_array = array();
                $automotosocial_array = array();
                $glassdoor_array = array();
                $juju_array = array();
                $automotohr_array = array();
                $other_array = array();
                $zip_recruiter_array = array();
                $jobs_2_career_array = array();

                $applicants = $this->reports_model->get_stats_by_source($search, $company_sid);

                foreach ($applicants as $key => $value) {
                    $source = $value['applicant_source'];
                    if (strpos($source, 'indeed') !== FALSE) {
                        $indeed_array[] = $value;
                    } else if (strpos($source, 'automotosocial.com') !== FALSE) {
                        $automotosocial_array[] = $value;
                    } else if (strpos($source, 'glassdoor.com') !== FALSE) {
                        $glassdoor_array[] = $value;
                    } else if (strpos($source, 'juju.com') !== FALSE) {
                        $juju_array[] = $value;
                    } else if (strpos($source, 'automotohr.com') !== FALSE || strpos($source, 'automotohr') !== FALSE || strpos($source, 'Career Website') !== FALSE || strpos($source, 'career_website') !== FALSE) {
                        $automotohr_array[] = $value;
                    } else if (strpos($source, 'ziprecruiter') !== FALSE) {
                        $zip_recruiter_array[] = $value;
                    } else if (strpos($source, 'jobs2career') !== FALSE) {
                        $jobs_2_career_array[] = $value;
                    } else {
                        $other_array[] = $value;
                    }
                }

                $data['indeed'] = $indeed_array;
                $data['indeed_count'] = count($indeed_array);

                $data['automoto_social'] = $automotosocial_array;
                $data['automoto_social_count'] = count($automotosocial_array);

                $data['glassdoor'] = $glassdoor_array;
                $data['glassdoor_count'] = count($glassdoor_array);

                $data['juju'] = $juju_array;
                $data['juju_count'] = count($juju_array);

                $data['automotohr'] = $automotohr_array;
                $data['automotohr_count'] = count($automotohr_array);

                $data['other'] = $other_array;
                $data['other_count'] = count($other_array);

                $data['zip_recruiter'] = $zip_recruiter_array;
                $data['zip_recruiter_count'] = count($zip_recruiter_array);

                $data['jobs_2_career'] = $jobs_2_career_array;
                $data['jobs_2_career_count'] = count($jobs_2_career_array);


                $career_sites_array = array();
                $other_sites_array = array();

                //                foreach ($automotohr_array as $key => $value) {
                //                    $career_sites_array[$value['applicant_source']][] = $automotohr_array[$key];
                //                }

                foreach ($other_array as $key => $value) {
                    if (!empty($value['applicant_source'])) {
                        $other_sites_array[$value['applicant_source']][] = $other_array[$key];
                    } else {
                        //                $career_sites_array['Career Website'][] = $other_array[$key];
                        $automotohr_array[] = $other_array[$key];
                    }
                }

                $data['career_sites_array'] = $career_sites_array;
                $data['other_sites_array'] = $other_sites_array;
                $data['other_sites_array_count'] = count($other_sites_array);

                //load views
                $this->load->view('main/header', $data);
                $this->load->view('reports/generate_applicant_origination_statistics');
                $this->load->view('main/footer');
            } else {
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function generate_company_daily_activity_report($today_date = NULL)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');

            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            $company_sid = $data["session"]["company_detail"]["sid"];
            $today_date = urldecode($today_date);

            $data['title'] = "Daily Activity Report";
            $perform_action = $this->input->post('perform_action');

            //
            $companyinfo = getCompanyInfo($company_sid);
            $data['companyName'] = $companyinfo['company_name'];

            switch ($perform_action) {
                case 'export_csv_file':
                    $this->form_validation->set_rules('report_date', 'report_date', 'required|trim|xss_clean');
                    break;
            }

            if ($this->form_validation->run() == false) {
                //Do Nothing
            } else {
                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'export_csv_file':

                        $report_date = $this->input->post('report_date');

                        $my_date = new DateTime($report_date);

                        $start_date = $my_date->format('Y-m-d');
                        $start_date = $start_date . ' 00:00:00';

                        $end_date = $my_date->format('Y-m-d');
                        $end_date = $end_date . ' 23:59:59';

                        $employers = $this->reports_model->generate_activity_log_data_for_view($company_sid, $start_date, $end_date);

                        /*echo '<pre>';
                        print_r($employers);
                        exit;*/


                        header('Content-Type: text/csv; charset=utf-8');
                        header('Content-Disposition: attachment; filename=data.csv');
                        $output = fopen('php://output', 'w');

                        fputcsv($output, array($companyinfo['company_name'], '', ''));


                        foreach ($employers as $employer) {
                            fputcsv($output, array($employer['employer_name'], '', $employer['total_time_spent'] . ' Minutes'));
                            fputcsv($output, array('IP Address', 'Login Duration', 'User Agent'));
                            foreach ($employer['activity_logs'] as $key => $activity_log) {
                                fputcsv($output, array(str_replace('_', '.', $key), $activity_log['time_spent'] . ' Minutes', $activity_log['act_details']['user_agent']));
                            }
                            fputcsv($output, array('', '', ''));
                        }

                        fclose($output);

                        exit;
                        break;
                }
            }
        } else {
            redirect(base_url('login'), "refresh");
        }

        //load views
        $this->load->view('main/header', $data);
        $this->load->view('reports/generate_company_daily_activity_report');
        $this->load->view('main/footer');
    }


    public function activity_ajax_responder()
    {
        $data['session'] = $this->session->userdata('logged_in');
        $data['title'] = "Daily Activity Report";
        $security_sid = $data['session']['employer_detail']['sid'];
        $security_details = db_get_access_level_details($security_sid);
        $data['security_details'] = $security_details;
        $company_sid = $data["session"]["company_detail"]["sid"];
        $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');

        $perform_action = $this->input->post('perform_action');

        switch ($perform_action) {
            case 'get_employers_daily_activity':
                $this->form_validation->set_rules('report_date', 'report_date', 'required|trim|xss_clean');
                break;
            default:
                //do nothing
                break;
        }

        if ($this->form_validation->run() == false) {
        } else {
            $perform_action = $this->input->post('perform_action');
            switch ($perform_action) {
                case 'get_employers_daily_activity':
                    $report_date = $this->input->post('report_date');
                    $my_data['date'] = $report_date;
                    $my_date = new DateTime($report_date);

                    $start_date = $my_date->format('Y-m-d');
                    $start_date = $start_date . ' 00:00:00';

                    $end_date = $my_date->format('Y-m-d');
                    $end_date = $end_date . ' 23:59:59';

                    $report_data = $this->reports_model->generate_activity_log_data_for_view($company_sid, $start_date, $end_date);
                    $my_data['employers_logs'] = $report_data;
                    $my_data['report_type'] = 'daily';
                    $my_data['report_date'] = $report_date;

                    $this->load->view('reports/generate_company_daily_activity_report_partial', $my_data);

                    break;
                default:
                    //do nothing
                    break;
            }
        }
    }

    function generate_interviews_scheduled_by_recruiters($start_date = null, $end_date = null)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session']                                                    = $this->session->userdata('logged_in');
            $security_sid                                                       = $data['session']['employer_detail']['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;
            check_access_permissions($security_details, 'my_settings', 'reports');
            $company_sid                                                        = $data['session']['company_detail']['sid'];

            if ($start_date == null || $start_date == 'beginning-of-time') {
                $first_day_of_month                                             = mktime(0, 0, 0, date("m"), 1, date("Y"));
                $start_date                                                     = date("d-m-Y", $first_day_of_month);
            } else {
                $start_date                                                     = date('Y-m-d', strtotime(urldecode(str_replace('-', '/', $start_date))));
            }

            if ($end_date == null || $end_date == 'end-of-days') {
                $last_day_of_month                                              = mktime(0, 0, 0, date("m"), date('t'), date("Y"));
                $end_date                                                       = date("d-m-Y", $last_day_of_month);
            } else {
                $end_date                                                       = date('Y-m-d', strtotime(urldecode(str_replace('-', '/', $end_date))));
            }

            $data['startdate']                                                  = $start_date;
            $data['enddate']                                                    = $end_date;
            $have_status                                                        = $this->reports_model->have_status_records($company_sid);
            $data['have_status']                                                = $have_status;
            $company_events                                                     = $this->reports_model->get_company_events_in_date_range($company_sid, $start_date, $end_date);

            $employers_in_event                                                 = array();
            $employer_names                                                     = array();
            $applicant_names                                                    = array();

            foreach ($company_events as $event_detail) {
                $employer_sid                                                   = $event_detail['employers_sid'];
                $employers_in_event[$employer_sid][$event_detail['sid']]        = $event_detail;
                $interview_sids                                                 = $event_detail['interviewer'];
                $interviewer_array                                              = explode(',', $interview_sids);
                $users_type                                                     = $event_detail['users_type'];
                $user_sid                                                       = $event_detail['applicant_job_sid'];

                if ($users_type == 'employee') {
                    if (!array_key_exists($user_sid, $employer_names)) {
                        $employer_names[$user_sid]                              = $this->reports_model->get_employers_name($user_sid);
                    }
                } else {
                    if (!array_key_exists($user_sid, $applicant_names)) {
                        $applicant_names[$user_sid]                             = $this->reports_model->get_applicants_name($user_sid);
                    }
                }

                if (!array_key_exists($employer_sid, $employer_names)) {
                    $employer_names[$employer_sid]                              = $this->reports_model->get_employers_name($employer_sid);
                }

                foreach ($interviewer_array as $ia) {
                    $employers_in_event[$ia][$event_detail['sid']]              = $event_detail;

                    if (!array_key_exists($ia, $employer_names)) {
                        $employer_names[$ia]                                    = $this->reports_model->get_employers_name($ia);
                    }
                }
            }

            $data['events']                                                     = $employers_in_event;
            $data['employer_name']                                              = $employer_names;
            $data['applicant_names']                                            = $applicant_names;
            $data['title']                                                      = 'Advanced Hr Reports - Interviews Scheduled by Recruiters';

            //
            $companyinfo = getCompanyInfo($company_sid);
            $data['companyName'] = $companyinfo['company_name'];


            if (isset($_POST['submit']) && $_POST['submit'] == 'Export CSV') {
                header('Content-Type: text/csv; charset=utf-8');
                header('Content-Disposition: attachment; filename=interviews-scheduled-by-recruiters.csv');
                $output = fopen('php://output', 'w');

                fputcsv($output, array($companyinfo['company_name'], '', '', ''));

                foreach ($data['events'] as $employer_id => $employee_events) {
                    $name = ucwords($employer_names[$employer_id]);
                    //                    fputcsv($output, array($name));
                    fputcsv($output, array($name, 'Interview Scheduled For', 'Interview Status', 'Interview Date'));

                    foreach ($employee_events as $event) {
                        $input = array();
                        $input['schedule_for'] = '';
                        $user_sid = $event['applicant_job_sid'];

                        if ($event['users_type'] == 'employee') {
                            $input['name'] = ucwords($employer_names[$user_sid]);
                        } else {
                            $input['name'] =  ucwords($applicant_names[$user_sid]);
                        }

                        if ($have_status == true) {
                            if (empty($event['applicant_jobs_list'])) {
                                if (!empty($event['applicant_job_sid'])) {
                                    $status_sid = $event['applicant_job_sid'];
                                    $status_info = get_interview_status_by_parent_id($status_sid);
                                } else {
                                    $status_info = array();
                                    $status_info['name'] = 'Status Not Found';
                                }
                            } else {
                                $status_array = explode(',', $event['applicant_jobs_list']);
                                $status_sid = $status_array[0];
                                $status_info = get_interview_status($status_sid);
                            }

                            if (empty($status_info)) {
                                $status_info = array();
                                $status_info['name'] = 'Status Not Found';
                            }

                            $input['status'] = ucwords($status_info['name']);
                        } else { // don't have custom status enabled
                            if (empty($event['applicant_jobs_list'])) {
                                $status_sid = $event['applicant_job_sid'];
                                $field_id = 'portal_job_applications_sid';
                            } else {
                                $status_array = explode(',', $event['applicant_jobs_list']);
                                $status_sid = $status_array[0];
                                $field_id = 'sid';
                            }

                            $default_status = get_default_interview_status($status_sid, $field_id);

                            if ($default_status == 'Contacted') {
                                $input['status'] = ucwords($default_status);
                            } elseif ($default_status == 'Candidate Responded') {
                                $input['status'] = ucwords($default_status);
                            } elseif ($default_status == 'Qualifying') {
                                $input['status'] = ucwords($default_status);
                            } elseif ($default_status == 'Submitted') {
                                $input['status'] = ucwords($default_status);
                            } elseif ($default_status == 'Interviewing') {
                                $input['status'] = ucwords($default_status);
                            } elseif ($default_status == 'Offered Job') {
                                $input['status'] = ucwords($default_status);
                            } elseif ($default_status == 'Not In Consideration') {
                                $input['status'] = ucwords($default_status);
                            } elseif ($default_status == 'Client Declined') {
                                $input['status'] = ucwords($default_status);
                            } elseif ($default_status == 'Placed/Hired' || $default_status == 'Ready to Hire') {
                                $input['status'] = ucwords('Ready to Hire');
                            } elseif ($default_status == 'Not Contacted Yet') {
                                $input['status'] = ucwords($default_status);
                            } elseif ($default_status == 'Future Opportunity') {
                                $input['status'] = ucwords($default_status);
                            } elseif ($default_status == 'Left Message') {
                                $input['status'] = ucwords($default_status);
                            }
                        }

                        $input['date'] = reset_datetime(array('datetime' => $event['date'], '_this' => $this));
                        fputcsv($output, $input);
                    }
                }
                fclose($output);
                exit;
            }

            $this->load->view('main/header', $data);
            $this->load->view('reports/generate_interviews_scheduled_by_recruiters_report2');
            $this->load->view('main/footer');
        } else {
            redirect('login', "refresh");
        }
    }

    /**
     *
     *
     */
    function driving_license()
    {
        if (!$this->session->userdata('logged_in')) redirect('login', "refresh");
        //
        $data['session'] = $this->session->userdata('logged_in');
        $data['security_details'] = db_get_access_level_details($data['session']['employer_detail']['sid']);
        check_access_permissions($data['security_details'], 'my_settings', 'reports');
        //
        $company_sid   = $data['session']['company_detail']['sid'];
        //
        $data['title'] = 'Driving License Report';

        //
        $companyinfo = getCompanyInfo($company_sid);
        $data['companyName'] = $companyinfo['company_name'];

        if (sizeof($this->input->post(NULL, TRUE))) {
            $post = $this->input->post(NULL, TRUE);
            $post['companySid'] = $company_sid;

            $post['dd-license-type'] = strtolower($post['dd-license-type']);
            $post['dd-license-class'] = strtolower($post['dd-license-class']);

            if ($post['txt-license-number'] == '') $post['txt-license-number'] = 'all';
            if ($post['txt-issue-date'] == '') $post['txt-issue-date'] = 'all';
            if ($post['txt-expiration-date'] == '') $post['txt-expiration-date'] = 'all';
            // Fetch licenses
            $licenses = $this->reports_model->getDriverLicensesForExport($post);
            if (sizeof($licenses)) {

                header('Content-Type: text/csv; charset=utf-8');
                header("Content-Disposition: attachment; filename=Driving_report_" . (date('YmdHis')) . ".csv");
                $output = fopen('php://output', 'w');
                fputcsv($output, array($companyinfo['company_name'], '', '', '', '', '', '', '', ''));

                fputcsv($output, array('Employee', 'Job Title', 'Licence Type', 'Licence Class', 'License Authority', 'License Number', 'Date Of Birth', 'Issue Date', 'Expiration Date'));

                foreach ($licenses as $license) {
                    $a = array();
                    $a[] = remakeEmployeeName($license);
                    $a[] = $license['job_title'];
                    $a[] = $license['license_type'] != '' ? $license['license_type'] : 'N/A';
                    $a[] = $license['license_class'] != '' ? $license['license_class'] : 'N/A';
                    $a[] = $license['license_authority'] != '' ? $license['license_authority'] : 'N/A';
                    $a[] = $license['license_number'] != '' ? $license['license_number'] : 'N/A';
                    $a[] = $license['dob'] != '' ? $license['dob'] : 'N/A';
                    $a[] = $license['license_issue_date'] != '' ? $license['license_issue_date'] : 'N/A';
                    $a[] = $license['license_expiration_date'] != '' ? $license['license_expiration_date'] : 'N/A';
                    fputcsv($output, $a);
                }

                fclose($output);
                exit;
            }
        }
        //
        $this->load->view('main/header', $data);
        $this->load->view('reports/driving_license');
        $this->load->view('main/footer');
    }

    //
    function handler()
    {
        if (!$this->session->userdata('logged_in')) redirect('login', "refresh");
        $this->res['Redirect'] = false;
        if (!$this->input->is_ajax_request() || !$this->input->post('action', true)) {
            $this->res['Response'] = 'Invalid request made.';
            $this->resp();
        }
        //
        $ses = $this->session->userdata('logged_in');
        $companyId = $ses['company_detail']['sid'];
        //
        $formpost = $this->input->post(NULL, TRUE);
        $formpost['limit'] = $this->limit;
        $formpost['companySid'] = $companyId;
        //
        switch (strtolower($formpost['action'])) {
                //
            case 'get_driving_license_filter':
                // Fetch employees
                $employees = $this->reports_model->getEmployeesByCompanyId($companyId);
                //
                if (!sizeof($employees)) {
                    $this->res['Response'] = 'No Employees found.';
                    $this->resp();
                }
                $this->res['Status'] = true;
                $this->res['Response'] = 'Proceed';
                $this->res['Data'] = $employees;
                $this->resp();
                break;
                //
            case 'get_driving_licenses':
                // Fetch licenses
                $licenses = $this->reports_model->getDriverLicenses($formpost);
                //
                if (!sizeof($licenses)) {
                    $this->res['Response'] = 'No Employees found.';
                    $this->resp();
                }
                $this->res['Status'] = true;
                $this->res['Limit'] = $formpost['limit'];
                $this->res['Response'] = 'Proceed';
                $this->res['Data'] = $licenses['Data'];
                //
                if ($formpost['page'] == 1) {
                    $this->res['TotalRecords'] = $licenses['Count'];
                    $this->res['TotalPages'] = ceil($this->res['TotalRecords'] / $this->res['Limit']);
                }
                $this->resp();
                break;

            case 'get_employee_status_filter':

                // Fetch employees
                $employees = $this->reports_model->getEmployeesByCompanyIdAll($companyId);
                //
                if (!sizeof($employees)) {
                    $this->res['Response'] = 'No Employees found.';
                    $this->resp();
                }
                $this->res['Status'] = true;
                $this->res['Response'] = 'Proceed';
                $this->res['Data'] = $employees;
                $this->resp();
                break;

            case 'get_active_employee_status_filter':

                // Fetch employees
                $employees = $this->reports_model->getActiveEmployeesByCompanyIdAll($companyId);
                //
                if (!sizeof($employees)) {
                    $this->res['Response'] = 'No Employees found.';
                    $this->resp();
                }
                $this->res['Status'] = true;
                $this->res['Response'] = 'Proceed';
                $this->res['Data'] = $employees;
                $this->resp();
                break;

            case 'get_employee_document':

                $employeedocuments = $this->reports_model->getEmployeeDocument($formpost);
                //
                if (!sizeof($employeedocuments)) {
                    $this->res['Response'] = 'No Employees found.';
                    $this->resp();
                }
                $this->res['Status'] = true;
                $this->res['Limit'] = $formpost['limit'];
                $this->res['Response'] = 'Proceed';
                $this->res['Data'] = $employeedocuments['Data'];
                //
                if ($formpost['page'] == 1) {
                    $this->res['TotalRecords'] = $employeedocuments['Count'];
                    $this->res['TotalPages'] = ceil($this->res['TotalRecords'] / $this->res['Limit']);
                }
                $this->resp();
                break;

            case 'get_employee_assigned_document':

                $employeedocuments = $this->reports_model->getEmployeeAssignedDocument($formpost);
                //
                if (!sizeof($employeedocuments)) {
                    $this->res['Response'] = 'No Employees found.';
                    $this->resp();
                }
                $this->res['Status'] = true;
                $this->res['Limit'] = $formpost['limit'];
                $this->res['Response'] = 'Proceed';
                $this->res['Data'] = $employeedocuments['Data'];
                //
                if ($formpost['page'] == 1) {
                    $this->res['TotalRecords'] = $employeedocuments['Count'];
                    $this->res['TotalPages'] = ceil($this->res['TotalRecords'] / $this->res['Limit']);
                }
                $this->resp();
                break;
        }
        //
        $this->resp();
    }

    //
    function resp()
    {
        header('Content-Type: application/json');
        echo json_encode($this->res);
        exit(0);
    }

    function company_sms_report($start_date = null, $end_date = null)
    {
        if (!$this->session->userdata('logged_in')) redirect('login', "refresh");
        //
        $data['session'] = $this->session->userdata('logged_in');
        $data['security_details'] = db_get_access_level_details($data['session']['employer_detail']['sid']);
        check_access_permissions($data['security_details'], 'my_settings', 'reports');
        //
        $company_sid   = $data['session']['company_detail']['sid'];
        //
        if ($this->form_validation->run() == false) {
            $from_date = '';
            $to_date = '';
            $grand_total = '';
            //
            $current_date = date('Y-m-d H:i:s');
            //
            if ((empty($start_date) && empty($end_date)) || ($start_date == 'all' && $end_date == 'all')) {
                // First day of the current month.
                $from_date = date('Y-m-01', strtotime($current_date));
                // Last day of the current month.
                $to_date = date('Y-m-t', strtotime($current_date));
            } else if ($start_date == 'all' && ($end_date != 'all' || !empty($end_date))) {
                $from_date = date('Y-m-01', strtotime($end_date));
                //
                $to_date = date('Y-m-d', strtotime($end_date));
            } else if (($start_date != 'all' || !empty($start_date)) && $end_date == 'all') {
                // 
                $from_date = date('Y-m-d', strtotime($start_date));
                //
                $to_date = date('Y-m-t', strtotime($current_date));
            } else {
                // 
                $from_date = date('Y-m-d', strtotime($start_date));
                //
                $to_date = date('Y-m-d', strtotime($end_date));
            }

            $sms_data = $this->reports_model->get_sms_data($company_sid, $from_date, $to_date);

            $data_to_show = array();

            if (!empty($sms_data)) {
                $total_amount = 0;
                foreach ($sms_data as $key => $sms) {
                    //
                    $receiver_name = '';
                    $user_type = '';
                    //
                    if ($sms['module_slug'] == 'ats') {
                        $receiver_name = get_applicant_name($sms['receiver_user_id']);
                        $user_type = 'Applicant';
                    } else {
                        $receiver_name = getUserNameBySID($sms['receiver_user_id']);
                        $user_type = 'Employee';
                    }
                    //
                    $sender_name = getUserNameBySID($sms['sender_user_id']);
                    //
                    $data_to_show[$key]['receiver_name'] = $receiver_name;
                    $data_to_show[$key]['sender_name'] = $sender_name;
                    $data_to_show[$key]['message'] = $sms['message_body'];
                    $data_to_show[$key]['sender_phone_number'] = $sms['sender_phone_number'];
                    $data_to_show[$key]['receiver_phone_number'] = $sms['receiver_phone_number'];
                    $data_to_show[$key]['user_type'] = $sms['receiver_phone_number'];

                    $data_to_show[$key]['sent'] = $sms['is_sent'] == 1 ? 'Sent' : 'Not Sent';
                    $data_to_show[$key]['read'] = $sms['is_read'] == 1 ? 'Delivered' : 'Sent';
                    $data_to_show[$key]['date'] = date_format(new DateTime($sms['created_at']), 'M d Y h:m a');
                    $data_to_show[$key]['amount'] = $sms['charged_amount'];

                    $total_amount = $total_amount + $sms['charged_amount'];
                }

                $grand_total = $total_amount;
            }

            $data['sms_data'] = $data_to_show;
            $data['grand_total'] = $grand_total;
            $data['company_name'] = getCompanyNameBySid($company_sid);
            $data['title'] = 'SMS Service Reports';

            $this->load->view('main/header', $data);
            $this->load->view('reports/sms_company_report');
            $this->load->view('main/footer');
        } else {
        }
    }

    function error_report()
    {
        // $alertpages = ["assign_bulk_documents", "add_history_documents"];
        // $page = str_replace(base_url(), "", $_POST["OnPage"]);
        // $_POST['ErrorLogTime'] = date('Y-m-d H:i:s');
        // $_POST['OccurrenceTime'] = DateTime::createFromFormat('d/m/Y, H:i:s A', $_POST['OccurrenceTime'])->format('Y-m-d H:i:s');
        // if (str_replace($alertpages, '', $page) != $page) {
        // 	sendMail(
        // 		FROM_EMAIL_NOTIFICATIONS,
        // 		OFFSITE_DEV_EMAIL,
        // 		'Bulk Upload Documents Error',
        // 		@json_encode($_POST)
        // 	);
        // } else {
        // 	sendMail(
        // 		FROM_EMAIL_NOTIFICATIONS,
        // 		OFFSITE_DEV_EMAIL,
        // 		'Error On ' . $page,
        // 		@json_encode($_POST)
        // 	);
        // }
        // //
        // jsErrorHandler($_POST);
        //  
        echo "error repoted and send email";
    }


    function employee_document()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('login', "refresh");
        }
        //
        $data['session'] = $this->session->userdata('logged_in');
        $data['security_details'] = db_get_access_level_details($data['session']['employer_detail']['sid']);
        check_access_permissions($data['security_details'], 'my_settings', 'reports');
        $company_sid   = $data['session']['company_detail']['sid'];
        //
        $data['title'] = 'Employee Document Report';

        //
        $companyinfo = getCompanyInfo($company_sid);
        $data['companyName'] = $companyinfo['company_name'];


        if (sizeof($this->input->post(NULL, TRUE))) {
            $post = $this->input->post(NULL, TRUE);
            $post['companySid'] = $company_sid;

            $post['employeeSid'] = $post['dd-employee'];
            $post['employeeStatus'] = $post['dd-status-emp'];
            // Fetch Status
            $employeedocument = $this->reports_model->getEmployeeDocument($post, true);

            if (sizeof($employeedocument['Data'])) {


                header('Content-Type: text/csv; charset=utf-8');
                header("Content-Disposition: attachment; filename=document_report_" . (date('Y_m_d_H_i_s', strtotime('now'))) . ".csv");
                $output = fopen('php://output', 'w');

                fputcsv($output, array($companyinfo['company_name'], '', '', ''));

                fputcsv($output, array(
                    "Exported By", $data['session']['employer_detail']['first_name'] . " " . $data['session']['employer_detail']['last_name']
                ));
                fputcsv($output, array(
                    "Export Date", date('m/d/Y H:i:s ', strtotime('now')) . STORE_DEFAULT_TIMEZONE_ABBR
                ));

                fputcsv(
                    $output,
                    array(
                        'Employee',
                        'Document Title',
                        'Is Confidential?',
                        'Confidential Employees',
                        'Authorize Signers',
                        'Visible To Payroll',
                        'Allowed Departments',
                        'Allowed Teams',
                        'Allowed Employees',
                        'Approval Flow'
                    )
                );

                //
                $rows = $employeedocument['Data'];
                //
                $employeeList = '';
                //
                $us = array_column($rows, 'user_sid');
                //
                if ($us) {
                    $employeeList .= implode(',', $us) . ',';
                }
                //
                $ce = array_column($rows, 'confidential_employees');
                //
                if ($ce) {
                    $employeeList .= implode(',', $ce) . ',';
                }
                $as = array_column($rows, 'authorize_signers');
                //
                if ($as) {
                    $employeeList .= implode(',', $as) . ',';
                }
                $ae = array_column($rows, 'allowed_employees');
                //
                if ($ae) {
                    $employeeList .= implode(',', $ae) . ',';
                }
                $ae = array_column($rows, 'document_approval_employees');
                //
                if ($ae) {
                    $employeeList .= implode(',', $ae) . ',';
                }
                //
                $employeeList = array_values(array_unique(explode(',', rtrim($employeeList, ','))));
                $employeeOBJ = $this->reports_model->getEmployeeByIdsOBJ($employeeList);
                //
                foreach ($rows as $row) {
                    //
                    $a = [];
                    $a[] = $employeeOBJ[$row['user_sid']];
                    $a[] = $row['document_title'];
                    $a[] = $row['is_confidential'] == '1' ? "YES" : "NO";
                    //
                    if ($row['confidential_employees']) {
                        //
                        $tmp = explode(',', $row['confidential_employees']);
                        //
                        $b = '';
                        foreach ($tmp as $t) {
                            $b .= $employeeOBJ[$t] . "\n\n";
                        }
                        //
                        $a[] = $b;
                    } else {
                        $a[] = '-';
                    }
                    //
                    if ($row['authorize_signers']) {
                        //
                        $tmp = explode(',', $row['authorize_signers']);
                        //
                        $b = '';
                        foreach ($tmp as $t) {
                            $b .= $employeeOBJ[$t] . "\n\n";
                        }
                        //
                        $a[] = $b;
                    } else {
                        $a[] = '-';
                    }
                    $a[] = $row['visible_to_payroll'] == '1' ? "YES" : "NO";
                    $a[] = $row['allowed_departments'] ? implode("\n\n", $row['allowed_departments']) : '-';
                    $a[] = $row['allowed_teams'] ? implode("\n\n", $row['allowed_teams']) : '-';
                    //
                    if ($row['allowed_employees']) {
                        //
                        $tmp = explode(',', $row['allowed_employees']);
                        //
                        $b = '';
                        foreach ($tmp as $t) {
                            $b .= $employeeOBJ[$t] . "\n\n";
                        }
                        //
                        $a[] = $b;
                    } else {
                        $a[] = '-';
                    }
                    //
                    if ($row['document_approval_employees']) {
                        //
                        $tmp = explode(',', $row['document_approval_employees']);
                        //
                        $b = '';
                        foreach ($tmp as $t) {
                            $b .= $employeeOBJ[$t] . "\n\n";
                        }
                        //
                        $a[] = $b;
                    } else {
                        $a[] = '-';
                    }
                    //
                    fputcsv($output, $a);
                }

                fclose($output);
                exit;
            }
        }
        //
        $this->load->view('main/header', $data);
        $this->load->view('reports/employee_document');
        $this->load->view('main/footer');
    }


    //
    function employeeAssignedDocuments()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('login', "refresh");
        }
        //
        $data['session'] = $this->session->userdata('logged_in');
        $data['security_details'] = db_get_access_level_details($data['session']['employer_detail']['sid']);
        check_access_permissions($data['security_details'], 'my_settings', 'reports');
        $company_sid   = $data['session']['company_detail']['sid'];
        //
        $data['title'] = 'Employee Assigned Documents Report';
        $companyinfo = getCompanyInfo($company_sid);
        $data['companyName'] = $companyinfo['company_name'];

        $data['employerSid'] = $data["session"]["employer_detail"]["sid"];

        //
        if (sizeof($this->input->post(NULL, TRUE))) {
            $post = $this->input->post(NULL, TRUE);
            $post['companySid'] = $company_sid;

            $post['employeeSid'] = $post['dd-employee'];
            $post['employeeStatus'] = $post['dd-status-emp'];

            $employeedocument = $this->reports_model->getEmployeeAssignedDocument($post);
            //_e($employeedocument,true,true);


            if (sizeof($employeedocument['Data'])) {

                $employeeList = array_column($employeedocument['Data'], 'sid');

                header('Content-Type: text/csv; charset=utf-8');
                header("Content-Disposition: attachment; filename=employee_assigned_document_report_" . (date('Y_m_d_H_i_s', strtotime('now'))) . ".csv");
                $output = fopen('php://output', 'w');

                fputcsv($output, array($companyinfo['company_name'], '', '', ''));

                fputcsv($output, array(
                    "Exported By", $data['session']['employer_detail']['first_name'] . " " . $data['session']['employer_detail']['last_name']
                ));
                fputcsv($output, array(
                    "Export Date", date('m/d/Y H:i:s ', strtotime('now')) . STORE_DEFAULT_TIMEZONE_ABBR
                ));

                fputcsv(
                    $output,
                    array(
                        'Employees',
                        '# of Documents',
                        '# Not Completed',
                        '# Completed',
                        '# No Action Required',
                        'Documents'
                    )
                );

                //
                $rows = $employeedocument['Data'];
                $employeeOBJ = $this->reports_model->getEmployeeByIdsOBJ($employeeList);

                //
                foreach ($rows as $row) {

                    //
                    $totalAssignedDocs = count($row['assigneddocuments']);
                    $totalAssignedGeneralDocs = count($row['assignedgeneraldocuments']);
                    $totalDocs = $totalAssignedDocs + $totalAssignedGeneralDocs;

                    $totalDocsNotCompleted = 0;
                    $totalDocsCompleted = 0;
                    $totalDocsNoAction = 0;
                    $completedStatus ='';
                    //
                    if (!empty($row['assignedi9document'])) {
                        $totalDocs = $totalDocs + 1;
                        if ($row['assignedi9document'][0]['user_consent'] == 1) {
                            $totalDocsCompleted = $totalDocsCompleted + 1;
                           // $completedStatus = ' (Completed) ';

                        } else {
                            $totalDocsNotCompleted = $totalDocsNotCompleted + 1;
                          //  $completedStatus = ' (Not Completed) ';

                        }
                    }
                    if (!empty($row['assignedw9document'])) {
                        $totalDocs = $totalDocs + 1;
                        if ($row['assignedw9document'][0]['user_consent'] == 1) {
                            $totalDocsCompleted = $totalDocsCompleted + 1;
                          //  $completedStatus = ' (Completed) ';

                        } else {
                            $totalDocsNotCompleted = $totalDocsNotCompleted + 1;
                           // $completedStatus = ' (Not Completed) ';

                        }
                    }
                    if (!empty($row['assignedw4document'])) {
                        $totalDocs = $totalDocs + 1;
                        if ($row['assignedw4document'][0]['user_consent'] == 1) {
                            $totalDocsCompleted = $totalDocsCompleted + 1;
                          //  $completedStatus = ' (Completed) ';

                        } else {
                            $totalDocsNotCompleted = $totalDocsNotCompleted + 1;
                            //$completedStatus = ' (Not Completed) ';

                        }
                    }
                    if (!empty($row['assignedeeocdocument'])) {
                        $totalDocs = $totalDocs + 1;
                        if ($row['assignedeeocdocument'][0]['last_completed_on'] != '' && $row['assignedeeocdocument'][0]['last_completed_on'] != null) {
                            $totalDocsCompleted = $totalDocsCompleted + 1;
                            $completedStatus = ' (Completed) ';
                        } else {
                            $totalDocsNotCompleted = $totalDocsNotCompleted + 1;
                            $completedStatus = ' (Not Completed) ';
                        }
                    }

                    //
                    $doc = '';

                    if (!empty($row['assignedi9document'])) {

                        if ($row['assignedi9document'][0]['user_consent'] == 1) {
                            $completedStatus = ' (Completed) ';

                        } else {
                            $completedStatus = ' (Not Completed) ';

                        }

                        $doc .= "I9 Fillable" . $completedStatus . "\n\n";
                    }
                    if (!empty($row['assignedw9document'])) {

                        if ($row['assignedw9document'][0]['user_consent'] == 1) {
                            $completedStatus = ' (Completed) ';

                        } else {
                            $completedStatus = ' (Not Completed) ';

                        }

                        $doc .= "W9 Fillable" . $completedStatus . "\n\n";
                    }
                    if (!empty($row['assignedw4document'])) {

                        if ($row['assignedw4document'][0]['user_consent'] == 1) {
                            $completedStatus = ' (Completed) ';

                        } else {
                            $completedStatus = ' (Not Completed) ';

                        }
                        $doc .= "W4 Fillable" . $completedStatus . "\n\n";
                    }
                    if (!empty($row['assignedeeocdocument'])) {

                        if ($row['assignedeeocdocument'][0]['last_completed_on'] != '' && $row['assignedeeocdocument'][0]['last_completed_on'] != null) {
                            $completedStatus = ' (Completed) ';
                        } else {
                            $completedStatus = ' (Not Completed) ';
                        }
                        $doc .= "EEOC Form" . $completedStatus . "\n\n";
                    }

                    //
                    if (count($row['assignedgeneraldocuments']) > 0) {
                        foreach ($row['assignedgeneraldocuments'] as $rowGeneral) {

                            if ($rowGeneral['is_completed'] == 1) {
                                $totalDocsCompleted = $totalDocsCompleted + 1;
                                $completedStatus = ' (Completed) ';
                            } else {
                                $completedStatus = ' (Not Completed) ';
                                $totalDocsNotCompleted = $totalDocsNotCompleted + 1;
                            }

                            $doc .= ucwords(str_replace('_', ' ', $rowGeneral['document_type'])) . $completedStatus . "\n\n";
                        }
                    }

                    //
                    if (count($row['assigneddocuments']) > 0) {
                        foreach ($row['assigneddocuments'] as $assigned_row) {
                            $completedStatus ='';
                            if ($assigned_row['completedStatus'] == 'Not Completed') {
                                $totalDocsNotCompleted = $totalDocsNotCompleted + 1;
                                $completedStatus = ' (Not Completed) ';
                            }
                            if ($assigned_row['completedStatus'] == 'Completed') {
                                $totalDocsCompleted = $totalDocsCompleted + 1;
                                $completedStatus = ' (Completed) ';
                            }

                            if ($assigned_row['completedStatus'] == 'No Action Required') {
                                $totalDocsNoAction = $totalDocsNoAction  +1;
                               $completedStatus = ' (No Action Required) ';
                            }

                            if ($assigned_row['confidential_employees'] != null) {
                                $confidentialEmployees = explode(',', $assigned_row['confidential_employees']);

                                if (in_array($data['employerSid'], $confidentialEmployees)) {
                                    $doc .= $assigned_row['document_title'] . $completedStatus . "\n\n";
                                } else {
                                    $totalDocs = $totalDocs - 1;
                                }
                            } else {
                                $doc .= $assigned_row['document_title'] . $completedStatus . "\n\n";
                            }
                        }
                    }

                    //
                    if ($row['assignedPerformanceDocument'] != 'Not Assigned') {
                        $doc .= "Performance Evaluation Document" . $row['assignedPerformanceDocument'] . "\n\n";
                    }

                    //
                    $a = [];
                    $a[] = $employeeOBJ[$row['sid']];
                    $a[] = $totalDocs;
                    $a[] = $totalDocsNotCompleted;
                    $a[] = $totalDocsCompleted;
                    $a[] = $totalDocsNoAction;
                    $a[] = $doc;
                    //
                    fputcsv($output, $a);
                }

                fclose($output);
                exit;
            }
        }

        //
        $this->load->view('main/header', $data);
        $this->load->view('reports/employee_assigned_documents');
        $this->load->view('main/footer');
    }

    public function employeeTerminationReport($start_date = 'all', $end_date = 'all', $page_number = 1)
    {

        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'reports');
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $data['title'] = 'Advanced Hr Reports - Employees Termination Reports';
            //
            $start_date = urldecode($start_date);
            $end_date = urldecode($end_date);
            //
            if (!empty($start_date) && $start_date != 'all') {
                $start_date_applied = empty($start_date) ? null : DateTime::createFromFormat('m-d-Y', $start_date)->format('Y-m-d');
            } else {
                $start_date_applied = date('Y-m-d 00:00:00');
            }

            if (!empty($end_date) && $end_date != 'all') {
                $end_date_applied = empty($end_date) ? null : DateTime::createFromFormat('m-d-Y', $end_date)->format('Y-m-d');
            } else {
                $end_date_applied = date('Y-m-d');
            }
            //
            $between = '';
            //
            if ($start_date_applied != NULL && $end_date_applied != NULL) {
                $between = "terminated_employees.termination_date between '" . $start_date_applied . "' and '" . $end_date_applied . "'";
            }
            //
            $data["flag"] = true;
            
            //
            $data['terminatedEmployeesCount'] = sizeof($this->reports_model->getTerminatedEmployees($company_sid, $between, null, null));
            /** pagination * */
            $this->load->library('pagination');
            $records_per_page = PAGINATION_RECORDS_PER_PAGE;
            $my_offset = 0;
            //
            if($page_number > 1){
                $my_offset = ($page_number - 1) * $records_per_page;
            }
            //
            $baseUrl = base_url('manage_admin/reports/employees_termination_report') . '/'. urlencode($start_date) . '/' . urlencode($end_date);
            //
            $uri_segment = 6;
            $config = array();
            $config["base_url"] = $baseUrl;
            $config["total_rows"] = $data['terminatedEmployeesCount'];
            $config["per_page"] = $records_per_page;
            $config["uri_segment"] = $uri_segment;
            $choice = $config["total_rows"] / $config["per_page"];
            $config["num_links"] = ceil($choice);
            $config["use_page_numbers"] = true;
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
            $data['page_links'] = $this->pagination->create_links();
            $total_records = $data['terminatedEmployeesCount'];

            $data['current_page'] = $page_number;
            $data['from_records'] = $my_offset == 0 ? 1 : $my_offset;
            $data['to_records'] = $total_records < $records_per_page ? $total_records : $my_offset + $records_per_page;
        
            $data['terminatedEmployees'] = $this->reports_model->getTerminatedEmployees($company_sid, $between, $records_per_page, $my_offset);
            $allTerminatedEmployees = $this->reports_model->getTerminatedEmployees($company_sid, $between, null, null);

            if (isset($_POST['submit']) && $_POST['submit'] == 'Export') {
                if(isset($allTerminatedEmployees) && sizeof($allTerminatedEmployees) > 0){
                    $filename = str_replace(' ', '_',$data['session']['employer_detail']['CompanyName']).'_employee_terminated_report.csv';
                    header('Content-Type: text/csv; charset=utf-8');
                    header('Content-Disposition: attachment; filename='.$filename);
                    $output = fopen('php://output', 'w');

                    fputcsv($output, array('Name', 'Employee ID', 'Job Title', 'Department', 'Hire Date', 'Last Day Worked', 'Termination Reason'));

                    foreach($allTerminatedEmployees as $terminatedEmployee){
                        //
                        $employeeName = remakeEmployeeName([
                            'first_name' => $terminatedEmployee['first_name'],
                            'last_name' => $terminatedEmployee['last_name'],
                            'access_level' => $terminatedEmployee['access_level'],
                            'timezone' => isset($terminatedEmployee['timezone']) ? $terminatedEmployee['timezone'] : '',
                            'access_level_plus' => $terminatedEmployee['access_level_plus'],
                            'is_executive_admin' => $terminatedEmployee['is_executive_admin'],
                            'pay_plan_flag' => $terminatedEmployee['pay_plan_flag'],
                            'job_title' => $terminatedEmployee['job_title'],
                        ]); 
                        //
                        $hireDate = get_employee_latest_joined_date(
                            $terminatedEmployee['registration_date'],
                            $terminatedEmployee['joined_at'],
                            $terminatedEmployee['rehire_date'],
                            false
                        );
                        //
                        $reason = $terminatedEmployee['termination_reason'];
                        //
                        if ($reason == 1) {
                            $terminatedReason = 'Resignation';
                        } else if ($reason == 2) {
                            $terminatedReason = 'Fired';
                        } else if ($reason == 3) {
                            $terminatedReason = 'Tenure Completed';
                        } else if ($reason == 4) {
                            $terminatedReason = 'Personal';
                        } else if ($reason == 5) {
                            $terminatedReason = 'Personal';
                        } else if ($reason == 6) {
                            $terminatedReason = 'Problem with Supervisor';
                        } else if ($reason == 7) {
                            $terminatedReason = 'Relocation';
                        } else if ($reason == 8) {
                            $terminatedReason = 'Work Schedule';
                        } else if ($reason == 9) {
                            $terminatedReason = 'Retirement';
                        } else if ($reason == 10) {
                            $terminatedReason = 'Return to School';
                        } else if ($reason == 11) {
                            $terminatedReason = 'Pay';
                        } else if ($reason == 12) {
                            $terminatedReason = 'Without Notice/Reason';
                        } else if ($reason == 13) {
                            $terminatedReason = 'Involuntary';
                        } else if ($reason == 14) {
                            $terminatedReason = 'Violating Company Policy';
                        } else if ($reason == 15) {
                            $terminatedReason = 'Attendance Issues';
                        } else if ($reason == 16) {
                            $terminatedReason = 'Performance';
                        } else if ($reason == 17) {
                            $terminatedReason = 'Workforce Reduction';
                        } elseif ($reason == 18) {
                            $terminatedReason = 'Store Closure';
                        } else {
                            $terminatedReason = 'N/A';
                        }
                        //
                        $input = array();
                        $input['employee_name'] = $employeeName;
                        $input['employee_id'] = "AHR-".$terminatedEmployee['sid'];
                        $input['job_title'] = $terminatedEmployee['job_title'];
                        $input['department'] = getDepartmentNameBySID($terminatedEmployee['department_sid']);
                        $input['hire_date'] = formatDateToDB($hireDate, DB_DATE, SITE_DATE);
                        $input['last_day_worked'] = formatDateToDB($terminatedEmployee['termination_date'], DB_DATE, SITE_DATE);
                        $input['termination_reason'] = $terminatedReason;
                        fputcsv($output, $input);
                    }
                    fclose($output);
                    exit;
                } else {
                    $this->session->set_flashdata('message', 'No data found.');
                }
            }
            //
            $this->load->view('main/header', $data);
            $this->load->view('reports/employees_termination_report');
            $this->load->view('main/footer');
        } else {
            redirect('login', "refresh");
        }
    }
}
