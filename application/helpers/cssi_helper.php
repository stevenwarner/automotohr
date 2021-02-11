<?php defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('cssi_make_api_get_request')) {
    function cssi_make_api_get_request($request_url, $request_data = null, $access_token = null)
    {
        $options = array(
            CURLOPT_RETURNTRANSFER      => true,            // return web page
            CURLOPT_HEADER              => false,           // don't return headers
            CURLOPT_FOLLOWLOCATION      => true,            // follow redirects
            CURLOPT_ENCODING            => "",              // handle all encodings
            CURLOPT_USERAGENT           => STORE_NAME,    // who am i
            CURLOPT_AUTOREFERER         => true,            // set referer on redirect
            CURLOPT_CONNECTTIMEOUT      => 120,             // timeout on connect
            CURLOPT_TIMEOUT             => 120,             // timeout on response
            CURLOPT_MAXREDIRS           => 10,              // stop after 10 redirects
        );


        if ($request_data != null) {
            $request_url = sprintf("%s?%s", $request_url, http_build_query($request_data));
        }

        $ch = curl_init($request_url);
        curl_setopt_array($ch, $options);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Get Fix for this on Live Environment

        if ($access_token != null) {
            $headers = array();
            $headers[] = 'Authorization: Bearer { ' . $access_token . ' }';

            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }


        $content = curl_exec($ch);
        $err = curl_errno($ch);
        $errmsg = curl_error($ch);
        $header = curl_getinfo($ch);

        curl_close($ch);

        $header['errno'] = $err;
        $header['errmsg'] = $errmsg;

        if($content != '') {
            $header['response'] = json_decode($content, true);
        } else {
            $header['response'] = array();
        }

        return $header;
    }
}

if (!function_exists('cssi_make_api_post_request')) {
    function cssi_make_api_post_request($request_url, $request_data, $access_token)
    {
        $options = array(
            CURLOPT_RETURNTRANSFER      => true,            // return web page
            CURLOPT_HEADER              => false,           // don't return headers
            CURLOPT_FOLLOWLOCATION      => true,            // follow redirects
            CURLOPT_ENCODING            => "",              // handle all encodings
            CURLOPT_USERAGENT           => STORE_NAME,    // who am i
            CURLOPT_AUTOREFERER         => true,            // set referer on redirect
            CURLOPT_CONNECTTIMEOUT      => 120,             // timeout on connect
            CURLOPT_TIMEOUT             => 120,             // timeout on response
            CURLOPT_MAXREDIRS           => 10,              // stop after 10 redirects
        );




        $ch = curl_init($request_url);
        curl_setopt_array($ch, $options);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Get Fix for this on Live Environment

        if ($access_token != null) {
            $headers = array();
            $headers[] = 'Authorization: Bearer { ' . $access_token . ' }';

            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        curl_setopt($ch, CURLOPT_POST, 1);
        if ($request_data != false) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $request_data);
        }

        $content = curl_exec($ch);
        $err = curl_errno($ch);
        $errmsg = curl_error($ch);
        $header = curl_getinfo($ch);

        curl_close($ch);

        $header['errno'] = $err;
        $header['errmsg'] = $errmsg;

        if($content != '') {
            $header['response'] = json_decode($content, true);
        } else {
            $header['response'] = array();
        }
        return $header;
    }
}

if (!function_exists('cssi_make_api_request')) {
    function cssi_make_api_request($method, $url, $data = null, $access_token = null)
    {
        switch ($method) {
            case 'post':
                $response = cssi_make_api_post_request($url, $data, $access_token);
                break;
            case 'get':
                $response = cssi_make_api_get_request($url, $data, $access_token);
                break;
            case 'put':
                break;
            default:

        }

        //Send eMail To dev - start

        $email_body = 'Url: ' . $url . PHP_EOL;
        $email_body .= 'Access Token: ' . $access_token . PHP_EOL;
        $email_body .= 'Method: ' . $method . PHP_EOL;
        $email_body .= 'Request Data: ' . json_encode($data) . PHP_EOL;
        $email_body .= 'Request Result: ' . json_encode($response) . PHP_EOL;

        mail(FROM_EMAIL_DEV, 'CSSI Debug Email', $email_body);

        //Send eMail To dev - end

        return $response;
    }
}

if (!function_exists('cssi_get_access_token')) {
    function cssi_get_access_token($login_name, $password)
    {
        $request_data = array();
        $request_data['LoginName'] = $login_name;
        $request_data['Password'] = $password;

        $request_url = 'https://api.eyeforsecurity.com/api/v1/user/login';

        $request_response = cssi_make_api_request('post', $request_url, $request_data, null);

        $response = $request_response['response'];

        $access_token = '';

        if(!empty($response)){
            $access_token = $response['access_token'];
        }

        return $access_token;
    }
}

