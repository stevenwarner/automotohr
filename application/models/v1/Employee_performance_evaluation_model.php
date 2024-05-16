<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Employee Performance Evaluation Document Model
 *
 * @author AutomotoHR Dev Team
 * @link   www.automotohr.com
 * @version 1.0
 * @package AutomotoHR
 */

class Employee_performance_evaluation_model extends CI_Model
{
    /**
     * main
     */
    public function __construct()
    {
        // inherit parent
        parent::__construct();
    }

    /**
     * get the form by employee id
     *
     * @method setDefaultDocument
     *
     * @param int $employeeId
     * @return json
     */
    public function getEmployeeDocument(int $employeeId): array
    {
        // set the default return array
        $returnArray = $this->setDefaultDocument();
        // get the document against the employee
        if (
            !$this
                ->db
                ->where("employee_sid", $employeeId)
                ->count_all_results(
                    "employee_performance_evaluation_document"
                )
        ) {
            return $returnArray;
        }
        // get the document
        $record = $this
            ->db
            ->select([
                "employee_performance_evaluation_document.section_1_json",
                "employee_performance_evaluation_document.section_2_json",
                "employee_performance_evaluation_document.section_3_json",
                "employee_performance_evaluation_document.section_4_json",
                "employee_performance_evaluation_document.section_5_json",
                "employee_performance_evaluation_document.status",
                "employee_performance_evaluation_document.assigned_on",
                "employee_performance_evaluation_document.last_assigned_by",
                "employee_performance_evaluation_document.completed_at",
            ])
            ->select(getUserFields())
            ->join(
                "users",
                "users.sid = employee_performance_evaluation_document.last_assigned_by",
                "left"
            )
            ->where(
                "employee_performance_evaluation_document.employee_sid",
                $employeeId
            )
            ->limit(1)
            ->get(
                "employee_performance_evaluation_document"
            )
            ->row_array();

        //
        $returnArray["assigned_on"] = $record["assigned_on"];
        $returnArray["assigned_by"] = $record["last_assigned_by"]
            ? remakeEmployeeName($record)
            : "";

        // check the status of the document
        if ($record["status"] == 0) {
            $returnArray["status"] = "revoked";
        } elseif ($record["status"] == 1) {
            $returnArray["status"] = "assigned";
        }
        //
        $returnArray["completion_status"] = $record["completed_at"] ? true : false;
        // for sections
        if ($record["section_1_json"]) {
            $info = json_decode($record['section_1_json'], true);
            //
            $returnArray["sections"][1] = [
                "status" => $info['completed_at'] ? true : false,
                "is_verified" => $info['verified_by'] ? true : false,
                "completed_on" => $info['completed_at'],
                "completed_by" => getUserNameBySID($info['completed_by'])
            ];
        }
        if ($record["section_2_json"]) {
            $info = json_decode($record['section_2_json'], true);
            //
            $returnArray["sections"][2] = [
                "status" => true,
                "completed_on" => $info['completed_at'],
                "completed_by" => getUserNameBySID($info['completed_by'])
            ];
        }
        if ($record["section_3_json"]) {
            $info = json_decode($record['section_3_json'], true);
            //
            $returnArray["sections"][3] = [
                "status" => true,
                "completed_on" => $info['completed_at'],
                "completed_by" => getUserNameBySID($info['completed_by'])
            ];
        }
        if ($record["section_4_json"]) {
            $info = json_decode($record['section_4_json'], true);
            //
            $managerName = $info['manager_signature_by'] ? getUserNameBySID($info['manager_signature_by']) : "Not sign yet";
            $employeeName = $info['employee_signature_by'] ? getUserNameBySID($info['employee_signature_by']) : "Not sign yet";
            //
            $returnArray["sections"][4] = [
                "status" => $info['is_completed'] == 1 ? true : false,
                "completed_on" => $info['completed_at'],
                "completed_by" => "Manager: " . $managerName . "<br>Employee: " . $employeeName
            ];
        }
        if ($record["section_5_json"]) {
            $info = json_decode($record['section_5_json'], true);
            //
            $returnArray["sections"][5] = [
                "manager_completed" => $info['manager_completed_at'] ? true : false,
                "status" => $info['is_completed'] == 1 ? true : false,
                "completed_by" => $info['is_completed'] == 1 ? getUserNameBySID($info['hr_manager_completed_by']) : '',
                "completed_on" => $info['is_completed'] == 1 ? $info['hr_manager_completed_at'] : '',
            ];
        }

        return $returnArray;
    }

