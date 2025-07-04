<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Twilio extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('manage_admin/sms_model');
        $this->load->library('twilio/Twilioapp', null, 'twilio');
    }

    /**
     * Receives
     * Created on: 17-07-2019
     * 
     * @return JSON
     */
    function receive_request(){
        if(!sizeof($this->input->post())) exit(0);
        // Load twilio
        $resp = $this->twilio->receiveRequest();
        // Error handling
        if(!is_array($resp)) { _e('Throw an error', true, true); }
        if(isset($resp['Error'])) { _e('Throw an error', true, true); }

        if(isset($resp['URL']['company_id'])) $resp['URL']['cid'] = $resp['URL']['company_id'];
        // Get last row and insert data
        // Get the last record matching query from db
        $result_arr = $this->sms_model->get_last_sms_row(
            $resp['DataArray']['receiver_phone_number'],
            $resp['URL']['cid'],
            $resp['DataArray']['message_service_sid']
        );
       //_e($this->input->post(), true);
//_e($resp, true);
        //_e($result_arr, true);
        //
        if(!$result_arr || !sizeof($result_arr)) exit(0);
      _e('here', true);

        // Set insert array
        $insert_array = $resp['DataArray'];
        //
        if($resp['URL']['cid'] != 0){
            $insert_array['company_id'] = $resp['URL']['cid'];
            $insert_array['sender_user_id'] = $result_arr['receiver_user_id'];
            $insert_array['sender_user_type'] = $result_arr['receiver_user_type'];
        } else {
            $insert_array['sender_user_id'] = NULL;
            $insert_array['sender_user_type'] = 'admin';
        }
        $insert_array['message_body'] = $resp['DataArray']['message_body'];
        $insert_array['module_slug']  = $result_arr['module_slug'];
        $insert_array['message_mode']  = $result_arr['message_mode'];
        $insert_array['message_sid']  = $resp['DataArray']['message_sid'];
        $insert_array['receiver_user_id'] = $result_arr['sender_user_id'];
        $insert_array['receiver_user_type'] = $result_arr['sender_user_type'];
        $insert_array['is_sent'] = 0;

        $insert_array['sender_phone_number'] = $result_arr['receiver_phone_number'];
        $insert_array['receiver_phone_number'] = $result_arr['sender_phone_number'];
        // Insert data into database
        $insert_id = $this->sms_model->_insert('portal_sms', $insert_array);
        _e('', true);
         _e($insert_id, true);
         _e('', true);
        //
        echo 'done';
        exit(0);
    }


    /**
     * SMS cron
     * TOBE deleted after testing
     *
     */
    function sms_cron($module = 'admin'){
        exit(0);
        // Fetch all SMS
        $this->load->library('twilio/Twilioapp', null, 'twilio');
        $sms = $this->sms_model->fetch_last_sent_sms($module);

        // _e($sms, true, true);
        //
        if(!$sms && !sizeof($sms)) exit(0);
        $this
        ->twilio
        ->setMode('production');
        //
        foreach ($sms as $k0 => $v0) {
            $resp = $this
            ->twilio
            ->fetchMessagesList(
                array(
                    "dateSentAfter" => new DateTime($v0['created_at']),
                    "to" => $v0['sender_phone_number'],
                    "from" => $v0['receiver_phone_number']
                ), 
                40
            );

            if(isset($resp['Error'])) continue;
            // Loop through data
            foreach ($resp as $k1 => $v1) {
                // Check in db
                if($this->sms_model->check_message_sid($v1['Sid']) != 0) continue;
                $insert_array = array();
                $insert_array['replied_at'] = $v1['SentAt'];
                $insert_array['message_sid'] = $v1['Sid'];
                $insert_array['message_body'] = $v1['Body'];
                $insert_array['message_service_sid'] = $v1['MessageServiceSid'];
                $insert_array['sender_phone_number'] = $v1['From'];
                $insert_array['receiver_phone_number'] = $v1['To'];

                $insert_array['message_mode'] = 'production';
                $insert_array['module_slug'] = $module;
                $insert_array['receiver_user_id'] = $v0['sender_user_id'];
                $insert_array['receiver_user_type'] = $v0['sender_user_type'];
                //
                if($module == 'admin') $insert_array['sender_user_type'] = 'admin';
                else {
                    $insert_array['sender_user_ide'] = $v0['receiver_user_id'];
                    $insert_array['sender_user_type'] = $v0['receiver_user_type'];
                }
                $insert_array['is_sent'] = 0;
                $insert_array['currency_iso'] = $v1['PriceUnit'];
                $insert_array['charged_amount'] = $v1['Price'];

                $insert_id = $this->sms_model->_insert('portal_sms', $insert_array);
            }
        }
        exit(0);
    }


    function test(){
        exit(0);
        // $resp = 
        // $this
        // ->twilio
        // ->setMode('production')
        // ->setAlfaSenderName('Mubashir Ahemd')
        // ->setMessageServiceCode(array( 'company_id' => 0 ))
        // ->setMessageServicePhoneSid('PNcd41479c6145ecb0eab1dcdcf608360e')
        // ->createMessageService()
        // ;


        // $resp = 
        // $this
        // ->twilio
        // ->setMode('production')
        // ->setCountryISO('US')
        // ->availablePhoneNumbers();


        // $resp2 = 
        // $this
        // ->twilio
        // ->setMode('production')
        // ->setAlfaSenderName('1AutomotoHr')
        // ->setMessageServiceCode(
        //     array(
        //         'company_id' => 51
        //     )
        // )
        // ->setMessageServicePhoneSid('PN65520a9b2ee78e5b1013f87241ba895c')
        // ->createMessageService();
        // $h = fopen('step5.txt', 'w');
        // fwrite($h, json_encode($resp2));
        // fclose($h);
        // // _e($resp2, true, true);
        // // Check for errors
        // if(isset($resp2['Error'])) return false;

        //  // Save number to db
        //  $this->db->insert(
        //     'portal_company_sms_module', 
        //     array(
        //         'company_sid' => 51,
        //         'phone_sid' => 'PN65520a9b2ee78e5b1013f87241ba895c',
        //         'alfa_sender_sid' => $resp2['AlfaSid'],
        //         'alfa_sender_name' => $resp2['AlfaName'],
        //         'message_service_sid' => $resp2['Sid'],
        //         'message_service_name' => $resp2['MessageServiceCode'],
        //         'phone_number' => '(951) 476-0684',
        //         'purchaser_type' => 'admin',
        //         'purchaser_id' => 1
        //     )
        // );


        // _e($resp, true, true);
    }

}