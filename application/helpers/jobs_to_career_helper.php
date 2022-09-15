<?php defined('BASEPATH') OR exit('No direct script access allowed');

/***
 * Helper for Comunicating with Jobs2Career APIs using Curl Requests
 *
 * campaignID = Campaign id assigned by Jobs2Career
 * accountID = company_sid
 * referenceID = job_sid
 *
 */

//Curl API Call
if (!function_exists('curl_make_api_call')) {
    function curl_make_api_call($method, $url, $data = false, $environment = JTC_API_MODE)
    {
        $curl = curl_init();

        switch ($method) {
            case 'post':
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($data) {
                    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                            'Content-Type: application/json',
                            'Content-Length: ' . strlen($data))
                    );
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                }
                break;
            case 'put':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
                if ($data) {
                    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                            'Content-Type: application/json',
                            'Content-Length: ' . strlen($data))
                    );
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                }
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);

        curl_close($curl);

        //Send eMail To dev - start

        $email_body = 'Url: ' . $url . PHP_EOL;
        $email_body .= 'API Mode: ' . $environment . PHP_EOL;
        $email_body .= 'Method: ' . $method . PHP_EOL;
        $email_body .= 'Request Data: ' . $data . PHP_EOL;
        $email_body .= 'Request Result: ' . $result . PHP_EOL;

        mail(TO_EMAIL_DEV, 'Jobs2Career Debug Email', $email_body);

        //Send eMail To dev - end

        return $result;
    }
}

//Helper to generate url
if (!function_exists('jtc_generate_request_url')) {
    function jtc_generate_request_url($target_api = 'campaign', $environment = JTC_API_MODE, $task_url_segment = '/campaign/ping', $account_id = null, $start_datetime_unix = null, $end_datetime_unix = null)
    {
        if ($environment == 'live') {
            switch ($target_api) {
                case 'campaign':
                    $request_url = JTC_LIVE_CAMPAIGN_BASE_URL . $task_url_segment;
                    break;
                case 'jobs':
                    $request_url = JTC_LIVE_JOBS_BASE_URL . $task_url_segment;
                    break;
                case 'billing':
                    $request_url = JTC_LIVE_BILLING_BASE_URL . $task_url_segment;
                    break;
                case 'prediction':
                    $request_url = JTC_LIVE_PREDICTION_BASE_URL . $task_url_segment;
                    break;
            }

            if ($account_id == null) {
                $request_url .= '?api_key=' . JTC_LIVE_API_KEY;
            } else {
                $request_url .= '?accountID=' . $account_id;
            }
        } else {
            switch ($target_api) {
                case 'campaign':
                    $request_url = JTC_STAGING_CAMPAIGN_BASE_URL . $task_url_segment;
                    break;
                case 'jobs':
                    $request_url = JTC_STAGING_JOBS_BASE_URL . $task_url_segment;
                    break;
                case 'billing':
                    $request_url = JTC_STAGING_BILLING_BASE_URL . $task_url_segment;
                    break;
                case 'prediction':
                    $request_url = JTC_STAGING_PREDICTION_BASE_URL . $task_url_segment;
                    break;
            }

            if ($account_id == null) {
                $request_url .= '?api_key=' . JTC_STAGING_API_KEY;
            } else {
                $request_url .= '?accountID=' . $account_id;
            }

        }

        if ($start_datetime_unix != null && $end_datetime_unix != null) {
            $request_url .= '&startTime=' . urlencode(date('c', $start_datetime_unix));
            $request_url .= '&endTime=' . urlencode(date('c', $end_datetime_unix));
        }

        return $request_url;
    }
}


//Campaign API - Start

if (!function_exists('jtc_campaign_ping_request')) {
    function jtc_campaign_ping_request($environment = JTC_API_MODE)
    {
        $request_url = jtc_generate_request_url('campaign', $environment, '/campaign/ping');
        return curl_make_api_call('get', $request_url, false, $environment);
    }
}

