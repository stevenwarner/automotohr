<?php defined('BASEPATH') || exit('No direct script access allowed');

class Company_benefits extends Public_controller
{
    /**
     * for js
     */
    private $js;
    /**
     * for css
     */
    private $css;
    /**
     * main entry point to controller
     */
    public function __construct()
    {
        // inherit
        parent::__construct();
        // Call the model
        $this->load->model("v1/Company_benefits_model", "company_benefits_model");
        // set path to CSS file
        $this->css = 'public/v1/css/company_benefits/';
        // set path to JS file
        $this->js = 'public/v1/js/company_benefits/';
        // set the logged in user id
        $this->userId = $this->session->userdata('logged_in')['employer_detail']['sid'] ?? 0;
    }

    /**
     * main benefits listing page
     */
    public function index()
    {
        //
        $data = $this->getData();
        // set the title
        $data['title'] = "Company Benefits :: " . STORE_NAME;
        //
        $data['appCSS'] = bundleCSS([
            'v1/plugins/ms_modal/main',
            'v1/app/css/loader'
        ], $this->css, 'benefits');
        //
        $data['appJs'] = bundleJs([
            'js/app_helper',
            'v1/plugins/ms_modal/main',
            'v1/company_benefits/js/main',
        ], $this->css, 'benefits');

        //
        $this->load
            ->view('main/header', $data)
            ->view('v1/benefits/manage')
            ->view('main/footer');
    }


    // API routes

    /**
     * generate benefit add view
     */
    public function generateBenefitsView()
    {
        // get the session
        $session = checkUserSession(false);
        // check session and generate proper error
        $this->checkSessionStatus($session);
        // check if company is on payroll
        $this->checkForLinkedCompany(true);
        // get store benefits
        $benefits = $this->company_benefits_model
            ->getBenefits(
                $session['company_detail']['sid']
            );
        //
        return SendResponse(
            200,
            [
                'view' => $this->load->view(
                    'v1/benefits/partials/benefits',
                    ['benefits' => $benefits],
                    true
                )
            ]
        );
    }

    /**
     * generate benefit add view
     */
    public function generateAddView()
    {
        // get the session
        $session = checkUserSession(false);
        // check session and generate proper error
        $this->checkSessionStatus($session);
        // check if company is on payroll
        $this->checkForLinkedCompany(true);
        // get store benefits
        $storeBenefits = $this->company_benefits_model
            ->getStoreBenefits(true);
        //
        return SendResponse(
            200,
            [
                'view' => $this->load->view(
                    'v1/benefits/partials/add_benefit',
                    ['storeBenefits' => $storeBenefits],
                    true
                )
            ]
        );
    }

    /**
     * create benefit
     */
    public function createBenefit()
    {
        // get the session
        $session = checkUserSession(false);
        // check session and generate proper error
        $this->checkSessionStatus($session);
        // check if company is on payroll
        $this->checkForLinkedCompany(true);
        //
        $post = $this->input->post(null, true);
        //
        $errorArray = [];
        // validation
        if (!$post) {
            $errorArray[] = '"Data" is required.';
        }
        if (!$post['description']) {
            $errorArray[] = '"Name" is required.';
        }
        //
        if ($errorArray) {
            return SendResponse(
                400,
                [
                    'errors' => $errorArray
                ]
            );
        }
        // get store benefits
        $gustoResponse = $this->company_benefits_model
            ->createBenefits(
                $post,
                $session['company_detail']['sid']
            );
        //
        return SendResponse(
            $gustoResponse['errors'] ? 400 : 200,
            $gustoResponse
        );
    }

    /**
     * generate benefit edit view
     *
     * @param int $benefitId
     */
    public function generateEditView(int $benefitId)
    {
        // get the session
        $session = checkUserSession(false);
        // check session and generate proper error
        $this->checkSessionStatus($session);
        // check if company is on payroll
        $this->checkForLinkedCompany(true);
        // get store benefits
        $storeBenefits = $this->company_benefits_model
            ->getStoreBenefits(true);
        // get benefit by id
        $benefit = $this->company_benefits_model
            ->getCompanyBenefitById($benefitId);
        //
        return SendResponse(
            200,
            [
                'view' => $this->load->view(
                    'v1/benefits/partials/edit_benefit',
                    [
                        'storeBenefits' => $storeBenefits,
                        'benefit' => $benefit,
                    ],
                    true
                )
            ]
        );
    }

