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

    //
    function get_all_licenses(){
        $a = 
        $this->db->select('sid')
        // ->where('is_paid', 1)
        ->where('active', 1)
        ->where('parent_sid', 0)
        ->get('users');
        //
        $b = $a->result_array();
        $a->free_result();
        //
        if(empty($b)){
            exit(0);
        }
        //
        $a = 
        $this->db
        ->select('
            users.first_name,
            users.last_name,
            users.email,
            users.sid,
            company.CompanyName,
            company.sid as CompanyId,
            company.Location_Address,
            company.PhoneNumber,
            license_information.sid as licenseId,
            license_information.license_type,
            license_information.license_details
        ')
        ->from('license_information')
        ->join('users', 'users.sid = license_information.users_sid')
        ->join('users as company', 'company.sid = users.parent_sid')
        ->where_in('users.parent_sid', array_column($b, 'sid'))
        ->where('license_information.users_type', 'employee')
        ->group_start()
        ->where('license_information.last_notification_sent_at IS NULL', NULL, NULL)
        ->or_where('license_information.last_notification_sent_at <>', date('Y-m-d', strtotime('now')))
        ->group_end()
        ->get();
        //
        $b = $a->result_array();
        $a->free_result();
        //
        if(empty($b)){
            exit(0);
        }
        //
        foreach($b as $key => $il){
            //
            $il['license_details'] = @preg_replace_callback('!s:(\d+):"(.*?)";!', function($x){
                return "s:".strlen($x[2]).":\"".$x[2]."\";";
            }, $il['license_details']);
            //
            $b[$key]['license_details'] = unserialize($il['license_details']);
        }
        //
        return $b;
    }

    //
    function update_license_last_sent_date($id){
        $this->db
        ->where('sid', $id)
        ->update('license_information', [
            'last_notification_sent_at' => date('Y-m-d', strtotime('now'))
        ]);
    }
}
