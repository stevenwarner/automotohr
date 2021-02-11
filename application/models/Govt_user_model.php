<?php

class Govt_user_model extends CI_Model {
    function __construct() {
        parent::__construct();
        $this->load->library('email');
        $this->load->library('encryption');
    }

    function create_or_update_govt_account($company_id, $company_name){
        $data = array(
            'company_id' => $company_id,
            'username' => $this->input->post('username'),
            'password' => $this->input->post('password'),
            'note' => $this->input->post('note'),
            'email' => $this->input->post('email'),
        );
        $sid = $this->input->post('sid');
        if($sid > 0 && $this->input->post('submit_type')=="govt_user_save") {
            // $subject = 'Login Credentials, {{company_name}}';
            // $encrypted_company_id = $this->encryption->encrypt($data['company_id']);
            // $data['encrypted_company_id'] = str_replace('/','$slash$',$encrypted_company_id);
            // Outputs: This is a plain-text message!
            //echo $this->encryption->decrypt($ciphertext);
            // $message = $this->load->view('manage_employer/govt_users/govt_user_register_email', $data, true);
            // $message = str_replace(['{{company_name}}'], [$company_name], $message);
            // $subject = str_replace(['{{company_name}}'], [$company_name], $subject);

            // $this->db->insert('govt_users_email_history', [
            //     'govt_user_id' => $sid,
            //     'email' => $data['email'],
            // ]);

            $this->db->where('sid',$sid);
            $this->db->update('govt_users',$data);
            $this->db->insert('govt_users_history', [
                'govt_user_id' => $sid,
                'username' => $this->input->post('username'),
                'password' => $this->input->post('password'),
                'note' => $this->input->post('note'),
                'email' => $this->input->post('email'),
            ]);
            $msg = "Your credentials have been saved successfully";

        }else if($sid > 0 && $this->input->post('submit_type')=="govt_user_send_email"){

            $subject = 'Login Credentials, {{company_name}}';
            $encrypted_company_id = $this->encryption->encrypt($data['company_id']);
            $data['encrypted_company_id'] = str_replace('/','$slash$',$encrypted_company_id);
            // Outputs: This is a plain-text message!
            //echo $this->encryption->decrypt($ciphertext);
            $message = $this->load->view('manage_employer/govt_users/govt_user_register_email', $data, true);
            $message = str_replace(['{{company_name}}'], [$company_name], $message);
            $subject = str_replace(['{{company_name}}'], [$company_name], $subject);
            log_and_sendEmail(FROM_EMAIL_NOTIFICATIONS, $data['email'], $subject, $message, $data['username']);
            $this->db->insert('govt_users_email_history', [
                'govt_user_id' => $sid,
                'email' => $data['email'],
            ]);
            $msg = "Email Sent Sucessfully";
        }else{
            if($sid > 0){
                $this->db->where('sid', $sid);
                $this->db->update('govt_users', $data);
                $msg = "Your Profile is updated successfully";
            }else{
                $response = $this->db->insert('govt_users', $data);
                $sid = $this->db->insert_id();
                $msg = "Your Profile is created successfully";

            }
            $this->db->insert('govt_users_history', [
                'govt_user_id' => $sid,
                'username' => $this->input->post('username'),
                'password' => $this->input->post('password'),
                'note' => $this->input->post('note'),
                'email' => $this->input->post('email'),
            ]);

        }
        return json_encode(['success'=>$msg,'sid'=>$sid]);


    }

    function get_user_detail($company_id){
        $this->db->where('company_id',$company_id);
        $query=$this->db->get('govt_users');
        $result=$query->row_array();
        return $result;
    }

    function get_user_exist($company_id){

        $this->db->where('company_sid',$company_id);
        $this->db->where('username',$this->input->post('username'));
        $this->db->where('password',$this->input->post('password'));
        $this->db->where('is_expired', 0);
        $this->db->where('expire_at > CURDATE()');
        $query=$this->db->get('govt_users');
        $result=$query->row_array();

        return $result;
    }

