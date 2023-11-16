<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class cms_model extends CI_Model
{

    //
    public function get_pages_data($limit = null, $offset = null, $count_only = false)
    {
        if ($limit !== null && $offset !== null) {
            $this->db->limit($limit, $offset);
        }

        if ($count_only == true) {
            return $this->db->count_all_results('cms_pages_new');
        } else {
            $query = $this->db
                ->order_by("sid", "ASC")->get('cms_pages_new');
            return $query->result_array();
        }
    }

    //
    public function get_page_data($sid)
    {
        $this->db->where('sid', $sid);
        $query = $this->db->get('cms_pages_new');
        return $query->row_array();
    }

    //
    public function update_page_data($sid, $data)
    {
        $this->db->where('sid', $sid);
        $this->db->update('cms_pages_new', $data);
    }

    public function getPageById(int $pageId): array
    {
        //
        return $this->db
            ->select("content")
            ->where("sid", $pageId)
            ->get("cms_pages_new")
            ->row_array();
    }

    public function updatePage($pageId, $data)
    {
        $this->db->where("sid", $pageId)
            ->update("cms_pages_new", [
                "content" => $data,
                "updated_at" => getSystemDate()
            ]);
    }
}
