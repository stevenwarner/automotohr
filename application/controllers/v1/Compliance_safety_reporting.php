<?php

use Aws\Common\Facade\S3;

defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'controllers/csp/Base_csp.php';

/**
 * Indeed controller to handle all new
 * events
 *
 * @author  AutomotoHR Dev Team
 * @version 1.0
 */
class Compliance_safety_reporting extends Base_csp
{
    public function __construct()
    {
        parent::__construct(true);
    }

    /**
     * overview
     */
    public function overview()
    {
        // set the title
        $this->data['title'] = 'Compliance Safety Reporting | Overview';
        // get types
        $this->data["types"] = $this
            ->compliance_report_model
            ->getAllReportTypes(
                $this->getLoggedInCompany("sid")
            );
        // load JS
        $this->data['pageJs'][] = 'https://code.highcharts.com/highcharts.js';
        $this->data['pageJs'][] = 'https://code.highcharts.com/modules/exporting.js';
        $this->data['pageJs'][] = 'https://code.highcharts.com/modules/accessibility.js';
        $this->data['pageJs'][] = 'csp/overview';
        //
        $this->renderView('compliance_safety_reporting/overview');
    }

    /**
     * listing
     */
    public function listing()
    {
        // set the title
        $this->data['title'] = 'Compliance Safety Reporting';
        // get types
        $this->data["types"] = $this
            ->compliance_report_model
            ->getAllReportTypes(
                $this->getLoggedInCompany("sid")
            );
        //
        $this->renderView('compliance_safety_reporting/listings');
    }

    /**
     * add
     *
     * @param int $reportId
     */
    public function add(int $reportTypeId)
    {
        // get types
        $this->data["type"] = $this
            ->compliance_report_model
            ->getReportTypeById(
                $reportTypeId
            );
        // set the title
        $this->data['title'] = 'Compliance Safety Reporting | Add ' . $this->data["type"]["compliance_report_name"];
        $this->data['PageCSS'][] = 'v1/plugins/ms_uploader/main.min';
        $this->data['pageJs'][] = 'v1/plugins/ms_uploader/main.min';
        $this->data['pageJs'][] = 'csp/add_report.min';
        // get the employees
        $this->data["employees"] = $this
            ->compliance_report_model
            ->getActiveEmployees(
                $this->getLoggedInCompany("sid"),
                $this->getLoggedInEmployee("sid")
            );
        //
        $this->renderView('compliance_safety_reporting/add_report');
    }

    public function processAdd(int $reportTypeId)
    {
        $this->form_validation->set_rules('report_title', 'Report Title', 'required');
        $this->form_validation->set_rules('report_date', 'Report Date', 'required');

        if (!$this->form_validation->run()) {
            return sendResponse(
                400,
                ["errors" => explode("\n", validation_errors())]
            );
        }
        // get the post
        $post = $this->input->post(null, true);
        //allowed_internal_system_count
        $reportId = $this->compliance_report_model->addReport(
            $reportTypeId,
            $this->getLoggedInCompany("sid"),
            $this->getLoggedInEmployee("sid"),
            $post
        );

        if (!$reportId) {
            return sendResponse(
                400,
                ["errors" => ["Failed to add the report"]]
            );
        }
        // return the success
        return sendResponse(
            200,
            ["id" => $reportId, "message" => "Report added successfully."]
        );
    }

