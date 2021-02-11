<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auto_clone_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    function get_all_jobs() {
        $this->db->select('sid, user_sid, active');
        $this->db->order_by('sid', 'asc');
        $this->db->from('portal_job_listings');

        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function get_all_active_jobs() {
        $this->db->select('sid, user_sid');
        $this->db->where('active', 1);
        $this->db->order_by('sid', 'asc');
        $this->db->from('portal_job_listings');

        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function insert_clone_data() {
        $this->db->select('sid, user_sid, active');
        $this->db->order_by('sid', 'asc');
        $this->db->from('portal_job_listings');

        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        $insert_data = array();

        for ($i = 0; $i < count($records_arr); $i++) {
            $job_sid = $records_arr[$i]['sid'];
            $job_sid_count = strlen($job_sid);
            $random_key_length = 22 - $job_sid_count;
            $auto_random_number = $this->random_key($random_key_length);
            $random_number = $auto_random_number . $job_sid;

            $insert_data = array(   'job_sid' => $records_arr[$i]['sid'],
                                    'company_sid' => $records_arr[$i]['user_sid'],
                                    'job_status' => $records_arr[$i]['active'],
                                    'active' => 1,
                                    'publish_date' => date('Y-m-d H:i:s'),
                                    'uid' => $random_number);

            $this->db->insert('portal_job_listings_feeds_data', $insert_data);
        }
    }

    function random_key($str_length = 22) {
        $characters = '0123456789abcdefghijklmnopqrstuvw01234hijk56789';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $str_length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    function update_job_uid($job_sid = NULL, $company_sid = NULL) {
        if ($job_sid != NULL && $company_sid != NULL) {
            $random_number = $this->generate_unique_uid($job_sid);
            $data = array('active' => 0);
            $this->db->where('job_sid', $job_sid);
            $this->db->update('portal_job_listings_feeds_data', $data);

            $insert_data = array(   'job_sid' => $job_sid,
                                    'company_sid' => $company_sid,
                                    'job_status' => 1,
                                    'active' => 1,
                                    'publish_date' => date('Y-m-d H:i:s'),
                                    'uid' => $random_number);

            $this->db->insert('portal_job_listings_feeds_data', $insert_data);
        }
    }

    function check_autofeed_days_counter() {
        $this->db->select('*');
        $this->db->from('portal_job_listings_feeds_counter');

        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0]['days'];
        } else {
            $insert_data = array('days' => 1, 'last_run_date' => date('Y-m-d H:i:s'));
            $this->db->insert('portal_job_listings_feeds_counter', $insert_data);
            return 1;
        }
    }

    function reset_counter_data() {
        $this->db->truncate('portal_job_listings_feeds_counter');
        $insert_data = array('days' => 1, 'last_run_date' => date('Y-m-d H:i:s'));
        $this->db->insert('portal_job_listings_feeds_counter', $insert_data);
    }

    function update_counter_data() {
        $this->db->where('sid', 1);
        $this->db->set('days', 'days+1', FALSE);
        $this->db->update('portal_job_listings_feeds_counter');
    }

    function check_uniqueness_of_uid($random_number, $job_sid) {
        $this->db->select('sid');
        $this->db->where('uid', $random_number);
        $this->db->from('portal_job_listings_feeds_data');

        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (empty($records_arr)) {
            return true;
        } else {
            return false;
        }
    }

    function generate_unique_uid($job_sid) {
        $job_sid_count = strlen($job_sid);
        $random_key_length = 22 - $job_sid_count;
        $auto_random_number = $this->random_key($random_key_length);
        $random_number = $auto_random_number . $job_sid;
        return $random_number;
    }

    function get_job_applicants() {
        $this->db->select('t1.sid, t1.portal_job_applications_sid, t1.company_sid, t1.job_sid, t1.date_applied, t1.ip_address, t1.user_agent, t1.applicant_source');
        $this->db->select('t2.email');
        $this->db->select('t3.user_sid');
        $this->db->where('t1.company_sid', NULL);
        $this->db->join('portal_job_applications as t2', 't1.portal_job_applications_sid = t2.sid', 'left');
        $this->db->join('portal_job_listings as t3', 't1.job_sid = t3.sid', 'left');
        $this->db->from('portal_applicant_jobs_list t1');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function update_job_applicants($sid, $company_sid) {
        $this->db->where('sid', $sid);
        $this->db->set('company_sid', $company_sid);
        $this->db->update('portal_applicant_jobs_list');
        echo '<br>' . $this->db->last_query();
    }

    function get_active_uids($today_start = null, $limit = null) {
        $this->db->select('*');
        $this->db->where('active', 1);
        $this->db->where('job_status', 1);
        $this->db->order_by('sid', 'desc');
        $this->db->from('portal_job_listings_feeds_data');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function get_active_published_today($today_start, $today_end) {
        $this->db->select('*');
        $this->db->where('active', 1);
        $this->db->where('job_status', 1);
        $this->db->where('publish_date BETWEEN \'' . $today_start . '\' AND \'' . $today_end . '\'');
        $this->db->from('portal_job_listings_feeds_data');
        return $this->db->count_all_results();
    }

    function update_company_job_status($job_sid, $company_sid, $sid) {
        $this->db->select('active, has_job_approval_rights');
        $this->db->where('parent_sid', 0);
        $this->db->where('sid', $company_sid);
        $this->db->from('users');

        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            $company_status = $records_arr[0]['active'];
            $has_job_approval_rights = $records_arr[0]['has_job_approval_rights'];

            if ($company_status == 1) { // company is active - Check job Status
                $this->db->select('sid, active, approval_status');
                $this->db->where('sid', $job_sid);
                $this->db->from('portal_job_listings');

                $record_obj = $this->db->get();
                $record_arr = $record_obj->result_array();
                $record_obj->free_result();

                if (empty($record_arr)) {
                    $data = array('job_status' => 0);
                    $this->db->where('sid', $sid);
                    $this->db->update('portal_job_listings_feeds_data', $data);
                } else {
                    $job_status = $record_arr[0]['active'];
                    $job_approval_status = $record_arr[0]['approval_status'];

                    if ($has_job_approval_rights == 1) {
                        if ($job_status != 1 || $job_approval_status != 'approved') {
                            $data = array('job_status' => 0);
                            $this->db->where('sid', $sid);
                            $this->db->update('portal_job_listings_feeds_data', $data);
                        }
                    } else {
                        if ($job_status != 1) {
                            $data = array('job_status' => 0);
                            $this->db->where('sid', $sid);
                            $this->db->update('portal_job_listings_feeds_data', $data);
                        }
                    }
                }
            } else {
                $data = array('active' => 0);
                $this->db->where('sid', $sid);
                $this->db->update('portal_job_listings_feeds_data', $data);
            }
        } else { // company does not exists. Change the job status
            $data = array('active' => 0);
            $this->db->where('sid', $sid);
            $this->db->update('portal_job_listings_feeds_data', $data);
        }
    }

    function check_duplicate_key($job_sid) {
        $this->db->select('*');
        $this->db->where('active', 1);
        $this->db->where('job_status', 1);
        $this->db->where('job_sid', $job_sid);
        $this->db->order_by('sid', 'desc');
        $this->db->from('portal_job_listings_feeds_data');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        $count = count($records_arr);

        if ($count > 1) {
            foreach ($records_arr as $key => $value) {
                if ($key > 0) {
                    $sid = $value['sid'];
                    $data = array('job_status' => 0);
                    $this->db->where('sid', $sid);
                    $this->db->update('portal_job_listings_feeds_data', $data);
                }
            }
        }
    }

    function verify_each_job($job_sid, $company_sid) {
        $this->db->select('user_sid, active, Title, approval_status, organic_feed');
        $this->db->where('sid', $job_sid);
        $this->db->where('user_sid', $company_sid);
        $this->db->from('portal_job_listings');

        $record_obj = $this->db->get();
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        echo '<pre>';
        print_r($record_arr);
        echo '<pre>';
    }

    function get_all_company_jobs_indeed() {
        $product_sid = array(1, 21);
        $this->db->where_in('product_sid', $product_sid);
        $this->db->where('active', 1);
        $this->db->where('expiry_date > "' . date('Y-m-d H:i:s') . '"');
        $this->db->join('portal_job_listings', 'portal_job_listings.sid = jobs_to_feed.	job_sid');
        return $this->db->get('jobs_to_feed')->result_array();
    }

    function get_all_company_jobs_indeed_organic($featuredArray) {
        $this->db->select('user_sid, approval_status, sid, activation_date');
        $this->db->where('active', 1);
        $this->db->where('organic_feed', 1);
        $this->db->where_not_in('sid', $featuredArray);
        $this->db->order_by('sid', 'asc');

        $records_obj = $this->db->get('portal_job_listings');
        $result = $records_obj->result_array();
        $records_obj->free_result();
        return $result;
    }

    function get_all_active_companies() {
        $result = $this->db->query("SELECT `sid` FROM `users` WHERE `parent_sid` = '0' AND `career_site_listings_only` = 0 AND `active` = '1' AND (`expiry_date` > '2016-04-20 13:26:27' OR `expiry_date` IS NULL)")->result_array();
        if (count($result) > 0) {
            $data = array();
            foreach ($result as $r) {
                $data[] = $r['sid'];
            }
            return $data;
        } else {
            return array();
        }
    }

    function get_portal_detail($company_id) {
        $this->db->select('sid');
        $this->db->where('user_sid', $company_id);
        $records_obj = $this->db->get('portal_employer');
        $result = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($result)) {
            return $result[0];
        } else {
            return array();
        }
    }

    function get_company_name_and_job_approval($sid) {
        $this->db->select('CompanyName, has_job_approval_rights');
        $this->db->where('sid', $sid);
        $records_obj = $this->db->get('users');
        $result = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($result)) {
            return $result[0];
        } else {
            return array();
        }
    }

    function fetch_uid_from_job_sid($job_sid) {
        $this->db->select('uid, publish_date');
        $this->db->where('job_sid', $job_sid);
        $this->db->where('active', 1);
        $this->db->order_by('sid', 'desc');
        $this->db->limit(1);

        $record_obj = $this->db->get('portal_job_listings_feeds_data');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return '';
        }
    }

    function jobs_published_today($today_start, $today_end) {
        $this->db->select('sid');
        $this->db->where('activation_date >=', $today_start);
        $this->db->where('activation_date <=', $today_end);
        $this->db->from('portal_job_listings');
        return $this->db->count_all_results();
    }

}
