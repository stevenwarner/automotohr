<?php

class copy_policies_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    function getAllCompanies($active = 1) {
        $result = $this->db
        ->select('sid as company_id, CompanyName as title')
        ->where('parent_sid', 0)
        ->where('active', $active)
        ->where('is_paid', 1)
        ->where('career_page_type', 'standard_career_site')
        ->order_by('CompanyName', 'ASC')
        ->get('users');
        //
        $companies = $result->result_array();
        $result = $result->free_result();
        //
        return $companies;
    }


    function getCompanyPolicies($formpost, $limit){
        $start = $formpost['page'] == 1 ? 0 : ($formpost['page'] * $limit) - $limit;
        $this->db
        ->select('
            timeoff_policies.sid as policie_id, 
            timeoff_policies.title, 
            timeoff_policies.is_archived,
            timeoff_category_list.category_name as category,
            "policy" as type
        ')
        ->join('timeoff_categories', 'timeoff_categories.sid = timeoff_policies.type_sid', 'inner')
        ->join('timeoff_category_list', 'timeoff_category_list.sid = timeoff_categories.timeoff_category_list_sid', 'inner')
        ->where('timeoff_policies.company_sid', $formpost['fromCompanyId'])
        ->order_by('timeoff_policies.title')
        ->limit($limit, $start);

        if($formpost['status'] != 'all') $this->db->where('timeoff_policies.is_archived', $formpost['status']);

        $result = $this->db->get('timeoff_policies');
        //
        $policies = $result->result_array();
        $result = $result->free_result();
        //
        $returnArray = array('policies' => $policies, 'PoliciesCount' => 0);
        if($formpost['page'] == 1 && !sizeof($policies)) return $returnArray;
        if($formpost['page'] != 1) return $returnArray;
        //
        $this->db
        ->where('company_sid', $formpost['fromCompanyId'])
        ->order_by('title');
        if($formpost['status'] != 'all') $this->db->where('is_archived', $formpost['status']);
        $returnArray['PoliciesCount'] = $this->db->count_all_results('timeoff_policies');
        //
        return $returnArray;
    }

    function checkPolicyCopied($formpost){
        return $this->db
        ->where('from_company_sid', $formpost['fromCompanyId'])
        ->where('to_company_sid', $formpost['toCompanyId'])
        ->where('policy_sid', $formpost['policy']['policy_id'])
        ->where('policy_category', $formpost['policy']['policy_category'])
        ->count_all_results('copy_timeoff_policy_track');
    }

    function movePolicy($formpost, $id){
        // Fetch document details
        $result = $this->db
        ->select('*')
        ->where('sid', $formpost['policy']['policy_id'])
        ->get('timeoff_policies');
        //
        $policy = $result->row_array();
        $result = $result->free_result();
        //
        if(!sizeof($policy)) return false;
        //
        unset(
            $policy['sid'],
            $policy['created_at'],
            $policy['updated_at']
        );
        //
        $this->db->trans_begin();
        //
        $policy['creator_sid'] = 0;
        $policy['assigned_employees'] = 0;
        $policy['company_sid'] = $formpost['toCompanyId'];
        //
        $this->db->insert('timeoff_policies', $policy);
        $policyId = $this->db->insert_id();
        //
        if(!$policyId) return false;
        //
        $insertArray = array();
        $insertArray['admin_sid'] = $id;
        $insertArray['policy_sid'] = $formpost['policy']['policy_id'];
        $insertArray['to_company_sid'] = $formpost['toCompanyId'];
        $insertArray['new_policy_sid'] = $policyId;
        $insertArray['from_company_sid'] = $formpost['fromCompanyId'];
        $insertArray['policy_category'] = $formpost['policy']['policy_category'];
        //
        $this->db->insert('copy_timeoff_policy_track', $insertArray);
        $historyInsertId = $this->db->insert_id();

        if(!$historyInsertId) {
            $this->db->trans_rollback();
            return false;
        }
        else $this->db->trans_commit();
        return true;
    }
}
