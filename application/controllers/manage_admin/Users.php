<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends Admin_Controller {
    function __construct() {
        parent::__construct();
        $this->identity_column = $this->config->item('identity', 'ion_auth');
        $this->load->model('manage_admin/users_model');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    public function index($group_id = NULL) {
        $redirect_url = 'manage_admin';
        $function_name = 'list_admin_users';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->data['page_title'] = 'Manage Admin Users';
        $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');
        $perform_action = $this->input->post('perform_action');

        switch ($perform_action) {
            case 'deactivate_user':
                $this->form_validation->set_rules('user_id', 'user_id', 'required|trim|xss_clean');
                break;
            case 'activate_user':
                $this->form_validation->set_rules('user_id', 'user_id', 'required|trim|xss_clean');
                break;
            case 'delete_user':
                $this->form_validation->set_rules('user_id', 'user_id', 'required|trim|xss_clean');
                break;
        }

        if ($this->form_validation->run() == false) {
            $user_data = $this->ion_auth->users($group_id)->result();
            $this->data['users'] = $user_data;
            $this->render('manage_admin/users/listing_view');
        } else {
            $perform_action = $this->input->post('perform_action');

            switch ($perform_action) {
                case 'deactivate_user':
                    $user_id = $this->input->post('user_id');
                    $this->set_user_status($user_id, 0);
                    break;
                case 'activate_user':
                    $user_id = $this->input->post('user_id');
                    $this->set_user_status($user_id, 1);
                    break;
                case 'delete_user':
                    $user_id = $this->input->post('user_id');
                    $this->delete_admin_user($user_id);
                    break;
            }
        }
    }

    private function set_user_status($user_id, $status = 0) {
        $data_to_update = array();
        $data_to_update['active'] = $status;
        $this->db->where('id', $user_id);
        $this->db->update('administrator_users', $data_to_update);

        if ($status == 0) {
            $this->session->set_flashdata('message', '<strong>Success</strong> User Successfully Deactivated.');
        } else if ($status == 1) {
            $this->session->set_flashdata('message', '<strong>Success</strong> User Successfully Activated.');
        }

        redirect('manage_admin/users', 'refresh');
    }

    function delete_admin_user($user_id) {
        $this->db->where('id', $user_id);
        $this->db->delete('administrator_users');
        $this->session->set_flashdata('message', '<strong>Success</strong> User Successfully Deleted.');
        redirect('manage_admin/users', 'refresh');
    }

    public function add_subaccount() {
        $redirect_url = 'manage_admin';
        $function_name = 'add_subaccount';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->data['page_title'] = 'Add Sub-Account';
        $this->load->library('form_validation');
        $this->form_validation->set_rules('first_name', 'First name', 'trim|required');
        $this->form_validation->set_rules('last_name', 'Last name', 'trim|required');
        $this->form_validation->set_rules('company', 'Company', 'trim');
        $this->form_validation->set_rules('phone', 'Phone', 'trim');
        $this->form_validation->set_rules('username', 'Username', 'trim|required|is_unique[administrator_users.username]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[administrator_users.email]');
//        $this->form_validation->set_rules('password', 'Password', 'required');
//        $this->form_validation->set_rules('password_confirm', 'Password confirmation', 'required|matches[password]');
        $this->form_validation->set_rules('groups[]', 'Groups', 'required|integer');

        if ($this->form_validation->run() === FALSE) {
            $this->data['groups'] = $this->ion_auth->groups()->result();
            $this->load->helper('form');
            $this->render('manage_admin/users/create_user_view');
        } else {
            $username = $this->input->post('username');
            $email = $this->input->post('email');
            $last_name = $this->input->post('last_name');
            $first_name = $this->input->post('first_name');
            $company = $this->input->post('company');
            $phone = $this->input->post('txt_phonenumber') ? $this->input->post('txt_phonenumber') : $this->input->post('phone');
//            $password = $this->input->post('password');
            $password = '';
            $group_ids = $this->input->post('groups');
            if(isset($_POST['submit']) && $_POST['submit'] == 'Create User'){
                $additional_data = array('first_name' => $first_name,
                    'last_name' => $last_name,
                    'company' => $company,
                    'phone' => $phone);
                $this->ion_auth->register($username, $password, $email, $additional_data, $group_ids);
                $this->session->set_flashdata('message', 'Account Successfully Created');
            } elseif(isset($_POST['create_submit']) && $_POST['create_submit'] == 'Create User and Send Email'){
                $salt = generateRandomString(48);
                $additional_data = array('first_name' => $first_name,
                    'last_name' => $last_name,
                    'company' => $company,
                    'salt' => $salt,
                    'phone' => $phone);

                $this->ion_auth->register($username, $password, $email, $additional_data, $group_ids);
                $replacement_array = array();
                $replacement_array['employer_name'] = ucwords($first_name . ' ' . $last_name);
                $replacement_array['company_name'] = $company;
                $replacement_array['username'] = $username;
                $replacement_array['login_page'] = '<a style="background-color: #d62828; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" href="https://www.automotohr.com/manage_admin/user/login" target="_blank">Login page</a>';
                $replacement_array['firstname'] = $first_name;
                $replacement_array['lastname'] = $last_name;
                $replacement_array['create_password_link']  = '<a style="background-color: #d62828; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" target="_blank" href="' . base_url() . "manage_admin/users/generate_password/" . $salt . '">Generate Password</a>';
                //
                $employee_details = $this->users_model->email_user_data($email);
                $user_extra_info = array();
                $user_extra_info['user_sid'] = $employee_details['sid'];
                $user_extra_info['user_type'] = "employee";
                //
                log_and_send_templated_email(NEW_EMPLOYEE_TEAM_MEMBER_NOTIFICATION, $email, $replacement_array, [], 1, $user_extra_info);
                $this->session->set_flashdata('message', 'Email Sent Successfully');

            }
            redirect('manage_admin/users', 'refresh');
        }
    }

    function send_login_credentials() {
        $sid = $this->input->post('sid');
        $this->load->model('users_model');
        $employee_details = $this->users_model->get_employee_details($sid);
        if(!empty($employee_details)) {
            $first_name = $employee_details[0]['first_name'];
            $last_name = $employee_details[0]['last_name'];
            $username = $employee_details[0]['username'];
            $company_name = $employee_details[0]['company'];
            $email = $employee_details[0]['email'];
            $salt = $employee_details[0]['salt'];

            if($salt == NULL || $salt == '') {
                $salt = generateRandomString(48);
                $data = array('salt' => $salt);
                $this->users_model->update_user($sid, $data);
            }

            $replacement_array = array();
            $replacement_array['employer_name'] = ucwords($first_name . ' ' . $last_name);
            $replacement_array['company_name'] = $company_name;
            $replacement_array['username'] = $username;
            $replacement_array['login_page'] = '<a style="background-color: #d62828; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" href="https://www.automotohr.com/manage_admin/user/login" target="_blank">Login page</a>';
            $replacement_array['firstname'] = $first_name;
            $replacement_array['lastname'] = $last_name;
            $replacement_array['create_password_link']  = '<a style="background-color: #d62828; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" target="_blank" href="' . base_url() . "manage_admin/users/generate_password/" . $salt . '">Generate Password</a>';
            //
            $user_extra_info = array();
            $user_extra_info['user_sid'] = $sid;
            $user_extra_info['user_type'] = "employee";
            //
            log_and_send_templated_email(NEW_EMPLOYEE_TEAM_MEMBER_NOTIFICATION, $email, $replacement_array, [], 1, $user_extra_info);

            echo 'success';
        } else {
            echo 'error';
        }
    }

    /**
     * Reset sub account password
     *
     * @param $key String
     *
     * @return Void
     *
     */
    function generate_password($key = NULL) {
        if($key == NULL) {
            $this->session->set_flashdata('message', 'Error: Your Security Key has Expired!, Please contact Administrator.');
            redirect(base_url('manage_admin/user/login'), 'refresh');
        }
        // 
        $this->load->model('users_model');
        //
        $check_exist = $this->users_model->check_key($key);

        if (!$check_exist) {
            $this->session->set_flashdata('message', '<b>Error:</b> Invalid Request!');
            redirect(base_url('manage_admin/user/login'), 'refresh');
        }

        $this->load->model('home_model');
        $data['home_page'] = $this->home_model->get_home_page_data();
        //
        if($this->input->post('password')){
            $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|matches[cpassword]');
            $this->form_validation->set_rules('cpassword', 'cPassword', 'trim|required|xss_clean');
            $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i> ', '</p>');
            
            $this->form_validation->run();
            $password = $this->ion_auth_model->hash_password($this->input->post('password'), $key,FALSE);
            //
            $this->users_model->updatePass($password, $key);

            $this->session->set_flashdata('message', 'Password generated successfully! Please login to access your account.');
            redirect(base_url('manage_admin/user/login'), 'refresh');
        }

        $data['page_title'] = 'Generate Password - Employee Team Member';
        $this->load->view('main/static_header', $data);
        $this->load->view('users/generate-password');
        $this->load->view('main/footer');
    }


    // Old and deprecated
    // public function generate_password($key = NULL) {
    //     if($key != NULL) {
    //         $this->load->model('users_model');
    //         $check_exist = $this->users_model->check_key($key);

    //         if (!$check_exist) {
    //             $this->session->set_flashdata('message', '<b>Error:</b> Invalid Request!');
    //             redirect(base_url('manage_admin/user/login'), 'refresh');
    //         } else {
    //             if ($this->session->userdata('logged_in')) {
    //                 $this->session->unset_userdata('logged_in');
    //                 $this->session->unset_userdata('coupon_data');
    //             }

    //             $this->load->model('home_model');
    //             $data['home_page'] = $this->home_model->get_home_page_data();
    //             $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|matches[cpassword]');
    //             $this->form_validation->set_rules('cpassword', 'cPassword', 'trim|required|xss_clean');
    //             $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i> ', '</p>');

    //             if ($this->form_validation->run() == FALSE) {
    //                 $data['page_title'] = 'Generate Password - Employee Team Member';
    //                 $this->load->view('main/static_header', $data);
    //                 $this->load->view('users/generate-
    //                     password');
    //                 $this->load->view('main/footer');
    //             } else {
    //                 $password = $this->input->post('password');
    //                 $this->users_model->updatePass($password, $key);
    //                 $this->session->set_flashdata('message', 'Password generated successfully! Please login to access your account.');
    //                 redirect(base_url('manage_admin/user/login'), 'refresh');
    //             }
    //         }
    //     } else {
    //         $this->session->set_flashdata('message', 'Error: Your Security Key has Expired!, Please contact Administrator.');
    //         redirect(base_url('manage_admin/user/login'), 'refresh');
    //     }
    // }

    public function edit_profile($user_id = NULL) {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url = 'manage_admin';
        $function_name = 'edit_my_account';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //
        //$user_id  = $this->input->post('user_id') ? $this->input->post('user_id') : $user_id;

        if ($user_id == NULL) { // edit my profile
            $profile_type = 'personal';
            $this->data['page_title'] = 'Edit My Profile';
            check_access_permissions($security_details, 'manage_admin', 'edit_my_account'); // Param2: Redirect URL, Param3: Function Name
        } else { // edit other employee team profile
            $profile_type = 'team';
            $this->data['page_title'] = 'Edit Sub Account Profile';
            check_access_permissions($security_details, 'manage_admin', 'add_subaccount'); // Param2: Redirect URL, Param3: Function Name
        }

        $this->data['profile_type'] = $profile_type;
        $this->load->library('form_validation');
        $this->form_validation->set_rules('first_name', 'First name', 'trim|required');
        $this->form_validation->set_rules('last_name', 'Last name', 'trim|required');
        $this->form_validation->set_rules('company', 'Company', 'trim');
        $this->form_validation->set_rules('phone', 'Phone', 'trim');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('user_id', 'User ID', 'trim|integer|required');

        if ($profile_type == 'team') {
            $this->form_validation->set_rules('groups[]', 'Groups', 'required|integer');
        }

       if ($this->input->post('password') != NULL) {
            $this->form_validation->set_rules('password', 'Password', 'trim|xss_clean|matches[password_confirm]');
            $this->form_validation->set_rules('password_confirm', 'Confirm Password', 'trim|xss_clean');
       }

        if ($this->form_validation->run() === FALSE) {
            if ($user = $this->ion_auth->user((int) $user_id)->row()) {
                $this->data['user'] = $user;
            } else {
                $this->session->set_flashdata('message', 'The user doesn\'t exist.');
                redirect('manage_admin/users', 'refresh');
            }

            $this->data['groups'] = $this->ion_auth->groups()->result();
            $this->data['usergroups'] = array();

            if ($usergroups = $this->ion_auth->get_users_groups($user->id)->result()) {
                foreach ($usergroups as $group) {
                    $this->data['usergroups'][] = $group->id;
                }
            }

            $this->load->helper('form');
            $this->render('manage_admin/users/edit_user_view');
        } else {
                $user_id = $this->input->post('user_id');
                $password = $this->input->post('password');
                $new_data = array();
                $new_data['email']      = $this->input->post('email');
                $new_data['first_name'] = $this->input->post('first_name');
                $new_data['last_name']  = $this->input->post('last_name');
                $new_data['company']    = $this->input->post('company');
                $new_data['phone']      = $this->input->post('txt_phonenumber') ? $this->input->post('txt_phonenumber') : $this->input->post('phone');
                if (!empty($password)) {
                    $new_data['password']    = $this->input->post('password');
                }
                
            if(IS_TIMEZONE_ACTIVE) {
                // Added on: 25-06-2019
                $new_timezone = $this->input->post('timezone', true);
                if($new_timezone != '') $new_data['timezone'] = $new_timezone;
            }
            
            $this->ion_auth->update($user_id, $new_data);

            if ($profile_type == 'team') { //Update the groups user belongs to
                $groups = $this->input->post('groups');
                if (isset($groups) && !empty($groups)) {
                    $this->ion_auth->remove_from_group('', $user_id);
                    foreach ($groups as $group) {
                        $this->ion_auth->add_to_group($group, $user_id);
                    }
                }
            }

            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect('manage_admin/users', 'refresh');
        }
    }

    public function delete() {
        $user_id = $this->input->post('sid');
        $admin_id = $this->session->userdata('user_id');
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        echo $admin_id;
        print_r($security_details);
//        if (in_array('full_access', $security_details) || in_array('add_subaccount', $security_details)) {
//            $this->ion_auth->delete_user($user_id);
//            $this->session->set_flashdata('message', $this->ion_auth->messages());
//        }
    }

    public function who_is_online() {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url = 'manage_admin';
        $function_name = 'who_is_online';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        $online_users = $this->users_model->get_online_users(10);
        $total_users_count = $this->users_model->total_users();
        $active_users = $this->users_model->total_active_users();
        $inactive_users = $this->users_model->total_inactive_uers();

        $this->data['online_users'] = $online_users;
        $this->data['total_users'] = $total_users_count;
        $this->data['active_users'] = $active_users;
        $this->data['inactive_users'] = $inactive_users;
        $this->data['page_title'] = 'Current Online Employers';
        $this->render('manage_admin/users/list_online_users_view');
    }

    function employer_login() {
        $redirect_url = 'manage_admin';
        $function_name = 'employerlogin';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->load->model('dashboard_model');
        $this->load->config('ion_auth', TRUE);
        $employer_id = $this->input->post('sid');
        $username = $this->input->post('username');
        $identity = $this->config->item('identity', 'ion_auth');

        if (substr(CI_VERSION, 0, 1) == '2')
        {
            $this->session->unset_userdata( array($identity => '', 'id' => '', 'user_id' => '') );
        }
        else
        {
            $this->session->unset_userdata( array($identity, 'id', 'user_id') );
        }
        $query = $this->db->select($this->identity_column . ', email, id, password, active, last_login')
            ->where($this->identity_column, $username)
            ->limit(1)
            ->order_by('id', 'desc')
            ->get('administrator_users');
        $user = $query->row();
        $session_data = array(
            'identity'             => $user->{$this->identity_column},
            $this->identity_column => $user->{$this->identity_column},
            'email'                => $user->email,
            'user_id'              => $user->id, //everyone likes to overwrite id so we'll use user_id
            'old_last_login'       => $user->last_login
        );
        $this->session->set_flashdata('message', 'Successfully Switched to '.ucwords($username));
        $this->session->set_userdata($session_data);
    }

}
