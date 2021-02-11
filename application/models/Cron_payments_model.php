<?php

class Cron_payments_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_single_record_payment_processing(){
        $this->db->where('payment_processed', 0);
        $this->db->limit(1);
        $this->db->order_by('sid', 'ASC');

        $record = $this->db->get('recurring_payments_process_history')->result_array();

        if(!empty($record)){
            $record = $record[0];
            $picked_sid = $record['sid'];

            $this->db->where('sid', $picked_sid);
            $dataToSave = array();
            $dataToSave['payment_processed'] = 1;

            $this->db->update('recurring_payments_process_history', $dataToSave);

            return $record;
        } else {
            return array();
        }
    }

    function update_payment_status($sid, $payment_status, $payment_response_text){
        $dataToSave = array();
        $dataToSave['payment_status'] = $payment_status;
        $dataToSave['payment_response_text'] = $payment_response_text;
        $dataToSave['payment_date'] = date('Y-m-d H:i:s');

        $this->db->where('sid', $sid);
        $this->db->update('recurring_payments_process_history', $dataToSave);
    }

    function get_company_information($company_sid){
        $this->db->select('*');
        $this->db->where('sid', $company_sid);
        return $this->db->get('users')->result_array();
    }
}
