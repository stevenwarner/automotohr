<?php

class Reporting_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }


    public function get_security_access_levels()
    {
        $this->db->select('access_level');
        $this->db->where('status', 1);
        $access_levels = $this->db->get('security_access_level')->result_array();

        return $access_levels
            ? array_column($access_levels, "access_level")
            : [];
    }

    public function getCompaniesList($executiveAdminId)
    {
        return $this
            ->db
            ->select([
                "users.parent_sid",
                "users.CompanyName",
            ])
            ->where([
                "users.access_level_plus" => 1,
                "executive_user_companies.executive_admin_sid" => $executiveAdminId
            ])
            ->join(
                "users",
                "users.sid = executive_user_companies.logged_in_sid",
                "left"
            )
            ->get('executive_user_companies')
            ->result_array();
    }

    public
    function get_all_employees_from_DB($company_sid, $access_level, $status, $start, $end)
    {
        //
        if (!$status) {
            $status = ["all"];
        }

        $this->db->select('*');
        $this->db->where('parent_sid', $company_sid);

        if (is_array($access_level)) {
            if (!in_array("all", $access_level)) {
                $this->db->group_start();
                $this->db->where_in('access_level', $access_level);
                //
                if (in_array("executive_admin", $access_level)) {
                    $this->db->or_where('is_executive_admin', 1);
                } else {
                    $this->db->where('is_executive_admin', 0);
                }
                $this->db->group_end();
            }
        } else {
            if ($access_level != 'all' && $access_level != 'executive_admin' && $access_level != null) {
                $this->db->where('access_level', $access_level);
            }
        }

        if ($access_level == 'executive_admin') {
            $this->db->where('is_executive_admin', 1);
        }

        //
        if (is_array($status)) {
            //
            if ($status[0] != 'all') {
                $this->db->group_start();
                foreach ($status as $statusVal) {

                    if ($statusVal == 'terminated') {
                        $this->db->or_where('terminated_status', 1);
                    } else if ($statusVal == 'active') {
                        $this->db->group_start();
                        $this->db->or_where('active', 1);
                        $this->db->where('terminated_status', 0);
                        $this->db->group_end();
                    } else {
                        $this->db->or_where('LCASE(general_status) ', $statusVal);
                    }
                }
                $this->db->group_end();
            }
        } else {
            if ($status == 'active') {
                $this->db->where('active', 1);
                $this->db->where('terminated_status', 0);
            }

            if ($status == 'terminated') {
                $this->db->where('terminated_status', 1);
            }

            if ($status != 'all' && $status != 'active' && $status != 'terminated') {
                $this->db->where('LCASE(general_status) ', $status);
            }
        }


        if (!empty($start) && !empty($end)) {

            $startDate = str_replace(' 23:59:59', '', $end);
            $endDate = str_replace(' 00:00:00', '', $start);

            //
            $this->db->group_start();
            $this->db->group_start();
            $this->db->where('joined_at >= ', $startDate);
            $this->db->where('joined_at <= ', $endDate);
            $this->db->group_end();

            $this->db->or_group_start();
            $this->db->where('rehire_date>=', $startDate);
            $this->db->where('rehire_date<=', $endDate);
            $this->db->group_end();

            $this->db->or_group_start();
            $this->db->where('registration_date>=', $end);
            $this->db->where('registration_date<=', $start);
            $this->db->group_end();
            $this->db->group_end();
        }


        $records_obj = $this->db->get('users');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr;
        } else {
            return array();
        }
    }

    public function get_department_name($department_sid)
    {
        $this->db->select('name');
        $this->db->where('sid', $department_sid);
        $record_obj = $this->db->get('departments_management');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0]['name'];
        } else {
            return '';
        }
    }

    public function get_team_name($team_sid)
    {
        $this->db->select('name');
        $this->db->where('sid', $team_sid);
        $record_obj = $this->db->get('departments_team_management');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0]['name'];
        } else {
            return '';
        }
    }

    public function getCompanyName(
        int $companyId
    ) {
        return $this
            ->db
            ->select("CompanyName")
            ->where("sid", $companyId)
            ->get("users")
            ->row_array()["CompanyName"];
    }

    public  function get_employee_last_status_info($employee_sid)
    {
        $this->db->select('employee_status');
        $this->db->where('employee_sid ', $employee_sid);
        $this->db->order_by('sid', 'DESC');
        $record_obj = $this->db->get('terminated_employees');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            $employee_status = "Archived Employee";
            //
            if ($record_arr['employee_status'] == 1) {
                $employee_status = 'Terminated';
            } else if ($record_arr['employee_status'] == 2) {
                $employee_status = 'Retired';
            } else if ($record_arr['employee_status'] == 3) {
                $employee_status = 'Deceased';
            } else if ($record_arr['employee_status'] == 4) {
                $employee_status = 'Suspended';
            } else if ($record_arr['employee_status'] == 5) {
                $employee_status = 'Active';
            } else if ($record_arr['employee_status'] == 6) {
                $employee_status = 'Inactive';
            } else if ($record_arr['employee_status'] == 7) {
                $employee_status = 'Leave';
            } else if ($record_arr['employee_status'] == 8) {
                $employee_status = 'Rehired';
            } else if ($record_arr['employee_status'] == 9) {
                $employee_status = 'Transferred';
            }
            //
            return $employee_status;
        } else {
            return 'Archived Employee';
        }
    }

    public function get_status_info($employee_sid, $status)
    {
        $this->db->select('termination_reason , termination_date');
        $this->db->where('employee_status', $status);
        $this->db->where('employee_sid ', $employee_sid);
        $this->db->order_by('sid', 'DESC');
        $record_obj = $this->db->get('terminated_employees');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }

    public function get_all_departments($company_sid)
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
}
