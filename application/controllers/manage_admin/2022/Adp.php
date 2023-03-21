<?php defined('BASEPATH') or exit('No direct script access allowed');

class Adp extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('manage_admin/company_model');
        $this->load->model('2022/Adp_model', 'adp_model');
        $this->load->model('employee_model');
    }


    function adp_settings($company_sid = null)
    {
        if ($company_sid == NULL || $company_sid == '' || $company_sid == 0) {
            $this->session->set_flashdata('message', 'Company not found!');
            redirect('manage_admin/companies/', 'refresh');
        }
        $redirect_url = 'manage_admin/companies';
        $function_name = 'edit_company';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->form_validation->set_rules('status', 'Status', 'required|trim|xss_clean');

        $this->data['company_sid'] = $company_sid;
        $company_name = $this->company_model->get_company_name($company_sid);
        $this->data['company_name'] = $company_name;
        $this->data['adp_company_data'] = $this->adp_model->get_adp_company_data($company_sid);

        //
        $this->data['employees'] = $this->adp_model->getOnADPEmployees($company_sid);
        //
        $this->data['offADPEmployees'] = $this->adp_model->getOffADPEmployees(
            array_column($this->data['employees'], 'sid'),
            $company_sid
        );

        //GET ADP Employees
        $adpEmployees = $this->adp_get_employees();

        $this->data['adpEmployees'] = json_decode($adpEmployees);
        $this->data['page_title'] = 'ADP Settings';
        $this->render('manage_admin/adp_settings');
    }

    //
    function saveAdpEmployees()
    {
        $formpost = $this->input->post(NULL, TRUE);
        if (!empty($formpost)) {
            foreach ($formpost['holdEmployeeLinks'] as $formdata) {
                $this->adp_model->add_update_adp_employees($formdata['employeeId'], $formdata['AdpEmployeeAssociateId'], $formdata['AdpEmployeeWorkerId']);
            }
        }
    }

    //
    function adp_get_employees($associateId = '')
    {
        $associateLink = $associateId  ? '/' . $associateId : '';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, getCreds('AHR')->API_SERVER_URL . 'adp/workers' . $associateLink);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        $result = curl_exec($curl);
        curl_close($curl);
        if (!$result) {
            $result = json_encode([]);
        }
        return $result;
    }

    //
    public function adp_enable_disable()
    {
        $formpost = $this->input->post(NULL, TRUE);
        $company_sid = $formpost['CompanyId'];
        if ($formpost['Status'] == 0) {
            $dataToUpdate['status'] =  1;
        } elseif ($formpost['Status'] == 1) {
            $dataToUpdate['status'] =  0;
        }
        $this->adp_model->update_adp_company_settings($company_sid, $dataToUpdate);
    }



    //
    function deleteAdpEmployee()
    {
        $formpost = $this->input->post(NULL, TRUE);
        if (!empty($formpost["Id"])) {
            $this->adp_model->delete_ADP_employyee($formpost['Id']);
        }
    }


    //
    function adpEmployeeDetail($associateId)
    {
        $employeedata  = $this->adp_get_employees($associateId);
        $this->data['adpemployeedata'] = json_decode($employeedata );
         //
         return SendResponse(
            200,
            [
                'view' => $this->load->view('manage_admin/adp_employee_details', $this->data, true)
            ]
        );
    }



    //
    public function adp_employee_report()
    {

        $this->data['companies'] = $this->adp_model->getCompanies('active');
        $this->data['companysid'] = 0;
        $formpost = $this->input->post(NULL, TRUE);

        if ($formpost['companySid'] > 0) {
            $companyId = $formpost['companySid'];
            $this->data['employees'] = $this->adp_model->getOnADPEmployees($companyId);
            //
            $this->data['offADPEmployees'] = $this->adp_model->getOffADPEmployees(
                array_column($this->data['employees'], 'sid'),
                $companyId
            );

            $this->data['companysid'] = $companyId;
        }
        $this->data['page_title'] = 'ADP Report';
        $this->render('manage_admin/adp_report');
    }
}
