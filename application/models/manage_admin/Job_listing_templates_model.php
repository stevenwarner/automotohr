<?php

class job_listing_templates_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function InsertTemplate($sid, $title, $description, $requirements, $sortOrder) {
        $data = array(
            'title' => $title,
            'description' => $description,
            'requirements' => $requirements,
            'archive_status' => 'active',
            'status' => 1,
            'sort_order' => $sortOrder
        );

        $this->db->insert('portal_job_listing_templates', $data);
    }

    public function UpdateTemplate($sid, $title, $description, $requirements, $sortOrder) {
        $data = array(
            'title' => $title,
            'description' => $description,
            'requirements' => $requirements,
            'sort_order' => $sortOrder
        );

        $this->db->where('sid', $sid);
        $this->db->update('portal_job_listing_templates', $data);
    }

    public function SaveTemplate($sid, $title, $description, $requirements, $sortOrder) {
        if ($sid == null) {
            $this->InsertTemplate($sid, $title, $description, $requirements, $sortOrder);
        } else {
            $this->UpdateTemplate($sid, $title, $description, $requirements, $sortOrder);
        }
    }

    public function SetStatusTemplate($sid, $status = 0) {
        $data = array(
            'status' => $status
        );

        $this->db->where('sid', $sid);
        $this->db->update('portal_job_listing_templates', $data);
    }

    public function SetArchiveStatusTemplate($sid, $status = 'active') {
        $data = array(
            'archive_status' => $status
        );

        $this->db->where('sid', $sid);
        $this->db->update('portal_job_listing_templates', $data);
    }

    public function GetAllActiveTemplates() {
        //$this->db->where('status', 1);
        $this->db->where('archive_status', 'active');
        $this->db->order_by('sort_order', 'ASC');
        return $this->db->get('portal_job_listing_templates')->result_array();
    }

    public function GetTemplate($sid) {
        $this->db->where('sid', $sid);
        $this->db->where('status', 1);
        $this->db->where('archive_status', 'active');
        $return = $this->db->get('portal_job_listing_templates')->result_array();

        if (!empty($return)) {
            return $return;
        } else {
            $template = array(
                'sid' => '',
                'available_for_companies' => '',
                'title' => '',
                'description' => '',
                'requirements' => '',
                'status' => 0,
                'archive_status' => ''
            );

            return $template;
        }
    }

    //Groups Related
    public function InsertGroup($sid, $name, $description, $status, $templates = array()) {
        $data = array(
            'name' => $name,
            'status' => $status,
            'description' => $description,
            'templates' => serialize($templates)
        );

        $this->db->insert('portal_job_listing_template_groups', $data);
    }

    public function UpdateGroup($sid, $name, $description, $status, $templates = array()) {
        $data = array(
            'name' => $name,
            'description' => $description,
            'status' => $status,
            'templates' => serialize($templates)
        );

        $this->db->where('sid', $sid);
        $this->db->update('portal_job_listing_template_groups', $data);
    }

    public function SaveGroup($sid, $name, $description, $status, $templates = array()) {
        if ($sid == null) {
            $this->InsertGroup($sid, $name, $description, $status, $templates);
        } else {
            $this->UpdateGroup($sid, $name, $description, $status, $templates);
        }
    }

    public function SetStatusGroup($sid, $status = 0) {
        $data = array(
            'status' => $status
        );

        $this->db->where('sid', $sid);
        $this->db->update('portal_job_listing_template_groups', $data);
    }

    public function SetArchiveStatusGroup($sid, $status = 'active') {
        $data = array(
            'archive_status' => $status
        );

        $this->db->where('sid', $sid);
        $this->db->update('portal_job_listing_template_groups', $data);
    }

    public function GetAllActiveGroups() {
        $this->db->where('archive_status', 'active');

        return $this->db->get('portal_job_listing_template_groups')->result_array();
    }

    public function GetGroup($sid) {
        $this->db->where('sid', $sid);
//        $this->db->where('status', 1);
        $this->db->where('archive_status', 'active');
        $return = $this->db->get('portal_job_listing_template_groups')->result_array();

        if (!empty($return)) {
            return $return;
        } else {
            $group = array(
                'sid' => '',
                'name' => '',
                'description' => '',
                'templates' => '',
                'status' => 0,
                'archive_status' => 'active'
            );

            return $group;
        }
    }

    //Comapnies Related
    public function GetAllCompanies() {
        $this->db->where('parent_sid', 0);
        $this->db->where('active', 1);
        $this->db->where('career_page_type', 'standard_career_site');
        $return = $this->db->get('users')->result_array();
        return $return;
    }

}
