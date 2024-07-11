<?php defined("BASEPATH") || exit("No direct access allowed.");

/**
 * Gusto library
 * 
 * @author AutomotoHR Dev Team
 * @link   www.automotohr.com
 * @version 1.0
 */
class Lb_gusto
{

    /**
     * CI instance
     * @var reference
     */
    private $ci;

    /**
     * holds the application mode
     * production|demo
     * @var string
     */
    private $mode;

    /**
     * holds the application
     * credentials
     * @var array
     */
    private $credentials;

    /**
     * holds the API version
     * @var string
     */
    private $version;

    /**
     * main
     */
    public function __construct(array $params)
    {
        // holds the CI instance
        $this->ci = &get_instance();
        // check when company id is set
        if (isset($params["companyId"])) {
            // set the mode
            $this->setMode($params["companyId"]);
        } elseif (isset($params["mode"])) {
            $this->setManualMode($params["mode"]);
        } else {
            exit("Please provide a company id.");
        }
        // ste the version
        $this->version = "2024-03-01";
    }

    /**
     * get company admins from Gusto
     *
     * @param array $company
     * @return array
     */
    public function gustoCall(
        string $event,
        array $company,
        array $request = [],
        string $requestType = "GET"
    ): array {
        // set call headers
        $callHeaders = [
            'Authorization: Bearer ' . ($company['access_token']) . '',
            'Content-Type: application/json',
            'Accept: application/json',
            "X-Gusto-API-Version: {$this->version}"
        ];
        //
        $curlOptions = [
            CURLOPT_CUSTOMREQUEST => $requestType,
            CURLOPT_HTTPHEADER => $callHeaders
        ];
        //
        if ($requestType === "POST" || $requestType === "PUT") {
            $curlOptions[CURLOPT_POSTFIELDS] = json_encode($request);
        }
        // make call to Gusto
        $response =  $this->makeCall(
            $this->getUrl(
                $event,
                $company['gusto_uuid'],
                $company['other_uuid'] ?? '',
                $company['other_uuid_2'] ?? '',
            ),
            $curlOptions
        );
        // auth failed needs to generate new tokens
        if (isset($response['errors']['auth'])) {
            // generate new access token
            $tokenResponse = $this->refreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            // generated
            if (isset($tokenResponse['access_token'])) {
                // update in database
                $this->updateToken(
                    $tokenResponse,
                    [
                        'gusto_uuid' => $company['gusto_uuid']
                    ]
                );
                // set to local variable
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                // recall the event
                return $this->gustoCall(
                    $event,
                    $company,
                    $request,
                    $requestType
                );
            } else {
                return [
                    'errors' => [
                        'invalid_grant' => [
                            $tokenResponse['error_description']
                        ]
                    ]
                ];
            }
        } else {
            // pass actual response
            return $response;
        }
    }

    /**
     * Parse Gusto errors
     *
     * Convert Gusto errors to AutomotoHR errors
     * for handling errors
     *
     * @param mixed $response
     * @return array
     */
    public function hasGustoErrors($response)
    {
        // set errors array
        $errors = [
            'errors' => []
        ];
        // if it's a single error
        //
        if (isset($response['message'])) {
            $errors['errors'][] = $response['message'];
        } elseif (isset($response['error'])) {
            $errors['errors'][] = $response['error'];
        } elseif (isset($response['errors']['invalid_grant'])) {
            $errors['errors'] = array_merge($errors['errors'], $response['errors']['invalid_grant']);
        } elseif (isset($response['errors'])) {
            foreach ($response['errors'] as $err) {
                //

                if (isset($err['error_key']) && $err['error_key'] == "states") {
                    return $this->getStateErrorList($err['errors']);
                } elseif (isset($err['errors'])) {
                    foreach ($err['errors'] as $err0) {
                        $errors['errors'][] = $err0['message'];
                    }
                } elseif (isset($err[0])) {
                    foreach ($err as $err0) {
                        $errors['errors'][] = $err0['message'];
                    }
                } else {
                    //
                    $errors['errors'][] = $err['message'];
                }
            }
        }

        //
        return $errors['errors'] ? $errors : [];
    }

    /**
     * create partner company on Gusto
     *
     * @param array $request
     * @return array
     */
    public function createPartnerCompanyOnGusto(array $request): array
    {
        return $this->makeCall(
            $this->getUrl('partner_managed_companies'),
            [
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => [
                    'Authorization: Token ' . ($this->credentials["api_token"]) . '',
                    'Content-Type: application/json',
                    "X-Gusto-API-Version: {$this->version}"
                ]
            ]
        );
    }

