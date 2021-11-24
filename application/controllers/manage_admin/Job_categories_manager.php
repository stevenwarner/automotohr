<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Job_categories_manager extends Admin_Controller {
    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->library('pagination');
        $this->load->model('manage_admin/job_categories_manager_model');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    private function check_category_uniqueness($category_name) {
        
    }

    public function index($page_number = 0) {
        $redirect_url = 'manage_admin';
        $function_name = 'job_categories_manager';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->form_validation->set_rules('perform_action', 'Perform Action', 'required|xss_clean|trim');

        if ($this->form_validation->run() == false) {
            $this->data['page_title'] = 'Job Categories Manager';
            $this->data['letter'] = '';
            $categories = $this->job_categories_manager_model->get_all_system_job_categories();
            $total_categories = count($categories);
            $per_page = 50;

            if ($page_number > 1) {
                $page_number = $page_number - 1;
            }

            $offset = $page_number * $per_page;
            $categories = $this->job_categories_manager_model->get_all_system_job_categories($per_page, $offset);
            $this->data['categories'] = $categories;
            $config = array(); //Pagination
            $config['base_url'] = base_url('manage_admin/job_categories_manager');
            $config['total_rows'] = $total_categories;
            $config['per_page'] = $per_page;
            $config['uri_segment'] = 3;
            $config['num_links'] = 9;
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
            $config['cur_tag_open'] = '<li class="active"><a href="javascript:void(0);">';
            $config['cur_tag_close'] = '</a></li>';
            $config['num_tag_open'] = '<li class="page">';
            $config['num_tag_close'] = '</li>';
            $this->pagination->initialize($config);
            $this->data['page_links'] = $this->pagination->create_links();
            $this->render('manage_admin/job_categories_manager/index', 'admin_master');
        } else {
            $perform_action = $this->input->post('perform_action');
            
            switch ($perform_action) {
                case 'delete_category':
                    $category_sid = $this->input->post('category_sid');
                    $this->job_categories_manager_model->delete_system_job_category($category_sid);
                    $this->session->set_flashdata('message', '<strong>Success: </strong>Job Listing Category Successfully Deleted!');
                    redirect('manage_admin/job_categories_manager', 'refresh');
                    break;
            }
        }
    }

    public function appendix($latter = 'a') {
        $redirect_url = 'manage_admin';
        $function_name = 'job_categories_manager';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->form_validation->set_rules('perform_action', 'Perform Action', 'required|xss_clean|trim');
        
        if ($this->form_validation->run() == false) {
            $this->data['page_title'] = 'Job Categories Manager';
            $categories = $this->job_categories_manager_model->get_all_system_job_categories_through_index($latter);
            $this->data['categories'] = $categories;
            $this->data['page_links'] = '';
            $this->data['appendix'] = true;
            $this->data['letter'] = $latter;
            $this->render('manage_admin/job_categories_manager/index', 'admin_master');
        } else {
            $perform_action = $this->input->post('perform_action');

            switch ($perform_action) {
                case 'delete_category':
                    $category_sid = $this->input->post('category_sid');
                    $this->job_categories_manager_model->delete_system_job_category($category_sid);
                    $this->session->set_flashdata('message', '<strong>Success: </strong>Job Listing Category Successfully Deleted!');
                    redirect('manage_admin/job_categories_manager', 'refresh');
                    break;
            }
        }
    }

    public function add_job_category() {
        $redirect_url = 'manage_admin/job_categories_manager';
        $function_name = 'add_job_category';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->form_validation->set_rules('category_name', 'Category Name', 'required|trim|xss_clean|is_unique[listing_field_list.value]');

        if ($this->form_validation->run() == false) {
            $job_job_category_industries = $this->job_categories_manager_model->get_all_job_category_industries();
            $this->data['job_job_category_industries'] = $job_job_category_industries;
            $this->data['page_title'] = 'Job Categories Manager';
            $this->render('manage_admin/job_categories_manager/add_job_category', 'admin_master');
        } else {
            $category_name = $this->input->post('category_name');
            $category_group_sid = $this->input->post('category_group_sid');
            $this->job_categories_manager_model->insert_new_system_job_category($category_name);
            $this->session->set_flashdata('message', '<strong>Success:</strong> Job Listing Category Successfully Created.');
            redirect('manage_admin/job_categories_manager');
        }
    }

    public function edit_job_category($category_sid = 0) {
        $redirect_url = 'manage_admin/job_categories_manager';
        $function_name = 'add_job_category';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $job_category = $this->job_categories_manager_model->get_system_job_category($category_sid);
        $this->form_validation->set_rules('category_name', 'Category Name', 'required|trim|xss_clean');
        $this->form_validation->set_rules('category_group_sid', 'Category Group', 'trim|xss_clean');

        if ($this->form_validation->run() == false) {
            $this->data['page_title'] = 'Job Categories Manager';
            $this->data['job_category'] = $job_category;
            $this->render('manage_admin/job_categories_manager/add_job_category', 'admin_master');
        } else {
            $category_name = $this->input->post('category_name');
            $category_exists = $this->job_categories_manager_model->check_if_job_category_exists(0, $category_name);

            if (strtolower($job_category['value']) == strtolower($category_name)) {
                $this->job_categories_manager_model->update_system_job_category($category_sid, $category_name);
                $this->session->set_flashdata('message', '<strong>Success:</strong> Job Listing Category Successfully Updated.');
                redirect('manage_admin/job_categories_manager');
            } else {
                if ($category_exists == true) {
                    $this->session->set_flashdata('message', '<strong>Error :</strong> Category already exists!');
                    redirect('manage_admin/job_categories_manager/edit_job_category/' . $category_sid, 'refresh');
                } else {
                    $this->job_categories_manager_model->update_system_job_category($category_sid, $category_name);
                    $this->session->set_flashdata('message', '<strong>Success:</strong> Job Listing Category Successfully Updated.');
                    redirect('manage_admin/job_categories_manager');
                }
            }
        }
    }

    public function job_category_industries() {
        $redirect_url = 'manage_admin';
        $function_name = 'job_categories_industries';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $job_category_industries = $this->job_categories_manager_model->get_all_job_category_industries();
        $this->data['job_category_industries'] = $job_category_industries;

        $this->data['page_title'] = 'Job Category Industries';
        $this->render('manage_admin/job_categories_manager/job_category_industries', 'admin_master');
    }

    public function add_job_category_industry() {
        $redirect_url = 'manage_admin';
        $function_name = 'job_categories_manager';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name

        $this->form_validation->set_rules('industry_name', 'Industry Name', 'required|xss_clean|trim');
        $this->form_validation->set_rules('short_description', 'Description', 'required|xss_clean|trim');

        if ($this->form_validation->run() == false) {
            $this->data['page_title'] = 'Add New Job Category Industries';
            $this->render('manage_admin/job_categories_manager/add_job_category_industry', 'admin_master');
        } else {
            $industry_name = $this->input->post('industry_name');
            $short_description = $this->input->post('short_description');
            $data_to_save = array();
            $data_to_save['industry_name'] = $industry_name;
            $data_to_save['short_description'] = $short_description;
            $data_to_save['status'] = 1;
            $this->job_categories_manager_model->add_job_category_industry($data_to_save);
            $this->session->set_flashdata('message', '<strong>Success</strong> Category Group Added!');
            redirect('manage_admin/job_categories_manager/job_category_industries', 'refresh');
        }
    }

    public function edit_job_category_industry($sid = null) {
        $redirect_url = 'manage_admin';
        $function_name = 'job_categories_manager';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name

        $this->form_validation->set_rules('industry_name', 'Industry Name', 'required|xss_clean|trim');
        $this->form_validation->set_rules('short_description', 'Description', 'required|xss_clean|trim');

        if ($this->form_validation->run() == false) {
            if ($sid != null && $sid > 0) {
                $job_category_industry = $this->job_categories_manager_model->get_job_category_industry($sid);
                $this->data['job_category_industry'] = $job_category_industry;
                $this->data['page_title'] = 'Edit Job Category Industries';
                $this->render('manage_admin/job_categories_manager/add_job_category_industry', 'admin_master');
            } else {
                $this->session->set_flashdata('message', '<strong>Error :</strong> Job Category Group Not Found!');
                redirect('manage_admin/job_categories_manager/job_category_industries');
            }
        } else {
            $industry_name = $this->input->post('industry_name');
            $short_description = $this->input->post('short_description');
            $data_to_update = array();
            $data_to_update['industry_name'] = $industry_name;
            $data_to_update['short_description'] = $short_description;
            $data_to_update['status'] = 1;
            $this->job_categories_manager_model->update_job_category_industry($sid, $data_to_update);
            $this->session->set_flashdata('message', '<strong>Success</strong> Category Group Updated!');
            redirect('manage_admin/job_categories_manager/job_category_industries', 'refresh');
        }
    }

    public function assign_categories($industry_sid = null) {
        $redirect_url = 'manage_admin/job_categories_manager';
        $function_name = 'add_job_category';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->form_validation->set_rules('industry_sid', 'Industry sid', 'required|xss_clean|trim');
        
        if ($this->form_validation->run() == false) {
            $industry = $this->job_categories_manager_model->get_job_category_industries($industry_sid);
            $this->data['industry'] = $industry;
            $categories = $this->job_categories_manager_model->get_all_system_job_categories();
            $this->data['categories'] = $categories;
            $industry_specific_categories = $this->job_categories_manager_model->job_industry_categories($industry_sid, 0);
            $this->data['industry_specific_categories'] = $industry_specific_categories;
            $industry_specific_cat_sids = array();

            foreach ($industry_specific_categories as $category) {
                $industry_specific_cat_sids[] = $category['sid'];
            }

            $this->data['industry_specific_cat_sids'] = $industry_specific_cat_sids;
            $this->data['page_title'] = 'Job Categories Manager';
            $this->render('manage_admin/job_categories_manager/assign_categories', 'admin_master');
        } else {
            $industry_sid = $this->input->post('industry_sid');
            $categories = $this->input->post('categories');
            $data_to_insert = array();
            
            if(!empty($categories)){
                foreach($categories as $category){
                    $data = array();
                    $data['industry_sid'] = $industry_sid;
                    $data['category_sid'] = $category;
                    $data_to_insert[] = $data;
                }
            }

            $this->job_categories_manager_model->batch_insert_categories_to_industry($industry_sid, $data_to_insert, true);
            $this->session->set_flashdata('message', '<strong>Success :</strong> Categories Assignment successful!');
            redirect('manage_admin/job_categories_manager/job_category_industries/', 'refresh');
        }
    }

    //
    function delete_industry($industryId){
        // Fetch the industry
        $industry = $this->job_categories_manager_model->get_job_category_industry($industryId);
        $this->job_categories_manager_model->InsertIndustryToDelete($industry);
        $this->job_categories_manager_model->DeleteIndustry($industryId);
        //
        echo 'success';
    }


}
