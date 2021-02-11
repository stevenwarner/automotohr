<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Reference_checks_questionnaire_public extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
        $this->load->model('reference_checks_model');
        $this->load->model('dashboard_model');
    }

    public function index($verificationKey = NULL)
    {
        $data['title'] = "Reference Questionnaire";
        if ($verificationKey != NULL) {
            $data['title'] = "Reference Questionnaire";
            $reference = $this->reference_checks_model->GetReferenceCheckDetails($verificationKey);

            if (!empty($reference) && $reference['status'] == 'active') {

                $user_sid = $reference['user_sid'];
                $company_id = $reference['company_sid'];
                $type = $reference['users_type'];

                $data['user_sid'] = $user_sid;
                $data['company_sid'] = $company_id;
                $data['users_type'] = $type;
                $data['reference_status'] = $reference['status'];

                switch ($type) {
                    case 'employee';
                        $userDetails = $this->reference_checks_model->GetUserDetails($user_sid);


                        $data['employer'] = $userDetails;
                        $data['reference'] = $reference;
                        break;
                    case 'applicant';
                        $applicantDetails = $this->reference_checks_model->GetApplicantDetails($user_sid);

                        $job_details = $this->dashboard_model->get_listing($applicantDetails['job_sid'], $company_id);

                        $userDetails = array(
                            'sid' => $applicantDetails['sid'],
                            'first_name' => $applicantDetails['first_name'],
                            'last_name' => $applicantDetails['last_name'],
                            'email' => $applicantDetails['email'],
                            'Location_Address' => $applicantDetails['address'],
                            'Location_City' => $applicantDetails['city'],
                            'Location_Country' => $applicantDetails['country'],
                            'Location_State' => $applicantDetails['state'],
                            'Location_ZipCode' => $applicantDetails['zipcode'],
                            'PhoneNumber' => $applicantDetails['phone_number'],
                            'job_title' => $job_details['Title']
                        );

                        $data['employer'] = $userDetails;
                        $data['reference'] = $reference;

                        break;
                }


                //Handle Post
                if (isset($_POST['perform_action'])) {
                    switch ($_POST['perform_action']) {
                        case 'save_work_reference_questionnaire_information':
                            $dataToSave = array(
                                'position' => $_POST['position'],
                                'work_period_start' => $_POST['work_period_start'],
                                'work_period_end' => $_POST['work_period_end'],
                                'final_salary' => $_POST['final_salary'],
                                'duties_description' => $_POST['duties_description'],
                                'performance' => $_POST['performance'],
                                'late_or_absent' => $_POST['late_or_absent'],
                                'teamwork' => $_POST['teamwork'],
                                'follow_directions' => $_POST['follow_directions'],
                                'assignments_performance' => $_POST['assignments_performance'],
                                'assignments_performance_timely' => $_POST['assignments_performance_timely'],
                                'decision_making_and_work_independently' => $_POST['decision_making_and_work_independently'],
                                'written_and_verbal_communication' => $_POST['written_and_verbal_communication'],
                                'duties_best_performed' => $_POST['duties_best_performed'],
                                'areas_of_improvement' => $_POST['areas_of_improvement'],
                                'disciplinary_record' => $_POST['disciplinary_record'],
                                'dishonesty_insubordination' => $_POST['dishonesty_insubordination'],
                                'reason_for_leaving' => $_POST['reason_for_leaving'],
                                'would_re_employ' => $_POST['would_re_employ'],
                                'referee_name' => $_POST['referee_name'],
                                'referee_title' => $_POST['referee_title'],
                                'conducted_date' => $_POST['conducted_date'],
                                'should_accept' => $_POST['should_accept'],

                                //Additional Fields for Making the save / load work for both work and personal reference.
                                'period_known' => '',
                                'personal_setting' => '',
                                'how_well_you_know' => '',
                                'brief_description_of_success' => '',
                                'strengths_and_weaknesses' => '',
                                'writing_skills' => '',
                                'leadership' => '',
                                'punctual' => '',
                                'work_attitude' => '',
                                'outstanding_abilities' => '',
                                'follow_instructions' => '',
                                'self_starter_or_motivated_by_others' => '',
                                'stressful_situations' => '',
                                'difficult_people' => '',
                                'tactful_manner' => '',
                                'accomplishments' => '',
                                'development_areas' => '',
                                'advice' => ''
                            );

                            $conducted_by = $_POST['conducted_by'];

                            $this->reference_checks_model->UpdateQuestionnairInformation($reference['sid'], $user_sid, $company_id, $type, $dataToSave, $conducted_by);


                            redirect('reference_checks_questionnaire_public/success', 'reload');
                            break;
                        case 'save_personal_reference_questionnaire_information':
                            $dataToSave = array(
                                'period_known' => $_POST['period_known'],
                                'personal_setting' => $_POST['personal_setting'],
                                'how_well_you_know' => $_POST['how_well_you_know'],
                                'brief_description_of_success' => $_POST['brief_description_of_success'],
                                'strengths_and_weaknesses' => $_POST['strengths_and_weaknesses'],
                                'writing_skills' => $_POST['writing_skills'],
                                'leadership' => $_POST['leadership'],
                                'punctual' => $_POST['punctual'],
                                'work_attitude' => $_POST['work_attitude'],
                                'outstanding_abilities' => $_POST['outstanding_abilities'],
                                'follow_instructions' => $_POST['follow_instructions'],
                                'self_starter_or_motivated_by_others' => $_POST['self_starter_or_motivated_by_others'],
                                'stressful_situations' => $_POST['stressful_situations'],
                                'difficult_people' => $_POST['difficult_people'],
                                'tactful_manner' => $_POST['tactful_manner'],
                                'accomplishments' => $_POST['accomplishments'],
                                'development_areas' => $_POST['development_areas'],
                                'advice' => $_POST['advice'],
                                'should_accept' => $_POST['should_accept'],
                                'referee_name' => $_POST['referee_name'],
                                'referee_title' => $_POST['referee_title'],
                                'conducted_date' => $_POST['conducted_date'],


                                //Additional Fields for Making the save / load work for both work and personal reference.
                                'position' => '',
                                'work_period_start' => '',
                                'work_period_end' => '',
                                'final_salary' => '',
                                'duties_description' => '',
                                'performance' => '',
                                'late_or_absent' => '',
                                'teamwork' => '',
                                'follow_directions' => '',
                                'assignments_performance' => '',
                                'assignments_performance_timely' => '',
                                'decision_making_and_work_independently' => '',
                                'written_and_verbal_communication' => '',
                                'duties_best_performed' => '',
                                'areas_of_improvement' => '',
                                'disciplinary_record' => '',
                                'dishonesty_insubordination' => '',
                                'reason_for_leaving' => '',
                                'would_re_employ' => ''
                            );

                            $conducted_by = $_POST['conducted_by'];

                            $this->reference_checks_model->UpdateQuestionnairInformation($reference['sid'], $user_sid, $company_id, $type, $dataToSave, $conducted_by);


                            redirect('reference_checks_questionnaire_public/success', 'reload');
                            break;
                    }
                }


                $this->load->view('reference_checks/reference_checks_public', $data);

            } else {
                //$this->session->set_flashdata('message', '<b>Notification: </b>The link has Expired! Status Message. Reference Empty');
                redirect('reference_checks_questionnaire_public/error', 'reload');
            }
        } else {
            //$this->session->set_flashdata('message', '<b>Notification: </b>The link has Expired! Key Does Not Exist');
            redirect('reference_checks_questionnaire_public/error', 'reload');
        }

    }

    public function error()
    {
        $data['title'] = 'Reference Checks Questionnaire - Error';
        $this->load->view('reference_checks/reference_checks_public_error_message', $data);
    }

    public function success(){
        $data['title'] = 'Reference Checks Questionnaire - Success';
        $this->load->view('reference_checks/reference_checks_public_success_message', $data);
    }
}
