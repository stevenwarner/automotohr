<?php

class Dashboard_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function total_companies()
    {
        $this->db->from('users');
        $this->db->where('parent_sid', 0);
        $this->db->where('career_page_type', 'standard_career_site');
        return $this->db->get()->num_rows();
    }

    function active_companies($date = NULL)
    {
        $this->db->from('users');
        if ($date == 'today')
            $this->db->where('date(registration_date)', date('Y-m-d'));
        elseif ($date == 'week')
            $this->db->where('YEARWEEK(`registration_date`, 1) = YEARWEEK(CURDATE(), 1)');
        elseif ($date == 'month')
            $this->db->where('month(registration_date)', date('m'));

        $this->db->where('career_page_type', 'standard_career_site');
        $this->db->where('parent_sid', 0);
        $this->db->where('active', 1);

        return $this->db->get()->num_rows();
    }

    function not_active_companies($date = NULL)
    {
        $this->db->from('users');
        if ($date == 'today')
            $this->db->where('date(registration_date)', date('Y-m-d'));
        elseif ($date == 'week')
            $this->db->where('YEARWEEK(`registration_date`, 1) = YEARWEEK(CURDATE(), 1)');
        elseif ($date == 'month')
            $this->db->where('month(registration_date)', date('m'));

        $this->db->where('career_page_type', 'standard_career_site');
        $this->db->where('parent_sid', 0);
        $this->db->where('active', 0);
        return $this->db->get()->num_rows();
    }

    function total_employers()
    {
        $this->db->from('users');
        $this->db->where('parent_sid > ', 0);
        return $this->db->get()->num_rows();
    }

    function active_employers($date = NULL)
    {
        $this->db->from('users');
        $this->db->where('active', 1);
        if ($date == 'today')
            $this->db->where('date(registration_date)', date('Y-m-d'));
        elseif ($date == 'week')
            $this->db->where('YEARWEEK(`registration_date`, 1) = YEARWEEK(CURDATE(), 1)');
        elseif ($date == 'month')
            $this->db->where('month(registration_date)', date('m'));

        $this->db->where('parent_sid > ', 0);
        return $this->db->get()->num_rows();
    }

    function not_active_employers($date = NULL)
    {
        $this->db->from('users');
        $this->db->where('active', 0);
        if ($date == 'today')
            $this->db->where('date(registration_date)', date('Y-m-d'));
        elseif ($date == 'week')
            $this->db->where('YEARWEEK(`registration_date`, 1) = YEARWEEK(CURDATE(), 1)');
        elseif ($date == 'month')
            $this->db->where('month(registration_date)', date('m'));

        $this->db->where('parent_sid > ', 0);
        return $this->db->get()->num_rows();
    }


    function get_companies()
    {
        //$this->db->select('sid, username, career_page_type, email, registration_date, expiry_date, active, parent_sid, PhoneNumber, CompanyName, ContactName, WebSite, background_check, accounts_contact_person, accounts_contact_number, full_billing_address, is_paid, job_category_industries_sid');
        //$this->db->where('active', 1);
        $this->db->select('sid, active, registration_date, number_of_rooftops');
        $this->db->where('parent_sid', 0);
        $this->db->where('is_paid', 1);
        $this->db->where('career_page_type', 'standard_career_site');
        $this->db->from('users');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function get_employers()
    {
        //$this->db->select('*');
        //$this->db->where('active', 1);
        $this->db->select('sid, active, registration_date');
        $this->db->where('parent_sid >', 0);
        $this->db->from('users');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function get_total_job_listings()
    {
        $this->db->select('portal_job_listings.sid, portal_job_listings.active,portal_job_listings.approval_status,organic_feed,users.has_job_approval_rights,jobs_to_feed.expiry_date');
        $this->db->where_not_in('portal_job_listings.active', 2);
        $this->db->where('users.active', 1);
        $this->db->from('portal_job_listings');
        $this->db->join('users', 'portal_job_listings.user_sid = users.sid', 'left');
        $this->db->join('jobs_to_feed', 'jobs_to_feed.job_sid = portal_job_listings.sid', 'left');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function get_total_job_applications()
    {
        $this->db->select('sid, date_applied');
        $this->db->where('applicant_type', 'Applicant');
        $this->db->from('portal_applicant_jobs_list');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
        //return $this->db->get('portal_applicant_jobs_list')->result_array();
    }

    function get_total_earnings($from_date = null, $to_date = null, $test_companies = null)
    {
        $this->db->select_sum('amount');

        if (!empty($test_companies)) { //Exclude Test Companies
            $this->db->where_not_in('company_sid', $test_companies);
        }

        if ($from_date != null && $to_date != null) {
            $this->db->where('receipt_date BETWEEN \'' . $from_date . '\' AND \'' . $to_date . '\'');
        }

        $data_rows = $this->db->get('receipts')->result_array();

        if (!empty($data_rows)) {
            return $data_rows[0]['amount'];
        } else {
            return 0;
        }
    }



    function get_paid_sales($from_date = null, $to_date = null, $test_companies = null)
    {
        $this->db->select('receipt_date, amount, invoice_type');

        if (!empty($test_companies)) { //Exclude Test Companies
            $this->db->where_not_in('company_sid', $test_companies);
        }

        if ($from_date != null && $to_date != null) {
            $this->db->where('receipt_date BETWEEN \'' . $from_date . '\' AND \'' . $to_date . '\'');
        }

        $data_rows = $this->db->get('receipts')->result_array();
        return $data_rows;
    }

    function get_admin_invoices_total($from_date = null, $to_date = null, $status = 'all', $test_companies = null)
    {
        $this->db->select_sum('value'); //get all non-discounted invoices value
        $this->db->where('is_discounted', 0);

        if (!empty($test_companies)) { //Exclude Test Companies
            $this->db->where_not_in('company_sid', $test_companies);
        }

        if ($status != 'all') {
            $this->db->where('payment_status', $status);
            $this->db->where('invoice_status !=', 'deleted');
        }

        if ($from_date != null && $to_date != null) {
            $this->db->where('created BETWEEN \'' . $from_date . '\' AND \'' . $to_date . '\'');
        }

        $non_discounted_invoices_total = $this->db->get('admin_invoices')->result_array();
        //echo '<br>' . $this->db->last_query();

        //get all discounted invoices value
        $this->db->select_sum('total_after_discount');
        $this->db->where('is_discounted', 1);


        if (!empty($test_companies)) { //Exclude Test Companies
            $this->db->where_not_in('company_sid', $test_companies);
        }

        if ($status != 'all') {
            $this->db->where('payment_status', $status);
            $this->db->where('invoice_status !=', 'deleted');
        }

        if ($from_date != null && $to_date != null) {
            $this->db->where('created BETWEEN \'' . $from_date . '\' AND \'' . $to_date . '\'');
        }

        $discounted_invoices_total = $this->db->get('admin_invoices')->result_array();
        //echo '<br>' . $this->db->last_query();

        //echo $this->db->last_query();

        if (!empty($non_discounted_invoices_total)) {
            $non_discounted_invoices_total = $non_discounted_invoices_total[0]['value'];
        } else {
            $non_discounted_invoices_total = 0;
        }

        if (!empty($discounted_invoices_total)) {
            $discounted_invoices_total = $discounted_invoices_total[0]['total_after_discount'];
        } else {
            $discounted_invoices_total = 0;
        }

        return floatval($non_discounted_invoices_total) + floatval($discounted_invoices_total);
    }

    function get_admin_invoices_count($from_date = null, $to_date = null, $status = 'all', $test_companies = null)
    {
        $this->db->select('sid');

        if ($from_date != null && $to_date != null) {
            $this->db->where('created BETWEEN \'' . $from_date . '\' AND \'' . $to_date . '\'');
        }

        //Exclude Test Companies
        if (!empty($test_companies)) {
            $this->db->where_not_in('company_sid', $test_companies);
        }

        if ($status != 'all') {
            $this->db->where('payment_status', $status);
            $this->db->where('invoice_status !=', 'deleted');
        }

        return $this->db->get('admin_invoices')->num_rows();
    }


    function get_marketplace_invoices_total($from_date = null, $to_date = null, $status = 'all', $test_companies = null)
    {
        $this->db->select_sum('total');

        if ($from_date != null && $to_date != null) {
            $this->db->where('date BETWEEN \'' . $from_date . '\' AND \'' . $to_date . '\'');
        }

        //Exclude Test Companies
        if (!empty($test_companies)) {
            $this->db->where_not_in('company_sid', $test_companies);
        }

        if ($status != 'all') {
            $this->db->where('status', $status);
        }

        $data_row = $this->db->get('invoices')->result_array();

        if (!empty($data_row)) {
            return $data_row[0]['total'];
        } else {
            return 0;
        }
    }

    function get_marketplace_invoices_count($from_date = null, $to_date = null, $status = 'all', $test_companies = null)
    {
        $this->db->select('sid');

        if ($from_date != null && $to_date != null) {
            $this->db->where('created BETWEEN \'' . $from_date . '\' AND \'' . $to_date . '\'');
        }

        //Exclude Test Companies
        if (!empty($test_companies)) {
            $this->db->where_not_in('company_sid', $test_companies);
        }

        if ($status != 'all') {
            $this->db->where('payment_status', $status);
        }

        return $this->db->get('invoices')->num_rows();
    }


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
    function get_admins($status = -1, $fullname = TRUE, $exec = FALSE)
    {
        $this->db
            ->select('
            ' . (
                $fullname ? 'id as admin_id, concat(first_name, " ", last_name) as full_name' : 'id, first_name, last_name, "0" as show_email'
            ) . '
            ,
            email as email_address
        ')
            ->from('administrator_users')
            ->order_by('first_name', 'ASC');
        if ($status != -1) $this->db->where('active', $status);
        if ($exec) _e($this->db->get_compiled_select(), true);
        $result = $this->db->get();
        $result_arr = $result->result_array();
        $result = $result->free_result();
        return $result_arr;
    }


    /**
     * Get Demo Users
     * Created on: 10-06-2019
     *
     * @param $status Bool Optional (-1 = All, 0 = InActive, 1 = Active)
     * @param $exec Bool Optional
     *
     * @return Bool|Int
     */
    function get_demo_users($status = -1, $exec = FALSE)
    {
        $this->db
            ->select('
            sid as id,
            company_name,
            concat(first_name, " ", last_name) as full_name,
            "Demo" as type,
            email as email_address
        ')
            ->from('free_demo_requests')
            ->order_by('first_name', 'ASC');

        if ($status != -1) $this->db->where('status', $status);
        if ($exec) _e($this->db->get_compiled_select(), true);
        $result = $this->db->get();
        $result_arr = $result->result_array();
        $result = $result->free_result();
        return $result_arr;
    }

    /**
     * Get Affiliates Users
     * Created on: 10-06-2019
     *
     * @param $is_referred Bool Optional (-1 = All, 0 = InActive, 1 = Active)
     * @param $status Bool Optional (-1 = All, 0 = InActive, 1 = Active)
     * @param $exec Bool Optional
     *
     * @return Bool|Int
     */
    function get_affiliate_users($is_referred = -1, $status = -1, $exec = FALSE)
    {
        $type = $is_referred != -1 ? 'all_affiliate_users' : ($is_referred == 1 ? "referred_affiliate_users" : "direct_affiliate_users");
        $this->db
            ->select('
            sid as id, 
            concat(first_name, " ", last_name) as full_name,
            IF(is_reffered = 0, "Affiliate", "Referred Affiliate") as type,
            email as email_address
        ', false)
            ->from('affiliations')
            ->order_by('first_name', 'ASC');
        // To get active or inactive records
        if ($status != -1) $this->db->where('status', $status);
        // To get referred users
        if ($is_referred != -1) $this->db->where('is_reffered', (bool)$is_referred);
        // Display query
        if ($exec) _e($this->db->get_compiled_select(), true);
        // Execute query
        $result = $this->db->get();
        $result_arr = $result->result_array();
        $result = $result->free_result();
        return $result_arr;
    }


    /**
     * Get Affiliates Clients
     * Created on: 10-06-2019
     *
     * @param $is_referred Bool Optional (-1 = All, 0 = InActive, 1 = Active)
     * @param $status Bool Optional (-1 = All, 0 = InActive, 1 = Active)
     * @param $exec Bool Optional
     *
     * @return Bool|Int
     */
    function get_affiliate_clients($is_referred = -1, $status = -1, $exec = FALSE)
    {
        $type = $is_referred != -1 ? 'all_affiliate_clients' : ($is_referred == 1 ? "referred_affiliate_clients" : "direct_affiliate_clients");
        $this->db
            ->select('
            sid as id, 
            concat(first_name, " ", last_name) as full_name,
            IF(is_reffered = 0, "Affiliate Client", "Referred Affiliate Client") as type,
            email as email_address
        ', false)
            ->from('client_refer_by_affiliate')
            ->order_by('first_name', 'ASC');
        // To get active or inactive records
        if ($status != -1) $this->db->where('status', $status);
        // To get referred users
        if ($is_referred != -1) $this->db->where('is_reffered', (bool)$is_referred);
        // Display query
        if ($exec) _e($this->db->get_compiled_select(), true);
        // Execute query
        $result = $this->db->get();
        $result_arr = $result->result_array();
        $result = $result->free_result();
        return $result_arr;
    }


    /**
     * Insert/Update/Delete
     * Created on: 12-06-2019
     *
     * @param $table String
     * @param $data Array
     * @param $where Array
     * @param $type String
     *
     * @return Int|Bool
     */
    function _q($table, $data, $where = array(), $type = 'insert')
    {
        if ($type == 'insert') {
            $this->db->insert(
                $table,
                $data
            );
            return $this->db->insert_id();
        }
        if ($type == 'update')
            return $this->db->update(
                $table,
                $data,
                $where
            );
        if ($type == 'delete')
            return $this->db->delete(
                $table,
                $data
            );
    }

    /**
     * Get events
     * for calendars
     * Created on: 12-06-2019
     * 
     * @param $type String
     * @param $yesr String
     * @param $month String
     * @param $day String
     * @param $week_start String
     * @param $week_end String
     * @param $admin_id Integer
     * @param $event_type String
     * @param $exec Bool
     *
     * @return Array|Bool
     */
    function get_events($type, $year, $month, $day, $week_start = FALSE, $week_end = FALSE, $admin_id, $event_type, $exec = FALSE)
    {
        // * to be removed after testing
        $this->db->select('
            admin_events.sid as event_sid,
            admin_events.creator_sid,
            admin_events.event_title,
            admin_events.event_category,
            admin_events.event_date,
            DATE_FORMAT(admin_events.event_date, "%m-%d-%Y") as front_date,
            admin_events.event_start_time,
            admin_events.event_end_time,
            admin_events.event_status,
            admin_events.event_status as last_status,
            admin_events.is_recur,
            admin_events.recur_type,
            admin_events.recur_start_date,
            admin_events.recur_end_date,
            admin_events.recur_list
        ')
            ->from('admin_events')
        ;

        //
        if ($event_type == 'own') {
            $this->db
                ->select('"false" as editFlag')
                ->group_start()
                ->where("FIND_IN_SET(" . $admin_id . ", participants)")
                ->or_where("creator_sid", $admin_id)
                ->or_where("user_id", $admin_id)
                ->group_end();
        }

        // check for type
        if ($type == 'day')
            $this->db->where('event_date = ', ($year . '-' . $month . '-' . $day));
        else { // month, week
            $from_date = $year . '-' . $week_start;
            $to_date   = $year . '-' . $week_end;
            $this->db->where('event_date between "' . $from_date . '" and "' . $to_date . '"', null);
        }

        //
        if ($exec) return $this->db->get_compiled_select();
        //
        $result = $this->db->get();
        $events = $result->result_array();
        $result = $result->free_result();
        //
        if (!sizeof($events)) return false;
        //
        $date_check = date('Y-m-d');
        foreach ($events as $k0 => $v0) {
            // get status changes 
            $result = $this->db
                ->select('event_status')
                ->where('event_sid', $v0['event_sid'])
                ->where('user_sid', $v0['creator_sid'])
                ->from('admin_event_history')
                ->order_by('created_at', 'DESC')
                ->limit('1')
                ->get();
            $admin_event_history = $result->row_array()['event_status'];
            $result = $result->free_result();
            //
            $events[$k0]['color'] = $v0['color'] = get_calendar_event_color($v0['event_category']);
            //
            $events[$k0]['event_start_time_24Hr'] = $v0['event_start_time_24Hr'] = DateTime::createFromFormat('h:i A', $v0['event_start_time'])->format('H:i:s');
            $events[$k0]['event_end_time_24Hr']   = $v0['event_end_time_24Hr']   = DateTime::createFromFormat('h:i A', $v0['event_end_time'])->format('H:i:s');
            // $events[$k0]['external_participants'] = $v0['external_participants'] = $this->get_event_external_participants_names($v0['sid']);
            // $v0['interviewers_names'] = $this->get_interviewers_name($v0['interviewer']);
            // $v0['applicant_job_names'] = $this->get_applicant_job_names($v0['applicant_jobs_list']);

            // back event
            $events[$k0]['json'] = array();
            $events[$k0]['json']['title'] = $v0['event_title'];
            $events[$k0]['json']['start'] = $v0['event_date'] . 'T' . $v0['event_start_time_24Hr'];
            $events[$k0]['json']['end']   = $v0['event_date'] . 'T' . $v0['event_end_time_24Hr'];
            $events[$k0]['json']['color'] = $v0['color'];
            $events[$k0]['json']['event_sid'] = $v0['event_sid'];
            $events[$k0]['json']['event_category'] = $events[$k0]['event_category'];
            $events[$k0]['json']['event_date'] = $v0['event_date'];
            $events[$k0]['json']['editable'] = true;
            $events[$k0]['json']['expired_status'] = $v0['event_date'] < $date_check ? true : false;
            $events[$k0]['json']['is_recur']  = 0;
            $events[$k0]['json']['recur_type']  = $v0['recur_type'];
            $events[$k0]['json']['recur_start_date'] = $v0['recur_start_date'] === NULL ? NULL : $v0['recur_start_date'] . 'T23:59:59';
            $events[$k0]['json']['recur_end_date']   = $v0['recur_end_date'] === NULL ? NULL : $v0['recur_end_date'] . 'T23:59:59';
            $events[$k0]['json']['recur_list']       = empty($v0['recur_list']) ? NULL : $v0['recur_list'];
            // Added on: 02-05-2019
            $events[$k0]['json']['status'] = $admin_event_history != NULL ? (
                $admin_event_history == 'cannotattend' ? 'cancelled' : (
                    $admin_event_history == 'reschedule' ? 'rescheduled' : $admin_event_history
                )
            ) : $v0['event_status'];
            //
            $events[$k0]['json']['last_status']  = $admin_event_history != NULL ? (
                $admin_event_history == 'cannotattend' ? 'cancelled' : (
                    $admin_event_history == 'reschedule' ? 'rescheduled' : $admin_event_history
                )
            ) : 'pending';

            // Get status changes 
            $events[$k0]['json']['new_event_requests'] = $this->db
                ->select('event_sid')
                ->where('event_sid', $v0['event_sid'])
                ->where('event_status !=', 'confirmed')
                ->where('status', '0')
                ->from('admin_event_history')
                ->count_all_results();

            $events[$k0]['json']['event_requests'] = $this->db
                ->select('event_sid')
                ->where('event_sid', $v0['event_sid'])
                ->from('admin_event_history')
                ->count_all_results();
            $events[$k0] =  $events[$k0]['json'];
        }

        $events = array_values($events);
        return $events;
        // _e($events, true, true);
    }


    /**
     * Get event details
     * Created on: 12-06-2019
     * 
     * @param $event_sid Integer
     * @param $exec Bool Optional
     * 
     * @return Array|Bool
     */
    function event_detail($event_sid, $exec = FALSE)
    {
        $this->db
            ->select('
            admin_events.sid as event_sid,
            admin_events.parent_event_sid,
            admin_events.creator_sid,
            admin_events.user_id,
            admin_events.user_name,
            admin_events.user_email,
            admin_events.user_phone,
            admin_events.user_type,
            admin_events.event_title,
            admin_events.event_date,
            admin_events.event_start_time,
            admin_events.event_end_time,
            admin_events.event_type,
            admin_events.event_category,
            admin_events.event_status,
            admin_events.meeting_id,
            admin_events.meeting_phone,
            admin_events.meeting_url,
            admin_events.reminder_check,
            admin_events.reminder_duration,
            admin_events.reminder_sent_flag,
            admin_events.event_address,
            admin_events.comment,
            admin_events.event_address,
            admin_events.is_recur,
            admin_events.recur_type,
            admin_events.recur_start_date,
            admin_events.recur_end_date,
            admin_events.recur_list,
            admin_events.updated_at as last_modified_at,
            admin_events.participants,
            admin_events.participants_show_email_list,
            administrator_users.first_name as creator_first_name,
            administrator_users.last_name as creator_last_name,
            administrator_users.email as creator_email_address
        ')
            ->from('admin_events')
            ->join('administrator_users', 'administrator_users.id = admin_events.creator_sid', 'inner')
            ->where('admin_events.sid', $event_sid);

        $result = $this->db->get();
        $event = $result->row_array();
        $result = $result->free_result();
        // Set default user array
        $event['users_array'] = array();

        // Fetch user details if exists
        if ($event['user_id'] != NULL) {
            // For single user
            if ((int)$event['user_id'] !== 0) {
                $table = $event['user_type'] == 'affiliate' ? 'affiliations' : ($event['user_type'] == 'demo' ? 'free_demo_requests' : ($event['user_type'] == 'admin' ? 'administrator_users' : 'client_refer_by_affiliate')
                );

                $columns = $event['user_type'] == 'admin' ? 'first_name, last_name, email as email_address' : 'first_name, last_name, email as email_address, is_reffered';
                $column = $table == 'administrator_users' ? 'id' : 'sid';
                $this->db->select($columns)
                    ->where($column, $event['user_id'])
                    ->from($table);
                //
                $result = $this->db->get();
                $user = $result->row_array();
                if (isset($user['first_name'])) {
                    $user['first_name'] = ucwords($user['first_name']);
                    $user['last_name']  = ucwords($user['last_name']);
                }
                $result->free_result();

                $event = array_merge($event, $user);
            } else { // For multiple users

                // Parse the user ids
                $event['user_ids'] = $user_id_array = @json_decode($event['user_id'], true);
                if ($user_id_array != '') {

                    foreach ($user_id_array as $k0 => $v0) {
                        switch ($v0['type']) {
                            case 'affiliate':
                                $table = 'affiliations';
                                break;
                            case 'demo':
                                $table = 'free_demo_requests';
                                break;
                            case 'admin':
                            case 'super admin':
                                $table = 'administrator_users';
                                break;
                            default:
                                $table = 'client_refer_by_affiliate';
                                break;
                        }

                        $columns = ($v0['type'] == 'admin' || $v0['type'] == 'super admin') ? 'first_name, last_name, email as email_address' : 'first_name, last_name, email as email_address, is_reffered';
                        $column = $table == 'administrator_users' ? 'id' : 'sid';
                        $this->db->select($columns)
                            ->where($column, $v0['id'])
                            ->from($table);
                        //
                        $result = $this->db->get();
                        $user = $result->row_array();
                        if (isset($user['first_name'])) {
                            $user_id_array[$k0]['first_name'] = ucwords($user['first_name']);
                            $user_id_array[$k0]['last_name']  = ucwords($user['last_name']);
                            $user_id_array[$k0]['email_address']  = strtolower($user['email_address']);
                        }
                        $result->free_result();
                    }
                    //                
                    $event['users_array'] = $user_id_array;
                }
            }
        }

        $tmp = array();

        // get participants detail
        // if(in_array($event['event_category'], array('meeting', 'training-session', 'other'))){
        $participants_show_email_list = explode(',', $event['participants_show_email_list']);
        $participants = explode(',', $event['participants']);
        if (sizeof($participants)) {
            if ($participants[0] != 'all') {
                foreach ($participants as $k0 => $v0) {
                    $result = $this->db->select('email as email_address, first_name, last_name, "' . $v0 . '" as id')
                        ->from('administrator_users')
                        ->where('id', $v0)
                        ->get();

                    $result_arr = $result->row_array();
                    $result->free_result();

                    $result_arr['show_email'] = in_array($v0, $participants_show_email_list) ? 1 : 0;

                    $tmp[] = $result_arr;
                }
            } else {
                $tmp = $this->get_admins(-1, false);
            }
        }
        // }

        // Set Participants array
        $event['participants'] = $tmp;

        // get external detail
        // if(in_array($event['event_category'], array('meeting', 'training-session', 'other'))){
        $result = $this->db
            ->select('external_participant_name, external_participant_email, show_email')
            ->where('event_sid', $event_sid)
            ->where('external_type', 'participant')
            ->from('admin_event_extra_participants')
            ->get();
        // }
        // Get external participants
        $event['external_participants'] = $result->result_array();
        $result->free_result();
        // Get external users
        $result = $this->db
            ->select('external_participant_name as name, external_participant_email as email_address,show_email')
            ->from('admin_event_extra_participants')
            ->where('event_sid', $event['event_sid'])
            ->where('external_type', 'user')
            ->order_by('external_participant_name', 'ASC')
            ->get();
        //
        $event['external_users'] = $result->result_array();
        //
        $result->free_result();

        // Get status changes 
        $event['new_event_requests'] = $this->db
            ->select('event_sid')
            ->where('event_sid', $event_sid)
            ->where('event_status !=', 'confirmed')
            ->where('status', '0')
            ->from('admin_event_history')
            ->count_all_results();

        // Get status changes 
        $event['event_requests'] = $this->db
            ->select('event_sid')
            ->where('event_sid', $event_sid)
            ->from('admin_event_history')
            ->count_all_results();

        // Get reminder email history 
        $event['event_reminder_email_history'] = $this->db
            ->select('event_sid')
            ->where('event_sid', $event_sid)
            ->from('admin_event_reminder_email_history')
            ->count_all_results();
        return $event;
    }


    /**
     * Get event details
     * Created on: 20-06-2019
     * 
     * @param $event_sid Integer
     * @param $exec Bool Optional
     * 
     * @return Array|Bool
     */
    function get_old_event_details($event_sid, $exec = FALSE)
    {
        $this->db
            ->select('*')
            ->from('admin_events')
            ->where('admin_events.sid', $event_sid);

        $result = $this->db->get();
        $event = $result->row_array();
        $result = $result->free_result();
        $tmp = array();
        // get external detail
        if (in_array($event['event_category'], array('meeting', 'training-session', 'other'))) {
            $result = $this->db
                ->select('external_participant_name, external_participant_email, show_email')
                ->where('event_sid', $event_sid)
                ->from('admin_event_extra_participants')
                ->get();
            $tmp = $result->result_array();
            $result->free_result();
        }

        // Get external participants
        $event['external_participants'] = $tmp;

        return $event;
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
    function get_reminder_email_history($event_sid, $page, $limit = 10, $exec = FALSE)
    {
        //
        $start_limit = $page == 1 ? 0 : ((($page * $limit) - $limit));
        $this->db
            ->select(
                '
            email_address as user_email,
            CONCAT(UCASE(SUBSTRING(user_type, 1, 1)),SUBSTRING(user_type, 2)) as user_type,
            user_name,
            date_format(created_at, "%Y-%m-%d %H:%i:%s") as created_at',
                false
            )
            ->from('admin_event_reminder_email_history')
            ->where('event_sid', $event_sid)
            ->order_by('created_at', 'DESC')
            ->limit($limit, $start_limit);
        //
        if ($exec) return $this->db->get_compiled_select();
        //
        $result = $this->db->get();
        $result_arr = $result->result_array();
        $result = $result->free_result();
        $nr = array();
        if (sizeof($result_arr)) {
            foreach ($result_arr as $k0 => $v0) {
                $nr[$v0['created_at']][] = array('user_name' => ucwords($v0['user_name'] == NULL ? '-' : $v0['user_name']), 'user_email' => $v0['user_email'], 'user_type' => $v0['user_type']);
            }
        }
        // Only execute when requested page
        // value is one
        if ($page == 1) {
            return array(
                // 'History' => $result_arr,
                'History' => $nr,
                'Count' => $this->db
                    ->select('event_sid')
                    ->from('admin_event_reminder_email_history')
                    ->where('event_sid', $event_sid)
                    ->count_all_results()

            );
        }
        return $nr;
    }


    /**
     * Get event status change requests history
     * Added on: 24-06-2019
     *
     * @param $event_sid Integer
     * @param $limit Integer
     * @param $page Integer
     * @param $exec Bool Optional
     *
     * @return Array
     */
    function get_event_availablity_requests($event_sid, $page, $limit = 10, $exec = FALSE)
    {
        //
        $start_limit = $page == 1 ? 0 : ((($page * $limit) - $limit));
        $this->db
            ->select(
                '
            user_name,
            user_email,
            reason, 
            event_start_time as start_time, 
            event_end_time as end_time,
            user_sid,
            IF(event_status = "cannotattend", "Cannot Attend", CONCAT(UCASE(SUBSTRING(event_status, 1, 1)),SUBSTRING(event_status, 2))) as event_status,
            CONCAT(UCASE(SUBSTRING(user_type, 1, 1)),SUBSTRING(user_type, 2)) as user_type,
            date_format(event_date, "%m-%d-%Y") as date,
            date_format(created_at, "%Y-%m-%d %H:%i:%s") as created_at',
                false
            )
            ->from('admin_event_history')
            ->where('event_sid', $event_sid)
            ->order_by('created_at', 'DESC')
            ->limit($limit, $start_limit);
        //
        if ($exec) return $this->db->get_compiled_select();
        //
        $result = $this->db->get();
        $result_arr = $result->result_array();
        $result = $result->free_result();
        //
        if (!sizeof($result_arr)) return false;
        //
        // foreach($result_arr as $k0 => $v0){
        //     $result_arr[$k0]['reason'] = $v0['reason'] === NULL ? '-' : $v0['reason'];
        //     $result_arr[$k0]['date']   = $v0['date'] === NULL ? '-' : $v0['date'];
        //     $result_arr[$k0]['start_time'] = $v0['start_time'] === NULL ? '-' : $v0['start_time'];
        //     $result_arr[$k0]['end_time']   = $v0['end_time'] === NULL ? '-' : $v0['end_time'];
        //     //
        //     switch ($v0['user_type']) {
        //         case 'Applicant':
        //             $result = $this->db
        //             ->select('concat( portal_job_applications.first_name, " ", portal_job_applications.last_name ) as user_name')
        //             ->from('portal_job_applications')
        //             ->where('sid', $v0['user_sid'])
        //             ->get();
        //             $result_arr[$k0]['user_name'] = $result->row_array()['user_name'];
        //             $result = $result->free_result();
        //         break;
        //         case 'Employee':
        //             $result = $this->db
        //             ->select('concat(first_name," ",last_name) as user_name')
        //             ->from('users')
        //             ->where('sid', $v0['user_sid'])
        //             ->get();
        //             $result_arr[$k0]['user_name'] = $result->row_array()['user_name'];
        //             $result = $result->free_result();
        //         break;
        //         case 'Interviewer':
        //             $result = $this->db
        //             ->select('concat(first_name," ",last_name, " (", (
        //                     IF(is_executive_admin = 1, "Executive Admin", access_level)
        //                 ) ,")") as user_name', false)
        //             ->where('sid', $v0['user_sid'])
        //             ->from('users')
        //             ->get();
        //             $result_arr[$k0]['user_name'] = $result->row_array()['user_name'];
        //             $result = $result->free_result();
        //             $result_arr[$k0]['user_type'] = 'Participants';
        //         break;
        //         case 'Extrainterviewer':
        //             $result = $this->db
        //             ->select('name as user_name')
        //             ->where('sid', $v0['user_sid'])
        //             ->from('portal_schedule_event_external_participants')
        //             ->get();

        //             $result_arr[$k0]['user_name'] = $result->row_array()['user_name'];
        //             $result = $result->free_result();
        //             $result_arr[$k0]['user_type'] = 'Non-employee Participants';
        //         break;
        //         case 'Personal':
        //             $result = $this->db
        //             ->select('CONCAT(UCASE(SUBSTRING(users_name, 1, 1)),SUBSTRING(users_name, 2)) as user_name')
        //             ->select('category')
        //             ->where('sid', $v0['user_sid'])
        //             ->from('portal_schedule_event')
        //             ->get();

        //             $result_arr[$k0]['user_name'] = $result->row_array()['user_name'];
        //             $result = $result->free_result();
        //         break;
        //     }
        // }
        // Only excute when requested page
        // value is one
        if ($page == 1) {
            return array(
                'History' => $result_arr,
                'Count' => $this->db
                    ->select('event_sid')
                    ->from('admin_event_history')
                    ->where('event_sid', $event_sid)
                    ->count_all_results()

            );
        }

        return $result_arr;
    }


    /**
     * Check for event history limit
     * Added at: 26-06-2019
     *
     * @param $event_sid Integer
     * @param $user_sid Integer
     *
     * @return Integer
     */
    function check_event_history_limit($event_sid, $user_sid)
    {
        return $this->db
            ->select('event_sid')
            ->from('admin_event_history')
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
     * @param $str Bool Optional
     *
     * @return String
     */
    function get_event_column_by_event_id($event_sid, $column = 'event_date', $str = FALSE)
    {
        $this->db
            ->select($column)
            ->from('admin_events')
            ->where('admin_events.sid', $event_sid);
        //
        $result = $this->db->get();

        $column_name = $result->row_array()[$column];
        $result = $result->free_result();

        if ($str)
            return strtotime($column_name . ' 23:59:59');
        return $column_name;
    }

    /**
     * Fetch today events
     * Created on: 25-06-2019
     *
     * @return Array|Bool
     */
    function fetch_all_today_events()
    {
        $this->db->select('*')
            ->from('admin_events')
            ->where('reminder_sent_flag', 0)
            ->where('reminder_check', 1)
            ->where('event_date', date("Y-m-d"))
            ->order_by('sid', 'desc');
        //
        $result = $this->db->get();
        $result_arr = $result->result_array();
        $result->free_result();
        //
        return sizeof($result_arr) ? $result_arr : false;
    }

    /**
     * Get organic job count
     * Created on: 16-09-2019
     * 
     * @uses getAllActiveCompanies
     * 
     * @return Integer
     */
    function getOrganicJobCount()
    {
        //  
        $product_sid = array(1, 21, 2);
        $result = $this->db
            ->select('portal_job_listings.sid')
            ->from('jobs_to_feed')
            ->where_in('product_sid', $product_sid)
            ->where('active', 1)
            ->where('expiry_date > "' . date('Y-m-d H:i:s') . '"', null)
            ->join('portal_job_listings', 'portal_job_listings.sid = jobs_to_feed. job_sid')
            ->get();
        //
        $skipJobArray = $result->result_array();
        $result = $result->free_result();
        //
        if (!sizeof($skipJobArray)) $featuredArray = array();
        else foreach ($skipJobArray as $k0 => $v0) $featuredArray[] = $v0['sid'];
        //
        $this->db
            ->select('user_sid, approval_status')
            ->from('portal_job_listings')
            ->where('active', 1)
            ->where('organic_feed', 1);
        if (sizeof($featuredArray)) $this->db->where_not_in('sid', $featuredArray);
        $result = $this->db->get();
        //
        $result_arr = $result->result_array();
        $result = $result->free_result();
        //
        if (!sizeof($result_arr)) return 0;
        //
        $organicCount = 0;
        //
        // Get all active companies
        $activeCompanies = $this->getAllActiveCompanies();
        //
        foreach ($result_arr as $k0 => $v0) {
            if (!in_array($v0['user_sid'], $activeCompanies)) continue;
            // When company is either deleted or inactive
            if ($this->getPortalDetail($v0['user_sid']) == 0) continue;
            // //
            $approvalRight = $this->getCompanyNameAndJobApproval($v0['user_sid']);
            // // // Check for approval right and job status
            if ($approvalRight['has_job_approval_rights'] != 1) {
                $organicCount++;
                continue;
            }
            if ($v0['approval_status'] != 'approved') continue;
            $organicCount++;
        }
        //
        return $organicCount;
    }

    /**
     * Get active companies
     * Created on: 16-09-2019
     * 
     * @return Integer
     */
    private function getAllActiveCompanies()
    {
        $result =
            $this->db
            ->select('sid')
            ->from('users')
            ->where('parent_sid', 0)
            ->where('career_site_listings_only', 0)
            ->where('active', 1)
            ->group_start()
            ->where('expiry_date > "2016-04-20 13:26:27"', null)
            ->or_where('expiry_date IS NULL ', null)
            ->group_end()
            ->get();
        //
        $result_arr = $result->result_array();
        $result     = $result->free_result();
        //
        return array_column($result_arr, 'sid');
    }


    private function getPortalDetail($companySid)
    {
        return $this->db
            ->from('portal_employer')
            ->where('user_sid', $companySid)
            ->count_all_results();
    }

    private function getCompanyNameAndJobApproval($companySid)
    {
        $result =
            $this->db
            ->select('has_job_approval_rights')
            ->where('sid', $companySid)
            ->from('users')
            ->get();
        //
        $result_arr = $result->row_array();
        $result = $result->free_result();
        //
        return $result_arr;
    }

    function get_total_job_applications_week()
    {
        $dto = new DateTime();
        $dto->setISODate(date('Y', strtotime('now')), date('W', strtotime('now')));
        $week_start = $dto->format('Y-m-d');
        $dto->modify('+6 days');
        $week_end = $dto->format('Y-m-d');
        //
        $start_date = $week_start . ' 00:00:01';
        $end_date  = $week_end . ' 23:59:59';
        //
        $this->db->where('applicant_type', 'Applicant');
        // $this->db->where('date_format(date_applied,"%Y-%u") = "'.( date('Y-W', strtotime('now')) ).'"', null);
        $this->db->where('date_applied BETWEEN "' . $start_date . '" and "' . $end_date . '"');
        $this->db->from('portal_applicant_jobs_list');
        return $this->db->count_all_results();
    }

    function get_total_job_applications_month()
    {
        $start_month = date('Y-m-01') . ' 00:00:01';
        $end_month  = date('Y-m-t') . ' 23:59:59';
        //
        $this->db->where('applicant_type', 'Applicant');
        // $this->db->where('date_format(date_applied,"%Y-%m") = "'.( date('Y-m', strtotime('now')) ).'"', null);
        $this->db->where('date_applied BETWEEN "' . $start_month . '" and "' . $end_month . '"');
        $this->db->from('portal_applicant_jobs_list');
        return $this->db->count_all_results();
    }

    function get_total_job_applications_year()
    {
        $start_year = date('Y-01-01') . ' 00:00:01';
        $end_year  = date('Y-12-31') . ' 23:59:59';
        //
        $this->db->where('applicant_type', 'Applicant');
        // $this->db->where('date_format(date_applied,"%Y") = "'.( date('Y', strtotime('now')) ).'"', null);
        $this->db->where('date_applied BETWEEN "' . $start_year . '" and "' . $end_year . '"');
        $this->db->from('portal_applicant_jobs_list');
        return $this->db->count_all_results();
    }

    function get_total_job_applications_today()
    {
        $start_date = date('Y-m-d') . ' 00:00:01';
        $end_date  = date('Y-m-d') . ' 23:59:59';
        //
        $this->db->where('applicant_type', 'Applicant');
        // $this->db->where('date_format(date_applied,"%Y-%m-%d") = "'.( date('Y-m-d', strtotime('now')) ).'"', null);
        $this->db->where('date_applied BETWEEN "' . $start_date . '" and "' . $end_date . '"');
        $this->db->from('portal_applicant_jobs_list');
        return $this->db->count_all_results();
    }

    function get_total_job_applications_all()
    {
        $this->db->where('applicant_type', 'Applicant');
        $this->db->from('portal_applicant_jobs_list');
        return $this->db->count_all_results();
    }


    //
    public function get_Scheduled_events(
        $calendarStartDate,
        $calendarEndDate
    ) {
        //
        $this->db
            ->select("
            free_demo_requests_schedules.sid,
            free_demo_requests_schedules.schedule_datetime,
            free_demo_requests_schedules.schedule_type,
            free_demo_requests.job_role,
            free_demo_requests_schedules.schedule_status
        ");
        //
        $this->db
            ->where("free_demo_requests_schedules.schedule_datetime >= ", $calendarStartDate)
            ->where("free_demo_requests_schedules.schedule_datetime <= ", $calendarEndDate);
        $this->db->join('free_demo_requests', 'free_demo_requests.sid = free_demo_requests_schedules.user_sid', 'left');
        //
        $records = $this->db
            ->get("free_demo_requests_schedules")
            ->result_array();

        foreach ($records as $key => $val) {
            $records[$key]['title'] = $val['job_role'];
            $records[$key]['start'] = $val['schedule_datetime'];
            $records[$key]['end'] = $val['schedule_datetime'];
            $records[$key]['color'] = '#af4200';
            $records[$key]['status'] = $val['schedule_status'];
            $records[$key]['type'] = $val['schedule_type'];
            $records[$key]['scheduled'] = 1;
        }
        return $records;
    }
}
