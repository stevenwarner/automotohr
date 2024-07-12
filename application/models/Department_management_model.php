<?php

class Department_management_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function fetch_all_company_employees($company_sid)
    {
        $this->db->select('sid,first_name, last_name, email, job_title, access_level, access_level_plus, pay_plan_flag, is_executive_admin');
        $this->db->where('parent_sid', $company_sid);
        $this->db->where('active', 1);
        $this->db->where('terminated_status', 0);
        $this->db->order_by(SORT_COLUMN, SORT_ORDER);
        $result = $this->db->get('users')->result_array();
        return $result;
    }


    function fetch_all_company_employees_only($company_sid)
    {
        $this->db->select('sid,first_name, last_name, email, job_title, access_level, access_level_plus, pay_plan_flag, is_executive_admin');
        $this->db->where('parent_sid', $company_sid);
        $this->db->where('active', 1);
        $this->db->where('is_executive_admin', 0);
        $this->db->where('terminated_status', 0);
        $this->db->order_by(SORT_COLUMN, SORT_ORDER);
        $result = $this->db->get('users')->result_array();
        return $result;
    }

    function get_all_departments($company_sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('is_deleted', 0);
        $this->db->order_by('sort_order', 'asc');
        $record_obj = $this->db->get('departments_management');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }

    function get_department($department_sid)
    {
        $this->db->select('*');
        $this->db->where('sid', $department_sid);
        $record_obj = $this->db->get('departments_management');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function get_department_name($department_sid)
    {
        $this->db->select('name');
        $this->db->where('sid', $department_sid);
        $record_obj = $this->db->get('departments_management');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0]['name'];
        } else {
            return array();
        }
    }

    function get_team_name($team_sid)
    {
        $this->db->select('name');
        $this->db->where('sid', $team_sid);
        $record_obj = $this->db->get('departments_team_management');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0]['name'];
        } else {
            return array();
        }
    }

    function insert_department($data_to_insert)
    {
        $this->db->insert('departments_management', $data_to_insert);
        return $this->db->insert_id();
    }

    function update_department($department_sid, $data_to_update)
    {
        $this->db->where('sid', $department_sid);
        $this->db->update('departments_management', $data_to_update);
    }


    function deleteDepartment($department_sid)
    {
        $this->deleteDepartmentTeams($department_sid);
        $this->deleteDepartmentEmployeesByDepartmentId($department_sid);
        $this->deleteDepartmentEmployeesFromUsers($department_sid, 'department');
    }

    function deleteDepartmentTeams($departmentID)
    {
        //
        $employeeId = checkAndGetSession("employee")["sid"];
        //
        $data_to_update = array();
        $data_to_update['is_deleted'] = 1;
        $data_to_update['deleted_by_sid'] = $employeeId;
        $data_to_update['deleted_date'] = date('Y-m-d H:i:s');
        //
        $this->db->where('department_sid', $departmentID);
        $this->db->update('departments_team_management', $data_to_update);
    }

    function deleteDepartmentEmployeesByDepartmentId($departmentID)
    {
        $this->db->where('department_sid', $departmentID);
        $this->db->delete('departments_employee_2_team');
    }

    function deleteDepartmentEmployeesByTeamId($teamID)
    {
        $this->db->where('team_sid', $teamID);
        $this->db->delete('departments_employee_2_team');
    }

    function deleteDepartmentEmployeesFromUsers($sid, $type)
    {
        $data_to_update = array();
        $data_to_update['team_sid'] = 0;
        //
        if ($type == 'department') {
            $data_to_update['department_sid'] = 0;
            $this->db->where('department_sid', $sid);
        } else {
            $this->db->where('team_sid', $sid);
        }
        //
        $this->db->update('users', $data_to_update);
    }

    function get_all_department_teams($company_sid, $department_sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('department_sid', $department_sid);
        $this->db->where('is_deleted', 0);
        $this->db->order_by('sort_order', 'asc');
        $record_obj = $this->db->get('departments_team_management');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }

    function get_team($team_sid)
    {
        $this->db->select('*');
        $this->db->where('sid', $team_sid);
        $record_obj = $this->db->get('departments_team_management');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function insert_team($data_to_insert)
    {
        $this->db->insert('departments_team_management', $data_to_insert);
        return $this->db->insert_id();
    }

    function update_team($team_sid, $data_to_update)
    {
        $this->db->where('sid', $team_sid);
        $this->db->update('departments_team_management', $data_to_update);
    }

    function deleteTeam($team_sid)
    {
        $this->deleteDepartmentEmployeesByTeamId($team_sid);
        $this->deleteDepartmentEmployeesFromUsers($team_sid, 'team');
    }

    function delete_employees_from_team($department_sid, $team_sid)
    {
        $this->db->where('department_sid', $department_sid);
        $this->db->where('team_sid', $team_sid);
        $this->db->delete('departments_employee_2_team');
    }

    function assign_employee_to_team($data_to_insert)
    {
        $this->db->insert('departments_employee_2_team', $data_to_insert);
    }
    function updateDepartmentTeamForEmployee($departmentId, $teamId, $employeeId)
    {
        $this->db
            ->where('sid', $employeeId)
            ->update('users', array(
                'department_sid' => $departmentId,
                'team_sid' => $teamId
            ));
    }

    function get_all_employees_to_team($department_sid, $team_sid)
    {
        $this->db->select('employee_sid');
        $this->db->where('department_sid', $department_sid);
        $this->db->where('team_sid', $team_sid);
        $record_obj = $this->db->get('departments_employee_2_team');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }

    function get_all_company_departments($company_sid)
    {
        $this->db->select('sid as department_sid, name');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('is_deleted', 0);
        $this->db->where('status', 1);
        $this->db->order_by('sort_order', 'asc');
        $record_obj = $this->db->get('departments_management');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }

    function get_all_company_teams($company_sid)
    {
        $this->db->select('departments_team_management.sid as team_sid, departments_team_management.name, departments_management.sid as department_sid');
        $this->db->where('departments_team_management.company_sid', $company_sid);
        $this->db->where('departments_team_management.is_deleted', 0);
        $this->db->where('departments_team_management.status', 1);
        $this->db->where('departments_management.is_deleted', 0);
        $this->db->join('departments_management', 'departments_management.sid = departments_team_management.department_sid', 'inner');
        // $this->db->order_by('departments_management.sort_order', 'asc');
        $record_obj = $this->db->get('departments_team_management');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }

    function remove_employee_from_team($department_sid, $team_sid, $employee_sid)
    {
        $this->db->where('department_sid', $department_sid);
        $this->db->where('team_sid', $team_sid);
        $this->db->where('employee_sid', $employee_sid);
        $this->db->delete('departments_employee_2_team');
    }

    function get_department_name_and_supervisor($department_sid)
    {
        $this->db->select('supervisor');
        $this->db->where('sid', $department_sid);
        $record_obj = $this->db->get('departments_management');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            $return_data = explode(',', $record_arr[0]['supervisor']);
            return $return_data;
        } else {
            return array();
        }
    }

    function get_team_name_and_teamlead($team_sid)
    {
        $this->db->select('team_lead');
        $this->db->where('sid', $team_sid);
        $record_obj = $this->db->get('departments_team_management');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            $return_data = explode(',', $record_arr[0]['team_lead']);
            return $return_data;
        } else {
            return array();
        }
    }

    function check_employee_already_exist($company_sid, $department_sid, $approver, $employer_sid, $is_department = 1)
    {
        //
        if (empty($department_sid) || $department_sid == null) {
            return [];
        }
        $this->db->where('company_sid', $company_sid);
        $this->db->where('employee_sid', $approver);
        $this->db->where('is_department', $is_department);
        $this->db->where("FIND_IN_SET({$department_sid}, department_sid) > 0", NULL, NULL);

        if (!$this->db->count_all_results('timeoff_approvers')) {
            $data_to_insert = array();
            $data_to_insert['company_sid'] = $company_sid;
            $data_to_insert['employee_sid'] = $approver;
            $data_to_insert['department_sid'] = $department_sid;
            $data_to_insert['status'] = 1;
            $data_to_insert['creator_sid'] = $employer_sid;
            $data_to_insert['approver_percentage'] = 0;
            $data_to_insert['is_archived'] = 0;
            $data_to_insert['is_department'] = $is_department;
            $data_to_insert['sort_order'] = 1;

            $this->db->insert('timeoff_approvers', $data_to_insert);
        }
    }


    function getApprovers($company_sid, $department_sid, $is_department = 1)
    {
        //
        if (empty($department_sid) || $department_sid == null) {
            return [];
        }
        $this->db->select('employee_sid');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('is_archived', 0);
        $this->db->where('is_department', $is_department);
        $this->db->where("FIND_IN_SET({$department_sid}, department_sid) > 0", NULL, NULL);
        $record_obj = $this->db->get('timeoff_approvers');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        //
        return array_column($record_arr, 'employee_sid');
    }

    function archive_all_removed_approvers($company_sid, $department_sid, $approvers, $is_department = 1)
    {
        //
        //
        if (empty($department_sid) || $department_sid == null) {
            return [];
        }
        $this->db->select('sid, department_sid');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('is_department', $is_department);
        $this->db->where("FIND_IN_SET({$department_sid}, department_sid) > 0", NULL, NULL);
        $this->db->where_not_in('employee_sid', $approvers);
        $record_obj = $this->db->get('timeoff_approvers');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        //
        if (empty($record_arr)) {
            return;
        }
        //
        foreach ($record_arr as $record) {
            //
            $upd = [];
            //
            if ($record['department_sid'] == $department_sid) {
                $upd['is_archived'] = 1;
            } else {
                //
                $p1 = $department_sid . ',';
                $p2 = ',' . $department_sid;
                //
                $newDept = str_replace([$p1, $p2], '', $record['department_sid']);
                //
                $upd['department_sid'] = $newDept;
            }
            //
            $this->db
                ->where('sid', $record['sid'])
                ->update('timeoff_approvers', $upd);
        }
    }
}
