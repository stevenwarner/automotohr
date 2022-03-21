<?php
class Copy_applicants_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    function get_all_companies($active = 1) {
        $this->db->select('sid, CompanyName');
        $this->db->where('parent_sid', 0);
        $this->db->where('active', $active);
        $this->db->where('is_paid', 1);
        $this->db->where('career_page_type', 'standard_career_site');
        $this->db->order_by('CompanyName', 'ASC');
        // $this->db->order_by('sid', 'desc');
        $this->db->from('users');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function get_applicant_job_list($job_applications_sid) {
        $this->db->where('portal_job_applications_sid', $job_applications_sid);
        $records_obj = $this->db->get('portal_applicant_jobs_list');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function get_job_title($job_sid) {
        $this->db->select('Title');
        $this->db->where('sid', $job_sid);
        $records_obj = $this->db->get('portal_job_listings');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr[0]['Title'];
    }

    function get_applicant_emergency_contacts($old_applicant_sid, $new_applicant_sid) {
        $this->db->select('*');
        $this->db->where('users_sid', $old_applicant_sid);
        $this->db->where('users_type', 'applicant');

        $records_obj = $this->db->get('emergency_contacts');
        $result = $records_obj->result_array();
        $records_obj->free_result();
        
        if (count($result) > 0) {
            foreach ($result as $employee) {
                $insert_emergency_contact = array(  'users_sid' => $new_applicant_sid,
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
                                                    'priority' => $employee['priority']);
                
                $this->db->insert('emergency_contacts', $insert_emergency_contact);
            }
            
            return $result;
        } else {
            return 0;
        }
    }

    function get_applicant_equipment_information($sid, $hired_id) {
        $this->db->select('*');
        $this->db->where('users_sid', $sid);
        $this->db->where('users_type', 'applicant');

        $records_obj = $this->db->get('equipment_information');
        $result = $records_obj->result_array();
        $records_obj->free_result();
        
        if (count($result) > 0) {
            foreach ($result as $equipment_information) {
                $insert_equipment_information = array(  'users_sid' => $hired_id,
                                                        'users_type' => 'applicant',
                                                        'equipment_details' => $equipment_information['equipment_details']);
                
                $this->db->insert('equipment_information', $insert_equipment_information);
            }
            
            return $result;
        } else {
            return 0;
        }
    }

    function get_applicant_dependant_information($sid, $hired_id, $target_cid) {
        $this->db->select('*');
        $this->db->where('users_sid', $sid);
        $this->db->where('users_type', 'applicant');
        
        $records_obj = $this->db->get('dependant_information');
        $result = $records_obj->result_array();
        $records_obj->free_result();
        
        if (count($result) > 0) {
            foreach ($result as $info) {
                $insert_dependant_information = array(  'users_sid' => $hired_id,
                                                        'users_type' => 'applicant',
                                                        'company_sid' => $target_cid,
                                                        'dependant_details' => $info['dependant_details']);
                $this->db->insert('dependant_information', $insert_dependant_information);
            }
            
            return $result;
        } else {
            return 0;
        }
    }

    function get_applicant_license_information($sid, $hired_id) {
        $this->db->select('*');
        $this->db->where('users_sid', $sid);
        $this->db->where('users_type', 'applicant');

        $records_obj = $this->db->get('license_information');
        $result = $records_obj->result_array();
        $records_obj->free_result();
        
        if (count($result) > 0) {
            foreach ($result as $info) {
                $insert_license_information = array(    'users_sid' => $hired_id,
                                                        'users_type' => 'applicant',
                                                        'license_type' => $info['license_type'],
                                                        'license_details' => $info['license_details']);
                
                $this->db->insert('license_information', $insert_license_information);
            }
            return $result;
        } else {
            return 0;
        }
    }

    function get_reference_checks($sid, $hired_id, $target_cid) {
        $this->db->select('*');
        $this->db->where('user_sid', $sid);
        $this->db->where('users_type', 'applicant');

        $records_obj = $this->db->get('reference_checks');
        $result = $records_obj->result_array();
        $records_obj->free_result();
        
        if (count($result) > 0) {
            foreach ($result as $info) {
                $insert_reference_checks = array(   'company_sid' => $target_cid,
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
                                                    'status' => $info['status']);
                
                $this->db->insert('reference_checks', $insert_reference_checks);
            }
            return $result;
        } else {
            return 0;
        }
    }

    function get_onboarding_configuration($sid, $hired_sid, $target_cid) {
        $this->db->select('*');
        $this->db->where('user_sid', $sid);
        $this->db->where('user_type', 'applicant');
        $records_obj = $this->db->get('onboarding_applicants_configuration');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            foreach ($records_arr as $record) {
                unset($record['sid']);
                $record['user_sid'] = $hired_sid;
                $record['user_type'] = 'applicant';
                $record['company_sid'] = $target_cid;
                $this->db->insert('onboarding_applicants_configuration', $record);
            }

            return $records_arr;
        } else {
            return 0;
        }
    }

