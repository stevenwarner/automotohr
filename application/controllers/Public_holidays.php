<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Public_holidays extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('public_holidays_model');
    }

    //
    function index(){
        $key = 'AIzaSyAvsWMh5HASBfE_m9v6XRaP60yOm6LuXN8';
        $url = 'https://www.googleapis.com/calendar/v3/calendars/en.usa%23holiday%40group.v.calendar.google.com/events?key='.( $key ).'';
        // Fetch the public holidays
        $holidays = @json_decode(getFileData($url), true);
        if(!isset($holidays['items']) && !sizeof($holidays['items'])){
            // Send email to DEV regarding this issue
            @mail(TO_EMAIL_DEV, 'Public Holidays CRON - FAILED: ' . date('Y-m-d H:i:s'), print_r($holidays, true));
            exit(0);
        }
        //
        $holidays = $holidays['items'];
        // Loop through holidays
        foreach ($holidays as $k => $v) {
            // Create insert/update array
            $d = array();
            $d['status'] = $v['status'];
            $d['to_date'] = $v['end']['date'];
            $d['from_date'] = $v['start']['date'];
            $d['event_link'] = $v['htmlLink'];
            $d['holiday_title'] = $v['summary'];
            $d['holiday_year'] = substr($v['id'], 0, 4);
            //
            $this->public_holidays_model->checkAndInsertHoliday($d);
        }
        //
        @mail(TO_EMAIL_DEV, 'Public Holidays CRON - SUCCESS: ' . date('Y-m-d H:i:s'), print_r($holidays, true));
        _e('Success', true);
        exit(0);
    }
}