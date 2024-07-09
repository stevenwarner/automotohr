<?php

class Indeed_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    //

    /**
     * Insert job budget
     * @param Array $post
     * @return Integer
     */
    function insertJobBudget($post)
    {
        $insertArray = array();
        $insertArray['budget'] = $post['budget'];
        $insertArray['job_sid'] = $post['jobSid'];
        $insertArray['charged_by'] = $post['employeeSid'];
        $insertArray['start_date'] = date('Y-m-d');
        if ($post['budgetPerDay'] != 0)
            $insertArray['expire_date'] = date('Y-m-d', strtotime('+' . ($post['budgetPerDay']) . ' days'));
        else $insertArray['expire_date'] = date('Y-m-d', strtotime('+30 days'));
        $insertArray['budget_days'] = $post['budgetPerDay'];
        if ($post['phoneNumber'] != '')
            $insertArray['phone_number'] = $post['phoneNumber'];
        // _e($insertArray, true, true);
        //
        $this->db->insert('portal_job_indeed', $insertArray);
        //
        $insertSid = $this->db->insert_id();
        //
        $this->db
            ->where('sid', $post['jobSid'])
            ->update('portal_job_listings', array(
                'indeed_sponsored' => 1
            ));
        //
        return $insertSid;
    }

    function getJobBudgetAndExpireOldBudget($jobSid)
    {
        $result =
            $this->db
            ->select('
            sid as budget_sid,
            budget,
            budget_days,
            expire_date,
            phone_number
        ')
            ->from('portal_job_indeed')
            ->where('job_sid', $jobSid)
            ->where('expire_date > CURDATE() OR expire_date IS NULL', null)
            ->order_by('sid', 'DESC')
            ->limit(1)
            ->get();
        //
        $budget = $result->row_array();
        $result = $result->free_result();
        //
        if (!sizeof($budget)) $this->db->where('sid', $jobSid)->update('portal_job_listings', array('indeed_sponsored' => 0));
        return $budget;
    }

    /**
     * Get Indeed Paid jobs
     * @return Array
     */
    function getIndeedPaidJobIds()
    {
        $result = $this->db
            ->select('job_sid, budget')
            ->from('portal_job_indeed')
            ->order_by('sid', 'DESC')
            ->get();
        //
        $jobs = $result->result_array();
        $result = $result->free_result();
        //
        if (sizeof($jobs)) {
            $ids = array();
            $budget = array();
            //
            foreach ($jobs as $k0 => $v0) {
                // Set budget
                if (isset($budget[$v0['job_sid']])) $budget[$v0['job_sid']] += $v0['budget'];
                else $budget[$v0['job_sid']] = $v0['budget'];

                // Set Ids
                $ids[] = $v0['job_sid'];
            }
            //
            return array(
                'Ids' => $ids,
                'Budget' => $budget
            );
        }
        return array(
            'Ids' => array(),
            'Budget' => array()
        );
    }

    /**
     * Get active Indeed paid jobs
     * @param Array $paidJobIds
     * @return Array
     */
    function getIndeedPaidJobs($paidJobIds = array())
    {
        $result = $this->db->where('active', 1)
            // ->where_in('sid', $paidJobIds)
            ->where('indeed_sponsored', 1)
            ->order_by('sid', 'desc')
            ->get('portal_job_listings');
        //
        $jobs = $result->result_array();
        $result = $result->free_result();
        //
        return $jobs;
    }


    /**
     * Get active Indeed organic jobs
     * @param Array $paidJobIds
     * @return Array
     */
    function getIndeedOrganicJobs($paidJobIds = array())
    {
        $this->db->where('active', 1)
            ->where('organic_feed', 1);
        // ->where('indeed_sponsored', 0)
        if (!empty($paidJobIds)) {
            $this->db->where_not_in('sid', $paidJobIds);
        }
        $this->db->order_by('sid', 'desc');
        $result = $this->db->get('portal_job_listings');
        //
        $jobs = $result->result_array();
        $result = $result->free_result();
        //
        return $jobs;
    }


    /**
     * Get all active companies
     * @return Array
     */
    function getAllActiveCompanies($feedSid)
    {
        $result = $this->db->query("SELECT `sid` FROM `users` WHERE `parent_sid` = '0' AND `is_paid`='1' AND `career_site_listings_only` = 0 AND `active` = '1' AND (`expiry_date` > '2016-04-20 13:26:27' OR `expiry_date` IS NULL)")->result_array();
        if (count($result) > 0) {
            $data = array();
            foreach ($result as $r) {
                // Check if feed is allowed
                if ($this->db
                    ->where('company_sid', $r['sid'])
                    ->where('feed_sid', $feedSid)
                    ->where('status', 0)
                    ->count_all_results('feed_restriction')
                ) {
                    continue;
                }
                $data[] = $r['sid'];
            }
            return $data;
        } else {
            return 0;
        }
    }

    /**
     * Get company details
     * @param  Integer $companySid
     * @return Array
     */
    function getPortalDetail($companySid)
    {
        $result = $this->db
            ->where('user_sid', $companySid)
            ->get('portal_employer');
        //
        $portal = $result->row_array();
        $result = $result->free_result();
        //
        return $portal;
    }

    /**
     * Get company approval rights status
     * @param  Integer $companySid
     * @return Array
     */
    function getCompanyNameAndJobApproval($companySid)
    {
        $result = $this->db
            ->select('CompanyName, has_job_approval_rights, ContactName as full_name, PhoneNumber as phone_number, email')
            ->where('sid', $companySid)
            ->get('users');
        //
        $record = $result->row_array();
        $result = $result->free_result();
        //
        return $record;
    }

    /**
     * Get UID of Job
     * @param  Integer $jobSid
     * @return Array
     */
    function fetchUidFromJobSid($jobSid)
    {
        $result = $this->db
            ->select('uid, publish_date')
            ->where('job_sid', $jobSid)
            ->where('active', 1)
            ->order_by('sid', 'desc')
            ->limit(1)
            ->get('portal_job_listings_feeds_data');
        //
        $record = $result->row_array();
        $result = $result->free_result();
        //
        return $record;
    }

    /**
     * Get UID of Job
     * @param  Integer $jobSid
     * @return Array
     */
    function getJobIdByUid($jobUid)
    {
        $result = $this->db
            ->select('job_sid')
            ->where('uid', $jobUid)
            ->where('active', 1)
            ->order_by('sid', 'desc')
            ->limit(1)
            ->get('portal_job_listings_feeds_data');
        //
        $record = $result->row_array();
        $result = $result->free_result();
        //
        return $record;
    }

    function getPhonenumber($jobSid, $companySid)
    {
        $r = array('email' => '', 'phone_number' => '');
        $a = $this->db
            ->select('phone_number')
            ->where('job_sid', $jobSid)
            ->get('portal_job_indeed');
        //
        $b = $a->row_array();
        $a = $a->free_result();
        //
        $a = $this->db
            ->select('PhoneNumber')
            ->where('parent_sid', $companySid)
            ->where("PhoneNumber != ''", null)
            ->order_by('is_primary_admin', 'DESC')
            ->get('users');
        //
        $c = $a->row_array();
        $a = $a->free_result();

        //
        // $a = $this->db
        // ->select('email')
        // ->where('parent_sid', $companySid)
        // ->where("email != ''", null)
        // ->order_by('is_primary_admin', 'DESC')
        // ->get('users');
        // //
        // $d = $a->row_array();
        // $a = $a->free_result();
        //
        if (sizeof($b)) $r['phone_number'] = $b['phone_number'];
        // if(sizeof($d)) $r['email'] = $d['email'];
        if (!sizeof($b) && sizeof($c)) $r['phone_number'] = $c['PhoneNumber'];
        //
        return $r;
    }

    /**
     * Budget details
     * @param Array $formpost
     * @return Decimal
     */
    function isBudgetExists($formpost)
    {
        $result = $this->db
            ->select('budget')
            ->where('sid', $formpost['budgetSid'])
            ->get('portal_job_indeed');
        //
        $budget = $result->row_array();
        $result = $result->free_result();
        //
        return sizeof($budget) ? $budget['budget'] : 0;
    }

    /**
     * Update budget details
     * @return VOID
     */
    function updateBudget($post)
    {
        $updateArray = array();
        $updateArray['budget'] = $post['budget'];
        $updateArray['job_sid'] = $post['jobSid'];
        $updateArray['charged_by'] = $post['employeeSid'];
        if ($post['budgetPerDay'] != 0)
            $updateArray['expire_date'] = date('Y-m-d', strtotime('+' . ($post['budgetPerDay']) . ' days'));
        else $insertArray['expire_date'] = date('Y-m-d', strtotime('+30 days'));
        $updateArray['budget_days'] = $post['budgetPerDay'];
        if ($post['phoneNumber'] != '')
            $updateArray['phone_number'] = $post['phoneNumber'];
        //
        $this->db
            ->where('sid', $post['budgetSid'])
            ->update('portal_job_indeed', $updateArray);
        //
        $this->db
            ->where('sid', $post['jobSid'])
            ->update('portal_job_listings', array(
                'indeed_sponsored' => 1
            ));
    }

    //
    function GetCompanyIndeedDetails($companyId, $jobId)
    {
        //
        $r = ['Phone' => '', 'Email' => '', 'Name' => ''];
        //
        $details = $this->db->where('company_sid', $companyId)->get('company_indeed_details')->row_array();
        //
        if (!empty($details)) {
            $r['Name'] = $details['contact_name'];
            $r['Email'] = $details['contact_email'];
            $r['Phone'] = $details['contact_phone'];
        } else {
            $details = $this->db
                ->select('phone_number')
                ->where('job_sid', $jobId)
                ->get('portal_job_indeed')
                ->row_array();
            //
            if (!empty($details)) {
                $r['Phone'] = $details['phone_number'];
            }
        }

        return $r;
    }

    /**
     * get the candidate questionnaire by job id
     *
     * @param int $jobId
     * @return array
     */
    public function getCandidateQuestionnaireByJobId(int $jobId)
    {
        // get the job questionnaire
        $result = $this->db
            ->select("portal_job_listings.questionnaire_sid")
            ->where("portal_job_listings.sid", $jobId)
            ->get("portal_job_listings")
            ->row_array();
        // when no screening question id found
        if (!$result || !$result["questionnaire_sid"]) {
            return [];
        }
        // get the questions with options
        return $this->getScreeningQuestionsWithOptions($result["questionnaire_sid"]);
    }

    /**
     * get the candidate questionnaire by id
     *
     * @param int $screeningQuestionnaireId
     * @return array
     */
    public function getScreeningQuestionsWithOptions(int $screeningQuestionnaireId)
    {
        // get the questionnaire
        $result = $this->db
            ->select("sid, caption, is_required, question_type")
            ->where("questionnaire_sid", $screeningQuestionnaireId)
            ->get("portal_questions")
            ->result_array();
        // when no screening questions found
        if (!$result) {
            return [];
        }
        // set question array
        $questionArray = [];
        // loop through the data
        foreach ($result as $v0) {
            // set the question info
            $question = [
                "id" => (int) $v0["sid"],
                "type" => getIndeedMappedQuestionType($v0["question_type"]),
                "question" => $v0["caption"],
            ];
            if ($v0["is_required"]) {
                $question["required"] = true;
            }
            //
            if ($v0["question_type"] != "string") {
                // get the options
                $options = $this->getScreeningQuestionOptions(
                    $screeningQuestionnaireId,
                    $v0["sid"]
                );
                // for boolean
                $question["options"] = [];
                // loop through options
                foreach ($options as $v1) {
                    $question["options"][] = [
                        "label" => $v1["value"],
                        "value" => $v1["score"]
                    ];
                }
            }
            // add question to the array
            $questionArray[] = $question;
        }
        //
        return $questionArray;
    }

    /**
     * get the candidate questionnaire by id
     *
     * @param int $screeningQuestionnaireId
     * @param int $screeningQuestionnaireOptionId
     * @return array
     */
    public function getScreeningQuestionOptions(
        int $screeningQuestionnaireId,
        int $screeningQuestionnaireOptionId
    ) {
        // get the job questionnaire
        return $this->db
            ->select("value, score, result_status")
            ->where("questionnaire_sid", $screeningQuestionnaireId)
            ->where("questions_sid", $screeningQuestionnaireOptionId)
            ->get("portal_question_option")
            ->result_array();
    }

    /**
     * get the job details by id
     *
     * @param int $jobId
     * @param array $columns Optional
     * @return array
     */
    public function getJobDetailsById(
        int $jobId,
        array $columns = ["*"]
    ) {
        // get the job questionnaire
        return $this->db
            ->select($columns)
            ->where("sid", $jobId)
            ->get("portal_job_listings")
            ->row_array();
    }

    /**
     * get company demographic questions
     *
     * @param int $companyId
     * @return array
     */
    public function getCompanyDemographicQuestions(
        int $companyId
    ) {
        // check if company has enabled
        // EEOC
        if (!$this->hasEEOCEnabled($companyId)) {
            // in case of off
            return [];
        }
        // get the fields
        $fields = $this->db
            ->select("
                dl_citizen,
                dl_vet,
                dl_vol,
                dl_gen,
            ")
            ->where("user_sid", $companyId)
            ->get("portal_employer")
            ->row_array();
        // get the EEOC form questions
        return getEEOCFormQuestions($fields);
    }

    /**
     * get company demographic questions
     *
     * @param int $companyId
     * @return array
     */
    public function hasEEOCEnabled(
        int $companyId
    ) {
        // check if company has enabled
        // EEOC
        return $this->db
            ->select("eeo_form_status")
            ->where("eeo_form_status", 1)
            ->where("user_sid", $companyId)
            ->count_all_results("portal_employer");
    }


    /**
     * Push the applicant status to Indeed
     * using disposition API
     *
     * @param string $status
     * @param int $applicantListId
     */
    public function pushTheApplicantStatus(
        string $status,
        int $applicantListId
    ) {
        return ["success" => true];
        // check if an applicant has Indeed ATS Id
        if (!$this->db
            ->where("sid", $applicantListId)
            ->where("indeed_ats_sid <>", null)
            ->count_all_results("portal_applicant_jobs_list")) {
            return [
                "error" => "Indeed ATS id not found."
            ];
        }
        // get the indeed ats id
        $indeedAtsId = $this->db
            ->select("indeed_ats_sid")
            ->where("sid", $applicantListId)
            ->where("indeed_ats_sid <>", null)
            ->get("portal_applicant_jobs_list")
            ->row_array()["indeed_ats_sid"];
        // load the library
        $this->load->library("Indeed_lib");
        // send the call
        $response = $this->indeed_lib
            ->sendDispositionCall(
                $indeedAtsId,
                "NEW"
            );
        // when error occurred
        if ($response["error"]) {
            return $response;
        }
        return $response;
    }

    /**
     * save and retrieve questionnaires against job
     *
     * @param int $jobId
     * @param int $companyId
     * @param bool $createFile
     * Optional: Default is false
     */
    public function saveQuestionIntoFile(
        $jobId,
        $companyId,
        $createFile = false
    ) {
        // set the folder path
        $folder = ROOTPATH . '../protected_files/jobs/';
        // set the file name
        $fileName =  $jobId . '.json';
        // create the file
        if ($createFile) {
            // check and get demographic questions
            $demographicQuestions = $this->indeed_model
                ->getCompanyDemographicQuestions(
                    $companyId
                );
            // get the job questionnaires
            $screeningQuestionnaire = $this->indeed_model
                ->getCandidateQuestionnaireByJobId($jobId);
            // when no questionnaire is available
            if (!$screeningQuestionnaire && !$demographicQuestions) {
                //
                return false;
            }
            // check and create the folder
            if (!is_dir($folder)) {
                mkdir($folder, 0777, true);
            }
            // create the file
            // if file already exists it overwrites
            $handler = fopen($folder . $fileName, 'w');
            // set the json
            $questionArray = [
                "schemaVersion" => "1.0",
            ];
            // check and set screening screeningQuestionnaire
            if ($screeningQuestionnaire && !$this->checkQuestionAreValidForIndeed($screeningQuestionnaire)) {
                $questionArray["screenerQuestions"] = [
                    "questions" => $screeningQuestionnaire
                ];
            }
            // check and set demographic questions
            if ($demographicQuestions) {
                $questionArray["demographicQuestions"] = [
                    "questions" => $demographicQuestions
                ];
            }
            // write the file data
            fwrite($handler, json_encode($questionArray));
            // close the file stream
            fclose($handler);
            //
            return true;
        }
        // retrieve file
        // check if the requested file exists
        if (!file_exists($folder . $fileName)) {
            // send the response to indeed
            // in case no questions were found
            return SendResponse(
                400,
                [
                    "errors" => [
                        "No screening or demographic questions found."
                    ]
                ]
            );
        }
        // get the file data
        $fileData = json_decode(
            loadFileData($folder . $fileName),
            true
        );
        // send the response to Indeed
        SendResponse(
            200,
            $fileData
        );
    }

    /**
     * 
     */
    private function checkQuestionAreValidForIndeed($screeningQuestionnaire)
    {
        //
        $result = false;
        //
        if ($screeningQuestionnaire) {
            //
            foreach ($screeningQuestionnaire as $question) {
                //
                if ($question['options']) {
                    //
                    $answers = [];
                    $break = 'no';
                    //
                    foreach ($question['options'] as $option) {
                        if (in_array($option['value'], $answers)) {
                            $result = true;
                            $break = 'yes';
                            break;
                        }
                        //
                        $answers[] = $option['value'];
                    }
                    //
                    if ($break == 'yes') {
                        break;
                    }
                }
            }
        }
        //
        return $result;
    }

    /**
     * add the job onto the queue
     *
     * @param int $jobId
     * @param int $companyId
     * @param string $jobApprovalStatus
     * @param bool $byPassApprovalStatus Optional - Default is 'false'
     * @return array
     */
    public function addJobToQueue(
        int $jobId,
        int $companyId,
        string $jobApprovalStatus,
        bool $byPassApprovalStatus = false
    ): array {
        //
        // check if job is allowed to be added to queue
        if (!$byPassApprovalStatus && $this->getJobApprovalStatus($companyId) && $jobApprovalStatus != 'approved') {
            return ["errors" => [
                "The job is not approved."
            ]];
        }
        // set current date and time
        $dateWithTime = getSystemDate();
        // add the job to the queue
        $this->db
            ->insert(
                "indeed_job_queue",
                [
                    "job_sid" => $jobId,
                    "log_sid" => 0,
                    "is_processed" => 0,
                    "is_expired" => 0,
                    "has_errors" => 0,
                    "processed_at" => null,
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

        return [
            "success" => "The job is successfully added to the queue."
        ];
    }

    /**
     * update the job onto the queue
     *
     * @param int $jobId
     * @param int $companyId
     * @param string $jobApprovalStatus
     * @return array
     */
    public function updateJobToQueue(
        int $jobId,
        int $companyId,
        string $jobApprovalStatus
    ): array {
        // check if job is allowed to be added to queue
        if ($this->getJobApprovalStatus($companyId) && $jobApprovalStatus != 'approved') {
            return ["errors" => [
                "The job is not approved."
            ]];
        }
        // set current date and time
        $dateWithTime = getSystemDate();
        // check if job already exists
        if (
            !$this->db
                ->where("job_sid", $jobId)
                ->count_all_results("indeed_job_queue")
        ) {
            // add when the job was not found
            return $this->addJobToQueue($jobId, $companyId, $jobApprovalStatus, true);
        }
        // check if the job is processed
        if ($this->db
            ->where("is_processed", 1)
            ->where("job_sid", $jobId)
            ->count_all_results("indeed_job_queue")
        ) {
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
            // update the record
            $this->db
                ->where("job_sid", $jobId)
                ->update(
                    "indeed_job_queue",
                    [
                        "log_sid" => null,
                        "is_processed" => 0,
                        "is_expired" => 0,
                        "has_errors" => 0,
                        "processed_at" => null,
                        "updated_at" => $dateWithTime,
                    ]
                );
            // manage the counter
            $this->db->query("
                UPDATE `indeed_job_queue_count`
                SET `total_unprocessed_jobs` = `total_unprocessed_jobs` + 1
                WHERE `sid` = 1;
            ");
            $this->db->query("
                UPDATE `indeed_job_queue_count`
                SET `total_processed_jobs` = `total_processed_jobs` - 1
                WHERE `sid` = 1;
            ");
            //
            return [
                "success" => "The job is successfully updated to the queue."
            ];
        }
        //
        return [
            "success" => "The job was already ready to be processed."
        ];
    }

    /**
     * expires the job in queue
     *
     * @param int $jobId
     * @return array
     */
    public function expireJobToQueue(
        int $jobId
    ): array {
        // check if job already exists
        if (
            $this->db
            ->where("job_sid", $jobId)
            ->where("is_expired <>", 1)
            ->count_all_results("indeed_job_queue")
        ) {
            // set update array
            $updateArray = [];
            $updateArray["is_expired"] = 1;
            $updateArray["is_processed"] = 0;
            $updateArray["processed_at"] = null;
            //
            $isProcessed = 0;
            // check wether it was processed or not
            if ($this->db
                ->where("job_sid", $jobId)
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
                ->where("job_sid", $jobId)
                ->update(
                    "indeed_job_queue",
                    $updateArray
                );
            // increase the expire counter
            $this->db->query("
                UPDATE `indeed_job_queue_count`
                SET `total_expired_jobs` = `total_expired_jobs` + 1
                WHERE `sid` = 1;
            ");
            //
            if ($isProcessed) {
                $this->db->query("
                    UPDATE `indeed_job_queue_count`
                    SET `total_processed_jobs` = `total_processed_jobs` - 1
                    WHERE `sid` = 1;
                ");
                $this->db->query("
                    UPDATE `indeed_job_queue_count`
                    SET `total_unprocessed_jobs` = `total_unprocessed_jobs` + 1
                    WHERE `sid` = 1;
                ");
            }
            //
            return [
                "success" => "The job is added to be expired."
            ];
        }
        return [
            "success" => "The job was either already expired / not added yet."
        ];
    }

    /**
     * Get company job approval rights status
     *
     * @param  int $companySid
     * @return bool
     */
    public function getJobApprovalStatus(int $companyId): bool
    {
        return $this->db
            ->where('has_job_approval_rights', 1)
            ->where('sid', $companyId)
            ->count_all_results('users');
    }

    /**
     * check if already something running
     *
     * @return int
     */
    public function checkQueue(): int
    {
        return $this
            ->db
            ->where([
                "is_processing" => 1
            ])
            ->count_all_results(
                "indeed_job_queue"
            );
    }

    /**
     * check if already something running
     *
     * @param int $numberOfJobs
     * @return array
     */
    public function getQueueJobs(
        int $numberOfJobs = 1
    ): array {
        return $this
            ->db
            ->where([
                "is_processing" => 0,
                "is_processed" => 0,
            ])
            ->order_by("sid", "ASC")
            ->limit($numberOfJobs)
            ->get(
                "indeed_job_queue"
            )
            ->result_array();
    }

    /**
     * get the job details
     *
     * @param int $jobId
     * @return array
     */
    public function getJobDetailsForIndeed(
        int $jobId
    ): array {
        return $this
            ->db
            ->select([
                "portal_job_listings.user_sid",
                "portal_job_listings.activation_date",
                "portal_job_listings.JobDescription",
                "portal_job_listings.JobRequirements",
                "portal_job_listings.Location_Country",
                "portal_job_listings.Location_State",
                "portal_job_listings.Location_City",
                "portal_job_listings.Location_ZipCode",
                "portal_job_listings.Salary",
                "portal_job_listings.JobType",
                "portal_job_listings.JobCategory",
                "portal_job_listings.questionnaire_sid",
                "portal_job_listings.Title",
                "company_indeed_details.contact_name",
                "company_indeed_details.contact_email",
                "company_indeed_details.contact_phone",
            ])
            ->join(
                "company_indeed_details",
                "company_indeed_details.company_sid = portal_job_listings.user_sid",
                "left"
            )
            ->where("portal_job_listings.sid", $jobId)
            ->limit(1)
            ->get("portal_job_listings")
            ->row_array();
    }
}
