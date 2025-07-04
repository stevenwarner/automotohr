<?php

class application_tracking_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    function get_applicant($employer_id, $job_id, $and, $limit, $start, $archived = 0) {
        $this->db->where('employer_sid', $employer_id);
        $this->db->where('hired_status', 0);
        $this->db->where('archived', $archived);
        $this->db->limit($limit, $start);
        
        if (!empty($and)) {
            $this->db->where($and);
        }
        
        $this->db->order_by("portal_job_applications.sid", "desc");
        return $this->db->get('portal_job_applications');
    }

    function get_applicant_all($company_id, $employer_id, $and, $archived) {
        $this->db->select('portal_job_applications.sid');
        $this->db->select('portal_job_applications.employer_sid');
        $this->db->select('portal_job_applications.first_name');
        $this->db->select('portal_job_applications.last_name');
        $this->db->select('portal_job_applications.pictures');
        $this->db->select('portal_job_applications.email');
        $this->db->select('portal_job_applications.phone_number');
        $this->db->select('portal_job_applications.address');
        $this->db->select('portal_job_applications.country');
        $this->db->select('portal_job_applications.city');
        $this->db->select('portal_job_applications.state');
        $this->db->select('portal_job_applications.zipcode');
        $this->db->select('portal_job_applications.resume');
        $this->db->select('portal_job_applications.cover_letter');
        $this->db->select('portal_job_applications.YouTube_Video');
        $this->db->select('portal_job_applications.full_employment_application');
        $this->db->select('portal_job_applications.hired_sid');
        $this->db->select('portal_job_applications.hired_status');
        $this->db->select('portal_job_applications.hired_date');
        $this->db->select('portal_job_applications.verification_key');
        $this->db->select('portal_job_applications.linkedin_profile_url');
        $this->db->select('portal_job_applications.extra_info');
        $this->db->select('portal_job_applications.referred_by_name');
        $this->db->select('portal_job_applications.referred_by_email');
        $this->db->select('portal_job_applications.job_fit_category_sid');
        $this->db->select('portal_job_listings.visible_to');
        $this->db->where('employer_sid', $company_id);
        $this->db->where('hired_status', 0);
        $this->db->where('archived', $archived);
       
        if (!empty($and)) {
            $this->db->where($and);
        }

        $this->db->order_by("portal_job_applications.sid", "desc");
        $this->db->join('portal_job_listings', 'portal_job_listings.sid = portal_job_applications.job_sid', 'left'); //Join Job
        $result_array = $this->db->get('portal_job_applications')->result_array();
        $return_result = array();
        
        foreach ($result_array as $row) {
            if (!empty($row['visibile_to']) && $row['visibile_to'] != null) {
                $visible_to = unserialize($row['visibile_to']);

                if (in_array($employer_id, $visible_to)) {
                    $return_result[] = $row;
                }
            }
        }

        return $return_result;
    }

    function get_applicant_count($employer_id, $job_id, $and) {
        $this->db->where('employer_sid', $employer_id);
        $this->db->where('hired_status', 0);
        
        if (!empty($and)){
            $this->db->where($and);
        }
        
        $this->db->order_by("sid", "desc");
        return $this->db->get('portal_job_applications');
    }

    function get_job_title($job_id) {
        $this->db->where('sid', $job_id);
        $query = $this->db->get('portal_job_listings');
        $total = $query->num_rows();
        
        if ($total > 0) {
            $result = $query->result_array();
            return $result[0];
        }
    }

