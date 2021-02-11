<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Job_listing_categories_manager extends Public_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('dashboard_model');
        $this->load->model('job_listing_categories_model');
        $this->load->library("pagination");
    }

    public function index() { 
        if ($this->session->userdata('logged_in')) {
            $data['title']                                                      = "Job Listing Categories Manager";
            $data['session']                                                    = $this->session->userdata('logged_in');
            $security_sid                                                       = $data['session']['employer_detail']['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details; 
            check_access_permissions($security_details, 'my_settings', 'job_listing_categories_manager'); // Param2: Redirect URL, Param3: Function Name
            $company_id                                                         = $data['session']['company_detail']['sid'];
            $company_name                                                       = $data['session']['company_detail']['CompanyName'];
            $employer_id                                                        = $data["session"]["employer_detail"]["sid"];
            $data['employerData']                                               = $this->dashboard_model->getEmployerDetail($employer_id);
            $categories_count                                                   = $this->job_listing_categories_model->GetAllCount($company_id);
            
            $records_per_page                                                   = PAGINATION_RECORDS_PER_PAGE;
            $page                                                               = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0; 
            $my_offset                                                          = 0;
            
            if($page > 1) {
                $my_offset                                                      = ($page - 1) * $records_per_page;
            } 

            $baseUrl                                                            = base_url('job_listing_categories');
            $uri_segment                                                        = 2; 
            $config                                                             = array();
            $config["base_url"]                                                 = $baseUrl;
            $config["total_rows"]                                               = $categories_count;
            $config["per_page"]                                                 = $records_per_page;
            $config['uri_segment']                                              = $uri_segment;
            $choice                                                             = $config["total_rows"] / $config["per_page"];
            $config['num_links']                                                = ceil($choice);
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
            $categories                                                         = $this->job_listing_categories_model->GetAll($company_id, $records_per_page, $my_offset);
            $data['categories']                                                 = $categories;
            $this->form_validation->set_rules('action', 'Action', 'required');
            
            if($this->form_validation->run() === FALSE){                
                $this->load->view('main/header', $data);
                $this->load->view('job_listing_categories_manager/index');
                $this->load->view('main/footer');
            } else {
                $action                                                         = $this->input->post('action');
                if($action == 'delete_category'){
                    $sid                                                        = $this->input->post('sid');
                    $this->job_listing_categories_model->DeleteSingle($sid);
                    redirect('job_listing_categories');
                }
            }
        } else {
            redirect('login', "refresh");
        }
    }

    public function add_edit($sid = null){
        if($this->session->userdata('logged_in')) {
            $data['session']                                                    = $this->session->userdata('logged_in');
            $security_sid                                                       = $data['session']['employer_detail']['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;
            check_access_permissions($security_details, 'my_settings', 'job_listing_categories_manager'); // Param2: Redirect URL, Param3: Function Name

            $data['title']                                                      = 'Add New Job Listing Categories';
            $company_id                                                         = $data['session']['company_detail']['sid'];
            $employer_id                                                        = $data["session"]["employer_detail"]["sid"];
            $data['employerData']                                               = $this->dashboard_model->getEmployerDetail($employer_id);
            $data['companyData']                                                = $this->dashboard_model->getCompanyDetail($company_id);
            $data['companyData']['locationDetail']                              = db_get_state_name($data['companyData']['Location_State']);
            $categoryInfo                                                       = array();
            
            if($sid != null){
                $data['title']                                                  = 'Edit Job Listing Categories';
                $categoryInfo                                                   = $this->job_listing_categories_model->GetSingle($sid);
                
                if(!empty($categoryInfo)){
                    $categoryInfo                                               = $categoryInfo[0];
                    $data['categoryInfo']                                       = $categoryInfo;
                }
            }

            $stored_categoryName                                                = '';
            
            if(!empty($categoryInfo)){
                $stored_categoryName                                            = $categoryInfo['value'];
            }

            if($stored_categoryName != $this->input->post('category')) {
                $this->form_validation->set_rules('category', 'Category', 'required|callback_check_listing_category|trim');
            } else {
                $this->form_validation->set_rules('category', 'Category', 'required|trim');
            }

            if($this->form_validation->run() === FALSE){
                $this->load->view('main/header', $data);
                $this->load->view('job_listing_categories_manager/add_edit');
                $this->load->view('main/footer');
            } else {
                $categoryName                                                   = $this->input->post('category');
                $sortOrder                                                      = $this->input->post('sort_order');
                $sid                                                            = $this->input->post('sid');

                if($sid == ''){
                    $sid                                                        = null;
                    $this->session->set_flashdata('message ', '<b>Success:</b> New category added successfully.');
                } else {
                    $this->session->set_flashdata('message ', '<b>Success:</b> Category name changed successfully.');
                }

                $this->job_listing_categories_model->Save($sid, $company_id, $employer_id, 198, $categoryName, $sortOrder);
                redirect('job_listing_categories', 'refresh');
            }
        } else {
            redirect('login', "refresh");
        }
    }

    function check_listing_category($str) {
        $data['session']                                                        = $this->session->userdata('logged_in');
        $company_id                                                             = $data['session']['company_detail']['sid'];
        $this->db->where('company_sid', $company_id);
        $this->db->where('field_type', 'custom');
        $this->db->where('value', $str);
        $categoryInfo                                                           = $this->db->get('listing_field_list')->result_array();
        $return                                                                 = FALSE;

        if (empty($categoryInfo)) {
            $return = TRUE;
        }

        $this->form_validation->set_message('check_listing_category', 'Category Already Exists!');
        return $return;
    }
}