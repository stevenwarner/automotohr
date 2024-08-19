<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Schedule model
 * 
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @version 1.0
 * @package Scheduling
 */
class Ledger_model extends CI_Model
{
    /**
     * Main entry point
     */
    public function __construct()
    {
        // inherit parent
        parent::__construct();
    }

    function getEmployerDetail($id) {
        $this->db->where('sid', $id);
        return $this->db->get('users')->row_array();
    }

    function checkEmployeeExistInCompany ($data, $companyId) {
        //
        $columnName = "";
        $columnValue = "";
        //
        if ($data['employee_id']) {
            $columnName = "sid";
            $columnValue = $data['employee_id'];
        } else if ($data['employee_number']) {
            $columnName = "employee_number";
            $columnValue = $data['employee_number'];
        } else if ($data['employee_ssn']) {
            $columnName = 'REGEXP_REPLACE(ssn, "[^0-9]", "") =';
            $columnValue = preg_replace('/[^0-9]/', '',$data['employee_ssn']);
        } else if ($data['employee_email']) {
            $columnName = 'LOWER(email)';
            $columnValue = $data['employee_email'];
        } else if ($data['employee_phone']) {
            $columnName = 'PhoneNumber';
            $columnValue =$data['employee_phone'];
        }
      
        //
        if ($columnName &&  $columnValue) {
            //
            $columnValue = trim(strtolower($columnValue));
            //
            $this->db->select("sid");
            $this->db->where('parent_sid', $companyId);
            //
            if ($columnName == 'PhoneNumber') {
                //
                $phoneNumber = preg_replace('/[^0-9]/', '',$data['employee_phone']);
                //
                $this->db->group_start();
                $this->db->where('REGEXP_REPLACE(PhoneNumber,"[^0-9]","")', $phoneNumber);
                //
                if (substr(preg_replace('/[^0-9]/', '',$data['employee_phone']),0,1) != 1) {
                    $this->db->or_where('REGEXP_REPLACE(PhoneNumber,"[^0-9]","")', '1' . $phoneNumber);
                    $this->db->or_where('REGEXP_REPLACE(PhoneNumber,"[^0-9+]","")', '+1' . $phoneNumber);
                } else {
                    $this->db->or_where('REGEXP_REPLACE(PhoneNumber,"[^0-9+]","")', '+' . $phoneNumber);
                    $this->db->or_where('REGEXP_REPLACE(PhoneNumber,"[^0-9]","")', substr($phoneNumber,1));
                }
                //
                $this->db->group_end();
            } else {
                $this->db->where($columnName, $columnValue);
            }
            
            $this->db->limit(1);
            //
            $result = $this->db->get('users')->row_array();
            
            return $result['sid'] ?? 0;
        } 
        //
        return 0;
        //
    }

    public function insertLedgerInfo ($data) {
        $this->db->insert('payrolls.payroll_ledger', $data);
    }
}
