<?php

/**
 * Created by PhpStorm.
 * User: Hamid Ashraf
 * Date: 2/26/2016
 * Time: 8:43 AM
 */
class themes_pages_model extends CI_Model {
    public $tableName = 'portal_themes_pages';

    function __construct() {
        parent::__construct();
    }

    public function Insert($company_id, $theme_name, $page_name, $page_title, $page_content, $page_status, $job_opportunities, $job_opportunities_text, $job_fair, $job_fair_page_url) {
        $data = array(
            'company_id' => $company_id,
            'theme_name' => $theme_name,
            'page_name' => $page_name,
            'page_title' => htmlentities($page_title),
            'page_content' => htmlentities($page_content),
            'page_status' => $page_status,
            'job_opportunities' => $job_opportunities,
            'job_opportunities_text' => $job_opportunities_text,
            'job_fair_page_url' => $job_fair_page_url
        );

        if ($job_fair != 2) {
            $data['job_fair'] = $job_fair;
        }

        $this->db->insert($this->tableName, $data);
    }

    public function Update($sid, $company_id, $theme_name, $page_name, $page_title, $page_content, $page_status, $job_opportunities, $job_opportunities_text, $job_fair, $job_fair_page_url) {
        $data = array(
            'company_id' => $company_id,
            'theme_name' => $theme_name,
            'page_name' => $page_name,
            'page_title' => htmlentities($page_title),
            'page_content' => htmlentities($page_content),
            'page_status' => $page_status,
            'job_opportunities' => $job_opportunities,
            'job_opportunities_text' => $job_opportunities_text,
            'job_fair_page_url' => $job_fair_page_url
        );

        if ($job_fair != 2) {
            $data['job_fair'] = $job_fair;
        }

        $this->db->where('sid', $sid);
        $this->db->update($this->tableName, $data);
    }

    public function Save($sid, $company_id, $theme_name, $page_name, $page_title, $page_content, $page_status, $job_opportunities, $job_opportunities_text, $job_fair, $job_fair_page_url) {
        if (empty($sid)) {
            $this->Insert($company_id, $theme_name, $page_name, $page_title, $page_content, $page_status, $job_opportunities, $job_opportunities_text, $job_fair, $job_fair_page_url);
        } else {
            $this->Update($sid, $company_id, $theme_name, $page_name, $page_title, $page_content, $page_status, $job_opportunities, $job_opportunities_text, $job_fair, $job_fair_page_url);
        }
    }

    public function Delete($sid) {
        $this->db->where('sid', $sid);
        $this->db->delete($this->tableName);
    }

    public function MarkAsActiveInActive($sid, $ActiveInActive = 1) {
        $data = array();
        
        if ($ActiveInActive == 1) {
            $data = array(
                'page_status' => 1
            );
        } else {
            $data = array(
                'page_status' => 0
            );
        }
        
        $this->db->where('sid', $sid);
        $this->db->update($this->tableName, $data);
    }

    public function GetAll() {
        return $this->db->get($this->tableName)->result_array();
    }

    public function GetAllCompanySpecific($companyId) {
        $this->db->where('company_id', $companyId);
        $myReturn = $this->db->get($this->tableName)->result_array();
        return $myReturn;
    }

    public function GetAllLimitOffset($limit, $offset) {
        return $this->db->get($this->tableName, $limit, $offset)->result_array();
    }

    public function GetSingle($sid) {
        $this->db->where('sid', $sid);
        return $this->db->get($this->tableName)->result_array();
    }

    public function UpdateBannerImage($sid, $page_banner) {
        $data = array(
            'page_banner' => $page_banner
        );

        $this->db->where('sid', $sid);
        $this->db->update($this->tableName, $data);
    }

    public function MarkBannerAsActiveInActive($sid, $ActiveInActive = 1) {
        $data = array();
        
        if ($ActiveInActive == 1) {
            $data = array(
                'page_banner_status' => 1
            );
        } else {
            $data = array(
                'page_banner_status' => 0
            );
        }
        
        $this->db->where('sid', $sid);
        $this->db->update($this->tableName, $data);
    }

    public function UpdateYoutubeVideo($sid, $youtubeVideo, $video_location) {
        $data = array(
            'page_youtube_video' => $youtubeVideo,
            'video_location' => $video_location
        );

        $this->db->where('sid', $sid);
        $this->db->update($this->tableName, $data);
    }

    public function MarkYoutubeVideoAsActiveInActive($sid, $ActiveInActive = 1) {
        $data = array();
        
        if ($ActiveInActive == 1) {
            $data = array(
                'page_youtube_video_status' => 1
            );
        } else {
            $data = array(
                'page_youtube_video_status' => 0
            );
        }
        
        $this->db->where('sid', $sid);
        $this->db->update($this->tableName, $data);
    }

    public function GetAllPagesNameCompanySpecific($company_id, $sid) {
        $this->db->select('page_title,page_name');
        $this->db->where('company_id', $company_id);
        $this->db->where('sid <>', $sid);
        return $this->db->get($this->tableName)->result_array();
    }

}
