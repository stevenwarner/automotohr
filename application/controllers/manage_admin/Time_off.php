<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Time_off extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/time_off_model');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    public function index($company_sid = null) {
        $redirect_url       = 'manage_admin/company_module/1';
        $function_name      = 'edit_company_timeoff_polices';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name

        if($company_sid != null && is_numeric($company_sid)){

            $this->data['page_title']                                           = 'Policies Management';
            $this->data['company_sid']                                          = $company_sid;
            $company_timeoff_policies                                           = $this->time_off_model->get_company_timeoff_policies($company_sid); 
            $timeoff_categories                                                 = $this->time_off_model->get_all_timeoff_category();
            $company_default_policy                                             = array();
            if (!empty($company_timeoff_policies )) {
                foreach ($company_timeoff_policies as $pkey => $policy) {
                    // $category_name = $timeoff_categories[$policy['timeoff_category_sid']];
                    // $company_timeoff_policies[$pkey]['category_name'] = $category_name;

                    if ($policy['default_policy'] == 1) {
                        $company_default_policy = $policy;
                        unset($company_timeoff_policies[$pkey]);
                    }
                }
            }    

            $this->data['timeoff_categories']                                   = $timeoff_categories;
            $this->data['company_policies']                                     = $company_timeoff_policies;
            $this->data['company_default_policy']                               = $company_default_policy;
            $this->data['access_level']                                         = $this->time_off_model->get_security_access_levels();
            $this->render('manage_admin/time_off/policies_listing', 'admin_master');
        } else {
            $this->session->set_flashdata('message', 'Error: Company not found!');
            redirect($redirect_url, 'refresh');
        }
    }

    public function manage_time_off_icons ($company_sid = null) {
        $redirect_url       = 'manage_admin/company_module/1';
        $function_name      = 'edit_timeoff_icons';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name

        if($company_sid != null && is_numeric($company_sid)){
            $this->data['page_title']                                           = 'Icons Management';
            $this->data['company_sid']                                          = $company_sid;
            $timeoff_icons                                                      = $this->time_off_model->get_all_timeoff_icons();
            $this->data['timeoff_icons']                                        = $timeoff_icons;
            $this->data['access_level']                                         = $this->time_off_model->get_security_access_levels();
            $this->render('manage_admin/time_off/time_off_icons', 'admin_master');
        } else {
            $this->session->set_flashdata('message', 'Error: Company not found!');
            redirect($redirect_url, 'refresh');
        }    
    }

    public function manage_approvers ($company_sid = null) {
        $redirect_url       = 'manage_admin/company_module/1';
        $function_name      = 'edit_company_timeoff_approvers';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name

        if($company_sid != null && is_numeric($company_sid)){

            $this->data['page_title']                                           = 'Approvers Management';
            $this->data['company_sid']                                          = $company_sid;
            $company_timeoff_approvers                                          = $this->time_off_model->get_company_timeoff_approvers($company_sid);
            
            if (!empty($company_timeoff_approvers)) {
                foreach ($company_timeoff_approvers as $apkey => $approver) {
                    $employee_name = $approver['first_name'].' '.$approver['last_name'];
                    $user_name = getUserNameBySID($approver['employee_sid']);
                    $employee_role = str_replace($employee_name, '', $user_name);

                    $company_timeoff_approvers[$apkey]['employee_name'] = $employee_name;
                    $company_timeoff_approvers[$apkey]['employee_role'] = $employee_role;

                    if ($approver['department_sid'] != 'all') {
                        $department_team_name = $this->time_off_model->get_department_team_name($approver['department_sid'], $approver['is_department']);
                        $company_timeoff_approvers[$apkey]['department_team_name'] = $department_team_name;
                    } else {
                        if ($approver['is_department'] == 1) {
                            $company_timeoff_approvers[$apkey]['department_team_name'] = 'All Departments';
                        } else {
                            $company_timeoff_approvers[$apkey]['department_team_name'] = 'All Teams';
                        }
                    }
                    
                }
            }

            $this->data['company_approvers']                                     = $company_timeoff_approvers;
            $this->data['access_level']                                         = $this->time_off_model->get_security_access_levels();
            $this->render('manage_admin/time_off/approvers_listing', 'admin_master');
        } else {
            $this->session->set_flashdata('message', 'Error: Company not found!');
            redirect($redirect_url, 'refresh');
        }
    }

    public function time_off_settings ($company_sid = null) {
        die('time_off_setting');
    }

    public function handler () {
        //
        $post = $this->input->post();
        //
        switch ($post['action']) {
            case 'change_policy_status':
                $policy_sid = $post['policy_sid'];
                $company_sid = $post['company_sid'];
                $status = $post['status'];
                $data_to_update = array(
                    'is_archived' => !$status
                );
                $this->time_off_model->change_company_policy_status($policy_sid, $company_sid, $data_to_update);
                echo 'success';
                break;

            case 'change_timeoff_icon_info':
                $icon_id = $post['icon_id'];
                $info_content = $post['info_content'];
                $data_to_update = array(
                    'info_content' => $info_content
                );
                $this->time_off_model->change_time_off_icon_info_content($icon_id, $data_to_update);
                echo 'success';
                break;   
            case 'change_approver_status':
                $approver_sid = $post['approver_sid'];
                $company_sid = $post['company_sid'];
                $is_archived = $post['is_archived'];
                $data_to_update = array(
                    'is_archived' => $is_archived
                );
                $this->time_off_model->change_company_approvers_status($approver_sid, $company_sid, $data_to_update);
                echo 'success';
                break;     
        }
    }    
}
?>