<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Summary of Job_sync_api_model
 */
class Job_sync_api_model extends CI_Model
{
    private $jobId;
    private $job;
    /**
     * Summary of __construct
     */
    public function __construct()
    {
        parent::__construct();
        //
        $this->jobId = 0;
        $this->job = [];
    }

    /**
     * Summary of checkAndAddJobs
     * @param mixed $jobIds
     * @return array<array{errors: array|bool>}
     */
    public function checkAndAddJobs($jobIds): array
    {
        // check if the job ids are empty
        if (empty($jobIds)) {
            return $this->getError("Job IDs are required");
        }
        $responseArray = [];
        //
        foreach ($jobIds as $jobId) {
            $responseArray[] = $this->checkAndAddJob($jobId);
        }
        //
        return $responseArray;
    }

    public function checkAndAddJob(int $jobId)
    {
        // set the job id
        $this->jobId = $jobId;
        // check if the job exists
        if (!$this->jobId) {
            return $this->getError("Job ID is required");
        }
        // load the job
        $this->setJob();
        // check if the job is allowed
        if (!$this->job) {
            return $this->getError("Job not found");
        }
        // check if the job is allowed
        // if the job is allowed, add it to the queue
        // if the job is not allowed, expire it
        return $this->checkAndProcessJob();
    }


    /**
     * Summary of loadJob
     * @return void
     */
    private function setJob()
    {
        $this->job = $this
            ->db
            ->select([
                "portal_job_listings.sid",
                "portal_job_listings.active",
                "portal_job_listings.approval_status",
                "portal_job_listings.user_sid",
                "users.CompanyName",
                "users.has_job_approval_rights",
            ])
            ->where([
                "portal_job_listings.sid" => $this->jobId,
                "portal_job_listings.active" => 1, // active job
                "portal_job_listings.organic_feed" => 1, // organic feed
                "users.is_paid" => 1, // main company
                "users.career_site_listings_only" => 0, // not career site only
                "users.active" => 1, // company is active
            ])
            ->group_start()
            ->where("users.expiry_date >=", "2016-04-20 13:26:27")
            ->or_where("users.expiry_date is null", null)
            ->group_end()
            ->from("portal_job_listings")
            ->join(
                "users",
                "users.sid = portal_job_listings.user_sid",
                "inner"
            )
            ->get()
            ->row_array();
    }

    /**
     * Summary of checkAndProcessJob
     * @return bool
     */
    private function checkAndProcessJob()
    {
        if ($this->job["has_job_approval_rights"] == 1) {
            if ($this->job["approval_status"] == "approved") {
                return $this->checkAndAddJobToQueue();
            } else {
                return $this->checkAndExpireJob();
            }
        } else {
            return $this->checkAndAddJobToQueue();
        }
    }

    private function checkAndAddJobToQueue()
    {
        // check if job is already in the queue
        if (
            !$this->db
                ->where("job_sid", $this->jobId)
                ->count_all_results("indeed_job_queue")
        ) {
            // add when the job was not found
            return $this->addJobToQueue();
        }
        //
        return $this->updateJobInQueue();
    }

