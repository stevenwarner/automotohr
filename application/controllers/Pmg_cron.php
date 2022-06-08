<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Pmg_cron extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('performance_management_model', 'pmm');
    }

    public function index($verification_key = null) {
        //
        if(
            $verification_key != 'dwwbtPzuoHI9d5TEIKBKDGWwNoGEUlRuSidW8wQ4zSUHIl9gBxRx18Z3Dqk4HV7ZNCbu2ZfkjFVLHWINnY5uzMkUfIiINdZ19NJj'
        ){ exit(0); }
        // Fetch goals
        $goals7 = $this->pmm->getGoalsExpire(7);
        //
        if(!empty($goals7)){
            foreach($goals7 as $goal){
                //
                $employeList = [];
                //
                if($goal['goal_type'] == 2){
                    //
                    $employeList = $this->pmm->getTeamEmployees($goal['sid'], $goal['companyId']);
                } else if($goal['goal_type'] == 3){
                    $employeList = $this->pmm->getDepartmentEmployees($goal['sid'], $goal['companyId']);
                } else if($goal['goal_type'] == 1){
                    $employeList[] = $goal['sid'];
                }
                //
                if(!empty($employeList)){
                    //
                    foreach($employeList as $imp){
                        //
                        $this->sendEmail(
                            GOAL_EXPIRY_3, 
                            $imp,
                            $goal['companyId'],
                            $goal['CompanyName'],
                            $goal['title'],
                            7
                        );
                    }
                }
            }
        }

        // Fetch goals
        $goals3 = $this->pmm->getGoalsExpire(3);
        //
        if(!empty($goals3)){
            foreach($goals3 as $goal){
                //
                $employeList = [];
                //
                if($goal['goal_type'] == 2){
                    //
                    $employeList = $this->pmm->getTeamEmployees($goal['sid'], $goal['companyId']);
                } else if($goal['goal_type'] == 3){
                    $employeList = $this->pmm->getDepartmentEmployees($goal['sid'], $goal['companyId']);
                } else if($goal['goal_type'] == 1){
                    $employeList[] = $goal['sid'];
                }
                //
                if(!empty($employeList)){
                    //
                    foreach($employeList as $imp){
                        //
                        $this->sendEmail(
                            GOAL_EXPIRY_3, 
                            $imp,
                            $goal['companyId'],
                            $goal['CompanyName'],
                            $goal['title'],
                            3
                        );
                    }
                }
            }
        }

        // Fetch goals
        $goals1 = $this->pmm->getGoalsExpire(1);
        //
        if(!empty($goals1)){
            foreach($goals1 as $goal){
                //
                $employeList = [];
                //
                if($goal['goal_type'] == 2){
                    //
                    $employeList = $this->pmm->getTeamEmployees($goal['sid'], $goal['companyId']);
                } else if($goal['goal_type'] == 3){
                    $employeList = $this->pmm->getDepartmentEmployees($goal['sid'], $goal['companyId']);
                } else if($goal['goal_type'] == 1){
                    $employeList[] = $goal['sid'];
                }
                //
                if(!empty($employeList)){
                    //
                    foreach($employeList as $imp){
                        //
                        $this->sendEmail(
                            GOAL_EXPIRY_3, 
                            $imp,
                            $goal['companyId'],
                            $goal['CompanyName'],
                            $goal['title'],
                            1
                        );
                    }
                }
            }
        }
        //
        echo "All Done";
        //
        exit(0);            
    }

    //
    private function sendEmail($templateId, $employeeId, $companyId, $companyName, $title, $days){
        // Get employee details
        $employeeDetails = $this->pmm->getEmployeeDetails($employeeId);
        // Get template details
        $template = $this->pmm->getTemplate($templateId);
        //
        $replaceArray = [];
        $replaceArray['{{goal_title}}'] = $title;
        $replaceArray['{{days}}'] = $days;
        $replaceArray['{{first_name}}'] = ucwords($employeeDetails['first_name']);
        $replaceArray['{{last_name}}'] = ucwords($employeeDetails['last_name']);
        $replaceArray['{{link}}'] = '<a href="'.base_url('performance-management/goals').'" style="padding: 10px; color: #fff; background-color: #fd7a2a; border-radius: 5px;">Show Goal</a>';
        //
        $hf = message_header_footer($companyId, $companyName);
        //
        $body = $hf['header']. str_replace(array_keys($replaceArray), $replaceArray, $template['text']).$hf['footer'];
        //
        $this->load->model('Hr_documents_management_model', 'HRDMM');
        if($this->HRDMM->isActiveUser($employeeId)){
        log_and_sendEmail(
            'notification@automotohr.com', 
            $employeeDetails['email'],
            str_replace(array_keys($replaceArray), $replaceArray, $template['subject']),
            $body,
            $companyName
        );
    }

    }
}