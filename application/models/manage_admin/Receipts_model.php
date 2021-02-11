<?php

class Receipts_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function insert_receipt_record($data_to_insert = array()){
        $this->db->insert('receipts', $data_to_insert);
        return $this->db->insert_id();
    }

    function update_receipt_record($sid, $data_to_insert = array()){
        $this->db->where('sid', $sid);
        $this->db->update('receipts', $data_to_insert);
    }

    function generate_new_receipt($company_sid, $invoice_sid, $amount, $transaction_method, $generated_by, $generated_from, $invoice_type = null){

        $data_to_insert = array();
        $data_to_insert['company_sid'] = $company_sid;
        $data_to_insert['invoice_sid'] = $invoice_sid;
        $data_to_insert['amount'] = $amount;
        $data_to_insert['processed_by_sid'] = $generated_by;
        $data_to_insert['processed_from'] = $generated_from;
        $data_to_insert['receipt_date'] = date('Y-m-d H:i:s');
        $data_to_insert['transaction_method'] = $transaction_method;

        if($invoice_type != null){
            $data_to_insert['invoice_type'] = $invoice_type;
        }

        //insert receipt record
        $receipt_sid = $this->insert_receipt_record($data_to_insert);

        //update receipt number
        $receipt_number = $this->generate_receipt_number($receipt_sid);

        $data_to_update = array();
        $data_to_update['receipt_number'] = $receipt_number;

        $this->update_receipt_record($receipt_sid, $data_to_update);
    }

    function generate_receipt_number($receipt_sid){
        $padded_receipt_sid = str_pad($receipt_sid, 6, 0, STR_PAD_LEFT);

        return 'AHR-RCT-' . date('Y') . '-' . $padded_receipt_sid;
    }



}
