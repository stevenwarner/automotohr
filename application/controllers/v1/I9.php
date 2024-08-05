<?php defined('BASEPATH') or exit('No direct script access allowed');

class I9 extends Public_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('form_wi9_model', 'i9_model');
        $this->load->model('onboarding_model');
    }

    /**
     * I9 section one
     *
     */
    public function my()
    {
        // check session
        if (!$this->session->userdata('logged_in')) {
            return redirect('/login');
        }
        // set empty data array
        $data = [];
        $data['title'] = "My I9 Form";
        // get session
        $data['session'] = $this->session->userdata('logged_in');
        $data['employee'] = $data['session']['employer_detail'];
        $data['load_view'] = $data['session']['company_detail']['ems_status'];
        // set employer
        $data['loggedInPersonId'] = $data['session']['employer_detail']['sid'];
        $data['companyId'] = $data['session']['company_detail']['sid'];
        $data['companyName'] = ucwords($data['session']['company_detail']['CompanyName']);
        // get i9 form details
        $data['form'] = $this->i9_model->getI9Form(
            $data['loggedInPersonId'],
            'employee',
            'section1'
        );
        //
        $data['company_sid'] = $data['companyId'];
        $data['user_sid'] = $data['loggedInPersonId'];
        $data['user_type'] = 'employee';
        $pre_form = [];
        $pre_form['sid'] = $data['form']['sid'];
        $data['pre_form'] = $pre_form;
        $data['documents_assignment_sid'] = null;
        $data['prepare_signature'] = 'get_prepare_signature';
        $data['formType'] = "employee";
        // get states
        $data['states'] = $this->db
            ->select('state_code, state_name')
            ->where('active', 1)
            ->where('country_sid', USA_CODE)
            ->get('states')
            ->result_array();
        // load page scripts
        $data['PageScripts'] = [
            'js/app_helper',
            'v1/forms/i9/main'
        ];
        //
        $this->load
            ->view('main/header', $data)
            ->view('v1/forms/i9/my')
            ->view('main/footer');
    }

    /**
     * I9 section one print and download
     *
     * @param int    $formId
     * @param string $section
     */
    public function printOrDownload(string $action, int $formId)
    {
        // check session
        if (!$this->session->userdata('logged_in')) {
            return redirect('/login');
        }
        // set empty data array
        $data = [];
        $data['title'] = "My I9 Form";
        $data['session'] = $this->session->userdata('logged_in');
        //
        $formInfo = $this->i9_model->getI9FormById(
            $formId
        );
        //
        $data['form'] = $this->i9_model->getI9Form(
            $formInfo["user_sid"],
            $formInfo["user_type"],
            'section1'
        );
        //
        // 
        //
        $data['states'] = $this->db
            ->select('state_code, state_name')
            ->where('active', 1)
            ->where('country_sid', USA_CODE)
            ->get('states')
            ->result_array();
        // load page scripts
        $data['PageScripts'] = [];
        //
        $data['title'] = 'Form i-9';
        $data['pre_form'] = $data['form'];
        $data['section_access'] = "complete_pdf";
        //
        if ($action == "print") {
            $this->load->view('2022/federal_fillable/form_i9_print_new', $data);
        } else {
            $this->load->view('2022/federal_fillable/form_i9_download_new', $data);
        }
    }

    /**
     * I9 section one
     *
     */
    public function saveMy(): array
    {
        // check session
        if (!$this->session->userdata('logged_in')) {
            return SendResponse(
                401,
                [
                    'errors' => [
                        'You are not authorize to make this request. Please, re-login and try again.'
                    ]
                ]
            );
        }
        // set rules
        $rules = 'required|trim|xss_clean';
        //
        $ruleArray = [
            [
                'field' => 'form_code',
                'label' => 'Form key',
                'rules' => $rules . '|numeric',
            ],
            [
                'field' => 'section1_last_name',
                'label' => 'Last name',
                'rules' => $rules,
            ],
            [
                'field' => 'section1_first_name',
                'label' => 'First name',
                'rules' => $rules,
            ],
            [
                'field' => 'section1_address',
                'label' => 'Address',
                'rules' => $rules,
            ],
            [
                'field' => 'section1_city_town',
                'label' => 'City',
                'rules' => $rules
            ],
            [
                'field' => 'section1_state',
                'label' => 'State',
                'rules' => $rules . '|max_length[3]|min_length[2]'
            ],
            [
                'field' => 'section1_zip_code',
                'label' => 'Zip code',
                'rules' => $rules
            ],
            [
                'field' => 'section1_date_of_birth',
                'label' => 'Date of birth',
                'rules' => $rules . '|callback_check_age'
            ],
            [
                'field' => 'section1_social_security_number',
                'label' => 'Social security number',
                'rules' => $rules . '|exact_length[9]|numeric'
            ],
            [
                'field' => 'section1_emp_email_address',
                'label' => 'Email',
                'rules' => $rules . '|valid_email'
            ],
            [
                'field' => 'section1_emp_telephone_number',
                'label' => 'Telephone',
                'rules' => $rules
            ],
            [
                'field' => 'section1_signature',
                'label' => 'Employee signature',
                'rules' => $rules . '|valid_base64'
            ],
            [
                'field' => 'section1_today_date',
                'label' => 'Today date',
                'rules' => $rules
            ],
        ];
        // get post
        $post = $this->input->post(null, true);
        //
        if ($post['section1_penalty_of_perjury'] == 'alien-work') {
            $ruleArray[] = [
                'field' => 'alien_authorized_expiration_date',
                'label' => 'Expiration date',
                'rules' => $rules
            ];
            //
            if ($post['section1_alien_registration_number_two'] == 'USCIS-Number') {
                $ruleArray[] = [
                    'field' => 'section1_uscis_registration_number_one',
                    'label' => 'USCIS number',
                    'rules' => $rules . '|numeric'
                ];
            } elseif ($post['section1_alien_registration_number_two'] == 'Foreign-Number') {
                $ruleArray[] = [
                    'field' => 'foreign_passport_number',
                    'label' => 'Foreign number',
                    'rules' => $rules
                ];
                $ruleArray[] = [
                    'field' => 'country_of_issuance',
                    'label' => 'Country of issuance',
                    'rules' => $rules
                ];
            } else {
                $ruleArray[] = [
                    'field' => 'form_admission_number',
                    'label' => 'Form I-94 Admission number',
                    'rules' => $rules
                ];
            }
        } elseif ($post['section1_penalty_of_perjury'] == 'permanent-resident') {
            $ruleArray[] = [
                'field' => 'section1_uscis_registration_number_one',
                'label' => 'USCIS number',
                'rules' => $rules . '|numeric'
            ];
        }
        // set rules array
        $this->form_validation->set_rules($ruleArray);


        // run validation

        if ($this->form_validation->run() === false) {
            return SendResponse(
                400,
                [
                    'errors' => array_values($this->form_validation->error_array())
                ]
            );
        }
        //
        $i9Form = $this->i9_model->getI9FormById(
            $post['form_code'],
            [
                'section1_preparer_json'
            ]
        );
        //
        if (!$i9Form) {
            return SendResponse(
                400,
                [
                    'errors' => [
                        'I9 form is either disabled or not valid.'
                    ]
                ]
            );
        }
        //
        // lets make update array
        $updateArray = $post;
        // lets convert dates
        $updateArray['section1_date_of_birth'] = $updateArray['section1_date_of_birth']
            ? formatDateToDB(
                $updateArray['section1_date_of_birth'],
                SITE_DATE,
                DB_DATE
            ) : null;


        //
        $updateArray['section1_today_date'] = $updateArray['section1_today_date']
            ? formatDateToDB(
                $updateArray['section1_today_date'],
                SITE_DATE,
                DB_DATE
            ) : null;
        //
        $updateArray['alien_authorized_expiration_date'] = $updateArray['alien_authorized_expiration_date']
            ? formatDateToDB(
                $updateArray['alien_authorized_expiration_date'],
                SITE_DATE,
                DB_DATE
            ) : null;
        //
        $updateArray['section1_alien_registration_number'] = serialize(
            [
                'section1_alien_registration_number_two' => $updateArray['section1_alien_registration_number_two'],
                'section1_alien_registration_number_one' => $updateArray['section1_uscis_registration_number_one'],
                'alien_authorized_expiration_date' => $updateArray['alien_authorized_expiration_date'],
                'foreign_passport_number' => $updateArray['foreign_passport_number'],
                'country_of_issuance' => $updateArray['country_of_issuance'],
                'form_admission_number' => $updateArray['form_admission_number'],
            ]
        );
        //remove items
        unset(
            $updateArray['form_code'],
            $updateArray['section_1_other_last_names_used'],
            $updateArray['section1_alien_registration_number_two'],
            $updateArray['alien_authorized_expiration_date'],
            $updateArray['section1_uscis_registration_number_one'],
            $updateArray['foreign_passport_number'],
            $updateArray['country_of_issuance'],
            $updateArray['section1_signature'],
            $updateArray['form_admission_number'],
            $updateArray['section1_preparer'],
        );
        //
        $updateArray['section1_emp_signature'] = 'data:image/png;base64,' . $post['section1_signature'];
        $updateArray['section1_emp_signature_init'] = '';
        $updateArray['section1_emp_signature_ip_address'] = getUserIP();
        $updateArray['section1_emp_signature_user_agent'] =
            $_SERVER['HTTP_USER_AGENT'];
        //
        $updateArray['section1_other_last_names'] = $post['section_1_other_last_names_used'];
        $updateArray[$i9Form['user_type'] === 'employee' ? 'employer_filled_date' : 'applicant_filled_date'] = getSystemDate();
        //
        $updateArray['user_consent'] = 1;
        $updateArray['version'] = 2023;
        $updateArray['employer_flag'] = 0;
        $updateArray['employer_filled_date'] = '';
        //
        $translator = $this->input->post('section1_preparer', false);
        //
        if ($translator) {
            //
            $translatorArray = json_decode(
                $i9Form['section1_preparer_json'],
                true
            );
        }
        //
        foreach ($translator as $index => $preparer) {
            $preparer['today_date'] = formatDateToDB(
                $preparer['today_date'],
                SITE_DATE,
                DB_DATE
            );
            //
            $translatorArray[$index] = array_merge(
                $translatorArray[$index],
                $preparer
            );
        }
        //
        $updateArray['section1_preparer_json'] = json_encode($translatorArray);
        unset($updateArray['form_mode']);
        //
        $this->db
            ->where('sid', $post['form_code'])
            ->update(
                'applicant_i9form',
                $updateArray
            );
        //
        $returnURl = "";
        $returnFlag = "false";
        //
        return SendResponse(
            200,
            [
                'success' => true,
                'message' => 'You have successfully completed the I9 form.',
                'return' =>  $returnFlag,
                'URL' =>  $returnURl
            ]
        );
    }

    /**
     * get the preparer id
     */
    public function getPreparerSignature(int $index, int $i9Id): array
    {
        // get the i9 preparer signatures
        $details = $this->db
            ->select('section1_preparer_json')
            ->where('sid', $i9Id)
            ->get('applicant_i9form')
            ->row_array();

        //
        if (!$details) {
            return SendResponse(
                200,
                [
                    'success' => true,
                    'data' => []
                ]
            );
        }
        //
        $details = json_decode($details['section1_preparer_json'], true);
        //
        if (!isset($details[$index])) {
            return SendResponse(
                200,
                [
                    'success' => true,
                    'data' => []
                ]
            );
        }
        //
        if (empty($details[$index]['signature'])) {
            return SendResponse(
                200,
                [
                    'success' => true,
                    'data' => []
                ]
            );
        }
        //
        return SendResponse(
            200,
            [
                'success' => true,
                'data' => [
                    'signature' => $details[$index]['signature']
                ]
            ]
        );
    }

    /**
     * save the preparer id
     */
    public function savePreparerSignature(int $index, int $i9Id): array
    {
        // get the i9 preparer signatures
        $details = $this->db
            ->select('section1_preparer_json')
            ->where('sid', $i9Id)
            ->get('applicant_i9form')
            ->row_array();

        //
        if (!$details) {
            return SendResponse(
                200,
                [
                    'success' => true,
                    'data' => []
                ]
            );
        }
        $details = json_decode($details['section1_preparer_json'], true);
        //
        $post = $this->input->post(null, true);
        //
        $details[$index] = [
            'signature' => $this->input->post('drawn_signature', false),
            'initial' => $this->input->post('init_signature', false),
            'user_agent' => $post['user_agent'],
            'ip_address' => $post['ip_address'],
            'last_name' => '',
            'first_name' => '',
            'middle_initial' => '',
            'address' => '',
            'city' => '',
            'state' => '',
            'zip_code' => '',
            'today_date' => '',
        ];
        //
        $this->db
            ->where('sid', $i9Id)
            ->update('applicant_i9form', [
                'section1_preparer_json' => json_encode($details)
            ]);
        //
        return SendResponse(
            200,
            [
                'success' => true
            ]
        );
    }

    /**
     * get the preparer id
     */
    public function getAuthorizedSignature(): array
    {
        //
        $session = $this->session->userdata('logged_in');
        $employeeId = $session['employer_detail']['sid'];
        // get the i9 preparer signatures
        $details = $this->db
            ->select('signature_bas64_image')
            ->where('user_sid', $employeeId)
            ->where('user_type', "employee")
            ->where('is_active', 1)
            ->get('e_signatures_data')
            ->row_array();

        //
        if (!$details) {
            return SendResponse(
                200,
                [
                    'success' => true,
                    'data' => []
                ]
            );
        }
        //
        if (!$details["signature_bas64_image"]) {
            return SendResponse(
                200,
                [
                    'success' => true,
                    'data' => []
                ]
            );
        }

        return SendResponse(
            200,
            [
                'success' => true,
                'data' => [
                    'signature' => $details['signature_bas64_image']
                ]
            ]
        );
    }

    /**
     * get the preparer id
     */
    public function saveAuthorizedSection($formId): array
    {
        if ($this->session->userdata('logged_in')) {
            $session = $this->session->userdata('logged_in');
            $filler_sid = $session['employer_detail']['sid'];
            $employer_sid = $session['employer_detail']['sid'];
            $company_sid = $session['company_detail']['sid'];
            //
            $formInfo = $this->i9_model->getI9FormById($formId);
            //
            $previous_form = $this->i9_model->getI9Form(
                $formInfo['user_sid'],
                $formInfo['user_type']
            );
            //
            $formpost = $this->input->post(NULL, TRUE);
            //
            if ($previous_form['user_consent'] == 1) {
                //
                if ($type != 'applicant' && $formpost['user_consent'] == 1) {
                    // Send document completion alert
                    broadcastAlert(
                        DOCUMENT_NOTIFICATION_ACTION_TEMPLATE,
                        'documents_status',
                        'i9_completed',
                        $company_sid,
                        $session['company_detail']['CompanyName'],
                        $session['employer_detail']['first_name'],
                        $session['employer_detail']['last_name'],
                        $session['employer_detail']['sid']
                    );
                }
                //
                $updateArray = array();
                //
                $updateArray['section2_last_name'] = $formpost['section2_last_name'];
                $updateArray['section2_first_name'] = $formpost['section2_first_name'];
                $updateArray['section2_middle_initial'] = $formpost['section2_middle_initial'];
                $updateArray['section2_citizenship'] = $formpost['section2_citizenship'];

                $updateArray['section2_lista_part1_document_title'] = $formpost['lista_part1_doc_select_input'] != 'input' ? $formpost['section2_lista_part1_document_title'] : $formpost['section2_lista_part1_document_title_text_val'];
                $updateArray['section2_lista_part1_issuing_authority'] = isset($formpost['section2_lista_part1_issuing_authority']) && $formpost['lista_part1_issuing_select_input'] != 'input' ? $formpost['section2_lista_part1_issuing_authority'] : $formpost['section2_lista_part1_issuing_authority_text_val'];
                $updateArray['section2_lista_part1_document_number'] = $formpost['section2_lista_part1_document_number'];
                $updateArray['section2_lista_part1_expiration_date'] = empty($formpost['section2_lista_part1_expiration_date']) || $formpost['section2_lista_part1_expiration_date'] == 'N/A' ? null : DateTime::createFromFormat('m-d-Y', $formpost['section2_lista_part1_expiration_date'])->format('Y-m-d H:i:s');
                $updateArray['section2_lista_part2_document_title'] = $formpost['lista_part2_doc_select_input'] != 'input' ? $formpost['section2_lista_part2_document_title'] : $formpost['section2_lista_part2_document_title_text_val'];
                $updateArray['section2_lista_part2_issuing_authority'] = isset($formpost['section2_lista_part2_issuing_authority']) && $formpost['lista_part2_issuing_select_input'] != 'input' ? $formpost['section2_lista_part2_issuing_authority'] : $formpost['section2_lista_part2_issuing_authority_text_val'];
                $updateArray['section2_lista_part2_document_number'] = $formpost['section2_lista_part2_document_number'];
                $updateArray['section2_lista_part2_expiration_date'] = empty($formpost['section2_lista_part2_expiration_date']) || $formpost['section2_lista_part2_expiration_date'] == 'N/A' ? null : DateTime::createFromFormat('m-d-Y', $formpost['section2_lista_part2_expiration_date'])->format('Y-m-d H:i:s');
                $updateArray['section2_lista_part3_document_title'] = $formpost['lista_part3_doc_select_input'] != 'input' ? $formpost['section2_lista_part3_document_title'] : $formpost['section2_lista_part3_document_title_text_val'];
                $updateArray['section2_lista_part3_issuing_authority'] = isset($formpost['section2_lista_part3_issuing_authority']) && $formpost['lista_part3_doc_select_input'] != 'input' ? $formpost['section2_lista_part3_issuing_authority'] : $formpost['section2_lista_part3_issuing_authority_text_val'];
                $updateArray['section2_lista_part3_document_number'] = $formpost['section2_lista_part3_document_number'];
                $updateArray['section2_lista_part3_expiration_date'] = empty($formpost['section2_lista_part3_expiration_date']) || $formpost['section2_lista_part3_expiration_date'] == 'N/A' ? null : DateTime::createFromFormat('m-d-Y', $formpost['section2_lista_part3_expiration_date'])->format('Y-m-d H:i:s');
                $updateArray['section2_additional_information'] = $formpost['section2_additional_information'];

                $updateArray['listb_auth_select_input'] = isset($formpost['listb-auth-select-input']) ? $formpost['listb-auth-select-input'] : '';
                $updateArray['lista_part1_doc_select_input'] = isset($formpost['lista_part1_doc_select_input']) ? $formpost['lista_part1_doc_select_input'] : '';
                $updateArray['lista_part1_issuing_select_input'] = isset($formpost['lista_part1_issuing_select_input']) ? $formpost['lista_part1_issuing_select_input'] : '';
                $updateArray['lista_part2_doc_select_input'] = isset($formpost['lista_part2_doc_select_input']) ? $formpost['lista_part2_doc_select_input'] : '';
                $updateArray['lista_part2_issuing_select_input'] = isset($formpost['lista_part2_issuing_select_input']) ? $formpost['lista_part2_issuing_select_input'] : '';
                $updateArray['lista_part3_doc_select_input'] = isset($formpost['lista_part3_doc_select_input']) ? $formpost['lista_part3_doc_select_input'] : '';
                $updateArray['lista_part3_issuing_select_input'] = isset($formpost['lista_part3_issuing_select_input']) ? $formpost['lista_part3_issuing_select_input'] : '';

                $updateArray['section2_listb_document_title'] = $formpost['section2_listb_document_title'];
                $updateArray['section2_listb_issuing_authority'] = isset($formpost['section2_listb_issuing_authority']) && $formpost['listb-auth-select-input'] != 'input' ? $formpost['section2_listb_issuing_authority'] : $formpost['section2_listb_issuing_authority_text_val'];
                $updateArray['section2_listb_document_number'] = $formpost['section2_listb_document_number'];
                $updateArray['section2_listb_expiration_date'] = empty($formpost['section2_listb_expiration_date']) || $formpost['section2_listb_expiration_date'] == 'N/A' ? null : DateTime::createFromFormat('m-d-Y', $formpost['section2_listb_expiration_date'])->format('Y-m-d H:i:s');

                $updateArray['section2_listc_document_title'] = $formpost['section2_listc_document_title'];
                $updateArray['section2_listc_dhs_extra_field'] = $formpost['section2_listc_dhs_extra_field'];
                $updateArray['listc_auth_select_input'] = isset($formpost['listc-auth-select-input']) ? $formpost['listc-auth-select-input'] : '';
                $updateArray['section2_listc_issuing_authority'] = isset($formpost['section2_listc_issuing_authority']) && $formpost['listc-auth-select-input'] != 'input' ? $formpost['section2_listc_issuing_authority'] : $formpost['section2_listc_issuing_authority_text_val'];
                $updateArray['section2_listc_document_number'] = $formpost['section2_listc_document_number'];
                $updateArray['section2_listc_expiration_date'] = empty($formpost['section2_listc_expiration_date']) || $formpost['section2_listc_expiration_date'] == 'N/A' ? null : DateTime::createFromFormat('m-d-Y', $formpost['section2_listc_expiration_date'])->format('Y-m-d H:i:s');

                $updateArray['section2_firstday_of_emp_date'] = empty($formpost['section2_firstday_of_emp_date']) || $formpost['section2_firstday_of_emp_date'] == 'N/A' ? null : DateTime::createFromFormat('m-d-Y', $formpost['section2_firstday_of_emp_date'])->format('Y-m-d H:i:s');
                $updateArray['section2_sig_emp_auth_rep'] = $this->input->post('section2_sig_emp_auth_rep', FALSE);

                $updateArray['section2_today_date'] = empty($formpost['section2_today_date']) || $formpost['section2_today_date'] == 'N/A' ? null : DateTime::createFromFormat('m-d-Y', $formpost['section2_today_date'])->format('Y-m-d H:i:s');
                $updateArray['section2_title_of_emp'] = $formpost['section2_title_of_emp'];
                $updateArray['section2_last_name_of_emp'] = $formpost['section2_last_name_of_emp'];
                $updateArray['section2_first_name_of_emp'] = $formpost['section2_first_name_of_emp'];
                $updateArray['section2_emp_business_name'] = $formpost['section2_emp_business_name'];
                $updateArray['section2_emp_business_address'] = $formpost['section2_emp_business_address'];
                $updateArray['section2_city_town'] = $formpost['section2_city_town'];
                $updateArray['section2_state'] = $formpost['section2_state'];
                $updateArray['section2_zip_code'] = $formpost['section2_zip_code'];
                $updateArray['section2_alternative_procedure'] = isset($formpost['section2_authorized_alternative_procedure']) ? 1 : 0;
                //
                $details = [];
                // 
                for ($i = 1; $i <= 3; $i++) {
                    $details[$i] = [
                        'section3_rehire_date' => $formpost['section3_authorized_rehire_date_' . $i],
                        'section3_last_name' => $formpost['section3_authorized_last_name_' . $i],
                        'section3_first_name' => $formpost['section3_authorized_first_name_' . $i],
                        'section3_middle_initial' => $formpost['section3_authorized_middle_initial_' . $i],
                        'section3_document_title' => $formpost['section3_authorized_document_title_' . $i],
                        'section3_document_number' => $formpost['section3_authorized_document_number_' . $i],
                        'section3_expiration_date' => $formpost['section3_authorized_expiration_date_' . $i],
                        'section3_name_of_emp' => $formpost['section3_authorized_name_of_emp_' . $i],
                        'signature' => $this->input->post('section3_authorized_signature_' . $i, FALSE),
                        'section3_signature_date' => !empty($formpost['section3_authorized_today_date_' . $i]) ? $formpost['section3_authorized_today_date_' . $i] : "",
                        'section3_additional_information' => $formpost['section3_authorized_additional_information_' . $i],
                        'section3_alternative_procedure' => isset($formpost['section3_authorized_alternative_procedure_' . $i]) ? 1 : 0,
                    ];
                    //
                }
                //
                $updateArray['section3_authorized_json'] = json_encode($details);
                $updateArray['emp_app_sid'] = $employer_sid;
                $updateArray['employer_flag'] = 1;
                $updateArray['employer_filled_date'] = date('Y-m-d H:i:s');
                // Section 2,3 Ends
            } else {
                $updateArray['section1_last_name'] = $formpost['section1_last_name'];
                $updateArray['section1_first_name'] = $formpost['section1_first_name'];
                $updateArray['section1_middle_initial'] = $formpost['section1_middle_initial'];
                $updateArray['section1_other_last_names'] = $formpost['section1_other_last_names'];
                $updateArray['section1_address'] = $formpost['section1_address'];
                $updateArray['section1_apt_number'] = $formpost['section1_apt_number'];
                $updateArray['section1_city_town'] = $formpost['section1_city_town'];
                $updateArray['section1_state'] = $formpost['section1_state'];
                $updateArray['section1_zip_code'] = $formpost['section1_zip_code'];
                $updateArray['section1_date_of_birth'] = empty($formpost['section1_date_of_birth']) || $formpost['section1_date_of_birth'] == 'N/A' ? null : DateTime::createFromFormat('m-d-Y', $formpost['section1_date_of_birth'])->format('Y-m-d H:i:s');
                $updateArray['section1_social_security_number'] = $formpost['section1_social_security_number'];
                $updateArray['section1_emp_email_address'] = $formpost['section1_emp_email_address'];
                $updateArray['section1_emp_telephone_number'] = $formpost['section1_emp_telephone_number'];
                $updateArray['section1_penalty_of_perjury'] = $formpost['section1_penalty_of_perjury'];
                //
                $options = array();
                if ($formpost['section1_penalty_of_perjury'] == 'permanent-resident') {
                    $options['section1_alien_registration_number_one'] = $formpost['section1_alien_registration_number_one'];
                    $options['section1_alien_registration_number_two'] = $formpost['section1_alien_registration_number_two'];
                } elseif ($formpost['section1_penalty_of_perjury'] == 'alien-work') {
                    $options['section1_alien_registration_number_one'] = $formpost['section1_alien_registration_number_one'];
                    $options['section1_alien_registration_number_two'] = $formpost['section1_alien_registration_number_two'];
                    $options['alien_authorized_expiration_date'] = empty($formpost['alien_authorized_expiration_date']) || $formpost['alien_authorized_expiration_date'] == 'N/A' ? null : DateTime::createFromFormat('m-d-Y', $formpost['alien_authorized_expiration_date'])->format('Y-m-d H:i:s');
                    $options['form_admission_number'] = $formpost['form_admission_number'];
                    $options['foreign_passport_number'] = $formpost['foreign_passport_number'];
                    $options['country_of_issuance'] = $formpost['country_of_issuance'];
                }

                $updateArray['section1_alien_registration_number'] = serialize($options);
                $updateArray['section1_today_date'] = empty($formpost['section1_today_date']) || $formpost['section1_today_date'] == 'N/A' ? null : DateTime::createFromFormat('m-d-Y', $formpost['section1_today_date'])->format('Y-m-d H:i:s');
                $updateArray['version'] = 2023;
            }




            // Log i9 form
            $i9TrackerData = [];
            $i9TrackerData['data'] = $updateArray;
            $i9TrackerData['loggedIn_person_id'] = $employer_sid;
            $i9TrackerData['previous_form_sid'] = $previous_form['emp_app_sid'];
            $i9TrackerData['session_id'] = session_id();
            $i9TrackerData['session_employer_id'] = $session['employer_detail']['sid'];
            $i9TrackerData['session_company_id'] = $session['company_detail']['sid'];
            $i9TrackerData['reviewer_signature_base64'] =  $formpost['section2_sig_emp_auth_rep'];
            $i9TrackerData['module'] = $sid ? 'fi9/gp' : 'fi9/bp';
            //
            portalFormI9Tracker($employer_sid, "employee", $i9TrackerData);

            $this->db
                ->where('sid', $formId)
                ->update(
                    'applicant_i9form',
                    $updateArray
                );
            //
            $i9_sid = getVerificationDocumentSid($employer_sid, "employee", 'i9');
            keepTrackVerificationDocument($employer_sid, "employee", 'completed', $i9_sid, 'i9', 'Blue Panel');
            //
            $this->session->set_flashdata('message', '<strong>Success: </strong> I-9 Submitted Successfully!');
            redirect(base_url('hr_documents_management/documents_assignment') . '/' . $formInfo['user_type'] . '/' . $formInfo['user_sid'], 'refresh');
        } else {
            redirect('login', "refresh");
        }
    }

    public function getUserSection(string $userType, int $userId, string $formMode)
    {
        // set empty data array
        $data = [];
        $data['title'] = "My I9 Form";
        // get session
        if ($userType == "applicant") {
            $applicant_details = $this->i9_model->getApplicantInformation($userId);
            $data['applicant'] = $applicant_details;
            //
            $data['company_sid'] = $applicant_details['employer_sid'];
            $data['load_view'] = check_company_ems_status($applicant_details['employer_sid']);
            $data['companyName'] = getCompanyNameBySid($applicant_details['employer_sid']);
            $data['loggedInPersonId'] = $userId;
            //
            $company_detail = $this->i9_model->getCompanyDetail($applicant_details['employer_sid']);
            $uniqueId = $this->i9_model->getApplicantUniqueId($userId, $applicant_details['employer_sid']);
            //
            $data['session']['company_detail'] = $company_detail;
            $data['unique_sid'] = $uniqueId;
        }
        //
        // get i9 form details
        $data['form'] = $this->i9_model->getI9Form(
            $userId,
            $userType,
            'section1'
        );
        //
        $data['user_sid'] = $userId;
        $data['user_type'] = $userType;
        $pre_form = [];
        $pre_form['sid'] = $data['form']['sid'];
        $data['pre_form'] = $pre_form;
        $data['documents_assignment_sid'] = null;
        $data['prepare_signature'] = 'get_prepare_signature';
        $data['formMode'] = $formMode;
        $data['formType'] = $userType;
        // get states
        $data['states'] = $this->db
            ->select('state_code, state_name')
            ->where('active', 1)
            ->where('country_sid', USA_CODE)
            ->get('states')
            ->result_array();
        // load page scripts
        $data['PageScripts'] = [
            'js/app_helper',
            'v1/forms/i9/main'
        ];
        //
        if ($userType == "applicant") {
            //
            $i9Form = $this->i9_model->getI9FormById(
                $data['form']['sid'],
                [
                    'link_creation_time',
                    'user_consent'
                ]
            );
            //
            if ($formMode == "public_link" && ($i9Form['link_creation_time'] <= strtotime('now') || (isset($i9Form['user_consent']) && $i9Form['user_consent'] == 1))) {
                redirect("forms/i9/expired");
            }
            //
            if ($formMode == "public_link") {
                $this->load->view('main/public_header', $data);
            } else {
                $this->load->view('onboarding/applicant_boarding_header', $data);
            }
            //
            $this->load->view('v1/forms/i9/my');
            $this->load->view('onboarding/on_boarding_footer');

            //
        } else {
            $this->load
                ->view('main/header', $data)
                ->view('v1/forms/i9/my')
                ->view('main/footer');
        }
    }

    public function saveUserSection()
    {

        // set rules
        $rules = 'required|trim|xss_clean';
        //
        $ruleArray = [
            [
                'field' => 'form_code',
                'label' => 'Form key',
                'rules' => $rules . '|numeric',
            ],
            [
                'field' => 'section1_last_name',
                'label' => 'Last name',
                'rules' => $rules,
            ],
            [
                'field' => 'section1_first_name',
                'label' => 'First name',
                'rules' => $rules,
            ],
            [
                'field' => 'section1_address',
                'label' => 'Address',
                'rules' => $rules,
            ],
            [
                'field' => 'section1_city_town',
                'label' => 'City',
                'rules' => $rules
            ],
            [
                'field' => 'section1_state',
                'label' => 'State',
                'rules' => $rules . '|max_length[3]|min_length[2]'
            ],
            [
                'field' => 'section1_zip_code',
                'label' => 'Zip code',
                'rules' => $rules
            ],
            [
                'field' => 'section1_date_of_birth',
                'label' => 'Date of birth',
                'rules' => $rules.'|callback_check_age'
            ],
            [
                'field' => 'section1_social_security_number',
                'label' => 'Social security number',
                'rules' => $rules . '|exact_length[9]|numeric'
            ],
            [
                'field' => 'section1_emp_email_address',
                'label' => 'Email',
                'rules' => $rules . '|valid_email'
            ],
            [
                'field' => 'section1_emp_telephone_number',
                'label' => 'Telephone',
                'rules' => $rules
            ],
            [
                'field' => 'section1_signature',
                'label' => 'Employee signature',
                'rules' => $rules . '|valid_base64'
            ],
            [
                'field' => 'section1_today_date',
                'label' => 'Today date',
                'rules' => $rules
            ],
        ];
        // get post
        $post = $this->input->post(null, true);
        //
        if ($post['section1_penalty_of_perjury'] == 'alien-work') {
            $ruleArray[] = [
                'field' => 'alien_authorized_expiration_date',
                'label' => 'Expiration date',
                'rules' => $rules
            ];
            //
            if ($post['section1_alien_registration_number_two'] == 'USCIS-Number') {
                $ruleArray[] = [
                    'field' => 'section1_uscis_registration_number_one',
                    'label' => 'USCIS number',
                    'rules' => $rules . '|numeric'
                ];
            } elseif ($post['section1_alien_registration_number_two'] == 'Foreign-Number') {
                $ruleArray[] = [
                    'field' => 'foreign_passport_number',
                    'label' => 'Foreign number',
                    'rules' => $rules
                ];
                $ruleArray[] = [
                    'field' => 'country_of_issuance',
                    'label' => 'Country of issuance',
                    'rules' => $rules
                ];
            } else {
                $ruleArray[] = [
                    'field' => 'form_admission_number',
                    'label' => 'Form I-94 Admission number',
                    'rules' => $rules
                ];
            }
        } elseif ($post['section1_penalty_of_perjury'] == 'permanent-resident') {
            $ruleArray[] = [
                'field' => 'section1_uscis_registration_number_one',
                'label' => 'USCIS number',
                'rules' => $rules . '|numeric'
            ];
        }
        // set rules array
        $this->form_validation->set_rules($ruleArray);
        // run validation
        if ($this->form_validation->run() === false) {
            return SendResponse(
                400,
                [
                    'errors' => array_values($this->form_validation->error_array())
                ]
            );
        }
        //
        $i9Form = $this->i9_model->getI9FormById(
            $post['form_code'],
            [
                'section1_preparer_json'
            ]
        );
        //
        if (!$i9Form) {
            return SendResponse(
                400,
                [
                    'errors' => [
                        'I9 form is either disabled or not valid.'
                    ]
                ]
            );
        }
        // lets make update array
        $updateArray = $post;
        // lets convert dates
        $updateArray['section1_date_of_birth'] = $updateArray['section1_date_of_birth']
            ? formatDateToDB(
                $updateArray['section1_date_of_birth'],
                SITE_DATE,
                DB_DATE
            ) : null;
        //
        $updateArray['section1_today_date'] = $updateArray['section1_today_date']
            ? formatDateToDB(
                $updateArray['section1_today_date'],
                SITE_DATE,
                DB_DATE
            ) : null;
        //
        $updateArray['alien_authorized_expiration_date'] = $updateArray['alien_authorized_expiration_date']
            ? formatDateToDB(
                $updateArray['alien_authorized_expiration_date'],
                SITE_DATE,
                DB_DATE
            ) : null;
        //
        $updateArray['section1_alien_registration_number'] = serialize(
            [
                'section1_alien_registration_number_two' => $updateArray['section1_alien_registration_number_two'],
                'section1_alien_registration_number_one' => $updateArray['section1_uscis_registration_number_one'],
                'alien_authorized_expiration_date' => $updateArray['alien_authorized_expiration_date'],
                'foreign_passport_number' => $updateArray['foreign_passport_number'],
                'country_of_issuance' => $updateArray['country_of_issuance'],
                'form_admission_number' => $updateArray['form_admission_number'],
            ]
        );
        //remove items
        unset(
            $updateArray['form_code'],
            $updateArray['section_1_other_last_names_used'],
            $updateArray['section1_alien_registration_number_two'],
            $updateArray['alien_authorized_expiration_date'],
            $updateArray['section1_uscis_registration_number_one'],
            $updateArray['foreign_passport_number'],
            $updateArray['country_of_issuance'],
            $updateArray['section1_signature'],
            $updateArray['form_admission_number'],
            $updateArray['section1_preparer'],
        );
        //
        $updateArray['section1_emp_signature'] = 'data:image/png;base64,' . $post['section1_signature'];
        $updateArray['section1_emp_signature_init'] = '';
        $updateArray['section1_emp_signature_ip_address'] = getUserIP();
        $updateArray['section1_emp_signature_user_agent'] =
            $_SERVER['HTTP_USER_AGENT'];
        //
        $updateArray['section1_other_last_names'] = $post['section_1_other_last_names_used'];
        $updateArray[$i9Form['user_type'] === 'employee' ? 'employer_filled_date' : 'applicant_filled_date'] = getSystemDate();
        //
        $updateArray['user_consent'] = 1;
        $updateArray['version'] = 2023;
        $updateArray['employer_flag'] = 0;
        $updateArray['employer_filled_date'] = '';
        //
        $translator = $this->input->post('section1_preparer', false);
        //
        if ($translator) {
            //
            $translatorArray = json_decode(
                $i9Form['section1_preparer_json'],
                true
            );
        }
        //
        foreach ($translator as $index => $preparer) {
            $preparer['today_date'] = formatDateToDB(
                $preparer['today_date'],
                SITE_DATE,
                DB_DATE
            );
            //
            $translatorArray[$index] = array_merge(
                $translatorArray[$index],
                $preparer
            );
        }
        //
        $updateArray['section1_preparer_json'] = json_encode($translatorArray);
        unset($updateArray['form_mode']);
        //
        $this->db
            ->where('sid', $post['form_code'])
            ->update(
                'applicant_i9form',
                $updateArray
            );
        //
        $fromPage = "Blue Panel";
        $returnURl = "";
        $returnFlag = "false";
        //
        if ($i9Form['user_type'] == "applicant") {
            if ($post['form_mode'] == "public_link") {
                $fromPage = "Public Link";
                $returnURl = base_url("thank_you");
                $returnFlag = "true";
            } else {
                $fromPage = "Onboarding Panel";
            }
        }
        // 
        keepTrackVerificationDocument($i9Form['user_sid'], $i9Form['user_type'], 'completed', $post['form_code'], 'i9', $fromPage);
        //
        return SendResponse(
            200,
            [
                'success' => true,
                'message' => 'You have successfully completed the I9 form.',
                'return' =>  $returnFlag,
                'URL' =>  $returnURl,
            ]
        );
    }

    public function publicLinkExpired()
    {
        //
        $this->load->view('public/documents/expired_public');
    }

    /**
     * Modify I9 form
     * Employers can modify the I9 form after
     * the form is assigned to the user. This
     * will only prefill/update the data without
     * consent
     * @param string $userType
     * @param int $userId
     * @version 1.0
     */
    public function employerModifyI9(
        string $userType,
        int $userId
    ) {
        // check if form is assigned or not
        if (
            !$this
                ->i9_model
                ->isI9FormAssigned(
                    $userType,
                    $userId
                )
        ) {
            $this->session
                ->set_flashdata(
                    "message",
                    "
                <strong>Errors: </strong>
                The I9 form is not assigned.
            "
                );
            return redirect(
                "hr_documents_management/documents_assignment/{$userType}/{$userId}"
            );
        }
        // form is assigned
        //
        $data = [];

        $data['session'] = $this->session->userdata('logged_in');
        $data['security_details'] = db_get_access_level_details($data['session']['employer_detail']['sid']);
        // no need to check in this Module as Dashboard will be available to all
        $company_sid = $data['session']['company_detail']['sid'];
        $data['company_sid'] = $company_sid;
        //
        $user_info = array();
        $company_name = $data['session']['company_detail']['CompanyName'];
        $data['company_name'] = $company_name;
        // load model
        $this->load->model("hr_documents_management_model");
        //
        switch ($userType) {
            case 'employee':
                $user_info = $this->hr_documents_management_model->get_employee_information($company_sid, $userId);

                if (empty($user_info)) {
                    $this->session->set_flashdata('message', '<strong>Error:</strong> Employee Not Found!');
                    redirect('employee_management', 'refresh');
                }

                $data = employee_right_nav($userId, $data);
                $left_navigation = 'manage_employer/employee_management/profile_right_menu_employee_new';
                $data['applicant_average_rating'] = $this->hr_documents_management_model->getApplicantAverageRating($userId, 'employee'); // getting applicant ratings - getting average rating of applicant
                $data['employer'] = $this->hr_documents_management_model->get_company_detail($userId);

                $data['downloadDocumentData'] = $this->hr_documents_management_model->get_last_download_document_name($company_sid, $userId, $userType, 'single_download');
                break;
            case 'applicant':
                $user_info = $this->hr_documents_management_model->get_applicant_information($company_sid, $userId);

                if (empty($user_info)) {
                    $this->session->set_flashdata('message', '<strong>Error:</strong> Applicant Not Found!');
                    redirect('application_tracking_system/active/all/all/all/all', 'refresh');
                }

                $data = applicant_right_nav($userId, 0);
                $left_navigation = 'manage_employer/application_tracking_system/profile_right_menu_applicant';
                $applicant_info = $this->hr_documents_management_model->get_applicants_details($userId);

                $data_employer = array(
                    'sid' => $applicant_info['sid'],
                    'first_name' => $applicant_info['first_name'],
                    'last_name' => $applicant_info['last_name'],
                    'email' => $applicant_info['email'],
                    'Location_Address' => $applicant_info['address'],
                    'Location_City' => $applicant_info['city'],
                    'Location_Country' => $applicant_info['country'],
                    'Location_State' => $applicant_info['state'],
                    'Location_ZipCode' => $applicant_info['zipcode'],
                    'PhoneNumber' => $applicant_info['phone_number'],
                    'profile_picture' => $applicant_info['pictures'],
                    'user_type' => ucwords($userType)
                );

                $data['applicant_average_rating'] = $this->hr_documents_management_model->getApplicantAverageRating($userId, 'applicant'); //getting average rating of applicant
                $data['employer'] = $data_employer;
                $data['company_sid'] = $company_sid;
                $data['employer_sid'] = $applicant_info['sid'];

                $data['downloadDocumentData'] = $this->hr_documents_management_model->get_last_download_document_name($company_sid, $userId, $userType, 'single_download');
                break;
        }
        // get the form
        // get i9 form details
        $data["i9Form"] = $this->i9_model->getI9Form(
            $userId,
            $userType,
            'section1'
        );
        $data["userId"] = $userId;
        $data["userType"] = $userType;
        //
        $data["left_navigation"] = $left_navigation;
        // get states
        $data['states'] = $this->db
            ->select('state_code, state_name')
            ->where('active', 1)
            ->where('country_sid', USA_CODE)
            ->get('states')
            ->result_array();
        // load page scripts
        $data['PageScripts'] = [
            'js/app_helper',
            'v1/forms/i9/modify_i9_employer'
        ];
        $this->load
            ->view('main/header', $data)
            ->view('v1/forms/i9/modify')
            // ->view('v1/forms/i9/my')
            ->view('main/footer');
    }


    public function processEmployerModifyI9(
        string $userType,
        int $userId
    ) {

        // check session
        if (!$this->session->userdata('logged_in')) {
            return SendResponse(
                401,
                [
                    'errors' => [
                        'You are not authorize to make this request. Please, re-login and try again.'
                    ]
                ]
            );
        }
        // get post
        $post = $this->input->post(null, true);
        //
        $i9Form = $this
            ->i9_model
            ->getI9Form(
                $userId,
                $userType,
                'section1'
            );
        //
        if (!$i9Form) {
            return SendResponse(
                400,
                [
                    'errors' => [
                        'I9 form is either disabled or not valid.'
                    ]
                ]
            );
        }
        // lets make update array
        $updateArray = $post;
        // lets convert dates
        $updateArray['section1_date_of_birth'] = $updateArray['section1_date_of_birth']
            ? formatDateToDB(
                $updateArray['section1_date_of_birth'],
                SITE_DATE,
                DB_DATE
            ) : null;
        //
        $updateArray['section1_today_date'] = $updateArray['section1_today_date']
            ? formatDateToDB(
                $updateArray['section1_today_date'],
                SITE_DATE,
                DB_DATE
            ) : null;
        //
        $updateArray['alien_authorized_expiration_date'] = $updateArray['alien_authorized_expiration_date']
            ? formatDateToDB(
                $updateArray['alien_authorized_expiration_date'],
                SITE_DATE,
                DB_DATE
            ) : null;
        //
        $updateArray['section1_alien_registration_number'] = serialize(
            [
                'section1_alien_registration_number_two' => $updateArray['section1_alien_registration_number_two'],
                'section1_alien_registration_number_one' => $updateArray['section1_uscis_registration_number_one'],
                'alien_authorized_expiration_date' => $updateArray['alien_authorized_expiration_date'],
                'foreign_passport_number' => $updateArray['foreign_passport_number'],
                'country_of_issuance' => $updateArray['country_of_issuance'],
                'form_admission_number' => $updateArray['form_admission_number'],
            ]
        );
        //remove items
        unset(
            $updateArray['section_1_other_last_names_used'],
            $updateArray['section1_alien_registration_number_two'],
            $updateArray['alien_authorized_expiration_date'],
            $updateArray['section1_uscis_registration_number_one'],
            $updateArray['foreign_passport_number'],
            $updateArray['country_of_issuance'],
            $updateArray['section1_signature'],
            $updateArray['form_admission_number'],
            $updateArray['section1_preparer'],
        );
        //
        $updateArray['section1_other_last_names'] = $post['section_1_other_last_names_used'];
        //
        $updateArray['version'] = 2023;
        //
        $this->db
            ->where('sid', $i9Form['sid'])
            ->update(
                'applicant_i9form',
                $updateArray
            );
        //
        return SendResponse(
            200,
            [
                'success' => true,
                'message' => 'You have successfully updated the section 1 of I9 form .',
            ]
        );
    }


    //
    public function check_age($section1_date_of_birth)
    {
        if (underAge(formatDateToDB($section1_date_of_birth, 'm/d/Y', DB_DATE))) {

            $this->form_validation->set_message('check_age', UNDER_AGE_MESSAGE);

            return false;
        } else {
            return true;
        }
    }
}
