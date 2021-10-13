<?php

if(!function_exists('getPackagesList')){
    function getPackagesList(){
        $resp = callAH(
            ASSUREHIR_PACKAGES_URL,
            [
                CURLOPT_CUSTOMREQUEST => 'GET'
            ]
        );
        // $resp['Result'] = '[{"id":"715648052022478114","name":"Management","created_at":"2018-09-14T10:44:55-07:00","updated_at":"2020-04-01T08:16:25.469-07:00","descriptions":["SSN Address Trace","National DOJ Sex Offender","National Criminal Database","7yr County Criminal Records","Drivers Record","1 National Practitioner Data Bank"],"ssn":true,"crct":"7yr","msmj":true,"mvdr":true,"npdb":"1x","sxdb":true},{"id":"715648483599582519","name":"Management with Drug Screen","created_at":"2018-09-14T10:45:47-07:00","updated_at":"2018-09-14T10:46:31.530-07:00","descriptions":["SSN Address Trace","National DOJ Sex Offender","National Criminal Database","7yr County Criminal Records"],"ssn":true,"crct":"7yr","msmj":true,"sxdb":true},{"id":"55994665500935170","name":"Pro","created_at":"2016-03-18T07:11:24-07:00","updated_at":"2020-06-24T13:02:01.253-07:00","descriptions":["SSN Address Trace","National DOJ Sex Offender","Excluded Parties/Terrorist Watchlist","National Criminal Database","7yr County Criminal Records","1 Education Verification","5yr Employment Verification"],"ssn":true,"crct":"7yr","expl":true,"msmj":true,"sxdb":true,"vedu":"1x","vemp":"5yr"},{"id":"715649324163269960","name":"Standard","created_at":"2018-09-14T10:47:27-07:00","updated_at":"2019-04-16T08:17:56.591-07:00","descriptions":["SSN Address Trace","National DOJ Sex Offender","National Criminal Database","7yr County Criminal Records"],"ssn":true,"crct":"7yr","msmj":true,"sxdb":true},{"id":"715649653273527637","name":"Standard with Drug Screen","created_at":"2018-09-14T10:48:06-07:00","updated_at":"2020-01-24T07:34:26.000-08:00","descriptions":["SSN Address Trace","National DOJ Sex Offender","National Criminal Database","7yr Statewide Criminal Records","Drivers Record","Drug Test 5 Panel Hair"],"dsh":true,"ssn":true,"crst":"7yr","msmj":true,"mvdr":true,"sxdb":true,"dsh_panel":"5-Panel Urine"},{"id":"729712566770599198","name":"Verifications Only","created_at":"2018-10-03T20:28:36-07:00","updated_at":"2018-10-03T20:28:36.660-07:00","descriptions":["1 Education Verification","5yr Employment Verification"],"vedu":"1x","vemp":"5yr"}]';
        //
        return json_decode($resp['Result'], true);
    }
}


if(!function_exists('placeOrderAH')){
    function placeOrderAH($json){
        $resp = json_decode($json, true);
        $r = [];
        $r['orderInfo'] = $resp;
        $r['orderStatus'] = [];
        //
        $result = callAH(
            ASSUREHIR_ORDER_URL,
            [
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $json
            ]
        );
        //
        $r['orderStatus'] = json_decode($result['Result'], true);
        return json_encode($r);
    }
}


// Send curl request
if(!function_exists('callAH')){
    function callAH($url, $options){
        //
        $curl = curl_init();
        //
        $options[CURLOPT_URL] = $url;
        $options[CURLOPT_SSL_VERIFYPEER] = false;
        $options[CURLOPT_SSL_VERIFYHOST] = false;
        $options[CURLOPT_RETURNTRANSFER] = true;
        $options[CURLOPT_HTTP_VERSION] = CURL_HTTP_VERSION_1_1;
        $options[CURLOPT_HTTPHEADER] = [
            'Content-Type: application/json',
            'Authorization: Bearer '.(ASSUREHIRE_APIKEY).''
        ];
        //
        curl_setopt_array($curl, $options);
        //
        $result = curl_exec($curl);
        $info = curl_getinfo($curl);
        $error = curl_error($curl);
        //
        return ['Result' => $result, 'Info' => $info, 'Error' => $error];
    }
}