if (!function_exists('jtc_campaign_details_request')) {
    function jtc_campaign_details_request($campaign_id, $environment = JTC_API_MODE)
    {
        $task_url_segment = '/campaigns/' . $campaign_id . '/details';

        $request_url = jtc_generate_request_url('campaign', $environment, $task_url_segment);

        return curl_make_api_call('get', $request_url, false, $environment);
    }
}

//Campaign API - End


//Prediction API - Start

if (!function_exists('jtc_prediction_generate_json_array')) {
    function jtc_prediction_generate_json_array($job_sid)
    {
        //Get Job details
        $CI = &get_instance();
        $CI->db->select('*');
        $CI->db->where('sid', $job_sid);
        $job_info = $CI->db->get('portal_job_listings')->result_array();

        if (!empty($job_info)) {
            $job_info = $job_info[0];
            //Get Company Info
            $company_sid = $job_info['user_sid'];
            $CI->db->select('*');
            $CI->db->where('sid', $company_sid);
            $company_info = $CI->db->get('users')->result_array();

            if (!empty($company_info)) {
                $company_info = $company_info[0];
            }

            //Get Job Category Info
            /*
            $job_category = $job_info['JobCategory'];
            $CI->db->select('*');
            $CI->db->where('sid', $job_category);
            $category_info = $CI->db->get('listing_field_list')->result_array();

            if (!empty($category_info)) {
                $category_info = $category_info[0];
            }
            */

            // Get Job Country Info
            $location_country = $job_info['Location_Country'];
            $CI->db->select('*');
            $CI->db->where('sid', $location_country);
            $country_info = $CI->db->get('countries')->result_array();

            if (!empty($country_info)) {
                $country_info = $country_info[0];
            }

            //Get Job State Info
            $location_state = $job_info['Location_State'];
            $CI->db->select('*');
            $CI->db->where('sid', $location_state);
            $state_info = $CI->db->get('states')->result_array();

            if (!empty($state_info)) {
                $state_info = $state_info[0];
            }

            $location_zipcode = $job_info['Location_ZipCode'];
            if ($location_zipcode == '') {
                $location_zipcode = $company_info['Location_ZipCode'];
            }

            $location_city = $job_info['Location_City'];


            $return_result = array();

            $return_result['applicationName'] = STORE_NAME;
            $return_result['title'] = $job_info['Title'];
            $return_result['description'] = trim(str_replace(array("\r", "\n"), '', strip_tags($job_info['JobDescription'])));
            $return_result['jobCompanyName'] = $company_info['CompanyName'];

            $locations = array();
            $location = array();
            $location['city'] = $location_city;
            $location['state'] = $state_info['state_code'];
            $location['country'] = $country_info['country_code'];
            $location['postalCode'] = $location_zipcode;

            $locations[] = $location;

            $return_result['locations'] = $locations;

            /*
            $sample = '{
                    "applicationName": "Sample Application",
                    "title": "Sample Title",
                    "description": "Sample Description",
                    "jobCompanyName": "Sample Company",
                    "locations":[
                                    {
                                        "city": "Austin",
                                        "state": "TX",
                                        "country": "US",
                                        "postalCode": "78750"
                                    }
                                ]
                    }';
            */


            return json_encode($return_result);
        } else {
            return array();
        }
    }
}

if (!function_exists('jtc_prediction_post_prediction_request')) {
    function jtc_prediction_post_prediction_request($campaign_id, $job_json_data, $environment = JTC_API_MODE, $account_id = JTC_ACCOUNT_ID)
    {
        $task_url_segment = '/campaigns/' . $campaign_id . '/jobPrediction';

        $request_url = jtc_generate_request_url('prediction', $environment, $task_url_segment, $account_id);

        return curl_make_api_call('post', $request_url, $job_json_data, $environment);
    }
}

if (!function_exists('jtc_prediction_post_prediction_full_request')) {
    function jtc_prediction_post_prediction_full_request($campaign_id, $job_json_data, $environment = JTC_API_MODE, $account_id = JTC_ACCOUNT_ID)
    {
        $task_url_segment = '/campaigns/' . $campaign_id . '/jobPredictionFull';

        $request_url = jtc_generate_request_url('prediction', $environment, $task_url_segment, $account_id);

        return curl_make_api_call('post', $request_url, $job_json_data, $environment);
    }
}

