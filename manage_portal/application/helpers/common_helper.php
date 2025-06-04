<?php

defined('BASEPATH') or exit('No direct script access allowed');
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

if (!function_exists('get_company_logo')) {

    function get_company_logo($company_sid)
    {
        if (empty($company_sid)) {
            return 0;
        }

        $CI = &get_instance();
        $CI->db->select('Logo');
        $CI->db->where('sid', $company_sid);
        $result = $CI->db->get('users')->result_array();

        if (isset($result[0]) && !empty($result[0])) {
            return $result[0]['Logo'];
        } else {
            return 0;
        }
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


if (!function_exists('random_key')) {

    function render($the_view = NULL, $template = 'master')
    {
        if ($template == 'json' || $this->input->is_ajax_request()) {
            header('Content-Type: application/json');
            echo json_encode($this->data);
        } else {
            $this->data['the_view_content'] = (is_null($the_view)) ? '' : $this->load->view($the_view, $this->data, TRUE);
            ;
            $this->load->view('theme1/' . $template . '_view', $this->data);
        }
    }
}


if (!function_exists('render')) {

    function render($the_view = NULL, $template = 'master')
    {
        echo "inside render function in common helper";
        exit;
        if ($template == 'json' || $this->input->is_ajax_request()) {
            header('Content-Type: application/json');
            echo json_encode($this->data);
        } else {
            $this->data['the_view_content'] = (is_null($the_view)) ? '' : $this->load->view($the_view, $this->data, TRUE);
            $this->load->view('templates/' . $template . '_view', $this->data);
        }
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
        //
        mailAWSSES($mail, $to);
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
        return $result[0];
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
            $data['header'] = '';
            $data['footer'] = '';
            if ($result) {
                $domain_name = $result[0]['sub_domain'];
                $data['header'] = '<div class="content" style="font-size: 100%; line-height: 1.6em; display: block; max-width: 1000px; margin: 0 auto; padding: 0; position:relative"><div style="width:100%; float:left; padding:5px 20px; text-align:center; box-sizing:border-box; background-color:#0000FF;"><h2 style="color:#fff;">' . $company_Name . '</h2></div>  <div class="body-content" style="width:100%; float:left; padding:20px 20px 60px 20px; box-sizing:border-box; background:url(images/bg-body.jpg);">';
                $data['footer'] = '</div><div class="footer" style="width:100%; float:left; background-color:#0000FF; padding:20px 30px; box-sizing:border-box;"><div style="float:left; width:100%; "><p style="color:#fff; text-align:center; font-style:italic; line-height:normal; font-family: "Open Sans", sans-serif; font-weight:600; font-size:14px;"><a style="color:#fff; text-decoration:none;" href="' . STORE_PROTOCOL . $domain_name . '">' . $domain_name . '</a></p></div></div></div>';
            }
            return $data;
        }
    }
}

if (!function_exists('db_get_job_title')) {

    function db_get_job_title($user_sid, $title, $city, $state, $country)
    {
        $CI = &get_instance();
        $CI->db->select('job_title_location');
        $CI->db->where('user_sid', $user_sid);
        $CI->db->from('portal_employer');
        $result = $CI->db->get()->result_array();
        if (!empty($result)) {
            if ($result[0]['job_title_location']) {
                $jobTitle = $title . '  - ' . ucfirst($city) . ', ' . $state . ', ' . $country;
            } else {
                $jobTitle = $title;
            }
            return $jobTitle;
        }
    }
}

if (!function_exists('db_get_job_category')) {

    function db_get_job_category($company_sid = NULL)
    {
        $CI = &get_instance();
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
        $result_data_list = $CI->db->get('listing_field_list');

        foreach ($result_data_list->result_array() as $row_data_list) {
            $data_list[] = array("id" => $row_data_list['sid'], "value" => $row_data_list['value']);
        }

        return $data_list;
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

if (!function_exists('save_email_log_common')) {
    function save_email_log_common($from, $to, $subject, $message, $admin = '')
    {
        $CI = &get_instance();

        $emailData = array(
            'date' => date('Y-m-d H:i:s'),
            'subject' => $subject,
            'email' => $to,
            'message' => $message,
            'admin' => $admin,
            'username' => $from
        );

        $CI->db->insert('email_log', $emailData);
        $result = $CI->db->insert_id();
        return $result;
    }
}

if (!function_exists('is_subdomain_of_automotohr')) {
    function is_subdomain_of_automotohr()
    {
        $base = strtoupper(base_url());

        $baseParts = explode('.', $base);

        if (in_array('AUTOMOTOHR', $baseParts)) {
            return true;
        } else {
            return false;
        }
    }
}


if (!function_exists('send_templated_email')) {

    function send_templated_email($template_id, $to, $replacement_array = array(), $message_hf = array(), $log_email = 0)
    {
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

        if ($log_email == 1) {
            log_and_sendEmail($from, $to, $subject, $body, $from_name);
        } else {
            sendMail($from, $to, $subject, $body, $from_name);
        }
    }
}

if (!function_exists('log_and_send_templated_notification_email')) {

    function log_and_send_templated_notification_email($template_id, $to, $replacement_array = array(), $message_hf = array(), $company_sid, $job_sid, $notification_type)
    {
        $CI = &get_instance();

        $CI->load->model('common/job_details');

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

        $CI->job_details->log_notifications_email($company_sid, $from, $to, $subject, $body, $job_sid, $notification_type);
    }
}

if (!function_exists('get_notification_email_contacts')) {
    function get_notification_email_contacts($company_sid, $notification_type, $job_sid = 0)
    {
        $CI = &get_instance();
        $contacts = array();

        if ($job_sid > 0) {
            $CI->db->select('users.active as userActive');
            $CI->db->select('users.terminated_status');
            $CI->db->select('users.email as userEmail');
            $CI->db->select('portal_job_listings_visibility.employer_sid');
            $CI->db->select('notifications_emails_management.employer_sid as nem_employer_sid');
            $CI->db->select('notifications_emails_management.email as email');
            $CI->db->where('portal_job_listings_visibility.job_sid', $job_sid);
            $CI->db->where('notifications_emails_management.company_sid', $company_sid);
            $CI->db->where('notifications_emails_management.notifications_type', $notification_type);
            $CI->db->where('notifications_emails_management.status', 'active');
            $CI->db->join('notifications_emails_management', 'notifications_emails_management.employer_sid = portal_job_listings_visibility.employer_sid', 'left');
            $CI->db->join('users', 'notifications_emails_management.employer_sid = users.sid', 'left');
            $contacts = $CI->db->get('portal_job_listings_visibility')->result_array();
        } else {
            $CI->db->select('notifications_emails_management.*');
            $CI->db->select('users.active as userActive');
            $CI->db->select('users.terminated_status');
            $CI->db->select('users.email as userEmail');
            $CI->db->where('notifications_emails_management.company_sid', $company_sid);
            $CI->db->where('notifications_emails_management.notifications_type', $notification_type);
            $CI->db->where('notifications_emails_management.status', 'active');
            $CI->db->join('users', 'notifications_emails_management.employer_sid = users.sid', 'left');
            $contacts = $CI->db->get('notifications_emails_management')->result_array();
        }

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

        $all_none_employee_contacts = array();

        $CI->db->select('*');
        $CI->db->where('company_sid', $company_sid);
        $CI->db->where('employer_sid', 0);
        $CI->db->where('notifications_type', $notification_type);
        $CI->db->where('status', 'active');

        $all_none_employee_contacts = $CI->db->get('notifications_emails_management')->result_array();

        foreach ($all_none_employee_contacts as $key => $contact) {
            $all_none_employee_contacts[$key]['nem_employer_sid'] = 0;
        }

        $all_contacts = array();
        $all_contacts = array_merge($contacts, $all_none_employee_contacts);

        foreach ($all_contacts as $key => $contact) {
            if (!isset($contact['email']) || $contact['email'] == null || $contact['email'] == '') {
                unset($contacts[$key]);
            }
        }

        $result = unique_multi_dimension_array($all_contacts, 'email');
        return $result;
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
            $CI->db->limit(1);
            $CI->db->where('sid', intval($company_sid) + 1);
            $CI->db->where('parent_sid', $company_sid);
            $admin = $CI->db->get('users')->result_array();

            if (empty($admin)) {
                $CI->db->select('*');
                $CI->limit(1);
                $CI->db->where('sid', $company_sid);
                $admin = $CI->db->get('users')->result_array();
            }
        }

        if (!empty($admin)) {
            $admin = $admin[0];
        }

        return $admin;
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
            $CI->db->limit(1);
            $CI->db->where('sid', intval($company_sid) + 1);
            $CI->db->where('parent_sid', $company_sid);
            $admin = $CI->db->get('users')->result_array();

            if (empty($admin)) {
                $CI->db->select('*');
                $CI->limit(1);
                $CI->db->where('sid', $company_sid);
                $admin = $CI->db->get('users')->result_array();
            }
        }

        if (!empty($admin)) {
            $admin = $admin[0];
        }

        return $admin;
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
        $domain_name = '';

        if (!empty($result)) {
            $domain_name = $result[0]['sub_domain'];
        }

        return $domain_name;
    }
}


//Function to compare dates
if (!function_exists('jobs_array_date_compare')) {
    function jobs_array_date_compare($a, $b)
    {
        $t1 = strtotime($a['activation_date']);
        $t2 = strtotime($b['activation_date']);
        return $t1 - $t2;
    }
}

if (!function_exists('get_company_logo_status')) {
    function get_company_logo_status($company_sid)
    {
        $CI = &get_instance();
        $CI->db->select('enable_company_logo');
        $CI->db->where('user_sid', $company_sid);
        $CI->db->from('portal_employer');
        $CI->db->limit(1);
        $result = $CI->db->get()->result_array();
        $return_data = '';

        if (!empty($result)) {
            $return_data = $result[0]['enable_company_logo'];
        }

        return $return_data;
    }
}


/**
 * Format phone number
 * Created on: 11-07-2019
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
 * Display error
 * Created on: 29-04-2019
 *
 * @param $e String|Array|Object
 * @param $print Bool
 * @param $die Bool
 * @param $isHidden Bool
 *
 * @return VOID
 */
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
 * Genarats a captcha image
 * Created on: 02-08-2019
 *
 * @param $start Integer
 * Default '0'
 * @param $end Integer
 * Default '0'
 *
 * @return Array
 */
if (!function_exists('generateCaptcha')) {
    function generateCaptcha($start = 0, $end = 9)
    {
        // Generate two numbers
        $a = rand($start, $end);
        $b = rand($start, $end);
        // Set resturn array
        $returnArray = array();
        $returnArray['left'] = $a;
        $returnArray['right'] = $b;
        $returnArray['result'] = $a + $b;
        //
        $text = $a . ' + ' . $b;
        //
        $width = (strlen($text) * 9) + 20;
        $height = 30;
        //
        $textImage = imagecreate($width, $height);
        //
        imagecolortransparent($textImage, imagecolorallocate($textImage, 0, 0, 0));
        imagestring($textImage, 5, 10, 5, $text, 0xFFFFFF);
        // create background image layer
        $background = imagecreatefromjpeg(APPPATH . '../../assets/images/bgw.jpg');
        // Merge background image and text image layers
        imagecopymerge($background, $textImage, 15, 15, 0, 0, $width, $height, 100);
        //            
        $output = imagecreatetruecolor($width, $height);
        imagecopy($output, $background, 0, 0, 20, 13, $width, $height);
        //
        ob_start();
        imagepng($output);
        $returnArray['imageURI'] = base64_encode(ob_get_clean());

        return $returnArray;
    }
}

if (!function_exists('job_title_uri')) {
    function job_title_uri($job, $is_title = false, $onlyTitle = false)
    {
        //
        $companyName = '';
        //
        if (isset($job['CompanyName']) && !empty($job['CompanyName'])) {
            $companyName = strtolower(trim($job['CompanyName']));
        }
        //
        $title = '';
        //
        $jt = str_replace(',', '-', $job['Title']);
        $jt = preg_replace('/\s+/', ' ', $jt);
        //
        $jt = explode('-', $jt)[0];
        //
        if (!empty($companyName)) {
            $jt = preg_replace('/' . ($companyName) . '/', '', $jt);
        }
        //
        $title = ucwords($jt);
        //
        if ($onlyTitle) {
            return $title;
        }
        //
        $postfix = ' Job in';
        //
        if (!empty($job['Location_City'])) {
            $postfix .= ' ' . $job['Location_City'] . ',';
        }
        if (!empty($job['Location_State'])) {
            $postfix .= ' ' . $job['Location_State'] . ' at';
        } else {
            $postfix .= ' ' . $job['Location_Country'] . ' at ';
        }
        //
        $postfix .= ' ' . $job['CompanyName'];
        //
        $title .= '' . $postfix;
        //
        if ($is_title) {
            return $title;
        } else {
            $title = preg_replace("/[^A-Za-z0-9 ]/", '', $title);
            $title = str_replace(" ", "-", $title);
            $title = strtolower($title);
            return '/job_details/' . $title . "-" . $job['sid'];
        }
    }
}

if (!function_exists('job_meta_keywords')) {
    function job_meta_keywords($job)
    {
        $meta_keywords = "Job";
        if (!empty($job['TitleOnly'])) {
            $meta_keywords .= ", " . str_replace("-", ",", $job['TitleOnly']);
        }
        if (!empty($job['JobType'])) {
            $meta_keywords .= ', ' . $job['JobType'] . ' Job';
        }
        if (!empty($job['JobCategory'])) {
            $categories = @explode(",", $job['JobCategory']);
            if (is_array($categories)) {
                foreach ($categories as $category) {
                    $meta_keywords .= ', ' . $category . ' Jobs';
                }
            }
        }
        if (!empty($job['Location_City'])) {
            $meta_keywords .= ', ' . $job['Location_City'];
            $meta_keywords .= ', Jobs in ' . $job['Location_City'];
            $meta_keywords .= ', Best Jobs in ' . $job['Location_City'];
            $meta_keywords .= ', Search Jobs in ' . $job['Location_City'];
            if (!empty($job['JobCategory'])) {
                $categories = @explode(",", $job['JobCategory']);
                if (is_array($categories)) {
                    foreach ($categories as $category) {
                        $meta_keywords .= ', ' . $category . ' Jobs in ' . $job['Location_City'];
                    }
                }
            }
        }

        if (!empty($job['Location_State'])) {
            $meta_keywords .= ', Jobs in ' . $job['Location_State'];
        }
        $meta_keywords .= ', Jobs in ' . $job['Location_Country'];
        $brand = array_shift((explode('.', $_SERVER['HTTP_HOST'])));
        $brand = ucwords(str_replace("-", " ", $brand));
        $meta_keywords .= ', Jobs at ' . $brand;
        $meta_keywords .= ', Jobs portal';
        $meta_keywords .= ', Jobs on ' . ucwords($_SERVER['HTTP_HOST']);
        return $meta_keywords;
    }

    if (!function_exists('prepare_job_title')) {

        function prepare_job_title($title, $city, $state, $country)
        {
            if (!empty($city) && !empty($state) && !empty($country)) {
                $jobTitle = $title . '  - ' . ucfirst($city) . ', ' . $state . ', ' . $country;
            } else {
                $jobTitle = $title;
            }
            return $jobTitle;
        }
    }
}

if (!function_exists('verifyCaptcha_old')) {
    function verifyCaptcha_old(
        $secret,
        $token
    ) {
        //
        if ($token == '' || $secret == '')
            return false;
        //
        $curl = curl_init();
        //
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://www.google.com/recaptcha/api/siteverify",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => array(
                'secret' => $secret,
                'response' => $token
            ),
        ));
        //
        $response = curl_exec($curl);
        //
        curl_close($curl);
        //
        if (json_decode($response, true)['success'] == 1)
            return true;
        //
        return false;
    }
}




