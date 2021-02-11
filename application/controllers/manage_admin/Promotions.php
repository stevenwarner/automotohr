<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Promotions extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/promotions_model');
        //$this->load->library("pagination");
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    public function index()
    {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url       = 'manage_admin';
        $function_name      = 'list_promotions';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        $this->data['page_title'] = 'Manage Promotions';

        $this->data['data'] = $this->promotions_model->get_all_promotions();
        $this->render('manage_admin/promotions/listings_view', 'admin_master');
    }

    public function add_new_promotion()
    {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url       = 'manage_admin';
        $function_name      = 'add_new_promotion';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        $this->data['page_title'] = 'Add New Promotional code';
        $this->load->library('form_validation');
        $this->form_validation->set_rules('code', 'Promotion Code', 'trim|required|is_unique[promotions.code]');
        $this->form_validation->set_rules('discount', 'Discount', 'trim|required|greater_than[0]');
        $this->form_validation->set_rules('type', 'type', 'trim');
        $this->form_validation->set_rules('start_date', 'Start Date', 'trim');
        $this->form_validation->set_rules('end_date', 'End Date', 'trim');
        $this->form_validation->set_rules('maximum_uses', 'maximum_uses', 'trim|numeric');
        $this->form_validation->set_rules('active', 'active', 'trim');
        if ($this->form_validation->run() === FALSE) {
            $this->load->helper('form');
            $this->render('manage_admin/promotions/add_new_promotion');
        } else {
            $code = $this->input->post('code');
            $discount = $this->input->post('discount');
            $type = $this->input->post('type');
            $start_date = $this->input->post('start_date');
            $end_date = $this->input->post('end_date');
            $maximum_uses = $this->input->post('maximum_uses');
            $active = $this->input->post('active');

            $this->promotions_model->save_coupon($code, $discount, $type, $start_date, $end_date, $maximum_uses, $active);
            redirect('manage_admin/promotions', 'refresh');
        }
    }

    public function edit_promotion($sid = NULL)
    {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url       = 'manage_admin';
        $function_name      = 'edit_promotion';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        $sid = $this->input->post('sid') ? $this->input->post('sid') : $sid;
        $this->data['page_title'] = 'Edit Promotion';
        $this->load->library('form_validation');
        $promotion_detail = $this->promotions_model->promotion_details($sid);

        if (isset($_POST) && $promotion_detail[0]['code'] != $this->input->post('code')) {
            $this->form_validation->set_rules('code', 'Promotion Code', 'trim|required|is_unique[promotions.code]');
        } else {
            $this->form_validation->set_rules('code', 'Promotion Code', 'trim|required');
        }

        $this->form_validation->set_rules('discount', 'Discount', 'trim|required|greater_than[0]');
        $this->form_validation->set_rules('type', 'type', 'trim');
        $this->form_validation->set_rules('start_date', 'Start Date', 'trim');
        $this->form_validation->set_rules('end_date', 'End Date', 'trim');
        $this->form_validation->set_rules('maximum_uses', 'maximum_uses', 'trim|numeric');
        $this->form_validation->set_rules('active', 'active', 'trim');

        if ($this->form_validation->run() === FALSE) {
            if ($promotion_detail) {
                $this->data['promotion'] = $promotion_detail[0];
            } else {
                $this->session->set_flashdata('message', 'Promotion Code does not exists!');
                redirect('manage_admin/promotions', 'refresh');
            }
            $this->load->helper('form');
            $this->render('manage_admin/promotions/edit_promotion');
        } else {
            $sid = $this->input->post('sid');
            $code = $this->input->post('code');
            $discount = $this->input->post('discount');
            $type = $this->input->post('type');
            $start_date = $this->input->post('start_date');
            $end_date = $this->input->post('end_date');
            $maximum_uses = $this->input->post('maximum_uses');
            $active = $this->input->post('active');
            $this->promotions_model->edit_coupon($sid, $code, $discount, $type, $start_date, $end_date, $maximum_uses, $active);
            redirect('manage_admin/promotions', 'refresh');
        }
    }

    function checkDateFormat($date)
    {
        if (preg_match("/[0-12]{2}-[0-31]{2}-[0-9]{4}/", $date)) {
            if (checkdate(substr($date, 0, 2), substr($date, 3, 2), substr($date, 6, 4))) {
                return true;
            } else {
                $this->form_validation->set_message('checkDateFormat', 'Please Provide Date in MM-DD-YYYY Format');
                return false;
            }
        } else {
            if (!empty($date)) {
                $this->form_validation->set_message('checkDateFormat', 'Please Provide Date in MM-DD-YYYY Format ' . $date);
                return false;
            } else {
                return true;
            }
        }
    }

    function promotion_task()
    {
        $action = $this->input->post("action");
        $promotion_id = $this->input->post("sid");

        if ($action == 'delete') {
            $this->promotions_model->delete_promotion($promotion_id);
        } else if ($action == 'activate') {
            $this->promotions_model->active_promotion($promotion_id);
            $this->session->set_flashdata('message', 'Promotion Code Activated Successfully!');
        } else if ($action == 'deactivate') {
            $this->promotions_model->deactive_promotion($promotion_id);
            $this->session->set_flashdata('message', 'Promotion Code Deactivated Successfully!');
        }
    }

}