    /**
     * Summary of addJobToQueue
     * @return void
     */
    private function addJobToQueue()
    {
        // set current date and time
        $dateWithTime = getSystemDate();
        // add the job to the queue
        $this->db
            ->insert(
                "indeed_job_queue",
                [
                    "job_sid" => $this->jobId,
                    "log_sid" => null,
                    "is_processed" => 0,
                    "is_expired" => 0,
                    "errors" => null,
                    "has_errors" => 0,
                    "processed_at" => null,
                    "is_processing" => 0,
                    "created_at" => $dateWithTime,
                    "updated_at" => $dateWithTime,
                ]
            );
        // increase the counter
        $this->db->query("
            UPDATE `indeed_job_queue_count`
            SET `total_unprocessed_jobs` = `total_unprocessed_jobs` + 1
            WHERE `sid` = 1;
        ");
        return $this->getSuccess("Job added to queue");
    }

    /**
     * Summary of updateJobInQueue
     * @return bool
     */
    private function updateJobInQueue()
    {
        //
        $dateWithTime = getSystemDate();
        // check if the job is processed
        // check if the job is processing and has errors
        if (
            $this->db
                ->group_start()
                ->where("is_processed", 1)
                ->or_group_start()
                ->where("is_processing", 1)
                ->where("has_errors", 1)
                ->group_end()
                ->group_end()
                ->where("job_sid", $this->jobId)
                ->count_all_results("indeed_job_queue")
        ) {
            // move the record to history
            $this->db->query("
                INSERT INTO `indeed_job_queue_history`
                (`job_sid`,
                `log_sid`,
                `is_processed`,
                `is_expired`,
                `is_processing`,
                `errors`,
                `has_errors`,
                `processed_at`,
                `created_at`,
                `updated_at`)
                SELECT `job_sid`,
                `log_sid`,
                `is_processed`,
                `is_expired`,
                `is_processing`,
                `errors`,
                `has_errors`,
                `processed_at`,
                `created_at`,
                `updated_at`
                FROM
                `indeed_job_queue`
            ");
            // update the record
            $this->db
                ->where("job_sid", $this->jobId)
                ->update(
                    "indeed_job_queue",
                    [
                        "log_sid" => null,
                        "is_processed" => 0,
                        "is_expired" => 0,
                        "errors" => null,
                        "has_errors" => 0,
                        "processed_at" => null,
                        "is_processing" => 0,
                        "updated_at" => $dateWithTime,
                    ]
                );
            //
            return $this->getSuccess("Job updated in queue and moved to history");
        }
        // update the record
        $this->db
            ->where("job_sid", $this->jobId)
            ->update(
                "indeed_job_queue",
                [
                    "is_expired" => 0,
                    "errors" => null,
                    "updated_at" => getSystemDate()
                ]
            );
        return $this->getSuccess("Job updated in queue");

    }

    /**
     * Summary of checkAndExpireJob
     * @return bool
     */
    private function checkAndExpireJob()
    {
        // check if job already exists in the queue and is not expired
        if (
            $this->db
                ->where("job_sid", $this->jobId)
                ->where("is_expired <>", 1)
                ->count_all_results("indeed_job_queue")
        ) {
            // set update array
            $updateArray = [];
            $updateArray["is_expired"] = 1;
            $updateArray["is_processed"] = 0;
            $updateArray["is_processing"] = 0;
            $updateArray["processed_at"] = null;
            $updateArray["has_errors"] = 0;
            $updateArray["errors"] = null;
            $updateArray["updated_at"] = getSystemDate();
            //
            // check wether it was processed or not
            if (
                $this->db
                    ->where("job_sid", $this->jobId)
                    ->where("is_processed", 1)
                    ->count_all_results("indeed_job_queue")
            ) {
                $isProcessed = 1;
                // move the record to history
                $this->db->query("
                    INSERT INTO `indeed_job_queue_history`
                    (`job_sid`,
                    `log_sid`,
                    `is_processed`,
                    `is_expired`,
                    `has_errors`,
                    `processed_at`,
                    `created_at`,
                    `updated_at`)
                    SELECT `job_sid`,
                    `log_sid`,
                    `is_processed`,
                    `is_expired`,
                    `has_errors`,
                    `processed_at`,
                    `created_at`,
                    `updated_at`
                    FROM
                    `indeed_job_queue`
                ");
            }
            // mark the job expired
            $this->db
                ->where("job_sid", $this->jobId)
                ->update(
                    "indeed_job_queue",
                    $updateArray
                );
            return $this->getSuccess("Job expired and moved to history");
        }
        return $this->getError("Job already expired");
    }

    /**
     * Summary of getError
     * @param string $error
     * @return array{errors: string[]}
     */
    private function getError(string $error): array
    {
        return [
            "errors" => [
                $error
            ]
        ];
    }

    /**
     * Summary of getSuccess
     * @param string $message
     * @return array{success: string}
     */
    private function getSuccess(string $message): array
    {
        return [
            "success" => $message
        ];
    }
}
