<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Corporate_management extends Admin_Controller {
    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->library("pagination");
        $this->load->library('form_validation');
        $this->load->model('manage_admin/company_model');
        $this->load->model('portal_email_templates_model');
        $this->load->model('manage_admin/corporate_career_site_model');
        $this->load->model('manage_admin/automotive_groups_model');
        require_once(APPPATH . 'libraries/xmlapi.php');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    public function index() {
        $redirect_url = 'manage_admin';
        $function_name = 'corporate_panel';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        /** search code * */
        $search = array();
        $search_data = $this->input->get(NULL, true);
        $this->data["search"] = $search_data;
        $this->data["flag"] = false;
        
        foreach ($search_data as $key => $value) {
            if ($key != 'start' && $key != 'end') {
                if ($value != '') { // exclude these values from array
                    $search[$key] = $value;
                    $this->data["flag"] = true;
                }
            }
        }
        /** search code end * */
        
        //** pagination code **//
        if ($this->data["flag"] == false) {
            $config = array(); 
            $config["base_url"] = base_url() . "manage_admin/corporate_management/";
            $config["per_page"] = 20;
            $config["total_rows"] = $this->corporate_career_site_model->get_all_corporate_sites_count($search);
            $config["uri_segment"] = 3;
            $choice = $config["total_rows"] / $config["per_page"];
            $config["num_links"] = 4; 
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
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $my_offset = 0;

            if ($page > 1) {
                $my_offset = ($page - 1) * $config["per_page"];
            }

            $this->data["links"] = $this->pagination->create_links();
        } else {
            $config["per_page"] = NULL;
            $my_offset = NULL;
        }
        //** pagination code end **//
        $this->data['corporate_companies'] = $this->corporate_career_site_model->get_all_corporate_sites($config["per_page"], $my_offset, $search);
        $this->data['page_title'] = 'Corporate Companies Management';
        $this->render('manage_admin/corporate_management/listing_view');
    }

    public function add_corporate_site() {
        $redirect_url = 'manage_admin/corporate_management';
        $function_name = 'add_corporate_site';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->form_validation->set_rules('CompanyName', 'Company Name', 'required|trim|xss_clean|is_unique[users.CompanyName]');
        $this->form_validation->set_rules('ContactName', 'Contact Name', 'required|trim|xss_clean');
        $this->form_validation->set_rules('Location_Country', 'Country', 'trim|xss_clean');
        $this->form_validation->set_rules('Location_State', 'State', 'trim|xss_clean');
        $this->form_validation->set_rules('Location_City ', 'City', 'trim|xss_clean');
        $this->form_validation->set_rules('Location_ZipCode', 'Zipcode', 'trim|xss_clean');
        $this->form_validation->set_rules('Location_Address', 'Address', 'trim|xss_clean');
        $this->form_validation->set_rules('PhoneNumber', 'Phone Number', 'trim|xss_clean');
        $this->form_validation->set_rules('CompanyDescription', 'Description', 'trim|xss_clean');
        $this->form_validation->set_rules('new_subdomain', 'Corporate Site URL', 'required|trim|xss_clean|callback_check_domain');

        if ($this->form_validation->run() === FALSE) {
            $data_countries = $this->company_model->get_active_countries(); //get all active `countries`

            foreach ($data_countries as $value) {//get all active `states`
                $data_states[$value['sid']] = $this->company_model->get_active_states($value['sid']);
            }

            $automotive_groups = $this->corporate_career_site_model->get_all_automotive_groups();
            $this->data['automotive_groups'] = $automotive_groups;
            $this->data['active_countries'] = $data_countries;
            $this->data['active_states'] = $data_states;
            $data_states_encode = htmlentities(json_encode($data_states));
            $this->data['states'] = $data_states_encode;
            $this->data['data']['Location_Country'] = 227;
            $this->data['data']['Location_State'] = 1;
            $this->render('manage_admin/corporate_management/add_corporate_site');
        } else { //Company Information
            $company_data = array();
            $company_username = clean(strtolower($this->input->post('CompanyName')));
            $company_data['username'] = 'corp_' . $company_username . '-' . generateRandomString(6);
            $company_data['active'] = 1;
            $company_data['ip_address'] = getUserIP() . ' - ' . $_SERVER['HTTP_USER_AGENT'];
            $company_data['registration_date'] = date('Y-m-d H:i:s');
            $company_data['password'] = do_hash("admin125", 'md5');
            $company_data['api_key'] = random_key();
            $company_data['CompanyName'] = $this->input->post('CompanyName');
            $company_data['ContactName'] = $this->input->post('ContactName');
            $company_data['Location_Country'] = $this->input->post('Location_Country');
            $company_data['Location_State'] = $this->input->post('Location_State');
            $company_data['Location_City'] = $this->input->post('Location_City');
            $company_data['Location_ZipCode'] = $this->input->post('Location_ZipCode');
            $company_data['Location_Address'] = $this->input->post('Location_Address');
            $company_data['PhoneNumber'] = $this->input->post('PhoneNumber');
            $company_data['CompanyDescription'] = $this->input->post('CompanyDescription');
            $company_data['career_page_type'] = 'corporate_career_site';
            //Portal Information
            $domain = $this->clean_domain($this->input->post('new_subdomain')) . '.' . STORE_DOMAIN;
            $employer_portal_data = array();
            $employer_portal_data["sub_domain"] = $domain;
            $employer_portal_data["status"] = 1;
            $employer_portal_data["career_page_type"] = 'corporate_career_site';
            $result = $this->corporate_career_site_model->insert($company_data, $employer_portal_data);

            if (!empty($result)) { //making sub domain
                //Add Company Portal Templates Information - Start
                $company_sid = $result['company_id'];
                $company_name = $this->input->post('CompanyName');
                $company_email = FROM_EMAIL_DEV;

                if ($company_sid > 0) {
                    $this->portal_email_templates_model->check_default_tables($company_sid, $company_email, $company_name);
                }

                //Add Company Portal Templates Information - End
                $auth_details = $this->company_model->fetch_details(THEME_AUTH);
                $auth_user = $auth_details['auth_user'];
                $auth_pass = $auth_details['auth_pass'];
                $server = STORE_DOMAIN;
                $json_client = new xmlapi($server);
                $json_client->set_output('json');
                $json_client->set_port(2083);
                $json_client->password_auth($auth_user, $auth_pass);

                $args = array(
                            'dir' => 'public_html/manage_portal/',
                            'rootdomain' => STORE_DOMAIN,
                            'domain' => $domain
                        );

                if ($_SERVER['SERVER_NAME'] != 'localhost') {
                    $result = $json_client->api2_query($auth_user, 'SubDomain', 'addsubdomain', $args);
                    sendMail(FROM_EMAIL_DEV, 'ahassan@egenienext.com', 'New Api Result', $result);
                }
                //Add Company to Automotive Group
                $automotive_group_sid = $this->input->post('automotive_group_sid');
                $this->corporate_career_site_model->update_corporate_company_for_automotive_group($automotive_group_sid, $company_sid);
                $this->session->set_flashdata('message', '<b>Success:</b> New company added successfully');
                redirect('manage_admin/corporate_management', 'refresh');
            }
        }
    }
    
    public function edit_corporate_site($sid = NULL) {
        $redirect_url = 'manage_admin/corporate_management';
        $function_name = 'edit_corporate_site';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->data['page_title'] = 'Edit Corporate Company';
        $this->data['sid'] = $sid;
        
        if($sid == NULL || $sid == 0 || $sid == ''){
            $this->session->set_flashdata('message', '<b>Error:</b> Company does not exist');
            redirect('manage_admin/corporate_management', 'refresh');
        }

        $corporate_company_detail = $this->corporate_career_site_model->get_company_details($sid);
        $this->data['company_detail'] = $corporate_company_detail;
        $post_data = $this->input->post(NULL, true);
        
        if(isset($post_data['CompanyName']) && ($this->data['company_detail']['CompanyName'] == $post_data['CompanyName'])){
            $this->form_validation->set_rules('CompanyName', 'Company Name', 'required|trim|xss_clean');
        } else {
            $this->form_validation->set_rules('CompanyName', 'Company Name', 'required|trim|xss_clean|is_unique[users.CompanyName]');
        }
        
        $this->form_validation->set_rules('ContactName', 'Contact Name', 'required|trim|xss_clean');
        $this->form_validation->set_rules('Location_Country', 'Country', 'trim|xss_clean');
        $this->form_validation->set_rules('Location_State', 'State', 'trim|xss_clean');
        $this->form_validation->set_rules('Location_City ', 'City', 'trim|xss_clean');
        $this->form_validation->set_rules('Location_ZipCode', 'Zipcode', 'trim|xss_clean');
        $this->form_validation->set_rules('Location_Address', 'Address', 'trim|xss_clean');
        $this->form_validation->set_rules('PhoneNumber', 'Phone Number', 'trim|xss_clean');
        $this->form_validation->set_rules('CompanyDescription', 'Description', 'trim|xss_clean');
        //$this->form_validation->set_rules('automotive_group_sid', 'Automotive Group', 'required|trim|xss_clean');

        foreach($post_data as $key => $value){
            if(isset($value) && $value != ''){
                $this->data['company_detail'][$key] = $value;
            }
        }
        
        if ($this->form_validation->run() == FALSE) {
            $data_countries = $this->company_model->get_active_countries(); //get all active `countries`

            foreach ($data_countries as $value) {//get all active `states`
                $data_states[$value['sid']] = $this->company_model->get_active_states($value['sid']);
            }

            $assigned_automotive_group = $corporate_company_detail['automotive_group_sid'] == 0 ? null : $corporate_company_detail['automotive_group_sid'];
            $automotive_groups = $this->corporate_career_site_model->get_all_automotive_groups($assigned_automotive_group);
            $this->data['automotive_groups'] = $automotive_groups;
            $this->data['active_countries'] = $data_countries;
            $this->data['active_states'] = $data_states;
            $data_states_encode = htmlentities(json_encode($data_states));
            $this->data['states'] = $data_states_encode; 
            $this->render('manage_admin/corporate_management/edit_corporate_site');
        } else {
            $automotive_group_sid = $this->input->post('automotive_group_sid');
            unset($post_data['submit']);
            unset($post_data['automotive_group_sid']);
            $previous_automotive_group_sid = $corporate_company_detail['automotive_group_sid'];

            if($automotive_group_sid != $previous_automotive_group_sid){
                $group_executive_users = $this->automotive_groups_model->get_group_executive_users($previous_automotive_group_sid);

                foreach($group_executive_users as $executive_user){
                    $executive_admin_company_details = $this->company_model->get_executive_admin_company_details($executive_user['sid'], $sid);

                    if (!empty($executive_admin_company_details)) {
                        $logged_in_sid = $executive_admin_company_details['logged_in_sid'];
                        $admin_company_sid = $executive_admin_company_details['sid'];
                        $result = $this->company_model->executive_admin_company_remove($admin_company_sid, $logged_in_sid);
                    }
                }
            }

            if(isset($post_data['txt_phonenumber'])){ $post_data['PhoneNumber'] = $post_data['txt_phonenumber']; unset($post_data['txt_phonenumber']); }

            $this->corporate_career_site_model->update_corporate_company($sid, $post_data);
            $company_sid = $sid;
            //Add Company to Automotive Group
            $this->corporate_career_site_model->update_corporate_company_for_automotive_group($automotive_group_sid, $company_sid);
            $this->corporate_career_site_model->update_corporate_company_for_automotive_group($previous_automotive_group_sid, 0);
            $this->session->set_flashdata('message', '<b>Success:</b> Company updated successfully');
            redirect('manage_admin/corporate_management', 'refresh');
        }
    }

    public function check_domain($domain) {
        $domain = $domain . '.' . STORE_DOMAIN;
        $result = $this->corporate_career_site_model->check_existing_domain($domain);

        if ($result == 1) {
            $this->form_validation->set_message('check_domain', 'Please enter a unique domain URL');
            return false;
        } else {
            return true;
        }
    }

    function ajax_responder() {
        if ($_POST) {
            if (isset($_POST['perform_action'])) {
                $perform_action = $_POST['perform_action'];

                switch ($perform_action) {
                    case 'change_company_status':
                        $company_sid = $_POST['corporate_company_sid'];
                        $company_status = $_POST['company_status'];
                        $this->corporate_career_site_model->update_company_status($company_sid, $company_status);
                        $this->session->set_flashdata('message', '<b>Success:</b> Company status updated successfully');
                        break;
                }
            }
        }
        redirect('manage_admin/corporate_management');
    }

    function clean_domain($domain) {
        $domain = str_replace(' ', '-', $domain); // Replaces all spaces with hyphens
        $domain = preg_replace('/[^A-Za-z0-9.\-]/', '', $domain); // Removes special chars
        $domain = preg_replace('/-+/', '-', $domain); // Replaces multiple hyphens with single one
        $domain = preg_replace('/\.+/', '.', $domain); // Replaces multiple periods with single one   
        $first_char = substr($domain, 0, 1);
        $last_char = substr($domain, -1);

        if ($first_char == '.') { // remove the first character as a period
            $domain = substr($domain, 1);
        }
        
        if ($last_char == '.') { // remove the last character as a period
            $domain = substr($domain, 0, -1);
        }

        return $domain;
    }
}