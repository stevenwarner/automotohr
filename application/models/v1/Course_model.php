<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Course_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    public function checkEmployeeCourse ($companyId, $employeeId, $courseId) {
        //
        $this->db->where('company_sid', $companyId);
        $this->db->where('employee_sid', $employeeId);
        $this->db->where('course_sid', $courseId);
        //
        $this->db->from('lms_employee_course');
        //
        $count = $this->db->count_all_results();
        //
        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getCourseInfo ($sid) {
        $this->db->select('course_title, course_content, course_type, course_questions, Imsmanifist_json');
        $this->db->where('sid', $sid);
        $result = $this->db->get('lms_default_courses')->row_array();
        return $result;
    }

    public function getCMIObject ($courseId, $employeeId, $companyId) {
        $this->db->select('course_answer_json');
        $this->db->where('company_sid', $companyId);
        $this->db->where('employee_sid', $employeeId);
        $this->db->where('course_sid', $courseId);
        $result = $this->db->get('lms_employee_course')->row_array();
        //
        if (!empty($result)) {
            return json_decode($result['course_answer_json'], true);
        } else {
            return array();
        }
    }

    public function moveCourseHistory ($courseId, $employeeId, $companyId) {
        //
        $this->db->select('*');
        $this->db->where('company_sid', $companyId);
        $this->db->where('employee_sid', $employeeId);
        $this->db->where('course_sid', $courseId);
        //
        $result = $this->db->get('lms_employee_course')->row_array();
        //
        if (!empty($result)) {
           if (!empty($result['course_status']) && $result['course_status'] == "passed") {
            //
                $rowId = $result['sid'];
                unset($result['sid']);
                //
                $this->db->insert('lms_employee_course_history', $result);
                //
                $dataToUpdate = array();
                $dataToUpdate['lesson_status'] = 'incomplete';
                $dataToUpdate['course_status'] = '';
                $dataToUpdate['course_answer_json'] = NULL;
                //
                $this->db->where('sid', $rowId)->update('lms_employee_course', $dataToUpdate);
           }
        }
        //
        return true;
    }
   
}