    function check_applicant($email, $target_cid) {
        $this->db->select('sid');
        $this->db->where('employer_sid', $target_cid);
        $this->db->where('email', $email);
        $this->db->from('portal_job_applications');
        $ids = $this->db->count_all_results();
        // Added on: 20-05-2019
        return $ids != 0 ? true : false;

        if (sizeof($ids) > 0) {
            return true;
        } else {
            return false;
        }
    }

    function get_company_data($cid) {
        $this->db->select('CompanyName');
        $this->db->where('sid', $cid);
        $result = $this->db->get('users')->result_array()[0]['CompanyName'];
        return $result;
    }

    function get_all_applicants($source_company_sid, $type, $target_company_sid) {
        if ($type != 2) {
            $this->db->where('archived', $type);
        }
        
        $this->db->where('employer_sid', $source_company_sid);
        $this->db->order_by('sid', 'desc');
        $this->db->from('portal_job_applications');
        
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        $copied_applicant = array();
        $i = 0;
        
        if (sizeof($records_arr) > 0) {
            foreach ($records_arr as $applicant) {
                $old_applicant_sid = $applicant['sid'];
                //Keeping Record Of Old Applicant Data
                $copied_applicant[$i]['source_applicant_sid'] = $old_applicant_sid;
                $copied_applicant[$i]['source_company_sid'] = $source_company_sid;
                $copied_applicant[$i]['status'] = 0;
                $copied_applicant[$i]['created_date'] = date('Y-m-d H:i:s');
                
                if (!$this->check_applicant($applicant['email'], $target_company_sid)) {
                    unset($applicant['sid']);
                    $applicant['employer_sid'] = $target_company_sid;
                    $this->db->insert('portal_job_applications', $applicant);
                    $new_applicant_sid = $this->db->insert_id();

                    //Keeping Record Of Successful Copied
                    $copied_applicant[$i]['targeted_applicant_sid'] = $new_applicant_sid;
                    $copied_applicant[$i]['targeted_company_sid'] = $target_company_sid;
                    $copied_applicant[$i]['status'] = 1;
                    $job_list = $this->get_applicant_job_list($old_applicant_sid);
                    
                    if (sizeof($job_list) > 0) {
                        foreach ($job_list as $job) {
                            unset($job['sid']);
                            $job['portal_job_applications_sid'] = $new_applicant_sid;
                            $job['company_sid'] = $target_company_sid;
                            $status = $this->get_new_company_status($target_company_sid);
                            $job['status'] = $status['name'];
                            $job['status_sid'] = $status['sid'];
                            
                            if ($job['job_sid'] > 0) {
                                $job['desired_job_title'] = $this->get_job_title($job['job_sid']);
                                $job['job_sid'] = 0;
                            }
                            
                            $this->db->insert('portal_applicant_jobs_list', $job);
                        }
                    }
                    // Copy Emergency Contacts
                    $this->get_applicant_emergency_contacts($old_applicant_sid, $new_applicant_sid);
                    // Copy Applicant Equipment Information
                    $this->get_applicant_equipment_information($old_applicant_sid, $new_applicant_sid);
                    // Copy Applicant Dependent Information
                    $this->get_applicant_dependant_information($old_applicant_sid, $new_applicant_sid, $target_company_sid);
                    // Copy Applicant License Information
                    $this->get_applicant_license_information($old_applicant_sid, $new_applicant_sid);
                    // Copy Applicant Reference Check
                    $this->get_reference_checks($old_applicant_sid, $new_applicant_sid, $target_company_sid);
                }
                
                $i++;
            }
        }
        
        return $copied_applicant;
    }

