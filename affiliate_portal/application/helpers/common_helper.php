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
    function message_header_footer($sid)
    {
        $CI = &get_instance();
        $CI->db->select('company_name');
        $CI->db->where('sid', $sid);
        $CI->db->from('marketing_agencies');

        $record_obj = $CI->db->get();
        $result = $record_obj->result_array();
        $record_obj->free_result();
        $company_name = '';

        if (!empty($result)) {
            $company_name = $result[0]['company_name'];
        } else {
            $company_name = STORE_NAME;
        }

        $data['header'] = '<div class="content" style="font-size: 100%; line-height: 1.6em; display: block; max-width: 1000px; margin: 0 auto; padding: 0; position:relative"><div style="width:100%; float:left; padding:5px 20px; text-align:center; box-sizing:border-box; background-color:#0000FF;"><h2 style="color:#fff;">' . $company_name . '</h2></div>  <div class="body-content" style="width:100%; float:left; padding:20px 0; box-sizing:padding-box;">';
        $data['footer'] = '</div><div class="footer" style="width:100%; float:left; background-color:#0000FF; padding:20px 30px; box-sizing:border-box;"><div style="float:left; width:100%; "><p style="color:#fff; float:left; text-align:center; font-style:italic; line-height:normal; font-family: "Open Sans", sans-serif; font-weight:600; font-size:14px;"><a style="color:#fff; text-decoration:none;" href="' . STORE_FULL_URL . '">&copy; ' . date('Y') . ' ' . FROM_STORE_NAME . '. All Rights Reserved.</a></p></div></div></div>';
        $data['company_name'] = $company_name;
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
        // attach the SMTP creds
        attachSMTPToMailer($mail, $from);
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
        // attach the SMTP creds
        attachSMTPToMailer($mail, $from);
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
        // attach the SMTP creds
        attachSMTPToMailer($mail, $from);
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
        // attach the SMTP creds
        attachSMTPToMailer($mail, $from);
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
        // attach the SMTP creds
        attachSMTPToMailer($mail, $from);
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
        // attach the SMTP creds
        attachSMTPToMailer($mail, $from);
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
        $record_obj = $CI->db->get();
        $result = $record_obj->result_array();
        $record_obj->free_result();
        return $result;
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
        $record_obj = $CI->db->get();
        $result = $record_obj->result_array();
        $record_obj->free_result();
        return $result;
    }
}

if (!function_exists('db_get_state_name_only')) {
    function db_get_state_name_only($state_sid)
    {
        $CI = &get_instance();
        $CI->db->select('state_name');
        $CI->db->where('sid', $state_sid);
        $CI->db->from('states');
        $record_obj = $CI->db->get();
        $data = $record_obj->result_array();
        $record_obj->free_result();

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
        $record_obj = $CI->db->get();
        $result = $record_obj->result_array();
        $record_obj->free_result();
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

        $record_obj = $CI->db->get();
        $result = $record_obj->result_array();
        $record_obj->free_result();

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
        $record_obj = $CI->db->get();
        $result = $record_obj->result_array();
        $record_obj->free_result();
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
        $record_obj = $CI->db->get();
        $result = $record_obj->result_array();
        $record_obj->free_result();

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
        $record_obj = $CI->db->get();
        $result = $record_obj->result_array();
        $record_obj->free_result();

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
        $CI->db->select('sid');
        $CI->db->where('job_sid', $job_id);
        $CI->db->where('company_sid', $company_sid);
        return $CI->db->count_all_results('portal_applicant_jobs_list');
        //        $result = $CI->db->get('portal_applicant_jobs_list');
        //        return $result->num_rows();
    }
}


