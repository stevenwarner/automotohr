<?php defined('BASEPATH') or exit('No direct script access allowed');

class Common_model extends CI_Model {
    
    //
    function startR(){
        //
        $this->db
        ->where('start_date >=', date('Y-m-d', strtotime('now')))
        ->where('end_date >=', date('Y-m-d', strtotime('now')))
        ->update('performance_management_reviewees', ['is_started' => 1]);
    }
    
    //
    function endR(){
        //
        $this->db
        ->where('end_date >=', date('Y-m-d', strtotime('now')))
        ->update('performance_management_reviewees',['is_started' => 0]);
    }
}
