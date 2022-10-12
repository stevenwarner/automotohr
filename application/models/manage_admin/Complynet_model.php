<?php
class Complynet_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    //
    function get_all_companies($active = 1)
    {
        $this->db->select('sid, CompanyName,complynet_status');
        $this->db->where('parent_sid', 0);
        $this->db->where('active', $active);
        $this->db->where('is_paid', 1);
        $this->db->where('career_page_type', 'standard_career_site');
        $this->db->order_by('CompanyName', 'ASC');
        $this->db->from('users');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }
    //
    function mapcompany($data)
    {
        $this->db->insert('complynet_companies', $data);
    }

    //
    public function get_complynet_maped_companies($limit = null, $offset = null, $count_only = false)
    {
        $this->db->select('*');
        $this->db->from('complynet_companies');
        $this->db->order_by('sid', 'DESC');

        if ($count_only == true) {
            return $this->db->count_all_results();
        } else {

            if ($limit !== null && $offset !== null) {
                $this->db->limit($limit, $offset);
            }

            $records_obj = $this->db->get();
            $records_arr = $records_obj->result_array();
            $records_obj->free_result();

            return $records_arr;
        }
    }



    //
    function update_complynet_status($automotohr_sid, $complynet_status)
    {

        $data = array('complynet_status' => $complynet_status);
        $this->db->where('sid', $automotohr_sid);
        $this->db->update('users', $data);
        //
        $data = array('status' => $complynet_status, 'updated_at' => date('Y-m-d H:i:s'));
        $this->db->where('automotohr_sid', $automotohr_sid);
        $this->db->update('complynet_companies', $data);
    }
}
