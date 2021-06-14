<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Performance Management Module
 *
 * PHP version >= 5.6
 *
 * @category   Module
 * @package    Performance Managment
 * @author     AutomotoHR <www.automotohr.com>
 * @author     Mubashir Ahmed
 * @version    1.0
 * @link       https://www.automotohr.com
 */

class Performance_management extends Public_Controller{
    // Set page path
    private $pp;
    // Set mobile path
    private $mp;
    //  Set argumenst array
    private $pargs;
    //
    private $resp = [];
    //
    private $tables = [];

    /**
     * Contructor
     *
     * @employee Mubashir Ahmed 
     * @date     02/01/2021
     */
    function __construct(){
        //
        parent::__construct();
        //
        $this->tables = [
            'PM' => 'performance_management',
            'PMT' => 'performance_management_templates',
            'PMCT' => 'performance_management_company_templates',
            'USER' => 'users',
            'DM' => 'departments_management',
            'DTM' => 'departments_team_management',
            'DME' => 'departments_employee_2_team',
            'PMR' => 'performance_management_reviewees',
            'PMRV' => 'performance_management_reviewers',
            'PMQ' => 'performance_management_review_questions',
            'G' => 'goals',
            'GC' => 'goal_comments',
            'GH' => 'goal_history',
        ];
        //
        $this->pargs = [];
        // Load helper
        $this->load->helper('performance_management');
        // Load modal
        $this->load->model('performance_management_model', 'pmm', TRUE, $this->tables);
        // Load user agent
        $this->load->library('user_agent');
        //
        $this->mp = $this->agent->is_mobile() ? '' : '';
        //
        $this->pp = 'Performance_management/theme2/';
        //
        $this->pargs['pp'] = $this->pp;
        //
        $this->resp = [
            'Status' => FALSE,
            'Redirect' => TRUE,
            'Response' => 'Invalid request'
        ];
        //
        $this->header = 'main/header';
        $this->footer = 'main/footer';
    }

    /**
     * Dashboard
     * 
     * @employee Mubashir Ahmed 
     * @date     02/01/2021
     * 
     * @return Void
     */
    function dashboard(){
        // 
        $this->checkLogin($this->pargs);
        // Set title
        $this->pargs['title'] = 'Performance Management - Dashboard';
        // Get department & teams list
        // $employees = $this->pmm->getAllCompanyEmployees($this->pargs['companyId']);
        // //
        // if(!empty($employees)){
        //     foreach($employees as $employee){
        //         $this->pargs['employees'][$employee['Id']] = [
        //             'name' => ucwords($employee['FirstName'].' '.$employee['LastName']),
        //             'role' => $employee['FullRole'],
        //             'img' => getImageURL($employee['Image'])
        //         ];
        //     }
        // }
        // Get goals 
        // $this->pargs['goals'] = $this->pmm->getGoals($this->pargs['employerId']);
        // // Get Assigned Reviews 
        // $this->pargs['assignedReviews'] = $this->pmm->getReviewsByType($this->pargs['employerId'], 'assigned');
        // $this->pargs['feedbackReviews'] = $this->pmm->getReviewsByType($this->pargs['employerId'], 'feedback');
        // // Get employer role
        // $this->pargs['permission'] = $this->pmm->getEmployeePermission($this->pargs['employerId'], $this->pargs['level']);
        // // Get department & teams list
        // My goals

        // Set logged in employee departments and teams
        $this->pargs['employee_dt'] = $this->pmm->getMyDepartmentAndTeams($this->pargs['companyId'], $this->pargs['employerId']);
        // Set employee information for the blue screen
        $this->pargs['employee'] = $this->pargs['session']['employer_detail'];

        $this->load->view($this->header, $this->pargs);
        $this->load->view("{$this->pp}header");
        $this->load->view("{$this->pp}dashboard");
        $this->load->view("{$this->pp}footer");
        $this->load->view($this->footer);
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
        $data['companyId'] = $data['session']['company_detail']['sid'];
        $data['companyName'] = $data['session']['company_detail']['CompanyName'];
        $data['employerId'] = $data['session']['employer_detail']['sid'];
        $data['employerName'] = ucwords($data['session']['employer_detail']['first_name'] . ' ' . $data['session']['employer_detail']['last_name']);
        $data['isSuperAdmin'] = $data['session']['employer_detail']['access_level_plus'];
        $data['level'] = $data['session']['employer_detail']['access_level_plus'] == 1 || $data['session']['employer_detail']['pay_plan_flag'] == 1 ? 1 : 0;
        $data['employerRole'] = $data['session']['employer_detail']['access_level'] ;
        $data['load_view'] = $data['session']['company_detail']['ems_status'];
        $data['hide_employer_section'] = 1;
        //
        if ($return) {
            return true;
        }
        else {
            $data['security_details'] = db_get_access_level_details($data['employerId'], NULL, $data['session']);
        }
    }

    /**
     * Get cleaned params
     * 
     * @employee Mubashir Ahmed
     * @date     02/10/2021
     * 
     * @return Array
     */
    private function getParams(){
        $r = [];
        $p = $this->uri->segment_array();
        unset($p[0], $p[1], $p[2], $p[3]);
        $r = array_values($p);
        $r = array_map(function($i){
            return strip_tags(trim($i));
        }, $r);
        return $r;
    }
}