    //
    function get_i9form_users($session, $employeeIds = false){
        // Fetch I9 employee(s)
        $this->db->select('
            *,
            "assigned" as form_type,
            concat(users.first_name," ", users.last_name) as full_name
        ')
        ->join('users', 'users.sid = applicant_i9form.user_sid', 'inner')
        ->where('company_sid', $session['company_id'])
        ->where('user_consent is NOT NULL', null)
        ->where('user_type', 'employee')
        ->where('status', 1)
        ->from('applicant_i9form')
        ->order_by('full_name', 'ASC');
        $employees = explode(',', $session['employee_sids']);
        if($employees[0] != 'all'){
            $this->db->where_in('applicant_i9form.user_sid', $employees);
        }
        $result = $this->db->get();
        //
        $assignedI9 = $result->result_array();
        $result     = $result->free_result();
        // Fetch uploaded I9 employee(s)
        $this->db
        ->select('
            employee_sid as user_sid,
            s3_filename,
            "uploaded" as form_type,
            concat(users.first_name," ", users.last_name) as full_name
        ')
        ->join('users', 'users.sid = eev_documents.employee_sid', 'inner')
        ->where('document_type', 'i9')
        ->where('company_sid', $session['company_id'])
        ->order_by('full_name', 'ASC');
        if($employees[0] != 'all'){
            $this->db->where_in('eev_documents.employee_sid', $employees);
        }
        $result = $this->db->get('eev_documents');
        //
        $uploadedI9 = $result->result_array();
        $result     = $result->free_result();

        // Merge arrays
        $i9Array = array_merge_recursive($assignedI9, $uploadedI9);
        // Fetch supporting documents
        foreach ($i9Array as $k0 => $v0) {
            $i9Array[$k0]['documents'] = $this->getI9EmployeeDocuments($v0['user_sid']);
        }
        return $i9Array;
    }

    function fetch_i9form($user_type,$user_sid){
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('status', 1);
        $this->db->from('applicant_i9form');

        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (sizeof($records_arr) > 0) {
            return $records_arr[0];
        } else {
            return array();
        }
    }

    /**
     * Get I9 employees
     * Created: 19-09-2019
     *
     * @param int   $companySid
     * @param array $employeeIds
     *
     * @return array
     */
    function getI9Users($companySid, $employeeIds = array()){
        $this->db->select('
            users.sid,
            concat(users.first_name," ", users.last_name) as full_name
        ')
        ->from('applicant_i9form')
        ->where('company_sid', $companySid)
        ->where('user_type', 'employee')
        ->where('applicant_i9form.status', 1)
        ->join('users', 'users.sid = applicant_i9form.user_sid', 'inner');
        if(sizeof($employeeIds)) $this->db->where_in('users.sid', $employeeIds);
        $result = $this->db->get();
        $resultArray = $result->result_array();
        $result = $result->free_result();
        return $resultArray;
    }

    /**
     * Get I9 employee
     * Created: 19-09-2019
     *
     * @param array $employeeId
     *
     * @return array
     */
    function getI9User($employeeId){
        $result =
        $this->db
        ->select('concat(first_name," ", last_name) as full_name')
        ->from('users')
        ->where('sid', $employeeId)
        ->get();
        //
        $resultArray = $result->row_array();
        $result = $result->free_result();
        //
        return $resultArray;
    }

    /**
     * Get I9 employee documents
     * Created: 19-09-2019
     *
     * @param array $employeeSid
     *
     * @return array
     */
    function getI9EmployeeDocuments($employeeSid){
        //
        // $result =
        // $this->db
        // ->select('sid')
        // ->from('eev_documents')
        // ->where('employee_sid', $employeeSid)
        // ->where('document_type', 'i9')
        // ->get();
        // //
        // $eevDocumentId = $result->row_array();
        // $result = $result->free_result();
        // //
        // if(isset($eevDocumentId['sid'])) $eevDocumentId = $eevDocumentId['sid'];
        // else return array();
        //
        $result =
        $this->db->select('
            document_name,
            s3_filename
        ')
        ->from('eev_required_documents')
        ->where('employee_sid', $employeeSid)
        ->where_in('form_type', array('i9_assigned','uploaded'))
        ->order_by('sid', 'DESC')
        ->get();
        //
        $resultArray = $result->result_array();
        $result = $result->free_result();
        //
        return $resultArray;
    }


    function getAllEmployees($companySid){
        $result = $this->db
        ->select('
            sid,
            first_name,
            last_name,
            is_executive_admin,
            access_level,
            access_level_plus,
            pay_plan_flag,
            job_title,
        concat(first_name," ", last_name) as full_name,
        active as status')
        // ->where('active', 1)
        ->where('parent_sid', $companySid)
        ->order_by('concat(first_name," ", last_name)', 'ASC', false)
        ->order_by('active', 'ASC', false)
        ->get('users');
        //
        $records = $result->result_array();
        $result->free_result();
        //
        if(sizeof($records)){
            foreach ($records as $k0 => $v0) {
                $doExist =
                $this->db
                ->from('applicant_i9form')
                ->join('users', 'users.sid = applicant_i9form.user_sid', 'inner')
                ->where('user_consent is NOT NULL', null)
                ->where('user_type', 'employee')
                ->where('status', 1)
                ->where('applicant_i9form.user_sid', $v0['sid'])
                ->count_all_results();

                if(!$doExist){
                    $doExist = $this->db
                    ->from('eev_documents')
                    ->join('users', 'users.sid = eev_documents.employee_sid', 'inner')
                    ->where('document_type', 'i9')
                    ->where('employee_sid', $v0['sid'])
                    ->count_all_results();

                }

                $records[$k0]['i9'] = $doExist;

            }
        }
        return $records;
    }


    function getRecords($companySid, $filter = array()){
        $this->db
        ->select('
            *,
            expire_at,
            created_at
        ')
        ->order_by('is_expired', 'asc')
        ->order_by('created_at', 'desc')
        ->where('company_sid', $companySid);

        // Filter
        if($filter['agency'] != 'all') $this->db->where('agency_name', $filter['agency']);
        if($filter['agent'] != 'all') $this->db->where('agent_name', $filter['agent']);
        if($filter['status'] != 'all') $this->db->where('is_expired', $filter['status'] == 'active' ? 0 : 1);
        if($filter['startDate'] != 'all' && $filter['endDate'] != 'all')
        $this->db->where('DATE_FORMAT(created_at, "%m-%d-%Y") BETWEEN "'.( $filter['startDate'] ).'" AND "'.( $filter['endDate'] ).'"', null);
        else if($filter['startDate'] != 'all')
        $this->db->where('DATE_FORMAT(created_at, "%m-%d-%Y") >= "'.( $filter['startDate'] ).'"', null);
        else if($filter['endDate'] != 'all')
        $this->db->where('DATE_FORMAT(created_at, "%m-%d-%Y") <= "'.( $filter['endDate'] ).'"', null);
        //
        $result = $this->db->get('govt_users');
        //
        $records = $result->result_array();
        $result->free_result();

        if(sizeof($records)){
            foreach ($records as $k0 => $v0) {
                $result = $this->db
                ->select('created_at')
                ->where('govt_user_id', $v0['sid'])
                ->order_by('created_at', 'DESC')
                ->get('govt_users_email_history');
                //
                $rs = $result->row_array();
                $result->free_result();
                //
                $records[$k0]['last_sent_date'] = sizeof($rs) ? $rs['created_at'] : null;
            }
        }
        //
        return $records;
    }

    function checkAgent( $post, $ses ){
        $this->db
        ->where('username', strtolower($post['username']))
        ->where('company_sid', $ses['company_detail']['sid']);
        if(isset($post['sid'])) $this->db->where('sid <> '.$post['sid'].'', null);
        else{
            $this->db
            ->where('is_expired', 0);
        }
        return $this->db->count_all_results('govt_users');
    }


    function addAgent( $post, $ses ){
        // Upload image
        $newFileName = $_FILES['file']['name'];
        $newFileName = upload_file_to_aws('file', $ses['company_detail']['sid'], $newFileName);

        $this->db->insert('govt_users', array(
            'company_sid' => $ses['company_detail']['sid'],
            'agency_name' => ucwords(strtolower($post['agencyName'])),
            'agent_name' => ucwords(strtolower($post['agentName'])),
            'username' => strtolower($post['username']),
            'password' => $post['password'],
            'email' => strtolower($post['email']),
            'employee_sids' => $post['employees'],
            'note' => $post['note'],
            'employee_type' => $post['employeeType'],
            'picture' => $newFileName,
            'expire_at' => DateTime::createFromFormat('m/d/Y', $post['expiryDate'])->format('Y-m-d')
        ));

        return $this->db->insert_id();
    }

    function expireAgents( $post, $ses ){
        $this->db
        ->where('username', strtolower($post['username']))
        ->where('company_sid', $ses['company_detail']['sid'])
        ->update('govt_users', array(
            'is_expired' => 1
        ));
    }


    function getAgent( $companySid, $sid ){
        $result = $this->db
        ->select('*, DATE_FORMAT(expire_at, "%m/%d/%Y") as expire_at')
        ->where('company_sid', $companySid)
        ->where('sid', $sid)
        ->get('govt_users');
        //
        $record = $result->row_array();
        $result->free_result();
        //
        return $record;
    }


    function editAgent( $post, $ses ){
        //
        $newFileName = $post['picture'];
        if(isset($_FILES['file']) && sizeof($_FILES['file'])){
            // Upload image
            $newFileName = $_FILES['file']['name'];
            $newFileName = upload_file_to_aws('file', $ses['company_detail']['sid'], $newFileName);
        }

        $this->db
        ->where('sid', $post['sid'])
        ->update('govt_users', array(
            'agency_name' => ucwords(strtolower($post['agencyName'])),
            'agent_name' => ucwords(strtolower($post['agentName'])),
            'username' => strtolower($post['username']),
            'password' => $post['password'],
            'email' => strtolower($post['email']),
            'employee_sids' => $post['employees'],
            'employee_type' => $post['employeeType'],
            'note' => $post['note'],
            'picture' => $newFileName,
            'expire_at' => DateTime::createFromFormat('m/d/Y', $post['expiryDate'])->format('Y-m-d')
        ));
    }


    function expireAgentById($sid){
        $this->db
        ->where('sid', $sid)
        ->update('govt_users', array('is_expired' => 1));
    }

    function activateAgentById($sid){
        $this->db
        ->where('sid', $sid)
        ->update('govt_users', array('is_expired' => 0));
    }


    function getAgencies($companySid){
        $result = $this->db
        ->distinct('agency_name')
        ->where('company_sid', $companySid)
        ->order_by('agency_name', 'ASC')
        ->get('govt_users');
        //
        $records = $result->result_array();
        $result->free_result();
        //
        return $records;
    }


    function getAgents($companySid){
        $result = $this->db
        ->distinct('agent_name')
        ->where('company_sid', $companySid)
        ->order_by('agent_name', 'ASC')
        ->get('govt_users');
        //
        $records = $result->result_array();
        $result->free_result();
        //
        return $records;
    }
}
