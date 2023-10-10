<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Resources_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function getLatestBlogs ($limit = null, $start = null) {
        //
        $this->db->select("title, slug, description, feature_image");
        $this->db->where('status', 1);
        //
        if($limit != null){
            $this->db->limit($limit, $start);
        }
        //
        $this->db->order_by("created_at", "desc");
        $result = $this->db->get('cms_resources')->result_array();
        //
        if (!empty($result)) {
            return $result;
        } else {
            return array();
        }
    }

    public function getResources ($limit = null, $start = null, $keywords = null, $category = '') {
        //
        $this->db->select("title, slug, description, resources, resource_type");
        $this->db->where('status', 1);
        //
        if (!empty($category)) {
            $this->db->where("resource_type LIKE '%$category%'");
        } 
        //    
        if (!empty($keywords)) {
            $this->db->where("title LIKE '%$keywords%'");
        }
        //
        if($limit != null){
        $this->db->limit($limit, $start);
        }
        //
        $this->db->order_by("sid", "asc");
        $result = $this->db->get('cms_resources')->result_array();
        //
        if (!empty($result)) {
            return $result;
        } else {
            return array();
        }
    }

    public function checkSubscriberAlreadyExist ($email) {
        //
        $this->db->where('email', $email);
        //
        $this->db->from('cms_subscribers');
        //
        $count = $this->db->count_all_results();
        //
        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function addSubscriber ($dataToInsert) {
        $this->db->insert('cms_subscribers', $dataToInsert);
    }

    function updateSubscriber ($dataToUpdate, $email) {
        $this->db->where('email', $email)
                ->update('cms_subscribers', $dataToUpdate);
    }
}    