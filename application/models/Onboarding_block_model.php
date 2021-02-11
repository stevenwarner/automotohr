<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Onboarding_block_model extends CI_Model
{
    public function insert_data_into_DB($data_to_insert)
    {

        $this->db->insert('portal_onboarding_help_block', $data_to_insert);
        return $this->db->insert_id();
    }
    public function check_companySid($company_sid)
    {
        $this->db->select('sid,block_title,phone_number,email_address,description,is_active');
        $this->db->where('company_sid', $company_sid);
        $query = $this->db->get('portal_onboarding_help_block');
        return $query->row_array();

    }
    public function update_data_into_DB($data_to_insert, $company_sid)
    {
        $this->db->where('company_sid', $company_sid);
        $this->db->update('portal_onboarding_help_block', $data_to_insert);
    }
}