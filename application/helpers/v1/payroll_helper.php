<?php defined('BASEPATH') || exit('No direct script access allowed');

if (!function_exists('makeCall')) {
    /**
     * make call to Gusto
     *
     * @param string $url
     * @param array  $options
     * @return array
     */
    function makeCall(string $url, array $options = []): array
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
        // Check for auth error
        if ($info['http_code'] == '204') {
            $response = json_encode([
                'success' => true
            ]);
        }
        // Check for auth error
        if ($info['http_code'] == '202') {
            $response = json_encode([
                'success' => true
            ]);
        }
        // Save the request and response to database
        saveCall([
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
            $_this = &get_instance();
            //
            $_this->load->library('aws_lib');
            //
            $options = [
                'Bucket' => AWS_S3_BUCKET_NAME,
                'Key' => $filename,
                'Body' => $response,
                'ACL' => 'public-read',
                'ContentType' => $info['content_type']
            ];
            //
            $_this->aws_lib->put_object($options);
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
}

if (!function_exists('saveCall')) {
    /**
     * saves request and response
     *
     * @param array $ins
     */
    function saveCall($ins)
    {
        //
        $CI = &get_instance();
        //
        $ins['created_at'] = getSystemDate();
        //
        $CI->db->insert('payroll_calls', $ins);
    }
}

if (!function_exists('updateToken')) {
    /**
     * Updates new generated token into
     * the DB
     *
     * @param array $token
     * @param array $where
     */
    function updateToken(array $token, array $where): void
    {
        // update new token to database
        get_instance()
            ->db
            ->where($where)
            ->update('gusto_companies', [
                'access_token' => $token['access_token'],
                'refresh_token' => $token['refresh_token'],
            ]);
    }
}

if (!function_exists('payrollAuth')) {
    /**
     * get the authorization key
     *
     * @param array $company
     */
    function payrollAuth(array $company)
    {
        //
        return makeCall(
            getUrl('me'),
            [
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . ''
                )
            ]
        );
    }
}

if (!function_exists('refreshToken')) {
    /**
     * refresh the Auth
     *
     * @param array $request
     */
    function refreshToken(array $request)
    {
        //
        $gustoDetails = getCreds("AHR")->GUSTO->DEMO;
        //
        $body = [];
        $body['client_id'] = $gustoDetails->CLIENT_ID;
        $body['client_secret'] = $gustoDetails->CLIENT_SECRET;
        $body['redirect_uri'] = $gustoDetails->CALLBACK_URL;
        $body['refresh_token'] = $request['refresh_token'];
        $body['grant_type'] = 'refresh_token';
        //
        return makeCall(
            getUrl('refreshToken', ''),
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
}

if (!function_exists('hasGustoErrors')) {
    /**
     * Parse Gusto errors
     *
     * Convert Gusto errors to AutomotoHR errors
     * for handling errors
     *
     * @param mixed $response
     * @return array
     */
    function hasGustoErrors($response)
    {
        // set errors array
        $errors = [
            'errors' => []
        ];
        // if it's a single error
        if (isset($response['message'])) {
            $errors['errors'][] = $response['message'];
        } elseif (isset($response['error'])) {
            $errors['errors'][] = $response['error'];
        } elseif (isset($response['errors']['invalid_grant'])) {
            $errors['errors'] = array_merge($errors['errors'], $response['errors']['invalid_grant']);
        } elseif (isset($response['errors'])) {
            foreach ($response['errors'] as $err) {
                //
                if (isset($err['errors'])) {
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
}

if (!function_exists('getUrl')) {
    /**
     * get the required URL
     *
     * @param string $index
     * @param string $key
     * @param string $key1 Optional
     * @param string $key2 Optional
     * @return string
     */
    function getUrl($index, $key = '', $key1 = '', $key2 = '')
    {
        // set default URL
        $urls = [];
        $urls['me'] = 'v1/me';
        $urls['refreshToken'] = 'oauth/token?' . ($key);
        $urls['getWebHooks'] = 'v1/webhook_subscriptions';
        $urls['deleteWebHook'] = "v1/webhook_subscriptions/$key";
        $urls['requestVerificationToken'] = "v1/webhook_subscriptions/$key/request_verification_token";

        // company URLs
        $urls['createPartnerCompany'] = "v1/partner_managed_companies";
        $urls['agreeToServiceAgreementFromGusto'] = "v1/partner_managed_companies/$key/accept_terms_of_service";
        // get all company admins
        $urls['getAdminsFromGusto'] = "v1/companies/$key/admins";
        $urls['createAdminOnGusto'] = "v1/companies/$key/admins";
        $urls['createSignatory'] = "v1/companies/$key/signatories";
        $urls['createCompanyLocationOnGusto'] = "v1/companies/$key/locations";
        $urls['createEmployeeOnGusto'] = "v1/companies/$key/employees";
        $urls['getCompanyEarningWage'] = "v1/companies/$key/earned_wage_access";
        // for sync purpose
        $urls['getFederalTax'] = "v1/companies/$key/federal_tax_details";
        $urls['getIndustry'] = "v1/companies/$key/industry_selection";
        $urls['getPaySchedules'] = "v1/companies/$key/pay_schedules";
        $urls['updatePaySchedules'] = "v1/companies/$key/pay_schedules/$key1";
        $urls['getPaymentConfig'] = "v1/companies/$key/payment_configs";
        $urls['getBankAccounts'] = "v1/companies/$key/bank_accounts";
        $urls['sendDeposits'] = "v1/companies/$key/bank_accounts/$key1/send_test_deposits";
        $urls['verifyBankAccount'] = "v1/companies/$key/bank_accounts/$key1/verify";
        $urls['verifyCompany'] = "v1/companies/$key/approve";
        $urls['updatePaymentConfig'] = "v1/companies/$key/payment_configs";
        $urls['assignEmployeesToPaySchedules'] = "v1/companies/$key/pay_schedules/assign";
        // payroll blocker
        $urls['getPayrollBlockers'] = "v1/companies/$key/payrolls/blockers";
        //company flow
        $urls['getCompanyOnboardFlow'] = "v1/companies/$key/flows";
        // get company earning types
        $urls['getCompanyEarningTypes'] = "v1/companies/$key/earning_types";
        $urls['deactivateCompanyEarningTypes'] = "v1/companies/$key/earning_types/$key1";
        $urls['addCompanyEarningTypes'] = "v1/companies/$key/earning_types";
        $urls['editCompanyEarningTypes'] = "v1/companies/$key/earning_types/$key1";
        // webhooks
        $urls['createCompanyWebHook'] = "v1/webhook_subscriptions";
        $urls['verifyCompanyWebHook'] = "v1/webhook_subscriptions/$key/verify";
        // employee URLs
        $urls['createEmployeeJobOnGusto'] = "v1/employees/$key1/jobs";
        $urls['getEmployeeJobs'] = "v1/employees/$key1/jobs";
        // flow urls
        $urls['updateEmployeePersonalDetails'] = "v1/employees/$key1";
        $urls['getEmployeeWorkAddress'] = "v1/employees/$key1/work_addresses";
        $urls['updateEmployeeWorkAddress'] = "v1/work_addresses/$key1";
        $urls['updateEmployeeJob'] = "v1/jobs/$key1";
        $urls['updateEmployeeJobCompensation'] = "v1/compensations/$key1";
        $urls['getSingleJob'] = "v1/jobs/$key1";
        // home address urls
        $urls['updateHomeAddress'] = "v1/home_addresses/$key1";
        $urls['createHomeAddress'] = "v1/employees/$key1/home_addresses";
        // federal tax
        $urls['createFederalTax'] = "v1/employees/$key1/federal_taxes";
        // state tax
        $urls['getStateTax'] = "v1/employees/$key1/state_taxes";
        $urls['updateStateTax'] = "v1/employees/$key1/state_taxes";
        // payment method
        $urls['getPaymentMethod'] = "v1/employees/$key1/payment_method";
        $urls['updatePaymentMethod'] = "v1/employees/$key1/payment_method";
        // bank account
        $urls['addBankAccount'] = "v1/employees/$key1/bank_accounts";
        $urls['deleteBankAccount'] = "v1/employees/$key1/bank_accounts/$key2";
        // forms
        $urls['getEmployeeForms'] = "v1/employees/$key1/forms";
        $urls['getEmployeeFormPdf'] = "v1/employees/$key1/forms/$key2/pdf";
        $urls['signEmployeeForm'] = "v1/employees/$key1/forms/$key2/sign";
        // summary
        $urls['getEmployeeSummary'] = "v1/employees/$key1/onboarding_status";
        // finish onboard
        $urls['finishEmployeeOnboard'] = "v1/employees/$key1/onboarding_status";
        // remove employee from onboarding
        $urls['removeOnboardEmployee'] = "v1/employees/$key1";
        // Contractors
        $urls['createContractor'] = "v1/companies/$key/contractors";
        $urls['getContractor'] = "v1/contractors/$key1";
        $urls['updateContractor'] = "v1/contractors/$key1";
        $urls['getContractorHomeAddress'] = "v1/contractors/$key1/address";
        $urls['updateContractorHomeAddress'] = "v1/contractors/$key1/address";
        $urls['getContractorPaymentMethod'] = "v1/contractors/$key1/payment_method";
        $urls['updateContractorPaymentMethod'] = "v1/contractors/$key1/payment_method";
        $urls['createContractorBankAccount'] = "v1/contractors/$key1/bank_accounts";
        $urls['getContractorStatus'] = "v1/contractors/$key1/onboarding_status";
        $urls['updateContractorOnboardingStatus'] = "v1/contractors/$key1/onboarding_status";
        //
        $urls['getContractorDocuments'] = "v1/contractors/$key1/forms";
        $urls['getContractorFormPdf'] = "v1/contractors/$key1/forms/$key2/pdf";

        // External payroll calls
        // create external payroll
        $urls['createExternalPayroll'] = "v1/companies/$key/external_payrolls";
        // get external payroll
        $urls['getExternalPayroll'] = "v1/companies/$key/external_payrolls/$key1";
        // delete external payroll
        $urls['deleteExternalPayroll'] = "v1/companies/$key/external_payrolls/$key1";
        // get tax suggestions
        $urls['getExternalPayrollTaxSuggestions'] = "v1/companies/$key/external_payrolls/$key1/calculate_taxes";
        // update
        $urls['updateExternalPayroll'] = "v1/companies/$key/external_payrolls/$key1";
        // calculate
        $urls['calculateExternalPayrollTaxes'] = "v1/companies/$key/external_payrolls/$key1/calculate_taxes";
        // get tax liabilities
        $urls['getExternalPayrollTaxLiabilities'] = "v1/companies/$key/external_payrolls/tax_liabilities";
        // update tax liabilities
        $urls['updateExternalPayrollTaxLiabilities'] = "v1/companies/$key/external_payrolls/tax_liabilities";
        // confirm tax liabilities
        $urls['confirmExternalPayrollTaxLiabilities'] = "v1/companies/$key/external_payrolls/tax_liabilities/finish";

        // Regular payroll calls
        // blockers
        $urls["getPayrollBlockers"] =
            "v1/companies/$key/payrolls/blockers";
        // unprocessed payroll by start date
        $urls["getUnprocessedPayrollsByStartDate"] =
            "v1/companies/$key/payrolls?processing_statuses=unprocessed&start_date=$key1&payroll_types=regular&include=totals";
        // payroll by id
        $urls["getSinglePayrollById"] =
            "v1/companies/$key/payrolls/$key1?include=benefits,deductions,taxes,payroll_status_meta";
        // prepare by id
        $urls["preparePayrollForUpdate"] =
            "v1/companies/$key/payrolls/$key1/prepare?include=benefits,deductions,taxes,payroll_status_meta";
        // update payroll by id
        $urls["updateRegularPayrollById"] =
            "v1/companies/$key/payrolls/$key1";
        // calculate payroll by id
        $urls["calculateSinglePayroll"] =
            "v1/companies/$key/payrolls/$key1/calculate";
        // submit payroll by id
        $urls["submitSinglePayrollById"] =
            "v1/companies/$key/payrolls/$key1/submit";
        // cancel payroll by id
        $urls["cancelSinglePayrollById"] =
            "v1/companies/$key/payrolls/$key1/cancel";
        // get payroll receipt by id
        $urls["getSinglePayrollReceipt"] =
            "v1/payrolls/$key1/receipt";
        // get payroll employee pay stubs
        $urls["getSinglePayrollEmployeePayStubs"] =
            "v1/payrolls/$key1/employees/$key2/pay_stub";

        // Garnishment
        // create
        $urls["createGarnishment"] =
            "v1/employees/$key1/garnishments";
        // update
        $urls["updateGarnishment"] =
            "v1/garnishments/$key1";

        // company benefits
        // create
        $urls['createCompanyBenefit'] =
            "v1/companies/$key/company_benefits";
        // update
        $urls['updateCompanyBenefit'] =
            "v1/company_benefits/$key1";
        // delete
        $urls['deleteCompanyBenefit'] =
            "v1/company_benefits/$key1";
        // create employee benefit
        $urls['createEmployeeBenefit'] =
            "v1/employees/$key1/employee_benefits";
        // update employee benefit
        $urls['updateEmployeeBenefit'] =
            "v1/employee_benefits/$key1";
        // delete employee benefit
        $urls['deleteEmployeeBenefit'] =
            "v1/employee_benefits/$key1";

        // off cycle payrolls
        $urls["createOffCyclePayroll"] = "v1/companies/$key/payrolls";
        //
        $urls['createEmployeeTerminationOnGusto'] = 'v1/employees/' . ($key) . '/terminations';
        $urls['createEmployeeRehireOnGusto'] = 'v1/employees/' . ($key) . '/rehire';
        $urls['UpdateEmployeeTerminationOnGusto'] = 'v1/terminations/' . ($key);
        $urls['UpdateEmployeeRehireOnGusto'] = 'v1/employees/' . ($key) . '/rehire';
        // location minimum wages
        $urls["locationMinimumWages"] = "v1/locations/$key/minimum_wages";

        return getCreds("AHR")->GUSTO->DEMO->URL . $urls[$index];
    }
}

// Actual calls
if (!function_exists('createPartnerCompany')) {
    /**
     * create partner company on Gusto
     *
     * @param array $request
     * @return array
     */
    function createPartnerCompany(array $request): array
    {
        return makeCall(
            getUrl('createPartnerCompany'),
            [
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Token ' . (getCreds("AHR")->GUSTO->DEMO->API_TOKEN) . '',
                    'Content-Type: application/json',
                    'X-Gusto-API-Version: 2023-04-01'
                )
            ]
        );
    }
}



if (!function_exists('getAdminsFromGusto')) {
    /**
     * get company admins from Gusto
     *
     * @param array $company
     * @return array
     */
    function getAdminsFromGusto(array $company): array
    {
        // set call headers
        $callHeaders = [
            'Authorization: Bearer ' . ($company['access_token']) . '',
            'Content-Type: application/json',
            'Accept: application/json',
            'X-Gusto-API-Version: 2023-04-01'
        ];
        // make call to Gusto
        $response =  makeCall(
            getUrl('getAdminsFromGusto', $company['gusto_uuid']),
            [
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => $callHeaders
            ]
        );
        // auth failed needs to generate new tokens
        if (isset($response['errors']['auth'])) {
            // generate new access token
            $tokenResponse = refreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            // generated
            if (isset($tokenResponse['access_token'])) {
                // update in database
                updateToken($tokenResponse, ['gusto_uuid' => $company['gusto_uuid']]);
                // set to local variable
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                // recall the event
                return getAdminsFromGusto($company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        } else {
            // pass actual response
            return $response;
        }
    }
}

if (!function_exists('createAdminOnGusto')) {
    /**
     * create company admin on Gusto
     *
     * @param array $request
     * @param array $company
     * @return array
     */
    function createAdminOnGusto(array $request, array $company): array
    {
        // set call headers
        $callHeaders = [
            'Authorization: Bearer ' . ($company['access_token']) . '',
            'Content-Type: application/json',
            'Accept: application/json',
            'X-Gusto-API-Version: 2023-04-01'
        ];
        // make call to Gusto
        $response =  makeCall(
            getUrl('createAdminOnGusto', $company['gusto_uuid']),
            [
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => $callHeaders
            ]
        );
        // auth failed needs to generate new tokens
        if (isset($response['errors']['auth'])) {
            // generate new access token
            $tokenResponse = refreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            // generated
            if (isset($tokenResponse['access_token'])) {
                // update in database
                updateToken($tokenResponse, ['gusto_uuid' => $company['gusto_uuid']]);
                // set to local variable
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                // recall the event
                return createAdminOnGusto($request, $company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        } else {
            // pass actual response
            return $response;
        }
    }
}

if (!function_exists('agreeToServiceAgreementFromGusto')) {
    /**
     * get company admins from Gusto
     *
     * @param array $company
     * @return array
     */
    function agreeToServiceAgreementFromGusto(array $request, array $company): array
    {
        // set call headers
        $callHeaders = [
            'Authorization: Bearer ' . ($company['access_token']) . '',
            'Content-Type: application/json',
            'Accept: application/json',
            'X-Gusto-API-Version: 2023-04-01'
        ];
        // make call to Gusto
        $response =  makeCall(
            getUrl('agreeToServiceAgreementFromGusto', $company['gusto_uuid']),
            [
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => $callHeaders
            ]
        );
        // auth failed needs to generate new tokens
        if (isset($response['errors']['auth'])) {
            // generate new access token
            $tokenResponse = refreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            // generated
            if (isset($tokenResponse['access_token'])) {
                // update in database
                updateToken($tokenResponse, ['gusto_uuid' => $company['gusto_uuid']]);
                // set to local variable
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                // recall the event
                return agreeToServiceAgreementFromGusto($request, $company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        } else {
            // pass actual response
            return $response;
        }
    }
}

if (!function_exists('gustoCall')) {
    /**
     * get company admins from Gusto
     *
     * @param array $company
     * @return array
     */
    function gustoCall(
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
            'X-Gusto-API-Version: 2023-04-01'
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
        $response =  makeCall(
            getUrl(
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
            $tokenResponse = refreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            // generated
            if (isset($tokenResponse['access_token'])) {
                // update in database
                updateToken($tokenResponse, ['gusto_uuid' => $company['gusto_uuid']]);
                // set to local variable
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                // recall the event
                return gustoCall($event, $company, $request, $requestType);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        } else {
            // pass actual response
            return $response;
        }
    }
}


if (!function_exists('getBankAccountForGusto')) {
    /**
     * converts bank array to Gusto
     *
     * @param array $bankAccounts
     * @return array
     */
    function getBankAccountForGusto(array $bankAccounts): array
    {
        // set return
        $return = [
            'split_by' => 'Percentage',
            'splits' => []
        ];
        //
        foreach ($bankAccounts as $index => $account) {
            //
            if ($index == 0 && $account['deposit_type'] == 'amount') {
                $return['split_by'] = 'Amount';
            }
            //
            $splitAmount = $return['split_by'] === 'Amount'
                ? ($account['account_percentage'] * 500)
                : $account['account_percentage'] / count($bankAccounts);
            //
            $return['splits'][] = [
                'uuid' => $account['gusto_uuid'],
                'name' => $account['account_title'],
                'priority' => ($index + 1),
                'split_amount' => $splitAmount
            ];
        }
        //
        if (count($bankAccounts) == 1) {
            $return['splits'][0]['split_amount'] = 100;
            $return['split_by'] = 'Percentage';
        }

        //
        if (count($bankAccounts) > 1) {
            $return['splits'][1]['split_amount'] = null;
        }
        //
        return $return;
    }
}


if (!function_exists('calculateTax')) {
    /**
     * calculate employee taxes
     *
     * @param array $taxes
     * @return array
     */
    function calculateTax(array $taxes): array
    {
        //
        $returnArray = [
            'employee_tax_total' => 0,
            'employer_tax_total' => 0,
            'employee_taxes' => [],
            'employer_taxes' => [],
        ];

        //
        if ($taxes) {
            foreach ($taxes as $tax) {
                //
                $returnArray[$tax['employer'] ? 'employer_tax_total' : 'employee_tax_total'] += $tax['amount'];
                $returnArray[$tax['employer'] ? 'employer_taxes' : 'employee_taxes'][stringToSlug($tax['name'], '_')] = $tax;
            }
        }

        //
        return $returnArray;
    }
}

if (!function_exists('extractTaxes')) {
    /**
     * extract taxes
     *
     * @param array $employees
     * @return array
     */
    function extractTaxes(array $employees): array
    {
        //
        $returnArray = [];
        //
        if ($employees) {
            foreach ($employees as $value) {
                //
                if ($value['taxes']) {
                    foreach ($value['taxes'] as $v1) {
                        //
                        $taxSlug = stringToSlug($v1['name'], '_');
                        //
                        if (!isset($returnArray[$taxSlug])) {
                            $returnArray[$taxSlug] = [
                                'name' => $v1['name'],
                                'employee_tax_total' => 0,
                                'employer_tax_total' => 0,
                            ];
                        }
                        //
                        $returnArray[$taxSlug][$v1['employer'] ? 'employer_tax_total' : 'employee_tax_total'] += $v1['amount'];
                    }
                }
            }
        }

        //
        return $returnArray;
    }
}


if (!function_exists('calculateBenefits')) {
    /**
     * calculate employee taxes
     *
     * @param array $benefits
     * @return array
     */
    function calculateBenefits(array $benefits): array
    {
        //
        $returnArray = [
            'employee_tax_total' => 0,
            'employer_tax_total' => 0,
            'employee_taxes' => [],
            'employer_taxes' => [],
        ];

        //
        if ($benefits) {
            foreach ($benefits as $tax) {
                //
                $returnArray['employer_tax_total'] += $tax['company_contribution'];
                $returnArray['employee_tax_total'] += $tax['employee_deduction'];
                $returnArray[$tax['employee_deduction'] ? 'employee_taxes' : 'employer_taxes'][stringToSlug($tax['name'], '_')] = $tax;
            }
        }

        //
        return $returnArray;
    }
}

if (!function_exists('calculateDeductions')) {
    /**
     * calculate employee deductions
     *
     * @param array $deductions
     * @return array
     */
    function calculateDeductions(array $deductions): array
    {
        //
        $returnArray = [
            'employee_tax_total' => 0,
            'employee_taxes' => [],
        ];

        //
        if ($deductions) {
            foreach ($deductions as $tax) {
                //
                $returnArray['employee_tax_total'] += $tax['amount'];
                $returnArray['employee_taxes'][stringToSlug($tax['name'], '_')] = $tax;
            }
        }

        //
        return $returnArray;
    }
}


if (!function_exists('getPaymentUnitType')) {
    /**
     * get job compensation text
     *
     * @param string $paymentUnit
     * @return string
     */
    function getPaymentUnitType(string $paymentUnit): string
    {
        //
        $flsaStatus = 'Paid by the hour';
        //
        if ($paymentUnit === 'Exempt') {
            $flsaStatus = 'Salary/No overtime';
        } elseif ($paymentUnit === 'Salaried Nonexempt') {
            $flsaStatus = 'Salary/with overtime';
        }


        return $flsaStatus;
    }
}


if (!function_exists('getReason')) {
    /**
     * get reason for Gusto
     *
     * @param string $reason
     * @return string
     */
    function getReason(string $reason): string
    {
        // get reason
        $reasonsArray = [];
        $reasonsArray['bonus'] = 'Bonus';
        $reasonsArray['corrections'] = 'Correction';
        $reasonsArray['dismissed-employee'] = 'Dismissed employee';
        $reasonsArray['transition'] = 'Transition from old pay schedule';
        //
        return $reasonsArray[$reason] ?? 'Bonus';
    }
}

// Employee related calls
if (!function_exists('createAnEmployeeTerminationOnGusto')) {
    function createAnEmployeeTerminationOnGusto($request, $company, $payroll_employee_uuid, $headers = [])
    {
        //
        $callHeaders = [
            'Authorization: Bearer ' . ($company['access_token']) . '',
            'Accept: application/json',
            'Content-Type: application/json'
        ];
        //
        $callHeaders = array_merge($callHeaders, $headers);
        //
        $response =  MakeCall(
            getUrl('createEmployeeTerminationOnGusto', $payroll_employee_uuid),
            [
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => $callHeaders
            ]
        );
        //
        // $response = [
        //     "uuid" => "da441196-43a9-4d23-ad5d-f37ce6bb99c0",
        //     "employee_uuid" => "da441196-43a9-4d23-ad5d-f37ce6bb99c0",
        //     "version" => "d487dd0b55dfcacdd920ccbdaeafa351",
        //     "active" => true,
        //     "cancelable" => true,
        //     "effective_date" => $request['effective_date'],
        //     "run_termination_payroll" => false
        // ];
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_uuid' => $company['gusto_uuid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return createAnEmployeeTerminationOnGusto($request, $company, $payroll_employee_uuid, $headers);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}

// Employee related calls
if (!function_exists('createAnEmployeeRehireOnGusto')) {
    function createAnEmployeeRehireOnGusto($request, $company, $payroll_employee_uuid, $headers = [])
    {
        //
        $callHeaders = [
            'Authorization: Bearer ' . ($company['access_token']) . '',
            'Accept: application/json',
            'Content-Type: application/json'
        ];
        //
        $callHeaders = array_merge($callHeaders, $headers);
        //
        $response =  MakeCall(
            getUrl('createEmployeeRehireOnGusto', $payroll_employee_uuid),
            [
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => $callHeaders
            ]
        );
        //
        // $response = [
        //     "version" => "2e930d43acbdb241f8f14a2d531fa417",
        //     "employee_uuid" => "da441196-43a9-4d23-ad5d-f37ce6bb99c0",
        //     "active" => false,
        //     "effective_date" => $request['effective_date'],
        //     "file_new_hire_report" => false,
        //     "work_location_uuid" => "d2c80d44-857b-4d4d-bce4-23ae52cc863b,",
        //     "two_percent_shareholder" => false,
        //     "employment_status" => "full_time"
        // ];
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_uuid' => $company['gusto_uuid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return createAnEmployeeRehireOnGusto($request, $company, $payroll_employee_uuid, $headers);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}

// Employee related calls
if (!function_exists('updateAnEmployeeTerminationOnGusto')) {
    function updateAnEmployeeTerminationOnGusto($request, $company, $payroll_employee_uuid, $headers = [])
    {
        //
        $callHeaders = [
            'Authorization: Bearer ' . ($company['access_token']) . '',
            'accept: application/json',
            'content-type: application/json'
        ];
        //
        $callHeaders = array_merge($callHeaders, $headers);
        //
        $response =  MakeCall(
            getUrl('UpdateEmployeeTerminationOnGusto', $payroll_employee_uuid),
            [
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => $callHeaders
            ]
        );
        //
        // $response = [
        //     "uuid" => "da441196-43a9-4d23-ad5d-f37ce6bb99c0",
        //     "employee_uuid" => "da441196-43a9-4d23-ad5d-f37ce6bb99c0",
        //     "version" => "update12345Termination",
        //     "active" => true,
        //     "cancelable" => true,
        //     "effective_date" => $request['effective_date'],
        //     "run_termination_payroll" => false
        // ];
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_uuid' => $company['gusto_uuid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return updateAnEmployeeTerminationOnGusto($request, $company, $payroll_employee_uuid, $headers);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}


// Employee related calls
if (!function_exists('updateAnEmployeeRehireOnGusto')) {
    function updateAnEmployeeRehireOnGusto($request, $company, $payroll_employee_uuid, $headers = [])
    {
        //
        $callHeaders = [
            'Authorization: Bearer ' . ($company['access_token']) . '',
            'accept: application/json',
            'content-type: application/json'
        ];
        //
        $callHeaders = array_merge($callHeaders, $headers);
        //
        $response =  MakeCall(
            getUrl('UpdateEmployeeRehireOnGusto', $payroll_employee_uuid),
            [
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => $callHeaders
            ]
        );
        //
        // $response = [
        //     "version" => "update12345rehired",
        //     "employee_uuid" => "da441196-43a9-4d23-ad5d-f37ce6bb99c0",
        //     "active" => false,
        //     "effective_date" => $request['effective_date'],
        //     "file_new_hire_report" => false,
        //     "work_location_uuid" => "d2c80d44-857b-4d4d-bce4-23ae52cc863b,",
        //     "two_percent_shareholder" => false,
        //     "employment_status" => "full_time"
        // ];
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_uuid' => $company['gusto_uuid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return updateAnEmployeeRehireOnGusto($request, $company, $payroll_employee_uuid, $headers);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}