if (!function_exists('get_company_details')) {
    function get_company_details($company_sid)
    {
        $CI = &get_instance();
        $CI->db->select('*');
        $CI->db->where('parent_sid', 0);
        $CI->db->where('sid', $company_sid);
        $CI->db->from('users');
        $record_obj = $CI->db->get();
        $record_row = $record_obj->result_array();
        $record_obj->free_result();

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

        $record_obj = $CI->db->get();
        $result = $record_obj->result_array();
        $record_obj->free_result();

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

if (!function_exists('upload_file_to_aws')) {
    function upload_file_to_aws($file_input_id, $company_sid, $document_name, $suffix = '', $bucket_name = AWS_S3_BUCKET_NAME)
    {
        $CI = &get_instance();

        if (isset($_FILES[$file_input_id]) && $_FILES[$file_input_id]['name'] != '') {
            $last_index_of_dot = strrpos($_FILES[$file_input_id]["name"], '.') + 1;
            $file_ext = substr($_FILES[$file_input_id]["name"], $last_index_of_dot, strlen($_FILES[$file_input_id]["name"]) - $last_index_of_dot);
            $file_name = trim($document_name . '-' . $suffix);
            $file_name = str_replace(" ", "_", $file_name);
            $file_name = strtolower($file_name);
            $prefix = str_pad($company_sid, 4, '0', STR_PAD_LEFT);
            $new_file_name = $prefix . '-' . $file_name . '-' . generateRandomString(3) . '.' . $file_ext;

            $CI->load->library('aws_lib');

            $options = [
                'Bucket' => $bucket_name,
                'Key' => $new_file_name,
                'Body' => file_get_contents($_FILES[$file_input_id]["tmp_name"]),
                'ACL' => 'public-read',
                'ContentType' => $_FILES[$file_input_id]["type"]
            ];

            $CI->aws_lib->put_object($options);
            return $new_file_name;
        } else {
            return 'error';
        }
    }
}

if (!function_exists('db_get_access_level_details')) {
    function db_get_access_level_details($sid = NULL, $access_level = NULL)
    {

        if ($sid != NULL) {
            $CI = &get_instance();
            $data = array();
            $data['session'] = $CI->session->userdata('affiliate_loggedin');
            $employer_detail = $data['session']['affiliate_users'];
            $access_level = $employer_detail['access_level'];

            if ($access_level != 'Admin') {
                //                $CI->db->select('permissions');
                //                $CI->db->where('access_level', $access_level);
                //                $security_access_level = $CI->db->get('security_access_level')->result_array();
                $permissions = array('payment_voucher', 'refer_client', 'view_referred_clients', 'paying_clients', 'refer_affiliate', 'view_referred_affiliates');
                //                $access_level_permissions = $security_access_level[0]['permissions'];
                //                if (!empty($access_level_permissions)) {
                //                    $allpermissions = unserialize($access_level_permissions);
                //
                //                    foreach ($allpermissions as $permission) {
                //                        $permissions[] = strtolower($permission['function_name']);
                //                    }
                //                }
            } else {
                $permissions[] = 'full_access';
            }
            $data['session']['affiliate_users']['a_permissions'] = $permissions;
            $CI->session->set_userdata('affiliate_loggedin', $data['session']);

            return $permissions;
        }
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
            $CI->session->set_flashdata('message', SECURITY_PERMISSIONS_ERROR);
            redirect($reload_location, "location");
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

if (!function_exists('get_affiliate_name_and_email')) {

    function get_affiliate_name_and_email($sid)
    {
        if (filter_var($sid, FILTER_VALIDATE_EMAIL)) {
            $return_array['full_name'] = $sid;
            $return_array['email'] =  $sid;
            return $return_array;
        } else if ($sid == 'administrator') {
            $CI = &get_instance();
            $CI->db->where('id', 1);
            $CI->db->where('active', 1);
            $CI->db->from('administrator_users');

            $record_obj = $CI->db->get();
            $record_arr = $record_obj->result_array();
            $record_obj->free_result();

            if (sizeof($record_arr) > 0) {
                $return_array['full_name'] = $record_arr[0]['first_name'] . ' ' . $record_arr[0]['first_name'];
                $return_array['email'] = $record_arr[0]['email'];
                return $return_array;
            }
        } else {
            $CI = &get_instance();

            $CI->db->select('full_name, email');
            $CI->db->where('sid', $sid);
            $CI->db->from('marketing_agencies');

            $record_obj = $CI->db->get();
            $record_arr = $record_obj->result_array();
            $record_obj->free_result();

            if (sizeof($record_arr) > 0) {
                return $record_arr[0];
            } else {
                return array();
            }
        }
    }
}

if (!function_exists('get_e_signature')) {
    function get_e_signature($company_sid, $user_sid, $user_type)
    {
        $CI = &get_instance();

        $CI->db->select('*');
        $CI->db->where('company_sid', $company_sid);
        $CI->db->where('user_sid', $user_sid);
        $CI->db->where('user_type', $user_type);
        $CI->db->where('is_active', 1);
        $CI->db->limit(1);
        $CI->db->from('e_signatures_data');

        $records_obj = $CI->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }
}

if (!function_exists('set_e_signature')) {

    function set_e_signature($form_post)
    {
        $company_sid = 0;
        $user_type = 'affiliate';
        $user_sid = $form_post['affiliate_sid'];
        $ip_address = $form_post['ip_address'];
        $user_agent = $form_post['user_agent'];
        $first_name = $form_post['full_name'];
        $last_name = '';
        $email_address = $form_post['email_address'];
        $signature_timestamp = date('Y-m-d H:i:s');
        $signature = $form_post['signature'];
        $init_signature = $form_post['init_signature'];
        $user_consent = $form_post['user_consent'];
        $signature_hash = md5($signature);
        $active_signature = $form_post['active_signature'];
        $drawn_signature = $form_post['drawn_signature'];
        $drawn_init_signature = $form_post['drawn_init_signature'];

        $CI = &get_instance();
        $data_to_save = array();
        $data_to_save['user_type'] = $user_type;
        $data_to_save['user_sid'] = $user_sid;
        $data_to_save['company_sid'] = $company_sid;
        $data_to_save['ip_address'] = $ip_address;
        $data_to_save['user_agent'] = $user_agent;
        $data_to_save['first_name'] = $first_name;
        $data_to_save['last_name'] = $last_name;
        $data_to_save['email_address'] = $email_address;
        $data_to_save['signature_timestamp'] = $signature_timestamp;
        $data_to_save['signature'] = $signature;
        $data_to_save['init_signature'] = $init_signature;
        $data_to_save['signature_hash'] = $signature_hash;
        $data_to_save['user_consent'] = $user_consent == 1 ? 1 : 0;
        $data_to_save['is_active'] = 1;
        $data_to_save['signature_bas64_image'] = $drawn_signature;
        $data_to_save['init_signature_bas64_image'] = $drawn_init_signature;
        $data_to_save['active_signature'] = $active_signature;

        $CI->db->insert('e_signatures_data', $data_to_save);

        return $CI->db->insert_id();
    }
}

if (!function_exists('get_print_document_url')) {
    function get_print_document_url($message_sid)
    {
        $urls = '';

        $CI = &get_instance();
        $CI->db->select('attachment');
        $CI->db->where('sid', $message_sid);
        $CI->db->from('private_message_attachments');
        $CI->db->limit(1);
        $result = $CI->db->get()->result_array();

        $upload_document = $result[0]['attachment'];
        $file_name = explode(".", $upload_document);
        $document_name = $file_name[0];
        $document_extension = $file_name[1];

        if ($document_extension == 'pdf') {
            $urls['print_url'] = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $document_name . '.pdf';
        } else if ($document_extension == 'doc') {
            $urls['print_url'] = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $document_name . '%2Edoc&wdAccPdf=0';
        } else if ($document_extension == 'docx') {
            $urls['print_url'] = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $document_name . '%2Edocx&wdAccPdf=0';
        } else if ($document_extension == 'xls') {
            $urls['print_url'] = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $document_name . '%2Exls';
        } else if ($document_extension == 'xlsx') {
            $urls['print_url'] = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $document_name . '%2Exlsx';
        } else if (in_array($document_extension, ['jpe', 'jpg', 'jpeg', 'png', 'bmp', 'gif', 'svg'])) {
            $urls['print_url'] = base_url('hr_documents_management/print_generated_and_offer_later/original/generated/' . $document_sid);
        }

        $urls['download_url'] = base_url('messages/download_attachment/' . $upload_document);

        return $urls;
    }
}

/**
 * Generates dropdown for timezone
 * Created on: 19-07-2019
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
        $timezone_rows .= '<option value="">Please Select</option>';
        if (sizeof($timezones)) foreach ($timezones as $k0 => $v0) $timezone_rows .= '<option ' . ($selected == $v0['key'] ? 'selected="true"' : '') . ' value="' . ($v0['key']) . '">' . ($v0['name']) . ' (' . ($v0['key']) . ')</option>';
        $timezone_rows .= '</select>';
        return $timezone_rows;
    }
}

/**
 * Fetch timezones
 * Created on: 19-07-2019
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
 * Created on: 19-07-2019
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
        if ($_this->session->userdata('affiliate_loggedin')) {
            // If the type is user
            if ($type == 'user') {
                if (clean_string($_this->session->userdata('affiliate_loggedin')['affiliate_users'], 'timezone') == '') {
                    // Check for companys timezone
                    if (clean_string($_this->session->userdata('affiliate_loggedin')['affiliate_users'], 'timezone') == '') $timezone = STORE_DEFAULT_TIMEZONE_ABBR;
                    else $timezone = $_this->session->userdata('affiliate_loggedin')['affiliate_users']['timezone'];
                } else $timezone = $_this->session->userdata('affiliate_loggedin')['affiliate_users']['timezone'];
            } else if ($type == 'company') { // For company
                if (clean_string($_this->session->userdata('affiliate_loggedin')['affiliate_users'], 'timezone') == '') $timezone = STORE_DEFAULT_TIMEZONE_ABBR;
                else $timezone = $_this->session->userdata('affiliate_loggedin')['affiliate_users']['timezone'];
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
 * Created on: 19-07-2019
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
 * Created on: 19-07-2019
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
 * Created on: 19-07-2019
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
 * Check for index in array and for empty values
 * Created on: 19-07-2019
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
        if (!is_dir($tp)) mkdir($tp, 0777, true);
        // Create full path
        $tfp = $tp . $awsFileName;
        // Delete if file already exists
        if (file_exists($tfp)) unlink($tfp);
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
        $files['public/v1/css/app/affiliate-login'] = ["css" => "2.0"];
        $files['public/v1/js/app/affiliate-login'] = ["js" => "2.0"];
        // forgot
        $files['public/v1/css/app/affiliate-forgot'] = ["css" => "2.0"];
        $files['public/v1/js/app/affiliate-forgot'] = ["js" => "2.0"];
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
        $absolutePath = str_replace("affiliate_portal/", "", ROOTPATH . $destination);
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
        $absolutePath = str_replace("affiliate_portal/", "", ROOTPATH . $destination);
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
                'png', 'jpg', 'jpeg', 'gif', 'webp'
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
            return base_url('assets/images/img-applicant.jpg');
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