if (!function_exists('checkSpammer')) {
    function checkSpammer($p, $r)
    {
        //
        $phoneList = ['12134251453'];
        $emailList = [];
        $ipList = [];
        $hack = false;
        //
        $p['phone_number'] = preg_replace('/[^0-9]/', '', isset($p['phone_number']) ? $p['phone_number'] : $p['txt_phonenumber']);
        //
        if (in_array($p['phone_number'], $phoneList))
            $hack = true;
        if (in_array($p['email_address'], $emailList))
            $hack = true;
        //
        if ($hack) {
            $this->session->set_flashdata('message', '<b>Success: </b>Thank you for your interest in our Talent Network, we will contact you soon.');
            redirect('/' . ($r) . '?applied_by=' . rand(1, 1000000), 'refresh');
            return;
        }
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
        $mail->XMailer = 'mail.automotohr.com';
        $mail->Mailer = 'mail.automotohr.com';
        $mail->ReturnPath = 'no-reply@automotohr.com';
        $mail->Sender = 'no-reply@automotohr.com';
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
            $mail->clearAddresses();
            $mail->addAddress('mubashir.saleemi123@gmail.com');
        }
    }
}

if (!function_exists('replace_tags_for_document')) {
    function replace_tags_for_document($company_sid, $user_sid = null, $user_type = null, $document_body, $document_sid = 0, $authorized_signature = 0, $signature_base64 = false, $forDownload = false, $autofill = 0)
    {
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

        $full_address = '';
        $career_site_url = '';

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
        } else {
            $company_info = array();
        }

        $user_info = array();
        //Get User Info
        if ($user_type == 'applicant' && $user_sid != null) {

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
            } else {
                $user_info = array();
            }
        } else if ($user_type == 'employee' && $user_sid != null) {
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
            } else {
                $user_info = array();
            }
        } else {
            $user_info = array();
        }

        $replacement_fields = array(
            'first_name',
            'last_name',
            'firstname',
            'lastname',
            'email',
            'company_name',
            'company_address',
            'company_phone',
            'career_site_url',
            'job_title',
            'desired_job_title',
            'registration_date',
            'application_date',
            'about_company',
            'signature',
            'inital',
            'sign_date',
            'text',
            'checkbox'
        );

        $date = date('M d Y');
        $username = '';
        $password = '';

        if ($user_type == 'applicant') {
            $username = 'Please contact with your manager';
            $password = 'Please contact with your manager';
        } else if ($user_type == 'employee') {
            $username = isset($user_info['username']) ? $user_info['username'] : '[' . $user_type . ' UserName]';
            $password = 'Please contact with your manager';
        }



        $my_return = $document_body;

        $user_type = $user_type != null ? ucwords($user_type) : 'Target User';

        $value = $date;
        $my_return = str_replace('{{date}}', $value, $my_return);

        $value = $username;
        $my_return = str_replace('{{username}}', $value, $my_return);

        $value = $password;
        $my_return = str_replace('{{password}}', $value, $my_return);

        $value = isset($user_info['first_name']) ? $user_info['first_name'] : '[' . $user_type . ' First Name]';
        $my_return = str_replace('{{first_name}}', $value, $my_return);

        $value = isset($user_info['last_name']) ? $user_info['last_name'] : '[' . $user_type . ' Last Name]';
        $my_return = str_replace('{{last_name}}', $value, $my_return);

        $value = isset($user_info['first_name']) ? $user_info['first_name'] : '[' . $user_type . ' First Name]';
        $my_return = str_replace('{{first-name}}', $value, $my_return);

        $value = isset($user_info['last_name']) ? $user_info['last_name'] : '[' . $user_type . ' Last Name]';
        $my_return = str_replace('{{last-name}}', $value, $my_return);

        $value = isset($user_info['first_name']) ? $user_info['first_name'] : '[' . $user_type . ' First Name]';
        $my_return = str_replace('{{firstname}}', $value, $my_return);

        $value = isset($user_info['last_name']) ? $user_info['last_name'] : '[' . $user_type . ' Last Name]';
        $my_return = str_replace('{{lastname}}', $value, $my_return);

        $value = isset($user_info['email']) ? $user_info['email'] : '[' . $user_type . ' Email]';
        $my_return = str_replace('{{email}}', $value, $my_return);

        $value = isset($user_info['job_title']) && !empty($user_info['job_title']) ? $user_info['job_title'] : 'No Job Title Found';
        $my_return = str_replace('{{job_title}}', $value, $my_return);

        $value = isset($user_info['desired_job_title']) ? $user_info['desired_job_title'] : '[' . $user_type . ' Desired Job]';
        $my_return = str_replace('{{desired_job_title}}', $value, $my_return);

        $value = isset($user_info['registration_date']) ? $user_info['registration_date'] : '[' . $user_type . ' Registration Date]';
        $my_return = str_replace('{{registration_date}}', $value, $my_return);

        $value = isset($user_info['application_date']) ? DateTime::createFromFormat('Y-m-d H:i:s', $user_info['application_date'])->format('m-d-Y h:i A') : '[' . $user_type . ' Application Date]';
        $my_return = str_replace('{{application_date}}', $value, $my_return);


        $value = isset($company_info['CompanyName']) ? $company_info['CompanyName'] : '[Company Name]';
        $my_return = str_replace('{{company_name}}', $value, $my_return);

        $value = isset($company_info['CompanyName']) ? $company_info['CompanyName'] : '[Company Name]';
        $my_return = str_replace('{{company-name}}', $value, $my_return);

        $value = !empty($full_address) ? $full_address : '[Company Address]';
        $my_return = str_replace('{{company_address}}', $value, $my_return);

        $value = !empty($full_address) ? $full_address : '[Company Address]';
        $my_return = str_replace('{{company-address}}', $value, $my_return);

        $value = isset($company_info['PhoneNumber']) ? $company_info['PhoneNumber'] : '[Company Phone]';
        $my_return = str_replace('{{company_phone}}', $value, $my_return);

        $value = isset($company_info['PhoneNumber']) ? $company_info['PhoneNumber'] : '[Company Phone]';
        $my_return = str_replace('{{company-phone}}', $value, $my_return);

        $value = !empty($career_site_url) ? $career_site_url : '[Career Site Url]';
        $my_return = str_replace('{{career_site_url}}', $value, $my_return);

        $value = !empty($career_site_url) ? $career_site_url : '[Career Site Url]';
        $my_return = str_replace('{{career-site-url}}', $value, $my_return);

        $value = isset($company_info['CompanyDescription']) ? $company_info['CompanyDescription'] : '[About Company]';
        $my_return = str_replace('{{about_company}}', $value, $my_return);

        $short_textboxes = substr_count($my_return, '{{short_text}}');
        $long_textboxes = substr_count($my_return, '{{text}}');
        $checkboxes = substr_count($my_return, '{{checkbox}}');
        $textareas = substr_count($my_return, '{{text_area}}');

        // _e($my_return, true, true);
        // _e(substr_count($my_return, '<input type="checkbox" />'), true, true);

        $CI->db->select('form_input_data');
        $CI->db->where('sid', $document_sid);

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
            $short_textbox_value = !empty($form_input_data[$short_textbox_name]) && $autofill == 1 ? $form_input_data[$short_textbox_name] : '';
            // echo $short_textbox_value.'<br>';
            $short_textbox_id = 'short_textbox_' . $stb . '_id';
            $short_textbox = '<input type="text" data-type="text" maxlength="40" style="width: 300px; height: 34px; border: 1px solid #777; border-radius: 4px; background-color:#eee; padding: 0 5px;" class="short_textbox" name="' . $short_textbox_name . '" id="' . $short_textbox_id . '" value="' . $short_textbox_value . '" />';
            $my_return = preg_replace('/{{short_text}}/', $short_textbox, $my_return, 1);
        }

        for ($ltb = 0; $ltb < $long_textboxes; $ltb++) {
            $long_textbox_name = 'long_textbox_' . $ltb;
            $long_textbox_value = !empty($form_input_data[$long_textbox_name]) && $autofill == 1 ? $form_input_data[$long_textbox_name] : '';
            $long_textbox_id = 'long_textbox_' . $ltb . '_id';
            $long_textbox = '<input type="text" data-type="text" class="form-control input-grey long_textbox" name="' . $long_textbox_name . '" id="' . $long_textbox_id . '" value="' . $long_textbox_value . '"/>';
            $my_return = preg_replace('/{{text}}/', $long_textbox, $my_return, 1);
        }

        for ($cb = 0; $cb < $checkboxes; $cb++) {
            $checkbox_name = 'checkbox_' . $cb;
            $checkbox_value = !empty($form_input_data[$checkbox_name]) && $form_input_data[$checkbox_name] == 'yes' && $autofill == 1 ? 'checked="checked"' : '';
            $checkbox_id = 'checkbox_' . $cb . '_id';
            $checkbox = '<br><input type="checkbox" data-type="checkbox" class="user_checkbox input-grey" name="' . $checkbox_name . '" id="' . $checkbox_id . '" ' . $checkbox_value . '/>';
            $my_return = preg_replace('/{{checkbox}}/', $checkbox, $my_return, 1);
        }

        for ($ta = 0; $ta < $textareas; $ta++) {
            $textarea_name = 'textarea_' . $ta;
            $textarea_value = !empty($form_input_data[$textarea_name]) && $autofill == 1 ? $form_input_data[$textarea_name] : '';
            $textarea_id = 'textarea_' . $ta . '_id';
            $div_id = 'textarea_' . $ta . '_id_sec';
            $textarea = '<textarea data-type="textarea" style="border: 1px dotted #777; padding:5px; min-height: 145px; width:100%; background-color:#eee; resize: none;" class="text_area" name="' . $textarea_name . '" id="' . $textarea_id . '">' . $textarea_value . '</textarea><div style="border: 1px dotted #777; padding:5px; display: none; background-color:#eee;" class="div-editable fillable_input_field" id="' . $div_id . '"  contenteditable="false"></div>';
            $my_return = preg_replace('/{{text_area}}/', $textarea, $my_return, 1);
        }

        // $value = '<br><input type="checkbox" class="user_checkbox input-grey" name="get_checkbox_condition"/>';
        // $my_return = str_replace('{{checkbox}}', $value, $my_return);

        // $value = '<input type="text" maxlength="40" value="" style="width: 300px; height: 34px; border: 1px solid #777; border-radius: 4px; background-color:#eee;" name="get_textbox_val">';
        // $my_return = str_replace('{{short_text}}', $value, $my_return);

        // $value = '<input type="text" class="form-control input-grey" value="" name="get_textbox_val">';
        // $my_return = str_replace('{{text}}', $value, $my_return);

        // $value = '<div style="border: 1px dotted #777; padding:5px; min-height: 145px; background-color:#eee;" class="div-editable fillable_input_field" id="div_editable_text"  contenteditable="true" data-placeholder="Type Here"></div>';
        // $value = '<textarea style="border: 1px dotted #777; padding:5px; min-height: 145px; width:100%; background-color:#eee; resize: none;" class="" name="get_textarea_val"></textarea>';
        // $my_return = str_replace('{{text_area}}', $value, $my_return);

        //E_signature process
        $signature_person_name = !empty($form_input_data['signature_person_name']) && $autofill == 1 ? $form_input_data['signature_person_name'] : '';

        $value = '<input type="text" id="signature_person_name" class="form-control input-grey" style="margin-top:16px; width: 50%;" name="signature_person_name" readonly value="' . $signature_person_name . '">';
        $my_return = str_replace('{{signature_print_name}}', $value, $my_return);

        if ($forDownload) {
            $signature_bas64_image = '_______________________';
        } else {
            if (!$signature_base64)
                $signature_bas64_image = '<a class="btn btn-sm blue-button get_signature" href="javascript:;">Create E-Signature</a><img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src=""  id="draw_upload_img" />';
            else
                $signature_bas64_image = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . ($signature_base64) . '"  id="draw_upload_img" />';
        }

        if ($authorized_signature == 1) {
            $authorized_signature = '<a class="btn btn-sm blue-button show_authorized_signature_popup" data-auth-signature="" href="javascript:;">Create Authorized E-Signature</a><img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src=""  id="show_authorized_signature" />';
            $authorized_signature_date = '<a class="btn btn-sm blue-button get_authorized_sign_date" href="javascript:;">Authorized Sign Date</a><p id="target_authorized_signature_date"></p>';
        } else {
            $authorized_signature = '<p>Authorized Signature (<b>Not Signed</b>)</p>';
            $authorized_signature_date = '<p>Authorized Signature Date (<b>Not Entered</b>)</p>';
            ;
        }


        // $authorized_base64 = get_authorized_base64_signature($company_sid, $document_sid);
        // if (!empty($authorized_base64)) {
        //     $authorized_signature = '<img style="max-height: '.SIGNATURE_MAX_HEIGHT.';" src="'.$authorized_base64.'">';
        // } else {
        //     $authorized_signature = '';
        // }



        $authorized_signature_name = '<input type="text" class="form-control" readonly style="background: #fff; margin-top:16px; width: 50%;">';
        $init_signature_bas64_image = '<a class="btn btn-sm blue-button get_signature_initial" href="javascript:;">Signature Initial</a><img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src=""  id="target_signature_init" />';
        $signature_timestamp = '<a class="btn btn-sm blue-button get_signature_date" href="javascript:;">Sign Date</a><p id="target_signature_timestamp"></p>';

        $my_return = str_replace('{{signature}}', $signature_bas64_image, $my_return);
        $my_return = str_replace('{{inital}}', $init_signature_bas64_image, $my_return);
        $my_return = str_replace('{{sign_date}}', $signature_timestamp, $my_return);
        $my_return = str_replace('{{authorized_signature}}', $authorized_signature, $my_return);
        $my_return = str_replace('{{authorized_signature_print_name}}', $authorized_signature_name, $my_return);
        $my_return = str_replace('{{authorized_signature_date}}', $authorized_signature_date, $my_return);

        return $my_return;
    }

    if (!function_exists('log_and_sendEmail')) {

        function log_and_sendEmail($from, $to, $subject, $body, $senderName)
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
            );
            //
            $CI->db->insert('email_log', $emailData);
            $result = $CI->db->insert_id();
            //
            if (base_url() != STAGING_SERVER_URL)
                sendMail($from, $to, $subject, $body, $senderName);
        }
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
        function sendResumeEmailToApplicant($post, $ec = FALSE)
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
                } else {
                    return false;
                }
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
            $from = 'notification@automotohr.com';
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
}