if (!function_exists('jtc_prediction_post_prediction_range_request')) {
    function jtc_prediction_post_prediction_range_request($campaign_id, $job_json_data, $environment = JTC_API_MODE, $account_id = JTC_ACCOUNT_ID)
    {
        $task_url_segment = '/campaigns/' . $campaign_id . '/jobPredictionRange';

        $request_url = jtc_generate_request_url('prediction', $environment, $task_url_segment, $account_id);

        return curl_make_api_call('post', $request_url, $job_json_data, $environment);
    }
}

if (!function_exists('jtc_prediction_post_prediction_range_full_request')) {
    function jtc_prediction_post_prediction_range_full_request($campaign_id, $job_json_data, $environment = JTC_API_MODE, $account_id = JTC_ACCOUNT_ID)
    {
        $task_url_segment = '/campaigns/' . $campaign_id . '/jobPredictionRangeFull';

        $request_url = jtc_generate_request_url('prediction', $environment, $task_url_segment, $account_id);

        return curl_make_api_call('post', $request_url, $job_json_data, $environment);
    }
}

//Prediction API - End


//Jobs API - Start

if (!function_exists('jtc_jobs_ping_request')) {
    function jtc_jobs_ping_request($environment = JTC_API_MODE)
    {
        $task_url_segment = '/jobs/ping';

        $request_url = jtc_generate_request_url('jobs', $environment, $task_url_segment);

        return curl_make_api_call('get', $request_url, false, $environment);
    }
}

if (!function_exists('jtc_jobs_fetch_list_request')) {
    function jtc_jobs_fetch_list_request($campaign_id, $account_id, $environment = JTC_API_MODE)
    {
        $task_url_segment = '/campaigns/' . $campaign_id . '/accounts/' . $account_id . '/jobs';

        $request_url = jtc_generate_request_url('jobs', $environment, $task_url_segment);

        return curl_make_api_call('get', $request_url, false, $environment);
    }
}

