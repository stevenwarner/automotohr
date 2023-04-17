<?php defined('BASEPATH') or exit('No direct script access allowed');

class Benefits extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/Benefits_model');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    public function index()
    {
        $redirect_url = 'manage_admin';
        $function_name = 'benifits';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name);
        $this->data['page_title'] = 'Benefits Listing';
        $this->data['benifits'] = $this->Benefits_model->GetAllBenifits();
        $this->render('manage_admin/benefits/index', 'admin_master');
    }

    public function add_edit($sid = NULL)
    {
        $redirect_url = 'manage_admin';
        $function_name = 'add_edit_job_listing_templates';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name);
        $action = $this->input->post('action');

        if ($action == 'save_benifit') {
            $sid = $this->input->post('sid');

            $data_insert['name'] = $this->input->post('benifitname');
            $data_insert['category'] = $this->input->post('categoryname');
            $data_insert['benefit_type'] = $this->input->post('benefittype');
            $data_insert['description'] = $this->input->post('description');
            $data_insert['pretax'] = $this->input->post('pretax') ? '1' : '0';
            $data_insert['posttax'] = $this->input->post('posttax') ? '1' : '0';
            $data_insert['imputed'] = $this->input->post('imputed') ? '1' : '0';
            $data_insert['healthcare'] = $this->input->post('healthcare') ? '1' : '0';
            $data_insert['retirement'] = $this->input->post('retirement') ? '1' : '0';
            $data_insert['yearly_limit'] = $this->input->post('yearlylimit') ? '1' : '0';

            if ($sid == '') {
                $sid = null;
            }

            $this->form_validation->set_rules('benifitname', 'Name', 'trim|required');

            if ($this->form_validation->run() === FALSE) {
            } else {
                $this->Benefits_model->SaveBenifit($sid, $data_insert);
                redirect('manage_admin/benefits');
            }
        } else if ($action == 'enable_disable_benefit') {
            $sid = $this->input->post('sid');
            $data_update['status'] = $this->input->post('status') ? '0' : '1';
            $this->Benefits_model->UpdateBenifit($sid,  $data_update);
            redirect('manage_admin/benefits');
        }

        if ($sid == NULL) {
            $this->data['page_title'] = 'Add New Job Benefit';
            $this->render('manage_admin/benefits/add_edit', 'admin_master');
        } else {
            $this->data['benifit'] = $this->Benefits_model->GetBenifitById($sid);
            $this->data['page_title'] = 'Edit Benifit';
            $this->render('manage_admin/benefits/add_edit', 'admin_master');
        }
    }
}
