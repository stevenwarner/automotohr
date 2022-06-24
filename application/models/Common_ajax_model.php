<?php defined('BASEPATH') || exit('No direct script access allowed');

class Common_ajax_model extends CI_Model{

    //
    function get_email_template_by_code($slug){
        //
        $a = 
        $this->db
        ->select('from_email, from_name, subject, text')
        ->where('template_code', $slug)
        ->get('email_templates');
        //
        $b = $a->row_array();
        $a->free_result();
        //
        return $b;
    }

    //
    function get_user_detail($userId, $userType){
        //
        if($userType == 'applicant'){
            $this->db->select('sid, first_name, last_name, email, employer_sid AS parent_sid, desired_job_title AS job_title, "applicant" as user_type');
            $this->db->from('portal_job_applications');
        } else{
            $this->db->select('sid, first_name, last_name, email, parent_sid, job_title, "employee" as user_type');
            $this->db->from('users');
        }
        //
        $a = 
        $this->db
        ->where('sid', $userId)
        ->get();
        //
        $b = $a->row_array();
        $a->free_result();
        //
        return $b;
    }

    //
    function send_reminder_email_record($data){
        // Let's check and insert to actual log
        $a = $this->db
        ->select('sid')
        ->where('user_sid', $data['userId'])
        ->where('user_type', $data['userType'])
        ->where('module_type', $data['moduleType'])
        ->get('reminder_emails');
        //
        $b = $a->row_array();
        $a->free_result();
        //
        $sid = 0;
        //
        $date_time = date('Y-m-d H:i:s', strtotime('now'));
        //
        if(!empty($b)){ // Means we need to update it
            //
            $sid = $b['sid'];
            //
            $this->db
            ->where('sid', $sid)
            ->update('reminder_emails', [
                'updated_at' =>  $date_time,
                'last_sender_sid' => $data['lastSenderSid']
            ]);
        } else{ // Means we need to add a new record
            //
            $this->db
            ->insert('reminder_emails', [
                'user_sid' => $data['userId'],
                'user_type' => $data['userType'],
                'module_type' => $data['moduleType'],
                'created_at' => $date_time,
                'updated_at' => $date_time,
                'last_sender_sid' => $data['lastSenderSid']
            ]);
            //
            $sid = $this->db->insert_id();
        }
        // Save the log
        $this->db->insert('reminder_emails_log', [
            'reminder_emails_sid' => $sid,
            'sender_id' => $data['lastSenderSid'],
            'note' => $data['note'],
            'created_at' => $date_time
        ]);
        //
        return $sid;
    }

    //
    function get_send_reminder_email_history($userId, $userType){
        //
        $a = $this->db
        ->select('
            reminder_emails.sid,
            reminder_emails.module_type,
            reminder_emails.updated_at,
            users.first_name,
            users.last_name,
            users.job_title,
            users.access_level,
            users.access_level_plus,
            users.pay_plan_flag,
            users.is_executive_admin
        ')
        ->from('reminder_emails')
        ->join('users', 'users.sid = reminder_emails.last_sender_sid')
        ->where('user_sid', $userId)
        ->where('user_type', $userType)
        ->order_by('reminder_emails.updated_at', 'DESC')
        ->get();
        //
        $b = $a->result_array();
        $a->free_result();
        //
        if(empty($b)) {
            return $b;
        }
        //
        $logs = [];
        //
        foreach($b as $log){
            //
            $tmp = [];
            $tmp['module_type'] = $log['module_type'];
            $tmp['updated_at'] = $log['updated_at'];
            $tmp['name'] = ucwords($log['first_name'].' '.$log['last_name']);
            $tmp['role'] = remakeEmployeeName($log, false);
            //
            $a = $this->db
            ->select('
                reminder_emails_log.note,
                reminder_emails_log.created_at,
                users.first_name,
                users.last_name,
                users.job_title,
                users.access_level,
                users.access_level_plus,
                users.pay_plan_flag,
                users.is_executive_admin
            ')
            ->from('reminder_emails_log')
            ->join('users', 'users.sid = reminder_emails_log.sender_id')
            ->order_by('reminder_emails_log.sid', 'DESC')
            ->where('reminder_emails_sid', $log['sid'])
            ->get();
            //
            $c = $a->result_array();
            $a->free_result();
            //
            if(!empty($c)){
                //
                $tmp['logs'] = [];
                //
                foreach($c as $il){
                    $itmp = [];
                    $itmp['name'] = ucwords($il['first_name'].' '.$il['last_name']);
                    $itmp['role'] = remakeEmployeeName($il, false);
                    $itmp['created_at'] = $il['created_at'];
                    $itmp['note'] = $il['note'];
                    //
                    $tmp['logs'][] = $itmp;
                }
            }
            //
            $logs[] = $tmp;
        }
        //
        return $logs;
    }

    //
    function check_if_already_assigned($userId, $userType, $companyId, $type){
        //
        $a =
        $this->db
        ->select('sid')
        ->from('documents_assigned_general')
        ->where('user_sid', $userId)
        ->where('user_type', $userType)
        ->where('company_sid', $companyId)
        ->where('document_type', str_replace(' ', '_', strtolower($type)))
        ->get();
        //
        $b = $a->row_array();
        $a->free_result();
        //
        return !empty($b) ? $b['sid'] : 0;
    }

}