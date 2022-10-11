<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Complynet extends Admin_Controller {

    private $limit = 10;
    private $applicantLimit = 10;
    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/copy_applicants_model');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    //
    public function index() {
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, 'manage_admin', 'copy_applicants');
        $this->data['page_title'] = 'Manage Companies';
        $active_companies = $this->copy_applicants_model->get_all_companies();
        $this->data['active_companies'] = $active_companies;
        $applicants_type = array(   0 => 'Active Applicants',
                                    1 => 'Archived Applicants',
                                    2 => 'All Applicants');
        $this->data['applicants_type'] = $applicants_type;
        $this->form_validation->set_rules('copy_from', 'Copy From', 'trim|xss_clean|required|numeric');
        $this->form_validation->set_rules('applicants_type', 'Applicants Type', 'trim|xss_clean|required|numeric');
        $this->form_validation->set_rules('copy_to', 'Copy To', 'trim|xss_clean|required|numeric');

        $this->data['source'] = '';
        $this->data['destination'] = '';
        $this->data['type'] = '';

        if ($this->form_validation->run() === FALSE) {
            $this->render('manage_admin/complynet/manage_companies', 'admin_master');
        } else {
          
        }
    }


}