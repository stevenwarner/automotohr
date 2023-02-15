<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Company model
 * 
 * Holds all the company interactions
 * 
 * @author  AutomotoHR <www.automotohr.com>
 * @author  Mubashir Ahmed <mubashar@automotohr.com>
 * @version 1.0 
 * 
 */
class Course_model extends CI_Model {

    /**
     * Entry point
     */
    function __construct() {
        // Inherit parent class properties and methods
        parent::__construct();
    }


    /**
     * Get all active companies
     * 
     * @param array  $columns
     * @param array  $where
     * @param string $method
     * @param array  $orderBy
     * @return array
     */
    public function getAllCompanies(
        $columns = ['*'],
        $where = [],
        $method = 'result_array',
        $orderBy = ['CompanyName', 'ASC']
    ){
        // Set the default where clause
        if(!$where){
            $where['parent_sid'] = 0;
            $where['active'] = 
            $where['is_paid'] = 1;
            $where['career_page_type'] = 'standard_career_site';
        }
        //
        $this->db
        ->select($columns)
        ->where($where)
        ->from('users')
        ->order_by($orderBy[0], $orderBy[1]);
        // Execute the query
        $obj = $this->db->get();
        // Get he data
        $results = $obj->$method();
        // Free up the memory
        $obj = $obj->free_result();
        //
        return $results ?: [];
    }

    /**
     * Add company data to table
     * 
     * @param string $table
     * @param array $insertArray
     * 
     * @return int
     */
    public function addData(
        string $table, 
        array $insertArray
    ){
        //
        $this->db->insert($table, $insertArray);
        //
        return $this->db->insert_id();
    }

