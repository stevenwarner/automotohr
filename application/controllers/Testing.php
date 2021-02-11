<?php defined('BASEPATH') or exit('No direct script access allowed');

class Testing extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('hr_documents_management_model');
        $this->load->model('test_model');
    }
function te(){
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once(APPPATH . 'libraries/phpmailer/PHPMailerAutoload.php');
        $mail = new PHPMailer;
        
        $mail->From = 'notifications@automotohr.com';
        $mail->FromName = 'Notifications';

            $mail->addReplyTo('notifications@automotohr.com');
        
        //
        $mail->addAddress('mubashir.saleemi123@gmail.com');
        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);
        $mail->Subject = 'test';
        $mail->Body = 'ignore it';
        
        //
        mailAWSSES($mail, 'mubashir.saleemi123@gmail.com');
		
		echo '<pre>';
		$mail->SMTPDebug = 3	;
		_e($mail, true);
		//
		_e( $mail->send(), true);
 

     //  sendMail('accounts@automotohr.com', 'Steven@Automotohr.com', 'Email', 'Please ignore this message', 'Accounts @ AutomotoHR');
//         sendMail('accounts@automotohr.com', 'mubashir.saleemi123@gmail.com', 'tes', 'sdadsas', 'Accounts');
//         sendMail('notifications@automotohr.com', 'mubashir.saleemi123@gmail.com', 'tes', 'sdadsas', 'Accounts');
       //  echo sendMail('notifications@automotohr.com', 'mubashir.saleemi@outlook.com', 'tes', 'sdadsas', 'Accounts');
        die('asdasd');
    }
function fc(){
	
$d1 = date('Y-m-d', strtotime('now'));
$d2 = date('Y-m-d', strtotime('2020-02-13 +160 days'));

echo $d1, '<br />', $d2;

if($d1 < $d2){
	die('unser');
}

die('dd');
}

    function index(){
        //
        $this->load->library('Jwt');
        //
        $jwt = new Jwt();
        //
        $jwt->setSecret('d');
        //
        $jwt->setHeader([
            'typ' => 'JWT',
            'alg' => 'HS256'
        ]);
        //
        $jwt->setPayload([
            'iat' => strtotime('now'),
            'jti' => base64_encode(mcrypt_create_iv(32)),
            'iss' => 'automotohr',
            'nbf' => strtotime('+10 minutes'),
            'exp' => strtotime('+20 minutes'),
            'data' => [
                'user_id' => 1,
                'role' => 'admin'
            ]
        ]);
        //
        _e(Jwt::encode(), true);
    }



    function seeDiff(){
        //
        $companySid = 57;
        //
        $a = 
        $this->db
        ->select('download_required, signature_required, acknowledgment_required, sid')
        ->where('company_sid', $companySid)
        ->where('is_specific', 0)
        ->get('documents_management');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        if(!count($b)) return '';
        //
        $r = [];
        //
        foreach($b as $d){
            $a = 
            $this->db
            ->select('*')
            ->where('document_sid', $d['sid'])
            ->get('documents_assigned');
            //
            $c = $a->result_array();
            $a = $a->free_result();
            //
            if(count($c)) {
                //
                foreach($c as $e){
                    //
                    isDocumentCompletedCheck($e);
                    //
                    if($e['ra']['isNoAction']){
                        //
                        // $this->db
                        // ->where('sid', $e['sid'])
                        // ->update('documents_assigned', [
                        //     'acknowledgment_required' => $d['acknowledgment_required'],
                        //     'download_required' => $d['download_required'],
                        //     'signature_required' => $d['signature_required']
                        // ]);
                        $r[$d['sid']] = [
                            'D_acknowledgment_required' => $d['acknowledgment_required'],
                            'D_download_required' => $d['download_required'],
                            'D_signature_required' => $d['signature_required'],
                            'acknowledgment_required' => $e['acknowledgment_required'],
                            'download_required' => $e['download_required'],
                            'signature_required' => $e['signature_required']
                        ];
                    }
                }
            }
        }
        //
        _e($r, true);
        exit(0);
    }



























    function fbshare()
    {
        echo '
        <div id="fb-root"></div>
        <script>
            window.fbAsyncInit = function() {
            FB.init({appId: "1688129328143598", status: true, cookie: true,
            xfbml: true});
            };
            (function() {
                var e = document.createElement("script"); 
                e.async = true;
                e.src = document.location.protocol +
                "//connect.facebook.net/en_US/all.js";
                document.getElementById("fb-root").appendChild(e);
            }());
        </script><script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
$("#share_button").click(function(e){
e.preventDefault();
FB.ui(
{
method: "feed",
name: "Automotive Service Technician Blossom Chevrolet  - Indianapolis, Indiana, United States",
link: "https://blossom-chevrolet.automotohr.com/job_details/28423",
picture: "https://automotohrattachments.s3.amazonaws.com/5232-logo--dh1.png",
caption: "Automotive Service Technician Blossom Chevrolet  - Indianapolis, Indiana, United States",
description: "Blossom Chevrolet continues to grow, and we are in immediate need of experienced Automotive Mechanics and Technician in our S...",
message: ""
});
});
});
</script>