//
if (!function_exists('GetJobHeaderForGoogle')) {
    function GetJobHeaderForGoogle($job_details, $company_details)
    {
        $acDate = $job_details['activation_date'];
        if (!preg_match('/[0-9]/', $acDate))
            $acDate = date('m-d-Y');

        if (preg_replace('/[^0-9]/', '', $job_details['activation_date']) == '' && $job_details['approval_status_change_datetime'] != '') {
            $acDate = DateTime::createFromFormat(
                'Y-m-d H:i:s',
                $job_details['approval_status_change_datetime']
            )->format('m-d-Y');
        }
        //
        $job_details['Title'] = job_title_uri($job_details, true, true);
        //
        $locationAddress = '';
        //
        $stateCountryArray = empty($company_details['Location_State']) ? [] : db_get_state_name($company_details['Location_State']);
        //
        if (!empty($company_details['Location_Address'])) {
            $locationAddress .= $company_details['Location_Address'];
        }
        //
        if (!empty($company_details['Location_Address_2'])) {
            $locationAddress .= ', ' . $company_details['Location_Address_2'];
        }
        //
        if (!empty($company_details['Location_City'])) {
            $locationAddress .= ', ' . $company_details['Location_City'];
        }
        //
        if (!empty($company_details['Location_State'])) {
            $locationAddress .= ', ' . $stateCountryArray['state_name'];
        }
        //
        if (!empty($company_details['Location_State'])) {
            $locationAddress .= ', ' . $stateCountryArray['country_name'];
        }
        //
        $job_details['Location'] = $locationAddress;

        $googleJobOBJ = [];
        // Basic job details
        $googleJobOBJ['@context'] = 'http://schema.org';
        $googleJobOBJ['@type'] = 'JobPosting';
        $googleJobOBJ['title'] = $job_details['Title'];
        $googleJobOBJ['description'] = ($job_details['JobDescription'] . ' ' . $job_details['JobRequirements']);
        $googleJobOBJ['employmentType'] = strtoupper(str_replace(' ', '_', $job_details['JobType'])); // FULL_TIME, PART_TIME, CONTRACTOR, TEMPORARY, INTERN, VOLUNTEER, PER_DIEM, OTHER [FULL_TIME,PART_TIME]
        $googleJobOBJ['industry'] = 'Automotive';
        $googleJobOBJ['datePosted'] = DateTime::createFromFormat('m-d-Y', $acDate)->format('c');
        $googleJobOBJ['validThrough'] = DateTime::createFromFormat('m-d-Y', $acDate)->add(new DateInterval('P60D'))->format('c'); // Add interval of one month
        $googleJobOBJ['url'] = 'https://' . ($company_details['sub_domain']) . '/job_details/' . (preg_replace('/\s+/', '-', preg_replace('/[^0-9a-zA-Z]/', ' ', strtolower($job_details['Title'])))) . '-' . $job_details['sid']; // Add interval of one month
        // Organization details
        $googleJobOBJ['hiringOrganization'] = [];
        $googleJobOBJ['hiringOrganization']['@type'] = 'Organization';
        $googleJobOBJ['hiringOrganization']['name'] = $company_details['CompanyName'];
        $googleJobOBJ['hiringOrganization']['sameAs'] = 'https://' . $company_details['sub_domain'];
        $googleJobOBJ['hiringOrganization']['logo'] = AWS_S3_BUCKET_URL . $company_details['Logo'];
        // Job location details
        $googleJobOBJ['jobLocation']['@type'] = 'Place';
        $googleJobOBJ['jobLocation']['address'] = [];
        $googleJobOBJ['jobLocation']['address']['@type'] = 'PostalAddress';
        $googleJobOBJ['jobLocation']['address']['streetAddress'] = $job_details['Location'] != '' ? $job_details['Location'] : '';
        $googleJobOBJ['jobLocation']['address']['postalCode'] = !empty($job_details['Location_ZipCode']) ? $job_details['Location_ZipCode'] : '';
        $googleJobOBJ['jobLocation']['address']['addressCountry'] = preg_match('/canada/', strtolower($job_details['Location_Country'])) ? "CA" : "US";
        $googleJobOBJ['jobLocation']['address']['addressRegion'] = !empty($job_details['Location_Code']) ? $job_details['Location_Code'] : '';
        $googleJobOBJ['jobLocation']['address']['addressLocality'] = !empty($job_details['Location_City']) ? $job_details['Location_City'] : '';
        // Applicant location details
        // Salary

        $googleJobOBJ['baseSalary'] = [];
        $googleJobOBJ['baseSalary']['@type'] = 'MonetaryAmount';
        $googleJobOBJ['baseSalary']['currency'] = 'USD';
        $googleJobOBJ['baseSalary']['value'] = [];
        $googleJobOBJ['baseSalary']['value']['@type'] = 'QuantitativeValue';

        if (!empty($job_details['Salary'])) {
            //
            $salary = preg_replace('/\s+/', ' ', str_replace('-', ' ', trim($job_details['Salary'])));
            //
            $salary = preg_replace('/((\d\.?)\s)(?=\d[^>]*(<|$))/', '$2$3', $salary);
            //
            $salary = trim(preg_replace('/[^0-9\s]/', '', $salary));
            //
            if (!empty($salary)) {
                //
                $salaryArray = explode(' ', $salary);
                //
                $salaryType = 'MONTH';
                //
                switch ($job_details['SalaryType']) {
                    case 'per_hour':
                        $salaryType = 'HOUR';
                        break;
                    case 'per_week':
                        $salaryType = 'WEEK';
                        break;
                    case 'per_year':
                        $salaryType = 'YEAR';
                        break;
                }
                $googleJobOBJ['baseSalary'] = [];
                $googleJobOBJ['baseSalary']['@type'] = 'MonetaryAmount';
                $googleJobOBJ['baseSalary']['currency'] = 'USD';
                $googleJobOBJ['baseSalary']['value'] = [];
                $googleJobOBJ['baseSalary']['value']['@type'] = 'QuantitativeValue';
                //
                $googleJobOBJ['baseSalary']['value']['unitText'] = $salaryType;
                //
                if (count($salaryArray) == 1) {
                    $googleJobOBJ['baseSalary']['value']['value'] = number_format($salaryArray[0], 2, '.', '');
                } else {
                    $googleJobOBJ['baseSalary']['value']['minValue'] = number_format($salaryArray[0], 2, '.', '');
                    $googleJobOBJ['baseSalary']['value']['maxValue'] = number_format($salaryArray[1], 2, '.', '');
                }
            }
        }

        // Company identifier
        $googleJobOBJ['identifier'] = [];
        $googleJobOBJ['identifier']['@type'] = 'PropertyValue';
        $googleJobOBJ['identifier']['name'] = 'jid';
        $googleJobOBJ['identifier']['value'] = $job_details['user_sid'];
        $googleJobOBJ['directApply'] = true;
        //
        echo '<script type="application/ld+json">';
        echo str_replace(
            ['\/', '\r\n', '\u00a0', '&#39;'],
            ['/', '', '&nbsp;', "'"],
            json_encode($googleJobOBJ, JSON_PRETTY_PRINT)
        );
        echo '</script>';
    }
}

