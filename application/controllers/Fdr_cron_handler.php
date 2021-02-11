<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Fdr_cron_handler extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
        $this->load->model('demo_model');
    }

    public function index()
    {
        $today = new DateTime('now');

        $hours = intval($today->format('H'));

        $new_hours = $hours + 1;

        $filter_date = clone $today;
        $filter_date->setTime($new_hours, 0, 0);

        $datetime_for_db = $filter_date->format('Y-m-d H:i:s');

        $records = $this->demo_model->get_scheduled_tasks_for_cron($datetime_for_db);

        foreach($records as $record){
            $view_data = array();
            $view_data['schedule'] = $record;

            $template_html = $this->load->view('manage_admin/free_demo/cron_email_template_partial', $view_data, true);

            $replacement_array = array();
            $replacement_array['administrator'] = $record['created_by_first_name'] . ' ' . $record['created_by_last_name'];
            $replacement_array['task_details'] = $template_html;

            $creator_email = $record['created_by_email'];
            $dummy_email = 'j.taylor.title@gmail.com';

            //log_and_send_templated_email(ADMIN_NOTIFICATION_ON_DEMO_SCHEDULE, $creator_email, $replacement_array);
            log_and_send_templated_email(ADMIN_NOTIFICATION_ON_DEMO_SCHEDULE, TO_EMAIL_STEVEN, $replacement_array);

            $this->demo_model->update_email_trigger_status($record['sid']);
        }

    }

    public function test(){
        $dummy_email = 'j.taylor.title@gmail.com';
        mail($dummy_email, 'Cron Test ' . date('Y-m-d H:i:s'), 'Cron Test Email');
    }

}
