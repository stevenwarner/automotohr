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
        echo "<pre>";
        print_r(json_decode($resp['Result'], true));
        die();
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


// Send curl request
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