<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Email_templates_model extends CI_Model
{

    function get_all_templates($group)
    {
        $this->db->select('*');
        $this->db->where('group', $group);
        $this->db->from('email_templates');
        $this->db->order_by('sid', 'DESC');
        return $this->db->get()->result_array();
    }

    function get_all_templates_groups()
    {
        $this->db->distinct();
        $this->db->select('group');
        $this->db->group_by('group');
        $this->db->from('email_templates');
        return $this->db->get()->result_array();
    }

    public function get_all_email_templates()
    {
        $this->db->select('sid, template_name');
        $this->db->where('template_code', 'super_admin');
        $this->db->where('company_sid', 1);
        $records_obj = $this->db->get('portal_email_templates');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        $return_data = array();

        if (!empty($records_arr)) {
            $return_data = $records_arr;
        }

        return $return_data;
    }

    public function insert_new_email_templates($data_to_insert)
    {
        $this->db->insert('portal_email_templates', $data_to_insert);
    }

    public function get_email_template($sid)
    {
        $this->db->select('template_name, message_body');
        $this->db->where('template_code', 'super_admin');
        $this->db->where('company_sid', 1);
        $this->db->where('sid', $sid);
        $record_obj = $this->db->get('portal_email_templates');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        $return_data = array();

        if (!empty($record_arr)) {
            $return_data = $record_arr[0];
        }

        return $return_data;
    }

    public function update_email_templates($sid, $data_to_update)
    {
        $this->db->where('sid', $sid);
        $this->db->where('template_code', 'super_admin');
        $this->db->where('company_sid', 1);
        $this->db->update('portal_email_templates', $data_to_update);
    }

    public function save_template($data)
    {
        $this->db->insert('email_templates', $data);
        $this->db->affected_rows() != 1 ? $this->session->set_flashdata('message', '<b>Failed: </b>Could not add new template, Please try Again!') : $this->session->set_flashdata('message', '<b>Success: </b>New Email template added to system');
        return $this->db->insert_id();
    }

    public function get_template()
    {
        $data = $this->db->get('email_templates');
        return $data->result_object();
    }

    public function get_template_data($sid)
    {
        $this->db->query("SET NAMES 'utf8mb4'");

        $this->db->select('*');
        $this->db->where('sid', $sid);
        $this->db->from('email_templates');
        return $this->db->get()->result_array();
    }

    public function update_email_template($sid, $data)
    {
        $this->db->where('sid', $sid);
        $result = $this->db->update('email_templates', $data);
        (!$result) ? $this->session->set_flashdata('message', '<b>Error: </b>Update Failed, Please try Again!') : $this->session->set_flashdata('message', '<b>Success: </b>Email Template updated successfully');
        return true;
    }

    public function delete_template($del_id)
    {
        $this->db->where('sid', $del_id);
        $this->db->delete('email_templates');
    }

    public function delete_image($del_id)
    {
        $data = array('file' => '');
        $this->db->where('sid', $del_id);
        $this->db->update('email_templates', $data);
    }
}