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
        //
        if($ses['employer_detail']['access_level'] == 'Admin' || $ses['employer_detail']['access_level_plus'] || $ses['employer_detail']['pay_plan_plus']){
            //
            $this->load->model('varification_document_model');
            $total = 0;
            $total += $this->varification_document_model->get_all_users_pending_w4($ses['company_detail']['sid'], 'employee', TRUE);
            $total += $this->varification_document_model->get_all_users_pending_i9($ses['company_detail']['sid'], 'employee', TRUE);
            $total += $this->varification_document_model->getPendingAuthDocs($ses['company_detail']['sid'], 'employee', TRUE);
            $total += $this->varification_document_model->get_all_users_pending_w4($ses['company_detail']['sid'], 'applicant', TRUE);
            $total += $this->varification_document_model->get_all_users_pending_i9($ses['company_detail']['sid'], 'applicant', TRUE);
            $total += $this->varification_document_model->getPendingAuthDocs($ses['company_detail']['sid'], 'applicant', TRUE);
            //
            if($total != 0){
                $data[] = [
                    'count' => $total,
                    'link' => base_url('hr_documents_management/company_varification_document'),
                    'title' => 'Pending Employer Sections'
                ];
            }
        }
        //
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
