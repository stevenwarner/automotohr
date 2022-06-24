<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Bulk_email extends Admin_Controller {
    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/bulk_email_model');
        //$this->load->library('form_validation');
        //$this->load->library("pagination");
        //$this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    public function index() {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url       = 'manage_admin';
        $function_name      = 'bulk_emails_index';  // change the function name to apply security to this module
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //
        
        $this->data['page_title'] = 'Send Bulk Email';
        $companies = $this->bulk_email_model->get_all_companies();
        $this->data['companies'] = $companies;
        
        $company_sid = $this->input->get('company_sid');
        if(!empty($company_sid)){
            $this->data['company_sid'] = $company_sid;
            if($company_sid == 'all'){
                foreach($companies as $company) { 
                    $company_name = $company['CompanyName'];
                    $this->data['all_employers'][$company_name]['company_employees'] = $this->bulk_email_model->get_company_employees($company['sid']);
                }
            } else {
                $company = get_company_details($company_sid);
                $company_name = $company['CompanyName'];
                $this->data['all_employers'][$company_name]['company_employees'] = $this->bulk_email_model->get_company_employees($company_sid);
            }

            $this->data['admin_templates'] = $this->fetch_admin_templates();
        } else {
            $this->data['all_employers'] = '';
        }
        
        $this->render('manage_admin/bulk_email/index');
    }
    
    public function send_email (){
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url       = 'manage_admin';
        $function_name      = 'bulk_emails_index';  // change the function name to apply security to this module
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //
        
        $action = $this->input->post('action');
        $employee_ids = $this->input->post('ids');
        $subject = $this->input->post('subject');
        $body = $this->input->post('message');


        foreach ($employee_ids as $employee_id) {
            // Added on: 02-05-2019
            $user_subject = $subject;
            $user_body = $body;
            $employee_data = $this->bulk_email_model->get_employee_data($employee_id);
            //
            if($employee_data['active'] == 0 || $employee_data['terminated_status'] == 1){
                continue;
            }
            // Added on 30-04-2019
            replace_magic_quotes(
                $user_subject,
                array(
                    '{{first_name}}' => $employee_data['first_name'],
                    '{{last_name}}' => $employee_data['last_name'],
                    '{{email}}' => $employee_data['email'],
                    '{{phone}}'  => $employee_data['phone_number']
                )
            );
            replace_magic_quotes(
                $user_body,
                array(
                    '{{first_name}}' => $employee_data['first_name'],
                    '{{last_name}}' => $employee_data['last_name'],
                    '{{email}}' => $employee_data['email'],
                    '{{phone}}'  => $employee_data['phone_number']
                )
            );

            $from = FROM_EMAIL_STEVEN;
            $to = $employee_data['email'];
            $sender_name = 'Steven Warner';
            $messagebody = '<div class = "content" style = "font-size: 100%; line-height: 1.6em; display: block; max-width: 1000px; margin: 0 auto; padding: 0; position:relative"><div style = "width:100%; float:left; padding:5px 20px; text-align:center; box-sizing: border-box; background-color:#000;"><h2 style = "color:#fff;">'.ucwords(STORE_DOMAIN).'</h2></div> <div class = "body-content" style = "width:100%; float:left; padding:20px 20px 60px 20px; box-sizing:border-box; background:url(images/bg-body.jpg);">'
                    . '<p>Subject: ' . $user_subject . '</p>'
                    . $user_body
                    . '</div><div class = "footer" style = "width:100%; float:left; background-color:#000; padding:20px 30px; box-sizing:border-box;"><div style = "float:left; width:100%; "><p style = "color:#fff; text-align:center; font-style:italic; line-height:normal; font-family: "Open Sans", sans-serif; font-weight:600; font-size:14px;"><a style = "color:#fff; text-decoration:none;" href = "' . STORE_FULL_URL . '">' . ucwords(STORE_DOMAIN) . '</a></p></div></div></div>'
                    . '<div style="width:100%; float:left; background-color:#000; color:#000; box-sizing:border-box;">message_id:'
                    . generateRandomString(48) . "__" . '</div>';
            log_and_sendEmail($from, $to, $user_subject, $messagebody, $sender_name);
        }
    }

    /**
     * Fetch Admin templates
     *
     * @return Array|Bool
     */
    function fetch_admin_templates(){
        $admin_id = $this->ion_auth->user()->row()->id;
        if(!$admin_id) redirect('manage_admin/user/login');
        //
        return $this->bulk_email_model->fetch_admin_templates();
    }
}
