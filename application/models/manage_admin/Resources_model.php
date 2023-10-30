<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class resources_model extends CI_Model
{

    //
    public function get_resources($limit = null, $offset = null, $count_only = false)
    {
        if ($limit !== null && $offset !== null) {
            $this->db->limit($limit, $offset);
        }

        if ($count_only == true) {
            $count = $this->db->count_all_results('cms_resources');
            return $count;
        } else {
            $query = $this->dborder_by("sid", "DESC")->get('cms_resources');
            return $query->result_array();
        }
    }


    //
    public function add_resources($data)
    {
        $this->db->insert('cms_resources', $data);
        $insertedId = $this->db->insert_id();
        return $insertedId;
    }

    //
    public function update_resources($sid, $data)
    {
        $this->db->where('sid', $sid);
        $this->db->update('cms_resources', $data);
    }


    public function get_resourcesById($sId)
    {
        $this->db->where('sid', $sId);
        $query = $this->db->get('cms_resources');
        return $query->row_array();
    }

    //
    //
    public function get_subscribers($limit = null, $offset = null, $count_only = false)
    {
        if ($limit !== null && $offset !== null) {
            $this->db->limit($limit, $offset);
        }

        if ($count_only == true) {
            $count = $this->db->count_all_results('cms_subscribers');
            return $count;
        } else {
            $query = $this->db->get('cms_subscribers');
            return $query->result_array();
        }
    }
}
