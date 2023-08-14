<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Course_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function checkEmployeeCourse($companyId, $employeeId, $courseId)
    {
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

    public function getCourseInfo($sid)
    {
        $this->db->select('course_title, course_content, course_type, course_questions, Imsmanifist_json');
        $this->db->where('sid', $sid);
        $result = $this->db->get('lms_default_courses')->row_array();
        return $result;
    }

    public function getCMIObject($courseId, $employeeId, $companyId)
    {
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

    public function moveCourseHistory($courseId, $employeeId, $companyId)
    {
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

    /***
     * get employee pending course count
     *
     * @param int    $companyId
     * @param int    $employeeId
     * @return array
     */
    public function getEmployeePendingCourseCount(
        int $companyId,
        int $employeeId
    ): int {
        // get employee job title id
        $jobTitleId = $this->db
            ->select('portal_job_title_templates.sid')
            ->from('users')
            ->join('portal_job_title_templates', 'users.job_title = portal_job_title_templates.title', 'title')
            ->where('users.sid', $employeeId)
            ->get()
            ->row_array();
        // when no job title is assigned
        if (!$jobTitleId) {
            return 0;
        }
        //
        $jobTitleId = $jobTitleId['sid'];
        // get the courses
        $assignedCourses = $this->db
            ->select('lms_default_courses.sid')
            ->from('lms_default_courses')
            ->join(
                'lms_default_courses_job_titles',
                'lms_default_courses_job_titles.lms_default_courses_sid = lms_default_courses.sid',
                'inner'
            )
            ->where('lms_default_courses.company_sid', $companyId)
            ->group_start()
            ->where('lms_default_courses_job_titles.job_title_id', -1)
            ->or_where('lms_default_courses_job_titles.job_title_id', $jobTitleId)
            ->group_end()
            ->get()
            ->result_array();
        // if no courses are found
        if (!$assignedCourses) {
            return 0;
        }
        //
        $returnCount = 0;
        // loop through courses
        foreach ($assignedCourses as $course) {
            // get course answer
            $courseStatus = $this->db
                ->select('course_status')
                ->from('lms_employee_course')
                ->where('lms_employee_course.course_sid', $course['course_sid'])
                ->where('lms_employee_course.employee_sid', $employeeId)
                ->get()
                ->row_array();
            //
            if (!$courseStatus) {
                $returnCount++;
            } else {
                //
                if ($courseStatus['course_status'] === 'pending') {
                    $returnCount++;
                }
            }
        }

        return $returnCount;
    }
}
