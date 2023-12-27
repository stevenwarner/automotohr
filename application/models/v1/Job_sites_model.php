<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Job Sites model
 * 
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @version 1.0
 * @package Time & Clock
 */
class Job_sites_model extends CI_Model
{
    /**
     * holds the table name
     * @var string
     */
    private $tableName;

    /**
     * holds the fetch record fields
     * @var array
     */
    private $fetchFields;

    /**
     * Main entry point
     */
    public function __construct()
    {
        // inherit parent
        parent::__construct();
        // set the table name
        $this->tableName = "company_job_sites";
        // set the get fields
        $this->fetchFields = [
            "company_job_sites.site_name",
            "company_job_sites.street_1",
            "company_job_sites.street_2",
            "company_job_sites.city",
            "company_job_sites.zip_code",
            "company_job_sites.state",
            "company_job_sites.site_radius",
            "company_job_sites.lat",
            "company_job_sites.lng",
        ];
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
            ->select("{$this->tableName}.sid")
            ->select("states.state_code")
            ->select($this->fetchFields)
            ->where("{$this->tableName}.company_sid", $companyId)
            ->join("states", "states.sid = {$this->tableName}.state", "inner")
            ->order_by("{$this->tableName}.sid", "DESC")
            ->get($this->tableName)
            ->result_array();
    }

    /**
     * get the overtime rules
     *
     * @param int $companyId
     * @param int $jobSiteId
     * @return array
     */
    public function getSingle(
        int $companyId,
        int $jobSiteId
    ): array {
        return $this->db
            ->select($this->fetchFields)
            ->where("company_sid", $companyId)
            ->where("sid", $jobSiteId)
            ->get($this->tableName)
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
        $response = ["msg" => "Something went wrong while updating job site."];
        // set where
        $where = [
            "company_sid" => $companyId,
            "LOWER(REGEXP_REPLACE(site_name, '[^a-zA-Z0-9]', '')) = " => strtolower(
                preg_replace(
                    '/[^a-z]/i',
                    '',
                    $post["site_name"],
                )
            ),
        ];
        // set the insert array
        $ins = [];
        $ins["site_name"] = $post["site_name"];
        $ins["street_1"] = $post["street_1"];
        $ins["street_2"] = $post["street_2"];
        $ins["city"] = $post["city"];
        $ins["state"] = $post["state"];
        $ins["zip_code"] = $post["zip_code"];
        $ins["site_radius"] = $post["site_radius"];
        $ins["lat"] = $post["lat"];
        $ins["lng"] = $post["lng"];
        $ins["updated_at"] = getSystemDate();
        //
        if ($post["id"]) {
            // update
            // check if entry already exists
            if ($this->db->where($where)->where("sid <>", $post["id"])->count_all_results($this->tableName)) {
                $response["msg"] = "Job site already exists.";
            } else {
                // update
                $this->db
                    ->where("sid", $post["id"])
                    ->update($this->tableName, $ins);

                $status = 200;
                $response = ["msg" => "You have successfully updated job site."];
            }
        } else {
            // insert
            // check if entry already exists
            if ($this->db->where($where)->count_all_results($this->tableName)) {
                $response["msg"] = "Job site already exists.";
            } else {
                //
                $ins["company_sid"] = $companyId;
                $ins["created_at"] = $ins["updated_at"];
                // insert
                $this->db
                    ->insert($this->tableName, $ins);
                // check and insert log
                if ($this->db->insert_id()) {
                    //
                    $status = 200;
                    $response = ["msg" => "You have successfully add a new job site."];
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
     * @param int $jobSiteId
     * @return array
     */
    public function delete(
        int $companyId,
        int $jobSiteId
    ): array {
        //
        $status = 400;
        $response = ["msg" => "Something went wrong while deleting job site."];
        // check if entry already exists
        if (!$this->db->where(["company_sid" => $companyId, "sid" => $jobSiteId])->count_all_results($this->tableName)) {
            $response["msg"] = "System failed to verify the job site.";
        } else {
            // update
            $this->db
                ->where("sid", $jobSiteId)
                ->delete($this->tableName);

            $status = 200;
            $response = ["msg" => "You have successfully deleted the job site."];
        }

        //
        return SendResponse($status, $status === 400 ? ["errors" => [$response["msg"]]] : $response);
    }
}
