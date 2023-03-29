<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cron_document_assign extends Public_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('hr_documents_management_model', 'hrm');
    }


    //
    function index(){
        //
        // Daily 10:20
        // Weekly 4 10:20
        // Monthly 23 10:20
        // Yearly 1/12 10:20
        //
        $documents = $this->hrm->getAssignAndSendDocuments(['weekly', 'monthly', 'yearly', 'custom', 'daily']);
        //
        sendMail(
            'notifications@automotohr.com',
            'dev@automotohr.com',
            'Scheduled documents reassign - Start',
            '<pre>it is auto executed at '.date('Y-m-d H:i:s', strtotime('now')).'<br />'.(print_r($documents, true)),
            'AutomotoHR',
            'dev@automotohr.com'
        );
        //
        if(!$documents || !count($documents)) exit(0);
        $dates = array(1 => "Mon", 2 => "Tue", 3 => "Wed", 4 => "Thu", 5 => "Fri", 6 => "Sat", 7 => "Sun");
        //
        $report = [];
        //
        $send = [];
        //
        foreach(
            $documents as $document
        ){
            //
            $md = reset_datetime([ 'datetime' => date('m/d', strtotime('now')), 'from_format' => 'm/d', 'format' => 'm/d', 'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR, 'new_zone' => $document['timezone'], '_this' => $this ]);
            $m = reset_datetime([ 'datetime' => date('m', strtotime('now')), 'from_format' => 'm', 'format' => 'm', 'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR, 'new_zone' => $document['timezone'], '_this' => $this ]);
            $d = reset_datetime([ 'datetime' => date('d', strtotime('now')), 'from_format' => 'd', 'format' => 'd', 'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR, 'new_zone' => $document['timezone'], '_this' => $this ]);
            $hi = reset_datetime([ 'datetime' => date('h:i A', strtotime('now')), 'from_format' => 'h:i A', 'format' => 'h:i A', 'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR, 'new_zone' => $document['timezone'], '_this' => $this ]);
            $hi2 = reset_datetime([ 'datetime' => date('H:i', strtotime('now')), 'from_format' => 'H:i', 'format' => 'H:i', 'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR, 'new_zone' => $document['timezone'], '_this' => $this ]);
            $day = reset_datetime([ 'datetime' => date('D', strtotime('now')), 'from_format' => 'D', 'format' => 'D', 'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR, 'new_zone' => $document['timezone'], '_this' => $this ]);
            $n = reset_datetime([ 'datetime' => date('N', strtotime('now')), 'from_format' => 'D', 'format' => 'D', 'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR, 'new_zone' => $document['timezone'], '_this' => $this ]);
            // 
            $fd = '';
            $ft = '';
            //
            if(!empty($document['assign_time'])){
                $document['assign_time'] = DateTime::createFromFormat('h:i A', $document['assign_time'])->format('H:i');
            }

            $tas = [];
            $tas['Filter'] = [
                'MD' => $md,
                'M' => $m,
                'D' => $d,
                'HI' => $hi,
                'HI2' => $hi2,
                'DAY' => $day,
                'N' => $n
            ];
            $tas['Data'] = [
                'Date' => $document['assign_date'],
                'Time' => $document['assign_time'],
                'Type' => $document['assign_type'],
                'ID' => $document['sid']
            ];
            $tas['Sent'] = 0;
            //
            $employees = null;
            //
            if(!empty($document['assigned_employee_list'])) if($document['assigned_employee_list'] != 'all') $employees = json_decode($document['assigned_employee_list'], true);
            //
            switch ($document['assign_type']) {
                case 'weekly':
                    $yes = false;
                    //
                    $document['assign_date'] = $dates[$document['assign_date']];
                    //
                    if(!empty($document['assign_date']) && !empty($document['assign_time'])){
                        if($document['assign_date'] == $dates[$n]) if($hi2 == $document['assign_time']) $yes = true;
                    } else if(!empty($document['assign_date'])){
                        if($document['assign_date'] == $dates[$n]) $yes = true;
                    } else if(!empty($document['assign_time'])){
                        if($hi2 == $document['assign_time']) $yes = true;
                    }
                    //
                    if($yes){
                        $tas['Sent'] = 1;
                        $this->hrm->reAssignDocument(
                            $document['sid'],
                            $employees
                        );
                        continue;
                    }
                break;
                case 'monthly':
                    //
                    $yes = false;
                    //
                    $ad = new DateTime(DateTime::createFromFormat('Y-m-d H:i:s', $document['assigned_date'])->format('Y-m-d'));
                    $cd = new DateTime(date('Y-m-d', strtotime('now')));
                    $int = $ad->diff($cd)->days;
                    //
                    if(!empty($document['assign_date']) && !empty($document['assign_time'])){
                        if($int > 29 && $d == $document['assign_date'] && $document['assign_time'] == $hi2) $yes = true;
                    } else if(!empty($document['assign_date'])) {
                        if( $int > 29 && $d == $document['assign_date']) $yes = true;
                    } else if(!empty($document['assign_time'])){
                        if($document['assign_time'] == $hi2) $yes = true;
                    }
                    
                    if($yes){
                        $tas['Sent'] = 1;
                        $this->hrm->reAssignDocument(
                            $document['sid'],
                            $employees
                        );
                        continue;
                    }
                break;
                case 'yearly':
                case 'custom':
                    //
                    $yes = false;
                    //
                    $ad = new DateTime(DateTime::createFromFormat('Y-m-d H:i:s', $document['assigned_date'])->format('Y-m-d'));
                    $cd = new DateTime(date('Y-m-d', strtotime('now')));
                    $int = $ad->diff($cd)->y;
                    //
                    $t = explode('/', $document['assign_date']);
                    //
                    $am = $t[0];
                    $ad = $t[1];
                    //
                    if(!empty($document['assign_date']) && !empty($document['assign_time'])){
                        if($int >= 1 && $d == $ad && $m == $am && $document['assign_time'] == $hi2) $yes = true;
                    } else if(!empty($document['assign_date'])) {
                        if($int >= 1 && $d == $ad && $m == $am ) $yes = true;
                    } else if(!empty($document['assign_time'])){
                        if($document['assign_time'] == $hi2) $yes = true;
                    }
                    
                    if($yes){
                        $tas['Sent'] = 1;
                        $this->hrm->reAssignDocument(
                            $document['sid'],
                            $employees
                        );
                        continue;
                    }
                break;
                case "daily":
                    //
                    $ad = new DateTime(DateTime::createFromFormat('Y-m-d H:i:s', $document['assigned_date'])->format('Y-m-d'));
                    $cd = new DateTime(date('Y-m-d', strtotime('now')));
                    //
                    $int = $ad->diff($cd)->days;
                    
                    //
                    if($int >= 1 && $hi2 == $document['assign_time']) {
                        $tas['Sent'] = 1;
                        $this->hrm->reAssignDocument(
                            $document['sid'],
                            $employees
                        );
                    }
                break;
            }

            //
            $report[] = $tas;
        }

        //
        sendMail(
            FROM_EMAIL_NOTIFICATIONS,
            'dev@automotohr.com',
            'Scheduled documents reassign',
            '<pre>it is auto executed at '.date('Y-m-d H:i:s', strtotime('now')).'<br />'.(print_r($report, true)),
            'AutomotoHR',
            'no-reply@automotohr.com'
        );
        exit(0);
    }


    /**
     * Send completed documents report
     * 
     */
    function sendTodayCompletedDocumentList(){
        //
        $queryDate = date('Y-m-d', strtotime('now'));
        // Get list
        $documents = $this->hrm->getCompletedDocuments($queryDate, 1);
        //
        sendMail(
            'notifications@automotohr.com',
            'dev@automotohr.com',
            'Sent Completed Document Report',
            'it is auto executed at '.date('Y-m-d H:i:s', strtotime('now')).'<br />'.(print_r($documents, true)),
            'AutomotoHR',
            'dev@automotohr.com'
        );
        // 
        if(empty($documents) && empty($documents['Data'])) exit(0);
        //
        $todayDate = date('m/d/Y', strtotime('now'));
        // Get template
        $template = get_email_template(DOCUMENT_COMPLETION_REPOSRT_TEMPLATE_SID);
        //
        $subject = $template['subject'].' for '.$todayDate;
        $body = $template['text'];
        $fromEmail = !empty($template['from_email']) ? $template['from_email'] : 'notifications@automotohr.com';
        //
        $fileHeaders = ['Employee', 'Completed Documents', 'Date'];
        //
        foreach($documents['Data'] as $k => $v){
            //
            $employers = getNotificationContacts($k, 'documents_status');
            //
            $companyName = array_values($v)[0]['CompanyName'];
            //
            $hf = message_header_footer($k, $companyName);
            //
            $tempPath = APPPATH.'../temp_files/';
            $filename = '/document_completion_report_'.(str_replace('-', '_', stringToSlug($companyName))).'_'.date('m_d_Y', strtotime('now')).'.csv';
            $fullfilepath = $tempPath.$filename;
            //
            $f  = fopen($fullfilepath, 'w');
            //
            fputcsv($f, $fileHeaders);
            //
            foreach($v as $v0){
                //
                $fDataArray = [
                    $v0['user_name'],
                    implode(", ", $v0['Documents']),
                    $todayDate
                ];
                //
                fputcsv($f, $fDataArray);
            }
            //
            fclose($f);
            // Send Email
            foreach($employers as $employer){
                //
                $replaceArray = [
                    '{{company_name}}' => $companyName,
                    '{{first_name}}' => $employer['contact_name'],
                    '{{last_name}}' => $employer['contact_name'],
                    '{{contact-name}}' => $employer['contact_name'],
                    '{{date}}' => $todayDate
                ];
                //
                $newBody = str_replace(
                    array_keys($replaceArray),
                    $replaceArray,
                    $body
                );
                sendMailWithAttachmentRealPath(
                    $fromEmail,
                    $employer['email'],
                    $subject,
                    $newBody,
                    $companyName.' @ AutomotoHR',
                    $fullfilepath
                );
            }
            //
            @unlink($fullfilepath);
        }
        // Update the send report status
        $this->hrm->updateCompletedDocumentsStatusForReport($documents['Ids']);
        //
        echo "Done";
        exit(0);
    }

    /**
     * Send completed documents report
     * 
     */
    function sendTodayCompletedGDocumentList(){
        //
        $queryDate = date('Y-m-d', strtotime('now'));
        // Get list
        $documents = $this->hrm->getCompletedDocuments($queryDate, 0);
        //
        sendMail(
            'notifications@automotohr.com',
            'dev@automotohr.com',
            'Sent Generated Document Report',
            'it is auto executed at '.date('Y-m-d H:i:s', strtotime('now')).''.(print_r($documents, true)),
            'AutomotoHR',
            'dev@automotohr.com'
        );
        // 
        if(empty($documents) && empty($documents['Data'])) exit(0);
        //
        $todayDate = date('m/d/Y', strtotime('now'));
        // Get template
        $template = get_email_template(DOCUMENT_COMPLETION_REPOSRT_TEMPLATE_SID);
        //
        $subject = $template['subject'].' for '.$todayDate;
        $body = $template['text'];
        $fromEmail = !empty($template['from_email']) ? $template['from_email'] : 'notifications@automotohr.com';
        //
        $fileHeaders = ['Employee', 'Completed Documents', 'Date'];
        //
        foreach($documents['Data'] as $k => $v){
            //
            $employers = getNotificationContacts($k, 'general_information_status');
            //
            $companyName = array_values($v)[0]['CompanyName'];
            //
            $hf = message_header_footer($k, $companyName);
            //
            $tempPath = APPPATH.'../temp_files/';
            $filename = '/document_completion_report_'.(str_replace('-', '_', stringToSlug($companyName))).'_'.date('m_d_Y', strtotime('now')).'.csv';
            $fullfilepath = $tempPath.$filename;
            //
            $f  = fopen($fullfilepath, 'w');
            //
            fputcsv($f, $fileHeaders);
            //
            foreach($v as $v0){
                //
                $fDataArray = [
                    $v0['user_name'],
                    implode(", ", $v0['Documents']),
                    $todayDate
                ];
                //
                fputcsv($f, $fDataArray);
            }
            //
            fclose($f);
            // Send Email
            foreach($employers as $employer){
                //
                $replaceArray = [
                    '{{company_name}}' => $companyName,
                    '{{first_name}}' => $employer['contact_name'],
                    '{{last_name}}' => $employer['contact_name'],
                    '{{contact-name}}' => $employer['contact_name'],
                    '{{date}}' => $todayDate
                ];
                //
                $newBody = str_replace(
                    array_keys($replaceArray),
                    $replaceArray,
                    $body
                );
                sendMailWithAttachmentRealPath(
                    $fromEmail,
                    $employer['email'],
                    $subject,
                    $newBody,
                    $companyName.' @ AutomotoHR',
                    $fullfilepath
                );
            }
            //
            @unlink($fullfilepath);
        }
        // Update the send report status
        $this->hrm->updateCompletedDocumentsStatusForReport($documents['Ids']);
        //
        echo "Done";
        exit(0);
    }
}