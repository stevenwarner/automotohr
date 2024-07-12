<?php

class Direct_deposit_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_direct_deposit_details($users_type, $user_sid, $company_sid)
    {
        $this->db->select('*');
        $this->db->where('users_type', $users_type);
        $this->db->where('users_sid', $user_sid);

        if ($company_sid > 0) {
            $this->db->where('company_sid', $company_sid);
        }

        $records_obj = $this->db->get('bank_account_details');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }

    function save_direct_deposit_details($user_type, $user_sid, $bank_details)
    {
        $this->db->where('users_type', $user_type);
        $this->db->where('users_sid', $user_sid);

        $this->db->from('bank_account_details');
        $record_count = $this->db->count_all_results();

        if ($record_count > 0) {
            $this->db->where('users_type', $user_type);
            $this->db->where('users_sid', $user_sid);

            if (isset($bank_details['users_type'])) {
                unset($bank_details['users_type']);
            }

            if (isset($bank_details['users_sid'])) {
                unset($bank_details['users_sid']);
            }

            $this->db->update('bank_account_details', $bank_details);
        } else {
            $this->db->insert('bank_account_details', $bank_details);
        }
    }


    //
    function getDDI($users_type, $user_sid, $company_sid)
    {
        $this->db->select('bank_account_details.*, users.CompanyName');
        $this->db->where('users_type', $users_type);
        $this->db->where('users_sid', $user_sid);
        $this->db->order_by('bank_account_details.sid', 'ASC');
        $this->db->join('users', 'users.sid = bank_account_details.company_sid', 'inner');
        //
        if ($company_sid > 0) $this->db->where('bank_account_details.company_sid', $company_sid);
        //
        $a = $this->db->get('bank_account_details');
        $b = $a->result_array();
        $a->free_result();
        //
        return $b;
    }

    //
    function checkDDI($users_type, $user_sid, $company_sid)
    {
        $this->db->where('users_type', $users_type);
        $this->db->where('users_sid', $user_sid);
        $this->db->order_by('bank_account_details.sid', 'ASC');
        $this->db->join('users', 'users.sid = bank_account_details.company_sid', 'inner');
        //
        if ($company_sid > 0) $this->db->where('bank_account_details.company_sid', $company_sid);
        //
        // $a = $this->db->get('bank_account_details');
        return $this->db->count_all_results('bank_account_details');
    }

    //
    function get_user_extra_info($user_type, $user_sid, $company_sid)
    {
        $where = '';
        $table = '';
        if ($user_type == 'employee') {
            $where = 'parent_sid';
            $table = 'users';
        } else {
            $where = 'employer_sid';
            $table = 'portal_job_applications';
        }

        $this->db->select('extra_info');
        $this->db->where('sid', $user_sid);
        $this->db->where($where, $company_sid);
        $record_obj = $this->db->get($table);

        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        $employee_number = '';

        if (!empty($record_arr)) {
            $extra_info = unserialize($record_arr[0]['extra_info']);
            if (isset($extra_info['employee_number'])) {
                $employee_number = $extra_info['employee_number'];
            }
        }

        return $employee_number;
    }

    //
    function set_user_extra_info($user_type, $user_sid, $company_sid, $employee_number)
    {
        $where = '';
        $table = '';
        if ($user_type == 'employee') {
            $where = 'parent_sid';
            $table = 'users';
        } else {
            $where = 'employer_sid';
            $table = 'portal_job_applications';
        }

        $this->db->select('extra_info');
        $this->db->where('sid', $user_sid);
        $this->db->where($where, $company_sid);
        $record_obj = $this->db->get($table);

        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        $data_to_update = array();

        if (!empty($record_arr)) {
            $extra_info_arr = unserialize($record_arr[0]['extra_info']);
            if (isset($extra_info_arr['employee_number'])) {
                $extra_info_arr['employee_number'] = $employee_number;
            } else {
                $extra_info_arr['employee_number'] = $employee_number;
            }
            $data_to_update['extra_info'] = serialize($extra_info_arr);
        } else {
            $extra_info_arr = array();
            $extra_info_arr['employee_number'] = $employee_number;
            $data_to_update['extra_info'] = serialize($extra_info_arr);
        }

        $this->db
            ->where('sid', $user_sid)
            ->where($where, $company_sid)
            ->update($table, $data_to_update);
    }

    //
    function insertDDA($ins)
    {
        $this->db->insert("bank_account_details", $ins);
        return $this->db->insert_id();
    }

    //
    function getDDStatus($userSid, $userType)
    {
        //
        $b = $this->db
            ->where('users_type', $userType)
            ->where('users_sid', $userSid)
            ->count_all_results('bank_account_details');
        //
        if ($b == 0) return 'primary';
        //
        return 'secondary';
    }

    //
    function updateDD($sid)
    {
        //
        $a = $this->db
            ->select('users_sid, users_type')
            ->where('sid', $sid)
            ->get('bank_account_details');
        //
        $b = $a->row_array();
        $a = $a->free_result();
        //
        $this->db
            ->where('users_sid', $b['users_sid'])
            ->where('users_type', $b['users_type'])
            ->update('bank_account_details', [
                'account_status' => 'secondary'
            ]);
        $this->db
            ->where('sid', $sid)
            ->update('bank_account_details', [
                'account_status' => 'primary'
            ]);
    }

    //
    function updateDDA($u, $sid)
    {
        $this->db
            ->where('sid', $sid)
            ->update('bank_account_details', $u);
    }

    //
    function getUserData($sid, $type)
    {
        if ($type == 'applicant') {
            $a = $this->db
                ->select('
                    portal_job_applications.employer_sid,
                    portal_job_applications.first_name,
                    portal_job_applications.last_name,
                    portal_job_applications.email,
                    portal_job_applications.sid,
                    portal_job_applications.desired_job_title,
                    portal_job_listings.Title
                ')
                ->join('portal_job_listings', 'portal_job_listings.sid = portal_job_applications.job_sid', 'left')
                ->where('portal_job_applications.sid', $sid)
                ->get('portal_job_applications');
            //
            $b = $a->row_array();
            $a = $a->free_result();
            //
            return $b;
        } else {
            $a = $this->db
                ->select('employee_number, parent_sid, first_name, last_name, email, sid, document_sent_on, job_title')
                ->where('sid', $sid)
                ->get('users');
            //
            $b = $a->row_array();
            $a = $a->free_result();
            //
            return $b;
        }
    }

    function insert_bank_detail_history($data_to_insert)
    {
        $this->db->insert("bank_account_details_history", $data_to_insert);
    }

    //
    function getGeneralAssignments(
        $companySid,
        $userSid,
        $userType
    ) {
        //
        $a =
            $this->db
            ->select('document_type, assigned_at, note')
            ->where('company_sid', $companySid)
            ->where('user_sid', $userSid)
            ->where('user_type', $userType)
            ->where('status', 1)
            ->where('is_completed', 0)
            ->get('documents_assigned_general');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        if (!count($b)) return $b;
        //
        $c = [];
        //
        foreach ($b as $v) $c[$v['document_type']] = ['assigned_at' => $v['assigned_at'], 'note' => $v['note']];
        //
        return $c;
    }

    /**
     * move the history of direct deposit
     *
     * @param int $userId
     */
    public function moveCurrentDirectDepositToHistory(
        int $recordId
    ) {
        // get the records
        $record = $this
            ->db
            ->where([
                "sid" => $recordId,
            ])
            ->limit(1)
            ->get("bank_account_details")
            ->row_array();
        //
        if ($record) {
            //
            $insertData = $record;
            unset($insertData["sid"]);
            $insertData["bank_account_details_sid"] = $record["sid"];
            $this
                ->db
                ->insert(
                    "bank_account_details_history",
                    $insertData
                );
        }
    }
}