if (!function_exists('jtc_jobs_generate_json_array_for_post')) {
    function jtc_jobs_generate_json_array_for_post($job_sid, $start_datetime_unix, $stop_datetime_unix, $email_address, $budget, $budget_type, $cpa_price)
    {
        //Get Job details
        $CI = &get_instance();
        $CI->db->select('*');
        $CI->db->where('sid', $job_sid);
        $job_info = $CI->db->get('portal_job_listings')->result_array();

        if (!empty($job_info)) {
            $job_info = $job_info[0];
            //Get Company Info
            $company_sid = $job_info['user_sid'];
            $CI->db->select('*');
            $CI->db->where('sid', $company_sid);
            $company_info = $CI->db->get('users')->result_array();

            if (!empty($company_info)) {
                $company_info = $company_info[0];
            }

            //Get Job Category Info
            /*
            $job_category = $job_info['JobCategory'];
            $CI->db->select('*');
            $CI->db->where('sid', $job_category);
            $category_info = $CI->db->get('listing_field_list')->result_array();

            if (!empty($category_info)) {
                $category_info = $category_info[0];
            }
            */

            // Get Job Country Info
            $location_country = $job_info['Location_Country'];
            $CI->db->select('*');
            $CI->db->where('sid', $location_country);
            $country_info = $CI->db->get('countries')->result_array();

            if (!empty($country_info)) {
                $country_info = $country_info[0];
            }

            //Get Job State Info
            $location_state = $job_info['Location_State'];
            $CI->db->select('*');
            $CI->db->where('sid', $location_state);
            $state_info = $CI->db->get('states')->result_array();

            if (!empty($state_info)) {
                $state_info = $state_info[0];
            }

            $location_zipcode = $job_info['Location_ZipCode'];
            if ($location_zipcode == '') {
                $location_zipcode = $company_info['Location_ZipCode'];
            }

            $location_city = $job_info['Location_City'];


            $result_array = array();

            $job_details_array = array();
            $job_details_array['accountID'] = $company_info['sid'];
            $job_details_array['referenceID'] = $job_info['sid'];
            $job_details_array['title'] = $job_info['Title'];
            $job_details_array['description'] = trim(str_replace(array("\r", "\n"), '', strip_tags($job_info['JobDescription'])));
            $job_details_array['companyName'] = $company_info['CompanyName'];

            $address = $location_zipcode . ', ' . $location_city . ', ' . $state_info['state_code'] . ', ' . $country_info['country_code'] . ', ' . $country_info['country_name'];

            $location_array = array();
            $location_array['addressLine1'] = $address;
            $location_array['addressLine2'] = $address;
            $location_array['city'] = $location_city;
            $location_array['state'] = $state_info['state_code'];
            $location_array['country'] = $country_info['country_code'];
            $location_array['postalCode'] = $location_zipcode;
            $location_array['latitude'] = '0.0';
            $location_array['longitude'] = '0.0';

            $locations_array = array();
            $locations_array[] = $location_array;

            $job_details_array['locations'] = $locations_array;

            $job_details_array['startTime'] = date('c', $start_datetime_unix);
            $job_details_array['stopTime'] = date('c', $stop_datetime_unix);
            $job_details_array['emailAddress'] = $email_address;
            $job_details_array['hostedByJ2C'] = false;
            $job_details_array['applyURL'] = STORE_PROTOCOL . db_get_sub_domain($company_sid) . '/job_details/' . $job_sid;
            $job_details_array['requireResume'] = true;

            $result_array['jobDetail'] = $job_details_array;

            $job_budget = array();
            $job_budget['budget'] = $budget;
            $job_budget['budgetType'] = $budget_type;
            $job_budget['cpaPrice'] = $cpa_price;

            $result_array['jobBudget'] = $job_budget;

            $result_array = json_encode($result_array);

            return $result_array;
        } else {
            return array();
        }
    }
}

if (!function_exists('jtc_jobs_post_job_request')) {
    function jtc_jobs_post_job_request($campaign_id, $job_json_data, $environment = JTC_API_MODE)
    {
        $task_url_segment = '/campaigns/' . $campaign_id . '/jobs';

        $request_url = jtc_generate_request_url('jobs', $environment, $task_url_segment);

        return curl_make_api_call('post', $request_url, $job_json_data, $environment);
    }
}

if (!function_exists('jtc_jobs_fetch_status')) {
    function jtc_jobs_fetch_status($campaign_id, $reference_id, $environment = JTC_API_MODE)
    {
        $task_url_segment = '/campaigns/' . $campaign_id . '/jobs/' . $reference_id . '/status';

        $request_url = jtc_generate_request_url('jobs', $environment, $task_url_segment);

        return curl_make_api_call('get', $request_url, false, $environment);
    }
}

if (!function_exists('jtc_jobs_fetch_budget')) {
    function jtc_jobs_fetch_budget($campaign_id, $reference_id, $environment = JTC_API_MODE)
    {
        $task_url_segment = '/campaigns/' . $campaign_id . '/jobs/' . $reference_id . '/budget';

        $request_url = jtc_generate_request_url('jobs', $environment, $task_url_segment);

        return curl_make_api_call('get', $request_url, false, $environment);
    }
}


if (!function_exists('jtc_jobs_fetch_details')) {
    function jtc_jobs_fetch_details($campaign_id, $reference_id, $environment = JTC_API_MODE)
    {
        $task_url_segment = '/campaigns/' . $campaign_id . '/jobs/' . $reference_id . '/details';

        $request_url = jtc_generate_request_url('jobs', $environment, $task_url_segment);

        return curl_make_api_call('get', $request_url, false, $environment);
    }
}


