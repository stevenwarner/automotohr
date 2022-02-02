<?php defined('BASEPATH') || exit('No direct script access allowed');

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
        ->where('is_paid', 1)
        ->where('active', 1)
        ->where('parent_sid', 0)
        ->get('users');
        //
        $b = $a->result_array();
        $a->free_result();
        //
        if(empty($b)){
            return [];
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
        ->where('users.active', 1)
        ->where('users.terminated_status', 0)
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

    //
    function check_table_record_exist($table){
        //
        return $this->db
            ->select('last_id')
            ->get($table, NULL, NULL, FALSE)
            ->row();
    }

    /**
     * 
     * @return array
     */
    function get_records_from_log(){
        $this->db->select('s_count');
        $this->db->from('query_logs_check');
        $this->db->where('module', 'querylog' );
        //
        $result = $this->db->get('',null,null,FALSE)->row();
        //
        if(!$result){
            $this->db->insert("query_logs_check", array('module' =>'querylog','s_count' =>'0'), null, null, FALSE);
        } else{
            $this->db->where('sid > ', $result->s_count);
        }
        //
        $this->db->select("*, date_format(created_at, '%Y-%m-%d') as created_date");
        $this->db->from('query_logs');
        //
        return  $this->db->get('',null,null,FALSE)->result();
    }
    //
    function update_from_log($lastId){
        $this->db->update("query_logs_check", array('s_count' => $lastId), null, null, FALSE);
    }

    //
    function update_last_id($sid){
        //
        $check_existance = $this->db->select('table_name')->get('log_records',  NULL, NULL, FALSE)->row();
        if ($check_existance){
            $this->db
                ->where('table_name', 'query_logs')
                ->update('log_records', ['last_id' => $sid], NULL, NULL, FALSE);
            return true;
        }
        $this->db->insert('log_records',['table_name' => 'query_logs', 'last_id' => $sid, 'created_at' => date('Y-m-d H:i:s', strtotime('now'))], NULL, FALSE);

    }
}