    function get_new_company_status($company_id) {
        $statuses = array();
        $this->db->select('name,sid');
        $this->db->where('company_sid', $company_id);
        $this->db->where('css_class', 'not_contacted');
        
        $records_obj = $this->db->get('application_status');
        $new_css_class = $records_obj->result_array();
        $records_obj->free_result();

        if (sizeof($new_css_class) == 0) {
            $this->db->select('name,sid');
            $this->db->where('company_sid', 0);
            $this->db->where('css_class', 'not_contacted');
            $records_obj = $this->db->get('application_status');
            $new_css_class = $records_obj->result_array();
            $records_obj->free_result();
        }

        $statuses['name'] = $new_css_class[0]['name'];
        $statuses['sid'] = $new_css_class[0]['sid'];
        return $statuses;
    }

    function insert_copied_record($data) {
        $this->db->insert('applicant_copied_by_admin', $data);
        return $this->db->insert_id();
    }

    function get_applicant_jobs_sids($job_applications_sid) {
        $this->db->select('job_sid');
        $this->db->where('portal_job_applications_sid', $job_applications_sid);
        
        $records_obj = $this->db->get('portal_applicant_jobs_list');
        $result = $records_obj->result_array();
        $records_obj->free_result();
        return $result;
    }

//    function get_company_applicants($source_company_sid,$type){
//
//        $this->db->select('sid,first_name,last_name,email');
//        if($type!=2){
//            $this->db->where('archived',$type);
//        }
//        $this->db->where('employer_sid',$source_company_sid);
//        $this->db->order_by('sid', 'desc');
//        $this->db->from('portal_job_applications');
//        $records_obj = $this->db->get();
//        $records_arr = $records_obj->result_array();
//        $records_obj->free_result();
//        $i = 0;
//        foreach($records_arr as $row){
//            $job_list = $this->get_applicant_jobs_sids($row['sid']);
//            $job_details = array();
//            foreach($job_list as $job){
//                if($job['job_sid']>0){
//                    $desired_job_title = $this->get_job_title($job['job_sid']);
//                    $job_details[] = $desired_job_title;
//                }
//            }
//            if(sizeof($job_details)>0){
//                $records_arr[$i]['job_details'] = $job_details;
//            }
//            $i++;
//        }
//
//        return $records_arr;
//    }