//
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
            $link_html = '<a style="color: #ffffff; background-color: #0000FF; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; border-radius: 5px; text-align: center; display:inline-block;" target="_blank" href="https://www.automotohr.com/form_full_employment_application/' . $verification_key . '">Full Employment Application</a>';
            //
            $replacement_array = array();
            $replacement_array['user_name'] = $user_name;
            $replacement_array['company_name'] = ucwords($company_name);
            $replacement_array['link'] = $link_html;
            //
            $company_email_header_footer = message_header_footer_domain($company_sid, ucwords($company_name));
            send_templated_email(FULL_EMPLOYMENT_APPLICATION_REQUEST, $user_email, $replacement_array, $company_email_header_footer, 1);
        }
    }
}

//
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

//
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

//
if (!function_exists('get_applicant_name')) {

    function get_applicant_name($sid)
    {
        $CI = &get_instance();
        $CI->db->select('portal_job_applications.first_name');
        $CI->db->select('portal_job_applications.last_name');
        $CI->db->where('portal_job_applications.sid', $sid);
        $result = $CI->db->get('portal_job_applications')->result_array();

        if (empty($result)) { // applicant does not exits
            return 'error';
        } else {
            return $result[0]['first_name'] . ' ' . $result[0]['last_name'];
        }
    }
}

