<?php

class Dashboard_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function add_listing($lising_data)
    {
        $this->db->insert('portal_job_listings', $lising_data);
        $job_sid = $this->db->insert_id();
        $company_sid = $lising_data['user_sid'];
        $job_sid_count = strlen($job_sid);
        $random_key_length = 22 - $job_sid_count;
        $auto_random_number = $this->random_key($random_key_length);
        $random_number = $auto_random_number . $job_sid;

        $insert_data = array(
            'job_sid' => $job_sid,
            'company_sid' => $company_sid,
            'job_status' => 1,
            'active' => 1,
            'publish_date' => date('Y-m-d H:i:s'),
            'uid' => $random_number
        );

        $this->db->insert('portal_job_listings_feeds_data', $insert_data);
        return $job_sid;
    }

    function random_key($str_length = 22)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvw01234hijk56789';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $str_length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    function my_listings($employer_id, $limit, $start, $keywords)
    {
        $this->db->limit($limit, $start);

        if ($keywords != NULL) {
            $this->db->like('Title', $keywords);
        }

        $this->db->where("user_sid", $employer_id);
        $this->db->order_by("activation_date", 'DESC');
        return $this->db->get("portal_job_listings")->result_array();
    }

    function total_listings($employer_id)
    {
        $this->db->where("user_sid", $employer_id);
        return $this->db->get("portal_job_listings")->num_rows();
    }

    function get_listing($id, $company_id)
    {
        $this->db->where('sid', $id);
        $this->db->where('user_sid', $company_id);
        $resultData = $this->db->get('portal_job_listings');

        if ($resultData->num_rows() > 0) {
            $result = $resultData->result_array();
            return $result[0];
        }
    }

    function check_is_assigned_industry($company_sid)
    {
        $this->db->select('job_category_industries_sid');
        $this->db->where('sid', $company_sid);
        $result = $this->db->get("users")->result_array();

        if (isset($result[0]) && !empty($result[0])) {
            $indus_sid = $result[0]['job_category_industries_sid'];

            if (!empty($indus_sid)) {
                $this->db->select('sid');
                $this->db->where('industry_sid', $indus_sid);
                $num = $this->db->get('categories_2_industry')->result_array();

                if (sizeof($num) > 0) {
                    return $indus_sid;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function get_industry_specific_categories($company_id, $employer_id, $industry_sid)
    {
        $this->db->select('listing_field_list.sid as id, listing_field_list.value');
        $this->db->join('categories_2_industry', 'categories_2_industry.category_sid = listing_field_list.sid');
        $this->db->join('job_category_industries', 'job_category_industries.sid = categories_2_industry.industry_sid');
        $this->db->where('job_category_industries.sid', $industry_sid);
        $this->db->where('categories_2_industry.industry_sid', $industry_sid);
        $this->db->order_by('LOWER(listing_field_list.value)', 'ASC');
        $result_array_1 = $this->db->get('listing_field_list')->result_array();

        $this->db->select('sid as id, value');
        $this->db->where('company_sid', $company_id);
        $this->db->order_by('LOWER(value)', 'ASC');
        $result_array_2 = $this->db->get('listing_field_list')->result_array();
        $result = array_merge($result_array_1, $result_array_2);
        /** sorting * */
        $parent_array = array();

        foreach ($result as $key => $value) {
            $temp = '';
            $temp .= lcfirst($value['value']) . ',' . $value['id'];
            $parent_array[] = $temp;
        }

        sort($parent_array);
        $resultant_array = array();

        foreach ($parent_array as $key => $value) {
            $temp = explode(',', $value);
            $temp2 = array();
            $temp2['id'] = $temp[1];
            $temp2['value'] = ucwords($temp[0]);
            $resultant_array[] = $temp2;
        }
        /** sorting * */
        return $resultant_array;
    }

    function delete($id)
    {
        $this->listing_tracking($id, 'Job Deleted');

        if (is_array($id)) {
            $this->db->where_in('sid', $id);
        } else {
            $this->db->where('sid', $id);
        }

        $this->db->delete('portal_job_listings');
    }

    function add_delete_job_listings($jobId)
    {
        $this->db->where('sid', $jobId);
        $job_data = $this->db->get('portal_job_listings')->result_array();

        if (isset($job_data[0])) {
            $job_data = $job_data[0];
            unset($job_data['sid']);
            unset($job_data['deactivation_date']);
            $job_data['job_sid'] = $jobId;
            $job_data['deletion_date'] = date('Y-m-d H:i:s');
            $this->db->insert('portal_job_listings_deleted', $job_data);
            $this->delete($jobId);
        }
    }

    function archive_job($job_sid)
    {
        $this->listing_tracking($job_sid, 'Job Archived');
        $data_to_update = array();
        $data_to_update['active'] = 2;
        $data['deactivation_date'] = null;
        $this->db->where('sid', $job_sid);
        $this->db->update('portal_job_listings', $data_to_update);
    }

    function delete_img($id)
    {
        $data = array('pictures' => NULL);
        $this->db->where('sid', $id);
        $this->db->update('portal_job_listings', $data);
    }

    function deactive($id)
    {


        

        $this->listing_tracking($id, 'Job De-Activated');
        $data = array();
        $data['active'] = 0;
        $data['deactivation_date'] = date('Y-m-d H:i:s');

        if (is_array($id)) {
            $this->db->where_in('sid', $id);
        } else {
            $this->db->where('sid', $id);
        }
        $this->db->update('portal_job_listings', $data);

        $data = array('job_status' => 0);
        if (is_array($id)) {
            $this->db->where_in('job_sid', $id);
        } else {
            $this->db->where('job_sid', $id);
        }
        $this->db->update('portal_job_listings_feeds_data', $data);
    }

    function active($id)
    {
        $this->listing_tracking($id, 'Job Activated');
        $data = array('active' => 1);

        if (is_array($id)) {
            $this->db->where_in('sid', $id);
        } else {
            $this->db->where('sid', $id);
        }

        $this->db->update('portal_job_listings', $data);

        $data = array('job_status' => 1);
        if (is_array($id)) {
            $this->db->where_in('job_sid', $id);
        } else {
            $this->db->where('job_sid', $id);
        }
        $this->db->update('portal_job_listings_feeds_data', $data);
    }

    function listing_tracking($id, $status)
    {
        $current_data = $this->session->userdata('logged_in');
        $company_sid = $current_data['company_detail']['sid'];
        $employee_sid = $current_data['employer_detail']['sid'];

        if (is_array($id)) {
            foreach ($id as $job_id) {
                $modifications_data = array();
                $this->db->select('active');
                $this->db->where('sid', $job_id);
                $return_obj = $this->db->get('portal_job_listings');
                $return_arr = $return_obj->result_array();
                $return_obj->free_result();
                $previous_status = $return_arr[0]['active'];

                if ($previous_status == 0) {
                    $modifications_data['old_status'] = 'Inactive Job';
                }

                if ($previous_status == 1) {
                    $modifications_data['old_status'] = 'Active Job';
                }

                if ($previous_status == 2) {
                    $modifications_data['old_status'] = 'Archived Job';
                }

                $modifications_data['company_sid'] = $company_sid;
                $modifications_data['employee_sid'] = $employee_sid;
                $modifications_data['job_sid'] = $job_id;

                $modifications_data['new_status'] = $status;
                $modifications_data['modification_date'] = date('Y-m-d H:i:s');
                $this->db->insert('portal_job_listings_tracking', $modifications_data);
            }
        } else {
            $modifications_data = array();
            $this->db->select('active');
            $this->db->where('sid', $id);
            $return_obj = $this->db->get('portal_job_listings');
            $return_arr = $return_obj->result_array();
            $return_obj->free_result();
            $previous_status = $return_arr[0]['active'];

            if ($previous_status == 0) {
                $modifications_data['old_status'] = 'Inactive Job';
            }

            if ($previous_status == 1) {
                $modifications_data['old_status'] = 'Active Job';
            }

            if ($previous_status == 2) {
                $modifications_data['old_status'] = 'Archived Job';
            }

            $modifications_data['company_sid'] = $company_sid;
            $modifications_data['employee_sid'] = $employee_sid;
            $modifications_data['job_sid'] = $id;
            $modifications_data['new_status'] = $status;
            $modifications_data['modification_date'] = date('Y-m-d H:i:s');
            $this->db->insert('portal_job_listings_tracking', $modifications_data);
        }
    }

    function update_listing($id, $data)
    {
        $this->db->where('sid', $id);
        $this->db->update('portal_job_listings', $data);
    }

    function add_modifications_record($data)
    {
        $this->db->insert('portal_job_listings_modifications', $data);
    }

    function getAllEvents($company_sid)
    {
        $this->db->select('*');
        $this->db->where('companys_sid', $company_sid);
        $this->db->order_by('sid', 'DESC');
        $events = $this->db->get('portal_schedule_event')->result_array();

        foreach ($events as $key => $event) {
            $attendees = array();

            if (!empty($event['interviewer'])) {
                $attendees = explode(',', $event['interviewer']);
            }

            $events[$key]['interviewer'] = $attendees;
            $startTime24Hr = date("H:i", strtotime($event['eventstarttime']));
            $events[$key]['eventstarttime24Hr'] = $startTime24Hr;
            $endTime24Hr = date("H:i", strtotime($event['eventendtime']));
            $events[$key]['eventendtime24Hr'] = $endTime24Hr;
            $events[$key]['f_name_uc'] = "";
            $events[$key]['l_name_uc'] = "";
            $events[$key]['editFlag'] = "false";
            $date_applied = explode('-', $event['date']);
            $events[$key]['frontDate'] = $date_applied['1'] . "-" . $date_applied['2'] . "-" . $date_applied['0'];
            $events[$key]['backDate'] = $events[$key]['date'];
            $events[$key]['category_uc'] = ucwords($events[$key]['category']);
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
                    $this->db->select('first_name,last_name');
                    $this->db->where('sid', $row4['applicant_job_sid']);
                    $result = $this->db->get('portal_job_applications')->result_array();
                } else if ($row4['users_type'] == "employee") {
                    $this->db->select('first_name,last_name');
                    $this->db->where('sid', $row4['applicant_job_sid']);
                    $this->db->order_by(SORT_COLUMN, SORT_ORDER);
                    $result = $this->db->get('users')->result_array();
                }

                if (!empty($result)) {
                    $row4['f_name_uc'] = ": " . ucwords($result[0]['first_name']);
                    $row4['l_name_uc'] = ucwords($result[0]['last_name']);
                } else {
                    $row4['f_name_uc'] = "";
                    $row4['l_name_uc'] = "";
                }
            } else {
                $row4['f_name_uc'] = "";
                $row4['l_name_uc'] = "";
            }
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
                    $this->db->order_by(SORT_COLUMN, SORT_ORDER);
                    $result = $this->db->get('users')->result_array();
                }

                $row4['f_name_uc'] = ": " . ucwords($result[0]['first_name']);
                $row4['l_name_uc'] = ucwords($result[0]['last_name']);
            } else {
                $row4['f_name_uc'] = "";
                $row4['l_name_uc'] = "";
            }
            $events[] = $row4;
        }
        return $events;
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

                if ($row4['applicant_job_sid'] != NULL && $row4['applicant_job_sid'] != 0) {
                    if ($row4['users_type'] == "applicant") {
                        $this->db->select('first_name,last_name');
                        $this->db->where('sid', $row4['applicant_job_sid']);
                        $result = $this->db->get('portal_job_applications')->result_array();
                    } else if ($row4['users_type'] == "employee") {
                        $this->db->select('first_name,last_name');
                        $this->db->where('sid', $row4['applicant_job_sid']);
                        $this->db->order_by(SORT_COLUMN, SORT_ORDER);
                        $result = $this->db->get('users')->result_array();
                    }

                    if (!empty($result)) {
                        $row4['f_name_uc'] = ": " . ucwords($result[0]['first_name']);
                        $row4['l_name_uc'] = ucwords($result[0]['last_name']);
                    } else {
                        $row4['f_name_uc'] = '';
                        $row4['l_name_uc'] = '';
                    }
                } else {
                    $row4['f_name_uc'] = "";
                    $row4['l_name_uc'] = "";
                }
                $events[] = $row4;
            }
        }
        return $events;
    }

    function get_applicants($id)
    {
        $this->db->select('portal_applicant_jobs_list.sid as portal_applicant_jobs_list_sid');
        $this->db->select('portal_applicant_jobs_list.job_sid');
        $this->db->select('portal_job_applications.sid');
        $this->db->select('portal_job_applications.first_name');
        $this->db->select('portal_job_applications.last_name');
        $this->db->select('portal_job_applications.email');
        $this->db->where('portal_applicant_jobs_list.company_sid', $id);
        $this->db->where('portal_applicant_jobs_list.archived', 0);
        $this->db->where('portal_job_applications.hired_status', 0);
        $this->db->order_by('portal_applicant_jobs_list.sid', 'DESC');
        $this->db->join('portal_job_applications', 'portal_job_applications.sid = portal_applicant_jobs_list.portal_job_applications_sid', 'left');
        $applicant_result = $this->db->get('portal_applicant_jobs_list')->result_array();
        return $applicant_result;
    }

    function get_applicants_details($sid)
    {
        $this->db->where('sid', $sid);
        $result = $this->db->get('portal_job_applications')->result_array();

        if (!empty($result)) {
            return $result[0];
        }
    }

    function run_query($query)
    {
        return $this->db->query($query);
    }

    function get_applicant_email($applicant_sid)
    {
        $this->db->select('email');
        $this->db->where('sid', $applicant_sid);
        $result = $this->db->get('portal_job_applications')->result_array();
        return $result[0]['email'];
    }

    function get_employee_email($employee_sid)
    {
        $this->db->select('email');
        $this->db->where('sid', $employee_sid);
        $result = $this->db->get('users')->result_array();
        return $result[0]['email'];
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

    function get_employee_information($employee_sid)
    {
        $this->db->select('*');
        $this->db->where('sid', $employee_sid);
        $this->db->order_by(SORT_COLUMN, SORT_ORDER);
        $result = $this->db->get('users')->result_array();
        return $result[0];
    }

    function get_employee_name($employee_sid)
    {
        $this->db->select('first_name, last_name, email');
        $this->db->where('sid', $employee_sid);
        $this->db->order_by(SORT_COLUMN, SORT_ORDER);
        $result = $this->db->get('users')->result_array();
        return $result[0];
    }

    //my setting functions
    function get_portal_detail($employer_id)
    {
        $this->db->where('user_sid', $employer_id);
        $result = $this->db->get('portal_employer')->result_array();
        return $result[0];
    }

    function get_portal_detail_by_domain_name($name)
    {
        $this->db->where('sub_domain', $name);
        $result = $this->db->get('portal_employer')->num_rows();

        if ($result == 0) {
            return true;
        } else {
            return false;
        }
    }

    function check_existing_domain($name)
    {
        $this->db->where('sub_domain', $name);
        $result = $this->db->get('portal_employer')->num_rows();

        if ($result == 1) {
            return '1';
        } else {
            return '0';
        }
    }

    function get_company_detail($sid)
    {
        $this->db->where('sid', $sid);
        $result = $this->db->get('users')->result_array();

        if (!empty($result)) {
            return $result[0];
        }
    }

    function validate_employee_email($company_sid, $email)
    {
        $this->db->where('parent_sid', $company_sid);
        $this->db->where('email', $email);
        $this->db->where('email <>', '');
        $this->db->where('is_executive_admin', 0);
        $results = $this->db->get('users')->result_array();

        if (sizeof($results) > 0) {
            return true;
        } else {
            return false;
        }
    }

    function get_talent_configuration($company_sid)
    {
        $this->db->where('company_sid', $company_sid);
        $result = $this->db->get('talent_network_content_config')->result_array();

        if (!empty($result)) {
            return $result[0];
        } else {
            $insert_array = array();
            $insert_array['company_sid'] = $company_sid;
            $insert_array['title'] = 'WHY JOIN OUR TALENT NETWORK?';
            $insert_array['content'] = "<p>Joining our Talent Network will enhance your job search and application process. Whether you choose to apply or just leave your information, we look forward to staying connected with you.</p><ul><li>Receive alerts with new job opportunities that match your interests</li><li>Receive relevant communications and updates from our organization</li><li>Share job opportunities with family and friends through Social Media or email</li></ul>";
            $insert_array['picture_or_video'] = "none";
            $this->db->insert('talent_network_content_config', $insert_array);
            $result = $this->db->get('talent_network_content_config')->result_array();
            return $result[0];
        }
    }

    function save_talent_configuration($data)
    {
        $this->db->where("company_sid", $data['company_sid']);
        $result = $this->db->get('talent_network_content_config')->num_rows();

        if ($result > 0) {
            $this->db->where("company_sid", $data['company_sid']);
            $result = $this->db->update('talent_network_content_config', $data);
        } else { // insert
            $result = $this->db->insert('talent_network_content_config', $data);
        }

        return $result;
    }

    function delete_logo($id)
    {
        $data = array('Logo' => NULL);
        $this->db->where('sid', $id);
        $this->db->update('users', $data);
    }

    function update_company($company_data, $id)
    {
        $this->db->where('sid', $id);
        $this->db->update('users', $company_data);
        //-------------Updating Company name in employer row if COmpnay name is update---------
        $this->db->where('parent_sid', $id);
        $this->db->set('CompanyName', $company_data['CompanyName']);
        $this->db->update('users');
    }

    function update_user($sid, $data, $type = NULL)
    {
        $this->db->where('sid', $sid);
        // Added on: 27-06-2019
        if (isset($data['team_sid']) && $data['team_sid'] == '') $data['team_sid'] = 0;
        if (isset($data['dob']) && $data['dob'] == '') $data['dob'] = NULL;
        $result = $this->db->update('users', $data);
        (!$result) ? 'false' : 'true';
    }

    function get_company_video($company_sid)
    {
        $this->db->select('video_source, YouTubeVideo');
        $this->db->where('sid', $company_sid);
        $records_obj = $this->db->get('users');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        return $records_arr;
    }

    function update_applicant($sid, $data, $type = NULL)
    {
        $this->db->where('sid', $sid);
        $result = $this->db->update('portal_job_applications', $data);
        (!$result) ? 'false' : 'true';
    }

    function update_portal($portal_data, $id)
    {
        $this->db->where('user_sid', $id);
        $this->db->update('portal_employer', $portal_data);
    }

    function update_session_details($company_sid = 0, $employer_sid)
    {
        //$this->db->select('sid, Logo, username, career_page_type, email, ip_address, registration_date, expiry_date, active, activation_key, verification_key, parent_sid, Location_Country, Location_State, Location_City, Location_Address, PhoneNumber, CompanyName, ContactName, WebSite, Location_ZipCode, profile_picture, first_name, last_name, access_level, job_title, full_employment_application, background_check, job_listing_template_group, linkedin_profile_url, discount_type, discount_amount, has_job_approval_rights, has_applicant_approval_rights, is_primary_admin, is_executive_admin, marketing_agency_sid, is_paid, job_category_industries_sid');
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('sid', $employer_sid);
        $this->db->limit(1);
        $employer = $this->db->get()->result_array();

        if ($company_sid == 0) {
            $company_sid = $employer[0]['parent_sid'];
        }

        //$this->db->select('sid, Logo, username, career_page_type, email, ip_address, registration_date, expiry_date, active, activation_key, verification_key, parent_sid, Location_Country, Location_State, Location_City, Location_Address, PhoneNumber, CompanyName, ContactName, WebSite, Location_ZipCode, profile_picture, first_name, last_name, access_level, job_title, full_employment_application, background_check, job_listing_template_group, linkedin_profile_url, discount_type, discount_amount, has_job_approval_rights, has_applicant_approval_rights, is_primary_admin, is_executive_admin, marketing_agency_sid, is_paid, job_category_industries_sid');
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('sid', $company_sid);
        $this->db->limit(1);
        $company = $this->db->get()->result_array();
        $this->db->select('*');
        $this->db->from('portal_employer');
        $this->db->where('user_sid', $company_sid);
        $this->db->limit(1);
        $portal = $this->db->get()->result_array();
        /*      Temporary blocked it  // fetch clocked in Status
          $this->db->select('sid, attendance_type, attendance_date');
          $this->db->where('company_sid', $company_sid);
          $this->db->where('employer_sid', $employer_sid);
          $this->db->order_by('attendance_date', 'DESC');
          $this->db->limit(1);
          $attendance = $this->db->get('attendance')->result_array();

          if (!empty($attendance)) {
          $attendance = $attendance[0];
          }
         */
        $data = array();
        $attendance = array();
        $data['employer'] = $employer[0];
        $data['company'] = $company[0];
        $data['portal'] = $portal[0];
        $data['clocked_status'] = $attendance;
        return $data;
    }

    //-------my setting end-----------------
    function get_not_hired_applicants($company_sid)
    {
        $this->db->select('sid');
        $this->db->where('employer_sid', $company_sid);
        $this->db->where('hired_status', 0);
        $applicants = $this->db->get('portal_job_applications')->result_array();

        if (sizeof($applicants) > 0) {
            $applicant_sids = array();

            foreach ($applicants as $applicant) {
                $applicant_sids[] = $applicant['sid'];
            }

            return $applicant_sids;
        } else {
            return array();
        }
    }

    function get_all_company_background_checks($company_sid)
    {
        $this->db->select('date_applied');
        $this->db->where('company_sid', $company_sid);
        return $this->db->get('background_check_orders')->result_array();
    }

    function get_all_company_background_checks_count($company_sid, $this_month_start = NULL, $this_month_end = NULL)
    {
        $this->db->select('sid');
        $this->db->where('company_sid', $company_sid);

        if ($this_month_start != NULL && $this_month_end != NULL) {
            $this->db->where("date_applied >= ", $this_month_start);
            $this->db->where("date_applied <= ", $this_month_end);
        }

        $this->db->from('background_check_orders');
        return $this->db->count_all_results();
    }

    function get_all_company_jobs($company_sid, $employer_sid = 0)
    {
        if ($employer_sid > 0) {
            $this->db->select('portal_job_listings_visibility.job_sid');
            $this->db->select('portal_job_listings.activation_date');
            $this->db->select('portal_job_listings.active');
            $this->db->select('portal_job_listings.approval_status');
            $this->db->where('portal_job_listings_visibility.company_sid', $company_sid);
            $this->db->where('portal_job_listings_visibility.employer_sid', $employer_sid);
            $this->db->join('portal_job_listings', 'portal_job_listings.sid = portal_job_listings_visibility.job_sid', 'left');
            return $this->db->get('portal_job_listings_visibility')->result_array();
        } else {
            $this->db->select('portal_job_listings.sid as job_sid');
            $this->db->select('portal_job_listings.activation_date');
            $this->db->select('portal_job_listings.active');
            $this->db->select('portal_job_listings.approval_status');
            $this->db->where('user_sid', $company_sid);
            return $this->db->get('portal_job_listings')->result_array();
        }
    }

    function get_all_company_jobs_count($company_sid, $employer_sid = 0, $active = 0)
    { // hassan working area
        if ($employer_sid > 0) {
            $this->db->select('portal_job_listings_visibility.job_sid');
            $this->db->select('portal_job_listings.activation_date');
            $this->db->select('portal_job_listings.active');
            $this->db->select('portal_job_listings.approval_status');
            $this->db->where('portal_job_listings_visibility.company_sid', $company_sid);

            if ($active == 1) {
                $this->db->where('portal_job_listings.active', $active);
            }

            $this->db->where('portal_job_listings_visibility.employer_sid', $employer_sid);
            $this->db->join('portal_job_listings', 'portal_job_listings.sid = portal_job_listings_visibility.job_sid', 'left');
            $this->db->from('portal_job_listings_visibility');
            return $this->db->count_all_results();
        } else {
            $this->db->select('portal_job_listings.sid as job_sid');
            $this->db->select('portal_job_listings.activation_date');
            $this->db->select('portal_job_listings.active');
            $this->db->select('portal_job_listings.approval_status');

            if ($active == 1) {
                $this->db->where('portal_job_listings.active', $active);
            }

            $this->db->where('user_sid', $company_sid);
            $this->db->from('portal_job_listings');
            return $this->db->count_all_results();
        }
    }

    function GetAllJobsCompanySpecific($company_sid, $keywords, $approval_status, $limit = 0, $start = 1)
    {
        $this->db->select('*');
        $this->db->group_start();
        $this->db->where('active', 1);
        $this->db->or_where('active', 0);
        $this->db->group_end();
        $this->db->where('user_sid', $company_sid);
        $this->db->where('approval_status', $approval_status);

        if ($limit > 0) {
            $this->db->limit($limit, $start);
        }

        if (!empty($keywords)) {
            $this->db->like('Title', $keywords);
        }

        $this->db->order_by('portal_job_listings.sid', 'DESC');
        return $this->db->get('portal_job_listings')->result_array();
    }

    function GetAllJobsCompanySpecificCount($company_sid, $keywords, $approval_status, $limit = 0, $start = 1, $employer_sid = null)
    {
        $this->db->select('sid');
        $this->db->group_start();
        $this->db->where('active', 1);
        $this->db->or_where('active', 0);
        $this->db->group_end();
        $this->db->where('user_sid', $company_sid);
        $this->db->where('approval_status', $approval_status);

        if ($limit > 0) {
            $this->db->limit($limit, $start);
        }

        if (!empty($keywords)) {
            $this->db->like('Title', $keywords);
        }

        $this->db->from('portal_job_listings');
        return $this->db->count_all_results();
        //        return $this->db->get('portal_job_listings')->result_array();
    }

    function get_all_company_applicants($company_sid, $employer_sid = 0)
    {
        if ($employer_sid > 0) {
            $this->db->select('portal_job_listings_visibility.job_sid');
            $this->db->select('portal_applicant_jobs_list.date_applied');
            $this->db->select('portal_applicant_jobs_list.approval_status');
            $this->db->select('portal_job_applications.hired_status');
            $this->db->join('portal_applicant_jobs_list', 'portal_applicant_jobs_list.job_sid = portal_job_listings_visibility.job_sid');
            $this->db->join('portal_job_applications', 'portal_job_applications.sid = portal_applicant_jobs_list.portal_job_applications_sid', 'left');
            $this->db->where('portal_job_listings_visibility.company_sid', $company_sid);
            $this->db->where('portal_job_listings_visibility.employer_sid', $employer_sid);
            $this->db->where('portal_job_applications.hired_status', 0);
            return $this->db->get('portal_job_listings_visibility')->result_array();
        } else {
            $this->db->select('portal_applicant_jobs_list.job_sid');
            $this->db->select('portal_applicant_jobs_list.date_applied');
            $this->db->select('portal_applicant_jobs_list.approval_status');
            $this->db->select('portal_job_applications.hired_status');
            $this->db->where('portal_applicant_jobs_list.company_sid', $company_sid);
            $this->db->where('portal_job_applications.hired_status', 0);
            $this->db->join('portal_job_applications', 'portal_job_applications.sid = portal_applicant_jobs_list.portal_job_applications_sid', 'left');
            return $this->db->get('portal_applicant_jobs_list')->result_array();
        }
    }

    function get_all_company_applicants_count($company_sid, $employer_sid = 0, $today_start = NULL, $today_end = NULL, $approval_status = NULL)
    {
        $count = 0;
        //
        if ($employer_sid > 0) {
            $this->db->select('portal_applicant_jobs_list.sid');
            $this->db->select('portal_job_listings_visibility.job_sid');
            $this->db->select('portal_applicant_jobs_list.date_applied');
            $this->db->select('portal_applicant_jobs_list.approval_status');
            $this->db->select('portal_job_applications.hired_status');
            $this->db->join('portal_applicant_jobs_list', 'portal_applicant_jobs_list.job_sid = portal_job_listings_visibility.job_sid');
            $this->db->join('portal_job_applications', 'portal_job_applications.sid = portal_applicant_jobs_list.portal_job_applications_sid', 'left');
            $this->db->where('portal_job_listings_visibility.company_sid', $company_sid);
            $this->db->where('portal_job_listings_visibility.employer_sid', $employer_sid);
            $this->db->where('portal_job_applications.hired_status', 0);
            $this->db->where('portal_applicant_jobs_list.archived', 0);

            if ($today_start != NULL && $today_end != NULL) {
                $this->db->where('portal_applicant_jobs_list.date_applied >= ', $today_start);
                $this->db->where('portal_applicant_jobs_list.date_applied <= ', $today_end);
            }

            if ($approval_status != NULL) {
                $this->db->where('portal_applicant_jobs_list.approval_status', $approval_status);
            }

            // $this->db->from('portal_job_listings_visibility');
            // $count = $this->db->count_all_results();
            $result = $this->db->get('portal_job_listings_visibility')->result_array();
            $job_listing_sids = array();

            if (!empty($result)) {
                $job_listing_sids = array_column($result, "sid");
            }

            $job_fair_applicant = getEmployeeJobfairApplicant($company_sid, $employer_sid, $job_listing_sids, $today_start, $today_end, $approval_status, 'yes');

            $count = count($result) + $job_fair_applicant;




            // return $this->db->get('portal_job_listings_visibility')->result_array();
        } else {
            $this->db->select('portal_applicant_jobs_list.job_sid');
            $this->db->select('portal_applicant_jobs_list.date_applied');
            $this->db->select('portal_applicant_jobs_list.approval_status');
            $this->db->select('portal_job_applications.hired_status');
            $this->db->where('portal_applicant_jobs_list.company_sid', $company_sid);
            $this->db->where('portal_job_applications.hired_status', 0);
            $this->db->where('portal_applicant_jobs_list.archived', 0);

            if ($today_start != NULL && $today_end != NULL) {
                $this->db->where('portal_applicant_jobs_list.date_applied >= ', $today_start);
                $this->db->where('portal_applicant_jobs_list.date_applied <= ', $today_end);
            }

            if ($approval_status != NULL) {
                $this->db->where('portal_applicant_jobs_list.approval_status', $approval_status);
            }

            $this->db->join('portal_job_applications', 'portal_job_applications.sid = portal_applicant_jobs_list.portal_job_applications_sid', 'left');
            $this->db->from('portal_applicant_jobs_list');
            $count = $this->db->count_all_results();

            // return $this->db->get('portal_applicant_jobs_list')->result_array();
        }

        return $count;
    }

    function get_all_active_inactive_applicants_count($company_sid, $employer_sid = 0, $today_start = NULL, $today_end = NULL, $approval_status = NULL)
    {
        $count = 0;
        //
        if ($employer_sid > 0) {
            $this->db->select('portal_applicant_jobs_list.sid');
            $this->db->select('portal_job_listings_visibility.job_sid');
            $this->db->select('portal_applicant_jobs_list.date_applied');
            $this->db->select('portal_applicant_jobs_list.approval_status');
            $this->db->select('portal_job_applications.hired_status');
            $this->db->join('portal_applicant_jobs_list', 'portal_applicant_jobs_list.job_sid = portal_job_listings_visibility.job_sid');
            $this->db->join('portal_job_applications', 'portal_job_applications.sid = portal_applicant_jobs_list.portal_job_applications_sid', 'left');
            $this->db->where('portal_job_listings_visibility.company_sid', $company_sid);
            $this->db->where('portal_job_listings_visibility.employer_sid', $employer_sid);
            $this->db->where('portal_job_applications.hired_status', 0);

            if ($today_start != NULL && $today_end != NULL) {
                $this->db->where('portal_applicant_jobs_list.date_applied >= ', $today_start);
                $this->db->where('portal_applicant_jobs_list.date_applied <= ', $today_end);
            }

            if ($approval_status != NULL) {
                $this->db->where('portal_applicant_jobs_list.approval_status', $approval_status);
            }

            // $this->db->from('portal_job_listings_visibility');
            // $count = $this->db->count_all_results();
            $result = $this->db->get('portal_job_listings_visibility')->result_array();
            $job_listing_sids = array();

            if (!empty($result)) {
                $job_listing_sids = array_column($result, "sid");
            }

            $job_fair_applicant = getEmployeeJobfairApplicant($company_sid, $employer_sid, $job_listing_sids, $today_start, $today_end, $approval_status, 'yes');


            $count = count($result) + $job_fair_applicant;

            // return $this->db->get('portal_job_listings_visibility')->result_array();
        } else {
            $this->db->select('portal_applicant_jobs_list.job_sid');
            $this->db->select('portal_applicant_jobs_list.date_applied');
            $this->db->select('portal_applicant_jobs_list.approval_status');
            $this->db->select('portal_job_applications.hired_status');
            $this->db->where('portal_applicant_jobs_list.company_sid', $company_sid);
            $this->db->where('portal_job_applications.hired_status', 0);

            if ($today_start != NULL && $today_end != NULL) {
                $this->db->where('portal_applicant_jobs_list.date_applied >= ', $today_start);
                $this->db->where('portal_applicant_jobs_list.date_applied <= ', $today_end);
            }

            if ($approval_status != NULL) {
                $this->db->where('portal_applicant_jobs_list.approval_status', $approval_status);
            }

            $this->db->join('portal_job_applications', 'portal_job_applications.sid = portal_applicant_jobs_list.portal_job_applications_sid', 'left');
            $this->db->from('portal_applicant_jobs_list');

            $count = $this->db->count_all_results();
            // return $this->db->get('portal_applicant_jobs_list')->result_array();
        }

        return $count;
    }

    function get_background_checks_count($company_id, $monthly = '')
    {
        $this->db->select('sid');
        $this->db->where('company_sid', $company_id);

        if ($monthly == 'monthly') {
            $this->db->where("date_applied >= ", date('Y-m-01 00:00:00'));
            $this->db->where("date_applied <= ", date('Y-m-d 23:59:59'));
        }

        $this->db->from('background_check_orders');
        return $this->db->count_all_results();
    }

    function get_applicants_count($company_sid, $today = false)
    { //getting applicant details
        $applicants_not_hired_sids = $this->get_not_hired_applicants($company_sid);
        $this->db->where("company_sid", $company_sid);
        //$this->db->where("hired_status", 0);

        if (sizeof($applicants_not_hired_sids) > 0) {
            $this->db->where_in('portal_job_applications_sid', $applicants_not_hired_sids);
        } else {
            $result = 0;
        }

        if ($today == true) {
            $this->db->where("date_applied >= ", date('Y-m-d 00:00:00'));
            $this->db->where("date_applied <= ", date('Y-m-d 23:59:59'));
        }

        $result = $this->db->get('portal_applicant_jobs_list')->num_rows();
        return $result;
    }

    function get_visitors($employerId)
    { //getting visitor details
        $this->db->select_sum('views');
        $this->db->where("user_sid", $employerId);
        $result = $this->db->get('portal_job_listings')->result_array();

        if ($result[0]['views'] == NULL) {
            return 0;
        } else {
            return $result[0]['views'];
        }
    }

    function deleteEvent($id)
    {
        $this->db->where('sid', $id);
        $this->db->delete('portal_schedule_event');
    }

    function domain_name_by_company_id($company_id)
    {
        $this->db->select('sub_domain');
        $this->db->where('user_sid', $company_id);
        $result = $this->db->get('portal_employer')->result_array();
        return $result[0]['sub_domain'];
    }

    function update_existing_domain($data, $sid)
    {
        $this->db->where('sid', $sid);
        $this->db->update('portal_employer', $data);
    }

    function getEventDetail($event_id)
    {
        $this->db->where('sid', $event_id);
        $result = $this->db->get('portal_schedule_event')->result_array();
        return $result[0];
    }

    function add_subaccount($data)
    {
        $this->db->insert('users', $data);
        $result = $this->db->affected_rows();

        if ($result != 1) {
            $this->session->set_flashdata('message', '<b>Failed: </b>Could not add your colleague, Please try Again!');
            $result = 'success';
        } else {
            $this->session->set_flashdata('message', '<b>Success: </b>Your colleage is added to the system! To add more fill the form again.');
            $result = 'fail';
        }

        return $result;
    }

    function add_emergency_contacts($data)
    {
        $this->db->insert('emergency_contacts', $data);
        $result = $this->db->affected_rows();

        if ($result != 1) {
            $this->session->set_flashdata('message', '<b>Failed: </b>Could not add emergency contact, Please try Again!');
            $result = 'success';
        } else {
            $this->session->set_flashdata('message', '<b>Success: </b>Emergency contact added successfully.');
            $result = 'fail';
        }

        return $result;
    }

    function edit_emergency_contacts($data, $sid)
    {
        $this->db->where('sid', $sid);
        $this->db->update('emergency_contacts', $data);
        $result = $this->db->affected_rows();

        if ($result != 1) {
            $this->session->set_flashdata('message', '<b>Failed: </b>Could not update emergency contact, Please try Again!');
            $result = 'success';
        } else {
            $this->session->set_flashdata('message', '<b>Success: </b>Emergency contact updated successfully.');
            $result = 'fail';
        }

        return $result;
    }

    function getEmployerDetail($id)
    {
        $this->db->where('sid', $id);
        return $this->db->get('users')->row_array();
    }

    function getCompanyDetail($id)
    {
        $this->db->where('sid', $id);
        return $this->db->get('users')->row_array();
    }

    function compnayJobCount($id)
    {
        $this->db->where('user_sid', $id);
        return $this->db->get('portal_job_listings')->num_rows();
    }

    function get_all_company_events($company_sid, $employer_sid = 0)
    {
        $this->db->select('date');
        $this->db->where('companys_sid', $company_sid);

        if ($employer_sid > 0) {
            $this->db->where('employers_sid', $employer_sid);
            $this->db->where("find_in_set('" . $employer_sid . "', interviewer)");
        }

        return $this->db->get('portal_schedule_event')->result_array();
    }

    function company_employee_events_count($company_sid, $employer_sid = 0, $today_start = NULL, $today_end = NULL)
    {
        $this->db->select('sid, employer_sid');
        $this->db->where('companys_sid', $company_sid);

        if ($employer_sid > 0) {
            $this->db->group_start();
            $this->db->where("find_in_set('" . $employer_sid . "', interviewer)");
            $this->db->or_where('employers_sid', $employer_sid);
            $this->db->group_end();
        }

        if ($today_start != NULL && $today_end != NULL) {
            $this->db->where("date >= ", $today_start);
            $this->db->where("date <= ", $today_end);
        }

        $this->db->from('portal_schedule_event');

        return $this->db->count_all_results();
    }

    function get_all_unread_messages($employer_sid)
    {
        $this->db->select('date');
        $this->db->where('to_id', $employer_sid);
        $this->db->where('outbox', 0);
        $this->db->group_start();
        $this->db->where('job_id', '');
        $this->db->or_where('job_id', NULL);
        $this->db->group_end();
        $this->db->where('status', 0);
        return $this->db->get('private_message')->result_array();
    }

    function get_all_unread_messages_count($employer_sid)
    {
        $this->db->select('sid');
        $this->db->where('to_id', $employer_sid);
        $this->db->where('outbox', 0);
        $this->db->group_start();
        $this->db->where('job_id', '');
        $this->db->or_where('job_id', NULL);
        $this->db->group_end();
        $this->db->where('status', 0);
        $this->db->from('private_message');
        return $this->db->count_all_results();
    }


    function get_all_auth_documents_assigned_count($company_id, $employer_id, $companyEmployeesForVerification = FALSE, $companyApplicantsForVerification = FALSE)
    {
        if (!$companyEmployeesForVerification) {
            $companyEmployeesForVerification = $this->getAllCompanyInactiveEmployee($company_id);
        }
        //
        if (!$companyApplicantsForVerification) {
            $companyApplicantsForVerification = $this->getAllCompanyInactiveApplicant($company_id);
        }
        //
        $data = $this->db
            ->select("user_type, user_sid")
            ->join('documents_assigned', 'authorized_document_assigned_manager.document_assigned_sid = documents_assigned.sid', 'inner')
            ->where('authorized_document_assigned_manager.assigned_to_sid', $employer_id)
            ->where('authorized_document_assigned_manager.company_sid', $company_id)
            // ->where('authorized_document_assigned_manager.assigned_status', 1)
            ->where('documents_assigned.archive', 0)
            ->where('documents_assigned.status', 1)
            ->group_start()
            ->where('documents_assigned.document_description like "%{{authorized_signature}}%"', null, false)
            ->or_where('documents_assigned.document_description like "%{{authorized_signature_date}}%"', null, false)
            ->or_where('documents_assigned.document_description like "%{{authorized_editable_date}}%"', null, false)
            ->group_end()
            ->get('authorized_document_assigned_manager');
        $data_obj = $data->result_array();

        // ->count_all_results('authorized_document_assigned_manager');
        //
        foreach ($data_obj as $key => $v) {
            if ($v["user_type"] == "applicant") {
                if (in_array($v["user_sid"], $companyApplicantsForVerification)) {
                    unset($data_obj[$key]);
                }
            }

            if ($v["user_type"] == "employee") {
                if (in_array($v["user_sid"], $companyEmployeesForVerification)) {
                    unset($data_obj[$key]);
                }
            }
        }
        //
        return count($data_obj);
    }


    function get_all_auth_documents_assigned_today_count($company_id, $employer_id, $companyEmployeesForVerification = FALSE, $companyApplicantsForVerification = FALSE)
    {
        if (!$companyEmployeesForVerification) {
            $companyEmployeesForVerification = $this->getAllCompanyInactiveEmployee($company_id);
        }
        //
        if (!$companyApplicantsForVerification) {
            $companyApplicantsForVerification = $this->getAllCompanyInactiveApplicant($company_id);
        }
        //
        $this->db
            ->join('documents_assigned', 'authorized_document_assigned_manager.document_assigned_sid = documents_assigned.sid', 'inner')
            ->where('authorized_document_assigned_manager.assigned_to_sid', $employer_id)
            ->where('authorized_document_assigned_manager.company_sid', $company_id)
            ->where("assigned_by_date >= ", date('Y-m-d 00:00:00'))
            ->where("assigned_by_date <= ", date('Y-m-d 23:59:59'))
            ->where('documents_assigned.archive', 0)
            ->where('documents_assigned.status', 1);
        //
        // if(!empty($inactive_employee_sid)){
        //     $this->db->group_start()
        //     ->where_not_in('documents_assigned.user_sid', $inactive_employee_sid)
        //     ->where('documents_assigned.user_type', 'employee')
        //     ->group_end();
        // }
        // //
        // if(!empty($inactive_applicant_sid)){
        //     $this->db->group_start()
        //     ->where_not_in('documents_assigned.user_sid', $inactive_applicant_sid)
        //     ->where('documents_assigned.user_type', 'applicant')
        //     ->group_end();
        // }
        //
        $data = $this->db
            ->select("user_type, user_sid")
            ->join('documents_assigned', 'authorized_document_assigned_manager.document_assigned_sid = documents_assigned.sid', 'inner')
            ->where('authorized_document_assigned_manager.assigned_to_sid', $employer_id)
            ->where('authorized_document_assigned_manager.company_sid', $company_id)
            ->where('documents_assigned.archive', 0)
            ->where('documents_assigned.status', 1)
            ->group_start()
            ->where('documents_assigned.document_description like "%{{authorized_signature}}%"', null, false)
            ->or_where('documents_assigned.document_description like "%{{authorized_signature_date}}%"', null, false)
            ->group_end()
            ->group_start()
            ->where('documents_assigned.authorized_signature IS NULL', null)
            ->or_where('documents_assigned.authorized_signature = ""', null)
            ->group_end()
            ->get('authorized_document_assigned_manager');

        $data_obj = $data->result_array();
        //
        foreach ($data_obj as $key => $v) {
            if ($v["user_type"] == "applicant") {
                if (in_array($v["user_sid"], $companyApplicantsForVerification)) {
                    unset($data_obj[$key]);
                }
            }

            if ($v["user_type"] == "employee") {
                if (in_array($v["user_sid"], $companyEmployeesForVerification)) {
                    unset($data_obj[$key]);
                }
            }
        }
        //
        $data2 = $this->db
            ->select("user_type, user_sid")
            ->join('documents_assigned', 'authorized_document_assigned_manager.document_assigned_sid = documents_assigned.sid', 'inner')
            ->where('authorized_document_assigned_manager.assigned_to_sid', $employer_id)
            ->where('authorized_document_assigned_manager.company_sid', $company_id)
            ->where('documents_assigned.archive', 0)
            ->where('documents_assigned.status', 1)
            ->where('documents_assigned.document_description like "%{{authorized_editable_date}}%"', null, false)
            ->where('documents_assigned.authorized_editable_date', null)
            ->get('authorized_document_assigned_manager');
        //
        $data_obj2 = $data2->result_array();
        //
        foreach ($data_obj2 as $key => $v) {
            if ($v["user_type"] == "applicant") {
                if (in_array($v["user_sid"], $companyApplicantsForVerification)) {
                    unset($data_obj[$key]);
                }
            }

            if ($v["user_type"] == "employee") {
                if (in_array($v["user_sid"], $companyEmployeesForVerification)) {
                    unset($data_obj[$key]);
                }
            }
        }
        //
        return count($data_obj) + count($data_obj2);
    }

    function get_all_pending_auth_documents_count($company_id, $employer_id, $companyEmployeesForVerification = FALSE, $companyApplicantsForVerification = FALSE)
    {
        if (!$companyEmployeesForVerification) {
            $companyEmployeesForVerification = $this->getAllCompanyInactiveEmployee($company_id);
        }
        //
        if (!$companyApplicantsForVerification) {
            $companyApplicantsForVerification = $this->getAllCompanyInactiveApplicant($company_id);
        }
        //
        $this->db
            ->join('documents_assigned', 'authorized_document_assigned_manager.document_assigned_sid = documents_assigned.sid', 'inner')
            ->where('authorized_document_assigned_manager.assigned_to_sid', $employer_id)
            ->where('authorized_document_assigned_manager.company_sid', $company_id)
            ->where('documents_assigned.archive', 0)
            ->where('documents_assigned.status', 1);
        //
        // if(!empty($inactive_employee_sid)){
        //     $this->db->group_start()
        //     ->where_not_in('documents_assigned.user_sid', $inactive_employee_sid)
        //     ->where('documents_assigned.user_type', 'employee')
        //     ->group_end();
        // }
        // //
        // if(!empty($inactive_applicant_sid)){
        //     $this->db->group_start()
        //     ->where_not_in('documents_assigned.user_sid', $inactive_applicant_sid)
        //     ->where('documents_assigned.user_type', 'applicant')
        //     ->group_end();
        // }
        //
        $data = $this->db
            ->select("user_type, user_sid")
            ->join('documents_assigned', 'authorized_document_assigned_manager.document_assigned_sid = documents_assigned.sid', 'inner')
            ->where('authorized_document_assigned_manager.assigned_to_sid', $employer_id)
            ->where('authorized_document_assigned_manager.company_sid', $company_id)
            ->where('documents_assigned.archive', 0)
            ->where('documents_assigned.status', 1)
            ->group_start()
            ->where('documents_assigned.document_description like "%{{authorized_signature}}%"', null, false)
            ->or_where('documents_assigned.document_description like "%{{authorized_signature_date}}%"', null, false)
            ->group_end()
            ->group_start()
            ->where('documents_assigned.authorized_signature IS NULL', null)
            ->or_where('documents_assigned.authorized_signature = ""', null)
            ->group_end()
            ->get('authorized_document_assigned_manager');
        //    
        $data_obj = $data->result_array();
        // ->count_all_results('authorized_document_assigned_manager');
        //
        foreach ($data_obj as $key => $v) {
            if ($v["user_type"] == "applicant") {
                if (in_array($v["user_sid"], $companyApplicantsForVerification)) {
                    unset($data_obj[$key]);
                }
            }

            if ($v["user_type"] == "employee") {
                if (in_array($v["user_sid"], $companyEmployeesForVerification)) {
                    unset($data_obj[$key]);
                }
            }
        }
        //
        $data2 = $this->db
            ->select("user_type, user_sid")
            ->join('documents_assigned', 'authorized_document_assigned_manager.document_assigned_sid = documents_assigned.sid', 'inner')
            ->where('authorized_document_assigned_manager.assigned_to_sid', $employer_id)
            ->where('authorized_document_assigned_manager.company_sid', $company_id)
            ->where('documents_assigned.archive', 0)
            ->where('documents_assigned.status', 1)
            ->where('documents_assigned.document_description like "%{{authorized_editable_date}}%"', null, false)
            ->where('documents_assigned.authorized_editable_date', null)
            ->get('authorized_document_assigned_manager');
        //
        $data_obj2 = $data2->result_array();
        //
        foreach ($data_obj2 as $key => $v) {
            if ($v["user_type"] == "applicant") {
                if (in_array($v["user_sid"], $companyApplicantsForVerification)) {
                    unset($data_obj[$key]);
                }
            }

            if ($v["user_type"] == "employee") {
                if (in_array($v["user_sid"], $companyEmployeesForVerification)) {
                    unset($data_obj[$key]);
                }
            }
        }
        //
        return count($data_obj) + count($data_obj2);
    }

    function compnayEventCount($id, $today = false)
    {
        $this->db->where('companys_sid', $id);

        if ($today == true) {
            $this->db->where("created_on >= ", date('Y-m-d 00:00:00'));
            $this->db->where("created_on <= ", date('Y-m-d 23:59:59'));
        }

        $this->db->from('portal_schedule_event');
        return $this->db->count_all_results();
    }

    function companyQuestionnairCount($id)
    {
        $this->db->where('employer_sid', $id);
        $this->db->from('portal_screening_questionnaires');
        return $this->db->count_all_results();
    }

    function get_employees_detail($parent_sid, $sid)
    {
        $this->db->select('*');
        $this->db->where('parent_sid', $parent_sid);
        $this->db->where('sid != ' . $sid);
        $this->db->order_by("sid", "desc");
        return $this->db->get('users')->result_array();
    }

    function save_event($data)
    {
        return $this->db->insert('portal_schedule_event', $data);
    }

    function update_event($sid, $data)
    {
        $this->db->where('sid', $sid);
        return $this->db->update('portal_schedule_event', $data);
    }

    function get_emergency_contacts($type, $users_sid)
    {
        $this->db->where('users_type', $type);
        $this->db->where('users_sid', $users_sid);
        $this->db->order_by('priority', "asc");
        $result = $this->db->get('emergency_contacts')->result_array();
        return $result;
    }

    function get_equipment_info($type, $users_sid)
    {
        $this->db->where('users_type', $type);
        $this->db->where('users_sid', $users_sid);
        $this->db->where('delete_flag', 0);
        return $this->db->get('equipment_information')->result_array();
    }

    function get_serialized_equipment_info()
    {
        return $this->db->get('equipment_information')->result_array();
    }

    function delete_equipment($sid)
    {
        $this->db->where('sid', $sid);
        $this->db->delete('equipment_information');
    }

    function emergency_contacts_details($sid)
    {
        $this->db->where('sid', $sid);
        $result = $this->db->get('emergency_contacts')->result_array();
        return $result;
    }

    function checkJobId($company_id, $jobId)
    {
        $this->db->where('user_sid', $company_id);
        $this->db->where('sid', $jobId);
        $result = $this->db->get('portal_job_listings');
        return $result->num_rows();
    }

    function getPurchasedProducts($companyId, $product_type = NULL)
    { //Getting products ID against Product type from product table STARTS
        if ($product_type == NULL) {
            $products = $this->db->get('products')->result_array();
        } else {
            $products = $this->db->get_where('products', array('product_type' => $product_type))->result_array();
        }

        foreach ($products as $key => $product) {
            $productIds[$key] = $product['sid'];
        } //Getting products ID against Product type from product table ENDS

        if ($product_type == NULL) {
            $productArray = $this->companyPurchasedProducts($productIds, $companyId);
        } else {
            $productArray = $this->companyPurchasedProducts($productIds, $companyId, $product_type);
        }

        return $productArray;
    }

    function notPurchasedProducts($product_ids = NULL, $product_type)
    {
        $this->db->where_not_in('sid', $product_ids);
        $this->db->where('active', 1);
        $this->db->where('product_type', $product_type);
        return $this->db->get('products')->result_array();
    }

    function productDetail($product_id)
    {
        $this->db->where('sid', $product_id);
        return $this->db->get('products')->result_array();
    }

    function insertJobFeed($jobData)
    {
        $this->db->insert('jobs_to_feed', $jobData);
    }

    //This function is wrong
    //(it will reduce quantity from the first product found regardless of for which company it is ) comment by Hamid
    function deduct_purchased_product_qty($productId)
    {
        $this->db->where('product_sid', $productId);
        $this->db->where('product_remaining_qty > 0');
        $this->db->limit(1);
        $this->db->set('product_remaining_qty', '`product_remaining_qty`- 1', FALSE);
        $this->db->update('order_product');
    }

    function deduct_company_purchase_product_qty($company_sid, $product_sid, $quantity_used = 1)
    {
        $this->db->select('sid, product_remaining_qty');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('product_sid', $product_sid);
        $this->db->where('product_remaining_qty > 0');
        $this->db->order_by('sid', 'ASC');
        $this->db->limit(1);
        $product = $this->db->get('order_product')->result_array();
        echo $this->db->last_query() . '<pre>';
        print_r($product);
        echo '<pre><hr>';
        if (!empty($product)) {
            $product = $product[0];
            $selected_product_sid = $product['sid'];
            $new_quantity = intval($product['product_remaining_qty']) - intval($quantity_used);
            $data_to_update = array();
            $data_to_update['product_remaining_qty'] = $new_quantity;
            $this->db->where('sid', $selected_product_sid);
            $this->db->update('order_product', $data_to_update);
        }
    }

    function active_products($jobId)
    {
        $this->db->select('product_sid');
        $this->db->where('job_sid', $jobId);
        $this->db->distinct('product_sid');
        $this->db->where('expiry_date > "' . date('Y-m-d H:i:s') . '"');
        return $this->db->get('jobs_to_feed')->result_array();
    }

    function saveOrderData($orderData)
    {
        $this->db->insert('orders', $orderData);
        return $this->db->insert_id();
    }

    function saveOrderProductData($orderProductData)
    {
        $this->db->insert('order_product', $orderProductData);
    }

    function getJobDetailWithPortalDetail($jobId)
    {
        $this->db->select('*,portal_job_listings.sid as job_sid');
        $this->db->where('portal_job_listings.sid', $jobId);
        $this->db->join('portal_employer', 'portal_job_listings.user_sid = portal_employer.user_sid');
        return $this->db->get('portal_job_listings')->result_array();
    }

    function getJobDetail($jobId)
    {
        $this->db->where('sid', $jobId);
        return $this->db->get('portal_job_listings')->result_array();
    }

    function getProductBrand($product_sid)
    {
        $this->db->select('product_brand');
        $this->db->where('sid', $product_sid);
        $result = $this->db->get('products')->result_array();
        return $result[0]['product_brand'];
    }

    function getProductsOfSameBrand($productBrand)
    {
        $this->db->select('sid');
        $this->db->where('product_brand', $productBrand);
        return $this->db->get('products')->result_array();
    }

    function isUniqueJobTitle($jobTitle, $companyId)
    {
        return $this->db->where('user_sid', $companyId)
            ->where('Title', $jobTitle)
            ->get('portal_job_listings')->num_rows();
    }

    function save_equipment_info($data)
    {
        $this->db->insert('equipment_information', $data);
    }

    function check_user_equipment($employer_id, $type)
    {
        return $this->db->where('users_sid', $employer_id)
            ->where('users_type', $type)
            ->get('equipment_information');
    }

    function update_equipment_info($equipment_id, $equipmentData)
    {
        $this->db->where('sid', $equipment_id)
            ->update('equipment_information', $equipmentData);
    }

    function get_license_info($employer_id, $employer_type, $license_type)
    {
        return $this->db->where('users_sid', $employer_id)
            ->where('users_type', $employer_type)
            ->where('license_type', $license_type)
            ->get('license_information')->result_array();
    }

    function check_user_license($employer_id, $employer_type, $license_type)
    {
        return $this->db->where('users_sid', $employer_id)
            ->where('users_type', $employer_type)
            ->where('license_type', $license_type)
            ->get('license_information');
    }

    function save_license_info($data, $dateOfBirth = array(), $employeeId = null)
    {
        $this->db->insert('license_information', $data);
        if (!empty($dateOfBirth) && $employeeId != null && ($data['users_type'] == 'employee' || $data['users_type'] == 'Employee')) {
            $this->db->where('sid', $employeeId)
                ->update('users', $dateOfBirth);
        } else if (!empty($dateOfBirth) && $data['users_sid'] != null && ($data['users_type'] == 'applicant' || $data['users_type'] == 'Applicant')) {
            $this->db->where('sid', $data['users_sid'])
                ->update('portal_job_applications', $dateOfBirth);
        }
    }

    function update_license_info($license_id, $licenseData, $dateOfBirth = array(), $employeeId = null)
    {
        $this->db->where('sid', $license_id)
            ->update('license_information', $licenseData);
        if (!empty($dateOfBirth) && $employeeId != null && ($licenseData['users_type'] == 'employee' || $licenseData['users_type'] == 'Employee')) {
            $this->db->where('sid', $employeeId)
                ->update('users', $dateOfBirth);
        } else if (!empty($dateOfBirth) && $licenseData['users_sid'] != null && ($licenseData['users_type'] == 'applicant' || $licenseData['users_type'] == 'Applicant')) {
            $this->db->where('sid', $licenseData['users_sid'])
                ->update('portal_job_applications', $dateOfBirth);
        }
    }

    function checkPurchasedProductCounter($productId)
    {
        $result = $this->db->select('sum(product_remaining_qty) as remaining_qty')
            ->where('product_sid', $productId)
            ->get('order_product')
            ->result_array();
        if ($result[0]['remaining_qty'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    function get_dependant_info($type, $users_sid)
    {
        $this->db->select('*');
        $this->db->where('users_type', $type);
        $this->db->where('users_sid', $users_sid);
        return $this->db->get('dependant_information')->result_array();
    }

    function save_dependant_info($data)
    {
        $this->db->insert('dependant_information', $data);
    }

    function check_user_dependant($employer_id, $type)
    {
        return $this->db->where('users_sid', $employer_id)
            ->where('users_type', $type)
            ->get('dependant_information');
    }

    function update_dependant_info($dependant_id, $dependantData)
    {
        $this->db->where('sid', $dependant_id)
            ->update('dependant_information', $dependantData);
    }

    function dependant_details($sid)
    {
        $this->db->where('sid', $sid);
        $result = $this->db->get('dependant_information')->result_array();
        return $result;
    }

    function checkPurchasedProductQty($productIds, $companyId, $product_type)
    {
        return $this->companyPurchasedProducts($productIds, $companyId, $product_type);
    }

    function companyPurchasedProducts_old($productIds, $companyId, $product_type = NULL)
    {
        $i = 0;
        $productArray = array();
        $productIDsInArray = array();

        $this->db->select('*');
        $this->db->where('company_sid', $companyId);
        $this->db->where('status', 'Paid');
        $record_obj = $this->db->get('invoices'); //Getting all invoices against the company which are paid
        $orders = $record_obj->result_array();
        $record_obj->free_result();

        foreach ($orders as $order) {
            $dataArray = unserialize($order['serialized_items_info']);
            foreach ($dataArray['products'] as $key => $product) {
                if (in_array($product, $productIds)) {
                    if (in_array($product, $productIDsInArray)) { //if the product is already added in the array.
                        foreach ($productArray as $myKey => $pro) {
                            if ($pro['product_sid'] == $product && $pro['no_of_days'] == $dataArray['no_of_days'][$key]) {
                                $pro['remaining_qty'] = $pro['remaining_qty'] + $dataArray['item_remaining_qty'][$key];
                            } else if (!in_array($product, $productIDsInArray)) {
                                $productArray[$i]['product_sid'] = $product;
                                $productArray[$i]['remaining_qty'] = $dataArray['item_remaining_qty'][$key];
                                $productArray[$i]['no_of_days'] = $dataArray['no_of_days'][$key];
                            }

                            $productArray[$myKey] = $pro;
                        }
                    } else { //if the product is not already added in the array.
                        if ($dataArray['item_remaining_qty'][$key] > 0) {
                            $productIDsInArray[$i] = $product;
                            $productArray[$i]['product_sid'] = $product;
                            $productArray[$i]['remaining_qty'] = $dataArray['item_remaining_qty'][$key];

                            if (isset($dataArray['no_of_days']))
                                $productArray[$i]['no_of_days'] = $dataArray['no_of_days'][$key];
                            $i++;
                        }
                    }
                }
            }
        }

        if ($product_type == NULL) {
            $products = $this->db->get('products')->result_array();
        } else {
            $products = $this->db->get_where('products', array('product_type' => $product_type))->result_array();
        }

        foreach ($productArray as $key => $pro) {
            foreach ($products as $myKey => $product) {
                if ($pro['product_sid'] == $product['sid']) {
                    $pro['product_image'] = $product['product_image'];
                    $pro['name'] = $product['name'];
                    $pro['product_brand'] = $product['product_brand'];
                    $productArray[$key] = $pro;
                }
            }
        }

        return $productArray;
    }

    function companyPurchasedProducts($productIds, $companyId, $product_type = NULL)
    {
        //
        $productArray = array();
        $productIDsInArray = array();
        //
        $this->db->select('*');
        $this->db->where('company_sid', $companyId);
        $this->db->where('status', 'Paid');
        $record_obj = $this->db->get('invoices'); //Getting all invoices against the company which are paid
        $orders = $record_obj->result_array();
        $record_obj->free_result();
        //
        foreach ($orders as $order) {
            $dataArray = unserialize($order['serialized_items_info']);
            //
            foreach ($dataArray['products'] as $key => $product) {
                if (in_array($product, $productIds)) {
                    if (in_array($product, $productIDsInArray)) {
                        //
                        $productArray[$product]['remaining_qty'] = $productArray[$product]['remaining_qty'] + $dataArray['item_remaining_qty'][$key];
                    } else {
                        //
                        array_push($productIDsInArray, $product);
                        $productArray[$product]['product_sid'] = $product;
                        $productArray[$product]['remaining_qty'] = $dataArray['item_remaining_qty'][$key];
                        //
                        if (isset($dataArray['no_of_days']))
                            $productArray[$product]['no_of_days'] = $dataArray['no_of_days'][$key];
                    }
                }
            }
        }
        //
        if ($product_type == NULL) {
            $products = $this->db->get('products')->result_array();
        } else
            $products = $this->db->get_where('products', array('product_type' => $product_type))->result_array();
        //
        foreach ($productArray as $key => $pro) {
            foreach ($products as $myKey => $product) {
                if ($pro['product_sid'] == $product['sid']) {
                    $pro['product_image'] = $product['product_image'];
                    $pro['name'] = $product['name'];
                    $pro['product_brand'] = $product['product_brand'];
                    $productArray[$key] = $pro;
                }
            }
        }
        //
        return $productArray;
    }

    function deduct_product_qty($productId, $companyId, $no_of_days = 0)
    { //Getting all invoices against the company which are paid STARTS
        $orders = $this->db->get_where('invoices', array('company_sid' => $companyId, 'status' => 'Paid'))->result_array();
        //Getting all invoices against the company which are paid ENDS
        foreach ($orders as $order) {
            $dataArray = unserialize($order['serialized_items_info']);

            foreach ($dataArray['products'] as $key => $product) {
                if ($no_of_days > 0) {
                    if ($product == $productId && $dataArray['no_of_days'][$key] == $no_of_days) { //I dont know why this no of days check is implemented so i have moved it one level above if the value is greater than zero then it will be applied. Hamid
                        $currentCounter = $dataArray['item_remaining_qty'][$key];

                        if ($currentCounter > 0) { //check if  the current remain qty is greater than 1 or not?
                            $currentCounter--;
                            $dataArray['item_remaining_qty'][$key] = $currentCounter;
                            $dataToUpdate['serialized_items_info'] = serialize($dataArray);
                            $this->db->where('sid', $order['sid'])->update('invoices', $dataToUpdate);
                            return;
                        }
                    }
                } else { // Added by Hamid ( No of days check was failing hence qty was not deducted ).
                    if ($product == $productId) {
                        $currentCounter = $dataArray['item_remaining_qty'][$key];

                        if ($currentCounter > 0) { //check if  the current remain qty is greater than 1 or not?
                            $currentCounter--;
                            $dataArray['item_remaining_qty'][$key] = $currentCounter;
                            $dataToUpdate['serialized_items_info'] = serialize($dataArray);
                            $this->db->where('sid', $order['sid'])->update('invoices', $dataToUpdate);
                            return;
                        }
                    }
                }
            }
        }
    }

    function get_product_budget($productId, $companyId, $no_of_days = 0)
    {
        //Getting all invoices against the company which are paid STARTS
        $orders = $this->db->get_where('invoices', array('company_sid' => $companyId, 'status' => 'Paid'))->result_array();
        //Getting all invoices against the company which are paid ENDS
        foreach ($orders as $order) {
            $dataArray = unserialize($order['serialized_items_info']);

            foreach ($dataArray['products'] as $key => $product) {
                if ($product == $productId && $dataArray['no_of_days'][$key] == $no_of_days) {
                    $currentCounter = $dataArray['item_remaining_qty'][$key];

                    if ($currentCounter > 0) { //check if  the current remain qty is greater than 1 or not?
                        return $dataArray['item_price'][$key];
                    }
                }
            }
        }
    }

    function save_kpa_onboarding($data)
    {
        $count = $this->db->get_where('kpa_onboarding', array('company_sid' => $data['company_sid']))->num_rows();

        if ($count > 0) {
            $this->db->where('company_sid', $data['company_sid']);
            $this->db->update('kpa_onboarding', $data);
            $this->session->set_flashdata('message', '<b>Success:</b> Outsourced HR Compliance and Onboarding details updated successfully');
        } else {
            $this->db->insert('kpa_onboarding', $data);
            $this->session->set_flashdata('message', '<b>Success:</b> Outsourced HR Compliance and Onboarding details saved successfully');
        }
    }

    function get_kpa_onboarding($company_sid)
    {
        return $this->db->get_where('kpa_onboarding', array('company_sid' => $company_sid))->row_array();
    }

    public function GetJobListingTemplateGroupDetail($group_sid)
    {
        $this->db->where('sid', $group_sid);
        return $this->db->get('portal_job_listing_template_groups')->result_array();
    }

    public function GetJobListingTemplateDetail($template_sid)
    {
        $this->db->where_in('sid', $template_sid);
        $this->db->where('status', 1);
        $this->db->order_by('sort_order', 'ASC');
        return $this->db->get('portal_job_listing_templates')->result_array();
    }

    public function GetAllActiveJobListingTemplates()
    {
        $this->db->where('status', 1);
        $this->db->where('archive_status', 'active');
        $this->db->order_by('sort_order', 'ASC');
        return $this->db->get('portal_job_listing_templates')->result_array();
    }

    public function update_access_level($access_level, $employer_id)
    {
        $data = array('access_level' => $access_level);
        $this->db->where('sid', $employer_id);
        $this->db->update('users', $data);
    }

    public function GetAllActiveUsers($parent_sid)
    {
        $this->db->where('parent_sid', $parent_sid);
        $this->db->where('is_executive_admin', 0);
        $this->db->where('active', 1);
        $this->db->order_by('first_name', 'ASC');

        return $this->db->get('users')->result_array();
    }

    public function GetSingleActiveUser($parent_sid, $user_sid)
    {
        $this->db->where('parent_sid', $parent_sid);
        $this->db->where('sid', $user_sid);
        $this->db->where('active', 1);
        $this->db->where('terminated_status', 0);
        return $this->db->get('users')->result_array();
    }

    public function GetAllUsers($company_sid)
    {
        $this->db->where('parent_sid', $company_sid);
        $this->db->where('username !=', '');
        //$this->db->where('password !=', '');
        $this->db->where('active', 1);
        $this->db->where('terminated_status', 0);
        //$this->db->where('is_executive_admin', 0);
        $this->db->order_by(SORT_COLUMN, SORT_ORDER);
        $result = $this->db->get('users')->result_array();

        if (!empty($result)) {
            return $result;
        } else {
            return array();
        }
    }

    public function get_special_chars_status($company_sid)
    {
        $this->db->select('job_title_special_chars');
        $this->db->where('user_sid', $company_sid);
        $portal_info = $this->db->get('portal_employer')->result_array();
        $special_chars = 0;

        if (!empty($portal_info)) {
            $special_chars = $portal_info[0]['job_title_special_chars'];
        } else {
            $special_chars = 0;
        }

        return $special_chars;
    }

    public function get_interview_questionnaires($company_sid)
    {
        $this->db->select('sid, title');
        $this->db->where('company_sid', 0);
        $this->db->where('status <>', 'deleted');
        $default_questionnaires = $this->db->get('interview_questionnaires')->result_array();
        $this->db->select('sid, title');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('status <>', 'deleted');
        $custom_questionnaires = $this->db->get('interview_questionnaires')->result_array();
        $return_array = array();
        $return_array['default'] = $default_questionnaires;
        $return_array['custom'] = $custom_questionnaires;
        return $return_array;
    }

    public function get_security_access_levels()
    {
        $this->db->select('access_level');
        $this->db->where('status', 1);
        $access_levels = $this->db->get('security_access_level')->result_array();
        $my_return = array();

        foreach ($access_levels as $access_level) {
            $my_return[] = $access_level['access_level'];
        }

        return $my_return;
    }

    public function get_last_attendance_record($company_sid, $employer_sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('employer_sid', $employer_sid);
        $this->db->limit(1);
        $this->db->order_by('sid', 'DESC');
        return $this->db->get('attendance')->result_array();
    }

    public function cancel_event($event_sid)
    {
        $this->db->where('sid', $event_sid);
        $this->db->set('event_status', 'cancelled');
        $this->db->update('portal_schedule_event');
    }

    public function get_event_participants($event_sid)
    {
        $this->db->select('applicant_job_sid, interviewer');
        $this->db->where('sid', $event_sid);
        $event_obj = $this->db->get('portal_schedule_event');
        $event_arr = $event_obj->result_array();
        $event_obj->free_result();

        if (!empty($event_arr)) {
            $event_arr = $event_arr[0];
            $applicant = $event_arr['applicant_job_sid'];

            $this->db->select('sid');
            $this->db->select('first_name');
            $this->db->select('last_name');
            $this->db->select('email');
            $this->db->where('sid', $applicant);

            $applicant_obj = $this->db->get('portal_job_applications');
            $applicant_arr = $applicant_obj->result_array();
            $applicant_obj->free_result();
            $interviewers = $event_arr['interviewer'];
            $interviewers = explode(',', $interviewers);

            $this->db->select('sid');
            $this->db->select('first_name');
            $this->db->select('last_name');
            $this->db->select('email');
            $this->db->where_in('sid', $interviewers);
            $this->db->where('terminated_status', 0);
            $this->db->order_by(SORT_COLUMN, SORT_ORDER);
            $users_obj = $this->db->get('users');
            $users_arr = $users_obj->result_array();
            $users_obj->free_result();
            $partitipants = array_merge($applicant_arr, $users_arr);

            return $partitipants;
        } else {
            return array();
        }
    }

    public function get_event_details($event_sid)
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

    public function delete_dependant($users_type, $users_sid, $dependant_sid, $company_sid)
    {
        $this->db->where('users_type', $users_type);
        $this->db->where('users_sid', $users_sid);
        $this->db->where('company_sid', $company_sid);
        $this->db->where('sid', $dependant_sid);
        $this->db->delete('dependant_information');
    }

    public function delete_emergency_contact($users_type, $users_sid, $contact_sid)
    {
        $this->db->where('users_type', $users_type);
        $this->db->where('users_sid', $users_sid);
        $this->db->where('sid', $contact_sid);
        $this->db->delete('emergency_contacts');
    }

    public function get_all_employees($company_sid)
    {
        $this->db->select('sid');
        $this->db->select('first_name');
        $this->db->select('last_name');
        $this->db->select('PhoneNumber');
        $this->db->select('YouTubeVideo');
        $this->db->select('profile_picture');
        $this->db->select('email');
        $this->db->select('cell_number');
        $this->db->where('parent_sid', $company_sid);
        $this->db->order_by(SORT_COLUMN, SORT_ORDER);
        $records_obj = $this->db->get('users');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        return $records_arr;
    }

    public function get_employee_info($company_sid, $employee_sid)
    {
        $this->db->select('sid');
        $this->db->select('access_level');
        $this->db->select('first_name');
        $this->db->select('last_name');
        $this->db->select('PhoneNumber');
        $this->db->select('YouTubeVideo');
        $this->db->select('profile_picture');
        $this->db->select('email');
        $this->db->select('cell_number');
        $this->db->select('linkedin_profile_url');
        $this->db->select('extra_info');
        $this->db->select('job_title');
        $this->db->where('parent_sid', $company_sid);
        $this->db->where('sid', $employee_sid);
        $this->db->order_by(SORT_COLUMN, SORT_ORDER);
        $records_obj = $this->db->get('users');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }

    function get_all_job_logos($user_sid)
    {
        $this->db->select('pictures ');
        $this->db->where('user_sid', $user_sid);
        $this->db->where('pictures !=', '');
        $this->db->group_by('pictures');
        $records_obj = $this->db->get('portal_job_listings');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        $all_job_logos = $records_arr;
        $return_array = array();

        if (!empty($all_job_logos)) {
            foreach ($all_job_logos as $job_logo) {
                $my_logo = $job_logo['pictures'];
                $exploded_logo = explode('.', $my_logo);
                //                echo '<br> Image Name: '.$exploded_logo[0].'.'.$exploded_logo[1];
                //                echo '<br><br>Actual Image Name: '.substr($exploded_logo[0],0,-6).'.'.$exploded_logo[1];
                $actual_name = substr($exploded_logo[0], 0, -6) . '.' . $exploded_logo[1];
                $return_array[$actual_name] = array('pictures' => $my_logo);
            }
        }

        return $return_array;
    }

    function mark_product_as_used($product_sid, $company_sid, $employer_sid, $job_sid)
    {
        //Use FIFO for Product Usage.
        $this->db->where('company_sid', $company_sid);
        $this->db->where('product_sid', $product_sid);
        $this->db->where('quantity_used', 0);
        $this->db->order_by('date_purchased', 'ASC');
        $this->db->limit(1);
        $products_obj = $this->db->get('invoice_items_track');
        $products_arr = $products_obj->result_array();
        $products_obj->free_result();

        if (!empty($products_arr)) {
            $invoice_items_track_sid = $products_arr[0]['sid'];
            $this->db->where('sid', $invoice_items_track_sid);
            $this->db->set('quantity_used', 1);
            $this->db->set('used_against_job_sid', $job_sid);
            $this->db->set('used_date', date('Y-m-d H:i:s'));
            $this->db->set('used_by_employer_sid', $employer_sid);
            $this->db->update('invoice_items_track');
        }
    }

    function get_feed_activation_requests($company_sid, $job_sid)
    {
        $this->db->select('jobs_to_feed.*');
        $this->db->select('products.product_type');
        $this->db->select('products.product_brand');
        $this->db->where('jobs_to_feed.refund_status', 0);
        $this->db->where('jobs_to_feed.job_sid', $job_sid);
        $this->db->where('jobs_to_feed.company_sid', $company_sid);
        $this->db->join('products', 'jobs_to_feed.product_sid = products.sid', 'left');

        $records_obj = $this->db->get('jobs_to_feed');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        return $records_arr;
    }

    function check_authenticity($company_sid, $con_id, $flag = NULL)
    {
        $this->db->select('users.parent_sid');
        if ($flag == 'dependants') {
            $this->db->where('dependant_information.sid', $con_id);
            $this->db->join('users', 'users.sid = dependant_information.users_sid', 'left');
            $parent_sid = $this->db->get('dependant_information')->result_array();
        } else {
            $this->db->where('emergency_contacts.sid', $con_id);
            $this->db->join('users', 'users.sid = emergency_contacts.users_sid', 'left');
            $parent_sid = $this->db->get('emergency_contacts')->result_array();
        }
        if ($company_sid != $parent_sid[0]['parent_sid']) {
            return true;
        } else {
            return false;
        }
    }

    function insert_share_record($data)
    {
        $this->db->insert('coworker_referrals', $data);
        return $this->db->insert_id();
    }

    function insert_jobs_records($insert_data)
    {
        $this->db->insert('portal_job_listings_record', $insert_data);
        return $this->db->insert_id();
    }

    function update_jobs_records($jid, $update_record)
    {
        if (is_array($jid)) {
            $this->db->where_in('portal_job_listings_sid', $jid);
        } else {
            $this->db->where('portal_job_listings_sid', $jid);
        }
        $this->db->update('portal_job_listings_record', $update_record);
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

    function get_applicant_jobs($applicant_sid)
    {
        $this->db->select('portal_applicant_jobs_list.sid');
        $this->db->select('portal_applicant_jobs_list.date_applied');
        $this->db->select('portal_applicant_jobs_list.job_sid');
        $this->db->select('portal_job_listings.Title as job_title');
        $this->db->where('portal_applicant_jobs_list.portal_job_applications_sid', $applicant_sid);
        $this->db->join('portal_job_listings', 'portal_job_listings.sid = portal_applicant_jobs_list.portal_job_applications_sid', 'left');
        $this->db->from('portal_applicant_jobs_list');

        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        return $records_arr;
    }

    function get_user_information($company_sid, $user_sids = array())
    {
        $this->db->select('first_name');
        $this->db->select('last_name');
        $this->db->select('email');

        $this->db->where('parent_sid', $company_sid);
        $this->db->where_in('sid', $user_sids);
        $this->db->order_by(SORT_COLUMN, SORT_ORDER);
        $record_obj = $this->db->get('users');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        return $record_arr;
    }

    public function GetUsersWithApprovalRights($company_sid, $module = 'jobs')
    {
        $this->db->select('sid');
        $this->db->where('parent_sid', $company_sid);
        $this->db->where('active', 1);

        if ($module == 'jobs') {
            $this->db->where('has_job_approval_rights', 1);
        } elseif ($module == 'applicants') {
            $this->db->where('has_applicant_approval_rights', 1);
        }

        $record_obj = $this->db->get('users');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        return $record_arr;
    }

    function get_career_website($sid)
    {
        $this->db->select('sub_domain, domain_type');
        $this->db->where('user_sid', $sid);
        $record_obj = $this->db->get('portal_employer');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        return $record_arr[0];
    }

    function get_active_theme_name($sid)
    {
        $this->db->select('theme_name');
        $this->db->where('user_sid', $sid);
        $this->db->where('theme_status', 1);
        $record_obj = $this->db->get('portal_themes');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        $theme_name = '';

        if (!empty($record_arr)) {
            $theme_name = $record_arr[0]['theme_name'];
        }

        return $theme_name;
    }

    function get_pay_per_job_status($sid)
    {
        $this->db->select('per_job_listing_charge, career_site_listings_only');
        $this->db->where('sid', $sid);
        $record_obj = $this->db->get('users');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $theme_name = $record_arr[0];
    }

    function check_safety_data($company_sid)
    {
        $this->db->where('company_sid', $company_sid);
        $this->db->where('status', 1);
        $this->db->from('safety_sheet_category');
        $result =  $this->db->count_all_results();
        return $result;
    }


    /**
     * Fetch Jobs by employer
     *
     * @param $company_sid Integer
     * @param $employer_sid Integer
     * @param $approval_status String
     *
     * @return Integer
     *
     */
    function getJobsForEmployee($company_sid, $employer_sid, $approval_status)
    {
        // Updated on: 22-04-2019
        return
            $this->db
            ->select('portal_job_listings.*')
            ->group_start()
            ->where('portal_job_listings.active', 1)
            ->or_where('portal_job_listings.active', 0)
            ->group_end()
            ->where('portal_job_listings_visibility.company_sid', $company_sid)
            ->where('portal_job_listings_visibility.employer_sid', $employer_sid)
            ->where('portal_job_listings.approval_status', $approval_status)
            ->order_by('portal_job_listings.sid', 'DESC')
            ->from('portal_job_listings_visibility')
            ->join('portal_job_listings', 'portal_job_listings.sid = portal_job_listings_visibility.job_sid', 'inner')
            ->count_all_results();
    }

    /**
     * Check if employee has approval access
     *
     * @param $company_sid Integer
     * @param $employee_sid Integer
     *
     * @return Integer
     */

    function check_employee_has_approval_rights($company_sid, $employee_sid)
    {
        $result = $this->db
            ->select('has_job_approval_rights')
            ->from('users')
            ->where('parent_sid', $company_sid)
            ->where('sid', $employee_sid)
            ->get();
        $has_rights = $result->row_array()['has_job_approval_rights'];
        $result = $result->free_result();
        return $has_rights;
    }

    function get_all_events_count($company_sid, $employer_id)
    {
        $between = "display_start_date <= '" . (date('Y-m-d H:i:s')) . "' AND (display_end_date >= '" . (date('Y-m-d H:i:s')) . "' OR display_end_date IS NULL)";
        $this->db->where("company_sid", $company_sid);
        $this->db->where("status", "1");
        $this->db->where("is_pending", '1');
        $this->db->where($between);
        $this->db->group_start();
        $this->db->where('FIND_IN_SET(' . $employer_id . ',announcement_for) <> ', 0);
        $this->db->or_where('announcement_for', 0);
        $this->db->group_end();
        $this->db->from('employee_announcement');
        return $this->db->count_all_results();
    }

    function get_all_messages_count($employer_sid)
    {
        $this->db->where("to_id", $employer_sid);
        $this->db->where("status", 0);
        $this->db->where("outbox", 0);
        $this->db->where("users_type", 'employee');
        $this->db->from('private_message');
        return $this->db->count_all_results();
    }

    function get_all_incidents_count($company_sid, $employer_sid)
    {
        $this->db->where('incident_type_configuration.employer_id', $employer_sid);
        $this->db->where('incident_reporting.employer_sid <> ', $employer_sid);
        $this->db->where('incident_reporting.company_sid', $company_sid);
        $this->db->where('incident_reporting.report_type', 'confidential');
        $this->db->where("incident_reporting.status", 'Pending');
        $this->db->join('incident_type_configuration', 'incident_type_configuration.incident_type_id = incident_reporting.incident_type_id', 'left');
        $this->db->from('incident_reporting');
        return $this->db->count_all_results();
    }

    function assigned_incidents_new_flow_count($id, $cid)
    {
        $this->db->where('incident_assigned_emp.employer_sid', $id);
        $this->db->where('incident_assigned_emp.company_sid', $cid);
        $this->db->where('incident_assigned_emp.assigned_status', 1);
        $this->db->where('incident_reporting.report_type', 'confidential');
        $this->db->where("incident_reporting.status", 'Pending');
        $this->db->join('incident_reporting', 'incident_reporting.id = incident_assigned_emp.incident_sid', 'left');
        $this->db->from('incident_assigned_emp');

        return $this->db->count_all_results();
    }

    function get_training_session_count($user_type, $user_sid, $company_id = false)
    {
        //
        $result = $this->db
            ->select('
            employees_assigned_to,
            session_status,
            sid as id
        ', false)
            ->from('learning_center_training_sessions')
            ->where('company_sid', $company_id)
            ->get();
        //
        $result_arr = $result->result_array();
        $result->free_result();
        $allCount = 0;
        //
        if (sizeof($result_arr)) {
            foreach ($result_arr as $k0 => $v0) {
                if ($v0['session_status'] != 'pending' && $v0['session_status'] != 'scheduled') continue;
                //
                if ($v0['employees_assigned_to'] == 'specific') {
                    // Check if it is assigned to login employee
                    if (
                        $this->db
                        ->where('training_session_sid', $v0['id'])
                        ->where('user_sid', $user_sid)
                        ->where('user_type', $user_type)
                        ->count_all_results('learning_center_training_sessions_assignments') == 0
                    ) {
                        continue;
                    }
                }
                $allCount++;
            }
        }
        return $allCount;
    }

    function get_my_all_online_videos_count($user_type, $user_sid, $company_sid, $fromProfile = false)
    {
        //
        $r = [];
        //
        if ($user_type == 'employee') {
            // Get all employees
            $this->db->select('sid, created_date, video_title, video_description, video_source, video_id, video_start_date, screening_questionnaire_sid')
                ->select('learning_center_online_videos.video_start_date')
                ->select('learning_center_online_videos.expired_start_date')
                ->where('company_sid', $company_sid)
                ->where('employees_assigned_to', 'all')
                ->order_by('created_date', 'DESC');
            //
            if (!$fromProfile) {
                $this->db
                    ->group_start()
                    ->where('learning_center_online_videos.video_start_date <= ', date('Y-m-d', strtotime('now')))
                    ->or_where('learning_center_online_videos.video_start_date IS NULL', NULL)
                    ->group_end()
                    ->group_start()
                    ->where('expired_start_date >= ', date('Y-m-d', strtotime('now')))
                    ->or_where('expired_start_date IS NULL', NULL)
                    ->group_end();
            }
            //
            $a = $this->db->get('learning_center_online_videos');
            $b = $a->result_array();
            $a->free_result();
            //
            $ids = array();
            //
            if (sizeof($b)) {
                foreach ($b as $k => $v) {
                    $ids[$v['sid']] = $v['sid'];
                }
            }
            //
            $r = $b;
        }
        // Get specific employees
        $this->db->select('learning_center_online_videos.sid')
            ->select('learning_center_online_videos.created_date')
            ->select('learning_center_online_videos.video_title')
            ->select('learning_center_online_videos.video_description')
            ->select('learning_center_online_videos.video_source')
            ->select('learning_center_online_videos.video_id')
            ->select('learning_center_online_videos.video_start_date')
            ->select('learning_center_online_videos.expired_start_date')
            ->select('learning_center_online_videos.screening_questionnaire_sid')
            ->select('learning_center_online_videos_assignments.learning_center_online_videos_sid')
            ->where('learning_center_online_videos_assignments.user_type', $user_type)
            ->where('learning_center_online_videos_assignments.user_sid', $user_sid)
            ->where('learning_center_online_videos_assignments.status', 1)
            ->order_by('learning_center_online_videos_assignments.date_assigned', 'DESC')
            ->join('learning_center_online_videos', 'learning_center_online_videos.sid = learning_center_online_videos_assignments.learning_center_online_videos_sid');
        //
        if (!$fromProfile) {
            $this->db
                ->group_start()
                ->where('learning_center_online_videos.video_start_date <= ', date('Y-m-d', strtotime('now')))
                ->or_where('learning_center_online_videos.video_start_date IS NULL', NULL)
                ->group_end()
                ->group_start()
                ->where('learning_center_online_videos.expired_start_date >= ', date('Y-m-d', strtotime('now')))
                ->or_where('learning_center_online_videos.expired_start_date IS NULL', NULL)
                ->group_end();
        }
        //
        $a = $this->db->get('learning_center_online_videos_assignments');
        $b = $a->result_array();
        $a->free_result();
        //
        if (sizeof($b)) {
            foreach ($b as $k => $v) {
                $ids[$v['sid']] = $v['sid'];
            }
        }
        //
        $r = array_merge($r, $b);
        //
        // if($user_type == 'employee'){
        //     //
        //     $ids = array_values($ids);

        //     // Check for departments
        //     $this->db
        //     ->select('sid, created_date, video_title, video_description, video_source, video_id, department_sids, video_start_date, screening_questionnaire_sid')
        //     ->where('company_sid', $company_sid)
        //     ->group_start()
        //     ->where('department_sids', 'all')
        //     ->or_where('department_sids <> ', 'all')
        //     ->group_end()
        //     ->where('department_sids IS NOT NULL', NULL)
        //     ->where('employees_assigned_to', 'specific')
        //     ->order_by('created_date', 'DESC');
        //     //
        //     if(sizeof($ids)) $this->db->where_not_in('sid', $ids);
        //     //
        //     $a = $this->db->get('learning_center_online_videos');
        //     $b = $a->result_array();
        //     $a->free_result();
        //     //
        //     if(!sizeof($b)) return $r;
        //     //
        //     $d = array();
        //     //
        //     $dept = $this->getDepartmentEmployees($company_sid, 'all', true);
        //     //
        //     foreach ($b as $k => $v) {
        //         if($v['department_sids'] == 'all'){
        //             if(!isset($dept[$v['department_sids']][$user_sid])) unset($b[$k]);
        //         } else {
        //             $t = explode(',', $v['department_sids']);
        //             foreach ($t as $k0 => $v0) {
        //                 if(!isset($dept[$v0][$user_sid])) unset($b[$k]);
        //             }
        //         }
        //     }
        //     //
        //     $r = array_merge($r, $b);
        // }
        //
        $current_date = date('Y-m-d');
        $video_count = 0;
        $screening_questionnaire_check = 1;
        //
        foreach ($r as $key => $single_r) {
            $video_start_date = date('Y-m-d', strtotime($single_r['video_start_date']));

            if ($video_start_date <= $current_date || empty($single_r['video_start_date'])) {

                $this->db->select('watched,sid');
                $this->db->where('learning_center_online_videos_sid', $single_r['sid']);
                $this->db->where('user_sid', $user_sid);
                $this->db->where('user_type', $user_type);
                $user_video_result = $this->db->get('learning_center_online_videos_assignments')->row_array();

                if (empty($user_video_result) || $user_video_result['watched'] == 0) {
                    $video_count = $video_count + 1;
                } else {
                    if (!empty($single_r['screening_questionnaire_sid']) || $single_r['screening_questionnaire_sid'] != 0) {
                        $this->db->select('sid');
                        $this->db->where('video_assign_sid', $user_video_result['sid']);
                        $this->db->where('video_sid', $single_r['sid']);
                        $user_video_questionnaire_result = $this->db->get('learning_center_screening_questionnaire')->row_array();

                        if (empty($user_video_questionnaire_result)) {
                            $video_count = $video_count + 1;
                        }
                    }
                }
            }
        }
        //
        return $video_count;
    }

    function getDepartmentEmployees(
        $companySid,
        $departmentSids,
        $dept = FALSE
    ) {
        $this->db
            ->select('users.sid')
            ->join('users', 'users.sid = departments_employee_2_team.employee_sid', 'inner')
            ->where('users.active', 1)
            ->where('users.parent_sid', $companySid)
            ->where('users.terminated_status', 0);
        //
        if ($dept) {
            $this->db
                ->select('departments_management.sid as department_sid')
                ->select('departments_management.name')
                ->where('departments_management.is_deleted', 0)
                ->where('departments_management.status', 1)
                ->join('departments_management', 'departments_management.sid = departments_employee_2_team.department_sid', 'inner');
        }
        //
        if ($departmentSids != 'all') $this->db->where_in('departments_employee_2_team.sid', $departmentSids);
        //
        $a = $this->db->get('departments_employee_2_team');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        if (!sizeof($b)) return array();
        //
        $r = array();
        //
        if (!$dept) foreach ($b as $k => $v) $r[] = $v['sid'];
        else {
            foreach ($b as $k => $v) {
                $r[$v['department_sid']][$v['sid']] = true;
                $r['all'][$v['sid']] = true;
            }
        }
        //
        return $r;
    }

    function get_documents_count($user_type, $user_sid)
    {

        $this->db->where('documents_assigned.user_type', $user_type);
        $this->db->where('documents_assigned.user_sid', $user_sid);
        $this->db->where('documents_assigned.is_pending', 1);
        $this->db->where('documents_assigned.document_type <>', 'offer_letter');
        $this->db->where('documents_assigned.astatus', 1);
        $this->db->where('documents_management.archive', 0);
        $this->db->join('documents_management', 'documents_management.sid = documents_assigned.document_sid', 'left');
        $this->db->from('documents_assigned');
        return $this->db->count_all_results();
    }

    function check_w4_form_exist($user_type, $user_sid)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('employer_sid', $user_sid);
        $this->db->group_start();
        $this->db->where('user_consent ', 0);
        $this->db->or_where('user_consent', NULL);
        $this->db->group_end();
        $this->db->where('status', 1);

        $this->db->from('form_w4_original');

        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }

    function check_w9_form_exist($user_type, $user_sid)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->group_start();
        $this->db->where('user_consent ', 0);
        $this->db->or_where('user_consent', NULL);
        $this->db->group_end();
        $this->db->where('status', 1);

        $this->db->from('applicant_w9form');

        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }

    function check_i9_exist($user_type, $user_sid)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->group_start();
        $this->db->where('user_consent ', 0);
        $this->db->or_where('user_consent', NULL);
        $this->db->group_end();
        $this->db->where('status', 1);
        $this->db->from('applicant_i9form');

        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }

    function get_assigned_offer_letter($company_sid, $user_type, $user_sid = null)
    {
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('document_type', 'offer_letter');
        $this->db->where('archive', 0);
        $this->db->where('status', 1);
        $this->db->where('user_consent', 0);

        $record_obj = $this->db->get('documents_assigned');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }

    /**
     * Get company timezone
     * Created on: 03-07-2019
     *
     * @param $company_id Integer
     * @param $do_clean   Bool     Optional
     *
     * @return String
     */
    function get_company_previous_timezone($company_id, $do_clean = TRUE)
    {
        $result = $this->db
            ->select('company_timezone')
            ->from('portal_employer')
            ->where('user_sid', $company_id)
            ->get();

        $timezone = $result->row_array();
        $result->free_result();
        //
        if (isset($timezone['company_timezone'])) $timezone = $timezone['company_timezone'];
        else $timezone = '';

        $return_timezone = $timezone = trim(strtolower($timezone));

        if ($do_clean) {
            if ($timezone == '' || $timezone == NULL || $timezone == 'null' || !preg_match('/^[a-z]/', $timezone)) $return_timezone = '';
            if ($timezone == strtolower(STORE_DEFAULT_TIMEZONE_ABBR)) $return_timezone = $timezone;

            return $return_timezone;
        }

        return strtoupper($timezone);
    }

    /**
     * Change calendar events timezone
     * Created on: 03-07-2019
     * TODO
     *
     * @param $company_id Integer
     * @param $new_timezone String
     *
     * @return Array|Bool
     */
    function set_new_timezone_in_old_calendar_events_by_company_id($company_id, $new_timezone)
    {
        if ($new_timezone == '') return false;
        // Fetch previous company timezone
        // Added on: 03-07-2019
        $prev_timezone = $this->get_company_previous_timezone($company_id);
        // Execute when timezone is not set
        // for the company
        if ($prev_timezone != '') return false;
        // Update users timezone
        $this->db
            ->where('parent_sid', $company_id)
            ->update('users', array('timezone' => ucwords(trim($new_timezone))));
        if ($new_timezone == STORE_DEFAULT_TIMEZONE_ABBR) return false;
        // Fetch events
        $result = $this->db
            ->select('sid, date, eventstarttime, eventendtime')
            ->from('portal_schedule_event')
            ->where('companys_sid', $company_id)
            ->order_by('sid', 'DESC')
            ->get();
        //
        $events = $result->result_array();
        $result->free_result();
        //
        if (!sizeof($events)) return false;
        //
        foreach ($events as $k0 => $v0) {
            // Reset times
            $v0['eventstarttime'] = !preg_match('/am|AM|pm|PM/', $v0['eventstarttime']) ? $v0['eventstarttime'] . 'AM' : $v0['eventstarttime'];
            $v0['eventendtime']   = !preg_match('/am|AM|pm|PM/', $v0['eventendtime']) ? $v0['eventendtime'] . 'AM' : $v0['eventendtime'];
            $v0['eventstarttime'] = preg_replace('/\s+/', '', $v0['eventstarttime']);
            $v0['eventendtime']   = preg_replace('/\s+/', '', $v0['eventendtime']);
            // Reset the date
            $new_date = reset_datetime(array(
                'datetime' => $v0['date'] . ' ' . $v0['eventstarttime'],
                'from_format' => 'Y-m-d h:iA',
                'format' => 'Y-m-d',
                '_this' => $this,
                'new_zone' => STORE_DEFAULT_TIMEZONE_ABBR,
                'from_timezone' => $new_timezone
            ));

            // Reset the start time
            $new_start_time = reset_datetime(array(
                'datetime' => $v0['date'] . ' ' . $v0['eventstarttime'],
                'from_format' => 'Y-m-d h:iA',
                'format' => 'h:iA',
                '_this' => $this,
                'new_zone' => STORE_DEFAULT_TIMEZONE_ABBR,
                'from_timezone' => $new_timezone
            ));

            // Reset the end time
            $new_end_time = reset_datetime(array(
                'datetime' => $v0['date'] . ' ' . $v0['eventendtime'],
                'from_format' => 'Y-m-d h:iA',
                'format' => 'h:iA',
                '_this' => $this,
                'new_zone' => STORE_DEFAULT_TIMEZONE_ABBR,
                'from_timezone' => $new_timezone
            ));

            // Create a records array
            $history_array = array(
                'event_sid' => $v0['sid'],
                'company_sid' => $company_id,
                'user_id' => $company_id,
                'user_type' => 'employee',
                'old_date' => $v0['date'],
                'old_start_time' => $v0['eventstarttime'],
                'old_end_time' => $v0['eventendtime'],
                // 'old_timezone' => $prev_timezone != '' ? $prev_timezone : STORE_DEFAULT_TIMEZONE_ABBR,
                'old_timezone' => $new_timezone,
                'new_date' => $new_date,
                'new_start_time' => $new_start_time,
                'new_end_time' => $new_end_time,
                'new_timezone' => $prev_timezone != '' ? $prev_timezone : STORE_DEFAULT_TIMEZONE_ABBR
            );
            // Save history of event
            $this->db->insert('portal_event_timezone_change_history', $history_array);
            // Update event datetime
            $this->db->where('sid', $v0['sid']);
            $this->db->update('portal_schedule_event', array(
                'date' => $new_date,
                'eventstarttime' => $new_start_time,
                'eventendtime' => $new_end_time
            ));
            // _e('', true);
            // _e($v0['sid']);
            // _e($history_array);
            // _e('Old date = '.$v0['date'].', New date = '.$new_date);
            // _e('Old start time = '.$v0['eventstarttime'].', New start time = '.$new_start_time);
            // _e('Old end time = '.$v0['eventendtime'].', New end time = '.$new_end_time);
            // _e('', true, true);

            //
        }
        return true;
    }

    function get_assigned_documents($company_sid, $user_type, $user_sid = null, $status = 1, $fetch_offer_letter = 1)
    {
        $this->db->select('
            documents_assigned.*,
            documents_management.acknowledgment_required,
            documents_management.download_required,
            documents_management.signature_required,
            documents_management.archive as company_archive
        ');
        if (ASSIGNEDOCIMPL) $this->db->select('documents_assigned.acknowledgment_required,documents_assigned.download_required,documents_assigned.signature_required');
        $this->db->where('documents_assigned.company_sid', $company_sid);
        $this->db->where('documents_assigned.status', 1);
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);


        // $this->db->where('documents_management.archive', 0);

        if ($fetch_offer_letter) {
            $this->db->where('documents_assigned.document_type <>', 'offer_letter');
        }

        if ($status) {
            $this->db->where('status', $status);
        }

        $this->db->join('documents_management', 'documents_management.sid = documents_assigned.document_sid', 'left');
        $record_obj = $this->db->get('documents_assigned');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    function assigned_incidents_count($employee_id, $company_id)
    {
        $this->db->select('sid');
        $this->db->where('employer_sid', $employee_id);
        $this->db->where('company_sid', $company_id);
        $this->db->where('incident_status', 'pending');
        $this->db->from('incident_assigned_emp');

        return $this->db->count_all_results();
    }

    function get_license_details($user_type, $user_sid, $license_type)
    {
        $this->db->select('*');
        $this->db->where('users_type', $user_type);
        $this->db->where('users_sid', $user_sid);
        $this->db->where('license_type', $license_type);
        $this->db->limit(1);

        $record_obj = $this->db->get('license_information');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function update_users_data($sid, $data)
    {
        $this->db->where('sid', $sid);
        $this->db->update('users', $data);
    }

    function update_applicant_data($sid, $data)
    {
        $this->db->where('sid', $sid);
        $this->db->update('portal_job_applications', $data);
    }

    //
    function get_drivers_license($userId, $userType = 'employee')
    {
        $a =
            $this->db
            ->where('users_sid', $userId)
            ->where('users_type', $userType)
            ->where('license_type', 'drivers')
            ->get('license_information');
        //
        $b = $a->row_array();
        //
        $a->free_result();
        //
        unset($a);
        //
        return $b;
    }

    //
    function addLicense($data)
    {
        $this->db->insert('license_information', $data);
        return $this->db->insert_id();
    }


    function GetJobLastStateByIds($jobIds)
    {
        //
        $a = $this->db
            ->select('deactive_by_name, portal_job_listings_sid, deactive_date')
            ->where_in('portal_job_listings_sid', $jobIds)
            ->where('deactive_date IS NOT NULL', NULL)
            ->where('active', 0)
            ->get('portal_job_listings_record');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        $t = [];
        foreach ($b as $v) {
            $t[$v['portal_job_listings_sid']] = $v;
        }
        return $t;
    }

    function get_employee_handbook_status($company_sid)
    {
        //
        return
            $this->db
            ->where('user_sid', $company_sid)
            ->where('employee_handbook', 1)
            ->count_all_results('portal_employer');
    }

    function check_company_employee_handbook_category($company_sid, $key = "employeehandbook")
    {
        //
        $this->db->select('sid');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('status', 1);
        $this->db->group_start();
        $this->db->like('REPLACE(TRIM(LOWER(name)), " ", "") ', $key);
        $this->db->or_like('REPLACE(TRIM(LOWER(name)), " ", "") ', 'employeehandbookandpolicies');
        $this->db->or_like('REPLACE(TRIM(LOWER(name)), " ", "") ', 'employeehandbook&policies');
        $this->db->or_like('REPLACE(TRIM(LOWER(name)), " ", "") ', 'employeepoliciesandhandbooks');
        $this->db->or_like('REPLACE(TRIM(LOWER(name)), " ", "") ', 'employeepolicies&handbooks');
        $this->db->group_end();

        $record_obj = $this->db->get('documents_category_management');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr['sid'];
        } else {
            return 0;
        }
    }
    /**
     * deprecated as of 2023-06-20
     * verify and remove
     */
    function get_employee_handbook_documents($category_sid, $employee_sid)
    {
        $this->db->select('document_sid, document_type');
        $this->db->where('category_sid', $category_sid);
        $this->db->order_by('document_sid', 'DESC');

        $records_obj = $this->db->get('documents_2_category');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        //
        $documents = [
            'assigned' => [],
            'original' => []
        ];
        //
        if (empty($records_arr)) {
            return $documents;
        }
        //
        foreach ($records_arr as $row) {
            // Set the default where clause
            $whereArray = [
                'user_type' => "employee",
                'user_sid' => $employee_sid,
            ];
            //
            $whereArray[$row['document_type'] == "documents_management" ? "document_sid" : "sid"] = $row['document_sid'];
            // Check if document is assigned
            if (
                $this->db
                ->where($whereArray)
                ->count_all_results('documents_assigned')
            ) {
                //
                $document = $this->db
                    ->where($whereArray)
                    ->get('documents_assigned')
                    ->row_array();
                //
                if (!empty($document)) {
                    $documents['assigned'][] = $document;
                }
            } else {
                // Get the original document
                $document = $this->db
                    ->where(['sid' => $row['document_sid']])
                    ->get('documents_management')
                    ->row_array();
                //
                if (!empty($document)) {
                    $documents['original'][] = $document;
                }
            }
        }
        //
        return $documents;
    }

    function getAllCompanyInactiveEmployee($companySid)
    {
        $a = $this->db
            ->select('
            sid
        ')
            ->where('parent_sid', $companySid)
            ->where('active', 0)
            ->where('parent_sid <> ', 0)
            ->or_where('terminated_status', 1)
            ->order_by('first_name', 'ASC')
            ->get('users');
        //
        $b = $a->result_array();
        $a = $a->free_result();

        return array_column($b, 'sid');
    }

    function getAllCompanyInactiveApplicant($companySid)
    {
        $a = $this->db
            ->select('
            portal_applicant_jobs_list.portal_job_applications_sid as sid
        ')
            ->where('portal_applicant_jobs_list.company_sid', $companySid)
            ->group_start()
            ->where('portal_applicant_jobs_list.archived', 1)
            ->or_where('portal_job_applications.hired_status', 1)
            ->group_end()
            ->join('portal_job_applications', 'portal_job_applications.sid = portal_applicant_jobs_list.portal_job_applications_sid', 'left')
            ->get('portal_applicant_jobs_list');
        //
        $b = $a->result_array();
        $a = $a->free_result();

        return array_column($b, 'sid');
    }

    /**
     * Get the count of available documents
     * 
     * @param number @company_id
     * @return number
     */
    function get_all_library_doc_count($company_id)
    {
        return  $this->db
            ->where('documents_management.company_sid', $company_id)
            ->where('documents_management.isdoctolibrary', 1)
            ->where('documents_management.archive', 0)
            ->count_all_results('documents_management');
    }


    //
    public function GetAllUsersNew($company_sid)
    {
        $this->db->where('parent_sid', $company_sid);
        $this->db->where('username !=', '');
        $this->db->where('active', 1);
        $this->db->where('terminated_status', 0);
        $this->db->where('access_level!=', 'Employee');
        $this->db->order_by(SORT_COLUMN, SORT_ORDER);
        $result = $this->db->get('users')->result_array();

        if (!empty($result)) {
            return $result;
        } else {
            return array();
        }
    }

    /**
     * get all handbook documents
     *
     * @param int $company_id
     * @param int $employee_sid
     * @return array
     */
    function get_employee_handbook_documents_new($company_id, $employee_sid)
    {
        // set default return array
        $documents = [
            'assigned' => [],
            'original' => []
        ];
        // get assigned documents
        $documents['assigned'] = $this->db
            ->where('company_sid', $company_id)
            ->where('user_sid', $employee_sid)
            ->where('isdoctohandbook', 1)
            ->get('documents_assigned')
            ->result_array();
        // set the query
        $this->db->where('company_sid', $company_id);
        $this->db->where('isdoctohandbook', 1);
        // check for non empty array
        if ($documents['assigned']) {
            $this->db->where_not_in('sid', array_column($documents['assigned'], 'document_sid'));
        }
        // get the company documents
        $documents['original'] = $this->db->get('documents_management')->result_array();
        // return the array
        return $documents;
    }

    function getLMSStatus($companySid)
    {
        $a = $this->db
            ->select('
            portal_applicant_jobs_list.portal_job_applications_sid as sid
        ')
            ->where('portal_applicant_jobs_list.company_sid', $companySid)
            ->group_start()
            ->where('portal_applicant_jobs_list.archived', 1)
            ->or_where('portal_job_applications.hired_status', 1)
            ->group_end()
            ->join('portal_job_applications', 'portal_job_applications.sid = portal_applicant_jobs_list.portal_job_applications_sid', 'left')
            ->get('portal_applicant_jobs_list');
        //
        $b = $a->result_array();
        $a = $a->free_result();

        return array_column($b, 'sid');
    }
}
