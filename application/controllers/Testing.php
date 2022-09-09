<?php defined('BASEPATH') || exit('No direct script access allowed');

class Testing extends CI_Controller
{
    //
    public function __construct()
    {
        parent::__construct();
        // Call the model
        $this->load->model("test_model", "tm");
    }


    public function sync_header_video_overlay()
    {
        $this->db->select('company_id, meta_key, meta_value');
        $this->db->where('meta_key', 'site_settings');
        $result = $this->db->get('portal_themes_meta_data')->result_array();

        if (!empty($result)) {
            foreach ($result as $row) {
                $meta_values = unserialize($row['meta_value']);
                if (isset($meta_values['enable_header_overlay'])) {
                    $data =  array(
                        'header_video_overlay' => $meta_values['enable_header_overlay']
                    );

                    $this->db->where('user_sid', $row['company_id']);
                    $this->db->update('portal_employer', $data);
                }
            }
        }
    }

    public function pipescript($secret_key)
    {
        //
        $creds = getCreds('AHR');
        //
        $hostname = $creds->DB->Host;
        $username = $creds->DB->User;
        $password = $creds->DB->Password;
        $database = $creds->DB->Database;
        //

        $dbhandle = mysqli_connect($hostname, $username, $password, $database) or die('Unable to Connect to MySql');
        //
        $query_string = "select * from private_message where identity_key = '$secret_key' and outbox = 1";
        $result = mysqli_query($dbhandle, $query_string);
        $messageData = mysqli_fetch_assoc($result);
        //
        $notifications_configuration = mysqli_query($dbhandle, "SELECT `private_message` FROM `notifications_emails_configuration` where `company_sid` = '" . $messageData["company_sid"] . "' LIMIT 1");
        $notifications_info = $notifications_configuration->fetch_assoc();
        //
        if (!empty($notifications_info) && $notifications_info["private_message"] == 1) {
            //
            $from_name = '';
            $to_name = '';
            $to_email = '';
            $company_name = '';
            $notification_subject = 'Private Message Notification';
            $notification_date = date('Y-m-d H:i:s');
            //
            if (is_numeric($messageData["to_id"])) {
                $employee_result = mysqli_query($dbhandle, "SELECT `first_name`,`last_name` FROM `users` where `sid` = '" . $messageData["to_id"] . "' LIMIT 1");
                $employee_info = $employee_result->fetch_assoc();
                //
                $from_name = $employee_info["first_name"] . " " . $employee_info["last_name"];
            } else {
                $to_type = $messageData["to_type"];
                //
                if ($to_type == "applicant") {
                    $applicant_result = mysqli_query($dbhandle, "SELECT `first_name`,`last_name` FROM `portal_job_applications` where `email` = '" . $messageData["to_id"] . "' LIMIT 1");
                    $applicant_info = $applicant_result->fetch_assoc();
                    //
                    $from_name = $applicant_info["first_name"] . " " . $applicant_info["last_name"];
                } else if ($to_type == "employer") {
                    $from_name = $messageData["to_id"];
                }
            }
            //
            if (is_numeric($messageData["from_id"])) {
                $employee_result = mysqli_query($dbhandle, "SELECT `first_name`,`last_name`,`email` FROM `users` where `sid` = '" . $messageData["from_id"] . "' LIMIT 1");
                $employee_info = $employee_result->fetch_assoc();
                //
                $to_name = $employee_info["first_name"] . " " . $employee_info["last_name"];
                $to_email = $employee_info["email"];
            } else {
                $from_type = $messageData["from_type"];
                //
                if ($from_type == "applicant") {
                    $applicant_result = mysqli_query($dbhandle, "SELECT `first_name`,`last_name`,ay`email` FROM `portal_job_applications` where `email` = '" . $messageData["from_id"] . "' LIMIT 1");
                    $applicant_info = $applicant_result->fetch_assoc();
                    //
                    $to_name = $applicant_info["first_name"] . " " . $applicant_info["last_name"];
                    $to_email = $applicant_info["email"];
                } else if ($from_type == "employer") {
                    $to_name = $messageData["to_id"];
                    $to_email = $messageData["to_id"];
                }
            }
            //
            $company_result = mysqli_query($dbhandle, "SELECT `CompanyName` FROM `users` where `sid` = '" . $messageData["company_sid"] . "' LIMIT 1");
            $company_info = $company_result->fetch_assoc();
            //
            $company_name = $company_info["CompanyName"];
            //
            $notification_email = '<div class="content" style="font-size: 100%; line-height: 1.6em; display: block; max-width: 1000px; margin: 0 auto; padding: 0; position:relative"><div style="width:100%; float:left; padding:5px 20px; text-align:center; box-sizing:border-box; background-color:#0000FF;"><h2 style="color:#fff;">' . $company_name . '</h2></div>  <div class="body-content" style="width:100%; float:left; padding:20px 0; box-sizing:padding-box;">'
                . '<h2 style="width:100%; margin:0 0 20px 0;">Dear ' . $to_name . ',</h2>'
                . '</br> You have a message in your AutomotoHR inbox from <strong>' . $from_name . '</strong>'
                . '</div><div class="footer" style="width:100%; float:left; background-color:#0000FF; padding:20px 30px; box-sizing:border-box;"><div style="float:left; width:100%; "><p style="color:#fff; float:left; text-align:center; font-style:italic; line-height:normal; font-family: "Open Sans", sans-serif; font-weight:600; font-size:14px;"></p></div></div></div>';
            //
            mail($to_email, $notification_subject, $notification_email);
            //   
            $query_log = "insert into email_log (date,subject,email,message,username,temp_id) VALUES ('" . $notification_date . "','" . $notification_subject . "','" . $to_email . "','" . $notification_email . "','" . $from_name . "','nil')";
            mysqli_query($dbhandle, $query_log);
            //
        }
    }




    public function convertMcrypttoOpenssl()
    {

        // for tbale form_document_credit_card_authorization
        $filedsToselect = "cc_type,cc_holder_name,cc_number,cc_expiration_month,cc_expiration_year";
        $this->db->select($filedsToselect);
        $result = $this->db->get('form_document_credit_card_authorization')->result_array();
        if (!empty($result)) {
            foreach ($result as $row) {

                //Opensslencrypte
                $cc_type_encrypted = opensslEncryptString(decode_string($row['cc_type']));
                $cc_holder_name_encrypted = opensslEncryptString(decode_string($row['cc_holder_name']));
                $cc_number_encrypted = opensslEncryptString(decode_string($row['cc_number']));
                $cc_expiration_month_encrypted = opensslEncryptString(decode_string($row['cc_expiration_month']));
                $cc_expiration_year_encrypted = opensslEncryptString(decode_string($row['cc_expiration_year']));

                //OpensslDecrypt
                $cc_type_decrypted = opensslDecryptString($cc_type_encrypted);
                $cc_holder_name_decrypted = opensslDecryptString($cc_holder_name_encrypted);
                $cc_number_decrypted = opensslDecryptString($cc_number_encrypted);
                $cc_expiration_month_decrypted = opensslDecryptString($cc_expiration_month_encrypted);
                $cc_expiration_year_decrypted = opensslDecryptString($cc_expiration_year_encrypted);

            }
        }

        // For Users table 
        $this->db->select('key');
        $result = $this->db->get('users')->result_array();
        if (!empty($result)) {
            foreach ($result as $row) {
                $key_encrypted = opensslEncryptString(decode_string($row['key']));
                //OpensslDecrypt
                $key_decrypted = opensslDecryptString($key_encrypted);
            }
        }
    }
}
