<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Handles all AJAX calls for applicants
 * 
 * @author  AutomotoHR <www.automotohr.com>
 * @author  Mubashir   <mubashar@automotohr.com>
 * @version 1.0
 */
class Applicants extends Public_Controller {

    private $companySES;
    private $employerSES;
    
    /**
     * Constructor
     */
    public function __construct() {
        //
        parent::__construct();
        //
        $this->load->model('ajax/Applicants_model', 'am');
    }

   /**
    * Reverts the applicant
    */
    public function revertApplicantFromOnboarding($applicantId){
        //
        $this->checkLogin();
        // Check if applicant belongs to the current company
        $isApplicantOfCurrentCompany = $this->am->getDataFromTable(
            'portal_job_applications', 
            ['*'],
            [
                'employer_sid' => $this->companySES['sid'],
                'sid' => (int)$applicantId
            ],
            ['sid', 'desc'],
            true
        );
        //
        if(!$isApplicantOfCurrentCompany){
            return SendResponse(
                404, [
                    'Response' => "Data failed to verify."
                ]
            );
        }
        //
        $this->am->revertApplicantOnboard(
            (int)$applicantId
        );
        //
        return SendResponse(
            200, [
                'Response' => "You have successfully reverted the onboarding."
            ]
        );
    }


    /**
     * Checks is use is logged in or not
     * 
     * @param boolean isReturn
     * @return boolean
     */
    private function checkLogin($isReturn = false){
        //
        if(!$this->session->userdata('logged_in')){
            return $isReturn ? false: SendResponse(404, ['Response' => 'Invalid Request']);
        }
        //
        $this->companySES = $this->session->userdata('logged_in')['company_detail'];
        //
        $this->employerSES = $this->session->userdata('logged_in')['employer_detail'];
        //
        return true;
    }
}
