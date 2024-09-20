<?php defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('curl_make_api_call')) {
    function curl_make_api_call($method, $url, $data = false, $environment = AB_API_MODE, $package_code = null)
    {
        $curl = curl_init();

        switch ($method) {
            case 'post':
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json'
                ]);

                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case 'put':
                curl_setopt($curl, CURLOPT_PUT, 1);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }

        // Optional Authentication:
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

        if ($environment == 'live') {
            curl_setopt($curl, CURLOPT_USERPWD, "AUTOHR_API:MChdJV71");
        } else {
            //curl_setopt($curl, CURLOPT_USERPWD, "AUTOHR_UAT:BD9A4F3A38"); //Automoto Testing Credentials
            curl_setopt($curl, CURLOPT_USERPWD, "AmyUAT:370034CD6D"); //AMY Testing Credentials
        }

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        
        
        if($httpCode != 200 && $method == 'post') {
            $message = '';
            if ($httpCode == 400) {
                $message = 'Bad Request';
            } else if ($httpCode == 401) {
                $message = 'Unauthorized Request';
            } else if ($httpCode == 403) {
                $message = 'Forbidden';
            } else if ($httpCode == 404) {
                $message = 'Server Not Found';
            } else if ($httpCode == 500) {
                $message = 'Internal Server Error';
            } else if ($httpCode == 502) {
                $message = 'Bad Gateway';
            } else if ($httpCode == 503) {
                $message = 'Service Unavailable';
            } else if ($httpCode == 504) {
                $message = 'Gateway Timeout';
            }

            $user_info = (array) json_decode($data);
            $user_data = (array) $user_info['orderInfo'];

            $request_from = $user_data['referenceCodes2'];
            $candidate_sid = $user_data['referenceCodes4'];
            $candidate_name = $user_data['referenceCodes5'];

            $emailTemplateBody = 'Dear Steven Warner, <br>';
            $emailTemplateBody = $emailTemplateBody . '<strong>An error has occurred while processing the background check with "Accurate" for '. $candidate_name .'.</strong> The following are the details of the request.' . '<br>';
            $emailTemplateBody = $emailTemplateBody . 'Package ID is <strong>' . $package_code . '</strong><br>';
            $emailTemplateBody = $emailTemplateBody . 'Candidate ID is <strong>' . $candidate_sid . '</strong><br>';
            $emailTemplateBody = $emailTemplateBody . 'Candidate name is <strong>' . $candidate_name . '</strong><br>';
            $emailTemplateBody = $emailTemplateBody . 'Error message is: <strong>"' . $message . '"and error code is: '.$httpCode.'</strong><br>';
            $emailTemplateBody = $emailTemplateBody . '<strong>Request</strong><br>';
            $emailTemplateBody = $emailTemplateBody . print_r($data, true);
            $emailTemplateBody = $emailTemplateBody . '<strong>Response</strong><br>';
            $emailTemplateBody = $emailTemplateBody . $result;
            $emailTemplateBody = $emailTemplateBody . '---------------------------------------------------------' . '<br>';
            $emailTemplateBody = $emailTemplateBody . '<strong>Automated Email; Please Do Not reply!</strong>' . '<br>';
            $emailTemplateBody = $emailTemplateBody . '---------------------------------------------------------' . '<br>';

            $from = FROM_EMAIL_NOTIFICATIONS;
            $to = TO_EMAIL_STEVEN;
            $subject = 'Background request failed for '. $candidate_name;
            $from_name = ucwords(STORE_DOMAIN);
            $body = $emailTemplateBody;
            //
            // sendMail($from, $to, $subject, $body, $from_name); 
            sendMail($from, 'mubashar.ahmed@egenienext.com', $subject, $body, $from_name); 
        }

        //Send eMail To dev - start

        $email_body = 'Url: ' . $url . PHP_EOL;
        $email_body .= 'API Mode: ' . $environment . PHP_EOL;
        $email_body .= 'Method: ' . $method . PHP_EOL;
        $email_body .= 'Request Data: ' . $data . PHP_EOL;
        $email_body .= 'Request Result: ' . $result . PHP_EOL;

        mail(TO_EMAIL_DEV, 'Accurate Background Debug Email', $email_body);

        //Send eMail To dev - end



        return $result;
    }
}

if (!function_exists('isJson')) {
    function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}

if (!function_exists('ab_get_package_codes')) {
    function ab_get_package_codes($environment = AB_API_MODE)
    {
        $request_url = '';

        if($environment == 'live'){
            $request_url = 'https://validus.accuratebackground.com/company/packages/get/AUTOHR';
        }else {
            $request_url = 'https://validusuat.accuratebackground.com/company/packages/get/AMY';
            //$request_url = 'https://validusuat.accuratebackground.com/company/packages/get/AUTOHR';
        }

        $response_data = curl_make_api_call('get', $request_url, false, $environment);

        return json_decode($response_data, true);
    }
}

if(!function_exists('ab_post_order')){
    function ab_post_order($package_code, $request_data, $environment = AB_API_MODE){
        $request_url = '';

        if($environment == 'live'){
            $request_url = 'https://validus.accuratebackground.com/order/create/AUTOHR/' . $package_code;
        }else{
            $request_url = 'https://validusuat.accuratebackground.com/order/create/AMY/' . $package_code;
            //$request_url = 'https://validusuat.accuratebackground.com/order/create/AUTOHR/' . $package_code;
        }

        return curl_make_api_call('post', $request_url, $request_data, $environment, $package_code);
    }
}

