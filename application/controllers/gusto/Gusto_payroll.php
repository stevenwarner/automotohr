<?php defined('BASEPATH') || exit('No direct script access allowed');

class Gusto_payroll extends CI_Controller
{
    /**
     * holds the default AutomotoHR admin
     */
    private $ahrAdmin = [];

    /**
     * holds the date and time
     */
    private $datetime;

    /**
     * holds the logged in person id
     */
    private $userId;

    /**
     * Main entry point to controller
     */
    public function __construct()
    {
        parent::__construct();
        // Call the model
        $this->load->model("gusto/Gusto_payroll_model", "gusto_payroll_model");
        $this->datetime = date('Y-m-d H:i:s', strtotime('now'));
        // set the AutomotoHR admin
        $this->ahrAdmin = [
            'first_name' => 'Mubashar',
            'last_name' => 'Ahmed',
            'email_address' => 'mubashar@automotohr.com',
            'phone_number' => '3331234569',
        ];
        // set the logged in user id
        $this->userId = $this->session->userdata('logged_in')['employer_detail']['sid'] ?? 0;
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

    /**
     *
     */
    public function getActiveCompanyEmployeesForPayroll(int $companyId, string $location)
    {
        // Fetch employees
        $employees = $this->gusto_payroll_model->getCompanyEmployees($companyId, [
            "users.sid",
            "users.first_name",
            "users.last_name",
            "users.access_level",
            "users.access_level_plus",
            "users.is_executive_admin",
            "users.job_title",
            "users.ssn",
            "users.dob",
            "users.email",
            "users.middle_name",
            "users.pay_plan_flag",
            "users.on_payroll",
            'users.PhoneNumber',
            'payroll_employees.payroll_employee_uuid',
        ], [
            'users.active' => 1,
            'users.is_executive_admin' => 0,
            'users.terminated_status' => 0
        ]);
        //
        if ($employees) {
            //
            $tmp = [];
            //
            foreach ($employees as $employee) {
                //
                if ($employee['payroll_employee_uuid']) {
                    continue;
                }
                //
                $missing_fields = [];
                //
                if (!$employee['first_name']) {
                    $missing_fields[] = 'First Name';
                }
                //
                if (!$employee['last_name']) {
                    $missing_fields[] = 'Last Name';
                }
                //
                $employee['ssn'] = preg_replace('/[^0-9]/', '', $employee['ssn']);
                //
                if (!preg_match('/[0-9]{9}/', $employee['ssn'])) {
                    $missing_fields[] = 'Social Security Number';
                }
                //
                if (!$employee['dob']) {
                    $missing_fields[] = 'Date Of Birth';
                }
                //
                if (!$employee['email']) {
                    $missing_fields[] = 'Email';
                }
                //
                $tmp[] = [
                    'sid' => $employee['sid'],
                    'full_name_with_role' => remakeEmployeeName($employee),
                    'first_name' => $employee['first_name'],
                    'last_name' => $employee['last_name'],
                    'email' => $employee['email'],
                    'phone_number' => $employee['PhoneNumber'],
                    'missing_fields' => $missing_fields
                ];
            }
            //
            $employees = $tmp;
        }
        //
        $data['employees'] = $employees;
        $data['location'] = $location;
        //
        header("content-type: text/html");
        echo $this->load->view('2022/gusto/pages/employees', $data, true);
        exit(0);
    }

    /**
     * Start the initial employee onboard
     *
     * @param number $companyId
     * @return
     */
    public function AddEmployeeOnGusto($companyId)
    {
        //
        $employeeId = $this->input->post('employee_id', true);
        //
        $response = $this->gusto_payroll_model->onboardEmployeeOnGusto($employeeId);
        //
        if (is_array($response)) {
            return sendResponse(200, ['status' => false, 'errors' => $response]);
        }
        //
        return sendResponse(200, ['status' => true]);
    }

    //
    public function managePayment(int $companyId)
    {

        //
        $payroll_settings = $this->gusto_payroll_model->GetPayrollColumn(
            'payroll_settings',
            [
                'company_sid' => $companyId
            ],
            'sid, fast_payment_limit, payment_speed',
            false
        );


        if (!$payroll_settings) {
            //
            $this->GetAndSetPaymentConfig($companyId);
            //
            $payroll_settings = $this->gusto_payroll_model->GetPayrollColumn(
                'payroll_settings',
                [
                    'company_sid' => $companyId
                ],
                'sid, fast_payment_limit, payment_speed',
                false
            );
        }

        //
        return SendResponse(
            200,
            [
                'view' => $this->load->view('gusto/managepayment', ['payroll_settings' => $payroll_settings, 'companySid' => $companyId], true)
            ]
        );
    }

    //
    private function GetAndSetPaymentConfig($companyId)
    {
        // Get company
        $company = $this->gusto_payroll_model->GetCompany($companyId, [
            'access_token',
            'refresh_token',
            'gusto_company_uid'
        ]);
        //
        $response = GetPaymentConfig($company);
        //
        if (isset($response['name'])) {
            return;
        }
        //
        $ai = [];
        $ai['company_sid'] = $companyId;
        $ai['last_updated_by'] = $this->session->userdata('logged_in')['employer_detail']['sid'];
        $ai['created_at'] = $ai['updated_at'] = date('Y-m-d H:i:s', strtotime('now'));
        $ai['fast_payment_limit'] = $response['fast_payment_limit'];
        $ai['payment_speed'] = $response['payment_speed'];
        $ai['partner_uid'] = $response['partner_uuid'];
        //
        $this->gusto_payroll_model->InsertPayroll(
            'payroll_settings',
            $ai
        );
    }


    // Create Partner Company

    /**
     * Get the page html
     */
    function GetPage($page, $companyId = 0)
    {
        //
        $data = [];
        //
        $page = stringToSlug(strtolower($page));
        //
        if ($page === 'onboard') {
            //
            $data['hasPrimaryAdmin'] = $this->gusto_payroll_model->HasPrimaryAdmin($companyId);
        }
        //
        if ($page === 'admin-view') {
            //
            $data['primaryAdmin'] = $this->gusto_payroll_model->GetPrimaryAdmin($companyId);
        }
        //
        header("content-type: text/html");
        echo $this->load->view('2022/gusto/pages/' . $page, $data, true);
        exit(0);
    }

    /**
     * Start the initial company onboard
     *
     * @param int $companyId
     * @return json
     */
    public function createPartnerCompanyOnGusto($companyId)
    {
        // check if company is already onboard
        $cd = $this->gusto_payroll_model->GetCompanyOnboardDetails($companyId);
        //
        if (!$cd['gusto_company_uid']) {
            $this->createPartnerCompany($companyId);
        }
        // add the AutomotoHR admin
        $this->gusto_payroll_model->checkAndSetStoreAdminForGusto($companyId, $this->ahrAdmin);
        // sync AutomotoHR to Gusto
        $this->gusto_payroll_model->pushCompanyDataToGusto($companyId);
        // make a quick sync
        $this->gusto_payroll_model->syncCompanyDataWithGusto($companyId);
        //
        return SendResponse(200, ['status' => true]);
    }

    /**
     * Create partner company
     *
     * @param number $companyId
     * @return
     */
    private function createPartnerCompany($companyId)
    {
        // Get company details
        $companyDetails = $this->gusto_payroll_model->GetCompanyDetails(
            $companyId,
            'CompanyName, ssn as ein, on_payroll'
        );
        // Check if ENI is already used
        if ($this->db->where('gusto_company_uid', $companyDetails['ein'])->count_all_results('payroll_companies')) {
            // return if EIN already in used
            return SendResponse(200, ['errors' => ['EIN already in used by an another company.']]);
        }

        // Make request array
        $request = [
            'user' => [],
            'company' => []
        ];
        //
        $request['user']['first_name'] = $this->ahrAdmin['first_name'];
        $request['user']['last_name'] = $this->ahrAdmin['last_name'];
        $request['user']['email'] = $this->ahrAdmin['email_address'];
        $request['user']['phone'] = $this->ahrAdmin['phone_number'];
        $request['company']['name'] = $companyDetails['CompanyName'];
        $request['company']['ein'] = $companyDetails['ein'];
        //
        $response = createPartnerCompany($request);
        // set errors
        $errors = hasGustoErrors($response);
        //
        if ($errors) {
            // Error took place
            return SendResponse(200, $errors);
        }
        //
        $insertArray = [];
        $insertArray['company_sid'] = $companyId;
        $insertArray['gusto_company_sid'] = $request['company']['ein'];
        $insertArray['gusto_company_uid'] = $response['company_uuid'];
        $insertArray['refresh_token'] = $response['refresh_token'];
        $insertArray['access_token'] = $response['access_token'];
        $insertArray['created_at'] = $this->datetime;
        $insertArray['updated_at'] = $this->datetime;
        //
        $this->gusto_payroll_model->InsertPayroll('payroll_companies', $insertArray);
        $this->gusto_payroll_model->UpdateCompany($companyId, ['on_payroll' => 1]);
        //
        return $response['company_uuid'];
    }


    /**
     * send test deposits
     *
     * @param int $companyId
     * @return json
     */
    public function sendTestDeposits(int $companyId)
    {
        // get company details
        $companyDetails = $this
            ->gusto_payroll_model
            ->getCompanyDetailsForGusto($companyId);
        // get bank account
        $bankDetails = $this
            ->gusto_payroll_model
            ->getCompanyBankAccount($companyId);
        //
        if (!$bankDetails) {
            return SendResponse(200, ['errors' => ['Please, add company\'s bank account first or sync the bank account.']]);
        }
        // get the deposits
        $response = getTestDeposits($bankDetails['payroll_uuid'], $companyDetails);
        //
        $errors = hasGustoErrors($response);
        //
        if ($errors) {
            return SendResponse(200, $errors);
        }
        return SendResponse(200, $response);
    }
    
    /**
     * send test deposits
     *
     * @param int $companyId
     * @return json
     */
    public function approveCompany(int $companyId)
    {
        // get company details
        $companyDetails = $this
            ->gusto_payroll_model
            ->getCompanyDetailsForGusto($companyId);
       
        // get the deposits
        $response = approveCompanyOnGusto($companyDetails);
        //
        $errors = hasGustoErrors($response);
        //
        if ($errors) {
            return SendResponse(200, $errors);
        }
        return SendResponse(200, ['success' => true]);
    }
}
