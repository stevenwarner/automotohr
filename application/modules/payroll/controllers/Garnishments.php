<?php defined('BASEPATH') || exit('No direct script access allowed');

class Garnishments extends Public_controller
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
        $this->load->model("v1/Garnishments_model", "garnishments_model");
        // set path to CSS file
        $this->css = 'public/v1/css/payroll/garnishments/';
        // set path to JS file
        $this->js = 'public/v1/js/payroll/garnishments/';
    }

    // API Routes
    /**
     * get the regular payroll view - review
     *
     * @param int $employeeId
     * @return JSON
     */
    public function generateView(int $employeeId): array
    {
        // get the session
        $session = checkUserSession(false);
        // check session and generate proper error
        $this->checkSessionStatus($session);
        // check if company is on payroll
        $this->checkForLinkedCompany(true);
        // get the pay stub
        $data['garnishments'] = $this
            ->garnishments_model
            ->getAllGarnishments(
                $employeeId,
                $session['company_detail']['sid']
            );
        // get employee information
        $data['employee'] = $this
            ->garnishments_model
            ->getEmployee(
                $employeeId
            );
        //
        return SendResponse(
            200,
            [
                'view' => $this->load->view('v1/payroll/garnishments/view', $data, true),
                'employee' => $data['employee']
            ]
        );
    }

    /**
     * get the regular payroll view - review
     *
     * @param int $employeeId
     * @return JSON
     */
    public function generateAddView(int $employeeId): array
    {
        // get the session
        $session = checkUserSession(false);
        // check session and generate proper error
        $this->checkSessionStatus($session);
        // check if company is on payroll
        $this->checkForLinkedCompany(true);
        //
        return SendResponse(
            200,
            [
                'view' => $this->load->view('v1/payroll/garnishments/add', [], true)
            ]
        );
    }

    /**
     * load edit view
     *
     * @param int $employeeId
     * @param int $garnishmentId
     * @return JSON
     */
    public function generateEditView(int $employeeId, int $garnishmentId): array
    {
        // get the session
        $session = checkUserSession(false);
        // check session and generate proper error
        $this->checkSessionStatus($session);
        // check if company is on payroll
        $this->checkForLinkedCompany(true);
        //
        $garnishment = $this->garnishments_model
            ->getGarnishmentById(
                $employeeId,
                $garnishmentId
            );
        //
        $beneficiary = $this->garnishments_model
            ->getBeneficiaryInfoById(
                $garnishmentId
            );
        //
        return SendResponse(
            200,
            [
                'view' => $this->load->view('v1/payroll/garnishments/edit', [
                    'garnishment' => $garnishment,
                    'beneficiary' => $beneficiary
                ], true)
            ]
        );
    }

    /**
     * save garnishment
     *
     * @param int $employeeId
     * @return JSON
     */
    public function save(int $employeeId): array
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
        //
        if (!$post) {
            $errorArray[] =  '"Data" is missing.';
        }
        //
        if (!$post['amount']) {
            $errorArray[] =  '"Amount" is missing.';
        }
        //
        if (!$post['description']) {
            $errorArray[] =  '"Description" is missing.';
        }
        //
        if (!$post['court_ordered']) {
            $errorArray[] =  '"Court ordered" is missing.';
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
        $garnishment_data = [];
        $garnishment_data['active'] = $post['active'];
        $garnishment_data['amount'] = $post['amount'];
        $garnishment_data['description'] = $post['description'];
        $garnishment_data['court_ordered'] = $post['court_ordered'];
        $garnishment_data['times'] = $post['times'];
        $garnishment_data['recurring'] = $post['recurring'];
        $garnishment_data['annual_maximum'] = $post['annual_maximum'];
        $garnishment_data['pay_period_maximum'] = $post['pay_period_maximum'];
        $garnishment_data['deduct_as_percentage'] = $post['deduct_as_percentage'];
        //
        $gustoResponse = $this
            ->garnishments_model
            ->saveGarnishment(
                $session['company_detail']['sid'],
                $session['employer_detail']['sid'],
                $employeeId,
                $garnishment_data
            );
        //
        if ($gustoResponse['success']) {
            //
            $garnishmentId = $gustoResponse['Id'];
            //
            $beneficiary_data = [];
            $beneficiary_data['name'] = $post['beneficiaryName'];
            $beneficiary_data['address'] = $post['beneficiaryAddress'];
            $beneficiary_data['phone'] = $post['beneficiaryPhone'];
            $beneficiary_data['payment_type'] = $post['beneficiaryPaymentType'];
            //
            if ($post['beneficiaryPaymentType'] == 'bank') {
                $beneficiary_data['bank_detail'] = serialize([
                    'account_title' => $post['bankAccountTitle'],
                    'banking_type' => $post['bankAccountType'],
                    'financial_institution' => $post['bankName'],
                    'bank_routing_number' => $post['bankRoutingNumber'],
                    'bank_account_number' => $post['bankAccountNumber']
                ]);
            } else {
                $beneficiary_data['bank_detail'] = null;
            }
            //
            $this->garnishments_model->updateGarnishmentBeneficiary(
                $garnishmentId,
                $beneficiary_data
            );
        }
        
        //
        return SendResponse(
            $gustoResponse['errors'] ? 400 : 200,
            $gustoResponse
        );
    }

    /**
     * update garnishment
     *
     * @param int $employeeId
     * @param int $garnishmentId
     * @return JSON
     */
    public function updateGarnishment(int $employeeId, int $garnishmentId): array
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
        //
        //
        if (!$post) {
            $errorArray[] =  '"Data" is missing.';
        }
        //
        if (!$post['amount']) {
            $errorArray[] =  '"Amount" is missing.';
        }
        //
        if (!$post['description']) {
            $errorArray[] =  '"Description" is missing.';
        }
        //
        if (!$post['court_ordered']) {
            $errorArray[] =  '"Court ordered" is missing.';
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
        $garnishment_data = [];
        $garnishment_data['active'] = $post['active'];
        $garnishment_data['amount'] = $post['amount'];
        $garnishment_data['description'] = $post['description'];
        $garnishment_data['court_ordered'] = $post['court_ordered'];
        $garnishment_data['times'] = $post['times'];
        $garnishment_data['recurring'] = $post['recurring'];
        $garnishment_data['annual_maximum'] = $post['annual_maximum'];
        $garnishment_data['pay_period_maximum'] = $post['pay_period_maximum'];
        $garnishment_data['deduct_as_percentage'] = $post['deduct_as_percentage'];
        //
        $gustoResponse = $this
            ->garnishments_model
            ->updateGarnishment(
                $session['company_detail']['sid'],
                $session['employer_detail']['sid'],
                $employeeId,
                $garnishmentId,
                $garnishment_data
            );
        //
        $beneficiary_data = [];
        $beneficiary_data['name'] = $post['beneficiaryName'];
        $beneficiary_data['address'] = $post['beneficiaryAddress'];
        $beneficiary_data['phone'] = $post['beneficiaryPhone'];
        $beneficiary_data['payment_type'] = $post['beneficiaryPaymentType'];
        //
        if ($post['beneficiaryPaymentType'] == 'bank') {
            $beneficiary_data['bank_detail'] = serialize([
                'account_title' => $post['bankAccountTitle'],
                'banking_type' => $post['bankAccountType'],
                'financial_institution' => $post['bankName'],
                'bank_routing_number' => $post['bankRoutingNumber'],
                'bank_account_number' => $post['bankAccountNumber']
            ]);
        } else {
            $beneficiary_data['bank_detail'] = null;
        }
        //
        $this->garnishments_model->updateGarnishmentBeneficiary(
            $garnishmentId,
            $beneficiary_data
        );    
        //
        return SendResponse(
            $gustoResponse['errors'] ? 400 : 200,
            $gustoResponse
        );
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
        if (!isCompanyLinkedWithGusto($this->session->userdata('logged_in')['company_detail']['sid'])) {
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
