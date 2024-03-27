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

        $cashTime = 10; // In minutes
        $this->output->cache($cashTime);

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
            return SendResponse(
                400,
                [
                    "errors" => [
                        "No screening or demographic questions found."
                    ]
                ]
            );
        }
        // set the json
        $questionArray = [
            "schemaVersion" => "1.0",
        ];
        // check and set screening screeningQuestionnaire
        if ($screeningQuestionnaire) {
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
        //
        $jsonData = json_encode($questionArray);

        $data['jsonData'] = $jsonData;

        $this->load->view('v1/indeed_job_json', $data);
     
    }
}
