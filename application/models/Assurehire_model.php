<?php

class Assurehire_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    //
    function validateId($id, $code){
        $t = $this->db
        ->where('external_id', $code)
        ->select('package_response')
        ->get('background_check_orders')
        ->result_array();
        //
        if(!count($t)) return [];
        
        return $t;
    }

 	function update_order_status($order_sid, $order_status) {
        $data_to_update = array();
        $data_to_update['order_response'] = $order_status;
        $this->db->where('external_id', $order_sid);
        return $this->db->update('background_check_orders', $data_to_update);
    }

    //
    function packageExists($packageId){
        return $this->db
        ->where('package_code', $packageId)
        ->count_all_results('products');
    }

    //
    function addProduct($ins){
        $this->db->insert('products', $ins);
        return $this->db->insert_id();
    }

    //
    function updateProduct($upd, $condition){
        $this->db->update('products', $upd, $condition);
    }

    //
    function GetCreds($companyId = null){
        //
        $this->db->select('company_sid, username, password');
        //
        if($companyId){
            $this->db->where('company_sid', $companyId);
        }
        //
        $result = $this->db->get('assurehire_companies');
        //
        $records = $result->result_array();
        //
        $result = $result->free_result();
        //
        if(!empty($records)){
            //
            $tmp = [];
            //
            foreach($records as $record){
                //
                $tmp[$record['company_sid']] = $record;
            }
            //
            $records = $tmp;
        }
        //
        return $records;
    }
}    