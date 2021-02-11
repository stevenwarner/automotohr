<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Public_holidays_model extends CI_Model{

    function __construct(){
        parent::__construct();
    }

    function checkAndInsertHoliday($d){
        $sid = $this->checkIfHolidayExists($d['holiday_year'], $d['holiday_title']);
        //
        if($sid == 0){
            $this->db->insert('timeoff_holiday_list', $d);
            return $this->db->insert_id();
        }
        //
        $this->db
        ->where('sid', $sid)
        ->update('timeoff_holiday_list', $d);
        return $sid;
    }

    //
    function checkIfHolidayExists($year, $name){
        $a =  $this->db
        ->select("sid")
        ->where("holiday_year", $year)
        ->where("LOWER(holiday_title)", strtolower($name))
        ->get('timeoff_holiday_list');
        //
        $b = $a->row_array();
        $a = $a->free_result();
        //
        return sizeof($b) ? $b['sid'] : 0;
    }

}