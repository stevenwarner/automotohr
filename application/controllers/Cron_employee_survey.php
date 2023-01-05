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
            $pendingNotifications = $this->ES_model->getTodaypendingNotifications();
        }
        _e($pendingNotifications,true,true);
    }


}