<?php
defined('BASEPATH') OR exit('No direct script access allowed');
ini_set("memory_limit","512M");

class Notifications extends Public_Controller {
    private $res;
    //
    public function __construct() {
        parent::__construct();
        //
        $this->res = array(
            'Status' => false,
            'Response' => 'Invalid request'
        );
        //
        if (!$this->session->userdata('logged_in')) $this->resp();
        //
        $this->load->model('notification_model');
    }


    public function get_notifications() {
        //
        $ses = $this->session->userdata('logged_in');
        //
        $data = $this->notification_model->getNotifications(
            $ses,
            strtolower($ses['employer_detail']['access_level']) != 'employee' ? false : true
        );
        if (checkIfAppIsEnabled('performance_review')) {
            $this->load->model('Performance_management_model', 'pmm');
            $goalsCount = $this->pmm->getMyGoals($ses['employer_detail']['sid']);
            //
            if(count($goalsCount)){
                $data[] = [
                    'count' => count($goalsCount),
                    'link' => base_url('performance-management/lms/goals'),
                    'title' => 'Goals'
                ];
            }
        }
        if(!sizeof($data)){
            $this->res['Response'] = 'No notifications found.';
            $this->resp();
        }
        //
        $this->res['Status'] = TRUE;
        $this->res['Data'] = $data;
        $this->res['Response'] = 'Proceed.';
        $this->resp();
    }


    private function resp(){
        header('Content-Type: application/json');
        echo json_encode($this->res);
        exit(0);
    }

}
