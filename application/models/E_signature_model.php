<?php

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

        if(!isset($data['is_active'])){
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
}
