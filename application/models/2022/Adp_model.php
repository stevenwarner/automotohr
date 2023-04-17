<?php defined('BASEPATH') || exit('No direct script access allowed');

class Adp_model extends CI_Model
{
    /**
     * Employee columns supported by ADP
     * @var array
     */
    private $employeeColumns;


    /**
     * Applicants columns supported by ADP
     * @var array
     */
    private $applicantColumns;

    public function __construct()
    {
        parent::__construct();
        //
        $this->employeeColumns = [
            'dob' => [
                'tag' => 'Workers - Biological Data Management',
                'name' => 'birthDate',
                'url' => 'events/hr/v1/worker.birth-date.change',
                'body' => [
                    "birthDate" => "1994-04-20"
                ]
            ],
            'gender' => [
                'tag' => 'Workers - Biological Data Management',
                'name' => 'genderCode',
                'url' => 'events/hr/v1/worker.gender.change',
                'body' => [
                    "codeValue" => "M"
                ]
            ],
            'race' => [
                'tag' => 'Workers - Biological Data Management',
                'name' => 'raceCode',
                'url' => 'events/hr/v1/worker.race.change',
                'body' => [
                    "raceCode" => [
                        'codeValue' => 1
                    ]
                ]
            ],
            'marital_status' => [
                'tag' => 'Workers - Demographic Data Management',
                'name' => 'maritalStatusCode',
                'url' => 'events/hr/v1/worker.marital-status.change',
                'body' => [
                    "maritalStatusCode" => [
                        'codeValue' => 'S'
                    ]
                ]
            ],

            'rehire_date' => [
                'tag' => 'Workers - Lifecycle Management',
                'name' => 'rehireDate',
                'url' => '​events/​hr/​v1/​worker.rehire',
                'body' => [
                    'effectiveDateTime' => '2023-01-02',
                    "workerDates" => [
                        'rehireDate' => '2023-01-01'
                    ],
                    'workerStatus' => [
                        "reasonCode" => [
                            'codeValue' => 'CURR'
                        ]
                    ]
                ]
            ],

            'Location_Address' => [
                'tag' => 'Workers - Personal Communication Management',
                'name' => 'lineOne',
                'url' => 'events/​hr/​v1/​worker.legal-address.change',
                'body' => [
                    "legalAddress" => [
                        'lineOne' => '4449 Beach address',
                        'lineTwo' => '',
                        'lineThree' => '',
                        'lineFour' => '',
                        'lineFive' => '',
                        'cityName' => 'Port Orchard',
                        "countrySubdivisionLevel1" => [
                            'subdivisionType' => 'State',
                            'codeValue' => 'wa',
                            'longName' => 'Washington',
                        ],
                        "countrySubdivisionLevel2" => [
                            'longName' => 'King County',
                        ],
                        'countryCode' => 'US',
                        'postalCode' => '2355',
                    ]
                ]
            ],
            'Location_City' => [
                'tag' => 'Workers - Personal Communication Management',
                'name' => 'cityName',
                'url' => 'events/​hr/​v1/​worker.legal-address.change',
                'body' => [
                    "legalAddress" => [
                        'lineOne' => '4449 Beach address',
                        'lineTwo' => '',
                        'lineThree' => '',
                        'lineFour' => '',
                        'lineFive' => '',
                        'cityName' => 'Port Orchard',
                        "countrySubdivisionLevel1" => [
                            'subdivisionType' => 'State',
                            'codeValue' => 'wa',
                            'longName' => 'Washington',
                        ],
                        "countrySubdivisionLevel2" => [
                            'longName' => 'King County',
                        ],
                        'countryCode' => 'US',
                        'postalCode' => '2355',
                    ]
                ]
            ],
            'Location_State' => [
                'tag' => 'Workers - Personal Communication Management',
                'name' => 'codeValue',
                'url' => 'events/​hr/​v1/​worker.legal-address.change',
                'body' => [
                    "legalAddress" => [
                        'lineOne' => '4449 Beach address',
                        'lineTwo' => '',
                        'lineThree' => '',
                        'lineFour' => '',
                        'lineFive' => '',
                        'cityName' => 'Port Orchard',
                        "countrySubdivisionLevel1" => [
                            'subdivisionType' => 'State',
                            'codeValue' => 'wa',
                            'longName' => 'Washington',
                        ],
                        "countrySubdivisionLevel2" => [
                            'longName' => 'King County',
                        ],
                        'countryCode' => 'US',
                        'postalCode' => '2355',
                    ]
                ]
            ],
            'Location_Country' => [
                'tag' => 'Workers - Personal Communication Management',
                'name' => 'countryCode',
                'url' => 'events/​hr/​v1/​worker.legal-address.change',
                'body' => [
                    "legalAddress" => [
                        'lineOne' => '4449 Beach address',
                        'lineTwo' => '',
                        'lineThree' => '',
                        'lineFour' => '',
                        'lineFive' => '',
                        'cityName' => 'Port Orchard',
                        "countrySubdivisionLevel1" => [
                            'subdivisionType' => 'State',
                            'codeValue' => 'wa',
                            'longName' => 'Washington',
                        ],
                        "countrySubdivisionLevel2" => [
                            'longName' => 'King County',
                        ],
                        'countryCode' => 'US',
                        'postalCode' => '2355',
                    ]
                ]
            ],
            'Location_ZipCode' => [
                'tag' => 'Workers - Personal Communication Management',
                'name' => 'postalCode',
                'url' => 'events/​hr/​v1/​worker.legal-address.change',
                'body' => [
                    "legalAddress" => [
                        'lineOne' => '4449 Beach address',
                        'lineTwo' => '',
                        'lineThree' => '',
                        'lineFour' => '',
                        'lineFive' => '',
                        'cityName' => 'Port Orchard',
                        "countrySubdivisionLevel1" => [
                            'subdivisionType' => 'State',
                            'codeValue' => 'wa',
                            'longName' => 'Washington',
                        ],
                        "countrySubdivisionLevel2" => [
                            'longName' => 'King County',
                        ],
                        'countryCode' => 'US',
                        'postalCode' => '2355',
                    ]
                ]
            ],
            'ssn' => [
                'tag' => 'Workers - Identification Management',
                'name' => 'ssn',
                'url' => '​events/​hr/​v1/​worker.government-id.change',
                'body' => [
                    "governmentID" => [
                        'idValue' => '143-20-1791',
                        "nameCode" => [
                            'codeValue' => 'SSN',
                        ],
                        'countryCode' => 'US',
                    ]
                ]
            ],
            'phone_number' => [
                'tag' => 'Personal Contacts',
                'name' => 'landlines',
                'url' => 'events/​hr/​v1/​worker.personal-contact.change',
                'body' => [
                    "communication" => [
                        "landlines" => [
                            'nameCode' => [
                                "codeValue" => "Home Phone",
                                "shortName" => "Home Phone"

                            ],
                            "countryDialing" => "1",
                            "areaDialing" => "770",
                            "dialNumber" => "7720252",
                            "formattedNumber" => "(770) 772-0252",

                        ],
                        "mobiles" => [
                            'nameCode' => [
                                "codeValue" => "Home Phone",
                                "shortName" => "Home Phone"
                            ],
                            "countryDialing" => "1",
                            "areaDialing" => "770",
                            "dialNumber" => "7720252",
                            "formattedNumber" => "(770) 772-0252",
                        ],
                        "emails" => [
                            "itemID" => "139470108012_1",
                            'nameCode' => [
                                "codeValue" => "E-mail",
                                "shortName" => "E-mail"
                            ],
                            "emailUri" => "email@test.com"

                        ]
                    ]
                ]
            ],
            'email' => [
                'tag' => 'Personal Contacts',
                'name' => 'emails',
                'url' => 'events/​hr/​v1/​worker.personal-contact.change',
                'body' => [
                    "communication" => [
                        "emails" => [
                            "itemID" => "139470108012_1",
                            'nameCode' => [
                                "codeValue" => "E-mail",
                                "shortName" => "E-mail"
                            ],
                            "emailUri" => "email@test.com"
                        ]
                    ]
                ]
            ],
            'termination_date' => [
                'tag' => 'Workers - Work Assignment Management',
                'name' => 'termination_date',
                'url' => '/events/hr/v1/worker.work-assignment.terminate',
                'body' => [
                    "workAssignment" => [
                        'terminationDate' => '2023-01-01',
                        'lastWorkedDate' => '2023-01-01',
                        'assignmentStatus' => [
                            'reasonCode' => [
                                'assignmentStatus' => 'A00',
                            ],

                        ]
                    ]
                ]
            ],
            'first_name' => [
                'tag' => 'Workers - Demographic Data Management',
                'name' => 'first_name',
                'url' => '/events/hr/v1/worker.legal-name.change',
                'body' => [
                    "worker" => [
                        "person" => [
                            "legalName" => [
                                "preferredSalutations" => [

                                    "salutationCode" => [
                                        "longName" => "Mr."
                                    ]

                                ],
                                "generationAffixCode" => [
                                    "longName" => "Honourable"
                                ],
                                "titleAffixCodes" => [

                                    "affixCode" => [
                                        "longName" => "Dr."
                                    ]

                                ],
                                "givenName" => " ",
                                "middleName" => "Z",
                                "familyName1" => "June",
                                "formattedName" => "June Mr,Z Ryana"
                            ]
                        ]
                    ]
                ]
            ],
            'last_name' => [
                'tag' => 'Workers - Demographic Data Management',
                'name' => 'last_name',
                'url' => '/events/hr/v1/worker.legal-name.change',
                'body' => [
                    "worker" => [
                        "person" => [
                            "legalName" => [
                                "preferredSalutations" => [

                                    "salutationCode" => [
                                        "longName" => "Mr."
                                    ]

                                ],
                                "generationAffixCode" => [
                                    "longName" => "Honourable"
                                ],
                                "titleAffixCodes" => [

                                    "affixCode" => [
                                        "longName" => "Dr."
                                    ]

                                ],
                                "givenName" => " ",
                                "middleName" => "Z",
                                "familyName1" => "June",
                                "formattedName" => "June Mr,Z Ryana"
                            ]
                        ]
                    ]
                ]
            ],
            'middle_name' => [
                'tag' => 'Workers - Demographic Data Management',
                'name' => 'middle_name',
                'url' => '/events/hr/v1/worker.legal-name.change',
                'body' => [
                    "worker" => [
                        "person" => [
                            "legalName" => [
                                "preferredSalutations" => [

                                    "salutationCode" => [
                                        "longName" => "Mr."
                                    ]

                                ],
                                "generationAffixCode" => [
                                    "longName" => "Honourable"
                                ],
                                "titleAffixCodes" => [

                                    "affixCode" => [
                                        "longName" => "Dr."
                                    ]

                                ],
                                "givenName" => " ",
                                "middleName" => "Z",
                                "familyName1" => "June",
                                "formattedName" => "June Mr,Z Ryana"
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }

    /**
     * Push employee changes to ADP queue
     *
     * @method getEmployeeAssociateOIDAdp,
     * getCompanyAdpMode
     * @param array $oldData
     * @param array $newData
     * @param int   $employeeId
     * @param int   $companyId
     * @param int   $employerId
     * @return bool
     */
    public function handleMultipleColumns(
        array $oldData,
        array $newData,
        int $employeeId,
        int $companyId,
        int $employerId
    ) {
        // set change array
        $newDataCustom = [];
        // check and set the required columns
        foreach (array_keys($this->employeeColumns) as $column) {
            // convert empty vales to "Not Specified"
            $oldValue = GetVal($oldData[$column]);
            $newValue = GetVal($newData[$column]);
            // check if the value is not same and not equal to "Not Specified"
            if ($newValue != 'Not Specified' && $oldValue != $newValue) {
                $newDataCustom[$column] = trim(GetVal($newData[$column]));
            }
        }
        // check if something needs to changed
        if (empty($newDataCustom)) {
            return false;
        }
        // fetch the associate id of employee
        // of ADP
        $associateOID = $this->getEmployeeAssociateOIDAdp($employeeId);
        // fetch company adp mode
        $mode = $this->getCompanyAdpMode($companyId);
        // loop through the data
        foreach ($newDataCustom as $field => $value) {
            //
            $extraValueArray = [];
            //
            $fieldIndex = $this->employeeColumns[$field];
            $fieldValueURL = $this->employeeColumns[$field]['url'];
            //
            if ($field == 'race') {
                $value = getRaceCode($value, false);
            } elseif ($field == 'marital_status') {
                $value = getMaritalCode($value, false);
            } elseif (
                $field == 'Location_Address'
                || $field == 'Location_City'
                || $field == 'Location_State'
                || $field == 'Location_ZipCode'
                || $field == 'Location_Country'
            ) {
                $extraValueArray['Location_Address'] = isset($newDataCustom['Location_Address']) ? $newDataCustom['Location_Address'] : $oldData['Location_Address'];
                $extraValueArray['Location_City'] = isset($newDataCustom['Location_City']) ? $newDataCustom['Location_City'] : $oldData['Location_City'];
                $extraValueArray['Location_State'] = isset($newDataCustom['Location_State']) ? $newDataCustom['Location_State'] : $oldData['Location_State'];
                $extraValueArray['Location_ZipCode'] = isset($newDataCustom['Location_ZipCode']) ? $newDataCustom['Location_ZipCode'] : $oldData['Location_ZipCode'];
                $extraValueArray['Location_Country'] = isset($newDataCustom['Location_Country']) ? $newDataCustom['Location_Country'] : $oldData['Location_Country'];
            } elseif ($field == 'phone_number') {
                $formatedPhone = '';
                // echo $value;
                //    die();
                if (phonenumber_validate($value)) {
                    $formatedPhone = phonenumber_format($value, 1);
                    $formatedPhonearray = explode(' ', $formatedPhone);
                    //
                    $areaDialing = str_replace("(", "", $formatedPhonearray[0]);
                    $areaDialing = str_replace(")", "", $areaDialing);
                    $dialNumber = str_replace("-", "", $formatedPhonearray[1]);
                    //
                    $extraValueArray['areaDialing'] = $areaDialing;
                    $extraValueArray['dialNumber'] = $dialNumber;
                    $extraValueArray['formattedNumber'] = $formatedPhone;
                }

                if ($formatedPhone == '') {
                    continue;
                }
            } else if ($field == 'first_name' || $field == 'last_name' || $field == 'middle_name') {
                $extraValueArray['first_name'] = isset($newDataCustom['first_name']) ? $newDataCustom['first_name'] : $oldData['first_name'];
                $extraValueArray['last_name'] = isset($newDataCustom['last_name']) ? $newDataCustom['last_name'] : $oldData['last_name'];
                $extraValueArray['middle_name'] = isset($newDataCustom['middle_name']) ? $newDataCustom['middle_name'] : $oldData['middle_name'];
            }

            // If no value is present
            // don't push it ADP
            if (!$value) {
                continue;
            }
            // set insert array
            $ins = [
                'employee_sid' => $employeeId,
                'associate_oid' => $associateOID,
                'key_index' => $field,
                'key_value' => $value,
                'request_url' => 'https://' . ($mode == 'uat' ?  'uat-' : '') . 'api.adp.com/' . $fieldValueURL,
                'request_body' => json_encode(generateRequestBody($fieldIndex, $associateOID, $value, $extraValueArray)),
                'request_method' => 'POST',
                'status' => 1
            ];
            //
            $whereArray = $ins;
            unset($whereArray['request_body']);
            // quickly check if job already exists
            if ($this->db->where($whereArray)->count_all_results('adp_queue')) {
                continue;
            }
            //
            unset($whereArray['key_value']);
            // inactive previous jobs
            if ($this->db->where($whereArray)->count_all_results('adp_queue')) {
                //
                $this->db->where($whereArray)->update('adp_queue', ['status' => 0]);
            }
            //
            $ins['created_at'] = $ins['updated_at'] = getSystemDate();
            //
            $this->db->insert('adp_queue', $ins);
        }
        return true;
    }


    //
    public function onboardApplicantToAdp($json) {
      //  _e($json,true,true);
   //     die($json);
        // set insert array
        $mode = 'uat' ;
        $ins = [
            'employee_sid' => 0,
            'associate_oid' => 0,
            'key_index' => 'onboarding',
            'key_value' => "",
            'request_url' => 'https://' . ($mode == 'uat' ?  'uat-' : '') . 'api.adp.com/hcm/v2/applicant.onboard',
            'request_body' => json_encode($json['onboarding']['body']),
            'request_method' => 'POST',
            'status' => 1
        ];
        //
        $ins['created_at'] = $ins['updated_at'] = getSystemDate();
        //
        $this->db->insert('adp_queue', $ins);
    }

    /**
     * Get the employee associate OID
     *
     * @param int $employeeId
     * @return string
     */
    public function getEmployeeAssociateOIDAdp(int $employeeId)
    {
        $record = $this->db
            ->select('associate_oid')
            ->where('employee_sid', $employeeId)
            ->get('adp_employees')
            ->row_array();
        //
        return $record['associate_oid'];
    }

    /**
     * Get the company mode
     *
     * @param int $companyId
     * @return string
     */
    public function getCompanyAdpMode(int $companyId)
    {
        return 'uat';
        $record = $this->db
            ->select('mode')
            ->where('company_sid', $companyId)
            ->get('adp_companies')
            ->row_array();
        //
        return $record['mode'];
    }


    //
    function get_adp_company_data($company_sid)
    {
        $this->db->select('*');
        $this->db->from('adp_companies');
        $this->db->where('company_sid', $company_sid);
        $query_result = $this->db->get();
        return $row = $query_result->row_array();
    }

    //

    function update_adp_company_settings($company_sid, $dataToUpdate)
    {
        $this->db->where('company_sid', $company_sid);
        $this->db->update('adp_companies', $dataToUpdate);
    }


    //
    function add_update_adp_employees($employeeId, $associateId, $workerId)
    {
        $record = $this->db
            ->select('*')
            ->where('employee_sid', $employeeId)
            ->get('adp_employees')
            ->row_array();

        if (!empty($record)) {
            //
            $ins['employee_sid'] = $record['employee_sid'];
            $ins['associate_oid'] = $record['associate_oid'];
            $ins['worker_id'] = $record['worker_id'];
            $ins['created_at'] = $ins['updated_at'] = getSystemDate();
            //
            $this->db->insert('adp_employees_history', $ins);
            //
            $dataToUpdate['associate_oid'] = $associateId;
            $dataToUpdate['worker_id'] = $workerId;
            $dataToUpdate['updated_at'] = getSystemDate();

            $this->db->where('employee_sid', $employeeId);
            $this->db->update('adp_employees', $dataToUpdate);
        } else {
            $insemp['employee_sid'] = $employeeId;
            $insemp['associate_oid'] = $associateId;
            $insemp['worker_id'] = $workerId;
            $insemp['created_at'] = $insemp['updated_at'] = getSystemDate();
            //
            $this->db->insert('adp_employees', $insemp);
        }
    }

    //

    public function getOnADPEmployees($company_sid)
    {
        //
        $this->db->select(
            'users.sid,
           users.first_name,
           users.last_name,
           users.email,
           users.username,
           users.PhoneNumber,
           users.department_sid,
           users.team_sid,
           users.job_title,
           users.access_level,
           users.access_level_plus,
           users.pay_plan_flag,
           adp_employees.associate_oid,
           adp_employees.worker_id,
           adp_employees.created_at
           '
        );
        $this->db->from('adp_employees');
        $this->db->join('users', 'users.sid = adp_employees.employee_sid');
        $this->db->where('users.parent_sid', $company_sid);
        $query_result = $this->db->get();
        return $result = $query_result->result_array();
    }


    //
    public function getOffADPEmployees($employeesArray, $companySid)
    {

        $this->db->select('
              sid,
              first_name,
              last_name,
              email,
              username,
              PhoneNumber,
              department_sid,
              team_sid,
              job_title,
              access_level,
              access_level_plus,
              pay_plan_flag
          ')
            ->where('parent_sid', $companySid)
            ->where('is_executive_admin', 0)
            ->order_by('first_name', 'ASC');
        //
        if (!empty($employeesArray)) {
            $this->db->where_not_in('sid', $employeesArray);
        }
        //
        $employees = $this->db->get('users')->result_array();
        return $employees;
    }



    /**
     * Get companies
     * @param string $status
     * @return array
     */
    //
    public function getCompanies(string $status = 'all')
    {
        $this->db
            ->select('CompanyName, sid')
            ->where([
                'parent_sid' => 0
            ])
            ->order_by('CompanyName', 'ASC');
        //
        if ($status != 'all') {
            $this->db->where('active', $status == 'active' ? 1 : 0);
        }
        //
        return
            $this->db->get('users')
            ->result_array();
    }


    //
    function delete_ADP_employyee($employee_sid)
    {

        $record = $this->db
            ->select('*')
            ->where('employee_sid', $employee_sid)
            ->get('adp_employees')
            ->row_array();

        if (!empty($record)) {
            //
            $ins['employee_sid'] = $record['employee_sid'];
            $ins['associate_oid'] = $record['associate_oid'];
            $ins['worker_id'] = $record['worker_id'];
            $ins['created_at'] = $ins['updated_at'] = getSystemDate();
            //
            $this->db->insert('adp_employees_history', $ins);
        }

        $this->db->where('employee_sid', $employee_sid);
        $this->db->delete('adp_employees');
    }
}