    /**
     * edit
     *
     * @param int $reportId
     */
    public function edit(int $reportId)
    {
        // get types
        $this->data["report"] = $this
            ->compliance_report_model
            ->getCSPReportById(
                $reportId,
                [
                    "csp_reports.title",
                    "csp_reports.report_date",
                    "csp_reports.completion_date",
                    "csp_reports.status",
                    "csp_reports.updated_at",
                    "compliance_report_types.compliance_report_name",
                    "users.first_name",
                    "users.last_name",
                    "users.middle_name",
                    "users.access_level",
                    "users.access_level_plus",
                    "users.pay_plan_flag",
                    "users.job_title",
                    "users.is_executive_admin",
                ]
            );
        // set the title
        $this->data['title'] = 'Compliance Safety Reporting | Edit ' . $this->data["report"]["title"];
        $this->data['PageCSS'][] = 'v1/plugins/ms_uploader/main.min';
        $this->data['PageCSS'][] = 'v1/plugins/ms_modal/main.min';
        $this->data['pageJs'][] = 'v1/plugins/ms_uploader/main.min';
        $this->data['pageJs'][] = 'v1/plugins/ms_modal/main.min';
        $this->data['pageJs'][] = 'csp/edit_report';
        // get the employees
        $this->data["employees"] = $this
            ->compliance_report_model
            ->getActiveEmployees(
                $this->getLoggedInCompany("sid"),
                $this->getLoggedInEmployee("sid")
            );   
        //
        $this->renderView('compliance_safety_reporting/edit_report');
    }

    /**
     * process edit
     * 
     * @param int $reportTypeId
     */
    public function processEdit(int $reportId)
    {
        $this->form_validation->set_rules('report_title', 'Report Title', 'required');
        $this->form_validation->set_rules('report_date', 'Report Date', 'required');

        if (!$this->form_validation->run()) {
            return sendResponse(
                400,
                ["errors" => explode("\n", validation_errors())]
            );
        }
        // get the post
        $post = $this->input->post(null, true);
        //allowed_internal_system_count
        $this->compliance_report_model->editReport(
            $reportId,
            $this->getLoggedInEmployee("sid"),
            $post
        );
        // return the success
        return sendResponse(
            200,
            ["message" => "Report updated successfully."]
        );
    }

    /**
     * delete external employee
     *
     * @param int $reportId
     * @param int $recordId
     */
    public function deleteExternalEmployee(int $reportId, int $recordId)
    {
        $this->compliance_report_model->deleteExternalEmployee(
            $reportId,
            $recordId
        );
        // return the success
        return sendResponse(
            200,
            ["message" => "External employee removed successfully."]
        );
    }

    /**
     * process edit
     * 
     * @param int $reportId
     * @param int $incidentId
     */
    public function processNotes(int $reportId, int $incidentId)
    {
        $this->form_validation->set_rules('type', 'Note Type', 'required');
        $this->form_validation->set_rules('content', 'Note', 'required');

        if (!$this->form_validation->run()) {
            return sendResponse(
                400,
                ["errors" => explode("\n", validation_errors())]
            );
        }
        // get the post
        $post = $this->input->post(null, true);
        //allowed_internal_system_count
        $this->compliance_report_model->addNotesToReport(
            $reportId,
            $incidentId,
            $this->getLoggedInEmployee("sid"),
            $post
        );
        // return the success
        return sendResponse(
            200,
            ["message" => "Notes added successfully."]
        );
    }

