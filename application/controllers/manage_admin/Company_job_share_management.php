<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Company_job_share_management extends Admin_Controller {
    function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('manage_admin/company_job_share_model');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }


    public function index($company_sid) {
        $redirect_url = 'manage_admin/companies';
        $function_name = 'manage_executive_admins';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name

        $this->data['company_sid'] = $company_sid;

        $standard_companies = array();
        $corporate_companies = array();
        $access_companies = array();
        $this->data['page_title'] = 'Manage Job Share With Company ';

        $this->form_validation->set_rules('perform_action', 'Perform Action', 'required|trim|xss_clean');

        if ($this->form_validation->run() == false) {

            $all_companies = $this->company_job_share_model->get_all_admin_companies();

            foreach($all_companies as $company_data){
                if($company_data['career_page_type']=='standard_career_site'){
                    $standard_companies[] = $company_data;
                } else {
                    $corporate_companies[] = $company_data;
                }
            }
            $configured_companies = array();
            $companies = $this->company_job_share_model->get_configured_companies($company_sid);
            if(sizeof($companies)>0){
                foreach($companies as $com){
                    $configured_companies[] = $com['linked_company_sid'];
                }
            }
            $this->data['configured_companies'] = $configured_companies;

            $this->data['standard_companies'] = $standard_companies;
            $this->data['corporate_companies'] = $corporate_companies;
            $this->data['access_companies'] = $access_companies;
            $this->render('manage_admin/company/company_job_share_view');
        } else {
            $perform_action = $this->input->post('perform_action');

            switch ($perform_action) {
                case 'enable_company_access':
                    $linked_company_sid = $this->input->post('company_sid');
                    $company_sid = $this->input->post('admin_company_sid');

                    $data = array(
                        'company_sid' => $company_sid,
                        'linked_company_sid' => $linked_company_sid,
                        'status' => 1,
                        'date_activated' => date('Y-m-d H:i:s')
                    );

                    $this->company_job_share_model->add_update_company($data);
                    echo 'success';
                    break;
                case 'disable_company_access':
                    $linked_company_sid = $this->input->post('company_sid');
                    $company_sid = $this->input->post('admin_company_sid');

                    $data = array(
                        'company_sid' => $company_sid,
                        'linked_company_sid' => $linked_company_sid,
                        'status' => 0,
                        'date_deactivated' => date('Y-m-d  H:i:s')
                    );

                    $this->company_job_share_model->add_update_company($data);
                    echo 'success';
                    break;
            }

            if ($this->input->post('is_ajax_request') == 1) {
                exit;
            }
        }
    }

}