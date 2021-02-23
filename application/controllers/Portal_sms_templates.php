<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Portal_sms_templates extends Public_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('portal_sms_templates_model');
        if ($this->session->userdata('logged_in') && !isset($_SESSION['logged_in']['default_template'])) {
            $company_sid = $this->session->userdata('logged_in')['company_detail']['sid'];
            $this->portal_sms_templates_model->check_default_templates($company_sid);
            $_SESSION['logged_in']['default_template'] = 1;
        }
    }

    public function index() {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'portal_email_templates');
            $employer_id = $data['session']['employer_detail']['sid'];
            $company_id = $data['session']['company_detail']['sid'];
            $company_name = $data['session']['company_detail']['CompanyName'];
            $data['title'] = 'SMS Templates Module';
            // Push new default emails 
            // for employees
//            $this->portal_sms_templates_model->check_default_tables($company_id, $data['session']['employer_detail']['email'], $company_name);
            $data['all_templates'] = $this->portal_sms_templates_model->getallsmstemplates($company_id);
            $data['employer_id'] = $employer_id;
            $data['company_id'] = $company_id;
            $data['company_name'] = $company_name;

            $this->load->view('main/header', $data);
            $this->load->view('sms_templates/portal_sms_templates');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), 'refresh');
        }
    }

    public function add_sms_template() {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'portal_email_templates');
            $employer_id = $data['session']['employer_detail']['sid'];
            $company_id = $data['session']['company_detail']['sid'];
            $data['title'] = 'SMS Templates Module';
            $names = array();
            $temp_names = $this->portal_sms_templates_model->get_company_custom_template_names($company_id);
            foreach($temp_names as $name){
                $names[] = strtolower($name['template_name']);
            }
            $data['names'] = json_encode($names);
            if (isset($_POST['action']) && $_POST['action'] == 'add_sms_template') {
                $template_body = $this->input->post('txt_message');
                $template_name = $this->input->post('template_name');
                $data_to_insert = array();
                $data_to_insert['template_code'] = strtolower(str_replace(' ', '_', trim($template_name)));
                $data_to_insert['template_name'] = $template_name;
                $data_to_insert['company_sid'] = $company_id;
                $data_to_insert['created_date'] = date('Y-m-d H:i:s');
                $data_to_insert['is_custom'] = 1;
                $data_to_insert['status'] = $this->input->post('status');
                $data_to_insert['sms_body'] = $template_body;


                $template_sid = $this->portal_sms_templates_model->insert_portal_sms_template($data_to_insert);

                $this->session->set_flashdata('message', '<b>Success:</b> SMS template <b><i>' . $template_name . ' </b></i>Added successfully');
                redirect('portal_sms_templates', 'refresh');
            }

            $this->load->view('main/header', $data);
            $this->load->view('sms_templates/portal_add_sms_templates');
            $this->load->view('main/footer');
        } else { // customer is not logged in
            redirect(base_url('login'), "refresh");
        }
    }

    public function edit_sms_template($sid = null) {
        if ($this->session->userdata('logged_in')) {
            if (!empty($sid) || $sid != null) {
                $data['session'] = $this->session->userdata('logged_in');
                $security_sid = $data['session']['employer_detail']['sid'];
                $security_details = db_get_access_level_details($security_sid);
                $data['security_details'] = $security_details;
                check_access_permissions($security_details, 'my_settings', 'portal_email_templates');
                $employer_id = $data['session']['employer_detail']['sid'];
                $company_id = $data['session']['company_detail']['sid'];
                $data['title'] = 'SMS Templates Module';
                $template_data = $this->portal_sms_templates_model->getTemplateDetails($sid, $company_id);

                if (!empty($template_data)) {
                    $data['employer_id'] = $employer_id;
                    $data['company_id'] = $company_id;
                    $data['template_data'] = $template_data[0];

                    $names = array();
                    $temp_names = $this->portal_sms_templates_model->get_company_custom_template_names($company_id,$sid);
                    foreach($temp_names as $name){
                        $names[] = strtolower($name['template_name']);
                    }
                    $data['names'] = json_encode($names);
                    if (isset($_POST['action']) && $_POST['action'] == 'edit_email_template') {
                        $formpost = $this->input->post(NULL, TRUE);
                        $template_name = $formpost['template_name'];
                        $sms_body = $formpost['txt_message'];
                        $data_to_save = array();
                        $data_to_save['template_code'] = strtolower(str_replace(' ', '_', trim($template_name)));
                        $data_to_save['template_name'] = $template_name;
                        $data_to_save['status'] = $formpost['status'];
                        $data_to_save['sms_body'] = $sms_body;
                        $this->portal_sms_templates_model->update_sms_template($data_to_save, $sid);
                        $this->session->set_flashdata('message', '<b>Success:</b> Email template <b><i>' . $template_name . ' </b></i>updated successfully');
                        redirect('portal_sms_templates', 'refresh');
                    }
                    //
                    $data['magic_tags'] = $this->get_sms_magic_tags();
                    //
                    $this->load->view('main/header', $data);
                    $this->load->view('sms_templates/portal_edit_sms_templates');
                    $this->load->view('main/footer');

                } else { // template not found
                    $this->session->set_flashdata('message', '<b>Error:</b> SMS template not found!');
                    redirect('portal_sms_templates', 'refresh');
                }
            } else { // template id not found
                $this->session->set_flashdata('message', '<b>Error:</b> SMS template not found!');
                redirect('portal_sms_templates', 'refresh');
            }
        } else { // customer is not logged in
            redirect(base_url('login'), "refresh");
        }
    }

    public function ajax_handler(){
        if($this->input->is_ajax_request()){
            $id = $this->input->post('id');
            $this->portal_sms_templates_model->delete_custom_email_template($id);
            echo 1;
        }else{
            echo 0;
        }
    }

    public function get_sms_magic_tags () {
        $magic_tags = array();
        //
        $magic_tags['Billing and Invoice'][0] = "{{invoice_number}}";
        $magic_tags['Billing and Invoice'][2] = "{{contact_name}}";
        //
        $magic_tags['New Applicant'][0] = "{{job_title}}";
        $magic_tags['New Applicant'][2] = "{{contact_name}}";
        //
        $magic_tags['Employment Application'][0] = "{{applicant_name}}";
        $magic_tags['Employment Application'][2] = "{{contact_name}}";
        //
        $magic_tags['License Expiration'][0] = "{{applicant_name}}";
        $magic_tags['License Expiration'][1] = "{{license_type}}";
        $magic_tags['License Expiration'][3] = "{{contact_name}}";
        //
        $magic_tags['Onboarding Request'][0] = "{{applicant_name}}";
        $magic_tags['Onboarding Request'][2] = "{{contact_name}}";
        //
        $magic_tags['Offer Letter'][0] = "{{applicant_name}}";
        $magic_tags['Offer Letter'][2] = "{{contact_name}}";
        //
        $magic_tags['New Job Listing'][0] = "{{job_title}}";
        $magic_tags['New Job Listing'][2] = "{{contact_name}}";
        //
        $magic_tags['New Calendar Event Template'][0] = "{{category_name}}";
        $magic_tags['New Calendar Event Template'][1] = "{{applicant_name}}";
        $magic_tags['New Calendar Event Template'][2] = "{{profile_link}}";
        $magic_tags['New Calendar Event Template'][3] = "{{resume_link}}";
        $magic_tags['New Calendar Event Template'][4] = "{{event_url}}";
        $magic_tags['New Calendar Event Template'][5] = "{{event_button}}";
        $magic_tags['New Calendar Event Template'][7] = "{{contact_name}}";
        //
        $magic_tags['Update Calendar Event Template'][0] = "{{category_name}}";
        $magic_tags['Update Calendar Event Template'][1] = "{{applicant_name}}";
        $magic_tags['Update Calendar Event Template'][2] = "{{event_url}}";
        $magic_tags['Update Calendar Event Template'][3] = "{{event_button}}";
        $magic_tags['Update Calendar Event Template'][5] = "{{contact_name}}";
        //
        $magic_tags['New Announcement Notification'][1] = "{{contact_name}}";
        //
        $magic_tags['Edit Announcement Notification'][0] = "{{title}}";
        $magic_tags['Edit Announcement Notification'][1] = "{{event_start_date}}";
        $magic_tags['Edit Announcement Notification'][2] = "{{event_end_date}}";
        $magic_tags['Edit Announcement Notification'][4] = "{{contact_name}}";
        //
        $magic_tags['Hr Document Notification'][0] = "{{first_name}}";
        $magic_tags['Hr Document Notification'][1] = "{{last_name}}";
        $magic_tags['Hr Document Notification'][2] = "{{url}}";
        $magic_tags['Hr Document Notification'][4] = "{{contact_name}}";
        //
        return $magic_tags;
    }


}