<button id="share_button">Sahre me</button>';
    }

    function captcha()
    {
        //
        if (isset($_POST) && sizeof($_POST)) {
            _e($_POST, true);
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://www.google.com/recaptcha/api/siteverify",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => array('secret' => '6Le58fgUAAAAACe95YxO4kEKfy27wHcQZvAmgOq5', 'response' => $_POST['g-recaptcha-response']),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            if (json_decode($response, true)['success'] == 1) echo 's';
            else echo 'b';

            _e($e, true);
        }

        echo '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Document</title>
            <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit"
        async defer>
    </script>
        </head>
        <body>

        <form action="' . (current_url()) . '" method="POST">
        <input type="text" name="name">
          <div id="html_element"></div>
          <br>
          <input type="submit" value="Submit">
        </form>

        <script>
        var onloadCallback = function() {
            widgetId1 = grecaptcha.render("html_element", {
                "sitekey" : "6Le58fgUAAAAAI3jc5nnXC_gIrSoA4c3r-7uGeyO",
                "theme" : "light"
            });
        };
        </script>

            
        </body>
        </html>';
    }

    public function fix_offer_letter($company_sid)
    {
        $processed_ids = array();
        $all_assigned_offer_letters = $this->hr_documents_management_model->get_all_assigned_offer_letters($company_sid);

        foreach ($all_assigned_offer_letters as $key => $offer_letter) {
            $user_sid = $offer_letter['user_sid'];
            $user_type = $offer_letter['user_type'];
            $processing_sid = '';

            if ($user_type == 'employee') {
                $processing_sid = 'e_' . $user_sid;
            } else {
                $processing_sid = 'a_' . $user_sid;
            }

            if (!in_array($processing_sid, $processed_ids)) {
                $all_user_assigned_offer_letters = $this->hr_documents_management_model->get_all_user_assigned_offer_letter($company_sid, $user_sid, $user_type);

                if (count($all_user_assigned_offer_letters) > 1) {
                    $count = count($all_user_assigned_offer_letters);

                    foreach ($all_user_assigned_offer_letters as $key => $user_assigned_offer_letter) {
                        if (--$count <= 0) {
                            break;
                        }

                        $previous_assigned_sid = $user_assigned_offer_letter['sid'];
                        $already_moved = $this->hr_documents_management_model->check_offer_letter_moved($previous_assigned_sid, 'offer_letter');

                        if ($already_moved == 'no') {
                            $user_assigned_offer_letter['doc_sid'] = $previous_assigned_sid;
                            $user_assigned_offer_letter['moved_by_script'] = 1;
                            unset($user_assigned_offer_letter['sid']);
                            $this->hr_documents_management_model->insert_documents_assignment_record_history($user_assigned_offer_letter);
                        }

                        $this->hr_documents_management_model->disable_current_processing_offer_letter($previous_assigned_sid);
                    }
                }
            }

            array_push($processed_ids, $processing_sid);
        }

        die('Process completed ........');
    }

    function shiftrtoa()
    {
        $a = $this->db
            ->select('sid, registration_date, joined_at')
            ->where('parent_sid <> ', 0)
            // ->limit(2)
            ->get('users');
        //
        $b = $a->result_array();
        $a->free_result();
        //
        $e['Total'] = sizeof($b);
        $e['Existed'] = 0;
        $e['Done'] = 0;
        foreach ($b as $k => $v) {
            //
            if ($v['joined_at'] != '') {
                $e['Existed']++;
                continue;
            }
            if ($v['registration_date'] == '') {
                $e['Existed']++;
                continue;
            }
            $this->db
                ->where('sid', $v['sid'])
                ->update('users', array(
                    'joined_at' => DateTime::createFromFormat("Y-m-d H:i:s", $v['registration_date'])->format("Y-m-d")
                ));
            $e['Done']++;
        }
        _e($e, true);
    }

    //
    function fixcategory()
    {
        $a = $this->db
            ->select('sid, jobCategory')
            ->where('jobCategory REGEXP "[a-zA-Z]"', null)
            ->get('portal_job_listings');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        if (!sizeof($b)) die('nf');
        //
        $c = array();
        $fn = array();
        //
        foreach ($b as $k => $v) {
            $t = explode(',', $v['jobCategory']);
            $f = array();
            foreach ($t as $k0 => $v0) {
                $v0 = strtolower($v0);
                if (!preg_match('/[a-zA-Z]/', $v0)) {
                    $f[] = $v0;
                    continue;
                }
                //
                if (isset($c[$t[$k0]])) {
                    $f[] = $c[$t[$k0]];
                    continue;
                }
                // Lets get the category id from database
                $a = $this->db
                    ->select('sid, LOWER(value) as value')
                    ->where('LOWER(value)', $v0)
                    ->where('field_sid', 198)
                    ->get('listing_field_list');
                //
                $d = $a->row_array();
                $a = $a->free_result();
                //
                if (!sizeof($d)) continue;
                //
                $f[] = $d['sid'];
                $c[$d['value']] = $d['sid'];
            }
            //
            $ids = implode(',', $f);
            // Update the data
            $u = $this->db
                ->where('sid', $v['sid'])
                ->update('portal_job_listings', array(
                    'jobCategory' => $ids
                ));
            // Save for testing purpose
            $fn[] = array(
                'sid' => $v['sid'],
                'updateStatus' => $u,
                'old' => $v['jobCategory'],
                'new' => $ids
            );
        }
        // Show what has changed
        echo '<pre>';
        print_r($fn);
        die;
    }

    //
    function move_assigned()
    {

        $a = $this->db
            ->select('sid, document_sid, document_type')
            ->where('document_sid <> ', 0)
            ->from('documents_assigned')
            ->get();
        //
        $b = $a->result_array();
        //
        $a = $a->free_result();
        //
        foreach ($b as $k => $v) {
            //
            if ($v['document_type'] == 'offer_letter') {
                $a = $this->db
                    ->select('acknowledgment_required, download_required, signature_required')
                    ->where('sid', $v['document_sid'])
                    ->get('offer_letter');
                //
                $o = $a->row_array();
                $a->free_result();
            } else {
                $a = $this->db
                    ->select('acknowledgment_required, download_required, signature_required')
                    ->where('sid', $v['document_sid'])
                    ->get('documents_management');
                //
                $o = $a->row_array();
                $a->free_result();
            }
            //
            $u = $this->db
                ->where('sid', $v['sid'])
                ->update('documents_assigned', [
                    'acknowledgment_required' => $o['acknowledgment_required'],
                    'download_required' => $o['download_required'],
                    'signature_required' => $o['signature_required']
                ]);
            _e($o, true);
            _e($u, true);
        }
    }



    //
    function moveTO()
    {


        function parse_csv($csv_string, $delimiter = ",", $skip_empty_lines = true, $trim_fields = true)
        {
            return array_map(
                function ($line) use ($delimiter, $trim_fields) {
                    return array_map(
                        function ($field) {
                            return str_replace('!!Q!!', '"', utf8_decode(urldecode($field)));
                        },
                        $trim_fields ? array_map('trim', explode($delimiter, $line)) : explode($delimiter, $line)
                    );
                },
                preg_split(
                    $skip_empty_lines ? ($trim_fields ? '/( *\R)+/s' : '/\R+/s') : '/\R/s',
                    preg_replace_callback(
                        '/"(.*?)"/s',
                        function ($field) {
                            return urlencode(utf8_encode($field[1]));
                        },
                        $enc = preg_replace('/(?<!")""/', '!!Q!!', $csv_string)
                    )
                )
            );
        }

        $content = file_get_contents('glockner.csv');

        $content = parse_csv($content);

        unset($content[0]);
        //
        array_values($content);

        $d = [];
        $name = 'NathanAllard';

        foreach ($content as $c) {
            //
            $name = trim($c[3] . $c[4]);
            //
            // if ($name !== 'NathanAllard') continue;
            //
            if (!isset($d[$name])) {
                //
                $d[$name] = [
                    'Name' => trim($c[3] . ' ' . $c[4]),
                    'Slug' => strtolower($name),
                    'TimeOffs' => []
                ];
            }
            //
            $policyName = $c[5] . (date('Y', strtotime($c[11])));
            //
            $d[$name]['TimeOffs'][] = [
                'Name' => $c[5],
                'AllowedTime' => ($c[10] * 60),
                'Consumed' => $c[6],
                'Department' => trim($c[1]),
                'Employer Name' => trim($c[2]),
                'Start Date' => date('m/d/Y', strtotime(trim($c[7]))),
                'End Date' => date('m/d/Y', strtotime(trim($c[8]))),
                'Status' => trim($c[9]),
                'Created Date' => date(
                    'Y-m-d 00:00:00',
                    strtotime(trim($c[11]))
                ),
                'Approval Date' => date(
                    'Y-m-d 00:00:00',
                    strtotime(trim($c[12]))
                ),
                'Note' => trim($c[13]),
            ];
            // if (!isset($d[$name]['TimeOffs'][$policyName])) {
            // }
            // //
            // $d[$name]['TimeOffs'][$policyName]['Consumed'] += $c[6];
        }
        //
        // _e(
        //     array_column(
        //         $d,
        //         'Name'
        //     )
        // );
        //
        // _e($d, true, true);
        $failed = $success = 0;
        $e = [];
        $f = [];
        //
        $file = fopen('glockner_new.csv', 'w');
        fputcsv($file, ["Employee Email Address", "Policy", "Partial Leave", "Note", "Requested From Date", "Requested To Date", "Requested Hours", "Request Status", "Reason", "Approver Comment"]);
        //
        foreach ($d as $timeoff) {
            //
            // Get employee id
            $r = $this->db->select('email')
                ->from('users')
                ->where('LOWER(username) = "' . ($timeoff['Slug']) . '"', null, false)
                ->where('parent_sid', 11276)
                ->limit(1)
                ->get()
                ->row_array();
            //
            if ($r && count($r)) {
                foreach ($timeoff['TimeOffs'] as $p) {
                    fputcsv($file, [
                        $r['email'],
                        $p['Name'],
                        'No',
                        '',
                        $p['Start Date'],
                        $p['End Date'],
                        $p['Consumed'],
                        $p['Status'],
                        $p['Note'],
                        '',
                    ]);
                }
            } else {
                $f[] = $timeoff['Name'];
            }
        }
        //
        _e($f);
        _e(file_get_contents('glockner_new.csv'), true);
        // _e($success, true);
    }


    //
    function ffea_fix(){
        $a = $this->db
        ->select('sid, applicant_sid')
        ->where('applicant_sid IS NOT NULL', null)
        ->where('parent_sid', 9180)
        ->get('users');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        foreach($b as $key => $user){
            $b[$key] = TRUE;
            // Check if row exists
            if(
                !$this->db
                ->where('user_sid', $user['sid'])
                ->where('user_type', 'employee')
                ->count_all_results('form_full_employment_application')
            ){
                $b[$key] = FALSE;
            }
        }
        //
        // foreach($b as $key => $user){
        //     $b[$key] = TRUE;
        //     // Check if row exists
        //     if(
        //         !$this->db
        //         ->where('user_sid', $user['sid'])
        //         ->where('user_type', 'employee')
        //         ->count_all_results('form_full_employment_application')
        //     ){
        //         $b[$key] = FALSE;
        //     //    $a = $this->db
        //     //    ->where('user_sid', $user['applicant_sid'])
        //     //    ->where('user_type', 'applicant')
        //     //    ->get('form_full_employment_application');
        //     //    //
        //     //    $c = $a->row_array();
        //     //    $a = $a->free_result();
        //     //    //
        //     //    if(!count($c)) continue;
        //     //    //
        //     //    unset($c['sid']);
        //     //    $c['user_type'] = 'employee';
        //     //    $c['user_sid'] = $user['sid'];
        //     //    $this->db
        //     //    ->insert('form_full_employment_application', $c);
        //     }
        // }
        _e($b, true);
        die('sas');
    }

    function set_profile_picture () {
        //
        $message = '';
        //
        $companies_form_list = $this->test_model->get_all_companies_form_list();
        //
        if(!empty($companies_form_list)) {
            //
            foreach ($companies_form_list as $key => $form) {
                //
                $form_sid = $form['sid'];
                $company_sid = $form['company_sid'];
                //
                $data_to_insert = array();
                $data_to_insert['job_fairs_forms_sid'] = $form_sid;
                $data_to_insert['company_sid'] = $company_sid;
                $data_to_insert['field_id'] = 'profile_picture';
                $data_to_insert['field_name'] = 'Upload a Profile Picture (.jpg .jpe .jpeg .png .gif)';
                $data_to_insert['field_type'] = 'default';
                $data_to_insert['field_display_status'] = 0;
                $data_to_insert['is_required'] = 0;
                $data_to_insert['question_type'] = 'file';
                $data_to_insert['sort_order'] = 11;
                //
                $this->test_model->insert_profile_field($data_to_insert);
                //
            }
            //
            $message = 'Profile picture add against all companies';
            //
        } else {
            //
            $message = 'No Company found in DB';
            //
        }
        //
        die($message);
    }

    function set_is_specific_type () {
        //
        $message = '';
        //
        $specific_document_list = $this->test_model->get_all_specific_document();
        //
        if(!empty($specific_document_list)) {
            //
            foreach ($specific_document_list as $key => $user) {
                //
                $user_type = $this->test_model->find_user_type_in_company($user['company_sid'], $user['is_specific']);
                //
                $this->test_model->update_user_type($user['sid'], $user_type);
                //
            }
            //
            $message = 'All specific document user type is fixed!';
            //
        } else {
            //
            $message = 'No Specific Document Found';
            //
        }
        //
        die($message);
    }

    // https://automotohrattachments.s3.amazonaws.com/0057-profile_picture-512161-REi.jpg
    // 
    function thumbGenerator(){
        //
        error_reporting(E_ALL);
        //
        $r = [];
        //
        $url = AWS_S3_BUCKET_URL;
        //
        /*
		$a = $this->db
        ->select('sid, Logo, profile_picture')
        ->where('parent_sid', 0)
        ->get('users')
        ->result_array();
        //
        if(!count($a)) exit(0);

        //
        foreach($a as $v){
            //
            if(!empty($v['Logo'])){
                $r[] = generateThumb(
                    $url.$v['Logo'], [
                        'url' => $url
                    ]
                );
                usleep(100);
            }
            //
            if(!empty($v['profile_picture'])){
                $r[] = generateThumb(
                    $url.$v['profile_picture'], [
                        'url' => $url
                    ]
                );
                usleep(100);
            }
        } */
		//
		$jobs = $this->db
        ->select('pictures, ListingLogo')
        ->where('active', 1)
        ->order_by('activation_date', 'DESC')
        //->limit(50)
        ->get('portal_job_listings')
        ->result_array();
        //
        if(!count($jobs)) exit(0);
        //
        //
        foreach($jobs as $v){
            //
            if(!empty($v['pictures'])){
                $r[] = generateThumb(
                    $url.$v['pictures'], [
                        'url' => $url
                    ]
                );
                usleep(100);
            }
            //
            if(!empty($v['ListingLogo'])){
                $r[] = generateThumb(
                    $url.$v['ListingLogo'], [
                        'url' => $url
                    ]
                );
                usleep(100);
            }
        }
        //
        _e(count($jobs));
        _e(count($r), true, true);
    }

	//
    function jobs(){
        //
        $jobs = $this->db->select('
            portal_job_listings.sid,
            portal_job_listings.status,
            portal_job_listings.active,
            portal_job_listings.approval_status,
            portal_job_listings.approval_status_change_datetime,
            portal_job_listings.organic_feed,
            portal_job_listings.activation_date,
            portal_job_listings.deactivation_date,
            portal_job_listings.expiration_date,
            users.sid as company_id,
            users.active as company_active,
            users.has_job_approval_rights,
            users.is_paid
        ')
        ->join('users', 'users.sid = portal_job_listings.user_sid', 'inner')
        ->get('portal_job_listings')
        ->result_array();
        //
        header('Content-Type: application/json');
        echo json_encode($jobs);
    }
}