    /**
     * create benefit
     *
     * @param int $benefitId
     */
    public function updateBenefit(int $benefitId)
    {
        // get the session
        $session = checkUserSession(false);
        // check session and generate proper error
        $this->checkSessionStatus($session);
        // check if company is on payroll
        $this->checkForLinkedCompany(true);
        //
        $post = $this->input->post(null, true);
        //
        $errorArray = [];
        // validation
        if (!$post) {
            $errorArray[] = '"Data" is required.';
        }
        if (!$post['description']) {
            $errorArray[] = '"Name" is required.';
        }
        //
        if ($errorArray) {
            return SendResponse(
                400,
                [
                    'errors' => $errorArray
                ]
            );
        }
        // get store benefits
        $gustoResponse = $this->company_benefits_model
            ->updateBenefits(
                $post,
                $benefitId
            );
        //
        return SendResponse(
            $gustoResponse['errors'] ? 400 : 200,
            $gustoResponse
        );
    }

    /**
     * delete benefit
     *
     * @param int $benefitId
     */
    public function deleteBenefit(int $benefitId)
    {
        // get the session
        $session = checkUserSession(false);
        // check session and generate proper error
        $this->checkSessionStatus($session);
        // check if company is on payroll
        $this->checkForLinkedCompany(true);
        // get store benefits
        $gustoResponse = $this->company_benefits_model
            ->deleteBenefits(
                $benefitId
            );
        //
        return SendResponse(
            $gustoResponse['errors'] ? 400 : 200,
            $gustoResponse
        );
    }

    /**
     * generate benefit employees edit view
     *
     * @param int $benefitId
     */
    public function generateBenefitEmployeesView(int $benefitId)
    {
        // get the session
        $session = checkUserSession(false);
        // check session and generate proper error
        $this->checkSessionStatus($session);
        // check if company is on payroll
        $this->checkForLinkedCompany(true);
        // get payroll employees
        $employees = $this->company_benefits_model
            ->getFilteredPayrollEmployees(
                $session['company_detail']['sid'],
                $benefitId
            );
        //
        return SendResponse(
            200,
            [
                'view' => $this->load->view(
                    'v1/benefits/partials/edit_benefit_employees',
                    [
                        'employees' => $employees,
                        'benefitId' => $benefitId
                    ],
                    true
                )
            ]
        );
    }

    /**
     * updates benefits employees
     *
     * @param int $benefitId
     */
    public function updateBenefitEmployees(int $benefitId)
    {
        // get the session
        $session = checkUserSession(false);
        // check session and generate proper error
        $this->checkSessionStatus($session);
        // check if company is on payroll
        $this->checkForLinkedCompany(true);
        // get the sanitized post
        $post = $this->input->post(null, true);
        //
        $errorArray = [];
        // validation
        if (!$post) {
            $errorArray[] = '"Data" is required.';
        }
        if (!$post['employees']) {
            $errorArray[] = '"Employees" is required.';
        }
        if (!$post['employee_deductions'] && !$post['company_contribution']) {
            $errorArray[] = '"Employee deductions or company contribution" is required.';
        }
        //
        if ($errorArray) {
            return SendResponse(
                400,
                [
                    'errors' => $errorArray
                ]
            );
        }
        //
        $gustoResponse = $this
            ->company_benefits_model
            ->addEmployeesToBenefit(
                $benefitId,
                $post
            );
        //
        return SendResponse(
            $gustoResponse['errors'] ? 400 : 200,
            $gustoResponse
        );
    }

    /**
     * generate benefit employees listing view
     *
     * @param int $benefitId
     */
    public function generateBenefitEmployeesListingView(int $benefitId)
    {
        // get the session
        $session = checkUserSession(false);
        // check session and generate proper error
        $this->checkSessionStatus($session);
        // check if company is on payroll
        $this->checkForLinkedCompany(true);
        // get store benefits
        $benefitEmployees = $this->company_benefits_model
            ->getBenefitEmployeeById(
                $benefitId
            );
        //
        return SendResponse(
            200,
            [
                'view' => $this->load->view(
                    'v1/benefits/partials/benefit_employees',
                    [
                        'benefitEmployees' => $benefitEmployees,
                        'benefitId' => $benefitId
                    ],
                    true
                )
            ]
        );
    }

