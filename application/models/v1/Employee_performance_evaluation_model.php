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
            $returnArray["sections"][1] = true;
        }
        if ($record["section_2_json"]) {
            $returnArray["sections"][2] = true;
        }
        if ($record["section_3_json"]) {
            $returnArray["sections"][3] = true;
        }
        if ($record["section_4_json"]) {
            $returnArray["sections"][4] = true;
        }
        if ($record["section_5_json"]) {
            $returnArray["sections"][5] = true;
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
    public function getEmployeeDocumentSection (
        $employeeId,
        $section
    ): array
    {
        return $this
        ->db
        ->select($section)
        ->where("employee_sid",$employeeId)
        ->limit(1)
        ->get(
            "employee_performance_evaluation_document"
        )
        ->row_array();
    }

    public function saveEmployeeDocumentSection ($employeeId, $data) {
        $this->db
        ->where("employee_sid", $employeeId)
            ->update(
                "employee_performance_evaluation_document",
                [
                    "section_1_json" => json_encode($data)
                ]
            );
    }

    /**
     * Check Employee complete its section 2
     *
     * @param int $employeeId
     * @return bool
     */
    public function checkEmployeeUncompletedDocument ($employeeId): bool
    {
        $record = $this
        ->db
        ->select("section_2_json")
        ->where("employee_sid", $employeeId)
        ->where( "status", 1)
        ->limit(1)
        ->get(
            "employee_performance_evaluation_document"
        )
        ->row_array();
        //
        if ($record['section_2_json']) {
            return false;
        } else {
            return true;
        }
    }
    
    /**
     * Check Employee complete its section 2
     *
     * @param int $employeeId
     * @return bool
     */
    public function checkEmployeeAssignPerformanceDocument ($employeeId): bool
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
}