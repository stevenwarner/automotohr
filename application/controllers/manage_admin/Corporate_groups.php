<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Corporate_groups extends Admin_Controller {
    function __construct() {
        parent::__construct();
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        $this->load->model('manage_admin/automotive_groups_model');
        $this->load->library("pagination");
    }

    public function index($page_number = 1) {
        $redirect_url = 'manage_admin';
        $function_name = 'automotive_groups';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name

        //Pagination start
        $records_per_page = PAGINATION_RECORDS_PER_PAGE;
        $my_offset = 0;
        if($page_number > 1){
            $my_offset = ($page_number - 1) * $records_per_page;
        }

        $count = $this->automotive_groups_model->get_automotive_group_records(0,0,[],[],1);
        $baseUrl = base_url('manage_admin/corporate_groups');
        $uri_segment = 3;
        $config = array();
        $config["base_url"] = $baseUrl;
        $config["total_rows"] = $count;
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
        $this->data["links"] = $this->pagination->create_links();
        $this->data['current_page'] = $page_number;
        //Pagination End

        $this->data['groups'] = $this->ion_auth->groups()->result();
        $this->form_validation->set_rules('perform_action', 'Perform Action', 'required|trim|xss_clean');
        $this->form_validation->set_rules('automotive_group_sid', 'Automotive Group SID', 'required|trim|xss_clean');

        if ($this->form_validation->run() == false) {
            $automotive_groups = $this->automotive_groups_model->get_automotive_group_records($records_per_page, $my_offset,[],[],0);
            $this->data['automotive_groups'] = $automotive_groups;
            $this->render('manage_admin/automotive_groups/index', 'admin_master');
        } else {
            $perform_action = $this->input->post('perform_action');
            $automotive_group_sid = $this->input->post('automotive_group_sid');

            switch ($perform_action) {
                case 'delete_automotive_group':
                    $this->automotive_groups_model->delete_automotive_group($automotive_group_sid);
                    $this->session->set_flashdata('message', '<strong>Success:</strong> Member company successfully deleted.');
                    break;
            }
        }
    }

    public function add() {
        $redirect_url = 'manage_admin';
        $function_name = 'manage_automotive_groups';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        $this->data['groups'] = $this->ion_auth->groups()->result();
        $this->data['page_title'] = 'Add New Corporate Group';
        $this->data['active_countries'] = $this->automotive_groups_model->get_active_countries();

        $this->form_validation->set_rules('group_name', 'Group Name', 'required|trim|is_unique[automotive_groups.group_name]');
        $this->form_validation->set_rules('group_country', 'Group Country', 'required|trim');
        $this->form_validation->set_rules('group_description', 'Group Description', 'trim');


        if ($this->form_validation->run() == false) {
            $this->data['automotive_group']['group_country_sid'] = $this->input->post('group_country');
            $this->render('manage_admin/automotive_groups/add_edit', 'admin_master');
        } else {
            $group_name = $this->input->post('group_name');
            $group_country = $this->input->post('group_country');
            $group_description = $this->input->post('group_description');

            $data_to_save = array();
            $data_to_save['group_name'] = $group_name;
            $data_to_save['group_country_sid'] = $group_country;
            $data_to_save['group_description'] = $group_description;

            $this->automotive_groups_model->insert_automotive_group_record($data_to_save);
            $this->session->set_flashdata('message', '<strong>Success</strong>: Corporate Group Added!');
            redirect('manage_admin/corporate_groups', 'refresh');
        }       
    }

    public function edit($automotive_group_sid = 0) {
        $redirect_url = 'manage_admin';
        $function_name = 'manage_automotive_groups';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name

        if ($automotive_group_sid > 0) {
            $this->data['groups'] = $this->ion_auth->groups()->result();
            $this->data['page_title'] = 'Edit Corporate Group';
            $where_filters = array();
            $where_filters['sid'] = $automotive_group_sid;
            $limit = 0;
            $offset = 0;
            $group_record = $this->automotive_groups_model->get_automotive_group_records($limit, $offset, $where_filters);
            $this->data['active_countries'] = $this->automotive_groups_model->get_active_countries();

            if (!empty($group_record)) {
                $this->data['automotive_group'] = $group_record[0];
                $previous_corp_company = $group_record[0]['corporate_company_sid'];

                if ($_POST) {
                    if ($this->input->post('group_name') == $group_record[0]['group_name']) {
                        $this->form_validation->set_rules('group_name', 'Group Name', 'required|trim');
                    } else {
                        $this->form_validation->set_rules('group_name', 'Group Name', 'required|trim|is_unique[automotive_groups.group_name]');
                    }
                }
                
                $this->form_validation->set_rules('group_country', 'Group Country', 'required|trim');
                $this->form_validation->set_rules('group_description', 'Group Description', 'trim');

                if ($this->form_validation->run() == false) {
                    $corporate_companies = $this->automotive_groups_model->get_all_corporate_companies($previous_corp_company);
                    $this->data['corporate_companies'] = $corporate_companies;
                    $this->render('manage_admin/automotive_groups/add_edit', 'admin_master');
                } else {
                    $group_name = $this->input->post('group_name');
                    $group_country = $this->input->post('group_country');
                    $group_description = $this->input->post('group_description');

                    $data_to_save = array();
                    $data_to_save['group_name'] = $group_name;
                    $data_to_save['group_country_sid'] = $group_country;
                    $data_to_save['group_description'] = $group_description;

                    $this->automotive_groups_model->update_automotive_group_record($automotive_group_sid, $data_to_save);
                    $this->session->set_flashdata('message', '<strong>Success</strong>: Corporate Group Info Modified!');
                    redirect('manage_admin/automotive_groups', 'refresh');
                }               
            } else {
                $this->session->set_flashdata('message', '<strong>Error:</strong> Automotive Group Not Found!');
                redirect('manage_admin/corporate_groups', 'refresh');
            }
        } else {
            $this->session->set_flashdata('message', '<strong>Error:</strong> Automotive Group Not Found!');
            redirect('manage_admin/corporate_groups', 'refresh');
        }
    }

    public function member_companies($automotive_group_sid = 0) {
        $redirect_url = 'manage_admin';
        $function_name = 'automotive_member_companies';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name

        if ($automotive_group_sid > 0) {
            $admin_id = $this->session->userdata('user_id');
            $security_details = db_get_admin_access_level_details($admin_id);
            $this->data['security_details'] = $security_details;
            //check_access_permissions($security_details, 'manage_admin', 'invoices_panel'); // Param2: Redirect URL, Param3: Function Name
            $this->data['groups'] = $this->ion_auth->groups()->result();
            $where_filters = array();
            $where_filters['sid'] = $automotive_group_sid;
            $group_record = $this->automotive_groups_model->get_automotive_group_records(0, 0, $where_filters);

            if (!empty($group_record)) {
                $this->data['automotive_group'] = $group_record[0];
                $this->data['page_title'] = 'Member Companies Of Group : ';
                $where_filters = array();
                $where_filters['automotive_group_sid'] = $automotive_group_sid;
                $this->form_validation->set_rules('perform_action', 'Perform Action', 'required|trim|xss_clean');
                $this->form_validation->set_rules('member_company_sid', 'member_company_sid', 'required|trim|xss_clean');

                if ($this->form_validation->run() == false) {
                    $limit = 0;
                    $offset = 0;
                    $member_companies = $this->automotive_groups_model->get_automotive_group_member_companies($limit, $offset, $where_filters);
                    $this->data['member_companies'] = $member_companies;
                    $this->render('manage_admin/automotive_groups/member_companies', 'admin_master');
                } else {
                    $perform_action = $this->input->post('perform_action');
                    $member_company_sid = $this->input->post('member_company_sid');
                    switch ($perform_action) {
                        case 'delete_member_company':
                            $this->automotive_groups_model->delete_member_company_record($member_company_sid);
                            $this->session->set_flashdata('message', '<strong>Success:</strong> Member company successfully deleted.');
                            redirect('manage_admin/automotive_groups/member_companies/' . $automotive_group_sid, 'refresh');
                            break;
                    }
                }                
            } else {
                $this->session->set_flashdata('message', '<strong>Error:</strong> Automotive Group Not Found!');
                redirect('manage_admin/corporate_groups', 'refresh');
            }
        } else {
            $this->session->set_flashdata('message', '<strong>Error:</strong> Automotive Group Not Found!');
            redirect('manage_admin/corporate_groups', 'refresh');
        }
    }

    public function add_member_company($automotive_group_sid = 0) {
        $redirect_url = 'manage_admin';
        $function_name = 'automotive_member_companies';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name

        if ($automotive_group_sid > 0) {
            $admin_id = $this->session->userdata('user_id');
            $security_details = db_get_admin_access_level_details($admin_id);
            $this->data['security_details'] = $security_details;
            //check_access_permissions($security_details, 'manage_admin', 'invoices_panel'); // Param2: Redirect URL, Param3: Function Name
            $this->data['groups'] = $this->ion_auth->groups()->result();
            $where_filters = array();
            $where_filters['sid'] = $automotive_group_sid;
            $group_record = $this->automotive_groups_model->get_automotive_group_records(0, 0, $where_filters);
            $data_countries = db_get_active_countries();
            
            foreach ($data_countries as $value) {
                $data_states[$value['sid']] = db_get_active_states($value['sid']);
            }

            $this->data['active_countries'] = $data_countries;
            $this->data['active_states'] = $data_states;
            $data_states_encode = htmlentities(json_encode($data_states));
            $this->data['states'] = $data_states_encode;
            $companies = $this->automotive_groups_model->get_all_companies();
            $this->data['companies'] = $companies;
            $oem_brands = $this->automotive_groups_model->get_all_oem_brands();
            $this->data['oem_brands'] = $oem_brands;
            $this->data['automotive_group_sid'] = $automotive_group_sid;

            if (!empty($group_record)) {
                $required_fields = 'required|';
                $company_is_registered = 'no';
                
                if(isset($_POST) && !empty($_POST)){
                    $company_is_registered = $_POST['company_is_registered'];
                    
                    if($company_is_registered=='yes'){ // it means it is not regstered at AHR
                       $required_fields = ''; 
                    }
                }
                
                $this->data['automotive_group'] = $group_record[0];
                $this->data['page_title'] = 'Add Member Company To Group :';
                $this->form_validation->set_rules('company_name', 'Company Name', $required_fields.'trim|xss_clean');
                $this->form_validation->set_rules('location_country', 'Country', $required_fields.'trim|xss_clean');
                $this->form_validation->set_rules('location_state', 'State', $required_fields.'trim|xss_clean');
                $this->form_validation->set_rules('location_address', 'Address', $required_fields.'trim|xss_clean');
                $this->form_validation->set_rules('location_city', 'City', $required_fields.'trim|xss_clean');
                $this->form_validation->set_rules('location_zipcode', 'Zipcode', 'trim|xss_clean');
                $this->form_validation->set_rules('pri_contact_name', 'Primary Contact Name', 'trim|xss_clean');
                $this->form_validation->set_rules('pri_contact_phone', 'Primary Contact Phone', 'trim|xss_clean');
                $this->form_validation->set_rules('pri_contact_email', 'Primary Contact Email', 'trim|xss_clean|valid_email');
                $this->form_validation->set_rules('sec_contact_name', 'Secondary Contact Name', 'trim|xss_clean');
                $this->form_validation->set_rules('sec_contact_phone', 'Secondary Contact Phone', 'trim|xss_clean');
                $this->form_validation->set_rules('sec_contact_email', 'Secondary Contact Email', 'trim|xss_clean|valid_email');
                $this->form_validation->set_rules('is_registered_in_ahr', 'Is Registered In ' . STORE_NAME, 'trim|xss_clean');
                $this->form_validation->set_rules('company_sid', 'Company', 'trim|xss_clean');
                $this->form_validation->set_rules('short_description', 'Notes', 'trim|xss_clean');
                $this->form_validation->set_rules('automotive_group_sid', 'Group', 'trim|xss_clean');

                if ($this->form_validation->run() == false) {
                    $this->render('manage_admin/automotive_groups/add_edit_member_company_new', 'admin_master');
                } else {
                    $company_name = $this->input->post('company_name');
                    $location_country = $this->input->post('location_country');
                    $location_state = $this->input->post('location_state');
                    $location_address = $this->input->post('location_address');
                    $location_city = $this->input->post('location_city');
                    $location_zipcode = $this->input->post('location_zipcode');
                    $pri_contact_name = $this->input->post('pri_contact_name');
                    $pri_contact_phone = $this->input->post('pri_contact_phone');
                    $pri_contact_email = $this->input->post('pri_contact_email');
                    $sec_contact_name = $this->input->post('sec_contact_name');
                    $sec_contact_phone = $this->input->post('sec_contact_phone');
                    $sec_contact_email = $this->input->post('sec_contact_email');
                    $is_registered_in_ahr = $this->input->post('is_registered_in_ahr');
                    $company_sid = $this->input->post('company_sid');
                    $short_description = $this->input->post('short_description');
                    $automotive_group_sid = $this->input->post('automotive_group_sid');

                    if ($is_registered_in_ahr == '' || $is_registered_in_ahr == null || empty($is_registered_in_ahr)) {
                        $is_registered_in_ahr = 0;
                    }

                    if ($company_sid == '' || $company_sid == null || empty($company_sid)) {
                        $company_sid = 0;
                    }
                    
                    // check if the company already exists in the group
                    $company_exists_in_group = $this->automotive_groups_model->check_company_exists_in_group($company_sid, $automotive_group_sid);
                    
                    if(!empty($company_exists_in_group)){
                        $this->session->set_flashdata('message', '<strong>Error: </strong> Member company already exists in the group.');
                        redirect('manage_admin/corporate_groups/member_companies/' . $automotive_group_sid, 'refresh');
                    }
                    
                    
                    if($company_is_registered=='yes'){
                        $company_details_db = $this->automotive_groups_model->get_company_details($company_sid);
                        $company_name = $company_details_db[0]['CompanyName'];
                        $location_country = $company_details_db[0]['Location_Country'];
                        $location_state = $company_details_db[0]['Location_State'];
                        $location_address = $company_details_db[0]['Location_Address'];
                        $location_city = $company_details_db[0]['Location_City'];
                        $location_zipcode = $company_details_db[0]['Location_ZipCode'];
                        $pri_contact_name = $company_details_db[0]['ContactName'];
                        $pri_contact_phone = $company_details_db[0]['PhoneNumber'];
                        $sec_contact_name = $company_details_db[0]['accounts_contact_person'];
                        $sec_contact_phone = $company_details_db[0]['accounts_contact_number'];
                    }

                    $data_to_insert = array();
                    $data_to_insert['company_name'] = $company_name;
                    $data_to_insert['location_country'] = $location_country;
                    $data_to_insert['location_state'] = $location_state;
                    $data_to_insert['location_address'] = $location_address;
                    $data_to_insert['location_city'] = $location_city;
                    $data_to_insert['location_zipcode'] = $location_zipcode;
                    $data_to_insert['pri_contact_name'] = $pri_contact_name;
                    $data_to_insert['pri_contact_phone'] = $pri_contact_phone;
                    $data_to_insert['pri_contact_email'] = $pri_contact_email;
                    $data_to_insert['sec_contact_name'] = $sec_contact_name;
                    $data_to_insert['sec_contact_phone'] = $sec_contact_phone;
                    $data_to_insert['sec_contact_email'] = $sec_contact_email;
                    $data_to_insert['is_registered_in_ahr'] = $is_registered_in_ahr;
                    $data_to_insert['company_sid'] = $company_sid;
                    $data_to_insert['short_description'] = $short_description;
                    $data_to_insert['automotive_group_sid'] = $automotive_group_sid;

                    $this->automotive_groups_model->insert_member_company_record($data_to_insert);
                    $this->session->set_flashdata('message', '<strong>Success: </strong> Member company added successfully.');
                    redirect('manage_admin/corporate_groups/member_companies/' . $automotive_group_sid, 'refresh');
                }                
            } else {
                $this->session->set_flashdata('message', '<strong>Error:</strong> Automotive Group Not Found!');
                redirect('manage_admin/corporate_groups', 'refresh');
            }
        } else {
            $this->session->set_flashdata('message', '<strong>Error:</strong> Automotive Group Not Found!');
            redirect('manage_admin/corporate_groups', 'refresh');
        }
    }

    public function edit_member_company($automotive_group_sid = 0, $member_company_sid = 0) {
        $redirect_url = 'manage_admin';
        $function_name = 'automotive_member_companies';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name

        if ($automotive_group_sid > 0 && $member_company_sid > 0) {
            $admin_id = $this->session->userdata('user_id');
            $security_details = db_get_admin_access_level_details($admin_id);
            $this->data['security_details'] = $security_details;
            //check_access_permissions($security_details, 'manage_admin', 'invoices_panel'); // Param2: Redirect URL, Param3: Function Name
            $this->data['groups'] = $this->ion_auth->groups()->result();
            $where_filters = array();
            $where_filters['sid'] = $automotive_group_sid;
            $group_record = $this->automotive_groups_model->get_automotive_group_records(0, 0, $where_filters);
            //Get Countries and States - Start
            $data_countries = db_get_active_countries();

            foreach ($data_countries as $value) {
                $data_states[$value['sid']] = db_get_active_states($value['sid']);
            }

            $this->data['active_countries'] = $data_countries;
            $this->data['active_states'] = $data_states;
            $data_states_encode = htmlentities(json_encode($data_states));
            $this->data['states'] = $data_states_encode;
            $companies = $this->automotive_groups_model->get_all_companies();
            $this->data['companies'] = $companies;
            $oem_brands = $this->automotive_groups_model->get_all_oem_brands();
            $this->data['oem_brands'] = $oem_brands;
            $this->data['automotive_group_sid'] = $automotive_group_sid;

            if (!empty($group_record)) {
                $required_fields = 'required|';
                $company_is_registered = 'no';
                
                if(isset($_POST) && !empty($_POST)){
                    $company_is_registered = $_POST['company_is_registered'];
                    
                    if($company_is_registered=='yes'){ // it means it is not regstered at AHR
                       $required_fields = ''; 
                    }
                }
                
                $this->data['automotive_group'] = $group_record[0];
                $this->data['page_title'] = 'Edit Member Company of Group : ';
                $where_filters = array();
                $where_filters['sid'] = $member_company_sid;
                $where_filters['automotive_group_sid'] = $automotive_group_sid;
                $limit = 0;
                $offset = 0;
                $member_company_info = $this->automotive_groups_model->get_automotive_group_member_companies($limit, $offset, $where_filters);

                if (!empty($member_company_info)) {
                    $this->data['member_company_info'] = $member_company_info[0];
                } else {
                    $this->data['member_company_info'] = array();
                }

                
                $this->form_validation->set_rules('company_name', 'Company Name', $required_fields.'trim|xss_clean');
                $this->form_validation->set_rules('location_country', 'Country', $required_fields.'trim|xss_clean');
                $this->form_validation->set_rules('location_state', 'State', $required_fields.'trim|xss_clean');
                $this->form_validation->set_rules('location_address', 'Address', $required_fields.'trim|xss_clean');
                $this->form_validation->set_rules('location_city', 'City', $required_fields.'trim|xss_clean');                
                $this->form_validation->set_rules('location_zipcode', 'Zipcode', 'trim|xss_clean');
                $this->form_validation->set_rules('pri_contact_name', 'Primary Contact Name', 'trim|xss_clean');
                $this->form_validation->set_rules('pri_contact_phone', 'Primary Contact Phone', 'trim|xss_clean');
                $this->form_validation->set_rules('pri_contact_email', 'Primary Contact Email', 'trim|xss_clean|valid_email');
                $this->form_validation->set_rules('sec_contact_name', 'Secondary Contact Name', 'trim|xss_clean');
                $this->form_validation->set_rules('sec_contact_phone', 'Secondary Contact Phone', 'trim|xss_clean');
                $this->form_validation->set_rules('sec_contact_email', 'Secondary Contact Email', 'trim|xss_clean|valid_email');
                $this->form_validation->set_rules('is_registered_in_ahr', 'Is Registered In ' . STORE_NAME, 'trim|xss_clean');
                $this->form_validation->set_rules('company_sid', 'Company', 'trim|xss_clean');
                $this->form_validation->set_rules('short_description', 'Notes', 'trim|xss_clean');
                $this->form_validation->set_rules('automotive_group_sid', 'Group', 'trim|xss_clean');

                if ($this->form_validation->run() == false) {
                    $this->render('manage_admin/automotive_groups/add_edit_member_company_new', 'admin_master');
                } else {
                    $company_name = $this->input->post('company_name');
                    $location_country = $this->input->post('location_country');
                    $location_state = $this->input->post('location_state');
                    $location_address = $this->input->post('location_address');
                    $location_city = $this->input->post('location_city');
                    $location_zipcode = $this->input->post('location_zipcode');
                    $pri_contact_name = $this->input->post('pri_contact_name');
                    $pri_contact_phone = $this->input->post('pri_contact_phone');
                    $pri_contact_email = $this->input->post('pri_contact_email');
                    $sec_contact_name = $this->input->post('sec_contact_name');
                    $sec_contact_phone = $this->input->post('sec_contact_phone');
                    $sec_contact_email = $this->input->post('sec_contact_email');
                    $is_registered_in_ahr = $this->input->post('is_registered_in_ahr');
                    $company_sid = $this->input->post('company_sid');
                    $short_description = $this->input->post('short_description');
                    $automotive_group_sid = $this->input->post('automotive_group_sid');

                    if ($is_registered_in_ahr == '' || $is_registered_in_ahr == null || empty($is_registered_in_ahr)) {
                        $is_registered_in_ahr = 0;
                    }

                    if ($company_sid == '' || $company_sid == null || empty($company_sid)) {
                        $company_sid = 0;
                    }
                    
                    if($company_is_registered=='yes'){
                        $company_details_db = $this->automotive_groups_model->get_company_details($company_sid);
                        $company_name = $company_details_db[0]['CompanyName'];
                        $location_country = $company_details_db[0]['Location_Country'];
                        $location_state = $company_details_db[0]['Location_State'];
                        $location_address = $company_details_db[0]['Location_Address'];
                        $location_city = $company_details_db[0]['Location_City'];
                        $location_zipcode = $company_details_db[0]['Location_ZipCode'];
                        $pri_contact_name = $company_details_db[0]['ContactName'];
                        $pri_contact_phone = $company_details_db[0]['PhoneNumber'];
                        $sec_contact_name = $company_details_db[0]['accounts_contact_person'];
                        $sec_contact_phone = $company_details_db[0]['accounts_contact_number'];
                    }

                    $data_to_insert = array();
                    $data_to_insert['company_name'] = $company_name;
                    $data_to_insert['location_country'] = $location_country;
                    $data_to_insert['location_state'] = $location_state;
                    $data_to_insert['location_address'] = $location_address;
                    $data_to_insert['location_city'] = $location_city;
                    $data_to_insert['location_zipcode'] = $location_zipcode;
                    $data_to_insert['pri_contact_name'] = $pri_contact_name;
                    $data_to_insert['pri_contact_phone'] = $pri_contact_phone;
                    $data_to_insert['pri_contact_email'] = $pri_contact_email;
                    $data_to_insert['sec_contact_name'] = $sec_contact_name;
                    $data_to_insert['sec_contact_phone'] = $sec_contact_phone;
                    $data_to_insert['sec_contact_email'] = $sec_contact_email;
                    $data_to_insert['is_registered_in_ahr'] = $is_registered_in_ahr;
                    $data_to_insert['company_sid'] = $company_sid;
                    $data_to_insert['short_description'] = $short_description;
                    $data_to_insert['automotive_group_sid'] = $automotive_group_sid;

                    $this->automotive_groups_model->update_member_company_record($member_company_sid, $data_to_insert);
                    $this->session->set_flashdata('message', '<strong>Success: </strong> Member company added seccussfully.');
                    redirect('manage_admin/corporate_groups/member_companies/' . $automotive_group_sid, 'refresh');
                }                
            } else {
                $this->session->set_flashdata('message', '<strong>Error:</strong> Automotive Group Not Found!');
                redirect('manage_admin/corporate_groups', 'refresh');
            }
        } else {
            $this->session->set_flashdata('message', '<strong>Error:</strong> Automotive Group Not Found!');
            redirect('manage_admin/corporate_groups', 'refresh');
        }
    }

    public function view_member_company($automotive_group_sid = 0, $member_company_sid = 0) {
        $redirect_url = 'manage_admin';
        $function_name = 'automotive_member_companies';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name

        if ($automotive_group_sid > 0 && $member_company_sid > 0) {
            $admin_id = $this->session->userdata('user_id');
            $security_details = db_get_admin_access_level_details($admin_id);
            $this->data['security_details'] = $security_details;
            //check_access_permissions($security_details, 'manage_admin', 'invoices_panel'); // Param2: Redirect URL, Param3: Function Name
            $this->data['groups'] = $this->ion_auth->groups()->result();
            $where_filters = array();
            $where_filters['sid'] = $automotive_group_sid;
            $group_record = $this->automotive_groups_model->get_automotive_group_records(0, 0, $where_filters);
            $data_countries = db_get_active_countries();
            
            foreach ($data_countries as $value) {
                $data_states[$value['sid']] = db_get_active_states($value['sid']);
            }

            $this->data['active_countries'] = $data_countries;
            $this->data['active_states'] = $data_states;
            $data_states_encode = htmlentities(json_encode($data_states));
            $this->data['states'] = $data_states_encode;
            $companies = $this->automotive_groups_model->get_all_companies();
            $this->data['companies'] = $companies;
            $oem_brands = $this->automotive_groups_model->get_all_oem_brands();
            $this->data['oem_brands'] = $oem_brands;
            $this->data['automotive_group_sid'] = $automotive_group_sid;

            if (!empty($group_record)) {
                $this->data['automotive_group'] = $group_record[0];
                $this->data['page_title'] = 'View Member Company Of Group : ';
                $where_filters = array();
                $where_filters['sid'] = $member_company_sid;
                $where_filters['automotive_group_sid'] = $automotive_group_sid;
                $limit = 0;
                $offset = 0;
                $member_company_info = $this->automotive_groups_model->get_automotive_group_member_companies($limit, $offset, $where_filters);

                if (!empty($member_company_info)) {
                    $this->data['member_company_info'] = $member_company_info[0];
                    $this->render('manage_admin/automotive_groups/view_member_company', 'admin_master');
                } else {
                    $this->data['member_company_info'] = array();
                    $this->session->set_flashdata('message', '<strong>Error:</strong> This company does not exist.');
                    redirect('manage_admin/corporate_groups/member_companies/' . $automotive_group_sid, 'refresh');
                }
            } else {
                $this->session->set_flashdata('message', '<strong>Error:</strong> Automotive Group Not Found!');
                redirect('manage_admin/corporate_groups', 'refresh');
            }
        } else {
            $this->session->set_flashdata('message', '<strong>Error:</strong> Automotive Group Not Found!');
            redirect('manage_admin/corporate_groups', 'refresh');
        }
    }
}