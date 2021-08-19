<?php
/**
 * 
 * 
 */
if(!function_exists('PayrollAuth')){
    function PayrollAuth($company){
        //
        return MakeCall(
            PayrollURL('Me'),[
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer '.($company['access_token']).''
                )
            ] 
        );
    }
}

//
if(!function_exists('CreatePartnerCompany')){
    function CreatePartnerCompany($request){
        //
        return MakeCall(
            PayrollURL('CreatePartnerCompany'),[
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Token '.(GUSTO_KEY_TEST).'',
                    'Content-Type: application/json'
                )
            ] 
        );
    }
}

//
if(!function_exists('AddEmployeeToCompany')){
    function AddEmployeeToCompany($request, $company){
        //
        return MakeCall(
            PayrollURL('AddEmployeeToCompany', $company['gusto_company_uid']),[
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer '.($company['access_token']).'',
                    'Content-Type: application/json'
                )
            ] 
        );
    }
}

//
if(!function_exists('RefreshToken')){
    function RefreshToken($request){
        //
        $key = 'client_id='.(GUSTO_CLIENT_ID).'&';
        $key .= 'client_secret='.(GUSTO_CLIENT_SECRET).'&';
        $key .= 'redirect_uri='.(GUSTO_CLIENT_REDIRECT_URL).'&';
        $key .= 'refresh_token='.($request['refresh_token']).'&';
        $key .= 'grant_type=refresh_token';
        //
        return MakeCall(
            PayrollURL('RefreshToken', $key),[
                CURLOPT_CUSTOMREQUEST => 'POST'
            ] 
        );
    }
}

//
if(!function_exists('MakeCall')){
    function MakeCall($url, $options = []){
        //
        $curl = curl_init();
        //
        curl_setopt_array(
            $curl, 
            array_merge(
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
                ],
                $options
            )
        );
        //
        $response = curl_exec($curl);
        //
        $info = curl_getinfo($curl);
        //
        curl_close($curl);
        _e($options, true);
        _e($url, true);
        _e($response, true);
        _e($info, true, true);

        // Check for aut error
        if($info['http_code'] != 200){
            return [
                'errors' => [
                    'auth' => [
                        $info['http_code']
                    ] 
                ]
            ];   
        }
        //
        $response = json_decode($response, true);
        //
        return $response; 
    }
}


//
if(!function_exists('PayrollURL')){
    function PayrollURL($index, $key = 0){
        //
        $urls = [];
        $urls['Me'] = 'v1/me';
        $urls['CreatePartnerCompany'] = 'v1/partner_managed_companies';
        $urls['AddEmployeeToCompany'] = 'v1/companies/'.($key).'/employees';
        $urls['RefreshToken'] = 'oauth/token?'.($key);
        //
        return (GUSTO_MODE === 'test' ? GUSTO_URL_TEST : GUSTO_URL).$urls[$index];
    }
}