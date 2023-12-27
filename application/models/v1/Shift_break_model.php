<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Shift break model
 * 
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @version 1.0
 * @package Time & Clock
 */
class Shift_break_model extends CI_Model
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
     * @return array
     */
    public function get(
        int $companyId
    ): array {
        return $this->db
            ->select("sid, break_name, break_duration, break_type")
            ->where("company_sid", $companyId)
            ->order_by("sid", "DESC")
            ->get("company_breaks")
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
                break_name,
                break_duration,
                break_type
            ")
            ->where("company_sid", $companyId)
            ->where("sid", $wageId)
            ->get("company_breaks")
            ->row_array();
    }


    /**
     * process
     *
     * @param int   $companyId
     * @param array $post
     * @return array
     */
    public function process(
        int $companyId,
        array $post
    ): array {
        //
        $status = 400;
        $response = ["msg" => "Something went wrong while updating break."];
        //
        if ($post["id"]) {
            // update
            // check if entry already exists
            if ($this->db->where([
                "company_sid" => $companyId,
                "LOWER(REGEXP_REPLACE(break_name, '[^a-zA-Z]', '')) = " => strtolower(
                    preg_replace(
                        '/[^a-z]/i',
                        '',
                        $post["break_name"],
                    )
                ),
            ])->where("sid <>", $post["id"])->count_all_results("company_breaks")) {
                $response["msg"] = "Break already exists.";
            } else {
                // update
                $this->db
                    ->where("sid", $post["id"])
                    ->update("company_breaks", [
                        "break_name" => $post["break_name"],
                        "break_duration" => $post["break_duration"],
                        "break_type" => $post["break_type"],
                        "updated_at" => getSystemDate(),
                    ]);

                $status = 200;
                $response = ["msg" => "You have successfully updated break."];
            }
        } else {
            // insert
            // check if entry already exists
            if ($this->db->where([
                "company_sid" => $companyId,
                "LOWER(REGEXP_REPLACE(break_name, '[^a-zA-Z]', '')) = " => strtolower(
                    preg_replace(
                        '/[^a-z]/i',
                        '',
                        $post["break_name"],
                    )
                )
            ])->count_all_results("company_breaks")) {
                $response["msg"] = "Break already exists.";
            } else {
                // insert
                $this->db
                    ->insert("company_breaks", [
                        "company_sid" => $companyId,
                        "break_name" => $post["break_name"],
                        "break_duration" => $post["break_duration"],
                        "break_type" => $post["break_type"],
                        "created_at" => getSystemDate(),
                        "updated_at" => getSystemDate(),
                    ]);
                // check and insert log
                if ($this->db->insert_id()) {
                    //
                    $status = 200;
                    $response = ["msg" => "You have successfully add a new break."];
                }
            }
        }
        //
        return SendResponse($status, $status === 400 ? ["errors" => [$response["msg"]]] : $response);
    }

    /**
     * delete
     *
     * @param int $companyId
     * @param int $breakId
     * @return array
     */
    public function delete(
        int $companyId,
        int $breakId
    ): array {
        //
        $status = 400;
        $response = ["msg" => "Something went wrong while deleting break."];
        // check if entry already exists
        if (!$this->db->where(["company_sid" => $companyId, "sid" => $breakId])->count_all_results("company_breaks")) {
            $response["msg"] = "System failed to verify the break.";
        } else {
            // update
            $this->db
                ->where("sid", $breakId)
                ->delete("company_breaks");

            $status = 200;
            $response = ["msg" => "You have successfully deleted the break."];
        }

        //
        return SendResponse($status, $status === 400 ? ["errors" => [$response["msg"]]] : $response);
    }
}
