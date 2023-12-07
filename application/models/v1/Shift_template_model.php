<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Shift template model
 * 
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @version 1.0
 * @package Time & Clock
 */
class Shift_template_model extends CI_Model
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
            ->select("
                sid,
                start_time,
                end_time,
                breaks_json,
                breaks_count,
                notes,
            ")
            ->where("company_sid", $companyId)
            ->order_by("sid", "DESC")
            ->get("company_shift_templates")
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
                start_time,
                end_time,
                breaks_json,
                breaks_count,
                notes,
            ")
            ->where("company_sid", $companyId)
            ->where("sid", $wageId)
            ->get("company_shift_templates")
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
        $post["start_time"] = formatDateToDB(
            $post["start_time"],
            "h:i A",
            "H:i"
        );
        $post["end_time"] = formatDateToDB(
            $post["end_time"],
            "h:i A",
            "H:i"
        );
        //
        $post["breaks"] = array_values($post["breaks"]);
        //
        if ($post["breaks"]) {
            foreach($post["breaks"] as $index => $break) {
                if ($break["start_time"]) {
                    $time = new DateTime();
                    $time->add(new DateInterval('PT' . $break["duration"] . 'M'));
                    $post["breaks"][$index]["end_time"] = $time->format('H:i');
                    $post["breaks"][$index]["start_time"] = formatDateToDB(
                        $break["start_time"],
                        "h:i a",
                        "H:i"
                    );
                }
            }
        }
        //
        $status = 400;
        $response = ["msg" => "Something went wrong while updating shift template."];
        //
        if ($post["id"]) {
            // update
            // check if entry already exists
            if ($this->db->where([
                "start_time" => $post["start_time"],
                "end_time" => $post["end_time"],
            ])->where("sid <>", $post["id"])->count_all_results("company_shift_templates")) {
                $response["msg"] = "Break already exists.";
            } else {
                // update
                $this->db
                    ->where("sid", $post["id"])
                    ->update("company_shift_templates", [
                        "start_time" => $post["start_time"],
                        "end_time" => $post["end_time"],
                        "breaks_json" => json_encode($post["breaks"]),
                        "breaks_count" => count($post["breaks"]),
                        "notes" => $post["notes"],
                        "updated_at" => getSystemDate(),
                    ]);

                $status = 200;
                $response = ["msg" => "You have successfully updated shift template."];
            }
        } else {
            // insert
            // check if entry already exists
            if ($this->db->where([
                "start_time" => $post["start_time"],
                "end_time" => $post["end_time"],
            ])->count_all_results("company_shift_templates")) {
                $response["msg"] = "Break already exists.";
            } else {
                // insert
                $this->db
                    ->insert("company_shift_templates", [
                        "company_sid" => $companyId,
                        "start_time" => $post["start_time"],
                        "end_time" => $post["end_time"],
                        "breaks_json" => json_encode($post["breaks"]),
                        "breaks_count" => count($post["breaks"]),
                        "notes" => $post["notes"],
                        "created_at" => getSystemDate(),
                        "updated_at" => getSystemDate(),
                    ]);
                // check and insert log
                if ($this->db->insert_id()) {
                    //
                    $status = 200;
                    $response = ["msg" => "You have successfully add a new shift template."];
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
     * @param int $shiftTemplateId
     * @return array
     */
    public function delete(
        int $companyId,
        int $shiftTemplateId
    ): array {
        //
        $status = 400;
        $response = ["msg" => "Something went wrong while deleting shift template."];
        // check if entry already exists
        if (!$this->db->where(["company_sid" => $companyId, "sid" => $shiftTemplateId])->count_all_results("company_shift_templates")) {
            $response["msg"] = "System failed to verify the break.";
        } else {
            // update
            $this->db
                ->where("sid", $shiftTemplateId)
                ->delete("company_shift_templates");

            $status = 200;
            $response = ["msg" => "You have successfully deleted the shift template."];
        }

        //
        return SendResponse($status, $status === 400 ? ["errors" => [$response["msg"]]] : $response);
    }
}
