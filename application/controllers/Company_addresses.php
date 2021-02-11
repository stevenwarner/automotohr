<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Company_addresses extends Public_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('company_addresses_model');
    }

    public function index()
    {
        if ($this->session->userdata('logged_in')) {
            $data['title'] = 'Company Addresses';
            $data['session'] = $this->session->userdata('logged_in');

            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'company_addresses'); // Param2: Redirect URL, Param3: Function Name

            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];


            $addresses = $this->company_addresses_model->get_all_company_addresses($company_sid);
            $data['addresses'] = $addresses;

            $this->load->view('main/header', $data);
            $this->load->view('company_addresses/index');
            $this->load->view('main/footer');
        } else {
            redirect('login', "refresh");
        }
    }

    public function add_new_address()
    {
        if ($this->session->userdata('logged_in')) {
            $data['title'] = 'Add Company Addresses';
            $data['session'] = $this->session->userdata('logged_in');

            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;

            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];

            if (isset($_POST['submit']) && $_POST['submit'] == 'Save Address') {
                $address = $this->input->post('address');
                $insert_data = array();
                $insert_data['company_sid'] = $company_sid;
                $insert_data['address'] = $address;
                $insert_data['status'] = 1;
                $insert_data['date_created'] = date('Y-m-d H:i:s');

                $this->company_addresses_model->add_update_address($insert_data, 'add');

                $this->session->set_flashdata('message', '<b>Success:</b> New address added successfully');
                redirect("company_addresses", "location");

            }
            $data['form'] = 'add';
            $data['address'] = '';

            $this->load->view('main/header', $data);
            $this->load->view('company_addresses/add_new_address');
            $this->load->view('main/footer');
        } else {
            redirect('login', "refresh");
        }
    }

    public function edit_new_address($address_sid)
    {
        if ($this->session->userdata('logged_in')) {
            $data['title'] = 'Edit Company Addresses';
            $data['session'] = $this->session->userdata('logged_in');

            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;

            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];

            if (isset($_POST['submit']) && $_POST['submit'] == 'Update Address') {
                $address = $this->input->post('address');
                $select_status = $this->input->post('status');
                $insert_data = array();
                $insert_data['address'] = $address;
                $insert_data['status'] = $select_status;

                $this->company_addresses_model->add_update_address($insert_data, 'update', $address_sid);

                $this->session->set_flashdata('message', '<b>Success:</b> Address has been updated successfully');
                redirect("company_addresses", "location");

            }
            $data['form'] = 'update';
            $address = $this->company_addresses_model->get_company_address_by_id($address_sid);
            if (sizeof($address) > 0 && $address[0]['company_sid'] == $company_sid) {
                $data['address'] = $address[0]['address'];
                $data['status'] = $address[0]['status'];
            } else {
                $this->session->set_flashdata('message', '<b>Error:</b> Un Authorized Access');
                redirect("company_addresses", "location");
            }

            $this->load->view('main/header', $data);
            $this->load->view('company_addresses/add_new_address');
            $this->load->view('main/footer');
        } else {
            redirect('login', "refresh");
        }
    }

    public function delete_address()
    {
        $sid = $this->input->post('sid');
        if ($sid >= 1) {
            $this->company_addresses_model->delete_address($sid);
            echo 'success';
        } else {
            echo 'failure';
        }
    }
}