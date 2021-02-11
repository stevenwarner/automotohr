<?php

class Company_job_share_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_all_admin_companies(){
        $this->db->select('users.sid, users.career_page_type, users.CompanyName, portal_employer.sub_domain');
        $this->db->where('parent_sid', 0);
        $this->db->where('active', 1);
        $this->db->where('is_paid', 1);
        $this->db->where('is_executive_admin', 0);
        $this->db->order_by('sid', 'desc');
        $this->db->join('portal_employer', 'users.sid = portal_employer.user_sid', 'left');
        $record_obj = $this->db->get('users');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    function get_configured_companies($company_sid){

        $this->db->select('linked_company_sid');
        $this->db->where('company_sid',$company_sid);
        $this->db->where('status',1);

        $result = $this->db->get('reassign_candidate_companies')->result_array();
        return $result;
    }

    function add_update_company($data){
        $this->db->select('sid');
        $this->db->where('company_sid',$data['company_sid']);
        $this->db->where('linked_company_sid',$data['linked_company_sid']);
        $result = $this->db->get('reassign_candidate_companies')->result_array();

        if(sizeof($result)>0){
            $id = $result[0]['sid'];
            $this->db->where('sid',$id);
            $this->db->update('reassign_candidate_companies',$data);
        }
        else{
            $this->db->insert('reassign_candidate_companies',$data);
            $id = $this->db->insert_id();
        }
        return $id;
    }

}