<?php

class Document_categories_manager_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }


    function get_all_system_job_categories_through_index($latter = 'a')
    {
        $this->db->select('*');
        $this->db->where('field_sid', 198);
        $this->db->where('field_type', 'system');
        $this->db->like('value', $latter, 'after');
        $this->db->order_by('value', 'ASC');
        $records_obj = $this->db->get('listing_field_list');
        $categories = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($categories)) {
            return $categories;
        } else {
            return array();
        }
    }

    function insert_new_system_job_category($category_name)
    {
        $data_to_insert = array();
        $data_to_insert['field_sid'] = 198;
        $data_to_insert['field_type'] = 'system';
        $data_to_insert['value'] = $category_name;
        $this->db->insert('listing_field_list', $data_to_insert);
    }


    function get_all_system_job_categories($limit = 0, $offset = 0)
    {
        $this->db->select('*');
        $this->db->where('field_sid', 198);
        $this->db->where('field_type', 'system');

        if ($limit > 0 && $offset >= 0) {
            $this->db->limit($limit, $offset);
        }

        $this->db->order_by('value', 'ASC');
        $records_obj = $this->db->get('listing_field_list');
        $categories = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($categories)) {
            return $categories;
        } else {
            return array();
        }
    }


    function get_system_job_category($category_sid)
    {

        $this->db->select('*');
        $this->db->where('sid', $category_sid);
        $records_obj = $this->db->get('listing_field_list');
        $category_info = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($category_info)) {
            return $category_info[0];
        } else {
            return array();
        }
    }

    function get_job_category_industry($sid)
    {
        $this->db->select('*');
        $this->db->where('sid', $sid);

        $records_obj = $this->db->get('job_category_industries');
        $category_info = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($category_info)) {
            return $category_info[0];
        } else {
            return array();
        }
    }

    function update_system_job_category($category_sid, $category_name)
    {
        $data_to_update = array();
        $data_to_update['value'] = $category_name;
        $this->db->where('sid', $category_sid);
        $this->db->update('listing_field_list', $data_to_update);
    }

    function delete_system_job_category($category_sid)
    {
        $this->db->where('sid', $category_sid);
        $this->db->delete('listing_field_list');
    }

    function get_all_document_category_industries($status = 0)
    {
        $this->db->select('*');

        if ($status > 0) {
            $this->db->where('status', $status);
        }

        $this->db->order_by('LOWER(industry_name)', 'ASC');
        $records_obj = $this->db->get('job_category_industries');
        $category_info = $records_obj->result_array();
        $records_obj->free_result();
        return $category_info;
    }

    function get_job_category_industries($sid)
    {
        $this->db->select('*');
        $this->db->where('sid', $sid);
        $records_obj = $this->db->get('job_category_industries');
        $group = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($group)) {
            return $group[0];
        } else {
            return array();
        }
    }

    function add_job_category_industry($data_row)
    {
        $this->db->insert('job_category_industries', $data_row);
        return $this->db->insert_id();
    }

    function update_job_category_industry($sid, $data_row)
    {
        $this->db->where('sid', $sid);
        $this->db->update('job_category_industries', $data_row);
    }

    function check_if_job_category_exists($company_sid, $category_name)
    {
        $this->db->select('sid');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('value', $category_name);
        $this->db->from('listing_field_list');
        $category_count = $this->db->count_all_results();

        if ($category_count > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    function batch_insert_categories_to_industry($industry_sid, $data, $allow_delete = false)
    {
        if ($allow_delete) {
            $this->db->where('industry_sid', $industry_sid);
            $this->db->delete('categories_2_industry');
        }

        if (!empty($data)) {
            $this->db->insert_batch('categories_2_industry', $data);
        }
    }

    function job_industry_categories($industry_sid, $company_sid)
    {
        $this->db->select('categories_2_industry.category_sid');
        $this->db->select('job_category_industries.industry_name');
        $this->db->select('listing_field_list.*');
        $this->db->join('job_category_industries', 'job_category_industries.sid = categories_2_industry.industry_sid', 'left');
        $this->db->join('listing_field_list', 'listing_field_list.sid = categories_2_industry.category_sid', 'left');
        $this->db->where('categories_2_industry.industry_sid', $industry_sid);
        $this->db->where('listing_field_list.company_sid', $company_sid);

        $records_obj = $this->db->get('categories_2_industry');
        $categories = $records_obj->result_array();
        $records_obj->free_result();
        return $categories;
    }

    function InsertIndustryToDelete($insertArray)
    {
        //
        $this->db->insert('job_category_industries_deleted', $insertArray);
        return $this->db->insert_id();
    }

    function DeleteIndustry($industryId)
    {
        //
        $this->db->where('sid', $industryId)
            ->delete('job_category_industries');
    }


    //--- new --

    function get_all_system_document_categories($limit = 0, $offset = 0)
    {
        //
        if ($limit === null) {
            return $this->db->count_all_results('default_categories');
        }
        if ($limit > 0 && $offset >= 0) {
            $this->db->limit($limit, $offset);
        }
        $this->db->order_by('category_name', 'ASC');
        $this->db->select('sid, category_name, description');
        $records_obj = $this->db->get('default_categories');
        //
        if (!$records_obj) {
            return [];
        }
        $categories = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($categories)) {
            return $categories;
        } else {
            return array();
        }
    }


    function add_category($data)
    {
        $this->db->insert('default_categories', $data);
        return $this->db->insert_id();
    }

    function update_category($sid, $data)
    {
        $this->db->where('sid', $sid);
        $this->db->update('default_categories', $data);
        return $sid;
    }

    function delete_document_category($category_sid)
    {
        $this->db->where('sid', $category_sid);
        $this->db->delete('default_categories');
    }

    function get_all_document_categories_through_index($latter = 'a')
    {
        $this->db->select('*');

        $this->db->like('category_name', $latter, 'after');
        $this->db->order_by('sid', 'ASC');
        $records_obj = $this->db->get('default_categories');
        //
        if (!$records_obj) {
            return [];
        }
        $categories = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($categories)) {
            return $categories;
        } else {
            return array();
        }
    }


    function check_if_document_category_exists($category_name)
    {
        $this->db->where('category_name', $category_name);
        $this->db->from('default_categories');
        return $this->db->count_all_results();
    }

    function check_if_document_category_industry_exists($category_name)
    {
        $this->db->select('sid');
        $this->db->where('industry_name', $category_name);
        $this->db->from('job_category_industries');
        return $this->db->count_all_results();
    }



    function get_docuemnt_category_industries($sid)
    {
        $this->db->select('*');
        $this->db->where('sid', $sid);
        $records_obj = $this->db->get('job_category_industries');
        $group = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($group)) {
            return $group[0];
        } else {
            return array();
        }
    }

    function document_industry_categories($industry_sid, $company_sid)
    {
        $this->db->select('categories_document_industry.category_sid');
        $this->db->select('document_category_industries.industry_name');
        $this->db->select('documents_category_management.*');
        $this->db->join('document_category_industries', 'document_category_industries.sid = categories_document_industry.industry_sid', 'left');
        $this->db->join('documents_category_management', 'documents_category_management.sid = categories_document_industry.category_sid', 'left');
        $this->db->where('categories_document_industry.industry_sid', $industry_sid);
        $this->db->where('documents_category_management.company_sid', $company_sid);

        $records_obj = $this->db->get('categories_document_industry');
        $categories = $records_obj->result_array();
        $records_obj->free_result();
        return $categories;
    }



    function document_batch_insert_categories_to_industry($industry_sid, $data, $allow_delete = false)
    {
        if ($allow_delete) {
            $this->db->where('industry_sid', $industry_sid);
            $this->db->delete('categories_document_industry');
        }

        if (!empty($data)) {
            $this->db->insert_batch('categories_document_industry', $data);
        }
    }


    function getPreselectedIndustryCategories($industry_sid)
    {
        $this->db->select('categories_document_industry.category_sid');
        $this->db->where('categories_document_industry.industry_sid', $industry_sid);

        $records_obj = $this->db->get('categories_document_industry');
        $categories = $records_obj->result_array();
        $records_obj->free_result();
        return $categories;
    }


    //
    function insert_industry_to_categories($data)
    {
        if (!empty($data)) {
            $this->db->insert('categories_document_industry', $data);
        }
    }
    //
    function delete_industry_to_categories($category_sid)
    {
        $this->db->where('category_sid', $category_sid);
        $this->db->delete('categories_document_industry');
    }
   //
   function delete_categories_documents_management($category_sid,$category_name)
   {
       $this->db->where('default_category_sid', $category_sid);
       $this->db->where('name', $category_name);
       $this->db->delete('documents_category_management');
   }


}