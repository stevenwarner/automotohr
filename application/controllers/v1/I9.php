<?php defined('BASEPATH') or exit('No direct script access allowed');

class I9 extends Public_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('form_wi9_model', 'i9_model');
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
    public function printOrDownload(int $formId, string $action)
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
        $columns = [];
        $columns[] = 'applicant_i9form.user_consent';
        $columns[] = 'applicant_i9form.section1_last_name';
        $columns[] = 'applicant_i9form.section1_first_name';
        $columns[] = 'applicant_i9form.section1_middle_initial';
        $columns[] = 'applicant_i9form.section1_other_last_names';
        $columns[] = 'applicant_i9form.section1_address';
        $columns[] = 'applicant_i9form.section1_apt_number';
        $columns[] = 'applicant_i9form.section1_city_town';
        $columns[] = 'applicant_i9form.section1_state';
        $columns[] = 'applicant_i9form.section1_zip_code';
        $columns[] = 'applicant_i9form.section1_date_of_birth';
        $columns[] = 'applicant_i9form.section1_social_security_number';
        $columns[] = 'applicant_i9form.section1_emp_email_address';
        $columns[] = 'applicant_i9form.section1_emp_telephone_number';
        $columns[] = 'applicant_i9form.section1_emp_signature';
        $columns[] = 'applicant_i9form.section1_today_date';
        $columns[] = 'applicant_i9form.section1_alien_registration_number';
        $columns[] = 'applicant_i9form.section1_penalty_of_perjury';
        $columns[] = 'applicant_i9form.section1_preparer_or_translator';
        $columns[] = 'applicant_i9form.section1_preparer_json';

        //
        $data['form'] = $this->i9_model->getI9FormById(
            $formId,
            $columns
        );
        // _e($data['form'], true);
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
        $this->load->view('v1/forms/i9/print', $data);
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
                'rules' => $rules.'|max_length[3]|min_length[2]'
            ],
            [
                'field' => 'section1_zip_code',
                'label' => 'Zip code',
                'rules' => $rules
            ],
            [
                'field' => 'section1_date_of_birth',
                'label' => 'Date of birth',
                'rules' => $rules
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
        //
        $this->db
            ->where('sid', $post['form_code'])
            ->update(
                'applicant_i9form',
                $updateArray
            );
        //
        return SendResponse(
            200,
            [
                'success' => true,
                'message' => 'You have successfully completed the I9 form.'
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
}
