<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cron_employee_survey extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('2022/Employee_survey_model', 'ES_model');
    }

    public function index()
    {
        $pendingNotifications = $this->ES_model->getTodayPendingNotifications();
        //
        foreach ($pendingNotifications as  $notification) {
            $respondents = $this->ES_model->getPendingSurveyRespondents($notification["sid"]);
            //
            foreach ($respondents as $respondent) {
                //
                $respondentInfo = $this->ES_model->getRespondentProfileInfo($respondent["employee_sid"]);
                //
                if (!empty($respondentInfo)) {
                    //
                    $companyName = getCompanyNameBySid($respondentInfo["parent_sid"]);
                    //
                    $replacement_array = array();
                    $replacement_array['company_name'] = ucwords($companyName);
                    $replacement_array['first_name'] = ucwords($respondentInfo['first_name']);
                    $replacement_array['last_name'] = ucwords($respondentInfo['last_name']);
                    $replacement_array['event_title'] = ucwords($notification['title']);
                    $replacement_array['baseurl'] = base_url();
                    //
                    $hf = message_header_footer(
                        $respondentInfo["parent_sid"],
                        $companyName
                    );
                    //
                    log_and_send_templated_email(EMPLOYEE_SURVEY_NOTIFICATION, $respondentInfo['email'], $replacement_array, $hf);
                }
                
                //
            }
            //
            $this->ES_model->updateSurveyNotification($notification["sid"], $notification["notification_type"]);
        }
    }


}