    /**
     * handle document assignment
     *
     * @param int $loggedInCompanyId
     * @param int $loggedInEmployeeId
     * @param int $employeeId
     * @param string $action
     * @return json
     */
    public function handleDocumentAssignment(
        int $loggedInCompanyId,
        int $loggedInEmployeeId,
        int $employeeId,
        string $action
    ): array {
        // get system dare time
        $systemDateTime = getSystemDate();
        // check if document already assigned
        if (
            $action == "assign" &&
            $this
            ->db
            ->where("employee_sid", $employeeId)
            ->count_all_results(
                "employee_performance_evaluation_document"
            )
        ) {
            //
            $this
                ->db
                ->where("employee_sid", $employeeId)
                ->update(
                    "employee_performance_evaluation_document",
                    [
                        "assigned_on" => $systemDateTime,
                        "last_assigned_by" => $loggedInEmployeeId,
                        "status" => 1,
                        "updated_at" => $systemDateTime,
                    ]
                );
            //
            $message = "You have successfully <strong>Re-assigned</strong> the document";
        } elseif ($action == "assign") {
            // set insert array
            $ins = [
                "company_sid" => $loggedInCompanyId,
                "employee_sid" => $employeeId,
                "assigned_on" => $systemDateTime,
                "last_assigned_by" => $loggedInEmployeeId,
                "completed_at" => null,
                "status" => 1,
                "created_at" => $systemDateTime,
                "updated_at" => $systemDateTime,
            ];
            //
            $this
                ->db
                ->insert(
                    "employee_performance_evaluation_document",
                    $ins
                );
            //
            $message = "You have successfully <strong>Assigned</strong> the document";
        } elseif ($action == "revoke") {
            //
            $this->moveTheDocumentToHistory($employeeId);
            //
            $this
                ->db
                ->where("employee_sid", $employeeId)
                ->update(
                    "employee_performance_evaluation_document",
                    [
                        "section_1_json" => null,
                        "section_2_json" => null,
                        "section_3_json" => null,
                        "section_4_json" => null,
                        "section_5_json" => null,
                        "completed_at" => null,
                        "status" => 0,
                        "last_assigned_by" => $loggedInEmployeeId,
                        "updated_at" => $systemDateTime,
                    ]
                );
            //
            $message = "You have successfully <strong>Revoked</strong> the document";
        }
        return [
            "success" => true,
            "message" => $message
        ];
    }

    /**
     * set the default return document array
     *
     * @return array
     */
    private function setDefaultDocument(): array
    {
        return [
            "assigned_on" => null,
            "assigned_by" => "",
            "completion_status" => 0,
            "is_required" => 0,
            "status" => "not_assigned",
            "sections" => [
                1 => [
                    "status" => false,
                    "completed_on" => "",
                    "completed_by" => ""
                ],
                2 => [
                    "status" => false,
                    "completed_on" => "",
                    "completed_by" => ""
                ],
                3 => [
                    "status" => false,
                    "completed_on" => "",
                    "completed_by" => ""
                ],
                4 => [
                    "status" => false,
                    "completed_on" => "",
                    "completed_by" => ""
                ],
                5 => [
                    "status" => false,
                    "completed_on" => "",
                    "completed_by" => ""
                ],
            ]
        ];
    }

    /**
     * Adds the document to history
     *
     * @param int $employeeId
     * @return bool
     */
    private function moveTheDocumentToHistory(int $employeeId): bool
    {
        // get the document
        $record = $this
            ->db
            ->where("employee_sid", $employeeId)
            ->limit(1)
            ->get(
                "employee_performance_evaluation_document"
            )
            ->row_array();
        //
        $record["employee_performance_evaluation_document_sid"] =
            $record["sid"];
        // remove the sid index
        unset($record["sid"]);
        // insert to history
        $this
            ->db
            ->insert(
                "employee_performance_evaluation_document_history",
                $record
            );
        //
        return $this->db->insert_id() ? true : false;
    }

