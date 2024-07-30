<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Payroll base controller
 */
class Payroll_base_controller extends CI_Controller
{
    /**
     * for js
     */
    protected $js;

    /**
     * for css
     */
    protected $css;

    /**
     * wether to create minified files or not
     */
    protected $disableMinifiedFilesCreation;

    /**
     * set the company id
     * @var int
     */
    protected $companyId;

    /**
     * set the access type
     * @var bool
     */
    protected $isPublic;

    /**
     * set the header and footer file
     * @var array
     */
    protected $pages;

    /**
     * set the data
     * @var array
     */
    protected $data;

    /**
     * main entry point to controller
     */
    public function __construct(bool $isPublic = false)
    {
        // inherit with parent
        parent::__construct();
        // set the access type
        $this->setPublicAccess($isPublic);
        // verify the session
        $this->verifyLogin();
        // add the form validators
        $this->setFormValidators();
        // load the main payroll model
        $this
            ->load
            ->model(
                "v1/Payroll/Payrolls_model",
                "payrolls_model"
            );
        $this->checkCompanySync();
        //
        $this->setOptions();
    }

    /**
     * check if company is synced with Gusto
     * redirect the request to dashboard
     *
     */
    protected function checkForLinkedCompany()
    {
        // check if module is active
        if (
            !$this
                ->payrolls_model
                ->checkIfCompanyIsLinked(
                    $this->companyId
                )
        ) {
            //
            if ($this->input->is_ajax_request()) {
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

    /**
     * load the pages
     *
     * @param string $page
     */
    protected function loadView(
        string $page
    ) {
        $this->load->view(
            $this->pages["main"]["header"],
            $this->data
        );
        $this->load->view($page);
        $this->load->view($this->pages["main"]["footer"]);
    }

    /**
     * load the pages
     *
     * @param string $page
     */
    protected function checkForPayrollBlockers()
    {
        // check if there are payroll blockers
        $payrollBlockers = $this
            ->payrolls_model
            ->getPayrollBlockers(
                $this->companyId
            );
        // set default flag to false
        $data = false;
        //
        if ($payrollBlockers["blocker_json"]) {
            // set the payroll blockers
            $this->data["payrollBlockers"] = $payrollBlockers["blocker_json"];
            // load the view
            $this->loadView("v1/payroll/regular/blockers");
            // set the flag to true
            $data = true;
        }
        return $data;
    }

    /**
     * load the js bundle
     *
     * @param string $page
     */
    protected function loadJsBundle(array $files, string $fileName)
    {
        return
            bundleJs(
                $files,
                $this->js,
                $fileName,
                $this->disableMinifiedFilesCreation
            );
    }

    /**
     * load the css bundle
     *
     * @param string $page
     */
    protected function loadCssBundle(array $files, string $fileName)
    {
        return
            bundleCSS(
                $files,
                $this->css,
                $fileName,
                $this->disableMinifiedFilesCreation
            );
    }

    /**
     * check login session
     */
    protected function checkSessionStatus()
    {
        if (!$this->session->userdata("logged_in")) {
            return SendResponse(
                401,
                [
                    'errors' => ['Access denied. Please login to access this route.']
                ]
            );
        }
    }

    /**
     * set the public access identifier
     *
     * @param bool isPublicAccess
     */
    private function setPublicAccess(bool $isPublicAccess)
    {
        // set the access type
        $this->isPublic = $isPublicAccess;

        return $this;
    }

    /**
     * set the public data of session
     *
     * @return array
     */
    private function verifyLogin()
    {
        if (!$this->isPublic) {
            return true;
        }
        //
        if (
            !$this
                ->session
                ->userdata('logged_in')
        ) {
            //
            return redirect("login");
        }
        // set the company id
        $this->companyId =
            $this
                ->session
                ->userdata('logged_in')['company_detail']["sid"];
        //
        return true;
    }

    /**
     * set the public data of session
     *
     * @return array
     */
    private function setFormValidators()
    {
        // set default form messages
        $this
            ->form_validation
            ->set_message(
                'required',
                '"{field}" is required.'
            );
        $this
            ->form_validation
            ->set_message(
                'valid_email',
                '"{field}" is invalid.'
            );
        //
        return $this;
    }

    /**
     * set the options
     *
     * @method setPublicData
     * @return array
     */
    private function setOptions()
    {
        //
        $this->pages = [
            "main" => [
                "header" => "main/header",
                "footer" => "main/footer",
            ]
        ];
        $this->data = $this->setPublicData();
        // set path to CSS file
        $this->css = 'public/v1/css/payrolls/';
        // set path to JS file
        $this->js = 'public/v1/js/payrolls/';
        // set the creation of minified files
        $this->disableMinifiedFilesCreation = false;
        //
        return $this;
    }

    /**
     * set the public data of session
     *
     * @return array
     */
    private function setPublicData(): array
    {
        //
        $data = [];
        // check and set user session
        $data['session'] = checkUserSession();
        // set
        $data['loggedInPerson'] = $data['session']['employer_detail'];
        $data['companyId'] = $data['session']['company_detail']['sid'];
        $data['employerId'] = $data['session']['employer_detail']['sid'];
        $data['level'] = 0;
        // get the security details
        $data['securityDetails'] =
            $data['security_details'] = db_get_access_level_details(
                $data['session']['employer_detail']['sid'],
                null,
                $data['session']
            );
        // make sure the view is new one
        $data["sanitizedView"] = true;

        return $data;
    }

    /**
     * check if company sync is in progress
     *
     * @return array
     */
    private function checkCompanySync()
    {
        if ($this->isPublic) {
            // check if job is synced
            $this
                ->payrolls_model
                ->checkSyncProgress(
                    $this->companyId
                );
        }
    }
}
