<?php
public function getComplynetCompanies () {
        $this->db->select('company_sid');
        $record_obj = $this->db->get('complynet_companies');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return array_column($record_arr, 'company_sid');
        } else {
            return array();
        }
    }

    public function checkEmployeeOnComplynet ($employeeId, $companyId) {
        $this->db->where('employee_sid', $employeeId);
        $this->db->where('company_sid', $companyId);
        $this->db->from('complynet_employees');
        return $this->db->count_all_results();
    }

    public function getTransferedEmployees ($companiesIds) {
        $this->db->select('sid, previous_employee_sid, from_company_sid, new_employee_sid, to_company_sid');
        $this->db->where_in('to_company_sid', $companiesIds);
        $this->db->order_by('sid', 'DESC');
        $record_obj = $this->db->get('employees_transfer_log');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }

    public function isSecondaryEmployeeTransferd ($employeeId, $companyId) {
        $this->db->select('sid, previous_employee_sid, from_company_sid, new_employee_sid, to_company_sid');
        $this->db->where('new_employee_sid', $employeeId);
        $this->db->where('to_company_sid', $companyId);
        $record_obj = $this->db->get('employees_transfer_log');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            $checkPrimary = $this->checkEmployeeOnComplynet($record_arr['new_employee_sid'], $record_arr['to_company_sid']);
            $checkSecondary = $this->checkEmployeeOnComplynet($record_arr['previous_employee_sid'], $record_arr['from_company_sid']);
            //
            if ($checkSecondary == 0 && $checkPrimary == 0) {
                $this->isSecondaryEmployeeTransferd($record_arr['previous_employee_sid'], $record_arr['from_company_sid']);
            } else {
                return $record_arr;
            }
        } else {
            return array();
        }
    }

    public function checkPrimaryEmployeeActive ($employeeId, $companyId) {
        $this->db->where('sid', $employeeId);
        $this->db->where('parent_sid', $companyId);
        $this->db->where('active', 1);
        $this->db->where('terminated_status', 0);
        $this->db->from('users');
        return $this->db->count_all_results();
    }

    /**
     * 
     */
    public function fixUnhandleEmployees () {
        //
        $notMoved = [];
        $complynetCompanies = $this->getComplynetCompanies();
        //
        // _e($complynetCompanies,true);
        if (!empty($complynetCompanies)) {
                //
            $transferEmployees = $this->getTransferedEmployees($complynetCompanies);
            // _e($transferEmployees,true);
            //
            if (!empty($transferEmployees)) {
                foreach ($transferEmployees as $employee) {
                    $checkPrimary = $this->checkEmployeeOnComplynet($employee['new_employee_sid'], $employee['to_company_sid']);
                    $checkSecondary = $this->checkEmployeeOnComplynet($employee['previous_employee_sid'], $employee['from_company_sid']);
                    $isActive = $this->checkPrimaryEmployeeActive($employee['new_employee_sid'], $employee['to_company_sid']);
                    //
                    if ($checkSecondary == 1 && $checkPrimary == 0 && $isActive == 1) {
                        $employeeInfo = [
                            'oldEmployeeId' => $employee['previous_employee_sid'],
                            'oldCompanyId' => $employee['from_company_sid'],
                            'newEmployeeId' => $employee['new_employee_sid'],
                            'newCompanyId' => $employee['to_company_sid']
                        ];
                        //
                        $notMoved[] = $employeeInfo;
                        //
                        echo "this employee is not move on complynet ". getUserNameBySID($employee['new_employee_sid']). " in company ".getCompanyNameBySid($employee['to_company_sid'])."<br>" ;
                        //
                        // $this->manageEmployee($employeeInfo);
                    } else if ($checkSecondary == 0 && $checkPrimary == 0 && $isActive == 1) {
                        $isTransferd = $this->isSecondaryEmployeeTransferd($employee['previous_employee_sid'], $employee['from_company_sid']);
                        //
                        if ($isTransferd) {
                            $checkSecondary = $this->checkEmployeeOnComplynet($isTransferd['previous_employee_sid'], $isTransferd['from_company_sid']);
                            //
                            if ($checkSecondary == 1) {
                                $employeeInfo = [
                                    'oldEmployeeId' => $isTransferd['previous_employee_sid'],
                                    'oldCompanyId' => $isTransferd['from_company_sid'],
                                    'newEmployeeId' => $employee['new_employee_sid'],
                                    'newCompanyId' => $employee['to_company_sid']
                                ];
                                //
                                $notMoved[] = $employeeInfo;
                                //
                                echo "this employee is not move on complynet ". getUserNameBySID($isTransferd['new_employee_sid']). " in company ".getCompanyNameBySid($isTransferd['to_company_sid'])."<br>" ;
                                //
                                // $this->manageEmployee($employeeInfo);
                            }
                        }
                    }

                }
            }
            _e($notMoved,true);
            
        } 
    }


    public function missingEmployee () {
        $jsonString = '[
            {   
              "oldEmployeeId": "28197",
              "oldCompanyId": "28144",
              "newEmployeeId": "52989",
              "newCompanyId": "16439"
            },
            {  
              "oldEmployeeId": "24672",
              "oldCompanyId": "16465",
              "newEmployeeId": "50074",
              "newCompanyId": "16463"
            },
            {   
              "oldEmployeeId": "35303",
              "oldCompanyId": "16463",
              "newEmployeeId": "50073",
              "newCompanyId": "16465"
            },
            {   
              "oldEmployeeId": "50852",
              "oldCompanyId": "50060",
              "newEmployeeId": "52637",
              "newCompanyId": "50063"
            }
        ]';
        //
        $employees = json_decode($jsonString,true);
        //
        $this->load->model('2022/Complynet_model', 'complynet_model');
        //
        foreach ($employees as $employee) {
            $this->complynet_model->manageEmployee($employee);
        }
        //
        die("I am here");
    }