    /**
     * Get desire document section data
     *
     * @param int $employeeId
     * @return array
     */
    public function getEmployeeDocumentSection(
        $employeeId,
        $section
    ): array {
        return $this
            ->db
            ->select($section)
            ->where("employee_sid", $employeeId)
            ->limit(1)
            ->get(
                "employee_performance_evaluation_document"
            )
            ->row_array();
    }

     /**
     * Get verification managers list
     *
     * @param int $employeeId
     * @return array
     */
    public function getVerificationManagers (
        $employeeId,
        $section
    ): array {
        $this->db->select('manager_sid');
        $this->db->where("employee_sid", $employeeId);
        $this->db->where('is_expired', 0);
        $this->db->where('section', $section);
        $records_obj = $this->db->get('employee_performance_verification_document_manager');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        $return_data = array();

        if (!empty($records_arr)) {
            $return_data = array_column($records_arr, "manager_sid");
        }

        return $return_data;
    }

    /**
     * Get desire document data
     *
     * @param int $employeeId
     * @param string $sections
     * @return array
     */
    public function getEmployeePerformanceDocumentData(
        $employeeId,
        $sections
    ): array {
        return $this
            ->db
            ->select($sections)
            ->where("employee_sid", $employeeId)
            ->limit(1)
            ->get(
                "employee_performance_evaluation_document"
            )
            ->row_array();
    }

    public function saveEmployeeDocumentSectionOne($employeeId, $data)
    {
        $this->db
            ->where("employee_sid", $employeeId)
            ->update(
                "employee_performance_evaluation_document",
                [
                    "section_1_json" => json_encode($data)
                ]
            );
    }

    public function saveEmployeeDocumentSectionTwo($employeeId, $data)
    {
        $this->db
            ->where("employee_sid", $employeeId)
            ->update(
                "employee_performance_evaluation_document",
                [
                    "section_2_json" => json_encode($data)
                ]
            );
    }

    public function saveEmployeeDocumentSectionThree($employeeId, $data)
    {
        $this->db
            ->where("employee_sid", $employeeId)
            ->update(
                "employee_performance_evaluation_document",
                [
                    "section_3_json" => json_encode($data)
                ]
            );
    }

