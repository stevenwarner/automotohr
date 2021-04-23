<?php defined('BASEPATH') or exit('No direct script access allowed');


class Cron_common extends CI_Controller{
    //
    private $verifyToken;

    function __construct(){
        parent::__construct();
        $this->load->model('common_model');
        //
        $this->verifyToken = getCreds('AHR')->VerifyToken;
    }

    function tos(){
        //
        $this->common_model->startR();
        $this->common_model->endR();
    }

    //
    function auto_email_reminder($verificationToken){
        //
        if($this->verifyToken != $verificationToken){
            echo "Failed";
            exit(0);
        }
        //
        $records = $this->common_model->get_all_licenses();
        //
        if(empty($records)){
            exit(0);
        }
        //
        $todaysDate = date('Y-m-d', strtotime('now'));
        //
        $this->load->model('common_ajax_model');
        //
        foreach($records as $record){
            //
            $expiryDate = !empty($record['license_details']['license_expiration_date']) ? $record['license_details']['license_expiration_date'] : '';
            // //
            if(empty($expiryDate)){
                continue;
            }
            //
            $expiryDate = str_replace('/', '-', $expiryDate);
            //
            $format = 'Y-m-d';
            // Re-format the date
            if(preg_match('/[0-9]{2}-[0-9]/', $expiryDate)){
                $format = 'm-d-Y';
            }
            //
            $expiryDate = DateTime::createfromformat($format, $expiryDate)->format('Y-m-d');
            //
            $difference = dateDifferenceInDays($expiryDate, $todaysDate);
            //
            $days = 0;
            //
            if($difference == 15){
                $days = 15;
            } else if($difference == 7){
                $days = 7;
            } else if($difference == 3){
                $days = 3;
            }
            //
            if($days != 0){
                //
                $type = $record['license_type'] == 'drivers' ? 'drivers-license' : 'occupational-license';
                //
                $note = "Your {$record['license_type']} license is expiring in {$days} days.";
                //
                $email_hf = message_header_footer($record['CompanyId'], ucwords($record['CompanyName']));
                // Let's record the action
                $this->common_ajax_model->send_reminder_email_record([
                    'userId' => $record['sid'],
                    'userType' => 'employee',
                    'moduleType' => $type,
                    'note' => $note,
                    'lastSenderSid' => 0
                ]);
                // Email Send
                $this->send_email_reminder($type, $note, [
                    'first_name' => $record['first_name'],
                    'last_name' => $record['last_name'],
                    'email' => $record['email']
                ], [
                    'CompanyName' => $record['CompanyName'],
                    'Location_Address' => $record['Location_Address'],
                    'PhoneNumber' => $record['PhoneNumber'],
                ], $email_hf);
                // Last Send Date Update
                $this->common_model->update_license_last_sent_date($record['licenseId']);
            }
            //
            echo "Executed \n";
        }
        //
        exit(0);
    }

    //
    private function send_email_reminder($type, $note, $user_detail, $company_detail, $email_hf){
        //
        $this->load->model('common_ajax_model');
        // Set link
        $link = '<a href="'.(base_url('general_info')).'" style="padding: 10px; color: #ffffff; background-color: #0000ff; border-radius: 5px;">Go To Document</a>';
        //
        $email_slug = $type.'-reminder-email';
        // Get email template
        $template = $this->common_ajax_model->get_email_template_by_code($email_slug);
        // Set replace array
        $replaceArray = [];
        $replaceArray['{{first_name}}'] = ucwords($user_detail['first_name']);
        $replaceArray['{{last_name}}'] = ucwords($user_detail['last_name']);
        $replaceArray['{{company_name}}'] = ucwords($company_detail['CompanyName']);
        $replaceArray['{{company_address}}'] = $company_detail['Location_Address'];
        $replaceArray['{{company_phone}}'] = $company_detail['PhoneNumber'];
        $replaceArray['{{career_site_url}}'] = 'https://'.$email_hf['sub_domain'];
        $replaceArray['{{note}}'] = "<strong>Note:</strong>".$note;
        $replaceArray['{{link}}'] = $link;
        //
        $indexes = array_keys($replaceArray);
        // Change subject
        $subject = str_replace($indexes, $replaceArray, $template['subject']);
        $body = $email_hf['header'].str_replace($indexes, $replaceArray, $template['text']).$email_hf['footer'];
        //
        $from_email = empty($template['from_email']) ? FROM_EMAIL_NOTIFICATIONS : $template['from_email'];
        $from_name = empty($template['from_name']) ? ucwords($company_detail['CompanyName']) : str_replace($indexes, $replaceArray, $template['from_name']);
        //
        log_and_sendEmail($from_email, $user_detail['email'], $subject, $body, $from_name);
    }

}