<?php defined('BASEPATH') || exit('No direct script access allowed');

class Adp_model extends CI_Model
{
    /**
     * Employee columns supported by ADP
     * @var array
     */
    private $employeeColumns;

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
            $fieldIndex = $this->employeeColumns[$field];
            $fieldValueURL = $this->employeeColumns[$field]['url'];
            //
            if ($field == 'race') {
                $value = getRaceCode($value, false);
            }
            // set insert array
            $ins = [
                'employee_sid' => $employeeId,
                'associate_oid' => $associateOID,
                'key_index' => $field,
                'key_value' => $value,
                'request_url' => 'https://' . ($mode == 'uat' ?  'uat-' : '') . 'api.adp.com/' . $fieldValueURL,
                'request_body' => json_encode(generateRequestBody($fieldIndex, $associateOID, $value)),
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
        $record = $this->db
            ->select('mode')
            ->where('company_sid', $companyId)
            ->get('adp_companies')
            ->row_array();
        //
        return $record['mode'];
    }
}
