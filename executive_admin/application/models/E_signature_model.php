<?php

use Aws\CloudFront\Signer;

class E_signature_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

       
    ///// Nisar 
    function set_e_signature($form_post)
    {

        $signerExist = $this->get_e_signature($form_post['user_sid'], 0);
        $data_to_save = array();

        $data_to_save['user_sid'] = $form_post['user_sid'];
        $data_to_save['ip_address'] = $form_post['ip_address'];
        $data_to_save['user_agent'] =  $form_post['user_agent'];
        $data_to_save['first_name'] = $form_post['first_name'];
        $data_to_save['last_name'] = $form_post['last_name'];
        $data_to_save['email_address'] = $form_post['email_address'];
        $data_to_save['signature_timestamp'] = date('Y-m-d H:i:s');
        $data_to_save['signature'] = $form_post['signature'];
        $data_to_save['init_signature'] = $form_post['init_signature'];
        $data_to_save['signature_hash'] = md5($form_post['signature']);
        $data_to_save['user_consent'] = $form_post['user_consent'] == 1 ? 1 : 0;
        $data_to_save['status'] = 1;
        $data_to_save['signature_bas64_image'] = $form_post['drawn_signature'];
        $data_to_save['init_signature_bas64_image'] = $form_post['drawn_init_signature'];
        $data_to_save['active_signature'] = $form_post['active_signature'];


        if (!empty($signerExist)) {

            $this->regenerate_e_signature($form_post['user_sid'], $data_to_save);
            return 1;
        } else {
            $data_to_save['created_at'] = date('Y-m-d H:i:s');
            $this->db->insert('executive_signatures_data', $data_to_save);
            return $this->db->insert_id();
        }
    }


    function regenerate_e_signature($user_sid, $data_to_update)
    {
        $data_to_update['updated_at'] = date('Y-m-d H:i:s');
        $this->db->where('user_sid', $user_sid);
        $this->db->update('executive_signatures_data', $data_to_update);
    }


    public function get_e_signature($executive_user_sid, $status = 1)
    {

        $this->db->select('*');
        $this->db->where('user_sid', $executive_user_sid);
        if ($status == 1) {
            $this->db->where('status', 1);
        }
        $this->db->from('executive_signatures_data');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }



    public function apply_e_signature($executive_user_sid)
    {
        $executive_user_signature = $this->get_e_signature($executive_user_sid, 1);

        if (empty($executive_user_signature)) {
            return false;
        }
        
        $this->db->select('executive_admin_sid,company_sid,logged_in_sid');
        $this->db->where('executive_admin_sid', $executive_user_sid);
        $this->db->from('executive_user_companies');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        //
        if (empty($records_arr)) {
            return false;
        }
        //
        foreach ($records_arr  as $companies_row) {
            //
            $data_to_save = array();
            $data_to_save['first_name'] = $executive_user_signature['first_name'];
            $data_to_save['last_name'] =  $executive_user_signature['last_name'];
            $data_to_save['email_address'] = $executive_user_signature['email_address'];
            $data_to_save['signature'] = $executive_user_signature['signature'];
            $data_to_save['init_signature'] = $executive_user_signature['init_signature'];
            $data_to_save['signature_hash'] = $executive_user_signature['signature_hash'];
            $data_to_save['signature_timestamp'] = $executive_user_signature['signature_timestamp'];
            $data_to_save['signature_bas64_image'] = $executive_user_signature['signature_bas64_image'];
            $data_to_save['init_signature_bas64_image'] = $executive_user_signature['init_signature_bas64_image'];
            $data_to_save['active_signature'] = $executive_user_signature['active_signature'];
            $data_to_save['ip_address'] = $executive_user_signature['ip_address'];
            $data_to_save['user_agent'] = $executive_user_signature['user_agent'];
            $data_to_save['user_consent'] = $executive_user_signature['user_consent'];
            //
            $whereArray = [
                'company_sid' => $companies_row['company_sid'],
                'user_sid' => $companies_row['logged_in_sid'],
                'user_type' => 'employee'
            ];
            // Check if the e signature is already been assigned
            if($this->db->where($whereArray)->count_all_results('e_signatures_data')){
                //
                $this->db->where($whereArray)->update('e_signatures_data', $data_to_save);
            } else{
                //
                $data_to_save['company_sid'] = $companies_row['company_sid'];
                $data_to_save['user_sid'] = $companies_row['logged_in_sid'];
                $data_to_save['user_type'] = 'employee';
                $data_to_save['is_active'] = 1;
                //
                $this->db->insert('e_signatures_data', $data_to_save);
            }
        }

        return true;
    }
}