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

  //

  function get_helpbox_info($company_sid)
  {
      $this->db->where('company_id', $company_sid);
      $records_obj = $this->db->get('helpbox_info_for_company');
      $records_arr = $records_obj->result_array();
      $records_obj->free_result();
      return $records_arr;
  }

  //   
  
  function add_update_helpbox_info($company_sid, $helpboxTitle, $helpboxEmail, $helpboxPhoneNumber, $helpboxStatus)
  {
      $dataToInsert = array();
      $dataToInsert['company_id'] = $company_sid;
      $dataToInsert['box_title'] = $helpboxTitle;
      $dataToInsert['box_support_email'] = $helpboxEmail;
      $dataToInsert['box_support_phone_number'] = $helpboxPhoneNumber;
      $dataToInsert['box_status'] = $helpboxStatus;
      $this->db->where('company_id', $company_sid);
      $result = $this->db->get('helpbox_info_for_company')->num_rows();

      if ($result > 0) {
          $this->db->where('company_id', $company_sid);
          $this->db->update('helpbox_info_for_company', $dataToInsert);
      } else {
          $this->db->insert('helpbox_info_for_company', $dataToInsert);
      }
  }



}