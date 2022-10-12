<?php
class Complynet_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    //
    function get_all_companies($active = 1, $company_id = 0)
    {
        $this->db->select('users.sid, users.CompanyName,users.complynet_status');
        $this->db->where('users.parent_sid', 0);
        $this->db->where('users.active', $active);
        $this->db->where('users.is_paid', 1);
        if($company_id!=0){
            $this->db->where('users.sid', $company_id);
        }

        $this->db->where('users.career_page_type', 'standard_career_site');
        //  $this->db->where('users.sid ', 'complynet_companies.automotohr_sid');
        //  $this->db->join('complynet_companies', 'complynet_companies.automotohr_sid = users.sid');
        $this->db->group_by('users.sid');

        $this->db->order_by('users.CompanyName', 'ASC');
        $this->db->from('users');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }
    //
    function mapcompany($data)
    {

        $records_obj = $this->db->select('sid')->from('complynet_companies')
            ->where('automotohr_sid', $data['automotohr_sid'])
            ->or_where('complynet_sid', $data['complynet_sid'])
            ->get()->num_rows();
        if ($records_obj > 0) {
            return 'alradyexist';
        } else {
            $this->db->insert('complynet_companies', $data);
            return 'saved';
        }
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


    //
    public function get_complynet_maped_company($company_id)
    {
        $this->db->select('*');
        $this->db->from('complynet_companies');
        $this->db->where('automotohr_sid', $company_id);
        $this->db->order_by('sid', 'DESC');
        $records_obj = $this->db->get()->row();
        return $records_obj;
    }


//
function get_active_employees_detail($parent_sid) {
    $this->db->select('*');
    $this->db->where('parent_sid', $parent_sid);
    $this->db->where('active', '1');
    $this->db->where('terminated_status', 0);
    $this->db->where('is_executive_admin', 0);
    $this->db->order_by('sid', 'DESC');
    $all_employees = $this->db->get('users')->result_array();
    return $all_employees;
}



}
