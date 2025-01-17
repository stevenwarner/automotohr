<?php defined('BASEPATH') or exit('No direct script access allowed');

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

    public function index($keyword = 'all', $opt_type = 'no', $start_date = null, $end_date = null, $employee_status = null, $page = null)
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

            $records_per_page = 5; //PAGINATION_RECORDS_PER_PAGE;
            if ($keyword == 'employee') {
                $page = ($this->uri->segment(7)) ? $this->uri->segment(7) : 0;
            } else {
                $page = ($this->uri->segment(6)) ? $this->uri->segment(6) : 0;
            }

            $my_offset = 0;

            if ($page > 1) {
                $my_offset = ($page - 1) * $records_per_page;
            }

            $male_cout = 0;
            $female_cout = 0;
            $notdefined_cout = 0;
            //
            $male_cout_hispanic = 0;
            $male_cout_white = 0;
            $male_cout_black = 0;
            $male_cout_native = 0;
            $male_cout_asian = 0;
            $male_cout_american = 0;
            $male_cout_races = 0;
            $male_cout_nogroup = 0;

            $male_cout_hispanic_hired = 0;
            $male_cout_white_hired = 0;
            $male_cout_black_hired = 0;
            $male_cout_native_hired = 0;
            $male_cout_asian_hired = 0;
            $male_cout_american_hired = 0;
            $male_cout_races_hired = 0;
            $male_cout_nogroup_hired = 0;
            //
            $female_cout_hispanic = 0;
            $female_cout_white = 0;
            $female_cout_black = 0;
            $female_cout_native = 0;
            $female_cout_asian = 0;
            $female_cout_american = 0;
            $female_cout_races = 0;
            $female_cout_nogroup = 0;
            //
            $female_cout_hispanic_hired = 0;
            $female_cout_white_hired = 0;
            $female_cout_black_hired = 0;
            $female_cout_native_hired = 0;
            $female_cout_asian_hired = 0;
            $female_cout_american_hired = 0;
            $female_cout_races_hired = 0;
            $female_cout_nogroup_hired = 0;
            //
            $notdefined_cout_hispanic = 0;
            $notdefined_cout_white = 0;
            $notdefined_cout_black = 0;
            $notdefined_cout_native = 0;
            $notdefined_cout_asian = 0;
            $notdefined_cout_american = 0;
            $notdefined_cout_races = 0;
            $notdefined_cout_nogroup = 0;


            if ($keyword == 'employee') {

                $total_records = '';
                $eeo_candidates = '';

                $segement6 = '/' . $employee_status;
                $uri_segment = 7;

                $eeo_candidates = $this->eeo_model->getEEOCEmployeesByFilter($keyword, $opt_type, $start_date, $end_date, $company_id, $records_per_page, $my_offset, false, $employee_status);
                $total_records = count($eeo_candidates);

                foreach ($eeo_candidates as $employee_row) {

                    if ($employee_row['gender'] == 'Male') {
                        $male_cout++;
                        if ($employee_row['group_status'] == 'Hispanic or Latino') {
                            $male_cout_hispanic++;

                            if ($employee_row['applicant_sid'] != '') {
                                $male_cout_hispanic_hired++;
                            }
                        } else if ($employee_row['group_status'] == 'White') {
                            $male_cout_white++;
                            if ($employee_row['applicant_sid'] != '') {
                                $male_cout_white_hired++;
                            }
                        } else if ($employee_row['group_status'] == 'Black or African American') {
                            $male_cout_black++;
                            if ($employee_row['applicant_sid'] != '') {
                                $male_cout_black_hired++;
                            }
                        } else if ($employee_row['group_status'] == 'Native Hawaiian or Other Pacific Islander') {
                            $male_cout_native++;
                            if ($employee_row['applicant_sid'] != '') {
                                $male_cout_native_hired++;
                            }
                        } else if ($employee_row['group_status'] == 'Asian') {
                            $male_cout_asian++;
                            if ($employee_row['applicant_sid'] != '') {
                                $male_cout_asian_hired++;
                            }
                        } else if ($employee_row['group_status'] == 'American Indian or Alaska Native') {
                            $male_cout_american++;
                            if ($employee_row['applicant_sid'] != '') {
                                $male_cout_american_hired++;
                            }
                        } else if ($employee_row['group_status'] == 'Two or More Races') {
                            $male_cout_races++;
                            if ($employee_row['applicant_sid'] != '') {
                                $male_cout_races_hired++;
                            }
                        } else {
                            $male_cout_nogroup++;
                            if ($employee_row['applicant_sid'] != '') {
                                $male_cout_nogroup_hired++;
                            }
                        }
                    } elseif ($employee_row['gender'] == 'Female') {
                        $female_cout++;
                        if ($employee_row['group_status'] == 'Hispanic or Latino') {
                            $female_cout_hispanic++;
                            if ($employee_row['applicant_sid'] != '') {
                                $female_cout_hispanic_hired++;
                            }
                        } else if ($employee_row['group_status'] == 'White') {
                            $female_cout_white++;
                            if ($employee_row['applicant_sid'] != '') {
                                $female_cout_white_hired++;
                            }
                        } else if ($employee_row['group_status'] == 'Black or African American') {
                            $female_cout_black++;
                            if ($employee_row['applicant_sid'] != '') {
                                $female_cout_black_hired++;
                            }
                        } else if ($employee_row['group_status'] == 'Native Hawaiian or Other Pacific Islander') {
                            $female_cout_native++;
                            if ($employee_row['applicant_sid'] != '') {
                                $female_cout_native_hired++;
                            }
                        } else if ($employee_row['group_status'] == 'Asian') {
                            $female_cout_asian++;
                            if ($employee_row['applicant_sid'] != '') {
                                $female_cout_asian_hired++;
                            }
                        } else if ($employee_row['group_status'] == 'American Indian or Alaska Native') {
                            $female_cout_american++;
                            if ($employee_row['applicant_sid'] != '') {
                                $female_cout_american_hired++;
                            }
                        } else if ($employee_row['group_status'] == 'Two or More Races') {
                            $female_cout_races++;
                            if ($employee_row['applicant_sid'] != '') {
                                $female_cout_races_hired++;
                            }
                        } else {
                            $female_cout_nogroup++;
                            if ($employee_row['applicant_sid'] != '') {
                                $female_cout_nogroup_hired++;
                            }
                        }
                    } else {
                        $notdefined_cout++;
                        if ($employee_row['group_status'] == 'Hispanic or Latino') {
                            $notdefined_cout_hispanic++;
                        } else if ($employee_row['group_status'] == 'White') {
                            $notdefined_cout_white++;
                        } else if ($employee_row['group_status'] == 'Black or African American') {
                            $notdefined_cout_black++;
                        } else if ($employee_row['group_status'] == 'Native Hawaiian or Other Pacific Islander') {
                            $notdefined_cout_native++;
                        } else if ($employee_row['group_status'] == 'Asian') {
                            $notdefined_cout_asian++;
                        } else if ($employee_row['group_status'] == 'American Indian or Alaska Native') {
                            $notdefined_cout_american++;
                        } else if ($employee_row['group_status'] == 'Two or More Races') {
                            $notdefined_cout_races++;
                        } else {
                            $notdefined_cout_nogroup++;
                        }
                    }
                }

                $eeo_candidates = array_slice($eeo_candidates, $my_offset, $records_per_page);
                $data['totalrecords'] = $total_records;
                $data['recordsfor'] = 'Employees';
            } else {
                $segement6 = '';
                $uri_segment = 6;
                $total_records = $this->eeo_model->get_all_eeo_applicants($keyword, $opt_type, $start_date, $end_date, $company_id, $records_per_page, $my_offset, true);
                $eeo_candidates = $this->eeo_model->get_all_eeo_applicants($keyword, $opt_type, $start_date, $end_date, $company_id, $records_per_page, $my_offset);

                if (!empty($eeo_candidates)) {
                    foreach ($eeo_candidates as $key => $eeo_detail) {
                        if (empty($eeo_detail["gender"])) {
                            $eeoc_form = $this->eeo_model->get_user_eeo_form_info($eeo_detail["applicant_sid"], "applicant");
                            //
                            $eeo_candidates[$key]["us_citizen"] = $eeoc_form['us_citizen'];
                            $eeo_candidates[$key]["visa_status"] = $eeoc_form['visa_status'];
                            $eeo_candidates[$key]["group_status"] = $eeoc_form['group_status'];
                            $eeo_candidates[$key]["veteran"] = $eeoc_form['veteran'];
                            $eeo_candidates[$key]["disability"] = $eeoc_form['disability'];
                            $eeo_candidates[$key]["gender"] = $eeoc_form['gender'];

                            if ($eeoc_form['gender'] == 'Male') {
                                $male_cout++;
                                if ($eeoc_form['group_status'] == 'Hispanic or Latino') {
                                    $male_cout_hispanic++;
                                } else if ($eeoc_form['group_status'] == 'White') {
                                    $male_cout_white++;
                                } else if ($eeoc_form['group_status'] == 'Black or African American') {
                                    $male_cout_black++;
                                } else if ($eeoc_form['group_status'] == 'Native Hawaiian or Other Pacific Islander') {
                                    $male_cout_native++;
                                } else if ($eeoc_form['group_status'] == 'Asian') {
                                    $male_cout_asian++;
                                } else if ($eeoc_form['group_status'] == 'American Indian or Alaska Native') {
                                    $male_cout_american++;
                                } else if ($eeoc_form['group_status'] == 'Two or More Races') {
                                    $male_cout_races++;
                                } else {
                                    $male_cout_nogroup++;
                                }
                            } elseif ($eeoc_form['gender'] == 'Female') {
                                $female_cout++;
                                if ($eeoc_form['group_status'] == 'Hispanic or Latino') {
                                    $female_cout_hispanic++;
                                } else if ($eeoc_form['group_status'] == 'White') {
                                    $female_cout_white++;
                                } else if ($eeoc_form['group_status'] == 'Black or African American') {
                                    $female_cout_black++;
                                } else if ($eeoc_form['group_status'] == 'Native Hawaiian or Other Pacific Islander') {
                                    $female_cout_native++;
                                } else if ($eeoc_form['group_status'] == 'Asian') {
                                    $female_cout_asian++;
                                } else if ($eeoc_form['group_status'] == 'American Indian or Alaska Native') {
                                    $female_cout_american++;
                                } else if ($eeoc_form['group_status'] == 'Two or More Races') {
                                    $female_cout_races++;
                                } else {
                                    $female_cout_nogroup++;
                                }
                            } else {
                                $notdefined_cout++;
                                if ($eeoc_form['group_status'] == 'Hispanic or Latino') {
                                    $notdefined_cout_hispanic++;
                                } else if ($eeoc_form['group_status'] == 'White') {
                                    $notdefined_cout_white++;
                                } else if ($eeoc_form['group_status'] == 'Black or African American') {
                                    $notdefined_cout_black++;
                                } else if ($eeoc_form['group_status'] == 'Native Hawaiian or Other Pacific Islander') {
                                    $notdefined_cout_native++;
                                } else if ($eeoc_form['group_status'] == 'Asian') {
                                    $notdefined_cout_asian++;
                                } else if ($eeoc_form['group_status'] == 'American Indian or Alaska Native') {
                                    $notdefined_cout_american++;
                                } else if ($eeoc_form['group_status'] == 'Two or More Races') {
                                    $notdefined_cout_races++;
                                } else {
                                    $notdefined_cout_nogroup++;
                                }
                            }

                        } else {
                            if ($eeo_detail['gender'] == 'Male') {
                                $male_cout++;
                                if ($eeo_detail['group_status'] == 'Hispanic or Latino') {
                                    $male_cout_hispanic++;
                                } else if ($eeo_detail['group_status'] == 'White') {
                                    $male_cout_white++;
                                } else if ($eeo_detail['group_status'] == 'Black or African American') {
                                    $male_cout_black++;
                                } else if ($eeo_detail['group_status'] == 'Native Hawaiian or Other Pacific Islander') {
                                    $male_cout_native++;
                                } else if ($eeo_detail['group_status'] == 'Asian') {
                                    $male_cout_asian++;
                                } else if ($eeo_detail['group_status'] == 'American Indian or Alaska Native') {
                                    $male_cout_american++;
                                } else if ($eeo_detail['group_status'] == 'Two or More Races') {
                                    $male_cout_races++;
                                } else {
                                    $male_cout_nogroup++;
                                }
                            } elseif ($eeo_detail['gender'] == 'Female') {
                                $female_cout++;
                                if ($eeo_detail['group_status'] == 'Hispanic or Latino') {
                                    $female_cout_hispanic++;
                                } else if ($eeo_detail['group_status'] == 'White') {
                                    $female_cout_white++;
                                } else if ($eeo_detail['group_status'] == 'Black or African American') {
                                    $female_cout_black++;
                                } else if ($eeo_detail['group_status'] == 'Native Hawaiian or Other Pacific Islander') {
                                    $female_cout_native++;
                                } else if ($eeo_detail['group_status'] == 'Asian') {
                                    $female_cout_asian++;
                                } else if ($eeo_detail['group_status'] == 'American Indian or Alaska Native') {
                                    $female_cout_american++;
                                } else if ($eeo_detail['group_status'] == 'Two or More Races') {
                                    $female_cout_races++;
                                } else {
                                    $female_cout_nogroup++;
                                }
                            } else {
                                $notdefined_cout++;
                                if ($eeo_detail['group_status'] == 'Hispanic or Latino') {
                                    $notdefined_cout_hispanic++;
                                } else if ($eeo_detail['group_status'] == 'White') {
                                    $notdefined_cout_white++;
                                } else if ($eeo_detail['group_status'] == 'Black or African American') {
                                    $notdefined_cout_black++;
                                } else if ($eeo_detail['group_status'] == 'Native Hawaiian or Other Pacific Islander') {
                                    $notdefined_cout_native++;
                                } else if ($eeo_detail['group_status'] == 'Asian') {
                                    $notdefined_cout_asian++;
                                } else if ($eeo_detail['group_status'] == 'American Indian or Alaska Native') {
                                    $notdefined_cout_american++;
                                } else if ($eeo_detail['group_status'] == 'Two or More Races') {
                                    $notdefined_cout_races++;
                                } else {
                                    $notdefined_cout_nogroup++;
                                }
                            }
                        }
                    }
                }    

                $data['totalrecords'] = $total_records;
                $data['recordsfor'] = 'Applicants';
            }
            $data['eeo_candidates'] = $eeo_candidates;

            $start_date = DateTime::createFromFormat('Y-m-d H:i:s', $start_date)->format('m-d-Y');
            $end_date = DateTime::createFromFormat('Y-m-d H:i:s', $end_date)->format('m-d-Y');
            $baseUrl = base_url('eeo') . '/' . urlencode($keyword) . '/' . $opt_type . '/' . urlencode($start_date) . '/' . urlencode($end_date) . $segement6;

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
            $data['employee_status'] = $employee_status;

            $data['male_cout'] = $male_cout;
            $data['female_cout'] = $female_cout;
            $data['notdefined_cout'] = $notdefined_cout;
            //
            $data['male_cout_hispanic'] = $male_cout_hispanic;
            $data['male_cout_white'] = $male_cout_white;
            $data['male_cout_black'] = $male_cout_black;
            $data['male_cout_native'] = $male_cout_native;
            $data['male_cout_asian'] = $male_cout_asian;
            $data['male_cout_american'] = $male_cout_american;
            $data['male_cout_races'] = $male_cout_races;
            $data['male_cout_nogroup'] = $male_cout_nogroup;
            //
            $data['male_cout_hispanic_hired'] = $male_cout_hispanic_hired;
            $data['male_cout_white_hired'] = $male_cout_white_hired;
            $data['male_cout_black_hired'] = $male_cout_black_hired;
            $data['male_cout_native_hired'] = $male_cout_native_hired;
            $data['male_cout_asian_hired'] = $male_cout_asian_hired;
            $data['male_cout_american_hired'] = $male_cout_american_hired;
            $data['male_cout_races_hired'] = $male_cout_races_hired;
            $data['male_cout_nogroup_hired'] = $male_cout_nogroup_hired;
            //
            $data['female_cout_hispanic'] = $female_cout_hispanic;
            $data['female_cout_white'] = $female_cout_white;
            $data['female_cout_black'] = $female_cout_black;
            $data['female_cout_native'] = $female_cout_native;
            $data['female_cout_asian'] = $female_cout_asian;
            $data['female_cout_american'] = $female_cout_american;
            $data['female_cout_races'] = $female_cout_races;
            $data['female_cout_nogroup'] = $female_cout_nogroup;
            //
            $data['female_cout_hispanic_hired'] = $female_cout_hispanic_hired;
            $data['female_cout_white_hired'] = $female_cout_white_hired;
            $data['female_cout_black_hired'] = $female_cout_black_hired;
            $data['female_cout_native_hired'] = $female_cout_native_hired;
            $data['female_cout_asian_hired'] = $female_cout_asian_hired;
            $data['female_cout_american_hired'] = $female_cout_american_hired;
            $data['female_cout_races_hired'] = $female_cout_races_hired;
            $data['female_cout_nogroup_hired'] = $female_cout_nogroup_hired;

            //
            $data['notdefined_cout_hispanic'] = $notdefined_cout_hispanic;
            $data['notdefined_cout_white'] = $notdefined_cout_white;
            $data['notdefined_cout_black'] = $notdefined_cout_black;
            $data['notdefined_cout_native'] = $notdefined_cout_native;
            $data['notdefined_cout_asian'] = $notdefined_cout_asian;
            $data['notdefined_cout_american'] = $notdefined_cout_american;
            $data['notdefined_cout_races'] = $notdefined_cout_races;
            $data['notdefined_cout_nogroup'] = $notdefined_cout_nogroup;

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

            $start_date = $_POST['startdate'];
            $end_date = $_POST['enddate'];
            $employee_status = $_POST['employee_status'];

            if ($_POST['applicantoption'] == 'employee') {
                $opt_type = $_POST['opt_type1'];
                $keyword = '';
            } else {
                $opt_type = $_POST['opt_type'];
                $keyword = $_POST['keyword'];
            }
            $keyword = empty($keyword) ? 'all' : $keyword;
            $start_date = empty($start_date) ? 'all' : $start_date;
            $end_date = empty($end_date) ? 'all' : $end_date;
            $opt_type = empty($opt_type) ? 'all' : $opt_type;
            $employee_status = empty($employee_status) ? 'all' : $employee_status;

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

            if ($_POST['applicantoption'] == 'employee') {
                $eeo_candidates = $this->eeo_model->getEEOCEmployeesByFilter($keyword, $opt_type, $start_date, $end_date, $company_id, 0, 0, false, $employee_status);
            } else {
                $eeo_candidates = $this->eeo_model->get_all_eeo_applicants($keyword, $opt_type, $start_date, $end_date, $company_id, null, 0, false);

                if (!empty($eeo_candidates)) {
                   foreach ($eeo_candidates as $key => $eeo_detail) {
                        if (empty($eeo_detail["gender"])) {
                            $eeoc_form = $this->eeo_model->get_user_eeo_form_info($eeo_detail["applicant_sid"], "applicant");
                            //
                            $eeo_candidates[$key]["us_citizen"] = $eeoc_form['us_citizen'];
                            $eeo_candidates[$key]["visa_status"] = $eeoc_form['visa_status'];
                            $eeo_candidates[$key]["group_status"] = $eeoc_form['group_status'];
                            $eeo_candidates[$key]["veteran"] = $eeoc_form['veteran'];
                            $eeo_candidates[$key]["disability"] = $eeoc_form['disability'];
                            $eeo_candidates[$key]["gender"] = $eeoc_form['gender'];

                        }
                    } 
                }
                
            }





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
                        "date_applied" => $manual_row['date_applied']
                    );
                } else {
                    $eeo_candidates[] = array(
                        "sid" => $manual_row['applicant_sid'],
                        "job_sid" => $manual_row['job_sid'],
                        "Title" => $manual_job_title,
                        "first_name" => $manual_row['first_name'],
                        "last_name" => $manual_row['last_name'],
                        "eeo_form" => $manual_row['eeo_form'],
                        "date_applied" => $manual_row['date_applied']
                    );
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
                        $veteran = $this->input->post('veteran')?$this->input->post('veteran'):'';
                        $disability = $this->input->post('disability')?$this->input->post('disability'):'';
                        $gender = $this->input->post('gender')?$this->input->post('gender'):'';

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
                            $data_to_insert['is_expired'] = 1;

                            $this->eeo_model->insert_eeo_record($users_type, $users_sid, $data_to_insert);
                            //
                            $dataToUpdate = array();
                            $dataToUpdate['gender'] = strtolower($gender);
                            update_user_gender($users_sid, $users_type, $dataToUpdate);
                            //
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
            if ($data['session']['portal_detail']['eeo_form_profile_status'] != 1) {
                $this->session->set_flashdata('message', '<strong>Error:</strong> E.E.O.C Form Disable!');
                //
                if ($user_type == 'applicant') {
                    redirect('applicant_profile/' . $user_sid . '/' . $jobs_listing, 'refresh');
                } else {
                    redirect('employee_profile/' . $user_sid, 'refresh');
                }
            }
            //
            switch ($user_type) {
                case 'employee':

                    if (!checkIfAppIsEnabled('etm')) {
                        $this->session->set_flashdata('message', '<b>Error:</b> Access denied');
                        redirect(base_url('dashboard'), "refresh");
                    }
                    
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
                    $eeo_form_status = $this->eeo_model->get_eeo_form_status($user_type, $user_sid);
                    $eeo_form_info = $this->hr_documents_management_model->get_user_eeo_form_info($user_sid, $user_type, false);
                    $data['eeo_form_status'] = $eeo_form_status;
                    $data['eeo_form_info'] = $eeo_form_info;

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
                        'user_type' => ucwords($user_type)
                    );

                    $data["user_name"] = $applicant_info['first_name'] . " " . $applicant_info['last_name'];

                    $data['applicant_average_rating'] = $this->hr_documents_management_model->getApplicantAverageRating($user_sid, 'applicant'); //getting average rating of applicant
                    $data['employer'] = $data_employer;
                    $data['company_sid'] = $company_sid;
                    $data['employer_sid'] = $applicant_info['sid'];
                    $data['left_navigation'] = $left_navigation;
                    break;
            }
            //
            $eeo_form_info = $this->hr_documents_management_model->get_user_eeo_form_info($user_sid, $user_type, false);
            $data['eeo_form_info'] = $eeo_form_info;
            //
            $eeoc_history = $this->hr_documents_management_model->fetch_form_history('eeoc', $user_type, $user_sid);
            //
            $history_array = array();
            $h_key = 0;
            //
            if (!empty($eeoc_history)) {
                foreach ($eeoc_history as $history) {
                    $history_array[$h_key]['sid'] = $history['sid'];
                    $history_array[$h_key]['type'] = 'EEOC_Form';
                    $history_array[$h_key]['name'] = 'EEOC Fillable Document';
                    $history_array[$h_key]['assign_on'] = $history['last_sent_at'];
                    $history_array[$h_key]['submitted_on'] = $history['last_completed_on'];
                    $history_array[$h_key]['status'] = !empty($history['is_expired']) && $history['is_expired'] == 1 ? "Completed" : "Not Completed";
                    //
                    $h_key++;
                }
            }
            //
            //
            $eeoc_track = $this->hr_documents_management_model->fetch_track_record($eeo_form_info['sid'], 'eeoc');
            //
            $data['title'] = 'EEOC Form';
            $data['user_type'] = $user_type;
            $data['user_sid'] = $user_sid;
            $data['job_list_sid'] = $jobs_listing;
            $data['history_title'] = "Re-Assign EEOC History";
            $data['verification_documents_history'] = $history_array;
            $data['track_history'] = $eeoc_track;
            $data['dl_citizen']         = getEEOCCitizenShipFlag($company_sid);
            $data['location']         = "Green Panel";
            //
            $this->load->view('main/header', $data);
            $this->load->view('eeo/user_eeoc');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    //
    function change_form_status()
    {
        //
        if (!$this->session->userdata('logged_in')) redirect('login', 'refresh');
        //
        $data['session'] = $this->session->userdata('logged_in');
        $employee_sid = $data['session']['employer_detail']['sid'];
        //
        if (!strtolower($this->input->method()) == 'post' || empty($this->input->post(NULL, TRUE))) {
            exit(0);
        }
        //
        $post = $this->input->post(NULL, TRUE);
        //
        $eeoc_form = $this->hr_documents_management_model->get_eeo_form_info($post["userId"], $post["userType"]);
        //
        if ($post["action"] == "active") {
            $this->hr_documents_management_model->activate_EEOC_forms($post["userType"], $post["userId"]);
            //
            keepTrackVerificationDocument($employee_sid, 'employee', 'assign', $eeoc_form['sid'], 'eeoc', 'EEOC Form Page');
            //
        } else if ($post["action"] == "deactive") {
            $this->hr_documents_management_model->deactivate_EEOC_forms($post["userType"], $post["userId"]);
            //
            keepTrackVerificationDocument($employee_sid, 'employee', 'revoke', $eeoc_form['sid'], 'eeoc', 'EEOC Form Page');
            //
        }
        //
        echo 'success';
        exit(0);
    }


    //
    function get_trail($sid, $document_type)
    {
        //
        $eeoc_track = $this->hr_documents_management_model->fetch_track_record($sid, $document_type);
        //
        if (empty($eeoc_track)) {
            echo '
                <tr>
                    <td colspan="12">
                        <p class="alert alert-info text-center">No trail found.</p>
                    </td>
                </tr>
            ';
            exit(0);
        }
        //
        $html = '';
        foreach ($eeoc_track as $track) {
            //
            $userDetails = getDataFromTable(
                $track['user_type'] === 'employee' ? 'users' : 'portal_job_applications',
                ['sid' => $track['user_sid']],
                $track['user_type'] === 'employee' ? explode(',', getUserFields()) : [
                    'first_name',
                    'last_name'
                ]
            );
            $html .= '<tr>';
            $html .= '    <td class="col-lg-4">';
            if ($track['document_type'] == "eeoc") {
                $html .= '               EEOC Fillable';
            } else if ($track['document_type'] == "w4") {
                $html .= '                W4 Fillable';
            } else if ($track['document_type'] == "w9") {
                $html .= '                W9 Fillable';
            } else if ($track['document_type'] == "i9") {
                $html .= '                I9 Fillable';
            }
            $html .= '    </td>';
            $html .= '    <td class="col-lg-4 text-right" colspan="4">';
            if ($track['user_type'] === 'applicant') {
                $html .=        $userDetails['first_name'].' '.$userDetails['last_name'].' (Applicant)';
            } else {
                $html .=        remakeEmployeeName($userDetails);
            }
            $html .= '    </td>';
            $html .= '    <td class="col-lg-4 text-right" colspan="4">';
            $html .=         reset_datetime(array('datetime' => $track['created_at'], '_this' => $this));
            $html .= '    </td>';
            $html .= '    <td class="col-lg-4 text-right" colspan="4">';
            if ($track['action'] == "revoke") {
                $html .= '              <strong class="text-danger">Revoked</strong>';
            } else if ($track['action'] == "assign") {
                $html .= '              <strong class="text-success">Assigned</strong>';
            } else if ($track['action'] == "completed") {
                $html .= '              <strong class="text-info">Completed</strong>';
            } elseif ($track['action'] == "updated") {
                $html .= '              <strong class="text-warning">Updated</strong>';
            }
            $html .= '    </td>';
            $html .= '</tr>';
        }
        //
        echo $html;
    }
    //
    function get_history($user_sid, $user_type, $document_type)
    {
        //
        $eeoc_history = $this->hr_documents_management_model->fetch_form_history($document_type, $user_type, $user_sid);
        //
        $history_array = array();
        $h_key = 0;
        //
        if (!empty($eeoc_history)) {
            foreach ($eeoc_history as $history) {
                $history_array[$h_key]['sid'] = $history['sid'];
                $history_array[$h_key]['type'] = strtoupper($document_type) . '_Form';
                $history_array[$h_key]['name'] = (strtoupper($document_type)) . ' Fillable Document';
                $history_array[$h_key]['assign_on'] = reset_datetime(array('datetime' => $history['last_sent_at'], '_this' => $this));
                $history_array[$h_key]['submitted_on'] = reset_datetime(array('datetime' => $history['last_completed_on'], '_this' => $this));
                $history_array[$h_key]['status'] = !empty($history['is_expired']) && $history['is_expired'] == 1 ? "Completed" : "Not Completed";

                //
                $h_key++;
            }
        }
        //
        header('content-type: application/json');
        echo json_encode($history_array);
        exit(0);
    }


    public function viewchart($keyword = 'all', $opt_type = 'no', $start_date = null, $end_date = null, $employee_status = null)
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

            $records_per_page = 5; //PAGINATION_RECORDS_PER_PAGE;
            if ($keyword == 'employee') {
                $page = ($this->uri->segment(7)) ? $this->uri->segment(7) : 0;
            } else {
                $page = ($this->uri->segment(6)) ? $this->uri->segment(6) : 0;
            }

            $my_offset = 0;

            if ($page > 1) {
                $my_offset = ($page - 1) * $records_per_page;
            }

            if ($keyword == 'employee') {

                $total_records = '';
                $eeo_candidates = '';

                $segement6 = '/' . $employee_status;
                $uri_segment = 7;

                $eeo_candidates = $this->eeo_model->get_all_eeo_employees($keyword, $opt_type, $start_date, $end_date, $company_id, $records_per_page, $my_offset, false, $employee_status);
                $total_records = count($eeo_candidates);
                $eeo_candidates = array_slice($eeo_candidates, $my_offset, $records_per_page);
            } else {
                $segement6 = '';
                $uri_segment = 6;
                $total_records = $this->eeo_model->get_all_eeo_applicants($keyword, $opt_type, $start_date, $end_date, $company_id, $records_per_page, $my_offset, true);
                $eeo_candidates = $this->eeo_model->get_all_eeo_applicants($keyword, $opt_type, $start_date, $end_date, $company_id, $records_per_page, $my_offset);
            }
            $data['eeo_candidates'] = $eeo_candidates;

            $start_date = DateTime::createFromFormat('Y-m-d H:i:s', $start_date)->format('m-d-Y');
            $end_date = DateTime::createFromFormat('Y-m-d H:i:s', $end_date)->format('m-d-Y');
            $baseUrl = base_url('eeo') . '/' . urlencode($keyword) . '/' . $opt_type . '/' . urlencode($start_date) . '/' . urlencode($end_date) . $segement6;

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
            $data['employee_status'] = $employee_status;





            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/eeo_applicants_new_chart');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }
}