// haseeb please check this function
    function get_state_name($state_id) {
        $this->db->select('state_name');
        $this->db->where('sid', $state_id);
        $total = $this->db->get('states')->num_rows();
        
        if ($total > 0) {
            $this->db->select('state_name');
            $this->db->where('sid', $state_id);
            $result = $this->db->get('states')->result_array();
            return $result[0];
        }
    }

    function get_all_jobs($employer_id) {
        $this->db->select('sid,Title');
        $this->db->where('user_sid', $employer_id);
        return $this->db->get('portal_job_listings');
    }

    function get_all_job_applicants($employer_id) {
        $this->db->where('applicant_type', 'Applicant');
        $this->db->where('employer_sid', $employer_id);
        $this->db->where('hired_status', 0);
        $this->db->where('archived', 0);
        $result = $this->db->get('portal_job_applications');
        return $result->num_rows();
    }

    function get_all_talent_applicants($employer_id) {
        $this->db->where('applicant_type', 'Talent Network');
        $this->db->where('employer_sid', $employer_id);
        $this->db->where('hired_status', 0);
        $this->db->where('archived', 0);
        $result = $this->db->get('portal_job_applications');
        return $result->num_rows();
    }

    function get_all_manual_applicants($employer_id) {
        $this->db->where('applicant_type', 'Manual Candidate');
        $this->db->where('employer_sid', $employer_id);
        $this->db->where('hired_status', 0);
        $this->db->where('archived', 0);
        $result = $this->db->get('portal_job_applications');
        return $result->num_rows();
    }

    function delete_applicant($id) {
        $this->db->where('sid', $id);
        $this->db->where('hired_status', 0);
        $this->db->delete('portal_job_applications');
    }

    function mc_delete_applicant($id) {
        $this->db->where('sid', $id);
        $this->db->delete('portal_manual_candidates');
    }

    function hire_applicant($id) {
        $data = array(
            'status' => 'Placed/Hired'
        );
        
        $this->db->where('sid', $id);
        $this->db->update('portal_job_applications', $data);
    }

    function mc_hire_applicant($id) {
        $data = array(
            'status' => 'Placed/Hired'
        );
        
        $this->db->where('sid', $id);
        $this->db->update('portal_manual_candidates', $data);
    }

    function decline_applicant($id) {
        $data = array(
            'status' => 'Client Declined'
        );
        
        $this->db->where('sid', $id);
        $this->db->update('portal_job_applications', $data);
    }

    function mc_decline_applicant($id) {
        $data = array(
            'status' => 'Client Declined'
        );
        
        $this->db->where('sid', $id);
        $this->db->update('portal_manual_candidates', $data);
    }

    function change_status_applicant($id, $status, $company_sid) {
        $have_status = $this->check_company_status($company_sid);               
        
        $data = array(
                        'status' => $status
                    );
        
        if($have_status == true){
            $this->db->select('sid');
            $this->db->where('company_sid', $company_sid);
            $this->db->where('LOWER(name)', strtolower($status));
            $status_sid = $this->db->get('application_status')->result_array();
            
            if(!empty($status_sid)){
                $status_sid = $status_sid[0]['sid'];
            } else {
                $status_sid = 0;
            }
            
            $data['status_sid'] = $status_sid;
        }
        
        $this->db->where('sid', $id);
        $this->db->update('portal_job_applications', $data);
    }

