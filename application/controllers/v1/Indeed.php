<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Indeed controller to handle all new
 * events
 *
 * @author  AutomotoHR Dev Team
 * @version 1.0
 */
class Indeed extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('indeed_model');
    }

    /**
     * list job Screening questionnaires
     *
     * @param string $jobId
     * @return void
     */
    public function jobQuestions(string $jobId)
    {
        // get the job id by uuid
        $feedData = $this->indeed_model->getJobIdByUid($jobId);
        // if there is a uuid
        if ($feedData) {
            $jobId = $feedData["job_sid"];
        }
        // get the company id
        $companyId = $this->indeed_model
            ->getJobDetailsById(
                $jobId,
                [
                    "user_sid"
                ]
            )["user_sid"];
        // get the questionnaires
        $this->indeed_model
            ->saveQuestionIntoFile(
                $jobId,
                $companyId,
                false
            );
    }
}
