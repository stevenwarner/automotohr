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
        $this->load->model('varification_document_model');
        // For verification documents
        $companyEmployeesForVerification = $this->varification_document_model->getAllCompanyInactiveEmployee($ses['company_detail']['sid']);
        $companyApplicantsForVerification = $this->varification_document_model->getAllCompanyInactiveApplicant($ses['company_detail']['sid']);
        //
        
        $EmsStatus = getCompanyEmsStatusBySid($ses['company_detail']['sid'], false);


        $data = $this->notification_model->getNotifications(
            $ses,
            strtolower($ses['employer_detail']['access_level']) != 'employee' ? false : true,
            $companyEmployeesForVerification,
            $companyApplicantsForVerification,
            $EmsStatus
        );
        //
        if (checkIfAppIsEnabled('performance_management')) {
            //
            $this->load->model('Performance_management_model', 'pmm');
            //
            $review = $this->pmm->getMyReviewCounts($ses['company_detail']['sid'], $ses['employer_detail']['sid']);
            $total_goals = count($this->pmm->getMyGoals($ses['employer_detail']['sid']));
            //
            if($total_goals){
                $data[] = [
                    'count' => $total_goals,
                    'link' => base_url('performance-management/goals'),
                    'title' => 'Goals'
                ];
            }
            //
            if($review['Total']){
                $data[] = [
                    'count' => $review['Total'],
                    'link' => base_url('performance-management/reviews/all'),
                    'title' => 'Pending Reviews'
                ];
            }
            
            //
            if($review['Feedbacks']){
                $data[] = [
                    'count' => $review['Feedbacks'],
                    'link' => base_url('performance-management/reviews'),
                    'title' => 'Pending Feedbacks'
                ];
            }
        }
        //
        if($EmsStatus==1 && ($ses['employer_detail']['access_level_plus'] || $ses['employer_detail']['pay_plan_plus'])){
            //
            $total = 0;
            $total += $this->varification_document_model->get_all_users_pending_w4($ses['company_detail']['sid'], 'employee', TRUE, $companyEmployeesForVerification);
            $total += $this->varification_document_model->get_all_users_pending_i9($ses['company_detail']['sid'], 'employee', TRUE, $companyEmployeesForVerification);
            $total += $this->varification_document_model->get_all_users_pending_w4($ses['company_detail']['sid'], 'applicant', TRUE, $companyApplicantsForVerification);
            $total += $this->varification_document_model->get_all_users_pending_i9($ses['company_detail']['sid'], 'applicant', TRUE, $companyApplicantsForVerification);
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
        if (checkIfAppIsEnabled('attendance')) {
            //
            $this->load->model('Attendance_model', 'atm');
            //
            $overtimeEmployees = $this->atm->GetEmployeeWithOverTime(
                $ses['company_detail']['sid'], 
                date('Y-m-d', strtotime('now')), 
                date('Y-m-d', strtotime('now')),
                [],
                'sid, employee_sid'
            );
            // //
            if($overtimeEmployees){
                $data[] = [
                    'count' => count($overtimeEmployees),
                    'link' => base_url('attendance/overtime'),
                    'title' => 'Overtime Employees'
                ];
            }
        }
        //
        
        if($EmsStatus==1){
            $total_document_approval = count($this->notification_model->getMyApprovalDocuments($ses['employer_detail']['sid']));
            //
            if($total_document_approval){
                $data[] = [
                    'count' => $total_document_approval,
                    'link' => base_url('hr_documents_management/approval_documents'),
                    'title' => 'Pending Approval Documents'
                ];
            }
        }

        //
        $this->load->model('v1/course_model');
        $pendingTrainings =
                $this->course_model->getEmployeePendingCourseCount(
                    $ses['company_detail']['sid'],
                    $ses['employer_detail']['sid']
                );
        //
        if ($pendingTrainings > 0) {
            $data[] = [
                'count' => $pendingTrainings,
                'link' => base_url('lms/courses/my'),
                'title' => 'Pending Courses'
            ];
        }        

        //
        if(!sizeof($data)){
            $this->res['Response'] = 'No notifications found.';
            $this->resp();
        }
        //
        if (checkIfAppIsEnabled('performanceevaluation')) {
            $this
            ->load
            ->model(
                "v1/Employee_performance_evaluation_model",
                "employee_performance_evaluation_model"
            );
            //
            $pendingPerformanceSection =
                    $this->employee_performance_evaluation_model->checkEmployeeUncompletedDocument(
                        $ses['employer_detail']['sid']
                    );
            //
            if ($pendingPerformanceSection) {
                $data[] = [
                    'count' => 1,
                    'link' => base_url('hr_documents_management/my_documents'),
                    'title' => 'Pending Performance Evaluation'
                ];
            }   
            //
            $pendingVerificationPerformanceSectionOne =
                $this->employee_performance_evaluation_model->checkPerformanceVerificationDocumentSection(
                    $ses['employer_detail']['sid'],
                    1
                ); 
            //
            if ($pendingVerificationPerformanceSectionOne) {
                $data[] = [
                    'count' => $pendingVerificationPerformanceSectionOne,
                    'link' => base_url('fillable/epe/verification/documents'),
                    'title' => 'Pending Performance Verification'
                ];
            }    
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
