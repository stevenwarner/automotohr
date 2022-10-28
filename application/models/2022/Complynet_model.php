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
    public function checkOrGetData(
        $columns = ['*'],
        $whereArray = [],
        $method = 'row_array',
        $table
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
        $obj = $this->db->get($table);
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
    function updateData($columns, $whereArray, $table)
    {
        $this->db->set($columns);
        $this->db->where($whereArray);
        $this->db->update($table);
    }
}
