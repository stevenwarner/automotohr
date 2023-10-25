<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Holds all public functions
 *
 * @package AutomotoHR
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 */
class App extends CI_Controller
{
    //
    private $css;
    private $js;
    private $header;
    private $footer;
    private $disableMinifiedFiles;
    //
    public function __construct()
    {
        parent::__construct();
        //
        $this->css = "public/v1/css/app/pages/";
        $this->js = "public/v1/js/app/pages/";
        //
        $this->header = "v1/app/header";
        $this->footer = "v1/app/footer";
    }

    // main website routes
    /**
     * why us route
     */
    public function whyUs()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
        }
        //
        $whyUsContent = getPageContent('why_us');
        // meta titles
        $data['meta'] = [];
        $data['meta']['title'] = $whyUsContent['page']['meta']['title'];
        $data['meta']['description'] = $whyUsContent['page']['meta']['description'];
        $data['meta']['keywords'] = $whyUsContent['page']['meta']['keywords'];
        // css
        $data['pageCSS'] = [
            'v1/plugins/bootstrap5/css/bootstrap.min',
            'v1/plugins/fontawesome/css/all',
        ];
        // css bundle
        $data['appCSS'] = bundleCSS([
            "v1/plugins/alertifyjs/css/alertify.min",
            'v1/app/css/theme',
            'v1/app/css/pages',
        ], $this->css, 'why_us', $this->disableMinifiedFiles);
        // js bundle
        $data['appJs'] = bundleJs([
            'v1/plugins/bootstrap5/js/bootstrap.bundle',
            'v1/plugins/alertifyjs/alertify.min',
            'js/jquery.validate.min',
            'js/app_helper',
            'v1/app/js/pages/home',
            'v1/app/js/pages/schedule_demo',
        ], $this->js, 'why_us', $this->disableMinifiedFiles);

        $data['whyUsContent'] = $whyUsContent;
        $this->load->view($this->header, $data);
        $this->load->view('v1/app/why_us');
        $this->load->view($this->footer);
    }

    // API routes
    /**
     * schedule your free demo process
     */
    public function scheduleDemoProcess()
    {
        // set rules
        $this->form_validation->set_rules('name', 'Please provide name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('email', 'Please provide valid email address ', 'trim|required|valid_email|xss_clean');
        $this->form_validation->set_rules('phone_number', 'Please provide valid number', 'trim|required|xss_clean');
        $this->form_validation->set_rules('company_name', 'Please provide your Company Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('title', 'Please provide your Title', 'trim|xss_clean');
        $this->form_validation->set_rules('company_size', 'Please provide your Company Size', 'trim|xss_clean');
        $this->form_validation->set_rules('newsletter_subscribe', 'Please select your choice', 'trim|xss_clean');
        $this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'required|callback_recaptcha');
        // run validation
        if (!$this->form_validation->run()) {
            return SendResponse(
                400,
                getFormErrors()
            );
        }
        //
        $client_source = 'schedule_your_free_demo';
        $first_name = $this->input->post('name', true);
        $email = $this->input->post('email', true);
        $phone_number = $this->input->post('phone_number', true);
        $company_name = $this->input->post('company_name', true);
        $job_role = $this->input->post('job_role', true);
        $company_size = $this->input->post('company_size', true);
        $newsletter_subscribe = $this->input->post('newsletter_subscribe', true);
        $date_requested = getSystemDate();
        $ppc = 0;
        $schedule_demo = NULL;
        $message = $this->input->post('client_message', true);
        //
        $this->load->model('Demo_model');
        $this->Demo_model->free_demo_new($first_name, $email, $phone_number, $company_name, $date_requested, $schedule_demo, $client_source, $ppc, $message, $company_size, $newsletter_subscribe, $job_role);
        $replacement_array['name'] = $first_name;
        $replacement_array['firstname'] = $first_name;
        $replacement_array['first_name'] = $first_name;
        $replacement_array['first-name'] = $first_name;
        $replacement_array['date_time'] = $schedule_demo;
        log_and_send_templated_email(DEMO_REQUEST_THANKYOU, $email, $replacement_array);
        //
        return SendResponse(200, ["msg" => "Thank you for your free demo request, we will contact you soon."]);
    }

    /**
     * validate google captcha
     * 
     * @param string $captchaCode
     * @return bool
     */
    public function recaptcha($captchaCode)
    {
        $this->form_validation->set_message('recaptcha', 'The reCAPTCHA field is telling me that you are a robot. Shall we give it another try?');
        //
        if (!$captchaCode) {
            return false;
        }
        //
        return validateCaptcha($captchaCode);
    }

    /**
     * SCORM parse
     */
    public function parseScorm($courseId)
    {
        // SCORM file name
        $filePath = $this->input->post('scorm_file');
        // get the file to local
        $zipFilePath = copyAWSFile($filePath);
        $uploadPath = str_replace('.zip', '', $zipFilePath);
        // extract the file
        $zip = new ZipArchive;
        $res = $zip->open($zipFilePath);
        //
        if ($res !== true) {
            // unable to extract the file
            return SendResponse(404, ['status' => false, 'errors' => ['Unable to unzip file.']]);
        }
        // extract the file
        $zip->extractTo($uploadPath);
        $zip->close();
        // set the "IMSmanifest" file
        $file = $uploadPath . '/imsmanifest.xml';
        // check if the file exists
        if (!file_exists($file)) {
            return SendResponse(404, ['status' => false, 'errors' => ['"IMSmanifest.xml" file is missing.']]);
        }
        // read the file
        $handler = fopen($file, 'r');
        $fileContents = fread($handler, filesize($file));
        fclose($handler);
        // load library
        $this->load->library('scorm/parser', [], 'scorm_parser');
        // content to JSON
        $scormInfo = $this
            ->scorm_parser
            ->setContent($fileContents)
            ->parse();
        //
        if (!$scormInfo) {
            return SendResponse(404, ['status' => false, 'errors' => ['Unable to read XML file.']]);
        }
        //
        if (isset($scormInfo['errors'])) {
            // Todo delete AWS file and also local one if version not matched
            return SendResponse(404, ['status' => false, 'errors' => $scormInfo['errors']]);
        }
        //
        $this->db->where("sid", $courseId);
        $this->db->update("lms_default_courses", ["Imsmanifist_json" => $scormInfo]);
        //
        return SendResponse(200, ['status' => true, 'success' => ['SCORM file read successfully.']]);
    }

    /**
     * preview s3 document
     */
    public function previewDocument(): array
    {
        // get post
        $post = $this->input->post(
            null,
            true
        );
        // set error array
        $errorArray = [];
        //
        if (!$post['key']) {
            $errorArray[] = '"File name" is missing.';
        }
        //
        if (!$post['ext']) {
            $errorArray[] = '"File extension" is missing.';
        }
        //
        if ($errorArray) {
            return SendResponse(
                404,
                [
                    'errors' => $errorArray
                ]
            );
        }
        //
        return SendResponse(
            200,
            [
                'view' => $this->load->view('v1/file/preview', $post, true)
            ]
        );
    }

    /**
     * download dile from AWS
     *
     * @param string $key
     * @return void
     */
    public function downloadFileFromAWSAndStream(string $key): void
    {
        downloadAWSFileToBrowser($key);
    }

    /**
     * Gusto webhook for company approval
     */
    public function gustoCompanyVerification()
    {
        // get incoming post
        $post = json_decode(
            file_get_contents('php://input'),
            true
        );
        // check for empty post
        if (!$post) {
            return SendResponse(400, ['errors' => ['"Data" is missing.']]);
        }
        //
        if ($post['verification_token']) {
            //
            $this->load->model('v1/payroll_model');
            //
            $gustoResponse = $this->payroll_model->callWebHook($post);
            //
            return SendResponse(
                $gustoResponse['errors'] ? 400 : 200,
                $gustoResponse
            );
        } else {
            // listen to the approval
            if ($post['event_type'] === 'company.approved') {
                $this->db
                    ->where('gusto_uuid', $post['resource_uuid'])
                    ->update(
                        'gusto_companies',
                        [
                            'status' => 'approved'
                        ]
                    );
                //
                return SendResponse(
                    200,
                    ['success' => true]
                );
            }
        }
        //
        return SendResponse(
            400,
            [
                'errors' => [
                    'No listeners found.'
                ]
            ]
        );
    }
}
