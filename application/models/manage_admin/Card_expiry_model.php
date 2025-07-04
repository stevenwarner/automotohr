<?php
class card_expiry_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    function get_active($type,$limit = null, $start = null){
        $this->db->select('users.CompanyName,emp_cards.expire_month,emp_cards.expire_year,emp_cards.type,emp_cards.number,emp_cards.name_on_card');
        $this->db->where('emp_cards.active',$type);
        // Updated on: 22-04-2019
        if($type == 1)
            $this->db->where('emp_cards.is_default', 1);
        if($type == 0){
            $this->db->where('concat(emp_cards.expire_year,"-",emp_cards.expire_month) <= ', date('Y-m'));
        }
        if($limit != null){
            $this->db->limit($limit, $start);
        }
       // $this->db->group_by('emp_cards.employer_sid');
        $this->db->join('users','users.sid = emp_cards.company_sid','left');
        $this->db->from('emp_cards');
        // Recurring payment check
        // must exists in recurring payment table
        // with status 'active'
        $this->db->where('recurring_payments.status', 'active');
        $this->db->join('recurring_payments','recurring_payments.company_sid = emp_cards.company_sid','inner');
        //
        $this->db->where('concat(emp_cards.expire_year,"-",IF(emp_cards.expire_month < 9 && length(emp_cards.expire_month) = 1, concat(0,emp_cards.expire_month), emp_cards.expire_month)) not between "'.(date('Y-m', strtotime('now'))).'" and "'.(date('Y-m', strtotime('+1 month'))).'" ', null);

        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function get_active_count($type){
        $this->db->select('users.CompanyName,emp_cards.expire_month,emp_cards.expire_year,emp_cards.type,emp_cards.number,emp_cards.name_on_card');
        $this->db->where('emp_cards.active',$type);
        // Updated on: 22-04-2019
        $this->db->where('emp_cards.is_default', 1);
        $this->db->join('users','users.sid = emp_cards.company_sid','left');
        $this->db->from('emp_cards');
        // Recurring payment check
        // must exists in recurring payment table
        // with status 'active'
        $this->db->where('recurring_payments.status', 'active');
        $this->db->join('recurring_payments','recurring_payments.company_sid = emp_cards.company_sid','inner');
        //
        $this->db->where('concat(emp_cards.expire_year,"-",IF(emp_cards.expire_month < 9 && length(emp_cards.expire_month) = 1, concat(0,emp_cards.expire_month), emp_cards.expire_month)) not between "'.(date('Y-m', strtotime('now'))).'" and "'.(date('Y-m', strtotime('+1 month'))).'" ', null);
        $count = $this->db->count_all_results();
        return $count;
    }

    // Updated on: 06-05-2019
    function expiring_in_month($current_month,$current_year,$limit = null, $start = null){
        // $this->db->where('emp_cards.expire_month',$current_month+1);
        // $this->db->where('emp_cards.expire_year',$current_year);

        $this->db
        ->select('users.CompanyName,emp_cards.expire_month,emp_cards.expire_year,emp_cards.type,emp_cards.number,emp_cards.name_on_card')
        ->where('emp_cards.active',1)
        ->where('concat(emp_cards.expire_year,"-",IF(emp_cards.expire_month < 9 && length(emp_cards.expire_month) = 1, concat(0,emp_cards.expire_month), emp_cards.expire_month)) between "'.(date('Y-m', strtotime('now'))).'" and "'.(date('Y-m', strtotime('+1 month'))).'" ', null)
        // Updated on: 22-04-2019
        ->where('emp_cards.is_default', 1)
        ->join('users','users.sid = emp_cards.company_sid','left')
        ->from('emp_cards');
        //
        if($limit != null) $this->db->limit($limit, $start);
        // Recurring payment check
        // must exists in recurring payment table
        // with status 'active'
        $this->db
        ->where('recurring_payments.status', 'active')
        ->join('recurring_payments','recurring_payments.company_sid = emp_cards.company_sid','inner');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj = $records_obj->free_result();
        return $records_arr;
    }

    function expiring_in_month_count($current_month,$current_year){
        $this->db->select('users.CompanyName,emp_cards.expire_month,emp_cards.expire_year,emp_cards.type,emp_cards.number,emp_cards.name_on_card');
        $this->db->where('emp_cards.active',1)
        ->where('emp_cards.is_default', 1);
        // $this->db->where('emp_cards.expire_month',$current_month+1);
        // $this->db->where('emp_cards.expire_year',$current_year);
        // Updated on: 22-04-2019
        // $this->db->where('concat(emp_cards.expire_year,"-",emp_cards.expire_month)', date('Y-m', strtotime('+1 month')));
        $this->db->where('concat(emp_cards.expire_year,"-",IF(emp_cards.expire_month < 9 && length(emp_cards.expire_month) = 1, concat(0,emp_cards.expire_month), emp_cards.expire_month)) between "'.(date('Y-m', strtotime('now'))).'" and "'.(date('Y-m', strtotime('+1 month'))).'" ', null);
        $this->db->join('users','users.sid = emp_cards.company_sid','left');
        $this->db->from('emp_cards');
        // Recurring payment check
        // must exists in recurring payment table
        // with status 'active'
        $this->db->where('recurring_payments.status', 'active');
        $this->db->join('recurring_payments','recurring_payments.company_sid = emp_cards.company_sid','inner');
        $count = $this->db->count_all_results();
        return $count;
    }



    // Added on: 06-05-2019
    function expired_cards($limit = null, $start = null, $is_count = FALSE){
        // $this->db->where('emp_cards.expire_month',$current_month+1);
        // $this->db->where('emp_cards.expire_year',$current_year);

        $this->db
        ->select('users.CompanyName,emp_cards.expire_month,emp_cards.expire_year,emp_cards.type,emp_cards.number,emp_cards.name_on_card')
        ->where('emp_cards.active',1)
        ->where('concat(emp_cards.expire_year,"-",IF(emp_cards.expire_month < 9 && length(emp_cards.expire_month) = 1, concat(0,emp_cards.expire_month), emp_cards.expire_month)) < "'.(date('Y-m', strtotime('now'))).'"', null)
        // Updated on: 22-04-2019
        ->where('emp_cards.is_default', 1)
        ->join('users','users.sid = emp_cards.company_sid','left')
        ->from('emp_cards');
        //
        if($limit != null) $this->db->limit($limit, $start);
        // Recurring payment check
        // must exists in recurring payment table
        // with status 'active'
        $this->db
        ->where('recurring_payments.status', 'active')
        ->join('recurring_payments','recurring_payments.company_sid = emp_cards.company_sid','inner');
        if($is_count) return $this->db->count_all_results();
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj = $records_obj->free_result();
        return $records_arr;
    }
}
?>