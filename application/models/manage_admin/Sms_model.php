<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Sms_model extends CI_Model {

    function __construct() { parent::__construct(); }

    /**
     * Get admins
     * Created on: 10-06-2019
     *
     * @param $status Bool Optional (-1 = All, 0 = InActive, 1 = Active)
     * @param $fullname Bool Optional
     * @param $exec Bool Optional
     *
     * @return Bool|Int
     */
    function get_admins($status = -1, $fullname = TRUE, $exec = FALSE){
        $this->db
        ->select('
            '.(
                $fullname ? 'id as admin_id, concat(first_name, " ", last_name) as full_name' : 'id, first_name, last_name, "0" as show_email'
            ).'
            ,
            email as email_address
        ')
        ->from('administrator_users')
        ->order_by('first_name', 'ASC');
        if($status != -1) $this->db->where('active', $status);
        if($exec) _e($this->db->get_compiled_select(), true);
        $result = $this->db->get();
        $result_arr = $result->result_array();
        $result = $result->free_result();
        return $result_arr;
    }


    /**
     * Insert data
     *
     * @param $table_name String
     * @param $data_array Array
     *
     * @return Array|Bool
     */
    function _insert($table_name, $data_array){
        if(!sizeof($data_array)) return false;
        $insert = $this->db->insert($table_name, $data_array);
        if(!$insert) return false;
        return $this->db->insert_id();
    }

    /**
     * Get sms records
     *
     * @param $module String
     * @param $user_id Integer
     * @param $inset Integer
     * @param $offset Integer
     * @param $total_records Integer
     *
     * @return Array|Bool
     */
    function get_sms($module, $user_id, $inset, $offset, $total_records){
        // Create a subquery
        $result = $this
        ->db
        ->select('MAX(sid) as sid, receiver_phone_number')
        ->from('portal_sms')
        ->where('module_slug', $module)
        ->where('sender_user_id', $user_id)
        ->where('is_sent', 1)
        ->group_by('receiver_phone_number')
        ->limit($offset, $inset)
        ->get();
        //
        $ids_array = $result->result_array();
        $result   = $result->free_result();
        //
        if(!sizeof($ids_array)) return false;
        //
        foreach ($ids_array as $k0 => $v0) {
            $is_read = $this
            ->db
            ->select('is_read')
            ->from('portal_sms')
            ->where('module_slug', $module)
            ->where('is_read', 0)
            ->where('is_sent', 0)
            ->where('sender_phone_number', $v0['receiver_phone_number'])
            ->limit(1)
            ->count_all_results();
            //
            $result = $this
            ->db
            ->select('receiver_phone_number, "none" as message_body, "'.($is_read).'" as is_read, module_slug, created_at')
            ->from('portal_sms')
            ->limit('1')
            ->where_in('sid', $v0['sid'])
            ->order_by('created_at', 'DESC')
            ->get();
            //
            $result_arr = $result->row_array();
            $result     = $result->free_result();

            if(!sizeof($result_arr)) { unset($ids_array[$k0]); continue; };
            $ids_array[$k0] = array_merge($result_arr, $ids_array[$k0]);
        }


        return array(
            'TotalRecords' => count($ids_array),
            // 'TotalRecords' => 50,
            'Records' => $ids_array
        );
    }


    /**
     * Get records by phonenumber
     *
     * @param $module String
     * @param $user_id Integer
     * @param $phone_number String
     * @param $inset Integer
     * @param $offset Integer
     * @param $total_records Integer
     *
     * @return Array|Bool
     */
    function get_sms_by_phonenumber($module, $user_id, $phone_number, $inset, $offset, $total_records){
        //
        $result = $this
        ->db
        ->select('sender_phone_number, receiver_phone_number, message_body, module_slug, is_sent, message_mode, created_at')
        ->from('portal_sms')
        ->where('module_slug', $module)
        ->group_start()
        ->where('sender_user_id', $user_id)
        ->or_where('receiver_user_id', $user_id)
        ->group_end()
        ->group_start()
        ->where('receiver_phone_number', $phone_number)
        ->or_where('sender_phone_number', $phone_number)
        ->group_end()
        ->order_by('sid', 'DESC')
        ->limit($offset, $inset)
        ->get();

        $result_arr = $result->result_array();
        $result     = $result->free_result();

        if(!sizeof($result_arr)) return false;

        $counted = $this
        ->db
        ->select('sid')
        ->from('portal_sms')
        ->where('module_slug', $module)
        ->group_start()
        ->where('sender_user_id', $user_id)
        ->or_where('receiver_user_id', $user_id)
        ->group_end()
        ->group_start()
        ->where('receiver_phone_number', $phone_number)
        ->or_where('sender_phone_number', $phone_number)
        ->group_end()
        ->order_by('sid', 'DESC')
        ->count_all_results();

        return array(
            'TotalRecords' => $counted,
            // 'TotalRecords' => 50,
            'Records' => $result_arr
        );

        return $result_arr;
    }


    /**
     * Get records by phonenumber
     *
     * @param $phone_number String
     * @param $company_id Integer
     * @param $message_service_id String
     *
     * @return Array|Bool
     */
    function get_last_sms_row($phone_number, $company_id, $message_service_sid){
        $this
        ->db
        ->select('
            receiver_user_id,
            receiver_user_type,
            receiver_phone_number,
            sender_phone_number,
            sender_user_id,
            sender_user_type,
            module_slug,
            message_mode
        ')
        ->from('portal_sms')
        ->limit('1')
        ->order_by('created_at', 'DESC')
        ->where('sender_phone_number', $phone_number);
        //
        if($company_id != 0){
            $this->db
            ->where('message_service_sid', $message_service_sid)
            ->where('company_id', $company_id);
        }else $this->db->where('module_slug', 'admin');

        //_e($this->db->get_compiled_select(), true, true);
        //
        $result = $this->db->get();
        //
        $result_arr = $result->row_array();
        $result     = $result->free_result();

        return $result_arr;
    }

    /**
     * Get unread SMS for 'admin'
     *
     * @param $type Bool
     * 0 = Unread SMS 
     * 1 = Read SMS
     * Default is 0
     *
     * @return Array|Bool
     */
    function get_sms_admin($type = 0){
        return $this
        ->db
        ->from('portal_sms')
        ->where('module_slug', 'admin')
        ->where('is_sent', 0)
        ->where('is_read', $type)
        ->count_all_results();
    }


    /**
     * Get unread SMS for 'admin'
     *
     * @param $phone_number String
     * Accepts E164 format
     *
     * @param $module String
     *
     * @return VOID
     */
    function update_read_status_by_phone_number_and_module($phone_number, $module){
        $this
        ->db
        ->where('module_slug', $module)
        ->group_start()
        ->where('receiver_phone_number', $phone_number)
        ->or_where('sender_phone_number', $phone_number)
        ->group_end()
        ->update('portal_sms', array( 'is_read' => 1));
    }

    /**
     * Get records by phonenumber
     *
     * @param $phone_number String
     * @param $company_id Integer
     * @param $message_service_id String
     *
     * @return Array|Bool
     */
    function fetch_last_sent_sms($module){
        $this
        ->db
        ->select('max(sid) as sid')
        ->from('portal_sms')
        ->group_by('receiver_phone_number')
        ->where('module_slug', $module)
        ->where('message_mode', 'production')
        ->where('is_sent', 1);
        //
        $result = $this->db->get();
        //
        $result_arr = $result->result_array();
        $result     = $result->free_result();
        //
        if(!sizeof($result_arr)) return false;
        //
        foreach ($result_arr as $k0 => $v0) {
            $result = $this
            ->db
            ->select('
                receiver_user_id,
                receiver_user_type,
                sender_user_id,
                sender_user_type,
                sender_phone_number,
                receiver_phone_number,
                message_sid,
                message_service_sid,
                module_slug,
                DATE_FORMAT(created_at, "%Y-%m-%d") as created_at
            ')
            ->from('portal_sms')
            ->limit(1)
            ->where('sid', $v0['sid'])
            ->get();
            $result_arr[$k0] = $result->row_array();
            $result = $result->free_result();
        }

        return $result_arr;
    }

    /**
     * Check message sid
     *
     * @param $message_sid String
     *
     * @return Bool
     */
    function check_message_sid($message_sid){
        return $this
        ->db
        ->select('sid')
        ->from('portal_sms')
        ->where('message_sid', $message_sid)
        ->limit(1)
        ->count_all_results();
    }
}