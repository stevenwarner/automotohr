<?php

class Home_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_home_page_data() {
        return $this->db->get('home_page')->row_array();
    }

    function get_resource_page_data(){
        $this->db->select('*');
        $this->db->where('page_name', 'resources');
        $record_obj = $this->db->get('dynamic_pages');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if(!empty($record_arr)){
            $record_arr = $record_arr[0];
            $dynamic_pages_sid = $record_arr['sid'];
            $this->db->where('dynamic_pages_sid', $dynamic_pages_sid);
            $this->db->where('status', 1);
            $sections_obj = $this->db->get('dynamic_pages_sections');
            $sections_arr = $sections_obj->result_array();
            $sections_obj->free_result();
            $record_arr['sections'] = $sections_arr;
            return $record_arr;
        } else {
            return array();
        }
    }

    /**
     * Update Phone number
     * Created on: 02-08-2019
     * 
     * @param $phonenumber String
     * @param $id          Integer
     * @param $type        String   Optional
     * Default is 'applicant'
     * 'applicant', 'employee'
     * @param $verificationCode          Integer
     * 
     * @return VOID
     */
    function updatePhoneNumber($phonenumber, $id, $type = 'applicant', $verificationCode = ''){
        $table = 'portal_job_applications';
        $column = 'phone_number';
        if($type != 'applicant'){
            $table = 'users';
            $column = 'PhoneNumber';
        }
        //
        $dataArray = array( $column => $phonenumber );
        if($verificationCode != '') $dataArray['verification_code'] = $verificationCode;
        //
        $this->db
        ->where('sid', $id)
        ->update($table, $dataArray);
    }

    /**
     * Verify verifcation code
     * Created on: 02-08-2019
     * 
     * @param $code        String
     * @param $id          Integer
     * @param $type        String   Optional
     * Default is 'applicant'
     * 'applicant', 'employee'
     * 
     * @return Bool
     */
    function verifyAndUpdateCode($code, $id, $type = 'applicant'){
        $table = $type != 'applicant' ? 'users' : 'portal_job_applications';
        $column = 'verification_code';
        //
        $result = $this->db
        ->where('sid', $id)
        ->where('verification_code', $code)
        ->from($table)
        ->limit(1)
        ->get();

        $result_arr = $result->row_array();
        $result = $result->free_result();
        //
        if(!sizeof($result_arr)) return false;

        //
        $this->db
        ->where('sid', $id)
        ->update($table, array( 'verification_code' => 'verified' ));
        return true;
    }

    /**
     * Verify verification code
     * Created on: 02-08-2019
     * 
     * @param $id          Integer
     * @param $type        String   Optional
     * Default is 'applicant'
     * 'applicant', 'employee'
     * 
     * @return Bool
     */
    function verify($id, $type = 'applicant'){
        $table = $type != 'applicant' ? 'users' : 'portal_job_applications';
        //
        $result = $this->db
        ->where('sid', $id)
        ->where('verification_code', 'verified')
        ->from($table)
        ->limit(1)
        ->get();

        $result_arr = $result->row_array();
        $result = $result->free_result();
        //
        if(sizeof($result_arr)) return false;
        return true;
    }

    /**
     * Get company message id
     * Created on: 02-08-2019
     * 
     * @param $companyId Integer
     * 
     * @return String|Bool
     */
    function getCompanyMessageId($companyId){
        //
        $result = $this->db
        ->select('message_service_sid')
        ->from('portal_company_sms_module')
        ->where('company_sid', $companyId)
        ->limit(1)
        ->get();

        $result_arr = $result->row_array();
        $result = $result->free_result();
        //
        if(!sizeof($result_arr)) return false;
        return $result_arr['message_service_sid'];
    }
}
