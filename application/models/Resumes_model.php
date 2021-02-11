<?php

class Resumes_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();

        //Connect to remote db
        if (base_url() == 'http://localhost/automotoCI/') {
            $this->DB_CONN = mysqli_connect('localhost', 'root', '', 'dealers_ams');
        } else {
            $this->DB_CONN = mysqli_connect(ROOT_DB_HOST, ROOT_DB_USER, ROOT_DB_PASSWORD, ROOT_DB_NAME);
        }


        if (!$this->DB_CONN) {
            $this->session->set_flashdata('message', 'Unable To Connect to Remote Database');
            //redirect('dashboard', 'refresh');
        }


//        $my_result = $this->DB_CONN->query('SELECT * FROM `listings` LIMIT 10');
//
//        if($my_result->num_rows > 0){
//
//
//            echo '<pre>';
//            while($data_rows = $my_result->fetch_assoc()) {
//                print_r($data_rows);
//                echo '<hr />';
//            }
//            exit;
//        }


    }

    private $DB_CONN = null;

    function ci_get_all_resumes($limit = null, $offset = null, $where_filters = array(), $like_filters = array(), $where_custom = null)
    {
        $table_name = 'listings_bkup';

        $this->db->select($table_name . '.*');


        if ($limit != null && $offset != null) {
            $this->db->limit($limit, $offset);
        }

        if (!empty($where_filters)) {
            foreach ($where_filters as $key => $filter) {
                $this->db->where($table_name . '.' . $key, $filter);
            }
        }

        if (!empty($like_filters)) {
            foreach ($like_filters as $key => $filter) {
                $this->db->like($table_name . '.' . $key, $filter);
            }
        }

        if ($where_custom != null) {
            $this->db->where($where_custom);
        }


        $this->db->order_by($table_name . '.' . 'sid', 'DESC');


        $data_rows = $this->db->get($table_name)->result_array();

        //echo $this->db->last_query();

        foreach ($data_rows as $key => $data_row) {
            //$data_rows[$key]['complex'] = unserialize($data_row['complex']);

            $user_sid = $data_row['user_sid'];

            $this->db->select('*');
            $this->db->where('sid', $user_sid);
            $user_info = $this->db->get('ams_users')->result_array();

            if (!empty($user_info)) {
                $data_rows[$key]['user_info'] = $user_info[0];
            } else {
                $data_rows[$key]['user_info'] = array();
            }

        }


        return $data_rows;
    }


    function ci_get_single_resume($sid)
    {
        $this->db->select('*');
        $this->db->where('sid', $sid);
        $data_row = $this->db->get('listings_bkup')->result_array();

        if (!empty($data_row)) {
            $data_row = $data_row[0];

            $user_sid = $data_row['user_sid'];

            $this->db->select('*');
            $this->db->where('sid', $user_sid);
            $user_info = $this->db->get('ams_users')->result_array();

            if (!empty($user_info)) {
                $data_row['user_info'] = $user_info[0];
            } else {
                $data_row['user_info'] = array();
            }

            return $data_row;
        } else {
            return array();
        }
    }

    function raw_get_all_resumes($sid = 0, $limit = 0, $offset = 0, $title = '', $keywords = '', $country = 0, $state = 0, $city = '', $zipcode = 0, $posted_within_days = 0)
    {

        $my_sql_command = 'SELECT * FROM `listings` ';


        $where_syntax = '';
        $keywords_syntax = '';
        $country_syntax = '';
        $state_syntax = '';
        $zipcode_syntax = '';
        $city_syntax = '';
        $limit_syntax = '';
        $title_syntax = '';
        $order_by_syntax = '';
        $addional_checks = '';
        $posted_within_days_syntax = '';

        // active column check is required. it should be 1

        if (!empty($title)) {
            $where_syntax = ' WHERE ';

            $title = mysqli_real_escape_string($this->DB_CONN, $title);

            $title_syntax = " `Title` LIKE '%" . $title . "%' ";

        }


        if (!empty($keywords)) {
            if ($where_syntax != '') {
                $keywords_syntax = ' AND ';
            } else {
                $where_syntax = ' WHERE ';
            }

            $keywords_array = explode(' ', $keywords);

            $keywords_syntax = $keywords_syntax . ' ( ';

            foreach ($keywords_array as $key => $my_keyword) {
                $keyword = mysqli_real_escape_string($this->DB_CONN, $my_keyword);

                if ($key == 0) {
                    $keywords_syntax .= " `Keywords` LIKE '%" . $keyword . "%' ";
                } else {
                    $keywords_syntax .= " OR `Keywords` LIKE '%" . $keyword . "%' ";
                }
            }

            $keywords_syntax = $keywords_syntax . ' ) ';
        }

        if ($country > 0) {
            if ($where_syntax != '') {
                $country_syntax = ' AND ';
            } else {
                $where_syntax = ' WHERE ';
            }

            $country = mysqli_real_escape_string($this->DB_CONN, $country);

            $country_syntax .= " `Location_Country` = " . $country . " ";
        }


        if ($state > 0) {
            if ($where_syntax != '') {
                $state_syntax = ' AND ';
            } else {
                $where_syntax = ' WHERE ';
            }

            $state = mysqli_real_escape_string($this->DB_CONN, $state);

            $state_syntax .= " `Location_State` = " . $state . " ";
        }

        if ($zipcode > 0) {
            if ($where_syntax != '') {
                $zipcode_syntax = ' AND ';
            } else {
                $where_syntax = ' WHERE ';
            }

            $zipcode = mysqli_real_escape_string($this->DB_CONN, $zipcode);

            $zipcode_syntax .= " `Location_State` = " . $state . " ";
        }

        if (!empty($city)) {
            if ($where_syntax != '') {
                $city_syntax = ' AND ';
            } else {
                $where_syntax = ' WHERE ';
            }

            $city = mysqli_real_escape_string($this->DB_CONN, $city);

            $city_syntax .= " `Location_City` LIKE '%" . $city . "%' ";
        }


        if ($posted_within_days > 0) {
            $mydate = date('Y/m/d 00:00:00', date_subtract_days(date('Y/m/d'), $posted_within_days));
            if ($where_syntax != '') {
                $posted_within_days_syntax = ' AND ';
            } else {
                $where_syntax = ' WHERE ';
            }

            $posted_within_days_syntax .= " `activation_date` BETWEEN '" . $mydate . "' AND '" . date('Y/m/d 23:59:59') . "' ";
        }

        if ($where_syntax == '') {
            $addional_checks = " WHERE `access_type` = 'everyone' AND `active` = 1 AND `listing_type_sid` = 7 ";
        } else {
            $addional_checks = " AND `access_type` = 'everyone' AND `active` = 1 AND `listing_type_sid` = 7 ";
        }

        $order_by_syntax = ' ORDER BY `sid` DESC ';


        if ($limit > 0 && $offset >= 0) {
            $limit = mysqli_real_escape_string($this->DB_CONN, $limit);
            $offset = mysqli_real_escape_string($this->DB_CONN, $offset);

            $limit_syntax = " LIMIT " . $offset . "," . $limit . " ";
        }


        $where_clause = $where_syntax . $title_syntax . $keywords_syntax . $country_syntax . $state_syntax . $city_syntax . $zipcode_syntax . $posted_within_days_syntax . $addional_checks . $order_by_syntax . $limit_syntax;
        $where_clause_for_count = $where_syntax . $title_syntax . $keywords_syntax . $country_syntax . $state_syntax . $city_syntax . $zipcode_syntax . $posted_within_days_syntax . $addional_checks . $order_by_syntax;

        if ($sid == 0) {
            $my_sql_command .= $where_clause;
        } else {
            $my_sql_command .= ' WHERE `sid` = ' . mysqli_real_escape_string($this->DB_CONN, $sid);
        }
        //echo $my_sql_command;

        $result = mysqli_query($this->DB_CONN, $my_sql_command);


        $number_of_records = $this->raw_count_filtered_records($where_clause_for_count);


        $data_rows = array();

        while ($row = $result->fetch_assoc()) {
            $data_rows[] = $row;
        }


        //$data_rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

        foreach ($data_rows as $key => $data_row) {
            $user_sid = $data_row['user_sid'];

            $user_info = $this->raw_get_user_info($user_sid);

            if (!empty($user_info)) {
                $data_rows[$key]['user_info'] = $user_info;


                $profile_picture = $data_rows[$key]['user_info']['ProfilePicture'];

                if($profile_picture != '') {
                    $profile_picture_detail = $this->raw_get_profile_picture_detail($profile_picture);

                    if (!empty($profile_picture_detail)) {
                        $data_rows[$key]['ProfilePictureDetail'] = $profile_picture_detail;
                    } else {
                        $data_rows[$key]['ProfilePictureDetail'] = array();
                    }
                }else{
                    $data_rows[$key]['ProfilePictureDetail'] = array();
                }

            } else {
                $data_rows[$key]['user_info'] = array();
                $data_rows[$key]['ProfilePictureDetail'] = array();
            }


            //Get Resume File Details
            $resume_sid = $data_row['sid'];

            $resume_files = $this->raw_get_resume_files($resume_sid);

            if (!empty($user_info)) {
                $data_rows[$key]['resume_files'] = $resume_files;
            } else {
                $data_rows[$key]['resume_files'] = array();
            }




            $job_category_sid = $data_row['JobCategory'];

            if($job_category_sid != '') {
                $job_category_detail = $this->raw_get_job_category_detail($job_category_sid);

                if (!empty($job_category_detail)) {
                    $data_rows[$key]['JobCategoryDetail'] = $job_category_detail;
                } else {
                    $data_rows[$key]['JobCategoryDetail'] = array();
                }
            }else{
                $data_rows[$key]['JobCategoryDetail'] = array();
            }


            //Get Occupation Details
            $occupation_sid = $data_row['Occupations'];

            if(!empty($occupation_sid)) {

                $occupation_detail = $this->raw_get_occupation_detail($occupation_sid);

                if (!empty($occupation_detail)) {
                    $data_rows[$key]['OccupationDetail'] = $occupation_detail;
                } else {
                    $data_rows[$key]['OccupationDetail'] = array();
                }
            }else{
                $data_rows[$key]['OccupationDetail'] = array();
            }

            /*
            if($data_row['complex'] != '') {
                $data_rows[$key]['complex'] = unserialize($data_row['complex']);
            }
            */

        }

        $this->DB_CONN->close();

        return array('data_rows' => $data_rows, 'total_records' => $number_of_records);
    }

    function raw_count_filtered_records($where_clause)
    {
        $my_sql_command = ' SELECT COUNT(*) AS id FROM listings ' . $where_clause;

        $result = mysqli_query($this->DB_CONN, $my_sql_command);

        $data = mysqli_fetch_array($result, MYSQLI_ASSOC);

        $count = $data['id'];

        return $count;
    }

    function raw_get_user_info($sid)
    {
        $my_sql_command = " SELECT * FROM `users` WHERE `sid` = " . mysqli_real_escape_string($this->DB_CONN, $sid);

        $result = mysqli_query($this->DB_CONN, $my_sql_command);

        return mysqli_fetch_array($result, MYSQLI_ASSOC);
    }

    function raw_get_resume_files($resume_sid)
    {
        $my_sql_command = " SELECT * FROM `uploaded_files` WHERE  `id` IN ( 'Resume_" . $resume_sid . "' )";

        $result = mysqli_query($this->DB_CONN, $my_sql_command);

        $data_rows = array();

        while ($row = $result->fetch_assoc()) {
            $data_rows[] = $row;
        }

        //return mysqli_fetch_all($result, MYSQLI_ASSOC);

        return $data_rows;
    }

    function raw_get_profile_picture_detail($profile_picture_id)
    {
        $my_sql_command = "SELECT * FROM `uploaded_files` WHERE `id` = '" . mysqli_real_escape_string($this->DB_CONN, $profile_picture_id) . "' ";

        //echo $my_sql_command;
        $result = mysqli_query($this->DB_CONN, $my_sql_command);

        $data_rows = array();

        while ($row = $result->fetch_assoc()) {
            $data_rows[] = $row;
        }

        //return mysqli_fetch_all($result, MYSQLI_ASSOC);

        if(!empty($data_rows)){
            return $data_rows[0];
        }else{
            return array();
        }
    }

    function raw_get_job_category_detail($sid)
    {
        $my_sql_command = "SELECT * FROM `listing_field_list` WHERE `sid` IN ( " . mysqli_real_escape_string($this->DB_CONN, $sid) . " ) ";

        //echo $my_sql_command;
        $result = mysqli_query($this->DB_CONN, $my_sql_command);

        $data_rows = array();

        while ($row = $result->fetch_assoc()) {
            $data_rows[] = $row;
        }

        return $data_rows;
    }

    function raw_get_occupation_detail($sid){
        $my_sql_command = "SELECT * FROM `listing_field_tree` WHERE `sid` IN ( " . mysqli_real_escape_string($this->DB_CONN, $sid) . " ) ";


        $result = mysqli_query($this->DB_CONN, $my_sql_command);

        $data_rows = array();

        while ($row = $result->fetch_assoc()) {
            $data_rows[] = $row;
        }

        return $data_rows;
    }

    function insert_resume_in_job_application($portal_job_applications_data = array(), $portal_applicant_jobs_list_data = array())
    {
        if(!empty($portal_job_applications_data) && !empty($portal_applicant_jobs_list_data)){
            $this->db->insert('portal_job_applications', $portal_job_applications_data);

            $portal_job_applications_sid = $this->db->insert_id();
            $portal_applicant_jobs_list_data['portal_job_applications_sid'] = $portal_job_applications_sid;

            $this->db->insert('portal_applicant_jobs_list', $portal_applicant_jobs_list_data);
        }
    }



    function check_job_applicant($job_sid, $email, $company_sid = NULL) {
        if($job_sid=='company_check'){ // It checks whether this applicant has applied for any job in this company
            $this->db->select('sid');
            $this->db->where('employer_sid', $company_sid);
            $this->db->where('email', $email);
            $this->db->order_by('sid', 'desc');
            $this->db->limit(1);
            $this->db->from('portal_job_applications');
            $result = $this->db->get()->result_array();

            if(sizeof($result)>0){
                $output = $result[0]['sid'];
            } else {
                $output = 'no_record_found';
            }

            return $output;
        } else { // It checks whether this applicant has applied for this particular job
            $this->db->select('sid');
            $this->db->where('employer_sid', $company_sid);
            $this->db->where('email', $email);
            $this->db->from('portal_job_applications');
            $result = $this->db->get()->result_array();

            if(empty($result)){
                return 0;
            } else {
                $applicant_sid = $result[0]['sid'];
                $this->db->select('sid');
                $this->db->where('job_sid', $job_sid);
                $this->db->where('portal_job_applications_sid', $applicant_sid);
                $this->db->from('portal_applicant_jobs_list');
                return $this->db->get()->num_rows();
            }

        }
    }


}