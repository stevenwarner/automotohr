<?php

class job_title_templates_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }


    public function InsertTemplate($sid, $title, $complynetJobTitle, $sortOrder, $color_code)
    {
        $data = array(
            'title' => $title,
            'complynet_job_title' => $complynetJobTitle,
            'archive_status' => 'active',
            'status' => 1,
            'sort_order' => $sortOrder,
            "color_code" => $color_code
        );

        $this->db->insert('portal_job_title_templates', $data);
    }

    public function UpdateTemplate($sid, $title, $complynetJobTitle, $sortOrder, $color_code)
    {
        $data = array(
            'title' => $title,
            'complynet_job_title' => $complynetJobTitle,
            'sort_order' => $sortOrder,
            "color_code" => $color_code
        );

        $this->db->where('sid', $sid);
        $this->db->update('portal_job_title_templates', $data);

        // //
        // $data = array(
        //     'job_title' => $title
        // );
        // $this->db->where('job_title_type', $sid);
        // $this->db->update('users', $data);

        // //
        // $data = array(
        //     'desired_job_title' => $title
        // );
        // $this->db->where('job_title_type', $sid);
        // $this->db->update('portal_job_applications', $data);
    }

    public function SaveTemplate($sid, $title, $complynetJobTitle, $sortOrder, $color_code)
    {
        if ($sid == null) {
            $this->InsertTemplate($sid, $title, $complynetJobTitle, $sortOrder, $color_code);
        } else {
            $this->UpdateTemplate($sid, $title, $complynetJobTitle, $sortOrder, $color_code);
        }
    }

    public function SetStatusTemplate($sid, $status = 0)
    {
        $data = array(
            'status' => $status
        );

        $this->db->where('sid', $sid);
        $this->db->update('portal_job_title_templates', $data);
    }

    public function SetArchiveStatusTemplate($sid, $status = 'active')
    {
        $data = array(
            'archive_status' => $status
        );
        //
        if ($status == 'deleted') {
            $data['status'] = 0;
        } else if ($status == 'active') {
            $data['status'] = 1;
        }
        
        //

        $this->db->where('sid', $sid);
        $this->db->update('portal_job_title_templates', $data);
    }

    public function GetAllActiveTemplates()
    {
        //$this->db->where('status', 1);
        $this->db->where('archive_status', 'active');
        $this->db->order_by('sort_order', 'ASC');
        return $this->db->get('portal_job_title_templates')->result_array();
    }

    public function GetTemplate($sid)
    {
        $this->db->where('sid', $sid);
        $this->db->where('status', 1);
        $this->db->where('archive_status', 'active');
        $return = $this->db->get('portal_job_title_templates')->result_array();

        if (!empty($return)) {
            return $return;
        } else {
            $template = array(
                'sid' => '',
                'title' => '',
                'complynet_job_title' => '',
                'status' => 0,
                'archive_status' => ''
            );

            return $template;
        }
    }

    public function GetTemplateById($sid)
    {
        $this->db->where('sid', $sid);
        $return = $this->db->get('portal_job_title_templates')->result_array();

        if (!empty($return)) {
            return $return;
        } else {
            $template = array(
                'sid' => '',
                'title' => '',
                'complynet_job_title' => '',
                'status' => 0,
                'archive_status' => ''
            );

            return $template;
        }
    }

    //Groups Related
    public function InsertGroup($sid, $name, $description, $status, $templates = array())
    {
        $data = array(
            'name' => $name,
            'status' => $status,
            'description' => $description,
            'titles' => serialize($templates)
        );

        $this->db->insert('portal_job_listing_template_groups', $data);
    }

    public function UpdateGroup($sid, $name, $description, $status, $templates = array())
    {
        $data = array(
            'name' => $name,
            'description' => $description,
            'status' => $status,
            'titles' => serialize($templates)
        );

        $this->db->where('sid', $sid);
        $this->db->update('portal_job_listing_template_groups', $data);
    }

    public function SaveGroup($sid, $name, $description, $status, $templates = array())
    {
        if ($sid == null) {
            $this->InsertGroup($sid, $name, $description, $status, $templates);
        } else {
            $this->UpdateGroup($sid, $name, $description, $status, $templates);
        }
    }

    public function SetStatusGroup($sid, $status = 0)
    {
        $data = array(
            'status' => $status
        );

        $this->db->where('sid', $sid);
        $this->db->update('portal_job_listing_template_groups', $data);
    }

    public function SetArchiveStatusGroup($sid, $status = 'active')
    {
        $data = array(
            'archive_status' => $status
        );

        $this->db->where('sid', $sid);
        $this->db->update('portal_job_listing_template_groups', $data);
    }

    public function GetAllActiveGroups()
    {
        $this->db->where('archive_status', 'active');
        $this->db->order_by('name', 'asc');

        return $this->db->get('portal_job_listing_template_groups')->result_array();
    }

    public function GetGroup($sid)
    {
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
                'titles' => '',
                'archive_status' => 'active'
            );

            return $group;
        }
    }

    //Comapnies Related
    public function GetAllCompanies()
    {
        $this->db->where('parent_sid', 0);
        $this->db->where('active', 1);
        $this->db->where('career_page_type', 'standard_career_site');
        $return = $this->db->get('users')->result_array();
        return $return;
    }
}
