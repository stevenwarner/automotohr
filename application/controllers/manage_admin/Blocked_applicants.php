<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Blocked_applicants extends Admin_Controller {
    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->model('manage_admin/blocked_applicants_model');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    public function index() {
        $admin_id = $this->ion_auth->user()->row()->id;
        $redirect_url = 'manage_admin';
        $function_name = 'blocked_app';
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->form_validation->set_rules('perform_action', 'perform_action', 'trim|required');
        
        if ($this->form_validation->run() == false) {
            $this->data['title'] = 'Blocked Applicants';
            $this->data['subtitle'] = 'List Of All Blocked Applicants';
            $blocked_applicants = $this->blocked_applicants_model->get_all_records();
            $this->data['blocked_applicants'] = $blocked_applicants;
            $this->render('manage_admin/blocked_applicants/index', 'admin_master');
        } else {
            $perform_action = $this->input->post('perform_action');

            switch ($perform_action) {
                case 'unblock_applicant':
                    $applicant_email = $this->input->post('applicant_email');
                    $this->blocked_applicants_model->delete_record($applicant_email);
                    $this->session->set_flashdata('message', '<strong>Success</strong> Applicant email ' . $applicant_email . ' Unblocked');
                    redirect('manage_admin/blocked_applicants', 'refresh');
                    break;
                case 'bulk_unblock_applicants':
                    $sids = $this->input->post('sids');

                    if (!empty($sids)) {
                        $this->blocked_applicants_model->delete_records_by_sids($sids);
                    }

                    echo 'success';
                    break;
            }
        }
    }

    public function add_applicant() {
        $admin_id = $this->ion_auth->user()->row()->id;
        $redirect_url = 'manage_admin';
        $function_name = 'block_new_email';
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->data['title'] = 'Blocked Applicants';
            $this->data['subtitle'] = 'Add Applicant';
            $this->render('manage_admin/blocked_applicants/add_applicant', 'admin_master');
        } else {
            $applicant_email = $this->input->post('applicant_email');
            $status = $this->blocked_applicants_model->check_if_already_blocked($applicant_email);
            
            if($status == 'not-exists') {
                $this->blocked_applicants_model->insert_blocked_applicant($applicant_email);
                $this->session->set_flashdata('message', '<strong>Success</strong>: Applicant Email Blocked!');
            } else {
                $this->session->set_flashdata('message', '<strong>Warning</strong>: Applicant Email is already Blocked!');
            }
            
            redirect('manage_admin/blocked_applicants', 'refresh');
        }
    }

}
