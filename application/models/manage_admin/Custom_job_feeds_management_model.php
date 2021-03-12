<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Custom_job_feeds_management_model extends CI_Model
{
    function insert_custom_feed($data){
        $this->db->insert('job_feeds_management', $data);
        return $this->db->insert_id();
    }
    function fetch_feeds_list($is_default, $accept_url){
        $this->db->select('sid,title,status,is_default,accept_url_flag,site,type,url, last_read');
        $this->db->where('is_default',$is_default);
        $this->db->where('accept_url_flag',$accept_url);
        $feed_list = $this->db->get('job_feeds_management')->result_array();
        return $feed_list;
    }
    function fetch_feed_by_id($feed_id){
        $this->db->select('title,status,description,is_default,accept_url_flag,site,type,url,last_read');
        $this->db->where('sid',$feed_id);
        $feed = $this->db->get('job_feeds_management')->result_array();
        return $feed;
    }
    function update_custom_feed($feed_id, $update_data){
        $this->db->where('sid',$feed_id);
        $this->db->update('job_feeds_management',$update_data);
    }
    function get_companies_data($feedSid){
        $a = $this->db
        ->select('sid, CompanyName, "1" as status')
        ->where('parent_sid',0)
        ->where('is_paid',1)
        ->where('active',1)
        ->get('users');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        if(!sizeof($b)) return array();
        //
        foreach ($b as $k => $v) {
            //
            $a = $this->db
            ->select('status')
            ->where('company_sid', $v['sid'])
            ->where('feed_sid', $feedSid)
            ->get('feed_restriction');
            //
            $c = $a->row_array();
            $a = $a->free_result();
            //
            if(!sizeof($c)) continue;
            $b[$k]['status'] = $c['status'];
        }
        return $b;

    }

    //
    function updateFeed($ins, $companySid, $feedSid){
        //
        $a = $this->db
        ->select('status')
        ->where('company_sid', $companySid)
        ->where('feed_sid', $feedSid)
        ->get('feed_restriction');
        //
        $b = $a->row_array();
        $a = $a->free_result();
        //
        if(sizeof($b)){
            // Update
            $this->db
            ->where('company_sid', $companySid)
            ->where('feed_sid', $feedSid)
            ->update('feed_restriction', $ins);
        }else{
            $ins['company_sid'] = $companySid;
            $ins['feed_sid'] = $feedSid;
            $ins['creator_sid'] = 1;
            // Insert
            $this->db
            ->insert('feed_restriction', $ins);
        }
    }

    function getFeedName($feedSid){
        $a = $this->db
        ->where('sid', $feedSid)
        ->get('job_feeds_management');
        //
        $b = $a->row_array();
        $a = $a->free_result();
        //
        return $b;
    }

}
