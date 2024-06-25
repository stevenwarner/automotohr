/**
     * sync company employees
     */
    public function syncComplyNetEmployees()
    {
        // get the companies and store the JSON
        $employees = $this->db
            ->select("
                complynet_employees.sid, 
                complynet_employees.company_sid, 
                complynet_employees.employee_sid,
                complynet_employees.complynet_employee_sid,
                complynet_employees.complynet_company_sid
            ")
            ->join("users", "users.sid = complynet_employees.employee_sid", "inner")
            ->where("users.active", 1)
            // ->limit(1)
            ->get("complynet_employees")
            ->result_array();
        //
        if (!$employees) {
            exit("No employees found");
        }

        // unique companies
        $companiesIdComplyNet = array_unique(array_column($employees, "complynet_company_sid"));
        $this->getCompanyEmployeesFromComplynet($companiesIdComplyNet);
        // load data file
        $companyEmployeesArray = json_decode(
            file_get_contents("../app_logs/company_employees.json"),
            true
        );
        // loop through the employees
        foreach ($employees as $employee) {
            // get the company array
            $company = $companyEmployeesArray[$employee["complynet_company_sid"]];
            // loop through the company employees
            foreach ($company as $companyEmployee) {
                // when the Id matches
                _e($companyEmployee,true);
                if ($companyEmployee["Id"] === $employee["complynet_employee_sid"]) {
                    //
                    $this->updateEmployeeAltId(
                        $companyEmployee["Id"],
                        $employee["employee_sid"],
                        $companyEmployee
                    );
                    //
                    $this->db
                        ->where("sid", $employee["sid"])
                        ->update(
                            "complynet_employees",
                            [
                                "complynet_json" => "[" . (json_encode($companyEmployee)) . "]",
                                "alt_id" => $companyEmployee["AltId"]
                            ]
                        );
                }
            }
        }

        exit("all done");
    }

    /**
     * get the company employees
     *
     * @param array companiesIdComplyNet
     */
    private function getCompanyEmployeesFromComplynet(array $companiesIdComplyNet)
    {
        //
        $dataHolder = [];
        //
        $this->load->library(
            'Complynet/Complynet_lib',
            '',
            'clib'
        );
        //
        foreach ($companiesIdComplyNet as $companyId) {
            //
            $dataHolder[$companyId] = $this->clib->getCompanyEmployees($companyId);
        }
        //

        $handler = fopen("../app_logs/company_employees.json", "w");
        fwrite($handler, json_encode($dataHolder));
        fclose($handler);
    }

    /**
     * update employee AltId
     *
     * @param string $employeeComplyId
     * @param string $altId
     * @param array|reference $companyEmployee
     */
    private function updateEmployeeAltId(
        string $employeeComplyId,
        string $altId,
        &$companyEmployee
    )
    {
        if (!$companyEmployee["AltId"] || $employeeComplyId == '8B046339-BAD0-4E00-BE63-5A16C94F70A5') {

            //
            $this->load->library(
                'Complynet/Complynet_lib',
                '',
                'clib'
            );
        
            $this->clib->updateAltId(
                $employeeComplyId,
                $altId
            );
        
            $companyEmployee["AltId"] = 'AHR'.$altId;
        }

    }