<?php

use Aws\CloudFront\Signer;

class E_signature_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function insert_e_signature($data_to_insert)
    {
        $this->db->insert('e_signatures_data', $data_to_insert);
    }

    public function update_e_signature($user_type, $user_sid, $data_to_update)
    {
        $this->db->where('user_sid', $user_sid);
        $this->db->where('user_type', $user_type);
        $this->db->update('e_signatures_data', $data_to_update);
    }

    public function save_e_signature($user_type, $user_sid, $data)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->set('is_active', 0);
        $this->db->update('e_signatures_data');

        if (!isset($data['user_type'])) {
            $data['user_type'] = $user_type;
        }

        if (!isset($data['user_sid'])) {
            $data['user_sid'] = $user_sid;
        }

        if (!isset($data['is_active'])) {
            $data['is_active'] = 1;
        }
        $this->insert_e_signature($data);
    }

    public function get_signature_record($user_type, $user_sid)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('is_active', 1);
        $this->db->limit(1);

        $record_obj = $this->db->get('e_signatures_data');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
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

            $this->db->insert('executive_signatures_data', $data_to_save);
            return $this->db->insert_id();
        }
    }


    function regenerate_e_signature($user_sid, $data_to_update)
    {
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
}
