<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Company_security_settings extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/security_model');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    public function index($company_sid = null) { 
        $redirect_url       = 'manage_admin/companies';
        $function_name      = 'edit_company';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name

        if($company_sid != null && is_numeric($company_sid)){
            if(isset($_POST) && !empty($_POST)){ // we need a model for this controller and have to switch the update query over there
                $sid                                                            = $_POST['sid'];
                $access_level                                                   = $_POST['access_level'];
                $is_primary_admin                                               = $_POST['is_primary_admin'];

                for($i = 0; $i < sizeof($access_level); $i++){
                    $data                                                       = array();
                    $data['access_level']                                       = $access_level[$i];
                    $data['is_primary_admin']                                   = $is_primary_admin[$i];

                    $this->db->where('sid', $sid[$i]);
                    $this->db->update('users', $data);
//                    echo $this->db->last_query();
//                    exit;
                }

                $this->session->set_flashdata('message', 'Success: Company Security Access Updated Successfully!');
                redirect('manage_admin/companies/manage_company/' . $company_sid, 'refresh');
            }

            $this->data['page_title']                                           = 'Company Security Access Manager';
            $this->data['company_sid']                                          = $company_sid;
            $this->data['company_employees']                                    = $this->security_model->get_company_users($company_sid);
            $this->data['access_level']                                         = $this->security_model->get_security_access_levels();
            $this->render('manage_admin/company/company_security_settings', 'admin_master');
        } else {
            $this->session->set_flashdata('message', 'Error: Company not found!');
            redirect('manage_admin/companies', 'refresh');
        }
    }
}
?>