    function get_company_applicants($source_company_sid, $type) {
        $this->db->select('portal_job_applications.sid,portal_job_applications.first_name,portal_job_applications.last_name,portal_job_applications.email,portal_applicant_jobs_list.job_sid,portal_job_listings.Title');
        
        if ($type != 2) {
            $this->db->where('portal_job_applications.archived', $type);
        }
        
        $this->db->where('portal_job_applications.employer_sid', $source_company_sid);
        $this->db->order_by('portal_job_applications.sid', 'desc');
        $this->db->group_by('portal_job_applications.sid');
        $this->db->join('portal_applicant_jobs_list', 'portal_applicant_jobs_list.portal_job_applications_sid=portal_job_applications.sid', 'left');
        $this->db->join('portal_job_listings', 'portal_job_listings.sid=portal_applicant_jobs_list.job_sid', 'left');
        $this->db->from('portal_job_applications');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function copy_selected($source_company_sid, $selected_ids, $target_company_sid) {
        $this->db->where_in('sid', $selected_ids);
        $this->db->order_by('sid', 'desc');
        $this->db->from('portal_job_applications');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        $copied_applicant = array();
        $i = 0;
        
        if (sizeof($records_arr) > 0) {
            foreach ($records_arr as $applicant) {
                $old_applicant_sid = $applicant['sid'];
                //Keeping Record Of Old Applicant Data
                $copied_applicant[$i]['source_applicant_sid'] = $old_applicant_sid;
                $copied_applicant[$i]['source_company_sid'] = $source_company_sid;
                $copied_applicant[$i]['status'] = 0;
                $copied_applicant[$i]['created_date'] = date('Y-m-d H:i:s');
                
                if (!$this->check_applicant($applicant['email'], $target_company_sid)) {
                    unset($applicant['sid']);
                    $applicant['employer_sid'] = $target_company_sid;
                    $this->db->insert('portal_job_applications', $applicant);
                    $new_applicant_sid = $this->db->insert_id();
                    //Keeping Record Of Successful Copied
                    $copied_applicant[$i]['targeted_applicant_sid'] = $new_applicant_sid;
                    $copied_applicant[$i]['targeted_company_sid'] = $target_company_sid;
                    $copied_applicant[$i]['status'] = 1;
                    $job_list = $this->get_applicant_job_list($old_applicant_sid);
                    
                    if (sizeof($job_list) > 0) {
                        foreach ($job_list as $job) {
                            unset($job['sid']);
                            $job['portal_job_applications_sid'] = $new_applicant_sid;
                            $job['company_sid'] = $target_company_sid;
                            $status = $this->get_new_company_status($target_company_sid);
                            $job['status'] = $status['name'];
                            $job['status_sid'] = $status['sid'];
                            
                            if ($job['job_sid'] > 0) {
                                $job['desired_job_title'] = $this->get_job_title($job['job_sid']);
                                $job['job_sid'] = 0;
                            }
                            
                            $this->db->insert('portal_applicant_jobs_list', $job);
                        }
                    }
                    // Copy Emergency Contacts
                    $this->get_applicant_emergency_contacts($old_applicant_sid, $new_applicant_sid);
                    // Copy Applicant Equipment Information
                    $this->get_applicant_equipment_information($old_applicant_sid, $new_applicant_sid);
                    // Copy Applicant Dependent Information
                    $this->get_applicant_dependant_information($old_applicant_sid, $new_applicant_sid, $target_company_sid);
                    // Copy Applicant License Information
                    $this->get_applicant_license_information($old_applicant_sid, $new_applicant_sid);
                    // Copy Applicant Reference Check
                    $this->get_reference_checks($old_applicant_sid, $new_applicant_sid, $target_company_sid);
                }
                $i++;
            }
        }
        
        return $copied_applicant;
    }

    function get_company_jobs($company_sid) {
        $this->db->select('sid,Title');
        $this->db->where('user_sid', $company_sid);
        $this->db->where('active', 1);
        
        $records_obj = $this->db->get('portal_job_listings');
        $result = $records_obj->result_array();
        $records_obj->free_result();
        return $result;
    }

    function get_company_with_job($source_id, $job_sid) {
        $this->db->select('portal_job_listings.Title,portal_job_applications.*');
        $this->db->where('portal_applicant_jobs_list.company_sid', $source_id);
        
        if ($job_sid != 0)
            $this->db->where('portal_applicant_jobs_list.job_sid', $job_sid);

        $this->db->order_by('portal_job_applications.sid', 'DESC');
        $this->db->join('portal_job_applications', 'portal_job_applications.sid = portal_applicant_jobs_list.portal_job_applications_sid', 'left');
        $this->db->join('portal_job_listings', 'portal_job_listings.sid = portal_applicant_jobs_list.job_sid', 'left');

        $records_obj = $this->db->get('portal_applicant_jobs_list');
        $result = $records_obj->result_array();
        $records_obj->free_result();
        return $result;
    }


    /**
     * Fetch record count 
     * for copying applicants
     * from one company to another
     *
     * @param $source_company_sid Integer
     * @param $type Integer
     * @param $page Integer Optional
     * @param $limit Integer Optional
     * @param $count_only Bool Optional
     * @param $ids_list Array Optional
     * @param $column String Optional
     * @param $job_sid Integer Optional
     *
     * @return Integer|Bool
     */
    function get_all_applicants_new($source_company_sid, $type, $page = 1, $limit = 100, $count_only = false, $ids_list = array(), $column = 'portal_job_applications.*', $job_sid = FALSE) {
        $start = $page == 1 ? 0 : ( (($page * $limit) - $limit) );

        if(!$count_only){
            if ($type != 2)
                $this->db->where('portal_job_applications.archived', $type);

            $this->db
            ->select($column)
            ->from('portal_job_applications')
            ->where('portal_job_applications.employer_sid', $source_company_sid)
            ->order_by('portal_job_applications.first_name', 'asc')
            // ->order_by('sid', 'desc')
            ->limit($limit, $start);
            //
            if(sizeof($ids_list)) $this->db->where_in('portal_job_applications.sid', $ids_list);

            $result = $this->db->get();

            $applicants = $result->result_array();
            $result = $result->free_result();
            //
            if(!sizeof($applicants)) return false;

            if(!$job_sid) return $applicants;

            foreach ($applicants as $k0 => $v0) {
                // get all job_sids by 
                $sub_query_to_fetch_all_job_sids = $this->db
                ->select('job_sid')
                ->from('portal_applicant_jobs_list')
                ->where('portal_job_applications_sid = '.$v0['sid'].'', null)
                ->get_compiled_select();
                //
                $result = $this->db
                ->select('GROUP_CONCAT(Title) as Title')
                ->from('portal_job_listings')
                ->where("sid IN($sub_query_to_fetch_all_job_sids)", null)
                ->get();
                //
                $applicants[$k0]['job_title'] = $result->row_array()['Title'];
                $result = $result->free_result();
                
            }
            //
            return $applicants;
        }else{

            if ($type != 2)
                $this->db->where('portal_job_applications.archived', $type);
            
            $this->db
            ->select('DISTINCT(portal_job_applications.sid)')
            ->from('portal_job_applications')
            ->where('portal_job_applications.employer_sid', $source_company_sid);
            
            if($job_sid && $job_sid != 1){
                $this->db->join('portal_applicant_jobs_list', 'portal_applicant_jobs_list.portal_job_applications_sid=portal_job_applications.sid', 'left');
                $this->db->join('portal_job_listings', 'portal_job_listings.sid=portal_applicant_jobs_list.job_sid', 'left');
                $this->db->group_by('portal_job_applications.sid');
                $this->db->where('portal_applicant_jobs_list.job_sid', $job_sid);
            }
            //
            if(sizeof($ids_list)) $this->db->where_in('portal_job_applications.sid', $ids_list);

            // _e($job_sid, true, true);
            // _e($this->db->get_compiled_select(), true, true);
            
            $return_array['TotalApplicants'] = $this->db->count_all_results();
        }

        return $return_array;
    }


    /**
     * Insert
     * Created on: 20-05-2019
     *
     * @param $table String
     * @param $data Array
     *
     * @return Bool|Integer
     */
    function _insert($table, $data){
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    /**
     * Transactions
     * Created on: 10-05-2019
     *
     * @param $type String
     *
     */
    function trans($type = 'trans_start'){
        $this->db->$type();
    }

    /**
     * Copy applicant emergency contatcs
     * Created on: 21-05-2019
     *
     * @param $old_applicant_sid Intger
     * @param $new_applicant_sid Intger
     *
     * @return Bool
     *
     */
    function copy_applicant_emergency_contacts($old_applicant_sid, $new_applicant_sid) {
        $result = $this->db
        ->select('
            first_name, 
            last_name,
            email,
            Location_Country,
            Location_State,
            Location_City,
            Location_ZipCode,
            Location_Address,
            PhoneNumber,
            Relationship,
            priority
        ')
        ->where('users_sid', $old_applicant_sid)
        ->where('users_type', 'applicant')
        ->get('emergency_contacts');

        $result_arr = $result->result_array();
        $result = $result->free_result();
        
        if (!sizeof($result_arr)) return false;
        foreach ($result_arr as $employee) {
            $this->db->insert(
                'emergency_contacts', 
                array(  
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
                )
            );
        }
        return true;
    }

    /**
     * Copy applicant equipment informartion
     * Created on: 21-05-2019
     *
     * @param $sid Integer
     * @param $hired_id Integer
     *
     * @return Bool
     *
     */
    function copy_applicant_equipment_information($sid, $hired_id) {
        $result = $this->db
        ->select('equipment_details')
        ->where('users_sid', $sid)
        ->where('users_type', 'applicant')
        ->get('equipment_information');

        $result_arr = $result->result_array();
        $result = $result->free_result();
        
        if (!sizeof($result_arr)) return false;
        foreach ($result_arr as $equipment_information) {
            $this->db->insert(
                'equipment_information',
                array(  
                    'users_sid' => $hired_id,
                    'users_type' => 'applicant',
                    'equipment_details' => $equipment_information['equipment_details']
                )
            );
        }
        return true;
    }

    /**
     * Copy applicant dependant informartion
     * Created on: 21-05-2019
     *
     * @param $sid Integer
     * @param $hired_id Integer
     * @param $target_cid Integer
     *
     * @return Bool
     *
     */
    function copy_applicant_dependant_information($sid, $hired_id, $target_cid) {
        $result = $this->db
        ->select('dependant_details')
        ->where('users_sid', $sid)
        ->where('users_type', 'applicant')
        ->get('dependant_information');

        $result_arr = $result->result_array();
        $result = $result->free_result();
        
        if (!sizeof($result_arr)) return false;
        foreach ($result_arr as $info) {
            $this->db->insert(
                'dependant_information', 
                array(  
                    'users_sid' => $hired_id,
                    'users_type' => 'applicant',
                    'company_sid' => $target_cid,
                    'dependant_details' => $info['dependant_details']
                )
            );
        }
        return true;
    }


    /**
     * Copy applicant license informartion
     * Created on: 21-05-2019
     *
     * @param $sid Integer
     * @param $hired_id Integer
     *
     * @return Bool
     *
     */
    function copy_applicant_license_information($sid, $hired_id) {
        $result = $this->db
        ->select('license_type, license_details')
        ->where('users_sid', $sid)
        ->where('users_type', 'applicant')
        ->get('license_information');

        $result_arr = $result->result_array();
        $result = $result->free_result();
        
        if(!sizeof($result_arr)) return false;
        foreach ($result_arr as $info) {
            $this->db->insert(
                'license_information', 
                array(    
                    'users_sid' => $hired_id,
                    'users_type' => 'applicant',
                    'license_type' => $info['license_type'],
                    'license_details' => $info['license_details']
                )
            );
        }
        return true;
    }

    /**
     * Copy applicant reference checks
     * Created on: 21-05-2019
     *
     * @param $sid Integer
     * @param $hired_id Integer
     * @param $target_cid Integer
     *
     * @return Bool
     *
     */
    function copy_reference_checks($sid, $hired_id, $target_cid) {
        $result = $this->db
        ->select('
            organization_name,
            department_name,
            branch_name,
            program_name,
            period_start,
            period_end,
            period,
            reference_type,
            reference_name,
            reference_title,
            reference_relation,
            reference_email,
            reference_phone,
            best_time_to_call,
            other_information,
            questionnaire_information,
            questionnaire_conducted_by,
            verified,
            status
        ')
        ->where('user_sid', $sid)
        ->where('users_type', 'applicant')
        ->get('reference_checks');

        $result_arr = $result->result_array();
        $result = $result->free_result();
        
        if (!sizeof($result_arr)) return false;
            foreach ($result_arr as $info) {
                $this->db->insert(
                    'reference_checks', 
                     array(   
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
                    )
                );
        }
        return true;
    }


    /**
     * Get jobs by company id and status
     * Created on: 21-08-2019
     *
     * @param $companyId
     * @param $jobStatus
     * @param $page
     * @param $limit
     *
     * @return Array|Bool
     */
    function fetchJobsByCompanyId($companyId, $jobStatus, $page, $limit = 100){
        //
        $start = $page == 1 ? 0 : ($page * $limit) - $limit;
        //
        $this->db
        ->select('
            portal_job_listings.sid, 
            portal_job_listings.active as job_status, 
            portal_job_listings.Title as job_title,
            portal_job_listings.Location_State as job_state,
            portal_job_listings.Location_City as job_city,
            portal_job_listings.JobType as job_type
        ')
        ->from('portal_job_listings')
        ->order_by('portal_job_listings.Title', 'ASC')
        ->where('portal_job_listings.user_sid', $companyId);
        //
        if($jobStatus != -1) $this->db->where('portal_job_listings.active', $jobStatus);
        else $this->db->where_in('portal_job_listings.active', array(0,1));
        //
        $result = $this->db
        ->limit($limit, $start)
        ->get();
        //
        $jobs = $result->result_array();
        $result = $result->free_result();
        //
        if(!sizeof($jobs)) return false;
        // Loop through applicants
        foreach ($jobs as $k0 => $v0){
            $job_title = ucwords(strtolower(trim($v0['job_title'])));
            $jobs[$k0]['job_title'] = $job_title;
            //
            $state = !empty($v0['job_state']) ? '['.db_get_state_name_only($v0['job_state']).']' : ''; 
            $city = !empty($v0['job_city']) ? '('.ucwords(strtolower(trim($v0['job_city']))).')' : ''; 
           
            $jobs[$k0]['new_job_title'] = $job_title.' '.$state.' '.$city.' ('.$v0['job_type'].')';
            //
            $jobs[$k0]['total_applicants']['archived'] = $this->db
            ->from('portal_job_applications')
            ->join('portal_applicant_jobs_list', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid')
            ->where('portal_applicant_jobs_list.job_sid', $v0['sid'])
            ->where('portal_applicant_jobs_list.archived', 1)
            ->count_all_results();
            $jobs[$k0]['total_applicants']['active'] = $this->db
            ->from('portal_job_applications')
            ->join('portal_applicant_jobs_list', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid')
            ->where('portal_applicant_jobs_list.job_sid', $v0['sid'])
            ->where('portal_applicant_jobs_list.archived', 0)
            ->count_all_results();
        } 
        //
        if($page != 1) return $jobs;
        //
        if($jobStatus != -1) $this->db->where('active', $jobStatus);
        else $this->db->where_in('portal_job_listings.active', array(0,1));
        $jobCount = $this->db
        ->from('portal_job_listings')
        ->where('user_sid', $companyId)
        ->count_all_results();
        //
        return array( 'Jobs' => $jobs, 'JobCount' => $jobCount );

    }

    function fetchApplicantsByCompanyId($companyId, $applicantsStatus, $page, $limit = 100){
        //
        $start = $page == 1 ? 0 : ($page * $limit) - $limit;
        //
        $this->db
        ->select('
            if(
                portal_applicant_jobs_list.job_sid = 0, 
                portal_applicant_jobs_list.desired_job_title, 
                portal_job_listings.Title
            ) as job_title, 
            portal_applicant_jobs_list.job_sid as job_id,
            portal_job_listings.Location_City as city,
            states.state_name as State, 
            if(
                portal_applicant_jobs_list.job_sid = 0, 
                "1", 
                portal_job_listings.active
            ) as job_status, 
            if(
                portal_applicant_jobs_list.job_sid = 0, 
                "approved", 
                portal_job_listings.status
            ) as status,
            portal_applicant_jobs_list.sid,
            concat(portal_job_applications.first_name," ",portal_job_applications.last_name) as full_name,
            portal_job_applications.email,
            portal_job_applications.sid as applicant_sid
        ')
        ->distinct()
        ->from('portal_applicant_jobs_list')
        ->join('portal_job_applications', 'portal_job_applications.sid =portal_applicant_jobs_list.portal_job_applications_sid ','inner')
        ->join('portal_job_listings', 'portal_job_listings.sid = portal_applicant_jobs_list.job_sid','left')
        ->join('states', 'portal_job_listings.Location_State=states.sid','left')
        ->where('portal_applicant_jobs_list.company_sid', $companyId)
        ->where('portal_applicant_jobs_list.archived', 0)
        ->order_by('Title', 'ASC');
        //
        if($applicantsStatus != -1) {$this->db->where('if(
                portal_applicant_jobs_list.job_sid = 0, 
                "1", 
                portal_job_listings.active
            )', $applicantsStatus);}
        else {$this->db->where_in('if(
                portal_applicant_jobs_list.job_sid = 0, 
                "1", 
                portal_job_listings.active
            )', array(0,1));}
        //
        $result = $this->db
        ->limit($limit, $start)
        ->get();
        //
        $applicants = $result->result_array();
        $result = $result->free_result();
        //
        if(!sizeof($applicants)) {return false;}
        //
        if($page != 1) {return $applicants;}
        //
        $this->db
            ->select('portal_applicant_jobs_list.sid')
            ->order_by('Title', 'ASC')
            ->where('portal_applicant_jobs_list.company_sid', $companyId)
            ->where('portal_applicant_jobs_list.archived', 0)
            ->distinct()
            ->from('portal_applicant_jobs_list')
            ->join('portal_job_applications', 'portal_job_applications.sid =portal_applicant_jobs_list.portal_job_applications_sid','inner')
            ->join('portal_job_listings', 'portal_job_listings.sid = portal_applicant_jobs_list.job_sid','left')
            ->join('states', 'portal_job_listings.Location_State=states.sid','left');

        if($applicantsStatus != -1) {$this->db->where('if(
                portal_applicant_jobs_list.job_sid = 0, 
                "1", 
                portal_job_listings.active
            )', $applicantsStatus);}
        else {$this->db->where_in('if(
                portal_applicant_jobs_list.job_sid = 0, 
                "1", 
                portal_job_listings.active
            )', array(0,1));}
        //
        $applicantsCount = $this->db->count_all_results();
        //
        return array( 'Applicants' => $applicants, 'ApplicantsCount' => $applicantsCount );

    }

    /**
     * Get applicants count by job id
     * Created on: 21-08-2019
     *
     * @param $jobId
     * @param $archived
     * @param $active
     * @param $limit
     *
     * @return Array|Bool
     */
    function fetchApplicantCountByJobId($companyId, $jobId, $archived, $active, $limit = 100){
        //
        $this->db
        ->from('portal_job_applications')
        ->join('portal_applicant_jobs_list', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid')
        ->where('portal_applicant_jobs_list.job_sid', $jobId)
        ->where('portal_applicant_jobs_list.company_sid', $companyId);
        //
        if($archived == 0 || $active == 0){
            if($archived != 0) $this->db->where('portal_job_applications.archived', 1);
            // if($active != 0) $this->db->where('portal_job_applications.status', 1);
        }
        //        
        return $this->db->count_all_results();
    }

    /**
     * Get applicants by job id
     * Created on: 21-08-2019
     *
     * @param $jobId
     * @param $archived
     * @param $active
     * @param $page
     * @param $limit
     *
     * @return Array|Bool
     */
    function fetchApplicantByJobId($companyId, $jobId, $archived, $active, $page, $limit = 10){
        //
        $start = $page == 1 ? 0 : ($page * $limit) - $limit;
        //
        $this->db
        ->select('portal_job_applications.*, portal_applicant_jobs_list.job_sid')
        ->from('portal_job_applications')
        ->join('portal_applicant_jobs_list', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid')
        ->where('portal_applicant_jobs_list.job_sid', $jobId)
        ->where('portal_applicant_jobs_list.company_sid', $companyId)
        ->limit($limit, $start);
        //
        if($archived == 0 || $active == 0){
            if($archived != 0) $this->db->where('portal_job_applications.archived', 1);
            // if($active != 0) $this->db->where('portal_job_applications.status', 1);
        }
        //        
        $result = $this->db->get();
        $result_arr = $result->result_array();
        $result     = $result->free_result();
        return sizeof($result_arr) ? $result_arr : false;
    }

    /**
     * Get job byid
     * Created on: 21-08-2019
     *
     * @param $applicantId
     *
     * @return Array|Bool
     */
    function getJobById($applicantId){
        //
        $this->db
        ->select('portal_applicant_jobs_list.*')
        ->from('portal_job_applications')
        ->join('portal_applicant_jobs_list', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid')
        ->where('portal_job_applications.sid', $applicantId);
        //        
        $result = $this->db->get();
        $result_arr = $result->row_array();
        $result     = $result->free_result();
        return sizeof($result_arr) ? $result_arr : false;
    }


    /**
     * Revert
     * Created on: 22-08-2019
     *
     * @param $jobId
     * @param $employerId
     *
     * @return VOID
     */
    function revert($jobId, $employerId){
        //
        $this->db
        ->where('job_sid', $jobId)
        ->where('employer_sid', $employerId)
        ->delete('portal_job_applications');
    }
   
  function get_applicant_data($sid){
     $this->db->select("*");
     $this->db->where("sid",$sid);
     $applicant_data=$this->db->get("portal_job_applications");
     return $applicant_data->row_array();
    }


    function mark_applicant_for_onboarding($applicant_sid) {
        $this->db->where('sid', $applicant_sid);
        $this->db->set('is_onboarding', 1);
        $this->db->update('portal_job_applications');
    }

    function un_mark_applicant_for_onboarding($applicant_sid) {
        $this->db->where('sid', $applicant_sid);
        $this->db->set('is_onboarding', 0);
        $this->db->update('portal_job_applications');
    }

    function save_onboarding_applicant($data_to_save) {
        $this->db->insert('onboarding_applicants', $data_to_save);
    }

    function check_ems_status ($company_sid) {
        $this->db->select('*');
        $this->db->where('sid', $company_sid);
        $this->db->where('active', 1);
        $this->db->where('parent_sid', 0);
        $records_obj = $this->db->get('users');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        $return_data = '';

        if (!empty($records_arr)) {
            $return_data = $records_arr[0]['ems_status'];
        }

        return $return_data;
    }
}
