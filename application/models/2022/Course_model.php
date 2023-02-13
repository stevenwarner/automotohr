<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Company model
 * 
 * Holds all the company interactions
 * 
 * @author  AutomotoHR <www.automotohr.com>
 * @author  Mubashir Ahmed <mubashar@automotohr.com>
 * @version 1.0 
 * 
 */
class Course_model extends CI_Model {

    /**
     * Entry point
     */
    function __construct() {
        // Inherit parent class properties and methods
        parent::__construct();
    }


    /**
     * Get all active companies
     * 
     * @param array  $columns
     * @param array  $where
     * @param string $method
     * @param array  $orderBy
     * @return array
     */
    public function getAllCompanies(
        $columns = ['*'],
        $where = [],
        $method = 'result_array',
        $orderBy = ['CompanyName', 'ASC']
    ){
        // Set the default where clause
        if(!$where){
            $where['parent_sid'] = 0;
            $where['active'] = 
            $where['is_paid'] = 1;
            $where['career_page_type'] = 'standard_career_site';
        }
        //
        $this->db
        ->select($columns)
        ->where($where)
        ->from('users')
        ->order_by($orderBy[0], $orderBy[1]);
        // Execute the query
        $obj = $this->db->get();
        // Get he data
        $results = $obj->$method();
        // Free up the memory
        $obj = $obj->free_result();
        //
        return $results ?: [];
    }

    /**
     * Add company data to table
     * 
     * @param string $table
     * @param array $insertArray
     * 
     * @return int
     */
    public function addData(
        string $table, 
        array $insertArray
    ){
        //
        $this->db->insert($table, $insertArray);
        //
        return $this->db->insert_id();
    }

    public function getAllActiveEmployees ($companySid) {
        $this->db->select('
            sid, 
            first_name, 
            middle_name, 
            last_name, 
            access_level, 
            timezone, 
            access_level_plus, 
            is_executive_admin, 
            pay_plan_flag, 
            department_sid, 
            profile_picture'
        );
        
        $this->db->where('is_executive_admin', 0);
        $this->db->where('parent_sid', $companySid);
        $this->db->where('active', 1);
        $this->db->where('terminated_status', 0);
        $records_obj = $this->db->get('users');
        //
        if (!empty($records_obj)) {
            $data = $records_obj->result_array();
            $records_obj->free_result();
            return $data;
        } else {
            return array();
        }
        //
    }

    public function getAllDepartments ($companySid) {
        //
        $this->db->select('
            sid, 
            name
        ');
        $this->db->where('company_sid', $companySid);
        $records_obj = $this->db->get('departments_management');
        //
        if (!empty($records_obj)) {
            $data = $records_obj->result_array();
            $records_obj->free_result();
            return $data;
        } else {
            return array();
        }        
    }

    public function getAllJobTitles ($companySid) {
        //
        $this->db->select('
            distinct(job_title)
        ');
        $this->db->where('parent_sid', $companySid);
        $records_obj = $this->db->get('users');
        //
        if (!empty($records_obj)) {
            $data = $records_obj->result_array();
            $records_obj->free_result();
            //
            $result_array = array();
            //
            foreach ($data as $row) {
                if (!empty($row['job_title'])) {
                    $jobTitle = strtolower($row['job_title']);
                    $value =  ucwords($jobTitle);
                    $key = str_replace(" ", "_", $jobTitle);
                    $result_array[$key] = $value;
                }
            }
            //
            return $result_array;
        } else {
            return array();
        }        
    }
}
