<?php defined('BASEPATH') || exit('No direct script access allowed');

class User_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     *
     */
    public function getCompanyEmployees($companyId)
    {
        return $this->db
            ->select(getUserFields())
            ->where([
                'parent_sid' => $companyId,
                'active' => 1,
                'terminated_status' => 0
            ])
            ->order_by('first_name', 'ASC')
            ->get('users')
            ->result_array();
    }

    /**
     * Get employee profile history data
     *
     * @param int     $employeeId
     * @param boolean $count
     * @return array
     */
    public function getProfileHistory(
        $employeeId,
        $count = false
    ) {
        //
        $this->db
            ->from('profile_history')
            ->where('user_sid', $employeeId);
        //
        if ($count) {
            return $this->db->count_all_results();
        }
        //
        $records =
            $this->db
            ->select('
            profile_history.profile_data,
            profile_history.created_at,
            profile_history.employer_sid,
            profile_history.history_type,
            users.first_name,
            users.last_name,
            users.middle_name,
            users.access_level,
            users.access_level_plus,
            users.is_executive_admin,
            users.job_title,
            users.timezone
        ')
            ->join('users', 'users.sid = profile_history.employer_sid', 'left')
            ->order_by('profile_history.sid', 'DESC')
            ->get()
            ->result_array();
        //
        if (!empty($records)) {
            foreach ($records as $key => $record) {
                $records[$key]['full_name'] = remakeEmployeeName($record);
                //
                unset(
                    $records[$key]['first_name'],
                    $records[$key]['last_name'],
                    $records[$key]['middle_name'],
                    $records[$key]['access_level'],
                    $records[$key]['access_level_plus'],
                    $records[$key]['is_executive_admin'],
                    $records[$key]['job_title'],
                    $records[$key]['timezone']
                );
            }
        }
        //
        return $records;
    }

    /**
     * Get states
     *
     * @return array
     */
    public function getStates()
    {
        //
        return $this->db
            ->select('sid, state_name')
            ->from('states')
            ->get()
            ->result_array();
    }

    /**
     *
     */
    public function handleGeneralDocumentChange(
        $documentType,
        $post,
        $licenseFile,
        $employeeId,
        $employerId = 0
    ) {
        //
        if ($employeeId == $employerId) {
            $employerId = 0;
        }
        // Lets fetch the old record
        $oldData = $this->getGeneralDocumentData($documentType, $employeeId);
        $newData = [];
        // Licenses
        if ($documentType == 'occupationalLicense' || $documentType == 'driversLicense') {
            $newData['license_type'] = $post['license_type'];
            $newData['license_authority'] = $post['license_authority'];
            $newData['license_class'] = isset($post['license_class']) ? $post['license_class'] : '';
            $newData['license_number'] = $post['license_number'];
            $newData['license_issue_date'] = !empty($post['license_issue_date']) ?
                formatDateToDB($post['license_issue_date'], SITE_DATE, DB_DATE)
                : '';
            $newData['license_expiration_date'] = !empty($post['license_expiration_date']) ?
                formatDateToDB($post['license_expiration_date'], SITE_DATE, DB_DATE)
                : '';
            $newData['license_indefinite'] = isset($post['license_indefinite']) ? $post['license_indefinite'] : '';
            $newData['license_notes'] = trim($post['license_notes']);
            $newData['license_file'] = $licenseFile;
            //
            if (!empty($oldData['license_issue_date'])) {
                $oldData['license_issue_date'] =
                    formatDateToDB($oldData['license_issue_date'], SITE_DATE, DB_DATE);
            }
            //
            if (!empty($oldData['license_expiration_date'])) {
                // Lets correct the format
                $oldData['license_expiration_date'] = formatDateToDB($oldData['license_expiration_date'], SITE_DATE, DB_DATE);
            }
            //
            $oldData['license_notes'] = trim($oldData['license_notes']);
        }
        // Direct deposit
        if ($documentType == 'directDeposit') {
            //
            $oldData = $oldData[$post['account_code'] - 1];
            //
            $newData['account_title'] = $post['account_title'];
            $newData['account_type'] = $post['account_type'];
            $newData['financial_institution_name'] = $post['financial_institution_name'];
            $newData['routing_transaction_number'] = $post['routing_transaction_number'];
            $newData['account_number'] = $post['account_number'];
            $newData['deposit_type'] = $post['deposit_type'];
            $newData['account_percentage'] = $post['account_percentage'];
            $newData['employee_number'] = $post['employee_number'];
            $newData['print_name'] = $post['print_name'];
            $newData['consent_date'] = formatDateToDB($post['consent_date'], SITE_DATE, DB_DATE);
            //
            if (!empty($licenseFile)) {
                $newData['voided_cheque'] = $licenseFile;
            } else {
                unset($oldData['voided_cheque']);
            }
            $newData['user_signature'] = $this->input->post('drawn_signature', false);
        }
        // dependents
        if ($documentType == 'dependent') {
            //
            $newData['first_name'] = $post['first_name'];
            $newData['last_name'] = $post['last_name'];
            $newData['address'] = $post['address'];
            $newData['address_line'] = $post['address_line'];
            $newData['Location_Country'] = $post['Location_Country'];
            $newData['Location_State'] = $post['Location_State'];
            $newData['city'] = $post['city'];
            $newData['postal_code'] = $post['postal_code'];
            $newData['phone'] = $post['phone'];
            $newData['birth_date'] = formatDateToDB($post['birth_date'], SITE_DATE, DB_DATE);
            $newData['relationship'] = $post['relationship'];
            $newData['ssn'] = $post['ssn'];
            $newData['gender'] = $post['gender'];
            $newData['family_member'] = $post['family_member'] ?? '';
            //
            if (!empty($oldData['birth_date'])) {
                //
                $oldData['birth_date'] =
                    formatDateToDB(
                        $oldData['birth_date'],
                        strpos('-', $oldData['birth_date']) === false ? SITE_DATE : 'm-d-Y',
                        DB_DATE
                    );
            }
        }
        // Emergency Contacts
        if ($documentType == 'emergencyContact') {
            //
            $newData['first_name'] = $post['first_name'];
            $newData['last_name'] = $post['last_name'];
            $newData['email'] = $post['email'];
            $newData['Location_Country'] = $post['Location_Country'];
            $newData['Location_State'] = $post['Location_State'];
            $newData['Location_City'] = $post['Location_City'];
            $newData['Location_ZipCode'] = $post['Location_ZipCode'];
            $newData['Location_Address'] = $post['Location_Address'];
            $newData['PhoneNumber'] = $post['PhoneNumber'];
            $newData['Relationship'] = $post['Relationship'];
            $newData['priority'] = $post['priority'];
        }
        // W4
        if ($documentType == 'w4') {
            //
            $newData['first_name'] = $post['w4_first_name'];
            $newData['middle_name'] = $post['w4_middle_name'];
            $newData['last_name'] = $post['w4_last_name'];
            $newData['ss_number'] = $post['ss_number'];
            $newData['home_address'] = $post['home_address'];
            $newData['city'] = $post['city'];
            $newData['zip'] = $post['zip'];
            //

            $difference = $this->findDifference($oldData, $newData);
            //
            if ($difference['changed'] > 0) {

                if ($difference['data']['middle_name']['new']) {
                    $data['middle_name'] = $difference['data']['middle_name']['new'];
                }
                if ($difference['data']['ss_number']['new']) {
                    $data['ssn'] = $difference['data']['ss_number']['new'];
                }
                if ($difference['data']['ss_number']['new']) {
                    $data['Location_address'] = $difference['data']['home_address']['new'];
                }

                if ($difference['data']['zip']['new']) {
                    $data['Location_ZipCode'] = $difference['data']['zip']['new'];
                }
                if ($difference['data']['city']['new']) {
                    $data['Location_City'] = $difference['data']['city']['new'];
                }

                $this->db
                    ->where('sid', $employeeId)
                    ->update('users', $data);
            }
        }
        //
        $difference = $this->findDifference($oldData, $newData);
        //
        if ($difference['changed'] == 0) {
            return 0;
        }
        //
        if ($documentType == 'directDeposit') {
            $difference['data']['account'] = $post['account_code'];
        }
        //
        return $this->saveDifference([
            'user_sid' => $employeeId,
            'employer_sid' => $employerId,
            'history_type' => $documentType,
            'profile_data' => json_encode($difference['data']),
            'created_at' => date('Y-m-d H:i:s', strtotime('now'))
        ]);
    }


    /**
     * Get the general documents
     *
     * @param string $documentType
     * @param int    $employeeId
     *
     * @return array
     */
    public function getGeneralDocumentData(
        $documentType,
        $employeeId
    ) {
        //
        $func = 'get' . (ucwords($documentType));
        //
        return $this->$func($employeeId);
    }

    /**
     * Get occupational license
     *
     * @param int    $userId
     * @param string $userType
     *
     * @return array
     */
    public function getOccupationalLicense(
        $userId,
        $userType = 'employee'
    ) {
        //
        $record = $this->db
            ->select('license_details, license_file')
            ->where([
                'users_sid' => $userId,
                'users_type' => $userType,
                'license_type' => 'occupational'
            ])
            ->get('license_information')
            ->row_array();
        //
        $tmp = [];
        //
        if (empty($record)) {
            $tmp['license_type'] = '';
            $tmp['license_authority'] = '';
            $tmp['license_class'] = '';
            $tmp['license_number'] = '';
            $tmp['license_issue_date'] = '';
            $tmp['license_expiration_date'] = '';
            $tmp['license_indefinite'] = '';
            $tmp['license_notes'] = '';
            $tmp['license_file'] = '';
            //
            return $tmp;
        }
        //
        $tmp = unserialize($record['license_details']);
        $tmp['license_file'] = $record['license_file'];
        //
        unset($record);
        //
        return $tmp;
    }

    /**
     * Get drivers license
     *
     * @param int    $userId
     * @param string $userType
     *
     * @return array
     */
    public function getDriversLicense(
        $userId,
        $userType = 'employee'
    ) {
        //
        $record = $this->db
            ->select('license_details, license_file')
            ->where([
                'users_sid' => $userId,
                'users_type' => $userType,
                'license_type' => 'drivers'
            ])
            ->get('license_information')
            ->row_array();
        //
        $tmp = [];
        //
        if (empty($record)) {
            $tmp['license_type'] = '';
            $tmp['license_authority'] = '';
            $tmp['license_class'] = '';
            $tmp['license_number'] = '';
            $tmp['license_issue_date'] = '';
            $tmp['license_expiration_date'] = '';
            $tmp['license_indefinite'] = '';
            $tmp['license_notes'] = '';
            $tmp['license_file'] = '';
            //
            return $tmp;
        }
        //
        $tmp = unserialize($record['license_details']);
        $tmp['license_file'] = $record['license_file'];
        //
        unset($record);
        //
        return $tmp;
    }

    /**
     * Get direct deposit
     *
     * @param int    $userId
     * @param string $userType
     *
     * @return array
     */
    public function getDirectDeposit(
        $userId,
        $userType = 'employee'
    ) {
        //
        $records = $this->db
            ->select('
            account_title,
            routing_transaction_number,
            account_number,
            financial_institution_name,
            account_type,
            voided_cheque,
            deposit_type,
            account_percentage,
            employee_number,
            user_signature,
            print_name,
            consent_date
        ')
            ->where([
                'users_sid' => $userId,
                'users_type' => $userType
            ])
            ->order_by('sid', 'ASC')
            ->get('bank_account_details')
            ->result_array();
        //
        if (empty($records)) {
            $records = [];
            $records[0]['account_title'] = '';
            $records[0]['routing_transaction_number'] = '';
            $records[0]['account_number'] = '';
            $records[0]['financial_institution_name'] = '';
            $records[0]['account_type'] = '';
            $records[0]['voided_cheque'] = '';
            $records[0]['deposit_type'] = '';
            $records[0]['account_percentage'] = '';
            $records[0]['employee_number'] = '';
            $records[0]['user_signature'] = '';
            $records[0]['print_name'] = '';
            $records[0]['consent_date'] = '';
            //
            $records[1]['account_title'] = '';
            $records[1]['routing_transaction_number'] = '';
            $records[1]['account_number'] = '';
            $records[1]['financial_institution_name'] = '';
            $records[1]['account_type'] = '';
            $records[1]['voided_cheque'] = '';
            $records[1]['deposit_type'] = '';
            $records[1]['account_percentage'] = '';
            $records[1]['employee_number'] = '';
            $records[1]['user_signature'] = '';
            $records[1]['print_name'] = '';
            $records[1]['consent_date'] = '';
        }
        //
        if (!isset($records[1])) {
            //
            $records[1]['account_title'] = '';
            $records[1]['routing_transaction_number'] = '';
            $records[1]['account_number'] = '';
            $records[1]['financial_institution_name'] = '';
            $records[1]['account_type'] = '';
            $records[1]['voided_cheque'] = '';
            $records[1]['deposit_type'] = '';
            $records[1]['account_percentage'] = '';
            $records[1]['employee_number'] = '';
            $records[1]['user_signature'] = '';
            $records[1]['print_name'] = '';
            $records[1]['consent_date'] = '';
        }
        //
        return $records;
    }

    /**
     * Get dependents
     *
     * @param int    $userId
     * @param string $userType
     *
     * @return array
     */
    public function getDependent(
        $userId,
        $userType = 'employee'
    ) {
        //
        $record = $this->db
            ->select('dependant_details')
            ->where([
                'sid' => $this->input->post('sid', true)
            ])
            ->get('dependant_information')
            ->row_array();
        //
        if (empty($record)) {
            $record['first_name'] = '';
            $record['last_name'] = '';
            $record['address'] = '';
            $record['address_line'] = '';
            $record['Location_Country'] = '';
            $record['Location_State'] = '';
            $record['city'] = '';
            $record['postal_code'] = '';
            $record['phone'] = '';
            $record['birth_date'] = '';
            $record['relationship'] = '';
            $record['ssn'] = '';
            $record['gender'] = '';
            //
            return $record;
        }
        //
        $record = unserialize($record['dependant_details']);
        //
        return $record;
    }

    /**
     * Get emergency contacts
     *
     * @param int    $userId
     * @param string $userType
     *
     * @return array
     */
    public function getEmergencyContact(
        $userId,
        $userType = 'employee'
    ) {
        //
        $record = $this->db
            ->select('
            first_name,
            last_name,
            email,
            Location_Country,
            Location_State,
            Location_City,
            Location_ZipCode,
            Location_Address,
            PhoneNumber,
            Relationship,
            priority
        ')
            ->where([
                'sid' => $this->input->post('sid', true)
            ])
            ->get('emergency_contacts')
            ->row_array();
        //
        if (empty($record)) {
            $record['first_name'] = '';
            $record['last_name'] = '';
            $record['email'] = '';
            $record['Location_Country'] = '';
            $record['Location_State'] = '';
            $record['Location_City'] = '';
            $record['Location_ZipCode'] = '';
            $record['Location_Address'] = '';
            $record['PhoneNumber'] = '';
            $record['Relationship'] = '';
            $record['priority'] = '';
        }
        //
        return $record;
    }

    /**
     *
     */
    private function findDifference($oldData, $newData)
    {
        //
        $changed = 0;
        //
        $dt = [];
        //
        if (!empty($oldData)) {
            foreach ($oldData as $key => $data) {
                //
                if (!isset($newData[$key])) {
                    continue;
                }
                //
                if ((isset($newData[$key])) && strip_tags($data) != strip_tags($newData[$key])) {
                    //
                    $dt[$key] = [
                        'old' => $data,
                        'new' => $newData[$key]
                    ];
                    //
                    $changed = 1;
                }
            }
        }
        //

        return ['changed' => $changed, 'data' => $dt];
    }

    /**
     * Saves the history
     *
     * @param array $data
     *
     * @return int
     */
    public function saveDifference($data)
    {
        //
        $this->db->insert('profile_history', $data);
        //
        return $this->db->insert_id();
    }


    /**
     * Get employee history data
     *
     * @param int     $employeeId
     * @return array
     */
    public function getEmployeeChanges(
        $companyId,
        $employeeIds,
        $startDate,
        $endDate
    ) {
        //
        $this->db
            ->select('
            profile_history.profile_data,
            profile_history.created_at,
            profile_history.employer_sid,
            profile_history.history_type,
            profile_history.user_sid,
            users.first_name,
            users.last_name,
            users.middle_name,
            users.access_level,
            users.access_level_plus,
            users.is_executive_admin,
            users.job_title,
            users.timezone
        ')
            ->join('users', 'users.sid = profile_history.user_sid', 'left')
            ->order_by('profile_history.sid', 'DESC');
        // where
        $this->db->where('parent_sid', $companyId);
        if ($employeeIds) {
            $this->db->where_in('user_sid', $employeeIds);
        }
        if ($startDate && $endDate) {
            $this->db->where('profile_history.created_at >= ', $startDate . ' 00:00:00');
            $this->db->where('profile_history.created_at <= ', $endDate . ' 23:59:59');
        }
        $records = $this->db
            ->get('profile_history')
            ->result_array();
        //
        if (empty($records)) {
            return [];
        }
        //
        $tmp = [];
        //
        foreach ($records as $key => $record) {
            //
            if (!isset($tmp[$record['user_sid']])) {
                $tmp[$record['user_sid']] = [];
                $tmp[$record['user_sid']]['full_name'] = remakeEmployeeName($record);
                $tmp[$record['user_sid']]['last_changed'] = $record['created_at'];
                $tmp[$record['user_sid']]['sid'] = $record['user_sid'];
                $tmp[$record['user_sid']]['what_changed'] = [];
                //
                if (trim($tmp[$record['user_sid']]['full_name']) == '[]') {
                    $tmp[$record['user_sid']]['full_name'] = 'Self';
                }
            }
            //
            $tmp[$record['user_sid']]['what_changed'][$record['history_type']] = 1;
        }
        //
        return $tmp;
    }

    /**
     * Gets the profile change count
     *
     * @param int    $companyId
     * @param string $type
     * @return int
     */
    public function getEmployeeInformationChange(
        int $companyId,
        string $type
    ) {
        // Set dates
        if ($type == 'daily') {
            $startDate = date('Y-m-d', strtotime('now'));
            $endDate = date('Y-m-d', strtotime('now'));
        } elseif ($type == 'week') {
            $startDate = date('Y-m-d', strtotime('monday this week'));
            $endDate = date('Y-m-d', strtotime('sunday this week'));
        } elseif ($type == 'month') {
            $startDate = date('Y-m-01', strtotime('now'));
            $endDate = date('Y-m-t', strtotime('now'));
        }
        // Query
        return
            $this->db
            ->select('profile_history.sid')
            ->join('users', 'users.sid = profile_history.user_sid')
            ->where('users.parent_sid', $companyId)
            ->where('profile_history.created_at >= ', $startDate . ' 00:00:00')
            ->where('profile_history.created_at <= ', $endDate . ' 23:59:59')
            ->group_by('profile_history.user_sid')
            ->get('profile_history')
            ->num_rows();
    }

    /**
     * Get all active companies
     */
    public function getAllActiveCompanies()
    {
        return $this->db
            ->select('sid, CompanyName')
            ->where('active', 1)
            ->where('is_paid', 1)
            ->where('parent_sid', 0)
            ->order_by('CompanyName', 'ASC')
            ->get('users')
            ->result_array();
    }

    /**
     * 
     */
    public function getEmployeeHistory($get)
    {
        //
        $whereArray = [];
        if ($get && $get['start_date'] && $get['end_date']) {
            //
            if ($get['start_date'] != '') {
                $whereArray['profile_history.created_at >='] = (formatDateToDB($get['start_date'], SITE_DATE, DB_DATE)) . ' 00:00:00';
            }
            //
            if ($get['end_date']) {
                $whereArray['profile_history.created_at <='] = (formatDateToDB($get['end_date'], SITE_DATE, DB_DATE)) . ' 23:59:59';
            }
        } else {
            $whereArray['profile_history.created_at >='] = getSystemDate(DB_DATE) . ' 00:00:00';
            $whereArray['profile_history.created_at <='] = getSystemDate(DB_DATE) . ' 23:59:59';
        }
        //
        $changedData = $this->db
            ->select('
                profile_history.sid, 
                profile_history.created_at,  
                profile_history.profile_data,
                users.first_name,
                users.last_name,
                users.nick_name,
                users.access_level,
                users.access_level_plus,
                users.is_executive_admin,
                users.job_title,
                users.CompanyName,
                users.parent_sid
            ')
            ->group_start()
            ->where("profile_history.profile_data REGEXP '\"job_title\"'")
            ->or_where("profile_history.profile_data REGEXP '\"email\"'")
            ->group_end()
            ->join('users', 'users.sid = profile_history.user_sid', 'inner')
            ->where($whereArray)
            ->order_by('profile_history.sid', 'DESC')
            ->get('profile_history')
            ->result_array();

        if (!empty($changedData)) {
            foreach ($changedData as $key => $val) {
                if ($val['CompanyName'] == '' || $val['CompanyName'] == null) {
                    $companyName = $this->db
                        ->select('CompanyName')
                        ->where('sid', $val['parent_sid'])
                        ->get('users')
                        ->row_array();
                    $changedData[$key]['CompanyName'] = $companyName['CompanyName'];
                }
            }
        }

        return  $changedData;
    }

    //
    public function getW4(
        $userId,
        $userType = 'employee'
    ) {
        //
        $record = $this->db
            ->select('first_name, middle_name,last_name,ss_number,home_address,city,state,zip')
            ->where([
                'employer_sid' => $userId,
                'user_type' => $userType
            ])
            ->get('form_w4_original')
            ->row_array();
        //
        $tmp = [];
        //
        if (empty($record)) {
            $tmp['first_name'] = '';
            $tmp['middle_name'] = '';
            $tmp['last_name'] = '';
            $tmp['ss_number'] = '';
            $tmp['home_address'] = '';
            $tmp['city'] = '';
            $tmp['state'] = '';
            $tmp['zip'] = '';
            //
            return $tmp;
        }
        //
        $tmp = $record;
        //
        unset($record);
        //
        return $tmp;
    }

    //
    public function getAiWhishlistData($get)
    {
        //
        $whereArray = [];
        if ($get && $get['start_date'] && $get['end_date']) {
            //
            if ($get['start_date'] != '') {
                $whereArray['created_at >='] = (formatDateToDB($get['start_date'], SITE_DATE, DB_DATE)) . ' 00:00:00';
            }
            //
            if ($get['end_date']) {
                $whereArray['created_at <='] = (formatDateToDB($get['end_date'], SITE_DATE, DB_DATE)) . ' 23:59:59';
            }
        }
        //
        $resultData =
            $this->db->where($whereArray)
            ->order_by('sid', 'DESC')
            ->get('cms_highlights')
            ->result_array();

        return  $resultData;
    }


    //
    public function getCookiesData($get)
    {
        //
        $whereArray = [];
        if ($get && $get['start_date'] && $get['end_date']) {
            //
            if ($get['start_date'] != '') {
                $whereArray['created_at >='] = (formatDateToDB($get['start_date'], SITE_DATE, DB_DATE)) . ' 00:00:00';
            }
            //
            if ($get['end_date']) {
                $whereArray['created_at <='] = (formatDateToDB($get['end_date'], SITE_DATE, DB_DATE)) . ' 23:59:59';
            }
        }

        if ($get['client_ip'] != '') {
            $whereArray['client_ip ='] = trim($get['client_ip']);
        }
        //
        $resultData =
            $this->db->where($whereArray)
            ->order_by('sid', 'DESC')
            ->get('cookie_log_data')
            ->result_array();

        return  $resultData;
    }



    //
    public function getIndeedApplicantDispositionData($get)
    {
        //
        $whereArray = [];
        if ($get && $get['start_date'] && $get['end_date']) {
            //
            if ($get['start_date'] != '') {
                $whereArray['portal_applicant_indeed_status_log.created_at >='] = (formatDateToDB($get['start_date'], SITE_DATE, DB_DATE)) . ' 00:00:00';
            }
            //
            if ($get['end_date']) {
                $whereArray['portal_applicant_indeed_status_log.created_at <='] = (formatDateToDB($get['end_date'], SITE_DATE, DB_DATE)) . ' 23:59:59';
            }
        }

        if ($get['client_ip'] != '') {
            $whereArray['client_ip ='] = trim($get['client_ip']);
        }
        //
        $resultData =
            $this->db
            ->select('
            portal_applicant_indeed_status_log.ats_status,
            portal_applicant_indeed_status_log.indeed_status,
            portal_applicant_indeed_status_log.created_by,
            portal_applicant_indeed_status_log.created_at,
            portal_applicant_indeed_status_log.status,
            portal_applicant_jobs_list.company_sid,
            portal_job_applications.first_name,
            portal_job_applications.middle_name,
            portal_job_applications.last_name,
            users.CompanyName
          
        ')
            ->where($whereArray)
            ->join('portal_applicant_jobs_list', 'portal_applicant_jobs_list.sid = portal_applicant_indeed_status_log.portal_applicant_job_list_sid', 'left')
            ->join('portal_job_applications', 'portal_job_applications.sid = portal_applicant_jobs_list.portal_job_applications_sid', 'left')
            ->join('users', 'users.sid = portal_applicant_jobs_list.company_sid', 'left')
            ->order_by('portal_applicant_indeed_status_log.sid', 'DESC')
            ->get('portal_applicant_indeed_status_log')
            ->result_array();

        return  $resultData;
    }
}
