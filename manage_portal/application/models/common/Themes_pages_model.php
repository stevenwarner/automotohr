<?php

/**
 * Created by PhpStorm.
 * User: Hamid Ashraf
 * Date: 2/26/2016
 * Time: 8:43 AM
 */

class themes_pages_model extends CI_Model
{
    public $tableName = 'portal_themes_pages';

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    public function GetAll()
    {
        return $this->db->get($this->tableName)->result_array();
    }

    public function GetAllCompanySpecific($companyId)
    {
        $this->db->where('company_id', $companyId);
        $myReturn = $this->db->get($this->tableName)->result_array();
        return $myReturn;
    }

    public function GetAllLimitOffset($limit, $offset)
    {
        return $this->db->get($this->tableName, $limit, $offset)->result_array();
    }

    public function GetSingle($sid)
    {
        $this->db->where('sid', $sid);
        return $this->db->get($this->tableName)->result_array();
    }

    public function UpdateBannerImage($sid, $page_banner)
    {
        $data = array(
            'page_banner' => $page_banner
        );

        $this->db->where('sid', $sid);
        $this->db->update($this->tableName, $data);
    }

    public function GetAllPageNamesAndTitles($companyId)
    {
        $this->db->select('page_name, page_title, page_banner, page_status, page_unique_name, job_opportunities, job_opportunities_text, job_fair');
        $this->db->where('company_id', $companyId);
        $this->db->where('page_status', 1);
        $myReturn = $this->db->get('portal_themes_pages')->result_array();
        return $myReturn;
    }

    public function GetPage($companyId, $pageName)
    {
        $this->db->where('company_id', $companyId);
        $this->db->where('page_name', $pageName);
        $myReturn  = $this->db->get('portal_themes_pages', 1)->result_array();

        if (empty($myReturn)) {
            return array();
        } else {
            return $myReturn[0];
        }
    }

    public function GetAllPagesCompanySpecific($companyId)
    {
        $this->db->where('company_id', $companyId);
        $myReturn = $this->db->get('portal_themes_pages')->result_array();
        return $myReturn;
    }

    function getCustomizeCareerSiteData($company_sid)
    {
        $this->db->select('status, menu, footer, inactive_pages');
        $this->db->where('company_sid', $company_sid);
        $record_obj = $this->db->get('customize_career_site');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        if (isset($record_arr[0])) {
            $record_arr[0]['inactive_pages'] = json_decode($record_arr[0]['inactive_pages']);
            return $record_arr[0];
        } else {
            $record_arr['status'] = 0;
            $record_arr['menu'] = 1;
            $record_arr['footer'] = 1;
            $record_arr['inactive_pages'] = [];
            return $record_arr;
        }
    }

    function get_remarket_company_settings()
    {
        $data['status'] = 0;

        if (isset($_GET["applied_by"]) && $_GET["applied_by"] > 0) {
            $this->db->select('*');
            $this->db->where('sid', $_GET["applied_by"]);
            $record_obj = $this->db->get('portal_applicant_jobs_list');
            $job_list = $record_obj->row_array();
            $record_obj->free_result();

            if ($job_list['job_sid'] > 0 || !empty($job_list['desired_job_title'])) {
                $this->db->select('status');
                $this->db->where('company_sid', $job_list['company_sid']);
                $record_obj = $this->db->get('remarket_company_settings');
                $record_arr = $record_obj->result_array();
                $record_obj->free_result();
                if (isset($record_arr[0])) {
                    $data = $record_arr[0];
                } else {
                    $record_arr['status'] = 0;
                    $data = $record_arr;
                }

                $data['questionnaire'] = $job_list['questionnaire'];
                unset($job_list['questionnaire']);
                $data['talent_and_fair_data'] = $job_list['talent_and_fair_data'];
                unset($job_list['talent_and_fair_data']);
                $data['portal_applicant_jobs_list'] = json_encode($job_list);
                $this->db->select('*');
                $this->db->where('sid', $job_list['portal_job_applications_sid']);
                $record_obj = $this->db->get('portal_job_applications');
                $data['portal_job_applications'] = json_encode($record_obj->row_array());
                $record_obj->free_result();
            }
        }
        return $data;
    }

    //
    public function insert_cookie_log($data)
    {
        $this->db->insert('cookie_log_data', $data);
    }
}
