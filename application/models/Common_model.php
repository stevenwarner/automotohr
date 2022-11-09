<?php defined('BASEPATH') || exit('No direct script access allowed');

class Common_model extends CI_Model
{

    //
    function startR()
    {
        //
        $this->db
            ->where('start_date >=', date('Y-m-d', strtotime('now')))
            ->where('end_date >=', date('Y-m-d', strtotime('now')))
            ->update('performance_management_reviewees', ['is_started' => 1]);
    }

    //
    function endR()
    {
        //
        $this->db
            ->where('end_date >=', date('Y-m-d', strtotime('now')))
            ->update('performance_management_reviewees', ['is_started' => 0]);
    }

    //
    function get_all_licenses()
    {
        $a =
            $this->db->select('sid')
            ->where('is_paid', 1)
            ->where('active', 1)
            ->where('parent_sid', 0)
            ->get('users');
        //
        $b = $a->result_array();
        $a->free_result();
        //
        if (empty($b)) {
            return [];
        }
        //
        $a =
            $this->db
            ->select('
            users.first_name,
            users.last_name,
            users.email,
            users.sid,
            company.CompanyName,
            company.sid as CompanyId,
            company.Location_Address,
            company.PhoneNumber,
            license_information.sid as licenseId,
            license_information.license_type,
            license_information.license_details
        ')
            ->from('license_information')
            ->join('users', 'users.sid = license_information.users_sid')
            ->join('users as company', 'company.sid = users.parent_sid')
            ->where_in('users.parent_sid', array_column($b, 'sid'))
            ->where('license_information.users_type', 'employee')
            ->where('users.active', 1)
            ->where('users.terminated_status', 0)
            ->group_start()
            ->where('license_information.last_notification_sent_at IS NULL', NULL, NULL)
            ->or_where('license_information.last_notification_sent_at <>', date('Y-m-d', strtotime('now')))
            ->group_end()
            ->get();
        //
        $b = $a->result_array();
        $a->free_result();
        //
        if (empty($b)) {
            exit(0);
        }
        //
        foreach ($b as $key => $il) {
            //
            $il['license_details'] = @preg_replace_callback('!s:(\d+):"(.*?)";!', function ($x) {
                return "s:" . strlen($x[2]) . ":\"" . $x[2] . "\";";
            }, $il['license_details']);
            //
            $b[$key]['license_details'] = unserialize($il['license_details']);
        }
        //
        return $b;
    }

    //
    function update_license_last_sent_date($id)
    {
        $this->db
            ->where('sid', $id)
            ->update('license_information', [
                'last_notification_sent_at' => date('Y-m-d', strtotime('now'))
            ]);
    }

    //
    function check_table_record_exist($table)
    {
        //
        return $this->db
            ->select('last_id')
            ->get($table, NULL, NULL, FALSE)
            ->row();
    }

    /**
     * 
     * @return array
     */
    function get_records_from_log()
    {
        $this->db->select('last_id');
        $this->db->from('log_records');
        $this->db->where('table_name', 'querylog');
        //
        $result = $this->db->get('', null, null, FALSE)->row();
        //
        if (!$result) {
            $this->db->insert("log_records", array('table_name' => 'querylog', 'last_id' => '0'), null, null, FALSE);
        } else {
            $this->db->where('sid > ', $result->last_id);
        }
        //
        $this->db->select("*, date_format(created_at, '%Y-%m-%d') as created_date");
        $this->db->from('query_logs');
        //
        return  $this->db->get('', null, null, FALSE)->result();
    }
    //
    function update_from_log($lastId)
    {
        $this->db->update("log_records", array('last_id' => $lastId), null, null, FALSE);
    }

    //
    function update_last_id($sid)
    {
        //
        $check_existance = $this->db->select('table_name')->get('log_records',  NULL, NULL, FALSE)->row();
        if ($check_existance) {
            $this->db
                ->where('table_name', 'query_logs')
                ->update('log_records', ['last_id' => $sid], NULL, NULL, FALSE);
            return true;
        }
        $this->db->insert('log_records', ['table_name' => 'query_logs', 'last_id' => $sid, 'created_at' => date('Y-m-d H:i:s', strtotime('now'))], NULL, FALSE);
    }


    function get_records_from_log_filter($data)
    {
        $this->db->select('query_type,ip,query_string,result,login_user_id,error,from_cache,start_time,end_time,benchmark,created_at, CEILING(benchmark) as seconds');
        $this->db->from('query_logs');
        //
        if (!empty($data['ip'])) {
            $this->db->where('ip', $data['ip']);
        }
        //
        if (!empty($data['benchmark'])) {
            $this->db->where('CEILING(benchmark)', $data['benchmark']);
        }
        //
        if (!empty($data['date'])) {
            $this->db->where('date(created_at)', $data['date']);
        }
        //
        if (!empty($data['limit'])) {
            $this->db->limit($data['limit']);
        }
        //
        return $this->db->get('', null, null, FALSE)->result();
    }


    // 
    function getTimeoffEnabledCompanies($sId = null)
    {
        $this->db->select("company_sid");
        $this->db->from('company_modules');
        $this->db->where('module_sid', 1);
        if ($sId != null) {
            $this->db->where('company_sid', $sId);
        }
        //
        return  $this->db->get()->result_array();
    }


    // 
    function getAllCurrentYearHolidays($year)
    {
        $this->db->select("holiday_year,holiday_title,from_date,to_date,event_link,status");
        $this->db->from('timeoff_holiday_list');
        $this->db->where('holiday_year', $year);
        //
        $holidays = $this->db->get()->result_array();

        if (empty($holidays)) {
            return [];
        }
        //
        $tmp = [];
        //
        foreach ($holidays as $holiday) {
            // 
            // Christmas Day -> christmasday
            $tmp[preg_replace('/[^a-z]/i', '', strtolower($holiday['holiday_title']))] = $holiday;
        }

        return $tmp;
    }



    public function CompanyPreviusYearHolidays($companySid)
    {

        $year = date('Y');

        $this->db->select("*");
        $this->db->from('timeoff_holidays');
        $this->db->where('holiday_year', $year - 1);
        $this->db->where('company_sid', $companySid);
        //
        $holidays = $this->db->get()->result_array();

        if (empty($holidays)) {
            return [];
        } else {
            return $holidays;
        }
    }


    public function CompanyCurrentYearHolidays($companySid)
    {

        $year = date('Y');

        $this->db->select("*");
        $this->db->from('timeoff_holidays');
        $this->db->where('holiday_year', $year);
        $this->db->where('company_sid', $companySid);
        //
        $holidays = $this->db->get()->result_array();

        if (empty($holidays)) {
            return [];
        } else {
            return $holidays;
        }
    }



    function checkPublicHoliday($sId, $year, $title)
    {
        $this->db->select('sid');
        $this->db->from('timeoff_holidays');
        $this->db->where('company_sid', $sId);
        $this->db->where('holiday_year', $year);
        $this->db->where('holiday_title', $title);
        //
        $result = $this->db->get()->row();
        if (empty($result)) {
            return [];
        } else {
            return $result;
        }
    }


    function getTerminatedEmployeeStatus()
    {
        $this->db->select("employee_sid");
        $this->db->from('terminated_employees');
        $this->db->group_by('employee_sid');
        $result = $this->db->get()->result_array();
        if (empty($result)) {
            return [];
        } else {
            return $result;
        }
    }



    function updateEmployeeTerminatedStatus($sid, $data)
    {
        //
        $this->db
            ->where('sid', $sid)
            ->update('users',$data);

    }
}
