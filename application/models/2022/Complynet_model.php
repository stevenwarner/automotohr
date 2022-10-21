<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Complynet model
 * 
 * Holds all the company interactions
 * 
 * @author  AutomotoHR <www.automotohr.com>
 * @author  Mubashir Ahmed <mubashar@automotohr.com>
 * @version 1.0 
 * 
 */
class Complynet_model extends CI_Model
{

    /**
     * Entry point
     */
    function __construct()
    {
        // Inherit parent class properties and methods
        parent::__construct();
    }

    /**
     * 
     */
    public function getActiveToken(
        $columns = ['*'],
        $whereArray = [],
        $method = 'row_array'
    ) {
        //
        $this->db
            ->select($columns)
            ->where($whereArray);
        //
        if ($method == 'count_all_results') {
            //
            return $this->db->$method();
        }
        //
        $obj = $this->db->get('complynet_access_token');
        //
        $results = $obj->$method();
        //
        return $results;
    }



    public function insertData(
        $table,
        $dataArray
    ) {
        //
        $this->db->insert($table, $dataArray);
        return $this->db->insert_id();
    }



    //

    public function complaynetCompaniesData($company_sid = null, $status = null, $limit = null, $offset = null, $count_only = false)
    {
        $this->db->select('sid,automotohr_id,complynet_id,automotohr_name,complynet_name,status');
        if (!empty($company_sid)  && $company_sid != 'all') {
            $companyIds = explode(',', $company_sid);
            $this->db->where_in('automotohr_id', $companyIds);
        }


        if ($status=='1' || $status=='0' ) {
            $this->db->where('status', $status);
        }

        $this->db->from('complynet_companies');

        if ($count_only == true) {
            return $this->db->count_all_results();
        } else {

            if ($limit !== null && $offset !== null) {
                $this->db->limit($limit, $offset);
            }

            $records_obj = $this->db->get();
            $records_arr = $records_obj->result_array();
            $records_obj->free_result();

            return $records_arr;
        }
    }




    //
    public function employeeData($company_sid = null, $employee_sid = null, $status = null, $limit = null, $offset = null, $count_only = false)
    {
        //die($employee_sid);

        $this->db->select('users.*,complynet_employees.email as complynet_email, complynet_employees.sid as complynet_sid ,complynet_employees.status as complynet_status');
        $this->db->where('users.parent_sid', $company_sid);

        if ($employee_sid != '') {
            $employeeIds = explode(',', $employee_sid);
            in_array("all", $employeeIds) ? '' : $this->db->where_in('users.sid', $employeeIds);
        }
        if ($status == '1') {
            $this->db->where('complynet_employees.email<>', '');
        }

        if ($status == '0') {
            $this->db->where('complynet_employees.email IS NULL', null, true);
        }

        $this->db->where('users.active', '1');
        $this->db->where('users.terminated_status', 0);
        $this->db->where('users.is_executive_admin', 0);
        $this->db->join('complynet_employees', 'complynet_employees.email = users.email', 'left');

        $this->db->from('users');

        if ($count_only == true) {
            return $this->db->count_all_results();
        } else {

            if ($limit !== null && $offset !== null) {
                $this->db->limit($limit, $offset);
            }

            $records_obj = $this->db->get();
            $records_arr = $records_obj->result_array();
            $records_obj->free_result();
            $qry = $this->db->last_query();
            //   echo $qry;
            //   die();

            return $records_arr;
        }
    }









    //

    function get_active_employees_detail($parent_sid)
    {
        $this->db->select('*');
        $this->db->where('parent_sid', $parent_sid);
        $this->db->where('active', '1');
        $this->db->where('terminated_status', 0);
        //  $this->db->where('archived', $archive);
        $this->db->where('is_executive_admin', 0);
        $this->db->order_by('sid', 'DESC');
        $all_employees = $this->db->get('users')->result_array();
        //   $all_employees = $this->verify_executive_admin_status($all_employees);
        return $all_employees;
    }
}
