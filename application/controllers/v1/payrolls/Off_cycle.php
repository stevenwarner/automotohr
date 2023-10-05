<?php defined('BASEPATH') || exit('No direct script access allowed');

class Off_cycle extends Public_controller
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
     * wether to create minified files or not
     */
    private $createMinifyFiles;
    /**
     * main entry point to controller
     */
    public function __construct()
    {
        // inherit
        parent::__construct();
        //
        $this->form_validation->set_message('required', '"{field}" is required.');
        $this->form_validation->set_message('valid_date', '"{field}" is invalid.');
        // Call the model
        $this->load->model("v1/Off_cycle_payroll_model", "off_cycle_payroll_model");
        // set the logged in user id
        $this->userId = $this->session->userdata('logged_in')['employer_detail']['sid'] ?? 0;
        // set path to CSS file
        $this->css = 'public/v1/css/payroll/off_cycle/';
        // set path to JS file
        $this->js = 'public/v1/js/payroll/off_cycle/';
        //
        $this->createMinifyFiles = false;
    }

    /**
     * main page
     *
     * @param string $reason
     */
    public function index(string $reason)
    {
        // load the regular payroll model
        $this->load->model("v1/Regular_payroll_model", "regular_payroll_model");
        //
        $data = $this->getData();
        // let's check if there are any payroll blockers
        $payrollBlockers = $this->regular_payroll_model
            ->getRegularPayrollBlocker(
                $data['loggedInPersonCompanyId']
            );
        //
        if ($payrollBlockers['data']) {
            //
            $data['title'] = "Payroll blockers";
            $data['payrollBlockers'] = $payrollBlockers['data'];
            //
            $this->load
                ->view('main/header', $data)
                ->view('v1/payroll/regular/blockers')
                ->view('main/footer');
        }

        // check if off cycle already exists
        $data['payroll'] = $this->off_cycle_payroll_model->alreadyExists($reason, $data['loggedInPersonCompanyId']);
        //
        if ($data['payroll']) {
            // payrollid
            // return redirect("/payrolls/off-cycle/{}/hours_and_earnings");
            die('sadas');
        }
        //
        $this->basic($reason);
    }

    /**
     * Basic details
     * 
     * @param string $reason
     */
    public function basic(string $reason)
    {
        //
        $data = $this->getData();
        //
        $data['title'] = "Basic details";
        // get payroll employees
        $data['payrollEmployees'] = $this->off_cycle_payroll_model
            ->getFilteredPayrollEmployees(
                $data['loggedInPersonCompanyId']
            );
        $data['schedule'] = $this->off_cycle_payroll_model
            ->getCompanySchedule(
                $data['loggedInPersonCompanyId']
            );
        //
        $data['appCSS'] = bundleCSS(
            [
                'v1/app/css/loader'
            ],
            $this->css,
            'basic',
            $this->createMinifyFiles
        );
        //
        $data['appJs'] = bundleJs(
            [
                'js/app_helper',
                'v1/payroll/js/off_cycle/basic'
            ],
            $this->js,
            'basic',
            $this->createMinifyFiles
        );
        //
        $this->load
            ->view('main/header', $data)
            ->view('v1/payroll/off_cycle/basic')
            ->view('main/footer');
    }



    // API calls

    /**
     * save employees
     *
     * @return json
     */
    public function saveEmployees(): array
    {
        // get the session
        $session = checkUserSession(false);
        // check session and generate proper error
        $this->checkSessionStatus($session);
        // check if company is on payroll
        $this->checkForLinkedCompany(true);
        // get sanitized post
        $post = $this->input->post(null, true);
        // set errors array
        $errorsArray = [];
        // validation
        if (!$post) {
            $errorsArray[] = '"Data" is required.';
        }
        if (!$post['employees']) {
            $errorsArray[] = '"Employees" are required.';
        }
        if (!$post['reason']) {
            $errorsArray[] = '"Reason" is required.';
        }
        //
        if ($errorsArray) {
            return SendResponse(400, ['errors' => $errorsArray]);
        }
        // check if payroll already exists
        $doExists = $this->off_cycle_payroll_model->alreadyExists($post['reason'], $session['company_detail']['sid']);
        //
        if ($doExists) {
            return SendResponse(400, ['errors' => ['"' . ($post['reason']) . '" already in progress.']]);
        }
        // 
        $response = $this->off_cycle_payroll_model->addPayrollWithEmployees(
            $session['company_detail']['sid'],
            $post
        );
        //
        return SendResponse(
            $response['errors'] ? 400 : 200,
            $response
        );
    }

    /**
     * callback event for validation to check date format
     *
     * @param string $date
     * @return bool
     */
    public function valid_date(string $date): bool
    {
        return preg_match(
            '/\d{2}\/\d{2}\/\d{4}/',
            $date
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
