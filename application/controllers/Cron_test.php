<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Cron_test extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('dashboard_model');
        $this->load->model('reports_model');
        $this->load->model('manage_admin/copy_employees_model');
    }

    public function index()
    {
        //$users = $this->dashboard_model->get_company_detail(31);



        $dummy_email = 'j.taylor.title@gmail.com';
        mail($dummy_email, 'Cron Test ' . date('Y-m-d H:i:s'), 'Cron Working');
    }



    //
    public function emailEmployeeDocuemntsDetail()
    {


        require_once(APPPATH . 'libraries/phpmailer/PHPMailerAutoload.php');
        $email = new PHPMailer;

        $companies = $this->copy_employees_model->get_all_companies();

        foreach ($companies as $companyRow) {

            $post['companySid'] = $companyRow['sid'];
            $post['employeeSid'] = ['all'];
            $post['employeeStatus'] = [];
            $post['documentSid'] = ['all'];
            $post['documentAction'] = ['all'];
            $post['documentSid'] = ['all'];
            $post['documentAction'] = ['all'];

            $employeedocument = $this->reports_model->getEmployeeAssignedDocumentForReport($post);


            if (sizeof($employeedocument['Data'])) {


                $employeeList = array_column($employeedocument['Data'], 'sid');


                $subject = $companyRow['CompanyName'];

                $file_name = str_replace(' ', '_', $subject) . '_employee_assigned_document_report';
                $file_name = $file_name . '_' . date('Y_m_d-H:i:s') . '.csv';
                $dir = FCPATH . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'csv_reports';


                if (!is_dir($dir)) mkdir($dir, 0777, true);
                $temp_file_path = $dir . '/' . $file_name;

                if (file_exists($temp_file_path)) {
                    unlink($temp_file_path);
                }


                $header_row = "Company Name ," . $companyRow['CompanyName'] . PHP_EOL;

                $header_row .= "Export Date ," . date('m/d/Y H:i:s ', strtotime('now')) . STORE_DEFAULT_TIMEZONE_ABBR . PHP_EOL;

                $header_row .= 'Employees,# of Documents,# Not Completed,# Completed,# No Action Required,Documents,';
                $file_content = '';
                $file_content .= $header_row . PHP_EOL;



                $rows = $employeedocument['Data'];
                $employeeOBJ = $this->reports_model->getEmployeeByIdsOBJ($employeeList);

                //
                foreach ($rows as $row) {

                    //
                    $totalAssignedDocs = count($row['assigneddocuments']);
                    $totalAssignedGeneralDocs = count($row['assignedgeneraldocuments']);
                    $totalDocs = $totalAssignedDocs + $totalAssignedGeneralDocs;

                    $totalDocsNotCompleted = 0;
                    $totalDocsCompleted = 0;
                    $totalDocsNoAction = 0;
                    $completedStatus = '';

                    //
                    if (!empty($row['assignedi9document'])) {
                        $totalDocs = $totalDocs + 1;
                        if ($row['assignedi9document'][0]['user_consent'] == 1) {
                            $totalDocsCompleted = $totalDocsCompleted + 1;
                        } else {
                            $totalDocsNotCompleted = $totalDocsNotCompleted + 1;
                        }
                    }
                    if (!empty($row['assignedw9document'])) {
                        $totalDocs = $totalDocs + 1;
                        if ($row['assignedw9document'][0]['user_consent'] == 1) {
                            $totalDocsCompleted = $totalDocsCompleted + 1;
                        } else {
                            $totalDocsNotCompleted = $totalDocsNotCompleted + 1;
                        }
                    }
                    if (!empty($row['assignedw4document'])) {
                        $totalDocs = $totalDocs + 1;
                        if ($row['assignedw4document'][0]['user_consent'] == 1) {
                            $totalDocsCompleted = $totalDocsCompleted + 1;
                        } else {
                            $totalDocsNotCompleted = $totalDocsNotCompleted + 1;
                        }
                    }
                    if (!empty($row['assignedeeocdocument'])) {
                        $totalDocs = $totalDocs + 1;
                        if ($row['assignedeeocdocument'][0]['last_completed_on'] != '' && $row['assignedeeocdocument'][0]['last_completed_on'] != null) {
                            $totalDocsCompleted = $totalDocsCompleted + 1;
                            $completedStatus = ' (Completed) ';
                        } else {
                            $totalDocsNotCompleted = $totalDocsNotCompleted + 1;
                            $completedStatus = ' (Not Completed) ';
                        }
                    }

                    //
                    $doc = '';

                    if (!empty($row['assignedi9document'])) {

                        if ($row['assignedi9document'][0]['user_consent'] == 1) {
                            $completedStatus = ' (Completed) ';
                        } else {
                            $completedStatus = ' (Not Completed) ';
                        }

                        //$doc .= "I9 Fillable" . $completedStatus . "\n\n";

                        $doc .= "I9 Fillable" . $completedStatus . "";
                    }
                    if (!empty($row['assignedw9document'])) {

                        if ($row['assignedw9document'][0]['user_consent'] == 1) {
                            $completedStatus = ' (Completed) ';
                        } else {
                            $completedStatus = ' (Not Completed) ';
                        }

                        //$doc .= "W9 Fillable" . $completedStatus . "\n\n";
                        $doc .= "W9 Fillable" . $completedStatus . "";
                    }
                    if (!empty($row['assignedw4document'])) {

                        if ($row['assignedw4document'][0]['user_consent'] == 1) {
                            $completedStatus = ' (Completed) ';
                        } else {
                            $completedStatus = ' (Not Completed) ';
                        }
                        //  $doc .= "W4 Fillable" . $completedStatus . "\n\n";
                        $doc .= "W4 Fillable" . $completedStatus . "";
                    }
                    if (!empty($row['assignedeeocdocument'])) {

                        if ($row['assignedeeocdocument'][0]['last_completed_on'] != '' && $row['assignedeeocdocument'][0]['last_completed_on'] != null) {
                            $completedStatus = ' (Completed) ';
                        } else {
                            $completedStatus = ' (Not Completed) ';
                        }
                        //  $doc .= "EEOC Form" . $completedStatus . "\n\n";
                        $doc .= "EEOC Form" . $completedStatus . "";
                    }

                    //
                    if (count($row['assignedgeneraldocuments']) > 0) {
                        foreach ($row['assignedgeneraldocuments'] as $rowGeneral) {

                            if ($rowGeneral['is_completed'] == 1) {
                                $totalDocsCompleted = $totalDocsCompleted + 1;
                                $completedStatus = ' (Completed) ';
                            } else {
                                $completedStatus = ' (Not Completed) ';
                                $totalDocsNotCompleted = $totalDocsNotCompleted + 1;
                            }

                            // $doc .= ucwords(str_replace('_', ' ', $rowGeneral['document_type'])) . $completedStatus . "\n\n";
                            $doc .= ucwords(str_replace('_', ' ', $rowGeneral['document_type'])) . $completedStatus . "";
                        }
                    }

                    //
                    if (count($row['assigneddocuments']) > 0) {
                        foreach ($row['assigneddocuments'] as $assigned_row) {
                            $completedStatus = '';
                            if ($assigned_row['completedStatus'] == 'Not Completed') {
                                $totalDocsNotCompleted = $totalDocsNotCompleted + 1;
                                $completedStatus = ' (Not Completed) ';
                            }
                            if ($assigned_row['completedStatus'] == 'Completed') {
                                $totalDocsCompleted = $totalDocsCompleted + 1;
                                $completedStatus = ' (Completed) ';
                            }

                            if ($assigned_row['completedStatus'] == 'No Action Required') {
                                $totalDocsNoAction = $totalDocsNoAction  + 1;
                                $completedStatus = ' (No Action Required) ';
                            }

                            if ($assigned_row['confidential_employees'] != null) {
                                $confidentialEmployees = explode(',', $assigned_row['confidential_employees']);

                                if (in_array($data['employerSid'], $confidentialEmployees)) {
                                    // $doc .= $assigned_row['document_title'] . $completedStatus . "\n\n";
                                    $doc .= $assigned_row['document_title'] . $completedStatus . "";
                                } else {
                                    $totalDocs = $totalDocs - 1;
                                }
                            } else {
                                //$doc .= $assigned_row['document_title'] . $completedStatus . "\n\n";
                                $doc .= $assigned_row['document_title'] . $completedStatus . "";
                            }
                        }
                    }

                    //
                    if ($row['assignedPerformanceDocument'] != 'Not Assigned' && !empty($row['assignedPerformanceDocument'])) {
                        // $doc .= "Performance Evaluation Document (" . $row['assignedPerformanceDocument'] . ")\n\n";

                        $doc .= "Performance Evaluation Document (" . $row['assignedPerformanceDocument'] . ")";
                    }


                    $file_content .= $employeeOBJ[$row['sid']] . ',' . $totalDocs . ',' . $totalDocsNotCompleted . ',' . $totalDocsCompleted . ',' . $totalDocsNoAction . ',' . $doc . PHP_EOL;
                }


                $buffer = '';
                $buffer = $buffer . $file_content;

                $fd = fopen($temp_file_path, "w");
                fputs($fd, $buffer);
                fclose($fd);


                $email->AddAttachment($temp_file_path, str_replace(' ', '_', $subject) . '.csv');
                $email->FromName = "AutoMotoHR";
                $email->addReplyTo(NULL);
                $email->CharSet = 'UTF-8';
                $email->isHTML(true);
                $email->From =  'events@automotohr.com';
                $email->Subject   = $subject;



                //
                $managersList = $this->reports_model->get_all_employeesNew($companyRow['sid']);

                if (!empty($managersList)) {

                    foreach ($managersList as $empRow) {

                        $to = $empRow['email'];
                        $name = $empRow['first_name'].' '.$empRow['last_name']; //$employee_info['first_name'] . ' ' . $employee_info['last_name'];
                        $message_date = date('Y-m-d H:i:s');

                        $hf = message_header_footer_domain($companyRow['sid'], $companyRow['CompanyName']);

                        $downloadLink = '<a href="' . base_url() . 'assets/csv_reports/' . $file_name . '"> Download </a>';

                        $body = $hf['header']
                            . '<h2 style="width:100%; margin:0 0 20px 0;">Dear ' . $name . ',</h2>'
                            . '<br><br>'
                            . 'Here is the report of "' . str_replace(' Employees Report', '', $subject) . '" employees Assigned Documents in CSV formate attached with this email.'
                            . '<br><br><b>'
                            . 'Date:</b> '
                            . $message_date
                            . '<br><br>'
                            . $downloadLink
                            . $hf['footer'];
                        $email->Body = $body;

                       // _e($body,true);

                        $email->addAddress($to);
                        $email->send();
                    }
                }
            }
        }

        echo "done";
    }
}
