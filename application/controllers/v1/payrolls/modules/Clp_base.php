<?php defined('BASEPATH') || exit('No direct script access allowed');

class Clp_base extends CI_Controller
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
    protected $createMinifyFiles;

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
     * main entry point to controller
     */
    public function __construct()
    {
        // inherit
        parent::__construct();
        //
        if (
            !$this
                ->session
                ->userdata('logged_in')
        ) {
            return redirect("login");
        }
        // set the company id
        $this->companyId =
            $this
                ->session
                ->userdata('logged_in')['company_detail']["sid"];
        // set the access type
        $this->isPublic = true;
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
        // load the main payroll model
        $this
            ->load
            ->model(
                "v1/Payroll/Payrolls_model",
                "payrolls_model"
            );
        // check if job is synced
        $this
            ->payrolls_model
            ->checkSyncProgress(
                $this->companyId
            );
        // set path to CSS file
        $this->css = 'public/v1/css/payrolls/';
        // set path to JS file
        $this->js = 'public/v1/js/payrolls/';
        // set the creation of minified files
        $this->createMinifyFiles = false;
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
     * set the public data of session
     *
     * @return array
     */
    protected function setPublicData(): array
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
        $data['security_details'] = db_get_access_level_details(
            $data['session']['employer_detail']['sid'],
            null,
            $data['session']
        );
        // make sure the view is new one
        $data["sanitizedView"] = true;

        return $data;
    }
}
