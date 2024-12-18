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
    private $commonFiles;
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
        $this->commonFiles = [
            "css"  => [],
            "js" => []
        ];
        $this->disableMinifiedFiles = true;
    }

    // main website routes
    /**
     * why us route
     */
    public function whyUs()
    {
        $this->output->cache(WEB_PAGE_CACHE_TIME_IN_MINUTES);
        //
        $whyUsContent = getPageContent('why_us');
        // meta titles
        $data['meta'] = [];
        $data['meta']['title'] = $whyUsContent['page']['meta']['title'];
        $data['meta']['description'] = $whyUsContent['page']['meta']['description'];
        $data['meta']['keywords'] = $whyUsContent['page']['meta']['keywords'];
        //
        $this->getCommon($data, "why-us");
        $data['whyUsContent'] = $whyUsContent;
        $data['pageContent'] = $pageContent;
        if(empty($whyUsContent)){
            $this->load->view('errors/html/error_404');

        }else{
        $this->load->view($this->header, $data);
        $this->load->view('v1/app/why_us');
        $this->load->view($this->footer);
        }
    }

    /**
     * about us us route
     */
    public function aboutUs()
    {
        $this->output->cache(WEB_PAGE_CACHE_TIME_IN_MINUTES);
        //
        $pageContent = getPageContent('about-us', true);
        // meta titles
        $data['meta'] = [];
        $data['meta']['title'] = $pageContent['page']['meta']['title'];
        $data['meta']['description'] = $pageContent['page']['meta']['description'];
        $data['meta']['keywords'] = $pageContent['page']['meta']['keywords'];
        //
        $this->getCommon($data, "about-us");
        //
        $data['pageContent'] = $pageContent;
        $this->load->view($this->header, $data);
        $this->load->view('v1/app/about_us');
        $this->load->view($this->footer);
    }

    /**
     * contact us us route
     */
    public function contactUs()
    {
        $this->output->cache(WEB_PAGE_CACHE_TIME_IN_MINUTES);
        //
        $pageContent = getPageContent('contact-us', true)["page"];
        // meta titles
        $data['meta'] = [];
        $data['meta']['title'] = $pageContent['meta']['title'];
        $data['meta']['description'] = $pageContent['meta']['description'];
        $data['meta']['keywords'] = $pageContent['meta']['keywords'];
        //
        $this->setCommon("v1/app/js/pages/contact_us", "js");
        $this->getCommon($data, "contact-us");
        //
        $data['pageContent'] = $pageContent;
        $this->load->view($this->header, $data);
        $this->load->view('v1/app/contact_us');
        $this->load->view($this->footer);
    }

    /**
     * privacy policy
     */
    public function privacyPolicy()
    {
        $this->output->cache(WEB_PAGE_CACHE_TIME_IN_MINUTES);
        //
        $pageContent = getPageContent('privacy-policy', true);
        // meta titles
        $data['meta'] = [];
        $data['meta']['title'] = $pageContent['page']['meta']['title'];
        $data['meta']['description'] = $pageContent['page']['meta']['description'];
        $data['meta']['keywords'] = $pageContent['page']['meta']['keywords'];
        //
        $this->getCommon($data, "privacy-policy");
        //
        $data['pageContent'] = $pageContent;
        if(empty($pageContent)){
            $this->load->view('errors/html/error_404');

        }else{
        $this->load->view($this->header, $data);
        $this->load->view('v1/app/privacy_policy');
        $this->load->view($this->footer);
        }
    }

    /**
     * terms of service
     */
    public function termsOfService()
    {
        $this->output->cache(WEB_PAGE_CACHE_TIME_IN_MINUTES);
        //
        $pageContent = getPageContent('terms-of-service', true);
        // meta titles
        $data['meta'] = [];
        $data['meta']['title'] = $pageContent['page']['meta']['title'];
        $data['meta']['description'] = $pageContent['page']['meta']['description'];
        $data['meta']['keywords'] = $pageContent['page']['meta']['keywords'];
        //
        $this->getCommon($data, "terms-of-service");
        //
        $data['pageContent'] = $pageContent;
        $this->load->view($this->header, $data);
        $this->load->view('v1/app/terms_of_service');
        $this->load->view($this->footer);
    }

    /**
     * site map
     */
    public function siteMap()
    {
        $this->output->cache(WEB_PAGE_CACHE_TIME_IN_MINUTES);
        //
        $pageContent = getPageContent('site_map', false);
        // meta titles
        $data['meta'] = [];
        $data['meta']['title'] = $pageContent['page']['meta']['title'];
        $data['meta']['description'] = $pageContent['page']['meta']['description'];
        $data['meta']['keywords'] = $pageContent['page']['meta']['keywords'];
        //
        $this->getCommon($data, "sitemap");
        $this->load->view($this->header, $data);
        $this->load->view('v1/app/sitemap');
        $this->load->view($this->footer);
    }

    /**
     * legal hub
     */
    public function legalHub()
    {
        $this->output->cache(WEB_PAGE_CACHE_TIME_IN_MINUTES);
        //
        $pageContent = getPageContent('legal', true);
        // meta titles
        $data['meta'] = [];
        $data['meta']['title'] = $pageContent['page']['meta']['title'];
        $data['meta']['description'] = $pageContent['page']['meta']['description'];
        $data['meta']['keywords'] = $pageContent['page']['meta']['keywords'];

        $this->setCommon("v1/app/css/legal", "css");
        //
        $this->getCommon($data, "sitemap");
        // 
        $data['pageContent'] = $pageContent;
        //
        $this->load->view($this->header, $data);
        $this->load->view('v1/app/legal');
        $this->load->view($this->footer);
    }

    /**
     * products
     *
     * @param string $productSlug
     */
    public function products(string $productSlug)
    {
        $this->output->cache(WEB_PAGE_CACHE_TIME_IN_MINUTES);
        //
        $pageContent = getPageContent($productSlug, true)["page"];

        // meta titles
        $data['meta'] = [];
        $data['meta']['title'] = $pageContent['meta']['title'];
        $data['meta']['description'] = $pageContent['meta']['description'];
        $data['meta']['keywords'] = $pageContent['meta']['keyword'];
        //
        $this->getCommon($data, "products");
        //
        $data["pageContent"] = $pageContent["sections"];
        //        
        if(empty($pageContent)){
            $this->load->view('errors/html/error_404');

        }else{
        $this->load->view($this->header, $data);
        $this->load->view('v1/app/products/main');
        $this->load->view($this->footer);
    }



    }

    /**
     * get your account
     */
    public function getYourAccount()
    {
        $this->output->cache(WEB_PAGE_CACHE_TIME_IN_MINUTES);
        //
        $pageContent = getPageContent('get_your_free_account', false)["page"];
        // meta titles
        $data['meta'] = [];
        $data['meta']['title'] = $pageContent['meta']['title'];
        $data['meta']['description'] = $pageContent['meta']['description'];
        $data['meta']['keywords'] = $pageContent['meta']['keywords'];
        //
        $this->getCommon($data, "get-your-account");
        //
        $data["pageContent"] = $pageContent;
        //
        $this->load->view($this->header, $data);
        $this->load->view('v1/app/get_your_account');
        $this->load->view($this->footer);
    }

    /**
     * affiliate
     */
    public function affiliateProgram()
    {
        $this->output->cache(WEB_PAGE_CACHE_TIME_IN_MINUTES);
        //
        $pageContent = getPageContent('affiliate-program', true);
        // meta titles
        $data['meta'] = [];
        $data['meta']['title'] = $pageContent['page']['meta']['title'];
        $data['meta']['description'] = $pageContent['page']['meta']['description'];
        $data['meta']['keywords'] = $pageContent['page']['meta']['keywords'];
        //
        $this->setCommon(
            "v1/affiliates/main",
            "js"
        );
        $this->getCommon($data, "affiliate-program");
        //
        $data["pageContent"] = $pageContent;
        //
        $this->load->model('affiliation_model');
        //
        $data["countries"] = $this->affiliation_model->get_all_countries();
        //
        if(empty($pageContent)){
            $this->load->view('errors/html/error_404');

        }else{
        $this->load->view($this->header, $data);
        $this->load->view('v1/app/affiliate');
        $this->load->view($this->footer);
        }
    }

    /**
     * set the common files
     *
     * @param string $filePath
     * @param string $type
     */
    private function setCommon(string $filePath, string $type = "css")
    {
        $this->commonFiles[$type][] = $filePath;
    }

    /**
     * set the common files
     *
     * @param array $data passed by reference
     */
    private function getCommon(&$data, string $page)
    {
        // css
        $data['pageCSS'] = [
            'v1/plugins/bootstrap5/css/bootstrap.min',
            'v1/plugins/fontawesome/css/all.min',
        ];
        //
        $appCSS =
            [
                "v1/plugins/alertifyjs/css/alertify.min",
                'v1/app/css/theme',
                'v1/app/css/pages'
            ];
        $appJs =
            [
                'v1/plugins/jquery/jquery-3.7.min',
                'v1/plugins/bootstrap5/js/bootstrap.bundle',
                'v1/plugins/alertifyjs/alertify.min',
                'js/jquery.validate.min',
                'js/app_helper',
                'v1/app/js/pages/home',
                'v1/app/js/pages/schedule_demo',
            ];

        // js
        $data['pageJs'] = [
            "https://www.google.com/recaptcha/api.js",
        ];
        // css bundle
        $data['appCSS'] = bundleCSS(array_merge($appCSS, $this->commonFiles["css"]), $this->css, $page, $this->disableMinifiedFiles);
        // js bundle
        $data['appJs'] = bundleJs(array_merge($appJs, $this->commonFiles["js"]), $this->js, $page, $this->disableMinifiedFiles);
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
        $this->form_validation->set_rules('country', 'Please select a country', 'trim|required|xss_clean');
        $this->form_validation->set_rules('state', 'Please select a state', 'trim|required|xss_clean');
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
        $country = $this->input->post('country', true);
        $state = $this->input->post('state', true);
        $newsletter_subscribe = $this->input->post('newsletter_subscribe', true);
        $date_requested = getSystemDate();
        $ppc = 0;
        $schedule_demo = NULL;
        $message = $this->input->post('client_message', true);
        //
        $this->load->model('Demo_model');
        $this->Demo_model->free_demo_new($first_name, $email, $phone_number, $company_name, $date_requested, $schedule_demo, $client_source, $ppc, $message, $company_size, $newsletter_subscribe, $job_role, 0, $country, $state);
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
     * contact us process
     */
    public function contactUsProcess()
    {
        $this->form_validation->set_rules('name', 'Name', 'required|trim|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|xss_clean|valid_email');
        $this->form_validation->set_rules('message', 'Message', 'required|trim|xss_clean|min_length[50]|strip_tags');
        $this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'required|callback_recaptcha[' . $this->input->post('g-recaptcha-response') . ']');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i> ', '</p>');
        // run validation
        if (!$this->form_validation->run()) {
            return SendResponse(
                400,
                getFormErrors()
            );
        }
        //
        $contact_name = $this->input->post('name', true);
        $contact_email = $this->input->post('email', true);
        $contact_message = strip_tags($this->input->post('message'));
        //
        if (preg_match('/.ru$/', $contact_email)) {
            return SendResponse(200, ["msg" => "<b>Success: </b>Thank you for your enquiry. We will get back to you!"]);
        }
        //
        $is_blocked_email = checkForBlockedEmail($contact_email);
        if ($is_blocked_email == 'not-blocked') {
            $from = FROM_EMAIL_NOTIFICATIONS;
            $subject = "Contact Us enquiry - " . STORE_NAME;
            $fromName = $contact_name;
            $replyTo = $contact_email;

            $body = EMAIL_HEADER
                . '<h2 style="width:100%; margin:0 0 20px 0;">New Contact Us Enquiry!</h2>'
                . '<br><b>Sender Name: </b>' . $fromName
                . '<br><b>Sender Email: </b>' . $contact_email
                . '<p><b>Message: </b>' . $contact_message . '</p>'
                . EMAIL_FOOTER;

            $system_notification_emails = get_system_notification_emails('free_demo_enquiry_emails');

            if (!empty($system_notification_emails)) {
                foreach ($system_notification_emails as $system_notification_email) {
                    if ($system_notification_email['email'] == 'steven@automotohr.com') {
                        continue;
                    }
                    sendMail($from, $system_notification_email['email'], $subject, $body, $fromName, $replyTo);
                }
            }
        }
        //
        return SendResponse(200, ["msg" => "<b>Success: </b>Thank you for your enquiry. We will get back to you!"]);
    }

    /**
     * contact us process
     */
    public function processAffiliateProgram()
    {

        $field = array(
            'field' => 'firstname',
            'label' => 'First Name',
            'rules' => 'xss_clean|trim|required'
        );

        $order_field = array(
            'field' => 'lastname',
            'label' => 'Last Name',
            'rules' => 'xss_clean|trim|required'
        );
        $config[] = $field;
        $config[] = $field;
        $config[] = $order_field;

        $this->form_validation->set_error_delimiters('<label class="error">', '</label>');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'required|callback_recaptcha[' . $this->input->post('g-recaptcha-response') . ']');
        // run validation
        if (!$this->form_validation->run()) {
            //
            $this->session->set_flashdata('errors', implode(',', getFormErrors()['errors']));
            return redirect('affiliate-program', 'refresh');
        }

        $post = $this->input->post(null, true);

        if (!filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
            $this->session->set_flashdata('errors', 'Affiliate Request Have Already Been Sent!');
            return redirect('affiliate-program', 'refresh');
        }

        //
        $this->load->model('affiliation_model');
        //
        $insert_data = array();
        $already_applied = $this->affiliation_model->check_register_affiliater($post['email']);

        if ($already_applied) {
            $this->session->set_flashdata('errors', 'Affiliate Request Have Already Been Sent!');
            redirect('affiliate-program', 'refresh');
        }

        $insert_data['first_name'] = $post['firstname'];
        $insert_data['last_name'] = $post['lastname'];
        $insert_data['email'] = $post['email'];
        $insert_data['paypal_email'] = $post['paypal_email'];
        $insert_data['company'] = $post['company'];
        $insert_data['street'] = $post['street'];
        $insert_data['city'] = $post['city'];
        $insert_data['state'] = $post['state'];
        $insert_data['zip_code'] = $post['zip'];
        $insert_data['country'] = $post['country'];
        $insert_data['method_of_promotion'] = $post['MOP'];
        $insert_data['website'] = $post['website'];
        $insert_data['special_notes'] = $post['info'];
        $insert_data['email_list'] = $post['no_of_names'];
        $insert_data['contact_number'] = $post['contact_number'];
        $insert_data['request_date'] = getSystemDate();
        $insert_data['ip_address'] = getUserIP();
        $insert_data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];

        if (isset($_FILES['w8_form']) && $_FILES['w8_form']['name'] != '') {
            $w8_form = upload_file_to_aws('w8_form', generateRandomString(4), 'w8_form');

            if ($w8_form != 'error') {
                $insert_data['w8_form'] = $w8_form;
            }
        }

        if (isset($_FILES['w9_form']) && $_FILES['w9_form']['name'] != '') {
            $w9_form = upload_file_to_aws('w9_form', generateRandomString(4), 'w9_form');

            if ($w9_form != 'error') {
                $insert_data['w9_form'] = $w9_form;
            }
        }


        $this->affiliation_model->insert_affiliation_form($insert_data);

        $from = FROM_EMAIL_NOTIFICATIONS;
        $subject = "Affiliate Program - New Request";
        $fromName = ucwords($insert_data['first_name'] . ' ' . $insert_data['last_name']);
        $replyTo = $insert_data['email'];

        $body = EMAIL_HEADER
            . '<h2 style="width:100%; margin:0 0 20px 0;">Affiliate Program Request!</h2>'
            . '<br><b>Applicant Name: </b>' . $fromName
            . '<br><b>Applicant Email: </b>' . $insert_data['email']
            . '<br><b>Contact Number: </b>' . $insert_data['contact_number']
            . '<br><b>Country: </b>' . $insert_data['country']
            . '<br>Login To Your Admin Panel For More Details'
            . EMAIL_FOOTER;

        //Send Emails Through System Notifications Email - Start
        $system_notification_emails = get_system_notification_emails('free_demo_enquiry_emails');

        if (!empty($system_notification_emails)) {
            foreach ($system_notification_emails as $system_notification_email) {
                sendMail($from, $system_notification_email['email'], $subject, $body, $fromName, $replyTo);
            }
        }

        $this->session->set_flashdata('success', '<strong>Success: </strong>Affiliate Request Submitted Successfully!');
        redirect('affiliate-program', 'refresh');
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
        $fileLanguage = $this->input->post('scorm_language');
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
        $insert_data = array();
        $insert_data['course_sid'] = $courseId;
        $insert_data['course_file_name'] = $filePath;
        $insert_data['course_file_language'] = $fileLanguage;
        $insert_data['Imsmanifist_json'] = $scormInfo;
        $insert_data['created_at'] = getSystemDate();
        $insert_data['updated_at'] = getSystemDate();
        //
        $this->db->insert('lms_scorm_courses', $insert_data);
        //
        // $this->db->where("sid", $courseId);
        // $this->db->update("lms_default_courses", ["Imsmanifist_json" => $scormInfo]);
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

    /**
     * get popups for front end
     */
    public function getPopup(string $pageName)
    {
        $pageData = $this->db
            ->select("content")
            ->where("page", $pageName)
            ->get("cms_pages_new")
            ->row_array();
        //
        if (!$pageData) {
            return SendResponse(400, ["errors" => ["No page found."]]);
        }
        //
        return SendResponse(200, [
            "view" => $this->load->view("v1/app/partials/popup", [
                "contentToShow" => json_decode($pageData["content"], true)["page"]["sections"]["section_0"]["details"]
            ], true)
        ]);
    }

    public function getStatesByCountry(string $countryName)
    {
        $pageData = $this->db
            ->select("state_name")
            ->where("country_sid", $countryName === "canada" ? 38 : 227)
            ->get("states")
            ->result_array();
        //
        if (!$pageData) {
            return SendResponse(400, ["errors" => ["No record found."]]);
        }
        //
        return SendResponse(200, array_column($pageData, "state_name"));
    }

    /**
     * Opt out of the EEOC
     *
     * @param int $eeoId
     * @return json
     */
    public function processOptOut(int $eeoId)
    {
        //
        $record = $this
            ->db
            ->select("users_type, application_sid")
            ->where("sid", $eeoId)
            ->get("portal_eeo_form")
            ->row_array();
        //
        if (!$record) {
            return SendResponse(
                400,
                [
                    "errors" => [
                        "Failed to verify EEOC form."
                    ]
                ]
            );
        }
        $this
            ->db
            ->where("sid", $eeoId)
            ->update(
                "portal_eeo_form",
                [
                    "is_opt_out" => 1,
                    "is_expired" => 1,
                    "last_completed_on" => getSystemDate()
                ]
            );

        keepTrackVerificationDocument(
            $record["application_sid"],
            $record["users_type"],
            'opt-out',
            $eeoId,
            'eeoc',
            'Document Center'
        );

        return SendResponse(200, [
            "message" => "You have successfully Opt-out of the EEOC."
        ]);
    }
}