    public function processFiles(int $reportId, int $incidentId, string $type)
    {
        //
        $post = $this->input->post(null, true);

        if ($post["link"]) {
            $id = $this
                ->compliance_report_model
                ->addFilesLinkToReport(
                    $reportId,
                    $incidentId,
                    $this->getLoggedInEmployee("sid"),
                    $post["link"],
                    $post["file_type"],
                    $this->input->post("title")
                );
            // return the success
            return sendResponse(
                200,
                [
                    "message" => "File is attached successfully.",
                    "view" => $this->load->view(
                        'compliance_safety_reporting/partials/file',
                        [
                            "document" => [
                                "sid" => $id,
                                "title" => $this->input->post("title"),
                                "created_at" => getSystemDate(),
                                "first_name" => $this->getLoggedInEmployee("first_name"),
                                "last_name" => $this->getLoggedInEmployee("last_name"),
                                "middle_name" => $this->getLoggedInEmployee("middle_name"),
                                "job_title" => $this->getLoggedInEmployee("job_title"),
                                "access_level" => $this->getLoggedInEmployee("access_level"),
                                "access_level_plus" => $this->getLoggedInEmployee("access_level_plus"),
                                "is_executive_admin" => $this->getLoggedInEmployee("is_executive_admin"),
                                "pay_plan_flag" => $this->getLoggedInEmployee("pay_plan_flag"),
                            ]
                        ],
                        true
                    ),
                ]
            );
        } else {
            // sanitize the name
            $fileName = preg_replace('/[^a-zA-Z0-9-_\.]/', '', $_FILES['file']['name']);
            $fileName = str_replace(' ', '_', $fileName);
            $fileName = time() . '_' . $fileName;
            $fileName = strtolower($fileName);

            $this->load->library('aws_lib');
            //
            try {
                $options = [
                    'Bucket' => AWS_S3_BUCKET_NAME,
                    'Key' => $fileName,
                    'Body' => file_get_contents($_FILES["file"]["tmp_name"]),
                    'ACL' => 'public-read',
                    'ContentType' => $_FILES["file"]["type"]
                ];
                //
                $this->aws_lib->put_object($options);
                $id = $this
                    ->compliance_report_model
                    ->addFilesToReport(
                        $reportId,
                        $incidentId,
                        $this->getLoggedInEmployee("sid"),
                        $fileName,
                        $_FILES["file"]["name"],
                        $type,
                        $this->input->post("title")
                    );
                // return the success
                return sendResponse(
                    200,
                    [
                        "message" => "File is attached successfully.",
                        "view" => $this->load->view(
                            'compliance_safety_reporting/partials/file',
                            [
                                "document" => [
                                    "sid" => $id,
                                    "title" => $this->input->post("title"),
                                    "created_at" => getSystemDate(),
                                    "first_name" => $this->getLoggedInEmployee("first_name"),
                                    "last_name" => $this->getLoggedInEmployee("last_name"),
                                    "middle_name" => $this->getLoggedInEmployee("middle_name"),
                                    "job_title" => $this->getLoggedInEmployee("job_title"),
                                    "access_level" => $this->getLoggedInEmployee("access_level"),
                                    "access_level_plus" => $this->getLoggedInEmployee("access_level_plus"),
                                    "is_executive_admin" => $this->getLoggedInEmployee("is_executive_admin"),
                                    "pay_plan_flag" => $this->getLoggedInEmployee("pay_plan_flag"),
                                ]
                            ],
                            true
                        ),
                    ]
                );
                //
            } catch (Exception $exception) {
                return sendResponse(
                    400,
                    ["errors" => ["Failed to upload file to AWS S3"]]
                );
            }
        }
    }

    /**
     * download file
     *
     * @param int $fileId
     */
    public function downloadFile(int $fileId)
    {
        // Get the file details from the database
        $file = $this->compliance_report_model->getFileById($fileId, [
            "s3_file_value",
            "file_value",
        ]);
        //
        if (!$file) {
            return redirect()->back();
        }
        // Get the file from S3
        $this->load->library('aws_lib');
        //
        $fileContent = $this->aws_lib->get_object(
            AWS_S3_BUCKET_NAME,
            $file['s3_file_value']
        );
        //
        if (!$fileContent) {
            return redirect()->back();
        }
        //
        header("Content-Type: {$fileContent->get("ContentType")}");
        header("Content-Disposition: attachment; filename=\"" . basename($file['file_value']) . "\"");
        header("Content-Length: " . strlen($fileContent['Body']));
        echo $fileContent['Body'];
    }

    /**
     * download file
     *
     * @param int $fileId
     */
    public function viewFile(int $fileId)
    {
        // Get the file details from the database
        $file = $this->compliance_report_model->getFileById($fileId, [
            "s3_file_value",
            "title",
            "file_type",
            "file_value",
        ]);
        //
        if (!$file) {
            return SendResponse(400, ["errors" => "Failed to verify the file."]);
        }
        return sendResponse(
            200,
            [
                "view" => $this->load->view(
                    'compliance_safety_reporting/partials/view',
                    [
                        "file" => $file
                    ],
                    true
                ),
                "data" => $file
            ]
        );
    }

