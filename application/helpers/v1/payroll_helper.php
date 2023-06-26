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
        // network issue
        if ($info['http_code'] == '0') {
            return [
                'errors' => [
                    [
                        'message' => 'Network issue: failed to reach payroll provider.'
                    ]
                ]
            ];
        }
        // Check for auth error
        if ($info['http_code'] == 401) {
            return [
                'errors' => [
                    'auth' => [
                        $info['http_code']
                    ]
                ]
            ];
        }
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
     * @param array $company
     */
    function updateToken($token, $where, $company)
    {
        //
        $CI = &get_instance();
        //
        $CI->load->model('Payroll_model', 'pm');
        //
        $CI->pm->updateToken([
            'access_token' => $token['access_token'],
            'refresh_token' => $token['refresh_token'],
            'old_access_token' => $company['access_token'],
            'old_refresh_token' => $company['refresh_token']
        ], $where);
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
        $body = [];
        $body['client_id'] = GUSTO_CLIENT_ID;
        $body['client_secret'] = GUSTO_CLIENT_SECRET;
        $body['redirect_uri'] = GUSTO_CLIENT_REDIRECT_URL;
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
        } elseif (isset($response['errors']['invalid_grant'])) {
            $errors['errors'] = array_merge($errors['errors'], $response['errors']['invalid_grant']);
        } elseif (isset($response['errors'])) {
            foreach ($response['errors'] as $err) {
                //
                if (isset($err[0])) {
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
     * @return string
     */
    function getUrl($index, $key = '', $key1 = '')
    {
        // set default URL
        $urls = [];
        $urls['me'] = 'v1/me';
        $urls['refreshToken'] = 'oauth/token?' . ($key);

        // company URLs
        $urls['createPartnerCompany'] = "v1/partner_managed_companies";

        return (GUSTO_MODE === 'test' ? GUSTO_URL_TEST : GUSTO_URL) . $urls[$index];
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
                    'Authorization: Token ' . (GUSTO_KEY_TEST) . '',
                    'Content-Type: application/json',
                    'X-Gusto-API-Version: 2023-04-01'
                )
            ]
        );
    }
}
