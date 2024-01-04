<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Minimum wages model
 * 
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @version 1.0
 * @package Employee
 */
class Minimum_wages_model extends CI_Model
{
    /**
     * Main entry point
     */
    public function __construct()
    {
        // inherit parent
        parent::__construct();
    }

    /**
     * sync minimum wages from Gusto
     *
     * @param int $companyId
     * @return array
     */
    public function sync(int $companyId): array
    {
        // check if the company is on Gusto
        if (!$this->db->where("company_sid", $companyId)->count_all_results("gusto_companies")) {
            return ["msg" => "Company is not on Gusto."];
        }
        // get the company details
        $companyDetails = $this->db
            ->select("
            gusto_companies.access_token,
            gusto_companies.refresh_token,
            gusto_companies_locations.gusto_uuid,
        ")
            ->join(
                "gusto_companies_locations",
                "gusto_companies_locations.company_sid = gusto_companies.company_sid",
                "left"
            )
            ->where("gusto_companies.company_sid", $companyId)
            ->get("gusto_companies")
            ->row_array();
        // if location doesn't exists
        if (!$companyDetails["gusto_uuid"]) {
            return ["errors" => [
                "Company location is not on Gusto."
            ]];
        }
        // load payroll helper
        // load the payroll helper
        $this->load->helper('v1/payroll' . ($this->db->where([
            "company_sid" => $companyId,
            "stage" => "production"
        ])->count_all_results("gusto_companies_mode") ? "_production" : "") . '_helper');
        // get the data
        $response = gustoCall(
            "locationMinimumWages",
            $companyDetails
        );
        // when have errors
        $errors = hasGustoErrors($response);
        // check and send back errors
        if ($errors) {
            return $errors;
        }
        // check for empty list
        if (!$response) {
            return ["msg" => "No records found."];
        }
        // loop through the loop
        foreach ($response as $wage) {
            // set the where array
            $where = [
                "company_sid" => $companyId,
                "gusto_uuid" => $wage["uuid"]
            ];
            // set array
            $ins = [];
            $ins["wage"] = $wage["wage"];
            $ins["wage_type"] = $wage["wage_type"];
            $ins["effective_date"] = $wage["effective_date"];
            $ins["authority"] = $wage["authority"];
            $ins["notes"] = $wage["notes"];
            $ins["is_custom"] = 0;
            $ins["updated_at"] = getSystemDate();
            // check if record exists
            if (!$this->db->where($where)->count_all_results("company_minimum_wages")) {
                // insert
                $ins["gusto_uuid"] = $wage["uuid"];
                $ins["company_sid"] = $companyId;
                $ins["created_at"] = $ins["updated_at"];
                //
                $this->db
                    ->insert("company_minimum_wages", $ins);
            } else {
                // update
                $this->db
                    ->where($where)
                    ->update("company_minimum_wages", $ins);
            }
        }
        //
        return ["msg" => "You have successfully synced the minimum wages."];
    }

    /**
     * get the overtime rules
     *
     * @param int $companyId
     * @return array
     */
    public function get(
        int $companyId
    ): array {
        return $this->db
            ->select("sid, gusto_uuid, wage, wage_type, authority, effective_date, notes, is_custom")
            ->where("company_sid", $companyId)
            ->order_by("sid", "DESC")
            ->get("company_minimum_wages")
            ->result_array();
    }

    /**
     * get the overtime rules
     *
     * @param int $companyId
     * @param int $wageId
     * @return array
     */
    public function getSingle(
        int $companyId,
        int $wageId
    ): array {
        return $this->db
            ->select("
                wage, wage_type, authority, effective_date, notes, is_custom
            ")
            ->where("company_sid", $companyId)
            ->where("sid", $wageId)
            ->get("company_minimum_wages")
            ->row_array();
    }


    /**
     * process the pay schedule
     *
     * @param int    $companyId
     * @param array  $post
     * @return array
     */
    public function process(
        int $companyId,
        array $post
    ): array {
        //
        $status = 400;
        $response = ["msg" => "Something went wrong while updating overtime rules."];
        //
        if ($post["id"]) {
            // update
            // check if entry already exists
            if ($this->db->where([
                "company_sid" => $companyId,
                "LOWER(REGEXP_REPLACE(rule_name, '[^a-zA-Z]', '')) = " => strtolower(
                    preg_replace(
                        '/[^a-z]/i',
                        '',
                        $post["rule_name"],
                    )
                ),
            ])->where("sid <>", $post["id"])->count_all_results("company_overtime_rules")) {
                $response["msg"] = "Overtime rule already exists.";
            } else {
                // update
                $this->db
                    ->where("sid", $post["id"])
                    ->update("company_overtime_rules", [
                        "rule_name" => $post["rule_name"],
                        "overtime_multiplier" => $post["overtime_multiplier"],
                        "double_overtime_multiplier" => $post["double_overtime_multiplier"],
                        "daily_json" => $post["daily"],
                        "weekly_json" => $post["weekly"],
                        "seven_consecutive_days_json" => $post["consecutive"],
                        "holiday_json" => $post["holiday"],
                        "updated_at" => getSystemDate(),
                    ]);

                $status = 200;
                $response = ["msg" => "You have successfully updated overtime rule."];
            }
        } else {
            // insert
            // check if entry already exists
            if ($this->db->where([
                "company_sid" => $companyId,
                "LOWER(REGEXP_REPLACE(rule_name, '[^a-zA-Z]', '')) = " => strtolower(
                    preg_replace(
                        '/[^a-z]/i',
                        '',
                        $post["rule_name"],
                    )
                )
            ])->count_all_results("company_overtime_rules")) {
                $response["msg"] = "Overtime rule already exists.";
            } else {
                // insert
                $this->db
                    ->insert("company_overtime_rules", [
                        "company_sid" => $companyId,
                        "rule_name" => $post["rule_name"],
                        "overtime_multiplier" => $post["overtime_multiplier"],
                        "double_overtime_multiplier" => $post["double_overtime_multiplier"],
                        "daily_json" => $post["daily"],
                        "weekly_json" => $post["weekly"],
                        "seven_consecutive_days_json" => $post["consecutive"],
                        "holiday_json" => $post["holiday"],
                        "status" => 1,
                        "created_at" => getSystemDate(),
                        "updated_at" => getSystemDate(),
                    ]);
                // check and insert log
                if ($this->db->insert_id()) {
                    //
                    $status = 200;
                    $response = ["msg" => "You have successfully add a new overtime rule."];
                }
            }
        }
        //
        return SendResponse($status, $status === 400 ? ["errors" => [$response["msg"]]] : $response);
    }

    /**
     * process the pay schedule
     *
     * @param int $companyId
     * @param int $ruleId
     * @return array
     */
    public function delete(
        int $companyId,
        int $ruleId
    ): array {
        //
        $status = 400;
        $response = ["msg" => "Something went wrong while updating overtime rules."];
        // check if entry already exists
        if (!$this->db->where(["company_sid" => $companyId, "sid" => $ruleId])->count_all_results("company_overtime_rules")) {
            $response["msg"] = "System failed to verify the overtime rule.";
        } else {
            // update
            $this->db
                ->where("sid", $ruleId)
                ->delete("company_overtime_rules");

            $status = 200;
            $response = ["msg" => "You have successfully deleted the overtime rule."];
        }

        //
        return SendResponse($status, $status === 400 ? ["errors" => [$response["msg"]]] : $response);
    }
}
