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
            ->where('is_deleted', 0)
            ->order_by('sid', 'ASC')
            ->get('cms_sliders')
            ->result_array();
    }


    //
    public function add_slider($data)
    {
        $this->db->insert('cms_sliders', $data);
    }

    //
    public function delete_slider($sid, $data)
    {
        $this->db->where('sid', $sid);
        $this->db->update('cms_sliders', $data);
    }


    public function getSliderById($sid)
    {
        return $this->db
            ->select('*')
            ->where('sid', $sid)
            ->order_by('sid', 'ASC')
            ->get('cms_sliders')
            ->row_array();
    }

    //
    public function update_slider($sid, $data)
    {
        $this->db->where('sid', $sid);
        $this->db->update('cms_sliders', $data);
    }

    //
    public function getSectionsByPageId($sid)
    {
        return $this->db
            ->select('*')
            ->where('page_id', $sid)
            ->where('is_deleted', 0)
            ->order_by('sid', 'ASC')
            ->get('cms_sections')
            ->result_array();
    }

    //
    public function add_section($data)
    {
        $this->db->insert('cms_sections', $data);
    }


    //
    public function delete_section($sid, $data)
    {
        $this->db->where('sid', $sid);
        $this->db->update('cms_sections', $data);
    }


    public function getSectionById($sid)
    {
        return $this->db
            ->select('*')
            ->where('sid', $sid)
            ->order_by('sid', 'ASC')
            ->get('cms_sections')
            ->row_array();
    }

    //
    public function update_section($sid, $data)
    {
        $this->db->where('sid', $sid);
        $this->db->update('cms_sections', $data);
    }

}
