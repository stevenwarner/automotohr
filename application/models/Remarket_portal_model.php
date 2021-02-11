<?php
class Remarket_portal_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function get_all_ahr_companies() {
        $this->db->select('sid, active, is_paid, has_job_approval_rights');
        $this->db->where('parent_sid', 0);
        $result = $this->db->get('users')->result_array();

        if (!empty($result)) {
            $companies = array();
            //
            foreach ($result as $value) {
                $companies[$value['sid']] = $value;
            }
            //
            return $companies;
        } else {
            return array();
        }
        
    }

    public function get_all_ahr_jobs() {
        $this->db->select('sid, user_sid, active, status, organic_feed, expiration_date, approval_status, approval_status_change_datetime'
        );
        $result = $this->db->get('portal_job_listings')->result_array();
        return $result;
    }
}