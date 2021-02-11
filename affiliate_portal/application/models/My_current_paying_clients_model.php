<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class My_current_paying_clients_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    public function get_secondary_agency($m_sid){
        $this->db->select('sid');
        $this->db->where('referred_by',$m_sid);
        $secondary_agency = $this->db->get('marketing_agencies')->result_array();
        return $secondary_agency;
    }

    public function get_all_paying_clients($referral_id, $secondary = 0) {
        $this->db->select('CompanyName, registration_date, expiry_date');
        $this->db->where('marketing_agency_sid', $referral_id);
        
      if($secondary){
            $this->db->select('marketing_agencies.full_name');
            $this->db->join('marketing_agencies','users.marketing_agency_sid = marketing_agencies.sid','left');
        }
        $this->db->where('recurring_payments.status', 'active');
        $this->db->join('recurring_payments','users.sid = recurring_payments.company_sid');
        $this->db->where('users.active', 1);
        $records_obj = $this->db->get('users');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

   
}