    /**
     * Check Employee complete its section 2
     *
     * @param int $employeeId
     * @return bool
     */
    public function checkEmployeeUncompletedDocument($employeeId): bool
    {
        $record = $this
            ->db
            ->select("section_2_json, section_3_json, employee_signature")
            ->where("employee_sid", $employeeId)
            ->where("status", 1)
            ->limit(1)
            ->get(
                "employee_performance_evaluation_document"
            )
            ->row_array();
        //
        if ($record) {
            if ($record['section_2_json']) {
                //
                if ($record['section_3_json'] && $record['employee_signature']) {
                    return false;
                } else if ($record['section_3_json']) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    /**
     * get which section is pending
     *
     * @param int $employeeId
     * @return string
     */
    public function getEmployeePendingSectionName($employeeId): string
    {
        $record = $this
            ->db
            ->select("section_2_json, section_3_json, employee_signature")
            ->where("employee_sid", $employeeId)
            ->where("status", 1)
            ->limit(1)
            ->get(
                "employee_performance_evaluation_document"
            )
            ->row_array();
        //
        if ($record) {
            if (!$record['section_2_json']) {
                return "section_2";
            } else if ($record['section_2_json'] && !$record['section_3_json']) {
                return "section_2";
            } else if ($record['section_3_json'] && !$record['employee_signature']) {
                return "section_4";
            }
            //
            return "all_section_completed";
        }
    }

    /**
     * Check Employee complete its section 2
     *
     * @param int $employeeId
     * @return bool
     */
    public function checkEmployeeAssignPerformanceDocument($employeeId): bool
    {
        //
        if (
            $this
            ->db
            ->where("employee_sid", $employeeId)
            ->count_all_results(
                "employee_performance_evaluation_document"
            )
        ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get all active employees
     *
     * @param int $companyId
     * @return array
     */
    function getCompanyActiveEmployees($companyId): array
    {
        $a = $this->db
            ->select('sid, first_name, last_name, access_level, access_level_plus, is_executive_admin, pay_plan_flag, job_title')
            ->where('parent_sid', $companyId)
            ->where('access_level <>', "Employee")
            ->where('active', 1)
            ->order_by('concat(first_name,last_name)', 'ASC', false)
            ->get('users');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        return $b;
    }

    /**
     * Save verification manager
     *
     * @param array $record
     * @return bool
     */
    function saveVerificationManager($record): bool
    {
        $this
            ->db
            ->insert(
                "employee_performance_verification_document_manager",
                $record
            );
        //
        return $this->db->insert_id() ? true : false;
    }

    function deleteSectionVerificationManagers($employeeId, $section)
    {
        //
        $this->db
            ->where('employee_sid', $employeeId)
            ->where('section', $section)
            ->delete('employee_performance_verification_document_manager');
    }

    function checkPerformanceVerificationDocumentSection($employeeId, $section)
    {
        return $this
            ->db
            ->where("manager_sid", $employeeId)
            ->where("section", $section)
            ->where("is_expired", 0)
            ->count_all_results(
                "employee_performance_verification_document_manager"
            );
    }

    /**
     * Get all pending verification document
     *
     * @param int $employeeId
     * @return array
     */
    function getAssignedPendingVerificationDocuments($employeeId): array
    {
        $a = $this->db
            ->select('employee_sid, section, created_at, created_by')
            ->where("manager_sid", $employeeId)
            ->where("is_expired", 0)
            ->get('employee_performance_verification_document_manager');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        return $b;
    }

    public function updateVerificationManagers($employeeId, $section)
    {
        $this
            ->db
            ->where("employee_sid", $employeeId)
            ->where("section", $section)
            ->update(
                "employee_performance_verification_document_manager",
                [
                    "is_expired" => 1
                ]
            );
    }

    public function getEmployeeSectionFourData($employeeId)
    {
        //
        return $this
            ->db
            ->select("section_1_json, section_2_json, section_3_json, section_4_json, manager_signature, employee_signature")
            ->where("employee_sid", $employeeId)
            ->limit(1)
            ->get(
                "employee_performance_evaluation_document"
            )
            ->row_array();
    }

    public function saveEmployeeDocumentSectionFour($employeeId, $data)
    {
        //
        $this->db
            ->where("employee_sid", $employeeId)
            ->update(
                "employee_performance_evaluation_document",
                [
                    "section_4_json" => json_encode($data)
                ]
            );
    }

    public function saveEmployeeDocumentSectionFive ($employeeId, $data) {
        //
        $this->db
            ->where("employee_sid", $employeeId)
            ->update(
                "employee_performance_evaluation_document",
                [
                    "section_5_json" => json_encode($data)
                ]
            );
    }

    public function saveSignature($employeeId, $employeeType, $base64)
    {
        $dataToUpdate = [];
        //
        if ($employeeType == "manager") {
            $dataToUpdate['manager_signature'] = $base64;
        } else if ($employeeType == "employee") {
            $dataToUpdate['employee_signature'] = $base64;
        } else if ($employeeType == "HR_manager") {
            $dataToUpdate['hr_signature'] = $base64;
            $dataToUpdate['completed_at'] = date('Y-m-d H:i:s');
        }

        $this->db
            ->where("employee_sid", $employeeId)
            ->update(
                "employee_performance_evaluation_document",
                $dataToUpdate
            );
    }

    public function getEmployeeCurrentPayRate ($employeeId) {
        return $this
            ->db
            ->select("hourly_rate")
            ->where("sid", $employeeId)
            ->limit(1)
            ->get(
                "users"
            )
            ->row_array();
    }
}
