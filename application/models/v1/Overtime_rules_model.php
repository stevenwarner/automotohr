<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Overtime rules model
 * 
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @version 1.0
 * @package Scheduling
 */
class Overtime_rules_model extends CI_Model
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
     * get the overtime rules
     *
     * @param int $companyId
     * @param int $status
     * @return array
     */
    public function getOvertimeRules(
        int $companyId,
        int $status
    ): array {
        return $this->db
            ->select("sid, rule_name, overtime_multiplier, double_overtime_multiplier")
            ->where("company_sid", $companyId)
            ->where("status", $status)
            ->order_by("sid", "DESC")
            ->get("company_overtime_rules")
            ->result_array();
    }

    /**
     * get the overtime rules
     *
     * @param int $companyId
     * @param int $ruleId
     * @return array
     */
    public function getOvertimeRuleById(
        int $companyId,
        int $ruleId
    ): array {
        return $this->db
            ->select("
                rule_name,
                overtime_multiplier,
                double_overtime_multiplier,
                daily_json,
                weekly_json,
                holiday_json,
                seven_consecutive_days_json
            ")
            ->where("company_sid", $companyId)
            ->where("sid", $ruleId)
            ->get("company_overtime_rules")
            ->row_array();
    }


    /**
     * process the pay schedule
     *
     * @param int    $companyId
     * @param array  $post
     * @return array
     */
    public function processOvertimeRules(
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
    public function processDeleteOvertimeRules(
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
