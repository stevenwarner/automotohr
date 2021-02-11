<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Resume_database extends Public_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('resumes_model');
        $this->load->model('application_tracking_system_model');
        $this->load->model('job_listings_visibility_model');
        require_once(APPPATH . 'libraries/aws/aws.php');
    }

    public function index($keywords = 'all', $posted_within = 'all', $country = 'all', $state = 'all', $city = 'all', $zipcode = 'all', $page = 1){
        if ($this->session->userdata('logged_in')) {
            /*** disabling the resume database for now ***/
            redirect('dashboard', 'refresh');
            /*** disabling the resume database for now ***/
            
            $data['session']                                                    = $this->session->userdata('logged_in');
            $security_sid                                                       = $data['session']['employer_detail']['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;
            check_access_permissions($security_details, 'dashboard', 'resume_database'); // First Param: security array, 2nd param: redirect url, 3rd param: function name
            $company_sid                                                        = $data['session']['company_detail']['sid'];
            $employer_sid                                                       = $data['session']['employer_detail']['sid'];
            $data['title']                                                      = 'Resume Database';
            $number_of_results                                                  = 51;
            $offset                                                             = $number_of_results * ($page - 1);

            $pagination_base_url = base_url('resume_database') . '/' . $keywords . '/' . $posted_within . '/' . $country . '/' . $state . '/' . $city . '/' . $zipcode . '/';
            $data['back_to_search'] = $pagination_base_url;
            $keywords = ($keywords == 'all' ? '' : $keywords);
            $posted_within = ($posted_within == 'all' ? '' : $posted_within);
            $country = ($keywords == 'all' ? '' : $country);
            $state = ($state == 'all' ? '' : $state);
            $city = ($city == 'all' ? '' : $city);
            $zipcode = ($zipcode == 'all' ? '' : $zipcode);
            $resumes = array();

            $keywords_segment = $this->uri->segment(2);
            if(!empty($keywords_segment)){
                $resumes = $this->resumes_model->raw_get_all_resumes(0, $number_of_results, $offset, $keywords, $keywords, $country, $state, $city, $zipcode, $posted_within);
                $total_records = $resumes['total_records'];
                $resumes = $resumes['data_rows'];
                $is_first_request = 1;
            } else {
                $total_records = 0;
                $resumes = array();
                $is_first_request = 1;
            }
            
            $data['is_first_request'] = $is_first_request;

            //-----------------------------------Pagination Starts----------------------------//
            $this->load->library('pagination');

            $config = array();
            $config["base_url"] = $pagination_base_url;
            $config["total_rows"] = $total_records;
            $config["per_page"] = $number_of_results;
            $config["uri_segment"] = 8;
            $choice = $config["total_rows"] / $config["per_page"];
            $config["num_links"] = 10;
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
            $data["page_links"] = $this->pagination->create_links();

            //-----------------------------------Pagination Ends-----------------------------//

            $this->session->set_userdata('back_url', current_url());
            $data_countries = db_get_active_countries();
            
            foreach ($data_countries as $value) {
                $data_states[$value['sid']] = db_get_active_states($value['sid']);
            }

            $data['active_countries'] = $data_countries;
            $data['active_states'] = $data_states;
            $data_states_encode = htmlentities(json_encode($data_states));
            $data['states'] = $data_states_encode;
            //$resumes = $this->resumes_model->get_all_resumes($number_of_results, $offset, $where_filters, $like_filters, $where_custom);
            $data['resumes'] = $resumes;
            //echo $this->db->last_query();
            $this->load->view('main/header', $data);
            $this->load->view('resumes/index');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function view($sid = null){
        if ($this->session->userdata('logged_in')) {
            $data['session']                                                    = $this->session->userdata('logged_in');
            $security_sid                                                       = $data['session']['employer_detail']['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;
            check_access_permissions($security_details, 'dashboard', 'resume_database'); // First Param: security array, 2nd param: redirect url, 3rd param: function name

            $company_sid                                                        = $data['session']['company_detail']['sid'];
            $employer_sid                                                       = $data['session']['employer_detail']['sid'];
            $data['title']                                                      = 'View Resume';

            if($sid != null) {
                $resume = $this->resumes_model->raw_get_all_resumes($sid);

                if (!empty($resume)) {
                    $data['resume'] = $resume['data_rows'][0];
                } else {
                    $data['resume'] = array();
                }

                $data_states = array();
                $data_countries = db_get_active_countries();
                foreach ($data_countries as $value) {
                    $data_states[$value['sid']] = db_get_active_states($value['sid']);
                }

                $data['active_countries'] = $data_countries;
                $data['active_states'] = $data_states;
                $data['back_url'] = $this->session->userdata('back_url');

                $this->load->view('main/header', $data);
                $this->load->view('resumes/view');
                $this->load->view('main/footer');
            } else {
                $this->session->set_flashdata('message', 'Resume Not Found');
                redirect('resume_database', 'refresh');
            }
        } else {
            redirect('login', 'location');
        }
    }

    public function save($sid = null){
        if ($this->session->userdata('logged_in')) {
            $data['session']                                                    = $this->session->userdata('logged_in');
            $security_sid                                                       = $data['session']['employer_detail']['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;
            check_access_permissions($security_details, 'dashboard', 'resume_database'); // First Param: security array, 2nd param: redirect url, 3rd param: function name
            $company_sid                                                        = $data['session']['company_detail']['sid'];
            $employer_sid                                                       = $data['session']['employer_detail']['sid'];
            $data['title']                                                      = 'Save Resume';

            if($sid != null) {
                $this->form_validation->set_rules('first_name', 'First Name', 'required|trim|xss_clean');
                $this->form_validation->set_rules('last_name', 'Last Name', 'required|trim|xss_clean');
                $this->form_validation->set_rules('email', 'Email', 'trim|xss_clean|valid_email');
                $this->form_validation->set_rules('phone_number', 'Phone Number', 'trim|xss_clean');
                $this->form_validation->set_rules('city ', 'City', 'trim|xss_clean');
                $this->form_validation->set_rules('address', 'Address', 'trim|xss_clean');
                $this->form_validation->set_rules('country', 'Country', 'trim|xss_clean');
                $this->form_validation->set_rules('state', 'State', 'trim|xss_clean');
                $this->form_validation->set_rules('job_sid', 'Job Sid', 'trim|xss_clean');

                if ($this->form_validation->run() == false) {
                    // do nothing
                } else {
                    $fileUrl = $this->input->post('resume');
                    $fileNameWithExt = $this->input->post('resume_file_name');

                    $resume = '';
                    $cover_letter = '';
                    $profile_picture = '';

                    if($fileUrl != ''){
                        $resume = download_and_upload_file_to_aws($fileUrl, $fileNameWithExt);
                    }

                    $profile_picture_url = $this->input->post('profile_picture_url');
                    $profile_pictureNameWithExt = $this->input->post('profile_picture_name');

                    if ($profile_picture_url != '') {
                        $profile_picture = download_and_upload_file_to_aws($profile_picture_url, $profile_pictureNameWithExt);
                    }

                    $first_name = $this->input->post('first_name');
                    $last_name = $this->input->post('last_name');
                    $email = $this->input->post('email');
                    $phone_number = $this->input->post('phone_number');
                    $city = $this->input->post('city');
                    $address = $this->input->post('address');
                    $country = $this->input->post('country');
                    $state = $this->input->post('state');
                    $job_sid = $this->input->post('job_sid');
                    $youtube_url = $this->input->post('youtube_video');
                    $objective = html_entity_decode($this->input->post('objective_text'));
                    //$keywords = html_entity_decode($this->input->post('keywords_text'));
                    $skills = html_entity_decode($this->input->post('skills_text'));
                    //$custom_cover_letter = PHP_EOL . $objective . PHP_EOL . $keywords . PHP_EOL;
                    $custom_cover_letter = PHP_EOL . $objective . PHP_EOL . $skills . PHP_EOL;

                    if (!empty($custom_cover_letter)) {
                        $custom_cover_letter = str_replace('<br>', PHP_EOL, $custom_cover_letter);
                        $custom_cover_letter = str_replace('<br />', PHP_EOL, $custom_cover_letter);
                        $custom_cover_letter = str_replace('</p>', PHP_EOL, $custom_cover_letter);
                        $cover_letter = create_and_upload_file_to_aws(strip_tags($custom_cover_letter), 'cover_letter_' . clean($first_name) . '_' . clean($last_name) . '.txt');
                    }

                    $data_to_applications = array();
                    $data_to_applications['employer_sid'] = $company_sid;
                    $data_to_applications['first_name'] = $first_name;
                    $data_to_applications['last_name'] = $last_name;
                    $data_to_applications['email'] = $email;
                    $data_to_applications['phone_number'] = $phone_number;
                    $data_to_applications['address'] = $address;
                    $data_to_applications['city'] = $city;
                    $data_to_applications['state'] = $state;
                    $data_to_applications['country'] = $country;
                    $data_to_applications['resume'] = $resume;
                    $data_to_applications['pictures'] = $profile_picture;
                    $data_to_applications['cover_letter'] = $cover_letter;
                    if($youtube_url != '') {
                        $data_to_applications['YouTube_Video'] = get_youtube_video_id_from_url($youtube_url);
                    }


                    $data_to_applicant_jobs_list = array();
                    $data_to_applicant_jobs_list['company_sid'] = $company_sid;
                    $data_to_applicant_jobs_list['job_sid'] = $job_sid;
                    $data_to_applicant_jobs_list['date_applied'] = date('Y-m-d');
                    $data_to_applicant_jobs_list['applicant_type'] = 'Imported Resume';
                    $data_to_applicant_jobs_list['status'] = 'Not Contacted Yet';
                    $data_to_applicant_jobs_list['status_sid'] = 1;


                    $applicant_exists = $this->resumes_model->check_job_applicant($job_sid, $email, $company_sid);


                    if ($applicant_exists == 0) {
                        $this->resumes_model->insert_resume_in_job_application($data_to_applications, $data_to_applicant_jobs_list);
                        $this->session->set_flashdata('message', '<b>Success:</b> New Applicant Added Successfully.');
                        redirect('application_tracking_system/active/all/all/all/all', 'refresh');
                    } else {
                        $this->session->set_flashdata('message', '<b>Error:</b> The Applicant is already in your applicant list.');
                        redirect('resume_database', 'refresh');
                    }
                }

                $resume = $this->resumes_model->raw_get_all_resumes($sid);

                if (!empty($resume)) {
                    $data['resume'] = $resume['data_rows'][0];
                } else {
                    $data['resume'] = array();


                    $this->session->set_flashdata('message', 'Resume Not Found');
                    redirect('resume_database', 'refresh');
                }

                $all_jobs_query = array();

                if (is_admin($employer_sid)) {
                    $all_jobs_query = $this->job_listings_visibility_model->GetAllJobsTitlesCompanySpecific($company_sid);
                } else {
                    $all_jobs_query = $this->job_listings_visibility_model->GetAllJobsTitlesCompanyAndEmployerSpecific($company_sid, $employer_sid);
                }

                $all_jobs = array();
                $all_jobs[] = array("sid" => '', "Title" => 'Please select job');

                //$all_jobs_query = $this->application_tracking_model->get_all_jobs($employer_id);
                foreach ($all_jobs_query as $row) {
                    $all_jobs[] = array("sid" => $row['sid'], "Title" => $row['Title']);
                }

                $data["all_jobs"] = $all_jobs;

                $data_countries = db_get_active_countries();
                foreach ($data_countries as $value) {
                    $data_states[$value['sid']] = db_get_active_states($value['sid']);
                }

                $data['active_countries'] = $data_countries;
                $data['active_states'] = $data_states;
                $data_states_encode = htmlentities(json_encode($data_states));
                $data['states'] = $data_states_encode;

                $data['back_url'] = $this->session->userdata('back_url');

                $this->load->view('main/header', $data);
                $this->load->view('resumes/save');
                $this->load->view('main/footer');
            } else {
                $this->session->set_flashdata('message', 'Resume Not Found');
                redirect('resume_database', 'refresh');
            }
        } else {
            redirect('login', 'location');
        }
    }

    public function print_resume($sid = null){
        if ($this->session->userdata('logged_in')) {
            $data['session']                                                    = $this->session->userdata('logged_in');
            $security_sid                                                       = $data['session']['employer_detail']['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;
            check_access_permissions($security_details, 'dashboard', 'resume_database'); // First Param: security array, 2nd param: redirect url, 3rd param: function name

            $company_sid                                                        = $data['session']['company_detail']['sid'];
            $employer_sid                                                       = $data['session']['employer_detail']['sid'];
            $data['title']                                                      = 'Print Resume';

            if($sid != null) {
                $resume = $this->resumes_model->raw_get_all_resumes($sid);

                if (!empty($resume)) {
                    $data['resume'] = $resume['data_rows'][0];
                } else {
                    $data['resume'] = array();
                }

                $data_states = array();
                $data_countries = db_get_active_countries();
                foreach ($data_countries as $value) {
                    $data_states[$value['sid']] = db_get_active_states($value['sid']);
                }

                $data['active_countries'] = $data_countries;
                $data['active_states'] = $data_states;
                $this->load->view('resumes/print', $data);

            }else{
                $this->session->set_flashdata('message', 'Resume Not Found');
                redirect('resume_database', 'refresh');
            }
        } else {
            redirect('login', 'location');
        }
    }
}