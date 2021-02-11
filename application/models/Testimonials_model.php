<?php

/**
 * Created by PhpStorm.
 * User: Hamid Ashraf
 * Date: 2/25/2016
 * Time: 9:55 AM
 */
class testimonials_model extends CI_Model
{
    public $tableName = 'portal_testimonials';

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }


    public function Insert($author_name, $designation, $short_description, $type, $status, $company_id, $resource_name, $full_description, $youtube_video_id)
    {
        $data = array(
            'author_name' => $author_name,
            'designation' => $designation,
            'short_description' => $short_description,
            'type' => $type,
            'status' => $status,
            'company_id' => $company_id,
            'resource_name' => $resource_name,
            'full_description' => $full_description,
            'youtube_video_id' => $youtube_video_id
        );

        $this->db->insert($this->tableName, $data);
    }

    public function Update($sid, $author_name, $designation, $short_description, $type, $status, $company_id, $resource_name, $full_description, $youtube_video_id)
    {
        $data = array(
            'author_name' => htmlentities($author_name),
            'designation' => $designation,
            'short_description' => htmlentities($short_description),
            'type' => $type,
            'status' => $status,
            'company_id' => $company_id,
            'resource_name' => $resource_name,
            'full_description' => htmlentities($full_description),
            'youtube_video_id' => $youtube_video_id
        );

        $this->db->where('sid', $sid);

        $this->db->update($this->tableName, $data);
    }

    public function Save($sid, $author_name, $designation, $short_description, $type, $status, $company_id, $resource_name, $full_description, $youtube_video_id)
    {
        if (!empty($sid)) {
            $this->Update($sid, $author_name, $designation, $short_description, $type, $status, $company_id, $resource_name, $full_description, $youtube_video_id);
        } else {
            $this->Insert($author_name, $designation, $short_description, $type, $status, $company_id, $resource_name, $full_description, $youtube_video_id);
        }
    }

    public function Delete($sid)
    {
        $this->db->where('sid', $sid);
        $this->db->delete($this->tableName);
    }

    public function MarkAsActiveInActive($sid, $ActiveInActive = 1)
    {
        $data = array();
        if ($ActiveInActive == 1) {
            $data = array(
                'status' => 1
            );
        } else {
            $data = array(
                'status' => 0
            );
        }
        $this->db->where('sid', $sid);
        $this->db->update($this->tableName, $data);
    }

    public function GetAll(){
        return $this->db->get($this->tableName)->result_array();
    }

    public function GetAllCompanySpecific($companyId){
        $this->db->where('company_id', $companyId);
        $myReturn = $this->db->get($this->tableName)->result_array();
        return $myReturn;
    }

    public function GetAllLimitOffset($limit, $offset){
        return $this->db->get($this->tableName, $limit, $offset)->result_array();
    }

    public function GetSingle($sid){
        $this->db->where('sid', $sid);
        return $this->db->get($this->tableName)->result_array();
    }


}