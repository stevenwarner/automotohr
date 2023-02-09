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

}