    public function save_email_manual_attachment()
    {
        $session = $this->session->userdata('logged_in');
        $employee_sid   = $session["employer_detail"]["sid"];
        $company_sid    = $session['company_detail']['sid'];

        $item_title     = $_POST['attachment_title'];
        $company_sid    = $_POST['company_sid'];
        $report_sid     = $_POST['report_sid'];
        $incident_sid   = $_POST['incident_sid'];
        $item_source    = $_POST['file_type'];
        $uploaded_by    = $_POST['uploaded_by'];
        $user_type      = $_POST['user_type'];

        if ($item_source == 'upload_document') {
            if (!empty($_FILES) && isset($_FILES['file']) && $_FILES['file']['size'] > 0) {

                $file_name          = $_POST['file_name'];
                $file_extension     = $_POST['file_ext'];
                $upload_incident_doc = upload_file_to_aws('file', $company_sid, 'file', '', AWS_S3_BUCKET_NAME);

                if (!empty($upload_incident_doc) && $upload_incident_doc != 'error') {
                    //
                    $insert_document_sid = $this
                        ->compliance_report_model
                        ->addFilesLinkToReport(
                            $report_sid,
                            $incident_sid,
                            $this->getLoggedInEmployee("sid"),
                            $upload_incident_doc,
                            "document",
                            $item_title
                        );

                    $return_data                    = array();
                    $return_data['item_sid']        = $insert_document_sid;
                    $return_data['item_title']      = $item_title;
                    $return_data['item_type']       = 'Document';
                    $return_data['item_source']     = strtoupper($file_extension);

                    echo json_encode($return_data);
                }
            } else {
                echo 'error';
            }
        } else {
            $video_id = '';

            if (!empty($_FILES) && isset($_FILES['file']) && $_FILES['file']['size'] > 0 && $item_source == 'upload_video') {
                $random = generateRandomString(5);
                $target_file_name = basename($_FILES["file"]["name"]);
                $file_name = strtolower($company_sid . '/' . $random . '_' . $target_file_name);
                $target_dir = "assets/uploaded_videos/incident_videos/";
                $target_file = $target_dir . $file_name;
                $basePath = $target_dir . $company_sid;

                if (!is_dir($basePath)) {
                    mkdir($basePath, 0777, true);
                }

                move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);

                $video_id = $file_name;
            } else if (!empty($_FILES) && isset($_FILES['file']) && $_FILES['file']['size'] > 0 && $item_source == 'upload_audio') {
                $random = generateRandomString(5);
                $target_file_name = basename($_FILES["file"]["name"]);
                $file_name = strtolower($company_sid . '/' . $random . '_' . $target_file_name);
                $target_dir = "assets/uploaded_videos/incident_videos/";
                $target_file = $target_dir . $file_name;
                $basePath = $target_dir . $company_sid;

                if (!is_dir($basePath)) {
                    mkdir($basePath, 0777, true);
                }

                move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);

                $video_id = $file_name;
            } else {
                if ($item_source == 'youtube') {
                    $youtube_video_link = $_POST['social_url'];
                    $url_prams = array();
                    parse_str(parse_url($youtube_video_link, PHP_URL_QUERY), $url_prams);

                    if (isset($url_prams['v'])) {
                        $video_id = $url_prams['v'];
                    } else {
                        $video_id = '';
                    }
                } else {
                    $vimeo_video_link = $_POST['social_url'];
                    $video_id = $this->vimeo_get_id($vimeo_video_link);
                }
            }
            //
            $insert_video_sid = $this
                ->compliance_report_model
                ->addFilesLinkToReport(
                    $report_sid,
                    $incident_sid,
                    $this->getLoggedInEmployee("sid"),
                    $video_id,
                    $item_source,
                    $item_title
                );

            $return_data                    = array();
            $return_data['item_sid']        = $insert_video_sid;
            $return_data['item_title']      = $item_title;
            $return_data['item_type']       = 'Media';
            $return_data['item_source']     = $item_source;