// Check Company AssureHire Module is active or not
if(!function_exists('checkAssureHireEnable')){
    function checkAssureHireEnable($company_sid){
        $CI = &get_instance();
        $CI->db->select('sid');
        $CI->db->where('module_name', "assurehire");
        $module_info = $CI->db->get('modules')->row_array();

        if (!empty($module_info)) {
            $module_sid = $module_info["sid"];

            $CI->db->select('sid');
            $CI->db->where('module_sid', $module_sid);
            $CI->db->where('company_sid', $company_sid);
            $module_enable = $CI->db->get('company_modules')->row_array();

            if (!empty($module_enable)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}

// Check Company AssureHire Module is active or not
if(!function_exists('checkProductBrand')){
    function checkProductBrand($product_sid){
        $CI = &get_instance();
        $CI->db->where('sid', $product_sid);
        $CI->db->where('LOWER(name) regexp "assurehire"', NULL, NULL);
        $product_count = $CI->db->count_all_results('products');

        if ($product_count) {
            return true;
        } else {
            return false;
        }
    }
}


// Check Company AssureHire Module is active or not
if(!function_exists('createAssurehireJson')){
    function createAssurehireJson($data_employer, $session, $portalDetails, $user_info, $package_code){
        //getting country and state details
        $stateCountry = db_get_state_name($data_employer['Location_State']);
        $company_name = $session["company_detail"]["CompanyName"];

        if (strlen($company_name) > 24) {
            $company_name = substr($company_name, 0, 24);
        }

        $employer_name = $session["employer_detail"]["first_name"] . ' ' . $session["employer_detail"]["last_name"];
        if (strlen($employer_name) > 24) {
            $employer_name = substr($employer_name, 0, 24);
        }

        $sub_domain = $portalDetails['sub_domain'];
        if (strlen($sub_domain) > 24) {
            $sub_domain = substr($sub_domain, 0, 24);
        }

        $user_name = $user_info['first_name'] . ' ' . $user_info['last_name'];

        if (strlen($user_name) > 24) {
            $user_name = substr($user_name, 0, 24);
        }
        //
        $orderReference = generateRandomString(8);
        //
        $requestArray = [];
        $requestArray['orderReference'] = $orderReference;
        $requestArray['packageId'] = $package_code;
        $requestArray['callback_url'] = "https://staging.automotohr.com/assurehire/cb/".$orderReference;
        // Set requester
        $requestArray['requestor'] = [];
        $requestArray['requestor']['company'] = $company_name;
        $requestArray['requestor']['name'] = $employer_name;
        $requestArray['requestor']['email'] = ASSUREHIR_USR;
        $requestArray['requestor']['address'] = [];
        $requestArray['requestor']['address']['countryCode'] = $stateCountry['country_code'];
        $requestArray['requestor']['address']['region'] = $stateCountry['state_code'];
        $requestArray['requestor']['address']['city'] = !empty($stateCountry['Location_City']) ? $stateCountry['Location_City'] : 'N/A';
        $requestArray['requestor']['address']['street'] = !empty($stateCountry['Location_Address']) ? $stateCountry['Location_Address'] : 'N/A';
        $requestArray['requestor']['address']['postalCode'] = !empty($stateCountry['Location_ZipCode']) ? $stateCountry['Location_ZipCode'] : 'N/A';
        // Set candidate
        $requestArray['candidate'] = [];
        $requestArray['candidate']['name'] = [];
        $requestArray['candidate']['name']['firstname'] = $data_employer['first_name'];
        $requestArray['candidate']['name']['lastname'] = $data_employer['last_name'];
        $requestArray['candidate']['name']['middlename'] = !empty($data_employer['middle_name']) ? $data_employer['middle_name'] : 'N/A';
        $requestArray['candidate']['aka'] = [];
        $requestArray['candidate']['dateOfBirth'] = date('Y-m-d', strtotime($data_employer['full_employment_application']['TextBoxDOB']));
        $requestArray['candidate']['ssn'] = $data_employer['full_employment_application']['TextBoxSSN'];
        //
        $requestArray['candidate']['governmentId'] = [];
        $requestArray['candidate']['governmentId']['countryCode'] = "N/A";
        $requestArray['candidate']['governmentId']['type'] = "N/A";
        $requestArray['candidate']['governmentId']['number'] = "N/A";
        //
        $requestArray['candidate']['contact'] = [];
        $requestArray['candidate']['contact']['email'] = $data_employer['email'];
        $requestArray['candidate']['contact']['phone'] = !empty($data_employer['PhoneNumber']) ? $data_employer['PhoneNumber'] : "N/A";
        //
        $requestArray['candidate']['address'] = [];
        $requestArray['candidate']['address']['street'] = $data_employer['Location_Address'];
        $requestArray['candidate']['address']['street2'] = "N/A";
        $requestArray['candidate']['address']['city'] = !empty($data_employer['Location_City']) ? $data_employer['Location_City'] : 'N/A';
        $requestArray['candidate']['address']['region'] = !empty($data_employer['state_code']) ? $data_employer['state_code'] : 'N/A';
        $requestArray['candidate']['address']['country'] = !empty($data_employer['country_code']) ? $data_employer['country_code'] : 'N/A';
        $requestArray['candidate']['address']['postCode'] = !empty($data_employer['Location_ZipCode']) ? $data_employer['Location_ZipCode'] : 'N/A';
        // Push employment
        $requestArray['candidate']['employment'] = [];
        $requestArray['candidate']['employment']['employer'] = !empty($user_info['full_employment_application']['TextBoxEmploymentEmployerName1']) ? $user_info['full_employment_application']['TextBoxEmploymentEmployerName1'] :  'N/A';
        $requestArray['candidate']['employment']['agency'] = 'N/A';
        $requestArray['candidate']['employment']['title'] =  !empty($user_info['full_employment_application']['TextBoxEmploymentEmployerPosition1']) ? $user_info['full_employment_application']['TextBoxEmploymentEmployerPosition1'] :  'N/A';
        $requestArray['candidate']['employment']['address'] = [];
        $requestArray['candidate']['employment']['address']['street'] = !empty($user_info['full_employment_application']['TextBoxEmploymentEmployerAddress1']) ? $user_info['full_employment_application']['TextBoxEmploymentEmployerAddress1'] :  'N/A';
        $requestArray['candidate']['employment']['address']['city'] = !empty($user_info['full_employment_application']['TextBoxEmploymentEmployerCity1']) ? $user_info['full_employment_application']['TextBoxEmploymentEmployerCity1'] :  'N/A';
        $requestArray['candidate']['employment']['address']['region'] = !empty($user_info['full_employment_application']['DropDownListEmploymentEmployerState1']) ? db_get_state_name_only($user_info['full_employment_application']['DropDownListEmploymentEmployerState1'], 'state_code'):  'N/A';
        $requestArray['candidate']['employment']['supervisor'] =  !empty($user_info['full_employment_application']['TextBoxEmploymentEmployerSupervisor1']) ? $user_info['full_employment_application']['TextBoxEmploymentEmployerSupervisor1'] :  'N/A';
        $requestArray['candidate']['employment']['phone'] =  !empty($user_info['full_employment_application']['TextBoxEmploymentEmployerPhoneNumber1']) ? $user_info['full_employment_application']['TextBoxEmploymentEmployerPhoneNumber1'] :  'N/A';
        $requestArray['candidate']['employment']['status'] =  'current';
        $requestArray['candidate']['employment']['startMonth'] =  !empty($user_info['full_employment_application']['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin1']) ? $user_info['full_employment_application']['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin1'] :  'N/A';
        $requestArray['candidate']['employment']['startYear'] =  !empty($user_info['full_employment_application']['DropDownListEmploymentEmployerDatesOfEmploymentYearBegin1']) ? $user_info['full_employment_application']['DropDownListEmploymentEmployerDatesOfEmploymentYearBegin1'] :  'N/A';
        $requestArray['candidate']['employment']['endMonth'] =  !empty($user_info['full_employment_application']['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd1']) ? $user_info['full_employment_application']['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd1'] :  'N/A';
        $requestArray['candidate']['employment']['endYear'] =  !empty($user_info['full_employment_application']['DropDownListEmploymentEmployerDatesOfEmploymentYearEnd1']) ? $user_info['full_employment_application']['DropDownListEmploymentEmployerDatesOfEmploymentYearEnd1'] :  'N/A';
        $requestArray['candidate']['employment']['reason'] =  !empty($user_info['full_employment_application']['TextBoxEmploymentEmployerReasonLeave1']) ? $user_info['full_employment_application']['TextBoxEmploymentEmployerReasonLeave1'] :  'N/A';
        $requestArray['candidate']['employment']['contactEmployer'] =  !empty($user_info['full_employment_application']['RadioButtonListEmploymentEmployerContact1_0']) ? $user_info['full_employment_application']['RadioButtonListEmploymentEmployerContact1_0'] :  'N/A';
        // Push drivers license
        $requestArray['candidate']['driverLicense'] = [];
        $requestArray['candidate']['driverLicense']['number'] = !empty($user_info['full_employment_application']['TextBoxDriversLicenseNumber']) ? $user_info['full_employment_application']['TextBoxDriversLicenseNumber'] :  'N/A';
        $requestArray['candidate']['driverLicense']['issuedBy'] = !empty($user_info['full_employment_application']['DropDownListDriversState']) ? db_get_state_name_only($user_info['full_employment_application']['DropDownListDriversState'], 'state_code') :  'N/A';
        //
        $requestArray['convicted'] = "N/A";
        $requestArray['conviction'] = [];
        $requestArray['conviction'][] = [
            'convictionDate' => "N/A",
            'description' => "N/A",
            'location' => [
                'countryCode' => "N/A",
                'region' => "N/A",
                'region2' => "N/A",
                'city' => "N/A",
            ],
        ];
        $requestArray['specialInstruction'] = "N/A";
        $requestArray['copyOfReport'] = "dev@automotohr.com";
        //
        return json_encode($requestArray, JSON_PRETTY_PRINT);
    }
}
