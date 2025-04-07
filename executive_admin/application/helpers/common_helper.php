<?php defined('BASEPATH') or exit('No direct script access allowed');

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

if (!function_exists('message_header_footer')) {
    function message_header_footer($compnay_id, $company_Name)
    {
        $CI = &get_instance();
        $CI->db->select('sub_domain');
        $CI->db->where('user_sid', $compnay_id);
        $CI->db->from('portal_employer');
        $result = $CI->db->get()->result_array();
        $domain_name = $result[0]['sub_domain'];
        $data['header'] = '<div class="content" style="font-size: 100%; line-height: 1.6em; display: block; max-width: 1000px; margin: 0 auto; padding: 0; position:relative"><div style="width:100%; float:left; padding:5px 20px; text-align:center; box-sizing:border-box; background-color:#0000FF;"><h2 style="color:#fff;">' . $company_Name . '</h2></div>  <div class="body-content" style="width:100%; float:left; padding:20px 0; box-sizing:padding-box;">';
        $data['footer'] = '</div><div class="footer" style="width:100%; float:left; background-color:#0000FF; padding:20px 30px; box-sizing:border-box;"><div style="float:left; width:100%; "><p style="color:#fff; float:left; text-align:center; font-style:italic; line-height:normal; font-family: "Open Sans", sans-serif; font-weight:600; font-size:14px;"><a style="color:#fff; text-decoration:none;" href="' . STORE_PROTOCOL . $domain_name . '">&copy; ' . date('Y') . ' ' . $domain_name . '. All Rights Reserved.</a></p></div></div></div>';
        return $data;
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

if (!function_exists('sendMail')) {
    function sendMail($from, $to, $subject, $body, $fromName = NULL, $replyTo = NULL)
    {
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
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->send();
    }
}

if (!function_exists('sendMailMultipleRecipients')) {
    function sendMailMultipleRecipients($from, $to = array(), $subject, $body, $fromName = NULL, $replyTo = NULL)
    {
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

        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->send();
    }
}

if (!function_exists('sendMailWithCC')) {
    function sendMailWithCC($from, $to, $cc, $subject, $body, $fromName = NULL, $replyTo = NULL)
    {
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
        $mail->addCC($cc);
        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->send();
    }
}

if (!function_exists('sendMailWithAttachment')) {
    function sendMailWithAttachment($from, $to, $subject, $body, $fromName = NULL, $file, $replyTo = NULL, $multiple = false)
    {
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
        $mail->isHTML(true);
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
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->send();
    }
}

if (!function_exists('sendMailWithAttachmentRealPath')) {
    function sendMailWithAttachmentRealPath($from, $to, $subject, $body, $fromName = NULL, $filePath = NULL, $replyTo = NULL)
    {
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
        $mail->isHTML(true);

        if ($filePath != NULL) {
            $mail->AddAttachment($filePath);
        }

        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->send();
    }
}

if (!function_exists('sendMailWithStringAttachment')) {
    function sendMailWithStringAttachment($from, $to, $subject, $body, $fromName = NULL, $files, $replyTo = NULL)
    {
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
        $mail->isHTML(true);

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
        $CI->db->order_by("order", "asc");
        $CI->db->where('active', '1');
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

if (!function_exists('db_get_country_name')) {
    function db_get_country_name($sid)
    {
        $CI = &get_instance();
        $CI->db->select('*');
        $CI->db->where('sid', $sid);
        $CI->db->from('countries');
        $result = $CI->db->get()->result_array();
        return $result[0];
    }
}

if (!function_exists('db_get_state_name')) {
    function db_get_state_name($sid)
    {
        $CI = &get_instance();
        $CI->db->select('country_sid, state_code, state_name, country_code, country_name');
        $CI->db->join('countries', 'countries.sid = states.country_sid');
        $CI->db->where('states.sid', $sid);
        $CI->db->from('states');
        $result = $CI->db->get()->result_array();
        if (isset($result[0])) {
            return $result[0];
        } else {
            return array();
        }
    }
}

if (!function_exists('my_date_format')) {
    function my_date_format($data)
    {
        $with_time = date('M d Y, D H:i:s', strtotime($data));
        if (strpos($with_time, '00:00:00')) {
            $with_time = date('M d Y, D', strtotime($data));
        }
        return $with_time;
        //        return date('m/d/Y', strtotime($data));
    }
}

if (!function_exists('db_get_cleanstring')) {
    function db_get_cleanstring($string)
    {
        $string = strtolower($string); //Lower case everything        
        $string = preg_replace("/[^a-z0-9_\s-]/", "", $string); //Make alphanumeric (removes all other characters)        
        $string = preg_replace("/[\s-]+/", " ", $string); //Clean up multiple dashes or whitespaces       
        $string = preg_replace("/[\s_]/", "-", $string); //Convert whitespaces and underscore to dash
        return $string;
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

if (!function_exists('formatDateForDb')) {
    function formatDateForDb($date)
    {
        $date_parts = explode('-', $date);
        $month = $date_parts[0];
        $day = $date_parts[1];
        $year = $date_parts[2];
        return strtotime($year . '-' . $month . '-' . $day . '00:00:00');
    }
}

if (!function_exists('convert_date_to_db_format')) {
    function convert_date_to_db_format($string_date)
    {
        return date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $string_date)));
    }
}

if (!function_exists('convert_date_to_frontend_format')) {
    function convert_date_to_frontend_format($string_date)
    {
        $with_time = date('M d Y, D H:i:s', strtotime($string_date));
        if (strpos($with_time, '00:00:00')) {
            $with_time = date('M d Y, D', strtotime($string_date));
        }
        return $with_time;
        //        if ($string_date == '0000-00-00 00:00:00') {
        //            $string_date = date('Y-m-d H:i:s');
        //        }
        //        return date('m/d/Y', strtotime(str_replace('-', '/', $string_date)));
    }
}

if (!function_exists('db_get_enum_values')) {
    function db_get_enum_values($table, $field)
    {
        $CI = &get_instance();
        $type = $CI->db->query("SHOW COLUMNS FROM `" . $table . "` WHERE Field = '" . $field . "'")->row(0)->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $enum = explode("','", $matches[1]);
        return $enum;
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

        if (($number >= 0 && (int)$number < 0) || (int)$number < 0 - PHP_INT_MAX) {
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
                $tens = ((int)($number / 10)) * 10;
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
                $numBaseUnits = (int)($number / $baseUnit);
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
            foreach (str_split((string)$fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }

        return $string;
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

if (!function_exists('month_date_year')) {
    function month_date_year($date)
    {
        return date('M d Y', strtotime($date));
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

if (!function_exists('date_with_time')) {
    function date_with_time($date)
    {
        $with_time = date('M d Y, D H:i:s', strtotime($date));
        if (strpos($with_time, '00:00:00')) {
            $with_time = date('M d Y, D', strtotime($date));
        }
        return $with_time;
        //        return date('D, d M Y h:i:s', strtotime($date));
    }
}

if (!function_exists('convert_email_template')) {
    function convert_email_template($emailTemplateBody, $employer_sid = NULL)
    {
        $CI = &get_instance();
        $CI->db->where('sid', $employer_sid);
        $userData = $CI->db->get('executive_users')->row_array();
        if (count($userData) > 0) {
            $emailTemplateBody = str_replace('{{firstname}}', ucfirst($userData['first_name']), $emailTemplateBody);
            $emailTemplateBody = str_replace('{{lastname}}', ucfirst($userData['last_name']), $emailTemplateBody);
            $emailTemplateBody = str_replace('{{site_url}}', base_url(), $emailTemplateBody);
            $emailTemplateBody = str_replace('{{date}}', month_date_year(date('Y-m-d')), $emailTemplateBody);
            $emailTemplateBody = str_replace('{{username}}', $userData['username'], $emailTemplateBody);
            $emailTemplateBody = str_replace('{{password}}', decode_string($userData['key']), $emailTemplateBody);
            $emailTemplateBody = str_replace('{{employer_id}}', $employer_sid, $emailTemplateBody);
            $emailTemplateBody = str_replace('{{verification_key}}', $userData['activation_code'], $emailTemplateBody);
            return $emailTemplateBody;
        } else {
            return 0;
        }
    }
}

if (!function_exists('get_no_of_applicants')) {
    function get_no_of_applicants($job_id, $company_sid)
    {
        $CI = &get_instance();
        $CI->db->select('*');
        $CI->db->where('job_sid', $job_id);
        $CI->db->where('company_sid', $company_sid);
        $result = $CI->db->get('portal_applicant_jobs_list');
        return $result->num_rows();
    }
}


if (!function_exists('get_company_details')) {
    function get_company_details($company_sid)
    {
        $CI = &get_instance();
        $CI->db->select('*');
        $CI->db->where('parent_sid', 0);
        $CI->db->where('sid', $company_sid);
        $record_row = $CI->db->get('users')->result_array();

        if (!empty($record_row)) {
            return $record_row[0];
        } else {
            return array();
        }
    }
}

if (!function_exists('is_leap_year')) {
    function is_leap_year($year = NULL)
    {
        if (is_numeric($year)) {
            return checkdate(2, 29, (int)$year);
        } else {
            return false;
        }
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
        $domain_name = $result[0]['sub_domain'];
        return $domain_name;
    }
}

if (!function_exists('my_print_r')) {
    function my_print_r($obj, $ip_address)
    {
        $ip = getUserIP();

        if ($ip == $ip_address) {
            echo  '<pre>';
            print_r($obj);
            echo '</pre>';
        }
    }
}

if (!function_exists('my_echo')) {
    function my_echo($str, $ip_address)
    {
        $ip = getUserIP();
        if ($ip == $ip_address) {
            echo  '<pre>';
            echo $str;
            echo '</pre>';
        }
    }
}

if (!function_exists('get_interview_status')) {
    function get_interview_status($status_sid)
    {
        $CI = &get_instance();
        $CI->db->select('name, css_class, bar_bgcolor');
        $CI->db->join('portal_applicant_jobs_list', 'portal_applicant_jobs_list.status_sid = application_status.sid');
        $CI->db->where('portal_applicant_jobs_list.sid', $status_sid);
        $CI->db->from('application_status');
        $result = $CI->db->get()->result_array();
        $return_data = array();

        if (!empty($result)) {
            $return_data = $result[0];
        }

        return $return_data;
    }
}

if (!function_exists('get_interview_status_by_parent_id')) {
    function get_interview_status_by_parent_id($status_sid)
    {
        $CI = &get_instance();
        $CI->db->select('name, css_class, bar_bgcolor');
        $CI->db->join('portal_applicant_jobs_list', 'portal_applicant_jobs_list.status_sid = application_status.sid');
        $CI->db->where('portal_applicant_jobs_list.portal_job_applications_sid', $status_sid);
        $CI->db->limit(1);
        $CI->db->from('application_status');
        $result = $CI->db->get()->result_array();
        $return_data = array();

        if (!empty($result)) {
            $return_data = $result[0];
        }

        return $return_data;
    }
}

if (!function_exists('get_default_interview_status')) {
    function get_default_interview_status($status_sid, $field_id)
    {
        $CI = &get_instance();
        $CI->db->select('status');
        $CI->db->where($field_id, $status_sid);
        $CI->db->from('portal_applicant_jobs_list');
        $CI->db->limit(1);
        $result = $CI->db->get()->result_array();
        $return_data = 'Not Contacted Yet';

        if (!empty($result)) {
            $return_data = $result[0]['status'];
        }

        return $return_data;
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
    function timezone_dropdown($selected = '', $attrs = array())
    {
        // Fetch timezones
        $timezones = get_timezones('all');
        $timezone_rows = '';
        $timezone_rows .= '<select';
        // Set Attrs
        if (sizeof($attrs)) foreach ($attrs as $k0 => $v0) $timezone_rows .= ' ' . $k0 . ' = "' . $v0 . '"';
        $timezone_rows .= '>';
        if ($selected == '') $timezone_rows .= '<option value="">Please Select</option>';
        if (sizeof($timezones)) foreach ($timezones as $k0 => $v0) $timezone_rows .= '<option ' . ($selected == $v0['key'] ? 'selected="true"' : '') . ' value="' . ($v0['key']) . '">' . ($v0['name']) . ' (' . ($v0['key']) . ')</option>';
        $timezone_rows .= '</select>';
        return $timezone_rows;
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
        $timezones[] = ['value' => '-11:00|US|01|SST', 'name' => 'Samoa Time',    'key' => 'SST'];
        $timezones[] = ['value' => '-08:00|US|05|AKST', 'name' => 'Alaska Time',   'key' => 'AKST'];
        $timezones[] = ['value' => '-07:00|US|07|PST', 'name' => 'Pacific Time',  'key' => 'PST'];
        $timezones[] = ['value' => '-06:00|US|09|MST', 'name' => 'Mountain Time', 'key' => 'MST'];
        $timezones[] = ['value' => '-05:00|US|11|CST', 'name' => 'Central Time',  'key' => 'CST'];
        $timezones[] = ['value' => '-04:00|US|13|EST', 'name' => 'Eastern Time',  'key' => 'EST'];
        $timezones[] = ['value' => '-04:00|US|14|AST', 'name' => 'Atlantic Time', 'key' => 'AST'];
        $timezones[] = ['value' => '+10:00|US|15|CHST', 'name' => 'Chamorro Time', 'key' => 'CHST'];
        $timezones[] = ['value' => '-09:00|US|03|HST', 'name' => 'Hawaii-Aleutian Time', 'key' => 'HST'];
        $timezones[] = ['value' => '-03:00|US|15|NST', 'name' => 'Newfoundland Standard Time', 'key' => 'NST'];
        // Europe
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
        // Merge arrays
        $zones = $timezones;
        // Check and return
        if ($type == 'all') return $zones; // return all zones
        else foreach ($zones as $k0 => $v0) if ($type == $v0['key']) return $index == '' ? $v0 : ($index == 'name' ? $v0['name'] . ' (' . ($v0['key']) . ')' : $v0[$index]); // return specific zone
    }
}

/**
 * Convert  datetime
 * Created on: 26-06-2019
 * Key: TIMEZONE
 *
 * @param $data Array
 * 'datetime'    String
 * '_this'       Instance
 * 'from_format' String Optional
 * 'format'      String Optional
 * 'type'        String Optional ('user', 'company', 'exective', 'admin', 'affiliate')
 *
 * @return String
 */
if (!function_exists('reset_datetime')) {
    function reset_datetime($data)
    {
        // Defaults
        $from_format = 'Y-m-d H:i:s';
        $format      = 'M d Y, D H:i:s';
        $type        = 'user';
        $from_timezone = STORE_DEFAULT_TIMEZONE_ABBR;
        $with_timezone = 0;
        // Check array size
        if (!is_array($data) || !sizeof($data) || !isset($data['datetime']) || !isset($data['_this'])) return false;
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
        $with_timezone = (int)$with_timezone;
        if ($with_timezone === 1) $format .= ', T \(P\)';
        // Check for login session
        if ($_this->session->userdata('logged_in')) {
            // If the type is user
            if ($type == 'user') {
                if (clean_string($_this->session->userdata('logged_in')['employer_detail'], 'timezone') == '') {
                    // Check for companys timezone
                    if (clean_string($_this->session->userdata('logged_in')['company_detail'], 'timezone') == '') $timezone = STORE_DEFAULT_TIMEZONE_ABBR;
                    else $timezone = $_this->session->userdata('logged_in')['company_detail']['timezone'];
                } else $timezone = $_this->session->userdata('logged_in')['employer_detail']['timezone'];
            } else if ($type == 'company') { // For company
                if (clean_string($_this->session->userdata('logged_in')['company_detail'], 'timezone') == '') $timezone = STORE_DEFAULT_TIMEZONE_ABBR;
                else $timezone = $_this->session->userdata('logged_in')['company_detail']['timezone'];
            } else $timezone = STORE_DEFAULT_TIMEZONE_ABBR;
        }

        // Set user given timezone
        $timezone = isset($new_zone) ? $new_zone : $timezone;
        // _e($timezone, true);

        // $timezone = 'CHST';
        // Reset timezone
        if (!preg_match('/^[A-Z]/', $timezone)) $timezone = STORE_DEFAULT_TIMEZONE_ABBR;
        if (!preg_match('/^[A-Z]/', $from_timezone)) $from_timezone = STORE_DEFAULT_TIMEZONE_ABBR;
        // _e($timezone);
        //
        reset_format($from_format);
        reset_format($format, 'M d Y, D H:i:s');
        $a = array(
            'datetime' => $datetime,
            'from_format' => $from_format,
            'format' => $format,
            'new_zone' => $timezone,
            'from_zone' => $from_timezone
        );
        //
        if (isset($debug) && $debug) $a['debug'] = $debug;
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
        if (!is_array($data) || !sizeof($data)) return false;
        // Reset formats
        if (!isset($data['from_format'])) $from_format = 'Y-m-d';
        if (!isset($data['format'])) $format = 'Y-m-d\TH:i:s\ZO';
        $to_format = 'Y-m-d H:i:s O P e I';
        $from_zone = STORE_DEFAULT_TIMEZONE_ABBR;
        // Convert array indexes to variables
        extract($data);
        // Check for date string
        if (!isset($datetime)) return false;
        // Check for new zone
        if (!isset($new_zone)) return false;
        // For debugging
        if (isset($debug) && $debug) _e($from_zone . ' - ' . $new_zone, true);
        // Set return array
        $return_array = array();
        // _e($from_zone, true);
        // _e(timezone_name_from_abbr($from_zone), true);
        // Let's create date
        $date_obj = DateTime::createFromFormat($from_format, $datetime, new DateTimeZone(timezone_name_from_abbr($from_zone)));
        if ($from_zone != 'UTC') {
            // Convert it to utc
            $utc = $date_obj->setTimezone(new DateTimeZone(timezone_name_from_abbr('UTC')));
            // Get utc date
            $return_array['UTC'] = parse_datetime($utc->format($to_format));
        }
        //
        if (isset($from_zone)) {
            $fromzone = $date_obj->setTimezone(new DateTimeZone(timezone_name_from_abbr($from_zone)));
            $return_array[$from_zone] = parse_datetime($fromzone->format($to_format));
        }
        //
        if ($from_zone != 'UTC') $tozone = $utc->setTimezone(new DateTimeZone(timezone_name_from_abbr($new_zone)));
        else $tozone = $date_obj->setTimezone(new DateTimeZone(timezone_name_from_abbr($new_zone)));
        $return_array[$new_zone] = parse_datetime($tozone->format($to_format));
        $return_array['date_time_string'] = $tozone->format($format);

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
 * Format phone number
 * Created on: 10-07-2019
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
            if (!isset($data[$index])) return '';
            $cur_data = trim($data[$index]);
            if ($index == 'timezone') if (!preg_match('/^[A-Z]/', $cur_data)) return '';
            return clean_string($cur_data);
        }
        // For string
        if ($data == '' || $data == NULL) return '';
        return $data;
    }
}

// Display error
// Created on: 29-09-2022
if (!function_exists('_e')) {
    function _e($e, $print = FALSE, $die = FAlSE, $isHidden = FALSE)
    {
        if ($isHidden) echo '<!-- ';
        echo '<pre>';
        if ($print) echo '<br />*****************************<br />';
        if (is_array($e)) print_r($e);
        else if (is_object($e)) var_dump($e);
        else echo ($e);
        if ($print) echo '<br />*****************************<br />';
        echo '</pre>';
        if ($isHidden) echo ' -->';
        if ($die) exit(0);
    }
}

if (!function_exists('remakeAccessLevel')) {
    function remakeAccessLevel($obj)
    {
        $access = '';
        if (isset($obj['is_executive_admin']) && $obj['is_executive_admin'] != 0) {
            $access = $obj['access_level'];
            $obj['access_level'] = ' ( Executive ' . $obj['access_level'] . ' )';
        } else {
            $obj['access_level'] = ' ( ' . $obj['access_level'] . ' )';
        }


        if ($obj['access_level_plus'] == 1 && $obj['pay_plan_flag'] == 1) return $obj['access_level'] . ' ( ' . $access . ' Plus / Payroll )';
        if ($obj['access_level_plus'] == 1) return $obj['access_level'] . ' ( ' . $access . ' Plus )';
        if ($obj['pay_plan_flag'] == 1) return $obj['access_level'] . ' ( ' . $access . ' Payroll )';

        if (isset($obj['is_executive_admin']) && $obj['is_executive_admin'] != 0) {
            return $obj['access_level'];
        }

        return  $obj['access_level'];
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
        if (isset($o['job_title']) && $o['job_title'] != '' && $o['job_title'] != null) $r .= ' (' . ($o['job_title']) . ')';
        //
        $r .= ' ' . remakeAccessLevel($o) . '';
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

//
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
        //
        return $arr[$index];
    }
}

//

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
        //
        if (strtolower($lastStatusText) === 'rehired') {
            return 'Active';
        }
        //
        return ucwords($lastStatusText ? $lastStatusText : ($active ? 'Active' : 'De-activated'));
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


//
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



// Replace magic quotes
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
            if (!$ses) return false;
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

//
if (!function_exists('getPageContent')) {

    function getPageContent($page, $slug = false)
    {
        //
        $CI = &get_instance();
        $CI->db
            ->select('content');
        if ($slug == true) {
            $CI->db->where('slug', $page);
        } else {
            $CI->db->where('page', $page);
        }
        $pageContent =   $CI->db->get('cms_pages_new')->row_array();
        return json_decode($pageContent['content'], true);
    }
}

//
if (!function_exists('getPageNameBySlug')) {

    function getPageNameBySlug($slug)
    {
        //
        $CI = &get_instance();
        $page =  $CI->db->select('page')
            ->where('slug', $slug)
            ->get('cms_pages_new')
            ->row_array();
        return $page['page'];
    }
}


//

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

if (!function_exists('findCompanyUser')) {
    function findCompanyUser($email, $company_sid)
    {
        $result = [
            'userType' => '',
            'profilePath' => '',
            'userName' => ''
        ];
        //
        $CI = &get_instance();
        $CI->db->select('sid, first_name, last_name');
        $CI->db->where('parent_sid', $company_sid);
        $CI->db->where('email', $email);
        $record_row = $CI->db->get('users')->row_array();

        if (!empty($record_row)) {
            $result['profilePath'] = base_url('employee_profile') . '/' . $record_row['sid'];
            $result['userType'] = "employee";
            $result['userName'] = $record_row['first_name'] . ' ' . $record_row['last_name'];
        } else {
            $CI->db->select('sid, first_name, last_name, email');
            $CI->db->where('email', $email);
            $CI->db->where('employer_sid', $company_sid);
            $record_obj = $CI->db->get('portal_job_applications');
            $record_arr = $record_obj->row_array();
            $record_obj->free_result();

            if (!empty($record_arr)) {
                $result['userName'] = $record_arr['first_name'] . ' ' . $record_arr['last_name'];
                $portal_job_applications_sid = $record_arr['sid'];

                $CI->db->select('sid');
                $CI->db->order_by('sid', 'desc');
                $CI->db->limit(1);
                $CI->db->where('portal_job_applications_sid', $portal_job_applications_sid);
                $obj = $CI->db->get('portal_applicant_jobs_list');
                $result_arr = $obj->row_array();
                $obj->free_result();

                if (!empty($result_arr)) {
                    $result['profilePath'] = base_url('applicant_profile') . '/' . $portal_job_applications_sid . '/' . $result_arr['sid'];
                    $result['userType'] = 'applicant';
                }
            }
        }

        return $result;
    }
}
//

if (!function_exists('getStaticFileVersion')) {
    /**
     * set and get the version of the minified file
     *
     * @param string $file The URI of the asset
     * @param string   $newFlow
     * @returns string
     */
    function getStaticFileVersion(
        string $file,
        string $newFlow = ''
    ) {
        // set files
        $files = [];
        // plugins
        $files['v1/plugins/ms_uploader/main'] = ['css' => '2.0.0', 'js' => '2.0.0'];
        $files['v1/plugins/ms_modal/main'] = ['css' => '2.0.0', 'js' => '2.0.0'];
        //
        $files['js/app_helper'] = ['js' => '1.0.0'];
        // Gusto
        $files['v1/payroll/js/company_onboard'] = ['js' => '1.0.0'];
        // set the main CSS file
        $files['2022/css/main'] = ['css' => '2.1.1'];
        // set the course files
        $files['js/app_helper'] = ['js' => '3.0.0'];
        $files['v1/common'] = ['js' => '3.0.0'];
        $files['v1/lms/add_question'] = ['js' => '3.0.0'];
        $files['v1/lms/edit_question'] = ['js' => '3.0.0'];
        $files['v1/lms/create_course'] = ['js' => '3.0.0'];
        $files['v1/lms/edit_course'] = ['js' => '3.0.0'];
        $files['v1/lms/main'] = ['js' => '3.0.0'];
        $files['v1/lms/assign_company_courses'] = ['js' => '3.0.0'];
        $files['v1/lms/preview_assign'] = ['js' => '3.0.0'];

        // payroll files
        // dashboard
        $files['v1/payroll/js/dashboard'] = ['js' => '1.0.0'];
        // admins
        $files['v1/payroll/js/admin/add'] = ['js' => '1.0.0'];
        // signatory
        $files['v1/payroll/js/signatories/create'] = ['js' => '1.0.0'];
        // employee onboard
        $files['v1/payroll/js/employees/manage'] = ['js' => '1.0.0'];
        // contractor onboard
        $files['v1/payroll/js/contractors'] = ['js' => '1.0.0'];
        // Earning types
        $files['v1/payroll/js/earnings/manage'] = ['js' => '1.0.0'];
        // new theme
        // login
        $files['public/v1/css/app/executive_admin'] = ["css" => "2.0"];
        $files['public/v1/js/app/executive_admin'] = ["js" => "2.0"];
        // forgot
        $files['public/v1/css/app/executive_admin_forgot'] = ["css" => "2.0"];
        $files['public/v1/js/app/executive_admin_forgot'] = ["js" => "2.0"];
        // check and return data
        return $newFlow ? ($files[$file][$newFlow] ?? '1.0.0') : ($files[$file] ?? []);
    }
}


//

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


//
if (!function_exists('GetCss')) {
    /**
     * Generates the style tags
     * @param array  $tabs
     * [
     *  'assets/js/script'
     * ]
     *
     * @return
     */
    function GetCss($scripts)
    {
        //
        $html = '';
        //
        foreach ($scripts as $script) {
            //
            if (is_array($script)) {
                $html .= '<link  rel="stylesheet" type="text/css"  href="' . (base_url('assets/' . _m($script[1], 'css', $script[0]))) . '">' . "\n\t";
            } else {
                //
                if (strpos($script, 'http') !== false) {
                    $html .= '<link  rel="stylesheet" type="text/css"  href="' . ($script) . '" />' . "\n\t";
                } else {
                    $html .= '<link  rel="stylesheet" type="text/css"  href="' . (base_url('assets/' . _m($script, 'css'))) . '">' . "\n\t";
                }
            }
        }
        //
        return $html;
    }
}


if (!function_exists('bundleJs')) {
    /**
     * Make a bundle of JS files
     *
     * @param array  $files
     * @param string $destination Optional
     * @param string $file Optional
     * @param bool   $lockFile Optional
     * @return string
     */
    function bundleJs(
        array $inputs,
        string $destination = 'assets/v1/app/js/',
        string $file = 'main',
        $lockFile = false
    ) {
        // reset the destination path
        $absolutePath = str_replace("executive_admin/", "", ROOTPATH . $destination);
        // check if served over production
        if (MINIFIED === '.min' || $lockFile) {
            //
            $fileName = $destination . $file;
            //
            return
                '<script src="' . (main_url(
                    $destination . $file . '.min.js?v=' . (getStaticFileVersion($fileName, 'js'))
                )) . '"></script>';
        }
        //
        if (!is_dir($absolutePath)) {
            mkdir($absolutePath, true, 0777) || exit('Failed to create path "' . ($absolutePath) . '"');
        }
        // add file to destination
        $absolutePathMin = $absolutePath;
        // add file to destination
        $absolutePath .= $file . '.js';
        $absolutePathMin .= $file . '.min.js';
        // creates a new file
        $handler = fopen($absolutePath, 'w');
        // if failed throw an error
        if (!$handler) {
            exit('Failed to set resources');
        }
        //
        foreach ($inputs as $input) {
            //
            $input = main_url('assets/' . $input . '.js');
            //
            fwrite($handler, file_get_contents($input) . "\n\n");
        }
        //
        fclose($handler);
        // delete the old file first
        if (file_exists($absolutePathMin)) {
            @unlink($absolutePathMin);
        }
        //
        shell_exec(
            "uglifyjs {$absolutePath} -c -m > {$absolutePathMin}"
        );
        //
        @unlink($absolutePath);
        //
        return '<script src="' . (main_url(
            $destination . $file . '.min.js?v=' . time()
        )) . '"></script>';
    }
}

if (!function_exists('bundleCSS')) {
    /**
     * Make a bundle of CSS files
     *
     * @param array  $files
     * @param string $destination Optional
     * @param string $file Optional
     * @param bool   $lockFile Optional
     * @return string
     */
    function bundleCSS(
        array $inputs,
        string $destination = 'assets/v1/app/css/',
        string $file = 'main',
        $lockFile = false
    ) {
        // reset the destination path
        $absolutePath = str_replace("executive_admin/", "", ROOTPATH . $destination);
        // check if served over production
        if (MINIFIED === '.min' || $lockFile) {
            //
            $fileName = $destination . $file;
            //
            return '<link rel="stylesheet" href="' . (main_url(
                $destination .
                    $file . '.min.css?v=' . (getStaticFileVersion($fileName, 'css'))
            )) . '" />';
        }
        //
        if (!is_dir($absolutePath)) {
            mkdir($absolutePath, true) || exit('Failed to create path "' . ($absolutePath) . '"');
        }
        // add file to destination
        $absolutePathMin = $absolutePath;
        $absolutePath .= $file . '.css';
        $absolutePathMin .= $file . '.min.css';
        // creates a new file
        $handler = fopen($absolutePath, 'w');
        // if failed throw an error
        if (!$handler) {
            exit('Failed to set resources');
        }
        //
        foreach ($inputs as $input) {
            //
            $input = main_url('assets/' . $input . '.css');
            //
            fwrite($handler, file_get_contents($input) . "\n\n");
        }
        //
        fclose($handler);
        // delete the old file first
        if (file_exists($absolutePathMin)) {
            @unlink($absolutePathMin);
        }
        //
        shell_exec(
            "uglifycss {$absolutePath} > {$absolutePathMin}"
        );
        //
        @unlink($absolutePath);
        //
        return '<link rel="stylesheet" href="' . (main_url(
            $destination . $file . '.min.css?v=' . time()
        )) . '" />';
    }
}

if (!file_exists("getSourceByType")) {
    function getSourceByType(string $type, string $path, string $props = '', $fullWidth = true): string
    {
        if ($type === "upload") {
            if (isImage($path)) {
                return '<img src="' . AWS_S3_BUCKET_URL . $path . '" style="' . ($fullWidth ? "width: 100%;" : "") . '" ' . ($props) . ' alt="' . (splitPathAndFileName($path)["name"]) . '" />';
            } else {
                return '<video src="' . AWS_S3_BUCKET_URL . $path . '" style="' . ($fullWidth ? "width: 100%;" : "") . '" controls ' . ($props) . '></video>';
            }
        } else {
            return '<iframe src="' . $path . '" title="AutomotoHR video" style="' . ($fullWidth ? "width: 100%;" : "") . ' min-height: 450px" ' . ($props) . '></iframe>';
        }
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

if (!function_exists('splitPathAndFileName')) {
    /**
     * splits file name and path
     *
     * @param string $file
     * @return array
     */
    function splitPathAndFileName(string $file): array
    {
        //
        $returnArray = [
            'path' => '',
            'name' => '',
            'orig_name' => $file,
            'ext' => '',
            'mime' => ''
        ];
        //
        $splits = explode('/', $file);
        //
        $index = count($splits) - 1;
        //
        $returnArray['name'] = $splits[$index];
        //
        unset($splits[$index]);
        // for extension
        $returnArray['path'] = implode('/', $splits);
        //
        $splits = explode('.', $returnArray['name']);
        //
        $index = count($splits) - 1;
        //
        $returnArray['ext'] = $splits[$index];
        $returnArray['mime'] = getMimeType($returnArray['ext']);
        //
        return $returnArray;
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

if (!function_exists("convertToStrip")) {
    /**
     * converts
     *
     * @param string $str
     * @return string
     */
    function convertToStrip(string $str): string
    {
        return preg_replace("/##(.*?)##/i", '<strong class="text-yellow">$1</strong>', $str);
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
            return AWS_S3_BUCKET_URL . '64533-profile_picture--ydI.jpg';
        }
    }
}


if (!function_exists('GetScripts')) {
    /**
     * Generates the scripts tags
     * @param array  $tabs
     * [
     *  'assets/js/script'
     * ]
     *
     * @return
     */
    function GetScripts($scripts)
    {
        //
        $html = '';
        //
        foreach ($scripts as $script) {
            //
            if (is_array($script)) {
                $html .= '<script type="text/javascript" src="' . (base_url('assets/' . _m($script[1], 'js', $script[0]))) . '"></script>' . "\n\t";
            } else {
                //
                if (strpos($script, 'http') !== false) {
                    $html .= '<script type="text/javascript" src="' . ($script) . '"></script>' . "\n\t";
                } else {
                    $html .= '<script type="text/javascript" src="' . (base_url('assets/' . _m($script))) . '"></script>' . "\n\t";
                }
            }
        }
        //
        return $html;
    }
}

//
if (!function_exists('get_executive_administrator_admin_plus_status')) {
    function get_executive_administrator_admin_plus_status($executiveAdminSid, $companySid)
    {
        $CI = &get_instance();
        $CI->db->select('users.access_level_plus');
        $CI->db->where('executive_admin_sid', $executiveAdminSid);
        $CI->db->where('company_sid', $companySid);
        $CI->db->join('users', 'executive_user_companies.logged_in_sid = users.sid', 'left');
        $result = $CI->db->get('executive_user_companies')->row_array();
        return $result;
    }
}

if (!function_exists('checkIfAppIsEnabled')) {
    function checkIfAppIsEnabled(
        $ctl,
        $companyId
    ) {
        // Get the instance of CI object
        $ci = &get_instance();
        // Get the called controller name
        $ctl = trim(strtolower(preg_replace('/[^a-zA-Z]/', '', $ctl ? $ctl : $ci->router->fetch_class())));
        // If not a controller then pass
        if ($ctl == '') return;
        //
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
        if (!sizeof($b)) return true;

        // Lets check if module is activated against logged in company
        $a = $ci
            ->db
            ->where('module_sid', $b['sid'])
            ->where('company_sid', $companyId)
            ->where('is_active', 1)
            ->get('company_modules');
        //
        $b = $a->row_array();
        $a->free_result();
        //
        if (!sizeof($b)) {
            return false;
        }
        //
        return true;
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

if (!function_exists('get_employee_profile_info')) {
    function get_employee_profile_info($emp_id)
    {
        $CI = &get_instance();
        $CI->db->select('first_name, last_name, email, timezone, access_level, job_title, is_executive_admin, access_level_plus, pay_plan_flag, profile_picture, PhoneNumber');
        $CI->db->where('sid', $emp_id);
        return $CI->db->get('users')->row_array();
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

if (!function_exists('db_get_employee_profile')) {
    function db_get_employee_profile($emp_id)
    {
        $CI = &get_instance();
        $CI->db->select('first_name, last_name, email, access_level, job_title, is_executive_admin, access_level_plus, pay_plan_flag, timezone,middle_name');
        $CI->db->where('sid', $emp_id);
        return $CI->db->get('users')->result_array();
    }
}

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


if (!function_exists('log_and_sendEmail')) {

    function log_and_sendEmail($from, $to, $subject, $body, $senderName)
    {
        $CI = &get_instance();
        if (empty($to) || $to == NULL) return 0;
        //
        $emailData = array(
            'date' => date('Y-m-d H:i:s'),
            'subject' => $subject,
            'email' => $to,
            'message' => $body,
            'username' => $senderName,
        );
        //
        $CI->db->insert('email_log', $emailData);
        $result = $CI->db->insert_id();
        //
        if (base_url() != STAGING_SERVER_URL)
            sendMail($from, $to, $subject, $body, $senderName);
    }
}

if (!function_exists('sendResponse')) {

    function sendResponse($response)
    {
        header('Content-Type: application/json');
        echo json_encode($response);
    }


    if (!function_exists('res')) {
        function res($in)
        {
            header('Content-Type: application/json');
            echo json_encode($in);
            exit(0);
        }
    }
}


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

if (!function_exists("checkAnyManualCourseAssigned")) {
    function checkAnyManualCourseAssigned(&$employeeId): bool
    {
        //
        $ci = &get_instance();
        //
        $count = $ci
            ->db
            ->where('employee_sid', $employeeId)
            ->count_all_results('lms_manual_assign_employee_course');
        //
        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('checkEmployeeIsLMSManager')) {
    /**
     * get lms employee teams and departments
     *
     * @param int $employeeId
     * @param int $companyId
     */
    function checkEmployeeIsLMSManager(int $employeeId, $companyId): bool
    {
        // set default
        $r = [
            'departments' => [],
            'teams' => [],
            'employees' => []
        ];
        //
        $CI = &get_instance();
        //
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


        $CI->db->group_start()
            ->where("FIND_IN_SET({$employeeId}, departments_management.lms_managers_ids) > 0", null, null)
            ->or_where("FIND_IN_SET({$employeeId}, departments_team_management.lms_managers_ids) > 0", null, null)
            ->group_end();

        $departmentAndTeams = $CI->db->get('departments_team_management');
        //
        $departmentAndTeams = $departmentAndTeams->result_array(); 
        //
        if ($departmentAndTeams) {
            return true;
        } else {
            return false;
        }
    }
}    

if (!function_exists('getEmployeeLoginId')) {
    function getEmployeeLoginId($employeeId, $companyId)
    {
        $CI = &get_instance();
        $CI->db->select('logged_in_sid');
        $CI->db->where('executive_admin_sid', $employeeId);
        $CI->db->where('company_sid', $companyId);
        $record_obj = $CI->db->get('executive_user_companies');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();
     
        return $record_arr['logged_in_sid'];
    }
}

if (!function_exists('getMyDepartmentAndTeams')) {
    /**
     * get employee teams and departments
     *
     * @param int $employeeId
     * @param string $flag
     * @param string $method
     */
    function getMyDepartmentAndTeams(int $employeeId, string $flag = "", string $method = "get", int $companyId = 0, array $filters): array
    {
        //
        $CI = &get_instance();
        //
        // set default
        $r = [
            'departments' => [],
            'teams' => [],
            'employees' => []
        ];

        //
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
        // 
        $CI->db->group_start()
            ->where("FIND_IN_SET({$employeeId}, departments_management.lms_managers_ids) > 0", null, null)
            ->or_where("FIND_IN_SET({$employeeId}, departments_team_management.lms_managers_ids) > 0", null, null)
            ->group_end();
        //
        if ($method == "count_all_results") {
            $CI->db->limit(1);
        }
        //
        $departmentAndTeams = $CI->db->$method('departments_team_management');
        //
        if ($method == "count_all_results") {
            return $departmentAndTeams  ? [1] : [];
        }
        //
        $departmentAndTeams = $departmentAndTeams->result_array();
        //
        if (!empty($departmentAndTeams)) {
            //
            foreach ($departmentAndTeams as $team) {
                $r['teams'][$team["team_sid"]] = array(
                    "department_sid" => $team["sid"],
                    "department_name" => $team["name"],
                    "sid" => $team["team_sid"],
                    "name" => $team["team_name"],
                    "employees_ids" => []
                );
                //
            }
            //
            $teamSids = array_column($departmentAndTeams, "team_sid");
            //
            $TeamEmployees = $CI->db->select("
                            department_sid, 
                            team_sid, 
                            employee_sid
                        ")
                ->where_in('team_sid', $teamSids)
                ->get('departments_employee_2_team')
                ->result_array();
            //
            $alreadyExist = [];
            $employees = [];
            //
            foreach ($TeamEmployees as $employee) {
                //
                if (!in_array($employee['employee_sid'], $alreadyExist)) {
                    array_push($alreadyExist, $employee['employee_sid']);
                    $jobTitleInfo = $CI->db->select("
                        users.job_title,
                        users.first_name,
                        users.last_name,
                        users.access_level,
                        users.timezone,
                        users.access_level_plus,
                        users.is_executive_admin,
                        users.pay_plan_flag,
                        users.lms_job_title,
                        users.profile_picture,
                        users.email,
                        portal_job_title_templates.sid as job_title_sid
                    ")
                        ->join(
                            "portal_job_title_templates",
                            "portal_job_title_templates.sid = users.lms_job_title",
                            "left"
                        )
                        ->where('users.sid', $employee['employee_sid'])
                        ->where([
                            "users.active" => 1,
                            "users.terminated_status" => 0,
                            "users.is_executive_admin" => 0
                        ])
                        ->get('users')
                        ->row_array();
                    if (!$jobTitleInfo || !$jobTitleInfo["first_name"]) {
                        continue;
                    }
                    //
                    $jobTitleId = !empty($jobTitleInfo['job_title_sid']) ? $jobTitleInfo['job_title_sid'] : 0;
                    //  
                    $employeeName = remakeEmployeeName([
                        'first_name' => $jobTitleInfo['first_name'],
                        'last_name' => $jobTitleInfo['last_name'],
                        'access_level' => $jobTitleInfo['access_level'],
                        'timezone' => isset($jobTitleInfo['timezone']) ? $jobTitleInfo['timezone'] : '',
                        'access_level_plus' => $jobTitleInfo['access_level_plus'],
                        'is_executive_admin' => $jobTitleInfo['is_executive_admin'],
                        'pay_plan_flag' => $jobTitleInfo['pay_plan_flag'],
                        'job_title' => $jobTitleInfo['job_title'],
                    ]);
                   
                    //
                    $employees[$employee['employee_sid']]["job_title_sid"] =  !empty($jobTitleInfo['job_title_sid']) ? $jobTitleInfo['job_title_sid'] : 0;
                    $employees[$employee['employee_sid']]["full_name"] = $employeeName;
                    $employees[$employee['employee_sid']]["profile_picture_url"] = getImageURL($jobTitleInfo["profile_picture"]);
                    $employees[$employee['employee_sid']]["only_name"] = remakeEmployeeName($jobTitleInfo, true, true);
                    $employees[$employee['employee_sid']]["designation"] = remakeEmployeeName($jobTitleInfo, false);
                    $employees[$employee['employee_sid']]["employee_sid"] = $employee['employee_sid'];
                    $employees[$employee['employee_sid']]["department_sid"] = $employee['department_sid'];
                    $employees[$employee['employee_sid']]["team_sid"] = $employee['team_sid'];
                    $employees[$employee['employee_sid']]["lms_job_title"] = $employee['lms_job_title'];
                    $employees[$employee['employee_sid']]["email"] = $jobTitleInfo['email'];
                    //
                    $employeeData = [];
                    $employeeData["employee_sid"] = $employee['employee_sid'];
                    $employeeData["job_title_sid"] =  $jobTitleId;
                    $employeeData["employee_name"] =  $employeeName;
                    //
                    if ($flag == 'courses') {
                        //
                        if ($jobTitleInfo['lms_job_title'] != 0) {
                            $today = date('Y-m-d');
                            //
                            $CI->db->select("
                                    lms_default_courses.sid
                                ");
                            $CI->db->join(
                                    "lms_default_courses_job_titles",
                                    "lms_default_courses_job_titles.lms_default_courses_sid = lms_default_courses.sid",
                                    "right"
                            );
                            //
                            if ($filters['courses'] != "all") {
                                $CI->db->where_in('lms_default_courses.sid', $filters['courses']);
                            }
                            //
                            $CI->db->where('lms_default_courses.company_sid', $companyId);
                            $CI->db->where('lms_default_courses.is_active', 1);
                            $CI->db->where('course_start_period <=', $today);
                            $CI->db->group_start();
                            $CI->db->where('lms_default_courses_job_titles.job_title_id', -1);
                            $CI->db->or_where('lms_default_courses_job_titles.job_title_id', $jobTitleId);
                            $CI->db->group_end();
                                // ->get('lms_default_courses')
                                // ->result_array();
                            $companyCourses = $CI->db->get('lms_default_courses')->result_array();    
                            //                       
                            $assignCourses = !empty($companyCourses) ? implode(',', array_column($companyCourses, "sid")) : "";
                            //
                            //manual AssignedCourses
                            //
                            $manualAssignedCourses = $CI->db
                                ->select('default_course_sid')
                                ->from('lms_manual_assign_employee_course')
                                ->where('employee_sid', $employee['employee_sid'])
                                ->where('company_sid', $companyId)
                                ->get()
                                ->result_array();
                            //
                            $manualAssignedCoursesList = !empty($manualAssignedCourses) ? ',' . implode(',', array_column($manualAssignedCourses, "default_course_sid")) : "";
                            //
                            $employees[$employee['employee_sid']]["assign_courses"] = $assignCourses . $manualAssignedCoursesList;
                            $employees[$employee['employee_sid']]["coursesInfo"] = getCoursesInfo($assignCourses . $manualAssignedCoursesList, $employee['employee_sid']);
                            $employeeData["assign_courses"] =  array_merge($assignCourses, $manualAssignedCourses);
                        } else if (checkAnyManualCourseAssigned($employee['employee_sid'])) {
                            //
                            $manualAssignedCourses = $CI->db
                                ->select('default_course_sid')
                                ->from('lms_manual_assign_employee_course')
                                ->where('employee_sid', $employee['employee_sid'])
                                ->where('company_sid', $companyId)
                                ->get()
                                ->result_array();
                            //
                            $manualAssignedCoursesList = !empty($manualAssignedCourses) ? implode(',', array_column($manualAssignedCourses, "default_course_sid")) : "";
                            //
                            $employees[$employee['employee_sid']]["assign_courses"] = $manualAssignedCoursesList;
                            $employees[$employee['employee_sid']]["coursesInfo"] = getCoursesInfo($manualAssignedCoursesList, $employee['employee_sid']);
                            $employeeData["assign_courses"] =  $manualAssignedCoursesList;
                            
                        } else {
                            $employees[$employee['employee_sid']]["assign_courses"] = "";
                            $employeeData["assign_courses"] =  "";
                        }
                    }

                    //
                    if (isset($r['departments'][$employee['department_sid']])) {
                        $r['departments'][$employee['department_sid']]['employees_ids'][] = $employeeData;
                    }
                    //
                    if (isset($r['teams'][$employee['team_sid']])) {
                        $r['teams'][$employee['team_sid']]['employees_ids'][] = $employeeData;
                    }
                    //
                }
            }
            //
            $r['employees'] = $employees;
        }
        //
        return $r;
    }
}

if (!function_exists('getCoursesInfo')) {
    /**
     * Prefill he form data
     * 
     * @param string $ids,
     * @param int $employeeId,
     * @return array
     */
    function getCoursesInfo(
        string $ids,
        int $employeeId
    ): array {
        //
        $result = [
            'total_course' => 0,
            'expire_soon' => 0,
            'expired' => 0,
            'started' => 0,
            'completed' => 0,
            'ready_to_start' => 0
        ];
        //
        // get the company courses
        $assignCourses = get_instance()->db
            ->select('sid, course_start_period, course_end_period')
            ->from('lms_default_courses')
            ->where_in('sid', explode(',', $ids))
            ->get()
            ->result_array();
        //
        // if no courses are found
        if (!$assignCourses) {
            return $result;
        }
        //
        $now = new DateTime(date("Y-m-d"));
        // loop through courses
        foreach ($assignCourses as $course) {
            //
            $courseStatus = getEmployeeCourseStatus($course['sid'], $employeeId);
            //
            $start_period = new DateTime($course['course_start_period']);
            $end_period = new DateTime($course['course_end_period']);
            //
            $start_diff = $now->diff($start_period)->format("%r%a");
            $end_diff = $now->diff($end_period)->format("%r%a");
            //
            if ($courseStatus == 'not_started') {
                if ($start_diff < 0 && $end_diff > 0) {
                    //
                    if ($end_diff < 15) {
                        $result['expire_soon']++;
                    }
                    //
                } else if ($start_diff < 0 && $end_diff < 0) {
                    $result['expired']++;
                }
                //
                $result['ready_to_start']++;
            } else if ($courseStatus == 'started') {
                $result['started']++;
            } else if ($courseStatus == 'completed') {
                $result['completed']++;
            }
            //
            $result['total_course']++;
            //
        }
        //
        return $result;
    }
}

if (!function_exists('getEmployeeCourseStatus')) {
    /**
     * Prefill he form data
     * 
     * @param int $courseId,
     * @param int $employeeId,
     * @return string
     */
    function getEmployeeCourseStatus(
        int $courseId,
        int $employeeId
    ): string {
        // get the company courses
        $courseInfo = get_instance()->db
            ->select('lesson_status')
            ->from('lms_employee_course')
            ->where('course_sid', $courseId)
            ->where('employee_sid', $employeeId)
            ->get()
            ->row_array();
        //
        // if no courses are found
        if (!$courseInfo) {
            return 'not_started';
        } else if ($courseInfo['lesson_status'] == 'completed') {
            return 'completed';
        } else if ($courseInfo['lesson_status'] == 'incomplete') {
            return 'started';
        } 
    }
}


//
if (!function_exists('getUserFields')) {
    function getUserFields()
    {
        $fields  = 'users.sid as userId,';
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