if(!function_exists('ab_get_order_status')){
    function ab_get_order_status($package_code, $environment = AB_API_MODE){
        if($environment == 'live'){
            $request_url = 'https://validus.accuratebackground.com/order/get/' . $package_code;
        } else {
            $request_url = 'https://validusuat.accuratebackground.com/order/get/' . $package_code;
        }

        return curl_make_api_call('get', $request_url, false, $environment);
    }
}

if(!function_exists('ab_build_request_body')){
    function ab_build_request_body($company_info, $employer_info, $subdomain, $user_info){
        //getting country and state detials
        $stateCountry = db_get_state_name($employer_info['Location_State']);

        $return_result = array();

        //Start Header Info
        $header_info = array();

        $requester = array();
        $requester['email'] = (AB_API_MODE == 'live' ? 'steven@automohr.com' : 'integrations@accuratebackground.com');

        $header_info['requester'] = $requester;
        $header_info['originCode'] = 'ats';
        //End Header Info

        //Start OrderInfo
        $order_info = array();
        $order_info['referenceCodes1'] = substr($company_info['CompanyName'], 24);
        $order_info['referenceCodes2'] = $employer_info['first_name'];
        $order_info['referenceCodes3'] = $employer_info['last_name'];
        $order_info['referenceCodes4'] = substr($subdomain, 24);;
        $order_info['referenceCodes5'] = '';

        $copy_of_report = array();
        $copy_of_report['requestCopy'] = true;
        $copy_of_report['byEmail'] = true;

        $position = array();

        $address = array();
        $address['countryCode'] = $stateCountry['country_code'];
        $address['region'] = $stateCountry['state_code'];
        $address['city'] = $employer_info['Location_City'];

        $position['address'] = $address;

        $order_info['position'] = $position;
        //End Order Info

        //Start Candidate
        $candidate = array();
        $name = array();
        $name['firstname'] = $user_info['first_name'];
        $name['lastname'] = $user_info['first_name'];
        $name['middlename'] = '';

        $candidate['name'] = $name;

        $aka = array();

        $aka_a = array();
        $aka_a['firstname'] = '';
        $aka_a['lastname'] = '';
        $aka_a['middlename'] = '';
        $aka[] = $aka_a;

        $aka_b = array();
        $aka_b['firstname'] = '';
        $aka_b['lastname'] = '';
        $aka_b['middlename'] = '';
        $aka[] = $aka_b;

        $aka_c = array();
        $aka_c['firstname'] = '';
        $aka_c['lastname'] = '';
        $aka_c['middlename'] = '';
        $aka[] = $aka_c;

        $candidate['aka'] = $aka;

        $candidate['dateOfBirth'] = date('Y-m-d', strtotime($user_info['date_of_birth']));
        $candidate['ssn'] = '';

        $government_id = array();
        $government_id['countryCode'] = '';
        $government_id['type'] = 'SIN';
        $government_id['number'] = '';

        $candidate['governmentId'] = $government_id;

        $contact = array();
        $contact['email'] = '';
        $contact['phone'] = '';

        $candidate['contact'] = $contact;

        $address = array();
        $address['street'] = '';
        $address['street2'] = '';
        $address['city'] = '';
        $address['region'] = '';
        $address['country'] = '';
        $address['postalCode'] = '';

        $candidate['address'] = $address;

        //Put everything together
        $return_result['headerInfo'] = $header_info;
        $return_result['orderInfo'] = $order_info;
        $return_result['candidate'] = $candidate;


        return $return_result;
    }
}

if(!function_exists('ab_get_orders_list')){
    function ab_get_orders_list($start_unix_date, $end_unix_date, $environment = AB_API_MODE){
        $request_url = '';

        if($environment == 'live'){
            $request_url = 'https://validus.accuratebackground.com/order/list/AUTOHR/?start_datetime=' . $start_unix_date . '&end_datetime=' . $end_unix_date;
        }else{
            $request_url = 'https://validusuat.accuratebackground.com/order/list/AMY/?start_datetime=' . $start_unix_date . '&end_datetime=' . $end_unix_date;
            //$request_url = 'https://validusuat.accuratebackground.com/order/list/AUTOHR/?start_datetime=' . $start_unix_date . '&end_datetime=' . $end_unix_date;
        }

        return curl_make_api_call('get', $request_url, false, $environment);
    }
}

if(!function_exists('ab_get_report')){
    function ab_get_report($search_id, $format = 'pdf', $environment = AB_API_MODE){
        $request_url = '';

        if($environment == 'live'){
            $request_url = 'https://validus.accuratebackground.com/reports/download_result_report?company_code=AUTOHR&search_id=' . $search_id . '&format=' . $format;
        }else{
            $request_url = 'https://validusuat.accuratebackground.com/reports/download_result_report?company_code=AMY&search_id=' . $search_id . '&format=' . $format;
            //$request_url = 'https://validusuat.accuratebackground.com/reports/download_result_report?company_code=AUTOHR&search_id=' . $search_id . '&format=' . $format;
        }


        $response = curl_make_api_call('post', $request_url, false, $environment);

        return $response;
    }
}