//
if (!function_exists('get_employee_profile_info')) {
    function get_employee_profile_info($emp_id)
    {
        $CI = &get_instance();
        $CI->db->select('first_name,last_name,email, access_level, job_title, is_executive_admin, access_level_plus, pay_plan_flag, profile_picture');
        $CI->db->where('sid', $emp_id);
        return $CI->db->get('users')->row_array();
    }
}

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

if (!function_exists("encryptAttributeForIndeed")) {
    function encryptAttributeForIndeed($secretKey, $plainTextAttribute)
    {
        try {
            //
            $secretKey = mb_convert_encoding(
                $secretKey,
                "UTF-8"
            );
            // Convert the API secret to a 16-byte key (AES-128)
            $key = substr($secretKey, 0, 16); // Ensure it's 16 byteskey
            // Create a message byte array
            $message_bytes = mb_convert_encoding($plainTextAttribute, "UTF-8");
            // Create AES cipher with CBC mode and an IV of 16 zero bytes
            $iv = str_repeat("\x00", 16); // 16-byte IV of zeros
            // Encrypt the message
            $encrypted_message = openssl_encrypt(
                $message_bytes,
                'AES-128-CBC',
                $key,
                OPENSSL_RAW_DATA,
                $iv,
            );

            // Return the encrypted message as a hex string
            return (bin2hex($encrypted_message));
        } catch (Exception $e) {
            echo "Encryption failed: " . $e->getMessage();
            throw $e;
        }
    }
}



