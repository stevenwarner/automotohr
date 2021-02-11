<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Oem_manufacturers extends Admin_Controller {
    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/oem_manufacturers_model');
        $this->load->model('manage_admin/company_model');
        $this->load->library('form_validation');
        //$this->load->library("pagination");
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    public function index() {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url       = 'manage_admin';
        $function_name      = 'oem_manufacturers';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //
        $this->data['page_title'] = 'OEM, Independent, Vendor';
        $this->data['oem_brands'] = $this->oem_manufacturers_model->get_all_oem_manufacturers();
        $this->render('manage_admin/oem_manufacturers/oem_manufacturers_listing');
    }

    public function add_oem_manufacturer() {
        $redirect_url       = 'manage_admin/oem_manufacturers';
        $function_name      = 'add_oem_manufacturer';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->form_validation->set_rules('oem_brand_name', 'OEM, Independent, Vendor Name', 'required|trim|xss_clean|is_unique[oem_brands.oem_brand_name]');
        $this->form_validation->set_rules('brand_website', 'OEM, Independent, Vendor Website', 'trim|xss_clean');
        
        if ($this->form_validation->run() === FALSE) {
            $this->data['page_title'] = 'Add New OEM, Independent, Vendor';
            $this->render('manage_admin/oem_manufacturers/add_oem_manufacturer');
        } else {
            $oem_data = array();
            $oem_data['oem_brand_name'] = $this->input->post('oem_brand_name');
            $oem_data['brand_website'] = $this->input->post('brand_website');
            $oem_data['brand_status'] = 'active';
            $oem_data['create_date'] = date('Y-m-d H:i:s');
            $result = $this->oem_manufacturers_model->add_oem_manufacturer($oem_data);
            $this->session->set_flashdata('message', 'New OEM, Independent, Vendor added successfully.');
            redirect('manage_admin/oem_manufacturers');
        }
    }

    public function oem_manufacturer_delete_ajax() {
        $oem_sid = $this->input->post('oem_sid');

        if ($oem_sid == NULL || $oem_sid == 0) {
            $result = false;
        } else {
            $result = $this->oem_manufacturers_model->oem_manufacturer_delete($oem_sid);
        }

        echo json_encode($result);
    }

    public function manage_oem_manufacturer($oem_sid) {       
        if ($oem_sid == 0 || $oem_sid == NULL) {
            $this->session->set_flashdata('message', 'OEM, Independent, Vendor does not exist');
            redirect('manage_admin/oem_manufacturers');
        }
        
        $redirect_url       = 'manage_admin/oem_manufacturers';
        $function_name      = 'manage_oem_manufacturer';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        
        $this->data['page_title'] = 'Manage OEM, Independent, Vendor';
        $this->data['brand_companies'] = $this->oem_manufacturers_model->get_brand_companies($oem_sid);
        $brand = $this->oem_manufacturers_model->get_oem_manufacturer($oem_sid);

        if (isset($brand[0])) {
            $this->data['oem_brand'] = $brand[0];
        } else {
            $this->session->set_flashdata('message', 'OEM, Independent, Vendor does not exist');
            redirect('manage_admin/oem_manufacturers');
        }

        $this->render('manage_admin/oem_manufacturers/manage_oem_manufacturers');
    }

    public function edit_oem_manufacturer($oem_sid) {
        if ($oem_sid == 0 || $oem_sid == NULL) {
            $this->session->set_flashdata('message', 'OEM, Independent, Vendor does not exist');
            redirect('manage_admin/oem_manufacturers');
        }
        
        $redirect_url       = 'manage_admin/oem_manufacturers';
        $function_name      = 'add_oem_manufacturer';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        
        $this->data['page_title'] = 'Edit OEM, Independent, Vendor';
        $brand = $this->oem_manufacturers_model->get_oem_manufacturer($oem_sid);

        if (isset($brand[0])) {
            $this->data['oem_brand'] = $brand[0];
        } else {
            $this->session->set_flashdata('message', 'OEM, Independent, Vendor does not exist');
            redirect('manage_admin/oem_manufacturers');
        }

        if ($brand[0]["oem_brand_name"] != $this->input->post('oem_brand_name')) {
            $this->form_validation->set_rules('oem_brand_name', 'OEM, Independent, Vendor Name', 'required|trim|xss_clean|is_unique[oem_brands.oem_brand_name]');
        } else {
            $this->form_validation->set_rules('oem_brand_name', 'OEM, Independent, Vendor Name', 'required|trim|xss_clean');
        }

        $this->form_validation->set_rules('brand_website', 'OEM, Independent, Vendor Website', 'trim|xss_clean');

        if ($this->form_validation->run() === FALSE) {
            
        } else {
            $update_data = array();
            $update_data['oem_brand_name'] = $this->input->post('oem_brand_name');
            $update_data['brand_website'] = $this->input->post('brand_website');
            $update_data['modified_date'] = date('Y-m-d H:i:s');

            $this->oem_manufacturers_model->update_oem_manufacturer($update_data, $oem_sid);
            $this->session->set_flashdata('message', 'OEM, Independent, Vendor updated successfully');
            redirect('manage_admin/oem_manufacturers');
        }

        $this->render('manage_admin/oem_manufacturers/edit_oem_manufacturer');
    }

    public function add_manufacturer_company($oem_sid) {
        // check user login
        $admin_id = $this->session->userdata('user_id');
        if (!isset($admin_id) || $admin_id == 0 || $admin_id == NULL) {
            redirect(base_url('login'), "refresh");
        }

        if ($oem_sid == 0 || $oem_sid == NULL) {
            $this->session->set_flashdata('message', 'OEM, Independent, Vendor does not exist');
            redirect('manage_admin/oem_manufacturers');
        }

        // check whether brand is in deleted mode or not
        $brand = $this->oem_manufacturers_model->get_oem_manufacturer($oem_sid);
        if (isset($brand[0])) {
            $this->data['oem_brand'] = $brand[0];
        } else {
            $this->session->set_flashdata('message', 'OEM, Independent, Vendor does not exist');
            redirect('manage_admin/oem_manufacturers');
        }

        $this->data['page_title'] = 'Add New OEM, Independent, Vendor Company';
        $this->data['oem_sid'] = $oem_sid;
        $this->data['companies'] = $this->company_model->get_all_companies();

        if (isset($_POST['submit'])) {
            $companies = $this->input->post('companies');
            $companies_names = $this->input->post('company_name');
            $companies_webs = $this->input->post('company_website');

            foreach ($companies as $company) {
                $data = array(
                    'oem_brand_sid' => $oem_sid,
                    'company_sid' => $company,
                    'company_name' => $companies_names[$company]
                );

                $this->oem_manufacturers_model->add_brand_company($data, $company);
            }

            $this->session->set_flashdata('message', 'New companies added to OEM, Independent, Vendor.');
            redirect('manage_admin/oem_manufacturers/manage_oem_manufacturer/' . $oem_sid);
        }

        $this->render('manage_admin/oem_manufacturers/add_oem_manufacturer_company');
    }

    public function remove_oem_company_ajax() {
        $sid = $this->input->post('sid');

        if ($sid == NULL || $sid == 0) {
            $result = false;
        } else {
            $result = $this->oem_manufacturers_model->remove_oem_company($sid);
        }

        echo json_encode($result);
    }

}