//----------------Queries for EEO form Candidates--------------//
    function get_manual_candidates($employer_id) {
        $this->db->where('employer_sid', $employer_id);
        $this->db->order_by("sid", "desc");
        return $this->db->get('portal_manual_candidates');
    }

    function delete_manual_candidate($id) {
        $this->db->where('sid', $id);
        $this->db->delete('portal_manual_candidates');
    }

    function delete_single_applicant($id) {
        $this->db->where('sid', $id);
        $this->db->where('hired_status', 0);
        $this->db->delete('portal_job_applications');
    }

    function active_single_applicant($id) {
        $data = array(
                        'archived' => 0
                    );
        
        $this->db->where('sid', $id);
        $this->db->update('portal_job_applications', $data);
    }

    function arch_single_applicant($id) {
        $data = array(
            'archived' => 1
        );
        
        $this->db->where('sid', $id);
        $this->db->update('portal_job_applications', $data);
    }

    function change_status_candidate($id, $status, $company_sid) {
        $have_status = $this->check_company_status($company_sid);
        
        $data = array(
                        'status' => $status
                    );
        
        if($have_status == true){
            $this->db->select('sid');
            $this->db->where('company_sid', $company_sid);
            $this->db->where('LOWER(name)', strtolower($status));
            $status_sid = $this->db->get('application_status')->result_array();
            
            if(!empty($status_sid)){
                $status_sid = $status_sid[0]['sid'];
            } else {
                $status_sid = 0;
            }
            
            $data['status_sid'] = $status_sid;
        }
        
        $this->db->where('sid', $id);
        $this->db->update('portal_manual_candidates', $data);
    }

    function getApplicantData($app_id) {
        $args = array('sid' => $app_id);
        $res = $this->db->get_where('portal_job_applications', $args);
        $ret = $res->result_array();
        
        if (!empty($ret)) {
            return $ret[0];
        } else {
            return false;
        }
    }

    function getApplicantEvents($employer_id, $applicant_id, $users_type, $date = NULL) {
        $args = array('companys_sid' => $employer_id, 'applicant_job_sid' => $applicant_id, 'users_type' => $users_type);
        
        if($date != NULL){ // get all rating after his/her hiring date.
            $created_on = array('created_on >', $date);
            $args = array_merge($args, $created_on);
        }
        
        $this->db->order_by('created_on', 'DESC');
        $res = $this->db->get_where('portal_schedule_event', $args);
        $ret = $res->result_array();
        return $ret;
    }

    function getApplicantNotes($app_id) {
        $this->db->where('applicant_job_sid', $app_id);
        $this->db->order_by('sid', 'DESC');
        return $this->db->get('portal_misc_notes')->result_array();
    }

    function getEmployerID($job_id) {
        $result = $this->db
                ->get_where('portal_job_listings', array(
                                                    'sid' => $job_id
                                                    )
        );
        
        if ($result->num_rows() > 0) {
            $res = $result->result_array();
            return $res[0];
        }
    }

    function insertNote($employers_sid, $applicant_job_sid, $applicant_email, $notes) {
        $now = date('Y-m-d H:i:s');
        $args = array('employers_sid' => $employers_sid, 'applicant_job_sid' => $applicant_job_sid, 'applicant_email' => $applicant_email, 'notes' => $notes, 'insert_date' => $now);
        $this->db->insert('portal_misc_notes', $args);
    }

    function updateNote($sid, $employers_sid, $applicant_job_sid, $applicant_email, $notes) {
        $now = date('Y-m-d H:i:s');
        $args = array('employers_sid' => $employers_sid, 'applicant_job_sid' => $applicant_job_sid, 'applicant_email' => $applicant_email, 'notes' => $notes, 'insert_date' => $now);

        $this->db->where(
                            array('sid' => $sid)
                        )->update('portal_misc_notes', $args);
    }

    function deleteEvent($sid) {
        $this->db->delete('portal_schedule_event', array(
            'sid' => $sid
        ));
    }

    function addEvent($companys_sid, $employers_sid, $applicant_email, $applicant_job_sid, $title, $category, $date, $description, $eventstarttime, $eventendtime) {
        $args = array(
                    'companys_sid' => $companys_sid,
                    'employers_sid' => $employers_sid,
                    'applicant_email' => $applicant_email,
                    'applicant_job_sid' => $applicant_job_sid,
                    'title' => $title,
                    'category' => $category, 'date' => $date,
                    'description' => $description,
                    'eventstarttime' => $eventstarttime,
                    'eventendtime' => $eventendtime);

        $this->db->insert('portal_schedule_event', $args);
    }

    function updateEvent($my_sid, $companys_sid, $employers_sid, $applicant_email, $applicant_job_sid, $title, $category, $date, $description, $eventstarttime, $eventendtime) {
        $args = array(
                    'companys_sid' => $companys_sid,
                    'employers_sid' => $employers_sid,
                    'applicant_email' => $applicant_email,
                    'applicant_job_sid' => $applicant_job_sid,
                    'title' => $title,
                    'category' => $category, 'date' => $date,
                    'description' => $description,
                    'eventstarttime' => $eventstarttime,
                    'eventendtime' => $eventendtime);

        $this->db->where('sid', $my_sid)
                ->update('portal_schedule_event', $args);
    }

    function getCountryName($country_id) {
        $res = $this->db->get_where('countries', array('sid' => $country_id));
        
        if ($res->num_rows() > 0) {
            $ret = $res->result_array();
            return $ret[0]["country_name"];
        } else
            return "";
    }

    function getStateName($state_id) {
        $res = $this->db->get_where('states', array(
            'sid' => $state_id
        ));

        if ($res->num_rows() > 0) {
            $ret = $res->result_array();
            return $ret[0]["state_name"];
        } else
            return "";
    }

    function getTalentCandidates($eid) {
            $res = $this->db->where('employer_sid', $eid)
                ->get('portal_join_network')
                ->result_array();
        return $res;
    }

    function deleteTalentUser($id) {
        $this->db->delete('portal_join_network', array('sid' => $id));
    }

    function getPassingMarks($question_id) {
        $this->db->select('passing_score');
        $this->db->where('sid', $question_id);
        $query = $this->db->get('portal_screening_questionnaires');
        
        if ($query->num_rows > 0) {
            $result = $this->db->get('portal_screening_questionnaires')->result_array();
            return $result[0]['passing_score'];
        } else
            return 0;
    }

    function delete_note($id) {
        $this->db->where('sid', $id);
        $this->db->delete('portal_misc_notes');
    }

    function next_applicant($application_id, $employer_sid, $is_archived = false) {
        if($is_archived == true){
            $data = $this->db->query("SELECT `sid` FROM `portal_job_applications` WHERE sid > $application_id and `employer_sid` = $employer_sid and `hired_status` = 0 and `archived` = 1  ORDER BY `sid` ASC LIMIT 1");
        } else {
            $data = $this->db->query("SELECT `sid` FROM `portal_job_applications` WHERE sid > $application_id and `employer_sid` = $employer_sid and `hired_status` = 0 and `archived` = 0  ORDER BY `sid` ASC LIMIT 1");
        }

        if ($data->num_rows() > 0) {
            $data = $data->result_array();
            return $data[0];
        }
    }

    /*
      function next_applicant($currentApplicantId, $company_sid) {
      $this->db->select('sid');
      $this->db->where('employer_sid', $company_sid);
      $this->db->where('hired_status', 0);
      $this->db->order_by('sid', 'DESC');
      $applicantIds = $this->db->get('portal_job_applications')->result_array();
      $ApplicantIdsArray = array();

      foreach($applicantIds as $applicantId){
      $ApplicantIdsArray[] = $applicantId['sid'];
      }

      $currentApplicantIndex = array_search($currentApplicantId, $ApplicantIdsArray);
      end($ApplicantIdsArray);
      $lastIndex = key($ApplicantIdsArray);

      $myReturn = 0;

      if($currentApplicantIndex < $lastIndex){
      $myReturn = $ApplicantIdsArray[$currentApplicantIndex + 1];
      }elseif($currentApplicantIndex = $lastIndex){
      $myReturn = $ApplicantIdsArray[0];
      }

      return $myReturn;
      }
     */

    function previous_applicant($application_id, $employer_sid, $is_archived = false) {
        if($is_archived){
            $data = $this->db->query("SELECT `sid` FROM `portal_job_applications` WHERE sid < $application_id and `employer_sid` = $employer_sid and `hired_status` = 0 and `archived` = 1 ORDER BY `sid` DESC LIMIT 1");
        } else {
            $data = $this->db->query("SELECT `sid` FROM `portal_job_applications` WHERE sid < $application_id and `employer_sid` = $employer_sid and `hired_status` = 0 and `archived` = 0 ORDER BY `sid` DESC LIMIT 1");
        }

        if ($data->num_rows() > 0) {
            $data = $data->result_array();
            return $data[0];
        }
    }

    function save_rating($data) {
        $result = $this->db->get_where('portal_applicant_rating', array(
                                            'applicant_job_sid' => $data['applicant_job_sid'],
                                            'employer_sid' => $data['employer_sid']
                                        ));
        
        if ($result->num_rows() > 0) {
            $query_data = $result->result_array();
            $this->db->where('sid', $query_data[0]['sid']);
            $this->db->delete('portal_applicant_rating');
        }
        
        $data['date_added'] = date('Y-m-d H:i:s');
        $this->db->insert('portal_applicant_rating', $data);
    }

    function getApplicantRating($app_id, $employer_id, $users_type = NULL, $date = NULL) {
        $this->db->where('applicant_job_sid', $app_id);
        $this->db->where('employer_sid', $employer_id);
        
        if($users_type != NULL) {
            $this->db->where('users_type', $users_type);
        }
        
        if($date != NULL){ // get all rating after his/her hiring date.
            $this->db->where('date_added >', $date);
        }
        
        $result = $this->db->get('portal_applicant_rating');
        
        if ($result->num_rows() > 0) {
            $data = $result->result_array();
            return $data[0];
        }
    }
    
    function getApplicantReviewsCount($applicant_sid){
        $result = $this->db->get_where('portal_applicant_rating', array( 'applicant_job_sid' => $applicant_sid ));
        return $result->num_rows();
    }

    function getApplicantAverageRating($app_id, $users_type = NULL, $date = NULL) {
        $this->db->where('applicant_job_sid', $app_id);
        
        if($users_type != NULL) {
            $this->db->where('users_type', $users_type);
        }
        
        if($date != NULL){ // get all rating after his/her hiring date.
            $this->db->where('date_added >', $date);
        }
        
        $result = $this->db->get('portal_applicant_rating');
        
        $rows = $result->num_rows();
        
        if ($rows > 0) {
            $this->db->select_sum('rating');
            $this->db->where('applicant_job_sid', $app_id);
            $this->db->where('users_type', $users_type);
            $data = $this->db->get('portal_applicant_rating')->result_array();
            return round($data[0]['rating'] / $rows, 2);
        }
    }

    function getApplicantAllRating($app_id, $users_type = NULL, $date = NULL) {
            $this->db->select('portal_applicant_rating.*, users.username');
            $this->db->where('applicant_job_sid', $app_id);
            $this->db->where('users_type', $users_type);

            if($date != NULL){ // get all rating after his/her hiring date.
                $this->db->where('date_added >', $date);
            }
            
            $this->db->join('users', 'portal_applicant_rating.employer_sid=users.sid');
            $result = $this->db->get('portal_applicant_rating');

            if ($result->num_rows() > 0) {
                return $result;
            } else
                return NULL;
    }

    function upload_extra_attachments($user_data) {
        $this->db->insert('portal_applicant_attachments', $user_data);
    }

    function getApplicantExtraAttachments($app_id, $employer_id, $users_type = NULL) {
        $result = $this->db->get_where('portal_applicant_attachments', array(
                                        'applicant_job_sid' => $app_id,
                                        /*'employer_sid' => $employer_id,*/ //This brings Employer Specific Files.
                                        'users_type' => $users_type
                                    ));
        
        if ($result->num_rows() > 0) {
            $data = $result->result_array();
            return $data;
        }
    }

    function getCompanyAccounts($company_id) {
        $args = array('parent_sid' => $company_id, 'active' => 1, 'terminated_status' => 0);
        $this->db->select('sid,username,email');
        $this->db->where('is_executive_admin', 0);
        $res = $this->db->get_where('users', $args);
        $ret = $res->result_array();
        return $ret;
    }

    function saveEvent($data) {
        $data['created_on'] = date('Y-m-d H:i:s');
        $this->db->insert('portal_schedule_event', $data);
    }

    function getApplicantCountByMonth($applicant_type, $company_id) {
        $result = $this->db->query('SELECT MONTH(date_applied) as month, COUNT(*) as count FROM `portal_job_applications` where applicant_type = "' . $applicant_type . '" and employer_sid = ' . $company_id . ' and YEAR(date_applied) = ' . date('Y') . ' and `hired_status` = 0 GROUP BY MONTH(date_applied)');
        return $result->result_array();
    }

    function update_Event($sid, $data) {
        $this->db->where('sid', $sid);
        $this->db->update('portal_schedule_event', $data);
    }

    function delete_file($id, $type) {
        if($type == 'file'){
            $this->db->where('sid', $id);
            $this->db->update('portal_applicant_attachments', array('status' => 'deleted'));
        } else {
            $this->db->where('sid', $id);
            $this->db->update('portal_job_applications', array($type => NULL));
        }
    }

    function save_message($data) {
        $data['outbox'] = 1;
        $this->db->insert('private_message', $data);
        $data['outbox'] = 0;
        $this->db->insert('private_message', $data);
    }

    function get_sent_messages($to_id, $job_id) { // removed : AND `job_id` = "' . $job_id . '" |||| AND `job_id` = "' . $job_id . '"
        $result = $this->db->query('SELECT * FROM `private_message`  WHERE `to_id` = "' . $to_id . '" AND `job_id` = "' . $job_id . '" AND `outbox` = 1 UNION SELECT * FROM `private_message` WHERE `from_id` = "' . $to_id . '"  AND `job_id` = "' . $job_id . '" AND `outbox` = 0 ORDER BY id DESC')->result_array();
        return $result;
    }

    function get_receive_messages($from_id, $job_id) {
        $this->db->where('from_id', $from_id);
        $this->db->where('job_id', $job_id);
        return $this->db->get('private_message')->result_array();
    }

    function deleteMessage($sid) {
        $this->db->delete('private_message', array(
                                                    'id' => $sid
                                                ));
    }

    function getMessageDetail($id) {
        $this->db->where('id', $id);
        $result = $this->db->get('private_message')->result_array();
        return $result[0];
    }

    function getEmployerDetail($employer_id) {
            $this->db->where('sid', $employer_id);
            $result = $this->db->get('users')->result_array();
        return $result[0];
    }

    //Update VerificationKey
    function updateVerificationKey($applicant_sid, $verification_key) {
        $this->db->where('sid', $applicant_sid);
        
        $data = array(
            'verification_key' => $verification_key
        );
        
        $this->db->update('portal_job_applications', $data);
    }

    function get_min_applicant_id($company_name) {
            $data = $this->db->query("SELECT MIN(sid) as sid FROM `portal_job_applications` where `employer_sid` = $company_name and `hired_status` = 0 ");
            $data = $data->result_array();
        return $data[0]['sid'];
    }

    function get_max_applicant_id($company_name) {
        $data = $this->db->query("SELECT MAX(sid) as sid FROM `portal_job_applications` where `employer_sid` = $company_name and `hired_status` = 0 ");
        $data = $data->result_array();
        return $data[0]['sid'];
    }

    function update_private_message_to_id($job_id, $email, $data) {
        $this->db->where('to_id', $email)
                ->where('job_id', $job_id)
                ->update('private_message', $data);
    }

    function update_private_message_from_id($job_id, $email, $data) {
        $this->db->where('from_id', $email)
                ->where('job_id', $job_id)
                ->update('private_message', $data);
    }

    function GetJobApprovalModuleStatus($company_sid) {
        $this->db->select('has_job_approval_rights');
        $this->db->from('users');
        $this->db->where('sid', $company_sid);
        $this->db->limit(1);
        $myData = $this->db->get()->result_array();

        if (!empty($myData)) {
            return $myData[0]['has_job_approval_rights'];
        } else {
            return 0;
        }
    }

    function set_applicant_approval_status($company_sid, $applicant_sid, $status, $status_by, $reason = null, $status_type = null, $reason_response = null) {
        $data = array();
        $data['approval_status'] = $status;
        $data['approval_by'] = $status_by;
        $data['approval_date'] = date('Y-m-d H:i:s');
        
        if ($reason != null) {
            $data['approval_status_reason'] = $reason;
        }

        if($status_type != null){
            $data['approval_status_type'] = $status_type;
        }

        if($reason_response == null || $reason_response == ''){
            $data['approval_status_reason_response'] = '';
        } else {
            $data['approval_status_reason_response'] = $reason_response;
        }

        $this->db->where('company_sid', $company_sid);
        $this->db->where('portal_job_applications_sid', $applicant_sid);
        $this->db->update('portal_applicant_jobs_list', $data);
    }

    function get_all_applicants_by_approval_status($company_sid, $status, $applicant_id = null) {
        $this->db->select('portal_job_applications.sid');
        $this->db->select('portal_job_applications.employer_sid');
        $this->db->select('portal_job_applications.first_name');
        $this->db->select('portal_job_applications.last_name');
        $this->db->select('portal_job_applications.pictures');
        $this->db->select('portal_job_applications.email');
        $this->db->select('portal_job_applications.phone_number');
        $this->db->select('portal_job_applications.address');
        $this->db->select('portal_job_applications.country');
        $this->db->select('portal_job_applications.city');
        $this->db->select('portal_job_applications.state');
        $this->db->select('portal_job_applications.zipcode');
        $this->db->select('portal_job_applications.resume');
        $this->db->select('portal_job_applications.cover_letter');
        $this->db->select('portal_job_applications.YouTube_Video');
        $this->db->select('portal_job_applications.full_employment_application');
        $this->db->select('portal_job_applications.hired_sid');
        $this->db->select('portal_job_applications.hired_status');
        $this->db->select('portal_job_applications.hired_date');
        $this->db->select('portal_job_applications.verification_key');
        $this->db->select('portal_job_applications.linkedin_profile_url');
        $this->db->select('portal_job_applications.extra_info');
        $this->db->select('portal_job_applications.referred_by_name');
        $this->db->select('portal_job_applications.referred_by_email');
        $this->db->select('portal_job_applications.job_fit_category_sid');
        $this->db->select('portal_job_listings.Title');
        $this->db->select('users.first_name as approver_fname');
        $this->db->select('users.last_name as approver_lname');
        $this->db->from('portal_job_applications');
        $this->db->where('portal_job_applications.employer_sid', $company_sid);
        //$this->db->where('portal_job_applications.approval_status', $status);
        $this->db->where('portal_job_applications.hired_status', 0);
        $this->db->where('portal_applicant_jobs_list.approval_status', $status);

        if ($applicant_id != null) {
            $this->db->where('portal_job_applications.sid', $applicant_id);
        }

        $this->db->order_by('portal_job_applications.sid', 'DESC');
        $this->db->join('portal_applicant_jobs_list', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid', 'left');
        $this->db->join('portal_job_listings', 'portal_job_applications.job_sid = portal_job_listings.sid', 'left');
        $this->db->join('users', 'portal_job_applications.approval_by = users.sid', 'left');
        return $this->db->get()->result_array();
    }

    function get_all_users_with_approval_rights($company_sid) {
        $this->db->select('*');
        $this->db->where('parent_sid', $company_sid);
        $this->db->where('has_job_approval_rights', 1);
        $this->db->where('active', 1);
        $this->db->where('terminated_status', 0);
        $this->db->from('users');
        return $this->db->get()->result_array();
    }

    function get_single_applicant($applicant_id) {
        $this->db->select('portal_job_applications.sid');
        $this->db->select('portal_job_applications.employer_sid');
        $this->db->select('portal_job_applications.first_name');
        $this->db->select('portal_job_applications.last_name');
        $this->db->select('portal_job_applications.pictures');
        $this->db->select('portal_job_applications.email');
        $this->db->select('portal_job_applications.phone_number');
        $this->db->select('portal_job_applications.address');
        $this->db->select('portal_job_applications.country');
        $this->db->select('portal_job_applications.city');
        $this->db->select('portal_job_applications.state');
        $this->db->select('portal_job_applications.zipcode');
        $this->db->select('portal_job_applications.resume');
        $this->db->select('portal_job_applications.cover_letter');
        $this->db->select('portal_job_applications.YouTube_Video');
        $this->db->select('portal_job_applications.full_employment_application');
        $this->db->select('portal_job_applications.hired_sid');
        $this->db->select('portal_job_applications.hired_status');
        $this->db->select('portal_job_applications.hired_date');
        $this->db->select('portal_job_applications.verification_key');
        $this->db->select('portal_job_applications.linkedin_profile_url');
        $this->db->select('portal_job_applications.extra_info');
        $this->db->select('portal_job_applications.referred_by_name');
        $this->db->select('portal_job_applications.referred_by_email');
        $this->db->select('portal_job_applications.job_fit_category_sid');
        $this->db->select('portal_job_listings.Title as job_title');
        $this->db->where('portal_job_applications.sid', $applicant_id);
        $this->db->join('portal_job_listings', 'portal_job_listings.sid = portal_job_applications.job_sid', 'left');
        return $this->db->get('portal_job_applications')->result_array();
    }

    function get_employer_details($employer_sid){
        $this->db->select('first_name,last_name,email');
        $this->db->where('sid', $employer_sid);
        $this->db->where('terminated_status', 0);
        $this->db->order_by(SORT_COLUMN,SORT_ORDER);
        $data_from_db = $this->db->get('users')->result_array();

        if(!empty($data_from_db)){
            return $data_from_db[0];
        } else {
            return array();
        }
    }

    function reset_applicant_for_approval($company_sid, $employer_sid, $applicant_sid, $approval_status_reason_response){
        $dataToUpdate = array();
        $dataToUpdate['approval_status_reason_response'] = $approval_status_reason_response;
        $dataToUpdate['approval_status'] = 'pending';
        $dataToUpdate['approval_by'] = $employer_sid;
        $dataToUpdate['approval_date'] = date('Y-m-d H:i:s');
        $this->db->where('sid', $applicant_sid);
        //$this->db->where('employer_sid', $company_sid);
        $this->db->update('portal_job_applications', $dataToUpdate);
    }

    function insert_applicant_approval_history_record($company_sid, $employer_sid, $applicant_sid, $approval_status, $approval_status_type, $approval_status_date, $approval_status_text){
        $data_to_insert = array();
        $data_to_insert['company_sid'] = $company_sid;
        $data_to_insert['employer_sid'] = $employer_sid;
        $data_to_insert['applicant_sid'] = $applicant_sid;
        $data_to_insert['approval_status'] = $approval_status;
        $data_to_insert['approval_status_type'] = $approval_status_type;
        $data_to_insert['approval_status_date'] = $approval_status_date;
        $data_to_insert['approval_status_text'] = $approval_status_text;
        $this->db->insert('applicants_approval_status_history', $data_to_insert);
    }
    
    function save_onboarding_email_record($data){
        $this->db->insert('outsource_onboarding_emails', $data);
    }
    
    function get_kpa_email_sent_count($company_sid, $applicant_sid) {
        $this->db->where('company_sid', $company_sid);
        $this->db->where('applicant_sid', $applicant_sid);
        return $this->db->get("outsource_onboarding_emails")->num_rows();
    }
    
    function get_application_status($status_sid){
        $this->db->select('*');
        $this->db->where('sid', $status_sid);
        return $this->db->get('application_status')->result_array();
    }

    function check_company_status($company_sid) {
        $this->db->where('company_sid', $company_sid);
        $records = $this->db->get('application_status')->result_array();
        
        if(sizeof($records) <= 0){
            return false; // records do not exist
        } else {
            return true; // records exist
        }
    }
    
    function get_status_by_company($company_sid) {
        $this->db->select('sid, name, css_class, status_order');
        $this->db->where('company_sid', $company_sid);
        $this->db->order_by('status_order', 'asc');
        return $this->db->get('application_status')->result_array();
    }

    function get_job_categories($category_sid){
        if($category_sid != '') {
            $this->db->select('value');
            $this->db->where('field_sid', 198);
            $this->db->where('sid IN ( ' . $category_sid . ' )');
            $category_info = $this->db->get('listing_field_list')->result_array();
            return $category_info;
        } else {
            return array();
        }
    }
    
    function check_sent_video_questionnaires($applicant_sid, $company_sid){
        $this->db->where('company_sid', $company_sid);
        $this->db->where('applicant_sid', $applicant_sid);
        $this->db->limit(1);
        $data = $this->db->get('video_interview_questions_sent')->result_array();
        
        if(!empty($data)){
            return true;
        } else {
            return false;
        }
    }
    
    function check_answered_video_questionnaires($applicant_sid, $company_sid){
        $this->db->where('video_interview_questions_sent.applicant_sid', $applicant_sid);
        $this->db->where('video_interview_questions_sent.company_sid', $company_sid);
        $this->db->where('video_interview_questions_sent.status', 'answered');
        $this->db->join('video_interview_questions', 'video_interview_questions_sent.question_sid = video_interview_questions.sid');
        $data = $this->db->get('video_interview_questions_sent')->num_rows();
        
        if($data > 0){
            return true;
        } else {
            return false;
        }
    }
}