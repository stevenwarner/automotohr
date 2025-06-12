<?php

defined('BASEPATH') or exit('No direct script access allowed');

//
if (!function_exists('getUserNameBySID')) {
    function getUserNameBySID($sid, $remake = true)
    {
        $user_info = db_get_employee_profile($sid);

        if (!empty($user_info)) {
            return $remake ? remakeEmployeeName([
                'first_name' => $user_info[0]['first_name'],
                'last_name' => $user_info[0]['last_name'],
                'access_level' => $user_info[0]['access_level'],
                'timezone' => isset($user_info[0]['timezone']) ? $user_info[0]['timezone'] : '',
                'access_level_plus' => $user_info[0]['access_level_plus'],
                'is_executive_admin' => $user_info[0]['is_executive_admin'],
                'pay_plan_flag' => $user_info[0]['pay_plan_flag'],
                'job_title' => $user_info[0]['job_title'],
            ]) : $user_info;
        } else {
            return '';
        }
    }
}

if (!function_exists('getEmployeeOnlyNameBySID')) {
    function getEmployeeOnlyNameBySID($sid)
    {
        $employee_info = db_get_employee_profile($sid);
        //
        if ($employee_info) {
            return $employee_info[0]["first_name"] . " " . $employee_info[0]["middle_name"] . " " . $employee_info[0]["last_name"];
        } else {
            return "";
        }
    }
}

if (!function_exists('getEmployeeBasicInfo')) {
    function getEmployeeBasicInfo($sid)
    {
        $CI = &get_instance();
        $CI->db->select('first_name, last_name, email, access_level, job_title, is_executive_admin, access_level_plus, pay_plan_flag, timezone, middle_name, department_sid, team_sid, PhoneNumber, parent_sid, profile_picture');
        $CI->db->where('sid', $sid);
        $userInfo = $CI->db->get('users')->row_array();
        //
        $basicInfo = [];
        //
        if ($userInfo) {
            //
            $userBasicInfo = [
                'first_name' => $userInfo['first_name'],
                'last_name' => $userInfo['last_name'],
                'access_level' => $userInfo['access_level'],
                'timezone' => isset($userInfo['timezone']) ? $userInfo['timezone'] : '',
                'access_level_plus' => $userInfo['access_level_plus'],
                'is_executive_admin' => $userInfo['is_executive_admin'],
                'pay_plan_flag' => $userInfo['pay_plan_flag'],
                'job_title' => $userInfo['job_title'],
            ];
            //
            $basicInfo['picture'] = $userInfo["profile_picture"];
            $basicInfo['pictureURL'] = getImageURL($userInfo["profile_picture"]);
            $basicInfo['phone'] = $userInfo["PhoneNumber"];
            $basicInfo["name"] = remakeEmployeeName($userBasicInfo, true, true);
            $basicInfo["designation"] = remakeEmployeeName($userBasicInfo, false);
            $basicInfo["employeeName"] = $basicInfo["name"] . ' ' . $basicInfo["designation"];
            $basicInfo["departmentName"] = getDepartmentNameBySID($userInfo['department_sid']);
            $basicInfo["teamName"] = getTeamNameBySID($userInfo['team_sid']);
            $basicInfo["companyName"] = getCompanyNameBySid($userInfo['parent_sid']);
            $basicInfo["departmentId"] = $userInfo['department_sid'];
            $basicInfo["teamId"] = $userInfo['team_sid'];
            $basicInfo["companyId"] = $userInfo['parent_sid'];
            $basicInfo["email"] = $userInfo['email'];
            $basicInfo["job_title"] = $userInfo['job_title'];
            $basicInfo['AHREmployeeID'] = 'AHR-' . $sid;
        }

        //
        return $basicInfo;
    }
}


if (!function_exists('getApplicantNameBySID')) {
    function getApplicantNameBySID($sid, $remake = true)
    {
        $applicant_info = db_get_applicant_profile($sid);
        //
        return $applicant_info["first_name"] . " " . $applicant_info["last_name"];
    }
}

if (!function_exists('get_employee_profile_info')) {
    function get_employee_profile_info($emp_id)
    {
        $CI = &get_instance();
        $CI->db->select('first_name, last_name, email, timezone, access_level, job_title, is_executive_admin, access_level_plus, pay_plan_flag, profile_picture, PhoneNumber');
        $CI->db->where('sid', $emp_id);
        return $CI->db->get('users')->row_array();
    }
}

if (!function_exists('getCompanyNameBySid')) {
    function getCompanyNameBySid($company_sid)
    {
        $company_name = '';
        if (!empty($company_sid)) {

            $CI = &get_instance();
            $CI->db->select('CompanyName');
            $CI->db->where('sid', $company_sid);
            //
            $company_info = $CI->db->get('users')->row_array();

            if (!empty($company_info)) {
                $company_name = $company_info['CompanyName'];
            }
        }

        return $company_name;
    }
}

if (!function_exists('getDefaultApproverName')) {
    function getDefaultApproverName($company_sid, $email)
    {
        //
        $CI = &get_instance();
        $CI->db->select('contact_name');
        $CI->db->where('company_sid', $company_sid);
        $CI->db->where('email', $email);
        $CI->db->where('notifications_type', "default_approvers");
        $CI->db->where('status', "active");
        $record_obj = $CI->db->get('notifications_emails_management');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();
        //
        $return_data = array();
        //
        if (!empty($record_arr)) {
            $return_data = ucwords($record_arr["contact_name"]);
        }

        return $return_data;
    }
}

if (!function_exists('getCompanyLogoBySid')) {
    function getCompanyLogoBySid($company_sid)
    {
        $company_name = '';
        if (!empty($company_sid)) {

            $CI = &get_instance();
            $CI->db->select('Logo');
            $CI->db->where('sid', $company_sid);
            //
            $company_info = $CI->db->get('users')->row_array();

            if (!empty($company_info)) {
                $company_name = $company_info['Logo'];
            }
        }

        return $company_name;
    }
}

if (!function_exists('getEmployeeJobfairApplicant')) {
    function getEmployeeJobfairApplicant($company_sid, $employee_sid, $arrayinfo = null, $today_start = NULL, $today_end = NULL, $approval_status = NULL, $count = "no", $fair_type = "all", $applicant_filters = array())
    {
        $CI = &get_instance();

        $CI->db->select("page_url");
        $CI->db->where("company_sid", $company_sid);
        //
        if ($fair_type != "all") {
            $CI->db->where("page_url", $fair_type);
        }
        //
        $CI->db->where("FIND_IN_SET({$employee_sid}, visibility_employees) > 0", false, false);
        //
        $assign_custom_job_fair = $CI->db->get('job_fairs_forms')->result_array();
        //
        $CI->db->select("page_url");
        $CI->db->where("company_sid", $company_sid);
        //
        if ($fair_type != "all") {
            $CI->db->where("page_url", $fair_type);
        }
        //
        $CI->db->where("FIND_IN_SET({$employee_sid}, visibility_employees) > 0", false, false);
        //
        $assign_default_job_fair = $CI->db->get('job_fairs_recruitment')->result_array();

        $assign_job_fair = array_merge($assign_default_job_fair, $assign_custom_job_fair);

        if (!empty($assign_job_fair)) {
            $page_urls = array();
            foreach ($assign_job_fair as $key => $job_fair) {
                array_push($page_urls, $job_fair['page_url']);
            }

            if ($count == "no") {
                $CI->db->select('sid');
            }
            $CI->db->where('applicant_type', 'Job Fair');
            $CI->db->where_in('job_fair_key', $page_urls);

            if (!empty($arrayinfo)) {
                $CI->db->where_not_in('sid', $arrayinfo);
            }

            if (!empty($applicant_filters)) {
                $CI->db->where('status_sid', $applicant_filters['status_sid']);
            }

            if ($today_start != NULL && $today_end != NULL) {
                $CI->db->where('portal_applicant_jobs_list.date_applied >= ', $today_start);
                $CI->db->where('portal_applicant_jobs_list.date_applied <= ', $today_end);
            }

            if ($approval_status != NULL) {
                $CI->db->where('portal_applicant_jobs_list.approval_status', $approval_status);
            }

            $CI->db->from('portal_applicant_jobs_list');

            if ($count == "no") {
                return $CI->db->get()->result_array();
            } else {
                return $CI->db->count_all_results();
            }
        } else {
            return $count != 'no' ? 0 : array();
        }
    }
}

if (!function_exists('getCompanyName')) {
    function getCompanyName($user_sid, $user_type)
    {
        $company_name = '';
        $company_sid = '';
        if ($user_type == 'applicant') {
            //
            $CI = &get_instance();
            $CI->db->select('employer_sid');
            $CI->db->where('sid', $user_sid);
            //
            $user_info = $CI->db->get('portal_job_applications')->row_array();
            $company_sid = $user_info['employer_sid'];
        } else {
            $CI = &get_instance();
            $CI->db->select('parent_sid');
            $CI->db->where('sid', $user_sid);
            //
            $user_info = $CI->db->get('users')->row_array();
            $company_sid = $user_info['parent_sid'];
        }

        if (!empty($company_sid)) {

            $CI = &get_instance();
            $CI->db->select('CompanyName');
            $CI->db->where('sid', $company_sid);
            //
            $company_info = $CI->db->get('users')->row_array();

            if (!empty($company_info)) {
                $company_name = $company_info['CompanyName'];
            }
        }

        return $company_name;
    }
}

if (!function_exists('clean_domain')) {

    function clean_domain($string)
    {
        $string = trim($string, '/');
        if (!preg_match('#^http(s)?://#', $string)) {
            $string = STORE_PROTOCOL . $string;
        }
        $urlParts = parse_url($string);
        $domain = preg_replace('/^www\./', '', $urlParts['host']);
        return $domain;
    }
}

if (!function_exists('unique_multi_dimension_array')) {

    function unique_multi_dimension_array($array, $key)
    {
        $temp_array = array();
        $i = 0;
        $key_array = array();

        foreach ($array as $val) {
            if (!in_array($val[$key], $key_array)) {
                $key_array[$i] = $val[$key];
                $temp_array[$i] = $val;
            }
            $i++;
        }
        return $temp_array;
    }
}

if (!function_exists('getUserIP')) {

    function getUserIP()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        // $ipaddress = '137.0.0.1 127.0.0.1';
        return strpos($ipaddress, ',') !== FALSE ? explode(',', $ipaddress)[0] : $ipaddress;
    }
}

if (!function_exists('clean')) {

    function clean($string)
    {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
        return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
    }
}

if (!function_exists('generateRandomString')) {

    function generateRandomString($length)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}

if (!function_exists('random_key')) {

    function random_key($str_length = 24)
    {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $bytes = openssl_random_pseudo_bytes(3 * $str_length / 4 + 1);
        $repl = unpack('C2', $bytes);
        $first = $chars[$repl[1] % 62];
        $second = $chars[$repl[2] % 62];
        return strtr(substr(base64_encode($bytes), 0, $str_length), '+/', "$first$second");
    }
}

if (!function_exists('message_header_footer')) {

    function message_header_footer($compnay_id, $company_Name)
    {
        $CI = &get_instance();
        $CI->db->select('sub_domain');
        $CI->db->where('user_sid', $compnay_id);
        $CI->db->from('portal_employer');
        $result = $CI->db->get()->result_array();
        $data['sub_domain'] = $domain_name = $result[0]['sub_domain'];
        $data['header'] = '<div class="content" style="font-size: 100%; line-height: 1.6em; display: block; max-width: 1000px; margin: 0 auto; padding: 0; position:relative"><div style="width:100%; float:left; padding:5px 20px; text-align:center; box-sizing:border-box; background-color:#0000FF;"><h2 style="color:#fff;">' . $company_Name . '</h2></div>  <div class="body-content" style="width:100%; float:left; padding:20px 0; box-sizing:padding-box;">';
        $data['footer'] = '</div><div class="footer" style="width:100%; float:left; background-color:#0000FF; padding:20px 30px; box-sizing:border-box;"><div style="float:left; width:100%; "><p style="color:#fff; float:left; text-align:center; font-style:italic; line-height:normal; font-family: "Open Sans", sans-serif; font-weight:600; font-size:14px;"><a style="color:#fff; text-decoration:none;" href="' . STORE_PROTOCOL . $domain_name . '">&copy; ' . date('Y') . ' ' . $domain_name . '. All Rights Reserved.</a></p></div></div></div>';
        return $data;
    }
}
if (!function_exists('render')) {

    function render($the_view = NULL, $template = 'master')
    {
        $CI = &get_instance();

        if ($template == 'json' || $CI->input->is_ajax_request()) {
            header('Content-Type: application/json');
            echo json_encode($CI->data);
        } else {
            $CI->data['the_view_content'] = (is_null($the_view)) ? '' : $CI->load->view($the_view, $CI->data, TRUE);
            $CI->load->view('templates/' . $template . '_view', $CI->data);
        }
    }
}

if (!function_exists('sendMail')) {

    function sendMail($from, $to, $subject, $body, $fromName = NULL, $replyTo = NULL)
    {
        if (is_staging_server())
            return true;
        require_once(APPPATH . 'libraries/phpmailer/PHPMailerAutoload.php');
        $mail = new PHPMailer;
        $mail->From = $from;
        $mail->FromName = $fromName;

        if ($replyTo == NULL) {
            $mail->addReplyTo($from);
        } else {
            $mail->addReplyTo($replyTo);
        }

        //

        $mail->addAddress($to);
        $mail->CharSet = 'UTF-8';
        // mailAWSSES($mail, $to);
        //$mail->addBCC('prosaifhasi@gmail.com');
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->send();
    }
}


if (!function_exists('sendMailMultipleRecipients')) {

    function sendMailMultipleRecipients($from, $to = array(), $subject, $body, $fromName = NULL, $replyTo = NULL)
    {
        if (is_staging_server())
            return true;
        require_once(APPPATH . 'libraries/phpmailer/PHPMailerAutoload.php');

        $mail = new PHPMailer;
        $mail->From = $from;
        $mail->FromName = $fromName;

        if ($replyTo == NULL) {
            $mail->addReplyTo($from);
        } else {
            $mail->addReplyTo($replyTo);
        }

        foreach ($to as $address) {
            $mail->addAddress($address);
        }

        //

        $mail->CharSet = 'UTF-8';
        //$mail->addBCC('prosaifhasi@gmail.com');
        $mail->isHTML(true);
        // mailAWSSES($mail, $to);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->send();
    }
}

if (!function_exists('sendMailWithCC')) {

    function sendMailWithCC($from, $to, $cc, $subject, $body, $fromName = NULL, $replyTo = NULL)
    {
        if (is_staging_server())
            return true;
        require_once(APPPATH . 'libraries/phpmailer/PHPMailerAutoload.php');
        $mail = new PHPMailer;
        $mail->From = $from;
        $mail->FromName = $fromName;

        if ($replyTo == NULL) {
            $mail->addReplyTo($from);
        } else {
            $mail->addReplyTo($replyTo);
        }

        //

        $mail->addAddress($to);
        $mail->addCC($cc);
        $mail->CharSet = 'UTF-8';
        // mailAWSSES($mail, $to);
        //$mail->addBCC('prosaifhasi@gmail.com');
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->send();
    }
}
if (!function_exists('sendMailWithAttachment')) {

    function sendMailWithAttachment($from, $to, $subject, $body, $fromName = NULL, $file, $replyTo = NULL, $multiple = false)
    {
        if (is_staging_server())
            return true;
        require_once(APPPATH . 'libraries/phpmailer/PHPMailerAutoload.php');
        $mail = new PHPMailer;
        $mail->From = $from;
        $mail->FromName = $fromName;

        if ($replyTo == NULL) {
            $mail->addReplyTo($from);
        } else {
            $mail->addReplyTo($replyTo);
        }


        $mail->addAddress($to);
        $mail->CharSet = 'UTF-8';
        //
        // mailAWSSES($mail, $to);
        //$mail->addBCC('prosaifhasi@gmail.com');
        $mail->isHTML(true);
        //
        if (is_array($file) && sizeof($file) && isset($file[0]['file']) && $multiple == false) {
            foreach ($file as $k => $v)
                $mail->addStringAttachment(getFileData($v['file']), $v['name']);
        } else {
            if ($multiple) {
                foreach ($file['tmp_name'] as $key => $fileName) {
                    if (empty($_FILES['message_attachment']['name'][$key]) || $_FILES['message_attachment']['size'][$key] == 0) {
                        continue;
                    }
                    $mail->AddAttachment($file['tmp_name'][$key], $file['name'][$key]);
                }
            } else {
                $mail->AddAttachment($file['tmp_name'], $file['name']);
            }
        }
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->send();
    }
}

if (!function_exists('sendMailWithAttachmentRealPath')) {

    function sendMailWithAttachmentRealPath($from, $to, $subject, $body, $fromName = NULL, $filePath = NULL, $replyTo = NULL, $is_html = true)
    {
        // if (is_staging_server()) return true;
        require_once(APPPATH . 'libraries/phpmailer/PHPMailerAutoload.php');
        $mail = new PHPMailer;
        $mail->From = $from;
        $mail->FromName = $fromName;

        if ($replyTo == NULL) {
            $mail->addReplyTo($from);
        } else {
            $mail->addReplyTo($replyTo);
        }

        $mail->addAddress($to);
        $mail->CharSet = 'UTF-8';
        //$mail->addBCC('prosaifhasi@gmail.com');
        $mail->isHTML(true);

        if ($filePath != NULL) {
            $mail->AddAttachment($filePath);
        }

        //
        // mailAWSSES($mail, $to);

        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->send();
        return $mail->getLastMessageID();
    }
}

if (!function_exists('sendMailWithAttachmentAsString')) {

    function sendMailWithAttachmentAsString(
        $from,
        $to,
        $subject,
        $body,
        $fromName = NULL,
        $filePath = NULL,
        $replyTo = NULL,
        $is_html = true
    ) {
        require_once(APPPATH . 'libraries/phpmailer/PHPMailerAutoload.php');
        $mail = new PHPMailer;
        $mail->From = $from;
        $mail->FromName = $fromName;
        $mail->addReplyTo($replyTo === null ? $from : $replyTo);
        $mail->addAddress($to);
        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);

        if ($filePath != NULL) {
            //
            $data = $filePath["data"];
            // Create CSV content in memory
            $dataContent = '';
            $file = fopen('php://temp', 'r+');
            foreach ($data as $row) {
                fputcsv($file, $row);
            }
            rewind($file);
            $dataContent = stream_get_contents($file);
            fclose($file);
            // Attach the CSV content as a file
            $mail->addStringAttachment($dataContent, $filePath["name"]);
        }
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->send();
        return $mail->getLastMessageID();
    }
}


if (!function_exists('sendMailWithStringAttachment')) {

    function sendMailWithStringAttachment($from, $to, $subject, $body, $fromName = NULL, $files, $replyTo = NULL)
    {
        if (is_staging_server())
            return true;
        require_once(APPPATH . 'libraries/phpmailer/PHPMailerAutoload.php');
        $mail = new PHPMailer;
        $mail->From = $from;
        $mail->FromName = $fromName;

        if ($replyTo == NULL) {
            $mail->addReplyTo($from);
        } else {
            $mail->addReplyTo($replyTo);
        }

        $mail->addAddress($to);
        $mail->CharSet = 'UTF-8';
        //$mail->addBCC('prosaifhasi@gmail.com');
        $mail->isHTML(true);

        //
        // mailAWSSES($mail, $to);

        foreach ($files as $file) {
            $string = file_get_contents(AWS_S3_BUCKET_URL . urlencode($file['document_name']));
            $mail->AddStringAttachment($string, $file['document_original_name'] . '.' . $file['document_type'], $encoding = 'base64', $type = 'application/octet-stream');
        }

        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->send();
    }
}


if (!function_exists('db_get_active_countries')) {

    function db_get_active_countries()
    {
        $CI = &get_instance();
        $CI->db->select('*');
        $CI->db->where('active', '1');
        $CI->db->order_by("order", "asc");
        $CI->db->from('countries');
        return $CI->db->get()->result_array();
    }
}

if (!function_exists('db_get_active_states')) {

    function db_get_active_states($sid = NULL)
    {
        $CI = &get_instance();
        $CI->db->select('sid, state_code, state_name');
        $CI->db->where('country_sid', $sid);
        $CI->db->where('active', '1');
        $CI->db->order_by("order", "asc");
        $CI->db->from('states');
        return $CI->db->get()->result_array();
    }
}

if (!function_exists('db_get_state_name_only')) {

    function db_get_state_name_only($state_sid)
    {
        $CI = &get_instance();
        $CI->db->select('state_name');
        $CI->db->where('sid', $state_sid);
        $CI->db->from('states');
        $data = $CI->db->get()->result_array();

        if (!empty($data)) {
            $data = $data[0];
            return $data['state_name'];
        } else {
            return '';
        }
    }
}

if (!function_exists('db_get_state_code_only')) {
    function db_get_state_code_only($state_sid)
    {
        $CI = &get_instance();
        $CI->db->select('state_code');
        $CI->db->where('sid', $state_sid);
        $CI->db->from('states');
        $data = $CI->db->get()->result_array();

        if (!empty($data)) {
            $data = $data[0];
            return $data['state_code'];
        } else {
            return '';
        }
    }
}

if (!function_exists('db_get_country_name')) {

    function db_get_country_name($sid, $_this = false, $column = '*')
    {
        if (!$_this)
            $_this = &get_instance();
        $result = $_this->db
            ->select($column)
            ->where('sid', $sid)
            ->from('countries')
            ->get();
        $result_arr = $result->row_array();
        $result = $result->free_result();
        return sizeof($result_arr) && isset($result_arr) && $column != '*' ? $result_arr[$column] : $result_arr;
    }
}

if (!function_exists('db_get_cart_content')) {

    function db_get_cart_content($sid)
    {
        $CI = &get_instance();
        $CI->db->select('shopping_cart.sid, shopping_cart.product_sid, shopping_cart.qty, shopping_cart.date, shopping_cart.company_sid, shopping_cart.no_of_days, products.name, products.serialized_extra_info, products.price, products.product_image');
        $CI->db->where('company_sid', $sid);
        $CI->db->from('shopping_cart');
        $CI->db->join('products', 'products.sid = shopping_cart.product_sid');
        $result = $CI->db->get()->result_array();
        return $result;
    }
}

if (!function_exists('db_delete_cart_product')) {

    function db_delete_cart_product($sid)
    {
        $CI = &get_instance();
        $CI->db->where('sid', $sid);
        $CI->db->delete('shopping_cart');
    }
}

if (!function_exists('db_check_cart_products')) {

    function db_check_cart_products($company_sid, $product_sid)
    {
        $CI = &get_instance();
        $CI->db->where('company_sid', $company_sid);
        $CI->db->where('product_sid', $product_sid);
        $CI->db->from('shopping_cart');
        $result = $CI->db->get()->result_array();
        return $result;
    }
}

if (!function_exists('db_get_state_name')) {

    function db_get_state_name($sid, $_this = false, $column = 'country_sid, state_code, state_name, country_code, country_name')
    {
        if (!$_this)
            $_this = &get_instance();
        $result = $_this->db
            ->select($column)
            ->join('countries', 'countries.sid = states.country_sid')
            ->where('states.sid', $sid)
            ->from('states')
            ->limit(1)
            ->get();
        //
        $result_arr = $result->row_array();
        $result = $result->free_result();
        return sizeof($result_arr) && isset($result_arr[$column]) ? $result_arr[$column] : $result_arr;
    }
}

if (!function_exists('generate_image_thumbnail')) {

    function generate_image_thumbnail($source_image_path, $thumbnail_image_path)
    {
        list($source_image_width, $source_image_height, $source_image_type) = getimagesize($source_image_path);

        switch ($source_image_type) {
            case IMAGETYPE_GIF:
                $source_gd_image = imagecreatefromgif($source_image_path);
                break;
            case IMAGETYPE_JPEG:
                $source_gd_image = imagecreatefromjpeg($source_image_path);
                break;
            case IMAGETYPE_PNG:
                $source_gd_image = imagecreatefrompng($source_image_path);
                break;
        }

        if ($source_gd_image === false) {
            return false;
        }

        $source_aspect_ratio = $source_image_width / $source_image_height;
        $thumbnail_aspect_ratio = THUMBNAIL_IMAGE_MAX_WIDTH / THUMBNAIL_IMAGE_MAX_HEIGHT;

        if ($source_image_width <= THUMBNAIL_IMAGE_MAX_WIDTH && $source_image_height <= THUMBNAIL_IMAGE_MAX_HEIGHT) {
            $thumbnail_image_width = $source_image_width;
            $thumbnail_image_height = $source_image_height;
        } elseif ($thumbnail_aspect_ratio > $source_aspect_ratio) {
            $thumbnail_image_width = (int) (THUMBNAIL_IMAGE_MAX_HEIGHT * $source_aspect_ratio);
            $thumbnail_image_height = THUMBNAIL_IMAGE_MAX_HEIGHT;
        } else {
            $thumbnail_image_width = THUMBNAIL_IMAGE_MAX_WIDTH;
            $thumbnail_image_height = (int) (THUMBNAIL_IMAGE_MAX_WIDTH / $source_aspect_ratio);
        }

        $thumbnail_gd_image = imagecreatetruecolor($thumbnail_image_width, $thumbnail_image_height);
        imagecopyresampled($thumbnail_gd_image, $source_gd_image, 0, 0, 0, 0, $thumbnail_image_width, $thumbnail_image_height, $source_image_width, $source_image_height);
        $img_disp = imagecreatetruecolor(THUMBNAIL_IMAGE_MAX_WIDTH, THUMBNAIL_IMAGE_MAX_WIDTH);
        $backcolor = imagecolorallocate($img_disp, 255, 255, 255);
        imagefill($img_disp, 0, 0, $backcolor);
        imagecopy($img_disp, $thumbnail_gd_image, (imagesx($img_disp) / 2) - (imagesx($thumbnail_gd_image) / 2), (imagesy($img_disp) / 2) - (imagesy($thumbnail_gd_image) / 2), 0, 0, imagesx($thumbnail_gd_image), imagesy($thumbnail_gd_image));
        imagejpeg($img_disp, $thumbnail_image_path, 90);
        imagedestroy($source_gd_image);
        imagedestroy($thumbnail_gd_image);
        imagedestroy($img_disp);
        return true;
    }
}

if (!function_exists('generate_image_compressed')) {

    function generate_image_compressed($source_image_path, $thumbnail_image_path)
    {
        ImageJPEG(ImageCreateFromString(file_get_contents($source_image_path)), $thumbnail_image_path, 75);
    }
}

if (!function_exists('message_header_footer_domain')) {

    function message_header_footer_domain($compnay_id, $company_Name)
    {
        $CI = &get_instance();
        $CI->db->select('sub_domain');
        $CI->db->where('user_sid', $compnay_id);
        $CI->db->from('portal_employer');
        $result = $CI->db->get()->result_array();
        $domain_name = $result[0]['sub_domain'];
        $data['header'] = '<div class = "content" style = "font-size: 100%; line-height: 1.6em; display: block; max-width: 1000px; margin: 0 auto; padding: 0; position:relative"><div style = "width:100%; float:left; padding:5px 20px; text-align:center; box-sizing: border-box; background-color:#0000FF;"><h2 style = "color:#fff;">' . $company_Name . '</h2></div> <div class = "body-content" style = "width:100%; float:left; padding:20px 20px 60px 20px; box-sizing:border-box; background:url(images/bg-body.jpg);">';
        $data['footer'] = '</div><div class = "footer" style = "width:100%; float:left; background-color:#0000FF; padding:20px 30px; box-sizing:border-box;"><div style = "float:left; width:100%; "><p style = "color:#fff; text-align:center; font-style:italic; line-height:normal; font-family: "Open Sans", sans-serif; font-weight:600; font-size:14px;"><a style = "color:#fff; text-decoration:none;" href = "' . STORE_PROTOCOL . $domain_name . '">' . $domain_name . '</a></p></div></div></div>';
        return $data;
    }
}

if (!function_exists('date_with_time')) {

    function date_with_time($date)
    {
        $with_time = date('M d Y, D H:i:s', strtotime($date));

        if (strpos($with_time, '00:00:00')) {
            $with_time = date('M d Y, D', strtotime($date));
        }

        return $with_time;
        //        return date('D, M d Y H:i:s', strtotime($date));
    }
}

if (!function_exists('date_time_not_day')) {

    function date_time_not_day($date)
    {
        return date('M d Y, D H:i:s', strtotime($date));
        //        return date('d M Y h:i:s', strtotime($date));
    }
}

if (!function_exists('save_email_log_common')) {

    function save_email_log_common($data)
    {
        $CI = &get_instance();
        $CI->db->query("SET NAMES 'utf8mb4'");
        $CI->db->insert('email_log', $data);
    }
}

if (!function_exists('my_date_format')) {

    function my_date_format($data)
    {
        return date('M d Y, D H:i:s', strtotime($data));
        //        return date('m-d-Y', strtotime($data));
    }
}

if (!function_exists('checkCompanyAccurateCheck')) {

    function checkCompanyAccurateCheck($company_id)
    {
        $CI = &get_instance();
        $CI->db->select('background_check');
        $CI->db->where('sid', $company_id);
        $result = $CI->db->get('users')->result_array();
        return $result[0]['background_check'];
    }
}

if (!function_exists('db_get_coupon_content')) {

    function db_get_coupon_content($code)
    {
        $CI = &get_instance();
        $CI->db->select('*');
        $CI->db->where('code', $code);
        $CI->db->where('active', '1');
        $result = $CI->db->get('promotions')->result_array();
        return $result;
    }
}

if (!function_exists('db_get_sub_domain')) {

    function db_get_sub_domain($company_id)
    {
        $CI = &get_instance();
        $CI->db->select('sub_domain');
        $CI->db->where('user_sid', $company_id);
        $CI->db->from('portal_employer');
        $result = $CI->db->get()->result_array();

        if (!empty($result)) {
            $domain_name = $result[0]['sub_domain'];
            return $domain_name;
        } else {
            return $company_id;
        }
    }
}

if (!function_exists('db_get_products_details')) {

    function db_get_products_details($product_id)
    {
        $CI = &get_instance();
        $CI->db->select('sid, name, active, price, number_of_postings, expiry_days, daily');
        $CI->db->where('sid', $product_id);
        $CI->db->from('products');
        $result = $CI->db->get()->result_array();

        if (!empty($result)) {
            return $result[0];
        }
    }
}
if (!function_exists('db_get_cleanstring')) {

    function db_get_cleanstring($string)
    {
        //Lower case everything
        $string = strtolower($string);
        //Make alphanumeric (removes all other characters)
        $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
        //Clean up multiple dashes or whitespaces
        $string = preg_replace("/[\s-]+/", " ", $string);
        //Convert whitespaces and underscore to dash
        $string = preg_replace("/[\s_]/", "-", $string);
        return $string;
    }
}

if (!function_exists('db_get_card_details')) {

    function db_get_card_details($sid)
    {
        $CI = &get_instance();
        $CI->db->select('sid, number, type,is_default,expire_month,expire_year, name_on_card');
        $CI->db->where('company_sid', $sid);
        $CI->db->from('emp_cards');
        $result = $CI->db->get()->result_array();
        return $result;
    }
}

if (!function_exists('db_get_cc_detail')) {

    function db_get_cc_detail($sid, $company_sid = NULL)
    {
        $CI = &get_instance();

        if (!empty($company_sid)) {
            $CI->db->where('company_sid', $company_sid);
        }

        $CI->db->where('sid', $sid);
        $CI->db->from('emp_cards');
        $result = $CI->db->get()->result_array();

        if (!empty($result)) {
            return $result[0];
        } else {
            return $result;
        }
    }
}
if (!function_exists('month_date_year')) {

    function month_date_year($date)
    {
        return date('M d Y, D H:i:s', strtotime($date));
        //        return date('M d Y', strtotime($date));
    }
}
if (!function_exists('encode_string')) {

    function encode_string($password)
    {
        $key = '#&$sdfdadasdsaderfvrfgbty78hnmuik263uifs5634d';
        $encoded = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $password, MCRYPT_MODE_CBC, md5(md5($key))));
        return $encoded;
    }
}

if (!function_exists('decode_string')) {

    function decode_string($encoded)
    {
        $key = '#&$sdfdadasdsaderfvrfgbty78hnmuik263uifs5634d';
        $decoded = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($encoded), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
        return $decoded;
    }
}

if (!function_exists('db_get_product_name')) {

    function db_get_product_name($sid)
    {
        $CI = &get_instance();
        $CI->db->select('name');
        $CI->db->where('sid', $sid);
        $CI->db->from('products');
        $result = $CI->db->get()->result_array();

        if (!empty($result)) {
            return $result[0]['name'];
        } else {
            return $result;
        }
    }
}

if (!function_exists('db_get_job_title')) {

    function db_get_job_title($user_sid, $title, $city = '', $state = '', $country = '')
    {
        if (!$city == false) {
            $jobTitle = $title;
            if ($city != '')
                $jobTitle .= ' - ' . ucfirst($city);
            if ($state != '')
                $jobTitle .= ', ' . $state;
            if ($country != '')
                $jobTitle .= ', ' . $country;
            return trim($jobTitle);
        }
        $CI = &get_instance();
        $CI->db->select('job_title_location');
        $CI->db->where('user_sid', $user_sid);
        $CI->db->from('portal_employer');
        $result = $CI->db->get()->row_array();

        if (!empty($result)) {
            if ($result['job_title_location']) {
                $jobTitle = $title;
                if ($city != '')
                    $jobTitle .= ' - ' . ucfirst($city);
                if ($state != '')
                    $jobTitle .= ', ' . $state;
                if ($country != '')
                    $jobTitle .= ', ' . $country;
                $jobTitle = trim($jobTitle);
            } else {
                $jobTitle = $title;
            }

            return $jobTitle;
        }
    }
}

if (!function_exists('db_check_email_exists')) {

    function db_check_email_exists($sid, $email)
    {
        $CI = &get_instance();
        $CI->db->select('*');
        $CI->db->where('sid', $sid);
        $CI->db->where('email', $email);
        $CI->db->from('users');
        $result = $CI->db->get()->result_array();

        if (empty($result)) { // email not registered under this company account
            // now check if this email exist against any employee on current company
            $CI->db->select('*');
            $CI->db->where('parent_sid', $sid);
            $CI->db->where('email', $email);
            $CI->db->from('users');
            $result2 = $CI->db->get()->result_array();

            if (empty($result2)) { // Email address is not found. It can be used for registeration perpose
                return 'success';
            } else {
                return 'error';
            }
        } else {
            return 'error';
        }
    }

    /*
      if (string.IsNullOrEmpty(RTE.Document.GetTextInSpan(DocumentSpan.All)))
      {
      //Empty text in the xamRichTextEditor
      }
     *  */
}


if (!function_exists('db_get_applicant_profile')) {

    function db_get_applicant_profile($sid)
    {
        $CI = &get_instance();
        //$CI->db->select('portal_job_applications.*');
        $CI->db->select('portal_job_applications.sid');
        $CI->db->select('portal_job_applications.employer_sid');
        $CI->db->select('portal_job_applications.first_name');
        $CI->db->select('portal_job_applications.last_name');
        $CI->db->select('portal_job_applications.pictures');
        $CI->db->select('portal_job_applications.email');
        $CI->db->select('portal_job_applications.phone_number');
        $CI->db->select('portal_job_applications.address');
        $CI->db->select('portal_job_applications.country');
        $CI->db->select('portal_job_applications.city');
        $CI->db->select('portal_job_applications.state');
        $CI->db->select('portal_job_applications.zipcode');
        $CI->db->select('portal_job_applications.resume');
        $CI->db->select('portal_job_applications.cover_letter');
        $CI->db->select('portal_job_applications.YouTube_Video');
        $CI->db->select('portal_job_applications.full_employment_application');
        $CI->db->select('portal_job_applications.hired_sid');
        $CI->db->select('portal_job_applications.hired_status');
        $CI->db->select('portal_job_applications.hired_date');
        $CI->db->select('portal_job_applications.verification_key');
        $CI->db->select('portal_job_applications.linkedin_profile_url');
        $CI->db->select('portal_job_applications.extra_info');
        $CI->db->select('portal_job_applications.referred_by_name');
        $CI->db->select('portal_job_applications.referred_by_email');
        $CI->db->select('portal_job_applications.job_fit_category_sid');
        $CI->db->select('portal_applicant_jobs_list.*, portal_applicant_jobs_list.sid as portal_applicant_jobs_list_sid');
        $CI->db->join('portal_applicant_jobs_list', 'portal_job_applications.sid = portal_applicant_jobs_list.portal_job_applications_sid', 'left');
        $CI->db->where('portal_job_applications.sid', $sid);
        $result = $CI->db->get('portal_job_applications')->result_array();

        if (empty($result)) { // applicant does not exits
            return 'error';
        } else {
            return $result[0];
        }
    }
}

if (!function_exists('db_insert_company_employee')) {

    function db_insert_company_employee($employer_data, $sid, $hired_job_sid = 0)
    {
        $CI = &get_instance();
        $CI->db->insert('users', $employer_data);
        $user_id = $CI->db->insert_id(); // check if insert was successful

        if ($CI->db->affected_rows() == '1') { // now update applications table
            $CI->db->where('sid', $sid);
            $data_applicant = array(
                'hired_sid' => $user_id,
                'hired_status' => '1',
                'hired_date' => date('Y-m-d'),
                'hired_job_sid' => $hired_job_sid
            );

            $CI->db->update('portal_job_applications', $data_applicant);
            $CI->db->trans_complete();
            // was there any update or error?
            if ($CI->db->affected_rows() == '1') {
                return $user_id;
            } else {
                if ($CI->db->trans_status() === FALSE) {
                    return 'error';
                }

                return $user_id;
            }
        } else {
            return 'error';
        }
    }
}


if (!function_exists('getEmployeeUserParent_sid')) {

    function getEmployeeUserParent_sid($sid)
    {
        $CI = &get_instance();
        $CI->db->select('parent_sid');
        $CI->db->where('sid', $sid);
        $CI->db->limit(1);
        $result = $CI->db->get('users')->result_array();

        if (count($result) > 0) {
            return $result[0]['parent_sid'];
        } else {
            return 0;
        }
    }
}

if (!function_exists('getApplicantsEmployer_sid')) {

    function getApplicantsEmployer_sid($sid)
    {
        $CI = &get_instance();
        $CI->db->select('employer_sid');
        $CI->db->where('sid', $sid);
        $CI->db->limit(1);
        $result = $CI->db->get('portal_job_applications')->result_array();

        if (count($result) > 0) {
            return $result[0]['employer_sid'];
        } else {
            return 0;
        }
    }
}


if (!function_exists('db_get_applicant_emergency_contacts')) {

    function db_get_applicant_emergency_contacts($sid, $hired_id)
    {
        $CI = &get_instance();
        $CI->db->select('*');
        $CI->db->where('users_sid', $sid);
        $CI->db->where('users_type', 'applicant');
        $result = $CI->db->get('emergency_contacts')->result_array();

        if (count($result) > 0) {
            foreach ($result as $employee) {
                $insert_emergency_contact = array('users_sid' => $hired_id, 'users_type' => 'employee', 'first_name' => $employee['first_name'], 'last_name' => $employee['last_name'], 'email' => $employee['email'], 'Location_Country' => $employee['Location_Country'], 'Location_State' => $employee['Location_State'], 'Location_City' => $employee['Location_City'], 'Location_ZipCode' => $employee['Location_ZipCode'], 'Location_Address' => $employee['Location_Address'], 'PhoneNumber' => $employee['PhoneNumber'], 'Relationship' => $employee['Relationship'], 'priority' => $employee['priority']);
                $CI->db->insert('emergency_contacts', $insert_emergency_contact);
            }
            return $result;
        } else {
            return 0;
        }
    }
}

if (!function_exists('db_get_applicant_equipment_information')) {

    function db_get_applicant_equipment_information($sid, $hired_id)
    {
        $CI = &get_instance();
        $CI->db->select('*');
        $CI->db->where('users_sid', $sid);
        $CI->db->where('users_type', 'applicant');
        $result = $CI->db->get('equipment_information')->result_array();

        if (count($result) > 0) {
            foreach ($result as $equipment_information) {
                $insert_equipment_information = array('users_sid' => $hired_id, 'users_type' => 'employee', 'equipment_details' => $equipment_information['equipment_details']);
                $CI->db->insert('equipment_information', $insert_equipment_information);
            }
            return $result;
        } else {
            return 0;
        }
    }
}


if (!function_exists('db_get_applicant_dependant_information')) {

    function db_get_applicant_dependant_information($sid, $hired_id)
    {
        $CI = &get_instance();
        $CI->db->select('*');
        $CI->db->where('users_sid', $sid);
        $CI->db->where('users_type', 'applicant');
        $result = $CI->db->get('dependant_information')->result_array();

        if (count($result) > 0) {
            foreach ($result as $info) {
                $insert_dependant_information = array('users_sid' => $hired_id, 'users_type' => 'employee', 'company_sid' => $info['company_sid'], 'dependant_details' => $info['dependant_details']);
                $CI->db->insert('dependant_information', $insert_dependant_information);
            }
            return $result;
        } else {
            return 0;
        }
    }
}

if (!function_exists('db_get_applicant_license_information')) {

    function db_get_applicant_license_information($sid, $hired_id)
    {
        $CI = &get_instance();
        $CI->db->select('*');
        $CI->db->where('users_sid', $sid);
        $CI->db->where('users_type', 'applicant');
        $result = $CI->db->get('license_information')->result_array();

        if (count($result) > 0) {
            foreach ($result as $info) {
                $insert_license_information = array('users_sid' => $hired_id, 'users_type' => 'employee', 'license_type' => $info['license_type'], 'license_details' => $info['license_details']);
                $CI->db->insert('license_information', $insert_license_information);
            }
            return $result;
        } else {
            return 0;
        }
    }
}

if (!function_exists('db_get_applicant_background_check_order')) {

    function db_get_applicant_background_check_order($sid, $hired_id)
    {
        $CI = &get_instance();
        $CI->db->select('*');
        $CI->db->where('users_sid', $sid);
        $CI->db->where('users_type', 'applicant');
        $result = $CI->db->get('background_check_orders')->result_array();

        if (count($result) > 0) {
            foreach ($result as $info) {
                $info['sid'] = 'NULL';
                $info['users_sid'] = $hired_id;
                $info['users_type'] = 'employee';
                $CI->db->insert('background_check_orders', $info);
            }
            return $result;
        } else {
            return 0;
        }
    }
}

if (!function_exists('db_get_applicant_background_check')) {

    function db_get_applicant_background_check($sid, $hired_id)
    {
        $CI = &get_instance();
        $CI->db->select('*');
        $CI->db->where('users_sid', $sid);
        $CI->db->where('users_type', 'applicant');
        $result = $CI->db->get('background_check_orders')->result_array();

        if (count($result) > 0) {
            foreach ($result as $info) {
                $insert_background_check = array('employer_sid' => $info['employer_sid'], 'company_sid' => $info['company_sid'], 'users_sid' => $hired_id, 'users_type' => 'employee', 'product_sid' => $info['product_sid'], 'package_id' => $info['package_id'], 'package_response' => $info['package_response'], 'order_response' => $info['order_response'], 'order_response_status' => $info['order_response_status'], 'date_applied' => $info['date_applied'], 'date_response_received' => $info['date_response_received'], 'product_price' => $info['product_price'], 'product_name' => $info['product_name'], 'product_type' => $info['product_type'], 'product_image' => $info['product_image']);
                $CI->db->insert('background_check_orders', $insert_background_check);
            }
            return $result;
        } else {
            return 0;
        }
    }
}

if (!function_exists('db_get_applicant_misc_notes')) {

    function db_get_applicant_misc_notes($sid, $hired_id)
    {
        $CI = &get_instance();
        $CI->db->select('*');
        $CI->db->where('applicant_job_sid', $sid);
        $CI->db->where('users_type', 'applicant');
        $result = $CI->db->get('portal_misc_notes')->result_array();

        if (count($result) > 0) {
            foreach ($result as $info) {
                $insert_misc_notes = array('employers_sid' => $info['employers_sid'], 'applicant_job_sid' => $hired_id, 'users_type' => 'employee', 'applicant_email' => $info['applicant_email'], 'notes' => $info['notes'], 'attachments' => $info['attachments'], 'attachment_name' => $info['attachment_name'], 'insert_date' => $info['insert_date'], 'modified_date' => $info['modified_date']);
                $CI->db->insert('portal_misc_notes', $insert_misc_notes);
            }
            return $result;
        } else {
            return 0;
        }
    }
}

if (!function_exists('db_get_applicant_private_message')) {

    function db_get_applicant_private_message($email, $hired_id)
    {
        $CI = &get_instance();
        $CI->db->select('*');
        $CI->db->where('to_id', $email);
        $CI->db->where('users_type', 'applicant');
        $result = $CI->db->get('private_message')->result_array();

        if (count($result) > 0) {
            foreach ($result as $info) {
                $insert_private_message = array('from_id' => $info['from_id'], 'to_id' => $hired_id, 'from_type' => 'employer', 'to_type' => 'employer', 'users_type' => 'employee', 'date' => $info['date'], 'status' => $info['status'], 'subject' => $info['subject'], 'message' => $info['message'], 'outbox' => $info['outbox'], 'anonym' => $info['anonym'], 'attachment' => $info['attachment'], 'job_id' => '');
                $CI->db->insert('private_message', $insert_private_message);
            }
            return $result;
        } else {
            return 0;
        }
    }
}

if (!function_exists('db_get_applicant_rating')) {

    function db_get_applicant_rating($sid, $hired_id)
    {
        $CI = &get_instance();
        $CI->db->select('*');
        $CI->db->where('applicant_job_sid', $sid);
        $CI->db->where('users_type', 'applicant');
        $result = $CI->db->get('portal_applicant_rating')->result_array();

        if (count($result) > 0) {
            foreach ($result as $info) {
                $insert_applicant_rating = array(
                    'employer_sid' => $info['employer_sid'],
                    'users_type' => 'employee',
                    'applicant_job_sid' => $hired_id,
                    'applicant_email' => $info['applicant_email'],
                    'rating' => $info['rating'],
                    'comment' => $info['comment'],
                    'date_added' => $info['date_added'],
                    'attachment' => $info['attachment'],
                    'attachment_extension' => $info['attachment_extension']
                );
                $CI->db->insert('portal_applicant_rating', $insert_applicant_rating);
            }
            return $result;
        } else {
            return 0;
        }
    }
}

if (!function_exists('db_get_applicant_schedule_event')) {

    function db_get_applicant_schedule_event($sid, $hired_id)
    {
        $CI = &get_instance();
        $CI->db->select('*');
        $CI->db->where('applicant_job_sid', $sid);
        $CI->db->where('users_type', 'applicant');
        $result = $CI->db->get('portal_schedule_event')->result_array();

        if (count($result) > 0) {
            foreach ($result as $info) {
                $insert_schedule_event = array('companys_sid' => $info['companys_sid'], 'employers_sid' => $info['employers_sid'], 'applicant_email' => $info['applicant_email'], 'applicant_job_sid' => $hired_id, 'users_type' => 'employee', 'title' => $info['title'], 'category' => $info['category'], 'date' => $info['date'], 'description' => $info['description'], 'eventstarttime' => $info['eventstarttime'], 'eventendtime' => $info['eventendtime'], 'interviewer' => $info['interviewer'], 'commentCheck' => $info['commentCheck'], 'messageCheck' => $info['messageCheck'], 'comment' => $info['comment'], 'subject' => $info['subject'], 'message' => $info['message'], 'messageFile' => $info['messageFile'], 'address' => $info['address'], 'goToMeetingCheck' => $info['goToMeetingCheck'], 'meetingId' => $info['meetingId'], 'meetingCallNumber' => $info['meetingCallNumber'], 'meetingURL' => $info['meetingURL'], 'created_on' => $info['created_on']);
                $CI->db->insert('portal_schedule_event', $insert_schedule_event);
            }
            return $result;
        } else {
            return 0;
        }
    }
}

if (!function_exists('db_get_applicant_attachments')) {

    function db_get_applicant_attachments($sid, $hired_id)
    {
        $CI = &get_instance();
        $CI->db->select('*');
        $CI->db->where('applicant_job_sid', $sid);
        $CI->db->where('users_type', 'applicant');
        $result = $CI->db->get('portal_applicant_attachments')->result_array();

        if (count($result) > 0) {
            foreach ($result as $info) {
                $insert_applicant_attachments = array('employer_sid' => $info['employer_sid'], 'applicant_job_sid' => $hired_id, 'users_type' => 'employee', 'original_name' => $info['original_name'], 'uploaded_name' => $info['uploaded_name'], 'date_uploaded' => $info['date_uploaded']);
                $CI->db->insert('portal_applicant_attachments', $insert_applicant_attachments);
            }
            return $result;
        } else {
            return 0;
        }
    }
}

if (!function_exists('db_get_reference_checks')) {

    function db_get_reference_checks($sid, $hired_id)
    {
        $CI = &get_instance();
        $CI->db->select('*');
        $CI->db->where('user_sid', $sid);
        $CI->db->where('users_type', 'applicant');
        $result = $CI->db->get('reference_checks')->result_array();

        if (count($result) > 0) {
            foreach ($result as $info) {
                $insert_reference_checks = array('company_sid' => $info['company_sid'], 'user_sid' => $hired_id, 'users_type' => 'employee', 'organization_name' => $info['organization_name'], 'department_name' => $info['department_name'], 'branch_name' => $info['branch_name'], 'program_name' => $info['program_name'], 'period_start' => $info['period_start'], 'period_end' => $info['period_end'], 'period' => $info['period'], 'reference_type' => $info['reference_type'], 'reference_name' => $info['reference_name'], 'reference_title' => $info['reference_title'], 'reference_relation' => $info['reference_relation'], 'reference_email' => $info['reference_email'], 'reference_phone' => $info['reference_phone'], 'best_time_to_call' => $info['best_time_to_call'], 'other_information' => $info['other_information'], 'questionnaire_information' => $info['questionnaire_information'], 'questionnaire_conducted_by' => $info['questionnaire_conducted_by'], 'verified' => $info['verified'], 'status' => $info['status']);
                $CI->db->insert('reference_checks', $insert_reference_checks);
            }
            return $result;
        } else {
            return 0;
        }
    }
}

if (!function_exists('formatDateForDb')) {

    function formatDateForDb($date)
    {
        $date_parts = explode(strpos($date, '/') !== false ? '/' : '-', $date);
        $day = '00';
        $month = '00';
        $year = '0000';

        // m-d-Y
        $dayIndex = 1;
        $monthIndex = 0;
        $yearIndex = 2;

        // Y-m-d
        if (
            preg_match('/[0-9]{4}-[0-9]{2}-[0-9]{2}/', $date) !== false ||
            preg_match('/[0-9]{4}\/[0-9]{2}\/[0-9]{2}/', $date) !== false
        ) {
            $yearIndex = 0;
            $monthIndex = 1;
            $dayIndex = 2;
        }

        // m-d-y
        if (
            preg_match('/[0-9]{4}-[0-9]{2}-[0-9]{2}/', $date) !== false ||
            preg_match('/[0-9]{4}\/[0-9]{2}\/[0-9]{2}/', $date) !== false
        ) {
            $monthIndex = 0;
            $dayIndex = 1;
            $yearIndex = 2;
        }
        //
        if (count($date_parts) == 3)
            $month = $date_parts[$monthIndex];
        if (count($date_parts) == 3)
            $day = $date_parts[$dayIndex];
        if (count($date_parts) == 3)
            $year = $date_parts[$yearIndex];
        //
        return strtotime($year . '-' . $month . '-' . $day . '00:00:00');
    }
}

if (!function_exists('get_email_template')) {

    function get_email_template($template_id)
    {
        $CI = &get_instance();
        $CI->db->where('sid', $template_id);
        $result = $CI->db->get('email_templates')->row_array();

        if (count($result) > 0) {
            return $result;
        } else {
            return 0;
        }
    }
}

if (!function_exists('get_sms_template')) {

    function get_sms_template($template_id)
    {
        $CI = &get_instance();
        $CI->db->where('sid', $template_id);
        $result = $CI->db->get('portal_sms_templates')->row_array();

        if (count($result) > 0) {
            return $result;
        } else {
            return 0;
        }
    }
}

if (!function_exists('convert_email_template')) {
    function convert_email_template($emailTemplateBody, $replacement_array = array())
    {
        if (!$replacement_array) {
            return $emailTemplateBody;
        }
        $company_data = get_company_data($replacement_array["parent_sid"]);
        //
        $change_password = '<a style="background-color: #d62828; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" href="' . base_url() . 'change_password/' . $replacement_array['username'] . '/' . $replacement_array['verification_key'] . '" target="_blank">Change your password</a>';
        //
        $change_your_password = '<a style="background-color: #d62828; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" href="' . base_url() . 'change_password/' . $replacement_array['username'] . '/' . $replacement_array['verification_key'] . '" target="_blank">Change your password</a>';
        //
        $button = '<a style="background-color: #d62828; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" href="' . base_url('login') . '" target="_blank">Button</a>';
        //
        $click_here = '<a style="background-color: #d62828; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" href="' . base_url() . 'account_activation/{{activation_key}}" target="_blank">Click Here</a>';
        //
        $login_link = '<a href="' . (base_url('login')) . '" style="color: #ffffff; background-color: #0000FF; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; border-radius: 5px; text-align: center; display:inline-block;">Click to login</a>';
        //
        $emailTemplateBody = str_replace('{{company_name}}', $company_data['company_name'], $emailTemplateBody);
        $emailTemplateBody = str_replace('{{company_address}}', $company_data['company_address'], $emailTemplateBody);
        $emailTemplateBody = str_replace('{{company_phone}}', $company_data['company_phone'], $emailTemplateBody);
        $emailTemplateBody = str_replace('{{career_site_url}}', $company_data['career_site_url'], $emailTemplateBody);
        //
        $emailTemplateBody = str_replace('{{email}}', ucfirst($replacement_array['email']), $emailTemplateBody);
        $emailTemplateBody = str_replace('{{job_title}}', ucfirst($replacement_array['job_title']), $emailTemplateBody);
        $emailTemplateBody = str_replace('{{first_name}}', ucfirst($replacement_array['first_name']), $emailTemplateBody);
        $emailTemplateBody = str_replace('{{last_name}}', ucfirst($replacement_array['last_name']), $emailTemplateBody);
        $emailTemplateBody = str_replace('{{firstname}}', ucfirst($replacement_array['first_name']), $emailTemplateBody);
        $emailTemplateBody = str_replace('{{lastname}}', ucfirst($replacement_array['last_name']), $emailTemplateBody);
        $emailTemplateBody = str_replace('{{site_url}}', base_url(), $emailTemplateBody);
        $emailTemplateBody = str_replace('{{date}}', month_date_year(date('Y-m-d')), $emailTemplateBody);
        $emailTemplateBody = str_replace('{{username}}', $replacement_array['username'], $emailTemplateBody);
        //
        $username = substr($replacement_array['username'], 0, strpos($replacement_array['username'], "_executive_admin_"));
        //
        if (empty($username)) {
            $username = $replacement_array['username'];
        }
        //
        $emailTemplateBody = str_replace('{{username}}', $username, $emailTemplateBody);
        $emailTemplateBody = str_replace('{{contact-name}}', ucfirst($replacement_array['first_name']) . ' ' . ucfirst($replacement_array['last_name']), $emailTemplateBody);
        $emailTemplateBody = str_replace('{{applicant_name}}', ucwords($replacement_array['first_name'] . ' ' . $replacement_array['last_name']), $emailTemplateBody);
        //
        if (isset($replacement_array['password_encrypt']) && $replacement_array['password_encrypt'] == "no") {
            $emailTemplateBody = str_replace('{{password}}', $replacement_array['password'], $emailTemplateBody);
        } else {
            $emailTemplateBody = str_replace('{{password}}', decode_string($replacement_array['key']), $emailTemplateBody);
        }
        //
        $emailTemplateBody = str_replace('{{employer_id}}', $employer_sid, $emailTemplateBody);
        $emailTemplateBody = str_replace('{{verification_key}}', $replacement_array['verification_key'], $emailTemplateBody);
        $emailTemplateBody = str_replace('{{activation_key}}', $replacement_array['activation_key'], $emailTemplateBody);
        //
        $emailTemplateBody = str_replace('{{link}}', $replacement_array['link'], $emailTemplateBody);
        $emailTemplateBody = str_replace('{{change_password}}', $change_password, $emailTemplateBody);
        $emailTemplateBody = str_replace('{{change_your_password}}', $change_your_password, $emailTemplateBody);
        $emailTemplateBody = str_replace('{{button}}', $button, $emailTemplateBody);
        $emailTemplateBody = str_replace('{{click_here}}', $click_here, $emailTemplateBody);
        $emailTemplateBody = str_replace('{{login_button}}', $replacement_array['login_button'], $emailTemplateBody);
        $emailTemplateBody = str_replace('{{login_link}}', $login_link, $emailTemplateBody);

        $emailTemplateBody = str_replace('{{contact_name}}', $replacement_array['contact_name'], $emailTemplateBody);
        $emailTemplateBody = str_replace('{{from_name}}', $replacement_array['from_name'], $emailTemplateBody);



        return $emailTemplateBody;
    }
}

//
if (!function_exists('get_company_data')) {

    function get_company_data($sid)
    {
        $CI = &get_instance();
        $CI->db->where('sid', $sid);
        $result = $CI->db->get('users')->row_array();

        if (!empty($result)) {
            $address = !empty($result["Location_Address"]) ? $result["Location_Address"] : "";
            $city = !empty($result["Location_City"]) ? ", " . $result["Location_City"] : "";
            $state = !empty($result["Location_State"]) ? ", " . db_get_state_name_only($result["Location_State"]) : "";
            $country = !empty($result["Location_Country"]) ? ", " . db_get_country_name($result['Location_Country'], $CI, 'country_name') : "";
            $company_address = $address . $city . $state . $country;
            $career_site_url = STORE_PROTOCOL_SSL . db_get_sub_domain($sid);
            //
            $result_array = array();
            $result_array["company_name"] = $result["CompanyName"];
            $result_array["contact_name"] = $result["ContactName"];
            $result_array["company_phone"] = $result["PhoneNumber"];
            $result_array["company_address"] = $company_address;
            $result_array["career_site_url"] = $career_site_url;
            return $result_array;
        } else {
            return array();
        }
    }
}


//Echo Value if Array is not empty and Array Key is Set
if (!function_exists('echo_value_if_key_exists')) {

    function echo_value_if_key_exists($array, $key)
    {
        if (!empty($array)) {
            if (array_key_exists($key, $array)) {
                echo $array[$key];
            } else {
                echo '';
            }
        } else {
            echo '';
        }
    }
}

//Return Value if Array is not empty and Array Key is Set
if (!function_exists('return_value_if_key_exists')) {

    function return_value_if_key_exists($array, $key)
    {
        if (!empty($array)) {
            if (array_key_exists($key, $array)) {
                return $array[$key];
            } else {
                echo '';
            }
        } else {
            echo '';
        }
    }

    if (!function_exists('checkCompanyKpaOnboardingCheck')) {

        function checkCompanyKpaOnboardingCheck($company_id)
        {
            $CI = &get_instance();
            $CI->db->where('company_sid', $company_id);
            $result = $CI->db->get('kpa_onboarding')->row_array();

            if ($result) {
                if ($result['status'] == 1 && !empty($result['kpa_url'])) {
                    return 1;
                } else {
                    return 0;
                }
            }
        }
    }
}

if (!function_exists('IsFeatureAvailable')) { //Check if Feature Purchased and Has Not Expired - Returs a 0 if not Available 1 if Available

    function IsFeatureAvailable($Purchased = 0, $PurchaseDate, $ExpiryDate)
    {
        $current_date_time = date('Y-m-d H:i:s');
        $feature_Available = 0;

        if ($Purchased == 1) {
            if ($ExpiryDate != null) {
                if ($PurchaseDate != null) {
                    if ($PurchaseDate < $current_date_time && $ExpiryDate > $current_date_time) {
                        $feature_Available = 1;
                    } else {
                        $feature_Available = 0;
                    }
                }
            } else {
                $feature_Available = 1;
            }
        } else {
            $feature_Available = 0;
        }

        return $feature_Available;
    }
}

if (!function_exists('applicant_right_nav')) {

    function applicant_right_nav($app_id, $job_list_sid = null, $ats_params = null)
    {
        $CI = &get_instance();
        $desired_job_title = "";

        $CI->db->select('sid, desired_job_title');
        $CI->db->where('portal_job_applications_sid', $app_id);
        $CI->db->order_by('sid', 'DESC');
        $CI->db->limit(1);
        $job_list_data = $CI->db->get('portal_applicant_jobs_list')->result_array();

        if (!empty($job_list_data)) {
            if ($job_list_sid == null || $job_list_sid == 0 || $job_list_sid == '') {
                $job_list_sid = $job_list_data[0]['sid'];
            }
            //
            $desired_job_title = $job_list_data[0]['desired_job_title'];
        }

        if ($ats_params == null || empty($ats_params) || $ats_params == '' || $ats_params == 0) {
            $ats_params = $CI->session->userdata('ats_params');
        }

        //$CI->load->model('application_tracking_model');
        $CI->load->model('application_tracking_system_model');
        $data['session'] = $CI->session->userdata('logged_in');
        $employer_id = $data['session']['employer_detail']['sid'];
        $company_id = $data['session']['company_detail']['sid'];
        $security_sid = $data['session']['employer_detail']['sid'];
        $employer_detail = $data['session']['employer_detail'];
        $company_detail = $data['session']['company_detail'];
        $security_details = db_get_access_level_details($security_sid);
        $data['security_details'] = $security_details;
        $data['applicant_rating'] = $CI->application_tracking_system_model->getApplicantRating($app_id, $employer_id, 'applicant');
        $applicant_info = $CI->application_tracking_system_model->getApplicantData($app_id);
        //        echo 'hererer<pre>'; print_r($applicant_info); echo '</pre>';
        $data['applicant_info'] = $applicant_info;
        $data['company_background_check'] = $company_detail['background_check'];
        //Outsourced HR Compliance and Onboarding check
        $data['kpa_onboarding_check'] = checkCompanyKpaOnboardingCheck($company_id);
        $data['job_list_sid'] = $job_list_sid;
        //
        $data['applicant_info']['desired_job_title'] = $desired_job_title;
        //
        if ($data['kpa_onboarding_check'] == 1) {
            $kpa_email_sent = $CI->application_tracking_system_model->get_kpa_email_sent_count($company_id, $app_id);

            if ($kpa_email_sent > 0) {
                $data['kpa_email_sent'] = true;
            } else {
                $data['kpa_email_sent'] = false;
            }
        } else {
            $data['kpa_email_sent'] = false;
        }

        if (empty($data['applicant_info']['resume'])) { // check if reseme is uploaded
            $data['applicant_info']['resume_link'] = "javascript:;";
            $data['applicant_info']['resume_download_link'] = 'javascript:;';
            $data['resume_link_title'] = "No Resume found!";
        } else {
            $data['applicant_info']['resume_link'] = AWS_S3_BUCKET_URL . $data['applicant_info']['resume'];
            $data['applicant_info']['resume_download_link'] = base_url('applicant_profile/downloadFile') . '/' . $data['applicant_info']['resume'];
            $data['resume_link_title'] = $data['applicant_info']['resume'];
        }

        if (empty($data['applicant_info']['cover_letter'])) { // check if cover letter is uploaded
            $data['applicant_info']['cover_link'] = "javascript:;";
            $data['applicant_info']['cover_download_link'] = 'javascript:;';
            $data['cover_letter_title'] = 'No Cover Letter found!';
        } else {
            $data['applicant_info']['cover_link'] = AWS_S3_BUCKET_URL . $data['applicant_info']['cover_letter'];
            $data['applicant_info']['cover_download_link'] = base_url('applicant_profile/downloadFile') . '/' . $data['applicant_info']['cover_letter'];
            $data['cover_letter_title'] = $data['applicant_info']['cover_letter'];
        }

        //echo $assignment_status; exit;
        //getting next and previous jobs link STARTS
        //$next_app_id = $CI->application_tracking_system_model->next_applicant($app_id, $company_id, false);
        //$prev_app_id = $CI->application_tracking_system_model->previous_applicant($app_id, $company_id, false);

        $order = $CI->application_tracking_system_model->get_applicants_order($company_id, $ats_params);
        $index = 0;

        foreach ($order as $key => $value) {
            if ($app_id == $value['sid'] && $job_list_sid == $value['job_list_sid']) {
                $index = $key;
            }
        }

        if (isset($order[$index + 1])) {
            $next_app_id = $order[$index + 1]['sid'];
            $job_list_sid_next = $order[$index + 1]['job_list_sid'];
        } else if (isset($order[0])) {
            $next_app_id = $order[0]['sid'];
            $job_list_sid_next = $order[0]['job_list_sid'];
        } else {
            $next_app_id = 0;
            $job_list_sid_next = 0;
        }

        if (isset($order[$index - 1])) {
            $prev_app_id = $order[$index - 1]['sid'];
            $job_list_sid_prev = $order[$index - 1]['job_list_sid'];
        } else if (NULL !== (end($order))) {
            $temp = end($order);
            $prev_app_id = $temp['sid'];
            $job_list_sid_prev = $temp['job_list_sid'];
        } else {
            $prev_app_id = 0;
            $job_list_sid_prev = 0;
        }

        if (!empty($next_app_id)) {
            $data['next_app'] = base_url() . "applicant_profile/$next_app_id/$job_list_sid_next";
        } else {
            $data['next_app'] = base_url() . "applicant_profile/" . $CI->application_tracking_system_model->get_min_applicant_id($company_id);
        }

        if (!empty($prev_app_id)) {
            $data['prev_app'] = base_url() . "applicant_profile/$prev_app_id/$job_list_sid_prev";
        } else {
            $data['prev_app'] = base_url() . "applicant_profile/" . $CI->application_tracking_system_model->get_max_applicant_id($company_id);
        }

        $data['applicant_extra_attachments'] = $CI->application_tracking_system_model->getApplicantExtraAttachments($app_id, $employer_id, 'applicant');
        $data['id'] = $app_id;
        $data['user_id'] = $company_id;
        $data['user_sid'] = $company_id;
        $data['email'] = $data['applicant_info']['email'];
        //Job Fit Category
        $job_fit_categories = isset($data['applicant_info']['job_fit_categories']) ? $data['applicant_info']['job_fit_categories'] : '';

        if ($job_fit_categories != NULL || $job_fit_categories != '') {
            $data['applicant_info']['job_fit_categories'] = $CI->application_tracking_system_model->get_job_categories($job_fit_categories);
        } else {
            $data['applicant_info']['job_fit_categories'] = array();
        }

        $job_categories = db_get_job_category();
        $data['job_categories'] = $job_categories;
        $assignment_status = $CI->application_tracking_system_model->check_assignment_management($app_id, $company_id, $employer_id);
        $data['assignment_status'] = $assignment_status;
        $data['questions_sent'] = $CI->application_tracking_system_model->check_sent_video_questionnaires($app_id, $company_id);

        if ($data['questions_sent']) {
            $data['questions_answered'] = $CI->application_tracking_system_model->check_answered_video_questionnaires($app_id, $company_id);
        } else {
            $data['questions_answered'] = false;
        }

        if ($job_list_sid) {
            // $data['applicant_job_queue'] = $CI->application_tracking_system_model->get_applicant_job_queue($job_list_sid);
        }

        return $data;
    }
}


if (!function_exists('employee_right_nav')) {

    function employee_right_nav($emp_id, $data = NULL)
    {
        $CI = &get_instance();
        $CI->load->model('employee_model');
        $CI->load->model('emergency_contacts_model');

        if ($data == NULL) {
            $data['session'] = $CI->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            $data['employee'] = $data['session']['employer_detail'];
        }

        $logged_in_employer_id = $data['session']['employer_detail']['sid'];
        $logged_in_company_id = $data['session']['company_detail']['sid'];
        $registration_date = $data['session']['employer_detail']['registration_date'];
        //getting next and previous jobs link STARTS
        $next_emp_id = $CI->employee_model->next_employee($emp_id, $logged_in_company_id, $logged_in_employer_id);
        $prev_emp_id = $CI->employee_model->previous_employee($emp_id, $logged_in_company_id, $logged_in_employer_id);
        $terminate_status = $CI->employee_model->fetch_terminated_status($emp_id);


        if (is_array($terminate_status) && sizeof($terminate_status) > 0) {
            $status = '';
            if ($terminate_status['employee_status'] == 1) {
                $status = 'Terminated Employee';
            } elseif ($terminate_status['employee_status'] == 2) {
                $status = 'Retired Employee';
            } elseif ($terminate_status['employee_status'] == 3) {
                $status = 'Deceased Employee';
            } elseif ($terminate_status['employee_status'] == 4) {
                $status = 'Active Employee Suspended';
            } elseif ($terminate_status['employee_status'] == 6) {
                $status = 'In-Active Employee';
            } elseif ($terminate_status['employee_status'] == 7) {
                $status = 'Active Employee On Leave';
            } elseif ($terminate_status['employee_status'] == 8) {
                $status = 'Active Employee';
            }
            $data['employee_terminate_status'] = $status;
        }
        if (!empty($next_emp_id)) {
            $next_id = $next_emp_id['sid'];
            $data['next_app'] = base_url() . "employee_profile/$next_id";
        } else {
            $data['next_app'] = base_url() . "employee_profile/" . $CI->employee_model->get_min_employee_id($logged_in_company_id, $logged_in_employer_id);
        }

        if (!empty($prev_emp_id)) {
            $prev_id = $prev_emp_id['sid'];
            $data['prev_app'] = base_url() . "employee_profile/$prev_id";
        } else {
            $data['prev_app'] = base_url() . "employee_profile/" . $CI->employee_model->get_max_employee_id($logged_in_company_id, $logged_in_employer_id);
        }

        $average_rating = $CI->emergency_contacts_model->getApplicantAverageRating($emp_id, 'employee', $registration_date);
        $data['applicant_rating'] = $average_rating;
        $data['applicant_average_rating'] = $average_rating;
        $data['applicant_extra_attachments'] = $CI->emergency_contacts_model->getApplicantExtraAttachments($emp_id, $logged_in_employer_id, 'employee'); //getting list of extra attachment files
        $data['company_background_check'] = checkCompanyAccurateCheck($logged_in_company_id); //getting Company accurate backgroud check
        $data['id'] = $emp_id;
        $data['user_id'] = $logged_in_company_id;
        $data['user_sid'] = $logged_in_company_id;
        $data['email'] = $data['session']['employer_detail']['email'];
        //check_access_permissions($security_details, 'dashboard', 'background_check'); // First Param: security array, 2nd param: redirect url, 3rd param: function name
        // check if reseme is uploaded
        if (empty($data['employer']['resume'])) {
            $data['employer']['resume_link'] = "javascript:void(0);";
            $data["resume_link_title"] = "No Resume found!";
        } else {
            $data['employer']['resume_link'] = AWS_S3_BUCKET_URL . $data['employer']['resume'];
            $data["resume_link_title"] = $data['employer']['resume'];
        }

        // check if cover letter is uploaded
        if (empty($data['employer']['cover_letter'])) {
            $data['employer']['cover_link'] = "javascript:void(0)";
            $data["cover_letter_title"] = "No Cover Letter found!";
        } else {
            $data['employer']['cover_link'] = AWS_S3_BUCKET_URL . $data['employer']['cover_letter'];
            $data["cover_letter_title"] = $data['employer']['cover_letter'];
        }

        return $data;
    }
}


if (!function_exists('generate_page_section_meta_array')) {

    function generate_page_section_meta_array($image, $video, $title, $tag_line, $content, $status, $show_video_or_image)
    {
        $dataToSave = array(
            'image' => $image,
            'video' => $video,
            'title' => $title,
            'tag_line' => $tag_line,
            'content' => $content, //htmlentities($content),
            'status' => $status,
            'show_video_or_image' => $show_video_or_image
        );

        return $dataToSave;
    }
}

if (!function_exists('merge_arrays_override_key_values')) {

    function merge_arrays_override_key_values($arrayOne = array(), $arrayTwo = array())
    {
        $result = array();

        foreach ($arrayOne as $key => $value) {
            $result[$key] = $value;
        }

        foreach ($arrayTwo as $key => $value) {
            if ($key === 'title' && !empty($value)) {
                $result[$key] = $value;
            } else if ($value != '' || $value != 0 || $value != null) {
                $result[$key] = $value;
            } else {
                if (array_key_exists($key, $arrayOne)) {
                    $result[$key] = $arrayOne[$key];
                }
            }
        }

        return $result;
    }
}


if (!function_exists('get_page_section_meta')) {

    function get_page_section_meta($companyId, $themeName, $pageName, $section)
    {
        $CI = &get_instance();
        $CI->db->select('meta_value');
        $CI->db->where('theme_name', $themeName);
        $CI->db->where('page_name', $pageName);
        $CI->db->where('company_id', $companyId);
        $CI->db->where('meta_key', $section);
        $Return = $CI->db->get('portal_themes_meta_data', 1)->result_array();

        if (!empty($Return)) {
            return unserialize($Return[0]['meta_value']);
        } else {
            return generate_page_section_meta_array('', '', '', '', '', 0, 'none', '');
        }
    }
}

if (!function_exists('put_file_on_aws')) {

    function put_file_on_aws($file_input_id)
    {
        if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == 'automotohr.local')
            return getS3DummyFileName($file_input_id, true);
        //
        require_once(APPPATH . 'libraries/aws/aws.php');
        if (isset($_FILES[$file_input_id]) && $_FILES[$file_input_id]['name'] != '') {
            $file = explode(".", $_FILES[$file_input_id]["name"]);
            $file_name = str_replace(" ", "-", $file[0]);
            $pictures = $file_name . '-' . generateRandomString(5) . '.' . $file[1];
            $aws = new AwsSdk();
            $aws->putToBucket($pictures, $_FILES[$file_input_id]['tmp_name'], AWS_S3_BUCKET_NAME);
            return $pictures;
        } else {
            return 'error';
        }
    }
}

if (!function_exists('upload_file_to_aws')) {

    function upload_file_to_aws($file_input_id, $company_sid, $document_name, $suffix = '', $bucket_name = AWS_S3_BUCKET_NAME, $key = NULL)
    {

        if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == 'automotohr.local')
            return getS3DummyFileName($file_input_id, true);

        $CI = &get_instance();
        if ($key !== NULL && isset($_FILES[$file_input_id]) && $_FILES[$file_input_id]['name'][$key] != '') {
            //
            $modify_file_name = modify_document_name($document_name, $_FILES[$file_input_id]["name"][$key], $company_sid, $suffix);
            //  
            $CI->load->library('aws_lib');
            //
            try {
                $options = [
                    'Bucket' => $bucket_name,
                    'Key' => $modify_file_name,
                    'Body' => file_get_contents($_FILES[$file_input_id]["tmp_name"][$key]),
                    'ACL' => 'public-read',
                    'ContentType' => $_FILES[$file_input_id]["type"][$key]
                ];
                //
                $CI->aws_lib->put_object($options);
                //
                return $modify_file_name;
            } catch (Exception $exception) {
                return 'error';
            }
            //
        } else if (isset($_FILES[$file_input_id]) && $_FILES[$file_input_id]['name'] != '') {
            $modify_file_name = modify_document_name($document_name, $_FILES[$file_input_id]["name"], $company_sid, $suffix);
            //
            $CI->load->library('aws_lib');
            //
            try {
                $options = [
                    'Bucket' => $bucket_name,
                    'Key' => $modify_file_name,
                    'Body' => file_get_contents($_FILES[$file_input_id]["tmp_name"]),
                    'ACL' => 'public-read',
                    'ContentType' => $_FILES[$file_input_id]["type"]
                ];
                //
                $CI->aws_lib->put_object($options);
                //
                return $modify_file_name;
            } catch (Exception $exception) {
                return 'error';
            }
        } else {
            return 'error';
        }
    }
}

if (!function_exists('modify_document_name')) {

    function modify_document_name($document_title, $document_name, $company_sid, $suffix)
    {
        $file_name = preg_replace('/[^A-Za-z0-9_]/', '_', pathinfo($document_title, PATHINFO_FILENAME));
        $file_name = strtolower(str_replace([$company_sid, $suffix, "pdf", "doc", "docx", "xls", "xlsx", "csv", "rtf"], "", $file_name));
        //
        $last_index_of_dot = strrpos($document_name, '.') + 1;
        $file_ext = substr($document_name, $last_index_of_dot, strlen($document_name) - $last_index_of_dot);
        //
        $modify_file_name = $company_sid . '_' . $suffix . '_' . generateRandomString(3) . '_' . $file_name . '.' . $file_ext;
        //
        return $modify_file_name;
    }
}


if (!function_exists('db_get_access_level_details')) {

    function db_get_access_level_details($sid = NULL, $access_level = NULL, $session = array())
    {
        check_user_status($sid);

        if ($sid != NULL) {
            $CI = &get_instance();
            $data = array();
            $data['session'] = sizeof($session) ? $session : $CI->session->userdata('logged_in');
            $employer_detail = $data['session']['employer_detail'];
            $access_level = $employer_detail['access_level'];
            $is_executive_admin = $employer_detail['is_executive_admin'];
            //
            if (
                $employer_detail['access_level_plus'] == 1
                // $employer_detail['pay_plan_flag'] == 1
            ) {
                $permissions[] = 'full_access';
                return $permissions;
            }

            if (isset($employer_detail['a_permissions'])) {
                return $employer_detail['a_permissions'];
            }

            if ($access_level == NULL) {
                $result = get_employer_company_data_by_employer_id($sid);
                $access_level = $result['employer_detail']['access_level'];
            }

            if (empty($access_level) || $access_level == NULL) {
                $access_level = 'Employee';
            }

            if ($is_executive_admin == 1) {
                if (!isset($employer_detail['executive_admin_access'])) {
                    $CI->db->select('executive_admin_sid');
                    $CI->db->where('logged_in_sid', $employer_detail['sid']);
                    $executive_user_data = $CI->db->get('executive_user_companies')->result_array();

                    if (!empty($executive_user_data)) {
                        $executive_user_sid = $executive_user_data[0]['executive_admin_sid'];

                        $CI->db->select('access_level');
                        $CI->db->where('sid', $executive_user_sid);
                        $access_level_data = $CI->db->get('executive_users')->result_array();

                        if (!empty($access_level_data)) {
                            $access_level = $access_level_data[0]['access_level'];
                            $data['session']['employer_detail']['executive_admin_access'] = $access_level;
                            $data['session']['employer_detail']['access_level'] = $access_level;
                            $CI->session->set_userdata('logged_in', $data['session']);
                        }
                    }
                }
            }

            if ($access_level != 'Admin') {
                $CI->db->select('permissions');
                $CI->db->where('access_level', $access_level);
                $security_access_level = $CI->db->get('security_access_level')->result_array();
                $permissions = array();
                $access_level_permissions = $security_access_level[0]['permissions'];

                if (!empty($access_level_permissions)) {
                    $allpermissions = unserialize($access_level_permissions);

                    foreach ($allpermissions as $permission) {
                        $permissions[] = strtolower($permission['function_name']);
                    }
                }
            } else {
                $permissions[] = 'full_access';
            }

            $data['session']['employer_detail']['a_permissions'] = $permissions;
            $CI->session->set_userdata('logged_in', $data['session']);

            return $permissions;
        }
    }
}


if (!function_exists('check_company_expiry')) {

    function check_company_expiry($company_sid = NULL)
    {
        if ($company_sid != NULL) {
            $CI = &get_instance();
            $companyData = $CI->db->select('expiry_date,activation_key')->where('sid', $company_sid)->get('users')->row_array();

            if ($companyData['expiry_date'] != NULL) {
                if ($companyData['expiry_date'] < date('Y-m-d H:i:s')) { // removed expiration check on request of Steven at 10th October 2016 // https://trello.com/c/5fJsVDtV/589-tried-to-manually-process-jim-butler-chevrolet-fenton-and-linn
                    //if compnay is expired place activation key against that company
                    //$CI->db->query("UPDATE `portal_employer` SET `status` = '0' WHERE `user_sid` = '" . $company_sid . "'"); //portal deactivate on expiry
                    if ($companyData['activation_key'] == NULL) {
                        $CI->load->model('dashboard_model');
                        $activation_key = random_key(24);
                        $updatedData = array('activation_key' => $activation_key);
                        $CI->dashboard_model->update_user($company_sid, $updatedData);
                    }
                    return false; // it is return false so that company does not redirects to expired page
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
if (!function_exists('date_add_days')) {

    function date_add_days($date, $days)
    {
        return strtotime($date . ' +' . $days . ' days');
    }
}
if (!function_exists('date_add_day')) {

    function date_add_day($date, $day)
    {
        return strtotime($date . ' +' . $day . ' day');
    }
}

if (!function_exists('date_add_weeks')) {

    function date_add_weeks($date, $weeks)
    {
        return strtotime($date . ' +' . $weeks . ' weeks');
    }
}

if (!function_exists('date_add_week')) {

    function date_add_week($date, $week)
    {
        return strtotime($date . ' +' . $week . ' week');
    }
}

if (!function_exists('date_add_months')) {

    function date_add_months($date, $months)
    {
        return strtotime($date . ' +' . $months . ' months');
    }
}

if (!function_exists('date_add_month')) {

    function date_add_month($date, $month)
    {
        return strtotime($date . ' +' . $month . ' month');
    }
}

if (!function_exists('date_add_years')) {

    function date_add_years($date, $years)
    {
        return strtotime($date . ' +' . $years . ' years');
    }
}

if (!function_exists('date_add_year')) {

    function date_add_year($date, $year)
    {
        return strtotime($date . ' +' . $year . ' year');
    }
}

if (!function_exists('date_subtract_days')) {

    function date_subtract_days($date, $days)
    {
        return strtotime($date . ' -' . $days . ' days');
    }
}

if (!function_exists('date_subtract_day')) {

    function date_subtract_day($date, $day)
    {
        return strtotime($date . ' -' . $day . ' day');
    }
}

if (!function_exists('date_subtract_weeks')) {

    function date_subtract_weeks($date, $weeks)
    {
        return strtotime($date . ' -' . $weeks . ' weeks');
    }
}

if (!function_exists('date_subtract_week')) {

    function date_subtract_week($date, $week)
    {
        return strtotime($date . ' -' . $week . ' week');
    }
}

if (!function_exists('date_subtract_months')) {

    function date_subtract_months($date, $months)
    {
        return strtotime($date . ' -' . $months . ' months');
    }
}

if (!function_exists('date_subtract_month')) {

    function date_subtract_month($date, $month)
    {
        return strtotime($date . ' -' . $month . ' month');
    }
}

if (!function_exists('date_subtract_years')) {

    function date_subtract_years($date, $years)
    {
        return strtotime($date . ' -' . $years . ' years');
    }
}

if (!function_exists('date_subtract_year')) {

    function date_subtract_year($date, $year)
    {
        return strtotime($date . ' -' . $year . ' year');
    }
}

if (!function_exists('check_expiry_date')) {

    function hasExpired($expiry_date, $currentDate = null)
    {
        if ($currentDate == null) {
            $currentDate = strtotime(date('Y-m-d H:i:s'));
        }

        if ($currentDate >= $expiry_date) {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('show_expiry_date_alert')) {

    function show_expiry_date_alert($alertStartDate, $alertEndDate, $expiryDate)
    {
        if ($expiryDate >= $alertStartDate && $expiryDate <= $alertEndDate) {
            return true;
        } else if ($expiryDate > $alertEndDate) {
            return false;
        } else if ($expiryDate < $alertStartDate) {
            return false;
        }
    }
}

if (!function_exists('db_get_enum_values')) {

    function db_get_enum_values($table, $field)
    {
        $CI = &get_instance();
        $type = $CI->db->query("SHOW COLUMNS FROM `" . $table . "` WHERE Field = '" . $field . "'")->row(0)->Type;
        //preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        //$enum = explode("', '", $matches[1]);
        //$enum = explode(',', $enum[0]);
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $enum = explode("','", $matches[1]);
        return $enum;
    }
}

if (!function_exists('db_get_company_users')) {

    function db_get_company_users($sid)
    {
        $CI = &get_instance();
        $CI->db->select('sid, first_name, last_name, username, access_level,email, is_primary_admin, job_title');
        $CI->db->where('parent_sid', $sid);
        $CI->db->where('is_executive_admin', 0);
        $CI->db->where('active', 1);
        $CI->db->order_by('concat(first_name,last_name)', 'ASC', false);
        return $CI->db->get('users')->result_array();
    }
}


if (!function_exists('check_access_permissions')) {

    function check_access_permissions($security_details, $reload_location, $function_name)
    {
        //if (in_array('full_access', $security_details) || in_array($function_name, $security_details)) {
        if (check_access_permissions_for_view($security_details, $function_name)) {
            // got access
        } else {
            $CI = &get_instance();
            $loggedin_info = $CI->session->userdata('logged_in');
            //
            $canAccessDocument = hasDocumentsAssigned($loggedin_info['employer_detail']);
            //
            $document_permissions = ["employee_management", "add_history_documents", "index"];
            if (in_array($function_name, $document_permissions) && $canAccessDocument) {
            } else {
                $CI = &get_instance();
                $CI->session->set_flashdata('message', SECURITY_PERMISSIONS_ERROR);
                redirect($reload_location, "location");
            }
        }
    }
}

if (!function_exists('check_access_permissions_for_view')) {

    function check_access_permissions_for_view($security_details, $function_names)
    {
        $my_return = false;

        if (is_array($function_names)) {
            foreach ($function_names as $function_name) {

                if (in_array('full_access', $security_details) || in_array($function_name, $security_details)) {
                    $my_return = true;
                    break;
                } else {
                    $my_return = false;
                }
            }
        } else {
            if (in_array('full_access', $security_details) || in_array($function_names, $security_details)) {
                $my_return = true;
            } else {
                $my_return = false;
            }
        }

        return $my_return;
    }
}

if (!function_exists('get_employer_company_data_by_employer_id')) {

    function get_employer_company_data_by_employer_id($employer_id)
    {
        $CI = &get_instance();
        $CI->db->select('*');
        $CI->db->from('users');
        $CI->db->where('sid', $employer_id);
        $CI->db->where('active', 1);
        $CI->db->limit(1);
        $emp_query = $CI->db->get();

        if ($emp_query->num_rows() == 1) {
            $employer = $emp_query->result_array();
            $CI->db->select('*');
            $CI->db->from('users');
            $CI->db->where('sid', $employer[0]['parent_sid']);
            $CI->db->limit(1);
            $company = $CI->db->get()->result_array();
            $data['employer_detail'] = $employer[0];
            $data['company_detail'] = $company[0];
            return $data;
        } else {
            return false;
        }
    }
}


if (!function_exists('uploadFile')) {

    function uploadFile($company_name, $destination, $fileInputId, $sizeLimit, $allowedFileFormats = array(), $overide_file_name = null)
    {
        if (base_url() == 'http://localhost/ahr/') {
            $destination = $destination . '\\' . clean($company_name) . '\\';
        } else {
            $destination = $destination . '/' . clean($company_name) . '/';
        }

        $error = '';

        if (isset($_POST['action']) && $_POST['action'] == 'upload_file') {
            if (!is_dir($destination)) {
                mkdir($destination);
            }

            $target_dir = $destination;
            $fileName = basename($_FILES[$fileInputId]["name"]);
            $temp = explode(".", $fileName);
            $newfilename = '';
            $newfileextension = '';

            if ($overide_file_name == null) {
                $newfilename = clean($temp[0]) . '-' . generateRandomString(5) . '.' . end($temp);
                $newfileextension = end($temp);
            } else {
                $newfilename = clean($overide_file_name) . '.' . end($temp);
                $newfileextension = end($temp);
            }

            $target_file = $target_dir . $newfilename;
            $uploadOk = 1;
            $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

            if (file_exists($target_file)) {
                unlink($target_file);
            }

            if ($_FILES[$fileInputId]["size"] > $sizeLimit) { //$error = "Sorry, your file is too large.";
                $error = "file_error";
                $uploadOk = 0;
            }

            if (!in_array($newfileextension, $allowedFileFormats)) { //$error = "Sorry, only " . implode(',', $allowedFileFormats) . " files are allowed.";
                $error = "file_error";
                $uploadOk = 0;
            }

            if ($uploadOk == 0) {
                return $error;
            } else {
                if (move_uploaded_file($_FILES[$fileInputId]["tmp_name"], $target_file)) {
                    return $target_file;
                } else { //$error = 'Sorry, there was an error uploading your file.';
                    $error = "file_error";
                    return $error;
                }
            }
        }
    }
}

if (!function_exists('check_username_password')) {

    function check_username_password($username, $password)
    {
        $CI = &get_instance();
        $CI->db->select('*');
        $CI->db->from('users');
        $CI->db->where('username', $username);
        $CI->db->where('password', MD5($password));
        $CI->db->where('active', 1);
        $CI->db->limit(1);
        $emp_query = $CI->db->get();

        if ($emp_query->num_rows() == 1) {
            $employer = $emp_query->result_array();
            $result = check_company_expiry($employer[0]['parent_sid']);

            if ($result == false) {
                return -1;
            } else {
                return $employer[0]['sid'];
            }
        } else {
            return 0;
        }
    }
}

if (!function_exists('clear_loggin_session')) {

    function clear_loggin_session()
    { //Logout current user and redirect to account expiry page.
        $CI = &get_instance();
        setcookie(STORE_NAME . "[username]", time() - 3600);
        setcookie(STORE_NAME . "[password]", time() - 3600);
        $CI->session->unset_userdata('logged_in');
        $CI->session->unset_userdata('coupon_data');
    }
}


if (!function_exists('check_user_status')) {

    function check_user_status($user_sid = NULL)
    {
        if ($user_sid == NULL) {
            redirect('login');
        }

        $CI = &get_instance();
        $CI->db->select('sid, parent_sid');
        $CI->db->from('users');
        $CI->db->where('sid', $user_sid);
        if (!$CI->session->userdata('user_id')) {
            $CI->db->where('active', 1);
            $CI->db->where('terminated_status', 0);
        }
        $CI->db->limit(1);
        $emp_query = $CI->db->get();

        if ($emp_query->num_rows() == 1) {
            $employer = $emp_query->result_array();
            $emp_query->free_result();

            if ($employer[0]['parent_sid'] > 0) { //if it is employer and have a company
                $CI->db->select('sid, parent_sid');
                $CI->db->from('users');
                $CI->db->where('sid', $employer[0]['parent_sid']);
                $CI->db->where('active', 1);
                $rows = $CI->db->count_all_results();

                if ($rows > 0) {
                    return true;
                } else {
                    clear_loggin_session();
                    $CI->session->set_flashdata('message', '<b>Error:</b> Your company account has been De-activated');
                    redirect('login');
                }
            } else { //if it is company only
                return true;
            }
        } else { // make a record that employer is forced to log out as his account is de-active
            $loggedin_info = $CI->session->userdata('logged_in');
            $activity_data = array();
            $activity_data['company_sid'] = $loggedin_info['company_detail']['sid'];
            $activity_data['employer_sid'] = $loggedin_info['employer_detail']['sid'];
            $activity_data['company_name'] = $loggedin_info['company_detail']['CompanyName'];
            $activity_data['employer_name'] = $loggedin_info['employer_detail']['first_name'] . ' ' . $loggedin_info['employer_detail']['last_name'];
            $activity_data['employer_access_level'] = $loggedin_info['employer_detail']['access_level'];
            $activity_data['module'] = 'Check User Status';
            $activity_data['action_performed'] = 'Forced Log out';
            $activity_data['action_year'] = date('Y');
            $activity_data['action_week'] = date('W');
            $activity_data['action_timestamp'] = date('Y-m-d H:i:s');
            $activity_data['action_status'] = '';
            $activity_data['action_url'] = current_url();
            $activity_data['employer_ip'] = getUserIP();
            $activity_data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
            $CI->db->insert('logged_in_activitiy_tracker', $activity_data);
            clear_loggin_session();
            $CI->session->set_flashdata('message', '<b>Error:</b> Your employer account has been De-activated');
            redirect('login');
        }
    }
}

if (!function_exists('get_employer_access_level')) {

    function get_employer_access_level($employer_sid)
    {
        if ($employer_sid != NULL) {
            $CI = &get_instance();
            $CI->db->select('access_level');
            $CI->db->from('users');
            $CI->db->where('sid', $employer_sid);
            $CI->db->where('active', 1);
            $CI->db->limit(1);
            $emp_query = $CI->db->get();

            if ($emp_query->num_rows() == 1) {
                $employer = $emp_query->result_array();
                return $employer[0]['access_level'];
            }
        }
    }
}

if (!function_exists('get_order_response')) {

    function get_order_response($package_id)
    {
        $CI = &get_instance();
        if ($package_id != NULL) {
            $url = 'https://validusuat.accuratebackground.com/order/get/' . $package_id;
            $result = CallAPI('GET', $url);
            //            $result = '{"orderInfo":{"referenceCode1":"Egenie Next Web Solutions","referenceCode2":"Haseeb Saif","referenceCode3":"intactwebsolutions.com","referenceCode4":"Your billing code 4","referenceCode5":"Your billing code 5","searchId":"33095152","packageId":"Y5811249","packageTitle":"Basic Package","compCode":"AUTOHR","orderDate":"2016-02-11","requestor":{"email":"steven@automohr.com","name":{"firstname":"","lastname":""}},"positionLocation":"","candidate":{"name":{"firstname":"Mohammad","lastname":"Muzammil","middlename":""},"aka":[],"dateOfBirth":"","ssn":"","governmentId":{"countryCode":"","type":"","number":""},"contact":{"email":"mmuzammil@egenienext.com","phone":"03054581930"},"address":{"street":"","street2":"","city":"","region":"","country":"United States","postalCode":""}},"convicted":"False","convictionDetails":"","notes":"Search back 7 yrs. \r\nsome optional text goes here"},"orderStatus":{"status":"DRAFT","result":"N/A","percentageComplete":"0","completedDate":"","notes":"","summary":[]},"version":"1.0"}';
            return $result;
        }
    }
}
if (!function_exists('CallAPI')) {

    function CallAPI($method, $url)
    {
        $data = false;
        $curl = curl_init();

        switch ($method) {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);

                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_PUT, 1);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }

        // Optional Authentication:
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

        //Live Credentials
        curl_setopt($curl, CURLOPT_USERPWD, "AUTOHR_API:MChdJV71");

        //Testing Credentials
        // curl_setopt($curl, CURLOPT_USERPWD, "AUTOHR_UAT:BD9A4F3A38");

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }
}

if (!function_exists('db_get_security_access_details')) {

    function db_get_security_access_details()
    {
        $CI = &get_instance();
        $CI->db->select('*');
        $result = $CI->db->get('security_access_level')->result_array();

        if (count($result) > 0) {
            return $result;
        } else {
            return 0;
        }
    }
}

if (!function_exists('db_get_admin_access_level_details')) {

    function db_get_admin_access_level_details($sid = NULL)
    {
        if ($sid != NULL) {
            $CI = &get_instance();
            $CI->db->select('group_id');
            $CI->db->where('user_id', $sid);
            $result_group = $CI->db->get('administrator_users_groups')->result_array();
            $group_sid = '';

            if (count($result_group) > 0) {
                $group_sid = $result_group[0]['group_id'];
            }

            if ($group_sid != '') {
                $CI->db->select('name, permissions');
                $CI->db->where('id', $group_sid);
                $result = $CI->db->get('administrator_groups')->result_array();

                if (count($result) > 0) {
                    $result = $result[0];
                    $group_name = $result['name'];
                    $group_permissions = $result['permissions'];

                    if ($group_name == 'admin') {
                        $permissions[] = 'full_access';
                    } else {
                        $permissions = array();

                        if (!empty($group_permissions)) {
                            $allpermissions = unserialize($group_permissions);
                            foreach ($allpermissions as $permission) {
                                $permissions[] = strtolower($permission['function_name']);
                            }
                        } else {
                            $permissions[] = 'no_access';
                        }
                    }
                } else {
                    $permissions[] = 'no_access';
                }

                return $permissions;
            }
        }
    }
}


if (!function_exists('db_get_all_active_companies')) {

    function db_get_all_active_companies()
    {
        $CI = &get_instance();
        $result = $CI->db->query("SELECT `sid` FROM `users` WHERE `parent_sid` = '0' AND `active` = '1' AND (`expiry_date` > '2016-04-20 13:26:27' OR `expiry_date` IS NULL)")->result_array();

        if (count($result) > 0) {
            $data = array();

            foreach ($result as $r) {
                $data[] = $r['sid'];
            }

            return $data;
        } else {
            return 0;
        }
    }
}

if (!function_exists('generate_ics_file_for_event')) {

    function generate_ics_file_for_event($destination, $event_sid, $is_update = false, $user_id = 0, $user_type = 'extrainterviewer', $user_name = '', $user_email = '')
    {
        try {
            $CI = &get_instance();
            $CI->db->select('portal_schedule_event.*');
            $CI->db->select('users.email as creator_email');
            $CI->db->select('users.first_name as creator_fname');
            $CI->db->select('users.last_name as creator_lname');
            $CI->db->where('portal_schedule_event.sid', $event_sid);
            $CI->db->join('users', 'users.sid = portal_schedule_event.employers_sid', 'left');
            $eventData = $CI->db->get('portal_schedule_event')->result_array();
            $eventData = $eventData[0];
            if (!empty($eventData)) {
                //
                $CI->load->model('calendar_model', 'cm');
                // Get user timezone
                $user_details = array();
                if ($user_type == 'applicant') {
                    $user_details = $CI->cm->get_applicant_detail($user_id);
                    if (!empty($eventData['event_timezone'])) {
                        $user_details['timezone'] = $eventData['event_timezone'];
                    }
                } else if ($user_type == 'employee' || $user_type == 'interviewer' || $user_type == 'personal') {
                    $user_details = $CI->cm->get_employee_detail($user_id);
                } else {
                    $user_details = $CI->cm->get_employee_detail($eventData['companys_sid']);
                    if (!empty($eventData['event_timezone'])) {
                        $user_details['timezone'] = $eventData['event_timezone'];
                    }
                }

                if (empty($user_details['timezone'])) {
                    $user_details['timezone'] = $CI->cm->get_employee_detail($eventData['companys_sid'])['timezone'];
                }
                if (empty($user_details['timezone'])) {
                    $user_details['timezone'] = STORE_DEFAULT_TIMEZONE_ABBR;
                }

                $dateFormat = 'Ymd\THis';
                $company_id = $eventData['companys_sid'];
                $contact_name = $eventData['creator_fname'] . ' ' . $eventData['creator_lname'];
                $CI->db->select('first_name');
                $CI->db->select('last_name');
                $CI->db->select('email');
                $CI->db->where_in('sid', explode(',', $eventData['interviewer']));
                $participants = $CI->db->get('users')->result_array();
                $CI->db->where('user_sid', $company_id);
                $portal_info = $CI->db->get('portal_employer')->result_array();
                $portal_info = $portal_info[0];
                $company_timezone = $portal_info['company_timezone'];
                $CI->db->where('sid', $company_id);
                $company_detail = $CI->db->get('users')->result_array();

                if (!empty($company_detail)) {
                    $company_detail = $company_detail[0];
                    $company_name = $company_detail['CompanyName'];
                    //Event Data
                    $eTitle = $eventData['title'];
                    $eDescription = $eventData['description'];
                    $eUrl = $eventData['meetingURL'];
                    $eStartDateTime = $eventData['date'] . ' ' . $eventData['eventstarttime'];
                    $eEndDateTime = $eventData['date'] . ' ' . $eventData['eventendtime'];
                    $eStartDateTimeFull = date('Y-m-d H:i:s', strtotime($eStartDateTime));
                    $eEndDateTimeFull = date('Y-m-d H:i:s', strtotime($eEndDateTime));
                    if ($eStartDateTimeFull > $eEndDateTimeFull)
                        $eEndDateTime = date('Y-m-d h:iA', strtotime($eEndDateTimeFull . ' +1 day'));

                    $eLastModified = $eventData['created_on'];

                    $eLastModified = reset_datetime(array(
                        'datetime' => $eStartDateTime,
                        'from_format' => 'Y-m-d h:iA',
                        'format' => 'Y-m-d H:i:s',
                        'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                        'new_zone' => $user_details['timezone'],
                        '_this' => $CI
                    ));
                    $eStartDateTime = reset_datetime(array(
                        'datetime' => $eStartDateTime,
                        'from_format' => 'Y-m-d h:iA',
                        'format' => 'Y-m-d h:iA',
                        'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                        'new_zone' => $user_details['timezone'],
                        '_this' => $CI
                    ));

                    $eEndDateTime = reset_datetime(array(
                        'datetime' => $eEndDateTime,
                        'from_format' => 'Y-m-d h:iA',
                        'format' => 'Y-m-d h:iA',
                        'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                        'new_zone' => $user_details['timezone'],
                        '_this' => $CI
                    ));

                    $eAddress = $eventData['address'];
                    $eCategory = $eventData['category'];
                    $eStatus = $eventData['event_status'];
                    //$uniqueName = clean(ucwords($company_name)) . '-event-' . clean($event_sid) . '-' . generateRandomString(5);
                    $uniqueName = STORE_NAME . '-event-' . clean($event_sid);
                    $targetFileName = $uniqueName . '.ics';

                    if (base_url() == 'http://localhost/ahr/') {
                        $destination = $destination . '\\' . $company_id . '-' . clean($company_name) . '\\';
                    } else {
                        $destination = $destination . '/' . $company_id . '-' . clean($company_name) . '/';
                    }
                    //var_dump(file_exists($destination)) ;
                    if (!is_dir($destination)) {
                        //echo $destination;
                        mkdir($destination);
                    }

                    $targetFile = $destination . $targetFileName;
                    //Start File Creation
                    $myFile = fopen($targetFile, 'w+');
                    $eventStartDateTime = strtotime($eStartDateTime);
                    $eventEndDateTime = strtotime($eEndDateTime);
                    $eventLastModifiedDateTime = strtotime($eLastModified);
                    $fileContent = 'BEGIN:VCALENDAR' . PHP_EOL;

                    if ($is_update == false) {
                        $fileContent .= 'METHOD:PUBLISH' . PHP_EOL;
                    } else {
                        $fileContent .= 'METHOD:REQUEST' . PHP_EOL;
                    }

                    $fileContent .= 'VERSION:2.0' . PHP_EOL;
                    $fileContent .= 'PRODID:-//Automoto HR//Automoto HR//EN' . PHP_EOL;
                    $fileContent .= 'BEGIN:VEVENT' . PHP_EOL;
                    $fileContent .= 'ORGANIZER;CN="' . $contact_name . '":mailto:' . $eventData['creator_email'] . PHP_EOL;

                    if (!empty($participants)) {
                        foreach ($participants as $participant) {
                            $fileContent .= 'ATTENDEE;CN="' . $participant['first_name'] . ' ' . $participant['last_name'] . '";RSVP=TRUE:mailto:' . $participant['email'] . PHP_EOL;
                        }
                    }

                    $fileContent .= 'SUMMARY:' . $eCategory . ': ' . $eTitle . PHP_EOL;

                    if (!empty($eUrl) || $eUrl != null) {
                        $fileContent .= 'URL:' . $eUrl . PHP_EOL;
                    }

                    $fileContent .= 'UID:' . $uniqueName . PHP_EOL;
                    $fileContent .= 'STATUS:' . strtoupper($eStatus) . PHP_EOL; // Can be CONFIRMED CANCELED TENTATIVE
                    //$fileContent .= 'TZID:' . $company_timezone . PHP_EOL; //Implement w
                    //date_default_timezone_set($company_timezone);
                    $fileContent .= 'DTSTAMP:' . date($dateFormat) . PHP_EOL;
                    $fileContent .= 'DTSTART:' . date($dateFormat, $eventStartDateTime) . PHP_EOL;
                    //date_default_timezone_set($company_timezone);
                    $fileContent .= 'DTEND:' . date($dateFormat, $eventEndDateTime) . PHP_EOL;
                    $fileContent .= 'LAST-MODIFIED:' . date($dateFormat, $eventLastModifiedDateTime) . PHP_EOL;
                    $fileContent .= 'CREATED:' . date($dateFormat) . PHP_EOL;
                    $fileContent .= 'LOCATION:' . $eAddress . PHP_EOL;
                    $fileContent .= 'END:VEVENT' . PHP_EOL;
                    $fileContent .= 'END:VCALENDAR' . PHP_EOL;
                    fwrite($myFile, $fileContent);
                    fclose($myFile);
                    return $targetFile;
                } else {
                    return NULL;
                }
            } else {
                return NULL;
            }
        } catch (Exception $exception) {
            echo '<pre>';
            print_r($exception);
            exit;
        }
    }

    if (!function_exists('delete_file_aws')) {

        function delete_file_aws($filename)
        {
            $bucket = AWS_S3_BUCKET_NAME;
            require_once(APPPATH . 'libraries/aws/aws.php');
            $aws = new AwsSdk();

            if (!empty($filename)) {
                $aws->deleteObj($filename, $bucket);
            }
        }
    }
    if (!function_exists('get_all_emoloyee_by_company')) {

        function get_all_emoloyee_by_company($company_sid)
        {
            $CI = &get_instance();
            $CI->db->select('sid');
            $CI->db->from('users');
            $CI->db->where('parent_sid', $company_sid);
            $result = $CI->db->get()->result_array();

            if (!empty($result)) {
                foreach ($result as $key => $emp) {
                    $ids[$key] = $emp['sid'];
                }
            }

            $ids[$key + 1] = $company_sid;
            return $ids;
        }
    }
}

if (!function_exists('get_company_expiry_days')) {

    function get_company_expiry_days($company_expiry_date)
    {
        $now = strtotime(date('Y-m-d H:i:s')); // or your date as well
        $your_date = strtotime($company_expiry_date);
        $datediff = $your_date - $now;
        return floor($datediff / (60 * 60 * 24));
    }
}

if (!function_exists('is_admin')) {

    function is_admin($user_sid)
    {
        $CI = &get_instance();
        $data = $CI->session->userdata('logged_in');
        $accessLevel = $data['employer_detail']['access_level'];

        if ($accessLevel == 'Admin') {
            return true;
        } else {
            return false;
        }

        //        $CI->db->select('access_level');
        //        $CI->db->where('sid', $user_sid);
        //        $CI->db->limit(1);
        //        $result = $CI->db->get('users')->result_array();
        //
        //        if (!empty($result)) {
        //            $accessLevel = $result[0]['access_level'];
        //            if ($accessLevel == 'Admin') {
        //                return true;
        //            } else {
        //                return false;
        //            }
        //        } else {
        //            return false;
        //        }
    }
}

if (!function_exists('get_user_with_admin_access')) {

    function get_user_with_admin_access($company_id)
    {
        $CI = &get_instance();
        $CI->db->select('*');
        $CI->db->where('parent_sid', $company_id);
        $CI->db->where('access_level', 'Admin');
        $CI->db->where('is_executive_admin', 0);
        $result = $CI->db->get('users')->result_array();

        if (!empty($result)) {
            return $result;
        } else {
            return array();
        }
    }
}


if (!function_exists('log_and_sendEmail')) {
    function log_and_sendEmail($from, $to, $subject, $body, $senderName, $temp_id = "nil")
    {
        $CI = &get_instance();
        if (empty($to) || $to == NULL)
            return 0;
        //
        $emailData = array(
            'date' => date('Y-m-d H:i:s'),
            'subject' => $subject,
            'email' => $to,
            'message' => $body,
            'username' => $senderName,
            'temp_id' => $temp_id,
            'temp_data' => json_encode([
                'SCRIPT_FILENAME' => $_SERVER['SCRIPT_FILENAME'],
                'REQUEST_URI' => $_SERVER['REQUEST_URI'],
                'argv' => $_SERVER['argv']
            ])
        );
        //
        save_email_log_common($emailData);
        //
        if (base_url() != STAGING_SERVER_URL)
            sendMail($from, $to, $subject, $body, $senderName);
    }
}

if (!function_exists('get_all_admin_email_addresses')) {

    function get_all_admin_email_addresses($company_sid)
    {
        $CI = &get_instance();
        $CI->db->select('email');
        $CI->db->where('parent_sid', $company_sid);
        $CI->db->where('access_level', 'Admin');
        $CI->db->where('is_executive_admin', 0);
        $myData = $CI->db->get('users')->resul_array();
        $myReturn = array();

        foreach ($myData as $row) {
            $myReturn[] = $row['email'];
        }

        return $myReturn;
    }
}

if (!function_exists('get_all_admin_users_profiles')) {

    function get_all_admin_users_profiles($company_sid)
    {
        $CI = &get_instance();
        $CI->db->select('*');
        $CI->db->where('parent_sid', $company_sid);
        $CI->db->where('access_level', 'Admin');
        $CI->db->where('is_executive_admin', 0);
        return $CI->db->get('users')->result_array();
    }
}

if (!function_exists('get_job_approval_module_status')) {

    function get_job_approval_module_status($company_sid)
    {
        $CI = &get_instance();
        $CI->db->select('has_job_approval_rights');
        $CI->db->from('users');
        $CI->db->where('sid', $company_sid);
        $CI->db->limit(1);
        $myData = $CI->db->get()->result_array();

        if (!empty($myData)) {
            return $myData[0]['has_job_approval_rights'];
        } else {
            return 0;
        }
    }
}

if (!function_exists('get_applicant_approval_module_status')) {

    function get_applicant_approval_module_status($company_sid)
    {
        $CI = &get_instance();
        $CI->db->select('has_applicant_approval_rights');
        $CI->db->from('users');
        $CI->db->where('sid', $company_sid);
        $CI->db->limit(1);
        $myData = $CI->db->get()->result_array();

        if (!empty($myData)) {
            return $myData[0]['has_applicant_approval_rights'];
        } else {
            return 0;
        }
    }
}

if (!function_exists('is_leap_year')) {

    function is_leap_year($year = NULL)
    {

        if (is_numeric($year)) {
            return checkdate(2, 29, (int) $year);
        } else {
            return false;
        }
    }
}

if (!function_exists('get_administrator_user_info')) {

    function get_administrator_user_info($id)
    {
        $CI = &get_instance();
        $CI->db->select('*');
        $CI->db->where('id', $id);
        $data = $CI->db->get('administrator_users')->result_array();

        if (!empty($data)) {
            $data = $data[0];
        }

        return $data;
    }
}

//Stackoverflow function to convert amount to words
if (!function_exists('convert_number_to_words')) {

    function convert_number_to_words($number)
    {
        $hyphen = '-';
        $conjunction = ' and ';
        $separator = ', ';
        $negative = 'negative ';
        $decimal = ' point ';
        $dictionary = array(
            0 => 'zero',
            1 => 'one',
            2 => 'two',
            3 => 'three',
            4 => 'four',
            5 => 'five',
            6 => 'six',
            7 => 'seven',
            8 => 'eight',
            9 => 'nine',
            10 => 'ten',
            11 => 'eleven',
            12 => 'twelve',
            13 => 'thirteen',
            14 => 'fourteen',
            15 => 'fifteen',
            16 => 'sixteen',
            17 => 'seventeen',
            18 => 'eighteen',
            19 => 'nineteen',
            20 => 'twenty',
            30 => 'thirty',
            40 => 'fourty',
            50 => 'fifty',
            60 => 'sixty',
            70 => 'seventy',
            80 => 'eighty',
            90 => 'ninety',
            100 => 'hundred',
            1000 => 'thousand',
            1000000 => 'million',
            1000000000 => 'billion',
            1000000000000 => 'trillion',
            1000000000000000 => 'quadrillion',
            1000000000000000000 => 'quintillion'
        );

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }

        if ($number < 0) {
            return $negative . convert_number_to_words(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens = ((int) ($number / 10)) * 10;
                $units = $number % 10;
                $string = $dictionary[$tens];

                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }

                break;
            case $number < 1000:
                $hundreds = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];

                if ($remainder) {
                    $string .= $conjunction . convert_number_to_words($remainder);
                }

                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];

                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= convert_number_to_words($remainder);
                }

                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();

            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }

            $string .= implode(' ', $words);
        }

        return $string;
    }
}


if (!function_exists('activate_features_after_payment')) {

    function activate_features_after_payment($company_sid, $package_sid, $number_of_rooftops = 1)
    {
        $CI = &get_instance();
        $CI->db->where('sid', $package_sid);
        $package = $CI->db->get('products')->result_array();

        if (!empty($package)) {
            $package = $package[0];
            $includes_facebook_api = $package['includes_facebook_api'];
            $includes_deluxe_theme = $package['includes_deluxe_theme'];
            $expiry_days = $package['expiry_days'];


            if ($includes_facebook_api == 1) {  //Update Facebook API Status
                $CI->db->select('*');
                $CI->db->where('company_sid', $company_sid);
                $data_row = $CI->db->get('facebook_configuration')->result_array();

                if (!empty($data_row)) {
                    $dataToSave = array();
                    $dataToSave['purchased'] = 1;
                    //$dataToSave['purchase_date'] = date('Y-m-d H:i:s');
                    $CI->db->where('company_sid', $company_sid);
                    $CI->db->update('facebook_configuration', $dataToSave);
                } else {
                    $dataToSave = array();
                    $dataToSave['purchased'] = 1;
                    $dataToSave['company_sid'] = $company_sid;
                    //$dataToSave['purchase_date'] = date('Y-m-d H:i:s');
                    $CI->db->insert('facebook_configuration', $dataToSave);
                }
            }

            //Update Theme 4 Status
            if ($includes_deluxe_theme == 1) {
                $CI->db->select('*');
                $CI->db->where('user_sid', $company_sid);
                $CI->db->where('theme_name', 'theme-4');
                $data_row = $CI->db->get('portal_themes')->result_array();

                if (!empty($data_row)) {
                    $dataToSave = array();
                    $dataToSave['purchased'] = 1;
                    //$dataToSave['purchase_date'] = date('Y-m-d H:i:s');
                    $CI->db->where('user_sid', $company_sid);
                    $CI->db->where('theme_name', 'theme-4');
                    $CI->db->update('portal_themes', $dataToSave);
                } else {
                    $dataToSave = array();
                    $dataToSave['purchased'] = 1;
                    $dataToSave['user_sid'] = $company_sid;
                    $dataToSave['theme_name'] = 'theme-4';
                    //$dataToSave['purchase_date'] = date('Y-m-d H:i:s');
                    $CI->db->insert('portal_themes', $dataToSave);
                }
            }

            if ($package['product_type'] == 'development-fee') {
                //Update Development Fee
                $CI->db->where('sid', $company_sid);
                $dataToSave = array();
                $dataToSave['development_fee'] = $package['price'] * $number_of_rooftops;
                $CI->db->update('users', $dataToSave);
            }

            //Activate Portal
            $dataToSave = array();
            $dataToSave['status'] = 1;
            $CI->db->where('user_sid', $company_sid);
            $CI->db->update('portal_employer', $dataToSave);
            //Disable Trial Period
            $dataToSave = array();
            $dataToSave['status'] = 'disabled';
            $CI->db->where('company_sid', $company_sid);
            $CI->db->update('trial_period', $dataToSave);
            $dataToSave = array();
            $dataToSave['operation_performed'] = 'trial_ended_by_package';
            $dataToSave['operation_datetime'] = date('Y-m-d H:i:s');
            $dataToSave['company_sid'] = $company_sid;
            $CI->db->insert('trial_period_histroy', $dataToSave);

            if ($expiry_days > 0) {
                $expiry_date = date_add_days(date('Y-m-d h:i:s'), $expiry_days);
                $expiry_date = date('Y-m-d h:i:s', $expiry_date);

                if ($package['product_type'] == 'facebook-api') {
                    $dataToSave = array();
                    $CI->db->where('company_sid', $company_sid);
                    $dataToSave['expiry_date'] = $expiry_date;
                    $CI->db->update('facebook_configuration', $dataToSave);
                } elseif ($package['product_type'] == 'development-fee') {
                    //do nothing
                } elseif ($package['product_type'] == 'enterprise-theme') {
                    //To be handled
                } elseif ($package['product_type'] == 'account-package') {
                    $dataToSave = array();
                    $CI->db->where('sid', $company_sid);
                    $dataToSave['expiry_date'] = $expiry_date;
                    $CI->db->update('users', $dataToSave);
                    //Update Package Detail to Users Table
                    Update_package_for_company_after_payment($company_sid, $package['sid'], $number_of_rooftops, $package['maximum_number_of_employees']);
                    //Activate Portal
                    $dataToSave = array();
                    $dataToSave['status'] = 1;
                    $CI->db->where('user_sid', $company_sid);
                    $CI->db->update('portal_employer', $dataToSave);
                } else {
                    //Handle Default Case
                }
            }
        }
    }
}

if (!function_exists('Update_package_for_company_after_payment')) {

    function Update_package_for_company_after_payment($company_sid, $package_sid, $number_of_rooftops, $number_of_employees)
    {
        $CI = &get_instance();
        $dataToSave = array();
        $dataToSave['account_package_sid'] = $package_sid;
        $dataToSave['number_of_rooftops'] = $number_of_rooftops;
        $dataToSave['maximum_number_of_employees'] = $number_of_employees;
        $CI->db->where('sid', $company_sid);
        $CI->db->update('users', $dataToSave);
    }
}

if (!function_exists('activate_invoice_features')) {

    function activate_invoice_features($company_sid, $invoice_sid)
    {
        $CI = &get_instance();
        $CI->load->model('admin_invoices_model');
        //Mark Invoice as Paid
        $CI->admin_invoices_model->Update_admin_invoice_payment_status($invoice_sid, 'paid');
        //Activate Features
        $invoice_items = $CI->admin_invoices_model->Get_admin_invoice_items($invoice_sid);

        if (!empty($invoice_items)) {
            foreach ($invoice_items as $item) {
                activate_features_after_payment($company_sid, $item['item_sid'], $item['number_of_rooftops']);
            }
        }
    }
}

if (!function_exists('db_save_credit_card')) {

    function db_save_credit_card($company_sid, $employer_sid, $cc_id, $cc_number, $cc_type, $expire_month, $expire_year, $merchant_id, $cc_state)
    {
        $CI = &get_instance();
        $carddata = array();
        $carddata['id'] = $cc_id;
        $carddata['number'] = $cc_number;
        $carddata['type'] = $cc_type;
        $carddata['expire_month'] = $expire_month;
        $carddata['expire_year'] = $expire_year;
        $carddata['merchant_id'] = $merchant_id;
        $carddata['state'] = $cc_state;
        $current_date = date('Y-m-d H:i:s');
        //Check if Card Already Exists
        $CI->db->select('*');
        $CI->db->where('company_sid', $company_sid);
        $CI->db->where('number', $cc_number);
        $CI->db->where('type', $cc_type);
        $result = $CI->db->get('emp_cards')->result_array();

        if (empty($result)) { //Card Does Not Exists
            $carddata['company_sid'] = $company_sid;
            $carddata['employer_sid'] = $employer_sid;
            $carddata['date_added'] = $current_date;
            $CI->db->insert('emp_cards', $carddata);
        } else {
            $card_sid = $result[0]['sid'];
            //Card Exists
            $carddata['date_modified'] = $current_date;
            $CI->db->where('sid', $card_sid);
            $CI->db->update('emp_cards', $carddata);
        }
    }
}

if (!function_exists('db_get_first_admin_user')) {

    function db_get_first_admin_user($company_sid)
    {
        $CI = &get_instance();
        $CI->db->select('*');
        $CI->db->where('parent_sid', $company_sid);
        $CI->db->where('access_level', 'Admin');
        $CI->db->where('is_primary_admin', 1);
        $CI->db->where('is_executive_admin', 0);
        $CI->db->order_by('sid', 'ASC');
        $CI->db->limit(1);
        $result = $CI->db->get('users')->result_array();

        if (!empty($result)) {
            $result = $result[0];
            if ($result['sid'] == intval($company_sid) + 1) {
                return $result;
            } else {
                return array();
            }
        }
    }
}

if (!function_exists('generate_invoice_html')) {

    function generate_invoice_html($invoice_sid)
    {
        $CI = &get_instance();
        $CI->load->model('manage_admin/admin_invoices_model');
        $invoice_data = $CI->admin_invoices_model->Get_admin_invoice($invoice_sid, true);

        if (!empty($invoice_data)) {
            $created_by = get_administrator_user_info($invoice_data['created_by']);
            $admin_name = ucwords($created_by['first_name'] . ' ' . $created_by['last_name']);
            $invoice_data['created_by_name'] = $admin_name;
            $company_data = $CI->admin_invoices_model->Get_company_information($invoice_data['company_sid']);

            if (!empty($company_data)) {
                $company_data = $company_data[0];
                $country_name = db_get_country_name($company_data['Location_Country']);
                $country_name = $country_name['country_name'];
                $state_name = db_get_state_name_only($company_data['Location_State']);
                $company_data['state_name'] = $state_name;
                $company_data['country_name'] = $country_name;
            }
        }

        if (!empty($invoice_data)) {
            $invoice_html = '';
            $table = '';
            $th = '';
            $th_collection = '';

            $td = '';
            $td_collection = '';

            $tr = '';
            $tr_collection = '';


            $th = '<th colspan="7" style="background: #eeeeee; text-align: center; border-collapse: collapse; border:thin solid #000; padding: 5px; font-size: 26px;">Invoice # ' . $invoice_data['invoice_number'] . '</th>';
            $th_collection .= $th;

            $tr = '<tr>' . $th_collection . '</tr>';
            $tr_collection .= $tr;

            $th = '<th style="width:25%; vertical-align: top; background: #eeeeee; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . 'Client' . '</th>';
            $tr = $th;

            $td = '<td colspan="6" style="vertical-align: top; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px;"><strong>' . ucwords($invoice_data['company_name']) . '</strong><br />' . ucwords($company_data['Location_Address']) . ', ' . ucwords($company_data['Location_City']) . ', ' . ucwords($company_data['state_name']) . ', ' . ucwords($company_data['Location_ZipCode']) . ', ' . ucwords($company_data['country_name']) . '</td>';
            $tr .= $td;
            $tr = '<tr>' . $tr . '</tr>';

            $tr_collection .= $tr;

            $th = '<th style="width:25%; vertical-align: top; background: #eeeeee; text-align: left;  border-collapse: collapse; border:thin solid #000; padding: 5px;">' . 'Billing Address' . '</th>';
            $tr = $th;

            $td = '<td colspan="6" style="vertical-align: top; text-align: left;  border-collapse: collapse; border:thin solid #000; padding: 5px;">' . $company_data['full_billing_address'] . '</td>';
            $tr .= $td;

            $tr = '<tr>' . $tr . '</tr>';
            $tr_collection .= $tr;

            $th = '<th style="width:25%; vertical-align: top; background: #eeeeee; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . 'Phone' . '</th>';
            $tr = $th;

            $td = '<td colspan="6" style="vertical-align: top; text-align: left;  border-collapse: collapse; border:thin solid #000; padding: 5px;">' . $company_data['PhoneNumber'] . '</td>';
            $tr .= $td;

            $tr = '<tr>' . $tr . '</tr>';
            $tr_collection .= $tr;

            $th = '<th style="width:25%; vertical-align: top; background: #eeeeee; text-align: left;  border-collapse: collapse; border:thin solid #000; padding: 5px;">' . 'Invoice Date' . '</th>';
            $tr = $th;

            $td = '<td colspan="6" style="vertical-align: top; text-align: left;  border-collapse: collapse; border:thin solid #000; padding: 5px;"><strong style="font-size: 14px;">' . DateTime::createFromFormat('Y-m-d H:i:s', $invoice_data['created'])->format('m-d-Y H:i:s') . '</strong></td>';
            // $td = '<td colspan="6" style="vertical-align: top; text-align: left;  border-collapse: collapse; border:thin solid #000; padding: 5px;">' . reset_datetime(array( 'datetime' => $invoice_data['created'], '_this' => $CI)) . '</td>';
            $tr .= $td;

            $tr = '<tr>' . $tr . '</tr>';
            $tr_collection .= $tr;

            $th = '<th style="width:25%; vertical-align: top; background: #eeeeee; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . 'Payment Status' . '</th>';
            $tr = $th;

            $invoice_payment_status = $invoice_data['payment_status']; // color check over here
            $invoice_payment_color = 'color:red; font-size:20px; font-weight:bold;';

            if ($invoice_payment_status == 'paid') {
                $invoice_payment_color = 'color:#81b431; font-size:20px; font-weight:bold;';
            }

            $td = '<td class = "' . $invoice_data['payment_status'] . '" colspan="6" style="vertical-align: top; text-align: left; border-collapse: collapse; ' . $invoice_payment_color . ' border:thin solid #000; padding: 5px;">' . ucwords($invoice_data['payment_status']) . '</td>';
            $tr .= $td;

            $tr = '<tr>' . $tr . '</tr>';
            $tr_collection .= $tr;
            // Payment Method
            $th = '<th style="width:25%; vertical-align: top; background: #eeeeee; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . 'Payment Method' . '</th>';
            $tr = $th;

            $check_no = '';

            if ($invoice_data['check_number'] != NULL) {
                $check_no = ' #' . $invoice_data['check_number'];
            }

            $td = '<td colspan="6" style="vertical-align: top; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . ucwords($invoice_data['payment_method']) . $check_no . '</td>';
            $tr .= $td;
            $tr = '<tr>' . $tr . '</tr>';

            $tr_collection .= $tr;
            //here end
            $th = '<th style="width:25%; vertical-align: top; background: #eeeeee; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . 'Item Name' . '</th>' . '<th style="vertical-align: top; background: #eeeeee; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . 'Item Description' . '</th>' . '<th style="width:10%; vertical-align: top; background: #eeeeee; text-align: center; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . 'Item Price' . '</th>' . '<th style="vertical-align: top; background: #eeeeee; text-align: center; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . 'Rooftops' . '</th>' . '<th style="width:10%; vertical-align: top; background: #eeeeee; text-align: center; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . 'Totals' . '</th>';
            $tr = $th;
            $tr = '<tr>' . $tr . '</tr>';
            $tr_collection .= $tr;

            foreach ($invoice_data['items'] as $item) {
                $td = '<td style="width:25%; vertical-align: top; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . $item['item_name'] . '</td>' . '<td style="vertical-align: top; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . $item['item_description'] . '</td>' . '<td style="vertical-align: top; text-align: right; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . '$' . number_format($item['unit_price'], 2, '.', ',') . '</td>' . '<td style="vertical-align: top; text-align: center; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . $item['number_of_rooftops'] . '</td>' . '<td  style="vertical-align: top; text-align: right; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . '$' . number_format($item['quantity_total'], 2, '.', ',') . '</td>';
                $tr = $td;
                $tr = '<tr>' . $tr . '</tr>';
                $tr_collection .= $tr;
                $facebook_api_flag = false;

                if (count($invoice_data['items']) == 1) {
                    if ($item['includes_facebook_api'] == 1 && $item['item_name'] != 'Facebook Recruiting Application') {
                        $facebook_api_flag = true;
                    }
                }
            }

            if ($facebook_api_flag == true) {
                // $td = '<td style="width:25%; vertical-align: top; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . 'Facebook API' . '</td>' . '<td style="vertical-align: top; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . 'Fully Featured Facebook Recruiting Application' . '</td>' . '<td style="vertical-align: top; text-align: right; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . '$' . number_format(399, 2, '.', ',') . '</td>' . '<td style="vertical-align: top; text-align: center; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . ' ' . '</td>' . '<td  style="vertical-align: top; text-align: right; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . '$' . number_format(0, 2, '.', ',') . '</td>';
                // $tr = $td;
                // $tr = '<tr>' . $tr . '</tr>';
                // $tr_collection .= $tr;
            }

            $invoice_notes = '<strong>Invoice Notes:</strong>' . '<span>' . $invoice_data['invoice_description'] . '</span>';
            $past_due = '';

            if ($company_data['past_due'] == 'Yes') { // check if Past Due is enabled
                $past_due = '<br><div style="display:block; text-align: center;"><div style="display: inline-block; text-align: center; padding: 3px; font-size: 24px; margin: 20px 0; text-transform: uppercase; border:8px solid red; color:red; font-weight: bold; border-radius: 3px; -webkit-transform: rotate(-8deg); -moz-transform: rotate(-8deg); -o-transform: rotate(-8deg); -ms-transform: rotate(-8deg); transform: rotate(-8deg);"><div style="border:2px solid red; padding: 10px;">past due</div></div></div>';
            }

            $td = '<td style="border-left: thin solid #000; vertical-align: top; padding: 5px;" colspan="2" rowspan="3">' . $invoice_notes . $past_due . '</td>' . '<th style="vertical-align: top; background: #eeeeee; text-align: right; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . 'Subtotal' . '</th>' . '<td colspan="2" style="vertical-align: top; text-align: right; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . '$' . number_format($invoice_data['value'], 2, '.', ',') . '</td>';
            $tr = $td;
            $tr = '<tr>' . $tr . '</tr>';
            $tr_collection .= $tr;

            if ($invoice_data['is_discounted'] == 1) {
                $td = '<th style="vertical-align: top; background: #eeeeee; text-align: right; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . 'Discount' . '</th>' . '<td colspan="2" style="vertical-align: top; text-align: right; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . '( $' . number_format($invoice_data['discount_amount'], 2, '.', ',') . ' )</td>';
                $tr = $td;
                $tr = '<tr>' . $tr . '</tr>';
                $tr_collection .= $tr;

                $td = '<th style="vertical-align: top; background: #eeeeee; text-align: right; border-collapse: collapse; border:thin solid #000; padding: 5px; font-size: 18px;">' . 'Total' . '</th>' . '<td colspan="2" style="vertical-align: top; text-align: right; border-collapse: collapse; border:thin solid #000; padding: 5px; font-weight: bold; font-size: 18px;">' . '$' . number_format($invoice_data['total_after_discount'], 2, '.', ',') . '</td>';
                $tr = $td;
                $tr = '<tr>' . $tr . '</tr>';
                $tr_collection .= $tr;

                $th = '<th style="vertical-align: top; background: #eeeeee; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . 'US Dollars' . '</th>';
                $tr = $th;

                $td = '<td colspan="4" style="vertical-align: top; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px; font-weight: bold;">' . ucwords(convert_number_to_words($invoice_data['total_after_discount'])) . ' Only' . '</td>';
                $tr .= $td;

                $tr = '<tr>' . $tr . '</tr>';
                $tr_collection .= $tr;
            } else {
                $td = '<th style="vertical-align: top; background: #eeeeee; text-align: right; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . 'Discount' . '</th>' . '<td colspan="2" style="vertical-align: top; text-align: right; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . '($0.00)' . '</td>';
                $tr = $td;
                $tr = '<tr>' . $tr . '</tr>';
                $tr_collection .= $tr;

                $td = '<th style="vertical-align: top; background: #eeeeee; text-align: right; border-collapse: collapse; border:thin solid #000; padding: 5px; font-size: 18px;">' . 'Total' . '</th>' . '<td colspan="2" style="vertical-align: top; text-align: right; border-collapse: collapse; border:thin solid #000; padding: 5px; font-weight: bold; font-size: 18px;">' . '$' . number_format($invoice_data['value'], 2, '.', ',') . '</td>';
                $tr = $td;
                $tr = '<tr>' . $tr . '</tr>';
                $tr_collection .= $tr;

                $th = '<th style="vertical-align: top; background: #eeeeee; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . 'US Dollars' . '</th>';
                $tr = $th;

                $td = '<td colspan="4" style="vertical-align: top; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px; font-weight: bold;">' . ucwords(convert_number_to_words($invoice_data['value'])) . ' Only' . '</td>';
                $tr .= $td;

                $tr = '<tr>' . $tr . '</tr>';
                $tr_collection .= $tr;
            }

            // check if it is Check company then add check details
            if ($company_data['payment_type'] == 'Check') {
                $th = '<th style="width:25%; vertical-align: top; background: #eeeeee; text-align: left;  border-collapse: collapse; border:thin solid #000; padding: 5px;">' . 'Make Check payable to' . '</th>';
                $tr = $th;

                $td = '<td colspan="4" style="vertical-align: top; text-align: left;  border-collapse: collapse; border:thin solid #000; padding: 5px; font-size: 18px;">' . 'AutomotoSocial <br>30110 Boat Haven dr Canyon Lake Ca 92587 <br> Tel: (888) 794-0794' . '</td>';
                $tr .= $td;

                $tr = '<tr>' . $tr . '</tr>';
                $tr_collection .= $tr;
            }

            if (!isset($invoice_data['check_number']) && ($invoice_data['check_number'] != NULL) || $invoice_data['check_number'] != '') {

                $th = '<th style="vertical-align: top; background: #eeeeee; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . 'Check Number' . '</th>';
                $tr = $th;

                $td = '<td colspan="4" style="vertical-align: top; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px; font-weight: bold;">' . $invoice_data['check_number'] . '</td>';
                $tr .= $td;

                $tr = '<tr>' . $tr . '</tr>';
                $tr_collection .= $tr;
            }

            if (!isset($invoice_data['payment_description']) && ($invoice_data['payment_description'] != NULL) || $invoice_data['payment_description'] != '') {

                $th = '<th style="width:25%; vertical-align: top; background: #eeeeee; text-align: left;  border-collapse: collapse; border:thin solid #000; padding: 5px;">' . 'Payment Description' . '</th>';
                $tr = $th;

                $td = '<td colspan="4" style="vertical-align: top; text-align: left;  border-collapse: collapse; border:thin solid #000; padding: 5px; font-size: 14px;">' . ucwords($invoice_data['payment_description']) . '</td>';
                $tr .= $td;

                $tr = '<tr>' . $tr . '</tr>';
                $tr_collection .= $tr;
            }
            // Updated on: 27-06-2019
            if (isset($invoice_data['company_notes']) && ($invoice_data['company_notes'] != NULL || $invoice_data['company_notes'] != '')) {

                $th = '<th style="width:25%; vertical-align: top; background: #eeeeee; text-align: left;  border-collapse: collapse; border:thin solid #000; padding: 5px;">' . 'Note' . '</th>';
                $tr = $th;

                $td = '<td colspan="4" style="vertical-align: top; text-align: left;  border-collapse: collapse; border:thin solid #000; padding: 5px; font-size: 14px;">' . ucwords($invoice_data['company_notes']) . '</td>';
                $tr .= $td;

                $tr = '<tr>' . $tr . '</tr>';
                $tr_collection .= $tr;
            }

            $table = '<table width="100%">' . '<tbody>' . $tr_collection . '</tbody>' . '</table>';
            return $table;
        }
    }
}

if (!function_exists('generate_marketplace_invoice_html')) {

    function generate_marketplace_invoice_html($data = array(), $company_data = array())
    {
        $CI = &get_instance();
        $CI->load->model('manage_admin/invoice_model');

        if (!empty($data)) {
            if (!empty($company_data)) {
                $company_data = $company_data[0];
                $country_name = db_get_country_name($company_data['Location_Country']);
                $country_name = $country_name['country_name'];
                $state_name = db_get_state_name_only($company_data['Location_State']);
                $company_data['state_name'] = $state_name;
                $company_data['country_name'] = $country_name;
            }
        }

        if (!empty($data)) {


            $invoice_html = '';
            $table = '';
            $th = '';
            $th_collection = '';

            $td = '';
            $td_collection = '';

            $tr = '';
            $tr_collection = '';


            $th = '<th colspan="7" style="background: #eeeeee; text-align: center; border-collapse: collapse; border:thin solid #000; padding: 5px; font-size: 26px;">Invoice # ' . $data['sid'] . '</th>';
            $th_collection .= $th;

            $tr = '<tr>' . $th_collection . '</tr>';
            $tr_collection .= $tr;

            $th = '<th style="width:25%; vertical-align: top; background: #eeeeee; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . 'Client' . '</th>';
            $tr = $th;

            $td = '<td colspan="6" style="vertical-align: top; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px;"><strong>' . ucwords($company_data['CompanyName']) . '</strong><br /></td>';
            //            $td = '<td colspan="6" style="vertical-align: top; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px;"><strong>' . ucwords($company_data['CompanyName']) . '</strong><br />' . ucwords($company_data['Location_Address']) . ', ' . ucwords($company_data['Location_City']) . ', ' . ucwords($company_data['state_name']) . ', ' . ucwords($company_data['Location_ZipCode']) . ', ' . ucwords($company_data['country_name']) . '</td>';
            $tr .= $td;
            $tr = '<tr>' . $tr . '</tr>';

            $tr_collection .= $tr;

            //            $th = '<th style="width:25%; vertical-align: top; background: #eeeeee; text-align: left;  border-collapse: collapse; border:thin solid #000; padding: 5px;">' . 'Billing Address' . '</th>';
            //            $tr = $th;
            //
            //            $td = '<td colspan="6" style="vertical-align: top; text-align: left;  border-collapse: collapse; border:thin solid #000; padding: 5px;">' . $company_data['full_billing_address'] . '</td>';
            //            $tr .= $td;
            //
            //            $tr = '<tr>' . $tr . '</tr>';
            //            $tr_collection .= $tr;

            $th = '<th style="width:25%; vertical-align: top; background: #eeeeee; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . 'Phone' . '</th>';
            $tr = $th;

            $td = '<td colspan="6" style="vertical-align: top; text-align: left;  border-collapse: collapse; border:thin solid #000; padding: 5px;">' . $company_data['PhoneNumber'] . '</td>';
            $tr .= $td;

            $tr = '<tr>' . $tr . '</tr>';
            $tr_collection .= $tr;

            $th = '<th style="width:25%; vertical-align: top; background: #eeeeee; text-align: left;  border-collapse: collapse; border:thin solid #000; padding: 5px;">' . 'Invoice Date' . '</th>';
            $tr = $th;

            $td = '<td colspan="6" style="vertical-align: top; text-align: left;  border-collapse: collapse; border:thin solid #000; padding: 5px;">' . my_date_format($data['date']) . '</td>';
            $tr .= $td;

            $tr = '<tr>' . $tr . '</tr>';
            $tr_collection .= $tr;

            $th = '<th style="width:25%; vertical-align: top; background: #eeeeee; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . 'Payment Status' . '</th>';
            $tr = $th;

            $invoice_payment_status = $data['status']; // color check over here
            $invoice_payment_color = 'color:#81b431; font-size:20px; font-weight:bold;';

            if ($invoice_payment_status == 'paid') {
                $invoice_payment_color = 'color:#81b431; font-size:20px; font-weight:bold;';
            }

            $td = '<td class = "' . $data['status'] . '" colspan="6" style="vertical-align: top; text-align: left; border-collapse: collapse; ' . $invoice_payment_color . ' border:thin solid #000; padding: 5px;">' . ucwords($data['status']) . '</td>';
            $tr .= $td;

            $tr = '<tr>' . $tr . '</tr>';
            $tr_collection .= $tr;
            // Payment Method
            $th = '<th style="width:25%; vertical-align: top; background: #eeeeee; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . 'Payment Method' . '</th>';
            $tr = $th;

            $check_no = '';

            $td = '<td colspan="6" style="vertical-align: top; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . ucwords($data['payment_method']) . $check_no . '</td>';
            $tr .= $td;
            $tr = '<tr>' . $tr . '</tr>';

            $tr_collection .= $tr;
            //here end
            $th = '<th style="width:25%; vertical-align: top; background: #eeeeee; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px;"></th>' . '<th style="vertical-align: top; background: #eeeeee; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . 'Item Name' . '</th>' . '<th style="width:10%; vertical-align: top; background: #eeeeee; text-align: center; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . 'Item Quantity' . '</th>' . '<th style="width:10%; vertical-align: top; background: #eeeeee; text-align: center; border-collapse: collapse; border:thin solid #000; padding: 5px;"> Item Price</th>' . '<th style="width:10%; vertical-align: top; background: #eeeeee; text-align: center; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . 'Totals' . '</th>';
            $tr = $th;
            $tr = '<tr>' . $tr . '</tr>';
            $tr_collection .= $tr;

            $serialized_products = $data['serialized_products'];
            for ($i = 0; $i < count($serialized_products['custom_text']); $i++) {
                $td = '<td style="width:25%; vertical-align: top; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px;"></td>' . '<td style="vertical-align: top; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . db_get_product_name($serialized_products['products'][$i]) . '</td>' . '<td style="vertical-align: top; text-align: right; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . number_format($serialized_products['item_qty'][$i], 2, '.', ',') . '</td>' . '<td  style="vertical-align: top; text-align: right; border-collapse: collapse; border:thin solid #000; padding: 5px;"> $' . $serialized_products['item_price'][$i] . '</td>' . '<td  style="vertical-align: top; text-align: right; border-collapse: collapse; border:thin solid #000; padding: 5px;">$' . number_format($serialized_products['item_qty'][$i] * $serialized_products['item_price'][$i], 2, '.', ',') . '</td>';
                $tr = $td;
                $tr = '<tr>' . $tr . '</tr>';
                $tr_collection .= $tr;
            }

            $invoice_notes = '';
            //            $invoice_notes = '<strong>Invoice Notes:</strong>' . '<span>' . $data['invoice_description'] . '</span>';
            $past_due = '';

            if ($company_data['past_due'] == 'Yes') { // check if Past Due is enabled
                $past_due = '';
                //                $past_due = '<br><div style="display:block; text-align: center;"><div style="display: inline-block; text-align: center; padding: 3px; font-size: 24px; margin: 20px 0; text-transform: uppercase; border:8px solid red; color:red; font-weight: bold; border-radius: 3px; -webkit-transform: rotate(-8deg); -moz-transform: rotate(-8deg); -o-transform: rotate(-8deg); -ms-transform: rotate(-8deg); transform: rotate(-8deg);"><div style="border:2px solid red; padding: 10px;">past due</div></div></div>';
            }

            $td = '<td style="border-left: thin solid #000; vertical-align: top; padding: 5px;" colspan="2" rowspan="3">' . $invoice_notes . $past_due . '</td>' . '<th style="vertical-align: top; background: #eeeeee; text-align: right; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . 'Subtotal' . '</th>' . '<td colspan="2" style="vertical-align: top; text-align: right; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . '$' . number_format($data['sub_total'], 2, '.', ',') . '</td>';
            $tr = $td;
            $tr = '<tr>' . $tr . '</tr>';
            $tr_collection .= $tr;

            if ($data['total_discount'] > 0) {
                $td = '<th style="vertical-align: top; background: #eeeeee; text-align: right; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . 'Discount' . '</th>' . '<td colspan="2" style="vertical-align: top; text-align: right; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . '( $' . number_format($data['total_discount'], 2, '.', ',') . ' )</td>';
                $tr = $td;
                $tr = '<tr>' . $tr . '</tr>';
                $tr_collection .= $tr;

                $td = '<th style="vertical-align: top; background: #eeeeee; text-align: right; border-collapse: collapse; border:thin solid #000; padding: 5px; font-size: 18px;">' . 'Total' . '</th>' . '<td colspan="2" style="vertical-align: top; text-align: right; border-collapse: collapse; border:thin solid #000; padding: 5px; font-weight: bold; font-size: 18px;">' . '$' . number_format($data['total'], 2, '.', ',') . '</td>';
                $tr = $td;
                $tr = '<tr>' . $tr . '</tr>';
                $tr_collection .= $tr;

                $th = '<th style="vertical-align: top; background: #eeeeee; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . 'US Dollars' . '</th>';
                $tr = $th;

                $td = '<td colspan="4" style="vertical-align: top; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px; font-weight: bold;">' . ucwords(convert_number_to_words($data['total'])) . ' Only' . '</td>';
                $tr .= $td;

                $tr = '<tr>' . $tr . '</tr>';
                $tr_collection .= $tr;
            } else {
                $td = '<th style="vertical-align: top; background: #eeeeee; text-align: right; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . 'Discount' . '</th>' . '<td colspan="2" style="vertical-align: top; text-align: right; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . '($0.00)' . '</td>';
                $tr = $td;
                $tr = '<tr>' . $tr . '</tr>';
                $tr_collection .= $tr;

                $td = '<th style="vertical-align: top; background: #eeeeee; text-align: right; border-collapse: collapse; border:thin solid #000; padding: 5px; font-size: 18px;">' . 'Total' . '</th>' . '<td colspan="2" style="vertical-align: top; text-align: right; border-collapse: collapse; border:thin solid #000; padding: 5px; font-weight: bold; font-size: 18px;">' . '$' . number_format($data['total'], 2, '.', ',') . '</td>';
                $tr = $td;
                $tr = '<tr>' . $tr . '</tr>';
                $tr_collection .= $tr;

                $th = '<th style="vertical-align: top; background: #eeeeee; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . 'US Dollars' . '</th>';
                $tr = $th;

                $td = '<td colspan="4" style="vertical-align: top; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px; font-weight: bold;">' . ucwords(convert_number_to_words($data['total'])) . ' Only' . '</td>';
                $tr .= $td;

                $tr = '<tr>' . $tr . '</tr>';
                $tr_collection .= $tr;
            }

            // check if it is Check company then add check details
            if ($company_data['payment_type'] == 'Check') {
                $th = '<th style="width:25%; vertical-align: top; background: #eeeeee; text-align: left;  border-collapse: collapse; border:thin solid #000; padding: 5px;">' . 'Make Check payable to' . '</th>';
                $tr = $th;

                $td = '<td colspan="4" style="vertical-align: top; text-align: left;  border-collapse: collapse; border:thin solid #000; padding: 5px; font-size: 18px;">' . 'AutomotoSocial <br>30110 Boat Haven dr Canyon Lake Ca 92587 <br> Tel: (888) 794-0794' . '</td>';
                $tr .= $td;

                $tr = '<tr>' . $tr . '</tr>';
                $tr_collection .= $tr;
            }

            $table = '<table width="100%">' . '<tbody>' . $tr_collection . '</tbody>' . '</table>';
            return $table;
        }
    }
}

if (!function_exists('generate_commission_invoice_html')) {

    function generate_commission_invoice_html($invoice_sid)
    {
        $CI = &get_instance();
        $CI->load->model('manage_admin/admin_invoices_model');
        $invoice_data = $CI->admin_invoices_model->Get_commission_invoice($invoice_sid, true);

        if (!empty($invoice_data)) {
            $created_by = get_administrator_user_info($invoice_data['created_by']);
            $admin_name = ucwords($created_by['first_name'] . ' ' . $created_by['last_name']);
            $invoice_data['created_by_name'] = $admin_name;
            $company_data = $CI->admin_invoices_model->Get_company_information($invoice_data['company_sid']);

            if (!empty($company_data)) {
                $company_data = $company_data[0];
                $country_name = db_get_country_name($company_data['Location_Country']);
                $country_name = $country_name['country_name'];
                $state_name = db_get_state_name_only($company_data['Location_State']);
                $company_data['state_name'] = $state_name;
                $company_data['country_name'] = $country_name;
            }
        }

        if (!empty($invoice_data)) {
            $invoice_html = '';
            $table = '';
            $th = '';
            $th_collection = '';

            $td = '';
            $td_collection = '';

            $tr = '';
            $tr_collection = '';


            $th = '<th colspan="7" style="background: #eeeeee; text-align: center; border-collapse: collapse; border:thin solid #000; padding: 5px; font-size: 26px;">Invoice # ' . $invoice_data['invoice_number'] . '</th>';
            $th_collection .= $th;

            $tr = '<tr>' . $th_collection . '</tr>';
            $tr_collection .= $tr;

            $th = '<th style="width:25%; vertical-align: top; background: #eeeeee; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . 'Client' . '</th>';
            $tr = $th;

            $td = '<td colspan="6" style="vertical-align: top; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px;"><strong>' . ucwords($invoice_data['company_name']) . '</strong><br />' . ucwords($company_data['Location_Address']) . ', ' . ucwords($company_data['Location_City']) . ', ' . ucwords($company_data['state_name']) . ', ' . ucwords($company_data['Location_ZipCode']) . ', ' . ucwords($company_data['country_name']) . '</td>';
            $tr .= $td;
            $tr = '<tr>' . $tr . '</tr>';

            $tr_collection .= $tr;

            /*
              $th = '<th style="width:25%; vertical-align: top; background: #eeeeee; text-align: left;  border-collapse: collapse; border:thin solid #000; padding: 5px;">' . 'Email' . '</th>';
              $tr = $th;

              $td = '<td colspan="6" style="vertical-align: top; text-align: left;  border-collapse: collapse; border:thin solid #000; padding: 5px;">' . $invoice_data['company_email'] . '</td>';
              $tr .= $td;

              $tr = '<tr>' . $tr . '</tr>';
              $tr_collection .= $tr;
             */

            $th = '<th style="width:25%; vertical-align: top; background: #eeeeee; text-align: left;  border-collapse: collapse; border:thin solid #000; padding: 5px;">' . 'Billing Address' . '</th>';
            $tr = $th;

            $td = '<td colspan="6" style="vertical-align: top; text-align: left;  border-collapse: collapse; border:thin solid #000; padding: 5px;">' . $company_data['full_billing_address'] . '</td>';
            $tr .= $td;

            $tr = '<tr>' . $tr . '</tr>';
            $tr_collection .= $tr;

            $th = '<th style="width:25%; vertical-align: top; background: #eeeeee; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . 'Phone' . '</th>';
            $tr = $th;

            $td = '<td colspan="6" style="vertical-align: top; text-align: left;  border-collapse: collapse; border:thin solid #000; padding: 5px;">' . $company_data['PhoneNumber'] . '</td>';
            $tr .= $td;

            $tr = '<tr>' . $tr . '</tr>';
            $tr_collection .= $tr;

            /*
              $th = '<th style="width:25%; vertical-align: top; background: #eeeeee; text-align: left;  border-collapse: collapse; border:thin solid #000; padding: 5px;">' . 'Website' . '</th>';
              $tr = $th;

              $td = '<td colspan="4" style="vertical-align: top; text-align: left;  border-collapse: collapse; border:thin solid #000; padding: 5px;">' . $company_data['WebSite'] . '</td>';
              $tr .= $td;

              $tr = '<tr>' . $tr . '</tr>';
              $tr_collection .= $tr;
             */

            $th = '<th style="width:25%; vertical-align: top; background: #eeeeee; text-align: left;  border-collapse: collapse; border:thin solid #000; padding: 5px;">' . 'Invoice Date' . '</th>';
            $tr = $th;

            $td = '<td colspan="6" style="vertical-align: top; text-align: left;  border-collapse: collapse; border:thin solid #000; padding: 5px;">' . date('m-d-Y', strtotime(str_replace('-', '/', $invoice_data['created']))) . '</td>';
            $tr .= $td;

            $tr = '<tr>' . $tr . '</tr>';
            $tr_collection .= $tr;

            $th = '<th style="width:25%; vertical-align: top; background: #eeeeee; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . 'Payment Status' . '</th>';
            $tr = $th;

            $invoice_payment_status = $invoice_data['payment_status']; // color check over here
            $invoice_payment_color = 'color:red; font-size:20px; font-weight:bold;';

            if ($invoice_payment_status == 'paid') {
                $invoice_payment_color = 'color:#81b431; font-size:20px; font-weight:bold;';
            }

            $td = '<td class = "' . $invoice_data['payment_status'] . '" colspan="6" style="vertical-align: top; text-align: left; border-collapse: collapse; border:thin solid #000; ' . $invoice_payment_color . ' padding: 5px;">' . ucwords($invoice_data['payment_status']) . '</td>';
            $tr .= $td;

            $tr = '<tr>' . $tr . '</tr>';
            $tr_collection .= $tr;

            $th = '<th style="width:25%; vertical-align: top; background: #eeeeee; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . 'Item Name' . '</th>' . '<th style="vertical-align: top; background: #eeeeee; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . 'Item Description' . '</th>' . '<th style="width:10%; vertical-align: top; background: #eeeeee; text-align: center; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . 'Item Price' . '</th>' . '<th style="vertical-align: top; background: #eeeeee; text-align: center; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . 'Rooftops' . '</th>' . '<th style="width:10%; vertical-align: top; background: #eeeeee; text-align: center; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . 'Totals' . '</th>';
            $tr = $th;
            $tr = '<tr>' . $tr . '</tr>';
            $tr_collection .= $tr;

            foreach ($invoice_data['items'] as $item) {
                $td = '<td style="width:25%; vertical-align: top; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . $item['item_name'] . '</td>' . '<td style="vertical-align: top; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . $item['item_description'] . '</td>' . '<td style="vertical-align: top; text-align: right; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . '$' . number_format($item['unit_price'], 2, '.', ',') . '</td>' . '<td style="vertical-align: top; text-align: center; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . $item['number_of_rooftops'] . '</td>' . '<td  style="vertical-align: top; text-align: right; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . '$' . number_format($item['quantity_total'], 2, '.', ',') . '</td>';
                $tr = $td;
                $tr = '<tr>' . $tr . '</tr>';
                $tr_collection .= $tr;
                $facebook_api_flag = false;

                if (count($invoice_data['items']) == 1) {
                    if ($item['includes_facebook_api'] == 1 && $item['item_name'] != 'Facebook Recruiting Application') {
                        $facebook_api_flag = true;
                    }
                }
            }

            if ($facebook_api_flag == true) {
                // $td = '<td style="width:25%; vertical-align: top; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . 'Facebook API' . '</td>' . '<td style="vertical-align: top; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . 'Fully Featured Facebook Recruiting Application' . '</td>' . '<td style="vertical-align: top; text-align: right; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . '$' . number_format(399, 2, '.', ',') . '</td>' . '<td style="vertical-align: top; text-align: center; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . ' ' . '</td>' . '<td  style="vertical-align: top; text-align: right; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . '$' . number_format(0, 2, '.', ',') . '</td>';
                // $tr = $td;
                // $tr = '<tr>' . $tr . '</tr>';
                // $tr_collection .= $tr;
            }

            $invoice_notes = '<strong>Invoice Notes:</strong>' . '<span>' . $invoice_data['invoice_description'] . '</span>';
            $td = '<td style="border-left: thin solid #000; vertical-align: top; padding: 5px;" colspan="2" rowspan="3">' . $invoice_notes . '</td>' . '<th style="vertical-align: top; background: #eeeeee; text-align: right; border-collapse: collapse; border:thin solid #000; padding: 5px;" colspan="2">' . 'Subtotal' . '</th>' . '<td style="vertical-align: top; text-align: right; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . '$' . number_format($invoice_data['value'], 2, '.', ',') . '</td>';
            $tr = $td;
            $tr = '<tr>' . $tr . '</tr>';
            $tr_collection .= $tr;

            if ($invoice_data['is_discounted'] == 1) {
                //$td = '<td>' . ' ' . '</td>' . '<td>' . ' ' . '</td>' . '<th style="vertical-align: top; background: #eeeeee; text-align: right; border-collapse: collapse; border:thin solid #000; padding: 5px;" colspan="2">' . 'Discount %' . '</th>' . '<td style="vertical-align: top; text-align: right; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . $invoice_data['discount_percentage'] . '</td>';
                //$tr = $td;
                //$tr = '<tr>' . $tr . '</tr>';
                //$tr_collection .= $tr;
                $td = '<th style="vertical-align: top; background: #eeeeee; text-align: right; border-collapse: collapse; border:thin solid #000; padding: 5px;" colspan="2">' . 'Discount Amount' . '</th>' . '<td style="vertical-align: top; text-align: right; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . '$' . number_format($invoice_data['discount_amount'], 2, '.', ',') . '</td>';
                $tr = $td;
                $tr = '<tr>' . $tr . '</tr>';
                $tr_collection .= $tr;

                $td = '<th style="vertical-align: top; background: #eeeeee; text-align: right; border-collapse: collapse; border:thin solid #000; padding: 5px;" colspan="2">' . 'Total' . '</th>' . '<td style="vertical-align: top; text-align: right; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . '$' . number_format($invoice_data['total_after_discount'], 2, '.', ',') . '</td>';
                $tr = $td;
                $tr = '<tr>' . $tr . '</tr>';
                $tr_collection .= $tr;

                $th = '<th style="vertical-align: top; background: #eeeeee; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . 'US Dollars' . '</th>';
                $tr = $th;

                $td = '<td colspan="4" style="vertical-align: top; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . ucwords(convert_number_to_words($invoice_data['total_after_discount'])) . ' Only' . '</td>';
                $tr .= $td;

                $tr = '<tr>' . $tr . '</tr>';
                $tr_collection .= $tr;
            } else {
                //$td = '<td>' . ' ' . '</td>' . '<td>' . ' ' . '</td>' . '<th style="vertical-align: top; background: #eeeeee; text-align: right; border-collapse: collapse; border:thin solid #000; padding: 5px;" colspan="2">' . 'Discount %' . '</th>' . '<td style="vertical-align: top; text-align: right; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . '0' . '</td>';
                //$tr = $td;
                //$tr = '<tr>' . $tr . '</tr>';
                //$tr_collection .= $tr;

                $td = '<th style="vertical-align: top; background: #eeeeee; text-align: right; border-collapse: collapse; border:thin solid #000; padding: 5px;" colspan="2">' . 'Discount Amount' . '</th>' . '<td style="vertical-align: top; text-align: right; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . '$0.00' . '</td>';
                $tr = $td;
                $tr = '<tr>' . $tr . '</tr>';
                $tr_collection .= $tr;

                $td = '<th style="vertical-align: top; background: #eeeeee; text-align: right; border-collapse: collapse; border:thin solid #000; padding: 5px;" colspan="2">' . 'Total' . '</th>' . '<td style="vertical-align: top; text-align: right; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . '$' . number_format($invoice_data['value'], 2, '.', ',') . '</td>';
                $tr = $td;
                $tr = '<tr>' . $tr . '</tr>';
                $tr_collection .= $tr;

                $th = '<th style="vertical-align: top; background: #eeeeee; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . 'US Dollars' . '</th>';
                $tr = $th;

                $td = '<td colspan="4" style="vertical-align: top; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px;">' . ucwords(convert_number_to_words($invoice_data['value'])) . ' Only' . '</td>';
                $tr .= $td;

                $tr = '<tr>' . $tr . '</tr>';
                $tr_collection .= $tr;
            }

            $table = '<table width="100%">' . '<tbody>' . $tr_collection . '</tbody>' . '</table>';
            return $table;
        }
    }
}

if (!function_exists('generate_invoice_for_cron_processing')) {

    function generate_invoice_for_cron_processing($recurring_payment_sid, $admin_sid)
    {
        $CI = &get_instance();
        $CI->load->model('manage_admin/admin_invoices_model');
        $CI->load->model('manage_admin/recurring_payments_model');
        $CI->load->model('manage_admin/company_billing_contacts_model');
        $CI->load->model('cron_payments_model');
        $CI->load->model('manage_admin/marketing_agencies_model');
        $payment_params = $CI->recurring_payments_model->get_recurring_payment_record($recurring_payment_sid);

        if (!empty($payment_params)) {
            $payment_params = $payment_params[0];
            $company_sid = $payment_params['company_sid'];
            $discount_amount = $payment_params['discount_amount'];
            $total_after_discount = $payment_params['total_after_discount'];
            $cs_items = $payment_params['cs_item_ids'];
            $item_ids = explode(',', $cs_items);
            $rooftops = $payment_params['number_of_rooftops'];
            $item_id_to_number_of_rooftops = array();
            $item_id_to_quantity = array();

            foreach ($item_ids as $item_id) {
                $item_id_to_number_of_rooftops[intval($item_id)] = $rooftops;
                $item_id_to_quantity[intval($item_id)] = 1;
            }

            //Generate Invoice
            $invoice_sid = $CI->admin_invoices_model->Save_invoice($admin_sid, $company_sid, $item_ids, $item_id_to_number_of_rooftops, $item_id_to_quantity, 'automatic');
            //Insert Record for Cron Processing
            $CI->recurring_payments_model->insert_recurring_payment_process_record($recurring_payment_sid, $invoice_sid);
            //Update Discount
            $CI->admin_invoices_model->Update_admin_invoice_discount($invoice_sid, 0, $discount_amount, $total_after_discount);
            //Send Invoice In Email
            $test_email = 'mubashar.ahmed@egenienext.com';
            //Create Commission Invoice
            $commission_invoice_sid = $CI->admin_invoices_model->Save_commission_invoice($admin_sid, $company_sid, $item_ids, $item_id_to_number_of_rooftops, $item_id_to_quantity, 'automatic', 'super_admin');
            $secondary_invoice = 0;

            if (isset($commission_invoice_sid['secondary'])) {
                $secondary_invoice = $commission_invoice_sid['secondary'];
            }

            //Update Commission Invoice Sid in Admin Invoices Table
            $CI->admin_invoices_model->update_commission_invoice_sid($invoice_sid, $commission_invoice_sid['primary'], 'admin_invoice', $secondary_invoice);

            //Update Admin Invoice Sid in Commission Invoices Table
            $CI->admin_invoices_model->update_invoice_sid_in_commission_invoice($commission_invoice_sid['primary'], $invoice_sid, $secondary_invoice);

            //Update discount in Commission Invoice
            $CI->admin_invoices_model->Update_commission_invoice_discount($commission_invoice_sid['primary'], 0, $discount_amount, $total_after_discount);

            //Apply Discount on Commission
            $CI->admin_invoices_model->apply_discount_on_commission($commission_invoice_sid['primary']);

            //Re Calculate Commission
            $CI->marketing_agencies_model->recalculate_commission($commission_invoice_sid['primary']);

            if (isset($commission_invoice_sid['secondary'])) {
                //Update discount in Commission Invoice
                $CI->admin_invoices_model->Update_commission_invoice_discount($commission_invoice_sid['secondary'], 0, $discount_amount, $total_after_discount);

                //Apply Discount on Commission
                $CI->admin_invoices_model->apply_discount_on_commission($commission_invoice_sid['secondary']);

                //Re Calculate Commission
                $CI->marketing_agencies_model->recalculate_commission($commission_invoice_sid['secondary']);
            }

            //Send Invoice In Email
            //$company_details = $CI->cron_payments_model->get_company_information($company_sid);
            //$admin_details = $CI->cron_payments_model->get_company_information(intval($company_sid) + 1);
            $invoice_html = generate_invoice_html($invoice_sid);
            //mail($test_email, 'AutomtoHr Debug - Invoice Generated', 'Email Generated On : ' . date('Y-m-d H:i:s'));
            //Get All Company Billing Contacts
            $company_billing_contacts = $CI->company_billing_contacts_model->get_all_billing_contacts($company_sid);
            //Email to Steven
            $subject = STORE_NAME . ' - A new Invoice Generated!';
            $message_body = '';
            $message_body .= '<p>' . 'Dear Admin,' . '</p>';
            $message_body .= '<p>' . 'A new invoice has Been Automatically generated' . '</p>';
            $message_body .= '<p>' . 'Invoice Details are as Follows: ' . '</p>';
            $message_body .= '<p>' . $invoice_html . '</p>';
            $message_body .= '<p>' . STORE_NAME . '</p>';
            $message_body .= '<p>' . '**This is an automated email please do not reply.**' . '</p>';
            //Send Emails Through System Notifications Email - Start
            // disabled it on 3rd January 2019 as per Stevens Request
            /*$system_notification_emails = get_system_notification_emails('billing_and_invoice_emails');

            if (!empty($system_notification_emails)) {
                foreach ($system_notification_emails as $system_notification_email) {
                    log_and_sendEmail(FROM_EMAIL_ACCOUNTS, $system_notification_email['email'], $subject, $message_body, STORE_NAME);
                }
            } */
            //Send Emails Through System Notifications Email - End
            //log_and_sendEmail(FROM_EMAIL_NOTIFICATIONS, TO_EMAIL_STEVEN, $subject, $message_body, STORE_NAME);
            //Send to Company Billing Contacts
            if (!empty($company_billing_contacts)) {
                foreach ($company_billing_contacts as $company_billing_contact) {
                    $email = $company_billing_contact['email_address'];
                    $message_body = '';
                    $message_body .= '<p>' . 'Dear ' . ucwords($company_billing_contact['contact_name']) . '</p>';
                    $message_body .= '<p>' . 'A new invoice has Been Automatically generated' . '</p>';
                    $message_body .= '<p>' . 'Invoice Details are as Follows: ' . '</p>';
                    $message_body .= '<p>' . $invoice_html . '</p>';
                    $message_body .= '<p>' . STORE_NAME . '</p>';
                    $message_body .= '<p>' . '**This is an automated email please do not reply.**' . '</p>';
                    log_and_sendEmail(FROM_EMAIL_ACCOUNTS, $email, $subject, $message_body, STORE_NAME);
                }
            } else {
                //Email to Steven
                $subject = 'Urgent Invoice Issue';
                $message_body = '';
                $message_body .= '<p>' . 'Dear Steven,' . '</p>';
                $message_body .= '<p>' . 'This is an automated email generated by AHR "Recurring Invoices Module".' . '</p>';
                $message_body .= '<p>' . 'It is to inform you that system could not send this invoice to the client as there are no company contact details.' . '</p>';
                $message_body .= '<p>' . 'Kindly Add "Company Billing Contacts" from Super Admin.' . '</p>';
                $message_body .= '<p>' . 'Invoice Details are as Follows: ' . '</p>';
                $message_body .= '<p>' . $invoice_html . '</p>';
                $message_body .= '<p>' . STORE_NAME . '</p>';
                $message_body .= '<p>' . '**This is an automated email please do not reply.**' . '</p>';
                //log_and_sendEmail(FROM_EMAIL_NOTIFICATIONS, TO_EMAIL_STEVEN, $subject, $message_body, STORE_NAME);
                //Send Emails Through System Notifications Email - Start
                $system_notification_emails = get_system_notification_emails('billing_and_invoice_emails');

                if (!empty($system_notification_emails)) {
                    foreach ($system_notification_emails as $system_notification_email) {
                        log_and_sendEmail(FROM_EMAIL_ACCOUNTS, $system_notification_email['email'], $subject, $message_body, STORE_NAME);
                    }
                }
                //Send Emails Through System Notifications Email - End
            }

            /*
              if (!empty($admin_details)) {
              mail($test_email, 'AutomtoHr Debug - Admin Record Found', 'Email Generated On : ' . date('Y-m-d H:i:s'));
              //$company_details = $company_details[0];
              $admin_details = $admin_details[0];
              //Email to Steven
              $subject = STORE_NAME.' - A new Invoice Generated!';
              $message_body = '';

              $message_body .= '<p>' . 'Dear Admin,' . '</p>';
              $message_body .= '<p>' . 'A new invoice has Been Automatically generated' . '</p>';
              $message_body .= '<p>' . 'Invoice Details are as Follows: ' . '</p>';
              $message_body .= '<p>' . $invoice_html . '</p>';
              $message_body .= '<p>' . STORE_NAME . '</p>';
              $message_body .= '<p>' . '**This is an automated email please do not reply.**' . '</p>';
              log_and_sendEmail(FROM_EMAIL_NOTIFICATIONS, TO_EMAIL_STEVEN, $subject, $message_body, STORE_NAME);
              //Email to Company Admin
              $message_body = '';
              $message_body .= '<p>' . 'Dear ' . ucwords($admin_details['first_name'] . ' ' . $admin_details['last_name']) . '</p>';
              $message_body .= '<p>' . 'A new invoice has Been Automatically generated' . '</p>';
              $message_body .= '<p>' . 'Invoice Details are as Follows: ' . '</p>';
              $message_body .= '<p>' . $invoice_html . '</p>';
              $message_body .= '<p>' . STORE_NAME . '</p>';
              $message_body .= '<p>' . '**This is an automated email please do not reply.**' . '</p>';
              log_and_sendEmail(FROM_EMAIL_NOTIFICATIONS, $admin_details['email'], $subject, $message_body, STORE_NAME);
              }
             */

            if (base_url() != STAGING_SERVER_URL) {
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

                mail('mubashar.ahmed@egenienext.com', 'Automatic Invoice Generated' . date('Y-m-d H:i:s'), $invoice_html, $headers);
            }
        }
    }
}

if (!function_exists('get_credit_card')) {

    function get_credit_card($company_sid, $default = false)
    {
        $CI = &get_instance();
        $CI->db->select('*');
        $CI->db->where('company_sid', $company_sid);

        if ($default == true) {
            $CI->db->where('is_default', 1);
        } else {
            $CI->db->where('is_default', 0);
        }

        $CI->db->limit(1);
        $CI->db->order_by('sid', 'DESC');
        $cc = $CI->db->get('emp_cards')->result_array();

        if (!empty($cc)) {
            return $cc[0];
        } else {
            return array();
        }
    }
}


if (!function_exists('get_credit_cards')) {

    function get_credit_cards($company_sid)
    {
        $CI = &get_instance();
        $CI->db->select('*');
        $CI->db->where('company_sid', $company_sid);
        return $CI->db->get('emp_cards')->result_array();
    }
}


if (!function_exists('update_from_table')) {

    function update_from_table($table, $where, $dataToUpdate)
    {
        $CI = &get_instance();
        return $CI->db->where($where)->update($table, $dataToUpdate);
    }
}

if (!function_exists('log_and_send_templated_email')) {

    function log_and_send_templated_email($template_id, $to, $replacement_array = array(), $message_hf = array(), $log_email = 1, $extra_user_info = array())
    {
        if (empty($to) || $to == NULL) {
            return 0;
        }

        $emailTemplateData = is_array($template_id) ? $template_id : get_email_template($template_id);
        $emailTemplateBody = $emailTemplateData['text'];
        $emailTemplateSubject = $emailTemplateData['subject'];
        $emailTemplateFromName = $emailTemplateData['from_name'];
        //
        if (!empty($replacement_array)) {
            foreach ($replacement_array as $key => $value) {
                $modify_value = $key == "username" ? $value : ucwords($value);
                $emailTemplateBody = str_replace('{{' . $key . '}}', $modify_value, $emailTemplateBody);
                $emailTemplateSubject = str_replace('{{' . $key . '}}', $value, $emailTemplateSubject);
                $emailTemplateFromName = str_replace('{{' . $key . '}}', $value, $emailTemplateFromName);
            }
        }
        //
        if (!empty($extra_user_info)) {
            //
            $user_info = get_user_required_info($extra_user_info["user_sid"], $extra_user_info["user_type"]);
            //
            if ($user_info) {
                //
                $emailTemplateFromName = convert_email_template($emailTemplateFromName, $user_info);
                $emailTemplateSubject = convert_email_template($emailTemplateSubject, $user_info);
                $emailTemplateBody = convert_email_template($emailTemplateBody, $user_info);
            }
        }

        $from = $emailTemplateData['from_email'];
        $subject = $emailTemplateSubject;
        $from_name = $emailTemplateFromName;

        if ($from_name == '{{company_name}}' && isset($replacement_array['company_name'])) {
            $from_name = $replacement_array['company_name'];
        }

        if (sizeof($message_hf)) {
            $body = $message_hf['header']
                . $emailTemplateBody
                . $message_hf['footer'];
        } else {
            $body = EMAIL_HEADER
                . $emailTemplateBody
                . EMAIL_FOOTER;
        }

        if ($log_email == 1) {
            log_and_sendEmail($from, $to, $subject, $body, $from_name, $template_id);
        } else {
            sendMail($from, $to, $subject, $body, $from_name);
        }
    }
}

if (!function_exists('get_user_required_info')) {
    function get_user_required_info($user_sid, $user_type)
    {
        $columns = 'first_name, last_name, email, parent_sid, job_title';
        $table_name = 'users';
        //
        if ($user_type == 'applicant') {
            $columns = 'first_name, last_name, email, employer_sid AS parent_sid, desired_job_title AS job_title';
            $table_name = 'portal_job_applications';
        }
        //
        $CI = &get_instance();
        $CI->db->select($columns);
        $CI->db->where('sid', $user_sid);
        $result = $CI->db->get($table_name)->row_array();
        //
        if (!empty($result)) {
            return $result;
        } else {
            return array();
        }
    }
}

if (!function_exists('replace_sms_template')) {

    function replace_sms_template($template_id, $to, $replacement_array = array(), $message_hf = array(), $log_email = 1)
    {
        $emailTemplateData = is_array($template_id) ? $template_id : get_sms_template($template_id);
        $emailTemplateBody = $emailTemplateData['sms_body'];

        if (!empty($replacement_array)) {
            foreach ($replacement_array as $key => $value) {
                $emailTemplateBody = str_replace('{{' . $key . '}}', ucwords($value), $emailTemplateBody);
            }
        }

        return $emailTemplateBody;
    }
}

if (!function_exists('get_slug_data')) {

    function get_slug_data($slug_name = NULL, $table_name = NULL)
    {
        if ($slug_name != NULL && $table_name != NULL) {
            $CI = &get_instance();
            $CI->db->select($slug_name);
            $result = $CI->db->get($table_name)->row_array();

            if (!empty($result)) {
                return $result[$slug_name];
            } else {
                return array();
            }
        } else {
            return array();
        }
    }
}

if (!function_exists('remove_breaks_from_string')) {

    function remove_breaks_from_string($string)
    {
        if ($string != NULL) {
            $string = trim(preg_replace('/\s+/', ' ', $string));
        }

        return $string;
    }
}

if (!function_exists('generate_select_options_for_time')) {

    function generate_select_options_for_time($selected_value)
    {
        $options_tags = '';
        $selected_attr = '';

        for ($count_hours = 0; $count_hours < 24; $count_hours++) {
            for ($count_mins = 0; $count_mins < 60; $count_mins = $count_mins + 15) {
                $time_text = str_pad($count_hours, 2, '0', STR_PAD_LEFT) . ':' . str_pad($count_mins, 2, '0', STR_PAD_LEFT);

                if ($time_text == $selected_value) {
                    $selected_attr = ' selected="selected" ';
                } else {
                    $selected_attr = '';
                }

                $option_tag = '<option' . $selected_attr . ' value="' . $time_text . '">' . $time_text . '</option>';
                $options_tags .= $option_tag;
            }
        }

        return $options_tags;
    }
}

if (!function_exists('convert_date_to_db_format')) {

    function convert_date_to_db_format($string_date)
    {
        //        return date('M d Y, D H:i:s', strtotime(str_replace('-', '/', $string_date)));
        return date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $string_date)));
    }
}

if (!function_exists('convert_date_to_frontend_format')) {

    function convert_date_to_frontend_format($string_date, $return_empty_if_zero = false)
    {
        if (($string_date == '0000-00-00 00:00:00' || $string_date == '0000-00-00' || $string_date == null) && $return_empty_if_zero == false) {
            $string_date = date('Y-m-d H:i:s');
            $with_time = date('M d Y, D H:i:s', strtotime(str_replace('-', '/', $string_date)));
            if (strpos($with_time, '00:00:00')) {
                $with_time = date('M d Y, D', strtotime($string_date));
            }
            return $with_time;
        } elseif (($string_date == '0000-00-00 00:00:00' || $string_date == '0000-00-00' || $string_date == null) && $return_empty_if_zero == true) {
            return '';
        } else {
            $with_time = date('M d Y, D H:i:s', strtotime(str_replace('-', '/', $string_date)));
            if (strpos($with_time, '00:00:00')) {
                $with_time = date('M d Y, D', strtotime($string_date));
            }
            return $with_time;
        }
    }
}

if (!function_exists('get_portal_email_template')) {

    function get_portal_email_template($company_id, $template_code)
    {
        $CI = &get_instance();
        $CI->db->select('*');
        $CI->db->where('company_sid', $company_id);
        $CI->db->where('template_code', $template_code);
        return $CI->db->get('portal_email_templates')->result_array();
    }
}

if (!function_exists('get_job_title')) {

    function get_job_title($job_id)
    {
        $CI = &get_instance();
        $CI->db->select('Title');
        $CI->db->where('sid', $job_id);
        $CI->db->from('portal_job_listings');
        $result = $CI->db->get()->result_array();

        if (isset($result[0])) {
            return $result[0]['Title'];
        } else {
            return 'Job Deleted';
        }
    }
}

if (!function_exists('common_save_message')) {

    function common_save_message($product_data, $employer_sid = NULL)
    {
        $CI = &get_instance();
        $product_data['outbox'] = 1;
        $CI->db->insert('private_message', $product_data);

        if ($employer_sid != NULL) {
            //$product_data['outbox'] = 1;
            $product_data['from_id'] = $employer_sid;
            $CI->db->insert('private_message', $product_data);
        }

        return "Message Saved Successfully";
    }
}

if (!function_exists('save_email_log_autoresponder')) {

    function save_email_log_autoresponder($email_log)
    {
        $CI = &get_instance();
        return $CI->db->insert('email_log_autoresponder', $email_log);
    }
}

if (!function_exists('check_hired_status')) {

    function check_hired_status($job_sid)
    {
        $CI = &get_instance();
        $CI->db->select('*');
        $CI->db->from('portal_job_applications');
        $CI->db->where('sid', $job_sid);
        $emp_query = $CI->db->get();

        if ($emp_query->num_rows() == 1) {
            $emp = $emp_query->result_array();
            $emp = $emp[0];

            if ($emp['hired_status'] == '1') {
                return $emp['hired_sid'];
            } else {
                return 0;
            }
        } else {
            return -1;
        }
    }
}

if (!function_exists('get_company_billing_contacts')) {

    function get_company_billing_contacts($company_sid)
    {
        $CI = &get_instance();
        $CI->db->select('*');
        $CI->db->where('company_sid', $company_sid);
        $CI->db->where('status', 'active');
        return $CI->db->get('company_billing_contacts')->result_array();
    }
}

if (!function_exists('get_recurring_payment_detail')) {

    function get_recurring_payment_detail($company_sid)
    {
        $CI = &get_instance();
        $CI->db->select('*');
        $CI->db->where('company_sid', $company_sid);
        $CI->db->where('status', 'active');
        return $CI->db->get('recurring_payments')->result_array();
    }
}

if (!function_exists('common_indeed_acknowledgement_email')) {

    function common_indeed_acknowledgement_email($applicationData)
    {
        $template = get_portal_email_template($applicationData['employer_sid'], 'application_acknowledgement_letter');

        if (!empty($template) && $template[0]['enable_auto_responder'] == 1) {
            $title = $applicationData['job_title'];
            $body = $template[0]['message_body'];
            $body = str_replace('{{applicant_name}}', $applicationData['first_name'] . ' ' . $applicationData['last_name'], $body);
            $body = str_replace('{{job_title}}', $title, $body);
            $body = str_replace('{{company_name}}', $applicationData['company_name'], $body);
            $email = $template[0];
            $from = REPLY_TO;
            $subject = str_replace('{{company_name}}', $applicationData['company_name'], $email['subject']);
            $from_name = $email['from_name'];
            $message_data = array();
            $message_data['to_id'] = $applicationData['email'];
            $message_data['from_type'] = 'employer';
            $message_data['to_type'] = 'admin';
            $message_data['job_id'] = $applicationData['sid'];
            $message_data['users_type'] = 'applicant';
            $message_data['subject'] = 'Application Acknowledgement Letter';
            $message_data['message'] = $body;
            $message_data['date'] = date('Y-m-d H:i:s');
            $message_data['from_id'] = REPLY_TO;
            $message_data['contact_name'] = $applicationData['first_name'] . ' ' . $applicationData['last_name'];
            $message_data['identity_key'] = generateRandomString(48);
            $message_hf = message_header_footer_domain($applicationData['employer_sid'], $applicationData['company_name']);
            $secret_key = $message_data['identity_key'] . "__";

            $autoemailbody = $message_hf['header']
                . '<p>Subject: ' . $subject . '</p>'
                . $body
                . $message_hf['footer']
                . '<div style="width:100%; float:left; background-color:#000; color:#000; box-sizing:border-box;">message_id:'
                . $secret_key . '</div>';

            sendMail(REPLY_TO, $applicationData['email'], $subject, $autoemailbody, $from_name, REPLY_TO);
            //sendMail(REPLY_TO, 'mubashir.saleemi123@gmail.com', $subject, $autoemailbody, $from_name, REPLY_TO);

            $sent_to_pm = common_save_message($message_data, NULL);
            $email_log_autoresponder = array();
            $email_log_autoresponder['company_sid'] = $applicationData['employer_sid'];
            $email_log_autoresponder['sender'] = REPLY_TO;
            $email_log_autoresponder['receiver'] = $applicationData['email'];
            $email_log_autoresponder['from_type'] = 'employer';
            $email_log_autoresponder['to_type'] = 'admin';
            $email_log_autoresponder['users_type'] = 'applicant';
            $email_log_autoresponder['sent_date'] = date('Y-m-d H:i:s');
            $email_log_autoresponder['subject'] = $subject;
            $email_log_autoresponder['message'] = $autoemailbody;
            $email_log_autoresponder['job_or_employee_id'] = $applicationData['sid'];
            $save_email_log = save_email_log_autoresponder($email_log_autoresponder);
        } /* else {
mail('mubashir.saleemi123@gmail.com', 'Indeed acknowledgement - opt out', print_r($applicationData, true));
} */
    }
}

if (!function_exists('common_get_location_by_statecode')) {

    function common_get_location_by_statecode($state_code)
    {
        $CI = &get_instance();
        $CI->db->select('*');
        $CI->db->where('state_code', $state_code);
        $result = $CI->db->get('states')->result_array();

        if (!empty($result)) {
            return $result[0];
        } else {
            return array();
        }
    }
}

if (!function_exists('get_company_sids_excluded_from_reporting')) {

    function get_company_sids_excluded_from_reporting()
    {
        $CI = &get_instance();
        $CI->db->select('company_sid');
        $data_rows = $CI->db->get('companies_excluded_from_reporting')->result_array();
        $sids = array();

        foreach ($data_rows as $data_row) {
            $sids[] = $data_row['company_sid'];
        }

        return $sids;
    }
}


if (!function_exists('download_and_upload_file_to_aws')) {

    function download_and_upload_file_to_aws($remote_file_url, $file_name_with_extension)
    {
        $file_headers = get_headers($remote_file_url);
        $header_response_code = substr($file_headers[0], 9, 3);

        if (intval($header_response_code) < 400) {
            $fileContent = file_get_contents($remote_file_url);
            //making Directory to store
            $filePath = FCPATH . "assets/temp_files/";

            if (!file_exists($filePath)) {
                mkdir($filePath, 0777);
            }

            //Write Temporary File on Server
            $tempFile = fopen($filePath . $file_name_with_extension, 'w');
            fwrite($tempFile, $fileContent);
            fclose($tempFile);
            //Upload To Aws
            $uploaded_file = generateRandomString(6) . "_" . $file_name_with_extension;
            $aws = new AwsSdk();
            $aws->putToBucket($uploaded_file, $filePath . $file_name_with_extension, AWS_S3_BUCKET_NAME);
            return $uploaded_file;
        } else {
            return '';
        }
    }
}

if (!function_exists('create_and_upload_file_to_aws')) {

    function create_and_upload_file_to_aws($fileContent, $file_name_with_extension)
    {
        //making Directory to store
        $filePath = FCPATH . "assets/temp_files/";

        if (!file_exists($filePath)) {
            mkdir($filePath, 0777);
        }

        //Write Temporary File on Server
        $tempFile = fopen($filePath . $file_name_with_extension, 'w');
        fwrite($tempFile, $fileContent);
        fclose($tempFile);
        //Upload To Aws
        $uploaded_file = generateRandomString(6) . "_" . $file_name_with_extension;
        $aws = new AwsSdk();
        $aws->putToBucket($uploaded_file, $filePath . $file_name_with_extension, AWS_S3_BUCKET_NAME);
        return $uploaded_file;
    }
}


if (!function_exists('get_company_details')) {

    function get_company_details($company_sid)
    {
        $CI = &get_instance();
        $CI->db->select('*');
        $CI->db->where('parent_sid', 0);
        $CI->db->where('sid', $company_sid);
        $record_data = $CI->db->get('users')->result_array();

        if (!empty($record_data)) {
            return $record_data[0];
        } else {
            return array();
        }
    }
}

if (!function_exists('get_full_employment_application_status')) {

    function get_full_employment_application_status($user_sid, $user_type)
    {
        $CI = &get_instance();
        $status = 'unsinged';

        switch ($user_type) {
            case 'applicant':
                $CI->db->select('full_employment_application');
                $CI->db->where('sid', $user_sid);
                $applicant_info = $CI->db->get('portal_job_applications')->result_array();

                if (!empty($applicant_info)) {
                    $full_employment_application = $applicant_info[0]['full_employment_application'];
                    if (!empty($full_employment_application) && $full_employment_application != '') {
                        $full_employment_application = unserialize($full_employment_application);

                        if (isset($full_employment_application['signature'])) {
                            $signature = $full_employment_application['signature'];

                            if ($signature != '') {
                                $status = 'signed';
                            }
                        }
                    }
                }
                break;
            case 'employee':
                $CI->db->select('full_employment_application');
                $CI->db->where('sid', $user_sid);
                $applicant_info = $CI->db->get('users')->result_array();

                if (!empty($applicant_info)) {
                    $full_employment_application = $applicant_info[0]['full_employment_application'];
                    if (!empty($full_employment_application) && $full_employment_application != '') {
                        $full_employment_application = unserialize($full_employment_application);

                        if (isset($full_employment_application['signature'])) {
                            $signature = $full_employment_application['signature'];

                            if ($signature != '') {
                                $status = 'signed';
                            }
                        }
                    }
                }
                break;
        }


        return $status;
    }
}

if (!function_exists('get_full_emp_app_form_status')) {

    function get_full_emp_app_form_status($user_sid, $user_type)
    {
        $return_result = '';
        $CI = &get_instance();
        $CI->db->select('status');
        $CI->db->where('user_sid', $user_sid);
        $CI->db->where('user_type', $user_type);
        $row_data = $CI->db->get('form_full_employment_application')->result_array();

        if (!empty($row_data)) {
            $return_result = $row_data[0]['status'];
        } else {
            $return_result = 'notsent';
        }

        return $return_result;
    }
}

if (!function_exists('get_reference_checks_request_sent_status')) {

    function get_reference_checks_request_sent_status($applicant_sid)
    {
        $return_status = 'not_sent';
        $CI = &get_instance();
        $CI->db->select('verification_key');
        $CI->db->where('sid', $applicant_sid);
        $result_row = $CI->db->get('portal_job_applications')->result_array();

        if (!empty($result_row)) {
            if ($result_row[0]['verification_key'] != '') {
                $return_status = 'sent';
            }
        }

        return $return_status;
    }
}

if (!function_exists('count_references_records')) {

    function count_references_records($user_sid)
    {
        $CI = &get_instance();
        $CI->db->select('*');
        $CI->db->where('user_sid', $user_sid);
        return $CI->db->get('reference_checks')->num_rows();
    }
}

if (!function_exists('count_emergency_contacts')) {

    function count_emergency_contacts($user_sid)
    {
        $CI = &get_instance();
        $CI->db->select('*');
        $CI->db->where('users_sid', $user_sid);
        return $CI->db->get('emergency_contacts')->num_rows();
    }
}

if (!function_exists('count_licenses')) {

    function count_licenses($users_sid, $license_type)
    {
        $CI = &get_instance();
        $CI->db->select('*');
        $CI->db->where('license_type', $license_type);
        $CI->db->where('users_sid', $users_sid);

        return $CI->db->get('license_information')->num_rows();
    }
}

if (!function_exists('count_equipments')) {

    function count_equipments($users_sid)
    {
        $CI = &get_instance();
        $CI->db->select('*');
        $CI->db->where('users_sid', $users_sid);
        return $CI->db->get('equipment_information')->num_rows();
    }
}

if (!function_exists('count_dependants')) {

    function count_dependants($users_sid)
    {
        $CI = &get_instance();
        $CI->db->select('*');
        $CI->db->where('users_sid', $users_sid);
        return $CI->db->get('dependant_information')->num_rows();
    }
}

if (!function_exists('count_direct_deposit')) {

    function count_direct_deposit($users_sid)
    {
        $CI = &get_instance();
        $CI->db->select('*');
        $CI->db->where('users_sid', $users_sid);
        return $CI->db->get('bank_account_details')->num_rows();
    }
}

if (!function_exists('count_learning_center')) {

    function count_learning_center($user_sid, $company_sid, $user_type)
    {
        // Get online video count
        $online_video_count = onlineVideoCount($company_sid, $user_sid, $user_type);
        //
        // get training session count
        $training_session_count = trainingSessionCount($company_sid, $user_sid, $user_type);
        //
        $learning_center_count = $online_video_count + $training_session_count;
        //
        return $learning_center_count;
    }
}

if (!function_exists('onlineVideoCount')) {
    function onlineVideoCount($company_sid, $user_sid, $user_type, $fromProfile = false)
    {
        $CI = &get_instance();
        //
        $r = [];
        //
        if ($user_type == 'employee') {
            // Get all employees
            $CI->db->select('sid, created_date, video_title, video_description, video_source, video_id, video_start_date, screening_questionnaire_sid')
                ->select('learning_center_online_videos.video_start_date')
                ->select('learning_center_online_videos.expired_start_date')
                ->where('company_sid', $company_sid)
                ->where('employees_assigned_to', 'all')
                ->order_by('created_date', 'DESC');
            //
            if (!$fromProfile) {
                $CI->db
                    ->where('video_start_date <= ', date('Y-m-d', strtotime('now')))
                    ->group_start()
                    ->where('expired_start_date >= ', date('Y-m-d', strtotime('now')))
                    ->or_where('expired_start_date IS NULL', NULL)
                    ->group_end();
            }
            //
            $a = $CI->db->get('learning_center_online_videos');
            $b = $a->result_array();
            $a->free_result();
            //
            $ids = array();
            //
            if (sizeof($b)) {
                foreach ($b as $k => $v) {
                    $ids[$v['sid']] = $v['sid'];
                }
            }
            //
            $r = $b;
        }
        // Get specific employees
        $CI->db->select('learning_center_online_videos.sid')
            ->select('learning_center_online_videos.created_date')
            ->select('learning_center_online_videos.video_title')
            ->select('learning_center_online_videos.video_description')
            ->select('learning_center_online_videos.video_source')
            ->select('learning_center_online_videos.video_id')
            ->select('learning_center_online_videos.video_start_date')
            ->select('learning_center_online_videos.expired_start_date')
            ->select('learning_center_online_videos.screening_questionnaire_sid')
            ->select('learning_center_online_videos_assignments.learning_center_online_videos_sid')
            ->where('learning_center_online_videos_assignments.user_type', $user_type)
            ->where('learning_center_online_videos_assignments.user_sid', $user_sid)
            ->where('learning_center_online_videos_assignments.status', 1)
            ->order_by('learning_center_online_videos_assignments.date_assigned', 'DESC')
            ->join('learning_center_online_videos', 'learning_center_online_videos.sid = learning_center_online_videos_assignments.learning_center_online_videos_sid');
        //
        if (!$fromProfile) {
            $CI->db
                ->where('learning_center_online_videos.video_start_date <= ', date('Y-m-d', strtotime('now')))
                ->group_start()
                ->where('learning_center_online_videos.expired_start_date >= ', date('Y-m-d', strtotime('now')))
                ->or_where('learning_center_online_videos.expired_start_date IS NULL', NULL)
                ->group_end();
        }
        //
        $a = $CI->db->get('learning_center_online_videos_assignments');
        $b = $a->result_array();
        $a->free_result();
        //
        if (sizeof($b)) {
            foreach ($b as $k => $v) {
                $ids[$v['sid']] = $v['sid'];
            }
        }
        //
        $r = array_merge($r, $b);
        //
        $current_date = date('Y-m-d');
        $video_count = 0;
        $screening_questionnaire_check = 1;
        //
        foreach ($r as $key => $single_r) {
            $video_start_date = date('Y-m-d', strtotime($single_r['video_start_date']));

            if ($video_start_date < $current_date) {

                $CI->db->select('watched,sid');
                $CI->db->where('learning_center_online_videos_sid', $single_r['sid']);
                $CI->db->where('user_sid', $user_sid);
                $CI->db->where('user_type', $user_type);
                $user_video_result = $CI->db->get('learning_center_online_videos_assignments')->row_array();

                if (empty($user_video_result) || $user_video_result['watched'] == 0) {
                    $video_count = $video_count + 1;
                } else {
                    if (!empty($single_r['screening_questionnaire_sid']) || $single_r['screening_questionnaire_sid'] != 0) {
                        $CI->db->select('sid');
                        $CI->db->where('video_assign_sid', $user_video_result['sid']);
                        $CI->db->where('video_sid', $single_r['sid']);
                        $user_video_questionnaire_result = $CI->db->get('learning_center_screening_questionnaire')->row_array();

                        if (empty($user_video_questionnaire_result)) {
                            $video_count = $video_count + 1;
                        }
                    }
                }
            }
        }

        return $video_count;
    }
}

if (!function_exists('trainingSessionCount')) {
    function trainingSessionCount($company_sid, $user_sid, $user_type)
    {
        $CI = &get_instance();
        //
        $result = $CI->db
            ->select('
            employees_assigned_to,
            session_status,
            sid as id
        ', false)
            ->from('learning_center_training_sessions')
            ->where('company_sid', $company_sid)
            ->get();
        //
        $result_arr = $result->result_array();
        $result->free_result();
        $trainingSessionCount = 0;
        //
        if (sizeof($result_arr)) {
            foreach ($result_arr as $k0 => $v0) {
                if ($v0['session_status'] != 'pending' && $v0['session_status'] != 'scheduled')
                    continue;
                //
                if ($v0['employees_assigned_to'] == 'specific') {
                    // Check if it is assigned to login employee
                    if (
                        $CI->db
                            ->where('training_session_sid', $v0['id'])
                            ->where('user_sid', $user_sid)
                            ->where('user_type', $user_type)
                            ->count_all_results('learning_center_training_sessions_assignments') == 0
                    ) {
                        continue;
                    }
                }
                $trainingSessionCount++;
            }
        }
        return $trainingSessionCount;
    }
}

if (!function_exists('count_e_signature')) {

    function count_e_signature($user_type, $users_sid)
    {
        $CI = &get_instance();
        $CI->db->select('*');
        $CI->db->where('user_type', $user_type);
        $CI->db->where('user_sid', $users_sid);
        $CI->db->from('e_signatures_data');
        return $CI->db->count_all_results();
    }
}

if (!function_exists('count_referrals')) {

    function count_referrals($users_sid)
    {
        $CI = &get_instance();
        $CI->db->select('*');
        $CI->db->where('users_sid', $users_sid);
        return $CI->db->get('reference_network')->num_rows();
    }
}

if (!function_exists('count_interview_score_records')) {

    function count_interview_score_records($users_sid, $job_sid = 0)
    {
        $CI = &get_instance();
        $CI->db->select('*');
        $CI->db->where('candidate_sid', $users_sid);

        if ($job_sid > 0) {
            $CI->db->where('job_sid', $job_sid);
        }

        return $CI->db->get('interview_questionnaire_score')->num_rows();
    }
}

if (!function_exists('count_accurate_background_orders')) {

    function count_accurate_background_orders($users_sid, $product_type = 'background-checks')
    {
        $CI = &get_instance();
        $CI->db->select('product_type');
        $CI->db->where('users_sid', $users_sid);
        $CI->db->where('product_type', $product_type);
        return $CI->db->get('background_check_orders')->num_rows();
    }
}

if (!function_exists('show_hr_documents_light_bulb')) {

    function show_hr_documents_light_bulb($users_sid)
    {
        $CI = &get_instance();
        $return_result = false;
        $CI->db->select('*');
        $CI->db->where('receiver_sid', $users_sid);
        $total_documents = $CI->db->get('hr_user_document')->result_array();
        $acknowledged_documents = array();

        if (!empty($total_documents)) {
            foreach ($total_documents as $key => $document) {
                if ($document['acknowledged_date'] != '') {
                    $acknowledged_documents[] = $document;
                }
            }

            if (count($total_documents) == count($acknowledged_documents)) {
                $return_result = true;
            } else {
                $return_result = false;
            }
        } else {
            $return_result = false;
        }

        return $return_result;
    }
}

if (!function_exists('get_users_list')) {

    function get_users_list($company_sid, $type, $all = '')
    {
        $CI = &get_instance();

        if ($type == 'employee') {
            $CI->db->select('access_level, is_executive_admin, ' . (getUserFields()) . '');
            $where = array(
                'parent_sid' => $company_sid,
                'active' => 1,
                'terminated_status' => 0
            );
            $table = 'users';
        } else if ($type == 'applicant') {
            $where = array(
                'employer_sid' => $company_sid,
                'hired_status' => 0,
                //'archived' => 0
            );
            $table = 'portal_job_applications';
        }

        $CI->db->select('email, sid, first_name, last_name');
        // $CI->db->distinct();
        $CI->db->group_by('email');
        $CI->db->order_by('concat(first_name, last_name)', 'ASC', false);

        if ($type == 'employee') {
            $CI->db->where("username != ''");
            if ($all == '') {
                $CI->db->where("password != ''");
            }
        }

        return $CI->db->get_where($table, $where)->result_array();
    }
}


if (!function_exists('get_admin_notifications')) {
    // Updated on: 06-05-2019
    function get_admin_notifications()
    {
        $CI = &get_instance();
        $data = array();
        $data['awaiting_response'] = $CI->db->where('admin_read', 0)->from('tickets')->count_all_results();
        /* $CI->db->where('status', 'Feedback Required');
          $data['feedback_required'] = $CI->db->get('tickets')->num_rows(); */
        $data['feedback_required'] = 0;
        $data['pending_demo_requests'] = $CI->db->where('status', NULL)->from('free_demo_requests')->count_all_results();
        // $data['pending_demo_requests'] = $CI->db->get('free_demo_requests')->num_rows();
        // $current_month = date('m');
        // $current_year = date('Y');
        $CI->db
            ->from('emp_cards')
            ->join('users', 'users.sid = emp_cards.company_sid', 'left')
            ->join('recurring_payments', 'recurring_payments.company_sid = emp_cards.company_sid', 'inner')
            ->where('emp_cards.active', 1)
            ->where('users.active', 1)
            ->where('emp_cards.is_default', 1)
            ->where('recurring_payments.status', 'active')
            ->where('concat(emp_cards.expire_year,"-",IF(emp_cards.expire_month < 9 && length(emp_cards.expire_month) = 1, concat(0,emp_cards.expire_month), emp_cards.expire_month)) between "' . (date('Y-m', strtotime('now'))) . '" and "' . (date('Y-m', strtotime('+1 month'))) . '" ', null);
        // $CI->db->where('expire_month', $current_month + 1);
        // $CI->db->where('expire_year', $current_year);
        $data['cc_expiring'] = $CI->db->count_all_results();
        //
        $totalStatus = $CI->db
            ->select(' 
                COUNT(
                    DISTINCT(LOWER(REGEXP_REPLACE(name, "[^a-zA-Z]", "")))
                ) as count
            ')
            ->get('application_status')
            ->row_array()['count'];
        //
        $mapStatus = $CI->db
            ->select(' 
                COUNT(*) as count
            ')
            ->get('indeed_disposition_status_map')
            ->row_array()['count'];
        //
        $data['indeed_pending_status'] = $totalStatus - $mapStatus;

        $data['indeed_job_has_errors'] = $CI->db->where('has_errors', 1)->from('indeed_job_queue')->count_all_results();


        return $data;
    }
}

if (!function_exists('get_youtube_video_id_from_url')) {

    function get_youtube_video_id_from_url($youtube_url)
    {
        $url_parts = parse_url($youtube_url);
        $query_string = $url_parts['query'];

        if ($query_string != '') {
            parse_str($query_string, $query_vars);
            //Video Id is stored in v
            if (isset($query_vars['v'])) {
                return $query_vars['v'];
            } else {
                return '';
            }
        } else {
            return '';
        }
    }
}

if (!function_exists('get_social_security_number_from_FEA')) {

    function get_social_security_number_from_FEA($users_sid, $users_type)
    {
        $CI = &get_instance();
        $return_result = '';
        $temp = array();

        switch ($users_type) {
            case 'applicant':
                $CI->db->select('full_employment_application');
                $CI->db->where('sid', $users_sid);
                $temp = $CI->db->get('portal_job_applications')->result_array();
                break;
            case 'employee':
                $CI->db->select('full_employment_application');
                $CI->db->where('sid', $users_sid);
                $temp = $CI->db->get('users')->result_array();
                break;
        }

        if (!empty($temp) || $temp != '') {
            $temp = unserialize($temp[0]['full_employment_application']);
            $return_result = trim($temp['TextBoxSSN']);
        }

        return $return_result;
    }
}

//if(!function_exists('get_notification_email_contacts')){
//    function get_notification_email_contacts($company_sid, $notification_type){
//        $CI = & get_instance();
//
//        $CI->db->where('company_sid', $company_sid);
//        $CI->db->where('notifications_type', $notification_type);
//        $CI->db->where('status', 'active');
//
//        return $CI->db->get('notifications_emails_management')->result_array();
//    }
//}

if (!function_exists('get_notification_email_contacts')) {

    function get_notification_email_contacts($company_sid, $notification_type, $job_sid = 0)
    {
        $CI = &get_instance();
        $contacts = array();

        if ($job_sid > 0) {
            $CI->db->select('users.active as userActive');
            $CI->db->select('users.terminated_status');
            $CI->db->select('users.access_level');
            $CI->db->select('users.is_executive_admin');
            $CI->db->select('users.email as userEmail');
            $CI->db->select('portal_job_listings_visibility.employer_sid');
            $CI->db->select('notifications_emails_management.employer_sid as nem_employer_sid');
            $CI->db->select('notifications_emails_management.email as email');
            $CI->db->select('notifications_emails_management.contact_no,notifications_emails_management.contact_name');
            $CI->db->where('portal_job_listings_visibility.job_sid', $job_sid);
            $CI->db->where('notifications_emails_management.company_sid', $company_sid);
            $CI->db->where('notifications_emails_management.notifications_type', $notification_type);
            $CI->db->where('notifications_emails_management.status', 'active');
            $CI->db->join('notifications_emails_management', 'notifications_emails_management.employer_sid = portal_job_listings_visibility.employer_sid', 'left');
            $CI->db->join('users', 'notifications_emails_management.employer_sid = users.sid', 'left');
            $contacts = $CI->db->get('portal_job_listings_visibility')->result_array();
        } else {
            $CI->db->select('
                notifications_emails_management.*,
                users.active as userActive,
                users.terminated_status,
                users.access_level,
                users.is_executive_admin,
                users.email as userEmail
            ');
            $CI->db->where('notifications_emails_management.company_sid', $company_sid);
            $CI->db->where('notifications_emails_management.notifications_type', $notification_type);
            $CI->db->where('notifications_emails_management.status', 'active');
            $CI->db->join('users', 'notifications_emails_management.employer_sid = users.sid', 'left');
            $contacts = $CI->db->get('notifications_emails_management')->result_array();
        }

        $all_none_employee_contacts = array();
        $CI->db->select('*, "tmp" as access_level, "0" as is_executive_admin');
        $CI->db->where('company_sid', $company_sid);
        $CI->db->where('employer_sid', 0);
        $CI->db->where('notifications_type', $notification_type);
        $CI->db->where('status', 'active');
        $all_none_employee_contacts = $CI->db->get('notifications_emails_management')->result_array();

        // Remove the in-active / terminated employers
        if (count($contacts)) {
            foreach ($contacts as $key => $contact) {
                if ($contact['employer_sid'] != 0 && $contact['employer_sid'] != null) {
                    if ($contact['userActive'] == 0 || $contact['terminated_status'] == 1)
                        unset($contacts[$key]);
                    $contacts[$key]["email"] = $contact["userEmail"];
                }
            }
            // Reset the array indexes
            $contacts = array_values($contacts);
        }

        foreach ($all_none_employee_contacts as $key => $contact) {
            $all_none_employee_contacts[$key]['nem_employer_sid'] = 0;
        }

        $all_contacts = array();
        $all_contacts = array_merge($contacts, $all_none_employee_contacts);

        foreach ($all_contacts as $key => $contact) {
            if ($contact['email'] == null || $contact['email'] == '' || !isset($contact['email'])) {
                unset($contacts[$key]);
            }
        }

        $result = unique_multi_dimension_array($all_contacts, 'email');
        return $result;
    }
}

if (!function_exists('get_notifications_status')) {

    function get_notifications_status($company_sid)
    {
        $CI = &get_instance();
        $CI->db->where('company_sid', $company_sid);
        $CI->db->limit(1);
        $CI->db->order_by('sid', 'DESC');
        $data_row = $CI->db->get('notifications_emails_configuration')->result_array();

        if (!empty($data_row)) {
            return $data_row[0];
        } else {
            return array();
        }
    }
}


if (!function_exists('get_primary_administrator_information')) {

    function get_primary_administrator_information($company_sid)
    {
        $CI = &get_instance();
        $CI->db->select('*');
        $CI->db->where('is_primary_admin', 1);
        $CI->db->where('parent_sid', $company_sid);
        $admin = $CI->db->get('users')->result_array();

        if (empty($admin)) {
            $CI->db->select('*');
            $CI->db->where('sid', intval($company_sid) + 1);
            $CI->db->where('parent_sid', $company_sid);
            $CI->db->limit(1);
            $admin = $CI->db->get('users')->result_array();

            if (empty($admin)) {
                $CI->db->select('*');
                $CI->db->where('sid', $company_sid);
                $CI->limit(1);
                $admin = $CI->db->get('users')->result_array();
            }
        }

        if (!empty($admin)) {
            $admin = $admin[0];
        }

        return $admin;
    }
}


if (!function_exists('send_email_through_notifications')) {

    function send_email_through_notifications($company_sid, $notification_type, $email_template_id, $replacement_array, $message_header_footer = array())
    {
        $company_info = get_company_details($company_sid); //Get Company Information
        $notifications_status = get_notifications_status($company_sid); //Get Notifications Status
        //$company_primary_admin_info = get_primary_administrator_information($company_sid); //Get Primary Admin Information
        $my_debug_message = json_encode($replacement_array); //For Debug Purpose

        if (!empty($company_info) && !empty($notifications_status)) { //Check Company Information Exists and Notifications Status is not empty.
            switch ($notification_type) { //Check Which Type of Notification to Send
                case 'new_applicant':
                    if ($notifications_status['new_applicant_notifications'] == 1) {  //Check if New Applicant Notifications are Enabled
                        $notification_contacts = get_notification_email_contacts($company_sid, 'new_applicant'); //Get Notification Contacts for New Applicant Notifications
                    }
                    break;
                case 'billing_invoice':
                    if ($notifications_status['billing_invoice_notifications'] == 1) { //Check if Billing Invoice Notifications are Enabled
                        $notification_contacts = get_notification_email_contacts($company_sid, 'billing_invoice'); //Get Notification Contacts for Invoice Billing Notifications
                    }
                    break;
            }

            if (!empty($notification_contacts)) { //Check if Notifications Contacts Exist
                foreach ($notification_contacts as $contact) { //Send Templated Email to all Notification Contacts
                    //just to be on safe side replace both with and without underscore
                    $replacement_array['firstname'] = $contact['contact_name'];
                    $replacement_array['first_name'] = $contact['contact_name'];
                    //just to be on safe side replace both with and without underscore
                    $replacement_array['lastname'] = '';
                    $replacement_array['last_name'] = '';
                    $replacement_array['email'] = $contact['email'];
                    //just to be on safe side replace both with and without underscore
                    $replacement_array['companyname'] = $company_info['CompanyName'];
                    $replacement_array['company_name'] = $company_info['CompanyName'];
                    log_and_send_templated_email($email_template_id, $contact['email'], $replacement_array, $message_header_footer); //Log and Send Email
                }

                //To Steven
                $replacement_array['firstname'] = 'Steven';
                $replacement_array['first_name'] = 'Steven';
                $replacement_array['lastname'] = 'Warner';
                $replacement_array['last_name'] = 'Warner';
                //log_and_send_templated_email($email_template_id, TO_EMAIL_STEVEN, $replacement_array, $message_header_footer);
                //To Dev
                $replacement_array['firstname'] = 'eGenie';
                $replacement_array['first_name'] = 'eGenie';
                $replacement_array['lastname'] = 'Developer';
                $replacement_array['last_name'] = 'Developer';

                log_and_send_templated_email($email_template_id, TO_EMAIL_DEV, $replacement_array, $message_header_footer);
            }
        }
    }
}

if (!function_exists('populate_applicant_jobs_list')) {

    function populate_applicant_jobs_list()
    {
        $portal_applicant_jobs_list_sid = 0;
        $CI = &get_instance();
        $CI->db->select('*');
        $CI->db->order_by('sid', 'ASC');
        $applications = $CI->db->get('portal_job_applications')->result_array();

        foreach ($applications as $application) {
            $application_sid = $application['sid'];
            $company_sid = $application['employer_sid'];
            $job_sid = $application['job_sid'];
            $date_applied = $application['date_applied'];
            $status = $application['status'];
            $status_sid = $application['status_sid'];
            $questionnaire = $application['questionnaire'];
            $score = $application['score'];
            $passing_score = $application['passing_score'];
            $ip_address = $application['ip_address'];
            $applicant_source = $application['applicant_source'];
            $applicant_type = $application['applicant_type'];
            $eeo_form = $application['eeo_form'];
            $archived = $application['archived'];
            $approval_by = $application['approval_by'];
            $approval_date = $application['approval_date'];
            $approval_status = $application['approval_status'];
            $approval_status_reason = $application['approval_status_reason'];
            $approval_status_type = $application['approval_status_type'];
            $approval_status_reason_response = $application['approval_status_reason_response'];
            $desired_job_title = $application['desired_job_title'];

            $CI->db->select('*');
            $CI->db->where('portal_job_applications_sid', $application_sid);
            $CI->db->where('company_sid', $company_sid);
            $CI->db->where('job_sid', $job_sid);
            $job_list = $CI->db->get('portal_applicant_jobs_list')->result_array();

            if (empty($job_list)) {
                $data_to_insert = array();
                $data_to_insert['portal_job_applications_sid'] = $application_sid;
                $data_to_insert['company_sid'] = $company_sid;
                $data_to_insert['job_sid'] = $job_sid;
                $data_to_insert['date_applied'] = $date_applied;
                $data_to_insert['status'] = $status;
                $data_to_insert['status_sid'] = $status_sid;
                $data_to_insert['questionnaire'] = $questionnaire;
                $data_to_insert['applicant_type'] = $applicant_type;
                $data_to_insert['eeo_form'] = $eeo_form;
                $data_to_insert['archived'] = $archived;
                $data_to_insert['approval_by'] = $approval_by;
                $data_to_insert['approval_date'] = $approval_date;
                $data_to_insert['approval_status'] = $approval_status;
                $data_to_insert['approval_status_reason'] = $approval_status_reason;
                $data_to_insert['approval_status_type'] = $approval_status_type;
                $data_to_insert['approval_status_reason_response'] = $approval_status_reason_response;
                $data_to_insert['desired_job_title'] = $desired_job_title;

                if ($ip_address != null) {
                    $data_to_insert['ip_address'] = $ip_address;
                } else {
                    $data_to_insert['ip_address'] = '000.000.000.000';
                }

                $data_to_insert['applicant_source'] = $applicant_source;

                if ($score != null) {
                    $data_to_insert['score'] = $score;
                } else {
                    $data_to_insert['score'] = 0;
                }

                $data_to_insert['passing_score'] = $passing_score;
                $CI->db->insert('portal_applicant_jobs_list', $data_to_insert);
                $portal_applicant_jobs_list_sid = $CI->db->insert_id();
                $CI->db->where('application_sid', $application_sid);
                $eeo_form = $CI->db->get('portal_eeo_form')->num_rows();

                if ($eeo_form > 0) {
                    $data_to_update = array();
                    $data_to_update['portal_applicant_jobs_list_sid'] = $portal_applicant_jobs_list_sid;
                    $CI->db->where('application_sid', $application_sid);
                    $CI->db->update('portal_eeo_form', $data_to_update);
                }
            }

            if ($application['new_application_date'] == null) {
                $application_date = $application['date_applied'];
                $data_to_update = array();
                $data_to_update['new_application_date'] = $application_date;
                $CI->db->where('sid', $application_sid);
                $CI->db->update('portal_job_applications', $data_to_update);
            }
        }
    }
}

if (!function_exists('get_default_status_sid_and_text')) {

    function get_default_status_sid_and_text($company_sid)
    {
        $CI = &get_instance();
        $CI->db->select('sid, name');
        $CI->db->where('company_sid', $company_sid);
        $CI->db->where('css_class', 'not_contacted');
        $status = $CI->db->get('application_status')->result_array();
        $data = array();

        if ((sizeof($status) > 0) && isset($status[0]['sid'])) {
            $data['status_sid'] = $status[0]['sid'];
            $data['status_name'] = $status[0]['name'];
        } else {
            $data['status_sid'] = 0;
            $data['status_name'] = 'Not Contacted Yet';
        }

        return $data;
    }
}

if (!function_exists('db_get_job_category')) {

    function db_get_job_category($company_sid = NULL)
    {
        $CI = &get_instance();
        $CI->db->select('sid as id, value');
        $CI->db->where('field_sid', 198);

        if ($company_sid == NULL) {
            $CI->db->where('company_sid', 0);
        } else {
            $ids = array();
            $ids[] = 0;
            $ids[] = $company_sid;
            $CI->db->where_in('company_sid', $ids);
        }

        $CI->db->order_by('value');
        $records_obj = $CI->db->get('listing_field_list');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        return $records_arr;
    }
}

if (!function_exists('get_system_notification_emails')) {

    function get_system_notification_emails($notification_email_type = null)
    {
        $CI = &get_instance();
        $CI->db->select('system_notification_emails_config.system_notification_email_sid');
        $CI->db->select('system_notification_emails_config.has_access_to');
        $CI->db->select('system_notification_emails.*');
        $CI->db->where('system_notification_emails_config.has_access_to', $notification_email_type);
        $CI->db->where('system_notification_emails.status', 1);
        $CI->db->join('system_notification_emails', 'system_notification_emails.sid = system_notification_emails_config.system_notification_email_sid', 'left');
        return $CI->db->get('system_notification_emails_config')->result_array();
    }
}

if (!function_exists('log_and_send_templated_notification_email')) {

    function log_and_send_templated_notification_email($template_id, $to, $replacement_array = array(), $message_hf = array(), $company_sid, $job_sid, $notification_type)
    {
        if (empty($to) || $to == NULL)
            return 0;
        $CI = &get_instance();
        $emailTemplateData = get_email_template($template_id);
        $emailTemplateBody = $emailTemplateData['text'];
        $emailTemplateSubject = $emailTemplateData['subject'];
        $emailTemplateFromName = $emailTemplateData['from_name'];

        if (!empty($replacement_array)) {
            foreach ($replacement_array as $key => $value) {
                $emailTemplateBody = str_replace('{{' . $key . '}}', $value, $emailTemplateBody);
                $emailTemplateSubject = str_replace('{{' . $key . '}}', $value, $emailTemplateSubject);
                $emailTemplateFromName = str_replace('{{' . $key . '}}', $value, $emailTemplateFromName);
            }
        }

        $from = $emailTemplateData['from_email'];
        $subject = $emailTemplateSubject;
        $from_name = $emailTemplateFromName;

        if (!empty($message_hf)) {
            $body = $message_hf['header']
                . $emailTemplateBody
                . $message_hf['footer'];
        }

        sendMail($from, $to, $subject, $body, $from_name);
        log_notifications_email($company_sid, $from, $to, $subject, $body, $job_sid, $notification_type);
    }
}

if (!function_exists('log_notifications_email')) {

    function log_notifications_email($company_sid, $sender, $receiver, $subject, $message, $job_sid, $notification_type)
    {
        $CI = &get_instance();
        $data_to_insert = array();
        $data_to_insert['company_sid'] = $company_sid;
        $data_to_insert['sender'] = $sender;
        $data_to_insert['receiver'] = $receiver;
        $data_to_insert['from_type'] = 'system_notification';
        $data_to_insert['to_type'] = 'notification_contacts';
        $data_to_insert['users_type'] = 'employee';
        $data_to_insert['sent_date'] = date('Y-m-d H:i:s');
        $data_to_insert['subject'] = $subject;
        $data_to_insert['message'] = $message;
        $data_to_insert['job_or_employee_id'] = $job_sid;
        $data_to_insert['notification_type'] = $notification_type;
        $CI->db->insert('notifications_emails_log', $data_to_insert);
        return $CI->db->insert_id();
    }
}

if (!function_exists('get_questionnaire_response_status')) {

    function get_questionnaire_response_status($question_sid, $applicant_sid)
    {
        $CI = &get_instance();
        $CI->db->select('status');
        $CI->db->where('question_sid', $question_sid);
        $CI->db->where('applicant_sid', $applicant_sid);
        $data = $CI->db->get('video_interview_questions_sent')->result_array();

        if (empty($data)) {
            return 'Not Sent';
        } else {
            if (isset($data[0]['status'])) {
                return ucwords($data[0]['status']);
            } else {
                return 'Unanswered';
            }
        }
    }
}

if (!function_exists('convert_datetime_to_different_timezone')) {

    function convert_datetime_to_different_timezone($datetime, $original_timezone, $new_timezone)
    {
        $original = new DateTime($datetime, new DateTimeZone($original_timezone));
        $converted = clone $original;
        $converted->setTimezone(new DateTimeZone($new_timezone));
        return $converted;
    }
}

if (!function_exists('check_company_ems_status')) {

    function check_company_ems_status($company_id)
    {
        $CI = &get_instance();
        $CI->db->select('ems_status');
        $CI->db->where('sid', $company_id);
        $data = $CI->db->get('users')->result_array()[0]['ems_status'];
        return $data;
    }
}

if (!function_exists('check_resume_exist')) {

    function check_resume_exist($company_sid, $user_type, $user_sid)
    {
        $CI = &get_instance();
        $CI->db->select('request_status');
        $CI->db->where('company_sid', $company_sid);
        $CI->db->where('user_type', $user_type);
        $CI->db->where('user_sid', $user_sid);
        $CI->db->where('request_status', 1);
        $CI->db->where('is_respond', 0);
        $data = $CI->db->get('resume_request_logs')->result_array();

        if (empty($data)) {
            return 'empty';
        } else {
            return 'not_empty';
        }
    }
}



// Calendar Event color
// Added on: 23-04-2019
if (!function_exists('get_calendar_event_color')) {
    function get_calendar_event_color($interview_type = FALSE)
    {
        $event_color_array = array(
            // 'interview'=>'#0c4957',
            // 'interview-voip'=>'#e75480',
            // 'interview-phone'=>'#625d46',
            // 'call'=>'#570c23',
            // 'email'=>'#8E24AA',
            // 'meeting'=>'#3F51B5',
            // 'personal'=>'#E67C73',
            // 'other'=>'#616161',
            // 'training-session'=>'#337ab7',
            // 'confirmed'=>'#5cb85c',
            // 'notconfirmed'=>'#d9534f',
            // 'expired'=>'#d9534f',
            // 'cancelled'=>'#f0ad4e',
            'interview' => '#0000ff',
            'interview-voip' => '#0fa600',
            'interview-phone' => '#1c521d',
            'call' => '#dd7600',
            'email' => '#b910ff',
            'meeting' => '#0091dd',
            'personal' => '#266d55',
            'other' => '#7e7b7b',
            'training-session' => '#337ab7',
            // Event Status
            'confirmed' => '#5cb85c',
            'notconfirmed' => '#d9534f',
            'expired' => '#d9534f',
            'pending' => '#f0ad4e',
            'rescheduled' => '#E67C73',
            'cancelled' => '#d9534f',
            'completed' => '#5cb85c',
            'demo' => '#5cb88c',
            'gotomeeting' => '#fd7a2a',
            'timeoff' => '#5cb85c',
            'timeoff_pending' => '#7e7b7b',
            'goals' => '#fd7a2a',
            'holiday' => '#111111',

            'interview-voip' => '#FF0FFF',
            'call' => '#4B0082'
        );
        //
        if ($interview_type === FALSE)
            return $event_color_array;
        return isset($event_color_array[$interview_type]) ? $event_color_array[$interview_type] : $event_color_array['notconfirmed'];
    }
}


// Replace magic quotes
// Created on: 29-04-2019
if (!function_exists('replace_magic_quotes')) {
    function replace_magic_quotes(&$dataStr, $fromArray = array(), $toArray = array())
    {
        if (sizeof($fromArray) && sizeof($toArray)) {
            $dataStr = str_replace($fromArray, $toArray, $dataStr);
        } else if (sizeof($fromArray)) {
            foreach ($fromArray as $k0 => $v0) {
                $dataStr = str_replace($k0, $v0, $dataStr);
            }
        } else {
            $CI = get_instance();
            $ses = $CI->session->userdata('logged_in');
            if (!$ses)
                return false;
            //
            $ses = $ses['company_detail'];
            $dataStr = str_replace(
                array(
                    '{{company_name}}',
                    '{{company_address}}',
                    '{{company_phone}}',
                    '{{career_site_url}}',
                ),
                array(
                    $ses['CompanyName'],
                    $ses['Location_Address'],
                    $ses['PhoneNumber'],
                    $ses['WebSite']
                ),
                $dataStr
            );
        }
    }
}

// Display error
// Created on: 29-04-2019
if (!function_exists('_e')) {
    function _e($e, $print = FALSE, $die = FAlSE, $isHidden = FALSE)
    {
        if ($isHidden)
            echo '<!-- ';
        echo '<pre>';
        if ($print)
            echo '<br />*****************************<br />';
        if (is_array($e))
            print_r($e);
        else if (is_object($e))
            var_dump($e);
        else
            echo ($e);
        if ($print)
            echo '<br />*****************************<br />';
        echo '</pre>';
        if ($isHidden)
            echo ' -->';
        if ($die)
            exit(0);
    }
}

// Set the staging server check
// Created at: 03-05-2019
if (!function_exists('is_staging_server')) {
    function is_staging_server()
    {
        return false;
        //
        if (!preg_match('/applybuz/', base_url()))
            return false;

        $_this = &get_instance();
        if (
            in_array(
                $_this->input->ip_address(),
                array('127.0.0.1', '::1', '72.255.38.246')
            )
        ) {
            return true;
        }

        return false;
    }
}

/**
 * Generates ICS files and send email
 * Added on: 14-05-2019
 *
 * @param $event_sid Integer
 * @param $company_id Integer
 * @param $employer_id Integer
 * @param $email_list Array Optional
 * @param $is_return Bool Optional
 * @param $difference_array Array Optional
 *
 * @return VOID
 */
if (!function_exists('ics_files')) {
    function ics_files($event_sid, $company_id, $company_info, $action, $email_list = array(), $is_return = FALSE, $difference_array = array())
    {
        if ($action == 'delete_event')
            return false;
        // $_this =& get_instance();
        // $_this->load->model('calendar_model');
        // $_this->load->library('session');
        // Generate Updated .ics file
        if (base_url() == 'http://localhost/ahr/') {
            $destination = APPPATH . '..\assets\ics_files';
        } else {
            $destination = APPPATH . '../assets/ics_files/';
        }

        $ics_file = generate_ics_file_for_event($destination, $event_sid);
        // Send calendar email
        return send_calendar_email(
            $event_sid,
            $company_id,
            $company_info,
            $action,
            $ics_file,
            $email_list,
            $is_return,
            $difference_array
        );

        die;
        // Prepare email template
        $event_details = $_this->calendar_model->get_event_details($event_sid); //Get Event Details
        // Set defaults
        $send_to_interviewers = $send_to_extraparticipants = $send_to_ae = true;
        //
        $employer_info = $_this->session->userdata('logged_in')['employer_detail'];
        // Set default user info array
        $user_info['first_name'] = $employer_info['first_name'];
        $user_info['last_name'] = $employer_info['last_name'];
        $user_info['email'] = $employer_info['email'];
        $user_info['sid'] = $employer_info['sid'];
        //
        $employers = array();
        // Applicant Tile
        $event_category = $user_tile = '';
        $event_category = $event_details['category'];
        // Set applicant/employee details
        // if user type is not 'Personal'
        if ($event_details['users_type'] != 'personal') {
            $detail_type = $event_details['users_type'] == 'applicant' ? 'get_applicant_detail' : 'get_employee_detail';
            $user_info = $_this->calendar_model->$detail_type($event_details['applicant_job_sid']);
        }

        // Get Participants
        if (!empty($event_details['interviewer'])) {
            $employers = $_this->calendar_model->get_user_information($company_id, explode(',', $event_details['interviewer']));
        }

        // Reset categories
        switch ($event_details['category']) {
            case 'interview':
                $event_category = 'In-Person Interview';
                break;
            case 'interview-phone':
                $event_category = 'Phone Interview';
                break;
            case 'interview-voip':
                $event_category = 'Voip Interview';
                break;
            case 'training-session':
                $event_category = 'Training Session';
                break;
            case 'other':
                $event_category = 'Other Appointment';
                break;
            default:
                $event_category = ucwords($event_category);
                break;
        }
        //
        if (isset($company_info['requested_event_status'])) {
            $company_info['requested_event_status'] = $company_info['requested_event_status'] == 'Can Not Attend' ? 'Cannot Attend' : $company_info['requested_event_status'];
            $user_info['requested_event_status'] = $company_info['requested_event_status'];
        }

        $interviewers_rows = generate_interviewers_rows(
            $employers,
            $event_details,
            $event_category,
            $event_sid,
            $user_info,
            $_this
        );

        if ($event_details['users_type'] == 'applicant') {
            $user_tile .= '<p><b>' . ucwords($event_details['users_type']) . ' Name:</b> ' . ucwords($user_info['first_name'] . ' ' . $user_info['last_name']) . '</p>';
            if (!empty($user_info['email'])) {
                $user_tile .= '<p><b>Email:</b> ' . $user_info['email'] . '</p>';
            }

            if (!empty($user_info['phone_number'])) {
                $user_tile .= '<p><b>Phone:</b> ' . $event_details['users_phone'] . '</p>';
            }

            if (!empty($user_info['city'])) {
                $user_tile .= '<p><b>City:</b> ' . $user_info['city'] . '</p>';
            }

            $applicant_job_list_sid = 0;

            if (isset($user_info['job_applications']) && !empty($user_info['job_applications'])) {
                $applicant_job_list_sid = $user_info['job_applications'][0]['sid'];
                $applicant_jobs_list = $event_details['applicant_jobs_list'];

                if ($applicant_jobs_list != '' && $applicant_jobs_list != null) {
                    $applicant_jobs_array = explode(',', $applicant_jobs_list);
                }

                $user_tile .= '<p><b>Job(s) Applied:</b></p>';
                $user_tile .= '<ol>';

                if (!empty($applicant_jobs_array)) {
                    foreach ($user_info['job_applications'] as $job_application) {
                        $applicant_sid = $job_application['sid'];

                        if (in_array($applicant_sid, $applicant_jobs_array)) {
                            $job_title = !empty($job_application['job_title']) ? $job_application['job_title'] : '';
                            $desired_job_title = !empty($job_application['desired_job_title']) ? $job_application['desired_job_title'] : '';

                            if (!empty($job_title)) {
                                $title = $job_title;
                            } else if (!empty($desired_job_title)) {
                                $title = $desired_job_title;
                            } else {
                                continue;
                            }

                            $user_tile .= '<li>' . $title . '</li>';
                            $applicant_job_list_sid = $job_application['sid'];
                        }
                    }
                } else {
                    $job_application = $user_info['job_applications'];
                    $job_application_last_index = count($job_application) - 1;

                    for ($i = 0; $i < count($job_application); $i++) {
                        $applicant_sid = $job_application[$i]['sid'];

                        $job_title = !empty($job_application[$i]['job_title']) ? $job_application[$i]['job_title'] : '';
                        $desired_job_title = !empty($job_application[$i]['desired_job_title']) ? $job_application[$i]['desired_job_title'] : '';

                        if (!empty($job_title)) {
                            $title = $job_title;
                        } else if (!empty($desired_job_title)) {
                            $title = $desired_job_title;
                        } else {
                            continue;
                        }

                        $user_tile .= '<li>' . $title . '</li>';
                        $applicant_job_list_sid = $job_application[$job_application_last_index]['sid'];
                    }
                }

                $user_tile .= '</ol>';
            }

            $resume_btn = '';

            if (isset($user_info['resume']) && !empty($user_info['resume'])) {
                $resume_btn = '<a href="' . AWS_S3_BUCKET_URL . $user_info['resume'] . '" target="_blank" style="' . DEF_EMAIL_BTN_STYLE_PRIMARY . '">Download Resume</a>';
            }

            $profile_btn = '<a href="' . base_url('applicant_profile') . '/' . $user_info['sid'] . '/' . $applicant_job_list_sid . '" target="_blank" style="' . DEF_EMAIL_BTN_STYLE_DANGER . '" >View Profile</a>';
            $user_tile .= '<p>' . $resume_btn . ' ' . $profile_btn . '</p>';
            $user_tile .= '<hr />';
        }

        $from = FROM_EMAIL_EVENTS; //Create Email Notification
        $from_name = ucwords($company_info["CompanyName"]);
        $message_hf = message_header_footer($company_id, ucwords($company_info["CompanyName"]));
        $applicant_name = ucwords($user_info['first_name'] . ' ' . $user_info['last_name']);
        if (isset($company_info['requested_event_status']))
            $email_subject = ucwords($event_category) . ' - ' . ucwords($company_info['requested_event_status']) . ' ' . ucwords($company_info['CompanyName']);
        else
            $email_subject = ucwords($event_category) . ' - ' . ucwords($event_details['event_status']) . ' ' . ucwords($company_info['CompanyName']);
        // Set subject for 'Personal' type
        // and categories 'Call, Email'
        if ($event_details['users_type'] == 'personal' && ($event_details['category'] == 'call' || $event_details['category'] == 'email'))
            $email_subject = ucwords($event_details['category']) . ' - Has been scheduled with ' . ucwords($event_details['users_name']);
        else if ($event_details['users_type'] == 'personal' && ($event_details['category'] == 'training-session' || $event_details['category'] == 'Appointment'))
            $email_subject = ucwords($event_details['category']) . ' - Has been scheduled';
        $email_message = $message_hf['header'];
        $email_message .= '<div>';
        if ($action == 'confirm')
            $email_message .= '<p><b>Dear {{first_name}} {{last_name}},</b></p>';
        else
            $email_message .= '<p><b>Dear {{user_name}},</b></p>';


        if ($action == 'send_reminder_emails' || $action == 'send_cron_reminder_emails') {
            $email_message .= "<p>This is a reminder email regarding an upcoming \"<b>{$event_category}</b>\" event scheduled with you. <br /> Please, find the details below.</p>";
            // $email_message .= '<p><b>This is a reminder email regarding an upcoming event. Please, find the details below.</b></p>';
        } else if ($action == 'update_event' || $action == 'drop_update_event' || $action == 'drag_update_event') {
            // $email_message .= '<p><b>Your Event Details Have been Changed Please update your calendar as per below information. </b></p>';
            $email_message .= '<p><b>Your Event details have been Changed. Please update your calendar with the new information. </b></p>';
        } else if ($action == 'confirm') {
            $email_message .= '<p><b>{{applicant_name}}</b> has requested to change the event status to "<b>{{event_status}}</b>"</p>';
        } else {
            // Set subject for 'Personal' type
            // and categories 'Call, Email'
            if ($event_details['users_type'] == 'personal' && $event_details['category'] == 'call')
                $email_message .= '<p>You have scheduled an event regarding making a ' . ucwords($event_category) . ' to <b>' . $event_details['users_name'] . '</b></p>';
            else if ($event_details['users_type'] == 'personal' && $event_details['category'] == 'email')
                $email_message .= '<p>You have scheduled an event regarding sending an ' . ucwords($event_category) . ' to <b>' . $event_details['users_name'] . '</b></p>';
            else if ($event_details['users_type'] == 'personal' && $event_details['category'] == 'training-session') {
                $email_message .= '<p>You have scheduled a ' . ucwords($event_category) . ' for </p>';
                $email_message .= $interviewers_rows;
            } else if ($event_details['users_type'] == 'personal' && $event_details['category'] == 'Appointment') {
                $email_message .= '<p>You have scheduled an Appointment for </p>';
                $email_message .= $interviewers_rows;
            } else {
                $email_message .= "An \"<b>" . ucwords($event_category) . "</b>\" has been scheduled for you with \"<b>{{target_user}}</b>\"  with a status of \"<b>" . ucwords($event_details['event_status']) . "</b>\".";
                // $email_message .= '<p>An "<b>' . ucwords($event_category) . '<b has been Scheduled with a status of ' . ucwords($event_details['event_status']) . ' for you with <b>"{{target_user}}"</b></p>';
                if ($event_details['event_status'] == 'pending') {
                    // $email_message .= '<p>An ' . ucwords($event_category) . ' has been Scheduled with status of ' . ucwords($event_details['event_status']) . ' for you with <b>"{{target_user}}"</b></p>';
                    // $email_message .= '<p>' . ucwords($event_category) . ' has been planned with status of ' . ucwords($event_details['event_status']) . ' for you with <b>"{{target_user}}"</b></p>';
                } else {
                    // $email_message .= '<p>An ' . ucwords($event_category) . ' has been S ' . ucwords($event_details['event_status']) . ' for you with <b>"{{target_user}}"</b></p>';
                    // $email_message .= '<p>' . ucwords($event_category) . ' has been ' . ucwords($event_details['event_status']) . ' for you with <b>"{{target_user}}"</b></p>';
                }
            }
        }

        $email_message .= '<br />';
        $email_message .= ' '; //Applicant Tile
        $email_message .= '{{user_tile}}';
        $email_message .= ' ';
        $email_message .= '<p><b>Event Details are as follows:</b></p>'; //event information

        if ($event_details['users_type'] == 'personal' && $event_details['category'] == 'call')
            $email_message .= '<p><b>Event :</b> ' . ucwords($event_category) . '  "' . ucwords($event_details['users_name']) . '" on ' . $event_details['users_phone'] . '</p>';
        else if ($event_details['users_type'] == 'personal' && $event_details['category'] == 'email')
            $email_message .= '<p><b>Event :</b> Send an ' . ucwords($event_category) . ' to "' . ucwords($event_details['users_name']) . '"</p>';
        else if ($event_details['users_type'] == 'personal' && $event_details['category'] == 'training-session')
            $email_message .= '<p><b>Event :</b> Training Session</p>';
        else if ($event_details['users_type'] == 'personal' && $event_details['category'] == 'Appointment')
            $email_message .= '<p><b>Event :</b> Appointment</p>';
        else
            // $email_message .= '<p><b>Event :</b> ' . ucwords($event_category) . ' With "{{target_user}}"</p>';

            $email_message .= '<p><b>Date :</b> ' . date_with_time($event_details['date']) . '</p>';
        $email_message .= '<p><b>Time :</b> From ' . $event_details['eventstarttime'] . ' To ' . $event_details['eventendtime'] . '</p>';
        $email_message .= '<hr />';
        if ($action == 'confirm') {
            $email_message .= '{{NEW_EVENT_DETAILS}}';
        }
        $comment_tile = ''; //Comment Check

        if ($event_details['commentCheck'] == 1 && !empty($event_details['comment'])) {
            $comment_tile .= '<p><b>Comment From Employer</b></p>';
            $comment_tile .= '<p><b>Comment:</b> ' . $event_details['comment'] . '</p>';
            $comment_tile .= '<hr />';
        }

        $email_message .= ' '; //Comment Tile
        $email_message .= '{{comment_tile}}';
        $email_message .= ' ';

        if ($event_details['messageCheck'] == 1 && !empty($event_details['subject']) && !empty($event_details['message'])) { //Message Check
            $email_message .= '<p><b>Message From Employer</b></p>';
            $email_message .= '<p><b>Subject:</b> ' . $event_details['subject'] . '</p>';
            $email_message .= '<p><b>Message:</b> ' . $event_details['message'] . '</p>';

            if (!empty($event_details['messageFile']) && $event_details['messageFile'] != 'undefined') {
                $email_message .= '<p><b>Attachment:</b> <a href="' . AWS_S3_BUCKET_URL . $event_details['messageFile'] . '" target="_blank">' . $event_details['messageFile'] . '</a></p>';
            }

            $email_message .= '<hr />';
        }

        //Meeting Call Details
        if ($event_details['goToMeetingCheck'] == 1 && (!empty($event_details['meetingId']) || !empty($event_details['meetingCallNumber']) || !empty($event_details['meetingURL']))) {
            $email_message .= '<p><b>Meeting Call Details Are As Follows:</b></p>';

            if (!empty($event_details['meetingId'])) {
                $email_message .= '<p><b>Meeting ID:</b> ' . $event_details['meetingId'] . '</p>';
            }

            if (!empty($event_details['meetingCallNumber'])) {
                $email_message .= '<p><b>Meeting Call Number:</b> ' . $event_details['meetingCallNumber'] . '</p>';
            }

            if (!empty($event_details['meetingURL'])) {
                $email_message .= '<p><b>Meeting Call Url:</b> <a href="' . $event_details['meetingURL'] . '" target="_blank">' . $event_details['meetingURL'] . '</a></p>';
            }

            $email_message .= '<hr />';
        }

        if ($event_details['users_type'] != 'personal')
            $email_message .= $interviewers_rows;

        if (!empty($event_details['address'])) {
            // if (!empty($event_details['address']) && $event_details['category'] != 'interview-phone' && $event_details['category'] != 'interview-voip' && $event_details['category'] != 'call' && $event_details['category'] != 'email') {
            $map_url = "https://maps.googleapis.com/maps/api/staticmap?center=" . urlencode($event_details['address']) . "&zoom=13&size=400x400&key=" . GOOGLE_MAP_API_KEY . "&markers=color:blue|label:|" . urlencode($event_details['address']);
            $map_anchor = '<a href = "https://maps.google.com/maps?z=12&t=m&q=' . urlencode($event_details['address']) . '"><img src = "' . $map_url . '" alt = "No Map Found!" ></a>';
            $email_message .= '<p><b>Address:</b> ' . $event_details['address'] . ' </p>';
            $email_message .= '<p> ' . $map_anchor . ' </p>';
            $email_message .= '<hr />';
        }

        $email_message .= '{{EMAIL_STATUS_BUTTONS}}';
        $email_message .= '</div>';
        $email_message .= $message_hf['footer'];
        $user_message = $email_message; //Send Email to Applicant

        // For Send Reminder Emails
        if ($action == 'send_reminder_emails' && !sizeof($email_list)) {
            header('content-type: application/json');
            echo json_encode(array('Response' => 'Error! this event is no longer available.', 'Redirect' => FALSE, 'Status' => FALSE));
            exit(0);
        }

        if ($action == 'send_reminder_emails' || $action == 'send_cron_reminder_emails') {
            $date = date('Y-m-d H:i:s');
            foreach ($email_list as $k0 => $v0) {
                $user_message = $email_message; //Send Email to Applicant
                $user_message = str_replace('{{user_name}}', $v0['value'], $user_message);
                $user_message = str_replace('{{user_tile}}', ' ', $user_message);
                $user_message = str_replace('{{comment_tile}}', ($v0['type'] == 'employee' || $v0['type'] == 'applicant' ? '' : $event_details['comment']), $user_message);
                $user_message = str_replace('{{target_user}}', ucwords($company_info['CompanyName']), $user_message);
                //
                if ($action != 'send_cron_reminder_emails') {
                    // Set data array
                    $data_array = array();
                    $data_array['event_sid'] = $event_sid;
                    $data_array['email_address'] = $v0['email_address'];
                    $data_array['user_id'] = $v0['id'];
                    $data_array['user_name'] = $v0['value'];
                    $data_array['user_type'] = $v0['type'] != '' ? $v0['type'] : 'employee';
                    $data_array['created_at'] = $date;
                    // Create record in db
                    $_this->calendar_model->save_event_sent_email_reminder_history($data_array);
                }

                // // Add event status buttons
                $user_email_status_button_rows =
                    generate_event_status_rows(
                        $event_details['sid'],
                        $v0['id'],
                        $v0['type'],
                        $v0['value'],
                        $v0['email_address'],
                        $event_details['category'],
                        $event_details['learning_center_training_sessions'],
                        $_this
                    );
                $user_message = str_replace('{{EMAIL_STATUS_BUTTONS}}', $user_email_status_button_rows, $user_message);
                log_and_send_email_with_attachment(FROM_EMAIL_NOTIFICATIONS, $v0['email_address'], $email_subject, $user_message, $from_name, $ics_file);
            }
            if ($action != 'send_cron_reminder_emails') {
                header('content-type: application/json');
                echo json_encode(array('Response' => 'Reminder emails are sent to the selected emails.', 'Redirect' => FALSE, 'Status' => TRUE));
                exit(0);
            } else {
                // $_this->_e($user_message, true, true);
                exit(0);
            }
            ;
        }

        // Don't send email to interviewers
        // and to non-employee interviewers in case of
        // type 'Personal'
        if ($event_details['users_type'] == 'personal')
            $send_to_extraparticipants = $send_to_interviewers = false;
        // Send email to applicant/employee/person
        if ($send_to_ae) {
            $user_message = str_replace('{{user_name}}', $applicant_name, $user_message);
            $user_message = str_replace('{{user_tile}}', ' ', $user_message);
            $user_message = str_replace('{{comment_tile}}', ' ', $user_message);
            $user_message = str_replace('{{target_user}}', ucwords($company_info['CompanyName']), $user_message);
            //
            if ($event_details['users_type'] != 'personal' && $action != 'confirm') {
                // Add event status buttons
                $user_email_status_button_rows =
                    generate_event_status_rows(
                        $event_details['sid'],
                        $user_info['sid'],
                        $event_details['users_type'],
                        $user_info['first_name'] . ' ' . $user_info['last_name'],
                        $user_info['email'],
                        $event_details['category'],
                        $event_details['learning_center_training_sessions'],
                        $_this
                    );
                $user_message = str_replace('{{EMAIL_STATUS_BUTTONS}}', $user_email_status_button_rows, $user_message);
            } else
                $user_message = str_replace('{{EMAIL_STATUS_BUTTONS}}', '', $user_message);

            if ($is_return)
                return array('Subject' => $email_subject, 'Body' => $user_message, 'FromEmail' => FROM_EMAIL_NOTIFICATIONS);
            log_and_send_email_with_attachment(FROM_EMAIL_NOTIFICATIONS, $user_info['email'], $email_subject, $user_message, $from_name, $ics_file);

            // $_this->_e($email_subject, true);
            // $_this->_e($user_message, true);
        }
        // Send emails to Interviewers
        if ($send_to_interviewers) {
            //
            foreach ($employers as $employer) { //Send Email To Employers
                $user_message = $email_message;
                $employer_name = ucwords($employer['first_name'] . ' ' . $employer['last_name']);
                $user_message = str_replace('{{user_name}}', $employer_name, $user_message);
                $user_message = str_replace('{{user_tile}}', $user_tile, $user_message);
                $user_message = str_replace('{{comment_tile}}', $comment_tile, $user_message);
                $user_message = str_replace('{{target_user}}', $applicant_name, $user_message);


                // Add event status buttons
                $interviewer_email_status_button_rows =
                    generate_event_status_rows(
                        $event_details['sid'],
                        $employer['sid'],
                        'interviewer',
                        $employer_name,
                        $employer['email'],
                        $event_details['category'],
                        $event_details['learning_center_training_sessions'],
                        $_this
                    );
                $user_message = str_replace('{{EMAIL_STATUS_BUTTONS}}', $interviewer_email_status_button_rows, $user_message);
                // $_this->_e($user_message, true);

                log_and_send_email_with_attachment(FROM_EMAIL_NOTIFICATIONS, $employer['email'], $email_subject, $user_message, $from_name, $ics_file);
            }
        }
        // Send emails to non-employee Interviewers
        if ($send_to_extraparticipants) {
            if (!empty($event_external_participants)) { //Send Email To External Participants
                foreach ($event_external_participants as $event_external_participant) {
                    // $_this->_e($event_external_participant, true, true);
                    $user_message = $email_message;
                    $employer_name = ucwords($event_external_participant['name']);
                    $user_message = str_replace('{{user_name}}', $employer_name, $user_message);
                    $user_message = str_replace('{{user_tile}}', $user_tile, $user_message);
                    $user_message = str_replace('{{comment_tile}}', $comment_tile, $user_message);
                    $user_message = str_replace('{{target_user}}', $applicant_name, $user_message);
                    // Add event status buttons
                    $extrainterviewer_email_status_button_rows =
                        generate_event_status_rows(
                            $event_details['sid'],
                            $event_external_participant['sid'],
                            'extrainterviewer',
                            $employer_name,
                            $event_external_participant['email'],
                            $event_details['category'],
                            $event_details['learning_center_training_sessions'],
                            $_this
                        );
                    $user_message = str_replace('{{EMAIL_STATUS_BUTTONS}}', $extrainterviewer_email_status_button_rows, $user_message);
                    // $_this->_e($user_message, true);
                    log_and_send_email_with_attachment(FROM_EMAIL_NOTIFICATIONS, $event_external_participant['email'], $email_subject, $user_message, $from_name, $ics_file);
                }
            }
            // $_this->_e('end', true, true);
        }
    }
}

/**
 * Generate rows for Interviewers
 * and non-employee Interviewers
 * Added on: 14-05-2019
 *
 * @param $employers Array|Bool
 * @param $event_details Array
 * @param $event_category String
 * @param $event_sid Integer
 * @param $user_info Array
 * @param $_this Instance
 * @param $difference_array Array Optional
 *
 * @return Array
 *
 */
if (!function_exists('generate_interviewers_rows')) {
    function generate_interviewers_rows($employers, $event_details, $event_category, $event_sid, $user_info, $_this, $difference_array)
    {
        if (!sizeof($employers))
            return '';
        // Set defaults
        $show_emails = array();
        $interviewers_rows = '';
        $interviewers_rows .= '<tr>';
        $interviewers_rows .= '     <td style="font-size: 20px;"><br /><strong>' . (strtolower($event_details['users_type']) == 'applicant' ? 'Interviewer(s)' : 'Participant(s)') . '</strong><br /><br /></td>';
        $interviewers_rows .= '</tr>';
        //
        if ($event_category == '')
            $event_details['category'];
        //
        if (!empty($event_details['interviewer_show_email']) && !is_null($event_details['interviewer_show_email']))
            $show_emails = explode(',', $event_details['interviewer_show_email']);
        //
        if ($event_details['users_type'] == 'personal' && ($event_details['category'] == 'other' || $event_details['category'] == 'training-session'))
            $interviewers_rows .= '';
        else {
            $interviewers_rows .= '<tr><td style="font-size: 20px;"><p>Your <b>' . $event_category . '</b> is <b>' . ucwords(isset($user_info['requested_event_status']) ? $user_info['requested_event_status'] : $event_details['event_status']) . '</b> with:</p></td></tr>';
        }

        // $interviewers_rows .= '<ul>';

        if ($event_details['users_type'] == 'employee') {
            $interviewers_rows .= '<tr><td><p style="font-size: 20px;">&#9632; &nbsp;' . ucwords($user_info['first_name'] . ' ' . $user_info['last_name']) . ' ( <a href="mailto:' . $user_info['email'] . '">' . $user_info['email'] . '</a> ) (' . $user_info['timezone'] . ')</p></td></tr>';
        }
        $company_timezone = $_this->calendar_model->get_timezone('company', $event_details['company_id']);

        foreach ($employers as $employer) {
            if (empty($employer['timezone']))
                $employer['timezone'] = $company_timezone;
            $style = isset($difference_array['added_interviewers']) && sizeof($difference_array['added_interviewers']) && in_array($employer['sid'], $difference_array['added_interviewers']) ? 'style="background-color: #81b431;  font-weight: bold;"' : '';
            $email = in_array($employer['sid'], $show_emails) ? ' ( <a href="mailto:' . $employer['email'] . '">' . $employer['email'] . '</a> )' : '';
            $interviewers_rows .= '<tr ' . $style . '><td style="font-size: 20px;"><p>&#9632; &nbsp;' . ucwords($employer['first_name'] . ' ' . $employer['last_name']) . ' ' . $email . ' (' . $employer['timezone'] . ') ' . ($style != '' ? '<strong>&nbsp;&nbsp;[Added]</strong>' : '') . '</p></td></tr>';
        }

        // For removed participants
        if (
            isset($difference_array['removed_interviewers']) &&
            sizeof($difference_array['removed_interviewers'])
        ) {
            foreach ($difference_array['removed_interviewers'] as $k0 => $v0) {
                $interviewers_rows .= '<tr style="background-color: #d9534f; font-weight: bold;"><td><p>&#9632; &nbsp;' . ucwords($_this->calendar_model->get_interviewer_name($v0)) . ' <strong>&nbsp;&nbsp;[Removed]</strong></p></td></tr>';
            }
        }


        $event_external_participants = $event_details['external_participants'];
        // $event_external_participants = $_this->calendar_model->get_event_external_participants($event_sid); //Get External Participants
        $ext_participants = '';

        // For new external participants
        $new_exp = array();
        if (
            isset($difference_array['added_external_interviewers']) &&
            sizeof($difference_array['added_external_interviewers'])
        ) {
            foreach ($difference_array['added_external_interviewers'] as $k0 => $v0) {
                $new_exp[] = $v0['email'];
            }
        }


        if (!empty($event_external_participants)) {
            foreach ($event_external_participants as $event_external_participant) {
                $show_email = $event_external_participant['show_email'];
                $email_link = '';
                if ($show_email == 1)
                    $email_link = '( <a href="mailto:' . $event_external_participant['email'] . '">' . $event_external_participant['email'] . '</a> )';

                $participant = ucwords($event_external_participant['name']) . ' ' . $email_link . '';
                $style = in_array($event_external_participant['email'], $new_exp) ? 'style="background-color: #81b431; font-weight: bold;"' : '';
                $ext_participants .= '<tr ' . $style . '><td><p>&#9632; &nbsp;' . $participant . ' ' . ($style != '' ? '<strong>&nbsp;&nbsp;[Added]</strong>' : '') . '</p></td></tr>';
            }

            $interviewers_rows .= $ext_participants;
        }

        // For removed external participants
        if (
            isset($difference_array['removed_external_interviewers']) &&
            sizeof($difference_array['removed_external_interviewers'])
        ) {
            foreach ($difference_array['removed_external_interviewers'] as $k0 => $v0) {
                $del_exp[] = $v0['email'];
                $interviewers_rows .= '<tr style="background-color: #d9534f; font-weight: bold;"><td><p>&#9632; &nbsp;' . ucwords($v0['name']) . '<strong>&nbsp;&nbsp;[Removed]</strong></p></td></tr>';
            }
        }
        // $interviewers_rows .= '</ul>';
        // $interviewers_rows .= '<hr />';
        return $interviewers_rows;
    }
}

/**
 * Generates event status rows for email
 * Added on: 14-05-2019
 *
 * @param $event_sid Integer
 * @param $user_sid Integer
 * @param $event_type String (applicant, employee, interviewer, extrainterviewer)
 * @param $user_name String
 * @param $user_email String
 * @param $event_category String
 * @param $learning_center_training_sessions Integer
 * @param $_this Instance
 *
 * @return String
 */
if (!function_exists('generate_event_status_rows')) {
    function generate_event_status_rows($event_sid, $user_sid, $event_type, $user_name, $user_email, $event_category, $learning_center_training_sessions, $_this, &$links_url = '')
    {
        //
        // Load encryption class
        // to encrypt employee/applicant id
        // and email
        $_this->load->library('encrypt');
        $base_url = base_url() . 'event/';
        // Set event code string
        $string_conf = 'id=' . $user_sid . ':eid=' . $event_sid . ':etype=' . $event_type . ':type=confirmed:name=' . $user_name . ':email=' . $user_email;
        $string_notconf = 'id=' . $user_sid . ':eid=' . $event_sid . ':etype=' . $event_type . ':type=notconfirmed:name=' . $user_name . ':email=' . $user_email;
        $string_reschedule = 'id=' . $user_sid . ':eid=' . $event_sid . ':etype=' . $event_type . ':type=reschedule:name=' . $user_name . ':email=' . $user_email;
        if ($event_category == 'training-session')
            $string_attended = 'id=' . $user_sid . ':eid=' . $event_sid . ':etype=' . $event_type . ':type=attended:name=' . $user_name . ':email=' . $user_email;
        // Set encoded string
        $short_url = array();
        $short_url['conf'] = $base_url . str_replace('/', '$eb$eb$1', $_this->encrypt->encode($string_conf));
        $short_url['not-conf'] = $base_url . str_replace('/', '$eb$eb$1', $_this->encrypt->encode($string_notconf));
        $short_url['res'] = $base_url . str_replace('/', '$eb$eb$1', $_this->encrypt->encode($string_reschedule));
        $enc_string_conf = $short_url['conf'];
        if ($event_category == 'training-session') {
            $short_url['att'] = $base_url . str_replace('/', '$eb$eb$1', $_this->encrypt->encode($string_attended));
            $enc_string_attended = $short_url['att'];
        }
        $enc_string_notconf = $short_url['not-conf'];
        $enc_string_reschedule = $short_url['res'];
        // Set button rows
        $button_rows = '<tr><td>';
        // $button_rows = '<div class="cs-email-button-row">';
        $button_rows .= '   <p style="font-size: 20px;">Please select one of the options below to "Confirm", "Reschedule" or let us know that you "Cannot Attend".</p>';
        $button_rows .= '</td></tr>';
        $button_rows .= '<tr><td>';
        $max_width = '500px';
        $insert_array = array();
        if ($event_category == 'training-session') {
            $button_rows .= '   <a href="' . $enc_string_conf . '" target="_blank" style="background-color: #f0ad4e ; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block; margin: 5px; ">Will Attend</a>';
            $button_rows .= '   <a href="' . $enc_string_reschedule . '" target="_blank" style="background-color: #006699; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block; margin: 5px; ">Reschedule</a>';
            $button_rows .= '   <a href="' . $enc_string_notconf . '" target="_blank" style="background-color: #cc1100; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block; margin: 5px; ">Unable To Attend</a>';
            $button_rows .= '   <a href="' . $enc_string_attended . '" target="_blank" style="background-color: #009966; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block; margin: 5px; ">Attended</a>';
            // Only show trainig session link
            // when blue panel is active
            if (check_blue_panel_status() && $event_type != 'extrainterviewer') {
                $button_rows .= '   <br />';
                $button_rows .= '   <br />';
                $button_rows .= '   <p>Below is the link for the training session.</p>';
                $button_rows .= '   <a href="' . base_url('learning_center/view_training_session') . '/' . $learning_center_training_sessions . '" target="_blank" style="background-color: none; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; margin: 5px;">' . base_url('learning_center/view_training_session') . '/' . $learning_center_training_sessions . '</a>';
            }
            foreach ($short_url as $url) {
                $enc_str = generateRandomString(8);
                $insert_array['short_link'] = $enc_str;
                $insert_array['redirect_link'] = $url;
                $_this->db->insert('short_links', $insert_array);
                $links_url .= base_url() . 'event-link/' . $enc_str;
            }
        } else {
            $button_rows .= '   <a href="' . $enc_string_conf . '" target="_blank" style="background-color: #009966; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block; margin: 5px;">Confirm</a>';
            $button_rows .= '   <a href="' . $enc_string_reschedule . '" target="_blank" style="background-color: #006699; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block; margin: 5px; ">Reschedule</a>';
            $button_rows .= '   <a href="' . $enc_string_notconf . '" target="_blank" style="background-color: #cc1100; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block; margin: 5px; ">Cannot attend</a>';
            foreach ($short_url as $url) {
                $enc_str = generateRandomString(8);
                $insert_array['short_link'] = $enc_str;
                $insert_array['redirect_link'] = $url;
                $_this->db->insert('short_links', $insert_array);
                $links_url .= base_url() . 'event-link/' . $enc_str;
            }
        }
        $button_rows .= '</td></tr>';
        // $button_rows .= '</div>';
        // $button_rows .= '</div>';

        return $button_rows;
    }
}

/**
 * Save email sanpshot and send email
 * Added on: 14-05-2019
 *
 * @param $from String
 * @param $to String
 * @param $subject String
 * @param $body String
 * @param $sender_name String
 * @param $file_path String
 * @param $_this Instance
 *
 * @return String
 */
if (!function_exists('log_and_send_email_with_attachment')) {
    function log_and_send_email_with_attachment($from, $to, $subject, $body, $sender_name, $file_path, $method = "sendMailWithAttachmentRealPath")
    {
        if (empty($to) || $to == NULL)
            return 0;
        $email_data = array(
            'date' => date('Y-m-d H:i:s'),
            'subject' => $subject,
            'email' => $to,
            'message' => $body,
            'username' => $sender_name
        );
        //
        save_email_log_common($email_data);
        //
        if (base_url() != STAGING_SERVER_URL) {
            return $method($from, $to, $subject, $body, $sender_name, $file_path);
        }
    }
}


/**
 * Send calendar email
 * Added on: 24-05-2019
 *
 * @param $event_sid Integer
 * @param $company_id Integer
 * @param $company_info Array
 * @param $action String
 * @param $ics_file String
 * @param $email_list Array Optional
 * @param $is_return Bool Optional
 * @param $difference_array Array Optional
 *
 * @return VOID
 */
if (!function_exists('send_calendar_email')) {
    function send_calendar_email($event_sid, $company_id, $company_info, $action, $ics_file, $email_list = array(), $is_return = FALSE, $difference_array = array())
    {
        $_this = &get_instance();
        $_this->load->model('calendar_model');
        $_this->load->library('session');
        $sms_replace_array = array();
        // Fetch event details
        $event_details = $_this->calendar_model->get_event_detail($event_sid, false);

        // _e($event_details, true);
        // $event_details = $_this->calendar_model->get_event_detail($event_sid, true);

        $start_time = DateTime::createFromFormat('h:iA', $event_details['event_start_time'])->format('H:i:s');
        // $end_time = DateTime::createFromFormat('h:iA', $event_details['event_start_time'])->format('H:i:s');

        $date = DateTime::createFromFormat('Y-m-d H:i:s', $event_details['date'] . ' ' . $start_time);
        $duration = get_date_difference_duration($date);
        // $event_details = $_this->calendar_model->get_event_details($event_sid);
        //
        if (!sizeof($event_details))
            return false;
        $event_details['sid'] = $event_sid;
        $event_details['category'] = strtolower($event_details['category_uc']);
        // Set defaults
        $send_to_interviewers = $send_to_extraparticipants = $send_to_ae = true;
        $event_category = $user_tile = '';
        $employers = array();
        // Get employer session
        $employer_info = $_this->session->userdata('logged_in')['employer_detail'];
        // Set default user info array
        $user_info['first_name'] = $employer_info['first_name'];
        $user_info['last_name'] = $employer_info['last_name'];
        $user_info['email'] = $employer_info['email'];
        $user_info['sid'] = $employer_info['sid'];
        // Set event category
        $event_category = reset_category($event_details['category']);
        // Reset categories
        // switch ($event_details['category']) {
        //     case 'interview': $event_category = 'In-Person Interview'; break;
        //     case 'interview-phone': $event_category = 'Phone Interview'; break;
        //     case 'interview-voip': $event_category = 'Voip Interview'; break;
        //     case 'training-session': $event_category = 'Training Session'; break;
        //     case 'other': $event_category = 'Other Appointment'; break;
        //     default: $event_category = ucwords($event_category); break;
        // }
        // Set applicant/employee details
        // if user type is not 'Personal'
        if ($event_details['users_type'] != 'personal') {
            $detail_type = $event_details['users_type'] == 'applicant' ? 'get_applicant_detail' : 'get_employee_detail';
            $user_info = $_this->calendar_model->$detail_type($event_details['applicant_job_sid']);
            $user_info['timezone'] = $_this->calendar_model->get_timezone('reset', $event_details['company_id'], $user_info['timezone']);
        } else {
            $user_info['timezone'] = $_this->calendar_model->get_timezone('current', $event_details['company_id']);
        }
        $personal_timezone = $_this->calendar_model->get_employee_detail($user_info['sid']);
        if (!empty($personal_timezone) && isset($personal_timezone['timezone']))
            $user_info['timezone'] = $personal_timezone['timezone'];

        // Get Participants
        if (!empty($event_details['interviewer'])) {
            $employers = $_this->calendar_model->get_user_information($company_id, explode(',', $event_details['interviewer']));
        }
        // Change event status temporary
        // for request mode
        if (isset($company_info['requested_event_status']))
            $user_info['requested_event_status'] = $company_info['requested_event_status'] = $company_info['requested_event_status'] == 'Can Not Attend' ? 'Cannot Attend' : $company_info['requested_event_status'];
        $user_timezone = $user_info['timezone'];
        $employer_timezone = $user_info['timezone'];
        // Set interviewers rows
        $interviewers_rows = generate_interviewers_rows(
            $employers,
            $event_details,
            $event_category,
            $event_sid,
            $user_info,
            $_this,
            $difference_array
        );

        // TODO
        // change font color
        // make them bold
        // old msg
        // Default values

        // _e($difference_array, true);
        $event_update_message_row = $event_update_category_row = $event_update_date_row
            = $event_update_start_time_row = $event_update_end_time_row = $event_update_comment_row
            = $event_update_address_row = $event_update_message_row = $event_subject_row
            = $event_update_message_subject_row = $event_update_message_body_row = $event_update_message_file_row
            = $event_update_meeting_id_row = $event_update_meeting_url_row = $event_update_meeting_call_number_row
            = $event_update_meeting_row = $event_update_applicant_name_row = $event_update_user_phone_row
            = $event_update_comment_change_row = $event_update_employee_name_row = '';
        // Number of blocks that are changed
        $event_block_changed = 0;

        // Set new category info
        if ($action == 'update_event' && sizeof($difference_array) && isset($difference_array['new_category'])) {
            $event_update_category_row = 'Event type changed from "<b style="color: #d9534f; font-weight: bold; font-size: 15px;">' . (reset_category($difference_array['old_category'])) . '</b>" to "<b style="color: #81b431; font-weight: bold; font-size: 15px;">' . (reset_category($difference_array['new_category'])) . '</b>"';
            $event_subject_row = " - Event type has been changed.";
            $event_block_changed++;
            // _e($event_update_category_row, true);
        }

        // Set new event date info
        if (($action == 'update_event' || $action == 'drag_update_event') && sizeof($difference_array) && isset($difference_array['new_date'])) {
            $event_update_date_row = 'Event date changed from "<b style="color: #d9534f; font-weight: bold; font-size: 15px;">{{EVENT_DATE_OLD}}</b>" to "<b style="color: #81b431; font-weight: bold; font-size: 15px;">{{EVENT_DATE}}</b>"';
            $event_subject_row = " - Event date has been changed.";
            $event_block_changed++;
            // _e($event_update_date_row, true);
        }

        // Set new event start time info
        if (($action == 'update_event' || $action == 'drag_update_event') && sizeof($difference_array) && isset($difference_array['new_event_start_time'])) {
            $event_update_start_time_row = 'Event start time changed from "<b style="color: #d9534f; font-weight: bold; font-size: 15px;">{{EVENT_START_TIME_OLD}}</b>" to "<b style="color: #81b431; font-weight: bold; font-size: 15px;">{{EVENT_START_TIME}}</b>"';
            $event_subject_row = " - Event start time has been changed.";
            $event_block_changed++;
            // _e($event_update_start_time_row, true);
        }

        // Set new event end time info
        if (($action == 'update_event' || $action == 'drag_update_event') && sizeof($difference_array) && isset($difference_array['new_event_end_time'])) {
            $event_update_end_time_row = 'Event end time changed from "<b style="color: #d9534f; font-weight: bold; font-size: 15px;">{{EVENT_END_TIME_OLD}}</b>" to "<b style="color: #81b431; font-weight: bold; font-size: 15px;">{{EVENT_END_TIME}}</b>"';
            $event_subject_row = " - Event end time has been changed.";
            $event_block_changed++;
            // _e($event_update_end_time_row, true);
        }

        // Set new event comment info
        if (
            $action == 'update_event' && sizeof($difference_array) && (
                (isset($difference_array['new_comment']) && $difference_array['old_comment'] != '') ||
                (isset($difference_array['new_comment'], $difference_array['old_comment']) && $difference_array['old_comment'] != $difference_array['new_comment']))
        ) {
            if ($difference_array['old_comment'] != '') {
                $event_update_comment_row = 'Comment changed.';
                $event_update_comment_change_row = '<td><strong>Previous</strong><p style="color: #d9534f;">' . ($difference_array['old_comment']) . '</p></td><td><strong>New</strong><p style="color: #81b431;">' . ($difference_array['new_comment']) . '</p><br /></td>';
            }
            $event_subject_row = " - Comment has been changed.";
            $event_block_changed++;
            // _e($event_update_comment_row, true);
        }

        // Set new event address info
        if ($action == 'update_event' && sizeof($difference_array) && isset($difference_array['new_address']) && $difference_array['old_address'] != '') {
            $event_update_address_row = 'Address changed from "<b style="color: #d9534f; font-weight: 800; font-size: 15px;">' . ($difference_array['old_address']) . '</b>" to "<b style="color: #81b431; font-weight: 800; font-size: 15px;">' . ($difference_array['new_address']) . '</b>"';
            // _e($event_update_address_row, true);
            $event_subject_row = " - Event address has been changed.";
            $event_block_changed++;
        }

        // Set new event message info
        if (
            $action == 'update_event' && sizeof($difference_array) &&
            (isset($difference_array['new_message_check']) && $difference_array['old_message_check'] != '') ||
            (isset($difference_array['old_subject']) && $difference_array['old_subject'] != $difference_array['new_subject']) ||
            (isset($difference_array['old_message']) && $difference_array['old_message'] != $difference_array['new_message'])
        ) {
            $event_update_message_row = 'Message details changed.';
            $event_subject_row = " - Event message details have been changed.";
            $event_block_changed++;
            // _e($event_update_message_row, true);
        }

        // Set message difference
        // Subject
        if ($action == 'update_event' && isset($difference_array['old_subject'], $difference_array['new_subject']) && $difference_array['old_subject'] != $difference_array['new_subject'] && $difference_array['old_subject'] != '') {
            $event_update_message_subject_row = '<td><strong>Previous</strong><p style="color: #d9534f;">' . ($difference_array['old_subject']) . '</p></td><td><strong>New</strong><p style="color: #81b431;">' . ($difference_array['new_subject']) . '</p><br /></td>';
        }
        // Message body
        if ($action == 'update_event' && isset($difference_array['old_message'], $difference_array['new_message']) && $difference_array['old_message'] != $difference_array['new_message'] && $difference_array['old_message'] != '') {
            $event_update_message_body_row = '<td><strong>Previous</strong><p style="color: #d9534f;">' . ($difference_array['old_message']) . '</p></td><td><strong>New</strong><p style="color: #81b431;">' . ($difference_array['new_message']) . '</p><br /></td>';
        }
        // Message file
        if ($action == 'update_event' && isset($difference_array['old_message_file'], $difference_array['new_message_file']) && $difference_array['old_message_file'] != $difference_array['new_message_file']) {
            if ($difference_array['old_message_file'] != '')
                $event_update_message_file_row .= '<a href="' . AWS_S3_BUCKET_URL . '' . ($difference_array['old_message_file']) . '" target="_blank" style="color: #d9534f;">' . ($difference_array['old_message_file']) . '</a><br />';
            if ($difference_array['new_message_file'] != '')
                $event_update_message_file_row .= '<a href="' . AWS_S3_BUCKET_URL . '' . ($difference_array['new_message_file']) . '" target="_blank" style="color: #81b431;">' . ($difference_array['new_message_file']) . '</a>';
        }

        // Set new event meeting info
        if (
            $action == 'update_event' && sizeof($difference_array) &&
            (isset($difference_array['new_meeting_check']) && $difference_array['old_meeting_check'] != '') ||
            (isset($difference_array['old_meeting_id']) && $difference_array['old_meeting_id'] != $difference_array['new_meeting_id']) ||
            (isset($difference_array['old_meeting_url']) && $difference_array['old_meeting_url'] != $difference_array['new_meeting_url']) ||
            (isset($difference_array['old_meeting_call_number']) && $difference_array['old_meeting_call_number'] != $difference_array['new_meeting_call_number'])
        ) {
            $event_update_meeting_row = 'Meeting details have changed.';
            $event_subject_row = " - Event meeting details have changed.";
            $event_block_changed++;
            // _e($event_update_message_row, true);
        }

        // Set meeting difference
        // Meeting ID
        if ($action == 'update_event' && isset($difference_array['old_meeting_id'], $difference_array['new_meeting_id']) && $difference_array['old_meeting_id'] != $difference_array['new_meeting_id'] && $difference_array['old_meeting_id'] != '') {
            $event_update_meeting_id_row = '<td><strong>Previous</strong><p style="color: #d9534f;">' . ($difference_array['old_meeting_id']) . '</p></td><td><strong>New</strong><p style="color: #81b431;">' . ($difference_array['new_meeting_id']) . '</p><br /></td>';
        }
        // Meeting URL
        if ($action == 'update_event' && isset($difference_array['old_meeting_url'], $difference_array['new_meeting_url']) && $difference_array['old_meeting_url'] != $difference_array['new_meeting_url'] && $difference_array['old_meeting_url'] != '') {
            $event_update_meeting_url_row = '<td><strong>Previous</strong><p style="color: #d9534f;">' . ($difference_array['old_meeting_url']) . '</p></td><td><strong>New</strong><p style="color: #81b431;">' . ($difference_array['new_meeting_url']) . '</p><br /></td>';
        }
        // Meeting Phone
        if ($action == 'update_event' && isset($difference_array['old_meeting_call_number'], $difference_array['new_meeting_call_number']) && $difference_array['old_meeting_call_number'] != $difference_array['new_meeting_call_number'] && $difference_array['old_meeting_call_number'] != '') {
            $event_update_meeting_call_number_row = '<td><strong>Previous</strong><p style="color: #d9534f;">' . ($difference_array['old_meeting_call_number']) . '</p></td><td><strong>New</strong><p style="color: #81b431;">' . ($difference_array['new_meeting_call_number']) . '</p><br /></td>';
        }

        // Set Interviwers change
        if ($action == 'update_event' && sizeof($difference_array)) {
            $removed_interviewers = $added_interviewers = 0;
            $type = 'Interviewer(s)';

            if ($event_details['users_type'] == 'employee')
                $type = 'Participant(s)';
            if ($event_details['category'] == 'training-session')
                $type = 'Attendee(s)';

            if (isset($difference_array['added_interviewers']) && sizeof($difference_array['added_interviewers']))
                $added_interviewers += count($difference_array['added_interviewers']);
            if (isset($difference_array['added_external_interviewers']) && sizeof($difference_array['added_external_interviewers']))
                $added_interviewers += count($difference_array['added_external_interviewers']);
            if (isset($difference_array['removed_interviewers']) && sizeof($difference_array['removed_interviewers']))
                $removed_interviewers += count($difference_array['removed_interviewers']);
            if (isset($difference_array['removed_external_interviewers']) && sizeof($difference_array['removed_external_interviewers']))
                $removed_interviewers += count($difference_array['removed_external_interviewers']);
            if ($removed_interviewers != 0 || $added_interviewers != 0)
                $event_block_changed++;
            if ($removed_interviewers != 0 && $added_interviewers != 0)
                $event_subject_row = " - ($added_interviewers) $type added and ($removed_interviewers) removed.";
            else if ($added_interviewers != 0)
                $event_subject_row = " - ($added_interviewers) $type added.";
            else if ($removed_interviewers != 0)
                $event_subject_row = " - ($removed_interviewers) $type removed.";
        }

        // Set applicant change
        // Applicant
        if ($action == 'update_event' && sizeof($difference_array) && isset($difference_array['old_applicant_job_sid'], $difference_array['new_applicant_job_sid']) && $event_details['users_type'] == 'applicant') {
            // Get the old applicant details
            $event_update_applicant_name_row = '<td><strong>Previous</strong><p style="color: #d9534f;">' . (ucwords($_this->calendar_model->get_applicant_name($difference_array['old_applicant_job_sid']))) . '</p></td><td><strong>New</strong><p style="color: #81b431;">' . (ucwords($user_info['first_name'] . ' ' . $user_info['last_name'])) . '</p><br /></td>';
            $event_subject_row = " - Applicant has been changed.";
            $event_block_changed++;
        }
        // Employee
        if ($action == 'update_event' && sizeof($difference_array) && isset($difference_array['old_applicant_job_sid'], $difference_array['new_applicant_job_sid']) && $event_details['users_type'] == 'employee') {
            // Get the old employee details
            $event_update_employee_name_row = '<td><strong>Previous</strong><p style="color: #d9534f;">' . (ucwords($_this->calendar_model->get_interviewer_name($difference_array['old_applicant_job_sid']))) . '</p></td><td><strong>New</strong><p style="color: #81b431;">' . (ucwords($event_details['employer_details']['value'])) . '</p><br /></td>';
            $event_subject_row = " - Employee has been changed.";
            $event_block_changed++;
        }
        // User phone number
        if ($action == 'update_event' && sizeof($difference_array) && isset($difference_array['old_users_phone'], $difference_array['old_users_phone']) && $difference_array['new_users_phone'] != '' && $difference_array['old_users_phone'] != '') {
            $event_update_user_phone_row = '<td><strong>Previous</strong><p style="color: #d9534f;">' . ($difference_array['old_users_phone']) . '</p></td><td><strong>New</strong><p style="color: #81b431;">' . ($difference_array['new_users_phone']) . '</p><br /></td>';
            $event_subject_row = " - " . ($event_details['users_type'] == 'personal' ? 'Person' : ucfirst($event_details['users_type'])) . " phone number has been changed.";
            $event_block_changed++;
        }

        // _e($event_subject_row, true);
        // _e($difference_array, true);

        //
        // Get template header and footer
        $message_hf = message_header_footer($company_id, ucwords($company_info["CompanyName"]));
        // Template start
        // Seperator
        $seperator = '<tr><td style="width: 100%;"><hr /></td></tr>';
        //
        $from = FROM_EMAIL_EVENTS;
        $from_name = ucwords($company_info["CompanyName"]);
        $applicant_name = ucwords($user_info['first_name'] . ' ' . $user_info['last_name']);
        // Set subject for 'Personal' type
        // and categories 'Call, Email'
        if ($event_details['users_type'] == 'personal' && ($event_details['category'] == 'call' || $event_details['category'] == 'email'))
            $email_subject = ucwords($event_details['category']) . ' - Has been scheduled with ' . ucwords($event_details['users_name']);
        else if ($event_details['users_type'] == 'personal' && ($event_details['category'] == 'training-session' || $event_details['category'] == 'Appointment'))
            $email_subject = ucwords($event_details['category']) . ' - Has been scheduled';
        else
            $email_subject = ucwords($event_category) . ' - ' . ucwords(isset($company_info['requested_event_status']) ? $company_info['requested_event_status'] : $event_details['event_status']) . ' ' . ucwords($company_info['CompanyName']);

        // Update email subject with the change
        if ($action == 'update_event')
            $email_subject .= $event_block_changed > 1 ? ' - Event details have been changed.' : $event_subject_row;
        //
        $heading_top = $duration == -1 ? '' : '<tr><td><br /><h3 style="text-align: center;">Your upcoming appointment is starting in ' . (ucwords($duration)) . '</h3><br /></td></tr>';
        if ($action == 'confirm')
            $heading_top = '<tr><td><br /><h3 style="text-align: center;">{{EVENT_HEADING}}</h3><br /></td></tr>';

        // Set greet heading
        $heading_greet = '<tr><td>';
        if ($action == 'confirm')
            $heading_greet .= '<p style="font-size: 20px;">Dear <b>{{first_name}} {{last_name}},</b></p>';
        else
            $heading_greet .= '<p style="font-size: 20px;">Dear <b>{{user_name}},</b></p>';
        $heading_greet .= '</td></tr>';

        // Set content
        $to_content = '';
        // For reminder and cron
        if ($action == 'send_reminder_emails' || $action == 'send_cron_reminder_emails') {
            $to_content .= '<tr><td>';
            $emailTemplateData = get_email_template(CALENDAR_EVENT_REMINDER);
            $emailTemplateBody = $emailTemplateData['text'];
            $emailTemplateBody = str_replace('{{event_category}}', $event_category, $emailTemplateBody);
            $to_content .= $emailTemplateBody;
            $to_content .= '</td></tr>';
        } else if ($action == 'update_event' || $action == 'drop_update_event' || $action == 'drag_update_event') {
            $to_content .= '<tr><td>';
            $emailTemplateData = get_email_template(CALENDAR_EVENT_UPDATE);
            $emailTemplateBody = $emailTemplateData['text'];
            $to_content .= $emailTemplateBody;
            $to_content .= '</td></tr>';
        } else if ($action == 'confirm') {
            //
            $to_content .= '<tr><td>';
            $emailTemplateData = get_email_template(INTERVIEW_EMAIL_CONFIRMATION);
            $emailTemplateBody = $emailTemplateData['text'];
            $emailTemplateBody = str_replace('{{event_category}}', $event_category, $emailTemplateBody);
            $to_content .= $emailTemplateBody;
            $to_content .= '</td></tr>';
            //
        } else {
            // Set subject for 'Personal' type
            // and categories 'Call, Email'
            if ($event_details['users_type'] == 'personal' && $event_details['category'] == 'call') {
                $to_content .= '<tr><td>';
                $emailTemplateData = get_email_template(CALENDAR_CREATE_EMAIL_CALL);
                $emailTemplateBody = $emailTemplateData['text'];
                $emailTemplateBody = str_replace('{{event_category}}', ucwords($event_category), $emailTemplateBody);
                $emailTemplateBody = str_replace('{{event_status}}', ucwords($event_details['event_status']), $emailTemplateBody);
                $to_content .= $emailTemplateBody;
                $to_content .= '</td></tr>';
            } else if ($event_details['users_type'] == 'personal' && $event_details['category'] == 'email') {
                $to_content .= '<tr><td>';
                $emailTemplateData = get_email_template(CALENDAR_CREATE_EMAIL_EMAIL);
                $emailTemplateBody = $emailTemplateData['text'];
                $emailTemplateBody = str_replace('{{event_category}}', ucwords($event_category), $emailTemplateBody);
                $emailTemplateBody = str_replace('{{event_status}}', ucwords($event_details['event_status']), $emailTemplateBody);
                $to_content .= $emailTemplateBody;
                $to_content .= '</td></tr>';
            } else if ($event_details['users_type'] == 'personal' && $event_details['category'] == 'training-session') {
                $to_content .= '<tr><td>';
                $emailTemplateData = get_email_template(CALENDAR_CREATE_EMAIL_TRAINING);
                $emailTemplateBody = $emailTemplateData['text'];
                $emailTemplateBody = str_replace('{{event_category}}', ucwords($event_category), $emailTemplateBody);
                $emailTemplateBody = str_replace('{{event_status}}', ucwords($event_details['event_status']), $emailTemplateBody);
                $to_content .= $emailTemplateBody;
                $to_content .= '</td></tr>';
                $to_content .= $interviewers_rows;
            } else if ($event_details['users_type'] == 'personal' && $event_details['category'] == 'Appointment') {
                $to_content .= '<tr><td>';
                $emailTemplateData = get_email_template(CALENDAR_CREATE_EMAIL_TRAINING);
                $emailTemplateBody = $emailTemplateData['text'];
                $to_content .= $emailTemplateBody;
                $to_content .= '</td></tr>';
                $to_content .= $interviewers_rows;
            } else {

                $to_content .= '<tr><td>';
                $emailTemplateData = get_email_template(CREATE_INTERVIEW_EMAIL);
                $emailTemplateBody = $emailTemplateData['text'];
                $emailTemplateBody = str_replace('{{event_category}}', ucwords($event_category), $emailTemplateBody);
                $emailTemplateBody = str_replace('{{event_status}}', ucwords($event_details['event_status']), $emailTemplateBody);
                $to_content .= $emailTemplateBody;
                $to_content .= '</td></tr>';
            }
        }

        //
        // Set booking heading
        $heading_booking = '<tr><td style="font-size: 20px;"><strong>Event details</strong><br /><br /></td></tr>';
        // Set event type
        $event_type_row = '<tr><td style="font-size: 20px;"><strong>Type</strong></td></tr>';
        $event_type_row .= '<tr><td style="font-size: 20px;"><p>' . ($event_update_category_row ? $event_update_category_row : $event_category) . '</p></td></tr>';

        $event_date_time_row = '';
        $event_date_time_row .= '<tr>';
        $event_date_time_row .= '<td>';
        $event_date_time_row .= '    <table style="width: 100%; border: 0 !important;">';
        $event_date_time_row .= '<tr>';
        $event_date_time_row .= '<td style="font-size: 20px;"><br /><strong>Event Date</strong></td>';
        if ($action == 'confirm')
            $event_date_time_row .= '{{NEW_DATE_HEADING}}';
        $event_date_time_row .= '</tr>';
        $event_date_time_row .= '<tr>';

        $event_date_time_row .= '<td style="font-size: 20px;"><p>' . ($event_update_date_row != '' ? $event_update_date_row : '{{EVENT_DATE}}') . ' <b>({{EVENT_TIMEZONE}})</b></p></td>';
        if ($action == 'confirm')
            $event_date_time_row .= '{{NEW_EVENT_DATE}}';
        $event_date_time_row .= '</tr>';

        $event_date_time_row .= '<tr><td style="width: 100%;"><hr /></td></tr>';

        $event_date_time_row .= '<tr>';
        $event_date_time_row .= '<td style="font-size: 20px;"><br /><strong>Event Time</strong></td>';
        if ($action == 'confirm')
            $event_date_time_row .= '{{NEW_TIME_HEADING}}';
        $event_date_time_row .= '</tr>';
        $event_date_time_row .= '<tr>';

        $event_date_time_row .= '<td style="font-size: 20px;"><p>' . ($event_update_start_time_row != '' ? $event_update_start_time_row : '{{EVENT_START_TIME}} - {{EVENT_END_TIME}}') . ($event_update_end_time_row != '' ? '<br />' . $event_update_end_time_row : '') . ' <b>({{EVENT_TIMEZONE}})</b></p></td>';

        if ($action == 'confirm')
            $event_date_time_row .= '{{NEW_EVENT_TIME}}';
        $event_date_time_row .= '</tr>';
        if ($action == 'confirm') {
            $event_date_time_row .= '<tr><td><hr /></td></tr>';
            // $event_date_time_row  .=      '<tr><td>Click on the below button to visit the event.</td></tr>';
            $event_date_time_row .= '<tr style="font-size: 20px;"><td>{{EVENT_LINK}}</td></tr>';
        }
        $event_date_time_row .= '</table>';
        $event_date_time_row .= '</td>';
        $event_date_time_row .= '</tr>';

        if ($action == 'confirm')
            $event_date_time_row .= '{{NEW_EVENT_DETAILS}}';

        // Set comment
        $event_comment_row = '<tr><td style="font-size: 20px;"><br /><strong>Comment</strong></td></tr>';
        $event_comment_row .= $event_update_comment_row != '' ? '<tr><td style="font-size: 20px;">' . $event_update_comment_row . '</td></tr>' : '';
        $event_comment_row .= '<tr>' . ($event_update_comment_change_row != '' ? $event_update_comment_change_row : '<td style="font-size: 20px;"><p>' . ($event_details['comment']) . '</p></td>') . '</tr>';

        // Set message to applicant
        $event_message_row = '<tr><td style="font-size: 20px;"><br /><strong>Message Details</strong><br /><br /></td></tr>';
        $event_message_row .= $event_update_message_row != '' ? '<tr><td style="font-size: 20px;"><p>' . $event_update_message_row . '</p><br /></td></tr>' : '';
        $event_message_row .= '<tr><td style="font-size: 20px;"><strong>Subject</strong></td></tr>';
        $event_message_row .= '<tr>' . ($event_update_message_subject_row != '' ? $event_update_message_subject_row : '<td style="font-size: 20px;"><p>' . ($event_details['subject']) . '</p><br /></td>') . '</tr>';
        $event_message_row .= '<tr><td><strstyle="font-size: 20px;"ong>Message</strong></td></tr>';
        $event_message_row .= '<tr>' . ($event_update_message_body_row != '' ? $event_update_message_body_row : '<td style="font-size: 20px;"><p>' . ($event_details['message']) . '</p><br /></td>') . '</tr>';
        //
        if ($event_details['message_file'] != '') {
            $event_message_row .= '<tr><td style="font-size: 20px;"><strong>Attachment</strong></td></tr>';
            $event_message_row .= '<tr><td style="font-size: 20px;">' . ($event_update_message_file_row != '' ? $event_update_message_file_row : '<a href="' . AWS_S3_BUCKET_URL . '' . ($event_details['message_file']) . '" target="_blank">' . ($event_details['message_file']) . '</a>') . '</td></tr>';
        }

        // Set meeting details
        $event_meeting_row = '<tr><td style="font-size: 20px;"><br /><strong>Meeting Details</strong><br /><br /></td></tr>';
        $event_meeting_row .= $event_update_meeting_row != '' ? '<tr><td><p>' . $event_update_meeting_row . '</p><br /></td></tr>' : '';
        $event_meeting_row .= '<tr><td style="font-size: 20px;"><strong>ID</strong></td></tr>';
        $event_meeting_row .= '<tr>' . ($event_update_meeting_id_row != '' ? $event_update_meeting_id_row : '<td style="font-size: 20px;"><p>' . ($event_details['meeting_id']) . '</p><br /></td>') . '</tr>';
        $event_meeting_row .= '<tr><td style="font-size: 20px;"><strong>Phone</strong></td></tr>';
        $event_meeting_row .= '<tr>' . ($event_update_meeting_call_number_row != '' ? $event_update_meeting_call_number_row : '<td><p>' . ($event_details['meeting_call_number']) . '</p><br /></td>') . '</tr>';
        $event_meeting_row .= '<tr><td style="font-size: 20px;"><strong>Link</strong></td></tr>';
        $event_meeting_row .= '<tr>' . ($event_update_meeting_url_row ? $event_update_meeting_url_row : '<td style="font-size: 20px;"><p>' . ($event_details['meeting_url']) . '</p><br /><br /></td>') . '</tr>';

        // Set address
        $event_address_row = '<tr><td style="font-size: 20px;"><br /><strong>Address</strong><br /><br /></td></tr>';
        $event_address_row .= '<tr><td style="font-size: 20px;"><p>' . ($event_update_address_row != '' ? $event_update_address_row : $event_details['address']) . '</p></td></tr>';
        $event_address_row .= '<tr>';
        $event_address_row .= '     <td>';
        $event_address_row .= '         <a href="https://maps.google.com/maps?z=12&t=m&q=' . (urlencode($event_details['address'])) . '">';
        $event_address_row .= '             <img src="https://maps.googleapis.com/maps/api/staticmap?center=' . (urlencode($event_details['address'])) . '&zoom=13&size=400x400&key=' . GOOGLE_MAP_API_KEY . '&markers=color:blue|label:|' . (urlencode($event_details['address'])) . '" alt="No Map Found!" >';
        $event_address_row .= '         </a>';
        $event_address_row .= '     </td>';
        $event_address_row .= '</tr>';

        // Set calendar download links
        $_this->load->library('encrypt');

        // Create event link btn
        // Encode event_token
        $event_token = str_replace('/', '$eb$eb$', $_this->encrypt->encode($event_details['sid'] . ':' . $event_details['company_id']));
        // Set event link
        $event_link = base_url('calendar/my_events/' . ($event_token) . '');
        // Create event link button
        $event_link_btn = '<a href="' . ($event_link) . '"
        style="
            background-color: #009966;
            font-size: 16px;
            font-weight: bold;
            font-family: sans-serif;
            text-decoration: none;
            line-height: 40px;
            padding: 0 15px;
            color: #fff;
            border-radius: 5px;
            text-align: center;
            display: inline-block"
        target="_blank"
        >View Event</a>';

        //
        $with_info_box = '';
        if ($event_details['users_type'] == 'applicant') {
            // Set applicant info box
            $with_info_box = '<tr><td style="font-size: 20px;"><br /><p><strong>Applicant Details</strong></p><br /><br /></td></tr>';
            $with_info_box .= '<tr><td style="font-size: 20px;"><strong>Name: </strong></td></tr>';
            $with_info_box .= '<tr>' . ($event_update_applicant_name_row != '' ? $event_update_applicant_name_row : '<td><p>' . (ucwords($user_info['first_name'] . ' ' . $user_info['last_name'])) . '</p><br /></td>') . '</tr>';
            //
            if (isset($user_info['phone']) && $user_info['phone'] != '') {
                $with_info_box .= '<tr><td style="font-size: 20px;"><strong>Phone: </strong></td></tr>';
                $with_info_box .= '<tr>' . ($event_update_user_phone_row != '' ? $event_update_user_phone_row : '<td><p>' . ($user_info['phone']) . '</p><br /></td>') . '</tr>';
            }
            //
            if (isset($user_info['city']) && $user_info['city'] != '') {
                $with_info_box .= '<tr><td style="font-size: 20px;"><strong>City: </strong></td></tr>';
                $with_info_box .= '<tr><td style="font-size: 20px;"><p>' . ($user_info['city']) . '</p><br /></td></tr>';
            }
            //
            $applicant_job_list_sid = 0;

            if (isset($user_info['job_applications']) && !empty($user_info['job_applications'])) {
                $applicant_job_list_sid = $user_info['job_applications'][0]['sid'];
                $applicant_jobs_list = $event_details['applicant_jobs_list'];

                if ($applicant_jobs_list != '' && $applicant_jobs_list != null) {
                    $applicant_jobs_array = explode(',', $applicant_jobs_list);
                }

                $with_info_box .= '<tr><td style="font-size: 20px;"><p><b>Job(s) Applied:</b></p></td></tr>';

                if (!empty($applicant_jobs_array)) {
                    foreach ($user_info['job_applications'] as $job_application) {
                        $applicant_sid = $job_application['sid'];

                        $job_title = $job_application['job_title'] != '' ? $job_application['job_title'] : $job_application['desired_job_title'];
                        if ($job_title == '')
                            continue;
                        if (in_array($applicant_sid, $applicant_jobs_array)) {


                            // For added jobs
                            if ($action == 'update_event' && isset($difference_array['added_jobs']) && sizeof($difference_array['added_jobs']) && in_array($job_application['sid'], $difference_array['added_jobs'])) {
                                $with_info_box .= '<tr style="background-color: #81b431; font-weight: bolder;"><td><p>' . $job_title . ' <strong>[Added]</strong></p></td></tr>';
                            } else
                                $with_info_box .= '<tr><td style="font-size: 20px;"><p>' . $job_title . '</p></td></tr>';

                            $applicant_job_list_sid = $job_application['sid'];
                        }

                        // For removed jobs
                        if ($action == 'update_event' && isset($difference_array['removed_jobs']) && in_array($job_application['sid'], $difference_array['removed_jobs'])) {
                            $with_info_box .= '<tr style="background-color: #d9534f; font-weight: bold;"><td><p>' . $job_title . ' <strong>[Removed]</strong></p></td></tr>';
                        }
                    }
                } else {
                    $job_application = $user_info['job_applications'];
                    $job_application_last_index = count($job_application) - 1;

                    for ($i = 0; $i < count($job_application); $i++) {
                        $applicant_sid = $job_application[$i]['sid'];
                        $job_title = $job_application[$i]['job_title'] != '' ? $job_application[$i]['job_title'] : $job_application[$i]['desired_job_title'];

                        if ($job_title == '')
                            continue;

                        $with_info_box .= '<tr><td><p>' . $job_title . '</p></td></tr>';
                        $applicant_job_list_sid = $job_application[$job_application_last_index]['sid'];
                    }
                }
            }
            //
            $sms_replace_array['resume_link'] = '';
            $sms_replace_array['profile_link'] = base_url('applicant_profile') . '/' . $user_info['sid'] . '/' . $applicant_job_list_sid;
            if (isset($user_info['resume']) && $user_info['resume'] != '') {
                $with_info_box .= '<tr><td><br />
                    <a href="' . AWS_S3_BUCKET_URL . $user_info['resume'] . '" target="_blank" style="' . DEF_EMAIL_BTN_STYLE_PRIMARY . '">Download Resume</a>
                    <a href="' . base_url('applicant_profile') . '/' . $user_info['sid'] . '/' . $applicant_job_list_sid . '" target="_blank" style="' . DEF_EMAIL_BTN_STYLE_DANGER . '" >View Profile</a>
                </p></td></tr>';
                $sms_replace_array['resume_link'] = AWS_S3_BUCKET_URL . $user_info['resume'];
            }
            $with_info_box .= $seperator;
        } else if ($event_details['users_type'] == 'employee') {
            $with_info_box .= '<tr><td style="font-size: 20px;"><strong>Host details</strong><br /><br /></td></tr>';
            $with_info_box .= '<tr><td style="font-size: 20px;"><strong>Name</strong></td></tr>';
            $with_info_box .= '<tr>' . ($event_update_employee_name_row != '' ? $event_update_employee_name_row : '<td style="font-size: 20px;"><p>' . ($event_details['employer_details']['value']) . '</p><br /></td>') . '</tr>';
            if (isset($event_details['employer_details']['phone_number']) && $event_details['employer_details']['phone_number'] != '') {
                $with_info_box .= '<tr><td style="font-size: 20px;"><strong>Phone</strong></td></tr>';
                $with_info_box .= '<tr>' . ($event_update_user_phone_row != '' ? $event_update_user_phone_row : '<td style="font-size: 20px;"><p>' . ($event_details['employer_details']['phone_number']) . '</p></td>') . '</tr>';
            }
            $with_info_box .= $seperator;
        }

        $table_start = '<table style="width: 100%;"><tr><td style="width: 100%; height: 10px; background-color: #cccccc;"></td></tr>';
        $table_end = '</table>';

        $email_message = $message_hf['header'];
        $email_message .= $table_start;
        $email_message .= $heading_top;
        $email_message .= $heading_greet;
        $email_message .= $to_content;
        $email_message .= $seperator;
        // Event details section
        $email_message .= '{{EMAIL_STATUS_BUTTONS}}';
        $email_message .= $seperator;
        $email_message .= $heading_booking;
        $email_message .= $event_type_row;
        $email_message .= $event_date_time_row;
        // $email_message .= $event_time_row;
        // $email_message .= $seperator;
        if ($event_details['comment_check'] == 1) {
            // Show when comment is set
            $email_message .= '{{COMMENT_BOX}}';
        }
        // Show when message is checked
        if ($event_details['message_check'] == 1) {
            $email_message .= '{{MESSAGE_BOX}}';
            // $email_message .= $seperator;
        }
        // Show when meeting is checked
        if ($event_details['meeting_check'] == 1) {
            $email_message .= $event_meeting_row;
            $email_message .= $seperator;
        }
        $email_message .= $interviewers_rows;
        if ($interviewers_rows != '')
            $email_message .= $seperator;
        $email_message .= '{{WITH_INFO_BOX}}';
        // Show address
        if ($event_details['address'] != '') {
            $email_message .= $event_address_row;
            $email_message .= $seperator;
        }

        // $email_message .= $calendar_rows;
        $email_message .= '{{CALENDAR_ROWS}}';
        $email_message .= $table_end;
        $email_message .= $message_hf['footer'];

        // _e($with_info_box, true);
        // _e($email_message, true, true);

        // For Send Reminder Emails
        // handle error
        if ($action == 'send_reminder_emails' && !sizeof($email_list)) {
            header('content-type: application/json');
            echo json_encode(array('Response' => 'Error! this event is no longer available.', 'Redirect' => FALSE, 'Status' => FALSE));
            exit(0);
        }

        // Reminder and cron emails
        if ($action == 'send_reminder_emails' || $action == 'send_cron_reminder_emails') {
            $date = date('Y-m-d H:i:s');
            // Set user timezone
            $user_info['timezone'] = $_this->calendar_model->get_timezone('company', $event_details['company_id']);
            foreach ($email_list as $k0 => $v0) {
                if (empty($v0['timezone']))
                    $v0['timezone'] = $user_info['timezone'];
                $event_link_check = false;
                switch ($v0['type']) {
                    case 'interviewer':
                    case 'employee':
                    case 'personal':
                        //
                        $event_link_check = true;
                        break;
                }
                if (($v0['type'] == 'applicant' || $v0['type'] == 'non-employee interviewer') && isset($event_details['event_timezone'])) {
                    $v0['timezone'] = $event_details['event_timezone'];
                }

                $user_message = $email_message; //Send Email to Applicant
                if ($event_link_check)
                    $user_message = str_replace('{{EVENT_LINK}}', $event_link_btn, $user_message);
                $user_message = str_replace('{{user_name}}', $v0['value'], $user_message);
                $user_message = str_replace(
                    '{{WITH_INFO_BOX}}',
                    ($v0['type'] == 'employee' || $v0['type'] == 'applicant' ? ' ' : $with_info_box),
                    $user_message
                );
                $user_message = str_replace(
                    '{{COMMENT_BOX}}',
                    ($v0['type'] == 'employee' || $v0['type'] == 'applicant' ? ' ' : $event_comment_row . $seperator),
                    $user_message
                );
                $user_message = str_replace('{{target_user}}', ucwords($company_info['CompanyName']), $user_message);

                $user_message = str_replace('{{EVENT_DATE}}', reset_datetime(array(
                    'datetime' => $event_details['date'] . $event_details['event_start_time'],
                    'from_format' => 'Y-m-dh:iA',
                    'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                    'new_zone' => $v0['timezone'],
                    '_this' => $_this

                )), $user_message);
                $user_message = str_replace('{{EVENT_START_TIME}}', reset_datetime(array(
                    'datetime' => $event_details['date'] . $event_details['event_start_time'],
                    'from_format' => 'Y-m-dh:iA',
                    'format' => 'h:i A',
                    'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                    'new_zone' => $v0['timezone'],
                    '_this' => $_this

                )), $user_message);
                $user_message = str_replace('{{EVENT_END_TIME}}', reset_datetime(array(
                    'datetime' => $event_details['date'] . $event_details['event_end_time'],
                    'from_format' => 'Y-m-dh:iA',
                    'format' => 'h:i A',
                    'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                    'new_zone' => $v0['timezone'],
                    '_this' => $_this

                )), $user_message);
                $user_message = str_replace('{{EVENT_TIMEZONE}}', $v0['timezone'], $user_message);

                //
                if ($action != 'send_cron_reminder_emails') {
                    // Set data array
                    $data_array = array();
                    $data_array['event_sid'] = $event_sid;
                    $data_array['email_address'] = $v0['email_address'];
                    $data_array['user_id'] = $v0['id'];
                    $data_array['user_name'] = $v0['value'];
                    $data_array['user_type'] = $v0['type'] != '' ? $v0['type'] : 'employee';
                    $data_array['created_at'] = $date;
                    // Create record in db
                    $_this->calendar_model->save_event_sent_email_reminder_history($data_array);
                }

                if ($v0['type'] === 'person') {
                    $user_email_status_button_rows = '';
                } else {
                    // // Add event status buttons
                    $user_email_status_button_rows =
                        generate_event_status_rows(
                            $event_details['sid'],
                            $v0['id'],
                            $v0['type'],
                            $v0['value'],
                            $v0['email_address'],
                            $event_details['category'],
                            $event_details['learning_center_training_sessions'],
                            $_this
                        );
                }
                $user_message = str_replace('{{EMAIL_STATUS_BUTTONS}}', $user_email_status_button_rows, $user_message);

                // Set calendar
                $download_url_vcs = base_url('download-event') . '/' . (str_replace('/', '$eb$eb$1', $_this->encrypt->encode('type=vcs&uname=' . ($v0['value']) . '&uemail=' . ($v0['email_address']) . '&utype=' . ($v0['type']) . '&uid=' . ($v0['id']) . '&eid=' . $event_details['sid'])));
                $download_url_ics = base_url('download-event') . '/' . (str_replace('/', '$eb$eb$1', $_this->encrypt->encode('type=ics&uname=' . ($v0['value']) . '&uemail=' . ($v0['email_address']) . '&utype=' . ($v0['type']) . '&uid=' . ($v0['id']) . '&eid=' . $event_details['sid'])));
                $download_url_gc = base_url('download-event') . '/' . (str_replace('/', '$eb$eb$1', $_this->encrypt->encode('type=gc&uname=' . ($v0['value']) . '&uemail=' . ($v0['email_address']) . '&utype=' . ($v0['type']) . '&uid=' . ($v0['id']) . '&eid=' . $event_details['sid'])));
                $calendar_rows = '<tr>';
                $calendar_rows .= '     <td style="font-size: 20px;"><br /><strong>Calendar event</strong><br /><br /></td>';
                $calendar_rows .= '</tr>';
                $calendar_rows .= '<tr>';
                $calendar_rows .= '     <td style="font-size: 20px;"><img src="' . (base_url('assets/calendar_icons/outlook.png')) . '" width="20"/>&nbsp;&nbsp;<a href="' . ($download_url_vcs) . '">Add to Outlook Calendar</a></td>';
                $calendar_rows .= '</tr>';
                $calendar_rows .= '<tr>';
                $calendar_rows .= '     <td style="font-size: 20px;"><img src="' . (base_url('assets/calendar_icons/google.png')) . '" width="20"/>&nbsp;&nbsp;<a href="' . ($download_url_gc) . '">Add to Google Calendar</a></td>';
                $calendar_rows .= '</tr>';
                $calendar_rows .= '<tr>';
                $calendar_rows .= '     <td style="font-size: 20px;"><img src="' . (base_url('assets/calendar_icons/apple.png')) . '" width="20"/>&nbsp;&nbsp;<a href="' . ($download_url_ics) . '">Add to Apple Calendar</a></td>';
                $calendar_rows .= '</tr>';
                $calendar_rows .= '<tr>';
                $calendar_rows .= '     <td style="font-size: 20px;"><img src="' . (base_url('assets/calendar_icons/calendar.png')) . '" width="20"/>&nbsp;&nbsp;<a href="' . ($download_url_ics) . '">Mobile & Other Calendar</a></td>';
                $calendar_rows .= '</tr>';
                $calendar_rows .= '<style>.dash-inner-block td, .dash-inner-block th{ border-bottom: none;}</style>';
                // Replace calendar rows
                $user_message = str_replace('{{CALENDAR_ROWS}}', $calendar_rows, $user_message);

                log_and_send_email_with_attachment(FROM_EMAIL_NOTIFICATIONS, $v0['email_address'], $email_subject, $user_message, $from_name, $ics_file);
            }
            if ($action != 'send_cron_reminder_emails') {
                header('content-type: application/json');
                echo json_encode(array('Response' => 'Reminder emails are sent to the selected emails.', 'Redirect' => FALSE, 'Status' => TRUE));
                exit(0);
            } else
                exit(0);
        }

        // _e($email_message, true, true);

        // Send emails

        // Don't send email to interviewers
        // and to non-employee interviewers in case of
        // type 'Personal'
        if ($event_details['users_type'] == 'personal' && !in_array(strtolower($event_category), array('email', 'call')))
            $send_to_extraparticipants = $send_to_interviewers = false;

        // Don't send an email to applicant/employee
        // in case when only comment field is changed
        if ($action == 'update_event' && count($difference_array) == 2 && isset($difference_array['new_comment'])) {
            $send_to_ae = false;
        }
        if ($action == 'update_event' && count($difference_array) == 4 && isset($difference_array['old_comment'], $difference_array['old_comment_check']) && $difference_array['new_comment_check'] == 1) {
            $send_to_ae = false;
        }
        if ($action == 'update_event' && count($difference_array) == 4 && isset($difference_array['old_comment'], $difference_array['old_comment_check']) && $difference_array['new_comment_check'] == '') {
            $send_to_ae = $send_to_extraparticipants = $send_to_interviewers = false;
        }

        $message_index_array = array('old_subject', 'old_message', 'old_message_file', 'old_message_check');
        // Don't send an email to particpants/interviewers
        // in case when only message field is changed
        if ($action == 'update_event' && sizeof($difference_array)) {
            $is_exist = 0;
            foreach ($difference_array as $k0 => $v0) {
                if (in_array($k0, $message_index_array))
                    $is_exist = $is_exist + 2;
            }

            if ($is_exist != 0 && $is_exist == sizeof($difference_array))
                $send_to_interviewers = $send_to_extraparticipants = false;
        }

        // Don't send email when only
        // message box is unchecked
        // or event title is changed
        if ($action == 'update_event' && count($difference_array) == 2 && (isset($difference_array['old_message_check']) || isset($difference_array['old_event_title']))) {
            $send_to_ae = $send_to_extraparticipants = $send_to_interviewers = false;
        }
        if ($action == 'update_event' && count($difference_array) == 6 && isset($difference_array['old_subject'], $difference_array['old_message'], $difference_array['old_message_check']) && $difference_array['new_message_check'] == '') {
            $send_to_ae = $send_to_extraparticipants = $send_to_interviewers = false;
        }

        // Don't send email when only
        // comment box is unchecked
        if ($action == 'update_event' && count($difference_array) == 2 && isset($difference_array['old_comment_check'])) {
            $send_to_ae = $send_to_extraparticipants = $send_to_interviewers = false;
        }

        // _e($difference_array, true);

        // Send email to applicant/employee/person
        if ($send_to_ae) {
            if ($event_details['users_type'] == 'applicant' && isset($event_details['event_timezone'])) {
                $user_info['timezone'] = $event_details['event_timezone'];
            }
            $event_link_check = true;
            $user_message = $email_message;
            $user_message = str_replace('{{user_name}}', $applicant_name, $user_message);
            $user_message = str_replace('{{COMMENT_BOX}}', ' ', $user_message);
            // Show when message is checked
            if ($event_details['message_check'] == 1)
                $user_message = str_replace('{{MESSAGE_BOX}}', $event_message_row . $separator, $user_message);
            if (!$is_return)
                $user_message = str_replace('{{WITH_INFO_BOX}}', ' ', $user_message);
            $user_message = str_replace('{{target_user}}', ucwords($company_info['CompanyName']), $user_message);


            if (($event_link_check && $event_details['users_type'] == 'employee') || ($is_return))
                $user_message = str_replace('{{EVENT_LINK}}', $event_link_btn, $user_message);

            // For difference
            if (isset($difference_array) && sizeof($difference_array)) {
                if (isset($difference_array['old_date'])) {
                    $user_message = str_replace('{{EVENT_DATE_OLD}}', reset_datetime(array(
                        'datetime' => $difference_array['old_date'] . $difference_array['timezone_old_start_time'],
                        'from_format' => 'Y-m-dh:iA',
                        'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                        'new_zone' => $user_info['timezone'],
                        '_this' => $_this

                    )), $user_message);
                }

                if (isset($difference_array['old_event_start_time'])) {
                    $user_message = str_replace('{{EVENT_START_TIME_OLD}}', reset_datetime(array(
                        'datetime' => $difference_array['old_event_start_time'],
                        'from_format' => 'h:iA',
                        'format' => 'h:i A',
                        'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                        'new_zone' => $user_info['timezone'],
                        '_this' => $_this

                    )), $user_message);
                }

                if (isset($difference_array['old_event_end_time'])) {
                    $user_message = str_replace('{{EVENT_END_TIME_OLD}}', reset_datetime(array(
                        'datetime' => $difference_array['old_event_end_time'],
                        'from_format' => 'h:iA',
                        'format' => 'h:i A',
                        'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                        'new_zone' => $user_info['timezone'],
                        '_this' => $_this

                    )), $user_message);
                }
            }
            if (!$is_return) {
                $user_message = str_replace('{{EVENT_TIMEZONE}}', $user_info['timezone'], $user_message);
                $user_message = str_replace('{{EVENT_DATE}}', reset_datetime(array(
                    'datetime' => $event_details['date'] . $event_details['event_start_time'],
                    'from_format' => 'Y-m-dh:iA',
                    'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                    'new_zone' => $user_info['timezone'],
                    '_this' => $_this
                )), $user_message);

                $user_message = str_replace('{{EVENT_START_TIME}}', reset_datetime(array(
                    'datetime' => $event_details['date'] . $event_details['event_start_time'],
                    'from_format' => 'Y-m-dh:iA',
                    'format' => 'h:i A',
                    'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                    'new_zone' => $user_info['timezone'],
                    '_this' => $_this

                )), $user_message);

                $user_message = str_replace('{{EVENT_END_TIME}}', reset_datetime(array(
                    'datetime' => $event_details['date'] . $event_details['event_end_time'],
                    'from_format' => 'Y-m-dh:iA',
                    'format' => 'h:i A',
                    'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                    'new_zone' => $user_info['timezone'],
                    '_this' => $_this
                )), $user_message);
            }


            $utype = $event_details['users_type'];
            $uid = $user_info['sid'];
            $uemail = $user_info['email'];
            $uname = $user_info['first_name'] . ' ' . $user_info['last_name'];
            $p_name = ucwords($user_info['first_name'] . ' ' . $user_info['last_name']);
            //
            if ($action != 'confirm') {
                if ($event_details['users_type'] == 'personal' && in_array(strtolower($event_category), array('email', 'call'))) {
                    $user_email_status_button_rows = '';
                    $p_name = ucwords($event_details['users_name']);
                } else {
                    // Add event status buttons
                    $user_email_status_button_rows =
                        generate_event_status_rows(
                            $event_details['sid'],
                            $user_info['sid'],
                            $event_details['users_type'],
                            $user_info['first_name'] . ' ' . $user_info['last_name'],
                            $user_info['email'],
                            $event_details['category'],
                            $event_details['learning_center_training_sessions'],
                            $_this
                        );
                }
            } else
                $user_email_status_button_rows = '';
            //
            $user_message = str_replace('{{EMAIL_STATUS_BUTTONS}}', $user_email_status_button_rows, $user_message);
            $user_message = str_replace('{{PERSON_NAME}}', ucwords($p_name), $user_message);
            // Set calendar
            if ($action != 'confirm') {
                $download_url_vcs = base_url('download-event') . '/' . (str_replace('/', '$eb$eb$1', $_this->encrypt->encode('type=vcs&uname=' . ($uname) . '&uemail=' . ($uemail) . '&utype=' . ($utype) . '&uid=' . ($uid) . '&eid=' . $event_details['sid'])));
                $download_url_ics = base_url('download-event') . '/' . (str_replace('/', '$eb$eb$1', $_this->encrypt->encode('type=ics&uname=' . ($uname) . '&uemail=' . ($uemail) . '&utype=' . ($utype) . '&uid=' . ($uid) . '&eid=' . $event_details['sid'])));
                $download_url_gc = base_url('download-event') . '/' . (str_replace('/', '$eb$eb$1', $_this->encrypt->encode('type=gc&uname=' . ($uname) . '&uemail=' . ($uemail) . '&utype=' . ($utype) . '&uid=' . ($uid) . '&eid=' . $event_details['sid'])));
                $calendar_rows = '<tr>';
                $calendar_rows .= '     <td style="font-size: 20px;"><br /><strong>Calendar event</strong><br /><br /></td>';
                $calendar_rows .= '</tr>';
                $calendar_rows .= '<tr>';
                $calendar_rows .= '     <td style="font-size: 20px;"><img src="' . (base_url('assets/calendar_icons/outlook.png')) . '" width="20"/>&nbsp;&nbsp;<a href="' . ($download_url_vcs) . '">Add to Outlook Calendar</a></td>';
                $calendar_rows .= '</tr>';
                $calendar_rows .= '<tr>';
                $calendar_rows .= '     <td style="font-size: 20px;"><img src="' . (base_url('assets/calendar_icons/google.png')) . '" width="20"/>&nbsp;&nbsp;<a href="' . ($download_url_gc) . '">Add to Google Calendar</a></td>';
                $calendar_rows .= '</tr>';
                $calendar_rows .= '<tr>';
                $calendar_rows .= '     <td style="font-size: 20px;"><img src="' . (base_url('assets/calendar_icons/apple.png')) . '" width="20"/>&nbsp;&nbsp;<a href="' . ($download_url_ics) . '">Add to Apple Calendar</a></td>';
                $calendar_rows .= '</tr>';
                $calendar_rows .= '<tr>';
                $calendar_rows .= '     <td style="font-size: 20px;"><img src="' . (base_url('assets/calendar_icons/calendar.png')) . '" width="20"/>&nbsp;&nbsp;<a href="' . ($download_url_ics) . '">Mobile & Other Calendar</a></td>';
                $calendar_rows .= '</tr>';
                $calendar_rows .= '<style>.dash-inner-block td, .dash-inner-block th{ border-bottom: none;}</style>';
                // Replace calendar rows
                $user_message = str_replace('{{CALENDAR_ROWS}}', $calendar_rows, $user_message);
            }

            if ($is_return)
                return array('Subject' => $email_subject, 'Body' => $user_message, 'FromEmail' => FROM_EMAIL_NOTIFICATIONS, 'with_info_box' => $with_info_box);
            log_and_send_email_with_attachment(FROM_EMAIL_NOTIFICATIONS, $user_info['email'], $email_subject, $user_message, $from_name, $ics_file);

            // $_this->_e($email_subject, true);
            // $_this->_e($user_message, true);
        }

        // For 'personal' type
        if ($event_details['users_type'] === 'personal' && in_array(strtolower($event_category), array('email', 'call')) && $event_details['users_email'] != '' && $event_details['users_email'] != null) {
            $event_link_check = true;
            $user_message = $email_message;
            $user_message = str_replace('{{COMMENT_BOX}}', ' ', $user_message);
            // Show when message is checked
            if ($event_details['message_check'] == 1)
                $user_message = str_replace('{{MESSAGE_BOX}}', $event_message_row . $separator, $user_message);
            if (!$is_return)
                $user_message = str_replace('{{WITH_INFO_BOX}}', ' ', $user_message);
            $user_message = str_replace('{{target_user}}', ucwords($company_info['CompanyName']), $user_message);


            if (($event_link_check && $event_details['users_type'] == 'employee') || ($is_return))
                $user_message = str_replace('{{EVENT_LINK}}', $event_link_btn, $user_message);

            if (!empty($event_details['event_timezone']))
                $user_info['timezone'] = $event_details['event_timezone'];
            // For difference
            if (isset($difference_array) && sizeof($difference_array)) {
                if (isset($difference_array['old_date'])) {
                    $user_message = str_replace('{{EVENT_DATE_OLD}}', reset_datetime(array(
                        'datetime' => $difference_array['old_date'] . $difference_array['timezone_old_start_time'],
                        'from_format' => 'Y-m-dh:iA',
                        'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                        'new_zone' => $user_info['timezone'],
                        '_this' => $_this

                    )), $user_message);
                }

                if (isset($difference_array['old_event_start_time'])) {
                    $user_message = str_replace('{{EVENT_START_TIME_OLD}}', reset_datetime(array(
                        'datetime' => $difference_array['old_event_start_time'],
                        'from_format' => 'h:iA',
                        'format' => 'h:i A',
                        'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                        'new_zone' => $user_info['timezone'],
                        '_this' => $_this

                    )), $user_message);
                }

                if (isset($difference_array['old_event_end_time'])) {
                    $user_message = str_replace('{{EVENT_END_TIME_OLD}}', reset_datetime(array(
                        'datetime' => $difference_array['old_event_end_time'],
                        'from_format' => 'h:iA',
                        'format' => 'h:i A',
                        'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                        'new_zone' => $user_info['timezone'],
                        '_this' => $_this

                    )), $user_message);
                }
            }
            $user_message = str_replace('{{EVENT_DATE}}', reset_datetime(array(
                'datetime' => $event_details['date'] . $event_details['event_start_time'],
                'from_format' => 'Y-m-dh:iA',
                'format' => 'M d Y, D',
                'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                'new_zone' => $user_info['timezone'],
                '_this' => $_this

            )), $user_message);
            $user_message = str_replace('{{EVENT_START_TIME}}', reset_datetime(array(
                'datetime' => $event_details['date'] . $event_details['event_start_time'],
                'from_format' => 'Y-m-dh:iA',
                'format' => 'h:i A',
                'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                'new_zone' => $user_info['timezone'],
                '_this' => $_this

            )), $user_message);
            $user_message = str_replace('{{EVENT_END_TIME}}', reset_datetime(array(
                'datetime' => $event_details['date'] . $event_details['event_end_time'],
                'from_format' => 'Y-m-dh:iA',
                'format' => 'h:i A',
                'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                'new_zone' => $user_info['timezone'],
                '_this' => $_this

            )), $user_message);
            $user_message = str_replace('{{EVENT_TIMEZONE}}', $user_info['timezone'], $user_message);

            //
            $user_email_status_button_rows = '';
            $uid = 0;
            $utype = 'person';
            $uemail = $event_details['users_email'];
            $uname = $event_details['users_name'];
            //
            $user_message = str_replace('{{EMAIL_STATUS_BUTTONS}}', $user_email_status_button_rows, $user_message);
            $user_message = str_replace('{{PERSON_NAME}}', ucwords($user_info['first_name'] . ' ' . $user_info['first_name']), $user_message);
            $user_message = str_replace('{{user_name}}', $uname, $user_message);

            // Set calendar
            $download_url_vcs = base_url('download-event') . '/' . (str_replace('/', '$eb$eb$1', $_this->encrypt->encode('type=vcs&uname=' . ($uname) . '&uemail=' . ($uemail) . '&utype=' . ($utype) . '&uid=' . ($uid) . '&eid=' . $event_details['sid'])));
            $download_url_ics = base_url('download-event') . '/' . (str_replace('/', '$eb$eb$1', $_this->encrypt->encode('type=ics&uname=' . ($uname) . '&uemail=' . ($uemail) . '&utype=' . ($utype) . '&uid=' . ($uid) . '&eid=' . $event_details['sid'])));
            $download_url_gc = base_url('download-event') . '/' . (str_replace('/', '$eb$eb$1', $_this->encrypt->encode('type=gc&uname=' . ($uname) . '&uemail=' . ($uemail) . '&utype=' . ($utype) . '&uid=' . ($uid) . '&eid=' . $event_details['sid'])));
            $calendar_rows = '<tr>';
            $calendar_rows .= '     <td style="font-size: 20px;"><br /><strong>Calendar event</strong><br /><br /></td>';
            $calendar_rows .= '</tr>';
            $calendar_rows .= '<tr>';
            $calendar_rows .= '     <td style="font-size: 20px;"><img src="' . (base_url('assets/calendar_icons/outlook.png')) . '" width="20"/>&nbsp;&nbsp;<a href="' . ($download_url_vcs) . '">Add to Outlook Calendar</a></td>';
            $calendar_rows .= '</tr>';
            $calendar_rows .= '<tr>';
            $calendar_rows .= '     <td style="font-size: 20px;"><img src="' . (base_url('assets/calendar_icons/google.png')) . '" width="20"/>&nbsp;&nbsp;<a href="' . ($download_url_gc) . '">Add to Google Calendar</a></td>';
            $calendar_rows .= '</tr>';
            $calendar_rows .= '<tr>';
            $calendar_rows .= '     <td style="font-size: 20px;"><img src="' . (base_url('assets/calendar_icons/apple.png')) . '" width="20"/>&nbsp;&nbsp;<a href="' . ($download_url_ics) . '">Add to Apple Calendar</a></td>';
            $calendar_rows .= '</tr>';
            $calendar_rows .= '<tr>';
            $calendar_rows .= '     <td style="font-size: 20px;"><img src="' . (base_url('assets/calendar_icons/calendar.png')) . '" width="20"/>&nbsp;&nbsp;<a href="' . ($download_url_ics) . '">Mobile & Other Calendar</a></td>';
            $calendar_rows .= '</tr>';
            $calendar_rows .= '<style>.dash-inner-block td, .dash-inner-block th{ border-bottom: none;}</style>';
            // Replace calendar rows
            $user_message = str_replace('{{CALENDAR_ROWS}}', $calendar_rows, $user_message);

            if ($is_return)
                return array('Subject' => $email_subject, 'Body' => $user_message, 'FromEmail' => FROM_EMAIL_NOTIFICATIONS, 'with_info_box' => $with_info_box);
            log_and_send_email_with_attachment(FROM_EMAIL_NOTIFICATIONS, $uemail, $email_subject, $user_message, $from_name, $ics_file);

            // $_this->_e($email_subject, true);
            // $_this->_e($user_message, true);
        }

        // Send emails to Interviewers
        if ($send_to_interviewers) {
            //
            $event_link_check = true;
            foreach ($employers as $employer) { //Send Email To Employers
                // reset the timezone
                $employer['timezone'] = $_this->calendar_model->get_timezone('reset', $event_details['company_id'], $employer['timezone']);
                $user_message = $email_message;
                $links_url = '';
                if ($event_link_check)
                    $user_message = str_replace('{{EVENT_LINK}}', $event_link_btn, $user_message);
                $employer_name = ucwords($employer['first_name'] . ' ' . $employer['last_name']);
                $phone_number = $employer['PhoneNumber'];
                $user_message = str_replace('{{user_name}}', $employer_name, $user_message);
                $user_message = str_replace('{{COMMENT_BOX}}', $event_comment_row . $seperator, $user_message);
                // $user_message = str_replace('{{WITH_INFO_BOX}}', '', $user_message);
                $user_message = str_replace('{{WITH_INFO_BOX}}', $with_info_box, $user_message);
                $user_message = str_replace('{{MESSAGE_BOX}}', '', $user_message);
                $user_message = str_replace('{{target_user}}', $applicant_name, $user_message);
                $user_message = str_replace('{{EVENT_TIMEZONE}}', $employer['timezone'], $user_message);

                // For difference
                if (isset($difference_array) && sizeof($difference_array)) {
                    if (isset($difference_array['old_date'])) {
                        $user_message = str_replace('{{EVENT_DATE_OLD}}', reset_datetime(array(
                            'datetime' => $difference_array['old_date'] . $difference_array['timezone_old_start_time'],
                            'from_format' => 'Y-m-dh:iA',
                            'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                            'new_zone' => $employer['timezone'],
                            '_this' => $_this

                        )), $user_message);
                    }

                    if (isset($difference_array['old_event_start_time'])) {
                        $user_message = str_replace('{{EVENT_START_TIME_OLD}}', reset_datetime(array(
                            'datetime' => $difference_array['old_event_start_time'],
                            'from_format' => 'h:iA',
                            'format' => 'h:i A',
                            'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                            'new_zone' => $employer['timezone'],
                            '_this' => $_this

                        )), $user_message);
                    }

                    if (isset($difference_array['old_event_end_time'])) {
                        $user_message = str_replace('{{EVENT_END_TIME_OLD}}', reset_datetime(array(
                            'datetime' => $difference_array['old_event_end_time'],
                            'from_format' => 'h:iA',
                            'format' => 'h:i A',
                            'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                            'new_zone' => $employer['timezone'],
                            '_this' => $_this

                        )), $user_message);
                    }
                }

                $user_message = str_replace('{{EVENT_DATE}}', reset_datetime(array(
                    'datetime' => $event_details['date'] . $event_details['event_start_time'],
                    'from_format' => 'Y-m-dh:iA',
                    'format' => 'M d Y, D',
                    'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                    'new_zone' => $employer['timezone'],
                    '_this' => $_this

                )), $user_message);
                $user_message = str_replace('{{EVENT_START_TIME}}', reset_datetime(array(
                    'datetime' => $event_details['date'] . $event_details['event_start_time'],
                    'from_format' => 'Y-m-dh:iA',
                    'format' => 'h:i A',
                    'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                    'new_zone' => $employer['timezone'],
                    '_this' => $_this

                )), $user_message);
                $user_message = str_replace('{{EVENT_END_TIME}}', reset_datetime(array(
                    'datetime' => $event_details['date'] . $event_details['event_end_time'],
                    'from_format' => 'Y-m-dh:iA',
                    'format' => 'h:i A',
                    'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                    'new_zone' => $employer['timezone'],
                    '_this' => $_this

                )), $user_message);


                // Add event status buttons
                $interviewer_email_status_button_rows =
                    generate_event_status_rows(
                        $event_details['sid'],
                        $employer['sid'],
                        'interviewer',
                        $employer_name,
                        $employer['email'],
                        $event_details['category'],
                        $event_details['learning_center_training_sessions'],
                        $_this,
                        $links_url
                    );
                $user_message = str_replace('{{EMAIL_STATUS_BUTTONS}}', $interviewer_email_status_button_rows, $user_message);
                // $_this->_e($user_message, true);

                // Set calendar
                $download_url_vcs = base_url('download-event') . '/' . (str_replace('/', '$eb$eb$1', $_this->encrypt->encode('type=vcs&uname=' . ($employer_name) . '&uemail=' . ($employer['email']) . '&utype=interviewer&uid=' . ($employer['sid']) . '&eid=' . $event_details['sid'])));
                $download_url_ics = base_url('download-event') . '/' . (str_replace('/', '$eb$eb$1', $_this->encrypt->encode('type=ics&uname=' . ($employer_name) . '&uemail=' . ($employer['email']) . '&utype=interviewer&uid=' . ($employer['sid']) . '&eid=' . $event_details['sid'])));
                $download_url_gc = base_url('download-event') . '/' . (str_replace('/', '$eb$eb$1', $_this->encrypt->encode('type=gc&uname=' . ($employer_name) . '&uemail=' . ($employer['email']) . '&utype=interviewer&uid=' . ($employer['sid']) . '&eid=' . $event_details['sid'])));
                $calendar_rows = '<tr>';
                $calendar_rows .= '     <td style="font-size: 20px;"><br /><strong>Calendar event</strong><br /><br /></td>';
                $calendar_rows .= '</tr>';
                $calendar_rows .= '<tr>';
                $calendar_rows .= '     <td style="font-size: 20px;"><img src="' . (base_url('assets/calendar_icons/outlook.png')) . '" width="20"/>&nbsp;&nbsp;<a href="' . ($download_url_vcs) . '">Add to Outlook Calendar</a></td>';
                $calendar_rows .= '</tr>';
                $calendar_rows .= '<tr>';
                $calendar_rows .= '     <td style="font-size: 20px;"><img src="' . (base_url('assets/calendar_icons/google.png')) . '" width="20"/>&nbsp;&nbsp;<a href="' . ($download_url_gc) . '">Add to Google Calendar</a></td>';
                $calendar_rows .= '</tr>';
                $calendar_rows .= '<tr>';
                $calendar_rows .= '     <td style="font-size: 20px;"><img src="' . (base_url('assets/calendar_icons/apple.png')) . '" width="20"/>&nbsp;&nbsp;<a href="' . ($download_url_ics) . '">Add to Apple Calendar</a></td>';
                $calendar_rows .= '</tr>';
                $calendar_rows .= '<tr>';
                $calendar_rows .= '     <td style="font-size: 20px;"><img src="' . (base_url('assets/calendar_icons/calendar.png')) . '" width="20"/>&nbsp;&nbsp;<a href="' . ($download_url_ics) . '">Mobile & Other Calendar</a></td>';
                $calendar_rows .= '</tr>';
                $calendar_rows .= '<style>.dash-inner-block td, .dash-inner-block th{ border-bottom: none;}</style>';
                // Replace calendar rows
                $user_message = str_replace('{{CALENDAR_ROWS}}', $calendar_rows, $user_message);

                log_and_send_email_with_attachment(FROM_EMAIL_NOTIFICATIONS, $employer['email'], $email_subject, $user_message, $from_name, $ics_file);

                //SMS replace array
                $sms_replace_array['category_name'] = $event_category;
                $sms_replace_array['action'] = $action;
                $sms_replace_array['applicant_name'] = ucwords($user_info['first_name'] . ' ' . $user_info['last_name']);
                $sms_replace_array['contact_name'] = $employer_name;
                $sms_replace_array['event_button'] = $links_url;
                $sms_replace_array['event_url'] = base_url('calendar/my_events');
                //  replace_sms_send_email($sms_replace_array,$phone_number,$employer_name,$employer['email'],$_this,$company_id,$employer['sid']);
            }
        }

        // Send emails to non-employee Interviewers
        if ($send_to_extraparticipants) {
            if (sizeof($event_details['external_participants'])) { //Send Email To External Participants
                foreach ($event_details['external_participants'] as $event_external_participant) {
                    // $_this->_e($event_external_participant, true, true);
                    if (!empty($event_details['event_timezone'])) {
                        $event_external_participant['timezone'] = $event_details['event_timezone'];
                    }
                    $user_message = $email_message;
                    $employer_name = ucwords($event_external_participant['name']);
                    $user_message = str_replace('{{user_name}}', $employer_name, $user_message);
                    // $user_message  = str_replace('{{WITH_INFO_BOX}}', '', $user_message);
                    $user_message = str_replace('{{MESSAGE_BOX}}', '', $user_message);
                    $user_message = str_replace('{{WITH_INFO_BOX}}', $with_info_box, $user_message);
                    $user_message = str_replace('{{COMMENT_BOX}}', $event_comment_row . $seperator, $user_message);
                    $user_message = str_replace('{{target_user}}', $applicant_name, $user_message);
                    $user_message = str_replace('{{EVENT_TIMEZONE}}', $event_external_participant['timezone'], $user_message);
                    // For difference

                    if (isset($difference_array) && sizeof($difference_array)) {
                        if (isset($difference_array['old_date'])) {
                            $user_message = str_replace('{{EVENT_DATE_OLD}}', reset_datetime(array(
                                'datetime' => $difference_array['old_date'] . $difference_array['timezone_old_start_time'],
                                'from_format' => 'Y-m-dh:iA',
                                'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                                'new_zone' => $event_external_participant['timezone'],
                                '_this' => $_this

                            )), $user_message);
                        }

                        if (isset($difference_array['old_event_start_time'])) {
                            $user_message = str_replace('{{EVENT_START_TIME_OLD}}', reset_datetime(array(
                                'datetime' => $difference_array['old_event_start_time'],
                                'from_format' => 'h:iA',
                                'format' => 'h:i A',
                                'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                                'new_zone' => $event_external_participant['timezone'],
                                '_this' => $_this

                            )), $user_message);
                        }

                        if (isset($difference_array['old_event_end_time'])) {
                            $user_message = str_replace('{{EVENT_END_TIME_OLD}}', reset_datetime(array(
                                'datetime' => $difference_array['old_event_end_time'],
                                'from_format' => 'h:iA',
                                'format' => 'h:i A',
                                'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                                'new_zone' => $event_external_participant['timezone'],
                                '_this' => $_this

                            )), $user_message);
                        }
                    }

                    $user_message = str_replace('{{EVENT_DATE}}', reset_datetime(array(
                        'datetime' => $event_details['date'] . $event_details['event_start_time'],
                        'from_format' => 'Y-m-dh:iA',
                        'format' => 'M d Y, D',
                        'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                        'new_zone' => $event_external_participant['timezone'],
                        '_this' => $_this

                    )), $user_message);
                    $user_message = str_replace('{{EVENT_START_TIME}}', reset_datetime(array(
                        'datetime' => $event_details['date'] . $event_details['event_start_time'],
                        'from_format' => 'Y-m-dh:iA',
                        'format' => 'h:i A',
                        'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                        'new_zone' => $event_external_participant['timezone'],
                        '_this' => $_this

                    )), $user_message);
                    $user_message = str_replace('{{EVENT_END_TIME}}', reset_datetime(array(
                        'datetime' => $event_details['date'] . $event_details['event_end_time'],
                        'from_format' => 'Y-m-dh:iA',
                        'format' => 'h:i A',
                        'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                        'new_zone' => $event_external_participant['timezone'],
                        '_this' => $_this

                    )), $user_message);

                    // Add event status buttons
                    $extrainterviewer_email_status_button_rows =
                        generate_event_status_rows(
                            $event_details['sid'],
                            0,
                            'extrainterviewer',
                            $employer_name,
                            $event_external_participant['email'],
                            $event_details['category'],
                            $event_details['learning_center_training_sessions'],
                            $_this
                        );
                    $user_message = str_replace('{{EMAIL_STATUS_BUTTONS}}', $extrainterviewer_email_status_button_rows, $user_message);

                    // Set calendar
                    $download_url_vcs = base_url('download-event') . '/' . (str_replace('/', '$eb$eb$1', $_this->encrypt->encode('type=vcs&utype=extrainterviewer&uname=' . ($event_external_participant['name']) . '&uemail=' . ($event_external_participant['email']) . '&uid=0&eid=' . $event_details['sid'])));
                    $download_url_ics = base_url('download-event') . '/' . (str_replace('/', '$eb$eb$1', $_this->encrypt->encode('type=ics&utype=extrainterviewer&uname=' . ($event_external_participant['name']) . '&uemail=' . ($event_external_participant['email']) . '&uid=0&eid=' . $event_details['sid'])));
                    $download_url_gc = base_url('download-event') . '/' . (str_replace('/', '$eb$eb$1', $_this->encrypt->encode('type=gc&utype=extrainterviewer&uname=' . ($event_external_participant['name']) . '&uemail=' . ($event_external_participant['email']) . '&uid=0&eid=' . $event_details['sid'])));
                    $calendar_rows = '<tr>';
                    $calendar_rows .= '     <td style="font-size: 20px;"><br /><strong>Calendar event</strong><br /><br /></td>';
                    $calendar_rows .= '</tr>';
                    $calendar_rows .= '<tr>';
                    $calendar_rows .= '     <td><img src="' . (base_url('assets/calendar_icons/outlook.png')) . '" width="20"/>&nbsp;&nbsp;<a href="' . ($download_url_vcs) . '">Add to Outlook Calendar</a></td>';
                    $calendar_rows .= '</tr>';
                    $calendar_rows .= '<tr>';
                    $calendar_rows .= '     <td><img src="' . (base_url('assets/calendar_icons/google.png')) . '" width="20"/>&nbsp;&nbsp;<a href="' . ($download_url_gc) . '">Add to Google Calendar</a></td>';
                    $calendar_rows .= '</tr>';
                    $calendar_rows .= '<tr>';
                    $calendar_rows .= '     <td><img src="' . (base_url('assets/calendar_icons/apple.png')) . '" width="20"/>&nbsp;&nbsp;<a href="' . ($download_url_ics) . '">Add to Apple Calendar</a></td>';
                    $calendar_rows .= '</tr>';
                    $calendar_rows .= '<tr>';
                    $calendar_rows .= '     <td><img src="' . (base_url('assets/calendar_icons/calendar.png')) . '" width="20"/>&nbsp;&nbsp;<a href="' . ($download_url_ics) . '">Mobile & Other Calendar</a></td>';
                    $calendar_rows .= '</tr>';
                    $calendar_rows .= '<style>.dash-inner-block td, .dash-inner-block th{ border-bottom: none;}</style>';
                    // Replace calendar rows
                    $user_message = str_replace('{{CALENDAR_ROWS}}', $calendar_rows, $user_message);
                    // $_this->_e($user_message, true);
                    log_and_send_email_with_attachment(FROM_EMAIL_NOTIFICATIONS, $event_external_participant['email'], $email_subject, $user_message, $from_name, $ics_file);
                }
            }
            // $_this->_e('end', true, true);
        }
    }
}


/**
 * Pluralize the string
 * Created on: 24-05-2019
 *
 * @param $count Integer
 * @param $text String
 *
 * @return String
 */
if (!function_exists('pluralize')) {
    function pluralize($count, $text)
    {
        return $count . (($count == 1) ? (" $text") : (" ${text}s"));
    }
}

/**
 * Replace sms keywords and send sms
 * Created on: 11-13-2019
 *
 * @param $body
 *
 * @return String
 */
if (!function_exists('replace_sms_send_email')) {
    function replace_sms_send_email($body_array, $receiverPhoneNumber, $userName, $userEmailAddress, $_this, $company_sid, $employee_sid)
    {
        $employee_sms_status = 0;
        $_this->load->library('Twilioapp');
        if (get_company_sms_status($_this, $company_sid)) {
            if ($body_array['action'] == 'update_event') {
                $code = 'update_calendar_event_template';
                if ($employee_sid > 0) {
                    $notify_by = get_employee_sms_status($_this, $employee_sid);
                    if (strpos($notify_by['notified_by'], 'sms') !== false) {
                        $receiverPhoneNumber = $notify_by['PhoneNumber'];
                        $employee_sms_status = 1;
                    }
                }
                if ($employee_sms_status == 1) {
                    $template = get_company_sms_template($_this, $company_sid, $code);
                    $message = replace_sms_body($template['sms_body'], $body_array);
                    sendSMS($receiverPhoneNumber, $message, $userName, $userEmailAddress, $_this);
                }
            } else if ($body_array['action'] == 'save_event') {
                $code = 'new_calendar_event_template';
                if ($employee_sid > 0) {
                    $notify_by = get_employee_sms_status($_this, $employee_sid);
                    if (strpos($notify_by['notified_by'], 'sms') !== false) {
                        $receiverPhoneNumber = $notify_by['PhoneNumber'];
                        $employee_sms_status = 1;
                    }
                }
                if ($employee_sms_status == 1) {
                    $template = get_company_sms_template($_this, $company_sid, $code);
                    $message = replace_sms_body($template['sms_body'], $body_array);
                    sendSMS($receiverPhoneNumber, $message, $userName, $userEmailAddress, $_this);
                }
            }
        }
    }
}


/**
 * Pluralize the string
 * Created on: 24-05-2019
 *
 * @param $datetime Resource
 * @param $datetime2 Resource Optional
 *
 * @return String
 */
if (!function_exists('get_date_difference_duration')) {
    function get_date_difference_duration($datetime, $datetime2 = FALSE)
    {
        if (!$datetime2)
            $datetime2 = new DateTime('now');
        $interval = $datetime2->diff($datetime);
        if ($interval->invert == 1)
            return -1;
        if ($v = $interval->y >= 1)
            return pluralize($interval->y, 'year');
        if ($v = $interval->m >= 1)
            return pluralize($interval->m, 'month');
        if ($v = $interval->d >= 1)
            return pluralize($interval->d, 'day');
        if ($v = $interval->h >= 1)
            return pluralize($interval->h, 'hour');
        if ($v = $interval->i >= 1)
            return pluralize($interval->i, 'minute');
        if ($v = $interval->s >= 1)
            return pluralize($interval->s, 'seconds');
        return -1;
    }
}

/**
 * Generate VSF file for outlook
 * Created on: 27-05-2019
 *
 * @param $destination String
 * @param $event_sid Integer
 *
 * @return String
 */
if (!function_exists('generate_vcs_file_for_event')) {
    function generate_vcs_file_for_event($destination, $event_sid, $user_id, $user_type, $user_name, $user_email)
    {
        $_this = get_instance();
        // Load encryption library
        $_this->load->library('encrypt');
        // Loads calendar modal
        $_this->load->model('calendar_model', 'cm');
        $event_details = $_this->cm->get_event_detail_for_frontend($event_sid);

        // Get user timezone
        $user_details = array();
        if ($user_type == 'applicant') {
            $user_details = $_this->cm->get_applicant_detail($user_id);
            if (!empty($event_details['event_timezone'])) {
                $user_details['timezone'] = $event_details['event_timezone'];
            }
        } else if ($user_type == 'employee' || $user_type == 'interviewer' || $user_type == 'personal') {
            $user_details = $_this->cm->get_employee_detail($user_id);
        } else {
            $user_details = $_this->cm->get_employee_detail($event_details['companys_sid']);
            if (!empty($event_details['event_timezone'])) {
                $user_details['timezone'] = $event_details['event_timezone'];
            }
        }

        if ($user_details['timezone'] == '') {
            $user_details['timezone'] = $_this->cm->get_employee_detail($event_details['companys_sid'])['timezone'];
            if ($user_details['timezone'] == '')
                $user_details['timezone'] = STORE_DEFAULT_TIMEZONE_ABBR;
        }

        // if ($event_details['users_type'] != 'personal'){
        //     $detail_type = $event_details['users_type'] == 'applicant' ? 'get_applicant_detail' : 'get_employee_detail';
        //     $user_info = $_this->cm->$detail_type($event_details['applicant_job_sid']);
        // }

        $event_category = reset_category(strtolower($event_details['category_uc']));

        $event_title = "{$event_category} : " . $event_details['title'];

        // $new_start_time = DateTime::createFromFormat(
        //     'h:iA',
        //     $event_details['event_start_time']
        // )->format('His');

        // $new_end_time = DateTime::createFromFormat(
        //     'h:iA',
        //     $event_details['event_end_time']
        // )->format('His');


        $new_date = reset_datetime(array(
            'datetime' => $event_details['event_date_ac'] . $event_details['event_start_time'],
            'from_format' => 'Y-m-dh:iA',
            'format' => 'Y-m-d',
            'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
            'new_zone' => $user_details['timezone'],
            '_this' => $_this
        ));

        $new_start_time = reset_datetime(array(
            'datetime' => $event_details['event_date_ac'] . $event_details['event_start_time'],
            'from_format' => 'Y-m-dh:iA',
            'format' => 'His',
            'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
            'new_zone' => $user_details['timezone'],
            '_this' => $_this
        ));


        $new_end_time = reset_datetime(array(
            'datetime' => $event_details['event_date_ac'] . $event_details['event_end_time'],
            'from_format' => 'Y-m-dh:iA',
            'format' => 'His',
            'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
            'new_zone' => $user_details['timezone'],
            '_this' => $_this
        ));

        $ss = "\n";
        $ds = "\n\n";
        // Set details
        $details = '';
        $details .= "Event Type:{$ss}";
        $details .= "{$event_category}{$ss}";
        $details .= $ds;

        $user_sid = $event_details['applicant_job_sid'];
        $event_type = $event_details['users_type'];
        $event_sid = $event_sid;
        $user_name = ucwords($user_name);
        // $user_name  = $user_info['first_name']." ".$user_info['last_name'];
        // $user_email = $user_info['email'];
        //
        $base_url = base_url() . 'event/';
        // Set event code string
        $string_conf = 'id=' . $user_id . ':eid=' . $event_sid . ':etype=' . $event_type . ':type=confirmed:name=' . $user_name . ':email=' . $user_email;
        $string_notconf = 'id=' . $user_id . ':eid=' . $event_sid . ':etype=' . $event_type . ':type=notconfirmed:name=' . $user_name . ':email=' . $user_email;
        $string_reschedule = 'id=' . $user_id . ':eid=' . $event_sid . ':etype=' . $event_type . ':type=reschedule:name=' . $user_name . ':email=' . $user_email;
        //
        if (strtolower($event_details['category_uc']) == 'training-session')
            $string_attended = 'id=' . $user_id . ':eid=' . $event_sid . ':etype=' . $event_type . ':type=attended:name=' . $user_name . ':email=' . $user_email;
        // Set encoded string
        $enc_string_conf = $base_url . str_replace('/', '$eb$eb$1', $_this->encrypt->encode($string_conf));
        //
        if (strtolower($event_details['category_uc']) == 'training-session')
            $enc_string_attended = $base_url . str_replace('/', '$eb$eb$1', $_this->encrypt->encode($string_attended));
        //
        $enc_string_notconf = $base_url . str_replace('/', '$eb$eb$1', $_this->encrypt->encode($string_notconf));
        $enc_string_reschedule = $base_url . str_replace('/', '$eb$eb$1', $_this->encrypt->encode($string_reschedule));

        $details .= "Event Links:{$ss}";

        if (strtolower($event_details['category_uc']) == 'training-session') {
            $details .= "Attended: {$enc_string_attended}{$ss}";
        }

        $details .= "Confirm: {$enc_string_conf}{$ss}";
        $details .= "Reschedule: {$enc_string_reschedule}{$ss}";
        $details .= "Cannot Attend: {$enc_string_notconf}{$ss}";

        if (sizeof($event_details['interviewers'])) {
            $details .= $ds;
            $details .= ($event_details['users_type'] == 'applicant' ? 'Interviewer(s)' : 'Participant(s)') . ":{$ss}";
            foreach ($event_details['interviewers'] as $k0 => $v0) {
                $details .= "&#8277;&nbsp;" . $v0['value'] . "" . ($v0['show_email'] == 1 ? '(' . ($v0['email_address']) . ')' : '') . "{$ss}";
            }
        }

        $details .= $ds;
        $details .= "Company:{$ss}";
        $details .= "{$event_details['company_name']}{$ss}";

        $start_date_time = $new_date . "T{$new_start_time}";
        $eStartDateTimeFull = date('Y-m-d H:i:s', strtotime($new_date . " " . $new_start_time));
        $eEndDateTimeFull = date('Y-m-d H:i:s', strtotime($new_date . " " . $new_end_time));
        if ($eStartDateTimeFull > $eEndDateTimeFull)
            $new_date = date('Y-m-d', strtotime($new_date . ' +1 day'));
        $end_date_time = $new_date . "T{$new_end_time}";

        $address = $event_details['address'];
        $details = str_replace('&nbsp;', ' ', nl2br($details));
        $details = str_replace('&#8277;', '-', $details);
        $details = preg_replace('/\s+/', "", $details);
        $details = str_replace('<br/>', "=0D=0A", $details);

        $content = <<<EOD
BEGIN:VCALENDAR
BEGIN:VEVENT
DTSTART:$start_date_time
DTEND:$end_date_time
LOCATION;ENCODING=QUOTED-PRINTABLE:$address
DESCRIPTION;ENCODING=QUOTED-PRINTABLE:$details
SUMMARY;ENCODING=QUOTED-PRINTABLE:$event_title
PRIORITY:1
END:VEVENT
END:VCALENDAR
EOD;
        //
        $destination = APPPATH . '../assets/vcs_files/' . $event_details['companys_sid'] . '-' . $event_details['company_name'] . '/';

        if (!is_dir($destination))
            mkdir($destination, 0777, true);
        $uniqueName = STORE_NAME . '-event-' . $event_sid;
        $targetFileName = $uniqueName . '.vcs';

        $file_name = $destination . $targetFileName;

        $file = fopen($file_name, 'w');
        fwrite($file, $content);
        fclose($file);

        return $file_name;
    }
}

/**
 * Generate CSV
 * Created On: 30-05-2019
 *
 * @param $data Array
 * @param $file_name String
 *
 * @return String|Bool
 */
if (!function_exists('generate_csv')) {
    function generate_csv(
        $data,
        $file_name = false,
        $headers = array(),
        $type = 'accurate_background'
    ) {
        if (!sizeof($headers))
            $headers = array('Date', 'Ordered By', 'Candidate', 'Type', 'Product Name', 'Company Name', 'Status');
        $file_path = APPPATH . '../assets/csv/';
        if (!file_exists($file_path))
            mkdir($file_path, 0777, true);

        $file_name = ($file_name ? $file_name : '') . generateRandomString(4) . '.csv';

        $file_path .= $file_name;
        $file = fopen($file_path, 'w');

        if ($type == "invoice_orders") {
            $ci = &get_instance();
            $companyName = $ci->session->userdata('logged_in')['company_detail']['CompanyName'];
            fputcsv($file, array("Company Name: ", $companyName));
        }

        fputcsv($file, $headers);

        foreach ($data as $k0 => $v0) {
            $row = array();
            switch ($type) {
                case 'accurate_background':
                    $row[] = convert_date_to_frontend_format($v0['date_applied']);
                    $row[] = $v0['first_name'] . ' ' . $v0['last_name'];
                    $row[] = $v0['user_first_name'];
                    $row[] = ucfirst($v0['users_type']);
                    $row[] = $v0['product_name'];
                    $row[] = ucwords($v0['cname']);
                    $row[] = $v0['status'] == 'DRAFT' ? 'AWAITING CANDIDATE INPUT' : ucwords($v0['status']);
                    break;

                case 'invoice_orders':
                    $row[] = $v0['invoice_number'];
                    $row[] = $v0['fullname'];
                    $row[] = $v0['items'];
                    $row[] = $v0['date'];
                    $row[] = $v0['payment_method'];
                    $row[] = '$' . $v0['total'];
                    $row[] = $v0['status'];
                    break;
            }
            fputcsv($file, $row);
        }
        fclose($file);

        return str_replace('.csv', '', $file_name);
    }
}

/**
 * Download File
 * Created On: 30-05-2019
 *
 * @param $file_name String
 *
 * @return VOID
 */
if (!function_exists('download_file')) {
    function download_file($download_file)
    {
        if (file_exists($download_file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($download_file) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($download_file));
            readfile($download_file);

            unlink($download_file);
        }
    }
}

/**
 * Remove Special Entities
 * Created on: 03-06-2019
 *
 * @param $input String|Array
 *
 * @return String|Array
 */
if (!function_exists('sc_remove')) {
    function sc_remove($input)
    {
        if (is_array($input)) {
            foreach ($input as $k0 => $v0) {
                $input[$k0] = sc_remove($v0);
            }
            return $input;
        }
        return preg_replace(SC_REGEX, '', utf8_decode($input));
    }
}

/**
 * Generate ICS file
 * Created on: 12-06-2019
 *
 * @param $event Integer
 * @param $is_update Bool Optional
 *
 * @return String|Bool
 */
if (!function_exists('generate_admin_ics_file')) {
    function generate_admin_ics_file($event, $is_update = false)
    {
        // Return false when data array is empty
        if (!sizeof($event))
            return false;
        // Set path
        $file_path = APPPATH . '../assets/admin/ics/';
        // Create path if not exists
        if (!is_dir($file_path))
            mkdir($file_path, 0777, true);
        // Set UID
        $uid = STORE_NAME . '-event-' . clean($event['event_sid']);
        // Set file name
        $file_name = strtolower($uid) . '.ics';
        // Set full file name
        $file = $file_path . $file_name . '.ics';
        // Set date format
        $date_format = 'Ymd\THis';
        // Set seperator
        $sep = "\n";
        // Set description
        $description = '';

        // VCard
        $ics_body = '';
        $ics_body .= 'BEGIN:VCALENDAR' . PHP_EOL;
        $ics_body .= 'METHOD:' . ($is_update ? 'REQUEST' : 'PUBLISH') . '' . PHP_EOL;
        $ics_body .= 'VERSION:2.0' . PHP_EOL;
        $ics_body .= 'PRODID:-//Automoto HR//Automoto HR//EN' . PHP_EOL;
        $ics_body .= 'BEGIN:VEVENT' . PHP_EOL;
        $ics_body .= 'ORGANIZER;CN="' . ($event['creator_first_name'] . ' ' . $event['creator_last_name']) . '":mailto:' . $event['creator_email_address'] . PHP_EOL;
        $ics_body .= 'SUMMARY:' . reset_category($event['event_category']) . ': ' . $event['event_title'] . PHP_EOL;
        $ics_body .= 'DESCRIPTION:' . $description . ' ' . PHP_EOL;

        // Participants
        if (!isset($event['participants']) && sizeof($event['participants']) && isset($event['participants'][0]['first_name'])) {
            foreach ($event['participants'] as $participant) {
                $ics_body .= 'ATTENDEE;CN="' . ucwords($participant['first_name']) . ' ' . ucwords($participant['last_name']) . '";RSVP=TRUE:mailto:' . ($participant['show_email'] == 1 ? $participant['email'] : '') . PHP_EOL;
            }
        }

        // External Participants
        if (!isset($event['external_participants']) && sizeof($event['external_participants'])) {
            foreach ($event['external_participants'] as $participant) {
                $ics_body .= 'ATTENDEE;CN="' . ucwords($participant['external_participant_name']) . '";RSVP=TRUE:mailto:' . ($participant['show_email'] == 1 ? $participant['external_participant_email'] : '') . PHP_EOL;
            }
        }

        // External Users
        if (!isset($event['external_users']) && sizeof($event['external_users'])) {
            foreach ($event['external_users'] as $participant) {
                $ics_body .= 'ATTENDEE;CN="' . ucwords($participant['name']) . '";RSVP=TRUE:mailto:' . ($participant['email_adress']) . PHP_EOL;
            }
        }

        // System Users
        if (!isset($event['users_array']) && sizeof($event['users_array'])) {
            foreach ($event['users_array'] as $participant) {
                $ics_body .= 'ATTENDEE;CN="' . ucwords($participant['first_name']) . ' ' . ucwords($participant['last_name']) . '";RSVP=TRUE:mailto:' . ($participant['email_adress']) . PHP_EOL;
            }
        }

        if ($event['meeting_url'] != '')
            $ics_body .= 'URL:' . $event['meeting_url'] . PHP_EOL;

        $ics_body .= 'UID:' . $uid . PHP_EOL;
        $ics_body .= 'STATUS:' . strtoupper($event['event_status']) . PHP_EOL; // Can be CONFIRMED CANCELED TENTATIVE
        $ics_body .= 'DTSTAMP:' . date($date_format) . PHP_EOL;
        $ics_body .= 'DTSTART:' . date($date_format, strtotime($event['event_date'] . ' ' . $event['event_start_time'])) . PHP_EOL;
        $ics_body .= 'DTEND:' . date($date_format, strtotime($event['event_date'] . ' ' . $event['event_end_time'])) . PHP_EOL;
        $ics_body .= 'LAST-MODIFIED:' . date($date_format, strtotime($event['last_modified_at'])) . PHP_EOL;
        $ics_body .= 'CREATED:' . date($date_format, strtotime($event['event_date'])) . PHP_EOL;
        if ($event['event_address'] != '' || $event['event_address'] != null)
            $ics_body .= 'LOCATION:' . $event['event_address'] . PHP_EOL;
        $ics_body .= 'END:VEVENT' . PHP_EOL;
        $ics_body .= 'END:VCALENDAR' . PHP_EOL;

        // Open file
        $handler = fopen($file, 'w');
        fwrite($handler, $ics_body);
        fclose($handler);

        return $file;
    }
}

/**
 * Reset the event category - meeting Meeting
 * Created on: 12-06-2019
 *
 * @param $category String
 *
 * @return String
 */
if (!function_exists('reset_category')) {
    function reset_category($category)
    {
        // Reset categories
        switch ($category) {
            case 'interview':
                $category = 'In-Person Interview';
                break;
            case 'interview-phone':
                $category = 'Phone Interview';
                break;
            case 'interview-voip':
                $category = 'Voip Interview';
                break;
            case 'training-session':
                $category = 'Training Session';
                break;
            case 'gotomeeting':
                $category = 'GoToMeeting';
                break;
            case 'other':
                $category = 'Other Appointment';
                break;
            default:
                $category = ucwords($category);
                break;
        }

        return $category;
    }
}

/**
 * Generate Email Template For Calender
 * Craeted on: 21-06-2019
 *
 * @param $event Array
 * @param $type String Optional ('save', 'update', 'reminder')
 * @param $reminder_email_list Array Optional
 *
 * @return String
 */
if (!function_exists('send_admin_calendar_email_template')) {
    function send_admin_calendar_email_template($event, $type = 'save', $reminder_email_list = array())
    {
        //
        $_this = &get_instance();
        $subject = '';
        $body = '';
        //
        extract($event);
        //
        $event_category_new = reset_category($event_category);
        // Set comparison rows
        $event_update_message_row = $event_update_category_row = $event_update_date_row
            = $event_update_start_time_row = $event_update_end_time_row = $event_update_comment_row
            = $event_update_address_row = $event_update_meeting_id_row = $event_update_meeting_url_row
            = $event_update_meeting_call_number_row
            = $event_update_meeting_row = $event_update_applicant_name_row = $event_update_user_phone_row
            = $event_update_comment_change_row = $event_update_employee_name_row = $event_subject_row = '';

        // Number of blocks that are changed
        $event_block_changed = 0;

        // Check and set change
        if (($type == 'update' || $type == "drag_update") && sizeof($diff_array)) {

            // Check category change
            if (isset($diff_array['old_category'], $diff_array['new_category']) && $diff_array['old_category'] != '' && $diff_array['new_category'] != '') {
                $event_update_category_row = 'Event type changed from "<b style="color: #d9534f; font-weight: bold; font-size: 15px;">' . (reset_category($diff_array['old_category'])) . '</b>" to "<b style="color: #81b431; font-weight: bold; font-size: 15px;">' . (reset_category($diff_array['new_category'])) . '</b>"';
                $event_subject_row = " - Event type has been changed.";
                $event_block_changed++;
            }

            // Check date change
            if (isset($diff_array['old_date'], $diff_array['new_date']) && $diff_array['old_date'] != '' && $diff_array['new_date'] != '') {
                $new_from_syntax = $old_from_syntax = 'm-d-Y';
                // Reset dates
                if (!preg_match('/[0-9]{2}-[0-9]{2}-[0-9]{4}/', $diff_array['old_date']))
                    $old_from_syntax = 'Y-m-d';
                if (!preg_match('/[0-9]{2}-[0-9]{2}-[0-9]{4}/', $diff_array['new_date']))
                    $new_from_syntax = 'Y-m-d';
                // _e($diff_array, true);
                $event_update_date_row = 'Event date changed from "<b style="color: #d9534f; font-weight: bold; font-size: 15px;">' . (date_with_time(DateTime::createFromFormat($old_from_syntax, $diff_array['old_date'])->format('Y-m-d'))) . '</b>" to "<b style="color: #81b431; font-weight: bold; font-size: 15px;">' . (date_with_time(DateTime::createFromFormat($new_from_syntax, $diff_array['new_date'])->format('Y-m-d'))) . '</b>"';
                $event_subject_row = " - Event date has been changed.";
                $event_block_changed++;
            }

            // Check start time change
            if (isset($diff_array['old_event_start_time'], $diff_array['new_event_start_time']) && $diff_array['old_event_start_time'] != '' && $diff_array['new_event_start_time'] != '') {
                $event_update_start_time_row = 'Event start time changed from "<b style="color: #d9534f; font-weight: bold; font-size: 15px;">' . (($diff_array['old_event_start_time'])) . '</b>" to "<b style="color: #81b431; font-weight: bold; font-size: 15px;">' . (($diff_array['new_event_start_time'])) . '</b>"';
                $event_subject_row = " - Event start time has been changed.";
                $event_block_changed++;
            }

            // Check end time change
            if (isset($diff_array['old_event_end_time'], $diff_array['new_event_end_time']) && $diff_array['old_event_end_time'] != '' && $diff_array['new_event_end_time'] != '') {
                $event_update_end_time_row = 'Event end time changed from "<b style="color: #d9534f; font-weight: bold; font-size: 15px;">' . (($diff_array['old_event_end_time'])) . '</b>" to "<b style="color: #81b431; font-weight: bold; font-size: 15px;">' . (($diff_array['new_event_end_time'])) . '</b>"';
                $event_subject_row = " - Event end time has been changed.";
                $event_block_changed++;
            }

            // Set new event comment info
            if (
                (isset($diff_array['new_comment']) && $diff_array['old_comment'] != '') ||
                (isset($diff_array['new_comment'], $diff_array['old_comment']) && $diff_array['old_comment'] != $diff_array['new_comment'])
            ) {
                if ($diff_array['old_comment'] != '') {
                    $event_update_comment_row = 'Comment changed.';
                    $event_update_comment_change_row = sft('<strong>Previous</strong><p style="color: #d9534f;">' . ($diff_array['old_comment']) . '</p>', '<strong>New</strong><p style="color: #81b431;">' . ($diff_array['new_comment']) . '</p><br />');
                }
                $event_subject_row = " - Comment has been changed.";
                $event_block_changed++;
            }

            // Set new event address info
            if (isset($diff_array['new_address'], $diff_array['old_address']) && $diff_array['old_address'] != '' && $diff_array['new_address']) {
                $event_update_address_row = 'Address changed from "<b style="color: #d9534f; font-weight: 800; font-size: 15px;">' . ($diff_array['old_address']) . '</b>" to "<b style="color: #81b431; font-weight: 800; font-size: 15px;">' . ($diff_array['new_address']) . '</b>"';
                $event_subject_row = " - Event address has been changed.";
                $event_block_changed++;
            }


            // Set new event meeting info
            if (
                (isset($diff_array['new_meeting_check']) && $diff_array['old_meeting_check'] != '') ||
                (isset($diff_array['old_meeting_id']) && $diff_array['old_meeting_id'] != $diff_array['new_meeting_id']) ||
                (isset($diff_array['old_meeting_url']) && $diff_array['old_meeting_url'] != $diff_array['new_meeting_url']) ||
                (isset($diff_array['old_meeting_call_number']) && $diff_array['old_meeting_call_number'] != $diff_array['new_meeting_call_number'])
            ) {
                $event_update_meeting_row = 'Meeting details have changed.';
                $event_subject_row = " - Event meeting details have changed.";
                $event_block_changed++;
            }

            // Set meeting difference
            // Meeting ID
            if (isset($diff_array['old_meeting_id'], $diff_array['new_meeting_id']) && $diff_array['old_meeting_id'] != $diff_array['new_meeting_id'] && $diff_array['old_meeting_id'] != '') {
                $event_update_meeting_id_row = '<td><strong>Previous</strong><p style="color: #d9534f;">' . ($diff_array['old_meeting_id']) . '</p></td><td><strong>New</strong><p style="color: #81b431;">' . ($diff_array['new_meeting_id']) . '</p><br /></td>';
            }
            // Meeting URL
            if (isset($diff_array['old_meeting_url'], $diff_array['new_meeting_url']) && $diff_array['old_meeting_url'] != $diff_array['new_meeting_url'] && $diff_array['old_meeting_url'] != '') {
                $event_update_meeting_url_row = '<td><strong>Previous</strong><p style="color: #d9534f;">' . ($diff_array['old_meeting_url']) . '</p></td><td><strong>New</strong><p style="color: #81b431;">' . ($diff_array['new_meeting_url']) . '</p><br /></td>';
            }
            // Meeting Phone
            if (isset($diff_array['old_meeting_call_number'], $diff_array['new_meeting_call_number']) && $diff_array['old_meeting_call_number'] != $diff_array['new_meeting_call_number'] && $diff_array['old_meeting_call_number'] != '') {
                $event_update_meeting_call_number_row = '<td><strong>Previous</strong><p style="color: #d9534f;">' . ($diff_array['old_meeting_call_number']) . '</p></td><td><strong>New</strong><p style="color: #81b431;">' . ($diff_array['new_meeting_call_number']) . '</p><br /></td>';
            }

            // Set Interviwers change
            $removed_interviewers = $added_interviewers = 0;
            $itype = 'Participant(s)';

            if ($event_type == 'employee')
                $itype = 'Participant(s)';
            if ($event_category == 'training-session')
                $itype = 'Attendee(s)';

            if (isset($diff_array['added_interviewers']) && sizeof($diff_array['added_interviewers']))
                $added_interviewers += count($diff_array['added_interviewers']);
            if (isset($diff_array['added_external_interviewers']) && sizeof($diff_array['added_external_interviewers']))
                $added_interviewers += count($diff_array['added_external_interviewers']);
            if (isset($diff_array['removed_interviewers']) && sizeof($diff_array['removed_interviewers']))
                $removed_interviewers += count($diff_array['removed_interviewers']);
            if (isset($diff_array['removed_external_interviewers']) && sizeof($diff_array['removed_external_interviewers']))
                $removed_interviewers += count($diff_array['removed_external_interviewers']);
            if ($removed_interviewers != 0 || $added_interviewers != 0)
                $event_block_changed++;
            if ($removed_interviewers != 0 && $added_interviewers != 0)
                $event_subject_row = " - ($added_interviewers) $itype added and ($removed_interviewers) removed.";
            else if ($added_interviewers != 0)
                $event_subject_row = " - ($added_interviewers) $itype added.";
            else if ($removed_interviewers != 0)
                $event_subject_row = " - ($removed_interviewers) $itype removed.";

            // Set Interviwers change
            $removed_user_ids = $added_user_ids = 0;

            // Difference of system/external users
            if (isset($diff_array['added_user_ids']) && sizeof($diff_array['added_user_ids']))
                $added_user_ids += count($diff_array['added_user_ids']);
            if (isset($diff_array['added_external_users']) && sizeof($diff_array['added_external_users']))
                $added_user_ids += count($diff_array['added_external_users']);
            if (isset($diff_array['removed_user_ids']) && sizeof($diff_array['removed_user_ids']))
                $removed_user_ids += count($diff_array['removed_user_ids']);
            if (isset($diff_array['removed_external_users']) && sizeof($diff_array['removed_external_users']))
                $removed_user_ids += count($diff_array['removed_external_users']);
            if ($removed_user_ids != 0 || $added_user_ids != 0)
                $event_block_changed++;
            if ($removed_user_ids != 0 && $added_user_ids != 0)
                $event_subject_row = " - ($added_user_ids) Users added and ($removed_user_ids) removed.";
            else if ($added_user_ids != 0)
                $event_subject_row = " - ($added_user_ids) Users added.";
            else if ($removed_user_ids != 0)
                $event_subject_row = " - ($removed_user_ids) Users removed.";
        }

        //
        $users_row = $users_rows = '';
        $include_participants = true;
        //
        if (!in_array($event_category, array('training-session', 'meeting', 'other')) && $event_type == 'personal')
            $include_participants = false;
        //
        if ($include_participants) {
            if (sizeof($participants) || sizeof($external_participants))
                $users_rows .= sft('<strong> <p style="font-size: 20px;">' . ($event_category == 'training-session' ? 'Assigned Attendee(s)' : 'Participant(s)') . '</p></strong> <br />');
            // Users
            if (sizeof($participants)) {
                foreach ($participants as $k0 => $v0) {
                    $cls = (isset($diff_array['added_interviewers']) && in_array($v0['id'], $diff_array['added_interviewers'])) ? 'style="background-color:#81b431"' : '';
                    $users_rows .= '<tr ' . $cls . '><td style="font-size: 20px;"><p>&#9632; &nbsp;' . (ucwords($v0['first_name'] . ' ' . $v0['last_name'])) . ' ' . ($v0['show_email'] == 1 ? ' <a href="mailto:' . ($v0['email_address']) . '">(' . ($v0['email_address']) . ')</a>' : '') . '</p></td></tr>';
                }
                //
                if (isset($diff_array['removed_interviewers'])) {
                    foreach ($diff_array['removed_interviewers'] as $k0 => $v0) {
                        $users_rows .= '<tr style="background-color:#d9534f;"><td style="font-size: 20px;"><p>&#9632; &nbsp;' . (ucwords($v0['full_name'])) . ' ' . (isset($v0['show_email']) && $v0['show_email'] == 1 ? ' <a href="mailto:' . ($v0['email_address']) . '">(' . ($v0['email_address']) . ')</a>' : '') . '</p></td></tr>';
                    }
                }
            }
            // External users
            if (sizeof($external_participants)) {
                foreach ($external_participants as $k0 => $v0) {
                    // $cls = (isset($diff_array['added_external_interviewers']) && in_array($v0['id'], $diff_array['added_external_interviewers'])) ? 'style="background-color:#81b431"' : '';
                    $users_rows .= sft('<p style="font-size: 20px;">&#9632; &nbsp;' . (ucwords($v0['external_participant_name'])) . ' ' . ($v0['show_email'] == 1 ? ' <a href="mailto:' . ($v0['external_participant_email']) . '">(' . ($v0['external_participant_email']) . ')</a>' : '') . '</p>');
                }

                //
                if (isset($diff_array['removed_external_interviewers'])) {
                    foreach ($diff_array['removed_external_interviewers'] as $k0 => $v0) {
                        $users_rows .= '<tr style="background-color:#d9534f;"><td style="font-size: 20px;"><p>&#9632; &nbsp;' . (ucwords($v0['name'])) . ' ' . ($v0['show_email'] == 1 ? ' <a href="mailto:' . ($v0['email']) . '">(' . ($v0['email']) . ')</a>' : '') . '</p></td></tr>';
                    }
                }
            }

            // For 'demo' type
            if ($event_type == 'demo' || $event_type == 'super admin') {
                // For system demo and
                if (sizeof($users_array) || sizeof($external_users)) {
                    $users_rows .= sft('<hr />');
                    $users_rows .= sft('<strong>' . ($event_type == 'super admin' ? 'Admin(s)' : 'User(s)') . '</strong> <br />');
                }
                // System users
                if (sizeof($users_array)) {
                    foreach ($users_array as $k0 => $v0) {
                        $cls = isset($diff_array['added_user_ids']) && sizeof($diff_array['added_user_ids']) && in_array($v0['id'], $diff_array['added_user_ids']) ? 'style="background-color: #81b431;"' : '';
                        $users_rows .= '<tr ' . ($cls) . '><td style="font-size: 20px;"><p>&#9632; &nbsp;' . (ucwords($v0['first_name'] . ' ' . $v0['last_name'])) . '</p></td></tr>';
                    }
                }
                //
                if (isset($diff_array['removed_user_ids']) && sizeof($diff_array['removed_user_ids'])) {
                    foreach ($diff_array['removed_user_ids'] as $k0 => $v0) {
                        $users_rows .= '<tr style="background-color: #d9534f"><td style="font-size: 20px;"><p>&#9632; &nbsp;' . (ucwords($v0['name'])) . '</p></td></tr>';
                    }
                }
                // External users
                if (sizeof($external_users)) {
                    foreach ($external_users as $k0 => $v0) {
                        $cls = isset($diff_array['added_external_users']) && sizeof($diff_array['added_external_users']) && in_array($v0['email_address'], $diff_array['added_external_users']) ? 'style="background-color: #81b431"' : '';
                        $users_rows .= '<tr ' . ($cls) . '><td style="font-size: 20px;"><p>&#9632; &nbsp;' . (ucwords($v0['name'])) . ' ' . ($v0['show_email'] == 1 ? ' <a href="mailto:' . ($v0['email_address']) . '">(' . ($v0['email_address']) . ')</a>' : '') . '</p></td></tr>';
                    }
                }
                if (isset($diff_array['removed_external_users']) && sizeof($diff_array['removed_external_users'])) {
                    foreach ($diff_array['removed_external_users'] as $k0 => $v0) {
                        $users_rows .= '<tr style="background-color: #d9534f"><td style="font-size: 20px;"><p>&#9632; &nbsp;' . (ucwords($v0['name'])) . ' ' . ($v0['show_email'] == 1 ? ' <a href="mailto:' . ($v0['email']) . '">(' . ($v0['email']) . ')</a>' : '') . '</p></td></tr>';
                    }
                }
            }
        }

        // Set subject
        // For 'personal' type
        if ($event_type == 'personal' && !in_array($event_category, array('training-session', 'meeting', 'other')))
            $subject = $event_category_new . ' - Has been scheduled with ' . ucwords($user_name);
        else if ($event_type == 'personal' && in_array($event_category, array('training-session', 'meeting', 'other')))
            $subject = $event_category_new . ' - Has been scheduled';
        else // For 'super admin', 'demo'
            $subject = $event_category_new . ' - ' . (isset($requested_status) ? $requested_status : $event_status);

        // Update subject on change
        if ($event_subject_row != '')
            $subject .= $event_block_changed > 1 ? ' - Event details have been changed.' : $event_subject_row;

        // Set user info
        if ($type != 'reminder_email' || $type != 'send_cron_reminder_emails') {
            if ($event_type == 'personal')
                $to_user_name = '{{TO_USER_NAME}}';
            // if($event_type == 'personal') $to_user_name = ucwords($creator_first_name.' '.$creator_last_name);
            // else if($event_type == 'super admin' && in_array($event_category, array('call', 'email', 'gotomeeting'))) $to_user_name = ucwords($first_name.' '.$last_name);
            else if ($event_type == 'demo' && (int) $event['user_id'] !== 0)
                $to_user_name = ucwords($first_name . ' ' . $last_name);
            else
                $to_user_name = '{{TO_USER_NAME}}';
        } else
            $to_user_name = '{{TO_USER_NAME}}';

        $to_content = '';
        if ($type == 'update')
            $to_content .= '<p>Your event details have been Changed. Please update your calendar with the new information.</p>';
        else if ($type == 'reminder_email' || $type == 'send_cron_reminder_emails') {
            $to_content = 'This is a reminder email regarding an upcoming "<b>' . $event_category_new . '</b>" event scheduled with you. Please, find the details below.';
        } else if ($type == 'confirm') {
            $to_content .= '<tr><td><p>You are Receiving this email because <b>"{{REQUESTOR_NAME}}"</b> {{REQUESTOR_SEPARATOR}} "<b>{{REQUESTED_EVENT_STATUS}}</b>" this "<b>' . ($event_category_new) . '</b>".</p></td></tr>';
        } else {
            if ($event_status == "cancelled")
                $to_content .= '<p>The "<b>' . $event_category_new . '</b>" has been cancelled.</p>';
            else if ($event_type == 'personal' && $event_category == 'call')
                $to_content .= '<p>A "<b>' . $event_category_new . '</b>" has been scheduled with "<b>{{WITH_NAME}}</b>".</p>';
            // $to_content .= '<p>You have scheduled an event regarding making a "<b>' . $event_category_new . '</b>" to "<b>'.$user_name.'</b>".</p>';
            else if ($event_type == 'personal' && $event_category == 'email')
                $to_content .= '<p>An "<b>' . $event_category_new . '</b>" has been scheduled with "<b>{{WITH_NAME}}</b>".</p>';
            // $to_content .= '<p>You have scheduled an event regarding sending an "<b>' . $event_category_new . '</b>" to "<b>'.$user_name.'</b>".</p>';
            else if ($event_type == 'personal' && ($event_category == 'training-session' || $event_category == 'meeting')) {
                $to_content .= '<p>A "<b>' . $event_category_new . '</b>" has been scheduled. </p>';
                // $to_content .= '<p>You have scheduled a "<b>' . $event_category_new . '</b>"for </p>';
                // $to_content .= $interviewers_rows;
            } else if ($event_type == 'personal' && $event_category == 'other') {
                $to_content .= '<p>An Appointment is scheduled.</p>';
                // $to_content .= '<p>You have scheduled an Appointment for </p>';
                // $to_content .= $interviewers_rows;
            } else
                $to_content .= "<p>An \"<b>{$event_category_new}</b>\" has been scheduled for you with \"<b>{{target_user}}</b>\"  with a status of \"<b>" . ucwords($event_status) . "</b>\".</p>";
        }

        //
        $duration = explode(' ', get_date_difference_duration(DateTime::createFromFormat('Y-m-d h:i A', $event_date . ' ' . $event_start_time)));
        //
        $sep = sft('<hr />');

        // Set heading
        $top_heading_row = '';
        if ($event_status == "cancelled")
            $top_heading_row .= '<h3 style="text-align: center;">Your appointment has been cancelled </h3>';
        else
            $top_heading_row .= $duration[0] == -1 ? '' : '<h3 style="text-align: center;">Your appointment is starting in <b>' . ($duration[0]) . '</b> ' . ($duration[1]) . '</h3>';
        if ($type == 'confirm')
            $top_heading_row = '{{EVENT_HEADING}}';

        // Set greeting
        $greet_row = '';
        $greet_row .= 'Dear <b>' . ($to_user_name) . '</b>,';

        // Set event category
        $category_heading_row = '<strong>Event category:</strong>';
        $category_para_row = "<p>{$event_category_new}</p>";

        // Set event date
        $date_heading_row = '<strong>Event date:</strong>';
        $date_para_row = "<p>" . (date_with_time($event_date)) . "</p>";

        // Set event time
        $time_heading_row = '<strong>Event time:</strong>';
        $time_para_row = "<p>{$event_start_time} - {$event_end_time}</p>";

        // Set comment section
        $comment_heading_row = '<strong>' . ($event_type == 'personal' ? 'Personal comment' : 'Comment') . '</strong>';
        $comment_para_row = "<p>{$comment}</p>";

        // Set meeting id
        $meeting_id_heading_row = '<strong>Meeting ID</strong>';
        $meeting_id_para_row = "<p>{$meeting_id}</p>";

        // Set meeting url
        $meeting_url_heading_row = '<strong>Meeting URL</strong>';
        $meeting_url_para_row = "<p>{$meeting_url}</p>";

        // Set meeting phone
        $meeting_phone_heading_row = '<strong>Meeting Phone</strong>';
        $meeting_phone_para_row = "<p>{$meeting_phone}</p>";

        // Set address
        $address_heading_row = '<strong>Address</strong>';
        $address_para_row = "<p>{$event_address}</p>";
        $address_map_row = '<a href="https://maps.google.com/maps?z=12&t=m&q=' . (urlencode($event_address)) . '">';
        $address_map_row .= '    <img src="https://maps.googleapis.com/maps/api/staticmap?center=' . (urlencode($event_address)) . '&zoom=13&size=400x400&key=' . GOOGLE_MAP_API_KEY . '&markers=color:blue|label:|' . (urlencode($event_address)) . '" alt="No Map Found!" >';
        $address_map_row .= '</a>';

        // Generate calendar download links
        // Set calendar download links
        $_this->load->library('encrypt');
        // Set template
        $body = '';

        $body .= sft($top_heading_row);
        $body .= $sep;
        $body .= sft($greet_row);
        $body .= sft($to_content);
        $body .= $sep;
        //
        if ($type != 'confirm')
            $body .= sft('{{EVENT_STATUS_BUTTONS}}');

        $body .= sft("<strong>Event details</strong> <br /><br />");
        $body .= sft($category_heading_row);
        $body .= sft($category_para_row, $event_update_category_row);

        //
        if ($type == 'confirm') {
            $body .= sft($date_heading_row, '{{NEW_DATE_HEADING}}');
            $body .= sft($date_para_row, '{{REQUESTED_EVENT_DATE}}');
        } else {
            $body .= sft($date_heading_row);
            $body .= sft($event_update_date_row != '' ? $event_update_date_row : $date_para_row);
        }

        if ($type == 'confirm') {
            $body .= sft($time_heading_row, '{{NEW_TIME_HEADING}}');
            $body .= sft($time_para_row, '{{REQUESTED_EVENT_TIME}}');
        } else {
            $body .= sft($time_heading_row);
            $body .= sft($event_update_start_time_row != '' ? $event_update_start_time_row : $time_para_row);
        }
        //
        if ($event_update_end_time_row != '')
            $body .= sft($event_update_end_time_row != '' ? $event_update_end_time_row : $time_para_row);
        $body .= $sep;
        //
        if ($type == 'confirm') {
            $body .= '{{REQUEST_REASON}}';
            $body .= $sep;
        }

        // Comment check
        if ($comment != '' && $comment != null)
            $body .= '{{COMMENT_BOX}}';

        // GoToMeeting check
        if ($event_category == 'gotomeeting') {
            $body .= sft("<strong>GoToMeeting details</strong> <br /><br />");
            if ($event_update_meeting_row != '') {
                $body .= sft("<p>GoToMeeting details changed.</p>");
            }
            $body .= sft($meeting_id_heading_row);
            //
            if ($event_update_meeting_id_row != '')
                $body .= $event_update_meeting_id_row;
            else
                $body .= sft($meeting_id_para_row);

            $body .= sft($meeting_phone_heading_row);
            //
            if ($event_update_meeting_call_number_row != '')
                $body .= $event_update_meeting_call_number_row;
            else
                $body .= sft($meeting_phone_para_row);

            $body .= sft($meeting_url_heading_row);
            //
            if ($event_update_meeting_url_row != '')
                $body .= $event_update_meeting_url_row;
            else
                $body .= sft($meeting_url_para_row);

            $body .= $sep;
        }

        if ($users_rows != '') {
            $body .= $users_rows;
            $body .= $sep;
        }

        // Address check
        if ($event_address != '' && $event_address != null) {
            $body .= sft($address_heading_row);
            $body .= sft($event_update_address_row != '' ? $event_update_address_row : $address_para_row);
            $body .= sft($address_map_row);
        }
        if ($event_status != "cancelled")
            $body .= '{{CALENDAR_ROWS}}';

        $header = EMAIL_HEADER;
        $header = preg_replace('/<div class="body-content" (.*?)><\/div>/', '', $header);
        $footer = EMAIL_FOOTER;
        $body = $header . '</div><table border="0">' . ($body) . '</table><br />' . $footer;

        if ($type == 'confirm')
            return $body;

        $send_to_user = $send_to_users = $send_to_non_users = true;
        //
        $creator_name = ucwords($creator_first_name . ' ' . $creator_last_name);

        if (in_array($event_category, array('training-session', 'meeting', 'other')))
            $send_to_user = false;

        //
        // if($event_type == 'personal') $send_to_users = $send_to_non_users = false;
        if ($event_type == 'personal' && in_array($event_category, array('call', 'email', 'gotomeeting')))
            $send_to_users = $send_to_non_users = false;
        if ($event_type == 'personal' && $event_category == 'gotomeeting')
            $send_to_user = false;

        // Set only comment check
        if ($event_block_changed == 1 && isset($diff_array['old_comment']))
            $send_to_user = false;

        // _e($send_to_user, true, true);
        // _e($body, true, true);

        if (is_array($users_array) && ($event_type == 'demo' || $event_type == 'super admin'))
            $send_to_user = false;

        //
        if ($type == 'reminder_email' || $type == 'send_cron_reminder_emails') {
            if (!sizeof($reminder_email_list))
                return false;
            $current_datetime = date('Y-m-d H:i:s');
            foreach ($reminder_email_list as $k0 => $v0) {
                $comment_text = '';
                if ($comment != '' && ($v0['type'] == 'interviewer' || $v0['type'] == 'non-employee interviewer')) {
                    $comment_text = sft("<strong>Comment</strong> <br />");
                    if ($event_update_comment_change_row != '') {
                        $comment_text .= sft($event_update_comment_row);
                        $comment_text .= $event_update_comment_change_row;
                    } else
                        $comment_text .= sft($comment);
                }

                if ($comment_text != '')
                    $comment_text .= sft('<hr />');
                $user_message = $body;
                $user_message = str_replace('{{COMMENT_BOX}}', $comment_text, $user_message);
                $user_message = str_replace('{{COMPANY_NAME}}', $creator_name, $user_message);
                $user_message = str_replace('{{target_user}}', $creator_name, $user_message);

                //
                $user_message = str_replace('{{TO_USER_NAME}}', ucwords($v0['value']), $user_message);
                $e_type = $v0['type'] == 'admin' ? 'super admin' : ($v0['type'] == 'interviewer' ? 'participant' : ($v0['type'] == 'non-employee interviewer' ? 'external participant' : ($v0['type'] == 'person' ? 'person' : 'other')));
                //
                $uid = $v0['id'];
                $uemail = $v0['email_address'];
                $uname = $v0['value'];
                $utype = $e_type;
                // Create calendar links
                $download_url_vcs = base_url('download-event-file') . '/' . (str_replace('/', '$eb$eb$1', $_this->encrypt->encode('type=vcs&eid=' . $event_sid . '&uname=' . $uname . '&uemail' . $user_email . '=&utype=' . $utype . '&uid=' . $uid . '')));
                $download_url_ics = base_url('download-event-file') . '/' . (str_replace('/', '$eb$eb$1', $_this->encrypt->encode('type=ics&eid=' . $event_sid . '&uname=' . $uname . '&uemail' . $user_email . '=&utype=' . $utype . '&uid=' . $uid . '')));
                $download_url_gc = base_url('download-event-file') . '/' . (str_replace('/', '$eb$eb$1', $_this->encrypt->encode('type=gc&eid=' . $event_sid . '&uname=' . $uname . '&uemail' . $user_email . '=&utype=' . $utype . '&uid=' . $uid . '')));
                $calendar_rows = '<tr><td><br /><strong>Calendar event</strong><br /><br /></td></tr>';
                $calendar_rows .= '<tr><td><img src="' . (base_url('assets/calendar_icons/outlook.png')) . '" width="20"/>&nbsp;&nbsp;<a href="' . ($download_url_vcs) . '">Add to Outlook Calendar</a></td></tr>';
                $calendar_rows .= '<tr><td><img src="' . (base_url('assets/calendar_icons/google.png')) . '" width="20"/>&nbsp;&nbsp;<a href="' . ($download_url_gc) . '">Add to Google Calendar</a></td></tr>';
                $calendar_rows .= '<tr><td><img src="' . (base_url('assets/calendar_icons/apple.png')) . '" width="20"/>&nbsp;&nbsp;<a href="' . ($download_url_ics) . '">Add to Apple Calendar</a></td></tr>';
                $calendar_rows .= '<tr><td><img src="' . (base_url('assets/calendar_icons/calendar.png')) . '" width="20"/>&nbsp;&nbsp;<a href="' . ($download_url_ics) . '">Mobile & Other Calendar</a></td></tr>';
                $calendar_rows .= '<style>.dash-inner-block td, .dash-inner-block th{ border-bottom: none;}</style>';
                // Replace calendar
                $user_message = str_replace('{{CALENDAR_ROWS}}', $calendar_rows, $user_message);
                // Send email
                // Save data
                if ($type != 'send_cron_reminder_emails') {
                    // Set data array
                    $data_array = array();
                    $data_array['event_sid'] = $event_sid;
                    $data_array['user_id'] = $v0['id'];
                    $data_array['user_name'] = $v0['value'];
                    $data_array['email_address'] = $v0['email_address'];
                    $data_array['user_type'] = $e_type;
                    // Create record in db
                    $_this->dashboard_model->_q('admin_event_reminder_email_history', $data_array);
                }

                // Generate status link rows
                $status_rows = generate_admin_status_rows(
                    array(
                        'user_id' => $v0['id'],
                        'type' => $e_type,
                        'name' => strtolower($v0['value']),
                        'email_address' => $v0['email_address']
                    ),
                    $event,
                    $_this
                );

                if ($e_type == 'person')
                    $status_rows = '';
                // Generate button links
                $user_message = str_replace('{{EVENT_STATUS_BUTTONS}}', $status_rows, $user_message);

                // Send and log email
                log_and_send_email_with_attachment(
                    FROM_EMAIL_NOTIFICATIONS,
                    $v0['email_address'],
                    $subject,
                    $user_message,
                    $creator_name,
                    $ics_file
                );
            }
            return false;
        }

        // Send to user
        if ($send_to_user) {
            $user_message = $body;
            $user_message = str_replace('{{COMMENT_BOX}}', '', $user_message);
            $user_message = str_replace('{{COMPANY_NAME}}', $creator_name, $user_message);
            $user_message = str_replace('{{target_user}}', $creator_name, $user_message);

            $utype = 'super admin';
            $uid = $user_id;

            $status_rows = '';
            if (!in_array($type, array('cancel'))) {
                // if($event_type != 'personal' && !in_array($type, array('cancel'))){
                if (isset($first_name) && isset($last_name)) {
                    $cmpl = strtolower($first_name . ' ' . $last_name);
                    if ($first_name == '' && $last_name == '')
                        $cmpl = $email_address;
                    else if ($first_name != '' && $last_name == '')
                        $cmpl = strtolower($first_name);
                    else if ($first_name == '' && $last_name != '')
                        $cmpl = strtolower($last_name);
                } else {
                    // For personal 'call' & 'email'
                    $cmpl = strtolower($creator_first_name . ' ' . $creator_last_name);
                    $email_address = $creator_email_address;
                    $uid = $creator_sid;
                }
                // Generate status link rows
                $status_rows = generate_admin_status_rows(
                    array(
                        'user_id' => $uid,
                        'type' => 'super admin',
                        'name' => trim($cmpl),
                        'email_address' => $email_address
                    ),
                    $event,
                    $_this
                );
            }
            //
            if ($event_type == 'personal' && in_array($event_category, array('call', 'email', 'gotomeeting')))
                $status_rows = '';
            // Generate button links
            $user_message = str_replace('{{EVENT_STATUS_BUTTONS}}', $status_rows, $user_message);
            $user_message = str_replace('{{TO_USER_NAME}}', ucwords($cmpl), $user_message);
            $user_message = str_replace('{{WITH_NAME}}', ucwords($user_name), $user_message);
            // Create calendar links
            $download_url_vcs = base_url('download-event-file') . '/' . (str_replace('/', '$eb$eb$1', $_this->encrypt->encode('type=vcs&eid=' . $event_sid . '&uname=' . $cmpl . '&uemail' . $email_address . '=&utype=' . $utype . '&uid=' . $uid . '')));
            $download_url_ics = base_url('download-event-file') . '/' . (str_replace('/', '$eb$eb$1', $_this->encrypt->encode('type=ics&eid=' . $event_sid . '&uname=' . $cmpl . '&uemail' . $email_address . '=&utype=' . $utype . '&uid=' . $uid . '')));
            $download_url_gc = base_url('download-event-file') . '/' . (str_replace('/', '$eb$eb$1', $_this->encrypt->encode('type=gc&eid=' . $event_sid . '&uname=' . $cmpl . '&uemail' . $email_address . '=&utype=' . $utype . '&uid=' . $uid . '')));
            $calendar_rows = '<tr><td><br /><strong>Calendar event</strong><br /><br /></td></tr>';
            $calendar_rows .= '<tr><td><img src="' . (base_url('assets/calendar_icons/outlook.png')) . '" width="20"/>&nbsp;&nbsp;<a href="' . ($download_url_vcs) . '">Add to Outlook Calendar</a></td></tr>';
            $calendar_rows .= '<tr><td><img src="' . (base_url('assets/calendar_icons/google.png')) . '" width="20"/>&nbsp;&nbsp;<a href="' . ($download_url_gc) . '">Add to Google Calendar</a></td></tr>';
            $calendar_rows .= '<tr><td><img src="' . (base_url('assets/calendar_icons/apple.png')) . '" width="20"/>&nbsp;&nbsp;<a href="' . ($download_url_ics) . '">Add to Apple Calendar</a></td></tr>';
            $calendar_rows .= '<tr><td><img src="' . (base_url('assets/calendar_icons/calendar.png')) . '" width="20"/>&nbsp;&nbsp;<a href="' . ($download_url_ics) . '">Mobile & Other Calendar</a></td></tr>';
            $calendar_rows .= '<style>.dash-inner-block td, .dash-inner-block th{ border-bottom: none;}</style>';
            // Replace calendar
            $user_message = str_replace('{{CALENDAR_ROWS}}', $calendar_rows, $user_message);

            // Send and log email
            log_and_send_email_with_attachment(
                FROM_EMAIL_NOTIFICATIONS,
                $event_type == 'personal' ? $creator_email_address : $email_address,
                $subject,
                $user_message,
                $creator_name,
                $ics_file
            );
        }

        // Send to person
        if ($event_type == 'personal' && ($event_category == 'call' || $event_category == 'email') && $user_email != '' && $user_email != null) {
            $user_message = $body;
            $user_message = str_replace('{{COMMENT_BOX}}', '', $user_message);
            $user_message = str_replace('{{COMPANY_NAME}}', $creator_name, $user_message);
            $user_message = str_replace('{{target_user}}', $creator_name, $user_message);
            //
            $utype = 'person';
            $uid = 0;
            //
            $status_rows = '';
            if (!in_array($type, array('cancel'))) {
                // For personal 'call' & 'email'
                $cmpl = strtolower($user_name);
            }

            $user_message = str_replace('{{TO_USER_NAME}}', ucwords($cmpl), $user_message);
            $user_message = str_replace('{{WITH_NAME}}', ucwords($creator_first_name . ' ' . $creator_last_name), $user_message);

            // Create calendar links
            $download_url_vcs = base_url('download-event-file') . '/' . (str_replace('/', '$eb$eb$1', $_this->encrypt->encode('type=vcs&eid=' . $event_sid . '&uname=' . $cmpl . '&uemail' . $user_email . '=&utype=' . $utype . '&uid=' . $uid . '')));
            $download_url_ics = base_url('download-event-file') . '/' . (str_replace('/', '$eb$eb$1', $_this->encrypt->encode('type=ics&eid=' . $event_sid . '&uname=' . $cmpl . '&uemail' . $user_email . '=&utype=' . $utype . '&uid=' . $uid . '')));
            $download_url_gc = base_url('download-event-file') . '/' . (str_replace('/', '$eb$eb$1', $_this->encrypt->encode('type=gc&eid=' . $event_sid . '&uname=' . $cmpl . '&uemail' . $user_email . '=&utype=' . $utype . '&uid=' . $uid . '')));
            $calendar_rows = '<tr><td><br /><strong>Calendar event</strong><br /><br /></td></tr>';
            $calendar_rows .= '<tr><td><img src="' . (base_url('assets/calendar_icons/outlook.png')) . '" width="20"/>&nbsp;&nbsp;<a href="' . ($download_url_vcs) . '">Add to Outlook Calendar</a></td></tr>';
            $calendar_rows .= '<tr><td><img src="' . (base_url('assets/calendar_icons/google.png')) . '" width="20"/>&nbsp;&nbsp;<a href="' . ($download_url_gc) . '">Add to Google Calendar</a></td></tr>';
            $calendar_rows .= '<tr><td><img src="' . (base_url('assets/calendar_icons/apple.png')) . '" width="20"/>&nbsp;&nbsp;<a href="' . ($download_url_ics) . '">Add to Apple Calendar</a></td></tr>';
            $calendar_rows .= '<tr><td><img src="' . (base_url('assets/calendar_icons/calendar.png')) . '" width="20"/>&nbsp;&nbsp;<a href="' . ($download_url_ics) . '">Mobile & Other Calendar</a></td></tr>';
            $calendar_rows .= '<style>.dash-inner-block td, .dash-inner-block th{ border-bottom: none;}</style>';
            // Replace calendar
            $user_message = str_replace('{{CALENDAR_ROWS}}', $calendar_rows, $user_message);
            $user_message = str_replace('{{EVENT_STATUS_BUTTONS}}', '', $user_message);

            // Send and log email
            log_and_send_email_with_attachment(
                FROM_EMAIL_NOTIFICATIONS,
                $user_email,
                $subject,
                $user_message,
                $creator_name,
                $ics_file
            );
        }

        // Send emails to users
        if ($send_to_users && sizeof($participants)) {
            foreach ($participants as $k0 => $v0) {
                //
                $comment_text = '';
                if ($comment != '') {
                    $comment_text = sft("<strong>Comment</strong> <br />");
                    if ($event_update_comment_change_row != '') {
                        $comment_text .= sft($event_update_comment_row);
                        $comment_text .= $event_update_comment_change_row;
                    } else
                        $comment_text .= sft($comment);
                }

                if ($comment_text != '')
                    $comment_text .= sft('<hr />');

                $user_message = $body;
                $user_message = str_replace('{{COMMENT_BOX}}', $comment_text, $user_message);
                $user_message = str_replace('{{COMPANY_NAME}}', $creator_name, $user_message);
                $user_message = str_replace('{{target_user}}', $creator_name, $user_message);
                $user_message = str_replace('{{TO_USER_NAME}}', ucwords($v0['first_name'] . ' ' . $v0['last_name']), $user_message);

                $status_rows = '';
                if (!in_array($type, array('cancel'))) {
                    // Generate status link rows
                    $status_rows = generate_admin_status_rows(
                        array(
                            'user_id' => $v0['id'],
                            'type' => 'participant',
                            'name' => strtolower($v0['first_name'] . ' ' . $v0['last_name']),
                            'email_address' => $v0['email_address']
                        ),
                        $event,
                        $_this
                    );
                }

                $uid = $v0['id'];
                $utype = 'participant';
                $uname = strtolower($v0['first_name'] . ' ' . $v0['last_name']);
                $uemail = $v0['email_address'];
                // Create calendar links
                $download_url_vcs = base_url('download-event-file') . '/' . (str_replace('/', '$eb$eb$1', $_this->encrypt->encode('type=vcs&eid=' . $event_sid . '&uname=' . $uname . '&uemail' . $uemail . '=&utype=' . $utype . '&uid=' . $uid . '')));
                $download_url_ics = base_url('download-event-file') . '/' . (str_replace('/', '$eb$eb$1', $_this->encrypt->encode('type=ics&eid=' . $event_sid . '&uname=' . $uname . '&uemail' . $uemail . '=&utype=' . $utype . '&uid=' . $uid . '')));
                $download_url_gc = base_url('download-event-file') . '/' . (str_replace('/', '$eb$eb$1', $_this->encrypt->encode('type=gc&eid=' . $event_sid . '&uname=' . $uname . '&uemail' . $uemail . '=&utype=' . $utype . '&uid=' . $uid . '')));
                $calendar_rows = '<tr><td><br /><strong>Calendar event</strong><br /><br /></td></tr>';
                $calendar_rows .= '<tr><td><img src="' . (base_url('assets/calendar_icons/outlook.png')) . '" width="20"/>&nbsp;&nbsp;<a href="' . ($download_url_vcs) . '">Add to Outlook Calendar</a></td></tr>';
                $calendar_rows .= '<tr><td><img src="' . (base_url('assets/calendar_icons/google.png')) . '" width="20"/>&nbsp;&nbsp;<a href="' . ($download_url_gc) . '">Add to Google Calendar</a></td></tr>';
                $calendar_rows .= '<tr><td><img src="' . (base_url('assets/calendar_icons/apple.png')) . '" width="20"/>&nbsp;&nbsp;<a href="' . ($download_url_ics) . '">Add to Apple Calendar</a></td></tr>';
                $calendar_rows .= '<tr><td><img src="' . (base_url('assets/calendar_icons/calendar.png')) . '" width="20"/>&nbsp;&nbsp;<a href="' . ($download_url_ics) . '">Mobile & Other Calendar</a></td></tr>';
                $calendar_rows .= '<style>.dash-inner-block td, .dash-inner-block th{ border-bottom: none;}</style>';
                // Replace calendar
                $user_message = str_replace('{{CALENDAR_ROWS}}', $calendar_rows, $user_message);

                // Generate button links
                $user_message = str_replace('{{EVENT_STATUS_BUTTONS}}', $status_rows, $user_message);

                // Send and log email
                log_and_send_email_with_attachment(
                    FROM_EMAIL_NOTIFICATIONS,
                    $v0['email_address'],
                    $subject,
                    $user_message,
                    $creator_name,
                    $ics_file
                );
            }
        }

        // Send emails to non users
        if ($send_to_non_users && sizeof($external_participants)) {
            foreach ($external_participants as $k0 => $v0) {
                //
                $comment_text = '';
                if ($comment != '') {
                    $comment_text = sft("<strong>Comment</strong> <br />");
                    if ($event_update_comment_change_row != '') {
                        $comment_text .= sft($event_update_comment_row);
                        $comment_text .= $event_update_comment_change_row;
                    } else
                        $comment_text .= sft($comment);
                }

                if ($comment_text != '')
                    $comment_text .= sft('<hr />');

                $user_message = $body;
                $user_message = str_replace('{{COMMENT_BOX}}', $comment_text, $user_message);
                $user_message = str_replace('{{COMPANY_NAME}}', $creator_name, $user_message);
                $user_message = str_replace('{{target_user}}', $creator_name, $user_message);
                $user_message = str_replace('{{TO_USER_NAME}}', ucwords($v0['external_participant_name']), $user_message);

                $status_rows = '';
                if (!in_array($type, array('cancel'))) {
                    // Generate status link rows
                    $status_rows = generate_admin_status_rows(
                        array(
                            'user_id' => 0,
                            'type' => 'external participant',
                            'name' => strtolower($v0['external_participant_name']),
                            'email_address' => $v0['external_participant_email']
                        ),
                        $event,
                        $_this
                    );
                }

                $uid = 0;
                $utype = 'external participant';
                $uname = strtolower($v0['external_participant_name']);
                $uemail = $v0['external_participant_email'];
                // Create calendar links
                $download_url_vcs = base_url('download-event-file') . '/' . (str_replace('/', '$eb$eb$1', $_this->encrypt->encode('type=vcs&eid=' . $event_sid . '&uname=' . $uname . '&uemail' . $uemail . '=&utype=' . $utype . '&uid=' . $uid . '')));
                $download_url_ics = base_url('download-event-file') . '/' . (str_replace('/', '$eb$eb$1', $_this->encrypt->encode('type=ics&eid=' . $event_sid . '&uname=' . $uname . '&uemail' . $uemail . '=&utype=' . $utype . '&uid=' . $uid . '')));
                $download_url_gc = base_url('download-event-file') . '/' . (str_replace('/', '$eb$eb$1', $_this->encrypt->encode('type=gc&eid=' . $event_sid . '&uname=' . $uname . '&uemail' . $uemail . '=&utype=' . $utype . '&uid=' . $uid . '')));
                $calendar_rows = '<tr><td><br /><strong>Calendar event</strong><br /><br /></td></tr>';
                $calendar_rows .= '<tr><td><img src="' . (base_url('assets/calendar_icons/outlook.png')) . '" width="20"/>&nbsp;&nbsp;<a href="' . ($download_url_vcs) . '">Add to Outlook Calendar</a></td></tr>';
                $calendar_rows .= '<tr><td><img src="' . (base_url('assets/calendar_icons/google.png')) . '" width="20"/>&nbsp;&nbsp;<a href="' . ($download_url_gc) . '">Add to Google Calendar</a></td></tr>';
                $calendar_rows .= '<tr><td><img src="' . (base_url('assets/calendar_icons/apple.png')) . '" width="20"/>&nbsp;&nbsp;<a href="' . ($download_url_ics) . '">Add to Apple Calendar</a></td></tr>';
                $calendar_rows .= '<tr><td><img src="' . (base_url('assets/calendar_icons/calendar.png')) . '" width="20"/>&nbsp;&nbsp;<a href="' . ($download_url_ics) . '">Mobile & Other Calendar</a></td></tr>';
                $calendar_rows .= '<style>.dash-inner-block td, .dash-inner-block th{ border-bottom: none;}</style>';
                // Replace calendar
                $user_message = str_replace('{{CALENDAR_ROWS}}', $calendar_rows, $user_message);

                // Generate button links
                $user_message = str_replace('{{EVENT_STATUS_BUTTONS}}', $status_rows, $user_message);

                // Send and log email
                log_and_send_email_with_attachment(
                    FROM_EMAIL_NOTIFICATIONS,
                    $v0['external_participant_email'],
                    $subject,
                    $user_message,
                    $creator_name,
                    $ics_file
                );
            }
        }
        // _e($event, true);
        // Send to system users
        if (sizeof($users_array) && $users_array != '') {
            foreach ($users_array as $k0 => $v0) {
                $user_message = $body;

                $user_message = str_replace('{{TO_USER_NAME}}', trim($v0['first_name'] . ' ' . $v0['last_name']), $user_message);
                $user_message = str_replace('{{COMMENT_BOX}}', '', $user_message);
                $user_message = str_replace('{{COMPANY_NAME}}', $creator_name, $user_message);
                $user_message = str_replace('{{target_user}}', $creator_name, $user_message);

                $status_rows = '';
                if (!in_array($type, array('cancel'))) {
                    // Generate status link rows
                    $status_rows = generate_admin_status_rows(
                        array(
                            'user_id' => $v0['id'],
                            'type' => $v0['type'],
                            'name' => trim($v0['first_name'] . ' ' . $v0['last_name']),
                            'email_address' => $v0['email_address']
                        ),
                        $event,
                        $_this
                    );
                }

                $uid = $v0['id'];
                $utype = $v0['type'];
                $uname = strtolower($v0['first_name'] . ' ' . $v0['last_name']);
                $uemail = $v0['email_address'];
                // Create calendar links
                $download_url_vcs = base_url('download-event-file') . '/' . (str_replace('/', '$eb$eb$1', $_this->encrypt->encode('type=vcs&eid=' . $event_sid . '&uname=' . $uname . '&uemail' . $uemail . '=&utype=' . $utype . '&uid=' . $uid . '')));
                $download_url_ics = base_url('download-event-file') . '/' . (str_replace('/', '$eb$eb$1', $_this->encrypt->encode('type=ics&eid=' . $event_sid . '&uname=' . $uname . '&uemail' . $uemail . '=&utype=' . $utype . '&uid=' . $uid . '')));
                $download_url_gc = base_url('download-event-file') . '/' . (str_replace('/', '$eb$eb$1', $_this->encrypt->encode('type=gc&eid=' . $event_sid . '&uname=' . $uname . '&uemail' . $uemail . '=&utype=' . $utype . '&uid=' . $uid . '')));
                $calendar_rows = '<tr><td><br /><strong>Calendar event</strong><br /><br /></td></tr>';
                $calendar_rows .= '<tr><td><img src="' . (base_url('assets/calendar_icons/outlook.png')) . '" width="20"/>&nbsp;&nbsp;<a href="' . ($download_url_vcs) . '">Add to Outlook Calendar</a></td></tr>';
                $calendar_rows .= '<tr><td><img src="' . (base_url('assets/calendar_icons/google.png')) . '" width="20"/>&nbsp;&nbsp;<a href="' . ($download_url_gc) . '">Add to Google Calendar</a></td></tr>';
                $calendar_rows .= '<tr><td><img src="' . (base_url('assets/calendar_icons/apple.png')) . '" width="20"/>&nbsp;&nbsp;<a href="' . ($download_url_ics) . '">Add to Apple Calendar</a></td></tr>';
                $calendar_rows .= '<tr><td><img src="' . (base_url('assets/calendar_icons/calendar.png')) . '" width="20"/>&nbsp;&nbsp;<a href="' . ($download_url_ics) . '">Mobile & Other Calendar</a></td></tr>';
                $calendar_rows .= '<style>.dash-inner-block td, .dash-inner-block th{ border-bottom: none;}</style>';
                // Replace calendar
                $user_message = str_replace('{{CALENDAR_ROWS}}', $calendar_rows, $user_message);


                // Generate button links
                $user_message = str_replace('{{EVENT_STATUS_BUTTONS}}', $status_rows, $user_message);

                // Send and log email
                log_and_send_email_with_attachment(
                    FROM_EMAIL_NOTIFICATIONS,
                    $v0['email_address'],
                    $subject,
                    $user_message,
                    $creator_name,
                    $ics_file
                );
            }
        }

        // Send to external users
        if (sizeof($external_users)) {
            foreach ($external_users as $k0 => $v0) {
                $user_message = $body;

                $user_message = str_replace('{{TO_USER_NAME}}', trim($v0['name']), $user_message);
                $user_message = str_replace('{{COMMENT_BOX}}', '', $user_message);
                $user_message = str_replace('{{COMPANY_NAME}}', $creator_name, $user_message);
                $user_message = str_replace('{{target_user}}', $creator_name, $user_message);

                $status_rows = '';
                if (!in_array($type, array('cancel'))) {
                    // Generate status link rows
                    $status_rows = generate_admin_status_rows(
                        array(
                            'user_id' => 0,
                            'type' => 'external user',
                            'name' => trim($v0['name']),
                            'email_address' => $v0['email_address']
                        ),
                        $event,
                        $_this
                    );
                }

                $uid = 0;
                $utype = 'external user';
                $uname = strtolower($v0['name']);
                $uemail = $v0['email_address'];
                // Create calendar links
                $download_url_vcs = base_url('download-event-file') . '/' . (str_replace('/', '$eb$eb$1', $_this->encrypt->encode('type=vcs&eid=' . $event_sid . '&uname=' . $uname . '&uemail' . $uemail . '=&utype=' . $utype . '&uid=' . $uid . '')));
                $download_url_ics = base_url('download-event-file') . '/' . (str_replace('/', '$eb$eb$1', $_this->encrypt->encode('type=ics&eid=' . $event_sid . '&uname=' . $uname . '&uemail' . $uemail . '=&utype=' . $utype . '&uid=' . $uid . '')));
                $download_url_gc = base_url('download-event-file') . '/' . (str_replace('/', '$eb$eb$1', $_this->encrypt->encode('type=gc&eid=' . $event_sid . '&uname=' . $uname . '&uemail' . $uemail . '=&utype=' . $utype . '&uid=' . $uid . '')));
                $calendar_rows = '<tr><td><br /><strong>Calendar event</strong><br /><br /></td></tr>';
                $calendar_rows .= '<tr><td><img src="' . (base_url('assets/calendar_icons/outlook.png')) . '" width="20"/>&nbsp;&nbsp;<a href="' . ($download_url_vcs) . '">Add to Outlook Calendar</a></td></tr>';
                $calendar_rows .= '<tr><td><img src="' . (base_url('assets/calendar_icons/google.png')) . '" width="20"/>&nbsp;&nbsp;<a href="' . ($download_url_gc) . '">Add to Google Calendar</a></td></tr>';
                $calendar_rows .= '<tr><td><img src="' . (base_url('assets/calendar_icons/apple.png')) . '" width="20"/>&nbsp;&nbsp;<a href="' . ($download_url_ics) . '">Add to Apple Calendar</a></td></tr>';
                $calendar_rows .= '<tr><td><img src="' . (base_url('assets/calendar_icons/calendar.png')) . '" width="20"/>&nbsp;&nbsp;<a href="' . ($download_url_ics) . '">Mobile & Other Calendar</a></td></tr>';
                $calendar_rows .= '<style>.dash-inner-block td, .dash-inner-block th{ border-bottom: none;}</style>';
                // Replace calendar
                $user_message = str_replace('{{CALENDAR_ROWS}}', $calendar_rows, $user_message);
                // Generate button links
                $user_message = str_replace('{{EVENT_STATUS_BUTTONS}}', $status_rows, $user_message);

                // Send and log email
                log_and_send_email_with_attachment(
                    FROM_EMAIL_NOTIFICATIONS,
                    $v0['email_address'],
                    $subject,
                    $user_message,
                    $creator_name,
                    $ics_file
                );
            }
        }
    }
}

/**
 * Generates event status rows for email
 * Added on: 20-06-2019
 *
 * @param $user Array
 * @param $event Array
 * @param $_this Instance
 *
 * @return String
 */
if (!function_exists('generate_admin_status_rows')) {
    function generate_admin_status_rows($user, $event, $_this)
    {
        // Load encryption class
        // to encrypt employee/applicant id
        // and email
        $_this->load->library('encrypt');
        $base_url = base_url('event-detail') . '/';
        // Set event code string
        $string_conf = 'id=' . $user['user_id'] . ':eid=' . $event['event_sid'] . ':etype=' . (strtolower($event['event_type'])) . ':status=confirmed:type=' . ($user['type']) . ':name=' . $user['name'] . ':email=' . $user['email_address'];
        $string_notconf = 'id=' . $user['user_id'] . ':eid=' . $event['event_sid'] . ':etype=' . (strtolower($event['event_type'])) . ':status=notconfirmed:type=' . ($user['type']) . ':name=' . $user['name'] . ':email=' . $user['email_address'];
        $string_reschedule = 'id=' . $user['user_id'] . ':eid=' . $event['event_sid'] . ':etype=' . (strtolower($event['event_type'])) . ':status=reschedule:type=' . ($user['type']) . ':name=' . $user['name'] . ':email=' . $user['email_address'];

        // Set encoded string
        $enc_string_conf = $base_url . str_replace('/', '$eb$eb$1', $_this->encrypt->encode($string_conf));
        $enc_string_notconf = $base_url . str_replace('/', '$eb$eb$1', $_this->encrypt->encode($string_notconf));
        $enc_string_reschedule = $base_url . str_replace('/', '$eb$eb$1', $_this->encrypt->encode($string_reschedule));

        // Set button rows
        $button_rows = '';
        $button_rows .= sft('<p style="font-size: 20px;">Please select one of the options below to "Confirm", "Reschedule" or let us know that you "Cannot Attend".</p>');
        $button_rows .= '<tr><td>';

        $button_rows .= '   <a href="' . $enc_string_conf . '" target="_blank" style="background-color: #009966; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block; margin: 5px;">Confirm</a>';
        $button_rows .= '   <a href="' . $enc_string_reschedule . '" target="_blank" style="background-color: #006699; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block; margin: 5px; ">Reschedule</a>';
        $button_rows .= '   <a href="' . $enc_string_notconf . '" target="_blank" style="background-color: #cc1100; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block; margin: 5px; ">Cannot attend</a>';

        $button_rows .= '</td></tr>';

        return $button_rows;
    }
}

/**
 * Generates tr
 * Added on: 20-06-2019
 *
 * @param $a String
 * @param $b String Optional
 *
 * @return String
 */
if (!function_exists('sft')) {
    function sft($a, $b = '')
    {
        $row = '<tr>';
        if ($b != '')
            $row .= "   <td><span style='float:left;width:50%'>${a}</span><span style='float:right;width:50%'>${b}</span> </td>";
        else
            $row .= "   <td>${a}</td>";
        $row .= '</tr>';
        return $row;
    }
}


/**
 * Generate VSF file for outlook
 * Created on: 24-06-2019
 *
 * @param $event_sid Integer
 *
 * @return String
 */
if (!function_exists('generate_admin_vcs_file_for_event')) {
    function generate_admin_vcs_file_for_event($event_sid)
    {
        $_this = get_instance();
        // Load encryption library
        $_this->load->library('encrypt');
        // Loads calendar modal
        $_this->load->model('manage_admin/dashboard_model', 'cm');
        $event = $_this->cm->event_detail($event_sid);
        //
        $event_category = reset_category($event['event_category']);
        //
        $event_title = "{$event_category} : " . $event['event_title'];

        $new_start_time = DateTime::createFromFormat(
            'h:i A',
            $event['event_start_time']
        )->format('His');

        $new_end_time = DateTime::createFromFormat(
            'h:i A',
            $event['event_end_time']
        )->format('His');
        $event['event_date_gc'] = DateTime::createFromFormat(
            'Y-m-d',
            $event['event_date']
        )->format('Ymd');

        $ss = "\n";
        $ds = "\n\n";
        // Set details
        $details = '';
        $details .= "Event Type:{$ss}";
        $details .= "{$event_category}{$ss}";
        $details .= $ds;

        // $user_sid = $event['user_id'];
        // $event_type = $event['user_type'];
        // $event_sid  = $event_sid;
        // $user_name  = $event['first_name']." ".$event['last_name'];
        // $user_email = $event['email_address'];
        //
        // $base_url = base_url().'event/';
        // // Set event code string
        // $string_conf = 'id='.$user_sid.':eid='.$event_sid.':etype='.$event_type.':type=confirmed:name='.$user_name.':email='.$user_email;
        // $string_notconf = 'id='.$user_sid.':eid='.$event_sid.':etype='.$event_type.':type=notconfirmed:name='.$user_name.':email='.$user_email;
        // $string_reschedule = 'id='.$user_sid.':eid='.$event_sid.':etype='.$event_type.':type=reschedule:name='.$user_name.':email='.$user_email;
        // //
        // if(strtolower($event['category_uc']) == 'training-session')
        //     $string_attended = 'id='.$user_sid.':eid='.$event_sid.':etype='.$event_type.':type=attended:name='.$user_name.':email='.$user_email;
        // // Set encoded string
        // $enc_string_conf = $base_url.str_replace( '/', '$eb$eb$1', $_this->encrypt->encode($string_conf));
        // //
        // if(strtolower($event['category_uc']) == 'training-session')
        //     $enc_string_attended = $base_url.str_replace( '/', '$eb$eb$1', $_this->encrypt->encode($string_attended));
        // //
        // $enc_string_notconf  = $base_url.str_replace( '/', '$eb$eb$1', $_this->encrypt->encode($string_notconf));
        // $enc_string_reschedule = $base_url.str_replace( '/', '$eb$eb$1', $_this->encrypt->encode($string_reschedule));

        // $details .= "Event Links:{$ss}";

        // if($event['event_category'] == 'training-session'){
        //     $details .= "Attended: {$enc_string_attended}{$ss}";
        // }

        // $details .= "Confirm: {$enc_string_conf}{$ss}";
        // $details .= "Reschedule: {$enc_string_reschedule}{$ss}";
        // $details .= "Cannot Attend: {$enc_string_notconf}{$ss}";

        if (sizeof($event['participants']) && isset($event['participants'][0]['first_name'])) {
            $details .= $ds;
            $details .= ($event['event_category'] == 'training-session' ? 'Attendee(s)' : 'Participant(s)') . "{$ss}";
            foreach ($event['participants'] as $k0 => $v0) {
                $details .= "&#8277;&nbsp;" . (ucwords($v0['first_name'] . ' ' . $v0['last_name'])) . "" . ($v0['show_email'] == 1 ? '(' . ($v0['email_address']) . ')' : '') . "{$ss}";
            }
        }

        $details .= $ds;

        $start_date_time = $event['event_date_gc'] . "T{$new_start_time}";
        $end_date_time = $event['event_date_gc'] . "T{$new_end_time}";
        $address = $event['event_address'];
        $details = str_replace('&nbsp;', ' ', nl2br($details));
        $details = str_replace('&#8277;', '-', $details);
        $details = preg_replace('/\s+/', "", $details);
        $details = str_replace('<br/>', "=0D=0A", $details);

        $content = <<<EOD
BEGIN:VCALENDAR
BEGIN:VEVENT
DTSTART:$start_date_time
DTEND:$end_date_time
LOCATION;ENCODING=QUOTED-PRINTABLE:$address
DESCRIPTION;ENCODING=QUOTED-PRINTABLE:$details
SUMMARY;ENCODING=QUOTED-PRINTABLE:$event_title
PRIORITY:1
END:VEVENT
END:VCALENDAR
EOD;

        $destination = APPPATH . '../assets/admin/vcs/';
        $uid = STORE_NAME . '-event-' . clean($event['event_sid']);
        //
        if (!is_dir($destination))
            mkdir($destination, 0777, true);
        $targetFileName = $uid . '.vcs';

        $file_name = $destination . $targetFileName;

        $file = fopen($file_name, 'w');
        fwrite($file, $content);
        fclose($file);

        return $file_name;
    }
}

/**
 * Fetch timezones
 * Created on: 25-06-2019
 * Key: TIMEZONE
 *
 * @param $type String Optional
 * 'All' = Return all timezones
 * '-11' = Find a specific timezone
 *
 * @param $index String Optional
 *
 * @return Array
 */
if (!function_exists('get_timezones')) {
    function get_timezones($type = 'all', $index = '')
    {
        // Timezones without daylight time
        // America
        $timezones = [];
        // $timezones[] = ['value' => '-11:00|US|01|SST', 'name' => 'Samoa Time',    'key' => 'SST'];
        // $timezones[] = ['value' => '-08:00|US|05|AKST', 'name' => 'Alaska Time',   'key' => 'AKST'];
        $timezones[] = ['value' => '-07:00|US|07|PST', 'name' => 'Pacific Time', 'key' => 'PST', 'type' => 'north_america'];
        $timezones[] = ['value' => '-06:00|US|09|MST', 'name' => 'Mountain Time', 'key' => 'MST', 'type' => 'north_america'];
        $timezones[] = ['value' => '-05:00|US|11|CST', 'name' => 'Central Time', 'key' => 'CST', 'type' => 'north_america'];
        $timezones[] = ['value' => '-04:00|US|13|EST', 'name' => 'Eastern Time', 'key' => 'EST', 'type' => 'north_america'];
        //$timezones[] = ['value' => '-04:00|US|14|AST', 'name' => 'Atlantic Time', 'key' => 'AST'];
        // $timezones[] = ['value' => '+10:00|US|15|CHST', 'name' => 'Chamorro Time', 'key' => 'CHST'];
        $timezones[] = ['value' => '-09:00|US|03|HST', 'name' => 'Hawaii-Aleutian Time', 'key' => 'HST'];
        $timezones[] = ['value' => '-03:00|US|15|NST', 'name' => 'Newfoundland Standard Time', 'key' => 'NST'];
        // Europe

        /*
        $timezones[] = ['value' => '+02:00|EU|02|CET', 'name' => 'Central European Time', 'key' => 'CET'];
        $timezones[] = ['value' => '+02:00|EU|05|EET', 'name' => 'Eastern European Time', 'key' => 'EET'];
        $timezones[] = ['value' => '+03:00|EU|06|FET', 'name' => 'Further European Time', 'key' => 'FET'];
        $timezones[] = ['value' => '+00:00|EU|07|GMT', 'name' => 'Greenwich Mean Time',   'key' => 'GMT'];
        $timezones[] = ['value' => '+01:00|EU|01|BST', 'name' => 'British Time',          'key' => 'BST'];
        $timezones[] = ['value' => '+04:00|EU|08|KUYT', 'name' => 'Kuybyshev Time',        'key' => 'KUYT'];
        $timezones[] = ['value' => '+01:00|EU|09|IST', 'name' => 'Irish Time',            'key' => 'IST'];
        $timezones[] = ['value' => '+04:00|EU|10|MSK', 'name' => 'Moscow Time',           'key' => 'MSK'];
        $timezones[] = ['value' => '+04:00|EU|12|SAMT', 'name' => 'Samara Time',           'key' => 'SAMT'];
        $timezones[] = ['value' => '+03:00|EU|13|TRT', 'name' => 'Turkey Time',           'key' => 'TRT'];
        $timezones[] = ['value' => '+01:00|EU|14|WET', 'name' => 'Western European Time', 'key' => 'WET'];
      */

        // Merge arrays
        $zones = $timezones;
        // Check and return
        if ($type == 'all')
            return $zones; // return all zones
        else if ($type == 'north_america') {
            $selected_zones = [];
            foreach ($zones as $k0 => $v0) {
                if (isset($v0['type']) && $type == $v0['type']) {
                    $selected_zones[] = $v0;
                }
            }
            return $selected_zones;
        } else
            foreach ($zones as $k0 => $v0)
                if ($type == $v0['key'])
                    return $index == '' ? $v0 : ($index == 'name' ? $v0['name'] . ' (' . ($v0['key']) . ')' : $v0[$index]); // return specific zone
    }
}


/**
 * Generates dropdown for timezone
 * Created on: 25-06-2019
 * Key: TIMEZONE
 *
 * @param $selected String Optional
 * @param $attrs Array Optional
 *
 * @return String
 */
if (!function_exists('timezone_dropdown')) {
    function timezone_dropdown($selected = '', $attrs = array(), $type = 'all')
    {
        // Fetch timezones
        $timezones = get_timezones($type);
        $timezone_rows = '';
        $timezone_rows .= '<select';
        // Set Attrs
        if (sizeof($attrs))
            foreach ($attrs as $k0 => $v0)
                $timezone_rows .= ' ' . $k0 . ' = "' . $v0 . '"';
        $timezone_rows .= '>';
        if ($selected == '')
            $timezone_rows .= '<option value="">Please Select</option>';
        if (sizeof($timezones))
            foreach ($timezones as $k0 => $v0)
                $timezone_rows .= '<option ' . ($selected == $v0['key'] ? 'selected="true"' : '') . ' value="' . ($v0['key']) . '">' . ($v0['name']) . ' (' . ($v0['key']) . ')</option>';
        $timezone_rows .= '</select>';
        return $timezone_rows;
    }
}


/**
 * Parse timezone
 * Created on: 26-06-2019
 * Key: TIMEZONE
 *
 * @param $timezone Array|String (-11:00|US|01|SST)
 * @param $find String
 * 'all' = return array
 * 'continent' = return timezone continent
 * 'time' = return timezone difference from UTC
 * 'abbr' = return timezone 'abbr'
 * @param $delimiter String
 *
 * @return Array|Bool
 */
if (!function_exists('parse_timezone')) {
    function parse_timezone($timezone, $find = 'all', $delimiter = '|')
    {
        if (!is_array($timezone))
            $timezone = explode($delimiter, $timezone);
        //
        if (!sizeof($timezone))
            return false;
        // Reset timezone
        $find = strtolower(trim($find));
        // Check index and return result
        if ($find == 'all')
            return $timezone;
        else if ($find == 'time')
            return $timezone[0];
        else if ($find == 'continent')
            return $timezone[1];
        else if ($find == 'abbr')
            return $timezone[3];
        else if ($find == 'name')
            return getTimeZoneFromAbbr($timezone[3]);
        else if ($find == 'time_in_seconds') {
            $tmp = new DateTime('now', new DateTimeZone(getTimeZoneFromAbbr($timezone[3])));
            return $tmp->getOffset();
        } else
            return $timezone[2];
        //
        return false;
    }
}

/**
 * Change datetime Zone
 * Created on: 26-06-2019
 * Key: TIMEZONE
 *
 * @param $data Array
 * 'datetime'    DateTime string
 * 'from_format' Format of current datetime
 * 'format'      Format of new datetime
 * 'new_zone'    To convert to which zone
 *
 * @return Array|Bool
 */
if (!function_exists('reset_timezone')) {
    function reset_timezone($data)
    {
        // Check for array set and size
        if (!is_array($data) || !sizeof($data))
            return false;
        // Reset formats
        if (!isset($data['from_format']))
            $from_format = 'Y-m-d';
        if (!isset($data['format']))
            $format = 'Y-m-d\TH:i:s\ZO';
        $to_format = 'Y-m-d H:i:s O P e I';
        $from_zone = STORE_DEFAULT_TIMEZONE_ABBR;
        // Convert array indexes to variables
        extract($data);
        // Check for date string
        if (!isset($datetime))
            return false;
        // Check for new zone
        if (!isset($new_zone))
            return false;
        // For debugging
        if (isset($debug) && $debug)
            _e($from_zone . ' - ' . $new_zone, true);
        // Set return array
        $return_array = array();
        // _e($from_zone, true);
        // _e(timezone_name_from_abbr($from_zone), true);
        // Let's create date
        $date_obj = DateTime::createFromFormat($from_format, $datetime, new DateTimeZone(getTimeZoneFromAbbr($from_zone)));
        if ($date_obj) {
            if ($from_zone != 'UTC') {
                // Convert it to utc
                $utc = $date_obj->setTimezone(new DateTimeZone(getTimeZoneFromAbbr('UTC')));
                // Get utc date
                $return_array['UTC'] = parse_datetime($utc->format($to_format));
            }
            //
            if (isset($from_zone)) {
                $fromzone = $date_obj->setTimezone(new DateTimeZone(getTimeZoneFromAbbr($from_zone)));
                $return_array[$from_zone] = parse_datetime($fromzone->format($to_format));
            }
            //
            if ($from_zone != 'UTC')
                $tozone = $utc->setTimezone(new DateTimeZone(getTimeZoneFromAbbr($new_zone)));
            else
                $tozone = $date_obj->setTimezone(new DateTimeZone(getTimeZoneFromAbbr($new_zone)));
            $return_array[$new_zone] = parse_datetime($tozone->format($to_format));
            $return_array['date_time_string'] = $tozone->format($format);
        } else {
            $return_array['date_time_string'] = $datetime;
        }

        // if(isset($debug)) _e($return_array, true, true);

        return $return_array;
    }
}

/**
 * Parse datetime string
 * Created on: 26-06-2019
 *
 * @param $date_time_string String (Y-m-dTH:i:sZ)
 *
 * @return String
 */
if (!function_exists('parse_datetime')) {
    function parse_datetime($date_time_string)
    {
        $tmp = explode(' ', $date_time_string);
        $return_array['date'] = trim($tmp[0]);
        $return_array['time'] = trim($tmp[1]);
        $return_array['zone'] = trim($tmp[2]);
        $return_array['zone_with_sep'] = trim($tmp[3]);
        $return_array['timezone'] = trim($tmp[4]);
        $return_array['daylight'] = trim($tmp[5]);

        return $return_array;
    }
}

/**
 * Convert  datetime
 * Created on: 26-06-2019
 * Key: TIMEZONE
 *
 * @param $data Array
 * 'datetime'      String
 * '_this'         Instance
 * 'from_format'   String Optional
 * 'format'        String Optional
 * 'type'          String Optional ('user', 'company', 'exective', 'admin', 'affiliate')
 * 'short_format'  Boolean (Only used from ATS listing for short format)
 *
 * @return String
 */
if (!function_exists('reset_datetime')) {
    function reset_datetime($data, $short_format = false)
    {
        // Defaults
        $from_format = 'Y-m-d H:i:s';
        $format = 'M d Y, D H:i:s';
        $type = 'user';
        $from_timezone = STORE_DEFAULT_TIMEZONE_ABBR;
        $timezone = false;
        $with_timezone = 0;
        // Check array size
        if (!is_array($data) || !sizeof($data) || !isset($data['datetime']) || !isset($data['_this']))
            return false;
        $data['datetime'] = trim($data['datetime']);
        if ($data['datetime'] == NULL || $data['datetime'] == '' || $data['datetime'] == 'null')
            return null;
        // Check if only date is sent
        // then reset formats to date
        if (!preg_match('/\s[0-9]{2}:[0-9]{2}:[0-9]{2}/', $data['datetime'])) {
            $from_format = 'Y-m-d';
            $format = 'M d Y, D';
        }
        // Check if only time is sent
        // then reset formats to time
        if (!preg_match('/[0-9]{4}-[0-9]{2}-[0-9]{2}/', $data['datetime']) && !preg_match('/[0-9]{2}-[0-9]{2}-[0-9]{4}/', $data['datetime'])) {
            $from_format = 'H:i:s';
            $format = 'H:i:s';
        }
        // Convert indexes to variables
        extract($data);
        // Reset type
        $type = strtolower(trim($type));
        $with_timezone = (int) $with_timezone;
        if ($with_timezone === 1)
            $format .= ', T \(P\)';
        // Check for login session
        if ($_this->session->userdata('logged_in')) {
            // If the type is user
            if ($type == 'user') {
                if (clean_string($_this->session->userdata('logged_in')['employer_detail'], 'timezone') == '') {
                    // Check for companys timezone
                    if (clean_string($_this->session->userdata('logged_in')['company_detail'], 'timezone') == '')
                        $timezone = STORE_DEFAULT_TIMEZONE_ABBR;
                    else
                        $timezone = $_this->session->userdata('logged_in')['company_detail']['timezone'];
                } else
                    $timezone = $_this->session->userdata('logged_in')['employer_detail']['timezone'];
            } else if ($type == 'company') { // For company
                if (clean_string($_this->session->userdata('logged_in')['company_detail'], 'timezone') == '')
                    $timezone = STORE_DEFAULT_TIMEZONE_ABBR;
                else
                    $timezone = $_this->session->userdata('logged_in')['company_detail']['timezone'];
            } else
                $timezone = STORE_DEFAULT_TIMEZONE_ABBR;
        }

        if (isset($revert))
            $from_timezone = $timezone;
        // Set user given timezone
        $timezone = isset($new_zone) ? $new_zone : $timezone;
        if (!$timezone)
            return $datetime;

        // $timezone = 'CHST';
        // Reset timezone
        if (!preg_match('/^[A-Z]/', $timezone))
            $timezone = STORE_DEFAULT_TIMEZONE_ABBR;
        if (!preg_match('/^[A-Z]/', $from_timezone))
            $from_timezone = STORE_DEFAULT_TIMEZONE_ABBR;
        // _e($timezone);
        //
        reset_format($from_format);
        if ($short_format) {
            $format = 'M d Y, D';
        } else {
            reset_format($format, 'M d Y, D H:i:s');
        }
        $a = array(
            'datetime' => $datetime,
            'from_format' => $from_format,
            'format' => $format,
            'new_zone' => $timezone,
            'from_zone' => $from_timezone
        );
        //
        if (isset($debug) && $debug)
            $a['debug'] = $debug;
        //
        $n = reset_timezone($a);
        return isset($n['date_time_string']) ? $n['date_time_string'] : $datetime;
    }
}

/**
 * Resets date time formats
 * Created on: 26-06-2019
 * Key: TIMEZONE
 *
 * @param $format Reference
 * @param $syn String
 *
 * @return String
 */
if (!function_exists('reset_format')) {
    function reset_format(&$format, $syn = 'Y-m-d H:i:s')
    {
        if ($format != $syn) {
            switch ($format) {
                case 'b d Y H:i a':
                    $format = 'M d Y H:i D';
                    break;
                case 'default':
                    $format = 'M d Y, D H:i:s';
                    break;
                case 'with timezone':
                    $format = 'M d Y, D H:i:s, T \(P\)';
                    break;
            }
        }
    }
}

/**
 * Get current timezone
 * Created on: 28-06-2019
 * Key: TIMEZONE
 *
 * @param $_this Instance Optional
 *
 * @return String
 */
if (!function_exists('get_current_timezone')) {
    function get_current_timezone($_this = false)
    {
        //
        $timezone = false;
        if ($_this === false)
            $_this = &get_instance();
        // Check if user time zone is not set
        if (
            $_this->session->userdata('logged_in') &&
            clean_string($_this->session->userdata('logged_in')['employer_detail'], 'timezone') == ''
        ) {
            // Check for companys timezone
            if (clean_string($_this->session->userdata('logged_in')['company_detail'], 'timezone') == '')
                $timezone = STORE_DEFAULT_TIMEZONE_ABBR;
            else
                $timezone = $_this->session->userdata('logged_in')['company_detail']['timezone'];
        } else
            $timezone = $_this->session->userdata('logged_in')['employer_detail']['timezone'];

        // Get the current selected timezone
        $zone = get_timezones($timezone);
        $zone['offset'] = parse_timezone($zone['value'], 'time_in_seconds');
        $zone['time'] = parse_timezone($zone['value'], 'time');
        $zone['zone'] = parse_timezone($zone['value'], 'name');
        unset($zone['value']);
        return $zone;
    }
}

/**
 * Get current datetime
 * Created on: 06-08-2019
 * Key: TIMEZONE
 *
 * @param $array  Array
 * $to_format String
 * Default is 'Y-m-d H:i:s'
 * _this      Instance
 * $extra      String   Optional
 *
 * @return String
 */
if (!function_exists('get_current_datetime')) {
    function get_current_datetime($array)
    {
        if (!isset($array['to_format'], $array['_this']))
            return false;
        if ($array['to_format'] == '')
            return false;
        $type = 'user';
        extract($array);
        //
        $timezone = STORE_DEFAULT_TIMEZONE_ABBR;
        if ($_this === false)
            $_this = &get_instance();
        // Check if user time zone is not set
        if ($_this->session->userdata('logged_in')) {
            // If the type is user
            if ($type == 'user') {
                if (clean_string($_this->session->userdata('logged_in')['employer_detail'], 'timezone') == '') {
                    // Check for companys timezone
                    if (clean_string($_this->session->userdata('logged_in')['company_detail'], 'timezone') == '')
                        $timezone = STORE_DEFAULT_TIMEZONE_ABBR;
                    else
                        $timezone = $_this->session->userdata('logged_in')['company_detail']['timezone'];
                } else
                    $timezone = $_this->session->userdata('logged_in')['employer_detail']['timezone'];
            } else if ($type == 'company') { // For company
                if (clean_string($_this->session->userdata('logged_in')['company_detail'], 'timezone') == '')
                    $timezone = STORE_DEFAULT_TIMEZONE_ABBR;
                else
                    $timezone = $_this->session->userdata('logged_in')['company_detail']['timezone'];
            } else
                $timezone = STORE_DEFAULT_TIMEZONE_ABBR;
        }

        // Get the current selected timezone
        $date = new DateTime(date('Y-m-d'), new DateTimeZone(getTimeZoneFromAbbr($timezone)));
        return $date->format($to_format) . (isset($extra) ? $extra : '');
    }
}

/**
 * Reset calendar event datetime
 * Created on: 02-07-2019
 *
 * @param $event Reference
 * @param $_this Instance
 * @param $is_utc Bool Optional
 *
 * @return VOID
 */
if (!function_exists('reset_event_datetime')) {
    function reset_event_datetime(&$event, $_this, $is_utc = FALSE)
    {
        // Reset date/time
        $from_format = 'Y-m-d';
        if (isset($event['date'])) {
            if (!preg_match('/[0-9]{4}-[0-9]{2}-[0-9]{2}/', $event['date']))
                $from_format = 'm-d-Y';
        }
        // Reset calendar start datetime
        if (isset($event['start'])) {
            $start_arr = array(
                'datetime' => $event['start'],
                'from_format' => 'Y-m-d\TH:i',
                'format' => 'Y-m-d\TH:i',
                '_this' => $_this
            );
            if (!empty($event['event_timezone'])) {
                $start_arr['new_zone'] = $event['event_timezone'];
            }
            $event['start'] = reset_datetime($start_arr);
        }

        // Reset calendar end datetime
        if (isset($event['end'])) {
            $end_arr = array(
                'datetime' => $event['end'],
                'from_format' => 'Y-m-d\TH:i',
                'format' => 'Y-m-d\TH:i',
                '_this' => $_this
            );
            if (!empty($event['event_timezone'])) {
                $end_arr['new_zone'] = $event['event_timezone'];
            }
            $event['end'] = reset_datetime($end_arr);
        }

        // Reset event start time
        if (isset($event['event_start_time'])) {
            $event_starttime = $event['event_start_time'];
            $event_start_time_arr = array(
                'datetime' => $event['event_start_time'],
                'from_format' => 'h:iA',
                'format' => 'h:iA',
                '_this' => $_this
            );
            if (!empty($event['event_timezone'])) {
                $event_start_time_arr['new_zone'] = $event['event_timezone'];
            }
            $event['event_start_time'] = reset_datetime($event_start_time_arr);
        }

        // Reset event start time
        if (isset($event['event_end_time'])) {
            $event_end_time_arr = array(
                'datetime' => $event['event_end_time'],
                'from_format' => 'h:iA',
                'format' => 'h:iA',
                'new_zone' => $event['event_timezone'],
                '_this' => $_this
            );
            if (!empty($event['event_timezone'])) {
                $event_end_time_arr['new_zone'] = $event['event_timezone'];
            }
            $event['event_end_time'] = reset_datetime($event_end_time_arr);
        }

        // Reset event start time
        if (isset($event['eventstarttime'])) {
            $event_starttime = $event['eventstarttime'];
            $event['eventstarttime'] = reset_datetime(array(
                'datetime' => $event['date'] . $event['eventstarttime'],
                'from_format' => $from_format . 'h:iA',
                'format' => 'h:iA',
                'from_timezone' => get_current_timezone($_this)['key'],
                'new_zone' => STORE_DEFAULT_TIMEZONE_ABBR,
                '_this' => $_this
            ));
        }

        // Reset event start time
        if (isset($event['eventendtime'])) {
            $event_endtime = $event['eventendtime'];
            $event['eventendtime'] = reset_datetime(array(
                'datetime' => $event['date'] . $event['eventendtime'],
                'from_format' => $from_format . 'h:iA',
                'format' => 'h:iA',
                'from_timezone' => get_current_timezone($_this)['key'],
                'new_zone' => STORE_DEFAULT_TIMEZONE_ABBR,
                '_this' => $_this
            ));
        }

        // Reset db date
        if (isset($event['date'])) {
            $start_date = '';
            $start_date_format = '';
            //
            if (isset($event_starttime)) {
                $start_date = $event_starttime;
                $start_date_format = 'h:iA';
            } else if (isset($event_endtime)) {
                $start_date = $event_endtime;
                $start_date_format = 'h:iA';
            }
            //
            $a = array(
                'datetime' => $event['date'] . $start_date,
                'from_format' => $from_format . $start_date_format,
                'format' => $from_format,
                '_this' => $_this
            );
            if (!empty($event['event_timezone'])) {
                $a['new_zone'] = $event['event_timezone'];
            }
            // Check for UTC
            if ($is_utc) {
                $a['from_timezone'] = get_current_timezone($_this)['key'];
                $a['new_zone'] = STORE_DEFAULT_TIMEZONE_ABBR;
            }
            $event['date'] = reset_datetime($a);
        }

        // Reset calendar date
        if (isset($event['eventdate'])) {
            $event['eventdate'] = reset_datetime(array(
                'datetime' => $event['eventdate'] . ' 23:59:59',
                'from_format' => 'm-d-Y H:i:s',
                'format' => 'm-d-Y',
                '_this' => $_this
            ));
        }

        // Reset calendar start datetime
        if (isset($event['new_start'])) {
            $event['new_start'] = reset_datetime(array(
                'datetime' => $event['new_start'],
                'from_format' => 'Y-m-d H:i:s',
                'format' => 'Y-m-d H:i:s',
                '_this' => $_this
            ));
        }

        // Reset calendar start datetime
        if (isset($event['new_end'])) {
            $event['new_end'] = reset_datetime(array(
                'datetime' => $event['new_end'],
                'from_format' => 'Y-m-d H:i:s',
                'format' => 'Y-m-d H:i:s',
                '_this' => $_this
            ));
        }

        // Reset calendar start datetime
        if (isset($event['new_date'])) {
            $event['new_date'] = reset_datetime(array(
                'datetime' => $event['new_date'] . ' 23:59:59',
                'from_format' => 'Y-m-d H:i:s',
                'format' => 'Y-m-d',
                '_this' => $_this
            ));
        }
    }
}


/**
 * Check for index in array and for empty values
 * Created on: 03-07-2019
 *
 * @param $data Array
 * @param $index String
 *
 * @return String
 */
if (!function_exists('clean_string')) {
    function clean_string($data, $index = false)
    {
        if (is_array($data)) {
            $cur_data = '';
            if (!isset($data[$index]))
                return '';
            $cur_data = trim($data[$index]);
            if ($index == 'timezone') if (!preg_match('/^[A-Z]/', $cur_data))
                return '';
            return clean_string($cur_data);
        }
        // For string
        if ($data == '' || $data == NULL)
            return '';
        return $data;
    }
}

/**
 * Format phone number
 * Created on: 08-07-2019
 *
 * @param $phone_number Integer
 * @param $strip_country_code Bool Optional
 * Default is 'false'
 * @param $country_code String Optional
 * Default is '+1'
 *
 * @return String
 */
if (!function_exists('phonenumber_format')) {
    function phonenumber_format($phone_number, $strip_country_code = FALSE, $country_code = '+1')
    {
        if (strlen($phone_number) === 0)
            return $phone_number;
        if ($phone_number == $country_code)
            return '';
        if ($phone_number == '')
            return '';
        //
        if (substr($phone_number, 0, 1) == 1) {
            $phone_number = substr($phone_number, 1, strlen($phone_number));
        }
        // Removes country code if exists
        $phone_number = str_replace($country_code, '', $phone_number);
        // Clean phone number
        $phone_number = str_replace('/[^0-9]/', '', $phone_number);
        // Check for us format
        switch ($country_code) {
            // For US & Canada
            case '+1':
                // Match format & convert
                if (preg_match('/^(\d{3})(\d{3})(\d{4})(\d+)?$/', $phone_number, $match))
                    return trim('' . ($strip_country_code ? '' : $country_code . ' ') . '(' . ($match[1]) . ') ' . ($match[2]) . '-' . ($match[3]) . ' ');
                break;
        }
        // When no format is found
        return $phone_number;
    }
}


/**
 * Validate E164 phone number
 * Created on: 22-07-2019
 *
 * @param $phone_number Integer
 * @param $country_code String
 * Default is '+1'
 *
 * @return Bool
 */
if (!function_exists('phonenumber_validate')) {
    function phonenumber_validate($phone_number, $country_code = '+1')
    {
        // Removes country code if exists
        $phone_number = str_replace($country_code, '', $phone_number);
        // Clean phone number
        $phone_number = str_replace('/[^0-9]/', '', $phone_number);
        // Check for us format
        switch ($country_code) {
            // For US & Canada
            case '+1':
                // Match format & convert
                if (preg_match('/^(\d{3})(\d{3})(\d{4})$/', $phone_number, $match))
                    return true;
                return false;
                break;
        }
        // When no format is found
        return $phone_number;
    }
}


/**
 * Format phone number
 * Created on: 08-07-2019
 *
 * @param $sms_module_status Integer
 * @param $comapny_sid Integer
 * @param $to Array
 * Bind the new value to '$to'
 * @param $_this Instance
 *
 * @uses get_company_sms_phonenumber
 * @uses company_phone_regex_module_check
 *
 * @return VOID
 */
if (!function_exists('company_sms_phonenumber')) {
    function company_sms_phonenumber($sms_module_status, $company_sid, &$to, $_this)
    {
        // If sms status in inactive for company then
        // set to 0
        if ($sms_module_status == 0)
            $to['phone_sid'] = '';
        else {
            // Fetch the assigned phone number
            $details = get_company_sms_phonenumber($company_sid, $_this);
            if (!sizeof($details))
                $to['phone_sid'] = '';
            else
                $to['phone_sid'] = $details['phone_sid'];
        }

        company_phone_regex_module_check($company_sid, $to, $_this);
    }
}


/**
 * Fetch SMS phone number row
 * Created on: 18-07-2019
 *
 * @param $comapny_sid Integer
 * @param $_this Instance
 *
 * @return Array
 */
if (!function_exists('get_company_sms_phonenumber')) {
    function get_company_sms_phonenumber($company_sid, $_this)
    {
        $result =
            $_this
                ->db
                ->select('
            portal_company_sms_module.phone_sid,
            portal_company_sms_module.phone_number,
            portal_company_sms_module.message_service_sid
        ')
                ->from('portal_company_sms_module')
                ->join('users', 'users.sid = portal_company_sms_module.company_sid', 'inner')
                ->where('portal_company_sms_module.company_sid', $company_sid)
                ->where('users.sms_module_status != 0', null)
                ->order_by('portal_company_sms_module.sid', 'DESC')
                ->limit(1)
                ->get();
        //
        $result_arr = $result->row_array();
        $result = $result->free_result();

        if (!sizeof($result_arr))
            return array();
        // if($result_arr['sms_module_status'] == 0) return array();
        return $result_arr;
    }
}


/**
 * Format phone number regex
 * Created on: 25-07-2019
 *
 * @param $comapny_sid Integer
 * @param $to Array
 * Bind the new value to '$to'
 * @param $_this Instance
 *
 * @return VOID
 */
if (!function_exists('company_phone_regex_module_check')) {
    function company_phone_regex_module_check($company_sid, &$to, $_this)
    {
        $result =
            $_this
                ->db
                ->select('phone_pattern_module')
                ->from('users')
                ->where('sid', $company_sid)
                ->order_by('sid', 'DESC')
                ->limit(1)
                ->get();
        //
        $result_arr = $result->row_array();
        $result = $result->free_result();

        if (!sizeof($result_arr))
            $to['phone_pattern_enable'] = 0;
        else
            $to['phone_pattern_enable'] = $result_arr['phone_pattern_module'];
    }
}

/**
 * Get SMS notifications
 * Ceated on: 22-07-2019
 *
 * @param $_this Instance
 *
 * @return Integer
 */
if (!function_exists('fetch_admin_sms_notifications')) {
    function fetch_admin_sms_notifications($_this)
    {
        // Load SMS model
        $_this->load->model('manage_admin/sms_model');
        return $_this->sms_model->get_sms_admin(0);
    }
}
/**
 * Get SMS notifications
 * Ceated on: 22-07-2019
 *
 * @param $_this Instance
 *
 * @return Integer
 */
if (!function_exists('getProfileDataChange')) {
    function getProfileDataChange($_this)
    {
        // load user model
        $_this->load->model('2022/User_model', 'user_model');
        return $_this->user_model->getEmployeeHistory([]);
    }
}

/**
 * Send email to applicant/employee to update the number
 * Ceated on: 02-08-2019
 *
 * @param $dataArray Array
 * @param $_this     Instance
 *
 * @return Integer
 */
if (!function_exists('sendEmailToUpdatePhoneNumber')) {
    function sendEmailToUpdatePhoneNumber($dataArray, $_this)
    {
        // Check if array is set or not
        if (!is_array($dataArray) || !sizeof($dataArray))
            return false;
        // Get template header and footer
        $hf = message_header_footer($dataArray['companyId'], ucwords($dataArray["companyName"]));
        //
        $_this->load->library('encrypt');
        //
        $uri_public_string = 'id=' . $dataArray['sid'] . ':type=' . ($dataArray['type']) . ':cid=' . ($dataArray['companyId']) . ':cname=' . (strtolower($dataArray['companyName'])) . '';
        $uri_public_string_enc = base_url('modify/' . (str_replace('/', '$eb$eb$1', $_this->encrypt->encode($uri_public_string))) . '');
        //
        $body = '';
        $body .= '<div style="float: left; width: 100%; padding: 10px;">';
        $body .= '  <p>Dear <b>{{FULLNAME}}</b>,</p>';
        $body .= '  <p>Please, update your phone number by clicking the link below.</p>';
        $body .= '  <p><a href="{{URI_PUBLIC}}" target="_blank" style="background-color: #009966; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block; margin: 5px; ">{{URI_PUBLIC_TEXT}}</a></p>';
        $body .= '</div>';
        //
        $replaceArray = array();
        $replaceArray['{{FULLNAME}}'] = ucwords($dataArray['name']);
        $replaceArray['{{URI_PUBLIC}}'] = $uri_public_string_enc;
        $replaceArray['{{URI_PUBLIC_TEXT}}'] = 'Update Phone Number';
        //
        foreach ($replaceArray as $k0 => $v0)
            $body = str_replace($k0, $v0, $body);
        //
        $body = $hf['header'] . $body . $hf['footer'];
        //
        log_and_sendEmail(
            FROM_EMAIL_EVENTS,
            $dataArray['emailAddress'],
            'Update phone number - ' . (ucwords($dataArray['companyName'])) . '',
            $body,
            ucwords($dataArray['companyName'])
        );
        //
        return true;
    }
}


if (!function_exists('xml_template_indeed')) {
    function xml_template_indeed($jobs = false)
    {
        $date = date('D, d M Y h:i:s');
        $storeSSL = STORE_FULL_URL_SSL;
        $storeName = STORE_NAME;
        $XML = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<source>
    <publisher>{$storeName}</publisher>
    <publisherurl><![CDATA[{$storeSSL}]]></publisherurl>
    <lastBuildDate>{$date} PST</lastBuildDate>
    {{JOBS}}
</source>
XML;
        return $jobs ? str_replace('{{JOBS}}', $jobs, $XML) : $XML;
    }
}


if (!function_exists('xml_create_job')) {
    function xml_create_job(
        $uid,
        $companySid,
        $subDomain,
        $companyName,
        $jobTitleLocation,
        $formpost,
        $type,
        $_this
    ) {
        $countryCode = "US";
        $jobCategory = $jobType = $stateName = $city = $postalCode = $salary = "";
        $jobDesc = strip_tags($formpost['JobDescription'], '<br>');
        //
        if (isset($formpost['Location_Country']) && $formpost['Location_Country'] != NULL)
            $countryCode = db_get_country_name($formpost['Location_Country'], $_this, 'country_code');
        //
        if (isset($formpost['Location_State']) && $formpost['Location_State'] != NULL)
            $stateName = db_get_state_name($formpost['Location_State'], $_this, 'state_name');
        //
        if (isset($formpost['Location_City']) && $formpost['Location_City'] != NULL)
            $city = $formpost['Location_City'];
        //
        if (isset($formpost['Location_ZipCode']) && $formpost['Location_ZipCode'] != NULL)
            $postalCode = $formpost['Location_ZipCode'];
        //
        if (isset($formpost['Salary']) && $formpost['Salary'] != NULL)
            $salary = $formpost['Salary'];
        //
        if (isset($formpost['JobRequirements']) && $formpost['JobRequirements'] != NULL)
            $jobDesc = strip_tags($formpost['JobDescription'], '<br>') . '<br><br>Job Requirements:<br>' . strip_tags($formpost['JobRequirements'], '<br>');
        //
        if (isset($formpost['SalaryType']) && $formpost['SalaryType'] != NULL) {
            if ($formpost['SalaryType'] == 'per_hour')
                $jobType = "Per Hour";
            elseif ($formpost['SalaryType'] == 'per_week')
                $jobType = "Per Week";
            elseif ($formpost['SalaryType'] == 'per_month')
                $jobType = "Per Month";
            elseif ($formpost['SalaryType'] == 'per_year')
                $jobType = "Per Year";
        }
        //
        if ($formpost['JobCategory'] != null && $formpost['JobCategory'] != '')
            $jobCategory = $_this->job_listings_visibility_model->getJobCategoryNameById($formpost['JobCategory']);

        $title = $formpost['Title'];
        if ($jobTitleLocation == 1)
            $title = $title . '  - ' . ucfirst($city) . ', ' . $stateName . ', ' . $countryCode;

        $indeedURI = 'indeed-apply-joburl='
            . urlencode(STORE_PROTOCOL_SSL .
                $subDomain . '/job_details/' . $uid)
            . '&indeed-apply-jobid=' . $uid
            . '&indeed-apply-jobtitle=' . urlencode($title)
            . '&indeed-apply-jobcompanyname=' . urlencode($companyName)
            . '&indeed-apply-joblocation=' . urlencode($city . "," . $stateName . "," . $countryCode)
            . '&indeed-apply-apitoken=56010deedbac7ff45f152641f2a5ec8c819b17dea29f503a3ffa137ae3f71781&indeed-apply-posturl='
            . urlencode(STORE_FULL_URL_SSL . '/indeed_feed/indeedPostUrl')
            . '&indeed-apply-phone=required&indeed-apply-allow-apply-on-indeed=1';
        $date = date_with_time($formpost['publish_date']) . ' PST';
        $url = STORE_PROTOCOL_SSL . $subDomain . "/job_details/" . $uid;
        //
        $XML = "";
        $XML .= "<job>";
        $XML .= "   <title><![CDATA[$title]]></title>";
        $XML .= "   <date><![CDATA[$date]]></date>";
        $XML .= "   <url><![CDATA[$url]]></url>";
        $XML .= "   <city><![CDATA[$city]]></city>";
        $XML .= "   <state><![CDATA[$stateName]]></state>";
        $XML .= "   <country><![CDATA[$countryCode]]></country>";
        if ($salary == '')
            $XML .= "   <salary />";
        else
            $XML .= "   <salary><![CDATA[$salary]]></salary>";
        if ($jobType == '')
            $XML .= "   <jobtype />";
        else
            $XML .= "   <jobtype><![CDATA[$jobType]]></jobtype>";
        $XML .= "   <company><![CDATA[$companyName]]></company>";
        $XML .= "   <category><![CDATA[$jobCategory]]></category>";
        if ($postalCode == '')
            $XML .= "   <postalcode />";
        else
            $XML .= "   <postalcode><![CDATA[$postalCode]]></postalcode>";
        $XML .= "   <description><![CDATA[$jobDesc]]></description>";
        $XML .= "   <referencenumber><![CDATA[$uid]]></referencenumber>";
        if ($type == 'indeed')
            $XML .= "   <indeed-apply-data><![CDATA[$indeedURI]]></indeed-apply-data>";
        $XML .= "</job>";
        return trim($XML);
    }
}

/**
 * Onboarding side bar widget
 *
 */
if (!function_exists('onboardingHelpWidget')) {
    function onboardingHelpWidget($companySid)
    {
        //
        $_this = &get_instance();
        $_this->load->model('Onboarding_block_model');
        $record = $_this->Onboarding_block_model->check_companySid($companySid);
        // $record = $_this->Onboarding_block_model->check_companySid($_this->session->userdata('logged_in')['company_detail']['sid']);
        if (!sizeof($record) || $record['is_active'] == 0)
            return '';
        $rows = '';
        // $rows .= '<div class="col-lg-3 col-sm-12 col-xs-12 col-sm-pull-9">';

        // $rows .= '    <div class="widget-box js-sidebar-widget">';
        // $rows .= '        <ul class="quick-links border-gray" style="list-style: none;">';
        // $rows .= '            <li>';
        // $rows .= '                <h4 class="text-blue">'.($record['block_title']).'</h4>';
        // $rows .= '                <hr />';
        // $rows .= '            </li>';
        // $rows .= '            <li>';
        // $rows .= '                <div><a style="font-size: 13px;" href="tel:'.(preg_replace('/([^0-9])/', '', $record['phone_number'])).'"><i class="fa fa-phone"></i>&nbsp;'.($record['phone_number']).'</a></div>';
        // $rows .= '            </li>';
        // $rows .= '            <li>';
        // $rows .= '                <div><a style="font-size: 13px; text-transform: lowercase;" href="mailto:'.($record['email_address']).'?subject=Onboarding help needed"><i class="fa fa-envelope"></i>&nbsp;'.($record['email_address']).'</a></div>';
        // $rows .= '            </li>';
        // if($record['description'] != null && $record['description'] != ''){
        //     $rows .= '          <li>';
        //     $rows .= '               <hr />';
        //     $rows .= '               <p>'.($record['description']).'</p>';
        //     $rows .= '          </li>';
        // }
        // $rows .= '        </ul>';
        // $rows .= '    </div>';
        // $rows .= '</div>';
        // $rows .= '<style>
        //     ul.quick-links li a{ padding: 0; }
        //     ul.quick-links li a::before{ background: none; }
        // </style>';

        $rows .= '<div  class="">';
        $rows .= '    <h4 class="text-blue">' . ($record['block_title']) . '</h4>';
        $rows .= '    <p style="margin-bottom:2px !important;"><a style="font-size: 13px; color:#333131;" href="tel:' . (preg_replace('/([^0-9])/', '', $record['phone_number'])) . '"><i class="fa fa-phone"></i>&nbsp;' . ($record['phone_number']) . '</a></p>';
        $rows .= '    <p style="margin-bottom:2px !important;" ><a style="font-size: 13px; color:#333131;" href="mailto:' . ($record['email_address']) . '?subject=Onboarding help needed"><i class="fa fa-envelope"></i>&nbsp;' . ($record['email_address']) . '</a></p>';
        $rows .= '    <p style="margin-top: 8px; font-size: 13px;"><strong>' . ($record['description']) . '</strong></p>';
        $rows .= '</div>';

        $rows .= '<style>
           .cs-cus a{ padding: 0; }
           .cs-cus a::before{ background: none; }
        </style>';
        return $rows;
    }
}

if (!function_exists('deleteFolderWithFiles')) {
    function deleteFolderWithFiles($directory)
    {
        //It it's a file.
        if (is_file($directory)) {
            //Attempt to delete it.
            return unlink($directory);
        }
        //If it's a directory.
        elseif (is_dir($directory)) {
            //Get a list of the files in this directory.
            $scan = glob(rtrim($directory, '/') . '/*');
            //Loop through the list of files.
            foreach ($scan as $index => $path) {
                //Call our recursive function.
                deleteFolderWithFiles($path);
            }
            //Remove the directory itself.
            return @rmdir($directory);
        }
    }
}

if (!function_exists('getMimeType')) {
    function getMimeType($input)
    {
        $mime_types = array(
            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',
            'docx' => 'application/msword',
            'xlsx' => 'application/vnd.ms-excel',
            'pptx' => 'application/vnd.ms-powerpoint',


            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );
        //
        $tmp = explode('.', $input);
        $ext = strtolower($tmp[sizeof($tmp) - 1]);
        //
        $mimetype = '';
        if (function_exists('mime_content_type')) {
            $mimetype = @mime_content_type($input);
        }
        if (empty($mimetype) && function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = @finfo_file($finfo, $input);
            finfo_close($finfo);
        }
        if (empty($mimetype) && array_key_exists($ext, $mime_types)) {
            $mimetype = $mime_types[$ext];
        }
        if (empty($mimetype)) {
            $mimetype = 'application/octet-stream';
        }
        return $mimetype;
    }
}

if (!function_exists('downloadFileFromAWS')) {
    function downloadFileFromAWS($filePath, $url)
    {
        //This is the file where we save the    information
        $fp = fopen($filePath, 'w');
        //Here is the file we are downloading, replace spaces with %20
        $ch = curl_init(str_replace(" ", "%20", $url));
        curl_setopt($ch, CURLOPT_TIMEOUT, 70);
        // write curl response to file
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        // get curl response
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
    }
}

if (!function_exists('loadIndeedPackage')) {
    function loadIndeedPackage($indeedBudget, $session)
    {
        //
        if (sizeof($indeedBudget) && $indeedBudget['expire_date'] < date('Y-m-d'))
            $indeedBudget = array();

        $step1 = '';
        $step1 .= '<p>Sponsor this job on Indeed for prominent placement on the world’s #1 job site1.</p>';
        $step1 .= '<p>Indeed Sponsored Jobs get up to 5X more clicks and can help you hire quickly and build a strong candidate pipeline.</p>';
        $step1 .= '<p>Select a fixed budget per job for 30 days to get started:</p>';
        $step1 .= '<div class="checkbox-inline">';
        $step1 .= '    <label>';
        $step1 .= '        <input type="radio" name="indeedPackage" ' . (sizeof($indeedBudget) && (int) $indeedBudget['budget_days'] != 0 && (int) $indeedBudget['budget'] == 450 ? 'checked="checked"' : '') . ' class="js-indeed-package" data-per-day="15" value="450" />';
        $step1 .= '        <span>$450 (~$15 per day)</span>';
        $step1 .= '    </label>';
        $step1 .= '</div>';
        $step1 .= '<div class="checkbox-inline">';
        $step1 .= '    <label>';
        $step1 .= '        <input type="radio" name="indeedPackage" ' . (sizeof($indeedBudget) && (int) $indeedBudget['budget_days'] != 0 && (int) $indeedBudget['budget'] == 900 ? 'checked="checked"' : '') . ' class="js-indeed-package" data-per-day="30" value="900" />';
        $step1 .= '        <span>$900 (~$30 per day)</span>';
        $step1 .= '    </label>';
        $step1 .= '</div>';
        $step1 .= '<div class="checkbox-inline">';
        $step1 .= '    <label>';
        $step1 .= '        <input type="radio" name="indeedPackage" ' . (sizeof($indeedBudget) && (int) $indeedBudget['budget_days'] != 0 && (int) $indeedBudget['budget'] == 1500 ? 'checked="checked"' : '') . ' class="js-indeed-package" data-per-day="50" value="1500" />';
        $step1 .= '        <span>$1,500 (~$50 per day)</span>';
        $step1 .= '    </label>';
        $step1 .= '</div>';
        $step1 .= '<p><br />Or Enter Custom Budget: <input type="number" name="indeedPackageCustom" class="form-control js-custom-indeed-package" style="max-width: 200px; display: inline-block;" placeholder="e.g. 300 (min. 200)" value="' . (sizeof($indeedBudget) && (int) $indeedBudget['budget_days'] == 0 ? $indeedBudget['budget'] : '') . '"/></p>';
        $step1 .= '<p>You will only be billed for the unique clicks on your job, and if you remove the job from your ATS, the sponsored campaign will stop.</p>';
        $step1 .= '<p><a href="javascript:void(0)" class="btn btn-success btn-lg js-indeed-package-btn" id="js-indeed-next">Sponsor Job on Indeed now</a></p>';
        $step1 .= '<p>Note: Sponsored Job campaigns will not go live without an active Indeed account. If you do not have an Indeed account one will be created for you using the email address connected to your ATS. You’ll receive an account activation email from Indeed to finish a one time setup of your Indeed account at the address connected to your ATS account within a few hours. Please review that email to ensure Indeed has the information they need to start your first campaign.</p>';
        $step1 .= '<p>Once Indeed gets your Sponsored Job from your ATS, and your account is activated, your Sponsored Job campaign will be live within a few hours.</p>';
        $step1 .= '<p><a href="https://youtu.be/LcCCHeSVhqE" target="_blank">Watch this video</a> to learn more or <a href="https://www.indeed.com/hire/contact" target="_blank">contact Indeed</a> to discuss more options.</p>';
        $step1 .= '<p>By sponsoring this job and creating an account, you agree to Indeed’s Terms of Service and consent to our <a href="https://www.indeed.com/legal?hl=en_US#privacy" target="_blank">Privacy Policy</a> and <a href="https://www.indeed.com/legal?hl=en_US#cookies" target="_blank">Cookie Policy</a>.</p>';
        //
        $step2 = '';
        $step2 .= '<p>Thank you for sponsoring your job on Indeed.</p>';
        $step2 .= '<p><strong>Note:</strong> Sponsored Job campaigns will not go live without a payment method connected to your Indeed account. You will receive an email from Indeed within a few hours with instructions for setting this up for the first time. If you do not receive this email, please visit billing.indeed.com.</p>';
        $step2 .= '<p>Indeed is committed to the success of your advertising campaign. Please help us connect with you by sharing your phone number.</p>';
        $step2 .= '<p>Phone</p>';
        $step2 .= '<input name="indeedPhoneNumber" class="form-control js-indeed-package-phone-input" style="width: 300px; display: inline-block;" placeholder="(XXX)-XXX-XXXX" value="' . (sizeof($indeedBudget) ? $indeedBudget['phone_number'] : ($session['company_detail']['PhoneNumber'])) . '"/>&nbsp;&nbsp;<span style="font-weight: bold; color: #cc0000;">*</span></p>';
        $step2 .= '<p>A member of Indeed’s sales team will contact you to discuss your ad’s performance and offer suggestions for optimization. Please contact sjisupport@indeed.com with any questions or concerns.</p>';
        $step2 .= '<a class="btn btn-success btn-lg js-indeed-package-phone-btn">SUBMIT PHONE NUMBER</a> ';
        $step2 .= '<br /><br /><br /><p class="text-center" style="font-size: 12px !important;">*By filling out the phone number field, and submitting the form: I confirm that I am the subscriber to the telephone number entered and I hereby consent to receive autodialed marketing and informational calls from Indeed at the telephone number provided above, including if this number is a wireless (cell phone) number. Agreement to the above is not a condition of purchase of any Indeed product. I agree that this information will be dealt with in accordance with Indeed’s Cookie and Privacy Policy,</p>';
        $step2 .= '<a class="btn btn-default js-indeed-package-skip-btn">SKIP & CONTINUE</a>';
        //
        if (sizeof($indeedBudget)) {
            $step2 .= '<input type="hidden" class="js-indeed-budget-sid" value="' . ($indeedBudget['budget_sid']) . '" />';
        }
        $step4 = '';
        $step4 .= '<p class="text-center">This post is managed by Indeed.</p>';
        $step4 .= '<p class="text-center"><a style="color: #0000cc;"><b>Check the status</b>,</a> or</p>';
        $step4 .= '<p class="text-center"><a href="https://www.indeed.com/hire/contact" target="_blank" style="color: #0000cc;"><b>contact Indeed</b></a> for details</p>';
        $step4 .= '<p class="text-center"><button class="btn btn-info" id="js-end-compaign">End Compaign</button></p>';
        $step4 .= '<p class="text-center"><button class="btn btn-success" id="js-add-budget">Add Budget</button></p>';

        return array('Step1' => $step1, 'Step2' => $step2, 'Step4' => $step4);
    }
}

if (!function_exists('check_for_feature')) {
    function checkForNewModuleAccess($companySid, $feature, $stop = TRUE)
    {
        //
        if ($stop && !in_array($companySid, explode(',', DISALLOWEDCOMPANIES)))
            return false;
        if ($stop && !in_array(strtolower(trim($feature)), explode(',', DISALLOWEDMODULES)))
            return false;
        //
        return true;
    }
}

if (!function_exists('downloadFile')) {
    function downloadFile($filePath, $url)
    {
        //This is the file where we save the    information
        $fp = fopen($filePath, 'w+');
        //Here is the file we are downloading, replace spaces with %20
        $ch = curl_init(str_replace(" ", "%20", $url));
        curl_setopt($ch, CURLOPT_TIMEOUT, 50);
        // write curl response to file
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        // get curl response
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
    }
}

if (!function_exists('downloadFileFromDropbox')) {
    function downloadFileFromDropbox($url)
    {
        // Set path
        $path = APPPATH . '../assets/tmp/' . date('YmdHis') . '/';
        // Create path if not exists
        if (!file_exists($path))
            mkdir($path, 0777, true);
        // Get file extension
        $dataArray = explode('.', $url);
        $fileEXT = $dataArray[1] = str_replace('?dl=1', '', $dataArray[1]);
        resetResumeName($fileEXT);
        // Set filename
        $path .= date('YmdHis') . '.' . $fileEXT;
        // Download file to local
        downloadFile($path, $url);
        // Get downloaded file content
        $fileContent = file_get_contents($path);
        // Delete the local file
        unlink($path);
        //
        return $fileContent;
    }
}

/**
 * Convert non-allowed files to PDF
 * @param Reference|Array $in
 * @return VOID
 */
if (!function_exists('resetResumeName')) {
    function resetResumeName(&$in)
    {
        if (is_array($in))
            $ext = $in[1];
        else
            $ext = $in;
        $match = array();
        preg_match('/(pdf|docx|doc|jpg|png|gif|jpe)/', strtolower($ext), $match);
        if (!sizeof($match)) {
            if (is_array($in))
                $in[1] = 'pdf';
            else
                $in = 'pdf';
        }
    }
}

if (!function_exists('send_settings_to_remarket')) {
    function send_settings_to_remarket($url, $new_settings)
    {
        if (isDevServer()) {
            return;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($new_settings));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);
        //print_r($server_output);exit;
        curl_close($ch);
    }
}

if (!function_exists('calculateFormatMinutes')) {
    function calculateFormatMinutes($days = 0, $hours = 0, $minutes = 0, $slot = 9)
    {
        $totalMinutes = ($days * $slot * 60) + ($hours * 60) + $minutes;
        return $totalMinutes;
    }
}

/**
 * Get days for leap years
 *
 * @param $startYear String
 * 'YYYY'
 * @param $endYear String
 * 'YYYY'
 *
 * @return Integer
 */
if (!function_exists('getLeapYears')) {
    function getLeapYears($startYear, $endYear)
    {
        // Set default leap years count
        $leapYears = 0;
        // Create dates array
        $yearsToCheck = range($startYear, $endYear);
        // Loop through years
        foreach ($yearsToCheck as $year) {
            // Calculate leap years count
            $leapYears = $leapYears + (int) date('L', strtotime("$year-01-01"));
        }
        return $leapYears;
    }
}

if (!function_exists('get_array_from_minutes')) {
    function get_array_from_minutes($minutes, $defaultTimeFrame, $slug)
    {
        $returnArray = array();
        $returnArray['timeFrame'] = $defaultTimeFrame;
        $returnArray['originalMinutes'] = $minutes;
        $returnArray['D:H:M'] = array();
        //
        $returnArray['D:H:M']['days'] = (int) (($minutes) / ($defaultTimeFrame * 60));
        $returnArray['D:H:M']['hours'] = (int) ((($minutes) % ($defaultTimeFrame * 60)) / 60);
        $returnArray['D:H:M']['minutes'] = (int) ((($minutes) % ($defaultTimeFrame * 60)) % 60);

        $returnArray['H:M'] = array();
        $returnArray['H:M']['hours'] = (int) ($minutes / 60);
        $returnArray['H:M']['minutes'] = (int) ($minutes % 60);

        $returnArray['D'] = array();
        $returnArray['D']['days'] = number_Format($minutes / ($defaultTimeFrame * 60), 2);

        $returnArray['M'] = array();
        $returnArray['M']['minutes'] = $minutes;

        $returnArray['H'] = array();
        $returnArray['H']['hours'] = number_Format($minutes / 60, 2);

        $returnArray['active'] = $returnArray[$slug];
        $returnArray['text'] = '';

        if (isset($returnArray[$slug]['days']))
            $returnArray['text'] .= $returnArray[$slug]['days'] . ' day' . ($returnArray[$slug]['days'] > 1 || $returnArray[$slug]['days'] == 0 ? 's' : '') . ' & ';
        if (isset($returnArray[$slug]['hours']))
            $returnArray['text'] .= $returnArray[$slug]['hours'] . ' hour' . ($returnArray[$slug]['hours'] > 1 || $returnArray[$slug]['hours'] == 0 ? 's' : '') . ' & ';
        if (isset($returnArray[$slug]['minutes']))
            $returnArray['text'] .= $returnArray[$slug]['minutes'] . ' minute' . ($returnArray[$slug]['minutes'] > 1 || $returnArray[$slug]['minutes'] == 0 ? 's' : '') . ' & ';
        $returnArray['text'] = rtrim($returnArray['text'], '& ');

        return $returnArray;
    }
}

if (!function_exists('getTimeOffEmailTemplate')) {
    function getTimeOffEmailTemplate($in, $companyId, $companyName, $change = FALSE)
    {
        $CHF = message_header_footer($companyId, $companyName);
        $body = '<p>Dear <b>{{TO_FULLNAME}}</b>,</p>';
        if ($change) {
            $body .= '<p>{{EXP}} changed the Time Off details with the below details.</p>';
        } else {
            $body .= '<p>{{EXP}} requested Time Off with the following details.</p>';
        }
        $body .= '<p><strong>Policy:</strong> {{POLICY_NAME}}</p>';

        if ($in['single_day_leave'] == 0) {
            $body .= '<p><strong>Time Off Date:</strong> {{REQUESTED_DATE}}</p>';
            $body .= '<p><strong>Requested Time:</strong> {{REQUESTED_HOURS}}</p>';
        }

        $body .= '<p><strong>Request Details:</strong> {{REQUEST_DETAILS}}</p>';
        // $body .= '<p><strong>Partial Leave:</strong> {{PARTIAL_LEAVE}} </p>';
        // $body .= '<p><strong>Partial Leave Note:</strong> {{PARTIAL_LEAVE_NOTE}} </p>';
        $body .= '<p><strong>Time Off Reason:</strong> {{REASON}} </p>';
        $body .= '<p><strong>Status:</strong> {{TIMEOFF_STATUS}}</p>';
        $body .= '<br />';
        $body .= '{{CANCEL_BUTTON}}';
        $body .= '{{MAIN_BUTTONS}}';
        // Check if cancel
        $cancelURIToken = $in['type'] == 'requester' ? $in['cancelBtnToken'] : '';
        $cancelURI = base_url('timeoff/action') . '/' . $cancelURIToken;
        $cancelBN = '<p>If you want to cancel this Time Off click on the below button.</p><br />';
        $cancelBN .= '<a href="' . ($cancelURI) . '" style="background: #cc0000; color: #ffffff; padding: 10px; border-radius: 3px;">Cancel Time Off</a>';
        // Approve, Reject
        $mainBtn = '<p>Please, use the below buttons to either approve ore reject the Time Off.</p><br />';
        $approveURIToken = $in['type'] != 'requester' ? $in['approveBtnToken'] : '';
        $approveURI = base_url('timeoff/action') . '/' . $approveURIToken;
        $approveBtn = '<a href="' . ($approveURI) . '" style="background: #81b431; color: #ffffff; padding: 10px; margin: 10px; border-radius: 3px;">Approve Time Off</a>';
        //
        $rejectURIToken = $in['type'] != 'requester' ? $in['rejectBtnToken'] : '';
        $rejectURI = base_url('timeoff/action') . '/' . $rejectURIToken;
        $rejectBtn = '<a href="' . ($rejectURI) . '" style="background: #cc0000; color: #ffffff; padding: 10px; margin: 10px; border-radius: 3px;">Reject Time Off</a>';
        $mainBTN = $approveBtn . $rejectBtn;

        $viewURIToken = $in['type'] != 'requester' ? $in['viewBtnToken'] : '';
        $viewtURI = base_url('timeoff/action') . '/' . $viewURIToken;
        $viewBtn = '<a href="' . ($viewtURI) . '" style="background: #0000ff; color: #ffffff; padding: 10px; margin: 10px; border-radius: 3px;">View Time Off</a>';
        $mainBTN = $mainBTN . $viewBtn;
        // Logic goes here
        $i = array(
            '{{TO_FULLNAME}}',
            '{{EXP}}',
            '{{POLICY_NAME}}',
            '{{REQUESTED_DATE}}',
            '{{REQUESTED_HOURS}}',
            '{{REQUEST_DETAILS}}',
            // '{{PARTIAL_LEAVE}}',
            // '{{PARTIAL_LEAVE_NOTE}}',
            '{{REASON}}',
            '{{TIMEOFF_STATUS}}',
            '{{CANCEL_BUTTON}}',
            '{{MAIN_BUTTONS}}'
        );
        $v = array();
        $v[] = $in['to_full_name'];
        $v[] = $in['exp'];
        $v[] = $in['policy_name'];
        $v[] = $in['requested_date'];
        $v[] = $in['requested_time'];
        $v[] = $in['request_details'];
        // $v[] = $in['partial_leave'];
        // $v[] = $in['partial_leave_note'] == '' ? 'N/A' : $in['partial_leave_note'];
        $v[] = $in['reason'] == '' ? 'N/A' : $in['reason'];
        $v[] = $in['request_status'];
        $v[] = $in['type'] == 'requester' ? $cancelBN : '';
        $v[] = $in['type'] != 'requester' ? $mainBTN : '';
        //
        return $CHF['header'] . str_replace($i, $v, $body) . $CHF['footer'];
    }
}

if (!function_exists('paramsToArray')) {

    function paramsToArray($p)
    {
        $p = explode('&', $p);
        if (!sizeof($p))
            return $p;
        //
        $r = array();
        foreach ($p as $k => $v) {
            $t = explode('=', $v);
            $r[trim($t[0])] = trim($t[1]);
        }
        return $r;
    }
}

if (!function_exists('getFileExtension')) {
    function getFileExtension($in)
    {
        //
        $tmp = explode('.', $in);
        return strtolower($tmp[sizeof($tmp) - 1]);
    }
}

if (!function_exists('checkIfAppIsEnabled')) {
    function checkIfAppIsEnabled(
        $ctl = FALSE,
        $doRedirect = FALSE,
        $forceGet = FALSE
    ) {
        // Temporaty
        // if(getUserIP() == '72.255.38.246') return true;
        $devIds = array(57);
        $stagingIds = array(57);
        // Get the instance of CI object
        $ci = &get_instance();
        // Get session
        $ses = $ci->session->userdata('logged_in');
        // 
        // Check if use is logged in
        if (!$ses || !sizeof($ses) || !isset($ses['company_detail']))
            return true;
        // Get the called controller name
        $ctl = trim(strtolower(preg_replace('/[^a-zA-Z]/', '', $ctl ? $ctl : $ci->router->fetch_class())));
        // If not a controller then pass
        if ($ctl == '')
            return;
        // Check session for value
        if (!$forceGet && $ci->session->userdata('moduleSES') && isset($ci->session->userdata('moduleSES')['hybrid_document'])) {
            //
            $b = $ci->session->get('moduleSES')['hybrid_document'];
            //
            if ($b['stage'] != 'production')
                return true;
            //
            if ($b['isAllowed'])
                return true;
            //
            if ($doRedirect == TRUE) {
                $ci->session->set_flashdata('message', 'You don\'t have access to this module.');
                redirect('my_settings', 'refresh');
            } else
                return false;
        } else {
            //
            if (!$ci->session->userdata('moduleSES'))
                $ci->session->set_userdata('moduleSES', array());
            // Let's check if controller exists in module
            $a = $ci
                ->db
                ->select('sid, stage, is_disabled, is_ems_module')
                ->where('module_slug', $ctl)
                ->limit(1)
                ->get('modules');
            //
            $b = $a->row_array();
            $a->free_result();
            // If module doesn't exists then no need to continue
            if (!sizeof($b))
                return true;
            //
            $b['isAllowed'] = 0;
            //
            $s = $ci->session->userdata('moduleSES');
            $s[$ctl] = $b;
            //
            $ci->session->set_userdata('moduleSES', $s);
            //
            if ($ses['company_detail']['ems_status'] != 1 && $b['is_ems_module'] == 1) {
                if ($doRedirect == TRUE) {
                    $ci->session->set_flashdata('message', 'You don\'t have access to this module.');
                    redirect('my_settings', 'refresh');
                } else
                    return false;
            }
            // Make sure module is only available on Development
            if ($b['stage'] == 'development' && !in_array($ses['company_detail']['sid'], $devIds)) {
                if ($doRedirect == TRUE) {
                    $ci->session->set_flashdata('message', 'You don\'t have access to this module.');
                    redirect('my_settings', 'refresh');
                } else
                    return false;
            }
            // Make sure module is only available on Staging
            else if ($b['stage'] == 'staging' && !in_array($ses['company_detail']['sid'], $stagingIds)) {
                if ($doRedirect == TRUE) {
                    $ci->session->set_flashdata('message', 'You don\'t have access to this module.');
                    redirect('my_settings', 'refresh');
                } else
                    return false;
            }
            // Make sure module is only available on Production
            else if ($b['stage'] == 'production' && !in_array($ses['company_detail']['sid'], $stagingIds)) {
                // Lets check if module is activated against logged in company
                $a = $ci
                    ->db
                    ->where('module_sid', $b['sid'])
                    ->where('company_sid', $ses['company_detail']['sid'])
                    ->where('is_active', 1)
                    ->get('company_modules');
                //
                $b = $a->row_array();
                $a->free_result();
                //
                if (!sizeof($b)) {
                    if ($doRedirect == TRUE) {
                        $ci->session->set_flashdata('message', 'You don\'t have access to this module.');
                        redirect('my_settings', 'refresh');
                    } else
                        return false;
                }
                //
                $s[$ctl]['isAllowed'] = 1;
                $ci->session->set_userdata('moduleSES', $s);
            }
        }

        return true;
    }
}

if (!function_exists('yearsToMonth')) {
    function yearsToMonths($years, $months = 0)
    {
        return ($years * 12) + $months;
    }
}

if (!function_exists('remakeAccessLevel')) {
    function remakeAccessLevel($obj)
    {
        if (isset($obj['is_executive_admin']) && $obj['is_executive_admin'] != 0) {
            $obj['access_level'] = 'Executive ' . $obj['access_level'];
        }
        if ($obj['access_level_plus'] == 1 && $obj['pay_plan_flag'] == 1)
            return $obj['access_level'] . ' Plus / Payroll';
        if ($obj['access_level_plus'] == 1)
            return $obj['access_level'] . ' Plus';
        if ($obj['pay_plan_flag'] == 1)
            return $obj['access_level'] . ' Payroll';
        return $obj['access_level'];
    }
}

if (!function_exists('getFileData')) {
    function getFileData($url)
    {
        //make a curl call to fetch content
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $data = curl_exec($ch);
        curl_close($ch);
        //
        return $data;
    }
}

if (!function_exists('remakeEmployeeName')) {
    function remakeEmployeeName($o, $i = TRUE, $onlyName = false)
    {
        //
        $first_name = isset($o['first_name']) ? $o['first_name'] : (isset($o['to_first_name']) ? $o['to_first_name'] : '');
        $middleName = isset($o['middle_name']) ? ' ' . $o['middle_name'] : (isset($o['to_middle_name']) ? ' ' . $o['to_middle_name'] : '');
        $last_name = isset($o['last_name']) ? $o['last_name'] : (isset($o['to_last_name']) ? $o['to_last_name'] : '');
        //
        $r = $i ? $first_name . $middleName . ' ' . $last_name : '';
        //
        if ($onlyName) {
            return $r;
        }
        //
        if (isset($o['job_title']) && $o['job_title'] != '' && $o['job_title'] != null)
            $r .= ' (' . ($o['job_title']) . ')';
        //
        $r .= ' [' . remakeAccessLevel($o) . ']';
        //
        if (isset($o['timezone'])) {
            //
            $tz = !empty($o['timezone'])
                ? $o['timezone']
                : STORE_DEFAULT_TIMEZONE_ABBR;
            //
            $r .= ' (' . ($tz) . ')';
        }
        //
        return $r;
    }
}

if (!function_exists('getEmployerAssignJobs')) {
    function getEmployerAssignJobs($employer_sid, $user_sid)
    {
        $CI = &get_instance();
        $CI->db->select('job_sid');
        $CI->db->where('employer_sid', $employer_sid);

        $record_obj = $CI->db->get('portal_job_listings_visibility');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        // For job visibility
        if (!empty($record_arr)) {
            //
            $CI->db->select('job_sid');
            $CI->db->where('portal_job_applications_sid', $user_sid);
            $CI->db->where_in('job_sid', array_column($record_arr, 'job_sid'));
            //
            if ($CI->db->count_all_results('portal_applicant_jobs_list')) {
                return true;
            }
        }

        // For candidate
        $CI->db->select('sid');
        $CI->db->where('employer_sid', $employer_sid);
        $CI->db->where('applicant_sid', $user_sid);
        $CI->db->where('status', 'assigned');
        //
        if ($CI->db->count_all_results('assignment_management')) {
            return true;
        }

        return false;
    }
}

//
if (!function_exists('getTimeOffCompaniesForyearly')) {
    function getTimeOffCompaniesForyearly(
        $id = null
    ) {
        //
        $d = defined('TIMEOFFYEARLYCOMPANY') ? explode(',', TIMEOFFYEARLYCOMPANY) : [];
        //
        if ($id == null)
            return $d;
        //
        return in_array($id, $d);
    }
}
//
if (!function_exists('putFileOnAWSBase64')) {
    function putFileOnAWSBase64(
        $fileName
    ) {
        //
        if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == 'automotohr.local')
            return getS3DummyFileName($fileName, false);
        //
        $CI = &get_instance();
        // Set local path
        $path = APPPATH . '../assets/tmp/';
        //
        if (!is_dir($path))
            mkdir($path, 0777, true);
        $path .= $fileName;
        //
        downloadFileFromAWS($path, AWS_S3_BUCKET_URL . $fileName);
        //
        $CI->load->library('aws_lib');
        //
        $t = explode('.', $fileName);
        //
        $type = $t[sizeof($t) - 1];
        //
        unset($t[sizeof($t) - 1]);
        //
        $t = implode('_', $t);
        $t .= '_' . time() . '.' . $type;
        //
        $new_file_name = $t;
        //
        $options = [
            'Bucket' => AWS_S3_BUCKET_NAME,
            'Key' => $new_file_name,
            'Body' => file_get_contents($path),
            'ACL' => 'public-read',
            'ContentType' => getMimeType($new_file_name)
        ];
        //
        $CI->aws_lib->put_object($options);
        //
        unlink($path);
        //
        return $new_file_name;
    }
}

if (!function_exists('stringToSlug')) {
    function stringToSlug($i, $to = '-')
    {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', $to, $i)));
    }
}

if (!function_exists('SlugToString')) {
    function SlugToString($i)
    {
        return ucwords(trim(preg_replace('/_/', ' ', $i)));
    }
}

// Check if the logged in user
// has PP flag or ALP flag 'on'
if (!function_exists('getSSV')) {
    function getSSV($d)
    {
        //
        // $d['access_level_plus'] = 0;
        // $d['pay_plan_flag'] = 0;
        //
        if ($d['access_level_plus'] == 1 || $d['pay_plan_flag'] == 1)
            return false;
        return true;
    }
}

// Convert string to XXX
if (!function_exists('ssvReplace')) {
    function ssvReplace($str, $isDob = false)
    {
        //
        if ($isDob) {
            return substr($str, 0, -4) . XSYM . XSYM . XSYM . XSYM;
        }
        return preg_replace('/[a-zA-Z0-9]/', XSYM, $str);
    }
}



//
if (!function_exists('isDocumentCompleted')) {
    function isDocumentCompleted(&$documents)
    {
        foreach ($documents as $k0 => $document) {
            //
            if ($document['status'] != 1) {
                unset($documents[$k0]);
                continue;
            }
            // Column to check
            $is_magic_tag_exist = preg_match('/{{(.*?)}}/', $document['document_description']) ? true : false;
            //
            if (!$is_magic_tag_exist)
                $is_magic_tag_exist = preg_match('/<select(.*?)>/', $document['document_description']);
            //
            $is_document_completed = 0;
            //
            $is_magic_tag_exist = 0;
            //
            if (str_replace(EFFECT_MAGIC_CODE_LIST, '', $document['document_description']) != $document['document_description']) {
                $is_magic_tag_exist = 1;
            }
            // Check for uploaded manual dcoument
            if ($document['document_sid'] == 0) {
                continue;
            } else {
                //
                if ($document['acknowledgment_required'] || $document['download_required'] || $document['signature_required'] || $is_magic_tag_exist) {

                    if ($document['acknowledgment_required'] == 1 && $document['download_required'] == 1 && $document['signature_required'] == 1) {
                        if ($document['uploaded'] == 1) {
                            $is_document_completed = 1;
                        } else {
                            $is_document_completed = 0;
                        }
                    } else if ($document['acknowledgment_required'] == 1 && $document['download_required'] == 1) {
                        if ($is_magic_tag_exist == 1) {
                            if ($document['uploaded'] == 1) {
                                $is_document_completed = 1;
                            } else {
                                $is_document_completed = 0;
                            }
                        } else if ($document['uploaded'] == 1) {
                            $is_document_completed = 1;
                        } else if ($document['acknowledged'] == 1 && $document['downloaded'] == 1) {
                            $is_document_completed = 1;
                        } else {
                            $is_document_completed = 0;
                        }
                    } else if ($document['acknowledgment_required'] == 1 && $document['signature_required'] == 1) {
                        if ($document['uploaded'] == 1) {
                            $is_document_completed = 1;
                        } else {
                            $is_document_completed = 0;
                        }
                    } else if ($document['download_required'] == 1 && $document['signature_required'] == 1) {
                        if ($document['uploaded'] == 1) {
                            $is_document_completed = 1;
                        } else {
                            $is_document_completed = 0;
                        }
                    } else if ($document['acknowledgment_required'] == 1) {
                        if ($document['acknowledged'] == 1) {
                            $is_document_completed = 1;
                        } else if ($document['uploaded'] == 1) {
                            $is_document_completed = 1;
                        } else {
                            $is_document_completed = 0;
                        }
                    } else if ($document['download_required'] == 1) {
                        if ($document['downloaded'] == 1) {
                            $is_document_completed = 1;
                        } else if ($document['uploaded'] == 1) {
                            $is_document_completed = 1;
                        } else {
                            $is_document_completed = 0;
                        }
                    } else if ($document['signature_required'] == 1) {
                        if ($document['uploaded'] == 1) {
                            $is_document_completed = 1;
                        } else {
                            $is_document_completed = 0;
                        }
                    } else if ($is_magic_tag_exist == 1) {
                        if ($document['user_consent'] == 1) {
                            $is_document_completed = 1;
                        } else {
                            $is_document_completed = 0;
                        }
                    }

                    if ($is_document_completed == 0) {
                        unset($documents[$k0]);
                        continue;
                    }
                }
            }
        }
        //
        $documents = array_values($documents);
    }
}

if (!function_exists('loadTCPDF')) {
    function loadTCPDF()
    {
        // always load alternative config file for examples
        require_once(ROOTPATH . 'vendor/tcpdf/config/tcpdf_config.php');

        // Include the main TCPDF library (search the library on the following directories).
        $tcpdf_include_dirs = array(
            APPPATH . '../vendor/tcpdf/tcpdf.php'
        );
        foreach ($tcpdf_include_dirs as $tcpdf_include_path) {
            if (@file_exists($tcpdf_include_path)) {
                require_once($tcpdf_include_path);
                break;
            }
        }
    }
}


if (!function_exists('getFileName')) {
    function getFileName(
        $o,
        $u,
        $a = ''
    ) {
        //
        $t = explode('.', $u);
        $ue = '.' . $t[count($t) - 1];
        $ue = $a == '' ? $ue : $a . $ue;
        //
        if (strpos($o, '.') === false)
            return $o . $ue;
        return $o;
    }
}


//
if (!function_exists('getSendDocumentEmailButton')) {
    function getSendDocumentEmailButton($assignedDocument, $employee, $userType)
    {
        if ($userType == 'employee')
            return '';
        //
        $content = 'Send <strong>' . ($assignedDocument['document_title']) . '</strong> to <strong>' . ($employee['first_name'] . ' ' . $employee['last_name']) . '</strong> by email';
        $content = 'Send document by email to complete without going through OnBoarding process.';
        //
        $sendBTN = '<button class="btn btn-success btn-sm btn-block js-send-document" data-id="' . ($assignedDocument['sid']) . '" data-placement="left"  title="Send Document By Email" data-content="' . ($content) . '">Send Document</button>';
        return $sendBTN;
    }
}


//
if (!function_exists('replace')) {
    function replace($a, &$t)
    {
        $t = str_replace(
            array_keys($a),
            $a,
            $t
        );
    }
}


//
if (!function_exists('replaceDocumentContentTags')) {
    function replaceDocumentContentTags(&$content, $companySid, $userSid, $userType, $documentSid, $forDownload = false)
    {
        $document_content = replace_tags_for_document($companySid, $userSid, $userType, $content, $documentSid, 0, false, $forDownload);

        $value = '<div class="div-editable fillable_input_field input-grey" id="div_editable_text" contenteditable="true" data-placeholder="Type Here"></div>';
        $document_content = str_replace('[Target User Input Field]', $value, $document_content);

        $value = '<br><input type="checkbox" class="user_checkbox input-grey"/>';
        $document_content = str_replace('[Target User Checkbox]', $value, $document_content);

        //E_signature process
        $signature_bas64_image = '<a class="btn blue-button btn-sm get_signature" href="javascript:;">Create E-Signature</a><img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src=""  id="draw_upload_img" />';
        $init_signature_bas64_image = '<a class="btn blue-button btn-sm get_signature_initial" href="javascript:;">Signature Initial</a><img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src=""  id="target_signature_init" />';
        $signature_timestamp = '<a class="btn blue-button btn-sm get_signature_date" href="javascript:;">Sign Date</a><p id="target_signature_timestamp"></p>';

        $value = ' ';
        $document_content = str_replace($signature_bas64_image, $value, $document_content);
        $document_content = str_replace($init_signature_bas64_image, $value, $document_content);
        $document_content = str_replace($signature_timestamp, $value, $document_content);

        $content = $document_content;
    }
}


//
if (!function_exists('getFilePathForIframe')) {
    function getFilePathForIframe($document, $withIframe = true, $props = [])
    {
        //
        $dn = isset($document['document_s3_name']) ? $document['document_s3_name'] : $document;
        //
        if (empty($dn))
            return '';
        //
        if (!$withIframe)
            return AWS_S3_BUCKET_URL . $dn;
        //
        $iframe = '<iframe src="{{iframe_url}}" class="js-document-loader-iframe frameborder="0" width="100%" height="600" ></iframe>';
        //
        $t = explode('.', $dn);
        //
        $ext = $t[count($t) - 1];
        //
        if (in_array($ext, ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx']))
            $nn = 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode(AWS_S3_BUCKET_URL . $dn);
        else if (in_array($ext, ['jpe', 'jpg', 'jpeg', 'png', 'bmp', 'gif', 'svg']))
            return '<img class="img-responsive" src="' . (AWS_S3_BUCKET_URL . $dn) . '" ' . (implode(',', $props)) . '/>';
        else
            $nn = 'https://docs.google.com/gview?url=' . (AWS_S3_BUCKET_URL . $dn) . '&embedded=true';
        //
        replace(['{{iframe_url}}' => $nn], $iframe);
        return $iframe;
    }
}


//
if (!function_exists('getInstructions')) {
    function getInstructions($document)
    {
        if (strip_tags($document['document_description']) == '')
            return '';
        //
        return ' 
        <div class="panel panel-primary" style="margin-top: 10px;">
            <div class="panel-heading stev_blue">
                <strong>Instructions</strong>
            </div>
            <div class="panel-body">' . (html_entity_decode($document['document_description'])) . '</div>
        </div>';
    }
}

if (!function_exists('getReviewType')) {
    function getReviewType($code, $type = 'id')
    {
        $a = [
            '1' => 'entire_company',
            '2' => 'sub_cordinates',
            '3' => 'specific_employees',
            '4' => 'custom'
        ];
        //
        if ($type == 'text')
            return $a[$code];
        //
        $code = preg_replace('/[^a-zA-Z]/', '_', strtolower($code));
        //
        if ($code === 'specific_people')
            $code = 'specific_employees';
        //
        foreach ($a as $k => $v)
            if ($v == $code)
                return $k;
        return 1;
    }
}

if (!function_exists('getQuestion')) {
    function getQuestion($question, &$a)
    {
        //
        $row = '';
        $rating = [];
        $a[$question['sid']] = [];
        $a[$question['sid']]['sid'] = 0;
        $a[$question['sid']]['edit'] = 0;
        //
        if (count($question['ratingLabels'])) {
            $rating = array_column($question['ratingLabels'], 'text');
        }
        //
        if ($question['type'] != 'text') {
            $a[$question['sid']]['rating'] = '';
            for ($i = 1; $i <= $question['ratingScale']; $i++) {
                $class = '';
                if (($question['type'] == 'rating' || $question['type'] == 'text-rating') && !$question['useLabels']) {
                    $class = 'cs-rating-box';
                } else {
                    $class = 'cs-rating-text-box';
                }
                //
                $row .= '<div data-pid="' . ($question['sid']) . '" class="col-sm-2 col-xs-2 cs-rating-btn ' . ($class) . ' ' . hasAnswer($question, 'rating', $i, $a) . '">';
                $row .= '<p>' . ($i) . '</p>';
                if ($question['type'] == 'text-rating' && count($rating)) {
                    $row .= '<p>' . ($rating[$i - 1]) . '</p>';
                }
                $row .= '</div>';
            }
        }
        //
        if ($question['type'] == 'text' || $question['type'] == 'text-rating') {
            $a[$question['sid']]['text'] = '';
            if ($question['includeNA'] == 1) {
                $a[$question['sid']]['text'] = '-1';
            }
            $row .= '<textarea type="text" data-pid="' . ($question['sid']) . '" class="form-control cs-text-answer" rows="5" style="background-color: #eee;' . ($question['type'] == 'text-rating' ? "margin-top: 100px" : "") . '" placeholder="Write the answer here...."  ' . ($question['includeNA'] == 1 ? 'disabled="true"' : '') . '>' . hasAnswer($question, 'text', 0, $a) . '</textarea>';
        }
        //
        return $row;
    }
}

//
if (!function_exists('cleanDocumentsByPermission')) {
    function cleanDocumentsByPermission(
        &$data,
        $employerDetails,
        $withCategories = false,
        $dt = []
    ) {
        //
        if (!count($data))
            return;
        //
        $role = preg_replace('/\s+/', '_', strtolower($employerDetails['access_level']));
        //
        if ($withCategories) {
            //
            if (isset($data['categories_no_action_documents'])) {
                foreach ($data['categories_no_action_documents'] as $k0 => $v1) {
                    //
                    if (!isset($v1['documents']) || !is_array($v1['documents']) || !count($v1['documents']))
                        continue;
                    //
                    foreach ($v1['documents'] as $k2 => $document) {
                        if (
                            !hasPermissionToDocument(
                                $document['allowed_employees'],
                                $document['allowed_departments'],
                                $document['allowed_teams'],
                                $document['is_available_for_na'],
                                $document['is_confidential'],
                                $document['confidential_employees'],
                                $employerDetails['access_level_plus'],
                                $employerDetails['pay_plan_flag'],
                                $role,
                                $dt['Departments'],
                                $dt['Teams'],
                                $employerDetails['sid']
                            )
                        ) {
                            //
                            unset($data['categories_no_action_documents'][$k0]['documents'][$k2]);
                        }
                    }
                    //
                    $data['categories_no_action_documents'][$k0]['documents'] = array_values($data['categories_no_action_documents'][$k0]['documents']);
                }
            }

            //
            else if (isset($data['categories_documents_completed'])) {
                foreach ($data['categories_documents_completed'] as $k0 => $v1) {
                    //
                    if (!isset($v1['documents']) || !is_array($v1['documents']) || !count($v1['documents']))
                        continue;
                    //
                    foreach ($v1['documents'] as $k2 => $document) {
                        if (
                            !hasPermissionToDocument(
                                $document['allowed_employees'],
                                $document['allowed_departments'],
                                $document['allowed_teams'],
                                $document['is_available_for_na'],
                                $document['is_confidential'],
                                $document['confidential_employees'],
                                $employerDetails['access_level_plus'],
                                $employerDetails['pay_plan_flag'],
                                $role,
                                $dt['Departments'],
                                $dt['Teams'],
                                $employerDetails['sid']
                            )
                        ) {
                            //
                            unset($v1['documents'][$k2]);
                        }
                    }
                    //
                    $data['categories_documents_completed'][$k0] = array_values($v1['documents']);
                }
            }

            //
            else if (isset($data['no_action_document_categories'])) {
                foreach ($data['no_action_document_categories'] as $k0 => $v1) {
                    //
                    if (!isset($v1['documents']) || !is_array($v1['documents']) || !count($v1['documents']))
                        continue;
                    //
                    foreach ($v1['documents'] as $k2 => $document) {
                        if (
                            !hasPermissionToDocument(
                                $document['allowed_employees'],
                                $document['allowed_departments'],
                                $document['allowed_teams'],
                                $document['is_available_for_na'],
                                $document['is_confidential'],
                                $document['confidential_employees'],
                                $employerDetails['access_level_plus'],
                                $employerDetails['pay_plan_flag'],
                                $role,
                                $dt['Departments'],
                                $dt['Teams'],
                                $employerDetails['sid']
                            )
                        ) {
                            //
                            unset($v1['documents'][$k2]);
                        }
                    }
                    //
                    $data['no_action_document_categories'][$k0] = array_values($v1['documents']);
                }
            }

            //
            else if (isset($data['completed_documents'])) {
                foreach ($data['completed_documents'] as $k0 => $documents) {
                    //
                    foreach ($documents as $k2 => $document) {
                        if (
                            !hasPermissionToDocument(
                                $document['allowed_employees'],
                                $document['allowed_departments'],
                                $document['allowed_teams'],
                                $document['is_available_for_na'],
                                $document['is_confidential'],
                                $document['confidential_employees'],
                                $employerDetails['access_level_plus'],
                                $employerDetails['pay_plan_flag'],
                                $role,
                                $dt['Departments'],
                                $dt['Teams'],
                                $employerDetails['sid']
                            )
                        ) {
                            unset($documents[$k2]);
                        }
                    }
                    //
                    $data['completed_documents'] = array_values($documents);
                }
            }

            //
            else if (isset($data['no_action_documents'])) {
                foreach ($data['no_action_documents'] as $k0 => $documents) {
                    //
                    foreach ($documents as $k2 => $document) {
                        if (
                            !hasPermissionToDocument(
                                $document['allowed_employees'],
                                $document['allowed_departments'],
                                $document['allowed_teams'],
                                $document['is_available_for_na'],
                                $document['is_confidential'],
                                $document['confidential_employees'],
                                $employerDetails['access_level_plus'],
                                $employerDetails['pay_plan_flag'],
                                $role,
                                $dt['Departments'],
                                $dt['Teams'],
                                $employerDetails['sid']
                            )
                        ) {
                            unset($documents[$k2]);
                        }
                    }
                    //
                    $data['no_action_documents'] = array_values($documents);
                }
            }

            //
            else if (isset($data[0]['name'])) {
                foreach ($data as $k0 => $documents) {
                    //
                    foreach ($documents['documents'] as $k2 => $document) {
                        if (
                            !hasPermissionToDocument(
                                $document['allowed_employees'],
                                $document['allowed_departments'],
                                $document['allowed_teams'],
                                $document['is_available_for_na'],
                                $document['is_confidential'],
                                $document['confidential_employees'],
                                $employerDetails['access_level_plus'],
                                $employerDetails['pay_plan_flag'],
                                $role,
                                $dt['Departments'],
                                $dt['Teams'],
                                $employerDetails['sid']
                            )
                        ) {
                            $documents['documents_count']--;
                            unset($documents['documents'][$k2]);
                        }
                    }
                    //
                    $data[$k0]['documents_count'] = $documents['documents_count'];
                    $data[$k0]['documents'] = array_values($documents['documents']);
                }
            }
        } else {
            //
            // _e($data,true,true);
            foreach ($data as $k0 => $documents) {
                //
                if (!is_array($documents) || !isset($documents[0]) || !isset($documents[0]['document_title']))
                    continue;
                //
                foreach ($documents as $k1 => $document) {
                    if (
                        !hasPermissionToDocument(
                            $document['allowed_employees'],
                            $document['allowed_departments'],
                            $document['allowed_teams'],
                            $document['is_available_for_na'],
                            $document['is_confidential'],
                            $document['confidential_employees'],
                            $employerDetails['access_level_plus'],
                            $employerDetails['pay_plan_flag'],
                            $role,
                            $dt['Departments'],
                            $dt['Teams'],
                            $employerDetails['sid']
                        )
                    ) {
                        unset($documents[$k1]);
                    }
                }
                //
                $data[$k0] = array_values($documents);
            }
        }
    }
}

//
if (!function_exists('cleanAssignedDocumentsByPermission')) {
    function cleanAssignedDocumentsByPermission(
        $documents,
        $employerDetails,
        $dt = []
    ) {
        //
        if (!count($documents))
            return $documents;
        //
        $role = preg_replace('/\s+/', '_', strtolower($employerDetails['access_level']));
        //
        foreach ($documents as $k0 => $document) {
            if (
                !hasPermissionToDocument(
                    $document['allowed_employees'],
                    $document['allowed_departments'],
                    $document['allowed_teams'],
                    $document['is_available_for_na'],
                    $document['is_confidential'],
                    $document['confidential_employees'],
                    $employerDetails['access_level_plus'],
                    $employerDetails['pay_plan_flag'],
                    $role,
                    $dt['Departments'],
                    $dt['Teams'],
                    $employerDetails['sid']
                )
            ) {
                //
                unset($documents[$k0]);
            }
        }
        //
        $documents = array_values($documents);
        //
        return $documents;
    }
}


if (!function_exists('hasAnswer')) {
    function hasAnswer($question, $type, $index = 0, &$a)
    {
        //
        if (!isset($question['answer']) || !count($question['answer']))
            return '';
        //
        if ($type == 'rating' && $index == $question['answer']['rating_answer']) {
            $a[$question['sid']]['rating'] = $question['answer']['rating_answer'];
            $a[$question['sid']]['edit'] = 1;
            $a[$question['sid']]['sid'] = $question['answer']['sid'];
            return "active";
        }
        //
        if ($type == 'text') {
            $a[$question['sid']]['text'] = $question['answer']['text_answer'];
            $a[$question['sid']]['edit'] = 1;
            $a[$question['sid']]['sid'] = $question['answer']['sid'];
            return $question['answer']['text_answer'] == '-1' ? '' : $question['answer']['text_answer'];
        }
    }
}


//
if (!function_exists('getReviewName')) {
    function getReviewName($a, $id, $t = 'reviewer')
    {
        foreach ($a as $v) {
            switch ($t) {
                case 'reviewer':
                    if ($v['conductor_sid'] == $id)
                        return remakeEmployeeName($v);
                    break;
                default:
                    if ($v['employee_sid'] == $id)
                        return remakeEmployeeName([
                            'first_name' => $v['efirst_name'],
                            'last_name' => $v['elast_name'],
                            'access_level' => $v['eaccess_level'],
                            'access_level_plus' => $v['eaccess_level_plus'],
                            'is_executive_admin' => $v['eis_executive_admin'],
                            'pay_plan_flag' => $v['epay_plan_flag'],
                            'job_title' => $v['ejob_title'],
                        ]);
            }
        }
    }
}

//
if (!function_exists('getUserFields')) {
    function getUserFields()
    {
        $fields = 'users.sid as userId,';
        $fields .= 'users.first_name,';
        $fields .= 'users.last_name,';
        $fields .= 'users.email,';
        $fields .= 'users.access_level,';
        $fields .= 'users.access_level_plus,';
        $fields .= 'users.pay_plan_flag,';
        $fields .= 'users.is_executive_admin,';
        $fields .= 'users.timezone,';
        $fields .= 'users.job_title,';
        $fields .= 'users.profile_picture,';
        $fields .= 'users.user_shift_hours,';
        $fields .= 'users.user_shift_minutes,';
        $fields .= 'users.profile_picture as image,';
        $fields .= 'users.employee_number,';
        //
        return $fields;
    }
}

//
if (!function_exists('')) {
    function getPDBTN($document, $cls = '', $type = '')
    {
        //
        $r = [
            'pw' => '',
            'dw' => '',
            'pm' => '',
            'dm' => '',
            'pwnew' => '',
            'dwnew' => '',

        ];
        //
        $replace = [
            '{{DOCUMENTSID}}' => $document['sid'],  // Assigned document sid
            '{{DOCUMENTTYPE}}' => 'assigned', // Either submitted or assigned document
            '{{DOCUMENTATYPE}}' => 'assigned_document' // Assigned_document
        ];
        //
        $printURL = base_url('hr_documents_management/perform_action_on_document_content/{{DOCUMENTSID}}/{{DOCUMENTTYPE}}/{{DOCUMENTATYPE}}/print');
        $downloadURL = base_url('hr_documents_management/perform_action_on_document_content/{{DOCUMENTSID}}/{{DOCUMENTTYPE}}/{{DOCUMENTATYPE}}/download');

        $printURLNew = base_url('hr_documents_management/perform_action_on_document_content_new/{{DOCUMENTSID}}/{{DOCUMENTTYPE}}/{{DOCUMENTATYPE}}/print');
        $downloadURLNew = base_url('hr_documents_management/perform_action_on_document_content_new/{{DOCUMENTSID}}/{{DOCUMENTTYPE}}/{{DOCUMENTATYPE}}/download');


        // For Generated
        if ($document['offer_letter_type'] == 'generated' || $document['document_type'] == 'generated') {
            //
            if ($type == '') {
                if (!empty($document['user_consent'])) {
                    $replace['{{DOCUMENTTYPE}}'] = 'submitted';
                }
            }
            //
            $printURL = str_replace(array_keys($replace), $replace, $printURL);
            $downloadURL = str_replace(array_keys($replace), $replace, $downloadURL);

            //
            $printURLNew = str_replace(array_keys($replace), $replace, $printURLNew);
            $downloadURLNew = str_replace(array_keys($replace), $replace, $downloadURLNew);
        } else if ($document['offer_letter_type'] == 'uploaded' || $document['document_type'] == 'uploaded') {
            //
            if ($type == '') {
                if (!empty($document['user_consent']) || !empty($document['uploaded'])) {
                    $replace['{{DOCUMENTTYPE}}'] = 'submitted';
                }
            }
            //
            $awsPath = !empty($document['document_s3_name']) ? $document['document_s3_name'] : $document['uploaded_document_s3_name'];
            //
            $printURL = 'https://docs.google.com/gview?url=' . AWS_S3_BUCKET_URL . ($awsPath) . '&embedded=true';
            $downloadURL = str_replace(array_keys($replace), $replace, $downloadURL);
            $downloadURL = base_url("hr_documents_management/download_upload_document/" . $awsPath);

            //
            $printURLNew = 'https://docs.google.com/gview?url=' . AWS_S3_BUCKET_URL . ($awsPath) . '&embedded=true';
            $downloadURLNew = base_url("hr_documents_management/download_upload_document_new/" . $document['document_s3_name']);
        } else if ($document['offer_letter_type'] == 'hybrid_document' || $document['document_type'] == 'hybrid_document') {
            //
            if ($type == '') {
                if (!empty($document['user_consent']) || !empty($document['uploaded'])) {
                    $replace['{{DOCUMENTTYPE}}'] = 'submitted';
                }
            }
            //
            // $printURL = 'https://docs.google.com/gview?url=' . AWS_S3_BUCKET_URL . (!empty($document['document_s3_name']) ? $document['document_s3_name'] : '') . '&embedded=true';
            $printURL = str_replace(array_keys($replace), $replace, $printURL);
            $downloadURL = str_replace(array_keys($replace), $replace, $downloadURL);

            //
            // $printURLNew = str_replace(array_keys($replace), $replace, $printURLNew);
            // $downloadURLNew = str_replace(array_keys($replace), $replace, $downloadURLNew);
            if (isset($document['uploaded_document_extension'])) {
                $printURLNew = base_url('hr_documents_management/print_download_hybird_document/original/print/both/' . $document['sid']);
                $downloadURLNew = base_url('hr_documents_management/print_download_hybird_document/original/print/both/' . $document['sid']);
            } else {
                if (!empty($document['user_consent']) || !empty($document['uploaded'])) {
                    $printURLNew = base_url('hr_documents_management/print_download_hybird_document/submitted/print/both/' . $document['sid']);
                    $downloadURLNew = base_url('hr_documents_management/print_download_hybird_document/submitted/print/both/' . $document['sid']);
                } else {
                    $printURLNew = base_url('hr_documents_management/print_download_hybird_document/assigned/print/both/' . $document['sid']);
                    $downloadURLNew = base_url('hr_documents_management/print_download_hybird_document/assigned/print/both/' . $document['sid']);
                }
            }
        }
        //_e($document, true);
        //
        $r['pw'] = '<a href="' . ($printURL) . '" class="btn ' . ($cls) . ' btn-orange" style="margin-right: 5px" target="_blank">Print</a>';
        $r['pm'] = '<a href="' . ($printURL) . '" class="btn ' . ($cls) . ' btn-orange"  style="margin-right: 5px" target="_blank">Print</a>';
        //
        $r['dw'] = '<a href="' . ($downloadURL) . '" class="btn ' . ($cls) . ' btn-black" target="_blank">Download</a>';
        $r['dm'] = '<a href="' . ($downloadURL) . '" class="btn ' . ($cls) . ' btn-black"  target="_blank">Download</a>';

        //
        $r['pwnew'] = '<a href="' . ($printURLNew) . '" class="btn ' . ($cls) . ' btn-orange" style="margin-right: 5px" target="_blank">Print</a>';
        $r['dwnew'] = '<a href="' . ($downloadURLNew) . '" class="btn ' . ($cls) . ' btn-black" target="_blank">Download</a>';


        // _e($r, true);
        //
        return $r;
    }
}




/**
 * @employee:    Mubashir Ahmed
 * @date:        10/28/2020
 * Divide document to completed, not completed 
 * and no action required
 * 
 * @param      Array $documens
 * @return     Array
 *             [
 *                  Completed               - Array of completed documents
 *                  NotCompleted            - Array of not completed documents
 *                  NoActionRequired        - Array of no action required documents
 *                  All                     - Array of all assigned documents
 *                  CompletedCount          - Number of documents
 *                  NotCompletedCount       - Number of documents
 *                  NoActionRequiredCount   - Number of documents
 *                  AllCount                - Number of documents
 *             ]
 */
if (!function_exists('separateDocuments')) {
    function separateDocuments(
        $documents
    ) {
        //
        $r = [
            'Completed' => [],
            'NotCompleted' => [],
            'NoActionRequired' => [],
            'All' => [],
            'CompletedCount' => 0,
            'NotCompletedCount' => 0,
            'NoActionRequiredCount' => 0,
            'AllCount' => 0
        ];
        //
        if (!$documents || !count($documents))
            return $r;
        //
        foreach ($documents as $document) {
            $d = isDocumentCompletedCheck($document);
            //
            if ($d['isNoAction'])
                $r['NoActionRequired'][] = $document;
            else if ($d['isCompleted'])
                $r['Completed'][] = $document;
            else if (!$d['isCompleted'])
                $r['NotCompleted'][] = $document;
            $r['All'][] = $document;
        }
        //
        $r['CompletedCount'] = count($r['Completed']);
        $r['NotCompletedCount'] = count($r['NotCompleted']);
        $r['NoActionRequiredCount'] = count($r['NoActionRequired']);
        $r['AllCount'] = count($r['All']);
        //
        return $r;
    }
}

/**
 * @employee: Mubashir Ahmed
 * @date:     10/28/2020
 * Able to cache any page. For now,
 * it will cache only static pages
 * 
 * @param       String   $file
 * @param       Array    $session
 * @param       String   $make
 * @param       Array    $options
 *                        time  (timestamp)
 *                        flush (will delete the cached file)
 */
if (!function_exists('loadCachedFile')) {
    function loadCachedFile(
        $file,
        $session,
        $make = false,
        $options = []
    ) {
        if ($session['company_detail']['sid'] != 57)
            return;
        //
        $cacheTime = isset($options['time']) ? $options['time'] : strtotime('+1 day');
        //
        $cacheDir = ROOTPATH . 'cache/' . $session['company_detail']['sid'] . '/' . $session['employer_detail']['sid'] . '/' . $file . '/';
        //
        if (!is_dir($cacheDir))
            mkdir($cacheDir, DIR_WRITE_MODE, true);
        //
        if ($make) {
            //
            $filename = $cacheDir . $cacheTime . '.mak';
            //
            $f = fopen($filename, 'w');
            fwrite($f, ob_get_contents());
            fclose($f);
        } else {
            //
            $s = scandir($cacheDir);
            //
            if (isset($s[2])) {
                //
                $filename = $cacheDir . $s[2];
                //
                if (isset($options['flush'])) {
                    unlink($filename);
                    exit(0);
                }
                //
                if (file_exists($filename) && $cacheTime > strtotime('now')) {
                    include_once $filename;
                    exit(0);
                } else if (file_exists($filename) && $cacheTime <= strtotime('now')) {
                    unlink($filename);
                    exit(0);
                }
            }
        }
    }
}

//
if (!function_exists('getAuthorizedDocument')) {
    function getAuthorizedDocument($document)
    {
        if (str_replace('{{authorized_signature}}', '', $document['document_description']) != $document['document_description']) {
            //
            $authorized_sign_status = 0;
            $is_document_authorized = 0;
            //
            $assign_on = date("Y-m-d", strtotime($document['assigned_date']));
            $compare_date = date("Y-m-d", strtotime('2020-03-04'));
            //
            $is_document_authorized = $assign_on >= $compare_date || !empty($document['form_input_data']) ? 1 : 0;
            $authorized_sign_status = !empty($document['authorized_signature']) ? 1 : 0;
            //
            if ($is_document_authorized == 1) {
                return
                    '<a class="' . (empty($document['authorized_signature']) ? "btn blue-button btn-sm btn-block" : "btn btn-success btn-sm btn-block") . '  manage_authorized_signature" href="javascript:;" data-auth-signature="' . ($document['sid']) . '">
                    ' . ($authorized_sign_status == 0 ? "Employer Section - Not Completed" : "Employer Section - Completed") . '
                </a>';
            }
        }
        //
        return '';
    }
}


// 
if (!function_exists('mailAWSSES')) {
    function mailAWSSES(
        &$mail,
        $to,
        $d = false
    ) {
        //
        $creds = getCreds('AHR');
        // Set XMailer
        // $mail->XMailer = 'mail.automotohr.com';
        // $mail->Mailer = 'mail.automotohr.com';
        // $mail->ReturnPath = 'no-reply@automotohr.com';
        // $mail->Sender = 'no-reply@automotohr.com';
        // Set Host
        // $mail->isSMTP();
        // $mail->SMTPAuth   = true;
        // $mail->Host       = $creds->SES->Host;
        // $mail->Username   = $creds->SES->User;
        // $mail->Password   = $creds->SES->Password;
        // $mail->SMTPSecure = $creds->SES->Method;
        // $mail->Port       = $creds->SES->Port; 
        //
        // For local machines
        if (in_array($_SERVER['HTTP_HOST'], ['localhost', 'automotohr.local'])) {
            // $mail->SMTPOptions = array(
            //     'ssl' => array(
            //         'verify_peer' => false,
            //         'verify_peer_name' => false,
            //         'allow_self_signed' => true
            //         )
            //     );  
            // $mail->clearAddresses();
            // $mail->addAddress('mubashir.saleemi123@gmail.com');
        }
    }
}

if (!function_exists('generateThumb')) {
    function generateThumb(
        $file,
        $options = []
    ) {
        //
        $options['width'] = isset($options['width']) ? $options['width'] : 58;
        $options['height'] = isset($options['height']) ? $options['height'] : 58;
        $options['url'] = isset($options['url']) ? $options['url'] : AWS_S3_BUCKET_URL;
        //
        $CI = &get_instance();
        //
        $filename = str_replace($options['url'], '', $file);
        $thumb_filename = 'thumb__' . $filename;
        //
        $t = explode('.', $file);
        //
        if (strtolower(end($t)) == 'png')
            $prefix = "data:image/png;base64,";
        else
            $prefix = "data:image/jpeg;base64,";
        //
        // $img = trim(strtolower(end($t))) == 'png' ? imagecreatefrompng($file) : imagecreatefromjpeg($file);
        //
        $fileBase64 = getFileData($file);
        //
        try {
            $img = imagecreatefromstring($fileBase64);
        } catch (Exception $E) {
            echo $E->getMessage();
        }
        //
        // Enable interlancing
        imageinterlace($img, false);
        //        
        $thumb = imagescale($img, $options['width'], $options['height']);
        //
        ob_start();
        //
        if (strtolower(end($t)) == 'png')
            imagepng($thumb);
        else
            imagejpeg($thumb);
        //
        $data = ob_get_contents();
        //
        ob_end_clean();
        //
        $base64 = $prefix . base64_encode($data);
        //
        //
        $CI->load->library('aws_lib');

        $options = [
            'Bucket' => AWS_S3_BUCKET_NAME,
            'Key' => $thumb_filename,
            'Body' => $data,
            'ACL' => 'public-read',
            'ContentType' => getMimeType($thumb_filename)
        ];
        //
        $CI->aws_lib->put_object($options);

        // echo '<a href="'.( AWS_S3_BUCKET_URL.$thumb_filename ).'" target="_blank" >HERE</a>';
        //
        return [
            'link' => '<a href="' . (AWS_S3_BUCKET_URL . $thumb_filename) . '" target="_blank" >HERE</a>',
            'thumb' => $thumb_filename,
            'filename' => $filename,
            'base64' => $base64
        ];
    }
}

/**
 * 
 */
if (!function_exists('getS3DummyFileName')) {
    function getS3DummyFileName($filename, $isFile = false)
    {
        // PDFs
        $files['pdf'] = 'test_file_01.pdf';
        // Excel
        $files['csv'] =
            $files['xls'] =
            $files['xlsx'] = 'test_file_01.xlsx';
        // Word
        $files['doc'] =
            $files['docx'] =
            $files['rtf'] = 'test_file_01.doc';
        // Images
        $files['jpg'] =
            $files['jpeg'] =
            $files['gif'] =
            $files['svg'] =
            $files['png'] = 'test_file_01.png';
        //
        if ($isFile) {
            if (!isset($_FILES[$filename]))
                return $files['pdf'];
            $filename = $_FILES[$filename]['name'];
        }
        //
        if (empty($filename))
            return $files['pdf'];
        //
        $ext = strtolower(trim(@end(explode('.', $filename))));
        //
        return isset($files[$ext]) ? $files[$ext] : $files['pdf'];
    }
}

/**
 * Format date
 * 
 * @date 12/03/2020
 * @author 
 * 
 * @param String  $date         Date
 * @param String  $fromFormat   Default is 'Y-m-d'
 * @param String  $toFormat     Default is 'm/d/Y'
 * 
 * @return String
 */
if (!function_exists('formatDate')) {
    function formatDate(
        $date,
        $fromFormat = 'Y-m-d',
        $toFormat = 'm/d/Y'
    ) {
        if (empty($date) || preg_match('/0000/', $date))
            return $date;
        //
        $t = explode(' ', $date);
        //
        if (count(explode(' ', $fromFormat)) == 1)
            $date = $t[0];
        //
        return DateTime::createFromFormat($fromFormat, $date)->format($toFormat);
    }
}


/**
 * Format date from site to db
 * 
 * @employee Mubashir Ahmed
 * @date     02/17/2021
 * 
 * @param String  $date         Date
 * @param String  $fromFormat   Default is 'm/d/Y'
 * @param String  $toFormat     Default is 'Y-m-d'
 * 
 * @return String
 */
if (!function_exists('formatDateToDB')) {
    function formatDateToDB(
        $date,
        $fromFormat = 'm/d/Y',
        $toFormat = 'Y-m-d'
    ) {
        //
        if (empty($date)) {
            return $date;
        }
        // auto detect format
        if ($fromFormat === false) {
            $fromFormat = detectDateTimeFormat($date);
        }
        //
        $date = formatDateBeforeProcess($date, $fromFormat);
        //
        return DateTime::createFromFormat($fromFormat, $date)->format($toFormat);
    }
}

if (!function_exists('detectDateTimeFormat')) {
    function detectDateTimeFormat(
        $date
    ) {
        $format;

        // Y-m-d H:i:s
        if (preg_match("/\d{4}-\d{2}-\d{2}\s+\d{2}:\d{2}:\d{2}/", $date)) {
            $format = "Y-m-d H:i:s";
        }
        // Y-m-d
        else if (preg_match("/\d{4}-\d{2}-\d{2}/", $date)) {
            $format = "Y-m-d";
        }
        // m/d/Y H:i:s
        else if (preg_match("/\d{2}\/\d{2}\/\d{4}\s+\d{2}:\d{2}:\d{2}/", $date)) {
            $format = "Y-m-d";
        }
        // m/d/Y
        else if (preg_match("/\d{2}\/\d{2}\/\d{4}/", $date)) {
            $format = "Y-m-d";
        }
        // m-d-Y H:i:s
        else if (preg_match("/\d{2}-\d{2}-\d{4}\s+\d{2}:\d{2}:\d{2}/", $date)) {
            $format = "Y-m-d";
        }
        // m-d-Y
        else if (preg_match("/\d{2}-\d{2}-\d{4}/", $date)) {
            $format = "Y-m-d";
        }

        return $format;
    }
}


/**
 * Get differences between two dates 
 * 
 * @date 12/03/2020
 * 
 * @param String  $date1   Start Date
 * @param String  $date2   End Date
 * @param String  $format  
 * 
 * @return Integer
 */
if (!function_exists('dateDifferenceInDays')) {
    function dateDifferenceInDays(
        $date1,
        $date2,
        $format = '%a'
    ) {
        //
        $date1 = DateTime::createFromFormat('Y-m-d', $date1);
        $date2 = DateTime::createFromFormat('Y-m-d', $date2);
        //
        // if($format == 'years'){
        //     $diff = $date2->diff($date1);
        //     return $diff->h + ($diff->days * 24);
        // } else if($format == 'months'){
        //     $diff = $date2->diff($date1);
        //     return $diff->h + ($diff->days * 24);
        // } else if($format == 'days'){
        //     $diff = $date2->diff($date1);
        //     return $diff->h + ($diff->days * 24);
        // } else if($format == 'hours'){
        //     return $date2->diff($date1)->format($format);
        //     $diff = $date2->diff($date1);
        //     return $diff->h + ($diff->days * 24);
        // }
        return $date2->diff($date1)->format($format);
    }
}

//
if (!function_exists('getCompanyAdminSid')) {
    function getCompanyAdminSid($sid)
    {
        $return_sid = 0;
        //
        $CI = &get_instance();
        $CI->db->select('sid');
        $CI->db->where('access_level', 'Admin');
        $CI->db->group_start();
        $CI->db->where('access_level_plus', 1);
        $CI->db->or_where('pay_plan_flag', 1);
        $CI->db->group_end();
        $CI->db->where('terminated_status', 0);
        $CI->db->where('parent_sid', $sid);
        $CI->db->where('archived', 0);
        //
        $admin_plus = $CI->db->get('users')->row_array();

        if (empty($admin_plus)) {

            $CI = &get_instance();
            $CI->db->select('sid');
            $CI->db->where('access_level', 'Admin');
            $CI->db->where('terminated_status', 0);
            $CI->db->where('parent_sid', $sid);
            $CI->db->where('archived', 0);
            //
            $simple_admin = $CI->db->get('users')->row_array();

            if (!empty($admin_payplan)) {
                $return_sid = $simple_admin['sid'];
            }
        } else {
            $return_sid = $admin_plus['sid'];
        }

        return $return_sid;
    }
}


if (!function_exists('getCompanyAdminPlusList')) {
    function getCompanyAdminPlusList($companyId)
    {
        $response = [];
        //
        $CI = &get_instance();
        $CI->db->select('sid, first_name, last_name, email');
        $CI->db->where('access_level', 'Admin');
        $CI->db->group_start();
        $CI->db->where('access_level_plus', 1);
        $CI->db->or_where('pay_plan_flag', 1);
        $CI->db->group_end();
        $CI->db->where('terminated_status', 0);
        $CI->db->where('parent_sid', $companyId);
        $CI->db->where('archived', 0);
        //
        $admin_plus = $CI->db->get('users')->result_array();

        if ($admin_plus) {
            $response = $admin_plus;
        }

        return $response;
    }
}



// Accrual to text
function getAccrualText($accrualOBJ, $isNewHire = false)
{
    //
    $method = $accrualOBJ['method'] == null ? 'days_per_year' : $accrualOBJ['method'];
    $frequency = $accrualOBJ['frequency'] === null ? 'none' : $accrualOBJ['frequency'];
    $time = $accrualOBJ['time'] == null ? 'none' : $accrualOBJ['time'];
    $rate = $accrualOBJ['rate'] == null ? 0 : $accrualOBJ['rate'];
    $rateType = $accrualOBJ['rateType'];
    $frequencyVal = $accrualOBJ['frequencyVal'];
    $text = $isNewHire === false ? 'The entitled employee(s) will have {{accrue}} that can be accrued from {{time}}.' : 'The entitled employee(s) (on probation) will have {{accrue}} that can be accrued from {{time}}.';
    $accrue = '';
    $o = array(
        'days' => array(
            'none' => 'Jan to Dec',
            'start_of_period' => 'Jan to June',
            'end_of_period' => 'July to Dec'
        ),
        'time' => array(
            'none' => '1st to 30th',
            'start_of_period' => '1st to 15th',
            'end_of_period' => '16th to 30th'
        )
    );

    //
    if ($isNewHire !== false) {
        $rate = $accrualOBJ['newHireRate'];
        //
        if ($rate == null || $rate == 0)
            $rate = $accrualOBJ['rate'];
    }

    //
    if ($frequency == 'none')
        $time = $o['days'][$time];
    else
        $time = $o['time'][$time];


    // Case 1
    if ($rate == 0) {
        //
        $accrue = ` "Unlimited" days per year`;
        return preg_replace('/{{accrue}}/', $accrue, preg_replace('/{{time}}/', $time, $text));
    }

    // Case 1
    if ($method == 'hours_per_month' && $frequency == 'none') {
        //
        $accrue = "$rate hour " . ($rate > 1 ? 's' : '') . " per year";
        $duration = $time;
        return preg_replace('/{{accrue}}/', $accrue, preg_replace('/{{time}}/', $time, $text));
    }

    // Case 5
    if ($method == 'hours_per_month' && $frequency == 'yearly') {
        //
        $newRate = number_format(($rateType == 'total_hours' ? $rate / 12 : $rate * 12), 2);
        //
        if ($rateType == 'total_hours') {
            $accrue = "$rate hour" . ($rate > 1 ? 's' : '') . " per year with an accrue rate of $newRate hour" . ($newRate > 1 ? 's' : '') . " per month";
        } else {
            $accrue = "$newRate hour" . ($newRate > 1 ? 's' : '') . " per year with an accrue rate of $rate hour" . ($rate > 1 ? 's' : '') . " per month";
        }
        $time = "$time of each month";
        return preg_replace('/{{accrue}}/', $accrue, preg_replace('/{{time}}/', $time, $text));
    }

    // Case 6
    if ($method == 'hours_per_month' && $frequency == 'monthly') {
        //
        $newRate = $rate;
        //
        $accrue = " ${newRate} hour" . ($newRate > 1 ? 's' : '') . " each month";
        $duration = $time;
        return preg_replace('/{{accrue}}/', $accrue, preg_replace('/{{time}}/', $time, $text));
    }

    // Case 7
    if ($method == 'hours_per_month' && $frequency == 'custom') {
        //
        $slots = (12 / $frequencyVal);
        $frequencyValC = ($rate / $slots);
        $frequencyValC = number_format($frequencyValC, 2);
        //
        $accrue = " $frequencyValC hour" . ($frequencyValC > 1 ? 's' : '') . " every $frequencyVal month" . ($frequencyVal > 1 ? 's' : '') . " ";
        $duration = $time . '.';
        return preg_replace('/{{accrue}}/', $accrue, preg_replace('/{{time}}/', $time, $text));
    }
    //
    return '-';
}

//
function getStatusColor($index)
{
    //
    $colors = [
        'contacted' => 'rgb(0, 165, 133)',
        'responded' => 'rgb(226, 103, 0)',
        'qualifying' => 'rgb(0, 138, 163)',
        'submitted' => 'rgb(121, 112, 89)',
        'interviewing' => 'rgb(144, 4, 213)',
        'offered' => 'rgb(210, 52, 82)',
        'notin' => 'rgb(168, 23, 161)',
        'decline' => 'rgb(211, 0, 0)',
        'placed' => 'rgb(77, 160, 0)',
        'not_contacted' => 'rgb(82, 82, 82)',
        'future_opportunity' => '#00008B',
        'left_message' => '#B2B200',
        'contacted_important' => 'rgb(0, 165, 133)',
        'responded_important' => 'rgb(226, 103, 0)',
        'qualifying_important' => 'rgb(0, 138, 163)',
        'submitted_important' => 'rgb(121, 112, 89)',
        'interviewing_important' => 'rgb(144, 4, 213)',
        'offered_important' => 'rgb(210, 52, 82)',
        'notin_important' => 'rgb(168, 23, 161)',
        'decline_important' => 'rgb(211, 0, 0)',
        'placed_important' => 'rgb(77, 160, 0)',
        'not_contacted_important' => 'rgb(82, 82, 82)',
        'future_opportunity_important' => '#00008B',
        'left_message_important' => '#B2B200',
        'donothire' => '#a94442'
    ];
    //
    $index = ltrim(trim($index), '.');
    //
    return isset($colors[$index]) ? $colors[$index] : '#000000';
}


/**
 * Send emails to applicant for resume
 * 
 * @employee Mubashir Ahmed
 * @date     25/01/2021
 * 
 * @param Array $post
 * @param Bool  $ec
 *              Default is TRUE
 * 
 * @return Void
 */
if (!function_exists('sendResumeEmailToApplicant')) {
    function sendResumeEmailToApplicant($post, $ec = TRUE)
    {
        //
        $_this = &get_instance();
        $_this->load->model('resume_model');
        $_this->load->library('encryption');
        //
        $user_info = array();
        $emailTemplate = '';
        $default_subject = '';
        $default_template = '';
        $user_sid = $post['user_sid'];
        $user_type = $post['user_type'];
        $job_list_sid = $post['job_list_sid'];
        $requested_job_sid = $post['requested_job_sid'];
        $requested_job_type = $post['requested_job_type'];
        $company_sid = $post['company_sid'];
        $company_name = $post['company_name'];

        $emailTemplate = $_this->resume_model->get_send_resume_template($company_sid);

        if (!empty($emailTemplate)) {
            $default_subject = replace_tags_for_document($company_sid, $user_sid, $user_type, $emailTemplate['subject']);
            $default_template = replace_tags_for_document($company_sid, $user_sid, $user_type, $emailTemplate['message_body']);
        } else {
            $emailTemplate = get_email_template(SEND_RESUME_REQUEST);
            $default_subject = replace_tags_for_document($company_sid, $user_sid, $user_type, $emailTemplate['subject']);
            $default_template = replace_tags_for_document($company_sid, $user_sid, $user_type, $emailTemplate['text']);
        }

        $user_info = $_this->resume_model->get_applicant_information($user_sid);

        if (empty($user_info)) {
            if ($ec) {
                $resp = array();
                $resp['Status'] = FALSE;
                $resp['Response'] = '<strong>Success: </strong> Applicant not found!';
                header('Content-Type: application/json');
                echo @json_encode($resp);
                exit(0);
            } else
                return;
        }

        $verification_key = '';
        // $applicant_email = 'mubashir.saleemi123@gmail.com';
        $applicant_email = $user_info['email'];

        if ($user_info['verification_key'] == NULL || empty($user_info['verification_key'])) {
            $verification_key = random_key(80);
            $_this->resume_model->set_offer_letter_verification_key($user_sid, $verification_key, $user_type);
        } else {
            $verification_key = $user_info['verification_key'];
        }

        $data_to_insert = array();
        $data_to_insert['company_sid'] = $company_sid;
        $data_to_insert['user_type'] = $user_type;
        $data_to_insert['user_sid'] = $user_sid;
        $data_to_insert['user_email'] = $applicant_email;
        $data_to_insert['requested_by'] = 0;
        $data_to_insert['requested_subject'] = $default_subject;
        $data_to_insert['requested_message'] = $default_template;
        $data_to_insert['requested_ip_address'] = getUserIP();
        $data_to_insert['requested_user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        $data_to_insert['request_status'] = 1;
        $data_to_insert['requested_date'] = date('Y-m-d H:i:s', strtotime('now'));
        $data_to_insert['job_sid'] = $requested_job_sid;
        $data_to_insert['job_type'] = $requested_job_type;
        $data_to_insert['is_manual'] = 0;

        $_this->resume_model->deactivate_old_resume_request($company_sid, $user_type, $user_sid, $requested_job_sid, $requested_job_type);
        $_this->resume_model->insert_resume_request($data_to_insert);

        $subject = $default_subject;
        $message_body = $default_template;

        $requested_job_sid = $_this->encryption->encrypt($requested_job_sid);
        $requested_job_sid = str_replace('/', '$job', $requested_job_sid);
        $requested_job_type = $_this->encryption->encrypt($requested_job_type);
        $requested_job_type = str_replace('/', '$type', $requested_job_type);

        $url = 'https://www.automotohr.com/onboarding/send_requested_resume/' . $verification_key . '/' . $requested_job_sid . '/' . $requested_job_type;
        $link_btn = '<a style="background-color: #d62828; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" target="_blank" href="' . $url . '">Send Resume</a>';
        //
        $message_body = str_replace('{{link}}', $link_btn, $message_body);
        //
        $from = FROM_EMAIL_NOTIFICATIONS;
        $to = $applicant_email;
        $from_name = ucwords(STORE_DOMAIN);
        $email_hf = message_header_footer_domain($company_sid, $company_name);
        $body = $email_hf['header']
            . $message_body
            . $email_hf['footer'];
        //
        log_and_sendEmail($from, $to, $subject, $body, $from_name);
        //
        if ($ec) {
            $resp = array();
            $resp['Status'] = TRUE;
            $resp['Response'] = '<strong>Success! </strong> You have successfully sent a resume request.';
            header('Content-Type: application/json');
            echo @json_encode($resp);
        }
    }
}

/**
 * Download AWS file to browser
 * 
 * @employee Mubashir Ahmed
 * @date     01/26/2021
 * 
 * @param String $awsFileName
 * 
 * @return Void
 */
if (!function_exists('downloadAWSFileToBrowser')) {
    function downloadAWSFileToBrowser($awsFileName)
    {
        // Get CI instance
        $_this = &get_instance();
        // Set bucketname
        $bucket = AWS_S3_BUCKET_NAME;
        if (in_array($_SERVER['HTTP_HOST'], ['localhost', 'automotohr.local'])) {
            $bucket = str_replace('https', 'http', $bucket);
        }
        // Temporary store path
        $tp = FCPATH . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'temp_files' . DIRECTORY_SEPARATOR;
        //
        if (!is_dir($tp))
            mkdir($tp, 0777, true);
        // Create full path
        $tfp = $tp . $awsFileName;
        // Delete if file already exists
        if (file_exists($tfp))
            unlink($tfp);
        // Load AWS library
        $_this->load->library('aws_lib');
        $_this->aws_lib->get_object($bucket, $awsFileName, $tfp);

        if (file_exists($tfp)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $awsFileName . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($tfp));
            $handle = fopen($tfp, 'rb');
            $buffer = '';
            //
            while (!feof($handle)) {
                $buffer = fread($handle, filesize($tfp));
                echo $buffer;
                ob_flush();
                flush();
            }
            //
            fclose($handle);
            @unlink($tfp);
        }
    }
}

/**
 * Store applicant data in queue
 * 
 * @employee Mubashir Ahmed
 * @date     04/22/2025
 * 
 * @param String $data
 * 
 * @return Void
 */
if (!function_exists('storeApplicantApplicationInQueue')) {
    function storeApplicantApplicationInQueue($data)
    {
        // Get CI instance
        $_this = &get_instance();
        $_this->load->model('All_feed_model');

        $applicant_id = $data['portal_applicant_job_sid'];
        if (empty($applicant_id)) {
            $applicant = $_this->All_feed_model->get_applicant_job();
            $applicant_id = $applicant->sid;
        }

        if (!empty($applicant)) {
            $_this->All_feed_model->insert_applicant_job_queue([
                'portal_applicant_job_sid' => $applicant_id,
                'portal_job_applications_sid' => $data['portal_job_applications_sid'],
                'company_sid' => $data['company_sid'],
                'job_sid' => $data['job_sid']
            ]);
        }
    }
}


/**
 * 
 */
if (!function_exists('getSelect')) {
    function getSelect($options, $pre = [])
    {
        //
        $o = '';
        //
        if (!empty($pre)) {
            $o .= '<option value="' . (key($pre)) . '">' . ($pre[key($pre)]) . '</option>';
        }
        if (!empty($options)) {
            foreach ($options as $option) {
                $option = array_values($option);
                $o .= '<option value="' . ($option[0]) . '">' . ($option[1]) . '</option>';
            }
        }
        //
        return $o;
    }
}

/**
 * 
 */
if (!function_exists('getImageURL')) {
    function getImageURL($img)
    {
        //
        $img = str_replace(AWS_S3_BUCKET_URL, "", $img);
        $img = str_replace(AWS_S3_BUCKET_URL . 'test.png', "", $img);
        //
        if (!empty($img) && !preg_match('/pdf|doc|docx|xls|xlxs/i', strtolower($img))) {
            return AWS_S3_BUCKET_URL . $img;
        } else {
            return base_url('assets/images/img-applicant.jpg');
        }
    }
}


/**
 * 
 */
if (!function_exists('getVideoURL')) {
    function getVideoURL($id, $video_url, $module = 'performance_management')
    {
        switch ($module):
            case "performance_management":
                if (file_exists(APPPATH . "../assets/performance_management/videos/{$id}/{$video_url}"))
                    return base_url("assets/performance_management/videos/{$id}/{$video_url}");
                else
                    return FALSE;
                break;
        endswitch;
    }
}


/**
 * 
 */
if (!function_exists('getDueText')) {
    function getDueText($endDate, $full = false)
    {
        $endDate .= ' 23:59:59';
        $startDate = date('Y-m-d 23:59:59', strtotime('now'));
        $now = new DateTime($startDate);
        $ago = new DateTime($endDate);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full)
            $string = array_slice($string, 0, 1);
        return $string ? 'Due in ' . implode(', ', $string) . '' : 'Expired';
    }
}

if (!function_exists('concertBase64ToPDF')) {
    function concertBase64ToPDF(
        $base64_string,
        $file_name
    ) {
        //
        if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == 'automotohr.local')
            return getS3DummyFileName($fileName, false);
        //
        $CI = &get_instance();
        // Set local path
        $path = APPPATH . '../assets/tmp/';
        //
        $file_parts = explode(";base64,", $base64_string);
        //
        $image_base64 = base64_decode($file_parts[1]);
        //
        $new_file_name = strtolower(str_replace(" ", "_", $file_name)) . '.pdf';
        //
        $target_file = $path . $new_file_name;
        //
        file_put_contents($target_file, $image_base64);
        //
        $CI->load->library('aws_lib');
        //
        $options = [
            'Bucket' => AWS_S3_BUCKET_NAME,
            'Key' => $new_file_name,
            'Body' => file_get_contents($target_file),
            'ACL' => 'public-read',
            'ContentType' => getMimeType($new_file_name)
        ];
        //
        $CI->aws_lib->put_object($options);
        //
        unlink($target_file);
        //
        return $new_file_name;
    }
}

if (!function_exists('replace_select_html_tag')) {
    function replace_select_html_tag($body)
    {
        //
        $new_body = preg_replace('/<select(.*?)>(.*?)<\/select>/i', '{{select}}', $body);
        //
        return $new_body;
    }
}

if (!function_exists('document_description_tags')) {
    function document_description_tags($type)
    {
        //
        $all_magic_codes = array();
        $simple_codes = array('{{short_text}}', '{{text}}', '{{text_area}}', '{{checkbox}}', '{{short_text_required}}', '{{text_required}}', '{{text_area_required}}', '{{checkbox_required}}', '{{select}}');
        $signature_codes = array('{{signature}}', '{{inital}}');
        $authorized_codes = array('{{authorized_signature}}', '{{authorized_signature_date}}', '{{authorized_editable_date}}');
        //
        if ($type == 'all') {
            return array_merge($simple_codes, $signature_codes, $authorized_codes);
        } else if ($type == 'signature') {
            return $signature_codes;
        } else if ($type == 'authorized') {
            return $authorized_codes;
        } else if ($type == 'simple') {
            return $simple_codes;
        }
    }
}

if (!function_exists('isDocumentCompletedCheck')) {
    function isDocumentCompletedCheck(
        &$document,
        $check = false,
        $doConvertImages = false
    ) {
        //
        $ra = [
            'isCompleted' => false,
            'isNoAction' => false,
            'isAuthorized' => false,
            'isManualDocument' => false,
            'hasMagicCodes' => false,
            'buttonType' => '',
            'completedAt' => [],
            'assignedAt' => [],
            'assigned_print_url' => '',
            'assigned_download_url' => '',
            'submitted_print_url' => '',
            'submitted_download_url' => '',
            'ifram_url' => '',
            'image_path' => '',
            'html_body' => ''
        ];
        //
        $ra['assignedAt'] = [
            'db_format' => $document['assigned_date'],
            // 'fe_format' => DateTime::createFromFormat('Y-m-d H:i:s', $document['assigned_date'])->format('m/d/Y H:i'),
            // 'ff_format' => DateTime::createFromFormat('Y-m-d H:i:s', $document['assigned_date'])->format('M d Y, D H:i:s')
        ];
        // For manual & bulk upload ones
        if ($document['document_sid'] === 0) {
            $ra['isCompleted'] = $ra['isManualDocument'] = true;
        } else {
            // Set document type
            $type = $document['document_type'];
            $isDocument = true;
            $body = $document['document_description'];
            //
            if (!empty($document['manual_document_type']))
                $type = $document['manual_document_type'];
            if (!empty($document['offer_letter_type'])) {
                $isDocument = false;
                $type = $document['offer_letter_type'];
            }

            // Check document Type
            if ($type == 'generated' || $type == 'hybrid_document') {
                // 
                $magic_keys = document_description_tags('all');
                //
                $authorizedCodes = document_description_tags('authorized');
                $magicCodes = document_description_tags('simple');
                $magicSignatureCodes = document_description_tags('signature');
                //
                $withoutSignMC = str_replace($magicSignatureCodes, '', $body);
                $withoutMC = str_replace($magicCodes, '', $body);
                //
                if ($withoutSignMC != $body)
                    $ra['buttonType'] = 'consent_only';
                else if ($withoutMC != $body)
                    $ra['buttonType'] = 'save_only';
                //
                $ra['isAuthorized'] = preg_match('/{{authorized_signature}}|{{authorized_signature_date}}|{{authorized_editable_date}}/i', $body);
                //
                $ra['hasMagicCodes'] = $hasAnyMC = preg_match('/{{(.*?)}}/i', $body);
                //
                $hasSignMagicCode = preg_match('/{{signature}}|{{inital}}/i', $body);
                // When nothing is set
                if (
                    $document['signature_required'] == 0 &&
                    $document['download_required'] == 0 &&
                    $document['acknowledgment_required'] == 0 &&
                    !$hasAnyMC
                ) {
                    $ra['isNoAction'] = $ra['isCompleted'] = true;
                } else if (
                    $document['signature_required'] == 1 ||
                    $hasAnyMC                                   // When signature is required or any magic code is found
                ) {
                    // Check if it's signed
                    if ($document['user_consent'] == 1)
                        $ra['isCompleted'] = true;
                } else if (
                    $document['download_required'] == 1 &&
                    $document['acknowledgment_required'] == 1    // When download & acknowledged is required
                ) {
                    // 
                    if (
                        $document['acknowledged'] == 1 &&
                        $document['downloaded'] == 1
                    )
                        $ra['isCompleted'] = true;
                } else if (
                    $document['acknowledgment_required'] == 1    // When acknowledged is required
                ) {
                    // 
                    if ($document['acknowledged'] == 1)
                        $ra['isCompleted'] = true;
                } else if (
                    $document['download_required'] == 1          // When download is required
                ) {
                    // 
                    if ($document['downloaded'] == 1)
                        $ra['isCompleted'] = true;
                }

                if ($ra['isCompleted'] == true) {
                    if (empty($document['form_input_data'])) {
                        $listaction = getGeneratedDocumentURL($document, 'uncompleted', $ra['isAuthorized']);
                        $ra['assigned_print_url'] = $listaction['print_url'];
                        $ra['assigned_download_url'] = $listaction['download_url'];

                        $listaction = getUploadedDocumentURL($document['submitted_description']);
                        $ra['submitted_print_url'] = $listaction['print_url'];
                        $ra['submitted_download_url'] = $listaction['download_url'];
                        $ra['ifram_url'] = $listaction['ifram_url'];
                        $ra['image_path'] = $listaction['image_path'];
                    } else {
                        $listaction = getGeneratedDocumentURL($document, 'uncompleted', $ra['isAuthorized']);
                        $ra['assigned_print_url'] = $listaction['print_url'];
                        $ra['assigned_download_url'] = $listaction['download_url'];

                        $listaction = getGeneratedDocumentURL($document, 'completed', $ra['isAuthorized']);
                        $ra['submitted_print_url'] = $listaction['print_url'];
                        $ra['submitted_download_url'] = $listaction['download_url'];
                        $ra['html_body'] = $listaction['html_body'];
                    }
                } else {
                    $listaction = getGeneratedDocumentURL($document, 'uncompleted', $ra['isAuthorized']);
                    $ra['assigned_print_url'] = $listaction['print_url'];
                    $ra['assigned_download_url'] = $listaction['download_url'];
                    $ra['html_body'] = $listaction['html_body'];
                }
            } else if ($type == 'uploaded') {
                // When nothing is set
                if (
                    $document['signature_required'] == 0 &&
                    $document['download_required'] == 0 &&
                    $document['acknowledgment_required'] == 0
                ) {
                    $ra['isNoAction'] = $ra['isCompleted'] = true;
                } else if (
                    $document['signature_required'] == 1         // When signature (Reupload) is required or any magic code is found
                ) {
                    // Check if it's signed
                    if ($document['user_consent'] == 1 || $document['uploaded'] == 1)
                        $ra['isCompleted'] = true;
                } else if (
                    $document['download_required'] == 1 &&
                    $document['acknowledgment_required'] == 1    // When download & acknowledged is required
                ) {
                    // 
                    if (
                        $document['acknowledged'] == 1 &&
                        $document['downloaded'] == 1
                    )
                        $ra['isCompleted'] = true;
                } else if (
                    $document['acknowledgment_required'] == 1    // When acknowledged is required
                ) {
                    // 
                    if ($document['acknowledged'] == 1)
                        $ra['isCompleted'] = true;
                } else if (
                    $document['download_required'] == 1          // When download is required
                ) {
                    // 
                    if ($document['downloaded'] == 1)
                        $ra['isCompleted'] = true;
                }

                if ($ra['isCompleted'] == true) {

                    $listaction = getUploadedDocumentURL($document['document_s3_name']);
                    $ra['assigned_print_url'] = $listaction['print_url'];
                    $ra['assigned_download_url'] = $listaction['download_url'];

                    //
                    $listaction = getUploadedDocumentURL($document['uploaded_file']);
                    $ra['submitted_print_url'] = $listaction['print_url'];
                    $ra['submitted_download_url'] = $listaction['download_url'];
                    $ra['ifram_url'] = $listaction['ifram_url'];
                    $ra['image_path'] = $listaction['image_path'];
                } else {
                    $listaction = getUploadedDocumentURL($document['document_s3_name']);
                    $ra['assigned_print_url'] = $listaction['print_url'];
                    $ra['assigned_download_url'] = $listaction['download_url'];
                    $ra['ifram_url'] = $listaction['ifram_url'];
                    $ra['image_path'] = $listaction['image_path'];
                }
            } else if ($type === 'hybrid_document') {
                if ($document['user_consent'] == 1) {
                    $ra['isCompleted'] = true;
                }
            }
        }
        //
        $document['ra'] = $ra;
        //
        if ($check)
            return $ra['isCompleted'];
        //
        return $ra;
    }
}

if (!function_exists('getUploadedDocumentURL')) {
    function getUploadedDocumentURL($document_path)
    {
        $extension = strtolower(pathinfo($document_path)['extension']);

        $ra = [
            'print_url' => '',
            'download_url' => '',
            'ifram_url' => '',
            'image_path' => ''
        ];

        if (in_array($extension, ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'])) {
            $ra['print_url'] = "https://view.officeapps.live.com/op/view.aspx?src=" . AWS_S3_BUCKET_URL . $document_path;
            $ra['ifram_url'] = "https://view.officeapps.live.com/op/embed.aspx?src=" . AWS_S3_BUCKET_URL . $document_path;
        } else if (in_array($extension, ['jpe', 'jpg', 'jpeg', 'png', 'bmp', 'gif', 'svg'])) {
            $ra['image_path'] = AWS_S3_BUCKET_URL . $document_path;
            $ra['print_url'] = base_url('hr_documents_management/print_s3_image/' . $document_path);
        } else {
            $ra['print_url'] = "https://docs.google.com/viewerng/viewer?url=" . AWS_S3_BUCKET_URL . $document_path;
            $ra['ifram_url'] = 'https://docs.google.com/gview?url=' . AWS_S3_BUCKET_URL . $document_path . '&embedded=true';
        }

        $ra['download_url'] = base_url('hr_documents_management/download_upload_document/' . $document_path);

        return $ra;
    }
}

if (!function_exists('getGeneratedDocumentURL')) {
    function getGeneratedDocumentURL($document, $type, $isAuthorized)
    {
        $ra = [
            'print_url' => '',
            'download_url' => '',
            'html_body' => ''
        ];

        if ($type == 'company') {
            $ra['print_url'] = base_url('hr_documents_management/perform_action_on_document_content') . '/' . $document['sid'] . '/company/company_document/print';
            $ra['download_url'] = base_url('hr_documents_management/perform_action_on_document_content') . '/' . $document['sid'] . '/company/company_document/download';
            $ra['html_body'] = getDocumentBody($document, $type, $isAuthorized);
        } else if ($type == 'uncompleted') {
            $ra['print_url'] = base_url('hr_documents_management/perform_action_on_document_content') . '/' . $document['sid'] . '/assigned/assigned_document/print';
            $ra['download_url'] = base_url('hr_documents_management/perform_action_on_document_content') . '/' . $document['sid'] . '/assigned/assigned_document/download';
            $ra['html_body'] = getDocumentBody($document, $type, $isAuthorized);
        } else {
            $ra['print_url'] = base_url('hr_documents_management/perform_action_on_document_content') . '/' . $document['sid'] . '/submitted/assigned_document/print';
            $ra['download_url'] = base_url('hr_documents_management/perform_action_on_document_content') . '/' . $document['sid'] . '/submitted/assigned_document/download';
            $ra['html_body'] = getDocumentBody($document, $type, $isAuthorized);
        }

        return $ra;
    }
}

if (!function_exists('getDocumentBody')) {
    function getDocumentBody($document, $document_type, $isAuthorized)
    {
        $companyInfo = getCompanyInfo($document['company_sid']);
        $userInfo = getUserInfo($document['user_type'], $document['user_sid']);

        $my_return = $document['document_description'];

        $value = date('M d Y');
        $my_return = str_replace('{{date}}', $value, $my_return);

        $value = $userInfo['first_name'];
        $my_return = str_replace('{{first_name}}', $value, $my_return);

        $value = $userInfo['last_name'];
        $my_return = str_replace('{{last_name}}', $value, $my_return);

        $value = $userInfo['email'];
        $my_return = str_replace('{{email}}', $value, $my_return);

        $value = $userInfo['job_title'];
        $my_return = str_replace('{{job_title}}', $value, $my_return);

        $value = $companyInfo['company_name'];
        $my_return = str_replace('{{company_name}}', $value, $my_return);

        $value = $companyInfo['company_address'];
        $my_return = str_replace('{{company_address}}', $value, $my_return);

        $value = $companyInfo['company_phone'];
        $my_return = str_replace('{{company_phone}}', $value, $my_return);

        $value = $companyInfo['career_site_url'];
        $my_return = str_replace('{{career_site_url}}', $value, $my_return);

        $short_textboxes = substr_count($my_return, '{{short_text}}');
        $long_textboxes = substr_count($my_return, '{{text}}');
        $checkboxes = substr_count($my_return, '{{checkbox}}');
        $textareas = substr_count($my_return, '{{text_area}}');
        $short_textboxes_required = substr_count($my_return, '{{short_text_required}}');
        $long_textboxes_required = substr_count($my_return, '{{text_required}}');
        $checkboxes_required = substr_count($my_return, '{{checkbox_required}}');
        $textareas_required = substr_count($my_return, '{{text_area_required}}');

        //
        $CI = &get_instance();
        //
        $CI->db->select('form_input_data');
        $CI->db->where('sid', $document['sid']);

        $record_obj = $CI->db->get('documents_assigned');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        $form_input_data = '';

        if (!empty($record_arr)) {
            $form_input_data = unserialize($record_arr[0]['form_input_data']);
            $form_input_data = (array) json_decode($form_input_data);
        }

        for ($stb = 0; $stb < $short_textboxes; $stb++) {
            $short_textbox_name = 'short_textbox_' . $stb;
            //
            $short_textbox_value = '';
            if ($document_type == 'completed') {
                $short_textbox_value = !empty($form_input_data[$short_textbox_name]) ? $form_input_data[$short_textbox_name] : '';
            }
            //
            $short_textbox_id = 'short_textbox_' . $stb . '_id';
            $short_textbox = '<input type="text" data-type="text" data-required="no" maxlength="40" style="width: 300px; height: 34px; border: 1px solid #777; border-radius: 4px; background-color:#eee; padding: 0 5px;" class="short_textbox" name="' . $short_textbox_name . '" id="' . $short_textbox_id . '" value="' . $short_textbox_value . '" />';
            $my_return = preg_replace('/{{short_text}}/', $short_textbox, $my_return, 1);
        }

        for ($ltb = 0; $ltb < $long_textboxes; $ltb++) {
            $long_textbox_name = 'long_textbox_' . $ltb;
            //
            $long_textbox_value = '';
            if ($document_type == 'completed') {
                $long_textbox_value = !empty($form_input_data[$long_textbox_name]) ? $form_input_data[$long_textbox_name] : '';
            }
            //
            $long_textbox_id = 'long_textbox_' . $ltb . '_id';
            $long_textbox = '<input type="text" data-type="text" data-required="no" class="form-control input-grey long_textbox" name="' . $long_textbox_name . '" id="' . $long_textbox_id . '" value="' . $long_textbox_value . '"/>';
            $my_return = preg_replace('/{{text}}/', $long_textbox, $my_return, 1);
        }

        for ($cb = 0; $cb < $checkboxes; $cb++) {
            $checkbox_name = 'checkbox_' . $cb;
            //
            $checkbox_value = '';
            if ($document_type == 'completed') {
                $checkbox_value = !empty($form_input_data[$checkbox_name]) && $form_input_data[$checkbox_name] == 'yes' ? 'checked="checked"' : '';
            }
            //
            $checkbox_id = 'checkbox_' . $cb . '_id';
            $checkbox = '<br><input type="checkbox" data-type="checkbox" data-required="no" class="user_checkbox input-grey" name="' . $checkbox_name . '" id="' . $checkbox_id . '" ' . $checkbox_value . '/>';
            $my_return = preg_replace('/{{checkbox}}/', $checkbox, $my_return, 1);
        }

        for ($ta = 0; $ta < $textareas; $ta++) {
            $textarea_name = 'textarea_' . $ta;
            //
            $textarea_value = '';
            if ($document_type == 'completed') {
                $textarea_value = !empty($form_input_data[$textarea_name]) ? $form_input_data[$textarea_name] : '';
            }
            //
            $textarea_id = 'textarea_' . $ta . '_id';
            $div_id = 'textarea_' . $ta . '_id_sec';
            $textarea = '<textarea data-type="textarea" data-required="no" style="border: 1px dotted #777; padding:5px; min-height: 145px; width:100%; background-color:#eee; resize: none;" class="text_area" name="' . $textarea_name . '" id="' . $textarea_id . '">' . $textarea_value . '</textarea><div style="border: 1px dotted #777; padding:5px; display: none; background-color:#eee;" class="div-editable fillable_input_field" id="' . $div_id . '"  contenteditable="false"></div>';
            $my_return = preg_replace('/{{text_area}}/', $textarea, $my_return, 1);
        }
        //
        for ($stb; $stb < ($short_textboxes + $short_textboxes_required); $stb++) {
            $short_textbox_name = 'short_textbox_' . $stb;
            //
            $short_textbox_value = '';
            if ($document_type == 'completed') {
                $short_textbox_value = !empty($form_input_data[$short_textbox_name]) ? $form_input_data[$short_textbox_name] : '';
            }
            //
            $short_textbox_id = 'short_textbox_' . $stb . '_id';
            //
            $short_textbox = '<label><span class="staric">*</span></label><br>';
            $short_textbox .= '<input type="text" required data-type="text" data-required="yes" maxlength="40" style="width: 300px; height: 34px; border: 1px solid #777; border-radius: 4px; background-color:#eee; padding: 0 5px;" class="short_textbox" name="' . $short_textbox_name . '" id="' . $short_textbox_id . '" value="' . $short_textbox_value . '" />';
            //
            $my_return = preg_replace('/{{short_text_required}}/', $short_textbox, $my_return, 1);
        }

        for ($ltb; $ltb < ($long_textboxes + $long_textboxes_required); $ltb++) {
            $long_textbox_name = 'long_textbox_' . $ltb;
            //
            $long_textbox_value = '';
            if ($document_type == 'completed') {
                $long_textbox_value = !empty($form_input_data[$long_textbox_name]) ? $form_input_data[$long_textbox_name] : '';
            }
            //
            $long_textbox_id = 'long_textbox_' . $ltb . '_id';
            //
            $long_textbox = '<label><span class="staric">*</span></label>';
            $long_textbox .= '<input type="text" data-type="text" data-required="yes" class="form-control input-grey long_textbox" name="' . $long_textbox_name . '" id="' . $long_textbox_id . '" value="' . $long_textbox_value . '"/>';
            //
            $my_return = preg_replace('/{{text_required}}/', $long_textbox, $my_return, 1);
        }

        for ($ta; $ta < ($textareas + $textareas_required); $ta++) {
            $textarea_name = 'textarea_' . $ta;
            //
            $textarea_value = '';
            if ($document_type == 'completed') {
                $textarea_value = !empty($form_input_data[$textarea_name]) ? $form_input_data[$textarea_name] : '';
            }
            //
            $textarea_id = 'textarea_' . $ta . '_id';
            $div_id = 'textarea_' . $ta . '_id_sec';
            //
            $textarea = '<label><span class="staric">*</span></label>';
            $textarea .= '<textarea data-type="textarea" data-required="yes" style="border: 1px dotted #777; padding:5px; min-height: 145px; width:100%; background-color:#eee; resize: none;" class="text_area" name="' . $textarea_name . '" id="' . $textarea_id . '">' . $textarea_value . '</textarea><div style="border: 1px dotted #777; padding:5px; display: none; background-color:#eee;" class="div-editable fillable_input_field" id="' . $div_id . '"  contenteditable="false"></div>';
            $my_return = preg_replace('/{{text_area_required}}/', $textarea, $my_return, 1);
        }

        for ($cb; $cb < ($checkboxes + $checkboxes_required); $cb++) {
            $checkbox_name = 'checkbox_' . $cb;
            //
            $checkbox_value1 = '';
            $checkbox_value2 = '';
            //
            if ($document_type == 'completed') {
                $checkbox_value1 = !empty($form_input_data[$checkbox_name]) && $form_input_data[$checkbox_name] == 'yes' ? 'checked="checked"' : '';
                $checkbox_value2 = !empty($form_input_data[$checkbox_name]) && $form_input_data[$checkbox_name] == 'yes' ? 'checked="checked"' : '';
            }
            //
            $checkbox_id = 'checkbox_' . $cb . '_id';
            //
            $checkboxRequired = '<div class="row jsCheckbox" data-type="checkbox" data-required="yes" data-name="' . $checkbox_name . '" id="' . $checkbox_id . '">';
            $checkboxRequired .= '<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">';
            $checkboxRequired .= '<label><span class="staric">*</span></label>';
            $checkboxRequired .= '</div>';
            $checkboxRequired .= '<div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">';
            $checkboxRequired .= '<input type="checkbox" data-type="checkbox" class="user_checkbox input-grey" name="' . $checkbox_name . '1" value="yes" ' . $checkbox_value1 . '/>Yes';
            $checkboxRequired .= '</div>';
            $checkboxRequired .= '<br>';
            $checkboxRequired .= '<div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">';
            $checkboxRequired .= '<input type="checkbox" data-type="checkbox" class="user_checkbox input-grey" name="' . $checkbox_name . '2" value="no" ' . $checkbox_value2 . '/>No';
            $checkboxRequired .= '</div>';
            $checkboxRequired .= '</div>';
            //
            $my_return = preg_replace('/{{checkbox_required}}/', $checkboxRequired, $my_return, 1);
        }
        //
        $signature = '';
        $signature_initial = '';
        $signature_date = '';
        $signature_by = '';
        //
        if ($document_type == 'uncompleted') {
            $signature = '<a class="btn btn-sm blue-button get_signature" href="javascript:;">Create E-Signature</a><img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src=""  id="draw_upload_img" />';
            $signature_initial = '<a class="btn btn-sm blue-button get_signature_initial" href="javascript:;">Signature Initial</a><img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src=""  id="target_signature_init" />';
            $signature_date = '<a class="btn btn-sm blue-button get_signature_date" href="javascript:;">Sign Date</a><p id="target_signature_timestamp"></p>';
            $signature_by = '<input type="text" id="signature_person_name" class="form-control input-grey js_signature_person_name" style="margin-top:16px; width: 50%;" name="signature_person_name" readonly value="">';
        } else {
            $signature = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . $document['signature_base64'] . '">';
            $signature_initial = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . $document['signature_initial'] . '">';
            $signature_date = '<p><strong>' . date_with_time($document['signature_timestamp']) . '</strong></p>';
            $signature_person_name = !empty($form_input_data['signature_person_name']) ? $form_input_data['signature_person_name'] : '';
            $signature_by = '<p><strong>' . $signature_person_name . '</strong></p>';
        }
        //
        $my_return = str_replace('{{signature}}', $signature, $my_return);
        $my_return = str_replace('{{inital}}', $signature_initial, $my_return);
        $my_return = str_replace('{{sign_date}}', $signature_date, $my_return);
        $my_return = str_replace('{{signature_print_name}}', $signature_by, $my_return);
        //
        $authorized_signature = '';
        $authorized_signature_date = '';
        $authorized_editable_date = '';
        //
        if ($isAuthorized == 1) {
            if ($document['authorized_signature_by'] != 0 && $document_type == 'completed') {
                $authorized_signature_date = '<p><strong>' . date_with_time($document['authorized_signature_date']) . '</strong></p>';

                $CI->db->select('assigned_to_signature');
                $CI->db->where('document_assigned_sid', $document['sid']);
                $CI->db->where('assigned_to_signature <>', NULL);

                $record_obj = $CI->db->get('authorized_document_assigned_manager');
                $record_arr = $record_obj->row_array();
                $record_obj->free_result();

                if (!empty($record_arr)) {
                    $authorized_signature = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . $record_arr['assigned_to_signature'] . '">';
                }
            } else {
                $authorized_signature = '<p>Authorized Signature (<b>Not Signed</b>)</p>';
                $authorized_signature_date = '<p>Authorized Signature Date (<b>Not Entered</b>)</p>';
            }

            if (!empty($document['authorized_editable_date'])) {
                $authorized_editable_date = ' <strong>' . formatDateToDB($document['authorized_editable_date'], DB_DATE, DATE) . '</strong>';
            } else {
                $authorized_editable_date = '<p>Authorized Date (<b>Not Entered</b>)</p>';
            }

            $my_return = str_replace('{{authorized_signature}}', $authorized_signature, $my_return);
            $my_return = str_replace('{{authorized_signature_date}}', $authorized_signature_date, $my_return);
            $my_return = str_replace('{{authorized_editable_date}}', $authorized_editable_date, $my_return);
        }

        return $my_return;
    }
}

if (!function_exists('getCompanyInfo')) {
    function getCompanyInfo($company_sid)
    {
        //
        $ra = [
            'company_name' => '[Company Name]',
            'company_address' => '[Company Address]',
            'company_phone' => '[Company Phone]',
            'career_site_url' => '[Career Site Url]'
        ];
        //
        $CI = &get_instance();

        //Get Company Info
        $CI->db->select('users.CompanyName');
        $CI->db->select('users.Location_Address');
        $CI->db->select('users.Location_Country');
        $CI->db->select('users.Location_State');
        $CI->db->select('users.Location_City');
        $CI->db->select('users.CompanyDescription');
        $CI->db->select('users.Location_ZipCode');
        $CI->db->select('users.PhoneNumber');

        $CI->db->select('states.state_name as state');

        $CI->db->select('portal_employer.sub_domain');
        $CI->db->select('portal_employer.domain_type');

        $CI->db->where('users.sid', $company_sid);
        $CI->db->join('portal_employer', 'users.sid = portal_employer.user_sid', 'left');
        $CI->db->join('states', 'users.Location_State = states.sid', 'left');

        $record_obj = $CI->db->get('users');
        $company_info = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($company_info)) {
            $company_info = $company_info[0];

            $address = $company_info['Location_Address'];
            $city = $company_info['Location_City'];
            $state = $company_info['state'];
            $zipcode = $company_info['Location_ZipCode'];
            $country = $company_info['Location_Country'] == 227 ? 'United States' : 'Canada';

            $full_address = '';
            $full_address .= !empty($address) ? $address : '';
            $full_address .= !empty($city) ? ', ' . $city : '';
            $full_address .= !empty($state) ? ', ' . $state : '';
            $full_address .= !empty($zipcode) ? ', ' . $zipcode : '';
            $full_address .= !empty($country) ? ', ' . $country : '';

            $domain = $company_info['sub_domain'];
            $domain_type = $company_info['domain_type'];

            $career_site_url = '';
            if ($domain_type == 'addondomain') {
                $career_site_url = $domain;
            } else {
                $career_site_url = STORE_PROTOCOL . $domain;
            }

            $ra['company_name'] = $company_info['CompanyName'];
            $ra['company_address'] = $full_address;
            $ra['company_phone'] = $company_info['PhoneNumber'];
            $ra['career_site_url'] = $career_site_url;
        }
        //
        return $ra;
    }
}

if (!function_exists('getUserInfo')) {
    function getUserInfo($user_type, $user_sid)
    {
        //
        $ra = [
            'first_name' => '[' . $user_type . ' First Name]',
            'last_name' => '[' . $user_type . ' Last Name]',
            'email' => '[' . $user_type . ' Email]',
            'job_title' => 'No Job Title Found'
        ];
        //
        $CI = &get_instance();
        //
        if ($user_type == 'applicant') {

            $CI->db->select('portal_job_applications.first_name');
            $CI->db->select('portal_job_applications.last_name');
            $CI->db->select('portal_job_applications.email');

            $CI->db->select('portal_applicant_jobs_list.job_sid');
            $CI->db->select('portal_applicant_jobs_list.date_applied as application_date');
            $CI->db->select('portal_applicant_jobs_list.desired_job_title');
            $CI->db->select('portal_job_listings.Title as job_title');

            $CI->db->where('portal_job_applications.sid', $user_sid);

            $CI->db->join('portal_job_applications', 'portal_job_applications.sid = portal_applicant_jobs_list.portal_job_applications_sid', 'left');
            $CI->db->join('portal_job_listings', 'portal_job_listings.sid = portal_applicant_jobs_list.job_sid', 'left');

            $record_obj = $CI->db->get('portal_applicant_jobs_list');
            $record_arr = $record_obj->result_array();
            $record_obj->free_result();

            if (!empty($record_arr)) {
                $user_info = $record_arr[0];

                $ra['first_name'] = $user_info['first_name'];
                $ra['last_name'] = $user_info['last_name'];
                $ra['email'] = $user_info['email'];
                $ra['job_title'] = $user_info['job_title'];
            }
        } else if ($user_type == 'employee') {
            $CI->db->select('username');
            $CI->db->select('first_name');
            $CI->db->select('last_name');
            $CI->db->select('job_title');
            $CI->db->select('email');
            $CI->db->select('job_title');
            $CI->db->select('registration_date');

            $CI->db->where('sid', $user_sid);

            $record_obj = $CI->db->get('users');
            $record_arr = $record_obj->result_array();
            $record_obj->free_result();

            if (!empty($record_arr)) {
                $user_info = $record_arr[0];

                $ra['first_name'] = $user_info['first_name'];
                $ra['last_name'] = $user_info['last_name'];
                $ra['email'] = $user_info['email'];
                $ra['job_title'] = $user_info['job_title'];
            }
        }
        //
        return $ra;
    }
}

if (!function_exists('getUserHint')) {
    function getUserHint($slug)
    {
        $hints = array(
            'department_supervisor_hint' => 'Please select the "Department Heads". The selected employees will be marked as "Managers".',
            'department_approver_hint' => 'Please select the "Approvers". Any time off created by employees belonging to this department will go to the selected approvers.',
            'department_reporting_manager_hint' => 'Please select the "Reporting Managers". Any employee belonging to this department will report to the selected reporting managers and the performance reviews will be submitted to them.',
            'team_supervisor_hint' => 'Please select the "Team Leads". The selected employees will be marked as "Team Leaders".',
            'team_approver_hint' => 'Please select the "Approvers". Any time off created by employees belonging to this team will go to the selected approvers.',
            'team_reporting_manager_hint' => 'Please select the "Reporting Managers". Any employee belonging to this team will report to the selected reporting managers and the performance reviews will be submitted to them.',
        );
        //
        $hints['video_watched_ems'] = 'You have watched the assigned video.';
        $hints['questionnaire_watched_ems'] = 'You have completed the assigned questionnaire.';
        $hints['video_not_watched_ems'] = 'You have not watched the assigned video.';
        $hints['questionnaire_not_watched_ems'] = 'You have not completed the assigned questionnaire.';
        $hints['visibility_hint'] = '<strong>Visible To Payroll:</strong> Payroll employees can manage this document.<br /><br />The selected roles, departments (Supervisors), teams (Team Leads), and employees will have access to this document.';
        $hints['authorized_managers_hint'] = 'The selected manager will sign this document.';
        $hints['assigner_hint'] = 'Choose employees to approve document assign.';
        $hints['lms_manager_hint'] = 'Select the employees who have access to this department to view the employees in the LMS.';
        $hints['csp_manager_hint'] = 'Select the employees who have access to this department to view the employees in the Compliance Safety Reporting.';

        return isset($hints[$slug]) ? $hints[$slug] : '';
    }
}

//
if (!function_exists('fixEmailAddress')) {
    function fixEmailAddress($email, $type)
    {
        $regx = '/gm/';
        //
        $email_info = explode('@', $email);
        //
        switch ($type) {
            case 'gmail':
                $regx = '/(gm)/i';
                break;
        }
        //        
        if (!preg_match($regx, $email_info[1])) {
            return trim($email);
        }
        //
        $newEmailAddress = trim($email_info[0]) . '@';
        //
        $second_part = $email_info[1];
        $email_extention = explode('.', $second_part);
        //
        if (empty($email_extention[0]) || $email_extention[0] != $type) {
            $newEmailAddress .= "{$type}";
        } else {
            $newEmailAddress .= "{$type}";
        }
        //
        if (empty($email_extention[1])) {
            $newEmailAddress .= ".com";
        } else if ($email_extention[1] != 'com') {
            $newEmailAddress .= ".com";
        } else {
            $newEmailAddress .= ".com";
        }
        //
        return trim($newEmailAddress);
    }
}


if (!function_exists('getUploadFileLinks')) {
    function getUploadFileLinks($file_s3_name)
    {
        //
        $tmp = explode('.', $file_s3_name);
        //
        $file_extension = $tmp[count($tmp) - 1];
        //
        unset($tmp[count($tmp) - 1]);
        $document_file_name = implode('.', $tmp);
        $document_extension = strtolower($file_extension);



        switch (strtolower($file_extension)) {
            case 'pdf':
                $document_print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $document_file_name . '.pdf';
                break;
            case 'csv':
                $document_print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $document_file_name . '.csv';
                break;
            case 'doc':
                $document_print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $document_file_name . '%2Edoc&wdAccPdf=0';
                break;
            case 'docx':
                $document_print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $document_file_name . '%2Edocx&wdAccPdf=0';
                break;
            case 'ppt':
                $document_print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $document_file_name . '.ppt';
                break;
            case 'pptx':
                $document_print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $document_file_name . '.pptx';
                break;
            case 'xls':
                $document_print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $document_file_name . '%2Exls';
                break;
            case 'xlsx':
                $document_print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $document_file_name . '%2Exlsx';
                break;
            case 'jpg':
            case 'jpe':
            case 'jpeg':
            case 'png':
            case 'gif':
            case 'JPG':
            case 'JPE':
            case 'JPEG':
            case 'PNG':
            case 'GIF':
                $document_print_url = base_url("hr_documents_management/print_s3_image") . '/' . $file_s3_name;
                break;
        }

        $document_download_url = base_url("hr_documents_management/download_upload_document") . '/' . $file_s3_name;

        return ['download' => $document_download_url, 'print' => $document_print_url];
    }
}


//
if (!function_exists('has_approval')) {
    function has_approval($roles, $departments, $teams, $employees, $loggedin_user, $payroll = 0)
    {
        // Check for plus
        if ($loggedin_user['access_level_plus']) {
            return true;
        }

        // Check for roles
        if (!empty($roles)) {
            if (in_array(stringToSlug($loggedin_user['access_level']), explode(',', $roles))) {
                return true;
            }
        }

        // Check for employee
        if (!empty($employees)) {
            if (in_array(stringToSlug($loggedin_user['user_id']), explode(',', $employees))) {
                return true;
            }
        }

        // Check for payroll
        if ($payroll == $loggedin_user['pay_plan_flag']) {
            return true;
        }

        // 
        $CI = &get_instance();
        //
        if (!empty($departments)) {
            if (
                $CI->db
                    ->from('departments_management')
                    ->where('FIND_IN_SET("' . ($loggedin_user['user_id']) . '", supervisor) > 0', NULL, NULL)
                    ->where_in('sid', explode(',', $departments))
                    ->where('status', 1)
                    ->where('is_deleted', 0)
                    ->count_all_results()
            ) {
                return true;
            }
        }

        //
        if (!empty($teams)) {
            if (
                $CI->db
                    ->from('departments_team_management')
                    ->where('FIND_IN_SET("' . ($loggedin_user['user_id']) . '", team_lead) > 0', NULL, NULL)
                    ->where_in('sid', explode(',', $teams))
                    ->where('status', 1)
                    ->where('is_deleted', 0)
                    ->count_all_results()
            ) {
                return true;
            }
        }

        //
        return false;
    }
}

//
if (!function_exists('getRoles')) {
    function getRoles()
    {
        return [
            'admin' => 'Admin',
            'employee' => 'Employee',
            'hiring_manager' => 'Hiring Manager',
            'manager' => 'Manager'
        ];
    }
}

if (!function_exists('getDepartmentNameBySID')) {
    function getDepartmentNameBySID($sid)
    {
        $departmentName = '';
        if (!empty($sid)) {

            $CI = &get_instance();
            $CI->db->select('name');
            $CI->db->where('sid', $sid);
            //
            $department_info = $CI->db->get('departments_management')->row_array();

            if (!empty($department_info)) {
                $departmentName = $department_info['name'];
            }
        }

        return $departmentName;
    }
}

if (!function_exists('getTeamNameBySID')) {
    function getTeamNameBySID($sid)
    {
        $teamName = '';
        if (!empty($sid)) {

            $CI = &get_instance();
            $CI->db->select('name');
            $CI->db->where('sid', $sid);
            //
            $team_info = $CI->db->get('departments_team_management')->row_array();

            if (!empty($team_info)) {
                $teamName = $team_info['name'];
            }
        }

        return $teamName;
    }
}


if (!function_exists('addTimeToDate')) {
    function addTimeToDate(
        $date,
        $add,
        $format = 'Y-m-d',
        $type = 'P'
    ) {
        $date = new DateTime($date);
        $date->add(new DateInterval("P{$add}"));
        return $date->format($format);
    }
}

/**
 * Get time off button
 * 
 * @employee Mubashir Ahmed
 * @date     02/07/2021
 * 
 * @param Array  $replaceArray
 * 
 * @return String
 */
if (!function_exists('getButtonForEmail')) {
    function getButtonForEmail($replaceArray)
    {
        return
            str_replace(array_keys($replaceArray), $replaceArray, '<a href="{{url}}" target="_blank" style="padding: 8px 12px; border: 1px solid {{color}};background-color:{{color}};border-radius: 5px;font-size: 14px; color: #ffffff;text-decoration: none;font-weight:bold;display: inline-block; margin-right: 10px;">
        {{text}}             
        </a>');
    }
}


if (!function_exists('getCompletedPercentage')) {
    function getCompletedPercentage($records, $type, $returnAll = false)
    {
        //
        if (empty($records)) {
            return 0;
        }
        //
        $percentage = 0;
        $total = 0;
        $completed = 0;
        //
        foreach ($records as $reviewee) {
            foreach ($reviewee['reviewers'] as $reviewer) {
                //
                if ($type == 'manager' && $reviewer['is_manager'] == 0) {
                    continue;
                }
                if ($type == 'reviewer' && $reviewer['is_manager'] == 1) {
                    continue;
                }
                //
                $total++;
                //
                if ($reviewer['is_completed']) {
                    $completed++;
                }
            }
        }
        //
        if ($returnAll) {
            return [
                'total' => $total,
                'completed' => $completed,
                'percentage' => ceil($completed * $total / 100)
            ];
        }
        //
        return ceil($completed * $total / 100);
    }
}


if (!function_exists('res')) {
    function res($array)
    {
        header("Content-Type: application/json");
        echo json_encode($array);
        exit(0);
    }
}

if (!function_exists('job_title_uri')) {
    function job_title_uri($job)
    {
        //
        $companyName = strtolower(trim($job['CompanyName']));
        //
        $title = ucwords(trim(preg_replace('/' . ($companyName) . '/', '', explode('-', preg_replace('/\s+/i', ' ', trim(strtolower(str_replace(',', '-', $job['Title'])))))[0]))) . '';
        //
        return $title;
    }
}


if (!function_exists('GetVal')) {
    function GetVal($input)
    {
        return !empty($input) ? $input : 'Not Specified';
    }
}

if (!function_exists('subTimeToDate')) {
    function subTimeToDate(
        $date,
        $sub,
        $format = 'Y-m-d',
        $type = 'P'
    ) {
        $date = new DateTime($date);
        $date->sub(new DateInterval("P{$sub}"));
        return $date->format($format);
    }
}

if (!function_exists('GetErrorUrl')) {
    function GetErrorUrl()
    {
        return getCreds('AHR')->API_SERVER_URL . 'report_error';
    }
}

if (!function_exists('addDefaultCategoriesIntoCompany')) {
    function addDefaultCategoriesIntoCompany(
        $company_sid
    ) {
        if (empty($company_sid)) {
            return;
        }
        //
        $CI = &get_instance();
        //
        $CI->db->select('
            name, 
            description,
            status,
            sort_order,
            sid
        ');
        $CI->db->where('company_sid', 0);
        //
        $default_categories = $CI->db->get('documents_category_management')->result_array();

        // Get company industry
        $industryId = $CI->db->select('job_category_industries_sid')->where('sid', $company_sid)->get('users')->row_array()['job_category_industries_sid'];
        //
        if ($industryId != 0) {
            //
            $default_categories2 =
                $CI->db
                    ->select('
                default_categories.category_name as name,
                default_categories.description,
                "1" as status,
                "1" as sort_order,
                categories_document_industry.category_sid as sid
            ')
                    ->join('default_categories', 'default_categories.sid = categories_document_industry.category_sid')
                    ->where('categories_document_industry.industry_sid', $industryId)
                    ->get('categories_document_industry')->result_array();

            //
            $default_categories = array_merge($default_categories, $default_categories2);
        }
        //
        if (empty($default_categories)) {
            return;
        }
        // Only execute below code if system have default categories
        //
        $CI->db->select('sid, name, status, created_by_sid, updated_by_sid, default_category_sid');
        $CI->db->where('company_sid', $company_sid);
        //
        $company_categories = $CI->db->get('documents_category_management')->result_array();
        // When company doesn't have any categories
        if (empty($company_categories)) {
            // Only execute if company have no categories
            foreach ($default_categories as $category) {
                if ($category['status'] == 1) {
                    $data_to_insert = array();
                    $data_to_insert['company_sid'] = $company_sid;
                    $data_to_insert['name'] = $category['name'];
                    $data_to_insert['status'] = $category['status'];
                    $data_to_insert['description'] = $category['description'];
                    $data_to_insert['sort_order'] = $category['sort_order'];
                    $data_to_insert['created_by_sid'] = 0;
                    $data_to_insert['created_date'] = date('Y-m-d H:i:s');
                    $data_to_insert['default_category_sid'] = $category['sid'];
                    //
                    $CI->db->insert('documents_category_management', $data_to_insert);
                }
            }
            //
            return true;
        }
        // Get all company categories names
        $already_exist = array_column($company_categories, "name");
        // Only execute if company already have categories
        foreach ($default_categories as $default_category) {
            //
            $DCName = strtolower(str_replace(" ", "_", $default_category['name']));
            // Check if not already added
            if (!in_array($default_category['name'], $already_exist) && $default_category['status'] == 1) {
                $data_to_insert = array();
                $data_to_insert['company_sid'] = $company_sid;
                $data_to_insert['name'] = $default_category['name'];
                $data_to_insert['status'] = $default_category['status'];
                $data_to_insert['description'] = $default_category['description'];
                $data_to_insert['sort_order'] = $default_category['sort_order'];
                $data_to_insert['created_by_sid'] = 0;
                $data_to_insert['created_date'] = date('Y-m-d H:i:s', strtotime('now'));
                $data_to_insert['default_category_sid'] = $default_category['sid'];
                //
                $CI->db->insert('documents_category_management', $data_to_insert);
            } else {
                //
                foreach ($company_categories as $company_category) {
                    //
                    $CCName = strtolower(str_replace(" ", "_", $company_category['name']));
                    //
                    if ($DCName == $CCName && $company_category['created_by_sid'] == 0 && $company_category['updated_by_sid'] == 0) {
                        if ($default_category['status'] != $company_category['status']) {
                            $data_to_update = array();
                            $data_to_update['updated_by_sid'] = 0;
                            $data_to_update['updated_date'] = date('Y-m-d', strtotime('now'));
                            //
                            $CI->db->where('sid', $company_category['sid']);
                            $CI->db->update('documents_category_management', $data_to_update);
                        }
                    }
                }
            }
        }
    }
}


if (!function_exists('get_employee_latest_joined_date')) {

    function get_employee_latest_joined_date($registration_date, $joining_date, $rehire_date, $format_to_site = false)
    {
        $registration_date = trim($registration_date);
        $joining_date = trim($joining_date);
        $rehire_date = trim($rehire_date);
        //
        $return_date = '';
        //
        if (!empty($rehire_date) && $rehire_date != "0000-00-00") {
            $return_date = $rehire_date;
        } else if (!empty($joining_date) && $joining_date != "0000-00-00") {
            $return_date = $joining_date;
        } else if (!empty($registration_date) && $registration_date != "0000-00-00 00:00:00") {
            $return_date = DateTime::createFromFormat('Y-m-d H:i:s', $registration_date)->format('Y-m-d');
        }

        return $format_to_site == true && !empty($return_date) ? date_with_time($return_date) : $return_date;
    }
}

if (!function_exists('StripFeedTags')) {
    /**
     * Set feed content
     */
    function StripFeedTags($string)
    {
        //
        return strip_tags($string, FEED_STRIP_TAGS);
    }
}

if (!function_exists('getUserEEOC')) {

    function getUserEEOC($type, $sid)
    {
        $CI = &get_instance();
        $CI->db->select('*');
        $CI->db->where('users_type', $type);
        $CI->db->where('application_sid ', $sid);
        $CI->db->where('us_citizen', 'yes');
        //
        $eeoc = $CI->db->get('portal_eeo_form')->row_array();

        if (!empty($eeoc)) {
            return $eeoc;
        } else {
            return array();
        }
    }
}
if (!function_exists('send_full_employment_application')) {

    function send_full_employment_application($company_sid, $user_sid, $user_type)
    {
        $status = check_full_employment_application_module($company_sid);
        //
        if ($status == 1) {
            $CI = &get_instance();
            //
            $user_name = '';
            $user_email = '';
            $company_name = getCompanyNameBySid($company_sid);
            //
            if ($user_type == "applicant") {
                //
                $applicant_info = db_get_applicant_profile($user_sid);
                //
                $user_email = $applicant_info['email'];
                $user_name = get_applicant_name($user_sid);
            } else {
                //
                $employee_info = get_employee_profile_info($user_sid);
                //
                $user_email = $employee_info['email'];
                $user_name = getUserNameBySID($user_sid);
            }
            //
            $verification_key = random_key(80);
            //
            $dataToSave = array();
            $dataToSave['company_sid'] = $company_sid;
            $dataToSave['user_type'] = $user_type;
            $dataToSave['user_sid'] = $user_sid;
            $dataToSave['verification_key'] = $verification_key;
            $dataToSave['status'] = 'sent';
            $dataToSave['status_date'] = date('Y-m-d H:i:s');
            //
            $CI->db->insert('form_full_employment_application', $dataToSave);
            //
            $link_html = '<a style="color: #ffffff; background-color: #0000FF; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; border-radius: 5px; text-align: center; display:inline-block;" target="_blank" href="' . base_url('form_full_employment_application/' . $verification_key) . '">Full Employment Application</a>';
            //
            $replacement_array = array();
            $replacement_array['user_name'] = $user_name;
            $replacement_array['company_name'] = ucwords($company_name);
            $replacement_array['link'] = $link_html;
            //
            $company_email_header_footer = message_header_footer($company_sid, ucwords($company_name));
            log_and_send_templated_email(FULL_EMPLOYMENT_APPLICATION_REQUEST, $user_email, $replacement_array, $company_email_header_footer);
        }
    }
}

if (!function_exists('check_full_employment_application_module')) {

    function check_full_employment_application_module($company_sid)
    {
        $company_status = 0;
        //
        $CI = &get_instance();
        $CI->db->select('sent_to_an_applicant');
        $CI->db->where('user_sid', $company_sid);
        //
        $company_info = $CI->db->get('portal_employer')->row_array();
        //
        if (!empty($company_info)) {
            $company_status = $company_info['sent_to_an_applicant'];
        }
        //
        return $company_status;
    }
}


if (!function_exists('modify_AWS_file_name')) {

    function modify_AWS_file_name($sid, $file_name, $column, $table_name = 'documents_assigned')
    {
        $CI = &get_instance();
        $CI->load->library('aws_lib');
        //
        $file_info = get_file_info($sid, $table_name);
        //
        $new_file_name = modify_document_name($file_name, $file_name, $file_info["company_sid"], $file_info["user_sid"]);
        //
        $CI->aws_lib->copy_object(AWS_S3_BUCKET_NAME, $file_name, AWS_S3_BUCKET_NAME, $new_file_name);
        //
        // $CI->aws_lib->delete_object(AWS_S3_BUCKET_NAME, $file_name, "file");
        //
        $CI->db->where('sid', $sid);
        $CI->db->update($table_name, [$column => $new_file_name]);
        //
        return $new_file_name;
    }
}

if (!function_exists('get_file_info')) {

    function get_file_info($sid, $table_name)
    {
        $columns = 'user_sid';
        //
        if ($table_name == 'eev_documents' || $table_name == 'eev_required_documents') {
            $columns = 'employee_sid AS user_sid';
        }
        //
        $CI = &get_instance();
        $CI->db->select($columns);
        $CI->db->where('sid', $sid);
        $result = $CI->db->get($table_name)->row_array();
        //
        if (!empty($result)) {
            $data['session'] = $CI->session->userdata('logged_in');
            $company_sid = $data['session']['company_detail']['sid'];
            //
            $return_array = array();
            $return_array["user_sid"] = $result["user_sid"];
            $return_array["company_sid"] = $company_sid;
            //
            return $return_array;
        } else {
            return array();
        }
    }
}

//
if (!function_exists('check_user_eeoc_form')) {

    function check_user_eeoc_form($user_sid, $user_type)
    {
        $CI = &get_instance();
        //
        $CI->db->select('*');
        $CI->db->where('users_type', $user_type);
        $CI->db->where('application_sid', $user_sid);
        $CI->db->where('is_expired', 1);
        $CI->db->where('status', 1);
        $CI->db->from('portal_eeo_form');
        //
        $result = $CI->db->get()->row_array();

        if (!empty($result)) {
            return 1;
        } else {
            return 0;
        }
    }
}

if (!function_exists('CheckUserEEOCStatus')) {
    function CheckUserEEOCStatus($type, $sid)
    {
        $CI = &get_instance();
        //
        if (
            $CI->db
                ->where('users_type', $type)
                ->where('application_sid ', $sid)
                ->where('status', 1)
                ->where('is_expired', 1)
                ->count_all_results('portal_eeo_form')
        ) {
            return true;
        }
        //
        return false;
    }
}


if (!function_exists('LoadModel')) {
    function LoadModel($index, $_this)
    {
        //
        $models = [];
        $models['sem'] = 'single/Employee_model';
        $models['scm'] = 'single/Company_model';
        //
        $_this->load->model($models[$index], $index);
    }
}

/**
 * 
 */
if (!function_exists('SnToString')) {
    function SnToString($notation)
    {
        return (string) number_format(
            $notation,
            0,
            '',
            ''
        );
    }
}

/**
 * 
 */
if (!function_exists('ResetRate')) {
    function ResetRate($rate, $rateType = 'Hour')
    {
        //
        $newRate = $rate;
        //
        $rateType = strtolower($rateType);
        //
        if ($rateType == 'year') {
            $newRate = $rate / 52 / WORK_WEEK_HOURS;
        } else if ($rateType == 'month') {
            $newRate = ($rate * 12) / 52 / WORK_WEEK_HOURS;
        } else if ($rateType == 'week') {
            $newRate = $rate / WORK_WEEK_HOURS;
        }
        //
        return $newRate;
    }
}

if (!function_exists('getAPIUrl')) {
    function getAPIUrl($index)
    {
        //
        $urls = [];
        $urls['partner'] = 'partner_managed_company';
        // Employee login URL
        $urls['login'] = 'employee/login';
        // Get Company Account
        $urls['bank_account'] = 'company/bank_account';
        // Get Company Tax
        $urls['tax'] = 'company/tax';
        // Set company locations path
        $urls['locations'] = 'company/locations';
        // Set payperiod paths
        $urls['pay_period'] = 'company/pay_period';
        // Set employee path
        $urls['employees'] = 'employees';
        // Set company path
        $urls['company'] = 'company';
        //
        $urls['job_compensation'] = 'job/compensation';
        //
        if ($index == "employees" || $index == "job_compensation") {
            return getCreds('AHR')->API_BROWSER_URL . (isset($urls[$index]) ? $urls[$index] : '');
        } else {
            return getCreds('AHR')->API_SERVER_URL . (isset($urls[$index]) ? $urls[$index] : '');
        }
        // 
    }
}

if (!function_exists('getAPIKey')) {
    function getAPIKey()
    {
        return isset($_SESSION['API_TOKENS']) ? $_SESSION['API_TOKENS'] : 'testing123keyforAdmin';
    }
}

if (!function_exists('SendResponse')) {
    /**
     * Send response to the client
     * @param integer $type
     * @param string|array $data
     */
    function SendResponse($status, $data = '', $type = 'application/json')
    {
        //
        if ($status == 401) {
            header("Content-type: {$type}");
            header("HTTP/1.0 401 Unauthorized");
            if ($data) {
                echo json_encode($data);
            }
            exit(0);
        }
        //
        if ($status == 400) {
            header("Content-type: {$type}");
            header("HTTP/1.0 400 Bad Request");
            if ($data) {
                echo json_encode($data);
            }
            exit(0);
        }
        //
        if ($type == 'html') {
            $type = 'text/html';
        }
        //
        header("HTTP/1.0 200 OK");
        header("Content-type: {$type}");
        echo json_encode($data);
        exit(0);
    }
}


if (!function_exists('isPayrollOrPlus')) {
    /**
     * Check payroll module permission
     * Only payroll and plus is allowed to 
     * manage payroll module. The function was
     * created on 12/09/2021
     *
     * @param bool $strict Optional- Only checks access level if true
     * @return
     */
    function isPayrollOrPlus($strict = false)
    {
        // Get instance
        $CI = &get_instance();
        // Get the session
        $ses = $CI->session->userdata('logged_in');
        //
        if ($strict) {
            return $ses['employer_detail']['access_level_plus'] == 1 ? true : false;
        }
        // Check if the logged in user
        // is a plus or payroll
        if (
            $ses['employer_detail']['access_level_plus'] == 1 ||
            $ses['employer_detail']['pay_plan_flag'] == 1 ||
            strtolower($ses['employer_detail']['access_level']) == 'payroll'
        ) {
            return true;
        }
        // Don't have permission
        return false;
    }
}

if (!function_exists('redirectHandler')) {
    function redirectHandler($uri, $type = 'auto')
    {
        if (headers_sent()) {
            echo '<!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                        <meta http-equiv = "refresh" content = "2; url = ' . (base_url($uri)) . '" />
            </head>
            </html>';
        } else {
            redirect($uri, $type);
        }
    }
}

if (!function_exists('keepTrackVerificationDocument')) {
    function keepTrackVerificationDocument($user_sid, $user_type, $action, $document_sid, $document_type, $location)
    {
        //
        $data_to_insert = array();
        $data_to_insert['document_sid'] = $document_sid;
        $data_to_insert['document_type'] = $document_type;
        $data_to_insert['user_sid'] = $user_sid;
        $data_to_insert['user_type'] = $user_type;
        $data_to_insert['location'] = $location;
        $data_to_insert['action'] = $action;
        //
        $CI = &get_instance();
        $CI->db->insert('verification_documents_track', $data_to_insert);
    }
}

if (!function_exists('keepSecret')) {
    function keepSecret($string, $type = "end", $char_show = CHARACTER_SHOW)
    {
        $returnSecret = "";
        $length = strlen($string);
        //
        if ($type == "start") {
            return substr($string, 0, $char_show) . str_repeat('*', $length - $char_show);
        } else if ($type == "end") {
            return str_repeat('*', $length - $char_show) . substr($string, $length - $char_show, $char_show);
        } else if ($type == "all") {
            return str_repeat('*', $length - $char_show);
        } else {
            return $string;
        }
    }
}

//
if (!function_exists('getVerificationDocumentSid')) {
    function getVerificationDocumentSid($user_sid, $user_type, $doc_type)
    {
        $CI = &get_instance();
        //
        $CI->db->select('sid');
        //
        if ($doc_type == "w4") {
            $CI->db->where('user_type', $user_type);
            $CI->db->where('employer_sid', $user_sid);
            $CI->db->from('form_w4_original');
        } else if ($doc_type == "w9") {
            $CI->db->where('user_type', $user_type);
            $CI->db->where('user_sid', $user_sid);
            $CI->db->from('applicant_w9form');
        } else if ($doc_type == "i9") {
            $CI->db->where('user_type', $user_type);
            $CI->db->where('user_sid', $user_sid);
            $CI->db->from('applicant_i9form');
        } else if ($doc_type == "eeoc") {
            $CI->db->where('users_type', $user_type);
            $CI->db->where('application_sid', $user_sid);
            $CI->db->from('portal_eeo_form');
        }
        //
        $result = $CI->db->get()->row_array();

        if (!empty($result)) {
            return $result['sid'];
        } else {
            return 0;
        }
    }
}
if (!function_exists('GetFileContent')) {
    /**
     * Check and add file with data
     * 
     * @param string fullpath of the file with filename
     * @param any    the default data that needs to be on file
     * 
     * @return string content of the file 
     */
    function GetFileContent($filename, $data = "{}")
    {
        //
        $fileData = '';
        //
        if (file_exists($filename)) {
            //
            $file = fopen($filename, 'r');
            $fileData = fread($file, filesize($filename));
            fclose($file);
        }
        //
        if (empty($fileData)) {
            $file = fopen($filename, 'w');
            fwrite($file, $data);
            fclose($file);
            //
            $fileData = $data;
        }
        //
        return $fileData;
    }
}

if (!function_exists('_m')) {
    /**
     * Add environment check to the assets
     * and add tags for version
     * 
     * @param string $string
     * assets/js/script
     * assets/css/style
     * @param string $type
     * js|css
     * @param string $version
     * 1.0.0
     * @return
     */
    function _m($string, $type = 'js', $version = '1.0.0')
    {
        // get extension
        $d = getStaticFileVersion($string);
        //
        if ($d) {
            return
                $string . (strpos($string, '.min') === false ? MINIFIED : '') . '.' . $type . '?v=' . (MINIFIED === '.min' ? $d[$type] : time());
        }
        //
        return $string . (strpos($string, '.min') === false ? MINIFIED : '') . '.' . $type . '?v=' . (MINIFIED === '.min' ? $version : time());
    }
}


if (!function_exists('GetEmployeeStatusText')) {
    function GetEmployeeStatusText($index)
    {
        //
        $arr = [];
        $arr[1] = 'Terminated';
        $arr[2] = 'Retired';
        $arr[3] = 'Deceased';
        $arr[4] = 'Suspended';
        $arr[5] = 'Active';
        $arr[6] = 'Inactive';
        $arr[7] = 'Leave';
        $arr[8] = 'Rehired';
        $arr[9] = 'Transferred';
        //
        return $arr[$index];
    }
}

if (!function_exists('GetEmployeeStatus')) {
    /**
     * Get employee last status
     * 
     * @param string $lastStatusText
     * @param number $active
     * @return
     */
    function GetEmployeeStatus($lastStatusText, $active)
    {
        if (!$active && strtolower($lastStatusText) !== "terminated") {
            return "De-activated";
        }
        //
        if (strtolower($lastStatusText) === 'rehired') {
            return 'Active';
        }
        //
        return ucwords($lastStatusText ? $lastStatusText : ($active ? 'Active' : 'De-activated'));
    }
}

if (!function_exists('check_is_employee_exist_or_transfer')) {
    function check_is_employee_exist_or_transfer($company_sid, $applicant_sid, $email)
    {
        $CI = &get_instance();
        //
        $CI->db->select('hired_sid, hired_status');
        $CI->db->where('employer_sid', $company_sid);
        $CI->db->where('email', $email);
        $CI->db->where('sid <>', $applicant_sid);
        $CI->db->order_by('sid', 'desc');
        $CI->db->limit(1);
        $CI->db->from('portal_job_applications');
        $applicant_info = $CI->db->get()->row_array();
        //
        if (empty($applicant_info)) {
            return "no_record_found";
        }
        //
        if ($applicant_info["hired_status"] == 0) {
            return "record_found";
        }
        //
        $employee_sid = $applicant_info["hired_sid"];
        //
        $CI->db->where('from_company_sid', $company_sid);
        $CI->db->where('previous_employee_sid', $employee_sid);
        $CI->db->from('employees_transfer_log');
        //

        if ($CI->db->count_all_results()) {
            return "no_record_found";
        }
        // 
        $CI->db->select('parent_sid');
        $CI->db->where('email', $email);
        $CI->db->order_by('sid', 'DESC');
        $CI->db->limit(1);
        $CI->db->from('users');
        //
        $employee_info = $CI->db->get()->row_array();
        //
        if (!empty($employee_info) && $employee_info["parent_sid"] == $company_sid) {
            return "record_found";
        }
        //
        return "no_record_found";
    }
}

if (!function_exists('GetHireDate')) {
    /**
     * Get the new joined date
     * 
     * @param string $rd  Rehire date
     * @param string $jd  Joining date
     * @param string $red Registration date
     * 
     * @return
     */
    function GetHireDate(
        $rd,
        $jd,
        $red
    ) {
        //
        if ($red) {
            return $red;
        } else if ($jd) {
            return $jd;
        } else if ($rd) {
            return DateTime::createfromformat('Y-m-d H:i:s', $rd)->format('Y-m-d');
        }
    }
}

if (!function_exists('check_company_status')) {
    function check_company_status($company_sid)
    {
        $CI = &get_instance();
        $CI->db->select('active');
        $CI->db->where('sid', $company_sid);
        $result = $CI->db->get('users')->row_array();
        //
        return $result["active"];
    }
}

if (!function_exists('get_user_gender')) {
    function get_user_gender($user_sid, $user_type)
    {
        $table = "users";
        //
        if ($user_type == "applicant") {
            $table = "portal_job_applications";
        }
        //
        $CI = &get_instance();
        $CI->db->select('gender');
        $CI->db->where('sid', $user_sid);
        //
        $records_obj = $CI->db->get($table);
        $records_arr = $records_obj->row_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return ucfirst($records_arr["gender"]);
        } else {
            return "";
        }
    }
}

if (!function_exists('update_user_gender')) {
    function update_user_gender($user_sid, $user_type, $dataToUpdate)
    {
        $table = "users";
        //
        if ($user_type == "applicant") {
            $table = "portal_job_applications";
        }
        //
        $CI = &get_instance();
        $CI->db->where('sid', $user_sid);
        $CI->db->update($table, $dataToUpdate);
    }
}

if (!function_exists('checkOnboardingNotification')) {
    function checkOnboardingNotification($applicant_sid)
    {
        $CI = &get_instance();
        $CI->db->select('email_sent_date');
        $CI->db->where('applicant_sid', $applicant_sid);
        //
        $records_obj = $CI->db->get("onboarding_applicants");
        $records_arr = $records_obj->row_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            if (!empty($records_arr["email_sent_date"])) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }
}

if (!function_exists('onboardingNotificationPendingText')) {
    function onboardingNotificationPendingText($applicant_sid)
    {
        $CI = &get_instance();
        $CI->db->select('employer_sid, onboarding_start_date');
        $CI->db->where('applicant_sid', $applicant_sid);
        //
        $record_obj = $CI->db->get("onboarding_applicants");
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            $name = getUserNameBySID($record_arr["employer_sid"]);
            $date = date_with_time($record_arr["onboarding_start_date"]);
            //
            return $name . " has created setup onboarding on " . $date . " but did not send an Onboarding notification to the applicant.";
        } else {
            return "";
        }
    }
}

if (!function_exists('GetTimeDifferenceInSeconds')) {
    function GetTimeDifferenceInSeconds($d1, $d2)
    {
        //
        $d1 = new DateTime($d1);
        $d2 = new DateTime($d2);
        //
        $diff = $d1->diff($d2);
        //
        $daysInSecs = $diff->format('%r%a') * 24 * 60 * 60;
        $hoursInSecs = $diff->h * 60 * 60;
        $minsInSecs = $diff->i * 60;
        //
        return $daysInSecs + $hoursInSecs + $minsInSecs + $diff->s;
    }
}

if (!function_exists('GetHMSFromSeconds')) {
    function GetHMSFromSeconds($totalSeconds)
    {

        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds - $hours * 3600) / 60);
        $seconds = floor($totalSeconds - ($hours * 3600) - ($minutes * 60));
        // Pluralize
        $hours = (strlen($hours) === 1 ? '0' : '') . $hours;
        $minutes = (strlen($minutes) === 1 ? '0' : '') . $minutes;
        $seconds = (strlen($seconds) === 1 ? '0' : '') . $seconds;
        //
        return [
            'hours' => $hours,
            'minutes' => $minutes,
            'seconds' => $seconds
        ];
    }
}

if (!function_exists('CalculateTime')) {
    /**
     * Calculate time for DB
     * 
     * @param array $list
     * @param int $employee_sid
     * @return array
     */
    function CalculateTime($lists, $employee_sid)
    {
        //
        $ra = [
            'total_minutes' => 0,
            'total_worked_minutes' => 0,
            'total_break_minutes' => 0,
            'total_overtime_minutes' => 0
        ];
        //
        $lastAction = $lists[0]['action'];
        $lastActionDT = $lists[0]['action_date_time'];
        //
        $lists = array_reverse($lists);
        $shiftTime_info = get_user_shiftTime($employee_sid);
        $shift_hours = $shiftTime_info["user_shift_hours"];
        $shift_minute = $shiftTime_info["user_shift_minutes"];
        $total_shift_minutes = ($shift_minute + ($shift_hours * 60)) * 60;
        //
        $tz = !$shiftTime_info['timezone'] ? STORE_DEFAULT_TIMEZONE_ABBR : $shiftTime_info['timezone'];
        //
        $ra['total_minutes'] = GetTotalTime($lists, 'clock_in', 'clock_out', $tz);
        $ra['total_break_minutes'] = GetTotalTime($lists, 'break_in', 'break_out', $tz);
        //
        $lastActionDT = reset_timezone([
            'datetime' => $lastActionDT,
            'from_format' => DB_DATE_WITH_TIME,
            'format' => DB_DATE_WITH_TIME,
            'from_zone' => STORE_DEFAULT_TIMEZONE_ABBR,
            'new_zone' => $tz
        ])['date_time_string'];
        //
        $cdt = reset_timezone([
            'datetime' => date('Y-m-d H:i:s', strtotime('now')),
            'from_format' => DB_DATE_WITH_TIME,
            'format' => DB_DATE_WITH_TIME,
            'from_zone' => STORE_DEFAULT_TIMEZONE_ABBR,
            'new_zone' => $tz
        ])['date_time_string'];
        //
        $cd = formatDateToDB($cdt, DB_DATE_WITH_TIME, DB_DATE);
        //
        if ($lastAction == 'clock_in' || $lastAction == 'break_in') {
            $ra[$lastAction == 'clock_in' ? 'total_minutes' : 'total_break_minutes'] += GetTimeDifferenceInSeconds(
                strpos($lastActionDT, $cd) !== FALSE
                ? $cdt
                : formatDateToDB($lastActionDT, DB_DATE_WITH_TIME, DB_DATE) . ' 23:59:59',
                $lastActionDT
            );
        }
        //
        if ($lastAction === 'break_in') {
            $ra['total_minutes'] += $ra['total_break_minutes'];
        }
        // Total worked hours
        $ra['total_worked_minutes'] = $ra['total_minutes'] - $ra['total_break_minutes'];
        //
        if ($ra['total_worked_minutes'] < 0) {
            $ra['total_worked_minutes'] = 0;
        }
        //
        if ($ra['total_worked_minutes'] > $total_shift_minutes) {
            $ra['total_overtime_minutes'] = $ra['total_worked_minutes'] - $total_shift_minutes;
        }
        //
        return $ra;
    }
}

if (!function_exists('GetTotalTime')) {
    /**
     * Calculate time for DB
     * 
     * @param array $list
     * @param string $t1
     * @param string $t2
     * @return array
     */
    function GetTotalTime($lists, $t1, $t2)
    {
        //
        $total = 0;
        //
        $lastAction = '';
        //
        $lastDateTime = '';
        // For worked time
        foreach ($lists as $list) {
            // For clock ins
            if (empty($lastAction) || $list['action'] == $t1) {
                $lastAction = $list['action'];
                $lastDateTime = $list['action_date_time'];
            }

            if ($lastAction == $t2 && $list['action'] == $t1) {
                $lastAction = $list['action'];
                $lastDateTime = $list['action_date_time'];
            }
            //
            if ($lastAction == $t1 && $list['action'] == $t2) {
                //
                $total += GetTimeDifferenceInSeconds($lastDateTime, $list['action_date_time']);
                //
                $lastAction = $t2;
                $lastDateTime = '';
            }
        }
        return $total;
    }
}

if (!function_exists('GetActionColor')) {
    /**
     * Calculate time for DB
     * 
     * @param array $list
     * @return array
     */
    function GetActionColor($action)
    {
        //
        $list = [];
        $list['clock_in'] = 'success';
        $list['clock_out'] = 'danger';
        $list['break_in'] = 'warning';
        $list['break_out'] = 'black';
        //
        return $list[$action];
    }
}

if (!function_exists('ModifyDate')) {
    /**
     * Modify date time
     * 
     * @param string $date
     * @param string $modification
     * @param string $format
     * 
     * @return string
     */
    function ModifyDate($date, $modification, $format)
    {
        $stop_date = new DateTime($date);
        $stop_date->modify($modification);
        return $stop_date->format($format);
    }
}

if (!function_exists('ShowInfo')) {
    function ShowInfo($msg, $options = [])
    {
        //
        $props = [];
        $props['icon'] = 'fa-info-circle';
        $props['fontSize'] = 'csF16';
        $props['color'] = 'text-danger';
        //
        $props = array_merge($props, $options);
        //
        return '<strong class="' . ($props['color']) . ' ' . ($props['fontSize']) . '"><i class="fa ' . ($props['icon']) . '" aria-hidden="true"></i>&nbsp;<em>' . ($msg) . '</em></strong>';
    }
}

if (!function_exists('DistanceBTWLatLon')) {
    /**
     * Get distance between two lat lons
     * 
     * @param string $lat1
     * @param string $lon1
     * @param string $lat2
     * @param string $lon2
     * 
     * @return array
     */
    function DistanceBTWLatLon($lat1, $lon1, $lat2, $lon2)
    {
        //
        $ra = [
            'meters' => 0,
            'km' => 0,
            'text' => '0 KM'
        ];
        //
        if ($lat1 == 0 || $lat2 == 0) {
            return $ra;
        }
        //
        $R = 6371e3; // metres
        $l1 = $lat1 * pi() / 180; // φ, λ in radians
        $l2 = $lat2 * pi() / 180;
        $ll1 = ($lat2 - $lat1) * pi() / 180;
        $ll2 = ($lon2 - $lon1) * pi() / 180;
        //
        $a = sin($ll1 / 2) * sin($ll1 / 2) +
            cos($l1) * cos($l2) *
            sin($ll2 / 2) * sin($ll2 / 2);
        //
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $d = $R * $c; // in metres
        //
        $ra['meters'] = $d;
        $ra['km'] = ceil($d / 1000);
        $ra['text'] = $ra['km'] . ' KM';
        //
        return $ra;
    }
}

if (!function_exists('GetCleanedAction')) {
    /**
     * Get action for front end
     * 
     * @param string $action
     * @return string
     */
    function GetCleanedAction($action)
    {
        return ucwords(str_replace('_', ' ', $action));
    }
}

if (!function_exists('getTimeZone')) {
    function getTimeZone($company_sid, $employee_sid)
    {
        $CI = &get_instance();
        $CI->db->select('timezone');
        $CI->db->where('sid', $employee_sid);
        //
        $employee_info = $CI->db->get('users')->row_array();

        if (!empty($employee_info['timezone'])) {
            return $employee_info['timezone'];
        } else {
            $CI->db->select('timezone');
            $CI->db->where('sid', $company_sid);
            //
            $company_info = $CI->db->get('users')->row_array();
            //
            if (!empty($company_info['timezone'])) {
                return $company_info['timezone'];
            } else {
                return STORE_DEFAULT_TIMEZONE_ABBR;
            }
        }
    }
}

if (!function_exists('ConvertDateTime')) {
    function ConvertDateTime($company_sid, $employee_sid, $date, $format = DB_DATE, $toggle = false)
    {
        $timezone = getTimeZone($company_sid, $employee_sid);
        //
        $Data = [
            'datetime' => $date,
            'format' => $format,
            'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
            'new_zone' => $timezone,
            '_this' => get_instance()
        ];
        //        
        if ($toggle) {
            $Data['from_timezone'] = $timezone;
            $Data['new_zone'] = STORE_DEFAULT_TIMEZONE_ABBR;
        }
        //
        return ["modified" => reset_datetime($Data), "original" => $date];
    }
}

if (!function_exists('get_user_shiftTime')) {
    function get_user_shiftTime($employee_sid)
    {
        $CI = &get_instance();
        $CI->db->select('user_shift_minutes, user_shift_hours, timezone');
        $CI->db->where('sid', $employee_sid);
        //
        $records_obj = $CI->db->get("users");
        $records_arr = $records_obj->row_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr;
        } else {
            return "";
        }
    }
}

if (!function_exists('GetParams')) {
    function GetParams($param)
    {
        //
        $CI = &get_instance();
        //
        $get = $CI->input->get(NUll, true);
        //
        $r = '?';
        //
        if ($get) {
            //
            foreach ($get as $k => $v) {
                //
                if (is_array($v)) {
                    $r .= $k . '=' . implode(',', $v) . '&';
                } else {
                    $r .= $k . '=' . $v . '&';
                }
            }
        }
        //
        if ($param) {
            $r .= $param;
        }
        //
        return rtrim($r, '&');
    }
}

if (!function_exists('GetAttendanceActionText')) {
    /**
     * Get the text for attendace
     * 
     * @param string $action
     * @return
     */
    function GetAttendanceActionText($action)
    {
        //
        switch ($action):
            case "clock_in":
                $status = 'clocked in';
                break;
            case "clock_out":
                $status = 'clocked out';
                break;
            case "break_in":
                $status = 'started your break';
                break;
            case "break_out":
                $status = 'ended your break';
                break;
            default:
                $status = GetCleanedAction($action);
        endswitch;

        return $status;
    }
}

if (!function_exists('GetEmployeeShiftTime')) {
    /**
     * Get employee shift time in minutes/seconds
     * 
     * @param number $h
     * @param number $m
     * @param string $r (m|s|t)
     * 
     * @return
     */
    function GetEmployeeShiftTime($h, $m, $r = 's')
    {
        //
        if ($r === 't') {
            return $h . ' H' . ($m ? ' & ' . $m . ' M' : '');
        }
        //
        $nv = ($m + ($h * 60));
        //
        return $r === 'm' ? $nv : $nv * 60;
    }
}

if (!function_exists('isImage')) {
    /**
     * Check if the file is an image
     * 
     * @param string $str
     * @return
     */
    function isImage($str)
    {
        return in_array(
            strtolower(pathinfo($str, PATHINFO_EXTENSION)),
            [
                'png',
                'jpg',
                'jpeg',
                'gif',
                'webp'
            ]
        );
    }
}

if (!function_exists('get_user_assign_group_status')) {
    /**
     * Check the status og the assign group
     * 
     * @param number $group_sid
     * @param string $user_type
     * @param number $user_sid
     * @return
     * 
     */
    function get_user_assign_group_status($group_sid, $user_type, $user_sid)
    {
        $CI = &get_instance();
        $CI->db->select('assign_status');
        $CI->db->where('group_sid ', $group_sid);
        //
        if ($user_type == 'employee') {
            $CI->db->where('employer_sid ', $user_sid);
        } else if ($user_type == 'applicant') {
            $CI->db->where('applicant_sid ', $user_sid);
        }
        //
        $records_obj = $CI->db->get("documents_group_2_employee");
        $records_arr = $records_obj->row_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr["assign_status"];
        } else {
            return 0;
        }
    }
}

if (!function_exists('is_active_employee_or_company')) {

    function is_active_employee_or_company($user_email)
    {

        $CI = &get_instance();
        $CI->db->select('parent_sid, active, terminated_status, archived');
        $CI->db->where('email', $user_email);
        //
        $records_obj = $CI->db->get("users");
        $records_arr = $records_obj->row_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            if ($records_arr['parent_sid'] == 0 && $records_arr['active'] == 1) {
                return 1;
            } else if ($records_arr['parent_sid'] != 0 && $records_arr['active'] == 1 && $records_arr['terminated_status'] == 0 && $records_arr['archived'] == 0) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return 1;
        }
    }
}

if (!function_exists('verifyCaptcha')) {
    /**
     * 
     */
    function verifyCaptcha($token)
    {
        //
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://www.google.com/recaptcha/api/siteverify',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('secret' => getCreds('AHR')->GOOGLE_CAPTCHA_API_SECRET_V2, 'response' => $token)
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return json_decode($response, true);
    }
}


if (!function_exists('check_document_completed')) {

    function check_document_completed($assigned_document)
    {
        $is_magic_tag_exist = 0;
        $is_document_completed = 0;

        if (!empty($assigned_document['document_description']) && ($assigned_document['document_type'] == 'generated' || $assigned_document['document_type'] == 'hybrid_document')) {
            $document_body = $assigned_document['document_description'];
            $magic_codes = array('{{signature}}', '{{inital}}', '{{short_text_required}}', '{{text_required}}', '{{text_area_required}}', '{{checkbox_required}}');

            if (str_replace($magic_codes, '', $document_body) != $document_body) {
                $is_magic_tag_exist = 1;
            }
        }

        if ($assigned_document['document_type'] != 'offer_letter') {
            //
            if ($assigned_document['status'] == 1) {
                if ($assigned_document['acknowledgment_required'] || $assigned_document['download_required'] || $assigned_document['signature_required'] || $is_magic_tag_exist) {

                    if ($assigned_document['acknowledgment_required'] == 1 && $assigned_document['download_required'] == 1 && $assigned_document['signature_required'] == 1) {
                        if ($assigned_document['uploaded'] == 1) {
                            $is_document_completed = 1;
                        } else {
                            $is_document_completed = 0;
                        }
                    } else if ($assigned_document['acknowledgment_required'] == 1 && $assigned_document['download_required'] == 1) {
                        if ($is_magic_tag_exist == 1) {
                            if ($assigned_document['uploaded'] == 1) {
                                $is_document_completed = 1;
                            } else {
                                $is_document_completed = 0;
                            }
                        } else if ($assigned_document['uploaded'] == 1) {
                            $is_document_completed = 1;
                        } else if ($assigned_document['acknowledged'] == 1 && $assigned_document['downloaded'] == 1) {
                            $is_document_completed = 1;
                        } else {
                            $is_document_completed = 0;
                        }
                    } else if ($assigned_document['acknowledgment_required'] == 1 && $assigned_document['signature_required'] == 1) {
                        if ($assigned_document['uploaded'] == 1) {
                            $is_document_completed = 1;
                        } else {
                            $is_document_completed = 0;
                        }
                    } else if ($assigned_document['download_required'] == 1 && $assigned_document['signature_required'] == 1) {
                        if ($assigned_document['uploaded'] == 1) {
                            $is_document_completed = 1;
                        } else {
                            $is_document_completed = 0;
                        }
                    } else if ($assigned_document['acknowledgment_required'] == 1) {
                        if ($assigned_document['acknowledged'] == 1) {
                            $is_document_completed = 1;
                        } else if ($assigned_document['uploaded'] == 1) {
                            $is_document_completed = 1;
                        } else {
                            $is_document_completed = 0;
                        }
                    } else if ($assigned_document['download_required'] == 1) {
                        if ($assigned_document['downloaded'] == 1) {
                            $is_document_completed = 1;
                        } else if ($assigned_document['uploaded'] == 1) {
                            $is_document_completed = 1;
                        } else {
                            $is_document_completed = 0;
                        }
                    } else if ($assigned_document['signature_required'] == 1) {
                        if ($assigned_document['uploaded'] == 1) {
                            $is_document_completed = 1;
                        } else {
                            $is_document_completed = 0;
                        }
                    } else if ($is_magic_tag_exist == 1) {
                        if ($assigned_document['user_consent'] == 1) {
                            $is_document_completed = 1;
                        } else {
                            $is_document_completed = 0;
                        }
                    }

                    if ($is_document_completed == 1) {
                        return "Completed";
                    } else {
                        return "not_completed";
                    }
                } else {
                    return "Completed";
                }
            } else {
                return "not_completed";
            }
        }
    }
}



if (!function_exists('get_documents_assigned_data')) {

    function get_documents_assigned_data($document_sid, $employee_id, $employee)
    {

        $CI = &get_instance();
        $CI->db->where('document_sid', $document_sid);
        $CI->db->where('user_sid', $employee_id);
        $CI->db->where('user_type', $employee);
        $record_obj = $CI->db->get('documents_assigned');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();
        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }
}
if (!function_exists('get_all_group_documents')) {
    function get_all_group_documents($group_sid, $excludeArchivedDocuments = false)
    {
        $CI = &get_instance();
        $CI->db->select('documents_2_group.*,documents_management.document_title');
        $CI->db->join('documents_management', 'documents_management.sid = documents_2_group.document_sid', "inner");
        $CI->db->where('group_sid', $group_sid);
        $CI->db->where('documents_management.is_specific', 0);
        if ($excludeArchivedDocuments) {
            $CI->db->where('documents_management.archive', 0);
        }
        $record_obj = $CI->db->get('documents_2_group');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            //
            $tmp = [];
            foreach ($record_arr as $rc) {
                if (!$tmp[$rc["document_sid"]]) {
                    $tmp[$rc["document_sid"]] = $rc;
                }
            }
            return array_values($tmp);
            // return $record_arr;
        } else {
            return array();
        }
    }
}

if (!function_exists('check_document_completed_date')) {
    function check_document_completed_date($assigned_document)
    {
        //
        $completed_date = '';
        //
        if ($assigned_document['status'] == 1) {
            //
            $is_magic_tag_exist = 0;
            //
            if (!empty($assigned_document['document_description']) && ($assigned_document['document_type'] == 'generated' || $assigned_document['document_type'] == 'hybrid_document')) {
                $document_body = $assigned_document['document_description'];
                $magic_codes = array('{{signature}}', '{{inital}}', '{{short_text_required}}', '{{text_required}}', '{{text_area_required}}', '{{checkbox_required}}');

                if (str_replace($magic_codes, '', $document_body) != $document_body) {
                    $is_magic_tag_exist = 1;
                }
            }
            //
            if ($assigned_document['acknowledgment_required'] || $assigned_document['download_required'] || $assigned_document['signature_required'] || $is_magic_tag_exist) {

                if ($assigned_document['signature_required'] == 1) {
                    if ($assigned_document['document_type'] == "uploaded") {
                        $completed_date = $assigned_document['uploaded_date'];
                    } else {
                        $completed_date = $assigned_document['signature_timestamp'];
                    }
                } else if ($assigned_document['acknowledgment_required'] == 1) {
                    $completed_date = $assigned_document['acknowledged_date'];
                } else if ($assigned_document['download_required'] == 1) {
                    $completed_date = $assigned_document['downloaded_date'];
                } else if ($is_magic_tag_exist == 1) {
                    $completed_date = $assigned_document['signature_timestamp'];
                }
            } else {
                $completed_date = $assigned_document['assigned_date'];
            }
        }
        return $completed_date;
    }
}

if (!function_exists('get_document_action_date')) {
    function get_document_action_date($assigned_document, $type)
    {
        //
        $return_date = '';
        //
        if ($assigned_document['status'] == 1) {
            if ($type == "assigned") {
                $return_date = $assigned_document['assigned_date'];
            } else if ($type == "completed") {
                $return_date = check_document_completed_date($assigned_document);
            }
        }
        //
        if (!empty($return_date) && $return_date != '0000-00-00 00:00:00') {
            $CI = &get_instance();
            $return_date = reset_datetime(array('datetime' => $return_date, '_this' => $CI));
        }
        //
        return $return_date;
    }
}

if (!function_exists('generateEmailButton')) {
    /**
     * Generate button for email
     * 
     * @author Mubashir Ahmed
     * @version 1.0
     * 
     * @param string $bgColor
     * @param string $link
     * @param string $text
     * @param string $color
     * 
     * @return string
     */
    function generateEmailButton(
        $bgColor = '#0000FF',
        $link = '',
        $text = 'Click Here',
        $color = '#ffffff'
    ) {
        return '<a style="color: ' . ($color) . '; background-color: ' . ($bgColor) . '; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; border-radius: 5px; text-align: center; display:inline-block;" target="_blank" href="' . base_url($link) . '">' . ($text) . '</a>';
    }
}

if (!function_exists('get_encryption_initialize_array')) {
    /**
     * CReturn encryption initialize array
     *
     * @return
     * 
     */
    function get_encryption_initialize_array()
    {
        $CI = &get_instance();
        //
        return array(
            'cipher' => 'aes-256',
            'mode' => 'ctr',
            'key' => $CI->config->item('encryption_key'),
            'driver' => 'openssl'
        );
    }
}


if (!function_exists('hasPermissionToDocument')) {
    /**
     * Check the document permission
     * 
     * 
     * @param array   $allowedEmployees
     * @param array   $allowedDepartments
     * @param array   $allowedTeams
     * @param array   $allowedRoles
     * @param integer $isConfidential
     * @param array   $confidentialEmployees
     * @param integer $isAdminPlus
     * @param integer $isPayPlan
     * @param string  $role
     * @param array   $departments
     * @param array   $teams
     * @param integer $employeeId
     * 
     * @return booloan
     */
    function hasPermissionToDocument(
        $allowedEmployees,
        $allowedDepartments,
        $allowedTeams,
        $allowedRoles,
        $isConfidential,
        $confidentialEmployees,
        $isAdminPlus,
        $isPayPlan,
        $role,
        $departments,
        $teams,
        $employeeId
    ) {
        // Check the confidential as priority
        if ($confidentialEmployees) {
            //
            if ($confidentialEmployees == "-1" || in_array($employeeId, explode(',', $confidentialEmployees))) {
                return true;
            }
            //
            return false;
        }
        // Check for plus
        if ($isAdminPlus || $isPayPlan) {
            return true;
        }
        // Check for the role
        if (!empty($allowedRoles) && in_array($role, explode(',', $allowedRoles))) {
            return true;
        }
        // Check for the employee
        if (
            !empty($allowedEmployees) &&
            (in_array($employeeId, explode(',', $allowedEmployees)) ||
                in_array("-1", explode(',', $allowedEmployees))
            )
        ) {
            return true;
        }
        // Check for the department
        if (
            !empty($departments) &&
            !empty($allowedDepartments) &&
            (in_array("-1", explode(',', $allowedDepartments)) ||
                array_intersect($departments, $allowedDepartments)
            )
        ) {
            return true;
        }
        // Check for the teams
        if (
            !empty($teams) &&
            !empty($allowedTeams) &&
            (in_array("-1", explode(',', $allowedTeams)) ||
                array_intersect($teams, $allowedTeams)
            )
        ) {
            return true;
        }
        //
        return false;
    }
}


if (!function_exists('addColumnsForDocumentAssigned')) {
    /**
     * 
     */
    function addColumnsForDocumentAssigned(
        &$dataArray,
        $document = []
    ) {
        // Get CI instance
        $_this = &get_instance();
        //
        $data = $document ? $document : $_this->input->post(null, true);
        //
        if (isset($data['setting_is_confidential'])) {
            $data['is_confidential'] = $data['setting_is_confidential'];
        }
        //
        if (isset($data['confidentialSelectedEmployees'])) {
            $data['confidential_employees'] = $data['confidentialSelectedEmployees'];
        }
        //
        $dataArray['is_confidential'] = ($data['is_confidential'] == 'on' || $data['is_confidential'] == 1) ? 1 : 0;
        //
        $dataArray['confidential_employees'] = NULL;
        //
        if ($data['confidential_employees']) {
            $dataArray['confidential_employees'] = $data['confidential_employees'];
        }
    }
}


if (!function_exists('loadFileData')) {
    function loadFileData($filePath)
    {
        //
        $h = fopen($filePath, 'r');
        //
        $contents = fread($h, filesize($filePath));
        //
        fclose($h);
        //
        return $contents;
    }
}


if (!function_exists('checkDateFormate')) {
    /**
     * Check the date format to 
     * avoid 500
     * 
     * @param string $dateIm
     * @param string $format
     * @return string
     */
    function checkDateFormate($dateIn, $format = 'm-d-Y')
    {
        // Check for empty
        if (empty($dateIn) || $dateIn == "N/A") {
            return "";
        }
        // Check for valid syntax
        if (!preg_match('/[0-9]{2}-[0-9]{2}-[0-9]{4}/', $dateIn)) {
            return "";
        }
        //
        $dateArray = explode("-", $dateIn);
        //
        if ($dateArray[0] > 12) {
            return "";
        }
        //
        return $dateIn;
    }
}


if (!function_exists('getAssetTag')) {
    /**
     * Set the tage depending on the
     * devlopment mode
     *
     * @param string $tag
     * @return string
     */
    function getAssetTag($tag = '1.0.1')
    {
        return MINIFIED === '.min' ? $tag : time();
    }
}
/**
 * 
 */
if (!function_exists('checkDontHireText')) {
    function checkDontHireText($empIds)
    {
        $CI = &get_instance();
        //
        $records =
            $CI->db
                ->select('
            terminated_employees.employee_sid,
            terminated_employees.termination_date,
            terminated_employees.employee_status,
            terminated_employees.do_not_hire,
            ' . (getUserFields()) . '
        ')
                ->join('users', 'users.sid = terminated_employees.employee_sid')
                ->where_in('terminated_employees.employee_sid', $empIds)
                ->order_by('terminated_employees.sid', 'DESC')
                ->get('terminated_employees')
                ->result_array();
        //
        if (empty($records)) {
            return [];
        }
        //
        $tmp = [];
        //
        foreach ($records as $record) {
            // Check if last record was found
            if (!isset($tmp[$record['employee_sid']])) {
                //
                $tmp[$record['employee_sid']] = [];
                //
                if ($record['employee_status'] == 1 && $record['do_not_hire'] == 1) {
                    //
                    $tmp[$record['employee_sid']] = [
                        'full_name' => remakeEmployeeName($record),
                        'action_date' => formatDateToDB($record['termination_date'], DB_DATE, DATE)
                    ];
                }
            }
        }
        //
        return $tmp;
    }
}

if (!function_exists('doNotHireWarning')) {
    /**
     * Employee do not hire
     *
     * @param int   $employeeId
     * @param array $list
     * @param int   $fontSize
     * @return array
     */
    function doNotHireWarning($employeeId, $list, $fontSize = 20)
    {
        //
        $returnArray = [
            'message' => '',
            'row' => ''
        ];
        //
        if (empty($list)) {
            return $returnArray;
        }
        // Check if employee exists
        if (!isset($list[$employeeId]) || empty($list[$employeeId])) {
            return $returnArray;
        }
        //
        $returnArray['message'] = '<p class="text-danger" style="font-size: ' . $fontSize . 'px;"><strong>DO NOT HIRE this person<strong> <i class="fa fa-info-circle text-danger" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="' . ($list[$employeeId]['full_name']) . ' marked this employee as DO NOT HIRE on the ' . ($list[$employeeId]['action_date']) . '"></i></p>';
        $returnArray['row'] = 'bg-danger';
        //
        return $returnArray;
    }
}


if (!function_exists('formatDateBeforeProcess')) {
    /**
     * Format date to correct
     *
     * @param string $date
     * @param string $format
     * @return string
     */
    function formatDateBeforeProcess(
        string $date,
        string $format
    ) {
        //
        if (
            $format == 'm/d/Y' &&
            preg_match('/[0-9]{2}-[0-9]{2}-[0-9]{4}/', $date)
        ) {
            return DateTime::createFromFormat(
                'm-d-Y',
                $date
            )->format('m/d/Y');
        }
        //
        return $date;
    }
}

if (!function_exists('getDatesBetweenDates')) {
    /**
     * Get dates array between dates
     *
     * @param string $startDate
     * @param string $endDate
     * @param int    $requestedHours
     * @return array
     */
    function getDatesBetweenDates(
        string $startDate,
        string $endDate,
        int $requestedHours = 0,
        bool $convertTimeZoneToLoggedInPerson = false
    ) {
        //
        $datesArray = [];
        //
        if ($convertTimeZoneToLoggedInPerson) {
            //
            $loggedInPersonTimeZone = getTimeZoneFromAbbr(getLoggedInPersonTimeZone());
            //
            $period = new DatePeriod(
                new DateTime($startDate, new DateTimeZone($loggedInPersonTimeZone)),
                new DateInterval('P1D'),
                new DateTime($endDate, new DateTimeZone($loggedInPersonTimeZone))
            );
        } else {
            $period = new DatePeriod(
                new DateTime($startDate),
                new DateInterval('P1D'),
                new DateTime($endDate)
            );
        }
        //
        foreach ($period as $key => $value) {
            $count++;
            //
            $datesArray[] = [
                'date' => $value->format('Y-m-d'),
                'time' => 0
            ];
        }
        //
        $datesArray[] = ['date' => $endDate, 'time' => 0];
        //
        $requestedMinutes = $requestedHours * 60;
        $requestedMinutesChunk = $requestedMinutes / count($datesArray);
        //
        foreach ($datesArray as $index => $value) {
            //
            if ($requestedMinutes == 0) {
                $requestedMinutesChunk = 0;
            }
            //
            $datesArray[$index]['time'] = $requestedMinutesChunk;
            //
            $requestedMinutes -= $requestedMinutesChunk;
        }
        //
        return $datesArray;
    }
}


if (!function_exists('getCurrentLoginEmployeeId')) {
    /**
     * Get the logged in employee index
     *
     * @param string $index
     * @return string|array
     */
    function getCurrentLoginEmployeeDetails($index = '')
    {
        //
        $CI = &get_instance();
        //
        return $index != '' ? $CI->session->userdata('logged_in')['employer_detail'][$index] : $CI->session->userdata('logged_in')['employer_detail'];
    }
}

if (!function_exists('getCurrentYearHolidaysFromGoogle')) {
    /**
     * Get current year holidays
     *
     * @return array
     */
    function getCurrentYearHolidaysFromGoogle()
    {
        //
        $CI = &get_instance();
        //
        $holidays = json_decode(
            getFileData("https://www.googleapis.com/calendar/v3/calendars/en.usa%23holiday%40group.v.calendar.google.com/events?key=" . (getCreds('AHR')->GoogleAPIKey) . ""),
            true
        )['items'];
        // Extract current year holidays
        $holidays = array_filter(
            $holidays,
            function ($holiday) {
                $year = date('Y');
                return preg_match("/$year/", $holiday['start']['date']);
            }
        );
        //
        $ra = [];
        //
        $year = date('Y');
        // Let's insert to database
        foreach ($holidays as $holiday) {
            //
            $ia = [];
            $ia['holiday_year'] = $year;
            $ia['holiday_title'] = trim($holiday['summary']);
            $ia['from_date'] = trim($holiday['start']['date']);
            $ia['to_date'] = trim($holiday['end']['date']);
            $ia['event_link'] = trim($holiday['htmlLink']);
            $ia['status'] = trim($holiday['status']);
            $ia['icon'] = NULL;
            $ia['created_at'] = $ia['updated_at'] = date('Y-m-d H:i:s', strtotime('noe'));
            //
            $ra[] = $ia;
            //
            if (
                !$CI->db->where([
                    'holiday_title' => $ia['holiday_title'],
                    'holiday_year' => $year
                ])->count_all_results('timeoff_holiday_list')
            ) {
                //
                $CI->db->insert(
                    'timeoff_holiday_list',
                    $ia
                );
            }
        }
        //
        return $ra;
    }
}


if (!function_exists('isDevServer')) {
    function isDevServer()
    {
        return strpos($_SERVER['SERVER_NAME'], '.com') === false ? true : false;
    }
}

if (!function_exists('getSystemDate')) {
    /**
     * Get the current datetime
     *
     * @param string $format
     * @param string $timestamp
     * @return string
     */
    function getSystemDate(string $format = DB_DATE_WITH_TIME, string $timestamp = 'now')
    {
        return date($format, strtotime($timestamp));
    }
}

if (!function_exists('getSystemDateInLoggedInPersonTZ')) {
    /**
     * Get the datetime in logged in person tz
     *
     * @param string $format
     * @param string $timestamp
     * @return string
     */
    function getSystemDateInLoggedInPersonTZ(string $format = DB_DATE_WITH_TIME, string $timestamp = 'now')
    {
        // get the date in current timezone
        $pstDateObject = new DateTime($timestamp, new DateTimeZone(getLoggedInPersonTimeZone()));
        // return the date
        return $pstDateObject->format($format);
    }
}

if (!function_exists('getSystemDateInUTC')) {
    /**
     * Get the current datetime
     *
     * @param string $format
     * @param string $timestamp
     * @param string $customDate Optional
     * @return string
     */
    function getSystemDateInUTC(string $format = DB_DATE_WITH_TIME, string $timestamp = 'now', string $customDate = "")
    {
        // get the date in current timezone
        $utcDateObject = new DateTime($customDate ?? $timestamp, new DateTimeZone("UTC"));
        // return the date
        return $utcDateObject->format($format);
    }
}


if (!function_exists('getTimeZoneFromAbbr')) {
    /**
     * Get the timezone from abbr
     *
     * If the invalid abbr is pass then the
     * system defaults to server's timezone
     *
     * @param string $abbr
     * @return string
     */
    function getTimeZoneFromAbbr(string $abbr)
    {
        //
        $abbr = strtoupper(trim($abbr));
        // set the abbr array
        $timeZoneArray = [
            'SAMT' => 'GST',
            'TRT' => 'EAT',
            'FET' => 'EAT',
            'KUYT' => 'GST'
        ];
        //
        $abbr = isset($timeZoneArray[$abbr]) ? $timeZoneArray[$abbr] : $abbr;
        //
        $timeZone = timezone_name_from_abbr($abbr);
        //
        if (!$timeZone) {
            $timeZone = timezone_name_from_abbr(STORE_DEFAULT_TIMEZONE_ABBR);
        }
        //
        return $timeZone;
    }
}

//
if (!function_exists('getCompanyEmsStatusBySid')) {
    function getCompanyEmsStatusBySid($company_sid, $doRedirect = true)
    {
        $CI = &get_instance();
        //
        $CI->db->where('sid', $company_sid);
        $CI->db->where('ems_status', 1);
        //
        $response = $CI->db->count_all_results('users');
        if ($response <= 0 && $doRedirect) {
            return redirect('/dashboard');
        }
        return $response;
    }
}


//
if (!function_exists('get_eeoc_options_status')) {
    function get_eeoc_options_status($company_sid)
    {
        $CI = &get_instance();
        $CI->db->select('dl_vol,dl_vet,dl_gen');
        $CI->db->where('user_sid', $company_sid);
        return $CI->db->get('portal_employer')->row_array();
    }
}

//
if (!function_exists('get_employee_transfer_date')) {
    function get_employee_transfer_date($employee_sid)
    {
        //

        $CI = &get_instance();
        $CI->db->select('employee_copy_date');
        $CI->db->where('new_employee_sid', $employee_sid);
        $record_obj = $CI->db->get('employees_transfer_log');
        //
        if (!empty($record_obj)) {
            $data = $record_obj->row_array();
            $record_obj->free_result();
            return formatDateToDB($data['employee_copy_date'], DB_DATE_WITH_TIME, DATE);
        }
    }
}

//
if (!function_exists('get_company_departments_teams')) {
    /**
     * Get company department and teams
     *
     * @param int $companyId
     * @param string $id Optional
     * @param int $teamId Optional
     * @return array|string
     */
    function get_company_departments_teams(int $companyId, string $id = '', $teamId = 0)
    {
        //
        $select = '<select name="' . ($id) . '" id="' . ($id) . '" class="jsSelect2" style="width: 100%;">';
        $select .= '<option value="0">Please select a team</option>';
        $select .= '{{options}}';
        $select .= '</select>';
        //
        $CI = &get_instance();
        $CI->db->select('name,sid');
        $CI->db->where('company_sid', $companyId);
        $CI->db->where('is_deleted', 0);
        $departments = $CI->db->get('departments_management')->result_array();
        //
        if (!$departments) {
            return $id ? $select : [];
        }
        //
        $departmentIds = array_column($departments, 'sid');
        //
        $tmp = [];
        //
        foreach ($departments as $department) {
            $tmp[$department['sid']] = [
                'id' => $department['sid'],
                'name' => $department['name'],
                'teams' => []
            ];
        }
        //
        $departments = $tmp;
        // Get teams by department ids
        $CI->db->select('name,sid,department_sid');
        $CI->db->where_in('department_sid', $departmentIds);
        $CI->db->where('is_deleted', 0);
        $CI->db->where('status', 1);
        //
        $teams = $CI->db->get('departments_team_management')->result_array();
        //
        if ($teams) {
            //
            foreach ($teams as $team) {
                $departments[$team['department_sid']]['teams'][] = [
                    'sid' => $team['sid'],
                    'name' => $team['name']
                ];
            }
        }
        //
        if (!empty($id)) {
            //
            $options = '';
            //
            foreach ($departments as $department) {
                $options .= '<optgroup label="' . ($department['name']) . '">';
                if ($department['teams']) {
                    foreach ($department['teams'] as $team) {
                        $options .= '<option value="' . ($team['sid']) . '" ' . ($teamId == $team['sid'] ? "selected" : "") . '>' . ($team['name']) . '</option>';
                    }
                }
                $options .= '</optgroup>';
            }
            //
            $select = str_replace('{{options}}', $options, $select);
        }

        //
        return $id ? $select : $departments;
    }
}

if (!function_exists('getDepartmentColumnByTeamId')) {
    function getDepartmentColumnByTeamId(
        int $teamId,
        string $column
    ) {
        //
        $CI = &get_instance();
        //
        return $CI->db->select($column)
            ->where('sid', $teamId)
            ->get('departments_team_management')
            ->row_array()[$column];
    }
}

if (!function_exists('handleEmployeeDepartmentAndTeam')) {
    function handleEmployeeDepartmentAndTeam(
        int $employeeId,
        int $teamId
    ) {
        //
        if (!$employeeId || !$teamId || $employeeId == 0 || $teamId == 0) {
            return false;
        }
        // Get department id
        $departmentId = getDepartmentColumnByTeamId($teamId, 'department_sid');
        //
        if (!$departmentId) {
            return false;
        }
        //
        $CI = &get_instance();
        // Update users
        $CI->db
            ->where('sid', $employeeId)
            ->update(
                'users',
                [
                    'department_sid' => $departmentId,
                    'team_sid' => $teamId
                ]
            );
        // Make entry in teams table
        $CI->load->model('employee_model');
        //
        $CI->employee_model->checkAndAddEmployeeToTeam(
            $departmentId,
            $teamId,
            $employeeId
        );
        //
        // $CI->db
        //     ->insert(
        //         'departments_employee_2_team',
        //         [
        //             'department_sid' => $departmentId,
        //             'team_sid' => $teamId,
        //             'employee_sid' => $employeeId,
        //             'created_at' => date('Y-m-d H:i:s', strtotime('now'))
        //         ]
        //     );
        //
        return true;
    }
}


if (!function_exists('_secret')) {
    function _secret(string $str, bool $isDate = false, bool $checkPlus = false)
    {
        //
        if (empty($str)) {
            return $str;
        }
        //
        if ($checkPlus) {
            //
            $CI = &get_instance();
            //
            if (
                $CI->session->userdata('logged_in')['employer_detail']['access_level_plus'] == 1
                || $CI->session->userdata('logged_in')['employer_detail']['pay_plan_plus'] == 1
            ) {
                return $str;
            }
        }
        // Check if it's a date
        if (strpos($str, ',') !== false && $isDate) {
            return preg_replace('/[0-9]{4}/', '####', $str);
        }
        //
        if (
            (preg_match('/[0-9]{2}-[0-9]{2}-[0-9]{4}/i', $str)
                || preg_match('/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/i', $str)
                || preg_match('/[0-9]{4}-[0-9]{2}-[0-9]{2}/i', $str)
                || preg_match('/[0-9]{4}\/[0-9]{2}\/[0-9]{2}/i', $str)
            ) && $isDate
        ) {
            return preg_replace('/[0-9]{4}/', '####', $str);
        }
        //
        return '###-##-' . substr($str, -4);
    }
}


if (!function_exists('isSecret')) {
    function isSecret(string $str)
    {

        return strpos(strtolower($str), '#') !== false ? true : false;
    }
}

if (!function_exists('getCompanyEEOCFormStatus')) {
    function getCompanyEEOCFormStatus($company_sid)
    {
        $CI = &get_instance();
        $CI->db->select('eeo_form_status');
        $CI->db->where('user_sid', $company_sid);
        $CI->db->from('portal_employer');
        $portalData = $CI->db->get()->row_array();

        if (!empty($portalData)) {
            return $portalData['eeo_form_status'];
        } else {
            return 0;
        }
    }
}

//
if (!function_exists('get_company_helpbox_info')) {

    function get_company_helpbox_info($company_sid)
    {
        $CI = &get_instance();
        $CI->db->where('company_id', $company_sid);
        $records_obj = $CI->db->get('helpbox_info_for_company');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }
}

if (!function_exists('db_get_employee_profile_byemail')) {
    function db_get_employee_profile_byemail($email, $companySid)
    {
        $CI = &get_instance();
        $CI->db->select('sid,first_name,last_name,email, access_level, job_title, is_executive_admin, access_level_plus, pay_plan_flag');
        $CI->db->where('email', $email);
        $CI->db->where('parent_sid', $companySid);
        return $CI->db->get('users')->result_array();
    }
}

if (!function_exists('get_user_anniversary_date')) {
    /**
     * Get employee annivversary date
     * 
     * @param string $joinedAt 
     * @param string $registrationDate 
     * @param string $rehiredDate
     * @param string $compareDate Optional
     * @param bool   $onlyText Optional
     * @return string|array
     */
    function get_user_anniversary_date(
        $joinedAt,
        $registrationDate,
        $rehiredDate,
        string $compareDate = '',
        bool $onlyText = true
    ) {
        //
        $r = [];
        $r['joiningDate'] =
            $r['timeSpentInCompany'] =
            $r['timeSpentInCompanyAgo'] =
            $r['text'] = '';
        //
        $joiningDate = null;
        //
        if ($rehiredDate && $rehiredDate != '0000-00-00') {
            $joiningDate = $rehiredDate;
        } elseif ($joinedAt && $joinedAt != '0000-00-00') {
            $joiningDate = $joinedAt;
        } elseif ($registrationDate && $registrationDate != '0000-00-00 00:00:00') {
            $joiningDate = trim(explode(' ', $registrationDate)[0]);
        } else {
            return $onlyText ? '' : $r;
        }

        //
        $timeSpentString = '';
        $timeSpentString2 = '';
        $datetime1 = date_create($joiningDate);
        $datetime2 = date_create($compareDate ?? getSystemDate(DB_DATE));

        $interval = $datetime1->diff($datetime2);
        //
        $years = $interval->format('%y');
        $months = $interval->format('%m');
        $days = $interval->format('%d');

        if ($years > 0) {
            $timeSpentString2 = $years . ' ' . ($years > 1 ? 'years' : 'year') . ' ago';
            $timeSpentString .= $years . ' ' . ($years > 1 ? 'years' : 'year');
        }
        if ($months > 0) {
            if ($timeSpentString2 == '') {
                //
                $timeSpentString2 = $months . ' ' . ($months > 1 ? 'months' : 'month') . ' ago';
            }
            $timeSpentString .= ($timeSpentString == '' ? '' : ', ') . $months . ' ' . ($months > 1 ? 'months' : 'month');
        }
        if ($days > 0) {
            if ($timeSpentString2 == '') {
                //
                $timeSpentString2 = $days . ' ' . ($days > 1 ? 'days' : 'day') . ' ago';
            }
            $timeSpentString .= ($timeSpentString == '' ? '' : ', ') . $days . ' ' . ($days > 1 ? 'days' : 'day');
        }
        //
        $return_date = formatDateToDB($joiningDate, DB_DATE, DATE);
        $r['joiningDate'] = $return_date;
        $r['timeSpentInCompany'] = $timeSpentString;
        $r['timeSpentInCompanyAgo'] = $timeSpentString2;
        $r['text'] = $return_date . " (Joined " . $timeSpentString2 . ")";
        //
        if ($onlyText) {
            return "<strong>Employee Anniversary Date: " . $r['text'] . "<strong>";
        }
        //
        return $r;
    }
}

//
if (!function_exists('get_user_datescolumns')) {
    function get_user_datescolumns($emp_id)
    {
        $CI = &get_instance();
        $CI->db->select('joined_at,registration_date');
        $CI->db->where('sid', $emp_id);
        return $CI->db->get('users')->result_array();
    }
}


//
if (!function_exists('showLanguages')) {
    function showLanguages($languages)
    {
        return rtrim(ucwords(implode(', ', (explode(',', $languages))), '\, '), ', ');
    }
}


//
if (!function_exists('isGustoAdmin')) {
    function isGustoAdmin($email, $companySid)
    {
        $CI = &get_instance();
        $CI->db->select('sid');
        $CI->db->from('payroll_company_admin');
        $CI->db->where('email_address ', $email);
        $CI->db->where('company_sid', $companySid);
        $rows = $CI->db->count_all_results();
        return $rows;
    }
}

//
if (!function_exists('getActiveEmployees')) {
    function getActiveEmployees($company_sid)
    {
        $CI = &get_instance();

        $CI->db->select('
            sid, first_name, last_name, middle_name, nick_name,
            email, PhoneNumber, dob, job_title, access_level, access_level_plus,
            Location_Address, Location_Address_2, Location_City, Location_State,
            Location_City, Location_ZipCode, Location_Country,ssn
        ');
        $where = array(
            'parent_sid' => $company_sid,
            'active' => 1,
            'is_executive_admin' => 0,
            'terminated_status' => 0
        );
        return $CI->db->get_where('users', $where)->result_array();
    }
}

//
if (!function_exists('checkTermsAccepted')) {
    function checkTermsAccepted($company_sid)
    {
        $CI = &get_instance();

        $CI->db->where('terms_accepted', 1);
        $CI->db->where('company_sid ', $company_sid);
        //
        if ($CI->db->count_all_results('payroll_companies')) {
            return true;
        }

        return false;
    }
}

if (!function_exists('get_templet_jobtitles')) {
    function get_templet_jobtitles($companyId)
    {
        if (!empty($companyId)) {
            // Get Group Id
            $CI = &get_instance();
            $CI->db->select('job_titles_template_group');
            $CI->db->where('sid', $companyId);
            //
            $company_info = $CI->db->get('users')->row_array();
            $gropuSid = $company_info['job_titles_template_group'];

            // Get Gropup Data
            $CI->db->where('sid', $gropuSid);
            $CI->db->where('archive_status', 'active');
            $GroupsData = $CI->db->get('portal_job_listing_template_groups')->row_array();

            $titlesIdArray = array();

            if ($GroupsData['titles'] != '') {
                $titlesIdArray = unserialize($GroupsData['titles']);
            }
            if (!$titlesIdArray) {
                return [];
            }
            // Get Job Titles
            $CI->db->where('archive_status', 'active');
            $CI->db->where_in('sid', $titlesIdArray);
            $CI->db->order_by('sort_order', 'ASC');
            $jobTitlesData = $CI->db->get('portal_job_title_templates')->result_array();

            return $jobTitlesData;
        }
    }
}

if (!function_exists('getStoreJobTitles')) {
    function getStoreJobTitles()
    {
        // Get Job Titles
        return get_instance()->db
            ->where('archive_status', 'active')
            ->order_by('title', 'ASC')
            ->get('portal_job_title_templates')->result_array();
    }
}


//
if (!function_exists('get_templet_complynettitle')) {
    function get_templet_complynettitle($titleSid)
    {
        if (!empty($titleSid)) {
            $CI = &get_instance();
            $CI->db->select('complynet_job_title');
            $CI->db->where('sid', $titleSid);
            $CI->db->order_by('sort_order', 'ASC');
            $jobComplynetData = $CI->db->get('portal_job_title_templates')->row_array();
            return $jobComplynetData['complynet_job_title'];
        }
    }
}



//  Get User Complynet Job Title by Sid
if (!function_exists('get_user_complynettitle')) {
    function get_user_complynettitle($sid)
    {
        if (!empty($sid)) {
            $CI = &get_instance();
            $CI->db->select('complynet_job_title');
            $CI->db->where('sid', $sid);
            $jobComplynetData = $CI->db->get('users')->row_array();
            return $jobComplynetData['complynet_job_title'];
        }
    }
}


//
if (!function_exists('get_employee_drivers_license')) {
    function get_employee_drivers_license($emp_id)
    {
        $CI = &get_instance();
        $CI->db->select('license_details');
        $CI->db->where('users_sid', $emp_id);
        $CI->db->where('license_type ', 'drivers');
        return $CI->db->get('license_information')->row_array();
    }
}
//
if (!function_exists('getEEOCCitizenShipFlag')) {
    function getEEOCCitizenShipFlag($companySid)
    {
        $CI = &get_instance();
        $CI->db->select('dl_citizen');
        $CI->db->where('user_sid', $companySid);
        $portalData = $CI->db->get('portal_employer')->row_array();
        return $portalData['dl_citizen'];
    }
}
//
if (!function_exists('get_employee_drivers_license')) {
    function get_employee_drivers_license($emp_id)
    {
        $CI = &get_instance();
        $CI->db->select('license_details');
        $CI->db->where('users_sid', $emp_id);
        $CI->db->where('license_type ', 'drivers');
        return $CI->db->get('license_information')->row_array();
    }
}

//
if (!function_exists('get_company_module_status')) {
    function get_company_module_status($companySid, $fieldName)
    {
        $CI = &get_instance();
        $CI->db->select($fieldName);
        $CI->db->where('user_sid', $companySid);
        $result = $CI->db->get('portal_employer')->row_array();
        return $result[$fieldName];
    }
}



//
if (!function_exists('get_executive_administrator_admin_plus_status')) {
    function get_executive_administrator_admin_plus_status($executiveAdminSid, $companySid)
    {
        $CI = &get_instance();
        $CI->db->select('users.access_level_plus');
        $CI->db->select('users.can_access_compliance_safety_report');
        $CI->db->where('executive_admin_sid', $executiveAdminSid);
        $CI->db->where('company_sid', $companySid);
        $CI->db->join('users', 'executive_user_companies.logged_in_sid = users.sid', 'left');
        $result = $CI->db->get('executive_user_companies')->row_array();
        return $result;
    }
}


if (!function_exists('changeComplynetEmployeeStatus')) {
    /**
     * Check and update status on ComplyNet
     * 
     * @method get_instance
     * 
     * @param int    $employeeId
     * @param string $newStatus active|deactive
     * @param bool   $doReturn Optional
     * @return array
     */
    function changeComplynetEmployeeStatus(int $employeeId, string $newStatus, bool $doReturn = true)
    {
        // set default response array
        $res = [];
        // set default old status
        $oldStatus = '';
        // get CI instance
        $CI = &get_instance();
        // check if employee is already synced with ComplyNet
        if (!$CI->db->where('employee_sid', $employeeId)->count_all_results('complynet_employees')) {
            $res['errors'][] = 'The employee has not yet been synchronized with ComplyNet.';
            return $doReturn ? $res : sendResponse(200, $res);
        }
        // get the employee details
        $record =
            $CI->db->select('complynet_location_sid, email, complynet_json')->where([
                'employee_sid' => $employeeId
            ])
                ->get('complynet_employees')
                ->row_array();
        // check if something happens in between
        if (empty($record)) {
            $res['errors'][] = 'The employee has not yet been synchronized with ComplyNet.';
            return $doReturn ? $res : sendResponse(200, $res);
        }
        // decode the json to array
        $jsonToArray = json_decode($record['complynet_json'], true);
        // set the username of employee on ComplyNet
        $username = isset($jsonToArray[0]['UserName']) ? $jsonToArray[0]['UserName'] : $jsonToArray['UserName'];
        // if username is not email then set it to username
        if (strpos($username, '@') === false) {
            $record['email'] = $username;
        }
        // Load ComplyNet library
        $CI->load->library('Complynet/Complynet_lib', '', 'complynet_lib');
        // Get the employee
        $response = $CI->complynet_lib->getEmployeeByEmail($record['email']);
        // if ComplyNet don't have this employee
        if (!$response) {
            $res['errors'][] = 'ComplyNet does not have a record of the selected employee.';
            return $doReturn ? $res : sendResponse(200, $res);
        }
        // get the employee agianst right location0
        foreach ($response as $key => $value) {
            if ($value['LocationId'] == $record['complynet_location_sid']) {
                $oldStatus = $value['Status'] == 1 ? "active" : "deactive";
            }
        }
        // if the employee is not found
        if ($oldStatus == '') {
            $res['errors'][] = 'The ComplyNet status of selected employee could not be located.';
            return $doReturn ? $res : sendResponse(200, $res);
        }
        // skip if both status are same
        if ($newStatus != $oldStatus) {
            //
            $updateArray = [];
            $updateArray["userName"] = $record['email'];
            //
            $CI->complynet_lib->changeEmployeeStatusByEmail(['userName' => $record['email']]);
            //
            $res['success'] = 'The status of the selected employee has been updated to "' . (ucfirst($newStatus)) . '".';
            return $doReturn ? $res : sendResponse(200, $res);
        }
        //
        $res['success'] = 'The employee\'s status had already been marked as "' . (ucfirst($newStatus)) . '".';
        return $doReturn ? $res : sendResponse(200, $res);
    }
}


if (!function_exists("uploadFileToAwsFromUrl")) {
    /**
     * upload file to AWS from url
     *
     * @param string $url
     * @param string $extension
     * @param string $prefix Optional
     * @return string
     */
    function uploadFileToAwsFromUrl(string $url, string $extension, string $prefix = ''): string
    {
        // se the path
        $path = tempnam(sys_get_temp_dir(), time() . $extension);
        // download the file
        downloadFileFromAWS(
            $path,
            $url
        );
        // get the CI instance
        $CI = &get_instance();
        // load the library
        $CI->load->library('aws_lib');
        // upload to AWS
        $newFileName = time();
        if ($prefix) {
            $newFileName .= stringToSlug($prefix, "_");
        }
        $newFileName .= $extension;
        //
        $options = [
            'Bucket' => AWS_S3_BUCKET_NAME,
            'Key' => $newFileName,
            'Body' => file_get_contents($path),
            'ACL' => 'public-read',
            'ContentType' => getMimeType($newFileName)
        ];
        //
        $CI->aws_lib->put_object($options);
        //
        return $newFileName;
    }
}

if (!function_exists('get_company_departments_teams_dropdown')) {
    /**
     * Get company department and teams
     *
     * @param int $companyId
     * @param string $id Optional
     * @param int $teamId Optional
     * @return array|string
     */
    function get_company_departments_teams_dropdown(int $companyId, string $id = '', $teamId = 0)
    {
        //
        $select = '<select name="' . ($id) . '" id="' . ($id) . '" class="jsSelect2" style="width: 100%;">';
        $select .= '<option value="0">All</option>';
        $select .= '{{options}}';
        $select .= '</select>';
        //
        $CI = &get_instance();
        $CI->db->select('name,sid');
        $CI->db->where('company_sid', $companyId);
        $CI->db->where('is_deleted', 0);
        $departments = $CI->db->get('departments_management')->result_array();
        //
        if (!$departments) {
            return $id ? $select : [];
        }
        //
        $departmentIds = array_column($departments, 'sid');
        //
        $tmp = [];
        //
        foreach ($departments as $department) {
            $tmp[$department['sid']] = [
                'id' => $department['sid'],
                'name' => $department['name'],
                'teams' => []
            ];
        }
        //
        $departments = $tmp;
        // Get teams by department ids
        $CI->db->select('name,sid,department_sid');
        $CI->db->where_in('department_sid', $departmentIds);
        $CI->db->where('is_deleted', 0);
        $CI->db->where('status', 1);
        //
        $teams = $CI->db->get('departments_team_management')->result_array();
        //
        if ($teams) {
            //
            foreach ($teams as $team) {
                $departments[$team['department_sid']]['teams'][] = [
                    'sid' => $team['sid'],
                    'name' => $team['name']
                ];
            }
        }
        //
        if (!empty($id)) {
            //
            $options = '';
            //
            foreach ($departments as $department) {
                $options .= '<optgroup label="' . ($department['name']) . '">';
                if ($department['teams']) {
                    foreach ($department['teams'] as $team) {
                        $options .= '<option value="' . ($team['sid']) . '" ' . ($teamId == $team['sid'] ? "selected" : "") . '>' . ($team['name']) . '</option>';
                    }
                }
                $options .= '</optgroup>';
            }
            //
            $select = str_replace('{{options}}', $options, $select);
        }

        //
        return $id ? $select : $departments;
    }
}



if (!function_exists('getActiveAdmin')) {
    function getActiveAdmin($company_sid)
    {
        $CI = &get_instance();

        $CI->db->select('
            sid, first_name, last_name, middle_name, nick_name,
            email, PhoneNumber, dob, job_title, access_level, access_level_plus,
            Location_Address, Location_Address_2, Location_City, Location_State,
            Location_City, Location_ZipCode, Location_Country,ssn
        ');
        $where = array(
            'parent_sid' => $company_sid,
            'active' => 1,
            'is_executive_admin' => 0,
            'terminated_status' => 0,
            'access_level' => 'Admin'
        );
        return $CI->db->get_where('users', $where)->result_array();
    }
}


if (!function_exists("getCompanyColumnById")) {
    function getCompanyColumnById(int $id, string $column): array
    {
        return get_instance()
            ->db
            ->select($column)
            ->where("sid", $id)
            ->limit(1)
            ->get("users")
            ->row_array();
    }
}

if (!function_exists("getIncidentTypeId")) {
    function getIncidentTypeId(int $incidentId): int
    {
        $CI = &get_instance();
        $CI->db->select('incident_type_id');
        $CI->db->where('id', $incidentId);
        //dent
        $record_obj = $CI->db->get("incidentId");
        $record_data = $record_obj->row_array();
        $record_obj->free_result();

        return $record_data['incident_type_id'];
    }
}

if (!function_exists("isSafetyIncident")) {
    function isSafetyIncident(int $incidentTypeId): bool
    {
        $CI = &get_instance();
        $CI->db->select('is_safety_incident');
        $CI->db->where('id', $incidentTypeId);
        //
        $records_obj = $CI->db->get("incident_type");
        $isSafetyIncident = $records_obj->row_array()['is_safety_incident'];
        $records_obj->free_result();

        if ($isSafetyIncident == 1) {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('getManualUserNameByEmailId')) {
    function getManualUserNameByEmailId($reportId, $incidentId, $emailId)
    {
        $CI = &get_instance();
        $CI->db->select('external_name');
        $CI->db->where('csp_reports_sid', $reportId);
        $CI->db->where('csp_report_incident_sid', 0);
        $CI->db->where('external_email', $emailId);
        $reportUser = $CI->db->get('csp_reports_employees')->row_array();
        //
        $employeeName = '';
        //
        if ($reportUser) {
            //
            $employeeName = $reportUser['external_name'] . ' [External User]';
        } else {
            $CI = &get_instance();
            $CI->db->select('external_name');
            $CI->db->where('csp_reports_sid', $reportId);
            $CI->db->where('csp_report_incident_sid', $incidentId);
            $CI->db->where('external_email', $emailId);
            $incidentUser = $CI->db->get('csp_reports_employees')->row_array();
            //
            if ($incidentUser) {
                $employeeName = $incidentUser['external_name'] . ' [External User]';
            } else {
                $employeeName = $emailId;
            }
        }

        //
        return $employeeName;
    }
}

if (!function_exists('checkIfAnyIncidentIssueAssigned')) {
    function checkIfAnyIncidentIssueAssigned(
        $employeeId
    ) {
        //
        $companyId = getEmployeeUserParent_sid($employeeId);
        //
        $CI = &get_instance();
        $CI->db->select("
            departments_team_management.sid as team_sid, 
            departments_team_management.name as team_name,
            departments_management.sid,
            departments_management.name,
            departments_management.lms_managers_ids
        ")
            ->join(
                "departments_management",
                "departments_management.sid = departments_team_management.department_sid",
                "inner"
            )
            ->where("departments_management.company_sid", $companyId)
            ->where("departments_management.is_deleted", 0)
            ->where("departments_team_management.is_deleted", 0);
        // if not plus then check for LMS manager role
        $CI->db->group_start()
            ->where("FIND_IN_SET({$employeeId}, departments_management.csp_managers_ids) > 0", null, null)
            ->or_where("FIND_IN_SET({$employeeId}, departments_team_management.csp_managers_ids) > 0", null, null)
            ->group_end();

        //
        $records_obj = $CI->db->get("departments_team_management");
        $departmentAndTeams = $records_obj->result_array();
        $records_obj->free_result();
        //
        if ($departmentAndTeams) {
            $departments = [];
            $teams = [];
            //
            foreach ($departmentAndTeams as $row) {
                if (!in_array($row['sid'], $departments)) {
                    $departments[] = $row['sid'];
                }
                //
                if (!in_array($row['team_sid'], $teams)) {
                    $teams[] = $row['team_sid'];
                }
            }
            //
            if (!empty($departments) || !empty($teams)) {
                //
                $CI->db->select('csp_reports_employees.csp_reports_sid');
                $CI->db->join(
                    "csp_reports",
                    "csp_reports.sid = csp_reports_employees.csp_reports_sid",
                    "inner"
                );
                $CI->db->where('csp_reports.company_sid', $companyId);
                $CI->db->where('csp_reports_employees.status', 1);
                $CI->db->group_start();
                if ($departments) {
                    foreach ($departments as $department) {
                        $CI->db->or_where('FIND_IN_SET("' . ($department) . '", allowed_departments) > 0', NULL, FALSE);
                    }
                }
                // For teams
                if ($teams) {
                    foreach ($teams as $team) {
                        $CI->db->or_where('FIND_IN_SET("' . ($team) . '", allowed_teams) > 0', NULL, FALSE);
                    }
                }
                $CI->db->group_end();
                $CI->db->from('csp_reports_employees');
                $assignCount = $CI->db->count_all_results();
                //
                if ($assignCount > 0) {
                    return true;
                }
            }
        }
        //
        return false;
    }
}


//
if (!function_exists('get_email_template_by_code')) {

    function get_email_template_by_code($template_code)
    {
        $CI = &get_instance();
        $CI->db->query("SET NAMES 'utf8mb4'");
        $CI->db->where('template_code', $template_code);
        $result = $CI->db->get('email_templates')->row_array();

        if (count($result) > 0) {
            return $result;
        } else {
            return 0;
        }
    }
}

if (!function_exists("sendDispositionStatusToIndeed")) {
    /**
     * Send the applicant status to Indeed
     * @param int $applicantId
     * @param string $indeedStatus
     * @param int $companyId
     */
    function sendDispositionStatusToIndeed(
        $applicantId,
        $indeedStatus,
        $companyId
    ) {
        //
        $CI = &get_instance();
        // get the job with active indeed tracking key
        $record = $CI->db
            ->select([
                "portal_applicant_jobs_list.sid",
            ])
            ->where("portal_applicant_jobs_list.portal_job_applications_sid", $applicantId)
            ->where("portal_applicant_jobs_list.company_sid", $companyId)
            ->group_start()
            ->where("portal_applicant_jobs_list.indeed_ats_sid <>", null)
            ->where("portal_applicant_jobs_list.indeed_ats_sid != ''", "")
            ->group_end()
            ->where("indeed_job_queue.is_processed", 1)
            ->where("indeed_job_queue.is_expired", 0)
            ->join(
                "indeed_job_queue",
                "indeed_job_queue.job_sid = portal_applicant_jobs_list.job_sid",
                "inner"
            )
            ->limit(1)
            ->get("portal_applicant_jobs_list")
            ->row_array();
        // cehck for job
        if ($record) {
            // load indeed model
            $CI->load->model("indeed_model");
            $CI->indeed_model->pushTheApplicantStatus(
                $indeedStatus,
                $record["sid"],
                $companyId,
                true
            );
        }
    }
}

if (!function_exists('encrypt_id')) {
    function encrypt_id($val) {
        $CI =& get_instance();
        $CI->load->library('encryption');
        return base64_encode($CI->encryption->encrypt($val));
    }
}

if (!function_exists('decrypt_id')) {
    function decrypt_id($val) {
        $CI =& get_instance();
        $CI->load->library('encryption');
        try {
            return $CI->encryption->decrypt(base64_decode($val));
        } catch (Exception $e) {
            return false;
        }
    }
}