if (!function_exists('cssi_get_user_account_information')) {
    function cssi_get_user_account_information($access_token)
    {
        $api_url = 'https://api.eyeforsecurity.com/api/v1/user/data';

        $response = cssi_make_api_request('get', $api_url, false, $access_token);

        return $response;
    }
}


/**
 * array(
'Email' => 'test@gmail.com',
'Password' => 'tester123',
'ConfirmPassword' => 'tester123',
'LoginName' => 'test@gmail.com',
'FirstName' => 'Jack',
'LastName' => 'Black',
'Company' => 'Acme Co.',
'ContactPhone' => '3213121233',
'Address' => '123 street',
'City' => 'orlando',
'State' => 'fl',
'ZipCode' => '32825',
'CorporateAccount' => '0',
'SaveCreditCard' => '1',
'NameOnCard' => 'Some Dude',
'CardType' => 'Discover',
'CardNumber' => '6011000000000012',
'CardExpiration' => '04/18',
'CardCCV' => '123'
)
 */
if(!function_exists('cssi_register_new_user_request')){
    function cssi_register_new_user_request($user_information = array()){
        $request_url = 'https://api.eyeforsecurity.com/api/v1/user/signup';

        $required_keys = array();
        $required_keys[] = 'Email';
        $required_keys[] = 'Password';
        $required_keys[] = 'ConfirmPassword';
        $required_keys[] = 'LoginName';
        $required_keys[] = 'FirstName';
        $required_keys[] = 'LastName';
        $required_keys[] = 'Company';
        $required_keys[] = 'ContactPhone';
        $required_keys[] = 'Address';
        $required_keys[] = 'City';
        $required_keys[] = 'State';
        $required_keys[] = 'ZipCode';
        $required_keys[] = 'CorporateAccount';
        $required_keys[] = 'SaveCreditCard';
        $required_keys[] = 'NameOnCard';
        $required_keys[] = 'CardType';
        $required_keys[] = 'CardNumber';
        $required_keys[] = 'CardExpiration';
        $required_keys[] = 'CardCCV';

        $provided_array_check = false;

        $request_response = array();

        if(!empty($user_information)){
            foreach($required_keys as $key){
                if(isset($user_information[$key])){
                    $provided_array_check = true;
                } else {
                    $provided_array_check = false;
                    break;
                }
            }

            if($provided_array_check == true){
                $request_response = cssi_make_api_post_request($request_url, $user_information, null);
            }
        }

        return $request_response;
    }
}

if(!function_exists('cssi_register_new_user')){
    function cssi_register_new_user(){

        $user_info = array(
            'Email' => FROM_EMAIL_DEV,
            'Password' => 'dev@automotohr',
            'ConfirmPassword' => 'dev@automotohr',
            'LoginName' => FROM_EMAIL_DEV,
            'FirstName' => 'Software',
            'LastName' => 'Engineer',
            'Company' => 'egenie next',
            'ContactPhone' => '0123456789',
            'Address' => 'Address goes here',
            'City' => 'orlando',
            'State' => 'fl',
            'ZipCode' => '32825',
            'CorporateAccount' => '1',
            'SaveCreditCard' => '1',
            'NameOnCard' => 'Software Engineer',
            'CardType' => 'Discover',
            'CardNumber' => '6011000000000012',
            'CardExpiration' => '04/18',
            'CardCCV' => '123'
        );

        $request_response = cssi_register_new_user_request($user_info);

        return $request_response;

    }
}

/**
 * array(
'FirstName' => 'Benson',
'LastName' => 'Vardos',
'SSN' => ' 123456789 ',
'DateOfBirth' => '06/06/1960',
'ProductId' => '2',
'UseSavedCreditCard' => '1'
)
 */
if(!function_exists('cssi_create_background_check_saved_credit_card')){
    function cssi_create_background_check_saved_credit_card($access_token, $first_name, $last_name, $ssn, $date_of_birth, $product_id){
        $candidate_details = array(
            'FirstName' => $first_name,
            'LastName' => $last_name,
            'SSN' => $ssn,
            'DateOfBirth' => $date_of_birth,
            'ProductId' => $product_id,
            'UseSavedCreditCard' => '1'

            //'NameOnCard' => 'Some Dude',
            //'CardType' => 'Discover',
            //'CardNumber' => '6011000000000012',
            //'CardExpiration' => '04/18',
            //'CardCCV' => '123'
        );

        //CardType : Discover
        //CardNumber : 6011000000000012
        //CardExpiration : 04 / 18
        //CardCCV : 123

        print_r($candidate_details);

        echo '<br />';

        $request_url = 'https://api.eyeforsecurity.com/api/v1/background-check/create';

        $request_response = cssi_make_api_post_request($request_url, $candidate_details, $access_token);

        return $request_response;
    }
}