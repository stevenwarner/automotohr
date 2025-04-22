<?php defined('BASEPATH') or exit('No direct script access allowed');
ini_set('memory_limit', '50M');
class Indeed_feed extends CI_Controller
{

    private $debug_email = TO_EMAIL_DEV;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('all_feed_model');
        $this->load->model('users_model');

        require_once(APPPATH . 'libraries/aws/aws.php');
    }

    /**
     * 
     */
    private function addLastRead($sid)
    {
        $this->db
            ->where('sid', $sid)
            ->set([
                'last_read' => date('Y-m-d H:i:s', strtotime('now'))
            ])->update('job_feeds_management');
    }

    public function index()
    {
        $sid = $this->isActiveFeed();
        $this->addLastRead(8);
        $jobData = $this->all_feed_model->get_all_company_jobs_indeed();
        $activeCompaniesArray = $this->all_feed_model->get_all_active_companies($sid);
        $rows = '';
        header('Content-type: text/xml');
        header('Pragma: public');
        header('Cache-control: private');
        header('Expires: -1');
        $rows .= "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
        // echo '<xml>';
        $rows .=  "<source>
        <publisher>" . STORE_NAME . "</publisher>
        <publisherurl><![CDATA[" . STORE_FULL_URL_SSL . "]]></publisherurl>
        <lastBuildDate>" . date('D, d M Y h:i:s') . " PST</lastBuildDate>";

        if (count($jobData) > 0) {
            // $productData = $this->all_feed_model->get_product_data($jobData[0]['product_sid']);
            foreach ($jobData as $job) {
                if (in_array($job['user_sid'], $activeCompaniesArray)) {
                    $company_id = $job['user_sid'];
                    $companyPortal = $this->all_feed_model->get_portal_detail($company_id);
                    $companydata = $this->all_feed_model->get_company_name_and_job_approval($company_id);
                    $companyName = $companydata['CompanyName'];
                    $has_job_approval_rights = $companydata['has_job_approval_rights'];

                    if ($has_job_approval_rights ==  1) {
                        $approval_right_status = $job['approval_status'];

                        if ($approval_right_status != 'approved') {
                            continue;
                        }
                    }

                    if (isset($job['JobRequirements']) && $job['JobRequirements'] != NULL) {
                        $jobDesc = strip_tags(nl2br($job['JobDescription']), '<br>') . '<br><br>Job Requirements:<br>' . strip_tags(nl2br($job['JobRequirements']), '<br>');
                    } else {
                        $jobDesc = strip_tags(nl2br($job['JobDescription']), '<br>');
                    }

                    if (isset($job['Location_Country']) && $job['Location_Country'] != NULL) {
                        $country = db_get_country_name($job['Location_Country']);
                    } else {
                        $country['country_code'] = "US";
                    }

                    if (isset($job['Location_State']) && $job['Location_State'] != NULL) {
                        $state = db_get_state_name($job['Location_State']);
                    } else {
                        $state['state_name'] = "";
                    }

                    if (isset($job['Location_City']) && $job['Location_City'] != NULL) {
                        $city = $job['Location_City'];
                    } else {
                        $city = "";
                    }

                    if (isset($job['Location_ZipCode']) && $job['Location_ZipCode'] != NULL) {
                        $zipcode = $job['Location_ZipCode'];
                    } else {
                        $zipcode = "";
                    }

                    if (isset($job['Salary']) && $job['Salary'] != NULL) {
                        $salary = $job['Salary'];
                    } else {
                        $salary = "";
                    }

                    if (isset($job['SalaryType']) && $job['SalaryType'] != NULL) {
                        if ($job['SalaryType'] == 'per_hour') {
                            $jobType = "Per Hour";
                        } elseif ($job['SalaryType'] == 'per_week') {
                            $jobType = "Per Week";
                        } elseif ($job['SalaryType'] == 'per_month') {
                            $jobType = "Per Month   ";
                        } elseif ($job['SalaryType'] == 'per_year') {
                            $jobType = "Per Year";
                        } else {
                            $jobType = "";
                        }
                    } else {
                        $jobType = "";
                    }

                    $JobCategorys = $job['JobCategory'];

                    if ($JobCategorys != null) {
                        $cat_id = explode(',', $JobCategorys);
                        $job_category_array = array();

                        foreach ($cat_id as $id) {
                            $job_cat_name = $this->all_feed_model->get_job_category_name_by_id($id);
                            $job_category_array[] = $job_cat_name[0]['value'];
                        }

                        $job_category = implode(', ', $job_category_array);
                    }

                    //
                    $jobQuestionnaireUrl = "";
                    //
                    if ($job["questionnaire_sid"] || $this->indeed_model->hasEEOCEnabled($job["user_sid"])) {
                        $jobQuestionnaireUrl = "&indeed-apply-questions=";
                        $jobQuestionnaireUrl .= urlencode(
                            STORE_FULL_URL_SSL . "indeed/$uid/jobQuestions.json"
                        );
                    }

                    $rows .=  "
                    <job>
                    <title><![CDATA[" . db_get_job_title($company_id, $job['Title'], fasle) . "]]></title>
                    <sponsored><![CDATA[yes]]></sponsored>
                    <budget><![CDATA[" . $job['budget'] . "]]></budget>
                    <date><![CDATA[" . date_with_time($job['activation_date']) . " PST]]></date>
                    <referencenumber><![CDATA[" . $job['sid'] . "]]></referencenumber>
                    <url><![CDATA[" . STORE_PROTOCOL_SSL . $companyPortal['sub_domain'] . "/job_details/" . $job['sid'] . "]]></url>
                    <company><![CDATA[" . $companyName . "]]></company>
                    <city><![CDATA[" . $city . "]]></city>
                    <state><![CDATA[" . $state['state_name'] . "]]></state>
                    <country><![CDATA[" . $country['country_code'] . "]]></country>
                    <postalcode><![CDATA[" . $zipcode . "]]></postalcode>
                    <salary><![CDATA[" . $salary . "]]></salary>
                    <jobtype><![CDATA[" . $jobType . "]]></jobtype>
                    <category><![CDATA[" . $job_category . "]]></category>
                    <description><![CDATA[" . $jobDesc . "]]></description>
                    <indeed-apply-data><![CDATA[indeed-apply-joburl=" . urlencode(STORE_PROTOCOL_SSL . $companyPortal['sub_domain'] . "/job_details/" . $job['sid']) . "&indeed-apply-jobid=" . $job['sid'] . "&indeed-apply-jobtitle=" . urlencode(db_get_job_title($company_id, $job['Title'], $city, $state['state_name'], $country['country_code'])) . "&indeed-apply-jobcompanyname=" . urlencode($companyName) . "&indeed-apply-joblocation=" . urlencode($city . "," . $state['state_name'] . "," . $country['country_code']) . "&indeed-apply-apitoken=56010deedbac7ff45f152641f2a5ec8c819b17dea29f503a3ffa137ae3f71781&indeed-apply-posturl=" . urlencode(STORE_FULL_URL_SSL . "/indeed_feed/indeedPostUrl") . "&indeed-apply-phone=required&indeed-apply-allow-apply-on-indeed=1{$jobQuestionnaireUrl}]]></indeed-apply-data>
                    </job>";
                }
            }
        }
        $rows .=  '</source>
        ';
        echo trim($rows);
        @mail('mubashir.saleemi123@gmail.com', 'Indeed Feed Paid - HIT on ' . date('Y-m-d H:i:s') . '', count($jobData));
        exit;
    }

    /**
     * 
     */
    private function addReport($source, $email, $type = 'add')
    {
        if ($type == 'add') {
            $this->db
                ->insert('daily_job_counter', [
                    'source' => $source,
                    'email' => $email,
                    'created_at' => date('Y-m-d H:i:s', strtotime('now')),
                    'already_exists' => 0
                ]);
        } else {
            $this->db
                ->where('email', $email)
                ->where('created_at', date('Y-m-d H:i:s', strtotime('now')))
                ->update('daily_job_counter', [
                    'already_exists' => 1
                ]);
        }
    }

    public function indeedPostUrl()
    {
        // error_reporting(E_ALL);
        //
        $this->addLastRead(9);
        @mail('mubashir.saleemi123@gmail.com', 'Indeed - Applicant Recieve - ' . date('Y-m-d H:i:s') . '', print_r(file_get_contents('php://input'), true));
        //
        $folder = APPPATH . '../../applicant/indeed';
        //
        if (!is_dir($folder)) mkdir($folder, 0777, true);
        // 
        $categories_file = fopen($folder . '/Indeed_Applicant_Recieve_' . date('Y_m_d_H_i_s') . '.json', 'w');
        //
        fwrite($categories_file, file_get_contents('php://input'));
        //
        fclose($categories_file);
        //
        if (file_get_contents('php://input')) {
            $jSonData = file_get_contents('php://input');
            $data = json_decode($jSonData, true);
            $indeedPost = $data;
            $applicationData = array(); // Old Variable currently used
            $insert_data_primary = array(); // Data to be inserted in Primary table
            $applicant_resume = '';
            $this->addReport('Indeed', $data['applicant']['email']);
            sleep(rand(1, 3));

            $job_sid = $data['job']['jobId'];

            if (!is_numeric($job_sid)) {
                $job_sid = $this->all_feed_model->fetch_job_id_from_random_key($job_sid);
            }

            $job_details = $this->all_feed_model->get_job_detail($job_sid);
            $companyId = $job_details['user_sid'];
            //
            if (check_company_status($companyId) == 0) {
                echo '<h1>404. Company not Found!</h1>';
                die();
            }
            //
            // if (in_array($companyId, array("7", "51"))) {

            if (isset($data['applicant']['resume']['file']['data'])) { //making Resume file to upload on AWS
                $base64Data = $data['applicant']['resume']['file']['data']; //Decode pdf content
                $pdf_decoded = base64_decode($base64Data);
                $filePath = FCPATH . "assets/temp_files/"; //making Directory to store
                $fileName = $data['applicant']['resume']['file']['fileName'];

                if (isset($fileName)) {
                    if (!file_exists($filePath)) {
                        mkdir($filePath, 0777);
                    }

                    $pdf = fopen($filePath . $fileName, 'w'); //Write data back to pdf file
                    fwrite($pdf, $pdf_decoded);
                    fclose($pdf); //close output file

                    $resume = generateRandomString(6) . "_" . $fileName;
                    $aws = new AwsSdk();
                    $aws->putToBucket($resume, $filePath . $fileName, AWS_S3_BUCKET_NAME); //uploading file to AWS
                    $applicationData['resume'] = $resume;
                    $insert_data_primary['resume'] = $resume;
                    $applicant_resume = $resume;
                    unlink('/' . DOC_ROOT . 'assets/temp_files/' . $fileName);
                }
            }

            $nameArray = explode(' ', $data['applicant']['fullName'], 2);
            $more_info = $data['applicant']['resume']['json'];

            if (!empty($more_info)) {
                if (isset($more_info['location']['city'])) {
                    $city_details = $more_info['location']['city'];
                    $city_details = explode(',', $city_details);
                    $city_name = trim($city_details[0]);
                    $applicationData['city'] = $city_name;
                    $insert_data_primary['city'] = $city_name;
                    $state_code = trim($city_details[1]);
                    $state_country_id = common_get_location_by_statecode($state_code);

                    if (!empty($state_country_id)) {
                        $country_sid = $state_country_id['country_sid'];
                        $state_sid = $state_country_id['sid'];
                        $applicationData['country'] = $country_sid;
                        $applicationData['state'] = $state_sid;
                        $insert_data_primary['country'] = $country_sid;
                        $insert_data_primary['state'] = $state_sid;
                    }
                }

                if (isset($more_info['location']['postalcode'])) {
                    $postalcode = $more_info['location']['postalcode'];
                    $applicationData['zipcode'] = $postalcode;
                    $insert_data_primary['zipcode'] = $postalcode;
                }
            }

            if (isset($data['applicant']['coverletter'])) {
                $fileData = $data['applicant']['coverletter'];
                $temp_cover = $nameArray[0] . "_cover_letter";
                $filePath = FCPATH . "assets/temp_files/";

                if (!file_exists($filePath)) {
                    mkdir($filePath, 0777); //make dir
                }

                write_file("$filePath" . $temp_cover . ".txt", $fileData); //write file
                $cover_letter = $temp_cover . '-' . generateRandomString(6) . '.txt';

                $aws = new AwsSdk();
                $aws->putToBucket($cover_letter, $filePath . $temp_cover . '.txt', AWS_S3_BUCKET_NAME);
                $applicationData['cover_letter'] = $cover_letter;
                $insert_data_primary['cover_letter'] = $cover_letter;
                unlink('/' . DOC_ROOT . 'assets/temp_files/' . $temp_cover . ".txt");
            }

            $referer = 'https://www.indeed.com';
            $userAgent = '';

            if (isset($data['analytics']['referer'])) {
                $referer = $data['analytics']['referer'];
            }

            if (isset($data['analytics']['userAgent'])) {
                $userAgent = $data['analytics']['userAgent'];
            }

            // $job_sid = $data['job']['jobId'];

            // if (!is_numeric($job_sid)) {
            //     $job_sid = $this->all_feed_model->fetch_job_id_from_random_key($job_sid);
            // }

            $original_job_title = '';
            // $job_details = $this->all_feed_model->get_job_detail($job_sid);
            $questionnaire_sid = 0;

            if (!empty($job_details)) {
                $original_job_title = $job_details['Title'];
            }

            $company_name = $data['job']['jobCompany'];
            $applicant_email = $data['applicant']['email'];
            $jobTitle = $data['job']['jobTitle'];
            $date_applied = date('Y-m-d H:i:s');
            //$companyId = $this->all_feed_model->companyIdByName(strtolower(urldecode($company_name)));
            // $companyId = $job_details['user_sid'];
            $all_status = $this->all_feed_model->get_default_status_sid_and_text($companyId);
            $status_sid = $all_status['status_sid'];
            $status = $all_status['status_name'];
            $applicationData['job_sid'] = $job_sid;
            $applicationData['employer_sid'] = $companyId;
            $applicationData['first_name'] = $nameArray[0];
            $applicationData['last_name'] = $nameArray[1];
            $applicationData['email'] = $applicant_email;
            $applicationData['phone_number'] = $data['applicant']['phoneNumber'];
            $applicationData['status_sid'] = $status_sid;
            $applicationData['status'] = $status;
            $applicationData['date_applied'] = $date_applied;
            $applicationData['applicant_source'] = $referer;
            $applicationData['main_referral'] = 'indeed';
            $applicationData['ip_address'] = $data['analytics']['ip'];
            $applicationData['user_agent'] = $userAgent;

            if (checkForBlockedEmail($applicant_email) == 'blocked') {
                exit(0);
            }

            // Check if user has already applied in this company for any other job
            $portal_job_applications_sid = $this->all_feed_model->check_job_applicant('company_check', $applicant_email, $companyId);

            if ($portal_job_applications_sid == 'no_record_found') {
                //$insert_data_primary['job_sid'] = $job_sid; // this has to be removed
                $insert_data_primary['employer_sid'] = $companyId;
                $insert_data_primary['first_name'] = $nameArray[0];
                $insert_data_primary['last_name'] = $nameArray[1];
                $insert_data_primary['email'] = $applicant_email;
                $insert_data_primary['phone_number'] = $data['applicant']['phoneNumber'];
                $job_applications_sid = $this->all_feed_model->saveApplicant($insert_data_primary);
                //
                send_full_employment_application($companyId, $job_applications_sid, "applicant");
            } else {
                $job_applications_sid = $portal_job_applications_sid;
            }

            if (!isset($resume) || empty($resume) || $resume == '') {
                sendResumeEmailToApplicant([
                    'company_sid' => $companyId,
                    'company_name' => $company_name,
                    'job_list_sid' => $job_sid,
                    'user_sid' => $job_applications_sid,
                    'user_type' => 'applicant',
                    'requested_job_sid' => $job_sid,
                    'requested_job_type' => 'job'
                ], false);
            }

            if(isset($resume) && !empty($resume))
            {
                storeApplicantApplicationInQueue([
                    'portal_applicant_job_sid' => null,
                    'portal_job_applications_sid' => $job_applications_sid,
                    'company_sid' => $companyId,
                    'job_sid' => $job_sid
                ]);
            }

            // Indeed questionnaire
            $eeoc = [];
            $jobQuestions = [];
            // when the answers are coming from Indeed
            // Indeed questionnaire
            if ($indeedPost["screenerQuestionsAndAnswers"] && $indeedPost["screenerQuestionsAndAnswers"]["questionsAndAnswers"]) {
                //
                $questionsAndAnswers = $indeedPost["screenerQuestionsAndAnswers"]["questionsAndAnswers"];
                //
                $jobQuestions["job_sid"] = 0;
                $jobQuestions["questionnaire_sid"] = 0;
                $jobQuestions["action"] = "job_applicant";
                $jobQuestions["q_name"] = "";
                $jobQuestions["q_passing"] = '';
                $jobQuestions["q_send_pass"] = 0;
                $jobQuestions["q_pass_text"] = '';
                $jobQuestions["q_send_fail"] = 0;
                $jobQuestions["q_fail_text"] = '';
                $jobQuestions["my_id"] = '';
                $jobQuestions['all_questions_ids'] = [];
                //
                foreach ($questionsAndAnswers as $key => $questionAndAnswer) {
                    if (isset($indeedPost["questionsAndAnswers"]) && in_array($questionAndAnswer['question']['id'], ['citizen', 'group', 'veteran', 'disability', 'gender'])) {
                        //
                        $index = '';
                        if ($questionAndAnswer['question']['id'] == "citizen") {
                            $index = 'us_citizen';
                        } elseif ($questionAndAnswer['question']['id'] == "group") {
                            $index = 'group_status';
                        } elseif ($questionAndAnswer['question']['id'] == "veteran") {
                            $index = 'veteran';
                        } elseif ($questionAndAnswer['question']['id'] == "disability") {
                            $index = 'disability';
                        } elseif ($questionAndAnswer['question']['id'] == "gender") {
                            $index = 'gender';
                        }
                        $eeoc[$index] = $questionAndAnswer['answer']['value'];
                    } elseif (is_numeric($questionAndAnswer['question']['id'])) {
                        //
                        $jobQuestions['all_questions_ids'][] = $questionAndAnswer['question']['id'];
                        //
                        if ($jobQuestions["questionnaire_sid"] == 0) {
                            $questionInfo = $this->all_feed_model->getJobQuestionnaireInfo($questionAndAnswer['question']['id']);
                            $jobQuestions['q_name'] = $questionInfo['name'];
                            $jobQuestions['questionnaire_sid'] = $questionInfo['sid'];
                            $jobQuestions['my_id'] = 'q_question_' . $questionInfo['sid'];
                        }
                        //
                        $questionType = $this->all_feed_model->getQuestionType($questionAndAnswer['question']['id']);
                        //
                        if ($questionType == "string") {
                            $jobQuestions['caption' . $questionAndAnswer['question']['id']] = $questionAndAnswer['question']['question'];
                            $jobQuestions['type' . $questionAndAnswer['question']['id']] = $questionType;
                            $jobQuestions[$questionType . $questionAndAnswer['question']['id']] = $questionAndAnswer['answer'];
                            // 
                        } else {
                            //
                            $jobQuestions['caption' . $questionAndAnswer['question']['id']] = $questionAndAnswer['question']['question'];
                            $jobQuestions['type' . $questionAndAnswer['question']['id']] = $questionType;
                            //
                            if ($questionType == 'multilist') {
                                $jobQuestions[$questionType . $questionAndAnswer['question']['id']] = [];
                            } else {
                                $jobQuestions[$questionType . $questionAndAnswer['question']['id']] = '';
                            }
                            //
                            foreach ($questionAndAnswer['question']['options'] as $key => $option) {
                                if ($questionType == 'multilist' && in_array($option['value'], array_column($questionAndAnswer['answer'], "value"))) {
                                    $answerID = $this->all_feed_model->getQuestionAnswerId($questionAndAnswer['question']['id'], $option['value']);
                                    $jobQuestions[$questionType . $questionAndAnswer['question']['id']][] = $option['label'] . ' @#$ ' . $answerID;
                                } else if ($questionType != 'multilist' && $option['value'] == $questionAndAnswer['answer']['value']) {
                                    $answerID = $this->all_feed_model->getQuestionAnswerId($questionAndAnswer['question']['id'], $option['value']);
                                    $jobQuestions[$questionType . $questionAndAnswer['question']['id']] = $option['label'] . ' @#$ ' . $answerID;
                                }
                            }
                        }
                    }
                }
            }

            // Indeed questionnaire
            if ($indeedPost['demographicQuestionsAndAnswers'] && $indeedPost['demographicQuestionsAndAnswers']["questionsAndAnswers"]) {
                $index = '';
                $value = '';
                //
                foreach ($indeedPost['demographicQuestionsAndAnswers']['questionsAndAnswers'] as $eeocQuestion) {
                    //
                    switch ($eeocQuestion['question']['id']) {
                            //
                        case 'citizen':
                            $index = 'us_citizen';
                            $value = $eeocQuestion['answer']['value'];
                            break;
                        case 'group':
                            $index = 'group_status';
                            $value = $eeocQuestion['answer']['value'];
                            break;
                        case 'veteran':
                            $index = 'veteran';
                            $value = $eeocQuestion['answer']['value'];
                            break;
                        case 'disability':
                            $index = 'disability';
                            $value = $eeocQuestion['answer']['value'];
                            break;
                        case 'gender':
                            $index = 'gender';
                            $value = $eeocQuestion['answer']['value'];
                            break;

                        case 'citizen-eeo':
                            $index = 'us_citizen';
                            break;
                        case 'group-eeo':
                            $index = 'group_status';
                            break;
                            //
                        case 'veteran-eeo':
                            $index = 'veteran';
                            break;

                        case 'disability-eeo':
                            $index = 'disability';
                            break;

                        case 'gender-eeo':
                            $index = 'gender';
                            $value = $eeocQuestion['answer']['value'] == 1 ? "YES, I HAVE A DISABILITY" : "NO, I DON'T HAVE A DISABILITY";
                            break;
                    }
                    $eeoc[$index] = $value;
                }
            }

            //check if the user has already applied for this job
            $already_applied = $this->all_feed_model->check_job_applicant($job_sid, $applicant_email, $companyId);

            if ($already_applied <= 0) {
                $insert_job_list = array(
                    'portal_job_applications_sid' => $job_applications_sid,
                    'job_sid' => $job_sid,
                    'company_sid' => $companyId,
                    'date_applied' => $date_applied,
                    'status' => $status,
                    'status_sid' => $status_sid,
                    'applicant_source' => $referer,
                    'main_referral' => 'indeed',
                    'applicant_type' => 'Applicant',
                    'eeo_form' => null,
                    'ip_address' => $data['analytics']['ip'],
                    'user_agent' => $userAgent,
                    'resume' => $applicant_resume,
                    'last_update' => date('Y-m-d'),
                    "indeed_ats_sid" => $indeedPost["id"]
                );
                sleep(1);
                $final_check = $this->all_feed_model->applicant_list_exists_check($job_applications_sid, $job_sid, $companyId);

                if ($final_check <= 0) {

                    /*START START START*/
                    // https://www.youtube.com/shorts/o99t-97sD1o
                    $jobs_list_result = $this->all_feed_model->add_applicant_job_details($insert_job_list);
                    $portal_applicant_jobs_list_sid = $jobs_list_result[0];
                    //
                    // Comment below line because this function exit the process  and now allow to send emails on 11 Apr 2024;
                    // $this->indeed_model->pushTheApplicantStatus(
                    //     "NEW",
                    //     $portal_applicant_jobs_list_sid,
                    //    $companyId
                    // );
                    //
                    $job_added_successfully = $jobs_list_result[1];
                    $acknowledgement_email_data['company_name'] = $company_name;
                    $acknowledgement_email_data['sid'] = $job_applications_sid;
                    $acknowledgement_email_data['job_sid'] = $job_sid;
                    $acknowledgement_email_data['job_title'] = $jobTitle;
                    $acknowledgement_email_data['employer_sid'] = $companyId;
                    $acknowledgement_email_data['first_name'] = $nameArray[0];
                    $acknowledgement_email_data['last_name'] = $nameArray[1];
                    $acknowledgement_email_data['email'] = $applicant_email;
                    $acknowledgement_email_data['phone_number'] = $data['applicant']['phoneNumber'];
                    $acknowledgement_email_data['date_applied'] = date('Y-m-d H:i:s');
                    common_indeed_acknowledgement_email($acknowledgement_email_data);
                    // send email to 'new applicant notification' users *** START *** ////////

                    // EEOC fill from Indeed
                    if ($eeoc) {
                        $eeoc['users_type'] = 'applicant';
                        $eeoc['status'] = 1;
                        $eeoc['application_sid'] = $job_applications_sid;
                        $eeoc['portal_applicant_jobs_list_sid'] = $portal_applicant_jobs_list_sid;
                        $eeoc['last_sent_at'] =
                            $eeoc["assigned_at"] =
                            $eeoc["last_completed_on"]
                            = getSystemDate();
                        //
                        $this->all_feed_model->save_eeo_form($eeoc);
                    }

                    $company_sid = $companyId;
                    $message_hf = message_header_footer_domain($company_sid, $company_name);
                    $replacement_array = array();
                    $replacement_array['site_url'] = base_url();
                    $replacement_array['date'] = month_date_year(date('Y-m-d'));
                    $replacement_array['job_title'] = $jobTitle;
                    $replacement_array['phone_number'] = $data['applicant']['phoneNumber'];

                    if (isset($city_name)) {
                        $replacement_array['city'] = $city_name;
                    } else {
                        $replacement_array['city'] = '';
                    }

                    $replacement_array['company_name'] = $company_name;
                    $notifications_status = get_notifications_status($company_sid);
                    //$company_primary_admin_info = get_primary_administrator_information($company_sid);
                    $my_debug_message = json_encode($replacement_array);
                    $applicant_notifications_status = 0;

                    if (!empty($notifications_status)) {
                        $applicant_notifications_status = $notifications_status['new_applicant_notifications'];
                    }

                    $resume_url = '';
                    $resume_anchor = '';
                    $profile_anchor = '<a href="' . base_url('applicant_profile/' . $job_applications_sid . '/' . $portal_applicant_jobs_list_sid) . '" style="' . DEF_EMAIL_BTN_STYLE_DANGER . '"  download="resume" >View Profile</a>';

                    if (!empty($resume)) {
                        $resume_url = AWS_S3_BUCKET_URL . urlencode($resume);
                        $resume_anchor = '<a style="' . DEF_EMAIL_BTN_STYLE_PRIMARY . '" href="' . $resume_url . '" target="_blank">Download Resume</a>';
                    }

                    $applicant_notification_contacts = array();

                    if ($applicant_notifications_status == 1) { // New Applicants Notifications Enabled
                        $applicant_notification_contacts = get_notification_email_contacts($company_sid, 'new_applicant', $job_sid);

                        if (!empty($applicant_notification_contacts)) {
                            foreach ($applicant_notification_contacts as $contact) {
                                $replacement_array['firstname'] = $nameArray[0];
                                $replacement_array['lastname'] = $nameArray[1];
                                $replacement_array['email'] = $applicant_email;
                                $replacement_array['company_name'] = $company_name;
                                $replacement_array['phone_number'] = $data['applicant']['phoneNumber'];
                                $replacement_array['resume_link'] = $resume_anchor;
                                $replacement_array['applicant_profile_link'] = $profile_anchor;
                                $replacement_array['original_job_title'] = $original_job_title;
                                log_and_send_templated_notification_email(APPLY_ON_JOB_EMAIL_ID, $contact['email'], $replacement_array, $message_hf, $company_sid, $job_sid, 'new_applicant_notification');
                            }
                        }
                    } // send email to 'new applicant notification' users *** END *** ////////

                    //check if screening Questionnaire is attached to the job - If Yes, Send screen questionnaire email to applicant ***  START ***
                    if (!empty($job_details)) {
                        $original_job_title = $job_details['Title'];
                        $questionnaire_sid = $job_details['questionnaire_sid'];
                    }

                    if ($questionnaire_sid > 0) {
                        $questionnaire_status = $this->all_feed_model->check_screening_questionnaires($questionnaire_sid);
                        if ($questionnaire_status == 'found') {
                            // when the answers are coming from Indeed
                            // // Indeed questionnaire
                            if ($jobQuestions) {
                                //
                                $extraInfo = array(
                                    "companyId" => $companyId,
                                    "applicantId" => $job_applications_sid,
                                    "applicantJobsListId" => $portal_applicant_jobs_list_sid,
                                    "jobId" => $job_details['sid'],
                                    "jobTitle" => $job_details['Title'],
                                    "jobType" => $job_details['JobType'],
                                    "companyName" => $data['job']['jobCompany'],
                                );
                                //
                                $this->all_feed_model->processJobScreeningQuestionnaire(
                                    $extraInfo,
                                    $jobQuestions
                                );
                                //
                            } else {
                                $email_template_information = $this->all_feed_model->get_email_template_data(SCREENING_QUESTIONNAIRE_FOR_JOB);
                                $screening_questionnaire_key = $this->all_feed_model->generate_questionnaire_key($portal_applicant_jobs_list_sid);

                                if (empty($email_template_information)) {
                                    $email_template_information = array(
                                        'subject' => '{{company_name}} - Screening Questionnaire for {{job_title}}',
                                        'text' => '<p>Dear {{applicant_name}},</p>
                                                                                    <p>You have successfully applied for the job: "{{job_title}}" and your job application is in our system. </p>
                                                                                    <p><strong>Please complete the Job Screening Questionnaire by clicking on the link below. We are excited to learn more about you. </strong></p>
                                                                                    <p>{{url}}</p>
                                                                                    <p>Thank you, again, for your interest in {{company_name}}</p>',
                                        'from_name' => '{{company_name}}'
                                    );
                                }

                                $emailTemplateBody = $email_template_information['text'];
                                $emailTemplateSubject = $email_template_information['subject'];
                                $emailTemplateFromName = $email_template_information['from_name'];
                                $replacement_array = array();
                                $replacement_array['company_name'] = $company_name;

                                if ($original_job_title != '') {
                                    $replacement_array['job_title'] = $original_job_title;
                                } else {
                                    $replacement_array['job_title'] = $jobTitle;
                                }

                                $replacement_array['applicant_name'] = $nameArray[0] . '&nbsp;' . $nameArray[1];
                                $replacement_array['url'] = '<a style="' . DEF_EMAIL_BTN_STYLE_PRIMARY . '" href="' . base_url() . 'Job_screening_questionnaire/' . $screening_questionnaire_key . '" target="_blank">Screening Questionnaire</a>';

                                if (!empty($replacement_array)) {
                                    foreach ($replacement_array as $key => $value) {
                                        $emailTemplateBody = str_replace('{{' . $key . '}}', $value, $emailTemplateBody);
                                        $emailTemplateSubject = str_replace('{{' . $key . '}}', $value, $emailTemplateSubject);
                                        $emailTemplateFromName = str_replace('{{' . $key . '}}', $value, $emailTemplateFromName);
                                    }
                                }

                                if (!empty($emailTemplateBody)) {
                                    $emailTemplateBody     = str_replace('{{site_url}}', base_url(), $emailTemplateBody);
                                    $emailTemplateBody     = str_replace('{{date}}', month_date_year(date('Y-m-d')), $emailTemplateBody);
                                    $emailTemplateBody     = str_replace('{{firstname}}', $nameArray[0], $emailTemplateBody);
                                    $emailTemplateBody     = str_replace('{{lastname}}', $nameArray[1], $emailTemplateBody);
                                    $emailTemplateBody     = str_replace('{{applicant_name}}', $nameArray[0] . ' ' . $nameArray[1], $emailTemplateBody);
                                    $emailTemplateBody     = str_replace('{{job_title}}', $replacement_array['job_title'], $emailTemplateBody);
                                    $emailTemplateBody     = str_replace('{{company_name}}', $company_name, $emailTemplateBody);
                                }

                                $message_data = array();
                                $message_data['to_id'] = $applicant_email;
                                $message_data['from_type'] = 'employer';
                                $message_data['to_type'] = 'admin';
                                $message_data['job_id'] = $job_applications_sid;
                                $message_data['users_type'] = 'applicant';
                                $message_data['subject'] = $emailTemplateSubject;
                                $message_data['message'] = $emailTemplateBody;
                                $message_data['date'] = $date_applied;
                                $message_data['from_id'] = REPLY_TO;
                                $message_data['contact_name'] = $nameArray[0] . '&nbsp;' . $nameArray[1];
                                $message_data['identity_key'] = generateRandomString(48);
                                $message_hf = message_header_footer_domain($company_sid, $company_name);
                                $secret_key = $message_data['identity_key'] . "__";
                                $autoemailbody = FROM_INFO_EMAIL_DISCLAIMER_MSG . $message_hf['header']
                                    . $emailTemplateBody
                                    . $message_hf['footer']
                                    . '<div style="width:100%; float:left; background-color:#000; color:#000; box-sizing:border-box;">message_id:'
                                    . $secret_key . '</div>';

                                $autoemailbody .= FROM_INFO_EMAIL_DISCLAIMER_MSG;

                                sendMail(FROM_EMAIL_INFO, $applicant_email, $emailTemplateSubject, $autoemailbody, $company_name, FROM_EMAIL_INFO);
                                $sent_to_pm = common_save_message($message_data, NULL);
                                $this->all_feed_model->update_questionnaire_status($portal_applicant_jobs_list_sid);
                            }
                        }
                    }
                    // *** END - Screening Questionnaire Email ***
                    /*END END END*/
                }
            } else {
                $this->addReport('Indeed', $data['applicant']['email'], 'update');
                $applicant_email = $data['applicant']['email'];
                $company_name = $data['job']['jobCompany'];
                $company_sid = $this->all_feed_model->companyIdByName(strtolower(urldecode($company_name)));
                $replacement_array = array();
                $replacement_array['company_name'] = $data['job']['jobCompany'];
                $replacement_array['job_title'] = $data['job']['jobTitle'];
                $replacement_array['applicant_name'] = $data['applicant']['fullName'];
                $replacement_array['resume_link'] = '';
                $replacement_array['applicant_profile_link'] = '';
                log_and_send_templated_email(INDEED_ALREADY_APPLIED_NOTIFICATION, $applicant_email, $replacement_array, message_header_footer($company_sid, $company_name), 0);
            }
        }
    }

    private function isActiveFeed()
    {
        $this->load->model('all_job_feed_model');
        $validSlug = $this->all_job_feed_model->check_for_slug('indeed_paid');
        if (!$validSlug) {
            echo '<h1>404. Feed Not Found!</h1>';
            die();
        }
        return $validSlug;
    }
}