            echo json_encode($return_data);
        }
    }

    public function validate_vimeo_video()
    {
        $str = $this->input->post('url');
        if ($str != "") {
            if ($_SERVER['HTTP_HOST'] == 'localhost') {
                $api_url = 'https://vimeo.com/api/oembed.json?url=' . urlencode($str);
                $response = @file_get_contents($api_url);

                if (!empty($response)) {
                    $response = json_decode($response, true);

                    if (isset($response['video_id'])) {
                        echo TRUE;
                    } else {
                        $this->form_validation->set_message('validate_vimeo', 'In-valid Vimeo video URL');
                        $this->session->set_flashdata('message', '<b>Error:</b> In-valid Vimeo video URL');
                        echo FALSE;
                    }
                } else {
                    $this->form_validation->set_message('validate_vimeo', 'In-valid Vimeo video URL');
                    $this->session->set_flashdata('message', '<b>Error:</b> In-valid Vimeo video URL');
                    echo FALSE;
                }
            } else {
                $api_url = 'https://vimeo.com/api/oembed.json?url=' . urlencode($str);
                $cSession = curl_init();
                curl_setopt($cSession, CURLOPT_URL, $api_url);
                curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($cSession, CURLOPT_HEADER, false);
                $response = curl_exec($cSession);
                curl_close($cSession);
                $response = json_decode($response, true); //$response = @file_get_contents($api_url);

                if (isset($response['video_id'])) {
                    echo TRUE;
                } else {
                    $this->form_validation->set_message('validate_vimeo', 'In-valid Vimeo video URL');
                    $this->session->set_flashdata('message', '<b>Error:</b> In-valid Vimeo video URL');
                    echo FALSE;
                }
            }
        } else {
            echo FALSE;
        }
    }

    public function vimeo_get_id($str)
    {
        if ($str != "") {
            if ($_SERVER['HTTP_HOST'] == 'localhost') {
                $api_url = 'https://vimeo.com/api/oembed.json?url=' . urlencode($str);
                $response = @file_get_contents($api_url);

                if (!empty($response)) {
                    $response = json_decode($response, true);

                    if (isset($response['video_id'])) {
                        return $response['video_id'];
                    } else {
                        return 0;
                    }
                } else {
                    return 0;
                }
            } else {
                $api_url = 'https://vimeo.com/api/oembed.json?url=' . urlencode($str);
                $cSession = curl_init();
                curl_setopt($cSession, CURLOPT_URL, $api_url);
                curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($cSession, CURLOPT_HEADER, false);
                $response = curl_exec($cSession);
                curl_close($cSession);
                $response = json_decode($response, true); //$response = @file_get_contents($api_url);

                if (isset($response['video_id'])) {
                    return $response['video_id'];
                } else {
                    return 0;
                }
            }
        } else {
            return 0;
        }
    }

    function update_email_read_flag()
    {
        $email_sid              = $_POST['email_sid'];

        $data_to_update             = array();
        $data_to_update['is_read']  = 1;
        $this->compliance_report_model->update_email_is_read_flag($email_sid, $data_to_update);



        if (isset($_POST['receiver_sid'])) {
            $receiver_sid           = $_POST['receiver_sid'];
            $sender_info            = $this->compliance_report_model->get_email_sender_info($email_sid);

            $incident_sid           = $sender_info['incident_reporting_id'];
            $sender_sid             = $sender_info['sender_sid'] == 0 ? $sender_info['manual_email'] : $sender_info['sender_sid'];

            $log_in_user_status     = is_manager_have_new_email($receiver_sid, $incident_sid);
            $status_one = 0;
            if ($log_in_user_status > 0) {
                $status_one = 1;
            }

            $current_user_status    = is_user_have_unread_message($receiver_sid, $sender_sid, $incident_sid);
            $status_two = 0;
            if ($current_user_status > 0) {
                $status_two = 1;
            }

            if (filter_var($sender_sid, FILTER_VALIDATE_EMAIL)) {
                $split_email = explode('@', $sender_sid);
                $sender_sid = $split_email[0];
            }

            $return_data                = array();
            $return_data['status_one']  = $status_one;
            $return_data['status_two']  = $status_two;
            $return_data['sender_sid']  = $sender_sid;

            echo json_encode($return_data);
        } else {
            echo 'success';
        }
    }
}
