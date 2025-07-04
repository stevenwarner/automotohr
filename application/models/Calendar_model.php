<?php

class Calendar_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_events_applicants($id)
    {
        $this->db->select('*')
            ->from('portal_schedule_event')
            ->where('companys_sid', $id)
            ->where('users_type', 'applicant');
        $query = $this->db->get();
        $events = array();


        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row4) {
                $startTime24Hr = date("H:i", strtotime($row4['eventstarttime']));
                $row4['eventstarttime24Hr'] = $startTime24Hr;
                $endTime24Hr = date("H:i", strtotime($row4['eventendtime']));
                $row4['eventendtime24Hr'] = $endTime24Hr;
                $date_applied = explode('-', $row4['date']);
                $row4['frontDate'] = $date_applied['1'] . "-" . $date_applied['2'] . "-" . $date_applied['0'];
                $row4['backDate'] = $row4['date'];
                $row4['category_uc'] = ucwords($row4['category']);
                //
                $row4['f_name_uc'] = "";
                $row4['l_name_uc'] = "";
                $row4['default_phone_number'] = "";

                if ($row4['applicant_job_sid'] != NULL && $row4['applicant_job_sid'] != 0) {
                    if ($row4['users_type'] == "applicant") {
                        $this->db->select('first_name,last_name,phone_number as phone_number');
                        $this->db->where('sid', $row4['applicant_job_sid']);
                        $result = $this->db->get('portal_job_applications')->result_array();
                    } else if ($row4['users_type'] == "employee") {
                        $this->db->select('first_name,last_name,PhoneNumber as phone_number');
                        $this->db->where('sid', $row4['applicant_job_sid']);
                        $result = $this->db->get('users')->result_array();
                    }

                    if (!empty($result)) {
                        $row4['f_name_uc'] = ": " . ucwords($result[0]['first_name']);
                        $row4['l_name_uc'] = ucwords($result[0]['last_name']);
                        $row4['default_phone_number'] = ucwords($result[0]['phone_number']);
                    }
                }

                $external_participants = $this->get_event_external_participants($row4['sid']);
                $row4['external_participants'] = $external_participants;

                $events[] = $row4;
            }
        }
        return $events;
    }

    function get_events_employee($id)
    {
        $this->db->select('*')
            ->from('portal_schedule_event')
            ->where('applicant_job_sid', $id)
            ->where('users_type', 'employee');
        $array1 = $this->db->get()->result_array();
        //checking event in interviewer filed
        $where = "FIND_IN_SET(" . $id . ", interviewer)";
        $this->db->select('*')
            ->from('portal_schedule_event')
            ->where($where)
            ->where('users_type', 'employee');
        $array2 = $this->db->get()->result_array();

        foreach ($array2 as $key => $row) {
            $row['editFlag'] = "false";
            $array2[$key] = $row;
        }

        $rawEvents = array_merge($array1, $array2);
        $events = array();

        foreach ($rawEvents as $row4) {
            $startTime24Hr = date("H:i", strtotime($row4['eventstarttime']));
            $row4['eventstarttime24Hr'] = $startTime24Hr;
            $endTime24Hr = date("H:i", strtotime($row4['eventendtime']));
            $row4['eventendtime24Hr'] = $endTime24Hr;
            $date_applied = explode('-', $row4['date']);
            $row4['frontDate'] = $date_applied['1'] . "-" . $date_applied['2'] . "-" . $date_applied['0'];
            $row4['backDate'] = $row4['date'];
            $row4['category_uc'] = ucwords($row4['category']);

            if ($row4['applicant_job_sid'] != NULL && $row4['applicant_job_sid'] != 0) {
                if ($row4['users_type'] == "applicant") {
                    $this->db->select('first_name,last_name,phone_number as phone_number');
                    $this->db->where('sid', $row4['applicant_job_sid']);
                    $result = $this->db->get('portal_job_applications')->result_array();
                } else if ($row4['users_type'] == "employee") {
                    $this->db->select('first_name,last_name, PhoneNumber as phone_number');
                    $this->db->where('sid', $row4['applicant_job_sid']);
                    $result = $this->db->get('users')->result_array();
                }

                if (!empty($result)) {
                    $row4['f_name_uc'] = ": " . ucwords($result[0]['first_name']);
                    $row4['l_name_uc'] = ucwords($result[0]['last_name']);
                    $row4['default_phone_number'] = ucwords($result[0]['phone_number']);
                } else {
                    $row4['f_name_uc'] = "";
                    $row4['l_name_uc'] = "";
                    $row4['default_phone_number'] = "";
                }
            } else {
                $row4['f_name_uc'] = "";
                $row4['default_phone_number'] = "";
            }

            $external_participants = $this->get_event_external_participants($row4['sid']);
            $row4['external_participants'] = $external_participants;

            $events[] = $row4;
        }
        return $events;
    }

    function get_events_employee_for_employeer($id)
    { //checking event in applicant job sid
        $this->db->select('*')
            ->from('portal_schedule_event')
            ->where('applicant_job_sid', $id)
            ->where('users_type', 'employee');
        $array1 = $this->db->get()->result_array();

        //checking event in interviewer filed
        $where = "FIND_IN_SET(" . $id . ", interviewer)";
        $this->db->select('*')
            ->from('portal_schedule_event')
            ->where($where);
        $array2 = $this->db->get()->result_array();

        foreach ($array2 as $key => $row) {
            $row['editFlag'] = "false";
            $array2[$key] = $row;
        }

        $rawEvents = array_merge($array1, $array2);
        $events = array();

        foreach ($rawEvents as $row4) {
            $startTime24Hr = date("H:i", strtotime($row4['eventstarttime']));
            $row4['eventstarttime24Hr'] = $startTime24Hr;
            $endTime24Hr = date("H:i", strtotime($row4['eventendtime']));
            $row4['eventendtime24Hr'] = $endTime24Hr;
            $date_applied = explode('-', $row4['date']);
            $row4['frontDate'] = $date_applied['1'] . "-" . $date_applied['2'] . "-" . $date_applied['0'];
            $row4['backDate'] = $row4['date'];
            $row4['category_uc'] = ucwords($row4['category']);

            if ($row4['applicant_job_sid'] != NULL && $row4['applicant_job_sid'] != 0) {
                if ($row4['users_type'] == "applicant") {
                    $this->db->select('first_name,last_name');
                    $this->db->where('sid', $row4['applicant_job_sid']);
                    $result = $this->db->get('portal_job_applications')->result_array();
                } else if ($row4['users_type'] == "employee") {
                    $this->db->select('first_name,last_name');
                    $this->db->where('sid', $row4['applicant_job_sid']);
                    $result = $this->db->get('users')->result_array();
                }

                $first_name = isset($result[0]['first_name']) ? ucwords($result[0]['first_name']) : '';
                $last_name = isset($result[0]['last_name']) ? ucwords($result[0]['last_name']) : '';

                $row4['f_name_uc'] = ": " . $first_name;
                $row4['l_name_uc'] = $last_name;
            } else {
                $row4['f_name_uc'] = "";
                $row4['l_name_uc'] = "";
            }
            // Added on 04-04-2019
            $external_participants = $this->get_event_external_participants($row4['sid']);
            $row4['external_participants'] = $external_participants;
            $events[] = $row4;
        }
        return $events;
    }

    function get_applicants($id){
        $result = $this->db
        ->select('portal_applicant_jobs_list.sid as portal_applicant_jobs_list_sid')
        ->select('portal_applicant_jobs_list.job_sid')
        ->select('portal_job_applications.sid')
        ->select('portal_job_applications.first_name')
        ->select('portal_job_applications.last_name')
        ->select('portal_job_applications.email')
        ->select('portal_job_applications.phone_number')
        ->where('portal_applicant_jobs_list.company_sid', $id)
        ->where('portal_applicant_jobs_list.archived', 0)
        ->where('portal_job_applications.hired_status', 0)
        ->order_by('portal_applicant_jobs_list.sid', 'DESC')
        ->join('portal_job_applications', 'portal_job_applications.sid = portal_applicant_jobs_list.portal_job_applications_sid', 'left')
        ->get('portal_applicant_jobs_list');

        $result_arr = $result->result_array();
        $result = $result->free_result();
        return $result_arr;
    }

    function getCompanyAccounts($company_id)
    {
        $args = array('parent_sid' => $company_id, 'active' => 1, 'career_page_type' => 'standard_career_site');
        $this->db->select('sid,username,email,first_name,last_name,access_level,is_executive_admin,PhoneNumber,timezone');
        //$this->db->where('is_executive_admin', 0);
        $res = $this->db->get_where('users', $args);
        $ret = $res->result_array();
        return $ret;
    }

    function get_applicant_detail($applicant_sid)
    {
        $this->db->select('portal_job_applications.sid');
        $this->db->select('portal_job_applications.first_name');
        $this->db->select('portal_job_applications.last_name');
        $this->db->select('portal_job_applications.email');
        $this->db->select('portal_job_applications.phone_number');
        $this->db->select('portal_job_applications.address');
        $this->db->select('portal_job_applications.country');
        $this->db->select('portal_job_applications.city');
        $this->db->select('portal_job_applications.state');
        $this->db->select('portal_job_applications.zipcode');
        $this->db->select('portal_job_applications.resume');
        $this->db->select('portal_job_applications.cover_letter');
        $this->db->select('portal_job_applications.YouTube_Video as youtube_video');
        $this->db->select('countries.country_name');
        $this->db->select('states.state_name');
        $this->db->where('portal_job_applications.sid', $applicant_sid);
        $this->db->join('countries', 'countries.sid = portal_job_applications.country', 'left');
        $this->db->join('states', 'states.sid = portal_job_applications.state', 'left');
        $this->db->from('portal_job_applications');

        $record_obj = $this->db->get();
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            $record_arr = $record_arr[0];
            $record_arr['timezone'] = '';
            $record_arr['job_applications'] = $this->get_applicant_jobs($applicant_sid);
            return $record_arr;
        } else {
            return array();
        }
    }

    function get_employee_detail($employee_sid)
    {
        $this->db->select('users.sid');
        $this->db->select('users.first_name');
        $this->db->select('users.last_name');
        $this->db->select('users.email');
        $this->db->select('users.timezone');
        $this->db->select('users.PhoneNumber as phone_number');
        $this->db->select('users.Location_Address as address');
        $this->db->select('users.Location_Country as country');
        $this->db->select('users.Location_City as city');
        $this->db->select('users.Location_State as state');
        $this->db->select('users.Location_ZipCode as zipcode');
        $this->db->select('users.YouTubeVideo as youtube_video');
        $this->db->select('countries.country_name');
        $this->db->select('states.state_name');

        $this->db->where('users.sid', $employee_sid);

        $this->db->join('countries', 'countries.sid = users.Location_Country', 'left');
        $this->db->join('states', 'states.sid = users.Location_State', 'left');

        $this->db->from('users');

        $record_obj = $this->db->get();
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            $record_arr = $record_arr[0];
            return $record_arr;
        } else {
            return array();
        }
    }

    function verify_event_session($sid, $type, $company_sid)
    {
        if ($type == 'applicant') {
            $this->db->select('sid');
            $this->db->where('sid', $sid);
            $this->db->where('employer_sid', $company_sid);
            $this->db->from('portal_job_applications');
            return $this->db->count_all_results();
        }

        if ($type == 'employee') {
            $this->db->select('sid');
            $this->db->where('sid', $sid);
            $this->db->where('parent_sid', $company_sid);
            $this->db->from('users');
            return $this->db->count_all_results();
        }
    }

    function cancel_event($event_sid)
    {
        $this->db->where('sid', $event_sid);
        $this->db->set('event_status', 'cancelled');
        $this->db->update('portal_schedule_event');
    }

    function save_event($data)
    {
        unset($data['training_session_type']);
        $this->db->insert('portal_schedule_event', $data);
        return $this->db->insert_id();
    }

    function update_event($sid, $data)
    {
        unset($data['training_session_type']);
        $this->db->where('sid', $sid);
        return $this->db->update('portal_schedule_event', $data);
    }

    function deleteEvent($id)
    {
        $this->db->where('event_sid', $id);
        $this->db->delete('portal_schedule_event_history');
        
        $this->db->where('sid', $id);
        $this->db->delete('portal_schedule_event');
    }

    function get_event_details($event_sid)
    {
        $this->db->select('*');
        $this->db->where('sid', $event_sid);

        $event_obj = $this->db->get('portal_schedule_event');
        $event_arr = $event_obj->result_array();
        $event_obj->free_result();

        if (!empty($event_arr)) {
            return $event_arr[0];
        } else {
            return array();
        }
    }

    function get_user_information($company_sid, $user_sids = array())
    {
        $this->db->select('sid');
        $this->db->select('first_name');
        $this->db->select('timezone');
        $this->db->select('last_name');
        $this->db->select('email');
        $this->db->select('PhoneNumber');

        $this->db->where('parent_sid', $company_sid);
        $this->db->where_in('sid', $user_sids);

        $record_obj = $this->db->get('users');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        return $record_arr;
    }

    function get_applicant_jobs($applicant_sid)
    {
        $this->db->select('portal_applicant_jobs_list.sid');
        $this->db->select('portal_applicant_jobs_list.date_applied');
        $this->db->select('portal_applicant_jobs_list.job_sid');
        $this->db->select('portal_applicant_jobs_list.desired_job_title');
        $this->db->select('portal_job_listings.Title as job_title');
        $this->db->where('portal_applicant_jobs_list.portal_job_applications_sid', $applicant_sid);
        $this->db->join('portal_job_listings', 'portal_applicant_jobs_list.job_sid = portal_job_listings.sid', 'left');
        $this->db->from('portal_applicant_jobs_list');

        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        return $records_arr;
    }

    function get_employee_events($company_sid, $employee_sid, $events_date = null)
    {
        $this->db->select('*');
        $this->db->select('eventstarttime as event_start_time');
        $this->db->select('date as event_date');
        $this->db->select('eventendtime as event_end_time');
        $this->db->select('IF(date < "'.date('Y-m-d').'", 1, 0) as is_expired');
        $this->db->where('companys_sid', $company_sid);
        //$this->db->where('employers_sid', $employee_sid);

        $today = date('Y-m-d');
        if ($events_date == 'upcoming') {
            $this->db->where('date >=', $today);
            $this->db->order_by('date', 'ASC');
        } else if ($events_date == 'past') {
            $this->db->where('date <', $today);
            $this->db->order_by('date', 'DESC');
        } else if ($events_date !== null) {
            $this->db->where('date', $today);
        }

        $this->db->group_start();
        $this->db->or_where('FIND_IN_SET(' . $employee_sid . ', interviewer)');
        $this->db->or_where('applicant_job_sid', $employee_sid);
        $this->db->or_where('employers_sid', $employee_sid);
        $this->db->group_end();


        $this->db->where('applicant_job_sid >', 0);

        $records_obj = $this->db->get('portal_schedule_event');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if(!sizeof($records_arr)) return $records_arr;

        foreach ($records_arr as $k0 => $v0) {
            reset_event_datetime($records_arr[$k0], $this);
        }

        return $records_arr;
    }

    public function get_company_addresses($company_sid)
    {
        // updated 
        // on: 27-03-2019
        //
        $SQL1 = $this->db
        ->select('distinct(address)')
        ->where('companys_sid', $company_sid)
        ->where('address != ', '')
        ->from('portal_schedule_event')
        ->get_compiled_select();
        //
        $SQL2 = $this->db
        ->select('distinct(Location_Address) as address')
        ->where('sid', $company_sid)
        ->from('users')
        ->get_compiled_select();
        //
        $SQL3 = $this->db
        ->select('distinct(address)')
        ->from('company_addresses_locations')
        ->where('company_sid', $company_sid)
        ->where('status', 1)
        ->get_compiled_select();

        //
        $result = $this->db->query("$SQL2 UNION $SQL3");
        // event addresses are not included
        // $result = $this->db->query("$SQL1 UNION $SQL2 UNION $SQL3");
        $result_arr = $result->result_array();
        $result = $result->free_result();
        //
        if(!sizeof($result_arr)) return false;
        //
        $return_array = array();
        foreach ($result_arr as $k0 => $v0) {
            $return_array[] = $v0['address'];
        }
        return $return_array;

        // old
        $this->db->select('address');
        $this->db->group_by('address');
        $this->db->where('companys_sid', $company_sid);

        $records_obj = $this->db->get('portal_schedule_event');
        $records_events_arr = $records_obj->result_array();
        $records_obj->free_result();

        $this->db->select('Location_Address');
        $this->db->where('sid', $company_sid);
        $records_obj = $this->db->get('users');
        $records_users_arr = $records_obj->result_array();
        $records_obj->free_result();

        $this->db->select('address');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('status', 1);
        $records_obj = $this->db->get('company_addresses_locations');
        $records_addresses_arr = $records_obj->result_array();
        $records_obj->free_result();

        $addresses = array();

        /*
        foreach($records_events_arr as $key => $address){
            if(!empty($address['address'])){
                if(!in_array($address['address'], $addresses)) {
                    $addresses[] = $address['address'];
                }
            }
        }
        */

        foreach ($records_users_arr as $key => $address) {
            if (!empty($address['Location_Address'])) {
                if (!in_array($address['Location_Address'], $addresses)) {
                    $addresses[] = $address['Location_Address'];
                }
            }
        }

        foreach ($records_addresses_arr as $key => $address) {
            if (!empty($address['address'])) {
                if (!in_array($address['address'], $addresses)) {
                    $addresses[] = $address['address'];
                }
            }
        }


        return $addresses;
    }

    public function save_company_address($company_sid, $address)
    {
        $this->db->select('sid');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('address', $address);
        $this->db->from('company_addresses_locations');
        $count = $this->db->count_all_results();

        if ($count <= 0) {
            $data_to_save = array();
            $data_to_save['company_sid'] = $company_sid;
            $data_to_save['address'] = $address;
            $data_to_save['status'] = 1;
            $data_to_save['date_created'] = date('Y-m-d H:i:s');

            $this->db->insert('company_addresses_locations', $data_to_save);
        }
    }

    public function check_if_email_is_of_an_employee($company_sid, $email_address)
    {
        $this->db->select('sid');
        $this->db->where('parent_sid', $company_sid);
        $this->db->where('email', $email_address);

        $this->db->from('users');

        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return false;
        }
    }

    public function add_event_external_participants($company_sid, $employer_sid, $event_sid, $name, $email, $show_email)
    {
        $data_to_save = array();
        $data_to_save['company_sid'] = $company_sid;
        $data_to_save['employer_sid'] = $employer_sid;
        $data_to_save['event_sid'] = $event_sid;
        $data_to_save['name'] = trim($name);
        $data_to_save['email'] = trim($email);
        $data_to_save['show_email'] = $show_email;

        $this->db->insert('portal_schedule_event_external_participants', $data_to_save);
    }

    public function remove_all_external_participants($company_sid, $employer_sid, $event_sid)
    {
        $this->db->where('company_sid', $company_sid);
        $this->db->where('employer_sid', $employer_sid);
        $this->db->where('event_sid', $event_sid);

        $this->db->delete('portal_schedule_event_external_participants');
    }

    public function append_participant_to_event($event_sid, $participant_sid){
        $this->db->select('interviewer');
        $this->db->where('sid', $event_sid);
        $this->db->from('portal_schedule_event');

        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if(!empty($records_arr)){
            $participants = $records_arr[0]['interviewer'];
            if(!empty($participants)){
                $participants = explode(',', $participants);

                if(!in_array($participant_sid, $participants)){
                    $participants[] = $participant_sid;

                    $this->db->where('sid', $event_sid);
                    $this->db->set('interviewer', implode(',', $participants));
                    $this->db->update('portal_schedule_event');
                }
            }
        }
    }

    public function get_event_external_participants($event_sid){
        // $this->db->where('event_sid', $event_sid);
        // $this->db->from('portal_schedule_event_external_participants');

        // $records_obj = $this->db->get();
        // $records_arr = $records_obj->result_array();
        // $records_obj->free_result();

        // if(!empty($records_arr)){
        //     return $records_arr;
        // } else {
        //     return array();
        // }

        // update on: 28-03-2019
        //
        $records_obj = $this->db
        ->select('sid, name, email, show_email')
        ->where('event_sid', $event_sid)
        ->from('portal_schedule_event_external_participants')
        ->get();
        //
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        //
        return $records_arr;
    }
    
    function get_all_company_applicants($company_id) {

        $this->db->select('portal_job_applications.sid, portal_applicant_jobs_list.sid as list_sid, portal_applicant_jobs_list.job_sid, portal_job_listings.Title as job_title, portal_applicant_jobs_list.desired_job_title');
       // $this->db->where('portal_applicant_jobs_list.job_sid >',0);
        $this->db->where('portal_applicant_jobs_list.archived', 0);
        $this->db->where('portal_job_applications.hired_status', 0);
        $this->db->where('portal_applicant_jobs_list.company_sid',$company_id);
        $this->db->order_by('portal_applicant_jobs_list.date_applied', 'DESC');
        $this->db->join('portal_job_applications', 'portal_job_applications.sid = portal_applicant_jobs_list.portal_job_applications_sid', 'left');
        $this->db->join('portal_job_listings', 'portal_job_listings.sid = portal_applicant_jobs_list.job_sid', 'left');
        $applicant_job_list = $this->db->get('portal_applicant_jobs_list')->result_array();
       // echo $this->db->last_query();
        return $applicant_job_list;

        // update on: 28-03-2019
        // $result = $this->db
        // ->select('portal_job_applications.sid, 
        //     portal_applicant_jobs_list.sid as list_sid, 
        //     portal_applicant_jobs_list.job_sid, 
        // ')
        // ->select('IF(portal_job_listings.Title = "", portal_applicant_jobs_list.desired_job_title, portal_job_listings.Title) as job_title', false)
        // ->where('portal_applicant_jobs_list.archived', 0)
        // ->where('portal_job_applications.hired_status', 0)
        // ->where('portal_applicant_jobs_list.company_sid',$company_id)
        // ->order_by('portal_applicant_jobs_list.date_applied', 'DESC')
        // ->join('portal_job_applications', 'portal_job_applications.sid = portal_applicant_jobs_list.portal_job_applications_sid', 'left')
        // ->join('portal_job_listings', 'portal_job_listings.sid = portal_applicant_jobs_list.job_sid', 'left')
        // ->get('portal_applicant_jobs_list');
        // //
        // $result_arr = $result->result_array();
        // $result = $result->free_result();
        // return $result_arr;
    }
    
    function get_all_applicant_jobs($applicant_sid, $company_id) {
        $this->db->select('portal_applicant_jobs_list.portal_job_applications_sid as applicant_sid, portal_applicant_jobs_list.sid as sid, portal_applicant_jobs_list.job_sid, portal_job_listings.Title, portal_applicant_jobs_list.desired_job_title as job_title');
       // $this->db->where('portal_applicant_jobs_list.job_sid >',0);
        $this->db->where('portal_applicant_jobs_list.archived', 0);
        $this->db->where('portal_applicant_jobs_list.company_sid',$company_id);
        $this->db->where('portal_applicant_jobs_list.portal_job_applications_sid',$applicant_sid);
        $this->db->order_by('portal_applicant_jobs_list.date_applied', 'DESC');
        $this->db->join('portal_job_listings', 'portal_job_listings.sid = portal_applicant_jobs_list.job_sid', 'left');
        $applicant_job_list = $this->db->get('portal_applicant_jobs_list')->result_array();
       // echo $this->db->last_query();
        return $applicant_job_list;
    }

    function fetch_all_today_events(){
        // TOBE deleted after testing
        // return $this->db
        // ->select('portal_schedule_event.sid, 
        //     applicant_email, 
        //     title, 
        //     date, 
        //     eventstarttime, 
        //     eventendtime, 
        //     duration, 
        //     CompanyName, 
        //     companys_sid, 
        //     category, 
        //     users_type, 
        //     applicant_job_sid, interviewer')
        // ->limit(1)
        // ->order_by('sid', 'desc')
        // ->where('date = "' . date('Y-m-d', strtotime('now')) . '"')
        // ->join('users','users.sid = portal_schedule_event.companys_sid','left')
        // ->get('portal_schedule_event')
        // ->result_array();

        //
        $result = $this->db
        ->select('portal_schedule_event.sid, applicant_email, title, date, eventstarttime, eventendtime, duration, CompanyName, companys_sid, category, users_type, applicant_job_sid, interviewer, event_status')
        ->where('reminder_flag',1)
        ->where('sent_flag',0)
        ->where('date BETWEEN "' . date('Y-m-d') . '" and "' . date('Y-m-d') . '"')
        ->join('users','users.sid = portal_schedule_event.companys_sid','left')
        ->get('portal_schedule_event');
        //
        $result_arr = $result->result_array();
        $result = $result->free_result();


        if(!sizeof($result_arr)) return false;

        foreach($result_arr as $key => $event){
            $result = $this->db
            ->select('concat(first_name," ",last_name) as full_name')
            ->where('sid', $event['applicant_job_sid'])
            ->get(
                $event['users_type'] == 'applicant' ? 'portal_job_applications' : 'users'
            );
            $applicant = $result->row_array();
            $result = $result->free_result();
        
            $result_arr[$key]['name'] = $applicant['full_name'];
        }
        return $result_arr;
    }

    function update_event_reminder_sent_status($sid){
        $sent_status = array('sent_flag' => 1);
        $this->db->where('sid', $sid);
        $this->db->update('portal_schedule_event',$sent_status);
    }

    /**
     * get company accounts  
     * for calendars
     * 
     * @param $company_sid Integer
     *
     * @return Array|Bool
     */
    function get_company_accounts($company_sid) {
        $result = $this->db
        ->select('sid as employer_id')
        ->select('email as email_address')
        ->select('username')
        ->select('first_name')
        ->select('last_name')
        ->select('job_title')
        ->select('access_level')
        ->select('access_level_plus')
        ->select('is_executive_admin')
        ->select('pay_plan_flag')
        ->select('PhoneNumber as phone_number')
        ->select('concat(first_name," ",last_name) as full_name')
        ->select('case when is_executive_admin = 1 then "Executive Admin" else access_level end as employee_type', false)
        ->select('timezone')
        ->where('parent_sid', $company_sid)
        ->where('active', 1)
        ->where('career_page_type', 'standard_career_site')
        ->from('users')
        ->order_by('full_name', 'ASC')
        ->get();
        // fetch result
        $result_arr = $result->result_array();
        // free result from memory 
        // and flush variable data
        $result = $result->free_result();
        // return output
        return $result_arr;
    }


    /**
     * get company accounts  
     * for calendars
     * 
     * @param $company_sid Integer
     *
     * @return Array|Bool
     */
    function fetch_company_addresses($company_sid) {
        //
        $SQL2 = $this->db
        ->select('distinct(Location_Address) as address')
        ->where('sid', $company_sid)
        ->from('users')
        ->get_compiled_select();
        //
        $SQL3 = $this->db
        ->select('distinct(address)')
        ->from('company_addresses_locations')
        ->where('company_sid', $company_sid)
        ->where('status', 1)
        ->get_compiled_select();

        //
        $result = $this->db->query("$SQL2 UNION $SQL3");
        // event addresses are not included
        $result_arr = $result->result_array();
        $result = $result->free_result();
        //
        if(!sizeof($result_arr)) return false;
        //
        $return_array = array();
        foreach ($result_arr as $k0 => $v0) {
            $return_array[] = $v0['address'];
        }
        return $return_array;
    }


    /**
     * get events
     * for calendars
     * 
     * @param $type String
     * @param $yesr String
     * @param $month String
     * @param $day String
     * @param $week_start String
     * @param $week_end String
     * @param $company_id Integer
     * @param $employer_id Integer
     * @param $event_type String
     * @param $access_level String
     * @param $exec Bool
     *
     * @return Array|Bool
     */
    function get_events($type, $year, $month, $day, $week_start = FALSE, $week_end = FALSE, $company_id, $employer_id, $event_type, $access_level, $exec = FALSE){
        //
        $this->db
        ->select('sid')
        ->select('eventstarttime')
        ->select('title')
        ->select('date')
        ->select('eventendtime')
        ->select('event_timezone')
        ->select('interviewer')
        ->select('applicant_job_sid')
        ->select('users_type')
        ->select('event_status')
        ->select('category')
        ->select('applicant_jobs_list')
        ->select('date as back_date')
        ->select('date_format(date,"%m-%d-%Y") as front_date')
        ->select('CONCAT(UCASE(SUBSTRING(category, 1, 1)),SUBSTRING(category, 2)) as category_uc')
        ->select('is_recur')
        ->select('recur_type')
        ->select('recur_start_date')
        ->select('recur_end_date')
        ->select('recur_list')
        ->from('portal_schedule_event');
        // for employess
        if($event_type == 'employee' || $access_level == 'hiring manager' || $access_level == 'employee'){
            $this->db
            ->select('"false" as editFlag')
            ->group_start()
            ->where("FIND_IN_SET(" . $employer_id . ", interviewer)")
            ->or_where("employers_sid", $employer_id)
            ->or_where("applicant_job_sid", $employer_id)
            ->group_end();
        }
        // for employee events
        // for personal events
        if($event_type == 'employee' || $access_level == 'employee') {
            $this->db->group_start();
            $this->db->where('users_type', 'employee');
            $this->db->or_where('users_type', 'personal');
            $this->db->group_end();
        }
        // for applicants events
        if($event_type == 'applicant') $this->db->where('users_type', 'applicant');
        // Set companys check
        $this->db->where('companys_sid', $company_id);
        if($week_start > $week_end){
            //$year = date('Y', strtotime($year.'-01-01 -1 year'));
        }
        // check for type
        if ($month == 12 && $type != "day") {
            $endYear = addTimeToDate($year."-12-31","1Y", "Y");
        } else {
            $endYear = $year;
        }
        //
        if ($month == 1 && $type != "day") {
            $startYear = subTimeToDate($year."-01-01","1Y", "Y");
        } else {
            $startYear = $year;
        }
        //
        if($type == 'day') {
            $this->db->where('date = ', ($year.'-'.$month.'-'.$day));
        }
        else{ // month, week
            $from_date = $startYear.'-'.$week_start;
            $to_date   = $endYear.'-'.$week_end;
            //
            if(substr($week_start, 0, 2) == '12'){ $to_date   = date('Y', strtotime('+1 year')).'-'.$week_end;}
            $this->db->where('date between "'.$from_date.'" and "'.$to_date.'"', null);
        }

        //
        if($exec) return $this->db->get_compiled_select();
        //
        $result = $this->db->get();
        $events = $result->result_array();
        $result = $result->free_result();


        // Fetch recur events
        // $this->db
        // ->select('sid')
        // ->select('eventstarttime')
        // ->select('title')
        // ->select('date')
        // ->select('eventendtime')
        // ->select('interviewer')
        // ->select('applicant_job_sid')
        // ->select('users_type')
        // ->select('event_status')
        // ->select('category')
        // ->select('applicant_jobs_list')
        // ->select('date as back_date')
        // ->select('date_format(date,"%m-%d-%Y") as front_date')
        // ->select('CONCAT(UCASE(SUBSTRING(category, 1, 1)),SUBSTRING(category, 2)) as category_uc')
        // ->select('is_recur')
        // ->select('recur_type')
        // ->select('recur_start_date')
        // ->select('recur_end_date')
        // ->select('recur_list')
        // ->from('portal_schedule_event');
        // // for employess
        // if($event_type == 'employee' || $access_level == 'hiring manager'){
        //     $this->db
        //     ->select('"false" as editFlag')
        //     ->where("FIND_IN_SET(" . $employer_id . ", interviewer)");
        // }
        // // for employee events
        // if($event_type == 'employee') $this->db->where('users_type', 'employee');
        // // for applicants events
        // if($event_type == 'applicant') $this->db->where('users_type', 'applicant');
        // // for personal events
        // if($event_type == 'personal') $this->db->where('users_type', 'personal');
        // // Set companys check
        // $this->db->where('companys_sid', $company_id);
        // // check for type
        // if($type == 'day')
        //     $this->db->where('recur_start_date = ', ($year.'-'.$month.'-'.$day));
        // else{ // month, week
        //     $from_date = $year.'-'.$week_start;
        //     $to_date   = $year.'-'.$week_end;
        //     $this->db->where('recur_end_date', null);
        //     // $this->db->where('recur_start_date = "'.$from_date.'"', null);
        //     // $this->db->where('recur_end_date <= "'.$to_date.'"', null);
        // }

        // //
        // $result = $this->db->get();
        // $recur_events = $result->result_array();
        // $result = $result->free_result();

        // // merge
        // $events = array_merge($events, $recur_events);
        //
        if(!sizeof($events)) return false;
        $date_check = date('Y-m-d');
        //
        foreach ($events as $k0 => $v0) {
            // get status changes 
            $result = $this->db
            ->select('event_type')
            ->where('event_sid', $v0['sid'])
            ->where('user_sid', $v0['applicant_job_sid'])
            ->from('portal_event_history')
            ->order_by('created_at', 'DESC')
            ->limit('1')
            ->get();
            $portal_event_history = $result->row_array()['event_type'];
            $result = $result->free_result();
            //
            $portal_event_history_status = $this->db
            ->select('status')
            ->where('event_sid', $v0['sid'])
            ->where('status', '0')
            ->where('event_type !=', 'confirmed')
            ->from('portal_event_history')
            ->count_all_results();
            // if($v0['event_status'] != 'confirmed') $v0['category'] = 'notconfirmed';
            // if($v0['event_status'] == 'cancelled') $v0['category'] = 'cancelled';
            // Don't need expire status
            // Updated on: 02-05-2019
            // $v0['event_status'] = $v0['date'] < $date_check ? 'expired' : $v0['event_status'];
            // get event type color
            $events[$k0]['color'] = $v0['color'] = get_calendar_event_color( $v0['category'] );
            //
            $events[$k0]['event_start_time_24Hr'] = $v0['event_start_time_24Hr'] = DateTime::createFromFormat("h:iA", $v0['eventstarttime'])->format('H:i');
            $events[$k0]['event_end_time_24Hr']   = $v0['event_end_time_24Hr']   = DateTime::createFromFormat("h:iA", $v0['eventendtime'])->format('H:i');
            $events[$k0]['external_participants'] = $v0['external_participants'] = $this->get_event_external_participants_names($v0['sid']);
            $v0['interviewers_names'] = $this->get_interviewers_name($v0['interviewer']);
            $v0['applicant_job_names'] = $this->get_applicant_job_names($v0['applicant_jobs_list']);

            //
            $events[$k0]['default_phone_number'] 
            = $v0['default_phone_number']
            = $v0['l_name_uc']
            = $v0['f_name_uc']
            = $events[$k0]['l_name_uc'] 
            = $events[$k0]['f_name_uc'] = "";
            //
            // if ($v0['applicant_job_sid'] == NULL || $v0['applicant_job_sid'] == 0) { unset($events[$k0]); continue;}
            //
            $SQL_select = "PhoneNumber as phone_number";
            $SQL_from = "users";
            //
            if ($v0['users_type'] == "applicant") {
                $SQL_select = "phone_number as phone_number";
                $SQL_from = "portal_job_applications";
            }
            // set default values
            $event_category = $v0['category_uc'];
            $interviewers_name = $v0['category'] != 'meeting' ? 'Interviewer(s):' : 'Attendee(s):';
            $job_title = 'Job Title: '.( $v0['applicant_job_names'] ? $v0['applicant_job_names'] : '' );
            // set interviewers name
            if($v0['interviewers_names']){
                $interviewers_name .= ' - ' . $v0['interviewers_names'];
            }
            // Set for external employees
            if($v0['external_participants']){
                $interviewers_name .=  ' - ' . $v0['external_participants'];
            }
            // set event category
            if ($event_category == 'Interview') $event_category = 'In Person Interview';
            else if ($event_category == 'Interview-phone') $event_category = 'Phone Interview';
            else if ($event_category == 'Interview-voip') $event_category = 'Voip Interview';

            // back event
            $events[$k0]['json'] = array();
            $events[$k0]['json']['title'] = $this->set_title($events, $k0, $v0, $event_category, $interviewers_name, $job_title);
            $events[$k0]['json']['start'] = $v0['date'].'T'. $v0['event_start_time_24Hr'];
            $events[$k0]['json']['end']   = $v0['date'].'T'. $v0['event_end_time_24Hr'];
            $events[$k0]['json']['event_timezone']   = $v0['event_timezone'];
            $events[$k0]['json']['color'] = $v0['color'];
            $events[$k0]['json']['event_id'] = $v0['sid'];
            $events[$k0]['json']['category'] = $events[$k0]['category'];
            $events[$k0]['json']['date']     = $v0['date'];
            $events[$k0]['json']['editable'] = true;
            $events[$k0]['json']['requests'] = $portal_event_history_status;
            // $events[$k0]['json']['status'] = $v0['event_status'];
            $events[$k0]['json']['expired_status'] = $v0['date'] < $date_check ? true : false;
            // $events[$k0]['json']['editable'] = $v0['date'] < $date_check ? false : true;
            $events[$k0]['json']['eventdate'] = $v0['front_date'];
            $events[$k0]['json']['is_recur']  = 0;
            // $events[$k0]['json']['is_recur']  = $v0['is_recur'];
            $events[$k0]['json']['recur_type']  = $v0['recur_type'];
            $events[$k0]['json']['recur_start_date'] = $v0['recur_start_date'] === NULL ? NULL : $v0['recur_start_date'].'T23:59:59';
            $events[$k0]['json']['recur_end_date']   = $v0['recur_end_date'] === NULL ? NULL : $v0['recur_end_date'].'T23:59:59';
            $events[$k0]['json']['recur_list']       = empty($v0['recur_list']) ? NULL : $v0['recur_list'];
            // Added on: 02-05-2019
            $events[$k0]['json']['status']  = $portal_event_history != NULL ? (
                $portal_event_history == 'cannotattend' ? 'cancelled' : (
                    $portal_event_history == 'reschedule' ? 'rescheduled' : $portal_event_history
                )
            ) : $v0['event_status'];
            //
            $events[$k0]['json']['last_status']  = $portal_event_history != NULL ? (
                $portal_event_history == 'cannotattend' ? 'cancelled' : (
                    $portal_event_history == 'reschedule' ? 'rescheduled' : $portal_event_history
                )
            ) : 'pending';


            // Check for date check
            // if($events[$k0]['json']['recur_list'] !== NULL){
            //     $recur_json = @json_decode($events[$k0]['json']['recur_list'], true); 
            //     $events[$k0]['json']['dow'] = ($recur_json['Days'] == '*') ? array(0,1,2,3,4,5,6) : $recur_json['Days'];
            // }


            //
            $result = $this->db
            ->select("CONCAT(UCASE(SUBSTRING(first_name, 1, 1)),SUBSTRING(first_name, 2)) as first_name, CONCAT(UCASE(SUBSTRING(last_name, 1, 1)),SUBSTRING(last_name, 2)) as last_name")
            ->select($SQL_select)
            ->from($SQL_from)
            ->where('sid', $v0['applicant_job_sid'])
            ->get();

            $result_arr = $result->row_array();
            $result = $result->free_result();

            if (!sizeof($result_arr)) {
                $events[$k0] = $events[$k0]['json'];
                // Reset event datetimes
                reset_event_datetime($events[$k0], $this);
                continue;
            }

            $events[$k0]['f_name_uc'] = ": " . $result_arr['first_name'];
            $events[$k0]['l_name_uc'] = $result_arr['last_name'];
            $events[$k0]['default_phone_number'] = $result['phone_number'];

            $events[$k0]['json']['title'] = $this->set_title($events, $k0, $v0, $event_category, $interviewers_name, $job_title);
            $events[$k0] = $events[$k0]['json'];

            // Reset event datetimes
            reset_event_datetime($events[$k0], $this);
        }

        $events = array_values($events);

        return $events;
    }

    /**
     * set title
     *  
     * @param $events 
     * @param $k0 
     * @param $v0 
     * @param $event_category 
     * @param $interviewers_name 
     * @param $job_title 
     *  
     * @return String
     */
    private function set_title($events, $k0, $v0, $event_category, $interviewers_name, $job_title){
        return (
                $events[$k0]['users_type'] == 'personal' ? 'Personal Appointment ' : (
                    addslashes($events[$k0]['f_name_uc']).' '.
                    addslashes($events[$k0]['l_name_uc']).' - '
                )
            ). 
            $event_category.
            ', '.$v0['title'].', '.
            $interviewers_name.
            (($v0['category'] != 'meeting' ||  $events[$k0]['users_type'] == 'personal') ? ', ' . $job_title : '');
    }

    /**
     * get interviewers details
     *  
     * @param $sid 
     *  
     * @return array
     */
    private function employee_data_by_id($sid){
        $result = $this->db
        ->select('concat(first_name," ",last_name) as full_name')
        ->where('sid', $sid)
        ->where('active', 1)
        ->where('career_page_type', 'standard_career_site')
        ->from('users')
        ->limit(1)
        ->get();
        // fetch result
        $result_arr = $result->row_array();
        // free result from memory 
        // and flush variable data
        $result = $result->free_result();
        // return output
        return $result_arr;
    }

    /**
     * get job details by id
     *  
     * @param $job_list_sid 
     * @param $is_applicant 
     *  
     * @return String
     */
    function get_job_by_job_id($job_list_sid, $is_applicant = FALSE) {
        $result = $this->db
        ->select('
            portal_job_applications.sid, 
            portal_applicant_jobs_list.sid as list_sid, 
            portal_applicant_jobs_list.job_sid')
        ->select('IF(portal_job_listings.Title = "", portal_applicant_jobs_list.desired_job_title, portal_job_listings.Title) as job_title', false)
        ->from('portal_applicant_jobs_list')
        ->where('portal_applicant_jobs_list.archived', 0)
        ->where('portal_job_applications.hired_status', 0)
        ->where(
            $is_applicant ? 'portal_job_applications.sid' : 'portal_applicant_jobs_list.sid',
            $job_list_sid
        )
        ->order_by('portal_applicant_jobs_list.date_applied', 'DESC')
        ->join('portal_job_applications', 'portal_job_applications.sid = portal_applicant_jobs_list.portal_job_applications_sid', 'left')
        ->join('portal_job_listings', 'portal_job_listings.sid = portal_applicant_jobs_list.job_sid', 'left')
        ->get();
        $result_arr = $result->row_array();
        $result = $result->free_result();
        return $result_arr;
    }

    /**
     * get applicant job tittles
     *  
     * @param $job_list_sid 
     * @param $is_applicant 
     *  
     * @return String
     */
    private function get_applicant_job_names($job_list_sids, $is_applicant = FALSE) {
        // check for empty
        if(empty($job_list_sids)) return false;
        //
        $result = $this->db
        ->select('
            portal_job_applications.sid, 
            portal_applicant_jobs_list.sid as list_sid, 
            portal_applicant_jobs_list.job_sid')
        ->select('GROUP_CONCAT(IF(portal_job_listings.Title = "", portal_applicant_jobs_list.desired_job_title, portal_job_listings.Title) SEPARATOR ", ") as job_title', false)
        ->from('portal_applicant_jobs_list')
        ->where('portal_applicant_jobs_list.archived', 0)
        ->where('portal_job_applications.hired_status', 0)
        ->where(
            $is_applicant ? 'portal_job_applications.sid' : 'portal_applicant_jobs_list.sid',
            $job_list_sids
        )
        ->order_by('portal_applicant_jobs_list.date_applied', 'DESC')
        ->join('portal_job_applications', 'portal_job_applications.sid = portal_applicant_jobs_list.portal_job_applications_sid', 'left')
        ->join('portal_job_listings', 'portal_job_listings.sid = portal_applicant_jobs_list.job_sid', 'left')
        ->get();
        //
        $result_arr = $result->row_array();
        $result = $result->free_result();
        //
        if($result_arr['job_title'] == NULL) return false;

        return $result_arr['job_title'];
    }

    /**
     * get interviewers names as string
     *  
     * @param $event_sid Integer 
     *  
     * @return String
     */
    private function get_interviewers_name($interviewers){
        // check fror empty
        if(empty($interviewers)) return false;
        //
        $result = $this->db
        ->select('GROUP_CONCAT(concat(first_name," ",last_name) SEPARATOR ", ") as full_name')
        ->where_in('sid', explode(',', $interviewers))
        ->where('active', 1)
        ->where('career_page_type', 'standard_career_site')
        ->from('users')
        ->get();
        // fetch result
        $result_arr = $result->row_array();
        // free result from memory 
        // and flush variable data
        $result = $result->free_result();
        //
        if($result_arr['full_name'] == NULL) return false;
        //
        return $result_arr['full_name'];
    }

    /**
     * get external interviewers names as string
     *  
     * @param $event_sid Integer 
     *  
     * @return String
     */
    private function get_event_external_participants_names($event_sid){
        // check fror empty
        if(empty($event_sid)) return false;
        //
        $records_obj = $this->db
        ->select('GROUP_CONCAT(name SEPARATOR ", ") as name')
        ->where('event_sid', $event_sid)
        ->from('portal_schedule_event_external_participants')
        ->get();
        //
        $records_arr = $records_obj->row_array();
        $records_obj->free_result();

        if($records_arr['name'] == NULL) return false;
        //
        return $records_arr['name'];
    }



    /**
     * Search applicant in database
     *  
     * @param $company_id Integer
     * @param $query String 
     *  
     * @return Array
     */
    function get_applicants_by_query($company_id, $query){
        $result = $this->db
        ->select('portal_applicant_jobs_list.sid as portal_applicant_jobs_list_sid')
        ->select('portal_applicant_jobs_list.job_sid')
        ->select('portal_job_applications.sid as id')
        ->select('concat( portal_job_applications.first_name, " ", portal_job_applications.last_name, " (",portal_job_applications.email,")") as value ')
        ->select('portal_job_applications.phone_number')
        ->where('portal_applicant_jobs_list.company_sid', $company_id)
        ->where('portal_applicant_jobs_list.archived', 0)
        ->where('portal_job_applications.hired_status', 0)
        ->group_start()
        ->like('concat(portal_job_applications.first_name, " ", portal_job_applications.last_name)', $query)
        ->or_like('portal_job_applications.email', $query)
        ->group_end()
        ->order_by('value', 'DESC')
        ->group_by('id')
        ->join('portal_job_applications', 'portal_job_applications.sid = portal_applicant_jobs_list.portal_job_applications_sid', 'left')
        ->limit(10)
        ->get('portal_applicant_jobs_list');


        $result_arr = $result->result_array();
        $result = $result->free_result();

        if(!sizeof($result_arr)) return false;

        foreach ($result_arr as $k0 => $v0) {
            $result = $this->db
            ->select('
                portal_applicant_jobs_list.sid as list_sid, 
                portal_applicant_jobs_list.job_sid, 
                (CASE 
                WHEN portal_job_listings.Title = "" THEN portal_applicant_jobs_list.desired_job_title
                WHEN portal_job_listings.Title IS NULL THEN portal_applicant_jobs_list.desired_job_title
                ELSE portal_job_listings.Title
                END) as job_title
            ', false)
            ->order_by('portal_applicant_jobs_list.date_applied', 'DESC')
            ->join('portal_job_applications', 'portal_job_applications.sid = portal_applicant_jobs_list.portal_job_applications_sid', 'left')
            ->join('portal_job_listings', 'portal_job_listings.sid = portal_applicant_jobs_list.job_sid', 'left')
            ->where('portal_applicant_jobs_list.company_sid', $company_id)
            ->where('portal_job_applications.sid', $v0['id'])
            ->get('portal_applicant_jobs_list');

            $result2_arr = $result->result_array();
            $result = $result->free_result();

            $result_arr[$k0]['jobs'][] = $result2_arr;
        }

        // update on: 28-03-2019
        //
        //
        return $result_arr;
    }

    /**
     * Search employees in database
     *  
     * @param $company_id Integer
     * @param $query String 
     * @param $ids String 
     * @param $interviewer_check Bool 
     *  
     * @return Array
     */
    function get_employees_by_query($company_id, $query, $ids = '', $interviewer_check = FALSE){
        //
        $employer_id = $this->session->userdata('logged_in')['employer_detail']['sid'];
        //
        $this->db
        ->select('sid as id')
        ->select('PhoneNumber as phone_number');
        if(!$interviewer_check) $this->db->select('concat(first_name," ",last_name, " (", email ,")") as value');
        else {
            $this->db->select('
                IF(
                    sid <> '.$employer_id.', 
                    (
                        concat( first_name," ",last_name, " (", 
                        ( IF(is_executive_admin = 1, "Executive Admin", access_level) ) 
                        ,")" )
                     )
                     ,
                     "You"
                )
            as value', false)
            ->select('email as email_address');
        }
        $this->db
        // ->select('case when is_executive_admin = 1 then "Executive Admin" else access_level end as employee_type', false)
        ->where('parent_sid', $company_id)
        ->where('active', 1)
        ->where('career_page_type', 'standard_career_site')
        ->group_start()
        ->like('concat(first_name, " ", last_name)', $query)
        ->or_like('email', $query)
        ->group_end();
        //
        if(!$interviewer_check) $this->db->group_start()->where('sid <>', $employer_id)->group_end();
        if($interviewer_check) $this->db->group_start()->where_not_in('sid', explode(',', $ids))->group_end();
        //
        $this->db->from('users')
        ->order_by('value', 'ASC')
        ->limit('10');

        $result = $this->db->get();
        //
        $result_arr = $result->result_array();
        $result = $result->free_result();
        //
        return $result_arr;
    }



    /**
     * get event detail
     * 
     * @param $event_id Integer
     * @param $reset_datetime Bool Optional
     * @param $exec Bool Optional
     * 
     * @return Array|Bool
     */
    function get_event_detail($event_id, $reset_datetime = true, $exec = FALSE){
        // Default columns
        $columns = "
        portal_schedule_event.employers_sid, 
        portal_schedule_event.messageFile as message_file, 
        portal_schedule_event.parent_sid as parent_event_sid, 
        portal_schedule_event.title as event_title, 
        portal_schedule_event.applicant_job_sid, 
        portal_schedule_event.event_timezone, 
        portal_schedule_event.applicant_jobs_list, 
        portal_schedule_event.description, 
        portal_schedule_event.users_type, 
        portal_schedule_event.users_phone, 
        portal_schedule_event.users_name, 
        portal_schedule_event.applicant_email as users_email, 
        portal_schedule_event.date, 
        portal_schedule_event.eventstarttime as event_start_time, 
        portal_schedule_event.eventendtime as event_end_time, 
        portal_schedule_event.interviewer, 
        portal_schedule_event.interviewer_show_email, 
        portal_schedule_event.commentCheck as comment_check, 
        portal_schedule_event.messageCheck as message_check, 
        portal_schedule_event.comment, 
        portal_schedule_event.subject, 
        portal_schedule_event.message, 
        portal_schedule_event.address, 
        portal_schedule_event.goToMeetingCheck as meeting_check, 
        portal_schedule_event.meetingId as meeting_id, 
        portal_schedule_event.meetingCallNumber as meeting_call_number, 
        portal_schedule_event.meetingURL as meeting_url, 
        portal_schedule_event.reminder_flag, 
        portal_schedule_event.event_status, 
        portal_schedule_event.duration, 
        portal_schedule_event.event_status, 
        CONCAT(UCASE(SUBSTRING(portal_schedule_event.category, 1, 1)),SUBSTRING(portal_schedule_event.category, 2)) as category_uc, 
        portal_schedule_event.is_recur, 
        portal_schedule_event.recur_type, 
        portal_schedule_event.recur_start_date, 
        portal_schedule_event.recur_end_date, 
        portal_schedule_event.recur_list, 
        portal_schedule_event.companys_sid as company_id, 
        portal_schedule_event.learning_center_training_sessions, 
        learning_center_training_sessions.online_video_sid";
        //
        if(is_array($event_id)){
            $event_array = $event_id;
            $event_id = $event_array['event_id'];
            $columns = $event_array['columns'];
        }

        //
        $this->db
        ->select($columns)
        ->where('portal_schedule_event.sid', $event_id)
        ->from('portal_schedule_event')
        ->join('learning_center_training_sessions', 'portal_schedule_event.learning_center_training_sessions = learning_center_training_sessions.sid', 'left')
        ;
        //
        if($exec) return $this->db->get_compiled_select();
        //
        $result = $this->db->get();
        $event = $result->row_array();
        $result = $result->free_result();
        //
        if(!sizeof($event)) return false;

        if(!isset($event_array)){
            // get applicant or employee details
            if($event['users_type'] == 'applicant'){
                $result = $this->db
                ->select('concat( portal_job_applications.first_name, " ", portal_job_applications.last_name, " (",portal_job_applications.email,")") as value ')
                ->select('portal_job_applications.sid as id')
                ->select('portal_job_applications.email as email_address')
                ->where('portal_job_applications.sid', $event['applicant_job_sid'])
                ->from('portal_job_applications')
                ->get();
                ///
                $event['applicant_details'] = $result->row_array();
                $result = $result->free_result();


                if(!sizeof($event['applicant_details'])) return false;
                //$event['applicant_details']['timezone'] = $this->get_timezone('company', $event['company_id']);
                $event['applicant_details']['timezone'] = $event['event_timezone'];
                //fetch jobs
                $result = $this->db
                ->select('
                    portal_applicant_jobs_list.sid as list_sid, 
                    portal_applicant_jobs_list.job_sid, 
                ')
                // ->select('IF(portal_job_listings.Title = "", portal_applicant_jobs_list.desired_job_title, portal_job_listings.Title) as job_title', false)
                ->select('
                    (CASE 
                    WHEN portal_job_listings.Title = "" THEN portal_applicant_jobs_list.desired_job_title
                    WHEN portal_job_listings.Title IS NULL THEN portal_applicant_jobs_list.desired_job_title
                    ELSE portal_job_listings.Title
                    END) as job_title
                ', FALSE)
                ->order_by('portal_applicant_jobs_list.date_applied', 'DESC')
                ->join('portal_job_applications', 'portal_job_applications.sid = portal_applicant_jobs_list.portal_job_applications_sid', 'left')
                ->join('portal_job_listings', 'portal_job_listings.sid = portal_applicant_jobs_list.job_sid', 'left')
                ->where('portal_job_applications.sid', $event['applicant_job_sid'])
                // ->where('portal_applicant_jobs_list.job_sid  <> 0', null)
                ->get('portal_applicant_jobs_list');

                $event['applicant_details']['jobs'] = $result->result_array();
                $result = $result->free_result();
            } else {

                // echo $event['employers_sid'];
                // fetch employee
                $result = $this->db
                ->select('sid as id')
                ->select('timezone')
                ->select('PhoneNumber as phone_number')
                ->select('email as email_address')
                ->select('concat(first_name," ",last_name, " (", email ,")") as value')
                ->where('sid', $event['applicant_job_sid'])
                ->where('active', 1)
                ->where('career_page_type', 'standard_career_site')
                ->from('users')
                ->get();

                $event['employer_details'] = $result->row_array();
                $event['employer_details']['timezone'] = $this->get_timezone('reset', $event['company_id'], $event['employer_details']['timezone']);
                $result = $result->free_result();
            }

            // get interviewer details
            $result = $this->db
            ->select('sid as id')
            ->select('timezone')
            ->select('PhoneNumber as phone_number')
            ->select('concat(first_name," ",last_name) as full_name')
            ->select('(IF(is_executive_admin = 1, "Executive Admin", access_level)) as employee_type', false)
            ->select('concat(first_name," ",last_name, " (", (
                    IF(is_executive_admin = 1, "Executive Admin", access_level)
                ) ,")") as value', false)
            ->select('email as email_address')
            ->where_in('sid', explode(',', $event['interviewer']))
            ->where('active', 1)
            ->where('career_page_type', 'standard_career_site')
            ->from('users')
            ->get();

            $event['interviewers_details'] = $result->result_array();
            $result = $result->free_result();

            if(sizeof($event['interviewers_details'])){
                foreach ($event['interviewers_details'] as $k0 => $v0) {
                    $event['interviewers_details'][$k0]['timezone'] = $this->get_timezone('reset', $event['company_id'], $v0['timezone']);
                }
            }

            
            // get extra interviewers
            $result = $this->db
            ->select('name')
            ->select('email')
            ->select('show_email')
            ->where('event_sid', $event_id)
            ->from('portal_schedule_event_external_participants')
            ->get();

            $event['external_participants'] = $result->result_array();
            $result = $result->free_result();
             if(sizeof($event['external_participants'])){
                foreach ($event['external_participants'] as $k0 => $v0) {
                    $event['external_participants'][$k0]['timezone'] = $this->get_timezone('company', $event['company_id']);
                }
            }

       
            // get status changes 
            $event['event_history_count'] = $this->db
            ->select('event_sid')
            ->where('event_sid', $event_id)
            ->from('portal_event_history')
            ->count_all_results();

            // get status changes 
            $event['event_requests'] = $this->db
            ->select('event_sid')
            ->where('event_sid', $event_id)
            ->where('event_type !=', 'confirmed')
            ->where('status', '0')
            ->from('portal_event_history')
            ->count_all_results();

            // get reminder emails status changes 
            $event['event_reminder_email_history'] = $this->db
            ->select('event_sid')
            ->where('event_sid', $event_id)
            ->from('portal_event_reminder_email_history')
            ->count_all_results();

            //get event change history
            $event['event_change_history'] = $this->db
            ->select('event_sid')
            ->where('event_sid', $event_id)
            ->where('details !=','null')
            ->from('portal_schedule_event_history')
            ->count_all_results();
        }  

        if(isset($event_array)){
             
            // get creator details
            $result = $this->db
            ->select('CompanyName')
            ->where_in('sid', $event['companys_sid'])
            ->where('active', 1)
            ->where('career_page_type', 'standard_career_site')
            ->from('users')
            ->get();

            $event['company_name'] = $result->row_array()['CompanyName'];
            $result = $result->free_result();
            $creator_sid = $event['employers_sid'];
            
            $result = $this->db
            ->select('user_id')
            ->where('event_sid', $event_id)
            ->from('portal_schedule_event_history')
            ->order_by('created_at', 'ASC')
            ->get();
            $event_change_history = $result->row_array();
            $result->free_result();
            if(isset($event_change_history['user_id'])){
                $creator_sid = $event_change_history['user_id'];
            }
            // get creator details
            $result = $this->db
            ->select('sid')
            ->select('first_name')
            ->select('last_name')
            ->select('timezone')
            ->select('email as email_address')
            ->where('sid', $creator_sid)
            // ->where_in('sid', explode(',', $event['employers_sid']))
            ->where('active', 1)
            ->where('career_page_type', 'standard_career_site')
            ->from('users')
            ->get();

            $event['creator_employee'] = $result->row_array();
            $result = $result->free_result();
        }


        // Reset event datetimes
        if($reset_datetime) reset_event_datetime($event, $this);

        //
        return $event;
    }

    

    /**
     * Update event
     *
     * @param $event_id Integer
     * @param $date String
     * @param $start_time String
     * @param $end_time String
     * @param $type Bool Optiona;
     *
     * @return Bool
     */
    function update_event_by_id($event_id, $date, $start_time, $end_time, $type = FALSE){
        // Set data array
        $data_array = array(
            'date' => $date,
            'eventstarttime' => $start_time,
            'eventendtime' => $end_time
        );

        if($type == 'drag') unset($data_array['eventstarttime'], $data_array['eventendtime']);
        // Check for date
        if( $date >= date('Y-m-d')){
            // Get reminder check and sent_flag
            $result = $this->db
            ->select('reminder_flag, sent_flag')
            ->from('portal_schedule_event')
            ->where('sid', $event_id)
            ->get();
            //
            $result_arr = $result->row_array();
            $result = $result->free_result();

            if($result_arr['reminder_flag'] == 1 && $result_arr['sent_flag'] == 1){
                $data_array['sent_flag'] = 0;
            }
        }
        return $this->db->update('portal_schedule_event', $data_array, array( 'sid' => $event_id ));
    }

    /**
     * Update event
     * Added at: 08-04-2019
     *
     * @param $sid Integer
     * @param $type String
     * @param $company_sid Interger
     *
     * @return Integer
     */
    function verify_event_details($sid, $type, $company_sid){
        return 
        $this->db
        ->select('sid')
        ->where('sid', $sid)
        ->where(
            $type == 'applicant' ? 'employer_sid' : 'parent_sid', 
            $company_sid
        )
        ->from($type == 'applicant' ? 'portal_job_applications' : 'users')
        ->count_all_results();
    }

    /**
     * Update event
     * Added at: 08-04-2019
     *
     * Deprecated
     *
     * @param $event_sid Integer
     * @param $update_array Array
     *
     * @return Bool
     */
    function update_event_event_sid($event_sid, $update_array){
        return 
        $this->db
        ->where('sid', $event_sid)
        ->update('portal_schedule_event', $update_array );
    }


    /**
     * Insert event history
     * Added at: 09-04-2019
     *
     * @param $event_history_array Array
     *
     * @return Bool|String
     */
    function add_event_history($event_history_array){
        return 
        $this->db
        ->insert('portal_event_history', $event_history_array );
    }

    /**
     * Check for event history limit
     * Added at: 09-04-2019
     *
     * @param $event_sid Integer
     * @param $user_sid Integer
     *
     * @return Integer
     */
    function check_event_history_limit($event_sid, $user_sid){
        return $this->db
        ->select('portal_event_history_id')
        ->from('portal_event_history')
        ->where('event_sid', $event_sid)
        ->where('user_sid', $user_sid)
        ->count_all_results();
    }

    /**
     * Get event column
     * Added at: 09-04-2019
     *
     * @param $event_sid Integer
     * @param $column String Optional
     * @param $str Bool|Array Optional
     *
     * @return String
     */
    function get_event_column_by_event_id($event_sid, $column = 'date', $str = FALSE){
        $this->db
        ->select(($column == 'CompanyName' ? 'users.' : '').$column)
        ->from('portal_schedule_event')
        ->where('portal_schedule_event.sid', $event_sid);
        // Set dynamic wwhere condition
        if(is_array($str) && sizeof($str)){
            foreach ($str as $k0 => $v0) {
                $this->db->where('portal_schedule_event.'.$k0, $v0);
            }
            $str = false;
        }
        if($column == 'CompanyName')
            $this->db->join('users', 'portal_schedule_event.companys_sid = users.sid', 'left');

        //
        $result = $this->db->get();

        $column_name = $result->row_array()[$column];
        $result = $result->free_result();

        if($str)
            return strtotime($column_name.' 23:59:59');
        return $column_name;
    }


    /**
     * Save sent reminder emails
     * history for calendar event
     * Added on: 10-04-2019
     *
     * @param $data_array Array
     *
     * @return Integer|Bool
     */
    function save_event_sent_email_reminder_history($data_array){
        return $this->db->insert('portal_event_reminder_email_history', $data_array);
    }

    /**
     * Get event detail by sid   
     * Added on: 10-04-2019
     *
     * @param $event_sid Integer
     * @param $exec Bool Optional
     *
     * @return Array
     */
    function get_event_detail_by_event_id($event_sid, $exec = FALSE ){
         //
        $this->db
        ->select('title as event_title')
        ->select('users_type')
        ->select('date_format(date, "%m-%d-%Y") as event_date')
        ->select('eventstarttime as event_start_time')
        ->select('eventendtime as event_end_time')
        ->select('CONCAT(UCASE(SUBSTRING(category, 1, 1)),SUBSTRING(category, 2)) as category_uc')
        ->where('sid', $event_sid)
        ->from('portal_schedule_event');
        //
        if($exec) return $this->db->get_compiled_select();
        //
        $result = $this->db->get();
        $event = $result->row_array();
        $result = $result->free_result();

        return $event;
    }


    /**
     * Get event status change requests history
     * Added on: 11-04-2019
     *
     * @param $event_sid Integer
     * @param $limit Integer
     * @param $page Integer
     * @param $exec Bool Optional
     *
     * @return Array
     */
    function get_event_availablity_requests($event_sid, $page, $limit = 10, $exec = FALSE ){
        //
        $start_limit = $page == 1 ? 0 : ( (($page * $limit) - $limit) );
        $this->db
        ->select('
            reason, 
            event_start_time as start_time, 
            event_end_time as end_time,
            user_name,
            user_sid,
            IF(event_type = "cannotattend", "Cannot Attend", CONCAT(UCASE(SUBSTRING(event_type, 1, 1)),SUBSTRING(event_type, 2))) as event_status,
            CONCAT(UCASE(SUBSTRING(user_type, 1, 1)),SUBSTRING(user_type, 2)) as user_type,
            date_format(event_date, "%m-%d-%Y") as date,
            date_format(created_at, "%Y-%m-%d %H:%i:%s") as created_at', false
        )
        ->from('portal_event_history')
        ->where('event_sid', $event_sid)
        ->order_by('created_at', 'DESC')
        ->limit($limit, $start_limit);
        //
        if($exec) return $this->db->get_compiled_select();
        //
        $result = $this->db->get();
        $result_arr = $result->result_array();
        $result = $result->free_result();
        //
        if(!sizeof($result_arr)) return false;
        //
        foreach($result_arr as $k0 => $v0){
            $result_arr[$k0]['reason'] = $v0['reason'] === NULL ? '-' : $v0['reason'];
            $result_arr[$k0]['date']   = $v0['date'] === NULL ? '-' : $v0['date'];
            $result_arr[$k0]['start_time'] = $v0['start_time'] === NULL ? '-' : $v0['start_time'];
            $result_arr[$k0]['end_time']   = $v0['end_time'] === NULL ? '-' : $v0['end_time'];

            if($result_arr[$k0]['date'] != '-'){
                $result_arr[$k0]['date'] = reset_datetime(array(
                    'datetime' => $v0['date'].$v0['start_time'],
                    'from_format' => 'm-d-Yh:iA',
                    'format' => 'm-d-Y',
                    '_this' => $this
                ));
                $result_arr[$k0]['start_time'] = reset_datetime(array(
                    'datetime' => $v0['date'].$v0['start_time'],
                    'from_format' => 'm-d-Yh:iA',
                    'format' => 'h:iA',
                    '_this' => $this
                ));
                $result_arr[$k0]['end_time'] = reset_datetime(array(
                    'datetime' => $v0['date'].$v0['end_time'],
                    'from_format' => 'm-d-Yh:iA',
                    'format' => 'h:iA',
                    '_this' => $this
                ));
            }
            //
            $result_arr[$k0]['created_at'] = reset_datetime(array(
                'datetime' => $v0['created_at'],
                'from_format' => 'Y-m-d H:i:s',
                'format' => 'Y-m-d H:i:s',
                '_this' => $this
            ));
            //
            switch ($v0['user_type']) {
                case 'Applicant':
                    $result = $this->db
                    ->select('concat( portal_job_applications.first_name, " ", portal_job_applications.last_name ) as user_name')
                    ->from('portal_job_applications')
                    ->where('sid', $v0['user_sid'])
                    ->get();
                    $result_arr[$k0]['user_name'] = $result->row_array()['user_name'];
                    $result = $result->free_result();
                break;
                case 'Employee':
                    $result = $this->db
                    ->select('concat(first_name," ",last_name) as user_name')
                    ->from('users')
                    ->where('sid', $v0['user_sid'])
                    ->get();
                    $result_arr[$k0]['user_name'] = $result->row_array()['user_name'];
                    $result = $result->free_result();
                break;
                case 'Interviewer':
                    $result = $this->db
                    ->select('concat(first_name," ",last_name, " (", (
                            IF(is_executive_admin = 1, "Executive Admin", access_level)
                        ) ,")") as user_name', false)
                    ->where('sid', $v0['user_sid'])
                    ->from('users')
                    ->get();
                    $result_arr[$k0]['user_name'] = $result->row_array()['user_name'];
                    $result = $result->free_result();
                    $result_arr[$k0]['user_type'] = 'Participants';
                break;
                case 'Extrainterviewer':
                    $result = $this->db
                    ->select('name as user_name')
                    ->where('sid', $v0['user_sid'])
                    ->from('portal_schedule_event_external_participants')
                    ->get();

                    $tmp_result = $result->row_array();
                    $result = $result->free_result();
                    if(isset($tmp_result['user_name'])){
                        $result_arr[$k0]['user_name'] = $tmp_result['user_name'];
                    }
                    $result_arr[$k0]['user_type'] = 'Non-employee Participants';
                break;
                case 'Personal':
                    $result = $this->db
                    ->select('CONCAT(UCASE(SUBSTRING(users_name, 1, 1)),SUBSTRING(users_name, 2)) as user_name')
                    ->select('category')
                    ->where('sid', $v0['user_sid'])
                    ->from('portal_schedule_event')
                    ->get();

                    $result_arr[$k0]['user_name'] = $result->row_array()['user_name'];
                    $result = $result->free_result();
                break;
            }
        }
        // Only excute when requested page
        // value is one
        if($page == 1){
            return array(
                'History' => $result_arr,
                'Count' => $this->db
                ->select('event_sid')
                ->from('portal_event_history')
                ->where('event_sid', $event_sid)
                ->count_all_results()

            );
        }

        return $result_arr;
    }


    /**
     * Get sent reminder emails history
     * Added on: 12-04-2019
     *
     * @param $event_sid Integer
     * @param $limit Integer
     * @param $page Integer
     * @param $exec Bool Optional
     *
     * @return Array
     */
    function get_reminder_email_history($event_sid, $page, $limit = 10, $exec = FALSE ){
            // date_format(created_at, "%m-%d-%Y %h:%i:%s %p") as created_at', false
        //
        $start_limit = $page == 1 ? 0 : ( (($page * $limit) - $limit) );
        $this->db
        ->select('
            email_address as user_email,
            CONCAT(UCASE(SUBSTRING(user_type, 1, 1)),SUBSTRING(user_type, 2)) as user_type,
            user_name,
            date_format(created_at, "%Y-%m-%d %H:%i:%s") as created_at', false
        )
        ->from('portal_event_reminder_email_history')
        ->where('event_sid', $event_sid)
        ->order_by('created_at', 'DESC')
        ->limit($limit, $start_limit);
        //
        if($exec) return $this->db->get_compiled_select();
        //
        $result = $this->db->get();
        $result_arr = $result->result_array();
        $result = $result->free_result();
        $nr = array();
        if(sizeof($result_arr)){
            foreach ($result_arr as $k0 => $v0) {
                 //
                $v0['created_at'] = reset_datetime(array(
                    'datetime' => $v0['created_at'],
                    'from_format' => 'Y-m-d H:i:s',
                    'format' => 'Y-m-d H:i:s',
                    '_this' => $this
                ));
                $nr[$v0['created_at']][] = array('user_name' => ucwords($v0['user_name'] == NULL ? '-' : $v0['user_name']), 'user_email' => $v0['user_email'], 'user_type' => $v0['user_type']);
            }
        }
        // Only execute when requested page
        // value is one
        if($page == 1){
            return array(
                // 'History' => $result_arr,
                'History' => $nr,
                'Count' => $this->db
                ->select('event_sid')
                ->from('portal_event_reminder_email_history')
                ->where('event_sid', $event_sid)
                ->count_all_results()

            );
        }
        return $nr;
    }

    /**
     * Get event change history
     * added at: 18-10-2019
     *
     * @param $event_sid Integer
     * @param $limit Integer
     * @param $page Integer
     * @param $exec Bool Optional
     *
     * @return Array
     */
    function get_event_change_history($event_sid, $page, $limit = 10, $exec = FALSE ){
        //
        $this->db
        ->select('portal_schedule_event.sid')
        ->select('portal_schedule_event.event_timezone')
        ->from('portal_schedule_event')
        ->where('portal_schedule_event.sid', $event_sid);
        //
        if($exec) return $this->db->get_compiled_select();
        //
        $result = $this->db->get();
        $event_details = $result->row_array();
        $result = $result->free_result();

        $start_limit = $page == 1 ? 0 : ( (($page * $limit) - $limit) );
        $this->db
        ->select('*')
        ->from('portal_schedule_event_history')
        ->where('event_sid', $event_sid)
        ->order_by('created_at', 'DESC')
        ->limit($limit, $start_limit);
        //
        if($exec) return $this->db->get_compiled_select();
        //
        $result = $this->db->get();
        $result_arr = $result->result_array();
        $result = $result->free_result();
        //
        if(!sizeof($result_arr)) return false;
        //
        $user_ids = [];
        $applicant_ids = [];
        foreach($result_arr as $k0 => $v0){
            if($v0['details'] == 'null'){
                unset($result_arr[$k0]);
                continue;
            }
            
            $user_ids[] = $v0['user_id'];
            $details = json_decode($v0['details'],true);
            if(is_string($details))
                $details = json_decode($details,true);
            
            if(isset($details['old_applicant_job_sid'])){
                if(isset($details['old_users_type']) && $details['old_users_type'] == "employee"){
                    $user_ids[] = $details['old_applicant_job_sid'];
                }
                if(isset($details['old_users_type']) && $details['old_users_type'] == "applicant"){
                    $applicant_ids[] = $details['old_applicant_job_sid'];
                }
                if(isset($details['users_type']) && $details['users_type'] == "employee" && isset($details['old_applicant_job_sid'])){
                   $user_ids[] = $details['old_applicant_job_sid'];
                }
                if(isset($details['users_type']) && $details['users_type'] == "applicant" && isset($details['old_applicant_job_sid'])){
                    $applicant_ids[] = $details['old_applicant_job_sid'];
                }
                
            }
            if(isset($details['new_applicant_job_sid'])){
                if(isset($details['new_users_type']) && $details['new_users_type'] == "employee" && isset($details['new_applicant_job_sid'])){
                    $user_ids[] = $details['new_applicant_job_sid'];
                }
                if(isset($details['new_users_type']) && $details['new_users_type'] == "applicant" && isset($details['new_applicant_job_sid'])){
                    $applicant_ids[] = $details['new_applicant_job_sid'];
                }
                if(isset($details['users_type']) && $details['users_type'] == "employee" && isset($details['new_applicant_job_sid'])){
                    $user_ids[] = $details['new_applicant_job_sid'];
                }
                if(isset($details['users_type']) && $details['users_type'] == "applicant" && isset($details['new_applicant_job_sid'])){
                    $applicant_ids[] = $details['new_applicant_job_sid'];
                }
            }
            if(isset($details['old_interviewers'])){
                $old_interviewers_ids = explode(",",$details['old_interviewers']);
                if(is_array($old_interviewers_ids))
                    $user_ids = array_merge($user_ids,$old_interviewers_ids);
            }
            if(isset($details['new_interviewers'])){
                $new_interviewers_ids = explode(",",$details['new_interviewers']);
                if(is_array($new_interviewers_ids))
                    $user_ids = array_merge($user_ids,$new_interviewers_ids);
            }
            
            $result_arr[$k0]['details'] = $details;
                
        }
        $user_names = $this->get_user_names($user_ids);
        $applicant_names = $this->get_applicant_names($applicant_ids);
        foreach($result_arr as $k0 => $v0){
            $details = $result_arr[$k0]['details'];
            //change date start and end time to user timezone
            if(isset($details['new_date']) && isset($details['timezone_new_start_time'])){
                $new_date_arr = array(
                    'datetime' => $details['new_date'].$details['timezone_new_start_time'],
                    'from_format' => 'Y-m-dh:iA',
                    'format' => 'm-d-Y',
                    '_this' => $this
                );
                if(!empty( $event_details['event_timezone'])){
                    $new_date_arr['new_zone'] = $event_details['event_timezone'];
                }
                $details['new_date'] = reset_datetime($new_date_arr);
            }
    
            if(isset($details['old_date']) && isset($details['timezone_old_start_time'])){
                $old_date_arr = array(
                    'datetime' => $details['old_date'].$details['timezone_old_start_time'],
                    'from_format' => 'Y-m-dh:iA',
                    'format' => 'm-d-Y',
                    '_this' => $this
                );
                if(!empty( $event_details['event_timezone'])){
                    $old_date_arr['new_zone'] = $event_details['event_timezone'];
                }
                $details['old_date'] = reset_datetime($old_date_arr);
            }
            if(isset($details['new_event_start_time'])){
                $new_event_start_time_arr = array(
                    'datetime' => $details['new_event_start_time'],
                    'from_format' => 'h:iA',
                    'format' => 'h:iA',
                    '_this' => $this
                );
                if(!empty( $event_details['event_timezone'])){
                    $new_event_start_time_arr['new_zone'] = $event_details['event_timezone'];
                }
                $details['new_event_start_time'] = reset_datetime($new_event_start_time_arr);
            }
            
            if(isset($details['new_event_end_time'])){
                $new_event_end_time_arr = array(
                    'datetime' => $details['new_event_end_time'],
                    'from_format' => 'h:iA',
                    'format' => 'h:iA',
                    '_this' => $this
                );
                if(!empty( $event_details['event_timezone'])){
                    $new_event_end_time_arr['new_zone'] = $event_details['event_timezone'];
                }
                $details['new_event_end_time'] = reset_datetime($new_event_end_time_arr);
            }
            if(isset($details['old_event_start_time'])){
                $old_event_start_time_arr = array(
                    'datetime' => $details['old_event_start_time'],
                    'from_format' => 'h:iA',
                    'format' => 'h:iA',
                    '_this' => $this
                );
                if(!empty( $event_details['event_timezone'])){
                    $old_event_start_time_arr['new_zone'] = $event_details['event_timezone'];
                }
                $details['old_event_start_time'] = reset_datetime($old_event_start_time_arr);
            }
            if(isset($details['old_event_end_time'])){
                $old_event_end_time_arr = array(
                    'datetime' => $details['old_event_end_time'],
                    'from_format' => 'h:iA',
                    'format' => 'h:iA',
                    '_this' => $this
                );
                if(!empty( $event_details['event_timezone'])){
                    $old_event_end_time_arr['new_zone'] = $event_details['event_timezone'];
                }
                $details['old_event_end_time'] = reset_datetime($old_event_end_time_arr);
                
            }
            if(isset($details['old_users_type']) && $details['old_users_type'] == "applicant" && isset($details['old_applicant_job_sid'])){
                if(isset($applicant_names[$details['old_applicant_job_sid']]))
                    $details['old_applicant_name'] =  $applicant_names[$details['old_applicant_job_sid']];
            }
            if(isset($details['new_users_type']) && $details['new_users_type'] == "applicant" && isset($details['new_applicant_job_sid'])){
                if(isset($applicant_names[$details['new_applicant_job_sid']]))
                    $details['new_applicant_name'] = $applicant_names[$details['new_applicant_job_sid']];
            }
            if(isset($details['old_users_type']) && $details['old_users_type'] == "employee" && isset($details['old_applicant_job_sid'])){
                if(isset($user_names[$details['old_applicant_job_sid']]))
                    $details['old_employee_name'] = $user_names[$details['old_applicant_job_sid']];
            }
            if(isset($details['new_users_type']) && $details['new_users_type'] == "employee" && isset($details['new_applicant_job_sid'])){
                if(isset($user_names[$details['new_applicant_job_sid']]))
                    $details['new_employee_name'] = $user_names[$details['new_applicant_job_sid']];
            }
            if(isset($details['users_type']) && $details['users_type'] == "employee" && isset($details['new_applicant_job_sid'])){
                if(isset($user_names[$details['new_applicant_job_sid']]))
                    $details['new_employee_name'] = $user_names[$details['new_applicant_job_sid']];
            }
            if(isset($details['users_type']) && $details['users_type'] == "applicant" && isset($details['new_applicant_job_sid'])){
                if(isset($applicant_names[$details['new_applicant_job_sid']]))
                    $details['new_applicant_name'] = $applicant_names[$details['new_applicant_job_sid']];
            }
            if(isset($details['users_type']) && $details['users_type'] == "employee" && isset($details['old_applicant_job_sid'])){
                if(isset($user_names[$details['old_applicant_job_sid']]))
                    $details['old_employee_name'] = $user_names[$details['old_applicant_job_sid']];
            }
            if(isset($details['users_type']) && $details['users_type'] == "applicant" && isset($details['old_applicant_job_sid'])){
                if(isset($applicant_names[$details['old_applicant_job_sid']]))
                    $details['old_applicant_name'] = $applicant_names[$details['old_applicant_job_sid']];
            }
            if(isset($details['old_users_type'])){
                $details['old_users_type'] = ucfirst($details['old_users_type']);
            }
            if(isset($details['new_users_type'])){
                $details['new_users_type'] = ucfirst($details['new_users_type']);
            }
            if(isset($details['old_interviewers'])){
                $new_interviewers_ids = explode(",",$details['old_interviewers']);
                if(is_array($new_interviewers_ids))
                    foreach($new_interviewers_ids as $interviewer)
                        if(isset($user_names[$interviewer]))
                            $details['old_interviewer_names'][] = $user_names[$interviewer];
            }
            if(isset($details['new_interviewers'])){
                $new_interviewers_ids = explode(",",$details['new_interviewers']);
                if(is_array($new_interviewers_ids))
                    foreach($new_interviewers_ids as $interviewer)
                        if(isset($user_names[$interviewer]))
                            $details['new_interviewer_names'][] = $user_names[$interviewer];
            }
            $result_arr[$k0]['details'] = $details;
            
            $result_arr[$k0]['created_at'] = reset_datetime(array(
                'datetime' => $v0['created_at'],
                'from_format' => 'Y-m-d H:i:s',
                'format' => 'Y-m-d H:i:s',
                '_this' => $this
            ));
            $result_arr[$k0]['user_name'] = $user_names[$v0['user_id']];
        }
        // Only excute when requested page
        // value is one
        if($page == 1){
            return array(
                'History' => $result_arr,
                'Count' => $this->db
                ->select('event_sid')
                ->from('portal_schedule_event_history')
                ->where('event_sid', $event_sid)
                ->count_all_results()

            );
        }
        return $result_arr;
    }

    /**
     * Fetch interview, non-employee interviewers
     * applicant or employee data
     *
     * @param $event_sid Integer
     * @param $user_type String
     * @param $interviewer String
     * @param $applicant_job_sid Integer
     *
     * @return Array
     *
     */
    function get_event_email_list($event_sid, $user_type, $interviewer, $applicant_job_sid){
        $return_array = array();
        if($user_type == 'applicant'){
            // for applicant
            $result = $this->db
            ->select('concat( first_name, " ", last_name) as value ')
            ->select('sid as id')
            ->select('"applicant" as type')
            ->select('email as email_address')
            ->where('sid', $applicant_job_sid)
            ->from('portal_job_applications')
            ->get();
            ///
            $return_array[] = $result->row_array();
            $result = $result->free_result();
        }else{
            // For employee
            $result = $this->db
                ->select('sid as id')
                ->select('"employee" as type')
                ->select('email as email_address')
                ->select('concat(first_name," ",last_name) as value')
                ->select('timezone')
                ->where('sid', $applicant_job_sid)
                ->where('active', 1)
                ->where('career_page_type', 'standard_career_site')
                ->from('users')
                ->get();

            $return_array[] = $result->row_array();
            $result = $result->free_result();
        }

        // For Interviewers
        $result = $this->db
            ->select('sid as id')
            ->select('"interviewer" as type')
            ->select('concat(first_name," ",last_name) as value', false)
            ->select('email as email_address')
            ->select('timezone')
            ->where_in('sid', explode(',', $interviewer))
            ->where('active', 1)
            ->where('career_page_type', 'standard_career_site')
            ->from('users')
            ->get();
        //
        $return_array = array_merge($return_array, $result->result_array());
        $result = $result->free_result();

        // For non-employee Interviewers
        $result = $this->db
            ->select('"0" as id')
            ->select('"non-employee interviewer" as type')
            ->select('name as value')
            ->select('email as email_address')
            ->where('event_sid', $event_sid)
            ->from('portal_schedule_event_external_participants')
            ->get();
        //
        $return_array = array_merge($return_array, $result->result_array());
        $result = $result->free_result();

        return $return_array;
    }


    /**
     * get event detail for front-end
     * 
     * @param $event_id Integer
     * @param $exec Bool Optional
     * 
     * @return Array|Bool
     */
    function get_event_detail_for_frontend($event_id, $exec = FALSE){
        //
        $this->db
        ->select('portal_schedule_event.sid')
        ->select('portal_schedule_event.companys_sid')
        ->select('users.CompanyName as company_name')
        ->select('users.PhoneNumber as company_phone_number')
        ->select('portal_schedule_event.eventstarttime as event_start_time')
        ->select('portal_schedule_event.eventendtime as event_end_time')
        ->select('date_format(portal_schedule_event.date, "%Y%m%d") as event_date_gc')
        ->select('portal_schedule_event.date as event_date_ac')
        ->select('date_format(portal_schedule_event.date, "%M-%d, %Y") as event_date')
        ->select('portal_schedule_event.interviewer')
        ->select('portal_schedule_event.title')
        ->select('portal_schedule_event.address')
        ->select('portal_schedule_event.users_type')
        ->select('portal_schedule_event.applicant_job_sid')
        ->select('portal_schedule_event.event_timezone')
        ->select('portal_schedule_event.employers_sid')
        ->select('portal_schedule_event.interviewer_show_email')
        ->select('CONCAT(UCASE(SUBSTRING(portal_schedule_event.category, 1, 1)),SUBSTRING(portal_schedule_event.category, 2)) as category_uc')
        ->from('portal_schedule_event')
        ->join('users', 'users.sid = portal_schedule_event.companys_sid', 'inner')
        ->where('portal_schedule_event.sid', $event_id);
        //
        if($exec) return $this->db->get_compiled_select();
        //
        $result = $this->db->get();
        $event = $result->row_array();
        $result = $result->free_result();
        //
        if(!sizeof($event)) return false;

        $interviewer_show_email = explode(',', $event['interviewer_show_email']);

        // get interviewer details
        $result = $this->db
        ->select('concat(first_name," ",last_name) as value', false)
        ->select('email as email_address')
        ->select('timezone')
        ->select('sid')
        ->where_in('sid', explode(',', $event['interviewer']))
        ->where('active', 1)
        ->where('career_page_type', 'standard_career_site')
        ->from('users')
        ->get();

        $interviewers = $result->result_array();
        if(sizeof($interviewers)){
            foreach ($interviewers as $k0 => $v0) {
                $interviewers[$k0]['show_email'] = (int)in_array($v0['sid'], $interviewer_show_email);
                // $interviewers[$k0]['timezone']   = $this->get_timezone('reset', $event['companys_sid'], $v0['timezone'] );
                unset($interviewers[$k0]['sid']);
            }
        }
        //
        // $timezone = $this->get_timezone('company', $event['companys_sid'] );
        $result = $result->free_result();
        // get extra interviewers
        $result = $this->db
        ->select('name as value')
        // ->select('"'.($timezone).'" as timezone')
        ->select('email as email_address')
        ->select('show_email')
        ->where('event_sid', $event_id)
        ->from('portal_schedule_event_external_participants')
        ->get();

        $external_participants = $result->result_array();
        $event['interviewers'] = array_merge($interviewers, $external_participants);
        $result = $result->free_result();

        unset($event['interviewer'], $event['interviewer_show_email']);
        $event['event_date'] = str_replace('-', ' ', $event['event_date']);
        //
        return $event;
    }


    /**
     * Get videos
     * 
     * @param $company_id Integer
     * @param $employee_id Integer
     * @param $exec Bool Optional
     * 
     * @return Array|Bool
     */
    function fetch_online_videos($company_id, $employee_id, $exec = FALSE){
        //
        $this->db
        ->select('sid as id')
        ->select('video_title as name')
        ->from('learning_center_online_videos')
        ->where('company_sid', $company_id)
        // ->where('created_by_sid', $employee_id)
        ->order_by('sid', 'DESC');
        //
        if($exec) return $this->db->get_compiled_select();
        //
        $result = $this->db->get();
        $event = $result->result_array();
        $result = $result->free_result();
        //
        if(!sizeof($event)) return false;
        return $event;
    }

    /**
     * Insert
     * Created on: 07-05-2019 
     * 
     * @param $insert_array Array
     * 
     * @return Bool|Integer
     */
    function insert_learning_center_training_session($insert_array){
        //
        $this->db->insert('learning_center_training_sessions', $insert_array);
        return $this->db->insert_id();
    }


    /**
     * Insert
     * Created on: 07-05-2019 
     * 
     * @param $insert_array Array
     * 
     * @return Bool|Integer
     */
    function insert_learning_center_training_sessions_assignments($insert_array){
        //
        $this->db->insert('learning_center_training_sessions_assignments', $insert_array);
        return $this->db->insert_id();
    }


    /**
     * Update
     * Created on: 07-05-2019 
     * 
     * @param $training_session_id Array
     * @param $insert_array Array
     * 
     * @return VOID
     */
    function update_learning_center_training_session($training_session_id, $insert_array){
        //
        $this->db
        ->where('sid', $training_session_id)
        ->update('learning_center_training_sessions', $insert_array);
    }


    /**
     * Get prev. assignment
     * Created on: 07-05-2019
     *
     * @param $training_session_id Integer
     *
     * @return Bool|Array
     */
    function get_last_active_training_session_assignments($training_session_id) {
        $result = 
        $this->db
        ->select('attended, date_attended, attend_status, user_type, user_sid')
        ->where('training_session_sid', $training_session_id)
        ->where('status', 1)
        ->get('learning_center_training_sessions_assignments');
        $result_arr = $result->result_array();
        $result = $result->free_result();

        return $result_arr;
    }


    /**
     * Change status of assignmed
     * Created on: 07-05-2019
     *
     * @param $training_session_id Integer
     *
     * @return VOID
     */
    function set_training_session_assignment_status($training_session_id) {
        $this->db
        ->where('training_session_sid', $training_session_id)
        ->where('status', 1)
        ->order_by('sid', 'DESC')
        ->update('learning_center_training_sessions_assignments', array('status' => 0));
    }

    /**
     * Update event
     * Created on: 07-05-2019
     *
     * @param $event_id Integer
     * @param $array Array
     *
     * @return Bool
     */
    function update_event_columns_by_id($event_id, $array){
        return $this->db->update('portal_schedule_event', $array, array( 'sid' => $event_id ));
    }

    /**
     * Update LC
     * Created on: 07-05-2019
     *
     * @param $learning_training_session_id Integer
     * @param $array Array
     *
     * @return Bool
     */
    function update_lc_by_id($learning_training_session_id, $array){
        return $this->db->update('learning_center_training_sessions', $array, array( 'sid' => $learning_training_session_id ));
    }

    /**
     * Drop non-employees from LC
     * Created on: 07-05-2019
     *
     * @param $learning_training_session_id Integer
     *
     * @return VOID
     */
    function drop_all_non_employee_from_lc($learning_training_session_id){
        $this->db->delete('learning_center_training_sessions_assignments', array( 
            'training_session_sid' => $learning_training_session_id,
            'user_type' => 'non-employee'
        ));
    }

    /**
     * Fetch user status
     * Created on: 09-05-2019
     *
     * @param $user_id Integer
     * @param $event_sid Integer
     *
     * @return Array|Bool
     */
    function get_lc_particpant_status($user_id, $event_sid){
        $result = $this->db
        ->select('learning_center_training_sessions_assignments.attend_status')
        ->select('learning_center_training_sessions_assignments.training_session_sid')
        ->from('learning_center_training_sessions_assignments')
        ->join('learning_center_training_sessions', 'learning_center_training_sessions_assignments.training_session_sid = learning_center_training_sessions.sid', 'left')
        ->join('portal_schedule_event', 'learning_center_training_sessions.sid = portal_schedule_event.learning_center_training_sessions', 'left')
        ->where('learning_center_training_sessions_assignments.user_sid', $user_id)
        ->where('learning_center_training_sessions_assignments.status', 1)
        ->where('portal_schedule_event.sid', $event_sid)
        ->limit(1)
        ->get();
        $result_arr = $result->row_array();
        $result = $result->free_result();
        return sizeof($result_arr) ? $result_arr : false;
    }


    /**
     * Update user status LC
     * Created on: 09-05-2019
     *
     * @param $training_session_sid Integer
     * @param $user_sid Integer
     * @param $array Array
     *
     * @return VOID
     */
    function update_lc_user_status($training_session_sid, $user_sid, $array){
        $this->db
        ->where('training_session_sid', $training_session_sid)
        ->where('user_sid', $user_sid)
        ->where('status', 1)
        ->update('learning_center_training_sessions_assignments', $array);
    }

    /**
     * Cancel training session LC
     * Created on: 09-05-2019
     *
     * @param $training_session_sid Integer
     *
     * @return VOID
     */
    function cancel_training_session($training_session_sid){
        $this->db
        ->where('sid', $training_session_sid)
        ->update('learning_center_training_sessions', array('session_status' => 'cancelled'));
    }

    /**
     * Delete training session LC
     * Created on: 09-05-2019
     *
     * @param $training_session_sid Integer
     *
     * @return VOID
     */
    function delete_training_session($training_session_sid){
        $this->db
        ->where('sid', $training_session_sid)
        ->delete('learning_center_training_sessions');

        $this->db
        ->where('training_session_sid', $training_session_sid)
        ->delete('learning_center_training_sessions_assignments');
    }


    /**
     * Reset training session status
     * Created on: 09-05-2019
     *
     * @param $training_session_sid Integer
     * @param $status String
     *
     * @return VOID
     */
    function training_session_reset_status($training_session_sid, $status){
        $this->db
        ->where('sid', $training_session_sid)
        ->update('learning_center_training_sessions', array('session_status' => $status));
    }

    /**
     * Get event row 
     * Created on: 10-05-2019
     *
     * @param $learning_center_training_sessions Integer
     *
     * @return Array|Bool
     */
    function fetch_ts_event_row_by_lcid($learning_center_training_sessions){
        $return_array = array();
        $result = $this->db
        ->select('portal_schedule_event.*, users.CompanyName as company_name')
        ->from('portal_schedule_event')
        ->join('users', 'users.sid = portal_schedule_event.companys_sid', 'left')
        ->where('portal_schedule_event.learning_center_training_sessions', $learning_center_training_sessions)
        ->limit(1)
        ->get();
        // Fetch event row
        $return_array['event_details'] = $result->row_array();
        $result->free_result();
        //
        if(!sizeof($return_array['event_details'])) return false;
        // Fetch extra particpants
        $result = $this->db
        ->select('name, email, show_email')
        ->from('portal_schedule_event_external_participants')
        ->where('event_sid', $return_array['event_details']['sid'])
        ->get();
        // Fetch event row
        $return_array['event_external_participants'] = $result->result_array();
        $result->free_result();

        // Fetch training sesion row from LC
        $result = $this->db
        ->select('*')
        ->from('learning_center_training_sessions')
        ->where('sid', $learning_center_training_sessions)
        ->get();
        // Fetch event row
        $return_array['learning_center_training_sessions'] = $result->row_array();
        $result->free_result();
        //

        if(!sizeof($return_array['learning_center_training_sessions'])) return $return_array;

        // Fetch training sesion row from LC
        $result = $this->db
        ->select('*')
        ->from('learning_center_training_sessions_assignments')
        ->where('training_session_sid', $learning_center_training_sessions)
        ->get();
        // Fetch event row
        $return_array['learning_center_training_sessions_assignments'] = $result->result_array();
        $result = $result->free_result();

        return $return_array;
    }



     /**
     * Insert data
     * Created on: 10-05-2019
     *
     * @param $table_name String
     * @param $data Array
     *
     * @return Integer|Bool
     */
    function _insert($table_name, $data){
        $this->db
        ->insert($table_name, $data);
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
     * Update request status to viewed
     * Created on: 14-06-2019
     *
     * @param $event_sid Integer
     *
     */
    function update_request_status($event_sid){
        return $this->db
        ->set('status', '1')
        ->where('event_sid', $event_sid)
        ->update('portal_event_history');
    }


    /**
     * Get user full name
     * Created on: 17-06-2019
     *
     * @param $user_sid Integer
     *
     * @return String|Bool
     */
    function get_interviewer_name($user_sid){
        $result = $this->db
        ->select('CONCAT(first_name, " ", last_name) AS full_name')
        ->where('sid', $user_sid)
        ->from('users')
        ->get();
        $result_arr = $result->row_array()['full_name'];
        $result->free_result();
        return $result_arr;
    }

    /**
     * Get applicant full name
     * Created on: 18-06-2019
     *
     * @param $user_sid Integer
     *
     * @return String|Bool
     */
    function get_applicant_name($user_sid){
        $result = $this->db
        ->select('concat( portal_job_applications.first_name, " ", portal_job_applications.last_name ) as full_name')
        ->where('portal_job_applications.sid', $user_sid)
        ->from('portal_job_applications')
        ->get();
        ///
        $result_arr = $result->row_array()['full_name'];
        $result = $result->free_result();
        return $result_arr;
    }


    /**
     * Get timezone
     * Created on: 02-07-2019
     *
     * @param $type String ('company', 'reset')
     * @param $id Integer
     * @param $reset_field String Optional
     *
     * @return String|Bool
     */
    function get_timezone($type, $id, $reset_field = ''){
        $type = strtolower($type);
        //
        switch ($type) {
            //
            case 'reset':
                if(!$this->get_timezone('clean', 0, $reset_field)) return $this->get_timezone('company', $id);
                return $reset_field;
            break;
            case 'current':
                $timezone = get_current_timezone($this)['key'];
                if(!$this->get_timezone('clean', 0, $timezone)) return $this->get_timezone('company', $id);
                return $timezone;
            break;
            //
            case 'company':
                $result = $this->db
                ->select('timezone')
                ->where('sid', $id)
                ->from('users')
                ->get();
                //
                $timezone = $result->row_array()['timezone'];
                $result = $result->free_result();

                if(!$this->get_timezone('clean', 0, $timezone)) return STORE_DEFAULT_TIMEZONE_ABBR;
                return $timezone;
            break;

            case 'clean':
                $reset_field = trim($reset_field);
                return !preg_match('/^[A-Z]/', $reset_field) || $reset_field == NULL || $reset_field == '' ? false : true;
            break;
        }

        // Reset timezone
        return false;
    }
    function get_user_names($user_ids){
        $user_names = [];
        if(count($user_ids) > 0){
            $result = $this->db
                ->select('sid,concat(first_name," ",last_name) as user_name')
                ->from('users')
                ->where_in('sid', $user_ids)
                ->get();
                $users = $result->result_array();
                $result = $result->free_result();
            foreach($users as $user){
                $user_names[$user['sid']] = $user['user_name'];
                    
            }
        }
        return $user_names;
    }
    function get_applicant_names($applicant_ids){
        $applicant_names = [];
        if(count($applicant_ids) > 0){
            $result = $this->db
                ->select('sid,concat(first_name," ",last_name) as user_name')
                ->from('portal_job_applications')
                ->where_in('sid', $applicant_ids)
                ->get();
                $users = $result->result_array();
                $result = $result->free_result();
            foreach($users as $user){
                $applicant_names[$user['sid']] = $user['user_name'];
                    
            }
        }
        return $applicant_names;
    }
    function get_event_creator($event_id,$creator_sid){
        $result = $this->db
        ->select('user_id')
        ->where('event_sid', $event_id)
        ->from('portal_schedule_event_history')
        ->order_by('created_at', 'ASC')
        ->get();
        $event_change_history = $result->row_array();
        $result->free_result();
        if(isset($event_change_history['user_id'])){
            $creator_sid = $event_change_history['user_id'];
        }
        return $creator_sid;
    }

    function check_and_assigned_video_record($video_sid_arr, $type, $sid, $company_sid){
        if(sizeof($video_sid_arr)){
            foreach($video_sid_arr as $vsid){
                $this->db->from('learning_center_online_videos_assignments');
                $this->db->where('user_type', $type);
                $this->db->where('user_sid', $sid);
                $this->db->where('learning_center_online_videos_sid', $vsid);
                $assigned = $this->db->count_all_results();
                if(!$assigned){
                    $data_to_insert = array();
                    $data_to_insert['learning_center_online_videos_sid'] = $vsid;
                    $data_to_insert['user_type'] = $type;
                    $data_to_insert['user_sid'] = $sid;
                    $data_to_insert['date_assigned'] = date('Y-m-d H:i:s');
                    $data_to_insert['status'] = 1;
                    $data_to_insert['from_training_session'] = 1;
                    $this->db->insert('learning_center_online_videos_assignments', $data_to_insert);
                }
            }
        }
    }
    
}
