<?php defined('BASEPATH') || exit('No direct script access allowed');

class Pages_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     *
     */
    public function getPages()
    {
        return $this->db
            ->select('*')
            ->order_by('sid', 'ASC')
            ->get('cms_pages')
            ->result_array();
    }

    //
    public function getPageById($sid)
    {
        return $this->db
            ->select('*')
            ->where('sid', $sid)
            ->order_by('sid', 'ASC')
            ->get('cms_pages')
            ->row_array();
    }
    //
    function update_page($sid, $data)
    {
        $this->db->where('sid', $sid);
        $this->db->update('cms_pages', $data);
    }

    //
    public function getSlidersByPageId($sid)
    {
        return $this->db
            ->select('*')
            ->where('page_id', $sid)
            ->order_by('sid', 'ASC')
            ->get('cms_sliders')
            ->result_array();
    }


     //
     function add_slider($data)
     {
         $this->db->insert('cms_sliders', $data);
     }


}