if (!function_exists('jtc_jobs_fetch_complete_info')) {
    function jtc_jobs_fetch_complete_info($campaign_id, $reference_id, $environment = JTC_API_MODE)
    {
        $task_url_segment = '/campaigns/' . $campaign_id . '/jobs/' . $reference_id;

        $request_url = jtc_generate_request_url('jobs', $environment, $task_url_segment);

        return curl_make_api_call('get', $request_url, false, $environment);
    }
}

if (!function_exists('jtc_jobs_generate_json_array_for_update')) {
    function jtc_jobs_generate_json_array_for_update($job_sid, $email_address, $stop_time_unix)
    {

        //Get Job details
        $CI = &get_instance();
        $CI->db->select('*');
        $CI->db->where('sid', $job_sid);
        $job_info = $CI->db->get('portal_job_listings')->result_array();

        if (!empty($job_info)) {
            $job_info = $job_info[0];
            //Get Company Info
            $company_sid = $job_info['user_sid'];
            $CI->db->select('*');
            $CI->db->where('sid', $company_sid);
            $company_info = $CI->db->get('users')->result_array();

            if (!empty($company_info)) {
                $company_info = $company_info[0];
            }

            $job_url = STORE_PROTOCOL . db_get_sub_domain($company_sid) . '/job_details/' . $job_sid;

            $result_array = array();
            $result_array['accountID'] = $company_info['sid'];
            $result_array['referenceID'] = strval($job_sid);
            $result_array['title'] = $job_info['Title'];
            $result_array['description'] = trim(str_replace(array("\r", "\n"), '', strip_tags($job_info['JobDescription'])));
            $result_array['companyName'] = $company_info['CompanyName'];
            $result_array['emailAddress'] = $email_address;
            $result_array['stopTime'] = date('c', $stop_time_unix);
            $result_array['applyURL'] = $job_url;


            $sample_array = '{
                    "accountID": "9540",
                    "referenceID": "9540",
                    "title": "testjob",
                    "description": "java",
                    "companyName": "austin",
                    "emailAddress": "demo@j2c.com",
                    "stopTime": "2016-10-28T10:00:00+00:00",
                    "applyURL": "http://www.jobs2careers.com"
                    }';
            $result_array = json_encode($result_array);

            return $result_array;
        } else {
            return json_encode(array());
        }
    }
}


if (!function_exists('jtc_jobs_update_job_information')) {
    function jtc_jobs_update_job_information($campaign_id, $reference_id, $job_json_data, $environment = JTC_API_MODE)
    {
        $task_url_segment = '/campaigns/' . $campaign_id . '/jobs/' . $reference_id . '/details';

        $request_url = jtc_generate_request_url('jobs', $environment, $task_url_segment);

        return curl_make_api_call('put', $request_url, $job_json_data, $environment);
    }
}

if (!function_exists('jtc_jobs_update_job_status')) {
    function jtc_jobs_update_job_status($campaign_id, $reference_id, $status = 'LIVE', $environment = JTC_API_MODE)
    {
        $task_url_segment = '/campaigns/' . $campaign_id . '/jobs/' . $reference_id . '/status';

        $request_url = jtc_generate_request_url('jobs', $environment, $task_url_segment);

        $job_json_data = '';

        $status_array = array();
        $status_array['status'] = $status;

        $job_json_data = json_encode($status_array);

        return curl_make_api_call('put', $request_url, $job_json_data, $environment);
    }
}


if (!function_exists('jtc_jobs_update_job_budget')) {
    function jtc_jobs_update_job_budget($campaign_id, $reference_id, $budget = 500.00, $environment = JTC_API_MODE)
    {
        $task_url_segment = '/campaigns/' . $campaign_id . '/jobs/' . $reference_id . '/budget';

        $request_url = jtc_generate_request_url('jobs', $environment, $task_url_segment);

        $job_json_data = '';

        $budget_array = array();
        $budget_array['budget'] = $budget;

        $job_json_data = json_encode($budget_array);

        return curl_make_api_call('put', $request_url, $job_json_data, $environment);
    }
}

//Jobs API - End


//Billing API - Start

