<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Resources_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function getLatestBlogs($limit = null, $start = null)
    {
        //
        $this->db->select("title, slug, description, feature_image");
        $this->db->where('status', 1);
        //
        if ($limit != null) {
            $this->db->limit($limit, $start);
        }
        //
        $this->db->order_by("sort_order", "Asc");
        $result = $this->db->get('cms_resources')->result_array();
        //
        if (!empty($result)) {
            return $result;
        } else {
            return array();
        }
    }

    public function getResources($limit = null, $start = null, $keywords = "", $category = '')
    {
        //
        $this->db->select("title, slug, description, resources, resource_type, feature_image");
        $this->db->where('status', 1);
        //
        if ($category || $keywords) {
            $this->db->group_start();
        }
        //
        if ($category) {
            $categoryList = explode(",", $category);
            foreach ($categoryList as $value) {

                $this->db->or_where("FIND_IN_SET('" . ($value) . "', resource_type) > 0", null, null);
            }
        }
        //
        if ($keywords) {
            $this->db->or_where("title LIKE '%$keywords%'");
            $this->db->or_where("description LIKE '%$keywords%'");
        }
        if ($category || $keywords) {
            $this->db->group_end();
        }
        //
        if ($limit != null) {
            $this->db->limit($limit, $start);
        }
        //
        $this->db->order_by("sort_order", "asc");
        $result = $this->db->get('cms_resources')->result_array();

        //
        if (!empty($result)) {
            return $result;
        } else {
            return array();
        }
    }

    public function checkSubscriberAlreadyExist($email)
    {
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

    public function addSubscriber($dataToInsert)
    {
        $this->db->insert('cms_subscribers', $dataToInsert);
    }

    function updateSubscriber($dataToUpdate, $email)
    {
        $this->db->where('email', $email)
            ->update('cms_subscribers', $dataToUpdate);
    }




    public function getBlogDetail($slug)
    {
        //
        $this->db->select("title, slug, description, feature_image,	resources,meta_title,meta_description,meta_key_word,created_at,resource_type");
        //
        $this->db->where('slug', $slug);

        $result = $this->db->get('cms_resources')->row_array();
        //
        if (!empty($result)) {
            return $result;
        } else {
            return array();
        }
    }
}
