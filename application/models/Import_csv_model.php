<?php

class Import_csv_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function CheckIfEmployeeExists($company_sid, $emailAddress, $isEmail = true) {
        $this->db->select('sid,extra_info');
        if($isEmail){
            $this->db->where('email', $emailAddress);
        } else{
            $this->db->where('ssn', $emailAddress);
        }
        $this->db->where('parent_sid', $company_sid);
        $data = $this->db->get('users')->result_array();

        if (!empty($data) && count($data) > 0) {
            return $data[0];
        } else {
            return false;
        }
    }

    function CheckIfEmployeeExistsWithOthers($company_sid, $empNo, $ssn, $phoneNumber) {
        $this->db->select('sid');
        $this->db->from('users');
        if((!empty($phoneNumber) && $phoneNumber != NULL) || (!empty($ssn) && $ssn != NULL) || (!empty($empNo) && $empNo != NULL)){
            $this->db->group_start();
            if(!empty($phoneNumber) && $phoneNumber != NULL){
                $this->db->where('PhoneNumber', $phoneNumber);
                if(!empty($empNo) && $phoneNumber != NULL)
                    $this->db->or_where('employee_number', $empNo);
                if(!empty($ssn) && $ssn != NULL)
                    $this->db->or_where('ssn', $ssn);
            }elseif(!empty($empNo) && $empNo != NULL){
                $this->db->where('employee_number', $empNo);
                if(!empty($ssn) && $ssn != NULL)
                    $this->db->or_where('ssn', $ssn);
            }else{
                if(!empty($ssn) && $ssn != NULL)
                    $this->db->where('ssn', $ssn);
            }
            $this->db->group_end();
        }
        $this->db->where('parent_sid', $company_sid);
        $count_exist = $this->db->count_all_results();

//        echo $this->db->last_query();die();
        if ($count_exist > 0) {
            return $count_exist;
        } else {
            return false;
        }
    }

    function InsertNewUser($data) {
        $this->db->insert('users', $data);
        return $this->db->insert_id();
    }

    function UpdateNewUser($sid, $data) {
        $this->db->where('sid', $sid);
        $this->db->update('users', $data);
    }

    function insert_new_applicant($company_sid, $email_address, $data) {
        $this->db->where('employer_sid', $company_sid);
        $this->db->where('email', $email_address);
        $applicant_info = $this->db->get('portal_job_applications')->result_array();
        $my_return = 0;
        
        if(!empty($applicant_info)){
            $applicant_info = $applicant_info[0]; // applicant found. Please update his credentials
            $data_to_update = array();
            
            if(!empty($data['first_name'])){
                $data_to_update['first_name'] = $data['first_name'];
            }
            
            if(!empty($data['last_name'])){
                $data_to_update['last_name'] = $data['last_name'];
            }
            
            if(!empty($data['phone_number'])){
                $data_to_update['phone_number'] = $data['phone_number'];
            }
            
            if(!empty($data['address'])){
                $data_to_update['address'] = $data['address'];
            }
            
            if(!empty($data['city'])){
                $data_to_update['city'] = $data['city'];
            }
            
            if(!empty($data['zipcode'])){
                $data_to_update['zipcode'] = $data['zipcode'];
            }
            
            if(!empty($data['state'])){
                $data_to_update['state'] = $data['state'];
            }
            
            if(!empty($data['country'])){
                $data_to_update['country'] = $data['country'];
            }
            
            if(!empty($data['pictures'])){
                $data_to_update['pictures'] = $data['pictures'];
            }
            
            if(!empty($data['resume'])){
                $data_to_update['resume'] = $data['resume'];
            }
            
            if(!empty($data['cover_letter'])){
                $data_to_update['cover_letter'] = $data['cover_letter'];
            }
            
            if(!empty($data_to_update)){
                $this->db->where('sid', $applicant_info['sid']);
                $this->db->update('portal_job_applications', $data_to_update);
            }
            
            $my_return = $applicant_info['sid'];
        } else {
            $this->db->insert('portal_job_applications', $data);
            $my_return = $this->db->insert_id();
        }

        return $my_return;
    }

    function insert_new_applicant_job_record($company_sid, $portal_job_applications_sid, $data) {
        // $this->db->select('sid');
        // $this->db->where('user_sid', $company_sid);
        // if(isset($desired_job_title)){
        //     $this->db->where('LOWER(Title)', strtolower($data['desired_job_title']));
        // }
        // $this->db->order_by('sid', 'DESC');
        // $this->db->limit(1);
        // $job_details = $this->db->get('portal_job_listings')->result_array();
        // $job_sid = 0;
        
        // if(!empty($job_details)){
        //     $job_sid = $job_details[0]['sid'];
        // }
        
        // if($job_sid > 0){
        //     $data_to_add = array('job_sid' => $job_sid);
        //     $data = array_merge($data, $data_to_add);
        // }

        $this->db->where('company_sid', $company_sid);
        $this->db->where('portal_job_applications_sid', $portal_job_applications_sid);
        $applicant_info = $this->db->get('portal_applicant_jobs_list')->result_array();

        if(empty($applicant_info)){
            $data['ip_address'] = '';
            $this->db->insert('portal_applicant_jobs_list', $data);
        }
    }

    function get_default_status_sid_and_text($company_sid) {
        $this->db->select('sid, name');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('css_class', 'not_contacted');
        $status = $this->db->get('application_status')->result_array();
        $data = array();

        if((sizeof($status) > 0) && isset($status[0]['sid'])){
            $data['status_sid'] = $status[0]['sid'];
            $data['status_name'] = $status[0]['name'];
        } else {
            $data['status_sid'] = 1;
            $data['status_name'] = 'Not Contacted Yet';
        }

        return $data;
    }
    
    function get_state_and_country_id($state_name) {
        $this->db->select('sid, country_sid');
        $this->db->where('active', 1);
        $this->db->group_start();
        $this->db->like('state_code', trim($state_name));
        $this->db->or_like('state_name', trim($state_name));
        $this->db->group_end();
        $this->db->from('states');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        
        if(!empty($records_arr)){
            $result = $records_arr[0];
        } else {
            $result = array();
        }
        
        return $result;
    }
    
    function verify_url_data($url, $type = null) {
    // step 1: Get the header response code
    // step 2: Get path information
    // step 3: Check Extension of the file to make sure it is correct file
    // step 4: check if the import is from AHR, then use same files or else go to step 5
    // step 5: Pass URL as first parameter and Type as second parameter e.g. Picture or Resume or cover letter.
        $http_response_header = get_headers($url);
        
        $matches = array();
        preg_match('#HTTP/\d+\.\d+ (\d+)#', $http_response_header[0], $matches);
                
        if(!empty($matches) && isset($matches[1]) && $matches[1] == 200) {
            //
            $this->load->library('aws_lib');
            $fileName = random_key(10).date('YmdHis').'.'.pathinfo($url)['extension'];
            $tmpPath = ROOTPATH.'assets/temp_files/'.($fileName);
            $options = [
                'Bucket' => AWS_S3_BUCKET_NAME,
                'Key' => $fileName,
                // 'Body' => file_get_contents($tmpPath),
                'ACL' => 'public-read',
                'ContentType' => pathinfo($url)['extension']
            ];
            if(($position = strpos($url, 'automotohrattachments.s3.amazonaws.com')) !== false){
                if(!preg_match('/localhost/i', base_url())){
                    @copy($url, $tmpPath);
                    $options['Body'] = @file_get_contents($tmpPath);
                    $this->aws_lib->put_object($options);
                    unlink($tmpPath);
                }
                // move the file to S3
                return $fileName;
                $attachment_name = substr($url, $position+39); 
                return $attachment_name; 
            }  else { // if outside URL - we will process later
                if(!preg_match('/localhost/i', base_url())){
                    @copy($url, $tmpPath);
                    $options['Body'] = @file_get_contents($tmpPath);
                    $this->aws_lib->put_object($options);
                    unlink($tmpPath);
                }
                return $fileName;
            }
        } else { // not valid url
            return null;
        }
    }
    
    function getEmployerDetail($id) {
        $this->db->where('sid', $id);
        return $this->db->get('users')->row_array();
    }
    
    function generate_username($first_name, $last_name, $email = NULL) {
        $username = NULL;
        
        if($first_name != NULL) {
            $username = clean(strtolower($first_name));
        }
        
        if($last_name != NULL) {
            $username .= clean(strtolower($last_name));
        }
        
        if($username == NULL && $email != NULL) {
            $username = clean(strtolower($email));
        } 
        
        if($username != NULL) {
            $this->db->select('*');
            $this->db->from('users');
            $this->db->where('username', $username);
            $this->db->limit(1);
            $result = $this->db->count_all_results();
            
            if($result > 0) {
                $username .= '-'.strtolower(random_key(5));
            }
        }

        return $username;        
    }


    /**
     * Get status id and name
     * Created on: 19-08-2019
     *
     * @param $companyId Integer
     *
     * @return Array
     */
    function getDefaultStatusSidAndText($companyId) {
        $result =  $this->db
        ->select('sid as status_sid, name as status_name')
        ->from('application_status')
        ->where('company_sid', $companyId)
        ->where('css_class', 'not_contacted')
        ->limit(1)
        ->get();
        //
        $result_arr = $result->row_array();
        $result     = $result->free_result();
        //
        return sizeof($result_arr) && isset($result_arr['sid']) ? $result_arr : array( 'status_sid' => 1, 'status_name' => 'Not Contacted Yet' );
    }

    function getDepartmentTeamIds($team_name){
        $this->db->select('sid,department_sid');
        $this->db->where('name',$team_name);
        $depTeam = $this->db->get('departments_team_management')->result_array();
        return $depTeam;
    }

    /**
     * Check if an employee already exists
     */
    function GetEmployee($companyId, $column, $value) {
        return $this->db
        ->where($column, $value)
        ->where('is_executive_admin', 0)
        ->where('parent_sid', $companyId)
        ->get('users')->row_array();
    }

    //
    function AddEmployeeStatus($insertArray){
        $this->db->insert('terminated_employees', $insertArray);
        return $this->db->insert_id();
    }

    //
    function UpdateRehireDateInUsers($rehireDate, $employeeId){
        $data_to_update = array(); 
        $data_to_update['rehire_date'] = $rehireDate;
        $data_to_update['general_status'] = 'rehired';
        $data_to_update['active'] = 1;
        $data_to_update['terminated_status'] = 0;

        $this->db->where('sid', $employeeId);
        $this->db->update('users', $data_to_update);
    }

    function updateUserInfo($data_to_update, $employeeId){
        $this->db->where('sid', $employeeId);
        $this->db->update('users', $data_to_update);
    }

   //
    function InsertLicenseDetails($data) {
        $this->db->insert('license_information', $data);
        return $this->db->insert_id();
    }

    function updatLicenseDetails($data_to_update, $employeeId){
        $this->db->where('users_sid', $employeeId);
        $this->db->where('license_type', 'drivers');
        $this->db->update('license_information', $data_to_update);
    }


    //
    function assigneDepartmentTeam($insertArray){
        $this->db->insert('departments_employee_2_team', $insertArray);
        return $this->db->insert_id();
    }

}
