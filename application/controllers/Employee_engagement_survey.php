<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Employee engagement survey
 *
 * PHP version >= 5.6
 *
 * @category   Module
 * @package    Employee engagement survey
 * @author     AutomotoHR <www.automotohr.com>
 * @version    1.0
 * @link       https://www.automotohr.com
 */

class Employee_engagement_survey extends Public_Controller{

    private $arg;
    /**
     * Contructor
     */
    function __construct(){
        //
        parent::__construct();
        //
        $this->header = 'main/header';
        $this->footer = 'main/footer';
        $this->pageHeader = 'employee_engagement_survey/header';
    }

    /**
     * Overview Page
     * @return
     */
    function overview(){
        //
        $this->checkLogin($this->arg);
        //
        $this->arg['title'] = 'Test';
        $this->arg['employee'] = $this->arg['session']['employer_detail'];
        //
        $this->load
        ->view($this->header, $this->arg)
        ->view($this->pageHeader)
        ->view('employee_engagement_survey/overview')
        ->view($this->footer);
    }


    /**
     * Check user session and set data
     * 
     * @employee Mubashir Ahmed
     * @date     02/02/2021
     *
     * @param Reference $data
     * @param Bool      $return (Default is 'FALSE')
     * 
     * @return VOID
     */
    private function checkLogin(&$data, $return = FALSE){
        //
        if (!$this->session->userdata('logged_in')) {
            if ($return) {
                return false;
            }
            redirect('login', 'refresh');
        }
        //
        $data['session'] = $this->session->userdata('logged_in');
        //
        $data['isSuperAdmin'] = $data['session']['employer_detail']['access_level_plus'];
        $data['load_view'] = $data['session']['company_detail']['ems_status'];
        $data['hide_employer_section'] = 1;
        //
        $data['security_details'] = db_get_access_level_details($data['session']['employer_detail']['sid'], NULL, $data['session']);
        //
        if ($return) {
            return true;
        }
    }
}