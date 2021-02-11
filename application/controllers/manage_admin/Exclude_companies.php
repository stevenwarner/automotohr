<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Exclude_companies extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('manage_admin/exclude_companies_model');
        $this->load->model('manage_admin/company_model');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    function index()
    {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url       = 'manage_admin';
        $function_name      = 'exclude_companies';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        $this->data['page_title'] = 'Exclude Companies';

        $excluded_companies = $this->exclude_companies_model->get_excluded_companies();
        $this->data['excluded_companies'] = $excluded_companies;
        $this->render('manage_admin/exclude_companies/listings_view');
    }

    function add_exclude_company()
    {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url       = 'manage_admin';
        $function_name      = 'add_exclude_company';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        $this->data['page_title'] = 'Add Exclude Company';
        $this->data['companies'] = $this->exclude_companies_model->get_all_ahr_companies(); // company_model->get_all_companies();

        if (isset($_POST['submit'])) {
            $companies = $this->input->post('companies');
            $companies_names = $this->input->post('company_name');
            $companies_webs = $this->input->post('company_website');

            foreach ($companies as $company) {
                $data = array(
                    'company_sid' => $company,
                    'company_name' => $companies_names[$company]
                );

                $this->exclude_companies_model->exclude_company($data, $company);
            }

            $this->session->set_flashdata('message', 'New companies excluded from reporting.');
            redirect('manage_admin/exclude_companies');
        }

        $this->render('manage_admin/exclude_companies/add_exclude_company');
    }

    public function remove_excluded_company_ajax()
    {
        $sid = $this->input->post('sid');

        if ($sid == NULL || $sid == 0) {
            $result = false;
        } else {
            $result = $this->exclude_companies_model->remove_excluded_company($sid);
        }

        echo json_encode($result);
    }
}
