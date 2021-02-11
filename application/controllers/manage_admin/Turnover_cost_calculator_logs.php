<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Turnover_cost_calculator_logs extends Admin_Controller
{

    function __construct()
    {
        parent::__construct();
        
        $this->load->model('turnover_cost_calculator_model');
    }

    public function index()
    {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url = 'manage_admin';
        $function_name = 'turnover_cost_logs';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //


        if ($this->form_validation->run() == false) {

            $page_visit_count = $this->turnover_cost_calculator_model->get_page_visit_count();
            $this->data['page_visit_count'] = $page_visit_count;

            $calculations = $this->turnover_cost_calculator_model->get_all_turnover_cost_calculation_records();
            $this->data['calculations'] = $calculations;

            $this->render('manage_admin/turnover_cost_calculator_logs/index', 'admin_master');
        } else {
            //Handle Post
        }

    }


}