    /**
     * generate benefit employees edit view
     *
     * @param int $employeeBenefitId
     */
    public function generateBenefitEmployeesEditView(int $employeeBenefitId)
    {
        // get the session
        $session = checkUserSession(false);
        // check session and generate proper error
        $this->checkSessionStatus($session);
        // check if company is on payroll
        $this->checkForLinkedCompany(true);
        // get store benefits
        $employeeBenefit = $this->company_benefits_model
            ->getEmployeeBenefitById(
                $employeeBenefitId
            );
        //
        return SendResponse(
            200,
            [
                'view' => $this->load->view(
                    'v1/benefits/partials/edit_benefit_employee',
                    [
                        'employeeBenefit' => $employeeBenefit,
                        'employeeBenefitId' => $employeeBenefitId
                    ],
                    true
                )
            ]
        );
    }

    /**
     * update employee benefit
     *
     * @param int $employeeBenefitId
     */
    public function updateBenefitEmployee(int $employeeBenefitId)
    {
        // get the session
        $session = checkUserSession(false);
        // check session and generate proper error
        $this->checkSessionStatus($session);
        // check if company is on payroll
        $this->checkForLinkedCompany(true);
        // get the sanitized post
        $post = $this->input->post(null, true);
        //
        $errorArray = [];
        // validation
        if (!$post) {
            $errorArray[] = '"Data" is required.';
        }
        if (!$post['employee_deductions'] && !$post['company_contribution']) {
            $errorArray[] = '"Employee deductions or company contribution" is required.';
        }
        //
        if ($errorArray) {
            return SendResponse(
                400,
                [
                    'errors' => $errorArray
                ]
            );
        }
        //
        $gustoResponse = $this
            ->company_benefits_model
            ->updateEmployeeBenefit(
                $session['company_detail']['sid'],
                $employeeBenefitId,
                $post
            );
        //
        return SendResponse(
            $gustoResponse['errors'] ? 400 : 200,
            $gustoResponse
        );
    }

    /**
     * delete employee benefit
     *
     * @param int $employeeBenefitId
     */
    public function deleteBenefitEmployee(int $employeeBenefitId)
    {
        // get the session
        $session = checkUserSession(false);
        // check session and generate proper error
        $this->checkSessionStatus($session);
        // check if company is on payroll
        $this->checkForLinkedCompany(true);
        //
        $gustoResponse = $this
            ->company_benefits_model
            ->deleteEmployeeBenefit(
                $session['company_detail']['sid'],
                $employeeBenefitId
            );
        //
        return SendResponse(
            $gustoResponse['errors'] ? 400 : 200,
            $gustoResponse
        );
    }

