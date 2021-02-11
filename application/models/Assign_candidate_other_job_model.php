<?php

class Assign_candidate_other_job_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_all_companies($company_sid)
    {
        $this->db->select('users.sid, users.CompanyName');
        $this->db->where('reassign_candidate_companies.company_sid',$company_sid);
        $this->db->where('reassign_candidate_companies.status',1);

        $this->db->join('users','users.sid=reassign_candidate_companies.linked_company_sid','left');
        $result = $this->db->get('reassign_candidate_companies')->result_array();

        $this->db->select('sid, CompanyName');
        $this->db->where('sid',$company_sid);
        $result[] = $this->db->get('users')->result_array()[0];
        return $result;
    }

    function get_applicants($company_sid){
        $this->db->select('portal_job_applications.sid,portal_job_applications.first_name,portal_job_applications.last_name');
        $this->db->where('employer_sid',$company_sid);
        $this->db->where('portal_applicant_jobs_list.archived', 0);
        $this->db->where('portal_job_applications.hired_status', 0);

        $this->db->join('portal_applicant_jobs_list', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid', 'left');
        $applicants = $this->db->get('portal_job_applications')->result_array();

        return $applicants;
    }

    function get_company_jobs($company_sid)
    {
        $this->db->select('sid, Title, active');
        $this->db->where('active', 1);
        $this->db->where('user_sid', $company_sid);
        return $this->db->get('portal_job_listings')->result_array();
    }

    function get_applied_applicant($company,$job){

        $this->db->select('portal_job_listings.Title,portal_job_listings.sid as job_sid,portal_job_applications.first_name,portal_job_applications.email,portal_job_applications.sid,portal_job_applications.last_name');
        $this->db->where('company_sid',$company);

        if($job!='all'){
            $this->db->where('portal_applicant_jobs_list.job_sid',$job);
        }

        $this->db->join('portal_job_applications','portal_job_applications.sid = portal_applicant_jobs_list.portal_job_applications_sid','left');
        $this->db->join('portal_job_listings','portal_job_listings.sid = portal_applicant_jobs_list.job_sid','right');

        $this->db->order_by('portal_job_applications.sid','desc');
        $result = $this->db->get('portal_applicant_jobs_list')->result_array();
        return $result;
    }

    function get_new_company_status($company_id){
        $statuses = array();
        $this->db->select('name,sid');
        $this->db->where('company_sid',$company_id);
        $this->db->where('css_class','not_contacted');
        $new_css_class = $this->db->get('application_status')->result_array();

        if(sizeof($new_css_class)==0){
            $this->db->select('name,sid');
            $this->db->where('company_sid',0);
            $this->db->where('css_class','not_contacted');
            $new_css_class = $this->db->get('application_status')->result_array();
        }

        $statuses['name'] = $new_css_class[0]['name'];
        $statuses['sid'] = $new_css_class[0]['sid'];
        return $statuses;
    }

    function check_applicant($email, $target_cid){
        $this->db->select('sid');
        $this->db->where('employer_sid',$target_cid);
        $this->db->where('email',$email);

        $ids = $this->db->get('portal_job_applications')->result_array();
        if(sizeof($ids)>0){
            return true;
        }
        else{
            return false;
        }
    }

    function get_job_title($job_sid){
        $this->db->select('Title');
        $this->db->where('sid',$job_sid);
        $result = $this->db->get('portal_job_listings')->result_array();
        return $result[0]['Title'];
    }

    function check_applicant_job($job,$candidate){
        $this->db->select('sid');
        $this->db->where('portal_job_applications_sid',$candidate);
        $this->db->where('job_sid',$job);

        $ids = $this->db->get('portal_applicant_jobs_list')->result_array();
        if(sizeof($ids)>0){
            return true;
        }
        else{
            return false;
        }
    }

    function get_applicant_emergency_contacts($old_applicant_sid,$new_applicant_sid){

        $this->db->select('*');
        $this->db->where('users_sid', $old_applicant_sid);
        $this->db->where('users_type', 'applicant');
        $result = $this->db->get('emergency_contacts')->result_array();
        if (count($result) > 0) {
            foreach ($result as $employee) {
                $insert_emergency_contact = array(
                    'users_sid' => $new_applicant_sid,
                    'users_type' => 'applicant',
                    'first_name' => $employee['first_name'],
                    'last_name' => $employee['last_name'],
                    'email' => $employee['email'],
                    'Location_Country' => $employee['Location_Country'],
                    'Location_State' => $employee['Location_State'],
                    'Location_City' => $employee['Location_City'],
                    'Location_ZipCode' => $employee['Location_ZipCode'],
                    'Location_Address' => $employee['Location_Address'],
                    'PhoneNumber' => $employee['PhoneNumber'],
                    'Relationship' => $employee['Relationship'],
                    'priority' => $employee['priority']
                );
                $this->db->insert('emergency_contacts', $insert_emergency_contact);
            }
            return $result;
        } else {
            return 0;
        }
    }

    function get_applicant_equipment_information($sid, $hired_id){
        $this->db->select('*');
        $this->db->where('users_sid', $sid);
        $this->db->where('users_type', 'applicant');
        $result = $this->db->get('equipment_information')->result_array();
        if (count($result) > 0) {
            foreach ($result as $equipment_information) {
                $insert_equipment_information = array(
                    'users_sid' => $hired_id,
                    'users_type' => 'applicant',
                    'equipment_details' => $equipment_information['equipment_details']
                );
                $this->db->insert('equipment_information', $insert_equipment_information);
            }
            return $result;
        } else {
            return 0;
        }
    }

    function get_applicant_dependant_information($sid, $hired_id, $target_cid){
        $this->db->select('*');
        $this->db->where('users_sid', $sid);
        $this->db->where('users_type', 'applicant');
        $result = $this->db->get('dependant_information')->result_array();
        if (count($result) > 0) {
            foreach ($result as $info) {
                $insert_dependant_information = array(
                    'users_sid' => $hired_id,
                    'users_type' => 'applicant',
                    'company_sid' => $target_cid,
                    'dependant_details' => $info['dependant_details']
                );
                $this->db->insert('dependant_information', $insert_dependant_information);
            }
            return $result;
        } else {
            return 0;
        }
    }

    function get_applicant_license_information($sid, $hired_id){
        $this->db->select('*');
        $this->db->where('users_sid', $sid);
        $this->db->where('users_type', 'applicant');
        $result = $this->db->get('license_information')->result_array();
        if (count($result) > 0) {
            foreach ($result as $info) {
                $insert_license_information = array(
                    'users_sid' => $hired_id,
                    'users_type' => 'applicant',
                    'license_type' => $info['license_type'],
                    'license_details' => $info['license_details']
                );
                $this->db->insert('license_information', $insert_license_information);
            }
            return $result;
        } else {
            return 0;
        }
    }

    function get_reference_checks($sid, $hired_id, $target_cid){
        $this->db->select('*');
        $this->db->where('user_sid', $sid);
        $this->db->where('users_type', 'applicant');
        $result = $this->db->get('reference_checks')->result_array();
        if (count($result) > 0) {
            foreach ($result as $info) {
                $insert_reference_checks = array(
                    'company_sid' => $target_cid,
                    'user_sid' => $hired_id,
                    'users_type' => 'applicant',
                    'organization_name' => $info['organization_name'],
                    'department_name' => $info['department_name'],
                    'branch_name' => $info['branch_name'],
                    'program_name' => $info['program_name'],
                    'period_start' => $info['period_start'],
                    'period_end' => $info['period_end'],
                    'period' => $info['period'],
                    'reference_type' => $info['reference_type'],
                    'reference_name' => $info['reference_name'],
                    'reference_title' => $info['reference_title'],
                    'reference_relation' => $info['reference_relation'],
                    'reference_email' => $info['reference_email'],
                    'reference_phone' => $info['reference_phone'],
                    'best_time_to_call' => $info['best_time_to_call'],
                    'other_information' => $info['other_information'],
                    'questionnaire_information' => $info['questionnaire_information'],
                    'questionnaire_conducted_by' => $info['questionnaire_conducted_by'],
                    'verified' => $info['verified'],
                    'status' => $info['status']
                );
                $this->db->insert('reference_checks', $insert_reference_checks);
            }
            return $result;
        } else {
            return 0;
        }
    }

    function get_full_employment_application($old_user,$new_user,$new_company_sid){
        $this->db->where('user_sid',$old_user);
        $this->db->where('user_type','applicant');

        $emp_application = $this->db->get('form_full_employment_application')->result_array();

        if(sizeof($emp_application)>0){
            $applicant = $emp_application[0];
            unset($applicant['sid']);

            $applicant['company_sid'] = $new_company_sid;
            $applicant['user_sid'] = $new_user;

            $this->db->insert('form_full_employment_application',$applicant);
        }
    }

    function re_assign_candidate($target_company_sid,$source_company_sid,$old_job,$new_job,$candidate,$company_name,$login_sid){
        $this->db->where('sid',$candidate);
        $this->db->order_by('sid', 'desc');
        $this->db->from('portal_job_applications');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        $applicant_assign_status = 0;
        $record = array();
        if(sizeof($records_arr)>0){
            foreach($records_arr as $applicant){
                $old_applicant_sid = $applicant['sid'];
                if(!$this->check_applicant($applicant['email'],$target_company_sid)){ //Check every time for email in that company
                    unset($applicant['sid']);
                    $applicant['applicant_type'] = 'Manual Candidate';
                    $applicant['employer_sid'] = $target_company_sid;
                    $this->db->insert('portal_job_applications',$applicant);
                    $new_applicant_sid = $this->db->insert_id();
                    $job_list = $this->get_applicant_job_list($old_job,$old_applicant_sid);
                    if(sizeof($job_list)>0){
                        foreach($job_list as $job){
                            unset($job['sid']);
                            $job['portal_job_applications_sid'] = $new_applicant_sid;
                            $job['company_sid'] = $target_company_sid;
                            $job['job_sid'] = $new_job;
                            $status = $this->get_new_company_status($target_company_sid);
                            $job['status'] = $status['name'];
                            $job['status_sid'] = $status['sid'];
                            $job['desired_job_title'] = $this->get_job_title($job['job_sid']);
                            $job['date_applied'] = date('Y-m-d H:i:s');
                            $job['applicant_type'] = 'Re-Assigned Candidates';
                            $job['applicant_source'] = $company_name . '-' . $job_list['desired_job_title'] . '-' . date('Y-m-d-H:i:s');
                            $this->db->insert('portal_applicant_jobs_list',$job);
                        }
                    }
                    // Copy Emergency Contacts
                    $this->get_applicant_emergency_contacts($old_applicant_sid,$new_applicant_sid);
                    // Copy Applicant Equipment Information
                    $this->get_applicant_equipment_information($old_applicant_sid,$new_applicant_sid);
                    // Copy Applicant Dependent Information
                    $this->get_applicant_dependant_information($old_applicant_sid,$new_applicant_sid,$target_company_sid);
                    // Copy Applicant License Information
                    $this->get_applicant_license_information($old_applicant_sid,$new_applicant_sid);
                    // Copy Applicant Reference Check
                    $this->get_reference_checks($old_applicant_sid,$new_applicant_sid,$target_company_sid);
                    // Copy Full Employment Applications
                    $this->get_full_employment_application($old_applicant_sid,$new_applicant_sid,$target_company_sid);
                    $applicant_assign_status = 1;


                    $record['source_company_sid']   = $source_company_sid;
                    $record['source_applicant_sid']   = $candidate;
                    $record['source_company_name']    = $company_name;
                    $record['targeted_company_sid']   = $target_company_sid;
                    $record['targeted_applicant_sid'] = $new_applicant_sid;
                    $record['status'] = 1;
                    $record['created_date'] = date('Y-m-d H:i:s');
                    $record['executed_by_sid'] = $login_sid;
                    $record['reason'] = 'Successfully Re-assigned';
                    $record['module_name'] = 're_assigned_applicant';
                }
                else{
                    if(!$this->check_applicant_job($new_job,$candidate)){
                        $job_list = $this->get_applicant_job_list($old_job,$old_applicant_sid);
                        unset($job_list['sid']);
                        $job_list['portal_job_applications_sid'] = $candidate;
                        $job_list['company_sid'] = $target_company_sid;
                        $job_list['job_sid'] = $new_job;
                        $status = $this->get_new_company_status($target_company_sid);
                        $job_list['status'] = $status['name'];
                        $job_list['status_sid'] = $status['sid'];
                        $job_list['desired_job_title'] = $this->get_job_title($job_list['job_sid']);
                        $job_list['date_applied'] = date('Y-m-d H:i:s');
                        $job_list['applicant_type'] = 'Re-Assigned Candidates';
                        $job_list['applicant_source'] = $company_name . '-' . $job_list['desired_job_title'] . '-' . date('Y-m-d-H:i:s');
                        $this->db->insert('portal_applicant_jobs_list',$job_list);
                        $applicant_assign_status = 2;

                        $record['source_company_sid']   = $source_company_sid;
                        $record['source_applicant_sid']   = $candidate;
                        $record['source_company_name']    = $company_name;
                        $record['targeted_company_sid']   = $target_company_sid;
                        $record['status'] = 1;
                        $record['created_date'] = date('Y-m-d H:i:s');
                        $record['executed_by_sid'] = $login_sid;
                        $record['reason'] = 'Applicant Already Exist. Re-assigned To New Job.';
                        $record['module_name'] = 're_assigned_applicant';

                    } else{
                        $applicant_assign_status = 3;

                        $record['source_company_sid']   = $source_company_sid;
                        $record['source_applicant_sid']   = $candidate;
                        $record['source_company_name']    = $company_name;
                        $record['targeted_company_sid']   = $target_company_sid;
                        $record['status'] = 1;
                        $record['created_date'] = date('Y-m-d H:i:s');
                        $record['executed_by_sid'] = $login_sid;
                        $record['reason'] = 'Applicant Already Exist And Applied For Job.';
                        $record['module_name'] = 're_assigned_applicant';
                    }
                }
                $this->db->insert('applicant_copied_by_admin',$record);
            }
        }
        return $applicant_assign_status;

    }

    function get_applicant_job_list($old_job,$job_applications_sid){
        $this->db->where('portal_job_applications_sid',$job_applications_sid);
        $this->db->where('job_sid',$old_job);
        $result = $this->db->get('portal_applicant_jobs_list')->result_array();
        return $result[0];
    }

}