if (!function_exists('jtc_billing_ping')) {
    function jtc_billing_ping($environment = JTC_API_MODE)
    {
        $task_url_segment = '/billing/ping';

        $request_url = jtc_generate_request_url('billing', $environment, $task_url_segment);

        return curl_make_api_call('get', $request_url, false, $environment);
    }
}

if (!function_exists('jtc_billing_get_account_billing')) {
    function jtc_billing_get_account_billing($campaign_id, $account_id, $environment = JTC_API_MODE, $start_datetime_unix, $end_datetime_unix)
    {
        $task_url_segment = '/campaigns/' . $campaign_id . '/accounts/' . $account_id . '/billing';

        $request_url = jtc_generate_request_url('billing', $environment, $task_url_segment, null, $start_datetime_unix, $end_datetime_unix);

        return curl_make_api_call('get', $request_url, false, $environment);
    }
}

if (!function_exists('jtc_billing_get_campaign_billing')) {
    function jtc_billing_get_campaign_billing($campaign_id, $environment = JTC_API_MODE, $start_datetime_unix, $end_datetime_unix)
    {
        $task_url_segment = '/campaigns/' . $campaign_id . '/billing';

        $request_url = jtc_generate_request_url('billing', $environment, $task_url_segment, null, $start_datetime_unix, $end_datetime_unix);

        return curl_make_api_call('get', $request_url, false, $environment);
    }
}

if (!function_exists('jtc_billing_get_job_billing')) {
    function jtc_billing_get_job_billing($campaign_id, $reference_id, $environment = JTC_API_MODE)
    {
        $task_url_segment = '/campaigns/' . $campaign_id . '/jobs/' . $reference_id . '/billing';

        $request_url = jtc_generate_request_url('billing', $environment, $task_url_segment);

        return curl_make_api_call('get', $request_url, false, $environment);
    }
}

if (!function_exists('jtc_billing_get_campaign_report')) {
    function jtc_billing_get_campaign_report($campaign_id, $environment = JTC_API_MODE)
    {
        $task_url_segment = '/campaigns/' . $campaign_id . '/report';

        $request_url = jtc_generate_request_url('billing', $environment, $task_url_segment);

        return curl_make_api_call('get', $request_url, false, $environment);
    }
}


if (!function_exists('jtc_billing_get_account_report')) {
    function jtc_billing_get_account_report($campaign_id, $account_id, $environment = JTC_API_MODE, $start_datetime_unix, $end_datetime_unix)
    {
        $task_url_segment = '/campaigns/' . $campaign_id . '/accounts/' . $account_id . '/report';

        $request_url = jtc_generate_request_url('billing', $environment, $task_url_segment, null, $start_datetime_unix, $end_datetime_unix);

        return curl_make_api_call('get', $request_url, false, $environment);
    }
}

if (!function_exists('jtc_billing_get_job_report')) {
    function jtc_billing_get_job_report($campaign_id, $reference_id, $environment = JTC_API_MODE, $start_datetime_unix, $end_datetime_unix)
    {
        $task_url_segment = '/campaigns/' . $campaign_id . '/jobs/' . $reference_id . '/report';

        $request_url = jtc_generate_request_url('billing', $environment, $task_url_segment, null, $start_datetime_unix, $end_datetime_unix);

        return curl_make_api_call('get', $request_url, false, $environment);
    }
}

if (!function_exists('jtc_billing_get_campaign_clicks_applications')) {
    function jtc_billing_get_campaign_clicks_applications($campaign_id, $account_ids = array(), $environment = JTC_API_MODE, $start_datetime_unix, $end_datetime_unix)
    {
        $task_url_segment = '/campaigns/' . $campaign_id . '/detailsreport';

        $request_data = array();
        $request_data['startDate'] = date('c', $start_datetime_unix);
        $request_data['endDate'] = date('c', $end_datetime_unix);
        $request_data['accountIDs'] = $account_ids;

        $request_json_data = json_encode($request_data);

        $request_url = jtc_generate_request_url('billing', $environment, $task_url_segment);

        return curl_make_api_call('post', $request_url, $request_json_data, $environment);
    }
}

//Billing API - Start