    /**
     * flush payroll data
     */
    public function flushPayroll()
    {
        // get the session
        $session = checkUserSession();

        $companyId = $session['company_detail']['sid'];
        // get company payroll ids
        $employeeIds = $this->db->select('employee_sid')
            ->where('company_sid', $companyId)
            ->get('gusto_companies_employees')
            ->result_array();
        // get company payroll ids
        $contractorIds = $this->db->select('sid')
            ->where('company_sid', $companyId)
            ->get('gusto_contractors')
            ->result_array();
        // get company payroll ids
        $externalPayrollId = $this->db->select('sid')
            ->where('company_sid', $companyId)
            ->get('payrolls.external_payrolls')
            ->result_array();
        // get company payroll ids
        $regularPayrollId = $this->db->select('sid')
            ->where('company_sid', $companyId)
            ->get('payrolls.regular_payrolls')
            ->result_array();
        //
        if ($employeeIds) {
            //
            $employeeIds = array_column($employeeIds, 'employee_sid');
            //
            $this->db->where_in('employee_sid', $employeeIds)->delete('gusto_companies_employees_work_addresses');
            $this->db->where_in('employee_sid', $employeeIds)->delete('gusto_employees_federal_tax');
            $this->db->where_in('employee_sid', $employeeIds)->delete('gusto_employees_jobs');
            $this->db->where_in('employee_sid', $employeeIds)->delete('gusto_employees_payment_method');
            $this->db->where_in('employee_sid', $employeeIds)->delete('gusto_employees_state_tax');
            $this->db->where_in('employee_sid', $employeeIds)->delete('payrolls.employee_benefits');
            $this->db->where_in('employee_sid', $employeeIds)->delete('payrolls.employee_garnishments');
            $this->db->where_in('employee_sid', $employeeIds)->update('bank_account_details', ['gusto_uuid' => null]);
            $this->db->where_in('employee_sid', $employeeIds)->update('users', ['on_payroll' => 0]);
        }
        //
        if ($contractorIds) {
            //
            $contractorIds = array_column($contractorIds, 'sid');
            //
            $this->db->where_in('contractor_sid', $contractorIds)->delete('gusto_contractors_bank_accounts');
            $this->db->where_in('contractor_sid', $contractorIds)->delete('gusto_contractors_documents');
        }
        //
        if ($externalPayrollId) {
            //
            $externalPayrollId = array_column($externalPayrollId, 'sid');
            //
            $this->db->where_in('external_payrolls_sid', $externalPayrollId)->delete('payrolls.external_payrolls_employees');
            $this->db->where_in('external_payrolls_sid', $externalPayrollId)->delete('payrolls.external_payrolls_logs');
            $this->db->where_in('external_payrolls_sid', $externalPayrollId)->delete('payrolls.external_payrolls_tax_suggestions');
        }
        //
        if ($regularPayrollId) {
            //
            $regularPayrollId = array_column($regularPayrollId, 'sid');
            //
            $this->db->where_in('regular_payroll_sid', $regularPayrollId)->delete('payrolls.regular_payrolls_employees');
        }
        //
        $this->db->where('company_sid', $companyId)->delete('gusto_companies');
        $this->db->where('company_sid', $companyId)->delete('companies_bank_accounts');
        $this->db->where('company_sid', $companyId)->delete('gusto_companies_admin');
        $this->db->where('company_sid', $companyId)->delete('gusto_companies_earning_types');
        $this->db->where('company_sid', $companyId)->delete('gusto_companies_employees');
        $this->db->where('company_sid', $companyId)->delete('gusto_companies_locations');
        $this->db->where('company_sid', $companyId)->delete('gusto_companies_signatories');
        $this->db->where('company_sid', $companyId)->delete('gusto_company_temporary');
        $this->db->where('company_sid', $companyId)->delete('gusto_contractors');
        $this->db->where('company_sid', $companyId)->delete('gusto_employees_forms');
        $this->db->where('company_sid', $companyId)->delete('payrolls.company_benefits');
        $this->db->where('company_sid', $companyId)->delete('payrolls.external_payrolls');
        $this->db->where('company_sid', $companyId)->delete('payrolls.external_payrolls_tax_liabilities');
        $this->db->where('company_sid', $companyId)->delete('payrolls.payroll_blockers');
        $this->db->where('company_sid', $companyId)->delete('payrolls.regular_payrolls');
        $this->db->where('company_sid', $companyId)->update('users', ['on_payroll' => 0]);
        //
        return redirect('/dashboard');
    }


    /**
     * get common data
     */
    private function getData()
    {
        //
        $data = [];
        // check and set user session
        $data['session'] = checkUserSession();
        //
        $data['isLoggedInView'] = 1;
        // set
        $data['loggedInPerson'] = $data['session']['employer_detail'];
        $data['loggedInPersonCompany'] = $data['session']['company_detail'];
        $data['loggedInPersonCompanyId'] = $data['session']['company_detail']['sid'];
        $data['loggedInPersonId'] = $data['session']['employer_detail']['sid'];
        // get the security details
        $data['security_details'] = db_get_access_level_details(
            $data['session']['employer_detail']['sid'],
            null,
            $data['session']
        );
        //
        return $data;
    }

    /**
     * generate error based on session
     *
     * @param mixed $session
     * @return json
     */
    private function checkSessionStatus($session)
    {
        if (!$session) {
            return SendResponse(
                401,
                [
                    'errors' => ['Access denied. Please login to access this route.']
                ]
            );
        }
    }

    /**
     * check if company is synced with Gusto
     */
    private function checkForLinkedCompany($isAJAX = false)
    {
        // check if module is active
        if (!isCompanyOnBoard($this->session->userdata('logged_in')['company_detail']['sid'])) {
            //
            if ($isAJAX) {
                return SendResponse(
                    400,
                    [
                        'errors' => ['Company is not set-up for payroll.']
                    ]
                );
            }
            // set message
            $this->session->set_flashdata('message', 'Access denied!');
            // redirect
            return redirect('dashboard');
        }
    }
}
