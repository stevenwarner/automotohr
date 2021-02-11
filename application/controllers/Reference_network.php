<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Reference_network extends Public_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('reference_network_model');
        $this->load->model('application_tracking_system_model');
        $this->load->library('pagination');
    }

    public function index() { 
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $employer_detail = $data['session']['employer_detail'];
            $company_detail = $data['session']['company_detail'];
            $security_sid = $employer_detail['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'dashboard', 'my_referral_network'); // Param2: Redirect URL, Param3: Function Name
            $data['title'] = 'My Referral Network';
            $company_id = $company_detail['sid'];
            $employer_id = $employer_detail['sid'];
            $data['employer'] = $employer_detail;
            $data['companyData'] = $company_detail;
            $data['companyData']['locationDetail'] = db_get_state_name($company_detail['Location_State']);
            $references_count = $this->reference_network_model->GetAllCount($company_id, $employer_id);
            //$this->db->last_query().'<br>'.$references_count;

            $records_per_page = PAGINATION_RECORDS_PER_PAGE;
            $page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
            $my_offset = 0;

            if ($page > 1) {
                $my_offset = ($page - 1) * $records_per_page;
            }

            $baseUrl = base_url('my_referral_network');
            $uri_segment = 2;
            $config = array();
            $config['base_url'] = $baseUrl;
            $config['total_rows'] = $references_count;
            $config['per_page'] = $records_per_page;
            $config['uri_segment'] = $uri_segment;
            $choice = $config['total_rows'] / $config['per_page'];
            $config['num_links'] = ceil($choice);
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
            $references = $this->reference_network_model->GetAll($company_id, $employer_id, $records_per_page, $my_offset);

            foreach ($references as $key => $reference) {
                $jobId = $reference['job_sid'];
                $jobDetails = $this->reference_network_model->GetJobDetails($jobId);

                if (!empty($jobDetails)) {
                    $references[$key]['Job_title'] = $jobDetails[0]['Title'];
                } else {
                    $references[$key]['Job_title'] = 'Job Listing Expired';
                }
            }
//            echo '<pre>'; print_r($references); exit;
            $data['references'] = $references;
            $data['references_count'] = $references_count;
            $data['employee'] = $employer_detail;
            $access_level = $employer_detail['access_level'];
            $load_view = check_blue_panel_status(false, 'self');
            $data['load_view'] = $load_view;
//            if(strtolower($access_level) == 'employee' || $load_view) {
//                $this->load->view('onboarding/on_boarding_header', $data);
//                $this->load->view('reference_network/index_ems');
//                $this->load->view('onboarding/on_boarding_footer');
//            } else {
                $data['applicant_average_rating'] = $this->application_tracking_system_model->getApplicantAverageRating($employer_id, 'employee');
                $this->load->view('main/header', $data);
                $this->load->view('reference_network/index');
                $this->load->view('main/footer');
//            }
        } else {
            redirect('login', "refresh");
        }
    }

    public function add_edit($sid = null) {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $employer_detail = $data['session']['employer_detail'];
            $company_detail = $data['session']['company_detail'];
            $security_sid = $employer_detail['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'dashboard', 'my_referral_network'); // Param2: Redirect URL, Param3: Function Name
            $data['title'] = 'Add New Referral';
            $company_id = $company_detail['sid'];
            $employer_id = $employer_detail['sid'];
            $data['employer'] = $employer_detail;
            $data['employee'] = $employer_detail;
            $data['companyData'] = $company_detail;
            $data['companyData']['locationDetail'] = db_get_state_name($company_detail['Location_State']);
            $access_level = $employer_detail['access_level'];
            $jobs = $this->reference_network_model->GetAllActiveJobs($company_id);

            $jobsArray = array();
            $jobsArray[''] = 'Please Select a Job Listing';
            
            foreach ($jobs as $job) {
                $jobsArray[$job['sid']] = $job['Title'];
            }

            $data['jobs'] = $jobsArray;

            if ($sid != null) {
                //todo Handle Edit Case
            }

            $this->form_validation->set_rules('job_sid', 'Job', 'required');
            $this->form_validation->set_rules('referred_to', 'Refer To', 'required|trim|xss_clean');
            $this->form_validation->set_rules('reference_email', 'Reference Email', 'required|valid_email|trim|xss_clean');
            $this->form_validation->set_rules('personal_message', 'Personal Message', 'trim|xss_clean');

            if ($this->form_validation->run() === FALSE) {
                if(strtolower($access_level) == 'employee' || check_blue_panel_status(false, 'self')) {
                    $this->load->view('onboarding/on_boarding_header', $data);
                    $this->load->view('reference_network/new_referral_ems');
                    $this->load->view('onboarding/on_boarding_footer');
                } else {
                    $data['applicant_average_rating'] = $this->application_tracking_system_model->getApplicantAverageRating($employer_id, 'employee');
                    $this->load->view('main/header', $data);
                    $this->load->view('reference_network/new_referral');
                    $this->load->view('main/footer');
                }
            } else { // All is Well Save Form
                $jobId = $this->input->post('job_sid');
                $referredTo = $this->input->post('referred_to');
                $referenceEmail = $this->input->post('reference_email');
                $personalMessage = $this->input->post('personal_message');
                $jobDetails = $this->reference_network_model->GetJobDetails($jobId);
                $this->reference_network_model->Save(null, $company_id, $employer_id, $jobId, $referredTo, $referenceEmail, date('Y-m-d H:i:s'), $personalMessage);
                $UserName = $data['session']['employer_detail']['first_name'] . ' ' . $data['session']['employer_detail']['last_name'];
                $sub_domain_url = db_get_sub_domain($company_id);

                $emailTemplateData = get_email_template(REFER_A_JOB);
                $emailTemplateBody = $emailTemplateData['text'];
                $emailTemplateBody = str_replace('{{referred_to}}', ucwords($referredTo), $emailTemplateBody);
                $emailTemplateBody = str_replace('{{username}}', ucwords($UserName), $emailTemplateBody);
                $emailTemplateBody = str_replace('{{job_title}}', $jobDetails[0]['Title'], $emailTemplateBody);
                $emailTemplateBody = str_replace('{{sub_domain_url}}', $sub_domain_url, $emailTemplateBody);
                $emailTemplateBody = str_replace('{{job_id}}', $jobId, $emailTemplateBody);
                $emailTemplateBody = str_replace('{{job_link}}', '<p><strong><a style="background-color: #d62828; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" href="http://"'.$sub_domain_url . '/job_details/' .$jobId.'" target="_blank">View Job</a></strong></p>', $emailTemplateBody);
                $messageBody = "";
                $messageBody .= '<p>' . '<b>- Attached Personal Message -</b>' . '</p>';
                $messageBody .= '<p>' . $personalMessage . '</p>';
                $from = $emailTemplateData['from_email'];
                $to = $referenceEmail;
                $subject = str_replace('{{username}}', ucwords($UserName), $emailTemplateData['subject']);
                $from_name = $emailTemplateData['from_name'];
                $body = EMAIL_HEADER
                        . $emailTemplateBody
                        . $messageBody
                        . EMAIL_FOOTER;

                $emailData = array(
                    'date' => date('Y-m-d H:i:s'),
                    'subject' => $subject,
                    'email' => $referenceEmail,
                    'message' => $body,
                    'username' => $data['session']['employer_detail']['sid']
                );

                if (base_url() == 'http://localhost/automotoCI/') {
                    save_email_log_common($emailData);
                } else {
                    save_email_log_common($emailData);
                    sendMail($from, $to, $subject, $body, $from_name);
                }
                
                $this->session->set_flashdata('message', '<b>Success:</b> Job Referred.');
                redirect('my_referral_network', 'refresh');
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function view($sid = null) {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $employer_detail = $data['session']['employer_detail'];
            $company_detail = $data['session']['company_detail'];
            $security_sid = $employer_detail['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'dashboard', 'my_referral_network'); // Param2: Redirect URL, Param3: Function Name
            $data['title'] = 'My Referral Network - View';
            $company_id = $company_detail['sid'];
            $employer_id = $employer_detail['sid'];
            $data['employerData'] = $employer_detail;
            $company_id = $company_detail['sid'];
            $data['companyData'] = $company_detail;
            $data['companyData']['locationDetail'] = db_get_state_name($company_detail['Location_State']);

            if ($sid != null) {
                $referral_info = $this->reference_network_model->Get($sid);
                $jobId = $referral_info[0]['job_sid'];
                $jobDetails = $this->reference_network_model->GetJobDetails($jobId);

                if (!empty($jobDetails)) {
                    $referral_info[0]['job_title'] = $jobDetails[0]['Title'];
                } else {
                    $referral_info[0]['job_title'] = 'Job Listing Expired!';
                }
                
                $data['referral_info'] = $referral_info[0];
                $this->load->view('main/header', $data);
                $this->load->view('reference_network/view_referral');
                $this->load->view('main/footer');
            } else {
                redirect('my_reference_network', 'refresh');
            }
        }
    }

    public function reference_network($flag = NULL) {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $employer_detail = $data['session']['employer_detail'];
            $company_detail = $data['session']['company_detail'];
            $security_sid = $employer_detail['sid'];
            $employer_id = $employer_detail['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            $company_id = $company_detail['sid'];
            $company_name = $company_detail['CompanyName'];
            $data['employer'] = $employer_detail;
            $data['companyData'] = $company_detail;
            $data['companyData']['locationDetail'] = db_get_state_name($company_detail['Location_State']);
            $data['title'] = ucwords($company_name) . ' - Referral Network';
            $access_level = $employer_detail['access_level'];

            if($flag=='coworker') {
                check_access_permissions($security_details, 'dashboard', 'coworker');
                $references_count = $this->reference_network_model->GetShareJobReferralsCount($company_id,'coworker');
            } elseif($flag=='via_email') {
                check_access_permissions($security_details, 'dashboard', 'referred_by_email');
                $references_count = $this->reference_network_model->GetShareJobReferralsCount($company_id,'via_email');
            } elseif($flag=='applicant_referrals') {
                check_access_permissions($security_details, 'dashboard', 'applicant_referrals');
                $references_count = $this->reference_network_model->GetApplicantProvidedReferralsCount($company_id);
            } else {
                check_access_permissions($security_details, 'dashboard', 'referral_network');
                $references_count = $this->reference_network_model->GetAllForCompanyCount($company_id);
            }

            /** pagination * */
            $records_per_page = PAGINATION_RECORDS_PER_PAGE;
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;
            $my_offset = 0;

            if ($page > 1) {
                $my_offset = ($page - 1) * $records_per_page;
            }

            $baseUrl = base_url('referral_network/'.$flag);
            $uri_segment = 3;
            $config = array();
            $config['base_url'] = $baseUrl;
            $config['total_rows'] = $references_count;
            $config['per_page'] = $records_per_page;
            $config['uri_segment'] = $uri_segment;
            $choice = $config['total_rows'] / $config['per_page'];
            $config['num_links'] = ceil($choice);
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
            $data['references_count'] = $references_count;
            /** pagination end * */
            $references = $this->reference_network_model->GetAllForCompany($company_id, $records_per_page, $my_offset);

            foreach ($references as $key => $reference) {
                $jobId = $reference['job_sid'];
                $jobDetails = $this->reference_network_model->GetJobDetails($jobId); //Get Job Title

                if (!empty($jobDetails)) {
                    $references[$key]['Job_title'] = $jobDetails[0]['Title'];
                } else {
                    $references[$key]['Job_title'] = 'Job Listing Expired';
                }

                $userId = $reference['users_sid'];
                $userDetails = $this->reference_network_model->GetUserDetails($userId);

                if (!empty($userDetails)) {
                    $userDetails = $userDetails[0];
                    $references[$key]['user_name'] = ucwords($userDetails['first_name'] . ' ' . $userDetails['last_name']);
                } else {
                    $references[$key]['user_name'] = 'anonymous';
                }
            }

            $coworkers  = $this->reference_network_model->GetShareJobReferrals($company_id,'coworker', $records_per_page, $my_offset);
            $via_emails = $this->reference_network_model->GetShareJobReferrals($company_id,'via_email', $records_per_page, $my_offset);
            $applicant_provided = $this->reference_network_model->GetApplicantProvidedReferrals($company_id, $records_per_page, $my_offset);
            $data['references'] = $references;
            $data['coworkers'] = $coworkers;
            $data['via_emails'] = $via_emails;
            $data['applicant_provided'] = $applicant_provided;
            $data['employee'] = $employer_detail;

            if (isset($_POST['submit']) && $_POST['submit'] == 'Export') {
                if($flag=='coworker') {
                    $myRecords = $this->reference_network_model->GetShareJobReferrals($company_id,'coworker');
                } elseif($flag=='via_email') {
                    $myRecords = $this->reference_network_model->GetShareJobReferrals($company_id,'via_email');
                } elseif($flag=='applicant_referrals') {
                    $myRecords = $this->reference_network_model->GetApplicantProvidedReferrals($company_id);
                } else {
                    $myRecords = $this->reference_network_model->GetAllForCompany($company_id);
                    foreach ($myRecords as $key => $reference) {
                        $jobId = $reference['job_sid'];
                        $jobDetails = $this->reference_network_model->GetJobDetails($jobId); //Get Job Title

                        if (!empty($jobDetails)) {
                            $myRecords[$key]['Job_title'] = $jobDetails[0]['Title'];
                        } else {
                            $myRecords[$key]['Job_title'] = 'Job Listing Expired';
                        }

                        $userId = $reference['users_sid'];
                        $userDetails = $this->reference_network_model->GetUserDetails($userId);

                        if (!empty($userDetails)) {
                            $userDetails = $userDetails[0];
                            $myRecords[$key]['user_name'] = ucwords($userDetails['first_name'] . ' ' . $userDetails['last_name']);
                        } else {
                            $myRecords[$key]['user_name'] = 'anonymous';
                        }
                    }
                }

                if (isset($myRecords) && sizeof($myRecords) > 0) {

                    switch($flag){
                        case 'coworker':
                            $file = 'Coworker_Referrals_'.date('Y-m-d H:i:s');
                            break;
                        case 'via_email':
                            $file = 'Referred_By_Email_'.date('Y-m-d H:i:s');
                            break;
                        case 'applicant_referrals':
                            $file = 'Applicant_Provided_Referrals_'.date('Y-m-d H:i:s');
                            break;
                        case 'all_referrals':
                        case '':
                            $file = 'My_Referrals_'.date('Y-m-d H:i:s');
                            break;
                    }

                    header('Content-Type: text/csv; charset=utf-8');
                    header('Content-Disposition: attachment; filename='.$file.'.csv');
                    $output = fopen('php://output', 'w');

                    if($flag!='applicant_referrals') {
                        fputcsv($output, array('Referred On', 'Referred By', 'Job Title', 'Referred To', 'Referred Email'));
                    } else {
                        fputcsv($output, array('Referred By', 'Job Title', 'Referred To', 'Referred Email'));
                    }

                    foreach ($myRecords as $record) {
                        $input = array();

                        if($flag=='coworker'){
                            $input['referred_date'] = reset_datetime(array('datetime' =>$record['date_time'], '_this' => $this));
                            $input['name'] = $record['referral_name'];
                            $input['Job_title'] = $record['Title'];
                            $input['referred_to'] = ucwords($record['first_name'] . " " . $record['last_name']);
                            $input['reference_email'] = $record['email'];
                        } elseif($flag=='via_email') {
                            $input['referred_date'] = reset_datetime(array('datetime' =>$record['date_time'], '_this' => $this));
                            $input['name'] = $record['referral_name'];
                            $input['Job_title'] = $record['Title'];
                            $input['referred_to'] = $record['share_name'];
                            $input['reference_email'] = $record['share_email'];
                        } elseif($flag=='applicant_referrals') {
                            $input['name'] = $record['referred_by_name'];
                            $input['Job_title'] = $record['Title'];
                            $input['referred_to'] = ucwords($record['first_name']." ".$record['last_name']);
                            $input['reference_email'] = $record['email'];
                        } else {
                            $input['referred_date'] = reset_datetime(array('datetime' =>$record['referred_date'], '_this' => $this));
                            $input['name'] = $record['user_name'];
                            $input['Job_title'] = $record['Job_title'];
                            $input['referred_to'] = $record['referred_to'];
                            $input['reference_email'] = $record['reference_email'];
                        }

                        fputcsv($output, $input);
                    }

                    fclose($output);
                    exit;
                } else {
                    $this->session->set_flashdata('message', 'No data found.');
                }
            }
            
            if(strtolower($access_level) == 'employee') {
                $this->load->view('onboarding/on_boarding_header', $data);
                $this->load->view('reference_network/reference_network_ems');
                $this->load->view('onboarding/on_boarding_footer');
            } else {
                $data['applicant_average_rating'] = $this->application_tracking_system_model->getApplicantAverageRating($employer_id, 'employee');
                $this->load->view('main/header', $data);
                $this->load->view('reference_network/reference_network');
                $this->load->view('main/footer');
            }
        } else {
            redirect('login', "refresh");
        }
    }
}