    /**
     * get requests
     *
     * @return array
     */
    public function getWebHooks(): array
    {
        return $this->makeCall(
            $this->getUrl('webhook_subscriptions'),
            [
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                    'Authorization: Token ' . ($this->credentials["api_token"]) . '',
                    'Content-Type: application/json',
                    "X-Gusto-API-Version: {$this->version}"
                ]
            ]
        );
    }

    /**
     * converts bank array to Gusto
     *
     * @param array $bankAccounts
     * @return array
     */
    public function setAndGetPaymentMethodSplits(array $bankAccounts): array
    {
        // set return
        $return = [
            'split_by' => 'Percentage',
            'splits' => []
        ];
        // when there is only 1 split
        if (count($bankAccounts) === 1) {
            //
            $return['splits'] = [
                "uuid" => $bankAccounts[0]["gusto_uuid"],
                "name" => $bankAccounts[0]["account_title"],
                "priority" => 1,
                'split_amount' => 100,
            ];
            //
            return $return;
        }
        // for multiple
        // set the split by
        if ($bankAccounts[0]['deposit_type'] == 'amount') {
            $return['split_by'] = 'Amount';
        }
        // for first bank account
        $return['splits'][0] = [
            "uuid" => $bankAccounts[0]["gusto_uuid"],
            "name" => $bankAccounts[0]["account_title"],
            "priority" => 1,
            'split_amount' => $bankAccounts[0]["account_percentage"],
        ];
        // for second bank account
        $return['splits'][1] = [
            "uuid" => $bankAccounts[1]["gusto_uuid"],
            "name" => $bankAccounts[1]["account_title"],
            "priority" => 2,
            'split_amount' => null,
        ];

        if ($return["split_by"] === "Amount") {
            $return['splits'][1]['split_amount'] = null;
        } else {
            $return['splits'][1]["split_amount"] =
                100 - $return['splits'][0]["split_amount"];
        }
        //
        return $return;
    }

    // ----------------------------------------------------------
    // Template
    // ----------------------------------------------------------
    /**
     * earning types
     *
     * @return array
     */
    public function getEarningTypes(): array
    {
        return  [
            "cash_spiffs" => [
                "name" => "Cash Spiffs",
                "fields_json" => '{"name":"Cash Spiffs","rate_type":"Flat Rate","rate":"","wage_type":"Supplemental Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "accrued_commission" => [
                "name" => "Accrued Commission",
                "fields_json" => '{"name":"Accrued Commission","rate_type":"Flat Rate","rate":"0.0","wage_type":"Supplemental Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"Yes","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "accrued_payroll" => [
                "name" => 'Accrued Payroll',
                "fields_json" => '{"name":"Accrued Payroll","rate_type":"Flat Rate","rate":"0.0","wage_type":"Supplemental Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "apprentice_pay" => [
                "name" => 'Apprentice Pay',
                "fields_json" => '{"name":"Apprentice Pay","rate_type":"Hourly Rate","rate":"0.0","wage_type":"Supplemental Wages","count_toward_minimum_wage":"0","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "building_maintenance_pay" => [
                "name" => 'Building Maintenance Pay',
                "fields_json" => '{"name":"Building Maintenance Pay","rate_type":"Hourly Rate","rate":"0.0","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"Yes","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "commercial_truck_reg_hours" => [
                "name" => 'Commercial Truck Reg Hours',
                "fields_json" => '{"name":"Commercial Truck Reg Hours","rate_type":"Hourly Rate","rate":"0.0","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "cost_of_medical_coverage" => [
                "name" => 'Cost of Medical Coverage',
                "fields_json" => '{"name":"Cost of Medical Coverage","rate_type":"Flat Rate","rate":"0.0","wage_type":"Imputed Income","count_toward_minimum_wage":"No","non_monetary_income":"Yes","process_as_ot":"0","report_as_a_fringe_benefit":"Yes","from_w-2_box_14":"Yes","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"No","federal_income_tax_additional":"No","federal_income_tax_fixed_rate":"No","social_security_company":"No","social_security_employee":"No","medicare_company":"No","medicare_employee":"No","federal_unemployment_insurance":"No","mn_income_tax":"No","mn_income_tax_additional":"No","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"No","mn_workforce_dev_assessment":"No"}'
            ],
            "demo_earnings" => [
                "name" => 'Demo Earnings',
                "fields_json" => '{"name":"Demo Earnings","rate_type":"Flat Rate","rate":"0.0","wage_type":"Imputed Income","count_toward_minimum_wage":"No","non_monetary_income":"Yes","process_as_ot":"0","report_as_a_fringe_benefit":"Yes","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"No","federal_income_tax_additional":"No","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"No","mn_income_tax":"No","mn_income_tax_additional":"No","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"No","mn_workforce_dev_assessment":"No"}'
            ],
            "employee_referral_bonus" => [
                "name" => 'Employee Referral Bonus',
                "fields_json" => '{"name":"Employee Referral Bonus","rate_type":"Flat Rate","rate":"0.0","wage_type":"Supplemental Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"No","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"Yes","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"No","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"Yes","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "esst" => [
                "name" => 'ESST',
                "fields_json" => '{"name":"ESST","rate_type":"Hourly Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"0","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"0","federal_income_tax":"0","federal_income_tax_additional":"0","federal_income_tax_fixed_rate":"0","social_security_company":"0","social_security_employee":"0","medicare_company":"0","medicare_employee":"0","federal_unemployment_insurance":"0","mn_income_tax":"0","mn_income_tax_additional":"0","mn_income_tax_fixed_rate":"0","mn_unemployment_insurance":"0","mn_workforce_dev_assessment":"0"}'
            ],
            "f&i_manager_commission" => [
                "name" => 'F&I Manager Commission',
                "fields_json" => '{"name":"F&I Manager Commission","rate_type":"Flat Rate","rate":"","wage_type":"Supplemental Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "general_manager_commission" => [
                "name" => 'General Manager Commission',
                "fields_json" => '{"name":"General Manager Commission","rate_type":"Flat Rate","rate":"","wage_type":"Supplemental Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "general_manager_salary" => [
                "name" => 'General Manager Salary',
                "fields_json" => '{"name":"General Manager Salary","rate_type":"Flat Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "holiday_pay" => [
                "name" => 'Holiday Pay',
                "fields_json" => '{"name":"Holiday Pay","rate_type":"Hourly Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "manager_commission" => [
                "name" => 'Manager Commission',
                "fields_json" => '{"name":"Manager Commission","rate_type":"Flat Rate","rate":"","wage_type":"Supplemental Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "manager_holiday_hours" => [
                "name" => 'Manager Holiday Hours',
                "fields_json" => '{"name":"Manager Holiday Hours","rate_type":"Hourly Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "manager_salary" => [
                "name" => 'Manager Salary',
                "fields_json" => '{"name":"Manager Salary","rate_type":"Flat Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "manager_vacation_hours" => [
                "name" => 'Manager Vacation Hours',
                "fields_json" => '{"name":"Manager Vacation Hours","rate_type":"Hourly Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"Yes","leave_plan":"Yes","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"No","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "miscellaneous_earnings" => [
                "name" => 'Miscellaneous Earnings',
                "fields_json" => '{"name":"Miscellaneous Earnings","rate_type":"Flat Rate","rate":"","wage_type":"Supplemental Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "officer_health_premiums" => [
                "name" => 'Officer Health Premiums',
                "fields_json" => '{"name":"Officer Health Premiums","rate_type":"Flat Rate","rate":"","wage_type":"Imputed Income","count_toward_minimum_wage":"No","non_monetary_income":"Yes","process_as_ot":"0","report_as_a_fringe_benefit":"Yes","from_w-2_box_14":"Yes","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"No","federal_income_tax_fixed_rate":"No","social_security_company":"No","social_security_employee":"No","medicare_company":"No","medicare_employee":"No","federal_unemployment_insurance":"No","mn_income_tax":"Yes","mn_income_tax_additional":"No","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"No","mn_workforce_dev_assessment":"No"}'
            ],
            "other_pay" => [
                "name" => 'Other Pay',
                "fields_json" => '{"name":"Other Pay","rate_type":"Flat Rate","rate":"","wage_type":"Supplemental Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "overtime_hourly_wages" => [
                "name" => 'Overtime Hourly Wages',
                "fields_json" => '{"name":"Overtime Hourly Wages","rate_type":"Hourly Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"Yes","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "overtime_premium" => [
                "name" => 'Overtime Premium',
                "fields_json" => '{"name":"Overtime Premium","rate_type":"Hourly Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "owner_salary" => [
                "name" => 'Owner Salary',
                "fields_json" => '{"name":"Owner Salary","rate_type":"Flat Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "paid_time_off" => [
                "name" => 'Paid Time Off',
                "fields_json" => '{"name":"Paid Time Off","rate_type":"Hourly Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"No","process_as_ot":"No","report_as_a_fringe_benefit":"No","from_w-2_box_14":"0","update_balances_":"Yes","leave_plan":"Yes","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "piece_work_1" => [
                "name" => 'Piece Work 1',
                "fields_json" => '{"name":"Piece Work 1","rate_type":"Hourly Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"No","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "piece_work_2" => [
                "name" => 'Piece Work 2',
                "fields_json" => '{"name":"Piece Work 2","rate_type":"Hourly Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "pto_payout" => [
                "name" => 'PTO Payout',
                "fields_json" => '{"name":"PTO Payout","rate_type":"Hourly Rate","rate":"","wage_type":"Supplemental Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"Yes","federal_loan_assessment":"Yes","federal_income_tax":"No","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"Yes","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"No","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"Yes","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "regular_hourly_wages" => [
                "name" => 'Regular Hourly Wages',
                "fields_json" => '{"name":"Regular Hourly Wages","rate_type":"Hourly Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "salary" => [
                "name" => 'Salary',
                "fields_json" => '{"name":"Salary","rate_type":"Flat Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"0","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "shuttle_driver_reg_hours" => [
                "name" => 'Shuttle Driver Reg Hours',
                "fields_json" => '{"name":"Shuttle Driver Reg Hours","rate_type":"Hourly Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "sign_on_bonus" => [
                "name" => 'Sign On Bonus',
                "fields_json" => '{"name":"Sign On Bonus","rate_type":"Flat Rate","rate":"","wage_type":"Supplemental Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"No","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"Yes","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"No","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"Yes","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "tech_unapplied_time" => [
                "name" => 'Tech Unapplied Time',
                "fields_json" => '{"name":"Tech Unapplied Time","rate_type":"Flat Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "technician_upsells" => [
                "name" => 'Technician Upsells',
                "fields_json" => '{"name":"Technician Upsells","rate_type":"Flat Rate","rate":"","wage_type":"Supplemental Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "training_hourly_pay" => [
                "name" => 'Training Hourly Pay',
                "fields_json" => '{"name":"Training Hourly Pay","rate_type":"Hourly Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "training_fixed_amount" => [
                "name" => 'Training Fixed Amount',
                "fields_json" => '{"name":"Training Fixed Amount","rate_type":"Flat Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "umt_manager_salary" => [
                "name" => 'UMT Manager Salary',
                "fields_json" => '{"name":"UMT Manager Salary","rate_type":"Flat Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "vacation_pay" => [
                "name" => 'Vacation pay',
                "fields_json" => '{"name":"Vacation pay","rate_type":"Hourly Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "paid_time_off" => [
                "name" => 'Paid Time Off',
                "fields_json" => '{"name":"Paid Time Off","rate_type":"Hourly Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"No","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"No","federal_income_tax":"No","federal_income_tax_additional":"No","federal_income_tax_fixed_rate":"No","social_security_company":"No","social_security_employee":"No","medicare_company":"No","medicare_employee":"No","federal_unemployment_insurance":"No","mn_income_tax":"No","mn_income_tax_additional":"No","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"No","mn_workforce_dev_assessment":"No"}'
            ]

        ];
    }

    /**
     * Company benefits
     *
     * @return array
     */
    public function getBenefits(): array
    {
        return [
            [
                "benefit_type" => "2",
                "description" => "Dental",
                "deletable" => "1",
                "supports_percentage_amounts" => "0",
                "responsible_for_employer_taxes" => "0",
                "responsible_for_employee_w2" => "0"
            ],
            [
                "benefit_type" => "6",
                "description" => "Health Savings Contribution",
                "deletable" => "1",
                "supports_percentage_amounts" => "1",
                "responsible_for_employer_taxes" => "0",
                "responsible_for_employee_w2" => "0"
            ],
            [
                "benefit_type" => "1",
                "description" => "Medical Premium",
                "deletable" => "1",
                "supports_percentage_amounts" => "0",
                "responsible_for_employer_taxes" => "0",
                "responsible_for_employee_w2" => "0"
            ],
            [
                "benefit_type" => "3",
                "description" => "Vision Premium",
                "deletable" => "1",
                "supports_percentage_amounts" => "0",
                "responsible_for_employer_taxes" => "0",
                "responsible_for_employee_w2" => "0"
            ],
            [
                "benefit_type" => "993",
                "description" => "Voluntary Life Insurance Premi",
                "deletable" => "1",
                "supports_percentage_amounts" => "0",
                "responsible_for_employer_taxes" => "0",
                "responsible_for_employee_w2" => "0"
            ],
            [
                "benefit_type" => "5",
                "description" => "401(k) - Retirement Account",
                "deletable" => "1",
                "supports_percentage_amounts" => "1",
                "responsible_for_employer_taxes" => "0",
                "responsible_for_employee_w2" => "0"
            ]
        ];
    }

    /**
     * get the empty response
     */
    public function getEmptyResponse(): array
    {
        return [
            "errors" => [
                "Empty response from Gusto."
            ]
        ];
    }

    /**
     * get the success response
     *
     * @param array $gustoResponse
     * @return array
     */
    public function getSuccessResponse(
        array $gustoResponse
    ): array {
        $response = [
            "success" => true,
            "message" => "You have successfully performed the event.",
            "response" => []
        ];
        // when multiple output
        if ($gustoResponse[0]) {
            foreach ($gustoResponse as $v0) {
                $response["response"][] = [
                    "gusto_uuid" => $v0["uuid"] ?? null,
                    "gusto_version" => $v0["version"] ?? null,
                ];
            }
        } else { // for single output
            $response["response"][] = [
                "gusto_uuid" => $gustoResponse["uuid"] ?? null,
                "gusto_version" => $gustoResponse["version"] ?? null,
            ];
        }
        // return the response
        return $response;
    }

    /**
     * make call to Gusto
     *
     * @param string $url
     * @param array  $options
     * @return array
     */
    private function makeCall(string $url, array $options = []): array
    {
        $curl = curl_init();
        //
        $options =
            [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1
            ] + $options;
        //
        curl_setopt_array(
            $curl,
            $options
        );
        //
        $response = curl_exec($curl);
        //
        $info = curl_getinfo($curl);
        //
        curl_close($curl);
        // network issue
        if ($info['http_code'] == '0') {
            $response = json_encode([
                'errors' => [
                    [
                        'message' => 'Network issue: failed to reach payroll provider.'
                    ]
                ]
            ]);
        }
        // Check for auth error
        if ($info['http_code'] == 401) {
            $response = json_encode([
                'errors' => [
                    'auth' => [
                        $info['http_code']
                    ]
                ]
            ]);
        }
        // acknowledged changes
        if ($info['http_code'] == '204') {
            $response = json_encode([
                'success' => true
            ]);
        }
        // acknowledged
        if ($info['http_code'] == '202') {
            $response = json_encode([
                'success' => true
            ]);
        }
        // Save the request and response to database
        $this->saveCall([
            'request_method' => $options[CURLOPT_CUSTOMREQUEST],
            'request_url' => $url,
            'request_body' => json_encode([
                'headers' => $options[CURLOPT_HTTPHEADER],
                'body' => $options[CURLOPT_POSTFIELDS]
            ]),
            'response_body' => $response,
            'response_code' => $info['http_code'],
            'response_headers' => json_encode($info)
        ]);

        //
        if ($info['content_type'] === 'application/pdf') {
            //
            $filename = 'employees/pay_stub/' . time() . '_' . (random_key(10)) . '_employee_pay_stub' . '.pdf';
            //
            $this->ci->load->library('aws_lib');
            //
            $options = [
                'Bucket' => AWS_S3_BUCKET_NAME,
                'Key' => $filename,
                'Body' => $response,
                'ACL' => 'public-read',
                'ContentType' => $info['content_type']
            ];
            //
            $this->ci->aws_lib->put_object($options);
            //
            return [
                's3_file_name' => $filename,
                's3_file_url' => AWS_S3_BUCKET_URL . $filename
            ];
        }
        // Convert to Associated Array and keep the long big ints
        $response = json_decode($response, true, 512, JSON_BIGINT_AS_STRING);
        //
        return $response;
    }

    /**
     * Updates new generated token into
     * the DB
     *
     * @param array $token
     * @param array $where
     */
    private function updateToken(array $token, array $where): void
    {
        // update new token to database
        $this
            ->ci
            ->db
            ->where($where)
            ->update('gusto_companies', [
                'access_token' => $token['access_token'],
                'refresh_token' => $token['refresh_token'],
            ]);
    }

    /**
     * refresh the Auth
     *
     * @method setCredentials
     * @param array $request
     */
    private function refreshToken(array $request)
    {
        //
        $body = [];
        $body['client_id'] = $this->credentials["client_id"];
        $body['client_secret'] = $this->credentials["client_secret"];
        $body['redirect_uri'] = $this->credentials["url"];
        $body['refresh_token'] = $request['refresh_token'];
        $body['grant_type'] = 'refresh_token';
        //
        return $this->makeCall(
            $this->getUrl('refreshToken', ''),
            [
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($body),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Token ' . ($request['access_token']) . '',
                    'Content-Type: application/json'
                )
            ]
        );
    }


    /**
     * get the required URL
     *
     * @param string $index
     * @param string $key
     * @param string $key1 Optional
     * @param string $key2 Optional
     * @return string
     */
    private function getUrl(
        string $index,
        string $key = '',
        string $key1 = '',
        string $key2 = ''
    ) {
        // set default URL
        $urls = [];
        $urls['me'] = 'v1/me';
        $urls['refreshToken'] = 'oauth/token?' . ($key);

        // create partner company routes
        $urls["partner_managed_companies"] =
            "v1/partner_managed_companies";
        // webhook_subscriptions
        $urls["webhook_subscriptions"] =
            "v1/webhook_subscriptions";
        // agreements
        $urls["agreement"] =
            "v1/partner_managed_companies/{$key}/accept_terms_of_service";

        // Company
        // admins
        $urls["admins"] =
            "v1/companies/{$key}/admins";
        // locations
        $urls["company_locations"] =
            "v1/companies/{$key}/locations";
        // minimum_wages
        $urls["minimum_wages"] =
            "v1/locations/{$key1}/minimum_wages";
        // payment_configs
        $urls["payment_configs"] =
            "v1/companies/{$key}/payment_configs";
        // federal_tax_details
        $urls["federal_tax_details"] =
            "v1/companies/{$key}/federal_tax_details";
        // industry_selection
        $urls["industry_selection"] =
            "v1/companies/{$key}/industry_selection";
        // forms
        $urls["forms"] =
            "v1/companies/{$key}/forms";
        // forms_pdf
        $urls["forms_pdf"] =
            "v1/forms/{$key1}/pdf";
        // company_benefits
        $urls["company_benefits"] =
            "v1/companies/{$key}/company_benefits";
        // bank_accounts
        $urls["bank_accounts"] =
            "v1/companies/{$key}/bank_accounts";
        // send_test_deposits
        $urls["send_test_deposits"] =
            "v1/companies/{$key}/bank_accounts/{$key1}/send_test_deposits";
        // verify_bank_Account
        $urls["verify_bank_Account"] =
            "v1/companies/{$key}/bank_accounts/{$key1}/verify";
        // departments
        $urls["departments"] =
            "v1/companies/{$key}/departments";
        // custom_fields
        $urls["custom_fields"] =
            "v1/companies/{$key}/custom_fields";
        // employees
        $urls["employees"] =
            "v1/companies/{$key}/employees";
        // industry
        $urls["industry"] =
            "v1/companies/{$key}/industry_selection";
        // flows
        $urls["flows"] =
            "v1/companies/{$key}/flows";
        // signatories
        $urls["signatories"] =
            "v1/companies/{$key}/signatories";
        // employee_form_pdf
        $urls["employee_form_pdf"] =
            "v1/employees/{$key1}/forms/{$key2}/pdf";

        // Employees
        // employees
        $urls["get_employee_profile"] =
            "v1/employees/{$key1}";
        // work_addresses
        $urls["work_addresses"] =
            "v1/employees/{$key1}/work_addresses";
        // home_addresses
        $urls["home_addresses"] =
            "v1/employees/{$key1}/home_addresses";
        // jobs
        $urls["jobs"] =
            "v1/employees/{$key1}/jobs";
        // compensations
        $urls["compensations"] =
            "v1/jobs/{$key1}/compensations";
        // terminations
        $urls["terminations"] =
            "v1/employees/{$key1}/terminations";
        // rehire
        $urls["rehire"] =
            "v1/employees/{$key1}/rehire";
        // federal_taxes
        $urls["federal_taxes"] =
            "v1/employees/{$key1}/federal_taxes";
        // state_taxes
        $urls["state_taxes"] =
            "v1/employees/{$key1}/state_taxes";
        // payment_method
        $urls["payment_method"] =
            "v1/employees/{$key1}/payment_method";
        // bank_accounts
        $urls["employee_bank_accounts"] =
            "v1/employees/{$key1}/bank_accounts";
        // forms
        $urls["employee_forms"] =
            "v1/employees/{$key1}/forms";
        // benefits
        $urls["employee_benefits"] =
            "v1/employees/{$key1}/employee_benefits";
        // garnishments
        $urls["garnishments"] =
            "v1/employees/{$key1}/garnishments";

        // update_job
        $urls["update_job"] =
            "v1/jobs/{$key1}";
        // update compensations
        $urls["update_compensations"] =
            "v1/compensations/{$key1}";
        // update_employee_home_address
        $urls["update_employee_home_address"] =
            "v1/home_addresses/{$key1}";
        // update_employee_profile
        $urls["update_employee_profile"] =
            "v1/employees/{$key1}";
        // sign_employee_form
        $urls["sign_employee_form"] =
            "v1/employees/{$key1}/forms/{$key2}/sign";
        // update_state_taxes
        $urls["update_state_taxes"] =
            "v1/employees/{$key1}/state_taxes";

        // Payrolls
        // payroll_blockers
        $urls["payroll_blockers"] =
            "v1/companies/{$key}/payrolls/blockers";



        // Payrolls
        $urls["earning_types"] =
            "v1/companies/{$key}/earning_types";



        return $this->credentials["url"] . $urls[$index];
    }

    /**
     * set the credentials
     */
    private function setCredentials()
    {
        // load the credentials
        $credentials = getCreds("AHR")->GUSTO;
        // for production mode
        if ($this->mode === "production") {
            $this->credentials = [
                "client_id" => $credentials->PRODUCTION->CLIENT_ID,
                "client_secret" => $credentials->PRODUCTION->CLIENT_SECRET,
                "callback_url" => $credentials->PRODUCTION->CALLBACK_URL,
                "url" => $credentials->PRODUCTION->URL,
                "api_token" => $credentials->PRODUCTION->API_TOKEN,
            ];
        } else {
            $this->credentials = [
                "client_id" => $credentials->DEMO->CLIENT_ID,
                "client_secret" => $credentials->DEMO->CLIENT_SECRET,
                "callback_url" => $credentials->DEMO->CALLBACK_URL,
                "url" => $credentials->DEMO->URL,
                "api_token" => $credentials->DEMO->API_TOKEN,
            ];
        }
    }

    /**
     * Parse Gusto errors
     *
     * Convert Gusto errors to AutomotoHR errors
     * for handling errors
     *
     * @param mixed $response
     * @return array
     */
    private function getStateErrorList($response)
    {
        // set errors array
        $errors = [
            'errors' => []
        ];
        //
        foreach ($response as $err) {
            if ($err['error_key'] == 'questions') {
                //
                foreach ($err['errors'] as $err0) {
                    foreach ($err0['errors'] as $err1) {
                        $errors['errors'][] = $err1['message'];
                    }
                }
            }
        }
        //
        return $errors;
    }

    /**
     * saves request and response
     *
     * @param array $ins
     */
    private function saveCall(array $ins)
    {
        //
        $ins['created_at'] = getSystemDate();
        //
        $this
            ->ci
            ->db
            ->insert(
                'payroll_calls',
                $ins
            );
    }

    /**
     * check the company mode
     *
     * @param int $companyId
     */
    private function setMode(int $companyId)
    {
        // set default mode
        $this->mode = "demo";
        // check if account is on production
        if (
            $this
            ->ci
            ->db
            ->where([
                "company_sid" => $companyId,
                "stage" => "production",
            ])
            ->count_all_results("gusto_companies_mode")
        ) {
            $this->mode = "production";
        }
        //
        $this->setCredentials();
    }

    /**
     * check the company mode
     *
     * @param string $mode
     */
    private function setManualMode(string $mode)
    {
        // set default mode
        $this->mode = $mode === "production" ? $mode : "demo";
        //
        $this->setCredentials();
    }
}