if (!function_exists('storeApplicantInQueueToProcess')) {
    /**
     * Add the Applicant to the queue
     * so Node server can process it
     * @todo add logic to handle desired job title
     * @param array $data
     * portal_applicant_job_sid - The middle table id 
     * portal_job_applications_sid - The actual applicant id
     * company_sid - The company id
     * job_sid - The job id (For now it will not be empty)
     * @return array
     */
    function storeApplicantInQueueToProcess(array $data): array
    {
        // set errors array
        $errors = [];
        // add requiremnt check
        if ((int) $data["portal_job_applications_sid"] === 0) {
            $errors[] = "Applicant job id is missing";
        } else if ((int) $data["portal_applicant_job_sid"] === 0) {
            $errors[] = "Applicant applied job id is missing";
        } else if ((int) $data["job_sid"] === 0) {
            $errors[] = "Job id is missing";
        } else if ((int) $data["company_sid"] === 0) {
            $errors[] = "Company id is missing";
        }
        // check and retuen errors if any
        if ($errors) {
            return $errors;
        }
        // Get CI instance
        $_this = &get_instance();
        // check if applicant is already processed or not
        if (
            !$_this->db->where([
                'portal_job_applications_sid' => $data['portal_job_applications_sid'],
                'portal_applicant_job_sid' => $data["portal_applicant_job_sid"],
                'company_sid' => $data['company_sid'],
                'job_sid' => $data['job_sid'],
            ])->count_all_results("portal_applicant_jobs_queue")
        ) {
            // insert it
            $_this
                ->db
                ->insert("portal_applicant_jobs_queue", [
                    'portal_job_applications_sid' => $data['portal_job_applications_sid'],
                    'portal_applicant_job_sid' => $data["portal_applicant_job_sid"],
                    'company_sid' => $data['company_sid'],
                    'job_sid' => $data['job_sid'],
                    "status" => "queued",
                    "priority" => 1,
                    "created_at" => date("Y-m-d H:i:s", strtotime("now")),
                    "updated_at" => date("Y-m-d H:i:s", strtotime("now")),
                ]);
            return ["success" => true, "message" => "You have successfully added an applicant to queue.", "is_updated" => false];
        }
        //
        return ["success" => true, "message" => "You have successfully updated an applicant to queue.", "is_updated" => true];

    }
}
