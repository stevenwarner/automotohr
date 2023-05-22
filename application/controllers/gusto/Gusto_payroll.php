<?php defined('BASEPATH') || exit('No direct script access allowed');

class Gusto_payroll extends CI_Controller
{
    /**
     * Main entry point to controller
     */
    public function __construct()
    {
        parent::__construct();
        // Call the model
        $this->load->model("gusto/Gusto_payroll_model", "gusto_payroll_model");
    }

    /**
     * Add admin
     *
     * @param int $companyId
     */
    public function addAdmin(int $companyId)
    {
        //
        $post = $this->input->post(null, true);
        //
        $admin = $this->db
            ->select('sid, gusto_uuid')
            ->where('company_sid', $companyId)
            ->where('email_address', $post['emailAddress'])
            ->get('payroll_company_admin')
            ->row_array();
        // already exists
        if ($admin) {
            //
            if ($admin['gusto_uuid']) {
                return SendResponse(200, ['error' => 'Admin already exists.']);
            }
            // fetch all admins
            $gustoAdmins = $this->gusto_payroll_model->fetchAllAdmins($companyId);
            //
            $this->db
                ->where('sid', $admin['sid'])
                ->update('payroll_company_admin', [
                    'gusto_uuid' => $gustoAdmins[$admin['email_address']]
                ]);
            //
            return SendResponse(200, ['error' => 'Admin already exists.']);
        }
        // add a new one
        $response = $this->gusto_payroll_model->moveAdminToGusto([
            'first_name' => $post['firstName'],
            'last_name' => $post['lastName'],
            'email' => $post['emailAddress']
        ], $companyId);

        if ($response['errors']) {
            //
            return SendResponse(
                200,
                [
                    'errors' => $response['errors']
                ]
            );
        }
        //
        return SendResponse(
            200,
            [
                'success' => 'You have successfully added an admin.'
            ]
        );
    }

    /**
     * Get admins
     *
     * @param int $companyId
     */
    public function getAdmins(int $companyId)
    {
        // fetch all admins
        $gustoAdmins = $this->gusto_payroll_model->fetchAllAdmins($companyId);
        // get all admins
        $admins = $this->db
            ->select('sid, gusto_uuid, first_name, last_name, email_address, created_at')
            ->where('company_sid', $companyId)
            ->order_by('sid', 'desc')
            ->get('payroll_company_admin')
            ->result_array();
        //
        if ($admins) {
            //
            foreach ($admins as $key => $admin) {
                //
                if (!$admin['gusto_uuid']) {
                    //
                    if (!isset($gustoAdmins[$admin['email_address']])) {
                        // move to Gusto first
                        $response = $this->gusto_payroll_model->moveAdminToGusto([
                            'first_name' => $admin['first_name'],
                            'last_name' => $admin['last_name'],
                            'email' => $admin['email_address']
                        ], $companyId);
                        //
                        $admins[$key]['gusto_uuid'] = $response['uuid'];
                    } else {
                        //
                        $this->db
                            ->where('company_sid', $companyId)
                            ->where('email_address', $admin['email_address'])
                            ->update('payroll_company_admin', [
                                'gusto_uuid' => $gustoAdmins[$admin['email_address']]['uuid']
                            ]);
                        //
                        $admins[$key]['gusto_uuid'] = $gustoAdmins[$admin['email_address']]['uuid'];
                    }
                }
            }
        }
        //
        //$data['session'] = $this->session->userdata('logged_in');
        //  $company_sid = $data['session']['company_detail']['sid'];


        return SendResponse(
            200,
            [
                'view' => $this->load->view('gusto/admins/view', ['admins' => $admins, 'companySid' => $companyId], true)
            ]
        );
    }

    /**
     * Get signatories
     *
     * @param int $companyId
     * @return json
     */
    public function getSignatories(int $companyId)
    {
        // fetch all signatories
        $this->gusto_payroll_model->fetchAllSignatories($companyId);
        // get all signatories
        $signatories = $this->db
            ->where('company_sid', $companyId)
            ->where('is_deleted', 0)
            ->order_by('sid', 'desc')
            ->get('payroll_signatories')
            ->result_array();
        //
        return SendResponse(
            200,
            [
                'view' => $this->load->view('gusto/signatories/view', ['signatories' => $signatories, 'companySid' => $companyId], true)
            ]
        );
    }

    /**
     * Add signatory
     *
     * @param int $companyId
     */
    public function addSignatory(int $companyId)
    {
        //
        $post = $this->input->post(null, true);
        //
        $signatory = $this->db
            ->select('sid, gusto_uuid')
            ->where('company_sid', $companyId)
            ->where('email', $post['email'])
            ->where('is_deleted', 0)
            ->get('payroll_signatories')
            ->row_array();
        // already exists
        if ($signatory) {
            //
            if ($signatory['gusto_uuid']) {
                return SendResponse(200, ['errors' => ['Signatory already exists.']]);
            }
            // fetch all admins
            $gustoAdmins = $this->gusto_payroll_model->fetchAllSignatories($companyId);
            //
            $this->db
                ->where('sid', $signatory['sid'])
                ->update('payroll_signatories', [
                    'gusto_uuid' => $gustoAdmins[$signatory['email_address']]
                ]);
            //
            return SendResponse(200, ['errors' => ['Signatory already exists.']]);
        }
        // add a new one
        $response = $this->gusto_payroll_model->moveSignatoryToGusto([
            "ssn" => $post['ssn'],
            "first_name" => $post['firstName'],
            "last_name" => $post['lastName'],
            "email" => $post['email'],
            "title" => $post['title'],
            "birthday" => formatDateToDB($post['birthday'], SITE_DATE, DB_DATE),
            "home_address" => [
                "street_1" => $post['street1'],
                "city" => $post['city'],
                "state" => $post['state'],
                "zip" => $post['zip'],
                "street_2" => $post['street2']
            ],
            "middle_initial" => $post['middleInitial'],
            "phone" => $post['phone']
        ], $companyId);

        //
        if (is_array($response)) {
            return SendResponse(200, ['errors' => $response]);
        }
        //
        return SendResponse(
            200,
            [
                'success' => 'You have successfully added a signatory.'
            ]
        );
    }