    public function getAllCourses ($companySid) {
        $this->db->select('
            sid, 
            creator_sid,
            title,
            type,
            description,
            created_at
        ');
        $this->db->where('company_sid', $companySid);
        $records_obj = $this->db->get('lms_courses');
        //
        if (!empty($records_obj)) {
            $data = $records_obj->result_array();
            $records_obj->free_result();
            //
            $result_array = array();
            //
            foreach ($data as $row) {
                
                $description = strlen($row['description']) > 100 ? substr($row['description'],0,100)." ..." : $row['description'];
                //
                $type = $row['title'] == "upload" ? "Scorm" : "Manual";
                //
                $employees = $this->countCourseEmployees($row['sid']);
                //
                array_push($result_array, array(
                    "courseID" => $row['sid'],
                    "created_by" => getUserNameBySID($row['creator_sid']),
                    "created_on" => formatDateToDB($row['created_at'], DB_DATE_WITH_TIME, DATE),
                    "type" => $type,
                    "title" => $row['title'],
                    "description" => $description,
                    "employees" => $employees
                ));
            }
            //
            return $result_array;
        } else {
            return array();
        } 
    }

    public function getAllActiveEmployees (
        $companySid, 
        $departmentId, 
        $includedIds, 
        $excludedIds, 
        $employeeType, 
        $jobTitles 
    ) {
        $this->db->select('
            sid, 
            first_name, 
            middle_name, 
            last_name, 
            access_level, 
            timezone, 
            access_level_plus, 
            is_executive_admin, 
            pay_plan_flag, 
            department_sid, 
            profile_picture'
        );
        
        $this->db->where('is_executive_admin', 0);
        $this->db->where('parent_sid', $companySid);
        $this->db->where('active', 1);
        $this->db->where('terminated_status', 0);
        //
        if (!empty($departmentId) && $departmentId !== "all") {
            $this->db->where_in('department_sid', $departmentId);
        }
        //
        if (!empty($includedIds) && $includedIds !== "all") {
            $this->db->where_in('sid', $includedIds);
        }
        //
        if (!empty($excludedIds) && $excludedIds !== "all" && $excludedIds !== "no") {
            $this->db->where_not_in('sid', $excludedIds);
        }
        //
        if (!empty($employeeType) && $employeeType !== "all") {
            $this->db->where('employee_type', $employeeType);
        }
        //
        if (!empty($jobTitles) && $jobTitles !== "all") {
            $this->db->where_in('job_title', str_replace(',', '","', $jobTitles));
        }
        //
        $records_obj = $this->db->get('users');
        //
        if (!empty($records_obj)) {
            $data = $records_obj->result_array();
            $records_obj->free_result();
            return $data;
        } else {
            return array();
        }
        //
    }

    public function getAllDepartments ($companySid) {
        //
        $this->db->select('
            sid, 
            name
        ');
        $this->db->where('company_sid', $companySid);
        $records_obj = $this->db->get('departments_management');
        //
        if (!empty($records_obj)) {
            $data = $records_obj->result_array();
            $records_obj->free_result();
            return $data;
        } else {
            return array();
        }        
    }

    public function getAllJobTitles ($companySid) {
        //
        $this->db->select('
            distinct(job_title)
        ');
        $this->db->where('parent_sid', $companySid);
        $records_obj = $this->db->get('users');
        //
        if (!empty($records_obj)) {
            $data = $records_obj->result_array();
            $records_obj->free_result();
            //
            $result_array = array();
            //
            foreach ($data as $row) {
                if (!empty($row['job_title'])) {
                    $jobTitle = strtolower($row['job_title']);
                    $value =  ucwords($jobTitle);
                    $key = str_replace(" ", "_", $jobTitle);
                    array_push($result_array, array(
                        "key" => $key,
                        "value" => $value
                    ));
                }
            }
            //
            return $result_array;
        } else {
            return array();
        }        
    }

    public function getAllAssignedEmployees ($companySid, $courseSid) {
        $this->db->select('
            employee_sid
        ');
        $this->db->where('company_sid', $companySid);
        $this->db->where('course_sid', $courseSid);
        $this->db->where('status', 1);
        $records_obj = $this->db->get('lms_assigned_employees');
        //
        if (!empty($records_obj)) {
            $data = $records_obj->result_array();
            $records_obj->free_result();
            return $data;
        } else {
            return array();
        } 
    }

    function deleteAssignedEmployees($companySid, $courseSid) {
        $this->db->where('company_sid', $companySid);
        $this->db->where('course_sid', $courseSid);
        $this->db->delete('lms_assigned_employees');
    }

    function getChapterVideo ($companySid, $courseSid, $chapterSid) {
        $this->db->select('
            chapter_video
        ');
        $this->db->where('company_sid', $companySid);
        $this->db->where('course_sid', $courseSid);
        $this->db->where('sid', $chapterSid);
        $record_obj = $this->db->get('lms_manual_course');
        //
        if (!empty($record_obj)) {
            $data = $record_obj->row_array();
            $record_obj->free_result();
            return base_url($data['chapter_video']);
        } else {
            return '';
        } 
    }

    function getChapterQuestions ($companySid, $courseSid, $chapterSid) {
        $this->db->select('
            sid,
            question,
            answer,
            type,
            sort_order
        ');
        $this->db->where('company_sid', $companySid);
        $this->db->where('course_sid', $courseSid);
        $this->db->where('chapter_sid', $chapterSid);
        $this->db->order_by('sort_order', 'desc');
        $records_obj = $this->db->get('lms_manual_course_question');
        //
        if (!empty($records_obj)) {
            $data = $records_obj->result_array();
            $records_obj->free_result();
            return $data;
        } else {
            return array();
        }
    }

    function updateQuestion ($data_to_update, $sid) {
        $this->db->where('sid', $sid);
        $this->db->update('lms_manual_course_question', $data_to_update);
    }

    function getQuestionInfo ($sid) {
        //
        $this->db->select('
            question,
            answer,
            type
        ');
        //
        $this->db->where('sid', $sid);
        $record_obj = $this->db->get('lms_manual_course_question');
        //
        if (!empty($record_obj)) {
            $data = $record_obj->row_array();
            $record_obj->free_result();
            return $data;
        } else {
            return array();
        } 
    }

    function deleteCourseQuestion ($sid) {
        $this->db->where('sid', $sid);
        $this->db->delete('lms_manual_course_question');
    }

    function getManualCourseInfo ($courseSid) {
        $this->db->select('
            sid,
            creator_sid,
            chapter_title,
            chapter_description,
            chapter_video,
            count_question,
            created_at
        ');
        $this->db->where('course_sid', $courseSid);
        $records_obj = $this->db->get('lms_manual_course');
        //
        if (!empty($records_obj)) {
            $data = $records_obj->result_array();
            $records_obj->free_result();
            //
            $result_array = array();
            //
            foreach ($data as $row) {
                
                $description = strlen($row['chapter_description']) > 100 ? substr($row['chapter_description'],0,100)." ..." : $row['chapter_description'];
                //
                array_push($result_array, array(
                    "chapterID" => $row['sid'],
                    "created_by" => getUserNameBySID($row['creator_sid']),
                    "created_on" => formatDateToDB($row['created_at'], DB_DATE_WITH_TIME, DATE),
                    "videoURL" => base_url($row['chapter_video']),
                    "question_count" => $row['count_question'],
                    "title" => $row['chapter_title'],
                    "description" => $description,
                ));
            }
            //
            return $result_array;
            //
        } else {
            return array();
        }   
    }

    function countChapterQuestion ($courseSid, $chapterSid) {
        return $this->db
            ->where("course_sid", $courseSid)
            ->where("chapter_sid", $chapterSid)
            ->count_all_results("lms_manual_course_question");
    }

    function countCourseEmployees ($courseSid) {
        return $this->db
            ->where("course_sid", $courseSid)
            ->count_all_results("lms_assigned_employees");
    }

    function updateChapter ($data_to_update, $sid) {
        $this->db->where('sid', $sid);
        $this->db->update('lms_manual_course', $data_to_update);
    }

    function updateCourse ($data_to_update, $sid) {
        $this->db->where('sid', $sid);
        $this->db->update('lms_courses', $data_to_update);
    }
}
