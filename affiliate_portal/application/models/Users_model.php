<?php
class Users_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }
    function login($username, $password) {
        $this->db->select('*');
        $this->db->from('marketing_agencies');
        $this->db->where('username', $username);
        $this->db->where('password', MD5($password));
        $this->db->limit(1);
        $affiliate_query = $this->db->get();
        //echo $this->db->last_query();
        if ($affiliate_query->num_rows() == 1) {
            $affiliate_users = $affiliate_query->result_array();
            $status = $affiliate_users[0]['status']; // check the status whether the affiliate is active or inactive
            if ($status) {
                $data['status'] = 'active';
                $data['affiliate_users'] = $affiliate_users[0];
            } else {
                $data['status'] = 'inactive';
            }
        } else {
            $data['status'] = 'not_found';
        }
        return $data;
    }
    function get_executive_users_companies($executive_admin_sid = NULL, $keyword = NULL) {
        if ($executive_admin_sid == NULL || $executive_admin_sid == 0) {
            $this->session->set_flashdata('message', '<b>Error:</b> No Company Found!');
            redirect(base_url('dashboard'), "refresh");
        }
        
        $this->db->select('t1.sid');
        $this->db->select('t1.executive_admin_sid');
        $this->db->select('t1.company_sid');
        $this->db->select('t1.logged_in_sid');
        $this->db->select('t2.CompanyName as company_name');
        $this->db->select('t3.sub_domain as company_website');
        $this->db->from('executive_user_companies as t1');
        $this->db->join('users as t2', 't2.sid = t1.company_sid', 'left');
        $this->db->join('portal_employer as t3', 't3.user_sid = t1.company_sid', 'left');
        $this->db->where('t1.executive_admin_sid', $executive_admin_sid);
        
        if($keyword != NULL || $keyword != ''){
            $this->db->like('t2.CompanyName', $keyword);
        }
        
        $this->db->order_by('t2.sid', 'desc');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }
    function check_user($username) {
        $this->db->select('*');
        $this->db->from('executive_users');
        $this->db->where('username', $username);
        $this->db->limit(1);
        $executive_query = $this->db->get();
        if ($executive_query->num_rows() > 0) {
            return false;
        } else {
            return true;
        }
    }
    function check_email($email) {
        $this->db->select('*');
        $this->db->from('marketing_agencies');
        $this->db->where('email', $email);
        $this->db->limit(1);
        $record_query = $this->db->get();
        if ($record_query->num_rows() > 0) {
            return false;
        } else {
            return true;
        }
    }
    function get_user_name($email) {
        $this->db->select('full_name,username');
        $this->db->from('marketing_agencies');
        $this->db->where('email', $email);
        $this->db->where('status', 1);
        $this->db->limit(1);
        $record_obj = $this->db->get();
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }
    function check_username($email) {
        $this->db->select('*');
        $this->db->from('marketing_agencies');
        $this->db->where('email', $email);
        $this->db->where('status', 1);
        $this->db->limit(1);
        $record_obj = $this->db->get();
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }
    function check_company($admin_id, $company_id) {
        $this->db->select('*');
        $this->db->from('executive_user_companies');
        $this->db->where('executive_admin_sid', $admin_id);
        $this->db->where('company_sid', $company_id);
        $this->db->limit(1);
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    function save_email_logs($data) {
        $this->db->insert('email_log', $data);
    }
    function varification_key($user_email, $random_string) {
        $this->db->where('email', $user_email);
        $data = array('activation_code' => $random_string);
        $this->db->update('marketing_agencies', $data);
    }
    function varification_user_key($user_name, $random_key) {
        $this->db->select('*');
        $this->db->from('marketing_agencies');
        $this->db->where('username', $user_name);
        $this->db->where('activation_code', $random_key);
        $data = $this->db->get();
        if ($data->num_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    function change_password($password, $user, $key) {
        $data = array('password' => $password);
        $this->db->where('username', $user);
        $this->db->where('activation_code', $key);
        $this->db->update('marketing_agencies', $data);
    }
    function reset_key($user) {
        $data = array('activation_code' => NULL);
        $this->db->where('username', $user);
        $this->db->update('marketing_agencies', $data);
    }
    function email_user_data($email) {
        $this->db->select('*');
        $this->db->from('marketing_agencies');
        $this->db->where('email', $email);
        $this->db->limit(1);
        $query_result = $this->db->get();
        if ($query_result->num_rows() > 0) {
            return $row = $query_result->row_array();
        }
    }
    function username_user_data($user) {
        $this->db->select('*');
        $this->db->from('marketing_agencies');
        $this->db->where('username', $user);
        $this->db->limit(1);
        $query_result = $this->db->get();
        if ($query_result->num_rows() > 0) {
            return $row = $query_result->row_array();
        }
    }
    function user_data_by_id($sid) {
        $this->db->from('executive_users');
        $this->db->where('sid', $sid);
        $this->db->limit(1);
        $query_result = $this->db->get();
        if ($query_result->num_rows() > 0) {
            $row = $query_result->row_array();
            return $row;
        }
    }
    function get_company_employees($company_id) {
        $this->db->select('sid, first_name, last_name, username, email, registration_date, active');
        $this->db->from('users');
        $this->db->where('parent_sid', $company_id);
        $employees = $this->db->get();
        return $employees->result_array();
    }
    function get_admin_invoices($company_sid = null, $invoice_status = 'active') {
        $this->db->select('*');
        $this->db->order_by('sid', 'DESC');
        if ($company_sid != null) {
            $this->db->where('company_sid', $company_sid);
        }
        $this->db->where('invoice_status', $invoice_status);
        $invoices = $this->db->get('admin_invoices')->result_array();
        if (!empty($invoices)) {
            foreach ($invoices as $key => $invoice) {
                $this->db->select('item_name');
                $this->db->where('invoice_sid', $invoice['sid']);
                $invoice_item_names = $this->db->get('admin_invoice_items')->result_array();
                if (!empty($invoice_item_names)) {
                    $invoices[$key]['item_names'] = $invoice_item_names;
                } else {
                    $invoices[$key]['item_names'] = array();
                }
            }
        }
        return $invoices;
    }
    public function get_admin_marketplace_invoices($company_sid) {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->order_by('sid', 'DESC');
        $invoices = $this->db->get('invoices')->result_array();
        if (!empty($invoices)) {
            foreach ($invoices as $key => $invoice) {
                $items_ids = $invoice['product_sid'];
                $this->db->select('name');
                $this->db->where('sid IN ( ' . $items_ids . ' ) ');
                $items_names = $this->db->get('products')->result_array();
                if (!empty($items_names)) {
                    $invoices[$key]['item_names'] = $items_names;
                } else {
                    $invoices[$key]['item_names'] = array();
                }
            }
        }
        return $invoices;
    }
    public function get_parent_company($company_sid) {
        $this->db->select('*');
        $this->db->where('is_primary_admin', 1);
        $this->db->where('parent_sid', $company_sid);
        $admin = $this->db->get('users')->result_array();
        if (empty($admin)) {
            $this->db->select('*');
            $this->db->limit(1);
            $this->db->where('sid', intval($company_sid) + 1);
            $this->db->where('parent_sid', $company_sid);
            $admin = $this->db->get('users')->result_array();
        }
        return $admin;
    }
    public function get_company_details($company_id) {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('sid', $company_id);
        $company = $this->db->get();
        return $company->result_array();
    }
    
    public function get_company_exists($company_id) {
        $this->db->select('sid');
        $this->db->where('sid', $company_id);
        $records_obj = $this->db->get('users');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }
    
    public function get_portal_details($company_id) {
        $this->db->select('*');
        $this->db->from('portal_employer');
        $this->db->where('user_sid', $company_id);
        $this->db->limit(1);
        $portal = $this->db->get()->result_array();
        
        if(!empty($portal)){
            return $portal[0];
        } else {
            return array();
        }
    }
    public function check_key($key){
        $this->db->select('username');
        $this->db->from('executive_users');
        $this->db->where('salt', $key);
        $this->db->limit(1);
        $query_result = $this->db->get()->result_array();
        if(sizeof($query_result)>0){
            return $query_result[0]['username'];
        }
        else{
            return 'not_found';
        }
    }
    public function updatePass($password, $key, $username){
        $data = array('password' => MD5($password),'verification_key' => NULL);
        $this->db->where('verification_key', $key);
        $this->db->where('username', $username);
        $this->db->update('marketing_agencies', $data);
//        $update_id = $this->db->affected_rows();
//        if($update_id){
//            $this->db->select('*');
//            $this->db->from('executive_users');
//            $this->db->where('id', $update_id);
//            $this->db->limit(1);
//            $executive_query = $this->db->get();
//            //echo $this->db->last_query();
//            if ($executive_query->num_rows() == 1) {
//                $executive_users = $executive_query->result_array();
//                $status = $executive_users[0]['active']; // check the status whether the account is active or inactive
//
//                if ($status == 1) {
//
//                    $data['status'] = 'active';
//                    $data['executive_user'] = $executive_users[0];
//                } else {
//                    $data['status'] = 'inactive';
//                }
//            }
//        }
//        else
//            return false;
    }
    
    public function update_executive_admin_profile($sid, $data) {
        $this->db->where('sid', $sid);
        $result = $this->db->update('executive_users', $data);
        
        if($result) { // record is updated, Update all companies accounts + session.
            $this->db->select('logged_in_sid');
            $this->db->where('executive_admin_sid' , $sid);
            $record_obj = $this->db->get('executive_user_companies');
            $record_arr = $record_obj->result_array();
            $record_obj->free_result();
            if(!empty($record_arr)){
                $data_to_update = array(
                                        'first_name' => $data['first_name'],
                                        'last_name' => $data['last_name'],
                                        'email' => $data['email'],
                                        'PhoneNumber' => $data['direct_business_number'],
                                        'job_title' => $data['job_title'],
                                        'YouTubeVideo' => $data['video']);
                if(isset($data['profile_picture'])) {  //profile_picture
                    $data_to_update['profile_picture'] = $data['profile_picture'];
                }
                foreach($record_arr as $users){
                    $this->db->where('sid', $users['logged_in_sid']);
                    $this->db->update('users', $data_to_update);
                }
            } // update session informtion.
            
            $this->db->select('*');
            $this->db->where('sid', $sid);
            $record_obj = $this->db->get('executive_users');
            $record_arr = $record_obj->result_array();
            $record_obj->free_result();            
            $session_data = array();
            $session_data['status'] = 'active';
            $session_data['executive_user'] = $record_arr[0];
            $this->session->set_userdata('affiliate_loggedin', $session_data);
            return 'success';
        } else {
            return 'error';
        }
    }
    public function change_login_cred($password, $id) {
        $data = array('password' => $password);
        $this->db->where('sid', $id);
        $this->db->update('executive_users', $data);
    }
    
    public function get_employee_email($sid){
        $this->db->select('email');
        $this->db->where('sid', $sid);
        $this->db->from('users');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        $email = null;
        
        if(!empty($records_arr)) {
            $email = $records_arr[0]['email'];
        }
        return $email;
    }
    public function verify_affiliate_key($verification_key = NULL){
        $this->db->select('username');
        $this->db->where('verification_key',$verification_key);
        $result = $this->db->get('marketing_agencies')->result_array();
        if(sizeof($result)>0){
            return $result[0]['username'];
        }else{
            return 'not_found';
        }
    }
    public function get_affiliate_user($affiliate_user_sid) {
        $this->db->select('*');
        $this->db->from('marketing_agencies');
        $this->db->where('sid', $affiliate_user_sid);
        $this->db->limit(1);
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }
    public function check_user_username_before_update_login_credential($sid, $username) {
        $this->db->select('sid');
        $this->db->where('username', $username);
        $this->db->where('sid !=', $sid);
        $this->db->limit(1);
        $result = $this->db->get('marketing_agencies')->result_array();
        return $result;
    }
    public function update_Affiliate_user($user_sid, $data_to_update) {
        $this->db->where('sid', $user_sid);
        $this->db->update('marketing_agencies', $data_to_update);
    }
    public function get_affiliate_user_profilr_picture($affiliate_user_sid) {
        $this->db->select('profile_picture');
        $this->db->from('marketing_agencies');
        $this->db->where('sid', $affiliate_user_sid);
        $this->db->limit(1);
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }
    public function update_user_login_credential($sid, $data_array) {
        $this->db->where('sid', $sid);
        $this->db->update('marketing_agencies', $data_array);
    }
}