    /**
     * Update signatory
     *
     * @param int $companyId
     */
    public function updateSignatory(int $companyId)
    {
        //
        $post = $this->input->put(null, true);
        // fetch signatory
        $signatory = $this->db
            ->select('version, ssn, birthday, gusto_uuid')
            ->where('sid', $post['id'])
            ->get('payroll_signatories')
            ->row_array();
        // update
        $response = $this->gusto_payroll_model->updateSignatoryToGusto([
            "version" => $signatory['version'],
            "ssn" => strpos($post['ssn'], '#') !== false ? $signatory['ssn'] : $post['ssn'],
            "first_name" => $post['firstName'],
            "last_name" => $post['lastName'],
            "title" => $post['title'],
            "birthday" => strpos($post['birthday'], '#') !== false ? $signatory['birthday'] : formatDateToDB($post['birthday'], SITE_DATE, DB_DATE),
            "home_address" => [
                "street_1" => $post['street1'],
                "city" => $post['city'],
                "state" => $post['state'],
                "zip" => $post['zip'],
                "street_2" => $post['street2']
            ],
            "middle_initial" => $post['middleInitial'],
            "phone" => $post['phone']
        ], $companyId, $post['id'], $signatory['gusto_uuid']);

        //
        if (is_array($response)) {
            return SendResponse(200, ['errors' => $response]);
        }
        //
        return SendResponse(
            200,
            [
                'success' => 'You have successfully updated signatory.'
            ]
        );
    }

    /**
     * delete signatory
     *
     * @param int $companyId
     * @param int $signatoryId
     */
    public function deleteSignatory(int $companyId, int $signatoryId)
    {
        // add a new one
        $this->gusto_payroll_model->deleteSignatoryFromGusto($companyId, $signatoryId);
        //
        return SendResponse(
            200,
            [
                'success' => 'You have successfully added a signatory.'
            ]
        );
    }


    /**
     * Sync
     */
    public function syncDataDataWithGusto($companyId)
    {
        // get company details
        $this->gusto_payroll_model->syncCompanyDataWithGusto($companyId);
    }


    /**
     * add and Update
     */
    public function onboardEmployeeOnGusto($employeeId)
    {
        // add and Update Employee on Gusto
        $this->gusto_payroll_model->onboardEmployeeOnGusto($employeeId);
    }

    /**
     * Handle employee onboard
     * 
     * Handles employee onboard process from the 
     * UI of super admin and employer panel
     * 
     * @method handleEmployeeProfileForOnboarding
     * 
     * @param string $type
     * profile|job|home_address|federal_tax|state_tax|payment_method|documents
     * @return json
     */
    public function onboardEmployee(string $type)
    {
        // get the filtered post
        $post = $this->input->post(null, true);
        // get gusto employee details
        $gustoEmployeeDetails = $this->db
            ->select('payroll_employee_uuid, version')
            ->where([
                'company_sid' => $post['companyId'],
                'employee_sid' => $post['employeeId']
            ])
            ->get('payroll_employees')
            ->row_array();
        // double check the intrusion
        if (!$gustoEmployeeDetails) {
            // add the error
            $errors['errors'][] = 'Employee not found.';
            // send back response
            return SendResponse(200, $errors);
        }
        // get the company details
        $gustoCompany =
            $this->db
            ->select('
                gusto_company_sid, 
                gusto_company_uid,
                access_token,
                refresh_token
            ')
            ->where([
                'company_sid' => $post['companyId']
            ])
            ->get('payroll_companies')
            ->row_array();
        // double check the intrusion
        if (!$gustoCompany) {
            // add the error
            $errors['errors'][] = 'Company credentials not found.';
            // send back response
            return SendResponse(200, $errors);
        }
        //
        $func = 'handleEmployee' . str_replace(' ', '', ucwords(str_replace('_', ' ', $type))) . 'ForOnboarding';

        $this->gusto_payroll_model->$func(
            $post,
            $gustoEmployeeDetails,
            $gustoCompany,
            false
        );
    }

    /**
     * 
     */
    public function checkAndFinishCompanyOnboard(int $companyId)
    {
        //
        $response = $this->gusto_payroll_model->checkAndFinishCompanyOnboard($companyId, true);
        //
        if (isset($response['steps'])) {
            return sendResponse(200, ['view' => $this->load->view('payroll/onboardSteps', $response, true)]);
        }
        return sendResponse(200, $response);
    }

    /**
     * 
     */
    public function checkAndFinishEmployeeOnboard(int $employeeId)
    {
        //
        $response = $this->gusto_payroll_model->checkAndFinishEmployeeOnboard($employeeId, true);
        //
        if (isset($response['steps'])) {
            return sendResponse(200, ['view' => $this->load->view('payroll/employeeOnboardSteps', $response, true)]);
        }
        return sendResponse(200, $response);
    }
}
