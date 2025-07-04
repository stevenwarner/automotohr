<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Documents_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }
    public function get_w9_document ($sid) {
        $this->db->select('*');
        $this->db->where('affiliate_sid', $sid);

        $record_obj = $this->db->get('affiliate_w9form');
        $data = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($data)) {
            return $data[0];
        } else {
            return array();
        }
    }

    public function update_w9_form($sid, $data_to_update) {
        $this->db->where('affiliate_sid', $sid);
        $this->db->update('affiliate_w9form', $data_to_update);
    }

    public function get_affiliate_detail ($verification_key) {
        $this->db->select('*');
        $this->db->where('verification_key', $verification_key);

        $record_obj = $this->db->get('marketing_agencies');
        $data = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($data)) {
            return $data[0];
        } else {
            return array();
        }
    }

    function unset_verification_key($sid)
    {
        $dataToUpdate = array();
        $dataToUpdate['verification_key'] = NULL;

        $this->db->where('sid', $sid);
        $this->db->update('marketing_agencies', $dataToUpdate);
    }

    function get_w9_form_data($sid){
        $this->db->select('*');
        $this->db->where('sid', $sid);
        $records_obj = $this->db->get('affiliate_w9form');

        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        
        if(!empty($records_arr)){
            return $records_arr[0];
        } else {
            return array();
        }
    }

    //
    function getAllDocuments($userId){
        return $this->db
        ->select('sid, document_name, status, insert_date, aws_document_name, client_aws_filename')
        ->where('marketing_agency_sid', $userId)
        ->where('active_status', 1)
        ->get('marketing_agency_documents')
        ->result_array();
    }

    //
    function insertDocument($ins){
        $this->db->insert('marketing_agency_documents', $ins);
    }
    
    //
    function updateDocument($upd, $id){
        $this->db
        ->where('sid', $id)
        ->update('marketing_agency_documents', $upd);
    }
}