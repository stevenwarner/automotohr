<?php defined('BASEPATH') || exit('No direct script access allowed');

class Pay_stub extends Public_controller
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
        // Call the model
        $this->load->model("v1/Pay_stubs_model", "pay_stubs_model");
        // set path to CSS file
        $this->css = 'public/v1/css/payroll/pay_stub/';
        // set path to JS file
        $this->js = 'public/v1/js/payroll/pay_stub/';
        //
        $this->createMinifyFiles = true;
    }

    /**
     * Main page
     */
    public function index()
    {
        //
        $data = $this->getData();
        //
        $data['title'] = "Pay stubs";
        // css
        $data['appCSS'] = bundleCSS(
            [
                'v1/plugins/ms_modal/main',
                'v1/app/css/loader'
            ],
            $this->css,
            'main',
            $this->createMinifyFiles
        );
        //
        $data['appJs'] = bundleJs(
            [
                'js/app_helper',
                'v1/plugins/ms_modal/main',
                'v1/payroll/js/pay_stubs/main'
            ],
            $this->js,
            'main',
            $this->createMinifyFiles
        );
        // get my pay subs
        $data['payStubs'] = $this
            ->pay_stubs_model
            ->getPayStubs(
                $data['loggedInPersonId'],
                $data['loggedInPersonCompanyId']
            );

        //
        $this->load
            ->view('main/header', $data)
            ->view('v1/payroll/pay_stubs/manage')
            ->view('main/footer');
    }

    /**
     * get the regular payroll view - review
     *
     * @param int $payStubId
     * @return JSON
     */
    public function download(int $payStubId)
    {
        // get the session
        $data = $this->getData();
        //
        $payStub = $this->pay_stubs_model
            ->getSinglePayStub(
                $payStubId,
                $data['loggedInPersonCompanyId']
            );
        //
        $result = getAWSSecureFile(
            $payStub['paystub_json']['s3_file_name']
        );
        //
        $fileName = 'pay_stub_'.(stringToSlug($payStub['start_date'], '_')).'_'.(stringToSlug($payStub['end_date'], '_')).'.pdf';
        //
        $this->load->helper('download');
        //
        force_download(
            $fileName,
            $result['Body']
        );
    }


    /**
     * get the regular payroll view - review
     *
     * @param int $payStubId
     * @return JSON
     */
    public function generateView(int $payStubId): array
    {
        // get the session
        $session = checkUserSession(false);
        // check session and generate proper error
        $this->checkSessionStatus($session);
        // check if company is on payroll
        $this->checkForLinkedCompany(true);
        // get the pay stub
        $data['payStub'] = $this->pay_stubs_model
            ->getSinglePayStub(
                $payStubId,
                $session['company_detail']['sid']
            );
        //
        return SendResponse(
            200,
            ['view' => $this->load->view('v1/payroll/pay_stubs/view', $data, true)]
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
