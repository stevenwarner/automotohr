<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cron_sms extends CI_Controller
{

    public function index()
    {
        // Load the twilio library
        $this->load->library('twilio/Twilioapp', null, 'twilio');
        //
        $sms = $this->db->select('sid, message_sid')
        ->where('is_read', 0)
        ->where('is_sent', 1)
        ->order_by('sid', 'desc')
        ->get('portal_sms')
        ->result_array();
        //
        if(empty($sms)){ exit(0);}
        //
        foreach($sms as $v){
            $resp = 
            $this
            ->twilio
            ->setMode('production')
            ->fetchMessageBySid($v['message_sid']);
            //
            if(isset($resp['Error'])) {
                continue;
            }
            //
            $this->db
            ->where('sid', $v['sid'])
            ->update('portal_sms', [
                'is_read' => $resp['Status'] == 'Deliverd' ? 1 : 0,
                'charged_amount' => ltrim($resp['Price'], '-')
            ]);
        }
    }


}
